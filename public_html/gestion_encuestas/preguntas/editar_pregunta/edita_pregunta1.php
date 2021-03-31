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
<title>Edicion de Malla</title>
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
	top: 507px;
	text-align: center;
}
#apDiv3 {
	position:absolute;
	width:50%;
	height:31px;
	z-index:3;
	left: 5%;
	top: 542px;
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
	if((isset($_GET["id_encuesta"]))and(isset($_GET["id_pregunta"])))
	{ 
		$id_encuesta=$_GET["id_encuesta"]; $id_pregunta=$_GET["id_pregunta"];
		if((is_numeric($id_encuesta))and(is_numeric($id_pregunta)))
		{ $continuar=true;}
		else
		{ $continuar=false;}
	}
	else{ $continuar=false;}
}
else
{ $continuar=false;}
?>
<body>
<h1 id="banner">Administrador - Edici&oacute;n de Pregunta, Encuesta</h1>
<div id="link"><br />
<a href="../ver_preguntas.php?id_encuesta=<?php echo $id_encuesta;?>" class="button">Volver a Preguntas</a><br />
</div>
<div id="apDiv1">
<?php
if($continuar)
{
	////
	$array_tipos=array("alternativa", "directa");
	//datos pregunta
	require("../../../../funciones/conexion_v2.php");
		
		$cons="SELECT * FROM encuestas_pregunta WHERE id_pregunta='$id_pregunta' AND id_encuesta='$id_encuesta' LIMIT 1";
		$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
			$D=$sql->fetch_assoc();
			$pregunta=$D["pregunta"];
			$tipo_pregunta=$D["tipo"];
			$posicion=$D["posicion"];
		$sql->free();
	@mysql_close($conexion);
	$conexion_mysqli->close();
}
else
{
	if(DEBUG){ echo"Continuar: NO<br>";}
	else{ header("location: ../ver_preguntas.php?id_encuesta=$id_encuesta");}
}
?>
<form action="edita_pregunta2.php" method="post" enctype="multipart/form-data" id="frm">
<table width="50%" border="1" align="center">
<thead>
  <tr>
    <th colspan="3">Pregunta cod <?php echo $id_pregunta; ?> de la Encuesta Cod. <?php echo $id_encuesta;?>
      <input name="id_encuesta" type="hidden" id="id_encuesta" value="<?php echo $id_encuesta;?>" />
      <input name="id_pregunta" type="hidden" id="id_pregunta" value="<?php echo $id_pregunta; ?>" /></th>
  </tr>
  <tr>
    <td>Posicion</td>
    <td colspan="2"><label for="posicion"></label>
      <select name="posicion" id="posicion">
      <?php
      for($x=1;$x<=100;$x++)
	  {
		  if($x==$posicion){ $select='selected="selected"';}
		  else{ $select='';}
		  
		  echo'<option value="'.$x.'" '.$select.'>'.$x.'</option>';
	  }
	  ?>
      </select></td>
  </tr>
  <tr>
    <td width="246">tipo</td>
    <td colspan="2"><select name="tipo" id="tipo">
      <?php
      foreach($array_tipos as $n =>$valor)
	  {
		  if($tipo_pregunta==$valor)
		  { echo'<option value="'.$valor.'" selected="selected">'.$valor.'</option>';}
		  else
		  { echo'<option value="'.$valor.'">'.$valor.'</option>';}
	  }
	  ?>
    </select></td>
    </tr>
  <tr>
    <td>quitar &lt;p&gt;&lt;/p&gt; de inicio y fin</td>
    <td width="95"><input type="radio" name="quitar_p" id="radio" value="si" />
      <label for="quitar_p"></label>
      si</td>
    <td width="105"><input name="quitar_p" type="radio" id="radio2" value="no" checked="checked" />
      no</td>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td colspan="3">Pregunta</td>
  </tr>
  <tr>
    <td colspan="3"><textarea name="pregunta" cols="50" id="pregunta"><?php echo $pregunta;?></textarea></td>
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