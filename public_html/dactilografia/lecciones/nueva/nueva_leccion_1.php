<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define("DEBUG", true);
?>
<?php
	$array_clasificacion=array("A"=>"muy dificil","B"=>"dificil","C"=>"moderado","D"=>"facil","E"=>"muy facil");
	$array_minutos=array(1,5,10,15,20,25,30,35,40,45);
	$array_segundos=array(0,5,10,15,20,25,30,35,40,45,50,55);
	$array_exigencia=array(20,25,30,35,40,45,50,55,60,65,70,75);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<title>Nueva Leccion</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 140px;
}
</style>
<!-- TinyMCE -->
<script type="text/javascript" src="../../../libreria_publica/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "simple"
	});
</script>
<!-- /TinyMCE -->
<script language="javascript">
function CONFIRMAR()
{
	c=confirm('Seguro(a) Desea Agregar esta Leccion...?');
	if(c)
	{
		document.getElementById('frm').submit();
	}
}
</script>
</head>
<body>
<h1 id="banner">Dactilografia - Nueva Lecci&oacute;n</h1>
<br />
<div id="link"><a href="../lecciones_main.php" class="button">Volver</a></div>
  <div id="apDiv1">
  <form action="nueva_leccion_2.php" method="post" enctype="multipart/form-data" id="frm">
    <table width="60%" border="1" align="center">
    <thead>
      <tr>
        <th colspan="2">Datos Nueva Leccion</th>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td width="53%">Titulo</td>
        <td width="47%"><label for="titulo"></label>
          <input type="text" name="titulo" id="titulo" /></td>
      </tr>
      <tr>
        <td>Descripcion</td>
        <td><label for="descripcion"></label>
          <input type="text" name="descripcion" id="descripcion" /></td>
      </tr>
      <tr>
        <td>Clasificacion</td>
        <td><label for="clasificacion"></label>
          <select name="clasificacion" id="clasificacion">
          <?php
		  	foreach($array_clasificacion as $n =>$valor)
			{
				echo'<option value="'.$n.'">'.$valor.'</option>';
			}
          ?>
          </select></td>
      </tr>
      <tr>
        <td>Tiempo Limite</td>
        <td><label for="minutos"></label>
          <select name="minutos" id="minutos">
           <?php
		  	foreach($array_minutos as $nm =>$valorm)
			{
				echo'<option value="'.$valorm.'">'.$valorm.'</option>';
			}
          ?>
          </select>
          :
          <label for="segundos"></label>
          <select name="segundos" id="segundos">
           <?php
		  	foreach($array_segundos as $ns =>$valors)
			{
				echo'<option value="'.$valors.'">'.$valors.'</option>';
			}
          ?>
          </select></td>
      </tr>
      <tr>
        <td>nivel exigencia</td>
        <td><label for="nivel_exigencia"></label>
          <select name="nivel_exigencia" id="nivel_exigencia">
           <?php
		  	foreach($array_exigencia as $ne =>$valore)
			{
				echo'<option value="'.$valore.'">'.$valore.'</option>';
			}
          ?>
          </select></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2">Texto (Utilice shift+Enter para solo saltos de Linea.)</td>
      </tr>
      <tr>
        <td colspan="2"><label for="texto"></label>
          <textarea name="texto" id="texto" cols="70" rows="8"></textarea></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="button" name="continuar" id="continuar" value="Grabar"  onclick="CONFIRMAR();"/></td>
      </tr>
      </tbody>
    </table>
    </form>
</div>
</body>
</html>