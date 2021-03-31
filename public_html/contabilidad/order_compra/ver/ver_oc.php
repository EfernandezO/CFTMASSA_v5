<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
	define("DEBUG", true);
//-----------------------------------------//
	include("../../../../funciones/funcion.php");
	require('../../../libreria_publica/fpdf/mc_table.php');
	////definicion de parametros
	$logo="../../../BAses/Images/logoX.jpg";
	$fecha_actual_palabra=fecha();
	$fecha_actual=date("d-m-Y");
	$salto_Y=5;//separacion entre parrafos
	
	
	$borde=1;
	$fecha=fecha();
	$letra_1=12;
	$autor="ACX";
	$titulo="Orden de Compra N.";
	$zoom=75;
	$hoja_oficio[0]=217;
	$hoja_oficio[1]=330;
	
	
if(isset($_GET["id_oc"]))
{
	$OC_id=$_GET["id_oc"];
	if(is_numeric($OC_id))
	{ $continuar=true;}
	else
	{ $continuar=false;}
}
else
{$continuar=false;}

////--------------------------------------------/////
if($continuar)
{
	include("../../../../funciones/conexion_v2.php");
	//inicializacion de pdf
	//$pdf=new FPDF('P','mm',"Letter");
	$pdf=new PDF_MC_Table('P','mm',"Letter");
	$pdf->SetAuthor($autor);
	$pdf->SetTitle($titulo);
	$pdf->SetDisplayMode($zoom);
	$pdf->AddFont('Allegro','','ALLEGRO.php');
	$pdf->AddPage();
	//-----------------------------------------------------//
		//datos orden de compra
		$cons="SELECT * FROM orden_compra WHERE id_oc='$OC_id' LIMIT 1";
		$sql=$conexion_mysqli->query($cons);
			$OC=$sql->fetch_assoc();
			$OC_id_proveedor=$OC["id_proveedor"];
			$OC_id_solicitante=$OC["id_solicitante"];
			$OC_descripcion=$OC["descripcion"];
			$OC_sede=$OC["sede"];
			$OC_unidad_solicitante=$OC["unidad_solicitante"];
			$OC_cotizacion=$OC["cotizacion"];
			$OC_condiciones_pago=$OC["condiciones_pago"];
			$OC_fecha_creacion=$OC["fecha_creacion"];
			$array_OC_fecha_creacion=explode("-",$OC_fecha_creacion);
			
		$sql->free();	
		////////////////////////////////////////////////////////////
		//datos proveedor
		$cons_P="SELECT * FROM proveedores WHERE id_proveedor='$OC_id_proveedor' LIMIT 1";
		$sql_P=$conexion_mysqli->query($cons_P);
		$DP=$sql_P->fetch_assoc();
			$proveedor_razon_social=$DP["razon_social"];
			$proveedor_rut=$DP["rut"];
			$proveedor_direccion=$DP["direccion"];
			$proveedor_ciudad=$DP["ciudad"];
		$sql_P->free();	
		//-----------------------------------------------------//
		//datos presonal
		$cons_PERS="SELECT nombre, apellido_P, apellido_M FROM personal WHERE id='$OC_id_solicitante' LIMIT 1";
		$sql_PERS=$conexion_mysqli->query($cons_PERS);
		$DPERS=$sql_PERS->fetch_assoc();
			$personal_nombre=$DPERS["nombre"]." ".$DPERS["apellido_P"]." ".$DPERS["apellido_M"];
		$sql_PERS->free();	
		//------------------------------------------------------//
		
		//imagen texto
		$pdf->image($logo,15,5,30,24,'jpg'); //este es el logo
		$pdf->SetFont('Times','',8);
		$pdf->Text(45,8,"3 Sur 1068");
		$pdf->Text(45,11,"Fono 2225921");
		$pdf->Text(45,14,"Talca");
		$pdf->Text(45,17,"O'higgins 313");
		$pdf->Text(45,20,"Fono 2213880");
		$pdf->Text(45,23,"Linares");
		$pdf->Text(45,26,"Rut: 89.921.100-6");
		$pdf->Text(45,29,"daf@cftmass.cl");
		//-----------------------------------------------//
		$pdf->SetY(25);
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->SetFont('Times','B',16);
		$pdf->SetXY(50,35);
		$pdf->Cell(60,10,$titulo,$borde,0,'L');
		$pdf->SetFont('Times','',16);
		$pdf->Cell(30,10,$OC_id,$borde,0,'L');
		
		$pdf->SetX(160);
		$pdf->Cell(15,5,"Dia",$borde,0,'C');
		$pdf->Cell(15,5,"Mes",$borde,0,'C');
		$pdf->Cell(15,5,"Año",$borde,1,'C');
		
		$pdf->SetX(160);
		$pdf->Cell(15,5,$array_OC_fecha_creacion[2],$borde,0,'C');
		$pdf->Cell(15,5,$array_OC_fecha_creacion[1],$borde,0,'C');
		$pdf->Cell(15,5,$array_OC_fecha_creacion[0],$borde,1,'C');
		
		$pdf->ln(20);
		$pdf->SetFont('Times','B',12);
		$pdf->Cell(40,6,"Proveedor",$borde,0,'L');
		$pdf->SetFont('Times','',12);
		$pdf->Cell(105,6,$proveedor_razon_social,$borde,1,'L');
		$pdf->SetFont('Times','B',12);
		$pdf->Cell(40,6,"RUT",$borde,0,'L');
		$pdf->SetFont('Times','',12);
		$pdf->Cell(105,6,$proveedor_rut,$borde,1,'L');
		$pdf->SetFont('Times','B',12);
		$pdf->Cell(40,6,"Direccion",$borde,0,'L');
		$pdf->SetFont('Times','',12);
		$pdf->Cell(105,6,$proveedor_direccion.", ".$proveedor_ciudad,$borde,1,'L');
		$pdf->SetFont('Times','B',12);
		$pdf->Cell(40,6,"Unidad Solicitante",$borde,0,'L');
		$pdf->SetFont('Times','',12);
		$pdf->Cell(60,6,$OC_unidad_solicitante,$borde,0,'L');
		$pdf->SetFont('Times','B',12);
		$pdf->Cell(12,6,"Sede",$borde,0,'L');
		$pdf->SetFont('Times','',12);
		$pdf->Cell(33,6,$OC_sede,$borde,1,'L');
		$pdf->SetFont('Times','B',12);
		$pdf->Cell(40,6,"Responsable",$borde,0,'L');
		$pdf->SetFont('Times','',12);
		$pdf->Cell(105,6,$personal_nombre,$borde,1,'L');
		$pdf->SetFont('Times','B',12);
		$pdf->Cell(40,6,"Cotizacion",$borde,0,'L');
		$pdf->SetFont('Times','',12);
		$pdf->Cell(105,6,$OC_cotizacion,$borde,1,'L');
		$pdf->SetFont('Times','B',12);
		$pdf->Cell(40,6,"Condiciones de Pago",$borde,0,'L');
		$pdf->SetFont('Times','',12);
		$pdf->Cell(105,6,$OC_condiciones_pago,$borde,1,'L');
		//-------------------------------------------------------------------------------------//
		$pdf->Ln();
		$pdf->SetFont('Times','B',12);
		$pdf->Cell(195,6,"",$borde,1,'C');
		$pdf->Cell(20,6,"Cantidad",$borde,0,'C');
		$pdf->Cell(30,6,"Unidad Medida",$borde,0,'C');
		$pdf->Cell(105,6,"Detalle",$borde,0,'C');
		$pdf->Cell(20,6,"Unitario",$borde,0,'C');
		$pdf->Cell(20,6,"Total",$borde,1,'C');
		
		$pdf->SetFont('Times','',10);
		$cons_ITEM="SELECT * FROM orden_compra_item WHERE id_oc='$OC_id'";
		$sql_item=$conexion_mysqli->query($cons_ITEM);
		$num_item=$sql_item->num_rows;
		$TOTAL=0;
		if($num_item>0)
		{
			while($I=$sql_item->fetch_assoc())
			{
				$I_id=$I["id_item"];
				$I_oc=$I["id_oc"];
				$I_cantidad=$I["cantidad"];
				$I_unidad_medida=$I["unidad_medida"];
				$I_descripcion=ucwords(strtolower($I["descripcion"]));
				$I_valor_unitario=$I["valor_unitario"];
				
				$aux_total=($I_cantidad*$I_valor_unitario);
				$TOTAL+=$aux_total;
				
				$pdf->SetWidths(array(20,30,105,20,20));
				$pdf->Row(array(number_format($I_cantidad,0,",","."),$I_unidad_medida,utf8_decode($I_descripcion),number_format($I_valor_unitario,0,",","."), $aux_total));
			}
			$pdf->SetX(185);
			$pdf->Cell(20,6,number_format($TOTAL,0,",","."),$borde,1,'R');
		}
		
	$sql_item->free();
	$conexion_mysqli->close();	
	mysql_close($conexion);
	$pdf->Output();
}//fin si continuar
else
{if(DEBUG){echo"sin orden de compra<br>";}	}
?>