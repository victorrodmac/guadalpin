<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

require("../../pdf/pdf_js.php");
//require("../pdf/mc_table.php");

class PDF_AutoPrint extends PDF_JavaScript
{
function AutoPrint($dialog=false)
{
    //Open the print dialog or start printing immediately on the standard printer
    $param=($dialog ? 'true' : 'false');
    $script="print($param);";
    $this->IncludeJS($script);
}

function AutoPrintToPrinter($server, $printer, $dialog=false)
{
    //Print on a shared printer (requires at least Acrobat 6)
    $script = "var pp = getPrintParams();";
    if($dialog)
        $script .= "pp.interactive = pp.constants.interactionLevel.full;";
    else
        $script .= "pp.interactive = pp.constants.interactionLevel.automatic;";
    $script .= "pp.printerName = '\\\\\\\\".$server."\\\\".$printer."';";
    $script .= "print(pp);";
    $this->IncludeJS($script);
}
}
define ( 'FPDF_FONTPATH', '../../pdf/font/' );
# creamos el nuevo objeto partiendo de la clase ampliada
$MiPDF = new PDF_AutoPrint();
$MiPDF->SetMargins ( 20, 20, 20 );
# ajustamos al 100% la visualización
$MiPDF->SetDisplayMode ( 'fullpage' );
// Consulta  en curso. 
if (substr($curso, 0, 1) == '1') {
	$mas = ", colegio";
}
//echo "select distinct id_matriculas from matriculas_temp, matriculas where id=id_matriculas order by curso".$mas.", letra_grupo, apellidos, nombre" ;
$result0 = mysqli_query($db_con, "select distinct id_matriculas from matriculas_temp, matriculas where id=id_matriculas order by curso".$mas.", letra_grupo, apellidos, nombre" );
while ($id_ar = mysqli_fetch_array($result0)) {
$id = "";
$id = $id_ar[0];
$result = mysqli_query($db_con, "select * from matriculas where id = '$id'");
if ($row = mysqli_fetch_array ( $result )) {
	$apellidos = "Apellidos del Alumno: ". $row['apellidos'];
	 $nombre= "Nombre: ".$row['nombre'];
	 $nacido= "Nacido en: ".$row['nacido'];
	 $nacimiento = cambia_fecha($row['nacimiento']);
	 $provincia= "Provincia de: ".$row['provincia'];
	 $fecha_nacimiento= "Fecha de Nacimiento: $nacimiento";
	 $domicilio= "Domicilio: ".$row['domicilio'];
	 $localidad= "Localidad: ".$row['localidad'];
	 $dni= "DNI del alumno: ".$row['dni'];
	 $padre= "Apellidos y nombre del Tutor legal 1: ".$row['padre'];
	 $pa = explode(", ", $row['padre']);
	 $papa = "$pa[1] $pa[0]";
	 $dnitutor= "DNI: ".$row['dnitutor'];
	 $madre= "Apellidos y nombre del Tutor legal 2: ".$row['madre'];
	 $dnitutor2= "DNI: ".$row['dnitutor2'];
	 $telefono1= "Tel�fono Casa: ".$row['telefono1'];
	 $telefono2= "Tel�fono M�vil: ".$row['telefono2'];
	 $telefonos="$telefono1\n   $telefono2";
	 $idioma = $row['idioma'];
	 $religion = $row['religion'];
	 $itinerario = $row['itinerario'];
	 $optativas4 = $row['optativas4'];
	 $matematicas3 = $row['matematicas3'];
	 $ciencias4 = $row['ciencias4'];

	 if ($row['colegio'] == "Otro Centro") { $colegio= "Centro de procedencia:  ".$row['otrocolegio']; }else{	 $colegio= "Centro de procedencia:  ".$row['colegio']; }
	 $correo= "Correo electr�nico de padre o madre: ".$row['correo'];

	 // Optativas y refuerzos
	 $n_curso = substr($curso, 0, 1);
	 $n_curso2 = $n_curso-1;

	 if ($n_curso == '1') {
	$opt1 = array("Alem�n 2� Idioma","Cambios Sociales y G�nero", "Franc�s 2� Idioma","Tecnolog�a Aplicada");
	$a1 = array("Actividades de refuerzo de Lengua Castellana", "Actividades de refuerzo de Matem�ticas", "Actividades de refuerzo de Ingl�s", "Ampliaci�n: Taller T.I.C.", "Ampliaci�n: Matem�ticas Recreativas", "Ampliaci�n: Taller de Teatro", "Ampliaci�n: Taller de Lenguas Extranjeras");
	 }

	 if ($n_curso == '2') {
	$opt1 = array("Alem�n 2� Idioma","Cambios Sociales y G�nero", "Franc�s 2� Idioma");
	$a1 = array("Actividades de refuerzo de Lengua Castellana ", "Actividades de refuerzo de Matem�ticas", "Actividades de refuerzo de Ingl�s", "Ampliaci�n: Taller T.I.C. II", "Ampliaci�n: Taller de Teatro II");
	$opt21 = array("Alem�n 2� Idioma","Cambios Sociales y G�nero", "Franc�s 2� Idioma","Tecnolog�a Aplicada");
	$a21 = array("Actividades de refuerzo de Lengua Castellana", "Actividades de refuerzo de Matem�ticas", "Actividades de refuerzo de Ingl�s", "Ampliaci�n: Taller T.I.C.", "Ampliaci�n: Matem�ticas Recreativas", "Ampliaci�n: Taller de Teatro", "Ampliaci�n: Taller de Lenguas Extranjeras (Se debe elegir Alem�n o Franc�s como Optativa)");
	 }

	 if ($n_curso == '3') {
	$opt1 = array("Alem�n 2� Idioma","Cambios Sociales y G�nero", "Franc�s 2� Idioma","Cultura Cl�sica", "Taller T.I.C. III", "Taller de Cer�mica", "Taller de Teatro");
	$a1 = array("Actividades de refuerzo de Lengua Castellana ", "Actividades de refuerzo de Matem�ticas", "Actividades de refuerzo de Ingl�s", "Ampliaci�n: Lengua", "Ampliaci�n: Matem�ticas", "Ampliaci�n: Ingl�s");
	$opt21 = array("Alem�n 2� Idioma","Cambios Sociales y G�nero", "Franc�s 2� Idioma");
	$a21 = array("Actividades de refuerzo de Lengua Castellana ", "Actividades de refuerzo de Matem�ticas", "Actividades de refuerzo de Ingl�s", "Ampliaci�n: Taller T.I.C. II", "Ampliaci�n: Taller de Teatro II");
	 }

if ($n_curso == '4') {
	$it41 = array("(Bachillerato de Ciencias)", "Matem�ticas Acad�micas", "Tecnolog�a (S�lo Ingenier�a y Arquitectura)", "F�sica y Qu�mica", "Biolog�a y Geolog�a", "Econom�a");
	$it42 = array("(Bachillerato de Humanidades y Ciencias Sociales)", "Matem�ticas Acad�micas", "Lat�n", "Econom�a");
	$it43 = array("(Ciclos Formativos y Mundo Laboral)", "Matem�ticas Aplicadas", "Tecnolog�a", "Ciencias Aplicadas a la Actividad Profesional", "Iniciaci�n a la Actividad Emprendedora y Empresarial");
	$opt1=array("Alem�n 2� Idioma", "Franc�s 2� Idioma", "TIC", "Ed. Pl�stica y Visual", "M�sica");
	$opt21 = array("Alem�n 2� Idioma","Cambios Sociales y G�nero", "Franc�s 2� Idioma","Cultura Cl�sica", "Taller T.I.C. III", "Taller de Cer�mica", "Taller de Teatro");
	$a21 = array("Actividades de refuerzo de Lengua Castellana ", "Actividades de refuerzo de Matem�ticas", "Actividades de refuerzo de Ingl�s", "Ampliaci�n: Lengua", "Ampliaci�n: Matem�ticas", "Ampliaci�n: Ingl�s");
}

if ($n_curso < '4'){
	 $optativa1= $row['optativa1']." - $opt1[0]";
	 $optativa2= $row['optativa2']." - $opt1[1]";
	 $optativa3= $row['optativa3']." - $opt1[2]";
	 $optativa4= $row['optativa4']." - $opt1[3]";
if($n_curso=='3'){
	 $optativa5= $row['optativa5']." - $opt1[4]";
	 $optativa6= $row['optativa6']." - $opt1[5]";
	 $optativa7= $row['optativa7']." - $opt1[6]";
		 }
}
else{
	 $optativa1= $row['optativa1']." - $opt1[0]";
	 $optativa2= $row['optativa2']." - $opt1[1]";
	 $optativa3= $row['optativa3']." - $opt1[2]";
	 $optativa4= $row['optativa4']." - $opt1[3]";
	 $optativa5= $row['optativa5']." - $opt1[4]";
	}

	 for ($i=1;$i<8;$i++)
	 {
	 	if ($row['act1'] == $i) {
	 		${act.$i} = " X  " . $a1[$i-1];
	 	}
	 	else{
	 		${act.$i} = "      ".$a1[$i-1];
	 	}
	 }
 
	 
	 $optativa21= $row['optativa21']." - $opt21[0]";
	 $optativa22= $row['optativa22']." - $opt21[1]";
	 $optativa23= $row['optativa23']." - $opt21[2]";
	 $optativa24= $row['optativa24']." - $opt21[3]";
 	 $optativa25= $row['optativa25']." - $opt21[4]";
 	 $optativa26= $row['optativa26']." - $opt21[5]";
 	 $optativa27= $row['optativa27']." - $opt21[6]";
	 
	 for ($i=1;$i<8;$i++)
	 {
	 	if ($row['act21'] == $i) {
	 		${act2.$i} = " X  " . $a21[$i-1];

	 	}
	 	else{
	 		${act2.$i} = "      ".$a21[$i-1];
	 	}
	 }

	 if ($n_curso == '4'){
	 	for ($i=1;$i<7;$i++)
	 {
	 	if ($row['act1'] == $i) {
			${act2.$i} = " X  " . $a21[$i-1];	
		 }
		 else{
	 		${act2.$i} = "      ".$a21[$i-1];
	 	}
	 }
	}

	 $observaciones= "OBSERVACIONES: ".$row['observaciones'];
	 $texto_exencion= "El alumno solicita la exenci�n de la Asignatura Optativa";
	 $texto_bilinguismo= "El alumno solicita participar en el Programa de Bilinguismo";
	 $curso = $row['curso'];
	 $fecha_total = $row['fecha'];
	 $transporte = $row['transporte'];
	 $ruta_este = $row['ruta_este'];
	 $ruta_oeste = $row['ruta_oeste'];
	 $texto_transporte = "Transporte escolar: $ruta_este$ruta_oeste.";
	 $sexo = $row['sexo'];
	 if ($row['hermanos'] == '' or $row['hermanos'] == '0') { $hermanos = ""; } else{ $hermanos = $row['hermanos']; }
	 
	 $nacionalidad = $row['nacionalidad'];
	 $itinerario = $row['itinerario'];
	 $optativas4 = $row['optativas4'];
}
$fech = explode(" ",$fecha_total);
$fecha = $fech[0];
$titulo1 = "SOLICITUD DE MATR�CULA EN ".$n_curso."� DE E.S.O.";
$an = substr($config['curso_actual'],0,4);
$an1 = $an+1;
$hoy = formatea_fecha(date('Y-m-d'));
$cuerpo3 = "En ".$config['centro_localidad'].", a $hoy
Firma del Padre/Madre/Representante legal D/D�



Fdo. D/D� ---------------------------------------------
que asegura la veracidad de los datos registrados en el formulario.
";
$datos_centro = "PROTECCI�N DE DATOS.\n En cumplimiento de lo dispuesto en la Ley Org�nica 15/1999, de 13 de Diciembre, de Protecci�n de Datos de Car�cter Personal, el ".$config['centro_denominacion']." le informa que los datos personales obtenidos mediante la cumplimentaci�n de este formulario y dem�s documentaci�n que se adjunta van a ser incorporados, para su tratamiento, a nuestra base de datos, con la finalidad de recoger los datos personales y acad�micos del alumnado que cursa estudios en nuestro Centro, as� como de las respectivas unidades familiares.\n De acuerdo con lo previsto en la Ley, puede ejercer los derechos de acceso, rectificaci�n, cancelaci�n y oposici�n dirigiendo un escrito a la Secretar�a del Instituto en ".$config['centro_direccion'].", ".$config['centro_codpostal']." ".$config['centro_localidad'].", M�laga";

	# insertamos la primera pagina del documento
	$MiPDF->Addpage ();
	$MiPDF->SetFont ( 'Times', '', 10  );
	$MiPDF->SetTextColor ( 0, 0, 0 );
	$MiPDF->SetFillColor(230,230,230);
	
	
	// Formulario de matr�cula

	#Cuerpo.
	$MiPDF->Image ( '../../img/encabezado2.jpg', 10, 10, 180, '', 'jpg' );
	$MiPDF->Ln ( 10 );
	$MiPDF->Multicell ( 0, 4, $titulo1, 0, 'C', 0 );
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(168,6,"DATOS PERSONALES DEL ALUMNO",1,0,'C',1);
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(112,8,$apellidos,1);

	$MiPDF->Cell(56,8,$nombre,1);
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(56,8,$nacido,1);
	$MiPDF->Cell(56,8,$provincia,1);
	$MiPDF->Cell(56,8,$fecha_nacimiento,1);
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(72,8,$domicilio,1);
	$MiPDF->Cell(40,8,$localidad,1);
	$MiPDF->Cell(56,8,$dni,1);
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(112,8,$padre,1);
	$MiPDF->Cell(56,8,$dnitutor,1);
	if (strlen($madre)>38) {
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(112,8,$madre,1);
	$MiPDF->Cell(56,8,$dnitutor2,1);
	}
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(90,8,$telefonos,1);
	$MiPDF->Cell(78,8,$colegio,1);
	if ($transporte=='1') {
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(168,8,$texto_transporte,1);	
	}
	if (strlen($correo)>38) {
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(168,8,$correo,1);	
	}
	$MiPDF->Ln ( 10 );
	$MiPDF->Cell(84,6,"IDIOMA EXTRANJERO",1,0,'L',1);
	$MiPDF->Cell(84,6,"ENSE�ANZA DE RELIGI�N O ALTERNATIVA",1,0,'L',1);
	$MiPDF->Ln ( 6);
	$MiPDF->Cell(84,8,$idioma,0);
	$MiPDF->Cell(84,8,$religion,0);
	$MiPDF->Ln ( 8 );
	if($n_curso<'3'){
	$MiPDF->Cell(84,6,"ASIGNATURAS OPTATIVAS",1,0,'L',1);
	$MiPDF->Cell(84,6,"PROGRAMA DE REFUERZO O ALTERNATIVO",1,0,'L',1);
	$MiPDF->Ln ( 6 );
	}
	else{
		if($n_curso=='4'){

	$extra_it="";
	if(stristr($itinerario,"1")==TRUE){$extra_it="1 (".$ciencias4.")";}
	else{$extra_it=$itinerario." ";}
	//echo $ciencias4;
	if(strlen($optativas4)>1){$extra_it.=" - $optativas4";}	
	//if ($n_curso == '4') { $extra="4ESO (It. $itinerario".$extra_it.")";}
	
	$MiPDF->Cell(168,6,"ITINERARIO $extra_it.",1,0,'C',1);
	$MiPDF->Ln ( 6 );
		}
		else{
	$MiPDF->Cell(168,6,"ASIGNATURAS OPTATIVAS",1,0,'C',1);
	$MiPDF->Ln ( 6 );
		}
	}
	if($n_curso=='3'){
	if ($matematicas3=="A") {
		$mat_3="Matem�ticas Acad�micas (Bachillerato)";}elseif($matematicas3=="B"){$mat_3="Matem�ticas Aplicadas (Formaci�n Profesional)";
		}
	$MiPDF->Cell(168,6,$mat_3,1,0,'C',0);
	$MiPDF->Ln ( 5 );
	}

	// $MyPDF->FillColor();
	if($n_curso<4){
	$MiPDF->Cell(84,8,$optativa1,0);
	$MiPDF->Cell(84,8,$act1,0);
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(84,8,$optativa2,0);
	$MiPDF->Cell(84,8,$act2,0);
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(84,8,$optativa3,0);
	$MiPDF->Cell(84,8,$act3,0);
	$MiPDF->Ln ( 5 );
	if ($n_curso=='2') {
	$MiPDF->Cell(84,8,"",0);
	}
	else{
	$MiPDF->Cell(84,8,$optativa4,0);
	}
	$MiPDF->Cell(84,8,$act4,0);
	$MiPDF->Ln ( 5 );

	if ($n_curso=='1') {
	$MiPDF->Cell(84,8,"",0);
	$MiPDF->Cell(84,8,$act5,0);
	$MiPDF->Cell(84,8,"",0);
	$MiPDF->Cell(84,8,$act6,0);
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(84,8,"",0);
	$MiPDF->Cell(84,8,$act7,0);
	$MiPDF->Ln ( 5 );
	}
	elseif ($n_curso=='2') {
	$MiPDF->Cell(84,8,"",0);
	$MiPDF->Cell(84,8,$act5,0);
	}
	elseif ($n_curso=='3') {
	$MiPDF->Cell(84,8,$optativa5,0);
	$MiPDF->Cell(84,8,$act5,0);
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(84,8,$optativa6,0);
	$MiPDF->Cell(84,8,$act6,0);
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(84,8,$optativa7,0);
	$MiPDF->Cell(84,8,"",0);
	$MiPDF->Ln ( 5 );
	}
	}
	elseif($n_curso=='4'){
	$MiPDF->Cell(168,6,"ASIGNATURAS OPTATIVAS",1,0,'C',1);
	$MiPDF->Ln ( 6 );
	$MiPDF->Cell(84,8,$optativa1,0);
	$MiPDF->Cell(84,8,$optativa4,0);
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(84,8,$optativa2,0);
	$MiPDF->Cell(84,8,$optativa5,0);
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(84,8,$optativa3,0);
	$MiPDF->Cell(84,8,"",0);
	$MiPDF->Ln ( 5 );
	}

	if (substr($curso, 0, 1) == 2 or substr($curso, 0, 1) == 3 or substr($curso, 0, 1) == 4){
	$MiPDF->Ln ( 7 );
	$MiPDF->Cell(168,6,"ASIGNATURAS DE ".$n_curso2."� DE ESO",1,0,'C',1);
	$MiPDF->Ln ( 6 );
	$MiPDF->Cell(84,6,"ASIGNATURA OPTATIVA",1,0,'L',1);
	$MiPDF->Cell(84,6,"PROGRAMA DE REFUERZO O ALTERNATIVO",1,0,'L',1);
	$MiPDF->Ln ( 6 );
	// $MyPDF->FillColor();
	$MiPDF->Cell(84,8,$optativa21,0);
	$MiPDF->Cell(84,8,$act21,0);
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(84,8,$optativa22,0);
	$MiPDF->Cell(84,8,$act22,0);
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(84,8,$optativa23,0);
	$MiPDF->Cell(84,8,$act23,0);
	$MiPDF->Ln ( 5 );
	if (substr($curso, 0, 1) == 3){
	$MiPDF->Cell(84,8,"",0);
	$MiPDF->Cell(84,8,$act24,0);
	$MiPDF->Ln ( 5 );
	}
	else{
	$MiPDF->Cell(84,8,$optativa24,0);
	$MiPDF->Cell(84,8,$act24,0);
	$MiPDF->Ln ( 5 );
	}

	if (substr($curso, 0, 1) == 4) {
	$MiPDF->Cell(84,8,$optativa25,0);
	$MiPDF->Cell(84,8,$act25,0);
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(84,8,$optativa26,0);
	$MiPDF->Cell(84,8,$act26,0);
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(84,8,$optativa27,0);
	$MiPDF->Cell(84,8,"",0);
		}
	if (substr($curso, 0, 1) == 2 or substr($curso, 0, 1) == 3) {
	$MiPDF->Cell(84,8,"",0);
	$MiPDF->Cell(84,8,$act25,0);
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(84,8,"",0);
	$MiPDF->Cell(84,8,$act26,0);
		}
	}

	else{
	$MiPDF->Ln ( 7 );		
	}
		
	if ($row[39]=='1') {
	$MiPDF->Ln ( 7 );	
	$MiPDF->Cell(168,5,$texto_exencion,1,0,'L',1);
	}
	if ($row[40]=='Si') {
		$MiPDF->Ln ( 7 );
		$MiPDF->Cell(168,5,$texto_bilinguismo,1,0,'L',1);
	}
	$MiPDF->Ln ( 8 );
	if (strlen($observaciones)>15) {
	$MiPDF->MultiCell(168,4,$observaciones,0,'L');
	$MiPDF->Ln ( 3);		
	}
	else{
	$MiPDF->Ln ( 8 );		
	}

	}
   $MiPDF->AutoPrint(true);     
   $MiPDF->Output ();

?>