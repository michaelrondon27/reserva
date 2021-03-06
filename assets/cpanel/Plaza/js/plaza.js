$(document).ready(function(){
	listar();
	registrar_plaza();
	actualizar_plaza();
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
				"url":url+"PlazasBancarias/listado_plazas",
				"dataSrc":""
			},
			"columns":[
				{"data": "id_plaza",
					render : function(data, type, row) {
						return "<input type='checkbox' class='checkitem chk-col-blue' id='item"+data+"' value='"+data+"'><label for='item"+data+"'></label>"
					}
				},
				{"data":"cod_plaza"},
				{"data":"nombre_plaza"},
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
							botones+="<span class='editar btn btn-xs btn-primary waves-effect' data-toggle='tooltip' title='Editar'><i class='fa fa-pencil-square-o' style='margin-bottom:5px'></i></span> ";
		              	if(data.status == 1 && actualizar == 0)
							botones+="<span class='desactivar btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='Desactivar'><i class='fa fa-unlock' style='margin-bottom:5px'></i></span> ";
						else if(data.status == 2 && actualizar == 0)
							botones+="<span class='activar btn btn-xs btn-warning waves-effect' data-toggle='tooltip' title='Activar'><i class='fa fa-lock' style='margin-bottom:5px'></i></span> ";
						if(borrar == 0)
		              		botones+="<span class='eliminar btn btn-xs btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fa fa-trash-o' style='margin-bottom:5px'></i></span>";
		          		return botones;
		          	}
				}
			],
			"language": idioma_espanol,
			"dom": 'Bfrtip',
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
	function nuevoPlaza(cuadroOcultar, cuadroMostrar){
		cuadros("#cuadro1", "#cuadro2");
		$("#form_plaza_registrar")[0].reset();
		$("#cod_plaza_registrar").focus();
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function registrar_plaza(){
		enviarFormulario("#form_plaza_registrar", 'PlazasBancarias/registrar_plaza', '#cuadro2');
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro3 para la consulta de la plaza.
	*/
	function ver(tbody, table){
		$(tbody).on("click", "span.consultar", function(){
			var data = table.row( $(this).parents("tr") ).data();
			document.getElementById('cod_plaza_consultar').value=data.cod_plaza;
			document.getElementById('nombre_plaza_consultar').value=data.nombre_plaza;
			cuadros('#cuadro1', '#cuadro3');
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro4 para editar la plaza.
	*/
	function editar(tbody, table){
		$("#form_plaza_actualizar")[0].reset();
		$(tbody).on("click", "span.editar", function(){
			var data = table.row( $(this).parents("tr") ).data();
			document.getElementById('id_plaza_editar').value=data.id_plaza;
			document.getElementById('cod_plaza_editar').value=data.cod_plaza;
			document.getElementById('nombre_plaza_editar').value=data.nombre_plaza;
			cuadros('#cuadro1', '#cuadro4');
			$("#nombre_plaza_editar").focus();
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function actualizar_plaza(){
		enviarFormulario("#form_plaza_actualizar", 'PlazasBancarias/actualizar_plaza', '#cuadro4');
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a eliminar
	*/
	function eliminar(tbody, table){
		$(tbody).on("click", "span.eliminar", function(){
            var data=table.row($(this).parents("tr")).data();
            eliminarConfirmacion('PlazasBancarias/eliminar_plaza', data.id_plaza, "¿Esta seguro de eliminar el registro?")
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
            statusConfirmacion('PlazasBancarias/status_plaza', data.id_plaza, 2, "¿Esta seguro de desactivar el registro?", 'desactivar')
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
            statusConfirmacion('PlazasBancarias/status_plaza', data.id_plaza, 1, "¿Esta seguro de activar el registro?", 'activar')
        });
	}
/* ------------------------------------------------------------------------------- */