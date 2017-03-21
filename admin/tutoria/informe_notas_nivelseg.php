<?php
require('../../bootstrap.php');


include("../../menu.php");
include("menu.php");

include("../cabecerainforme.php");
?>

<?php
	$calcularaw = mysqli_query($db_con, "select estado from variables_estado where valor='suspensos1'");
	$calcula = mysqli_fetch_array($calcularaw);
	if ($calcula[0] == 0)
	{
		mysqli_query($db_con, "truncate suspensos". $key );
		mysqli_query($db_con, "truncate suspensosprof". $key );
		include( "../generartablainformesopcion2.php");
		mysqli_query($db_con, "update variables_estado set estado = 1 where valor = 'suspensos". $key ."'");
	}
foreach ($titulos as $key=>$val){

	
	$key == '0' ? $activ=" active" : $activ='';
?>
<div class="tab-pane fade in<?php echo $activ;?>" id="<?php echo "tab".$key;?>">
<h3>Resultados de los Alumnos por Grupo</h3><br />
<p class="help-block text-warning" align="left">En 4º de ESO y 2º de Bachillerato, los alumnos titulan con <strong>0</strong> asignaturas suspensas. En el resto de los grupos de ESO y Bachillerato los alumnos promocionan con <strong>2 o menos</strong> asignaturas suspensas. </p>
<table class="table table-striped table-bordered"  align="center" style="width:auto" valign="top">
<thead>
<th></th>
<th class='text-info'>Alumnos</th>
<th class='text-warning'>Repiten</th>
<th>0 Susp.</th>
<th>1-2 Susp.</th>
<th>3-5 Susp.</th>
<th>6-8 Susp.</th>
<th>9+ Susp.</th>
<th class='text-success'>Promo./Tit.</th>
</thead>
<tbody>
<?php

// UNIDADES DEL CURSO
	$curso = $_GET['curso'];
	$uno_do = mysqli_query($db_con, "select distinct claveal, grupo from suspensos". $key ." where nivel = '$curso' and suspensos > '0' and suspensos < '3'");
	$uno_dos='';
	$uno_dos=mysqli_num_rows($uno_do);

	$tres_cinc = mysqli_query($db_con, "select distinct claveal, grupo from suspensos". $key ." where nivel = '$curso' and suspensos > '2' and suspensos < '6'");
	$tres_cinco='';
	$tres_cinco=mysqli_num_rows($tres_cinc);

	$seis_och = mysqli_query($db_con, "select distinct claveal, grupo from suspensos". $key ." where nivel = '$curso' and suspensos > '5' and suspensos < '9'");
	$seis_ocho='';
	$seis_ocho=mysqli_num_rows($seis_och);

	$nuev = mysqli_query($db_con, "select distinct claveal, grupo from suspensos". $key ." where nivel = '$curso' and suspensos > '8'");
	$nueve='';
	$nueve=mysqli_num_rows($nuev);

	//$tota = mysqli_query($db_con, "select distinct notas.claveal from notas, alma where alma.claveal1 = notas.claveal and nivel = '$curso'");
	$tota = mysqli_query($db_con, "select distinct claveal from tcombasi, alma where tcombasi.claveal1 = alma.CLAVEAL1 and notas".$key." is not null and curso = '$curso'");
	$total='';
	$total=mysqli_num_rows($tota);
	$cero = $total - $uno_dos - $tres_cinco - $seis_ocho - $nueve;


// Promocion
	$extra1 = " and suspensos = '0'";
	$prom1 = mysqli_query($db_con, "select distinct claveal, grupo from suspensos". $key ." where nivel = '$curso' and grupo not like '1E%' and grupo not like '2E%' and grupo not like '3E%' and grupo not like '1B%'  $extra1");
	$promo1=mysqli_num_rows($prom1);
	if ($promo1==0) { $promo1=""; }

	$extra2 = " and suspensos < '3'";
	$prom2 = mysqli_query($db_con, "select distinct claveal, grupo from suspensos". $key ." where nivel = '$curso' and (grupo like '1E%' or grupo like '2E%' or grupo like '3E%' or grupo like '1B%')  $extra2");
	$promo2=mysqli_num_rows($prom2);
	if ($promo2==0) { $promo2=""; }

	$n_pil = mysqli_query($db_con, "select distinct claveal, grupo from suspensos". $key ." where nivel = '$curso' and pil > '1'");
	$num_pil='';
	$num_pil=mysqli_num_rows($n_pil);

	$porcient = (($promo1+$promo2)*100)/$total;
	$porciento='';
	if ($porcient>49) {
		$porciento = "<span class='text-success'>".substr($porcient,0,5)."%</span>";
	}
	else{
		$porciento = "<span class='text-danger'>".substr($porcient,0,5)."%</span>";	
	}

?>

<tr>
<th><?php echo $unidad;?></th>
<th class='text-info'><?php echo $total;?></th>
<td class='text-warning'><?php echo $num_pil;?></td>
<td><?php echo $cero;?></td>
<td><?php echo $uno_dos;?></td>
<td><?php echo $tres_cinco;?></td>
<td><?php echo $seis_ocho;?></td>
<td><?php echo $nueve;?></td>
<th class='text-success'><?php echo $porciento." <span class='pull-right'>(".$promo2."".$promo1.")</span>";?></th>
</tr>

</tbody>
</table>
<hr style="width:700px;" />
<br />
<!--  Estadísticas por asignatura -->
<br />
<br />
</div>
<?php
}
?>
</div>
</div>
</div>

<?php include("../../pie.php");?>
</body>
</html>
