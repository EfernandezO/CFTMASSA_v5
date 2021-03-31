<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="inspeccion";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define("DEBUG", true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<title>Pagos del Alumno</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:64px;
	z-index:1;
	left: 5%;
	top: 144px;
}
</style>
</head>
<?php
if($_GET)
{
	include("../../../../funciones/conexion.php");
	include("../../../../funciones/funcion.php");
	$id_empresa=$_GET["id_empresa"];
	$id_pago=$_GET["id_pago"];
	$cons_A="SELECT * FROM empresa WHERE id='$id_empresa' LIMIT 1";
	$sql_A=mysql_query($cons_A)or die(mysql_error());
		$DA=mysql_fetch_assoc($sql_A);
		$empresa=$DA["nombre_fantasia"]." ".$DA["rut"];
	mysql_free_result($sql_A);	
}
?>
<body>
<h1 id="banner">Administrador -Pagos de la Empresa</h1>
<div id="link"><br><a href="#" class="button" onclick="javascript:window.close();">Cerrar</a></div>
<div id="apDiv1">
  <table width="65%" border="1" align="center">
<thead>
<tr>
	<th colspan="9">PAGOS DE La EMPRESA ID <?php echo $id_empresa?><br />
    	<?php echo "$empresa ";?>
</th>
</tr>
  <tr>
  	<td></td>
    <td>N&deg;</td>
    <td>ID Pago</td>
    <td>Valor</td>
    <td>Forma Pago</td>
    <td>Fecha Pago</td>
    <td>Glosa</td>
    <td>Por Concepto</td>
    <td>ID User</td>
  </tr>
  </thead>
<?php
if($_GET)
{
		$aux=0;

		$cons="SELECT * FROM pagos WHERE id_empresa='$id_empresa' ORDER by fechapago";
		$sql=mysql_query($cons)or die(mysql_error());
		$num_reg=mysql_num_rows($sql);
		if($num_reg>0)
		{
			while($P=mysql_fetch_assoc($sql))
			{
				$aux++;
				
				$P_id=$P["idpago"];
				$P_fecha=$P["fechapago"];
				$P_valor=$P["valor"];
				$P_glosa=$P["glosa"];
				$P_forma_pago=$P["forma_pago"];
				$P_por_concepto=$P["por_concepto"];
				$P_cod_user=$P["cod_user"];	
				
				echo'<tr>';
				if($P_id==$id_pago)
				{
					echo'<td><img src="../../../BAses/Images/flecha_der.png" width="29" height="29" alt="-&gt;" /></td>';
				}
				else
				{
					echo'<td>&nbsp;</td>';
				}
				   echo'<td>'.$aux.'</td>
						<td>'.$P_id.'</td>
						<td>$'.number_format($P_valor,0,",",".").'</td>
						<td>'.$P_forma_pago.'</td>
						<td>'.fecha_format($P_fecha).'</td>
						<td>'.$P_glosa.'</td>
						<td>'.$P_por_concepto.'</td>
						<td>'.$P_cod_user.'</td>
					</tr>';
			}
		}
		else
		{
			echo'<tr>
				<td colspan="6">Sin Pago Realizados Por esta Empresa</td>
				</tr>';
		}
	mysql_free_result($sql);	
	mysql_close($conexion);
}
?>
  <tbody>
  </tbody>
</table>
</div>
</body>
</html>