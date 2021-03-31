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
{ $id_encuesta=$_GET["id_encuesta"];}
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
	language : "es"
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
	c=confirm('Seguro(a) desea Ingresar esta pregunta...?');
	if(c==true)
	{
		document.frm.submit();
	}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Nueva Pregunta de Encuesta</h1>
<div id="link"><br />
<a href="../ver_preguntas.php?id_encuesta=<?php echo $id_encuesta;?>" class="button">Volver a Seleccion</a></div>
<div id="Layer1">
<form action="nva_pregunta2.php" method="post" name="frm" id="frm">
  <table width="60%" border="0" align="center">
  <thead>
    <tr>
      <th colspan="3">Nueva Pregunta
        <input name="id_encuesta" type="hidden" id="id_encuesta" value="<?php echo $id_encuesta;?>" /></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="215">Encuesta</td>
      <td colspan="2"><?php echo $id_encuesta;?></td>
      </tr>
    <tr>
      <td>Posicion</td>
      <td colspan="2"><label for="posicion"></label>
        <select name="posicion" id="posicion">
          <?php
      for($x=1;$x<=100;$x++)
	  {
		  
		  echo'<option value="'.$x.'">'.$x.'</option>';
	  }
	  ?>
        </select></td>
    </tr>
    <tr>
      <td>Tipo</td>
      <td colspan="2"><select name="tipo" id="tipo">
        <option value="alternativa" selected="selected">alternativa</option>
        <option value="directa">directa</option>
      </select></td>
      </tr>
    <tr>
      <td>quitar &lt;p&gt;&lt;/p&gt; de inicio y fin</td>
      <td width="75"><input type="radio" name="quitar_p" id="radio" value="si" />
        <label for="quitar_p"></label>
        si</td>
      <td width="264"><input name="quitar_p" type="radio" id="radio2" value="no" checked="checked" />
        no</td>
    </tr>
    <tr>
      <td colspan="3">Pregunta
        <label for="pregunta"></label></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><textarea name="pregunta" id="pregunta"></textarea></td>
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
