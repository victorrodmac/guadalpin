<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

include("../../menu.php");
?>



	<div class="container">
		
		<div class="page-header">
		  <h2>Administración <small>Importación de de Grupos-Tutorias si no se han introducido en el programa horw</small></h2>
		</div>
		
			
		<div id="status-loading" class="text-center">
			<span class="lead"><span class="fa fa-circle-o-notch fa-spin"></span> Cargando...</span>
		</div>
		
		
		<div id="wrap" class="row" style="display: none;">
		
			<div class="col-sm-12">
								
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Unidad</th>
							<th>Curso</th>
							<th>Tutor/a</th>
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
								while (!feof($file))
								{
									$linea++;
									$linealeida=fgets($file);
									$tr=explode("|",$linealeida);
									if ($linea=="9") {
										break;
									}
								}
								while(($row = fgetcsv($file, 1000, "|")) !== FALSE) {
									//echo $row[0]."fila".$linea;
									
									$unidad	= mysqli_real_escape_string($db_con, trim($row[0]));
									$tipo		= mysqli_real_escape_string($db_con, trim($row[1]));
									$capacidad	= mysqli_real_escape_string($db_con, trim($row[2]));
									$alumnos	= mysqli_real_escape_string($db_con, trim($row[3]));
									$tutorrow	= mysqli_real_escape_string($db_con, trim($row[4]));
									$sede	= mysqli_real_escape_string($db_con, trim($row[5]));
									$turno	= mysqli_real_escape_string($db_con, trim($row[6]));
									$curso	= mysqli_real_escape_string($db_con, trim($row[7]));

									$tutor0 = explode("(",$tutorrow);
									$tutor = trim( $tutor0[0]);
									$contar = 0;
									$sel = "select tutor from FTUTORES where unidad = '".$unidad."'";
									$ins = "insert INTO `FTUTORES` ( `unidad` , `tutor` , `observaciones1`,`observaciones2` ) VALUES ('$unidad', '$tutor','','')";
									$upd = "update `FTUTORES` set tutor = '".$tutor. "' where unidad = '".$unidad."'";
									$dep = "update departamentos set cargo = 2 where nombre = '".$tutor. "'";
									
									$existeraw = mysqli_query($db_con, $sel);
									while ($existe = mysqli_fetch_array($existeraw))
									{
										$contar ++;
									}
									//echo $contar;
									if ($contar == 0)
										mysqli_query($db_con, $ins );
									else
									{
										mysqli_query($db_con, $upd );
									}
									mysqli_query($db_con, $dep );

										
									echo "<tr>\n";
									echo "<td>".$unidad."</td>\n";
									echo "<td>".$curso."</td>\n";
									echo "<td>".$tutor."</td>\n";
									//echo "<td>".$nombre."__".$apellidos."</td>\n";
									echo "</tr>\n";										
										

									
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