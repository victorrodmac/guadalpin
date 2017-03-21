<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

require("../../pdf/pdf_js.php");

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
$n_curso = substr($curso, 0, 1);
$result0 = mysqli_query($db_con, "select distinct id_matriculas from matriculas_temp, matriculas where id=id_matriculas order by curso".$mas.", letra_grupo, apellidos, nombre" );
//echo "select distinct id_matriculas from matriculas_temp, matriculas where id=id_matriculas order by curso".$mas.", letra_grupo, apellidos, nombre";
while ($id_ar = mysqli_fetch_array($result0)) {
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
	  if(stristr($optativas4,'Iniciaci')==TRUE){$optativas4='Iniciaci�n Actividad Emprendedora';}
	 $matematicas3 = $row['matematicas3'];
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
	$a21 = array("Actividades de refuerzo de Lengua Castellana ", "Actividades de refuerzo de Matem�ticas", "Actividades de refuerzo de Ingl�s", "Ampliaci�n: Taller T.I.C. II", "Ampliaci�n: Taller de Teatro II");
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

	 for ($i=1;$i<6;$i++)
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
	 for ($i=1;$i<6;$i++)
	 {
	 	if ($row['act21'] == $i) {
	 		${act2.$i} = " X  " . $a21[$i-1];
	 	}
	 	else{
	 		${act2.$i} = "      ".$a21[$i-1];
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
//$hoy = formatea_fecha($fech[0]);
$an = substr($config['curso_actual'],0,4);
$an1 = $an+1;
$hoy = formatea_fecha(date('Y-m-d'));
$titulo_documentacion = "DOCUMENTACI�N NECESARIA PARA LA MATRICULACI�N";
$documentacion = "1. Fotocopia del D.N.I. Obligatorio por ley para todo alumnado mayor de 14 a�os. Si el alumnado es menor de 14 a�os y no se dispone de D.N.I., se admitir� una fotocopia del Libro de Familia o Certificado de Nacimiento.
2. El alumnado procedente de otros Institutos o de Colegios no adscritos a nuestro Centro deben aportar el Certificado de expediente acad�mico..
3. Los alumnos que se matriculen a partir de 3� de ESO tienen que abonar 2 euros para la cuota obligatoria del Seguro Escolar.
4. Cuota voluntaria de 12 euros para la Asociaci�n de Padres y Madres del Centro.
";
$datos_junta = "PROTECCI�N DE DATOS.\n En cumplimiento de lo dispuesto en la Ley Org�nica 15/1999, de 13 de Diciembre, de Protecci�n de Datos de Car�cter Personal, la Consejer�a de Educaci�n le informa que los datos personales obtenidos mediante la cumplimentaci�n de este formulario y dem�s documentaci�n que se adjunta van a ser incorporados, para su tratamiento, al fichero 'S�neca. Datos personales y acad�micos del alumnado', con la finalidad de recoger los datos personales y acad�micos del alumnado que cursa estudios en centros dependientes de la Conserjer�a de Educaci�n, as� como de las respectivas unidades familiares.\n De acuerdo con lo previsto en la Ley, puede ejercer los derechos de acceso, rectificaci�n, cancelaci�n y oposici�n dirigiendo un escrito a la Secretar�a General T�cnica de la Conserjer�a de Educaci�n de la Junta de Andaluc�a en Avda. Juan Antonio de Vizarr�n, s/n, Edificio Torretriana 41071 SEVILLA";

// Normas de telefon�a m�vil
$titulo_moviles = "SOBRE EL USO DE TEL�FONOS M�VILES Y OTROS DISPOSITIVOS EN EL CENTRO";

$texto_moviles="
           Estimadas familias:

     Les informamos de que est� prohibido el uso de tel�fonos m�viles y otros dispositivos de grabaci�n/reproducci�n multimedia por parte del alumnado durante el horario escolar. Dicha medida es consecuencia de salvaguardar la intimidad tanto del alumnado como del profesorado, quienes pudieran ver vulnerados sus derechos de protecci�n por grabaciones y/o difusiones de im�genes capturadas de forma ajena a su voluntad. Por este motivo, recordamos que la utilizaci�n de estos aparatos est� prohibida en el Centro. En caso de que alg�n alumno sea sorprendido con cualquier dispositivo electr�nico, este le ser� requisado aplic�ndose las medidas que en materia de convivencia hay estipuladas en nuestro Reglamento al efecto.
     El tel�fono m�vil en el Centro es absolutamente innecesario y constituye un elemento perturbador del clima de estudio y trabajo en el mismo. En aquellos casos en los que el alumnado tenga que comunicarse con la familia (que se entienden como situaciones graves o de urgencia), los tel�fonos del Centro est�n siempre a disposici�n del alumnado. 
     Por �ltimo anunciar que dado que se ha dejado claro que est�n prohibidos estos dispositivos en el instituto, informamos que el Centro no se hace responsable ni va a mediar en situaciones donde se produzcan <<desapariciones>> de dichos dispositivos dentro de nuestras instalaciones.";

$final_moviles="
D./D�. $papa, con DNI n�mero ".$row['dnitutor'].", padre/madre/tutor legal del alumno/ a ".$row['nombre']." ".$row['apellidos']." del curso $curso, teniendo en cuenta la informaci�n aportada, es conocedor de la prohibici�n de la tenencia y uso de los tel�fonos m�viles, as� como de cualquier otro dispositivo electr�nico que difunda o grabe im�genes de v�deo/audio.
";
$firma_moviles="
Firmado,


Padre/madre/tutor legal.";

// Formulario de la junta	
for($i=1;$i<3;$i++){
	$MiPDF->Addpage ();
	#### Cabecera con direcci�n
	$MiPDF->SetFont ( 'Times', 'B', 10  );
	$MiPDF->SetTextColor ( 0, 0, 0 );
	$MiPDF->SetFillColor(230,230,230);
	$MiPDF->Image ( '../../img/encabezado2.jpg', 10, 10, 180, '', 'jpg' );
	$MiPDF->Ln ( 8 );
	$titulo2 = "EDUCACI�N SECUNDARIA OBLIGATORIA                                                           MATRICULA";
	$MiPDF->Multicell ( 0, 4, $titulo2, 0, 'L', 0 );

	$MiPDF->Ln ( 8 );
	$MiPDF->SetFont ( 'Times', '', 7 );
	$MiPDF->Cell(21,6,"N� MATR�CULA: ",0);
	$MiPDF->Cell(24,6,"",1);
	$adv = "         ANTES DE FIRMAR ESTE IMPRESO, COMPRUEBE QUE CORRESPONDE A LA
	        ETAPA EDUCATIVA EN LA QUE DESEA REALIZAR LA MATR�CULA.
	        ESTA MATR�CULA EST� CONDICIONADA A LA COMPROBACI�N DE LOS DATOS,  DE CUYA
	        VERACIDAD SE RESPONSABILIZA LA PERSONA FIRMANTE. ";
	$MiPDF->MultiCell(120, 3, $adv,0,'L',0);
	$MiPDF->Ln ( 5 );
	$MiPDF->SetFont ( 'Times', '', 10 );
	$MiPDF->Cell(5,6,"1",1,0,'C',1);
	$MiPDF->Cell(163,6,"DATOS PERSONALES DEL ALUMNO",1,0,'C',1);
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(84,5,"APELLIDOS",0,0,"C");
	$MiPDF->Cell(84,5,"NOMBRE",0,0,"C");
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(84,5,$row['apellidos'],1,0,'C');
	$MiPDF->Cell(84,5,$row['nombre'],1,0,'C');
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(40,5,"FECHA NACIMIENTO",0,0,"C");
	$MiPDF->Cell(26,5,"DNI/NIE",0,0,"C");
	$MiPDF->Cell(26,5,"TEL�FONO",0,0,"C");
	$MiPDF->Cell(35,5,"NACIONALIDAD",0,0,"C");
	$MiPDF->Cell(21,5,"HERMANOS",0,0,"C");
	$MiPDF->Cell(20,5,"SEXO",0,0,"C");
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(40,5,$nacimiento,1,0,'C');
	$MiPDF->Cell(26,5,$row['dni'],1,0,'C');
	$MiPDF->Cell(26,5,$row['telefono1'],1,0,'C');
	$MiPDF->Cell(35,5,$nacionalidad,1,0,'C');
	$MiPDF->Cell(21,5,$hermanos,1,0,'C');
	$MiPDF->Cell(20,5,$sexo,1,0,'C');
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(100,5,"DOMICILIO",0,0,"C");
	$MiPDF->Cell(25,5,"LOCALIDAD",0,0,"C");
	$MiPDF->Cell(15,5,"C.P.",0,0,"C");
	$MiPDF->Cell(28,5,"PROVINCIA",0,0,"C");
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(100,5,$row['domicilio'],1,0,'C');
	$MiPDF->Cell(25,5,$row['localidad'],1,0,'C');
	$MiPDF->Cell(15,5,$config['centro_codpostal'],1,0,'C');
	$MiPDF->Cell(28,5,$config['centro_provincia'],1,0,'C');
	$MiPDF->Ln ( 8 );
		
	$MiPDF->Cell(84,5,"CORREO ELECTR�NICO DE CONTACTO",0,0,"C");
	$MiPDF->Cell(84,5,"Transporte Escolar",0,0,"C");
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(84,5,$row['correo'],1,0,'C');
	$MiPDF->Cell(84,5,$ruta_est.$ruta_oeste,1,0,'C');
	
	$MiPDF->Ln ( 12 );
	$MiPDF->Cell(5,6,"2",1,0,'C',1);
	$MiPDF->Cell(163,6,"DATOS DE LOS REPRESENTANTES LEGALES DEL ALUMNO",1,0,'C',1);
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(140,5,"APELLIDOS Y NOMBRE DEL REPRESENTANTE LEGAL 1(con quien este convive)",0,0,"C");
	$MiPDF->Cell(28,5,"DNI/NIE",0,0,"C");
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(140,5,$row['padre'],1,0,'C');
	$MiPDF->Cell(28,5,$row['dnitutor'],1,0,'C');
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(140,5,"APELLIDOS Y NOMBRE DEL REPRESENTANTE LEGAL 2",0,0,"C");
	$MiPDF->Cell(28,5,"DNI/NIE",0,0,"C");
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(140,5,$row['madre'],1,0,'C');
	$MiPDF->Cell(28,5,$row['dnitutor2'],1,0,'C');

	
	$MiPDF->Ln ( 12 );
	$MiPDF->Cell(5,6,"3",1,0,'C',1);
	$MiPDF->Cell(163,6,"DATOS DE MATR�CULA",1,0,'C',1);
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(76,5,"CENTRO DOCENTE EN EL QUE SE MATRICULA",0,0,"C");
	$MiPDF->Cell(46,5,"LOCALIDAD",0,0,"C");
	$MiPDF->Cell(46,5,"CODIGO",0,0,"C");
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(76,5,$config['centro_denominacion'],1,0,'C');
	$MiPDF->Cell(46,5,$config['centro_localidad'],1,0,'C');
	$MiPDF->Cell(46,5,$config['centro_codigo'],1,0,'C');
	$MiPDF->Ln ( 6 );
	//echo $itinerario;
	$extra_it="";
	if($itinerario==1){$extra_it="(".$row['ciencias4'].")";}
	if(strlen($optativas4)>1){$extra_it.=" - $optativas4";}	
	if ($n_curso == '4') { $extra="4ESO (It. $itinerario".$extra_it.")";}elseif ($n_curso == '3') { $extra="3ESO (Matem�ticas $matematicas3)";}else{$extra=$curso;}
	
       $MiPDF->Cell(84,6,"IDIOMA EXTRANJERO",0,0,'C');
	$MiPDF->Cell(84,6,"RELIGI�N O ALTERNATIVA",0,0,'C');
	$MiPDF->Ln ( 6);
	$MiPDF->Cell(84,5,$idioma,1,0,'C');
	$MiPDF->Cell(84,5,$religion,1,0,'C');	
	$MiPDF->Ln ( 7);
	
	$MiPDF->Cell(60,5,"CURSO EN QUE SE MATRICULA",0,0,"C");
	$MiPDF->Cell(108,5,"MATERIAS OPTATIVAS DEL CURSO EN QUE SE MATRICULA",0,0,"C");
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(60,5,$extra,1,0,'C');
	$opt="";
	if ($n_curso=="4") {
		$opt = "     $optativa1										     $optativa4
	    $optativa2											    $optativa5
	    $optativa3";
	}
	elseif ($n_curso=="1" ) {
		$opt = "     $optativa1
	    $optativa2
	    $optativa3
	    $optativa4";
	}
	elseif ($n_curso=="2" ) {
		$opt = "     $optativa1
	    $optativa2
	    $optativa3";
	}
	else{
	$opt = "         $optativa1										     $optativa5
	    $optativa2											     $optativa6
	    $optativa3											     $optativa7
	    $optativa4";
	}
	$MiPDF->MultiCell(108,5,$opt,1);
	$MiPDF->Ln ( 4 );
	$f_hoy = "        En ".$config['centro_localidad'].", a ".$hoy;
	$sello = "                                  Sello del Centro";
	$firma_centro = "                                El/La Funcionario/a";
	$firma_padre= "  Firma del representante o Guardador legal 1";
	$MiPDF->Cell(84,8,$firma_padre,0);	
	$MiPDF->Cell(84, 8, $firma_centro,0);
	$MiPDF->Ln ( 20);
	$MiPDF->Cell(84, 8, $f_hoy,0);
	$MiPDF->Cell(84, 8, $sello,0);
	$MiPDF->Ln ( 9 );
	$nota = "NOTA: Para la primera matriculaci�n del alumnado en el centro docente se aportar� documento acreditativo de la fecha de nacimimiento del alumno/a y documento de estar en posesi�n de los requisitos acad�micos establecidos en la legislaci�n vigente.";
	$MiPDF->SetFont ( 'Times', 'B', 8 );
	$MiPDF->MultiCell(168,5,$nota,0);
	$MiPDF->SetFont ( 'Times', '', 7 );
	$MiPDF->Ln ( 3 );		
	$MiPDF->MultiCell(168, 3, $datos_junta,1,'L',1);
}

	# insertamos la primera pagina del documento
	$MiPDF->Addpage ();
	$MiPDF->SetFont ( 'Times', 'B', 11  );
	$MiPDF->SetTextColor ( 0, 0, 0 );
	$MiPDF->SetFillColor(230,230,230);
	$MiPDF->Image ( '../../img/encabezado2.jpg', 10, 10, 180, '', 'jpg' );
	$MiPDF->Ln ( 12 );
	$MiPDF->Multicell ( 0, 4, $titulo_documentacion, 0, 'C', 0 );
	$MiPDF->Ln ( 4 );
	$MiPDF->SetFont ( 'Times', '', 10  );
	$MiPDF->SetTextColor ( 0, 0, 0 );
	$MiPDF->SetFillColor(230,230,230);
	$MiPDF->Multicell ( 0, 6, $documentacion, 0, 'L', 0 );
	$MiPDF->Ln ( 5 );
	$MiPDF->Multicell ( 0, 6, "------------------------------------------------------------------------------------------------------------------------------------------", 0, 'L', 0 );
	$MiPDF->Multicell ( 0, 6, "------------------------------------------------------------------------------------------------------------------------------------------", 0, 'L', 0 );
	$MiPDF->Ln ( 8 );
	$MiPDF->SetFont ( 'Times', 'B', 11  );
	$MiPDF->SetTextColor ( 0, 0, 0 );
	$MiPDF->SetFillColor(230,230,230);
	$MiPDF->Multicell ( 0, 4, $titulo_moviles, 0, 'C', 0 );
	$MiPDF->Ln ( 2 );
	$MiPDF->SetFont ( 'Times', '', 10  );
	$MiPDF->Multicell ( 0, 6, $texto_moviles, 0, 'L', 0 );
	$MiPDF->Ln ( 1 );
	$MiPDF->Multicell ( 0, 6, $final_moviles, 0, 'L', 0 );
	$MiPDF->Ln ( 3 );
	$MiPDF->Multicell ( 0, 6, $firma_moviles, 0, 'C', 0 );
	
	include("autorizaciones.php");
	}
   $MiPDF->AutoPrint(true);     
   $MiPDF->Output ();

?>