<?php 
//-----------------------------------------//
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Procesos_masivos_excel_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	
	error_reporting(E_ALL);
	set_time_limit(600);
//-----------------------------------------//


		$continuar=false;
		$year_actual=date("Y");
		$mostrar=array("bnm", "bet");///beneficios actuales
		
	$nombre_archivo=base64_decode($_GET["archivo"]);
	if(!empty($nombre_archivo))
	{
		$directorio="../../CONTENEDOR_GLOBAL/trabajos_masivos/";
		if(DEBUG){ echo"Archivo A Procesar: $nombre_archivo<br>";}
		$archivo_a_procesar=$directorio.$nombre_archivo;
////////////////////////////////
//LECTURA DE EXCEL
/** Include path **/
set_include_path(get_include_path() . PATH_SEPARATOR . '../../libreria_publica/PHPExcel-1.7.7/Classes/');
/** PHPExcel_IOFactory */
include('../../libreria_publica/PHPExcel-1.7.7/Classes/PHPExcel/IOFactory.php');

$inputFileName = $archivo_a_procesar;
if(DEBUG){echo 'Cargando Archivo ',pathinfo($inputFileName,PATHINFO_BASENAME),'<br> usando IOFactory para identificar formato<br /><br>';}
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);


$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

$TOTAL_REGISTROS_ARCHIVO=count($sheetData);
///////////////////////////////////////////////////////////////
//fin LECTURA EXCEL
/////////////////////
//comienza escritura archivo

		if(isset($sheetData))
		{ $continuar=true;}
	}

if($continuar)	
{

  $tabla='<table border="1">
	<thead>
		<tr>
			<th>N.</th>
			<th>Presente en archivo</th>
			<th>Sede</th>
			<th>yearIngresoCarrera</th>
			<th>idCarrera</th>
			
			<th>Rut</th>
			<th>Nombre</th>
			<th>Apellido P</th>
			<th>Apellido M</th>
			<th>Aporte BNM sistema</th>
			<th>Aporte BNM archivo</th>
			<th>Aporte BET sistema</th>
			<th>Aporte BET archivo</th>
			<th>Diferencias</th>
		</tr>
     </thead>
<tbody>';

	require("../../../funciones/conexion_v2.php");	
	require("../../../funciones/class_ALUMNO.php");
	include("../../../funciones/funcion.php");	
		$mostrar_alumno=false;
		
		
		$cons_main_1="SELECT DISTINCT(beneficiosEstudiantiles_asignaciones.id_alumno) FROM beneficiosEstudiantiles_asignaciones INNER JOIN contratos2 ON beneficiosEstudiantiles_asignaciones.id_contrato=contratos2.id WHERE contratos2.ano='$year_actual' AND beneficiosEstudiantiles_asignaciones.id_beneficio IN(1,2) ORDER by contratos2.sede";
		$sql_main_1=$conexion_mysqli->query($cons_main_1)or die("MAIN 1".$conexion_mysqli->error);
		$num_reg_M=$sql_main_1->num_rows;
		if(DEBUG){ echo"<br><br>$cons_main_1<br>CANTIDAD: $num_reg_M<br><br>";}
		if($num_reg_M>0)
		{
			
			$cantidad_alumno_BNM=0;
			$cantidad_alumno_BET=0;
			$cantidad_alumno_desc_cantidad=0;
			$cantidad_alumno_desc_porcentaje=0;
			
			$cantidad_alumno_identificados_en_archivo=0;
			$cantidad_datos_invalidos_en_archivo=0;
			
			$SUMA_APORTE_BECA_NUEVO_MILENIO=0;
			$SUMA_APORTE_BECA_EXCELENCIA=0;
			
			$cuenta_alumno_beneficiados=0;
			while($DID=$sql_main_1->fetch_row())
			{
				//-----------------------------//
				$mostrar_alumno=false;
				$diferencias=false;
				$alumno_en_archivo="no";
				$color_BNM="";
				$color_BET="";
			
				
				$ARCHIVO_APORTE_BNM=0;
				$ARCHIVO_APORTE_BET=0;
				
				$alumnos_presente_en_archivo=false;
				$alumno_tiene_algun_beneficio=false;
				//-----------------------------//
				
				$id_alumno=$DID[0];
				
				$ALUMNO=new ALUMNO($id_alumno);
				$ALUMNO->SetDebug(DEBUG);
			
				$A_rut=$ALUMNO->getRut();
				$A_nombre=$ALUMNO->getNombre();
				$A_apellido_P=$ALUMNO->getApellido_P();
				$A_apellido_M=$ALUMNO->getApellido_M();
				
				$A_sede=$ALUMNO->getSedeActual();
				$A_idCarrera=$ALUMNO->getUltimaIdCarreraMat();
				$A_situacion=$ALUMNO->getUltimaSituacionMat();
				$A_yearIngresoCarrera=$ALUMNO->getUltimoYearIngresoMat();
				
				
				if(DEBUG){echo"REVISANDO ALUMNO<br>id_alumno: $id_alumno RUT: $A_rut<br>";}
				
				
					$cons_main="SELECT beneficiosEstudiantiles_asignaciones.id_alumno, id_beneficio, valor, id_contrato, contratos2.ano FROM `beneficiosEstudiantiles_asignaciones` INNER JOIN contratos2 
ON beneficiosEstudiantiles_asignaciones.id_contrato=contratos2.id AND beneficiosEstudiantiles_asignaciones.id_alumno=contratos2.id_alumno WHERE contratos2.ano='$year_actual' AND contratos2.id_alumno='$id_alumno'";
					
					if(DEBUG){ echo"<br>$cons_main<br>";}
					$sql_main=$conexion_mysqli->query($cons_main)or die("MAIN".$conexion_mysqli->error);
					$num_registros=$sql_main->num_rows;
					if(DEBUG){ echo"Numero Registros $num_registros<br>";}
					
					
					$ARRAY_BENEFICIOS=array();
					if($num_registros>0)
					{
						
						while($DB=$sql_main->fetch_row())
						{
							
							if(isset($ARRAY_BENEFICIOS[$DB[0]][$DB[1]])){$ARRAY_BENEFICIOS[$DB[0]][$DB[1]]+=$DB[2];}
							else{$ARRAY_BENEFICIOS[$DB[0]][$DB[1]]=$DB[2];}
							
							
						}
					}
					$sql_main->free();
					if(DEBUG){ echo"BENEFICIOS ASIGNADOS<br>"; var_dump($ARRAY_BENEFICIOS); echo"<br>";}
					
					//-----------------------------------------------------------------//
								if(DEBUG){ echo"Busca en ARCHIVO...<br>";}
								//var_dump($sheetData);
								
							
					$cantidad_elementos_archivo=count($sheetData);
					if(DEBUG){ echo"Cantidad elementos en archivo: $cantidad_elementos_archivo<br>";}
					if($cantidad_elementos_archivo>0)
					{
						foreach($sheetData as $fila => $array_columnas)
						{
							$COLUMNA_A=$array_columnas["A"];
							$COLUMNA_B=strtoupper($array_columnas["B"]);
							
							if(DEBUG){echo"$fila -> $COLUMNA_A - $COLUMNA_B :";}
							$aux_rut=str_inde($COLUMNA_A)."-".str_inde($COLUMNA_B);
							
							if((is_numeric($COLUMNA_A))and(is_string($COLUMNA_B)))
							{ $dato_archivo_valido=true;}
							else{ $dato_archivo_valido=false;}
							
							if($dato_archivo_valido)
							{
								if($A_rut==$aux_rut)
								{ 
									$alumnos_presente_en_archivo=true;
									$ARCHIVO_APORTE_BNM=trim($array_columnas["C"]);
									if(empty($ARCHIVO_APORTE_BNM)){ $ARCHIVO_APORTE_BNM=0;}
									$ARCHIVO_APORTE_BET=trim($array_columnas["D"]);
									if(empty($ARCHIVO_APORTE_BET)){ $ARCHIVO_APORTE_BET=0;}
									$ARCHIVO_DESC_CANTIDAD=trim($array_columnas["E"]);
									if(empty($ARCHIVO_DESC_CANTIDAD)){ $ARCHIVO_DESC_CANTIDAD=0;}
									$ARCHIVO_DESC_PORCENTAJE=trim($array_columnas["F"]);
									if(empty($ARCHIVO_DESC_PORCENTAJE)){ $ARCHIVO_DESC_PORCENTAJE=0;}
							
									$cantidad_alumno_identificados_en_archivo++;
									if(DEBUG){ echo"<strong>Alumno en archivo</strong><br>";}
									$alumno_en_archivo="si";
									
								
									unset($sheetData[$fila]); 
									break;
								}
								else
								{ if(DEBUG){ echo"Alumno No presente en archivo...<br>";}}

							}
							else
							{
								if(DEBUG){ echo"Identificador en Archivo No validos<br>";}
								$cantidad_datos_invalidos_en_archivo++;
								unset($sheetData[$fila]); 
							}
						}
					}
					else
					{
						//sin elementos en archivo
						if(DEBUG){ echo"Sin Elementos en Archivo<br>";}
					}
								//-------------------------------------------------------------------------------------//
					if(isset($ARRAY_BENEFICIOS[$id_alumno][1])){
						$C_aporte_beca_nuevo_milenio=$ARRAY_BENEFICIOS[$id_alumno][1];
					}else{$C_aporte_beca_nuevo_milenio=0;}
					
					if(isset($ARRAY_BENEFICIOS[$id_alumno][2])){
						$C_aporte_beca_excelencia=$ARRAY_BENEFICIOS[$id_alumno][2];
					}else{$C_aporte_beca_excelencia=0;}
								
								//-------------------------------------------------------------------------------------//
								
					if($alumnos_presente_en_archivo)
					{
							//////////////comparativas
							//bnm
							if($C_aporte_beca_nuevo_milenio==$ARCHIVO_APORTE_BNM)
							{ $color_BNM="#00FF00"; }
							else
							{ $color_BNM="#FF0000"; if(DEBUG){ echo"diferencias entre: |$C_aporte_beca_nuevo_milenio| -|$ARCHIVO_APORTE_BNM|<br>";} $diferencias=true;}
							//bet
							if($C_aporte_beca_excelencia==$ARCHIVO_APORTE_BET)
							{ $color_BET="#00FF00";}
							else
							{ $color_BET="#FF0000"; $diferencias=true;}
					
							
					}
					else
					{
						//comparativa para alumnos sin presencia en archivo
						//bnm
							if($C_aporte_beca_nuevo_milenio==$ARCHIVO_APORTE_BNM)
							{ $color_BNM=""; }
							else
							{ $color_BNM="#FFFF00"; if(DEBUG){ echo"advertencia tiene BNM y no esta en archivo: |$C_aporte_beca_nuevo_milenio| -|$ARCHIVO_APORTE_BNM|<br>";} $diferencias=true;}
							
							//bet
							if($C_aporte_beca_excelencia==$ARCHIVO_APORTE_BET)
							{ $color_BET="";}
							else
							{ $color_BET="#FFFF00"; if(DEBUG){ echo"advertencia tiene BET y no esta en archivo: |$C_aporte_beca_nuevo_milenio| -|$ARCHIVO_APORTE_BNM|<br>";} $diferencias=true;}
							///cantidad desc
							
							
					}
									
					////////////////////////////////////////
					if($C_aporte_beca_nuevo_milenio>0)
					{ $tiene_BNM=true; if(DEBUG){ echo"<strong>Tiene BNM</strong><br>";}}
					else
					{ $tiene_BNM=false; if(DEBUG){ echo"No tiene BNM<br>";}}
					
					if($C_aporte_beca_excelencia>0)
					{ $tiene_BET=true; if(DEBUG){ echo"<strong>Tiene BET</strong><br>";}}
					else
					{ $tiene_BET=false; if(DEBUG){ echo"No tiene BET<br>";}}
					
					
					$mostrar_alumno=true;/////---------> todos
							
					if($mostrar_alumno)
					{
						if(DEBUG){ echo"*Mostrar Alumno*<br><br>";}
						
						if($tiene_BET){ $cantidad_alumno_BET++;}
						if($tiene_BNM){ $cantidad_alumno_BNM++;}
						
						
						$cuenta_alumno_beneficiados++;
						/////////////////////////////////////////
						
						if($diferencias){$diferencia_label="si";}
						else{$diferencia_label="no";}
						
						$SUMA_APORTE_BECA_NUEVO_MILENIO+=$C_aporte_beca_nuevo_milenio;
						$SUMA_APORTE_BECA_EXCELENCIA+=$C_aporte_beca_excelencia;
						////////////////////////////////////////
						
						$tabla.='<tr>
						<td>'.$cuenta_alumno_beneficiados.'</td>
						<td>'.$alumno_en_archivo.'</td>
						
						<td>'.$A_sede.'</td>
						<td>'.$A_yearIngresoCarrera.'</td>
						<td>'.$A_idCarrera.'</td>
						
						
						<td>'.$A_rut.'</td>
						<td>'.$A_nombre.'</td>
						<td>'.$A_apellido_P.'</td>
						<td>'.$A_apellido_M.'</td>
						<td bgcolor="'.$color_BNM.'">'.$C_aporte_beca_nuevo_milenio.'</td>
						<td bgcolor="'.$color_BNM.'">'.$ARCHIVO_APORTE_BNM.'</td>
						<td bgcolor="'.$color_BET.'">'.$C_aporte_beca_excelencia.'</td>
						<td bgcolor="'.$color_BET.'">'.$ARCHIVO_APORTE_BET.'</td>
						<td>'.$diferencia_label.'</td>
						</tr>';
					}
					//--------------------------------------------------//
			}
		}
		else
		{
			//sin id ese año
			if(DEBUG){ echo"UID:0<br>";}
		}
		
		$sql_main_1->free();
		
		
		//-------------------------------------------------------------------------//
		
	@mysql_close($conexion);
	$conexion_mysqli->close();

$tabla.='
<tr>
	<td colspan="8"><strong>Totales</strong></td>
    <td>&nbsp;</td>
    <td><strong>$'.number_format($SUMA_APORTE_BECA_NUEVO_MILENIO,0,",",".").'</strong></td>
	<td>&nbsp;</td>
	<td><strong>$'.number_format($SUMA_APORTE_BECA_EXCELENCIA,0,",",".").'</strong></td>
</tr>
</tbody></table>';

$tabla_resumen='<br><table border="1">
<tr>
	<td colspan="2" bgcolor="#FF9933" align="center"><strong>Resumen</strong></td>
</tr>
<tr>
	<td><strong>TOTAL REGISTROS EN ARCHIVO </strong></td>
    <td><strong>'.$TOTAL_REGISTROS_ARCHIVO.'</strong></td>
</tr>
<tr>
	<td><strong>TOTAL ALUMNOS IDENTIFICADOS</strong></td>
    <td><strong>'.$cantidad_alumno_identificados_en_archivo.'</strong></td>
</tr>
<tr>
	<td><strong>TOTAL DATOS INVALIDOS EN ARCHIVO</strong></td>
    <td><strong>'.$cantidad_datos_invalidos_en_archivo.'</strong></td>
   
</tr>
<tr>
	<td><strong>Cantidad Alumnos Con Beca Nuevo Milenio </strong></td>
    <td><strong>'.$cantidad_alumno_BNM.'</strong></td>
   
</tr>
<tr>
	<td><strong>Cantidad Alumnos Con Beca Excelencia Tecnica</strong></td>
    <td><strong>'.$cantidad_alumno_BET.'</strong></td>
   
</tr>

</table>';

if(count($sheetData)>0)
{
	$tabla_no_encontrados='<br><table border="1">
			<tr>
				<td colspan="5" bgcolor="#FF00AA">Alumnos de Archivo No Encontrados</td>
			</tr>
			<tr>
				<td>N</td>
				<td>Rut</td>
				<td>Dv</td>
				<td>BNM</td>
				<td>BET</td>
			</tr>';
			$aux_ne=0;
	foreach($sheetData as $fila => $array_columnas)
	{
		$aux_ne++;
		$tabla_no_encontrados.='<tr>
									<td>'.$aux_ne.'</td>
									<td>'.$array_columnas["A"].'</td>
									<td>'.$array_columnas["B"].'</td>
									<td>'.$array_columnas["C"].'</td>
									<td>'.$array_columnas["D"].'</td>
								 </tr>';
	}
	$tabla_no_encontrados.='</table><br>';
}

if(DEBUG)
{ var_export($_GET);}
else
{
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=coteja_alumnos_beneficiados_$year_actual.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}
	echo $tabla;
	echo $tabla_resumen;
	echo $tabla_no_encontrados;
}
else
{ echo"No se puede continuar...<br>";}
?>