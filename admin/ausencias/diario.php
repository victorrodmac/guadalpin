<?php
require('../../bootstrap.php');

include("../../menu.php");
include("menu.php");

function dia_anterior($fecha) 
{ 
    $sol =  strtotime($fecha) - 86400; 
    return date('Y-m-d', $sol); 
}
function dia_siguiente($fecha) 
{ 
    $sol = strtotime($fecha) + 86400; 
    return date('Y-m-d', $sol); 
}
// CALENDARIO

if (isset($_POST['cmp_fecha'])) {$fecha_ini = trim(mysqli_real_escape_string($db_con,$_POST['cmp_fecha']));}else{$fecha=date('d/m/Y');}
$hoy = date('d-m-Y');
$actual = isset($_GET['fecha'])  ? $_GET['fecha']  : date('Y-m-d');

//corrección del error de no mostrar nada si el mes empieza por 0

$semana = 1;

$man = dia_siguiente( $actual );
$ayer = dia_anterior( $actual );

$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
 
$actualf = $dias[date('w', strtotime($actual))]." ".date('d', strtotime($actual))." de ".$meses[date('n', strtotime($actual))-1]. " del ".date('Y', strtotime($actual)) ;
?>
<div class="container">
	<div class="row">
	<br />
		<div class="page-header">
			<h2>Ausencias del profesorado <small> Profesores ausentes</small></h2>
		</div>
		<div class="col-md-12 col-md-offset-2">
			<div class="col-md-8">
				<h2><small>Ausencias a fecha <?php echo $actualf; ?></small></h2>
			</div>
			<div class="btn-group col-md-4">
				<a href="?fecha=<?php echo $ayer; ?>" class="btn btn-default">&laquo;</a>
				<a href="?fecha=<?php echo $hoy; ?>" class="btn btn-default">Hoy</a>
				<a href="?fecha=<?php echo $man; ?>" class="btn btn-default">&raquo;</a>
			</div>
		</div>
		<div class="col-md-8 col-md-offset-2">
		<?php
			$exp_fechaini = explode('/', $fecha);
			$hoy = $exp_fechaini[2].'/'.$exp_fechaini[1].'/'.$exp_fechaini[0]; 

			// Consulta de datos del alumno.
			$result = mysqli_query($db_con, "select inicio, fin, tareas, id, profesor, horas from ausencias where date(inicio) <= '$actual' and date(fin) >= '$actual' order by inicio" );
			echo '<br /><table class="table table-striped table-bordered" style="width:100%;">';	
			echo "
			<thead>
			<th>1ª Hora</th>
			<th>2ª Hora</th>
			<th>3ª Hora</th>
			<th>4ª Hora</th>
			<th>5ª Hora</th>
			<th>6ª Hora</th>
			</thead><tbody>";
			while($row = mysqli_fetch_array ( $result ))
			{
			
				$profe_baja=$row[4];
				$tar = $row[2];
				$horas = $row[5];

				echo "<tr><th colspan='6' style='text-align:center'>";
				echo "$profe_baja";
				echo "</th></tr><tr>";
				$ndia = date ( "w", strtotime($actual) );
				for ($i=1;$i<7;$i++)
				{
					echo "<td>";
					if($horas==0 or mb_strstr($horas, $i))
					{
						$sql = "select a_asig, a_grupo, a_aula, c_asig from horw where prof = '$profe_baja' and dia = '$ndia' and hora = '$i'";
						$hor = mysqli_query($db_con, $sql);
						$hor_asig=mysqli_fetch_array($hor);
						if (mysqli_num_rows($hor) > '0')
						{
							echo "<p class='text-info'>Horario: $hor_asig[0]</p>";
							if (strlen($hor_asig[1]) > '1' and $hor_asig[3]!="25")
							{
								$hor2 = mysqli_query($db_con, "select a_grupo from horw where prof = '$profe_baja' and dia = '$ndia' and hora = '$i'");
								echo "<p class='text-success'>Grupos: ";
								while($hor_bj = mysqli_fetch_array($hor2))
								{
									echo $hor_bj[0]." ";
								}
								echo "</p>";
							}
							if (strlen($hor_asig[2] > '1'))
							{
								echo "<p class='text-warning'>Aula: <span style='font-weight:normal;'>$hor_asig[2]</p>";
							}
						}
					}
					echo "</td>";
				}
				echo "</tr>";
			}
			echo "</table>";
			echo "<br /><legend>Tareas para los Alumnos</legend>";
			$result2 = mysqli_query($db_con, "select inicio, fin, tareas, id, profesor, horas, archivo from ausencias  where date(inicio) <= '$hoy' and date(fin) >= '$hoy' order by inicio" );
			while($row2 = mysqli_fetch_array ( $result2 ))
			{
				$profe_baja=$row2[4];
				$tar = $row2[2];
				if (strlen($tar) > '1')
				{
					echo '<table class="table table-striped table-bordered">';	
					echo "
					<tr><th class='text-center'>$profe_baja</th></tr>
					<tr><td>$tar</td></tr>";
					if (strlen($row2[6])>0) {
						echo "<tr class='info'><td>Archivo adjunto:&nbsp; <a href='archivos/$row2[6]'><i class='fa fa-file-o'> </i> $row2[6]</a></td></tr>";
					}
					echo "</table><br />";
				}
			}
			
		?>
		</div>
	</div>
</div>

	<?php include("../../pie.php"); ?>
	
</body>
</html>
