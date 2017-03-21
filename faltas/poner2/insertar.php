<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed');


// Eliminamos de FALTAS las faltas borradas por el profesor. La tarea es cosa del fichero borrar.php
include("borrar.php");
// Conexi�n

// Contamos el total de variables que se han enviado desde el Formulario.
$total = count($_POST);
$claves = array_values($_POST);
$i = 4;

// Cambiamos el nombre del profe por su codigo
$trozos = explode("_ ",$profesor) ;
$id = $trozos[0];
$profesores = $trozos[1];

// Luego habr� que separar del total los dos campos que molestan para formar los bloques de 6 variables: profesor al principio y enviar al final. Por eso restamos 2.
while($i < $total - 2)
{

	// Dividimos los valores en grupos de 6, cada uno conteniendo todos los datos necesarios para una hora de un dia de la semana, con su fecha, nivel grupo, etc.
	$trozos = array_slice($claves, $i, 6);
	$cod_asig = $trozos[4];

	// Comienzan los controles para validar los datos.
	// Primero comprobamos si se han puesto faltas. Si no, pasamos al siguiente bloque.
	if (empty($trozos[2]))
	{
	}

	else {
		// Bloquear Fechas futuras y pasadas
		$fecha0 = explode('-',$trozos[0]);
		$dia0 = $fecha0[0];
		$mes = $fecha0[1];
		$a�o = $fecha0[2];
		$fecha1 = $a�o . "-" . $mes . "-" . $dia0;
		$fecha11 = $dia0 . "-" . $mes . "-" . $a�o;
		$fecha2 = mktime(0,0,0,$mes,$dia0,$a�o);
		$fecha22 = strtotime($config['curso_inicio']);
		$diames = date("j");
		$nmes = date("n");
		$nano = date("Y");
		$hoy1 = mktime(0,0,0,$nmes,$diames,$nano);

		// Fiestas del A�o, Vacaciones, etc.
		$comienzo_del_curso = strtotime($config['curso_inicio']);
		$final_del_curso = strtotime($config['curso_fin']);

		// Festivos
		$repe=mysqli_query($db_con, "select fecha from festivos where date(fecha) = date('$fecha1')");
		if (mysqli_num_rows($repe) > '0') {
			$dia_festivo='1';
		}
		if($dia_festivo=='1')
		{
			$mens_fecha = "No es posible poner o justificar Faltas en un <b>D�a Festivo</b> o en <b>Vacaciones</b>. Comprueba la Fecha: <b>$fecha11</b>";
		}
		elseif ($fecha2 < $fecha22) {
			$mens_fecha = "No es posible poner o justificar Faltas del Curso Anterior.<br>Comprueba la Fecha: <b>$fecha11</b>.";
		}
		elseif ($fecha2 > $hoy1) {
			$mens_fecha = "No es posible poner o justificar Faltas en el Futuro.<br>Comprueba la Fecha: <b>$fecha11</b>.";
		}
		else {
			// Pasamos fecha espa�ola a formato MySql
			$fecha0 = explode('-',$trozos[0]);
			$dia0 = $fecha0[0];
			$mes = $fecha0[1];
			$a�o = $fecha0[2];
			$fecha1 = $a�o . "-" . $mes . "-" . $dia0;

			// Caso de faltas para TODOS los alumnos en una hora
			if ($trozos[2] == "T" OR $trozos[2] == "t") {
				// Buscamos en alma los datos que necesitamos, claveal y nc, porque los dem�s los cogemos del formulario.
				$claveT ="select CLAVEAL, nc from alma where unidad = '$trozos[3]'  order by nc";
				$claveT0 = mysqli_query($db_con, $claveT);
				while ($claveT1 = mysqli_fetch_array($claveT0))
				{
					$clavealT = $claveT1[0];
					$ncT = $claveT1[1];
					// Comprobamos si se est� volviendo a meter una falta que ya ha sido metida.
					$duplicadosT = "select NC from FALTAS where unidad = '$trozos[3]'  and NC = '$ncT' and FECHA = '$fecha1' and FALTA = 'F'";
					$duplicadosT0 = mysqli_query($db_con, $duplicadosT);
					$duplicadosT1 = mysqli_num_rows($duplicadosT0);
					// O si hay al menos una justicaci�n introducida por el Tutor en ese d�a
					$jt ="select NC from FALTAS where unidad = '$trozos[3]'  and NC = '$ncT' and FECHA = '$fecha1' and FALTA = 'J'";
					$jt0 = mysqli_query($db_con, $jt);
					$jt1 = mysqli_num_rows($jt0);

					// Tiene actividad extraescolar en la fecha
					$hay_actividad="";
					$extraescolar=mysqli_query($db_con, "select idcalendario from calendario_alumnos where claveal = '$clavealT' and idcalendario in (select id from calendario where date(fechaini) <= date('$fecha1') and date(fechafin) >= date('$fecha1'))");
					if (mysqli_num_rows($extraescolar) > '0') {
						while($actividad = mysqli_fetch_array($extraescolar)){
							$tr = mysqli_query($db_con,"select id from calendario where id = '$actividad[0]' and (horaini<= (select hora_inicio from tramos where hora = '$trozos[5]') or horaini='00:00:00') and (horafin>= (select hora_fin from tramos where hora = '$trozos[5]') or horafin='00:00:00' )");
							if (mysqli_num_rows($tr)>0) {
								$hay_actividad = 1;
							}
						}
					}
					// Expulsado del Centro o Aula de Convivencia en la fecha
					$hay_expulsi�n="";
					$exp=mysqli_query($db_con, "select expulsion, aula_conv from Fechoria where claveal = '$clavealT' and ((expulsion > '0' and date(inicio) <= date('$fecha1') and date(fin) >= date('$fecha1')) or (aula_conv > '0' and date(inicio_aula) <= date('$fecha1') and date(fin_aula) >= date('$fecha1')))");
					if (mysqli_num_rows($exp) > '0') {
								$hay_expulsi�n = 1;
					}

					if ($hay_actividad==1){
						$mens_fecha = "No es posible poner Falta a algunos o todos los alumnos del grupo porque est�n registrados en una Actividad Extraescolar programada.";
					}
					elseif ($hay_expulsi�n==1){
						$mens_fecha = "No es posible poner Falta a algunos o todos los alumnos del grupo porque expulsados del Centro o est�n en el Aula de Convivencia.";
					}
					else{
						// Si la falta no se ha metido, insertamos los datos.
						if ($duplicadosT1 == "0" and $jt1 == "0") {
							$semana = date( mktime(0, 0, 0, $mes, $dia0, $a�o));
							$hoy = getdate($semana);
							$nombredia = $hoy[wday];

							// Comprobamos problema de varios c�digos en Bachillerato y otro
							$asig_bach = mysqli_query($db_con,"select distinct codigo from materias where nombre like (select distinct nombre from materias where codigo = '$cod_asig' limit 1) and grupo like '$trozos[3]' and abrev not like '%\_%'");
							while($cod_bch = mysqli_fetch_array($asig_bach)){
							$comb = mysqli_query($db_con,"select * from alma where claveal='$clavealT' and combasi like '%$cod_bch[0]%'");
							if (mysqli_num_rows($comb)>0) {
									$codigo_asignatura = $cod_bch[0];
								}
							}					
							if (strlen($codigo_asignatura)>0) {}
								else{
									$codigo_asignatura = $cod_asig;
								}

							/*// Comprobamos problema de c�digo en Bachillerato
							$bch = mysqli_query($db_con,"select curso from alma where unidad = '$trozos[3]'");
							$cur_bach = mysqli_fetch_array($bch);
							$curso_bach = $cur_bach[0];
							if (stristr($curso_bach,"Bach")==TRUE) {
								$bch_cod = mysqli_query($db_con,"select codigo from asignaturas where nombre like (select nombre from asignaturas where codigo = '$cod_asig') and curso = '$curso_bach' and abrev not like '%\_%'");
								$cod_bch = mysqli_fetch_array($bch_cod);
								$codigo_asignatura = $cod_bch[0];
							}
							else{
								$codigo_asignatura = $cod_asig;
							}*/

							// Insertamos las faltas de TODOS los alumnos.
							$t0 = "insert INTO  FALTAS (CLAVEAL, unidad, NC, FECHA, HORA, DIA, PROFESOR, CODASI, FALTA) VALUES ('$clavealT', '$trozos[3]', '$ncT', '$fecha1', '$trozos[5]', '$nombredia', '$id', '$codigo_asignatura', 'F')";

							mysqli_query($db_con, $t0) or die("No se ha podido insertar datos");
						}
						if ($jt1 > 0) {
							$mens4.="El Tutor ya ha justificado las Faltas del Alumno N� <b >$ncT</b> de <b >$trozos[3]</b> en ese d�a.<br>";
						}
					}
				}
			}
			else {
				// Cortamos los NC de los alumnos, en caso de que haya varias faltas.
				$nnc = explode(".",trim($trozos[2],"."));
				$nnc1 = array($nnc);
				// Contamos el n�mero de faltas puestas.
				$num =count($nnc);
				// Recorremos los distintos NC.
				$c = 0;
				while ($c < ($num)) {
					$nc = $nnc[$c];
					// Para cada falta, comprobamos si existe el alumno, o sea, si tiene CLAVEAL en alma.
					$clave ="select CLAVEAL from alma where unidad = '$trozos[3]'  AND nc = '$nc'";
					$clave0 = mysqli_query($db_con, $clave);
					$clave1 = mysqli_fetch_row($clave0);
					$claveal = $clave1[0];
					// Si la falta tiene formato num�rico, continamos (en el formato num�rico se excluyen signos como "-" o ",", por si el profe separa las faltas de otra manera que la correcta).
					if (is_numeric($nc))
					{
						// Si no existe el alumno, porque su CLAVEAL no aparece...
						if ( strlen($claveal) < 5)
						{
							// mensaje de error: no existe el Alumno.
							$mens1.="<b>$nc</b> no es el n�mero de ning�n alumno de <b>$trozos[3]</b>.<br>";
						}
						else {

							// Si hemos pasado los filtros, hay que comprobar si se est� volviendo a meter una falta que ya ha sido metida.
							$duplicados = "select NC, FALTA from FALTAS where unidad = '$trozos[3]' and NC = '$nc' and HORA = '$trozos[5]' and FECHA = '$fecha1' and FALTA = 'F'";
							//echo $duplicados."<br>";
							$duplicados0 = mysqli_query($db_con, $duplicados);
							$duplicados1 = mysqli_num_rows($duplicados0);
							// O si hay al menos una justicaci�n introducida por el Tutor en ese d�a
							$jt ="select NC from FALTAS where unidad = '$trozos[3]' and NC = '$nc' and FECHA = '$fecha1' and FALTA = 'J'";
							$jt0 = mysqli_query($db_con, $jt);
							$jt1 = mysqli_num_rows($jt0);
								
							// Tiene actividad extraescolar en la fecha
							$hay_actividad="";
							$extraescolar=mysqli_query($db_con, "select idcalendario from calendario_alumnos where claveal = '$claveal' and idcalendario in (select id from calendario where date(fechaini) >= date('$fecha1') and date(fechafin) <= date('$fecha1'))");
							if (mysqli_num_rows($extraescolar) > '0') {
								while($actividad = mysqli_fetch_array($extraescolar)){
									$tr = mysqli_query($db_con,"select id from calendario where id = '$actividad[0]' and horaini<= (select hora_inicio from tramos where hora = '$trozos[5]') and horafin>= (select hora_fin from tramos where hora = '$trozos[5]')");
									if (mysqli_num_rows($tr)>0) {
										$hay_actividad = 1;
									}
								}
							}

							// Expulsado del Centro o Aula de Convivencia en la fecha
							$hay_expulsi�n="";
							$exp=mysqli_query($db_con, "select expulsion, aula_conv from Fechoria where claveal = '$claveal' and ((expulsion > '0' and date(inicio) <= date('$fecha1') and date(fin) >= date('$fecha1')) or (aula_conv > '0' and date(inicio_aula) <= date('$fecha1') and date(fin_aula) >= date('$fecha1')))");
							if (mysqli_num_rows($exp) > '0') {
										$hay_expulsi�n = 1;
							}

							if ($hay_actividad==1){
								$mens_fecha = "No es posible poner Falta a algunos o todos los alumnos del grupo porque est�n registrados en una Actividad Extraescolar programada.";
							}
							elseif ($hay_expulsi�n==1){
								$mens_fecha = "No es posible poner Falta a alguno de los alumnos porque est�n expulsados del Centro o en el Aula de Convivencia.";
					}
							else{
								
								// Otras posibilidades
								if ($duplicados1 > 0 or $jt1 > 0)
								{
									$duplicados2 = mysqli_fetch_row($duplicados0);
									// La falta est� justificada
									if ($jt1 > 0) {
										$mens3.="El Tutor ya ha justificado las Faltas del Alumno N� <b>$nc</b> de <b>$trozos[3]</b> en ese d�a.<br>";
									}
									// La falta ha sido metida ya.
									elseif ($duplicados2[1] == "F") {
									}
								}
								else{
									$semana = date( mktime(0, 0, 0, $mes, $dia0, $a�o));
									$hoy = getdate($semana);
									$nombredia = $hoy[wday];

									// Comprobamos problema de varios c�digos en Bachillerato y otro
									$asig_bach = mysqli_query($db_con,"select distinct codigo from materias where nombre like (select distinct nombre from materias where codigo = '$cod_asig' limit 1) and grupo like '$trozos[3]' and abrev not like '%\_%'");
									while($cod_bch = mysqli_fetch_array($asig_bach)){
									$comb = mysqli_query($db_con,"select * from alma where claveal='$claveal' and combasi like '%$cod_bch[0]%'");
									if (mysqli_num_rows($comb)>0) {
											$codigo_asignatura = $cod_bch[0];
										}
									}					
									if (strlen($codigo_asignatura)>0) {}
										else{
											$codigo_asignatura = $cod_asig;
										}

									/*// Comprobamos problema de c�digo en Bachillerato
									$bch = mysqli_query($db_con,"select curso from alma where unidad = '$trozos[3]'");
									$cur_bach = mysqli_fetch_array($bch);
									$curso_bach = $cur_bach[0];
									if (stristr($curso_bach,"Bach")==TRUE) {
										$bch_cod = mysqli_query($db_con,"select codigo from asignaturas where nombre like (select nombre from asignaturas where codigo = '$cod_asig') and curso = '$curso_bach' and abrev not like '%\_%'");
										$cod_bch = mysqli_fetch_array($bch_cod);
										$codigo_asignatura = $cod_bch[0];
									}
									else{
										$codigo_asignatura = $cod_asig;
									}*/
									

									$insert = "insert INTO  FALTAS (  CLAVEAL , unidad ,   NC ,  FECHA ,  HORA , DIA,  PROFESOR ,  CODASI ,  FALTA ) VALUES ('$claveal',  '$trozos[3]',  '$nc',  '$fecha1',  '$trozos[5]', '$nombredia',  '$id',  '$codigo_asignatura', 'F')";
									//echo $insert."<br>";
									mysqli_query($db_con, $insert) or die("No se ha podido insertar datos");
								}
							}
						}
					}
					else {
						// Mensaje de error: no es un n�mero correcto, porque no existe ("45", o un alumno que se ha dado de baja, o bien porque se han incluido letras o signos inv�lidos)
						$mens2.="<b>$nc</b> no es el n�mero de ning�n alumno de <b>$trozos[3]</b>.<br>";
					}
					$c++;
				}
			}
		}
	}
	// Pasamos al siguiente bloque de 6 variables hasta el final
	$i += 6;
}
if(!(empty($mens_fecha)) or !(empty($mens1)) or !(empty($mens2)) or !(empty($mens3)) or !(empty($mens4))){}
else{$registro = 1;}
?>