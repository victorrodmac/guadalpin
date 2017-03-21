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
					<p>Hay dos tipos de textos (y documentos) que pueden enviarse a trav�s de
					la Intranet: la mensajer�a interna permite enviar un mensaje a un
					profesor o grupo de profesores y es accesible s�lo dentro de la
					aplicaci�n; tambi�n es posible enviar un correo electr�nico (con
					archivos adjuntos) si el servidor donde est� instalada la aplicaci�n
					tiene configurado un Servidor de correo (algo habitual en estos d�as).</p>
					<p>La redacci�n de un mensaje interno comienza con la selecci�n de un
					profesor o grupo de profesores. Los grupos de profesores pueden, a su
					vez, admitir una selecci�n m�ltiple (puedo marcar varios elementos
					manteniendo apretada la tecla <kbd>Ctrl</kbd>) o pueden ser grupos cerrados. 
					Si, por ejemplo, selecciono Profesores, Departamentos o Equipos educativos
					puedo elegir qu� profesores, Departamentos o Equipos son los
					destinatarios del mensaje. En los grupos cerrados (Equipo directivo,
					Jefes de Departamento, Orientaci�n, Claustro, etc.) reciben el mensaje
					todos sus miembros y no podemos seleccionar entre ellos.</p>
					<p>Una vez elegido el destinatario del mensaje escribimos el t�tulo (corto 
					y explicativo, ya que es la cabecera que veremos en la p�gina de entrada
					de la Intranet) y contenido del mismo. El texto admite enlaces,
					im�genes, etc.</p>
					<p>Los mensajes enviados y recibidos presentan los �ltimos 500 mensajes de
					nuestra lista. Si necesitamos ver un mensaje antiguo que no aparece en
					la lista utilizamos el sistema de b�squeda que aparece en la parte
					superior derecha del men�. Podemos borrar mensajes de esta lista as�
					como tambi�n de la lista de mensajes enviados.</p>
					<p>Si queremos enviar un correo electr�nico, elegimos los destinatarios de
					forma individual o en grupo, adjuntamos uno o varios documentos si
					procede y enviamos el mensaje.</p>
					<p>Si el Centro tiene contratado un sistema de env�o de SMS (la aplicaci�n
					est� preparada para funcionar con Trendoo) y el profesor
					pertenece a un perfil autorizado para utilizar el sistema (Equipo
					directivo, Tutores, DACE, Orientaci�n, etc.) se puede enviar un SMS con
					un m�ximo de 160 caracteres a los tel�fonos registrados en S�neca por la
					familia del alumno durante la matriculaci�n. Si necesitamos seleccionar
					m�ltiples alumnos de nuevo mantenemos presionada la tecla <kbd>Ctrl</kbd> 
					mientras los marcamos con el rat�n.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Entendido</button>
				</div>
			</div>
		</div>
	</div>

	<form method="get" action="buscar.php">
		<div class="navbar-search pull-right col-sm-3">
			<div class="input-group">
				<input type="text" class="form-control input-sm" id="q" name="q" value="<?php echo (isset($_GET['q'])) ? $_GET['q'] : '' ; ?>" placeholder="Buscar mensajes...">
				<span class="input-group-btn">
					<button class="btn btn-default btn-sm" type="submit"><span class="fa fa-search fa-lg"></span></button>
				</span>
			</div><!-- /input-group -->
		</div><!-- /.col-lg-3-->
	</form>
	
	<ul class="nav nav-tabs">
		<li<?php echo (strstr($_SERVER['REQUEST_URI'],'inbox=recibidos')==TRUE) ? ' class="active"' : ''; ?>><a href="index.php?inbox=recibidos">Mensajes recibidos</a></li>
		<li<?php echo (strstr($_SERVER['REQUEST_URI'],'inbox=enviados')==TRUE) ? ' class="active"' : ''; ?>><a href="index.php?inbox=enviados">Mensajes enviados</a></li>
		<li<?php echo (strstr($_SERVER['REQUEST_URI'],'redactar.php')==TRUE) ? ' class="active"' : ''; ?>><a href="redactar.php">Redactar mensaje</a></li>
		<li<?php echo (strstr($_SERVER['REQUEST_URI'],'correo.php')==TRUE) ? ' class="active"' : ''; ?>><a href="correo.php">Redactar correo</a></li>
		<?php if((isset($config['mod_sms']) && $config['mod_sms']) && ((stristr($_SESSION['cargo'],'1') == TRUE) or (stristr($_SESSION['cargo'],'6') == TRUE) or (stristr($_SESSION['cargo'],'7') == TRUE) or (stristr($_SESSION['cargo'],'8') == TRUE))): ?>
		<li><a href="../../sms/index.php">SMS</a></li>
		<?php endif; ?>
	</ul>

</div>
