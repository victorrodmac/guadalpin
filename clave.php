<?php
require('bootstrap.php');


function validarContrasena($contrasena) {
	$result = preg_match("((?=.*\d)(?=.*[a-z])(?=.*[A-z])(?=.*[!\"#$%&'()*+,-./:;<=>?@[\]^_`{|}~]).{8,20})", $contrasena);
	
	return $result;
}

function validarCorreo($correo) {
	$result = filter_var($correo, FILTER_VALIDATE_EMAIL);
	
	return $result;
}

// VALIDAMOS EL FORMULARIO
if (isset($_POST['submit'])) {

	$usuario = $_POST['usuario'];
	$codigo2 = $_POST['codigo2'];
	$codigo3 = $_POST['codigo3'];
	$correo  = $_POST['correo'];
	$movil  = $_POST['movil'];
	
	$codigo2_has_error = 0;
	$codigo3_has_error = 0;
	$correo_has_error = 0;
	
	// Comprobamos que los campos no est�n vac�os
	if(empty($correo) || empty($codigo2) || empty($codigo3)) {
		$msg_error = "Todos los campos del formulario son obligatorios.";
		$codigo2_has_error = 1;
		$codigo3_has_error = 1;
		$correo_has_error = 1;
	}
	else {
		// Comprobamos si se cumple los requisitos de seguridad de la contrase�a
		if (! validarContrasena($codigo2)) {	
			$msg_error = "La contrase�a no cumple con los requisitos de seguridad.";
			$codigo2_has_error = 1;
		}
		else {
			// Comprobamos que las contrase�as coinciden
			if($codigo2 !== $codigo3) {
				$msg_error = "Las contrase�as no coinciden.";
				$codigo2_has_error = 1;
				$codigo3_has_error = 1;
			}
			else {
				// Comprobamos que la direcci�n de correo electr�nico sea v�lida
				if(! validarCorreo($correo)) {
					$msg_error = "La direcci�n de correo electr�nico no es v�lida.";
					$correo_has_error = 1;
				}
				elseif (! is_numeric($movil) || strlen($movil) != 9 || substr($movil, 0, 1) != '6' && substr($movil, 0, 1) != '7') {	
					$msg_error = "El n�mero de tel�fono m�vil no es v�lido.";
					$movil_has_error = 1;
				}
				else {
					// Obtenemos el hash de la contrase�a
					$hash = sha1($codigo2);
					
					$result = mysqli_query($db_con, "UPDATE c_profes SET pass='$hash', correo='$correo', telefono='$movil' WHERE profesor='".$_SESSION['profi']."'");
					
					// Comprobamos si se ha relizado la consulta a la base de datos
					if(!$result) {
						$msg_error = "No se ha podido cambiar la contrase�a, as� que mejor te pongas en contacto con quien pueda arreglar el asunto.";
					}
					else {
						$_SESSION['cambiar_clave'] = 0;
						
						if (isset($_GET['tour']) && $_GET['tour']) {
							// Redirigimos a la p�gina de fotograf�a
							header("Location:"."admin/fotos/fotos_profes.php?tour=1");
						}
						else {
							// Redirigimos a la p�gina principal
							header("Location:"."index.php");
						}
					}
				}
			}
		}
	}
}

$result = mysqli_query($db_con, "SELECT idea, correo, telefono FROM c_profes WHERE idea='".$_SESSION['ide']."' LIMIT 1");
$row = mysqli_fetch_assoc($result);

include("menu.php");
?>

	<div class="container">
		
		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<h2><?php echo (isset($_GET['tour']) && $_GET['tour']) ? 'Lo primero es lo primero. Cambie la contrase�a y a�ada su correo electr�nico.' : 'Cambiar la contrase�a'; ?></h2>
		</div>
		
		<!-- MENSAJES -->
		<?php if(isset($msg_error)): ?>
		<div class="alert alert-danger" role="alert">
			<?php echo $msg_error; ?>
		</div>
		<?php endif; ?>
		
		<div class="row">
			
			<div class="col-sm-6">
				
				<div class="well">
					
					<form class="form-horizontal" method="post" action="">
						<fieldset>
							
							<div class="form-group">
						    <label for="usuario" class="col-sm-4 control-label">Usuario IdEA</label>
						    <div class="col-sm-8">
						      <input type="text" class="form-control" id="usuario" name="usuario" value="<?php echo $row['idea']; ?>" readonly>
						    </div>
						  </div>
						  
						  <div id="form-group-codigo2"  class="form-group">
						    <label for="codigo2" class="col-sm-4 control-label">Nueva contrase�a</label>
						    <div class="col-sm-8">
						      <input type="password" class="form-control" id="codigo2" name="codigo2" placeholder="Nueva contrase�a" maxlength="20">
						    </div>
						  </div>
						  
						  <div id="form-group-codigo3"  class="form-group">
						    <label for="codigo3" class="col-sm-4 control-label">Repita la contrase�a</label>
						    <div class="col-sm-8">
						      <input type="password" class="form-control" id="codigo3" name="codigo3" placeholder="Repita la contrase�a" maxlength="20">
						    </div>
						  </div>
						  
						  <div id="form-group-email" class="form-group">
						    <label for="correo" class="col-sm-4 control-label">Correo electr�nico</label>
						    <div class="col-sm-8">
						      <input type="email" class="form-control" id="correo" name="correo" placeholder="Correo electr�nico" value="<?php echo $row['correo'];?>" maxlength="64">
						    </div>
						  </div>

						   <div id="form-group-movil" class="form-group">
						    <label for="movil" class="col-sm-4 control-label">Tel�fono m�vil</label>
						    <div class="col-sm-8">
						      <input type="text" class="form-control" id="movil" name="movil" placeholder="N�mero de m�vil" value="<?php echo $row['telefono'];?>" maxlength="9">
						    </div>
						  </div>
							
							
							<div class="form-group">
							  <div class="col-sm-8 col-sm-offset-4">
							    <button type="submit" class="btn btn-primary" name="submit">Cambiar la contrase�a</button>
							  </div>
							</div>
						</fieldset>
					</form>
					
				</div><!-- /.well -->
				
			</div><!-- /.col-sm-6 -->
			
			
			<div class="col-sm-6">
				
				<h3>Utilice una contrase�a segura</h3>
				
				<p>A partir de este momento tendr�s que usar tu nombre de usuario IdEA y esta clave para acceder a la intranet. La direcci�n de correo electr�nico se usar� para las notificaciones y para reiniciar la contrase�a en caso de olvido.</p>
				
				<p>La clave debe cumplir las siguientes condiciones:</p>
				
				<ul class="">
					<li>Tener al menos una longitud de 8 caracteres y 20 como m�ximo.</li>
					<li>Contener al menos una letra, un n�mero y un signo de puntuaci�n o un s�mbolo.</li>
					<li>Los s�mbolos aceptados son !"#$%&amp;'()*+,-./:;&raquo;=>?@[\]^_`{|}~</li>
					<li>Las letras acentuadas y las e�es no est�n admitidas.</li>
					<li>No ser similar al nombre de usuario.</li>
					<li>No ser similar a su D.N.I. o pasaporte.</li>
				</ul>
				
			</div>
			
		</div><!-- /.row -->
		
	</div><!-- /.container -->

<?php include("pie.php"); ?>

<?php if($codigo2_has_error): ?>
    <script>$("#form-group-codigo2").addClass( "has-error" );</script>
<?php endif; ?>
<?php if($codigo3_has_error): ?>
    <script>$("#form-group-codigo3").addClass( "has-error" );</script>
<?php endif; ?>
<?php if($correo_has_error): ?>
    <script>$("#form-group-email").addClass( "has-error" );</script>
<?php endif; ?>
<?php if($movil_has_error): ?>
    <script>$("#form-group-movil").addClass( "has-error" );</script>
<?php endif; ?>

</body>
</html>
