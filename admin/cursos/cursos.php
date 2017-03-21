<?php
require('../../bootstrap.php');

require_once('../../pdf/class.ezpdf.php');
$pdf = new Cezpdf('a4');
$pdf->selectFont('../../pdf/fonts/Helvetica.afm');
$pdf->ezSetCmMargins(1,1,1.5,1.5);
# hasta aqu� lo del pdf
$options_center = array(
				'justification' => 'center'
			);
$options_right = array(
				'justification' => 'right'
			);
$options_left = array(
				'justification' => 'left'
					);
$codasig= mysqli_query($db_con, "SELECT codigo, abrev, curso FROM asignaturas");
while($asigtmp = mysqli_fetch_array($codasig)) {
	$asignatura[$asigtmp[0]] = $asigtmp[1].'('.substr($asigtmp[2],0,2).')';
} 
$libd12 = "LIBD1: Ref. Lengua; LIBD2: Ref. Matem�ticas; LIBD3: Ref. Ingl�s; LIBD4: Taller TIC; LIBD5: Taller Teatro.";
$lc3 = "OPLC1: TALLER TIC; OPLC2: TALLER CER�MICA; OPLC3: TALLER TEATRO.";
$lc1b = "OPLC1: Ed. F�sica; OPLC2: Estad�stica; OPLC3: Franc�s.";
if (isset($_GET['unidad'])) 
{

	$matr="";
	$crs = mysqli_query($db_con,"select curso from alma where unidad = '".$_GET['unidad']."'");
	$curso_a = mysqli_fetch_array($crs);
	if(stristr($curso_a[0],"Bachill")==TRUE){$matr = 'matriculas_bach';}elseif(stristr($curso_a[0],"E.S.O.")==TRUE){$matr = 'matriculas';}

	if ($config['mod_matriculacion']==1) {
		$sqldatos="SELECT concat(alma.apellidos,', ',alma.nombre), nc, matriculas, alma.claveal, alma.curso, bilinguismo FROM alma, $matr WHERE  $matr.claveal=alma.claveal and alma.unidad='".$_GET['unidad']."' $texto ORDER BY nc, alma.apellidos, alma.nombre";
	}	
	else{
		$sqldatos="SELECT concat(alma.apellidos,', ',alma.nombre), nc, matriculas, alma.claveal, curso FROM alma WHERE alma.unidad='".$_GET['unidad']."' $texto ORDER BY nc, alma.apellidos, alma.nombre";
	}

// echo $sqldatos;
	$lista= mysqli_query($db_con, $sqldatos );

	$num=0;
	unset($data);
	while($datatmp = mysqli_fetch_array($lista)) { 
		if ($datatmp[2]>1) {
			$datatmp[0]=$datatmp[0]." (R)";
		}
		if ($datatmp['bilinguismo']=='Si') {
			$datatmp[0]=$datatmp[0]." (Bil)";
		}
		if(strstr($datatmp[4],"E.S.O.")==TRUE){
			$m_ex = "select exencion from matriculas where claveal = '$datatmp[3]'";
			$m_exen = mysqli_query($db_con, $m_ex);
			$m_exento = mysqli_fetch_array($m_exen);
			if($m_exento[0]=="1"){
				$datatmp[0]=$datatmp[0]." (Ex)";
			}
		}
		
		$data[] = array(
				'num'=>$datatmp[1],
				'nombre'=>$datatmp[0],
				);
	}
	$titles = array(
				'num'=>'<b>N�</b>',
				'nombre'=>'<b>Alumno</b>',
				'c1'=>'   ',
				'c2'=>'   ',
				'c3'=>'   ',
				'c4'=>'   ',
				'c5'=>'   ',
				'c6'=>'   ',
				'c7'=>'   ',
				'c8'=>'   ',
				'c9'=>'   ',
				'c10'=>'   '
			);
	$options = array(
				'textCol' => array(0.2,0.2,0.2),
				 'innerLineThickness'=>0.5,
				 'outerLineThickness'=>0.7,
				'showLines'=> 2,
				'shadeCol'=>array(0.9,0.9,0.9),
				'xOrientation'=>'center',
				'width'=>500
			);
	$txttit = "Lista del Grupo ".$_GET['unidad']."\n";
	$txttit.= $config['centro_denominacion'].". Curso ".$config['curso_actual'].".\n";
		
	$pdf->ezText($txttit, 13,$options_center);
	$pdf->ezTable($data, $titles, '', $options);
	$pdf->ezText("\n\n\n", 10);
	$pdf->ezText("<b>Fecha:</b> ".date("d/m/Y"), 10,$options_right);
	$pdf->ezNewPage();
}
		
foreach ($_POST['unidad'] as $unida){
	$tr_c = explode(" -> ",$unida);
	$tr_unidad = $tr_c[0];
	$cod_asig = $tr_c[2];
	$tr_codasi = explode("-",$tr_c[2]);
	$n_uni+=1;
	$cuenta = count($_POST['unidad']);

	if ($tr_codasi[0]=="2" or $tr_codasi[0]=="21") {}
	else
	{
		if (strlen($tr_codasi[1])>1) {
			$extra_asig = "or asignatura = '$tr_codasi[1]'";
		}
		else{
			$extra_asig="";
		}
		$sel = mysqli_query($db_con,"select alumnos from grupos where profesor = '".$_SESSION['profi']."' and curso = '$tr_unidad' and (asignatura = '$tr_codasi[0]' $extra_asig)");
		$hay_sel = mysqli_num_rows($sel);
		$hay_grupo = mysqli_fetch_array($sel);
		$hay_alumno = explode(",",$hay_grupo[0]);	
	}

	if($_POST['asignaturas']==""){
		
		$sqldatos="SELECT concat(alma.apellidos,', ',alma.nombre), nc, matriculas, alma.claveal, curso, alma.claveal FROM alma";

		if ($tr_codasi[0]=="2" or $tr_codasi[0]=="21") {
			$sqldatos.=" WHERE (1=1)";
		}
		elseif(strlen($tr_codasi[0])>1 and strlen($tr_codasi[1])>1){
			$sqldatos.=" WHERE (combasi like '%$tr_codasi[0]%' or combasi like '%$tr_codasi[1]%')";
		} 
		else{
			$sqldatos.=" WHERE combasi like '%$tr_codasi[0]%'";		
		}
			
		$sqldatos.=" $text and alma.unidad='".$tr_unidad."' ORDER BY nc, alma.apellidos, alma.nombre";
		//echo $sqldatos;
		$lista= mysqli_query($db_con, $sqldatos );
		$num=0;
		unset($data);	

		while($datatmp = mysqli_fetch_array($lista)) { 

			if ($datatmp[2]>1) {
					$datatmp[0]=$datatmp[0]." (R)";
				}

			if ($config['mod_matriculacion']==1) {
				$mtr_eso = mysqli_query($db_con,"select bilinguismo from matriculas where bilinguismo = 'Si' and claveal = '".$datatmp['claveal']."'");
				$mtr_bach = mysqli_query($db_con,"select bilinguismo from matriculas_bach where bilinguismo = 'Si' and claveal = '".$datatmp['claveal']."'");
				if (mysqli_num_rows($mtr_eso)>0 or mysqli_num_rows($mtr_bach)>0) {
					$datatmp[0]=$datatmp[0]." (Bil)";
				}
			}	
			
			if(strstr($datatmp[4],"E.S.O.")==TRUE){
				$m_ex = "select exencion from matriculas where claveal = '$datatmp[3]'";
				$m_exen = mysqli_query($db_con, $m_ex);
				$m_exento = mysqli_fetch_array($m_exen);
				if($m_exento[0]=="1"){
					$datatmp[0]=$datatmp[0]." (Ex)";
				}
			}
			if ($hay_sel==0 or in_array($datatmp[1],$hay_alumno) or $_POST['todos']=="1") {
				$data[] = array(
							'num'=>$datatmp[1],
							'nombre'=>$datatmp[0],
							);
			}
		}	
		$titles = array(
					'num'=>'<b>N�</b>',
					'nombre'=>'<b>Alumno</b>',
					'c1'=>'   ',
					'c2'=>'   ',
					'c3'=>'   ',
					'c4'=>'   ',
					'c5'=>'   ',
					'c6'=>'   ',
					'c7'=>'   ',
					'c8'=>'   ',
					'c9'=>'   ',
					'c10'=>'   '
				);
		$options = array(
					'textCol' => array(0.2,0.2,0.2),
					 'innerLineThickness'=>0.5,
					 'outerLineThickness'=>0.7,
					'showLines'=> 2,
					'shadeCol'=>array(0.9,0.9,0.9),
					'xOrientation'=>'center',
					'width'=>500
				);
		$txttit = "Lista del Grupo $tr_unidad $text2\n";
		$txttit.= $config['centro_denominacion'].". Curso ".$config['curso_actual'].".\n";
		$pdf->ezText($txttit, 13,$options_center);

		$pdf->ezTable($data, $titles, '', $options);
			
		$pdf->ezText("\n\n\n", 10);
		$pdf->ezText("<b>Fecha:</b> ".date("d/m/Y"), 10,$options_right);
		//echo "Cuenta = $cuenta; grupos = $n_uni;<br>";
		if ($cuenta>1) {
			if ($n_uni==$cuenta) {}
			else{
				$pdf->ezNewPage();			
			}
				
		}
	}

	if ($_POST['asignaturas']=='1'){

		$sqldatos="SELECT concat(alma.apellidos,', ',alma.nombre), combasi, NC, alma.unidad, matriculas, alma.claveal, CURSO FROM alma WHERE ";

		if(strlen($tr_codasi[0])>1 and strlen($tr_codasi[1])>1){
			$sqldatos.=" (combasi like '%$tr_codasi[0]%' or combasi like '%$tr_codasi[1]%')";
		} 
		else{
			$sqldatos.=" combasi like '%$tr_codasi[0]%'";		
		}
		$sqldatos.=" $text and alma.unidad='".$tr_unidad."' ORDER BY nc, alma.apellidos, alma.nombre";

		//echo $sqldatos;
		$lista= mysqli_query($db_con, $sqldatos);
		$num=0;
		unset($data);
		while($datatmp = mysqli_fetch_array($lista)) 
		{ 

			if ($datatmp[4]>1) {
				$datatmp[0]=$datatmp[0]." (R)";
			}

			if ($config['mod_matriculacion']==1) {
				$mtr_eso = mysqli_query($db_con,"select bilinguismo from matriculas where bilinguismo = 'Si' and claveal = '".$datatmp['claveal']."'");
				$mtr_bach = mysqli_query($db_con,"select bilinguismo from matriculas_bach where bilinguismo = 'Si' and claveal = '".$datatmp['claveal']."'");
				if (mysqli_num_rows($mtr_eso)>0 or mysqli_num_rows($mtr_bach)>0) {
					$datatmp[0]=$datatmp[0]." (Bil)";
				}
			}	
			
			if(strstr($datatmp[4],"E.S.O.")==TRUE){
				$m_ex = "select exencion from matriculas where claveal = '$datatmp[5]'";
				$m_exen = mysqli_query($db_con, $m_ex);
				$m_exento = mysqli_fetch_array($m_exen);
				if($m_exento[0]=="1"){
					$datatmp[0]=$datatmp[0]." (Ex)";
				}
			}
			$unidadn = $datatmp[6];
			$mat="";
			$asignat = substr($datatmp[1],0,strlen($datatmp[1])-1);
			$asignat = $datatmp[1];
			$asig0 = explode(":",$asignat);
			foreach($asig0 as $asignatura){			
				$consulta = "select distinct abrev, curso from asignaturas where codigo = '$asignatura' and curso like '%$unidadn%' limit 1";
				// echo $consulta."<br>";
				$abrev = mysqli_query($db_con, $consulta);		
				$abrev0 = mysqli_fetch_array($abrev);
				$curs=substr($abrev0[1],0,2);
				$mat.=$abrev0[0]."; ";
			}
		//	echo $mat."<br>";		
			$ixx = $datatmp[2];
			
			if ($hay_sel==0 or in_array($datatmp[2],$hay_alumno) or $_POST['todos']=="1") {
				$data[] = array(
							'num'=>$ixx,
							'nombre'=>$datatmp[0],
							'asig'=>$mat
							);
			}
			
		}
		$titles = array(
						'num'=>'<b>N�</b>',
						'nombre'=>'<b>Alumno</b>',
						'asig'=>'<b>Asignaturas</b>'
					);
		$options = array(
						'showLines'=> 2,
						'shadeCol'=>array(0.9,0.9,0.9),
						'xOrientation'=>'center',
						'fontSize' => 8,
						'width'=>500
					);
		$txttit = "<b>Alumnos del grupo: $tr_unidad $text2</b>\n";
		$txttit.= $config['centro_denominacion'].". Curso ".$config['curso_actual'].".\n";	
		$pdf->ezText($txttit, 12,$options_center);

		$pdf->ezTable($data, $titles, '', $options);

		if ($_SERVER['SERVER_NAME'] == 'iesmonterroso.org') 
		{

			if (strstr($unidadn,"E.S.O.")==TRUE AND (strstr($unidadn,"1")==TRUE OR strstr($unidadn,"2")==TRUE)) {
				$pdf->ezText($libd12, 9,$options);
				$pdf->ezText("\n\n\n", 5);
			}
			
			if (strstr($unidadn,"3")==TRUE) {
				$pdf->ezText($lc3, 9,$options);
				$pdf->ezText("\n\n\n", 5);
			}
			
			if (strstr($unidadn,"BACH.")==TRUE AND strstr($unidadn,"1")==TRUE) {
				$pdf->ezText($lc1b, 9,$options);
				$pdf->ezText("\n\n\n", 5);
			}
			else{
				$pdf->ezText("\n\n\n", 10);
			}
		}
		else{
			$pdf->ezText("\n\n\n", 10);
		}

		$pdf->ezText("<b>Fecha:</b> ".date("d/m/Y"), 9,$options_right);
		if ($cuenta>1) {
			if ($n_uni==$cuenta) {}
			else{
				$pdf->ezNewPage();			
			}
		}
	} 
}
$pdf->ezStream();
?>
