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
		$nivele = mysqli_query($db_con, "select distinct curso from alma order by curso");
		while ($orden_nivel = mysqli_fetch_array($nivele))
		{
	?>
		<legend><?php echo $orden_nivel[0]; ?></legend>
		<table class="table table-striped table-bordered"  align="center" style="width:700px;" valign="top">
		<thead>
			<th class='text-info'>Asignatura</th>
			<th class='text-info'>Profesor</th>
			<th class='text-info'>grupo</th>
			<th class='text-info'>Matriculados</th>
			<th class='text-info' nowrap>Aprob.(%)</th>
			<th class='text-info' nowrap>Al. Aprob.</th>
		</thead>
		<tbody>	
		<?php
			$as = mysqli_query($db_con, "select asignaturas.nombre, asignaturas.codigo from asignaturas where curso = '$orden_nivel[0]' and abrev not like '%\_%' and asignaturas.codigo not in (select distinct codigo from asignaturas where nombre like 'Refuerz%')");
			while ($asi = mysqli_fetch_array($as)) 
			{
				$niv_cur = $orden_nivel[0];
				$nomasi = $asi[0];
				$codasi = $asi[1];
				$cprofquery = mysqli_query($db_con, "select distinct nprof, cprof, unidad from suspensosprof". $key ." where asignatura = '$codasi' ");
				//echo "select distinct nprof, cprof, grupo from suspensosprof". $key ." where asignatura = '$codasi' ";
				$desdoble = mysqli_num_rows($cprofquery);
				while ($cprofraw = mysqli_fetch_array($cprofquery)) 
				{
					$nomprof = $cprofraw[0];
					$codprof = $cprofraw[1];
					$profgrupo = $cprofraw[2];

					$cod_nota = mysqli_query($db_con, "select nota from suspensosprof". $key .", alma where asignatura = '$codasi' and nota < '5' and nota <> '10' and alma.claveal1 = suspensosprof". $key .".claveal and curso = '$orden_nivel[0]' and alma.unidad = '$profgrupo' ");
					$cod_apro = mysqli_query($db_con, "select nota from suspensosprof". $key .", alma where asignatura = '$codasi' and (nota > '4' or nota = '10') and alma.claveal1 = suspensosprof". $key .".claveal and curso = '$orden_nivel[0]' and alma.unidad = '$profgrupo' ");
					$num_susp='';
					$num_susp = mysqli_num_rows($cod_nota);
					$num_apro='';
					$num_apro = mysqli_num_rows($cod_apro);
					//echo "select distinct suspensosprof". $key .".claveal from suspensosprof". $key ." where claveal in (select tcombasi.claveal1 from tcombasi, alma where alma.claveal1 = tcombasi.claveal1 and alma.unidad = '$profgrupo' and curso = '$orden_nivel[0]' and cmateria = '$codasi')";
					$combas = mysqli_query($db_con, "select distinct suspensosprof". $key .".claveal from suspensosprof". $key ." where claveal in (select tcombasi.claveal1 from tcombasi, alma where alma.claveal1 = tcombasi.claveal1 and alma.unidad = '$profgrupo' and curso = '$orden_nivel[0]' and cmateria = '$codasi')");
					$num_matr='';
					$num_matr = mysqli_num_rows($combas);
					if ($num_matr < $num_apro )
					{
						//Esta asignatura tiene un desdoble con lo que hay que dividir entre 2
						$num_susp = $num_susp / $desdoble;
						$num_apro = $num_apro / $desdoble;
					}
					
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
					if ($num_matr>0 and stristr($nomasi,"Tutor")==FALSE) 
					{
						if ($nomasiant == $nomasi)
						{
							echo "<tr><th></th>";
						}
						else
						{
							echo "<tr><th>$nomasi</th>";
						}
						if ($nomprofant == $nomprof)
						{
							echo "<td></td>";
						}
						else
						{
							echo "<td>$nomprof</td>";
						}
						echo "<td>$profgrupo</td><td>$num_matr</td><td>";
						echo $porciento_asig2 ."</td><td>".$num_apro."</td></tr>";
						$nomasiant = $nomasi;
						$nomprofant = $nomprof;
					}
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