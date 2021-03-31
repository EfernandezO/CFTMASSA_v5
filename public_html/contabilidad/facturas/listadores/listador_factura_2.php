<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("gestion_facturas_v1.2");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_POST)
{
	if(DEBUG){ var_dump($_POST);}
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/funcion.php");
	require('../../../libreria_publica/fpdf/mc_table.php');
	$sede=$_POST["fsede"];
	$condicion=$_POST["condicion"];
	
	$hay_condiciones=false;
	
	if($sede=="todas"){ $condicion_sede=""; if(DEBUG){ echo"sin filtro de sede NO hay condicion<br>";}}
	else{ $condicion_sede="sede='$sede'"; $hay_condiciones=true; if(DEBUG){ echo"Selecciona sede hay condicion<br>";}}
	
	if($condicion=="todas")
	{ $condicion_P=""; if(DEBUG){ echo"Sin filtro de Condicion<br>";}}
	else
	{ 
		if($hay_condiciones){$condicion_P="AND condicion='$condicion'"; }
		else{ $condicion_P="condicion='$condicion'"; } 
		$hay_condiciones=true;
	}
	
	$fecha_inicio=$_POST["fecha_inicio"];
	$fecha_fin=$_POST["fecha_fin"];
	$movimiento=$_POST["movimiento"];
	
	if($movimiento="T")
	{ $condicion_movimiento="";}
	else{ if($hay_condiciones){$condicion_movimiento="AND movimiento='$movimiento'";}else{$condicion_movimiento="movimiento='$movimiento'";} $hay_condiciones=true;}
	
	if($hay_condiciones){$condicion_fecha="AND fecha_vencimiento BETWEEN '$fecha_inicio' AND '$fecha_fin'";}
	else{ $condicion_fecha="fecha_vencimiento BETWEEN '$fecha_inicio' AND '$fecha_fin'";}
	
	$zoom=75;
	$borde=1;
	$color_relleno="200,100,100";
	$TOTAL_FACTURAS=0;
	$TOTAL_SALDOS=0;
	$TITULO="Facturas $condicion entre ".fecha_format($fecha_inicio)." y ".fecha_format($fecha_fin)." \n Sede: $sede";
	/////////////////////////////////
	$pdf=new PDF_MC_Table('P','mm','Letter');

		$pdf->SetAutoPageBreak(true);
		$pdf->SetDisplayMode($zoom);	
		$pdf->AddPage(); /* Se añade una nueva página */
		$pdf->SetFont('Arial','B',12); 
		$pdf->MultiCell(190,6,$TITULO,$borde,"C");
		$pdf->SetFont('Arial','',10); 
		$y_actual=$pdf->GetY();
		$pdf->SetY($y_actual+10);
		//cabecera
		$pdf->SetFillColor($color_relleno);
		$pdf->Cell(5,6,"-",$borde,0,"C",true);
		$pdf->Cell(20,6,"Cod.",$borde,0,"C",true);
		$pdf->Cell(50,6,"Proveedor",$borde,0,"C",true);
		$pdf->Cell(25,6,"Ingreso",$borde,0,"C",true);
		$pdf->Cell(25,6,"Vencimiento",$borde,0,"C",true);
		$pdf->Cell(20,6,"Condicion",$borde,0,"C",true);
		$pdf->Cell(25,6,"Valor",$borde,0,"C",true);
		$pdf->Cell(25,6,"Saldo",$borde,1,"C",true);
	
	////////////////////////////////
	$cons="SELECT * FROM facturas WHERE $condicion_sede $condicion_P $condicion_movimiento $condicion_fecha ORDER BY id_proveedor, cod_factura, fecha_vencimiento";
	if(DEBUG){echo"<br>-> $cons<br>";}
	$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$num_reg=$sqli->num_rows;
	if($num_reg>0)
	{
		$contador=0;
		while($F=$sqli->fetch_assoc())
		{
			$contador++;
			$cod_factura=$F["cod_factura"];
			$id_factura=$F["id"];
			$id_proveedor=$F["id_proveedor"];
			//--------------------------------------------//
			//proveedores
			$cons_P="SELECT * FROM proveedores WHERE id_proveedor='$id_proveedor' LIMIT 1";
			$sqli_P=$conexion_mysqli->query($cons_P);
				$P=$sqli_P->fetch_assoc();
				$P_razon_social=$P["razon_social"];
				
				$proveedor=$P_razon_social;
			$sqli_P->free();	
			//---------------------------------------------//
			$comentario=$F["comentario"];
			$fecha_ingreso=$F["fecha_ingreso"];
			$fecha_vencimiento=$F["fecha_vencimiento"];
			$condicion=$F["condicion"];
			$valor=$F["valor"];
			$saldo=$F["saldo"];
			$abono=$F["abono"];
			$movimiento=$F["movimiento"];
			
			$TOTAL_FACTURAS+=$valor;
			$TOTAL_SALDOS+=$saldo;
			
			$pdf->SetAligns(array("C","C","C","C","C","C","R","R"));
			$pdf->SetWidths(array(5,20,50,25,25,20,25,25));
				$pdf->Row(array($movimiento,$cod_factura,$proveedor,fecha_format($fecha_ingreso),fecha_format($fecha_vencimiento), $condicion, "$".number_format($valor,0,",","."), "$".number_format($saldo,0,",",".")));
			
			if(DEBUG){echo"$id_factura - $proveedor - $fecha_vencimiento - $condicion - $valor<br>";}
		}
	}
	else
	{
		if(DEBUG){ echo"sin registros";}
		else{
		$pdf->Cell(190,6,"Sin Registros",$borde,0,"C");
		}
	}
	if(DEBUG){ echo"Total F-> $TOTAL_FACTURAS<br>";}
	else
	{
		$pdf->Cell(145,6,"TOTAL por ($num_reg) Facturas Encontradas",$borde,0,"L",true);
		$pdf->Cell(25,6,"$".number_format($TOTAL_FACTURAS,0,",","."),$borde,0,"R",true);
		$pdf->Cell(25,6,"$".number_format($TOTAL_SALDOS,0,",","."),$borde,1,"R",true);
	}
	$sqli->free();
	$conexion_mysqli->close();
	$pdf->Output();
}
else
{
	//header("location: ../registro/ver/ver_factura.php");
}
?>