<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
	<link href="<?=base_url();?>assets/template/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
	<?php if(($permiso[0]->general==1 && $permiso[0]->detallada==1 && $permiso[0]->registrar==1 && $permiso[0]->actualizar==1 && $permiso[0]->eliminar==1) OR $permiso[0]->status==2): ?>
		<script src="<?=base_url();?>assets/cpanel/js/permiso.js"></script>
	<?php endif ?>
	<body class="theme-blue">
		<input type="hidden" id="ruta" value="<?=base_url();?>" name="ruta">
		<section class="content">
	        <div class="container-fluid">
	        	<div id="alertas"></div>
				<div class="block-header">
	                <ol class="breadcrumb breadcrumb-col-cyan">
                        <li><a href="javascript:void(0);"><?php echo $breadcrumbs->nombre_modulo_vista; ?></a></li>
                        <li><?php echo $breadcrumbs->nombre_lista_vista; ?></li>
                    </ol>
	            </div>
	        	<!-- Comienzo del cuadro de la tabla -->
					<div class="row clearfix" id="cuadro1">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>
		                                Gestión de <?php echo $breadcrumbs->nombre_lista_vista; ?>
		                            </h2>
		                            <ul class="header-dropdown m-r--5">
		                                <button class="btn btn-primary waves-effect registrar ocultar" onclick="nuevoBanco()"><i class='fa fa-plus-circle' style="color: white; font-size: 18px;"></i> | Nuevo</button>
		                            </ul>
		                        </div>
		                        <div class="body">
		                            <div class="table-responsive">
		                                <table class="table table-bordered table-striped table-hover" id="tabla">
		                                    <thead>
		                                        <tr>
		                                        	<th style="text-align: center; padding: 0px 10px 0px 5px;"><input type="checkbox" id="checkall" class="chk-col-blue"/><label for="checkall"></label></th>
		                                            <th>Código</th>
		                                            <th>Nombre o Razón Social</th>
		                                            <th>Fecha de Registro</th>
		                                            <th>Registrado Por</th>
		                                            <th style="width: 17%;">Acciones</th>
		                                        </tr>
		                                    </thead>
		                                    <tbody></tbody>
		                                </table>
		                                <div class="col-md-2 eliminar ocultar">
		                                	<button class="btn btn-danger waves-effect" onclick="eliminarMultiple('Bancos/eliminar_multiple_banco')">Eliminar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 actualizar ocultar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('Bancos/status_multiple_banco', 1, 'activar')">Activar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 actualizar ocultar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('Bancos/status_multiple_banco', 2, 'desactivar')">Desactivar seleccionados</button>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        <!-- Cierre del cuadro de la tabla -->

		        <!-- Comienzo del cuadro de registrar banco -->
					<div class="row clearfix ocultar" id="cuadro2">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Registro de <?php echo $breadcrumbs->nombre_lista_vista; ?></h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
			                            <form name="form_banco_registrar" id="form_banco_registrar" method="post">
			                            	<div class="col-sm-6">
			                            		<label for="cod_banco_registrar">Código*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control" name="cod_banco" id="cod_banco_registrar" onkeypress='return solonumeros(event)' maxlength="4" placeholder="P. EJ. 002" required>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-6">
				                                <label for="nombre_banco_registrar">Nombre o Razón Social*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="nombre_banco" id="nombre_banco_registrar" placeholder="P. EJ. BANCO NACIONAL DE MÉXICO, S.A." required>
				                                    </div>
				                                </div>
				                            </div>
                                			<br>
                                			<div class="col-sm-4 col-sm-offset-5">
		                                        <button type="button" onclick="regresar('#cuadro2')" class="btn btn-primary waves-effect">Regresar</button>
		                                        <input type="submit" value="Guardar" class="btn btn-success waves-effect">
			                                </div>
			                            </form>
			                        </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        <!-- Cierre del cuadro de registrar banco -->

		        <!-- Comienzo del cuadro de consultar banco -->
					<div class="row clearfix ocultar" id="cuadro3">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Consultar <?php echo $breadcrumbs->nombre_lista_vista; ?></h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
		                            	<div class="col-sm-6">
		                            		<label>Código</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control" id="cod_banco_consultar" disabled>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-6">
			                                <label>Nombre o Razón Social</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control mayusculas" id="nombre_banco_consultar" disabled>
			                                    </div>
			                                </div>
			                            </div>
                            			<br>
                            			<div class="col-sm-2 col-sm-offset-5">
	                                        <button type="button" onclick="regresar('#cuadro3')" class="btn btn-primary waves-effect">Regresar</button>
		                                </div>
			                        </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        <!-- Cierre del cuadro de consultar banco -->

		        <!-- Comienzo del cuadro de editar banco -->
					<div class="row clearfix ocultar" id="cuadro4">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Editar de <?php echo $breadcrumbs->nombre_lista_vista; ?></h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
			                            <form name="form_banco_actualizar" id="form_banco_actualizar" method="post">
			                            	<div class="col-sm-6">
			                            		<label for="cod_banco_editar">Código*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control" id="cod_banco_editar" disabled>
				                                    </div>
				                                </div>
				                            </div>
				                            <input type="hidden" class="form-control" name="id_banco" id="id_banco_editar">
				                            <div class="col-sm-6">
				                                <label for="nombre_banco_editar">Nombre o Razón Social*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="nombre_banco" id="nombre_banco_editar" placeholder="P. EJ. BANCO NACIONAL DE MÉXICO, S.A." required>
				                                    </div>
				                                </div>
				                            </div>
                                			<br>
                                			<div class="col-sm-4 col-sm-offset-5">
		                                        <button type="button" onclick="regresar('#cuadro4')" class="btn btn-primary waves-effect">Regresar</button>
		                                        <input type="submit" value="Guardar" class="btn btn-success waves-effect">
			                                </div>
			                            </form>
			                        </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        <!-- Cierre del cuadro de editar banco -->
			</div>
		</section>
	</body>
	<script src="<?=base_url();?>assets/template/plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>
    <script src="<?=base_url();?>assets/cpanel/Bancos/js/bancos.js"></script>
    <script>
		$("#mv<?php echo $permiso[0]->id_modulo_vista ?>").attr('class', 'active');
		$("#lv<?php echo $permiso[0]->id_lista_vista ?>").attr('class', 'active');
		var consultar = <?php echo $permiso[0]->detallada ?>,
			registrar = <?php echo $permiso[0]->registrar ?>,
			actualizar = <?php echo $permiso[0]->actualizar ?>,
			borrar = <?php echo $permiso[0]->eliminar ?>;
		if(registrar==0)
			$(".registrar").removeClass('ocultar');
		if(actualizar==0)
			$(".actualizar").removeClass('ocultar');
		if(borrar==0)
			$(".eliminar").removeClass('ocultar');
	</script>
</html>
