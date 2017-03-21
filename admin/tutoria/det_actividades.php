<?php
require('../../bootstrap.php');

if (file_exists('config.php')) {
	include('config.php');
}


if(!$tutor){$tutor = $_SESSION['profi'];}


include("../../menu.php");
include("menu.php");
  
if (isset($_POST['id'])) {
	$id = $_POST['id'];
} 
elseif (isset($_GET['id'])) {
	$id = $_GET['id'];
} 
else
{
$id="";
}

if(stristr($_SESSION['cargo'],'1') == TRUE and strstr($tutor," ==> ")==TRUE){
$tr = explode(" ==> ",$tutor);
$tutor = $tr[0];
$unidad = $tr[1];
	}
else{
$SQL = "select unidad from FTUTORES where tutor = '$tutor'";
	$result = mysqli_query($db_con, $SQL);
	$row = mysqli_fetch_array($result);
	$unidad = $row[0];
}
?>
  <div align="center">
  <br><h3>Información completa de Actividad Extraescolar
  </h3><br />
  
  <?php

 	$datos0 = "select `nombre`, `descripcion`, `horaini`, `horafin`, `fechafin`, `departamento`, `fechareg`, `confirmado`, `observaciones` from calendario where id = '$id'";
	$datos1 = mysqli_query($db_con, $datos0);
	$datos = mysqli_fetch_array($datos1);
	$fecha0 = explode("-",$datos[4]);
	$fecha = "$fecha0[2]-$fecha0[1]-$fecha0[0]";
	$registro = $datos[6];
	?>
<div>
</div>
<div>
<table align="center" class="table table-bordered table-striped" style="width:800px;">
	<tr>
		<th colspan="2">
		<h4 class="text-info"><?php echo $datos[0];?></h4>
		</th>
	</tr>
	<tr>
		<th>Grupos</th>
		<td>
		<?php
		   	$consuni = "select distinct unidad from calendario_alumnos, alma where calendario_alumnos.claveal = alma.claveal and idcalendario = '$id'";
			$unirow = mysqli_query($db_con, $consuni);
			$cadena = "";
			while ($row = mysqli_fetch_assoc($unirow))
			{
				$cadena .= $row["unidad"].";";
			}
		 	echo substr($cadena,0,-1);
		 ?>
		 	
		 </td>
	</tr>
	<tr>
		<th>Descripción</th>
		<td><?php echo $datos[1];?></td>
	</tr>
	<tr>
		<th>Departamento</th>
		<td><?php echo $datos[5];?></td>
	</tr>
	<tr>
		<th>Profesores</th>
		<td>
		<?php
		   	$consprof = "select distinct nombre from calendario_profesores where idcalendario = '$id'";
			$profrow = mysqli_query($db_con, $consprof);
			$cadena = "";
			while ($row = mysqli_fetch_assoc($profrow))
			{
				$cadena .= $row["nombre"].";";
			}
		 	echo substr($cadena,0,-1);
		 ?>
	</tr>
	<tr>
		<th>Horario</th>
		<td><?php 
		if ($datos[2]=="00:00:00") {
			echo "Todo el día.";
		}
		else{
		echo $datos[2]." - ".$datos[3];
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
		if ($datos[7]=="0") {
			echo "NO";
		}
		else{
			echo "SÍ";
		}	
		?></td>
	</tr>
		<tr>
		<th>Observaciones</th>
		<td><?php echo $datos[8];?></td>
	</tr>
</table>
</div>
<br />
</div>
</body>
</html>