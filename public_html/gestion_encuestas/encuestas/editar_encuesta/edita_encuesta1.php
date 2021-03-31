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
<title>Edicion de Encuesta</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 86px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:31px;
	z-index:2;
	left: 30%;
	top: 639px;
	text-align: center;
}
#apDiv3 {
	position:absolute;
	width:50%;
	height:31px;
	z-index:3;
	left: 5%;
	top: 674px;
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
	if(isset($_GET["id_encuesta"]))
	{ 
		$id_encuesta=$_GET["id_encuesta"];
		if(is_numeric($id_encuesta))
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
<h1 id="banner">Administrador - Edici&oacute;n de  Encuesta</h1>
<div id="link"><br />
<a href="../../gestion_encuesta.php" class="button">Volver a Encuestas</a><br />
</div>
<div id="apDiv1">
<?php
if($continuar)
{
	//datos pregunta
	require("../../../../funciones/conexion_v2.php");
		
		$cons="SELECT * FROM encuestas_main WHERE id_encuesta='$id_encuesta' LIMIT 1";
		$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
			$D=$sql->fetch_assoc();
			$nombre=$D["nombre"];
			$descripcion=$D["descripcion"];
			$visible_alumno=$D["visible_alumno"];
			$visible_exalumno=$D["visible_exalumno"];
			$visible_docente=$D["visible_docente"];
			$visible_jefe_carrera=$D["visible_jefe_carrera"];
			if(empty($visible_jefe_carrera)){$visible_jefe_carrera="off";}
			
			$utilizar_para_evaluacion_docente=$D["utilizar_para_evaluacion_docente"];
			$utilizar_para_evaluacion_JC_D=$D["utilizar_para_evaluacion_JC_D"];
			$utilizar_para_evaluacion_JC=$D["utilizar_para_evaluacion_JC"];
			$utilizar_para_autoevaluacion=$D["utilizar_para_autoevaluacion_docente"];
			
			
			if(empty($visible_alumno)){ $visible_alumno="off";}
			if(empty($visible_exalumno)){ $visible_exalumno="off";}
			if(empty($visible_docente)){ $visible_docente="off";}
		$sql->free();
	@mysql_close($conexion);
	$conexion_mysqli->close();
}
else
{
	if(DEBUG){ echo"Continuar: NO<br>";}
	else{ header("location: ../../index.php");}
}
?>
<form action="edita_encuesta2.php" method="post" enctype="multipart/form-data" id="frm">
<table width="60%" border="1" align="center">
<thead>
  <tr>
    <th colspan="3"> Encuesta Cod. <?php echo $id_encuesta;?>
      <input name="id_encuesta" type="hidden" id="id_encuesta" value="<?php echo $id_encuesta;?>" />
      </th>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td>Visible Alumno</td>
    <td><input type="radio" name="visible_alumno" id="visible_alumno" value="on" <?php if($visible_alumno=="on"){?>checked="checked"<?php }?> />
     Si</td>
    <td><input name="visible_alumno" type="radio" id="visible_alumno2" value="off" <?php if($visible_alumno=="off"){?>checked="checked"<?php }?> />
      no</td>
  </tr>
  <tr>
    <td>Visible Exalumno</td>
    <td><input type="radio" name="visible_exalumno" id="visible_exalumno3" value="on" <?php if($visible_exalumno=="on"){?>checked="checked"<?php }?> />
      Si</td>
    <td><input name="visible_exalumno" type="radio" id="visible_exalumno4" value="off" <?php if($visible_exalumno=="off"){?>checked="checked"<?php }?> />
      no</td>
  </tr>
  <tr>
    <td>Visible Jefe Carrera</td>
    <td><input type="radio" name="visible_jefe_carrera" id="visible_jefe_carrera" value="on" <?php if($visible_jefe_carrera=="on"){?>checked="checked"<?php }?>/>
      Si</td>
    <td><input name="visible_jefe_carrera" type="radio" id="visible_jefe_carrera" value="off" <?php if($visible_jefe_carrera=="off"){?>checked="checked"<?php }?> />
      no</td>
  </tr>
  <tr>
    <td>Visible Docentes</td>
    <td><input type="radio" name="visible_docente" id="visible_docente" value="on" <?php if($visible_docente=="on"){?>checked="checked"<?php }?>/>
      Si</td>
    <td><input name="visible_docente" type="radio" id="visible_docente" value="off" <?php if($visible_docente=="off"){?>checked="checked"<?php }?> />
      no</td>
  </tr>
  <tr>
    <td>utilizar para evaluacion Docente</td>
    <td><input name="utilizar_para_evaluacion_docente" type="radio" id="utilizar_para_evaluacion_docente" value="1" <?php if($utilizar_para_evaluacion_docente=="1"){ echo'checked="checked"';}?>/>
      <label for="utilizar_para_evaluacion_docente">Si</label></td>
    <td><input name="utilizar_para_evaluacion_docente" type="radio" id="utilizar_para_evaluacion_docente2" value="0" <?php if($utilizar_para_evaluacion_docente!=="1"){ echo'checked="checked"';}?>/> 
      no</td>
  </tr>
  <tr>
    <td>Utilizar para evaluacion de Jefe carrera</td>
    <td><input type="radio" name="utilizar_JC" id="radio" value="1"  <?php if($utilizar_para_evaluacion_JC =="1"){ echo'checked="checked"';}?>/>
      <label for="utilizar_para_autoevaluacion_docente">Si</label></td>
    <td><input type="radio" name="utilizar_JC" id="radio2" value="0" <?php if($utilizar_para_evaluacion_JC !=="1"){ echo'checked="checked"';}?>/>
      no</td>
  </tr>
  <tr>
    <td>Utilizar para evaluacion de Jefe carrera -&gt; Docente</td>
    <td><input type="radio" name="utilizar_jefecarrera_docente" id="radio" value="1"  <?php if($utilizar_para_evaluacion_JC_D =="1"){ echo'checked="checked"';}?>/>
      <label for="utilizar_para_autoevaluacion_docente">Si</label></td>
    <td><input type="radio" name="utilizar_jefecarrera_docente" id="radio2" value="0" <?php if($utilizar_para_evaluacion_JC_D !=="1"){ echo'checked="checked"';}?>/>
      no</td>
  </tr>
  <tr>
    <td>Utilizar para autoevaluacion docente</td>
    <td><p>
      <input type="radio" name="utilizar_para_autoevaluacion_docente" id="radio3" value="1" <?php if($utilizar_para_autoevaluacion_docente =="1"){ echo'checked="checked"';}?>/>
      Si</p></td>
    <td><input type="radio" name="utilizar_para_autoevaluacion_docente" id="radio4" value="0" <?php if($utilizar_para_autoevaluacion_docente !=="1"){ echo'checked="checked"';}?>/>
      no</td>
  </tr>
  <tr>
    <td width="31%">Nombre</td>
    <td width="69" colspan="2">
      <input name="nombre" type="text" id="nombre" value="<?php echo $nombre;?>" size="50"/></td>
  </tr>
  <tr>
    <td>Descripcion</td>
    <td colspan="2"><label for="descripcion"></label>
      <textarea name="descripcion" cols="50" rows="5" id="descripcion"><?php echo $descripcion; ?></textarea></td>
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