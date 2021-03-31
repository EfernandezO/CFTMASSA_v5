<?php 
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG",false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_alumnos_beneficio_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//


		
		$considerar_semestre=base64_decode($_GET["considerar_semestre"]);
		
		if($considerar_semestre=="1"){ $considerar_vigencia_del_semestre=true;}
		else{ $considerar_vigencia_del_semestre=false;}
		
		$sede=base64_decode($_GET["sede"]);
		$year_ingreso=base64_decode($_GET["year_ingreso"]);
		$nivel=base64_decode($_GET["nivel"]);
		$carrera=base64_decode($_GET["carrera"]);
		$id_carrera=base64_decode($_GET["id_carrera"]);
		
		$contrato_year=base64_decode($_GET["year_vigencia_contrato"]);
		$contrato_semestre=base64_decode($_GET["semestre_vigencia_contrato"]);
		$mostrar_1=base64_decode($_GET["mostrar"]);
		$mostrar=explode(",",$mostrar_1);	
		$mostrar_2=base64_decode($_GET["mostrar_2"]);
		$mostrar_2=unserialize($mostrar_2);
		
		if(DEBUG){ var_dump($mostrar_2);}
		
		$filtro_label="Filtrar X ";
		if($mostrar_2["BNM"]==1){ $FILTRO_BNM=true; $filtro_label.="BNM ";}
		else{ $FILTRO_BNM=false;}
		
		if($mostrar_2["BET"]==1){ $FILTRO_BET=true; $filtro_label.="BET ";}
		else{ $FILTRO_BET=false;}
		
		if($mostrar_2["cantidad_desc"]==1){ $FILTRO_cantidad_desc=true; $filtro_label.="Cantidad Desc ";}
		else{ $FILTRO_cantidad_desc=false;}
		
		if($mostrar_2["porcentaje_desc"]==1){ $FILTRO_porcentaje_desc=true; $filtro_label.="Porcentaje desc ";}
		else{ $FILTRO_porcentaje_desc=false;}

  $tabla='<table border="1">
	<thead>
	<tr bgcolor="#33CCFF">
    <th>N.</th>
    <th>Ingreso</th>
	<th>Sede</th>
	<th>Situacion</th>
    <th>Carrera</th>
    <th>Nivel</th>
	<th>Rut</th>
    <th>Nombre</th>
    <th>Apellido P</th>
    <th>Apellido M</th>
    <th>Aporte BNM</th>
    <th>Aporte BET</th>
    <th>Otros Desc</th>
    <th>% otros desc</th>
    <th>Num. Cuotas</th>
    <th>Linea Credito</th>
	<th>Valor Cuota*</th>
	<th>Glosa de Beca o Desc</th>
    </tr>
     </thead>
<tbody>';

	require("../../../funciones/conexion_v2.php");	
		$mostrar_alumno=false;
		
		$mostrar_alumno_1=false;
		$mostrar_alumno_2=false;
		$mostrar_alumno_3=false;
		$mostrar_alumno_4=false;
		
		echo"<strong>Sede:</strong> $sede <strong>Carrera:</strong> ".utf8_decode($carrera)."<br><strong>Nivel:</strong>$nivel <strong>Año Ingreso:</strong>$year_ingreso<br><strong>Año de Contrato:</strong> $contrato_year<br>".$filtro_label;
		$hay_condiciones=true;
				
		if($sede!=="0")
		{
			 $condicion_sede="AND alumno.sede='$sede'";
			 $hay_condiciones=true;
		}
		else
		{ $condicion_sede="";}
		
		if($year_ingreso!="0")
		{ 
			if($hay_condiciones)
			{$condicion_ingreso="AND alumno.ingreso='$year_ingreso'";}
			else
			{ $condicion_ingreso=" alumno.ingreso='$year_ingreso'";}
			$hay_condiciones=true;
		}
		else
		{ $condicion_ingreso="";}
		
		
		if($nivel!="0")
		{ 
			if($hay_condiciones)
			{ $condicion_nivel="AND alumno.nivel='$nivel'";}
			else
			{ $condicion_nivel=" alumno.nivel='$nivel'";}
			$hay_condiciones=true;
		}
		else
		{ $condicion_nivel="";}
		
		if($id_carrera!=0)
		{
			if($hay_condiciones)
			{ $condicion_carrera="AND alumno.id_carrera='$id_carrera'";}
			else
			{ $condicion_carrera="alumno.id_carrera='$id_carrera'";}
			$hay_condiciones=true;
		}
		else
		{ $condicion_carrera="";}
		
		$cons_main_1="SELECT DISTINCT(id_alumno) FROM contratos2 INNER JOIN alumno ON contratos2.id_alumno=alumno.id WHERE ano='$contrato_year' $condicion_sede $condicion_carrera $condicion_nivel $condicion_ingreso AND condicion<>'inactivo' ORDER by contratos2.sede, alumno.ingreso, alumno.carrera, alumno.nivel, alumno.jornada, alumno.apellido_P, alumno.apellido_M";
		$sql_main_1=mysql_query($cons_main_1)or die("MAIN 1".mysql_error());
		$num_reg_M=mysql_num_rows($sql_main_1);
		if(DEBUG){ echo"<br><br>$cons_main_1<br>CANTIDAD: $num_reg_M<br><br>";}
		if($num_reg_M>0)
		{
			
			$cantidad_alumno_BNM=0;
			$cantidad_alumno_BET=0;
			$cantidad_alumno_desc_cantidad=0;
			$cantidad_alumno_desc_porcentaje=0;
			
			$SUMA_APORTE_BECA_NUEVO_MILENIO=0;
			$SUMA_APORTE_BECA_EXCELENCIA=0;
			$SUMA_ARANCEL=0;
			$SUMA_TOTAL=0;
			$SUMA_TOTALIZADO=0;
			$SUMA_CANTIDAD_DESC=0;
			$cuenta_alumno_beneficiados=0;
			$SUMA_LINEA_CREDITO=0;
			while($DID=mysql_fetch_row($sql_main_1))
			{
				$mostrar_alumno=false;
				
				$id_alumno=$DID[0];
				if(DEBUG){ echo"UID:$id_alumno<br>";}
				$cons_main_2="SELECT MAX(id) FROM contratos2 WHERE id_alumno='$id_alumno' AND ano='$contrato_year' AND condicion<>'inactivo'";
				if(DEBUG){ echo"<br>>$cons_main_2<br>";}
				$sql_main_2=mysql_query($cons_main_2)or die(mysql_error());
				$DCM=mysql_fetch_row($sql_main_2);
					$aux_id_contrato=$DCM[0];
				mysql_free_result($sql_main_2);	
					if(DEBUG){ echo"--->MAX id contrato: $aux_id_contrato<br>";}
					//-------------------------------------------------//
					$cons_main="SELECT alumno.*, contratos2.id as id_contrato, contratos2.semestre, contratos2.ano, contratos2.vigencia, contratos2.beca_nuevo_milenio, contratos2.aporte_beca_nuevo_milenio, contratos2.aporte_beca_excelencia, contratos2.txt_beca, contratos2.cantidad_beca, contratos2.porcentaje_beca, contratos2.arancel, contratos2.total, contratos2.linea_credito_paga, contratos2.numero_cuotas FROM alumno INNER JOIN contratos2 ON alumno.id = contratos2.id_alumno WHERE contratos2.id='$aux_id_contrato' $condicion_sede $condicion_ingreso $condicion_nivel $condicion_carrera  ORDER by id_contrato DESC LIMIT 1";
					
					if(DEBUG){ echo"<br>$cons_main<br>";}
					$sql_main=mysql_query($cons_main)or die("MAIN".mysql_error());
					$num_registros=mysql_num_rows($sql_main);
					if(DEBUG){ echo"Numero Registros $num_registros<br>";}
					
					if($num_registros>0)
					{
						
						while($DB=mysql_fetch_assoc($sql_main))
						{
							$A_id=$DB["id"];
							$A_sede=$DB["sede"];
							$A_nombre=$DB["nombre"];
							$A_apellido_P=$DB["apellido_P"];
							$A_apellido_M=$DB["apellido_M"];
							$A_rut=$DB["rut"];				
							$A_carrera=$DB["carrera"];
							$A_nivel=$DB["nivel"];
							$A_year_ingreso=$DB["ingreso"];
							$A_situacion=$DB["situacion"];
							
							$C_id=$DB["id_contrato"];
							$C_semestre=$DB["semestre"];
							$C_ano=$DB["ano"];
							$C_vigencia=$DB["vigencia"];
							$C_beca_nuevo_milenio=$DB["beca_nuevo_milenio"];
							$C_aporte_beca_nuevo_milenio=$DB["aporte_beca_nuevo_milenio"];
							$C_aporte_beca_excelencia=$DB["aporte_beca_excelencia"];
							$C_txt_beca=$DB["txt_beca"];
							
							$C_num_cuotas=$DB["numero_cuotas"];
							
							$C_c_otros_desc=$DB["cantidad_beca"];
							$C_p_otros_desc=$DB["porcentaje_beca"];
							
							
							$C_arancel=$DB["arancel"];
							$C_total=$DB["total"];
							$C_linea_credito=$DB["linea_credito_paga"];
							
							$totalizado_desc=(($C_p_otros_desc*$C_arancel)/100);
							
							if(DEBUG){ echo"<br><br>Verificando Vigencia del Contrato<br> datos del contrato year: $C_ano  semestre: $C_semestre<br>DATOS FIltro: Year: $contrato_year Semestre: $contrato_semestre<br>";}
							switch($C_vigencia)
							{
								case"anual":
									if(DEBUG){ echo"ANUAL<br>";}
									if($C_ano==$contrato_year){ $contrato_OK=true; if(DEBUG){ echo"Cumple con vigencia OK<br>";}}
									else{ $contrato_OK=false; if(DEBUG){ echo"No cumple con Vigencia Error<br>";}}
									break;
								case"semestral":
									if(DEBUG){ echo"SEMESTRAL<br>";}
									if($C_ano==$contrato_year)
									{
										if($considerar_vigencia_del_semestre) 
										{
											if(DEBUG){ echo"Considerar Vigencia del Semestre<br>";}
											if($C_semestre==$contrato_semestre){ $contrato_OK=true; if(DEBUG){ echo"Cumple con vigencia OK<br>";}}
											else{ $contrato_OK=false; if(DEBUG){ echo"NO Cumple con vigencia ERROR<br>";}}
										}
										else
										{
											if(DEBUG){ echo"NO Considerar Vigencia del Semestre<br>";}
											$contrato_OK=true; 
											if(DEBUG){ echo"Cumple con vigencia OK<br>";}
										}
										
									}
									else{ $contrato_OK=false; if(DEBUG){ echo"NO Cumple con vigencia Error<br>";}}
							}
							if(DEBUG){ echo"<br>";}
							
							
							if(DEBUG){ echo"id_alumno: $A_id <br>Rut: $A_rut<br>id_contrato: $C_id Semestre Contrato: $C_semestre Year Contrato: $C_ano BNM: $C_beca_nuevo_milenio Aporte BNM: $C_aporte_beca_nuevo_milenio<br>info: $C_txt_beca<br><br>";}
								
								////////////////////////////////////////
									if($C_aporte_beca_nuevo_milenio>0)
								{ $tiene_BNM=true; if(DEBUG){ echo"<strong>Tiene BNM</strong><br>";} $color_1="#00FF00";}
								else
								{ $tiene_BNM=false; if(DEBUG){ echo"No tiene BNM<br>";} $color_1="";}
								
								if($C_aporte_beca_excelencia>0)
								{ $tiene_BET=true; if(DEBUG){ echo"<strong>Tiene BET</strong><br>";} $color_2="#00FF00";}
								else
								{ $tiene_BET=false; if(DEBUG){ echo"No tiene BET<br>";} $color_2="";}
								
								if($C_c_otros_desc>0)
								{ $tiene_desc_cantidad=true; if(DEBUG){ echo"<strong>Tiene Desc Cantidad</strong><br>";} $color_3="#00FF00";}
								else
								{ $tiene_desc_cantidad=false; if(DEBUG){ echo"No tiene Desc Cantidad<br>";} $color_3="";}
								
								if($C_p_otros_desc>0)
								{ $tiene_desc_porcentaje=true; if(DEBUG){ echo"<strong>Tiene Desc porcentaje</strong><br>";} $color_4="#00FF00";}
								else
								{ $tiene_desc_porcentaje=false; if(DEBUG){ echo"No tiene Desc porcentaje<br>";} $color_4="";}
								
								if($C_linea_credito>0){ $tiene_linea_credito=true;  if(DEBUG){ echo"Tiene Linea de Credito<br>";}}
								else{ $tiene_linea_credito=false; if(DEBUG){ echo"No tiene Linea de Credito<br>";}}
								////////////////////////////////////////////////////////////
								
								
								if($FILTRO_BNM){ if($tiene_BNM){ $mostrar_alumno_1=true;}else{ $mostrar_alumno_1=false;}} 
								else{ $mostrar_alumno_1=true;}
								
								if($FILTRO_BET){ if($tiene_BET){ $mostrar_alumno_2=true;}else{ $mostrar_alumno_2=false;}} 
								else{ $mostrar_alumno_2=true;}
								
								if($FILTRO_cantidad_desc){ if($tiene_desc_cantidad){ $mostrar_alumno_3=true;}else{ $mostrar_alumno_3=false;}} 
								else{ $mostrar_alumno_3=true;}
								
								if($FILTRO_porcentaje_desc){ if($tiene_desc_porcentaje){ $mostrar_alumno_4=true;}else{ $mostrar_alumno_4=false;}} 
								else{ $mostrar_alumno_4=true;}
								//FINAL
								if($mostrar_alumno_1 and $mostrar_alumno_2 and $mostrar_alumno_3 and $mostrar_alumno_4)
								{ $mostrar_alumno=true;}
							
							if($mostrar_alumno and $contrato_OK)
							{
								if(DEBUG){ echo"*Mostrar Alumno*<br><br>";}
								
								if($tiene_BET){ $cantidad_alumno_BET++;}
								if($tiene_BNM){ $cantidad_alumno_BNM++;}
								if($tiene_desc_cantidad){ $cantidad_alumno_desc_cantidad++;}
								if($tiene_desc_porcentaje){ $cantidad_alumno_desc_porcentaje++;}
								
								$cuenta_alumno_beneficiados++;
								/////////////////////////////////////////
								$SUMA_ARANCEL+=$C_arancel;
								$SUMA_TOTAL+=$C_total;
								$SUMA_TOTALIZADO+=$totalizado_desc;
								$SUMA_CANTIDAD_DESC+=$C_c_otros_desc;
								$SUMA_APORTE_BECA_NUEVO_MILENIO+=$C_aporte_beca_nuevo_milenio;
								$SUMA_APORTE_BECA_EXCELENCIA+=$C_aporte_beca_excelencia;
								$SUMA_LINEA_CREDITO+=$C_linea_credito;
								////////////////////////////////////////
								
								$tabla.='<tr>
								<td>'.$cuenta_alumno_beneficiados.'</td>
								<td>'.$A_year_ingreso.'</td>
								<td>'.$A_sede.'</td>
								<td>'.$A_situacion.'</td>
								<td>'.utf8_decode($A_carrera).'</td>
								<td>'.$A_nivel.'</td>
								<td>'.$A_rut.'</td>
								<td>'.utf8_decode($A_nombre).'</td>
								<td>'.utf8_decode($A_apellido_P).'</td>
								<td>'.utf8_decode($A_apellido_M).'</td>
								<td align="right" bgcolor="'.$color_1.'">'.$C_aporte_beca_nuevo_milenio.'</td>
								<td align="right" bgcolor="'.$color_2.'">'.$C_aporte_beca_excelencia.'</td>
								<td align="right" bgcolor="'.$color_3.'">'.$C_c_otros_desc.'</td>
								<td align="center" bgcolor="'.$color_4.'">'.$C_p_otros_desc.'-'.$totalizado_desc.'</td>
								<td>'.$C_num_cuotas.'</td>
								<td>'.$C_linea_credito.'</td>
								<td>'.round(($C_linea_credito/$C_num_cuotas)).'</td>
								<td>'.$C_txt_beca.'</td>
								</tr>';
							}
						}
					}
					else
					{
						if(DEBUG){ echo"SIN REGISTROS<br>";}
					}
					//--------------------------------------------------//
					mysql_free_result($sql_main);
			}
		}
		else
		{
			//sin id ese año
			if(DEBUG){ echo"UID:0<br>";}
		}
		
		mysql_free_result($sql_main_1);
		
		
		//-------------------------------------------------------------------------//
		
	mysql_close($conexion);

$tabla.='</tbody>
<tfoot>
<tr bgcolor="#CCFF99">
	<td colspan="9"><strong>Totales</strong></td>
    <td>&nbsp;</td>
    <td><strong>$'.number_format($SUMA_APORTE_BECA_NUEVO_MILENIO,0,",",".").'</strong></td>
	 <td><strong>$'.number_format($SUMA_APORTE_BECA_EXCELENCIA,0,",",".").'</strong></td>
     <td><strong>$'.number_format($SUMA_CANTIDAD_DESC,0,",",".").'</strong></td>
     <td><strong>$'.number_format($SUMA_TOTALIZADO,0,",",".").'</strong></td>
	  <td>&nbsp;</td>
    <td><strong>$'.number_format($SUMA_LINEA_CREDITO,0,",",".").'</strong></td>
	<td>&nbsp;</td>
</tr>
<tr bgcolor="#CCFF99">
	<td colspan="7"><strong>Cantidad Alumnos Con Beca Nuevo Milenio </strong></td>
    <td><strong>'.$cantidad_alumno_BNM.'</strong></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="6">&nbsp;</td>
</tr>
<tr bgcolor="#CCFF99">
	<td colspan="7"><strong>Cantidad Alumnos Con Beca Excelencia Tecnica</strong></td>
    <td><strong>'.$cantidad_alumno_BET.'</strong></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="6">&nbsp;</td>
</tr>
<tr bgcolor="#CCFF99">
	<td colspan="7"><strong>Cantidad Alumnos Con Desc. cantidad</strong></td>
    <td><strong>'.$cantidad_alumno_desc_cantidad.'</strong></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="6">&nbsp;</td>
</tr>
<tr bgcolor="#CCFF99">
	 <td colspan="7"><strong>Cantidad Alumnos Con Desc. porcentaje</strong></td>
    <td><strong>'. $cantidad_alumno_desc_porcentaje.'</strong></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
   <td colspan="6">&nbsp;</td>
</tr>
</tfoot>
</table>';
if(DEBUG)
{ var_export($_GET);}
else
{
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=alumnos_con_becas.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}
require("../../../funciones/VX.php");	
$evento="Genera informe .XLS alumnso con Beneficio Sede: $sede year_contrato $contrato_year";
REGISTRA_EVENTO($evento);
echo $tabla;
?>