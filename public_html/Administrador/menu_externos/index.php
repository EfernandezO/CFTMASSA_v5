<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="externo";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	
include("../../../funciones/conexion.php");
$id_usuario_activo=$_SESSION["USUARIO"]["id"];
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
mysql_close($conexion);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Menu Externos</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js"></script>
<!--CHAT-->
<link rel="stylesheet" type="text/css" href="../../chat/css/chat.css"/>
<script type="text/javascript" src="plugin_chat.js"></script>
<!--CHAT-->
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

ddsmoothmenu.init({
	mainmenuid: "smoothmenu2", //Menu DIV id
	orientation: 'v', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu-v', //class added to menu's outer DIV
	//customtheme: ["#804000", "#482400"],
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})

</script>
<!--FIN MENU HORIZONTAL-->
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
   <link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/jquery.treeview.css">
	<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
	<script src="../../libreria_publica/jquery_treeview/lib/jquery.cookie.js" type="text/javascript"></script>
	<script src="../../libreria_publica/jquery_treeview/jquery.treeview.js" type="text/javascript"></script>
	
	<script type="text/javascript">
		$(function() {
			$("#browser").treeview();
		});
	</script>
    <style type="text/css">
<!--
.Estilo3 {font-size: 12px}
-->
    </style>
</head>

<body>
<h1 id="banner"> Men&uacute; Principal - Externo</h1>
<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a href="#">Configuración</a>
	<ul>
    	<li><a href="../mis_datos/index.php">Mis Datos</a></li>
    </ul>
</li>
<div id="div_user_conectados">
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
 </div>
<li><a href="../../OKALIS/msj_error/salir.php">Salir</a></li>
</ul>
<br style="clear: left" />
</div>

<?php
//var_export($_SESSION["USUARIO"]);
$user=$_SESSION["USUARIO"]["nombre"]." ".$_SESSION["USUARIO"]["apellido"];
$organizacion=$_SESSION["USUARIO"]["organizacion"];
?>
<div id="user">
<h3><strong><span class="Estilo3">Bienvenido</span> Sr. <?php echo $user;?></strong><br />
  <strong><span class="Estilo3">Organizacion:</span></strong> <?php echo $organizacion;?>
</h3>
</div>
<div id="main">
  <ul id="browser" class="filetree">
    <li class="Estilo1"><img src="../../libreria_publica/jquery_treeview/images/folder.gif" alt="e" /> Menu Alumnos
	    <ul>
	      <li><a href="../../contabilidad/informe_contratos_del_dia/index.php"><span class="closed Estilo1"><img src="../../libreria_publica/jquery_treeview/images/file.gif" alt="7" /></span></a> <a href="../../informes/informe_alumno_proceso_titulacion/index.php">Alumnos Proceso-Titulaci&oacute;n</a></li>
	      <li><a href="../../contabilidad/informe_deuda_alumno_NO_matriculado/index.php"><span class="closed Estilo1"><img src="../../libreria_publica/jquery_treeview/images/file.gif" alt="7" /></span></a> <a href="../../contabilidad/informe_deuda_alumno_NO_matriculado/index.php">Consulta Deuda Alumno NO matriculados</a></li>
	      <li><a href="#"><span class="closed Estilo1"><img src="../../libreria_publica/jquery_treeview/images/file.gif" alt="7" /></span></a> <a href="../../contabilidad/informe_contratos_del_dia/index.php">Informe Matriculas Generadas (rango fecha)</a></li>
	      </ul>
  </li>
   
</ul>
</div>
</body>
</html>