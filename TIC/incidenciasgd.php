<?php
require('../bootstrap.php');

if (file_exists('config.php')) {
	include('config.php');
}

// ELIMINAR INCIDENCIA
if (isset($_GET['parte']) && isset($_GET['borrar']) && $_GET['borrar'] == 1) {
	$result = mysqli_query($db_con, "DELETE FROM partesticgd WHERE parte=".$_GET['parte']." LIMIT 1");
	
	if(!$result) $msg_error = "No se ha podido eliminar la incidencia. Error: ".mysqli_error($db_con);
	else $msg_success = "La incidencia ha sido eliminada.";
}

$PLUGIN_DATATABLES = 1;

include("../menu.php");
include("menu.php");
?>

	<div class="container">
		
		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<h2>Centro TIC <small>Lista de incidencias</small></h2>
		</div>
		
		
		<!-- MENSAJES -->
		<?php if(isset($msg_success) && $msg_success): ?>
		<div class="alert alert-success" role="alert">
			<?php echo $msg_success; ?>
		</div>
		<?php endif; ?>
		
		<?php if(isset($msg_error) && $msg_error): ?>
		<div class="alert alert-danger" role="alert">
			<?php echo $msg_error; ?>
		</div>
		<?php endif; ?>
		
		
		<!-- SCAFFOLDING -->
		<div class="row">
		
			<!-- COLUMNA CENTRAL -->
			<div class="col-sm-12">
				<?php 
				//echo "SELECT parte, unidad, nincidencia, a_aula, nserie, fecha, hora, profesor, descripcion, estado FROM partesticgd WHERE fecha >= '".$config['curso_inicio']."' ORDER BY parte DESC";
				$result = mysqli_query($db_con, "SELECT parte, unidad, nincidencia, a_aula, nserie, fecha, hora, profesor, descripcion, estado, solucion, fecsol FROM partesticgd WHERE fecha >= '".$config['curso_inicio']."' ORDER BY parte DESC"); 
				?>
				
				<?php if (mysqli_num_rows($result)): ?>
				<table class="table table-bordered table-striped table-hover datatable">
					<thead>
						<tr>
							<th>Fecha</th>
							<th>Grp</th>
							<th>Aula</th>
							<th>Ord</th>
							<th>Descripción</th>
							<th>Inc</th>
							<th>Profesor/a</th>
							<th>Estado</th>
							<th>Solución</th>
							<th>FecSol</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php while ($row = mysqli_fetch_array($result))
						{ 
							$visible = 1;
							if ( $row['unidad'] == '' )
							{
								if(stristr($_SESSION['cargo'],'1') == TRUE || strlen(stristr($_SESSION['cargo'],'g')) > 0 || ($row['profesor'] == $pr && $row['estado'] == 'activo'))
								{}
								else
								{
									$visible = 0;
								}
							}
							if ($visible == 1)
							{	
								if ($row['estado'] == 'activo' || $row['estado'] == 'Activo')
								{
									if ($row['solucion'] == '' )
									{
										$act = 1;
										echo '<tr>';
									}
									else
									{
										$act = 2;
										echo '<tr class="warning">';
									}
								}
								else
								{
									$act = 0;
									echo '<tr class="success">';
								}
							?>
								<td nowrap><?php echo $row['fecha']; ?></td>
								<td><?php echo $row['unidad']; ?></td>
								<td><?php echo $row['a_aula']; ?></td>
								<td><?php echo $row['nserie']; ?></td>
								<td><?php echo $row['descripcion']; ?></td>
								<td><?php echo $row['nincidencia']; ?></td>
								<td><?php echo $row['profesor']; ?></td>
								<td>
									<?php 
										if ($act == 1)
										{
											echo '<span class="sr-only">Pendiente</span><span class="fa fa-exclamation-triangle fa-fw fa-lg" data-bs="tooltip" title="Pendiente"></span></td><td>pendiente de solución';
										}
										else
										{
											if ($act == 2)
											{
												echo '<span class="sr-only">Pendiente</span><span class="fa fa-exclamation-triangle fa-fw fa-lg" data-bs="tooltip" title="Pendiente"></span></td><td>'.$row['solucion'];
											}
											else
											{
												echo '<span class="sr-only">Solucionado</span><span class="fa fa-check-circle fa-fw fa-lg" data-bs="tooltip" title="Solucionado"></span></td><td>'.$row['solucion'];
											}
										}
									?>
								</td>
								<td><?php echo $row['fecsol']; ?></td>
								<td nowrap>
									<?php 
										//una vez solucionado sólo lo puede borrar administración o TIC
										if(stristr($_SESSION['cargo'],'1') == TRUE || strlen(stristr($_SESSION['cargo'],'g')) > 0 || ($row['profesor'] == $pr && $row['estado'] == 'activo')):  ?>
											<a href="indexgd.php?id=<?php echo $row['parte']; ?>"><span class="fa fa-edit fa-fw fa-lg" data-bs="tooltip" title="Editar"></span></a>
											<a href="incidenciasgd.php?parte=<?php echo $row['parte']; ?>&borrar=1" data-bb="confirm-delete"><span class="fa fa-trash-o fa-fw fa-lg" data-bs="tooltip" title="Eliminar"></span></a>
									<?php endif; ?>
								</td>
							</tr>
						<?php }
						} ?>
						<?php mysqli_free_result($result); ?>
					</tbody>
				</table>
				
				<div class="hidden-print">
					<a class="btn btn-primary" href="#" onclick="javascript:print();">Imprimir</a>
					<?php if(stristr($_SESSION['cargo'],'1') == TRUE || strlen(stristr($_SESSION['cargo'],'g')) > 0): ?>
					<div class="btn-group">
					  <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					    Imprimir PDF <span class="caret"></span>
					  </button>
					  <ul class="dropdown-menu">
					     <li><a href="incidenciasgd_pdf.php?order=fecha" target="_blank">Ordenado por fecha</a></li>
					     <li><a href="incidenciasgd_pdf.php?order=estado" target="_blank">Ordenado por pendientes</a></li>
					  </ul>
					</div>
					<?php endif; ?>
					<a href="index.php" class="btn btn-default">Volver</a>
				</div>
				
				
				<?php else: ?>
				
				<h3>No se ha registrado ninguna incidencia</h3>
				
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				
				
				<?php endif; ?>
				
								
			</div><!-- /.col-sm-12 -->
			
		
		</div><!-- /.row -->
		
	</div><!-- /.container -->
  
<?php include("../pie.php"); ?>

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
  		                  "next": "Última",
  		                  "next": "",
  		                  "previous": ""
  		                }
  		        }
  	});
});
</script>

</body>
</html>
