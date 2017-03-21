<?php
require('../../../bootstrap.php');

if (file_exists('../../../config.php')) {
	include('../../../config.php');
}

include("envio.php");

// Consulta  en curso.

if(!($_POST['id'])){$id = $_GET['id'];}else{$id = $_POST['id'];}
if(!($_POST['claveal'])){$claveal = $_GET['claveal'];}else{$claveal = $_POST['claveal'];}
if(!($_POST['textoa'])){$textoa = $_GET['textoa'];}else{$textoa = $_POST['textoa'];}
if(!($_POST['amones_sms'])){$amones_sms = $_GET['amones_sms'];}else{$amones_sms = $_POST['amones_sms'];}


$actualizar = "UPDATE  Fechoria SET  recibido =  '1' WHERE  Fechoria.id = '$id'";
mysqli_query($db_con, $actualizar );
$cons = "select alma.apellidos, alma.nombre, alma.unidad, Fechoria.fecha, Fechoria.notas, Fechoria.asunto, Fechoria.informa, 
  Fechoria.grave, Fechoria.medida, listafechorias.medidas2, Fechoria.expulsion, Fechoria.tutoria, Fechoria.claveal, alma.padre, alma.domicilio, alma.localidad, alma.codpostal, alma.provinciaresidencia, tutor, Fechoria.id, alma.CORREO, alma.telefono, alma.telefonourgencia from Fechoria, alma, listafechorias, FTUTORES where FTUTORES.unidad = alma.unidad and  Fechoria.claveal = alma.claveal and listafechorias.fechoria = Fechoria.asunto  and Fechoria.id = '$id' order by Fechoria.fecha DESC";
//echo $cons;
$result = mysqli_query($db_con, $cons ) or die (mysqli_error($db_con));

if ($row = mysqli_fetch_array ( $result )) {
	$apellidos = $row [0];
	$nombre = $row [1];
	$unidad = $row [2];
	$fecha = $row [3];
	$notas = $row [4];
	$asunto = $row [5];
	$informa = $row [6];
	$grave = $row [7];
	$medida = $row [8];
	$medidas2 = $row [9];
	$expulsion = $row [10];
	$tutoria = $row [11];
	$claveal = $row [12];
	$padre = $row [13];
	$direccion = $row [14];
	$localidad = $row [15];
	$codpostal = $row [16];
	$provincia = $row [17];
	$tutor = $row [18];
	$id = $row [19];
	$correo = $row [20];
	$tfno = $row [21];
	$tfno_u = $row [22];
}
$tr_tut = explode(", ", $tutor);
$tutor = "$tr_tut[1] $tr_tut[0]";
$fecha2 = date('Y-m-d');
$hoy = strftime("%d.%m.%Y", strtotime($fecha));

if ($amones_sms != "")
{
	$mens = 'Jefatura de Estudios le comunica que, con fecha '.strftime("%e de %B de %Y", strtotime($fecha)).', su hijo/a '.$nombre.' '.$apellidos.' ha sido amonestado/a por '.$textoa.'. <br>Asimismo, le comunico que, seg�n contempla el Plan de Convivencia del Centro, regulado por el Decreto 327/2010 de 13 de Julio por el que se aprueba el Reglamento Org�nico de los Institutos de Educaci�n Secundaria, de reincidir su hijo/a en este tipo de conductas contrarias a las normas de convivencia del Centro podr�a impon�rsele otra medida de correcci�n que podr�a llegar a ser la suspensi�n del derecho de asistencia al Centro.<br>Le recordamos que puede conseguir informaci�n m�s detallada contactando con la Jefatura de Estudios del Centro.<br><br><hr>Este correo es informativo. Por favor, no responder a esta direcci�n de correo. Si necesita mayor informaci�n sobre el contenido de este mensaje, p�ngase en contacto con Jefatura de Estudios.';
	$mensms = "Pongo en su conocimiento que con fecha ".strftime("%e de %B de %Y", strtotime($fecha))." su hijo/a $nombre $apellidos alumno del grupo $unidad ha sido amonestado/a por ".$textoa;
	enviarsmsemail( $mensms, "Comunicaci�n de amonestaci�n escrita", $mens, $db_con, $claveal, $nombre, $apellidos, $unidad, $tfno, $tfno_u, $correo, $informa, 1, $id, 1, $config['jefatura_email']);
}

require("../../../pdf/fpdf.php");

// Variables globales para el encabezado y pie de pagina
$GLOBALS['CENTRO_NOMBRE'] = $config['centro_denominacion'];
$GLOBALS['CENTRO_DIRECCION'] = $config['centro_direccion'];
$GLOBALS['CENTRO_CODPOSTAL'] = $config['centro_codpostal'];
$GLOBALS['CENTRO_LOCALIDAD'] = $config['centro_localidad'];
$GLOBALS['CENTRO_TELEFONO'] = $config['centro_telefono'];
$GLOBALS['CENTRO_FAX'] = $config['centro_fax'];
$GLOBALS['CENTRO_CORREO'] = $config['centro_email'];
$GLOBALS['CENTRO_PROVINCIA'] = $config['centro_provincia'];

# creamos la clase extendida de fpdf.php 
class GranPDF extends FPDF {
	function Header() {
		$this->SetTextColor(0, 122, 61);
		$this->Image( '../../../img/encabezado.jpg',25,14,53,'','jpg');
		$this->SetFont('ErasDemiBT','B',10);
		$this->SetY(15);
		$this->Cell(75);
		$this->Cell(80,5,'CONSEJER�A DE EDUCACI�N, CULTURA Y DEPORTE',0,1);
		$this->SetFont('ErasMDBT','I',10);
		$this->Cell(75);
		$this->Cell(80,5,$GLOBALS['CENTRO_NOMBRE'],0,1);
		$this->SetTextColor(255, 255, 255);
	}
	function Footer() {
		$this->SetTextColor(0, 122, 61);
		$this->Image( '../../../img/pie.jpg', 0, 245, 25, '', 'jpg' );
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


# creamos el nuevo objeto partiendo de la clase ampliada
$A4="A4";
$MiPDF = new GranPDF ( 'P', 'mm', $A4 );
$MiPDF->AddFont('NewsGotT','','NewsGotT.php');
$MiPDF->AddFont('NewsGotT','B','NewsGotTb.php');
$MiPDF->AddFont('ErasDemiBT','','ErasDemiBT.php');
$MiPDF->AddFont('ErasDemiBT','B','ErasDemiBT.php');
$MiPDF->AddFont('ErasMDBT','','ErasMDBT.php');
$MiPDF->AddFont('ErasMDBT','I','ErasMDBT.php');

$MiPDF->SetMargins (25, 20, 20);
$MiPDF->SetDisplayMode ( 'fullpage' );

$titulo = "Comunicaci�n de amonestaci�n escrita";
$cuerpo = "Muy Srs. nuestros:

Pongo en su conocimiento que con fecha ".strftime("%e de %B de %Y", strtotime($fecha))." su hijo/a $nombre $apellidos alumno del grupo $unidad ha sido amonestado/a por \"$textoa\".

Asimismo, le comunico que, seg�n contempla el Plan de Convivencia del Centro, regulado por el Decreto 327/2010 de 13 de Julio por el que se aprueba el Reglamento Org�nico de los Institutos de Educaci�n Secundaria, de reincidir su hijo/a en este tipo de conductas contrarias a las normas de convivencia del Centro podr�a impon�rsele otra medida de correcci�n que podr�a llegar a ser la suspensi�n del derecho de asistencia al Centro.

En ".$config['centro_localidad'].", a ".strftime("%e de %B de %Y", strtotime($fecha)).".";


for($i = 0; $i < 1; $i ++) {
	# insertamos la primera pagina del documento
	$MiPDF->Addpage ();
	
	// INFORMACION DE LA CARTA
	$MiPDF->SetY(45);
	$MiPDF->SetFont ( 'NewsGotT', '', 12 );
	$MiPDF->Cell(75, 5, 'Fecha:  '.$hoy, 0, 0, 'L', 0 );
	$MiPDF->Cell(75, 5, $padre, 0, 1, 'L', 0 );
	$MiPDF->Cell(75, 12, 'Ref.:     Fec/'.$row['id'], 0, 0, 'L', 0 );
	$MiPDF->Cell(75, 5, $direccion, 0, 1, 'L', 0 );
	$MiPDF->Cell(75, 0, '', 0, 0, 'L', 0 );
	$MiPDF->Cell(75, 5, $codpostal.' '.mb_strtoupper($provincia, 'iso-8859-1'), 0, 1, 'L', 0 );
	$MiPDF->Cell(0, 12, 'Asunto: '.$titulo, 0, 1, 'L', 0 );
	$MiPDF->Ln(10);
	
	// CUERPO DE LA CARTA
	$MiPDF->SetFont('NewsGotT', 'B', 12);
	$MiPDF->Multicell(0, 5, mb_strtoupper($titulo, 'iso-8859-1'), 0, 'C', 0 );
	$MiPDF->Ln(5);
	
	$MiPDF->SetFont('NewsGotT', '', 12);
	$MiPDF->Multicell( 0, 5, $cuerpo, 0, 'L', 0 );
	$MiPDF->Ln(10);

	//FIRMAS
	$MiPDF->Cell (90, 5, 'Representante legal', 0, 0, 'C', 0 );
	if ($config['firmaamonestacion'] == 1 )
	{
		$MiPDF->Cell (55, 5, 'Jefe/a de estudios', 0, 1, 'C', 0 );
	}
	else
	{
		$MiPDF->Cell (55, 5, 'Tutor/a', 0, 1, 'C', 0 );
	}
	$MiPDF->Cell (55, 20, '', 0, 0, 'C', 0 );
	$MiPDF->Cell (55, 20, '', 0, 1, 'C', 0 );
	$MiPDF->SetFont('NewsGotT', '', 10);
	$MiPDF->Cell (90, 5, 'Fdo. '.$padre, 0, 0, 'C', 0 );
	if ($config['firmaamonestacion'] == 1 )
	{
		$MiPDF->Cell (55, 5, 'Fdo. '.mb_convert_case($config['directivo_jefatura'], MB_CASE_TITLE, "iso-8859-1"), 0, 1, 'C', 0 );
	}
	else
	{
		$MiPDF->Cell (55, 5, 'Fdo. '.mb_convert_case($tutor, MB_CASE_TITLE, "iso-8859-1"), 0, 1, 'C', 0 );
	}		
	
	
	// RECIBI
	$txt_recibi = "D./D�a. $nombre $apellidos, alumno/a del grupo $unidad, he recibido la $titulo con referencia Fec/".$row['id']." registrado el ".strftime("%e de %B de %Y", strtotime($fecha)).".";
	
	$MiPDF->Ln(8);

	$MiPDF->Line(20, $MiPDF->GetY(), 190, $MiPDF->GetY());
	$MiPDF->Ln(5);
	
	$MiPDF->SetFont('NewsGotT', 'B', 12);
	$MiPDF->Multicell(0, 5, 'RECIB�', 0, 'C', 0 );
	$MiPDF->Ln(5);
	
	$MiPDF->SetFont('NewsGotT', '', 12);
	$MiPDF->Multicell(0, 5, $txt_recibi, 0, 'L', 0 );
	$MiPDF->Ln(15);
	$MiPDF->Cell (55, 25, '', 0, 0, 'L', 0 );
	$MiPDF->Cell (55, 15, 'Fdo. '.$nombre.' '.$apellidos, 0, 0, 'L', 0 );
	
}

$MiPDF->Output();

?>
