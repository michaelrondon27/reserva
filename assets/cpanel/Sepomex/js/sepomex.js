$(document).ready(function(){
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