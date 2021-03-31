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
	require("../../../../funciones/conexion_v2.php");
	include("../../../../funciones/funcion.php");
	$id_alumno=base64_decode($_GET["id_alumno"]);
	if(isset($_GET["id_pago"])){$id_pago=$_GET["id_pago"];}
	else{ $id_pago="";}
	$cons_A="SELECT nombre, apellido_P, apellido_M, carrera FROM alumno WHERE id='$id_alumno' LIMIT 1";
	$sql_A=$conexion_mysqli->query($cons_A);
		$DA=$sql_A->fetch_assoc();
		$alumno=$DA["nombre"]." ".$DA["apellido_P"]." ".$DA["apellido_M"];
		$carrera=$DA["carrera"];
	$sql_A->free();	
}
?>
<body>
<h1 id="banner">Administrador -Pagos del Alumno</h1>
<div id="link"><br><a href="#" class="button" onclick="javascript:window.close();">Cerrar</a></div>
<div id="apDiv1">
  <table width="65%" border="1" align="center">
<thead>
<tr>
	<th colspan="9">PAGOS DE ALUMNO ID <?php echo $id_alumno?><br />
    	<?php echo "$alumno <br> $carrera";?>
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

		$cons="SELECT * FROM pagos WHERE id_alumno='$id_alumno' ORDER by fechapago";
		$sql=$conexion_mysqli->query($cons);
		$num_reg=$sql->num_rows;
		if($num_reg>0)
		{
			while($P=$sql->fetch_assoc())
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
				<td colspan="6">Sin Pago Realizados Por este Alumno</td>
				</tr>';
		}
	$sql->free();	
	$conexion_mysqli->close();
}
?>
  <tbody>
  </tbody>
</table>
</div>
</body>
</html>