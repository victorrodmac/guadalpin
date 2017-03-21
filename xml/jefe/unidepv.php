<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

include("../../menu.php");
?>



	<div class="container">
		
		<div class="page-header">
		  <h2>Administración <small>Importación de de Grupos-Aulas si no se han introducido en el programa horw</small></h2>
		</div>
		
			
		<div id="status-loading" class="text-center">
			<span class="lead"><span class="fa fa-circle-o-notch fa-spin"></span> Cargando...</span>
		</div>
		
		
		<div id="wrap" class="row" style="display: none;">
		
			<div class="col-sm-12">
								
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Codigo grupo</th>
							<th>Grupo</th>
							<th>Codigo nivel</th>
							<th>Codigo aula</th>
							<th>Aula</th>
							<th>Descripción Aula</th>
						</tr>
					</thead>
					<tbody>
							<?php
							if(isset($_FILES['archivo'])) {
							
								$file = fopen($_FILES['archivo']['tmp_name'], "r");
								
								$conectores = array("(", ")", "de ", "del ", "de la ", "de los ", "de las ", "y ", "y de ");
								$acentos_no_permitidos = array("á","é","í","ó","ú","Á","É","Í","Ó","Ú");
								$acentos_permitidos = array("a","e","i","o","u","A","E","I","O","U");
								
								$linea = 1;
								while(($row = fgetcsv($file, 1000, ",")) !== FALSE) {
									//echo $row[0]."fila".$linea;
									
									$cod_grupo	= mysqli_real_escape_string($db_con, trim($row[0]));
									$grupo		= mysqli_real_escape_string($db_con, trim($row[1]));
									$cod_nivel	= mysqli_real_escape_string($db_con, trim($row[2]));
									$cod_aula	= mysqli_real_escape_string($db_con, trim($row[3]));
									$nombaula	= mysqli_real_escape_string($db_con, trim($row[4]));
									$descaula	= mysqli_real_escape_string($db_con, trim($row[5]));
										
									$sql = "UPDATE horw SET `a_aula` = '$descaula' WHERE `a_grupo` = '$grupo' and `a_aula` = ''";
									mysqli_query($db_con, $sql);
									echo "<tr>\n";
									echo "<td>".$cod_grupo."</td>\n";
									echo "<td>".$grupo."</td>\n";
									echo "<td>".$cod_nivel."</td>\n";
									echo "<td>".$cod_aula."</td>\n";
									echo "<td>".$nombaula."</td>\n";
									echo "<td>".$descaula."</td>\n";
									echo "</tr>\n";										
										
									$sql = "UPDATE aulas SET isinhorw = 1 where a_aula ='".$descaula."'";
									//echo $sql;
									mysqli_query($db_con,$sql);
									
									$linea++;
								}
								fclose($file);
								
							}
							
							
							?>
						</tr>
					</tbody>
				</table>
					
				
				<div class="hidden-print">
					<a class="btn btn-primary" href="javascript:print();">Imprimir</a>
					<a class="btn btn-default" href="../index.php">Volver a Administración</a>
				</div>
			
			</div><!-- /.col-sm-12 -->
		
		</div><!-- /.row -->
	
	</div><!-- /.container -->
	
	</div>

	<?php include("../../pie.php");	?>
	
	<script>
	function espera() {
		document.getElementById("wrap").style.display = '';
		document.getElementById("status-loading").style.display = 'none';        
	}
	window.onload = espera;
	</script>
	
</body>
</html>