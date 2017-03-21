<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1, 7));

if (isset($_GET['curso'])) {$curso = $_GET['curso'];}elseif (isset($_POST['curso'])) {$curso = $_POST['curso'];}else{$curso="";}
if (isset($_GET['dni'])) {$dni = $_GET['dni'];}elseif (isset($_POST['dni'])) {$dni = $_POST['dni'];}else{$dni="";}
if (isset($_GET['claveal'])) {$claveal = $_GET['claveal'];}elseif (isset($_POST['claveal'])) {$claveal = $_POST['claveal'];}else{$claveal="";}
if (isset($_GET['enviar'])) {$enviar = $_GET['enviar'];}elseif (isset($_POST['enviar'])) {$enviar = $_POST['enviar'];}else{$enviar="";}
if (isset($_GET['id'])) {$id = $_GET['id'];}elseif (isset($_POST['id'])) {$id = $_POST['id'];}else{$id="";}
// Divorcios
$divorciados = array(
array(
													'id'     => 'Guardia y Custodia compartida por Madre y Padre',
													'nombre' => 'Guardia y Custodia compartida por Madre y Padre',
),
array(
													'id'     => 'Guardia y Custodia de la Madre',
													'nombre' => 'Guardia y Custodia de la Madre',
),
array(
													'id'     => 'Guardia y Custodia del Padre',
													'nombre' => 'Guardia y Custodia del Padre',
),
);


// Enfermedades
$enfermedades = array(
array(
													'id'     => 'Celiaqu�a',
													'nombre' => 'Celiaqu�a',
),
array(
													'id'     => 'Alergias a alimentos',
													'nombre' => 'Alergias a alimentos',
),
array(
													'id'     => 'Alergias respiratorias',
													'nombre' => 'Alergias respiratorias',
),
array(
													'id'     => 'Asma',
													'nombre' => 'Asma',
),
array(
													'id'     => 'Convulsiones febriles',
													'nombre' => 'Convulsiones febriles',
),
array(
													'id'     => 'Diabetes',
													'nombre' => 'Diabetes',
),
array(
													'id'     => 'Epilepsia',
													'nombre' => 'Epilepsia',
),
array(
													'id'     => 'Insuficiencia card�aca',
													'nombre' => 'Insuficiencia card�aca',
),
array(
													'id'     => 'Insuficiencia renal',
													'nombre' => 'Insuficiencia renal',
),
);

$transporte_este = array(
array(
												'id'     => 'Urb. Mar y Monte',
												'nombre' => 'Urb. Mar y Monte',
),
array(
												'id'     => 'Urb. Diana - Isdabe',
												'nombre' => 'Urb. Diana - Isdabe',
),
array(
												'id'     => 'Benamara - Benavista',
												'nombre' => 'Benamara - Benavista',
),
array(
												'id'     => 'Bel Ai',
												'nombre' => 'Bel Ai',
),
array(
												'id'     => 'Parada Bus Portillo Cancelada',
												'nombre' => 'Parada Bus Portillo Cancelada',
),
array(
												'id'     => 'Parque Antena',
												'nombre' => 'Parque Antena',
),
array(
												'id'     => 'El Pirata',
												'nombre' => 'El Pirata',
),
array(
												'id'     => 'El Veler�n',
												'nombre' => 'El Veler�n',
),
array(
												'id'     => 'El Padr�n',
												'nombre' => 'El Padr�n',
),
array(
												'id'     => 'Mc Donald\'s',
												'nombre' => 'Mc Donald\'s',
),
);

$transporte_oeste = array(
array(
												'id'     => 'Buenas Noches',
												'nombre' => 'Buenas Noches',
),
array(
												'id'     => 'Costa Galera',
												'nombre' => 'Costa Galera',
),
array(
												'id'     => 'Bah�a Dorada',
												'nombre' => 'Bah�a Dorada',
),
array(
												'id'     => 'Don Pedro',
												'nombre' => 'Don Pedro',
),
array(
												'id'     => 'Bah�a Azul',
												'nombre' => 'Bah�a Azul',
),
array(
												'id'     => 'G. Shell - H10',
												'nombre' => 'G. Shell - H10',
),
array(
												'id'     => 'Seghers Bajo (Babylon)',
												'nombre' => 'Seghers Bajo (Babylon)',
),
array(
												'id'     => 'Seghers Alto (Ed. Sierra Bermeja)',
												'nombre' => 'Seghers Alto (Ed. Sierra Bermeja)',
),
);

$opt_a2b = mysqli_query($db_con,"select opt_aut21 from matriculas_bach");
if (mysqli_num_rows($opt_aut2b) > 0) {}
	else{
mysqli_query($db_con,"ALTER TABLE  `matriculas_bach` ADD  `opt_aut21` INT( 1 ) NOT NULL ,
ADD  `opt_aut22` INT( 1 ) NOT NULL ,
ADD  `opt_aut23` INT( 1 ) NOT NULL ,
ADD  `opt_aut24` INT( 1 ) NOT NULL ,
ADD  `opt_aut25` INT( 1 ) NOT NULL ,
ADD  `opt_aut26` INT( 1 ) NOT NULL");
	}

// Asignaturas y Modalidades
// 1 BACHILLERATO
$it1 = array("1"=>"Ciencias e Ingenier�a y Arquitectura", "2"=>"Ciencias y Ciencias de la Salud", "3"=>"Humanidades", "4"=>"Ciencias Sociales y Jur�dicas");

$opt11=array( "CC11" => "Cultura Cient�fica 1", "TIC11" => "Tecnolog�as de Informaci�n y Comunicaci�n 1");
$opt12=array("CC12" => "Cultura Cient�fica 2", "TIC12" => "Tecnolog�as de Informaci�n y Comunicaci�n 2");
$opt13=array("LUN13" => "Literatura Universal 3","HMC13" => "Historia del Mundo Contempor�neo 3");
$opt14=array("LUN14" => "Literatura Universal 4","HMC14" => "Historia del Mundo Contempor�neo 4");

// 2 BACHILLERATO
$it2 = array("1"=>"Ciencias e Ingenier�a y Arquitectura", "2"=>"Ciencias y Ciencias de la Salud", "3"=>"Humanidades", "4"=>"Ciencias Sociales y Jur�dicas");

$it21 = array("Bachillerato de Ciencias", "Arquitectura e Ingenier�a y Ciencias", "Matem�ticas II", "F�sica", "Dibujo T�cnico II");
$it22 = array("Bachillerato de Ciencias", "Ciencias y Ciencias de la Salud", "Matem�ticas II", "Qu�mica", "Biolog�a");
$it23 = array("Bachillerato de Humanidades", "Humanidades", "Lat�n II", "Historia del Arte");
$it24 = array("Bachillerato de Ciencias Sociales", "Ciencias Sociales y Jur�dicas", "Matem�ticas de las Ciencias Sociales II", "Geograf�a");

$opt21=array("TIN21" => "Tecnolog�a Industrial 1 II", "CTM21" => "Ciencias de la Tierra y del Medio Ambiente 1", "PSI21" => "Psicolog�a 1", "GEO21" => "Geolog�a 1", "TIC21" => "TIC 1 II", "AL21" => "Alem�n 2� Idioma 1", "FR21" => "Franc�s 2� Idioma 1", "ING21" => "Ingl�s 2� Idioma 1");
$opt22=array("TIN22" => "Tecnolog�a Industrial 2 II", "CTM22" => "Ciencias de la Tierra y del Medio Ambiente 2", "PSI22" => "Psicolog�a 2", "GEO22" => "Geolog�a 2", "TIC22" => "TIC 2 II", "AL22" => "Alem�n 2� Idioma 2", "FR22" => "Franc�s 2� Idioma 2", "ING22" => "Ingl�s 2� Idioma 2");
$opt23=array( "TIC23" => "TIC II 3", "AL23" => "Alem�n 2� Idioma 3", "FR23" => "Franc�s 2� Idioma 3", "ING23" => "Ingl�s 2� Idioma 3");
$opt24=array( "TIC24" => "TIC II 4", "FAG24" => "Fundamentos de Administracci�n y Gesti�n 4", "AL24" => "Alem�n 2� Idioma 4", "FR24" => "Franc�s 2� Idioma 4", "ING24" => "Ingl�s 2� Idioma 4");

$opt_aut2=array("opt_aut21" => "Educaci�n F�sica", "opt_aut22" => "Estad�stica", "opt_aut23" => "Introducci�n Ciencias de la Salud", "opt_aut24" => "Alem�n 2� Idioma", "opt_aut25" => "Franc�s 2� Idioma", "opt_aut26" => "Ingl�s 2� Idioma", "opt_aut27" => "Electrotecnia");

// Se procesan los datos enviados ppor el formulario
if(isset($_POST['enviar'])){
	foreach($_POST as $key => $val)
	{
		${$key} = $val;
		//echo "${$key} --> $val<br />";
	}
	// Comprobaci�n de campos vac�os
	$nacimiento = str_replace("/","-",$nacimiento);
	$fecha0 = explode("-",$nacimiento);
	$fecha_nacimiento = "$fecha0[2]-$fecha0[1]-$fecha0[0]";
	$campos = "apellidos nombre nacido provincia nacimiento domicilio localidad padre dnitutor telefono1 telefono2 religion colegio sexo nacionalidad ";
	$itinerario1=substr($mod1,-1);
	$itinerario2=substr($mod2,-1);
	foreach($_POST as $key => $val)
	{		
		if ($mod1==1) {$optativa1=$optativa11;}elseif ($mod1==2) {$optativa1=$optativa12;}elseif($mod1==3){$optativa1=$optativa13;}elseif($mod1==4){$optativa1=$optativa14;}else{$optativa1="";}
		if ($key=="mod1") {
						if($optativa11=="" and $optativa12=="" and $optativa13=="" and $optativa14==""){
							$vacios.= "optativas de modalidad de 1BACH, ";
							$num+=1;
						}

		}
		

		if ($key=="mod2"){

			if ($mod2 > 2) {
				if($optativa2 == ""){
					$vacios.= "optativa2, ";
					$opt_mod2=1;
					$num+=1;
				}	
			}
							
			$n_o="";

				foreach (${opt2.$itinerario2} as $opt => $n_opt){
					foreach ($_POST as $clave=>$valor){
						if ($clave==$opt) {
							$n_o+=1;
							${optativa2b.$n_o}=$valor;
							if(${optativa2b.$n_o} == ""){
								$vacios.= "optativa2b".$n_o.", ";
								$num+=1;
							}
						}
					}
				}

			$n_o="";

				foreach ($opt_aut2 as $opt2 => $n_opt2){
					foreach ($_POST as $clave=>$valor){
						if ($clave==$opt2) {
							$n_o++;
							if(${opt_aut2.$n_o} == ""){
							$vacios.= "optativa libre ".$n_o.", ";
							$num+=1;
							}
						$tr_o = explode(", ",$n_opt2);
					}
				}
			}
		}


		if(strstr($campos,$key." ")==TRUE){
			if($val == ""){
				$vacios.= $key.", ";
				$num+=1;
			}
		}
	}

	if ($religion == "") {
		$vacios.= "religion, ";
		$num+=1;
	}
	if ($religion1b == "" and $curso=="2BACH" and $repetidor<>1) {
		$vacios.= "religion o alternativa de 1BACH, ";
		$num+=1;
	}
	if ($idioma1 == "") {
		$vacios.= "1� idioma, ";
		$num+=1;
	}
	if ($idioma2 == "" and $curso=="1BACH") {
		$vacios.= "2� idioma, ";
		$num+=1;
	}
	if ($curso=="2BACH" and $repetidor == ""  and (empty($itinerario1) or empty($itinerario2))) {
		$vacios.= "modalidad de 2, ";
		$num+=1;
	}
	if ($curso=="2BACH" and $repetidor == "1" and empty($itinerario2)) {
		$vacios.= "modalidad de 2, ";
		$num+=1;
	}
	if ($curso=="1BACH" and empty($itinerario1)) {
		$vacios.= "modalidad de 1�, ";
		$num+=1;
	}

	if ($sexo == "") {
		$vacios.= "sexo, ";
		$num+=1;
	}
	// Control de errores
	if($num > 0){
		$adv = substr($vacios,0,-2);
		echo '
<script> 
 alert("Los siguientes datos son obligatorios y no los has rellenado en el formulario de inscripci�n:\n ';
		$num_cur = substr($curso,0,1);
		$num_cur_ant = $num_cur - 1;
		$cur_act = substr($curso,0,1)."� de BACHILLERATO";
		$cur_ant = $num_cur_ant . "� de BACHILLERATO";
		for ($i=1;$i<8;$i++){
			$adv= str_replace("optativa$i", "optativa de $cur_ant $i", $adv);
		}
		for ($i=1;$i<5;$i++){
			$adv= str_replace("optativa$i", "optativa de $cur_act  $i", $adv);
		}
		echo $adv.'.\n';
		echo 'Rellena los campos mencionados y env�a los datos de nuevo para poder registrar tu solicitud correctamente.")
 </script>
';
	}
	else{

		if (substr($curso,0,1)==2){
			for ($i = 1; $i < 9; $i++) {
				for ($z = $i+1; $z < 9; $z++) {
					if (${optativa2b.$i}>0) {
						if (${optativa2b.$i}==${optativa2b.$z}) {
							$opt_rep="1";
						}
					}
				}
			}
		for ($i = 1; $i < 8; $i++) {
				for ($z = $i+1; $z < 8; $z++) {
					if (${opt_aut2.$i}>0) {
						if (${opt_aut2.$i}==${opt_aut2.$z}) {
							$opt_rep2="1";
						}
					}
				}
			}
		}


		if (substr($curso,0,1)==1 and ($idioma1==$idioma2)){
			$idioma_rep="1";
		}
		if($colegio == "Otro Centro" and ($otrocolegio == "" or $otrocolegio == "Escribe aqu� el nombre del Centro")){
			$vacios.="otrocolegio ";
			echo '
<script> 
 alert("No has escrito el nombre del Centro del que procede el alumno.\n';
			echo 'Rellena el nombre del Centro y env�a los datos de nuevo para poder registrar tu solicitud correctamente.")
 </script>
';
		}
		elseif($enfermedad == "Otra enfermedad" and ($otraenfermedad == "" or $otraenfermedad == "Escribe aqu� el nombre de la enfermedad")){
			$vacios.="otraenfermedad ";
			$msg_error = "No has escrito el nombre de la enfermedad del alumno. Rellena el nombre de la enfermedad y env�a los datos de nuevo para poder registrar tu solicitud correctamente.";
		}
		elseif(strstr($nacimiento,"-") == FALSE){
			echo '
<script> 
 alert("ATENCI�N:\n ';
			echo 'La fecha de nacimiento que has escrito no es correcta.\nEl formato adecuado para la fecha  es: dia-mes-a�o (01-01-1998).")
 </script>
';
		}
		elseif(strlen($ruta_este) > 0 and strlen($ruta_oeste) > 0){
			echo '
<script> 
 alert("ATENCI�N:\n';
			echo 'Parece que has seleccionado dos rutas incompatibles para el Transporte Escolar, y solo puedes seleccionar una ruta, hacia el Este o hacia el Oeste de '.$config['centro_localidad'].'.\nElige una sola parada y vuelve a enviar los datos.")
 </script>
';
			$ruta_error = "";
		}
		elseif ($opt_rep=="1"){
			echo '
						<script> 
 alert("ATENCI�N:\n';
			echo 'Parece que has seleccionado el mismo n�mero de preferencia para varias optativas, y cada optativa debe tener un n�mero de preferencia distinto.\nElige las optativas sin repetir el n�mero de preferencia e int�ntalo de nuevo.")
 </script>
';
		}
		elseif ($opt_rep2=="1"){
			echo '
						<script> 
 alert("ATENCI�N:\n';
			echo 'Parece que has seleccionado el mismo n�mero de preferencia para varias optativas, y cada optativa debe tener un n�mero de preferencia distinto.\nElige las optativas sin repetir el n�mero de preferencia e int�ntalo de nuevo.")
 </script>
';
		}
		elseif ($idioma_rep=="1"){
			echo '
						<script> 
 alert("ATENCI�N:\n';
			echo 'Parece que has seleccionado el mismo idioma como primera y segunda, y cada idioma debe ser distinto.\nElige los idiomas sin repetir e int�ntalo de nuevo.")
 </script>
';
		}

		else{
			if (strlen($claveal) > 3) {$extra = " claveal = '$claveal'";}
			elseif (strlen($dni) > 3) {$extra = " dni = '$dni'";}
			else {$extra = " dnitutor = '$dnitutor' ";}

			// El alumno ya se ha registrado anteriormente
			$ya_esta = mysqli_query($db_con, "select id from matriculas_bach where $extra");
			if (mysqli_num_rows($ya_esta) > 0) {
				$ya = mysqli_fetch_array($ya_esta);
				if (strlen($ruta_este) > 0 or strlen($ruta_oeste) > 0) {$transporte = '1';}
				if (empty($foto)) { $foto = "0";}
				$act_datos = "update matriculas_bach set apellidos='$apellidos', nombre='$nombre', nacido='$nacido', provincia='$provincia', nacimiento='$fecha_nacimiento', domicilio='$domicilio', localidad='$localidad', dni='$dni', padre='$padre', dnitutor='$dnitutor', madre='$madre', dnitutor2='$dnitutor2', telefono1='$telefono1', telefono2='$telefono2', religion='$religion', colegio='$colegio', otrocolegio='$otrocolegio', letra_grupo='$letra_grupo', idioma1='$idioma1', idioma2='$idioma2', religion = '$religion', observaciones = '$observaciones', promociona='$promociona', transporte='$transporte', ruta_este='$ruta_este', ruta_oeste='$ruta_oeste', curso='$curso', sexo = '$sexo', hermanos = '$hermanos', nacionalidad = '$nacionalidad', claveal = '$claveal', itinerario1 = '$itinerario1', itinerario2 = '$itinerario2', optativa1='$optativa1', optativa2='$optativa2', optativa2b1 = '$optativa2b1', optativa2b2 = '$optativa2b2', optativa2b3 = '$optativa2b3', optativa2b4 = '$optativa2b4', optativa2b5 = '$optativa2b5', optativa2b6 = '$optativa2b6', optativa2b7 = '$optativa2b7', optativa2b8 = '$optativa2b8', repite = '$repetidor', enfermedad = '$enfermedad', otraenfermedad = '$otraenfermedad', foto='$foto', bilinguismo='$bilinguismo', divorcio='$divorcio', religion1b='$religion1b', opt_aut21='$opt_aut21', opt_aut22='$opt_aut22', opt_aut23='$opt_aut23', opt_aut24='$opt_aut24', opt_aut25='$opt_aut25', opt_aut26='$opt_aut26', opt_aut27='$opt_aut27' where id = '$ya[0]'";
				//echo $act_datos."<br>";
				mysqli_query($db_con, $act_datos);
			}
			else{

				if (strlen($ruta) > 0) {$transporte = '1';}
				if (empty($foto)) { $foto = "0";}
				$con_matr =  "insert into matriculas_bach (apellidos, nombre, nacido, provincia, nacimiento, domicilio, localidad, dni, padre, dnitutor, madre, dnitutor2, telefono1, telefono2, colegio, otrocolegio, letra_grupo, correo, idioma1, idioma2, religion, optativa1, optativa2, optativa2b1, optativa2b2, optativa2b3, optativa2b4, optativa2b5, optativa2b6, optativa2b7, optativa2b8, observaciones, curso, fecha, promociona, transporte, ruta_este, ruta_oeste, sexo, hermanos, nacionalidad, claveal, itinerario1, itinerario2, repite, enfermedad, otraenfermedad, foto, bilinguismo, divorcio, religion1b, opt_aut21, opt_aut22, opt_aut23, opt_aut24, opt_aut25, opt_aut26, opt_aut27) VALUES ('$apellidos',  '$nombre', '$nacido', '$provincia', '$fecha_nacimiento', '$domicilio', '$localidad', '$dni', '$padre', '$dnitutor', '$madre', '$dnitutor2', '$telefono1', '$telefono2', '$colegio', '$otrocolegio', '$letra_grupo', '$correo', '$idioma1', '$idioma2', '$religion', '$optativa1', '$optativa2', '$optativa2b1', '$optativa2b2', '$optativa2b3', '$optativa2b4', '$optativa2b5', '$optativa2b6', '$optativa2b7', '$optativa2b8', '$observaciones', '$curso', now(), '$promociona', '$transporte', '$ruta_este', '$ruta_oeste', '$sexo', '$hermanos', '$nacionalidad', '$claveal', '$itinerario1', '$itinerario2', '$repetidor', '$enfermedad', '$otraenfermedad', '$foto', '$bilinguismo', '$divorcio', '$religion1b', '$opt_aut21', '$opt_aut22', '$opt_aut23', '$opt_aut24', '$opt_aut25', '$opt_aut26', '$opt_aut27')";
				mysqli_query($db_con, $con_matr);
				
				//echo $con_matr;
			}
			$ya_esta1 = mysqli_query($db_con, "select id from matriculas_bach where $extra");
			$ya_id = mysqli_fetch_array($ya_esta1);
			$id = $ya_id[0];
			if ($nuevo=="1") {
				include("imprimir.php");
			}
			else{
				?>
<link
	href="../../css/bootstrap.min.css" rel="stylesheet">
<link
	href="../../css/otros.css" rel="stylesheet">
<link
	href="../../css/bootstrap-responsive.min.css" rel="stylesheet">
<link
	href="../../css/font-awesome.min.css" rel="stylesheet">
<link
	href="../../css/imprimir.css" rel="stylesheet" media="print">
<br />
<br />
<div align="center">
<div class="alert alert-success alert-block fade in">
<button type="button" class="close" data-dismiss="alert">&times;</button>
Los datos de la Matr�cula se han registrado correctamente en la Base de
datos.</div>
</div>
<br />
				<?php
			}
			//	exit();
		}
	}
}

$n_curso="";

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="iso-8859-1">
<title>Intranet &middot; <?php echo $config['centro_denominacion']; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description"
	content="Intranet del <?php echo $config['centro_denominacion']; ?>">
<meta name="author"
	content="IESMonterroso (https://github.com/IESMonterroso/intranet/)">

<link href="../../css/bootstrap.min.css" rel="stylesheet">
<link href="../../css/otros.css" rel="stylesheet">

<link href="../../js/datetimepicker/bootstrap-datetimepicker.css"
	rel="stylesheet">
<link href="../../css/font-awesome.min.css" rel="stylesheet">
</head>

<body style="padding-top: 10px;">

<div class="container"><!-- MENSAJES --> <?php if(isset($msg_error)): ?>
<div class="alert alert-danger"><?php echo $msg_error; ?></div>
<?php endif; ?> <?php if(isset($msg_success)): ?>
<div class="alert alert-success"><?php echo $msg_success; ?></div>
<?php endif; ?> <?php

$cargo="1";

// Rellenar datos a partir de las tablas alma o matriculas.
if ($dni or $claveal or $id) {

	if (!empty($id)) {
		$conditio = " id = '$id'";
	}
	else{
		if (strlen($claveal) > 3) {$conditio = " claveal = '$claveal'"; $conditio1 = $conditio;}else{$conditio = " dni = '$dni' or dnitutor = '$dni' "; $conditio1 = " dni = '$dni' or dnitutor = '$dni' ";}
	}
	//echo $conditio;
	$curso = str_replace(" ","",$curso);
	
	// Comprobaci�n de padre con varios hijos en el Centro
	$ya_matricula = mysqli_query($db_con, "select claveal, apellidos, nombre, id from matriculas_bach where ". $conditio ."");
	$ya_secundaria = mysqli_query($db_con, "select claveal, apellidos, nombre from alma_secundaria where ". $conditio1 ."");
	$ya_alma = mysqli_query($db_con, "select claveal, apellidos, nombre, unidad, idcurso from alma, unidades where nomunidad=unidad and (". $conditio1 .")");
	// Comprobamos si el alumno se ha registrado ya
	$ya = mysqli_query($db_con, "select apellidos, nombre, nacido, provincia, nacimiento, domicilio, localidad, dni, padre, dnitutor, madre,
	dnitutor2, telefono1, telefono2, colegio, otrocolegio, letra_grupo, correo, idioma1, idioma2, religion, 
	itinerario1, itinerario2, optativa1, optativa2, optativa2b1, optativa2b2, optativa2b3, 
	optativa2b4, optativa2b5, optativa2b6, optativa2b7, optativa2b8, observaciones, curso, fecha, 
	promociona, transporte, ruta_este, ruta_oeste, sexo, hermanos, nacionalidad, claveal, itinerario1, itinerario2, repite, foto, enfermedad, otraenfermedad, bilinguismo, divorcio, religion1b, opt_aut21, opt_aut22, opt_aut23, opt_aut24, opt_aut25, opt_aut26, opt_aut27 from matriculas_bach where ". $conditio ."");

	// Ya se ha matriculado
	if (mysqli_num_rows($ya) > 0) {
		$datos_ya = mysqli_fetch_object($ya);
		$naci = explode("-",$datos_ya->nacimiento);
		$nacimiento = "$naci[2]-$naci[1]-$naci[0]";
		$apellidos = $datos_ya->apellidos; $id = $datos_ya->id; $nombre = $datos_ya->nombre; $nacido = $datos_ya->nacido; $provincia = $datos_ya->provincia; $domicilio = $datos_ya->domicilio; $localidad = $datos_ya->localidad; $dni = $datos_ya->dni; $padre = $datos_ya->padre; $dnitutor = $datos_ya->dnitutor; $madre = $datos_ya->madre; $dnitutor2 = $datos_ya->dnitutor2; $telefono1 = $datos_ya->telefono1; $telefono2 = $datos_ya->telefono2; $colegio = $datos_ya->colegio; $correo = $datos_ya->correo; $otrocolegio = $datos_ya->otrocolegio; $letra_grupo = $datos_ya->letra_grupo; $religion = $datos_ya->religion; $observaciones = $datos_ya->observaciones; $promociona = $datos_ya->promociona; $transporte = $datos_ya->transporte; $ruta_este = $datos_ya->ruta_este; $ruta_oeste = $datos_ya->ruta_oeste; $sexo = $datos_ya->sexo; $hermanos = $datos_ya->hermanos; $nacionalidad = $datos_ya->nacionalidad; $claveal = $datos_ya->claveal; $curso = $datos_ya->curso;  $itinerario1 = $datos_ya->itinerario1; $itinerario2 = $datos_ya->itinerario2; $optativa1 = $datos_ya->optativa1; $optativa2 = $datos_ya->optativa2; $optativa2b1 = $datos_ya->optativa2b1; $optativa2b2 = $datos_ya->optativa2b2; $optativa2b3 = $datos_ya->optativa2b3; $optativa2b4 = $datos_ya->optativa2b4; $optativa2b5 = $datos_ya->optativa2b5; $optativa2b6 = $datos_ya->optativa2b6; $optativa2b7 = $datos_ya->optativa2b7; $optativa2b8 = $datos_ya->optativa2b8; $repetidor = $datos_ya->repite; $idioma1 = $datos_ya->idioma1; $idioma2 = $datos_ya->idioma2; $foto = $datos_ya->foto; $enfermedad = $datos_ya->enfermedad; $otraenfermedad = $datos_ya->otraenfermedad; $bilinguismo = $datos_ya->bilinguismo; $divorcio = $datos_ya->divorcio; $religion1b = $datos_ya->religion1b; $opt_aut21 = $datos_ya->opt_aut21; $opt_aut22 = $datos_ya->opt_aut22; $opt_aut23 = $datos_ya->opt_aut23; $opt_aut24 = $datos_ya->opt_aut24; $opt_aut25 = $datos_ya->opt_aut25; $opt_aut26 = $datos_ya->opt_aut26; $opt_aut27 = $datos_ya->opt_aut27;
		
		$n_curso = substr($curso,0,1);
		if ($ruta_error == '1') {
			$ruta_este = "";
			$ruta_oeste = "";
		}
	}

	// Viene de Colegio de Secundaria
	elseif (mysqli_num_rows($ya_secundaria) > 0){
		$alma = mysqli_query($db_con, "select apellidos, nombre, provinciaresidencia, fecha, domicilio, localidad, dni, padre, dnitutor, concat(PRIMERAPELLIDOTUTOR2,' ',SEGUNDOAPELLIDOTUTOR2,', ',NOMBRETUTOR2), dnitutor2, telefono, telefonourgencia, correo, concat(PRIMERAPELLIDOTUTOR,' ',SEGUNDOAPELLIDOTUTOR,', ',NOMBRETUTOR), curso, sexo, nacionalidad, matriculas, claveal, colegio, unidad from alma_secundaria where ". $conditio1 ."");

		if (mysqli_num_rows($alma) > 0) {
			$al_alma = mysqli_fetch_array($alma);
			$apellidos = $al_alma[0];  $nombre = $al_alma[1]; $nacido = $al_alma[5]; $provincia = $al_alma[2]; $nacimiento = $al_alma[3]; $domicilio = $al_alma[4]; $localidad = $al_alma[5]; $dni = $al_alma[6]; $padre = $al_alma[7]; $dnitutor = $al_alma[8];
			if (strlen($al_alma[9]) > 3) {$madre = $al_alma[9];	}else{ $madre = ""; }
			; $dnitutor2 = $al_alma[10]; $telefono1 = $al_alma[11]; $telefono2 = $al_alma[12]; $correo = $al_alma[13]; $padre = $al_alma[14];
			$n_curso_ya = $al_alma[15]; $sexo = $al_alma[16]; $nacionalidad = $al_alma[17]; $letra_grupo = substr($al_alma[21],-1); $claveal= $al_alma[19]; $colegio= $al_alma[20];
			$nacimiento= str_replace("/","-",$nacimiento);
			$curso="1BACH";
			$n_curso=substr($curso, 0, 1);
		}
	}

	// Es alumno del Centro
	elseif (mysqli_num_rows($ya_alma) > 0){
		$alma = mysqli_query($db_con, "select apellidos, nombre, provinciaresidencia, fecha, domicilio, localidad, dni, padre, dnitutor, concat(PRIMERAPELLIDOTUTOR2,' ',SEGUNDOAPELLIDOTUTOR2,', ',NOMBRETUTOR2), dnitutor2, telefono, telefonourgencia, correo, concat(PRIMERAPELLIDOTUTOR,' ',SEGUNDOAPELLIDOTUTOR,', ',NOMBRETUTOR), curso, sexo, nacionalidad, matriculas, claveal, unidad, combasi, curso, matriculas, idcurso, localidadnacimiento from alma, unidades where nomunidad=unidad and (". $conditio1 .")");

		if (mysqli_num_rows($alma) > 0) {
			$al_alma = mysqli_fetch_array($alma);
			if (empty($curso)) {
				if ($al_alma[24]=="106183" or $al_alma[24]=="106201"){$curso="2BACH";}
				if ($al_alma[24]=="6204" or $al_alma[24]=="2067"){$curso="2BACH";}
				if ($al_alma[24]=="101143"){$curso="1BACH";}
			}
			else{
				if ($al_alma[24]=="101143"){$curso="1BACH";}
			}
			$n_curso = substr($curso,0,1);
//echo "Curso actual: ".$curso;
			$apellidos = $al_alma[0];  $nombre = $al_alma[1]; $nacido = $al_alma[5]; $provincia = $al_alma[2]; $nacimiento = $al_alma[3]; $domicilio = $al_alma[4]; $localidad = $al_alma[5]; $dni = $al_alma[6]; $padre = $al_alma[7]; $dnitutor = $al_alma[8];
			if ($madre == "") { if (strlen($al_alma[9]) > 3) {$madre = $al_alma[9];	}else{ $madre = ""; }}
			if ($dnitutor2 == "") { $dnitutor2 = $al_alma[10];} if ($telefono1 == "") { $telefono1 = $al_alma[11]; } if ($telefono2 == "") { $telefono2 = $al_alma[12];} if ($correo == "") { $correo = $al_alma[13];} $padre = $al_alma[14];
			$n_curso_ya = $al_alma[15]; $sexo = $al_alma[16]; $nacionalidad = $al_alma[17]; $letra_grupo = substr($al_alma[20],-1); $claveal= $al_alma[19]; $combasi = $al_alma[21]; $unidad = $al_alma[20]; $curso_largo = $al_alma[22]; $matriculas = $al_alma[23]; $nacido = $al_alma[25];
			if ($n_curso == substr($n_curso_ya,0,1)) {
				echo '
<script> 
 if(confirm("ATENCI�N:\n ';
				echo 'Has elegido matricularte en el mismo Curso( ';
				echo strtoupper($n_curso_ya);
				echo ') que ya has estudiado este a�o. \nEsta situaci�n solo puede significar que est�s absolutamente seguro de que vas a repetir el mismo Curso. Si te has equivocado al elegir Curso para el pr�ximo a�o escolar, vuelve atr�s y selecciona el curso correcto. De lo contrario, puedes continuar.")){}else{history.back()};
 </script>';
				/*if ($n_curso=="1") {
				 $repetidor = '1';
				 $repetidor1 = "1";
				 }*/
				$repetidor = '1';
				// ${repetidor.$n_curso} = "1";
			}
			$nacimiento= str_replace("/","-",$nacimiento);
			$colegio = $config['centro_denominacion'];
		}
	}
	?>
<form id="form1" name="form1" method="post"
	action="matriculas_bach.php<?php if($cargo == "1"){echo "?cargo=1";}?>">

<table align="center" class="table table-bordered">
	<!-- CABECERA: LOGOTIPO -->
	<thead>
		<tr>
			<td colspan="2" style="border-right: 0; height: 90px;"><img
				class="img-responsive" src="../../img/encabezado.jpg" alt=""
				width="350"></td>
			<td colspan="2">
			<h4 class="text-uppercase"><strong>Consejer�a de Educaci�n, Cultura y
			Deporte</strong></h4>
			<h5 class="text-uppercase"><strong><?php echo $config['centro_denominacion']; ?></strong></h5>
			</td>
		</tr>
	</thead>

	<!-- CUERPO -->
	<tbody>
	<?php
	// CURSO MATRICULA
	if (empty($n_curso)) $n_curso = substr($curso,0,1);

	switch ($curso) {
		case '1BACH' : $curso_matricula="PRIMERO"; break;
		case '2BACH' : $curso_matricula="SEGUNDO"; break;
	}
	?>
		<tr>
			<td colspan="4">
			<h4 class="text-center text-uppercase">SOLICITUD DE MATR�CULA EN <?php echo $curso_matricula; ?>
			DE BACHILLERATO</h4>
			</td>
		</tr>

		<?php
		if (substr($curso,0,1) == substr($n_curso_ya,0,1) and (substr($n_curso_ya,0,1) == "1") and $cargo == '1') {$repite_1bach = "1";}
		if (substr($curso,0,1) == substr($n_curso_ya,0,1) and (substr($n_curso_ya,0,1) == "2") and $cargo == '1') {$repite_2bach = "1";}
		?>

		<!-- DATOS PERSONALES DEL ALUMNO -->
		<tr>
			<th class="active text-center text-uppercase" colspan="4">Datos
			personales del alumno o alumna</th>
		</tr>
		<tr>
			<td class="col-sm-3">
			<div
				class="form-group <?php echo (strstr($vacios,"apellidos, ")==TRUE) ? 'has-error' : ''; ?>">
			<label for="apellidos">Apellidos</label> <input type="text"
				class="form-control" id="apellidos" name="apellidos"
				value="<?php echo (isset($apellidos)) ? $apellidos : ''; ?>"
				maxlength="60"></div>
			</td>
			<td class="col-sm-3">
			<div
				class="form-group <?php echo (strstr($vacios,"nombre, ")==TRUE) ? 'has-error' : ''; ?>">
			<label for="nombre">Nombre</label> <input type="text"
				class="form-control" id="nombre" name="nombre"
				value="<?php echo (isset($nombre)) ? $nombre : ''; ?>"
				maxlength="30"></div>
			</td>
			<td class="col-sm-3">
			<div
				class="form-group <?php echo (strstr($vacios,"nacimiento, ")==TRUE) ? 'has-error' : ''; ?>">
			<label for="nacimiento">Fecha de nacimiento</label> <input
				type="text" class="form-control" id="nacimiento" name="nacimiento"
				value="<?php echo (isset($nacimiento)) ? $nacimiento : ''; ?>"
				maxlength="11" data-date-format="DD-MM-YYYY"
				data-date-viewmode="years"></div>
			</td>
			<td class="col-sm-3">
			<div
				class="form-group <?php echo (strstr($vacios,"dni, ")==TRUE) ? 'has-error' : ''; ?>">
			<label for="dni">DNI / Pasaporte o equivalente</label> <input
				type="text" class="form-control" id="dni" name="dni"
				value="<?php echo (isset($dni)) ? $dni : ''; ?>" maxlength="10"></div>
			</td>
		</tr>
		<tr>
			<td class="col-sm-3">
			<div
				class="form-group <?php echo (strstr($vacios,"nacionalidad, ")==TRUE) ? 'has-error' : ''; ?>">
			<label for="nacionalidad">Nacionalidad</label> <input type="text"
				class="form-control" id="nacionalidad" name="nacionalidad"
				value="<?php echo (isset($nacionalidad)) ? $nacionalidad : ''; ?>"
				maxlength="30"></div>
			</td>
			<td>
			<div
				class="form-group <?php echo (strstr($vacios,"nacido, ")==TRUE) ? 'has-error' : ''; ?>">
			<label for="nacido">Nacido en</label> <input type="text"
				class="form-control" id="nacido" name="nacido"
				value="<?php echo (isset($nacido)) ? $nacido : ''; ?>"
				maxlength="30"></div>
			</td>
			<td>
			<div
				class="form-group <?php echo (strstr($vacios,"provincia, ")==TRUE) ? 'has-error' : ''; ?>">
			<label for="provincia">Provincia</label> <input type="text"
				class="form-control" id="provincia" name="provincia"
				value="<?php echo (isset($provincia)) ? $provincia : ''; ?>"
				maxlength="30"></div>
			</td>
			<td>
			<p><strong>Sexo</strong></p>
			<div class="form-inline">
			<div
				class="form-group <?php echo (strstr($vacios,"sexo, ")==TRUE) ? 'has-error' : ''; ?>">
			<div class="radio"><label> <input type="radio" name="sexo"
				value="hombre"
				<?php echo (isset($sexo) && $sexo == 'hombre' || $sexo == 'H') ? 'checked' : ''; ?>>
			&nbsp;Hombre </label></div>
			</div>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<div
				class="form-group <?php echo (strstr($vacios,"sexo, ")==TRUE) ? 'has-error' : ''; ?>">
			<div class="radio"><label> <input type="radio" name="sexo"
				value="mujer"
				<?php echo (isset($sexo) && $sexo == 'mujer' || $sexo == 'M') ? 'checked' : ''; ?>>
			&nbsp;Mujer </label></div>
			</div>
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="2">
			<div
				class="form-group <?php echo (strstr($vacios,"domicilio, ")==TRUE) ? 'has-error' : ''; ?>">
			<label for="domicilio">Domicilio, calle, plaza o avenida y n�mero</label>
			<input type="text" class="form-control" id="domicilio"
				name="domicilio"
				value="<?php echo (isset($domicilio)) ? $domicilio : ''; ?>"
				maxlength="60"></div>
			</td>
			<td>
			<div
				class="form-group <?php echo (strstr($vacios,"localidad, ")==TRUE) ? 'has-error' : ''; ?>">
			<label for="localidad">Municipio / Localidad</label> <input
				type="text" class="form-control" id="localidad" name="localidad"
				value="<?php echo (isset($localidad)) ? $localidad : ''; ?>"
				maxlength="30"></div>
			</td>
			<td>
			<div class="form-group"><label for="hermanos">N� de hermanos</label>
			<input type="number" class="form-control" id="hermanos"
				name="hermanos"
				value="<?php echo (isset($hermanos)) ? $hermanos : '0'; ?>" min="0"
				max="99" maxlength="2"></div>
			</td>
		</tr>
		<tr>
			<td>
			<div
				class="form-group <?php echo (strstr($vacios,"telefono1, ")==TRUE) ? 'has-error' : ''; ?>">
			<label for="telefono1">Tel�fono</label> <input type="text"
				class="form-control" id="telefono1" name="telefono1"
				value="<?php echo (isset($telefono1)) ? $telefono1 : ''; ?>"
				maxlength="9"></div>
			</td>
			<td>
			<div
				class="form-group <?php echo (strstr($vacios,"telefono2, ")==TRUE) ? 'has-error' : ''; ?>">
			<label for="telefono2">Tel�fono urgencias</label> <input type="text"
				class="form-control" id="telefono2" name="telefono2"
				value="<?php echo (isset($telefono2)) ? $telefono2 : ''; ?>"
				maxlength="9"></div>
			</td>
			<td>
			<div class="form-group"><label for="correo">Correo electr�nico</label>
			<input type="text" class="form-control" id="correo" name="correo"
				value="<?php echo (isset($correo)) ? $correo : ''; ?>"
				maxlength="120"></div>
			</td>
			<td rowspan="2">
			<div class="form-group <?php echo (strstr($vacios,"colegio, ")==TRUE) ? 'has-error' : ''; ?>">
			<label for="colegio">Centro de procedencia</label> 
			<select
				class="form-control" id="colegio" name="colegio">
				<?php if($curso == "1BACH"): 
				$cole_1=mysqli_query($db_con,"select distinct colegio from alma_secundaria order by colegio");
				?>
				<option value="<?php echo (isset($colegio)) ? $colegio : ''; ?>"><?php echo (isset($colegio)) ? $colegio : ''; ?></option>
				<?php while ($centros_adscritos=mysqli_fetch_array($cole_1)): ?>
				<option value="<?php echo $centros_adscritos[0]; ?>"
				<?php echo (isset($colegio) && $colegio == $centros_adscritos[0]) ? 'selected' : ''; ?>><?php echo $centros_adscritos[0]; ?></option>
				<?php endwhile; ?>
				<?php else: ?>
				<option value="<?php echo $config['centro_denominacion']; ?>"><?php echo $config['centro_denominacion']; ?></option>
				<?php endif; ?>
				<option value="Otro Centro">Otro Centro</option>
			</select></div>
			
			<div id="form-otrocolegio"
				class="form-group <?php echo (isset($otrocolegio) && !empty($otrocolegio)) ? '' : 'hidden'; ?>">
			<label for="otrocolegio">Centro educativo</label> <input type="text"
				class="form-control" id="otrocolegio" name="otrocolegio"
				value="<?php echo (isset($otrocolegio)) ? $otrocolegio : ''; ?>"
				maxlength="60" placeholder="Escribe aqu� el nombre del Centro"> <input
				type="hidden" name="letra_grupo"
				value="<?php echo (isset($letra_grupo)) ? $letra_grupo : ''; ?>"></div>
			</td>
		</tr>
		<tr>
			<td colspan="3">
			<p class="help-block"><small>El centro podr� enviar comunicaciones
			v�a SMS si proporciona el n�mero de un tel�fono m�vil o por correo
			electr�nico.</small></p>
			</td>
		</tr>
		<!-- DATOS DE LOS REPRESENTANTES O GUARDADORES LEGALES -->
		<tr>
			<th class="active text-center text-uppercase" colspan="4">Datos de
			los representantes o guardadores legales</th>
		</tr>
		<tr>
			<td colspan="3">
			<div
				class="form-group <?php echo (strstr($vacios,"padre, ")==TRUE) ? 'has-error' : ''; ?>">
			<label for="padre">Apellidos y nombre del representante o guardador
			legal 1 <br><small class="text-muted">(con quien conviva el alumno/a y tenga atribuida su
			guarda y custodia)</small></label> <input type="text"
				class="form-control" id="padre" name="padre"
				value="<?php echo (isset($padre)) ? $padre : ''; ?>" maxlength="60">
			</div>
			</td>
			<td>
			<div
				class="form-group <?php echo (strstr($vacios,"dnitutor, ")==TRUE) ? 'has-error' : ''; ?>">
			<label for="dnitutor">DNI / NIE</label> <input type="text"
				class="form-control" id="dnitutor" name="dnitutor"
				value="<?php echo (isset($dnitutor)) ? $dnitutor : ''; ?>"
				maxlength="10"></div>
			</td>
		</tr>
		<tr>
			<td colspan="3">
			<div class="form-group"><label for="madre">Apellidos y nombre del
			representante o guardador legal 2</label> <input type="text"
				class="form-control" id="madre" name="madre"
				value="<?php echo (isset($madre)) ? $madre : ''; ?>" maxlength="60">
			</div>
			</td>
			<td>
			<div
				class="form-group <?php echo ((isset($madre) && !empty($madre)) && (isset($dnitutor2) && empty($dnitutor2))) ? 'has-error' : ''; ?>">
			<label for="dnitutor2">DNI / NIE</label> <input type="text"
				class="form-control" id="dnitutor2" name="dnitutor2"
				value="<?php echo (isset($dnitutor2)) ? $dnitutor2 : ''; ?>"
				maxlength="10"></div>
			</td>
		</tr>

		<?php if($config['mod_transporte_escolar']): ?>
		<!-- TRANSPORTE ESCOLAR -->
		<tr>
			<th class="active text-center text-uppercase" colspan="4">Solicitud
			de transporte escolar</th>
		</tr>
		<tr>
			<td class="text-center" colspan="4">
			<div class="form-inline">

			<div class="form-group"><label for="ruta_este">Ruta Este:</label> <select
				class="form-control" id="ruta_este" name="ruta_este">
				<option value=""></option>
				<?php for ($i = 0; $i < count($transporte_este); $i++): ?>
				<option value="<?php echo $transporte_este[$i]['id']; ?>"
				<?php echo (isset($ruta_este) && $ruta_este == $transporte_este[$i]['id']) ? 'selected' : ''; ?>><?php echo $transporte_este[$i]['nombre']; ?></option>
				<?php endfor; ?>
			</select></div>

			&nbsp;&nbsp;&nbsp;&nbsp;

			<div class="form-group"><label for="ruta_oeste">Ruta Oeste:</label> <select
				class="form-control" id="ruta_oeste" name="ruta_oeste">
				<option value=""></option>
				<?php for ($i = 0; $i < count($transporte_oeste); $i++): ?>
				<option value="<?php echo $transporte_oeste[$i]['id']; ?>"
				<?php echo (isset($ruta_oeste) && $ruta_oeste == $transporte_oeste[$i]['id']) ? 'selected' : ''; ?>><?php echo $transporte_oeste[$i]['nombre']; ?></option>
				<?php endfor; ?>
			</select></div>

			</div>
			</td>
		</tr>
		<?php endif; ?>

		<!-- PRIMER IDIOMA Y RELIGION O ALTERNATIVA -->
		<tr>
			<th class="active text-center text-uppercase"
			<?php echo ($curso != 1) ? ' colspan="2"' : '' ?>>Primer idioma
			extranjero</th>
			<?php if ($curso == 1): ?>
			<th class="active text-center text-uppercase">Segundo idioma
			extranjero</th>
			<?php endif ?>
			<th class="active text-center text-uppercase" colspan="2">Opci�n de
			ense�anza de religi�n o alternativa<br>
			<small>(se�ale una)</small></th>
		</tr>
		<tr>
			<td <?php echo ($curso != 1) ? ' colspan="2"' : '' ?>>
			<div class="form-group"><select class="form-control" id="idioma1"
				name="idioma1">
				<?php $result = mysqli_query($db_con, "SELECT combasi FROM alma WHERE claveal='$claveal'"); ?>
				<?php $row = mysqli_fetch_array($result); ?>
				<?php $exp_combasi = explode(':', $row['combasi']); ?>
				<?php foreach ($exp_combasi as $codasi): ?>
				<?php $result1 = mysqli_query($db_con, "SELECT abrev, nombre FROM asignaturas WHERE codigo='$codasi' AND (abrev = 'ING' OR abrev = 'FRA')"); ?>
				<?php $row1 = mysqli_fetch_array($result1); ?>
				<?php if (!(empty($row1['nombre'])) && $n_curso=="2"): ?>
				<?php $idio = 1; ?>
				<?php $id_1b = $row1['nombre']; ?>
				<option value="<?php echo $row1['nombre']; ?>"><?php echo $row1['nombre']; ?></option>
				<?php endif; ?>
				<?php endforeach; ?>
				<?php if ($idio<>1 || $cargo=="1"): ?>
				<option value="Ingl�s"
				<?php echo (isset($idioma1) && $idioma1 == 'Ingl�s') ? 'selected' : ''; ?>>Ingl�s</option>
				<option value="Franc�s"
				<?php echo (isset($idioma1) && $idioma1 == 'Franc�s') ? 'selected' : ''; ?>>Franc�s</option>
				<?php endif; ?>
			</select> <?php if (isset($idio) && $idio): ?> <small
				class="help-block">(El Idioma de 2� de Bachillerato debe ser el
			mismo que el Idioma de 1� de Bachillerato. Para otras opciones,
			consulta en Jefatura de Estudios.)</small> <?php endif; ?></div>
			</td>
			<?php if ($curso == 1): ?>
			<td>
			<div class="form-group"><select class="form-control" id="idioma2"
				name="idioma2">
				<option value="Franc�s"
				<?php echo (isset($idioma2) && $idioma2 == 'Franc�s') ? 'selected' : ''; ?>>Franc�s</option>
				<option value="Alem�n"
				<?php echo (isset($idioma2) && $idioma2 == 'Alem�n') ? 'selected' : ''; ?>>Alem�n</option>
				<option value="Ingl�s"
				<?php echo (isset($idioma2) && $idioma2 == 'Ingl�s') ? 'selected' : ''; ?>>Ingl�s</option>
			</select></div>
			</td>
			<?php endif; ?>
			<td style="border-right: 0;">
			<div
				class="form-group <?php echo (strstr($vacios,"religion, ")==TRUE) ? 'has-error' : ''; ?>">
			<div class="radio"><label> <input type="radio" name="religion"
				value="Religi�n Cat�lica" required
				<?php if(stristr($religion,' Cat')==TRUE){echo "checked";} ?>>
			Religi�n Cat�lica </label></div>
			</div>

			<div
				class="form-group <?php echo (strstr($vacios,"religion, ")==TRUE) ? 'has-error' : ''; ?>">
			<div class="radio"><label> <input type="radio" name="religion"
				value="Religi�n Isl�mica" required
				<?php if($religion == 'Religi�n Isl�mica'){echo "checked";} ?>>
			Religi�n Isl�mica </label></div>
			</div>

			<div
				class="form-group <?php echo (strstr($vacios,"religion, ")==TRUE) ? 'has-error' : ''; ?>">
			<div class="radio"><label> <input type="radio" name="religion"
				value="Religi�n Jud�a" required
				<?php if($religion == 'Religi�n Jud�a'){echo "checked";} ?>>
			Religi�n Jud�a </label></div>
			</div>
			</td>
			<td>
			<div
				class="form-group <?php echo (strstr($vacios,"religion, ")==TRUE) ? 'has-error' : ''; ?>">
			<div class="radio"><label> <input type="radio" name="religion"
				value="Religi�n Evang�lica" required
				<?php if($religion == 'Religi�n Evang�lica'){echo "checked";} ?>>
			Religi�n Evang�lica </label></div>
			</div>
			<div
				class="form-group <?php echo (strstr($vacios,"religion, ")==TRUE) ? 'has-error' : ''; ?>">
			<div class="radio"><label> <input type="radio" name="religion"
				value="Valores �ticos" required
				<?php if($religion == 'Valores �ticos'){echo "checked";} ?>>
			Educaci�n para la Ciudadan�a y los Derechos Humanos</label></div>
			</div>
			</td>
		</tr>


		<!-- ASIGNATURAS DE MODALIDAD Y OPTATIVAS -->
		<tr>
			<th class="active text-center text-uppercase" colspan="4">Modalidad y
			asignaturas optativas de <?php echo $curso_matricula; ?> de
			Bachillerato</th>
		</tr>

		<!-- ASIGNATURAS DE MODALIDAD Y OPTATIVAS EN PRIMERO DE BACHILLERATO opt11-->
		<?php if($curso == "1BACH"): ?>
		<tr>
		<?php foreach ($it1 as $n_it1=>$itiner1){ ?>
			<td class="text-center" >
			<strong>
			<div class="radio" id='it1'>
			<label> <input required type="radio" name="mod1" value="<?php echo $n_it1; ?>" <?php echo ($itinerario1 == $n_it1) ? 'checked' : ''; ?> /><strong><?php echo $itiner1; ?></strong></label>
			</div>
			</strong>
			</td>
			<?php } ?>
		</tr>
		<tr>
		<?php for ($i = 1; $i <= 4; $i++){ ?>
		
		
		<td>
		<?php if ($i==1) { echo "<p>Matem�ticas<br>F�sica y Qu�mica<br>Dibujo T�cnico<br>Tecnolog�a Industrial</p>";}elseif($i==2){echo "<p>Matem�ticas<br>F�sica y Qu�mica<br>Biolog�a y Geolog�a<br>Anatom�a Aplicada</p>";} elseif($i==3){echo "<p>Lat�n I<br>Griego I<br>Patrimonio Cultural y Art�stico<br>TIC I</p>";} elseif($i==4){echo "<p>Matem�ticas de las Ciencias Sociales<br>Econom�a<br>Cultura Emprendedora<br>TIC I</p>";}?>

		<div class="form-group">
		<select class="form-control" name="optativa1<?php echo $i;?>"  <?php if(stristr($vacios,"optativas de modalidad de 1B")==TRUE and $mod1 == $i){echo 'style="background-color:#FFFF66;"';}?>>
		<option></option>
		<?php foreach (${opt1.$i} as $optit_1 => $nombre){ ?>
				<option value="<?php echo $optit_1; ?>"
				<?php echo (isset($optativa1) && $optativa1 == $optit_1 && ($itinerario1 == $i)) ? 'selected' : ''; ?>><?php echo $nombre; ?></option>

				
		<?php }?>
		</select>
		</div>
		</td>		
		<?php } ?>
	
		</tr>
		<?php endif;?>

		<!-- ASIGNATURAS DE MODALIDAD Y OPTATIVAS EN SEGUNDO DE BACHILLERATO -->
		<?php if ($curso == "2BACH"): ?>

		<?php
		if (empty($curso_largo)) {
			$cl = mysqli_query($db_con, "select curso from alma where claveal='$claveal'");
			$cl0 = mysqli_fetch_array($cl);
			$curso_largo = $cl0[0];
		}
		?>

		<tr>
		<?php foreach ($it2 as $n_it2=>$itiner2){ ?>
			<td class="text-center" >
			<strong>
			<div class="radio" id='it1'>
			<label> <input required type="radio" name="mod2" value="<?php echo $n_it2; ?>" <?php echo ($itinerario2 == $n_it2) ? 'checked' : ''; ?> /><strong><?php echo $itiner2; ?></strong></label>
			</div>
			</strong>
			</td>
			<?php } ?>
		</tr>

		<tr>
		<?php for ($i = 1; $i <= 4; $i++): ?>
			<td>
			<div class="text-left">
			<p><?php echo ${it2.$i}[2]; ?></p>
			<p><?php echo ${it2.$i}[3]; ?></p>
			<?php if($i<3){echo ${it2.$i}[4];} else{ ?>
			<hr>
			<div class="form-group">
			<div class="radio">
			<label> 
			<input type="radio"	value="Griego II" name="optativa2" 
			<?php echo ($optativa2 == 'Griego II' and $itinerario2 == $i) ? 'checked' : ''; ?>
			>
			Griego II 
			</label>
			</div>
			<div class="radio">
			<label> 
			<input type="radio"	value="Economia de la Empresa" name="optativa2"	
			<?php echo ($optativa2 == 'Economia de la Empresa' and $itinerario2 == $i) ? 'checked' : ''; ?>
			>
			Econom�a de la Empresa 
			</label>
			<br>
			<small class="help-block">(Selecciona una de las materias)</small>
			</div>
			</div>

			<?php } ?>
			</div>
			</td>
			<?php endfor; ?>
		</tr>
		<tr>
			<th colspan="4" class="active text-center text-uppercase">
				Asignaturas espec�ficas de Modalidad en Segundo de Bachillerato (4 horas lectivas)
			</th>
		</tr>


		<tr>
		<?php for ($i = 1; $i <= 4; $i++): ?>
			<td><?php $num1=""; $num_it = count(${opt2.$i}); ?>
			<?php foreach (${opt2.$i} as $optit_1 => $nombre): ?> <?php $num1 += 1; ?>
			<div class="form-horizontal">
				<label class="col-sm-8 control-label">
				<div class="text-left"><?php echo $nombre; ?></div> </label>
			<div class="form-group <?php echo ((isset($opt_rep) && $opt_rep == 1) or (stristr($vacios,"optativa2b")==TRUE  and $mod2 == $i)) ? 'has-error"' : '' ; ?>">
			<div class="col-sm-4">
				<?php //echo ${optativa2b.$num1}." ". $itinerario2."==".$i;?>
			<select class="form-control" name="<?php echo $optit_1; ?>" id="<?php echo $optit_1; ?>">
				<option value=""></option>
				<?php for ($z = 1; $z <= $num_it; $z++): ?>
				<option value="<?php echo $z; ?>" <?php echo (${optativa2b.$num1}==$z and $itinerario2==$i) ? 'selected' : ''; ?>>
					<?php echo $z; ?>
				</option>
				<?php endfor; ?>
			</select>
			</div>
			</div>

			
			</div>
			<?php endforeach; ?></td>
			<?php endfor; ?>
		</tr>
		<tr>
			<th class="active text-center text-uppercase" colspan="4">Asignaturas Optativas de 2� de Bachillerato (2 horas)<p class="help-block">
			<small>(Debes seleccionar las asignaturas optativas en su orden de preferencia: 1, 2, 3, etc.)</small></p></th>
		</tr>
		<tr>
			<td style="border-top: 0; text-align:left; <?php if(stristr($adv, "optativa libre")==TRUE) {echo 'background-color: #F2F5A9;';}?>" colspan="4" >
			<div class="form-horizontal">

			<?php $num1 = ""; ?>
			<?php foreach ($opt_aut2 as $opt_2): ?>
			<?php $num1 += 1; ?>
			<label class="col-sm-3 control-label">
			<div class="text-right"><?php echo $opt_2; ?></div>
			</label>
			<div class="col-sm-1">
				<select class="form-control <?php echo (isset($opt_rep2) && $opt_rep2 == 1) ? 'has-error"' : '';?>" id="opt_aut2<?php echo $num1;?>" name="opt_aut2<?php echo $num1;?>">
				<option value=""></option>
				<?php for ($z = 1; $z < 8; $z++): ?>
				<option value="<?php echo $z;?>"<?php echo (${opt_aut2.$num1} == $z) ? 'selected':'';?>><?php echo $z; ?></option>
				<?php endfor; ?>
			</select>
			</div>
			<?php endforeach; ?> 
			</div>
			</td>
		</tr>

		<?php if ($repetidor <> 1): ?>

		<!-- ASIGNATURAS OPTATIVAS DE PRIMERO DE BACHILLERATO -->
		
		<tr id="no_repite1">
			<th class="active text-center text-uppercase" colspan="4">
			Opciones de matriculaci�n en 1� de Bachillerato<p class="help-block"><small>
			(Para solicitar una modalidad o v�a diferente a la que ya has
			cursado debes pasar por Jefatura de Estudios)</small></p></th>
		</tr>
		<tr>
	<td colspan="4">
	<div class="form-group">
	<div class="checkbox"><label> <input type="checkbox" name="bilinguismo"
		value="Si" <?php if($bilinguismo == 'Si'){echo "checked";} ?>> El
	alumno/a solicita participar en el programa de biling�ismo (Ingl�s) en 1� de BACHILLERATO </label></div>
	</div>
	</td>
</tr>
<tr>
<tr>
	<td style="background-color: #eee;" colspan="4">
	<strong>Religi�n, Educaci�n para la Ciudadan�a o Cultura Cient�fica</strong>
	</td>
</tr>
<tr>
		
			<td colspan="4">
			
			
			<table style="width: 100%; border: none; <?php if(stristr($vacios,"religion o alternativa de 1BACH")==TRUE) {echo 'background-color: #FFFF66;'; } ?>">
			<tr>
				<td valign=top style="border: none;width:50%">
				<input type="radio" name="religion1b" value="Religi�n Catolica"
					style="margin: 2px 2px"
		<?php if($religion1b == 'Religi�n Catolica'){echo "checked";} ?> required />
				Religi&oacute;n Cat&oacute;lica<br />
				<input type="radio"
					name="religion1b" value="Religi�n Isl�mica" style="margin: 2px 2px"
		<?php if($religion1b == 'Religi�n Isl�mica'){echo "checked";} ?>  required />
				Religi&oacute;n Isl&aacute;mica<br />
				<input type="radio" name="religion1b" value="Religi�n Jud�a"
					style="margin: 2px 2px"
		<?php if($religion1b == 'Religi�n Jud�a'){echo "checked";} ?>  required />
				Religi&oacute;n Jud&iacute;a
				</td>
				<td valign=top style="border: none"><input type="radio"
					name="religion1b" value="Religi�n Evang�lica" style="margin: 2px 2px"
		<?php if($religion1b == 'Religi�n Evang�lica'){echo "checked";} ?>  required />

				Religi&oacute;n Evang&eacute;lica<br />
				<input type="radio" name="religion1b" value="Cultura Cient�fica"
					style="margin: 2px 2px"
		<?php if($religion1b == 'Cultura Cient�fica'){echo "checked";} ?>  required />
				Cultura Cient�fica<br />
				<input type="radio" name="religion1b" value="Valores �ticos"
					style="margin: 2px 2px"
		<?php if($religion1b == 'Valores �ticos'){echo "checked";} ?>  required />
				Educaci�n para la Ciudadan�a y los Derechos Humanos </td>
			</tr>
		</table>
			
			</td>
			
		</tr>


		<tr>
	<td style="background-color: #eee;" colspan="4">
	<strong>Modalidades y Optativas de 1� Bachillerato</strong>
	</td>
</tr>
		

<tr>
		<?php foreach ($it1 as $n_it1=>$itiner1){ ?>
			<td class="text-center" >
			<strong>
			<div class="radio" id='it1'>
			<label> <input required type="radio" name="mod1" value="<?php echo $n_it1; ?>" <?php echo ($itinerario1 == $n_it1) ? 'checked' : ''; ?> /><strong><?php echo $itiner1; ?></strong></label>
			</div>
			</strong>
			</td>
			<?php } ?>
		</tr>
		<tr>
		<?php for ($i = 1; $i <= 4; $i++){ ?>
		
		
		<td>
		<?php if ($i==1) { echo "<p>Matem�ticas<br>F�sica y Qu�mica<br>Dibujo T�cnico<br>Tecnolog�a Industrial</p>";}elseif($i==2){echo "<p>Matem�ticas<br>F�sica y Qu�mica<br>Biolog�a y Geolog�a<br>Anatom�a Aplicada</p>";} elseif($i==3){echo "<p>Lat�n I<br>Griego I<br>Patrimonio Cultural y Art�stico<br>TIC I</p>";} elseif($i==4){echo "<p>Matem�ticas de las Ciencias Sociales<br>Econom�a<br>Cultura Emprendedora<br>TIC I</p>";}?>

		<div class="form-group">
		<select class="form-control" name="optativa1<?php echo $i;?>"  <?php if(stristr($vacios,"optativas de modalidad de 1B")==TRUE and $mod1 == $i){echo 'style="background-color:#FFFF66;"';}?>>
		<option></option>
		<?php foreach (${opt1.$i} as $optit_1 => $nombre){ ?>
				<option value="<?php echo $optit_1; ?>"
				<?php echo (isset($optativa1) && $optativa1 == $optit_1 && ($itinerario1 == $i)) ? 'selected' : ''; ?>><?php echo $nombre; ?></option>

				
		<?php }?>
		</select>
		</div>
		</td>		
		<?php } ?>
	
		</tr>

		<?php endif; ?>

		<?php endif; ?>
		
		<!-- BILING�ISMO -->
		<?php if(substr($curso, 0, 1) < 2): ?>
		<tr>
			<td colspan="4">
			<div class="form-group">
			<div class="checkbox"><label> <input type="checkbox"
				name="bilinguismo" value="Si"
				<?php if($bilinguismo == 'Si'){echo "checked";} ?>> El alumno/a
			solicita participar en el programa de biling�ismo (Ingl�s) </label></div>
			</div>
			</td>
		</tr>
		<?php endif; ?>
		<!-- ENFERMEDADES -->
		<tr>
			<th class="active text-center" colspan="4"><span class="text-uppercase">Enfermedades del Alumno:</span><p class="help-block"><small>
			Se�alar si el alumno tiene alguna enfermedad que es importante que el
			Centro conozca por poder afectar a la vida acad�mica del alumno.</small></p></th>
		</tr>
		<tr>
			<td colspan="2" style="border-top: 0;">
			<p class="help-block"><small>
			Se�alar si el alumno tiene alguna enfermedad que es importante que el Centro conozca por poder afectar a la vida acad�mica del alumno.</small></p>
		<div
				class="form-group col-sm-5">
			<label for="enfermedad">Enfermedades del Alumno</label>					 
			<select
				class="form-control" id="enfermedad" name="enfermedad">
			<option value=""></option>	
				<?php for ($i = 0; $i < count($enfermedades); $i++): ?>
				<option value="<?php echo $enfermedades[$i]['id']; ?>"
				<?php echo (isset($enfermedad) && $enfermedad == $enfermedades[$i]['id']) ? 'selected' : ''; ?>><?php echo $enfermedades[$i]['nombre']; ?></option>
				<?php endfor; ?>
				<option value="Otra enfermedad"<?php echo (isset($enfermedad) && $enfermedad == "Otra enfermedad") ? 'selected' : ''; ?>>Otra enfermedad</option>
			</select></div>
			
			<div id="form-otraenfermedad"
				class="form-group <?php echo (isset($otraenfermedad) && !empty($otraenfermedad)) ? '' : 'hidden'; ?> col-sm-7">
			<label for="otraenfermedad">Otras enfermedades</label>
			<input type="text"
				class="form-control" id="otraenfermedad" name="otraenfermedad"
				value="<?php echo (isset($otraenfermedad)) ? $otraenfermedad : ''; ?>"
				maxlength="60" placeholder="Escribe aqu� el nombre de la enfermedad"> 
				</div>
			</td>

		<!-- DIVORCIOS -->			
			<td colspan="2" style="border-top: 0;">
			<p class="help-block"><small>
			Se�alar si el alumno procede de padres divorciados y cual es la situaci�n legal de la Guardia y Custodia respecto al mismo.</small></p>
		<div
				class="form-group col-sm-10">
			<label for="divorcio">Alumno con padres divorciados</label>					 
			<select
				class="form-control" id="divorcio" name="divorcio">
			<option value=""></option>	
				<?php for ($i = 0; $i < count($divorciados); $i++): ?>
				<option value="<?php echo $divorciados[$i]['id']; ?>"
				<?php echo (isset($divorcio) && $divorcio == $divorciados[$i]['id']) ? 'selected' : ''; ?>><?php echo $divorciados[$i]['nombre']; ?></option>
				<?php endfor; ?>
			</select>
			</div>
			</td>
		</tr>
		
		<!-- FOTO -->
		<tr>
			<th class="active text-center" colspan="4"><span class="text-uppercase">Foto del Alumno:</span><p class="help-block"><small>
			Desmarcar si la familia tiene alg�n inconveniente en que se publiquen en nuestra web fotograf�as del alumno por motivos educativos (Actividaes Complementarias y Extraescolares, etc.)</small></p></th>
		</tr>
		<tr>
			<td colspan="4" style="border-top: 0;">
		<div
				class="checkbox">
			<label for="foto"> 
			<?php if ($foto==1 or $foto=="") { $extra_foto = "checked";	} else {$extra_foto="";} ?>
			<input	type="checkbox" name = "foto"  id="foto" value = "1" <?php echo $extra_foto;?>>
			 Foto del Alumno </label>
			</div>
			</td>
		</tr>
		
		<!-- OBSERVACIONES -->
		<tr>
			<th class="active text-center" colspan="4"><span class="text-uppercase">Observaciones:</span><p class="help-block"><small>
			Indique aquellas cuestiones que considere sean importantes para
			conocimiento del Centro</small></p></th>
		</tr>
		<tr>
			<td colspan="4" style="border-top: 0;"><textarea class="form-control"
				id="observaciones" name="observaciones" rows="5"><?php echo (isset($observaciones)) ? $observaciones : ''; ?></textarea>
			</td>
		</tr>

	</tbody>
</table>


<!-- CAMPOS OCULTOS Y ENVIO DE FORMULARIO -->
<div class="text-center" colspan="4"><input type="hidden" name="curso"
	value="<?php echo (isset($curso)) ? $curso : ''; ?>"> <input
	type="hidden" name="nuevo"
	value="<?php echo (isset($nuevo)) ? $nuevo : ''; ?>"> <input
	type="hidden" name="curso_matricula"
	value="<?php echo (isset($curso_matricula)) ? $curso_matricula : ''; ?>">
<input type="hidden" name="claveal"
	value="<?php echo (isset($claveal)) ? $claveal : ''; ?>"> <input
	type="hidden" name="repetidor"
	value="<?php echo (isset($repetidor)) ? $repetidor : ''; ?>">

<button type="submit" class="btn btn-primary" name="enviar">Guardar
cambios</button>
<button type="reset" class="btn btn-default">Cancelar</button>
</div>

</form>

				<?php
}
else{ ?> <?php if($dni == '' || $dnitutor == ''): ?>
<div class="alert alert-danger">Debes introducir el DNI / Pasaporte o
equivalente del alumno/a o tutor legal que solicita la matriculaci�n en
este centro.</div>
<?php endif; ?> <?php if($curso == ''): ?>
<div class="alert alert-danger">Debes seleccionar el curso del alumno/a
que solicita la matriculaci�n en este centro.</div>
<?php endif; ?> <?php
}
?></div>
<!-- /.container -->

<?php include("../../pie.php"); ?>

<script>
	$(function ()  
	{ 
		$('#nacimiento').datetimepicker({
			language: 'es',
			pickTime: false
		})
	});  
	
	$(document).ready(function() {
			
		// Selector de Enfermedad
		$('#enfermedad').change(function() {
				if($('#enfermedad').val() == 'Otra enfermedad') {
					$('#form-otraenfermedad').removeClass('hidden');
				}
				else {
					$('#form-otraenfermedad').addClass('hidden');
				}
			});
		
		// Selector de colegio
		$('#colegio').change(function() {
			if($('#colegio').val() == 'Otro Centro') {
				$('#form-otrocolegio').removeClass('hidden');
			}
			else {
				$('#form-otrocolegio').addClass('hidden');
			}
		});
	});
	</script>

</body>
</html>
