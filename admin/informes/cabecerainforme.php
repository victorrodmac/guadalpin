<?php

function creatablas( $db_con, $blqueo, $raw  )
{
  while (($calarray = mysqli_fetch_array($raw)) && ($blqueo == 1))
  {
    
    $key = $calarray[0]-1;
    $blqraw = mysqli_query($db_con, "select estado from variables_estado where valor='susblk".$key."'");
    $blq = mysqli_fetch_array($blqraw);
    // comprueba si alguien esta escribiendo ahora mismo en la tabla y bloquea el acceso a los informes hasta que se termine la modificación
    // existe un posible problema que es cuando el usuario que estaba generando la tabla no termina de crear las tablas con lo que no actualiza el bloqueo de la tablas y se queda bloqueada la tabla. Habría que poner un enlace en administración para desbloquear las tablas por si acaso. En tanto que no este ese enlace, el administrador debe poner a 1 el valor de suspensosblkN donde N es la tabla que se haya quedado bloqueada.
    echo "select estado from variables_estado where valor='susblk".$key."'key".$blq[0];
    if ( $blq[0] == 0)
    {     
      $blqueo = 0;
    }
    else
    {
      mysqli_query($db_con, "update variables_estado set estado = 0 where valor = 'susblk".$key."'");
      mysqli_query($db_con, "drop table if exists suspensos".$key);
      mysqli_query($db_con, "drop table if exists suspensosprof".$key);

      $notas0 = "create table suspensos".$key." select count(calificaciones.nombre) as suspensos, alma.claveal1 as claveal, matriculas as pil, unidad as grupo, alma.curso as nivel from alma left join tcombasi on alma.claveal1= tcombasi.claveal1, calificaciones where notas". $key ." = calificaciones.codigo and calificaciones.nombre < '5' and calificaciones.nombre <> '10' group BY alma.`claveal1`, matriculas, unidad, alma.curso ";
      //echo $notas0;
      $result1 = mysqli_query($db_con, $notas0);
      $notas2 = "create table suspensosprof".$key." select distinct alma.claveal1 as claveal, cmateria as asignatura, calificaciones.nombre as nota, prof as nprof, c_prof as cprof, alma.unidad  from alma left join tcombasi on alma.claveal1= tcombasi.claveal1, calificaciones, horw where alma.unidad = horw.a_grupo and c_asig = cmateria and notas".$key." = calificaciones.codigo";
      //echo $notas2;
      $result1 = mysqli_query($db_con, $notas2);
      mysqli_query($db_con, "update variables_estado set estado = 0 where valor = 'suspensos".$key."'");
      mysqli_query($db_con, "update variables_estado set estado = 1 where valor = 'suspensosblk".$key."'");
    }
  
  }
  return $blqueo;
}
?>
<br />
<div align="center" style="max-width:920px;margin:auto;">
<div class="page-header">
  <h2>Informe de Evaluaciones <small> Estadísticas de Calificaciones</small></h2>
</div>

<?php
if (isset($_POST['f_curso'])) {
	$f_curso=$_POST['f_curso'];
}

if (file_exists(INTRANET_DIRECTORY . '/config_datos.php')) {

	if (!empty($f_curso) && ($f_curso != $config['curso_actual'])) {
		$exp_c_escolar = explode("/", $f_curso);
		$anio_escolar = $exp_c_escolar[0];
		
		$db_con = mysqli_connect($config['db_host_c'.$anio_escolar], $config['db_user_c'.$anio_escolar], $config['db_pass_c'.$anio_escolar], $config['db_name_c'.$anio_escolar]);
	}
	if (empty($f_curso)){
		$f_curso = $config['curso_actual'];
	}
}
else {
		$f_curso = $config['curso_actual'];
}
?>

<?php if (file_exists(INTRANET_DIRECTORY . '/config_datos.php')): ?>
<form method="POST" class="well well-large" style="width:450px; margin:auto">
<p class="lead">Informe Histórico</p>	
  	<div class="form-group">
  			    <label for="f_curso">Curso escolar</label>
  			    
  			    <select class="form-control" id="f_curso" name="f_curso" onChange="submit()">
  			    	<?php $exp_c_escolar = explode("/", $config['curso_actual']); ?>
  			    	<?php for($i=0; $i<5; $i++): ?>
  			    	<?php $anio_escolar = $exp_c_escolar[0] - $i; ?>
  			    	<?php $anio_escolar_sig = substr(($exp_c_escolar[0] - $i + 1), 2, 2); ?>
  			    	<?php if($i == 0 || (isset($config['db_host_c'.$anio_escolar]) && $config['db_host_c'.$anio_escolar] != "")): ?>
  			    	<option value="<?php echo $anio_escolar.'/'.$anio_escolar_sig; ?>"<?php if ($_POST['f_curso']==$anio_escolar.'/'.$anio_escolar_sig) { echo "selected"; }?>><?php echo $anio_escolar.'/'.$anio_escolar_sig; ?></option>
  			    	<?php endif; ?>
  			    	<?php endfor; ?>
  			    </select>
  	</div>
</form>
<hr />  	
<?php endif; ?>

<div class="tabbable" style="margin-bottom: 18px;">
<ul class="nav nav-tabs">
<li class="active"><a href="#tab0" data-toggle="tab">Eval. Inicial</a></li>	
<li><a href="#tab1" data-toggle="tab">1ª Evaluación</a></li>
<li><a href="#tab2" data-toggle="tab">2ª Evaluación</a></li>
<li><a href="#tab3" data-toggle="tab">Evaluación Ordinaria</a></li>
<li><a href="#tab4" data-toggle="tab">Evaluación Extraordinaria</a></li>
<li><a href="#tab5" data-toggle="tab">Primera Evaluación Excepcional</a></li>
<li><a href="#tab6" data-toggle="tab">Segunda Evaluación2 Excepcional</a></li>
</ul>

<div class="tab-content" style="padding-bottom: 9px; border-bottom: 1px solid #ddd;">
<?php 
// Comprobamos datos de evaluaciones
//$n1 = mysqli_query($db_con, "select * from notas where notas0 not like '' or notas1 not like ''");
$n1 = mysqli_query($db_con, "select * from tcombasi where notas0 not like '' or notas1 not like ''");
if(mysqli_num_rows($n1)>0){}
else{
	echo '<div align="center"><div class="alert alert-warning alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h5>ATENCIÓN:</h5>No hay datos de Calificaciones en la tabla NOTAS. Debes importar las Calificaciones desde Séneca (Administración de la Intranet --> Importar Calificaciones) para que este módulo funcione.
          </div></div>';
	exit();
}

$titulos = array("0"=>"Eval. Inicial","1"=>"1ª Evaluación","2"=>"2ª Evaluación","3"=>"Evaluación Ordinaria","4"=>"Evaluación Extraordinaria","5"=>"Primera Evaluación Excepcional","6"=>"Segunda Evaluación Excepcional");

?>


