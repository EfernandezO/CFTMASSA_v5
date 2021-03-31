<?php
//-----------------------------------------//
	require("../OKALIS/seguridad.php");
	require("../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
//-----------------------------------------//	
?>
<html>
<head>
<title>Menu Docentes | cftmass</title>
<?php include("../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../libreria_publica/jquery_treeview/jquery.treeview.css">
	<link rel="stylesheet" type="text/css" href="../libreria_publica/jquery_treeview/demo/screen.css">
	
	
	<script type="text/javascript" src="../libreria_publica/jquery_libreria/jquery.min_1.2.6.js"></script>
	<script src="../libreria_publica/jquery_treeview/lib/jquery.cookie.js" type="text/javascript"></script>
	<script src="../libreria_publica/jquery_treeview/jquery.treeview.js" type="text/javascript"></script>
	
	<script type="text/javascript">
		$(function() {
			$("#browser").treeview();
		});
	</script>
<style type="text/css">
<!--
a:link {
	text-decoration: none;
	color: #6699FF;
}
a:visited {
	text-decoration: none;
	color: #6699FF;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #6699FF;
}
.Estilo6 {font-weight: bold}
.Estilo7 {font-weight: bold}
-->
</style>
<!--INICIO MENU HORIZONTAL-->
 <link rel="stylesheet" type="text/css" href="../libreria_publica/menu_horizontal/ddsmoothmenu-v.css"/>  
  <link rel="stylesheet" type="text/css" href="../libreria_publica/menu_horizontal/ddsmoothmenu.css"/>
 
<script type="text/javascript" src="../libreria_publica/menu_horizontal/ddsmoothmenu.js">

/***********************************************
* Smooth Navigational Menu- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

</script>

<script type="text/javascript">

ddsmoothmenu.init({
	mainmenuid: "smoothmenu1", //menu DIV id
	orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu', //class added to menu's outer DIV
	//customtheme: ["#1c5a80", "#18374a"],
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})

ddsmoothmenu.init({
	mainmenuid: "smoothmenu2", //Menu DIV id
	orientation: 'v', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu-v', //class added to menu's outer DIV
	//customtheme: ["#804000", "#482400"],
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})

</script>
<!--FIN MENU HORIZONTAL-->
</head>
 <?php 
	//var_dump($_SESSION);
  $docente_nombre=$_SESSION["USUARIO"]["nombre"]." ".$_SESSION["USUARIO"]["apellido"];
  $privilegio=$_SESSION["USUARIO"]["privilegio"];
  ?>
<body>
<h1 id="banner">Docentes - Men&uacute; Principal </h1>
 <div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a href="#">Aplicaciones</a>
  <ul>
  <li><a href="../dactilografia/Lecciones_disponibles.php">Dactilografia</a></li>
  <li><a href="../downloadXasignatura/carga/index.php">Recursos X Curso</a></li>
  </ul>
</li>
<li><a href="#">Utilidades</a>
  <ul>
    <li><a href="#">Asistencia</a></li>
  <li><a href="../agenda_contactos/visor_agenda/index.php">Agenda de Contactos</a></li>
  <li><a href="../archivador_Documentos_institucionales/index.php">Documentos Institucionales</a></li>
  </ul>
</li>
<li><a href="#">Mis Datos</a>
  <ul>
  <li><a href="mis_datos/datos_personales/mdocentex.php">Datos Personales</a></li>
  <li><a href="mis_datos/cambio_clave/cambio_clave_1.php">Cambio Clave</a></li>
  <li><a href="mis_datos/estudios_laborales/estudio_1.php">Estudio y Trabajo</a></li>
  <li><a href="asignacion_asignaturas/ver_asignaciones/ver_mis_asignaciones.php">Asignaciones</a></li>
  </ul>
</li>
<li><a href="#">Encuestas</a>
  <ul>
  <li><a href="../gestion_encuestas/index.php">Encuestas General</a></li>
  <li><a href="resultados_evalucion_docente/resultado_evaluacion_docente_1.php">Resultados Evaluacion Docente</a></li>
  <li><a href="autoevaluacion_docente/autoevaluacion_docente_1.php">Autoevaluacion Docente</a></li>
  
   <li><a href="evaluacion_JC_D/evaluacion_JC_D_1.php">Coevaluacion [JC -> D]</a></li>
  
  </ul>
</li>
<li><a href="#">Capacitacion</a>
  <ul>
  <li><a href="carga_descarga_tareas_docente/carga_tareas_de_docentes/index.php">Carga de Tareas</a></li>  
  </ul>
</li>
<li><a href="../OKALIS/msj_error/salir.php">Salir</a></li>
</ul>
<br style="clear: left" />
</div> 
<h3>Bienvenido(a) Sr(ita).: <?php echo $docente_nombre;?><br>
Privilegio de: <?php echo $privilegio;?></h3>
<h4>¿Que desea Hacer?</h4>
	<div id="main">
	  <ul id="browser" class="filetree">
      <li class="Estilo1 Estilo6"><img src="../libreria_publica/jquery_treeview/images/folder.gif" alt="-" /><a href="../buscador_alumno_BETA/HALL/index.php">Alumno</a> (selector)<span class="Estilo1"><img src="../BAses/Images/NEW.gif" alt="new" width="31" height="16"></span></li>
      <li class="Estilo1 Estilo6"><img src="../libreria_publica/jquery_treeview/images/folder.gif" alt="-" /> Libro de Clases <span class="Estilo1"><img src="../BAses/Images/NEW.gif" alt="new" width="31" height="16"></span>
        <ul>
	    <li><img src="../libreria_publica/jquery_treeview/images/file.gif" alt="as" /><a href="selectorAsignaturaDocenteUnificado/index.php">Selector de Curso/Asignatura</a></li>
	  </ul>
	  </li>
	  <li class="Estilo1 Estilo6"><img src="../libreria_publica/jquery_treeview/images/folder.gif" alt="-" /> Pruebas<ul>
	    <li><a href="../Notas/consultanotas.php"><strong><img src="../libreria_publica/jquery_treeview/images/file.gif" alt="as" /></strong></a> <a href="../banco_pruebas/index.php">Banco de Pruebas</a></li>
	  </ul>
	  </li>
      <?php if($privilegio=="jefe_carrera"){?>
	  <li class="Estilo1"><strong><img src="../libreria_publica/jquery_treeview/images/folder.gif" alt="as" width="16" height="14" /> Informes</strong>
        <ul class="Estilo7">
          <li><a href="../informes/alumno_aprobacion_asignatura/index.php"><strong><img src="../libreria_publica/jquery_treeview/images/file.gif" alt="as" /></strong>Nivel Aprobacion de Alumnos X asignatura</a></li>
         <li><a href="../informes/alumno_aprobacion_asignatura/index.php"><strong><img src="../libreria_publica/jquery_treeview/images/file.gif" alt="as" /></strong></a><a href="planificaciones_v2/revision_planificaciones/revision_planificaciones_1.php">Revision Planificacion</a></li>
         <li><a href="../informes/alumno_aprobacion_asignatura/index.php"><strong><img src="../libreria_publica/jquery_treeview/images/file.gif" alt="as" /></strong></a><a href="../gestion_actas_reunion/index.php">Actas Reunión</a></li>
         <li><a href="#"><strong><img src="../libreria_publica/jquery_treeview/images/file.gif" alt="as" /></strong></a><a href="../informes/alumno_carrera_asignatura_jefe_carrera/alumno_carrera_asignatura_1.php">Alumnos X Asignatura</a></li>
        </ul>
      </li>
      <?php }?>
	  <li class="Estilo1"><strong><img src="../libreria_publica/jquery_treeview/images/file.gif" alt="as" /> <a href="../Mensajes/ver_mjs/ver_mjs.php">Mensajes</a></strong></li>
	  </ul>	
</div>
</body>
</html>