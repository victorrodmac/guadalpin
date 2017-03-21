<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed');

if (isset($_GET['recurso'])) {$recurso = $_GET['recurso'];}elseif (isset($_POST['recurso'])) {$recurso = $_POST['recurso'];}else{$recurso="";}
if (isset($_GET['servicio'])) {$servicio = $_GET['servicio'];}elseif (isset($_POST['servicio'])) {$servicio = $_POST['servicio'];}else{$servicio="";}
if (isset($_GET['mens'])) {$mens = $_GET['mens'];}elseif (isset($_POST['mens'])) {$mens = $_POST['mens'];}else{$mens="";}
if (isset($_GET['servicio_aula'])) {$servicio_aula = $_GET['servicio_aula'];}elseif (isset($_POST['servicio_aula'])) {$servicio_aula = $_POST['servicio_aula'];}else{$servicio_aula="";}
?>

<div class="container">
	
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
					<?php if (strstr($_SESSION['cargo'],"1")==TRUE): ?>
					<p>El sistema de reservas permite asignar y controlar el uso de los medios del
					Centro (Dependencias, Medios Audiovisuales, Ordenadores, etc.). Hay tres
					Tipos por defecto en el sistema: Aulas, Ordenadores TIC y Medios, pero puedes 
					a�adir o quitar categor�as (Tipos) a tu gusto.</p>
					
					<p>El m�dulo se configura bien desde el men� en la p�gina de Administraci�n de 
					la Intranet (<em><strong>Configuraci�n --> Gesti�n de Reservas</strong></em>) o 
					bien en la propia p�gina de las Reservas (<em><strong>Gesti�n de Reservas --> 
					Administraci�n de recursos</strong></em>). Defines en primer lugar los <em>tipos 
					de recursos</em> que vas abrir al sistema (Ordenadores TIC y Medios Audiovisuales 
					aparecen por defecto). Una vez definidos los tipos de recursos, crea los 
					<em>elementos</em> (Carrito n� 1, Videoproyector n� 1, etc.) dentro de cada tipo.
					Puedes a�adir una descripci�n o informaci�n m�s precisa en el campo 
					<em><strong>Observaciones</strong></em>, y aparecer� bajo el nombre del recurso 
					en la p�gina de entrada del m�dulo de reservas.</p>
					
					<?php endif; ?>
					<p>La reserva de Aulas y Dependencias del Centro est� integrada con el m�dulo de 
					Horarios. Puede funcionar sin la importaci�n de los horarios si creamos las Aulas 
					desde la opci�n del men� 'Administrar Aulas y Dependencias', pero est� 
					pensado para tomar la lista de aulas desde el horario que hemos importado. Por 
					defecto, todas las aulas del Centro aparecen en la lista como reservables. Si 
					deseamos ocultar aulas del sistema utilizamos la opci�n mencionada del men�; 
					tambi�n podemos crear aulas que no aparecen en el horario.</p>
					
					<p>El funcionamiento es sencillo: elegimos el aula (Recurso TIC o Medio), fecha y 
					hora; comprobamos que no ha sido reservada anteriormente por otro profesor y 
					procedemos a registrarla. El Aula Magna (Sal�n de Usos M�ltiples) s�lo puede ser 
					reservado por el Equipo Directivo (si necesitamos hacerlo, debemos pedir 
					autorizaci�n a los miembros del mismo). El resto de las aulas s�lo permiten la 
					reserva cuando en la hora correspondiente el aula no est� asignada en el horario 
					a alg�n profesor en tareas lectivas. Si nos hemos equivocado o queremos eliminar 
					una reserva, seleccionamos el d�a de la misma y borramos nuestro nombre el d�a y 
					la hora elegidos. Una vez borrado, enviamos los datos del formulario con el bot�n 
					<em><strong>Reservar</strong></em> y desaparecer� de la lista.</p>
					
					<?php if (strstr($_SESSION['cargo'],"1")==TRUE): ?>
					<p>El Equipo directivo puede asignar el horario de las dependencias del Centro de 
					varias maneras. Si el aula aparece en el Horario y este se ha importado en la base 
					de datos, se presentar�n las horas del aula con la asignatura que se imparte en 
					ese momento con la expresi�n <strong>Asignada por Horario</strong>. El Equipo 
					directivo puede asignar el aula a otro profesor por encima del Horario si por 
					alguna raz�n lo considera necesario. La hora aparecer� entonces en el formulario 
					con la expresi�n <strong>Asignada por Direcci�n.</strong></p>
					
					<p>Si el aula est� vac�a en ese momento cualquier profesor puede seleccionar esa 
					hora para utilizar el aula.</p>
					
					<p>Si queremos asignar una hora a un profesor durante todo el curso escolar, 
					seleccionamos al mismo en el formulario y pulsamos sobre el bot�n rojo 
					<strong>Reservar todo el Curso</strong>. Es importante elegir un d�a sin reserva 
					alguna de profesores para realizar la asignaci�n permanente porque ls profesores 
					que aparezcan en el formulario quedar�n fijados para todo el curso escolar. Los 
					campos en los que aparezca la palabra "Asignaci�n" (bien por Horario o bien por
					Direcci�n) no se registran en la reserva permanente. A partir de ese momento los 
					profesores ver�n en ese campo la expresi�n <strong>Asignada por Direcci�n</strong> 
					y no podr�n elegir el aula en esa hora. Este procedimiento se puede aplicar tantas 
					veces como queramos para aquellas dependencias o aulas que aparezcan en el horario.</p>
					<?php endif; ?>
					
					<p>Si hemos marcado la opci�n 'Centro TIC' en la instalaci�n de la aplicaci�n, 
					aparecer� una entrada en el men� para los carritos de ordenadores o aulas TIC. 
					Si utilizamos los carros de ordenadores mediante el sistema de reservas podemos 
					acceder a las estad�sticas de uso de los mismos dentro del men� de la p�gina de 
					inicio de la Intranet (Trabajo --> Centro TIC --> Estad�sticas).</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Entendido</button>
				</div>
			</div>
		</div>
	</div>


	<ul class="nav nav-tabs">
	<?php
	$rc = mysqli_query($db_con, "select tipo from reservas_tipos");
	while ($srv = mysqli_fetch_array($rc)) {
	?>
	<li
	<?php echo (strstr($_SERVER['REQUEST_URI'],'index.php?recurso='.urlencode($srv[0]))==TRUE) ? ' class="active"' : ''; ?>><a
		href="//<?php echo $config['dominio']; ?>/<?php echo $config['path']; ?>/reservas/index.php?recurso=<?php echo urlencode($srv[0]);?>"><?php echo $srv[0];?></a></li>
	<?php
	}
	?>
	
	<?php if ($config['mod_horarios']): ?>
	<li
	<?php echo (strstr($_SERVER['REQUEST_URI'],'index_aula')==TRUE) ? ' class="active"' : ''; ?>><a
		href="//<?php echo $config['dominio']; ?>/<?php echo $config['path']; ?>/reservas/index_aula.php?recurso=aula_grupo">Aulas
	y Dependencias del Centro</a></li>
	<?php endif; ?>
	<?php if (strstr($_SESSION['cargo'],"1")==TRUE): ?>	
	<li	class="dropdown<?php echo (strstr($_SERVER['REQUEST_URI'],'gestion')==TRUE OR strstr($_SERVER['REQUEST_URI'],'ocultar')==TRUE) ? ' active' : ''; ?>">
	<a class="dropdown-toggle" data-toggle="dropdown" href="#"> Gesti�n de reservas<span
		class="caret"></span> </a>
	<ul class="dropdown-menu" role="menu">	
	<?php if ($config['mod_horarios']): ?>

	<li
	<?php echo (strstr($_SERVER['REQUEST_URI'],'ocultar.php')==TRUE) ? ' class="active"' : ''; ?>><a
		href="//<?php echo $config['dominio']; ?>/<?php echo $config['path']; ?>/reservas/ocultar.php">Administraci�n de aulas y dependencias</a></li>
	<?php endif; ?>
	<li><a href="//<?php echo $config['dominio']; ?>/<?php echo $config['path']; ?>/reservas/gestion_tipo.php">Administraci�n de recursos</a></li>
	</ul>
	</li>
	<?php endif; ?>
</ul>

</div>
