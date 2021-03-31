<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_seguimientoXCohorte_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("server_cohorte.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"GENERA_INFORME");
$xajax->register(XAJAX_FUNCTION,"CARGA_ASIGNATURA");
////////////////////////////////////////////
function GENERA_INFORME($FORMULARIO)
{
	
	$total_nivel_1_matriculados=0;
	$total_nivel_2_matriculados=0;
	$total_nivel_3_matriculados=0;
	$total_nivel_4_matriculados=0;
	$total_nivel_5_matriculados=0;
	
	sleep(2);
	$contador=0;
	$graficar=true;
	$div_graficos="grafico";
	$carrera=base64_decode($FORMULARIO["carrera"]);
	$id_carrera=$FORMULARIO["carrera"];
	$year=$FORMULARIO["year_ingreso"];
	$sede=$FORMULARIO["fsede"];
	
	$ver_alumnos=$FORMULARIO["ver_alumno"];
	
	
	if($ver_alumnos=="si")
	{ $ver_alumnos=true;}
	else{ $ver_alumnos=false;}
	
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/funciones_sistema.php");
	
	$nombre_carrera=NOMBRE_CARRERA($id_carrera);
	$div_asignatura="Layer3";
	$objResponse = new xajaxResponse();
	
	$year_actual=date("Y");
	$mes_actual=date("m");
	if($ver_alumnos)
	{
		$datos_tabla='<table width="100%" id="example">
<thead>
	<tr>
		<th colspan="17">Alumno de '.$nombre_carrera.' ingreso '.$year.'</th>
	</tr>
	<tr>
	<td>N.</td>
	<td>Run</td>
	<td>Nombre</td>
	<td>Apellido P</td>
	<td>Apellido M</td>
	<td>Nivel 1</td>
	<td>Nivel 2</td>
	<td>Nivel 3</td>
	<td>Nivel 4</td>
	<td>Nivel 5</td>
	<td>Cantidad Semestres</td>
	<td colspan="5"> Contratos X periodo</td>
	</tr>
</thead>
<tbody>';}
		
		$txt_html="";
		$alumnos_titulados=0;
		$alumnos_egresados=0;
		
		$cons="SELECT DISTINCT(id_alumno) FROM contratos2 INNER JOIN alumno ON contratos2.id_alumno = alumno.id WHERE alumno.sede='$sede' AND alumno.id_carrera='$id_carrera' AND contratos2.ano='$year' AND alumno.ingreso='$year' ORDER by alumno.apellido_P, alumno.apellido_M";
		$sql=mysql_query($cons)or die(mysql_error());
		$num_registros=mysql_num_rows($sql);
		if($num_registros>0)
		{
			$aux=0;
			while($A=mysql_fetch_row($sql))
			{
				$aux++;
				$txt_html.='<br><br><strong>'.$aux.'</strong><br>';
				$id_alumno=$A[0];
				
				$nivel_1_matriculado=true;
				$nivel_2_matriculado=false;
				$nivel_3_matriculado=false;
				$nivel_4_matriculado=false;
				$nivel_5_matriculado=false;
				
				$contador++;	
				
				$cons_A="SELECT rut, nombre, apellido_P, apellido_M, situacion FROM alumno WHERE id='$id_alumno' AND id_carrera='$id_carrera' LIMIT 1";
				$sql_A=mysql_query($cons_A)or die(mysql_error());
				$DA=mysql_fetch_assoc($sql_A);
					$A_rut=$DA["rut"];
					$A_nombre=$DA["nombre"];
					$A_apellido_P=$DA["apellido_P"];
					$A_apellido_M=$DA["apellido_M"];
					$A_situacion=$DA["situacion"];
				mysql_free_result($sql_A);

				switch($A_situacion)
				{
					case"T":
						$alumnos_titulados+=1;
						break;
					case"EG":
						$alumnos_egresados+=1;
						break;
				}
				
				$aux_semestre=1;
				$year_consulta=$year;
				$continuar=true;
				$suma_semestres_alumno=0;
				$columnas="";
				$year_verificados=2;
				
				while($continuar)
				{
					if($aux_semestre>2)
					{ 
						$aux_semestre=1;
						$year_consulta++;
					}
					$year_transcurridos=($year_consulta-$year);
					if($year_transcurridos==$year_verificados)
					{$continuar=false;}
					
					$cons_C="SELECT * FROM contratos2 WHERE id_alumno='$id_alumno' AND semestre='$aux_semestre' AND ano='$year_consulta'";
					
					$acumulas.="<br>".$cons_C;
					$sql_C=mysql_query($cons_C)or die(mysql_error());
					$num_contratos=mysql_num_rows($sql_C);
					
					$columnas.='<td>'.$num_contratos.'</td>';
					if($num_contratos>0)
					{
						while($C=mysql_fetch_assoc($sql_C))
						{
							$C_id=$C["id"];
							$C_condicion=$C["condicion"];
							$C_vigencia=$C["vigencia"];
							$C_semestre=$C["semestre"];
							$C_year=$C["ano"];
							$C_nivel_alumno=$C["nivel_alumno"];
							
							
							if(DEBUG){$txt_html.="($id_alumno) id_contrato:$C_id Semestre:$C_semestre Year:$C_year Vigencia: $C_vigencia Nivel Contrato->$C_nivel_alumno|Condicion: $C_condicion|";}
							if(($C_condicion=="OK")or($C_condicion=="old")or($C_condicion=="ok")or($C_condicion=="OLD")or($C_condicion=="retiro"))
							{
								switch($C_vigencia)
								{
									case "semestral":
										switch($C_nivel_alumno)
										{
											case 2:
												$nivel_2_matriculado=true;
												break;
											case 3:
												$nivel_3_matriculado=true;
												break;
											case 4:
												$nivel_4_matriculado=true;
												break;
											case 5:
												$nivel_5_matriculado=true;
												break;					
										}
										if($C_semestre==1)
										{ 
											if(isset($SEMESTRE_1[$year_consulta])){$SEMESTRE_1[$year_consulta]++;}
											else{$SEMESTRE_1[$year_consulta]=1;}
											if(DEBUG){ $txt_html.=" SUMA 1 SEMESTRE ";}
										}
										else
										{ 
											if(isset($SEMESTRE_2[$year_consulta])){$SEMESTRE_2[$year_consulta]++;}
											else{$SEMESTRE_2[$year_consulta]=1;}
											if(DEBUG){ $txt_html.=" SUMA 2 SEMESTRE ";}
										}
										$suma_semestres_alumno+=1;
										break;
									case"anual":
										if(DEBUG){ $txt_html.=" SUMA 1-2 SEMESTRE ";}
										//si veo contratos del año actual considero el contrato hasta el semestre 1, aunque sea ANUAL
										//salvo que el mes actual sea mayor a 8, porque desde agosto cuenta para nosotros el 2 semestre
										switch($C_nivel_alumno)
											{
												case 1:
													$nivel_1_matriculado=true;
													$nivel_2_matriculado=true;
													break;
												case 2:
													$nivel_1_matriculado=true;
													$nivel_2_matriculado=true;
													break;
												case 3:
													$nivel_3_matriculado=true;
													$nivel_4_matriculado=true;
													break;
												case 4:
													$nivel_3_matriculado=true;
													$nivel_4_matriculado=true;
													break;
												case 5:
													$nivel_5_matriculado=true;
													break;					
											}
										if($year_consulta==$year_actual)
										{
											if($mes_actual>=8)
											{
												$suma_semestres_alumno+=2;
												if(isset($SEMESTRE_1[$year_consulta])){$SEMESTRE_1[$year_consulta]++;}
											else{$SEMESTRE_1[$year_consulta]=1;}
												
												if(isset($SEMESTRE_2[$year_consulta])){$SEMESTRE_2[$year_consulta]++;}
											else{$SEMESTRE_2[$year_consulta]=1;}
											}
											else
											{
												$suma_semestres_alumno+=1;
												if(isset($SEMESTRE_1[$year_consulta])){$SEMESTRE_1[$year_consulta]++;}
											else{$SEMESTRE_1[$year_consulta]=1;}
											}
										}
										else
										{
											$suma_semestres_alumno+=2;
											if(isset($SEMESTRE_1[$year_consulta])){$SEMESTRE_1[$year_consulta]++;}
											else{$SEMESTRE_1[$year_consulta]=1;}
												
												if(isset($SEMESTRE_2[$year_consulta])){$SEMESTRE_2[$year_consulta]++;}
											else{$SEMESTRE_2[$year_consulta]=1;}
										}
										break;	
									default:
											if(DEBUG){ $txt_html.=" NO SUMA -$C_vigencia-";}
								}
								
							}
							if(DEBUG){ $txt_html.="=$suma_semestres_alumno<br>";}
							
						}//while contratos
					}//fin si contratos
					else
					{
						if(DEBUG){ $txt_html.="($id_alumno) sin Contrato en este periodo ->$aux_semestre $year_consulta<br>"; }
					}
					mysql_free_result($sql_C);
					$aux_semestre++;
				}//fin while periodo tiempo
				
				if($ver_alumnos)
				{
					
					if($nivel_1_matriculado){ $nivel_1_label="si"; $total_nivel_1_matriculados++;}
					else{  $nivel_1_label="no";}
					
					if($nivel_2_matriculado){ $nivel_2_label="si"; $total_nivel_2_matriculados++;}
					else{  $nivel_2_label="no";}
					
					if($nivel_3_matriculado){ $nivel_3_label="si"; $total_nivel_3_matriculados++;}
					else{  $nivel_3_label="no";}
					
					if($nivel_4_matriculado){ $nivel_4_label="si"; $total_nivel_4_matriculados++;}
					else{  $nivel_4_label="no";}
					
					if($nivel_5_matriculado){ $nivel_5_label="si"; $total_nivel_5_matriculados++;}
					else{  $nivel_5_label="no";}
					
					$datos_tabla.='<tr>
										<td>'.$contador.'</td>
										<td>'.$A_rut.'</td>
										<td>'.$A_nombre.'</td>
										<td>'.$A_apellido_P.'</td>
										<td>'.$A_apellido_M.'</td>
										<td align="center">'.$nivel_1_label.'</td>
										<td align="center">'.$nivel_2_label.'</td>
										<td align="center">'.$nivel_3_label.'</td>
										<td align="center">'.$nivel_4_label.'</td>
										<td align="center">'.$nivel_5_label.'</td>
										<td align="center">'.$suma_semestres_alumno.'</td>'.$columnas.'</tr>';
				}
				
				
			}//fin while alumno
		}//fin si alumno
		else
		{
			if($ver_alumnos){
			$datos_tabla.='<tr><td>No hay...</td></tr>';}
		}
		if($ver_alumnos)
		{ $datos_tabla.='<tr>
							<td colspan="5">Totales</td>
							<td>'.$total_nivel_1_matriculados.'</td>
							<td>'.$total_nivel_2_matriculados.'</td>
							<td>'.$total_nivel_3_matriculados.'</td>
							<td>'.$total_nivel_4_matriculados.'</td>
							<td>'.$total_nivel_5_matriculados.'</td>
							<td colspan="6">&nbsp;</td>
						</tr>
						</tbody></table>';}
		if($num_registros>0)
		{
			$tabla_main.='<table width="50%" border="1">
  <thead>
    <tr>
      <th width="25%">A&ntilde;o</th>
      <th width="25%">1 Semestre</th>
      <th width="25%">2 Semestre</th>
    </tr>
    </thead>
    <tr>
      <td width="25%">'.$year.'</td>
      <td width="25%">'.$SEMESTRE_1[$year].'</td>
      <td width="25%">'.$SEMESTRE_2[$year].'</td>
    </tr>
  </table>
  <table width="50%" border="1">
    <thead>
      <tr>
         <th width="25%">A&ntilde;o</th>
      <th width="25%">1 Semestre</th>
      <th width="25%">2 Semestre</th>
      </tr>
    </thead>
    <tr>
      <td width="25%">'.($year+1).'</td>
      <td width="25%">'.$SEMESTRE_1[$year+1].'</td>
      <td width="25%">'.$SEMESTRE_2[$year+1].'</td>
    </tr>
  </table>
  <table width="50%" border="1">
    <thead>
      <tr>
         <th width="25%">A&ntilde;o</th>
      <th width="25%">1 Semestre</th>
      <th width="25%">2 Semestre</th>
      </tr>
    </thead>
    <tr>
       <td width="25%">'.($year+2).'</td>
      <td width="25%">'.$SEMESTRE_1[$year+2].'</td>
      <td width="25%">'.$SEMESTRE_2[$year+2].'</td>
    </tr>
  </table>';
			
			
		}
		else
		{
			$tabla_main='<table width="50%"><thead><th>...</th></thead><tr><td>Sin Datos</td></tr></table>';
		}
		if(DEBUG)
		{ $html=$txt_html." <br> ".$cons." (".$num_registros.") registros<br>".$acumulas;}
		//----------------------------------------------------------------------------------//
		
		$tabla_resumen_2='<table width="50%">
							<thead>
								<tr>
									<th colspan="3">Resumen '.$year_consulta.'</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Condicion</td>
									<td>Cantidad</td>
									<td>%</td>
								</tr>
								<tr>
									<td>Total</td>
									<td>'.$num_registros.'</td>
									<td>100%</td>
								</tr>
								<tr>
									<td>Titulados</td>
									<td>'.$alumnos_titulados.'</td>
									<td>'.number_format((($alumnos_titulados*100)/$num_registros),2,",",".").'</td>
								</tr>
								<tr>
									<td>Egresados</td>
									<td>'.$alumnos_egresados.'</td>
									<td>'.number_format((($alumnos_egresados*100)/$num_registros),2,",",".").'</td>
								</tr>
							</tbody>
						 </table>';
		//------------------------------------------------------------------------------------//		
		$html.=$datos_tabla."<br>".$tabla_main."<br>".$tabla_resumen_2;
		
		include("../../../../funciones/G_chart.php");
		
		
		//-----------------------------------------------------------------------------------------------------//
		
		$periodo_1=$SEMESTRE_1[$year];
		$periodo_2=$SEMESTRE_2[$year];
		$periodo_3=$SEMESTRE_1[$year+1];
		$periodo_4=$SEMESTRE_2[$year+1];
		$periodo_5=$SEMESTRE_1[$year+2];
		$periodo_6=$SEMESTRE_2[$year+2];
		
		if(empty($periodo_1))
		{ $periodo_1=0;}
		if(empty($periodo_2))
		{ $periodo_2=0;}
		if(empty($periodo_3))
		{ $periodo_3=0;}
		if(empty($periodo_4))
		{ $periodo_4=0;}
		if(empty($periodo_5))
		{ $periodo_5=0;}
		if(empty($periodo_6))
		{ $periodo_6=0;}
		
		//echo "P: $periodo_1 $periodo_2 $periodo_3 $periodo_4 $periodo_5 $periodo_6";
 		$concat_cantidad=$periodo_1.",".$periodo_2.",".$periodo_3.",".$periodo_4.",".$periodo_5.",".$periodo_6;
		
		if($periodo_1 > $periodo_2)
		{ $max_year_1=$periodo_1;}
		else{ $max_year_1=$periodo_2;}
		
		if($periodo_3>$periodo_4)
		{$max_year_2=$periodo_3;}
		else{ $max_year_2=$periodo_4;}
		
		if($periodo_5>$periodo_6)
		{$max_year_3=$periodo_5;}
		else{ $max_year_3=$periodo_6;}
		
		if($max_year_1>$max_year_2)
		{ $max_t=$max_year_1;}
		else
		{ $max_t=$max_year_2;}
		
		if($max_year_3>$max_t)
		{ $max_t=$max_year_3;}
		///////////////////////////ARRAY para GRAFICO////////////////////////////////////
		$array_grafico["rango_X"]="|1|2|3|4|5|6|";
		$array_grafico["datos"][]=$concat_cantidad;
		$array_grafico["tipo"]="lc";//"bvs";"lc"
		$array_grafico["rango_Y_auto"]=true;//si true no necesita enviar "rango_Y", se genera automaticamnete
		$array_grafico["dato_max"]=$max_t;
		$array_grafico["etiqueta_izquierda"]="Alumnos";
		$array_grafico["etiqueta_inferior"]="Semestres";
		$array_grafico["titulo"]="Seguimiento";
		$array_grafico["simbologia"]="cantidad Alumnos";
		$array_grafico["colores_lineas_hex"]="F1A1AA";
		$array_grafico["color_titulo_hex"]="F10000";
		$array_grafico["size_titulo"]=20;
		///////////////----------------------------------------------------///////////////
		if($graficar)
		{
			$grafico=GRAFICO_GOOGLE($array_grafico,false,false);
			$objResponse->Assign($div_graficos,"innerHTML",$grafico);
			$html.="<br>".$grafico;
		}
		$objResponse->Assign($div_asignatura,"innerHTML",$html);	
		
		$boton_exportar='<a href="genera_excel.php?id_carrera='.$id_carrera.'&year='.$year.'&sede='.$sede.'" class="button_R" target="_blank">Exportar .xlsx ['.$nombre_carrera.'-'.$year.']</a>';
		$objResponse->Assign("div_ex","innerHTML",$boton_exportar);	
		//-----------------------------------------------------------------------------------------------------//
	mysql_free_result($sql);	
	mysql_close($conexion);	
	
	return $objResponse;
}
$xajax->processRequest();		
?>