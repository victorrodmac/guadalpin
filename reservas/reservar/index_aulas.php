<?php
require('../../bootstrap.php');


$pr = $_SESSION['profi'];

include("../../menu.php");
include("../menu.php");

if (isset($_POST['enviar']) or isset($_GET['enviar'])) 
{

	for ($i=1;$i<=7;$i++)
	{
		if (isset($_POST['day_event'.$i]) and strstr($_POST['day_event'.$i],"Asignada")==FALSE) 
		{ 
			${day_event.$i} = $_POST['day_event'.$i]; 
		}
		elseif (isset($_GET['day_event'.$i]) and strstr($_GET['day_event'.$i],"Asignada")==FALSE) 
		{ 
			${day_event.$i} = $_GET['day_event'.$i]; 
		}
		else{${day_event.$i}="";}
	}

	if (isset($_GET['month'])) { $month = intval($_GET['month']); }
	if (isset($_POST['month'])) { $month = intval($_POST['month']); }

	if (isset($_GET['year'])) { $year = intval ($_GET['year']); }
	if (isset($_POST['year'])) { $year = intval ($_POST['year']); }

	if ($year < 1990) { $year = 1990; } if ($year > 2035) { $year = 2035; }

	if (isset($_GET['today'])) { $today = intval ($_GET['today']); }
	if (isset($_POST['today'])) { $today = intval ($_POST['today']); }


	$month = (isset($month)) ? $month : date("n",time());
	$year = (isset($year)) ? $year : date("Y",time());
	$today = (isset($today))? $today : date("j", time());

	$sql_date = "$year-$month-$today";
	$semana = date( mktime(0, 0, 0, $month, $today, $year));
	$hoy = getdate($semana);
	$numero_dia = $hoy['wday'];

	$eventQuery = "SELECT id FROM `reservas` WHERE eventdate = '$sql_date' and servicio='$servicio'";
	//echo $eventQuery;
	$eventExec = mysqli_query($db_con, $eventQuery);
	$event_found = "";
	while($row = mysqli_fetch_array($eventExec)) {
		$event_found = 1;
	}
	$day_event_safe1 = addslashes($day_event1);
	$day_event_safe2 = addslashes($day_event2);
	$day_event_safe3 = addslashes($day_event3);
	$day_event_safe4 = addslashes($day_event4);
	$day_event_safe5 = addslashes($day_event5);
	$day_event_safe6 = addslashes($day_event6);
	$day_event_safe7 = addslashes($day_event7);
	//$aula = htmlspecialchars($aula);

	if ($event_found == 1) {
		//UPDATE
		$postQuery = "UPDATE `reservas` SET hora1 = '".$day_event1."', hora2 = '".$day_event2."', hora3 = '".$day_event3."', hora4 = '".$day_event4."', hora5 = '".$day_event5."', hora6 = '".$day_event6."', hora7 = '".$day_event7."' WHERE eventdate = '$sql_date' and servicio='$servicio';";
		// echo $postQuery;
		$postExec = mysqli_query($db_con, $postQuery) or die("Could not Post UPDATE Event to database!");
		mysqli_query($db_con, "DELETE FROM `reservas` WHERE hora1 = '' and hora2 = ''  and hora3 = ''  and hora4 = ''  and hora5 
= ''  and hora6 = ''  and hora7 = '' and servicio='$servicio' ");
		$mens="actualizar";
	} else 
	{
		//INSERT
		//no tengo ni idea para que sirve la columna html
		$show_html = 0;
		$postQuery = "INSERT INTO `reservas` (eventdate,dia,hora1, hora2, hora3, hora4, hora5, hora6, hora7,html, servicio, anual) VALUES 
('$sql_date','$numero_dia','".$day_event1."','".$day_event2."','".$day_event3."','".$day_event4."','".$day_event5."','".$day_event6."','".$day_event7."','$show_html', '$servicio', 0)";
		echo $postQuery;
		$postExec = mysqli_query($db_con, $postQuery) or die("Could not Post INSERT `reservas` Event to database!");

		mysqli_query($db_con, "DELETE FROM `reservas` WHERE hora1 = '' and hora2 = ''  and hora3 = ''  and hora4 = ''  and hora5 
= ''  and hora6 = ''  and hora7 = '' and servicio='$servicio' ");
		$mens="insertar";
	}
}

if (isset($_POST['permanente'])) 
{
	$numero_dia = $_POST['numero_dia'];
	// Comprobamos que existe la tabla del aula
	$tabla_perm = "reservas";
	$reg = mysqli_query($db_con,"select distinct servicio from reservas where anual = 0");
	while ($t_reg = mysqli_fetch_array($reg)){
		if ($t_reg[0]==$tabla_perm){$existe = "1";}
	}

	// Insertamos datos despu�s de borrar la fila del d�a
	mysqli_query($db_con,"delete from reservas where anual = 1 and dia='$numero_dia' and servicio = '$servicio'");

	//INSERT
	for ($i = 1; $i < 8; $i++) 
	{
		if (strstr($_POST['day_event'.$i],"Horario")==TRUE) {
			$_POST['day_event'.$i]="";
		}
	}
	$post_perm = "INSERT INTO reservas (eventdate,dia,hora1, hora2, hora3, hora4, hora5, hora6, hora7,html, servicio, anual)  VALUES ('1960-01-01','$numero_dia','".$_POST['day_event1']."','".$_POST['day_event2']."','".$_POST[
'day_event3']."','".$_POST['day_event4']."','".$_POST['day_event5']."','".$_POST['day_event6']."','".$_POST['day_event7']."','0','".$servicio."', 1)";
	echo $post_perm;
	$exec_permanente = mysqli_query($db_con, $post_perm) or die("Could not Post INSERT Event to database!");

}


if (isset($_GET['month'])) 
{ 
	$month = $_GET['month']; 
	$month = preg_replace("/[[:space:]]/", "", $month); $month = preg_replace ("/[[:punct:]]/", "", $month); 
	$month = preg_replace ("/[[:alpha:]]/", "", $month); 
}
if (isset($_GET['year'])) 
{ 
	$year = $_GET['year']; 
	$year = preg_replace("/[[:space:]]/", "", $year); 
	$year = preg_replace ("/[[:punct:]]/", "", $year); 
	$year = preg_replace ("/[[:alpha:]]/", "", $year); 
	if ($year < 1990) 
	{ 
		$year = 1990; 
	} 
	if ($year > 2035) 
	{ 
		$year = 2035; 
	}
}
if (isset($_GET['today'])) 
{ 
	$today = $_GET['today']; 
	$today = preg_replace("/[[:space:]]/", "", $today); 
	$today = preg_replace ("/[[:punct:]]/", "", $today); 
	$today = preg_replace ("/[[:alpha:]]/", "", $today); 
}

$month = (isset($month)) ? $month : date("n",time());
$year = (isset($year)) ? $year : date("Y",time());
$today = (isset($today))? $today : date("j", time());
$daylong = date("l",mktime(1,1,1,$month,$today,$year));
$monthlong = date("F",mktime(1,1,1,$month,1,$year));
$dayone = date("w",mktime(1,1,1,$month,1,$year))-1;
$numdays = date("t",mktime(1,1,1,$month,1,$year));
$alldays = array('Lun','Mar','Mi�','Jue','Vie','S�b','Dom');
$next_year = $year + 1;
$last_year = $year - 1;
if ($daylong == "Sunday")
{
	$daylong = "Domingo";
}
elseif ($daylong == "Monday")
{
	$daylong = "Lunes";
}
elseif ($daylong == "Tuesday")
{
	$daylong = "Martes";
}
elseif ($daylong == "Wednesday")
{
	$daylong = "Mi�rcoles";
}
elseif ($daylong == "Thursday")
{
	$daylong = "Jueves";
}
elseif ($daylong == "Friday")
{
	$daylong = "Viernes";
}
elseif ($daylong == "Saturday")
{
	$daylong = "S�bado";
}


if ($monthlong == "January")
{
	$monthlong = "Enero";
}
elseif ($monthlong == "February")
{
	$monthlong = "Febrero";
}
elseif ($monthlong == "March")
{
	$monthlong = "Marzo";
}
elseif ($monthlong == "April")
{
	$monthlong = "Abril";
}
elseif ($monthlong == "May")
{
	$monthlong = "Mayo";
}
elseif ($monthlong == "June")
{
	$monthlong = "Junio";
}
elseif ($monthlong == "July")
{
	$monthlong = "Julio";
}
if ($monthlong == "August")
{
	$monthlong = "Agosto";
}
elseif ($monthlong == "September")
{
	$monthlong = "Septiembre";
}
elseif ($monthlong == "October")
{
	$monthlong = "Octubre";
}
elseif ($monthlong == "November")
{
	$monthlong = "Noviembre";
}
elseif ($monthlong == "December")
{
	$monthlong = "Diciembre";
}
if ($today > $numdays) { $today--; }
if ($servicio) {
	$aula = $servicio;
	$nombre_aula = $servicio;
	$n_servicio = strtoupper($servicio);
}else{
	$tr_serv = explode(" ==> ",$servicio_aula);
	$servicio=$tr_serv[0];
	$nombre_aula = $tr_serv[1];
	$aula = $tr_serv[0];
	$n_servicio = strtoupper($tr_serv[0]);
}
// Comprobamos que existe la tabla del aula
$reg = mysqli_query($db_con,"select distinct servicio from reservas where anual = 0");
while ($t_reg = mysqli_fetch_array($reg)){
	if ($t_reg[0]==$servicio){$existe = "1";}
}

// Estructura de la Tabla
?>

<div class="container">

	<div class="page-header">
		<h2>Sistema de Reservas <small> Reserva de <?php echo $servicio; ?>	</small>
		</h2>
	</div>

<?php 
	if (isset($mens))
	{
		if ($mens == 'actualizar')
		{
			echo '<div class="alert alert-success">La reserva se ha actualizado correctamente.</div>';
		}
		elseif ($mens == 'insertar')
		{
			echo '<div class="alert alert-success">La reserva se ha realizado correctamente.</div>';
		}
	}
?>

	<div class="row">
		<div class="col-sm-5">
<?php
			$mes_sig = $month+1;
			$mes_ant = $month-1;
			$ano_ant = $ano_sig = $year;
			if ($mes_ant == 0) {
				$mes_ant = 12;
				$ano_ant = $year-1;
			}
			if ($mes_sig == 13) {
				$mes_sig = 1;
				$ano_sig = $year+1;
			}

			//Nombre del Mes
			echo "<table class=\"table table-bordered table-centered\"><thead><tr>";
			echo "<th><h4><a href=\"".$_SERVER['PHP_SELF']."?servicio=$aula&year=".$ano_ant."&month=".$mes_ant."\"><span class=\"fa fa-arrow-circle-left fa-fw fa-lg\"></span></a></h4></th>";
			echo "<th colspan=\"5\"><h4>".$monthlong.' '.$year."</h4></th>";
			echo "<th><h4><a href=\"".$_SERVER['PHP_SELF']."?servicio=$aula&year=".$ano_sig."&month=".$mes_sig."\"><span class=\"fa fa-arrow-circle-right fa-fw fa-lg\"></span></a></h4></th>";
			echo "</tr><tr>";


			//Nombre de D�as
			foreach($alldays as $value) {
				echo "<th>
				$value</th>";
			}
			echo "</tr></thead><tbody><tr>";


			//D�as vac�os
			if ($dayone < 0) $dayone = 6;
			for ($i = 0; $i < $dayone; $i++) {
				echo "<td>&nbsp;</td>";
			}

			//D�as
			for ($zz = 1; $zz <= $numdays; $zz++) 
			{
				if ($i >= 7) 
				{  
					print("</tr><tr>"); 
					$i=0; 
				}

				// Enlace
				$enlace = $_SERVER['PHP_SELF'].'?year='.$year.'&today='.$zz.'&month='.$month.'&servicio='.$aula;

				// Mirar a ver si hay alguna ctividad en el d�as
				$result_found = 0;
				if ($zz == $today) {
					echo '<td class="calendar-today"><a 
			href="'.$enlace.'">'.$zz.'</a></td>';
					$result_found = 1;
				}

				// Enlace
				$enlace = $_SERVER['PHP_SELF'].'?year='.$year.'&today='.$zz.'&month='.$month.'&servicio='.$aula;

				if ($result_found != 1) 
				{
					//Buscar actividad para el d�a y marcarla
					$sql_currentday = "$year-$month-$zz";

					$eventQuery = "SELECT hora1, hora2, hora3, hora4, hora5, hora6, hora7 FROM `reservas` WHERE eventdate = '$sql_currentday' and servicio='$servicio';";
					$eventExec = mysqli_query($db_con, $eventQuery );
					if (mysqli_num_rows($eventExec)>0) {
						while ( $row = mysqli_fetch_array ( $eventExec ) ) {
							echo '<td class="calendar-orange"><a href="'.$enlace.'">'.$zz.'</a></td>';
							$result_found = 1;
						}
					}
					else{
						$sql_currentday = "$year-$month-$zz";
						$fest = mysqli_query($db_con, "select distinct fecha, nombre from $db.festivos WHERE fecha = '$sql_currentday'");
						if (mysqli_num_rows($fest)>0) {
							$festiv=mysqli_fetch_array($fest);
							echo '<td class="calendar-red">'.$zz.'</td>';
							$result_found = 1;
						}
					}
						
				}

				if ($result_found != 1) {
					echo '<td><a href="'.$enlace.'">'.$zz.'</a></td>';
				}

				$i++; $result_found = 0;
			}

			$create_emptys = 7 - (($dayone + $numdays) % 7);
			if ($create_emptys == 7) { $create_emptys = 0; }

			if ($create_emptys != 0) {
				echo "<td colspan=\"$create_emptys\">&nbsp;</td>";
			}

			echo "</tr></tbody>";
			echo "</table>";
			echo "";
?>
		</div>

		<div class="col-sm-7">

			<div class="well">
<?php
				echo "<form method=\"post\" action=\"index_aulas.php?servicio=$aula&year=$year&today=$today&month=$month\" name=\"jcal_post\">";
				echo "<legend>Reserva para el $daylong, $today de $monthlong</legend><br />";
				$sql_date = "$year-$month-$today";
				$semana = date( mktime(0, 0, 0, $month, $today, $year));
				$hoy = getdate($semana);
				$numero_dia = $hoy['wday'];
				$eventQuery = "SELECT hora1, hora2, hora3, hora4, hora5, hora6, hora7, html FROM `reservas` WHERE eventdate = '$sql_date' and servicio='$servicio'";
				$eventExec = mysqli_query($db_con, $eventQuery);
				while($row = mysqli_fetch_array($eventExec)) {
					$event_event1 = stripslashes($row["hora1"]);
					$event_event2 = stripslashes($row["hora2"]);
					$event_event3 = stripslashes($row["hora3"]);
					$event_event4 = stripslashes($row["hora4"]);
					$event_event5 = stripslashes($row["hora5"]);
					$event_event6 = stripslashes($row["hora6"]);
					$event_event7 = stripslashes($row["hora7"]);
				}
				if($_SESSION['profi'] == 'conserje' or stristr($_SESSION['cargo'],'1') == TRUE){
					$SQL = "select distinct nombre from $db.departamentos order by nombre";
				}
				else{
					$SQL = "select distinct nombre from $db.departamentos where nombre = '".$_SESSION['profi'] ."'";
				}

				if($aula)
				{
					for ($i = 1; $i < 8; $i++)
					{
						$num_aula_hor=0;
						$num_hor=0;

						echo '<div class="form-group">';

						// Comprobamos reserva definitiva del aula
						$aula_hor = "SELECT hora$i FROM reservas WHERE anual = 1 and dia = '$numero_dia' and servicio='$servicio'";
						//echo $aula_hor."<br>";
						$res_aula_hor = mysqli_query($db_con, $aula_hor);
						$num_aula_hor = mysqli_fetch_row($res_aula_hor);

						// Comprobamos horario de profesores
						$hor = "SELECT distinct a_grupo, a_asig FROM $db.horw WHERE dia = '$numero_dia' and hora='$i' and a_aula = '$aula' and a_grupo is not null and a_grupo not like 'G%'";
						//echo $hor;
						$res_hor = mysqli_query($db_con, $hor);
						$grupo_aula="";
						if (mysqli_num_rows($res_hor)>0) {
							$num_hor = mysqli_fetch_row($res_hor);
							$grupo_aula = " (".$num_hor[1].")";
						}
						// El profesor es Administrador
						if(stristr($_SESSION['cargo'],'1') == TRUE)
						{
							echo "<label>".$i."� hora</label> &nbsp;&nbsp; <select name=\"day_event$i\" class='form-control'>";
							if (strlen($grupo_aula)>0) {
								echo "<option>Asignada por Horario: $grupo_aula</option>";
							}		
							elseif(strlen(${event_event.$i})>0)
							{
								echo "<option>".${event_event.$i}."</option>";
							}
							elseif (strlen($num_aula_hor[0])>0) 
							{
								echo "<option>Asignada por Direcci�n: $num_aula_hor[0]</option>";
							}		
							else{
								echo "<option></option>";
							}
							$result1 = mysqli_query($db_con, $SQL);
							while($row1 = mysqli_fetch_array($result1)){
								$profesor = $row1[0];
								echo "<option>" . $profesor . "</option>";
							}
							echo "</select>";
						}
						else
						{
							if (strlen($num_hor[0])>0) 
							{
								echo "<label>".$i."� hora</label> &nbsp;&nbsp; <p class=\"help-block text-info\">Asignada por horario</p>";
							}
							elseif (strlen($num_aula_hor[0])>0) 
							{
								echo "<label>".$i."� hora</label> &nbsp;&nbsp; <p class=\"help-block text-danger\">Asignada por Direcci�n</p>";
							}
							else
							{
								if (${event_event.$i}  == "") 
								{
									echo "<label>".$i."� hora</label> &nbsp;&nbsp; <select name=\"day_event$i\" class='form-control'>";
									echo "<option></option>";
									$result1 = mysqli_query($db_con, $SQL);
									while($row1 = mysqli_fetch_array($result1)){
										$profesor = $row1[0];
										echo "<option>" . $profesor . "</option>";
									}
									echo "</select>";
								}
								else 
								{
									if(mb_strtolower($pr) == mb_strtolower(${event_event.$i})) 
									{
										echo "<label>".$i."� hora</label> &nbsp;&nbsp; <input class='form-control' type='text' name=\"day_event$i\" value=\"${event_event.$i}\">";
									}
									else
									{
										echo "<label>".$i."� hora</label> &nbsp;&nbsp; <input disabled class='form-control' type='text' value='${event_event.$i}'><input type=\"hidden\" value=\"${event_event.$i}\" name=\"day_event$i\">";
									}
								}
							}
						}
						echo '</div>';
					}
				}

				echo "<input type=\"hidden\" value=\"$year\" name=\"year\">
				      <input type=\"hidden\" value=\"$month\" name=\"month\">
				      <input type=\"hidden\" value=\"$today\" name=\"today\">
				      <input type=\"hidden\" value=\"$numero_dia\" name=\"numero_dia\">
				      <input type=\"submit\" class=\"btn btn-primary\" id=\"formsubmit\" name=\"enviar\" value=\"Reservar\">";

				if (stristr($_SESSION['cargo'],'1') == TRUE) 
				{
					echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" class=\"btn btn-danger\" id=\"formsubmit1\" name=\"permanente\" value=\"Reservar todo el Curso\">";;
				}

				echo "</form>";
				echo "</div>";
?>
			</div>
		</div>
<?php
		if ($_SERVER['SERVER_NAME']=="iesmonterroso.org" and $servicio=="AMAG") 
		{
?>
			<h3>NUEVAS NORMAS DE USO Y FUNCIONAMIENTO PARA EL AULA MAGNA</h3>

			<p class="text-justify">Dadas las necesidades de espacios educativos que
				nuestro instituto ha ido teniendo en los �ltimos cursos, el uso del Aula
				Magna se fue incrementando y esa masificaci�n e indiscriminaci�n en su
				utilizaci�n deriv� en algunas situaciones que han hecho replantearnos
				algunos de los protocolos que ten�amos para un espacio tan fr�gil como
				es el Aula Magna. De hecho, el centro ha tenido que hacer frente a
			varias facturas de diferentes empresas que han tenido que reparar
			algunos de sus elementos: persianas, sonido, iluminaci�n, alba�iler�a...
			</p>

			<p class="text-justify">Con la intenci�n de reconducir la situaci�n,
			publicamos una serie de normas de cara a su posible utilizaci�n por
			parte de miembros de la comunidad educativa del centro. Son, intentando
			recrear un orden cronol�gico, las siguientes:</p>

			<ul>
				<li class="text-justify">Reserva del Aula Magna. Si el uso es para la
				ma�ana ya no se puede llevar a cabo a trav�s del m�dulo que hay en la
				Intranet puesto que se ha deshabilitado esa opci�n. Este tipo de
				reserva matutina solo se puede hacer hablando con el alguien del
				equipo directivo o la persona responsable del D.A.C.E., salvo las ya
				asignadas por horario del centro desde el inicio de curso (Taller de
				Teatro, por ejemplo). Si la utilizaci�n es para casos excepcionales
				(ex�menes de varios grupos...) para la tarde, s� que se debe hacer
				usando el m�dulo de la Intranet, marcando la 7 hora.</li>

				<li class="text-justify">Si la reserva del Aula Magna implica la
				necesidad del uso de medios audiovisuales ( llave de la mesa de sonido,
				la mesa de sonido, megafon�a, pantalla, ordenador, videoproyector,
				mando de aire acondicionado/calefacci�n...), se deber� contactar con
				M. Carmen Gal�n, del Departamento de Actividades Complementarias y
				Extraescolares, o Paco P�rez, vicedirector, puesto que su custodia y
				mantenimiento solo lo llevan ellos. Es por eso que la devoluci�n del
				citado material, en el mismo estado en el que fue entregado, se har� a
				uno de ellos dos.</li>

				<li class="text-justify">Cuando se coja la llave de Conserjer�a para
				acceder al Aula Magna, adem�s de apuntarse en el listado
				correspondiente se tendr� la precauci�n de abrir ambas puertas (la
				misma llave abre las dos puertas verdes acristaladas) y la doble hoja,
				aunque haya que dejarlas entornadas por motivos de necesidad de una
				opacidad en el Aula. Esto es para evitar problemas en el caso de
				evacuaci�n por emergencia. No estamos hablando de la llave ni de la
				puerta de acceso al parking de profesores. Esa llave tambi�n se
				encuentra en el juego de llaves del Aula Magna en la Conserjer�a.</li>

				<li class="text-justify">Comprobar si la alarma est� o no activada.
				Habr�a que preguntar antes la clave de la alarma si no supiera con
				anterioridad. Aparece un mensaje en la pantalla de la alarma que
				dice: "Sistema Activo".</li>

				<li class="text-justify">En el caso de que haya que subir las
				persianas, abrir las ventanas y/o descorrer las cortinas rojas, se
				encargar� de ello �nica y exclusivamente el profesor responsable de la
				actividad para evitar malos usos. Del mismo modo se proceder� cuando
				toque bajar las persianas, cerrar las ventanas y/o correr las cortinas.</li>

				<li class="text-justify">Se deber� mantener la limpieza en el Aula
				Magna de forma que el alumnado utilice las papeleras que hay en la
				entrada y en los servicios. Asimismo, se intentar� mantener el orden y
				la disposici�n de las sillas-palas para futuros usos. No es de recibo
				que uno vaya al Aula Magna a una conferencia con el alcalde de
				Estepona, por ejemplo, y el Aula Magna est� sucia y/o desordenada.</li>

				<li class="text-justify">Comprobar antes de dejar el Aula Magna que
				todo est� como debiera estar (luces apagadas, persianas echadas,
				ventanas cerradas...) y, antes de salir, poner la alarma si es
				necesario.</li>
			</ul>

			<p class="text-justify">Agradecemos de antemano la predisposici�n de
			todos para con el cuidado de este importante espacio educativo de
			nuestro instituto. Tan �nico como valioso!</p>

<?php
		}
?>
	</div>
</div>

<?php include("../../pie.php"); ?>
</body>
</html>
