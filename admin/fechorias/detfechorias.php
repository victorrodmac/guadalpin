<?php
require('../../bootstrap.php');

if (file_exists('../../config.php')) {
  include('../../config.php');
}

$tutor = $_SESSION['profi'];


include("../../menu.php");
include("menu.php");
include("envio.php");

?>
  <div class="container">
  
    <div class="page-header">
      <h2 style="display: inline;">Problemas de convivencia <small> Informe personal del Problema</small></h2>
      
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
              <p>Esta p�gina tiene varias funciones. En primer lugar, ofrece informaci�n detallada de un 
              problema de convivencia registrado por un Profesor. Presenta tambi�n datos num�ricos sobre 
              los problemas y tipos de problema del alumno. En la parte inferior tenemos una tabla donde 
              se recoge el historial delictivo del alumno.</p>
              <p>En la parte derecha nos encontramos, si pertenecemos al Equipo directivo, un par de 
              formularios para expulsar al alumno del Centro o expulsarlo al Aula de Convivencia una serie 
              de horas o d�as. La fecha de la expulsi�n no debe ser inmediata, considerando que los 
              Profesores del Equipo educativo del alumno que va a ser expulsado necesitar�n alg�n tiempo 
              para rellenar su Informe de Tareas de tal modo que �ste trabaje durante su ausencia.</p>
              <p>Tambi�n nos encontramos una serie de botones para imprimir partes oficiales relacionados 
              con el problema registrado, en caso de que necesitemos hacerlo. Generan documentos oficiales 
              preparados para ser enviados a los Padres del alumno, por lo que su uso est� limitado a 
              Tutores y Equipo directivo.</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Entendido</button>
            </div>
          </div>
        </div>
      </div>
      
    </div>

<?php
    if(!($_POST['id'])){$id = $_GET['id'];}else{$id = $_POST['id'];}
    if(!($_POST['claveal'])){$claveal = $_GET['claveal'];}else{$claveal = $_POST['claveal'];}
    if (isset($_POST['expulsion'])) { $expulsion = $_POST['expulsion']; }
    if (isset($_POST['inicio'])) { $inicio = $_POST['inicio']; }
    if (isset($_POST['fin'])) { $fin = $_POST['fin']; }
    if (isset($_POST['mens_movil'])) { $mens_movil = $_POST['mens_movil']; }
    if (isset($_POST['submit'])) { $submit = $_POST['submit']; }
    if (isset($_POST['convivencia'])) { $convivencia = $_POST['convivencia']; }
    if (isset($_POST['horas'])) { $horas = $_POST['horas']; }
    if (isset($_POST['fechainicio'])) { $fechainicio = $_POST['fechainicio']; }
    if (isset($_POST['fechafin'])) { $fechafin = $_POST['fechafin']; }
    if (isset($_POST['tareas'])) { $tareas = $_POST['tareas']; }
    if (isset($_POST['tareas_exp'])) { $tareas_exp = $_POST['tareas_exp']; }
    if (isset($_POST['imprimir4'])) { $imprimir4 = $_POST['imprimir4']; }
    if (isset($_POST['imprimir'])) { $imprimir = $_POST['imprimir']; }
    if (isset($_POST['imprimir5'])) { $imprimir5 = $_POST['imprimir5']; }
    if (isset($_POST['imprimir2'])) { $imprimir2 = $_POST['imprimir2']; }
    if (isset($_POST['imprimir3'])) { $imprimir3 = $_POST['imprimir3']; }
    if (isset($_POST['inicio_aula'])) { $inicio_aula = $_POST['inicio_aula']; }
    if (isset($_POST['fin_aula'])) { $fin_aula = $_POST['fin_aula']; }
    if (isset($_POST['convivencia'])) { $convivencia = $_POST['convivencia']; }
    if (isset($_POST['hora'])) { $hora = $_POST['hora']; }
    if (isset($_POST['tipofalta'])) { $tipofalta = $_POST['tipofalta']; }
    if (isset($_POST['aplazados'])) { $aplazados = $_POST['aplazados']; }
    if (isset($_POST['gravedad'])) { $gravedad = $_POST['gravedad']; }

    include("expulsiones.php");
    if (strlen($mensaje)>"0") {
    echo '<div align="center"><div class="alert alert-warning alert-block fade in">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
          <h5>ATENCI�N:</h5>'.
                $mensaje.'
              </div></div>';
    }
    $sql = "select alma.apellidos, alma.nombre, alma.unidad, alma.nc, Fechoria.fecha, Fechoria.notas, Fechoria.asunto, Fechoria.informa, Fechoria.grave, Fechoria.medida, listafechorias.medidas2, Fechoria.expulsion, Fechoria.tutoria, Fechoria.inicio, Fechoria.fin, aula_conv, inicio_aula, fin_aula, Fechoria.horas, Fechoria.hora, Fechoria.confirmado from Fechoria, alma, listafechorias where Fechoria.claveal = alma.claveal and listafechorias.fechoria = Fechoria.asunto  and Fechoria.id = '$id' order by Fechoria.fecha DESC";
    //echo $sql;
    $result = mysqli_query($db_con, $sql);
    if ($row = mysqli_fetch_array($result))
    {
      $apellidos = $row[0];
      $nombre = $row[1];
      $unidad = $row[2];
      $fecha = $row[4];
      $notas = $row[5];
      $asunto = $row[6];
      $informa = $row[7];
      $grave = $row[8];
      $medida = $row[9];
      $medidas2 = $row[10];
      $expulsion = $row[11];
      $tutoria = $row[12];
      $inicio = $row[13];
      $fin = $row[14];
      $convivencia = $row[15];
      $inicio_aula = $row[16];
      $fin_aula = $row[17];
      $horas = $row[18];
      $hora = $row[19];
      $confirmado = $row[20];
      if($inicio){ $inicio1 = explode("-",$inicio); $inicio = $inicio1[2] . "-" . $inicio1[1] ."-" . $inicio1[0];}
      if($fin){ $fin1 = explode("-",$fin); $fin = $fin1[2] . "-" . $fin1[1] ."-" . $fin1[0];}
      if($inicio_aula){ $inicio1 = explode("-",$inicio_aula); $inicio_aula = $inicio1[2] . "-" . $inicio1[1] ."-" . $inicio1[0];}
      if($fin_aula){ $fin1 = explode("-",$fin_aula); $fin_aula = $fin1[2] . "-" . $fin1[1] ."-" . $fin1[0];}
    }
    $numero = mysqli_query($db_con, "select Fechoria.claveal from Fechoria where Fechoria.claveal like '%$claveal%' and Fechoria.fecha >= '2006-09-15' order by Fechoria.fecha"); 
    $numerototal= mysqli_num_rows($numero);
    $numerograves0 = mysqli_query($db_con, "select Fechoria.claveal from Fechoria where Fechoria.claveal like '%$claveal%' and Fechoria.fecha >= '2006-09-15' and grave = 'grave' order by Fechoria.fecha"); 
    $numerograves= mysqli_num_rows($numerograves0);
    $numeromuygraves0 = mysqli_query($db_con, "select Fechoria.claveal from Fechoria where Fechoria.claveal like '%$claveal%' and Fechoria.fecha >= '2006-09-15' and grave = 'muy grave' order by Fechoria.fecha"); 
    $numeromuygraves= mysqli_num_rows($numeromuygraves0);
    $numeroexpulsiones0 = mysqli_query($db_con, "select Fechoria.claveal from Fechoria where Fechoria.claveal like '%$claveal%' and Fechoria.fecha >= '2006-09-15' and expulsion >= '1' order by Fechoria.fecha"); 
    $numeroexpulsiones= mysqli_num_rows($numeroexpulsiones0);

    if(isset($_GET['envio']))
    {
      $envio = $_GET['envio'];
      $alumno = mysqli_query($db_con, " SELECT distinct alma.APELLIDOS, alma.NOMBRE, alma.unidad, alma.TELEFONO, alma.TELEFONOURGENCIA, alma.CORREO FROM alma WHERE alma.claveal = '$claveal'" );
      $rowa = mysqli_fetch_array ( $alumno );
      $apellidos = trim ( $rowa [0] );
      $nombre = trim ( $rowa [1] );
      $unidad = trim ( $rowa [2] );
      $tfno = trim ( $rowa [3] );
      $tfno_u = trim ( $rowa [4] );
      $correo = trim ( $rowa [5] );

      $mens = 'Jefatura de Estudios le comunica que, con fecha '.$fecha.', su hijo/a '.$nombre.' '.$apellidos.' ha cometido una falta '.$grave.' contra las normas de convivencia del Centro. El tipo de falta es el siguiente: '.$asunto.'.<br>Le recordamos que puede conseguir informaci�n m�s detallada contactando con la Jefatura de Estudios del Centro.<br><br><hr>Este correo es informativo. Por favor, no responder a esta direcci�n de correo. Si necesita mayor informaci�n sobre el contenido de este mensaje, p�ngase en contacto con Jefatura de Estudios.';
      $mensms = "Su hijo/a $nombre $apellidos alumno del grupo $unidad ha cometido una falta contra las normas de convivencia del Centro. Hable con su hijo/a y, ante cualquier duda, consulte en http://".$config['dominio'];
      enviarsmsemail( $mensms, "Problemas de convivencia", $mens, $db_con, $claveal, $nombre, $apellidos, $unidad, $tfno, $tfno_u, $correo, $informa, 1, $id, 0, $config['jefatura_email']);
      echo '<div align="center"><div class="alert alert-warning alert-block fade in">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
          <h5>Se ha enviado un correo al Tutor/a legal del alumno</h5></div></div>';
    }

    ?>


    <legend align="center">
      <?php echo "$nombre $apellidos ($unidad)";?>
    </legend>
    <br />
    <div class="row">
      <div class="col-sm-7">
        <div class="well well-large">
        <?php
          if(file_exists("../../xml/fotos/".$claveal.".jpg"))
          {
            echo "<img src='../../xml/fotos/$claveal.jpg' border='2' width='100' height='119' style='margin-bottom:-145px' class='img-thumbnail img-circle pull-right hidden-phone' />";
          }
          else
          {
            if(file_exists("../../xml/fotos/".$claveal.".JPG")){
              echo "<img src='../../xml/fotos/$claveal.JPG' border='2' width='100' height='119' style='margin-bottom:-145px' class='img-thumbnail img-circle pull-right hidden-phone' />";
            }

          }
          ?>
          <table class="table table-striped">
            <tr>
              <th colspan="5"><h4>Informaci�n detallada sobre el Problema<?php echo $id; ?></h4></th>
            </tr>
            <tr>
              <th>NOMBRE</th>
              <td colspan="4"><?php echo $nombre." ".$apellidos; ?>
              </td>
            </tr>
            <tr>
              <th>GRUPO</th>
              <td colspan="4"><?php echo $unidad; ?></td>
            </tr>
            <tr>
              <th>FECHA</th>
              <td colspan="4"><?php echo $fecha; ?></td>
            </tr>
            <tr>
              <th>HORA</th>
              <td colspan="4"><?php echo $hora."� hora"; ?></td>
            </tr>
            <tr>
              <th>OBSERVACIONES</th>
              <td colspan="4"><?php echo $notas; ?></td>
            </tr>
            <tr>
              <th>ASUNTO</th>
              <td colspan="4"><?php echo $asunto; ?></td>
            </tr>
            <tr>
              <th>MEDIDAS</th>
              <td colspan="4"><?php echo $medida; ?></td>
            </tr>
            <tr>
              <th>GRAVEDAD</th>
              <td colspan="4"><?php echo $grave; ?></td>
            </tr>
            <tr>
              <th>ANTECEDENTES</th>
              <td >Total: <?php echo $numerototal; ?></td>
              <td >Graves: <?php echo $numerograves; ?></td>
              <td >Muy Graves: <?php echo $numeromuygraves; ?></td>
              <td >Expulsiones: <?php echo $numeroexpulsiones; ?></td>
            </tr>
            <tr>
              <th>PROTOCOLOS</th>
              <td colspan="4"><?php echo $medidas2; ?></td>
            </tr>
            <tr>
              <th>PROFESOR</th>
              <td colspan="4"><?php echo $informa; ?></td>
            </tr>
          </table>
          <br />
          <div align="center">
            <a href="../informes/index.php?claveal=<?php echo $claveal;?>&todos=1" target="_blank" class="btn btn-primary">
              Ver Informe del Alumno</a> 
            <a href="../jefatura/index.php?alumno=<?php echo $apellidos.", ".$nombre;?>&unidad=<?php echo $unidad;?>&grupo=<?php echo $grupo;?>" target="_blank" class="btn btn-primary">Registrar intervenci�n de Jefatura</a>
      <?php 
            if (stristr($_SESSION['cargo'],'1'))
            {
      ?>
              <a href="detfechorias.php?id=<?php echo $id;?>&claveal=<?php echo $claveal;?>&envio=1" target="_self" class="btn btn-primary">Envio sms o email</a>
      <?php
            }
      ?>
          </div>
        </div>
        <hr>
        <br />
        <h4>Problemas de Convivencia en el Curso</h4>
    <?php
        echo "<br />
        <table class='table table-striped' style='width:auto;'>";
          echo "<tr>
            <th>Fecha</th>
            <th>Tipo</th>
            <th>Gravedad</th>
            <th></th>
          </tr>";
      // Consulta de datos del alumno.
          $result = mysqli_query($db_con, "select distinct Fechoria.fecha, Fechoria.asunto, Fechoria.grave, Fechoria.id from Fechoria where claveal = '$claveal' and fecha >= '".$config['curso_inicio']."' order by fecha DESC" );
      
          while ( $row = mysqli_fetch_array ( $result ) ) 
          {
            echo "<tr>
              <td nowrap>$row[0]</td>
              <td>$row[1]</td>
              <td>$row[2]</td>
              <td nowrap><a href='detfechorias.php?id= $row[3]&claveal=$claveal' data-bs='tooltip' title='Detalles'><i class='fa fa-search fa-fw fa-lg'></i></a><a href='delfechorias.php?id= $row[3]' data-bs='tooltip' title='Eliminar'><i class='fa fa-trash-o fa-fw fa-lg'></i></a></td>
            </tr>";
          }
        echo "</table>\n";
    ?>      
      </div>
      <div class="col-sm-5">
    <?php
        $pr = $_SESSION ['profi'];
        $conv = mysqli_query($db_con, "SELECT DISTINCT nombre FROM departamentos WHERE cargo like '%b%' AND nombre = '$pr'");
        if (mysqli_num_rows($conv) > '0') {$gucon = '1';}
        if (mysqli_num_rows($expulsion) > '0') {$expul = '1';}
        if(stristr($_SESSION['cargo'],'1') == TRUE or $gucon == '1' or stristr($_SESSION['cargo'],'8') == TRUE)
        {
          if (stristr($_SESSION['cargo'],'1') == TRUE or stristr($_SESSION['cargo'],'8') == TRUE) 
          {
    ?>
            <div class="well"><h4>Expulsi�n del alumno</h4><br>
              <form id="form1" name="form1" method="post" action="detfechorias.php" class="">

                <div class="form-group">
                  <label> N&ordm; de D&iacute;as expulsi�n:</label>
                  <input name="expulsion" type="text" id="textfield" <?php if($expulsion > 0){echo "value=$expulsion";}?> maxlength="2" class="form-control" />
                  <label> N&ordm; de D&iacute;as aplazados:</label>
                  <input name="aplazados" type="text" id="textfield" <?php if($aplazados > 0){echo "value=$aplazados";}?> maxlength="2" class="form-control" />
                </div>
                 
                <input name="id" type="hidden" value="<?php echo $id; ?>"/>
                <input name="claveal" type="hidden" value="<?php echo $claveal; ?>"/>
           

                <div class="form-group " id="datetimepicker1">
                  <label>Inicio:</label>
                  <div class="input-group">
                    <input name="inicio" type="text" class="form-control" data-date-format="DD-MM-YYYY" id="inicio" <?php if(strlen($inicio) > '0' and !($inicio == '01-01-1960')){echo "value='$inicio'";}?>  >
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  </div> 
                </div>

                <div class="form-group " id="datetimepicker2">
                <label>Fin:</label>
                  <div class="input-group">
                    <input name="fin" type="text" class="form-control" data-date-format="DD-MM-YYYY" id="fin" <?php if(strlen($fin) > '0' and !($fin == '01-01-1960')){echo "value='$fin'";}?>  >
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  </div> 
                </div>

                <div class="row">
                  <div class="form-group col-sm-3">
                    <div class="checkbox">    
                      <label>
                      <input name="mens_movil" type="checkbox" id="sms" value="envia_sms" checked="checked" />
                      Enviar SMS o Email</label>
                    </div>
                  </div>
                  <div class="form-group col-sm-3">
                    <div class="checkbox pull-right">    
                      <label>
                      <input name="gravedad" type="checkbox" id="gravedad" value="<?php echo $gravedad;?>" />
                      Grave </label>
                    </div>
                  </div>
                  <div class="form-group col-sm-3">
                    <div class="checkbox">
                      <label for='tareas'>
                      <input name="tareas_exp" type="checkbox" id="tareas" value="insertareas_exp" checked="checked" />
                      Activar Tareas
                      </label>
                    </div>
                  </div>
                  <div class="form-group col-sm-3">
                    <div class="checkbox pull-right">    
                      <label>
                      <input name="borrar_exp" type="checkbox" id="borrar_exp" value="<?php echo $id;?>" />
                      Borrar datos </label>
                    </div>
                  </div>
                </div>
                <input name="submit" type="submit" value="Enviar datos" class="btn btn-primary" />
              </form>
            </div>
    <?php
          }
          $hora = date ( "G" ); // hora
          $ndia = date ( "w" );
          if (($hora == '8' and $minutos > 15) or ($hora == '9' and $minutos < 15)) {
            $hora_dia = '1';
          } elseif (($hora == '9' and $minutos > 15) or ($hora == '10' and $minutos < 15)) {
            $hora_dia = '2';
          } elseif (($hora == '10' and $minutos > 15) or ($hora == '11' and $minutos < 15)) {
            $hora_dia = '3';
          } elseif (($hora == '11' and $minutos > 15) or ($hora == '11' and $minutos < 45)) {
            $hora_dia = 'R';
          } elseif (($hora == '11' and $minutos > 45) or ($hora == '12' and $minutos < 45)) {
            $hora_dia = '4';
          } elseif (($hora == '12' and $minutos > 45) or ($hora == '13' and $minutos < 45)) {
            $hora_dia = '5';
          } elseif (($hora == '13' and $minutos > 45) or ($hora == '14' and $minutos < 45)) {
            $hora_dia = '6';
          } else {
            $hora_dia = "0";
          } 
    ?>
          <div class="well">
            <h4>Expulsi�n al Aula de convivencia </h4><br>
            <form id="form2" name="form2" method="post" action="detfechorias.php" >
              
              <div class="form-group">
                <label >N&uacute;mero de D&iacute;as</label>
                <input name="convivencia" type="text" id="expulsion" 
    <?php 
                if($convivencia > 0)
                {
                  echo "value=$convivencia";
                }else
                { 
                  if ($gucon == '1') 
                  {
                    echo "value=''";
                  }
                }
    ?> 
                size="2" maxlength="2" class="form-control" />
              </div>
              
              <div class="form-group">
                <label >Horas sueltas</label>
                <input name="horas" type="text" 
    <?php 
                if($horas > 0 || $horas == 'R')
                {
                  echo "value=$horas";
                }else
                { 
                  if (stristr($_SESSION['cargo'],'1') == TRUE) 
                  {
                    echo "value=123R456";
                  }else
                  {
                    echo "value=$hora_dia";
                  }
                }
    ?> 
                size="6" maxlength="6" class="form-control" />
              </div>
              <input name="id" type="hidden" value="<?php echo $id;?>" />
              <input name="claveal" type="hidden" value="<?php echo $claveal;?>" />
              <hr>
             
              <div class="form-group"  id="datetimepicker3">
                <label>Inicio:</label>
                <div class="input-group">
                  <input name="fechainicio" type="text" class="form-control" data-date-format="DD-MM-YYYY" id="fechainicio" 
    <?php 
                    if($inicio_aula)
                    {
                      echo "value=$inicio_aula";
                    }else
                    {
                      if ($gucon == '1')
                      { 
                        $def_inicio = date ( 'd' ) . "-" . date ( 'm' ) . "-" . date ( 'Y' );   
                        echo "value='$def_inicio'";
                      }
                    } 
    ?> 
                  >
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div> 
              </div>

              <div class="form-group" id="datetimepicker4">
                <label>Fin:</label>
                <div class="input-group">
                  <input name="fechafin" type="text" class="form-control" data-date-format="DD-MM-YYYY" id="fechafin" 
    <?php 
                    if($fin_aula)
                    {
                      echo "value=$fin_aula";
                    }else
                    { 
                      if ($gucon == '1')
                      {
                         $def_fin = date ( 'd' ) . "-" . date ( 'm' ) . "-" . date ( 'Y' );  
                        echo "value='$def_fin'";
                      }
                    } 
    ?>  
                  >
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div> 
              </div>
              <div class="row">
                <div class="form-group col-sm-6">
                  <div class="checkbox">
                    <label for='tareas'>
                      <input name="tareas" type="checkbox" id="tareas" value="insertareas" 
    <?php 
                      if ($gucon == '1') {}
                      else
                      {
                        echo 'checked="checked"';
                      }
    ?> 
                    />
                      Activar Tareas
                    </label>
                  </div>
                </div>
    <?php 
                if($config['mod_sms'])
                { 
    ?>
                  <div class="form-group  col-sm-6">
                    <div class="checkbox">
                      <label for='sms'>
                        <input name="mens_movil" type="checkbox" id="sms" value="envia_sms" checked="checked"  />
                        Enviar SMS
                      </label>
                    </div>             
                  </div>
    <?php 
                } 
    ?>
                <div class="form-group  col-sm-6">
                  <div class="checkbox">
                    <label for='borrar_aula'>
                      <input name="borrar_aula" type="checkbox" id="borrar_aula" value="<?php echo $id;?>"  />
                      Borrar datos
                    </label>
                  </div>
                </div>
              </div>
            
              <input type="submit" name="imprimir4" value="Enviar datos" class="btn btn-primary"/>
          
            </form>
          </div>
    <?php
        }
    ?>
      <div>
    <?php
    // s�lo se pueden los partes de expulsi�n y de amonestaci�n escrita si en configuraci�n esta permitido o bien eres directiva
      if((($config['amonestacion_jefatura'] == 1) && (stristr($_SESSION['cargo'],'1') == TRUE)) || ($config['amonestacion_jefatura'] == 0))
      {
    ?>

        <div class="well">
          <h4>Impresi�n de partes</h4><br>
    <?php
          if(stristr($_SESSION['cargo'],'1') == TRUE)
          {
    ?>
            <h6>EXPULSI&Oacute;N DEL CENTRO</h6>
            <form id="form2" name="form2" method="post" action="imprimir/expulsioncentro.php">
              <div class="checkbox">    
                <label>
                  <input name="amones_sms" type="checkbox" id="amones_sms" value="amones_sms" checked="checked" />
                  Enviar SMS o email
                </label>
              </div>
              <input name="id" type="hidden" value="<?php echo $id;?>" />
              <input name="claveal" type="hidden" value="<?php echo $claveal;?>" />
              <input name="tipofalta" type="hidden" value="<?php echo $tipofalta;?>" />
              <input name="aplazados" type="hidden" value="<?php echo $aplazados;?>" />
              <input name="fechainicio" type="hidden" id="textfield2" size="10" maxlength="10" <?php if($inicio){echo "value=$inicio";}?> />
              <input name="fechafin" type="hidden" id="textfield3" size="10" maxlength="10" <?php if($fin){echo "value=$fin";}?> />
                
              <input type="submit" name="imprimir" value="Expulsi&oacute;n del Centro" class="btn btn-danger"/>
                
            </form>
            <h6>EXPULSI&Oacute;N AL AULA DE CONVIVENCIA</h6>
              
            <form id="form3" name="form3" method="post" action="imprimir/convivencia.php">
              <input name="id" type="hidden" value="<?php echo $id;?>" />
              <input name="claveal" type="hidden" value="<?php echo $claveal;?>" />
              <input name="fechainicio" type="hidden" id="textfield2" size="10" maxlength="10" <?php if($inicio_aula){echo "value=$inicio_aula";}?> />
              <input name="fechafin" type="hidden" id="textfield3" size="10" maxlength="10" <?php if($fin_aula){echo "value=$fin_aula";}?> />
              <input name="horas" type="hidden" value="<?php echo $horas;?>" />
              <input type="submit" name="imprimir5" value="Aula de Convivencia"  class="btn btn-danger" />
            </form>
    <?php
          }
    ?>

          <h6>EXPULSI&Oacute;N DEL AULA </h6>
          <form id="form3" name="form3" method="post" action="imprimir/expulsionaula.php">
          
            <input name="id" type="hidden" value="<?php echo $id;?>" />
            <input name="claveal" type="hidden" value="<?php echo $claveal;?>" />
            <input type="submit" name="imprimir2" value="Parte de Expulsi&oacute;n del Aula" class="btn btn-danger" />
          
          </form>
          <h6>AMONESTACI&Oacute;N ESCRITA </h6>
          <form id="form3" name="form3" method="post" action="imprimir/amonestescrita.php">
      
            <label for="textoa">Texto que se a�ade al parte</label>
            <input type="text" name="textoa" value="reiteraci�n de partes">
            <div class="checkbox">    
              <label>
                <input name="amones_sms" type="checkbox" id="amones_sms" value="amones_sms" checked="checked" />
                Enviar SMS o email
              </label>
            </div>
            <input name="id" type="hidden" value="<?php echo $id;?>" />
            <input name="claveal" type="hidden" value="<?php echo $claveal;?>" />
            <input type="submit" name="imprimir3" value="Amonestaci&oacute;n escrita " class="btn btn-danger" />
          
          </form>
        </div>
    <?php
      }
    ?>
      </div>
    </div>
  </div>
</div>
<?php include("../../pie.php");?>
  <script>  
  $(function ()  
  { 
    $('#datetimepicker1').datetimepicker({
      language: 'es',
      pickTime: false
    });
    
    $('#datetimepicker2').datetimepicker({
      language: 'es',
      pickTime: false
    });
    
    $('#datetimepicker3').datetimepicker({
      language: 'es',
      pickTime: false
    });
    
    $('#datetimepicker4').datetimepicker({
      language: 'es',
      pickTime: false
    });
  });  
  </script>
</body>
</html>
