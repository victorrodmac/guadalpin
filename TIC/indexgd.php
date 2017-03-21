<?php
require('../bootstrap.php');

if (file_exists('config.php')) {
	include('config.php');
}

// COMPROBAMOS SI SE EDITA UNA INCIDENCIA
if(isset($_GET['id'])) {
	
	$id = $_GET['id'];
	
	if (stristr($_SESSION['cargo'],'1') == TRUE || strlen(stristr($_SESSION['cargo'],'g')) > 0) $sql_where = '';
	else $sql_where = 'AND profesor=\''.$_SESSION['profi'].'\'';
	
	$result = mysqli_query($db_con, "SELECT unidad, a_aula, nserie, fecha, hora, profesor, descripcion, estado, nincidencia, solucion, idea, fecsol FROM partesticgd WHERE parte='$id' $sql_where LIMIT 1");
	
	if (mysqli_num_rows($result)) {
		
		$row = mysqli_fetch_array($result);
		
		$profesor = $row['profesor'];
		$estado = $row['estado'];
		$unidad = $row['unidad'];
		$a_aula = $row['a_aula'];
		$numeroserie = $row['nserie'];
		$exp_fecha = explode('-', $row['fecha']);
		$fecha_inc = $exp_fecha[2].'-'.$exp_fecha[1].'-'.$exp_fecha[0];
		$hora = $row['hora'];
		$descripcion = $row['descripcion'];
		$nincidencia = $row['nincidencia'];
		$solucion = $row['solucion'];
		$idea = $row['idea'];
		$exp_fecha2 = explode('-', $row['fecsol']);
		$fecsol = $exp_fecha2[2].'-'.$exp_fecha2[1].'-'.$exp_fecha2[0];
		
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
	$idea = mysqli_real_escape_string($db_con, $_POST['idea']);
	$estado = mysqli_real_escape_string($db_con, $_POST['estado']);
	$unidad = mysqli_real_escape_string($db_con, $_POST['unidad']);
	$a_aula = mysqli_real_escape_string($db_con, $_POST['a_aula']);
	$numeroserie = mysqli_real_escape_string($db_con, $_POST['numeroserie']);
	$exp_fecha = explode('-', $_POST['fecha_inc']);
	$fecha_sql = $exp_fecha[2].'-'.$exp_fecha[1].'-'.$exp_fecha[0];
	$hora = mysqli_real_escape_string($db_con, $_POST['hora']);
	$descripcion = mysqli_real_escape_string($db_con, $_POST['descripcion']);
	$solucion = mysqli_real_escape_string($db_con, $_POST['solucion']);
	if (isset($_POST['fecsol']))
	{
		$exp_fecha2 = explode('-', $_POST['fecsol']);
		$fecsol = $exp_fecha2[2].'-'.$exp_fecha2[1].'-'.$exp_fecha2[0];
	}
	else
	{
		$fecsol = '';
	}

	if(empty($descripcion) || strlen(trim($descripcion))<8) { 
		$msg_error = 'El campo de descripción de la incidencia es obligatorio.';
	}
	else {
	
		// COMPROBAMOS SI SE TRATA DE UNA ACTUALIZACION O INSERCION
		if (isset($id)) {
			
			$nincidencia = $_POST['nincidencia'];
			
			$result = mysqli_query($db_con, "UPDATE partesticgd SET unidad='$unidad', a_aula='$a_aula', nserie='$numeroserie', fecha='$fecha_sql', hora='$hora', descripcion='$descripcion', estado='$estado', nincidencia='$nincidencia', solucion = '$solucion', fecsol = '$fecsol' WHERE parte=$id LIMIT 1");
			
			if(!$result) {
				$msg_error = 'La incidencia no se ha podido actualizar. Error: '.mysqli_error($db_con);
			}
			else {
				$msg_success = 'La incidencia ha sido actualizada.';
			}
			
			if ($estado == 'solucionado')
				// se le manda un mensaje al usuario de la incidencia de que ya se ha solucionado el problema.
			{
				 $query0='insert into mens_texto (asunto, texto, origen, destino ) values ("Solucion incidencia aula '.$a_aula.'" , "Solucionado incidencia en la unidad '.$unidad.' en el aula '.$a_aula.' con descripcion= '.$descripcion.'","'.$_SESSION['profi'].'","'.$idea.'")';
				//echo $query0;
				mysqli_query($db_con, $query0);
				$query1 = 'select id from mens_texto where asunto = "Solucion incidencia aula '.$a_aula.'" and texto = "Solucionado incidencia en la unidad '.$unidad.' en el aula '.$a_aula.' con descripcion= '.$descripcion.'" and origen = "'.$_SESSION['profi'].'"';
				//echo $query1;
				$id0 = mysqli_query($db_con, $query1);
				$id1 = mysqli_fetch_array($id0);
				$id = $id1[0];
				$query2="insert into mens_profes (id_texto, profesor) values ('".$id."','".$idea."')";
				//echo $query2;
				mysqli_query($db_con, $query2);
			}
			
		}
		else {
			$inser = "INSERT INTO partesticgd (unidad,a_aula,nserie,fecha,hora,profesor,descripcion,estado,idea) VALUES	('".$unidad."','".$a_aula."','".$numeroserie."','".$fecha_sql."','".$hora."','".$profesor."','".$descripcion."','".$estado."','".$idea."')";
			//echo $inser;
			$result = mysqli_query($db_con, $inser);
			
			if(!$result) {
				$msg_error = 'La incidencia no se ha podido registrar. Error: '.mysqli_error($db_con);
			}
			else {
				
				if ( $config['mod_centrotic'] == 1 ) 
				{
					$concorr = "SELECT correo, c_profes.idea, PROFESOR FROM c_profes, cargos WHERE cargos.dni = c_profes.dni and cargos.cargo = 'g'";
					//echo $concorr;
					$result = mysqli_query($db_con, $concorr);
					$row = mysqli_fetch_array($result);	
					if ($row['correo'] != "") {
						$correo_coordinador = $row['correo'];
						$tema = "Nuevo parte de incidencia";
						$texto = "Datos de la incidencia:
						Grupo --> '$unidad';
						Aula --> '$a_aula';
						Nª de Serie --> '$numeroserie';
						Fecha --> '$fecha_sql';
						Hora --> '$hora';
						Profesor --> '$profesor';
						Descripción --> '$descripcion';
						Estado --> '$estado';
						";
						
						require(INTRANET_DIRECTORY."/lib/class.phpmailer.php");
						$mail = new PHPMailer();
						$mail->Host = "localhost";
						$mail->From = $config['noreply'];
						$mail->FromName = $config['noreply'];
						$mail->AddReplyTo($config['noreply'], $config['noreply']);
						$mail->Sender = $config['noreply'];
						$mail->IsHTML(true);
						$mail->AddAddress($correo_coordinador);
						
						$mail->msgHTML($texto."<br/>Mensaje automatico de la intranet. No responda a este mensaje");
						$mail->Subject = 'Nueva incidencia ';


						if(!$mail->Send()) {
							$msg_class = "alert-danger";
							$msg = "Error: " . $mail->ErrorInfo;
						} 
						else{
							$msg_class = "alert-success";
							$msg = "El mensaje ha sido enviado.";
						}

					}
					
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
							<input type="hidden" name="idea" value="<?php echo (isset($idea)) ? $idea : $_SESSION['ide']; ?>">
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
							  <label for="alumno">Aula</label>
							  <?php 
								//echo "SELECT DISTINCT a_aula FROM HORW WHERE a_grupo='$unidad' ORDER BY a_aula ASC";
							  $result = mysqli_query($db_con, "SELECT DISTINCT a_aula FROM horw WHERE a_grupo='$unidad' ORDER BY a_aula ASC"); 
							  ?>
							  <?php if(mysqli_num_rows($result)): ?>
							  <select class="form-control" id="a_aula" name="a_aula">
							  	<option></option>
							  	<?php while($row = mysqli_fetch_array($result)): ?>
							  	<option value="<?php echo $row['a_aula']; ?>" <?php echo (isset($a_aula) && $a_aula == $row['a_aula']) ? 'selected' : ''; ?>><?php echo $row['a_aula']; ?></option>
							  	<?php endwhile; ?>
							  	<?php mysqli_free_result($result); ?>
							  </select>
							  <?php else: ?>
							  <select class="form-control" name="a_aula" disabled>
							  	<option></option>
							  </select>
							  <?php endif; ?>
							</div>
						  
						  <div class="row">
						  	<div class="col-sm-4">
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
						    <label for="descripcion">Descripción de la incidencia</label>
						    <textarea class="form-control" id="descripcion" name="descripcion" placeholder="Describa brevemente la incidencia del ordenador..." rows="6"><?php echo (isset($descripcion) && $descripcion) ? $descripcion : ''; ?></textarea>
						  </div>
						  
						  <?php if (isset($id) && stristr($_SESSION['cargo'],'1') == TRUE || strlen(stristr($_SESSION['cargo'],'g')) > 0): ?>
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
						  				<label for="nincidencia">Nº incidencia</label>
						  				<input type="text" class="form-control" id="nincidencia" name="nincidencia" value="<?php echo (isset($nincidencia) && $nincidencia) ? $nincidencia : ''; ?>">
						  			</div>
						  		</div>
						  		
						  	</div>

						  	<div class="col-sm-12">
						  		<div class="form-group" id="datetimepicker2">
						  			<label for="fecsol">Fecha</label>
						  			 <div class="input-group">
						  			 	<input type="text" class="form-control" id="fecsol" name="fecsol" value="<?php if ((isset($fecsol)) && ($fecsol == NULL )){echo $fecsol;}else{echo date('d-m-Y');}?>" data-date-format="DD-MM-YYYY">
						  			  <span class="input-group-addon">
						  			  	<span class="fa fa-calendar fa-fw"></span>
						  			  </span>
						  			</div> 
						  		</div>
						  	</div>
						  	
						  </div>
						  <div id="form-group-solucion" class="form-group">
						    <label for="solucion">Descripción de la solución de la incidencia</label>
						    <textarea class="form-control" id="solucion" name="solucion" placeholder="Describa brevemente la solución de la incidencia del ordenador..." rows="6"><?php echo (isset($solucion) && $solucion) ? $solucion : ''; ?></textarea>
						  </div>
						  
						  <?php endif; ?>
						  
						  
						  <button type="submit" class="btn btn-primary" name="enviar"><?php echo (isset($id)) ? 'Actualizar' : 'Registrar'; ?></button>
						  <button type="reset" class="btn btn-default">Cancelar</button>
					  </fieldset>
					</form>
					
				</div><!-- /.well -->
				
			</div><!-- /.col-sm-6 -->
			<?php
			/*
						  			 		if ((isset($fecsol)) && ($fecsol == NULL ))
						  			 		{
						  			 			echo $fecsol;
						  			 		}
						  			 		else
						  			 		{
						  			 			echo date('d-m-Y');
						  			 		}
						  			 		*/
			?>
			<!-- COLUMNA DERECHA -->
			<div class="col-sm-6">
				
				<h3>Información</h3>
				
				<p>En esta página se dan de alta los problemas que podáis tener con los ordenadores, tanto portátiles como fijos.</p>
				
				<p>Los fallos pueden ser de dos tipos: bien sucede que la máquina o alguna de sus partes presenta problemas (la máquina no enciende, se ha fastidiado la pantalla o el teclado, etc); o bien el Sistema Operativo o alguna de sus aplicaciones no funcionan. Cualquiera de las dos clases de problemas se registran aquí.</p>
				
				<p>Para modificar una incidencia ya registrada, diríjase a <strong>Listado de incidencias</strong> y haga click en el icono de edición <strong><span class="fa fa-edit"></span></strong>.</p>
				
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
