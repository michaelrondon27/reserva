$(document).ready(function(){
	listar();
	registrar_rol();
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
				"url":url+"Roles/listado_roles",
				"dataSrc":""
			},
			"columns":[
				{"data": "id_rol",
					render : function(data, type, row) {
						return "<input type='checkbox' class='checkitem chk-col-blue' id='item"+data+"' value='"+data+"'><label for='item"+data+"'></label>"
					}
				},
				{"data":"nombre_rol"},
				{"data":"descripcion_rol",
					render : function(data, type, row) {
						var descripcion = data;
						if (data != null)
							if (data.length > 30)
								descripcion = data.substr(0,29) + "..."
						return descripcion;
					}
				},
				{"data":null,
					render : function(data, type, row) {
						var nombre = data.nombre_lista_vista;
						if (data.nombre_lista_vista != null)
							if (data.nombre_lista_vista.length > 25)
								nombre = data.nombre_lista_vista.substr(0,24) + "..."
							if(consultar == 0)
								nombre += " <span onclick='modalOperaciones(" + data.id_rol + ", \"" + "#resultados" + "\")' class='badge bg-blue waves-effect' style='cursor: pointer;' data-toggle='tooltip' title='Ver más.'><i class='fa fa-plus'></i></span>";
						return nombre
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
						if(actualizar == 0)
							botones += "<span class='editar btn btn-xs btn-primary waves-effect' data-toggle='tooltip' title='Editar'><i class='fa fa-pencil-square-o' style='margin-bottom:5px'></i></span> ";
						if(data.status == 1 && actualizar == 0)
							botones += "<span class='desactivar btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='Desactivar'><i class='fa fa-lock' style='margin-bottom:5px'></i></span> ";
						else if(data.status == 2 && actualizar == 0)
							botones+="<span class='activar btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='Activar'><i class='fa fa-unlock' style='margin-bottom:5px'></i></span> ";
						if(borrar == 0)
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
		limpiarFormularioRegistrar();
		$("#nombre_rol_registrar").focus();
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion para limpiar el formulario de registrar.
	*/
	function limpiarFormularioRegistrar(){
		$("#form_rol_registrar")[0].reset();
		$("#tableRegistrar tbody tr").remove(); 
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
            	lista_vista.push(id);
            	lista_vista.push(verificarCheckbox('consultar' + id));
            	lista_vista.push(verificarCheckbox('registrar' + id));
            	lista_vista.push(verificarCheckbox('actualizar' + id));
            	lista_vista.push(verificarCheckbox('eliminar' + id));
				objeto.push(lista_vista);
			});
            console.log(nombre);
            console.log(descripcion);
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
	            var table = "<table class='table table-bordered table-striped table-hover'><thead><tr>";
	            table += "<th>Nombre</th><th>Consultar</th><th>Registrar</th><th>Actualizar</th><th>Eliminar</th></tr></thead><tbody>";
	            respuesta.forEach(function(operacion, index){
	            	table += '<tr><th>' + operacion.nombre_lista_vista + '</th>';
	            	table += '<th>' + validarPermisoListaVista(operacion.consultar) + '</th>';
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
	function agregarListaVista(select, tabla){
		var value = $(select).val();
		var text = $(select + " option:selected").html();
		var validado = false;
		if (value != "") {
			$("#tableRegistrar tbody tr").each(function() {
			  	if (value == $(this).find(".id_lista_vista").val())
			  		validado = true;
			});
			if (!validado) {
				var html = "<tr id='r" + value + "'><td>" + text + " <input type='hidden' class='id_lista_vista' name='id_lista_vista' value='" + value + "'></td>";
				html += "<td>" + agregarCheckbox(value, 'consultar') + "</td>";
				html += "<td>" + agregarCheckbox(value, 'registrar') + "</td>";
				html += "<td>" + agregarCheckbox(value, 'actualizar') + "</td>";
				html += "<td>" + agregarCheckbox(value, 'eliminar') + "</td>";
				html += "<td><button type='button' class='btn btn-danger waves-effect' onclick='eliminarListaVista(\"" + "#r" + value + "\")'>Quitar</button></td></tr>";
				$(tabla + " tbody").append(html);
			} else {
				warning('¡La opción selecciona ya se encuentra agregada!');
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
	function agregarCheckbox(id, campo){
		return "<input type='checkbox' name='" + campo + "' class='checkitem chk-col-blue' id='" + campo + id + "' value='0'><label for='" + campo + id + "'></label>";
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que agrega las lista ista a la tabla
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