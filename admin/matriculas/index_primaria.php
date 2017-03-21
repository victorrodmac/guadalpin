<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

include("../../menu.php");
include("menu.php");
?>
<div class="container">

<div class="page-header" align="center">
  <h2>Matriculaci�n de Alumnos <small> Importar Alumnos de Primaria</small></h2>
</div>
<br />
<div class="row">
<div class="col-sm-5">
<FORM ENCTYPE="multipart/form-data" ACTION="../../xml/jefe/alma_primaria.php" METHOD="post">
  <div class="well well-large">
  <p class="lead">Selecciona el archivo con los datos<br />
  </p>
  <input type="file" name="archivo1" class="input input-file span4">
  <hr>
  <div align="center">
    <INPUT type="submit" name="enviar" value="Aceptar" class="btn btn-primary">
  </div>
  </div>
</FORM>
</div>
<div class="col-sm-7">
 <p class="help-block">El m&oacute;dulo de Matriculaci&oacute;n permite importar los datos de los alumnos de Colegios adscritos al IES, facilitando enormemente la tarea al tomar los datos de las criaturas de la base de datos. Para contar con los datos de los Colegios, los Directores de los mismos deben proporcionar el archivo de S&eacute;neca RegAlum.txt (lo descargamos desde S&eacute;neca: Alumnado --&gt; Alumnado del Centro --&gt; Aceptar (arriba a la derecha) --&gt; Exportar (arriba a la izquierda) --&gt; Exportar datos al formato: Texto plano). Una vez en nuestras manos, le cambiamos el nombre por el del Colegio respectivo (p.ej.: Santo Tomas.txt), y comprimimos todos los archivos en formato .zip. Este es el archivo comprimido que debes seleccionar en el formulario.</p><p><em><strong>Hay que tener en cuenta que el m�dulo de importaci�n supone que el formato de las grupos de los Colegios es semejante al de los Institutos</strong></em>, por lo que se espera que el nombre sea del tipo <strong>6P-A</strong>, <strong>6� A</strong>,, <strong>6� Primaria A</strong> etc. Si el Colegio no sigue ese criterio, es necesario editar los archivos de S�neca y buscar / reemplazar el nombre de las Unidades para ajustarlo a los criterios de la Intranet antes de proceder a la importaci�n.</p><p>
La importaci�n de los alumnos de Primaria tambi�n permite que los Colegios adscritos puedan registrar los Informes de Tr�nsito de los alumnos de 6� a trav�s del m�dulo correspondiente de la P�gina del Centro (http://iesmonterroso.org/transito/). Estos datos prueden luego ser consultados en Informes de Tr�nsito en el M�dulo de Matriculaci�n de la Intranet. Para que los Colegios accedan, se genera una contrase�a que debe ser entregada al Director de cada Centro. �sta se genera escribiendo el nombre del colegio sin espacios y en min�sculas, seguido de un gui�n bajo y el c�digo de Centro de nuestro IES.  
 </p>
</div>

</div>
</div>


	<?php include("../../pie.php"); ?>

</body>
</html>

