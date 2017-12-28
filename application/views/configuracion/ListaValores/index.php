<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
	<link href="<?=base_url();?>assets/template/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
	<body class="theme-blue">
		<input type="hidden" id="ruta" value="<?=base_url();?>" name="ruta">
		<section class="content">
	        <div class="container-fluid">
	        	<div id="alertas"></div>

	        	<!-- Comienzo del cuadro de la tabla -->
					<div class="row clearfix" id="cuadro1">
						<div class="col-sm-1" style="padding-top: 5px;">
                			<span class="form-group" >Filtrar: </span>
                		</div>
                        <div class="col-sm-4" style="margin-bottom: 10px;">
                            <select class="form-control" onchange="filtrar(this.value)">
                            	<option value="" selected>Seleccione</option>
                        		<?php foreach ($tipolval as $data): ?>
                        			<option value="<?=$data->descriplval;?>"><?=$data->descriplval;?></option>
                        		<?php endforeach ?>
                        	</select>
                        </div>
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>
		                                Gestión de Lista de Valores
		                            </h2>
		                            <ul class="header-dropdown m-r--5">
		                                <button class="btn btn-primary" onclick="nuevaListaValor()"><i class='fa fa-plus-circle' style="color: white; font-size: 18px;"></i> | Nuevo</button>
		                            </ul>
		                        </div>
		                        <div class="body">
		                            <div class="table-responsive">
		                                <table class="table table-bordered table-striped table-hover" id="tabla">
		                                    <thead>
		                                        <tr>
		                                        	<th style="text-align: center; padding: 0px 10px 0px 5px; width: 5%;"><input type="checkbox" id="checkall" class="chk-col-blue"/><label for="checkall"></label></th>
		                                        	<th>Tipo de Valor</th>
		                                            <th>Descripción</th>
		                                            <th>Fecha de Registro</th>
		                                            <th>Registrado Por</th>
		                                            <th style="min-width: 17%;">Acciones</th>
		                                        </tr>
		                                    </thead>
		                                    <tbody></tbody>
		                                </table>
		                                <div class="col-md-2">
		                                	<button class="btn btn-danger" onclick="eliminarMultiple('ListaValores/eliminar_multiple_lval')">Eliminar seleccionados</button>
		                                </div>
		                                <div class="col-md-2">
		                                	<button class="btn btn-warning" onclick="statusMultiple('ListaValores/status_multiple_lval', 1, 'activar')">Activar seleccionados</button>
		                                </div>
		                                <div class="col-md-2">
		                                	<button class="btn btn-warning" onclick="statusMultiple('ListaValores/status_multiple_lval', 2, 'desactivar')">Desactivar seleccionados</button>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        <!-- Cierre del cuadro de la tabla -->

		        <!-- Comienzo del cuadro de registrar lista de valores -->
					<div class="row clearfix ocultar" id="cuadro2">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Registro de Lista de Valor</h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
			                            <form name="form_valor_registrar" id="form_valor_registrar" method="post">
			                            	<div class="col-sm-6">
			                            		<label for="descriplval">Tipo de Valor*</label>
		                                    	<select name="tipolval" id="tipolval_registrar" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($tipolval as $data): ?>
		                                    			<option value="<?=$data->tipolval;?>"><?=$data->descriplval;?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
				                            <div class="col-sm-6">
				                                <label for="tipolval">Descripción*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control" name="descriplval" id="descriplval_registrar" placeholder="P. EJ. DESCRIPCIÓN" required>
				                                    </div>
				                                </div>
				                            </div>
                                			<br>
                                			<div class="col-sm-4 col-sm-offset-5">
		                                        <button type="button" onclick="regresar('#cuadro2')" class="btn btn-primary">Regresar</button>
		                                        <input type="submit" value="Guardar" class="btn btn-success">
			                                </div>
			                            </form>
			                        </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        <!-- Cierre del cuadro de registrar lista de valores -->

		        <!-- Comienzo del cuadro de consultar lista de valores -->
					<div class="row clearfix ocultar" id="cuadro3">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Consultar Lista de Valor</h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
		                        		<div class="col-sm-6">
			                                <label>Tipo de Valor</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control" id="tipolval_consultar" disabled>
			                                    </div>
			                                </div>
			                            </div>
		                            	<div class="col-sm-6">
		                            		<label>Descripción</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control" id="descriplval_consultar" disabled>
			                                    </div>
			                                </div>
			                            </div>
                            			<br>
                            			<div class="col-sm-2 col-sm-offset-5">
	                                        <button type="button" onclick="regresar('#cuadro3')" class="btn btn-primary">Regresar</button>
		                                </div>
			                        </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        <!-- Cierre del cuadro de consultar lista de valores -->

		        <!-- Comienzo del cuadro de editar lista de valores -->
					<div class="row clearfix ocultar" id="cuadro4">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Editar de Lista de Valor</h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
			                            <form name="form_lval_actualizar" id="form_lval_actualizar" method="post">
			                            	<div class="col-sm-6">
			                            		<label for="descriplval">Tipo de Valor*</label>
		                                    	<select name="tipolval" id="tipolval_editar" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($tipolval as $data): ?>
		                                    			<option value="<?=$data->tipolval;?>"><?=$data->descriplval;?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
				                            <input type="hidden" class="form-control" name="codlval" id="codlval_editar">
				                            <div class="col-sm-6">
				                                <label for="tipolval">Descripción*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control" name="descriplval" id="descriplval_editar" placeholder="P. EJ. DESCRIPCIÓN" required>
				                                    </div>
				                                </div>
				                            </div>
                                			<br>
                                			<div class="col-sm-4 col-sm-offset-5">
		                                        <button type="button" onclick="regresar('#cuadro4')" class="btn btn-primary">Regresar</button>
		                                        <input type="submit" value="Guardar" class="btn btn-success">
			                                </div>
			                            </form>
			                        </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        <!-- Cierre del cuadro de editar lista de valores -->
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
    <script src="<?=base_url();?>assets/cpanel/ListaValores/js/listaValores.js"></script>
</html>