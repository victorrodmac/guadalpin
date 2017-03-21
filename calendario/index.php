<?php
require('../bootstrap.php');

$GLOBALS['db_con'] = $db_con;


// CALENDARIO
$dia_actual = date('d');

$dia  = isset($_GET['dia'])  ? $_GET['dia']  : date('d');
$mes  = isset($_GET['mes'])  ? $_GET['mes']  : date('n');
$anio = isset($_GET['anio']) ? $_GET['anio'] : date('Y');

//correcci�n del error de no mostrar nada si el mes empieza por 0
if ((strlen($mes) > 1) && ($mes != 10) && ($mes != 11) && ($mes != 12))
	$mes = substr($mes, 1);

$semana = 1;

for ($i = 1; $i <= date('t', strtotime($anio.'-'.$mes)); $i++) {
	
	$dia_semana = date('N', strtotime($anio.'-'.$mes.'-'.$i));
	
	$calendario[$semana][$dia_semana] = $i;
	if ($dia_semana == 7) $semana++;
	
}


// NAVEGACION
$mes_ant = $mes - 1;
$anio_ant = $anio;

if ($mes == 1) {
	$mes_ant = 12;
	$anio_ant = $anio - 1;
}


$mes_sig = $mes + 1;
$anio_sig = $anio;

if ($mes == 12) {
	$mes_sig = 1;
	$anio_sig = $anio + 1;
}

// HTML CALENDARIO MENSUAL
function vista_mes ($calendario, $dia, $mes, $anio, $cargo) {
	
	// Correcci�n en mes
	($mes < 10) ? $mes = '0'.$mes : $mes = $mes;
	
	echo '<div class"table-responsive">';
	echo '<table id="calendar" class="table table-bordered">';
	echo '	<thead>';
	echo '		<tr>';
	echo '			<th class="text-center">Lunes</th>';
	echo '			<th class="text-center">Martes</th>';
	echo '			<th class="text-center">Mi�rcoles</th>';
	echo '			<th class="text-center">Jueves</th>';
	echo '			<th class="text-center">Viernes</th>';
	echo '			<th class="text-center">S�bado</th>';
	echo '			<th class="text-center">Domingo</th>';
	echo '		</tr>';
	echo '	</thead>';
	echo '	<tbody>';
	
	foreach ($calendario as $dias) {
		echo '		<tr>';
	
		for ($i = 1; $i <= 7; $i++) {
			
			if ($i > 5) {
				if (isset($dias[$i]) && ($mes == date('m')) && ($dias[$i] == date('d'))) {
					echo '			<td class="text-muted today" width="14.28%">';
				}
				else {
					echo '			<td class="text-muted" width="14.28%">';
				}
			}
			else {
				if (isset($dias[$i]) && ($mes == date('m')) && ($dias[$i] == date('d'))) {
					echo '			<td class="today" width="14.28%">';
				}
				else {
					echo '			<td width="14.28%">';
				}
			}
			
			if (isset($dias[$i])) {

				echo '				<p class="lead text-right">'.$dias[$i].'</p>';
				
				// Correcci�n en d�a
				($dias[$i] < 10) ? $dia0 = '0'.$dias[$i] : $dia0 = $dias[$i];
				
				
				// Consultamos los calendarios privados del usuario y los p�blicos
				$cons = "SELECT id, color FROM calendario_categorias WHERE (profesor='".$_SESSION['ide']."' AND espublico=0) or espublico=1";
				//echo $cons;
				$result_calendarios = mysqli_query($GLOBALS['db_con'], $cons);
				while ($calendario = mysqli_fetch_assoc($result_calendarios)) {
					$cons2 ="SELECT distinct calendario.id, calendario.nombre, descripcion, fechaini, horaini, fechafin, horafin FROM calendario WHERE categoria='".$calendario['id']."' AND YEAR(fechaini)='$anio' AND '$mes' BETWEEN MONTH(fechaini) AND MONTH(fechafin) ORDER BY horaini ASC, horafin ASC";
				//echo $cons2;
					$result_eventos = mysqli_query($GLOBALS['db_con'], $cons2);
					while ($eventos = mysqli_fetch_assoc($result_eventos)) {
						
						$id = $eventos['id'];
						$nombre = $eventos['nombre'];
						$descripcion = $eventos['descripcion'];
						$saltarprimera ++;
						$horaini = substr($eventos['horaini'], 0, -3);
						$horafin = substr($eventos['horafin'], 0, -3);
						$fechaini = $eventos['fechaini'];
						$fechafin = $eventos['fechafin'];
						$unidades = "";
						if ($calendario['id'] == 2 )
						{
							$cons4 = "SELECT distinct unidad from calendario_alumnos, alma WHERE alma.claveal = calendario_alumnos.claveal and idcalendario='$id' order by unidad";
							//echo $cons4;
							$result4 = mysqli_query($db_con, $cons4);
							while( $resultrow4 = mysqli_fetch_array($result4))
							{
								$unidades .= $resultrow4[0].";";
							}
							$unidades = substr($unidades, 0, -1);
						}

						if ($anio.'-'.$mes.'-'.$dia0 >= $fechaini && $anio.'-'.$mes.'-'.$dia0 <= $fechafin) {
							
							if ($eventos['fechafin'] == $anio.'-'.$mes.'-'.$dia0) {
								$hora_evento = 'Hasta las '.$horafin;
							}
							else if($fechaini != $fechafin || ($fechaini == $fechafin && $horaini == $horafin)) {
								$hora_evento = 'Todo el d�a';
							}
							else {
								$hora_evento = $horaini.' - '.$horafin;
							}								
							echo '<a href="insercion.php?mes='.$mes.'&anio='.$anio.'&id='.$id.'" class="label idcal_'.$calendario["id"].' visible" style="background-color: '.$calendario['color'].';" data-bs="tooltip" title="'.substr(stripslashes($descripcion), 0, 500).'"><p><strong>'.$hora_evento.'</strong></p>'.stripslashes($nombre).'<br>'.$unidades.'</a>';
						}
					}
					mysqli_free_result($result_eventos);
				}
				mysqli_free_result($result_calendarios);
				
				// FESTIVOS
				$result = mysqli_query($GLOBALS['db_con'], "SELECT fecha, nombre FROM festivos");
				while ($festivo = mysqli_fetch_assoc($result)) {
					
					if ($festivo['fecha'] == $anio.'-'.$mes.'-'.$dia0) {
						echo '<div class="label label-danger hidden_calendario_festivo visible" data-bs="tooltip" title="'.$festivo['nombre'].'">'.$festivo['nombre'].'</div>';
					}
				}
				mysqli_free_result($result);
				unset($festivo);
				
				
			}
			else {
				echo '&nbsp;';
			}
			
			echo '			</td>';
		}
		echo '		</tr>';
	}
	
	echo '	</tbody>';
	echo '</table>';
	echo '</div>';

}

$lista_errores = array(
	'ErrorCalendarioNoExiste' => 'El calendario que intenta modificar no existe.',
	'ErrorCalendarioExiste'   => 'Este calendario ya existe.',
	'ErrorCalendarioInsertar' => 'Se ha producido un error al crear el calendario.',
	'ErrorEliminarCalendario' => 'Se ha producido un error al eliminar el calendario.',
	'ErrorCalendarioEdicion'  => 'Se ha producido un error al modificar el calendario.',
	'ErrorEventoNoExiste'     => 'El evento que intenta modificar no existe.',
	'ErrorEventoUnidad'       => 'Hab�a unidades sin alumnos con lo que no se han introducido estas unidades',
	'ErrorEventoExiste'       => 'Este evento ya existe.',
	'ErrorEventoInsertar'     => 'Se ha producido un error al crear el evento.',
	'ErrorEventoFecha'        => 'Se ha producido un error al crear el evento. La fecha de inicio no puede ser posterior a la fecha final del evento.',
	'ErrorEliminarEvento'     => 'Se ha producido un error al eliminar el evento.',
	'ErrorEventoEdicion'      => 'Se ha producido un error al modificar el evento.',
	'EventoPendienteConfirmacion' => 'El evento ha sido registrado y est� pendiente de aprobaci�n por el Consejo Escolar. Debe esperar su aprobaci�n para que aparezca oficialmente en el calendario.',
	'ErrorSinAlumnos'		  => 'Has seleccionado grupos pero no has seleccionado que vaya ningun alumno.',
	);

function randomColor() {
    $str = '#';
    for($i = 0 ; $i < 6 ; $i++) {
        $randNum = rand(0 , 15);
        switch ($randNum) {
            case 10: $randNum = 'A'; break;
            case 11: $randNum = 'B'; break;
            case 12: $randNum = 'C'; break;
            case 13: $randNum = 'D'; break;
            case 14: $randNum = 'E'; break;
            case 15: $randNum = 'F'; break;
        }
        $str .= $randNum;
    }
    return $str;
}

$PLUGIN_DATETIMEPICKER = 1;
$PLUGIN_COLORPICKER = 1;

include("../menu.php"); ?>

	<div class="container">
		
		<style type="text/css">
		table#calendar>tbody>tr>td {
			height: 103px !important;
		}
		.label {
			display: block;
			white-space: normal;
			text-align: left;
			margin-top: 5px;
			text-decoration: none !important;
			font-size: 0.9em;
			font-weight: 400;
		}
		
		p.lead {
			margin-bottom: 0;
		}
		
		.today {
			background-color: #ecf0f1;
		}
		
		.today p.lead {
			font-weight: bold;
		}
		
		<?php $result = mysqli_query($db_con, "SELECT id, nombre, color FROM calendario_categorias WHERE (profesor='".$_SESSION['ide']."' AND espublico=0) or espublico=1"); ?>
		<?php if (mysqli_num_rows($result)): ?>
		<?php while ($row = mysqli_fetch_assoc($result)): ?>
		.idcal_<?php echo $row['id']; ?> {
		  display: none;
		}
		<?php endwhile; ?>
		<?php endif; ?>
		
		.hidden_calendario_festivo {
			display: none;
		}
		
		.visible {
			display: block;
		}
		
		@media print {
			html, body {
				width: 100%;
			}
			
			.container, .col-md-12 {
				width: 100%;
			}
			
		}
		</style>
		
		
		<?php
		include('modal_insercion.php');
		?>
		
		
		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<h2>Calendario <small><?php echo strftime('%B, %Y', strtotime($anio.'-'.$mes)); ?></small></h2>
		</div>
		
		
		
		<!-- SCAFFOLDING -->
		<div class="row">
			
			<!-- COLUMNA CENTRAL -->
			<div class="col-md-12">
			
				<!-- BUTTONS -->
				<div class="hidden-print">
					<div class="btn-group">
					  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					    Calendarios <span class="caret"></span>
					  </button>
					  <ul class="dropdown-menu" role="menu" style="min-width: 340px !important;">
					  	<li role="presentation" class="dropdown-header">Mis calendarios</li>
					    <?php $result = mysqli_query($db_con, "SELECT id, nombre, color FROM calendario_categorias WHERE profesor='".$_SESSION['ide']."' AND espublico=0 ORDER BY id ASC"); ?>
					    <?php if (mysqli_num_rows($result)): ?>
					    <?php $i = 1; ?>
					    <?php while ($row = mysqli_fetch_assoc($result)): ?>
					    <li>
					    	<a href="#" class="nohide" id="toggle_calendario_<?php echo $row['id']; ?>">
					    		<span class="fa fa-square fa-fw fa-lg" style="color: <?php echo $row['color']; ?>;"></span>
					    		<?php echo $row['nombre']; ?>
					    		<span class="pull-right eyeicon_<?php echo $row['id']; ?>"><span class="fa fa-eye fa-fw fa-lg"></span></span>
					    		<?php if ($i > 1): ?>
					    		<form class="pull-right" method="post" action="post/eliminarCalendario.php?mes=<?php echo $mes; ?>&anio=<?php echo $anio; ?>" style="display: inline; margin-top: -5px;">
					    			<input type="hidden" name="cmp_calendario_id" value="<?php echo $row['id']; ?>">
					    			<button type="submit" class="btn-link delete-calendar"><span class="fa fa-trash fa-fw fa-lg"></span></button>
					    		</form>
					    		<?php else: ?>
					    		<?php $idcal_diario = $row['id']; ?>
					    		<?php endif; ?>
					    	</a>
					    </li>
					    <?php $i++; ?>
					    <?php endwhile; ?>
					    <?php unset($i); ?>
					    <?php endif; ?>
					    <li class="divider"></li>
					    <?php $result = mysqli_query($db_con, "SELECT id, nombre, color FROM calendario_categorias WHERE espublico=1"); ?>
					    <?php if (mysqli_num_rows($result)): ?>
				    	<li role="presentation" class="dropdown-header">Otros calendarios</li>
				    	<?php while ($row = mysqli_fetch_assoc($result)): ?>
				    	<li>
				    		<a href="#" class="nohide" id="toggle_calendario_<?php echo $row['id']; ?>">
				    			<span class="fa fa-square fa-fw fa-lg" style="color: <?php echo $row['color']; ?>;"></span>
				    			<?php echo $row['nombre']; ?>
				    			<span class="pull-right eyeicon_<?php echo $row['id']; ?>"><span class="fa fa-eye fa-fw fa-lg"></span></span>
				    			<?php if ((stristr($_SESSION['cargo'], '1')==TRUE) && $row['id'] != 1 && $row['id'] != 2): ?>
				    			<form class="pull-right" method="post" action="post/eliminarCalendario.php?mes=<?php echo $mes; ?>&anio=<?php echo $anio; ?>" style="display: inline; margin-top: -5px;">
				    				<input type="hidden" name="cmp_calendario_id" value="<?php echo $row['id']; ?>">
				    				<button type="submit" class="btn-link delete-calendar"><span class="fa fa-trash fa-fw fa-lg"></span></button>
				    			</form>
				    			<?php endif; ?>
				    		</a>
				    	</li>
				    	<?php endwhile; ?>
				    	<li>
				    		<a href="#" class="nohide" id="toggle_calendario_festivo">
				    			<span class="fa fa-square fa-fw fa-lg" style="color: #e14939;"></span> D�as festivos
				    			<span class="pull-right eyeicon_festivo"><span class="fa fa-eye fa-fw fa-lg"></span></span>
				    		</a>
				    	</li>
					    <?php endif; ?>
					    <li class="divider"></li>
					    <li><a href="#" data-toggle="modal" data-target="#modalNuevoCalendario">Crear calendario...</a></li>
					  </ul>
					</div>
					
					
					<a href="insercion.php?mes=<?php echo $mes;?>&anio=<?php echo $anio;?>" class="btn btn-primary"><span class="fa fa-calendar-plus-o fa-fw"></span> Nueva Actividad</a>
					
					<div class="pull-right">
						<a href="#" onclick="javascrip:print()" class="btn btn-default"><span class="fa fa-print fa-fw"></span></a>
						
						<div class="btn-group">
						  <a href="?mes=<?php echo $mes_ant; ?>&anio=<?php echo $anio_ant; ?>" class="btn btn-default">&laquo;</a>
						  <a href="?mes=<?php echo date('n'); ?>&anio=<?php echo date('Y'); ?>" class="btn btn-default">Hoy</a>
						  <a href="?mes=<?php echo $mes_sig; ?>&anio=<?php echo $anio_sig; ?>" class="btn btn-default">&raquo;</a>
						 </div>
						 
						<!-- Button trigger modal -->
					 	<a href="#"class="btn btn-default hidden-print" data-toggle="modal" data-target="#modalAyuda">
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
					 					<p>Este m�dulo presenta los distintos calendarios que funcionan en la aplicaci�n.</p>
					 					<p>El <strong>Calendario personal</strong> es propio de todos y cada uno de los 
					 					profesores. S�lo es visible para el profesor concreto que es su propietario. Si la 
					 					actividad afecta a Grupos de alumnos (hemos seleccionado alguno de nuestros grupos), 
					 					tambi�n es visible para los profesores que dan clase en esos grupos. Es una forma 
					 					f�cil de controlar los ex�menes o actividades que afectan al grupo por parte del 
					 					Equipo Educativo del mismo.</p>
					 					<p>Adem�s del Calendario personal, podemos crear tantos calendarios personales como 
					 					necesitemos (calendarios asociados a nuestros grupos para crear un diario de trabajo 
					 					con los alumnos, etc.). Para a�adir un calendario hacemos click sobre el icono de 
					 					<span class="fa fa-calendar-plus-o fa-fw"></span> que aparece al lado del selector de 
					 					calendarios.</p>
					 					<p>El <strong>Calendario del Centro</strong> es visible por todo el mundo, incluida 
					 					la P�gina p�blica del Centro. El Equipo Directivo puede crear entradas en este calendario.</p>
					 					<p>El <strong>Calendario de Actividades Complementarias y Extraescolares</strong> es 
					 					tambi�n visible por todo el mundo y pueden crear entradas los Jefes de Departamento, 
					 					Tutores, DACE y Direcci�n. Tambi�n pueden editar las actividades los profesores 
					 					asociados a una de ellas. El formulario de registro de Actividades aparece cuando 
					 					hemos seleccionado este Calendario. Los campos son obligatorios. Si es el Tutor 
					 					quien registra una actividad complementaria se encontrar� limitado a su Grupo de 
					 					Tutor�a, y aparecer� bajo el Departamento de Orientaci�n. M�s informaci�n sobre el 
					 					mecanismo que regula las Actividades Extraescolares en el Men� de la p�gina 
					 					principal --> Departamento --> Actividades Extraescolares.</p>
					 				</div>
					 				<div class="modal-footer">
					 					<button type="button" class="btn btn-default" data-dismiss="modal">Entendido</button>
					 				</div>
					 			</div>
					 		</div>
					 	</div>					  
						
					</div>

				</div>
				
				<br class="hidden-print">
				
				<?php if ($_GET['msg'] && $_GET['msg'] != "EventoPendienteConfirmacion"): ?>
				<div class="alert alert-danger alert-block hidden-print">
					<strong>Error: </strong> <?php echo $lista_errores[$_GET['msg']]; ?>
				</div>
				<?php endif; ?>
				
				<?php if ($_GET['msg'] && $_GET['msg'] == "EventoPendienteConfirmacion"): ?>
				<div class="alert alert-info alert-block hidden-print">
					<?php echo $lista_errores[$_GET['msg']]; ?>
				</div>
				<?php endif; ?>
				
				<?php vista_mes($calendario, $dia, $mes, $anio, $_SESSION['cargo']); ?>
				
			</div><!-- /.col-md-12 -->
			
		</div><!-- /.row -->
		
	</div><!-- /.container -->

<?php include("../pie.php"); ?>

	<script>
		$(function() {
			// MOSTRAR/OCULTAR CALENDARIOS
			<?php $result = mysqli_query($db_con, "SELECT id, nombre, color FROM calendario_categorias WHERE (profesor='".$_SESSION['ide']."' AND espublico=0 ) or espublico=1"); ?>
			<?php if (mysqli_num_rows($result)): ?>
			<?php while ($row = mysqli_fetch_assoc($result)): ?>
			$("#toggle_calendario_<?php echo $row['id']; ?>").click(function() {
			  $('.idcal_<?php echo $row['id']; ?>').toggleClass("visible");
			  if ($(".eyeicon_<?php echo $row['id']; ?>").html() == '<span class="fa fa-eye fa-fw fa-lg"></span>') {
			 	 $(".eyeicon_<?php echo $row['id']; ?>").html('<span class="fa fa-eye-slash fa-fw fa-lg"></span>');
			  }
			  else {
			  	$(".eyeicon_<?php echo $row['id']; ?>").html('<span class="fa fa-eye fa-fw fa-lg"></span>');
			  }
			});
			<?php endwhile; ?>
			<?php endif; ?>
			
			$("#toggle_calendario_festivo").click(function() {
			  $('.hidden_calendario_festivo').toggleClass("visible");
			  if ($(".eyeicon_festivo").html() == '<span class="fa fa-eye fa-fw fa-lg"></span>') {
			  	 $(".eyeicon_festivo").html('<span class="fa fa-eye-slash fa-fw fa-lg"></span>');
			  }
			  else {
			  	$(".eyeicon_festivo").html('<span class="fa fa-eye fa-fw fa-lg"></span>');
			  }
			});
			
			
			// OPCIONES DROPDOWN
			$('.dropdown-menu input, .dropdown-menu a.nohide').click(function(e) {
			    e.stopPropagation();
			});
			
			// ABRIR MODALES
			<?php if(isset($_GET['viewModal'])): ?>
			$('#modalEvento<?php echo $_GET['viewModal']; ?>').modal('show');
			<?php endif; ?>
			
			<?php if(isset($_GET['action']) && $_GET['action'] == 'nuevoEvento'): ?>
			$('#modalNuevoEvento').modal('show');
			<?php endif; ?>
			
			
			// MODAL NUEVO CALENDARIO
			$('#colorpicker1').colorpicker();
			
			// MODAL NUEVO EVENTO
			$('#modalNuevoEvento').modal({
			  show: false,
			  keyboard: false,
			  backdrop: true
			})
						
			
			$('#modalNuevoEvento').on('hidden.bs.modal', function () {
				$('#formNuevoEvento')[0].reset();
				$('#opciones_actividades').hide();
			})
			
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
			
			// MODAL CONFIRMACION PARA ELIMINAR
            $(".delete-calendar").on("click", function(e) {
            	bootbox.setDefaults({
            	  locale: "es",
            	  show: true,
            	  backdrop: true,
            	  closeButton: true,
            	  animate: true,
            	  title: "Confirmaci�n para eliminar",
            	});
            	
                e.preventDefault();
                var _this = this;
                bootbox.confirm("Esta acci�n eliminar� permanentemente los eventos del calendario �Seguro que desea continuar?", function(result) {
                    if (result) {
                        $(_this).parent().submit();
                    }
                });
            });
            
		});
	</script>
</body>
</html>
