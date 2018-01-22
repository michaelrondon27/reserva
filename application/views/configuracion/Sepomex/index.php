<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
	<?php if($permiso[0]->consultar==1 && $permiso[0]->actualizar==1): ?>
		<script src="<?=base_url();?>assets/cpanel/js/permiso.js"></script>
	<?php endif ?>
	<body class="theme-blue">
		<input type="hidden" id="ruta" value="<?=base_url();?>" name="ruta">
		<section class="content">
	        <div class="container-fluid">
	        	<div id="alertas"></div>
	        	<div class="row clearfix">
	                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                    <div class="card">
	                        <div class="header">
	                            <h2>
	                                Actualizar Código Postal
	                            </h2>
	                        </div>
	                        <div class="body">
	                        	<div class="table-responsive">
	                        		<div class="col-md-12">
	                        			<p><b>NOTA:</b></p>
	                        			<li class="text-info">Antes de subir el archivo debe recordar guardarlo con la codificación UTF-8.</li>
	                        			<li class="text-info">Debe considerar que este proceso puede demorar mucho tiempo en realizarse con éxito.</li>
	                        		</div>
		                            <form method="post" enctype="multipart/form-data" class="col-md-6 col-md-offset-3" name="form_sepomex" id="form_sepomex">
		                                <div class="col-sm-12">
		                            		<label for="cod_banco">Archivo*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="file" class="form-control" name="archivo" id="archivo" required>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-12">
		                            		<label for="cod_banco">Columnas Separadas Por*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control" name="separacion" id="separacion" maxlength="1" placeholder="P. EJ. |" required>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-12">
		                            		<label for="cod_banco">Iniciar en la Línea*</label>
			                                <div class="form-group">
			                                    <div class="form-line">
			                                        <input type="text" class="form-control" name="inicio" id="inicio" onkeypress='return solonumeros(event)' maxlength="4" placeholder="P. EJ. 3" required>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-4 col-sm-offset-5 actualizar ocultar">
	                                        <input type="submit" value="Actualizar" class="btn btn-success waves-effect">
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
    <script src="<?=base_url();?>assets/cpanel/Sepomex/js/sepomex.js"></script>
    <script>
		$("#mv<?php echo $permiso[0]->id_modulo_vista ?>").attr('class', 'active');
		$("#lv<?php echo $permiso[0]->id_lista_vista ?>").attr('class', 'active');
		var actualizar = <?php echo $permiso[0]->actualizar ?>;
		if(actualizar==0)
			$(".actualizar").removeClass('ocultar');
	</script>
</html>
