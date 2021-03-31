<?php require("../../SC/seguridad.php");?>
<?php require("../../SC/privilegio2.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Generador de Balances</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla.css">
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:1;
	left: 201px;
	top: 163px;
}
#Layer2 {
	position:absolute;
	width:765px;
	height:115px;
	z-index:1;
	left: 21px;
	top: 177px;
}
#Layer3 {
	position:absolute;
	width:635px;
	height:158px;
	z-index:1;
	left: 102px;
	top: 71px;
}
#Layer4 {
	position:absolute;
	width:177px;
	height:21px;
	z-index:1;
	left: 253px;
	top: 310px;
}
#Layer5 {
	position:absolute;
	width:121px;
	height:20px;
	z-index:1;
	left: 89px;
	top: 312px;
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
.Estilo10 {font-size: 10px; font-weight: bold; }
.Estilo11 {font-size: 10px; }
.Estilo12 {font-size: 12px}
#apDiv1 {
	position:absolute;
	width:325px;
	height:115px;
	z-index:2;
}
.Estilo14 {font-size: 12px; font-weight: bold; }
-->
</style>
</head>

<body>
<h1 id="banner">Administrador - Balance </h1>
<?php
	if($_POST)
	{
		//extract($_POST);
		/*foreach($_POST as $n => $valor)
		{
			echo"$n -> $valor<br>";
		}*/
		
		$array_meses=array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
		
		$fecha_corte=$_POST["fecha_corte"];
		$ftipo_doc=$_POST["ftipo_doc"];
		$ftipo_cons=$_POST["ftipo_cons"];
		$fsede=$_POST["fsede"];
		$fmuestra=$_POST["fmuestra"];
		
		$desarma_fecha=explode("-",$fecha_corte);
		//var_export($desarma_fecha);
		$year=$desarma_fecha[0];
		$mes=$desarma_fecha[1];
		$dia=$desarma_fecha[2];
		
		//var_export($array_meses);
		$mes_palabra=$array_meses[$mes-1];
		//echo"----> $ftipo_cons<br>";
		switch($ftipo_cons)
		{
			case"D":
				$tituloX="de $fsede Periodo (Dia: $fecha_corte)";
				break;
			case"M":
				$tituloX="de $fsede del Periodo ($mes_palabra del $year)";
				break;
			case"A":
				$tituloX="de $fsede del Periodo (Año $year)";
				break;	
		}
		
		
		include("../../../funciones/funcion.php");
		include("../../../funciones/funcion2.php");
		include("../../../funciones/conexion.php");
		//valido fecha si no utilizo la actual
		
		$fecha=$fecha_corte;
		//var_export($_POST);
		list($ingreso,$egreso)=ingresos_egresos($ftipo_cons,$fecha,$ftipo_doc,$fsede);
		
		//var_export($ingreso);
		//var_export($egreso);
		if($fmuestra=="Det")
		{
			$color3 = "#E0FAC5";
			
			?>
               <div id="apDiv1"> 
                <table width="100%" border="1">
              <tr>
                <td colspan="2"><span class="Estilo14">Ingresos - Egresos</span></td>
                </tr>
              <tr>
                <td width="25%"><span class="Estilo12">Sede</span></td>
                <td width="75%"><?php echo $fsede;?></td>
              </tr>
              <tr>
                <td><span class="Estilo12">Fecha Corte</span></td>
                <td><?php echo $fecha_corte;?></td>
              </tr>
              <tr>
                <td><span class="Estilo12">Duracion</span></td>
                <td><?php echo $tituloX;?></td>
              </tr>
            </table>
</div>
             

			<div id="Layer2">
  <table sumary>
  <caption></caption>
  			<thead>
  			<tr>
			<th colspan="8" align="center" scope="col"><span class="Estilo10">Detalle de Transacciones <?php echo $tituloX;?></span></th>
			</tr>
			</thead>
   			 <tr>
      			<td width="105" bgcolor="#CCFF00"><div align="center" class="Estilo10">N&ordm; Boleta </div></td>
   			   <td width="143" bgcolor="#CCFF00"><div align="center" class="Estilo10">Fecha de Movimiento </div></td>
      			<td width="79" bgcolor="#CCFF00"><div align="center" class="Estilo10">Valor</div></td>
      			<td width="117" bgcolor="#CCFF00"><div align="center" class="Estilo10">Tipo Documento </div></td>
                <td width="117" bgcolor="#CCFF00"><div align="center" class="Estilo10">Forma de Pago</div></td>
   			   <td width="144" bgcolor="#CCFF00"><div align="center" class="Estilo10">Glosa</div></td>
   			   <td width="129" bgcolor="#CCFF00"><div align="center" class="Estilo10">Tipo Movimiento </div></td>
                 <td width="129" bgcolor="#CCFF00"><div align="center" class="Estilo10">Por Concepto</div></td>
</tr><?php
			//*********Escribo Egresos*********
			$n=count($egreso["id_pago"]);
			echo"<b>*Numero de Egresos :$n</b><br>";
			$Total_egreso=0;
			for($x=0;$x<$n;$x++)
			{
				 $eid_boleta=$egreso["id_boleta"][$x];
			 	$efechapago=$egreso["fechapago"][$x];
			 	$evalor=$egreso["valor"][$x];
			 	$etipo_doc=$egreso["tipodoc"][$x];
				$eforma_pago=$egreso["forma_pago"][$x];
			 	$eglosa=$egreso["glosa"][$x];
			 	$emovimiento=$egreso["movimiento"][$x];
			 	$epor_concepto=$egreso["por_concepto"][$x];
				 $Total_egreso+=$evalor;
			 	
				$eaux_num_documento=$egreso["aux_num_documento"][$x];
				 //arreglo valores
			 
			 
			 	if($emovimiento=="E")
			 	{
			 		$emovimiento="Egreso";
			 	}
				
				if($x%2==0)
				{
					$color="#D5E7FB";
				}
				else
				{
					$color="#E8F2FC";
				}
				
					?> <tr align="center" style="background-color:<?php echo $color;?>" onMouseOver="this.style.backgroundColor='<?php echo $color3; ?>'" onMouseOut="this.style.backgroundColor='<?php echo $color;?>'" >
					
      					<td><div align="center" class="Estilo11"><?php echo "$eid_boleta";?></div></td>
      					<td><div align="center" class="Estilo11"><?php echo fecha_format($efechapago);?></div></td>
      					<td><div align="center" class="Estilo11">$ <?php echo number_format($evalor,0,",",".");?></div></td>
      					<td><div align="center" class="Estilo11"><?php echo $etipo_doc;?><br /><?php echo "[$eaux_num_documento]";?></div></td>
                        <td><div align="center" class="Estilo11"><?php echo $eforma_pago;?></div></td>
	  <td><div align="center" class="Estilo11"><?php echo $eglosa;?></div></td>
	  <td><div align="center" class="Estilo11"><?php echo $emovimiento;?></div></td>
                        <td><div align="center" class="Estilo11"><?php echo $epor_concepto;?></div></td>
    					</tr>
						<?php
			 
			}
			?>
			
			<tr bgcolor="#CCCCCC">
				<td ><span class="Estilo11">Total Egreso:</span></td>
			  <td colspan="7"><div align="right" class="Estilo11">$<?php echo number_format($Total_egreso,0,",",".");?></div></td>
	</tr><tr><td colspan="7"><span class="Estilo11"></span></td>
				</tr>
				<?php
			//***********FIN Escribo Egreso******
		
			//***********Escribo Ingreso*********
			$m=count($ingreso[id_pago]);
			
			echo"<b>*Numero de Ingresos :$m</b><br>";
			
			?><tr>
      			<td width="105" bgcolor="#CEFF00"><div align="center" class="Estilo11"><strong>N&ordm; de Boleta </strong></div></td>
   			  <td width="143" bgcolor="#CEFF00"><div align="center" class="Estilo11"><strong>Fecha de Movimiento </strong></div></td>
      			<td width="79" bgcolor="#CEFF00"><div align="center" class="Estilo11"><strong>Valor</strong></div></td>
      			<td width="117" bgcolor="#CEFF00"><div align="center" class="Estilo11"><strong>Tipo Documento </strong></div></td>
                <td width="117" bgcolor="#CEFF00"><div align="center" class="Estilo11"><strong>Forma de Pago</strong></div></td>
   			  <td width="144" bgcolor="#CEFF00"><div align="center" class="Estilo11"><strong>Glosa</strong></div></td>
   			  <td width="129" bgcolor="#CEFF00"><div align="center" class="Estilo11"><strong>Tipo Movimiento</strong></div></td>
              <td width="129" bgcolor="#CEFF00"><div align="center" class="Estilo11"><strong>Por Concepto</strong></div></td>
</tr><?php
		
			$Total_ingreso=0;
			for($x=0;$x<$m;$x++)
			{
				 $iid_boleta=$ingreso[id_boleta][$x];
				 ////////////////Busco folio de Boleta////////////////////////////
				 $cons_bo="SELECT folio FROM boleta WHERE id='$iid_boleta'";
				 $sql_bo=mysql_query($cons_bo)or die("boleta ".mysql_error());
				 $DB=mysql_fetch_assoc($sql_bo);
				 $aux_folio=$DB["folio"];
				 mysql_free_result($sql_bo);
				//////////////////////--------------------////////////////////////
			 	$ifechapago=$ingreso["fechapago"][$x];
			 	$ivalor=$ingreso["valor"][$x];
			 	$itipo_doc=$ingreso["tipodoc"][$x];
				$iforma_pago=$ingreso["forma_pago"][$x];
				//echo"-> $iforma_pago<br>";
			 	$iglosa=$ingreso["glosa"][$x];
			 	$imovimiento=$ingreso["movimiento"][$x];
			 	$ipor_concepto=$ingreso["por_concepto"][$x];
			 	$Total_ingreso+=$ivalor;
				
				$iaux_num_documento=$ingreso["aux_num_documento"][$x];
			 
			 	//arreglo valores
			 
			 	if($imovimiento=="I")
			 	{
			 		$imovimiento="Ingreso";
			 	}
				if($x%2==0)
				{
					$color="#D5E7FB";
				}
				else
				{
					$color="#E8F2FC";
				}
			 	
					?> <tr align="center" style="background-color:<?php echo $color;?>" onMouseOver="this.style.backgroundColor='<?php echo $color3; ?>'" onMouseOut="this.style.backgroundColor='<?php echo $color;?>'">
      					<td><div align="center" class="Estilo11"><?php echo "$iid_boleta($aux_folio)";?></div></td>
      					<td><div align="center" class="Estilo11"><?php echo fecha_format($ifechapago);?></div></td>
      					<td><div align="center" class="Estilo11">$ <?php echo number_format($ivalor,0,",",".");?></div></td>
      					<td><div align="center" class="Estilo11"><?php echo $itipo_doc;?></div></td>
                        <td><div align="center" class="Estilo11"><?php echo $iforma_pago;?></div></td>
	  <td><div align="center" class="Estilo11"><?php echo $iglosa; ?></div></td>
	  <td><div align="center" class="Estilo11"><?php echo $imovimiento;?></div></td>
                        <td><div align="center" class="Estilo11"><?php echo $ipor_concepto;?></div></td>
    					</tr><?php
			}
			?>
			<tfoot>
<tr bgcolor="#CCCCCC">
				<td ><span class="Estilo11">Total Ingreso:</span></td>
		<td colspan="7" bgcolor="#CCCCCC"><div align="right"><span class="Estilo11">$<?php echo number_format($Total_ingreso,0,",",".");?></span></div></td>
			  </tr>
				<?php
			//********* FIN escribe Ingreso**********
		
			$saldo_caja=($Total_ingreso-$Total_egreso);
			if($saldo_caja>0)
			{
				?><tr bgcolor="#CCFF00">
				 <td><span class="Estilo11"><strong>Saldo en Caja</strong></span></td>
				 <td><span class="Estilo11"><strong>$<?php echo number_format($saldo_caja,0,",",".");?></strong></span></td>
				 </tr><?php
			}
			else
			{
				?><tr bgcolor="#FF9933">
				 <td><span class="Estilo11"><strong>Saldo en Caja</strong></span></td>
				 <td><span class="Estilo11"></span></td>
				 <td><span class="Estilo11"><strong>$<?php echo number_format($saldo_caja,0,",",".");?></strong></span></td>
				 </tr><?php
			}	 
			?>
			</tfoot>
			 </table>
</div>
<?php
		}
		else
		{
		    $Total_ingreso=0;
			$Total_egreso=0;
			$n_egreso=count($egreso[valor]);
			$n_ingreso=count($ingreso[valor]);
			if($n_egreso>0)
			{
				$Total_egreso=array_sum($egreso[valor]);
			}
			if($n_ingreso>0)
			{
				$Total_ingreso=array_sum($ingreso[valor]);
			}
			$saldo_caja=($Total_ingreso-$Total_egreso);
			echo'<div id="Layer3">
  <table width="651" height="142" border="0">
  <tr>
  <td colspan="2" align="center" bgcolor="#f5f5f5"><span class="Estilo12"><strong>Resumen de Transacciones '.$tituloX.'</strong></span></td>
  </tr>
	<tr>
	  <td width="140" bgcolor="#e5e5e5"><div align="center" class="Estilo12"><strong>Total Ingreso </strong></div></td>
	  <td width="146" bgcolor="#e5e5e5"><div align="center" class="Estilo12"><strong>Total Egreso </strong></div></td>
	</tr>
   			 <tr>
     		 <td bgcolor="#f5f5f5"><span class="Estilo12">$'.number_format($Total_ingreso,0,",",".").'</span></td>
     		 <td bgcolor="#f5f5f5">&nbsp;</td>
    </tr>
    			<tr>
     		 <td height="22" bgcolor="#f5f5f5">&nbsp;</td>
     		 <td bgcolor="#f5f5f5"><span class="Estilo12">$'.number_format($Total_egreso,0,",",".").'</span></td>
	</tr>';
				if($saldo_caja>0)
				{
					echo'
					<tr bgcolor="#33FF66">
					<td bgcolor="#99CC66"><span class="Estilo12"><strong>$'.number_format($saldo_caja,0,",",".").'</strong></span></td>
					<td bgcolor="#99CC66"><span class="Estilo12"><strong>Saldo en Caja</strong></span></td>';	
				}
				else
				{
					echo'
					<tr bgcolor="#FF0000">
					<td bgcolor="#FF3333"><span class="Estilo12"><strong>Saldo en Caja</strong></span></td>
					<td bgcolor="#FF3333"><span class="Estilo12"><strong>$'.number_format($saldo_caja,0,",",".").'</strong></span></td>';
				}
				echo'</tr></table></div>';
		}
		
	}

?>
</body>
</html>