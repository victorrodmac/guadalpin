<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

$profe = $_SESSION['profi'];
include("../../menu.php");
?>

	<div class="container">
	
		<div class="page-header">
		  <h2>Administraci�n <small> D�as Festivos</small></h2>
		</div>
		
		<div class="row">
			
			<div class="col-sm-6">
				
				<div class="well">
					<form action="festivos.php" method="post" enctype="multipart/form-data">
						
						<fieldset>
							<legend>D�as festivos</legend>
							
							<div class="form-group">
								<label class="text-info" for="file">200CalEscCent.txt</label>
								<input type="file" id="file" name="archivo" accept="text/plain">
							</div>
							
							<br>
								
							<button type="submit" class="btn btn-primary" name="enviar">Importar</button>
							<a href="../index.php" class="btn btn-default">Volver</a>
							
						</fieldset>
						
					</form>
				</div><!-- /.well -->
			
			</div><!-- /.col-sm-6 -->
			
			<div class="col-sm-6">
				
				<h3>Informaci�n sobre la importaci�n</h3>
				
				<p class="help-block"><span style="color:#9d261d">(*) </span>Si has descargado el archivo <strong>200CalEscCent</strong> de S�neca (desde S�neca --> Centro --> D�as festivos), puedes continuar con el segundo paso.</p>
			
			</div><!-- /.col-sm-6 -->
		
		</div><!-- /.row -->
	
	</div>
	
	<?php include("../../pie.php"); ?>

</body>
</html>
