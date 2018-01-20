<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
	<body class="theme-blue">
		<input type="hidden" id="ruta" value="<?=base_url();?>" name="ruta">
		<section class="content">
	        <div class="container-fluid">
	        	<div id="alertas"></div>
				<div class="row clearfix">
	                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                    <div class="card">
	                        <div class="header">
	                            <h2>Datos de Correo Electrónico</h2>
	                        </div>
	                        <div class="body">
	                        	<div class="table-responsive">
		                            <form name="form_correo_actualizar" id="form_correo_actualizar" method="post">
			                            <div class="col-sm-6">
			                                <label for="servidor_smtp">Servidor SMTP*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control" name="servidor_smtp" id="servidor_smtp" required>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-6">
			                                <label for="servidor_smtp">Puerto*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control" name="puerto" id="puerto" required onkeypress='return solonumeros(event)' maxlength="4">
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-6">
			                                <label for="servidor_smtp">Usuario</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control" name="usuario" id="usuario">
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-6">
			                                <label for="servidor_smtp">Clave</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="password" class="form-control" name="clave" id="clave">
			                                    </div>
			                                </div>
			                            </div>
			                            <input type="hidden" name="id_mi_correo" id="id_mi_correo" value="0">
                            			<div class="col-sm-4 col-sm-offset-5">
	                                        <input type="submit" value="Actuzalizar" class="btn btn-success waves-effect">
		                                </div>
		                            </form>
		                        </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
			</div>
		</section>
	</body>
    <script src="<?=base_url();?>assets/cpanel/MiCorreo/js/miCorreo.js"></script>
</html>