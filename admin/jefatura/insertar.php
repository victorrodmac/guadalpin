<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); 

// Control de errores
if (!$fecha_reg or !$observaciones or !$causa or !$accion)
{
	echo '<div align="center"><div class="alert alert-warning alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h5>ATENCI�N:</h5>
No has introducido datos en alguno de los campos , y <strong> todos son obligatorios</strong>.<br> Vuelve atr�s e int�ntalo de nuevo.
</div></div><br />';
exit();
}

if ($alumno == "Todos los Alumnos") {
$todos0 = mysqli_query($db_con, "select distinct claveal, apellidos, nombre from alma where unidad = '$unidad'");
while ($todos = mysqli_fetch_array($todos0)) {
$clave=$todos[0];	
if (empty($prohibido)){$prohibido = "0";}
$tutor = "Jefatura de Estudios";
$apellidos = $todos[1];
$nombre = $todos[2];
$dia = explode("-",$fecha_reg);
$fecha2 = "$dia[2]-$dia[1]-$dia[0]";
$query="insert into tutoria (apellidos, nombre, tutor,unidad,observaciones,causa,accion,fecha, jefatura, prohibido, claveal) values 
('".$apellidos."','".$nombre."','".$tutor."','".$unidad."','".$observaciones."','".$causa."','".$accion."','".$fecha2."','1','".$prohibido."','".$clave."')";
mysqli_query($db_con, $query);
}
// Mensaje al Tutor
if($prohibido=="0") {
$tt = mysqli_query($db_con,"select idea from departamentos where nombre like (select tutor from FTUTORES where unidad = '$unidad')");
$tt0 = mysqli_fetch_row($tt);
$tutor_real = $tt0[0];

$asunto = "Nueva Intervenci�n de Tutor�a desde Jefatura de Estudios";
$texto = "Jefatura de Estudios ha registrado una intervenci�n sobre todos los alumnos de tu Tutor�a. Podr�s leerla en la lista de intervenciones del alumno de la p�gina del tutor.";
$profesor = $_SESSION['profi'];

$query0="insert into mens_texto (asunto, texto, origen) values ('".$asunto."','".$texto."','".$profesor."')";
mysqli_query($db_con, $query0);
$id0 = mysqli_query($db_con, "select id from mens_texto where asunto = '$asunto' and texto = '$texto' and origen = '$profesor'");
$id1 = mysqli_fetch_array($id0);
$id = $id1[0];
			
$query1="insert into mens_profes (id_texto, profesor) values ('".$id."','".$tutor_real."')";
mysqli_query($db_con, $query1);
}
echo '<div align="center"><div class="alert alert-success alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
Los datos se han introducido correctamente.
</div></div><br />';
}
else{
if (empty($prohibido)){$prohibido = "0";}
$tutor = "Jefatura de Estudios";
$tr = explode(" --> ",$alumno);
$al = $tr[0];
$clave = $tr[1];
$trozos = explode (", ", $al);
$apellidos = $trozos[0];
$nombre = $trozos[1];
$dia = explode("-",$fecha_reg);
$fecha2 = "$dia[2]-$dia[1]-$dia[0]";
if($fecha2=="0000-00-00"){$fecha2=date('Y-m-d');}
		$query="insert into tutoria (apellidos, nombre, tutor,unidad,observaciones,causa,accion,fecha, jefatura, prohibido, claveal) values 
		('".$apellidos."','".$nombre."','".$tutor."','".$unidad."','".$observaciones."','".$causa."','".$accion."','".$fecha2."','1','".$prohibido."','".$clave."')";
		 // echo $query;
mysqli_query($db_con, $query);
echo '<div align="center"><div class="alert alert-success alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
Los datos se han introducido correctamente.
</div></div><br />';

// Mensaje al Tutor
if($prohibido=="0") {
$tt = mysqli_query($db_con,"select idea from departamentos where nombre like (select tutor from FTUTORES where unidad = '$unidad')");
$tt0 = mysqli_fetch_row($tt);
$tutor_real = $tt0[0];

$asunto = "Nueva Intervenci�n de Tutor�a desde Jefatura de Estudios";
$texto = "Jefatura de Estudios ha registrado una intervenci�n sobre el alumno $nombre $apellidos de tu Tutor�a. Podr�s leerla en la lista de intervenciones del alumno de la p�gina del tutor.";
$profesor = $_SESSION['profi'];

$query0="insert into mens_texto (asunto, texto, origen) values ('".$asunto."','".$texto."','".$profesor."')";
mysqli_query($db_con, $query0);
$id0 = mysqli_query($db_con, "select id from mens_texto where asunto = '$asunto' and texto = '$texto' and origen = '$profesor'");
$id1 = mysqli_fetch_array($id0);
$id = $id1[0];
			
$query1="insert into mens_profes (id_texto, profesor) values ('".$id."','".$tutor_real."')";
mysqli_query($db_con, $query1);
}

}
  $al0 = mysqli_query($db_con, "select distinct id, alma.claveal, tutoria.claveal from tutoria, alma where tutoria.apellidos=alma.apellidos and tutoria.nombre=alma.nombre and tutoria.unidad=alma.unidad order by id");
  while($al1 = mysqli_fetch_array($al0))
  {
 $claveal = $al1[1];
 $clave_tut = $al1[2];
 $id = $al1[0];
 if (empty($clave_tut)) {
 	mysqli_query($db_con, "update tutoria set claveal='$claveal' where id='$id'");
 }
}
$id="";
?>
