<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("agregaObservacionAlumno");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>nva Observacion</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:347px;
	height:115px;
	z-index:1;
	left: 38px;
	top: 148px;
}
.Estilo1 {font-size: 12px}
.Estilo3 {font-size: 12px; font-weight: bold; }
.Estilo5 {font-size: 12px; font-style: italic; }
#apDiv2 {
	position:absolute;
	width:96%;
	height:115px;
	z-index:1;
	left: 2%;
	top: 85px;
}
-->
</style>
<!-- TinyMCE -->
<script type="text/javascript" src="../../../libreria_publica/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups,autosave",

		// Theme options
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example word content CSS (should be your site CSS) this one removes paragraph margins
		content_css : "css/word.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
</script>
<!-- /TinyMCE -->
<SCRIPT language="javascript">
function confirmar()
{
	c=confirm('Seguro desea Agregar esta Observacion');
	if(c)
	{
		document.frm.submit();
	}
}
</SCRIPT>
</head>
<?php
$id_alumno=$_GET["id_alumno"];
if(is_numeric($id_alumno))
		{
			include("../../../../funciones/conexion_v2.php");
			$id_alumno=$_GET["id_alumno"];
			$cons_alu="SELECT * FROM alumno WHERE id='$id_alumno'";
			$sql_alu=$conexion_mysqli->query($cons_alu);
			$DA=$sql_alu->fetch_assoc();
			$nombre=$DA["nombre"];
			$apellido_old=$DA["apellido"];
			$apellido_new=$DA["apellido_P"]." ".$DA["apellido_M"];
			if($apellido_new==" ")
			{ $apellido_label=$apellido_old;}
			else
			{ $apellido_label=$apellido_new;}
			$carrera=$DA["carrera"];
			$nivel=$DA["nivel"];
			$sql_alu->free();
			$conexion_mysqli->close();
		}
?>
<body>
<h1 id="banner">Administrador - Hoja de Vida</h1>

	<div id="link">
	  <div align="right"><br /></div>
	</div>
	<div id="apDiv2">
      <form action="nva_observacion2.php" method="post" name="frm" id="frm2">
        <table width="80%" border="1" align="center">
          <thead>
            <tr>
              <th colspan="2">Observaciones de <span class="Estilo5"><?php echo $nombre;?> <?php echo $apellido_label;?></span></th>
            </tr>
          </thead>
          <tbody>
            <tr class="odd">
              <td>Tipo Visualizacion</td>
              <td><label for="tipo_visualizacion"></label>
                <select name="tipo_visualizacion" id="tipo_visualizacion">
                  <option value="privada">privada</option>
                  <option value="publica" selected="selected">publica</option>
              </select></td>
            </tr>
            <tr class="odd">
              <td colspan="2"><div align="center">
                  <textarea id="observacion" name="observacion" rows="3" cols="50" style="width: 80%"></textarea>
              </div></td>
            </tr>
            <tr class="odd">
              <td colspan="2"><div align="right">
                <input name="origen" type="hidden" id="origen" value="nueva_observacion_MIN" />
                <input name="id_alumno" type="hidden" id="id_alumno" value="<?php echo $id_alumno;?>" />
                
                  <input type="submit" name="button2" id="button2" value="Agregar" />
        
              </div></td>
            </tr>
          </tbody>
        </table>
        <br />
      </form>
</div>
	<p>&nbsp;</p>
    </body>
</html>
