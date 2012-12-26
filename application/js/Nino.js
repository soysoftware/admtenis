//Constructor de la clase Nino
function Nino(argClass , argId){
	this.class = argClass;
	this.id = argId;
	this.createObject();
}

Nino.prototype.createObject 	=	function(){
	var ajaxObject;
	$.ajax({
		url		:	'ajax/getObject.php',
		dataType:	'json',
		type	:	'POST',
		data	:	{'clase':this.class , 'id':this.id},
		async	:	false,
		cache	:	false,
		timeout	:	3000,
		success	:	function(response){
						if(parseInt(response.code)){							
							ajaxObject = response.data.object;
						} else {
							//Error
						}
					}
	 });
	this.fillObject(ajaxObject);
}


Nino.prototype.fillObject		=	function(argObject){
	for(attr in argObject){
		debugger;
		this[attr] = argObject[attr];
		if(attr.substr(0,1) == '_'){
			debugger;
			$.proxy(function(){debugger; this.__defineGetter__(attr, attr);}, attr);
		}
	}
}

Nino.prototype.getter			=	function(attr){
	debugger;
	alert(arguments.callee.name);
	alert("Soy el getter de nino " + attr );
}
