"use strict";

import data from './JSON/datos.json' assert { type: 'json' };

let aciertos = 0;
let temporizador;
let comodin_publico_usado = false;
let comodin_50 = false;
let usuario;


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
  10:'1.000.000 €',
  11: '',
  12: '',
  13: '',
  14: '',
  15: '',
}

$('#temporizador').hide()
$('#aciertos').hide()
$('#dinero').hide()

$(document).ready(function(){
  $('#start').click(function(){
    usuario = $("#usuario").text();
    $('#temporizador').show()
    $('#aciertos').show()
    $("#botones_juego").show()

    $(this).hide()

    pintarPreguntas(1);
  });

  $("#plantarse").click(function(event){
    event.preventDefault();

    const maximo_preguntas = Object.keys(dinero).length;
    if (aciertos < (maximo_preguntas / 2) ){
      alert("Lo siento, pero te vas sin nada")
      location.reload()
      return;
    }

    else if(aciertos > (maximo_preguntas / 2) && aciertos < (maximo_preguntas - 3)){
      alert("Enhorabuena, usuario, te llevas " + parseInt(dinero[aciertos]) / 2 + "K");
      location.reload()
      return;
    }

    alert("Enhorabuena, tu premio es de " + dinero[aciertos] );
    return;
  })
})


function pintarPreguntas(dificultad, respuesta = null){
  clearInterval(temporizador);
  startTimer(10);

  const maximo_preguntas = Object.keys(dinero).length;

  $("#nivel_pregunta").show().find("input#nivel_pregunta_actual").val(dificultad)

  let preguntas = getQuestion(dificultad, respuesta);

  // while(preguntas == undefined){
  //   dificultad++;
  //   preguntas = getQuestion(dificultad, respuesta);
  // }

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

  respuestas.sort(ordenarRespuestasAleatoriamente);

  let questionElement = $('<div class="active" id="pregunta_'+id+'">');
  questionElement.append('<p>' + pregunta + '</p>');
  $.each(respuestas, function(j){
    var input = $('<input name="respuestas" class="respuesta'+(j+1)+'">').attr({
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

      dificultad = aciertos + 1;
      $("#pregunta_"+id).removeClass('active').hide();

      if(dificultad > maximo_preguntas){
        alert("ENHORABUENA, " + usuario + " HAS GANADO " + dinero[aciertos])
      }else{
        pintarPreguntas(dificultad)
      }

      return;
    }else{
      respuestaSeleccionada.css('background-color', 'red');
      respuestaSeleccionada.next('label').css('background-color', 'red');
      alert("HAS PERDIDO")
      $('#submit').click()
    }
  });

  $("#comodin_50").click(function(event){
    event.preventDefault();
    $(this).remove()

    $(".active").remove()
    comodin_50 = true;

    pintarPreguntas(dificultad, respuesta_correcta)
  })

  $("#comodin_publico").click(function(event){
    event.preventDefault();
    $(this).remove()
    $(".active").remove()

    comodin_publico_usado = true;
    pintarPreguntas(dificultad, respuesta_correcta)
  })

  $("#comodin_llamada").click(function(event){
    event.preventDefault();

    var pregunta_actual = [];

    $('div.active:last-child input[type="radio"]').each(function() {
      var value = $(this).val();
      pregunta_actual.push(value);
    });

    const posible_respuesta = aplicarComodinLlamada(pregunta_actual, respuesta_correcta, 60);
    // // console.log(posible_respuesta)
    // // pintarPreguntas(dificultad, respuesta_correcta)

    console.log("Yo creo que la respuesta correcta es " + posible_respuesta);

    // $("div.active").remove()

  })
}

function ordenarRespuestasAleatoriamente(){
  return Math.random() - 0.5;
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


function getQuestion(dificultad, respuesta = null){
  const preguntas = data.preguntas;
  const preguntasFiltradas = preguntas.filter(function(pregunta) {

    if(respuesta != null){
      return pregunta.respuesta_correcta === respuesta;
    }

    return pregunta.dificultad === dificultad;
  });

  const posicionAleatoria = Math.floor(Math.random() * preguntasFiltradas.length);

  return preguntasFiltradas[posicionAleatoria];
}

function startTimer(segundos){
  var timerElement = $('#tiempo');

  temporizador = setInterval(function() {
    segundos--;
    timerElement.text(segundos);

    if (segundos <= 0) {
      clearInterval(temporizador);
      alert("SE HA ACABADO EL TIEMPO, HAS PERDIDO. " + usuario)
      $('#submit').click()
      return;
    }
  }, 1000);
}