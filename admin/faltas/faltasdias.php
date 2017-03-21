<?php
require('../../bootstrap.php');


include("../../menu.php");
include("../../faltas/menu.php");
$PLUGIN_DATATABLES = 1;
?>
<style type="text/css">
.table td{
	vertical-align:middle;
}
</style>
<?php
$imprimir_activado = true;
$fechasq0=explode("-",$fecha10);
$fechasq1=$fechasq0[2]."-".$fechasq0[1]."-".$fechasq0[0];
$fechasq2=explode("-",$fecha20);
$fechasq3=$fechasq2[2]."-".$fechasq2[1]."-".$fechasq2[0];
echo '<div class="container">
		<div class="row">
			<div class="col-sm-2"></div>
			<div class="col-sm-8">';
				echo '<div class="page-header"><h2>Faltas de Asistencia <small> Informe de faltas</small></h2></div>';
		        echo "<legend align='center' class='text-info'>Alumnos con m�s de <strong class='text-info'>$numero</strong> faltas de asistencia<br> entre los d�as <strong class='text-info'>$fechasq1</strong> y <strong class='text-info'>$fechasq3</strong></legend>
				<table class='table table-striped datatable' style='width:100%;'>";
		        echo "<thead><tr><th width=\"60\"></th><th>Alumno</th><th>Curso</th>
		        <th nowrap>N� faltas</th><th nowrap>N� d�as</th></tr></thead><tbody>";

				$SQLTEMP2 = "select CLAVEAL, nombre, apellidos, unidad, count(*) AS dias, sum(numero) as faltas from (SELECT FALTAS.CLAVEAL, nombre, apellidos, FALTAS.unidad, FALTAS.fecha, (count(*)) AS numero FROM FALTAS, alma where alma.claveal = FALTAS.claveal and falta = 'F' and FALTAS.fecha >= '$fechasq1' and FALTAS.fecha <= '$fechasq3'  group by FALTAS.claveal, FALTAS.unidad, FALTAS.fecha, nombre, apellidos) as p group by CLAVEAL, unidad, nombre, apellidos  having faltas > $numero";  
				$resultTEMP= mysqli_query($db_con, $SQLTEMP2);

				//Fecha del d�a
				$fhoy=getdate();
				$fecha=$fhoy[mday]."-".$fhoy[mon]."-".$fhoy[year];

				while  ($row0 = mysqli_fetch_array($resultTEMP))
				{
					$claveal = $row0[0];
					echo "<tr><td>";
					$foto = '../../xml/fotos/'.$row0[0].'.jpg'; 
					if (file_exists($foto))
					{
						echo '<img src="'.$foto.'" width="55" alt="" />';
					}
					else
					{
						$foto = '../../xml/fotos/'.$row0[0].'.JPG';
						if (file_exists($foto))
						{
							echo '<img src="'.$foto.'" width="55" alt="" />';
						}
						else
						{
							echo '<span class="fa fa-user fa-fw fa-3x"></span>';
						}
					}
					echo "</td><td>";
					echo "$row0[1] $row0[2]</td><td>$row0[3]</td>
					<td><strong style='color:#9d261d'>$row0[4]</strong></td>";
				    echo "<td><strong style='color:#46a546'>$row0[5]</strong></td></tr>";
				}
			       
			  ?>
			</tbody>
			</table>
			</div>
		</div>
	</div>
</div>
<?php include("../../pie.php");?>
	<script>
	$(document).ready(function() {
	  var table = $('.datatable').DataTable({
	  		"paging":   true,
	      "ordering": true,
	      "info":     false,
	      
	  		"lengthMenu": [[15, 35, 50, -1], [15, 35, 50, "Todos"]],
	  		
	  		"order": [[ 2, "desc" ]],
	  		
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
</body>
</html>

