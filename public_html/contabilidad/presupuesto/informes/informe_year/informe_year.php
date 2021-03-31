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
<title>seleccion de Informe</title>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_3.css">
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:95%;
	height:62px;
	z-index:1;
	left: 5px;
	top: 130px;
}
-->
</style>
<style type="text/css">
<!--
a:link {
	color: #006699;
	text-decoration: none;
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
  <style type="text/css">
<!--
.Estilo1 {font-size: 10px}
-->
  </style>
</head>

<body>
<h1 id="banner">Presupuesto - Informe Anual</h1>

<div id="link"><a href="../selector_informe.php" class="Estilo2">Volver a Seleccion</a></div>
<div id="apDiv1">
<?php
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
	$PRESUPUESTO_ANUAL=array();
	
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
			$PRESUPUESTO_ANUAL[]=$codigo;
		}
	}
	else
	{ echo"No hay Item Creados para esta sede...<br>";}
	
	if(DEBUG)
	{
		echo"<br> ARRAY con ITEM<br>";
		var_export($PRESUPUESTO_ANUAL);
	}
	
	if(!empty($PRESUPUESTO_ANUAL))
	{
		foreach($PRESUPUESTO_ANUAL as $n => $codigoX)
		{
			$ANUAL[]=BUSCAR_PRESUPUESTO($year, $codigoX, $sede);
		}
		
		if(DEBUG){var_export($ANUAL);}
		
		//////////////////presentar datos
	
	}
	
	mysql_free_result($sql_item);
}
else
{ echo "sin datos";}

function BUSCAR_PRESUPUESTO($year, $codigo, $sede)
{
	if(DEBUG){echo"<br>==============FUNCION===================<br>";}
	for($mes=1;$mes<=12;$mes++)
	{
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
		$RESULTADOS[$codigo][$mes]["I"]=0;
		$RESULTADOS[$codigo][$mes]["E"]=0;
		$cons="SELECT * FROM presupuesto WHERE sede='$sede' AND item='$codigo' AND fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
		if(CARGAR_INGRESOS)
		{
			//cargo los ingresos desde la tabla pagos
			$cons_ingresos="SELECT SUM(valor) FROM pagos WHERE sede='$sede' AND item='$codigo' AND movimiento='I' AND fechapago BETWEEN '$fecha_inicio' AND '$fecha_fin'";
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
		$RESULTADOS[$codigo][$mes]["I"]+=$aux_ingresos;//sumo los inpresos desde cajas
		//////////////////////////////////
		$sql=mysql_query($cons)or die("funcion".mysql_error);
		while($P=mysql_fetch_assoc($sql))
		{
			$movimiento=$P["movimiento"];
			$valor=$P["valor"];
			switch($movimiento)
			{
				case"I":
					$RESULTADOS[$codigo][$mes]["I"]+=$valor;
					break;
				case"E":
					$RESULTADOS[$codigo][$mes]["E"]+=$valor;
					break;	
			}
		}
		mysql_free_result($sql);
	}
	if(DEBUG){echo"<br>==============FIN_FUNCION===================<br>";}
	return($RESULTADOS);
}
?>
<table width="100%" border="1">
	<thead>
    <tr>
    	<th colspan="25"><div align="center">PRESUPUESTO <?php echo $sede;?>- <?php echo $year;?></div></th>
    </tr>
  <tr>
    <td width="1%" rowspan="2">&nbsp;</td>
    <td width="7%" colspan="2"><div align="center">Enero</div></td>
    <td width="9%" colspan="2"><div align="center">Febrero</div></td>
    <td width="7%" colspan="2"><div align="center">Marzo</div></td>
    <td width="6%" colspan="2"><div align="center">Abril</div></td>
    <td width="6%" colspan="2"><div align="center">Mayo</div></td>
    <td width="6%" colspan="2"><div align="center">Junio</div></td>
    <td width="5%" colspan="2"><div align="center">Julio</div></td>
    <td width="8%" colspan="2"><div align="center">Agosto</div></td>
    <td width="12%" colspan="2"><div align="center">Septiempre</div></td>
    <td width="9%" colspan="2"><div align="center">Octubre</div></td>
    <td width="12%" colspan="2"><div align="center">Noviembre</div></td>
    <td width="12%" colspan="2"><div align="center">Diciembre</div></td>
  </tr>
  <tr>
    <td><div align="center">I</div></td>
    <td><div align="center">E</div></td>
    <td><div align="center">I</div></td>
    <td><div align="center">E</div></td>
    <td><div align="center">I</div></td>
    <td><div align="center">E</div></td>
    <td><div align="center">I</div></td>
    <td><div align="center">E</div></td>
    <td><div align="center">I</div></td>
    <td><div align="center">E</div></td>
    <td><div align="center">I</div></td>
    <td><div align="center">E</div></td>
    <td><div align="center">I</div></td>
    <td><div align="center">E</div></td>
    <td><div align="center">I</div></td>
    <td><div align="center">E</div></td>
    <td><div align="center">I</div></td>
    <td><div align="center">E</div></td>
    <td><div align="center">I</div></td>
    <td><div align="center">E</div></td>
    <td><div align="center">I</div></td>
    <td><div align="center">E</div></td>
    <td><div align="center">I</div></td>
    <td><div align="center">E</div></td>
  </tr>
  </thead>
  <tbody>
  <?php
  $size="height=450&width=550";
  $rel='rel="sexylightbox"';
  if($continuar)
  {
  	foreach($ANUAL as $n => $valor)
	{
		echo'<tr>';
		foreach($valor as $codigoY =>$valorx)
		{
		
			////////////////
			$cons_nc="SELECT nombre FROM presupuesto_parametros WHERE codigo='$codigoY' AND sede='$sede' LIMIT 1";
			$sql_nc=mysql_query($cons_nc)or die(mysql_error());
			$D_nc=mysql_fetch_assoc($sql_nc);
			$nombre_codigo=$D_nc["nombre"];
			mysql_free_result($sql_nc);
			//////////////////
			///acumuladores
			$TOTALES_MES[1]["I"]+=$valorx[1]["I"];
			$TOTALES_MES[1]["E"]+=$valorx[1]["E"];
			$TOTALES_MES[2]["I"]+=$valorx[2]["I"];
			$TOTALES_MES[2]["E"]+=$valorx[2]["E"];
			$TOTALES_MES[3]["I"]+=$valorx[3]["I"];
			$TOTALES_MES[3]["E"]+=$valorx[3]["E"];
			$TOTALES_MES[4]["I"]+=$valorx[4]["I"];
			$TOTALES_MES[4]["E"]+=$valorx[4]["E"];
			$TOTALES_MES[5]["I"]+=$valorx[5]["I"];
			$TOTALES_MES[5]["E"]+=$valorx[5]["E"];
			$TOTALES_MES[6]["I"]+=$valorx[6]["I"];
			$TOTALES_MES[6]["E"]+=$valorx[6]["E"];
			$TOTALES_MES[7]["I"]+=$valorx[7]["I"];
			$TOTALES_MES[7]["E"]+=$valorx[7]["E"];
			$TOTALES_MES[8]["I"]+=$valorx[8]["I"];
			$TOTALES_MES[8]["E"]+=$valorx[8]["E"];
			$TOTALES_MES[9]["I"]+=$valorx[9]["I"];
			$TOTALES_MES[9]["E"]+=$valorx[9]["E"];
			$TOTALES_MES[10]["I"]+=$valorx[10]["I"];
			$TOTALES_MES[10]["E"]+=$valorx[10]["E"];
			$TOTALES_MES[11]["I"]+=$valorx[11]["I"];
			$TOTALES_MES[11]["E"]+=$valorx[11]["E"];
			$TOTALES_MES[12]["I"]+=$valorx[12]["I"];
			$TOTALES_MES[12]["E"]+=$valorx[12]["E"];
			//escritura de tabla
			echo'<td><div align="center" class="Estilo1"><a href="#" title="'.$nombre_codigo.'">'.$codigoY.'</a></div></td>
				<td><div align="center" class="Estilo1"><a href="detalle_mes.php?tipo=I&mes=1&year='.$year.'&sede='.$sede.'&codigo='.$codigoY.'&TB_iframe=true&'.$size.'" '.$rel.'>'.number_format($valorx[1]["I"],0,",",".").'</a></div></td>
				<td><div align="center" class="Estilo1"><a href="detalle_mes.php?tipo=E&mes=1&year='.$year.'&sede='.$sede.'&codigo='.$codigoY.'&TB_iframe=true&'.$size.'" '.$rel.'>'.number_format($valorx[1]["E"],0,",",".").'</a></div></td>
				<td><div align="center" class="Estilo1"><a href="detalle_mes.php?tipo=I&mes=2&year='.$year.'&sede='.$sede.'&codigo='.$codigoY.'&TB_iframe=true&'.$size.'" '.$rel.'>'.number_format($valorx[2]["I"],0,",",".").'</a></div></td>
				<td><div align="center" class="Estilo1"><a href="detalle_mes.php?tipo=E&mes=2&year='.$year.'&sede='.$sede.'&codigo='.$codigoY.'&TB_iframe=true&'.$size.'" '.$rel.'>'.number_format($valorx[2]["E"],0,",",".").'</a></div></td>
				<td><div align="center" class="Estilo1"><a href="detalle_mes.php?tipo=I&mes=3&year='.$year.'&sede='.$sede.'&codigo='.$codigoY.'&TB_iframe=true&'.$size.'" '.$rel.'>'.number_format($valorx[3]["I"],0,",",".").'</a></div></td>
				<td><div align="center" class="Estilo1"><a href="detalle_mes.php?tipo=E&mes=3&year='.$year.'&sede='.$sede.'&codigo='.$codigoY.'&TB_iframe=true&'.$size.'" '.$rel.'>'.number_format($valorx[3]["E"],0,",",".").'</a></div></td>
				<td><div align="center" class="Estilo1"><a href="detalle_mes.php?tipo=I&mes=4&year='.$year.'&sede='.$sede.'&codigo='.$codigoY.'&TB_iframe=true&'.$size.'" '.$rel.'>'.number_format($valorx[4]["I"],0,",",".").'</a></div></td>
				<td><div align="center" class="Estilo1"><a href="detalle_mes.php?tipo=E&mes=4&year='.$year.'&sede='.$sede.'&codigo='.$codigoY.'&TB_iframe=true&'.$size.'" '.$rel.'>'.number_format($valorx[4]["E"],0,",",".").'</a></div></td>
				<td><div align="center" class="Estilo1"><a href="detalle_mes.php?tipo=I&mes=5&year='.$year.'&sede='.$sede.'&codigo='.$codigoY.'&TB_iframe=true&'.$size.'" '.$rel.'>'.number_format($valorx[5]["I"],0,",",".").'</a></div></td>
				<td><div align="center" class="Estilo1"><a href="detalle_mes.php?tipo=E&mes=5&year='.$year.'&sede='.$sede.'&codigo='.$codigoY.'&TB_iframe=true&'.$size.'" '.$rel.'>'.number_format($valorx[5]["E"],0,",",".").'</a></div></td>
				<td><div align="center" class="Estilo1"><a href="detalle_mes.php?tipo=I&mes=6&year='.$year.'&sede='.$sede.'&codigo='.$codigoY.'&TB_iframe=true&'.$size.'" '.$rel.'>'.number_format($valorx[6]["I"],0,",",".").'</a></div></td>
				<td><div align="center" class="Estilo1"><a href="detalle_mes.php?tipo=E&mes=6&year='.$year.'&sede='.$sede.'&codigo='.$codigoY.'&TB_iframe=true&'.$size.'" '.$rel.'>'.number_format($valorx[6]["E"],0,",",".").'</a></div></td>
				<td><div align="center" class="Estilo1"><a href="detalle_mes.php?tipo=I&mes=7&year='.$year.'&sede='.$sede.'&codigo='.$codigoY.'&TB_iframe=true&'.$size.'" '.$rel.'>'.number_format($valorx[7]["I"],0,",",".").'</a></div></td>
				<td><div align="center" class="Estilo1"><a href="detalle_mes.php?tipo=E&mes=7&year='.$year.'&sede='.$sede.'&codigo='.$codigoY.'&TB_iframe=true&'.$size.'" '.$rel.'>'.number_format($valorx[7]["E"],0,",",".").'</a></div></td>
				<td><div align="center" class="Estilo1"><a href="detalle_mes.php?tipo=I&mes=8&year='.$year.'&sede='.$sede.'&codigo='.$codigoY.'&TB_iframe=true&'.$size.'" '.$rel.'>'.number_format($valorx[8]["I"],0,",",".").'</a></div></td>
				<td><div align="center" class="Estilo1"><a href="detalle_mes.php?tipo=E&mes=8&year='.$year.'&sede='.$sede.'&codigo='.$codigoY.'&TB_iframe=true&'.$size.'" '.$rel.'>'.number_format($valorx[8]["E"],0,",",".").'</a></div></td>
				<td><div align="center" class="Estilo1"><a href="detalle_mes.php?tipo=I&mes=9&year='.$year.'&sede='.$sede.'&codigo='.$codigoY.'&TB_iframe=true&'.$size.'" '.$rel.'>'.number_format($valorx[9]["I"],0,",",".").'</a></div></td>
				<td><div align="center" class="Estilo1"><a href="detalle_mes.php?tipo=E&mes=9&year='.$year.'&sede='.$sede.'&codigo='.$codigoY.'&TB_iframe=true&'.$size.'" '.$rel.'>'.number_format($valorx[9]["E"],0,",",".").'</a></div></td>
				<td><div align="center" class="Estilo1"><a href="detalle_mes.php?tipo=I&mes=10&year='.$year.'&sede='.$sede.'&codigo='.$codigoY.'&TB_iframe=true&'.$size.'" '.$rel.'>'.number_format($valorx[10]["I"],0,",",".").'</a></div></td>
				<td><div align="center" class="Estilo1"><a href="detalle_mes.php?tipo=E&mes=10&year='.$year.'&sede='.$sede.'&codigo='.$codigoY.'&TB_iframe=true&'.$size.'" '.$rel.'>'.number_format($valorx[10]["E"],0,",",".").'</a></div></td>
				<td><div align="center" class="Estilo1"><a href="detalle_mes.php?tipo=I&mes=11&year='.$year.'&sede='.$sede.'&codigo='.$codigoY.'&TB_iframe=true&'.$size.'" '.$rel.'>'.number_format($valorx[11]["I"],0,",",".").'</a></div></td>
				<td><div align="center" class="Estilo1"><a href="detalle_mes.php?tipo=E&mes=11&year='.$year.'&sede='.$sede.'&codigo='.$codigoY.'&TB_iframe=true&'.$size.'" '.$rel.'>'.number_format($valorx[11]["E"],0,",",".").'</a></div></td>
				<td><div align="center" class="Estilo1"><a href="detalle_mes.php?tipo=I&mes=12&year='.$year.'&sede='.$sede.'&codigo='.$codigoY.'&TB_iframe=true&'.$size.'" '.$rel.'>'.number_format($valorx[12]["I"],0,",",".").'</a></div></td>
				<td><div align="center" class="Estilo1"><a href="detalle_mes.php?tipo=E&mes=12&year='.$year.'&sede='.$sede.'&codigo='.$codigoY.'&TB_iframe=true&'.$size.'" '.$rel.'>'.number_format($valorx[12]["E"],0,",",".").'</a></div></td>
				</tr>';
			
		}
		
	}
  }
  else
  { echo'<tr><td colspan="25">Sin Datos</td></tr>';}
  unset($ANUAL);
  ?>
  </tbody>
  <tfoot>
  <tr>
  	<td><strong>TOTALES</strong></td>
    <td><div align="center"><strong><?php echo number_format($TOTALES_MES[1]["I"],0,",",".");?></strong></div></td>
    <td><div align="center"><strong><?php echo number_format($TOTALES_MES[1]["E"],0,",",".");?></strong></div></td>
    <td><div align="center"><strong><?php echo number_format($TOTALES_MES[2]["I"],0,",",".");?></strong></div></td>
    <td><div align="center"><strong><?php echo number_format($TOTALES_MES[2]["E"],0,",",".");?></strong></div></td>
    <td><div align="center"><strong><?php echo number_format($TOTALES_MES[3]["I"],0,",",".");?></strong></div></td>
    <td><div align="center"><strong><?php echo number_format($TOTALES_MES[3]["E"],0,",",".");?></strong></div></td>
    <td><div align="center"><strong><?php echo number_format($TOTALES_MES[4]["I"],0,",",".");?></strong></div></td>
    <td><div align="center"><strong><?php echo number_format($TOTALES_MES[4]["E"],0,",",".");?></strong></div></td>
    <td><div align="center"><strong><?php echo number_format($TOTALES_MES[5]["I"],0,",",".");?></strong></div></td>
    <td><div align="center"><strong><?php echo number_format($TOTALES_MES[5]["E"],0,",",".");?></strong></div></td>
    <td><div align="center"><strong><?php echo number_format($TOTALES_MES[6]["I"],0,",",".");?></strong></div></td>
    <td><div align="center"><strong><?php echo number_format($TOTALES_MES[6]["E"],0,",",".");?></strong></div></td>
    <td><div align="center"><strong><?php echo number_format($TOTALES_MES[7]["I"],0,",",".");?></strong></div></td>
    <td><div align="center"><strong><?php echo number_format($TOTALES_MES[7]["E"],0,",",".");?></strong></div></td>
    <td><div align="center"><strong><?php echo number_format($TOTALES_MES[8]["I"],0,",",".");?></strong></div></td>
    <td><div align="center"><strong><?php echo number_format($TOTALES_MES[8]["E"],0,",",".");?></strong></div></td>
    <td><div align="center"><strong><?php echo number_format($TOTALES_MES[9]["I"],0,",",".");?></strong></div></td>
    <td><div align="center"><strong><?php echo number_format($TOTALES_MES[9]["E"],0,",",".");?></strong></div></td>
    <td><div align="center"><strong><?php echo number_format($TOTALES_MES[10]["I"],0,",",".");?></strong></div></td>
    <td><div align="center"><strong><?php echo number_format($TOTALES_MES[10]["E"],0,",",".");?></strong></div></td>
    <td><div align="center"><strong><?php echo number_format($TOTALES_MES[11]["I"],0,",",".");?></strong></div></td>
    <td><div align="center"><strong><?php echo number_format($TOTALES_MES[11]["E"],0,",",".");?></strong></div></td>
    <td><div align="center"><strong><?php echo number_format($TOTALES_MES[12]["I"],0,",",".");?></strong></div></td>
    <td><div align="center"><strong><?php echo number_format($TOTALES_MES[12]["E"],0,",",".");?></strong></div></td>
  </tr>
  </tfoot>
</table>
<?php
if(isset($TOTALES_MES))
{
	unset($TOTALES_MES);
}
@mysql_close($conexion);
?>
</div>
</body>
</html>