<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

if (isset($_FILES['archivo1'])) {$archivo1 = $_FILES['archivo1'];}
if (isset($_FILES['archivo2'])) {$archivo2 = $_FILES['archivo2'];}

include("../../menu.php");
?>

<div class="container">

	<div class="page-header">
		<h2>Administraci�n <small> Actualizaci�n de alumnos</small></h2>
	</div>
	
	<div id="status-loading" class="text-center">
		<span class="lead"><span class="fa fa-circle-o-notch fa-spin"></span> Cargando...</span>
	</div>

	<div id="wrap" class="row" style="display: none;">
	
		<div class="col-sm-8 col-sm-offset-2">
	
			<div class="well">
				<?php
				if($archivo1 and $archivo2){

					// Copia de Seguridad
					mysqli_query($db_con, "DROP TABLE alma_seg") ;
					mysqli_query($db_con, "create table alma_seg select * from alma");
			
					// Creamos Base de datos y enlazamos con ella.
					$base0 = "DROP TABLE `alma`";
					mysqli_query($db_con, $base0);
			
					// Creaci�n de la tabla alma
					$alumnos = "CREATE TABLE  `alma` (
			`Alumno/a` varchar( 255 ) default NULL ,
			 `ESTADOMATRICULA` varchar( 255 ) default NULL ,
			 `CLAVEAL` varchar( 12 ) default NULL ,
			 `DNI` varchar( 10 ) default NULL ,
			 `DOMICILIO` varchar( 255 ) default NULL ,
			 `CODPOSTAL` varchar( 255 ) default NULL ,
			 `LOCALIDAD` varchar( 255 ) default NULL ,
			 `FECHA` varchar( 255 ) default NULL ,
			 `PROVINCIARESIDENCIA` varchar( 255 ) default NULL ,
			 `TELEFONO` varchar( 255 ) default NULL ,
			 `TELEFONOURGENCIA` varchar( 255 ) default NULL ,
			 `CORREO` varchar( 64 ) default NULL ,
			 `CURSO` varchar( 255 ) default NULL ,
			 `NUMEROEXPEDIENTE` varchar( 255 ) default NULL ,
			 `UNIDAD` varchar( 255 ) default NULL ,
			 `apellido1` varchar( 255 ) default NULL ,
			 `apellido2` varchar( 255 ) default NULL ,
			 `NOMBRE` varchar( 30 ) default NULL ,
			 `DNITUTOR` varchar( 255 ) default NULL ,
			 `PRIMERAPELLIDOTUTOR` varchar( 255 ) default NULL ,
			 `SEGUNDOAPELLIDOTUTOR` varchar( 255 ) default NULL ,
			 `NOMBRETUTOR` varchar( 255 ) default NULL ,
			 `SEXOPRIMERTUTOR` varchar( 255 ) default NULL ,
			 `DNITUTOR2` varchar( 255 ) default NULL ,
			 `PRIMERAPELLIDOTUTOR2` varchar( 255 ) default NULL ,
			 `SEGUNDOAPELLIDOTUTOR2` varchar( 255 ) default NULL ,
			 `NOMBRETUTOR2` varchar( 255 ) default NULL ,
			 `SEXOTUTOR2` varchar( 255 ) default NULL ,
			 `LOCALIDADNACIMIENTO` varchar( 255 ) default NULL ,
			 `ANOMATRICULA` varchar( 4 ) default NULL ,
			 `MATRICULAS` varchar( 255 ) default NULL ,
			 `OBSERVACIONES` varchar( 255 ) default NULL,
			 `PROVINCIANACIMIENTO` varchar( 255 ) default NULL ,
			 `PAISNACIMIENTO` varchar( 255 ) default NULL ,
			 `EDAD` varchar( 2 ) default NULL ,
			 `NACIONALIDAD` varchar( 32 ) default NULL,
			 `SEXO` varchar( 1 ) default NULL ,
			 `FECHAMATRICULA` varchar( 255 ) default NULL  
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE latin1_spanish_ci ";
			
					mysqli_query($db_con, $alumnos) or die ('<div align="center"><div class="alert alert-danger alert-block fade in">
			            <button type="button" class="close" data-dismiss="alert">&times;</button>
						<h5>ATENCI�N:</h5>
			No se ha podido crear la tabla <strong>Alma</strong>. Ponte en contacto con quien pueda resolver el problema.
			</div></div><br />
			<div align="center">
			  <input type="button" value="Volver atr�s" name="boton" onClick="history.back(2)" class="btn btn-inverse" />
			</div>');
			
					$SQL6 = "ALTER TABLE `alma` ADD PRIMARY KEY(`CLAVEAL`)";
					$result6 = mysqli_query($db_con, $SQL6);
			
					// Importamos los datos del fichero CSV (todos_alumnos.csv) en la tab�a alma2.
			
					$fp = fopen ($_FILES['archivo1']['tmp_name'] , "r" ) or die('<div align="center"><div class="alert alert-danger alert-block fade in">
			            <button type="button" class="close" data-dismiss="alert">&times;</button>
						<h5>ATENCI�N:</h5>
			No se ha podido abrir el archivo RegAlum.txt. O bien te has olvidado de enviarlo o el archivo est� corrompido.
			</div></div><br />
			<div align="center">
			  <input type="button" value="Volver atr�s" name="boton" onClick="history.back(2)" class="btn btn-inverse" />
			</div>'); 
					while (!feof($fp))
					{
						$num_linea++;
						$linea=fgets($fp);
						$tr=explode("|",$linea);
						if ($num_linea=="7") {
							$num_col = count($tr);
							break;
						}
					}

					$n_col_tabla=0;
					$contar_c = mysqli_query($db_con,"show columns from alma");
					while($contar_col = mysqli_fetch_array($contar_c)){
						$n_col_tabla++;
					}

					if ($n_col_tabla!=$num_col) { 

						// Restauramos Copia de Seguridad porque S�neca ha modificado la estructura de RegAlum.txt
						mysqli_query($db_con, "insert into alma select * from alma_seg");
						echo '<br><div align="center"><div class="alert alert-danger alert-block fade in">
			            <button type="button" class="close" data-dismiss="alert">&times;</button>
						<h5>ATENCI�N:</h5>
						No se han podido importar los datos de los alumnos porque S�neca ha modificado la estructura del archivo RegAlum.txt, bien porque ha a�adido alg�n campo bien porque lo ha eliminado. Ahora mismo el archivo tiene '.$num_col.' campos de datos mientras que la tabla tiene '.$n_col_tabla.' columnas. 
						<br>Se mantienen las tablas tal como estaban mientras actualizas la aplicaci�n o lo comunicas a los desarrolladores para que estos puedan arreglar el asunto.
						</div></div><br />
						<div align="center">
						  <input type="button" value="Volver atr�s" name="boton" onClick="history.back(2)" class="btn btn-inverse" />
						</div>';
						?>
									</div><!-- /.well -->
	
							</div><!-- /.col-sm-8 -->
							
						</div><!-- /.row -->
						
					</div><!-- /.container -->

					<?php include("../../pie.php");	?>
					<?php
					exit();
					}
					
					//echo $num_linea++; // Si todo va bien, el valor de $num_linea en esta l�nea es 7
					
					while (!feof($fp))
					{
						$num_linea++;
						
						$linea="";
						$lineasalto="";
						$dato="";
						$linea=fgets($fp);
						
						// En la l�nea 9 es donde comienza el listado de alumnos
						if ($num_linea > 8) {
							$tr=explode("|",$linea);						
							$lineasalto = "INSERT INTO alma VALUES (";
								foreach ($tr as $valor){
									$dato.= "\"". mysqli_real_escape_string($db_con, trim($valor)) . "\", ";
								}
							$dato=substr($dato,0,strlen($dato)-2);
							$lineasalto.=$dato;
							$lineasalto.=");";
							$consulta=explode(',',$lineasalto);
							//Comprobamos que la matr�cula no haya sido anulada para a�adirla
							if (!preg_match('*Anulada*', $consulta[2])){
								mysqli_query($db_con, $lineasalto);
							}		
						}
					}

					fclose($fp);
			
					// Descomprimimos el zip de las calificaciones en el directorio exporta/
					include('../../lib/pclzip.lib.php');
					// Borramos archivos antiguos					
					$files = glob('../exporta/*'); 
						foreach($files as $file)
						{ 
  						if(is_file($file) and stristr($file, "index")==FALSE)
    						unlink($file); 
						}
					
					$archive = new PclZip($_FILES['archivo2']['tmp_name']);
					if ($archive->extract(PCLZIP_OPT_PATH, '../exporta') == 0)
					{
						die("Error : ".$archive->errorInfo(true));
					}
			
					// Procesamos los datos de RegAlum para crear la tabla alma
					$crear = "ALTER TABLE  alma
			ADD  `COMBASI` VARCHAR( 250 ) NULL FIRST ,
			ADD  `APELLIDOS` VARCHAR( 40 ) NULL AFTER  `UNIDAD`,
			ADD  `CLAVEAL1` VARCHAR( 8 ) NULL AFTER  `CLAVEAL`,
			ADD  `PADRE` VARCHAR( 78 ) NULL AFTER  `CLAVEAL1`,
			ADD  nc DOUBLE NULL AFTER `CLAVEAL`
			";
					mysqli_query($db_con, $crear);
			
					// �ndices
					mysqli_query($db_con, "ALTER TABLE  `alma` ADD INDEX (  `CLAVEAL1` )");
					mysqli_query($db_con, "ALTER TABLE  `alma` ADD INDEX (  `NOMBRE` )");
					mysqli_query($db_con, "ALTER TABLE  `alma` ADD INDEX (  `APELLIDOS` )");
			
					// Apellidos unidos formando un solo campo.
					$SQL2 = "SELECT apellido1, apellido2, CLAVEAL, NOMBRE FROM  alma";
					$result2 = mysqli_query($db_con, $SQL2);
					while  ($row2 = mysqli_fetch_array($result2))
					{
						$apellidos = trim($row2[0]). " " . trim($row2[1]);
						$apellidos1 = trim($apellidos);
						$nombre = $row2[3];
						$nombre1 = trim($nombre);
						$actualiza1= "UPDATE alma SET APELLIDOS = \"". $apellidos1 . "\", NOMBRE = \"". $nombre1 . "\" where CLAVEAL = \"". $row2[2] . "\"";
						mysqli_query($db_con, $actualiza1);
					}
			
					// Apellidos y nombre del padre.
					$SQL3 = "SELECT PRIMERAPELLIDOTUTOR, SEGUNDOAPELLIDOTUTOR, NOMBRETUTOR, CLAVEAL FROM  alma";
					$result3 = mysqli_query($db_con, $SQL3);
					while  ($row3 = mysqli_fetch_array($result3))
					{
						$apellidosP = trim($row3[2]). " " . trim($row3[0]). " " . trim($row3[1]);
						$apellidos1P = trim($apellidosP);
						$actualiza1P= "UPDATE alma SET PADRE = \"". $apellidos1P . "\" where CLAVEAL = \"". $row3[3] . "\"";
						mysqli_query($db_con, $actualiza1P);
					}
			
					// Eliminaci�n de campos innecesarios por repetidos
					$SQL3 = "ALTER TABLE alma
			  DROP `apellido1`,
			  DROP `Alumno/a`,
			  DROP `apellido2`";
					$result3 = mysqli_query($db_con, $SQL3);
					$cambiar_nombre = "ALTER TABLE alma MODIFY COLUMN NOMBRE VARCHAR(30) AFTER APELLIDOS";
					mysqli_query($db_con, $cambiar_nombre);
			
					// Eliminaci�n de alumnos dados de baja
					$SQL4 = "DELETE FROM alma WHERE unidad = ''";
					$result4 = mysqli_query($db_con, $SQL4);
					// Eliminaci�n de alumnos dados de baja
					$SQL5 = "DELETE FROM alma WHERE unidad = 'Unida'";
					$result5 = mysqli_query($db_con, $SQL5);
			
					// Exportamos c�digos de asignaturas de los alumnos y CLAVEAL1 para las consultas de evaluaci�n
					include("exportacodigos.php");
			
					// Eliminamos alumnos sin asignaturas que tienen la matricula pendiente, y que no pertenecen a los Ciclos
					$SQL6 = "delete FROM alma WHERE (COMBASI IS NULL and (curso like '%E.S.O.%' or curso like '%Bach%' or curso like '%P.C.P.I.$' or curso like '%F.P.B.$') and ESTADOMATRICULA not like 'Obtiene T%' and ESTADOMATRICULA not like 'Repit%' and ESTADOMATRICULA not like 'Promocion%' and ESTADOMATRICULA not like 'Pendiente de confirma%')";
					$result6 = mysqli_query($db_con, $SQL6);
			
					// Eliminamos a los alumnoos de Ciclos con algun dato en estadomatricula
					$SQL7 = "DELETE FROM alma WHERE ESTADOMATRICULA not like '' and ESTADOMATRICULA not like 'Obtiene T%' and ESTADOMATRICULA not like 'Repit%' and ESTADOMATRICULA not like 'Promocion%'  and ESTADOMATRICULA not like 'Pendiente de confirm%'";
					mysqli_query($db_con, $SQL7);
			
					// Creamos una asignatura ficticia para que los alumnos sin Asignaturas puedan aparecer en las listas
					$SQL8 = "update alma set combasi = 'Sin_Asignaturas' where combasi IS NULL";
					mysqli_query($db_con, $SQL8);
					echo '<div class="alert alert-success alert-block fade in">
			            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h5>ALUMNOS DEL CENTRO:</h5> los Alumnos se han introducido correctamente en la Base de datos.
			</div>';
			
					//Caso especial de 2� de Bachillerato en Mayo
					/*if ((date('m')=='05' and date('d')>'25') OR (date('m')>'5' and date('m')<'9') ) {
					$ct = mysqli_query($db_con,"select * from alma where curso like '2%' and curso like '%Bach%'");
					if (mysqli_num_rows($ct)>0) {}
					else{
					//$ctrl = mysqli_query($db_con,"insert into alma (select * from alma_seg where curso like '2%' and curso like '%Bach%')");
					if (mysqli_affected_rows($ctrl)>0) {	}
					else{
					echo '<div align="center"><div class="alert alert-warning alert-block fade in">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<h5>ATENCI�N:</h5>
					Ha surgido un problema con la incorporaci�n de alumnos de 2� de Bachillerato. Busca ayuda.
					</div></div><br />';
					}
					}
					}*/
					
					include("actualizar.php");
					
					if (isset($config['mod_sms']) && $config['mod_sms']) {
						include("crear_hermanos.php");
					}
				}
				else{
					echo '<div align="center"><div class="alert alert-danger alert-block fade in">
			            <button type="button" class="close" data-dismiss="alert">&times;</button>
						<h5>ATENCI�N:</h5>
			Parece que te est� olvidando de enviar todos los archivos con los datos de los alumnos. Aseg�rate de enviar ambos archivos descargados desde S�neca.
			</div></div><br />';
				}
			
				// Si se ha creado la tabla matriculas y el mes es mayor que sept. y menor que Diciembre, actualizamos los datos de alma con los datos de la tabla matriculas.
				$matr = mysqli_query($db_con, "select * from matriculas");
				if (mysqli_num_rows($matr)>0 and (date('m')>8 and date('m')<13)) {
					$pro = mysqli_query($db_con, "select claveal,	apellidos, nombre,	provincia,	domicilio,	localidad,	dni, padre,	dnitutor, telefono1, telefono2, nacido, madre, dnitutor2 from matriculas where curso like '%ESO%'");
					while ($prf = mysqli_fetch_array($pro)) {
			
						$pap = explode(", ",$prf[7]);
						$papa = $pap[1]." ".$pap[0];
						$papa=trim($papa);
			
						$mam = explode(", ",$prf[12]);
						$nombretutor2 = $mam[1];
						$apel_mam = explode(" ",$mam[0]);
						$primerapellidotutor2 = $apel_mam[0];
						$segundoapellidotutor2 = "$apel_mam[1] $apel_mam[2] $apel_mam[3]";
						$segundoapellidotutor2=trim($segundoapellidotutor2);
			
						$alm = mysqli_query($db_con, "select claveal,	apellidos,	nombre,	provinciaresidencia, domicilio, localidad, dni, padre, dnitutor, telefono, telefonourgencia, localidadnacimiento, primerapellidotutor2, segundoapellidotutor2, nombretutor2, dnitutor2 from alma where claveal = '$prf[0]' and (apellidos not like '$prf[1]' or nombre not like '$prf[2]' or provinciaresidencia not like '$prf[3]' or domicilio not like '$prf[4]' or localidad not like '$prf[5]' or dni not like '$prf[6]' or padre not like '$papa'  or telefono not like '$prf[9]' or telefonourgencia not like '$prf[10]' or localidadnacimiento not like '$prf[11]' or dnitutor2 not like '$prf[13]')");
			
						if (mysqli_num_rows($alm)>0) {
			
							$num+=1;
							$alma = mysqli_fetch_array($alm);
			
							$com = explode(", ",$prf[7]);
							$nom = trim($com[1]);
							$apel = explode(" ", $com[0]);
							$apel1 = $apel[0];
							$apel2 = $apel[1]." ".$apel[2]." ".$apel[3]." ".$apel[4];
							$apel2 = trim($apel2);
			
							$com2 = explode(", ",$prf[12]);
							$nom2 = trim($com2[1]);
							$apel0 = explode(" ", $com2[0]);
							$apel21 = $apel0[0];
							$apel22 = $apel0[1]." ".$apel0[2]." ".$apel0[3]." ".$apel0[4];
							$apel22 = trim($apel22);
			
							$padre_alma = ", padre = '$nom $apel1 $apel2'";
							$padre_completo = ", nombretutor = '$nom', primerapellidotutor = '$apel1', segundoapellidotutor = '$apel2'";
							$madre_completo = ", nombretutor2 = '$nom2', primerapellidotutor2 = '$apel21', segundoapellidotutor2 = '$apel22', dnitutor2 = '$prf[13]'";
			
								
						 mysqli_query($db_con, "update alma set apellidos = '$prf[1]', nombre = '$prf[2]', provinciaresidencia = '$prf[3]', domicilio = '$prf[4]', localidad = '$prf[5]', dni = '$prf[6]', padre = '$prf[7]', dnitutor = '$prf[8]', telefono = '$prf[9]', telefonourgencia = '$prf[10]', localidadnacimiento = '$prf[11]' $padre_alma $padre_completo $madre_completo where claveal = '$prf[0]'");
						 $num_filas+=mysqli_affected_rows($db_con);
						}
					}
					echo '<br />
				<div align="center"><div class="alert alert-warning alert-block fade in">
			            <button type="button" class="close" data-dismiss="alert">&times;</button>
						<h5>ATENCI�N:</h5>
			Se han modificado los datos personales de '.$num_filas.' alumnos para ajustarlos a la tabla de las matr�culas. Este proceso se termina el mes de Diciembre, momento en el que los adminstrativos han podido registrar los nuevos datos en S�neca. </div></div><br />';
				}
				
			
			// Si se ha creado la tabla matriculas_bach y el mes es mayor que sept. y menor que Diciembre, actualizamos los datos de alma con los datos de la tabla matriculas_bach.
				$matr = mysqli_query($db_con, "select * from matriculas_bach");
				if (mysqli_num_rows($matr)>0 and (date('m')>8 and date('m')<13)) {
					$pro = mysqli_query($db_con, "select claveal,	apellidos, nombre,	provincia,	domicilio,	localidad,	dni, padre,	dnitutor, telefono1, telefono2, nacido, madre, dnitutor2 from matriculas_bach where curso like '%BACH%'");
					while ($prf = mysqli_fetch_array($pro)) {
			
						$pap = explode(", ",$prf[7]);
						$papa = $pap[1]." ".$pap[0];
						$papa=trim($papa);
			
						$mam = explode(", ",$prf[12]);
						$nombretutor2 = $mam[1];
						$apel_mam = explode(" ",$mam[0]);
						$primerapellidotutor2 = $apel_mam[0];
						$segundoapellidotutor2 = "$apel_mam[1] $apel_mam[2] $apel_mam[3]";
						$segundoapellidotutor2=trim($segundoapellidotutor2);
			
						$alm = mysqli_query($db_con, "select claveal,	apellidos,	nombre,	provinciaresidencia, domicilio, localidad, dni, padre, dnitutor, telefono, telefonourgencia, localidadnacimiento, primerapellidotutor2, segundoapellidotutor2, nombretutor2, dnitutor2 from alma where claveal = '$prf[0]' and (apellidos not like '$prf[1]' or nombre not like '$prf[2]' or provinciaresidencia not like '$prf[3]' or domicilio not like '$prf[4]' or localidad not like '$prf[5]' or dni not like '$prf[6]' or padre not like '$papa'  or telefono not like '$prf[9]' or telefonourgencia not like '$prf[10]' or localidadnacimiento not like '$prf[11]' or dnitutor2 not like '$prf[13]')");
			
						if (mysqli_num_rows($alm)>0) {
			
							$num+=1;
							$alma = mysqli_fetch_array($alm);
			
							$com = explode(", ",$prf[7]);
							$nom = trim($com[1]);
							$apel = explode(" ", $com[0]);
							$apel1 = $apel[0];
							$apel2 = $apel[1]." ".$apel[2]." ".$apel[3]." ".$apel[4];
							$apel2 = trim($apel2);
			
							$com2 = explode(", ",$prf[12]);
							$nom2 = trim($com2[1]);
							$apel0 = explode(" ", $com2[0]);
							$apel21 = $apel0[0];
							$apel22 = $apel0[1]." ".$apel0[2]." ".$apel0[3]." ".$apel0[4];
							$apel22 = trim($apel22);
			
							$padre_alma = ", padre = '$nom $apel1 $apel2'";
							$padre_completo = ", nombretutor = '$nom', primerapellidotutor = '$apel1', segundoapellidotutor = '$apel2'";
							$madre_completo = ", nombretutor2 = '$nom2', primerapellidotutor2 = '$apel21', segundoapellidotutor2 = '$apel22', dnitutor2 = '$prf[13]'";
			
								
						 mysqli_query($db_con, "update alma set apellidos = '$prf[1]', nombre = '$prf[2]', provinciaresidencia = '$prf[3]', domicilio = '$prf[4]', localidad = '$prf[5]', dni = '$prf[6]', padre = '$prf[7]', dnitutor = '$prf[8]', telefono = '$prf[9]', telefonourgencia = '$prf[10]', localidadnacimiento = '$prf[11]' $padre_alma $padre_completo $madre_completo where claveal = '$prf[0]'");
						 $num_filas+=mysqli_affected_rows();
						}
					}
					echo '<br />
				<div align="center"><div class="alert alert-warning alert-block fade in">
			            <button type="button" class="close" data-dismiss="alert">&times;</button>
						<h5>ATENCI�N:</h5>
			Se han modificado los datos personales de '.$num_filas.' alumnos para ajustarlos a la tabla de las matr�culas. Este proceso se termina el mes de Diciembre, momento en el que los adminstrativos han podido registrar los nuevos datos en S�neca. </div></div><br />';
				}
			
				// Alumnos TIC
				include("exportaTIC.php");
			
				// Asignaturas y alumnos con pendientes
				include("asignaturas.php");
			
				?>
				
				<div class="text-center">
					 <a href="../index.php" class="btn btn-primary">Volver a Administraci�n</a>
				</div>
			
			</div><!-- /.well -->
	
		</div><!-- /.col-sm-8 -->
		
	</div><!-- /.row -->
	
</div><!-- /.container -->

<?php include("../../pie.php");	?>

<script>
function espera() {
	document.getElementById("wrap").style.display = '';
	document.getElementById("status-loading").style.display = 'none';        
}
window.onload = espera;
</script>

</body>
</html>
