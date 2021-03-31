<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(true);
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("deudores_mensualidad_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

if($_GET)
{
		$id_carrera=base64_decode($_GET["id_carrera"]);
		$sede=base64_decode($_GET["sede"]);
		$year_cuotas=base64_decode($_GET["year_cuotas"]);
		
		$nombre_archivo="cuotas_".$year_cuotas."_".date("dmYHis");
		
		if(DEBUG)
		{ var_export($_GET);}
		else
		{
			header('Content-type: application/vnd.ms-excel');
			header("Content-Disposition: attachment; filename=$nombre_archivo.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/class_ALUMNO.php");
	require("../../../funciones/funciones_sistema.php");
	     
		if(DEBUG){ var_export($_POST);}

		
		if($year_cuotas!="0")
		{ $condicion_year_cuota="AND letras.ano='$year_cuotas'";}
		else
		{ $condicion_year_cuota="";}
		
		if($id_carrera!="0")
		{ $condicion_carrera="contratos2.id_carrera='$id_carrera' AND";}
		else
		{ $condicion_carrera="";}
		
		
$tabla='<table border="1" align="center" width="100%">
<thead>
	<td colspan="12" align="center">Carrera:'.NOMBRE_CARRERA($id_carrera).' - Sede:'.$sede.'  -  Año Cuotas: '.$year_cuotas.'</td>
	<tr>
	<th >Sede</th>
	<th >id_alumno</th>
    <th>id_carrera</th>
    <th>Jornada</th>
    <th>yearIngresoCarrera</th>
    <th >Rut</th>
    <th>Nombre</th>
    <th>Apellido P</th>
    <th>Apellido M</th>
	<th>FONO</th>
	 <th>Semestre</th>
      <th>A&ntilde;o</th>
      <th>id_contrato</th>
      <th>Total</th>
    <th>Tipo</th>
    <th>Fecha Vencimiento</th>
    <th>Valor Cuota</th>
    <th>Deuda Cuota</th>
    <tbody>';

		
		$cons_CUO="SELECT letras.id AS id_letra, letras.idalumn, letras.id_contrato, letras.fechavenc, letras.valor, letras.deudaXletra, letras.semestre, letras.ano, letras.sede, letras.tipo, contratos2.id_alumno, contratos2.yearIngresoCarrera, contratos2.id_carrera, contratos2.jornada, contratos2.linea_credito_paga FROM letras INNER JOIN contratos2 ON letras.id_contrato = contratos2.id WHERE contratos2.sede='$sede' $condicion_carrera  $condicion_year_cuota ORDER by contratos2.sede, letras.ano, contratos2.id_carrera, contratos2.id_alumno, letras.fechavenc";
		
		if(DEBUG){ echo"<br><br>-->$cons_CUO<br><br>";}
		$sql_CUO=$conexion_mysqli->query($cons_CUO)or die($conexion_mysqli->error);
		$num_cuotas_encontradas=$sql_CUO->num_rows;
		
		if($num_cuotas_encontradas>0)
		{
			$SUMA_TOTAL_VALOR=0;
			$SUMA_TOTAL_DEUDA=0;
			$ARRAY_RESUMEN=array();
			$A_idOLD=0;
			while($L=$sql_CUO->fetch_assoc())	
			{
				$A_id=$L["id_alumno"];
				
				
				if($A_id!==$A_idOLD){$ALUMNO = new ALUMNO($A_id);}
				
				$A_year_ingreso=$L["yearIngresoCarrera"];
				$A_carrera=$L["id_carrera"];
				$A_sede=$L["sede"];
				
				$C_id_contrato=$L["id_contrato"];
				$C_id=$L["id_letra"];
				$C_id_carrera=$L["id_carrera"];
				$C_jornada=$L["jornada"];
				$C_vence=$L["fechavenc"];
				$C_valor=$L["valor"];
				$C_deuda=$L["deudaXletra"];
				$C_semestre=$L["semestre"];
				$C_year=$L["ano"];
				
				
				
				$cuotaPagada=0;
				$cuotapendiente=0;
				if($C_deuda>0){$cuotapendiente=1;}
				else{$cuotaPagada=1;}
				
				
				$C_tipoCuota=$L["tipo"];
				
				$SUMA_TOTAL_DEUDA+=$C_deuda;
				$SUMA_TOTAL_VALOR+=$C_valor;
				
				if(isset($ARRAY_RESUMEN[$A_id])){
						$ARRAY_RESUMEN[$A_id]["totalCuotas"]+=$C_valor;
						$ARRAY_RESUMEN[$A_id]["totalDeuda"]+=$C_deuda;
						$ARRAY_RESUMEN[$A_id]["numeroCuotas"]++;
						$ARRAY_RESUMEN[$A_id]["numeroCuotasPagadas"]+=$cuotaPagada;
						$ARRAY_RESUMEN[$A_id]["numeroCuotasPendientes"]+=$cuotapendiente;
					}
					else{
						$ARRAY_RESUMEN[$A_id]["totalCuotas"]=$C_valor;
						$ARRAY_RESUMEN[$A_id]["totalDeuda"]=$C_deuda;
						$ARRAY_RESUMEN[$A_id]["numeroCuotas"]=1;
						$ARRAY_RESUMEN[$A_id]["numeroCuotasPagadas"]=$cuotaPagada;
						$ARRAY_RESUMEN[$A_id]["numeroCuotasPendientes"]=$cuotapendiente;
						
					}
				
				
				
				$A_idOLD=$A_id;
				if(DEBUG){ echo" <b>$A_id</b> $C_id - $C_valor - $C_deuda - $C_vence - $C_semestre - $C_year - $A_carrera - $A_sede - [$C_id_contrato]<br>";}
				
				
				
				$CNT_linea_credito=$L["linea_credito_paga"];
				
				$validador=md5("GDXT".date("d-m-Y"));
				$url_destino='http://intranet.cftmassachusetts.cl/buscador_alumno_BETA/enrutador.php?validador='.$validador.'&id_alumno='.$A_id;
				
				$tabla.='<tr>
					<td>'.$A_sede.'</td>
					<td><a href="#" target="_blank" >'.$A_id.'</a></td>
					<td>'.$C_id_carrera.'</td>
					<td>'.$C_jornada.'</td>
					<td>'.$A_year_ingreso.'</td>
					
					<td>'.$ALUMNO->getRut().'</td>
					<td>'.$ALUMNO->getNombre().'</td>
					<td>'.$ALUMNO->getApellido_P().'</td>
					<td>'.$ALUMNO->getApellido_M().'</td>
					<td>'.$ALUMNO->getFono().' - '.$ALUMNO->getFonoApoderado().'</td>
					<td>'.$C_semestre.'</td>
					<td>'.$C_year.'</td>
					<td>'.$C_id_contrato.'</td>
					<td>'.$CNT_linea_credito.'</td>
					<td>'.$C_tipoCuota.'</td>
					<td>'.date("d-m-Y", strtotime($C_vence)).'</td>
					<td align="right">'.$C_valor.'</td>
					<td align="right">'.$C_deuda.'</td>
				</tr>';
			}
			
			if(DEBUG){ echo"<br>TOTAL V----->$SUMA_TOTAL_VALOR  TOTAL D --->$SUMA_TOTAL_DEUDA<br>";}
			$tabla.='<tr>
					<td><strong>Totales</strong></td>
					<td colspan="14">-> % de Morosidad (deuda con respecto a total): '.($SUMA_TOTAL_DEUDA*100)/$SUMA_TOTAL_VALOR.'</td>
					<td align="right"><strong>'.$SUMA_TOTAL_VALOR.'</strong></td>
					<td align="right"><strong>'.$SUMA_TOTAL_DEUDA.'</strong></td>
				</tr>';
			
		}
		else
		{
			if(DEBUG){ echo"NO se Encontraron Cuotas...<br>";}
		}
	$sql_CUO->free();	
	$conexion_mysqli->close();
}
///////////////////////
$tabla.='</tbody>
</table>';
$tabla.= "Generado el ".date("d-m-Y H:i:s");


$tablaResumen='<br><br>RESUMEN<br>
			<table border="1">
			<tr bgcolor="#66CCFF">
				<td>n</td>
				<td>Estado</td>
				<td>Rut</td>
				<td>Nombre</td>
				<td>Apellido P</td>
				<td>Apellido M</td>
				<td>Total en cuotas</td>
				<td>Total deuda</td>
				<td>Num cuotas</td>
				<td>Num cuotas Pagadas</td>
				<td>Num cuotas Pendientes</td>
				<td>FONO</td>
				<td>EMAIL</td>
			</tr>';
$n=0;
foreach($ARRAY_RESUMEN as $auxId => $auxArray){
	$n++;
	 $ALUMNO = new ALUMNO($auxId);
	 $XtotalCuotas=$auxArray["totalCuotas"];
	 $XtotalDeuda=$auxArray["totalDeuda"];
	 $XnumCuotas=$auxArray["numeroCuotas"];
	 $XcuotasPagadas=$auxArray["numeroCuotasPagadas"];
	 $XcuotasPendientes=$auxArray["numeroCuotasPendientes"];
	
		if($XtotalDeuda>0){$estado="moroso"; $color="#AA0000";}
		else{ $estado="al_dia"; $color="#00AA00";}
	 
	 $tablaResumen.='<tr>
	 					<td>'.$n.'</td>
						<td bgcolor="'.$color.'">'.$estado.'</td>	
						<td>'.$ALUMNO->getRut().'</td>
						<td>'.$ALUMNO->getNombre().'</td>
						<td>'.$ALUMNO->getApellido_P().'</td>
						<td>'.$ALUMNO->getApellido_M().'</td>
						<td>'.$XtotalCuotas.'</td>
						<td>'.$XtotalDeuda.'</td>
						<td>'.$XnumCuotas.'</td>
						<td>'.$XcuotasPendientes.'</td>
						<td>'.$XcuotasPagadas.'</td>
						<td>'.$ALUMNO->getFono().'</td>
						<td>'.$ALUMNO->getEmail().'</td>
	 				 </tr>';
}

$tablaResumen.='</table><br>';

echo $tabla;
echo $tablaResumen;
?>
