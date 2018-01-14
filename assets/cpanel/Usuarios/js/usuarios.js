$(document).ready(function(){
	$("#perfiles").attr('class', 'active');
	$("#usuarios").attr('class', 'active');
	elegirFecha('.fecha');
	telefonoInput('.telefono');
	listar();
	registrar_usuario();
	actualizar_usuario();
});

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion para cargar los datos de la base de datos en la tabla.
	*/
	function listar(cuadro){
		$('#tabla tbody').off('click');
		cuadros(cuadro, "#cuadro1");
		var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
		var table=$("#tabla").DataTable({
			"destroy":true,
			"stateSave": true,
			"serverSide":false,
			"ajax":{
				"method":"POST",
				"url":url+"usuarios/listado_usuarios",
				"dataSrc":""
			},
			"columns":[
				{"data": "id_usuario",
					render : function(data, type, row) {
						return "<input type='checkbox' class='checkitem chk-col-blue' id='item"+data+"' value='"+data+"'><label for='item"+data+"'></label>"
					}
				},
				{"data":"nombre_datos_personales"},
				{"data":"apellido_p_datos_personales"},
				{"data":"apellido_m_datos_personales"},
				{"data":"curp_datos_personales"},
				{"data":"d_ciudad"},
				{"data":"correo_usuario"},
				{"data":"nombre_rol"},
				{"data":"fec_regins",
					render : function(data, type, row) {
						return cambiarFormatoFecha(data);
	          		}
				},
				{"data":"fec_ult_acceso_usuario",
					render : function(data, type, row) {
						if(data!=null){
							return cambiarFormatoFecha(data);
						}else{
							return "";
						}						
	          		}
				},
				{"data": null,
					render : function(data, type, row) {
						var botones="<span class='consultar btn btn-info' data-toggle='tooltip' title='Consultar'><i class='fa fa-eye' style='margin-bottom:5px'></i></span> ";
						botones+="<span class='editar btn btn-primary' data-toggle='tooltip' title='Editar'><i class='fa fa-pencil-square-o' style='margin-bottom:5px'></i></span> ";
						if(data.status==1){
							botones+="<span class='desactivar btn btn-warning' data-toggle='tooltip' title='Desactivar'><i class='fa fa-lock' style='margin-bottom:5px'></i></span> ";
						}else if(data.status==2){
							botones+="<span class='activar btn btn-warning' data-toggle='tooltip' title='Activar'><i class='fa fa-unlock' style='margin-bottom:5px'></i></span> ";
						}
		              	return botones+="<span class='eliminar btn btn-danger' data-toggle='tooltip' title='Eliminar'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></span>";
		              	return botones;
		          	}
				}
			],
			"language": idioma_espanol,
			"dom": 'Bfrtip',
			"responsive": true,
			"buttons":[
				'copy', 'csv', 'excel', 'pdf', 'print'
			]
		});
		consultar("#tabla tbody", table);
		editar("#tabla tbody", table);
		eliminar("#tabla tbody", table);
		desactivar("#tabla tbody", table);
		activar("#tabla tbody", table);
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro2 para mostrar el formulario de registrar.
	*/
	function nuevoUsuario(cuadroOcultar, cuadroMostrar){
		cuadros("#cuadro1", "#cuadro2");
		limpiarFormularioRegistrar();
		$("#nombre_datos_personales_registrar").focus();
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion para limpiar el formulario de registrar.
	*/
	function limpiarFormularioRegistrar(){
		$("#form_usuario_registrar")[0].reset();
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function registrar_usuario(){
		enviarFormulario("#form_usuario_registrar", 'Usuarios/registrar_usuario');
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro3 para la consulta del banco.
	*/
	function consultar(tbody, table){
		$(tbody).on("click", "span.consultar", function(){
			var data = table.row( $(this).parents("tr") ).data();
			document.getElementById('nombre_datos_personales_consultar').value=data.nombre_datos_personales;
			document.getElementById('apellido_p_datos_personales_consultar').value=data.apellido_p_datos_personales;
			document.getElementById('apellido_m_datos_personales_consultar').value=data.apellido_m_datos_personales;
			document.getElementById('fecha_nac_datos_personales_consultar').value=cambiarFormatoFecha(data.fecha_nac_datos_personales);
			$("#nacionalidad_datos_personales_consultar option[value='"+data.nacionalidad_datos_personales+"']").attr("selected","selected");
			document.getElementById('curp_datos_personales_consultar').value=data.curp_datos_personales;
			document.getElementById('telefono_consultar').value=data.telefono_principal_contacto;
			$("#edo_civil_datos_personales_consultar option[value='"+data.edo_civil_datos_personales+"']").attr("selected","selected");
			$("#genero_datos_personales_consultar option[value='"+data.genero_datos_personales+"']").attr("selected","selected");
			document.getElementById('direccion_contacto_consultar').value=data.direccion_contacto;
			document.getElementById('calle_contacto_consultar').value=data.calle_contacto;
			document.getElementById('exterior_contacto_consultar').value=data.exterior_contacto;
			document.getElementById('interior_contacto_consultar').value=data.interior_contacto;
			document.getElementById('codigo_postal_consultar').value=data.d_codigo;
			document.getElementById('estado_consultar').value=data.d_estado;
			if(data.ciudad!=""){
				document.getElementById('ciudad_consultar').value=data.d_ciudad;
			}else{
				document.getElementById('ciudad_consultar').value='NO APLICA';
			}
			document.getElementById('municipio_consultar').value=data.d_mnpio;
			document.getElementById('colonia_consultar').value=data.d_asenta;
			document.getElementById('correo_usuario_consultar').value=data.correo_usuario;
			$("#id_rol_consultar option[value='"+data.id_rol+"']").attr("selected","selected");
			if(data.avatar_usuario!=null){
				$("#imagen_consultar").attr('src', document.getElementById('ruta').value+'assets/cpanel/Usuarios/images/'+data.avatar_usuario);
			}else{
				$("#imagen_consultar").attr('src', "http://placehold.it/180");
			}
			document.getElementById('colonia_consultar').value=data.d_asenta;
			cuadros('#cuadro1', '#cuadro3');
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro4 para editar el banco.
	*/
	function editar(tbody, table){
		$(tbody).on("click", "span.editar", function(){
			var data = table.row( $(this).parents("tr") ).data();
			document.getElementById('nombre_datos_personales_actualizar').value=data.nombre_datos_personales;
			document.getElementById('apellido_p_datos_personales_actualizar').value=data.apellido_p_datos_personales;
			document.getElementById('apellido_m_datos_personales_actualizar').value=data.apellido_m_datos_personales;
			document.getElementById('fecha_nac_datos_personales_actualizar').value=cambiarFormatoFecha(data.fecha_nac_datos_personales);
			$("#nacionalidad_datos_personales_actualizar option[value='"+data.nacionalidad_datos_personales+"']").attr("selected","selected");
			document.getElementById('curp_datos_personales_actualizar').value=data.curp_datos_personales;
			document.getElementById('telefono_actualizar').value=data.telefono_principal_contacto;
			$("#edo_civil_datos_personales_actualizar option[value='"+data.edo_civil_datos_personales+"']").attr("selected","selected");
			$("#genero_datos_personales_actualizar option[value='"+data.genero_datos_personales+"']").attr("selected","selected");
			document.getElementById('direccion_contacto_actualizar').value=data.direccion_contacto;
			document.getElementById('calle_contacto_actualizar').value=data.calle_contacto;
			document.getElementById('exterior_contacto_actualizar').value=data.exterior_contacto;
			document.getElementById('interior_contacto_actualizar').value=data.interior_contacto;
			document.getElementById('codigo_postal_actualizar').value=data.d_codigo;
			agregarOptions("#estado_actualizar", data.d_estado, data.d_estado);
			$("#estado_actualizar option[value='"+data.d_estado+"']").attr("selected","selected");
			if(data.d_ciudad!=""){
                agregarOptions('#ciudad_actualizar', data.d_ciudad, data.d_ciudad);
                $("#ciudad_actualizar").css('border-color', '#ccc');
                $("#ciudad_actualizar option[value='"+data.d_ciudad+"']").attr("selected","selected");
            }else{
                agregarOptions('#ciudad_actualizar', "N/A", "NO APLICA");
                $("#ciudad_actualizar").css('border-color', '#a94442');
                $("#ciudad_actualizar option[value='N/A']").attr("selected","selected");
            }
            agregarOptions("#municipio_actualizar", data.d_mnpio, data.d_mnpio);
			$("#municipio_actualizar option[value='"+data.d_mnpio+"']").attr("selected","selected");
			agregarOptions('#colonia_actualizar', data.id_codigo_postal, data.d_asenta);
			$("#colonia_actualizar option[value='"+data.id_codigo_postal+"']").attr("selected","selected");
			document.getElementById('correo_usuario_actualizar').value=data.correo_usuario;
			document.getElementById('correo_confirmar_actualizar').value=data.correo_usuario;
			$("#id_rol_actualizar option[value='"+data.id_rol+"']").attr("selected","selected");
			if(data.avatar_usuario!=null){
				$("#imagen_actualizar").attr('src', document.getElementById('ruta').value+'assets/cpanel/Usuarios/images/'+data.avatar_usuario);
			}else{
				$("#imagen_actualizar").attr('src', "http://placehold.it/180");
			}
			document.getElementById('id_contacto_actualizar').value=data.id_contacto;
			document.getElementById('id_datos_personales_actualizar').value=data.id_datos_personales;
			document.getElementById('id_usuario_actualizar').value=data.id_usuario;
			cuadros('#cuadro1', '#cuadro4');
			$("#nombre_banco_editar").focus();
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function actualizar_usuario(){
		enviarFormulario("#form_usuario_actualizar", 'Usuarios/actualizar_usuario');
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a eliminar
	*/
	function eliminar(tbody, table){
		$(tbody).on("click", "span.eliminar", function(){
            var data=table.row($(this).parents("tr")).data();
            eliminarConfirmacion('Usuarios/eliminar_usuario', data.id_usuario, "¿Esta seguro de eliminar el registro?");
        });
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a desactivar
	*/
	function desactivar(tbody, table){
		$(tbody).on("click", "span.desactivar", function(){
            var data=table.row($(this).parents("tr")).data();
            statusConfirmacion('Usuarios/status_usuario', data.id_usuario, 2, "¿Esta seguro de desactivar el registro?", 'desactivar');
        });
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a desactivar
	*/
	function activar(tbody, table){
		$(tbody).on("click", "span.activar", function(){
            var data=table.row($(this).parents("tr")).data();
            statusConfirmacion('Usuarios/status_usuario', data.id_usuario, 1, "¿Esta seguro de activar el registro?", 'activar');
        });
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Funcion que busca los codigos
    */
    function buscarCodigos(codigo, type){
    	if(type == 'create'){
    		var estado = 'estado_registrar',
	    		ciudad = 'ciudad_registrar',
	    		municipio = 'municipio_registrar',
	    		colonia = 'colonia_registrar';
    	}else if(type == 'edit'){
    		var estado = 'estado_actualizar',
	    		ciudad = 'ciudad_actualizar',
	    		municipio = 'municipio_actualizar',
	    		colonia = 'colonia_actualizar';
    	}
        eliminarOptions(document.getElementById(estado));
        eliminarOptions(document.getElementById(ciudad));
        eliminarOptions(document.getElementById(municipio));
        eliminarOptions(document.getElementById(colonia));
        if(codigo.length>4){
            var url=document.getElementById('ruta').value;
            $.ajax({
                url:url+'Usuarios/buscar_codigos',
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
                        agregarOptions("#"+ estado, campo.d_estado, campo.d_estado);
                    });
                    respuesta.ciudades.result_object.forEach(function(campo, index){
                        if(campo.d_ciudad!=""){
                            agregarOptions("#"+ ciudad, campo.d_ciudad, campo.d_ciudad);
                            $("#"+ ciudad).css('border-color', '#ccc');
                        }else{
                            agregarOptions(ciudad, "N/A", "NO APLICA");
                            $("#"+ ciudad + " option[value='N/A']").attr("selected","selected");
                            $("#"+ ciudad).css('border-color', '#a94442');
                        }
                    });
                    respuesta.municipios.result_object.forEach(function(campo, index){
                        agregarOptions("#"+ municipio, campo.d_mnpio, campo.d_mnpio);
                    });
                    respuesta.colonias.result_object.forEach(function(campo, index){
                        agregarOptions("#"+ colonia, campo.id_codigo_postal, campo.d_asenta);
                    });
                }
            });
        }else{
            warning('Debe colocar al menos 5 caracteres para continuar.');
        }
    }
/* ------------------------------------------------------------------------------- */