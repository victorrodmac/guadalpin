<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); ?>
  
<?php  
// Evaluaciones
$notas1 = "select notas1, notas2, notas3, notas4, unidad, notas0, notas5, notas6 from alma, notas where alma.CLAVEAL1 = notas.claveal and alma.CLAVEAL = '$claveal'";
//echo $notas1;
$result1 = mysqli_query($db_con, $notas1);
$row1 = mysqli_fetch_array($result1);
$asignatura_1 = substr($row1[0], 0, strlen($row1[0])-1);
$inicial = substr($row1[5], 0, strlen($row1[5])-1);

if (strlen($asignatura_1) > 0) {
	$trozos = explode(";", $asignatura_1);
}
elseif(strlen($inicial) > 0) {
	$trozos = explode(";", $inicial);
}
?>

<?php
if (strlen($inicial) > 0) {
	$titulo_extra = "<th>Ev. Inic.</th>";
	$col_extra = 1;
}
?>
<h3>Evaluaciones</h3><br>
<div class='table-responsive'><table class='table table-bordered table-striped table-hover'>
		<thead><tr><th>Asignatura / Materia</th><?php echo $titulo_extra; ?><th>Ev.ini</th><th>1Ev.</th><th>2Ev.</th><th>Ord.</th><th>Ext.</th><th>1FExt</th><th>2Fext</th></tr></thead>

<?php 

$num = count($trozos); 
for ($i=0;$i<$num; $i++)
  {
$nombre_asig ="";
$bloque = explode(":", $trozos[$i]);
$nombreasig = "select NOMBRE, ABREV, CURSO, CODIGO from asignaturas where CODIGO = '" . $bloque[0] . "'  order by CURSO";
$asig = mysqli_query($db_con, $nombreasig);
if(mysqli_num_rows($asig) < 1)	{$nombre_asig = "Asignatura sin c�digo"; }
while($rowasig = mysqli_fetch_array($asig))	{
  if ($rowasig[3] == "")
  {$nombre_asig = "Asignatura sin c�digo"; }
else{
$nombre_asig = $rowasig[0];
}	
	if(strlen(strstr($rowasig[1],'_')) > 0)	{	}

	else 	{	$asig_pend = $rowasig[2];	}	
	}
$asignatura1 = substr($row1[0], 0, strlen($row1[0])-1);
$trozos1 = explode(";", $asignatura1);
	if (strstr($row1[0],$bloque[0])) {
	foreach($trozos1 as $codi1)
	{
	$bloque1 = explode(":", $codi1);
	if($bloque1[0] == $bloque[0])
	{
$califica1 = "select nombre from calificaciones where codigo = '" . $bloque1[1]. "'";

$numero1 = mysqli_query($db_con, $califica1);
$rown1 = mysqli_fetch_array($numero1);

}
	}	
	}
	else{
		$rown1[0]=" ";
	}


$asignatura2 = substr($row1[1], 0, strlen($row1[1])-1);
$trozos2 = explode(";", $asignatura2);
	if (strstr($row1[1],$bloque[0])) {
	foreach($trozos2 as $codi2)
	{
	$bloque2 = explode(":", $codi2);
	if($bloque2[0] == $bloque[0])
	{
$califica2 = "select nombre from calificaciones where codigo = '" . $bloque2[1]. "'";
$numero2 = mysqli_query($db_con, $califica2);
$rown2 = mysqli_fetch_array($numero2);
	}
	}	
	}
	else{
		$rown2[0]=" ";
	}
	
	
	
$asignatura3 = substr($row1[2], 0, strlen($row1[2])-1);
$trozos3 = explode(";", $asignatura3);
	if (strstr($row1[2],$bloque[0])) {
	foreach($trozos3 as $codi3)
	{
	$bloque3 = explode(":", $codi3);
	if($bloque3[0] == $bloque[0])
	{
$califica3 = "select nombre from calificaciones where codigo = '" . $bloque3[1]. "'";
$numero3 = mysqli_query($db_con, $califica3);
$rown3 = mysqli_fetch_array($numero3);
//if($rown3[0] == "No Presentado"){
//	$rown3[0] = "NP";
//}
	}
	}
	}
	else{
		$rown3[0]=" ";
	}
	
	
	
$asignatura4 = substr($row1[3], 0, strlen($row1[3])-1);
$trozos4 = explode(";", $asignatura4);
	if (strstr($row1[3],$bloque[0])) {
		foreach($trozos4 as $codi4)
	{
	$bloque4 = explode(":", $codi4);
	
	if($bloque[0] == $bloque4[0])
	{
$califica4 = "select nombre from calificaciones where codigo = '" . $bloque4[1]. "'";
$numero4 = mysqli_query($db_con, $califica4);
$rown4 = mysqli_fetch_array($numero4);
	}
	}
	}
	else{
		$rown4[0]=" ";
	}

$asignatura5 = substr($row1[5], 0, strlen($row1[5])-1);
$trozos5 = explode(";", $asignatura5);
	if (strstr($row1[5],$bloque[0])) {
		foreach($trozos5 as $codi5)
	{
	$bloque5 = explode(":", $codi5);

	if($bloque[0] == $bloque5[0])
	{
$califica5 = "select nombre from calificaciones where codigo = '" . $bloque5[1]. "'";
$numero5 = mysqli_query($db_con, $califica5);
$rown5 = mysqli_fetch_array($numero5);
	}
	}
	}
	else{
		$rown5[0]=" ";
	}
	
	$asignatura6 = substr($row1[6], 0, strlen($row1[6])-1);
$trozos6 = explode(";", $asignatura5);
	if (strstr($row1[6],$bloque[0])) {
		foreach($trozos6 as $codi6)
	{
	$bloque6 = explode(":", $codi6);

	if($bloque[0] == $bloque6[0])
	{
$califica6 = "select nombre from calificaciones where codigo = '" . $bloque6[1]. "'";
$numero6 = mysqli_query($db_con, $califica6);
$rown6 = mysqli_fetch_array($numero6);
	}
	}
	}
	else{
		$rown6[0]=" ";
	}

$asignatura7 = substr($row1[7], 0, strlen($row1[7])-1);
$trozos7 = explode(";", $asignatura7);
	if (strstr($row1[7],$bloque[0])) {
		foreach($trozos7 as $codi7)
	{
	$bloque7 = explode(":", $codi7);

	if($bloque[0] == $bloque7[0])
	{
$califica7 = "select nombre from calificaciones where codigo = '" . $bloque7[1]. "'";
$numero7 = mysqli_query($db_con, $califica7);
$rown7 = mysqli_fetch_array($numero7);
	}
	}
	}
	else{
		$rown7[0]=" ";
	}

	
	
if($rown1[0] == "" and $rown2[0] == "" and $rown3[0] == "" and $rown4[0] == "" and $rown5[0] == "" and $rown6[0] == "" and $rown7[0] == "" )
	{
			}
	else
		{
	echo "<tr><td>";
	if ($nombre_asig == "Asignatura sin c�digo")  $asig_pend = "Consultar con Administraci�n";
	$trozo_curso=explode("(",$asig_pend);
	$asig_curso=$trozo_curso[0];
	echo $nombre_asig . " <span  class='small'>(" . $asig_curso . ")</span></td>"; 
	echo "<td>";
	echo $rown5[0] ."</td>";
		echo "<td>";
	echo $rown1[0] ." </td>";
		echo "<td>";
	echo $rown2[0] . " </td>";
	echo "<td>";
	 echo $rown3[0] . "</td>";
	echo "<td>";
	 echo $rown4[0] . "</td>";
	echo "<td>";
	 echo $rown6[0] . "</td>";
	echo "<td>";
	 echo $rown7[0] . "</td></tr>";

			}
	}
	echo "</table></div>";

  
?>
