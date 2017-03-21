<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

$PLUGIN_DATATABLES = 1;

include("../../menu.php");
include("../../faltas/menu.php");
?>
<div class="container">

<div class="page-header">
  <h2>Informe sobre Faltas de Asistencia <small>Faltas no registradas</small></h2>
</div>

<div id="status-loading" class="text-center">
    <br><br><span class="lead"><span class="fa fa-circle-o-notch fa-spin"></span> Cargando datos...<br><small>El proceso puede tomar alg�n tiempo.</small><br><br></span>
</div>

<div id="wrap" class="row" style="display: none;">

<?php include("menu_informes.php"); ?>

<?php 
mysqli_query($db_con,"CREATE TABLE IF NOT EXISTS `faltas_control` (
`id` int(11) NOT NULL auto_increment,
  `profesor` tinyint(4) NOT NULL,
  `alumno` int(11) NOT NULL,
  `asignatura` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `num_profes` tinyint(4) NOT NULL,
  `hora` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1");
?>

<br>

<div class="alert alert-info">
	Esta p�gina presenta informaci�n sobre faltas de asistencia no registradas por los profesores. Para entender los datos ofrecidos es necesario comprender el procedimiento seguido para extraerlos. Se han seleccionado los alumnos a los que se les han registrado 3 o m�s faltas en un d�a, con la idea de excluir aquellos alumnos que han podido faltar una o dos horas por razones frecuentes (cita m�dica, se han dormido, etc.). De esos alumnos con 3 o m�s faltas se han buscado las horas no registradas por los profesores, y se han localizado a aquellos que les daban clase en esas horas. Se han tenido en cuenta los grupos con desdobles en la contabilidad, reduciendo el n�mero final de faltas no registradas. <strong>El resultado final es aproximativo y contiene probablemente algunas imprecisiones</strong>, pero permite hacerse una composici�n de lugar sobre los profesores que no registran las faltas de asistencia (<em>que puede/debe complementarse con el Informe sobre los Profesores</em>).
</div>

<br>

<div class="col-sm-4 col-sm-offset-4">

<?php 
// Comprobamos i hay datos anetriores.
$hay = mysqli_query($db_con,"select max(fecha) from faltas_control");
if (mysqli_num_rows($hay)>0) {
  $ultima = mysqli_fetch_array($hay);
  $extra = "and date(fecha) > '$ultima[0]'";
}

// Creamos tabla temporal
$crea = mysqli_query($db_con,"create table faltas_tmp select distinct claveal, fecha from FALTAS where falta not like 'R' ".$extra."");

$num_horas="";

$rc = mysqli_query($db_con,"select * from faltas_tmp");
while ($nrec = mysqli_fetch_array($rc)) {

$rec = mysqli_query($db_con,"select * from FALTAS where claveal='$nrec[0]' and fecha = '$nrec[1]' and falta not like 'R' order by hora");
$num_horas = mysqli_num_rows($rec);


if ($num_horas<6 and $num_horas>2) {

$prof_flt = mysqli_query($db_con, "SELECT distinct hora FROM FALTAS where profesor not like '' and codasi not like '' and claveal='$nrec[0]' and fecha = '$nrec[1]' and falta not like 'R' order by hora");

$hr="";
$dia="";

while ($fl = mysqli_fetch_array($prof_flt)) {
  $hr[]=$fl[0];
}

  for ($i=1; $i < 7; $i++) { 

    if (in_array($i,$hr)) { 
      }
      else{
          $dia=date('w', strtotime($nrec[1]));
          $num_profes="";
              $prf = mysqli_query($db_con, "SELECT distinct c_prof, c_asig FROM horw where dia = '$dia' and hora = '$i' and a_grupo = (select unidad from alma where claveal = '$nrec[0]')");

              $num_profes = mysqli_num_rows($prf);
                while($prfe = mysqli_fetch_array($prf)){
                mysqli_query($db_con,"insert into faltas_control VALUES('','$prfe[0]','$nrec[0]','$prfe[1]','$nrec[1]','$num_profes','$i')");
                }
            }
        }
    }
}

// Presentamos los datos en una tabla
?>
  <table class='table table-bordered table-striped table-vcentered datatable'>
      <thead>
        <tr>
          <th>Profesor</th>
          <th>Total</th>
        </tr>
      </thead>
  <tbody>
<?php 
$nom_profe = mysqli_query($db_con,"select distinct prof, c_prof from horw order by no_prof");
while ($nombre_profe=mysqli_fetch_array($nom_profe)) {

$veces="";

$dat = mysqli_query($db_con,"select profesor, num_profes from faltas_control where profesor = '$nombre_profe[1]'");
while ($dato = mysqli_fetch_array($dat)) {
  if ($dato[1]>1) {
    $veces+=1/$dato[1];
  }
  else{
    $veces+=1;
  }
  }
  $veces=round($veces);
  if ($veces>0) {
    echo "<tr><td>$nombre_profe[0]</td><td>$veces</td></tr>";
  }
}
?>
</tbody>
</table>
<?php
$borra=mysqli_query($db_con,"DROP TABLE faltas_tmp");        
?>

</div>
</div>
</div>

<?php	
include("../../pie.php");
?>  
    <script>
  $(document).ready(function() {
    var table = $('.datatable').DataTable({
        "paging":   true,
        "ordering": true,
        "info":     false,
        
        "lengthMenu": [[15, 35, 50, -1], [15, 35, 50, "Todos"]],
        
        "order": [[ 1, "asc" ]],
        
        "language": {
                    "lengthMenu": "_MENU_",
                    "zeroRecords": "No se ha encontrado ning�n resultado con ese criterio.",
                    "info": "P�gina _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay resultados disponibles.",
                    "infoFiltered": "(filtrado de _MAX_ resultados)",
                    "search": "Buscar: ",
                    "paginate": {
                          "first": "Primera",
                          "next": "�ltima",
                          "next": "",
                          "previous": ""
                        }
                }
      });
  });
  </script> 

<script>
function espera() {
  document.getElementById("wrap").style.display = '';
  document.getElementById("status-loading").style.display = 'none';        
}
window.onload = espera;
</script>

</body>
</html>
