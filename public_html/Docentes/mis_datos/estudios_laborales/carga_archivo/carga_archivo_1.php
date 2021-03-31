<?php
//--------------CLASS_okalis------------------//
require("../../../../OKALIS/class_OKALIS_v1.php");
define("DEBUG", false);
$O=new OKALIS();
$O->DEBUG=DEBUG;
$O->ruta_conexion="../../../../../funciones/";
$O->clave_del_archivo=md5("Docentes->estudioTrabajo");
$O->PERMITIR_ACCESO_USUARIO();
if(isset($_GET["E_id"]))
{
	$E_id=base64_decode($_GET["E_id"]);
	$id_funcionario=base64_decode($_GET["id_funcionario"]);
	if((is_numeric($E_id))and($E_id>0))
	{$continuar=true;}
	else
	{$continuar=false;}
}
else
{ $continuar=false;}
//---------------------------------------------------------//
if($continuar)
{
	$path="../../../../CONTENEDOR_GLOBAL/docente_estudios/";
	require("../../../../../funciones/conexion_v2.php");
	
	$cons="SELECT archivo FROM personal_registro_estudios WHERE id='$E_id' LIMIT 1";
	$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		$PRE=$sqli->fetch_assoc();
		$ARCHIVO_ACTUALX=$PRE["archivo"];
		
		if((empty($ARCHIVO_ACTUALX))or($ARCHIVO_ACTUALX=="NULL")){ $archivo_actual='Sin Archivo';}
		else{ $archivo_actual='<a href="'.$path.''.$ARCHIVO_ACTUALX.'" target="_blank">'.$ARCHIVO_ACTUALX.'</a>';}
		
	$sqli->free();
	$conexion_mysqli->close();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../../funciones/codificacion.php");?>
<title>Carga Archivo Estudios</title>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
 <link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:185px;
	z-index:1;
	left: 5%;
	top: 124px;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:22px;
	z-index:2;
	left: 5%;
	top: 325px;
	text-align: center;
}
#apDiv3 {
	position:absolute;
	width:90%;
	height:22px;
	z-index:3;
	left: 5%;
	top: 373px;
}
</style>
<script language="javascript">
function CONFIRMAR()
{
	archivo=document.getElementById('archivo').value;
	continuar=true;
	
	if((archivo=="")||(archivo==" "))
	{
		continuar=false;
		alert("No se ha seleccionado ningun archivo");
	}
	
	if(continuar)
	{
		c=confirm('Seguro(a) desea Cargar este archivo  ?');
		if(c){ document.getElementById('frm').submit();}
	}
}
</script>
</head>

<body>
<h1 id="banner">Docentes - Estudios</h1>
<div id="apDiv1">
<?php if($continuar){?>
<form action="carga_archivo_2.php" method="post" enctype="multipart/form-data" id="frm">
  <table width="40%" border="1" align="center">
  <thead>
    <tr>
      <th colspan="2">Archivos de Estudio</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="40%">Archivo Actual</td>
      <td width="60%"><?php echo $archivo_actual;?>
        <input name="E_id" type="hidden" id="E_id" value="<?php echo $E_id;?>" />
        <input name="id_funcionario" type="hidden" id="id_funcionario" value="<?php echo $id_funcionario;?>" /></td>
    </tr>
    <tr>
      <td>Nuevo Archivo</td>
      <td><label for="archivo"></label>
        <input type="file" name="archivo" id="archivo" /></td>
    </tr>
    <tr>
      <td>formatos aceptados(&quot;jpg&quot;, &quot;jpeg&quot;, &quot;png&quot;, &quot;gif&quot;)</td>
      <td>Para Restaurar Imagen Click <a href="restaurar_imagen.php?E_id=<?php echo base64_encode($E_id);?>&id_funcionario=<?php echo base64_encode($id_funcionario);?>" class="button">aqui</a> (dejara en blanco la imagen)</td>
    </tr>
    </tbody>
  </table>
  </form>
  <?php }?>
</div>
<div id="apDiv2"><a href="#" class="button_G" onclick="CONFIRMAR();">Grabar</a></div>
<div id="apDiv3"><?php
	if(isset($_GET["error"]))
	{
		$error=$_GET["error"];
		$img_ok='<img src="../../BAses/Images/ok.png" width="29" height="26" alt="ok" />';
		$img_error='<img src="../../BAses/Images/b_drop.png" width="16" height="16" alt="X" />';
		switch($error)
		{
			case"C1":
				$msj="Fallo al intentar subir archivo, pruebe mas tarde...";
				$img=$img_error;
				break;	
			case"C2":
				$msj="Formato de archivo incompatible o peso superior a 10Mb...";
				$img=$img_error;
				break;	
			default:
				$msj="";
				$img='';
				break;			
		}
		
		echo $msj." ".$img;
	}?>
	</div>
</body>
</html>