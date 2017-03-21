<?php
require('../../../bootstrap.php');
function abrevactividad($db_con, $actividad) {
	$result = mysqli_query($db_con, "SELECT idactividad, nomactividad FROM actividades_seneca WHERE nomactividad = '$actividad'");
	while ($row = mysqli_fetch_array($result)) {
		$exp_nomactividad = explode('(', $row['nomactividad']);
		
		$exp_nomactividad = str_replace(' a ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' al ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' el ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' la ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' las ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' los ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' de ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' En ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' en ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' su ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' del ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' Del ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' con ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' que ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' y ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace('.', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(',', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace('-', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' para ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' cuando ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' caso ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' como ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' no ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' tengan ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' otros ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' determine ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' correspondientes ', ' ', $exp_nomactividad);
		
		$nomactividad = mb_convert_case($exp_nomactividad[0], MB_CASE_TITLE, 'ISO-8859-1');
		
		$abrev = "";
		for ($i = 0; $i < strlen($nomactividad); $i++) {
			if ($nomactividad[$i] == mb_convert_case($nomactividad[$i], MB_CASE_UPPER, 'ISO-8859-1') && $nomactividad[$i] != " " && $nomactividad[$i] != ".") {
				$abrev .= mb_convert_case($nomactividad[$i], MB_CASE_UPPER, 'ISO-8859-1');
			}
		}
		
		if (strlen($abrev) < 3) {
			$exp_nomactividad = explode(' ', $nomactividad);
			$abrev .= $exp_nomactividad[1][1].$exp_nomactividad[1][2];
			$abrev = mb_convert_case($abrev, MB_CASE_UPPER, 'ISO-8859-1');
		}
		
		if (strlen($abrev) < 2) {
			$exp_nomactividad = explode(' ', $nomactividad);
			$abrev .= $exp_nomactividad[0][1].$exp_nomactividad[0][2];
			$abrev = mb_convert_case($abrev, MB_CASE_UPPER, 'ISO-8859-1');
		}
	}
	
	return $abrev;
}


// SELECCION DE PROFESOR
if(!(stristr($_SESSION['cargo'],'1') == TRUE))
{
	$profesor = $_SESSION['profi'];
}
else {
	if (isset($_SESSION['mod_horarios']['profesor'])) {
		$profesor = $_SESSION['mod_horarios']['profesor'];
	}
	
	if ($_POST['profesor']) {
		$profesor = $_POST['profesor'];
		$_SESSION['mod_horarios']['profesor'] = $profesor;
	}	
}


// MODIFICADORES DE FORMULARIO
if (isset($_GET['dia'])) $dia = urldecode($_GET['dia']);
else $dia = $_POST['dia'];

if (isset($_GET['hora'])) $hora = urldecode($_GET['hora']);
else $hora = $_POST['hora'];

if (isset($_GET['asignatura'])) $asignatura = urldecode($_GET['asignatura']);
else $asignatura = $_POST['asignatura'];

if (isset($_POST['unidad'])) {
	$unidad = substr($_POST['unidad'], 0, -1);
	$unidad_curso = $_POST['unidad'];
	$exp_unidad = explode('|', $unidad_curso);
	$unidad = $exp_unidad[0];
	$curso = $exp_unidad[1];
}
elseif (isset($_GET['unidad']) && $_GET['asignatura'] !== '25' and $_POST['unidad']=="") {
	$unidad = urldecode($_GET['unidad']);

	// A partir del c�digo de la asignatura y la unidad, descubrimos el curso...
	$result = mysqli_query($db_con, "SELECT CURSO FROM materias WHERE GRUPO='$unidad' AND CODIGO = '$asignatura' LIMIT 1");
	$esDesdoble = 0;
	if (! mysqli_num_rows($result)) {
		// En el caso de ser un desdoble
		$unidad = substr($unidad, 0, -1);
		$result = mysqli_query($db_con, "SELECT CURSO FROM materias WHERE GRUPO='$unidad' AND CODIGO = '$asignatura' LIMIT 1");

		$datos_curso = mysqli_fetch_assoc($result);
		$curso = $datos_curso['CURSO'];
		$esDesdoble = 1;
	}
	else {
		$datos_curso = mysqli_fetch_assoc($result);
		$curso = $datos_curso['CURSO'];
	}
	
	$unidad_curso = $unidad.'|'.$curso;
}
elseif ((isset($_GET['unidad']) && $_GET['unidad'] == '')) {
	$unidad = substr($_POST['unidad'], 0, -1);
	$unidad_curso = $_POST['unidad'];
	$exp_unidad = explode('|', $unidad_curso);
	$unidad = $exp_unidad[0];
	$curso = $exp_unidad[1];
}

if (isset($_GET['dependencia'])) {$dependencia = urldecode($_GET['dependencia']);}
else {$dependencia = $_POST['dependencia'];}

// ENVIO DE FORMULARIO
if ((isset($_POST['enviar'])) || (isset($_POST['copiar']))){
	$dia = $_POST['dia'];
	$hora = $_POST['hora'];
	
	// OBTENEMOS DATOS DEL PROFESOR
	$result = mysqli_query($db_con, "SELECT DISTINCT no_prof, c_prof FROM horw WHERE prof='".$profesor."'");
	if (mysqli_num_rows($result)>0) {
		$datos_profesor = mysqli_fetch_array($result);
		$numprofesor = $datos_profesor['no_prof'];
		$codprofesor = $datos_profesor['c_prof'];	
		}
		else{
		$result2 = mysqli_query($db_con, "SELECT idprofesor FROM profesores_seneca WHERE nomprofesor='".$profesor."'");
		$res_prof = mysqli_fetch_array($result2);
		$codprofesor = $res_prof[0];
		$result3 = mysqli_query($db_con, "SELECT max(no_prof) FROM horw");

		if (mysqli_num_rows($result3)>0) {
			$no_profeso = mysqli_fetch_array($result3);
			$numprofesor=$no_profeso[0]+1;
		}
		else{
			$numprofesor=1;
			}
		}
	
	
	// OBTENEMOS DATOS DE LA ASIGNATURA
	$result = mysqli_query($db_con, "SELECT nombre, abrev, curso FROM asignaturas WHERE codigo='".$_POST['asignatura']."' AND abrev NOT LIKE '%\_%'");
	$datos_asignatura = mysqli_fetch_array($result);
	$codasignatura = $_POST['asignatura'];
	$nomasignatura = $datos_asignatura['nombre'];
	$abrevasignatura = $datos_asignatura['abrev'];
	$curso_asignatura = $datos_asignatura['curso'];
	
	$anc = 0;
	if ($nomasignatura == '') {
		$hor = mysqli_query($db_con, "select distinct a_asig, asig from horw where c_asig='".$_POST['asignatura']."'");
		if (mysqli_num_rows($hor)>0) {
			$hor_act = mysqli_fetch_array($hor);
			$codasignatura = $_POST['asignatura'];
			$nomasignatura = $hor_act['asig'];
			$abrevasignatura = $hor_act['a_asig'];
		}
		else{
			$result = mysqli_query($db_con, "SELECT idactividad, nomactividad FROM actividades_seneca WHERE idactividad='".$_POST['asignatura']."'");
			$datos_asignatura = mysqli_fetch_array($result);
			$codasignatura = $_POST['asignatura'];
			$nomasignatura = $datos_asignatura['nomactividad'];
			$abrevasignatura = abrevactividad($db_con, $datos_asignatura['nomactividad']);			
		}
	}
	if ($nomasignatura == '')
	{
		// no hay asignatura registrada
		$anc = 1;
		$hornocarg = mysqli_query($db_con, "select distinct a_asig, asig from asignocargadas where c_asig='".$_POST['asignatura']."'");
		$hncrow = mysqli_fetch_array($hornocarg);
		$codasignatura = $_POST['asignatura'];
		$nomasignatura = $hncrow['asig'];
		$abrevasignatura = $hncrow['a_asig'];

	}
if ($codasignatura=="25") {
		$unidad="GU";
	}
	$result = mysqli_query($db_con, "INSERT INTO horw (dia, hora, a_asig, asig, c_asig, prof, no_prof, c_prof, a_aula, a_grupo) VALUES ('$dia', '$hora', '$abrevasignatura', '$nomasignatura', '$codasignatura', '$profesor', '$numprofesor', '$codprofesor', '$coddependencia', '$unidad')");
	mysqli_query($db_con, "INSERT INTO horw_faltas (dia, hora, a_asig, asig, c_asig, prof, no_prof, c_prof, a_aula, a_grupo) VALUES ('$dia', '$hora', '$abrevasignatura', '$nomasignatura', '$codasignatura', '$profesor', '$numprofesor', '$codprofesor', '$coddependencia', '$unidad')");
	$_SESSION['n_cursos']=1;
	$t_profesores = mysqli_query($db_con,"select * from profesores where nivel='$curso_asignatura' and materia='$nomasignatura' and profesor='$profesor' and grupo='$unidad'");
	if (mysqli_num_rows($t_profesores)>0) {}
	else{
		if (strlen($curso_asignatura) > '0' and strlen($unidad) > '0') {
			mysqli_query($db_con, "INSERT INTO profesores (nivel, materia, profesor, grupo) VALUES ('$curso_asignatura', '$nomasignatura', '$profesor', '$unidad')");
		}		
	}

	// la asignatura es nueva en materias ya que no se ha cargado con el fichero de calificaciones
	if ($anc == 1)
	{
		$mater = mysqli_query($db_con, "select distinct nombre from materias where codigo='".$_POST['asignatura']."'");
		if (mysqli_num_rows($mater) == 0) 
		{
			$mater2 = mysqli_query($db_con, "select max(id) from materias");
			$mater2row = mysqli_fetch_array($mater2);
			$id = $mater2row[0] + 1;
			$mater3 = mysqli_query($db_con, "select CURSO from materias where grupo='".$unidad."'");
			$curso = mysqli_fetch_array($mater3);
			$ins = "insert into materias values ('".$_POST['asignatura']."','".$nomasignatura."','".$abrevasignatura."','".$curso[0]."','".$unidad."','".$id."')";
			mysqli_query($db_con, $ins);
			$ins2 = "insert into asignaturas values ('".$_POST['asignatura']."','".$nomasignatura."','".$abrevasignatura."','".$curso[0]."')";
			mysqli_query($db_con, $ins2);
		}
	}
	
	if (! $result) {
		$msg_error = "Error al modificar el horario. Error: ".mysqli_error($db_con);
	}
	else {
		header('Location:'.'index.php?msg_success=1');
	}
}

if (isset($_POST['actualizar'])) {
	$dia = $_POST['dia'];
	$hora = $_POST['hora'];
	
	// OBTENEMOS DATOS DEL PROFESOR
	$result = mysqli_query($db_con, "SELECT DISTINCT no_prof, c_prof FROM horw WHERE prof='".$profesor."'");
	$datos_profesor = mysqli_fetch_array($result);
	$numprofesor = $datos_profesor['no_prof'];
	$codprofesor = $datos_profesor['c_prof'];
	
	// OBTENEMOS DATOS DE LA ASIGNATURA
	$result = mysqli_query($db_con, "SELECT nombre, abrev FROM asignaturas WHERE codigo='".$_POST['asignatura']."' AND abrev NOT LIKE '%\_%'");
	$datos_asignatura = mysqli_fetch_array($result);
	$codasignatura = $_POST['asignatura'];
	$nomasignatura = $datos_asignatura['nombre'];
	$abrevasignatura = $datos_asignatura['abrev'];
	
	$anc = 0;
	if ($nomasignatura == '') {
		$hor = mysqli_query($db_con, "select distinct a_asig, asig from horw where c_asig='".$_POST['asignatura']."'");
		if (mysqli_num_rows($hor)>0) {
			$hor_act = mysqli_fetch_array($hor);
			$codasignatura = $_POST['asignatura'];
			$nomasignatura = $hor_act['asig'];
			$abrevasignatura = $hor_act['a_asig'];
		}
		else{
			$result = mysqli_query($db_con, "SELECT idactividad, nomactividad FROM actividades_seneca WHERE idactividad='".$_POST['asignatura']."'");
			if (mysqli_num_rows($result)>0) {
				$datos_asignatura = mysqli_fetch_array($result);
				$codasignatura = $_POST['asignatura'];
				$nomasignatura = $datos_asignatura['nomactividad'];
				$abrevasignatura = abrevactividad($db_con, $datos_asignatura['nomactividad']);
			}
		}	
		if ($nomasignatura == '')
		{
			$anc = 1;
			$hornocarg = mysqli_query($db_con, "select distinct a_asig, asig from asignocargadas where c_asig='".$_POST['asignatura']."'");
			$hncrow = mysqli_fetch_array($hornocarg);
			$codasignatura = $_POST['asignatura'];
			$nomasignatura = $hncrow['asig'];
			$abrevasignatura = $hncrow['a_asig'];

		}
	}
	if ($codasignatura=="25") {
		$unidad="GU";
	}
	
	if($esDesdoble) $unidad = $unidad.'D';
	if ($unidad == 'D') $unidad = '';

	$update1 = "UPDATE horw SET dia='$dia', hora='$hora', a_asig='$abrevasignatura', asig='$nomasignatura', c_asig='$codasignatura', a_aula='$coddependencia', a_grupo='$unidad' WHERE dia='".$_GET['dia']."' AND hora='".$_GET['hora']."' AND a_grupo='".$_GET['unidad']."' AND prof='$profesor' LIMIT 1";
	$update2 = "UPDATE horw_faltas SET dia='$dia', hora='$hora', a_asig='$abrevasignatura', asig='$nomasignatura', c_asig='$codasignatura', a_aula='$coddependencia', a_grupo='$unidad' WHERE dia='".$_GET['dia']."' AND hora='".$_GET['hora']."' AND a_grupo='".$_GET['unidad']."' AND prof='$profesor' LIMIT 1";

	$result = mysqli_query($db_con, $update1);
	
	mysqli_query($db_con, $update2);
	
	// Actualizamos tabla Profesores

	mysqli_query($db_con,"delete from profesores where profesor = '$profesor'");
	
	$pro =mysqli_query($db_con,"select distinct asig, a_grupo, prof from horw where prof = '$profesor' and (a_grupo in (select nomunidad from unidades) or a_grupo in (select distinct a_grupo from horw where c_asig = '135785' or c_asig = '25226')) and c_asig not like '2' order by prof");
	while ($prf =mysqli_fetch_array($pro)) {
		$materia = $prf[0];
		$grupo = $prf[1];
		$profesor = $prf[2];
		$tr_g = explode("-",$grupo);
		if(strlen($tr_g[1])>1){
		$grupo = substr($grupo,0,-1);
		}
		$niv =mysqli_query($db_con,"select distinct curso from alma where unidad = '$grupo'");
		$nive =mysqli_fetch_array($niv);
		$nivel = $nive[0];

		if (strlen($nivel) > '0' and strlen($grupo) > '0') {
			mysqli_query($db_con,"INSERT INTO  profesores (
			`nivel` ,
			`materia` ,
			`grupo` ,
			`profesor`
			) VALUES ('$nivel', '$materia', '$grupo', '$profesor')");
		}

	}
	// la asignatura es nueva en materias ya que no se ha cargado con el fichero de calificaciones
	if ($anc == 1)
	{
		$mater = mysqli_query($db_con, "select distinct nombre from materias where codigo='".$_POST['asignatura']."'");
		if (mysqli_num_rows($mater) == 0) 
		{
			$mater2 = mysqli_query($db_con, "select max(id) from materias");
			$mater2row = mysqli_fetch_array($mater2);
			$id = $mater2row[0] + 1;
			$mater3 = mysqli_query($db_con, "select CURSO from materias where grupo='".$unidad."'");
			$curso = mysqli_fetch_array($mater3);
			$ins = "insert into materias values ('".$_POST['asignatura']."','".$nomasignatura."','".$abrevasignatura."','".$curso[0]."','".$unidad."','".$id."')";
			mysqli_query($db_con, $ins);
			$ins2 = "insert into asignaturas values ('".$_POST['asignatura']."','".$nomasignatura."','".$abrevasignatura."','".$curso[0]."')";
			mysqli_query($db_con, $ins2);
		}
	}

	if (! $result) {
		$msg_error = "Error al modificar el horario. Error: ".mysqli_error($db_con);
	}
	else {
		header('Location:'.'index.php?msg_success=1');
	}
}

if (isset($_POST['eliminar'])) {
	$dia = $_GET['dia'];
	$hora = $_GET['hora'];
	$unidad_curso = $_GET['unidad'];
	$asig = $_GET['asignatura'];
	$exp_unidad = explode('|', $unidad_curso);
	$unidad = $exp_unidad[0];
	
	$del1 = "DELETE FROM horw WHERE dia='$dia' AND hora='$hora' AND a_grupo='$unidad' AND prof='$profesor' LIMIT 1";
	$del2 = "DELETE FROM horw_faltas WHERE dia='$dia' AND hora='$hora' AND a_grupo='$unidad' AND prof='$profesor' LIMIT 1";
	$del3 =  "DELETE FROM profesores WHERE grupo='$unidad' AND profesor='$profesor' and materia = (select distinct nombre from asignaturas where codigo='$asig' and abrev not like '%\_%')";

    //echo $del1;
    //echo $del2;
    //echo $del3;
	$result = mysqli_query($db_con, $del1);
	mysqli_query($db_con, $del2);
	//$cuantos = mysqli_query($db_con, "select a_grupo from horw WHERE a_grupo='$unidad' AND prof='$profesor' and c_asig='$asig'");
	//if (mysqli_num_rows($cuantos) == 0) 
		mysqli_query($db_con, $del3);
	
	if (! $result) {
		$msg_error = "Error al modificar el horario. Error: ".mysqli_error($db_con);
	}
	else {
		header('Location:'.'index.php?msg_success=1');
	}
}

include("../../../menu.php");
?>


<div class="container">
	
	<!-- TITULO DE LA PAGINA -->
	<div class="page-header">
		<h2>Administraci�n <small>Modificaci�n de horarios</small></h2>
	</div>
	
	
	<?php if(isset($msg_error)): ?>
	<div class="alert alert-danger">
		<?php echo $msg_error; ?>
	</div>
	<?php endif; ?>
	
	<?php if(isset($_GET['msg_success'])): ?>
	<div class="alert alert-success">
		El horario ha sido modificado.
	</div>
	<?php endif; ?>
	
	
	<!-- SCAFFOLDING -->
	<div class="row">
	
		<!-- COLUMNA IZQUIERDA -->
		<div class="col-sm-5">
			
			<div class="well">
				
				<form enctype="multipart/form-data" method="post" action="">
					<fieldset>
						<legend>Horario regular<?php echo $nom_curso; ?></legend>
						
						<?php if (stristr($_SESSION['cargo'],'1') == TRUE): ?>
						<div class="form-group">
						  <label for="profesor">Profesor/a</label>
						  <select class="form-control" id="profesor" name="profesor" onchange="submit()">
						  	<option value=""></option>
						  	<?php $result = mysqli_query($db_con, "SELECT nombre, departamento FROM departamentos WHERE departamento <> 'Admin' AND departamento <> 'Administracion' AND departamento <> 'Conserjeria' ORDER BY nombre ASC"); ?>
						  	<?php while ($row = mysqli_fetch_array($result)): ?>
						  	<option value="<?php echo $row['nombre']; ?>" <?php echo (isset($profesor) && $row['nombre'] == $profesor) ? 'selected' : ''; ?>><?php echo $row['nombre']; ?></option>
						  	<?php endwhile; ?>
						  </select>
						</div>
						
						<hr>
						<?php else: ?>
						<input type="hidden" name="profesor" value="<?php echo $profesor; ?>">
						<?php endif; ?>
						
						<div class="form-group">
						  <label for="dia">D�a de la semana</label>
						  <select class="form-control" id="dia" name="dia">
						  	<option value=""></option>
						  	<?php $arrdias = array(1=>'Lunes',2=>'Martes',3=>'Mi�rcoles',4=>'Jueves',5=>'Viernes'); ?>
						  	<?php foreach ($arrdias as $numdia => $nomdia): ?>
						  	<option value="<?php echo $numdia; ?>" <?php echo (isset($dia) && $numdia == $dia) ? 'selected' : ''; ?>><?php echo $nomdia; ?></option>
						  	<?php endforeach; ?>
						  </select>
						</div>
						
						<div class="form-group">
						  <label for="hora">Hora</label>
						  <select class="form-control" id="hora" name="hora">
						  	<option value=""></option>
						  	<?php $result_horas = mysqli_query($db_con,"SELECT hora_inicio, hora_fin, hora FROM tramos"); ?>
								<?php while ($horas = mysqli_fetch_array($result_horas)): ?>
								<option value="<?php echo $horas['hora']; ?>" <?php echo (isset($hora) && $horas['hora'] == $hora) ? 'selected' : ''; ?>><?php echo $horas['hora_inicio'].' - '.$horas['hora_fin'].' ('.$horas['hora'].')'; ?></option>
								<?php endwhile; ?>
						  </select>
						</div>
						
						<div class="form-group">
						  <label for="unidad">Unidad</label>
						  <select class="form-control" id="unidad" name="unidad" onchange="submit()">
						  	<option value=""></option>
						  	<?php $result = mysqli_query($db_con, "SELECT unidades.nomunidad, cursos.nomcurso FROM unidades JOIN cursos ON unidades.idcurso=cursos.idcurso order by unidades.idunidad"); ?>
						  	<?php while ($row = mysqli_fetch_array($result)): ?>
						  	<option value="<?php echo $row['nomunidad'].'|'.$row['nomcurso']; ?>" <?php echo (isset($unidad_curso) && $row['nomunidad'].'|'.$row['nomcurso'] == $unidad_curso) ? 'selected' : ''; ?>><?php echo $row['nomunidad'].' ('.$row['nomcurso'].')'; ?></option>
						  	<?php endwhile; ?>
						  </select>
						</div>

						<div class="form-group">
						  <label for="asignatura">Asignatura / Actividad</label>
						  <select class="form-control" id="asignatura" name="asignatura">
						 	<option value=""></option>
						 		<?php if ($unidad): ?>
						  	<optgroup label="Asignaturas">
						  	<?php /*
						  	"SELECT DISTINCT MATERIA, PROFESOR FROM profesores WHERE grupo='$curso'
						  		<?php $result = mysqli_query($db_con, "SELECT codigo, nombre, abrev, curso FROM materias WHERE codigo <> '' AND abrev NOT LIKE '%\_%' AND curso='$curso' and grupo = '$unidad' ORDER BY curso ASC, nombre ASC"); ?>
				  		  	<?php while ($row = mysqli_fetch_array($result)): ?>
				  		  	<option value="<?php echo $row['codigo']; ?>" <?php echo (isset($asignatura) && $row['codigo'] == $asignatura) ? 'selected' : ''; ?>><?php echo $row['curso'].' - '.$row['nombre'].' ('.$row['abrev'].')'; ?></option>
				  		  	<?php endwhile; ?>
				  		  	*/
				  		  	?>
						  		<?php 
						  		
						  		//echo "SELECT DISTINCT idmateria, MATERIA, PROFESOR FROM profesores, cursos, materias_seneca WHERE grupo='$unidad' and nivel = nomcurso and materias_seneca.idcurso = cursos.idcurso and nommateria = materia and profesor = '$profesor' order by materia";
						  		$result = mysqli_query($db_con, "SELECT DISTINCT idmateria, MATERIA, PROFESOR FROM profesores_relmatuni, cursos, materias_seneca WHERE grupo='$unidad' and nivel = nomcurso and materias_seneca.idcurso = cursos.idcurso and nommateria = materia and profesor = '$profesor' order by materia"); ?>
				  		  	<?php while ($row = mysqli_fetch_array($result)): ?>
				  		  	<option value="<?php echo $row['idmateria']; ?>" <?php echo (isset($asignatura) && $row['idmateria'] == $asignatura) ? 'selected' : ''; ?>><?php echo $unidad.' - '.$row[1]; ?></option>
				  		  	<?php endwhile; ?>
					  		</optgroup>
					  		<?php endif; ?>
						  	<optgroup label="Actividades">
						  		<?php if ($unidad): ?>
							  	<?php $result = mysqli_query($db_con, "SELECT DISTINCT idactividad, nomactividad FROM actividades_seneca WHERE idactividad='2' OR idactividad='21' OR idactividad='136' OR idactividad='351' OR idactividad='356' OR idactividad='386' OR idactividad='861' ORDER BY nomactividad ASC"); ?>
							  	<?php else: ?>
							  	<?php $result = mysqli_query($db_con, "SELECT DISTINCT idactividad, nomactividad FROM actividades_seneca WHERE idactividad <> 1 ORDER BY nomactividad ASC"); ?>
							  	<?php endif; ?>
							  	<?php while ($row = mysqli_fetch_array($result)): ?>
							  	<option value="<?php echo $row['idactividad']; ?>" <?php echo (isset($asignatura) && $row['idactividad'] == $asignatura) ? 'selected' : ''; ?>><?php echo $row['nomactividad']; ?></option>
							  	<?php endwhile; ?>
							  	<?php if (!$unidad): ?>
							  	<option value="GUC">Servicio de Guardia (Aula de Convivencia)</option>
							  	<?php endif; ?>
						  	</optgroup>
						  </select>
						</div>
						
						<div class="form-group">
						  <label for="dependencia">Aula</label>
						  <select class="form-control" id="dependencia" name="dependencia">
						  	<option value=""></option>
						  	<?php 
/*								$ocultar_dependencias_seneca = TRUE;
						  		$result = mysqli_query($db_con, "SELECT DISTINCT a_aula, n_aula FROM aulas WHERE a_aula <> n_aula AND n_aula <> '' and seneca = 1 ORDER BY n_aula"); 
						  		$aulas_hor = mysqli_num_rows($result);
						  		$result2 = mysqli_query($db_con, "SELECT nomdependencia, descdependencia FROM dependencias ORDER BY nomdependencia ASC"); 
						  		$aulas_dep = mysqli_num_rows($result2);
								if($aulas_hor)
								{
									if($aulas_hor >= $aulas_dep) 
										$ocultar_dependencias_seneca = FALSE; 
									echo '<optgroup label="Aulas registradas en Horw">';
							  		while ($row = mysqli_fetch_array($result))
							  		{
							  			echo '<option value="'.$row['a_aula'].'" ';
							  			if (isset($dependencia) && $row['a_aula'] == $dependencia)
							  			{
							  				echo 'selected';
										}
										else
										{
											echo '';
										}  
										echo '>'.$row['n_aula'].'</option>';
							  		}
									echo '</optgroup>';
							  	}
							  	if($ocultar_dependencias_seneca)
							  	{
									if($aulas_dep > 0)
									{
					  					echo '<optgroup label="Aulas registradas en S�neca">';
						  	  			while ($row = mysqli_fetch_array($result2))
						  	  			{
						  	  				echo '<option value="'.$row['nomdependencia'].'" ';
						  	  				if (isset($dependencia) && $row['a_aula'] == $dependencia)
								  			{
								  				echo 'selected';
											}
											else
											{
												echo '';
											}  
											echo '>'.$row['descdependencia'].'</option>';
						  	  			}
					  					echo '</optgroup>';
					  				}
							  	}*/
								$ocultar_dependencias_seneca = TRUE;
						  		$result = mysqli_query($db_con, "SELECT DISTINCT a_aula, n_aula, isinhorw FROM aulas WHERE a_aula <> n_aula AND n_aula <> '' and seneca = 1 ORDER BY isinhorw DESC, n_aula"); 
						  		$aulas_hor = mysqli_num_rows($result);
						  		$cambio = -1;
								if($aulas_hor)
								{
							  		while ($row = mysqli_fetch_array($result))
							  		{
							  			// compruebo si los datos han cambiado de aulas de horw a aulas de seneca
										if($row['isinhorw'] != $cambio)
										{
											// si ya se ha realizado una impresi�n de optgroup hay que cerrarlo
											if ($cambio != -1 )
												echo '</optgroup>';

											if ($row['isinhorw'] == 0)
												echo '<optgroup label="Aulas registradas en Horw">';
											else
												echo '<optgroup label="Aulas registradas en S�neca">';
											
											$cambio = $row['isinhorw'];
										}
							  			echo '<option value="'.$row['a_aula'].'" ';
							  			if (isset($dependencia) && $row['a_aula'] == $dependencia)
							  			{
							  				echo 'selected';
										}
										else
										{
											echo '';
										}  
										echo '>'.$row['n_aula'].'</option>';
							  		}
							  	}
								echo '</optgroup>';
							?>
						  </select>
						</div>
						
						<br>				
					  	
					  	<?php if (isset($_GET['dia']) && isset($_GET['hora'])): ?>
					  	<button type="submit" class="btn btn-primary" name="actualizar">Actualizar</button>
					  	<button type="submit" class="btn btn-warning" name="copiar">Copiar</button>
					  	<button type="submit" class="btn btn-danger" name="eliminar">Eliminar</button>
					  	<a href="index.php" class="btn btn-default">Nuevo</a>
					  	<?php else: ?>
					  	<button type="submit" class="btn btn-primary" name="enviar">A�adir</button>
					  	<a class="btn btn-default" href="../../index.php">Volver</a>
					  	<?php endif; ?>
					  	
				  </fieldset>
				</form>
				
			</div><!-- /.well -->
			
		</div><!-- /.col-sm-5 -->
		
		
		<div class="col-sm-7">
			
			<h3><?php echo $profesor; ?></h3>
			
			<div class="table-responsive">
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>&nbsp;</th>
							<th>Lunes</th>
							<th>Martes</th>
							<th>Mi�rcoles</th>
							<th>Jueves</th>
							<th>Viernes</th>
						</tr>
					</thead>
					<tbody>
					<?php $thoras = ""; ?>
					<?php $result_horas = mysqli_query($db_con,"SELECT hora FROM tramos"); ?>
					<?php while ($row = mysqli_fetch_array($result_horas)): ?>
					<?php $thoras[] = $row['hora']; ?>
					<?php endwhile; ?>
					
					<?php foreach($thoras as $thora): ?>
						<tr>
							<th><?php echo $thora; ?></th>
							<?php for($i = 1; $i < 6; $i++): ?>
							<?php $result = mysqli_query($db_con, "SELECT DISTINCT a_asig, asig, c_asig, a_grupo, a_aula, n_aula FROM horw WHERE prof='$profesor' AND dia='$i' AND hora='$thora'"); ?>
							<td width="20%">
					 			<?php while($row = mysqli_fetch_array($result)): ?>
					 			<abbr data-bs="tooltip" title="<?php echo $row['asig']; ?>"><?php echo $row['a_asig']; ?></abbr><br>
					 			<?php echo (!empty($row['n_aula']) && $row['n_aula'] != 'Sin asignar o sin aula' && $row['n_aula'] != ' ' || $row['a_aula'] != ' ') ? '<abbr class="pull-right text-danger" data-bs="tooltip" title="'.$row['n_aula'].'">'.$row['a_aula'].'</abbr>' : ''; ?>
					 			<?php echo (!empty($row['a_grupo'])) ? '<span class="text-warning">'.$row['a_grupo'].'</span>' : ''; ?><br>
					 			<a href="index.php?dia=<?php echo $i; ?>&hora=<?php echo $thora; ?>&unidad=<?php echo $row['a_grupo']; ?>&asignatura=<?php echo $row['c_asig']; ?>&dependencia=<?php echo $row['a_aula']; ?>"><span class="fa fa-edit fa-fw fa-lg"></span></a>
				 				<?php echo '<hr>'; ?>
					 			<?php endwhile; ?>
					 			<?php mysqli_free_result($result); ?>
					 		</td>
					 		<?php endfor; ?>
					 	</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
						
		</div><!-- /.col-sm-7 -->
	
	</div><!-- /.row -->
	
</div><!-- /.container -->
  
<?php include("../../../pie.php"); ?>
	
</body>
</html>
