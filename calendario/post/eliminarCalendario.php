<?php
require('../../bootstrap.php');

$GLOBALS['db_con'] = $db_con;


if (! isset($_POST['cmp_calendario_id'])) {
	die("<h1>FORBIDDEN</h1>");
	exit();
}

$calendario_id = mysqli_escape_string($db_con, $_POST['cmp_calendario_id']);

// Comprobamos si existen eventos y los eliminamos
$result = mysqli_query($db_con, "SELECT id FROM calendario WHERE categoria=$calendario_id");
while ($row = mysqli_fetch_assoc($result)) {
	$consdalumnos = "delete from calendario_alumnos where idcalendario = ".$row['id'];
	//echo $consdalumnos;
	mysqli_query($db_con, $consdalumnos);
	$consdprofesores = "delete from calendario_profesores where idcalendario = ".$row['id'];
	//echo $consdprofesores;
	mysqli_query($db_con, $consdprofesores);
	$consdasignaturas = "delete from calendario_asignaturas where idcalendario = ".$row['id'];
	//echo $consdasignaturas;
	mysqli_query($db_con, $consdasignaturas);
	$consdnotascuad = "delete from notas_cuaderno where idcalendario = ".$row['id'];
	//echo $consdnotascuad;
	mysqli_query($db_con, $consdnotascuad);

	mysqli_query($db_con, "DELETE FROM calendario WHERE id=".$row['id']." LIMIT 1");
}

$result = mysqli_query($db_con, "DELETE FROM calendario_categorias WHERE id=$calendario_id");
if (! $result) {
	header('Location:'.'http://'.$config['dominio'].'/'.$config['path'].'/calendario/index.php?mes='.$_GET['mes'].'&anio='.$_GET['anio'].'&msg=ErrorEliminarCalendario');
	exit();
}
else {
	header('Location:'.'http://'.$config['dominio'].'/'.$config['path'].'/calendario/index.php?mes='.$_GET['mes'].'&anio='.$_GET['anio'].'');
	exit();
}
?>