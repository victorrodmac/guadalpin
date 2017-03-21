$( document ).ready(function() {
	$.post( "estadisticas/estadisticas.php", { "id" : 'asistencia' }, null, "json" )
	    .done(function( data, textStatus, jqXHR ) {
	    	
	       $('#stats-numfaltas').html(data.num_faltas);
	       
	       if (data.asistencia_tabla.length > 0) {
	           $('#stats-asistencia-modal').html('<div style="height: 350px; overflow: scroll;"><table class="table table-condensed table-hover table-striped"><thead><tr><th>Alumno/a</th><th>Hora</th><th>Falta</th></tr></thead><tbody id="asistencia_tabla"></tbody></table></div>');
	           
	           var texto_falta = "";
	           
	           $.each(data.asistencia_tabla, function(i, item) {
	           	 switch(item.falta) {
	           	 	case 'F' : texto_falta = "Injustificada"; break;
	           	 	case 'J' : texto_falta = "Justificada"; break;
	           	 	case 'R' : texto_falta = 'Restraso'; break;
	           	 } 	
	           	 	
	          	 $('#asistencia_tabla').append('<tr><td>' + item.alumno + '</td><td>' + item.hora + '� hora</td><td>' + texto_falta + '</td></tr>');
	           });
	       }
	       else {
	           $('#stats-asistencia-modal').html('<br><p class="lead text-center text-muted">No se han registrado faltas de asistencia hoy</p><br>');
	       }
	});
	
	$.post( "estadisticas/estadisticas.php", { "id" : 'convivencia' }, null, "json" )
	    .done(function( data, textStatus, jqXHR ) {
	       $('#stats-convivencia').html(data.total);
	       
	       if (data.convivencia_tabla.length > 0) {
	           $('#stats-convivencia-modal').html('<div style="height: 350px; overflow: scroll;"><table class="table table-condensed table-hover table-striped"><thead><tr><th>Alumno/a</th><th>Problema</th><th>Profesor</th></tr></thead><tbody id="convivencia_tabla"></tbody></table></div>');
	           
	           $.each(data.convivencia_tabla, function(i, item) {
	          	 $('#convivencia_tabla').append('<tr onclick="window.location.href=\'admin/fechorias/detfechorias.php?id=' + item.idfechoria + '&amp;claveal=' + item.claveal + '\'" style="cursor: pointer; font-size: 0.9em;"><td nowrap>' + item.alumno + '</td><td>' + item.problema + '</td><td nowrap>' + item.profesor + '</td></tr>');
	           });
	       }
	       else {
	           $('#stats-convivencia-modal').html('<br><p class="lead text-center text-muted">No se han registrado problemas de convivencia hoy</p><br>');
	       }
	});
	
	$.post( "estadisticas/estadisticas.php", { "id" : 'expulsiones' }, null, "json" )
	    .done(function( data, textStatus, jqXHR ) {
	       $('#stats-expulsados').html(data.total_expulsados);
	       $('#stats-reingresos').html(data.total_reingresan);
	       
	       if (data.expulsados_tabla.length > 0) {
	           $('#stats-expulsados-modal').html('<table class="table table-condensed table-hover table-striped"><thead><tr><th>Alumno/a</th><th>Unidad</th><th>Problema</th><th>Inicio</th><th>Fin</th></tr></thead><tbody id="expulsados_tabla"></tbody></table>');
	           
	           $.each(data.expulsados_tabla, function(i, item) {
	          	 $('#expulsados_tabla').append('<tr onclick="window.location.href=\'admin/fechorias/detfechorias.php?id=' + item.idfechoria + '&amp;claveal=' + item.claveal + '\'" style="cursor: pointer; font-size: 0.9em;"><td nowrap>' + item.alumno + '</td><td>' + item.unidad + '</td><td>' + item.problema + '</td><td nowrap>' + item.inicio + '</td><td nowrap>' + item.fin + '</td></tr>');
	           });
	       }
	       else {
	           $('#stats-expulsados-modal').html('<br><p class="lead text-center text-muted">No hay alumnos expulsados actualmente</p><br>');
	       }
	       
	       if (data.reincorporaciones_tabla.length > 0) {
	           $('#stats-reincorporaciones-modal').html('<table class="table table-condensed table-hover table-striped"><thead><tr><th>Alumno/a</th><th>Unidad</th><th>Problema</th><th>Inicio</th><th>Fin</th></tr></thead><tbody id="reincorporaciones_tabla"></tbody></table>');
	           
	           $.each(data.reincorporaciones_tabla, function(i, item) {
	          	 $('#reincorporaciones_tabla').append('<tr onclick="window.location.href=\'admin/fechorias/detfechorias.php?id=' + item.idfechoria + '&amp;claveal=' + item.claveal + '\'" style="cursor: pointer; font-size: 0.9em;"><td nowrap>' + item.alumno + '</td><td>' + item.unidad + '</td><td>' + item.problema + '</td><td nowrap>' + item.inicio + '</td><td nowrap>' + item.fin + '</td></tr>');
	           });
	       }
	       else {
	           $('#stats-reincorporaciones-modal').html('<br><p class="lead text-center text-muted">No hay alumnos que se reincorporen hoy</p><br>');
	       }
	});
	
	$.post( "estadisticas/estadisticas.php", { "id" : 'visitas' }, null, "json" )
	    .done(function( data, textStatus, jqXHR ) {
	       $('#stats-visitas').html(data.total);
	       
	       if (data.visitas_tabla.length > 0) {
	           $('#stats-visitas-modal').html('<table class="table table-condensed table-hover table-striped"><thead><tr><th>Alumno/a</th><th>Unidad</th></tr></thead><tbody id="visitas_tabla"></tbody></table>');
	           
	           $.each(data.visitas_tabla, function(i, item) {
	          	 $('#visitas_tabla').append('<tr onclick="window.location.href=\'admin/infotutoria/infocompleto.php?id=' + item.idvisita + '\'" style="cursor: pointer; font-size: 0.9em;"><td nowrap>' + item.alumno + '</td><td>' + item.unidad + '</td></tr>');
	           });
	       }
	       else {
	           $('#stats-visitas-modal').html('<br><p class="lead text-center text-muted">No hay visitas previstas para hoy</p><br>');
	       }
	});
});