$(document).ready(function(){
	$("#configuracion").attr('class', 'active');
	$("#miCorreo").attr('class', 'active');
	listar();
	actualizar_mi_correo();
});


/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro3 para la consulta de la plaza.
	*/
	function listar(){
		var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
        $.ajax({
            url:url+'MiCorreo/buscar_mi_correo',
            type:'POST',
            dataType:'JSON',
            beforeSend: function(){
                mensajes('info', '<span>Cargando datos, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
            },
            error: function (repuesta) {
                listar();              
            },
            success: function(respuesta){
                $("#alertas").html('');
                if(Object.keys(respuesta).length>0){
                	document.getElementById('servidor_smtp').value=respuesta[0].servidor_smtp;
                	document.getElementById('puerto').value=respuesta[0].puerto;
                	document.getElementById('usuario').value=respuesta[0].usuario;
                	document.getElementById('clave').value=respuesta[0].clave;
                	document.getElementById('id_mi_correo').value=respuesta[0].id_mi_correo;
                }
            }
        });
	}
/* ------------------------------------------------------------------------------- */


/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function actualizar_mi_correo(){
		enviarFormulario("#form_correo_actualizar", 'MiCorreo/actualizar_mi_correo');
	}
/* ------------------------------------------------------------------------------- */
