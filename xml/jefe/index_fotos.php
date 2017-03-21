<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

$profe = $_SESSION['profi'];
include("../../menu.php");
?>

<div class="container">
	
	<!-- TITULO DE LA PAGINA -->
	<div class="page-header">
		<h2>Administraci�n <small>Importaci�n de fotos de los alumnos</small></h2>
	</div>
	
	
	<!-- SCAFFOLDING -->
	<div class="row">
	
		<!-- COLUMNA IZQUIERDA -->
		<div class="col-sm-6">
			
			<div class="well">
				
				<form enctype="multipart/form-data" method="post" action="fotos.php">
					<fieldset>
						<legend>Importaci�n de fotos de los alumnos</legend>

						<div class="form-group">
						  <label for="archivo"><span class="text-info">Fotografias_alumnos.zip</span></label>
						  <input type="file" id="archivo" name="archivo" accept="application/zip">
						</div>
						<div class="checkbox">
					  	<label>
					    	<input type="checkbox" name="tabla" value="1"> Crear tabla de fotos para P�gina P�blica
					    </label>
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
			
			<p>Para importar las fotos de todos los alumnos, necesitaremos crear un archivo comprimido (.zip) conteniendo todos los archivos de fotos de los alumnos.</p>
			
			<p>Cada archivo de foto tiene como nombre el N�mero de Identificaci�n Escolar que S�neca asigna a cada alumno seguido de la extensi�n .jpg o .jpeg. El nombre t�pico de un archivo de foto quedar�a por ejemplo as�: 1526530.jpg.</p>
			
			<p>Los tutores podr�n actualizar las fotograf�as de los alumnos en la P�gina del tutor.</p>
			
		</div>
		
	
	</div><!-- /.row -->
	
</div><!-- /.container -->
  
<?php include("../../pie.php"); ?>
	
</body>
</html>
