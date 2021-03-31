<?php
//-----------------------------------------//
//--------------CLASS_okalis------------------//
	require("../../class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Permiso_acceso_a_modulos");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//-----------------------------------------//	
//////////////////////XAJAX/////////////////
@require_once ("../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("conceder_denegar_permisos.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"PERMISOS");
//------------------------------------------------------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php $xajax->printJavascript(); ?> 
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css"/>
<title>okalis comprobar permisos</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:63px;
	z-index:1;
	left: 5%;
	top: 169px;
}
#apDiv2 {
	position:absolute;
	width:50%;
	height:100px;
	z-index:2;
	left: 5%;
	top: 58px;
}
#div_informacion {
	position:absolute;
	width:40%;
	height:98px;
	z-index:3;
	left: 55%;
	top: 58px;
}
</style>
<!--INICIO LIGHTBOX EVOLUTION-->
   <script type="text/javascript" src="../../../libreria_publica/lightbox_1.6.9_evolution/js/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.css" />
  <!--[if IE 6]>
  <link rel="stylesheet" type="text/css" href="../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.ie6.css" />
  <![endif]-->
  <script type="text/javascript" src="../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/jquery.lightbox.min.js"></script>
 <script type="text/javascript">
    jQuery(document).ready(function($){
      $('.lightbox').lightbox(); 
    });
  </script>
 <!--FIN LIGHTBOX EVOLUTION-->
   <!--INICIO MENU HORIZONTAL-->
  <link rel="stylesheet" type="text/css" href="../../../libreria_publica/menu_horizontal/ddsmoothmenu.css"/>
 
<script type="text/javascript" src="../../../libreria_publica/menu_horizontal/ddsmoothmenu.js">

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
<h1 id="banner">Administrador - Permisos por usuario</h1>
<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a href="#">Permisos</a>
  <ul>
 	 <li><a href="relacion_usuario_archivo_1.php">Ver por Modulo</a></li>

  </ul>
</li>

<li><a href="../../../Administrador/ADmenu.php">Volver al Menu</a></li>
</ul>
<br style="clear: left" />
</div> 
<div id="apDiv1">
 <?php
 	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/funciones_sistema.php");
	
	$year_actual=date("Y");
	$mes_actual=date("m");
	if($mes_actual>=8){$semestre_actual=2;}
	else{ $semestre_actual=1;}
	
	$ARRAY_USUARIO=array();
	//busqueda de usuarios
 	$cons="SELECT id FROM personal WHERE nivel >=1 ORDER by apellido_P, apellido_M";
	$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$num_usuario=$sqli->num_rows;
	if($num_usuario>0)
	{
		$aux=0;
		while($U=$sqli->fetch_assoc())
		{
			$aux++;
			$U_id=$U["id"];
			$ARRAY_USUARIO[$aux]=$U_id;
		}
	}
	
	$sqli->free();
	//busqueda de modulos
	
	
	
?>
<table width="60%" align="center">
<thead>
<tr>
	<th colspan="3">usuario acceso administrador</th>
</tr>
</thead>
<tbody>
<?php	
	
	//-----------------------------------------//
	foreach($ARRAY_USUARIO as $n => $auxIdPersonal){
		echo'<tr>
				<td>'.NOMBRE_PERSONAL($auxIdPersonal).'</td>
				<td></td>
				<td><a href="comprobar_permisosUsuario.php?idUsuario='.base64_encode($auxIdPersonal).'&lightbox[iframe]=true&lightbox[width]=650&lightbox[height]=450" class="lightbox">Revisar</a></td>
			</tr>';
	}

		
	
	
	$conexion_mysqli->close();
?>
</tbody>
</table>
</body>
</html>