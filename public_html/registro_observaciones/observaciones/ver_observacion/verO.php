<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
//-----------------------------------------//	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>nva Observacion</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla.css">
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:347px;
	height:115px;
	z-index:1;
	left: 618px;
	top: 141px;
}
.Estilo1 {font-size: 12px}
#apDiv2 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:1;
	left: 5%;
	top: 76px;
}
.Estilo3 {font-size: 12px; font-weight: bold; }
.Estilo5 {font-size: 12px; font-style: italic; }
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
	c=confirm('Seguro desea Editar esta Observacion');
	if(c)
	{
		document.frm.submit();
	}
}
</SCRIPT>
</head>
<?php
	include("../../../../funciones/conexion.php");
	$id_alumno=$_GET["id_alumno"];
	$id_observacion=$_GET["id_observacion"];
		if(is_numeric($id_alumno))
		{
			$id_alumno=$_GET["id_alumno"];
			$cons_alu="SELECT * FROM alumno WHERE id='$id_alumno'";
			$sql_alu=mysql_query($cons_alu)or die(mysql_error());
			$DA=mysql_fetch_assoc($sql_alu);
			$nombre=$DA["nombre"];
			$apellido_old=$DA["apellido"];
			$apellido_new=$DA["apellido_P"]." ".$DA["apellido_M"];
			if($apellido_new==" ")
			{ $apellido_label=$apellido_old;}
			else
			{ $apellido_label=$apellido_new;}
			$carrera=$DA["carrera"];
			$nivel=$DA["nivel"];
			mysql_free_result($sql_alu);
			
		}
	////////////////////////////////////////////
	$cons_O="SELECT * FROM hoja_vida WHERE id='$id_observacion' AND id_alumno='$id_alumno'";
//	echo"$cons_O<br>";
	$sql_O=mysql_query($cons_O)or die(mysql_error());
	$DO=mysql_fetch_assoc($sql_O);
		$fecha=$DO["fecha"];
		$observacion=$DO["observacion"];
		$id_user=$DO["id_user"];
	mysql_free_result($sql_O);	
	mysql_close($conexion);	
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	switch($privilegio)
	{
		case"admi_total":
			$editar=true;
			break;
		default:
			$editar=false;	
	}
?>
<body>
<h1 id="banner">Administrador - Hoja de Vida</h1>

	<div id="link">
	  <div align="right"><br />
<a href="../hoja_vida.php?id_alumno=<?php echo $id_alumno;?>" class="button">Volver</a></div>
	</div>
<div id="apDiv2">
	<form action="edicion_observacion.php" method="post" name="frm" id="frm">
      <table width="450" border="1">
        <thead>
          <tr>
            <td colspan="2"><span class="Estilo3">Datos Alumno</span></td>
          </tr>
        </thead>
        <tbody>
          <tr class="odd">
            <td><div align="left"><span class="Estilo1">ID</span></div></td>
            <td><span class="Estilo5"><?php echo $id_alumno;?></span></td>
          </tr>
          <tr class="odd">
            <td><div align="left"><span class="Estilo1">Nombre</span></div></td>
            <td><span class="Estilo5"><?php echo $nombre;?></span></td>
          </tr>
          <tr class="odd">
            <td><div align="left"><span class="Estilo1">Apellido</span></div></td>
            <td><span class="Estilo5"><?php echo $apellido_label;?></span></td>
          </tr>
          <tr class="odd">
            <td><div align="left"><span class="Estilo1">Carrera</span></div></td>
            <td><span class="Estilo5"><?php echo $carrera;?></span></td>
          </tr>
          <tr class="odd">
            <td><div align="left"><span class="Estilo1">Nivel</span></div></td>
            <td><span class="Estilo5"><?php echo $nivel;?></span></td>
          </tr>
          <tr class="odd">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr class="odd">
            <td colspan="2"><div align="center">
              <textarea id="observacion" name="observacion" rows="3" cols="50" style="width: 80%"><?php echo $observacion;?></textarea>
            </div></td>
          </tr>
          <tr class="odd">
            <td colspan="2"><div align="right">
            	<input name="id_observacion" type="hidden" id="id_observacion" value="<?php echo $id_observacion;?>" />
              <input name="id_alumno" type="hidden" id="id_alumno" value="<?php echo $id_alumno;?>" />
              <?php if($editar){?>
              <input type="button" name="button" id="button" value="Editar"  onclick="confirmar();"/>
              <?php }?>
            </div></td>
          </tr>
        </tbody>
      </table>
      </form>
</div>
    </body>
</html>