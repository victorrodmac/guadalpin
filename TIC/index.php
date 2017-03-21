<?php
require('../bootstrap.php');

if (file_exists('config.php')) {
	include('config.php');
}

// COMPROBAMOS SI SE EDITA UNA INCIDENCIA
if(isset($_GET['id'])) {
	
	$id = $_GET['id'];
	
	if (stristr($_SESSION['cargo'],'1') == TRUE || $config['tic']['coordinador'] == $pr) $sql_where = '';
	else $sql_where = 'AND profesor=\''.$_SESSION['profi'].'\'';
	
	$result = mysqli_query($db_con, "SELECT unidad, carro, nserie, fecha, hora, alumno, profesor, descripcion, estado, nincidencia FROM partestic WHERE parte='$id' $sql_where LIMIT 1");
	
	if (mysqli_num_rows($result)) {
		
		$row = mysqli_fetch_array($result);
		
		$profesor = $row['profesor'];
		$estado = $row['estado'];
		$unidad = $row['unidad'];
		$alumno = $row['alumno'];
		$carrito = $row['carro'];
		$numeroserie = $row['nserie'];
		$exp_fecha = explode('-', $row['fecha']);
		$fecha_inc = $exp_fecha[2].'-'.$exp_fecha[1].'-'.$exp_fecha[0];
		$hora = $row['hora'];
		$descripcion = $row['descripcion'];
		$nincidencia = $row['nincidencia'];
		
		mysqli_free_result($result);
		
	}
	else {
		$msg_error = "La incidencia que intenta editar no existe o no tiene privilegios administrativos para editarla.";
		unset($id);
	}

}


// ENVIO DEL FORMULARIO
if(isset($_POST['enviar'])) {
	
	$profesor = mysqli_real_escape_string($db_con, $_POST['profesor']);
	$estado = mysqli_real_escape_string($db_con, $_POST['estado']);
	$unidad = mysqli_real_escape_string($db_con, $_POST['unidad']);
	$alumno = mysqli_real_escape_string($db_con, $_POST['alumno']);
	$carrito = mysqli_real_escape_string($db_con, $_POST['carrito']);
	$numeroserie = mysqli_real_escape_string($db_con, $_POST['numeroserie']);
	$exp_fecha = explode('-', $_POST['fecha_inc']);
	$fecha_sql = $exp_fecha[2].'-'.$exp_fecha[1].'-'.$exp_fecha[0];
	$hora = mysqli_real_escape_string($db_con, $_POST['hora']);
	$descripcion = mysqli_real_escape_string($db_con, $_POST['descripcion']);
	
	if(empty($descripcion) || strlen(trim($descripcion))<8) { 
		$msg_error = 'El campo de descripci�n de la incidencia es obligatorio.';
	}
	else {
	
		// COMPROBAMOS SI SE TRATA DE UNA ACTUALIZACION O INSERCION
		if (isset($id)) {
			
			$nincidencia = $_POST['nincidencia'];
			
			$result = mysqli_query($db_con, "UPDATE partestic SET unidad='$unidad', carro='$carrito', nserie='$numeroserie', fecha='$fecha_sql', hora='$hora', alumno='$alumno', descripcion='$descripcion', estado='$estado', nincidencia='$nincidencia' WHERE parte=$id LIMIT 1");
			
			if(!$result) {
				$msg_error = 'La incidencia no se ha podido actualizar. Error: '.mysqli_error($db_con);
			}
			else {
				$msg_success = 'La incidencia ha sido actualizada.';
			}
			
			
		}
		else {
			$result = mysqli_query($db_con, "INSERT INTO partestic (unidad,carro,nserie,fecha,hora,alumno,profesor,descripcion,estado) VALUES	('".$unidad."','".$carrito."','".$numeroserie."','".$fecha_sql."','".$hora."','".$alumno."','".$profesor."','".$descripcion."','".$estado."')");
			
			if(!$result) {
				$msg_error = 'La incidencia no se ha podido registrar. Error: '.mysqli_error($db_con);
			}
			else {
				
				if (isset($config['tic']['notificaciones']) && $config['tic']['notificaciones'] == 0) {
					
					$correo_coordinador = "admin@".$config['dominio'];
					
					if (isset($config['tic']['coordinador']) && $config['tic']['coordinador'] != "") {
						$result = mysqli_query($db_con, "SELECT correo FROM c_profes WHERE profesor = '".$config['tic']['coordinador']."'");
						$row = mysqli_fetch_array($result);
						
						if ($row['correo'] != "") {
							$correo_coordinador = $row['correo'];
						}
					}
					
					$tema = "Nuevo parte de incidencia";
					$texto = "Datos de la incidencia:
					Grupo --> '$unidad';
					Recurso --> '$carrito';
					N� de Serie --> '$numeroserie';
					Fecha --> '$fecha_sql';
					Hora --> '$hora';
					Alumno --> '$alumno';
					Profesor --> '$profesor';
					Descripci�n --> '$descripcion';
					Estado --> '$estado';
					";
					mail($correo_coordinador, $tema, $texto); 
				}
				
				$msg_success = 'La incidencia ha sido registrada.';
			}
			
		}
	}
}


if (isset($_POST['unidad'])) {
	$unidad = $_POST['unidad'];
}

include("../menu.php");
include("menu.php");
?>
	
	<div class="container">
		
		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<h2>Centro TIC <small><?php echo (isset($id)) ? 'Editar' : 'Nueva'; ?> incidencia</small></h2>
		</div>
		
		
		<!-- MENSAJES -->
		<?php if(isset($msg_success) && $msg_success): ?>
		<div class="alert alert-success" role="alert">
			<?php echo $msg_success; ?>
		</div>
		<?php endif; ?>
		
		<?php if(isset($msg_error) && $msg_error): ?>
		<div class="alert alert-danger" role="alert">
			<?php echo $msg_error; ?>
		</div>
		<?php endif; ?>
		
		
		<!-- SCAFFOLDING -->
		<div class="row">
		
			<!-- COLUMNA IZQUIERDA -->
			<div class="col-sm-6">
			
				<div class="well">
					
					<form method="post" action="">
						<fieldset>
							<legend><?php echo (isset($id)) ? 'Editar' : 'Nueva'; ?> incidencia</legend>
							
							<input type="hidden" name="profesor" value="<?php echo (isset($profesor)) ? $profesor : $_SESSION['profi']; ?>">
							<?php if(!isset($id)): ?> 
							<input type="hidden" name="estado" value="activo">
							<?php endif; ?>
							
							<div class="row">
								<!--FORMLISTACURSOS
								<div class="col-sm-6">
									<div class="form-group">
										<label for="curso">Curso</label>
									</div>
								</div>
								FORMLISTACURSOS//-->
								
								<div class="col-sm-12">
									<div class="form-group">
									  <label for="unidad">Unidad</label>
										<?php $result = mysqli_query($db_con, "SELECT DISTINCT unidad, SUBSTRING(unidad,2,1) AS orden FROM alma ORDER BY orden ASC"); ?>
										<?php if(mysqli_num_rows($result)): ?>
										<select class="form-control" id="unidad" name="unidad" onchange="submit()">
											<option value=""></option>
											<?php while($row = mysqli_fetch_array($result)): ?>
											<option value="<?php echo $row['unidad']; ?>" <?php echo (isset($unidad) && $unidad == $row['unidad']) ? 'selected' : ''; ?>><?php echo $row['unidad']; ?></option>
											<?php endwhile; ?>
											<?php mysqli_free_result($result); ?>
										</select>
										<?php else: ?>
										<select class="form-control" name="unidad" disabled>
											<option></option>
										</select>
										<?php endif; ?>
									</div>
								</div>
							</div>
							
							<div class="form-group">
							  <label for="alumno">Alumno/a</label>
							  <?php $result = mysqli_query($db_con, "SELECT DISTINCT APELLIDOS, NOMBRE, CLAVEAL FROM FALUMNOS WHERE unidad='$unidad' ORDER BY APELLIDOS ASC"); ?>
							  <?php if(mysqli_num_rows($result)): ?>
							  <select class="form-control" id="alumno" name="alumno">
							  	<option></option>
							  	<?php while($row = mysqli_fetch_array($result)): ?>
							  	<option value="<?php echo $row['APELLIDOS'].', '.$row['NOMBRE'].' --> '.$row['CLAVEAL']; ?>" <?php echo (isset($alumno) && $alumno == $row['APELLIDOS'].', '.$row['NOMBRE'].' --> '.$row['CLAVEAL']) ? 'selected' : ''; ?>><?php echo $row['APELLIDOS'].', '.$row['NOMBRE']; ?></option>
							  	<?php endwhile; ?>
							  	<?php mysqli_free_result($result); ?>
							  </select>
							  <?php else: ?>
							  <select class="form-control" name="alumno" disabled>
							  	<option></option>
							  </select>
							  <?php endif; ?>
							</div>
						  
						  <div class="row">
						  	<div class="col-sm-2">
					  			<div class="form-group">
					  				<label for="carrito">Recurso</label>
					  				<input type="text" class="form-control" id="carrito" name="carrito" value="<?php echo (isset($carrito) && $carrito) ? $carrito : ''; ?>" maxlength="1">
					  			</div>
						  	</div>
						  	
						  	<div class="col-sm-2">
						  		<div class="form-group">
						  			<label for="numeroserie">Ordenador</label>
						  			<input type="text" class="form-control" id="numeroserie" name="numeroserie" value="<?php echo (isset($numeroserie) && $numeroserie) ? $numeroserie : ''; ?>" maxlength="3">
						  		</div>
						  	</div>
						  	
						  	<div class="col-sm-6">
						  		<div class="form-group" id="datetimepicker1">
						  			<label for="fecha_inc">Fecha</label>
						  			 <div class="input-group">
						  			 	<input type="text" class="form-control" id="fecha_inc" name="fecha_inc" value="<?php echo (isset($fecha_inc)) ? $fecha_inc : date('d-m-Y'); ?>" data-date-format="DD-MM-YYYY">
						  			  <span class="input-group-addon">
						  			  	<span class="fa fa-calendar fa-fw"></span>
						  			  </span>
						  			</div> 
						  		</div>
						  	</div>
						  	
						  	<div class="col-sm-2">
						  		<div class="form-group">
						  			<label for="hora">Hora</label>
						  			<input type="number" class="form-control" id="hora" name="hora" min="1" max="6" value="<?php echo (isset($hora) && $hora) ? $hora : '1'; ?>" maxlength="1">
						  		</div>
						  	</div>
						  </div>
						   
						  <div id="form-group-descripcion" class="form-group">
						    <label for="descripcion">Descripci�n de la incidencia</label>
						    <textarea class="form-control" id="descripcion" name="descripcion" placeholder="Describa brevemente la incidencia del ordenador..." rows="6"><?php echo (isset($descripcion) && $descripcion) ? $descripcion : ''; ?></textarea>
						  </div>
						  
						  <?php if (isset($id) && stristr($_SESSION['cargo'],'1') == TRUE || $config['tic']['coordinador'] == $pr): ?>
						  <?php $estados = array('activo' => 'Activo', 'solucionado' => 'Solucionado'); ?>
						  <div class="row">
						  	
						  	<div class="col-sm-6">
						  		
						  		<div class="form-group">
						  			<div class="form-group">
						  				<label for="estado">Estado de la incidencia</label>
						  				<select class="form-control" id="estado" name="estado">
						  					<?php foreach ($estados as $valor => $desc): ?>
						  					<option value="<?php echo $valor; ?>"><?php echo $desc; ?></option>
						  					<?php endforeach; ?>
						  				</select>
						  			</div>
						  		</div>
						  		
						  	</div>
						  	
						  	<div class="col-sm-6">
						  		
						  		<div class="form-group">
						  			<div class="form-group">
						  				<label for="nincidencia">N� incidencia</label>
						  				<input type="text" class="form-control" id="nincidencia" name="nincidencia" value="<?php echo (isset($nincidencia) && $nincidencia) ? $nincidencia : ''; ?>">
						  			</div>
						  		</div>
						  		
						  	</div>
						  	
						  </div>
						  
						  
						  <?php endif; ?>
						  
						  
						  <button type="submit" class="btn btn-primary" name="enviar"><?php echo (isset($id)) ? 'Actualizar' : 'Registrar'; ?></button>
						  <button type="reset" class="btn btn-default">Cancelar</button>
					  </fieldset>
					</form>
					
				</div><!-- /.well -->
				
			</div><!-- /.col-sm-6 -->
			
			
			<!-- COLUMNA DERECHA -->
			<div class="col-sm-6">
				
				<h3>Informaci�n</h3>
				
				<p>En esta p�gina se dan de alta los problemas que pod�is tener con los ordenadores, tanto port�tiles como fijos.</p>
				
				<p>Los fallos pueden ser de dos tipos: bien sucede que la m�quina o alguna de sus partes presenta problemas (la m�quina no enciende, se ha fastidiado la pantalla o el teclado, etc); o bien el Sistema Operativo o alguna de sus aplicaciones no funcionan. Cualquiera de las dos clases de problemas se registran aqu�.</p>
				
				<p>Para modificar una incidencia ya registrada, dir�jase a <strong>Listado de incidencias</strong> y haga click en el icono de edici�n <strong><span class="fa fa-edit"></span></strong>.</p>
				
				<p class="text-danger">Si el problema ha sido causado por el mal uso de un alumno/a, registrar el problema en el modulo de Problemas de Convivencia.</p>

			
			</div>
			
		
		</div><!-- /.row -->
		
	</div><!-- /.container -->
  
<?php include("../pie.php"); ?>
	
	<?php if(isset($msg_error) && $msg_error): ?>
	<script>$("#form-group-descripcion").addClass("has-error");</script>
	<?php endif; ?>

	<script>  
	$(function ()  
	{ 
		$('#datetimepicker1').datetimepicker({
			language: 'es',
			pickTime: false
		});
	});  
	</script>

</body>
</html>
