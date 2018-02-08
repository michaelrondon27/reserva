<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
	<?php if(($permiso[0]->consultar==1 && $permiso[0]->actualizar==1) OR $permiso[0]->status==2): ?>
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
				<div class="row clearfix">
	                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                    <div class="card">
	                        <div class="header">
	                            <h2>Datos de <?php echo $breadcrumbs->nombre_lista_vista; ?></h2>
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
                            			<div class="col-sm-4 col-sm-offset-5 actualizar ocultar">
                            				<span class="btn btn-danger waves-effect" onclick="listar()">Cancelar</span>
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
    <script>
		$("#mv<?php echo $permiso[0]->id_modulo_vista ?>").attr('class', 'active');
		$("#lv<?php echo $permiso[0]->id_lista_vista ?>").attr('class', 'active');
		var actualizar = <?php echo $permiso[0]->actualizar ?>;
		if(actualizar==0)
			$(".actualizar").removeClass('ocultar');
	</script>
</html>