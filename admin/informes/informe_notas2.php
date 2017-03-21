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
		<h3>Resultados de los Alumnos por Grupo</h3><br />
		<p class="help-block text-warning" align="left">En 4º de ESO y 2º de Bachillerato, los alumnos titulan con <strong>0</strong> asignaturas suspensas. En el resto de los grupos de ESO y Bachillerato los alumnos promocionan con <strong>2 o menos</strong> asignaturas suspensas. </p>
	<?php

	// CURSOS

		$nivele = mysqli_query($db_con, "select distinct curso from alma order by curso");
		while ($orden_nivel = mysqli_fetch_array($nivele))
		{
			echo '<legend>'.$orden_nivel[0].'</legend>';
	?>	
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
			<th>Promo.</th>
			<th>Tit.</th>
		</thead>
		<tbody>
	<?php

	// UNIDADES DEL CURSO

			$niv = mysqli_query($db_con, "select distinct curso, unidad from alma where curso = '$orden_nivel[0]' order by unidad");
			//echo "select distinct curso, unidad from alma where curso = '$orden_nivel[0]' order by unidad";
			while ($ni = mysqli_fetch_array($niv)) 
			{
				$unidad = $ni[1];
				
				$idn = $ini[3];
				if ($idn=="101140") { $nivel="1E"; }
				elseif ($idn=="101141") { $nivel="2E"; }
				elseif ($idn=="101142") { $nivel="3E"; }
				elseif ($idn=="6029" or $idn=="2063") { $nivel="1B"; }
				else{ $nivel = $ni[1]; }
				$n_grupo+=1;
				
				$n_grupo+=1;
				$curso = $ni[0];
				$grupo = $ni[1];
				$rep = ""; 
				$promo = "";
				
		// Calculamos
		/*		$cer = mysqli_query($db_con, "select distinct claveal, grupo from suspensos". $key ." where grupo = '$grupo' and suspensos = '0'");
				$cero = '';
				$cero=mysqli_num_rows($cer);*/

				$uno_do = mysqli_query($db_con, "select distinct claveal, grupo from suspensos". $key ." where grupo = '$grupo' and suspensos > '0' and suspensos < '3'");
				$uno_dos='';
				$uno_dos=mysqli_num_rows($uno_do);

				$tres_cinc = mysqli_query($db_con, "select distinct claveal, grupo from suspensos". $key ." where grupo = '$grupo' and suspensos > '2' and suspensos < '6'");
				$tres_cinco='';
				$tres_cinco=mysqli_num_rows($tres_cinc);

				$seis_och = mysqli_query($db_con, "select distinct claveal, grupo from suspensos". $key ." where grupo = '$grupo' and suspensos > '5' and suspensos < '9'");
				$seis_ocho='';
				$seis_ocho=mysqli_num_rows($seis_och);

				$nuev = mysqli_query($db_con, "select distinct claveal, grupo from suspensos". $key ." where grupo = '$grupo' and suspensos > '8'");
				//echo "select distinct claveal, grupo from suspensos". $key ." where grupo = '$grupo' and suspensos > '0' and suspensos < '3'";
				$nueve='';
				$nueve=mysqli_num_rows($nuev);

				//$tota = mysqli_query($db_con, "select distinct notas.claveal from notas, alma where alma.claveal1 = notas.claveal and grupo = '$grupo'");
				$tota = mysqli_query($db_con, "select distinct claveal from tcombasi, alma where tcombasi.claveal1 = alma.CLAVEAL1 and notas".$key." is not null and unidad = '$grupo'");
				$total='';
				$total=mysqli_num_rows($tota);
				$cero = $total - $uno_dos - $tres_cinco - $seis_ocho - $nueve;
				/*$tota = mysqli_query($db_con, "select distinct claveal from suspensos". $key ." where grupo = '$grupo'");
				$total='';
				$total=mysqli_num_rows($tota);*/

				// Promocion
				$extra1 = " and suspensos = '0'";
				$prom1 = mysqli_query($db_con, "select distinct suspensos". $key .".claveal, suspensos". $key .".grupo from suspensos". $key .", alma where suspensos". $key .".nivel = curso and suspensos". $key .".grupo = '$grupo' and (curso like '4%' or curso like '2º de bach%') $extra1");
				$promo1=mysqli_num_rows($prom1);
				if ($promo1==0) { $promo1=""; }

				$extra2 = " and suspensos < '3'";
				$prom2 = mysqli_query($db_con, "select distinct suspensos". $key .".claveal, suspensos". $key .".grupo from suspensos". $key .", alma where BINARY suspensos". $key .".nivel = BINARY curso and suspensos". $key .".grupo = '$grupo' and (curso not like '4%' and curso not like '2º de bach%')  $extra2");
				$promo2=mysqli_num_rows($prom2);
				if ($promo2==0) { $promo2=""; }

				$n_pil = mysqli_query($db_con, "select distinct claveal, grupo from suspensos". $key ." where grupo = '$grupo' and pil = '1'");
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
			<th><?php echo $porciento."</th><th>".$promo2."".$promo1."";?></th>
			</tr>
	<?php

			}

	?>
		</tbody>
		</table>
		<hr style="width:700px;" />
		<br />
	<?php

		}
	?>
	<!--  Estadísticas por asignatura -->
		<br />
		<br />
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
