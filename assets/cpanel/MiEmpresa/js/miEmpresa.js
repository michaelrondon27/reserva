$(document).ready(function(){
    telefonoInput('.telefono');
	listar();
	actualizar_mi_empresa();
    var busqueda = false;
});


/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro3 para la consulta de la plaza.
	*/
	function listar(){
		var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
        $.ajax({
            url:url+'MiEmpresa/buscar_mi_empresa',
            type:'POST',
            dataType:'JSON',
            error: function (repuesta) {
                listar();              
            },
            success: function(respuesta){
                $("#alertas").html('');
                if(Object.keys(respuesta.empresa).length>0){
                    respuesta.estados.result_object.forEach(function(campo, index){
                        agregarOptions('#estado', campo.d_estado, campo.d_estado);
                    });
                    respuesta.ciudades.result_object.forEach(function(campo, index){
                        if(campo.d_ciudad!=""){
                            agregarOptions('#ciudad', campo.d_ciudad, campo.d_ciudad);
                            $("#ciudad").css('border-color', '#ccc');
                        }else{
                            agregarOptions('#ciudad', "N/A", "NO APLICA");
                            $("#ciudad").css('border-color', '#a94442');
                        }
                    });
                    respuesta.municipios.result_object.forEach(function(campo, index){
                        agregarOptions('#municipio', campo.d_mnpio, campo.d_mnpio);
                    });
                    respuesta.colonias.result_object.forEach(function(campo, index){
                        agregarOptions('#colonia', campo.id_codigo_postal, campo.d_asenta);
                    });
                	document.getElementById('nombre_mi_empresa').value=respuesta.empresa.result_array[0].nombre_mi_empresa;
                	document.getElementById('rfc_mi_empresa').value=respuesta.empresa.result_array[0].rfc_mi_empresa;
                	document.getElementById('id_mi_empresa').value=respuesta.empresa.result_array[0].id_mi_empresa;
                    document.getElementById('telefono_principal_contacto').value=respuesta.empresa.result_array[0].telefono_principal_contacto;
                    document.getElementById('correo_opcional_contacto').value=respuesta.empresa.result_array[0].correo_opcional_contacto;
                    document.getElementById('direccion_contacto').value=respuesta.empresa.result_array[0].direccion_contacto;
                    document.getElementById('calle_contacto').value=respuesta.empresa.result_array[0].calle_contacto;
                    document.getElementById('exterior_contacto').value=respuesta.empresa.result_array[0].exterior_contacto;
                    document.getElementById('interior_contacto').value=respuesta.empresa.result_array[0].interior_contacto;
                    document.getElementById('id_contacto').value=respuesta.empresa.result_array[0].id_contacto;
                    document.getElementById('codigo_postal').value=respuesta.empresa.result_array[0].d_codigo;
                    $("#estado option[value='"+respuesta.empresa.result_array[0].d_estado+"']").attr("selected","selected");
                    if(respuesta.empresa.result_array[0].d_ciudad!=""){
                        $("#ciudad option[value='"+respuesta.empresa.result_array[0].d_ciudad+"']").attr("selected","selected");
                    }else{
                        $("#ciudad option[value='N/A']").attr("selected","selected");
                    }
                    $("#municipio option[value='"+respuesta.empresa.result_array[0].d_mnpio+"']").attr("selected","selected");
                    $("#colonia option[value='"+respuesta.empresa.result_array[0].id_codigo_postal+"']").attr("selected","selected");
                }
            }
        });
	}
/* ------------------------------------------------------------------------------- */


/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function actualizar_mi_empresa(){
		enviarFormulario("#form_empresa_actualizar", 'MiEmpresa/actualizar_mi_empresa');
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Funcion que busca los codigos
    */
    function buscarCodigos(codigo){
        if (!busqueda){
            busqueda = true;
            eliminarOptions('estado');
            eliminarOptions('ciudad');
            eliminarOptions('municipio');
            eliminarOptions('colonia');
            if(codigo.length>4){
                var url=document.getElementById('ruta').value;
                $.ajax({
                    url:url+'MiEmpresa/buscar_codigos',
                    type:'POST',
                    dataType:'JSON',
                    data:{'codigo':codigo},
                    beforeSend: function(){
                        mensajes('info', '<span>Buscando, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
                    },
                    error: function (repuesta) {
                        mensajes('danger', '<span>Ha ocurrido un error, por favor intentelo de nuevo</span>');         
                    },
                    success: function(respuesta){
                        $("#alertas").html('');
                        respuesta.estados.result_object.forEach(function(campo, index){
                            agregarOptions('#estado', campo.d_estado, campo.d_estado);
                        });
                        respuesta.ciudades.result_object.forEach(function(campo, index){
                            if(campo.d_ciudad!=""){
                                agregarOptions('#ciudad', campo.d_ciudad, campo.d_ciudad);
                                $("#ciudad").css('border-color', '#ccc');
                            }else{
                                agregarOptions('#ciudad', "N/A", "NO APLICA");
                                $("#ciudad option[value='N/A']").attr("selected","selected");
                                $("#ciudad").css('border-color', '#a94442');
                            }
                        });
                        respuesta.municipios.result_object.forEach(function(campo, index){
                            agregarOptions('#municipio', campo.d_mnpio, campo.d_mnpio);
                        });
                        respuesta.colonias.result_object.forEach(function(campo, index){
                            agregarOptions('#colonia', campo.id_codigo_postal, campo.d_asenta);
                        });
                    }
                });
            }else{
                warning('Debe colocar al menos 5 caracteres para continuar.');
            }
        }
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        funcion que detecta la tecla enter para la busqueda de los codigos postales.
    */
    $("#codigo_postal").keydown(function(e) {
        if(e.which == 13) {
            if (!busqueda){
                buscarCodigos(document.getElementById('codigo_postal').value);
                busqueda = true;
            }

        }
    });
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        funcion que pone en false la variable busqueda, esto es para que no se vaya
        a disparar dos veces la funcion buscarCodigo.
    */
    $("#codigo_postal").focus(function() {
        busqueda = false;
    });
/* ------------------------------------------------------------------------------- */