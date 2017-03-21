<?php
require('../../../bootstrap.php');

if (file_exists('../../../config.php')) {
	include('../../../config.php');
}
include("envio.php");

if(!($_POST['id'])){$id = $_GET['id'];}else{$id = $_POST['id'];}
if(!($_POST['claveal'])){$claveal = $_GET['claveal'];}else{$claveal = $_POST['claveal'];}
if (isset($_POST['expulsion'])) { $expulsion = $_POST['expulsion']; }
if (isset($_POST['fechainicio'])) { $fechainicio = $_POST['fechainicio']; }
if (isset($_POST['fechafin'])) { $fechafin = $_POST['fechafin']; }
if (isset($_POST['aplazados'])) { $aplazados = $_POST['aplazados']; }
if (isset($_POST['gravedad'])) 
{ 
	$gravedad = "grave"; 
	$mensgravedad = "gravemente perjudicial";
}
else
{
	$gravedad = "leve";
	$mensgravedad = "contraria";
}
if(!($_POST['exp_sms'])){$exp_sms = $_GET['exp_sms'];}else{$exp_sms = $_POST['exp_sms'];}


$tutor = $_SESSION ['profi'];

// Consulta  en curso. 
$actualizar = "UPDATE  Fechoria SET  recibido =  '1' WHERE  Fechoria.id = '$id'";
mysqli_query($db_con, $actualizar );
$result = mysqli_query($db_con, "select alma.apellidos, alma.nombre, alma.unidad, Fechoria.fecha, Fechoria.notas, Fechoria.asunto, Fechoria.informa, Fechoria.grave, Fechoria.medida, listafechorias.medidas2, Fechoria.expulsion, Fechoria.tutoria, Fechoria.claveal, alma.telefono, alma.telefonourgencia, alma.padre, alma.domicilio, alma.localidad, alma.codpostal, alma.provinciaresidencia, Fechoria.id, alma.CORREO from Fechoria, listafechorias, alma where Fechoria.claveal = alma.claveal and listafechorias.fechoria = Fechoria.asunto and Fechoria.id = '$id' order by Fechoria.fecha DESC" );
if ($row = mysqli_fetch_array ( $result )) {
	$idfec = $row['id'];
	$apellidos = $row [0];
	$nombre = $row [1];
	$unidad = $row [2];
	$fecha = $row [3];
	$grave = $row [7];
	$expulsion = $row [10];
	$claveal = $row [12];
	$tfno = $row [13];
	$tfno_u = $row [14];
	$padre = $row ['padre'];
	$direccion = $row ['domicilio'];
	$codpostal = $row ['codpostal'];
	$provincia = $row ['provinciaresidencia'];
	$correo = $row['CORREO'];
	$id = $row['id'];
}

$fecha2 = date ( 'Y-m-d' );
$hoy = strftime("%d.%m.%Y", strtotime($fecha));
$fechaesp = explode ( "-", $fechainicio );
$fechaesp1 = explode ( "-", $fechafin );
$fecha = "$fechaesp[2]-$fechaesp[1]-$fechaesp[0]";
$fecha_fin = "$fechaesp1[2]-$fechaesp1[1]-$fechaesp1[0]";
$inicio1 = formatea_fecha ( $fecha );
$fin1 = formatea_fecha ( $fecha_fin );

$msgapl = "";
if ($aplazados > 0)
{
	$cumplir = $expulsi�n - $aplazados;
	$msgapl = " de los cuales cumplira ".$cumplir." de forma inmediata quedando el resto de la sanci�n aplazada, la cual se ejecutar� en caso de que recayese en conductas contrarias a las normas de convivencia,";
}

$titulo = "Comunicaci�n de expulsi�n del centro";
$cuerpo = "El Director del ".$config['centro_denominacion']." de ".$config['centro_localidad'].", en virtud de las facultades otorgadas por el Plan de Convivencia del Centro, regulado por el Decreto 327/2010 de 13 de Julio en el que se aprueba el Reglamento Org�nico de los Institutos de Educaci�n Secundaria, una vez estudiado el expediente disciplinario de $nombre $apellidos, alumno/a del grupo $unidad.

Acuerda:

1.- Tipificar la conducta de este alumno(a) como $mensgravedad a las normas de convivencia del Centro, suponiendo falta $gravedad.
2.- Imponer las siguientes correcciones:
     Amonestaci�n que constar� en el expediente individual del alumno/a. 
    Suspensi�n del derecho de asistencia a clase por un periodo de $expulsion d�as lectivos,".$msgapl." desde el $inicio1 hasta el $fin1, ambos inclusive. Durante esos d�as, el alumno/a deber� permanecer en su domicilio durante el horario escolar realizando los deberes o trabajos que tenga encomendados. La no realizaci�n de las tareas supone el incumplimiento de la correcci�n por lo que dicha conducta se considerar� gravemente perjudicial para la convivencia y, como consecuencia, conllevar�a la imposici�n de una nueva medida correctora.

NOTA: El padre, madre o representante legal podr� presentar en el registro de entrada del Centro, en el plazo de dos d�as lectivos, una reclamaci�n dirigida a la Direcci�n del Centro contra las correcciones impuestas.

En ".$config['centro_localidad'].", a ".strftime("%e de %B de %Y", strtotime($fecha)).".";

if ( $exp_sms != "" )
{
	$aclaracion = "conducta ".$mensgravedad." a las normas de convivencia del Centro";
	$mensms = "Pongo en su conocimiento que con fecha ".strftime("%e de %B de %Y", strtotime($fecha))." su hijo/a $nombre $apellidos alumno del grupo $unidad ha recibido una expulsi�n por ".$aclaracion;
	enviarsmsemail( $mensms, "Comunicaci�n de expulsi�n del centro", $cuerpo, $db_con, $claveal, $nombre, $apellidos, $unidad, $tfno, $tfno_u, $correo, $informa, 1, $id, 1, $config['jefatura_email']);	
}

require ("../../../pdf/fpdf.php");

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
	$MiPDF->Multicell(0, 5, $cuerpo, 0, 'L', 0 );
	$MiPDF->Ln(10);
	
	
	//FIRMAS
	$MiPDF->Cell (90, 5, 'Representante legal', 0, 0, 'C', 0 );
	$MiPDF->Cell (55, 5, 'Director/a del centro', 0, 1, 'C', 0 );
	$MiPDF->Cell (55, 20, '', 0, 0, 'C', 0 );
	$MiPDF->Cell (55, 20, '', 0, 1, 'C', 0 );
	$MiPDF->SetFont('NewsGotT', '', 10);
	$MiPDF->Cell (90, 5, 'Fdo. '.$padre, 0, 0, 'C', 0 );
	$MiPDF->Cell (55, 5, 'Fdo. '.mb_convert_case($config['directivo_direccion'], MB_CASE_TITLE, "iso-8859-1"), 0, 1, 'C', 0 );
	
  
$result1 = mysqli_query($db_con, "select distinct Fechoria.fecha, Fechoria.asunto, Fechoria.informa, Fechoria.claveal from Fechoria, alma where alma.claveal = Fechoria.claveal and alma.claveal = $claveal and Fechoria.fecha >= '".$config['curso_inicio']."' order by Fechoria.fecha DESC, alma.unidad, alma.apellidos");
$num = mysqli_num_rows($result1);

$tit_fech = "PROBLEMAS DE CONVIVENCIA DEL ALUMNO EN EL CURSO ACTUAL";
$MiPDF->Addpage ();
	$MiPDF->SetFont ( 'NewsGotT', '', 12);
	$MiPDF->SetTextColor ( 0, 0, 0 );
	$MiPDF->Ln (15);
	$MiPDF->SetFont ( 'NewsGotT', 'B', 12);
	$MiPDF->Multicell ( 0, 4, $tit_fech, 0, 'L', 0 );
	$MiPDF->Ln ( 3 );
	$MiPDF->SetFont ( 'NewsGotT', '', 12);
	
$result = mysqli_query($db_con, "select distinct Fechoria.fecha, Fechoria.asunto, Fechoria.informa, Fechoria.claveal from Fechoria, alma where alma.claveal = Fechoria.claveal and alma.claveal = $claveal and Fechoria.fecha >= '".$config['curso_inicio']."' order by Fechoria.fecha DESC, alma.unidad, alma.apellidos limit 0, 24");

 // print "$AUXSQL";
  while($row = mysqli_fetch_array($result))
                {
$dato = "$row[0]   $row[1]";
$MiPDF->Ln ( 4 );
$MiPDF->Multicell ( 0, 4, $dato, 0, 'J', 0 );              
                }

                
if ($num > '24' and $num < '49') 
{		
$tit_fech = "PROBLEMAS DE CONVIVENCIA DEL ALUMNO EN EL CURSO ACTUAL";
$MiPDF->Addpage ();
	$MiPDF->SetFont ( 'NewsGotT', '', 12);
	$MiPDF->SetTextColor ( 0, 0, 0 );
	$MiPDF->Ln (15);
	$MiPDF->SetFont ( 'NewsGotT', 'B', 12);
	$MiPDF->Multicell ( 0, 4, $tit_fech, 0, 'L', 0 );
	$MiPDF->Ln ( 3 );
	$MiPDF->SetFont ( 'NewsGotT', '', 12);
	
$result = mysqli_query($db_con, "select distinct Fechoria.fecha, Fechoria.asunto, Fechoria.informa, Fechoria.claveal from Fechoria, alma where alma.claveal = Fechoria.claveal and alma.claveal = $claveal and Fechoria.fecha >= '".$config['curso_inicio']."' order by Fechoria.fecha DESC, alma.unidad, alma.apellidos limit 25, 24");
 // print "$AUXSQL";
  while($row = mysqli_fetch_array($result))
                {
$pr = explode(", ",$row[2]);
$dato = "$row[0]   $row[1]";
$MiPDF->Ln ( 4 );
$MiPDF->Multicell ( 0, 4, $dato, 0, 'J', 0 );             
                }
}


if ($num > '48' and $num < '73') 
{		
$tit_fech = "PROBLEMAS DE CONVIVENCIA DEL ALUMNO EN EL CURSO ACTUAL";
$MiPDF->Addpage ();
	$MiPDF->SetFont ( 'NewsGotT', '', 12);
	$MiPDF->SetTextColor ( 0, 0, 0 );
	$MiPDF->Ln (15);
	$MiPDF->SetFont ( 'NewsGotT', 'B', 12);
	$MiPDF->Multicell ( 0, 4, $tit_fech, 0, 'L', 0 );
	$MiPDF->Ln ( 3 );
	$MiPDF->SetFont ( 'NewsGotT', '', 12);
	
$result = mysqli_query($db_con, "select distinct Fechoria.fecha, Fechoria.asunto, Fechoria.informa, Fechoria.claveal from Fechoria, alma where alma.claveal = Fechoria.claveal and alma.claveal = $claveal and Fechoria.fecha >= '".$config['curso_inicio']."' order by Fechoria.fecha DESC, alma.unidad, alma.apellidos limit 50,24");
 // print "$AUXSQL";
  while($row = mysqli_fetch_array($result))
                {
$pr = explode(", ",$row[2]);
$dato = "$row[0]   $row[1]";
$MiPDF->Ln ( 4 );
$MiPDF->Multicell ( 0, 4, $dato, 0, 'J', 0 );              
                }
}


if ($num > '74' and $num < '24') 
{		
$tit_fech = "PROBLEMAS DE CONVIVENCIA DEL ALUMNO EN EL CURSO ACTUAL";
$MiPDF->Addpage ();
	$MiPDF->SetFont ( 'NewsGotT', '', 12);
	$MiPDF->SetTextColor ( 0, 0, 0 );
	$MiPDF->Ln (15);
	$MiPDF->SetFont ( 'NewsGotT', 'B', 12);
	$MiPDF->Multicell ( 0, 4, $tit_fech, 0, 'L', 0 );
	$MiPDF->Ln ( 3 );
	$MiPDF->SetFont ( 'NewsGotT', '', 12);
	
$result = mysqli_query($db_con, "select distinct Fechoria.fecha, Fechoria.asunto, Fechoria.informa, Fechoria.claveal from Fechoria, alma where alma.claveal = Fechoria.claveal and alma.claveal = $claveal and Fechoria.fecha >= '".$config['curso_inicio']."' order by Fechoria.fecha DESC, alma.unidad, alma.apellidos limit 75,24");
 // print "$AUXSQL";
  while($row = mysqli_fetch_array($result))
                {
$pr = explode(", ",$row[2]);
$dato = "$row[0]   $row[1]";
$MiPDF->Ln ( 4 );
$MiPDF->Multicell ( 0, 4, $dato, 0, 'J', 0 );              
                }
}

// RECIBI
$txt_recibi = "D./D�a. $nombre $apellidos, alumno/a del grupo ".$unidad.", he recibido la $titulo con referencia Fec/".$idfec." registrado el ".strftime("%e de %B de %Y", strtotime($fecha)).".";

$MiPDF->Ln(8);
$MiPDF->Line(25, $MiPDF->GetY(), 190, $MiPDF->GetY());
$MiPDF->Ln(5);

$MiPDF->SetFont('NewsGotT', 'B', 12);
	$MiPDF->Multicell(0, 5, 'RECIB�', 0, 'C', 0 );
	$MiPDF->Ln(5);
	
	$MiPDF->SetFont('NewsGotT', '', 12);
	$MiPDF->Multicell(0, 5, $txt_recibi, 0, 'L', 0 );
	$MiPDF->Ln(15);
	$MiPDF->Cell (55, 25, '', 0, 0, 'L', 0 );
	$MiPDF->Cell (55, 5, 'Fdo. '.$nombre.' '.$apellidos, 0, 0, 'L', 0 );

            
$MiPDF->Output ();

?>
