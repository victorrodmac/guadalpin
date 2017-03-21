<?php
require('../../bootstrap.php');

if (file_exists('config.php')) {
	include('config.php');
}

  $al0 = mysqli_query($db_con, "select distinct id, alma.claveal, tutoria.claveal from tutoria, alma where tutoria.apellidos=alma.apellidos and tutoria.nombre=alma.nombre and tutoria.unidad=alma.unidad order by id");
  while($al1 = mysqli_fetch_array($al0))
  {
 $claveal = $al1[1];
 $clave_tut = $al1[2];
 $id = $al1[0];
 if (empty($clave_tut)) {
 	mysqli_query($db_con, "update tutoria set claveal='$claveal' where id='$id'");
echo "OK<br />";
 }

}
?>
