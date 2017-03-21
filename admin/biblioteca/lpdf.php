<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1, 'c'));

require_once('../../pdf/class.ezpdf.php');
$pdf =& new Cezpdf('a4');
$pdf->selectFont('../../pdf/fonts/Helvetica.afm');
$pdf->ezSetCmMargins(1,1,1.5,1.5);
# hasta aquí lo del pdf
$options_center = array(
				'justification' => 'center'
			);
$options_right = array(
				'justification' => 'right'
			);
$options_left = array(
				'justification' => 'left'
					);
$fecha_act = date('Y-m-d');	
$lista=mysqli_query($db_con, "select curso,apellidos,nombre,ejemplar,devolucion, id from morosos order by curso, apellidos, devolucion") or die ("error query lista");
while($datatmp = mysqli_fetch_array($lista)) 
{
	if(strstr($datatmp[0],"Monter")==TRUE){$datatmp[0]="Prof.";}
	$data[] = array(
				'id'=>$datatmp[5],
				'curso'=>$datatmp[0],
				'nombre'=>$datatmp[1].', '.$datatmp[2],
				'ejemplar'=>$datatmp[3],
				'devol'=>$datatmp[4]
				);
}
$titles = array(
				'id'=>'<b>Id</b>',
				'curso'=>'<b>Curso</b>',
				'nombre'=>'<b>Alumno/a</b>',
				'ejemplar'=>'<b>Ejemplar</b>',
				'devol'=>'<b>Devoluci�n</b>'

				);
$options = array(
				'showHeadings'=>1,
				'shadeCol'=>array(0.9,0.9,0.9),
				'justification'=>'center',
				'xPos' => 'center',
				'xOrientation'=>'center',
				'fontSize' => 8,
				'width'=>475,
				// justificacion y tamaño de columnas de manera independiente
				'cols'=>array(
							"id" => array('justification'=>'center', 'width' => '48'),
							"curso" => array('justification'=>'center', 'width' => '35'),
							"nombre" => array('justification'=>'left'),
							"ejemplar" => array('justification'=>'left'),
							"devol" => array('justification'=>'center', 'width' => '65')
							)
				);


if ($_SERVER['SERVER_NAME'] == 'iesmonterroso.org') {
	$biblio= "Biblioteca Julio P�rez Santander";
}
else {
	$biblio= "Biblioteca";
}
$txttit= $biblio.'. ' . $config['centro_denominacion'].". Curso ".$config['curso_actual'].".\n";
$txttit.= "Lista de morosos con fecha ". $fecha ."\n";
	
$pdf->ezText($txttit, 13, $options_center);
$pdf->ezTable($data, $titles,'', $options);
//$pdf->ezText("\n\n\n", 10);


$pdf->ezStream();


?>