<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed');

switch ($config['centro_provincia']) {
	case 'Almer�a' : $web_delegacion = 'almeria'; break;
	case 'C�diz' : $web_delegacion = 'cadiz'; break;
	case 'C�rdoba' : $web_delegacion = 'cordoba'; break;
	case 'Granada' : $web_delegacion = 'granada'; break;
	case 'Huelva' : $web_delegacion = 'huelva'; break;
	case 'Ja�n' : $web_delegacion = 'jaen'; break;
	case 'M�laga' : $web_delegacion = 'malaga'; break;
	case 'Sevilla' : $web_delegacion = 'sevilla'; break;
}

//	VALORES DEL MENU
//	-----------------------------------------------------------------------------------------------------
//	menu_id					(string) Identificador del men�
//	nombre					(string) Nombre del men�
//	cargos					(array) ID de los cargos con privilegios para visualizar el menu
//	ncargos					(array) ID de los cargos sin privilegios para visualizar el menu
//	modulo					(boolean) Valor del m�dulo del que depende el men�
//	meses					(array) N�mero del mes cuando est� disponible el men� (sin 0 iniciales)
//	items					(array) Opciones del men�
//	items -> href			(string) URI de la p�gina
//	items -> titulo			(string) T�tulo de la p�gina
//	items -> cargos			(array) ID de los cargos con privilegios para visualizar la opci�n del men�
//	items -> ncargos		(array) ID de los cargos sin privilegios para visualizar la opci�n del men�
//	items -> modulo			(boolean) Valor del m�dulo del que depende la opci�n del men�
//	items -> meses			(array) N�mero del mes cuando est� disponible la opci�n del men� (sin 0 iniciales)
//
//	Se puede realizar menus anidados en un item, estos submenus permiten las mismas acciones de control
//	de acceso que el item padre.
//


$menu = array(
	array(
		'menu_id' => 'direccion',
		'nombre'  => 'Direcci�n del centro',
		'cargos'  => array('1'),
		'items'   => array (
			array(
				'href'   => 'xml/index.php',
				'titulo' => 'Administraci�n de la Intranet',
			),
			array(
				'href'   => '#',
				'titulo' => 'Intervenciones',
				'items' => array(
					array(
						'href'   => 'admin/jefatura/index.php',
						'titulo' => 'Intervenciones sobre alumnos'
					),
					array(
						'href'   => 'admin/jefatura/profesores.php',
						'titulo' => 'Intervenciones sobre profesores'
					)
				)
			),
			array(
				'href'   => 'admin/tutoria/index.php',
				'titulo' => 'Control de Tutor�as',
			),
			array(
				'href'   => 'admin/guardias/index_admin.php',
				'titulo' => 'Gesti�n de Guardias',
				'modulo' => $config['mod_horarios'],
			),
			array(
				'href'   => 'admin/ausencias/index.php',
				'titulo' => 'Gesti�n de Ausencias',
			),
			array(
				'href'   => 'admin/matriculas/index.php',
				'titulo' => 'Matriculaci�n de alumnos',
				'modulo'  => $config['mod_matriculacion'],
				'meses'	 => array(6, 7, 8, 9),
			)
		)
	),
	
	array(
		'menu_id' => 'extraescolares',
		'nombre'  => 'Extraescolares',
		'cargos'  => array('5'),
		'items'   => array (
			array(
				'href'   => 'admin/actividades/indexextra.php',
				'titulo' => 'Administrar actividades'
			),
			array(
				'href'   => 'calendario/index.php?action=nuevoEvento',
				'titulo' => 'Introducir actividades'
			)
		)
	),
	
	array(
		'menu_id' => 'orientacion',
		'nombre'  => 'Orientaci�n',
		'cargos'  => array('8'),
		'items'   => array (
			array(
				'href'   => 'admin/orientacion/tutor.php',
				'titulo' => 'Intervenciones'
			),
			array(
				'href'   => 'admin/tutoria/index.php',
				'titulo' => 'Tutor�as'
			),
			array(
				'href'   => 'admin/actividades/indexextra.php',
				'titulo' => 'Actividades Extraescolares'
			),
			array(
				'href'   => 'admin/matriculas/index.php',
				'titulo' => 'Matriculaci�n de alumnos',
				'modulo'  => $config['mod_matriculacion'],
				'meses'	 => array(6, 7, 8, 9),
			),
			array(
				'href'   => 'admin/matriculas/consulta_transito.php',
				'titulo' => 'Informes de Tr�nsito',
				'modulo'  => $config['mod_matriculacion'],
			)
		)
	),
	
	array(
		'menu_id' => 'tutoria',
		'nombre'  => 'Tutor�a de '.$_SESSION['mod_tutoria']['unidad'],
		'cargos'  => array('2'),
		'items'   => array (
			array(
				'href'   => 'admin/tutoria/index.php',
				'titulo' => 'Resumen global',
			),
			array(
				'href'   => 'admin/datos/datos.php?unidad='.$_SESSION['mod_tutoria']['unidad'],
				'titulo' => 'Datos de alumnos',
			),
			array(
				'href'   => 'admin/tutoria/intervencion.php',
				'titulo' => 'Intervenciones',
			)
		)
	),
	
	array(
		'menu_id' => 'biblioteca',
		'nombre'  => 'Biblioteca',
		'modulo'  => $config['mod_biblioteca'],
		'cargos'  => array('c'),
		'items'   => array (
			array(
				'href'   => 'http://'.$config['mod_biblioteca_web'],
				'titulo' => 'P�gina de la Biblioteca',
				'target' => '_blank'
			),
			array(
				'href'   => 'admin/cursos/hor_aulas.php?aula=Biblioteca',
				'titulo' => 'Horario de la Biblioteca'
			),
			array(
				'href'   => 'admin/biblioteca/consulta.php',
				'titulo' => 'Gesti�n de los Pr�stamos'
			),
			array(
				'href'   => 'admin/biblioteca/index.php',
				'titulo' => 'Consultar fondos de la Biblioteca'
			),
			array(
				'href'   => 'admin/biblioteca/index_biblio.php',
				'titulo' => 'Importar datos de Abies'
			)
		)
	),

		
	array(
		'menu_id' => 'consultas',
		'nombre'  => 'Consultas',
		'items'   => array (
			array(
				'href'   => 'admin/datos/cdatos.php',
				'titulo' => 'Datos de los alumnos'
			),
			array(
				'href'   => 'admin/cursos/chorarios.php',
				'titulo' => 'Horarios de profesores/grupos',
				'modulo' => $config['mod_horarios'],
			),
			array(
				'href'   => '#',
				'titulo' => 'Listas',
				'items' => array(
					array(
						'href'   => 'admin/cursos/ccursos.php',
						'titulo' => 'Lista de los grupos'
					),
					array(
						'href'   => 'admin/pendientes/index.php',
						'titulo' => 'Listas de pendientes'
					)
				)
			),
			array(
				'href'   => '#',
				'titulo' => 'Fotograf�as',
				'items' => array(
					array(
						'href'   => 'admin/fotos/index.php',
						'titulo' => 'Fotos de los alumnos'
					),
					array(
						'href'   => 'admin/fotos/profes.php',
						'titulo' => 'Fotos de los profesores'
					)
				)
			),
			array(
				'href'   => '#',
				'titulo' => 'Informaci�n y Estad�sticas',
				'ncargos' => array('6', '7'),
				'items' => array(
					array(
						'href'   => 'admin/informes/informe_notas1.php',
						'titulo' => 'Informe sobre las Evaluaciones'
					),
					array(
						'href'   => 'admin/fechorias/informe_convivencia.php',
						'titulo' => 'Informe sobre Convivencia'
					),
					array(
						'href'   => 'admin/faltas/informe_profesores.php',
						'titulo' => 'Informe sobre Faltas de Asistencia',
						'modulo' => $config['mod_asistencia'],
					),
					array(
						'href'   => 'admin/cursos/hor_guardias.php',
						'titulo' => 'Informe sobre Guardias',
						'modulo' => $config['mod_horarios'],
					),
					array(
						'href'   => 'admin/ausencias/ausencias_profes.php',
						'titulo' => 'Informe sobre Ausencias de Profesores',
					)
				)
			),
			array(
				'href'   => 'admin/cursos/calendario_escolar.php',
				'titulo' => 'Calendario escolar',
			),
			array(
				'href'   => 'admin/biblioteca/index.php',
				'titulo' => 'Fondos de la Biblioteca',
				'modulo'  => $config['mod_biblioteca'],
				'ncargos' => array('6', '7'),
			),
			array(
				'href'   => 'xml/jefe/index_mayores.php',
				'titulo' => 'Alumnos mayores de 18 a�os',
				'cargos' => array('6'),
			)
		)
	)
);
	
$menu_trabajo =  array(
	array(
		'menu_id' => 'trabajo',
		'nombre'  => 'Trabajo',
		'items'   => array (
			array(
				'href'   => 'admin/matriculas/index.php',
				'titulo' => 'Matriculaci�n de alumnos',
				'cargos' => array('7'),
			),
			array(
				'href'   => 'admin/actividades/indexextra.php',
				'titulo' => 'Actividades extraescolares',
				'cargos' => array('6', '7'),
			),
			array(
				'href'   => '#',
				'titulo' => 'Problemas de convivencia',
				'ncargos' => array('6', '7'),
				'items' => array(
					array(
						'href'   => 'admin/fechorias/infechoria.php',
						'titulo' => 'Registrar problema'
					),
					array(
						'href'   => 'admin/fechorias/cfechorias.php',
						'titulo' => 'Consultar problemas'
					),
					array(
						'href'   => 'admin/fechorias/lfechorias.php',
						'titulo' => '�ltimos problemas'
					),
					array(
						'href'   => 'admin/fechorias/expulsados.php',
						'titulo' => 'Alumnos expulsados'
					),
					array(
						'href'   => 'admin/fechorias/convivencia_jefes.php',
						'titulo' => 'Aula de convivencia',
						'cargos' => array('1'),
					),
					array(
						'href'   => 'admin/fechorias/convivencia.php',
						'titulo' => 'Aula de convivencia',
						'cargos' => array('b'),
						'ncargos' => array('1'),
					)
				)
			),
			array(
				'href'   => '#',
				'titulo' => 'Faltas de asistencia',
				'modulo' => $config['mod_asistencia'],
				'ncargos' => array('6', '7'),
				'items' => array(
					array(
						'href'   => 'faltas/index.php',
						'titulo' => 'Poner faltas',
					),
					array(
						'href'   => 'faltas/poner2/index.php',
						'titulo' => 'Tutor�a de Faltas',
						'cargos' => array('1','3'),
					),

					array(
						'href'   => 'faltas/justificar/index.php',
						'titulo' => 'Justificar faltas',
						'cargos' => array('1','2','3'),
					),
					array(
						'href'   => 'admin/faltas/index.php',
						'titulo' => 'Consultas'
					),
					array(
						'href'   => 'faltas/absentismo/index.php',
						'titulo' => 'Alumnos absentistas',
						'cargos' => array('1'),
					),
					array(
						'href'   => 'admin/tutoria/consulta_absentismo.php',
						'titulo' => 'Alumnos absentistas',
						'cargos' => array('2'),
					),
					array(
						'href'   => 'faltas/seneca/',
						'titulo' => 'Subir faltas a S�neca',
						'cargos' => array('1'),
					),
					array(
						'href'   => 'faltas/seneca/importarSeneca.php',
						'titulo' => 'Descargar faltas de S�neca',
						'cargos' => array('1'),
					),
					array(
						'href'   => 'admin/cursos/horariototal_faltas.php',
						'titulo' => 'Partes de faltas (por d�as)',
						'cargos' => array('1'),
						'target' => '_blank',
					),
					array(
						'href'   => 'admin/faltas/horario_semanal.php',
						'titulo' => 'Partes de faltas (semanal)',
						'cargos' => array('1'),
						'target' => '_blank',
					),
					array(
						'href'   => 'admin/faltas/horario_semanal_div.php',
						'titulo' => 'Partes de faltas (PMAR)',
						'cargos' => array('1'),
						'target' => '_blank',
					),
					array(
						'href'   => 'admin/cursos/horariofaltas.php?horario_profesor=1',
						'titulo' => 'Horario de Faltas del Profesor',
						'ncargos' => array('1'),
						'target' => '_blank',
					),
					array(
						'href'   => 'admin/cursos/ccursos.php',
						'titulo' => 'Partes de Faltas de Grupo',
						'cargos' => array('2'),
					)
				)
			),
			array(
				'href'   => '#',
				'titulo' => 'Calendario',
				'items' => array(
					array(
						'href'   => 'calendario/index.php',
						'titulo' => 'Ver calendario',
					),
					array(
						'href'   => 'calendario/index_unidades.php',
						'titulo' => 'Calendario de unidades',
						'ncargos' => array('6'),
					)
				)
			),
			array(
				'href'   => '#',
				'titulo' => 'Informes',
				'ncargos' => array('6'),
				'items' => array(
					array(
						'href'   => 'admin/informes/cinforme.php',
						'titulo' => 'Informe de un alumno',
					),
					array(
						'href'   => 'admin/tareas/index.php',
						'titulo' => 'Informes de Tareas',
					),
					array(
						'href'   => 'admin/infotutoria/index.php',
						'titulo' => 'Informes de Tutoria',
					)
				)
			),
			array(
				'href'   => '#',
				'titulo' => 'Centro TIC',
				'modulo' => $config['mod_centrotic'],
				'items' => array(
					array(
						'href'   => 'TIC/indexgd.php',
						'titulo' => 'Nueva incidencia',
					),
					array(
						'href'   => 'TIC/incidenciasgd.php',
						'titulo' => 'Listado de incidencias',
					),
					array(
						'href'   => 'TIC/perfiles_alumnos.php',
						'titulo' => 'Perfiles de alumnos',
					),
					array(
						'href'   => 'TIC/perfiles_profesores.php',
						'titulo' => 'Perfiles de profesores',
					),
					array(
						'href'   => 'TIC/protocolo.php',
						'titulo' => 'Protocolo de uso',
					),
					array(
						'href'   => 'TIC/Guia_Rapida_SDI1.pdf',
						'titulo' => 'Manual de la SDI',
						'target' => '_blank',
					),
					array(
						'href'   => 'TIC/estadisticas.php',
						'titulo' => 'Estad�sticas de las TIC',
					)
				)
			),
			array(
				'href'   => '#',
				'titulo' => 'Sistema de Reservas',
				'items' => array(
					array(
						'href'   => 'reservas/index.php?recurso=TIC',
						'titulo' => 'Recursos TIC',
					),
					array(
						'href'   => 'reservas/index.php?recurso=Medios%20Audiovisuales',
						'titulo' => 'Medios Audiovisuales',
					),
					array(
						'href'   => 'reservas/index_aula.php?recurso=aula_grupo',
						'titulo' => 'Aulas y dependencias',
					)
				)
			),
			array(
				'href'   => '#',
				'titulo' => 'Mensajer�a',
				'items' => array(
					array(
						'href'   => 'admin/mensajes/index.php',
						'titulo' => 'Mensajes',
					),
					array(
						'href'   => 'admin/mensajes/correo.php',
						'titulo' => 'Correo electr�nico',
					),
					array(
						'href'   => 'sms/index.php',
						'titulo' => 'Mensajes SMS',
						'cargos'  => array('1'),
						'modulo' => $config['mod_sms'],
					),
				),
			),
			array(
				'href'   => 'admin/ausencias/index.php',
				'titulo' => 'Registrar ausencia',
				'ncargos' => array('1', '6', '7'),
			)
		)
	)
);

$menu_departamento = array(
	array(
		'menu_id' => 'departamento',
		'nombre'  => 'Departamento',
		'ncargos' => array('6', '7'),
		'items'   => array(
			array(
				'href'   => 'admin/rd/index.php',
				'titulo' => 'Actas del Departamento'
			),
			array(
				'href'   => 'admin/textos/intextos.php',
				'titulo' => 'Libros de texto',
				'cargos' => array('1','4'),
			),
			array(
				'href'   => 'admin/textos/consulta.php',
				'titulo' => 'Libros de texto',
				'ncargos' => array('1','4'),
			),
			array(
				'href'   => 'admin/departamento/pedidos/index.php',
				'titulo' => 'Pedidos de material',
				'cargos' => array('1','4'),
			),
			array(
				'href'   => 'admin/inventario/introducir.php',
				'titulo' => 'Inventario de material',
			),
			array(
				'href'   => 'admin/actividades/indexextra.php',
				'titulo' => 'Actividades extraescolares',
			),
			array(
				'href'   => 'admin/departamento/memoria.php',
				'titulo' => 'Memoria de departamento',
			),
			array(
				'href'   => 'admin/evaluacion_pendientes/index.php',
				'titulo' => 'Evaluaci�n de Pendientes',
			),
		)
	)
);

$menu_actas = array(
	'menu_id' => 'actas',
	'titulo'  => 'Actas',
	'cargos' => array('1','4','9','a','f'),
	'items'   => array (
		array(
			'href'   => 'admin/rd/index.php?organo=DFEIE',
			'titulo' => 'Actas del DFEIE',
			'cargos'  => array('f')
		),
		array(
			'href'   => 'admin/rd/index.php?organo=TIC',
			'titulo' => 'Actas del TIC',
			'cargos'  => array('g')
		),
		array(
			'href'   => 'admin/rd/index.php?organo=ETCP',
			'titulo' => 'Actas del ETCP',
			'cargos'  => array('9')
		),
		array(
			'href'   => 'admin/rd/index.php?organo=Equipo directivo',
			'titulo' => 'Actas del Equipo Directivo',
			'cargos'  => array('1')
		),
		array(
			'href'   => 'admin/rd/index.php?organo=Coord. Ense�anzas Biling�es',
			'titulo' => 'Actas de Ens. Biling�es',
			'cargos'  => array('a')
		),
		array(
			'href'   => 'admin/rd/administracion.php',
			'titulo' => 'Administrar actas',
			'cargos'  => array('1')
		)
	)
);

if (file_exists('./admin/rd/config.php')) {
	include('./admin/rd/config.php');
	
	if (isset($config['actas_depto']['secretario_aca']) && $pr == $config['actas_depto']['secretario_aca']) {
		$menu_actas_aca = array(
			'href'   => 'admin/rd/index.php?organo=�rea Art�stica',
			'titulo' => 'Actas del �rea �rt�stica',
			'ncargos'  => array('9')
		);
		
		array_push($menu_actas[0]['items'], $menu_actas_aca);
	}
	
	if (isset($config['actas_depto']['secretario_acct']) && $pr == $config['actas_depto']['secretario_acct']) {
		$menu_actas_acct = array(
			'href'   => 'admin/rd/index.php?organo=�rea Cient�fico-Tecnol�gica',
			'titulo' => 'Actas del �rea Cient�fico-Tecnol�gica',
			'ncargos'  => array('1')
		);
		
		array_push($menu_actas[0]['items'], $menu_actas_acct);
	}
	
	if (isset($config['actas_depto']['secretario_acsl']) && $pr == $config['actas_depto']['secretario_acsl']) {
		$menu_actas_acsl = array(
			'href'   => 'admin/rd/index.php?organo=�rea Social-Ling��stica',
			'titulo' => 'Actas del �rea Social-Ling�istica',
			'ncargos'  => array('1')
		);
		
		array_push($menu_actas[0]['items'], $menu_actas_acsl);
	}
	
	if (isset($config['actas_depto']['secretario_afp']) && $pr == $config['actas_depto']['secretario_afp']) {
		$menu_actas_afp = array(
			'href'   => 'admin/rd/index.php?organo=�rea Formaci�n Profesional',
			'titulo' => 'Actas del �rea Formaci�n Profesional',
			'ncargos'  => array('1')
		);
		
		array_push($menu_actas[0]['items'], $menu_actas_afp);
	}
}

array_push($menu_trabajo[0]['items'], $menu_actas);
	

if ($_SERVER['SERVER_NAME'] == 'iesmonterroso.org') {

	$paginas_interes = array(
		array(
			'menu_id' => 'paginas_interes',
			'nombre'  => 'P�ginas de inter�s',
			'items'   => array (
				array(
					'href'   => 'varios/Planos_Centro.pdf',
					'titulo' => 'Planos del Centro',
					'target' => '_blank',
				),
				array(
					'href'   => '//'.$config['dominio'],
					'titulo' => 'P�gina del '.$config['centro_denominacion'],
					'target' => '_blank',
				),
				array(
					'href'   => 'http://www.iesmonterroso.net/moodle/',
					'titulo' => 'Plataforma Moodle',
					'target' => '_blank',
				),
				array(
					'href'   => 'http://iesmonterroso.org/PC/',
					'titulo' => 'Plan de centro',
					'target' => '_blank',
				),
				array(
					'href'   => 'http://www.juntadeandalucia.es/educacion/webportal/web/delegacion-'.$web_delegacion.'/',
					'titulo' => 'Delegaci�n de Educaci�n',
					'target' => '_blank',
				),
				array(
					'href'   => 'https://www.juntadeandalucia.es/educacion/portaldocente/',
					'titulo' => 'Portal del Personal Docente',
					'target' => '_blank',
				),
				array(
					'href'   => 'http://www.mecd.gob.es/portada-mecd/',
					'titulo' => 'Ministerio de Educaci�n',
					'target' => '_blank',
				),
				array(
					'href'   => 'http://www.juntadeandalucia.es/educacion/portalaverroes',
					'titulo' => 'Portal Averroes',
					'target' => '_blank',
				)
			)
		)
	);

}
else {
	
	$paginas_interes = array(
		array(
			'menu_id' => 'paginas_interes',
			'nombre'  => 'P�ginas de inter�s',
			'items'   => array (
				array(
					'href'   => '//'.$config['dominio'],
					'titulo' => 'P�gina del '.$config['centro_denominacion'],
					'target' => '_blank',
				),
				array(
					'href'   => 'http://www.juntadeandalucia.es/averroes/centros-tic/'.$config['centro_codigo'].'/',
					'titulo' => 'Servidor de contenidos',
					'target' => '_blank',
				),
				array(
					'href'   => 'http://www.juntadeandalucia.es/educacion/webportal/web/delegacion-'.$web_delegacion.'/',
					'titulo' => 'Delegaci�n de Educaci�n',
					'target' => '_blank',
				),
				array(
					'href'   => 'https://www.juntadeandalucia.es/educacion/portaldocente/',
					'titulo' => 'Portal del Personal Docente',
					'target' => '_blank',
				),
				array(
					'href'   => 'http://www.mecd.gob.es/portada-mecd/',
					'titulo' => 'Ministerio de Educaci�n',
					'target' => '_blank',
				),
				array(
					'href'   => 'http://www.juntadeandalucia.es/educacion/portalaverroes',
					'titulo' => 'Portal Averroes',
					'target' => '_blank',
				),
				array(
					'href'   => 'http://educacionadistancia.juntadeandalucia.es/profesorado/',
					'titulo' => 'Aula Virtual del Profesorado',
					'target' => '_blank',
				)
			)
		)
	);
	
}

$menu = array_merge($menu, $menu_trabajo, $menu_departamento, $paginas_interes);
?>
<!-- MENU-LATERAL -->

<!-- PHONE SCREENS -->
<div class="visible-xs">
	<div class="row">
		<?php if (isset($config['mod_asistencia']) && $config['mod_asistencia']): ?>
		<div class="col-xs-3 text-center">
			<a href="faltas/index.php">
				<span class="fa fa-clock-o fa-2x"></span><br>
				Asistencia</a>
		</div>
		<?php endif; ?>
		<div class="col-xs-3 text-center">
			<a href="admin/fechorias/infechoria.php">
				<span class="fa fa-gavel fa-2x"></span><br>
				Convivencia</a>
		</div>
		<div class="col-xs-3 text-center">
			<a href="admin/mensajes/redactar.php">
				<span class="fa fa-comments fa-2x"></span><br>
				Mensajes</a>
		</div>
		<div class="col-xs-3 text-center">
			<a href="#" id="toggleMenu">
				<span class="fa fa-ellipsis-h fa-2x"></span><br>
				Men�</a>
		</div>
	</div>
	<br>
</div>

<!-- TABLETS / DESKTOPS SCREENS  -->
<div class="panel-group hidden-xs" id="accordion">
<?php $nmenu = 1; ?>
<?php for($i=0 ; $i < count($menu) ; $i++): ?>
<?php if(!isset($menu[$i]['modulo']) || ($menu[$i]['modulo'] == '1')): ?>
<?php if(!isset($menu[$i]['cargos']) || in_array($carg[0], $menu[$i]['cargos']) || in_array($carg[1], $menu[$i]['cargos']) || in_array($carg[2], $menu[$i]['cargos']) || in_array($carg[3], $menu[$i]['cargos']) || in_array($carg[4], $menu[$i]['cargos'])): ?>
<?php if(!isset($menu[$i]['ncargos']) || !in_array($carg[0], $menu[$i]['ncargos']) && !in_array($carg[1], $menu[$i]['ncargos']) && !in_array($carg[2], $menu[$i]['ncargos']) && !in_array($carg[3], $menu[$i]['ncargos']) && !in_array($carg[4], $menu[$i]['ncargos'])): ?>
<?php if(!isset($menu[$i]['meses']) || in_array(date('n'), $menu[$i]['meses'])): ?>
	<div class="panel panel-default">
	  <div class="panel-heading">
	    <h4 class="panel-title">
	      <a data-toggle="collapse" data-parent="#accordion" href="#<?php echo $menu[$i]['menu_id']; ?>" style="display: block;">
	      	<span class="fa fa-chevron-down pull-right"></span>
	        <?php echo $menu[$i]['nombre']; ?>
	      </a>
	    </h4>
	  </div>
	  <div id="<?php echo $menu[$i]['menu_id']; ?>" class="panel-collapse collapse <?php echo ($nmenu == 1 || ($menu[$i]['menu_id'] == 'departamento' && strstr($_SESSION['cargo'],'4')==TRUE)) ? 'in' : ''; ?>">
	    <div class="panel-body">
	    	<?php if(count($menu[$i]['items']) > 0): ?>
				<ul class="nav nav-pills nav-stacked">
					<?php $count=0; ?>
					<?php for($j=0 ; $j < count($menu[$i]['items']) ; $j++): ?>
					<?php if(isset($menu[$i]['items'][$j]['items'])): ?>
					<?php if(!isset($menu[$i]['items'][$j]['modulo']) || ($menu[$i]['items'][$j]['modulo'] == '1')): ?>
					<?php if(!isset($menu[$i]['items'][$j]['cargos']) || in_array($carg[0], $menu[$i]['items'][$j]['cargos']) || in_array($carg[1], $menu[$i]['items'][$j]['cargos']) || in_array($carg[2], $menu[$i]['items'][$j]['cargos']) || in_array($carg[3], $menu[$i]['items'][$j]['cargos']) || in_array($carg[4], $menu[$i]['items'][$j]['cargos'])): ?>
					<?php if(!isset($menu[$i]['items'][$j]['ncargos']) || !in_array($carg[0], $menu[$i]['items'][$j]['ncargos']) && !in_array($carg[1], $menu[$i]['items'][$j]['ncargos']) && !in_array($carg[2], $menu[$i]['items'][$j]['ncargos']) && !in_array($carg[3], $menu[$i]['items'][$j]['ncargos']) && !in_array($carg[4], $menu[$i]['items'][$j]['ncargos'])): ?>
					<?php if(!isset($menu[$i]['items'][$j]['meses']) || in_array($carg, $menu[$i]['items'][$j]['meses'])): ?>
					<li><a data-toggle="collapse" href="#<?php echo $menu[$i]['menu_id']; ?>-submenu<?php echo $count; ?>">
						<span class="fa fa-chevron-down pull-right"></span>
						<?php echo $menu[$i]['items'][$j]['titulo']; ?></a>
					</li>
					<div id="<?php echo $menu[$i]['menu_id']; ?>-submenu<?php echo $count; ?>" class="collapse">
						<ul class="nav nav-pills nav-stacked">
							<?php for($k=0 ; $k < count($menu[$i]['items'][$j]['items']) ; $k++): ?>
							<?php if(!isset($menu[$i]['items'][$j]['items'][$k]['modulo']) || ($menu[$i]['items'][$j]['items'][$k]['modulo'] == '1')): ?>
							<?php if(!isset($menu[$i]['items'][$j]['items'][$k]['cargos']) || in_array($carg[0], $menu[$i]['items'][$j]['items'][$k]['cargos']) || in_array($carg[1], $menu[$i]['items'][$j]['items'][$k]['cargos']) || in_array($carg[2], $menu[$i]['items'][$j]['items'][$k]['cargos']) || in_array($carg[3], $menu[$i]['items'][$j]['items'][$k]['cargos']) || in_array($carg[4], $menu[$i]['items'][$j]['items'][$k]['cargos'])): ?>
							<?php if(!isset($menu[$i]['items'][$j]['items'][$k]['ncargos']) || !in_array($carg[0], $menu[$i]['items'][$j]['items'][$k]['ncargos']) && !in_array($carg[1], $menu[$i]['items'][$j]['items'][$k]['ncargos']) && !in_array($carg[2], $menu[$i]['items'][$j]['items'][$k]['ncargos']) && !in_array($carg[3], $menu[$i]['items'][$j]['items'][$k]['ncargos']) && !in_array($carg[4], $menu[$i]['items'][$j]['items'][$k]['ncargos'])): ?>
							<?php if(!isset($menu[$i]['items'][$j]['items'][$k]['meses']) || in_array(date('n'), $menu[$i]['items'][$j]['items'][$k]['meses'])): ?>
							<li style="margin-left:20px;"><a href="<?php echo $menu[$i]['items'][$j]['items'][$k]['href']; ?>" <?php echo ($menu[$i]['items'][$j]['items'][$k]['target'] == '_blank') ? 'target="_blank"' : ''; ?>><?php echo $menu[$i]['items'][$j]['items'][$k]['titulo']; ?></a></li>
							<?php endif; ?>
							<?php endif; ?>
							<?php endif; ?>
							<?php endif; ?>
							<?php endfor; ?>
						</ul>
					</div>
					<?php $count++; ?>
					<?php endif; ?>
					<?php endif; ?>
					<?php endif; ?>
					<?php endif; ?>
					<?php else: ?>
					<?php if(!isset($menu[$i]['items'][$j]['modulo']) || ($menu[$i]['items'][$j]['modulo'] == '1')): ?>
					<?php if(!isset($menu[$i]['items'][$j]['cargos']) || in_array($carg[0], $menu[$i]['items'][$j]['cargos']) || in_array($carg[1], $menu[$i]['items'][$j]['cargos']) || in_array($carg[2], $menu[$i]['items'][$j]['cargos']) || in_array($carg[3], $menu[$i]['items'][$j]['cargos']) || in_array($carg[4], $menu[$i]['items'][$j]['cargos'])): ?>
					<?php if(!isset($menu[$i]['items'][$j]['ncargos']) || !in_array($carg[0], $menu[$i]['items'][$j]['ncargos']) && !in_array($carg[1], $menu[$i]['items'][$j]['ncargos']) && !in_array($carg[2], $menu[$i]['items'][$j]['ncargos']) && !in_array($carg[3], $menu[$i]['items'][$j]['ncargos']) && !in_array($carg[4], $menu[$i]['items'][$j]['ncargos'])): ?>
					<?php if(!isset($menu[$i]['items'][$j]['meses']) || in_array(date('n'), $menu[$i]['items'][$j]['meses'])): ?>
					<li><a href="<?php echo $menu[$i]['items'][$j]['href']; ?>" <?php echo ($menu[$i]['items'][$j]['target'] == '_blank') ? 'target="_blank"' : ''; ?>><?php echo $menu[$i]['items'][$j]['titulo']; ?></a></li>
					<?php endif; ?>
					<?php endif; ?>
					<?php endif; ?>
					<?php endif; ?>
					<?php endif; ?>
					<?php endfor; ?>
				</ul>
				<?php endif; ?>
	    </div>
	  </div>
	</div>
<?php $nmenu++; ?>
<?php endif; ?>
<?php endif; ?>
<?php endif; ?>
<?php endif; ?>
<?php endfor; ?>
</div>

<?php
// Eliminamos las variables usadas
unset($menu);
unset($paginas_interes);
unset($nmenu);
unset($count);
unset($web_delegacion);
?>

<!-- FIN MENU-LATERAL -->
