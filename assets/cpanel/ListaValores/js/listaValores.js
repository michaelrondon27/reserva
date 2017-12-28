$(document).ready(function(){
	$("#configuracion").attr('class', 'active');
	$("#listaValor").attr('class', 'active');
	listar();
	registrar_lval();
	actualizar_lval();
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
				"url":url+"ListaValores/listado_valores",
				"dataSrc":""
			},
			"columns":[
				{"data": "codlval",
					render : function(data, type, row) {
						return "<input type='checkbox' class='checkitem chk-col-blue' id='item"+data+"' value='"+data+"'><label for='item"+data+"'></label>"
					}
				},
				{"data":"descriptipolval"},
				{"data":"descriplval"},
				{"data":"fec_regins",
					render : function(data, type, row) {
						return cambiarFormatoFecha(data);
	          		}
				},
				{"data":"correo_usuario"},
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
		          	}
				}
			],
			"language": idioma_espanol,
			"dom": 'Bfrtip',
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
	function nuevaListaValor(cuadroOcultar, cuadroMostrar){
		cuadros("#cuadro1", "#cuadro2");
		limpiarFormularioRegistrar();
		$("#tipolval_registrar").focus();
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion para limpiar el formulario de registrar.
	*/
	function limpiarFormularioRegistrar(){
		document.getElementById('tipolval_registrar').value="";
		document.getElementById('descriplval_registrar').value="";
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function registrar_lval(){
		enviarFormulario("#form_valor_registrar", 'ListaValores/registrar_lval');
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro3 para la consulta de la plaza.
	*/
	function consultar(tbody, table){
		$(tbody).on("click", "span.consultar", function(){
			var data = table.row( $(this).parents("tr") ).data();
			document.getElementById('tipolval_consultar').value=data.tipolval;
			document.getElementById('descriplval_consultar').value=data.descriplval;
			cuadros('#cuadro1', '#cuadro3');
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/* 
		Funcion que muestra el cuadro4 para editar la plaza.
	*/
	function editar(tbody, table){
		$(tbody).on("click", "span.editar", function(){
			var data = table.row( $(this).parents("tr") ).data();
			document.getElementById('codlval_editar').value=data.codlval;
			document.getElementById('tipolval_editar').value=data.tipolval;
			document.getElementById('descriplval_editar').value=data.descriplval;
			cuadros('#cuadro1', '#cuadro4');
			$("#nombre_plaza_editar").focus();
		});
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que realiza el envio del formulario de registro
	*/
	function actualizar_lval(){
		enviarFormulario("#form_lval_actualizar", 'ListaValores/actualizar_lval');
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a eliminar
	*/
	function eliminar(tbody, table){
		$(tbody).on("click", "span.eliminar", function(){
            var data=table.row($(this).parents("tr")).data();
            eliminarConfirmacion('ListaValores/eliminar_lval', data.codlval)
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
            statusConfirmacion('ListaValores/status_lval', data.codlval, 2, 'desactivar')
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
            statusConfirmacion('ListaValores/status_lval', data.codlval, 1, 'activar')
        });
	}
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
	/*
		Funcion que filtra por tipo de valor en la tabla
	*/
	function filtrar(value){
		$('#tabla').DataTable().search(value).draw();
	}
/* ------------------------------------------------------------------------------- */