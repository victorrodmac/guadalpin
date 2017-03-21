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
		$key == '1' ? $activ=" active" : $activ='';
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
				<th class='text-info'>Matriculados</th>
				<th class='text-info' nowrap>Aprob.(%)</th>
				<th class='text-info' nowrap>Al. Aprob.</th>
			</thead>
			<tbody>	
			<?php
			$as = mysqli_query($db_con, "select asignaturas.nombre, asignaturas.codigo from asignaturas where curso = '$orden_nivel[0]' and abrev not like '%\_%' and asignaturas.codigo not in (select distinct codigo from asignaturas where nombre like 'Refuerz%')");
			while ($asi = mysqli_fetch_array($as)) 
			{
				$nomasi = $asi[0];
				$codasi = $asi[1];
				$cod_apro = mysqli_query($db_con, "select count(nota) from suspensosprof". $key .", alma where asignatura = '$codasi' and (nota > '4' or nota = '10') and alma.claveal1 = suspensosprof". $key .".claveal and curso = '$orden_nivel[0]'");
				$na = mysqli_fetch_array($cod_apro);
				$num_apro= $na[0];
				$combas = mysqli_query($db_con, "select distinct suspensosprof". $key .".claveal from suspensosprof". $key ." where claveal in (select tcombasi.claveal1 from tcombasi, alma where alma.claveal1 = tcombasi.claveal1 and cmateria = '$codasi' and curso = '$orden_nivel[0]')");
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

				if ($num_matr>0 and stristr($nomasi,"Tutor")==FALSE) 
				{
					echo "<tr><th>$nomasi</th><td>$num_matr</td><td>";
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