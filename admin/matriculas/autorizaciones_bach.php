<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); 

$pa = explode(", ", $datos_ya->padre);
$papa = "$pa[1] $pa[0]";
$hoy = formatea_fecha(date('Y-m-d'));
$titulo4 = "AUTORIZACI�N  PARA FOTOS Y GRABACIONES";
$autoriza_fotos="
D./D� $papa, con DNI $datos_ya->dnitutor, representante legal del alumno/a $datos_ya->nombre $datos_ya->apellidos
AUTORIZA al ".$config['centro_denominacion']." a fotografiar o grabar con video a su hijo o hija con fines educativos 
y dentro del contexto educativo del centro o de actividades complementarias o extraescolares desarrolladas por el mismo. 
";
$titulo5 = "		
En ".$config['centro_localidad'].", a $hoy


Firmado. D./D�
NOTA: Los padres y madres son libres de firmar,  o no,  esta autorizaci�n.";

// Religion
$an = substr($config['curso_actual'],0,4);
$an1 = $an+1;
$an2 = $an+2;
$c_escolar = $an1."/".$an2;
$titulo_religion = "SOLICITUD PARA CURSAR LAS ENSE�ANZAS DE RELIGI�N";
$autoriza_religion="
D./D� $papa, como padre, madre o tutor legal del alumno/a $datos_ya->nombre $datos_ya->apellidos del curso ".$n_curso."� de ESO del ".$config['centro_denominacion'].", en desarrollo de la Ley Org�nica 2/2006 de 3 de Mayo, de Educaci�n, modificada por la Ley Org�nica 8/2013, de 9 de diciembre, para la mejora de la calidad educativa.

SOLICITA:

Cursar a partir del curso escolar $c_escolar. mientras no modifique expresamente esta decisi�n, la ense�anza de Religi�n:
x $religion
";
$firma_religion = "		
En ".$config['centro_localidad'].", a $hoy


Firmado. D./D�
";
$final_religion="
SR./SRA. DIRECTOR/A -----------------------------------------------------------------------------------------------------";
$direccion_junta = "
Ed. Torretriana. C/. Juan A. de Vizarr�n, s/n. 41071 Sevilla
Telf. 95 506 40 00. Fax: 95 506 40 03.
e-mail: informacion.ced@juntadeandalucia.es
";

// Fotos
	$MiPDF->Addpage ();
	#### Cabecera con direcci�n
	$MiPDF->SetFont ( 'Times', 'B', 11  );
	$MiPDF->SetTextColor ( 0, 0, 0 );
	$MiPDF->SetFillColor(230,230,230);
	#Cuerpo.
	$MiPDF->Image ( '../../img/encabezado2.jpg', 10, 10, 180, '', 'jpg' );
	$MiPDF->Ln ( 20 );
	$MiPDF->Cell(168,5,$titulo4,0,0,'C');
	$MiPDF->SetFont ( 'Times', '', 10  );	
	$MiPDF->Ln ( 4 );
	$MiPDF->Multicell ( 0, 6, $autoriza_fotos, 0, 'L', 0 );
	$MiPDF->Ln ( 3 );
	$MiPDF->Multicell ( 0, 6, $titulo5, 0, 'C', 0 );
	$MiPDF->Ln ( 8);

// Religion

if (substr($religion, 0, 1)=="R") {
	$MiPDF->Cell(168,5,"----------------------------------------------------------------------------------------------------------------------------------------",0,0,'C');
	$MiPDF->Ln ( 12 );
	$MiPDF->SetFont ( 'Times', 'B', 11  );
	$MiPDF->SetTextColor ( 0, 0, 0 );
	$MiPDF->SetFillColor(230,230,230);
	$MiPDF->Cell(168,5,$titulo_religion,0,0,'C');
	$MiPDF->SetFont ( 'Times', '', 10  );	
	$MiPDF->Ln ( 4 );
	$MiPDF->Multicell ( 0, 6, $autoriza_religion, 0, 'L', 0 );
	$MiPDF->Ln ( 3 );
	$MiPDF->Multicell ( 0, 6, $firma_religion, 0, 'C', 0 );
	$MiPDF->Ln ( 8 );
	$MiPDF->Multicell ( 0, 6, $final_religion, 0, 'L', 0 );
	$MiPDF->Ln ( 6 );
	$MiPDF->SetFont ( 'Times', '', 9  );
	$MiPDF->Multicell ( 0, 6, $direccion_junta, 0, 'L', 0 );
}

// AMPA
$dni_papa = explode(": ", $dnitutor);
$dnipapa = $dni_papa[1];
$hijos = mysqli_query($db_con, "select apellidos, nombre, curso from matriculas where dnitutor = '$dnipapa'");
$hijos_bach = mysqli_query($db_con, "select apellidos, nombre, curso from matriculas_bach where dnitutor = '$dnipapa'");
$num_hijos = mysqli_num_rows($hijos);
$num_hijos_bach = mysqli_num_rows($hijos_bach);

if ($num_hijos > 0) {
$n_h="";
while ($hij = mysqli_fetch_array($hijos)){
	$n_h+=1;
	${hijo.$n_h} = $hij[1]." ".$hij[0];
	//echo $hij[2];
	if (substr($hij[2], 0, 2) == '1E' or substr($hij[2], 0, 2) == '2E' or substr($hij[2], 0, 2) == '3E' or substr($hij[2], 0, 2) == '4E'){
		$niv = substr($hij[2], 0, 1);
		
			${nivel.$n_h} = $niv." ESO";			
		
		}
	}	
}


if ($num_hijos_bach > 0) {
$n_h_p="";
while ($hij_bach = mysqli_fetch_array($hijos_bach)){
	$n_h_p+=1;
	${hijop.$n_h_p} = $hij_bach[1]." ".$hij_bach[0];
	//echo $hij[2];
	if (substr($hij_bach[2], 0, 2) == '1B' or substr($hij_bach[2], 0, 2) == '2B'){
		$niv_bach = substr($hij_bach[2], 0, 1);

			${nivelp.$n_h_p} = $niv_bach." BACH";			

		}	
	}
}	


	$tit_ampa = '
I.E.S. MONTERROSO
Avda. Sto. Tom�s de Aquino s/n
29680 ESTEPONA
e-mail: ampa@iesmonterroso.org
ampamonterroso@gmail.com

     Como cada a�o, la labor del A.M.P.A. comienza informando a las madres y padres de la necesidad de pertenecer a la Asociaci�n, pues con su aportaci�n y colaboraci�n ayudamos a la gran tarea que supone EDUCAR A NUESTROS HIJAS E HIJOS. Son muchas las cosas que hacemos pero m�s las que se pueden llevar a cabo, con el compromiso e implicaci�n de toda la comunidad educativa: padres y madres, profesorado y alumnado.
     Para m�s informaci�n de las actividades del A.M.P.A. consultar p�gina www.iesmonterroso.org  pinchando en A.M.P.A, o directamente accediendo al blog   http://ampamonterroso.blogspot.com/ 
     La  cuota  de  la Asociaci�n  de  Madres  y  Padres  es  de  12 euros por  familia y por curso.  La  pertenencia  a la   A.M.P.A  es voluntaria. Las  madres,  padres o tutores  de  los  alumnos/as  que  deseen  pertenecer a la  A.M.P.A  deber�n  presentar  este  impreso.';
	$ampa2 = '
Nombre del Padre, Madre o Tutor Legal: '.$papa.'. DNI: '.$datos_ya->dnitutor.'
'.$domicilio.'
'.$telefono1.'
'.$correo.'

NOMBRE Y  APELLIDOS  DE SUS HIJOS/AS  Y CURSO EN QUE SE MATRICULAN EN '.$c_escolar.'
';
	$ampa31='EXCLUSIVO  PARA ALUMNOS  QUE  VAN  A  CURSAR 1�, 2�, 3� y 4� DE E.S.O.';
	$ampa3 = '     Os recordamos que  es OBLIGATORIO el uso de la Agenda Escolar del Instituto para 1�, 2�, 3� y 4� de E.S.O., necesaria para el contacto permanente entre el profesorado y familia.  La Agenda ser� entregada gratuitamente a los alumnos que se hagan socios en el momento de la matriculaci�n.
   
     Un cordial saludo.
	
	';

	if ($num_hijos=="0" and $num_hijos_bach=="0") {
		$hijo1 = "$row[3] $row[2]";
		$nivel1= $curso;
		$ampa2.=$hijo1." ".$nivel1;
	}
	else{
		for ($i = 1; $i < $num_hijos+1; $i++) {
		$ampa2.=${hijo.$i}.' '.${nivel.$i}.'
';
		
	}
	for ($i = 1; $i < $num_hijos_bach+1; $i++) {
		if (empty(${nivelp.$i})) {
			${nivelp.$i}=$curso;
		}
		$ampa2.=${hijop.$i}.' '.${nivelp.$i}.'
';
		
		}		
	}
	
	$MiPDF->Addpage ();
	#### Cabecera con direcci�n
	$MiPDF->SetFont ( 'Times', 'B', 11  );
	$MiPDF->SetTextColor ( 0, 0, 0 );
	$MiPDF->SetFillColor(230,230,230);
	#Cuerpo.
	$MiPDF->Image ( '../../img/ampa.jpg', 8, 8, 170, '', 'jpg' );
	$MiPDF->Ln ( 20 );
	$MiPDF->Cell(168,8,"ASOCIACI�N DE MADRES Y PADRES BACCALAUREATUS",1,1,'C');
	$MiPDF->SetFont ( 'Times', '', 10  );	
	$MiPDF->Ln ( 4 );
	$MiPDF->Multicell ( 0, 6, $tit_ampa, 0, 'L', 0 );
	$MiPDF->Ln ( 3 );
	$MiPDF->Multicell ( 0, 6, $ampa2, 0, 'L', 0 );
	$MiPDF->Ln ( 3 );
	?>