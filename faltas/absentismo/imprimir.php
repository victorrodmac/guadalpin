<?php
require('../../bootstrap.php');


$tutor = $_SESSION['profi'];

// PDF
$mes = $_GET['mes'];
$fecha2 = date('Y-m-d');
$hoy = formatea_fecha($fecha);
include("../../pdf/fpdf.php");
define('FPDF_FONTPATH','../../pdf/font/');
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
		$this->Image( '../../img/encabezado.jpg',25,14,53,'','jpg');
		$this->SetFont('ErasDemiBT','B',12);
		$this->SetY(15);
		$this->Cell(75);
		$this->Cell(80,5,'CONSEJER�A DE EDUCACI�N, CULTURA Y DEPORTE',0,1);
		$this->SetFont('ErasMDBT','I',12);
		$this->Cell(75);
		$this->Cell(80,5,$GLOBALS['CENTRO_NOMBRE'],0,1);
		$this->SetTextColor(255, 255, 255);
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

# creamos el nuevo objeto partiendo de la clase
$MiPDF=new GranPDF('P','mm','A4');
$MiPDF->AddFont('NewsGotT','','NewsGotT.php');
$MiPDF->AddFont('NewsGotT','B','NewsGotTb.php');
$MiPDF->AddFont('ErasDemiBT','','ErasDemiBT.php');
$MiPDF->AddFont('ErasDemiBT','B','ErasDemiBT.php');
$MiPDF->AddFont('ErasMDBT','','ErasMDBT.php');
$MiPDF->AddFont('ErasMDBT','I','ErasMDBT.php');
			$MiPDF->SetMargins(25,20,20);
			# ajustamos al 100% la visualizacion
			$MiPDF->SetDisplayMode('fullpage');
			
                     if($mes=='09'){$n_mes='Septiembre';}
                    if($mes=='10'){$n_mes='Octubra';}
                    if($mes=='11'){$n_mes='Noviembre';}
                    if($mes=='12'){$n_mes='Diciembre';}
                    if($mes=='01'){$n_mes='Enero';}
                    if($mes=='02'){$n_mes='Febrero';}
                    if($mes=='03'){$n_mes='Marzo';}
                    if($mes=='04'){$n_mes='Abril';}
                    if($mes=='05'){$n_mes='Mayo';}
                    if($mes=='06'){$n_mes='Junio';}
$alumnos0 = "select numero, alma.unidad, apellidos, nombre, jefatura, orientacion, tutoria, padre, domicilio, provinciaresidencia, telefono, fecha, alma.claveal, serv_sociales from absentismo, alma where absentismo.claveal=alma.claveal and mes='$mes'";
$alumnos1 = mysqli_query($db_con, $alumnos0);
while($alumno = mysqli_fetch_array($alumnos1))
{
	$fecha=date('Y-m-d');	
# insertamos la primera pagina del documento
$MiPDF->Addpage();
   	$foto = '../../xml/fotos/'.$alumno[12].'.jpg';
	if (file_exists($foto)) {
		$MiPDF->Image($foto,90,30,26,'','jpg');
	} 
	else
	{
	   	$foto = '../../xml/fotos/'.$alumno[12].'.JPG';
		if (file_exists($foto)) {
			$MiPDF->Image($foto,90,30,26,'','JPG');
		} 

	}
$cuerpo0="INFORME DE ABSENTISMO ESCOLAR";
$datos10="Alumno: $alumno[3] $alumno[2]
Fecha: $alumno[11]
Curso: $alumno[1]
Tutor/a: $alumno[7]
Domicilio: $alumno[8] ($alumno[9])
Tel�fono: $alumno[10]";
$cuerpo1="El Alumno no ha asistido al Centro $alumno[0] horas lectivas durante el mes de $n_mes. Las faltas de asistencia no han sido justificadas.";
	$mes = array(1=>"enero",2=>"febrero",3=>"marzo",4=>"abril",5=>"mayo",6=>"junio",7=>"julio",
                 8=>"agosto",9=>"septiembre",10=>"octubre",11=>"noviembre",12=>"diciembre");
    $dia = array("domingo", "lunes","martes","mi�rcoles","jueves","viernes","s�bado");
	$diames = date("j");
    $nmes = date("n");
    $ndia = date("w");
    $nano0 = date("Y");
	$cuerpo2 = "$diames de ".$mes[$nmes].", $nano0";
$cuerpo3="Informe de la Jefatura de Estudios";
$cuerpo33="$alumno[4]";
if (strlen($alumno[5])>0) {
$cuerpo4="Informe del Departamento de Orientaci�n";
$cuerpo44="$alumno[5]";	
}
if (strlen($alumno[6])>0) {
$cuerpo5="Informe del Tutor del Curso";
$cuerpo55="$alumno[6]";	
}
if (strlen($alumno[13])>0) {
$cuerpo6="Informe de los Servicios Sociales";
$cuerpo66="$alumno[13]";	
}
#### Cabecera con direcci�n
	$MiPDF->SetFont('NewsGotT','',12);
	$MiPDF->SetTextColor(0,0,0);
	
	#Cuerpo.
	$MiPDF->Ln(45);
	$MiPDF->SetFont('NewsGotT','B',12);
	$MiPDF->Multicell(0,20,$cuerpo0,0,'C',0);
	$MiPDF->SetFont('NewsGotT');
	
	$MiPDF->Multicell(0,4,$datos10,0,'L',0);
	$MiPDF->Ln(5);
	$MiPDF->SetFont('NewsGotT','',12);
	$MiPDF->Multicell(0,4,$cuerpo1,0,'J',0);
	$MiPDF->Ln(5);
	
	$MiPDF->SetFont('NewsGotT','B',12);
	$MiPDF->Multicell(0,4,$cuerpo3,0,'L',0);
	$MiPDF->Ln(1);
		
	$MiPDF->SetFont('NewsGotT','',12);
	$MiPDF->Multicell(0,4,$cuerpo33,0,'J',0);
	$MiPDF->Ln(5);
if (strlen($alumno[5])>0) {
		$MiPDF->SetFont('NewsGotT','B',12);
	$MiPDF->Multicell(0,4,$cuerpo4,0,'L',0);	
	$MiPDF->Ln(1);
		$MiPDF->SetFont('NewsGotT','',12);
	$MiPDF->Multicell(0,4,$cuerpo44,0,'J',0);
	$MiPDF->Ln(5);
}		
if (strlen($alumno[6])>0) {
	$MiPDF->SetFont('NewsGotT','B',12);
	$MiPDF->Multicell(0,4,$cuerpo5,0,'L',0);
	$MiPDF->Ln(1);
		$MiPDF->SetFont('NewsGotT','',12);
	$MiPDF->Multicell(0,4,$cuerpo55,0,'J',0);
	$MiPDF->Ln(5);
	}
if (strlen($alumno[13])>0) {
	$MiPDF->SetFont('NewsGotT','B',12);
	$MiPDF->Multicell(0,4,$cuerpo6,0,'L',0);
	$MiPDF->Ln(1);
		$MiPDF->SetFont('NewsGotT','',12);
	$MiPDF->Multicell(0,4,$cuerpo66,0,'J',0);
	$MiPDF->Ln(25);
	}
	$MiPDF->SetFont('NewsGotT','',12,12);
	$MiPDF->Multicell(0,4,$cuerpo2,0,'C',0);
	$MiPDF->Ln(1);
	//unlink($temp);
	//$MiPDF->ImageDestroy($foto);
	}

$MiPDF->Output();	

?>
