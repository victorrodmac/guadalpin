<?php
require('../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

include("../menu.php");
?>

<div class="container">
	
	<!-- TITULO DE LA PAGINA -->
	<div class="page-header">
		<h2>Administraci�n <small>Funciones, configuraci�n, importaci�n de datos,...</small></h2>
	</div>
	
	
	<div class="row">
		
		<!-- COLUMNA IZQUIERDA -->
		<div class="col-sm-4">
		
			<div class="well">
			<?php include("menu.php");?>
			</div>
			
		</div><!-- /.col-sm-4 -->
		
		
		<!-- COLUMNA DERECHA -->
		<div class="col-sm-8">
			
			<h3>Descripci�n de los m�dulos e instrucciones.</h3>
			
			<div class="text-justify">
			<p>	
			Esta es la pagina de Administraci�n de la Intranet y de las Bases de Datos de la misma. A continuaci�n siguen algunas explicaciones sobre la mayor�a de los m�dulos de gesti�n.</p>
			<hr>
			<p>El <strong>primer grupo de opciones (<span class="text-info">Configuraci�n</span>)</strong> permite editar y modificar los datos de la configuraci�n que se crearon cuando se instal� la Intranet por primera vez (cambiar informaci�n del Centro o de la Base de datos, seleccionar m�dulos activos en la aplicaci�n, entra en modo Mantenimiento, etc.).</p> 
			
			<hr>
			<p>	
			El <strong>segundo grupo de opciones (<span class="text-info">A Principio de curso...</span>)</strong> crea las tablas principales: Alumnos, Profesores, Asignaturas, Calificaciones y Horarios. Hay que tener a mano varios archivos que descargamos de Seneca y los programas generadores de horarios. </p>
			<ul>
			<li>Los <span class="text-info">Alumnos, Asignaturas y Sistemas de Calificaciones</span> se crean una sola vez a comienzo de curso, aunque luego podemos actualizarlos cuando queramos. En este proceso se crean las tablas de Alumnos y se les asigna un n�mero de aula. Tambi�n se generan dos archivos preparados para el Alta masiva de Alumnos y Profesores en Gesuser y en Moodle (los coloca en intranet/xml/jefe/TIC/), as� como la tabla de Alumnos con asignaturas pendientes. Necesitamos dos archivos de S�neca: 
			<ul>
			  <li>el de los alumnos. Lo descargamos desde S�neca. Alumnado --&gt; Alumnado --&gt; Alumnado del Centro --&gt; Aceptar (arriba a la derecha) --&gt; Exportar (arriba a la izquierda) --&gt; Exportar datos al formato: Texto plano. El archivo que se descarga se llama RelPerCen.txt</li>
			  <li>el de las evaluaciones. Se descarga de Seneca desde &quot;Intercambio de Informaci�n --&gt; Exportaci�n desde Seneca --&gt; Exportaci�n de Calificaciones&quot;. Arriba a la derecha hay un icono para crear un nuevo documento con los datos de las evaluaciones; seleccionar todos los grupos del Centro para una evaluaci�n (la primera vale, por ejemplo) y a�adirlos a la lista. Cuando hay�is terminado, pulsad en el icono de confirmaci�n y al cabo de un minuto volved a la p�gina de exportaci�n de calificaciones y ver�is que se ha generado un archivo comprimido que pod�is descargar. </li>
			</ul>
			</li>
			<li>Los <span class="text-info">Datos generales del Centro</span>. Este m�dulo se encarga de importar la relaci�n de <strong>cursos</strong> y <strong>unidades</strong> del Centro registrados en S�neca, as� como la relaci�n de <strong>materias</strong> que se imparten y <strong>actividades</strong> del personal docente. Se importar� tambi�n la relaci�n de <strong>dependencias</strong>, que se utilizar� para realizar reservas de aulas o consultar el horario de las mismas. Es un elemento esencial porque garantiza la coherencia de datos muy diversos con los otros m�dulos de la aplicaci�n. Por esta raz�n, es conveniente <b>Actualizar los Datos del Centro</b> regularmente.</li>
			<li><span class="text-info">Los Profesores y Departamentos</span>. Se descarga desde S�neca --&gt; Personal --&gt; Personal del centro  --&gt; Exportar (arriba a la izquierda) --&gt; Exportar datos al formato: Texto plano.</li>
			<li><span class="text-info">Los Horarios</span>. Este es el �nico archivo que no se descarga de S�neca, sino de los Programas generadores de Horarios. Requiere el archivo con extensi�n XML que se genera con el programa generador de horarios para subir los datos del Horario a S�neca. Este m�dulo tambi�n se encarga de preparar el archivo para exportar a S�neca que crean los programas de Horarios (Horw, etc.), evitando tener que registrar manualmente los horarios de cada profesor. La adaptaci�n que realiza este m�dulo es conveniente, ya que la compatibilidad con S�neca de los generadores de horarios tiene limitaciones (C�digo �nico de las asignaturas de Bachillerato, Diversificaci�n, etc.). Es necesario tener a mano el archivo en formato XML que se exporta desde Horw o cualquier otro generador de Horarios. La Intranet puede funcionar sin horarios, pero sus funciones quedan muy limitadas. Por esta raz�n conviene hacer lo posible por importar un horario dentro de la aplicaci�n. En caso de no poder hacerlo, todav�a nos queda la posibilidad de crear el horario dentro de la aplicaci�n con el m�dulo Profesores --&gt; Modificar / Crear Horarios.</li>			  
			<li><span class="text-info">Los Profesores, Unidades y Materias </span>. Se descarga desde S�neca --&gt; Personal --&gt; Personal del centro --&gt; Unidades y Materias  --&gt; Exportar (arriba a la izquierda) --&gt; Exportar datos al formato: Texto plano.
			</li>
			<li>Los <span class="text-info">D�as Festivos</span> deben importarse incluso si no contamos con horario, porque varios m�dulos de la misma (Calendario, etc.) dependen de los datos.
			</li>
			<li><span class="text-info">Modificar ROF</span> permite ajustar la lista y tipo de Problemas de Convivencia al Reglamento de Organizaci�n y Funcionamiento que est� en vigor en nuestro Centro. </li>
			<li>La opci�n, <span class="text-info">Importaci�n de Grupos-Aulas</span>, se debe ejecutar cuando en el programa horw no se le ha asignado aula a los grupos. Sirve para asignarle un n�mero de aula a los grupos creados.</li>	
			<li>La �ltima opci�n, <span class="text-info">Limpiar Horarios</span>, se debe ejecutar cuando los cambios en los horarios se han dado por terminados y estos se encuentran en perfecto estado en S�neca. Supone que hemos actualizado en primer lugar los profesores.</li>			
			</ul>
			</p>
			<hr>
			<p>
			    El <strong>tercer grupo (<span class="text-info">Actualizaci�n</span>)</strong> permite actualizar los datos de Alumnos, Asignaturas, Profesores y Departamentos del Centro. Esta pensado para la actualizaci�n de los alumnos que se van matriculando a lo largo del Curso, as� como para la puesta al d�a de la lista de Profesores y Departamentos. Los archivos requeridos son los mismos que hemos mencionado m�s arriba.</p>
			    <hr>
			    <p>
			El <strong>cuarto grupo de opciones</strong> afecta a los <strong><span class="text-info">Profesores</span></strong>.<br> 
			 Una vez se han creado los Departamentos y Profesores, es necesario seleccionar los <span class="text-info">Perfiles de los Profesores</span> para que la aplicaci�n se ajuste a las funciones propias de cada profesor ( Tutor, Direcci�n, Jefe de Departamento, etc. ). <br>
			 Tambi�n desde aqu� se puede <span class="text-info">Restablecer las contrase�as</span> de los profesores que se hayan olvidado de la misma. Al restablecerla, el profesor deber� entrar de nuevo con el DNI como contrase�a, lo que le llevar� a la p�gina desde la que tendr� que cambiarla con los criterios de S�neca. <br>
			 <span class="text-info">Hoja de Firmas</span> aparece en el Men� si se ha marcado la opci�n de <em>Horarios</em> en la Configuraci�n de la aplicaci�n. Presenta una hoja para que firmen los profesores si el Centro utiliza el sistema.<br>
			 <span class="text-info">Sustituci�n de profesores</span> cambia los datos de un profesor que ha sido sustituido por los datos del profesor que lo sustituye, de tal manera que el nuevo profesor pueda entrar en la Intranet con los datos heredados del profesor sustituido.<br>
			 Podemos tambi�n <span class="text-info">Gestionar los Departamentos</span>, editar sus nombres, unirlos, y asignar profesores a los mismos. Es conveniente hacerlo a principio de curso.<br>
			 <span class="text-info">Subir fotos</span> nos permite realizar una descarga masiva de fotos de los profesores a la Intranet. Se comprimen todas las fotos en un archivo con la extensi�n <em>ZIP</em>. El nombre de la foto de un profesor se construye a partir del usuario IdEA ( <em>mgargon732.jpg</em>, por ejemplo). Si nuestro Servidor no permite subir un archivo de tanto tama�o, podemos crear varios archivos comprimidos m�s peque�os y subirlos uno a uno.<br>
			 <span class="text-info">Informe de accesos</span> ofrece informaci�n sobre el uso de la Intranet por parte de los Profesores.
			 </p>
			 <hr>
			 		 
			<p>El <strong>quinto grupo <span class="text-info">(Alumnos)</span></strong> toca asuntos varios relacionados con los mismos. </p>
			<ul>
			 <li><span class="text-info">Las Listas de Grupos</span>. Supone que se han realizado todas las tareas anteriores (Horario, Profesores, Alumnos, etc.). Presenta la lista de todos los Grupos del Centro en formato PDF, preparada para ser imprimida y entregada a los Profesores a principios de Curso. </li>
			<li><span class="text-info">Carnet de los Alumnos</span> genera los carnet de los alumnos del Centro preparados para ser imprimidos. Este m�dulo supone que se han subido las fotos de los alumnos a la intranet utilizando el enlace <span class="text-info">Subir fotos de alumnos</span>, a continuaci�n.</li>
			<li><span class="text-info">Subir fotos de alumnos</span> permite hacer una subida en bloque de todas las fotos de los alumnos para sean utilizadas por los distintos m�dulos de la Intranet. Para ello, necesitaremos crear un archivo comprimido ( .zip ) con todos los archivos de fotos de los alumnos. Cada archivo de foto tiene como nombre el NIE de S�neca (el N�mero de Identificaci�n que S�neca asigna a cada alumno ) seguido de la extensi�n <em>.jpg</em> o <em>.jpeg</em>. El nombre t�pico de un archivo de foto quedar�a por ejemplo as�: <em>1526530.jpg</em>. </li>
		  	<li><span class="text-info">Libros de Texto gratuitos</span> es un conjunto de p�ginas pensadas para registrar el estado de los libros de cada alumno dentro del Programa de Ayudas al Estudio de la Junta, e imprimir los certificados correspondientes (incluidas las facturas en caso de mal estado o p�rdida del material).</li>
		    <li><span class="text-info">Matriculaci�n de alumnos</span> es un m�dulo que permite matricular a los alumnos a trav�s de la intranet o, en su caso, a trav�s de internet (si el m�dulo se ha incorporado a la p�gina principal del Centro). Los tutores, a final de curso, ayudan a los alumnos a matricularse en una sesi�n de tutor�a. Posteriormente el Centro imprime los formularios de la matr�cula y se los entregan a los alumnos para ser firmados por sus padres y entregados de nuevo en el IES. El Equipo directivo cuenta entonces con la ventaja de poder administrar los datos f�cilmente para formar los grupos de acuerdo a una gran variedad de criterios. El m�dulo incluye una p�gina que realiza previsiones de matriculaci�n de alumnos en las distintas evaluaciones.</li> 
		    <li> <span class="text-info">Informe de accesos</span> ofrece informaci�n sobre el uso del <em><b>Acceso para Alumnos</b></em> de la <b>P�gina p�blica del Centro</b> por parte de los Alumnos y sus Padres. Supone que la <em>P�gina del Centro</em> o su m�dulo de <em>Acceso para Alumnos</em> est� operativa. La P�gina del Centro puede ser descargada desde <a href="https://github.com/IESMonterroso/pagina_centros" target="_blank">GitHub</a>. Posteriormente debemos leer el archivo README con informaci�n sobre la misma y editar el archivo de configuraci�n (<em>conf_principal.php</em>) para ajustarlo a nuestro Centro.</li>
		    </ul>
			<hr>
			
			<p>El <strong>sexto grupo (<span class="text-info">Notas de Evaluaci�n</span>)</strong> crea y actualiza la tabla de las Notas de Evaluaci�n que aparecen en los Informes de la Intranet, pero tambi�n presenta las Calificaciones del alumno en la pagina principal. Los archivos necesarios se descargan de S�neca desde &quot;Intercambio de Informaci�n --&gt; Exportaci�n desde Seneca --&gt; Exportaci�n de Calificaciones&quot;.</p>
			<hr>
			
			<p>El <strong>s�ptimo grupo (<span class="text-info">Centro TIC</span>)</strong> proporciona los archivos de importaci�n de usuarios alumnos y profesores para las plataformas Gesuser (creaci�n de perfiles de usuario de los ordenadores port�tiles con Guadalinex) y Moodle (Plataforma educativa propia del centro o de la Junta de Andaluc�a).</p>
			<hr>
			
			<p>El <strong>octavo grupo (<span class="text-info">Sistema de Reservas</span>)</strong> ofrece la posibilidad de gestionar el m�dulo de Reservas contenido en la aplicaci�n (crear categor�as, y dentro de estas Elementos para reservar).</p>
			<hr>
			
			<p>El <strong>noveno grupo (<span class="text-info">Base de datos</span></strong>) permite realizar copias de seguridad de las bases de datos que contienen los datos esenciales de la Intranet. La copia de seguridad crea un archivo, comprimido o en formato texto (SQL), en un directorio de la aplicaci�n ( /intranet/xml/jefe/copia_db/ ). Esta copia puede ser descargada una vez creada. Tambi�n podemos restaurar la copia de seguridad seleccionando el archivo que hemos creado anteriormente. 
			<br>Si el Centro ha tenido la aplicaci�n funcionando durante varios cursos escolares, la opci�n <strong>Bases de datos de Cursos anteriores</strong> nos ofrece la posibilidad de incorporarlas a la Intranet. De este modo, podemos acceder a los datos de los alumnos a lo largo de los �ltimos a�os, especialmente en los <em>Informes de un Alumno</em>, o en el <em>Informe hist�rico del alumno</em> cuando vemos los <em>Datos de los alumnos</em>.</p>
			
		</div><!-- /.col-sm-8 -->
		
	</div><!-- /.col-sm-8 -->
		
	</div><!-- /.row-->

</div><!-- /.container -->


<?php include("../pie.php");?>  
</body>
</html>
