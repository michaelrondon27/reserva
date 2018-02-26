$(document).ready(function(){
	listar();
	registrar_proyecto();
	actualizar_proyecto();
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
				"url": url + "Proyectos/listado_proyectos",
				"dataSrc":""
			},
			"columns":[
				{"data": "id_proyecto",
					render : function(data, type, row) {
						return "<input type='checkbox' class='checkitem chk-col-blue' id='item"+data+"' value='"+data+"'><label for='item"+data+"'></label>"
					}
				},
				{"data":"codigo"},
				{"data":"nombre",
					render : function(data, type, row) {
						var descripcion = data;
						if (descripcion.length > 15)
							descripcion = data.substr(0,14) + "..."
						return descripcion;
	          		}
				},
				{"data":"descripcion",
					render : function(data, type, row) {
						var descripcion = data;
						if (descripcion.length > 15)
							descripcion = data.substr(0,14) + "..."
						return descripcion;
	          		}
				},
				{"data": null,
					render : function(data, type, row) {
						var nombres = data.nombres + " " + data.paterno + " " + data.materno;
						if (nombres.length > 15)
							descripcion = nombres.substr(0,14) + "..."
						return descripcion;
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
							botones += "<span class='desactivar btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='Desactivar'><i class='fa fa-unlock' style='margin-bottom:5px'></i></span> ";
						else if(data.status == 2 && actualizar == 0)
							botones += "<span class='activar btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='Activar'><i class='fa fa-lock' style='margin-bottom:5px'></i></span> ";
						if(borrar == 0)
		              		botones += "<span class='eliminar btn btn-xs btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></span>";
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
	function nuevoProyecto(cuadroOcultar, cuadroMostrar){
		cuadros("#cuadro1", "#cuadro2");
		$("#form_proyecto_registrar")[0].reset();
		$("#codigo_registrar").focus();
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function registrar_proyecto(){
		enviarFormulario("#form_proyecto_registrar", 'Proyectos/registrar_proyecto', '#cuadro2');
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro3 para la consulta del banco.
	*/
	function ver(tbody, table){
		$(tbody).on("click", "span.consultar", function(){
			var data = table.row( $(this).parents("tr") ).data();
			document.getElementById('codigo_consultar').value=data.codigo;
			document.getElementById('nombre_consultar').value=data.nombre;
			document.getElementById('descripcion_consultar').value=data.descripcion;
			$("#director_consultar option[value='" + data.director + "']").attr("selected","selected");
			if ( data.plano != "") {
				$("#no_plano").addClass('ocultar');
				$("#plano_consultar").removeClass('ocultar');
				$("#plano_consultar").attr('href', document.getElementById('ruta').value + "assets/cpanel/Proyectos/planos/"  + data.plano).attr('download', "PLANOS_PROYECTO_" + data.codigo);
			} else {
				$("#plano_consultar").addClass('ocultar');
				$("#no_plano").removeClass('ocultar');
			}
			cuadros('#cuadro1', '#cuadro3');
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro4 para editar el banco.
	*/
	function editar(tbody, table){
		$("#form_proyecto_actualizar")[0].reset();
		$(tbody).on("click", "span.editar", function(){
			var data = table.row( $(this).parents("tr") ).data();
			document.getElementById('codigo_editar').value=data.codigo;
			document.getElementById('nombre_editar').value=data.nombre;
			document.getElementById('descripcion_editar').value=data.descripcion;
			document.getElementById('id_proyecto_editar').value=data.id_proyecto;
			$("#director_editar option[value='" + data.director + "']").attr("selected","selected");
			cuadros('#cuadro1', '#cuadro4');
			$("#codigo_editar").focus();
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function actualizar_proyecto(){
		enviarFormulario("#form_proyecto_actualizar", 'Proyectos/actualizar_proyecto', '#cuadro4');
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a eliminar
	*/
	function eliminar(tbody, table){
		$(tbody).on("click", "span.eliminar", function(){
            var data=table.row($(this).parents("tr")).data();
            eliminarConfirmacion('Proyectos/eliminar_proyecto', data.id_proyecto, "¿Esta seguro de eliminar el registro?");
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
            statusConfirmacion('Proyectos/status_proyecto', data.id_proyecto, 2, "¿Esta seguro de desactivar el registro?", 'desactivar');
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
            statusConfirmacion('Proyectos/status_proyecto', data.id_proyecto, 1, "¿Esta seguro de activar el registro?", 'activar');
        });
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que agrega las inombiliaria a la tabla
	*/
	function agregarInmobiliaria(select, tabla){
		var idInmobiliaria = $(select).val();
		var nombreInmobiliaria = $(select + " option:selected").html();
		var validadoInmobiliaria = false;
		var html = '';
		$(tabla + " tbody tr").each(function() {
		  	if (value == $(this).find(".id_inmobiliaria").val())
		  		validadoInmobiliaria = true;
		});
		if (!validadoInmobiliaria) {
			html += "<tr id='i" + idInmobiliaria + "'><td>" + nombreInmobiliaria + " <input type='hidden' class='id_inmobiliaria' name='id_inmobiliaria' value='" + vidInmobiliariaalue + "'></td>";
			html += "<td><button type='button' class='btn btn-danger waves-effect' onclick='eliminarInmobiliaria(\"" + "#i" + idInmobiliaria + "\")'>Eliminar</button></td></tr>";
			$(tabla + " tbody").append(html);
		} else {
			warning('¡La opción seleccionada ya se encuentra agregada!');
		}
		$(select + " option[value='']").attr("selected","selected");
	}
/* ------------------------------------------------------------------------------- */