<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_pagare_emitidos_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//


$FACTOR=0.033;
$FACTOR_MAXIMO_APLICABLE=0.4;
///----------------------------------//
$mostrar_cuotas=true;
if($_POST)
{
	if(DEBUG){ var_dump($_POST);}
	else
	{
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=informe_pagare_emitidos.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/VX.php");
	require("../../../funciones/funciones_varias.php");
	
	
	$SUMA_IMPUESTO=0;

	$mes=mysqli_real_escape_string($conexion_mysqli, $_POST["mes"]);
	$year=mysqli_real_escape_string($conexion_mysqli, $_POST["year"]);
	$sede=mysqli_real_escape_string($conexion_mysqli, $_POST["sede"]);
	$hay_condiciones=false;
	
	$evento="Revision de Pagare Emitidos $mes - $year sede: $sede";
	REGISTRA_EVENTO($evento);
	
	$tabla_html='<table border="1">
	<thead>
		<tr>
			<th colspan="17">Pagare de periodo ['.$mes.' - '.$year.'] sede '.$sede.'</th>
		</tr>
		</thead>
					<tr>
					<td bgcolor="#66CCFF">N</td>
					<td bgcolor="#66CCFF">Rut</td>
					<td bgcolor="#66CCFF">Nombre</td>
					<td bgcolor="#66CCFF">Apellido P</td>
					<td bgcolor="#66CCFF">Apellido M</td>
					<td bgcolor="#66CCFF">Sede</td>
					<td bgcolor="#66CCFF">Folio Pagare</td>
					<td bgcolor="#66CCFF">Linea Credito</td>
					<td bgcolor="#66CCFF">Fecha Generacion</td>
					<td bgcolor="#66CCFF">Fecha Inicio Contrato</td>
					<td bgcolor="#66CCFF">Fecha Fin Contrato</td>
					<td bgcolor="#66CCFF">Diferencia Inicio Fin</td>
					<td bgcolor="#66CCFF">Valor cuota</td>
					<td bgcolor="#66CCFF">Fecha vence cuota</td>
					<td bgcolor="#66CCFF">Meses diferencia</td>
					<td bgcolor="#CC6633">Factor</td>
					<td bgcolor="#CC6633">impuesto</td>
				 </tr>
				 <tbody>';
	
	
	if($mes>0){ $condicion_mes="MONTH(contratos2.fecha_generacion)='$mes' "; $hay_condiciones=true;}
	else{ $condicion_mes="";}
	
	if($year=="todos"){ $condicion_year="";}
	else
	{
		if($hay_condiciones)
		{$condicion_year="AND YEAR(contratos2.fecha_generacion)='$year' "; }
		else{ $condicion_year="YEAR(contratos2.fecha_generacion)='$year' "; }
		$hay_condiciones=true;
	}
	
	if($sede=="0"){$condicion_sede="";}
	else
	{
		if($hay_condiciones)
		{$condicion_sede="AND contratos2.sede='$sede' ";}
		else{ $condicion_sede="contratos2.sede='$sede' ";}
		$hay_condiciones=true;
	}
	
	if($hay_condiciones)
	{ $condicion_WHERE="WHERE ";}
	else{ $condicion_WHERE="";}
	
	
	$cons="SELECT contratos2.id, contratos2.fecha_generacion, contratos2.fecha_inicio, contratos2.fecha_fin, contratos2.folio_pagare, contratos2.linea_credito_paga, contratos2.sede, alumno.id AS id_alumno, alumno.rut, alumno.nombre, alumno.apellido_P, alumno.apellido_M FROM contratos2 INNER JOIN alumno ON contratos2.id_alumno=alumno.id $condicion_WHERE $condicion_mes $condicion_year $condicion_sede ORDER by contratos2.fecha_generacion DESC";
	
	if(DEBUG){ echo"---> $cons<br>";}
	$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$num_registros=$sqli->num_rows;
	if($num_registros>0)
	{
		$contador=0;
		while($C=$sqli->fetch_assoc())
		{
			$contador++;
			
			$C_id=$C["id"];
			$C_fecha_generacion=$C["fecha_generacion"];
			$C_fecha_inicio=$C["fecha_inicio"];
			$C_fecha_fin=$C["fecha_fin"];
			$C_folio_pagare=$C["folio_pagare"];
			$C_linea_credito_paga=$C["linea_credito_paga"];
			$C_sede=$C["sede"];
			$A_id=$C["id_alumno"];
			$A_rut=$C["rut"];
			$A_nombre=$C["nombre"];
			$A_apellido_P=$C["apellido_P"];
			$A_apellido_M=$C["apellido_M"];
			
			$dias_diferencia_pagare=DIFERENCIA_ENTRE_FECHAS($C_fecha_inicio, $C_fecha_fin);
			
			
			//------------------------------------------------------//
	
			//-----------------------------------------------//
			if(($mostrar_cuotas)and($C_linea_credito_paga>0))		 
			{
				///busco cuotas
				$cons_CUO="SELECT * FROM letras WHERE id_contrato='$C_id' AND idalumn='$A_id' AND tipo='cuota' ORDER by fechavenc";			 
				$sqli_cuo=$conexion_mysqli->query($cons_CUO)or die($conexion_mysqli->error);
				$num_cuotas=$sqli_cuo->num_rows;
				if(DEBUG){ echo"$cons_CUO<br> num_cuotas: $num_cuotas<br>";}
				$contador_cuotas=0;
				if($num_cuotas>0)
				{
					while($CUO=$sqli_cuo->fetch_assoc())
					{
						$contador_cuotas++;
						
						if($contador_cuotas>13){ $contador_para_factor=13;}
						else{$contador_para_factor=$contador_cuotas;}
						
						
						$aux_factor=(($FACTOR*$contador_para_factor)/100);
						
						$CUO_fechavenc=$CUO["fechavenc"];
						$CUO_deudaXcuoa=$CUO["deudaXletra"];
						$CUO_valor=$CUO["valor"];
						
						$array_fecha_generacion=explode(" ",$C_fecha_generacion);
						$array_fecha_generacion=explode("-", $array_fecha_generacion[0]);
						
						$C_fecha_generacion_X=$array_fecha_generacion[0]."-".$array_fecha_generacion[1]."-01";
						
					
						$meses_diferencia=DIFERENCIA_ENTRE_FECHAS($C_fecha_generacion_X, $CUO_fechavenc, "meses_y_fraccion");
						
						if($meses_diferencia>0)
						{
							$aux_factor=($FACTOR*$meses_diferencia);
							if($aux_factor>$FACTOR_MAXIMO_APLICABLE){ $aux_factor=$FACTOR_MAXIMO_APLICABLE;}
						}
						else{$aux_factor=$FACTOR_MAXIMO_APLICABLE;}
						
						
						$aux_factor=($aux_factor/100);
						$aux_impuesto=($CUO_valor*$aux_factor);
						
						
						$SUMA_IMPUESTO+=$aux_impuesto;
						
						$tabla_html.='<tr>
							<td>'.$contador.'</td>
							<td>'.$A_rut.'</td>
							<td>'.$A_nombre.'</td>
							<td>'.$A_apellido_P.'</td>
							<td>'.$A_apellido_M.'</td>
							<td>'.$C_sede.'</td>
							<td>'.$C_folio_pagare.'</td>
							<td>'.$C_linea_credito_paga.'</td>
							<td>'.$C_fecha_generacion.'</td>
							<td>'.$C_fecha_inicio.'</td>
							<td>'.$C_fecha_fin.'</td>
							<td>'.$dias_diferencia_pagare.'</td>
							<td>'.$CUO_valor.'</td>
							<td>'.$CUO_fechavenc.'</td>
							<td>'.$meses_diferencia.'</td>
							<td>'.$aux_factor.'</td>
							<td align="right">'.$aux_impuesto.'</td>
						</tr>';
					}
				}
				else
				{
					
				}
			}
			 
		}
		
		$tabla_html.='<tr>
						<td colspan="16">TOTAL impuesto</td>
						<td>'.$SUMA_IMPUESTO.'</td>
						</tr>';
	}
	else
	{
		$tabla_html.='<tr><td colspan="17">Sin Registros encontrados, que pena...:(</td></tr>';
	}
	$sqli->free();
	
	$tabla_html.='</tbody></table>';
	
	echo $tabla_html;
}
else
{
	header("location: index.php");
}
?>