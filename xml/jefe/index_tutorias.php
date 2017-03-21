<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

$profe = $_SESSION['profi'];
include("../../menu.php");
?>

<div class="container">
	
	<!-- TITULO DE LA PAGINA -->
	<div class="page-header">
		<h2>Administración <small>Importación de Grupos-Tutor</small></h2>
	</div>
	
	
	<!-- SCAFFOLDING -->
	<div class="row">
	
		<!-- COLUMNA IZQUIERDA -->
		<div class="col-sm-6">
			
			<div class="well">
				
				<form enctype="multipart/form-data" method="post" action="tutorias.php">
					<fieldset>
						<legend>Importación de Grupos-Tutor si no se han introducido en el programa horw</legend>
						
						<div class="form-group">
						  <label for="archivo"><span class="text-info">RegUnidades.txt</span></label>
						  <input type="file" id="archivo" name="archivo" accept="text/plain">
						</div>
						
						<br>
						
					  <button type="submit" class="btn btn-primary" name="enviar">Importar</button>
					  <a class="btn btn-default" href="../index.php">Volver</a>
				  </fieldset>
				</form>
				
			</div><!-- /.well -->
			
		</div><!-- /.col-sm-6 -->
		
		
		<div class="col-sm-6">
			
			<h3>Información sobre la importación</h3>
			
			<p>Este apartado se encarga de importar el <strong>Grupos-Tutor si no se han introducido en el programa horw</strong> que trabaja en el Centro.</p>

			<p>Para obtener el archivo de exportación de Tutorías debes dirigirte a Séneca, y luego en el Menú a <strong>Alumnado</strong>, <strong>Unidades</strong>, <strong>Relación de Unidades</strong>. Selecciona en <strong>Curso</strong> la opción <strong>Cualquiera</strong>. Haga clic en el botón <strong>Exportar datos</strong>. El formato de exportación debe ser <strong>Texto plano</strong>. </p>
									
		</div>
		
	
	</div><!-- /.row -->
	
</div><!-- /.container -->
  
<?php include("../../pie.php"); ?>
	
</body>
</html>
