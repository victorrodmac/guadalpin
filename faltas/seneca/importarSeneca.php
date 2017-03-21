<?php
require('../../bootstrap.php');
require('../../lib/pclzip.lib.php');

acl_acceso($_SESSION['cargo'], array(1));

function fecha_mysql($fecha) {
	$trozo = explode("/", $fecha);
	return $trozo[2]."-".$trozo[1]."-".$trozo[0];
}

function fecha_seneca($fecha_mysql) {
	$trozo = explode("-", $fecha_mysql);
	return $trozo[2]."/".$trozo[1]."/".$trozo[0];
}

include('../../menu.php');

if(!isset($_GET['admin'])) {
	include('../menu.php');
}
?>

<div class="container"><!-- TITULO DE LA PAGINA -->
<div class="page-header">
<h2>Importaci�n de faltas de asistencia</h2>
</div>

<?php
if (isset($_POST['submit'])) {

	// Elimina posibles ficheros en el directorio
if (!file_exists('origen')) {
    mkdir('origen', 0777, true);
}
	$directory = dir("origen/");
	while($filename = $directory->read()) {
		$path = "origen/".$filename;
		unlink($path);
	}
	$directory->close();

	// Descomprimimos el ZIP
	$archive = new PclZip($_FILES['archivo']['tmp_name']);
	$archive->extract(PCLZIP_OPT_PATH, 'origen/');


	$draw_html = 0;
	$directorio = scandir("./origen/");
	sort($directorio);
	foreach ($directorio as $archivo) {

		if (!is_dir($archivo) and stristr($archivo,".xml")==TRUE)
		{
			$cuentains = 0;
			$cuentaupd = 0;
			$cuentanada = 0;

			// Obtenemos el nivel y grupo
			$curso = explode("_",$archivo);
			$nivel = strtoupper(substr($curso[0],0,2));
			$grupo = strtoupper(substr($curso[0],2,1));


			$doc = new DOMDocument('1.0', 'iso-8859-1');
			$doc->load( './origen/'.$archivo );

			// Fechas de inicio y fin
			$tag_fechadesde	= $doc->getElementsByTagName("FECHA_DESDE");
			$tag_fechahasta	= $doc->getElementsByTagName("FECHA_HASTA");
			$fecha_desde = $tag_fechadesde->item(0)->nodeValue;
			$fecha_hasta = $tag_fechahasta->item(0)->nodeValue;

			// Obtenemos los datos del curso
			$tag_xofertamatrig	= $doc->getElementsByTagName("X_OFERTAMATRIG");
			$tag_dofertamatrig	= $doc->getElementsByTagName("D_OFERTAMATRIG");
			$tag_xunidad 		= $doc->getElementsByTagName("X_UNIDAD");
			$tag_tnombre		= $doc->getElementsByTagName("T_NOMBRE");
			$X_OFERTAMATRIG 	= $tag_xofertamatrig->item(0)->nodeValue;
			$D_OFERTAMATRIG		= utf8_decode($tag_dofertamatrig->item(0)->nodeValue);
			$X_UNIDAD			= $tag_xunidad->item(0)->nodeValue;
			$T_NOMBRE			= utf8_decode($tag_tnombre->item(0)->nodeValue);

			if ($draw_html == 0) {
				echo '
		        <div class="alert alert-info">
		        	Importando faltas de asistencia desde el d�a <strong>'.$fecha_desde.'</strong> hasta el d�a <strong>'.$fecha_hasta.'</strong>.
		        	Esta acci�n sobreescribir� los datos existentes en ese periodo de tiempo.
		        </div>';
			}

			// Copia la tabla y elimina los datos para evitar duplicaciones
			//mysqli_query($db_con, "drop TABLE FALTAS_seg");
			//mysqli_query($db_con, "CREATE TABLE FALTAS_seg SELECT * FROM FALTAS");

			$fecha_desde=cambia_fecha($fecha_desde);
			$fecha_hasta=cambia_fecha($fecha_hasta);

			//desactivo el borrado de faltas anteriores
			//mysqli_query($db_con, "DELETE FROM FALTAS WHERE FECHA BETWEEN '$fecha_desde' AND '$fecha_hasta' and unidad = '$T_NOMBRE'");

			if ($draw_html == 0) {
				echo '<div class="well">';
				echo '<ul>';
			}

			echo '<li><strong>'.$T_NOMBRE.' ('.$D_OFERTAMATRIG.'):</strong> ';

			$tag_alumno = $doc->getElementsByTagName("ALUMNO");
			foreach( $tag_alumno as $alumno ) {
				$tag_xmatricula = $alumno->getElementsByTagName("X_MATRICULA");
				$tag_cnumescolar = $alumno->getElementsByTagName("C_NUMESCOLAR");
				$X_MATRICULA = $tag_xmatricula->item(0)->nodeValue;
				$C_NUMESCOLAR = $tag_cnumescolar->item(0)->nodeValue;

				// Obtenemos el n�mero de lista
				$result = mysqli_query($db_con, "SELECT nc FROM alma WHERE CLAVEAL = '$C_NUMESCOLAR' LIMIT 1");
				$row = mysqli_fetch_assoc($result);
				$nc_alumno = $row['nc'];
				mysqli_free_result($result);

				// Obtenemos las asignaturas en las que est� matriculado el alumno
				$result = mysqli_query($db_con, "SELECT combasi FROM alma WHERE claveal = '$C_NUMESCOLAR' LIMIT 1");
				$row = mysqli_fetch_assoc($result);
				$combasi = rtrim($row['combasi'],':');
				mysqli_free_result($result);
				$asignaturas = explode(':', $combasi);

				$tag_faltas = $alumno->getElementsByTagName("FALTA_ASISTENCIA");
				foreach( $tag_faltas as $falta ) {
					$tag_ffalasi = $falta->getElementsByTagName("F_FALASI");
					$tag_xtramo = $falta->getElementsByTagName("X_TRAMO");
					$tag_ctipfal = $falta->getElementsByTagName("C_TIPFAL");
					$tag_ldiacom = $falta->getElementsByTagName("L_DIACOM");
					$F_FALASI = $tag_ffalasi->item(0)->nodeValue;
					$X_TRAMO = $tag_xtramo->item(0)->nodeValue;
					$C_TIPFAL = $tag_ctipfal->item(0)->nodeValue;
					$L_DIACOM = $tag_ldiacom->item(0)->nodeValue;

					// Obtenemos el tramo horario
					$result = mysqli_query($db_con, "SELECT hora FROM tramos WHERE tramo = '$X_TRAMO' LIMIT 1");
					$row = mysqli_fetch_assoc($result);
					
						$hora_tramo = $row['hora'];
					
					mysqli_free_result($result);

					if ($C_TIPFAL == 'I') {
						$tipo_falta = 'F';
					}
					else {
						$tipo_falta = $C_TIPFAL;
					}

					if ($L_DIACOM == 'S') 
					{
						$inicio = 1;
						$fin = 7;
					}
					else
					{
						$inicio = $hora_tramo;
						$fin = $hora_tramo + 1;
					}
					//echo "inicio".$inicio."fin".$fin;
					for ($i=$inicio; $i < $fin; $i++) 
					{	
						$consexiste = "SELECT FALTA from FALTAS where CLAVEAL = '$C_NUMESCOLAR' AND unidad = '$T_NOMBRE' and NC = '$nc_alumno'and FECHA = '".fecha_mysql($F_FALASI)."' and HORA = '$i'";
//						echo "e_".$consexiste;
						$resultexiste = mysqli_query($db_con, $consexiste);
						// nada se pone a 1 si no hay que insertar o modificar datos
						$nada = 0;
						// modifica se ponea a 1 si se tiene que hacer un update en vez de un insert
						$modifica = 0;
						while($row1 = mysqli_fetch_array($resultexiste))
						{
							// si coincide la falta es que ya esta registrado bien no hay que hacer nada
							if ($row1[0] == $tipo_falta)
							{
								$nada = 1;
							}
							else
							{
								// si la falta esta justificada en la intranet y hay falta en seneca se prioriza la justificaci�n con lo que no se hace nada
								if ($row1[0] == 'J' )
								{
									$nada = 1;
								}
								else
								{
								// si la falta justificada es la de seneca al priorizarse la justificaci�n hay que cambiar a justificado ya que esto representa que el tutor o el profesor lo ha justificado despues de producirse la falta
								// hay otro caso posible que es cuando en seneca hay un retraso y en la intranet se pone una falta o justificaci�n en ese caso se supone que el retraso se pone en el momento en que llega y antes hab�a una falta con lo que se debe de cambiar a retraso
									if ($tipo_falta == 'R' or $tipo_falta == 'J')
									{
										$modifica = 1;
									}
									// aqui llega si hay apuntado un retraso en intranet y se quiere cambiar a falta, como se supone que la falta se pone primero y despu�s se cambia a retraso en este caso debe dejar el retraso.
									else
									{
										$nada = 1;
									}
								}
							}
						}
						//echo "modifica".$modifica."nada".$nada;
						if ($nada == 0 )
						{
							// Obtenemos el c�digo del profesor y de la asignatura que se imparte en el d�a y hora
							$result = mysqli_query($db_con, "SELECT c_prof, c_asig FROM horw WHERE dia = '".strftime("%u", strtotime(fecha_mysql($F_FALASI)))."' AND hora = '$i' AND a_grupo like '$T_NOMBRE%'");
							$codasig="";
							$nprofesor="";
							while ($row = mysqli_fetch_assoc($result)) {
								$cod_orig=$row['c_asig'];
								$prof_orig=$row['c_prof'];
								
								if(in_array($row['c_asig'], $asignaturas)) {
									$nprofesor = $row['c_prof'];
									$codasig = $row['c_asig'];
								}
								elseif ($row['c_asig']=="2") {
									$nprofesor = $row['c_prof'];
									$codasig = "2";
								}
								elseif ($row['c_asig']=="21") {
									$nprofesor = $row['c_prof'];
									$codasig = "21";
								}
							}
							
							mysqli_free_result($result);
							
							if ($codasig=="") {
								$nprofesor = $prof_orig;
								$codasig = $cod_orig;
							}
							if ($modifica == 0)
							{
								$consins = "INSERT INTO FALTAS (CLAVEAL, unidad, NC, FECHA, DIA, HORA, PROFESOR, CODASI, FALTA) VALUES ('$C_NUMESCOLAR', '$T_NOMBRE', '$nc_alumno', '".fecha_mysql($F_FALASI)."', '".strftime("%u", strtotime(fecha_mysql($F_FALASI)))."', '$i', '$nprofesor', '$codasig', '$tipo_falta')";
								$result = mysqli_query($db_con, $consins);
//								echo "i_".$consins;
								$cuentains = $cuentains + 1;
							}
							else
							{
								$consupdate = "update FALTAS set FALTA = '$tipo_falta' where CLAVEAL = '$C_NUMESCOLAR' AND unidad = '$T_NOMBRE' and NC = '$nc_alumno'and FECHA = '".fecha_mysql($F_FALASI)."' and HORA = '$i'";
								$result = mysqli_query($db_con, $consupdate);
								$cuentaupd = $cuentaupd + 1;
//								echo "u_".$consupdate;
							}
							
						}
						else
						{
							$cuentanada = $cuentanada + 1;
						}
					}
					

					unset($nprofesor);
					unset($codasig);

				}


			}
			echo '<span class="text-success">Resultado de la importaci�n:</span> se han importado '.$cuentains.' registros, se han modificado '.$cuentaupd.' registros y se han encontrado '.$cuentanada.' registros repetidos</li>';
			$draw_html = 1;

		}
	}
// Eliminamos faltas de alumnos que se han dado de baja en el Centro.
mysqli_query($db_con,"delete from FALTAS where nc='0'");
	echo '</ul>';
	echo '</div>';
	echo '<br>';
	echo '<a href="importarSeneca.php" class="btn btn-primary">Realizar otra importaci�n</a> ';
	echo '<a href="../index.php" class="btn btn-default">Volver</a>';
}
else {
	?>
	<?php $result = mysqli_query($db_con, "SELECT * FROM FALTAS"); ?>
	<?php if(mysqli_num_rows($result)): ?>
	<div class="alert alert-warning">Ya existe informaci�n en la base de datos. Este proceso sobreescribir� la informaci�n de la Intranet. Es recomendable realizar una <a href="copia_db/index.php" class="alert-link">copia de seguridad</a> antes de proceder a la importaci�n de los datos.</div>
	<?php endif; ?>

<div class="row">

<div class="col-sm-6">

<div class="well">
<form action="" method="POST" enctype="multipart/form-data">

<fieldset><legend>Importaci�n de faltas de asistencia</legend>

<div class="form-group"><label for="archivo"><span class="text-info">Exportacion_Faltas_Alumnado.zip</span></label>
<input type="file" id="archivo" name="archivo" accept="application/zip">
</div>

</fieldset>

<br>

<button type="submit" class="btn btn-primary" name="submit">Importar</button>
	<?php if($_GET['admin'] == 1): ?> <a class="btn btn-default"
	href="../../xml/index.php">Volver</a> <?php else: ?> <a
	class="btn btn-default" href="../index.php">Volver</a> <?php endif; ?>

</form>
</div>

</div>

<div class="col-sm-6">

<h3>Informaci�n de importaci�n de datos</h3>

<p>Este apartado se encarga de importar los faltas de asistencia de los
alumnos matriculados en el centro.</p>

<p>Para obtener el archivo de exportaci�n de Faltas de Asistencia debe
dirigirse al apartado <strong>Utilidades</strong>, <strong>Importaci�n/Exportaci�n
de datos</strong>. Seleccione <strong>Exportaci�n de Faltas del Alumnado</strong>.
Seleccione una fecha comprendida en un rango de un mes y a�ada todas las
unidades de todos los cursos del centro. Proceda a descargar el archivo
comprimido.</p>
</div>

</div>
	<?php
}
?></div>
<!-- /.container -->

<?php include('../../pie.php'); ?>
</body>
</html>
