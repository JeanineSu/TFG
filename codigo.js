"use strict";

let aciertos = 0;
let temporizador;
let comodin_publico_usado = false;
let comodin_50 = false;

const quizContainer = $('#quizContainer');
const quizForm = $('#quizForm');

const dinero = {
  1: '1000 €',
  2: '10.000 €',
  3: '50.000 €',
  4: '100.000 €',
  5: '200.000 €',
  6: '300.000 €',
  7: '500.000 €',
  8: '750.000 €',
  9: '800.000 €',
  10:'1.000.000 €'
}

$('#temporizador').hide()
$('#aciertos').hide()
$('#dinero').hide()

$(document).ready(function(){
  $('#start').click(function(){
    $('#temporizador').show()
    $('#aciertos').show()
    $("#botones_juego").show()

    $(this).hide()
    pintarPreguntas(1);
  });

  $("#plantarse").click(function(event){
    event.preventDefault();
    if (aciertos < 3){
      alert("Lo siento pero te vas sin nada")
      location.reload()
      return;
    }
  
    alert("Enhorabuena, tu premio es de " + dinero[aciertos]);
  })
})

function pintarPreguntas(dificultad, respuesta = null){

  $.getJSON('./JSON/datos.json', function(data) {
    // Manipula los datos del archivo JSON aquí
    clearInterval(temporizador);
    startTimer(30);
    let nivel;

    $("#nivel_pregunta").show().find("span").text(nivel)
    console.log(data)
    var preguntas = data.preguntas;

    const preguntasFiltradas = preguntas.filter(function(pregunta) {

      if(respuesta != null){
        return pregunta.respuesta_correcta === respuesta;
      }

      return pregunta.dificultad === dificultad;
    });

    const posicionAleatoria = Math.floor(Math.random() * preguntasFiltradas.length);

    preguntas = preguntasFiltradas[posicionAleatoria];

    const pregunta = preguntas.pregunta;
    var respuestas = preguntas.respuestas;
    const id = preguntas.id;
    const respuesta_correcta = preguntas.respuesta_correcta;

    if (comodin_50) {
      respuestas = aplicarComodin50(respuestas, respuesta_correcta);
      comodin_50 = false;
    }

    let porcentajes;
    if(comodin_publico_usado){
      porcentajes = aplicarComodinPublico(respuestas);
    }

    let questionElement = $('<div class="active" id="pregunta_'+id+'">');
    questionElement.append('<p>' + pregunta + '</p>');
    $.each(respuestas, function(j){
      var input = $('<input>').attr({
        type: 'radio',
        value: respuestas[j]
      });
      var label = $('<label>').text(respuestas[j]);

      if(comodin_publico_usado){
        label = $('<label>').text(respuestas[j]  + ' (' + porcentajes[j] + '%)');
      }

      questionElement.append(input);
      questionElement.append(label);
      questionElement.append('<br>');
    })

    if(comodin_publico_usado){
      comodin_publico_usado = false;
    }

    quizContainer.append(questionElement);

    questionElement.on('change', 'input[type="radio"]', function() {
      var respuestaSeleccionada = $(this);
      const respuestaUsuario = $(this).val();

      if(respuestaUsuario === respuesta_correcta){
        respuestaSeleccionada.css('background-color', 'green');
        respuestaSeleccionada.next('label').css('background-color', 'green');
        aciertos++;
        $('#dinero').show()
        $('#dinero_acumulado').text(dinero[aciertos])
        $("#aciertos_acumulados").text(aciertos)

        nivel = aciertos + 1;
        console.log(nivel)
        $("#pregunta_"+id).removeClass('active').hide();
        pintarPreguntas(nivel)

        // if(aciertos <= 10){
        // }else{
        //   alert("ENHORABUENA HAS GANADO " + dinero[aciertos])
        //   return;
        // }
      }else{
        respuestaSeleccionada.css('background-color', 'red');
        respuestaSeleccionada.next('label').css('background-color', 'red');
        // alert("HAS PERDIDO")
      }
    });

    $("#comodin_50").click(function(event){
      event.preventDefault();
      $(this).remove()

      $(".active").remove()
      comodin_50 = true;

      if(nivel == undefined){
        nivel = aciertos + 1;
      }
      pintarPreguntas(nivel, respuesta_correcta)
    })

    $("#comodin_publico").click(function(event){
      event.preventDefault();
      $(this).remove()
      $(".active").remove()

      console.log("Nivel en este comodin" + nivel)

      comodin_publico_usado = true;
      if(nivel == undefined){
        nivel = aciertos + 1;
      }
      pintarPreguntas(nivel, respuesta_correcta)
    })

    $("#comodin_llamada").click(function(event){
      event.preventDefault();
      $(".active").remove()

      if(nivel == undefined){
        nivel = aciertos + 1;
      }
      var pregunta_actual = [];

      $('div.active input[type="radio"]').each(function() {
        var value = $(this).val();

        pregunta_actual.push(value);
      });

      $(this).remove()
      const posible_respuesta = aplicarComodinLlamada(pregunta_actual, respuesta_correcta, 60);
      console.log(posible_respuesta)
      pintarPreguntas(nivel, respuesta_correcta)

      alert("Yo creo que la respuesta correcta es " + posible_respuesta);
    })
  })
      .done(function() {
        console.log('Carga del archivo JSON exitosa');
      })
      .fail(function(jqxhr, textStatus, error) {
        var err = textStatus + ', ' + error;
        console.log('Error al cargar el archivo JSON: ' + err);
      });
}

function aplicarComodin50(respuestas, respuesta_correcta){
  let opcionesRespuestas = [respuesta_correcta];

  // Obtener una respuesta aleatoria diferente a la respuesta correcta
  const respuestasAleatorias = respuestas.filter(respuesta => respuesta !== respuesta_correcta);
  const respuestaAleatoria = respuestasAleatorias[Math.floor(Math.random() * respuestasAleatorias.length)];
  opcionesRespuestas.push(respuestaAleatoria);
  return opcionesRespuestas;
}

function aplicarComodinPublico(respuestas) {
  let porcentajes = [];
  let porcentajeRestante = 100;

  for (let i = 0; i < respuestas.length - 1; i++) {
    const porcentaje = Math.floor(Math.random() * porcentajeRestante);
    porcentajes.push(porcentaje);
    porcentajeRestante -= porcentaje;
  }

  porcentajes.push(porcentajeRestante);

  return porcentajes;
}

function aplicarComodinLlamada(respuestas, respuesta_correcta, porcentajeAcierto){

  const numeroAleatorio = Math.random() * 100;
  if(numeroAleatorio < porcentajeAcierto ){
    return respuesta_correcta;
  }

  const respuestasAleatorias = respuestas.filter(respuesta => respuesta !== respuesta_correcta);
  
  let posicionAleatoria = Math.floor(Math.random() * respuestasAleatorias.length);

  return respuestasAleatorias[posicionAleatoria];
}

function startTimer(segundos){
  var timerElement = $('#tiempo');

  temporizador = setInterval(function() {
    segundos--;
    timerElement.text(segundos);

    if (segundos <= 0) {
      clearInterval(temporizador);
      // alert("HAS PERDIDO")
    }
  }, 1000);
}