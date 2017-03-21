<?php
require('../../bootstrap.php');

if (file_exists('config.php')) {
	include('config.php');
}
$pr = $_SESSION['profi'];
$cargo = $_SESSION['cargo'];


$tut = mysqli_query($db_con, "select unidad from FTUTORES where tutor = '$pr'");
$tuto = mysqli_fetch_array($tut);
$unidad = $tuto[0];

include("../../menu.php");
include("menu.php"); 
?>
<div class="container">
<div class="page-header">
<h2>Informes de Tutor�a <small> Informes activos</small></h2>
</div>

<div class="row">

<div class="col-md-6 col-md-offset-3">	
    
<?php
 //Validaci�n del Informe por el Tutor o Directivo
if (isset($_GET['validar'])) {
	$validar = $_GET['validar'];
	
if ($validar=='1') {
	mysqli_query($db_con, "update infotut_alumno set valido='0' where id = '$id'");
		echo '<div align="center"><div class="alert alert-warning alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
El Informe ha sido marcado como <b>NO VALIDADO</b> por el Tutor. Esto significa que el Informe no podr� ser visto por los Padres del Alumno desde la p�gina p�blica del Centro
		</div></div>';
}
elseif ($validar=='0') {
	mysqli_query($db_con, "update infotut_alumno set valido='1' where id = '$id'");
		echo '<div align="center"><div class="alert alert-success alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
El Informe ha sido marcado como <b>VALIDADO</b> por el Tutor. Esto significa que el Informe podr� ser visto por los Padres del Alumno desde la p�gina p�blica del Centro
		</div></div>';
}
}


// Buscamos los grupos que tiene el Profesor, con su asignatura y nivel
	$SQLcurso = "select distinct grupo, materia, nivel from profesores where profesor = '$pr'";
	$resultcurso = mysqli_query($db_con, $SQLcurso);
	while($rowcurso = mysqli_fetch_array($resultcurso))
	{
	$grupo = $rowcurso[0];
	$asignatura = trim($rowcurso[1]);
	

// Buscamos el c�digo de la asignatura (materia) de cada grupo al que da el profesor
	$asigna0 = "select codigo, nombre from asignaturas where nombre = '$asignatura' and curso = '$rowcurso[2]' and abrev not like '%\_%'";
	//echo "$asigna0<br>";
	$asigna1 = mysqli_query($db_con, $asigna0);
	$asigna2 = mysqli_fetch_array($asigna1);
	$c_asig = $asigna2[0];	
	$n_asig = $asigna2[1];
	$hoy = date('Y-m-d');
	$nuevafecha = strtotime ( '-2 day' , strtotime ( $hoy ) ) ;
	$nuevafecha = date ( 'Y-m-d' , $nuevafecha );

// Buscamos los alumnos de esos grupos que tienen informes de Tutor�a activos y adem�s tienen esa asignatura en el campo combasi	
	$query = "SELECT id, infotut_alumno.apellidos, infotut_alumno.nombre, F_ENTREV, FECHA_REGISTRO, valido FROM infotut_alumno, alma WHERE alma.claveal = infotut_alumno.claveal and date(F_ENTREV)>='$nuevafecha' and alma.unidad = '$grupo' and combasi like '%$c_asig%' ORDER BY F_ENTREV asc";
	//echo $query."<br>";
	$result = mysqli_query($db_con, $query);
	$result0 = mysqli_query($db_con, "select tutor, unidad from FTUTORES where unidad = '$grupo'" );
	$row0 = mysqli_fetch_array ( $result0 );	
	$tuti = mb_strtoupper($row0[0]);
	$tuti_grupo = $row0[1];
	if (mysqli_num_rows($result) < 1){ }
	else{
		$si_al.=1;	
echo "<form name='consulta' method='POST' action='tutoria.php'>";
//$num_informe = mysqli_num_rows($sql1);
echo "<p class='lead text-info'>$grupo <br /><small class='text-muted'>$n_asig</small></p>";
echo "<div class='table-responsive'><table align=left class='table'><tr class='active'>";
echo "<th>Alumno</th>
<th>Cita padres</th>
<th>Fecha alta</th>
<th></th>
<th></th>
</tr>";
$count = "";
	while($row = mysqli_fetch_array($result))
	{
		$validado="";
		$validado =  $row[5];
		$count = $count + 1;
	echo "<tr><TD>
	 $row[1], $row[2]</td>
   <TD>$row[3]</td>
   <TD>$row[4] </td>
   <td>";
	 echo "
	 <input type='hidden' name='profesor' value='$profesor'>";
		 if (mysqli_num_rows($si) > 0 and $count < 1)
		{} else{ 
		//echo "$grupo == ".$_SESSION['mod_tutoria']['unidad'];
			echo "<a href='infocompleto.php?id=$row[0]&c_asig=$asignatura' class=''><i class='fa fa-search fa-fw fa-lg' data-bs='tooltip'  title='Ver Informe'> </i></a>";	
			if (stristr($cargo,'1') == TRUE or ($tuti == mb_strtoupper($_SESSION['profi']) and ($grupo == $_SESSION['mod_tutoria']['unidad']))) {
				echo "&nbsp;<a href='borrar_informe.php?id=$row[0]&del=1' class=''>
				<i class='fa fa-trash-o fa-fw fa-lg' data-bs='tooltip'  title='Borrar Informe' > </i> </a> 	";
			}
		}	
		 
	  if (mysqli_num_rows($si) > 0 and $count < 1)
		{} else{ 
echo "&nbsp;<a href='informar.php?id=$row[0]' class=''><i class='fa fa-pencil-square-o fa-fw fa-lg' data-bs='tooltip'  title='Redactar Informe'> </i> </a>";
				}
		echo "</td><td>";	
		//echo "$tuti == ".$_SESSION['profi']."<br>";
				if (stristr($cargo,'1') == TRUE or ($tuti == mb_strtoupper($_SESSION['profi']))) {
					if ($validado==1) {
				echo "&nbsp;<a href='index.php?id=$row[0]&validar=1' class='text-info'><i class='fa fa-check-square-o fa-fw fa-lg' data-bs='tooltip'  title='Informe validado por el Tutor' > </i></a> 	";				
					}
					else{
				echo "&nbsp;<a href='index.php?id=$row[0]&validar=0' class='text-danger'><i class='fa fa-minus-circle fa-fw fa-lg' data-bs='tooltip'  title='Informe no validado por el Tutor' > </i> </a> 	";					
					}

			}
   echo "</td>
   </tr>";		
	}	
	echo "</table></div>";
	 
	echo "</form><hr>";
	}
}

if (strstr($si_al,"1")==FALSE) {
 			echo "<div class='alert alert-info' align='center'><p><i class='fa fa-check-square-o
'> </i> No hay Informes de Tutor�a activos para ti. </p>";
 		} 	
?>
  </div>  
  </div>  
  </div>
  </div>
<?php include("../../pie.php");?>
</body>
</html>
