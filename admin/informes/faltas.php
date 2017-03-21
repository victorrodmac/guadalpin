<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); ?>

<!-- MODULO RESUMEN FALTAS DE ASISTENCIA -->

<a name="faltas"></a>
<h3>Resumen de faltas de asistencia</h3>

<div class="row">
	
	<div class="col-sm-2">
	
		<?php $result = mysqli_query($db_con, "SELECT COUNT(*) AS total FROM FALTAS WHERE FALTAS.falta = 'F' AND  FALTAS.claveal = '$claveal' "); ?>
		<?php $total = 0; ?>
		<?php if (mysqli_num_rows($result)): ?>
		<?php $row = mysqli_fetch_array($result); ?>
		<?php $total = $row['total']; ?>
		<?php mysqli_free_result($result); ?>
		<?php endif; ?>
		
		<h3 class="text-info text-center">
			<?php echo $total; ?><br>
			<small class="text-uppercase">faltas injustificadas</small>
		</h3>
		
	</div>
	
	<div class="col-sm-2">
		
		<?php $result = mysqli_query($db_con, "SELECT COUNT(*) AS total FROM FALTAS WHERE FALTAS.falta = 'J' AND  FALTAS.claveal = '$claveal' "); ?>
		<?php $total = 0; ?>
		<?php if (mysqli_num_rows($result)): ?>
		<?php $row = mysqli_fetch_array($result); ?>
		<?php $total = $row['total']; ?>
		<?php mysqli_free_result($result); ?>
		<?php endif; ?>
		
		<h3 class="text-info text-center">
			<?php echo $total; ?><br>
			<small class="text-uppercase">faltas justificadas</small>
		</h3>
		
	</div>

	<div class="col-sm-2">
	
		<?php $result = mysqli_query($db_con, "SELECT COUNT(*) AS total FROM FALTAS WHERE FALTAS.falta = 'R' AND  FALTAS.claveal = '$claveal' "); ?>
		<?php $total = 0; ?>
		<?php if (mysqli_num_rows($result)): ?>
		<?php $row = mysqli_fetch_array($result); ?>
		<?php $total = $row['total']; ?>
		<?php mysqli_free_result($result); ?>
		<?php endif; ?>
		
		<h3 class="text-info text-center">
			<?php echo $total; ?><br>
			<small class="text-uppercase">retrasos injustificados</small>
		</h3>
		
	</div>
	
	<div class="col-sm-2">
		
		<?php $result = mysqli_query($db_con, "SELECT FALTAS.fecha FROM FALTAS where FALTAS.falta = 'F' and  FALTAS.claveal = $claveal group by FALTAS.fecha"); ?>
		<?php $total = 0; ?>
		<?php $total = mysqli_num_rows($result); ?>
		
		<h3 class="text-info text-center">
			<?php echo $total; ?><br>
			<small class="text-uppercase">d�as con faltas injustificadas</small>
		</h3>
		
	</div>

	<div class="col-sm-2">
		
		<?php $result = mysqli_query($db_con, "select count(*) from (SELECT FALTAS.fecha, count(*) as cuenta FROM FALTAS where FALTAS.falta = 'F' and  FALTAS.claveal = '$claveal' group by FALTAS.fecha) as t where cuenta = 6"); ?>
		<?php $total = 0; ?>
		<?php $temp = mysqli_fetch_array($result); 
			$total = $temp[0];
			?>
		
		<h3 class="text-info text-center">
			<?php echo $total; ?><br>
			<small class="text-uppercase">d�as completos injustificados</small>
		</h3>
		
	</div>
	<div class="col-sm-2">
		
		<?php $result = mysqli_query($db_con, "select count(*) from (SELECT FALTAS.fecha, count(*) as cuenta FROM FALTAS where FALTAS.falta = 'J' and  FALTAS.claveal = '$claveal' group by FALTAS.fecha) as t where cuenta = 6"); ?>
		<?php $total = 0; ?>
		<?php $temp = mysqli_fetch_array($result); 
			$total = $temp[0];
			?>
		
		<h3 class="text-info text-center">
			<?php echo $total; ?><br>
			<small class="text-uppercase">d�as completos justificados</small>
		</h3>
		
	</div>

</div>

<br>

<!-- FIN MODULO RESUMEN FALTAS DE ASISTENCIA -->
