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
		<h3>Resultados de los Alumnos por Materias / Nivel / Grupo:</h3>
		<span class="help-block"> ( * ) En color <strong class="text-success">verde</strong> los aprobados; en color <strong class="text-warning">naranja</strong> los suspensos</span>
		<br />
	<?php
		$nivele = mysqli_query($db_con, "select distinct curso from alma order by curso");
		while ($orden_nivel = mysqli_fetch_array($nivele))
		{
	?>
			<legend><?php echo $orden_nivel[0]; ?></legend>
			<table class="table table-striped table-condensed table-bordered"  align="center" style="width:700px;" valign="top">
			<tr><th></th>
	<?php
			$sql_asig = "select distinct unidad from alma where curso = '$orden_nivel[0]' order by unidad";
			$query_asig = mysqli_query($db_con, $sql_asig);
			while ($a_asig = mysqli_fetch_array($query_asig)) 
			{
				echo '<th colspan="2" style="text-align:center">'.$a_asig[0].'</th>';
			}
			echo "</tr>";

			$sql = "select distinct asignaturas.nombre, asignaturas.codigo, abrev from asignaturas, profesores where profesores.materia = asignaturas.nombre and asignaturas.curso = '$orden_nivel[0]' and abrev not like '%\_%' and asignaturas.codigo not in (select distinct asignaturas.codigo from asignaturas where asignaturas.nombre like 'Libre Disp%') order by asignaturas.nombre";
			//echo $sql;	
			$as = mysqli_query($db_con, $sql);
			while ($asi = mysqli_fetch_array($as)) 
			{
				$nomasi = $asi[0];
				$codasi = $asi[1];
				$abrev = $asi[2];
				echo "<tr><th nowrap>$abrev</th>";
		
				$sql_asig = "select distinct unidad from alma where curso = '$orden_nivel[0]' order by unidad";
				$query_asig = mysqli_query($db_con, $sql_asig);
				while ($a_asig = mysqli_fetch_array($query_asig)) 
				{	
					$unidad = $a_asig[0];
					$cod_nota = mysqli_query($db_con, "select count(nota) from suspensosprof". $key ."where asignatura = '$codasi' and nota < '5' and nota <> '10' and unidad = '$unidad'");
					$cod_apro = mysqli_query($db_con, "select count(nota) from suspensosprof". $key ." where asignatura = '$codasi' and (nota > '4' or nota = '10') and unidad = '$unidad'");
		
					$ns = mysqli_fetch_array($cod_nota);
					$num_susp= $ns[0];
					$na = mysqli_fetch_array($cod_apro);
					$num_apro= $na[0];

					$combas = mysqli_query($db_con, "select distinct suspensosprof". $key .".claveal from suspensosprof". $key ." where claveal in (select tcombasi.claveal1 from tcombasi, alma where alma.claveal1 = tcombasi.claveal1 and cmateria = '$codasi' and unidad = '$unidad')");
					$num_matr='';
					$num_matr = mysqli_num_rows($combas);
					
					$porcient_asig = ($num_susp*100)/$num_matr;
					$porciento_asig='';
					$porciento_susp='';
					if ($porcient_asig > 0) {
						$porciento_susp = "<span class='text-warning'>".substr($porcient_asig,0,4)."% </span><span class=''> (".$num_susp.")</span>";
					}
						
					$porcient_asig2 = ($num_apro*100)/$num_matr;
					$porciento_asig2='';
					$porciento_apro='';
					if ($porcient_asig2 > 0) {
						$porciento_apro = "<span class='text-success'>".substr($porcient_asig2,0,4)."% </span><span class=''> (".$num_apro.")</span>";
					}
					echo "<td nowrap>".$porciento_susp."</td><td nowrap>$porciento_apro </td>";					

				}
				echo "</tr>";
			}
			?>
			</table>
			<hr />
			<br />
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
