<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

$profe = $_SESSION['profi'];
include("../../menu.php");
?>

<div class="container">
	
	<!-- TITULO DE LA PAGINA -->
	<div class="page-header">
		<h2>Administraci�n <small>Importaci�n de fotos de los profesores</small></h2>
	</div>
	
	
	<!-- SCAFFOLDING -->
	<div class="row">
	
		<!-- COLUMNA IZQUIERDA -->
		<div class="col-sm-6">
			
			<div class="well">
				
				<form enctype="multipart/form-data" method="post" action="fotos_profes.php">
					<fieldset>
						<legend>Importaci�n de fotos de los profesores</legend>

						<div class="form-group">
						  <label for="archivo"><span class="text-info">Fotografias_profesores.zip</span></label>
						  <input type="file" id="archivo" name="archivo" accept="application/zip">
						</div>
						
						<br>
						
					  <button type="submit" class="btn btn-primary" name="enviar">Importar</button>
					  <a class="btn btn-default" href="../index.php">Volver</a>
				  </fieldset>
				</form>
				
			</div><!-- /.well -->
			
		</div><!-- /.col-sm-6 -->
		
		
		<div class="col-sm-6">
			
			<h3>Informaci�n sobre la importaci�n</h3>
			
			<p>Para importar las fotos de todos los profesores, necesitaremos crear un archivo comprimido (.zip) conteniendo todos los archivos de fotos de los profesores.</p>
			
			<p>Cada archivo de foto tiene como nombre el usuario IdEA de S�neca seguido de la extensi�n .jpg o .jpeg. El nombre t�pico de un archivo de foto quedar�a por ejemplo as�: mgargon732.jpg.</p>
			
			<p>Los profesores podr�n actualizar su fotograf�a desde el men� superior, Cambiar fotograf�a.</p>
			
		</div>
		
	
	</div><!-- /.row -->
	
</div><!-- /.container -->
  
<?php include("../../pie.php"); ?>
	
</body>
</html>
