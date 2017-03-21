<?php
require('../../bootstrap.php');


$profesor = $_SESSION['profi'];

if (isset($_POST['n_dia'])) {$n_dia = $_POST['n_dia'];} elseif (isset($_GET['n_dia'])) {$n_dia = $_GET['n_dia'];} else{$n_dia="";}
if ($n_dia == 'Lunes') {	$dia = '1';}
if ($n_dia == 'Martes') { $dia = '2';}
if ($n_dia == 'Mi�rcoles') {	$dia = '3';}
if ($n_dia == 'Jueves') {	$dia = '4';}
if ($n_dia == 'Viernes') {	$dia = '5';}

include("../../menu.php");
?>

<div class="container">

	<div class="page-header">
		
		<h2 style="display: inline;"><?php echo $n_dia; ?> <small>Consulta de aulas libres</small></h2> 
		
		<?php $dias = array('Lunes','Martes','Mi�rcoles','Jueves','Viernes'); ?>
		<form class="pull-right col-sm-2" method="post" action="">
			<select class="form-control" id="n_dia" name="n_dia" onChange="submit()">
				<?php for($i = 0; $i < count($dias); $i++): ?>
					<option value="<?php echo $dias[$i]; ?>" <?php echo ($dias[$i] == $n_dia) ? 'selected' : ''; ?>><?php echo $dias[$i]; ?></option>
				<?php endfor; ?>
			</select>
		</form>
		
	</div><!-- /.page-header -->

	<!-- SCAFFOLDING -->
	<div class="row">
	
		<div class="col-sm-12">
		
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
						<?php
						$hr = mysqli_query($db_con,"select hora_inicio, hora_fin from tramos where hora < 7 and hora not like 'R'");
						while ($hor = mysqli_fetch_array($hr)) {
							echo "<th>$hor[0] - $hor[1]</th>";
						}
						?>
						</tr>
					</thead>
					<tbody>
						<tr>
<?php 
						for($i = 1; $i < 7; $i++)
						{ 
							echo '<td>';
								$sql = "SELECT DISTINCT aulas.a_aula, aulas.n_aula FROM horw, aulas WHERE horw.a_aula = aulas.a_aula and aulas.seneca = 1 and c_asig NOT LIKE '25' AND aulas.a_aula NOT LIKE '' AND aulas.a_aula NOT LIKE 'ACO%' AND aulas.a_aula NOT LIKE 'DI%' ORDER BY aulas.n_aula ASC";
								$result = mysqli_query($db_con, $sql); 
								while ($row = mysqli_fetch_array($result))
								{
									$sql = "SELECT a_grupo FROM horw where a_aula = '$row[0]' AND dia='$dia' AND hora='$i' AND c_asig NOT LIKE '25' ORDER BY a_grupo ASC";
									$grupo = mysqli_query($db_con, $sql); 
									$asig = mysqli_fetch_array($grupo);
									if($asig['a_grupo'] == '')
									{ 
										echo '<p><a href="hor_aulas.php?aula='.$row['n_aula'].'">'.$row['n_aula'].'</a></p>';
									} 
								} 
							echo '</td>';
						} 
?>
						</tr>
					</tbody>
				</table>
			</div>
		
			<div class="hidden-print">
				<a class="btn btn-primary" href="#" onclick="javascript:print();">Imprimir</a>
				<a class="btn btn-default" href="chorarios.php">Volver</a>
			</div>
			
		</div><!-- /.col-sm-12 -->
	
	</div><!-- /.row -->

</div><!-- /.container -->

	<?php include("../../pie.php"); ?>

</body>
</html>
