<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); ?>

<ul class="nav nav-pills nav-stacked">
	
	<?php if ($_SESSION['ide'] == 'admin' or stristr($_SESSION['cargo'],'0')==TRUE): ?>
	<!-- CONFIGURACI�N -->
	<li class="nav-header">Configuraci�n</li> 
	<li><a href="../config/config.php">Configuraci�n general y m�dulos</a></li>
	<?php endif;?>
	
	<!-- A PRINCIPIO DE CURSO -->
	<li class="nav-header">A principio de curso...</li>
	<li><a href="jefe/index2.php">Importar Alumnos y Asignaturas</a></li>
	<li><a href="jefe/index_xml.php">Importar datos del Centro</a></li>
	<li><a href="jefe/index_departamentos.php">Importar Profesores y Departamentos</a></li>
	<?php if(isset($config['mod_horarios']) && $config['mod_horarios']): ?>
	<li><a href="jefe/horario.php">Importar/Preparar Horarios </a></li>
	<?php endif; ?>
	<li><a href="jefe/index_profesores.php">Importar Profesores, Unidades y Materias</a></li>
	<li><a href="jefe/index_pas.php">Importar Personal no docente</a></li>
	<li><a href="jefe/index_festivos.php">Importar d�as festivos</a></li>
	<li><a href="jefe/rof/index.php">Modificar ROF</a></li>
	<?php if(isset($config['mod_horarios']) && $config['mod_horarios']): ?>
	<li><a href="jefe/index_unidepv.php">Importaci�n de Grupos-Aulas</a></li>
	<li><a href="jefe/index_limpia.php">Limpiar Horarios</a></li>
	<?php endif; ?>
	
	<!-- ACTUALIZACI�N -->
	<li class="nav-header">Actualizaci�n</li>
	<li><a href="jefe/index.php">Actualizar Alumnos</a></li>
	<li><a href="jefe/index_xml.php">Actualizar datos del Centro</a></li>
	<li><a href="jefe/index_departamentos2.php">Actualizar Profesores y Departamentos</a></li>
	<?php if(isset($config['mod_horarios']) && $config['mod_horarios']): ?>
		<li><a href="jefe/horario.php">Actualizar Horarios</a></li>
	<?php endif; ?>
	<li><a href="jefe/index_profesores.php">Actualizar Profesores, Unidades y Materias</a></li>
	<li><a href="jefe/index_pas.php">Actualizar Personal no docente</a></li>
	
	<!-- PERSONAL DEL CENTRO -->
	<li class="nav-header">Personal del Centro</li> 
	<li><a href="../config/cargos.php">Perfiles de los profesores</a></li>
	<li><a href="jefe/index_tutorias.php">Importar Grupos-Tutor�as</a></li>
	<li><a href="jefe/gest_dep.php">Gesti�n de los Departamentos</a></li>
	<li><a href="jefe/reset_password.php">Restablecer contrase�as</a></li>
	<?php if(isset($config['mod_horarios']) && $config['mod_horarios']): ?>
	<li><a href="jefe/horarios/index.php">Crear / Modificar horarios</a></li>
	<li><a href="jefe/horas.php">Hoja de Firmas para Profesores</a></li>
	<?php endif; ?>
	<li><a href="jefe/index_hor.php">Sustituci�n de profesores</a></li>
	<li><a href="jefe/index_fotos_profes.php">Subir fotos de profesores</a></li>
	<li><a href="jefe/informes/accesos.php">Informe de accesos</a></li>
	
	<!-- ALUMNOS -->
	<li class="nav-header">Alumnos</li>
	<li><a href="../admin/cursos/listatotal.php">Listas de todos los Grupos</a></li>
	<li><a href="jefe/form_carnet.php">Carnet de los alumnos</a></li>
	<li><a href="jefe/index_fotos.php">Subir fotos de alumnos</a></li>
	<li><a href="../admin/libros/indextextos.php">Libros de Texto Gratuitos</a></li>
	<?php if(isset($config['mod_matriculacion']) && $config['mod_matriculacion']): ?>
	<li><a href="../admin/matriculas/index.php">Matriculaci�n de alumnos</a></li>
	<li><a href="./jefe/index_mayores.php">Alumnos mayores de 18 a�os</a></li>	
	<?php endif; ?>
	<?php if($_SESSION['pagina_centro']==1): ?>	
	<li><a href="jefe/informes/accesos_alumnos.php">Informe de accesos</a></li>
	<?php endif; ?>
	<!-- CALIFICACIONES -->
	<li class="nav-header">Notas de evaluaci�n</li>
	<li><a href="jefe/index_notas.php">Importar Calificaciones</a></li>
	
	
	<?php if(isset($config['mod_centrotic']) && $config['mod_centrotic']): ?>
	<!-- CENTRO TIC -->
	<li class="nav-header">Centro TIC</li>
	<?php if(file_exists('jefe/TIC/alumnos.txt')): ?>
	<li><a href="jefe/TIC/download.php?file=alumnos.txt" target="_blank">Usuarios alumnos (Gesuser)</a></li>
	<?php endif; ?>
	<?php if(file_exists('jefe/TIC/alumnos_moodle.txt')): ?>
	<li><a href="jefe/TIC/download.php?file=alumnos_moodle.txt" target="_blank">Usuarios alumnos (Moodle)</a></li>
	<?php endif; ?>
	<?php if(file_exists('jefe/TIC/profesores.txt')): ?>
	<li><a href="jefe/TIC/download.php?file=profesores.txt" target="_blank">Usuarios profesores (Gesuser)</a></li>
	<?php endif; ?>
	<?php if(file_exists('jefe/TIC/profesores_moodle.txt')): ?>
	<li><a href="jefe/TIC/download.php?file=profesores_moodle.txt" target="_blank">Usuarios profesores (Moodle)</a></li>	
	<?php endif; ?>
	<?php endif; ?>
	
	<!-- SISTEMA DE RESERVAS -->
	<li class="nav-header">Sistema de Reservas</li> 
	<li><a href="../reservas/gestion_tipo.php">Gesti�n de reservas</a></li>
	
	<?php if ($_SESSION['ide'] == 'admin' or stristr($_SESSION['cargo'],'0')==TRUE):?>
	<li class="nav-header">Bases de datos</li>
	<li><a href="../config/config_datos.php">Bases de datos de cursos anteriores</a></li>
	<li><a href="jefe/copia_db/index.php">Copias de seguridad</a></li>
	<?php endif; ?>
	
	<?php if(isset($config['mod_sms']) && $config['mod_sms']): ?>
	<li class="nav-header">SMS</li>
	<li><a href="jefe/index_sms.php">Informaci�n env�os SMS</a></li> 
	<?php endif; ?>
</ul>
