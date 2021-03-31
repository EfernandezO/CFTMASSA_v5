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
<title>Informe Mensual</title>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 77px;
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
#apDiv1 .resumen {
	margin-top: 20px;
	margin-bottom: 20px;
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
<style type="text/css">
<!--
.Estilo1 {
	font-size: 14px;
	font-weight: bold;
}
-->
</style>
</head>

<body>
<h1 id="banner">Presupuesto - Informe Mensual</h1>
<div id="link"><a href="../selector_informe.php" class="Estilo2">Volver a Seleccion</a></div>

<div id="apDiv1">
<div align="center"><?php
if($_POST)
{
	if(DEBUG){ var_export($_POST);}
	include("../../../../../funciones/conexion.php");
	include("../../../../../funciones/funcion.php");
	$fecha_presupuesto=$_POST["fecha_presupuesto"];
	$sede=$_POST["sede"];
	
	$array_fecha=explode("-",$fecha_presupuesto);
	$dia=$array_fecha[2];
	$mes=$array_fecha[1];
	$year=$array_fecha[0];
	/////////////////
	//busco los distintos item
	//y los almaceno en array
	$PRESUPUESTO_MENSUAL=array();
	$MENSUAL=array();
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
			$PRESUPUESTO_MENSUAL["codigo"][]=$codigo;
			$PRESUPUESTO_MENSUAL["nombre"][]=$nombre;
		}
	}
	else
	{ echo"No hay Item Creados para esta sede...<br>";}
	
	if(DEBUG)
	{
		echo"<br> ARRAY con ITEM<br>";
		var_export($PRESUPUESTO_MENSUAL);
	}
	if(!empty($PRESUPUESTO_MENSUAL["codigo"]))
	{
		foreach($PRESUPUESTO_MENSUAL["codigo"] as $n => $codigoX)
		{
			
			$AUX=BUSCAR_PRESUPUESTO($mes, $year, $codigoX, $sede);
			array_push($MENSUAL, $AUX);
			
		}
		
		if(DEBUG){var_export($MENSUAL);}
		
		$maximo_dia=MAXIMO_DIA_MES($mes, $year);
		//////////////////presentar datos
	
	}
	
	mysql_free_result($sql_item);
}
else
{ echo "sin datos";}//fin post	
////////////////////////////////////////////////////////////////////
function BUSCAR_PRESUPUESTO($mes, $year, $codigo, $sede)
{
	if(DEBUG){echo"<br>==============FUNCION===================<br>";}
	
		switch($mes)
		{
			case"1":
				$fecha_inicio="$year-01-01";
				$fecha_fin="$year-01-31";
				$dia_maximo=31;
				break;
			case"2":
				$fecha_inicio="$year-02-01";
				if($year%4==0)
				{ 
					$fecha_fin="$year-02-29";
					$dia_maximo=29;
				}
				else
				{ 
					$fecha_fin="$year-02-28";
					$dia_maximo=28;
				}
				break;
			case"3":
				$fecha_inicio="$year-03-01";
				$fecha_fin="$year-03-31";
				$dia_maximo=31;
				break;		
			case"4":
				$fecha_inicio="$year-04-01";
				$fecha_fin="$year-04-30";
				$dia_maximo=30;
				break;
			case"5":
				$fecha_inicio="$year-05-01";
				$fecha_fin="$year-05-31";
				$dia_maximo=31;
				break;
			case"6":
				$fecha_inicio="$year-06-01";
				$fecha_fin="$year-06-30";
				$dia_maximo=30;
				break;
			case"7":
				$fecha_inicio="$year-07-01";
				$fecha_fin="$year-07-31";
				$dia_maximo=31;
				break;
			case"8":
				$fecha_inicio="$year-08-01";
				$fecha_fin="$year-08-31";
				$dia_maximo=31;
				break;
			case"9":
				$fecha_inicio="$year-09-01";
				$fecha_fin="$year-09-30";
				$dia_maximo=30;
				break;
			case"10":
				$fecha_inicio="$year-10-01";
				$fecha_fin="$year-10-31";
				$dia_maximo=31;
				break;
			case"11":
				$fecha_inicio="$year-11-01";
				$fecha_fin="$year-11-30";
				$dia_maximo=30;
				break;
			case"12":
				$fecha_inicio="$year-12-01";
				$fecha_fin="$year-12-31";
				$dia_maximo=31;
				break;								
		}
		for($d=1;$d<=$dia_maximo;$d++)
		{
			if($d<10)
			{ $d_label="0".$d;}
			else
			{ $d_label=$d;}
			$fechaY="$year-$mes-$d_label";
			$RESULTADOS[$codigo][$d]["I"]=0;
			$RESULTADOS[$codigo][$d]["E"]=0;
			$cons="SELECT * FROM presupuesto WHERE sede='$sede' AND item='$codigo' AND fecha='$fechaY'";
			if(CARGAR_INGRESOS)
			{
				//cargo los ingresos desde la tabla pagos
				$cons_ingresos="SELECT SUM(valor) FROM pagos WHERE sede='$sede' AND item='$codigo' AND movimiento='I' AND fechapago='$fechaY'";
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
			$RESULTADOS[$codigo][$d]["I"]+=$aux_ingresos;//sumo los inpresos desde cajas
			//////////////////////////////////
			$sql=mysql_query($cons)or die("funcion".mysql_error);
			while($P=mysql_fetch_assoc($sql))
			{
				$movimiento=$P["movimiento"];
				$valor=$P["valor"];
				switch($movimiento)
				{
					case"I":
						$RESULTADOS[$codigo][$d]["I"]+=$valor;
						break;
					case"E":
						$RESULTADOS[$codigo][$d]["E"]+=$valor;
						break;	
				}
			}
			mysql_free_result($sql);
	}
	
	if(DEBUG){echo"<br>==============FIN_FUNCION===================<br>";}
	//$RESULTADOS["MAXIMO_DIA"]=$dia_maximo;
	return($RESULTADOS);
}
/////////////////////////////////////////////
function MAXIMO_DIA_MES($mes, $year)
{	
		switch($mes)
		{
			case"1":
				$fecha_inicio="$year-01-01";
				$fecha_fin="$year-01-31";
				$dia_maximo=31;
				break;
			case"2":
				$fecha_inicio="$year-02-01";
				if($year%4==0)
				{ 
					$fecha_fin="$year-02-29";
					$dia_maximo=29;
				}
				else
				{ 
					$fecha_fin="$year-02-28";
					$dia_maximo=28;
				}
				break;
			case"3":
				$fecha_inicio="$year-03-01";
				$fecha_fin="$year-03-31";
				$dia_maximo=31;
				break;		
			case"4":
				$fecha_inicio="$year-04-01";
				$fecha_fin="$year-04-30";
				$dia_maximo=30;
				break;
			case"5":
				$fecha_inicio="$year-05-01";
				$fecha_fin="$year-05-31";
				$dia_maximo=31;
				break;
			case"6":
				$fecha_inicio="$year-06-01";
				$fecha_fin="$year-06-30";
				$dia_maximo=30;
				break;
			case"7":
				$fecha_inicio="$year-07-01";
				$fecha_fin="$year-07-31";
				$dia_maximo=31;
				break;
			case"8":
				$fecha_inicio="$year-08-01";
				$fecha_fin="$year-08-31";
				$dia_maximo=31;
				break;
			case"9":
				$fecha_inicio="$year-09-01";
				$fecha_fin="$year-09-30";
				$dia_maximo=30;
				break;
			case"10":
				$fecha_inicio="$year-10-01";
				$fecha_fin="$year-10-31";
				$dia_maximo=31;
				break;
			case"11":
				$fecha_inicio="$year-11-01";
				$fecha_fin="$year-11-30";
				$dia_maximo=30;
				break;
			case"12":
				$fecha_inicio="$year-12-01";
				$fecha_fin="$year-12-31";
				$dia_maximo=31;
				break;								
		}
	return($dia_maximo);
}
mysql_close($conexion);
?>	
</div>
<div id="titulo">
  <div align="center"><span class="Estilo1"><?php echo"$mes/$year - $sede";?></span></div>
 </div>
<table width="100%" border="1">
    <?php
	 $size='height=450&width=550';
  	 $rel='rel="sexylightbox"';
	 echo'<tr>
	 		<td rowspan="2">Dia</td>';
	 
	 foreach($PRESUPUESTO_MENSUAL["codigo"] as $n => $valor)
	 {
	 			$aux_nombre=$PRESUPUESTO_MENSUAL["nombre"][$n];
				echo'<td colspan="2"><a href="#" title="'.$valor.'">'.$aux_nombre.'</a></td>';
	 }
	 echo'</tr>';
	 
	  echo'<tr>';
	 

	 foreach($PRESUPUESTO_MENSUAL["codigo"] as $n => $valor)
	 {
	 	
				echo'<td>I</td>
					 <td>E</td>';
	 }
	 echo'</tr>';
	 
	 
    for($d=1;$d<=$maximo_dia;$d++)
	{
		$fila='<tr>
			<td>'.$d.'</td>';
	
		foreach($PRESUPUESTO_MENSUAL["codigo"] as $n => $codigo)
		 {
		 	$aux_ingreso=$MENSUAL[$n][$codigo][$d]["I"];
			$aux_egreso=$MENSUAL[$n][$codigo][$d]["E"];
			if(($aux_ingreso>0)or($aux_egreso>0))
			{ $mostrar=true;}
			$fila.='<td>$<a href="detalle_dia.php?sede='.$sede.'&codigo='.$codigo.'&year='.$year.'&mes='.$mes.'&dia='.$d.'&tipo=I&TB_iframe=true&'.$size.'" '.$rel.'">'.number_format($aux_ingreso,0,",",".").'</a></td>';
			$fila.='<td>$<a href="detalle_dia.php?sede='.$sede.'&codigo='.$codigo.'&year='.$year.'&mes='.$mes.'&dia='.$d.'&tipo=E&TB_iframe=true&'.$size.'" '.$rel.'">'.number_format($aux_egreso,0,",",".").'</a></td>';
			
			$TOTAL[$codigo]["I"]+=$aux_ingreso;
			$TOTAL[$codigo]["E"]+=$aux_egreso;
		 }
		$fila.='</tr>';
		if($mostrar)
		{ echo $fila;}
		$mostrar=false;
	}
	 echo'<tr>
	 		<td>TOTAL</td>';
	 foreach($PRESUPUESTO_MENSUAL["codigo"] as $n => $codigo)
	 {
	 	
				echo'<td>$'.number_format($TOTAL[$codigo]["I"],0,",",".").'</td>
					 <td>$'.number_format($TOTAL[$codigo]["E"],0,",",".").'</td>';
	 }
	 echo'</tr>';
	?>
</table>

</div>
</body>
</html>