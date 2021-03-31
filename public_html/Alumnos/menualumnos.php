<?php
//--------------CLASS_okalis------------------//
	require("../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../funciones/";
	$O->clave_del_archivo=md5("Menu_de_alumnos_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	$privilegios=$_SESSION["privilegio"];
	$id_usuario_activo=$_SESSION["USUARIO"]["id"];
      	$privilegio=$_SESSION["USUARIO"]["privilegio"];
		if($privilegio=="matricula")
		{$url_menu="../Administrador/menu_matricula/index.php";}
		else
		{$url_menu="../Administrador/ADmenu.php";}
		
		/////////////////////////////////////////////////////////////
		require("../../funciones/conexion_v2.php");
	/////-----------------------------
	//session para el CHAT
	/////----------------------------------
	$_SESSION["CHAT"]['nick'] = $_SESSION["USUARIO"]["nick"]; // Must be already set
	//busco usuarios activos
	include("../../funciones/VX.php");
	//cambio estado_conexin USER-----------
	 CAMBIA_ESTADO_CONEXION($id_usuario_activo, "on");
	$array_usuarios_activos=USUARIOS_ACTIVOS($id_usuario_activo);
//------------------------------------------
	//@mysql_close($conexion);
	$conexion_mysqli->close();
?>
<html>
<head>
<title>Menu Alumnos</title>
<?php include("../../funciones/codificacion.php");?>

	<link rel="stylesheet" type="text/css" href="../libreria_publica/jquery_treeview/demo/screen.css">
	<script type="text/javascript" src="../libreria_publica/jquery_libreria/jquery.min_1.2.6.js"></script>
<!--INICIO MENU HORIZONTAL-->
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
</script>
<!--FIN MENU HORIZONTAL-->
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 163px;
	text-align: left;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:37px;
	z-index:2;
	left: 5%;
	top: 451px;
	text-align: center;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Men&uacute; Alumnos </h1>
<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a href="#">Gestion</a>
	<ul>
    	 <li><a href="../buscador_alumno_BETA/HALL/index.php">Gesti&oacute;n de Alumnos</a></li>
    </ul>
</li>
    <li><a href="#">Informes</a>
    <ul>
        <li><a href="#">Generales</a>
        	<ul>
           <!--<li><a href="../informes/alumnos_aprobacion_ramos/index.php">Alumnos, Porcentaje Aprobacion</a></li>-->
           <li><a href="../informes/ranking_alumno/index.php">Rankig Alumno</a></li>
			<li><a href="../informes/duracionCarrerra/duracionCarrera.php">Duracion Carreras</a></li>
	 <!--     <li><a href="../informes/comprobar_egresados/index.php">Comprobar Egresado</a></li>-->
          <li><a href="../informes/alumnos_egresados_y_titulados_ok/index.php">Egresados/Titulados</a></li>
	      <li><a href="../informes/informe_alumnos_retirados/index.php"> Alumnos Retirados/Postergados</a></li>
	      <li><a href="../informes/alumno_asignatura_pendiente/index.php">Situaciones Pendientes</a></li>
	      <li><a href="../informes/alumnos_curso_contrato/index.php">Alumnos por Curso</a></li>
         <!-- <li><a href="../informes/informe_alumno_proceso_titulacion/index.php">Alumno-Proceso Titulacion</a></li>-->
         <!-- <li><a href="../informes/alumnos_X_nivel_year_ingreso/index.php">Alumno X Nivel, A&#324;o</a></li>-->
         <li><a href="../informes/resumen_general/resumen_alumno_proceso_titulacion_x.php">Resumen General Alumno con proceso Titulacion</a></li>
         <li><a href="../informes/resumen_general/resumen_general_egresados.php">Resumen General Egresados</a></li>
       
            </ul>
        <li>
        <li><a href="#">Matricula</a>
        	<ul>
          <li><a href="../informes/cohorte_institucional/cohorte_institucional_1_v2.php">Cohorte institucional</a></li>
          <li><a href="../informes/resumen_general/resumen_general_x.php">Resumen General</a></li>
          <li><a href="../informes/resumen_general/resumen_general_alumnos_matriculados_nivel_1.php">Resumen General matriculas nivel 1</a></li>
          <li><a href="../informes/memoria/memoria_matriculas.php">Memoria Matriculas</a></li>
          <li><a href="../informes/memoria/memoria_matriculas_solo_alumnos_nivel_1.php">Memoria Matriculas Nivel 1</a></li>
          <li><a href="../informes/informe_matriculas_estadisticas/index.php">Comparativo Matricula</a></li>
            </ul>
        <li>
        <li><a href="#">SIES</a>
        	<ul>
            	 <li><a href="../informes/alumnos_matriculados_formato_sies/index.php">Alumnos Matriculados Formato SIES</a></li>
          <li><a href="../informes/alumnos_titulados_formato_sies/index.php"> Alumnos Titulados Formato SIES</a></li>
          <li><a href="../informes/alumnos_egresados_formato_sies/index.php"> Alumnos Egresados Formato SIES</a></li>
          <li><a href="../informes/alumnosXyear_ingreso_formato_sies/index.php">Alumnos X a&ntilde;o Ingreso Formato SIES</a></li>
          <li><a href="../informes/personal_academico_sies/index.php">Sies Docentes</a></li>
            </ul>
        </li>
        <li><a href="#">Super. de Salud</a>
        <ul>
        	<li><a href="../informes/alumnos_tens_formato_super_int_salud/index.php">Generador de Archivo Para Carga Masiva</a></li>
        </ul>
        </li>
        <li><a href="#">Otros</a>
        <ul>
        	<li><a href="../informes/alumnos_solicitan_pase/alumno_solicita_pase.php">Alumnos Solicitan Pase Esc.</a></li>
        </ul>
        </li>
         <li><a href="#">Aprobacion</a>
        <ul>
        	 <li><a href="../informes/alumno_aprobacion_asignatura/index.php">Aprobacion General</a></li>
        </ul>
        </li>
         <li><a href="#">Retencion</a>
        <ul>
        	  <li><a href="../informes/retencion/seguimientoXcohorte/index.php">Seguimiento X Cohorte</a></li>
               <li><a href="../informes/retencion/seguimiento_semestral/seguimiento_1.php">Seguimiento Semestral</a></li>
        </ul>
        </li>
        <li><a href="#">CNED</a>
        <ul>
        	<li><a href="../informes/informe_notas_X_toma_ramo/informe_notas_X_toma_ramo.php">Notas Semestrales (toma ramo)</a></li>
            <li><a href="../informes/alumnos_estadisticas/alumno_estadisticas.php">Origen Alumnos</a></li>
            <li><a href="../informes/CNED_numeroAlumnosXasignatura/AsignaturaXsemestre.php">Asignaturas X Semestre</a></li>
            <li><a href="../informes/cnedCohorte/cnedHistoricoAnual.php">Cned historico Anual</a></li>
            <li><a href="../informes/cnedCohorte/cnedCohorte.php">Cned Cohortes</a></li>
            
        </ul>
        </li>
         <li><a href="#">Moodle</a>
        <ul>
        	<li><a href="../informes/informeMoodle/actividadCursos.php">Actividad de Cursos [Full xls]</a></li>
            <li><a href="../informes/informeMoodle/accesosUsuario.php">accesos de Usuario [xls]</a></li>
            
        </ul>
        </li>
    </ul>
    </li>
    <li><a href="#">Operaciones</a>
        <ul>
            <li><a href="#">Notas Parciales</a>
            	<ul>
               		<li><a href="../Notas_parciales_3/evaluaciones/index.php">Ingreso Notas Parciales</a></li>
                    <li><a href="../Notas_parciales_3/revisionNotas/revisionNotas.php">Revision Evaluaciones</a></li>
                </ul>
            </li>
            <li><a href="gestion_actas_titulo/index.php">Actas</a></li>
            <li><a href="exportar_moodle/index.php">Exportar a Moodle</a></li>
            <li><a href="exportar_gsuite/index.php">Exportar a Gsuite</a></li>
        </ul>
    </li>
    <li><a href="#">Curso</a>
    <ul>
        <li><a href="#">Plantillas</a>
        	<ul>
           <li><a href="plantillas_carpeta_asignatura/relacion_asistencia.php">Asistencia</a></li> 
           <li><a href="plantillas_carpeta_asignatura/resumen_asistencia.php">Resumen Asistencia</a></li> 
           <li><a href="plantillas_carpeta_asignatura/hoja_materias.php">Materias</a></li> 
           <li><a href="plantillas_carpeta_asignatura/calificaciones.php">Calificaciones</a></li> 
           </ul>
        </li>
    </ul>       
    <li><a href="#">ON-line</a>
        
        <ul>
            <?php
            if(isset($array_usuarios_activos))
            {
                if($array_usuarios_activos[0]!="No hay usuarios")
                {
                    foreach($array_usuarios_activos as $nua=>$valorua)
                    {
                        echo'<li><a href="javascript:void(0)" onclick="javascript:chatWith(\''.$valorua.'\')">Chat con '.$valorua.'</a></li>';
                    }
                }
                else
                {
                    echo'<li><a href="#">No hay Usuarios Conectados :(</a></li>';
                }
            }
            else
            {
                echo'<li><a href="#">No hay Usuarios Conectados :[</a></li>';
            }
            ?>
        </ul>
    
    </li>
    <li><a href="<?php echo $url_menu;?>">Menu Principal</a></li>
</ul>
<br style="clear: left" />
</div>
<div id="apDiv1">
  <p><h3>Acceso Rapido.</h3></p>
  <br>
  <br>
  <p><a href="../buscador_alumno_BETA/HALL/index.php" class="button_G">Gesti&oacute;n Alumno</a></p>
  <br><br>

  <p><a href="../informes/alumnos_curso_contrato/index.php" class="button_G">Alumnos X Curso</a></p>
  <br><br>

  <p><a href="../informes/alumno_carrera_asignatura/alumno_carrera_asignatura_1.php" class="button_G">Alumnos X Asignatura</a></p>
</div>
</body>
</html>