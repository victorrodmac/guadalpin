<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); ?>
<hr />
<br />
<h3>Resultados por Materias</h3><br />

<?php
//$titulos = array("3"=>"Evaluación Ordinaria");
//foreach ($titulos as $key=>$val){
// Tabla temporal.

// se elimina ya que esta información esta en tcombasi	
/* $crea_tabla2 = "CREATE TABLE  `temp` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`claveal` VARCHAR( 12 ) NOT NULL ,
`asignatura` INT( 8 ) NOT NULL ,
`nota` TINYINT( 2 ) NOT NULL ,
INDEX (  `claveal` )
) ENGINE = MyISAM";
 mysqli_query($db_con, $crea_tabla2);  
 mysqli_query($db_con, "ALTER TABLE  `temp` ADD INDEX (  `asignatura` )");*/
?>
<?php
$key = "3";
$val = "Evaluación Ordinaria";
$todos="";
$notas1 = mysqli_query($db_con, "select * from tcombasi where notas3 not like '' limit 1");
$result1 = mysqli_query($db_con, $notas1);
$todos = mysqli_num_rows($result1);
if ($todos < '1' or $todos == "") {
	echo '<div align="center"><div class="alert alert-warning alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h5>ATENCIÓN:</h5>No se han registrado las calificaciones de la Evaluación Ordinaria todavía. Es importante que <strong><u>no imprimas</u></strong> la Memoria de Tutoría hasta que las notas de la Evaluación Ordinaria se hayan registrado. De lo contrario, las Estadísticas de la Evaluación Ordinaria no aparecerán en la Memoria.
          </div></div>';
}


?>

<table class="table table-striped table-bordered"  align="center" style="width:800px;" valign="top">
<thead>
<th class='text-info'>Asignatura</th>
<th class='text-info'>Matriculados</th>
<th class='text-info'>Suspensos</th>
<th class='text-info'>Aprobados</th>
</thead>
<tbody>	
	<?php
$sql = "select distinct asignaturas.nombre, asignaturas.codigo from asignaturas, profesores where profesores.materia = asignaturas.nombre
 and asignaturas.curso = '".$config['curso_actual']."' and profesores.grupo = '".$_SESSION['mod_tutoria']['unidad']."' and abrev not like '%\_%' and asignaturas.codigo not in (select distinct asignaturas.codigo from asignaturas where asignaturas.nombre like 'Libre Disp%')";
//echo $sql;	
$as = mysqli_query($db_con, $sql);
while ($asi = mysqli_fetch_array($as)) {
	$nomasi = $asi[0];
	$codasi = $asi[1];
	$cod_nota = mysqli_query($db_con, "select claveal1 from tcombasi, alma,calificaciones where calificaciones.codigo = notas3 and asignatura = '$codasi' and calificaciones.nota < '5' and calificaciones.nota <> '10' and alma.claveal1 = tcombasi.claveal1 and unidad = '".$_SESSION['mod_tutoria']['unidad']."'");
	$cod_apro = mysqli_query($db_con, "select claveal1 from tcombasi, alma ,calificaciones where calificaciones.codigo = notas3 and asignatura = '$codasi' and (calificaciones.nota > '4' or calificaciones.nota = '10') and alma.claveal1 = tcombasi.claveal1 and unidad = '".$_SESSION['mod_tutoria']['unidad']."'");
	
	//echo "select id from temp where asignatura = '$codasi'<br>";
	$num_susp='';
	$num_susp = mysqli_num_rows($cod_nota);
	$num_apro='';
	$num_apro = mysqli_num_rows($cod_apro);
	$combas = mysqli_query($db_con, "select claveal from alma, tcombasi where cmateria = '$codasi' and alma.claveal1 = tcombasi.claveal1 and unidad = '".$_SESSION['mod_tutoria']['unidad']."'");
	//echo "select claveal from alma where combasi like '%$codasi%' and unidad = '$unidad'<br>";
	$num_matr='';
	$num_matr = mysqli_num_rows($combas);
	
	$porcient_asig = ($num_susp*100)/$num_matr;
	$porciento_asig='';
if ($porcient_asig>49) {
	$porciento_asig = "<span class='text-success'>".substr($porcient_asig,0,4)."%</span>";
}
else{
	$porciento_asig = "<span class='text-danger'>".substr($porcient_asig,0,4)."%</span>";	
}
	
	$porcient_asig2 = ($num_apro*100)/$num_matr;
	$porciento_asig2='';
if ($porcient_asig2>49) {
	$porciento_asig2 = "<span class='text-success'>".substr($porcient_asig2,0,4)."%</span>";
}
else{
	$porciento_asig2 = "<span class='text-danger'>".substr($porcient_asig2,0,4)."%</span>";	
}

if ($porcient_asig>0) {
			echo "<tr><th>$nomasi</th><td>$num_matr</td><td>";
	echo $porciento_asig."<span class='pull-right'>(".$num_susp.")</span></td><td>$porciento_asig2 <span class='pull-right'>(".$num_apro.")</span></td></tr>";
	}

}
?>
</tbody>
</table>
<br />
<hr />
<?php
?>

<?php
mysqli_query($db_con, "drop table temp");
?>

