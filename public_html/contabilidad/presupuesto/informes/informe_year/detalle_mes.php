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
<title>Detalle de mes</title>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:28px;
	z-index:1;
	left: 5%;
	top: 72px;
}
#apDiv1 #TOTAL {
	margin-top: 25px;
	border: thin solid #339900;
}
-->
</style>
</head>
<?php
	$sede=$_GET["sede"];
	$codigo=$_GET["codigo"];
	$year=$_GET["year"];
	$mes=$_GET["mes"];
	$tipo=$_GET["tipo"];
	
	
	if($mes<10)
	{ $mes_label="0".$mes;}
	else
	{ $mes_label=$mes;}
	if($tipo=="I")
	{ $tipo_label="Ingresos";}
	else
	{ $tipo_label="Egresos";}
	
	$msj="Item($codigo) - $tipo_label<br> $mes_label/$year - $sede";
?>
<body>
<h1 id="banner">Presupuesto - Detalle del Mes</h1>

<div id="link"><a href="#" onclick="window.print();">imprimir</a></div>
<div id="apDiv1">
  <div align="center">
    <p><?php echo $msj;?></p>
    <table width="100%" border="1">
      <thead>
      <tr>
      <th colspan="6">PRESUPUESTO</th>
      </tr>
        <tr>
          <td>N&deg;</td>
          <td>Item</td>
          <td>Valor</td>
          <td>Fecha</td>
          <td>Glosa</td>
          <td>Usuario</td>
        </tr>
      </thead>
      <tbody>
        <?php 
if($_GET)
{
	include("../../../../../funciones/conexion.php");
	include("../../../../../funciones/funcion.php");
	if(DEBUG){var_export($_GET);}
	$sede=$_GET["sede"];
	$codigo=$_GET["codigo"];
	$year=$_GET["year"];
	$mes=$_GET["mes"];
	$tipo=$_GET["tipo"];
	
		switch($mes)
		{
			case"1":
				$fecha_inicio="$year-01-01";
				$fecha_fin="$year-01-31";
				break;
			case"2":
				$fecha_inicio="$year-02-01";
				if($year%4==0)
				{ $fecha_fin="$year-02-29";}
				else
				{ $fecha_fin="$year-02-28";}
				break;
			case"3":
				$fecha_inicio="$year-03-01";
				$fecha_fin="$year-03-31";
				break;		
			case"4":
				$fecha_inicio="$year-04-01";
				$fecha_fin="$year-04-30";
				break;
			case"5":
				$fecha_inicio="$year-05-01";
				$fecha_fin="$year-05-31";
				break;
			case"6":
				$fecha_inicio="$year-06-01";
				$fecha_fin="$year-06-30";
				break;
			case"7":
				$fecha_inicio="$year-07-01";
				$fecha_fin="$year-07-31";
				break;
			case"8":
				$fecha_inicio="$year-08-01";
				$fecha_fin="$year-08-31";
				break;
			case"9":
				$fecha_inicio="$year-09-01";
				$fecha_fin="$year-09-30";
				break;
			case"10":
				$fecha_inicio="$year-10-01";
				$fecha_fin="$year-10-31";
				break;
			case"11":
				$fecha_inicio="$year-11-01";
				$fecha_fin="$year-11-30";
				break;
			case"12":
				$fecha_inicio="$year-12-01";
				$fecha_fin="$year-12-31";
				break;								
		}
	$cons="SELECT * FROM presupuesto WHERE sede='$sede' AND movimiento='$tipo' AND item='$codigo' AND fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";	
	
	if(DEBUG){echo"$cons<br>";}
	$sql=mysql_query($cons)or die(mysql_error());
	$num_reg=mysql_num_rows($sql);
	if($num_reg>0)
	{
		$aux=0;
		while($P=mysql_fetch_assoc($sql))
		{
			$aux++;
			$id=$P["id"];
			$movimiento=$P["movimiento"];
			$codigo_item=$P["item"];
			$valor=$P["valor"];
			$glosa=$P["glosa"];
			$fecha=$P["fecha"];
			$fecha_generacion=$P["fecha_generacion"];
			$cod_user=$P["cod_user"];
			
			echo'<tr>
				<td><div align="center">'.$aux.'</div></td>
				<td><div align="center">'.$codigo_item.'</div></td>
				<td><div align="center">$'.number_format($valor,0,",",".").'</div></td>
				<td><div align="center"><a href="#" title="FC.: '.$fecha_generacion.'">'.fecha_format($fecha).'</a></div></td>
				<td><div align="center">'.$glosa.'</div></td>
				<td><div align="center">'.$cod_user.'</div></td>
				</tr>';
				
				$acumula_valor_P+=$valor;
		}
	}
	else
	{
		echo'<tr><td colspan="6">Sin Datos...</td></tr>';
	}
	mysql_free_result($sql);
}
else
{echo"Sin Datos...<br>";}
?>
      </tbody>
      <tr>
      	<td colspan="2">TOTAL</td>
        <td><?php echo "$".number_format($acumula_valor_P,0,",",".");?></td>
        <td colspan="3">&nbsp;</td>
      </tr>
    </table><br />

     <table width="100%" border="1">
      <thead>
      <tr>
      <th colspan="6">Ingresos Cajas</th>
      </tr>
        <tr>
          <td>N&deg;</td>
          <td>Item</td>
          <td>Valor</td>
          <td>Fecha</td>
          <td>Glosa</td>
          <td>Usuario</td>
        </tr>
      </thead>
      <tbody>
      <?php
	  if($tipo=="I")
	  {
		  if(CARGAR_INGRESOS)
		  {
				$cons_ingresos="SELECT * FROM pagos WHERE sede='$sede' AND item='$codigo' AND movimiento='I' AND fechapago BETWEEN '$fecha_inicio' AND '$fecha_fin' ORDER by fechapago, idpago";
				if(DEBUG){echo"INGRESOS: $cons_ingresos<br>";}
				$sql_ingresos=mysql_query($cons_ingresos)or die("suma_ingresos ".mysql_error());
				$num_regx=mysql_num_rows($sql_ingresos);
				if(DEBUG){echo"numero de Ingresos desde cajas: $num_regx<br>";}
				if($num_regx>0)
				{
					$auxX=0;
					while($DIP=mysql_fetch_assoc($sql_ingresos))
					{
						$auxX++;
						$id_pago=$DIP["idpago"];
						$itemX=$DIP["item"];
						$fecha_pago=$DIP["fechapago"];
						$fecha_generacionX=$DIP["fecha_generacion"];
						$codigo_userX=$DIP["cod_user"];
						$valorX=$DIP["valor"];
						$glosaX=$DIP["glosa"];
						$forma_pago=$DIP["forma_pago"];
						
						
					echo'<tr>
						<td><div align="center">'.$auxX.'</div></td>
						<td><div align="center">'.$itemX.'</div></td>
						<td><div align="center">$'.number_format($valorX,0,",",".").'</div></td>
						<td><div align="center"><a href="#" title="FC.: '.$fecha_generacionX.'">'.fecha_format($fecha_pago).'</a></div></td>
						<td><div align="center">'.$glosaX.'</div></td>
						<td><div align="center">'.$codigo_userX.'</div></td>
						</tr>';
						
						$acumula_valor_I+=$valorX;
					}
				}
				else
				{
					echo'<tr><td colspan="6">no hay Ingresos Registrados</td></tr>';
				}
				mysql_free_result($sql_ingresos);
		  }
		  else
		  {
			echo'<tr><td colspan="6">CARGA_INGRESOS NO HABILITADA</td></tr>';
		  }
	  }
	  else
	  {
	  	if(DEBUG){ echo"TIPO E, no se cargar desde PAGOS...";}
	  }
	  mysql_close($conexion);
	  ?>
      </tbody>
      <tr>
      	<td colspan="2">TOTAL</td>
        <td><?php echo "$".number_format($acumula_valor_I,0,",",".");?></td>
        <td colspan="3">&nbsp;</td>
      </tr>
    </table>
    
     <div id="TOTAL">
       <div align="left"><strong>TOTAL(presupuesto + cajas): <?php echo "$".number_format($acumula_valor_P+$acumula_valor_I,0,",",".")?></strong></div>
    </div>
  </div>
</div>
</body>
</html>