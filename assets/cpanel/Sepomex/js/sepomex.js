$(document).ready(function(){
	$("#configuracion").attr('class', 'active');
	$("#sepomex").attr('class', 'active');
	enviarSepomex();
});

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function enviarSepomex(){
		enviarFormulario("#form_sepomex", 'Sepomex/actualizar');
	}
/* ------------------------------------------------------------------------------- */