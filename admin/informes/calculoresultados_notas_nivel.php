<?php
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
<th><?php echo $curso;?></th>
<th class='text-info'><?php echo $total;?></th>
<td class='text-warning'><?php echo $num_pil;?></td>
<td><?php echo $cero;?></td>
<td><?php echo $uno_dos;?></td>
<td><?php echo $tres_cinco;?></td>
<td><?php echo $seis_ocho;?></td>
<td><?php echo $nueve;?></td>
<th><?php echo $porciento." <span class='pull-right'>(".$promo2."".$promo1.")</span>";?></th>
</tr>