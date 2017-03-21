<?php
require('../../bootstrap.php');

if (file_exists('config.php')) {
	include('config.php');
}


include("../../menu.php");
include("menu.php");

include("../informes/informe_notas_grupo.php");
/*
?>
<br />
<div align="center" style="max-width:920px;margin:auto;">
<div class="page-header">
  <h2>Informe de Evaluaciones por Grupo <small> Estad�sticas de Calificaciones</small></h2>
</div>

<div class="tabbable" style="margin-bottom: 18px;">
<ul class="nav nav-tabs">
<li class="active"><a href="#tab1" data-toggle="tab">1� Evaluaci�n</a></li>
<li><a href="#tab2" data-toggle="tab">2� Evaluaci�n</a></li>
<li><a href="#tab3" data-toggle="tab">Evaluaci�n Ordinaria</a></li>
<li><a href="#tab4" data-toggle="tab">Evaluaci�n Extraordinaria</a></li>
<li><a href="#tab5" data-toggle="tab">Primera Evaluaci�n Excepcional</a></li>
<li><a href="#tab6" data-toggle="tab">Segunda Evaluaci�n Excepcional</a></li>
</ul>

<div class="tab-content" style="padding-bottom: 9px; border-bottom: 1px solid #ddd;">
<br>
<?php 
// Comprobamos datos de evaluaciones
$n1 = mysqli_query($db_con, "select * from notas where notas0 not like '' or notas1 not like ''");
if(mysqli_num_rows($n1)>0){}
else{
	echo '<div align="center"><div class="alert alert-warning alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h5>ATENCI�N:</h5>No hay datos de Calificaciones en la tabla NOTAS. Debes importar las Calificaciones desde S�neca (Administraci�n de la Intranet --> Importar Calificaciones) para que este m�dulo funcione.
          </div></div>';
	exit();
}
?>


<?php
$unidad = $_GET['unidad'];
$titulos = array("0"=>"Eval. Inicial","1"=>"1� Evaluaci�n","2"=>"2� Evaluaci�n","3"=>"Evaluaci�n Ordinaria","4"=>"Evaluaci�n Extraordinaria","5"=>"Primera Evaluaci�n Excepcional","6"=>"Segunda Evaluaci�n Excepcional");
foreach ($titulos as $key=>$val){

// Tabla temporal.
 $crea_tabla2 = "CREATE TABLE  `temp` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`claveal` VARCHAR( 12 ) NOT NULL ,
`asignatura` INT( 8 ) NOT NULL ,
`nota` TINYINT( 2 ) NOT NULL ,
INDEX (  `claveal` )
) ENGINE = MyISAM";
 mysqli_query($db_con, $crea_tabla2); 
 mysqli_query($db_con, "ALTER TABLE  `temp` ADD INDEX (  `asignatura` )");
$key == '0' ? $activ=" active" : $activ='';
?>
<div class="tab-pane fade in<?php echo $activ;?>" id="<?php echo "tab".$key;?>">
<?php

	$rep = ""; 
	$promo = "";
	$todos="";
$notas1 = "select notas". $key .", claveal1, matriculas, unidad, curso from alma, notas where alma.CLAVEAL1 = notas.claveal and alma.unidad = '$unidad'";
//echo $notas1."<br>";

$result1 = mysqli_query($db_con, $notas1);
$todos = mysqli_num_rows($result1);
if ($todos < '1') {
echo '<div align="center"><div class="alert alert-warning alert-block fade in" style="max-width:920px">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h5>ATENCI�N:</h5>No hay datos de Calificaciones del Curso <strong class=text-danger>'.$curso.'</strong>. 
          </div></div>';
}
while($row1 = mysqli_fetch_array($result1)){
$asignatura1 = substr($row1[0], 0, strlen($row1[0])-1);
$claveal = $row1[1];
$grupo = $row1[3];
$nivel_curso = $row1[4];
if ($row1[2]>"1") {
	$pil = "1";
}
else{
	$pil = '0';
}
$trozos1 = explode(";", $asignatura1);
$num = count($trozos1);
$susp="";
 for ($i=0;$i<$num; $i++)
  {
$bloque = explode(":", $trozos1[$i]);
$nombreasig = "select nombre from calificaciones where codigo = '" . $bloque[1] . "'";
$asig = mysqli_query($db_con, $nombreasig);
$cali = mysqli_fetch_row($asig);
if($cali[0] < '5' and !($cali[0] == ''))	{
	$susp+=1; 
	}
		mysqli_query($db_con, "insert into temp values('','$claveal','$bloque[0]','$cali[0]')");
	}
	}


?>
<h3>Resultados de los Alumnos por Materias y Grupo</h3><br />

	<legend><?php echo $unidad; ?></legend><hr />

<table class="table table-striped table-bordered"  align="center" style="width:700px;" valign="top">
<thead>
<th class='text-info'>Asignatura</th>
<th class='text-info'>Matriculados</th>
<th class='text-info'>Suspensos</th>
<th class='text-info'>Aprobados</th>
</thead>
<tbody>	
	<?php
$sql = "select distinct asignaturas.nombre, asignaturas.codigo from asignaturas, profesores where profesores.materia = asignaturas.nombre
 and asignaturas.curso = '$nivel_curso' and profesores.grupo = '$unidad' and abrev not like '%\_%' and asignaturas.codigo not in 
(select distinct asignaturas.codigo from asignaturas where asignaturas.nombre like 'Libre Disp%')";
//echo $sql;	
$as = mysqli_query($db_con, $sql);
while ($asi = mysqli_fetch_array($as)) {
	$n_c = mysqli_query($db_con, "select distinct nivel from alma where curso = '$nivel_curso'");
	$niv_cur = mysqli_fetch_array($n_c);
	$nomasi = $asi[0];
	$codasi = $asi[1];
	$cod_nota = mysqli_query($db_con, "select id from temp, alma where asignatura = '$codasi' and nota < '5' and alma.claveal1 = temp.claveal and unidad = '$unidad'");
	$cod_apro = mysqli_query($db_con, "select id from temp, alma where asignatura = '$codasi' and nota > '4' and alma.claveal1 = temp.claveal and unidad = '$unidad'");
	
	//echo "select id from temp where asignatura = '$codasi'<br>";
	$num_susp='';
	$num_susp = mysqli_num_rows($cod_nota);
	$num_apro='';
	$num_apro = mysqli_num_rows($cod_apro);
	$combas = mysqli_query($db_con, "select claveal from alma where combasi like '%$codasi%' and unidad = '$unidad'");
	//echo "select claveal from alma where combasi like '%$codasi%'  and nivel like '$niv_cur[0]%' and curso = '$orden_nivel[1]'<br>";
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

</div>
<?php
mysqli_query($db_con, "drop table temp");
}
?>
</div>
</div>
</div>

	<?php include("../../pie.php"); ?>
	
</body>
</html>
*/ ?>