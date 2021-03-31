<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
//-----------------------------------------//	

define("DEBUG",false);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-3" />
<title>caja</title>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:698px;
	height:115px;
	z-index:1;
	left: 3px;
	top: 55px;
}
#link {
	text-align: right;
	padding-right: 10px;
}
-->
</style>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
 
  <script type="text/javascript" src="../../libreria_publica/sexy_lightbox/jQuery/jquery.easing.1.3.js"></script>
  <script type="text/javascript" src="../../libreria_publica/sexy_lightbox/jQuery/sexylightbox.v2.3.jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/sexy_lightbox/jQuery/sexylightbox.css">
  <script type="text/javascript">
    $(document).ready(function(){
      SexyLightbox.initialize({color:'black', dir: '../../libreria_publica/sexy_lightbox/jQuery/sexyimages'});
    });
  </script>
<style type="text/css">
<!--
#apDiv2 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:2;
	left: 5%;
	top: 80px;
}
-->
</style>
<script src="../../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../SpryAssets/SpryCollapsiblePanel.css">
<style type="text/css">
<!--
.Estilo1 {
	font-size: 12px
}
a:link {
	color: #6699FF;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #6699FF;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #6699FF;
}
.Estilo2 {color: #000099}
-->
</style>
</head>
<?php
require("../../../funciones/conexion_v2.php");
include("../../../funciones/funcion.php");
$cod_userX=$_POST["usuario"];
$cons="SELECT nombre, apellido, sede FROM personal WHERE id='$cod_userX'";
				$sqlX=$conexion_mysqli->query($cons);
				$DA=$sqlX->fetch_assoc();
				$nombre=$DA["nombre"];
				$apellido=$DA["apellido"];
				$sede_user=$DA["sede"];
				$user=$nombre." ".$apellido;
$sqlX->free();
?>
<body>
<h1 id="banner">Administrador - Informe Caja </h1>

<div id="link">
  <br /> 
  <a href="index.php" class="button">Volver a Seleccion</a></div>
<div id="apDiv2">
<h3 align="center">Caja del Dia - Periodo (<?php echo fecha_format($_POST["fecha_ini"])." al ".fecha_format($_POST["fecha_fin"])?>)<br />
Usuario.: <?php echo $cod_userX." [$user]"; ?><br /><?php echo $sede_user;?></h3>
  <div id="CollapsiblePanel1" class="CollapsiblePanel">
    <div class="CollapsiblePanelTab Estilo1" tabindex="0">Detalle</div>
    <div class="CollapsiblePanelContent">
      <table width="100%" border="0">
   
<?php
if($_POST)
{
	//var_export($_POST);
	
	$fecha_ini=$_POST["fecha_ini"];
	$fecha_fin=$_POST["fecha_fin"];
	$cod_user=$_POST["usuario"];
	
	$array_tipo_movimiento=array("I"=>"ingreso", "E"=>"egreso");
	$array_forma_pago=array("efectivo", "cheque",);
	$contador_transacciones=0;
	
	foreach($array_tipo_movimiento as $mov => $valor_mov)
	{
		foreach($array_forma_pago as $m => $FP)
		{
			if($FP=="cheque")
			{
				$condicion=" AND movimiento='$mov' AND forma_pago IN('cheque', 'multi_cheque')";
			}
			else
			{
				$condicion=" AND movimiento='$mov' AND forma_pago='$FP'";
			}
			$cons="SELECT * FROM pagos WHERE cod_user='$cod_user' $condicion AND fechapago BETWEEN '$fecha_ini' AND '$fecha_fin' ORDER by idpago";
			//echo"---> $cons<br>";
			$sqlP=$conexion_mysqli->query($cons);
			$num_pagos=$sqlP->num_rows;
			
			$cabecera='<tr bgcolor="#e5e5e5">
						<td colspan="8"><strong>'.strtoupper($valor_mov).'</strong></td>
						</tr>
						<tr>
						<td align="center"><span class="Estilo2">ID Pago</span></td>
						<td align="center"><span class="Estilo2">N° Doc</span></td>
						<td align="center"><span class="Estilo2">Tipo Doc</span></td>
						<td align="center"><span class="Estilo2">Forma Pago</span></td>
						<td align="center"><span class="Estilo2">Por Concepto</span></td>
						<td align="center"><span class="Estilo2">Glosa</span></td>
						<td align="center"><span class="Estilo2">Valor</span></td>
						</tr>';
						
			if($mov!=$movimiento_old)
			{ echo $cabecera;}
			$movimiento_old=$mov;
			if($num_pagos>0)
			{
				while($DP=$sqlP->fetch_assoc())
				{
					$contador_x_tipo[$valor_mov][$FP]++;
					$contador_transacciones++;
					$id_pago=$DP["idpago"];
					$id_cuota=$DP["id_cuota"];
					$id_boleta=$DP["id_boleta"];
					$por_concepto=$DP["por_concepto"];
					$id_alumno=$DP["id_alumno"];
					$id_factura=$DP["id_factura"];
					$fecha_pago=$DP["fechapago"];
					$valor=$DP["valor"];
					$tipo_doc=$DP["tipodoc"];
					$glosa=$DP["glosa"];
					$sede=$DP["sede"];
					$movimiento=$DP["movimiento"];
					$forma_pago=$DP["forma_pago"];
					$fechaV_cheque=$DP["fechaV_cheque"];
					$id_cheque=$DP["id_cheque"];
					$semestre=$DP["semestre"];
					$year=$DP["year"];
					$aux_num_documento=$DP["aux_num_documento"];
					////////////////////////
					//echo"---->$por_concepto|<br>";
					switch($por_concepto)
					{
						case"arancel":
							$consultar_boleta=true;
							break;
						case"matricula":
							$consultar_boleta=true;
							break;
						case"otro_ingreso_2":
							$consultar_boleta=true;	
							break;
						default:
							$consultar_boleta=false;					
					}
					///consulto folio boleta///
					if($consultar_boleta)
					{
						$cons_bo="SELECT folio FROM boleta WHERE id='$id_boleta'";
						//echo"$cons_bo<br>";
						$sql_bo=$conexion_mysqli->query($cons_bo);
						$DBO=$sql_bo->fetch_assoc();
						$aux_folio=$DBO["folio"];
						$sql_bo->free();
						////////////////////////
						if(empty($boletas[$valor_mov][$FP]))
						{
							$boletas[$valor_mov][$FP].=$aux_folio;
						}
						else
						{
							$boletas[$valor_mov][$FP].=" - ".$aux_folio;
						}	
					}
					else
					{
						//echo"no consulto<br>";
						$aux_folio=$aux_num_documento;
					}	
					///////////-------////////
		
					if($id_cheque==0)
					{
						$id_cheque_label="---";
					}
					else
					{
						$id_cheque_label=$id_cheque;
					}
							
					echo'<tr>
					<td align="center"><em>'.$id_pago.'</em></td>
					<td align="center"><em>'.$id_boleta.'('.$aux_folio.')</em> ';
					if($consultar_boleta)
					{
						echo'<a href="../visor_boletas/ver_boleta.php?id_boleta='.base64_encode($id_boleta).'&id_alumno='.base64_encode($id_alumno).'&TB_iframe=true&height=300&width=470" rel="sexylightbox">Ver</a>';
					}
					echo'</td>
					<td align="center"><em>'.$tipo_doc.'</em></td>
					<td align="center"><em>'.$forma_pago.'</em></td>
					<td align="center"><em>'.$por_concepto.'</em></td>
					<td align="center"><em>'.$glosa.'</em></td>
					<td align="center"><em>$'.number_format($valor,0,",",".").'</em></td>
					</tr>';
					$aux_acumulador[$valor_mov][$FP]+=$valor;
				}//fin while
				
				if($aux_acumulador>0)
				{
					echo'<tr bgcolor="#f5f5f5">
						<td colspan="2">Subtotal '.$valor_mov.'('.$FP.')</td>
						<td colspan="5" align="right"><strong>$'.number_format($aux_acumulador[$valor_mov][$FP],0,",",".").'</strong></td>
						</tr>
						<tr><td colspan="7">&nbsp;</td></tr>';
				}
				
			}//fin si	
			$sqlP->free();
		}//fin foreach
		echo'<tr bgcolor="#f5f5f5">
			<td colspan="2"><strong>Total '.$valor_mov.'</strong></td>
			<td colspan="5" align="right"><strong>$'.number_format(@array_sum($aux_acumulador[$valor_mov]),0,",",".").'</strong></td>
			</tr>
			<tr><td colspan="7">&nbsp;</td></tr>';
	}//fin foreach
	
	echo'<tr bgcolor="#f5f5f5">
			<td colspan="3"><strong>Total (INGRESOS - EGRESOS)</strong></td>
			<td colspan="4" align="right"><strong>$'.number_format(@array_sum($aux_acumulador["ingreso"])-@array_sum($aux_acumulador["egreso"]),0,",",".").'</strong></td>
			</tr>
			<tr><td colspan="7">&nbsp;</td></tr>';
	
	$conexion_mysqli->close();
}
?>
</table>
  </div>
</div>
  <div id="CollapsiblePanel2" class="CollapsiblePanel">
    <div class="CollapsiblePanelTab Estilo1" tabindex="0">Resumen</div>
    <div class="CollapsiblePanelContent">
      <table width="100%" border="0">
        <tr>
          <td colspan="2" bgcolor="#e5e5e5"><strong>Cheque (Ingreso)</strong></td>
        </tr>
        <tr>
          <td width="44%" bgcolor="#f5f5f5">Boletas Emitidas</td>
          <td width="56%" bgcolor="#f5f5f5"><?php echo $boletas["ingreso"]["cheque"];?></td>
        </tr>
        <tr>
          <td bgcolor="#f5f5f5">Transacciones</td>
          <td bgcolor="#f5f5f5"><?php echo $contador_x_tipo["ingreso"]["cheque"];?></td>
        </tr>
        <tr>
          <td bgcolor="#f5f5f5">Total Cheque</td>
          <td bgcolor="#f5f5f5"><?php echo "$".number_format($aux_acumulador["ingreso"]["cheque"],0,",",".");?></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" bgcolor="#e5e5e5"><strong>Efectivo (Ingreso)</strong></td>
        </tr>
        <tr>
          <td bgcolor="#f5f5f5">Boletas Emitidas</td>
          <td bgcolor="#f5f5f5"><?php echo $boletas["ingreso"]["efectivo"];?></td>
        </tr>
        <tr>
          <td bgcolor="#f5f5f5">Transacciones</td>
          <td bgcolor="#f5f5f5"><?php echo $contador_x_tipo["ingreso"]["efectivo"];?></td>
        </tr>
        <tr>
          <td bgcolor="#f5f5f5">Total Efectivo</td>
          <td bgcolor="#f5f5f5"><?php echo "$".number_format($aux_acumulador["ingreso"]["efectivo"],0,",",".");?></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" bgcolor="#e5e5e5"><strong>Cheque (Egreso)</strong></td>
        </tr>
        <tr>
          <td bgcolor="#f5f5f5">Transacciones</td>
          <td bgcolor="#f5f5f5"><?php echo $contador_x_tipo["egreso"]["cheque"];?></td>
        </tr>
        <tr>
          <td bgcolor="#f5f5f5">Total</td>
          <td bgcolor="#f5f5f5"><?php echo "$".number_format($aux_acumulador["egreso"]["cheque"],0,",",".");?></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" bgcolor="#e5e5e5"><strong>Efectivo(Egreso)</strong></td>
        </tr>
        <tr>
          <td bgcolor="#f5f5f5">Transacciones</td>
          <td bgcolor="#f5f5f5"><?php echo $contador_x_tipo["egreso"]["efectivo"];?></td>
        </tr>
        <tr>
          <td bgcolor="#f5f5f5">Total</td>
          <td bgcolor="#f5f5f5"><?php echo "$".number_format($aux_acumulador["egreso"]["efectivo"],0,",",".");?></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td bgcolor="#e5e5e5"><strong>TOTAL EFECTIVO</strong><br />
          Ingreso(efectivo) - Egreso (efectivo)</td>
          <td bgcolor="#e5e5e5">
		  <?php 
		  if(isset($aux_acumulador["ingreso"]["efectivo"])){$ingreso_efectivo=$aux_acumulador["ingreso"]["efectivo"];}
		  else{ $ingreso_efectivo=0;}
		  
		   if(isset($aux_acumulador["egreso"]["efectivo"])){$egreso_efectivo=$aux_acumulador["egreso"]["efectivo"];}
		  else{ $egreso_efectivo=0;}
		  
		  
		  	$total_efectivo=($ingreso_efectivo-$egreso_efectivo);
		  	echo "$".number_format($total_efectivo,0,",",".");
		  ?>          </td>
        </tr>
        <tr>
          <td bgcolor="#e5e5e5"><strong>TOTAL CHEQUE</strong><br />
          ingreso (cheque) - Egreso (cheque)</td>
          <td bgcolor="#e5e5e5">
		  <?php 
		  if(isset($aux_acumulador["ingreso"]["cheque"])){ $ingreso_cheque=$aux_acumulador["ingreso"]["cheque"];}
		  else{ $ingreso_cheque=0;}
		  
		  if(isset($aux_acumulador["egreso"]["cheque"])){ $egreso_cheque=$aux_acumulador["egreso"]["cheque"];}
		  else{ $egreso_cheque=0;}
		  
		  
		  
		  	$total_cheque=($ingreso_cheque-$egreso_cheque);
		  	echo "$".number_format($total_cheque,0,",",".");
		  ?>          </td>
        </tr>
        <tr>
          <td bgcolor="#e5e5e5"><strong>SALDO CAJA</strong><br />
            total(efectivo) + total(Cheque)</td>
          <td bgcolor="#e5e5e5"><?php echo"$".number_format(($total_efectivo + $total_cheque),0,",",".");?></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="center">_______________________</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="center">V.B FINANZAS </td>
        </tr>
      </table>
    </div>
  </div>
</div>
<script type="text/javascript">
<!--
var CollapsiblePanel1 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel1");
var CollapsiblePanel2 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel2");
//-->
</script>
</body>
</html>
