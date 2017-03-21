<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); 

mysqli_query($db_con, "CREATE TABLE IF NOT EXISTS `actualizacion` (
  `d` int(11) NOT NULL AUTO_INCREMENT,
  `modulo` varchar(128) COLLATE latin1_spanish_ci NOT NULL,
  `fecha` datetime NOT NULL,
  PRIMARY KEY (`d`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");


/*
 @descripcion: Sistema de reservas
 @fecha: 20 de agosto de 2015
 */
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Sistema de reservas'");
if (! mysqli_num_rows($actua)) {
	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Sistema de reservas', NOW())");
	
	// Eliminamos antigua actualizaci�n
	$result = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Reservas en base de datos principal'");
	if (! mysqli_num_rows($result)) {
	 mysqli_query($db_con, "DELETE FROM actualizacion WHERE modulo = 'Reservas en base de datos principal' LIMIT 1");
	}

	// Estructura de tabla para la tabla `reservas`
	
	mysqli_query($db_con, "CREATE TABLE IF NOT EXISTS ".$config['db_name'].".`reservas` (
	  `id` smallint(5) unsigned NOT NULL auto_increment,
	  `eventdate` date default NULL,
	  `dia` tinyint(1) NOT NULL default '0',
	  `html` tinyint(1) NOT NULL default '0',
	  `event1` varchar(64) default NULL,
	  `event2` varchar(64) NOT NULL default '',
	  `event3` varchar(64) NOT NULL default '',
	  `event4` varchar(64) NOT NULL default '',
	  `event5` varchar(64) NOT NULL default '',
	  `event6` varchar(64) NOT NULL default '',
	  `event7` varchar(64) NOT NULL default '',
	  `servicio` varchar(32) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");
	
	// Estructura de tabla para la tabla `reservas_hor`
	
	mysqli_query($db_con, "CREATE TABLE IF NOT EXISTS ".$config['db_name'].".`reservas_hor` (
	  `dia` tinyint(1) NOT NULL default '0',
	  `hora1` varchar(24) default NULL,
	  `hora2` varchar(24) default NULL,
	  `hora3` varchar(24) default NULL,
	  `hora4` varchar(24) default NULL,
	  `hora5` varchar(24) default NULL,
	  `hora6` varchar(24) default NULL,
	  `hora7` varchar(24) default NULL,
	  `servicio` varchar(32) NOT NULL,
	  KEY `dia` (`dia`)
	) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");
	
	// Tabla de Estad�sticas TIC
	
	mysqli_query($db_con, "CREATE TABLE IF NOT EXISTS ".$config['db_name'].".`usuario` (
	  `profesor` varchar(48) NOT NULL default '',
	  `c1` smallint(3) default NULL,
	  `c2` smallint(3) default NULL,
	  `c3` smallint(3) default NULL,
	  `c4` smallint(3) default NULL,
	  `c5` smallint(3) default NULL,
	  `c6` smallint(3) default NULL,
	  `c7` smallint(3) default NULL,
	  `c8` smallint(3) default NULL,
	  `c9` smallint(3) default NULL,
	  `c10` smallint(3) default NULL,
	  `c11` smallint(3) default NULL,
	  `c12` smallint(6) default NULL,
	  `c13` smallint(6) default NULL,
	  `c14` smallint(6) default NULL,
	  `c15` smallint(6) default NULL,
	  PRIMARY KEY  (`profesor`)
	) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");
	
	// Tabla de Dependencias ocultas
	
	mysqli_query($db_con, "CREATE TABLE IF NOT EXISTS `".$config['db_name']."`.`ocultas` (
	  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
	  `aula` varchar(48) COLLATE latin1_spanish_ci NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");
	
	// Tabla de Dependencias nuevas
	
	mysqli_query($db_con, "CREATE TABLE IF NOT EXISTS `".$config['db_name']."`.`nuevas` (
	  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
	  `abrev` varchar(5) COLLATE latin1_spanish_ci NOT NULL,
	  `nombre` varchar(128) COLLATE latin1_spanish_ci NOT NULL,
	  `texto` varchar(128) COLLATE latin1_spanish_ci NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");
	
	
	// CREACI�N TABLA: RESERVAS_TIPOS
	mysqli_query($db_con,"CREATE TABLE IF NOT EXISTS `".$config['db_name']."`.`reservas_tipos` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `tipo` varchar(254) COLLATE latin1_spanish_ci NOT NULL,
	  `observaciones` varchar(255) COLLATE latin1_spanish_ci NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci ;");

	mysqli_query($db_con, "TRUNCATE TABLE `".$config['db_name']."`.`reservas_tipos`");
	
	// CREACI�N TABLA: RESERVAS_ELEMENTOS
	mysqli_query($db_con,"CREATE TABLE IF NOT EXISTS `".$config['db_name']."`.`reservas_elementos` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `elemento` varchar(128) COLLATE latin1_spanish_ci NOT NULL,
	  `id_tipo` tinyint(2) NOT NULL,
	  `oculto` tinyint(1) NOT NULL DEFAULT '0',
	  `observaciones` varchar(255) COLLATE latin1_spanish_ci NOT NULL,
	  PRIMARY KEY (`id`)

	) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");
	mysqli_query($db_con, "TRUNCATE TABLE `".$config['db_name']."`.`reservas_elementos`");
	
	// INSERTAMOS LOS TIPOS DE RESERVAS POR DEFECTO DE LA APLICACI�N
	mysqli_query($db_con,"INSERT INTO `".$config['db_name']."`.`reservas_tipos` (`id`, `tipo`, `observaciones`) VALUES
	(1, 'TIC', ''),
	(2, 'Medios Audiovisuales', '');");
	
	
	// INSERTAMOS LOS CARRITOS TIC
	$result = mysqli_query($db_con, "SHOW TABLES FROM `reservas` LIKE 'carrito%'");
	if(! mysqli_num_rows($result)) {
		$result = mysqli_query($db_con, "SHOW TABLES FROM `".$config['db_name']."` LIKE 'carrito%'");
	}
	
	$i = 1;
	while ($row = mysqli_fetch_array($result)) {
	
		if ((stristr($row[0], 'hor') == FALSE)) {
			$nomcarrito = mysqli_real_escape_string($db_con, $row[0]);
			
			mysqli_query($db_con, "INSERT INTO `".$config['db_name']."`.`reservas_elementos` (elemento, id_tipo, oculto, observaciones) VALUES ('TIC $i', '1', '0', '')");
			
			$i++;
		}
		
	}
	mysqli_free_result($result);
	
	// INSERTAMOS LOS MEDIOS AUDIOVISUALES
	$result = mysqli_query($db_con, "SHOW TABLES FROM `reservas` LIKE 'medio%'");
	if(! mysqli_num_rows($result)) {
		$result = mysqli_query($db_con, "SHOW TABLES FROM `".$config['db_name']."` LIKE 'medio%'");
	}
	
	$i = 1;
	while ($row = mysqli_fetch_array($result)) {
		
		if ((stristr($row[0], 'hor') == FALSE)) {
			$nommedio = mysqli_real_escape_string($db_con, $row[0]);
			
			mysqli_query($db_con, "INSERT INTO `".$config['db_name']."`.`reservas_elementos` (elemento, id_tipo, oculto, observaciones) VALUES ('Medio $i', '2', '0', '')");
			
			$i++;
		}
		
	}
	mysqli_free_result($result);
	
	// ELIMINACI�N DE DATOS
	
	// ELIMINAMOS TABLAS DE AULAS Y DEPENDENCIAS 
	$result = mysqli_query($db_con, "SELECT DISTINCT a_aula, n_aula FROM horw WHERE c_asig NOT LIKE '25' AND a_aula NOT LIKE '' ORDER BY n_aula ASC");
	while ($row = mysqli_fetch_array($result)) {
	
		$nomdependencia = mysqli_real_escape_string($db_con, $row[0]);
		
		// MIGRAMOS LOS DATOS
		$result_reservas = mysqli_query($db_con, "SELECT * FROM `reservas`.`$nomdependencia`");
		if(! mysqli_num_rows($result_reservas)) {
			$result_reservas = mysqli_query($db_con, "SELECT * FROM `".$config['db_name']."`.`$nomdependencia`");
		}
		while ($datos = mysqli_fetch_array($result_reservas)) {
			mysqli_query($db_con,"INSERT INTO `".$config['db_name']."`.`reservas` (`eventdate`, `dia`, `html`, `event1`, `event2`, `event3`, `event4`, `event5`, `event6`, `event7`, `servicio`) VALUES ('$datos[1]', '$datos[2]', '$datos[3]', '$datos[4]', '$datos[5]', '$datos[6]', '$datos[7]', '$datos[8]', '$datos[9]', '$datos[10]', '$nomdependencia')");
		}
		
		mysqli_query($db_con, "DROP TABLE ".$config['db_name'].".`$nomdependencia`");
		
	}
	mysqli_free_result($result);
	
	// ELIMINAMOS TABLAS DE CARRITOS TIC
	$result = mysqli_query($db_con, "SHOW TABLES FROM `reservas` LIKE 'carrito%'");
	if(! mysqli_num_rows($result)) {
		$result = mysqli_query($db_con, "SHOW TABLES FROM `".$config['db_name']."` LIKE 'carrito%'");
	}
	while ($row = mysqli_fetch_array($result)) {
	
		$nomcarrito = mysqli_real_escape_string($db_con, $row[0]);
		
		// MIGRAMOS LOS DATOS
		$result_reservas = mysqli_query($db_con, "SELECT * FROM `reservas`.`$nomcarrito`");
		if(! mysqli_num_rows($result_reservas)) {
			$result_reservas = mysqli_query($db_con, "SELECT * FROM `".$config['db_name']."`.`$nomcarrito`");
		}
		while ($datos = mysqli_fetch_array($result_reservas)) {
			$nombre_tic = str_replace("carrito","TIC ",$nomcarrito);
			mysqli_query($db_con,"INSERT INTO `".$config['db_name']."`.`reservas` (`eventdate`, `dia`, `html`, `event1`, `event2`, `event3`, `event4`, `event5`, `event6`, `event7`, `servicio`) VALUES ('$datos[1]', '$datos[2]', '$datos[3]', '$datos[4]', '$datos[5]', '$datos[6]', '$datos[7]', '$datos[8]', '$datos[9]', '$datos[10]', '$nombre_tic')");
		}
		
		mysqli_query($db_con, "DROP TABLE ".$config['db_name'].".`$nomcarrito`");
		
	}
	mysqli_free_result($result);
	
	// ELIMINAMOS TABLAS DE MEDIOS AUDIOVISUALES
	$result = mysqli_query($db_con, "SHOW TABLES FROM `reservas` LIKE 'medio%'");
	if(! mysqli_num_rows($result)) {
		$result = mysqli_query($db_con, "SHOW TABLES FROM `".$config['db_name']."` LIKE 'medio%'");
	}
	while ($row = mysqli_fetch_array($result)) {
	
		$nommedio = mysqli_real_escape_string($db_con, $row[0]);
		
		// MIGRAMOS LOS DATOS
		$result_reservas = mysqli_query($db_con, "SELECT * FROM `reservas`.`$nommedio`");
		if(! mysqli_num_rows($result_reservas)) {
			$result_reservas = mysqli_query($db_con, "SELECT * FROM `".$config['db_name']."`.`$nommedio`");
		}
		while ($datos = mysqli_fetch_array($result_reservas)) {
			$nombre_medio = str_replace("medio","medio ",$nommedio);
			mysqli_query($db_con,"INSERT INTO `".$config['db_name']."`.`reservas` (`eventdate`, `dia`, `html`, `event1`, `event2`, `event3`, `event4`, `event5`, `event6`, `event7`, `servicio`) VALUES ('$datos[1]', '$datos[2]', '$datos[3]', '$datos[4]', '$datos[5]', '$datos[6]', '$datos[7]', '$datos[8]', '$datos[9]', '$datos[10]', '$nombre_medio')");
		}
		
		mysqli_query($db_con, "DROP TABLE ".$config['db_name'].".`$nommedio`");
		
	}
	mysqli_free_result($result);
	
	// ELIMINAMOS LA BASE DE DATOS DE RESERVAS
	mysqli_query($db_con, "DROP DATABASE `reservas`");
	
	unset($nomcarrito);
	unset($nommedio);
	unset($i);

}

/*
 @descripcion: Temas personalizados para cada profesor.
 @fecha: 19 de julio de 2015
 */
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Temas del Profesor'");
if (! mysqli_num_rows($actua)) {
	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Temas del Profesor', NOW())");

	mysqli_query($db_con, "CREATE TABLE IF NOT EXISTS `temas` (
  `idea` varchar(12) COLLATE latin1_spanish_ci NOT NULL,
  `tema` varchar(64) COLLATE latin1_spanish_ci NOT NULL,
  `fondo` varchar(16) COLLATE latin1_spanish_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

mysqli_query($db_con, "ALTER TABLE `temas`
 ADD UNIQUE KEY `idea` (`idea`)");
}


/*
 @descripcion: Eliminado usuario conserje
 @fecha: 10 de agosto de 2015
 */
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Eliminado usuario conserje'");
if (! mysqli_num_rows($actua)) {
	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Eliminado usuario conserje', NOW())");

	mysqli_query($db_con, "DELETE FROM departamentos WHERE nombre='conserje' LIMIT 1");
	mysqli_query($db_con, "DELETE FROM departamentos WHERE nombre='Conserjeria' LIMIT 1");
	mysqli_query($db_con, "DELETE FROM c_profes WHERE profesor='conserje' LIMIT 1");
	mysqli_query($db_con, "DELETE FROM c_profes WHERE profesor='Conserjeria' LIMIT 1");
	mysqli_query($db_con, "DELETE FROM calendario_categorias WHERE nombre='conserje'");
	mysqli_query($db_con, "DELETE FROM calendario_categorias WHERE nombre='Conserjeria'");
	mysqli_query($db_con, "DELETE FROM mens_texto WHERE origen='conserje'");
	mysqli_query($db_con, "DELETE FROM mens_texto WHERE origen='Conserjeria'");
	mysqli_query($db_con, "DELETE FROM reg_intranet WHERE profesor='conserje'");
	mysqli_query($db_con, "DELETE FROM reg_intranet WHERE profesor='Conserjeria'");
}


/*
 @descripcion: Eliminar calendarios duplicados
 @fecha: 10 de agosto de 2015
 */
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Eliminar calendarios duplicados'");
if (! mysqli_num_rows($actua)) {
	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Eliminar calendarios duplicados', NOW())");
	
	$result_profesores = mysqli_query($db_con, "SELECT DISTINCT profesor FROM calendario_categorias ORDER BY profesor ASC");
	
	while ($row = mysqli_fetch_array($result_profesores)) {
		
		$result_calendarios = mysqli_query($db_con, "SELECT id, nombre FROM calendario_categorias WHERE profesor='".$row['profesor']."' AND color='#3498db' AND espublico=0 ORDER BY id ASC");
		
		$i = 0;
		while ($row_calendario = mysqli_fetch_array($result_calendarios)) {
		
			if ($i == 0) {
				$calendario_principal = $row_calendario['id'];
			}
			else {
				$result_eventos = mysqli_query($db_con, "SELECT id FROM calendario WHERE categoria='".$row_calendario['id']."' ORDER BY id ASC");
				
				while ($row_evento = mysqli_fetch_array($result_eventos)) {
					mysqli_query($db_con, "UPDATE calendario SET categoria='".$calendario_principal."' WHERE categoria='".$row_evento['id']."' LIMIT 1");
				}
				mysqli_free_result($result_eventos);
				
				mysqli_query($db_con, "DELETE FROM calendario_categorias WHERE id='".$row_calendario['id']."' LIMIT 1");
			}
			
			$i++;
		}
		mysqli_free_result($result_calendarios);
		
	}
	mysqli_free_result($result_profesores);
	
	unset($calendario_principal);
	unset($i);
}

/*
 @descripcion: Eliminado usuario conserje
 @fecha: 10 de agosto de 2015
 */
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Eliminado usuario conserje'");
if (! mysqli_num_rows($actua)) {
	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Eliminado usuario conserje', NOW())");

	mysqli_query($db_con, "DELETE FROM departamentos WHERE nombre='conserje' LIMIT 1");
	mysqli_query($db_con, "DELETE FROM departamentos WHERE nombre='Conserjeria' LIMIT 1");
	mysqli_query($db_con, "DELETE FROM c_profes WHERE profesor='conserje' LIMIT 1");
	mysqli_query($db_con, "DELETE FROM c_profes WHERE profesor='Conserjeria' LIMIT 1");
	mysqli_query($db_con, "DELETE FROM calendario_categorias WHERE nombre='conserje'");
	mysqli_query($db_con, "DELETE FROM calendario_categorias WHERE nombre='Conserjeria'");
	mysqli_query($db_con, "DELETE FROM mens_texto WHERE origen='conserje'");
	mysqli_query($db_con, "DELETE FROM mens_texto WHERE origen='Conserjeria'");
	mysqli_query($db_con, "DELETE FROM reg_intranet WHERE profesor='conserje'");
	mysqli_query($db_con, "DELETE FROM reg_intranet WHERE profesor='Conserjeria'");
}


/*
 @descripcion: Actualizaci�n de tablas de mensajes a Idea.
 @fecha: 12 de agosto de 2015
 */
 
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Idea en Mensajes'");
if (! mysqli_num_rows($actua)) {

	mysqli_query($db_con,"drop table mens_texto_backup");
	mysqli_query($db_con,"drop table mens_profes_backup");
	mysqli_query($db_con,"create table mens_texto_backup select * from mens_texto");
	mysqli_query($db_con,"create table mens_profes_backup select * from mens_profes");
	
	
	mysqli_query($db_con,"create table departamento_tmp select nombre, idea, dni from departamentos");
	mysqli_query($db_con,"insert into departamento_tmp select nombre, idea, dni from ".$config['db_name']."2014.departamentos where nombre not in (select nombre from departamento_tmp)");
	mysqli_query($db_con,"insert into departamento_tmp select nombre, idea, dni from ".$config['db_name']."2013.departamentos where nombre not in (select nombre from departamento_tmp)");
	mysqli_query($db_con,"insert into departamento_tmp select nombre, idea, dni from ".$config['db_name']."2012.departamentos where nombre not in (select nombre from departamento_tmp)");
	mysqli_query($db_con,"insert into departamento_tmp select nombre, idea, dni from ".$config['db_name']."2011.departamentos where nombre not in (select nombre from departamento_tmp)");
	
	$query = mysqli_query($db_con,"select distinct profesor from mens_profes");
	while ($row = mysqli_fetch_array($query)) {
		if (strlen($row[0])>10) {		
		$query1 = mysqli_query($db_con,"select idea from departamento_tmp where nombre = '$row[0]'");
		if (mysqli_num_rows($query1)>0) {	
		$row1 = mysqli_fetch_array($query1);
		$idea = $row1[0];
		if ($idea!=="") {
			$n++;
		mysqli_query($db_con,"update mens_profes set profesor = '$idea' where profesor = '$row[0]'");
				}
			}
		}
	}
	
	$n=0;
	$query = mysqli_query($db_con,"select distinct origen from mens_texto");
	while ($row = mysqli_fetch_array($query)) {
		if (strlen($row[0])>10) {		
		$query1 = mysqli_query($db_con,"select idea from departamento_tmp where nombre = '$row[0]'");
		if (mysqli_num_rows($query1)>0) {	
		$row1 = mysqli_fetch_array($query1);
		$idea = $row1[0];
		if ($idea!=="") {
		$n++;
		mysqli_query($db_con,"update mens_texto set origen = '$idea' where origen = '$row[0]'");
				}
			}
		}
	}
	
	$n=0;
	$query = mysqli_query($db_con,"select distinct destino from mens_texto where destino not like 'Departamento%' and destino not like 'Equipo Educativo%' and destino not like 'CA%' and destino not like 'ETCP%' and destino not like 'Claustro%' and destino not like 'Equipo Directivo%' and destino not like 'Biling%' and destino not like '' and destino not like '; '");
	
	while ($row = mysqli_fetch_array($query)) {
		$idea = "";
		$trozos = explode("; ",$row[0]);
		foreach ($trozos as $nombre){
		if (strlen($row[0])>10) {		
		$query1 = mysqli_query($db_con,"select idea from departamento_tmp where nombre = '$nombre'");
		if (mysqli_num_rows($query1)>0) {	
		$row1 = mysqli_fetch_array($query1);
		$idea.=$row1[0]."; ";
				}
			}
		}
		if ($idea!=="") {
			$n++;
			mysqli_query($db_con,"update mens_texto set destino = '$idea' where destino = '$row[0]'");
		}
	}
	
	mysqli_query($db_con,"drop table departamento_tmp");
	
	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Idea en Mensajes', NOW())");
	
	unset($idea);
	unset($n);
}


/*
 @descripcion: Integraci�n de la tabla Jornada en la tabla Tramos.
 @fecha: 18 de agosto de 2015
 */
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Eliminacion tabla jornada'");
if (! mysqli_num_rows($actua)) {
	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Eliminacion tabla jornada', NOW())");
	
	$query_j = mysqli_query($db_con,"select hora_inicio from tramos");
	if (mysqli_num_rows($query_j)>0) {	}
	else{
		mysqli_query($db_con,"ALTER TABLE `tramos` ADD `hora_inicio` VARCHAR(5) NOT NULL , ADD `hora_fin` VARCHAR(5) NOT NULL");
	}
}

/*
 @descripcion: Modificaci�n Tabla listafechorias
 @fecha: 27 de agosto de 2015
 */
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Tabla listafechorias'");
if (! mysqli_num_rows($actua)) {
	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Tabla listafechorias', NOW())");
	
	mysqli_query($db_con, "ALTER TABLE `listafechorias` CHANGE `ID` `ID` INT(4) NOT NULL AUTO_INCREMENT;");
	
}

/*
 @descripcion: Informes de tutor�a - Nuevo campo motivo
 @fecha: 5 de octubre de 2015
 */
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Informes de tutor�a - Nuevo campo motivo'");
if (! mysqli_num_rows($actua)) {
	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Informes de tutor�a - Nuevo campo motivo', NOW())"); 
 	mysqli_query($db_con, "ALTER TABLE `infotut_alumno` ADD `motivo` VARCHAR(255) NULL");
}

/*
 @descripcion: Tabla Usuarioalumno - Aumento de longitid del campo (5 a 64)
 @fecha: 10 de octubre de 2015
 */
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Usuarioalumno - actualizacion campo unidad'");
if (! mysqli_num_rows($actua)) {
	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Usuarioalumno - actualizacion campo unidad', NOW())"); 
	
	mysqli_query($db_con,"ALTER TABLE `usuarioalumno` CHANGE `unidad` `unidad` VARCHAR(64) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL DEFAULT ''");
	
	$cambio0 = mysqli_query($db_con, "select claveal, unidad, apellidos, nombre from alma");
	while($cambio = mysqli_fetch_array($cambio0)){
		$f_cambio0 = mysqli_query($db_con, "select unidad from usuarioalumno where claveal = '$cambio[0]'");
		$f_cambio = mysqli_fetch_array($f_cambio0);
		if($cambio[1] == $f_cambio[0]){}
		else{
			mysqli_query($db_con, "update usuarioalumno set unidad = '$cambio[1]' where claveal = '$cambio[0]'");
		}	
	}
}

/*
 @descripcion: Tabla FALTAS - Aumento de longitud del campo CODASI (5 a 10)
 @fecha: 4 de noviembre de 2015
 */
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Tabla FALTAS - Aumento de longitud del campo CODASI'");
if (! mysqli_num_rows($actua)) {
	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Tabla FALTAS - Aumento de longitud del campo CODASI', NOW())"); 
	
	if (file_exists(INTRANET_DIRECTORY . '/config_datos.php')) {
		$anio_escolar = substr($config['curso_actual'], 0, 4)-1;
		
		while ($config['db_host_c'.$anio_escolar] != "") {
			$db_con = mysqli_connect($config['db_host_c'.$anio_escolar], $config['db_user_c'.$anio_escolar], $config['db_pass_c'.$anio_escolar], $config['db_name_c'.$anio_escolar]);
			
			mysqli_query($db_con, "ALTER TABLE `FALTAS` CHANGE `CODASI` `CODASI` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_spanish_ci NULL DEFAULT NULL;");
			
			mysqli_close($db_con);
			
			$anio_escolar--;
		}
		
		$db_con = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
		
		mysqli_query($db_con, "ALTER TABLE `FALTAS` CHANGE `CODASI` `CODASI` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_spanish_ci NULL DEFAULT NULL;");
	}
	else {
		mysqli_query($db_con, "ALTER TABLE `FALTAS` CHANGE `CODASI` `CODASI` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_spanish_ci NULL DEFAULT NULL;");
	}
}

/*
 @descripcion: Tabla FALTAS - Correci�n regular de error de c�digo de asignatura vac�a (hasta confirmar que error ha desaparecido, como parece)
 @fecha: 28 de noviembre de 2015
 */
$hoy= date('Y-m-d');
$tr_dia = explode("-",$hoy);
$n_dia = date('N', mktime(0, 0, 0, $tr_dia[1], $tr_dia[2], $tr_dia[0]));
$n_hora = date('G');

if($config['mod_asistencia']=="1" and $n_dia = "1" and $n_hora = "8") { 
	$result_falta = mysqli_query($db_con, "SELECT distinct claveal, hora, dia, unidad FROM FALTAS WHERE codasi = '' ORDER BY fecha DESC");
	while($row_falta = mysqli_fetch_array($result_falta)){
		$claveal = $row_falta['claveal'];
		$dia = $row_falta['dia'];
		$unidad = $row_falta['unidad'];
		$hora = $row_falta['hora'];
		$sin_cod=mysqli_query($db_con,"select c_asig from horw_faltas where dia='$dia' and hora='$hora' and a_grupo like '$unidad%'");
		while ($sin_codigo = mysqli_fetch_array($sin_cod)) {
			$asig_nene = mysqli_query($db_con,"select combasi from alma where claveal = '$claveal' and combasi like '%$sin_codigo[0]:%'");
			if (mysqli_num_rows($asig_nene)>0) {
				$codigo_asignatura=$sin_codigo[0];
				$num++;
				mysqli_query($db_con,"update FALTAS set codasi = '$codigo_asignatura' where claveal = '$claveal' and hora = '$hora' and dia = '$dia'");
				break;
			}
		}
	}
}

/*
 @descripcion: Modificaci�n temas - Standard y Yeti
 @fecha: 30 de noviembre de 2015
 */
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Modificaci�n temas - Standard y Yeti'");
if (! mysqli_num_rows($actua)) {
	mysqli_query($db_con, "UPDATE temas SET tema = 'temas/bootstrap.min-standard.css' WHERE tema = 'temas/bootstrap.min.standard.css'");
	mysqli_query($db_con, "UPDATE temas SET tema = 'temas/bootstrap.min-yeti.css' WHERE tema = 'temas/bootstrap.min-jeti.css'");
	
	unlink(INTRANET_DIRECTORY . '/css/temas/bootstrap.min.standard.css');
	unlink(INTRANET_DIRECTORY . '/img/temas/standard.png');
	
	unlink(INTRANET_DIRECTORY . '/css/temas/bootstrap.min-jeti.css');
	unlink(INTRANET_DIRECTORY . '/css/temas/jeti.png');
	
	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Modificaci�n temas - Standard y Yeti', NOW())"); 
}

/*
 @descripcion: Eliminar del horario grupos seleccionados del Cuaderno en los que no impartimos clase a ning�n alumno.
 @fecha: 11 de Diciembre de 2015
 */
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Borrar grupos seleccionados sin alumnos del horario'");
if (! mysqli_num_rows($actua)) {
$hor = mysqli_query($db_con, "select distinct profesor, asignatura, curso, id from grupos where alumnos=''");
while($hor_profe = mysqli_fetch_array($hor)){

	// Varios c�digos de asignatura en Bachillerato
		$bach1 = mysqli_query($db_con,"select nomcurso from unidades, cursos where unidades.idcurso=cursos.idcurso and nomunidad='$hor_profe[2]'");
		$bach2 = mysqli_fetch_array($bach1);
		if (stristr($bach2[0], "Bachill")==TRUE) {
			$asig1 = mysqli_query($db_con,"select codigo from asignaturas, unidades, cursos where unidades.idcurso=cursos.idcurso and nomcurso=asignaturas.curso and nomunidad='$hor_profe[2]' and nombre = (select distinct nombre from asignaturas where codigo = '".$hor_profe[1]."' and abrev not like '%\_%')");
			$asig2 = mysqli_fetch_array($asig1);
			$asignatura=$asig2[0];
		}

	mysqli_query($db_con, "delete from horw where prof = '$hor_profe[0]' and c_asig = '$hor_profe[1]' and a_grupo = '$hor_profe[2]'");
	mysqli_query($db_con, "delete from horw_faltas where prof = '$hor_profe[0]' and c_asig = '$hor_profe[1]' and a_grupo = '$hor_profe[2]'");
	mysqli_query($db_con, "delete from grupos where id = '$hor_profe[3]'");
	}
mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Borrar grupos seleccionados sin alumnos del horario', NOW())"); 
}

/*
 @descripcion: cambiar estructura de la tabla profesores_seneca para simplificarla Y actualizar nombre de profesores en Horw.
 @fecha: 28 de Diciembre de 2015
 */
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Estructura tabla profesores_seneca'");
if (! mysqli_num_rows($actua)) {
mysqli_query($db_con,"ALTER TABLE `profesores_seneca` CHANGE `nomprofesor` `nomprofesor` VARCHAR(64) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL;");

// Cambiamos el nombre de los profesores de Horw para ajustarlos a S�neca.	
	$nom_prof = mysqli_query($db_con, "select ape1profesor, ape2profesor, nomprofesor, idprofesor from profesores_seneca");
	while($nom_profe = mysqli_fetch_row($nom_prof)){
		$nombre_profesor = "$nom_profe[0] $nom_profe[1], $nom_profe[2]";
		mysqli_query($db_con,"update profesores_seneca set nomprofesor = '$nombre_profesor' where idprofesor = '$nom_profe[3]'");
	}

// Borramos campos innecesarios
mysqli_query($db_con,"ALTER TABLE `profesores_seneca` DROP `ape1profesor`");
mysqli_query($db_con,"ALTER TABLE `profesores_seneca` DROP `ape2profesor`");

// Cambiamos el nombre de los profesores de Horw para ajustarlos a S�neca.
$hor0 = mysqli_query($db_con, "select distinct c_prof, prof from horw order by prof");
while($hor_profe0 = mysqli_fetch_array($hor0)){
	
	$nom_prof0 = mysqli_query($db_con, "select nomprofesor from profesores_seneca where idprofesor = '$hor_profe0[0]'");
	$nom_profe0 = mysqli_fetch_row($nom_prof0);
	mysqli_query($db_con,"update horw set prof = '$nom_profe0[0]' where c_prof = '$hor_profe0[0]'");
	mysqli_query($db_con,"update horw_faltas set prof = '$nom_profe0[0]' where c_prof = '$hor_profe0[0]'");
	mysqli_query($db_con,"update profesores set profesor = '$nom_profe0[0]' where profesor = '$hor_profe0[1]'");
}

mysqli_query($db_con,"delete from profesores where nivel='' or grupo=''");
mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Estructura tabla profesores_seneca', NOW())"); 
}

/*
 @descripcion: Campo Observacioenes en tabla de Ausencias del Profesor.
 @fecha: 31 de Mayo de 2016
 */
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Observaciones en Ausencias del profesor'");
if (! mysqli_num_rows($actua)) {
	
	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Observaciones en Ausencias del profesor', NOW())");

	mysqli_query($db_con, "ALTER TABLE  `ausencias` ADD  `Observaciones` TEXT NULL");
}

/*
 @descripcion: Campo turno en tabla guardias
 @fecha: 12 de Junio de 2016
 */

 $actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Campo turno en tabla guardias'");
if (! mysqli_num_rows($actua)) {
	
	mysqli_query($db_con, "ALTER TABLE guardias ADD turno TINYINT(1);");
	mysqli_query($db_con, "UPDATE guardias SET turno = 1;");
	
	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Campo turno en tabla guardias', NOW())");
}

/*
 @descripcion: Tabla de accesos para SMS
 @fecha: 25 de Agosto de 2016
 */

$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Tabla de Accesos para SMS'");
if (! mysqli_num_rows($actua)) {

	// Borramos tabla de pruebas
	mysqli_query($db_con,"drop table control_acceso");
		
	// Tablas necesarias para el m�dulo
	mysqli_query($db_con,"CREATE TABLE IF NOT EXISTS `acceso_dias` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	  `fecha` date NOT NULL,
	  `numero` int(11) NOT NULL,
	  PRIMARY KEY  (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1 ");	
	
	mysqli_query($db_con,"CREATE TABLE IF NOT EXISTS `acceso` (
	`id` int(11) NOT NULL auto_increment,
	  `profesor` varchar(10) COLLATE latin1_spanish_ci NOT NULL,
	  `fecha` date NOT NULL,
	  `clase` tinyint(1) NOT NULL,
	  `observaciones` varchar(32) COLLATE latin1_spanish_ci NOT NULL,
	  PRIMARY KEY  (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1");

	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Tabla de Accesos para SMS', NOW())");
}


/*
 @descripcion: Correcci�n tel�fono del profesor en c_profes
 @fecha: 3 de septiembre de 2016
 */

$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Correcci�n tel�fono del profesor'");
if (! mysqli_num_rows($actua)) {
	
	if ( mysqli_num_rows(mysqli_query($db_con, "SHOW COLUMNS FROM c_profes LIKE 'telefono'")) == 1 ) {
		mysqli_query($db_con, "ALTER TABLE `c_profes` CHANGE `telefono` `telefono` CHAR(9) CHARACTER SET latin1 COLLATE latin1_spanish_ci NULL DEFAULT NULL");
	}
	else {
		mysqli_query($db_con, "ALTER TABLE `c_profes` ADD `telefono` CHAR(9) NULL");
	}
	
	if ( mysqli_num_rows(mysqli_query($db_con, "SHOW COLUMNS FROM departamentos LIKE 'telefono'")) == 1 ) {
		mysqli_query($db_con, "ALTER TABLE `departamentos` DROP `telefono`");
	}
	
	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Correcci�n tel�fono del profesor', NOW())");
}


/*
 @descripcion: Longitud abreviatura aula en horario
 @fecha: 18 de septiembre de 2016
 */

$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Longitud abreviatura aula en horario'");
if (! mysqli_num_rows($actua)) {

	mysqli_query($db_con, "ALTER TABLE `horw` CHANGE `a_aula` `a_aula` VARCHAR(32) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL DEFAULT ''");
	mysqli_query($db_con, "ALTER TABLE `horw_faltas` CHANGE `a_aula` `a_aula` VARCHAR(32) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL DEFAULT ''");

	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Longitud abreviatura aula en horario', NOW())");
}


/*
 @descripcion: Longitud unidad en tabla Grupos
 @fecha: 4 de octubre de 2016
 */

$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Longitud del campo unidad en tabla Grupos'");
if (! mysqli_num_rows($actua)) {
	
	mysqli_query($db_con,"ALTER TABLE `grupos` CHANGE `curso` `curso` VARCHAR(32) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL DEFAULT ''");	

	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Longitud del campo unidad en tabla Grupos', NOW())");
}

/*
 @descripcion: Nuevo m�dulo - Pedidos de materiales
 @fecha: 24 de octubre de 2016
 */

$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Nuevo m�dulo - Pedidos de materiales'");
if (! mysqli_num_rows($actua)) {
	
	mysqli_query($db_con,"CREATE TABLE IF NOT EXISTS `depto_pedidos` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `departamento` varchar(80) COLLATE latin1_spanish_ci NOT NULL,
	  `responsable` varchar(80) COLLATE latin1_spanish_ci NOT NULL,
	  `id_acta` int(10) unsigned NOT NULL,
	  `justificacion` tinytext COLLATE latin1_spanish_ci,
	  `incidencias` tinytext COLLATE latin1_spanish_ci,
	  `condiciones` tinyint(1) unsigned NOT NULL DEFAULT '0',
	  `fechaRegistro` datetime NOT NULL,
	  `entregado` tinyint(1) NOT NULL DEFAULT '0',
	  `vistoSecretaria` tinyint(1) NOT NULL DEFAULT '0',
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1 ;");
	
	mysqli_query($db_con,"CREATE TABLE IF NOT EXISTS `depto_pedidos_detalles` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `id_pedido` int(10) unsigned NOT NULL,
	  `articulo` varchar(80) COLLATE latin1_spanish_ci NOT NULL,
	  `cantidad` tinyint(3) unsigned NOT NULL,
	  `importe` decimal(10,2) unsigned NOT NULL,
	  PRIMARY KEY (`id`,`id_pedido`)
	) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1 ;");
	
	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Nuevo m�dulo - Pedidos de materiales', NOW())");
}

/*
 @descripcion: Cambio en tabla fechorias y convivencia para permitir castigo en recreos.
 @fecha: 16 de noviembre de 2016
 */

$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Registrar expulsi�n en hora de recreo'");
if (! mysqli_num_rows($actua)) {
	
	mysqli_query($db_con,"ALTER TABLE `Fechoria` CHANGE `horas` `horas` VARCHAR(10) NULL DEFAULT '123R456'");
	mysqli_query($db_con,"ALTER TABLE `convivencia` CHANGE `hora` `hora` CHAR(1) NOT NULL DEFAULT '0'");
	
	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Registrar expulsi�n en hora de recreo', NOW())");
}

/*
 @descripcion: Ampliaci�n campos de asignaturas y cursos
 @fecha: 21 de noviembre de 2016
 */

$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Ampliaci�n campos de asignaturas y cursos'");
if (! mysqli_num_rows($actua)) {
	mysqli_query($db_con, "ALTER TABLE `materias` CHANGE `NOMBRE` `NOMBRE` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_spanish_ci NULL DEFAULT NULL");
	mysqli_query($db_con, "ALTER TABLE `asignaturas` CHANGE `NOMBRE` `NOMBRE` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_spanish_ci NULL DEFAULT NULL");
	mysqli_query($db_con, "ALTER TABLE `asignaturas` CHANGE `CURSO` `CURSO` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_spanish_ci NULL DEFAULT NULL");
	
	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Ampliaci�n campos de asignaturas y cursos', NOW())");
}


/*
 @descripcion: Sustituci�n de no_prof por c_prof en FALTAS
 @fecha: 27 de noviembre de 2016
 */

$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Sustituci�n de no_prof en FALTAS'");
if (! mysqli_num_rows($actua)) {

	mysqli_query($db_con,"ALTER TABLE `FALTAS` CHANGE `PROFESOR` `PROFESOR` INT(7) NULL DEFAULT NULL");

	$hor0 = mysqli_query($db_con, "select distinct no_prof, c_prof from horw");
	while($hor_profe0 = mysqli_fetch_array($hor0)){
	mysqli_query($db_con,"update FALTAS set profesor='$hor_profe0[1]' where profesor='$hor_profe0[0]'");
	}

	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Sustituci�n de no_prof en FALTAS', NOW())");
}


/*
 @descripcion: Calificaciones de la Evaluaci�n Inicial
 @fecha: 1 de enero de 2017
 */
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Calificaciones de Inicial en tabla notas'");
if (! mysqli_num_rows($actua)) {
	mysqli_query($db_con,"RENAME TABLE `notas_seg` TO `notas_seg_old`");
	mysqli_query($db_con,"create table `notas_seg` select * from `notas`");
	mysqli_query($db_con,"ALTER TABLE `notas` ADD `notas0` VARCHAR(200) NULL AFTER `claveal`");
	
	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Calificaciones de Inicial en tabla notas', NOW())");
	}
/*
 @descripcion: Calificaciones de la Evaluaci�n Inicialde Cursos anteriores (evita errores en estad�sticas de evaluaci�n)
 @fecha: 12 de enero de 2017
 */
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Campo de Inicial en BD anteriores'");
if (! mysqli_num_rows($actua)) {
	for ($i=0; $i <= 6; $i++) { 
		if ($config['db_name_c201'.$i]!="") {
	
			mysqli_query($db_con,"RENAME TABLE ".$config['db_name_c201'.$i].".`notas_seg` TO ".$config['db_name_c201'.$i].".`notas_seg_old`");
			mysqli_query($db_con,"create table ".$config['db_name_c201'.$i].".`notas_seg` select * from ".$config['db_name_c201'.$i].".`notas`");
			mysqli_query($db_con,"ALTER TABLE ".$config['db_name_c201'.$i].".`notas` ADD `notas0` VARCHAR(200) NULL AFTER `claveal`");
		
		}
	}
	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Campo de Inicial en BD anteriores', NOW())");
	
}
/*
 @descripcion: Creaci�n de la tabla tcombasi para sustituir la columna de combasi de la tabla alma y la tabla notas. Temporalmente las dos tablas seguiran en paralelo por compatibilidad, pero una vez cambiado todo desapareceran.
 @fecha: 1 de febrero de 2017
 */
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Tabla tcombasi'");
if (! mysqli_num_rows($actua)) {
	mysqli_query($db_con,"CREATE TABLE IF NOT EXISTS `tcombasi` ( `claveal1` varchar(12) collate latin1_spanish_ci NOT NULL, `cmateria` int(8) NOT NULL,  notas0 int(3), notas1 int(3), notas2 int(3), notas3 int(3), notas4 int(3), notas5 int(3), notas6 int(3),promociona int(3), `nprof` VARCHAR( 50 ) NULL DEFAULT NULL , `cprof` VARCHAR( 30 ) NULL DEFAULT NULL , `grupo` VARCHAR( 64 ) NULL DEFAULT NULL, constraint ccombasi primary key ( claveal1, cmateria))");
	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Tabla tcombasi', NOW())");
}
/*
 @descripcion: Creaci�n de la tabla variables_estado son variables del mismo estilo que la tabla actualizaci�n que determinan el estado de ciertos procedimientos internos de la intranet. Ahora mismo s�lo tiene dos variables: suspensos (sirve para indicar de que se deben de generar las tablas intermedias para acelerar los c�lculos de los informes) y suspensosblk (bloquea cualquier intento de visualizar un informe mientras se esta generando las tablas intermedias, ya que podr�a generar errores de datos (no muy probable) y sobre todo lentitud del proceso). Se repiten tantas veces como columnas de evaluaciones haya.
 @fecha: 1 de febrero de 2017
 */
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Tabla variables_estado'");
if (! mysqli_num_rows($actua)) {
	mysqli_query($db_con,"create table variables_estado( id int primary key, valor varchar(20), estado int)");
	mysqli_query($db_con,"INSERT INTO `variables_estado` (`id`, `valor`, `estado`) VALUES ('1', 'suspensos0', '0')");
	mysqli_query($db_con,"INSERT INTO `variables_estado` (`id`, `valor`, `estado`) VALUES ('2', 'suspensos1', '0')");
	mysqli_query($db_con,"INSERT INTO `variables_estado` (`id`, `valor`, `estado`) VALUES ('3', 'suspensos2', '0')");
	mysqli_query($db_con,"INSERT INTO `variables_estado` (`id`, `valor`, `estado`) VALUES ('4', 'suspensos3', '0')");
	mysqli_query($db_con,"INSERT INTO `variables_estado` (`id`, `valor`, `estado`) VALUES ('5', 'suspensos4', '0')");
	mysqli_query($db_con,"INSERT INTO `variables_estado` (`id`, `valor`, `estado`) VALUES ('6', 'suspensos5', '0')");
	mysqli_query($db_con,"INSERT INTO `variables_estado` (`id`, `valor`, `estado`) VALUES ('7', 'suspensos6', '0')");
	mysqli_query($db_con,"INSERT INTO `variables_estado` (`id`, `valor`, `estado`) VALUES ('8', 'susblk0', '1')");
	mysqli_query($db_con,"INSERT INTO `variables_estado` (`id`, `valor`, `estado`) VALUES ('9', 'susblk1', '1')");
	mysqli_query($db_con,"INSERT INTO `variables_estado` (`id`, `valor`, `estado`) VALUES ('10', 'susblk2', '1')");
	mysqli_query($db_con,"INSERT INTO `variables_estado` (`id`, `valor`, `estado`) VALUES ('11', 'susblk3', '1')");
	mysqli_query($db_con,"INSERT INTO `variables_estado` (`id`, `valor`, `estado`) VALUES ('12', 'susblk4', '1')");
	mysqli_query($db_con,"INSERT INTO `variables_estado` (`id`, `valor`, `estado`) VALUES ('13', 'susblk5', '1')");
	mysqli_query($db_con,"INSERT INTO `variables_estado` (`id`, `valor`, `estado`) VALUES ('14', 'susblk6', '1')");

	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Tabla variables_estado', NOW())");
}
/*
 @descripcion: Modificaci�n del tama�o de apellidos en la tabla falumnos debido a que si el apellido del alumno/a es muy largo no se llega a introducir en la BD.
 @fecha: 1 de febrero de 2017
 */
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Correcci�n apellidos de falumnos'");
if (! mysqli_num_rows($actua)) {
	mysqli_query($db_con,"ALTER TABLE `FALUMNOS` CHANGE `APELLIDOS` `APELLIDOS` CHAR(50) CHARACTER SET latin1 COLLATE latin1_spanish_ci NULL DEFAULT NULL;");

	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Correcci�n apellidos de falumnos");
}
/*
 @descripcion: Modificaci�n del tama�o de tipo de listafechorias y de grave de fechorias para a�adir un tipo nuevo ( a determinar ). Con esto se consigue que cualquier parte que no este definido en el ROF tiene que ser confirmado por parte de jefatura, que se encargar� de tificar el parte con el nivel de gravedad que estime oportuno, teniendo la posibilidad de quitarlo si no se tiene consideraci�n de falta.
 @fecha: 1 de febrero de 2017
 */
$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Correcci�n tama�o col varias tablas'");
if (! mysqli_num_rows($actua)) {
	mysqli_query($db_con,"ALTER TABLE `listafechorias` CHANGE `tipo` `tipo` CHAR(12) CHARACTER SET latin1 COLLATE latin1_spanish_ci NULL DEFAULT NULL;");
	mysqli_query($db_con,"ALTER TABLE `Fechoria` CHANGE `grave` `grave` CHAR(12) CHARACTER SET latin1 COLLATE latin1_spanish_ci NULL DEFAULT NULL;");

	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Correcci�n tama�o col varias tablas', NOW())");
}

/*
 @descripcion: creaci�n de una tabla especifica para los partes de mantenimiento TIC en el IES Guadalpin
 @fecha: 1 de febrero de 2017
 */

$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Creaci�n tabla partesticgd'");
if (! mysqli_num_rows($actua)) {
	mysqli_query($db_con," CREATE TABLE IF NOT EXISTS `partesticgd` (
  							`parte` smallint(5) unsigned NOT NULL auto_increment,
  							`unidad` varchar(64) collate latin1_spanish_ci NOT NULL,
							  `a_aula` varchar(32) collate latin1_spanish_ci default NULL,
							  `n_aula` varchar(64) collate latin1_spanish_ci default NULL,
							  `nserie` varchar(15) collate latin1_spanish_ci NOT NULL default '',
							  `fecha` date NOT NULL default '1960-01-01',
							  `hora` char(2) collate latin1_spanish_ci default '',
							  `profesor` varchar(64) collate latin1_spanish_ci NOT NULL default '',
							  `descripcion` text collate latin1_spanish_ci NOT NULL,
							  `estado` varchar(12) collate latin1_spanish_ci NOT NULL default 'activo',
							  `nincidencia` varchar(10) collate latin1_spanish_ci default NULL,
							  PRIMARY KEY  (`parte`)
							) DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci PACK_KEYS=0 AUTO_INCREMENT=1 ");

	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Creaci�n tabla partesticgd', NOW())");

}

/*
 @descripcion: Modificaci�n destino mens_texto para ponerle un valor por defecto
 @fecha: 9 de febrero de 2017
 */

$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Modificaci�n destino mens_texto'");
if (! mysqli_num_rows($actua)) {
	mysqli_query($db_con, "ALTER TABLE `mens_texto` CHANGE `destino` `destino` text CHARACTER SET latin1 COLLATE latin1_spanish_ci NULL DEFAULT NULL");
	
	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Modificaci�n destino mens_texto', NOW())");
}
/*
 @descripcion: Modificaci�n de partesticgd para que tenga un campo m�s de soluci�n y otro de idea
 @fecha: 11 de febrero de 2017
 */

$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'A�adir a partesticgd la columna solucion e idea'");
if (! mysqli_num_rows($actua)) {
	mysqli_query($db_con, "ALTER TABLE `partesticgd` add `solucion` text CHARACTER SET latin1 COLLATE latin1_spanish_ci NULL DEFAULT NULL");
	mysqli_query($db_con, "ALTER TABLE `partesticgd` add `idea` varchar(12) CHARACTER SET latin1 COLLATE latin1_spanish_ci NULL DEFAULT NULL");
	
	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('A�adir a partesticgd la columna solucion e idea', NOW())");
}

/*
 @descripcion: Meter el campo de hora en la tabla de Fechoria y actualizar el valor a 1
 @fecha: 11 de febrero de 2017
 */

$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Meter el campo de hora en la tabla de Fechoria'");
if (! mysqli_num_rows($actua)) {
	mysqli_query($db_con, "ALTER TABLE `Fechoria` add `hora` int(4) DEFAULT 1");

	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Meter el campo de hora en la tabla de Fechoria', NOW())");
}

/*
 @descripcion: Cargar asignaturas no cargadas ni en horw ni en calificaciones debido a que no tienen calificaci�n y no se ha introducido en horw
 @fecha: 14 de febrero de 2017
 */

$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Cargar asignaturas no cargadas'");
if (! mysqli_num_rows($actua)) {
	mysqli_query($db_con, "
		CREATE TABLE IF NOT EXISTS `asignocargadas` (
		  `a_asig` varchar(8) collate latin1_spanish_ci NOT NULL default '',
		  `asig` varchar(128) collate latin1_spanish_ci NOT NULL default '',
		  `c_asig` varchar(30) primary key collate latin1_spanish_ci NOT NULL default ''
		)  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1 ");
	mysqli_query($db_con, "INSERT INTO asignocargadas VALUES ('LIBD1','Libre Disposici�n 1', '141393')");

	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Cargar asignaturas no cargadas', NOW())");
}

/*
 @descripcion: Crear tabla de profesores proveniente de seneca del fichero relmatuni donde estan los profesores y que asignaturas dan
 @fecha: 14 de febrero de 2017
 */

$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Crear tabla de profesores proveniente de seneca'");
if (! mysqli_num_rows($actua)) {
	mysqli_query($db_con, "
		CREATE TABLE IF NOT EXISTS `profesores_relmatuni` (
  `nivel` varchar(255) COLLATE latin1_spanish_ci DEFAULT NULL,
  `materia` varchar(255) COLLATE latin1_spanish_ci DEFAULT NULL,
  `grupo` varchar(255) COLLATE latin1_spanish_ci DEFAULT NULL,
  `profesor` varchar(255) COLLATE latin1_spanish_ci DEFAULT NULL
) DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci; ");

	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Crear tabla de profesores proveniente de seneca', NOW())");
}

/*
 @descripcion: A�adir fecha de soluci�n de la incidencia
 @fecha: 17 de febrero de 2017
 */

$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'A�adir fecha de soluci�n de la incidencia'");
if (! mysqli_num_rows($actua)) {
	mysqli_query($db_con, "ALTER TABLE `partesticgd` ADD `fecsol` DATE NULL DEFAULT NULL AFTER `idea`");

	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('A�adir fecha de soluci�n de la incidencia', NOW())");
}

/*
 @descripcion: A�adir id de fechoria enviada por correo o sms por jefatura de estudios o de manera autom�tica
 @fecha: 21 de febrero de 2017
 */

$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'A�adir id de fechoria enviada'");
if (! mysqli_num_rows($actua)) {
	mysqli_query($db_con, "ALTER TABLE `tutoria` ADD `enviado` INT(11) NULL DEFAULT NULL AFTER `jefatura`;");

	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('A�adir id de fechoria enviada', NOW())");
}

/*
 @descripcion: Modificaci�n de la estructura de los calendarios para introducir alumnos y eliminar los fallos de FN1 al serializar
 @fecha: 27 de febrero de 2017
 */

$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Modificacion calendarios'");
if (! mysqli_num_rows($actua)) {
	mysqli_query($db_con, "alter table calendario ENGINE=INNODB;");
	mysqli_query($db_con, "alter table asignaturas ENGINE=INNODB;");
	mysqli_query($db_con, "alter table departamentos ENGINE=INNODB;");
	mysqli_query($db_con, "alter table departamentos add primary key (idea);");
	mysqli_query($db_con, "alter table departamentos add index (nombre);");
	mysqli_query($db_con, "alter table asignaturas add PRIMARY key( CODIGO, CURSO);");
	mysqli_query($db_con, "alter table asignaturas add INDEX( CODIGO, CURSO);");
	mysqli_query($db_con, "alter table alma ENGINE=INNODB;");
	mysqli_query($db_con, "create table calendario_asignaturas ( 
		idcalendario int(11), 
		codasig varchar(10) COLLATE latin1_spanish_ci, 
		unidad varchar(255) COLLATE latin1_spanish_ci, 
		constraint capk PRIMARY KEY ( idcalendario, codasig, unidad), 
		constraint cafk1 FOREIGN key (idcalendario) REFERENCES calendario(id), 
		index(codasig,unidad)
		) ENGINE=INNODB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;");
	mysqli_query($db_con, "create table calendario_profesores ( 
		idcalendario int(11), 
		nombre varchar(48) COLLATE latin1_spanish_ci, 
		constraint cppk PRIMARY KEY ( idcalendario, nombre), 
		constraint cpfk1 FOREIGN key (idcalendario) REFERENCES calendario(id)
		) ENGINE=INNODB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;");
	mysqli_query($db_con, "alter table notas_cuaderno add idcalendario int;");
	mysqli_query($db_con, "ALTER TABLE `actividadalumno` CHANGE `cod_actividad` `idcalendario` INT(11) NOT NULL DEFAULT '0';");
	mysqli_query($db_con, "RENAME TABLE actividadalumno TO calendario_alumnos;");
	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Modificacion calendarios', NOW())");
}

/*
 @descripcion: Eliminacion de las columnas no normalizadas de los calendarios 
 @fecha: 10 de marzo de 2017
 */

$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Eliminacion de las columnas no normalizadas de los calendarios'");
if (! mysqli_num_rows($actua)) 
{
	$cons = "SELECT id, profesores, unidades, asignaturas, profesorreg FROM calendario";
	$result = mysqli_query($db_con, $cons);
	while($rowresult = mysqli_fetch_array($result))
	{
		$id = $rowresult[0];
		$profesoresr = $rowresult[1];
		$unidadesr = $rowresult[2];
		$asignaturasr = $rowresult[3];
		$profesorreg = $rowresult[4];
		if ($profesoresr != "")
		{
			$cons_prof = "insert into calendario_profesores values ";
			$profesores = explode(';', $profesoresr);
			$hay = 0;
			foreach ($profesores as $profesor) {
				if ($profesor != "")
				{
					if ($hay == 1)
						$cons_prof .= ",";
					$cons_prof .= "('".$id."','".trim(mysqli_real_escape_string($db_con, $profesor))."')";
					$hay = 1;					
				}
			}						
			if ($hay > 0)
				mysqli_query($db_con, $cons_prof);
		}
		// si hay unidad y no hay asignatura significa que es una actividad de grupo
		if ($unidadesr != "" and $asignaturasr == "")
		{
			$cons2 = "SELECT idcalendario from calendario_alumnos where idcalendario = ".$id;
			$result2 = mysqli_query($db_con, $cons2);

			if (mysqli_num_rows($result2)) 
			{
			}
			else
			{
				$cons_alum = "insert into calendario_alumnos (idcalendario, claveal) values ";
				$unidades = explode(';', $unidadesr);
				$hay = 0;
				foreach ($unidades as $unidad) {
					if ($unidad != "")
					{
						$cons3 = "SELECT claveal from alma where unidad = '".trim($unidad)."'";
						$result3 = mysqli_query($db_con, $cons3);
						while($rowclaveal = mysqli_fetch_array($result3))
						{	
							if ($hay == 1)
								$cons_alum .= ",";
							$cons_alum .= "('".$id."','".$rowclaveal[0]."')";
							$hay = 1;
						}

					}
				}						
				if ($hay > 0)
					mysqli_query($db_con, $cons_alum);

			}
		}	
		// si hay asignatura hay una unidad asociada a esa asignatura
		if ($asignaturasr != "")
		{
			$cons_asig = "insert into calendario_asignaturas values ";
			$unidad = substr($unidadesr,0,-1); 
			$asignatura = substr($asignaturasr,0,-1);
			$cons = "SELECT DISTINCT c_asig FROM horw,c_profes WHERE c_profes.PROFESOR = horw.prof and idea='".$profesorreg."' AND a_grupo = '$unidad' AND asig='$asignatura'";
					
			$result_asignatura = mysqli_query($db_con, $cons);
			$hay2 = 0;
			while($codasignatura = mysqli_fetch_array($result_asignatura)){	
				if ($hay2 == 1)
					$cons_asig .= ",";				
				$cons_asig .= "('".$id."','".trim($codasignatura[0])."','".trim($unidad)."') ";
				$hay2 = 1;
			}
			mysqli_query($db_con, $cons_asig);
		}	
	}
	mysqli_query($db_con, "alter table calendario drop profesores;");
	mysqli_query($db_con, "alter table calendario drop unidades;");
	mysqli_query($db_con, "alter table calendario drop asignaturas;");
	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Eliminacion de las columnas no normalizadas de los calendarios', NOW())");
}

/*
 @descripcion: Creaci�n de una tabla para las aulas
 @fecha: 11 de marzo de 2017
 */

$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Creaci�n de una tabla para las aulas'");
if (! mysqli_num_rows($actua)) {
	mysqli_query($db_con, "CREATE TABLE if not exists `aulas` (
  `idaula` int(12) UNSIGNED NOT NULL,
  `a_aula` varchar(32) COLLATE latin1_spanish_ci NOT NULL,
  `n_aula` varchar(64) COLLATE latin1_spanish_ci NOT NULL DEFAULT '',
  `texto` varchar(128) COLLATE latin1_spanish_ci DEFAULT '',
  `oculta` int(11) DEFAULT '0',
  `seneca` int(1) NOT NULL DEFAULT '1',
  `reserva` int(1) NOT NULL,
  `isinhorw` int(1) NOT NULL,
  PRIMARY KEY (`a_aula`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

	$result2 = mysqli_query($db_con, "SELECT iddependencia, nomdependencia, descdependencia FROM dependencias ORDER BY nomdependencia ASC"); 
	while ($row = mysqli_fetch_array($result2))
	{
		$result = mysqli_query($db_con, "SELECT DISTINCT a_aula, n_aula FROM aulas WHERE a_aula = '".$row['nomdependencia']."'"); 
		$aulas_hor = mysqli_num_rows($result);
		if ($aulas_hor)
		{
			mysqli_query($db_con, "update aulas set n_aula = '".$row['nomdependencia']."', idaula = '".$row['iddependencia']."' where a_aula = '".$row['nomdependencia']."'");
		}
		else
		{
			mysqli_query($db_con, "insert into aulas (idaula, a_aula, n_aula, texto, oculta, seneca, reserva, isinhorw) values ('".$row['iddependencia']."','".$row['nomdependencia']."','".$row['descdependencia']."', '', 0, 1, 0, 0)");
		}
  	}
	$cons = "SELECT DISTINCT a_aula FROM horw";
	$result = mysqli_query($db_con, $cons);
	$hay = 0;
	while($rowresult = mysqli_fetch_array($result))
	{
		$a_aula = $rowresult[0];
		if ($a_aula != "")
		{
			$sql = "update aulas set isinhorw = 1 where a_aula = '".$a_aula."'";
			//echo $sql;
			mysqli_query($db_con, $sql);											
		}
	}						
	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Creaci�n de una tabla para las aulas', NOW())");
}

/*
 @descripcion: Creaci�n de columna anual en reservas, cambio nombres columnas, eliminaci�n de la tabla de reservas_hor ya que es informaci�n duplicada
 @fecha: 12 de marzo de 2017
 */

$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Cambio nombre columnas reservas'");
if (! mysqli_num_rows($actua)) {
	mysqli_query($db_con, "alter table reservas add anual int(1) NOT NULL DEFAULT '0';");
	mysqli_query($db_con, "ALTER TABLE `reservas` CHANGE `event1` `hora1` varchar(64) NOT NULL default '';");
	mysqli_query($db_con, "ALTER TABLE `reservas` CHANGE `event2` `hora2` varchar(64) NOT NULL default '';");
	mysqli_query($db_con, "ALTER TABLE `reservas` CHANGE `event3` `hora3` varchar(64) NOT NULL default '';");
	mysqli_query($db_con, "ALTER TABLE `reservas` CHANGE `event4` `hora4` varchar(64) NOT NULL default '';");
	mysqli_query($db_con, "ALTER TABLE `reservas` CHANGE `event5` `hora5` varchar(64) NOT NULL default '';");
	mysqli_query($db_con, "ALTER TABLE `reservas` CHANGE `event6` `hora6` varchar(64) NOT NULL default '';");
	mysqli_query($db_con, "ALTER TABLE `reservas` CHANGE `event7` `hora7` varchar(64) NOT NULL default '';");
	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Cambio nombre columnas reservas', NOW())");
}

/*
 @descripcion: Eliminacion de la tabla FALUMNOS ya que es informaci�n duplicada y creaci�n columna NC en alma
 @fecha: 12 de marzo de 2017
 */

$actua = mysqli_query($db_con, "SELECT modulo FROM actualizacion WHERE modulo = 'Eliminacion FALUMNOS'");
if (! mysqli_num_rows($actua)) {
	mysqli_query($db_con, "alter table alma add nc double NULL AFTER `CLAVEAL`;");
	$cons = "select claveal, nc from FALUMNOS";
	$result = mysqli_query($db_con, $cons);
	$hay = 0;
	while($rowresult = mysqli_fetch_array($result))
	{
		$sql = "update alma set nc = ".$rowresult[1]." where claveal = '".$rowresult[0]."'";
		//echo $sql;
		mysqli_query($db_con, $sql);
	}

	mysqli_query($db_con, "INSERT INTO actualizacion (modulo, fecha) VALUES ('Eliminacion FALUMNOS', NOW())");
}

