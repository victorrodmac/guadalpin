<?php
require('../../bootstrap.php');


$pr = $_SESSION['profi'];


include("../../menu.php");
include("../menu.php");

if (isset($_GET['month'])) 
{ 
	$month = $_GET['month']; 
	$month = preg_replace ("/[[:space:]]/", "", $month); 
	$month = preg_replace ("/[[:punct:]]/", "", $month); 
	$month = preg_replace ("/[[:alpha:]]/", "", $month); 
}
if (isset($_GET['year'])) 
{ 
	$year = $_GET['year']; 
	$year = preg_replace ("/[[:space:]]/", "", $year); 
	$year = preg_replace ("/[[:punct:]]/", "", $year); 
	$year = preg_replace ("/[[:alpha:]]/", "", $year); 
	if ($year < 1990) { $year = 1990; } 
	if ($year > 2035) { $year = 2035; } 
}

if (isset($_GET['today'])) 
{ 
	$today = $_GET['today']; 
	$today = preg_replace ("/[[:space:]]/", "", $today); 
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

// Estructura de la Tabla
?>

<div class="container">

	<div class="page-header">
	  <h2>Sistema de Reservas <small> Reserva de <?php echo $servicio; ?></small></h2>
	</div>
<?php
	if (isset($_GET['mens']))
	{
		if ($_GET['mens'] == 'actualizar')
		{
			echo '<div class="alert alert-success">La reserva se ha actualizado correctamente.</div>';
		}
		elseif ($_GET['mens'] == 'insertar')
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
			echo "<th><h4><a href=\"".$_SERVER['PHP_SELF']."?servicio=$servicio&year=".$ano_ant."&month=".$mes_ant."\"><span class=\"fa fa-arrow-circle-left fa-fw fa-lg\"></span></a></h4></th>";
			echo "<th colspan=\"5\"><h4>".$monthlong.' '.$year."</h4></th>";
			echo "<th><h4><a href=\"".$_SERVER['PHP_SELF']."?servicio=$servicio&year=".$ano_sig."&month=".$mes_sig."\"><span class=\"fa fa-arrow-circle-right fa-fw fa-lg\"></span></a></h4></th>";
			echo "</tr><tr>";
			
			
			//Nombre de Días
			foreach($alldays as $value) {
			  echo "<th>
			  $value</th>";
			}
			echo "</tr></thead><tbody><tr>";
			
			
			//Días vacíos
			if ($dayone < 0) $dayone = 6;
			for ($i = 0; $i < $dayone; $i++) 
			{
			  echo "<td>&nbsp;</td>";
			}
			
			//Días
			for ($zz = 1; $zz <= $numdays; $zz++) 
			{
				if ($i >= 7) {  print("</tr><tr>"); $i=0; }
			  
				// Enlace
				$enlace = $_SERVER['PHP_SELF'].'?year='.$year.'&today='.$zz.'&month='.$month.'&servicio='.$servicio;
				  
				// Mirar a ver si hay alguna ctividad en el días
				$result_found = 0;
				if ($zz == $today) 
				{ 
					echo '<td class="calendar-today"><a href="'.$enlace.'">'.$zz.'</a></td>';
				    $result_found = 1;
				}
		  
	  // Enlace
				$enlace = $_SERVER['PHP_SELF'].'?year='.$year.'&today='.$zz.'&month='.$month.'&servicio='.$servicio;
				  
				if ($result_found != 1) 
				{ 
					//Buscar actividad para el d�a y marcarla
					$sql_currentday = "$year-$month-$zz";
				
				    $eventQuery = "SELECT hora1, hora2, hora3, hora4, hora5, hora6, hora7 FROM `reservas` WHERE eventdate = '$sql_currentday' and servicio='$servicio';";
					$eventExec = mysqli_query($db_con, $eventQuery );
					if (mysqli_num_rows($eventExec)>0) 
					{
						while ( $row = mysqli_fetch_array ( $eventExec ) ) 
						{
				        	echo '<td class="calendar-orange"><a href="'.$enlace.'">'.$zz.'</a></td>';				
							$result_found = 1;
						}
					}	
					else
					{
						$sql_currentday = "$year-$month-$zz";
						$fest = mysqli_query($db_con, "select distinct fecha, nombre from $db.festivos WHERE fecha = '$sql_currentday'");
						if (mysqli_num_rows($fest)>0) 
						{
							$festiv=mysqli_fetch_array($fest);
							echo '<td class="calendar-red">'.$zz.'</td>';
							$result_found = 1;
						}	
					}
				}
	
				if ($result_found != 1) {
				    echo '<td><a href="'.$enlace.'">'.$zz.'</a></td>';
				}
				
				$i++; 
				$result_found = 0;
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
				echo "<form method=\"post\" action=\"jcal_post.php?servicio=$servicio&year=$year&today=$today&month=$month\" name=\"jcal_post\">";
				echo "<legend>Reserva para el $daylong, $today de $monthlong</legend><br />";	
				$sql_date = "$year-$month-$today";
				$semana = date( mktime(0, 0, 0, $month, $today, $year));
				$hoy = getdate($semana);
				$numero_dia = $hoy['wday'];
				$eventQuery = "SELECT hora1, hora2, hora3, hora4, hora5, hora6, hora7, html FROM reservas WHERE eventdate = '$sql_date' and servicio='$servicio' and anual = 0";
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

				if($_SESSION['profi'] == 'conserje' or stristr($_SESSION['cargo'],'1') == TRUE)
				{
					$SQL = "select distinct nombre from $db.departamentos order by nombre";
				}
				else
				{
					$SQL = "select distinct nombre from $db.departamentos where nombre = '". $_SESSION['profi'] ."'";
				}

				if($servicio)
				{
					$eventQuery2 = "SELECT hora1,hora2,hora3,hora4,hora5,hora6,hora7 FROM reservas WHERE dia = '$numero_dia' and servicio='$servicio' and anual=1";
					$reservado0 = mysqli_query($db_con, $eventQuery2);
					while ($reservado1 = mysqli_fetch_row($reservado0))
					{
						for ($i = 0; $i < 7; $i++)
						{
							$sig = $i +1;
							echo '<div class="form-group">';
							if($reservado1[$i] = "") 
							{
								echo "<label>".$sig."� hora</label> &nbsp;&nbsp; <span class='badge badge-warning'>$reservado1[$i]</span>"; 
							}
							else
							{
								if (empty(${"event_event$sig"})) 
								{ 
									echo "<label>".$sig."� hora</label> &nbsp;&nbsp; <select name=\"day_event".$sig."\" class=\"form-control\"><option></option>";
									$result1 = mysqli_query($db_con, $SQL);
									while($row1 = mysqli_fetch_array($result1))
									{ 
										$profesor = $row1[0];
										echo "<option>" . $profesor . "</option>";
									} 
									echo "</select>";
								} 
								else 
								{
									if(mb_strtolower($pr) == mb_strtolower(${"event_event$sig"})) 
									{
										echo "<label>".$sig."� hora</label> &nbsp;&nbsp; <div class=\"form-group\"><input class=\"form-control\" type=\"text\" name=\"day_event".$sig."\"  value=\"".${"event_event$sig"}."\"></div>";
									} 
									else
									{
										echo "<label>".$sig."� hora</label> &nbsp;&nbsp; <div class=\"form-group\"><input disabled class=\"form-control\" type=\"text\"  value='".${"event_event$sig"}."'></div><input type=\"hidden\" value=\"".${"event_event$sig"}."\" name=\"day_event".$sig."\">"; 
									}
								}
							}	
							echo '</div>';	
						}
					}
				}
				

/*				if($servicio)
				{
					$eventQuery2 = "SELECT hora1 FROM reservas_hor WHERE dia = '$numero_dia' and servicio='$servicio'";
					$reservado0 = mysqli_query($db_con, $eventQuery2);
					if (mysqli_num_rows($reservado0) == 1) 
					{
						$reservado1 = mysqli_fetch_row($reservado0);
					}
				}
				echo '<div class="form-group">';
				if(!(empty($reservado1[0]))) 
				{
					echo "<label>1� hora</label> &nbsp;&nbsp; <span class='badge badge-warning'>$reservado1[0]</span>"; 
				}
				else
				{
					if (empty($event_event1)) 
					{ 
						echo "<label>1� hora</label> &nbsp;&nbsp; <select name=\"day_event1\" class=\"form-control\"><option></option>";
						$result1 = mysqli_query($db_con, $SQL);
						while($row1 = mysqli_fetch_array($result1))
						{ 
							$profesor = $row1[0];
							echo "<option>" . $profesor . "</option>";
						} 
						echo "</select>";
					} 
					else 
					{
						if(mb_strtolower($pr) == mb_strtolower($event_event1)) 
						{
							echo "<label>1� hora</label> &nbsp;&nbsp; <div class=\"form-group\"><input class=\"form-control\" type=\"text\" name=\"day_event1\"  value=\"$event_event1\"></div>";
						} 
						else
						{
							echo "<label>1� hora</label> &nbsp;&nbsp; <div class=\"form-group\"><input disabled class=\"form-control\" type=\"text\"  value='$event_event1'></div><input type=\"hidden\" value=\"$event_event1\" name=\"day_event1\">"; 
						}
					}
				}	
				echo '</div>';	
						
				if($servicio)
				{
					$eventQuery2 = "SELECT hora2 FROM reservas_hor WHERE dia = '$numero_dia' and servicio='$servicio'";
					$reservado0 = mysqli_query($db_con, $eventQuery2);
					if (mysqli_num_rows($reservado0)>0) 
					{
						$reservado1 = mysqli_fetch_row($reservado0);
					}
				}
				if(!(empty($reservado1[0]))) 
				{
					echo "<label>2� hora</label> &nbsp;&nbsp; <span class='badge badge-warning'>$reservado1[0]</span>"; 
				}
				else
				{
					echo '<div class="form-group">';
					if (empty($event_event2)) 
					{ 
						echo "<label>2� hora</label> &nbsp;&nbsp; <select name=\"day_event2\" class='form-control'><option></option>";
						$result1 = mysqli_query($db_con, $SQL);
						while($row1 = mysqli_fetch_array($result1))
						{ 
							$profesor = $row1[0];
							echo "<option>" . $profesor . "</option>";
						} 
						echo "</select>";
					} 
					else 
					{
						if(mb_strtolower($pr) == mb_strtolower($event_event2)) 
						{
							echo "2 Hora &nbsp;&nbsp; <div class=\"form-group\"><input class=\"form-control\" type=\"text\" name=\"day_event2\"  value=\"$event_event2\"></div>"; 
						} 
						else
						{
							echo "<label>2� hora</label> &nbsp;&nbsp; <div class=\"form-group\"><input disabled class=\"form-control\" type=\"text\"  value='$event_event2'></div><input type=\"hidden\" value=\"$event_event2\" name=\"day_event2\">"; 
						}
					}
				}	
				echo '</div>';
			
		
				if($servicio)
				{
					$eventQuery2 = "SELECT hora3 FROM reservas_hor WHERE dia = '$numero_dia' and servicio='$servicio'";
					$reservado0 = mysqli_query($db_con, $eventQuery2);
					if (mysqli_num_rows($reservado0)>0) 
					{
						$reservado1 = mysqli_fetch_row($reservado0);
					}
				}
				if(!(empty($reservado1[0]))) 
				{
					echo "<label>3� hora</label> &nbsp;&nbsp; <span class='badge badge-warning'>$reservado1[0]</span>"; 
				}
				else
				{
					echo '<div class="form-group">';
					if(empty($event_event3)) 
					{ 
						echo "<label>3� hora</label> &nbsp;&nbsp; <select name=\"day_event3\" class='form-control'><option></option>";
						$result1 = mysqli_query($db_con, $SQL);
						while($row1 = mysqli_fetch_array($result1))
						{ 
							$profesor = $row1[0];
							echo "<option>" . $profesor . "</option>";
						}
						echo "</select>";
					}
					else 
					{	
						if(mb_strtolower($pr) == mb_strtolower($event_event3)) 
						{
							echo "<label>3� hora</label> &nbsp;&nbsp; <div class=\"form-group\"><input class=\"form-control\" type=\"text\" name=\"day_event3\"  value=\"$event_event3\"></div>"; 
						} 
						else
						{
							echo "<label>3� hora</label> &nbsp;&nbsp; <div class=\"form-group\"><input disabled class=\"form-control\" type=\"text\"  value='$event_event3'></div><input type=\"hidden\" value=\"$event_event3\" name=\"day_event3\">"; 
						}
					}	
				}	
				echo '</div>';
						
					
				if($servicio){$eventQuery2 = "SELECT hora4 FROM reservas_hor WHERE dia = '$numero_dia' and servicio='$servicio'";
				$reservado0 = mysqli_query($db_con, $eventQuery2);
				if (mysqli_num_rows($reservado0)>0) 
				{
					$reservado1 = mysqli_fetch_row($reservado0);
				}
			}
			if(!(empty($reservado1[0]))) 
			{
				echo "<label>4� hora</label> &nbsp;&nbsp; <span class='badge badge-warning'>$reservado1[0]</span>"; 
			}
			else
			{
				echo '<div class="form-group">';
				if (empty($event_event4)) 
				{ 
					echo "<label>4� hora</label> &nbsp;&nbsp; <select name=\"day_event4\" class='form-control'><option></option>";
					$result1 = mysqli_query($db_con, $SQL);
					while($row1 = mysqli_fetch_array($result1))
					{ 
						$profesor = $row1[0];
						echo "<option>" . $profesor . "</option>";
					} 
					echo "</select>";
				} 
				else 
				{
					if(mb_strtolower($pr) == mb_strtolower($event_event4)) 
					{
						echo "<label>4� hora</label> &nbsp;&nbsp; <div class=\"form-group\"><input class=\"form-control\" type=\"text\" name=\"day_event4\"  value=\"$event_event4\"></div>"; 
					} 
					else
					{
						echo "<label>4� hora</label> &nbsp;&nbsp; <div class=\"form-group\"><input disabled class=\"form-control\" type=\"text\"  value='$event_event4'></div><input type=\"hidden\" value=\"$event_event4\" name=\"day_event4\">"; 
					} 
				}	
			}	
			echo '</div>';
						
					
			if($servicio)
			{
				$eventQuery2 = "SELECT hora5 FROM reservas_hor WHERE dia = '$numero_dia' and servicio='$servicio'";
				$reservado0 = mysqli_query($db_con, $eventQuery2);
				if (mysqli_num_rows($reservado0)>0) 
				{
					$reservado1 = mysqli_fetch_row($reservado0);
				}
			}
			if(!(empty($reservado1[0]))) 
			{
				echo "5 Hora &nbsp;&nbsp; <span class='badge badge-warning'>$reservado1[0]</span>"; 
			}
			else
			{
				echo '<div class="form-group">';
				if (empty($event_event5)) 
				{ 
					echo "<label>5� hora</label> &nbsp;&nbsp; <select name=\"day_event5\" class='form-control'><option></option>";
					$result1 = mysqli_query($db_con, $SQL);
					while($row1 = mysqli_fetch_array($result1))
					{ 
						$profesor = $row1[0];
						echo "<option>" . $profesor . "</option>";
					} 
					echo "</select>";
				} 
				else 
				{
					if(mb_strtolower($pr) == mb_strtolower($event_event5)) 
					{
						echo "<label>5� hora</label> &nbsp;&nbsp; <div class=\"form-group\"><input class=\"form-control\" type=\"text\" name=\"day_event5\"  value=\"$event_event5\"></div>"; 
					} 
					else
					{
						echo "5� Hora &nbsp;&nbsp; <div class=\"form-group\"><input disabled class=\"form-control\" type=\"text\"  value='$event_event5'></div><input type=\"hidden\" value=\"$event_event5\" name=\"day_event5\">"; 
					}
				}	
			}	
			echo '</div>';
						
					
			if($servicio)
			{
				$eventQuery2 = "SELECT hora6 FROM reservas_hor WHERE dia = '$numero_dia' and servicio='$servicio'";
				$reservado0 = mysqli_query($db_con, $eventQuery2);
				if (mysqli_num_rows($reservado0)>0) {
					$reservado1 = mysqli_fetch_row($reservado0);
				}
			}
			echo '<div class="form-group">';
			if(!(empty($reservado1[0]))) 
			{
				echo "<label>6� hora</label> &nbsp;&nbsp; <span class='badge badge-warning'>$reservado1[0]</span>"; 
			}
			else
			{
				if (empty($event_event6)) 
				{ 
					echo "<label>6� hora</label> &nbsp;&nbsp; <select name=\"day_event6\" class='form-control'><option></option>";
					$result1 = mysqli_query($db_con, $SQL);
					while($row1 = mysqli_fetch_array($result1))
					{ 
						$profesor = $row1[0];
						echo "<option>" . $profesor . "</option>";
					} 
					echo "</select>";
				} 
				else 
				{
					if(mb_strtolower($pr) == mb_strtolower($event_event6)) 
					{
						echo "<label>6� hora</label> &nbsp;&nbsp; <div class=\"form-group\"><input class=\"form-control\" type=\"text\" name=\"day_event6\"  value=\"$event_event6\"></div>"; 
					} 
					else
					{
						echo "<label>6� hora</label> &nbsp;&nbsp; <div class=\"form-group\"><input disabled class=\"form-control\" type=\"text\"  value='$event_event6'></div><input type=\"hidden\" value=\"$event_event6\" name=\"day_event6\">"; 
					} 
				}	
			}	
			echo '</div>';
				
			
			if($servicio)
			{
				$eventQuery2 = "SELECT hora7 FROM reservas_hor WHERE dia = '$numero_dia' and servicio='$servicio'";
				$reservado0 = mysqli_query($db_con, $eventQuery2);
				if (mysqli_num_rows($reservado0)>0) {
					$reservado1 = mysqli_fetch_row($reservado0);
				}
			}
			echo '<div class="form-group">';
			if(!(empty($reservado1[0]))) 
			{
				echo "<label>7� hora</label> &nbsp;&nbsp; <span class='badge badge-warning'>$reservado1[0]</span>"; 
			}
			else
			{
				if (empty($event_event7)) 
				{ 
					echo "<label>7� hora</label> &nbsp;&nbsp; <select name=\"day_event7\" class='form-control'><option></option>";
					$result1 = mysqli_query($db_con, $SQL);
					while($row1 = mysqli_fetch_array($result1))
					{ 
						$profesor = $row1[0];
						echo "<option>" . $profesor . "</option>";
					} 
					echo "</select>";
				} 
				else 
				{
					if(mb_strtolower($pr) == mb_strtolower($event_event7)) 
					{
						echo "<label>7� hora</label> &nbsp;&nbsp; <div class=\"form-group\"><input class=\"form-control\" type=\"text\" name=\"day_event7\"  value=\"$event_event7\"></div>"; 
					} 
					else
					{
						echo "<label>7� hora</label> &nbsp;&nbsp; <div class=\"form-group\"><input disabled class=\"form-control\" type=\"text\"  value=\"$event_event7\"></div><input type=\"hidden\" value=\"$event_event7\" name=\"day_event7\">"; 
					}
				}
			}
			echo '</div>';
*/

			echo "<input type=\"hidden\" value=\"$year\" name=\"year\">
			      <input type=\"hidden\" value=\"$month\" name=\"month\">
			      <input type=\"hidden\" value=\"$today\" name=\"today\">
			      <input type=\"submit\" class=\"btn btn-primary\" id=\"formsubmit\" value=\"Reservar\">
			    </form>";
			echo "</div>";
	?>
			</div>
		</div>
	</div>

<?php
include("../../pie.php");
?>
</body>
</html>
