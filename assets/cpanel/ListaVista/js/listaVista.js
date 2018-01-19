$(document).ready(function(){
	listar();
	registrar_modulo();
	actualizar_modulo();
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
				"url":url+"ListaVista/listado_listaVista",
				"dataSrc":""
			},
			"columns":[
				{"data": "id_lista_vista",
					render : function(data, type, row) {
						return "<input type='checkbox' class='checkitem chk-col-blue' id='item"+data+"' value='"+data+"'><label for='item"+data+"'></label>"
					}
				},
				{"data":"nombre_lista_vista"},
				{"data":"descripcion_lista_vista"},
				{"data":"nombre_modulo_vista"},
				{"data":"posicion_lista_vista"},
				{"data":"fec_regins",
					render : function(data, type, row) {
						return cambiarFormatoFecha(data);
	          		}
				},
				{"data":"correo_usuario"},
				{"data": null,
					render : function(data, type, row) {
						var botones="<span class='consultar btn btn-xs btn-info' data-toggle='tooltip' title='Consultar'><i class='fa fa-eye' style='margin-bottom:5px'></i></span> ";
						if(actualizar == 0){
							botones+="<span class='editar btn btn-xs btn-primary' data-toggle='tooltip' title='Editar'><i class='fa fa-pencil-square-o' style='margin-bottom:5px'></i></span> ";
							if(data.status == 1){
								botones+="<span class='desactivar btn btn-xs btn-warning' data-toggle='tooltip' title='Desactivar'><i class='fa fa-lock' style='margin-bottom:5px'></i></span> ";
							}else if(data.status == 2){
								botones+="<span class='activar btn btn-xs btn-warning' data-toggle='tooltip' title='Activar'><i class='fa fa-unlock' style='margin-bottom:5px'></i></span> ";
							}
						}
						if(borrar == 0){
							return botones+="<span class='eliminar btn btn-xs btn-danger' data-toggle='tooltip' title='Eliminar'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></span>";
						}
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
	function nuevoListaVista(cuadroOcultar, cuadroMostrar){
		cuadros("#cuadro1", "#cuadro2");
		limpiarFormularioRegistrar();
		$("#nombre_modulo_vista_registrar").focus();
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion para limpiar el formulario de registrar.
	*/
	function limpiarFormularioRegistrar(){
		$("#form_modulo_registrar")[0].reset();
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function registrar_modulo(){
		enviarFormulario("#form_modulo_registrar", 'Modulos/registrar_modulo', '#cuadro2');
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro3 para la consulta del banco.
	*/
	function consultar(tbody, table){
		$(tbody).on("click", "span.consultar", function(){
			var data = table.row( $(this).parents("tr") ).data();
			document.getElementById('nombre_lista_vista_consultar').value=data.nombre_lista_vista;
			document.getElementById('descripcion_lista_vista_consultar').value=data.descripcion_lista_vista;
			$('#posicion_lista_vista_consultar').find('option').remove().end().append('<option value="'+data.posicion_lista_vista+'">'+data.posicion_lista_vista+'</option>');
			document.getElementById('url_lista_vista_consultar').value=data.url_lista_vista;
			$("#visibilidad_lista_vista_consultar option[value='"+data.visibilidad_lista_vista+"']").attr("selected","selected");
			$("#id_modulo_vista_consultar option[value='"+data.id_modulo_vista+"']").attr("selected","selected");
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
			document.getElementById('nombre_modulo_vista_actualizar').value=data.nombre_modulo_vista;
			document.getElementById('descripcion_modulo_vista_actualizar').value=data.descripcion_modulo_vista;
			$("#posicion_modulo_vista_actualizar option[value='"+data.posicion_modulo_vista+"']").attr("selected","selected");
			document.getElementById('inicial').value=data.posicion_modulo_vista;
			document.getElementById('id_modulo_vista_actualizar').value=data.id_modulo_vista;
			cuadros('#cuadro1', '#cuadro4');
			$("#nombre_modulo_vista_actualizar").focus();
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a eliminar
	*/
	function eliminar(tbody, table){
		$(tbody).on("click", "span.eliminar", function(){
            var data=table.row($(this).parents("tr")).data();
            eliminarConfirmacion('Modulos/eliminar_modulo', data.id_modulo_vista, "¿Esta seguro de eliminar el registro?");
        });
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function actualizar_modulo(){
		enviarFormulario("#form_modulo_actualizar", 'Modulos/actualizar_modulo', '#cuadro4');
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a desactivar
	*/
	function desactivar(tbody, table){
		$(tbody).on("click", "span.desactivar", function(){
            var data=table.row($(this).parents("tr")).data();
            statusConfirmacion('Modulos/status_modulo', data.id_modulo_vista, 2, "¿Esta seguro de desactivar el registro?", 'desactivar');
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
            statusConfirmacion('Modulos/status_modulo', data.id_modulo_vista, 1, "¿Esta seguro de activar el registro?", 'activar');
        });
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que hace un count de las lista vista por modulos registrados y el resultado se 
		despliega en un select para la seleccion de la posicion de la lista vista.
	*/
	function contador_listaVista(value, select){
		$('#posicion_modulo_vista_registrar').find('option').remove().end().append('<option value="">Seleccione</option>');
		$('#posicion_modulo_vista_actualizar').find('option').remove().end().append('<option value="">Seleccione</option>');
		if (value != "") {
			$.ajax({
		        url:document.getElementById('ruta').value + 'ListaVista/contador_listaVista',
		        type:'POST',
		        dataType:'JSON',
		        data:{'id': value},
		        error: function () {
                    contador_listaVista(value, select);
                },
		        success: function (respuesta) {
		            var contador = Object.keys(respuesta).length;
		            if (select == 'registrar') {
		            	contador++;
		            }
		            for (var i = 1; i <= contador; i++) {
		            	agregarOptions("#posicion_modulo_vista_" + select, i, i);
		            }
		        }
		    });
		} else {
			warning('¡Debe seleccionar un modulo!');
		}
	}
/* ------------------------------------------------------------------------------- */

