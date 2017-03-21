<?php
require('../../bootstrap.php');
//include('../../config.php');

if (file_exists('../../config.php')) {
	include('../../config.php');
	$centro = $config['centro_denominacion'];
}
else
{
	$centro = "no esta";
}

if(isset($_GET['id']) && !empty($_GET['id'])) {
	$id = $_GET['id'];
}
else {
	header('Location:'.'index.php');
}

$GLOBALS['CENTRO_NOMBRE'] = $config['centro_denominacion'];
$GLOBALS['CENTRO_DIRECCION'] = $config['centro_direccion'];
$GLOBALS['CENTRO_CODPOSTAL'] = $config['centro_codpostal'];
$GLOBALS['CENTRO_LOCALIDAD'] = $config['centro_localidad'];
$GLOBALS['CENTRO_TELEFONO'] = $config['centro_telefono'];
$GLOBALS['CENTRO_FAX'] = $config['centro_fax'];
$GLOBALS['CENTRO_CORREO'] = $config['centro_email'];
$GLOBALS['CENTRO_PROVINCIA'] = $config['centro_provincia'];
require("../../pdf/mc_table.php");

class GranPDF extends PDF_MC_Table {
	function Header() {
		
		$this->SetTextColor(0, 122, 61);
		$this->Image( '../../img/encabezado.jpg',25,14,53,'','jpg');
		$this->SetFont('ErasDemiBT','B',10);
		$this->SetY(15);
		$this->Cell(75);
		$this->MultiCell(170, 5, 'CONSEJER�A DE EDUCACI�N, CULTURA Y DEPORTE', 0,'R', 0);
		$this->Ln(1);
		$this->MultiCell(245, 5, $GLOBALS['CENTRO_NOMBRE'], 0,'R', 0);
		$this->Ln(14);
	}
	function Footer() {
		$this->SetTextColor(0, 122, 61);
		$this->Image( '../../img/pie.jpg', 0, 245, 25, '', 'jpg' );
		$this->SetY(275);
		$this->SetFont('ErasMDBT','',8);
		$this->Cell(75);
		$this->Cell(80,4,$GLOBALS['CENTRO_DIRECCION'].'. '.$GLOBALS['CENTRO_CODPOSTAL'].', '.$GLOBALS['CENTRO_LOCALIDAD'].' ('.$GLOBALS['CENTRO_PROVINCIA'] .')',0,1);
		$this->Cell(75);
		$this->Cell(80,4,'Telf: '.$GLOBALS['CENTRO_TELEFONO'].'   Fax: '.$GLOBALS['CENTRO_FAX'],0,1);
		$this->Cell(75);
		$this->Cell(80,4,'Correo-e: '.$GLOBALS['CENTRO_CORREO'],0,1);
		$this->SetTextColor(255, 255, 255);
	}
}

$MiPDF = new GranPDF('L', 'mm', 'A4');

$MiPDF->AddFont('NewsGotT','','NewsGotT.php');
$MiPDF->AddFont('NewsGotT','B','NewsGotTb.php');
$MiPDF->AddFont('ErasDemiBT','','ErasDemiBT.php');
$MiPDF->AddFont('ErasDemiBT','B','ErasDemiBT.php');
$MiPDF->AddFont('ErasMDBT','','ErasMDBT.php');
$MiPDF->AddFont('ErasMDBT','I','ErasMDBT.php');

$MiPDF->SetMargins(25, 20, 20);
$MiPDF->SetDisplayMode('fullpage');

$titulo = "Informe de tutor�a";


$MiPDF->Addpage();

$MiPDF->SetFont('NewsGotT', 'B', 12);
$MiPDF->Multicell(0, 5, mb_strtoupper($titulo, 'iso-8859-1'), 0, 'C', 0 );
$MiPDF->Ln(5);


$MiPDF->SetFont('NewsGotT', '', 12);


// INFORMACION DEL ALUMNO
$result = mysqli_query($db_con, "SELECT apellidos, nombre, unidad, tutor, f_entrev, claveal FROM infotut_alumno WHERE id='$id'");

$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

$MiPDF->SetFont('NewsGotT', 'B', 12);
$MiPDF->Cell(25, 5, 'Alumno/a: ', 0, 0, 'L', 0);
$MiPDF->SetFont('NewsGotT', '', 12);
$MiPDF->Cell(80, 5, $row['apellidos'].', '.$row['nombre'], 0, 0, 'L', 0 );

$MiPDF->SetFont('NewsGotT', 'B', 12);
$MiPDF->Cell(35, 5, 'Fecha de visita: ', 0, 0, 'L', 0);
$MiPDF->SetFont('NewsGotT', '', 12);
$MiPDF->Cell(55, 5, strftime('%e de %B de %Y',strtotime($row['f_entrev'])), 0, 1, 'L', 0 );

$MiPDF->Ln(2);

$MiPDF->SetFont('NewsGotT', 'B', 12);
$MiPDF->Cell(20, 5, 'Unidad: ', 0, 0, 'L', 0);
$MiPDF->SetFont('NewsGotT', '', 12);
$MiPDF->Cell(85, 5, $row['unidad'], 0, 0, 'L', 0 );

$MiPDF->SetFont('NewsGotT', 'B', 12);
$MiPDF->Cell(20, 5, 'Tutor/a: ', 0, 0, 'L', 0);
$MiPDF->SetFont('NewsGotT', '', 12);
$MiPDF->Cell(40, 5, mb_convert_case($row['tutor'], MB_CASE_TITLE, "iso-8859-1"), 0, 1, 'L', 0 );

$MiPDF->Ln(5);

mysqli_free_result($result);


// INFORME

$MiPDF->SetWidths(array(70, 65, 120));
$MiPDF->SetFont('NewsGotT', 'B', 12);
$MiPDF->SetTextColor(255, 255, 255);
$MiPDF->SetFillColor(61, 61, 61);

$MiPDF->Row(array('Asignatura / Materia', 'Profesor/a', 'Observaciones'), 0, 6);	

$result = mysqli_query($db_con, "SELECT asignatura, informe, profesor FROM infotut_profesor WHERE id_alumno='$id'");

$MiPDF->SetTextColor(0, 0, 0);
$MiPDF->SetFont('NewsGotT', '', 12);

while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	$MiPDF->Row(array($row['asignatura'], $row['profesor'], $row['informe']), 1, 6);	
}

mysqli_free_result($result);


// SALIDA

$MiPDF->Output();

?>
