<?php
require('../../bootstrap.php');

include("../../menu.php");
include("menu.php");
?>
 
	<div class="container">
		
		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
		  <h2>Biblioteca <small>Consulta de Fondos de la Biblioteca</small></h2>
		</div>
		
		<!-- SCAFFOLDING -->
		<div class="row">
			
			<!-- COLUMNA CENTRAL -->
			<div class="col-sm-6 col-sm-offset-3">
				
				<div class="well">
				
					<form method="post" action="biblioteca.php">
		  			
		  			<fieldset>
		  			
		  				<legend>Consulta de Fondos de la Biblioteca</legend>
		  				
			  			<div class="form-group">
			        	<label for="autor">Autor</label>
			          <input type="text" class="form-control" id="autor" name="autor"> 
			       	</div>
			       	
			       	<div class="form-group">
			        	<label for="titulo0">T�tulo</label>
			        	<input type="text" class="form-control" id="titulo0" name="titulo0">
			        </div>
			        
			        <div class="form-group">     
			        	<label for="editorial">Editorial</label>
			        	<input type="text" class="form-control" id="editorial" name="editorial">
			        </div>
			        
			        <p class="help-block">La Biblioteca del Centro ha registrado m�s de 10.000 vol�menes en el Fondo general, y el trabajo contin�a. En estas p�ginas puedes buscar textos u otros materiales en nuestra base de datos.</p>
			        	        
			        <p class="help-block">La consulta de materiales en el Fondo es abierta, por lo que no es necesario escribir autor, t�tulo o editorial <em>exactos</em>. Si, por ejemplo, introduzco la expresi�n "Cer" en el campo "Autor", la consulta me devolver� libros de "<strong>Cer</strong>nuda" y de "<strong>Cer</strong>vantes".</p>
			  			
			  			<button type="submit" class="btn btn-primary" name="enviar">Consultar</button>
			  			
			  		</fieldset>
			  		
			  	</form>
			  	
			  </div><!-- /.well -->
		  
		  </div><!-- /col-sm-6 -->
		  
		</div><!-- /.row -->
		
	</div><!-- /.container -->

<?php include("../../pie.php"); ?>

</body>
</html>
