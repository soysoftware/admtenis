var functions = new Functions;

//Defino la clase "FUNCIONES"
function Functions(){
}

//Métodos de "FUNCIONES"
Functions.prototype.save = function(url, objToSend){
	if (typeof objToSend === 'undefined')
		return false;
	$.ajax({
		url		:	'controllers/' + url + '.php',
		dataType:	'json',
		type	:	'POST',
		data	:	objToSend,
		async	:	false,
		cache	:	false,
		timeout	:	3000,
		success	:	function(response){
						if (parseInt(response.code)){
							object = response.data;
						} else {
							//Error Handler
						}
					}
	});
	return object;
};

Functions.prototype.getObject = function(_class, id, attr){
	var object = null;
	var objToSend;
	if (typeof attr === 'undefined')
		objToSend = {'clase':_class , 'id': id};
	else
		objToSend = {'clase': _class, 'id': id, 'attr': attr};
	$.ajax({
			url		:	'controllers/getObject.php',
			dataType:	'json',
			type	:	'POST',
			data	:	objToSend,
			async	:	false,
			cache	:	false,
			timeout	:	3000,
			success	:	function(response){
							if(parseInt(response.code)){
								object = response.data;
							} else {
								//Error Handler
							}
						}
		 });
	return object;
};


Functions.prototype.sumaMinutos = function(original, adicion) {
	var h = explodeHora(original);
	h[1] += parseInt(adicion);
	if (h[1] >= 60) {
		horasDeMas = Math.floor(h[1] / 60);
		h[0] += horasDeMas;
		h[1] -= parseInt(horasDeMas * 60);
	}
	var ret = implodeHora(h);
	return ret;
};

Functions.prototype.esHoraMayorIgual = function(hora1, hora2) {
	var h1 = explodeHora(hora1);
	var h2 = explodeHora(hora2);
	if (h1[0] > h2[0])
		return true;
	else if(h1[0] == h2[0] && h1[1] > h2[1])
		return true;
	else if(h1[0] == h2[0] && h1[1] == h2[1] && h1[2] >= h2[2])
		return true;
	return false;
};

Functions.prototype.explodeHora = function(stringHora) {
	var h = stringHora.split(':');
	h[0] = parseInt(h[0]);
	h[1] = parseInt(h[1]);
	h[2] = parseInt(h[2]);
	return h;
};

Functions.prototype.implodeHora = function(h) {
	h[0] = h[0].padLeft(2, '0');
	h[1] = h[1].padLeft(2, '0');
	h[2] = h[2].padLeft(2, '0');
	var ret = h.join(':');
	return ret;
};

String.prototype.padLeft = function(n,str){
    return Array(n-String(this).length+1).join(str||'0')+this;
};

(function( $ ) {

	var settings		=	{
								'refreshTime' : 15000
							};

	var superficies		=		{
								1:'polvo',
								2:'cemento',
								3:'cesped'
							};

	var cancha			=	{};

	var canchaAnterior	=	{};

	var methods			=	{
			
			init	:	function(options){	
				this.each(function(i,e){
					$(e).cancha('loadCancha').cancha('initDiv').cancha('loadDiv');
					setInterval($.proxy(function() {
							this.cancha('refresh');
						}, $(e)), settings.refreshTime
					);
				});
				return this;
			},

			loadCancha	:	function(){
				$.extend(canchaAnterior, cancha);
				$.extend(cancha , functions.getObject('Cancha' , this.attr('id')));
				return this;
			},

			loadDiv		:	function(){
				this.find('.cancha.tenis').addClass(superficies[cancha.superficie]);				
				this.find('span.numeroCancha').html(cancha.numero);
				this.find('span.hora').html((cancha.turnosLibres.length == 0) ? 'Hoy no, mañana' : cancha.turnosLibres[0].horaInicio.substr(0,5));
				var ft = $.isEmptyObject(canchaAnterior); //ft = first time =D
				if(parseInt(cancha.estado) == 2){
					// Cancha ocupada
					this.find('.cancha.tenis').addClass('disabled');
					if (ft || cancha.turnoActual.idTurno != canchaAnterior.turnoActual.idTurno) {
						cancha.turnoActual = functions.getObject('Turno' , cancha.turnoActual.idTurno);
						for ( i in cancha.turnoActual.socios ) {
							this.find('.player#p'+(parseInt(i)+1)).removeClass('hide').children('span.name').html(cancha.turnoActual.socios[i].socio.apellido + ' ' + cancha.turnoActual.socios[i].socio.nombre);
						}
					}
					if (ft || cancha.turnoSiguiente.idTurno != canchaAnterior.turnoSiguiente.idTurno) {
						if(cancha.turnoSiguiente.idTurno == '') {
							contenidoMarquee = 'No hay turnos reservados';
						} else {
							cancha.turnoSiguiente = functions.getObject('Turno' , cancha.turnoSiguiente.idTurno);
							contenidoMarquee = 'Proximo turno: ' + (cancha.turnosLibres.length == 0) ? 'Hoy no, mañana' : cancha.turnosLibres[0].horaInicio.substr(0,5);
							for(i in cancha.turnoSiguiente.socios) {
								contenidoMarquee += cancha.turnoSiguiente.socios[i].socio.nombre + ' ' + cancha.turnoSiguiente.socios[i].socio.apellido;
							}
						}
					}
					this.find('.marquee').removeClass('hide').html(contenidoMarquee);
				} else {
					// Cancha libre
					this.find('.cancha.tenis').removeClass('disabled');
					this.find('.marquee').addClass('hide');
				}
				return this;
			},
			
			refresh		:	function(){
				console.log("Actualizando la cancha: "+cancha.numero);
				this.cancha('loadCancha');
				this.cancha('loadDiv');
				return this;
			},
			
			initDiv		:	function(){
				this.append(
							//Proximo turno
							'<div class = "proxTurno">'+
								'<span>proximo turno disponible</span>'+
								'<span class = "hora"></span>'+
							'</div> '+
							//Jugadores
							'<div class="player hide" id="p1">'+
								'<span class="pos">J1:</span>'+
								'<span class="name"></span>'+
							'</div>'+
							'<div class="player hide" id="p2">'+
								'<span class="pos">J2:</span>'+
								'<span class="name"></span>'+
							'</div>'+
							'<div class="player hide" id="p3">'+
								'<span class="pos">J3:</span>'+
								'<span class="name"></span>'+
							'</div>'+
							'<div class="player hide" id="p4">'+
								'<span class="pos">J4:</span>'+
								'<span class="name"></span>'+
							'</div>'+
							//Cancha
							'<div class="cancha tenis">'+
								'<div class="fl-left mitad izquierda">'+
									'<div class="paralela superior"></div>'+
									'<div class="centro">'+
									'	<div class="fl-right">'+
											'<div class="cuadrante superior"></div>'+
											'<div class="cuadrante"></div>'+
										'</div>'+
									'</div>'+
									'<div class="paralela inferior"></div>'+
								'</div>'+
								'<div class="fl-right mitad derecha">'+
									'<div class="paralela superior"></div>'+
									'<div class="centro al-right">'+
										'<span class = "numeroCancha"></span>'+
										'<div class="fl-left">'+
											'<div class="cuadrante superior"></div>'+
											'<div class="cuadrante"></div>'+
										'</div>'+
									'</div>'+
									'<div class="paralela inferior"></div>'+
								'</div>'+
							'</div>'+
							//Marquee
							'<div class = "marquee hide">'+
								'<span class = "title"></span>'+
								'<span class = "content"></span>'+
							'</div>'							
						   );
				return this;
			}
	
	};
	
	$.fn.cancha = function(method){
		  if ( methods[method] ) {
		      return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
		  } else if ( typeof method === 'object' || ! method ) {
		      return methods.init.apply( this, arguments );
		  } else {
		      $.error( 'El metodo ' +  method + ' no existe en ajaxForm' );
		  } 
	};
	
})( jQuery );