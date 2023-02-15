<?php include ("../../SC/seguridad.php");?>
<?php include ("../../SC/privilegio.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Edicion Noticias</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla.css">
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
<script type="text/javascript" src="../../libreria_publica/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
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
<body>
<h1 id="banner">Nueva - Noticia</h1>
<div id="link"><a href="../edita_noticia/edita_not1.php">Volver a seleccion</a></div>
<?php
    if($_GET["errorb"]==1)
	{
	 echo'<div id="apDiv3"><img src="../../BAses/Images/X.jpg" alt="No Valido" width="46" height="32" /></div>';
	}
	if($_GET["errorn"]==1)
	{
	 echo'<div id="Layer3"><img src="../../BAses/Images/X.jpg" alt="No Valido" width="52" height="32" /></div>';
	}
	if($_GET["errori"]==1)
	{
	 echo'<div id="Layer4"><img src="../../BAses/Images/X.jpg" alt="No Valido" width="46" height="32" /></div>';
	}
	
if($_GET)	
{
	$idN=base64_decode($_GET["idN"]);
	if(is_numeric($idN))
	{
		include("../../../funciones/conexion.php");
		$cons="SELECT * FROM noticias WHERE idn='$idN'";
		$sql=mysql_query($cons)or die(mysql_error());
		$DN=mysql_fetch_assoc($sql);
			$fecha=$DN["fecha"];
			$array_fecha=explode("-",$fecha);
			$anio_X=$array_fecha[0];
			$mes_X=$array_fecha[1];
			$dia_X=$array_fecha[2];
			$autor=$DN["autor"];
			$titulo=$DN["titulo"];
			$breve=$DN["breve"];
			$noticia=$DN["noticia"];
			$imagen=$DN["imagen"];
			$path="../image_not/";
			$ruta_img=$path.$imagen;
		mysql_free_result($sql);	
		mysql_close($conexion);
	}
}
?>
<div id="Layer2">
  <form action="modifica_noticia_2.php" method="post" enctype="multipart/form-data" name="frm" id="frm">
    <div align="center">
      <table width="60%" height="257" border="0">
        <thead>
          <tr>
            <td colspan="2"><div align="center" class="Estilo3">Edici&oacute;n de Noticia 
                <input name="idN" type="hidden" id="idN" value="<?php echo $idN;?>" />
            </div></td>
          </tr>
        </thead>
        <tbody>
          <tr class="odd">
            <td width="126"><span class="Estilo3">Fecha:</span></td>
            <td width="279"><label> <span class="Estilo4">dia
              <?php
	$dia_actual = date("d"); 
echo('<select name="fdia">'); 
for($i=1;$i<=31;$i++)
{ 

     if($i==$dia_X)
	 {
	     echo('<option value="'.$i.'" selected="selected">'.$i.'</option>'); 
	 }
	 else
	 {
         echo('<option value="'.$i.'">'.$i.'</option>'); 
	  }	 

} 

echo('</select>');  
?>
              mes
              <?php
	$mes_actual = date("n"); 
    


echo('<select name="fmes">'); 

for($i=1;$i<=12;$i++)
{ 

     if($i==$mes_X)
	 {
	     echo('<option value="'.$i.'" selected="selected">'.$i.'</option>'); 
	 }
	 else
	 {
         echo('<option value="'.$i.'">'.$i.'</option>'); 
	  }	 

} 

echo('</select>');  
?>
              a&ntilde;o
              <?php
	$anio_actual = date("Y"); 
    
	$anios_anteriores = $anio_actual-5;
	$anios_futuros= $anio_actual +5;

echo'<select name="fano">'; 

for($i=$anios_anteriores;$i<=$anios_futuros;$i++)
{ 
     if($i==$anio_X)
	 {
	     echo('<option value="'.$i.'" selected="selected">'.$i.'</option>'); 
	 }
	 else
	 {
         echo('<option value="'.$i.'">'.$i.'</option>'); 
	  }	

} 

echo('</select>');  
?>
            </span></label></td>
          </tr>
          <tr class="odd">
            <td><span class="Estilo3">Autor</span></td>
            <td><label>
              <input name="fautor" type="text" id="fautor" value="<?php echo $autor;?>" />
            </label></td>
          </tr>
          <tr class="odd">
            <td><span class="Estilo3">Titulo:</span></td>
            <td><label>
              <input name="ftitulo" type="text" id="ftitulo"  value="<?php echo $titulo;?>"/>
            </label></td>
          </tr>
          <tr class="odd">
            <td><span class="Estilo3">Breve:</span></td>
            <td><label>
              <textarea name="fbreve" id="fbreve"><?php echo $breve;?></textarea>
            </label></td>
          </tr>
          <tr class="odd">
            <td valign="top"><span class="Estilo3">Noticia</span></td>
            <td><label>
              <textarea name="txt_noticia" id="txt_noticia"><?php echo $noticia;?></textarea>
              </label>            </td>
          </tr>
          <tr class="odd">
            <td><span class="Estilo3">Imagen Actual
              <input name="imagen_actual" type="hidden" id="imagen_actual" value="<?php echo $imagen;?>" />
            </span></td>
            <td><img name="previa" src="<?php echo $ruta_img;?>" width="50" height="50" alt="img actual" /></td>
          </tr>
          <tr class="odd">
            <td class="Estilo3">Nva Imagen</td>
            <td><input type="file" name="imagen_nueva" id="imagen_nueva" /></td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="2"><div align="center">
              &nbsp;
              <label>
                <input type="reset" name="Submit2" value="Restablecer" />
                </label>
              &nbsp;
              <input type="submit" name="Submit" value="Continuar&gt;&gt;" />
            </div></td>
          </tr>
        </tfoot>
      </table>
    </div>
  </form>
</div>
</body>
</html>