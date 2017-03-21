<?php
require('../../bootstrap.php');

include("../../menu.php");
include("../../faltas/menu.php");
?>

<div class="container">

<div class="page-header">
  <h2>Faltas de Asistencia <small> Resumen de faltas por Asignatura</small></h2>
  </div>
<br />
<div class="row">
<div class="col-sm-6 col-sm-offset-3">

<?php

if (isset($profe)) {}else{$profe= $_SESSION['profi'];}
if (isset($materia)) {
	
$tr = explode(" -> ",$materia);
$asignatura = $tr[0];
$grupo = $tr[1];
$nivel = $tr[2];
$nivel_bach = substr($nivel,0,9);
//echo "$asignatura --> $grupo --> $nivel<br>";
$SQL = "select FALTAS.claveal, count(*) as numero, codasi, CONCAT( apellidos, ', ', nombre ) as ncompleto, FALTAS.nc from FALTAS, alma where FALTAS.claveal = alma.claveal and codasi in (select distinct codigo from asignaturas where nombre = '$asignatura' and curso like '$nivel_bach%' and abrev not like '%\_%') and FALTAS.unidad = '$grupo' and profesor like (select distinct c_prof from horw where prof = '$profe') and falta='F' group by FALTAS.nc, FALTAS.claveal, codasi, ncompleto order BY FALTAS.nc";

$result = mysqli_query($db_con, $SQL);
if ($result) {
	echo "<center><p class='lead'><small>$asignatura ( $grupo )</small></p>";
}
  if ($row = mysqli_fetch_array($result))
        {
        echo "<table class='table table-striped' style='width:auto'>\n";
        echo "<thead><th width=\"60\"></th><th>Alumno</th><th>Total</th></thead><tbody>";
        do {
			echo "<tr><td>";
			$foto = '../../xml/fotos/'.$row[0].'.jpg'; 
			if (file_exists($foto))
			{
				echo '<img src="'.$foto.'" width="55" alt="" />';
			}
			else
			{
				$foto = '../../xml/fotos/'.$row[0].'.JPG';
				if (file_exists($foto))
				{
					echo '<img src="'.$foto.'" width="55" alt="" />';
				}
				else
				{
					echo '<span class="fa fa-user fa-fw fa-3x"></span>';
				}
			}
			echo "</td><td>";
			echo "<a href='informes.php?claveal=$row[0]&fechasp1=".$config['curso_inicio']."&fechasp3=".$config['curso_fin']."&submit2=2'>$row[3]</a></td><td style='vertical-align:middle'><strong>$row[1]</strong></td></tr>\n"; 
        } while($row = mysqli_fetch_array($result));
        echo "</tbody></table></center>";
        } 
        else
        {
			echo '<br /><div align="center"><div class="alert alert-warning alert-block fade in" style="max-width:500px;text-align:left">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<legend>ATENCI�N:</legend>
No hay registros coincidentes, bien porque te has equivocado
        al introducir los datos, bien porque ningun dato se ajusta a tus criterios.
		</div></div><br />';
?>
        <?php
        }
}
else{
				echo '<br /><div align="center"><div class="alert alert-warning alert-block fade in" style="max-width:500px;text-align:left">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<legend>ATENCI�N:</legend>
Debes seleccionar una asugnatura con su Grupo y Nivel. Vuelve atr�s e int�ntalo de nuevo.
		</div></div><br />';
}
  ?>
</div>
</div>
</div>

	<?php include("../../pie.php"); ?>
	
</body>
</html>