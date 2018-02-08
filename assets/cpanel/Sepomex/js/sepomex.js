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

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que cancela el formulario.
	*/
	function cancelar(){
		$("#form_sepomex")[0].reset();
		$("#alertas").html('');
	}
/* ------------------------------------------------------------------------------- */