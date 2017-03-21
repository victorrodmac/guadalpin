<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); ?>

<div class="hidden-print">
	
	<ul class="nav nav-tabs">
		<li<?php echo (strstr($_SERVER['REQUEST_URI'],'informe_profesores.php')==TRUE) ? ' class="active"' : ''; ?>><a href="informe_profesores.php">Profesores</a></li>
		<li<?php echo (strstr($_SERVER['REQUEST_URI'],'informe_grupos')==TRUE) ? ' class="active"' : ''; ?>><a href="informe_grupos.php">Grupos</a></li>
		<li<?php echo (strstr($_SERVER['REQUEST_URI'],'informe_materias.php')==TRUE) ? ' class="active"' : ''; ?>><a href="informe_materias.php">Asignaturas</a></li>
		<?php
		if(stristr($_SESSION['cargo'],'1') == TRUE or stristr($_SESSION['cargo'],'8') == TRUE){
		?>
		<li<?php echo (strstr($_SERVER['REQUEST_URI'],'informe_faltas.php')==TRUE) ? ' class="active"' : ''; ?>><a href="informe_faltas.php">Faltas no registradas</a></li>
		<?php
		}
		?>
	</ul>

</div>
