<?php 
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_pagos_X_Rango_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	set_time_limit(6000);
	if(DEBUG){error_reporting(E_ALL); ini_set("display_errors", 1);}

if($_POST)
{
	if(DEBUG){ var_export($_POST);}
	$fecha_inicio=$_POST["fecha_inicio"];
	$fecha_fin=$_POST["fecha_fin"];
	$sede=$_POST["fsede"];
	$movimiento=$_POST["movimiento"];
	
	$A=busca_pagos($fecha_inicio, $fecha_fin, $sede,$movimiento);
	
	$pagos=$A[0];
	$titulo=$A[1];
	
	//------------------------------------------//
	require("../../../funciones/VX.php");
	$evento="Revisa Informe Pagos X Rango de Fechas periodo[$fecha_inicio - $fecha_fin] sede: $sede movimiento $movimiento";
	REGISTRA_EVENTO($evento);
	//--------------------------------------------//
	
}

else
{
	echo"No Post<br>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<title>Detalle de pagos</title>
<style type="text/css" title="currentStyle">
			@import "../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/css/demo_page.css";
			@import "../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/css/demo_table_jui.css";
			@import "../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/examples/examples_support/themes/smoothness/jquery-ui-1.8.4.custom.css";
</style>
<style type="text/css">
#pagos {
	position:absolute;
	width:96%;
	height:115px;
	z-index:2;
	left: 2%;
	top: 109px;
}
#total {
	position:absolute;
	width:200px;
	height:115px;
	z-index:1;
	left: 423px;
	top: 233px;
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
#pagos #cheques {
	padding-top: 20px;
}
-->
</style>
<script type="text/javascript" language="javascript" src="../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="ISO-8859-1">
			$(document).ready(function() {
				oTable = $('#example').dataTable({
					"bJQueryUI": true,
					"bPaginate": false
				});
			} );
		</script>
</head>

<body>
<h1 id="banner">Administrador - Informe Caja por Periodo</h1>

<div id="link"><br />
<a href="index.php" class="button">Volver a Selecci&oacute;n</a><br /><br />
<a href="busca_pagos_XLS.php?fecha_inicio=<?php echo base64_encode($fecha_inicio);?>&fecha_fin=<?php echo base64_encode($fecha_fin);?>&sede=<?php echo base64_encode($sede);?>&movimiento=<?php echo base64_encode($movimiento);?>" class="button_R" target="_blank">Xls</a></div>
<?php
//////////////////////////////
function busca_pagos($fecha_inicio, $fecha_fin, $sede, $movimiento_consulta)
{
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	switch($privilegio)
	{
		case"admi_total":
			$mostrar_fecha_generacion=true;
			break;
		case"matricula":	
			$mostrar_fecha_generacion=false;
			break;
		case"inspeccion":
			$mostrar_fecha_generacion=true;
			break;	
		default:
			$mostrar_fecha_generacion=false;	
			//echo"--->$mostrar_aviso_saldo_anterior";
	}
	
	require("../../../funciones/conexion_v2.php");
	include("../../../funciones/funcion.php");
	require("../../../funciones/funciones_sistema.php");
	
	$borde=0;
	$img_ok='<img src="../../BAses/Images/ok.png" width="10" height="10"/>';
		$tipo_documento="";
		if(DEBUG){ echo"movimiento-> $movimiento_consulta<br>";}
		if($movimiento_consulta!="todos"){$condicion1="AND movimiento='$movimiento_consulta'";}
		else{$condicion1="";}
		
		if($sede=="todas"){ $condicion_sede="";}
		else{ $condicion_sede=" AND sede='$sede'";}
		
		if($movimiento_consulta=="todos"){ $movimiento_label="Movimientos";}
		elseif($movimiento_consulta=="I"){ $movimiento_label="Ingresos";}
		else{ $movimiento_label="Egresos";}
	
		$consP="SELECT * FROM pagos WHERE fechapago BETWEEN '$fecha_inicio' AND '$fecha_fin' $condicion_sede $condicion1 ORDER by fechapago, idpago";
		if(DEBUG){ echo "<br>$consP<br>";}
		$sqlP=$conexion_mysqli->query($consP)or die($conexion_mysqli->error);
		$cuenta_pag=$sqlP->num_rows;
		$html_tabla='<table border="'.$borde.'" width="80%" class="display" id="example">
				<thead>
				<th>-</th>
				<th>Cod.</th>
				<th>Fecha</th>
				<th>Valor</th>
				<th>Tipo Doc.</th>
				<th>Concepto</th>
				<th>N° Doc.</th>
				<th>Glosa</th>
				<th>Tipo Mov.</th>
				<th>Forma Pag.</th>
				</thead>
				<tbody>';
		if($cuenta_pag>0)
		{
			$suma_valor_I=0;
			$suma_valor_E=0;
			$suma_cheque_I=0;
			$suma_cheque_E=0;
			$suma_efectivo_I=0;
			$suma_efectivo_E=0;
			
			$i=0;
			$RESUMEN=array();
			while($D=$sqlP->fetch_assoc())
			{
				$icono='';
				$id_pago=$D["idpago"];
				$id_boleta=$D["id_boleta"];
				$id_alumno=$D["id_alumno"];
				$id_cuota=$D["id_cuota"];
				$id_factura=$D["id_factura"];
				$fecha_pag=$D["fechapago"];
				$fecha_generacion=$D["fecha_generacion"];
				$valor=$D["valor"];
				$tipo_documento=$D["tipodoc"];
				$glosa=$D["glosa"];
				$sede_pago=$D["sede"];
				$movimiento=$D["movimiento"];
				$forma_pago=$D["forma_pago"];
				$fechaV_cheque=$D["fechaV_cheque"];
				$id_cheque=$D["id_cheque"];
				$cod_user=$D["cod_user"];
				$por_concepto=$D["por_concepto"];
				$aux_num_documento=$D["aux_num_documento"];
				if($tipo_documento=="L"){$tipo_documento="letra";}
				
				if(!isset($RESUMEN[$por_concepto][$movimiento]))
				{
					///defino los valores iniciales
					$RESUMEN[$por_concepto][$movimiento]["efectivo"]=0;
					$RESUMEN[$por_concepto][$movimiento]["cheque"]=0;
					$RESUMEN[$por_concepto][$movimiento]["otro"]=0;
				}
				
				switch($forma_pago)
				{
					case"efectivo":
						$RESUMEN[$por_concepto][$movimiento]["efectivo"]+=$valor;
						break;
					case"cheque":
						$RESUMEN[$por_concepto][$movimiento]["cheque"]+=$valor;
						break;
					case"multi_cheque":
						$RESUMEN[$por_concepto][$movimiento]["cheque"]+=$valor;
						break;	
					default:
						$RESUMEN[$por_concepto][$movimiento]["otro"]+=$valor;	
				}

				list($tipo_documento, $numero_documento)=INFO_COMPROBANTE_FINANCIERO($id_pago);
				
				switch($movimiento)
				{
					case"I":
						switch($forma_pago)
						{
							case"efectivo":
								$suma_efectivo_I+=$valor;
								break;
							case"cheque":
								$suma_cheque_I+=$valor;
								break;
							default:
								$suma_valor_I+=$valor;	
						}
						break;
					case"E":
						switch($forma_pago)
						{
							case"efectivo":
								$suma_efectivo_E+=$valor;
								break;
							case"cheque":
								$suma_cheque_E+=$valor;
								break;
							default:
								$suma_valor_E+=$valor;	
						}
						break;
				}
				
				$html_tabla.='<tr>
					<td align="center">'.$icono.'</td>
					<td align="center">'.$id_pago.'</td>
					<td align="center"><a href="#" title="FG:'.$sede_pago.'-'.$fecha_generacion.'">'.fecha_format($fecha_pag,"-").'</a></td>
					<td align="right">'.$valor.'</td>
					<td align="center">'.$tipo_documento.'</td>
					<td align="center">'.$por_concepto.'</td>
					<td align="right">'.$numero_documento.'</td>
					<td>'.$glosa.'</td>
					<td align="center">'.$movimiento.'</td>
					<td align="center">'.$forma_pago.'</td>
					</tr>';
			}
		}
		else
		{
			//no pagos en rango fecha
			$html_tabla.='<tr>
					<td colspan="11">Sin pagos Registrados en este Rango de Fechas, sede y concepto</td>
					</tr>';
		}
		$html_tabla.='</tbody>
				<tfoot>
				<tr bgcolor="#EBE5D9">
				<td colspan="3">N. Total Movimientos:</td>
				<td colspan="2" align="right">'.$cuenta_pag.'</td>
				<td colspan="6">&nbsp;</td>
				</tr>
				<tr>
				<td colspan="10">&nbsp;</td>
				</tr>';
				
				/////////////////////////
				$suma_valor_I+=$suma_cheque_I+$suma_efectivo_I;//sumo los cheque ingresos y el efectivo
				$suma_valor_E+=$suma_cheque_E+$suma_efectivo_E;//sumo cheques al egreso y el efectivo
				/////////////////////////
		$html_tabla.='
				<tr>
				<td colspan="3">Total Ingresos Efectivo</td>
				<td colspan="2" align="right">$'.number_format($suma_efectivo_I,0,",",".").'</td>
				<td colspan="1">&nbsp;</td>
				<td colspan="2">Total Egresos Efectivo</td>
				<td colspan="2"  align="right">$'.number_format($suma_efectivo_E,0,",",".").'</td>
				</tr>
				<tr>
				<td colspan="3">Total Ingresos Cheque</td>
				<td colspan="2" align="right">$'.number_format($suma_cheque_I,0,",",".").'</td>
				<td colspan="1">&nbsp;</td>
				<td colspan="2">Total Egresos Cheque</td>
				<td colspan="2"  align="right">$'.number_format($suma_cheque_E,0,",",".").'</td>
				</tr>
				<tr>
				<td colspan="3"><strong>TOTAL Ingreso</strong></td>
				<td colspan="2" align="right"><strong>$'.number_format($suma_valor_I,0,",",".").'</strong></td>
				<td colspan="1">&nbsp;</td>
				<td colspan="2"><strong>TOTAL EGRESOS</strong></td>
				<td colspan="2"  align="right">$'.number_format($suma_valor_E,0,",",".").'</td>
				</tr>
				<tr>
				<td colspan="10">&nbsp;</td>
				</tr>
				</tfoot>
				</table>';
				
				$tabla_resumen='<table width="100%" border="1" class="display" id="example">
  <caption>Resumen Por Item</caption>
  <thead>
  <tr>
    <td width="7%" rowspan="2"><div align="center"><strong>N&deg;</strong></div></td>
    <td width="24%" rowspan="2"><div align="center"><strong>Concepto</strong></div></td>
    <td width="23%" colspan="4" bgcolor="#D8F6CE"><div align="center"><strong>Ingreso ($) </strong></div></td>
    <td width="25%" colspan="4" bgcolor="#F8E0E0"><div align="center"><strong>Egreso</strong> <strong>($)</strong></div></td>
    <td width="21%"><div align="center"><strong>Total</strong> <strong>($)</strong></div></td>
  </tr>
  <tr>
  	<td>efectivo</td>
    <td>cheque</td>
	<td>otro</td>
	<td bgcolor="#D8F6CE">total</td>
    <td>efectivo</td>
    <td>cheque</td>
	<td>otro</td>
	<td bgcolor="#F8E0E0">total</td>
    <td>&nbsp;</td>
  </tr>
  </thead>
  <tbody>';
			if($cuenta_pag>0)
				{	
					if(DEBUG){var_dump($RESUMEN);}
					$contador=0;
					$TOTALES=array();
				
					foreach($RESUMEN as $concepto => $MOV)
					{
						$contador++;
						
						////I
						if(isset($MOV["I"]["efectivo"])){$aux_ingreso_efectivo=$MOV["I"]["efectivo"];}
						else{ $aux_ingreso_efectivo=0;}
						if(isset($MOV["I"]["cheque"])){$aux_ingreso_cheque=$MOV["I"]["cheque"];}
						else{ $aux_ingreso_cheque=0;}
						if(isset($MOV["I"]["otro"])){$aux_ingreso_otro=$MOV["I"]["otro"];}
						else{ $aux_ingreso_otro=0;}
						
						///E
						if(isset($MOV["E"]["efectivo"])){ $aux_egreso_efectivo=$MOV["E"]["efectivo"];}
						else{ $aux_egreso_efectivo=0;}
						if(isset($MOV["E"]["cheque"])){ $aux_egreso_cheque=$MOV["E"]["cheque"];}
						else{ $aux_egreso_cheque=0;}
						if(isset($MOV["E"]["otro"])){$aux_egreso_otro=$MOV["E"]["otro"];}
						else{ $aux_egreso_otro=0;}
						
						
						
						$aux_total=(($aux_ingreso_efectivo+$aux_ingreso_cheque+$aux_ingreso_otro)-($aux_egreso_efectivo+$aux_egreso_otro));
						
						if(isset($TOTALES["I"]))
						{$TOTALES["I"]+=$aux_ingreso_efectivo+$aux_ingreso_cheque+$aux_ingreso_otro;}
						else{$TOTALES["I"]=$aux_ingreso_efectivo+$aux_ingreso_cheque+$aux_ingreso_otro;}
						
						if(isset($TOTALES["E"]))
						{$TOTALES["E"]+=$aux_egreso_efectivo+$aux_egreso_cheque+$aux_egreso_otro;}
						else{$TOTALES["E"]=$aux_egreso_efectivo+$aux_egreso_cheque+$aux_egreso_otro;}
						
						
						if(isset($TOTALES["T"]))
						{$TOTALES["T"]+=$aux_total;}
						else{$TOTALES["T"]=$aux_total;}
						
						$tabla_resumen.='<tr>
							<td>'.$contador.'</td>
							<td>'.$concepto.'</td>
							<td><div align="right">'.number_format($aux_ingreso_efectivo,0,",",".").'</div></td>
							<td><div align="right">'.number_format($aux_ingreso_cheque,0,",",".").'</div></td>
							<td><div align="right">'.number_format($aux_ingreso_otro,0,",",".").'</div></td>
							<td bgcolor="#D8F6CE"><div align="right">'.number_format($aux_ingreso_cheque+$aux_ingreso_efectivo+$aux_ingreso_otro,0,",",".").'</div></td>
							<td><div align="right">'.number_format($aux_egreso_efectivo,0,",",".").'</div></td>
							<td><div align="right">'.number_format($aux_egreso_cheque,0,",",".").'</div></td>
							<td><div align="right">'.number_format($aux_egreso_otro,0,",",".").'</div></td>
							<td bgcolor="#F8E0E0"><div align="right">'.number_format($aux_egreso_cheque+$aux_egreso_efectivo+$aux_egreso_otro,0,",",".").'</div></td>
							<td><div align="right">'.number_format($aux_total,0,",",".").'</div></td>
							</tr>';
					}
					$tabla_resumen.='<tr>
							<td colspan="2"><strong>TOTALES</strong></td>
							<td colspan="4"><div align="right">'.number_format($TOTALES["I"],0,",",".").'</div></td>
							<td colspan="4"><div align="right">'.number_format($TOTALES["E"],0,",",".").'</div></td>
							<td colspan="4"><div align="right">'.number_format($TOTALES["T"],0,",",".").'</div></td>
							</tr>';
					
				}
				else
				{$tabla_resumen.='<tr><td colspan="5">Sin Registros</td></tr>';}
		$tabla_resumen.='</tbody></table>';
		$html=$html_tabla;
		
		$html.="<br>".$tabla_resumen;
		
		//$html.='<br><a href="genera_excel_v3.php?movimiento='.base64_encode($movimiento_consulta).'&sede='.base64_encode($sede).'&fecha_inicio='.base64_encode($fecha_inicio).'&fecha_fin='.base64_encode($fecha_fin).'" title="Exportar a Excel" target="_blank"><img name="excel" src="../../BAses/Images/excel_icon.png" width="32" height="32" alt="Exportar a Excel" /></a>';
		
		
	$sqlP->free();	
	$conexion_mysqli->close();
	
	
	
	$msj_titulo=$movimiento_label.' del '.fecha_format($fecha_inicio).' al '.fecha_format($fecha_fin).' - '.$sede;
	return(array($html, $msj_titulo));
}
?>
<div id="pagos" class="demo_jui">
<fieldset><legend><strong><?php echo $titulo;?></strong></legend>
<?php echo $pagos;?>
</fieldset>
<div id="cheques"><?php //echo $cheques;?></div>
</div>
</body>
</html>