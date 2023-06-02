"use strict";

import data from './JSON/datos.json' assert { type: 'json' };
//declarar variables
let aciertos = 0;
let temporizador;
let comodin_publico_usado = false;
let comodin_50 = false;
let musica = false;


const quizContainer = $('#quizContainer');
const quizForm = $('#quizForm');
//Cantidades de dinero por niveles
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

// Ocultar los elementos en pantalla hasta que se pulse el comenzar
$('#temporizador').hide()
$('#aciertos').hide()
$('#dinero').hide()

//Función que se ejecuta cuando la página ha cargado
$(document).ready(function(){
//Función que se ejecuta cuando se hace click en el elemento "start"(comenzar juego)
  $('#start').click(function(){
    //Cuando se clicka en start los elementos que antes estaban en hide pasan a show
    $(".musica").show()
    $('#temporizador').show()
    $('#aciertos').show()
    $("#botones_juego").show()

    $(this).hide() // Ocultar el elemento en el que se hizo click

    pintarPreguntas(1); // Llamar a la función pintarPreguntas con la dificultad 1
  });

  //Función para ejecutar la música cuando se haga click en el botón
  $("#play-button").click(function(){
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
  })
//Función que se ejecuta cuando se haga click en plantarse
  $("#plantarse").click(function(event){
    event.preventDefault();// Evita el comportamiento predeterminado del evento de clic

    const maximo_preguntas = Object.keys(dinero).length; // Obtiene la cantidad máxima de preguntas desde el objeto 'dinero'

    alert("Enhorabuena, tu premio es de " + dinero[aciertos]);
    window.location.href = "index.php";
    return;
  });

})


function pintarPreguntas(dificultad, respuesta = null){
  clearInterval(temporizador);// Detiene el temporizador
  startTimer(30, '#tiempo');// Inicia un nuevo temporizador
  $(".posible_respuesta").remove();// Elimina elementos con la clase "posible_respuesta"

  const maximo_preguntas = Object.keys(dinero).length;// Obtiene la cantidad máxima de preguntas desde el objeto 'dinero'

  $("#nivel_pregunta").show().find("span").text(dificultad)// Muestra el nivel de la pregunta actual

  const preguntas = getQuestion(dificultad, respuesta);// Obtiene las preguntas correspondientes a la dificultad

  const pregunta = preguntas.pregunta;// Obtiene la pregunta actual
  var respuestas = preguntas.respuestas;// Obtiene las respuestas
  const id = preguntas.id; // Obtiene el ID de la pregunta
  const respuesta_correcta = preguntas.respuesta_correcta;// Obtiene la respuesta correcta

  if (comodin_50) {
    // Aplicar el comodín 50/50 para que solo se muestren en pantalla la respuesta correcta y otra cualquiera
    respuestas = aplicarComodin50(respuestas, respuesta_correcta);
    comodin_50 = false;// Marca el comodín 50/50 como utilizado y desaparece en pantalla
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
    //Asignar un input de tipo radio a las respuestas
    var input = $('<input name="respuestas" class="respuesta'+(j+1)+'">').attr({
      type: 'radio',
      value: respuestas[j]
    });
    var label = $('<label>').text(respuestas[j]);
  //Si el comodín del público ha sido usado incluir un label con el texto correspondiente a los porcentajes en cada respuesta
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

  //Evento onchange para los elementos de tipo radio
  questionElement.on('change', 'input[type="radio"]', function() {
    var respuestaSeleccionada = $(this);//Asignar el input a la respuesta seleccionada por el usuario
    const respuestaUsuario = $(this).val();
    //Si la variable seleccionada es igual a la correcta
    if(respuestaUsuario === respuesta_correcta){
      respuestaSeleccionada.css('background-color', 'green');
      respuestaSeleccionada.next('label').css('background-color', 'green');
      aciertos++; //Se incrementa la variable aciertos en uno
      $('#dinero').show() //Se muestra el elemento dinero
      $('#dinero_acumulado').text(dinero[aciertos]) //Se actualiza el dinero
      $("#aciertos_acumulados").text(aciertos)//Se actualizan los aciertos

      dificultad = aciertos + 1;
      $("#pregunta_"+id).removeClass('active').hide();//Se elimina la clase 'active' y se oculta el elemento con pregunta_

      if(dificultad > maximo_preguntas){ //Si el usuario ha llegado a la máxima dificultad (que es mayor que las preguntas)
        window.location.href = "ganar.php"; // Redirige a la página de ganar.php
      }else{
        pintarPreguntas(dificultad)// Vuelve a pintar las preguntas con la nueva dificultad
      }
      return;
    }else{ //Si no se cambia el color del elemento
      respuestaSeleccionada.css('background-color', 'red');
      respuestaSeleccionada.next('label').css('background-color', 'red');
      // alert("HAS PERDIDO")
      setTimeout(function() {
        window.location.href = "perder.php";
      }, 1000);
    }
  });

  $("#comodin_50").click(function(event){
    event.preventDefault();
    $(this).remove()//Eliminar el elemento del DOM para que desaparezca

    $(".active").remove()//Eliminar todos los elementos con la clase "active" del DOM.
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

    $("#quizContainer").append($p);//Agregar el elemento p a countainer
    //Eliminar todos los elementos de la clase votación excepto el último, garantizando que solo haya uno visible
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
    posibleRespuesta = $('<p class="posible_respuesta">'+posibleRespuesta+'</p>');//Asignar el resultado a la variable posibleRespuesta
    quizForm.append(posibleRespuesta)//Agregar el elemento al formulario
    $(".posible_respuesta:not(:last)").remove();

  })
}
// Función para ordenar las respuestas de forma aleatoria
function ordenarRespuestasAleatoriamente(){
  return Math.random() - 0.5;
}

function aplicarComodin50(respuestas, respuesta_correcta){// Función para aplicar el comodín 50/50
  let opcionesRespuestas = [respuesta_correcta]; //Crear array y agregarle la respuesta correcta

  // Filtrar las respuestas para obtener un nuevo array que excluya la respuesta_correcta
  // Esto asegura que solo se obtengan respuestas diferentes a la respuesta correcta
  const respuestasAleatorias = respuestas.filter(respuesta => respuesta !== respuesta_correcta);
  const respuestaAleatoria = respuestasAleatorias[Math.floor(Math.random() * respuestasAleatorias.length)];
  opcionesRespuestas.push(respuestaAleatoria);// Agregar la respuesta aleatoria al arra de opciones de respuesta
  return opcionesRespuestas; // Devolver el array con las dos opciones de respuesta
}

function aplicarComodinPublico(respuestas) {
  let porcentajes = [];// Array para almacenar los porcentajes de votos del público
  let porcentajeRestante = 100;// Porcentaje total inicial

  for (let i = 0; i < respuestas.length - 1; i++) {
    const porcentaje = Math.floor(Math.random() * porcentajeRestante);// Generar un porcentaje aleatorio para cada respuesta
    porcentajes.push(porcentaje);// Agregar el porcentaje al arreglo
    porcentajeRestante -= porcentaje;// Restar el porcentaje asignado a la respuesta actual del porcentaje restante
  }

  porcentajes.push(porcentajeRestante);// Agregar el porcentaje restante a la última respuesta

  return porcentajes;// Devolver el arreglo con los porcentajes de votos del público
}

function aplicarComodinLlamada(respuestas, respuesta_correcta, porcentajeAcierto){

  const numeroAleatorio = Math.floor(Math.random() * 100);
  var mensaje;
  //Si el número aleatorio generado es mayor que el porcentajeAcierto se manda el mensaje
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


function getQuestion(dificultad, respuesta = null){
  const preguntas = data.preguntas;
  const preguntasFiltradas = preguntas.filter(function(pregunta) {

    if(respuesta != null){
      return pregunta.respuesta_correcta === respuesta;// Filtrar por respuesta correcta si se proporciona una respuesta
    }

    return pregunta.dificultad === dificultad;// Filtrar por dificultad si no se proporciona una respuesta
  });

  const posicionAleatoria = Math.floor(Math.random() * preguntasFiltradas.length);// Obtener una posición aleatoria dentro del rango de preguntas filtradas

  return preguntasFiltradas[posicionAleatoria];// Devolver la pregunta seleccionada aleatoriamente
}

// Función para iniciar el temporizador
function startTimer(segundos, element){
  var timerElement = $(element);
  temporizador = setInterval(function() {
    segundos--;// Reducir el número de segundos
    timerElement.text(segundos);// Detener el temporizador si se agotan los segundos

    if (segundos <= 0) {
      clearInterval(temporizador);
      $('#submit').click()
      window.location.href = "perder.php";
      return;
    }
  }, 1000);
}