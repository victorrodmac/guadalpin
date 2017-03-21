<?php 

defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); 

$activo1="";
$activo3="";
$activo4="";
$activo5="";
$activo6="";
$activo7="";
if (strstr($_SERVER['REQUEST_URI'],'importar')==TRUE) {$activo4 = ' class="active" ';}
if (strstr($_SERVER['REQUEST_URI'],'seneca/index.php')==TRUE) {$activo1 = ' class="active" ';}
if (strstr($_SERVER['REQUEST_URI'],$config['path'].'/faltas/index.php')==TRUE) {$activo3 = ' class="active" ';}
if (strstr($_SERVER['REQUEST_URI'],'justificar')==TRUE) {$activo5 = ' class="active" ';}
if (strstr($_SERVER['REQUEST_URI'],$config['path'].'/admin/faltas/index.php')==TRUE) {$activo6 = ' class="active" ';}
if (strstr($_SERVER['REQUEST_URI'],'absentismo')==TRUE) {$activo7 = ' class="active" ';}
if (strstr($_SERVER['REQUEST_URI'],'index_admin.php')==TRUE) {$activo2 = ' class="active" ';}

?>
<div class="container hidden-print">
	
	<!-- Button trigger modal -->
	<a href="#" class="btn btn-default btn-sm pull-right hidden-print" data-toggle="modal" data-target="#modalAyuda">
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
					<p>El m�dulo de <b><em>Faltas de Asistencia</em></b> incluye estas funciones: poner faltas; 
					justificarlas (si el cargo es Tutor); consultar las faltas de m�ltiples maneras; hacer un 
					seguimiento de los Alumnos Absentistas (Tutores, Jefatura, Orientaci�n y Servicios Sociales 
					municipales); subir las faltas de asistencia registradas a S�neca (si utilizamos la 
					aplicaci�n para poner las faltas, una vez al mes se suben a S�neca); y descargar las faltas 
					de asistencia desde S�neca (si no utilizamos la aplicaci�n para poner las faltas pero 
					queremos incorporar las faltas a la Intranet para incluirlas en otros m�dulos -Informes de 
					alumnos, env�o de SMS a las familias, Memoria de Tutor�a, Cuaderno del Profesor, etc.).</p>
					
					<h5 style="font-weight: 600;">Poner faltas a los alumnos</h5>
					<p>La p�gina por defecto para poner las faltas es un formulario muy simple (similar a la 
					aplicaci�n <em>iS�neca</em> de la Consejer�a), pensado para ser utilizado en el tiempo real 
					de trabajo en el aula, pero que tambi�n puede ser utilizado en cualquier momento posterior. 
					Se ofrecen tres opciones: Falta No Justificada (F), Falta Justificada (J) o Retraso (R). 
					Est� tambi�n adaptada para su uso en dispositivos m�viles, y aparece en el Men� de iconos 
					espec�ficos de estos dispositivos en la p�gina de inicio de la Intranet.</p>
					
					<p>Si estamos en una aula con alumnos impartiendo una asignatura, el m�dulo detecta el 
					Grupo(s)/Asignatura y presenta una lista de los alumnos; de lo contrario, selecciona la 
					fecha y luego el Grupo o Grupos de una de tus asignaturas. Aparecer� la lista de tus 
					alumnos y podr�s marcar las faltas. Env�a las Faltas para guardarlas en la Base de datos. 
					Si el alumno est� registrado en ese momento en una Actividad Complementaria o el Tutor 
					del mismo ya ha justificado de antemano la falta de esa hora, no podremos marcar la falta.</p>
					
					<p>Hay un m�todo alternativo para registrar las faltas semanalmente. Si quieres utilizarlo, 
					pulsa en el Men� superior sobre <em><strong>Poner</strong></em> y se abrir� la p�gina. Esta 
					p�gina te presenta un cuadrante con la estructura de tu horario semanal. En cada d�a y hora 
					en que das clase aparecen los Grupos afectados. Selecciona en primer lugar un d�a de la 
					semana en la que quieres marcar las faltas. A continuaci�n, escribe el n�mero de aula de los 
					alumnos ausentes seguido de un punto (por ejemplo: 3.12.27.31.) en el campo de texto que 
					se abre bajo cada Grupo. Cuando hayas terminado con una semana env�a los datos con el 
					bot�n (<strong><em>Registrar las faltas de asistencia</em></strong>). Selecciona otra semana 
					y repite el procedimiento.</p>
					
					<h5 style="font-weight: 600;">Consultas</h5>
					<p>El m�dulo incluye un conjunto de diferentes tipos de consulta que podemos hacer sobre las 
					faltas de asistencia: Faltas de un alumnos, de un Grupo, de una Asignatura, as� como consultar 
					que alumnos tienen un determinado n�mero m�nimo de faltas en un rango de fechas. Cada 
					formulario de consulta contiene una descripci�n de su funci�n espec�fica.</p>
					
					<?php if(stristr($_SESSION['cargo'],'1') == TRUE OR stristr($_SESSION['cargo'],'2') == TRUE): ?>
					<h5 style="font-weight: 600;">Justificar faltas a los Alumnos</h5>
					<p>Para justificar como Tutor una falta de tu Grupo selecciona en primer lugar un alumno en 
					la columna de la derecha. Una vez el alumno aparece seleccionado elige el mes correspondiente. 
					Aparecer�n en rojo las faltas de asistencia del alumno y en verde las faltas justificadas.</p>
					
					<p>Al hacer click sobre una celda del calendario cambiamos su estado: si est� vac�a se pone 
					roja, si est� roja se pone verde, y si est� verde la dejamos a cero.</p>
					
					<p>Si la falta no ha sido registrada todav�a (el d�a del calendario no es verde ni rojo), 
					aparecer� un cuadro de di�logo en el que deber�s seleccionar las horas en que el alumno ha 
					estado ausente. Una vez marcadas las horas de la falta podr�s justificarlas haciendo click 
					de nuevo sobre el d�a elegido.</p>
					<?php endif; ?> 
					
					<?php if(stristr($_SESSION['cargo'],'1') == TRUE): ?>
					<h5 style="font-weight: 600;">Absentismo</h5>
					<p>El m�dulo de alumnos absentistas permite hacer un seguimiento de los alumnos con faltas de 
					asistencia frecuentes (S�neca habla de 25 faltas no justificadas al mes). El Equipo directivo 
					selecciona utiliza en primer lugar la Consulta para seleccionar a los alumnos con m�s de 25
					faltas de asistencia. Los marca de la lista y env�a los datos. Una vez registrados, los Tutores 
					de los alumnos, Orientaci�n y Equipo directivo reciben una notificaci�n en la p�gina de entrada 
					de la aplicaci�n, invit�ndoles a informar sobre el alumno y las razones (o ausencia de las 
					mismas) por las que este falta a sus clases. El Equipo directivo puede a�adir tambi�n 
					informaci�n proporcionada por los Servicio de Asistencia Social del Ayuntamiento.</p>
					
					<h5 style="font-weight: 600;">Administraci�n</h5>
					<p>Las Faltas de asistencia registradas en la Intranet pueden subirse a S�neca. El enlace nos 
					lleva a una p�gina desde la que procedemos a generar el archivo que posteriormente importamos 
					a S�neca. La p�gina contiene informaci�n sobre la forma de hacerlo.</p>
					
					<p>Si no utilizamos la Intranet para poner las faltas, todav�a podemos beneficiarnos de contar 
					con las faltas descarg�ndolas desde S�neca e incorpor�ndolas a la aplicaci�n. Son muchos los 
					m�dulos que incluyen informaci�n sobre las faltas de asistencia, y de este modo podemos 
					suministrarles los datos necesarios. El enlace nos presenta una p�gina para importar las faltas 
					de S�neca con la informaci�n necesaria para proceder.</p>
					
					<p>El <strong><em>Informe de Faltas para Padres</em></strong> presenta las cartas en formato 
					PDF que se env�an a las familias con las faltas del alumno, preparadas para imprimir, cuando 
					proceda y por la raz�n que proceda. Presenta informaci�n sobre los d�as en que el alumno ha 
					faltado al Centro, as� como la normativa correspondiente.</p>
					
					<p>Si utilizamos el servicio de SMS interno de la aplicaci�n aparecer� tambi�n 
					<strong><em>SMS de Faltas para Padres</em></strong>. Nos permite hacer un env�o masivo de SMS 
					a los padres de los alumnos de un determinado Nivel. Un alumno entra en la lista de env�o cuando 
					tiene m�s de 5 faltas de asistencia sin justificar en el periodo seleccionado.</p>
					
					<p>Los <strong><em>Partes de Faltas</em></strong> ofrecen una lista completa de los partes de 
					asistencia que se dejan en el aula para que firme y registre las faltas el profesor, si se 
					sigue este m�todo de control de la asistencia de los alumnos. El documento PDF se presenta 
					en formato diario o semanal.</p>
					
					<?php endif; ?> 					

					<p><strong><em>Horario de faltas para profesores</em></strong> crea los horarios que los 
					profesores necesitan para registrar las faltas de asistencia de los alumnos. Cada profesor 
					marca en su horario el n�mero de clase de los alumnos ausentes en una semana, y, o bien las 
					registra posteriormente en el m�dulo de <strong><em>Poner faltas</em></strong>, o bien lo 
					entrega para quelos Tutores de Faltas lo hagan.</p>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Entendido</button>
				</div>
			</div>
		</div>
	</div>
	

	<ul class="nav nav-tabs">
	<?php
	if(stristr($_SESSION['cargo'],'3') == TRUE or stristr($_SESSION['cargo'],'1') == TRUE)
	{
		?>
		<li <?php echo $activo3;?>><a
			href="//<?php echo $config['dominio']; ?>/<?php echo $config['path']; ?>/faltas/poner2/index.php">
		Poner</a></li>
		<?php
	} else {
		?>
		<li <?php echo $activo3;?>><a
			href="//<?php echo $config['dominio']; ?>/<?php echo $config['path']; ?>/faltas/index.php">
		Poner</a></li>
		<?php
	}
	?>
	
	<?php
	if(stristr($_SESSION['cargo'],'2') == TRUE or stristr($_SESSION['cargo'],'1') == TRUE or stristr($_SESSION['cargo'],'3') == TRUE)
	{
		?>
		<li <?php echo $activo5;?>><a
			href="//<?php echo $config['dominio']; ?>/<?php echo $config['path']; ?>/faltas/justificar/index.php">
		Justificar</a></li>
		<?php
	}
	?>
		<li <?php echo $activo6;?>><a
			href="//<?php echo $config['dominio']; ?>/<?php echo $config['path']; ?>/admin/faltas/index.php">
		Consultar</a></li>
		<?php
		if(stristr($_SESSION['cargo'],'2') == TRUE)
		{
			?>
		<li <?php echo $activo7;?>><a
			href="//<?php echo $config['dominio']; ?>/<?php echo $config['path']; ?>/admin/tutoria/consulta_absentismo.php">
		Alumnos Absentistas</a></li>
		<?php
		}
		?>
		<?php
		if(stristr($_SESSION['cargo'],'1') == TRUE)
		{
		?>
		<li <?php echo $activo7;?>><a
			href="//<?php echo $config['dominio']; ?>/<?php echo $config['path']; ?>/faltas/absentismo/index.php">
		Alumnos Absentistas</a></li>
		<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"> Administraci�n </a>		
		<ul class="dropdown-menu" role="menu">
			<li <?php echo $activo1;?>><a href="//<?php echo $config['dominio']; ?>/<?php echo $config['path']; ?>/faltas/seneca/index.php"> Subir Faltas a S&eacute;neca</a></li>
			<li <?php echo $activo4;?>><a href="//<?php echo $config['dominio']; ?>/<?php echo $config['path']; ?>/faltas/seneca/importarSeneca.php">Descargar Faltas de S�neca</a></li>
			<li class="divider"></li>
			<li><a href="//<?php echo $config['dominio']; ?>/<?php echo $config['path']; ?>/admin/cursos/horariototal_faltas.php" target="_blank">Parte de faltas completo (por d�as)</a></li>
			<li><a href="//<?php echo $config['dominio']; ?>/<?php echo $config['path']; ?>/admin/faltas/horario_semanal.php" target="_blank">Parte de faltas completo (semanal)</a></li>
			<li><a href="//<?php echo $config['dominio']; ?>/<?php echo $config['path']; ?>/admin/faltas/horario_semanal_div.php" target="_blank">Parte de faltas completo Diversificaci�n</a></li>
			<li><a href="//<?php echo $config['dominio']; ?>/<?php echo $config['path']; ?>/admin/cursos/horariofaltas.php">Horario de Faltas para Profesores</a></li>
			<li class="divider"></li>
			<?php if ($config['mod_sms']) {?>
			<li><a href="//<?php echo $config['dominio']; ?>/<?php echo $config['path']; ?>/sms/sms_cpadres.php">SMS de Faltas para Padres</a></li>
			<?php }?>
			<li><a href="//<?php echo $config['dominio']; ?>/<?php echo $config['path']; ?>/admin/faltas/cpadres.php">Informe de Faltas para
			Padres</a></li>
			<li class="divider"></li>
			<li><a href="//<?php echo $config['dominio']; ?>/<?php echo $config['path']; ?>/admin/faltas/informe_grupos.php">Informes sobre el Registro de Faltas</a></li>
		</ul>
		</li>
		<?php
		}
		else{
			?>
			<li><a href="//<?php echo $config['dominio']; ?>/<?php echo $config['path']; ?>/admin/cursos/horariofaltas.php?horario_profesor=1" target="_blank">Horario de Faltas para Profesores</a></li>
			<?php
		}
		?>
	</ul>
	
</div>

<?php

// Comprobaci�n de Festivos
$festivos="";
if(stristr($_SESSION['cargo'],'1') == TRUE or stristr($_SESSION['cargo'],'3') == TRUE)
{
	$repe0=mysqli_query($db_con, "select fecha from festivos");
	if (mysqli_num_rows($repe0)<'1') {
		$festivos='actualizar';
	}

	$repe=mysqli_query($db_con, "select fecha from festivos where date(fecha) < date('".$config['curso_inicio']."')");
	if (mysqli_num_rows($repe) > '0') {
		$festivos='actualizar';
	}
}

if($festivos == 'actualizar'){
	echo '<br /><div align="center"><a href="../xml/jefe/index_festivos.php"><div class="alert alert-warning alert-block fade in" style="max-width:500px;text-align:left">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
		<legend>Atenci&oacute;n:</legend>
No se han importado los <strong>D�as festivos </strong>de este Curso Escolar en la Base de datos.</span> Hazlo antes de comenzar a utilizar la aplicaci�n de Faltas de asistencia, o tendr�s problemas para exportar posteriormente los datos a S�neca. Haz click para proceder a la importaci�n de las fechas.
	</div></a></div>';	
}
?>