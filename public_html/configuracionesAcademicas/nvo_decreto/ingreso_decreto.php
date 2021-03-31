<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Gestion_carreras_Mallas->DECRETOS_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<title>Graba decreto</title>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:200px;
	height:88px;
	z-index:1;
	left: 51px;
	top: 94px;
}
#Layer3 {	position:absolute;
	width:218px;
	height:48px;
	z-index:2;
	left: 39px;
	top: 16px;
}
#Layer2 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:3;
	left: 5%;
	top: 89px;
}
#Layer4 {
	position:absolute;
	width:113px;
	height:30px;
	z-index:3;
	left: 265px;
	top: 18px;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: underline;
}
a:active {
	text-decoration: none;
}
-->
</style>
<script language="javascript">
function Confirmar()
{
	c=confirm('¿ Desea Guardar este Decreto ?');
	if(c==true)
	{
		document.frmX.submit();
	}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Decretos</h1>
<div id="link"><br />
<a href="../index.php" class="button">Volver al Men&uacute; </a>
</div>
<?php
if($_GET)
{
	$id_carrera=$_GET["id_carrera"];
	$sede=$_GET["sede"];
	include("../../../funciones/conexion.php");
	//include("../../../funciones/funcion.php");
	$opcion="";
	
	$cons="SELECT decreto FROM certificados WHERE id_carrera='$id_carrera' AND sede='$sede'";
	if(DEBUG){ echo"$cons<br>";}
	$sql=mysql_query($cons)or die("Error:".mysql_error());
	$num_reg=mysql_num_rows($sql);
	if($num_reg>0)
	{
		while($D=mysql_fetch_array($sql))
		{
			$decreto=$D["decreto"];
		}
		$opcion="actualizar";
	}
	else
	{
		$decreto="";
		$opcion="insertar";
	}?>
<div id="Layer2">
<form action="graba_decreto.php" method="post" name="frmX" id="frmX">
  <table width="40%" border="0" align="center">
  <thead>
    <tr>
      <th colspan="2" >
      Carrera Cod.:<?php echo $id_carrera;?> - <?php echo $opcion;?>
        <input name="opcion" type="hidden" id="opcion"  value="<?php echo $opcion;?>"/>
        <input name="id_carrera" type="hidden" id="id_carrera"  value="<?php echo $id_carrera;?>"/>
      </th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="115" valign="top" >Decreto:</td>
      <td width="229" >
        <textarea name="decreto" id="decreto" cols="40" rows="5"><?php echo $decreto;?></textarea>
     </td>
    </tr>
	<tr>
	<td >Sede:
	  <input name="sede" type="hidden" id="sede"  value="<?php echo $sede;?>"/></td>
	<td ><?php echo $sede;?></td>
	</tr>
    <tr>
      <td colspan="2" ><div align="center">
        <input type="button" name="Submit2" value="Guardar"  onclick="Confirmar();"/>
      </div></td>
    </tr>
    </tbody>
  </table>
  </form>
</div><?php
mysql_free_result($sql);
mysql_close($conexion);	
}
?>
</body>
</html>