<?php
require('bootstrap.php');
if ($_GET['resetea_mensaje']==1) {
	mysqli_query($db_con,"update mens_profes set recibidoprofe='1' where profesor='".$_GET['idea_mensaje']."'");
}
include("menu.php");
?>	

	<div class="container-fluid" style="padding-top: 15px;">
		
		<div class="row">
			
			<!-- COLUMNA IZQUIERDA -->
			<div class="col-md-3">
				
				<div id="bs-tour-menulateral">
				<?php include("menu_lateral.php"); ?>
				</div>
				
				<div id="bs-tour-ausencias">
				<?php include("admin/ausencias/widget_ausencias.php"); ?>
				</div>
				
				<div id="bs-tour-destacadas" class="hidden-xs">
				<?php include ("admin/noticias/widget_destacadas.php"); ?>
				</div>
	
			</div><!-- /.col-md-3 -->
			
			
			<!-- COLUMNA CENTRAL -->
			<div class="col-md-5">
				
				<?php 
				if (acl_permiso($carg, array('2'))) {
					if (file_exists('admin/tutoria/config.php')) {
						include('admin/tutoria/config.php');
					}
					include("admin/tutoria/inc_pendientes.php");
				}
				?>
				<div id="bs-tour-pendientes">
				<?php include ("pendientes.php"); ?>
				</div>
				
				<?php if (acl_permiso($carg, array('1'))): ?>
				<?php include ("estadisticas/inc_estadisticas_admin.php"); ?>
				<?php elseif (acl_permiso($carg, array('2'))): ?>
				<?php include ("estadisticas/inc_estadisticas_tutores.php"); ?>
				<?php endif; ?>
				
		        <div class="bs-module">
		        <?php include("admin/noticias/widget_noticias.php"); ?>
		        </div>
		        
		        <br>
				
			</div><!-- /.col-md-5 -->
			
			
			
			<!-- COLUMNA DERECHA -->
			<div class="col-md-4">
				
				<div id="bs-tour-buscar">
				<?php include("buscar.php"); ?>
				</div>
				
				<br><br>
				
				<div id="bs-tour-calendario">
				<?php
				define('MOD_CALENDARIO', 1);
				include("calendario/widget_calendario.php");
				?>
				</div>
				
				<br><br>
				
				<?php if($config['mod_horarios'] && ($dpto !== "Admin" && $dpto !== "Administracion" && $dpto !== "Conserjeria")): ?>
				
				<div id="bs-tour-horario">
					<h4><span class="fa fa-clock-o fa-fw"></span> Horario</h4>	
					<?php include("horario.php"); ?>
				</div>
				
				<?php else: ?>
				
				<h4><span class="fa fa-clock-o fa-fw"></span> Horario</h4>
				<div class="text-center">
					<a class="btn btn-sm btn-default" href="xml/jefe/horarios/index.php" style="margin-top:18px;">Crear/Modificar horario</a>
				</div>
				
				<?php endif; ?>
				
			</div><!-- /.col-md-4 -->
			
		</div><!-- /.row -->
		
	</div><!-- /.container-fluid -->

	<?php include("pie.php"); ?>
	
	<?php if (acl_permiso($carg, array('1'))): ?>
	<script src="//<?php echo $config['dominio'];?>/<?php echo $config['path'];?>/estadisticas/estadisticas_admin.js"></script>
	<?php elseif (acl_permiso($carg, array('2'))): ?>
	<script src="//<?php echo $config['dominio'];?>/<?php echo $config['path'];?>/estadisticas/estadisticas_tutores.js"></script>
	<?php endif; ?>
	
	<script>
	function notificar_mensajes(nmens) {
		if(nmens > 0) {
			$('#icono_notificacion_mensajes').addClass('text-warning');
		}
		else {
			$('#icono_notificacion_mensajes').removeClass('text-warning');
		}	
	}
	
	<?php if (isset($mensajes_pendientes) && $mensajes_pendientes): ?>
	var mensajes_familias = $("#lista_mensajes_familias li").size();
	var mensajes_profesores = $("#lista_mensajes li").size();
	var mensajes_pendientes = <?php echo $mensajes_pendientes; ?>;
	notificar_mensajes(mensajes_pendientes);
	<?php endif; ?>
	
	$('.modalmens').on('hidden.bs.modal', function (event) {
		var idp = $(this).data('verifica');
	  var noleido = $(this).find('#noleido-' + idp).attr('aria-pressed');
	  
	  // OJO: true o false se pasa como cadena de texto, no como binario
	  if (noleido == 'false') {
	  	
		  $.post( "./admin/mensajes/post_verifica.php", { "idp" : idp }, null, "json" )
		      .done(function( data, textStatus, jqXHR ) {
		          if ( data.status ) {
		              if (mensajes_profesores < 2) {
		              	$('#alert_mensajes').slideUp();
		              }
		              else {
		              	$('#mensaje_link_' + idp).slideUp();
		              }
		              $('#menu_mensaje_' + idp + ' div').removeClass('text-warning');
		              mensajes_profesores--;
		              mensajes_pendientes--;
		              notificar_mensajes(mensajes_pendientes);
		          }
		  });
		  
		 
		}
		
	});
	
	$('.modalmensfamilia').on('hidden.bs.modal', function (event) {
		var idf = $(this).data("verifica-familia");
	  
	  $.post( "./admin/mensajes/post_verifica.php", { "idf" : idf }, null, "json" )
	      .done(function( data, textStatus, jqXHR ) {
	          if ( data.status ) {
	              if (mensajes_familias < 2 ) {
	              	$('#alert_mensajes_familias').slideUp();
	              }
	              else {
	              	$('#mensaje_link_familia_' + idf).slideUp();
	              }
	              mensajes_familias--;
	              mensajes_pendientes--;
	              notificar_mensajes(mensajes_pendientes);
	          }
	  });
	});
	</script>
	
	<?php if(isset($_GET['tour']) && $_GET['tour']): ?>
	<script src="//<?php echo $config['dominio'];?>/<?php echo $config['path'];?>/js/bootstrap-tour/bootstrap-tour.min.js"></script>
	<?php include("./js/bootstrap-tour/intranet-tour.php"); ?>
	<?php endif; 
	?>

</body>
</html>
