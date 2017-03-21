<?php
require('../../bootstrap.php');

if(stristr($_SESSION['cargo'],'1') == TRUE or stristr($_SESSION['cargo'],'5') == TRUE)
{
	$jefes=1;
}

$PLUGIN_DATATABLES = 1;

include("../../menu.php");
include("menu.php");

?>
<div class='container'>
	<div class="page-header">
		<h2>Actividades Complementarias y Extraescolares <small> Administraci�n</small></h2>
	</div>

	<div class="row">

		<div class="col-sm-12">
			<?php   
			if ($_GET['eliminar']=="1") 
			{
				$cons = "delete from calendario_profesores WHERE idcalendario='".$_GET['id']."' ";
				//echo $cons;
				mysqli_query($db_con, $cons);
				$cons = "delete from calendario_alumnos WHERE idcalendario='".$_GET['id']."' ";
				//echo $cons;
				mysqli_query($db_con, $cons);
				$cons = "delete from calendario_asignaturas WHERE idcalendario='".$_GET['id']."' ";
				//echo $cons;
				mysqli_query($db_con, $cons);
				$cons = "delete from calendario WHERE id='".$_GET['id']."' ";
				//echo $cons;
				mysqli_query($db_con, $cons);
				if (mysqli_affected_rows($db_con)>'0') 
				{
					echo ' <br /><div><div class="alert alert-success alert-block fade in">
			            <button type="button" class="close" data-dismiss="alert">&times;</button>
						La actividad ha sido borrada correctamente de la base de datos.         
						</div></div>';  	
				}
				else
				{
					echo ' <br /><div><div class="alert alert-danger alert-block fade in">
			            <button type="button" class="close" data-dismiss="alert">&times;</button>
						La actividad no ha podido ser borrada correctamente de la base de datos.         
						</div></div>';  	

				}
			}

			if($confirmado == '1')
			{
				mysqli_query($db_con, "UPDATE  calendario SET  confirmado =  '1' WHERE id = '$id'");
				echo '
			<div><div class="alert alert-success alert-block fade in">
			            <button type="button" class="close" data-dismiss="alert">&times;</button>
			La Actividad ha sido confirmada por la Autoridad.
						</div></div>'; 

			}
			if($detalles == '1')
			{
				$datos0 = "select * from calendario where id = '$id'";
				$datos1 = mysqli_query($db_con, $datos0);
				$datos = mysqli_fetch_array($datos1);
				$fecha0 = explode("-",$datos[6]);
				$fecha = "$fecha0[2]-$fecha0[1]-$fecha0[0]";
				$registro = $datos[13];
			?>
			<div>
				<h3>Informaci�n completa de Actividad Extraescolar</h3>
				<br />
			</div>
			<div>
				<table align="center" class="table table-bordered table-striped" style="width:auto;">
				<tr>
					<th colspan="2">
						<h4 class="text-info"><?php echo $datos[2];?></h4>
					</th>
				</tr>
				<tr>
					<th>Grupos</th>
					<td><?php echo substr($datos[11],0,-1);?></td>
				</tr>
				<tr>
					<th>Descripci�n</th>
					<td><?php echo $datos[3];?></td>
				</tr>
				<tr>
					<th>Departamento</th>
					<td><?php echo $datos[9];?></td>
				</tr>
				<tr>
					<th>Profesores</th>
					<td><?php echo $datos[10];?></td>
				</tr>
				<tr>
					<th>Horario</th>
					<td><?php 
					if ($datos[5]=="00:00:00") {
						echo "Todo el d�a.";
					}
					else{
						echo $datos[5]." - ".$datos[7];
					}
					?>
					</td>
				</tr>
				<tr>
					<th>Fecha</th>
					<td><?php echo $fecha;?></td>
				</tr>
				<tr>
					<th>Registro</th>
					<td><?php echo $registro;?></td>
				</tr>
				<tr>
					<th>Autorizada</th>
					<td><?php
					if ($datos[9]=="0") {
						echo "NO";
					}
					else{
						echo "S�";
					}	
					?></td>
				</tr>
				<tr>
					<th>Observaciones</th>
					<td><?php echo $datos[16];?></td>
				</tr>
				</table>
			</div>
			<br />
		<?php
			}
		?>

			<table class="table table-striped table-hover datatable">
				<thead>
					<tr>
						<th>Fecha</th>
						<th>Actividad</th>
						<th>Unidades</th>
						<th>Mes</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php
					$datos0 = "select distinct id, unidades, nombre, descripcion, departamento, concat(horaini,'-',horafin), fechaini, confirmado, profesorreg from calendario where date(fechaini) > '".$config['curso_inicio']."' and categoria='2' order by fechaini";
					//echo $datos0;
					$datos1 = mysqli_query($db_con, $datos0);
					while($datos = mysqli_fetch_array($datos1))
					{
						$confirmado = $datos[7];
						$profesorreg = $datos[8];
						$fecha0 = explode("-",$datos[6]);
						$fecha = "$fecha0[2]-$fecha0[1]-$fecha0[0]";
						if (substr($fecha0[1],0,1)=="0") {$mes=str_replace("0","",$fecha0[1]);}else{$mes=$fecha0[1];}
						if($mes ==  "1") $mes2 = "Enero";
						if($mes ==  "2") $mes2 = "Febrero";
						if($mes ==  "3") $mes2 = "Marzo";
						if($mes ==  "4") $mes2 = "Abril";
						if($mes ==  "5") $mes2 = "Mayo";
						if($mes ==  "6") $mes2 = "Junio";
						if($mes ==  "7") $mes2 = "Julio";
						if($mes ==  "8") $mes2 = "Agosto";
						if($mes ==  "9") $mes2 = "Septiembre";
						if($mes ==  "10") $mes2 = "Octubre";
						if($mes ==  "11") $mes2 = "Noviembre";
						if($mes ==  "12") $mes2 = "Diciembre";
						$cal_act = '//'.$config['dominio'].'/'.$config['path'].'/calendario/insercion.php?mes='.$mes.'&anio='.$fecha0[0];
						//$cal_act = '//'.$config['dominio'].'/'.$config['path'].'/calendario/index.php?mes='.$mes.'&anio='.$fecha0[0].'&viewModal='.$datos[0];
						$datos[2]= str_replace("\\","",$datos[2]);
				?>
					<tr>
						<td nowrap="nowrap"><?php echo $datos[6];?></td>
						<td><?php echo $datos[2];?></td>
						<td style="width:200px"><?php echo $datos[1];?></td>
						<td><?php echo $mes2;?></td>
						<td nowrap>
				<?php 
						$result_actividad = mysqli_query($db_con, "SELECT nombre FROM calendario_profesores WHERE idcalendario = '".$datos[0]."' and nombre = '".$_SESSION["profi"]."'");
								
						if (( $_SESSION['ide'] == $profesorreg) || (mysqli_num_rows($result_actividad)))
						{
							$esprofesor = 1;
						}					
				?>		
				<?php
						echo '<a href="extraescolares.php?id='.$datos[0].'&ver_lista=1">
							<span class="fa fa-users fa-fw fa-lg" data-bs="tooltip" title="Ver la lista de alumnos seleccionados que realizan la actividad"></span>
							</a>';
						if($jefes == 1 or ($confirmado != "1" and $esprofesor == 1))
						{
				?> 
						<a href="extraescolares.php?id=<?php echo $datos[0];?>&profesores=<?php  echo $datos[5];?>">
							<span class="fa fa-user fa-fw fa-lg" data-bs="tooltip" title="Seleccionar alumnos que realizan la Actividad"></span>
						</a> 
				<?php 
						} 
				?>
						<a href="indexextra.php?id=<?php echo $datos[0];?>&detalles=1" data-bs="tooltip" title="Detalles">
							<span class="fa fa-search fa-fw fa-lg"></span>
						</a> 
				<?php
						if($jefes == 1 or ($confirmado != "1" and $esprofesor == 1))
						{
				?>
						<a href="<?php echo $cal_act;?>&id=<?php echo $datos[0];?>" data-bs="tooltip" title="Editar"><span class="fa fa-edit fa-fw fa-lg"></span></a>
				<?php
						}
						if($jefes == 1)
						{
							if($confirmado == "1")
							{
				?>
						<span class="fa fa-check-circle fa-fw fa-lg text-success"></span>
				<?php 
							}
							else
							{ 
				?> 
						<a href="indexextra.php?id=<?php echo $datos[0];?>&confirmado=1" data-bs="tooltip" title="Autorizar">
							<span class="fa fa-check-circle fa-fw fa-lg text-danger"></span>
						</a> 
				<?php 
							}
						} 
						if($jefes == 1 or ($confirmado != "1" and $esprofesor == 1))
						{
				?> 
						<a href="indexextra.php?id=<?php echo $datos[0];?>&eliminar=1" data-bs="tooltip" title="Eliminar" data-bb="confirm-delete">
							<span class="fa fa-trash-o fa-fw fa-lg"></span>
						</a> 
				<?php 
						} 
				?>
						</td>
					</tr>
				<?php
					}
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php include("../../pie.php");?>

<script>
	$(document).ready(function() {
		var table = $('.datatable').DataTable({
			"paging":   true,
	    "ordering": true,
	    "info":     false,
	    
			"lengthMenu": [[15, 35, 50, -1], [15, 35, 50, "Todos"]],
			
			"order": [[ 0, "desc" ]],
			
			"language": {
			            "lengthMenu": "_MENU_",
			            "zeroRecords": "No se ha encontrado ning�n resultado con ese criterio.",
			            "info": "P�gina _PAGE_ de _PAGES_",
			            "infoEmpty": "No hay resultados disponibles.",
			            "infoFiltered": "(filtrado de _MAX_ resultados)",
			            "search": "Buscar: ",
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
