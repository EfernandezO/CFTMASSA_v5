<?php
//--------------CLASS_okalis------------------//
	require("../../class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Permiso_acceso_a_modulos");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css"/>
<title>Okalis | permisos de archivo</title>
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
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:44px;
	z-index:1;
	left: 5%;
	top: 134px;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Modulos</h1>
<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a href="#">Permisos</a>
  <ul>
 	 <li><a href="permisosPorUsuario.php">Ver por usuario</a></li>

  </ul>
</li>

<li><a href="../../../Administrador/ADmenu.php">Volver al Menu</a></li>
</ul>
<br style="clear: left" />
</div> 
<div id="apDiv1">
  
  <table width="60%" border="1" align="center">
<thead>
  <tr>
    <th colspan="5">Modulos Disponibles</th>
  </tr>
   <tr>
    <td>N</td>
    <td>Nombre</td>
    <td>Categoria</td>
    <td colspan="2">Opc</td>
  </tr>
  </thead>
  <tbody>
<?php
	require("../../../../funciones/conexion_v2.php");
	$cons="SELECT * FROM okalis_archivos ORDER by categoria";
	$sqli=$conexion_mysqli->query($cons);
	$num_archivo=$sqli->num_rows;
	if($num_archivo>0)
	{
		$aux=0;
		while($A=$sqli->fetch_assoc())
		{
			$aux++;
			$id_archivo=$A["id_archivo"];
			$nombre_modulo=$A["nombre_modulo"];
			$categoria=$A["categoria"];
			echo'<tr>
					<td>'.$aux.'</td>
					<td>'.$nombre_modulo.'</td>
					<td>'.$categoria.'</td>
					<td><a href="comprobar_permisos.php?id_archivo='.base64_encode($id_archivo).'&lightbox[iframe]=true&lightbox[width]=650&lightbox[height]=450" class="lightbox" >permiso</a></td>
					<td><a href="../edicion_modulo/edicion_modulo_1.php?id_archivo='.base64_encode($id_archivo).'&lightbox[iframe]=true&lightbox[width]=650&lightbox[height]=450" class="lightbox" >Edicion</a></td>
				 </tr>';
		}
	}
	else
	{}
	$sqli->free();
	$conexion_mysqli->close();
	
?>
</table>
</div>
</body>
</html>