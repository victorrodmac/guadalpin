<?php
require('../../bootstrap.php');

$PLUGIN_DATATABLES = 1;

include("../../menu.php");
include("../../faltas/menu.php");
?>

<div class="container">
	<div class="page-header">
		<h2>Faltas de Asistencia <small> Resumen de faltas por Grupo</small></h2>
	</div>
	<br />
	<div class="row">
		<div class="col-sm-8 col-sm-offset-2">

		<?php
		$AUXSQL == "";
		  
		IF (TRIM("$unidad")=="")
		{
		    $AUXSQL .= " AND 1=1 ";
		}
		else
		{
		    $AUXSQL .= " and alma.unidad like '%$unidad%'";
		}

		IF (TRIM("$mes")=="")
		{
		    $AUXSQL .= " AND 1=1 ";
		}
		else
		{
		    $AUXSQL .= " and (month(fecha)) = $mes";
		}
			 
		$SQL = "select alma.claveal, alma.apellidos, alma.nombre, alma.unidad, FALTAS.falta, count(*) as NUMERO from FALTAS, alma where alma.claveal = FALTAS.claveal " . $AUXSQL . "  and FALTAS.falta = '$FALTA'  GROUP BY alma.claveal, alma.apellidos, alma.nombre, alma.unidad, FALTAS.falta having NUMERO >= '$numero2'";
		$result = mysqli_query($db_con, $SQL);

		if ($row = mysqli_fetch_array($result))
		{
	        echo "<table class='table table-striped datatable'>\n";
	        echo "<thead><th width=\"60\"></th><th>Alumno</th><th>Grupo</th><th>Falta</th><th>Total</th></thead><tbody>";
            do {
				echo "<tr><td>";
				$foto = '../../xml/fotos/'.$row[0].'.jpg'; 
				if (file_exists($foto))
				{
					echo '<img src="'.$foto.'" width="55" alt="" />';
				}
				else
				{
					$foto = '../../xml/fotos/'.$row[0].'.JPG';
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
	            echo "<a href='informes.php?claveal=$row[0]&fechasp1=".$config['curso_inicio']."&fechasp3=".$config['curso_fin']."&submit2=2'>$row[1], $row[2]</a></td><td>$row[3]</td><td>$row[4]</td><td style='color:#9d261d'><strong>$row[5]</strong></td></tr>\n"; 
	        } while($row = mysqli_fetch_array($result));
	        echo "</tbody></table>";
	    }
	    else
	    {
			echo '<br /><div align="center"><div class="alert alert-warning alert-block fade in" style="max-width:500px;text-align:left">
	            <button type="button" class="close" data-dismiss="alert">&times;</button>
				<h5>ATENCI�N:</h5>
	No hay registros coincidentes, bien porque te has equivocado
	        al introducir los datos, bien porque ningun dato se ajusta a tus criterios.
			</div></div><br />';
        }
		// Eliminar Tabla temporal
		$SQLDEL = "DROP table `FALTASTEMP`";
		mysqli_query($db_con, $SQLDEL);
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
			
			"order": [[ 0, "desc" ]],
			
			"language": {
			            "lengthMenu": "_MENU_",
			            "zeroRecords": "No se ha encontrado ningún resultado con ese criterio.",
			            "info": "Página _PAGE_ de _PAGES_",
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
