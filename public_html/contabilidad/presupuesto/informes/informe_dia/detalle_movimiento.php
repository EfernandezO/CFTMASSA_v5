<?php
//-----------------------------------------//
	require("../../../../OKALIS/seguridad.php");
	require("../../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="inspeccion";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define("DEBUG", false);
define("CARGAR_INGRESOS", true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_3.css">
<title>Detalle - Movimiento</title>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:38px;
	z-index:1;
	left: 5%;
	top: 131px;
}
-->
</style>
</head>
<?php
		include("../../../../../funciones/funcion.php");
		$tipo=$_GET["T"];
		$codigo=$_GET["C"];
		$movimiento=$_GET["M"];
		
		if($movimiento=="I")
		{ $movimiento_label="Ingresos";}
		else
		{ $movimiento_label="Egresos";}
		$fecha_presupuesto=$_GET["F"];
		$sede_presupuesto=$_GET["S"];
		
		$msj="ITEM: $codigo<br>$movimiento_label - ".fecha_format($fecha_presupuesto)."<br>$tipo - $sede_presupuesto";
?>
<body>
<h1 id="banner">Presupuesto - Detalle Movimiento</h1>
<div id="link"><a href="#" onclick="window.print();">imprimir</a></div>
  <div id="apDiv1">
    <div align="center">
    <table width="80%">
		<thead>
        <tr>
        	<th colspan="6"><?php echo $msj;?></th>
        </tr>
      <tr>
        <td>N&deg;</td>
        <td>fecha</td>
        <td>valor</td>
        <td>Forma de Pago</td>
        <td>glosa</td>
        <td>usuario</td>
      </tr>
   </thead>
   <tbody>
    <?php
    if($_GET)
	{
		include("../../../../../funciones/conexion.php");
		
		switch($tipo)
		{
			case"caja":
				if(CARGAR_INGRESOS)
				{
					$cons="SELECT * FROM pagos WHERE item='$codigo' AND movimiento='$movimiento' AND fechapago='$fecha_presupuesto' AND sede='$sede_presupuesto'";
					if(DEBUG){echo"-->$cons<br>";}
					$sql=mysql_query($cons)or die("presupuesto".mysql_error());
					$num_reg=mysql_num_rows($sql);
					if($num_reg>0)
					{
						$aux=0;
						while($P=mysql_fetch_assoc($sql))
						{
							$aux++;
							$fecha=$P["fechapago"];
							$valor=$P["valor"];
							$glosa=$P["glosa"];
							$cod_user=$P["cod_user"];
							$forma_pago=$P["forma_pago"];
							////////////////////
							$cons_user="SELECT nombre, apellido FROM personal WHERE id ='$cod_user'";
							$sql_user=mysql_query($cons_user) or die(mysql_error());
							$DU=mysql_fetch_assoc($sql_user);
							$nombre=$DU["nombre"];
							$apellido=$DU["apellido"];
							$usuario_nombre=$nombre." ".$apellido;
							mysql_free_result($sql_user);
							//////////////////////
							
							echo'<tr>
								<td>'.$aux.'</td>
								<td>'.fecha_format($fecha).'</td>
								<td>$'.number_format($valor,0,",",".").'</td>
								<td>'.$forma_pago.'</td>
								<td>'.$glosa.'</td>
								<td><a href="#" title="'.$usuario_nombre.'">'.$cod_user.'</a></td>
								</tr>';
								
								$total+=$valor;
						}
					}
					else
					{
						echo'<tr><td colspan="6">Sin Movimientos en este dia para este item desde "Caja"...</td><tr>';
					}
					mysql_free_result($sql);
				}
				else
				{
					echo'<tr><td colspan="6">La Carga de Ingresos desde Caja esta Deshabilitada...</td><tr>';
				}
				break;
			case"presupuesto":
				$cons="SELECT * FROM presupuesto WHERE item='$codigo' AND movimiento='$movimiento' AND sede='$sede_presupuesto' AND fecha='$fecha_presupuesto'";
				if(DEBUG){echo"-->$cons<br>";}
				$sql=mysql_query($cons)or die("presupuesto".mysql_error());
				$num_reg=mysql_num_rows($sql);
				if($num_reg>0)
					{
						$aux=0;
						while($P=mysql_fetch_assoc($sql))
						{
							$aux++;
							$fecha=$P["fecha"];
							$valor=$P["valor"];
							$glosa=$P["glosa"];
							$cod_user=$P["cod_user"];
							$forma_pago=$P["forma_pago"];
							////////////////////
							$cons_user="SELECT nombre, apellido FROM personal WHERE id ='$cod_user'";
							$sql_user=mysql_query($cons_user) or die(mysql_error());
							$DU=mysql_fetch_assoc($sql_user);
							$nombre=$DU["nombre"];
							$apellido=$DU["apellido"];
							$usuario_nombre=$nombre." ".$apellido;
							mysql_free_result($sql_user);
							//////////////////////
							
							echo'<tr>
								<td>'.$aux.'</td>
								<td>'.fecha_format($fecha).'</td>
								<td>$'.number_format($valor,0,",",".").'</td>
								<td>'.$forma_pago.'</td>
								<td>'.$glosa.'</td>
								<td><a href="#" title="'.$usuario_nombre.'">'.$cod_user.'</a></td>
								</tr>';
								
								$total+=$valor;
						}
					}
					else
					{
						echo'<tr><td colspan="6">Sin Movimientos en este dia para este item desde "Presupuesto"...</td><tr>';
					}
				break;	
		}
		mysql_close($conexion);
	}
	else
	{
		echo"Sin Datos...";
	}
	?>
    </tbody>
    <tfoot>
    	<tr>
        	<td colspan="2">Total</td>
            <td colspan="4"><?php echo "$".number_format($total,0,",",".");?></td>
        </tr>
    </tfoot>
     </table>
    </div>
  </div>
</body>
</html>
