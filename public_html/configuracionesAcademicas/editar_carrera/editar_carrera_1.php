<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Gestion_carreras_Mallas->MAIN_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

$continuar=false;
if(isset($_GET["id_carrera"]))
{
	$id_carrera=$_GET["id_carrera"];
	if(is_numeric($id_carrera)){ $continuar=true;}
}

if($continuar)
{
	require("../../../funciones/conexion_v2.php");
	$id_carrera=mysqli_real_escape_string($conexion_mysqli, $id_carrera);
	$cons_C="SELECT * FROM carrera WHERE id='$id_carrera' LIMIT 1";
	if(DEBUG){ echo"---> $cons_C<br>";}
	$sqli_C=$conexion_mysqli->query($cons_C)or die($conexion_mysqli->error);
	$DC=$sqli_C->fetch_assoc();
		$C_nombre=$DC["carrera"];
		$C_nombre_titulo=$DC["nombre_titulo"];
	$sqli_C->free();
	$conexion_mysqli->close();	
}
else{ if(DEBUG){ echo"Sin Continuar<br>";}}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<title>Edita Carrera</title>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 115px;
}
#Layer2 {
	position:absolute;
	width:437px;
	height:115px;
	z-index:2;
	left: 40px;
	top: 288px;
}
#Layer3 {
	position:absolute;
	width:97px;
	height:25px;
	z-index:2;
	left: 387px;
	top: 79px;
}
.Estilo2 {color: #0080C0}
#Layer4 {
	position:absolute;
	width:281px;
	height:67px;
	z-index:3;
	top: 15px;
	left: 47px;
}
#Layer5 {
	position:absolute;
	width:94px;
	height:20px;
	z-index:4;
	left: 286px;
	top: 81px;
}
-->
</style>
<script language="javascript">
function Confirmar()
{
	c=confirm('¿Esta Seguro(a) que Desea Modificar está Carrera?');
	if(c==true)
	{
		document.frm.submit();
	}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Men&uacute; Carreras </h1>
<div id="link"><br />
<a href="../index.php" class="button">Volver al Menu</a></div>
<div id="Layer1">
  <form action="editar_carrera_2.php" method="post" name="frm" id="frm">
  <table width="50%" border="0" align="center">
  <thead>
    <tr>
      <th colspan="2" ><div align="center"><strong> Edita Carrera</strong>
        <input name="id_carrera" type="hidden" id="id_carrera" value="<?php echo $id_carrera;?>" />
      </div></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td ><strong>Nombre de Carrera </strong></td>
      <td ><input name="nombre_carrera" type="text" id="nombre_carrera" value="<?php echo $C_nombre;?>" size="40" /></td>
    </tr>
    <tr>
      <td width="149" >Nombre de Titulo </td>
      <td width="307" ><label for="nombre_titulo"></label>
        <input name="nombre_titulo" type="text" id="nombre_titulo" value="<?php echo $C_nombre_titulo;?>" size="40" /></td>
    </tr>
    <tr>
      <td colspan="2" ><div align="center">
        <input type="button" name="Submit" value="Modificar" onclick="Confirmar();" />
        </div></td>
    </tr>
    </tbody>
  </table>
  </form>
</div>
</body>
</html>
