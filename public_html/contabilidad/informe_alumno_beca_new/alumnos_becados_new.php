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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Informe Contratos -  Matriculas</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:96%;
	height:115px;
	z-index:1;
	left: 2%;
	top: 137px;
}
#link {
	text-align: right;
	padding-right: 10px;
}
-->
</style>
</head>
<?php
if($_POST)
{
	require("../../../funciones/conexion_v2.php");	
	if(DEBUG){ var_dump($_POST);}
		
		
		$considerar_semestre=mysqli_real_escape_string($conexion_mysqli, $_POST["considerar_semestre"]);
		
		if($considerar_semestre=="1"){ $considerar_vigencia_del_semestre=true;}
		else{ $considerar_vigencia_del_semestre=false;}
		

		$sede=mysqli_real_escape_string($conexion_mysqli, $_POST["fsede"]);
		$year_ingreso=mysqli_real_escape_string($conexion_mysqli, $_POST["year_ingreso"]);
		$nivel=mysqli_real_escape_string($conexion_mysqli, $_POST["nivel"]);
		$id_carrera=mysqli_real_escape_string($conexion_mysqli, $_POST["carrera"]);
		
		require("../../../funciones/funciones_sistema.php");	
		$nombre_carrera=NOMBRE_CARRERA($id_carrera);
		
		$contrato_year=mysqli_real_escape_string($conexion_mysqli, $_POST["year_vigencia_contrato"]);
		
		$mes_actual=date("m");
		if($mes_actual>=8){ $semestre_actual=2;}
		else{ $semestre_actual=1;}
		
		$contrato_semestre=$semestre_actual;
		
		$mostrar_2=$_POST["mostrar_2"];
		
		if(isset($_POST["mostrar"]))
		{$mostrar=$_POST["mostrar"];}
		else{ $mostrar=array();}
		
		$concatena_mostrar="";
		foreach($mostrar as $nx=>$valorx)
		{
			$concatena_mostrar.=", $valorx ";
		}
		
		
		include("../../../funciones/VX.php");
		$evento="Revisa Informe Alumnos con Beneficios V1 sede: $sede id_carrera: $id_carrera year_contrato:  $contrato_year";
		REGISTRA_EVENTO($evento);
		
		
		$filtro_label="Filtrar X ";
		if($mostrar_2["BNM"]==1){ $FILTRO_BNM=true; $filtro_label.="BNM ";}
		else{ $FILTRO_BNM=false;}
		
		if($mostrar_2["BET"]==1){ $FILTRO_BET=true; $filtro_label.="BET ";}
		else{ $FILTRO_BET=false;}
		
		if($mostrar_2["cantidad_desc"]==1){ $FILTRO_cantidad_desc=true; $filtro_label.="Cantidad Desc ";}
		else{ $FILTRO_cantidad_desc=false;}
		
		if($mostrar_2["porcentaje_desc"]==1){ $FILTRO_porcentaje_desc=true; $filtro_label.="Porcentaje desc ";}
		else{ $FILTRO_porcentaje_desc=false;}
		
		$mostrar_2_X=serialize($mostrar_2);
}
?>
<body>
<h1 id="banner">Administrador - Informe Alumnos Becados</h1>

<div id="link"><br />
<a href="index.php" class="button">Volver a Seleccion</a><br />
<br />
<a href="alumnos_becados_new_xls.php?sede=<?php echo base64_encode($sede);?>&year_ingreso=<?php echo base64_encode($year_ingreso);?>&nivel=<?php echo base64_encode($nivel);?>&carrera=<?php echo base64_encode($nombre_carrera);?>&id_carrera=<?php echo base64_encode($id_carrera);?>&year_vigencia_contrato=<?php echo base64_encode($contrato_year);?>&semestre_vigencia_contrato=<?php echo base64_encode($contrato_semestre);?>&mostrar_2=<?php echo base64_encode($mostrar_2_X);?>&considerar_semestre=<?php echo base64_encode($considerar_semestre);?>" class="button_R" target="_blank">.XLS</a></div>
<div id="apDiv1" class="demo_jui">
  <table width="100%" align="center" border="1">
<thead>
	<tr>
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
    </tr>
     </thead>
<tbody>
<?php

		$mostrar_alumno=false;
		
		echo"<strong>Sede:</strong> $sede <strong>Carrera:</strong> $nombre_carrera<br><strong>Nivel:</strong>$nivel <strong>Año Ingreso:</strong>$year_ingreso<br><strong>Año de Contrato:</strong> $contrato_year<br>".$filtro_label;
		if(DEBUG){ var_export($_POST);}
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
		
		if($id_carrera!="0")
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
		
		$sql_main_1=$conexion_mysqli->query($cons_main_1)or die($conexion_mysqli->error);
		$num_reg_M=$sql_main_1->num_rows;
		if(DEBUG){ echo"<br><br><br><br><br><br><strong>$cons_main_1</strong><br>CANTIDAD: $num_reg_M<br><br>";}
		$aux=0;
		if($num_reg_M>0)
		{
			
			$cantidad_alumno_BNM=0;
			$cantidad_alumno_BET=0;
			$cantidad_alumno_desc_cantidad=0;
			$cantidad_alumno_desc_porcentaje=0;
			$cantidad_alumno_linea_credito=0;
			
			$SUMA_APORTE_BECA_NUEVO_MILENIO=0;
			$SUMA_APORTE_BECA_EXCELENCIA=0;
			$SUMA_ARANCEL=0;
			$SUMA_TOTAL=0;
			$SUMA_TOTALIZADO=0;
			$SUMA_CANTIDAD_DESC=0;
			$SUMA_LINEA_CREDITO=0;
			$cuenta_alumno_beneficiados=0;
			while($DID=$sql_main_1->fetch_row())
			{
				$mostrar_alumno=false;
				
				$mostrar_alumno_1=false;
				$mostrar_alumno_2=false;
				$mostrar_alumno_3=false;
				$mostrar_alumno_4=false;
				
				$aux++;
				$id_alumno=$DID[0];
				//-----------------------------------------------------------------------------//
				if(DEBUG){ echo"------------------------____________________________________--------------------------_____________________--------------<br>";}
				if(DEBUG){ echo"[Contador: $aux] id_alumno a Consultar:$id_alumno<br>";}
				$cons_main_2="SELECT MAX(id) FROM contratos2 WHERE id_alumno='$id_alumno' AND ano='$contrato_year' AND condicion<>'inactivo'";
				if(DEBUG){ echo"<br>>$cons_main_2<br>";}
				$sql_main_2=$conexion_mysqli->query($cons_main_2)or die($conexion_mysqli->error);
				$DCM=$sql_main_2->fetch_row();
					$aux_id_contrato=$DCM[0];
				$sql_main_2->free();
				//---------------------------------------------------------------------------------//
					if(DEBUG){ echo"--->MAX id contrato: $aux_id_contrato<br>";}
					//-------------------------------------------------//
					$cons_main="SELECT alumno.*, contratos2.id as id_contrato, contratos2.semestre, contratos2.ano, contratos2.vigencia, contratos2.beca_nuevo_milenio, contratos2.aporte_beca_nuevo_milenio, contratos2.aporte_beca_excelencia, contratos2.txt_beca, contratos2.cantidad_beca, contratos2.porcentaje_beca, contratos2.arancel, contratos2.total, contratos2.numero_cuotas, contratos2.linea_credito_paga FROM alumno INNER JOIN contratos2 ON alumno.id = contratos2.id_alumno WHERE contratos2.id='$aux_id_contrato' $condicion_sede $condicion_ingreso $condicion_nivel $condicion_carrera  ORDER by id_contrato DESC LIMIT 1";
					
					if(DEBUG){ echo"<br>$cons_main<br>";}
					$sql_main=$conexion_mysqli->query($cons_main)or die($conexion_mysqli->error);
					$num_registros=$sql_main->num_rows;
					if(DEBUG){ echo"Numero Registros $num_registros<br>";}
					
					if($num_registros>0)
					{
						
						while($DB=$sql_main->fetch_assoc())
						{
							$A_id=$DB["id"];
							$A_nombre=$DB["nombre"];
							$A_apellido_P=$DB["apellido_P"];
							$A_apellido_M=$DB["apellido_M"];
							$A_rut=$DB["rut"];				
							$A_carrera=$DB["carrera"];
							$A_nivel=$DB["nivel"];
							$A_year_ingreso=$DB["ingreso"];
							$A_sede=$DB["sede"];
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
								
								
								/*
								foreach($mostrar as $indice => $tipo_beca)
								{
									$tipo_beca=strtolower($tipo_beca);
									$tipo_beca=trim($tipo_beca);
									switch($tipo_beca)
									{
										case"bnm":
											if($tiene_BNM)
											{ $mostrar_alumno=true; if(DEBUG){ echo"$tipo_beca ---> mostrar alumno<br>";}}
											break;
										case"bet":
											if($tiene_BET)
											{ $mostrar_alumno=true; if(DEBUG){ echo"$tipo_beca ---> mostrar alumno<br>";}}
											break;
										case"cantidad_desc":
											if($tiene_desc_cantidad)
											{ $mostrar_alumno=true; if(DEBUG){ echo"$tipo_beca ---> mostrar alumno<br>";}}
											break;
										case"porcentaje_desc":	
											if($tiene_desc_porcentaje)
											{ $mostrar_alumno=true; if(DEBUG){ echo"$tipo_beca ---> mostrar alumno<br>";}}
											break;
									}
									
								}*/
								
								//que mostrar
								//BNM
								
								
								
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
								
								/*if($mostrar_alumno_1 or $mostrar_alumno_2 or $mostrar_alumno_3 or $mostrar_alumno_4)
								{ $mostrar_alumno=true;}*/
								
							
								
							if($mostrar_alumno and $contrato_OK)
							{
								if(DEBUG){ echo"<strong>Mostrar Alumno...</strong><br>";}
								
								if($tiene_BET){ $cantidad_alumno_BET++;}
								if($tiene_BNM){ $cantidad_alumno_BNM++;}
								if($tiene_desc_cantidad){ $cantidad_alumno_desc_cantidad++;}
								if($tiene_desc_porcentaje){ $cantidad_alumno_desc_porcentaje++;}
								if($tiene_linea_credito){ $cantidad_alumno_linea_credito++;}
								
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
								
								echo'<tr>
								<td>'.$cuenta_alumno_beneficiados.'</td>
								<td>'.$A_year_ingreso.'</td>
								<td>'.$A_sede.'</td>
								<td>'.$A_situacion.'</td>
								<td>'.$A_carrera.'</td>
								<td>'.$A_nivel.'</td>
								<td>'.$A_rut.'</td>
								<td>'.$A_nombre.'</td>
								<td>'.$A_apellido_P.'</td>
								<td>'.$A_apellido_M.'</td>
								<td align="right" bgcolor="'.$color_1.'">'.$C_aporte_beca_nuevo_milenio.'</td>
								<td align="right" bgcolor="'.$color_2.'">'.$C_aporte_beca_excelencia.'</td>
								<td align="right" bgcolor="'.$color_3.'">'.$C_c_otros_desc.'</td>
								<td align="center" bgcolor="'.$color_4.'">'.$C_p_otros_desc.'-'.$totalizado_desc.'</td>
								<td align="center">'.$C_num_cuotas.'</td>
								<td align="right">'.$C_linea_credito.'</td>
								</tr>';
							}
							else
							{
								if(DEBUG){ echo"<strong>NO Mostrar Alumno...</strong><br>";}
							}
						}
					}
					else
					{
						if(DEBUG){ echo"<strong>SIN REGISTROS de Contratos </strong><br>";}
					}
					//--------------------------------------------------//
					$sql_main->free();
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
?>
<tr>
	<td colspan="9" bgcolor="#00FFaa"><strong>Totales</strong></td>
    <td bgcolor="#00FFaa">&nbsp;</td>
    <td align="right" bgcolor="#00FFaa"><strong>$<?php echo number_format($SUMA_APORTE_BECA_NUEVO_MILENIO,0,",",".");?></strong></td>
    <td align="right" bgcolor="#00FFaa"><strong>$<?php echo number_format($SUMA_APORTE_BECA_EXCELENCIA,0,",",".");?></strong></td>
     <td align="right" bgcolor="#00FFaa"><strong>$<?php echo number_format($SUMA_CANTIDAD_DESC,0,",",".");?></strong></td>
     <td align="right" bgcolor="#00FFaa"><strong>$<?php echo number_format($SUMA_TOTALIZADO,0,",",".");?></strong></td>
    <td align="right" bgcolor="#00FFaa"><strong>$<?php echo number_format($SUMA_LINEA_CREDITO,0,",",".");?></strong></td>
    </tr>
<tr>
	<td colspan="6"><strong>Cantidad Alumnos Con Beca Nuevo Milenio </strong></td>
    <td><strong><?php echo $cantidad_alumno_BNM;?></strong></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
</tr>
<tr>
	<td colspan="6"><strong>Cantidad Alumnos Con Beca Excelencia Tecnica</strong></td>
    <td><strong><?php echo $cantidad_alumno_BET;?></strong></td>
        <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
</tr>
<tr>
	<td colspan="6"><strong>Cantidad Alumnos Con Desc. cantidad</strong></td>
    <td><strong><?php echo $cantidad_alumno_desc_cantidad;?></strong></td>
        <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
</tr>
<tr>
  <td colspan="6"><strong>Cantidad Alumnos Con Desc. porcentaje</strong></td>
  <td><strong><?php echo $cantidad_alumno_desc_porcentaje;?></strong></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td colspan="6"><strong>Cantidad Alumnos Con Linea de Credito</strong></td>
  <td><strong><?php echo $cantidad_alumno_linea_credito;?></strong></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
</tbody>
</table>
</div>
</body>
</html>