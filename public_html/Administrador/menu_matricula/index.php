<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="matricula";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	
	
	$id_usuario_activo=$_SESSION["USUARIO"]["id"];

	require("../../../funciones/conexion_v2.php");
	$privilegios=$_SESSION["privilegio"];
	

  		$nombre=$_SESSION["USUARIO"]["nombre"];
  		$apellido=$_SESSION["USUARIO"]["apellido"];
  		$sede=$_SESSION["USUARIO"]["sede"];
	
	
	/////-----------------------------
	//session para el CHAT
	/////----------------------------------
	$_SESSION["CHAT"]["nick"] = $_SESSION["USUARIO"]["nick"]; // Must be already set
	//var_export($_SESSION["CHAT"]);
	//busco usuarios activos
	require("../../../funciones/VX.php");
	//cambio estado_conexin USER-----------
	 CAMBIA_ESTADO_CONEXION($id_usuario_activo, "on");
	$array_usuarios_activos=USUARIOS_ACTIVOS($id_usuario_activo);
	//------------------------------------------
	/////////////////////////////////////////////////////////////
	//verificando Mensaje
	$cons_M="SELECT COUNT(id) FROM mensajes WHERE destinatario='$id_usuario_activo' AND leido='no'";
	$sql_M=$conexion_mysqli->query($cons_M)or die($conexion_mysqli->error);
	$D_M=$sql_M->fetch_row();
	$mensajes_no_leidos=$D_M[0];
	if(empty($mensajes_no_leidos))
	{ $mensajes_no_leidos=0;}
	$sql_M->free();
	if($mensajes_no_leidos>0)
	{ 
		$mensaje_label="($mensajes_no_leidos) Mensaje(s) sin Leer";
		$img_msj='../../BAses/Images/globo_rojo.png';
	}
	else
	{ 
		$mensaje_label="No hay Mensajes Nuevos";
		$img_msj='../../BAses/Images/globo_verde.png';
	}
	$destino='../../Mensajes/ver_mjs/ver_mjs.php';
	$icono_MSJ='<a href="'.$destino.'" onMouseover="ddrivetip(\''.$mensaje_label.'\', 160)"; onMouseout="hideddrivetip()"><img src="'.$img_msj.'" alt="Msj" width="25" /></a> ';
	//////////////////////////////////////////////////////////////////
	//solicitudes
	$fecha_actual=date("Y-m-d");
	$fecha_limite=date("Y-m-d", strtotime("$fecha_actual -10 days"));///fecha limite =fecha corte +10 dias
	switch($privilegios)
	{
		case"admi_total":
			$condicion_solicitud="";
			$informacion_solicitud="Generadas...";
			break;
		case"matricula":
			//$condicion_solicitud="WHERE sede_receptor='$sede' AND autorizado='no' AND estado='pendiente'";
			$condicion_solicitud="WHERE sede_receptor='$sede' AND autorizado='no' AND fecha_hora_solicitud>= '$fecha_limite'";
			$informacion_solicitud="Pendientes...";
			break;	
		default:
			$condicion_solicitud="";
			$informacion_solicitud="";
	}
	///////////
	//revision solicitudes
		$cons_solicitud="SELECT COUNT(id) FROM solicitudes $condicion_solicitud";
		
		$sql_solicitud=$conexion_mysqli->query($cons_solicitud)or die($conexion_mysqli->error);
		$D_solicitud=$sql_solicitud->fetch_row();
		$num_general_solicitudes=$D_solicitud[0];
		if(DEBUG){ echo"$cons_solicitud<br>NUM solicitudes: $num_general_solicitudes<br>";}
		if(empty($num_general_solicitudes)){ $num_general_solicitudes=0;}
		$sql_solicitud->free();
		$informacion_solicitud=$num_general_solicitudes." ".$informacion_solicitud;
		
		if($num_general_solicitudes>0)
		{ $hay_solicitudes=true;}
		else
		{ $hay_solicitudes=false;}
	/////////////////////////////////////////////////////////////
	
	$conexion_mysqli->close();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-3" />
<title>Menu matricula</title>
<script type="text/javascript" src="../../libreria_publica/jquery_libreria/jquery.min_1.2.6.js"></script>
<!--INICIO MENU HORIZONTAL-->
 <link rel="stylesheet" type="text/css" href="../../libreria_publica/menu_horizontal/ddsmoothmenu-v.css"/>  
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
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../chat/css/chat.css"/>
<script type="text/javascript" src="plugin_chat.js"></script>
<style type="text/css">
<!--
a:link {
	color: #3399FF;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #3399FF;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #3399FF;
}
.Estilo1 {
	font-size: 12px;
	font-weight: bold;
}
#link {
	text-align: right;
	padding-right: 10px;
}
-->
    </style>

<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:100%;
	height:20px;
	z-index:1;
	left: 0px;
	top: 423px;
}
-->
</style>
</head>

<body>
<h1 id="banner">Matriculas <?php echo date("Y"); ?> - Men&uacute; Principal </h1>
  <div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a href="#">Utilidades</a>
  <ul>
  <li><a href="#">Plantillas de Impresion</a>
    <ul>
    <li><a href="../../utilidades_varias/lomo_archivador/lomo_archivador_1.php">Lomo Archivador</a></li>
    </ul>
  </li>
  <li><a href=" ../../agenda_contactos/index.php">Agenda de Contactos</a></li>
  </ul>
</li>
<li><a <?php if($hay_solicitudes){?>class="hint--top  hint--always hint--error" data-hint="<?php echo $informacion_solicitud; }?>" href="../../solicitudes/revisiones/revisar_solicitudes_global.php">Gesti&oacute;n Solicitudes</a>
</li>
<li><a href="#">Configuración</a>
	<ul>
    	<li><a href="../mis_datos/index.php">Mis Datos</a></li>
    </ul>
</li>
<div id="div_user_conectados">
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
 </div>
<li><a href="../../OKALIS/msj_error/salir.php">Salir</a></li>
</ul>
<br style="clear: left" />
</div> 
 
<div id="link"><?php echo $icono_MSJ;?><a href="index.php" target="_blank" title="Expandir"><img src="../../BAses/Images/expandir.png" width="15" height="15" /></a> <a href="#" onclick="javascript:window.close();" title="Contraer"><img src="../../BAses/Images/contraer.png" width="15" height="15" /></a></div>

<h3>Bienvenido Sr(a).: <?php echo"$nombre $apellido<br> ";?> <br />
	Sede.:<?php echo $sede;?></h3>
<div id="menu">
<table width="200" border="0" align="center">
    <tr>
      <td><a href="../../buscador_alumno_BETA/HALL/index.php" title="Alumnos"><img src="../../BAses/Images/icono_alumno.jpg" alt="Alumnos" width="200" /></a></td>
      <td><a href="../../contabilidad/index.php"><img src="../../BAses/Images/icono-de-dinero.jpg" title="Finanzas" alt="Finanzas" width="200" /></a></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</div>

<div id="apDiv1">
      <div align="center"><a href="../../Alumnos/menualumnos.php"> Menu Alumnos </a>|<a href="../../contabilidad/index.php"> Finanzas </a>|<a href="../../Docentes/lista_funcionarios.php">Funcionarios</a>|<a href="../mis_datos/index.php">Mis Datos</a> |<a href="../../OKALIS/msj_error/salir.php">Salir</a></div>
</div>
</body>
</html>