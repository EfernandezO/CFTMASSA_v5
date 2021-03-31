<?php
//-----------------------------------------//
	require("../../seguridad.php");
	require("../../okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	
$continuar=false;
if(isset($_GET["id_archivo"]))
{
	$id_archivo=base64_decode($_GET["id_archivo"]);
	if(is_numeric($id_archivo)){ $continuar=true;}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>Okalis | Edicion Modulo</title>
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 120px;
}
#apDiv2 {
	position:absolute;
	width:60%;
	height:31px;
	z-index:2;
	left: 20%;
	top: 271px;
}
</style>
<script language="javascript" type="text/javascript">
function CONFIRMAR()
{
	c=confirm('Seguro(a) Que desea Modificar este Modulo...?');
	if(c){ document.getElementById('frm').submit();}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Modulos</h1>
<?php 
	require("../../../../funciones/conexion_v2.php");
	$id_archivo=mysqli_real_escape_string($conexion_mysqli, $id_archivo);
	if($continuar)
	{
		$cons="SELECT nombre_modulo, categoria FROM okalis_archivos WHERE id_archivo='$id_archivo' LIMIT 1";
		if(DEBUG){ echo"-->$cons<br>";}
		$sqli=$conexion_mysqli->query($cons) or die($conexion_mysqli->error);
			$OA=$sqli->fetch_assoc();
			
			$OA_nombre_modulo=$OA["nombre_modulo"];
			$OA_categoria=$OA["categoria"];
		$sqli->free();		
?>

<div id="apDiv1">
<form action="edicion_modulo_2.php" method="post" id="frm">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="2">Edicion de Modulo
        <input name="id_archivo" type="hidden" id="id_archivo" value="<?php echo $id_archivo;?>" /></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="10%">Nombre</td>
      <td width="90%"><label for="nombre_modulo"></label>
        <input type="text" name="nombre_modulo" id="nombre_modulo"  value="<?php echo $OA_nombre_modulo;?>"/></td>
    </tr>
    <tr>
      <td>Categoria</td>
      <td><label for="categoria"></label>
        <input type="text" name="categoria" id="categoria" value="<?php echo $OA_categoria;?>"/></td>
    </tr>
    </tbody>
  </table>
 </form> 
</div>
<div id="apDiv2"><a href="#" class="button_G" onclick="CONFIRMAR();">Modificar Modulo</a></div>
<?php }else{ echo"Sin Datos :(";}?>
</body>
</html>