<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
	<link href="<?=base_url();?>assets/template/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
	<link href="<?=base_url();?>assets/template/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
	<body class="theme-blue">
		<input type="hidden" id="ruta" value="<?=base_url();?>" name="ruta">
		<section class="content">
	        <div class="container-fluid">
	        	<div id="alertas"></div>

	        	<!-- Comienzo del cuadro de la tabla -->
					<div class="row clearfix" id="cuadro1">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>
		                                Gestión de Modulos
		                            </h2>
		                            <ul class="header-dropdown m-r--5">
		                                <button class="btn btn-primary" onclick="nuevoBanco()"><i class='fa fa-plus-circle' style="color: white; font-size: 18px;"></i> | Nuevo</button>
		                            </ul>
		                        </div>
		                        <div class="body">
		                            <div class="table-responsive">
		                                <table class="table table-bordered table-striped table-hover" id="tabla">
		                                    <thead>
		                                        <tr>
		                                        	<th style="text-align: center; padding: 0px 10px 0px 5px;"><input type="checkbox" id="checkall" class="chk-col-blue"/><label for="checkall"></label></th>
		                                            <th>Nombre de Modulo</th>
		                                            <th>Descripción</th>
		                                            <th>Posición</th>
		                                            <th>Fecha de Registro</th>
		                                            <th>Registrado Por</th>
		                                            <th style="width: 17%;">Acciones</th>
		                                        </tr>
		                                    </thead>
		                                    <tbody></tbody>
		                                </table>
		                                <div class="col-md-2">
		                                	<button class="btn btn-warning" onclick="statusMultiple('Bancos/status_multiple_banco', 1, 'activar')">Activar seleccionados</button>
		                                </div>
		                                <div class="col-md-2">
		                                	<button class="btn btn-warning" onclick="statusMultiple('Bancos/status_multiple_banco', 2, 'desactivar')">Desactivar seleccionados</button>
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
		                            <h2>Registro de Modulo</h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
			                            <form name="form_modulo_registrar" id="form_modulo_registrar" method="post">
			                            	<div class="col-sm-4">
			                            		<label for="nombre_modulo_vista_registrar">Nombre*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control" name="nombre_modulo_vista" id="nombre_modulo_vista_registrar" placeholder="P. EJ. Modulo" required>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="descripcion_modulo_vista_registrar">Descripción</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="descripcion_modulo_vista" id="descripcion_modulo_vista_registrar" placeholder="P. EJ. Describir funciones">
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="posicion_modulo_vista_registrar">Posición*</label>
		                                        <select id="posicion_modulo_vista_registrar" required class="form-control form-group" name="posicion_modulo_vista">
		                                        	<option value="">Seleccione</option>
		                                        </select>
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
		        <!-- Cierre del cuadro de registrar banco -->

		        <!-- Comienzo del cuadro de consultar banco -->
					<div class="row clearfix ocultar" id="cuadro3">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Consultar Banco</h2>
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
	                                        <button type="button" onclick="regresar('#cuadro3')" class="btn btn-primary">Regresar</button>
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
		                            <h2>Editar de Banco</h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
			                            <form name="form_banco_actualizar" id="form_banco_actualizar" method="post">
			                            	<div class="col-sm-6">
			                            		<label for="cod_banco">Código*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control" id="cod_banco_editar" disabled>
				                                    </div>
				                                </div>
				                            </div>
				                            <input type="hidden" class="form-control" name="id_banco" id="id_banco_editar">
				                            <div class="col-sm-6">
				                                <label for="nombre_banco">Nombre o Razón Social*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control mayusculas" name="nombre_banco" id="nombre_banco_editar" placeholder="P. EJ. BANCO NACIONAL DE MÉXICO, S.A." required>
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
    <script src="<?=base_url();?>assets/template/plugins/bootstrap-select/js/bootstrap-select.js"></script>
    <script src="<?=base_url();?>assets/cpanel/Modulos/js/modulos.js"></script>
</html>
