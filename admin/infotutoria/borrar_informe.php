<?php
require('../../bootstrap.php');

if (file_exists('config.php')) {
	include('config.php');
}

include("../../menu.php");
include("menu.php");
?>

<div class="container">
<div class="row">
<div class="page-header">
  <h2>Informes de Tutor�a <small> Borrar Informe</small></h2>
</div>
<br />
<?php
if ($del=='1') {
	mysqli_query($db_con, "delete from infotut_alumno where id = '$id'");
		echo '<div align="center"><div class="alert alert-success alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
El Informe ha sido borrado sin problemas.
		</div></div>';
}
?>
</div>
</div>
<?php include("../../pie.php");?>
</body>
</html>
