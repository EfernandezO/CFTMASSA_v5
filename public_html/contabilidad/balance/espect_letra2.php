<?php require ("../../SC/seguridad.php");?>
<?php require ("../../SC/privilegio2.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Proyecciones</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<script src="../../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../SpryAssets/SpryCollapsiblePanel.css">
<style type="text/css">
<!--
.Estilo1 {font-weight: bold}
#link {
	text-align: right;
	padding-right: 10px;
}
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
</head>

<body>
<h1 id="banner">Proyecciones - Ingresos </h1>

<div id="link"><a href="espect_letra.php">Volver a la Seleccion</a></div>
<?php
 if($_POST)
 {   
	
	include("../../../funciones/funcion.php");
	include("../../../funciones/conexion.php");
	extract($_POST);
	$aux=0;
	$total=0;
	$j=0;
	$Mes[1]="Enero";
	$Mes[2]="Febrero";
	$Mes[3]="Marzo";
	$Mes[4]="Abril";
	$Mes[5]="Mayo";
	$Mes[6]="Junio";
	$Mes[7]="Julio";
	$Mes[8]="Agosto";
	$Mes[9]="Septiembre";
	$Mes[10]="Octubre";
	$Mes[11]="Noviembre";
	$Mes[12]="Diciembre";
	$color3 = "#E0FAC5";
	
	$opcion_M=str_inde($opcion_M);
	$ano_BB=str_inde($ano_BB);
	$fsede=str_inde($fsede);
	///////
	$tipo_pago="cuota";
	//echo"$opcion_M<br>";

	if($opcion_M=="T")
	{
	echo'
<div id="Layer2"><br />
<br>
</br>
  <table width="467" border="0">
  <tr>
  <td colspan="3" align="center" bgcolor="#e5e5e5"><strong>Proyecciones '.$ano_BB.'</strong></td>
  </tr>
    <tr>
      <td width="156" bgcolor="#CCFF00"><div align="center"><strong>Mes</strong></div></td>
      <td width="142" bgcolor="#CCFF00"><div align="center"><strong>Ingresos Esperados</strong></div></td>
	  <td width="155" bgcolor="#CCFF00"><div align="center"><strong>Pagos Realizados</strong></div></td>
    </tr>';
	for($mes=1;$mes<=12;$mes++)
	{
	    $primer_dia_mes=fecha_mysql(false,"1/$mes/$ano_BB");
		switch ($mes)
		{
		       case 1:

			        $ultimo_dia_mes=fecha_mysql(false,"31/$mes/$ano_BB");
					break;
			   case 2:
			         $ultimo_dia_mes=fecha_mysql(false,"28/$mes/$ano_BB");
					 break;
			   case 3:
			         $ultimo_dia_mes=fecha_mysql(false,"31/$mes/$ano_BB");
					 break;
			   case 4:
			         $ultimo_dia_mes=fecha_mysql(false,"30/$mes/$ano_BB");
					 break;
			   case 5:
			        $ultimo_dia_mes=fecha_mysql(false,"31/$mes/$ano_BB");
					break;
			   case 6:
			         $ultimo_dia_mes=fecha_mysql(false,"30/$mes/$ano_BB");
					 break;
			   case 7:
			         $ultimo_dia_mes=fecha_mysql(false,"31/$mes/$ano_BB");
					 break;
			   case 8:
			         $ultimo_dia_mes=fecha_mysql(false,"31/$mes/$ano_BB");
					 break;
			   case 9:
			         $ultimo_dia_mes=fecha_mysql(false,"30/$mes/$ano_BB");
					 break;
			   case 10:
			         $ultimo_dia_mes=fecha_mysql(false,"31/$mes/$ano_BB");
					 break;
			   case 11:
			         $ultimo_dia_mes=fecha_mysql(false,"30/$mes/$ano_BB");
					 break;
			   case 12:
			         $ultimo_dia_mes=fecha_mysql(false,"31/$mes/$ano_BB");
					 break;
		}			 
		$cons="SELECT SUM(valor) FROM letras WHERE anulada='N' and sede='$fsede' and fechavenc BETWEEN '$primer_dia_mes' and '$ultimo_dia_mes'";
		//saco la condicion "ano='$ano_BB'" solo condiciono por fecha de vencimiento de letra/cuota
		
		//echo"ciclo-->$cons<br>";
		
		$consP="SELECT SUM(valor) FROM pagos WHERE movimiento='I' and tipodoc='$tipo_pago' and sede='$fsede' and fechapago BETWEEN '$primer_dia_mes' and '$ultimo_dia_mes'";
		
		$consXXX='SELECT alumno.rut, alumno.nombre, carrera.carrera, letras.valor, letras.deudaXletra
FROM (alumno INNER JOIN carrera ON alumno.carrera = carrera.carrera) INNER JOIN letras ON alumno.id = letras.idalumn
WHERE (((carrera.carrera)="Programación Computacional"));';
		
		$sqlP=mysql_query($consP) or die("Error Pagos: <br>".mysql_error());
		$sql=mysql_query($cons) or die("Error: <br>".mysql_error());
		$A_mes = mysql_fetch_row($sql); 
	    $t_mes = $A_mes[0];
		$A_pagos=mysql_fetch_row($sqlP);
		$t_pagos=$A_pagos[0];
		
		if($t_mes=="")
		{
			$t_mes=0;
		}
		if($t_pagos=="")
		{
			$t_pagos=0;
		}
		$total+=$t_mes;
		$total_ing+=$t_pagos;
		if($aux%2==0)
				{
					$color="#D5E7FB";
				}
				else
				{
					$color="#E8F2FC";
				}
				$aux++;
				
					echo " <tr align=\"center\" style=\"background-color:$color\" onMouseOver=\"this.style.backgroundColor='$color3'\" onMouseOut=\"this.style.backgroundColor='$color'\" >";
		echo' 
      		<td>'.$Mes[$mes].'</td>
      		<td><div align="right">$ '.number_format($t_mes,0,",",".").'</div></td>
			<td><div align="right">$ '.number_format($t_pagos,0,",",".").'</div></td>
    		</tr>';
			$dato1[$j]=$t_mes;
			$dato2[$j]=$t_pagos;
			$j++;
		
	}
	$dato1=serialize($dato1);
	$dato2=serialize($dato2);
	$_SESSION["valor1"]=$dato1;
	$_SESSION["valor2"]=$dato2;
	//echo"->$dato1 ++ $dato2<-";
	echo'<tr>
			<td bgcolor="#CEFF00"><strong>Total:</strong></td>
			<td bgcolor="#CEFF00"><div align="center" class="Estilo1">$ '.number_format($total,0,",",".").'</div></td>
			<td bgcolor="#CEFF00">&nbsp;</td>
  </tr>
  <tr>
  <td bgcolor="#CEFF00"><strong>Total:</strong></td>
			<td bgcolor="#CEFF00"><div align="center" class="Estilo1">&nbsp;</div></td>
			<td bgcolor="#CEFF00" align="center"><div align="center"><strong>$ '.number_format($total_ing,0,",",".").'</strong></div></td>
	</tr>		
  </table>
  <br><br>
<div id="CollapsiblePanel1" class="CollapsiblePanel">
  <div class="CollapsiblePanelTab" tabindex="0">Ver Grafico</div>
  <div class="CollapsiblePanelContent">';
 //aqui revisar rutas al subir
include_once "../../Graf/ofc/php-ofc-library/open_flash_chart_object.php";
$hostX=$_SERVER['HTTP_HOST'];
$hostX='localhost/CFTMASSA/www/';
open_flash_chart_object(465, 260, "http://".$hostX."/contabilidad/balance/data_graf/data1.php",false, "../../Graf/ofc/");
echo'  </div>
</div>
</div>';

	}
	else
	{
	    echo'
<div id="Layer2">
  <table width="464" border="0">
   <tr>
  <td colspan="3" align="center" bgcolor="#e5e5e5"><strong>Proyecciones '.$ano_BB.'</strong></td>
  </tr>
    <tr>
      <td width="172" bgcolor="#CCFF00"><div align="center"><strong>Mes</strong></div></td>
      <td width="145" bgcolor="#CCFF00"><div align="center"><strong>Ingresos Esperados</strong></div></td>
      <td width="133" bgcolor="#CCFF00"><div align="center"><strong>Pagos Realizados</strong></div></td>
    </tr>';
		$primer_dia_mes=fecha_mysql(false,"1/$opcion_M/$ano_BB");
		switch ($opcion_M)
		{
		       case 1:

			        $ultimo_dia_mes=fecha_mysql(false,"31/$opcion_M/$ano_BB");
					break;
			   case 2:
			         $ultimo_dia_mes=fecha_mysql(false,"28/$opcion_M/$ano_BB");
					 break;
			   case 3:
			         $ultimo_dia_mes=fecha_mysql(false,"31/$opcion_M/$ano_BB");
					 break;
			   case 4:
			         $ultimo_dia_mes=fecha_mysql(false,"30/$opcion_M/$ano_BB");
					 break;
			   case 5:
			        $ultimo_dia_mes=fecha_mysql(false,"31/$opcion_M/$ano_BB");
					break;
			   case 6:
			         $ultimo_dia_mes=fecha_mysql(false,"30/$opcion_M/$ano_BB");
					 break;
			   case 7:
			         $ultimo_dia_mes=fecha_mysql(false,"31/$opcion_M/$ano_BB");
					 break;
			   case 8:
			         $ultimo_dia_mes=fecha_mysql(false,"31/$opcion_M/$ano_BB");
					 break;
			   case 9:
			         $ultimo_dia_mes=fecha_mysql(false,"30/$opcion_M/$ano_BB");
					 break;
			   case 10:
			         $ultimo_dia_mes=fecha_mysql(false,"31/$opcion_M/$ano_BB");
					 break;
			   case 11:
			         $ultimo_dia_mes=fecha_mysql(false,"30/$opcion_M/$ano_BB");
					 break;
			   case 12:
			         $ultimo_dia_mes=fecha_mysql(false,"31/$opcion_M/$ano_BB");
					 break;
		}
		$cons="SELECT SUM(valor) FROM letras WHERE anulada='N' and sede='$fsede' and fechavenc BETWEEN '$primer_dia_mes' and '$ultimo_dia_mes'";
		//saco ano como condicion y solo utilizo fecha de vencimiento de letra
		
		$consP="SELECT SUM(valor) FROM pagos WHERE movimiento='I' and tipodoc='L' and sede='$fsede' and fechapago BETWEEN '$primer_dia_mes' and '$ultimo_dia_mes'";
		//echo"ini-->$cons<br>";
		//echo"-->$consP<br>";
		$sqlP=mysql_query($consP) or die("Error Pagos: <br>".mysql_error());
		$sql=mysql_query($cons) or die("Error Letras: <br>".mysql_error());
		$A_mes = mysql_fetch_row($sql); 
	    $t_mes = $A_mes[0];
		$A_pagos=mysql_fetch_row($sqlP);
		$t_pagos=$A_pagos[0];
		
		if($t_mes=="")
		{
			$t_mes=0;
		}	
		if($t_pagos=="")
		{
			$t_pagos=0;
		}
		$total+=$t_mes;
		if($aux%2==0)
				{
					$color="#D5E7FB";
				}
				else
				{
					$color="#E8F2FC";
				}
				$aux++;
				
					echo " <tr align=\"center\" style=\"background-color:$color\" onMouseOver=\"this.style.backgroundColor='$color3'\" onMouseOut=\"this.style.backgroundColor='$color'\" >";
		echo' 
      		<td>'.$Mes[$opcion_M].'</td>
      		<td><div align="right">$ '.number_format($t_mes,0,",",".").'</div></td>
			<td><div align="right">$ '.number_format($t_pagos,0,",",".").'</div></td>
    		</tr>
			<tr>
			<td bgcolor="#CEFF00"><strong>Total :</strong></td>
			<td bgcolor="#CEFF00"><div align="center" class="Estilo1">
			  <div align="right">$ '.number_format($total,0,",",".").'</div>
			</div></td>
			<td bgcolor="#CEFF00" align="center"><div align="right"></div></td>
			</tr>
			<tr>
			  <td bgcolor="#CEFF00"><strong>Total : </strong></td>
			  <td bgcolor="#CEFF00">&nbsp;</td>
			  <td bgcolor="#CEFF00" align="center"><div align="right"><strong>$ '.number_format($t_pagos,0,",",".").'</strong></div></td>
    </tr>
  </table>
</div>';
		
	}
	mysql_free_result($sql);
	mysql_close($conexion);
}	
?>
<script type="text/javascript">
<!--
var CollapsiblePanel1 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel1", {contentIsOpen:false});
//-->
</script>
</body>
</html>
