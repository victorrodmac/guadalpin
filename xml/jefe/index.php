<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

$profe = $_SESSION['profi'];
include("../../menu.php");
?>


<div class="container">
	
	<!-- TITULO DE LA PAGINA -->
	<div class="page-header">
		<h2>Administraci�n <small>Actualizaci�n de alumnos</small></h2>
	</div>
	
	<?php $result = mysqli_query($db_con, "SELECT * FROM alma LIMIT 1"); ?>
	<?php if(mysqli_num_rows($result)): ?>
	<div class="alert alert-warning">
		Ya existe informaci�n en la base de datos. Este proceso actualizar� la informaci�n de los alumnos y asignaturas matriculadas. Es recomendable realizar una <a class="copia_db/index.php" class="alert-link">copia de seguridad</a> antes de proceder a la importaci�n de los datos.
	</div>
	<?php endif; ?>
	
	
	<!-- SCAFFOLDING -->
	<div class="row">
	
		<!-- COLUMNA IZQUIERDA -->
		<div class="col-sm-6">
			
			<div class="well">
				
				<form enctype="multipart/form-data" method="post" action="alma.php">
					<fieldset>
						<legend>Actualizaci�n de alumnos</legend>
						
						<div class="form-group">
						  <label for="archivo1"><span class="text-info">RegAlum.txt</span></label>
						  <input type="file" id="archivo1" name="archivo1" accept="text/plain">
						</div>
						
						<br>
						
						<div class="form-group">
						  <label for="archivo2"><span class="text-info">Exportacion_de_Calificaciones.zip</span></label>
						  <input type="file" id="archivo2" name="archivo2" accept="application/zip">
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
			
			<p>Este apartado se encarga de importar los <strong>alumnos matriculados</strong> en el centro. Tambi�n se importar�n los <strong>sistemas de calificaciones</strong> soportados por S�neca para mostrar los resultados de las evaluaciones de cada curso.</p>
			
			<p>El sistema importar� la <strong>relaci�n de materias matriculadas</strong> de cada alumno.</p>
			
			<p>Para obtener el archivo de exportaci�n de alumnos debe dirigirse al apartado <strong>Alumnado</strong>, <strong>Alumnado</strong>, <strong>Alumnado del centro</strong>. Muestre todos los alumnos del centro y haga clic en el bot�n <strong>Exportar datos</strong>. El formato de exportaci�n debe ser <strong>Texto plano</strong>.</p>
			
			<p>Para obtener el archivo de exportaci�n de calificaciones debe dirigirse al apartado <strong>Utilidades</strong>, <strong>Importaci�n/Exportaci�n de datos</strong>. Seleccione <strong>Exportaci�n de Calificaciones</strong>. Seleccione una convocatoria com�n para todas las unidades y a�ada todas las unidades de todos los cursos del centro. Proceda a descargar el archivo comprimido.<p>
			
		</div>
		
	
	</div><!-- /.row -->
	
</div><!-- /.container -->
  
<?php include("../../pie.php"); ?>
	
</body>
</html>
