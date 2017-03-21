<?php
require('../../bootstrap.php');


if(!(stristr($_SESSION['cargo'],'1') == TRUE))
{
  header('Location:'.'http://'.$config['dominio'].'/'.$config['path'].'/salir.php');
  exit;	
}

include("../../menu.php");
?>
<br />
<div align="center">
  <div class="page-header">
    <h2>Administración <small> Importación de calificaciones por Evaluación</small></h2>
  </div>
  <br />
  <div class="well well-large" style="width:700px;margin:auto;text-align:left">
<?php
  $directorio = $_GET['directorio'];
//echo $directorio."<br>";
  if ($directorio=="../exporta0") {
  	mysqli_query($db_con, "TRUNCATE TABLE notas");
  }

// codigo para desserializar la tabla notas
// aquí genera $key para ver que nota se introduce y calcula el tamaño de la tabla (no necesario ya que al generarse  combasi se crea la tabla)
  $key = 0;
  if ($directorio=="../exporta0") {
    //mysqli_query($db_con, "TRUNCATE TABLE notas");
  }
  if ($directorio=="../exporta1") {
    $key = 1;
  }
  if ($directorio=="../exporta2") {
    $key = 2;
  }
  if ($directorio=="../exportaO") {
    $key = 3;
  }
  if ($directorio=="../exportaE") {
    $key = 4;
  }
  if ($directorio=="../exportaP") {
    $key = 5;
  }
  if ($directorio=="../exportaS") {
    $key = 6;
  }
  // actualiza la variable para crear las tablas temporales de suspensos
  $siz = mysqli_query($db_con, "select * from tcombasi limit 1");
  $size = mysqli_num_rows($siz);
  //echo $size.",".$key;

// Recorremos directorio donde se encuentran los ficheros y aplicamos la plantilla.
  if ($handle = opendir($directorio)) 
  {
     while (false !== ($file = readdir($handle))) 
     {   	
        if ($file != "." && $file != ".." && $file != "index.php") 
        {
         	
        $doc = new DOMDocument('1.0', 'utf-8');

        $doc->load( $directorio.'/'.$file );

        $claves = $doc->getElementsByTagName( "ALUMNO" );
         
        /*Al ser $materias una lista de nodos
        lo puedo recorrer y obtener todo
        su contenido*/
        foreach( $claves as $clave )
        {	
          $clave2 = $clave->getElementsByTagName( "X_MATRICULA" );
          $clave3 = $clave2->item(0)->nodeValue;
          //$codigo = "";
          $materias = $clave->getElementsByTagName( "MATERIA_ALUMNO" );
          if ($directorio=="../exporta0") {
            $cod = "INSERT INTO notas (claveal,notas0,notas1,notas2,notas3,notas4,notas5,notas6,promociona) VALUES ('$clave3', '";
          }
          else
          {
            $cod = "update notas set notas".$key." = '";
          }

          foreach( $materias as $materia )
          {		
            $codigos = $materia->getElementsByTagName( "X_MATERIAOMG" );
            $codigo = $codigos->item(0)->nodeValue;
            $notas = $materia->getElementsByTagName( "X_CALIFICA" );
            $nota = $notas->item(0)->nodeValue;


        // codigo para desserializar la tabla notas
            if ($size==0) {
              // nunca va a llegar pero por si acaso
              $codtc = "INSERT INTO tcombasi (claveal1,cmateria,notas". $key .") VALUES ('$clave3', '";
            }
            else
            {
              $codtc = "update tcombasi set notas". $key . " = '";
            }
            if ($size == 0)
            {
              $codtc .= $codigo . "' , '";
              $codtc .= $nota;
              $codtc .= "')";
            }
            else
            {
              $codtc .= $nota;
              $codtc .="' where claveal1 = '$clave3' and cmateria = '$codigo'";
            }
            //echo $codtc;
            mysqli_query($db_con, $codtc);
        // fin codigo

            $codigo.=":";
            $nota.=";";
            $cod.=$codigo.$nota;

          }
          if ($directorio=="../exporta0") 
          {
            $cod.="', '', '', '', '', '', '')";
        	}
        	else{
            $cod.="' where claveal = '$clave3'";
        	}
          // echo $cod."<br>";
          mysqli_query($db_con, $cod);
        }   	       
      }
    }
    closedir($handle);
    if ($directorio=="../exporta0") 
    {
      mysqli_query($db_con, "insert into notas (claveal) select claveal1 from alma where claveal1 not in (select claveal from notas)");
    }
    mysqli_query($db_con, "update variables_estado set estado = 1 where valor = 'suspensos".$key."'");

    echo '<div align="center"><div class="alert alert-success alert-block fade in"><button type="button" class="close" data-dismiss="alert">&times;</button>Las Notas de Evaluación se han importado correctamente en la base de datos.</div></div>';
  }  
  else
  {
	  echo '<div align="center"><div class="alert alert-danger alert-block fade in"> <button type="button" class="close" data-dismiss="alert">&times;</button><h5>ATENCIÓN:</h5>Parece que no hay archivos en el directorio correspondiente.<br> O bien no has enviado el archivo correcto descargado de Séneca o bien el archivo está corrompido.</div></div>';
    exit;
  }

?>
    <div align="center">
      <input type="button" value="Volver atrás" name="boton" onclick="history.back(2)" class="btn btn-inverse" />
    </div>
  </div>
</div>
<?php include("../../pie.php");?>
</body>
</html>
