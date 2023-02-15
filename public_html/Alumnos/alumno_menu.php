<?php
//-----------------------------------------//
/*	require("../OKALIS/seguridad.php");
	require("../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="ALUMNO";
	OKALIS($lista_invitados);
	define("DEBUG",false);
	require("../OKALIS/class_OKALIS_v1.php");
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../funciones/";
	$O->anti2LoggAlumno();
	*/
//-----------------------------------------//	
//--------------CLASS_okalis------------------//
	require("../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../funciones/";
	$O->clave_del_archivo=md5("ALUMNO->intranet");
	$O->PERMITIR_ACCESO_USUARIO();
	$O->anti2LoggAlumno();
//--------------FIN CLASS_okalis---------------//
   require('../../funciones/conexion_v2.php');
   
   $mes_actual=date("m");
   $year_actual=date("Y");
   if($mes_actual>=8){ $semestre_actual=2;}
   else{ $semestre_actual=1;}
   
   $id_alumno=$_SESSION["USUARIO"]["id"];
   $id_carrera=$_SESSION["USUARIO"]["id_carrera"];
   $cons="SELECT * FROM alumno WHERE id='$id_alumno' LIMIT 1";
   //--------------------------------------------------//
 	 include("../../funciones/VX.php");
	 //cambio estado_conexion USER-----------
	 CAMBIA_ESTADO_CONEXION_ALUMNO($id_alumno, "ON");
	  $evento="Ingreso a Intranet Alumno Menu Principal";
   	 REGISTRA_EVENTO($evento);
	//-----------------------------------------------//
	
	//datos del alumnos
	$ruta_imagen="../CONTENEDOR_GLOBAL/img_alumnos/";
   $sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
   $row = $sqli->fetch_assoc();
   
   		$imagen_alumno=$row["imagen"];
		if(empty($imagen_alumno)){ $imagen_alumno="../BAses/Images/login_logo.png";}
		else{ $imagen_alumno=$ruta_imagen.$imagen_alumno;}
		
    	$nombre=$row["nombre"];
    	$apellido_P=$row["apellido_P"];
		$apellido_M=$row["apellido_M"];
    	$carrera=$row["carrera"];
		$sede=$row["sede"];
		$nivel=$row["nivel"];
		$grupo=$row["grupo"];
		$jornada=$row["jornada"];
		$nombre_alumno="$nombre $apellido_P $apellido_M";
	$sqli->free();
	$msj_AVISOS="";
	///////////////////
	
	//avisos
	$cons2="SELECT * FROM avisos WHERE id_carrera='$id_carrera' AND sede='$sede'";
	$sql2=$conexion_mysqli->query($cons2)or die($conexion_mysqli->error);
		$numero_avisos=$sql2->num_rows;
		if(empty($numero_avisos)){ $numero_avisos=0; $numero_avisos_label="";}else{$numero_avisos_label=$numero_avisos;}

		///resumen de avisos
		$msj_AVISOS=' <ul class="dropdown-menu extended notification">';
		if($numero_avisos>0)
		{
				
				$msj_AVISOS.='<li>
							<p>Hay '.$numero_avisos.' Avisos</p>
						</li>';			
		}
		else
		{ 
			$msj_AVISOS.='
							<li>
								<p>No hay Avisos</p>
							</li>';
		}
		///deglose de avisos
		while($A=$sql2->fetch_assoc())
		{
			$A_docente=$A["quien_se_ausenta"];
			$A_observacion=$A["observacion"];
			$A_fecha_generacion=$A["fecha_generacion"];
			
			$msj_AVISOS.='<li>
						   <a href="#">
							   <span class="label label-important"><i class="icon-bolt"></i></span>
							  '.$A_docente.' '.$A_observacion.'
							   <span class="small italic">'.$A_fecha_generacion.'</span>
						   </a>
					   </li>';
		}                     
        $msj_AVISOS.='</ul>';  
		$sql2->free();               
	///////////////////////////
		///////////
	//revision solicitudes
	
	//Solicitudes//
$ARRAY_SOLICITUDES=array();
$num_solicitudes_autorizadas=0;
$num_solicitudes_no_autorizadas=0;

	$cons="SELECT * FROM solicitudes WHERE tipo_receptor='alumno' AND id_receptor='$id_alumno' AND id_carrera_receptor='$id_carrera' AND estado='pendiente' ORDER by id Desc";
		$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		$num_solicitudes=$sql->num_rows;
		if(DEBUG){ echo"--> $cons<br>Num Solicitudes: $num_solicitudes<br>";}
		if($num_solicitudes>0)
		{
			$aux=0;
			while($S=$sql->fetch_assoc())
			{
				$aux++;
				$S_id=$S["id"];
				$S_tipo=$S["tipo"];
				$S_categoria=$S["categoria"];
				$S_tipo_solicitante=$S["tipo_solicitante"];
				$S_id_solicitante=$S["id_solicitante"];
				$S_id_carrera_solicitante=$S["id_carrera_solicitante"];
				$S_fecha_hora_solicitud=$S["fecha_hora_solicitud"];
				$S_autorizado=$S["autorizado"];
				$S_id_autorizador=$S["id_autorizador"];
				$S_tipo_autorizador=$S["tipo_autorizador"];
				$S_fecha_hora_autorizacion=$S["fecha_hora_autorizacion"];
				$S_estado=$S["estado"];
				$S_fecha_hora_creacion=$S["fecha_hora_creacion"];
				
				if($S_autorizado=="no")
				{ $num_solicitudes_no_autorizadas++;}
				elseif($S_autorizado=="si")
				{ $num_solicitudes_autorizadas++;}
				
				$ARRAY_SOLICITUDES[$S_id]["categoria"]=$S_categoria;
				$ARRAY_SOLICITUDES[$S_id]["autorizado"]=$S_autorizado;
			}
		}
	
	
	$sql->free();
	$conexion_mysqli->close();
?>
<!DOCTYPE html>
<html lang="en"><!--<![endif]--><!-- BEGIN HEAD -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
   <meta charset="utf-8">
   <title>Intranet | Menu Principal Alumnos</title>
   <meta content="width=device-width, initial-scale=1.0" name="viewport">
   <meta content="" name="description">
   
 
   <link href="../libreria_publica/archivos_stilo_1/bootstrap.min.css" rel="stylesheet">
   <link href="../libreria_publica/archivos_stilo_1/bootstrap-responsive.min.css" rel="stylesheet">
   <link href="../libreria_publica/archivos_stilo_1/font-awesome.css" rel="stylesheet">
   <link href="../libreria_publica/archivos_stilo_1/style.css" rel="stylesheet">
   <link href="../libreria_publica/archivos_stilo_1/style-responsive.css" rel="stylesheet">
   <link href="../libreria_publica/archivos_stilo_1/style-default.css" rel="stylesheet" id="style_color">
   
<style type="text/css">.jqstooltip { position: absolute;left: 0px;top: 0px;visibility: hidden;background: rgb(0, 0, 0) transparent;background-color: rgba(0,0,0,0.6);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";color: white;font: 10px arial, san serif;text-align: left;white-space: nowrap;padding: 5px;border: 1px solid white;}.jqsfield { color: white;font: 10px arial, san serif;text-align: left;}</style></head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="fixed-top">
   <!-- BEGIN HEADER -->
   <div id="header" class="navbar navbar-inverse navbar-fixed-top">
       <!-- BEGIN TOP NAVIGATION BAR -->
       <div class="navbar-inner">
           <div class="container-fluid">
               <!--BEGIN SIDEBAR TOGGLE-->
               <div class="sidebar-toggle-box hidden-phone">
                   <div class="icon-reorder tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
               </div>
               <!--END SIDEBAR TOGGLE-->
               <!-- BEGIN LOGO -->
               <a class="brand" href="http://www.cftmass.cl">
                   <img src="../BAses/Images/logo.png" alt="Metro Lab">
               </a>
               <!-- END LOGO -->
               <!-- BEGIN RESPONSIVE MENU TOGGLER -->
               <a class="btn btn-navbar collapsed" id="main_menu_trigger" data-toggle="collapse" data-target=".nav-collapse">
                   <span class="icon-bar"></span>
                   <span class="icon-bar"></span>
                   <span class="icon-bar"></span>
                   <span class="arrow"></span>
               </a>
               <!-- END RESPONSIVE MENU TOGGLER -->
               <div id="top_menu" class="nav notify-row">
                   <!-- BEGIN NOTIFICATION -->
                   <ul class="nav top-menu">
                       <!-- BEGIN SETTINGS -->
                       <li class="dropdown">
                           <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                               <i class="icon-tasks"></i>
                               <span class="badge badge-important">1</span>
                           </a>
                           <ul class="dropdown-menu extended tasks-bar">
                               <li>
                                   <p>1 Tarea pendiente</p>
                               </li>
                               <li>
                                   <a href="#">
                                       <div class="task-info">
                                         <div class="desc">Menu v1.3</div>
                                         <div class="percent">80%</div>
                                       </div>
                                       <div class="progress progress-striped active no-margin-bot">
                                           <div class="bar" style="width: 80%;"></div>
                                       </div>
                                   </a>
                               </li>
                           </ul>
                       </li>
                       <!-- END SETTINGS -->
                       <!-- BEGIN INBOX DROPDOWN -->
                       <li class="dropdown" id="header_inbox_bar">
                           <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                               <i class="icon-envelope-alt"></i>
                               <span class="badge badge-important">1</span>
                           </a>
                           <ul class="dropdown-menu extended inbox">
                               <li>
                                   <p>1 Mensaje</p>
                               </li>
                               <li>
                                   <a href="#">
                                       <span class="photo"><img src="../libreria_publica/archivos_stilo_1/avatar-mini.png" alt="avatar"></span>
									<span class="subject">
									<span class="from">informatica</span>
									<span class="time">ahora</span>
									</span>
									<span class="message">
									    Hola Bienvenido a la intranet
									</span>
                                   </a>
                               </li>
                           </ul>
                       </li>
                       <!-- END INBOX DROPDOWN -->
                       <!-- BEGIN NOTIFICATION DROPDOWN -->
                       <li class="dropdown" id="header_notification_bar">
                           <a href="http://thevectorlab.net/metrolab/index.html#" class="dropdown-toggle" data-toggle="dropdown">

                               <i class="icon-bell-alt"></i>
                               <span class="badge badge-warning"><?php echo $numero_avisos_label;?></span>
                           </a>
                           <?php echo $msj_AVISOS;?>
                       </li>
                       <!-- END NOTIFICATION DROPDOWN -->

                   </ul>
               </div>
               <!-- END  NOTIFICATION -->
               <div class="top-nav ">
                   <ul class="nav pull-right top-menu">
                       <!-- BEGIN SUPPORT -->
                      
                       <!-- END SUPPORT -->
                       <!-- BEGIN USER LOGIN DROPDOWN -->
                       <li class="dropdown" style="background-color:#4a8bc2">
                           <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                               <img src="<?php echo $imagen_alumno;?>" alt="foto" width="29">
                               <span class="username"><?php echo ucwords(strtolower($nombre_alumno)); ?></span>
                               <b class="caret"></b>
                           </a>
                           <ul class="dropdown-menu extended logout">
                               <li><a href="edicion_alumno/mis_datos.php"><i class="icon-user"></i> Mis Datos</a></li>
                               <li><a href="../OKALIS/msj_error/salir.php"><i class="icon-key"></i> Salir</a></li>
                           </ul>
                       </li>
                       <!-- END USER LOGIN DROPDOWN -->
                   </ul>
                   <!-- END TOP NAVIGATION MENU -->
               </div>
           </div>
       </div>
       <!-- END TOP NAVIGATION BAR -->
   </div>
   <!-- END HEADER -->
   <!-- BEGIN CONTAINER -->
   <div id="container" class="row-fluid">
      <!-- BEGIN SIDEBAR -->
      <div class="sidebar-scroll" style="overflow: hidden; outline: none; cursor: -webkit-grab;">
        <div id="sidebar" class="nav-collapse collapse">

         <!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
         <div class="navbar-inverse">
            <form class="navbar-search visible-phone">
               <input type="text" class="search-query" placeholder="Buscar">
            </form>
         </div>
         <!-- END RESPONSIVE QUICK SEARCH FORM -->
         <!-- BEGIN SIDEBAR MENU -->
          <ul class="sidebar-menu">
              <li class="sub-menu active">
                  <a class="" href="#">
                      <i class="icon-dashboard"></i>
                      <span>Menu Principal</span>
                  </a>
              </li>
                 <li class="sub-menu">
                  <a class="" target="_blank" href="http://lms.cftmassachusetts.cl">
                      <i class="icon-book"></i>
                      <span>Aula Virtual</span>
                  </a>
              </li>
              <li class="sub-menu">
                  <a href="javascript:;" class="">
                      <i class="icon-book"></i>
                      <span>Aplicaciones</span>
                      <span class="arrow"></span>
                  </a>
                  <ul class="sub">
                      <li><a  href="../dactilografia/Lecciones_disponibles.php">Dactilografia</a></li>
                      <li><a href="../gestion_encuestas/index.php">Encuestas</a></li>
                      <li><a href="evaluacion_docente/evaluacion_docente_1.php">Evaluacion Docente</a></li>
                  </ul>
              </li>
          </ul>
         <!-- END SIDEBAR MENU -->
      </div>
      </div>
      <!-- END SIDEBAR -->
      <!-- BEGIN PAGE -->  
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
                   <!-- BEGIN THEME CUSTOMIZER-->
                   <div id="theme-change" class="hidden-phone">
                       <i class="icon-cogs"></i>
                        <span class="settings">
                            <span class="text">Color</span>
                            <span class="colors">
                                <span class="color-default" data-style="default"></span>
                                <span class="color-green" data-style="green"></span>
                                <span class="color-gray" data-style="gray"></span>
                                <span class="color-purple" data-style="purple"></span>
                                <span class="color-red" data-style="red"></span>
                            </span>
                        </span>
                   </div>
                   <!-- END THEME CUSTOMIZER-->
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                   <h3 class="page-title">
                     Menu Principal
                   </h3>
                   <ul class="breadcrumb">
                       <li>
                           <a href="http://cftmass.cl">cftmass.cl</a>
                           <span class="divider">/</span>
                       </li>
                       <li>
                           <a href="alumno_menu.php">Home</a>
                           <span class="divider">/</span>
                       </li>
                       <li class="active">Menu Principal </li>
                       <li class="pull-right search-wrap">
                           <form action="#" class="hidden-phone">
                               <div class="input-append search-input-area">
                                   <input class="" id="appendedInputButton" type="text">
                                   <button class="btn" type="button"><i class="icon-search"></i> </button>
                               </div>
                           </form>
                       </li>
                   </ul>
                   <!-- END PAGE TITLE & BREADCRUMB-->
               </div>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
            <div class="row-fluid">
                <!--BEGIN METRO STATES-->
                <div class="metro-nav">
                    <div class="metro-nav-block nav-block-orange">
                        <a data-original-title="libros" href="pedido_libro/registros_biblioteca.php">
                            <i class="icon-book"></i>
                            <div class="info">1</div>
                            <div class="status">Libros Pedidos</div>
                        </a>
                    </div>
                    <div class="metro-nav-block nav-olive">
                        <a data-original-title="Notas S" href="notas_semestrales/notas_semestrales.php">
                            <i class="icon-edit"></i>
                            <div class="info">2</div>
                            <div class="status">Notas Semestrales</div>
                        </a>
                    </div>
                    <div class="metro-nav-block nav-block-yellow">
                        <a data-original-title="Notas P" href="notas_parciales_3/ver_notas_parciales_v4.php">
                            <i class="icon-edit-sign"></i>
                            <div class="info">3</div>
                            <div class="status">Notas Parciales</div>
                        </a>
                    </div>
                    <div class="metro-nav-block nav-block-green">
                        <a data-original-title="Finanzas" href="consulta_cuotas/consulta_cuotas.php">
                            <i class="icon-money"></i>
                            <div class="info">4</div>
                            <div class="status">Estado Financiero</div>
                        </a>
                    </div>
                    <div class="metro-nav-block nav-block-red">
                        <a data-original-title="download" href="recursosXasignatura/listadorXasignatura.php">
                            <i class="icon-download-alt"></i>
                            <div class="info">5</div>
                            <div class="status">Recursos Descargables</div>
                        </a>
                    </div>
               <!-- </div>
                <div class="metro-nav">-->
                    <div class="metro-nav-block nav-light-purple">
                        <a data-original-title="Horario" href="horario/revisa_horario.php">
                            <i class="icon-calendar"></i>
                            <div class="info">6</div>
                            <div class="status">Horario</div>
                        </a>
                    </div>
                    <div class="metro-nav-block nav-block-grey" >
                        <a data-original-title="Asistencia" href="asistencia_v1/ver_asistencia_v1.php">
                            <i class="icon-check"></i>
                            <div class="info">7</div>
                            <div class="status">Asistencia</div>
                        </a>
                    </div>
                     <div class="metro-nav-block nav-block-blue" >
                        <a data-original-title="Certificados" href="certificadosAlumno/certificadoAlumno1_v1.php">
                            <i class="icon-bookmark"></i>
                            <div class="info">8</div>
                            <div class="status">Certificados</div>
                        </a>
                    </div>
                    
                    <div class="metro-nav-block nav-block-yellow">
                        <a data-original-title="Toma Ramos" href="tomaRamosAlumno/tomaRamosAlumno.php">
                            <i class="icon-edit-sign"></i>
                            <div class="info">9</div>
                            <div class="status">Toma de Ramos</div>
                        </a>
                    </div>
                    
                     <div class="metro-nav-block nav-block-grey">
                        <a data-original-title="Evaluacion Docente" href="evaluacion_docente/evaluacion_docente_1.php">
                            <i class=" icon-exclamation-sign"></i>
                            <div class="info">10</div>
                            <div class="status">Evaluacion Docente</div>
                        </a>
                    </div>
                    
                </div>
                <div class="space10"></div>
                <!--END METRO STATES-->
            </div>

   

            <!-- END PAGE CONTENT-->         
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
      <!-- END PAGE -->  
   </div>
   <!-- END CONTAINER -->

   <!-- BEGIN FOOTER -->
   <div id="footer">
       <?php echo date("Y");?> - CFT. Massachusetts
   </div>
   <!-- END FOOTER -->

   <!-- BEGIN JAVASCRIPTS -->
   <!-- Load javascripts at bottom, this will reduce page load time -->
   <script src="../libreria_publica/archivos_stilo_1/jquery-1.8.3.min.js"></script>
   <script src="../libreria_publica/archivos_stilo_1/jquery.nicescroll.js" type="text/javascript"></script>
   <script type="text/javascript" src="../libreria_publica/archivos_stilo_1/jquery-ui-1.9.2.custom.min.js"></script>
   <script type="text/javascript" src="../libreria_publica/archivos_stilo_1/jquery.slimscroll.min.js"></script>
   <script src="../libreria_publica/archivos_stilo_1/bootstrap.min.js"></script>

   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="../libreria_publica/archivos_stilo_1/excanvas.js"></script>
   <script src="../libreria_publica/archivos_stilo_1/respond.js"></script>
   <![endif]-->

   <script src="../libreria_publica/archivos_stilo_1/jquery.scrollTo.min.js"></script>

   <!--common script for all pages-->
   <script src="../libreria_publica/archivos_stilo_1/common-scripts.js"></script><div id="ascrail2000" class="nicescroll-rails" style="width: 5px; z-index: 100; cursor: -webkit-grab; position: fixed; height: 632px; display: none; background: rgb(64, 64, 64);"><div style="position: relative; top: 0px; float: right; width: 5px; height: 0px; border-radius: 0px; background-color: rgb(74, 139, 194); background-clip: padding-box;"></div></div><div id="ascrail2000-hr" class="nicescroll-rails" style="height: 5px; z-index: 100; position: fixed; display: none; background: rgb(64, 64, 64);"><div style="position: relative; top: 0px; height: 5px; width: 0px; border-radius: 0px; background-color: rgb(74, 139, 194); background-clip: padding-box;"></div></div>
   <!--script for this page only-->


   <!-- END JAVASCRIPTS -->   
<!-- END BODY -->
</body>
</html>