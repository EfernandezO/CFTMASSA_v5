<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Documentacion_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

$continuar=false;
if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
{
	if(isset($_SESSION["SELECTOR_ALUMNO"]["id"]))
	{
		$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
		if(is_numeric($id_alumno))
		{ $continuar=true;}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Alumno Documentos</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<!--INICIO LIGHTBOX EVOLUTION-->
  <script type="text/javascript" src="../../libreria_publica/lightbox_1.6.9_evolution/js/jquery.min.js"></script>
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
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:285px;
	z-index:1;
	left: 5%;
	top: 76px;
}
#apDiv2 {
	position:absolute;
	width:30%;
	height:24px;
	z-index:1;
	left: 65%;
	top: 70px;
}
#apDiv3 {
	position:absolute;
	width:30%;
	height:29px;
	z-index:2;
	left: 65%;
	top: 128px;
}
</style>
<script language="javascript">
function ELIMINAR(id_documento)
{
	c=confirm('Seguro(a) Desea Eliminar este Documento..?');
	if(c)
	{
		window.location="elimina_documento.php?id_documento="+id_documento;
	}
}
</script>
</head>
<body>
<h1 id="banner">Administrador - Documentación Alumno</h1>
 <div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a href="#">Documentos</a>
  <ul>
  <li><a href="carga_documento_1.php?lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=510" class="lightbox">Agregar</a></li>
  </ul>
</li>
<li><a href="../../buscador_alumno_BETA/HALL/index.php">Volver al Menu</a>
</li>
</ul>
<br style="clear: left" />
</div> 
<?php if($continuar){?>
<div id="apDiv1">
  <table width="60%" border="1">
  <thead>
    <tr>
      <th colspan="2">Datos Alumno</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="20%">ID Alumno</td>
      <td width="80%"><?php echo $_SESSION["SELECTOR_ALUMNO"]["id"];?></td>
    </tr>
    <tr>
      <td>Nombre</td>
      <td><?php echo $_SESSION["SELECTOR_ALUMNO"]["nombre"];?></td>
    </tr>
    <tr>
      <td>Apellido</td>
      <td><?php echo $_SESSION["SELECTOR_ALUMNO"]["apellido"];?></td>
    </tr>
    <tr>
      <td>Carrera</td>
      <td><?php echo $_SESSION["SELECTOR_ALUMNO"]["carrera"];?></td>
    </tr>
    </tbody>
  </table>
  
  <?php 
  $msj="";
  $img="";
  $img_ok='<img src="../../BAses/Images/ok.png" width="29" height="26" alt="ok" />';
  $img_error='<img src="../../BAses/Images/b_drop.png" width="16" height="16" alt="error" />';
  if(isset($_GET["error"]))
  {
		$error=$_GET["error"];
		switch($error)
		{
			case"DAE0":
				$msj="Documento Eliminado...";
				$img=$img_ok;
				break;
		}
	  
  }
  ?>
  
  <div id="apDiv3"><?php echo $msj.$img;?></div>
  <p>&nbsp;</p>
  <table width="100%" border="1">
  <thead>
    <tr>
      <th width="34">N</th>
      <th width="34">Tipo</th>
      <th width="68">Archivo</th>
      <th width="36">Opc</th>
    </tr>
    </thead>
    <tbody>
    <?php
	$ruta="../../CONTENEDOR_GLOBAL/alumno_documentos/";
    require("../../../funciones/conexion_v2.php");
	 require("../../../funciones/VX.php");
	 $evento="Revisa Documentos de Alumno id_alumno: $id_alumno";
	 
	 REGISTRA_EVENTO($evento);
	 
	$cons="SELECT * FROM alumno_documentos WHERE id_alumno='$id_alumno'";
	$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$num_documentos=$sqli->num_rows;
	
	if($num_documentos>0)
	{
		$contador=0;
		while($D=$sqli->fetch_assoc())
		{
			$contador++;
			
			$D_id_documento=$D["id_documento"];
			$D_tipo=$D["tipo"];
			$D_archivo=$D["archivo"];
			$D_cod_user=$D["cod_user"];
			$D_fecha_generacion=$D["fecha_generacion"];
			
			echo'<tr>
					<td align="center">'.$contador.'</td>
					<td align="center">'.$D_tipo.'</td>
					<td align="center"><a href="'.$ruta.$D_archivo.'?lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=510" class="lightbox" title="Ver Documento">'.$D_archivo.'</a></td>
					<td><a href="#" onclick="ELIMINAR(\''.base64_encode($D_id_documento).'\');">Eliminar</a></td>
				 </tr>';
			
		}
	}
	else
	{ echo'<tr><td colspan="4">Sin Registros...</td></tr>';}
	
	$sqli->free();
	$conexion_mysqli->close();
	?>
    </tbody>
  </table>
  <p>&nbsp;</p>
<?php }else{ echo"Sin Acceso...";}?>
</div>
</body>
</html>