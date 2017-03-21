<?php
require('../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

include("../menu.php");
include("menu.php");
?>

<div class="container">

	<div class="page-header">
		<h2>Reservas <small> Crear y Ocultar Aulas / Dependencias del centro</small></h2>
	</div>
	<div class="row">
<?php
		if (isset($_POST['nueva'])) 
		{
			$num = count($_POST);
			if ($num>1) 
			{
				$abrev_nueva = $_POST['abrev_nueva'];
				$nombre_nueva = $_POST['nombre_nueva'];
				$texto = $_POST['texto'];
				if ($_POST['nueva']=="Crear nueva Aula / Dependencia") 
				{
					$sql = "insert into aulas (idaula, a_aula, n_aula, texto, oculta, seneca, reserva, isinhorw) values ('-1',$abrev_nueva','$nombre_nueva','$texto', 0, 0, 0, 0)";
					mysqli_query($db_con, $sql);
					if (mysqli_affected_rows($db_con)>0) {
						$msg = "Los datos se han registrado correctamente. Las aulas / dependencias creadas aparecerán en el sistema de reservas a partir de ahora.";
					}			
				}

				elseif ($_POST['nueva']=="Actualizar datos del Aula / Dependencia") {
					$tr_h=explode(":",$_POST['id']);
					$id = $tr_h[0];

					$actualiza_aula = "update aulas set a_aula='$abrev_nueva', n_aula='$nombre_nueva', texto='$texto' where a_aula = '$id'";
					echo $actualiza_aula;
					mysqli_query($db_con, $actualiza_aula);
					$actualiza_hor = "update horw set a_aula='$abrev_nueva' where a_aula = '$id'";
					echo $actualiza_hor;
					mysqli_query($db_con, $actualiza_hor);
					$actualiza_hor_faltas = "update horw_faltas set a_aula='$abrev_nueva' where a_aula = '$id'";
					echo $actualiza_hor_faltas;
					mysqli_query($db_con, $actualiza_hor_faltas);
					$msg = "Los datos se han actualizado correctamente. Las aulas / dependencias actualizadas aparecerán en el sistema de reservas con los nuevos datos.";

				}
			}
		
?> 

			<br>
			<div class="alert alert-success">
				<p><?php echo $msg;?><p>
			</div>

<?php
		}
		if (isset($_POST['enviar'])) 
		{
			$num = count($_POST);
			mysqli_query($db_con,"update aulas set oculta = 0");
			foreach ($_POST as $valor)
			{
				if ($valor!=="Enviar datos") {
					mysqli_query($db_con,"update aulas set oculta = 1 where a_aula = '".$valor."'");
				}
			}
?> 

			<br>
			<div class="alert alert-success">
				<p>Los datos se han registrado correctamente. Las aulas y dependencias seleccionadas dejarán de aparecer en el sistema de reservas a partir de ahora.<P>
			</div>

<?php
		}
		if (isset($_GET['eliminar'])) 
		{
			$id = $_GET['id'];
			mysqli_query($db_con,"delete from aulas where a_aula = '$id' and seneca = 0");
			if (mysqli_affected_rows($db_con)>0) {
				$msg = "El aula/dependencia ha sido eliminada del sistema de reservas.";
			}
			else
			{
				$msg = "Esta aula/dependencia no se puede eliminar del sistema de reservas";
			}
			?> 
			<br>

			<div class="alert alert-success">
				<p><?php echo $msg;?><P>
			</div>

<?php
		}

		if (isset($_GET['editar'])) 
		{
			$id = $_GET['id'];
			$ya = mysqli_query($db_con,"select a_aula, n_aula,texto from aulas where a_aula = '$id'");
			if (mysqli_num_rows($ya)>0) {
				$ya_id = mysqli_fetch_array($ya);
				$abrev_nueva = $ya_id[0];
				$nombre_nueva =  $ya_id[1];
				$texto = $ya_id[2];
			}
		}
?>
		<div class="col-sm-5 col-sm-offset-1">
			<h3>Ocultar Aulas / Dependencias</h3>
			<p class="help-block text-justify well">A través de esta página puedes
			seleccionar los espacios del centro que quedan fuera del sistema de
			reservas. Marca la casilla de aquellas dependencias que quieres ocultar
			y envía los datos. A partir de ese momento las dependencias elegidas
			quedarán ocultas en la selección de aulas del sistema de reservas.</p>
			<form action="ocultar.php" method="post">
				<table class="table table-striped">
				<?php
					echo "<thead><th colspan=3>Aulas en el Horario</th><th></th></thead><tbody>";
					//$aulas = mysqli_query($db_con,"select distinct a_aula, n_aula from horw where c_asig not in (select distinct idactividad from actividades_seneca where idactividad not like '2' and idactividad not like '21') and a_aula not like '' order by a_aula");
					$aulas = mysqli_query($db_con,"select distinct a_aula, n_aula, oculta, seneca from aulas order by a_aula");
					while ($aula = mysqli_fetch_array($aulas)) {
						$check="";
						$abrev0 = $aula[0];
						$nombre0 = $aula[1];
						$oculta = $aula[2];
						if ($oculta == 1) {
							$check = " checked";
						}

						echo "<tr>";
						echo "<td><input type='checkbox' name='$abrev0' value='$abrev0' $check/></td>";
						echo "<td>$abrev0</td><td>$nombre0</td>";
						echo "<td>$nombre_nueva0 <span class='pull-right'><a href='ocultar.php?editar=1&id=$abrev0'><span class='fa fa-edit fa-fw fa-lg' data-bs='tooltip' title='Editar'></span></a>";
						if ($aula[3] == 0)
						{
							echo "<a href='ocultar.php?id=$abrev0&eliminar=1' data-bb='confirm-delete'><span class='fa fa-trash-o fa-fw fa-lg' data-bs='tooltip' title='Eliminar'></span></a>";
						}
						echo "</td>";
						echo "</tr>";
					}
?>
					<tr>
						<td colspan="4">
							<center><input type="submit" name="enviar" value="Enviar datos" class="btn btn-default"></center>
						</td>
					</tr>
					</tbody>
				</table>
			</form>
		</div>
		<div class="col-sm-5">
			<h3>Crear y editar Aula / Dependencia</h3>
			<p class="help-block text-justify well">Si el Centro no ha importado el
			Horario en la Base de datos, o bien si quieres poder reservar una
			dependencia o aula que no aparece en el Horario, es posible crear aulas
			para introducirlas en el sistema de reservas. Crea las aulas rellenando
			los datos en el formulario para que la misma aparezca en la lista de
			reservas.</p>
			<form action="ocultar.php" method="post">
				<div class="form-group">
					<label>Abreviatura</label> 
					<input class="form-control" type="text" maxlength="32" name="abrev_nueva" value="<?php echo $abrev_nueva;?>" placeholder="32 caracteres como máximo">
				</div>
				<div class="form-group">
					<label>Nombre del Aula</label> 
					<input class="form-control" type="text" maxlength="64" name="nombre_nueva" value="<?php echo $nombre_nueva;?>" placeholder="64 caracteres como máximo">
				</div>
				<div class="form-group">
					<label>Observaciones</label> 
					<textarea class="form-control" maxlength="128" name="texto" placeholder="128 caracteres como máximo"><?php echo $texto;?></textarea>
				</div>
<?php
				if (($id) and (isset($_GET['editar'])))
				{
?> 
					<input type="hidden" name="id" value="<?php echo $id;?>"> 
					<input class="btn btn-default" type="submit" name="nueva" value="Actualizar datos del Aula / Dependencia" /> 
<?php
				}
				else{
?> 
					<input class="btn btn-default" type="submit" name="nueva" value="Crear nueva Aula / Dependencia" /> 
<?php
				}
?>
			</form>
		</div>
	</div>
</div>
<?php include("../pie.php");?>

</body>
</html>









