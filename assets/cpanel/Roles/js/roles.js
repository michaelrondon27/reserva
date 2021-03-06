$(document).ready(function(){
	listar();
	registrar_rol();
	actualizar_rol();
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
				"url":url+"Roles/listado_roles",
				"dataSrc":""
			},
			"columns":[
				{"data": null,
					render : function(data, type, row) {
						var checkbox = "";
						if (data.editable_rol == 0)
							checkbox = "<input type='checkbox' class='checkitem chk-col-blue' id='item"+data.id_rol+"' value='"+data.id_rol+"'><label for='item"+data.id_rol+"'></label>"
						return checkbox;
					}
				},
				{"data":"nombre_rol"},
				{"data":"descripcion_rol",
					render : function(data, type, row) {
						var descripcion = data;
						if (data != null)
							if (data.length > 20)
								descripcion = data.substr(0,19) + "..."
						return descripcion;
					}
				},
				{"data":null,
					render : function(data, type, row) {
						var nombre = "";
						if (data.nombre_lista_vista[0].nombre_lista_vista != null){
							if (data.nombre_lista_vista[0].nombre_lista_vista.length > 20){
								nombre = data.nombre_lista_vista[0].nombre_lista_vista.substr(0,19) + "...";
							}
							if(consultar == 0){
								nombre += " <span onclick='modalOperaciones(" + data.id_rol + ", \"" + "#resultados" + "\")' class='badge bg-blue waves-effect' style='cursor: pointer;' data-toggle='tooltip' title='Ver más.'><i class='fa fa-plus' style='margin-top:2px'></i></span>";
							}
						}
						return nombre;
					}
				},
				{"data":"fec_regins",
					render : function(data, type, row) {
						return cambiarFormatoFecha(data);
	          		}
				},
				{"data":"correo_usuario"},
				{"data": null,
					render : function(data, type, row) {
						var botones = "";
						if(consultar == 0)
							botones += "<span class='consultar btn btn-xs btn-info waves-effect' data-toggle='tooltip' title='Consultar'><i class='fa fa-eye' style='margin-bottom:5px'></i></span> ";
						if(actualizar == 0 && data.editable_rol == 0)
							botones += "<span class='editar btn btn-xs btn-primary waves-effect' data-toggle='tooltip' title='Editar'><i class='fa fa-pencil-square-o' style='margin-bottom:5px'></i></span> ";
						if(data.status == 1 && actualizar == 0 && data.editable_rol == 0)
							botones += "<span class='desactivar btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='Desactivar'><i class='fa fa-unlock' style='margin-bottom:5px'></i></span> ";
						else if(data.status == 2 && actualizar == 0 && data.editable_rol == 0)
							botones+="<span class='activar btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='Activar'><i class='fa fa-lock' style='margin-bottom:5px'></i></span> ";
						if(borrar == 0 && data.editable_rol == 0)
							return botones += "<span class='eliminar btn btn-xs btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></span>";
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
		ver("#tabla tbody", table);
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
	function nuevoRol(cuadroOcultar, cuadroMostrar){
		cuadros("#cuadro1", "#cuadro2");
		$("#form_rol_registrar")[0].reset();
		$("#tableRegistrar tbody tr").remove(); 
		$("#nombre_rol_registrar").focus();
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function registrar_rol(){
		$("#form_rol_registrar").submit(function(e){
            e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit
            var nombre = $("#nombre_rol_registrar").val();
            var descripcion = $("#descripcion_rol_registrar").val();
            var objeto = [];
            $("#tableRegistrar tbody tr").each(function() {
            	var lista_vista = [];
            	var id = $(this).find(".id_lista_vista").val();
            	if (id != undefined){
	            	lista_vista.push(id);
	            	lista_vista.push(verificarCheckbox('general' + id));
	            	lista_vista.push(verificarCheckbox('detallada' + id));
	            	lista_vista.push(verificarCheckbox('registrar' + id));
	            	lista_vista.push(verificarCheckbox('actualizar' + id));
	            	lista_vista.push(verificarCheckbox('eliminar' + id));
					objeto.push(lista_vista);
				}
			});
            $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
            $.ajax({
                url: document.getElementById('ruta').value + 'Roles/registrar_rol',
                type: 'POST',
                dataType:'JSON',
                data:{
                	'nombre_rol' : nombre,
                	'descripcion_rol' : descripcion,
                	'permisos' : objeto
                },
                cache:false,
                beforeSend: function(){
                    mensajes('info', '<span>Guardando datos, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
                },
                error: function (repuesta) {
                    $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                    var errores=repuesta.responseText;
                    if(errores!="")
                        mensajes('danger', errores);
                    else
                        mensajes('danger', "<span>Ha ocurrido un error, por favor intentelo de nuevo.</span>");        
                },
                success: function(respuesta){
                    $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                    mensajes('success', respuesta);
                    listar('#cuadro2');
                }
            });
        });
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro3 para la consulta del banco.
	*/
	function ver(tbody, table){
		$(tbody).on("click", "span.consultar", function(){
			var data = table.row( $(this).parents("tr") ).data();
			document.getElementById('nombre_rol_consultar').value=data.nombre_rol;
			document.getElementById('descripcion_rol_consultar').value=data.descripcion_rol;
			modalOperaciones(data.id_rol, '#operaciones_consultar');
			cuadros('#cuadro1', '#cuadro3');
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro4 para editar el banco.
	*/
	function editar(tbody, table){
		$("#form_rol_actualizar")[0].reset();
		$("#tableActualizar tbody tr").remove(); 
		$(tbody).on("click", "span.editar", function(){
			var data = table.row( $(this).parents("tr") ).data();
			document.getElementById('nombre_rol_actualizar').value=data.nombre_rol;
			document.getElementById('descripcion_rol_actualizar').value=data.descripcion_rol;
			document.getElementById('id_rol_actualizar').value=data.id_rol;
			listarOperacionesEditar(data.id_rol);
			cuadros('#cuadro1', '#cuadro4');
			$("#nombre_rol_actualizar").focus();
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
            eliminarConfirmacion('Roles/eliminar_rol', data.id_rol, "¿Esta seguro de eliminar el registro?");
        });
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function actualizar_rol(){
		$("#form_rol_actualizar").submit(function(e){
            e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit
            var id = $("#id_rol_actualizar").val();
            var nombre = $("#nombre_rol_actualizar").val();
            var descripcion = $("#descripcion_rol_actualizar").val();
            var objeto = [];
            $("#tableActualizar tbody tr").each(function() {
            	var lista_vista = [];
            	var id = $(this).find(".id_lista_vista").val();
            	if (id != undefined){
            		lista_vista.push(id);
	            	lista_vista.push(verificarCheckbox('general' + id));
	            	lista_vista.push(verificarCheckbox('detallada' + id));
	            	lista_vista.push(verificarCheckbox('registrar' + id));
	            	lista_vista.push(verificarCheckbox('actualizar' + id));
	            	lista_vista.push(verificarCheckbox('eliminar' + id));
					objeto.push(lista_vista);
            	}
			});
            $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
            $.ajax({
                url: document.getElementById('ruta').value + 'Roles/actualizar_rol',
                type: 'POST',
                dataType:'JSON',
                data:{
                	'id_rol' : id,
                	'nombre_rol' : nombre,
                	'descripcion_rol' : descripcion,
                	'permisos' : objeto
                },
                cache:false,
                beforeSend: function(){
                    mensajes('info', '<span>Guardando datos, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
                },
                error: function (repuesta) {
                    $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                    var errores=repuesta.responseText;
                    if(errores!="")
                        mensajes('danger', errores);
                    else
                        mensajes('danger', "<span>Ha ocurrido un error, por favor intentelo de nuevo.</span>");        
                },
                success: function(respuesta){
                    $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                    mensajes('success', respuesta);
                    listar('#cuadro4');
                }
            });
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
            statusConfirmacion('Roles/status_rol', data.id_rol, 2, "¿Esta seguro de desactivar el registro?", 'desactivar');
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
            statusConfirmacion('Roles/status_rol', data.id_rol, 1, "¿Esta seguro de activar el registro?", 'activar');
        });
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que hace una busqueda de loas operaciones que hace cada rol pasado
		por parametro.
	*/
	function modalOperaciones(id, div){
		if (div == "#resultados")
			$("#modalOperaciones").modal('show');
		$.ajax({
	        url:document.getElementById('ruta').value + 'Roles/operaciones_rol',
	        type:'POST',
	        dataType:'JSON',
	        data: {'id' : id},
	        beforeSend: function() {
	        	$(div).html(loading());
	        },
	        error: function() {
                var html ='<div class="alert alert-danger" role="alert">';
		        html += '<span>¡Se ha producido un error!. Presiona <strong style="text-decoration: underline;" onclick="modalOperaciones(' + id + ')">aquí</strong> para intentarlo de nuevo</span>';
		        html += '</div>';
		        $(div).html(html);
	        },
	        success: function(respuesta){
	        	var validado = false;
	        	var modulo = 0;
	            var table = "<table class='table table-bordered table-hover' id='consultaIndividual'><thead><tr>";
	            table += "<th>Nombre</th><th>Consultar General</th><th>Consultar Detallada</th><th>Registrar</th><th>Actualizar</th><th>Eliminar</th></tr></thead><tbody>";
	            respuesta.forEach(function(operacion, index){
	            	if (modulo != operacion.id_modulo_vista) {
	            		modulo = operacion.id_modulo_vista;
	            		validado = false;
	            	}
					if (!validado){
		            	table += "<tr style='background-color: #eee;'><th colspan='6'>" + operacion.nombre_modulo_vista + "</th></tr>";
		            	validado = true;
					}
					table += '<tr><th>' + operacion.nombre_lista_vista + '</th>';
	            	table += '<th>' + validarPermisoListaVista(operacion.general) + '</th>';
	            	table += '<th>' + validarPermisoListaVista(operacion.detallada) + '</th>';
	            	table += '<th>' + validarPermisoListaVista(operacion.registrar) + '</th>';
	            	table += '<th>' + validarPermisoListaVista(operacion.actualizar) + '</th>';
	            	table += '<th>' + validarPermisoListaVista(operacion.eliminar) + '</th><tr>';
	            });
	            table += '</tbody></table>';
	            $(div).html(table);
	        }
	    });
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que valida true o false de las operaciones que hacen por lista vista.
	*/
	function validarPermisoListaVista(operacion){
		var check = '<i class="fa fa-check-square-o col-green" aria-hidden="true"></i>';
	    var close = '<i class="fa fa-times col-red" aria-hidden="true"></i>';
	    if(operacion == 0)
	    	return check;
	    else if (operacion == 1)
	    	return close;
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que agrega las lista ista a la tabla
	*/
	function agregarListaVista(select, tabla, modulo){
		var idModulo = $(modulo).val();
		var nombreModulo = $(modulo + " option:selected").html();
		var value = $(select).val();
		var text = $(select + " option:selected").html();
		var validadoLista = false;
		var validadoModulo = false;
		var html = '';
		$(tabla + " tbody tr").each(function() {
			if (idModulo == $(this).find(".id_modulo_vista").val()) {
				validadoModulo = true;
			}
		});
		if (value != "") {
			$(tabla + " tbody tr").each(function() {
			  	if (value == $(this).find(".id_lista_vista").val())
			  		validadoLista = true;
			});
			if (!validadoModulo) {
				html += "<tr style='background-color: #eee;' id='mv" + idModulo + "'><td colspan='7'>" + nombreModulo + "<input type='hidden' class='id_modulo_vista' value='" + idModulo + "'></td></tr>";
			}
			if (!validadoLista) {
				html += "<tr id='r" + value + "'><td>" + text + " <input type='hidden' class='id_lista_vista' name='id_lista_vista' value='" + value + "'></td>";
				html += "<td>" + agregarCheckbox(value, 'general', 1) + "</td>";
				html += "<td>" + agregarCheckbox(value, 'detallada', 1) + "</td>";
				html += "<td>" + agregarCheckbox(value, 'registrar', 1) + "</td>";
				html += "<td>" + agregarCheckbox(value, 'actualizar', 1) + "</td>";
				html += "<td>" + agregarCheckbox(value, 'eliminar', 1) + "</td>";
				html += "<td><button type='button' class='btn btn-danger waves-effect' onclick='eliminarListaVista(\"" + "#r" + value + "\")'>Eliminar</button></td></tr>";
				if (validadoModulo) {
					$(tabla + " tbody #mv" + idModulo).after(html);
				} else {
					$(tabla + " tbody").append(html);
				}
			} else {
				warning('¡La opción seleccionada ya se encuentra agregada!');
			}
			$(select + " option[value='']").attr("selected","selected");
		} else {
			warning('¡Debe seleccionar una opción!');
		}
	}
/* ------------------------------------------------------------------------------- */


/* ------------------------------------------------------------------------------- */
	/*
		Funcion con codigo para generar un checkbox
	*/
	function agregarCheckbox(id, campo, checked){
		if(checked == 1)
			return "<input type='checkbox' name='" + campo + "' class='checkitem chk-col-blue' id='" + campo + id + "' value='0'><label for='" + campo + id + "'></label>";
		else if (checked == 0)
			return "<input type='checkbox' name='" + campo + "' class='checkitem chk-col-blue' id='" + campo + id + "' value='0' checked><label for='" + campo + id + "'></label>";
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que elimina la lista ista de la tabla
	*/
	function eliminarListaVista(tr){
		$(tr).remove(); 
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que verificar checkbox
	*/
	function verificarCheckbox(checkbox){
		if (document.getElementById(checkbox).checked)
			return 0
		else
			return 1
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que hace una busqueda de las operaciones que tiene el rol por cada
		lista vista y mostrar los resultados para su edicion
	*/
	function listarOperacionesEditar(id){
		$.ajax({
	        url:document.getElementById('ruta').value + 'Roles/operaciones_rol',
	        type:'POST',
	        dataType:'JSON',
	        data: {'id' : id},
	        beforeSend: function() {
	        	$("#esperarLoading").html(loading());
	        },
	        error: function() {
                var html ='<div class="alert alert-danger" role="alert">';
		        html += '<span>¡Se ha producido un error!. Presiona <strong style="text-decoration: underline;" onclick="listarOperacionesEditar(' + id + ')">aquí</strong> para intentarlo de nuevo</span>';
		        html += '</div>';
		        $("#esperarLoading").html(html);
	        },
	        success: function(respuesta){
	        	$("#esperarLoading").html('');
	        	$("#listarRoles").removeClass('ocultar');
	        	var validado = false;
	        	var modulo = 0;
	            respuesta.forEach(function(operacion, index){
	            	var html = '';
	            	if (modulo != operacion.id_modulo_vista) {
	            		modulo = operacion.id_modulo_vista;
	            		validado = false;
	            	}
					if (!validado){
		            	html += "<tr style='background-color: #eee;' id='mv" + operacion.id_modulo_vista + "'><td colspan='7'>" + operacion.nombre_modulo_vista + "<input type='hidden' class='id_modulo_vista' value='" + operacion.id_modulo_vista + "'></td></tr>";
		            	validado = true;
					}
					html += "<tr id='r" + operacion.id_lista_vista + "'><td>" + operacion.nombre_lista_vista + " <input type='hidden' class='id_lista_vista' name='id_lista_vista' value='" + operacion.id_lista_vista + "'></td>";
					html += "<td>" + agregarCheckbox(operacion.id_lista_vista, 'general', operacion.general) + "</td>";
					html += "<td>" + agregarCheckbox(operacion.id_lista_vista, 'detallada', operacion.detallada) + "</td>";
					html += "<td>" + agregarCheckbox(operacion.id_lista_vista, 'registrar', operacion.registrar) + "</td>";
					html += "<td>" + agregarCheckbox(operacion.id_lista_vista, 'actualizar', operacion.actualizar) + "</td>";
					html += "<td>" + agregarCheckbox(operacion.id_lista_vista, 'eliminar', operacion.eliminar) + "</td>";
					html += "<td><button type='button' class='btn btn-danger waves-effect' onclick='eliminarRolOperacion(" + operacion.id_rol_operaciones + ", " + id + ")'>Eliminar</button></td></tr>";
					$("#tableActualizar tbody").append(html);
	            });
	        }
	    });
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que hace una busqueda de las operaciones que tiene el rol por cada
		lista vista y mostrar los resultados para su edicion
	*/
	function eliminarRolOperacion(id_rol_operacion, id_rol){
		swal({
            title: '¿Esta seguro de eliminar este registro?',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, Eliminar!",
            cancelButtonText: "No, Cancelar!",
            closeOnConfirm: true,
            closeOnCancel: false
        },
        function(isConfirm){
            if (isConfirm) {
                swal.close();
                $.ajax({
                    url: document.getElementById('ruta').value + "Roles/eliminar_rol_operacion",
                    type: 'POST',
                    dataType: 'JSON',
                    data:{
                        'id' : id_rol_operacion,
                    },
                    error: function (repuesta) {
                        var errores=repuesta.responseText;
                        mensajes('danger', errores);
                    },
                    success: function(respuesta){
                        mensajes('success', respuesta);
                        $("#tableActualizar").find("tbody tr#r" + id_rol).remove();
                    }
                });
            } else {
                swal("Cancelado", "No se ha eliminado el registro", "error");
            }
        });
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que hace una busqueda de las funciones del modulo seleccionado
	*/
	function buscarFunciones(id_modulo, funciones){
		if (id_modulo != "") {
			eliminarOptions(funciones);
			$.ajax({
                url: document.getElementById('ruta').value + "Roles/buscarListaVista",
                type: 'POST',
                dataType: 'JSON',
                data:{
                    'id_modulo' : id_modulo,
                },
                success: function(respuesta){
                    respuesta.forEach(function(campo, index){
                        agregarOptions("#"+ funciones, campo.id_lista_vista, campo.nombre_lista_vista);
                    });
                }
            });
		} else {
			warning('¡Debe seleccionar una opción!');
		}
	}
/* ------------------------------------------------------------------------------- */