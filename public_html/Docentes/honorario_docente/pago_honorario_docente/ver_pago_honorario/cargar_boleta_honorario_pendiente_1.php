<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("pago_honorario_docente_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

if(DEBUG){ var_dump($_GET);}
if(isset($_GET["id_funcionario"]))
{
	$id_funcionario=base64_decode($_GET["id_funcionario"]);
	if(is_numeric($id_funcionario)){$continuar_1=true;}
	else{$continuar_1=false; if(DEBUG){ echo"id_funcionario incorrecto";}}
}
else
{$continuar_1=false; if(DEBUG){ echo"no llego id_funcionario<br>";}}

if(isset($_GET["id_honorario"]))
{
	$id_honorario=base64_decode($_GET["id_honorario"]);
	if(is_numeric($id_honorario)){$continuar_2=true;}
	else{$continuar_2=false; if(DEBUG){ echo"id_honorario error<br>";}}
}
else
{$continuar_2=false; if(DEBUG){ echo"No llego id_honorario<br>";}}

if(isset($_GET["id_pago_honorario"]))
{
	$id_pago_honorario=base64_decode($_GET["id_pago_honorario"]);
	if(is_numeric($id_pago_honorario)){$continuar_3=true;}
	else{$continuar_3=false; if(DEBUG){ echo"id_pago_honorario error<br>";}}
}
else
{$continuar_3=false; if(DEBUG){ echo"No llego id_pago_honorario<br>";}}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../../funciones/codificacion.php"); ?>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css">
<title>Untitled Document</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:81px;
	z-index:1;
	left: 5%;
	top: 107px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:47px;
	z-index:2;
	left: 30%;
	top: 231px;
	text-align:center;
}
</style>
<script language="javascript">
function CONFIRMAR()
{
	c=confirm('Seguro(a) desea Cargar esta Boleta');
	if(c){ document.getElementById('frm').submit();}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Carga Boleta Honorario Docente</h1>
<div id="apDiv1">
<?php if($continuar_1 and $continuar_2 and $continuar_3){?>
<form action="cargar_boleta_honorario_pendiente_2.php" method="post" enctype="multipart/form-data" id="frm">
  <table width="200" border="1" align="center">
  <thead>
    <tr>
      <th colspan="2"><input name="id_pago_honorario" type="hidden" id="id_pago_honorario" value="<?php echo $id_pago_honorario;?>" />
        <input name="id_funcionario" type="hidden" id="id_funcionario" value="<?php echo $id_funcionario;?>" />
        <input name="id_honorario" type="hidden" id="id_honorario" value="<?php echo $id_honorario;?>" />
        Carga de Boleta Docente</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="80">Archivo</td>
      <td width="104"><label for="archivo"></label>
      <input type="file" name="archivo" id="archivo" /></td>
    </tr>
    </tbody>
  </table>
  </form>
  <?php }else{ echo"No hay datos<br>";}?>
</div>
<div id="apDiv2"><a href="#" class="button_G" onclick="CONFIRMAR();">Cargar Boleta</a></div>
</body>
</html>