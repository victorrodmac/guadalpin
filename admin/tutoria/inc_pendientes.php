<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed');

// Control de faltas leves reiteradas
include("../fechorias/envio.php");
//if(! (isset($config['tutoria']['amonestacion_reiteracion']) && $config['tutoria']['amonestacion_reiteracion'] == 1) ) {
if(! (isset($config['envioreiteracionfaltas']) && $config['envioreiteracionfaltas'] == 1) ) 
{
	$rep0 = mysqli_query($db_con, "select id, Fechoria.claveal, count(*) as numero from Fechoria, alma where Fechoria.claveal = alma.claveal and unidad = '".$_SESSION['mod_tutoria']['unidad']."' and grave = 'Leve' and medida not like 'Sancionada' group by Fechoria.claveal having numero >= ".$config["nfaltas"]);
	while ($rep = mysqli_fetch_array($rep0)) {
		
		$count_fech=1;		
		$claveal = $rep[1];	
		$alumno = mysqli_query($db_con, "SELECT distinct alma.APELLIDOS, alma.NOMBRE, alma.unidad, alma.nc, alma.CLAVEAL, alma.TELEFONO, alma.TELEFONOURGENCIA, alma.CORREO FROM alma WHERE alma.claveal = '$claveal'" );
			
		$rowa = mysqli_fetch_array ( $alumno );
		$asunto = "Reiteración en el mismo trimestre de cinco o más faltas leves";
		$medida = "Amonestación escrita";
		$apellidos = trim ( $rowa [0] );
		$nombre = trim ( $rowa [1] );
		$unidad = trim ( $rowa [2] );
		$claveal = trim ( $rowa [4] );
		$tfno = trim ( $rowa [5] );
		$tfno_u = trim ( $rowa [6] );
		$correo = trim ( $rowa [7] );
		$informa = $_SESSION ['profi'];

		$mens = 'Jefatura de Estudios le comunica que, con fecha '.strftime("%e de %B de %Y", strtotime($fecha)).', su hijo/a '.$nombre.' '.$apellidos.' ha sido amonestado/a por Reiteración en el mismo trimestre de '.$config["nfaltas"].' o más faltas leves <br>Asimismo, le comunico que, según contempla el Plan de Convivencia del Centro, regulado por el Decreto 327/2010 de 13 de Julio por el que se aprueba el Reglamento Orgánico de los Institutos de Educación Secundaria, de reincidir su hijo/a en este tipo de conductas contrarias a las normas de convivencia del Centro podría imponérsele otra medida de corrección que podría llegar a ser la suspensión del derecho de asistencia al Centro.<br>Le recordamos que puede conseguir información más detallada contactando con la Jefatura de Estudios del Centro.<br><br><hr>Este correo es informativo. Por favor, no responder a esta dirección de correo. Si necesita mayor información sobre el contenido de este mensaje, póngase en contacto con Jefatura de Estudios.';
		$mensms = "Pongo en su conocimiento que con fecha ".strftime("%e de %B de %Y", strtotime($fecha))." su hijo/a $nombre $apellidos alumno del grupo $unidad ha sido amonestado/a por Reiteración en el mismo trimestre de ".$config['nfaltas']." o más faltas leves";
		enviarsmsemail( $mensms, "Comunicación de amonestación escrita", $mens, $db_con, $claveal, $nombre, $apellidos, $unidad, $tfno, $tfno_u, $correo, $informa, 0, -1, 1, $config['jefatura_email']);	
			
	
		$query = "insert into Fechoria (CLAVEAL,FECHA,ASUNTO,NOTAS,INFORMA,grave,medida,expulsionaula) values ('" . $claveal . "','" . $fecha2 . "','" . $asunto . "','" . $notas . "','" . $informa . "','grave','" . $medida . "','0')";
		mysqli_query($db_con, $query );	
		
		// Actualizamos la Fechoría para amortizarla
		$rep1 = mysqli_query($db_con, "select id from Fechoria where claveal = '$claveal' and grave = 'Leve' and medida not like 'Sancionada'");
		while ($rep11 = mysqli_fetch_array($rep1)) {
			mysqli_query($db_con, "update Fechoria set medida = 'Sancionada' where id = '$rep11[0]'");
		}	
	}
}

// Problemas varios de convivencia

$result1 = mysqli_query($db_con, "select distinct id, recibido, Fechoria.claveal, expulsionaula, expulsion, inicio, aula_conv, inicio_aula, fin_aula, Fechoria.fecha, Fechoria.medida from Fechoria, alma where Fechoria.claveal = alma.claveal and unidad = '".$_SESSION['mod_tutoria']['unidad']."' and medida = 'Amonestación escrita'");
if(mysqli_num_rows($result1)>0)
{

while($row1 = mysqli_fetch_array($result1)) {
$id=$row1[0];
$recibido=$row1[1];
$claveal=$row1[2];
$expulsionaula=$row1[3];
$expulsion=$row1[4];
$inicio=$row1[5];
$aula=$row1[6];
$fechareg=$row1[9];
$inicioaula=$row1[7];
$finaula=$row1[8];
$medida=$row1[10];

// El Tutor no ha recibido el mensaje.
$hoy = date('Y')."-".date('m')."-".date('d');
$alumno1 = mysqli_query($db_con, "select nombre, apellidos from alma where claveal = '$claveal'");
$alumno0 = mysqli_fetch_array($alumno1);
$alumno = $alumno0[1].", ".$alumno0[0];

// Expulsión al Aula de Convivencia
if($aula > 0 and strtotime($fechareg) <= strtotime($hoy) and strtotime($inicioaula) >= strtotime($hoy)){
	$count_fech=1;
	?>

<div class="alert alert-warning">
	<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	<h4><?php echo $alumno; ?> ha sido expulsado al Aula de convivencia</h4>
	<p>El siguiente alumno ha sido expulsado al Aula de convivencia entre los días <strong><?php echo $inicioaula; ?></strong> y <strong><?php echo $finaula; ?></strong>. Ponte en contacto con Jefatura de estudios si necesitas detalles.</p>
	
	<br>

	<a class="btn btn-primary btn-sm" href="//<?php echo $config['dominio']; ?>/<?php echo $config['path']; ?>/admin/fechorias/detfechorias.php?claveal=<?php echo $claveal; ?>&id=<?php echo $id; ?>">Ver detalles</a>
</div>

<?php 
}

// Expulsión del Centro
if($expulsion > 0 and $fechareg <= $hoy and $inicio >= $hoy) {
	$count_fech=1;
 	?>
    <?php
$inicio= explode("-",$row1[5]);
$fechainicio = $inicio[2] . "-" . $inicio[1] . "-" . $inicio[0];
?> 

<div class="alert alert-danger">
	<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	<h4><?php echo $alumno; ?> ha sido expulsado del centro</h4>
	<p>El alumno/a ha sido expulsado del centro. Ponte en contacto con Jefatura de estudios si necesitas detalles.</p>
	
	<br>

	<a class="btn btn-primary btn-sm" href="//<?php echo $config['dominio']; ?>/<?php echo $config['path']; ?>/admin/fechorias/detfechorias.php?claveal=<?php echo $claveal; ?>&id=<?php echo $id; ?>">Ver detalles</a>
</div>

<?php 
}
if($recibido == 0)
{ 
if($expulsionaula == 1 and $expulsion == "0")
{
$count_fech=1;
?> 

<div class="alert alert-warning">
	<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	
	<h4><?php echo $alumno; ?> ha sido expulsado del aula</h4>
	
	<?php if (isset($config['tutoria']['impresion_expulsion_aula']) && $config['tutoria']['impresion_expulsion_aula'] == 1): ?>
	
	<p>Jefatura de estudios se encargará de imprimir el comunicado de expulsión del aula para los padres.</p>
	<?php mysqli_query($db_con, "UPDATE Fechoria SET recibido='1' WHERE Fechoria.id='$id'"); ?>
	
	<?php else: ?>
	
	<p>El alumno/a ha sido expulsado del aula y está pendiente de la amonestación escrita del tutor.</p>
	
	<br>

	<form method="post" action="//<?php echo $config['dominio']; ?>/<?php echo $config['path']; ?>/admin/fechorias/imprimir/expulsionaula.php">
		<input type="hidden" name="id" value="<?php echo $id; ?>">
		<a class="btn btn-primary btn-sm" href="//<?php echo $config['dominio']; ?>/<?php echo $config['path']; ?>/admin/fechorias/detfechorias.php?claveal=<?php echo $claveal; ?>&id=<?php echo $id; ?>">Ver detalles</a>
		<button type="submit" class="btn btn-primary btn-sm" name="amonestacion">Imprimir parte de expulsión</button>
	</form>
	<?php endif; ?>
</div>

<?php 
} 
elseif($expulsionaula == 0 and $expulsion == "0"  and $medida == "Amonestación escrita") 
{
	$count_fech=1;
//Amonestación Escrita	
	?>
	
	<div class="alert alert-info">
		<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
		<h4><?php echo $alumno; ?> tiene una amonestación escrita</h4>
		
		<?php if (isset($config['tutoria']['impresion_amonestacion']) && $config['tutoria']['impresion_amonestacion'] == 1): ?>
		
		<p>Jefatura de estudios se encargará de imprimir el comunicado de amonestación escrita para los padres.</p>
		<?php mysqli_query($db_con, "UPDATE Fechoria SET recibido='1' WHERE Fechoria.id='$id'"); ?>
		
		<?php else: ?>
		
		<p>El alumno/a está pendiente de la amonestación escrita del tutor.</p>
			
		<br>

		<form method="post" action="//<?php echo $config['dominio']; ?>/<?php echo $config['path']; ?>/admin/fechorias/imprimir/amonestescrita.php">
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<a class="btn btn-primary btn-sm" href="//<?php echo $config['dominio']; ?>/<?php echo $config['path']; ?>/admin/fechorias/detfechorias.php?claveal=<?php echo $claveal; ?>&id=<?php echo $id; ?>">Ver detalles</a>
			<button type="submit" class="btn btn-primary btn-sm" name="amonestacion">Imprimir amonestación escrita</button>
		</form>
		
		<?php endif; ?>
	</div>


<?php 
}
}
}
}
?>
