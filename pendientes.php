<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed');

// Actualizaciones de la aplicaci�n.
// Comprueba la �ltima release de la aplicaci�n
if (isset($_SESSION['user_admin']) && $_SESSION['user_admin']) {

	function getLatestVersion($repository, $default = INTRANET_VERSION) {

		$context = array(
		  'http' => array(
		  	'header' => "User-Agent: ".$_SERVER['HTTP_USER_AGENT']."\r\n"
		  	)
		  	);

		  	$file = @json_decode(@file_get_contents("https://api.github.com/repos/$repository/tags", false, stream_context_create($context)));
		  	return sprintf("%s", $file ? reset($file)->name : $default);
	}

	$ultima_version = ltrim(getLatestVersion('IESMonterroso/intranet'), 'v');
}
?>

<?php if(isset($_SESSION['user_admin']) && version_compare($ultima_version, INTRANET_VERSION, '>')): ?>
	<div class="alert alert-info">
		<h4>Nueva actualizaci�n de la Intranet</h4>
		<div class="row">
			<div class="col-sm-8">Disponible para su descarga la versi�n <?php echo $ultima_version; ?> de la aplicaci�n.</div>
			<div class="col-sm-4"><a href="https://github.com/IESMonterroso/intranet/releases/tag/v<?php echo $ultima_version; ?>"
		target="_blank" class="btn btn-primary pull-right"><span class="fa fa-download"></span> Descargar</a></div>
		</div>
	</div>
<?php endif; ?>

<?php
// Alumnos expulsados que vuelven
if (isset($_GET['id_tareas'])) {
	$id_tareas = $_GET['id_tareas'];
}
if (isset($_GET['tareas_expulsion'])) {
	if ($_GET['tareas_expulsion'] == 'Si') {
		mysqli_query($db_con, "update tareas_profesor set confirmado = 'Si' where id = '$id_tareas'");
	}
	if ($_GET['tareas_expulsion'] == 'No') {
		mysqli_query($db_con, "update tareas_profesor set confirmado = 'No' where id = '$id_tareas'");
	}
}

$SQLcurso = "select distinct grupo, materia, nivel from profesores where profesor = '$pr'";
$resultcurso = mysqli_query($db_con, $SQLcurso);
while ($exp = mysqli_fetch_array($resultcurso)) 
{
	$unidad = $exp[0];
	$materia = $exp[1];
	$a_asig0 = mysqli_query($db_con, "select distinct codigo from asignaturas where curso = '$exp[2]' and nombre = '$materia' and abrev not like '%\_%'");
	$cod_asig = mysqli_fetch_array($a_asig0);
	$hoy = date('Y') . "-" . date('m') . "-" . date('d');
	$expul= "SELECT DISTINCT alma.apellidos, alma.nombre, alma.unidad, alma.matriculas, tareas_profesor.id, nc
			FROM tareas_alumnos, tareas_profesor, alma
			WHERE alma.claveal = tareas_alumnos.claveal 
			AND tareas_alumnos.id = tareas_profesor.id_alumno
			AND (date(tareas_alumnos.fin) =  date_sub('$hoy', interval 1 day) 
			OR date(tareas_alumnos.fin) =  date_sub('$hoy', interval 2 day) 
			OR date(tareas_alumnos.fin) =  date_sub('$hoy', interval 3 day) 
			OR date(tareas_alumnos.fin) =  date_sub('$hoy', interval 4 day) 
			OR date(tareas_alumnos.fin) =  date_sub('$hoy', interval 5 day)
			OR date(tareas_alumnos.fin) =  date_sub('$hoy', interval 6 day)
			OR date(tareas_alumnos.fin) =  date_sub('$hoy', interval 7 day)
			)
			AND alma.unidad =  '$unidad'
			AND alma.combasi LIKE  '%$cod_asig[0]%' 
			and tareas_profesor.profesor='$pr'
			and tareas_profesor.asignatura='$materia' 
			and confirmado is null
			ORDER BY tareas_alumnos.fecha";
	$result = mysqli_query($db_con, $expul);
	while ($row = mysqli_fetch_array($result))
	{

		$asig_bach = mysqli_query($db_con,"select distinct codigo from materias where nombre like (select distinct nombre from materias where codigo = '$cod_asig[0]' limit 1) and grupo like '$unidad' and codigo not like '$cod_asig[0]' and abrev not like '%\_%'");
		if (mysqli_num_rows($asig_bach)>0) {							
			$as_bach=mysqli_fetch_array($asig_bach);
			$cod_asig_bach = $as_bach[0];	
			$extra_asig = "or asignatura like '$cod_asig_bach'";									
		}
		else{
			$extra_asig = "";
		}	

		$nc_grupo = $row['nc'];
		$sel = mysqli_query($db_con,"select alumnos from grupos where profesor = '$pr' and curso = '$unidad' and (asignatura = '$cod_asig[0]' $extra_asig)");
		$hay_grupo = mysqli_num_rows($sel);
		if ($hay_grupo>0) {
			$sel_al = mysqli_fetch_array($sel);
			$al_sel = explode(",",$sel_al[0]);
			$hay_al="";
			foreach($al_sel as $num_al){
				if ($num_al == $nc_grupo) {
					$hay_al = "1";;
				}
			}
		}

		if ($hay_al=="1" or $hay_grupo<1) {
			if (mysqli_num_rows($result) == '0') {
			}
			else{
				$count_vuelven = 1;
				echo "<div class='alert alert-info'><h4><i class='fa fa-warning'> </i> Alumnos que se reincorporan tras su Expulsi�n<br /></h4>";
				echo "<p>".$row[0].", ".$row[1]." ==> ".$unidad."</p>";
				echo "<p>�Ha realizado el alumno las tareas que le has encomendado en la asignatura de <b><em>$materia</em></b>?&nbsp;&nbsp;&nbsp;&nbsp;<a href='index.php?tareas_expulsion=Si&id_tareas=$row[4]'><button class='btn btn-primary btn-sm'>SI</button></a>&nbsp;&nbsp;<a href='index.php?tareas_expulsion=No&id_tareas=$row[4]'><button class='btn btn-danger btn-sm'>NO</button></a></p>";
				echo "</div>";
			}
		}
	}
}

// Alumnos expulsados que se van
$SQLcurso = "select distinct grupo, materia, nivel from profesores where profesor = '$pr'";
$resultcurso = mysqli_query($db_con, $SQLcurso);
while ($exp = mysqli_fetch_array($resultcurso)) 
{
	$unidad = $exp[0];
	$materia = $exp[1];
	$niv_curso = $exp[2];
	$cd = mysqli_query($db_con, "select distinct codigo from asignaturas where nombre = '$materia' and curso = '$niv_curso' and abrev not like '%\_%'");
	$cdm = mysqli_fetch_array($cd);
	$cod_mat = $cdm[0];
	$hoy = date('Y') . "-" . date('m') . "-" . date('d');
	$ayer0 = time() + (1 * 24 * 60 * 60);
	$ayer = date('Y-m-d', $ayer0);
	$result = mysqli_query($db_con, "select distinct alma.apellidos, alma.nombre, alma.unidad, alma.matriculas, Fechoria.expulsion, inicio, fin, id, Fechoria.claveal, tutoria, nc from Fechoria, alma where alma.claveal = Fechoria.claveal and expulsion > '0' and Fechoria.inicio = '$ayer' and alma.unidad = '$unidad' and combasi like '%$cod_mat%' order by Fechoria.fecha");
	if (mysqli_num_rows($result) > '0') 
	{
		$count_van = 1;
		while ($row = mysqli_fetch_array($result))
		{

			$asig_bach = mysqli_query($db_con,"select distinct codigo from materias where nombre like (select distinct nombre from materias where codigo = '$cod_mat' limit 1) and grupo like '$unidad' and codigo not like '$cod_mat' and abrev not like '%\_%'");
			if (mysqli_num_rows($asig_bach)>0) {							
				$as_bach=mysqli_fetch_array($asig_bach);
				$cod_asig_bach = $as_bach[0];	
				$extra_asig = "or asignatura like '$cod_asig_bach'";									
			}
			else{
				$extra_asig = "";
			}	

			$nc_grupo = $row['nc'];
			$sel = mysqli_query($db_con,"select alumnos from grupos where profesor = '$pr' and curso = '$unidad' and (asignatura = '$cod_mat' $extra_asig)");
			$hay_grupo = mysqli_num_rows($sel);
			if ($hay_grupo>0) {
				$sel_al = mysqli_fetch_array($sel);
				$al_sel = explode(",",$sel_al[0]);
				$hay_al="";
				foreach($al_sel as $num_al){
					if ($num_al == $nc_grupo) {
						$hay_al = "1";;
					}
				}
			}

			if ($hay_al=="1" or $hay_grupo<1) {
				echo "<div class='alert alert-info'><h4><i class='fa fa-warning'> </i> Alumnos que ma�ana abandonan el Centro por Expulsi�n </h4><br>";
				echo "<p>".$row[0].", ".$row[1]." ==> ".$unidad." (Expulsado $row[4] d�as) </p>";
				echo "<h5>$materia</h5></div>";
			}
		}
	}
}


// Informes de Tareas
$count0=0;
$SQLcurso = "select distinct grupo, materia, nivel from profesores where profesor = '$pr' and materia not like '%Tut%'";
$resultcurso = mysqli_query($db_con, $SQLcurso);
while($rowcurso = mysqli_fetch_array($resultcurso))
{
	$curso = $rowcurso[0];
	$unidad_t = $curso;
	$asignatura = $rowcurso[1];

	// Problema con asignaturas comunes de Bachillerato con distinto c�digo
	if(strlen($rowcurso[2])>15){
		$rowcurso[2] = substr($rowcurso[2],0,15);
	}

	$asigna0 = "select codigo from asignaturas where nombre = '$asignatura' and curso like '$rowcurso[2]%' and abrev not like '%\_%'";
	$asigna1 = mysqli_query($db_con, $asigna0);

	if(mysqli_num_rows($asigna1)>1){
		$texto_asig2="";
		while($asigna2 = mysqli_fetch_array($asigna1)){
			$codasi = $asigna2[0];	
			$texto_asig2.=" combasi like '%$asigna2[0]:%' or";
			$c_asig2.=" asignatura = '$asigna2[0]' or";
		}
		$texto_asig2=substr($texto_asig2,0,-3);
		$c_asig2=substr($c_asig2,0,-3);
	}
	else{
		$asigna2 = mysqli_fetch_array($asigna1);
		$codasi = $asigna2[0];	
		$texto_asig2=" combasi like '%$asigna2[0]:%'";
		$c_asig2=" asignatura = '$asigna2[0]'";
	}

	if($c_asig2){
	$hoy = date('Y-m-d');
	$query = "SELECT tareas_alumnos.ID, tareas_alumnos.CLAVEAL, tareas_alumnos.APELLIDOS, tareas_alumnos.NOMBRE, tareas_alumnos.unidad, tareas_alumnos.FIN, tareas_alumnos.FECHA, tareas_alumnos.DURACION, nc FROM tareas_alumnos, alma WHERE tareas_alumnos.claveal = alma.claveal and date(tareas_alumnos.FECHA)>='$hoy' and tareas_alumnos. unidad = '$unidad_t' and ($texto_asig2) ORDER BY tareas_alumnos.FECHA asc";
	$result = mysqli_query($db_con, $query);
	if (mysqli_num_rows($result) > 0)
	{
		while($row = mysqli_fetch_array($result))
		{

			$asig_bach = mysqli_query($db_con,"select distinct codigo from materias where nombre like (select distinct nombre from materias where codigo = '$codasi' limit 1) and grupo like '$unidad_t' and codigo not like '$codasi' and abrev not like '%\_%'");
			if (mysqli_num_rows($asig_bach)>0) {							
				$as_bach=mysqli_fetch_array($asig_bach);
				$cod_asig_bach = $as_bach[0];	
				$extra_asig = "or asignatura like '$cod_asig_bach'";						
				
			}
			else{
				$extra_asig = "";
			}	

			$nc_grupo = $row['nc'];
			$sel = mysqli_query($db_con,"select alumnos from grupos where profesor = '$pr' and curso = '$unidad_t' and (asignatura = '$codasi' $extra_asig)");

			$hay_grupo = mysqli_num_rows($sel);
			if ($hay_grupo>0) {
				$sel_al = mysqli_fetch_array($sel);
				$al_sel = explode(",",$sel_al[0]);
				$hay_al="";
				foreach($al_sel as $num_al){
					if ($num_al == $nc_grupo) {
						$hay_al = "1";;
					}
				}
			}

			if ($hay_al=="1" or $hay_grupo<1) {
				$si0 = mysqli_query($db_con, "select * from tareas_profesor where id_alumno = '$row[0]'  and asignatura = '$asignatura'");
				if (mysqli_num_rows($si0) > 0)
				{ }
				else
				{
					$count0 = $count0 + 1;
				}
			}
		}
	}
	}
}

// Informes de tutoria
$count03=0;
$SQLcurso3 = "select distinct grupo, materia, nivel from profesores where profesor = '$pr' and materia not like '%Tutor%'";
//echo $SQLcurso3."<br>";
$resultcurso3 = mysqli_query($db_con, $SQLcurso3);
while($rowcurso3 = mysqli_fetch_array($resultcurso3))
{
	$curso3 = $rowcurso3[0];
	$unidad3 = $curso3;
	$asignatura3 = trim($rowcurso3[1]);

	// Problema con asignaturas comunes de Bachillerato con distinto c�digo
	/*if(strlen($rowcurso3[2])>15){
		$rowcurso3[2] = substr($rowcurso3[2],0,15);
	}*/

	$asigna03 = "select codigo from asignaturas where nombre = '$asignatura3' and curso like '$rowcurso3[2]' and abrev not like '%\_%'";//echo $asigna03."<br>";
	$texto_asig3="";
	$c_asig3="";
	$asigna13 = mysqli_query($db_con, $asigna03);
	if(mysqli_num_rows($asigna13)>1){
		$texto_asig="";
		while($asigna23 = mysqli_fetch_array($asigna13)){
			$codasi1 = $asigna23[0];	
			$texto_asig3.=" combasi like '%$asigna23[0]:%' or";
			$c_asig3.=" asignatura = '$asigna23[0]' or";
		}
		$texto_asig3=substr($texto_asig3,0,-3);
		$c_asig3=substr($c_asig3,0,-3);
	}
	else{
		$asigna23 = mysqli_fetch_array($asigna13);
		$codasi1 = $asigna23[0];	
		$texto_asig3=" combasi like '%$asigna23[0]:%'";
		$c_asig3=" asignatura = '$asigna23[0]'";
	}

	if($c_asig3){
		$hoy = date('Y-m-d');
		//echo $hoy;

		$query3 = "SELECT id, infotut_alumno.apellidos, infotut_alumno.nombre, F_ENTREV, infotut_alumno.claveal, nc FROM infotut_alumno, alma WHERE infotut_alumno.claveal = alma.claveal and date(F_ENTREV) >= '$hoy' and infotut_alumno. unidad = '$unidad3' and (".$texto_asig3.") ORDER BY F_ENTREV asc";
	 //echo $query3."<br>";
		$result3 = mysqli_query($db_con, $query3);
		if (mysqli_num_rows($result3) > 0)
		{
			while($row3 = mysqli_fetch_array($result3))
			{
				$asig_bach = mysqli_query($db_con,"select distinct codigo from materias where nombre like (select distinct nombre from materias where codigo = '$codasi1' limit 1) and grupo like '$unidad3' and codigo not like '$codasi1' and abrev not like '%\_%'");
				if (mysqli_num_rows($asig_bach)>0) {							
					$as_bach=mysqli_fetch_array($asig_bach);
					$cod_asig_bach = $as_bach[0];	
					$extra_asig = "or asignatura like '$cod_asig_bach'";						
					
				}
				else{
					$extra_asig = "";
				}	

				$nc_grupo = $row3['nc'];
				$sel = mysqli_query($db_con,"select alumnos from grupos where profesor = '$pr' and curso = '$unidad3' and (asignatura = '$codasi1' $extra_asig)");
				
				$hay_grupo = mysqli_num_rows($sel);
				if ($hay_grupo>0) {
					$sel_al = mysqli_fetch_array($sel);
					$al_sel = explode(",",$sel_al[0]);
					$hay_al="";
					foreach($al_sel as $num_al){
						if ($num_al == $nc_grupo) {
							$hay_al = "1";;
						}
					}
				}


				if ($hay_al=="1" or $hay_grupo<1) {

					$asigna_pend = "select distinct nombre, abrev from pendientes, asignaturas where asignaturas.codigo=pendientes.codigo and claveal = '$row3[4]' and asignaturas.nombre in (select distinct materia from profesores where profesor in (select distinct departamentos.nombre from departamentos where departamento = '$dpto') and grupo='$unidad3') and abrev like '%\_%'";
					//echo $asigna_pend;
					$query_pend = mysqli_query($db_con,$asigna_pend);
					if (mysqli_num_rows($query_pend)>0) {
						while ($res_pend = mysqli_fetch_array($query_pend)) {
							$si_pend = mysqli_query($db_con, "select * from infotut_profesor where id_alumno = '$row3[0]' and asignatura = '$res_pend[0] ($res_pend[1])'");

							if (mysqli_num_rows($si_pend) > 0)
							{ }
							else
							{
								$count03 = $count03 + 1;
							}
						}
					}

					$si03 = mysqli_query($db_con, "select * from infotut_profesor where id_alumno = '$row3[0]' and asignatura = '$asignatura3'");
					if (mysqli_num_rows($si03) > 0)
					{ }
					else
					{
						$count03 = $count03 + 1;
					}
				}
			}
		}
	}
}

$count04=0;

// Informes de absentismo.
if (strstr($_SESSION['cargo'],'2')==TRUE) {
	$tut=$_SESSION['profi'];
	$tutor=mysqli_query($db_con, "select unidad from FTUTORES where tutor='$tut'");
	$d_tutor=mysqli_fetch_array($tutor);
	$mas=" and absentismo.unidad='$d_tutor[0]' and tutoria IS NULL ";
}
if (strstr($_SESSION['cargo'],'1')==TRUE) {
	$mas=" and (jefatura IS NULL or jefatura = '')";
}
if (strstr($_SESSION['cargo'],'8')==TRUE) {
	$mas=" and orientacion IS NULL ";
}
if (strstr($_SESSION['cargo'],'1')==TRUE or strstr($_SESSION['cargo'],'2')==TRUE or strstr($_SESSION['cargo'],'8')==TRUE) {
	$SQL0 = "SELECT absentismo.CLAVEAL, apellidos, nombre, absentismo.unidad, alma.matriculas, numero, mes FROM absentismo, alma WHERE alma.claveal = absentismo.claveal $mas order by unidad";
	// echo $SQL0;
	$result0 = mysqli_query($db_con, $SQL0);
	if (mysqli_num_rows($result0) > 0)
	{
		$count04 = $count04 + 1;
	}
}
if(($n_curso > 0 and ($count0 > '0' OR $count03 > '0')) OR (($count04 > '0')))
{	
	if (isset($count0)) {
		if($count0 > '0'){include("modulos/tareas.php");}
	}
	if (isset($count03)) {
		if($count03 > '0'){include("modulos/informes.php");}
	}
	if (isset($count04)) {
		if($count04 > '0'){include("modulos/absentismo.php");}
	}
}

$mensajes_pendientes = 0;


// Comprobar mensajes de Padres
$n_mensajesp = 0;

if(stristr($carg,'2') == TRUE)
{
	$unidad_m = $_SESSION['mod_tutoria']['unidad'];

	if (isset($_GET['asunto']) and $_GET['asunto'] == "Mensaje de confirmaci�n") {
		mysqli_query($db_con, "UPDATE mensajes SET recibidopadre = '1' WHERE id = $verifica_padres");
	}
	$men1 = "SELECT ahora, asunto, texto, nombre, apellidos, id, archivo FROM mensajes JOIN alma ON mensajes.claveal = alma.claveal WHERE mensajes.unidad = '$unidad_m' AND recibidotutor = '0' ORDER BY ahora DESC";
	$men2 = mysqli_query($db_con, $men1);
	if(mysqli_num_rows($men2) > 0)
	{
		$count_mpadres =  1;
		echo '<div id="alert_mensajes_familias" class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><p class="lead"><span class="fa fa-comments fa-fw"></span> Mensajes de Familias y alumnos</p><br><ul id="lista_mensajes_familias">';
			while($men = mysqli_fetch_row($men2))
			{
				$mensajes_pendientes++;
				$n_mensajesp=$n_mensajesp+1;
				$fechacompl = $men[0];
				$asunto = stripslashes($men[1]);
				$texto = stripslashes($men[2]);
				$nombre = $men[3];
				$apellidos = $men[4];
				$id = $men[5];
				$origen = $men[4].", ".$men[3];
?>
				<li id="mensaje_link_familia_<?php echo $id; ?>"><a class="alert-link" data-toggle="modal" href="#mensajep<?php echo $n_mensajesp;?>"><?php echo stripslashes($asunto); ?></a>
					<br />
					<?php echo "<small>".mb_convert_case($origen, MB_CASE_TITLE, "iso-8859-1")." (".fecha_actual2($fechacompl).")</small>";?>
				</li>
<?php
			}
			echo "</ul>";
		echo "</div>";

		$n_mensajesp = 0;
		mysqli_data_seek($men2,0);
		while($men = mysqli_fetch_row($men2)) {
			$n_mensajesp=$n_mensajesp+1;
			$fechacompl = $men[0];
			$asunto = $men[1];
			$texto = html_entity_decode($men[2]);
			$nombre = $men[3];
			$apellidos = $men[4];
			$id = $men[5];
			$archivo = $men[6];
			$origen = $men[4].", ".$men[3];
?>
			<div id="mensajep<?php echo $n_mensajesp;?>"
				data-verifica-familia="<?php echo $id; ?>"
				class="modal modalmensfamilia fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
							<h4 class="modal-title"><?php echo $asunto;?><br>
							<small class="muted">Enviado por <?php echo mb_convert_case($origen, MB_CASE_TITLE, "iso-8859-1"); ?> el <?php echo fecha_actual2($fechacompl); ?></small></h4>
						</div>

						<div class="modal-body"><?php echo stripslashes(html_entity_decode($texto, ENT_QUOTES, 'ISO-8859-1')); ?>
							<?php if (strlen($archivo) > 5): ?> 
								Archivo adjunto: <a href="//<?php echo $config['dominio']; ?>/notas/files/<?php echo $archivo; ?>" target="_blank"><?php echo $archivo; ?></a> 
							<?php endif; ?>
						</div>

						<div class="modal-footer"><a href="#" target="_top" data-dismiss="modal" class="btn btn-default">Cerrar</a> 
<?php 
							$asunto = str_replace('"','',$asunto); 
							$asunto = 'RE: '.$asunto; 
							echo '<a href="./admin/mensajes/redactar.php?padres=1&asunto='.$asunto.'&origen='.$origen.'" target="_top" class="btn btn-primary">Responder</a>';
?>
						</div>
					</div>
				</div>
			</div>
<?php
		}
	}
}


// Comprobar mensajes de profesores
$n_mensajes = 0;

$men1 = "select ahora, asunto, texto, profesor, id_profe, origen from mens_profes, mens_texto where mens_texto.id = mens_profes.id_texto and profesor = '".$_SESSION['ide']."' and recibidoprofe = '0' order by ahora desc";
$men2 = mysqli_query($db_con, $men1);
if(mysqli_num_rows($men2) > 0)
{
	$count_mprofes =  1;
	echo '<div id="alert_mensajes" class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>
	<p class="lead"><span class="fa fa-comments fa-fw"></span> Mensajes de Profesores</p><br>
		<ul id="lista_mensajes">';
		while($men = mysqli_fetch_row($men2))
		{
			$mensajes_pendientes++;
			$n_mensajes+=1;
			$fechacompl = $men[0];
			$asunto = $men[1];
			$texto = html_entity_decode($men[2]);
			$id = $men[4];
			$orig = $men[5];
			$query = mysqli_query($db_con,"select nombre from departamentos where idea = '$orig'");
			$row = mysqli_fetch_array($query);
			$nombre_profe = $row[0];
?>
				<li id="mensaje_link_<?php echo $id; ?>"><a class="alert-link"
					data-toggle="modal" href="#mensaje<?php echo $n_mensajes;?>"><?php echo stripslashes($asunto); ?></a>
					<br>
					<?php echo "<small>".mb_convert_case($nombre_profe, MB_CASE_TITLE, "iso-8859-1")." (".fecha_actual2($fechacompl).")</small>";?>
				</li>
<?php
		}
		echo "</ul>";
	echo "</div>";

	$n_mensajes = 0;
	mysqli_data_seek($men2,0);
	while($men = mysqli_fetch_row($men2)) 
	{
		$n_mensajes+=1;
		$fechacompl = $men[0];
		$asunto = $men[1];
		$texto = html_entity_decode($men[2]);
		$id = $men[4];
		$orig = $men[5];
		$query = mysqli_query($db_con,"select nombre from departamentos where idea = '$orig'");
		$row = mysqli_fetch_array($query);
		$nombre_profe = $row[0];
?>
		<div id="mensaje<?php echo $n_mensajes;?>" data-verifica="<?php echo $id; ?>" class="modal modalmens fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
						<h4 class="modal-title"><?php echo $asunto;?><br>
						<small class="muted">Enviado por <?php echo mb_convert_case($nombre_profe, MB_CASE_TITLE, "iso-8859-1"); ?> el <?php echo fecha_actual2($fechacompl); ?></small></h4>
					</div>

					<div class="modal-body"><?php echo stripslashes(html_entity_decode($texto, ENT_QUOTES, 'ISO-8859-1')); ?></div>

					<div class="modal-footer"><a href="#" target="_top" data-dismiss="modal" class="btn btn-default">Cerrar</a> 
<?php
						$asunto = str_replace('"','',$asunto);
						$asunto = 'RE: '.$asunto;
						echo '<a href="./admin/mensajes/redactar.php?profes=1&asunto='.$asunto.'&origen='.$orig.'&verifica='.$id.'" target="_top" class="btn btn-primary">Responder</a>';
						?>
						<button type="button" class="btn btn-info" id="noleido-<?php echo $id; ?>" data-toggle="button" aria-pressed="false" data-dismiss="modal" autocomplete="off">Marcar como no le�do</button>
					</div>
				</div>
			</div>
		</div>
<?php
	}
}
// Comprobar incidencias
	
if (( $config['mod_centrotic'] == 1 ) && (strlen(stristr($_SESSION['cargo'],'g')) > 0 ))
{
	$consparte = "SELECT parte, unidad, a_aula, fecha, hora, profesor, descripcion FROM partesticgd WHERE fecha >= '".$config['curso_inicio']."' and estado = 'activo' ORDER BY parte ASC";
//	echo $consparte;
	$inc1 = mysqli_query($db_con, $consparte); 
	$n_incidencias = 0;

	if(mysqli_num_rows($inc1) > 0)
	{
		echo '<div id="alert_mensajes" class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>
		<p class="lead"><span class="fa fa-comments fa-fw"></span> Incidencias </p><br>
			<ul id="lista_mensajes">';
			while($inc = mysqli_fetch_row($inc1))
			{
				$mensajes_pendientes++;
				$n_mensajes+=1;
				$parte = $inc[0];
				$unidad = $inc[1];
				$a_aula = $inc[2];
				$fecha = $inc[3];
				$hora = $inc[4];
				$profesor = $inc[5];
				$descripcion = $inc[6];
	?>
					<li id="mensaje_link_<?php echo $parte; ?>"><?php echo stripslashes($descripcion); ?>
						<br>
						<?php echo "<small>".$a_aula." (".$unidad.") ".$fecha." a ".$hora." hora por ".mb_convert_case($profesor, MB_CASE_TITLE, "iso-8859-1")."</small>";?>
						<span class='pull-right'>
						<a href='./TIC/indexgd.php?id=<?php echo $parte; ?>' class='alert-link' data-bs='tooltip' title='Ver informe'><span class='fa fa-search fa-fw fa-lg'></span></a>
						</span>
					</li>
	<?php
			}
			echo "</ul>";
		echo "</div>";

	}
}

if ($count_vuelven > 0 or $count_van > 0 or $count0 > 0 or $count03 > 0 or $count04 > 0 or $count_mprofes > 0 or $count_mpadres > 0 or $count_fech > 0) {
	echo "<br>";
}
else 
{
	if (isset($_GET['tour']) && $_GET['tour'])
	{
?>
	<div class='alert alert-warning'>
		<p class='lead'><i class='fa fa-bell'></i> Informes de tutor�a activos</p>
		<br>

		<p><?php echo date('d-m-Y'); ?> <a class='alert-link'
			data-toggle='modal' href='#'> Pedro P�rez</a> -- 1B-A <span
			class=' pull-right'> <a href='#' class='alert-link'
			style='margin-right: 10px'> <i class='fa fa-search fa-fw fa-lg'
			title='Ver informe'> </i></a> <a href='#' class='alert-link'
			style='margin-right: 10px'> <i class='fa fa-pencil fa-fw fa-lg'
			title='Rellenar informe'> </i> </a> </span></p>
	</div>

	<div class='alert alert-success'>
		<p class="lead"><span class="fa fa-comments fa-fw"></span> Mensajes de Profesores</p>
		<br>
		<ul>
			<li><a href="#" class="alert-link"> Claustro de profesores </a> <br>
			<small>Juan P�rez (<?php echo fecha_actual2(date('Y-m-d')); ?>)</small>
			</li>
		</ul>
		</div>

<?php 
	}
} 
?>
