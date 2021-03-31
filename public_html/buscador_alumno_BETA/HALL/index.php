<?php
//--------------CLASS_okalis------------------//

	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Gestion_alumnos_BETA_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

	require("../../../funciones/class_ALUMNO.php");
	$ALUMNO=new ALUMNO();

$tipo_usuario="";
$informacion_financiera_alumno="";	
$privilegio=$_SESSION["USUARIO"]["privilegio"];

$url_menu="../volver_menu.php";
if($privilegio=="jefe_carrera"){$url_menu="http://intranet.cftmassachusetts.cl/Docentes/okdocente.php";}

if(isset($_SESSION["SELECTOR_ALUMNO"]))
{
	if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
	{
		switch($privilegio)
		{
			case"finan":
				$ver_opciones_imprimibles=false;
				$ver_opciones_finanzas=true;
				$ver_opciones_registros=false;
				$ver_opciones_solicitudes=true;
				break;
			case"matricula":
				$ver_opciones_imprimibles=true;
				$ver_opciones_finanzas=true;
				$ver_opciones_registros=false;
				$ver_opciones_solicitudes=true;
				break;
			case"admi_total":
				$ver_opciones_imprimibles=true;
				$ver_opciones_finanzas=true;
				$ver_opciones_registros=true;
				$ver_opciones_solicitudes=true;
				$condicion_solicitud="";
				break;
			case"admi":
				$ver_opciones_imprimibles=true;
				$ver_opciones_finanzas=false;
				$ver_opciones_registros=true;
				$ver_opciones_solicitudes=true;
				break;	
			case"jefe_carrera":
				$ver_opciones_imprimibles=true;
				$ver_opciones_finanzas=false;
				$ver_opciones_registros=true;
				$ver_opciones_solicitudes=false;
				
				break;		
			default:
				$ver_opciones_imprimibles=false;
				$ver_opciones_finanzas=false;
				$ver_opciones_registros=false;
				$ver_opciones_solicitudes=false;
		}
	}
	else
	{
				$ver_opciones_imprimibles=false;
				$ver_opciones_finanzas=false;
				$ver_opciones_registros=false;
				$ver_opciones_solicitudes=false;
	}
}
else
{
	$ver_opciones_imprimibles=false;
	$ver_opciones_finanzas=false;
	$ver_opciones_registros=false;
	$ver_opciones_solicitudes=false;
}
///verifica alumno este activo
if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
{ $alumno_activo=$_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]; if(DEBUG){ echo"Alumno activo<br>";}}
else{ $alumno_activo=false; if(DEBUG){ echo"Alumno NO activo<br>";}}
/////
///iconos segun sexo, sino tiene imagen

$alto_1=410;
$ancho_1=450;
if($alumno_activo)
{
	if(!empty($_SESSION["SELECTOR_ALUMNO"]["imagen"]))
	{
		if(DEBUG){ echo"Alumno tiene Imagen cargada...<br>";}
		$path="../../CONTENEDOR_GLOBAL/img_alumnos/";
		$ruta_imagen=$path.$_SESSION["SELECTOR_ALUMNO"]["imagen"];
		$icono_alumno='<a href="../../Alumnos/carga_imagen/index.php?id_alumno='.$_SESSION["SELECTOR_ALUMNO"]["id"].'&TB_iframe=true&amp;height='.$alto_1.'&width='.$ancho_1.'" rel="sexylightbox" title="Click para cambiar imagen"><img src="'.$ruta_imagen.'" alt="XD" width="100" height="100" /></a>';
	}
	else
	{
		if(DEBUG){ echo"Alumno sin Imagen cargada...<br>";}
		$icono["M"]='<a href="../../Alumnos/carga_imagen/index.php?id_alumno='.$_SESSION["SELECTOR_ALUMNO"]["id"].'&TB_iframe=true&amp;height='.$alto_1.'&width='.$ancho_1.'" rel="sexylightbox" title="Click para cambiar imagen"><img src="../../BAses/Images/male_user_icon.png" alt=":D" width="100" height="100" /></a>';
		$icono["F"]='<a href="../../Alumnos/carga_imagen/index.php?id_alumno='.$_SESSION["SELECTOR_ALUMNO"]["id"].'&TB_iframe=true&amp;height='.$alto_1.'&width='.$ancho_1.'" rel="sexylightbox" title="Click para cambiar imagen"><img src="../../BAses/Images/female_user_icon.png" alt=":D" width="100" height="100" /></a>';
		
		if(isset($_SESSION["SELECTOR_ALUMNO"]["sexo"]))
		{ $sexo_alumno=trim($_SESSION["SELECTOR_ALUMNO"]["sexo"]);}
		else{ $sexo_alumno="M";}
		
		if(empty($sexo_alumno))
		{ $sexo_alumno="M";}
		$icono_alumno=$icono[$sexo_alumno];
	}
}
else
{
	$icono_alumno='<img src="../../BAses/Images/male_user_icon.png" alt=":D" width="100" height="100" title="No hay alumno Seleccionado"/>';
}

$ARRAY_ICONO_JORNADA=array("D"=>'<img src="../../BAses/Images/diurno.png" width="25" height="25" alt="diurno" />',
						   "V"=>'<img src="../../BAses/Images/vespertino.png" width="25" height="25" alt="vespertino" />');
if(DEBUG){ echo"IMG alumno: $icono_alumno<br>";}

//////////////////////////////////////
require("../../../funciones/conexion_v2.php");
include("../../../funciones/funcion.php");
include("../../../funciones/funciones_sistema.php");
$id_usuario_activo=$_SESSION["USUARIO"]["id"];

if($alumno_activo)
{
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
}
/////////////////////////////////////////////////////////////
	/////-----------------------------
	//session para el CHAT
	/////----------------------------------
	$_SESSION["CHAT"]['nick'] = $_SESSION["USUARIO"]["nick"]; // Must be already set
	//busco usuarios activos
	include("../../../funciones/VX.php");
	//cambio estado_conexin USER-----------
	 CAMBIA_ESTADO_CONEXION($id_usuario_activo, "on");
	$array_usuarios_activos=USUARIOS_ACTIVOS($id_usuario_activo);
//------------------------------------------
//Solicitudes//
$ARRAY_SOLICITUDES=array();
$num_solicitudes_autorizadas=0;
$num_solicitudes_no_autorizadas=0;
$num_solicitudes_pendientes=0;
$fecha_actual=date("Y-m-d");
$fecha_limite_solicitud=date("Y-m-d", strtotime("$fecha_actual -10 days"));///fecha limite =fecha corte +10 dias
if($alumno_activo)
{
	$cons="SELECT * FROM solicitudes WHERE tipo_receptor='alumno' AND id_receptor='$id_alumno' AND id_carrera_receptor='$id_carrera' ORDER by id Desc";
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
				{ $num_solicitudes_no_autorizadas++; }
				elseif($S_autorizado=="si")
				{ $num_solicitudes_autorizadas++;}
				
				if($S_estado=="pendiente")
				{ $num_solicitudes_pendientes++;}
				
				
				$ARRAY_SOLICITUDES[$S_id]["categoria"]=$S_categoria;
				$ARRAY_SOLICITUDES[$S_id]["autorizado"]=$S_autorizado;
				$ARRAY_SOLICITUDES[$S_id]["estado"]=$S_estado;
				$ARRAY_SOLICITUDES[$S_id]["fecha_hora_solicitud"]=$S_fecha_hora_solicitud;
				$ARRAY_SOLICITUDES[$S_id]["fecha_hora_creacion"]=$S_fecha_hora_creacion;
				$ARRAY_SOLICITUDES[$S_id]["fecha_hora_autorizacion"]=$S_fecha_hora_autorizacion;
			}
		}
	//----------------------------------------------------------------------------------///
	$deuda_actual_alumno=DEUDA_ACTUAL($id_alumno, $fecha_actual);
	$dias_morosidad_alumno=DIAS_MOROSIDAD($id_alumno);
	$tipo_morosidad_alumno=TIPO_MOROSIDAD($dias_morosidad_alumno);
	$tipo_morosidad_alumno_label=TIPO_MOROSIDAD_LABEL($tipo_morosidad_alumno);
	
	if($tipo_morosidad_alumno>0){$informacion_financiera_alumno.="Moroso";}
	
	$informacion_financiera_alumno.=" [".$tipo_morosidad_alumno_label."]";
	
	//-----------------------------------------------------//
	$situacion_alumno=$_SESSION["SELECTOR_ALUMNO"]["situacion"];
	if($situacion_alumno=="V"){ $tipo_usuario="alumno";}
	else{ $tipo_usuario="exalumno";}
	//------------------------------------------------------//
	
		
}//fin alumno activo

//////////////////////////////////
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>HALL</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<!--INICIO -->
  <script type="text/javascript" src="../../libreria_publica/jquery_libreria/mootools-yui-compressed.js"></script>
  <script type="text/javascript" src="../../libreria_publica/sexy_lightbox/Mootools/sexylightbox.v2.3.mootools.min.js"></script>
  <link rel="stylesheet" href="../../libreria_publica/sexy_lightbox/Mootools/sexylightbox.css" type="text/css" media="all" />
  <script type="text/javascript">
    window.addEvent('domready', function(){
      SexyLightbox = new SexyLightBox({color:'black', dir: '../../libreria_publica/sexy_lightbox/Mootools/sexyimages'});
    });
  </script>
<!--FIN -->

 <script type="text/javascript" src="../../libreria_publica/jquery_libreria/jquery_1_3_2.min.js"></script>
 <!--INICIO MENU HORIZONTAL-->
  <link rel="stylesheet" type="text/css" href="../../libreria_publica/menu_horizontal/ddsmoothmenu.css"/>
 
<script type="text/javascript" src="../../libreria_publica/menu_horizontal/ddsmoothmenu.js">

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
<link rel="stylesheet" type="text/css" href="../../libreria_publica/hint.css-master/hint.css"/>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:40%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 125px;
}
#apDiv2 {
	position:absolute;
	width:45%;
	height:500px;
	z-index:2;
	left: 50%;
	top: 125px;
	overflow: auto;
}
-->
</style>
<style type="text/css">
<!--
.Estilo2 {
	font-size: 12px;
	font-weight: bold;
}
.Estilo4 {font-size: 12px; font-style: italic; }
.Estilo5 {font-size: 12px}
-->
</style>
<script language="javascript">
function REDIRIGIR(url, msj)
{
	c=confirm(msj);
	if(c)
	{window.location=url;}
}
</script>
</head>

<body>
<h1 id="banner">Gesti&oacute;n de Alumnos -HALL V 2.1</h1>
<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a href="#">Alumno</a>
    <ul>
    	<li><a href="#" onclick="REDIRIGIR('../../Alumnos/nvo_alumno/paso_A.php', 'Seguro Desea Registar un Nuevo Alumno')">Agregar Nuevo</a></li>
        <li><a href="../index.php">Seleccionar</a></li>
        <?php if($alumno_activo){?>
        <li><a href="../../Alumnos/edit_alumno/buscaalumno2_tab.php">Modificar Datos</a></li>
       
        <li><a href="#">Acciones</a>
     	<ul>
    		<li><a href="../../Alumnos/sube_nivel/subir_nivel_1.php">Subir de Nivel</a></li>
            <li><a href="../../Alumnos/proceso_titulacion/proceso_titulacion.php?TB_iframe=true&height=600&width=800" rel="sexylightbox">Proceso titulacion</a></li>
            <li><a href="../../Alumnos/proceso_retiro/proceso_retiro_1.php?TB_iframe=true&height=550&width=750" rel="sexylightbox">Proceso Retiro</a></li>
            <li><a href="../../Alumnos/proceso_postergacion/proceso_postergacion_0.php?TB_iframe=true&height=400&width=750" rel="sexylightbox">Proceso Postergacion</a></li>
      		<li><a href="../../Alumnos/eliminaAlumno/eliminarAlumno1.php?TB_iframe=true&height=350&width=600" rel="sexylightbox">Eliminar</a></li>	      
          <li><a href="../../Alumnos/cambio_jornada/cambio_jornada_1.php?TB_iframe=true&height=450&width=750" rel="sexylightbox">Cambio Jornada</a></li>
            <li><a href="../../Alumnos/cambio_sede/cambio_sede_1.php?TB_iframe=true&height=450&width=750" rel="sexylightbox">Cambio Sede</a></li>
            <li><a href="../../Alumnos/edit_alumno/restablecer_clave/restablece_clave_1.php?TB_iframe=true&height=230&width=350" rel="sexylightbox">Restablecer Clave</a></li>
            <li><a href="../../Alumnos/proceso_pendiente/proceso_pendiente_1.php?TB_iframe=true&height=450&width=750" rel="sexylightbox">Proceso Alumno Pendiente</a></li>
            <li><a href="../../Alumnos/envio_mail_bienvenida/envio_bienvenida_1.php">Envio Bienvenida</a></li>
            <li><a href="../../gestion_encuestas/index.php?tipo_usuario=<?php echo $tipo_usuario;?>&id_usuario=<?php echo base64_encode($id_alumno);?>&id_carrera=<?php echo base64_encode($id_carrera);?>&TB_iframe=true&height=500&width=800" rel="sexylightbox">Encuestas</a><li>
     	</ul>
         <?php }?>
        </li>
</li>
    </ul>
</li>
<?php if($ver_opciones_imprimibles){?>
<!--OPCIONES ACADEMICAS-->
<li>
	<a href="#">Imprimibles</a>
    <ul>
        <li><a href="../../Certificados/recepcion_documentos/documentos_recepcionados_pdf.php">Recepcion Documentos Matricula</a></li>
    	<li><a href="../../Certificados/ficha_de_matricula/imp_ficha_mat_pdf.php" target="_blank">Ficha Matricula</a></li>
        <?php if($privilegio!="matricula"){?>
    	<!--<li><a href="../../Certificados/alumno_regular/alumno_regular.php">Certificado Alumno Regular</a></li>-->
        <li><a href="../../Certificados/certificado_matricula/index.php">Certificado Matricula</a></li>
       <!-- <li><a href="../../Certificados/concentracion_notas/informe_concentracion_notas_pdf_1.php" target="_blank">Concentracion de Notas</a></li>-->
        <li><a href="../../Certificados/informe_notas/informe_notas_pdf.php" target="_blank">Informe de Notas Cned</a></li>
        <li><a href="../../Certificados/informe_general/informe_general.php" target="_blank">Informe General</a></li>
       
        <li><a href="../../Certificados/registro_academico_pdf/registro_academico.php" target="_blank">Registro Academico</a></li>
        <li><a href="../../Certificados/solicitudPractica/cartaSolicitudPractica.php" target="_blank">Carta Solicitud de Practica</a></li>
        <li><a href="../../utilidades_varias/plantilla_titulo_v1/plantilla_titulo_1.php">Plantillas de Impresion Titulo</a></li>
        <li><a href="../../Certificados/asignaturas_pendientes/asignaturas_pendientes.php" target="_blank">Asignaturas Pendientes</a></li>
        <?php }  ?>
        <li><a href="#">Otros</a>
			<ul>
           	    <?php if(isset($_SESSION["SELECTOR_ALUMNO"]["id_carrera"]))
					{ if($_SESSION["SELECTOR_ALUMNO"]["id_carrera"]==4){?>
                <li><a href="../../Certificados/requerimientos_alumnos_TENS/requerimiento_alumnos_TENS.php" target="_blank">Requisito ingreso TENS</a></li>
                    <?php } ?>
                <li><a href="../../Alumnos/nvo_alumno/Intranet instructivoV2_1(ALUMNOS).pdf" target="_blank">Instructivo Intranet</a></li>
             
       
        <?php }?>
         </ul>
            </li>
     </ul>   
</li>
<!--FIN OPCIONES ACADEMICAS-->
<?php }?>
<?php if($ver_opciones_registros){?>
<!--OPCIONES REGISTRO-->
<li>
	<a href="#">Registros</a>
    <ul>
    	<li><a href="../../Notas/ingresanota_individual.php">Notas Semestrales</a></li>
        <li><a href="../../Notas_parciales_3/informe_notas_alumno/ver_notas_parciales_v3_1.php">Notas Parciales v3</a></li>
        <li><a href="#" onclick="REDIRIGIR('../../Alumnos/edit_alumno/crea_registro_academico_indv/grabaregistro.php','Seguro Desea Crear Registro Academico')">Creacion Registro Academico</a></li>
         <li><a href="../../asignaturas_ramo/tomaramo_individual.php">Toma de Ramos</a></li>
         <li><a href="../../Alumnos/asistencia/asistencia.php">Asistencia</a></li>
        <li><a href="../../Alumnos/registro_actividades/ver_registro_actividades.php">Operaciones Realizadas</a></li>
        <li><a href="../../Alumnos/documentacion_alumno/documentacion_alumno.php">Documentacion</a></li>
        <li><a href="../../Alumnos/registro_postulaciones_FUAS/registro_postulaciones_FUAS.php">Registro Postulacion FUAS</a></li>
        <li><a href="../../informes/revisionEvaluacionDocente/revisionEvaluacionDocente.php">Evalucion Docentes Realizadas</a></li>
    </ul>
</li>
<!--FIN OPCIONES REGISTRO-->
<?php }?>
<?php if($ver_opciones_finanzas){?>
<!--OPCIONES FINANZAS-->
<li>
	<a href="#">Finanzas</a>
     <ul>
    	<li><a href="#" onclick="REDIRIGIR('../../contabilidad/contrato/comprueba_X.php','Seguro Desea Iniciar Proceso Matricula')">Matricular</a></li>
        <li><a href="../../contabilidad/informe_financiero_alumno/index.php">Impresion y Gestion de Contratos</a></li>
        <li><a href="../../contabilidad/pagacuo/cuota1.php">Pago de Cuotas</a></li>
        <li><a href="../../contabilidad/registro_ingresos_boleta/ingresos_boleta.php">Pago Certificados y otros</a></li>
        <li><a href="../../contabilidad/asignar_beca/asignar_beca_1.php">Asignacion de Becas</a></li>
        <li> <a href="../../contabilidad/repactar_cuotas/repactar_cuota_1.php">Repactar Cuotas</a></li>
        <li><a href="../../contabilidad/utilizacion_interes_alumno/gestionar_interes_1.php?TB_iframe=true&height=350&width=300" rel="sexylightbox">Aplicar Interes</a></li>
        <li><a href="../../contabilidad/gestion_cobranza/utilizar_para_cobranza/utilizar_para_cobranza_1.php?TB_iframe=true&height=350&width=300" rel="sexylightbox">Seleccionar para Cobranza</a></li>
    </ul>
</li> 
<!--FIN OPCIONES FINANZAS--> 
<?php }?>  
<?php if($ver_opciones_solicitudes){?>
<!--OPCIONES SOLICITUDES-->
<li>
	<?php if(count($ARRAY_SOLICITUDES)>0)
	{
		switch($privilegio)
		{
			case"admi":
				if($num_solicitudes_pendientes>0)
				{
					$informacion_solicitudes='<a href="#" class="hint--top  hint--error hint--always" data-hint="'.$num_solicitudes_pendientes.' Pendientes">Solicitudes</a>';
				}
				else{ $informacion_solicitudes='<a href="#">Solicitudes</a>';}
				break;
			case"matricula":
				if($num_solicitudes_no_autorizadas>0)
				{ $informacion_solicitudes='<a href="#" class="hint--top  hint--error hint--always" data-hint="'.$num_solicitudes_no_autorizadas.' Pendientes">Solicitudes</a>';}
				else{ $informacion_solicitudes="";}
				break;
			case"admi_total":
				$informacion_solicitudes='<a href="#" class="hint--top  hint--error hint--always" data-hint="'.$num_solicitudes_autorizadas.'/'.$num_solicitudes.'">Solicitudes</a>';
				break;
			default:
				$informacion_solicitudes="";	
		}
	}
	else{ $informacion_solicitudes='<a href="#">Solicitudes</a>';}
	
	echo $informacion_solicitudes;
  	?>
     <ul>
  		<li><a href="#">Solicitudes Pendientes</a>
        	<ul>
            	<?php 
				if(count($ARRAY_SOLICITUDES)>0)
				{
					$ver_solicitud_actual=false;
					$cuenta_solicitud=0;
					
					foreach($ARRAY_SOLICITUDES as $nS =>$valorS)
					{ 
						$cuenta_solicitud++;
						$aux_categoria=$valorS["categoria"];
						$aux_autorizado=$valorS["autorizado"];
						$aux_fecha_hora_solicitud=$valorS["fecha_hora_solicitud"];
						$aux_fecha_hora_creacion=$valorS["fecha_hora_creacion"];
						$aux_fecha_hora_autorizacion=$valorS["fecha_hora_autorizacion"];
						$aux_estado=$valorS["estado"];
						$target="";
						switch($privilegio)
						{
							case"admi":
									if((($aux_autorizado=="si")and($aux_fecha_hora_creacion>=$fecha_limite_solicitud))or(($aux_autorizado=="si")and($aux_estado=="pendiente")))
									{ $url_solicitud="../../solicitudes/generacion_documentos/redireccion.php?id_solicitud=".$nS; $ver_solicitud_actual=true; $target="_blank";}
									else
									{$url_solicitud="#"; $ver_solicitud_actual=false;}
								break;
							case"matricula":
									if(($aux_autorizado=="no")and($aux_fecha_hora_solicitud>=$fecha_limite_solicitud))
									{$url_solicitud="../../solicitudes/operaciones/autorizacion_financiera_1.php?id_solicitud=".$nS; $ver_solicitud_actual=true;}
									else{ $url_solicitud="#"; $ver_solicitud_actual=false;}
								break;
							case"admi_total":
									$ver_solicitud_actual=true;
									if($aux_autorizado=="no")
									{$url_solicitud="../../solicitudes/operaciones/autorizacion_financiera_1.php?id_solicitud=".$nS;}
									else{ $url_solicitud="../../solicitudes/generacion_documentos/redireccion.php?id_solicitud=".$nS; $target="_blank";}
								break;
							default:
								$url_solicitud="#";	
								$ver_solicitud_actual=false;
						}
						
						
					   if($ver_solicitud_actual)
					   {
						   echo'<li><a href="'.$url_solicitud.'" class="hint--right  hint--info" data-hint="Autorizado -> '.$aux_autorizado.' ('.$aux_fecha_hora_autorizacion.')" target="'.$target.'">'.$cuenta_solicitud.'->'.$aux_categoria.'</a></li>';
					   }
					}
				}
				else
				{ echo'<li><a href="#">Sin Solicitudes...</a></li>';}?>
            </ul>
        </li>
        <li><a href="../../solicitudes/crea_solicitud/crea_solicitud_1.php?TB_iframe=true&height=400&width=750" rel="sexylightbox">Crear Solicitud</a></li>
    </ul>
</li> 

<!--FIN OPCIONES SOLICITUDES--> 
<?php }?>  
<li><a href="#">ON-line</a>
	<ul>
    	<?php
		if(isset($array_usuarios_activos))
		{
			if(isset($array_usuarios_activos[0]))
			{
				if($array_usuarios_activos[0]!="No hay usuarios")
				{
					foreach($array_usuarios_activos as $nua=>$valorua)
					{echo'<li><a href="#">'.$valorua.' on-line</a></li>';}
				}
				else
				{echo'<li><a href="#">No hay Usuarios Conectados :(</a></li>';}

			}else{echo'<li><a href="#">No hay Usuarios Conectados :(</a></li>';}
		}
		else
		{echo'<li><a href="#">No hay Usuarios Conectados :[</a></li>';}
        ?>
    </ul>
</li>
<li><a href="<?php echo $url_menu;?>">Volver al Menu</a></li>
<li><a href="../../OKALIS/msj_error/salir.php">Salir</a></li>
</ul>
<br style="clear: left" />
</div> 
<div id="apDiv1">
  <table width="100%" border="0">
  <thead>
    <tr>
      <th colspan="3"><span class="Estilo2">Alumno Actual</span></th>
      <th width="18%" ><div align="right">
      <?php if(isset($_SESSION["ULTIMO_ALUMNO"]["id_alumno"])and(count($_SESSION["ULTIMO_ALUMNO"]["id_alumno"])>0)){?>
      <a href="../historialSelecciones.php" title="Ultimo Alumno Seleccionado"><img src="../../BAses/Images/atras.png" width="16" height="16" alt="&lt;" /></a>
      <?php }?>
      <a href="../index.php">
      <img src="../../BAses/Images/icono_alumnos.gif" width="20" height="20" alt="cambiar"  title="Cambiar Alumno"/>
      </a>
      <a href="../deseleccionar_alumno.php" title="Deseleccionar Alumno">
      <img src="../../BAses/Images/b_drop.png" alt="X" width="16" height="16" />
      </a>
      </div></th>
    </tr>
    </thead>
    <tbody>
    <tr class="odd">
      <td width="17%" rowspan="3"><?php echo $icono_alumno; ?></td>
      <td width="19%"><span class="Estilo2">ID</span></td>
      <td colspan="2"><span class="Estilo4"><?php if(isset($_SESSION["SELECTOR_ALUMNO"]["id"])){ echo $_SESSION["SELECTOR_ALUMNO"]["id"]; } ?></span></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo2">Rut</span></td>
      <td colspan="2"><span class="Estilo4"><?php if(isset($_SESSION["SELECTOR_ALUMNO"]["rut"])){ echo $_SESSION["SELECTOR_ALUMNO"]["rut"]; } ?></span></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo2">Alumno</span></td>
      <td colspan="2"><span class="Estilo4"><?php if(isset($_SESSION["SELECTOR_ALUMNO"]["nombre"])){ echo $_SESSION["SELECTOR_ALUMNO"]["nombre"]." ".$_SESSION["SELECTOR_ALUMNO"]["apellido"]; } ?></span></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo2">Carrera(s)</span></td>
      <td colspan="3"><span class="Estilo4">
	  <?php if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])){ 
			  foreach($ALUMNO->getMatriculasAlumno() as $n =>$AuxArray){
				  $selector='';
				  $selector2='';
				  if($AuxArray["id_carrera"]==$_SESSION["SELECTOR_ALUMNO"]["id_carrera"] and $AuxArray["yearIngresoCarrera"]==$_SESSION["SELECTOR_ALUMNO"]["yearIngresoCarrera"]){ $selector='<strong>'; $selector2='</strong>';}
				  
				echo $selector.'<a href="../cambioMatriculaActiva.php?id_carreraNew='.base64_encode($AuxArray["id_carrera"]).'&yearIngresoNew='.base64_encode($AuxArray["yearIngresoCarrera"]).'&situacionNew='.base64_encode($AuxArray["situacion"]).'">'.NOMBRE_CARRERA($AuxArray["id_carrera"])." ".$AuxArray["yearIngresoCarrera"]."</a>".$selector2."<br>";
			}
	  }
	  ?> 
      </span></td>
      </tr>
    <tr class="odd">
      <td>&nbsp;</td>
      <td class="Estilo2">Academica</td>
      <td colspan="2"><span class="Estilo4"><?php if(isset($_SESSION["SELECTOR_ALUMNO"]["situacion"])){ echo $_SESSION["SELECTOR_ALUMNO"]["situacion"];}  if(isset($_SESSION["SELECTOR_ALUMNO"]["jornada"])){ echo $ARRAY_ICONO_JORNADA[$_SESSION["SELECTOR_ALUMNO"]["jornada"]];} if(isset($_SESSION["SELECTOR_ALUMNO"]["nivel_academico"])){ echo "{".$_SESSION["SELECTOR_ALUMNO"]["nivel_academico"]."}";}else{ } if(isset($_SESSION["SELECTOR_ALUMNO"]["sede"])){ echo" ". $_SESSION["SELECTOR_ALUMNO"]["sede"];}?></span></td>
    </tr>
    <tr class="odd">
      <td>&nbsp;</td>
      <td class="Estilo2">Financiera</td>
      <td colspan="2"><?php echo $informacion_financiera_alumno;?></td>
    </tr>
    </tbody>
  </table>
</div>
<div id="apDiv2">
   <table width="100%">
      <thead>
      <tr>
      	<th colspan="5">Observaciones</th>
      </tr>
        </thead>
        <tbody>
        <?php
		if(isset($_SESSION["SELECTOR_ALUMNO"]))
		{
		if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
		{
			$cons_HV="SELECT * FROM hoja_vida WHERE id_alumno='".$_SESSION["SELECTOR_ALUMNO"]["id"]."' ORDER by fecha desc";
			if(DEBUG){ echo"-->$cons_HV<br>";}
			$sql_HV=$conexion_mysqli->query($cons_HV)or die($conexion_mysqli->error);
			$num_observaciones=$sql_HV->num_rows;
			if($num_observaciones>0)
			{
				$contador=1;
				while($HV=$sql_HV->fetch_assoc())
				{
					$id_observacion=$HV["id"];
					$observacion=$HV["observacion"];
					$fecha=$HV["fecha"];
					$id_user=$HV["id_user"];
					////////////////////
						$cons_user="SELECT nombre, apellido FROM personal WHERE id ='$id_user'";
						$sql_user=$conexion_mysqli->query($cons_user) or die($conexion_mysqli->error);
						$DU=$sql_user->fetch_assoc();
						$usuario_nombre=$DU["nombre"];
						$usuario_apellido=$DU["apellido"];
						$usuario_nombre=$usuario_nombre." ".$usuario_apellido;
						$sql_user->free();
					//////////////////////
					$tipo_visualizacion=$HV["tipo_visualizacion"];
					echo'<tr>
						  <td>'.$contador.'</td>
						  <td>'.fecha_format($fecha).' '.$tipo_visualizacion.'</td>
						  <td>'.$observacion.'</td>
						  <td><a href="#" title="'.$usuario_nombre.'">'.$id_user.'</a></td>
						  </tr>';
						$contador++;
				}
				$sql_HV->free();
			}
			else
			{  echo'<tr><td colspan="7">Sin observaciones Registradas...</td></tr>';}
			
		}
		}
		?>
          </tbody>
        
        <tr>
        <td colspan="7"><div align="right">
        <?php if((isset($_SESSION["SELECTOR_ALUMNO"]))and($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])){?>
        <a href="../../registro_observaciones/observaciones/nueva_observacion.php/nva_observacion_MIN.php?id_alumno=<?php echo $_SESSION["SELECTOR_ALUMNO"]["id"];?>&amp;TB_iframe=true&amp;height=400&amp;width=750" rel="sexylightbox" title="Agregar Observacion"><img src="../../BAses/Images/add.png" alt="[+]" width="15" height="15" /></a>
        <?php }?>
        </div></td>
        </tr>
      </table>
</div>
</body>
<?php if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])){ $conexion_mysqli->close();}?>
</html>