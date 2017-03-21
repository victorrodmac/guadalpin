<?php
require('../../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

function limpiar_string($string)
{
	return trim(htmlspecialchars($string, ENT_QUOTES,'ISO-8859-1'));
}

if (isset($_POST['btnGuardar'])) {
	
	$prefLimitePedidos		= limpiar_string($_POST['prefLimitePedidos']);
	$prefLimiteArticulos	= limpiar_string($_POST['prefLimiteArticulos']);
	$prefFechaLimite		= limpiar_string($_POST['prefFechaLimite']);
	$prefCondiciones		= limpiar_string($_POST['prefCondiciones']);

	// CREACI�N DEL ARCHIVO DE CONFIGURACI�N
	if($file = fopen('config.php', 'w+'))
	{
		fwrite($file, "<?php \r\n");
		
		fwrite($file, "\r\n// CONFIGURACI�N M�DULO DE PEDIDOS\r\n");
		fwrite($file, "\$config['pedidos']['limite_pedidos']\t= $prefLimitePedidos;\r\n");
		fwrite($file, "\$config['pedidos']['limite_articulos']\t= $prefLimiteArticulos;\r\n");
		fwrite($file, "\$config['pedidos']['condiciones']\t\t= '$prefCondiciones';\r\n");
		
		fwrite($file, "\r\n\r\n// Fin del archivo de configuraci�n");
		
		fclose($file);
		
		$msg_success = "Las preferencias han sido guardadas correctamente.";
	}
	
}

if (file_exists('config.php')) {
	include('config.php');
}


include("../../../menu.php");
include("menu.php");
?>

<div class="container">

	<div class="page-header">
		<h2>Pedido de material <small>Preferencias</small></h2>
	</div>
	
	<!-- MENSAJES -->
	<?php if (isset($msg_error)): ?>
	<div class="alert alert-danger alert-fadeout">
		<?php echo $msg_error; ?>
	</div>
	<?php endif; ?>
	
	<?php if (isset($msg_success)): ?>
	<div class="alert alert-success alert-fadeout">
		<?php echo $msg_success; ?>
	</div>
	<?php endif; ?>


	<div class="row">

		<div class="col-sm-12">
			
			<form class="form-horizontal" method="post" action="preferencias.php">
				
				<div class="well">
					
					<fieldset>
						<legend>Preferencias</legend>
						
						<div class="form-group">
							<label for="prefLimitePedidos" class="col-sm-4 control-label">L�mite de pedidos por departamento</label>
							<div class="col-sm-3">
								<input type="text" class="form-control" id="prefLimitePedidos" name="prefLimitePedidos" value="<?php echo (isset($config['pedidos']['limite_pedidos'])) ? $config['pedidos']['limite_pedidos'] : 0; ?>">
							</div>
							<p class="col-sm-5 form-control-static help-block">(0 = Sin l�mite)</p>
						</div>
						
						<div class="form-group">
							<label for="prefLimiteArticulos" class="col-sm-4 control-label">L�mite de art�culos por pedido</label>
							<div class="col-sm-3">
								<input type="text" class="form-control" id="prefLimiteArticulos" name="prefLimiteArticulos" value="<?php echo (isset($config['pedidos']['limite_articulos'])) ? $config['pedidos']['limite_articulos'] : 15; ?>">
							</div>
							<p class="col-sm-5 form-control-static help-block">(0 = Sin l�mite)</p>
						</div>
						
						<div class="form-group">
							<label for="prefCondiciones" class="col-sm-4 control-label">Condiciones de compra</label>
							<div class="col-sm-8">
								<textarea class="form-control" id="prefCondiciones" name="prefCondiciones" rows="10">
								<?php echo (isset($config['pedidos']['condiciones'])) ? $config['pedidos']['condiciones'] : '<p>El presupuesto del centro determinar� la asignaci�n de partidas espec�ficas a cada Departamento en funci�n del n�mero de miembros del Departamento y de necesidades fundamentadas y para ello, cada Departamento entregar� en Secretar�a un proyecto escrito de presupuesto de funcionamiento anual.</p>
								
								<p>En �l deber�n detallarse las partidas que componen el gasto desglosado por conceptos (material general, fungible, bibliograf�a, actividades, repograf�a, etc.) y valorarlas con la mayor exactitud posible (I.V.A. incluido).</p>
								
								<p>Para la adquisici�n de material se sigue el siguiente protocolo:</p>
								
								<ol>
									<li>El primer paso es recoger en acta el acuerdo del Departamento</li>
									<li>Todo gasto deber� contar previamente con la conformidad del Secretario/a y del Director/a mediante petici�n por escrito</li>
									<li>Se solicitar� en el momento de la compra una factura con todos los requisitos legales oportunos:
										<ul>
											<li>Factura a nombre del '.$config['centro_denominacion'].'</li>
											<li>NIF de la Consejer�a de Educaci�n de la Junta de Andaluc�a: S4111001F</li>
											<li>CIF o NIF del proveedor</li>
											<li>Nombre del proveedor</li>
											<li>Fecha y n�mero de la factura</li>
											<li>Firma y sello de la empresa proveedora</li>
										</ul>
									</li>
									<li>El original del albar�n o factura se entregar� al Secretario/a, quedando una copia en poder del Jefe de Departamento correspondiente</li>
									<li>El Secretario/a s�lo entregar� a los Jefes de Departamento el valor nominal de la compra efectuada</li>
								</ol>'; ?>
								</textarea>
							</div>
						</div>
						
					</fieldset>
					
				</div>
				
				<button type="submit" class="btn btn-primary" name="btnGuardar">Guardar cambios</button>
				<?php if (isset($_GET['esAdmin']) && $_GET['esAdmin'] == 1): ?>
				<a href="../../../xml/index.php" class="btn btn-default">Volver</a>
				<?php else: ?>
				<a href="index.php" class="btn btn-default">Volver</a>
				<?php endif; ?>
			
			</form>
		
		</table>

		</div>

	</div>

</div>

<?php include("../../../pie.php"); ?>

<script>
$(document).ready(function() {
	$('#prefCondiciones').summernote({
		height: 260,
		lang: 'es-ES'
	});
});
</script>

</body>
</html>
