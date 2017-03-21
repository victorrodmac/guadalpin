<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); ?>

	<div class="container hidden-print">
		
		<!-- Button trigger modal -->
		<a href="#"class="btn btn-default btn-sm pull-right hidden-print" data-toggle="modal" data-target="#modalAyuda">
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
						<p>Las Intervenciones de Jefatura funcionan a modo de Diario donde se registran las 
						actividades de distinto tipo (entrevistas con Padres o Alumnos, llamadas de tel�fono, 
						etc.) asociadas a determinadas causas (Orientaci�n acad�mica, evoluci�n de estudios, 
						t�cnicas de estudio, etc.) que Jefatura realiza sobre un alumno dentro de sus 
						funciones. Se recogen los datos de las intervenciones en el formulario de tal modo 
						que se pueda hacer un seguimiento de las actividades de Jefatura con los alumnos del 
						Centro.</p>
						<p>Adem�s de sus propias Intervenciones, Jefatura puede ver las Intervenciones del 
						Tutor as� como otras intervenciones generadas por procesos automatizados (env�o de 
						SMS por faltas de asistencia, problemas de convivencia, etc.) sobre un determinado 
						alumno dentro del historial del mismo.</p>
						<p>La p�gina presenta el formulario de intervenciones y una lista con todas las 
						intervenciones realizadas ordenadas por fecha. Al hacer click sobre un alumno de esta 
						lista, se visualiza la intervenci�n en el formulario (pudiendo editarla, borrarla, 
						etc.) y aparece el historial de las intervenciones sobre el alumno bajo el formulario.</p>
						<p>Las Intervenciones de Jefatura sobre un Profesor funcionan de modo semejante a las 
						Intervenciones sobre el alumno: un Diario donde Jefatura registra las actividades de 
						distinto tipo (entrevistas personal, comunicaci�n por escrito, llamadas de tel�fono, 
						etc.) asociadas a determinadas causas (Tema Pedag�gico, Disciplina, Tutor�as con 
						Familias, etc.) que Jefatura realiza sobre un dentro de sus funciones. Se recogen 
						los datos de las intervenciones en el formulario de tal modo que se pueda hacer un 
						seguimiento de las actividades de Jefatura con los Profesores del Centro.</p>
						<p>La p�gina presenta el formulario de intervenciones y una lista con todas las 
						intervenciones realizadas ordenadas por fecha. Al hacer click sobre un Profesor de 
						esta lista, se visualiza la intervenci�n en el formulario (pudiendo editarla, borrarla,
						 etc.) y aparece el historial de las intervenciones sobre el Profesor bajo el formulario.</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Entendido</button>
					</div>
				</div>
			</div>
		</div>
		
		<ul class="nav nav-tabs">
			<li <?php echo (strstr($_SERVER['REQUEST_URI'],'index.php')==TRUE) ? ' class="active"' : ''; ?>><a href="index.php">Intervenci�n sobre Alumno</a></li>
			<li <?php echo (strstr($_SERVER['REQUEST_URI'],'profesores.php')==TRUE) ? ' class="active"' : ''; ?>><a href="profesores.php">Intervenci�n sobre Profesor</a></li>
		</ul>
		
	</div>
	