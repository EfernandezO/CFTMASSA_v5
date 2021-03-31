<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Genera_honorario_1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if(DEBUG){ var_dump($_GET);}

if($_GET)
{
	if(isset($_GET["id_funcionario"]))
	{$id_funcionario=base64_decode($_GET["id_funcionario"]);}
	else{$id_funcionario=0;}
	
	if(($id_funcionario>0)and(is_numeric($id_funcionario))){$continuar_1=true;}
	else{ $continuar_1=false;}
	
	if(isset($_GET["indice"]))
	{$indice=base64_decode($_GET["indice"]);}
	else
	{ $indice=0;}
	
	if(($indice>0)and(is_numeric($indice))){ $continuar_2=true;}
	else{ $continuar_2=false;}
	
	if(isset($_GET["tipo"]))
	{$tipo=$_GET["tipo"];}
	else{ $tipo="";}
	
	switch($tipo)
	{
		case"cargos":
			$continuar_3=true;
			$aux_valor=$_SESSION["HONORARIO"][$id_funcionario]["asignaciones"][$indice]["cargo"];
			$aux_glosa=$_SESSION["HONORARIO"][$id_funcionario]["asignaciones"][$indice]["glosa_cargo"];
			break;
		case"abonos":
			$continuar_3=true;
			$aux_valor=$_SESSION["HONORARIO"][$id_funcionario]["asignaciones"][$indice]["abono"];
			$aux_glosa=$_SESSION["HONORARIO"][$id_funcionario]["asignaciones"][$indice]["glosa_abono"];
			break;
		default:
				$continuar_3=false;	
	}
}
else
{
	$continuar_1=false;
	$continuar_2=false;
	$continuar_3=false;
}	
$action="#";	
	
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>Documento sin título</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 69px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:26px;
	z-index:2;
	left: 30%;
	top: 244px;
	text-align: center;
}
</style>
<script language="javascript">
function CONFIRMAR()
{
	valor=document.getElementById('valor').value;
	glosa=document.getElementById('glosa').value;
	continuar=true;
	
	if((valor=="")||(valor==" "))
	{
		alert('Ingrese Valor');
		continuar=false;
	}
	
	if(continuar)
	{
		c=confirm('zSeguro(a) Desea Aplicar estos Valores?');
		if(c){document.getElementById('frm').submit();}
	}
}
</script>
</head>

<body>
<h1 id="banner">Honorarios - Cargos Abonos V 2.0</h1>
<div id="apDiv1">
<?php
if($continuar_1 and $continuar_2 and $continuar_3)
{ $action="cargos_abonos_server_2.php";
	
?>
<form method="post" id="frm" action="<?php echo $action;?>">
	<table width="70%" border="1" align="center">
    <thead>
  <tr>
    <th colspan="2">Realizacion de <?php echo $tipo;?>
      <input name="tipo" type="hidden" id="tipo" value="<?php echo $tipo;?>" />
      <input type="hidden" name="indice" id="indice" value="<?php echo $indice;?>"/>
      <input type="hidden" name="id_funcionario" id="id_funcionario" value="<?php echo $id_funcionario;?>"/></th>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td width="11%">cantidad horas</td>
    <td width="89%"><label for="valor"></label>
      <input type="text" name="valor" id="valor"  value="<?php echo $aux_valor;?>"/></td>
  </tr>
  <tr>
    <td>Glosa</td>
    <td><label for="glosa"></label>
      <input type="text" name="glosa" id="glosa"  value="<?php echo $aux_glosa;?>"/></td>
  </tr>
  </tbody>
</table>
</form>
<?php	
}
else
{}
?>
</div>
<div id="apDiv2"><a href="#" class="button_G" onclick="CONFIRMAR();">Aplicar</a></div>
</body>
</html>