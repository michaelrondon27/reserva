<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
	<link href="<?=base_url();?>assets/template/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
	<link href="<?=base_url();?>assets/template/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />
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
		                                Gestión de Usuarios
		                            </h2>
		                            <ul class="header-dropdown m-r--5">
		                                <button class="btn btn-primary" onclick="nuevoUsuario()"><i class='fa fa-plus-circle' style="color: white; font-size: 18px;"></i> | Nuevo</button>
		                            </ul>
		                        </div>
		                        <div class="body">
		                            <div class="table-responsive">
		                                <table class="table table-bordered table-striped table-hover" id="tabla" style="font-size: 14px; width: 100%;">
		                                    <thead>
		                                        <tr>
		                                        	<th style="text-align: center; padding: 0px 10px 0px 5px; width:4%;"><input type="checkbox" id="checkall" class="chk-col-blue"/><label for="checkall"></label></th>
		                                        	<th>Nombre(s)</th>
		                                        	<th>Apeelido Paterno</th>
		                                        	<th>Apellido Materno</th>
		                                        	<th>CURP</th>
		                                        	<th>Oficina</th>
		                                            <th>Correo Electrónico</th>
		                                            <th>Rol</th>
		                                            <th>Fecha de Registro</th>
		                                            <th>Fecha Últi. Conexión</th>
		                                            <th style="width-max: 20%;">Acciones</th>
		                                        </tr>
		                                    </thead>
		                                    <tbody></tbody>
		                                </table>
		                                <div class="col-md-2">
		                                	<button class="btn btn-danger" onclick="eliminarMultiple('Bancos/eliminar_multiple_banco')">Eliminar seleccionados</button>
		                                </div>
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
					<div class="row clearfix " id="cuadro2">
		                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                    <div class="card">
		                        <div class="header">
		                            <h2>Registro de Usuario</h2>
		                        </div>
		                        <div class="body">
		                        	<div class="table-responsive">
		                        		<div class="col-md-12" style="border-bottom: 1px solid #ccc; margin-top: 20px;">
		                        			<h4>Datos Personales</h4>
		                        		</div>
			                            <form name="form_usuario_registrar" id="form_usuario_registrar" method="post">
			                            	<div class="col-sm-4">
			                            		<label for="nombre_datos_personales_registrar">Nombre(s)*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control" name="nombre_datos_personales" id="nombre_datos_personales_registrar" onkeypress='return sololetras(event)' maxlength="200" placeholder="P. EJ. LUIS RAÚL" required>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="apellido_p_datos_personales_registrar">Apellido Paterno*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control" name="apellido_p_datos_personales" id="apellido_p_datos_personales_registrar" placeholder="P. EJ. BELLO" required onkeypress='return sololetras(event)'>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="apellido_m_datos_personales_registrar">Apellido Materno*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control" name="apellido_m_datos_personales" id="apellido_m_datos_personales_registrar" placeholder="P. EJ. MENA" required onkeypress='return sololetras(event)'>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="fecha_nac_datos_personales_registrar">Fecha de Nacimiento*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control fecha" name="fecha_nac_datos_personales" id="fecha_nac_datos_personales_registrar" placeholder="dd-mm-yyyy" required onkeypress='return deshabilitarteclas(event)'>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
			                            		<label for="nacionalidad_datos_personales_registrar">Nacionalidad*</label>
		                                    	<select name="nacionalidad_datos_personales" id="nacionalidad_datos_personales_registrar" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($nacionalidades as $nacionalidad): ?>
		                                    			<option value="<?=$nacionalidad->codlval;?>"><?=$nacionalidad->descriplval;?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="curp_datos_personales_registrar">C.U.R.P.*</label>
				                                <div class="form-group form-float">
				                                    <div class="form-line" id="validCurp">
				                                        <input type="text" class="form-control" name="curp_datos_personales" id="curp_datos_personales_registrar" placeholder="P. EJ. BEML920313HCMLNS09" oninput="validarInputCurp(this)">
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="telefono_registrar">Teléfono*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control telefono" name="telefono_principal_contacto" id="telefono_registrar" placeholder="P. EJ.: +00 (000) 000-00-00" onkeypress='return deshabilitarteclas(event)'>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
			                            		<label for="edo_civil_datos_personales_registrar">Estado Civil*</label>
		                                    	<select name="edo_civil_datos_personales" id="edo_civil_datos_personales_registrar" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<option value="1">Soltero(a)</option>
		                                    		<option value="2">Casado(a)</option>
		                                    		<option value="3">Divorciado(a)</option>
		                                    		<option value="4">Viudo(a)</option>
		                                    	</select>
				                            </div>
				                            <div class="col-sm-4">
			                            		<label for="genero_datos_personales_registrar">Género*</label>
		                                    	<select name="genero_datos_personales" id="genero_datos_personales_registrar" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<option value="1">Hombre</option>
		                                    		<option value="2">Mujer</option>
		                                    	</select>
				                            </div>
				                            <div class="col-md-12" style="border-bottom: 1px solid #ccc; margin-top: 20px;">
			                        			<h4>Datos de la Dirección</h4>
			                        		</div>
			                        		<div class="col-sm-4">
				                                <label for="direccion_contacto_registrar">Domicilio</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control" name="direccion_contacto" id="direccion_contacto_registrar" placeholder="P. EJ. INSURGENTE">
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="calle_contacto_registrar">Calle</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control" name="calle_contacto" id="calle_contacto_registrar" placeholder="P. EJ. PRIMAVERA">
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="exterior_contacto_registrar">Número Exterior</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control" name="exterior_contacto" id="exterior_contacto_registrar" placeholder="P. EJ. 33" onkeypress='return solonumeros(event)'>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="interior_contacto_registrar">Número Interior</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control" name="interior_contacto" id="interior_contacto_registrar" placeholder="P. EJ. 2" onkeypress='return solonumeros(event)'>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="codigo_postal_registrar">Código Postal*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="text" class="form-control" id="codigo_postal_registrar" onkeypress='return solonumeros(event)' maxlength="6" onchange="buscarCodigos(this.value)">
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4" style="padding-bottom: 10px;">
				                                <label for="estado_registrar">Estado*</label>
		                                        <select id="estado_registrar" required class="form-control form-group" name="estado">
		                                        	<option value="">Seleccione</option>
		                                        </select>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="ciudad_registrar">Ciudad*</label>
		                                        <select id="ciudad_registrar" required class="form-control form-group" name="ciudad">
		                                        	<option value="">Seleccione</option>
		                                        </select>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="municipio_registrar">Municipio*</label>
		                                        <select id="municipio_registrar" required class="form-control form-group" name="municipio">
		                                        	<option value="">Seleccione</option>
		                                        </select>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="colonia_registrar">Colonia*</label>
		                                        <select id="colonia_registrar" required class="form-control form-group" name="colonia">
		                                        	<option value="">Seleccione</option>
		                                        </select>
				                            </div>
				                            <div class="col-md-12" style="border-bottom: 1px solid #ccc; margin-top: 20px;">
			                        			<h4>Cuenta de Usuario</h4>
			                        		</div>
				                            <div class="col-sm-4">
				                                <label for="correo_usuario_registrar">Correo Electrónico*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="email" class="form-control" name="correo_usuario" id="correo_usuario_registrar" placeholder="P. EJ. ejemplo@dominio.com">
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="correo_confirmar_registrar">Confirmar Correo Electrónico*</label>
				                                <div class="form-group form-float">
				                                    <div class="form-line" id="correoConfirmarRegistrar">
				                                        <input type="email" class="form-control" id="correo_confirmar_registrar" placeholder="P. EJ. ejemplo@dominio.com" oninput="validarCorreo('#correo_usuario_registrar', '#correo_confirmar_registrar','#correoConfirmarRegistrar')">
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4" style="padding-bottom: 30px;">
			                            		<label for="nacionalidad_datos_personales_registrar">Tipo de Rol*</label>
		                                    	<select name="nacionalidad_datos_personales" id="nacionalidad_datos_personales_registrar" required class="form-control">
		                                    		<option value="" selected>Seleccione</option>
		                                    		<?php foreach ($roles as $rol): ?>
		                                    			<option value="<?=$rol->id_rol;?>"><?=$rol->nombre_rol;?></option>
		                                    		<?php endforeach ?>
		                                    	</select>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="clave_usuario_registrar">Contraseña*</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="password" class="form-control" name="clave_usuario" id="clave_usuario_registrar" placeholder="Escribir contraseña">
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
				                                <label for="repetir_registrar">Repetir Contraseña*</label>
				                                <div class="form-group form-float">
				                                    <div class="form-line" id="repetirContraseñaRegistrar">
				                                        <input type="password" class="form-control" id="repetir_registrar" placeholder="P. EJ. ejemplo@dominio.com" oninput="validarClave('#clave_usuario_registrar', '#repetir_registrar','#repetirContraseñaRegistrar')">
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="col-sm-12">
				                                <label for="imagen_registrar">Subir Imagen</label>
				                                <div class="form-group">
				                                    <div class="form-line">
				                                        <input type="file" class="form-control" id="imagen_registrar" name="avatar_usuario">
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
			                                        <input type="text" class="form-control" id="nombre_banco_consultar" disabled>
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
				                                        <input type="text" class="form-control" name="nombre_banco" id="nombre_banco_editar" placeholder="P. EJ. BANCO NACIONAL DE MÉXICO, S.A." required>
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
    <script src="<?=base_url();?>assets/template/plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>
    <script src="<?=base_url();?>assets/cpanel/Usuarios/js/usuarios.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/momentjs/moment.js"></script>
    <script src="<?=base_url();?>assets/template/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
</html>
