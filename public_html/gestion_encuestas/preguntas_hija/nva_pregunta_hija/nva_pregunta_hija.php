<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Gestion de Encuestas_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_GET)
{ $id_encuesta=$_GET["id_encuesta"]; $id_pregunta=$_GET["id_pregunta"];}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<title>Agrega Pregunta | Encuesta</title>
<script type="text/javascript" src="../../../libreria_publica/tinymce_4.0b2/tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({
    selector: "textarea",
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
	force_br_newlines : true,
	force_p_newlines : false
});
</script>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:90%;
	height:80px;
	z-index:1;
	left: 5%;
	top: 95px;
}
#Layer2 {
	position:absolute;
	width:90%;
	height:130px;
	z-index:2;
	left: 5%;
	top: 250px;
}
#Layer3 {
	position:absolute;
	width:218px;
	height:48px;
	z-index:2;
	left: 39px;
	top: 16px;
}
#Layer4 {
	position:absolute;
	width:98px;
	height:31px;
	z-index:3;
	left: 292px;
	top: 32px;
}
-->
</style>
<script language="javascript">
function CONFIRMAR()
{
	c=confirm('Seguro(a) desea Ingresar esta pregunta hija...?');
	if(c==true)
	{
		document.frm.submit();
	}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Nueva Pregunta hija, Encuesta</h1>
<div id="link"><br />
<a href="../ver_preguntas_hijas.php?id_encuesta=<?php echo $id_encuesta;?>&id_pregunta=<?php echo $id_pregunta;?>" class="button">Volver a Seleccion</a></div>
<div id="Layer1">
<form action="nva_pregunta_hija2.php" method="post" name="frm" id="frm">
  <table width="60%" border="0" align="center">
  <thead>
    <tr>
      <th colspan="3">Nueva Pregunta hija
        <input name="id_encuesta" type="hidden" id="id_encuesta" value="<?php echo $id_encuesta;?>" />
        <input name="id_pregunta" type="hidden" id="id_pregunta" value="<?php echo $id_pregunta;?>" /></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="39%">Encuesta</td>
      <td width="61%" colspan="2"><?php echo $id_encuesta;?></td>
      </tr>
    <tr>
      <td>Pregunta</td>
      <td colspan="2"><?php echo $id_pregunta
;?></td>
    </tr>
    <tr>
      <td>quitar &lt;p&gt;&lt;/p&gt; de inicio y fin</td>
      <td><input name="quitar_p" type="radio" id="radio" value="si" checked="checked" />
        <label for="quitar_p"></label>
si</td>
      <td><input name="quitar_p" type="radio" id="radio2" value="no" />
        No</td>
    </tr>
    <tr>
      <td>habilitar respuesta  anexa</td>
      <td><input type="radio" name="respuesta_anexa" id="radio3" value="1" />
        <label for="respuesta_anexa">Si</label></td>
      <td><input name="respuesta_anexa" type="radio" id="radio4" value="0" checked="checked" />
        No</td>
    </tr>
    <tr>
      <td colspan="3">Contenido</td>
      </tr>
    <tr>
      <td colspan="3"><label for="contenido"></label>
        <textarea name="contenido" id="contenido"></textarea></td>
      </tr>
    <tr>
      <td colspan="3"><label>
        <input type="button" name="Submit" value="Continuar"  onclick="CONFIRMAR();"/>
        </label></td>
    </tr>
    </tbody>
  </table>
  </form>
</div>
</body>
</html>
