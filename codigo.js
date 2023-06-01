"use strict";

import data from './JSON/datos.json' assert { type: 'json' };
//declarar variables
let aciertos = 0;
let temporizador;
let comodin_publico_usado = false;
let comodin_50 = false;
let musica = false;

const quizContainer = $('#quizContainer');//Crear un contenedor quiz
const quizForm = $('#quizForm');//Formulario del quiz
//cantidades de dinero por nivle
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

$('#temporizador').hide() // Ocultar el elemento con el id "temporizador"
$('#aciertos').hide() // Ocultar el elemento con el id "aciertos"
$('#dinero').hide() // Ocultar el elemento con el id "dinero"

$(document).ready(function(){
// Función que se ejecuta cuando el documento está listo
  $('#start').click(function(){
    // Acción cuando se hace clic en el elemento con id "start"
    $(".musica").show()
    // Mostrar elementos con la clase "musica"
    $('#temporizador').show()
    // Mostrar elementos con la clase "temporizador"
    $('#aciertos').show()
    // Mostrar elementos con la clase "aciertos"
    $("#botones_juego").show()
    // Mostrar elementos con la clase "botones_juego"

    $(this).hide() // Ocultar el elemento en el que se hizo clic (con id "start")

    pintarPreguntas(1); // Llamar a la función pintarPreguntas con el argumento 1

  });


  $("#play-button").click(function(){
    // Acción cuando se hace clic en el elemento con id "play-button"
    if(musica){
      // Si la música está activada
      musica = false;// Desactivar la música
      $("#intro").get(0).pause()// Pausar la reproducción de la música
      $("#play-button").css("background-color", "red");// Cambiar el color de fondo del botón a rojo
    }else{
      // Si la música está desactivada
      musica = true;// Activar la música
      $("#intro").get(0).play()// Iniciar la reproducción de la música
      $("#play-button").css("background-color", "green");// Cambiar el color de fondo del botón a verde
    }

    var $p = $("<p>").text("Música " + (musica ? "activada" : "desactivada"));
// Agregar el párrafo al elemento con la clase "musica"
    $(".musica").append($p);

    setTimeout(function() {
      $p.remove();
    }, 2500);// Eliminar el párrafo después de 2500 milisegundos (2.5 segundos)
  })

  $("#plantarse").click(function(event){
    event.preventDefault();// Evita el comportamiento predeterminado del evento de clic

    const maximo_preguntas = Object.keys(dinero).length; // Obtiene la cantidad máxima de preguntas desde el objeto 'dinero'

    // Comprueba si el número de aciertos es menor que la mitad de la cantidad máxima de preguntas
    if (aciertos < (maximo_preguntas / 2) ){
      alert("Lo siento, pero te vas sin nada") // Muestra un mensaje de alerta indicando que el usuario se va sin premio
      location.reload()// Recarga la página actual
      return;// Sale de la función
    }
// Comprueba si el número de aciertos es mayor que la mitad de la cantidad máxima de preguntas y menor que la cantidad máxima de preguntas menos 3
    else if(aciertos > (maximo_preguntas / 2) && aciertos < (maximo_preguntas - 3)){
      // Muestra un mensaje de alerta indicando la mitad del premio
      alert("Enhorabuena, usuario, te llevas " + parseInt(dinero[aciertos]) / 2 + "K");
      location.reload()// Muestra un mensaje de alerta indicando el premio obtenido
      return;
    }

    alert("Enhorabuena, tu premio es de " + dinero[aciertos] );
    return;
  })
})


function pintarPreguntas(dificultad, respuesta = null){
  clearInterval(temporizador);// Detiene el temporizador
  startTimer(30, '#tiempo');// Inicia un nuevo temporizador de 5 minutos
  $(".posible_respuesta").remove();// Elimina elementos con la clase "posible_respuesta"

  const maximo_preguntas = Object.keys(dinero).length;// Obtiene la cantidad máxima de preguntas desde el objeto 'dinero'

  $("#nivel_pregunta").show().find("span").text(dificultad)// Muestra el nivel de la pregunta actual

  const preguntas = getQuestion(dificultad, respuesta);// Obtiene las preguntas correspondientes a la dificultad

  const pregunta = preguntas.pregunta;// Obtiene la pregunta actual
  var respuestas = preguntas.respuestas;// Obtiene las respuestas posibles
  const id = preguntas.id; // Obtiene el ID de la pregunta
  const respuesta_correcta = preguntas.respuesta_correcta;// Obtiene la respuesta correcta

  if (comodin_50) {
    respuestas = aplicarComodin50(respuestas, respuesta_correcta);// Aplica el comodín 50/50 para reducir las opciones de respuesta
    comodin_50 = false;// Marca el comodín 50/50 como utilizado
  }

  let porcentajes;
  if(comodin_publico_usado){
    porcentajes = aplicarComodinPublico(respuestas);// Obtiene los porcentajes de votación del comodín del público
  }

  respuestas.sort(ordenarRespuestasAleatoriamente);// Ordena las respuestas de forma aleatoria

  let questionElement = $('<div class="active" id="pregunta_'+id+'">');
  questionElement.append('<p>' + pregunta + '</p>');

// Dividir las respuestas en dos columnas
  var column1 = $('<div class="columna">');
  var column2 = $('<div class="columna">');
  questionElement.append(column1);
  questionElement.append(column2);

  $.each(respuestas, function(j){
    var input = $('<input name="respuestas" class="respuesta'+(j+1)+'">').attr({
      type: 'radio',
      value: respuestas[j]
    });
    var label = $('<label>').text(respuestas[j]);

    if(comodin_publico_usado){
      label = $('<label>').text(respuestas[j]  + ' (' + porcentajes[j] + '%)');
    }

    // Agregar las respuestas a las columnas correspondientes
    if (j < 2) {
      column1.append(input);
      column1.append(label);
      column1.append('<br>');
    } else {
      column2.append(input);
      column2.append(label);
      column2.append('<br>');
    }
  });


  if(comodin_publico_usado){
    comodin_publico_usado = false;// Marca el comodín del público como utilizado
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
        // alert("ENHORABUENA HAS GANADO " + dinero[aciertos])
        //$('#submit').click()
        window.location.href = "ganar.php"; // Redirige al usuario a la página de ganar.php
      }else{
        pintarPreguntas(dificultad)// Vuelve a pintar las preguntas con la nueva dificultad
      }

      return;
    }else{

      if(musica){

        let intro = $("#intro").get(0);// Reproduce el sonido de respuesta

        if(!intro.paused){
          intro.pause()
        }

        $("#incorrecto").get(0).play();// Redirige al usuario a la página de perder.php después de un segundo
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
    comodin_50 = true;// Marca el comodín 50/50 como utilizado

    pintarPreguntas(dificultad, respuesta_correcta)// Vuelve a pintar las preguntas con la respuesta correcta
  })

  $("#comodin_publico").click(function(event){
    event.preventDefault();
    $(this).remove()

    var $p = $("<p class='votacion'>").text("El publico está votando...");
    $p.addClass("votacion"); // Agregar la clase "votacion"
    $p.css({
      "color": "white",
    });

    $("#quizContainer").append($p);
    $(".votacion:not(:last)").remove();

    setTimeout(function() {
      $(".active").remove()
      $p.remove();
      comodin_publico_usado = true;// Marca el comodín del público como utilizado
      pintarPreguntas(dificultad, respuesta_correcta)// Vuelve a pintar las preguntas con la respuesta correcta
    }, 2500);
  })

  $("#comodin_llamada").click(function(event){
    event.preventDefault();
    $(this).remove()

    let posibleRespuesta = aplicarComodinLlamada(respuestas, respuesta_correcta, 50)// Aplica el comodín de llamada telefónica y obtiene una posible respuesta
    posibleRespuesta = $('<p class="posible_respuesta">'+posibleRespuesta+'</p>');
    quizForm.append(posibleRespuesta)
    $(".posible_respuesta:not(:last)").remove();

  })
}

function ordenarRespuestasAleatoriamente(){// Función para ordenar las respuestas de forma aleatoria
  return Math.random() - 0.5;
}

function aplicarComodin50(respuestas, respuesta_correcta){// Función para aplicar el comodín 50/50
  let opcionesRespuestas = [respuesta_correcta];

  // Obtener una respuesta aleatoria diferente a la respuesta correcta
  const respuestasAleatorias = respuestas.filter(respuesta => respuesta !== respuesta_correcta);
  const respuestaAleatoria = respuestasAleatorias[Math.floor(Math.random() * respuestasAleatorias.length)];
  opcionesRespuestas.push(respuestaAleatoria);// Agregar la respuesta aleatoria al arreglo de opciones de respuesta
  return opcionesRespuestas; // Devolver el arreglo con las dos opciones de respuesta
}

function aplicarComodinPublico(respuestas) {// Función para aplicar el comodín del público
  let porcentajes = [];// Arreglo para almacenar los porcentajes de votos del público
  let porcentajeRestante = 100;// Porcentaje total inicial

  for (let i = 0; i < respuestas.length - 1; i++) {
    const porcentaje = Math.floor(Math.random() * porcentajeRestante);// Generar un porcentaje aleatorio para cada respuesta
    porcentajes.push(porcentaje);// Agregar el porcentaje al arreglo
    porcentajeRestante -= porcentaje;// Restar el porcentaje asignado a la respuesta actual del porcentaje restante
  }

  porcentajes.push(porcentajeRestante);// Agregar el porcentaje restante a la última respuesta

  return porcentajes;// Devolver el arreglo con los porcentajes de votos del público
}

function aplicarComodinLlamada(respuestas, respuesta_correcta, porcentajeAcierto){// Función para aplicar el comodín de la llamada

  const numeroAleatorio = Math.floor(Math.random() * 100);
  var mensaje;
  if(numeroAleatorio < porcentajeAcierto ){
    mensaje = "Estoy bastante seguro de que la respuesta es ";
    return "<span style='color: white;'>" + mensaje + respuesta_correcta;
  }

  const respuestasAleatorias = respuestas.filter(respuesta => respuesta !== respuesta_correcta);
  const posicionAleatoria = Math.floor(Math.random() * respuestasAleatorias.length);
  const respuesta_final =  respuestasAleatorias[posicionAleatoria];

  mensaje = ["Estoy 100% seguro de que la respuesta es " + respuesta_final,
    "Uf, que dificil, pues conozco la respuesta lo siento",
    "Estoy un poco al 50/50 entre " + respuesta_final + " y " + respuesta_correcta + ', pero me decanto más por ' + respuesta_final]

  return "<span style='color: white;'>" + mensaje[posicionAleatoria]// Devolver el mensaje seleccionado aleatoriamente
}


function getQuestion(dificultad, respuesta = null){// Función para obtener una pregunta
  const preguntas = data.preguntas; // Obtener todas las preguntas del objeto "data"
  const preguntasFiltradas = preguntas.filter(function(pregunta) {

    if(respuesta != null){
      return pregunta.respuesta_correcta === respuesta;// Filtrar por respuesta correcta si se proporciona una respuesta
    }

    return pregunta.dificultad === dificultad;// Filtrar por dificultad si no se proporciona una respuesta
  });

  const posicionAleatoria = Math.floor(Math.random() * preguntasFiltradas.length);// Obtener una posición aleatoria dentro del rango de preguntas filtradas

  return preguntasFiltradas[posicionAleatoria];// Devolver la pregunta seleccionada aleatoriamente
}

function startTimer(segundos, element){// Función para iniciar el temporizador
  var timerElement = $(element);// Elemento del temporizador

  temporizador = setInterval(function() {
    segundos--;// Reducir el número de segundos
    timerElement.text(segundos);// Detener el temporizador si se agotan los segundos

    if (segundos <= 0) {
      clearInterval(temporizador);// Actualizar el elemento del temporizador con el número de segundos restantes
      // alert("SE HA ACABADO EL TIEMPO, HAS PERDIDO.")
      $('#submit').click()// Hacer clic en el botón de envío del formulario
      window.location.href = "perder.php";// Redireccionar a la página de perder
      return;
    }
  }, 1000);
}