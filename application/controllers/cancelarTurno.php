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
		var socioLogueado = 2;
		var canSocio;

		$(document).ready(function(){
			cargarTurnos();
		});

		function bind(){
			$('.turno').click(function(){
				cancelarTurno(this.id);
			});
		}

		function cargarTurnos() {
			$.post('ajax/getObject.php', {clase: 'Socio', id: socioLogueado}, function(socio){
				if (!socio.idSocio)
					return;
				//El socio existe -> sigo
				if (socio.turnosPorJugar.length == 0)
					return;
				//Tiene turnos -> sigo
				canSocio = socio;
				var html = '';
				for (var i in socio.turnosPorJugar) {
					var turno = socio.turnosPorJugar[i];
					var div = '<div id="' + turno.idTurno + '" class="turno">' + turno.horaInicio + ' - ' + turno.horaFin + '</div>';
					html += div;
				}
				$('#turnos').html(html);
				bind();
			}, 'json');
		}

		function cancelarTurno(id) {
			debugger;
			$.post('ajax/getObject.php', {clase: 'Turno', id: id}, function(turno){
				$.post('ajax/getObject.php', {clase: 'Parametro', id: '14'}, function(penalizar){
					debugger;
					var msgPenalizar = '';
					if (!turno.idTurno)
						return;
					//El turno existe -> sigo
					if (turno.estado != 1)
						return;
					//El turno aún no se jugó -> sigo
					if (penalizar.valor == '1' && turno.idTurno == canSocio.turnoParaCheckIn.idTurno)
						msgPenalizar = 'Tenga en cuenta que está en horario de checkIn y va a ser penalizado';
					if (confirm('Está seguro que desea cancelar el turno ' + id + '? ' + msgPenalizar)) {
						$('#' + id).remove();
						if ($('.turno').length > 0)
							alert('El turno fue cancelado. Aún quedan más');
						else
							alert('El turno fue cancelado. Era el último');
					}
				}, 'json');
			}, 'json');
		}
	</script>
</head>
<body>
	<div id='divContainer'>
		<div id='turnos'></div>
	</div>
</body>
</html>