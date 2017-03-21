<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); ?>

<div class="container hidden-print">
	
	<?php /*if (acl_permiso($carg, array('1'))): ?>
	<a href="preferencias.php" class="btn btn-sm btn-default pull-right"><span class="fa fa-cog fa-lg"></span></a>
	<?php endif; */?>
	
	<!-- Button trigger modal -->

	<a href="#"class="btn btn-default btn-sm pull-right hidden-print" data-toggle="modal" data-target="#modalAyuda" style="margin-left:25px; margin-right: 5px;">
		<span class="fa fa-question fa-lg"></span>
	</a>

	<!-- Modal -->
	<div class="modal fade" id="modalAyuda" tabindex="-1" role="dialog" aria-labelledby="modal_ayuda_titulo" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
					<h4 class="modal-title" id="modal_ayuda_titulo">Instrucciones de uso</h4>
				</div>
				<div class="modal-body">
					<p>Las p�ginas del tutor presentan informaci�n espec�fica sobre los alumnos de su tutor�a.<br>
					El men� superior abre las distintas opciones que se le presentan al tutor. 
					La p�gina principal (<b>Resumen</b>) contiene datos sobre los aspectos m�s relevantes de la vida escolar de los alumnos: Faltas de Asistencia, Problemas de Conducta, Actividades Extraescolares, Informes de Tutor�a y de Tareas por ausencia, intervenciones de tutor�a, Devoluci�n de libros a la Biblioteca, etc. Tambi�n presenta una lista de los mensajes de Padres/Alumnos y acceso a su p�gina de estos si el Centro dispone de ese servicio.</p>						
					<p>Las <b>Intervenciones de Tutor�a</b> permiten mantener un diaro en el que el Tutor registra sus intervenciones sobre alumnos de su grupo o sus padres. La p�gina presenta el formulario de intervenciones y una lista con todas las intervenciones realizadas ordenadas por fecha. Al hacer click sobre un alumno de esta lista, se visualiza la intervenci�n en el formulario (pudiendo editarla, borrarla, etc.) y aparece el historial de las intervenciones sobre el alumno bajo el formulario. La Intervenci�n va asociada a una <em>causa (Evoluci�n acad�mica, Faltas de asistencia, etc.)</em> y a un <em>tipo (Entrevista telef�nica o personal, etc.)</em> y </p>
					<p></p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Entendido</button>
				</div>
			</div>
		</div>
	</div>
		
	<?php if (strstr($_SESSION['cargo'],'1') == TRUE || strstr($_SESSION['cargo'],'8') == TRUE): ?>
		
		<form method="post" action="">
			<div class="pull-right"><?php $result = mysqli_query($db_con, "SELECT DISTINCT FTUTORES.unidad, tutor, curso FROM FTUTORES, alma where alma.unidad=FTUTORES.unidad ORDER BY FTUTORES.unidad ASC"); ?>
			<?php if(mysqli_num_rows($result)): ?> 
				<select class="form-control input-sm" id="tutor" name="tutor" onchange="submit()" style="width:280px;">
				<?php while($row = mysqli_fetch_array($result)): $curso_tutor=$row[2];?>
					<option value="<?php echo $row['tutor'].' ==> '.$row['unidad']; ?>"
					<?php echo ($_SESSION['mod_tutoria']['tutor'].' ==> '.$_SESSION['mod_tutoria']['unidad'] == $row['tutor'].' ==> '.$row['unidad']) ? 'selected' : ''; ?>><?php echo $row['unidad'].' - '.nomprofesor($row['tutor']); ?></option>
				<?php endwhile; ?>
				</select> 
			<?php else: ?> 
				<select class="form-control" id="tutor" name="tutor" disabled>
				<option value=""></option>
				</select> 
			<?php endif; ?> 
			<?php mysqli_free_result($result); ?>
			</div>
		</form>
	<?php endif; ?>
<?php
	$result2 = mysqli_query($db_con, "SELECT DISTINCT curso FROM alma where unidad= '".$_SESSION['mod_tutoria']['unidad']."'");
	$query2= mysqli_fetch_array($result2);
	$curso_tutor=$query2[0]; 
?>
<ul class="nav nav-tabs hidden-print">
	<li<?php echo (strstr($_SERVER['REQUEST_URI'],'index.php')==TRUE) ? ' class="active"' : ''; ?>><a href="index.php">Resumen</a>
	</li>
	<li<?php echo (strstr($_SERVER['REQUEST_URI'],'intervencion.php')==TRUE) ? ' class="active"' : ''; ?>><a href="intervencion.php">Intervenciones</a>
	</li>
	<li	class="dropdown<?php echo (strstr($_SERVER['REQUEST_URI'],'Tutor')==TRUE) ? ' active' : ''; ?>"><a class="dropdown-toggle" data-toggle="dropdown" href="#"> Men� de Tutor�a <span class="caret"></span> </a>
	<ul class="dropdown-menu" role="menu">
		<li><a href="../datos/datos.php?unidad=<?php echo $_SESSION['mod_tutoria']['unidad'] ?>">Datos de alumnos/as</a></li>
		<li><a href="../cursos/ccursos.php?unidad=<?php echo $_SESSION['mod_tutoria']['unidad']; ?>&submit1=1" target="_blank">Listado de alumnos/as</a></li>
		<?php if (isset($config['mod_sms']) && $config['mod_sms']): ?>
		<li><a href="../../sms/index.php?unidad=<?php echo $_SESSION['mod_tutoria']['unidad'];?>">Enviar SMS</a></li>	
		<?php endif; ?>
		<?php if (isset($config['mod_asistencia']) && $config['mod_asistencia']): ?>
		<li><a href="../../faltas/justificar/index.php">Justificar Faltas de Asistencia del Grupo</a></li>	
		<?php endif; ?>
		<li><a href="consulta_fotografias.php">Fotograf�as de alumnos/as</a></li>
		<li><a href="consulta_mesas.php">Asignaci�n de mesas</a></li>
		<li><a href="../../xml/jefe/form_carnet.php">Credenciales de alumnos</a></li>
		<li><a href="consulta_absentismo.php">Alumnos absentistas</a></li>
		<li class="divider"></li>
		<li><a href="../../admin/actividades/indexextra.php">Actividades Complementarias / Extraescolares</a></li>
		<?php if($_SERVER['SERVER_NAME']=="iesmonterroso.org"): ?>
		<li class="divider"></li>
		<li><a href="http://www.iesmonterroso.net/moodle/course/view.php?id=33"	target="_blank">Moodle de Orientaci�n</a></li>
		<?php endif; ?>
		<?php if($config['centro_provincia']=="M�laga"): ?>
		<li class="divider"></li>
		<li><a href="http://lnx.educacionenmalaga.es/orientamalaga/plan-provincial-2/" target="_blank">Plan Provincial contra el Absentismo Escolar</a></li>
		<?php endif; ?>
	</ul>
	</li>
	<li
		class="dropdown<?php echo (strstr($_SERVER['REQUEST_URI'],'informe_')==TRUE) ? ' active' : ''; ?>">
	<a class="dropdown-toggle" data-toggle="dropdown" href="#"> Informes <span
		class="caret"></span> </a>
	<ul class="dropdown-menu" role="menu">
		<li><a href="../informes/cinforme.php?unidad=<?php echo $_SESSION['mod_tutoria']['unidad']; ?>">Informe de un alumno/a</a></li>
		<li><a href="../infotutoria/infotut.php?unidad=<?php echo $_SESSION['mod_tutoria']['unidad']; ?>">Informe de tutor�a</a></li>
		<li><a href="../tareas/infotut.php?unidad=<?php echo $_SESSION['mod_tutoria']['unidad']; ?>">Informe de tareas</a></li>
		<li class="divider"></li>
		
		<?php 
		$inf_t = mysqli_query($db_con,"select idcurso from unidades where nomunidad = '".$_SESSION['mod_tutoria']['unidad']."'"); 
		$id_t = mysqli_fetch_array($inf_t);
		$id_curso = $id_t[0];
		if (strstr($id_curso,"10114")==TRUE or $id_curso=="105806" or $id_curso=="105825"):
		?>
		<li><a href="../tutoria/informe_evaluaciones.php">Informes de Evaluaci�n</a></li>
		<li class="divider"></li>
		<?php endif; ?>
		
		<?php 
		if ($id_curso=="101140"):
		?>
			<li><a href="../matriculas/consulta_transito.php">Informes de Tr�nsito</a></li>
			<li class="divider"></li>
		<?php endif; ?>
			<li><a href="../tutoria/informe_notas_grupo.php?unidad=<?php echo $_SESSION['mod_tutoria']['unidad']; ?>&curso=<?php echo $curso_tutor;?>">Estad�sticas de Evaluaci�n del Grupo</a></li>
			<li><a href="../tutoria/informe_notas_nivel.php?curso=<?php echo $curso_tutor;?>">Estad�sticas de Evaluaci�n del Nivel</a></li>
			<li class="divider"></li>
			<li><a href="informe_memoria.php">Memoria de tutor�a</a></li>
		</ul>
	</li>

	<?php if(strstr($curso_tutor,"E.S.O.")==TRUE and date('m') > '05' and date('m') < '10'): ?>
	<li><a
		href="../libros/libros.php?unidad=<?php echo $_SESSION['mod_tutoria']['unidad']; ?>&tutor=1">Libros
	de Texto</a></li>
	<?php endif; ?>
</ul>

</div>
