<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="ALUMNO";
	OKALIS($lista_invitados);
	////////////////////////
	//
	require("../../../funciones/conexion_v2.php");
	require("../../OKALIS/msj_error/anti_2_login.php");
	@mysql_close($conexion);
	define("DEBUG", false);
//-----------------------------------------//	
  
   $mes_actual=date("m");
   $year_actual=date("Y");
   if($mes_actual>=8){ $semestre_actual=2;}
   else{ $semestre_actual=1;}
   
   $id_alumno=$_SESSION["USUARIO"]["id"];
   $id_carrera=$_SESSION["USUARIO"]["id_carrera"];
   
   $cons="SELECT * FROM alumno WHERE id='$id_alumno' LIMIT 1";
 //--------------------------------------------------//
 	 include("../../../funciones/VX.php");
	 //cambio estado_conexion USER-----------
	 CAMBIA_ESTADO_CONEXION_ALUMNO($id_alumno, "ON");
	  $evento="Ingreso a Evaluacion Docente";
   	 REGISTRA_EVENTO($evento);
	//-----------------------------------------------//
	
	//datos del alumnos
	$ruta_imagen="../../CONTENEDOR_GLOBAL/img_alumnos/";
   $sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
   $row = $sqli->fetch_assoc();
   
   		$imagen_alumno=$row["imagen"];
		if(empty($imagen_alumno)){ $imagen_alumno="../../BAses/Images/login_logo.png";}
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
		$direccion=$row["direccion"];
		$ciudad=$row["ciudad"];
		$fono=$row["fono"];
		$email=$row["email"];
	$sqli->free();
	
	//AVISOS
	$msj_AVISOS="";
	///////////////////
	$ARRAY_DOCENTES=array();
	$ultimo_periodos_con_toma_de_ramos="";
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
	//-----------------------------------------------//
	//notas parciales
	//-------------------------------------------------//
	require("../../../funciones/funciones_sistema.php");
	$alumno_actualmente_matriculado=VERIFICAR_MATRICULA($id_alumno, $id_carrera,true);
   //$alumno_actualmente_matriculado=true;
   $array_semestre=array(1,2);
   $mes_actual=date("m");
   
   if($mes_actual>=8)///utilizo agosto para inicio 2 semestre
   { $semestre_actual=2;}
   else{ $semestre_actual=1;}

	$id_encuesta=0;
	  if(DEBUG){ echo"Buscando si existen encuestas marcadas para evaluacion docente<br>";}
	  $cons_E="SELECT id_encuesta FROM encuestas_main WHERE utilizar_para_evaluacion_docente='1' ORDER by id_encuesta DESC LIMIT 1";
	  $sqli=$conexion_mysqli->query($cons_E)or die($conexion_mysqli->error);
	  $num_encuestas=$sqli->num_rows;
	  if(DEBUG){ echo"->$cons_E<br>numero encuestas encontradas: $num_encuestas<br>";}
	  if($num_encuestas>0)
	  {
		$E=$sqli->fetch_row();
		$id_encuesta=$E[0];
	  }
	  $sqli->free();
	  if(DEBUG){ echo"ID ENCUESTA:$id_encuesta<br>";}
	  
	  if($id_encuesta>0){ $hay_evaluacion_docente=true;}
	  else{ $hay_evaluacion_docente=false;}
	

		//busco ultima toma de ramos
		$TR_semestre="";
		$TR_year="";
		if(DEBUG){ echo"Busco ultimo perido con toma de Ramos del Alumno<br>";}
		$cons_UTR="SELECT MAX(year) FROM toma_ramos WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera'";
		if(DEBUG){ echo"--->$cons_UTR<br>";}
		$sqli_UTR=$conexion_mysqli->query($cons_UTR)or die($conexion_mysqli->error);
		$num_registros=$sqli_UTR->num_rows;
		
		if($num_registros>0){ $hay_toma_ramos=true;}
		else{ $hay_toma_ramos=false;}
		
		if($hay_toma_ramos)
		{
			$DTR=$sqli_UTR->fetch_row();
			$TR_year=$DTR[0];
				$cons_UTR2="SELECT MAX(semestre) FROM toma_ramos WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND year='$TR_year'";
				if(DEBUG){ echo"--->$cons_UTR2<br>";}
				$sqli_UTR2=$conexion_mysqli->query($cons_UTR2)or die($conexion_mysqli->error);
				$DTR2=$sqli_UTR2->fetch_row();
					$TR_semestre=$DTR2[0];
				$sqli_UTR2->free();	
		}
		
		$sqli_UTR->free();
		
		//------------------------------------------------------------------------------//
		///comparo periodo ultima toma de ramos con el periodod actual segu  fecha actual
		if(DEBUG){ echo"PERIODOS<br>ACTUAL[$semestre_actual - $year_actual] CONSULTADO[$TR_semestre - $TR_year] <br>";}
		if(($TR_semestre==$semestre_actual)and($TR_year==$year_actual))
		{ if(DEBUG){ echo"Ultimo periodo concuerda con perido actual<br>";} $utilizar_periodo=true;}
		else{  if(DEBUG){ echo"Ultimo periodo NO concuerda con perido actual<br>";} $utilizar_periodo=true;}
		//--------------------------------------------------------------------//
		
		if(($hay_toma_ramos)and($utilizar_periodo)and ($hay_evaluacion_docente))
		{
		
			if(DEBUG){ echo"Ultimo periodo con toma de ramos [$TR_semestre - $TR_year]<br>";}
			///----------------------------------------------------------------------------------------------------------//
			
			if(DEBUG){ echo"Busco ramos que tomo el alumno en periodo anteriormente encontrado<br>";}
			$cons_TR="SELECT jornada, id_carrera, cod_asignatura, `semestre`, `year` FROM `toma_ramos` WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND semestre='$TR_semestre' AND year='$TR_year' ORDER by cod_asignatura";
			
			$sql_TR=$conexion_mysqli->query($cons_TR)or die($conexion_mysqli->error);
			$num_ramos_tomados=$sql_TR->num_rows;
			if(DEBUG){ echo"--->$cons_TR<br>num ramos tomados: $num_ramos_tomados<br>";}
			
			$periodos_con_notas_parciales="";
			if(DEBUG){echo"Consultando Toma de ramos alumno<br>";}
			$ARRAY_DOCENTES=array();
			if($num_ramos_tomados>0)
			{
				$aux=0;
				while($PTR=$sql_TR->fetch_assoc())
				{
					$aux++;
					if($aux%2==0){$clase_boton='btn btn-large btn-primary';}
					else{$clase_boton='btn btn-large btn-success';}
					$periodo_semestre=$PTR["semestre"];
					$periodo_year=$PTR["year"];
					$TR_jornada=$PTR["jornada"];
					$TR_id_carrera=$PTR["id_carrera"];
					$TR_cod_asignatura=$PTR["cod_asignatura"];
					$ultimo_periodos_con_toma_de_ramos=$periodo_semestre.' Semestre -'.$periodo_year;
					if(DEBUG){ echo"[$aux] Semestre: $periodo_semestre Year: $periodo_year Jornada:$TR_jornada id_carrera: $TR_id_carrera cod_asignatura: $TR_cod_asignatura<br>";}
					
					///busco docente del ramo
					if($TR_cod_asignatura>0)
					{$utilizar_asignatura=true; if(DEBUG){ echo"---->CONSULTAR esta asignatura<br>";}}
					else{ $utilizar_asignatura=false; if(DEBUG){echo"----->NO consultar esta asignatura<br>";}}
					
					if($utilizar_asignatura)
					{
						$cons_TRD="SELECT distinct(id_funcionario) FROM toma_ramo_docente WHERE jornada='$TR_jornada' AND id_carrera='$TR_id_carrera' AND cod_asignatura='$TR_cod_asignatura' AND sede='$sede' AND semestre='$periodo_semestre' AND year='$periodo_year'";
						$sqli_TRD=$conexion_mysqli->query($cons_TRD)or die($conexion_mysqli->error);
						$num_docentes=$sqli_TRD->num_rows;
						if(DEBUG){ echo"--->$cons_TRD<br>Numero docentes relacionados a asignatura: $num_docentes<br>";}
						if($num_docentes>0)
						{
							while($DTR=$sqli_TRD->fetch_row())
							{
								//guardo docentes en array
								$aux_id_funcionario=$DTR[0];
								if(DEBUG){ echo"---->$aux_id_funcionario<br>";}
								$cons_RE="SELECT COUNT(id_resultados) FROM encuestas_resultados WHERE id_encuesta='$id_encuesta' AND id_usuario='$id_alumno' AND id_usuario_evaluar='$aux_id_funcionario' AND semestre_evaluar='$TR_semestre' AND year_evaluar='$TR_year' AND id_carrera_evaluar='$TR_id_carrera'";
							  $SQLI_r=$conexion_mysqli->query($cons_RE)or die($conexion_mysqli->error);
							  $RE=$SQLI_r->fetch_row();
							  $numero_resultado=$RE[0];
							  if(DEBUG){ echo"--->$cons_RE<br>numero resultados: $numero_resultado<br>";}
							  if(empty($numero_resultado)){ $numero_resultado=0;}
							  if($numero_resultado>0){ $encuesta_contestada=true;   if(DEBUG){ echo"Encuesta ya ha sido contestada<br>";} }
							  else{ $encuesta_contestada=false; if(DEBUG){ echo"Encuesta No ha sido contestada<br>";} $continuar_2=true;}
							  
							  
							  if(isset($ARRAY_DOCENTES[$aux_id_funcionario]))
							  { if(DEBUG){ echo"--->docente ya guardado<br>";}}
							  else
							  {$ARRAY_DOCENTES[$aux_id_funcionario]=$encuesta_contestada;  if(DEBUG){ echo"--->docente guardado<br>";}}
							 
							}
						}
						else
						{
							if(DEBUG){ echo"No hay docente con esta asignatura vinculada<br>";}
						}
						$sqli_TRD->free();
					}
					
				}
				//Busco Jefe de carrera y su encuesta
				
				$id_encuesta_jefe_carrera=15;
				
				$cons_JC="SELECT id_funcionario FROM toma_ramo_docente WHERE sede='$sede' AND semestre='$TR_semestre' AND year='$TR_year' AND id_carrera='$TR_id_carrera' AND cod_asignatura='0'";
				$sqli_JC=$conexion_mysqli->query($cons_JC)or die($conexion_mysqli->error);
				if(DEBUG){ echo"----> $cons_JC<br>";}
				$JC=$sqli_JC->fetch_assoc();
				$JC_id=$JC["id_funcionario"];
				$sqli_JC->free();
				if($JC_id>0){ $hay_jefe_carrera=true;}
				else{ $hay_jefe_carrera=false;}
				if(DEBUG){ echo"id_jefe_carrera: $JC_id<br>";}
				
				///verifico si encuesta JC se ha contestado
				$cons_RE="SELECT COUNT(id_resultados) FROM encuestas_resultados WHERE id_encuesta='$id_encuesta_jefe_carrera' AND id_usuario='$id_alumno' AND id_usuario_evaluar='$JC_id' AND semestre_evaluar='$TR_semestre' AND year_evaluar='$TR_year' AND id_carrera_evaluar='$TR_id_carrera' AND sede_evaluar='$sede'";
			  $SQLI_r=$conexion_mysqli->query($cons_RE)or die($conexion_mysqli->error);
			  $RE=$SQLI_r->fetch_row();
			  $numero_resultado=$RE[0];
			  if(DEBUG){ echo"--->$cons_RE<br>numero resultados: $numero_resultado<br>";}
			  
			  if(empty($numero_resultado)){ $numero_resultado=0;}
			  if($numero_resultado>0){ $encuesta_contestada_jefe_carrera=true;   if(DEBUG){ echo"Encuesta ya ha sido contestada<br>";} }
			  else{ $encuesta_contestada_jefe_carrera=false; if(DEBUG){ echo"Encuesta No ha sido contestada<br>";} $continuar_2=true;}
				
			}
			else
			{ $ultimo_periodos_con_toma_de_ramos=""; if(DEBUG){echo"NO hay Toma de Ramos<br>";}}
			
			
			$sql_TR->free();
		}
	
?>
<!DOCTYPE html>
<html lang="en"><!--<![endif]--><!-- BEGIN HEAD -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
   <meta charset="utf-8">
   <title>Evaluacion Docente</title>
   <meta content="width=device-width, initial-scale=1.0" name="viewport">
   <meta content="" name="description">
   
 
   <link href="../../libreria_publica/archivos_stilo_1/bootstrap.min.css" rel="stylesheet">
   <link href="../../libreria_publica/archivos_stilo_1/bootstrap-responsive.min.css" rel="stylesheet">
   <link href="../../libreria_publica/archivos_stilo_1/font-awesome.css" rel="stylesheet">
   <link href="../../libreria_publica/archivos_stilo_1/style.css" rel="stylesheet">
   <link href="../../libreria_publica/archivos_stilo_1/style-responsive.css" rel="stylesheet">
   <link href="../../libreria_publica/archivos_stilo_1/style-default.css" rel="stylesheet" id="style_color">
   
<style type="text/css">.jqstooltip { position: absolute;left: 0px;top: 0px;visibility: hidden;background: rgb(0, 0, 0) transparent;background-color: rgba(0,0,0,0.6);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";color: white;font: 10px arial, san serif;text-align: left;white-space: nowrap;padding: 5px;border: 1px solid white;}.jqsfield { color: white;font: 10px arial, san serif;text-align: left;}</style>

</head>
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
                   <img src="../../libreria_publica/archivos_stilo_1/logo.png" alt="Metro Lab">
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
                                       <span class="photo"><img src="../../libreria_publica/archivos_stilo_1/avatar-mini.png" alt="avatar"></span>
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
                           <a href="#" class="dropdown-toggle" data-toggle="dropdown">

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
                       <li class="dropdown">
                           <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                               <img src="<?php echo $imagen_alumno;?>" alt="foto" width="29">
                               <span class="username"><?php echo ucwords(strtolower($nombre_alumno)); ?></span>
                               <b class="caret"></b>
                           </a>
                           <ul class="dropdown-menu extended logout">
                               <li><a href="../edicion_alumno/mis_datos.php"><i class="icon-user"></i> Mis Datos</a></li>
                               <li><a href="../../OKALIS/msj_error/salir.php"><i class="icon-key"></i> Salir</a></li>
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
                  <a href="javascript:;" class="">
                      <i class="icon-book"></i>
                      <span>Aplicaciones</span>
                      <span class="arrow"></span>
                  </a>
                  <ul class="sub">
                     <li><a  href="../../dactilografia/Lecciones_disponibles.php">Dactilografia</a></li>
                      <li><a href="../../gestion_encuestas/index.php">Encuestas</a></li>
                      <li><a href="evaluacion_docente_1.php">Evaluacion Docente</a></li>
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
                   <h3 class="page-title">Evaluación Docente</h3>
                   <ul class="breadcrumb">
                       <li>
                           <a href="http://cftmass.cl">cftmass.cl</a>
                           <span class="divider">/</span>
                       </li>
                       <li>
                           <a href="../alumno_menu.php">Home</a>
                           <span class="divider">/</span>
                       </li>
                       <li class="active">Evaluación Docente</li>
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
                        <a data-original-title="libros" href="../pedido_libro/registros_biblioteca.php">
                            <i class="icon-book"></i>
                            <div class="info">1</div>
                            <div class="status">Libros Pedidos</div>
                        </a>
                    </div>
                    <div class="metro-nav-block nav-olive">
                        <a data-original-title="Notas S" href="../notas_semestrales/notas_semestrales.php">
                            <i class="icon-edit"></i>
                            <div class="info">2</div>
                            <div class="status">Notas Semestrales</div>
                        </a>
                    </div>
                    <div class="metro-nav-block nav-block-yellow">
                        <a data-original-title="Notas P" href="../notas_parciales_3/ver_notas_parciales_v4.php">
                            <i class="icon-edit-sign"></i>
                            <div class="info">3</div>
                            <div class="status">Notas Parciales</div>
                        </a>
                    </div>
                    <div class="metro-nav-block nav-block-green double">
                        <a data-original-title="Finanzas" href="../consulta_cuotas/consulta_cuotas.php">
                            <i class="icon-money"></i>
                            <div class="info">4</div>
                            <div class="status">Estado Financiero</div>
                        </a>
                    </div>
                    <div class="metro-nav-block nav-block-red">
                        <a data-original-title="download" href="../recursosXasignatura/listadorXasignatura.php">
                            <i class="icon-download-alt"></i>
                            <div class="info">5</div>
                            <div class="status">Recursos Descargables</div>
                        </a>
                    </div>
                </div>
                <div class="metro-nav">
                    <div class="metro-nav-block nav-light-purple">
                        <a data-original-title="Horario" href="../horario/revisa_horario.php">
                            <i class="icon-calendar"></i>
                            <div class="info">6</div>
                            <div class="status">Horario</div>
                        </a>
                    </div>
                </div>
                <div class="space10"></div>
                <!--END METRO STATES-->
            </div>

   			 <div class="row-fluid">
                   
               <div class="span12">
                  <!-- BEGIN BUTTON PORTLET-->
                  <div class="widget red">
                        <div class="widget-title">
                           <h4><i class="icon-reorder"></i> Evaluación Docentes Periodo [<?php echo $ultimo_periodos_con_toma_de_ramos;?>]</h4>
                           <span class="tools">
                               <a class="icon-chevron-down" href="javascript:;"></a>
                               <a class="icon-remove" href="javascript:;"></a>
                           </span>
                        </div>
                        <div class="widget-body">
                        <table class="table table-striped table-bordered table-advance table-hover">
                            <thead>
                            <tr>
                                <th><i class="icon-bullhorn"></i> N</th>
                                <th class="hidden-phone"><i class="icon-question-sign"></i> Docente</th>
                                <th><i class="icon-bookmark"></i> Opc</th>
                            </tr>
                            </thead>
                            <tbody>
                            	<?php
                                if(count($ARRAY_DOCENTES)>0)
								{
									$aux2=0;
									foreach($ARRAY_DOCENTES as $aux_id_docente => $aux_condicion_encuesta)
									{
										
											$aux2++;
											if($aux_condicion_encuesta){$clase_x='label-success'; $condicion="Ok"; $url_EN="#"; $class='';}
											else{ $clase_x='label-important'; $condicion="Evaluar"; $url_EN='evaluacion_docente_2.php?id_docente='.base64_encode($aux_id_docente).'&semestre='.base64_encode($TR_semestre).'&year='.base64_encode($TR_year).'&lightbox[iframe]=true&lightbox[width]=800&lightbox[height]=600'; $class='lightbox';}
											
											 
											
											 
											//list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($id_carrera, $aux_cod_asignatura);
											echo'<tr>
													<td>'.$aux2.'</td>
													<td>'.NOMBRE_PERSONAL($aux_id_docente).'</td>
													<td><a href="'.$url_EN.'" class="'.$class.'"><span class="label '.$clase_x.' label-mini">'.$condicion.'</span></a></td>
												 </tr>';
										
									}
								}
								else
								{
									echo'<tr><td colspan="3"><i class="icon-bullhorn"></i> Sin Docentes</td></tr>';
								}
								if($hay_jefe_carrera)
								{
									
									if($encuesta_contestada_jefe_carrera){$clase_x='label-success'; $condicion="Ok"; $url_EN="#"; $class='';}
									else{ $clase_x='label-important'; $condicion="Evaluar"; $url_EN='evaluacion_JC_2.php?id_docente='.base64_encode($JC_id).'&semestre='.base64_encode($TR_semestre).'&year='.base64_encode($TR_year).'&sede='.base64_encode($sede).'&id_encuesta='.base64_encode($id_encuesta_jefe_carrera).'&lightbox[iframe]=true&lightbox[width]=800&lightbox[height]=600'; $class='lightbox';}
									
									echo'<tr>
											<td>*</td>
											<td>'.NOMBRE_PERSONAL($JC_id).' (Jefe de Carrera)</td>
											<td><a href="'.$url_EN.'" class="'.$class.'"><span class="label '.$clase_x.' label-mini">'.$condicion.'</span></a></td>
										 </tr>';
								}
								?>
                            </tbody>
                         </table>
                        </div>
                  </div>
                  <!-- END BUTTON PORTLET-->
               </div>    
           </div>   
               <!-- BEGIN BASIC PORTLET-->
                <div class="row-fluid">        
                <div class="span12" id="div_resultado">
                 	      
                        <!-- END BASIC PORTLET-->
                </div>
                        <!-- END BASIC PORTLET-->
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
   <script src="../../libreria_publica/archivos_stilo_1/jquery-1.8.3.min.js"></script>
   <script src="../../libreria_publica/archivos_stilo_1/jquery.nicescroll.js" type="text/javascript"></script>
   <script type="text/javascript" src="../../libreria_publica/archivos_stilo_1/jquery-ui-1.9.2.custom.min.js"></script>
   <script type="text/javascript" src="../../libreria_publica/archivos_stilo_1/jquery.slimscroll.min.js"></script>
   <script src="../../libreria_publica/archivos_stilo_1/bootstrap.min.js"></script>

   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="../../../libreria_publica/archivos_stilo_1/excanvas.js"></script>
   <script src="../../../libreria_publica/archivos_stilo_1/respond.js"></script>
   <![endif]-->

   <script src="../../libreria_publica/archivos_stilo_1/jquery.scrollTo.min.js"></script>

   <!--common script for all pages-->
   <script src="../../libreria_publica/archivos_stilo_1/common-scripts.js"></script><div id="ascrail2000" class="nicescroll-rails" style="width: 5px; z-index: 100; cursor: -webkit-grab; position: fixed; height: 632px; display: none; background: rgb(64, 64, 64);"><div style="position: relative; top: 0px; float: right; width: 5px; height: 0px; border-radius: 0px; background-color: rgb(74, 139, 194); background-clip: padding-box;"></div></div><div id="ascrail2000-hr" class="nicescroll-rails" style="height: 5px; z-index: 100; position: fixed; display: none; background: rgb(64, 64, 64);"><div style="position: relative; top: 0px; height: 5px; width: 0px; border-radius: 0px; background-color: rgb(74, 139, 194); background-clip: padding-box;"></div></div>
   <!--script for this page only-->


   <!-- END JAVASCRIPTS -->   
      <!--INICIO LIGHTBOX EVOLUTION-->
   <!-- <script type="text/javascript" src="../../libreria_publica/lightbox_1.6.9_evolution/js/jquery.min.js"></script>-->
  <link rel="stylesheet" type="text/css" href="../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.css" />
  <!--[if IE 6]>
  <link rel="stylesheet" type="text/css" href="../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.ie6.css" />
  <![endif]-->
  <script type="text/javascript" src="../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/jquery.lightbox.min.js"></script>
 <script type="text/javascript">
    jQuery(document).ready(function($){
      $('.lightbox').lightbox(); 
    });
  </script>
 <!--FIN LIGHTBOX EVOLUTION--> 
<!-- END BODY -->
</body>
</html>