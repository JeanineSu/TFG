"use strict";

import data from './JSON/datos.json' assert { type: 'json' };

let aciertos = 0;
let temporizador;
let comodin_publico_usado = false;
let comodin_50 = false;
let musica = false;

const quizContainer = $('#quizContainer');
const quizForm = $('#quizForm');

const dinero = {
  1: '1000 €',
  2: '5000 €',
  3: '10.000 €',
  4: '50.000 €',
  5: '100.000 €',
  6: '200.000 €',
  7: '300.000 €',
  8: '350.000 €',
  9:  '400.000 €',
  10: '500.000 €',
  11: '600.000 €',
  12: '700.000 €',
  13: '800.000 €',
  14: '900.000 €',
  15: '1.000.000 €',
}

$('#temporizador').hide()
$('#aciertos').hide()
$('#dinero').hide()

$(document).ready(function(){

  $('#start').click(function(){
    $(".musica").show()
    $('#temporizador').show()
    $('#aciertos').show()
    $("#botones_juego").show()

    $(this).hide()

    pintarPreguntas(1);

  });


  $("#play-button").click(function(){

    if(musica){
      musica = false;
      $("#intro").get(0).pause()
      $("#play-button").css("background-color", "red");
    }else{
      musica = true;
      $("#intro").get(0).play()
      $("#play-button").css("background-color", "green");
    }

    var $p = $("<p>").text("Música " + (musica ? "activada" : "desactivada"));

    $(".musica").append($p);

    setTimeout(function() {
      $p.remove();
    }, 2500);
  })

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
  startTimer(300000, '#tiempo');
  $(".posible_respuesta").remove();

  const maximo_preguntas = Object.keys(dinero).length;

  $("#nivel_pregunta").show().find("span").text(dificultad)

  const preguntas = getQuestion(dificultad, respuesta);

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
        alert("ENHORABUENA HAS GANADO " + dinero[aciertos])
        //$('#submit').click()
        window.location.href = "ganar.php";
      }else{
        pintarPreguntas(dificultad)
      }

      return;
    }else{

      if(musica){

        let intro = $("#intro").get(0);

        if(!intro.paused){
          intro.pause()
        }

        $("#incorrecto").get(0).play();
      }
      respuestaSeleccionada.css('background-color', 'red');
      respuestaSeleccionada.next('label').css('background-color', 'red');
      // alert("HAS PERDIDO")
      setTimeout(function() {
        window.location.href = "perder.php";
       // $('#submit').click()
      }, 1000);
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

    var $p = $("<p class='votacion'>").text("El publico está votando...");
    $("#quizContainer").append($p);
    $(".votacion:not(:last)").remove();

    setTimeout(function() {
      $(".active").remove()
      $p.remove();
      comodin_publico_usado = true;
      pintarPreguntas(dificultad, respuesta_correcta)
    }, 2500);
  })

  $("#comodin_llamada").click(function(event){
    event.preventDefault();
    $(this).remove()

    let posibleRespuesta = aplicarComodinLlamada(respuestas, respuesta_correcta, 50)
    posibleRespuesta = $('<p class="posible_respuesta">'+posibleRespuesta+'</p>');
    quizForm.append(posibleRespuesta)
    $(".posible_respuesta:not(:last)").remove();

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

  const numeroAleatorio = Math.floor(Math.random() * 100);
  var mensaje;
  if(numeroAleatorio < porcentajeAcierto ){
    mensaje = "Estoy bastante seguro de que la respuesta es ";
    return mensaje + respuesta_correcta;
  }

  const respuestasAleatorias = respuestas.filter(respuesta => respuesta !== respuesta_correcta);
  const posicionAleatoria = Math.floor(Math.random() * respuestasAleatorias.length);
  const respuesta_final =  respuestasAleatorias[posicionAleatoria];

  mensaje = ["Estoy 100% seguro de que la respuesta es " + respuesta_final,
    "Uf, que dificil, pues conozco la respuesta lo siento",
    "Estoy un poco al 50/50 entre " + respuesta_final + " y " + respuesta_correcta + ', pero me decanto más por ' + respuesta_final]

  return mensaje[posicionAleatoria]
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

function startTimer(segundos, element){
  var timerElement = $(element);

  temporizador = setInterval(function() {
    segundos--;
    timerElement.text(segundos);

    if (segundos <= 0) {
      clearInterval(temporizador);
      // alert("SE HA ACABADO EL TIEMPO, HAS PERDIDO.")
      $('#submit').click()
      window.location.href = "perder.php";
      return;
    }
  }, 1000);
}