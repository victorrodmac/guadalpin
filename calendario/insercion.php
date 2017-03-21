<?php
require('../bootstrap.php');
if (file_exists('../config.php')) {
  include('../config.php');
}

if ($_POST["imprimir"] == 1)
{
	$insercion = 1;
	include("../admin/actividades/imprimir.php");
}
$bloqueo = 0;


if ($_POST["borrar"] == 1)
{
	$cons = "delete from calendario_profesores WHERE idcalendario='".$_POST['id']."' ";
	//echo $cons;
	mysqli_query($db_con, $cons);
	$cons = "delete from calendario_alumnos WHERE idcalendario='".$_POST['id']."' ";
	//echo $cons;
	mysqli_query($db_con, $cons);
	$cons = "delete from calendario_asignaturas WHERE idcalendario='".$_POST['id']."' ";
	//echo $cons;
	mysqli_query($db_con, $cons);
	$cons = "delete from notas_cuaderno WHERE idcalendario='".$_POST['id']."' ";
	//echo $cons;
	mysqli_query($db_con, $cons);
	$cons = "delete from calendario WHERE id='".$_POST['id']."' ";
	//echo $cons;
	mysqli_query($db_con, $cons);
	$cons = "SELECT nombre from calendario WHERE id='".$_POST['id']."' ";
	//echo $cons;
	mysqli_query($db_con, $cons);
	if (mysqli_num_rows($result)>0) 
	{
		header('Location:'.'http://'.$config['dominio'].'/'.$config['path'].'/calendario/index.php?mes='.$_POST['mes'].'&anio='.$_POST['anio'].'&msg=ErrorEliminarEvento');
	}
	else
	{
		header('Location:'.'http://'.$config['dominio'].'/'.$config['path'].'/calendario/index.php?mes='.$_POST['mes'].'&anio='.$_POST['anio'].'');
	}
	exit();
}
else
{
	if ($_POST["grabar"] != 1)
	{
		include("../menu.php");
	}

	if (isset($_POST['cmp_nombre'])) {$nombre = trim(mysqli_real_escape_string($db_con,$_POST['cmp_nombre']));}
	if (isset($_POST['cmp_fecha_diacomp'])) {$fecha_diacomp = mysqli_real_escape_string($db_con,$_POST['cmp_fecha_diacomp']);}
	if (isset($_POST['cmp_fecha_ini'])) {$fecha_ini = trim(mysqli_real_escape_string($db_con,$_POST['cmp_fecha_ini']));}else{$fecha_ini=date('d/m/Y');}
	if (isset($_POST['cmp_hora_ini'])) {$hora_ini = trim(mysqli_real_escape_string($db_con,$_POST['cmp_hora_ini']));}else{$hora_ini=date('H:i');}
	if (isset($_POST['cmp_fecha_fin'])) {$fecha_fin = trim(mysqli_real_escape_string($db_con,$_POST['cmp_fecha_fin']));}else{$fecha_fin=date('d/m/Y');}
	if (isset($_POST['cmp_hora_fin'])) {$hora_fin = trim(mysqli_real_escape_string($db_con,$_POST['cmp_hora_fin']));}else{$hora_fin=date('H:i', strtotime('+1 hour', strtotime(date('H:i'))));}
	if (isset($_POST['cmp_descripcion'])) {$descripcion = trim(mysqli_real_escape_string($db_con,$_POST['cmp_descripcion']));}
	if (isset($_POST['cmp_lugar'])) {$lugar = trim(mysqli_real_escape_string($db_con,$_POST['cmp_lugar']));}
	if (isset($_GET['cmp_calendario'])){$calendario = $_GET['cmp_calendario'];}else{if (isset($_POST['cmp_calendario'])) {$calendario = mysqli_real_escape_string($db_con, $_POST['cmp_calendario']);}}
	if ($calendario == "")
	{
		$calendario = $_POST['cmp_calendario_editar'];
	}
	if (isset($_POST['cmp_unidad_asignatura'])) {$unidad_asignatura = $_POST['cmp_unidad_asignatura'];}
	if (isset($_GET['cmp_cuaderno'])){$cuaderno = $_GET['cmp_cuaderno'];}else{if (isset($_POST['cmp_cuaderno'])) {$cuaderno = $_POST['cmp_cuaderno'];}else{$cuaderno = 0;}}
	if (isset($_POST['cmp_departamento'])) {$departamento = $_POST['cmp_departamento'];}
	if (isset($_POST['cmp_profesores'])) {$profesores = $_POST['cmp_profesores'];}
	if (isset($_POST['cmp_observaciones'])) {$Observaciones = trim(mysqli_real_escape_string($db_con,$_POST['cmp_observaciones']));}
	if (isset($_POST['cmp_unidades'])) {$unidades = $_POST['cmp_unidades'];}
	if (isset($_POST['cmp_alumnos'])) {$alumnos = $_POST['cmp_alumnos'];}
	if (isset($_POST['cmp_todos_alumnos'])) {$todos_alumnos = mysqli_real_escape_string($db_con,$_POST['cmp_todos_alumnos']);}
	if (isset($_GET['dia'])){$dia = $_GET['dia'];}else{if (isset($_POST['dia'])){$dia = $_POST['dia'];}else{$dia = date('d');}}
	if (isset($_GET['mes'])){$mes = $_GET['mes'];}else{if (isset($_POST['mes'])){$mes = $_POST['mes'];}else{$mes = date('n');}}
	if (isset($_GET['anio'])){$anio = $_GET['anio'];}else{if (isset($_POST['anio'])){$anio = $_POST['anio'];}else{$anio = date('Y');}}
	if (isset($_GET['id'])){$id = $_GET['id'];}else{if (isset($_POST['id'])){$id = $_POST['id'];}}


	// Limpiamos variables
	/*echo "grabar".$_POST["grabar"];
	echo "nombre".$_POST['cmp_nombre'];
	echo "cmp_fecha_diacomp".isset($_POST['cmp_fecha_diacomp']);
	echo "<br>cmp_fecha_ini".$_POST['cmp_fecha_ini'];
	echo "cmp_hora_ini".$_POST['cmp_hora_ini'];
	echo "cmp_fecha_fin".$_POST['cmp_fecha_fin'];
	echo "cmp_hora_fin".$_POST['cmp_hora_fin'];
	echo "cmp_descripcion".$_POST['cmp_descripcion'];
	echo "cmp_lugar".$_POST['cmp_lugar'];
	echo "cmp_unidad_asignatura".$_POST['cmp_unidad_asignatura'];
	echo "cmp_cuaderno".$_POST['cmp_cuaderno'];
	echo "cmp_calendario".$_POST['cmp_calendario'];
	echo "cmp_departamento".$_POST['cmp_departamento'];
	echo "cmp_profesores".$_POST['cmp_profesores'];
	echo "cmp_observaciones".$_POST['cmp_observaciones'];
	echo "cmp_unidades".$_POST['cmp_unidades'];
	echo "cmp_alumnos".$_POST['cmp_alumnos'];
	echo " dia ".$dia." mes ".$mes." anio ".$anio;
	echo " dpto".$departamento;
	echo "<br> cmp_fecha_diacomp".$fecha_diacomp;
	echo " cmp_todos_alumnos".$todos_alumnos;*/

	$profesorreg = mysqli_real_escape_string($db_con, $_SESSION['ide']);
	$fechareg = date('Y-m-d');

	// esta en modo edición
	if (($id != "") && ($nombre == ""))
	{
		// se bloquean los componentes salvo que se puedan editar por permisos
		$bloqueo = 1;
		if(stristr($_SESSION['cargo'],'1') == TRUE or stristr($_SESSION['cargo'],'5') == TRUE)
		{
			$bloqueo=0;
		}
		$cons = "SELECT nombre, fechaini, horaini, fechafin, horafin, descripcion, lugar, categoria, departamento, observaciones, confirmado, categoria, profesorreg FROM calendario WHERE id='$id' ";
		//echo $cons;
		$result = mysqli_query($db_con, $cons);
		$resultrow = mysqli_fetch_array($result); 

		$profesorreg = $resultrow[12];
		if ( $_SESSION['ide'] == $profesorreg)
		{
			$bloqueo = 0;
		}					

		$nombre = $resultrow[0];
		$exp_fechaini = explode('-', $resultrow[1]);
		$fecha_ini = $exp_fechaini[2].'/'.$exp_fechaini[1].'/'.$exp_fechaini[0];
		$hora_ini = $resultrow[2];
		$exp_fechafin = explode('-', $resultrow[3]);
		$fecha_fin = $exp_fechafin[2].'/'.$exp_fechafin[1].'/'.$exp_fechafin[0];
		$hora_fin = $resultrow[4];
		if (($hora_ini == '00:00:00') && ($hora_fin == '00:00:00'))
			$fecha_diacomp = "si";
		else
			$fecha_diacomp = "";
		$descripcion = $resultrow[5];
		$lugar = $resultrow[6];
		$calendario = $resultrow[7];
		$departamento = $resultrow[8];
		$Observaciones = $resultrow[9];
		$todos_alumnos = "";
		
		if ($resultrow[11] != 2 && $resultrow[11] != 1) 
		{
			
			$cons3 = "SELECT asignaturas.nombre, unidad from calendario_asignaturas, asignaturas WHERE calendario_asignaturas.codasig = asignaturas.CODIGO and idcalendario='$id' "; 
			//echo $cons3;
			$result3 = mysqli_query($db_con, $cons3);
			$unidad_asignatura=array();
			while( $resultrow3 = mysqli_fetch_array($result3))
			{
				$unidad_asignatura[] = $resultrow3[1]." => ".$resultrow3[0];
			} 
			$cons3b = "SELECT idcalendario from notas_cuaderno WHERE idcalendario='$id' "; 
			//echo $cons3b;
			$result3b = mysqli_query($db_con, $cons3b);
			if (mysqli_num_rows($result3b)) 
			{
				$cuaderno = 1;
			}
			else
			{
				$cuaderno = 0;
			}
		} 
		if ($resultrow[11] == 2 )
		{
			$cons4 = "SELECT calendario_alumnos.claveal, unidad from calendario_alumnos, alma WHERE alma.claveal = calendario_alumnos.claveal and idcalendario='$id' order by unidad, calendario_alumnos.claveal";
			//echo $cons4;
			$result4 = mysqli_query($db_con, $cons4);
			$alumnos=array();
			$unidades=array();
			$uni_ant = "";
			while( $resultrow4 = mysqli_fetch_array($result4))
			{
				if ($uni_ant != $resultrow4[1])
				{
					$uni_ant = $resultrow4[1];
					$unidades[] = $resultrow4[1];
				}
				$alumnos[] = $resultrow4[1]." => ".$resultrow4[0];
			}
			$cons2 = "SELECT nombre from calendario_profesores WHERE idcalendario='$id' ";
			//echo $cons2;
			$result2 = mysqli_query($db_con, $cons2);
			$profesores=array();
			while( $resultrow2 = mysqli_fetch_array($result2))
			{
				$profesores[] = $resultrow2[0];
				if ($resultrow2[0] == $_SESSION["profi"])
					$bloqueo = 0;
			} 			
			$cons2 = "SELECT id from notas_cuaderno WHERE idcalendario='$id' ";
			//echo $cons2;
			$result2 = mysqli_query($db_con, $cons2);
			if (mysqli_num_rows($result2))
			{
				$cuaderno = 1;
			}
		}				

	}

	if ($_POST["grabar"] == 1)
	{
		if (! isset($_POST['cmp_nombre'])) {
			die("<h1>Falta campo nombre</h1>");
			exit();
		}
		

		if ($fecha_diacomp == '') $fecha_diacomp = 0;
		else $fecha_diacomp = 1;

		$exp_fechaini = explode('/', $fecha_ini);
		$fechaini_sql = $exp_fechaini[2].'-'.$exp_fechaini[1].'-'.$exp_fechaini[0];
		
		$exp_fechafin = explode('/', $fecha_fin);
		$fechafin_sql = $exp_fechafin[2].'-'.$exp_fechafin[1].'-'.$exp_fechafin[0];
		if ($fecha_diacomp) {
			$hora_ini = '00:00:00';
			$hora_fin = '00:00:00';
		}
		$string_departamento = "";
		if ($calendario == 2)
		{
			$string_departamento = $departamento;
		}

		// Pertenece al diario del profesor
		//echo "<br>cuaderno".$cuaderno;

		if ($cuaderno == '') $cuaderno = 0;
			else $cuaderno = 1;

		if (($contar2 > 0 ) && ($contar3 == 0))
		{
			header('Location:'.'http://'.$config['dominio'].'/'.$config['path'].'/calendario/index.php?mes='.$mes.'&anio='.$anio.'&msg=ErrorSinAlumnos');
			exit();
		}
		else
		{
			$extra = "";
			if ($id != "")
				$extra = " AND id <> ".$id;
			// Comprobamos si existe el evento
			$cons = "SELECT fechareg FROM calendario WHERE nombre='$nombre' AND fechaini='$fechaini_sql' AND horaini='$hora_ini' AND fechafin='$fechafin_sql' AND horafin='$hora_fin' AND categoria='$calendario'".$extra." LIMIT 1";
			//echo $cons;
			$result = mysqli_query($db_con, $cons);

			if (mysqli_num_rows($result)) {
				header('Location:'.'http://'.$config['dominio'].'/'.$config['path'].'/calendario/index.php?mes='.$mes.'&anio='.$anio.'&msg=ErrorEventoExiste');
				exit();
			}
			else {
				if ($fechaini_sql > $fechafin_sql) {
					header('Location:'.'http://'.$config['dominio'].'/'.$config['path'].'/calendario/index.php?mes='.$mes.'&anio='.$anio.'&msg=ErrorEventoFecha');
					exit();
				}
				else {
					if ($id != "")
					{
						$cons = "UPDATE calendario set categoria = $calendario, nombre = '$nombre', descripcion = '$descripcion', fechaini = '$fechaini_sql', horaini = '$hora_ini', fechafin = '$fechafin_sql', horafin = '$hora_fin', lugar = '$lugar', departamento = '$string_departamento', fechareg = '$fechareg', profesorreg = '$profesorreg', observaciones = '$observaciones', confirmado = 0 where id = $id";
						//echo $cons;
						$crear = mysqli_query($db_con, $cons);
						if (! $crear) {
							header('Location:'.'http://'.$config['dominio'].'/'.$config['path'].'/calendario/index.php?mes='.$mes.'&anio='.$anio.'&msg=ErrorEventoEdicion');
							exit();
						}
						else
						{
							$consdalumnos = "delete from calendario_alumnos where idcalendario = ".$id;
							//echo $consdalumnos;
							mysqli_query($db_con, $consdalumnos);
							$consdprofesores = "delete from calendario_profesores where idcalendario = ".$id;
							//echo $consdprofesores;
							mysqli_query($db_con, $consdprofesores);
							$consdasignaturas = "delete from calendario_asignaturas where idcalendario = ".$id;
							//echo $consdasignaturas;
							mysqli_query($db_con, $consdasignaturas);
							$consdnotascuad = "delete from notas_cuaderno where idcalendario = ".$id;
							//echo $consdnotascuad;
							mysqli_query($db_con, $consdnotascuad);
							$cons_prof = "insert into calendario_profesores values ";
							$hay = 0;
							if (is_array($profesores)) {
								foreach ($profesores as $profesor) {
									if ($hay == 1)
										$cons_prof .= ",";
									$cons_prof .= "('".$id."','".mysqli_real_escape_string($db_con, $profesor)."')";
									$hay = 1;
								}						
							}
							//echo "prof".$cons_prof;
							if ($hay > 0)
								mysqli_query($db_con, $cons_prof);
							$cons_alum = "insert into calendario_alumnos (idcalendario, claveal) values ";
							$hay = 0;
							if (is_array($alumnos)) {
				
								foreach ($alumnos as $alumno) {
									if ($hay == 1)
										$cons_alum .= ",";
									$alumarr = explode("=>",mysqli_real_escape_string($db_con, $alumno));
									$cons_alum .= "('".$id."','".trim($alumarr[1])."') ";
									$hay = 1;
								}
							}
							//echo "alum".$cons_alum;
							// para no insertar en el caso de que no haya alumnos seleccionados
							if ($hay == 1 )
								mysqli_query($db_con, $cons_alum);
							if ($calendario != 1 && $calendario != 2 ) {
							
								$cons_asig = "insert into calendario_asignaturas (idcalendario, codasig, unidad) values ";
								$cons_ncuad = "INSERT INTO notas_cuaderno (profesor, fecha, nombre, texto , asignatura, curso, orden, visible_nota, Tipo, color, texto_pond, idcalendario) VALUES ";
								$tipo="Números";
								$hay = 0;

								foreach ($unidad_asignatura as $unidad) {
									if ($hay == 1)
									{
										$cons_asig .= ",";
										$cons_ncuad .= ",";
									}
									$exp_unidad = explode(' => ', $unidad);
									
									// Las siguientes variables sirven para obtener el código de la asignatura
									$unidad = mysqli_real_escape_string($db_con, $exp_unidad[0]);
									$nomasignatura = mysqli_real_escape_string($db_con, $exp_unidad[1]); 
									// Códigos diferentes en Bachillerato
									//echo "unidad".$unidad."nomasignatura".$nomasignatura;
									$extra_unidad.="a_grupo='$unidad' or ";
									$hay = 1;
									if ($cuaderno == 1)
									{
										$cons2 = "SELECT MAX(orden) FROM notas_cuaderno WHERE profesor = '".$_SESSION['profi']."' AND curso='$unidad' AND asignatura='$nomasignatura'";
										//echo $cons2;
										$result_columnas = mysqli_query($db_con, $cons);
										$numcolumna = mysqli_fetch_array($result_columnas);
										$orden = $numcolumna[0] + 1;
									}
									
									$cons = "SELECT DISTINCT c_asig FROM horw WHERE prof='".$_SESSION['profi']."' AND a_grupo = '$unidad' AND asig='$nomasignatura'";
									//echo $cons;
										
									$result_asignatura = mysqli_query($db_con, $cons);
									while($codasignatura = mysqli_fetch_array($result_asignatura)){					
										$cons_asig .= "('".$id."','".trim($codasignatura[0])."','".trim($unidad)."') ";
										$cons_ncuad .= " ('".$_SESSION['profi']."', '$fechareg', '$nombre', '$descripcion', '$codasignatura[0]', '$unidad', '$orden', '0', '$tipo', '#FFFFFF','','".$id."')";
									}

								}
									
								//echo "asig".$cons_asig;
								//echo "cuad".$cons_ncuad;
								if ($hay > 0)
								{
									mysqli_query($db_con, $cons_asig);
									if ($cuaderno == 1)
										mysqli_query($db_con, $cons_ncuad);
								}								
							}
							if ($contar2 != $contar2b )
							{
								header('Location:'.'http://'.$config['dominio'].'/'.$config['path'].'/calendario/index.php?mes='.$mes.'&anio='.$anio.'&msg=ErrorEventoUnidad');
								exit();
							}
							else
							{
								header('Location:'.'http://'.$config['dominio'].'/'.$config['path'].'/calendario/index.php?mes='.$mes.'&anio='.$anio.'');
								exit();
							}
						}
					}
					else
					{
						$cons = "INSERT INTO calendario (categoria, nombre, descripcion, fechaini, horaini, fechafin, horafin, lugar, departamento, fechareg, profesorreg, observaciones, confirmado) VALUES ($calendario, '$nombre', '$descripcion', '$fechaini_sql', '$hora_ini', '$fechafin_sql', '$hora_fin', '$lugar', '$string_departamento', '$fechareg', '$profesorreg', '$observaciones',0)";
						//echo $cons;
						$crear = mysqli_query($db_con, $cons);
						if (! $crear) {
							header('Location:'.'http://'.$config['dominio'].'/'.$config['path'].'/calendario/index.php?mes='.$mes.'&anio='.$anio.'&msg=ErrorEventoInsertar');
							exit();
						}
						else {
							$cons = "SELECT id FROM calendario WHERE nombre='$nombre' AND fechaini='$fechaini_sql' AND horaini='$hora_ini' AND fechafin='$fechafin_sql' AND horafin='$hora_fin' AND categoria='$calendario' LIMIT 1";
							//echo $cons;
							$result = mysqli_query($db_con, $cons);
							$resultrow = mysqli_fetch_array($result); 
							$idnuevo = $resultrow[0];
							$cons_prof = "insert into calendario_profesores values ";
							$hay = 0;
							if (is_array($profesores)) {
								foreach ($profesores as $profesor) {
									if ($hay == 1)
										$cons_prof .= ",";
									$cons_prof .= "('".$idnuevo."','".mysqli_real_escape_string($db_con, $profesor)."')";
									$hay = 1;
								}						
							}
							//echo "prof".$cons_prof;
							// para no insertar en el caso de que no haya profesores seleccionados
							if ($hay == 1 )
								mysqli_query($db_con, $cons_prof);
							$cons_alum = "insert into calendario_alumnos (idcalendario, claveal) values ";
							$hay = 0;
							if (is_array($alumnos)) {
				
								foreach ($alumnos as $alumno) {
									if ($hay == 1)
										$cons_alum .= ",";
									$alumarr = explode("=>",mysqli_real_escape_string($db_con, $alumno));
									$cons_alum .= "('".$idnuevo."','".trim($alumarr[1])."') ";
									$hay = 1;
								}
							}
							//echo "alum".$cons_alum;
							// para no insertar en el caso de que no haya alumnos seleccionados
							if ($hay == 1 )
								mysqli_query($db_con, $cons_alum);
							// Comprobamos si el profesor ha marcado la opción de crear columna en el cuaderno
							if ($calendario != 1 && $calendario != 2 ) {
							
								$cons_asig = "insert into calendario_asignaturas (idcalendario, codasig, unidad) values ";
								$cons_ncuad = "INSERT INTO notas_cuaderno (profesor, fecha, nombre, texto , asignatura, curso, orden, visible_nota, Tipo, color, texto_pond, idcalendario) VALUES ";
								$tipo="Números";
								$hay = 0;

								foreach ($unidad_asignatura as $unidad) {
									if ($hay == 1)
									{
										$cons_asig .= ",";
										$cons_ncuad .= ",";
									}
									$exp_unidad = explode(' => ', $unidad);
									
									// Las siguientes variables sirven para obtener el código de la asignatura
									$unidad = mysqli_real_escape_string($db_con, $exp_unidad[0]);
									$nomasignatura = mysqli_real_escape_string($db_con, $exp_unidad[1]); 
									// Códigos diferentes en Bachillerato
									//echo "unidad".$unidad."nomasignatura".$nomasignatura;
									$extra_unidad.="a_grupo='$unidad' or ";
									$hay = 1;
									if ($cuaderno == 1)
									{
										$cons2 = "SELECT MAX(orden) FROM notas_cuaderno WHERE profesor = '".$_SESSION['profi']."' AND curso='$unidad' AND asignatura='$nomasignatura'";
										//echo $cons2;
										$result_columnas = mysqli_query($db_con, $cons);
										$numcolumna = mysqli_fetch_array($result_columnas);
										$orden = $numcolumna[0] + 1;
									}
									
									$cons = "SELECT DISTINCT c_asig FROM horw WHERE prof='".$_SESSION['profi']."' AND a_grupo = '$unidad' AND asig='$nomasignatura'";
									//echo $cons;
										
									$result_asignatura = mysqli_query($db_con, $cons);
									while($codasignatura = mysqli_fetch_array($result_asignatura)){					
										$cons_asig .= "('".$idnuevo."','".trim($codasignatura[0])."','".trim($unidad)."') ";
										$cons_ncuad .= " ('".$_SESSION['profi']."', '$fechareg', '$nombre', '$descripcion', '$codasignatura[0]', '$unidad', '$orden', '0', '$tipo', '#FFFFFF','','".$idnuevo."')";
									}

								}
									
								//echo "asig".$cons_asig;
								//echo "cuad".$cons_ncuad;
								if ($hay > 0)
								{
									mysqli_query($db_con, $cons_asig);
									if ($cuaderno == 1)
										mysqli_query($db_con, $cons_ncuad);
								}								
							}
							
							if ($contar2 != $contar2b )
							{
								header('Location:'.'http://'.$config['dominio'].'/'.$config['path'].'/calendario/index.php?mes='.$mes.'&anio='.$anio.'&msg=ErrorEventoUnidad');
								exit();
							}
							else
							{
								header('Location:'.'http://'.$config['dominio'].'/'.$config['path'].'/calendario/index.php?mes='.$mes.'&anio='.$anio.'');
								exit();
							}
						}
					}
				}
			}
		}
	}
	else
	{

	?>
	<div class="container">
		
		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<h2><small><?php echo ( $id != "" ) ? 'Editar' : 'Nuevo'; ?> Evento o Actividad</small></h2>
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
				
			<div class="well">
				
				<form id="formNuevoEvento" method="post" action="insercion.php" data-toggle="validator">
	    
	        	<fieldset>
	        		
	        		<div class="form-group">
	        			<label for="cmp_nombre" class="visible-xs">Nombre</label>
	        			<input type="text" class="form-control" id="cmp_nombre" name="cmp_nombre" placeholder="Nombre del evento o actividad" maxlength="120" value="<?php echo $nombre; ?>" required autofocus>
	        		</div>
	        		
	        		
	    			<div class="row">
	    				<div class="col-xs-12">
		    				<div class="form-group">
			    					<label>
			    						<input type="checkbox" id="cmp_fecha_diacomp" name="cmp_fecha_diacomp" value="si" 
			    					<?php
			    						if ($fecha_diacomp == "si" )
			    						{
			    							echo " checked ";
			    						}
			    					?>
			    						>
			    						<strong>Todo el día</strong>
			    					</label>
		    				</div>
	    				</div>
	    			</div>
	    				
	    			<div class="row">	    				
	    				<div class="col-xs-6 col-sm-3">
	    					<div class="form-group datetimepicker1">
	        					<label for="cmp_fecha_ini">Fecha inicio</label>
	        					<div class="input-group">
		        					<input type="text" class="form-control" id="cmp_fecha_ini" name="cmp_fecha_ini" value="<?php echo $fecha_ini; ?>" data-date-format="DD/MM/YYYY" required>
		        					<span class="input-group-addon"><span class="fa fa-calendar">
		        				</div>
		        			</div>
	    				</div>
	        			<div class="col-xs-6 col-sm-3">
	        				<div class="form-group datetimepicker2">
		        				<label for="cmp_hora_ini">Hora inicio</label>
		        				<div class="input-group">
		        					<input type="text" class="form-control cmp_fecha_toggle" id="cmp_hora_ini" name="cmp_hora_ini" value="<?php echo $hora_ini; ?>" data-date-format="HH:mm">
		        					<span class="input-group-addon"><span class="fa fa-clock-o">
		        				</div>
		        			</div>
	        			</div>
	        			<div class="col-xs-6 col-sm-3">
	        				<div class="form-group datetimepicker3">
		        				<label for="cmp_fecha_fin">Fecha fin</label>
		        				<div class="input-group">
		        					<input type="text" class="form-control" id="cmp_fecha_fin" name="cmp_fecha_fin" value="<?php echo $fecha_fin; ?>" data-date-format="DD/MM/YYYY">
		        					<span class="input-group-addon"><span class="fa fa-calendar">
		        				</div>
		        			</div>
		        		</div>
	        			<div class="col-xs-6 col-sm-3">
	        				<div class="form-group datetimepicker4">
		        				<label for="cmp_hora_fin">Hora fin</label>
		        				<div class="input-group">
		        					<input type="text" class="form-control cmp_fecha_toggle" id="cmp_hora_fin" name="cmp_hora_fin" value="<?php echo $hora_fin; ?>" data-date-format="HH:mm">
		        					<span class="input-group-addon"><span class="fa fa-clock-o">
		        				</div>
		        			</div>
	        			</div>
	        		</div>
	    		
	        		<div class="form-group">
	        			<label for="cmp_descripcion">Descripción</label>
	        			<textarea type="text" class="form-control" id="cmp_descripcion" name="cmp_descripcion" rows="3"><?php echo $descripcion; ?></textarea>
	        		</div>
	        		
	        		<div class="form-group">
	        			<label for="cmp_lugar">Lugar</label>
	        			<input type="text" class="form-control" id="cmp_lugar" name="cmp_lugar" value="<?php echo $lugar; ?>" >
	        		</div>
	    		
	        		<div class="form-group">
	        			<label for="cmp_calendario">Calendario</label>
	        			<select class="form-control" id="cmp_calendario" name="cmp_calendario" required>
	        				<optgroup label="Mis calendarios">
	        					<?php 
	        						$result = mysqli_query($db_con, "SELECT id, nombre, color FROM calendario_categorias WHERE profesor='".$_SESSION['ide']."' AND espublico=0");
	        						while ($row = mysqli_fetch_assoc($result))
	        						{
			        					echo '<option value="'.$row["id"].'" ';
			        					if ($row["id"] == $calendario)
			        					{
			        						echo "selected ";
			        					}
			        					echo '>'.$row["nombre"].'</option>';

	        						}
	        						mysqli_free_result($result); 
	        					?>
	        				</optgroup>
	        				<optgroup label="Otros calendarios">
	        					<?php 
	        						$result = mysqli_query($db_con, "SELECT id, nombre, color FROM calendario_categorias WHERE espublico=1");
	        						while ($row = mysqli_fetch_assoc($result))
	        						{
			        					echo '<option value="'.$row["id"].'" ';
			        					if ($row["id"] == $calendario)
			        					{
			        						echo "selected ";
			        					}
			        					echo '>'.$row["nombre"].'</option>';

	        						}
	        						mysqli_free_result($result); 
	        					?>
	        				</optgroup>
	        				
	        			</select>
	        			<input type="hidden" id="cmp_calendario_editar" name="cmp_calendario_editar" value="<?php echo $calendario; ?>">
	        		</div>
	        		
	        		<div id="opciones_diario">
	        			<?php 
	        				$cons = "SELECT DISTINCT grupo, materia FROM profesores WHERE profesor='".$_SESSION['profi']."' order by materia, grupo";
	        				$result = mysqli_query($db_con, $cons);
	        				if (mysqli_num_rows($result))
	        				{ 
	        			?>
			        			<div class="form-group">
			        				<label for="cmp_unidad_asignatura">Unidad y asignatura</label>
			        				
			        				<select class="form-control" id="cmp_unidad_asignatura" name="cmp_unidad_asignatura[]" size="5" multiple>
			        				<?php while ($row = mysqli_fetch_array($result)): ?>
			        					<option value="<?php echo $row['grupo'].' => '.$row['materia']; ?>" <?php echo (isset($unidad_asignatura) && in_array($row['grupo'].' => '.$row['materia'], $unidad_asignatura)) ? 'selected' : ''; ?>><?php echo $row['grupo'].' ('.$row['materia'].')'; ?></option>
			        				<?php endwhile; ?>
			        				</select>
			        			</div>
		        			
			        		    <div class="form-group">
			        				<div class="checkbox">
			        					<label for="cmp_cuaderno">
			        						<input type="checkbox" id="cmp_cuaderno" name="cmp_cuaderno" value="1" <?php if( $cuaderno == 1) echo "checked "; ?>> Crear columna en mi cuaderno de notas<br>
			        						<small class="text-muted">Se creará una columna de tipo numérico y no visible para las familias. Puede modificar estos valores en el cuaderno de notas.</small><br>
			        						<small class="text-danger">Importante: Si la asignatura tiene desdoble de unidades, debe marcar todas las unidades afectadas. Deben ser del mismo curso y tener el mismo nombre de asignatura.</small>
			        					</label>
			        				</div>
			        			</div>
	        			<?php
	        				}
	        			?>
	        		</div>
	        		<div id="opciones_actividades" class="row">
	        			
	        			<div class="col-sm-4">
	        		
			        		<div class="form-group">
			        			<label for="cmp_departamento">Departamento que lo organiza</label>
			        			<select class="form-control" id="cmp_departamento" name="cmp_departamento">
			        			<?php 
				        			echo '<option value="Múltiples Departamentos">Múltiples Departamentos</option>';
				        			$sel = '';
				        			if ($departamento == "Actividades Extraescolares")
				        				$sel = ' selected ';
			        				echo '<option value="Actividades Extraescolares" '.$sel.'>Actividades Extraescolares</option>';
				        			$sel = '';
				        			if ($departamento == "Relaciones de Género")
				        				$sel = ' selected ';
			        				echo '<option value="Relaciones de Género" '.$sel.'>Relaciones de Género</option>';     	
			        				$result = mysqli_query($db_con, "SELECT DISTINCT departamento FROM departamentos WHERE departamento <> 'Admin' AND departamento <> 'Conserjeria' AND departamento <> 'Administracion' ORDER BY departamento ASC"); 
			        				while ($row = mysqli_fetch_assoc($result))
			        				{
			        					echo '<option value="'.$row['departamento'].'"';
			        					if ($row['departamento'] == $departamento)
			        					{
			        						echo " selected ";
			        					}
			        					echo '>'.$row['departamento'].'</option>'; 
			        				}
			        			?>
			        			</select>
			        		</div>
			        		
			        		<div class="form-group">
			        			<label for="cmp_profesores">Profesores que asistirán a la actividad</label>
			        			<select class="form-control" id="cmp_profesores" name="cmp_profesores[]" size="21" multiple>
				        			<?php 
				        				$result = mysqli_query($db_con, "SELECT DISTINCT departamento,nombre FROM departamentos WHERE departamento <> 'Admin' AND departamento <> 'Conserjeria' AND departamento <> 'Administracion' ORDER BY departamento, nombre ASC");
				        				$dpto_ant = "";
				        				while ($row = mysqli_fetch_assoc($result))
				        				{ 
				        					if ($row['departamento'] != $dpto_ant )
				        					{
				        						if ($dpto_ant != "")
				        						{
							        				echo "</optgroup>";
				        						}
				        						$dpto_ant = $row['departamento'];
						        				echo '<optgroup label="'.$row['departamento'].'">';
				        					}
				        					echo '<option value="'.$row['nombre'].'"';
											if (isset($profesores) && in_array($row['nombre'], $profesores))
				        					{
				        						echo ' selected '; 	
				        					} 			        				
				        					echo '>'.$row['nombre'].'</option>';
				        				}
				        				echo "</optgroup>";
				        			?>
			        			</select>
			        			<p class="help-block">Para seleccionar varios profesores, mantén apretada la tecla <kbd>Ctrl</kbd> mientras los vas marcando con el ratón.</p>
			        		</div>
			        		<div class="form-group">
				        		<label for="cmp_observaciones">Observaciones (Precio de la Actividad, Recomendaciones para la misma, etc.)</label>
		        				<textarea type="text" class="form-control" id="cmp_observaciones" name="cmp_observaciones" rows="3"><?php echo $observaciones;?></textarea>
			        		</div>
			        	</div><!-- /.col-sm-4 -->
			        	
			        	<div class="col-sm-4">
			        		
			        		<div class="form-group">
			        			<label for="">Unidades que asistirán a la actividad</label>
			        			<select class="form-control" id="cmp_unidades" name="cmp_unidades[]" size="21" onChange=submit() multiple>
			        			<?php 
			        				$result = mysqli_query($db_con, "SELECT DISTINCT curso, unidad FROM alma ORDER BY curso, unidad ASC"); 
			        				$curso_ant = "";
			        				while ($row = mysqli_fetch_assoc($result))
			        				{ 
			        					if ($row['curso'] != $curso_ant )
			        					{
					        				echo "</optgroup>";
			        						$curso_ant = $row['curso'];
					        				echo '<optgroup label="'.$row['curso'].'">';
			        					}
			        					echo '<option value="'.$row['unidad'].'"';
										if (isset($unidades) && in_array($row['unidad'], $unidades))
			        					{
			        						echo ' selected '; 	
			        					} 			        				
			        					echo '>'.$row['unidad'].'</option>';
			        				}
			        				echo "</optgroup>";
			        			?>
			        			</select>
			        			<p class="help-block">Para seleccionar varios grupos, mantén apretada la tecla <kbd>Ctrl</kbd> mientras los vas marcando con el ratón.</p>
			        		</div>			        		
			        	</div><!-- /.col-sm-4 -->

			        	<div class="col-sm-4">
			        		
			        		<div class="form-group" >
			        			<label for="">Alumnos que asistirán a la actividad</label>
			    				<div class="checkbox" id="divalumnoschk">
			    					<label>
			    						<input type="checkbox" id="cmp_todos_alumnos" name="cmp_todos_alumnos" value="si"
			    					<?php
			    						if ($todos_alumnos == "si" )
			    						{
			    							echo " checked ";
			    						}
			    					?>
			    						onchange="seleccionar()"> 
			    						<strong>Seleccionar todos los alumnos de los grupos seleccionados</strong>
			    					</label>
			    				</div>
			    				<div class="select" id="divalumnossel">		    				
			        			<?php 
			        				$query = "SELECT DISTINCT alma.claveal, alma.nombre, alma.apellidos, alma.unidad, curso FROM alma";
									if (is_array($unidades)) {
										$query .= " WHERE ( ";
										$i = 0;
										foreach ($unidades as $unidad) {
											if ($i == 0)
											{
												$i++;
											}
											else
											{
												$query .= " or ";
											}
											$query .= "alma.unidad = '".mysqli_real_escape_string($db_con, $unidad)."'";
										}
										$query .= ")";
									}
			        				$query .= " ORDER BY curso, alma.unidad, alma.apellidos, alma.nombre ASC";
			        				$result = mysqli_query($db_con, $query); 
			        				$unidad_ant = "";
									echo '<select class="form-control" id="cmp_alumnos" name="cmp_alumnos[]" size="21" multiple>';
			        				while ($row = mysqli_fetch_assoc($result))
			        				{ 
			        					if ($row['unidad'] != $unidad_ant )
			        					{
					        				echo "</optgroup>";
			        						$unidad_ant = $row['unidad'];
					        				echo '<optgroup label="'.$row['unidad'].'">';
			        					}
			        					echo '<option value="'.$row['unidad'].' => '.$row['claveal'].'"';
			        					if ($todos_alumnos == "si" || (isset($alumnos) && in_array($row['unidad'].' => '.$row['claveal'], $alumnos)))
			        					{
			        						echo ' selected '; 	
			        					} 
			        					echo '>'.$row['nombre'].' '.$row['apellidos'].'</option>';
			        				}
			        				echo "</optgroup>";
			        			?>
				        			</select>
				        			<p class="help-block">Para seleccionar varios grupos, mantén apretada la tecla <kbd>Ctrl</kbd> mientras los vas marcando con el ratón.</p>
				        		</div>
			        		</div>			        		
			        	</div><!-- /.col-sm-4 -->
			        </div><!-- /.row -->
			        <input type="hidden" id="grabar" name="grabar" value="0">
			        <input type="hidden" id="imprimir" name="imprimir" value="0">
			        <input type="hidden" id="borrar" name="borrar" value="0">
			        <input type="hidden" id="dia" name="dia" value="<?php echo $dia; ?>">
			        <input type="hidden" id="mes" name="mes" value="<?php echo $mes; ?>">
			        <input type="hidden" id="anio" name="anio" value="<?php echo $anio; ?>">
			        <input type="hidden" id="anio" name="id" value="<?php echo $id; ?>">
	        	</fieldset>
	        	</form>
	        
		    </div>
		    <div id="pie">
		        <button type="button" class="btn btn-default" id="btn_cancelar" onclick="location.href='index.php?mes=<?php echo $mes; ?>&anio=<?php echo $anio; ?>';">Cancelar</button>
		        <?php
		        	//echo 'http://'.$config["dominio"].'/'.$config["path"].'/calendario/index.php?mes='.$mes.'&anio='.$anio;
		        ?>
		        
		        <input type="button" class="btn btn-primary" id="btn_enviar"  onclick="subform()" value="<?php echo ($id != "") ? 'Modificar' : 'Crear'; ?>">
		        <?php 
		        	if ($id != "")
		        	{
				        echo '<input type="button" class="btn btn-success" id="btn_imprimir"  onclick="imprimir()" value="Imprimir Carta para Padres">';
				        echo '<input type="button" class="btn btn-danger"  id="btn_eliminar" onclick="eliminar()" value="Eliminar">';
		        	}
		        ?>
		    </div>
		</div>
	</div>
<?php
	}
}
?>
<?php include("../pie.php"); ?>
	
<script>
	function seleccionar() {
		valor = document.getElementById("cmp_todos_alumnos").checked;
		elem=document.getElementById("cmp_alumnos").options;
		for(i=0;i<elem.length;i++)
			elem[i].selected=valor;
	}
	function desactivar(elemento) {
	for(var i = 0; i < elemento.length; i++) {
		document.getElementById(elemento[i]).disabled = true;
		alert(elemento[i]);
	}
}
	function subform() {
		elec = document.getElementById("cmp_calendario").value;
		sub = 0;
		hayalum = 1;
		hayprof = 1;
		if (elec == 2 )
		{
			hayalum = 0;
			hayprof = 0;
			elem=document.getElementById("cmp_alumnos").options;
			for(i=0;i<elem.length;i++)
			{
				if (elem[i].selected )
				{
					hayalum = 1;
				}
			}
			elem=document.getElementById("cmp_profesores").options;
			for(i=0;i<elem.length;i++)
			{
				if (elem[i].selected )
				{
					hayprof = 1;
				}
			}

		}
		if ((hayalum == 1) && (hayprof == 1))
		{

			document.getElementById("grabar").value = "1";
			document.getElementById("formNuevoEvento").submit();			
		}
		else
		{
			if (hayalum == 0)
			{
				alert("tienes que seleccionar algun alumno")
			}
			if (hayprof == 0)
			{
				alert("tienes que seleccionar algun profesor")
			}
		}
	}
	function eliminar() {
		document.getElementById("borrar").value = "1";
		document.getElementById("formNuevoEvento").submit();
	}
	function imprimir() {
		document.getElementById("imprimir").value = "1";
		document.getElementById("formNuevoEvento").submit();
	}
    $(function () {			
				
		// DATETIMEPICKERS
		$('.datetimepicker1').datetimepicker({
			language: 'es',
			pickTime: false
		})
		
		$('.datetimepicker2').datetimepicker({
			language: 'es',
			pickTime: true,
			pickDate: false
		})
		
		$('.datetimepicker3').datetimepicker({
			language: 'es',
			pickTime: false
		})
		
		$('.datetimepicker4').datetimepicker({
			language: 'es',
			pickTime: true,
			pickDate: false
		})
		
		if ($('#cmp_calendario').val() == 2) {
		    $('#opciones_actividades').show();
		    $('#opciones_diario').hide();
		}
		else {
	        $("#btn_imprimir").hide();
			$('#opciones_actividades').hide();
			if ($('#cmp_calendario').val() == 1) 
			{
				$('#opciones_diario').hide();
			}
			else
			{
				$('#opciones_diario').show();				
			}
		}

		if ($('#cmp_unidades :selected').length > 0) {
		    $('#divalumnoschk').show();
		    $('#divalumnossel').show();
		}
		else {
		    $('#divalumnoschk').hide();
		    $('#divalumnossel').hide();
		}
		if($('#cmp_fecha_diacomp').is(':checked')) {
			$(".cmp_fecha_toggle").attr('disabled', true);
			$(".cmp_fecha_toggle").attr('disabled', true);
		} else {  
			$(".cmp_fecha_toggle").attr('disabled', false);
			$(".cmp_fecha_toggle").attr('disabled', false);
		}  

		$('#cmp_calendario').change(function() {
		    if ($('#cmp_calendario').val() == 2) {
		        $('#opciones_actividades').show();
		        $("#btn_imprimir").show();
		    }
		    else {
		        $('#opciones_actividades').hide();
		        $("#btn_imprimir").hide();
		    }
		});
		$('#cmp_calendario').change(function() {
		    if ($('#cmp_calendario').val() != 1 && $('#cmp_calendario').val() != 2) {
		        $('#opciones_diario').show();
		    }
		    else {
		        $('#opciones_diario').hide();
		    }
		});
		
		$('#cmp_fecha_diacomp').click(function() {  
			if($('#cmp_fecha_diacomp').is(':checked')) {
				$(".cmp_fecha_toggle").attr('disabled', true);
				$(".cmp_fecha_toggle").attr('disabled', true);
			} else {  
				$(".cmp_fecha_toggle").attr('disabled', false);
				$(".cmp_fecha_toggle").attr('disabled', false);
			}  
		});
<?php
if ($id != "")
{
?>
	$("#cmp_calendario").attr('disabled', 'disabled');
<?php
}
if ($bloqueo == 1)
{
?>
	$("#cmp_nombre").attr('disabled', 'disabled');
	$("#cmp_fecha_diacomp").attr('disabled', 'disabled');
	$("#cmp_fecha_ini").attr('disabled', 'disabled');
	$("#cmp_fecha_fin").attr('disabled', 'disabled');
	$("#cmp_hora_ini").attr('disabled', 'disabled');
	$("#cmp_hora_fin").attr('disabled', 'disabled');
	$("#cmp_unidades").attr('disabled', 'disabled');
	$("#cmp_calendario").attr('disabled', 'disabled');
	$("#cmp_alumnos").attr('disabled', 'disabled');
	$("#cmp_observaciones").attr('disabled', 'disabled');
	$("#cmp_cuaderno").attr('disabled', 'disabled');
	$("#cmp_profesores").attr('disabled', 'disabled');
	$("#cmp_departamento").attr('disabled', 'disabled');
	$("#cmp_descripcion").attr('disabled', 'disabled');
	$("#cmp_lugar").attr('disabled', 'disabled');
	$("#cmp_todos_alumnos").attr('disabled', 'disabled');
	$("#cmp_descripcion").attr('disabled', 'disabled');
	$("#btn_eliminar").attr('disabled', 'disabled');
	$("#btn_imprimir").attr('disabled', 'disabled');
	$("#btn_enviar").attr('disabled', 'disabled');
   
<?php
}
?>		
        
	});
</script>