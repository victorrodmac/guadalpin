<?php
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
		<h3>Resultados de los Alumnos por Materias y Grupo</h3><br />
		<?php

		if (isset($_GET['unidad']))
			{
				$unidad = $_GET['unidad'];
				$curso = $_GET['curso'];
				include("calculoresultados_notas_grupo.php");
			}
		else
		{
			$nivele = mysqli_query($db_con, "select distinct curso from alma order by curso");
			while ($orden_nivel = mysqli_fetch_array($nivele)){
				
				echo "<legend>".$orden_nivel[0]."</legend><hr />";
				$curso = $orden_nivel[0];

			// UNIDADES DEL CURSO
				$niv = mysqli_query($db_con, "select distinct unidad from alma where curso = '$orden_nivel[0]' order by unidad");
				while ($ni = mysqli_fetch_array($niv)) {
					$unidad = $ni[0];
					include("calculoresultados_notas_grupo.php");

				}
			}
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
		<h3>Resultados de los Alumnos por Materias y Grupo</h3><br />
		<p class="help-block text-warning" align="left">No se puede mostrar el informe debido a que alguien esta generandolo en este momento. Por favor, espera un minuto antes de volver a intentarlo</p>
	</div>
<?php
}
?>
</div>
</div>
</div>
</div>

	<?php include("../../pie.php"); ?>
	
</body>
</html>
