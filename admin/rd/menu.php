<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); 

if (isset($_GET['q'])) {$expresion = $_GET['q'];}elseif (isset($_POST['q'])) {$expresion = $_POST['q'];}else{$expresion="";}
?>
	<div class="container hidden-print">
		
		<?php if (acl_permiso($carg, array('1'))): ?>
		<a href="preferencias.php" class="btn btn-sm btn-default pull-right"><span class="fa fa-cog fa-lg"></span></a>
		<?php endif; ?>
		
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
						<p>Este m�dulo permite a los Jefes de Departamento crear un documento 
						digital para las Reuniones del mismo, visible tanto por los miembros 
						del Departamento como por el Equipo directivo. Sustituye al m�todo 
						tradicional del Libro de Actas, y puede ser imprimido en caso de 
						necesidad por el Departamento o la Direcci�n.</p>
						<p>Seleccionamos en primer lugar la fecha de la reuni�n. Las Actas se 
						numeran autom�ticamente por lo que no es necesario intervenir manualmente 
						en ese campo. El formulario contiene un texto prefijado con el esquema 
						de cualquier Acta: Departamento, Curso escolar, N� de Acta, Asistentes etc. 
						El texto comienza con el Orden del d�a, y contin�a con la descripci�n de 
						los contenidos tratados en la reuni�n.</p>
						<p>A la derecha del formulario van apareciendo en su orden las Actas, 
						visibles para todos los miembros del Departamento. El Jefe del Departamento 
						puede editar las Actas <strong>hasta el momento en que se impriman</strong> 
						para entregar al Director: en ese momento el Acta queda bloqueada y s�lo 
						puede ser visualizada o imprimida. Al ser imprimida aparece un icono de 
						verificaci�n sustituyendo al icono de edici�n en la lista de actas. Por 
						esta raz�n, hay que se muy cuidadoso e imprimir el Acta s�lo cuando la misma 
						est� completada.</p>
						<p>Los Administradores de la Intranet (Equipo Directivo, por ejemplo) tiene 
						acceso a una opci�n, 'Todas las Actas', que les abre una p�gina con todas 
						las Actas de todos los Departamentos. La edici�n est� prohibida, pero pueden 
						verlas e imprimirlas.</p>
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
					<input type="text" class="form-control input-sm" id="q" name="q" maxlength="60" value="<?php echo (isset($_GET['q'])) ? $_GET['q'] : '' ; ?>" placeholder="Buscar...">
 		     		<span class="input-group-btn">
 		      			<button class="btn btn-default btn-sm" type="submit"><span class="fa fa-search fa-lg"></span></button>
 		     		</span>
 		  		 </div><!-- /input-group -->
 		  	</div><!-- /.col-lg-3--> 		 
		</form>  
  	 	<ul class="nav nav-tabs">
 			<li<?php echo (strstr($_SERVER['REQUEST_URI'],'index.php')==TRUE) ? ' class="active"' : ''; ?>><a href="index.php">Registrar o consultar actas</a></li>	
 			<?php if (acl_permiso($_SESSION['cargo'], array('1'))): ?>
          	<li<?php echo (strstr($_SERVER['REQUEST_URI'],'administracion.php')==TRUE) ? ' class="active"' : ''; ?>><a href="administracion.php">Administrar actas</a></li>
          	<?php endif; ?>
		</ul>
	</div>