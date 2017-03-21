<?php
ini_set("session.cookie_lifetime","5600");
ini_set("session.gc_maxlifetime","7200");

require('../../bootstrap.php');

if (file_exists('config.php')) {
	include('config.php');
}

$organos = array('DFEIE', 'Equipo directivo', 'ETCP', 'Coord. Ense�anzas Biling�es', '�rea Art�stica', '�rea Cient�fico-Tecnol�gica', '�rea Social-Ling��stica', '�rea Formaci�n Profesional');

$bloquea_campos = 0;

if (isset($_POST['departamento']) || isset($_GET['organo'])) {
	
	$esOrgano = 0;
	
	if (isset($_POST['departamento'])) {
		$departamento = mysqli_real_escape_string($db_con, $_POST['departamento']);
		
		if (! acl_permiso($_SESSION['cargo'], array('1')) && $dpto != $_POST['departamento']) {
			acl_acceso();
		}
	}
	else {
		$departamento = mysqli_real_escape_string($db_con, $_GET['organo']);
		$organo_centro = $_GET['organo'];
	}
	
	// COMPROBAMOS SI SE HA ASIGNADO EL PERFIL DE SECRETARIO DEL DFEIE
	if ($departamento == 'DFEIE') {
		acl_acceso($_SESSION['cargo'], array('1','f'));
		
		$result = mysqli_query($db_con, "SELECT nombre FROM departamentos WHERE departamento <> 'Admin' AND departamento <> 'Administracion' AND departamento <> 'Conserjeria' AND cargo LIKE '%f%' ORDER BY nombre ASC");
		$existe_secretario_dfeie = mysqli_num_rows($result);
		
		if ((! isset($config['actas_depto']['secretario_dfeie']) || $config['actas_depto']['secretario_dfeie'] == "") && ! $existe_secretario_dfeie) {
			$msg_alerta = "No se ha definido al secretario del Departamento de Formaci�n, Evaluaci�n e Innovaci�n Educativa en los perfiles de la aplicaci�n ni en las preferencias del m�dulo.";
			$bloquea_campos = 1;
		}
		elseif (isset($config['actas_depto']['secretario_dfeie'])) {
			$jefe_departamento = $config['actas_depto']['secretario_dfeie'];
			$bloquea_campos = 0;
		}
		else {
			$row = mysqli_fetch_row($result);
			$jefe_departamento = $row[0];
			$bloquea_campos = 0;
		}
		
		$titulo = 'Departamento de Formaci�n, Evaluaci�n e Innovaci�n Educativa';
		$esOrgano = 1;
	}
	
	// COMPROBAMOS SI SE HA ASIGNADO EL PERFIL DE SECRETARIO DEL EQ. DIRECTIVO
	if ($departamento == 'Equipo directivo') {
		acl_acceso($_SESSION['cargo'], array('1'));
		
		if ((! isset($config['actas_depto']['secretario_ed']) || $config['actas_depto']['secretario_ed'] == "") && ! isset($config['directivo_secretaria'])) {
			$msg_alerta = "No se ha definido al secretario del Equipo Directivo en la configuraci�n de la aplicaci�n ni en las preferencias del m�dulo.";
			$bloquea_campos = 1;
		}
		elseif (isset($config['actas_depto']['secretario_ed'])) {
			$jefe_departamento = $config['actas_depto']['secretario_ed'];
			$bloquea_campos = 0;
		}
		else {
			$jefe_departamento = $config['directivo_secretaria'];
			$bloquea_campos = 0;
		}
		
		$titulo = 'Equipo Directivo';
		$esOrgano = 1;
	}
	
	// COMPROBAMOS SI SE HA ASIGNADO EL PERFIL DE SECRETARIO DEL ETCP
	if ($departamento == 'ETCP') {
		acl_acceso($_SESSION['cargo'], array('1','9'));
		
		if (! isset($config['actas_depto']['secretario_etcp']) || $config['actas_depto']['secretario_etcp'] == "") {
			$msg_alerta = "No se ha definido al secretario del Equipo T�cnico de Coordinaci�n Pedag�gica en la configuraci�n de la aplicaci�n ni en las preferencias del m�dulo.";
			$bloquea_campos = 1;
		}
		else {
			$jefe_departamento = $config['actas_depto']['secretario_etcp'];
		}
		
		$titulo = 'Equipo T�cnico de Coordinaci�n Pedag�gica';
		$esOrgano = 1;
	}
	
	// COMPROBAMOS SI SE HA ASIGNADO EL PERFIL DE SECRETARIO DEL EQ. COORD. ENSE�ANZAS BILING�ES
	if ($departamento == 'CEB') {
		if (! isset($config['actas_depto']['secretario_ceb']) || $config['actas_depto']['secretario_ceb'] == "") {
			$msg_alerta = "No se ha definido al secretario del Equipo de Coordinaci�n de Ense�anzas Biling�es en la configuraci�n de la aplicaci�n ni en las preferencias del m�dulo.";
			$bloquea_campos = 1;
		}
		else {
			$jefe_departamento = $config['actas_depto']['secretario_ceb'];
		}
		
		$titulo = 'Equipo de Coordinaci�n de Ense�anzas Biling�es';
		$esOrgano = 1;
	}
	
	// COMPROBAMOS SI SE HA ASIGNADO EL PERFIL DE SECRETARIO DEL EQ. COORD. ENSE�ANZAS BILING�ES
	if ($departamento == 'Coord. Ense�anzas Biling�es') {
		acl_acceso($_SESSION['cargo'], array('1','a'));
		
		if (! isset($config['actas_depto']['secretario_ceb']) || $config['actas_depto']['secretario_ceb'] == "") {
			$msg_alerta = "No se ha definido al secretario del Equipo de Coordinaci�n de Ense�anzas Biling�es en la configuraci�n de la aplicaci�n ni en las preferencias del m�dulo.";
			$bloquea_campos = 1;
		}
		else {
			$jefe_departamento = $config['actas_depto']['secretario_ceb'];
		}
		
		$titulo = 'Equipo de Coordinaci�n de Ense�anzas Biling�es';
		$esOrgano = 1;
	}
	
	// COMPROBAMOS SI SE HA ASIGNADO EL PERFIL DE SECRETARIO DEL �REA DE COMPETENCIA ART�STICA
	if ($departamento == '�rea Art�stica') {
		if (! isset($config['actas_depto']['secretario_aca']) || $config['actas_depto']['secretario_aca'] == "") {
			$msg_alerta = "No se ha definido al coordinador del �rea de Competencia Art�stica en la configuraci�n de la aplicaci�n ni en las preferencias del m�dulo.";
			$bloquea_campos = 1;
		}
		else {
			$jefe_departamento = $config['actas_depto']['secretario_aca'];
			
			if (! acl_permiso($_SESSION['cargo'], array('1')) && $pr != $config['actas_depto']['secretario_aca']) {
				acl_acceso();
			}
		}
		
		$titulo = '�rea de Competencia Art�stica';
		$esOrgano = 1;
	}
	
	// COMPROBAMOS SI SE HA ASIGNADO EL PERFIL DE SECRETARIO DEL �REA DE COMPETENCIA CIENTIFICO-TECNOL�GICA
	if ($departamento == '�rea Cient�fico-Tecnol�gica') {
		if (! isset($config['actas_depto']['secretario_acct']) || $config['actas_depto']['secretario_acct'] == "") {
			$msg_alerta = "No se ha definido al coordinador del �rea de Competencia Cient�fico-Tecnol�gica en la configuraci�n de la aplicaci�n ni en las preferencias del m�dulo.";
			$bloquea_campos = 1;
		}
		else {
			$jefe_departamento = $config['actas_depto']['secretario_acct'];
			
			if (! acl_permiso($_SESSION['cargo'], array('1')) && $pr != $config['actas_depto']['secretario_acct']) {
				acl_acceso();
			}
		}
		
		$titulo = '�rea de Competencia Cient�fico-Tecnol�gica';
		$esOrgano = 1;
	}
	
	// COMPROBAMOS SI SE HA ASIGNADO EL PERFIL DE SECRETARIO DEL �REA DE COMPETENCIA SOCIAL-LING�ISTICA
	if ($departamento == '�rea Social-Ling��stica') {
		if (! isset($config['actas_depto']['secretario_acsl']) || $config['actas_depto']['secretario_acsl'] == "") {
			$msg_alerta = "No se ha definido al coordinador del �rea de Competencia Social-Ling�istica en la configuraci�n de la aplicaci�n ni en las preferencias del m�dulo.";
			$bloquea_campos = 1;
		}
		else {
			$jefe_departamento = $config['actas_depto']['secretario_acsl'];
			
			if (! acl_permiso($_SESSION['cargo'], array('1')) && $pr != $config['actas_depto']['secretario_acsl']) {
				acl_acceso();
			}
		}
		
		$titulo = '�rea de Competencia Social-Ling�istica';
		$esOrgano = 1;
	}
	
	// COMPROBAMOS SI SE HA ASIGNADO EL PERFIL DE SECRETARIO DEL �REA DE FORMACI�N PROFESIONAL
	if ($departamento == '�rea Formaci�n Profesional') {
		if (! isset($config['actas_depto']['secretario_afp']) || $config['actas_depto']['secretario_afp'] == "") {
			$msg_alerta = "No se ha definido al coordinador del �rea de Formaci�n Profesional en la configuraci�n de la aplicaci�n ni en las preferencias del m�dulo.";
			$bloquea_campos = 1;
		}
		else {
			$jefe_departamento = $config['actas_depto']['secretario_afp'];
			
			if (! acl_permiso($_SESSION['cargo'], array('1')) && $pr != $config['actas_depto']['secretario_afp']) {
				acl_acceso();
			}
		}
		
		$titulo = '�rea de Formaci�n Profesional';
		$esOrgano = 1;
	}
	
}
else {
	$departamento = $dpto;
	$titulo = 'Departamento de '.$departamento;
}

// REGISTRAMOS EL ACTA
if (isset($_POST['guardar'])) {
	
	$input_jefe_departamento = mysqli_real_escape_string($db_con, $_POST['jefe_departamento']);
	$input_fecha_reunion = mysqli_real_escape_string($db_con, $_POST['fecha_reunion']);
	$exp_input_fecha_reunion = explode('-', $input_fecha_reunion);
	$fecha_reunion_sql = $exp_input_fecha_reunion[2].'-'.$exp_input_fecha_reunion[1].'-'.$exp_input_fecha_reunion[0];
	$input_num_acta = mysqli_real_escape_string($db_con, $_POST['num_acta']);
	$input_texto_acta = mysqli_real_escape_string($db_con, $_POST['texto_acta']);
	$fecha_hoy = date('Y-m-d H:i:s');
	
	$result = mysqli_query($db_con, "INSERT INTO r_departamento (contenido, jefedep, timestamp, DEPARTAMENTO, fecha, impreso, numero) VALUES ('$input_texto_acta', '$input_jefe_departamento', '$fecha_hoy', '$departamento', '$fecha_reunion_sql', 0, $input_num_acta)");
	if (! $result) $msg_error = "Ha ocurrido un error al registrar el acta. Error: ".mysqli_error($db_con);
	else $msg_success = "El acta ha sido registrada correctamente";
}

if (isset($_GET['edit_id'])) {
	
	$id_acta = mysqli_real_escape_string($db_con, $_GET['edit_id']);
	
	$result = mysqli_query($db_con, "SELECT * FROM r_departamento WHERE id = $id_acta");
	$row = mysqli_fetch_array($result);
	$departamento = $row['DEPARTAMENTO'];
	$jefe_departamento = $row['jefedep'];
	$fecha_reunion_sql = $row['fecha'];
	$exp_fecha_reunion = explode('-', $fecha_reunion_sql);
	$fecha_reunion = $exp_fecha_reunion[2].'-'.$exp_fecha_reunion[1].'-'.$exp_fecha_reunion[0];
	$numero_acta = $row['numero'];
	$texto_acta = $row['contenido'];
}

// ACTUALIZAMOS EL ACTA
if (isset($_POST['actualizar'])) {
	
	$id_acta = mysqli_real_escape_string($db_con, $_POST['id_acta']);
	$jefe_departamento = mysqli_real_escape_string($db_con, $_POST['jefe_departamento']);
	$fecha_reunion = mysqli_real_escape_string($db_con, $_POST['fecha_reunion']);
	$exp_fecha_reunion = explode('-', $fecha_reunion);
	$fecha_reunion_sql = $exp_fecha_reunion[2].'-'.$exp_fecha_reunion[1].'-'.$exp_fecha_reunion[0];
	$numero_acta = mysqli_real_escape_string($db_con, $_POST['num_acta']);
	$texto_acta = $_POST['texto_acta'];
	$fecha_hoy = date('Y-m-d H:i:s');
	
	$result = mysqli_query($db_con, "UPDATE r_departamento SET contenido = '$texto_acta', fecha = '$fecha_reunion_sql', numero = $numero_acta WHERE id = $id_acta");
	if (! $result) $msg_error = "Ha ocurrido un error al registrar el acta. Error: ".mysqli_error($db_con);
	else $msg_success = "El acta ha sido registrada correctamente";
}

// ELIMINAR ACTA
if (isset($_GET['eliminar_id'])) {
	
	$eliminar_id = mysqli_real_escape_string($db_con, $_GET['eliminar_id']);
	
	$result = mysqli_query($db_con, "DELETE FROM r_departamento WHERE id = $eliminar_id");
	if (! $result) $msg_error = "Ha ocurrido un error al eliminar el acta. Error: ".mysqli_error($db_con);
	else $msg_success = "El acta ha sido eliminada correctamente.";
}
	

// URI m�dulo
if (isset($_GET['organo'])) {
	$uri = 'index.php?organo='.$_GET['organo'].'&amp;';
}
else {
	$uri = 'index.php?';
}

// PLUGINS
$PLUGIN_DATATABLES = 1;

include ("../../menu.php");
include ("menu.php");
?>

<div class="container">
	
	<form method="post" action="">
	
		<div class="page-header">
			<h2>Actas de departamentos <small>Registrar acta</small></h2>
			
			<?php if (acl_permiso($_SESSION['cargo'], array('1'))): ?>
			<div class="row">
				<div class="col-md-6 col-lg-5">
					<select class="form-control input-lg text-info" id="departamento" name="departamento" onchange="submit()">
						<option value=""></option>
						<optgroup label="�rganos del centro">
							<?php foreach($organos as $organo): ?>
							<option value="<?php echo $organo; ?>"<?php echo (isset($departamento) && $departamento == $organo) ? ' selected' : ''; ?>><?php echo $organo; ?></option>
							<?php endforeach; ?>
						</optgroup>
						<optgroup label="Departamentos del centro">
							<?php $result = mysqli_query($db_con, "SELECT DISTINCT departamento FROM departamentos WHERE departamento <> '' AND departamento <> 'Admin' AND departamento <> 'Administracion' AND departamento <> 'Conserjeria' ORDER BY departamento ASC"); ?>
							<?php while ($row = mysqli_fetch_array($result)): ?>
							<option value="<?php echo $row['departamento']; ?>"<?php echo (isset($departamento) && $departamento == $row['departamento']) ? ' selected' : ''; ?>><?php echo $row['departamento']; ?></option>
							<?php endwhile; ?>
						</optgroup>
					</select>
				</div><!-- /.col-sm-6 -->
			</div><!-- /.row -->
			<?php elseif (isset($organo) && in_array($organo, $organos)): ?>
			<h3 class="text-info"><?php echo $departamento; ?></h3>
			<?php else: ?>
			<h3 class="text-info"><?php echo $departamento; ?></h3>
			<?php endif; ?>
		
		</div><!-- /.page-header -->
		
		<?php if (isset($msg_error)): ?>
		<div class="alert alert-danger">
			<?php echo $msg_error; ?>
		</div>
		<?php endif; ?>
		
		<?php if (isset($msg_alerta)): ?>
		<div class="alert alert-warning">
			<?php echo $msg_alerta; ?>
		</div>
		<?php endif; ?>
		
		<?php if (isset($msg_success)): ?>
		<div class="alert alert-success">
			<?php echo $msg_success; ?>
		</div>
		<?php endif; ?>
		
		<div class="row">
			
			<?php if (isset($departmento) || $departamento == '' || $departamento == 'Admin'): ?>
			<div class="col-sm-12">
				<br><br><br>
				<p class="lead text-muted text-center">Seleccione un departamento</p>
				<br><br><br><br>
			</div>
			<?php else: ?>
			<div class="col-md-9">
			
				<div class="well">
					
					<?php
					// RECOLECTAMOS DATOS PARA RELLENAR EL ACTA
					
					if (! isset($id_acta)) {
						// Jefe de departamento
						if (! isset($jefe_departamento)) {
							$result = mysqli_query($db_con, "SELECT nombre FROM departamentos WHERE departamento = '$departamento' AND cargo LIKE '%4%'");
							$row = mysqli_fetch_row($result);
							$jefe_departamento = $row[0];
							mysqli_free_result($result);
						}
						
						// N�mero �ltima acta
						$result = mysqli_query($db_con, "SELECT MAX(numero) FROM r_departamento WHERE departamento = '$departamento'");
						$row = mysqli_fetch_row($result);
						$numero_acta = $row[0] + 1;
						mysqli_free_result($result);
						
						// Hora reuni�n
						$result = mysqli_query($db_con, "SELECT hora, hora_inicio FROM tramos WHERE tramos.hora = (SELECT horw.hora FROM horw WHERE prof = '$jefe_departamento' AND c_asig = '51')");
						if (mysqli_num_rows($result)) {
							$row = mysqli_fetch_array($result);
							$hora_reunion = $row['hora_inicio'];
							$hora_fin_reunion = $row['hora_inicio'] + 1;
							mysqli_free_result($result);
						}
						else {
							$hora_reunion = date('H:i');
							$hora_fin_reunion = strtotime('+1 hour', strtotime(date('H:i')));
							$hora_fin_reunion = date('H:i', $hora_fin_reunion);
						}
					}
					
					if (acl_permiso($_SESSION['cargo'], array('1')) || nomprofesor($jefe_departamento) == nomprofesor($pr)) $bloquea_campos = 0;
					else $bloquea_campos = 1;
					?>
					
					<fieldset>
						<legend>Registrar acta</legend>
						
						<div class="row">
							
							<div class="col-sm-6">
								
								<div class="form-group">
									<label for="jefe_departamento"><?php echo ($esOrgano) ? 'Secretario / Coordinador' : 'Jefe del departamento'; ?></label>
									<input type="text" class="form-control" id="jefe_departamento" name="jefe_departamento" value="<?php echo $jefe_departamento; ?>" <?php echo ($bloquea_campos) ? 'disabled' : 'readonly'; ?>>
								</div>
								
							</div><!-- /.col-sm-6 -->
							
							<div class="col-sm-4">
								
								<div class="form-group" id="datetimepicker1">
									<label for="fecha_reunion">Fecha de la reuni�n</label>
									<div class="input-group">
										<input type="text" class="form-control" id="fecha_reunion" name="fecha_reunion" value="<?php echo (isset($fecha_reunion)) ? $fecha_reunion : date('d-m-Y'); ?>" data-date-format="DD-MM-YYYY" <?php echo ($bloquea_campos) ? 'disabled' : 'required'; ?>>
										<span class="input-group-addon"><span class="fa fa-calendar"></span></span>
									</div>
								</div>
								
							</div><!-- /.col-sm-4 -->
							
							<div class="col-sm-2">
								
								<div class="form-group">
									<label for="num_acta">N� Acta</label>
									<input type="text" class="form-control" id="num_acta" name="num_acta" value="<?php echo ($numero_acta > 1) ? $numero_acta : '1'; ?>" <?php echo ($bloquea_campos) ? 'disabled' : 'required'; ?>>
								</div>
								
							</div><!-- /.col-sm-2 -->
						
						</div><!-- /.row -->
						
<?php
$html_textarea = "<p>".$titulo."</p>
<p>".$config['centro_denominacion']." (".$config['centro_localidad'].")</p>
<p>Curso escolar: ".$config['curso_actual']."</p>
<p>Acta n� NUMERO_ACTA</p>
<p><br></p>
<p style=\"text-align: center;\"><strong style=\"text-decoration: underline;\">ACTA DE REUNI�N DEL ".mb_strtoupper($titulo, 'ISO-8859-1')."</strong></p>
<p><br></p>
<p>En ".$config['centro_localidad'].", a las ".$hora_reunion." horas del FECHA_DE_LA_REUNION, se re&uacute;ne el ".$titulo." del ".$config['centro_denominacion']." de ".$config['centro_localidad'].", con el siguiente <span style=\"text-decoration: underline;\">orden del d&iacute;a</span>:</p>
<p><br></p>
<p><br></p>
<p><br></p>
<p><br></p>
<p><u>Profesores Asistentes:</u></p>
<p><br></p>
<p><br></p>
<p><u>Profesores Ausentes:</u></p>
<p><br></p>
<p><br></p>
<p><br></p>
<p>Sin m�s asuntos que tratar, se levanta la sesi�n a las ".$hora_fin_reunion." horas.</p>
<p><br></p>
<p><br></p>
<p><br></p>
<p>Fdo.: ".$jefe_departamento."</p>";
?>
						
						
						<div class="form-group">
							<label for="texto_acta">Acta</label>
							<textarea class="form-control" id="texto_acta" name="texto_acta" rows="20" <?php echo ($bloquea_campos) ? 'disabled' : 'required'; ?>><?php echo (isset($texto_acta)) ? $texto_acta : $html_textarea; ?></textarea>
						</div>
					
					</fieldset>
					
					<?php if (! isset($id_acta)): ?>
					<button class="btn btn-primary" id="guardar" name="guardar"<?php echo ($bloquea_campos) ? ' disabled' : ''; ?>>Registrar acta</button>
					<?php else: ?>
					<input type="hidden" name="id_acta" value="<?php echo $id_acta; ?>">
					<button class="btn btn-primary" id="actualizar" name="actualizar"<?php echo ($bloquea_campos) ? ' disabled' : ''; ?>>Actualizar acta</button>
					<a class="btn btn-default" href="<?php echo $uri; ?>">Registrar nueva acta</a>
					<?php endif; ?>
				</div>
			
			</div>
			
			<div class="col-md-3">
				
				<?php $result = mysqli_query($db_con, "SELECT id, fecha, numero, impreso FROM r_departamento WHERE departamento = '$departamento' ORDER BY numero DESC"); ?>
				<?php if (mysqli_num_rows($result)): ?>
				
				<table class="table table-bordered table-hover datatable">
					<thead>
						<th>Acta</th>
						<th>Opciones</th>
					</thead>
					<tbody>
						<?php while ($row = mysqli_fetch_array($result)): ?>
						<tr>
							<td>
								<?php echo strftime('%d-%m-%Y', strtotime($row['fecha'])); ?><br />
								<small class="text-muted">Acta n� <?php echo $row['numero']; ?></small>
							</td>
							<td>
								<?php if ($bloquea_campos): ?>
								<a href="pdf.php?id=<?php echo $row['id']; ?>" target="_blank" data-bs="tooltip" title="Ver acta"><span class="fa fa-eye fa-fw fa-lg"></span></a>
								<?php else: ?>
								<?php if (! $row['impreso']): ?>
								<a href="<?php echo $uri; ?>edit_id=<?php echo $row['id']; ?>" data-bs="tooltip" title="Editar acta"><span class="fa fa-edit fa-fw fa-lg"></span></a>
								<a href="pdf.php?id=<?php echo $row['id']; ?>&amp;imprimir=1" target="_blank" data-bs="tooltip" title="Imprimir acta"><span class="fa fa-print fa-fw fa-lg"></span></a>
								<a href="<?php echo $uri; ?>eliminar_id=<?php echo $row['id']; ?>" data-bs="tooltip" title="Eliminar acta" data-bb="confirm-delete"><span class="fa fa-trash-o fa-fw fa-lg"></span></a>
								<?php else: ?>
								<a href="pdf.php?id=<?php echo $row['id']; ?>" target="_blank" data-bs="tooltip" title="Ver acta"><span class="fa fa-eye fa-fw fa-lg"></span></a>
								<?php endif; ?>
								
								<?php endif; ?>
							</td>
						</tr>
						<?php endwhile; ?>
					</tbody>
				</table>

				<?php else: ?>
				<p class="lead text-muted text-center">No se ha registrado ning�n acta en este departamento.</p>
				<?php endif; ?>
						
			</div>
			<?php endif; ?>
		
		</div><!-- /.row -->
	
	</form>
</div>

<?php include("../../pie.php"); ?>

<script>  
	$(function ()  
	{ 
		$('#datetimepicker1').datetimepicker({
			language: 'es',
			pickTime: false
		})
	});  

	$(document).ready(function() {
	
		// EDITOR DE TEXTO
		$('#texto_acta').summernote({
			height: 400,
			lang: 'es-ES'
		});
		
		
		var numacta = 'n� NUMERO_ACTA';
		var fecha = 'FECHA_DE_LA_REUNION';
		
		function reemplazar_numacta() {
			texto = $(".note-editable").html();
			valor_numacta = "n� "+$('#num_acta').val();
		    texto = texto.replace(numacta, valor_numacta);
		    numacta = valor_numacta;
		    $(".note-editable").html(texto).text();
		}
		
		function reemplazar_fecha() {
			texto = $(".note-editable").html();
			valor_fecha = $('#fecha_reunion').val();
			formateo_fecha = moment(valor_fecha, 'DD-MM-YYYY', true).format('LL')
		    texto = texto.replace(fecha, formateo_fecha);
		    fecha = formateo_fecha;
		    $(".note-editable").html(texto).text();
		}
		
		reemplazar_numacta();
		reemplazar_fecha();
		
		$("#num_acta").keyup(function() {
			reemplazar_numacta();
		});
		
		$("#datetimepicker1").on("dp.change", function (e) {
			reemplazar_fecha();
        });
		
		<?php if ($bloquea_campos): ?>
		$('#texto_acta').summernote('disable');
		<?php endif; ?>
		
		var table = $('.datatable').DataTable({
			"paging":   true,
		    "ordering": false,
		    "info":     false,
		    "searching":   false,	    
	
			"lengthMenu": [[15, 35, 50, -1], [15, 35, 50, "Todos"]],
				
			"language": {
	            "lengthMenu": "_MENU_",
	            "zeroRecords": "Sin resultados.",
	            "info": "P�gina _PAGE_ de _PAGES_",
	            "infoEmpty": "No hay resultados disponibles.",
	            "infoFiltered": "(filtrado de _MAX_ resultados)",
	            "search": "",
	            "paginate": {
	                  "first": "Primera",
	                  "next": "�ltima",
	                  "next": "",
	                  "previous": ""
	                }
	        }
		});
		
	});
	</script>
	
</body>
</html>
