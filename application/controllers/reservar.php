<?php
require_once('../include/initialize.inc.php');
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' lang='es'/>
	<link rel='stylesheet/less' type='text/css' href='../css/styles.less' />
	<script type='text/javascript' src='../js/jquery.js' ></script>
	<script type='text/javascript' src='../js/less.min.js'></script>
	<script type='text/javascript'>
		var resCancha;
		var resJugadores;
		var resHoraInicio;
		var resHoraFin;
		var resLuz;

		$(document).ready(function(){
			$('.cancha').click(function(){
				reservarCancha(this.id);
			});
		});

		function reservarCancha(idCancha) {
			$.post('ajax/getObject.php', {clase: 'Cancha', id: idCancha}, function(cancha){
				if (!cancha.idCancha)
					return;
				//La cancha existe -> sigo
				if (cancha.estado == 4)
					return;
				//La cancha no está inhabilitada -> sigo
				if (cancha.estado == 3) {
					//La cancha está ocupada por torneo
					
				} else {
					//La cancha no está ocupada por torneo
					if (cancha.turnosLibres.length == 0)
						return;
					//Quedan turnos -> sigo
					resCancha = cancha;
					ingresarJugadores();
				}
			}, 'json');
		}

		function ingresarJugadores() {
			alert('Ingrese los jugadores');
			var j1 = prompt('Jugador 1');
			var j2 = prompt('Jugador 2');
			$.post('ajax/getObject.php', {clase: 'Socio', id: j1}, function(socio1){
				$.post('ajax/getObject.php', {clase: 'Socio', id: j2}, function(socio2){
					debugger;
					jugadores = [socio1, socio2];
					for (i in jugadores) {
						if (jugadores[i].estado != 1)
							return;
					}
					//Todos los jugadores pueden jugar -> sigo
					resJugadores = jugadores;
					seleccionarHoraInicio();
				}, 'json');
			}, 'json');
		}

		function seleccionarHoraInicio() {
			$.post('ajax/getObject.php', {clase: 'Parametro', id: '9'}, function(horaElegible){
				resHoraInicio = resCancha.turnosLibres[0].horaInicio;
				if (horaElegible.valor != '0')
					muestroHorarios(horaElegible.valor);
				else
					calcularCantidadHorasJugar();
			}, 'json');
		}

		function muestroHorarios(horaElegible){
			var horaDesdeLibre;
			if (horaElegible == '2') {
				$.post('ajax/getObject.php', {clase: 'Parametro', id: '12'}, function(horaLuz){
					horaDesdeLibre = horaLuz.valor;
					mostrarHorariosConLibreDesde(horaDesdeLibre);
				}, 'json');
			} else {
				horaDesdeLibre = resCancha.turnosLibres[0].horaInicio;
				mostrarHorariosConLibreDesde(horaDesdeLibre);
			}
		}

		function mostrarHorariosConLibreDesde(horaDesdeLibre) {
			debugger;
			var turnosAMostrar = [];
			turnosAMostrar[0] = resCancha.turnosLibres[0];
			for (var i = 1; i < resCancha.turnosLibres.length; i++) {
				var turno = resCancha.turnosLibres[i];
				if (funciones.esHoraMayorIgual(turno.horaInicio, horaDesdeLibre))
					//También tengo que controlar que ese turno alcance para la mínima duración de ese tipo de partido
					//Es decir, si hay un turno de media hora y quiero jugar un doble, no me tiene q mostrar el turno
					turnosAMostrar[turnosAMostrar.length] = turno;
			}
			var html = '';
			for (i in turnosAMostrar){
				var turno = turnosAMostrar[i];
				html += '<div id="' + turno.horaInicio + '" class="horario">' + turno.horaInicio + ' - ' + turno.horaFin + '</div>';
			}
			$('#turnos').html(html);
			$('.horario').click(function(){
				horarioClick(this.id);
			});
		}

		function horarioClick(horaInicio){
			debugger;
			resHoraInicio = horaInicio;
			calcularCantidadHorasJugar();
		}

		function calcularCantidadHorasJugar(){
			$.post('ajax/getObject.php', {clase: 'Parametro', id: '10'}, function(durMin){
				$.post('ajax/getObject.php', {clase: 'Parametro', id: '11'}, function(durMax){
					$.post('ajax/getObject.php', {clase: 'Parametro', id: '2'}, function(fraccion){
						durMin = parseInt(durMin.valor);
						durMax = parseInt(durMax.valor);
						fraccion = parseInt(fraccion.valor);
						var cantFrac = parseInt(durMin / fraccion);
						var posibleHoraFin = funciones.sumaMinutos(resHoraInicio, fraccion * 2);
						var bk = false;
						while (!bk) {
							if ((cantFrac * fraccion >= durMax) || (funciones.esHoraMayorIgual(posibleHoraFin)))
								;
								
						}




						
					}, 'json');
				}, 'json');
			}, 'json');
		}

		function seleccionarHoraFin() {
			$.post('ajax/getObject.php', {clase: 'Parametro', id: '10'}, function(minimaSingle){
				$.post('ajax/getObject.php', {clase: 'Parametro', id: '11'}, function(maximaSingle){
					var horaFin = resCancha.turnosLibres[0].horaFin;
					if (minimaSingle.valor != maximaSingle.valor)
						horaFin = prompt('Seleccione hora fín');
					resHoraFin = horaFin;
					seleccionarLuz();
				}, 'json');
			}, 'json');
		}

		function seleccionarLuz() {
			$.post('ajax/getObject.php', {clase: 'Parametro', id: '12'}, function(horaLuz){
				$.post('ajax/getObject.php', {clase: 'Parametro', id: '13'}, function(luzObligatoria){
					var luz = 0;
					if (true/*funciones.esHoraMayorIgual(horaInicio, horaLuz.valor)*/ && luzObligatoria.valor == 0)
						luz = prompt('Quiere luz?');
					resLuz = luz;
					aceptarGuardar();
				}, 'json');
			}, 'json');
		}

		function aceptarGuardar(){
			var a = 'Van a jugar ' + resJugadores[0].idSocio + ' y ' + resJugadores[1].idSocio;
			a += ' en la cancha ' + resCancha.idCancha + ' de ' + resHoraInicio + ' a ' + resHoraFin + (resLuz == 1 ? ' con ' : ' sin ') + 'luz';
			alert(a);
		}
	</script>
</head>
<body>
	<div id='divContainer'>
		<div id='1' class='cancha tenis cemento'>
			<div class='fl-left mitad izquierda'>
				<div class='paralela superior'></div>
				<div class='centro'>
					<div class='fl-right'>
						<div class='cuadrante superior'></div>
						<div class='cuadrante'></div>
					</div>
				</div>
				<div class='paralela inferior'></div>
			</div>
			<div class='fl-right mitad derecha'>
				<div class='paralela superior'></div>
				<div class='centro'>
					<div class='fl-left'>
						<div class='cuadrante superior'></div>
						<div class='cuadrante'></div>
					</div>
				</div>
				<div class='paralela inferior'></div>
			</div>
		</div>
		<div id='turnos'></div>
		<div id='cantidadHoras'></div>
	</div>
</body>
</html>