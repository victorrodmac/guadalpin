<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1, 8));

require_once('../../pdf/class.ezpdf.php');

$pdf =& new Cezpdf('a4');
$pdf->selectFont('../../pdf/fonts/Helvetica.afm');
$pdf->ezSetCmMargins(1,1,1.5,1.5);
$tot = mysqli_query($db_con, "select distinct curso, grupo_actual from matriculas where grupo_actual != '' order by curso, grupo_actual");
while($total = mysqli_fetch_array($tot)){
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
	$curso = $total[0];
	$grupo_actual = $total[1];
	
$sqldatos="SELECT concat(apellidos,', ',nombre), exencion, optativa1, optativa2, optativa3, optativa4, optativa5, optativa6, optativa7, act1, religion, diversificacion, matematicas3, bilinguismo, itinerario, optativas4, ciencias4 FROM matriculas WHERE curso = '$curso' and grupo_actual='".$grupo_actual."' ORDER BY apellidos, nombre";
//echo $sqldatos;

$div3=mysqli_query($db_con,"SELECT diversificacion FROM matriculas WHERE curso = '$curso' and grupo_actual='".$grupo_actual."' and diversificacion='1'");
if (mysqli_num_rows($div3)>0) {
	$div_3 = $grupo_actual;
}		
	
$lista= mysqli_query($db_con, $sqldatos );
$nc=0;
unset($data);
while($datatmp = mysqli_fetch_array($lista)) { 
        $bil = "";
        if($datatmp['bilinguismo']=="Si"){$bil = " (Bil.)";}
        
$religion = "";
	
if ($curso=="3ESO") {
for ($i = 2; $i < 9; $i++) {
		if ($datatmp[$i]=="1") {
			$datatmp[$i]="X";
		}
		else{
			$datatmp[$i]="";
		}
	}
	// Diversificaci�n
if ($datatmp['diversificacion']=="1") {
			$datatmp['diversificacion']="X";
		}
		else{
			$datatmp['diversificacion']="";
		}
}
else {
if ($curso=="2ESO") {
	// Diversificaci�n
	if ($datatmp['diversificacion']=="1") {
			$datatmp['diversificacion']="X";
		}
		else{
			$datatmp['diversificacion']="";
		}
}	
for ($i = 2; $i < 7; $i++) {
	if ($curso=="4ESO") {
		if ($datatmp[$i]=="1") {
			$datatmp[$i]="1";
		}
		elseif ($datatmp[$i]=="2") {
			$datatmp[$i]="2";
		}
		else{
			$datatmp[$i]="";
		}	
	}
	else{
		if ($datatmp[$i]=="1") {
			$datatmp[$i]="X";
		}
		else{
			$datatmp[$i]="";
		}	
	}

	
	}
	
}

for ($i = 0; $i < 10; $i++) {
		if ($datatmp[$i]=="0") {
			$datatmp[$i]="";
		}
	}
	
$nc+=1;

if (strstr($datatmp['religion'],"Cat")==TRUE) {
	$religion ="X";
}

if ($curso=="3ESO") {

	$act = "
	Actividades de Refuerzo y Ampliaci�n:
	1 => Actividades de refuerzo de Lengua Castellana, 2 => Actividades de refuerzo de Matem�ticas, 3 => Actividades de refuerzo de Ingl�s,4 => Ampliaci�n: Lengua, 5 => Ampliaci�n: Matem�ticas, 6 => Ampliaci�n: Ingl�s";

	$opt = "
	
	Optativas:
	1 => Alem�n 2� Idioma,	2 => Cambios Sociales y G�nero,	3 => Franc�s 2� Idioma,	4 => Cultura Cl�sica, 5 => Taller T.I.C. III,	6 => Taller de Cer�mica, 7 => Taller de Teatro
	
	Matem�ticas: A => Mat. Acad�micas; B => Mat. Ense�anzas Aplicadas";
	if ($div_3 == $grupo_actual) {
			$data[] = array(
				'num'=>$nc,
				'nombre'=>$datatmp[0].$bil,
				'c9'=>$religion,
				'c2'=>$datatmp[2],
				'c3'=>$datatmp[3],
				'c4'=>$datatmp[4],
				'c5'=>$datatmp[5],
				'c6'=>$datatmp[6],
				'c7'=>$datatmp[7],
				'c8'=>$datatmp[8],
				'c11'=>$datatmp['diversificacion'],
				'c12'=>$datatmp['matematicas3'],
				'c13'=>$datatmp['act1'],
				
				);
	$titles = array(
				'num'=>'<b>N�</b>',
				'nombre'=>'<b>Alumno</b>',
				'c9'=>'Rel. Cat.',
				'c2'=>'O1',
				'c3'=>'O2',
				'c4'=>'O3',
				'c5'=>'O4',
				'c6'=>'O5',
				'c7'=>'O6',
				'c8'=>'O7',
				'c11'=>'PMAR',
				'c12'=>'Mat',
				'c13'=>'Act',
			);
	}
	else{
			$data[] = array(
				'num'=>$nc,
				'nombre'=>$datatmp[0].$bil,
				'c10'=>$religion,
				'c2'=>$datatmp[2],
				'c3'=>$datatmp[3],
				'c4'=>$datatmp[4],
				'c5'=>$datatmp[5],
				'c6'=>$datatmp[6],
				'c7'=>$datatmp[7],
				'c8'=>$datatmp[8],
				'c9'=>$datatmp['matematicas3'],
				'c11'=>$datatmp['act1'],
				);
	$titles = array(
				'num'=>'<b>N�</b>',
				'nombre'=>'<b>Alumno</b>',
				'c10'=>'Rel. Cat.',
				'c2'=>'O1',
				'c3'=>'O2',
				'c4'=>'O3',
				'c5'=>'O4',
				'c6'=>'O5',
				'c7'=>'O6',
				'c8'=>'O7',
				'c9'=>'Mat',
				'c11'=>'Act',
			);
	}
}

if ($curso=="2ESO") {
	
		$act = "
	Actividades de Refuerzo y Ampliaci�n:
	1 => Actividades de refuerzo de Lengua Castellana, 2 => Actividades de refuerzo de Matem�ticas, 3 => Actividades de refuerzo de Ingl�s,4 => Ampliaci�n: Taller T.I.C. II, 5 => Ampliaci�n: Taller de Teatro II";	
		
		$opt = "
	
	Optativas:
	1 => Alem�n 2� Idioma, 2 => Cambios Sociales y G�nero,	3 => Franc�s 2� Idioma";
	if ($div_3 == $grupo_actual) {
				$data[] = array(
				'num'=>$nc,
				'nombre'=>$datatmp[0],
				'c7'=>$religion,
				'c2'=>$datatmp[2],
				'c3'=>$datatmp[3],
				'c4'=>$datatmp[4],
				'c5'=>$datatmp['act1'],
				'c6'=>$datatmp['diversificacion'],
				);

	$titles = array(
				'num'=>'<b>N�</b>',
				'nombre'=>'<b>Alumno</b>',
				'c7'=>'Rel. Cat.',
				'c2'=>'Opt1',
				'c3'=>'Opt2',
				'c4'=>'Opt3',
				'c5'=>'Act.',
				'c6'=>'PMAR',
			);
	}
	else{
			$data[] = array(
				'num'=>$nc,
				'nombre'=>$datatmp[0],
				'c7'=>$religion,
				'c2'=>$datatmp[2],
				'c3'=>$datatmp[3],
				'c4'=>$datatmp[4],
				'c5'=>$datatmp['act1'],
				);

	$titles = array(
				'num'=>'<b>N�</b>',
				'nombre'=>'<b>Alumno</b>',
				'c7'=>'Rel. Cat.',
				'c2'=>'Opt1',
				'c3'=>'Opt2',
				'c4'=>'Opt3',
				'c5'=>'Act.',
			);
	}

}


if ($curso=="1ESO") {
	$act = "
	Actividades de Refuerzo y Ampliaci�n:
	1 => Actividades de refuerzo de Lengua Castellana, 2 => Actividades de refuerzo de Matem�ticas, 3 => Actividades de refuerzo de Ingl�s,	4 => Ampliaci�n: Taller T.I.C., 5 => Ampliaci�n: Taller de Teatro";			
		$opt = "
					
	Optativas:
	1 => Alem�n 2� Idioma,	2 => Cambios Sociales y G�nero, 3 => Franc�s 2� Idioma,	4 => Tecnolog�a Aplicada";
	
	$data[] = array(
				'num'=>$nc,
				'nombre'=>$datatmp[0],
				'c7'=>$religion,
				'c2'=>$datatmp[2],
				'c3'=>$datatmp[3],
				'c4'=>$datatmp[4],
				'c5'=>$datatmp[5],
				'c6'=>$datatmp['act1'],
				);

	$titles = array(
				'num'=>'<b>N�</b>',
				'nombre'=>'<b>Alumno</b>',
				'c7'=>'Rel. Cat.',
				'c2'=>'Opt1',
				'c3'=>'Opt2',
				'c4'=>'Opt3',
				'c5'=>'Opt4',
				'c6'=>'Act.',
			);
}


if ($curso=="4ESO") {

		$opt = "
	
	Optativas Modalidad Itinerario 1 (Bachillerato de Ciencias -> Matem�ticas Acad�micas):
	1 => Tecnolog�a,	2 => F�sica y Qu�mica,	3 => Biolog�a y Geolog�a, 4 => Econom�a.
	Optativas Modalidad Itinerario 2 (Bachillerato de Humanidades y Ciencias Sociales -> Matem�ticas Acad�micas):
	1 => Lat�n,	2 => Econom�a.
	Optativas Modalidad Itinerario 3 (Ciclos Formativos y Mundo Laboral -> Matem�ticas Aplicadas):
	1 => Tecnolog�a,	2 => Ciencias Aplicadas a la Actividad Profesional,	3 => Iniciaci�n a la Actividad Emprendedora y Empresaria.

	Optativas Generales:
	1 => Alem�n 2� Idioma, 2 => Franc�s 2� Idioma, 3 => TIC, 4 => Ed. Pl�stica y Visual, 5 => M�sica.
	";

if ($datatmp['itinerario']==1) {
	$extra_itin="(".$datatmp['ciencias4'].")";
}
else{$extra_itin="";}

$opt4="";
if (stristr($datatmp['optativas4'],"Biolog")==TRUE) {$opt4="ByG";}
if (stristr($datatmp['optativas4'],"Econo")==TRUE) {$opt4="ECO";}
if (stristr($datatmp['optativas4'],"Aplicadas")==TRUE) {$opt4="CAAP";}
if (stristr($datatmp['optativas4'],"Iniciaci")==TRUE) {$opt4="IAEE";}
if ($datatmp['exencion']==1) {
	$exencion = 'X';
}
else{
	$exencion = '';
}
$data[] = array(
				'num'=>$nc,
				'nombre'=>$datatmp[0].$bil,
				'c8'=>$religion,
				'It.'=>$datatmp['itinerario'].$extra_itin,
				'c2'=>$datatmp[2],
				'c3'=>$datatmp[3],
				'c4'=>$datatmp[4],
				'c5'=>$datatmp[5],
				'c6'=>$datatmp[6],
				'c7'=>$opt4,
				'c9'=>$exencion,
				);

	$titles = array(
				'num'=>'<b>N�</b>',
				'nombre'=>'<b>Alumno</b>',
				'c8'=>'Rel. Cat.',
				'It.'=>'Itiner.',
				'c2'=>'O1',
				'c3'=>'O2',
				'c4'=>'O3',
				'c5'=>'O4',
				'c6'=>'O5',
				'c7'=>'OptGen',
				'c9'=>'Ex.',
			);
		}
	}


$options = array(
				'textCol' => array(0.2,0.2,0.2),
				'innerLineThickness'=>0.5,
				'outerLineThickness'=>0.7,
				'showLines'=> 2,
				'shadeCol'=>array(0.9,0.9,0.9),
				'xOrientation'=>'center',
				'width'=>500
			);
$txttit = "Lista del Grupo $curso-$grupo_actual\n";
$txttit.= $config['centro_denominacion'].". Curso ".$config['curso_actual'].".\n";
	
$pdf->ezText($txttit, 13,$options_center);
$pdf->ezTable($data, $titles, '', $options);
$pdf->ezText($opt, '10', $options);
if ($curso !== "4ESO") {
	$pdf->ezText($act, '10', $options);
}
$pdf->ezText("\n", 10);
$pdf->ezText("<b>Fecha:</b> ".date("d/m/Y"), 10,$options_right);
	
$pdf->ezNewPage();
}
$pdf->ezStream();
?>