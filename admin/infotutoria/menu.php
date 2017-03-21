<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); 

if (isset($_GET['unidad'])) {$unidad = $_GET['unidad'];}elseif (isset($_POST['unidad'])) {$unidad = $_POST['unidad'];}else{$unidad="";}
if (isset($_GET['alumno'])) {$alumno = $_GET['alumno'];}elseif (isset($_POST['alumno'])) {$alumno = $_POST['alumno'];}else{$alumno="";}
if (isset($_GET['fecha'])) {$fecha = $_GET['fecha'];}elseif (isset($_POST['fecha'])) {$fecha = $_POST['fecha'];}else{$fecha="";}
if (isset($_GET['tutor'])) {$tutor = $_GET['tutor'];}elseif (isset($_POST['tutor'])) {$tutor = $_POST['tutor'];}else{$tutor="";}
if (isset($_GET['id'])) {$id = $_GET['id'];}elseif (isset($_POST['id'])) {$id = $_POST['id'];}else{$id="";}
if (isset($_GET['del'])) {$del = $_GET['del'];}elseif (isset($_POST['del'])) {$del = $_POST['del'];}else{$del="";}
if (isset($_GET['todos'])) {$todos = $_GET['todos'];}elseif (isset($_POST['todos'])) {$todos = $_POST['todos'];}else{$todos="";}
if (isset($_GET['id_del'])) {$id_del = $_GET['id_del'];}elseif (isset($_POST['id_del'])) {$id_del = $_POST['id_del'];}else{$id_del="";}
if (isset($_GET['id_alumno'])) {$id_alumno = $_GET['id_alumno'];}elseif (isset($_POST['id_alumno'])) {$id_alumno = $_POST['id_alumno'];}else{$id_alumno="";}
if (isset($_GET['c_asig'])) {$c_asig = $_GET['c_asig'];}elseif (isset($_POST['c_asig'])) {$c_asig = $_POST['c_asig'];}else{$c_asig="";}
if (isset($_GET['unidad'])) {$unidad = $_GET['unidad'];}elseif (isset($_POST['unidad'])) {$unidad = $_POST['unidad'];}else{$unidad="";}
if (isset($_GET['motivo'])) {$motivo = $_GET['motivo'];}elseif (isset($_POST['motivo'])) {$motivo = $_POST['motivo'];}else{$motivo="";}
$activo1="";
$activo2="";
$activo3="";
$activo5="";
$activo3="";
if (strstr($_SERVER['REQUEST_URI'],'index.php')==TRUE) {$activo1 = ' class="active" ';}
if (strstr($_SERVER['REQUEST_URI'],'infotut.php')==TRUE) {$activo2 = ' class="active" ';}
if (strstr($_SERVER['REQUEST_URI'],'buscar.php')==TRUE){ $activo3 = ' class="active" ';}
if (strstr($_SERVER['REQUEST_URI'],'index_buscar.php')==TRUE){ $activo4 = ' class="active" ';}
if (strstr($_SERVER['REQUEST_URI'],'control.php')==TRUE){ $activo5 = ' class="active" ';}
?>
		<div class="container hidden-print">
			
			<?php if (acl_permiso($carg, array('1'))): ?>
+			<a href="preferencias.php" class="btn btn-sm btn-default pull-right"><span class="fa fa-cog fa-lg"></span></a>
+			<?php endif; ?>

			<!-- Button trigger modal -->
			<a href="#"class="btn btn-default btn-sm pull-right hidden-print" data-toggle="modal" data-target="#modalAyuda" style="margin-right: 5px;">
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
							<p>Los Informes de Tutor�a son activados por el Tutor cuando los padres de un alumno 
							solicitan una cita para hablar sobre este. Cuando han sido activados mediante el 
							formulario correspondiente (en el que el Tutor simplemente selecciona al alumno y la 
							fecha de la entrevista con los padres), los profesores del Equipo Educativo del alumno 
							reciben una solicitud de informaci�n para que describan el estado y evoluci�n del 
							alumno en su asignatura. La notificaci�n a los profesores aparece en la p�gina de 
							inicio de la Intranet, o bien en la p�gina de Informes de Tutor�a. La notificaci�n 
							desaparece cuando hemos rellenado el Informe o la fecha de la entrevista ha pasado.</p>
							<p>Una vez recibida la notificaci�n, el profesor entra en un formulario en el que 
							aparece la asignatura (o asignaturas, en el caso de que el profesor imparta m�s de una 
							al alumno, o �ste tenga una asignatura pendiente de continuidad), escribe su informe 
							para el Tutor y env�a los datos. Los Informes son visibles en todo momento para el 
							resto de los profesores del alumno.</p>
							<p>El m�dulo contiene la posibilidad de ver la totalidad de los Informes a lo largo 
							del Curso Escolar, o bien de buscar entre los Informes los correspondientes a un 
							alumno determinado. Hay que tener en cuenta que, una vez pasada la fecha de la 
							entrevista con los padres, los Informes de Tutor�a quedan bloqueados, aunque siguen 
							siendo visibles.</p>
							<p>El Equipo Directivo dispone de una opci�n, <strong>Control de Informes</strong>, 
							que presenta estad�sticas por alumno y profesor sobre los Informes de Tutor�a.</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Entendido</button>
						</div>
					</div>
				</div>
			</div>
			
			<ul class="nav nav-tabs">
				<li <?php echo $activo1;?>> <a href="index.php">P�gina de Informes de Tutor�a</a></li>
				
				<?php if (isset($config['informe_tutoria']['activa_administrativo']) && $config['informe_tutoria']['activa_administrativo'] == 1): ?>
+				<?php if (acl_permiso($carg, array('1', '2', '7'))): ?>
+				<li <?php echo $activo2;?>><a href="infotut.php?<?php if (isset($_SESSION['mod_tutoria']['unidad'])) {echo  "unidad=".$_SESSION['mod_tutoria']['unidad'];}?>">Activar Nuevo Informe</a></li>
+				<?php endif; ?>
+				<?php else: ?>
+				<?php if (acl_permiso($carg, array('1', '2'))): ?>
				<li <?php echo $activo2;?>><a href="infotut.php?<?php if (isset($_SESSION['mod_tutoria']['unidad'])) {echo  "unidad=".$_SESSION['mod_tutoria']['unidad'];}?>">Activar Nuevo Informe</a></li>
				<?php endif; ?>
				<?php endif; ?>
				
				<li <?php echo $activo3;?>> <a href="buscar.php?todos=1">Ver Todos los Informes</a></li>
				<li <?php echo $activo4;?>> <a href="index_buscar.php">Buscar Informes</a></li>
				<?php if (stristr($_SESSION ['cargo'],'1') == TRUE): ?>
				<li <?php echo $activo5;?>> <a href="control.php">Control de Informes</a></li>
				<?php endif; ?>
			</ul>
		</div>
