<?php
require_once('include/initialize.inc.php');
?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html>
<head>
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' lang='es'/>
	<link rel='stylesheet/less' type='text/css' href='css/styles.less' />
    <link rel='stylesheet' type='text/css' href='css/bootstrap.css' />
</head>
<body>
<header>
	<div class = "navbar navbar-fixed-top">
		<div class = "navbar-inner">
			<div class = "pull-left">
				Izquierda AAA 
			</div>
			<div class = "pull-right">
				Derecha
			</div>
		</div>
	</div>
</header>
<div id = "clear-header" style = "padding-top: 40px;">
</div>
<div id = "app-container">
</div>
<!--
    <div id = "navBar">
        <div class="navbar navbar-fixed-top">
          <div class="navbar-inner">
              <div class = "pull-left">
                  <i class="icon-calendar"></i>
                  <span>Octubre 24, 22:45hs</span>
              </div>
              <div class = "pull-right">
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                       <i class="icon-user"></i>
                       Lucas Ceballos
                      <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                         <li><a tabindex="-1" href="#">Ficha Personal</a></li>
                         <li><a tabindex="-1" href="#">Mis Reservas</a></li>
                         <li><a tabindex="-1" href="#">Penalizaciones</a></li>
                         <li class="divider"></li>
                         <li><a tabindex="-1" href="#">Salir</a></li>
                    </ul>
                </div>
              </div>
          </div>
        </div>
    </div>
	<div id = "divContainer">
	    <div id = "homeScreen" class = "pos-center al-center" style = "width:50%;">
	        <div id = "logoClub">
                <img alt="Club Atletico Lanus" src="img/club_logo.png">
	        </div>
	        <ul id = "homeNavBar" class = "pos-center">
	            <li><a class="btn btn-inverse btn-large btn-block" data-loading-text="Loading...">Reserva Rápida</a></li>
	            <li><a class="btn btn-inverse btn-large btn-block" data-loading-text="Loading...">Ver Canchas</a></li>
	            <li><a class="btn btn-inverse btn-large btn-block" data-loading-text="Loading...">Ayuda</a></li>
	        </ul>
	    </div>
	</div>
-->
</body>
<script type='text/javascript' src='js/jquery.js' ></script>
<script type='text/javascript' src='js/bootstrap.min.js' ></script>
<script type='text/javascript' src='js/functions.js' ></script>
<script type='text/javascript' src='js/less.min.js'></script>
<script>
/*
	var functions = new Functions();

	$(document).ready(function(){
		debugger;
		var socio = functions.getObject('Socio');
		socio.nombre = 'Juancho';
		socio.apellido = 'Talarga';
		var newSocio = functions.save('saveObject', {objectToSave: socio});
	});
*/
/*
	$(document).ready(function(){
		cantCanchas = parseInt((functions.getObject('Parametro' , 15))['valor'])// Cantidad de canchas
	    for(i=14;i<=cantCanchas;i++) {
		    $('#canchasContainer').append('<div class = "canchaFrame" id = "'+i+'"></div>');
	    }
		$('.canchaFrame').cancha();
	});
*/
</script>
</html>