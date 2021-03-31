<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//--------------CLASS_okalis------------------//
	require("../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../funciones/";
	$O->clave_del_archivo=md5("Bibioteca_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
//----------------------------------------------//
//------------------------------------------//
@require_once ("../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("carga_datos_server.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"RECARGAR");
/////////////////////////////////////////////////////////////
$id_usuario_activo=$_SESSION["USUARIO"]["id"];
$sede_usuario=$_SESSION["USUARIO"]["sede"];
$privilegio=$_SESSION["USUARIO"]["privilegio"];
switch($privilegio)
{
	case"admi_total":
		$condicion_sede="";
		$condicion_carrera="WHERE id_carrera='1'";
		break;
	case"admi":
		$condicion_sede="WHERE sede='$sede_usuario'";
		$condicion_carrera="AND id_carrera='1'";
		break;
}
	require("../../funciones/conexion_v2.php");
	require("../../funciones/funciones_sistema.php");
	/////-----------------------------
	//session para el CHAT
	/////----------------------------------
	$_SESSION["CHAT"]['nick'] = $_SESSION["USUARIO"]["nick"]; // Must be already set
	//busco usuarios activos
	include("../../funciones/VX.php");
	//cambio estado_conexin USER-----------
	$evento="Ingreso a Menu de Biblioteca";
	 REGISTRA_EVENTO($evento);
	 CAMBIA_ESTADO_CONEXION($id_usuario_activo, "on");
	$array_usuarios_activos=USUARIOS_ACTIVOS($id_usuario_activo);
	
//------------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../funciones/codificacion.php");?>
<title>Menu Biblioteca</title>
	<link rel="stylesheet" type="text/css" href="../libreria_publica/jquery_treeview/demo/screen.css">
    <link rel="stylesheet" type="text/css" href="../CSS/tabla_2.css"/>
        <?php $xajax->printJavascript(); ?> 
        <script type="text/javascript" src="../libreria_publica/jquery_libreria/jquery.min_1.2.6.js"></script>
         <!--INICIO MENU HORIZONTAL-->
 <link rel="stylesheet" type="text/css" href="../libreria_publica/menu_horizontal/ddsmoothmenu-v.css"/>  
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
<script language="javascript">        
function ELIMINAR(url)
{
	c=confirm('Seguro(a) Desea Eliminar Este Libro...?');
	if(c)
	{
		d=confirm('Seguro(a) Seguro(a) Desea Eliminar Este Libro...?');
		if(d)
		{window.location=url;}
	}
}
function FOCO()
{
	document.getElementById('codigo_libro').focus();
}
</script>
<!--FIN MENU HORIZONTAL-->
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:96%;
	height:36px;
	z-index:1;
	left: 2%;
	top: 287px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:43px;
	z-index:2;
	left: 2%;
	top: 112px;
}
#apDiv3 {
	position:absolute;
	width:20%;
	height:40px;
	z-index:3;
	left: 45%;
	top: 157px;
}
#apDiv4 {
	position:absolute;
	width:25%;
	height:115px;
	z-index:4;
	left: 70%;
	top: 136px;
}
#texto_resaltado
{
	font-style:inherit;
	color:#FF0000;
}
</style>
</head>

<body  onload="xajax_RECARGAR(xajax.getFormValues('frm'));return false;" onunload="FOCO();">
<h1 id="banner">Administrador - Men&uacute; Biblioteca </h1>
 <div id="smoothmenu1" class="ddsmoothmenu">
<ul>
    <li><a href="#">Libros</a>
    <ul>
		<li><a href="edicion_libro/nvo_libro/crealibro.php">Agregar Nuevo</a></li>
	</ul>
</li>
<li><a href="#">Informes</a>
	<ul>
    	<li><a href="informe_libro/informe/listador_libro.php">Listador</a></li>
        <li><a href="informe_libro/informe_X_carrera/formBL.php">Prestamos</a></li>
        <li><a href="informe_libro/quien_tiene/quien_tiene_libro.php">Quien Tiene Libro?</a></li>
    </ul>
</li>
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

	<li><a href="../Administrador/ADmenu.php">Menu Principal</a></li>
</ul>
<br style="clear: left" />
</div> 
<h3>Sistema Integrado de Biblioteca </h3>
<div id="apDiv1" class="demo_jui">
    <table width="100%" border="0" id="example">
	<thead>
		<tr>
        	<th>ID Libro</th>
            <th>Sede</th>
            <th>Carrera</th>
            <th>Titulo</th>
			<th>Autor</th>
			<th>Condicion</th>
			<th>V</th>
            <th>E</th>
            <th>B</th>
		</tr>
	</thead>
	<tbody>
    </tbody>
</table>
    </div>
<div id="apDiv2">
<form action="" method="get" name="frm" id="frm">
	<table width="100%" border="1">
    <thead>
	  <tr>
	    <th colspan="2">Filtro</th>
      </tr>
      </thead>
      <tbody>
	  <tr>
	    <td>Codigo</td>
	    <td><label for="codigo_libro"></label>
	      <input type="text" name="codigo_libro" id="codigo_libro" /></td>
	    </tr>
	  <tr>
	    <td>Sede</td>
	    <td>
		<?php
	  include("../../funciones/funcion.php");
	  echo selector_sede("sede"); 
	  ?>
      </td>
      </tr>
	  <tr>
	    <td>Carrera</td>
	    <td>
        <?php
        echo CAMPO_SELECCION("carrera", "carreras", "", true, "");
		?>
      </select>
        </td>
      </tr>
	  <tr>
	    <td>Titulo</td>
	    <td><label for="titulo"></label>
	      <input name="titulo" type="text" id="titulo" size="40" /></td>
      </tr>
      </tbody>
    </table>
</form>
</div>
<div id="apDiv3"><a href="#" class="button_G" onclick="xajax_RECARGAR(xajax.getFormValues('frm'));return false;">Ver</a></div>
<div id="apDiv4">
<?php
if(isset($_GET["error"]))
{
	$error=$_GET["error"];
	
	$img_ok='<img src="../BAses/Images/ok.png" width="29" height="26" alt="ok" />';
	$img_error='<img src="../BAses/Images/b_drop.png" width="16" height="16" alt="X" />';
	$msj="";
	$img="";
	switch($error)
	{
		case"UP1":
			$msj="Libro Modificado...:D";
			$img=$img_ok;
			break;
		case"UP2":
			$msj="Error al intentar Modificar Libro";
			$img=$img_error;
			break;	
		case"E0":
			$msj="Libro Eliminado...";
			$img=$img_ok;
			break;
		case"E1":
			$msj="No se pudo Eliminar el Libro... :(";
			$img=$img_error;
			break;		
		case"E2":
			$msj="No se Pudo Eliminar El Registro de Archivos Asociados al Libro";
			$img=$img_error;
			break;	
		case"E3":
			$msj="No se Pueden Eliminar Los Archivos Asociados al Libro";
			$img=$img_error;
			break;	
		case"E4":
			$msj="Error al intentar Eliminar el Libro";
			$img=$img_error;
			break;							
	}
	
	echo "$msj $img";
}
?>
</div>
</body>
<?php  mysql_close($conexion); $conexion_mysqli->close();?>
</html>