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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>Edicion de respuestas</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
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
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 114px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:31px;
	z-index:2;
	left: 30%;
	top: 509px;
	text-align: center;
}
#apDiv3 {
	position:absolute;
	width:50%;
	height:31px;
	z-index:3;
	left: 5%;
	top: 544px;
}
</style>
<script language="javascript">
function CONFIRMAR()
{
	c=confirm('Seguro(a) desea Guardar los Cambios...?');
	if(c)
	{document.getElementById('frm').submit();}
}
</script>
</head>
<?php
if($_GET)
{
	
	if((isset($_GET["id_encuesta"]))and(isset($_GET["id_pregunta"]))and(isset($_GET["id_pregunta_hija"])))
	{
		$id_encuesta=$_GET["id_encuesta"];
		$id_pregunta=$_GET["id_pregunta"];
		$id_pregunta_hija=$_GET["id_pregunta_hija"];
		
		if((is_numeric($id_encuesta))and(is_numeric($id_pregunta))and(is_numeric($id_pregunta_hija)))
		{ $continuar=true;}
		else
		{ $continuar=false;}
	}
	else
	{ $continuar=false;}
}
else
{ $continuar=false;}
?>
<body>
<h1 id="banner">Administrador - Alternativa, Encuesta</h1>
<div id="link"><br />
<a href="../ver_preguntas_hijas.php?id_encuesta=<?php echo $id_encuesta?>&id_pregunta=<?php echo $id_pregunta;?>" class="button">Volver a Alternativas</a><br />
</div>
<div id="apDiv1">
<?php
if($continuar)
{
	////
	$array_tipos=array("alternativa", "directa");
	//datos pregunta
	require("../../../../funciones/conexion_v2.php");
		
		//numero de preguntas que van
		$consN="SELECT COUNT(id_pregunta_hija) FROM encuestas_pregunta_hija WHERE id_encuesta='$id_encuesta' AND id_pregunta='$id_pregunta'";
		$sqliN=$conexion_mysqli->query($consN);
		$NP=$sqliN->fetch_row();
		$numeroPreguntas=$NP[0];
		if(empty($numeroPreguntas)){$numeroPreguntas=0;}
		$sqliN->free();
		
		$cons="SELECT posicion, contenido, respuesta_anexa FROM encuestas_pregunta_hija WHERE id_pregunta_hija='$id_pregunta_hija' AND id_encuesta='$id_encuesta' AND id_pregunta='$id_pregunta' LIMIT 1";
		if(DEBUG){ echo"--> $cons<br>";}
		$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
			$D=$sql->fetch_assoc();
			$contenido=$D["contenido"];
			$posicionActual=$D["posicion"];
			$respuesta_anexa=$D["respuesta_anexa"];
			if(DEBUG){echo"----------------> $contenido<br>";}
		$sql->free();
	@mysql_close($conexion);
	$conexion_mysqli->close();
}
?>
<form action="edita_pregunta_hija2.php" method="post" enctype="multipart/form-data" id="frm">
<table width="50%" border="1" align="center">
<thead>
  <tr>
    <th colspan="3">Pregunta cod <?php echo $id_pregunta; ?> de la Encuesta Cod. <?php echo $id_encuesta;?>
      <input name="id_encuesta" type="hidden" id="id_encuesta" value="<?php echo $id_encuesta;?>" />
      <input name="id_pregunta" type="hidden" id="id_pregunta" value="<?php echo $id_pregunta; ?>" />
      <input name="id_pregunta_hija" type="hidden" id="id_pregunta_hija" value="<?php echo $id_pregunta_hija; ?>" />
      </th>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td colspan="3">Contenido</td>
    </tr>
  <tr>
    <td>Posicion</td>
    <td colspan="2"><label for="posicion"></label>
      <select name="posicion" id="posicion">
      <?php
	  for($x=1;$x<=$numeroPreguntas;$x++){
		  if($x==$posicionActual){$check='selected="selected"';}
		  else{$check='';}
		  echo'<option value="'.$x.'" '.$check.'>'.$x.'</option>';
	  }
      ?>
      </select></td>
    </tr>
  <tr>
    <td>habilitar respuesta  anexa</td>
    <td><input type="radio" name="respuesta_anexa" id="radio3" value="1"  <?php if($respuesta_anexa=="1"){ echo'checked="checked"';}?>/>
      <label for="respuesta_anexa">Si</label></td>
    <td><input name="respuesta_anexa" type="radio" id="radio4" value="0" <?php if($respuesta_anexa=="0"){ echo'checked="checked"';}?>/>
      No</td>
  </tr>
  <tr>
    <td>quitar &lt;p&gt;&lt;/p&gt; de inicio y fin</td>
    <td><input type="radio" name="quitar_p" id="radio" value="si" checked="checked"/>
      <label for="quitar_p"></label>
      si</td>
    <td><input name="quitar_p" type="radio" id="radio2" value="no"  />
      no</td>
  </tr>
  <tr>
    <td colspan="3"><textarea name="contenido" cols="50" id="contenido"><?php echo $contenido;?></textarea></td>
  </tr>
  </tbody>    
</table>
</form>
</div>
<div id="apDiv2"><a href="#" class="button_G" onclick="CONFIRMAR();">Guardar Cambios</a></div>
<div id="apDiv3">
<?php
if(isset($_GET["error"]))
{
	$error=$_GET["error"];
	$msj="";
	switch($error)
	{
		case"M1":
			$msj="Codigo para el Ramo invalido, debe ser numerico y no ser utilizado por otro ramo...";
			break;
		case"M2":
			$msj="Ramo Incorrecto, no debe estar en blanco ni en uso por otro ramo...";
			break;
	}
	
	echo"$msj";
}
?>
</div>
</body>
</html>