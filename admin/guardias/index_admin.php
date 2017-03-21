<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

if (file_exists('config.php')) {
	include('config.php');
}

include("../../menu.php");

if (isset($_POST['profesor'])) $profesor = $_POST['profesor']; else $profesor="";

include 'menu.php';
?>
<div class="container">

	<div class="page-header">
		<h2 style="display: inline;">Guardias de aula <small>Registrar guardia</small></h2>
		
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
						<p>Selecciona el Profesor al que quieres apuntar una sustituci�n no registrada. Te 
						aparecer� el horario del Profesor, para que puedas determinar con precisi�n la hora 
						de la guardia (1� hora, 2� hora, etc) del d�a en cuesti�n.</p>
						<p>Seleccionas a continuaci�n el Profesor sustituido. Al hacer click en el campo de 
						la fecha, aparecer� una nueva ventana con el calendario en el que debes pinchar sobre 
						la fecha elegida. Escribe la hora de la guardia (1, 2, 3, etc) y env�a los datos.</p>
						<p>Si quieres consultar el historial de guardias de un Profesor, pincha en 
						<em>Consultar guardias y profesores</em>. Selecciona el Profesor y aparecer� un 
						hist�rico con todas las sustituciones realizadas. Si pinchas en una de las guardias 
						de su horario, podr�s ver las sustituciones de todos los profesores de esa guardia 
						en esa hora a lo largo del curso.</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Entendido</button>
					</div>
				</div>
			</div>
		</div>
		
	</div>
	
	
	<?php if (! $config['mod_horarios']): ?>
	<div class="alert alert-warning">
		<h5>M�dulo desactivado</h5>
		El m�dulo de Horarios debe ser activado en la Configuraci�n general de la Intranet para poder acceder a estas p�ginas, y ahora mismo est� desactivado.
	</div>
	<?php else: ?>
	
	<div class="row">
		
		<div class="col-sm-6">
		
			<div class="well">
				
				<form action="" method="post">
					
					<fieldset>
						<legend>Profesor/a de guardia</legend>
				
						<div class="form-group">
							<label for="profesor">Seleccione profesor/a</label> 
							<select class="form-control" id="profesor" name="profesor" onchange="submit()" required>
								<option value=""></option>
								<?php $result = mysqli_query($db_con, "SELECT DISTINCT prof FROM horw WHERE c_asig = '25' ORDER BY prof ASC"); ?>
								<?php while ($row = mysqli_fetch_array($result)): ?>
								<option value="<?php echo nomprofesor($row['prof']); ?>" <?php echo ($row['prof'] == $profesor) ? 'selected' : ''; ?>><?php echo nomprofesor($row['prof']); ?></option>
								<?php endwhile; ?>
							</select>
						</div>
					
					</fieldset>
					
				</form>
				
				<?php if (isset($profesor) && !empty($profesor)): ?>
				<?php include("inc_horario.php"); ?>
				<?php endif; ?>
				
			</div><!-- /.well -->
			
		</div><!-- /.col-sm-6 -->
		
		<div class="col-sm-6">
		
			<div class="well">
				
				<form action="procesa_guardias.php" method="post">
				
					<fieldset>
						<legend>Datos de la guardia</legend>
					
						<input type="hidden" id="profesorh" name="profesor" value="<?php echo $profesor; ?>">
						
						<div class="form-group">
							<label for="ausente">Profesor/a ausente</label>
							<select class="form-control" id="ausente" name="ausente" required>
								<option value=""></option>
							    <?php $result = mysqli_query($db_con, "SELECT DISTINCT prof FROM horw WHERE prof <>'".$profesor."' ORDER BY prof ASC"); ?>
								<?php while ($row = mysqli_fetch_array($result)): ?>
								<option value="<?php echo nomprofesor($row['prof']); ?>"><?php echo nomprofesor($row['prof']); ?></option>
								<?php endwhile; ?>
							</select>
					    </div>
					    
					    <div class="row">
					    
					    	<div class="col-md-6">
					    		
					    		<div class="form-group" id="datetimepicker1">     
					    			<label for="fecha_guardia">Fecha</label>
					    				<div class="input-group">
					    					<input type="text" class="form-control" id="fecha_guardia" name="fecha_guardia" value="<?php echo date('d-m-Y'); ?>" data-date-format="DD-MM-YYYY" required>
					    				<span class="input-group-addon"><span class="fa fa-calendar"></span></span>
					    			</div>   
					    		</div>
					    		
					    	</div><!-- /.col-md-6 -->
					    	
					    	<div class="col-md-6">
					    		
					    		<div class="form-group">
					    			<label for="hora_guardia">Hora:</label> 
					    			<select class="form-control" id="hora_guardia" name="hora_guardia" required>
					    				<?php for ($i = 1; $i < 7; $i++): ?>
					    				<option value="<?php echo $i; ?>" <?php echo ($i == $hora) ? 'selected' : ''; ?>><?php echo $i; ?>� hora</option>
					    				<?php endfor; ?>
					    			</select>
					    		</div>
					    		
					    	</div><!-- /.col-md-6 -->
					    
					    </div><!-- /.row -->
						
						<?php $array_turnos = array(1 => 'Hora completa', 2 => '1� media hora', 3 => '2� media hora'); ?> 
						
						<div class="form-group">
							<label class="turno_guardia">Turno:</label> 
							<select	class="form-control" id="turno_guardia" name="turno_guardia" required>
								<?php if (isset($config['guardias']['media_hora']) && $config['guardias']['media_hora'] == 1): ?>
								<?php for ($i = 1; $i < count($array_turnos)+1; $i++): ?>
								<option value="<?php echo $i; ?>"><?php echo $array_turnos[$i]; ?></option>
								<?php endfor; ?>
								<?php else: ?>
								<option value="<?php echo 1; ?>"><?php echo $array_turnos[1]; ?></option>
								<?php endif; ?>
							</select>
						</div>
						
						<br>
			
						<button type="submit" name="submit" class="btn btn-primary">Enviar datos</button>
					
					</fieldset>
				</form>
		
			</div><!-- /.well -->
			
		</div><!-- /.col-sm-6 -->
		
	</div><!-- /.row -->
	
	<?php endif; ?>
	
</div><!-- /.container -->

	<?php include("../../pie.php"); ?>
	
<script>  
$(function ()  
{ 
	$('#datetimepicker1').datetimepicker({
		language: 'es',
		pickTime: false
	})
});  
</script>
	
</body>
</html>
