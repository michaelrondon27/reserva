<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
	<link href="<?=base_url();?>assets/template/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
	<?php if(($permiso[0]->consultar==1 && $permiso[0]->registrar==1 && $permiso[0]->actualizar==1 && $permiso[0]->eliminar==1) OR $permiso[0]->status==2): ?>
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
		                                <button class="btn btn-primary waves-effect registrar ocultar" onclick="nuevoEsquemaComision()"><i class='fa fa-plus-circle' style="color: white; font-size: 18px;"></i> | Nuevo</button>
		                            </ul>
		                        </div>
		                        <div class="body">
		                            <div class="table-responsive">
		                                <table class="table table-bordered table-striped table-hover" id="tabla">
		                                    <thead>
		                                        <tr>
		                                        	<th style="text-align: center; padding: 0px 10px 0px 5px;"><input type="checkbox" id="checkall" class="chk-col-blue"/><label for="checkall"></label></th>
		                                            <th>Clasificación</th>
		                                            <th>Tipo de Vendedor</th>
		                                            <th>Ventas al mes</th>
		                                            <th>Tipo de Plazo</th>
		                                            <th>Porcentaje de Comisión</th>
		                                            <th>Fecha de Registro</th>
		                                            <th>Registrado Por</th>
		                                            <th style="width: 17%;">Acciones</th>
		                                        </tr>
		                                    </thead>
		                                    <tbody></tbody>
		                                </table>
		                                <div class="col-md-2 eliminar ocultar">
		                                	<button class="btn btn-danger waves-effect" onclick="eliminarMultiple('EsquemaComision/eliminar_multiple_esquema_comision')">Eliminar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 actualizar ocultar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('EsquemaComision/status_multiple_esquema_comision', 1, 'activar')">Activar seleccionados</button>
		                                </div>
		                                <div class="col-md-2 actualizar ocultar">
		                                	<button class="btn btn-warning waves-effect" onclick="statusMultiple('EsquemaComision/status_multiple_esquema_comision', 2, 'desactivar')">Desactivar seleccionados</button>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        <!-- Cierre del cuadro de la tabla -->

		        <!-- Comienzo del cuadro de registrar Esquema de Comisión -->
					<div class="row clearfix ocultar" id="cuadro2">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Registro de <?php echo $breadcrumbs->nombre_lista_vista; ?></h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
			                            <form name="form_esquema_comision_registrar" id="form_esquema_comision_registrar" method="post">
			                            	<div class="col-sm-4">
			                            		<label for="id_vendedor_registrar">Clasificación*</label>
		                                    	<select name="id_vendedor" id="id_vendedor_registrar" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($id_vendedores as $id_vendedor): ?>
		                                    			<option value="<?= $id_vendedor->codlval; ?>"><?= $id_vendedor->descriplval; ?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
				                            <div class="col-sm-4">
			                            		<label for="tipo_vendedor_registrar">Tipo de Vendedor*</label>
		                                    	<select name="tipo_vendedor" id="tipo_vendedor_registrar" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($tipos_vendedores as $tipo_vendedor): ?>
		                                    			<option value="<?= $tipo_vendedor->codlval; ?>"><?= $tipo_vendedor->descriplval; ?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
				                            <div class="col-sm-4">
			                            		<label for="tipo_plazo_registrar">Tipo de Plazo*</label>
		                                    	<select name="tipo_plazo" id="tipo_plazo_registrar" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($tipos_plazos as $tipo_plazo): ?>
		                                    			<option value="<?= $tipo_plazo->codlval; ?>"><?= $tipo_plazo->descriplval; ?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
			                            	<div class="col-sm-6">
			                            		<label for="num_ventas_mes_registrar">Ventas al mes*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control" name="num_ventas_mes" id="num_ventas_mes_registrar" onkeypress='return solonumeros(event)' placeholder="P. EJ. 2" required>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-6">
				                                <label for="porctj_comision_registrar">Porcentaje de Comisión*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control porcentaje" name="porctj_comision" id="porctj_comision_registrar" placeholder="P. EJ. 00,00" required>
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
		        <!-- Cierre del cuadro de registrar Esquema de Comisión -->

		        <!-- Comienzo del cuadro de consultar Esquema de Comisión -->
					<div class="row clearfix ocultar" id="cuadro3">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Consultar <?php echo $breadcrumbs->nombre_lista_vista; ?></h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
		                            	<div class="col-sm-4">
		                            		<label for="id_vendedor_consultar">Clasificación*</label>
	                                    	<select id="id_vendedor_consultar" class="form-control" disabled>
	                                    		<option value="" selected>Seleccione</option>
	                                    		<?php foreach ($id_vendedores as $id_vendedor): ?>
	                                    			<option value="<?= $id_vendedor->codlval; ?>"><?= $id_vendedor->descriplval; ?></option>
	                                    		<?php endforeach ?>
	                                    	</select>
			                            </div>
			                            <div class="col-sm-4">
		                            		<label for="tipo_vendedor_consultar">Tipo de Vendedor*</label>
	                                    	<select id="tipo_vendedor_consultar" class="form-control" disabled>
	                                    		<option value="" selected>Seleccione</option>
	                                    		<?php foreach ($tipos_vendedores as $tipo_vendedor): ?>
	                                    			<option value="<?= $tipo_vendedor->codlval; ?>"><?= $tipo_vendedor->descriplval; ?></option>
	                                    		<?php endforeach ?>
	                                    	</select>
			                            </div>
			                            <div class="col-sm-4">
		                            		<label for="tipo_plazo_consultar">Tipo de Plazo*</label>
	                                    	<select id="tipo_plazo_consultar" class="form-control" disabled>
	                                    		<option value="" selected>Seleccione</option>
	                                    		<?php foreach ($tipos_plazos as $tipo_plazo): ?>
	                                    			<option value="<?= $tipo_plazo->codlval; ?>"><?= $tipo_plazo->descriplval; ?></option>
	                                    		<?php endforeach ?>
	                                    	</select>
			                            </div>
		                            	<div class="col-sm-6">
		                            		<label for="num_ventas_mes_consultar">Ventas al mes*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control" id="num_ventas_mes_consultar" onkeypress='return solonumeros(event)' disabled>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-6">
			                                <label for="porctj_comision_consultar">Porcentaje de Comisión*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control porcentaje"  id="porctj_comision_consultar" disabled>
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
		        <!-- Cierre del cuadro de consultar Esquema de Comisión -->

		        <!-- Comienzo del cuadro de editar Esquema de Comisión -->
					<div class="row clearfix ocultar" id="cuadro4">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Editar de <?php echo $breadcrumbs->nombre_lista_vista; ?></h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
			                            <form name="form_esquema_comision_actualizar" id="form_esquema_comision_actualizar" method="post">
			                            	<div class="col-sm-4">
			                            		<label for="id_vendedor_actualizar">Clasificación*</label>
		                                    	<select name="id_vendedor" id="id_vendedor_actualizar" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($id_vendedores as $id_vendedor): ?>
		                                    			<option value="<?= $id_vendedor->codlval; ?>"><?= $id_vendedor->descriplval; ?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
				                            <div class="col-sm-4">
			                            		<label for="tipo_vendedor_actualizar">Tipo de Vendedor*</label>
		                                    	<select name="tipo_vendedor" id="tipo_vendedor_actualizar" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($tipos_vendedores as $tipo_vendedor): ?>
		                                    			<option value="<?= $tipo_vendedor->codlval; ?>"><?= $tipo_vendedor->descriplval; ?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
				                            <div class="col-sm-4">
			                            		<label for="tipo_plazo_actualizar">Tipo de Plazo*</label>
		                                    	<select name="tipo_plazo" id="tipo_plazo_actualizar" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($tipos_plazos as $tipo_plazo): ?>
		                                    			<option value="<?= $tipo_plazo->codlval; ?>"><?= $tipo_plazo->descriplval; ?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
			                            	<div class="col-sm-6">
			                            		<label for="num_ventas_mes_actualizar">Ventas al mes*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control" name="num_ventas_mes" id="num_ventas_mes_actualizar" onkeypress='return solonumeros(event)' placeholder="P. EJ. 2" required>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-6">
				                                <label for="porctj_comision_actualizar">Porcentaje de Comisión*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control porcentaje" name="porctj_comision" id="porctj_comision_actualizar" placeholder="P. EJ. 00,00" required>
				                                    </div>
				                                </div>
				                            </div>
				                            <input type="hidden" name="id_esquema_comision" id="id_esquema_comision_actualizar">
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
		        <!-- Cierre del cuadro de editar Esquema de Comisión -->
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
    <script src="<?=base_url();?>assets/template/plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>
    <script src="<?=base_url();?>assets/cpanel/EsquemaComision/js/esquemaComision.js"></script>
    <script>
		$("#mv<?php echo $permiso[0]->id_modulo_vista ?>").attr('class', 'active');
		$("#lv<?php echo $permiso[0]->id_lista_vista ?>").attr('class', 'active');
		var consultar = <?php echo $permiso[0]->consultar ?>,
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
