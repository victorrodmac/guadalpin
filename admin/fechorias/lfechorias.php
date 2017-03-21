<?php
require('../../bootstrap.php');


$idea = $_SESSION ['ide'];
if (strstr($_SERVER['REQUEST_URI'],'index.php')==TRUE) {$activ1 = ' class="active" ';}
if (strstr($_SERVER['REQUEST_URI'],'mensajes')==TRUE){ $activ2 = ' class="active" ';}
if (strstr($_SERVER['REQUEST_URI'],'upload')==TRUE){ $activ3 = ' class="active" ';}

$PLUGIN_DATATABLES = 1;

include("../../menu.php");
include("menu.php");

if(isset($_GET['id'])){$id = $_GET['id'];}
?>
<style>
.table th{
	font-size:0.8em;
	vertical-align:top !important;
}
</style>

<div class="container">
  
	<div class="page-header">
		<h2 style="display: inline;">Problemas de convivencia <small>�ltimos problemas</small></h2>
		
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
						<p>Esta p�gina presenta un listado de los �ltimos 300 Problemas de Convivencia que se han 
						registrado.</p>
						<p>La tabla con los resultados puede ser ordenada de forma ascendente o descendente pulsando 
						con el rat�n sobre el nombre de las columnas o las flechas que las acompa�an. Tambi�n admite 
						la introducci�n de criterios selectivos escribiendo datos en el campo de b�squeda que aparece 
						en la parte superior derecha de la tabla.</p>
						<p>La columna <strong>NUM.</strong> indica el n�mero total de Problemas registrados para un 
						alumno; la columna <strong>CAD.</strong> indica si el Problema ha caducado o no (30 d�as para 
						los Problemas Leves y Graves, 60 d�as para los Muy Graves); la siguiente columna nos dice si 
						el Tutor ha recibido y procesado la Notificaci�n del Problema en su p�gina de inicio. La 
						�ltima columna presenta un grupo de iconos con distintas funciones: podemos ver los detalles 
						del Problema; acceder al historial de Convivencia de un alumno; y, si procede, podemos editar 
						(hasta un m�ximo de 2 d�as despu�s de haberlo registrado) o eliminar un Problema de Convivencia.</p>
						<p>El Equipo Directivo tiene una columna con una casilla de verificaci�n que indica si el 
						Problema ha sido <strong>Revisado</strong> por Jefatura de Estudios.</p>
						</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Entendido</button>
					</div>
				</div>
			</div>
		</div>
		
	</div>
  
  <div class="row">
  <div class="col-sm-12">

<?php  
    echo '<div class="text-center" id="t_larga_barra">
    	<span class="lead"><span class="fa fa-circle-o-notch fa-spin"></span> Cargando...</span>
    </div>';
     		 
    echo "<div id='t_larga' style='display:none' >";
  if (isset($_POST['confirma'])) {
  	foreach ($_POST as $clave => $valor){
  		if (strlen($valor) > '0' and $clave !== 'confirma') {
  		$actualiza = "update Fechoria set confirmado = '1' where id = '$clave'";
  		$act = mysqli_query($db_con, $actualiza);		
  		} 
  	}
	echo '<div align="center"><div class="alert alert-success alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            El registro ha sido confirmado.
          </div></div>';
  }
   if(isset($_GET['borrar']) and $_GET['borrar']=="1"){
$query = "DELETE FROM Fechoria WHERE id = '$id'";
$result = mysqli_query($db_con, $query) or die ("Error en la Consulta: $query. " . mysqli_error($db_con));
echo '<div align="center"><div class="alert alert-success alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            El registro ha sido eliminado de la base de datos.
          </div></div>';	
}
  
  mysqli_query($db_con, "create table FechCaduca select id, fecha, TO_DAYS(now()) - TO_DAYS(fecha) as dias from Fechoria order by fecha desc limit 300");
  mysqli_query($db_con, "ALTER TABLE  `FechCaduca` ADD INDEX (  `id` )");
  mysqli_query($db_con, "ALTER TABLE  `FechCaduca` ADD INDEX (  `fecha` )");
  $query0 = "select alma.apellidos, alma.nombre, alma.unidad, alma.nc, Fechoria.fecha, Fechoria.asunto, Fechoria.informa, Fechoria.grave, Fechoria.claveal, Fechoria.id, Fechoria.expulsion, Fechoria.expulsionaula, Fechoria.medida, Fechoria.tutoria, recibido, dias, aula_conv, inicio_aula, fin_aula, confirmado, horas, hora from Fechoria, alma, FechCaduca where FechCaduca.id = Fechoria.id and alma.claveal = Fechoria.claveal  order by Fechoria.fecha desc limit 300";
  // echo $query0;
  $result = mysqli_query($db_con, $query0);
 echo "<form action='lfechorias.php' method='post' name='cnf'>
 <div class='table-responsive'>
 <table class='table table-bordered' style='width:auto' align='center'><tr><td class='expulsion-centro'>Expulsi�n del Centro</td><td class='amonestacion-escrita'>Amonestaci�n escrita</td><td class='expulsion-aula'>Expulsi�n del aula</td><td class='aula-convivencia-jefatura'>Aula de convivencia (Jefatura)</td><td class='aula-convivencia-profesor'>Aula de convivencia (Profesor)</td></tr></table><br />";
		echo '<div class="table-responsive"><table class="table table-striped table-bordered table-vcentered datatable">';
		$fecha1 = (date("d").-date("m").-date("Y"));
        echo "<thead><tr>
		<th></th>
        <th>ALUMNO</th>
		<th nowrap>UNIDAD</th>
		<th>FECHA</th>
		<th>HORA</th>
		<th>TIPO</th>
		<th>INFORMA</th>
		<th>GRAV.</th>
		<th>NUM.</th>
		<th>CAD.</th>		
		<th></th>
		<th></th>
		<th></th>
		</tr></thead><tbody>";	
   while($row = mysqli_fetch_array($result))
        {
        $marca = '';
		$apellidos = $row[0];
		$nombre = $row[1];
		$unidad = $row[2];
		$fecha = $row[4];
		$asunto = $row[5];
		$informa = $row[6];
		$grave = $row[7];
		$claveal = $row[8];
		$id = $row[9];
		$expulsion=$row[10];
		$expulsionaula=$row[11];
		$medida=$row[12];
		$tutoria=$row[13];
		$recibido=$row[14];
		$dias=$row[15];
		$aula_conv=$row[16];
		$inicio_aula=$row[17];
		$fin_aula=$row[18];
		$confirmado=$row[19];
		$horas=$row[20];
		$hora=$row[21];

		$cid = mysqli_query($db_con, "select id from tutoria where enviado = '".$id."'");
		$enviado= mysqli_num_rows($cid);

		if ($confirmado == '1') {
			$marca = " checked = 'checked'";
		}
		if(($dias > 30 and ($grave == 'leve' or $grave == 'grave')) or ($dias > 60 and $grave == 'muy grave'))
		{$caducada="S�";} else {$caducada="No";}
		$numero = mysqli_query($db_con, "select Fechoria.claveal from Fechoria where Fechoria.claveal 
		like '%$claveal%' and Fechoria.fecha >= '".$config['curso_inicio']."' order by Fechoria.fecha"); 
		$rownumero= mysqli_num_rows($numero);
		$rowcurso = $unidad;
        $rowalumno = $nombre."&nbsp;".$apellidos;
				$bgcolor="class=''";
				if($medida == "Amonestaci�n escrita" and $expulsionaula !== "1" and $expulsion == 0){$bgcolor="class='amonestacion-escrita'";}
				if($expulsionaula == "1"){$bgcolor="class='expulsion-aula'";}
				
				if($aula_conv > 0){
					if ($horas == "123456") {
						$bgcolor="class='aula-convivencia-jefatura'";
					}
					else{
						$bgcolor="class='aula-convivencia-profesor'";
					}
				}	
				
				if($expulsion > 0){$bgcolor="class='expulsion-centro'";}		
				if($recibido == '1'){$comentarios1="<i class='fa fa-check' data-bs='tooltip'  title='El Tutor ha recibido la notificaci�n.'> </i>";}elseif($recibido == '0'  and ($grave == 'grave' or $grave == 'muy grave' or $expulsionaula == '1' or $expulsion > '0' or $aula_conv > '0')){$comentarios1="<i class='fa fa-exclamation-triangle'  data-bs='tooltip' title='El Tutor NO ha recibido la notificaci�n.'> </i>";}else{$comentarios1="";}
		if ( $enviado == 0)
		{
			echo "<tr><td>";
		}
		else
		{
			//echo "llega";
			echo '<tr><td class="success">';			
		}
		$foto="<span class='fa fa-user fa-fw fa-3x'></span>";
		if(file_exists('../../xml/fotos/'.$claveal.'.jpg')) 
      		$foto = "<img src='../../xml/fotos/$claveal.jpg' width='50'>";
	    elseif(file_exists('../../xml/fotos/'.$claveal.'.JPG')) 
    		$foto = "<img src='../../xml/fotos/$claveal.JPG' width='50'>";
		echo $foto."</td>";
		if ( $enviado == 0)
		{
			echo '<td>'.$rowalumno.'</td>
			<td>'.$rowcurso.'</td>
			<td nowrap>'.$fecha.'</td>
			<td>'.$hora.'</td>
			<td>'.asunto.'</td>
			<td>'.nomprofesor($informa).'</td>
			<td '.$bgcolor.'>'.$grave.'</td>
			<td><center>'.$rownumero.'</center></td>
			<td>'.$caducada.'</td>
			<td nowrap>'.$comentarios1.' '.$comentarios.'</td><td nowrap>';
		}
		else
		{
			echo '<td class="success">'.$rowalumno.'</td>
			<td class="success">'.$rowcurso.'</td>
			<td nowrap class="success">'.$fecha.'</td>
			<td class="success">'.$hora.'</td>
			<td class="success">'.$asunto.'</td>
			<td class="success">'.nomprofesor($informa).'</td>
			<td '.$bgcolor.'>'.$grave.'</td>
			<td class="success"><center>'.$rownumero.'</center></td>
			<td class="success">'.$caducada.'</td>
			<td nowrap class="success">'.$comentarios1.' '.$comentarios.'</td><td nowrap class="success">';
		}	

		echo " <a href='detfechorias.php?id=$id&claveal=$claveal'><span class='fa fa-search fa-fw fa-lg' data-bs='tooltip' title='Detalles'></span></a>
		<a href='lfechorias2.php?clave=$claveal'><span class='fa fa-history fa-fw fa-lg' data-bs='tooltip' title='Historial'></span></a>
		";
		$ahora = mktime();
		$tr_f = explode("-",$fecha);
		$antes = mktime(0,0,0,$tr_f[1],$tr_f[2],$tr_f[0])+604800;
        if($_SESSION['profi']==$row[6] or stristr($_SESSION['cargo'],'1') == TRUE){
        	if (stristr($_SESSION['cargo'],'1') == TRUE) {
        		echo "<a href='infechoria.php?id=$id&nombre=$claveal'><span class='fa fa-edit fa-fw fa-lg' data-bs='tooltip' title='Editar'></span></a>";	
        	}
        	elseif($ahora < $antes){
        		echo "<a href='infechoria.php?id=$id&nombre=$claveal'><span class='fa fa-edit fa-fw fa-lg' data-bs='tooltip' title='Editar'></span></a>";
        	}
        	echo "<a href='lfechorias.php?id= $row[9]&borrar=1' data-bb='confirm-delete'><span class='fa fa-trash-o fa-fw fa-lg' data-bs='tooltip' title='Eliminar'></span></a>";
        }
		if ( $enviado == 0)
		{
			echo "</td><td>";
		}
		else
		{
			echo '</td><td class="success">';
		}	
		//echo "$expulsion >  $expulsionaula";
		if (stristr($_SESSION['cargo'],'1') and $grave=="muy grave" and $confirmado=="1") {
			echo "<input type='checkbox' name='confirmado' value='1' $marca data-bs='tooltip' title='El problema de conducta Muy grave ha sido confirmado por el Jefe de Estudios.' />";			
		}
		elseif (stristr($_SESSION['cargo'],'1') and $grave=="muy grave" and $confirmado!=="1") {
			echo "<input type='checkbox' name='confirmado' value='' $marca data-bs='tooltip' title='El problema de conducta Muy grave est� pendiente de confrimaci�n por parte del Jefe de Estudios. Para completar el proceso de registro, pulsa sobre el icono de edici�n y actualiza los datos en el formulario.'/>";
		}
		echo "</td></tr>";
	
        }

        mysqli_query($db_con, "drop table FechCaduca");
        
        echo "</tbody></table></div></div>";
        echo "<input type='hidden' name='confirma' value='si' />";
        echo "</form>";
		echo "</div></div></div></div></div>";
  ?>
  <?php include("../../pie.php");?>
  
  <script>
  $(document).ready(function() {
    var table = $('.datatable').DataTable({
    		"paging":   true,
        "ordering": true,
        "info":     false,
        
    		"lengthMenu": [[15, 35, 50, -1], [15, 35, 50, "Todos"]],
    		
    		"order": [[ 3, "desc" ]],
    		
    		"language": {
    		            "lengthMenu": "_MENU_",
    		            "zeroRecords": "No se ha encontrado ning�n resultado con ese criterio.",
    		            "info": "P�gina _PAGE_ de _PAGES_",
    		            "infoEmpty": "No hay resultados disponibles.",
    		            "infoFiltered": "(filtrado de _MAX_ resultados)",
    		            "search": "Buscar: ",
    		            "paginate": {
    		                  "first": "Primera",
    		                  "next": "�ltima",
    		                  "next": "",
    		                  "previous": ""
    		                }
    		        }
    	});
  });
  </script>
  <script>
function espera( ) {
        document.getElementById("t_larga").style.display = '';
        document.getElementById("t_larga_barra").style.display = 'none';        
}
window.onload = espera;
</script>     
  </body>
  </html>
