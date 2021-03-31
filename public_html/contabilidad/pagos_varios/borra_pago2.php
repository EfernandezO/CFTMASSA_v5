<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
//-----------------------------------------//	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Fin eliminacion</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla.css">
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:370px;
	height:112px;
	z-index:1;
	left: 85px;
	top: 164px;
}
#Layer2 {
	position:absolute;
	width:370px;
	height:112px;
	z-index:1;
	left: 82px;
	top: 162px;
}
#Layer3 {
	position:absolute;
	width:245px;
	height:15px;
	z-index:1;
	left: 308px;
	top: 74px;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
.Estilo2 {color: #0080C0}
.Estilo3 {
	color: #FF0000;
	font-size: medium;
}
.Estilo4 {font-size: small}
-->
</style>
</head>
<body>
<h1 id="banner">Administrador-Eliminaci&oacute;n de Movimientos </h1>
<?php
if($_POST)
{
	extract($_POST);
	include("../../../funciones/funcion.php");
	include("../../../funciones/conexion.php");
	$tipo_doc=str_inde($tipo_doc);
	$num_doc=str_inde($num_doc);
	$tipo_mov=str_inde($ftipo_mov);
	
	if(is_numeric($num_doc))
	{
		if(($tipo_doc=="Boleta")or($tipo_doc=="Factura"))
		{
			$condicion="numletra='$num_doc' and tipodoc='$tipo_doc' and sede='$fsede' and movimiento='$tipo_mov'";
			$consS="SELECT COUNT(idpago) FROM pagos WHERE $condicion";
			$consB="DELETE FROM pagos WHERE $condicion";
			//echo"$consS<br>$consB<br>";
			
			$sqlS=mysql_query($consS)or die(mysql_error());
			$D=mysql_fetch_row($sqlS);
			$coincidencias=$D[0];
			if($coincidencias>0)
			{
			
				if(mysql_query($consB))
				{
					?>
					<div id="Layer2">
 			 		<table sumary="info">
					<caption></caption>
					<thead>
    				<tr>
      				<td  scope="col" width="357" ><div align="center" class="Estilo1 Estilo3">Informaci&oacute;n</div>	</td>
    				</tr>
					</thead>
					<tbody>
    				<tr class="odd">
      				<td height="62"><div align="center" class="Estilo4">La <?php echo"$tipo_doc";?> Numero: <?php echo"$num_doc";?>, fue Eliminada Existosamente </div></td>
    				</tr>
    				
					</tbody>
					<tfoot>
					<tr>
      				<td>&nbsp;</td>
    				</tr>
					</tfoot>
  					</table>
</div>
					<?php
				}
				else
				{
					?>
					<div id="Layer1">
 			 		<table sumary="error">
					<caption></caption>
					<thead>
    				<tr>
      				<td scope="col" width="357" ><div align="center" class="Estilo1 Estilo3">ERROR</div>	</td>
    				</tr>
					</thead>
					<tbody>
    				<tr class="odd">
      				<td height="62" ><div align="center" class="Estilo4">No fue posible Eliminar este pago.<br />
        por favor revise sus datos e intentelo mas tarde.
</div></td>
    				</tr>
					</tbody>
					<tfoot>
    				<tr>
      				<td >&nbsp;</td>
    				</tr>
					</tfoot>
  					</table>
</div>
					<?php
				}
			}
			else
			{
				echo"<br><br><br><br><br><strong>No hay ningun movimiento registrado con los datos ingresados ($coincidencias)</strong><br>";
			}
		}
		else
		{
			echo"<br><br><br><br><br></b>Datos Incorrectos...</b>";
		}
	}
	else
	{
		echo"<br><br><br><br><br><b>Datos Incorrectos...</b>";
	}
	
	
}
?>
<div id="Layer3"><span class="Estilo2"><a href="borra_pago.php">Volver a Eliminacion</a> &nbsp;&nbsp;&nbsp;<a href="../index.php">Volver al Menu</a></span> </div>
</body>
</html>