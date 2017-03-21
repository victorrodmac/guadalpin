<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed');

// Creamos versi�n corta para FALTAS
mysqli_query($db_con, "CREATE TABLE almafaltas select CLAVEAL, NOMBRE, APELLIDOS, Unidad from alma") or die('<div align="center"><div class="alert alert-danger alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h5>ATENCI�N:</h5>
No se ha podido crear la tabla <strong>AlmaFaltas</strong> en la base de datos. 
Ponte en contacto con quien pueda resolver el problema.
</div></div><br /><br />
<div align="center">
  <input type="button" value="Volver atr�s" name="boton" onClick="../index.php" class="btn btn-primary" />
</div>');
mysqli_query($db_con, "ALTER TABLE almafaltas ADD PRIMARY KEY (  `CLAVEAL` )");

$elimina = "select distinct alma.claveal, alma.apellidos, alma.nombre, alma.unidad from alma, almafaltas where alma.claveal NOT IN (select distinct claveal from almafaltas)";
$elimina1 = mysqli_query($db_con, $elimina);
if(mysqli_num_rows($elimina1) > 0)
{
	echo "<div align='left'><div class='alert alert-warning alert-block fade in'>
            <button type='button'' class='close' data-dismiss='alert'>&times;</button>
            Tabla alma: los siguientes alumnos han sido eliminados de la tabla alma. <br>Comprueba los registros creados:</div></div>";
	while($elimina2 = mysqli_fetch_array($elimina1))
	{
		echo "<li>".$elimina2[2] . " " . $elimina2[1] . " -- " . $elimina2[3] . "</li>";
		$SQL6 = "DELETE FROM alma where claveal = '$elimina2[0]'";
		$SQL16 = "DELETE FROM usuarioalumno where claveal = '$elimina2[0]'";
		$result6 = mysqli_query($db_con, $SQL6);
		$result16 = mysqli_query($db_con, $SQL16);
	}
}
echo "<br />";

// Rellenamos datos en FALUMNOS desde almafaltas
$SQL0 = "SELECT distinct unidad FROM alma order by unidad";
$result0 = mysqli_query($db_con, $SQL0);
while  ($row0 = mysqli_fetch_array($result0))
{
	$SQL1 = "SELECT distinct CLAVEAL, APELLIDOS, NOMBRE, unidad FROM  alma WHERE unidad = '$row0[0]' ORDER BY APELLIDOS ASC, NOMBRE ASC";
	$result1 = mysqli_query($db_con, $SQL1);

	// Calculamos el numero de alumnos en cada curso
	$numero = mysqli_num_rows($result1);
	for($i=0; $i <= $numero -1; $i++)
	{
		while  ($row1= mysqli_fetch_array($result1))
		{
			$i = $i + 1 ;

			// Insertamos los datos en FALUMNOS
//								$SQL2 = "INSERT INTO FALUMNOS (CLAVEAL, APELLIDOS, NOMBRE, unidad, NC) VALUES (\"". $row1[0] . "\",\"". $row1[1] . "\",\"". $row1[2] . "\",\"". $row1[3] . "\",\"". $i . "\")";
//								echo $SQL2;
//								$result2 = mysqli_query($db_con, $SQL2);
			$SQL2 = "UPDATE alma set nc ='".$i ."' where claveal = '". $row1[0] ."'";
//								echo $SQL2;
			$result2 = mysqli_query($db_con, $SQL2);
		}
	}
}

$SQL1 = "select distinct almafaltas.claveal, almafaltas.apellidos, almafaltas.nombre, almafaltas.unidad from almafaltas where almafaltas.claveal NOT IN (select distinct claveal from alma)";
$result1 = mysqli_query($db_con, $SQL1);
$total = mysqli_num_rows($result1);
if ($total !== 0)
{
	echo "<div align='left'><div class='alert alert-success alert-block fade in'>
            <button type='button'' class='close' data-dismiss='alert'>&times;</button>
            Tabla alma: los nuevos alumnos han sido a�adidos a la tabla alma. <br>Comprueba en la lista de abajo los registros creados:</div></div>";
	while  ($row1= mysqli_fetch_array($result1))
	{
		// Buscamos el ultimo numero del Grupo del Alumno
		$SQL3 = "select MAX(nc) from alma where unidad = '$row1[3]'";
		$result3 = mysqli_query($db_con, $SQL3);
		while ($row3= mysqli_fetch_row($result3))
		{
			// Aumentamos el NC del ultimo en 1
			$numero = $row3[0] + 1;
			// Insertamos los datos en alma
			$SQL2 = "UPDATE alma set nc ='".$i ."' where claveal = '". $row1[0] ."'";
			echo $SQL2;
			$result2 = mysqli_query($db_con, $SQL2);
//			$SQL2 = "INSERT INTO alma (CLAVEAL, APELLIDOS, NOMBRE, unidad, NC) VALUES (\"". $row1[0] . "\",\"". $row1[1] . "\",\"". $row1[2] . "\",\"". $row1[3] . "\",\"". $numero . "\")";
			//echo $SQL2.";<br>";
			echo "<li>".$row1[2] . " " . $row1[1] . " -- " . $row1[3] . " -- " . $numero ."</li>";
//			$result2 = mysqli_query($db_con, $SQL2);

			// Usuario TIC
			$apellidos = $row1[1] ;
			$apellido = explode(" ",$row1[1] );
			$alternativo = strtolower(substr($row1[3],0,2));
			$nombreorig = $row1[2]  . " " . $row1[1] ;
			$nombre = $row1[2] ;
			$claveal = $row1[0] ;
			if (substr($nombre,0,1) == "�") {$nombre = str_replace("�","A",$nombre);}
			if (substr($nombre,0,1) == "�") {$nombre = str_replace("�","E",$nombre);}
			if (substr($nombre,0,1) == "�") {$nombre = str_replace("�","I",$nombre);}
			if (substr($nombre,0,1) == "�") {$nombre = str_replace("�","O",$nombre);}
			if (substr($nombre,0,1) == "�") {$nombre = str_replace("�","U",$nombre);}

			$apellido[0] = str_replace("�","A",$apellido[0]);
			$apellido[0] = str_replace("�","E",$apellido[0]);
			$apellido[0] = str_replace("�","I",$apellido[0]);
			$apellido[0] = str_replace("�","O",$apellido[0]);
			$apellido[0] = str_replace("�","U",$apellido[0]);
			$apellido[0] = str_replace("�","a",$apellido[0]);
			$apellido[0] = str_replace("�","e",$apellido[0]);
			$apellido[0] = str_replace("�","i",$apellido[0]);
			$apellido[0] = str_replace("�","o",$apellido[0]);
			$apellido[0] = str_replace("�","u",$apellido[0]);

			$userpass =
"a".strtolower(substr($nombre,0,1)).strtolower($apellido[0]);
			$userpass = str_replace("�","",$userpass);
			$userpass = str_replace("�","n",$userpass);
			$userpass = str_replace("-","_",$userpass);
			$userpass = str_replace("'","",$userpass);
			$userpass = str_replace("�","",$userpass);
			$userpass = str_replace("�","o",$userpass);

			$usuario  = $userpass;
			$passw = $userpass . preg_replace('/([ ])/e', 'rand(0,9)', '   ');
			$unidad = $row1[3];

			$repetidos = mysqli_query($db_con, "select usuario from usuarioalumno where
usuario like '$usuario%'");
			$n_a=0;
			while($num = mysqli_fetch_array($repetidos))
			{
				$n_a+=1;
			}
			$n_a+=1;
			$usuario = $usuario.$n_a;
			mysqli_query($db_con, "insert into usuarioalumno set nombre = \"".$nombreorig. "\", usuario = \"".$usuario. "\", pass = '$passw', perfil = 'a', unidad = '$unidad', claveal = '$claveal'");
			echo "<li>TIC: ".$nombreorig . " " . $usuario . " -- " . $unidad . "  " .$claveal. "</li>";
		}
	}
	echo "<br />";
}
else
{
	echo "<div align='left'><div class='alert alert-warning alert-block fade in'>
            <button type='button'' class='close' data-dismiss='alert'>&times;</button>
            Tabla alma: No se ha encontrado ningun registro nuevo para a�adir en alma.<br>Si crees que hay un problema, ponte en contacto con quien sepa arreglarlo</div></div><br />";	
}

// Cambio de grupo de un alumno.
/*$cambio0 = mysqli_query($db_con, "select claveal, unidad, apellidos, nombre from alma");
while($cambio = mysqli_fetch_array($cambio0)){
	$f_cambio0 = mysqli_query($db_con, "select unidad from FALUMNOS where claveal =
'$cambio[0]'");
	$f_cambio = mysqli_fetch_array($f_cambio0);
	if($cambio[1] == $f_cambio[0]){}
	else{
		$cambio_al+=1;
		$resultf = mysqli_query($db_con, "select MAX(NC) from FALUMNOS where unidad =
'$cambio[1]'");
		$f_result = mysqli_fetch_array($resultf);
		$f_numero = $f_result[0] + 1;
		mysqli_query($db_con, "update FALUMNOS set NC = '$f_numero', unidad =
'$cambio[1]' where claveal = '$cambio[0]'");
	}	
}*/

$cambio0 = mysqli_query($db_con, "select claveal, unidad, apellidos, nombre from alma");
while($cambio = mysqli_fetch_array($cambio0)){
	$f_cambio0 = mysqli_query($db_con, "select unidad from usuarioalumno where claveal = '$cambio[0]'");
	$f_cambio = mysqli_fetch_array($f_cambio0);
	if($cambio[1] == $f_cambio[0]){}
	else{
		mysqli_query($db_con, "update usuarioalumno set unidad = '$cambio[1]' where claveal = '$cambio[0]'");
	}	
}

mysqli_query($db_con, "drop table almafaltas");
?>

