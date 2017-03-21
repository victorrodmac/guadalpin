<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); ?>

<!-- MODAL NUEVO CALENDARIO -->
<form id="formNuevoCalendario" method="post" action="post/nuevoCalendario.php?mes=<?php echo $mes; ?>&anio=<?php echo $anio; ?>" data-toggle="validator">
	<div id="modalNuevoCalendario" class="modal fade">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Nuevo calendario</h4>
	      </div>
	      <div class="modal-body">
        
        	<fieldset>
        		
        		<div class="form-group">
        			<label for="cmp_calendario_nombre" class="visible-xs">Nombre</label>
        			<input type="text" class="form-control" id="cmp_calendario_nombre" name="cmp_calendario_nombre" placeholder="Nombre del calendario" maxlength="30" required autofocus>
        		</div>
        		
        		<div class="form-group" id="colorpicker1">
        			<label for="cmp_calendario_color">Color</label>
        			<div class="input-group">
        				<input type="text" class="form-control" id="cmp_calendario_color" name="cmp_calendario_color" value="<?php echo randomColor(); ?>" required>
        				<span class="input-group-addon"><i></i></span>
        			</div>
        		</div>
        		
        		<?php if (stristr($_SESSION['cargo'],'1')): ?>
        		<div class="checkbox">
        		   <label>
        		     <input type="checkbox" id="cmp_calendario_publico" name="cmp_calendario_publico"> Hacer público este calendario.<br>
        		     <small class="text-muted">Será visible por todos los profesores del centro. Solo el Equipo directivo puede crear y editar eventos en este calendario.</small>
        		   </label>
        		</div>
        		<?php endif; ?>
        				        		
        	</fieldset>
        
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	        <button type="submit" class="btn btn-primary">Crear</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</form>
<!-- FIN MODAL NUEVO CALENDARIO -->


