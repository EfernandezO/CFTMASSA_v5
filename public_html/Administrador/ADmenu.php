<?php
//-----------------------------------------//
	require("../OKALIS/seguridad.php");
	require("../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	OKALIS($lista_invitados);
	
	define("DEBUG", false);
//-----------------------------------------//	
	//////////////////
	$mostrar_nota_rapida=true;
	/////////////////	

	require("../../funciones/conexion_v2.php");
	$id_usuario_activo=$_SESSION["USUARIO"]["id"];
	$privilegios=$_SESSION["USUARIO"]["privilegio"];
	$sede=$_SESSION["USUARIO"]["sede"];
	
  		$nombre=$_SESSION["USUARIO"]["nombre"];
  		$apellido=$_SESSION["USUARIO"]["apellido"];
		$sede_usuario=$_SESSION["USUARIO"]["sede"];
 
	/////////////////////////////////////////////////////////////
	/////-----------------------------
	//session para el CHAT
	/////----------------------------------
	if(isset($_SESSION["USUARIO"]["nick"]))
	{ $_SESSION["CHAT"]['nick'] = $_SESSION["USUARIO"]["nick"];}
	else{ $_SESSION["CHAT"]['nick'] = $_SESSION["USUARIO"]["rut"];}
	//busco usuarios activos
	include("../../funciones/VX.php");
	//cambio estado_conexin USER-----------
	 CAMBIA_ESTADO_CONEXION($id_usuario_activo, "on");
	$array_usuarios_activos=USUARIOS_ACTIVOS($id_usuario_activo);
//------------------------------------------
	
	//verificando Mensaje
	$cons_M="SELECT COUNT(id) FROM mensajes WHERE destinatario='$id_usuario_activo' AND leido='no'";
	$sql_M=$conexion_mysqli->query($cons_M)or die("mensajes".$conexion_mysqli->error);
	$D_M=$sql_M->fetch_row();
	$mensajes_no_leidos=$D_M[0];
	if(empty($mensajes_no_leidos))
	{ $mensajes_no_leidos=0;}
	$sql_M->free();
	if($mensajes_no_leidos>0)
	{ 
		$mensaje_label="($mensajes_no_leidos) Mensaje(s) sin Leer";
		$img_msj='../BAses/Images/globo_rojo.png';
	}
	else
	{ 
		$mensaje_label="No hay Mensajes Nuevos";
		$img_msj='../BAses/Images/globo_verde.png';
	}
	$destino='../Mensajes/ver_mjs/ver_mjs.php';
	$icono_MSJ='<a href="'.$destino.'" onMouseover="ddrivetip(\''.$mensaje_label.'\', 160)"; onMouseout="hideddrivetip()"><img src="'.$img_msj.'" alt="Msj" width="25" /></a> ';
	//////////////////////////////////////////////////////////////////
	//nota rapida
	if($mostrar_nota_rapida)
	{
		$cons_NR="SELECT * FROM mensajes WHERE tipo_visualizacion='publica' AND leido='no' ORDER by id desc LIMIT 1";
		$sql_NR=$conexion_mysqli->query($cons_NR)or die("nota rapida ".$conexion_mysqli->error);
		$num_nr=$sql_NR->num_rows;
		if($num_nr>0)
		{
			$D_NR=$sql_NR->fetch_assoc();
			$txt_nota_rapida=$D_NR["mensaje"];
			if(!empty($txt_nota_rapida))
			{
				 $nota_rapida='<div id="nota">
				  <div id="nota_texto">'.$txt_nota_rapida.'</div>
				</div>';
			}	
		}
		else
		{$nota_rapida="";}	
		$sql_NR->free();
	}	
	
	//menu horizontal
	switch($privilegios)
	{
		case"admi_total":
			$mostrar_menu_administracion=true;
			$mostrar_configuraciones_sitio=true;
			$condicion_solicitud="";
			$informacion_solicitud="Generadas...";
			break;
		case"admi":
			$condicion_solicitud="WHERE sede_receptor='$sede_usuario' AND autorizado='si' AND estado='pendiente'";
			$mostrar_menu_administracion=false;	
			$mostrar_configuraciones_sitio=false;
			$informacion_solicitud="Pendientes...";
			break;	
		default:
			$mostrar_menu_administracion=false;	
			$mostrar_configuraciones_sitio=false;
			$condicion_solicitud="";
			$informacion_solicitud="";
	}
	///////////
	//revision solicitudes
		$cons_solicitud="SELECT COUNT(id) FROM solicitudes $condicion_solicitud";
		$sql_solicitud=$conexion_mysqli->query($cons_solicitud)or die($conexion_mysqli->error);
		$D_solicitud=$sql_solicitud->fetch_row();
		$num_general_solicitudes=$D_solicitud[0];
		if(empty($num_general_solicitudes)){ $num_general_solicitudes=0;}
		$sql_solicitud->free();
		$informacion_solicitud=$num_general_solicitudes." ".$informacion_solicitud;
		
		if($num_general_solicitudes>0)
		{ $hay_solicitudes=true;}
		else
		{ $hay_solicitudes=false;}
	///////////////////////////////
	//fecha servidor
	//date_default_timezone_set('America/Santiago');//zona horaria
	$fecha_servidor=date("d/m/Y H:i:s");
	////////////////////////
	//@mysql_close($conexion);
	$conexion_mysqli->close();
	////////////////////////	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<?php include("../../funciones/codificacion.php");?>
	<title>Menu Administrador</title>
	
	<link rel="stylesheet" type="text/css" href="../libreria_publica/jquery_treeview/jquery.treeview.css">
	<link rel="stylesheet" type="text/css" href="../libreria_publica/jquery_treeview/demo/screen.css">
	<link rel="stylesheet" type="text/css" href="../libreria_publica/hint.css-master/hint.css"/>
	<script type="text/javascript" src="../libreria_publica/jquery_libreria/jquery.min_1.2.6.js"></script>
	<script src="../libreria_publica/jquery_treeview/lib/jquery.cookie.js" type="text/javascript"></script>
	<script src="../libreria_publica/jquery_treeview/jquery.treeview.js" type="text/javascript"></script>
	
	<script type="text/javascript">
		$(function() {
			$("#browser").treeview();
		});
	</script>
<!--CHAT-->
<link rel="stylesheet" type="text/css" href="../chat/css/chat.css"/>
<script type="text/javascript" src="plugin_chat.js"></script>
<!--CHAT-->
	<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:240px;
	height:115px;
	z-index:1;
	left: 70%;
	top: 30%;
}
#apDiv1 #nota {
	background-image: url(../BAses/Images/nota_rapida.png);
	background-repeat: no-repeat;
	height: 267px;
	width: 233px;
}
#apDiv1 #nota #nota_texto {
	padding-top: 30px;
	padding-right: 5px;
	padding-bottom: 5px;
	padding-left: 5px;
}
-->
    </style>
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
</head>
	<body>
	<h1 id="banner">Administrador - Men&uacute; Principal </h1>
   <div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a href="#">Aplicaciones</a>
  <ul>
  <li><a href="../dactilografia/Lecciones_disponibles.php">Dactilografia</a></li>
  </ul>
</li>
<li><a href="#">Utilidades</a>
  <ul>
  <li><a href="#">Plantillas de Impresion</a>
    <ul>
    <li><a href="../utilidades_varias/lomo_archivador/lomo_archivador_1.php">Lomo Archivador</a></li>
    </ul>
  </li>
  <li><a href=" ../agenda_contactos/index.php">Agenda de Contactos</a></li>
  <li><a href="../utilidades_varias/calculo_notas_actas/calcula_nota.php">Calculo Notas Actas</a></li>
  <li><a href="../avisos/index.php">Avisos</a></li>
  <li><a href="../utilidades_varias/verificador_rut/verificador_rut.php">Verificador Rut</a></li>
  <li><a href="../utilidades_varias/mail_masivos/mail_masivo_0.php">Email Masivos</a></li>
  </ul>
</li>
<?php if($mostrar_menu_administracion){?>
<li><a href="#">Administraci&oacute;n</a>
	<ul>
    	<li><a href="administracion/administracion_estatus.php">Estatus</a></li>
        <li><a href="../utilidades/index.php">Respaldo BBDD</a></li>
        <li><a href="../historial/index.php">Historial</a></li>
        <li><a href="../Alumnos/trabajos_masivos/index.php">Procesos Excel</a></li>
    </ul>
</li>
<?php }?>
<li><a <?php if($hay_solicitudes){?>class="hint--top  hint--always hint--error" data-hint="<?php echo $informacion_solicitud; }?>" href="../solicitudes/revisiones/revisar_solicitudes_global.php">Gesti&oacute;n Solicitudes</a>
</li>
<li><a href="#">Configuración</a>
	<ul>
    	<li><a href="mis_datos/index.php">Mis Datos</a></li>
        <li><a href="../curso/listador_inscritos/lista_inscritos.php">Cursos Extra-Massa</a></li>
  
        <li><a href="#">Sitio</a>
        	<ul>
            	<li><a href="../noticias/menu_noticias.php">Noticias</a></li>
        		<li><a href="../Galeria/img_menu.php">Galeria Imagenes</a></li>
                <li><a href="../serviciosExternos/bolsaTrabajo/gestionOfertas.php">Bolsa de Trabajo</a></li>
            </ul>
        </li>
   
        <li><a href="#">Carga de Archivos</a>
        	<ul>
            <li><a href="../banco_pruebas/index.php">Banco de Pruebas</a></li>
            <li><a href="../upload_arch/index.php">Seccion Descargas</a></li>
            <li><a href="../downloadXasignatura/carga/index.php">Carga X asignatura</a></li>
            <li><a href="../archivador_general/index.php">Archivador General</a></li>
             <li><a href="../archivador_vario/index.php">Archivador Vario</a></li>
             <li><a href="../gestion_actas_reunion/index.php">Actas Reunion</a></li>
             <li><a href="../archivador_Documentos_institucionales/index.php">Documentos Institucionales</a></li>
          </ul>
        </li>
       <li><a href="../horario_V2/nuevo_horario/nvo_horario_1.php">Horario</a></li>
        <li><a href="../configuracionesAcademicas/index.php">Configuraciones Academicas</a></li>
        <li><a href="../gestion_encuestas/index.php">Encuestas</a></li>
        <li><a href="../OKALIS/gestion_acceso_archivos/relacion_usuario_archivo/relacion_usuario_archivo_1.php">Permisos/Modulos</a></li>
    </ul>
</li>
<li><a href="#">ON-line</a>
	
	<ul>
    	<?php
		if((isset($array_usuarios_activos))and(count($array_usuarios_activos)>0))
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
<li><a href="../OKALIS/msj_error/salir.php">Salir</a></li>
</ul>
<br style="clear: left" />
</div> 
    
   
	<div id="link"><?php echo $icono_MSJ; echo"<em>Fecha Servidor: $fecha_servidor</em>";?> </div>
	<h3>Bienvenido Sr(a).: <?php echo"$nombre $apellido<br> ";?> </h3>
      <div id="apDiv1">
		   <?php echo $nota_rapida;?>
    </div>
	<div id="main">
	<ul id="browser" class="filetree">
	  <li class="Estilo1"><strong><img src="../libreria_publica/jquery_treeview/images/folder.gif" /> <a href="../biblioteca/menu_biblioteca.php">Menu Biblioteca </a> </strong></li>
	  <li class="Estilo1"><img src="../BAses/Images/icono_funcionario.jpg" width="20" /> <a href="../Docentes/lista_funcionarios.php">Menu Funcionarios </a></li>
	  <li class="Estilo1"><img src="../BAses/Images/icono_alumnos.gif" width="25" /> <a href="../Alumnos/menualumnos.php">Menu Alumnos</a></li>
		<strong>
		<?php if($privilegios=="admi_total"){?>
		</strong>
	  <li class="Estilo1"><img src="../libreria_publica/jquery_treeview/images/folder.gif" alt="e" /> <a href="../contabilidad/index.php">Menu Finanzas</a></li>
		<li class="Estilo1"><a href="../desarrollo_intitucional/objetivos_estrategicos_main.php"><img src="../libreria_publica/jquery_treeview/images/file.gif" alt="e" width="15" height="14" /> Proyecto Desarrollo Institucional </a></li>
	  <?php }?>
	  <li class="closed Estilo1"><span class="Estilo1"><img src="../BAses/Images/icono_chat.jpg" alt="-" width="19" height="14" /> <a href="http://www.cftmass.cl/chatV3/demo/quien_esta_conectado.php" target="_blank">Chat</a></span>      </li>  
      </ul>
	  <br />

</div>
</body>
</html>