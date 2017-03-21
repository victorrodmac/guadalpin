<p class="lead"><?php echo $unidad; ?></p>
<table class="table table-striped table-bordered"  align="center" style="width:700px;" valign="top">
<thead>
<th class='text-info'>Asignatura</th>
<th class='text-info'>Matriculados</th>
<th class='text-info'>Aprob. (%)</th>
<th class='text-info'>Al. Aprob.</th>
</thead>
<tbody>	
<?php
	$sql = "select distinct asignaturas.nombre, asignaturas.codigo from asignaturas, profesores where profesores.materia = asignaturas.nombre
	 and asignaturas.curso = '$curso' and profesores.grupo = '$unidad' and abrev not like '%\_%' and asignaturas.codigo not in 
	(select distinct asignaturas.codigo from asignaturas where asignaturas.nombre like 'Refuerz%')";
	//echo $sql;	
	$as = mysqli_query($db_con, $sql);
	while ($asi = mysqli_fetch_array($as)) {
		$n_c = mysqli_query($db_con, "select distinct nivel from alma where curso = '$curso'");
		$niv_cur = mysqli_fetch_array($n_c);
		$nomasi = $asi[0];
		$codasi = $asi[1];
		$nivel_curso=$orden_nivel[1];
		if (stristr($nivel_curso,"3") and stristr($codasi,"25201") and $key==3) {
			$codasi="25177";
			$nomasi="Ciencias de la Naturaleza";
		}
		if (stristr($nivel_curso,"3") and (stristr($codasi,"25200")) and $key==3) {
			$codasi="";
			$nomasi="";
		}
		if (!($codasi=="")) {		
			$cod_nota = mysqli_query($db_con, "select count(nota) from suspensosprof". $key ." where asignatura = '$codasi' and nota < '5' and nota <> '10' and unidad = '$unidad'");
			$cod_apro = mysqli_query($db_con, "select count(nota) from suspensosprof". $key ." where asignatura = '$codasi' and (nota > '4' or nota = '10') and unidad = '$unidad'");
			$ns = mysqli_fetch_array($cod_nota);
			$num_susp= $ns[0];
			$na = mysqli_fetch_array($cod_apro);
			$num_apro= $na[0];
			$tota = mysqli_query($db_con, "select distinct claveal from tcombasi, alma where tcombasi.claveal1 = alma.CLAVEAL1 and notas".$key." is not null and unidad = '$unidad'");
			$num_matr='';
			$num_matr=mysqli_num_rows($tota);	
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

			if ($num_matr>0) {
				echo "<tr><th>$nomasi</th><td>$num_matr</td><td>";
				echo $porciento_asig2."</td><td>".$num_apro."</span></td></tr>";
			}
		}
	}
?>
</tbody>
</table>
<br />
<hr />
