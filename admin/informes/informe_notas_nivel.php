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
	foreach ($titulos as $key=>$val){
		
		$key == '0' ? $activ=" active" : $activ='';
	?>


	<div class="tab-pane fade in<?php echo $activ;?>" id="<?php echo "tab".$key;?>">
		<h3>Resultados de los Alumnos por Nivel</h3><br />
		<p class="help-block text-warning" align="left">En 4º de ESO y 2º de Bachillerato, los alumnos titulan con <strong>0</strong> asignaturas suspensas. En el resto de los grupos de ESO y Bachillerato los alumnos promocionan con <strong>2 o menos</strong> asignaturas suspensas. </p>
		<table class="table table-striped table-bordered"  align="center" style="width:auto" valign="top">
		<thead>
		<th></th>
		<th class='text-info'>Alumnos</th>
		<th class='text-warning'>Repiten</th>
		<th>0 Susp</th>
		<th>1-2 Susp</th>
		<th>3-5 Susp</th>
		<th>6-8 Susp</th>
		<th>9+ Susp.</th>
		<th class='text-success'>Promo./Tit.</th>
		</thead>
		<tbody>
	<?php
	// Evaluaciones ESO
		
		if (isset($_GET['curso']))
		{
			$curso = $_GET['curso'];
			include("calculoresultados_notas_nivel.php");
		}
		else
		{
			$nivele = mysqli_query($db_con, "select * from cursos");
			while ($orden_nivel = mysqli_fetch_array($nivele)){
				$niv = mysqli_query($db_con, "select distinct curso, idcurso from alma, cursos where curso=nomcurso and curso = '$orden_nivel[1]'");
				while ($ni = mysqli_fetch_array($niv)) {
					$idn = $ni[1];
					if ($idn=="101140") { $nivel="1E"; }
					elseif ($idn=="101141") { $nivel="2E"; }
					elseif ($idn=="101142") { $nivel="3E"; }
					elseif ($idn=="6029" or $idn=="2063") { $nivel="1B"; }
					else{ $nivel = $ni[1]; }
					$n_grupo+=1;
					$curso = $ni[0];
					
					$rep = ""; 
					$promo = "";
					include("calculoresultados_notas_nivel.php");

				}
			}
		}
	?>
		</tbody>
		</table>

		<!--  Estadísticas por asignatura -->
		<br />
	</div>
	<?php

	}
}
else
{
?>
	<div class="tab-pane fade in" id="<?php echo "tab0";?>">
		<h3>Resultados de los Alumnos por Nivel</h3><br />
		<p class="help-block text-warning" align="left">No se puede mostrar el informe debido a que alguien esta generandolo en este momento. Por favor, espera un minuto antes de volver a intentarlo</p>
	</div>
<?php
}
mysqli_close();
?>
</div>
</div>
</div>
</div>

<?php include("../../pie.php");?> 
</body>
</html>
