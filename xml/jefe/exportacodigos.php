<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed');

// Recorremos directorio donde se encuentran los ficheros y aplicamos la plantilla.
if ($handle = opendir('../exporta')) {
	mysqli_query($db_con, "truncate table tcombasi");
	mysqli_query($db_con, "truncate table tclaveal");
   while (false !== ($file = readdir($handle))) {   	
      if ($file != "." && $file != ".."&& $file != ".xml") {       	
		$doc = new DOMDocument('1.0', 'utf-8');

		$doc->load( '../exporta/'.$file );

		$claves = $doc->getElementsByTagName( "ALUMNO" );
		 
		/*Al ser $materias una lista de nodos
		lo puedo recorrer y obtener todo
		su contenido*/
		foreach( $claves as $clave )
		{	
			$clave0 = $clave->getElementsByTagName( "X_MATRICULA" );
			$clave1 = $clave0->item(0)->nodeValue;
			$clave2 = $clave->getElementsByTagName( "C_NUMESCOLAR" );
			$clave3 = $clave2->item(0)->nodeValue;
			//$codigo = "";
			$materias = $clave->getElementsByTagName( "MATERIA_ALUMNO" );
			$cod = "update alma set combasi = '";
			foreach( $materias as $materia )
			{
				$codtabla = "insert into tcombasi ( claveal1, cmateria ) values( '$clave1', '";		
				$codigos = $materia->getElementsByTagName( "X_MATERIAOMG" );
				$codigo = $codigos->item(0)->nodeValue;
				$codtabla .= $codigo."')";
				$codigo.=":";
				//echo $codtabla;
				mysqli_query($db_con, $codtabla);
				$cod.=$codigo;
			}
			mysqli_query($db_con, $codtabla2);				
			$cod.="', claveal1 = '$clave1' where claveal = '$clave3'";
			//echo $cod;
			mysqli_query($db_con, $cod);
		}   	       
	  }
   }
   closedir($handle);
} 
?>

