<?php
require('../../bootstrap.php');


include("../../menu.php");
include("menu.php");
include("cabecerainforme.php");
?>
<?php
$bloquear = 1;
$calcularaw = mysqli_query($db_con, "select id, estado from variables_estado where valor like '%suspensos%' and estado = 1");
$calcula = mysqli_num_rows($calcularaw);
if ($calcula > 0)
{
	$bloquear = creatablas( $db_con, $bloquear, $calcularaw );		
}
if ($bloquear == 1)
{
	foreach ($titulos as $key=>$val)
	{	
		$key == '0' ? $activ=" active" : $activ='';
?>
		<div class="tab-pane fade in<?php echo $activ;?>" id="<?php echo "tab".$key;?>">
			<h3>Resultados de las Materias por Nivel</h3><br />
	<?php
		$profq = mysqli_query($db_con, "select distinct cprof, nprof from suspensosprof". $key ." order by nprof");
		while ($rprof = mysqli_fetch_array($profq))
		{
	?>
			<legend><?php echo $rprof[1]; ?></legend>
			<table class="table table-striped table-bordered"  align="center" style="width:700px;" valign="top">
			<thead>
				<th class='text-info'>Grupo</th>
				<th class='text-info'>Asignatura</th>
				<th class='text-info'>Matriculados</th>
				<th class='text-info' nowrap>Aprob.(%)</th>
				<th class='text-info' nowrap>Al. Aprob.</th>
			</thead>
			<tbody>	
		<?php
			$consulta = "select distinct apr.c, sus.c, sus.unidad, asig from (((select distinct asig, a_grupo, c_prof, asignatura from horw inner join suspensosprof". $key ." on horw.a_grupo = suspensosprof". $key .".unidad and c_prof = cprof and asignatura = c_asig where c_prof = '$rprof[0]') as p ) left join (select count(nprof) as c, unidad, asignatura,cprof from suspensosprof". $key ." where cprof = '$rprof[0]' and nota < '5' and nota <> '10' group by unidad, asignatura, cprof) as sus on p.a_grupo = sus.unidad and sus.cprof = p.c_prof and p.asignatura = sus.asignatura) left join (select count(nprof) as c, unidad, asignatura from suspensosprof". $key ." where cprof = '$rprof[0]' and (nota > '4' or nota = '10') group by unidad, asignatura) as apr on apr.unidad = sus.unidad and apr.asignatura = sus.asignatura";
			//echo $consulta;
			$cons = mysqli_query($db_con, $consulta);
			
			while ($rcons = mysqli_fetch_array($cons)) 
			{
				$num_apro=$rcons[0];
				$num_susp=$rcons[1];
				$nomgrupo = $rcons[2];
				$nomasi = $rcons[3];
				$num_matr = $rcons[0]+$rcons[1];
			
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

				if ($num_matr>0 and stristr($nomasi,"Tutor")==FALSE) {
					echo "<tr><th>$nomgrupo</th><td>$nomasi</td><td>$num_matr</td><td>";
					echo $porciento_asig2 ."</td><td>".$num_apro."</td></tr>";
				}

			}
		?>
			</tbody>
			</table>
		<br />
		<hr />
		<?php
		}
		?>
	</div>
	<?php
	}
}
else
{
?>
	<div class="tab-pane fade in" id="<?php echo "tab0";?>">
		<h3>Resultados de los Alumnos por Grupo</h3><br />
		<p class="help-block text-warning" align="left">No se puede mostrar el informe debido a que alguien esta generandolo en este momento. Por favor, espera un minuto antes de volver a intentarlo</p>
	</div>
<?php
}
?>
</div>
</div>
</div>
</div>

<?php include("../../pie.php");?>
</body>
</html>