<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

$profe = $_SESSION['profi'];

if (isset($_POST['eval']) and !empty($_POST['eval'])) {$eval = $_POST['eval'];}else{$eval="";}

if (strlen($eval)>1) {	
	if (substr($eval,0,1)=='0') {$exporta='../exporta0';}
	if (substr($eval,0,1)=='1') {$exporta='../exporta1';}
	if (substr($eval,0,1)=='2') {$exporta='../exporta2';}
	if (substr($eval,0,1)=='J') {$exporta='../exportaO';}
	if (substr($eval,0,1)=='S') {$exporta='../exportaE';}
	// primera evaluaci�n final exepcional
	if (substr($eval,0,1)=='P') {$exporta='../exportaP';}
	// segunda evaluaci�n final exepcional
	if (substr($eval,0,3)=='Seg') {$exporta='../exportaS';}
	//echo $exporta;

	// Descomprimimos el zip de las calificaciones en el directorio exporta/
	include('../../lib/pclzip.lib.php');
	
	// Borramos archivos antiguos					
	$files = glob($exporta.'/*'); 
	foreach($files as $file)
	{ 
		if(is_file($file) and stristr($file, "index")==FALSE)
		unlink($file); 
	}    
		   
	$archive = new PclZip($_FILES['archivo2']['tmp_name']);  
	if ($archive->extract(PCLZIP_OPT_PATH,$exporta) == 0) 
	{
		include("../../menu.php");
		die('<br><div align="center"><div class="alert alert-danger alert-block fade in">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<h5>ATENCI�N:</h5>
		No se ha podido abrir el archivo comprimido con las Calificaciones. O bien te has olvidado de enviarlo o el archivo est� corrompido.
		</div></div><br />
		<div align="center">
		<input type="button" value="Volver atr�s" name="boton" onClick="history.back(2)" class="btn btn-inverse" />
		</div>'); 
	}
		  
	header("location://".$config['dominio']."/".$config['path']."/xml/notas/notas.php?directorio=$exporta");	  	  
	exit;	
}

include("../../menu.php");

?>


<div class="container">
	
	<!-- TITULO DE LA PAGINA -->
	<div class="page-header">
		<h2>Administraci�n <small>Importaci�n de calificaciones por evaluaci�n</small></h2>
	</div>
	
	<!-- SCAFFOLDING -->
	<div class="row">
	
		<!-- COLUMNA IZQUIERDA -->
		<div class="col-sm-6">
			
			<div class="well">
				
				<form enctype="multipart/form-data" method="post" action="">
					<fieldset>
						<legend>Importaci�n de calificaciones por evaluaci�n</legend>
						
						<div class="form-group">
							<label for="eval"><span class="text-info">Evaluaci�n</span></label>
							<select class="form-control" id="eval" name="eval" required>
								<option value=""></option>
								<option value="0� Evaluaci�n">Evaluaci�n Inicial</option>
								<option value="1� Evaluaci�n">1� Evaluaci�n</option>
								<option value="2� Evaluaci�n">2� Evaluaci�n</option>
								<option value="Junio">Junio (Ordinaria)</option>
								<option value="Septiembre">Septiembre (Extraordinaria)</option>
								<option value="Primera">Primera Evaluaci�n Final Excepcional</option>
								<option value="Segunda">Segunda Evaluaci�n Final Excepcional</option>
							</select>
						</div>
						
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
		
		
		<!-- COLUMNA DERECHA -->
		<div class="col-sm-6">
			
			<h3>Informaci�n sobre la importaci�n</h3>
			
			<p>Para obtener el archivo de exportaci�n de calificaciones debes dirigirte a S�neca, y luego en el Men� a <strong>Utilidades</strong>, <strong>Importaci�n/Exportaci�n de datos</strong>. Selecciona <strong>Exportaci�n de Calificaciones</strong>. Seleccion luego la convocatoria y a�ade todas las unidades de todos los cursos del centro. Procede a descargar el archivo comprimido.<p>
			
		</div><!-- /.col-sm-6 -->
		
	
	</div><!-- /.row -->
	
</div><!-- /.container -->
  
<?php include("../../pie.php"); ?>
	
</body>
</html>
