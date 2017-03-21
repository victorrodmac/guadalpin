<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); 

if (strlen($id)>0 and $grave == "muy grave" and stristr($_SESSION['cargo'],'1') == TRUE) {
	$confirm = mysqli_query($db_con,"select confirmado from Fechoria where confirmado = '1' and id = '$id'");
	if (mysqli_num_rows($confirm)>0) {
		$confirma_db = '1';
	}
}

// Control de errores
if (! $notas or ! $grave or ! $_POST['nombre'] or ! $asunto or ! $fecha or ! $hora or ! $informa or $fecha=='0000-00-00') {
	echo '<div align="center"><div class="alert alert-danger alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<legend>ATENCI&Oacute;N:</legend>
            No has introducido datos en alguno de los campos, y <strong>todos son obligatorios</strong>.<br> Vuelve atr&aacute;s, rellena los campos vac&iacute;os e int&eacute;ntalo de nuevo.
          </div></div>';
}
elseif (strlen ($notas) < '10' ) {
	echo '<div align="center"><div class="alert alert-danger alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<legend>ATENCI&Oacute;N:</legend>
            La descripci&oacute;n de lo sucedido es demasiado breve. Es necesario que proporciones m&aacute;s detalles de lo ocurrido para que Jefatura de Estudios y Tutor puedan hacerse una idea precisa del suceso.<br />Vuelve atr&aacute;s e int&eacute;ntalo de nuevo.
          </div></div>';
}
elseif (isset($_POST['nombre'])) 
{	

	if (is_array($nombre)) {
		$num_a = count($_POST['nombre']);
	}
	else{
		$num_a=1;
	}

	// Actualizar datos
	if ($_POST['submit2']) {
	}
	else{
		$dia0 = explode ( "-", $fecha );
		$fecha3 = "$dia0[2]-$dia0[1]-$dia0[0]";
	}

	$z=0;
	for ($i=0;$i<$num_a;$i++)
	{
	
		if ($num_a==1 and !is_array($nombre)) {
			$claveal = $nombre;
		}
		else{
			$claveal = $nombre[$i];
		}

		$sms_enviado="";
		if ($confirmado=="") {
			$confirmado="0";
		}
		$notas = trim($notas);
		$ya_esta = mysqli_query($db_con, "select claveal, fecha, grave, asunto, notas, informa, confirmado from Fechoria where claveal = '$claveal' and fecha = '$fecha3' and grave = '$grave' and asunto = '$asunto' and informa = '$informa' and notas = '$notas' and confirmado='$confirmado'");
		
		if (mysqli_num_rows($ya_esta)>0) {
			echo '<br /><div align="center"><div class="alert alert-warning alert-block fade in">
	            <button type="button" class="close" data-dismiss="alert">&times;</button>
	            <legend>Atenci&oacute;n:</legend>
	            Ya hay un problema de convivencia registrado que contiene los mismos datos que est&aacute;s enviando, y no queremos repetirlos... .
	          </div></div><br />';
		}	
		else{


			$dia = explode ( "-", $fecha );
			$fecha2 = "$dia[2]-$dia[1]-$dia[0]";

			if ($_POST['submit2'] and !($grave == "muy grave"  and $_POST['confirmado']=="1" and $confirma_db <>1 and isset($id))) 
			{
				$upd = "update Fechoria set claveal='$nombre', asunto = '$asunto', notas = '$notas', grave = '$grave', medida = '$medida', expulsionaula = '$expulsionaula', informa='$informa', hora= '$hora' where id = '$id'";
				//echo $upd;
				mysqli_query($db_con, $upd);
				echo '<br /><div align="center"><div class="alert alert-success alert-block fade in">
		            <button type="button" class="close" data-dismiss="alert">&times;</button>
		            Los datos se han actualizado correctamente.
		          </div></div><br />';
			}
			elseif ($grave == "muy grave"  and $_POST['confirmado']=="1" and $confirma_db <>1 and isset($id)) 
			{
				$upd2 = "update Fechoria set claveal='$nombre', fecha='$fecha', asunto = '$asunto', notas = '$notas', grave = '$grave', medida = '$medida', expulsionaula = '$expulsionaula', informa='$informa', confirmado='1', hora= '$hora' where id = '$id'";
				//echo $upd2;	
				mysqli_query($db_con, $upd2);
				echo '<br /><div align="center"><div class="alert alert-success alert-block fade in">
		            <button type="button" class="close" data-dismiss="alert">&times;</button>
		            Los datos se han actualizado correctamente.
		          </div></div><br />';
			}
			else{
				$query = "insert into Fechoria (CLAVEAL,FECHA,ASUNTO,NOTAS,INFORMA,grave,medida,expulsionaula,confirmado,hora) values ('" . $claveal . "','" . $fecha2 . "','" . $asunto . "','" . $notas . "','" . $informa . "','" . $grave . "','" . $medida . "','" . $expulsionaula . "','" . $confirmado . "','" . $hora ."')";
				//echo $query."<br>";
				$inserta = mysqli_query($db_con, $query );
				if ($inserta) {
			 		$z++;
			 	}
			}
			if ( ! isset($id) )
			{
				$sql = "select id from Fechoria where CLAVEAL = '".$claveal."' and FECHA = '".$fecha2."' and ASUNTO = '".$asunto."' and hora = '".$hora."'";
				//echo $sql;
				
				$selid = mysqli_query($db_con, $sql);
				while ($selraw = mysqli_fetch_assoc($selid))				
				{
					$id = $selraw['id'];
				}				
			}
		 	if (($grave == "muy grave" and $_POST['confirmado'] !="1") or $confirma_db=='1' or $jefatura == 0) 
		 	{	
			}
			else
			{
				$alumno = mysqli_query($db_con, " SELECT distinct alma.APELLIDOS, alma.NOMBRE, alma.unidad, alma.TELEFONO, alma.TELEFONOURGENCIA, alma.CORREO FROM alma WHERE alma.claveal = '$claveal'" );
				$rowa = mysqli_fetch_array ( $alumno );
				$apellidos = trim ( $rowa [0] );
				$nombre = trim ( $rowa [1] );
				$unidad = trim ( $rowa [2] );
				$tfno = trim ( $rowa [3] );
				$tfno_u = trim ( $rowa [4] );
				$correo = trim ( $rowa [5] );

		      	$mens = 'Jefatura de Estudios le comunica que, con fecha '.$fecha.', su hijo/a '.$nombre.' '.$apellidos.' ha cometido una falta '.$grave.' contra las normas de convivencia del Centro. El tipo de falta es el siguiente: '.$asunto.'.<br>Le recordamos que puede conseguir información más detallada contactando con la Jefatura de Estudios del Centro.<br><br><hr>Este correo es informativo. Por favor, no responder a esta dirección de correo. Si necesita mayor información sobre el contenido de este mensaje, póngase en contacto con Jefatura de Estudios.';
		      	$mensms = "Su hijo/a $nombre $apellidos alumno del grupo $unidad ha cometido una falta contra las normas de convivencia del Centro. Hable con su hijo/a y, ante cualquier duda, consulte en http://".$config['dominio'];
				enviarsmsemail( $mensms, "Problemas de convivencia", $mens, $db_con, $claveal, $nombre, $apellidos, $unidad, $tfno, $tfno_u, $correo,  $informa, 0, $id, 1, $config['jefatura_email']);
			}

			
		}

	}

	unset ($unidad);
	unset($nombre);
	unset ($id);
	unset ($claveal);
	if ($z>0 and !($_POST['confirmado']=="1" and $confirma_db <>1)) {
	echo '<br /><div align="center"><div class="alert alert-success alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            Se han registrado correctamente los Problemas de Convivencia de '.$z.' alumnos.
          </div></div><br />';
	}

}
?>

