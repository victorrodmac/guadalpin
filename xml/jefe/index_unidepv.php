<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

$profe = $_SESSION['profi'];
include("../../menu.php");
?>

<div class="container">
	
	<!-- TITULO DE LA PAGINA -->
	<div class="page-header">
		<h2>Administración <small>Importación de Grupos-Aulas</small></h2>
	</div>
	
	
	<!-- SCAFFOLDING -->
	<div class="row">
	
		<!-- COLUMNA IZQUIERDA -->
		<div class="col-sm-6">
			
			<div class="well">
				
				<form enctype="multipart/form-data" method="post" action="unidepv.php">
					<fieldset>
						<legend>Importación de Grupos-Aulas si no se han introducido en el programa horw</legend>
						
						<div class="form-group">
						  <label for="archivo"><span class="text-info">unidades_dependencias.txt</span></label>
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
			
			<p>Este apartado se encarga de importar el <strong>Grupos-Aulas si no se han introducido en el programa horw</strong> que trabaja en el Centro.</p>
			
			<p>Para obtener el archivo hay que sacar de la base de datos de las tablas de <strong>Unidades</strong> y <strong>Dependencias</strong> todos los registros en formato csv. Abra los dos ficheros exportados y copie las dos primeras columnas de la fila de aula que le corresponda del archivo <strong>Dependencias</strong> al final de la fila del grupo al que le corresponda del archivo <strong>Unidades</strong> separados todos los campos por , </p>
						
		</div>
		
	
	</div><!-- /.row -->
	
</div><!-- /.container -->
  
<?php include("../../pie.php"); ?>
	
</body>
</html>
