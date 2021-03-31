<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("bolsaTrabajoV1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>Ingreso Bolsa Trabajo</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<style type="text/css">
<!--
#Layer8 {	position:absolute;
	width:169px;
	height:20px;
	z-index:3;
	left: 333px;
	top: 63px;
}
#Laye1 {	position:absolute;
	width:442px;
	height:357px;
	z-index:2;
	left: 45px;
	top: 93px;
}
#Layer2 {
	position:absolute;
	width:90%;
	height:30px;
	z-index:2;
	left: 5%;
	top: 83px;
}
#Layer6 {
	position:absolute;
	width:48px;
	height:30px;
	z-index:3;
	left: 510px;
	top: 220px;
}
#Layer1 {
	position:absolute;
	width:44px;
	height:30px;
	z-index:4;
	left: 528px;
	top: 301px;
}
#Layer3 {
	position:absolute;
	width:48px;
	height:30px;
	z-index:5;
	left: 498px;
	top: 276px;
}
#Layer4 {
	position:absolute;
	width:48px;
	height:30px;
	z-index:6;
	left: 499px;
	top: 494px;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
#apDiv1 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:8;
	left: 154px;
	top: 370px;
}
#apDiv2 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:4;
}
#apDiv3 {
	position:absolute;
	width:50px;
	height:29px;
	z-index:7;
	left: 500px;
	top: 228px;
}
.Estilo3 {
	font-size: 12px;
	font-weight: bold;
}
.Estilo4 {font-size: 12px}
#link {
	text-align: right;
	padding-right: 10px;
}
-->
</style>
<!-- TinyMCE -->
<script type="text/javascript" src="../../../libreria_publica/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	// O2k7 skin (silver)
	tinyMCE.init({
		// General options
		mode : "exact",
		elements : "txt_noticia",
		theme : "advanced",
		skin : "o2k7",
		skin_variant : "silver",
		plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups,autosave",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "css/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Replace values for the template plugin
		template_replace_values : {
			username : "Massa_noticia",
			staffid : "991234"
		}
	});
</script>
<!-- /TinyMCE -->
</head>
<script language="javascript">
function tig_link()
{
	document.frm.fnoticia.value+='<a href="http://Aqui destino" target="_blank">Aqui texto</a>';
}
</script>

<body>
<h1 id="banner">Nueva - Oferta </h1>
<div id="link"><br /><a href="../gestionOfertas.php" class="button">
Volver al Menu</a></div>

<div id="Layer2">
  <form action="nueva2.php" method="post" enctype="multipart/form-data" name="frm" id="frm">
    <div align="center">
      <table width="60%" height="257" border="0" align="center">
        <thead>
          <tr>
            <th colspan="2"><div align="center" class="Estilo3">Ingrese Una Nueva Noticia </div></th>
          </tr>
        </thead>
        <tbody>
          <tr class="odd">
            <td><span class="Estilo3">Titulo:</span></td>
            <td><label>
              <input name="ftitulo" type="text" id="ftitulo" />
            </label></td>
          </tr>
          <tr class="odd">
            <td valign="top"><span class="Estilo3">Oferta</span></td>
            <td><label>
              <textarea name="txt_noticia" id="txt_noticia"></textarea>
              </label>
                <label><br />
              </label></td>
          </tr>
       
          <tr>
            <td colspan="2"><div align="center">
                <label></label>
              &nbsp;&nbsp;
<input type="submit" name="Submit" value="Continuar&gt;&gt;" />
            </div></td>
          </tr>
        </tbody>
      </table>
    </div>
  </form>
</div>
</body>
</html>