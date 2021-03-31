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
<title>presupuesto - informe diario</title>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:83px;
	z-index:1;
	left: 5%;
	top: 77px;
}
a:link {
	text-decoration: none;
	color: #006699;
}
a:visited {
	text-decoration: none;
	color: #006699;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #006699;
}
-->
</style>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="../../../../libreria_publica/sexy_lightbox/jQuery/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="../../../../libreria_publica/sexy_lightbox/jQuery/sexylightbox.v2.3.jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/sexy_lightbox/jQuery/sexylightbox.css">
<script type="text/javascript">
    $(document).ready(function(){
      SexyLightbox.initialize({color:'black', dir: '../../../../libreria_publica/sexy_lightbox/jQuery/sexyimages'});
    });
  </script>
</head>

<body>
<h1 id="banner">Presupuesto - Informe Diario</h1>
<div id="link"><a href="../selector_informe.php" class="Estilo2">Volver a Seleccion</a></div>
<div id="apDiv1">
  <div align="center">
  <?php
if($_POST)
{
	if(DEBUG){ var_export($_POST);}
	include("../../../../../funciones/conexion.php");
	include("../../../../../funciones/funcion.php");
	$fecha_presupuesto=$_POST["fecha_presupuesto"];
	$sede=$_POST["sede"];
	
	$msj=fecha_format($fecha_presupuesto)." - ".$sede;
	
	$array_fecha=explode("-",$fecha_presupuesto);
	$dia=$array_fecha[2];
	$mes=$array_fecha[1];
	$year=$array_fecha[0];
	/////////////////
	//busco los distintos item
	//y los almaceno en array
	$PRESUPUESTO_MENSUAL=array();
	
	$cons_item="SELECT codigo, nombre FROM presupuesto_parametros WHERE sede='$sede'";
	if(DEBUG){echo"ITEM -> $cons_item<br>";}
	$sql_item=mysql_query($cons_item)or die("item".mysql_error());
	$num_item=mysql_num_rows($sql_item);
	if($num_item>0)
	{ $continuar=true;}
	else
	{ $continuar=false;}
	
	if(DEBUG){echo"N -> $num_item<br>";}
	if($continuar)
	{
		while($I=mysql_fetch_assoc($sql_item))
		{
			$codigo=$I["codigo"];
			$nombre=$I["nombre"];
			$PRESUPUESTO_DIARIO["codigo"][]=$codigo;
			$PRESUPUESTO_DIARIO["nombre"][]=$nombre;
		}
	}
	else
	{ echo"No hay Item Creados para esta sede...<br>";}
	
	if(DEBUG)
	{
		echo"<br> ARRAY con ITEM<br>";
		var_export($PRESUPUESTO_DIARIO);
	}
	if($continuar)
	{
		foreach($PRESUPUESTO_DIARIO["codigo"] as $n => $codigo)
		{
			
			$RESULTADOS[$codigo]["I"]=0;
			$RESULTADOS[$codigo]["IC"]=0;
			$RESULTADOS[$codigo]["E"]=0;
			$RESULTADOS[$codigo]["nombre"]=$PRESUPUESTO_DIARIO["nombre"][$n];
			$cons="SELECT * FROM presupuesto WHERE sede='$sede' AND item='$codigo' AND fecha='$fecha_presupuesto'";
			if(CARGAR_INGRESOS)
			{
				//cargo los ingresos desde la tabla pagos
				$cons_ingresos="SELECT SUM(valor) FROM pagos WHERE sede='$sede' AND item='$codigo' AND movimiento='I' AND fechapago='$fecha_presupuesto'";
				$sql_ingresos=mysql_query($cons_ingresos)or die("suma_ingresos ".mysql_error());
				$D_ingresos=mysql_fetch_row($sql_ingresos);
				$aux_ingresos=$D_ingresos[0];
				if(empty($aux_ingresos))
				{ $aux_ingresos=0;}
				mysql_free_result($sql_ingresos);
			}
			else
			{ $aux_ingresos=0;}
			if(DEBUG){ echo"Presupuesto:--> $cons<br>Pagos:->$cons_ingresos<br>PI=$aux_ingresos<br>";}
			$RESULTADOS[$codigo]["IC"]+=$aux_ingresos;//sumo los inpresos desde cajas
			//////////////////////////////////
			$sql=mysql_query($cons)or die("funcion".mysql_error);
			while($P=mysql_fetch_assoc($sql))
			{
				$movimiento=$P["movimiento"];
				$valor=$P["valor"];
				switch($movimiento)
				{
					case"I":
						$RESULTADOS[$codigo]["I"]+=$valor;
						break;
					case"E":
						$RESULTADOS[$codigo]["E"]+=$valor;
						break;	
				}
			}
			mysql_free_result($sql);
		}
		if(DEBUG){ var_export($RESULTADOS);}
		unset($PRESUPUESTO_DIARIO);
		?>
  <table width="80%" border="1">
    <thead>
    <tr>
    	<th colspan="5"><?php echo $msj;?></th>
    </tr>
      <tr>
        <th rowspan="2">Item</th>
          <th>Caja</th>
          <th colspan="2">Presupuesto</th>
          <th rowspan="2">Total</th>
        </tr>
      <tr>
        <th>Ingreso</th>
        <th>Ingreso</th>
        <th>Egreso</th>
      </tr>
      </thead>
    <tbody>
      <?php
	  	 $size='height=450&width=550';
  		 $rel='rel="sexylightbox"';
		foreach($RESULTADOS as $codigo => $valor)
		{
			$aux_ingresos=$RESULTADOS[$codigo]["I"];
			$aux_ingresos_caja=$RESULTADOS[$codigo]["IC"];
			$aux_egresos=$RESULTADOS[$codigo]["E"];
			$aux_nombre=$RESULTADOS[$codigo]["nombre"];
			$aux_total=($aux_ingresos + $aux_ingresos_caja)-$aux_egresos;
			
			$total_ingresos+=$aux_ingresos;
			$total_ingresos_caja+=$aux_ingresos_caja;
			$total_egresos+=$aux_egresos;
			$total_final+=$aux_total;
			echo'<tr>
				<td><div align="center"><a href="#" title="'.$codigo.'">'.$aux_nombre.'</a></div></td>
				<td><div align="center">$<a href="detalle_movimiento.php?T=caja&C='.$codigo.'&M=I&F='.$fecha_presupuesto.'&S='.$sede.'&TB_iframe=true&'.$size.'" '.$rel.'">'.number_format($aux_ingresos_caja,0,",",".").'</a></div></td>
				<td><div align="center">$<a href="detalle_movimiento.php?T=presupuesto&C='.$codigo.'&M=I&F='.$fecha_presupuesto.'&S='.$sede.'&TB_iframe=true&'.$size.'" '.$rel.'">'.number_format($aux_ingresos,0,",",".").'</a></div></td>
				<td><div align="center">$<a href="detalle_movimiento.php?T=presupuesto&C='.$codigo.'&M=E&F='.$fecha_presupuesto.'&S='.$sede.'&TB_iframe=true&'.$size.'" '.$rel.'">'.number_format($aux_egresos,0,",",".").'</a></div></td>
				<td><div align="center">$'.number_format($aux_total,0,",",".").'</div></td>
				</tr>';
		}?>
      <tr>
        <td><div align="center"><strong>Total</strong></div></td>
            <td><div align="center"><strong><?php echo "$".number_format($total_ingresos_caja,0,",",".");?></strong></div></td>
            <td><div align="center"><strong><?php echo "$".number_format($total_ingresos,0,",",".");?></strong></div></td>
            <td><div align="center"><strong><?php echo "$".number_format($total_egresos,0,",",".");?></strong></div></td>
            <td><div align="center"><strong><?php echo "$".number_format($total_final,0,",",".");?></strong></div></td>
        </tbody>
  </table>
  <?php
	}
	unset($RESULTADOS);
mysql_close($conexion);	
}//fin post
else
{echo"Sin Datos<br>";}	
?>
  </div>
</div>
</body>
</html>