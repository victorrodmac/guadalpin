<?php
ini_set("session.cookie_lifetime","5600");
ini_set("session.gc_maxlifetime","7200");

require('../../bootstrap.php');

if (file_exists('config.php')) {
	include('config.php');
}

acl_acceso($_SESSION['cargo'], array(1, 2, 8));

// COMPROBAMOS SI ES EL TUTOR, SINO ES DEL EQ. DIRECTIVO U ORIENTADOR
if (stristr($_SESSION['cargo'],'2') == TRUE) {
	
	$_SESSION['mod_tutoria']['tutor']  = $_SESSION['mod_tutoria']['tutor'];
	$_SESSION['mod_tutoria']['unidad'] = $_SESSION['mod_tutoria']['unidad'];
	
}
else {

	if(isset($_POST['tutor'])) {
		$exp_tutor = explode('==>', $_POST['tutor']);
		$_SESSION['mod_tutoria']['tutor'] = trim($exp_tutor[0]);
		$_SESSION['mod_tutoria']['unidad'] = trim($exp_tutor[1]);
	}
	else{
		if (!isset($_SESSION['mod_tutoria'])) {
			header('Location:'.'tutores.php');
		}
	}
	
}


mysqli_query($db_con, "
CREATE TABLE IF NOT EXISTS `evalua_tutoria` (
  `id` int(11) NOT NULL auto_increment,
  `unidad` varchar(32) collate latin1_spanish_ci NOT NULL,
  `evaluacion` varchar(32) collate latin1_spanish_ci NOT NULL,
  `alumno` varchar(10) collate latin1_spanish_ci NOT NULL,
  `campo` varchar(10) collate latin1_spanish_ci NOT NULL,
  `valor` text collate latin1_spanish_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1
");

$curso = $_SESSION['mod_tutoria']['unidad'];
$evaluacion = $_POST['evaluacion'];

// ENVIO DEL FORMULARIO
if (isset($_POST['submit'])) {

	$curso = $_SESSION['mod_tutoria']['unidad'];
	$evaluacion = $_POST['evaluacion'];

	foreach ($_POST as $campo => $valor) {
		if ($campo != 'submit' and $valor!=="" and $campo !=="unidad" and $campo !=="evaluacion") {	
			
			$exp_campo = explode('-', $campo);

			$al_campo = $exp_campo[0];

			$claveal = $exp_campo[1];
			
$chk = mysqli_query($db_con, "select id from evalua_tutoria where unidad = '$curso' and evaluacion = '$evaluacion' and alumno = '$claveal' and campo = '$al_campo'");	
if (mysqli_num_rows($chk)>0) {
$result = mysqli_query($db_con, "update evalua_tutoria set valor = '$valor' where unidad = '$curso' and evaluacion = '$evaluacion' and alumno = '$claveal' and campo = '$al_campo'");	
}		
else{			
$result = mysqli_query($db_con, "INSERT INTO evalua_tutoria (unidad, evaluacion, alumno, campo, valor) VALUES ('$curso', '$evaluacion', '$claveal', '$al_campo', '$valor')");
}			
		}
	}
}

include("../../menu.php");
include("menu.php");
?>
<style type="text/css">
.tooltip-inner {
    max-width: 350px;
    /* If max-width does not work, try using width instead */
    width: 350px; 
}
</style>
<div class="container">
	
	<!-- TITULO DE LA PAGINA -->
	<div class="page-header">
		<h2 style="display:inline;">Tutor�a de <?php echo $_SESSION['mod_tutoria']['unidad']; ?> <small>Evaluaciones del tutor (<?php echo $evaluacion; ?>)</small></h2>
		
		<!-- Button trigger modal -->
		<a href="#"class="btn btn-default btn-sm pull-right hidden-print" data-toggle="modal" data-target="#modalAyuda">
			<span class="fa fa-question fa-lg"></span>
		</a>
	
		<!-- Modal -->
		<div class="modal fade" id="modalAyuda" tabindex="-1" role="dialog" aria-labelledby="modal_ayuda_titulo" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
						<h4 class="modal-title" id="modal_ayuda_titulo">Instrucciones de uso</h4>
					</div>
					<div class="modal-body">
						<p>El m�dulo est� abierto a Tutores, Equipo Directivo y Departamento de Orientaci�n.</p>
						<p>El Informe de Evaluaciones presenta los datos m�s importantes que conviene tener en 
						cuenta en el proceso de evaluaci�n de un alumno de la ESO: Edad, Cursos repetidos, PIL, 
						Exenci�n de optativas, Refuerzo y Asignaturas pendientes. <br>Puede ser utilizado como 
						fuente de informaci�n en las Sesiones de Evaluaci�n. Tambi�n puede utilizarse como un 
						diario donde el Tutor, el Equipo Directivo o el Departamento de Orientaci�n registran 
						los datos m�s relevantes en el campo de texto 'Observaciones'. Este campo es persistente: 
						si en la Evaluaci�n Ordinaria coloco el rat�n sobre el mismo aparecer�n en un cuadro 
						superpuesto las observaciones realizadas en las anteriores Sesiones de Evaluaci�n.</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Entendido</button>
					</div>
				</div>
			</div>
		</div>
		
		<h4 class="text-info">Tutor/a: <?php echo nomprofesor($_SESSION['mod_tutoria']['tutor']); ?></h4>
	</div>
	

<!-- MENSAJES --> <?php if (isset($msg_error)): ?>
<div class="alert alert-danger"><?php echo $msg_error; ?></div>
<?php endif; ?> <?php if (isset($msg_success)): ?>
<div class="alert alert-success"><?php echo $msg_success; ?></div>
<?php endif; ?>


<div class="row hidden-print">

<div class="col-sm-4 col-sm-offset-4">

<form method="post" action="">

<fieldset>

<div class="well">

<div class="row">

<div class="col-sm-12">
<input type='hidden' name='unidad' value='<?php echo $curso; ?>' />
<legend>Seleccione evaluaci�n</legend>
<div class="form-group">
<select class="form-control" id="evaluacion" name="evaluacion" onchange="submit()">
	<option><?php if (isset($_POST['evaluacion'])) {
		echo $evaluacion;
	} ?></option>
	<option>Ev. Inicial</option>
	<option>1� Evaluacion</option>
	<option>2� Evaluacion</option>
	<option>Ev.Ordinaria</option>
	<option>Ev.Extraordinaria</option>
	</select>
	</div>

</div>

</div>


</div>
<!-- /.well --></fieldset>

</form>

</div>
<!-- /.col-sm-12 -->
</div>
</div>
<div class="container-fluid">
<!-- /.row --> <?php if (isset($curso)  && isset($evaluacion)): ?>
<div class="row-fluid">

<div class="col-sm-12">

<div class="visible-print">
<h3><?php echo $evaluacion; ?> de <?php echo $curso; ?></h3>
</div>

<form method="post" action="" class="form-inline">

<input type="hidden" name="unidad" value="<?php echo $curso; ?>"> 
<input type="hidden" name="evaluacion" value="<?php echo $evaluacion; ?>">
<div class="table-responsive">
<table
	class="table table-bordered table-striped table-hover table-vcentered" align="center" style="width:auto">
	<thead>
		<tr>
			<th style="width:25px"></th>
			<th >Alumno/a</th>
			<th >Fecha</th>
			<th >Rep.</th>
			<th >PIL</th>
<?php
if ((strstr($curso,"1")==TRUE or strstr($curso,"2")==TRUE) or $orienta==1) {
?>			
			<th >Exen.</th>
			<th >Ref.</th>
<?php
}
?>
			<th>Pend.</th>
			<th>Observaciones</th>
			<?php if(stristr($_SESSION['cargo'],'8') == TRUE or stristr($_SESSION['cargo'],'1') == TRUE){?>
			<th>Orientaci�n</th>
			<th>Otros</th>
			<?php }?>
		</tr>
	</thead>
	<tbody>
	<?php $result = mysqli_query($db_con, "SELECT alma.apellidos, alma.nombre, alma.claveal, alma.nc, fecha, edad FROM alma WHERE alma.unidad='$curso' order by nc"); 
	?>
	<?php while ($row = mysqli_fetch_array($result)): $claveal = $row['claveal'];?>
		<tr>
		<?php $foto = '../../xml/fotos/'.$row['claveal'].'.jpg'; ?>
		<?php if (file_exists($foto)): ?>
			<td class="text-center"><img 
				src="<?php echo $foto; ?>"
				alt="<?php echo $row['apellidos'].', '.$row['nombre']; ?>"
				width="54"></td>
		<?php else: ?>
			<?php $foto = '../../xml/fotos/'.$row['claveal'].'.JPG'; ?>
			<?php if (file_exists($foto)): ?>
				<td class="text-center"><img 
					src="<?php echo $foto; ?>"
					alt="<?php echo $row['apellidos'].', '.$row['nombre']; ?>"
					width="54"></td>
			<?php else: ?>
				<td class="text-center"><span class="fa fa-user fa-fw fa-3x"></span></td>
			<?php endif; ?>
		<?php endif; ?>
					
			<td>
				<?php 
				$result_transito = mysqli_query($db_con, "SELECT * FROM transito_datos where claveal='".$claveal."'");
				?>
				<a href="../informes/index.php?claveal=<?php echo $claveal;?>&todos=Ver%20Informe%20Completo" target="_blank"><?php echo $row['nc'].". ".$row['apellidos'].', '.$row['nombre']; ?></a>
				<?php				
				if (mysqli_num_rows($result_transito)>0) {
				?>
				<br>
				<a href="../matriculas/informe_transito.php?claveal=<?php echo $claveal;?>" target="_blank" class="fa fa-user fa-lg text-info" data-bs="tooltip" data-html="true" title="Ver el Informe de Tr�nsito de Primaria disponible para el alumno"></a>
				<?php
				}
				?>				
			</td>
			
			<td><?php echo $row['fecha'].'<br><span class="text-success">('.$row['edad'].')</span>'; ?></td>
			
			<td>			
<?php
$repite = "";
// Primaria
$rep_primaria="";
$result_transito = mysqli_query($db_con, "SELECT dato FROM `transito_datos` WHERE `tipo` LIKE 'repetici%' and claveal='".$claveal."'");
while ($r_c = mysqli_fetch_array($result_transito)) {
	$rep_prim = trim($r_c[0]);
	$rep_primaria="Prim: ".$rep_prim."�; ";
}
$chk1 = mysqli_query($db_con, "select valor from evalua_tutoria where unidad = '$curso' and alumno = '".$row['claveal']."' and campo = 'rep'");
if (mysqli_num_rows($chk1)>0) {
	$rep0 = mysqli_fetch_array($chk1);
	$repite= $rep0[0];
}
else{
$index = substr($config['curso_actual'],0,4)+1;
$repi_db=mysqli_query($db_con,"select matriculas, curso from ".$config['db_name'].".alma where claveal='".$row['claveal']."' and matriculas > '1'");

if (mysqli_num_rows($repi_db)>0) {
$repit_db = mysqli_fetch_array($repi_db);
$repite=substr($repit_db[1],0,1)."�, ";
}
	for ($i = 0; $i < 5; $i++) {
	$ano = $config['db_name']."".($index-$i);
		$repi=mysqli_query($db_con,"select matriculas, curso from $ano.alma where claveal='".$row['claveal']."' and matriculas>'1'");
		if (mysqli_num_rows($repi)>0) {
		$repit = mysqli_fetch_array($repi);	
		$repite.=substr($repit[1],0,1)."�, ";
	}
	}
	if (strlen($repite)>0) {
		$repite=substr($repite,0,-2)." ESO";
	}
}
?>
				<textarea class="form-control" name="rep-<?php echo $row['claveal']; ?>" rows="2" cols="8" style="font-size:10px;padding:2px;"><?php if (strlen($rep_primaria)>0 and stristr($repite, $rep_primaria)==FALSE) { echo $rep_primaria; } ?><?php echo $repite; ?></textarea>
			</td>
			
			<td>
			<?php

$pil = "";			
$chk2 = mysqli_query($db_con, "select valor from evalua_tutoria where unidad = '$curso' and alumno = '".$row['claveal']."' and campo = 'pil'");
if (mysqli_num_rows($chk2)>0) {
	$pil0 = mysqli_fetch_array($chk2);
	$pil = $pil0[0];
}
	else{		
			$pil1 = mysqli_query($db_con,"select promociona from matriculas where promociona='2' and claveal = '".$row['claveal']."'");
			if (mysqli_num_rows($pil1)>0) {
	$pil="PIL";
			}
	}
echo "<input type='text' class='form-control input-sm' style='width:45px' maxlength='3' name='pil-".$row['claveal']."' value='$pil' />";
			?>
			</td>

<?php
if ((strstr($curso,"1")==TRUE or strstr($curso,"2")==TRUE) or $orienta==1) {
?>
<td>
			<?php

$exen = "";			
$chk21 = mysqli_query($db_con, "select valor from evalua_tutoria where unidad = '$curso' and alumno = '".$row['claveal']."' and campo = 'exen'");
if (mysqli_num_rows($chk21)>0) {
	$exen0 = mysqli_fetch_array($chk21);
	$exen = $exen0[0];
}
	else{		
			$exen0 = mysqli_query($db_con,"select exencion from matriculas where exencion='1' and claveal = '".$row['claveal']."'");
			if (mysqli_num_rows($exen0)>0) {
	$exen="1";
			}
	}
echo "<input type='text' class='form-control input-sm' style='width:30px' maxlength='3' name='exen-".$row['claveal']."' value='$exen' />";
			?>
			</td>

			<td>
	<?php

$ref = "";			
$chk22 = mysqli_query($db_con, "select valor from evalua_tutoria where unidad = '$curso' and alumno = '".$row['claveal']."' and campo = 'ref'");
if (mysqli_num_rows($chk22)>0) {
	$ref0 = mysqli_fetch_array($chk22);
	$ref = $ref0[0];
}
	else{		
			$ref1 = mysqli_query($db_con,"select act1 from matriculas where claveal = '".$row['claveal']."'");
			if (mysqli_num_rows($pil1)>0) {
			$refu = mysqli_fetch_array($ref1);
			
			if ($refu[0]=="1") {$ref="Leng";}
			if ($refu[0]=="2") {$ref="Mat";}
			if ($refu[0]=="3") {$ref="Ingl";}
	
			}
	}
echo "<input type='text' class='form-control input-sm' style='width:50px' maxlength='3' name='ref-".$row['claveal']."' value='$ref' />";
			?>
			</td>
<?php
}
?>						
			<td>
			<?php
			$pendiente="";
			$pend = mysqli_query($db_con,"select distinct pendientes.codigo, abrev from pendientes, asignaturas where pendientes.codigo=asignaturas.codigo and abrev like '%\_%' and claveal = '".$row['claveal']."'");
			while ($pendi = mysqli_fetch_row($pend)) {
				$pendiente.= $pendi[1]." ";
			}
			echo "<span class='text-danger'><small>$pendiente</small></span>";
			?>
			</td>
			
			<td>
<?php
$obs_extra = "";			
$chk44 = mysqli_query($db_con, "select valor, evaluacion, unidad from evalua_tutoria where alumno = '".$row['claveal']."' and campo = 'obs'");
if (mysqli_num_rows($chk44)>0) {
	while($obs00 = mysqli_fetch_array($chk44)){
	$obs_extra.="<p align=left>$obs00[2]<br>$obs00[1]:<br>$obs00[0]<p>";
	}
}

$obs = "";			
$chk4 = mysqli_query($db_con, "select valor from evalua_tutoria where unidad = '$curso' and evaluacion = '$evaluacion' and alumno = '".$row['claveal']."' and campo = 'obs'");
if (mysqli_num_rows($chk4)>0) {
	$obs0 = mysqli_fetch_array($chk4);
	$obs = $obs0[0];
}
?>
			<textarea class="form-control" name="obs-<?php echo $row['claveal']; ?>" rows="5" cols="45" style="font-size:10px;padding:1px;" data-bs="tooltip" data-html="true" title="<?php echo $obs_extra;?>"><?php echo $obs; ?></textarea>
			</td>
<?php if(stristr($_SESSION['cargo'],'8') == TRUE or stristr($_SESSION['cargo'],'1') == TRUE){?>
<td>			
<?php
$ori_extra = "";			
$chk55 = mysqli_query($db_con, "select valor, evaluacion, unidad from evalua_tutoria where alumno = '".$row['claveal']."' and campo = 'ori'");
if (mysqli_num_rows($chk55)>0) {
	while($ori00 = mysqli_fetch_array($chk55)){
	$ori_extra.="<p align=left>$ori00[2]<br>$ori00[1]:<br>$ori00[0]<p>";
	}
}

$ori = "";			
$chk5 = mysqli_query($db_con, "select valor from evalua_tutoria where unidad = '$curso' and evaluacion = '$evaluacion' and alumno = '".$row['claveal']."' and campo = 'ori'");
if (mysqli_num_rows($chk5)>0) {
	$ori0 = mysqli_fetch_array($chk5);
	$ori = $ori0[0];
}
?>			
			<textarea class="form-control" name="ori-<?php echo $row['claveal']; ?>" rows="5" cols="45" style="font-size:10px;padding:1px;" data-bs="tooltip" data-html="true" title="<?php echo $ori_extra;?>"><?php echo $ori; ?></textarea>
			</td>
			<td nowrap>
			<div class="form-group">
<?php
$inf_extra = "";			
$chk66 = mysqli_query($db_con, "select valor, evaluacion, unidad from evalua_tutoria where alumno = '".$row['claveal']."' and campo = 'inf'");
if (mysqli_num_rows($chk66)>0) {
	while($inf_extra0 = mysqli_fetch_array($chk66)){
	$inf_extra.="<p align=left>$inf_extra0[2]<br>$inf_extra0[1]: $inf_extra0[0]<p>";
	}
}

$inf = "";			
$chk6 = mysqli_query($db_con, "select valor from evalua_tutoria where unidad = '$curso' and evaluacion = '$evaluacion' and alumno = '".$row['claveal']."' and campo = 'inf'");
if (mysqli_num_rows($chk6)>0) {
	$inf0 = mysqli_fetch_array($chk6);
	$inf = $inf0[0];
}
?>			
			<select class="form-control input-sm" name="inf-<?php echo $row['claveal']; ?>" data-bs="tooltip" data-html="true" title="<?php echo $inf_extra;?>">
			<option><?php echo $inf;?></option>
			<option>SI</option>
			<option>NO</option>
			</select>
			</div>
			<label><small>Informe</small></label><br>
			
			<div class="form-group">
<?php
$aci_extra = "";			
$chk77 = mysqli_query($db_con, "select valor, evaluacion, unidad from evalua_tutoria where alumno = '".$row['claveal']."' and campo = 'aci'");
if (mysqli_num_rows($chk77)>0) {
	while($aci_extra0 = mysqli_fetch_array($chk77)){
	$aci_extra.="<p align=left>$aci_extra0[2]<br>$aci_extra0[1]: $aci_extra0[0]<p>";
	}
}

$aci = "";			
$chk7 = mysqli_query($db_con, "select valor from evalua_tutoria where unidad = '$curso' and evaluacion = '$evaluacion' and alumno = '".$row['claveal']."' and campo = 'aci'");
if (mysqli_num_rows($chk7)>0) {
	$aci0 = mysqli_fetch_array($chk7);
	$aci = $aci0[0];
}
?>			
			<select class="form-control input-sm" name="aci-<?php echo $row['claveal']; ?>" data-bs="tooltip" data-html="true" title="<?php echo $aci_extra;?>">
			<option><?php echo $aci;?></option>
			<option>SI</option>
			<option>NO</option>
			</select>
			</div>
			<label><small>ACI</small></label><br>
			
			<div class="form-group">
<?php
$dct_extra = "";			
$chk88 = mysqli_query($db_con, "select valor, evaluacion, unidad from evalua_tutoria where alumno = '".$row['claveal']."' and campo = 'dct'");
if (mysqli_num_rows($chk88)>0) {
	while($dct_extra0 = mysqli_fetch_array($chk88)){
	$aci_extra.="<p align=left>$dct_extra0[1]<br>$dct_extra0[1]: $dct_extra0[0]<p>";
	}
}

$dct = "";			
$chk8 = mysqli_query($db_con, "select valor from evalua_tutoria where unidad = '$curso' and evaluacion = '$evaluacion' and alumno = '".$row['claveal']."' and campo = 'dct'");
if (mysqli_num_rows($chk8)>0) {
	$dct0 = mysqli_fetch_array($chk8);
	$dct = $dct0[0];
}
?>			
			<select class="form-control input-sm" name="dct-<?php echo $row['claveal']; ?>" data-bs="tooltip" data-html="true" title="<?php echo $dct_extra;?>">
			<option><?php echo $dct;?></option>
			<option>SI</option>
			<option>NO</option>
			</select>
			<label><small>Dictamen</small></label>
			
			</div>
			
			
			</td>
<?php }?>
			
		</tr>
		<?php endwhile; ?>
		<tr>
			<td colspan='2'>
				<b>Observaciones generales sobre la <?php echo $evaluacion; ?></b>
			</td>
			<td colspan='9'>

<?php
$gen_extra = "";			
$chk99 = mysqli_query($db_con, "select valor, evaluacion from evalua_tutoria where unidad = '$curso' and campo = 'gen'");
if (mysqli_num_rows($chk99)>0) {
	while($gen00 = mysqli_fetch_array($chk99)){
	$gen_extra.="<p align=left>$gen00[1]:<br>$gen00[0]<p>";
	}
}
if (stristr($evaluacion, "inicia")==TRUE) {
	$al_evalua = "Ev__Inici";
}
elseif (stristr($evaluacion, ".Ordina")==TRUE) {
	$al_evalua = "Ev_Ordina";
}
elseif (stristr($evaluacion, "Extraordina")==TRUE) {
	$al_evalua = "Ev_Extrao";
}
else{
	$al_evalua = substr($evaluacion,0,2)."_evalua";
}

$gen = "";			
$chk9 = mysqli_query($db_con, "select valor from evalua_tutoria where unidad = '$curso' and evaluacion = '$evaluacion' and alumno = '$al_evalua ' and campo = 'gen'");
if (mysqli_num_rows($chk9)>0) {
	$gen0 = mysqli_fetch_array($chk9);
	$gen = $gen0[0];
}
?>			
			<textarea class="form-control" name="gen-<?php echo substr($evaluacion,0,9); ?>" rows="5" style="font-size:10px;padding:1px;width:100%;" data-bs="tooltip" data-html="true" title="<?php echo $gen_extra;?>"><?php echo $gen; ?></textarea>
			</td>
		</tr>
	</tbody>
</table>
</div>
<div class="hidden-print">
<button type="submit" class="btn btn-primary" name="submit" value="Registrar">Registrar</button>
<button type="reset" class="btn btn-default">Cancelar</button>
<a href="#" class="btn btn-info" onclick="javascript:print();">Imprimir</a>
</div>

</form>

</div>
<!-- /.col-sm-12 --></div>
<!-- /.row --> <?php endif; ?></div>
<!-- /.container -->

		<?php include("../../pie.php"); ?>

</body>
</html>
