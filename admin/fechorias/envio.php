<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); 

$GLOBALS['mod_sms_id'] = $config['mod_sms_id'];
$GLOBALS['mod_sms'] = $config['mod_sms'];
$GLOBALS['dominio'] = $config['dominio'];
$GLOBALS['centro_denominacion'] = $config['centro_denominacion'];
$GLOBALS['envioamonestacionpadreautomatico'] = $config['envioamonestacionpadreautomatico'];
$GLOBALS['noreply'] = $config['noreply'];
$GLOBALS['centro_codigo'] = $config['centro_codigo'];
$GLOBALS['centro_codpostal'] = $config['centro_codpostal'];
$GLOBALS['centro_direccion'] = $config['centro_direccion'];
$GLOBALS['centro_localidad'] = $config['centro_localidad'];
$GLOBALS['centro_provincia'] = $config['centro_provincia'];
$GLOBALS['centro_telefono'] = $config['centro_telefono'];
$GLOBALS['centro_fax'] = $config['centro_fax'];
$GLOBALS['centro_email'] = $config['centro_email'];
$GLOBALS['jefatura_email'] = $config['jefatura_email'];

function enviarsmsemail( $mensms, $tit, $mens, $db_con, $claveal, $nombre, $apellidos, $unidad, $tfno, $tfno_u, $correo, $informa, $jefatura, $id, $envioautomatico, $emailrespuesta)
{
	// si esta activo de forma automática o si jefatura el botón de envio
	if (($GLOBALS['envioamonestacionpadreautomatico'] == 1) || ($jefatura == 1))
	{

		// SMS
		if ($GLOBALS['mod_sms']) 
		{
			if (($envioautomatico == 1) || ($jefatura == 1)) 
			{
				$hora_f = date ( "G" );
				if ((substr ( $tfno, 0, 1 ) == "6" or substr ( $tfno, 0, 1 ) == "7" or substr ( $tfno_u, 0, 1 ) == "6" or substr ( $tfno_u, 0, 1 ) == "7") and $hora_f > '8' and $hora_f < '17') 
				{
					$sms_n = mysqli_query($db_con, "select max(id) from sms" );
					$n_sms = mysqli_fetch_array ( $sms_n );
					$extid = $n_sms [0] + 1;

					if (substr ( $tfno, 0, 1 ) == "6" or substr ( $tfno, 0, 1 ) == "6") {
						$mobile = $tfno;
					} else {
						$mobile = $tfno_u;
					}
					
					if(strlen($mobile) == 9) 
					{
				
						// ENVIO DE SMS
						include_once(INTRANET_DIRECTORY . '/lib/trendoo/sendsms.php');
						$sms = new Trendoo_SMS();
						$sms->sms_type = SMSTYPE_GOLD_PLUS;
						$sms->add_recipient('+34'.$mobile);
						$sms->message = $mensms;
						$sms->sender = $GLOBALS['mod_sms_id'];
						$sms->set_immediate();

						if ($sms->validate()){
							$sms_enviado=1;
						// Envío de SMS	
							$sms->send();
						// Registro de SMS		
							mysqli_query($db_con, "insert into sms (fecha,telefono,mensaje,profesor) values (now(),'$mobile','$mensms','$informa')");

							// Registro de Tutoría
							$fecha2 = date ( 'Y-m-d' );
							$accion = "Envío de SMS";
							mysqli_query($db_con, "insert into tutoria (apellidos, nombre, tutor,unidad,observaciones,causa,accion,fecha, claveal, enviado) values ('" . $apellidos . "','" . $nombre . "','" . $informa . "','" . $unidad ."','" . $mensms . "','" . $tit . "','" . $accion . "','" . $fecha2 . "','" . $claveal ."','".$id."')" );
						} 
					}
				}
				else {
					echo "
					<div class=\"alert alert-error\">
						<strong>Error:</strong> No se pudo enviar el SMS al teléfono (+34) ".$mobile.". Corrija la información de contacto del alumno/a en Séneca e importe los datos nuevamente.
					</div>
					<br>";
				}
			}
		}
		// FIN SMS
		// Envío de Email
		if( $correo == "")
		{
			$cor_control = mysqli_query($db_con,"select correo from control where claveal='".$claveal."'");
		 	$correo2 = mysqli_fetch_array($cor_control);
		 	$correo = $correo2[0];
		}
		if (strlen($correo)>0 and $sms_enviado<>1) 
		{
		 	 include_once(INTRANET_DIRECTORY."/lib/class.phpmailer.php");
		 	 $mail = new PHPMailer();
		 	 $mail->Host = "localhost";
		 	 $mail->From = $GLOBALS['noreply'];
		 	 $mail->FromName = $GLOBALS['centro_denominacion'];
		 	 $mail->AddReplyTo($GLOBALS['noreply'], $GLOBALS['noreply']);
		 	 $mail->Sender = $GLOBALS['noreply'];
		 	 $mail->IsHTML(true);
		 	 
		 	 $message = file_get_contents(INTRANET_DIRECTORY.'/lib/mail_template/index.htm');
		 	 $message = str_replace('{{dominio}}', $GLOBALS['dominio'], $message);
		 	 $message = str_replace('{{centro_denominacion}}', $GLOBALS['centro_denominacion'], $message);
		 	 $message = str_replace('{{centro_codigo}}', $GLOBALS['centro_codigo'], $message);
		 	 $message = str_replace('{{centro_direccion}}', $GLOBALS['centro_direccion'], $message);
		 	 $message = str_replace('{{centro_codpostal}}', $GLOBALS['centro_codpostal'], $message);
		 	 $message = str_replace('{{centro_localidad}}', $GLOBALS['centro_localidad'], $message);
		 	 $message = str_replace('{{centro_provincia}}', $GLOBALS['centro_provincia'], $message);
		 	 $message = str_replace('{{centro_telefono}}', $GLOBALS['centro_telefono'], $message);
		 	 $message = str_replace('{{centro_fax}}', $GLOBALS['centro_fax'], $message);
		 	 $message = str_replace('{{centro_email}}', $emailrespuesta, $message);
		 	 $message = str_replace('{{titulo}}', $tit, $message);
		 	 $message = str_replace('{{contenido}}', $mens, $message);
			 $mail->msgHTML($message);
			 $mail->Subject = $GLOBALS['centro_denominacion'].' - '.$tit;
			 $mail->AltBody = $mens;
		 	 $mail->AddAddress($correo, $nombre_alumno);
		 	if (($envioautomatico == 1) || ($jefatura == 1))
			{
				//$mail->Send();	
				// Registro de Tutoría
				if(!$mail->Send()) {
					$msg_class = "alert-danger";
					$msg = "Error: " . $mail->ErrorInfo;
				} 
				else{
					$msg_class = "alert-success";
					$msg = "El mensaje ha sido enviado.";
				}
				$fecha2 = date ( 'Y-m-d' );
				$accion = "Envío de Email";
				//echo "insert into tutoria (apellidos, nombre, tutor,unidad,observaciones,causa,accion,fecha, claveal, enviado) values ('" . $apellidos . "','" . $nombre_alum . "','" . $informa . "','" . $unidad ."','" . $asunto . "','" . $causa . "','" . $accion . "','" . $fecha2 . "','" . $claveal ."','".$id."')";
				if ($id == -1)
				{
					//cuando es envio masivo por reiteración de faltas leves.
					mysqli_query($db_con, "insert into tutoria (apellidos, nombre, tutor,unidad,observaciones,causa,accion,fecha, claveal) values ('" . $apellidos . "','" . $nombre . "','" . $informa . "','" . $unidad ."','" . $mens . "','" . $tit . "','" . $accion . "','" . $fecha2 . "','" . $claveal ."')" );
				}
				else
				{
					mysqli_query($db_con, "insert into tutoria (apellidos, nombre, tutor,unidad,observaciones,causa,accion,fecha, claveal, enviado) values ('" . $apellidos . "','" . $nombre . "','" . $informa . "','" . $unidad ."','" . $mens . "','" . $tit . "','" . $accion . "','" . $fecha2 . "','" . $claveal ."','".$id."')" );					
				}
			}	
			//echo "llega";
			//echo $msg;

		}
 	// Fin Correo
	
 	
	}
}

?>

