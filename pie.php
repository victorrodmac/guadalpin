<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); ?>
    
    <footer class="hidden-print">
    	<div class="container-fluid" role="footer">
    		<hr>
    		
    		<p class="text-center">
    			<small class="text-muted">Versi�n <?php echo INTRANET_VERSION; ?> - Copyright &copy; <?php echo date('Y'); ?> <span id="copyright">IESMonterroso</span></small><br>
    			<small class="text-muted">Modificaci�n hecha por el IES Guadalp�n</small><br>
    			<small class="text-muted">Este programa es software libre, liberado bajo la GNU General Public License.</small>
    		</p>
    		<p class="text-center">
    			<small>
    				<a href="//<?php echo $config['dominio']; ?>/<?php echo $config['path']; ?>/LICENSE.md" target="_blank">Licencia de uso</a>
    				&nbsp;&nbsp;&nbsp;&middot;&nbsp;&nbsp;&nbsp;
    				<a href="https://github.com/IESMonterroso/intranet" target="_blank">Github</a>
    			</small>
    		</p>
    	</div>
    </footer>
    
    <?php if(isset($_SESSION['user_admin'])): ?>
    <div class="hidden-print" style="z-index: 1000; clear: both; position: fixed; bottom: 0; width: 100%; padding: 15px 20px; padding-bottom: 0; background-color: rgba(0,0,0,.8); color: #fff; font-size: 86%;">
    	<a href="#" id="debug_button" style="position: absolute; margin-top: -40px; padding: 5px 10px; background-color: rgba(0,0,0,.8); color: #fff; font-size: 86%; text-transform: uppercase;"><span class="fa fa-dashboard fa-fw"></span> An�lisis</a>
    	<div id="debug" class="row" style="display: none;">
    	
    		<div class="col-sm-9">
    			<p class="form-control-static" style="padding-top: 5px;">Memoria utilizada: <?php echo size_convert(memory_get_peak_usage()).' / '.ini_get('memory_limit').'B'; ?></p>
    		</div>
    		<div class="col-sm-3">
    			<form method="post" class="form-horizontal pull-right" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    				<div class="form-group">
    				    <label for="view_as_user" class="col-sm-4 control-label" style="padding-top: 5px;">Ver como</label>
						<div class="col-sm-8">
							<select class="form-control" id="view_as_user" name="view_as_user" onchange="submit()" style="height: 30px; font-size: 86%;">
								<?php $result = mysqli_query($db_con, "SELECT nombre, idea FROM departamentos ORDER BY nombre ASC"); ?>
								<?php while($row = mysqli_fetch_assoc($result)): ?>
								<option value="<?php echo $row['nombre']; ?>"<?php echo ($row['nombre'] == $_SESSION['profi']) ? ' selected' : ''; ?>><?php echo $row['nombre']; ?></option>
								<?php endwhile; ?>
								<?php mysqli_free_result($result); ?>
							</select>
						</div>
    				</div>
    			</form>
    		</div>   		
    	</div>
    	
    </div>
    <?php endif; ?>

    <!-- MODAL SESI�N-->
	<div class="modal fade" id="session_expired" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			    <h4 class="modal-title">Inactividad de la cuenta</h4>
			  </div>
			  <div class="modal-body">
			    <p>Hemos detectado inactividad en su cuenta. Por seguridad, la sesi�n se cerrar� autom�ticamente dentro de 
			    	<strong>3 minutos</strong>. Realice alguna actividad en la aplicaci�n para cancelar esta acci�n.</p>
			  </div>
			  <div class="modal-footer">
			    <button type="button" class="btn btn-default" data-dismiss="modal">Entendido</button>
			  </div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<!-- FIN MODAL SESI�N -->
    
    <!-- BOOTSTRAP JS CORE -->
    <script src="//<?php echo $config['dominio'];?>/<?php echo $config['path'];?>/js/jquery-1.12.4.min.js"></script>  
    <script src="//<?php echo $config['dominio'];?>/<?php echo $config['path'];?>/js/bootstrap.min.js"></script>
    
    <!-- PLUGINS JS -->
    <script src="//<?php echo $config['dominio'];?>/<?php echo $config['path'];?>/js/bootbox.min.js"></script>
    <script src="//<?php echo $config['dominio'];?>/<?php echo $config['path'];?>/js/validator/validator.min.js"></script>
    <script src="//<?php echo $config['dominio'];?>/<?php echo $config['path'];?>/js/summernote/summernote.min.js"></script>
    <script src="//<?php echo $config['dominio'];?>/<?php echo $config['path'];?>/js/summernote/summernote-es-ES.js"></script>
    <script src="//<?php echo $config['dominio'];?>/<?php echo $config['path'];?>/js/datetimepicker/moment.js"></script>
    <script src="//<?php echo $config['dominio'];?>/<?php echo $config['path'];?>/js/datetimepicker/moment-es.js"></script>
    <script src="//<?php echo $config['dominio'];?>/<?php echo $config['path'];?>/js/datetimepicker/bootstrap-datetimepicker.js"></script>
    <?php if(isset($PLUGIN_DATATABLES) && $PLUGIN_DATATABLES): ?>
    <script src="//<?php echo $config['dominio'];?>/<?php echo $config['path'];?>/js/datatables/jquery.dataTables.min.js"></script>
    <script src="//<?php echo $config['dominio'];?>/<?php echo $config['path'];?>/js/datatables/dataTables.bootstrap.js"></script>
    <?php endif; ?>
    <?php if(isset($PLUGIN_COLORPICKER) && $PLUGIN_COLORPICKER): ?>
    <script src="//<?php echo $config['dominio'];?>/<?php echo $config['path'];?>/js/colorpicker/js/bootstrap-colorpicker.min.js"></script>
    <?php endif; ?>
    <script src="//<?php echo $config['dominio'];?>/<?php echo $config['path'];?>/js/ajax_alumnos.js"></script>
    
    <script>
    $(function () {
    	<?php if($_SERVER['SCRIPT_NAME'] == '/'.$config['path'].'/index.php'): ?>
    	localStorage.clear();
    	gray_color_theme = $(".panel-heading").css("background-color");
    	localStorage['gray_color_theme'] = gray_color_theme;
    	<?php endif; ?>
    	$(".table > thead > tr > th").css("background-color", localStorage['gray_color_theme']);
    	$(".today").css("background-color", localStorage['gray_color_theme']);
     });
    </script>
   
    
    <style type="text/css">
    table.dataTable thead .sorting,
    table.dataTable thead .sorting_asc,
    table.dataTable thead .sorting_desc,
    table.dataTable thead .sorting_asc_disabled,
    table.dataTable thead .sorting_desc_disabled {
        background-color: ;
        cursor: pointer;
    }
    </style>
		
	<script>
	$(function () {
	  var nua = navigator.userAgent
	  var isAndroid = (nua.indexOf('Mozilla/5.0') > -1 && nua.indexOf('Android ') > -1 && nua.indexOf('AppleWebKit') > -1 && nua.indexOf('Chrome') === -1)
	  if (isAndroid) {
	    $('select.form-control').removeClass('form-control').css('width', '100%')
	  }
	  
	  $("#debug_button").click(function() {
	    $('#debug').slideToggle();
	  });
	  
	  $("#toggleMenu").click(function() {
	    $('#accordion').toggleClass("hidden-xs");
	  });
	  
	})
	</script>

	<script>
	$("[data-bs=tooltip]").tooltip({
		container: 'body'
	});
	
	$(document).on("click", "a[data-bb]", function(e) {
	    e.preventDefault();
	    var type = $(this).data("bb");
			var link = $(this).attr("href");
			
			if (type == 'confirm-delete') {
				bootbox.setDefaults({
				  locale: "es",
				  show: true,
				  backdrop: true,
				  closeButton: true,
				  animate: true,
				  title: "Confirmaci�n para eliminar",
				});
				
				bootbox.confirm("Esta acci�n eliminar� permanentemente el elemento seleccionado �Seguro que desea continuar?", function(result) {
				    if (result) {
				    	document.location.href = link;
				    }
				});
			}
	});
	</script>

	<script>
	$(document).ready(function() {
		var expired_time = (<?php echo ini_get("session.gc_maxlifetime"); ?> * 60000) - 180000;
		setTimeout(function() {
			$("#session_expired").modal('show');
		}, expired_time);
	});
	</script>
