<?php
//------------------------------------//
//----funciones propias del sistema---//
//------------------------------------//
//
//dias morosidad de alumno, cuota
function CALCULA_PERIODO($semestre_consulta, $year_consulta, $semestres, $operacion="+", $semestre_buscado="", $year_buscado="")
{
	if(DEBUG){ echo"<strong>----------------------------INICIO CALCULA_PERIODO---------------------------------</strong><br>";}
	if(DEBUG){ echo"Periodo Inicial [$semestre_consulta - $year_consulta]<br> Operacion: $operacion -> ($semestres) Semestres<br>periodo buscado [$semestre_buscado - $year_buscado]<br>";}
	
	$semestre_X=$semestre_consulta;
	$year_X=$year_consulta;
	
	if(($semestre_buscado>0)and($year_buscado>0)){$buscar=true;}else{$buscar=false;}
	$encontrado=false;
	for($i=0;$i<$semestres;$i++)
	{
		if($buscar)
		{
			if(($semestre_buscado==$semestre_X) and($year_buscado==$year_X))
			{$encontrado=true; if(DEBUG){ echo"periodo encontrado<br>";}}
		}
		
		if($operacion=="+")
		{
			$semestre_X+=1;
			if($semestre_X>2){ $semestre_X=1; $year_X+=1;}
		}
		else
		{
			$semestre_X-=1;
			if($semestre_X<1){$semestre_X=2; $year_X-=1;}
		}
		
		
	}
	if($buscar)
		{
			if(($semestre_buscado==$semestre_X) and($year_buscado==$year_X))
			{$encontrado=true; if(DEBUG){ echo"preiodo encontrado<br>";}}
		}
	
	if(DEBUG){ 
			echo"Periodo Final [$semestre_X - $year_X]<br>"; 
			if($buscar){ if($encontrado){ echo"Encontrado<br>";}else{ echo"NO encontrado<br>";} }
	}

	if(DEBUG){ echo"<strong>----------------------------FIN CALCULA_PERIODO---------------------------------</strong><br>";}
	
	if($buscar){$array_respuesta=array($semestre_X, $year_X, $encontrado);}
	else{$array_respuesta=array($semestre_X, $year_X);}
	return($array_respuesta);
}
function DIAS_MOROSIDAD($id_alumno, $id_cuota=0, $idContrato=0)
{
	if(DEBUG){ echo"<strong>_________________________INICIO FUNCION DIAS_MOROSIDAD___________________________</strong><br>";}
	require("conexion_v2.php");
	$fecha_actual=date("Y-m-d");
	$fecha_actual_time=strtotime($fecha_actual);
	
	if($id_cuota>0){ $condicion_letra=" AND id='$id_cuota'";}
	elseif($idContrato>0){ $condicion_letra=" AND id_contrato='$idContrato'";}
	else{ $condicion_letra=" AND fechavenc<='$fecha_actual'";}
	
	$cons="SELECT fechavenc FROM letras WHERE idalumn='$id_alumno' $condicion_letra AND pagada<>'S' ORDER by fechavenc ASC LIMIT 1";
	$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$num_registros=$sql->num_rows;
	if($num_registros>0){
		$D=$sql->fetch_assoc();
		$aux_fecha_vencimiento=$D["fechavenc"];		
	}
	if(empty($aux_fecha_vencimiento)){ $CUOTA_FECHA_VENCE=$fecha_actual_time; if(DEBUG){ echo"Fecha Vencimiento Cuota: Vacia<br>";}}
	else{$CUOTA_FECHA_VENCE=strtotime($aux_fecha_vencimiento);}
	$sql->free();	
	$dias_diferencia=$fecha_actual_time-$CUOTA_FECHA_VENCE;
	
	$dias_diferencia=round(((($dias_diferencia/60)/60)/24));
	if(DEBUG){ echo"$cons<br>FECHA VENCE:$CUOTA_FECHA_VENCE   - ACTUAL: $fecha_actual_time  - diferencia: $dias_diferencia<br>Fecha Vencimiento: $aux_fecha_vencimiento<br>";}
	$conexion_mysqli->close();
	if(DEBUG){ echo"<strong>_________________________FIN FUNCION DIAS_MOROSIDAD___________________________</strong><br>";}
	return($dias_diferencia);
}
/////

//comprueba si es posible asignar una beca a un alumno
function VERIFICAR_BECA($id_alumno, $id_carrera, $semestre, $year, $id_beca)
{
	if(DEBUG){ echo"<strong>FUNCION:</strong> VERIFICAR_BECA<br>";}
	$cons_1="SELECT COUNT(id) FROM beca_asignaciones WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND semestre='$semestre' AND year='$year' AND id_beca='$id_beca'";
	$sql_1=mysql_query($cons_1)or die("VERIFICAR_BECA ".mysql_error());
		$DB=mysql_fetch_row($sql_1);
			$coincidencias_beca=$DB[0];
			if(empty($coincidencias_beca)){ $coincidencias_beca=0;}
		mysql_free_result($sql_1);	
	//////////////////////	
	if(DEBUG){ echo"---->$cons_1<br>Coincidencias:$coincidencias_beca<br>";}
	if($coincidencias_beca>0)	
	{ $respuesta_beca=false;}
	else
	{ $respuesta_beca=true;}
	return($respuesta_beca);	
}
//////////////////////////////////////////////////////////////////
function ELIMINA_CUOTAS_OLD($id_contrato, $id_alumno)
{
	$cons_del="DELETE FROM letras WHERE id_contrato='$id_contrato' AND idalumn='$id_alumno'";
	if(DEBUG){ echo"FUNCION -> $cons_del<br>";}
	else
	{ mysql_query($cons_del)or die("borra_cuota ".mysql_error());}
}
///////////////////////////////////////////
function CAMBIA_SITUACION_FINANCIERA_ALUMNO($id_alumno, $nueva_condicion="V")
{
	$cons_UP="UPDATE alumno SET situacion_financiera='$nueva_condicion' WHERE id='$id_alumno' LIMIT 1";
	if(DEBUG){ echo"---->$cons_UP<br>";}
	else
	{	mysql_query($cons_UP);}
}
///////////////////////////////////////////////////////////////////
function GENERA_BOLETA($id_receptor, $valor, $sede, $glosa, $fecha_boleta="", $tipo_receptor="alumno")
{
	require("conexion_v2.php");
	if(empty($fecha_boleta))
	{$fecha_boleta=date("Y-m-d");}
	if(DEBUG){ echo"<strong>---------------------FUNCION GENERA_BOLETA--------------------</strong><br>";}
	$folio="0";
	$usuario_activo=$_SESSION["USUARIO"]["id"];
	
	if($tipo_receptor=="alumno"){ $id_utilizable="id_alumno";}
	else{ $id_utilizable="id_empresa";}
	
	$campos="tipo_receptor, $id_utilizable, valor, glosa, fecha, folio, sede, cod_user";
	$valores="'$tipo_receptor', '$id_receptor', '$valor', '$glosa', '$fecha_boleta', '$folio', '$sede', '$usuario_activo'";
	$cons_boleta="INSERT INTO boleta ($campos) VALUES ($valores)";
	if(DEBUG)
	{
		echo"<br><br>BOLETA X-> $cons_boleta<br><br>";
		$num_boleta="id_boleta_debug";
	}
	else
	{
		$conexion_mysqli->query($cons_boleta)or die("genera boleta".$conexion_mysqli->error);
		$num_boleta=$conexion_mysqli->insert_id;
	}
	$conexion_mysqli->close();
	if(DEBUG){ echo"<strong>---------------------FIN FUNCION GENERA_BOLETA--------------------</strong><br>";}
	return($num_boleta);
}
//----------------------------------------------------------------------------//
function EXISTE_CHEQUE($numero_cheque, $banco_cheque)
{
	if(DEBUG){ echo"<strong>---------------------INICIO FUNCION EXISTE_CHEQUE--------------------</strong><br>";}
	require("conexion_v2.php");
		$numero_cheque=mysqli_real_escape_string($conexion_mysqli, $numero_cheque);
		$banco_cheque=mysqli_real_escape_string($conexion_mysqli, $banco_cheque);
		$cons_CH="SELECT COUNT(id) FROM registro_cheques WHERE numero='$numero_cheque' AND banco='$banco_cheque'";
		if(DEBUG){ echo"--->$cons_CH<br>";}
		$sqli_CH=$conexion_mysqli->query($cons_CH)or die($conexion_mysqli->error);
		$CH=$sqli_CH->fetch_row();
			$num_cheque_encontrados=$CH[0];
			if(empty($num_cheque_encontrados)){ $num_cheque_encontrados=0;}
		$sqli_CH->free();	
		if($num_cheque_encontrados>0){$ya_existe_cheque=true; if(DEBUG){ echo"El Cheque ya existe<br>";}}
		else{$ya_existe_cheque=false; if(DEBUG){ echo"El Cheque No existe<br>";}}
	$conexion_mysqli->close();
	if(DEBUG){ echo"<strong>---------------------FIN FUNCION EXISTE_CHEQUE--------------------</strong><br>";}
	return($ya_existe_cheque);
}

//--------------------------------------------------------------------------------//
function REGISTRA_CHEQUE($cheque, $chequeXmatricula_arancel=false)
{
	require("conexion_v2.php");
	if(isset($cheque["movimiento"]))
	{$movimiento=$cheque["movimiento"];}
	else{ $movimiento="I";}
	$fecha_actual=date("Y-m-d");
	$condicion="OK";
	if(DEBUG){ echo"<strong>---------------------FUNCION REGISTRA_CHEQUE--------------------</strong><br>";}
	///
	
	if(isset($cheque["emisor"])){ $emisor=$cheque["emisor"];}
	else{ $emisor="alumno";}
	
	if(DEBUG){ echo"Emisor del Cheque: $emisor<br>Moviento: $movimiento<br>";}
	
	
	if(isset($cheque["id_alumno"])){$id_alumno=$cheque["id_alumno"];}
	else{ $id_alumno=0;}
	
	if(isset($cheque["id_empresa"])){ $id_empresa=$cheque["id_empresa"];}
	else{ $id_empresa=0;}
	
	
	if($movimiento=="I")
	{
		if(DEBUG){ echo"Verificar cheque del emisor ID OK<br>";}
		switch($emisor)
		{
			case"alumno":
				$campo_id="id_alumno='$id_alumno' AND";
				break;
			case"empresa":
				$campo_id="id_empresa='$id_empresa' AND";
		}
	}
	else
	{
		if(DEBUG){ echo"Verificar cheque del emisor ID NO<br>";}
		$campo_id="";
	}
	
	$cheque_numero=$cheque["numero"];
	$cheque_vence=$cheque["fecha_vence"];
	$cheque_banco=$cheque["banco"];
	$valor=$cheque["valor"];
	$sede=$cheque["sede"];
	$glosa_cheque=$cheque["glosa"];
	
	
	$cons_b="SELECT COUNT(id)FROM registro_cheques WHERE $campo_id numero='$cheque_numero' AND banco='$cheque_banco'";
	$sqli=$conexion_mysqli->query($cons_b)or die("FUNCION REGISTRA CHEQUE:".$conexion_mysqli->error);
	$D=$sqli->fetch_row();
	$coincidencias=$D[0];
	
	if($coincidencias>0)
	{$cheque_numero=$cheque_numero."_".$coincidencias;}
	
	$continuar=true;
	
	$sqli->free();
	$campos="";
	$valores="";
	
	
	if($continuar)
	{
		switch($emisor)
		{
			case"alumno":
				$campos="id_alumno, ";
				$valores="'$id_alumno', ";
				break;
			case"empresa":
				$campos="id_empresa, id_alumno, ";
				$valores="'$id_empresa', '$id_alumno', ";
				break;	
		}
		
		$campos.="emisor, numero, fecha_vencimiento, banco, valor, condicion, sede, fecha, glosa, movimiento";
		$valores.="'$emisor', '$cheque_numero', '$cheque_vence', '$cheque_banco', '$valor', '$condicion', '$sede', '$fecha_actual', '$glosa_cheque', '$movimiento'";
		
		$cons_cheque="INSERT INTO registro_cheques ($campos) VALUES ($valores)";
		
		if(DEBUG)
		{
			echo"<br><br>REGISTRO CHEQUE X-> $cons_cheque<br><br>";
			$id_cheque="id_cheque_debug";
		}
		else
		{
			$conexion_mysqli->query($cons_cheque)or die("FUNCION REGISTRA CHEQUE: [insert] ".$conexion_mysqli->error);
			$id_cheque=$conexion_mysqli->insert_id;
		}
	}
	else
	{ if(DEBUG){echo"<br><br>=====> NO continuar...<br>";}}
	$conexion_mysqli->close();
	if(DEBUG){ echo"<strong>---------------------FIN FUNCION REGISTRA_CHEQUE--------------------</strong><br>";}
	return($id_cheque);
}
///////////////////////////////////////////////////////////
//--------------------------------------------------------//
////////////////////////////////////////////////////////////
function REGISTRAR_CERTIFICADO($tipo_certificado, $id_alumno, $rut, $id_carrera, $carrera, $sede, $id_solicitud=0)
{
	if(DEBUG){ echo"<br><strong>FUNCION REGISTRA CERTIFICADO</strong><br>";}
	require("conexion_v2.php");
	$fecha_hora=date("Y-m-d H:i:s");
	$id_user_activo=$_SESSION["USUARIO"]["id"];
	/////////////////////////////////////////////

	$campos="id_solicitud, id_alumno, rut_alumno, id_carrera, carrera_alumno, tipo_certificado, fecha_hora, id_user, sede";
	$valores="'$id_solicitud', '$id_alumno', '$rut', '$id_carrera', '', '$tipo_certificado', '$fecha_hora', '$id_user_activo', '$sede'";
	
	$cons_IN="INSERT INTO registro_certificados($campos) VALUES($valores)";
	if(DEBUG){ echo"$cons_IN<br>"; $id_certificado="0X0";}
	else{$conexion_mysqli->query($cons_IN)or die($conexion_mysqli->error); $id_certificado=$conexion_mysqli->insert_id;}
	
	$cons="SELECT COUNT(id) FROM registro_certificados WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND tipo_certificado='$tipo_certificado'";
	if(DEBUG){ echo"------>$cons<br>";}
			$sqlXX=$conexion_mysqli->query($cons);
			$C=$sqlXX->fetch_row();
			$numero_certificados=$C[0];
			if(empty($numero_certificados))
			{ $numero_certificados=0;}
			$sqlXX->free();
			
		$codigo_generacion=$id_certificado."_".$numero_certificados;	
	if(DEBUG){echo"CODIGO_GENERACION CERTIFICADO-->$codigo_generacion<br>";}
	
	$cons_UP="UPDATE registro_certificados SET codigo_generacion='$codigo_generacion' WHERE id='$id_certificado' AND id_solicitud='$id_solicitud' LIMIT 1";
	///actualiza certificado con codigo
	if(DEBUG){ echo"--->$cons_UP<br>";}
	else
	{$conexion_mysqli->query($cons_UP);}
	///////////////////////////////////////
	if(DEBUG){ echo"<strong>FIN FUNCION</strong><br>";}
	$conexion_mysqli->close();
	return($codigo_generacion);
}
//------------------------------------------------------//
function TIPO_MOROSIDAD($dias_morosidad)
{
	if(DEBUG){ echo"<strong>_________________________INICIO FUNCION TIPO_MOROSIDAD___________________________</strong><br>";}
	if(DEBUG){ echo"Dias de Morosidad: $dias_morosidad<br>";}
	if($dias_morosidad>0)
	{
		if($dias_morosidad<=30)
		{ $tipo_morosidad=1;}
		elseif($dias_morosidad<=60)
		{ $tipo_morosidad=2;}
		elseif($dias_morosidad<=90)
		{ $tipo_morosidad=3;}
		elseif($dias_morosidad<=120)
		{ $tipo_morosidad=4;}
		else
		{ $tipo_morosidad=5;}
	}
	else
	{ $tipo_morosidad=0;}
	if(DEBUG){ echo"Tipo Morosidad Correspondiente: $tipo_morosidad<br>";}
	if(DEBUG){ echo"<strong>_________________________FIN FUNCION TIPO_MOROSIDAD___________________________</strong><br>";}
	return($tipo_morosidad);
}
function TIPO_MOROSIDAD_LABEL($tipo_morosidad)
{
	if(DEBUG){ echo"<strong>_________________________INICIO FUNCION TIPO_MOROSIDAD_LABEL___________________________</strong><br>";}
		switch($tipo_morosidad)
		{ 
			case 0:
				$tipo_morosidad_label="al dia";
				break;
			case 1:
				$tipo_morosidad_label="0-30 dias";
				break;
			case 2:
				$tipo_morosidad_label="31 - 60 dias";	
				break;
			case 3:
				$tipo_morosidad_label="61 - 90 dias";		
				break;
			case 4:
				$tipo_morosidad_label="91 - 120 dias";		
				break;
			case 5:
				$tipo_morosidad_label="120+ dias";		
				break;
		}
	if(DEBUG){ echo"<strong>_________________________FIN FUNCION TIPO_MOROSIDAD_LABEL___________________________</strong><br>";}
	return($tipo_morosidad_label);
}
/////////////////////////////////////////////
//verifica si alumno tiene matricula vigente actual
function VERIFICAR_MATRICULA($id_alumno, $id_carrera, $yearIngresoCarrera, $considerar_vigencia=false, $semestre_automatico=true, $semestre_consulta="", $year_automatico=true, $year_consulta="", $alargar_vigencia_para_5_nivel=false)
{
	require("conexion_v2.php");
	if(DEBUG){ echo"<br>_______________<strong>FUNCION VERIFICAR_MATRICULA</strong>____________________<br>";}
	$matricula_vigente=false;
	$year_actual=date("Y");
	$mes_actual=date("m");
	////////////////////////////////////////////////////
	if($alargar_vigencia_para_5_nivel)
	{ if(DEBUG){ echo"Se Considerar� vigente alumnos de 5 nivel que tenga contrato en el a�o consultado, no utiliza el semestre<br>";}}
	//considero agosto como inicio 2 semestre
	if($semestre_automatico)
	{
		if($mes_actual>=8){ $semestre_actual=2;}
		else{ $semestre_actual=1;}
		if(DEBUG){ echo"Semestre actual calculado Automaticamente: $semestre_actual<br>";}
	}
	else
	{ $semestre_actual=$semestre_consulta; if(DEBUG){ echo"Semestre actual Manual: $semestre_actual<br>";}}
	
	if($year_automatico)
	{ if(DEBUG){ echo"Year actual calculado Automaticamente: $year_actual<br>";} }
	else
	{ 
		if(DEBUG){ echo"Year actual Manual: $year_consulta<br>";}
		$year_actual=$year_consulta;
	}
	
	/////////////////////////////////////////////////////////
	
	
		$cons_xxx1="SELECT * FROM contratos2 WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera' AND ano='$year_actual' ORDER by id";
		$sql_xxx1=$conexion_mysqli->query($cons_xxx1)or die($conexion_mysqli->error);
		$num_contratos=$sql_xxx1->num_rows;
		if(DEBUG){ echo"$cons_xxx1<br>num_contratos: $num_contratos<br>";}
		if($num_contratos>0)
		{
			while($DC=$sql_xxx1->fetch_assoc())
			{
					$C_condicion=strtolower($DC["condicion"]);
					$C_year=$DC["ano"];
					$C_semestre=$DC["semestre"];
					$C_vigencia=$DC["vigencia"];
					$C_nivel_alumno=$DC["nivel_alumno"];
					
					if(DEBUG){ echo"----->condicion: $C_condicion year: $C_year Semestre: $C_semestre Vigencia: $C_vigencia<br>";}
					
				if($considerar_vigencia)
				{
					if(DEBUG){ echo"<strong>Considerar Vigencia de Contrato</strong><br>";}
					if(($C_condicion=="ok")or($C_condicion=="old"))
					{
						switch($C_vigencia)
						{
							case"semestral":
								///alumno de 5 nivel se le considera simepre como contrato anual
								if(($C_nivel_alumno==5)and($alargar_vigencia_para_5_nivel))
								{
									if(DEBUG){ echo"Nivel Alumno=5, solo considerar Vigencia del a�o del contratos...<br>";}
									if($C_year==$year_actual)
									{ $matricula_vigente=true;}
									else
									{ $matricula_vigente=false;}
								}
								else
								{
									if(($C_semestre==$semestre_actual)and($C_year==$year_actual))
									{ $matricula_vigente=true;}
									else
									{ $matricula_vigente=false;}
								}
								break;
							case"anual":
								if($C_year==$year_actual)
								{ $matricula_vigente=true;}
								else
								{ $matricula_vigente=false;}
								break;	
						}
					}
					else
					{ $matricula_vigente=false; }
					
				}
				else
				{
					if(DEBUG){ echo"<strong>No considero Vigencia</strong><br>";}
					if(($C_condicion=="ok")or($C_condicion=="old"))
					{ $matricula_vigente=true;}
				}
				
				if($matricula_vigente){ if(DEBUG){ echo"Alumno con Matricula en este periodo OK<br>";} break;}
				else{ if(DEBUG){ echo"Alumno Sin Matricula en este periodo Error<br>";}}
			}
		}
		else
		{
			if(DEBUG){ echo"Sin Contratos encontrados<br>";}
		}
		$sql_xxx1->free();
		
		if(DEBUG){ if($matricula_vigente){ echo"Alumno Vigente...<br>";}else{ echo"Alumno NO Vigente...<br>";}}
		if(DEBUG){ echo"<br>_______________<strong>FIN FUNCION VERIFICAR_MATRICULA</strong>____________________<br>";}
		$conexion_mysqli->close();
	return($matricula_vigente);
}
//----------------------------------//
//devuleve la condicion del alumno en un periodo [semestre - a�o} determinado
function CONDICION_DE_ALUMNO_PERIODO($id_alumno, $id_carrera, $yearIngresoCarrera, $semestre_consulta="", $year_consulta="")
{
	require("conexion_v2.php");
	$array_respuesta=array();
	$array_datos=array();
	$hay_contrato=false;
	if(DEBUG){ echo"<br>_______________<strong>FUNCION CONDICION_DE_ALUMNO_PERIODO</strong>____________________<br>";}
	if(DEBUG){ echo"DATOS DEL ENTRADA id_alumno: $id_alumno id_carrera: $id_carrera yearIngresoCarrera: $yearIngresoCarrera periodo [$semestre_consulta - $year_consulta] <br>";}
		$cons_xxx1="SELECT * FROM contratos2 WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera' AND ano='$year_consulta' ORDER by id DESC";
		$sql_xxx1=$conexion_mysqli->query($cons_xxx1)or die($conexion_mysqli->error);
		$num_contratos=$sql_xxx1->num_rows;
		if(DEBUG){ echo"$cons_xxx1<br>num_contratos: $num_contratos<br>";}
		if($num_contratos>0)
		{
			while($DC=$sql_xxx1->fetch_assoc())
			{
					$C_id_contrato=$DC["id"];
					$C_condicion=strtolower($DC["condicion"]);
					$C_year=$DC["ano"];
					$C_semestre=$DC["semestre"];
					$C_vigencia=$DC["vigencia"];
					$C_nivel_alumno=$DC["nivel_alumno"];
					$C_nivel_alumno_2=$DC["nivel_alumno_2"];
					$C_jornada=$DC["jornada"];
					$C_fecha_generacion=$DC["fecha_generacion"];
					
					
					if(DEBUG){ echo"<strong>----->id_contrato: $C_id_contrato condicion: $C_condicion year: $C_year Semestre: $C_semestre Vigencia: $C_vigencia</strong><br>";}
					if(DEBUG){ echo"--->Revisando Nivel del alumno al realizar contrato<br>";}
					switch($C_vigencia)
					{
						case"semestral":
							$nivel_alumno_en_contrato=$C_nivel_alumno;
							if(DEBUG){ echo"|---->Como contrato semestral, utilizar nivel del contrato [$C_nivel_alumno]<br>";}
							break;
						case"anual":
							if(DEBUG){ echo"|---->Como contrato anual, revisar nivel segun semestre consulta<br>";}
							if($semestre_consulta==1)
							{$nivel_alumno_en_contrato=$C_nivel_alumno;if(DEBUG){ echo"|---->Como semestre a consultar es [1], utilizar nivel del contrato [$C_nivel_alumno]<br>";}}
							elseif($semestre_consulta==2)
							{

								$nivel_alumno_en_contrato=$C_nivel_alumno_2; 
								if(DEBUG){ echo"|---->Como semestre a consultar es [2], utilizar nivel del contrato 2 [$C_nivel_alumno_2]<br>";}
								if($nivel_alumno_en_contrato>5){ $nivel_alumno_en_contrato=5;}
							}
							break;
					}
					
					
					$C_jornada_alumno=$DC["jornada"];
					$C_sede_alumno=$DC["sede"];
					
					if(DEBUG){ echo"--->Revisando Vigencia del Contrato en periodo contrato<br>";}
					
					switch($C_vigencia)
					{
						case"semestral":
							if(($C_semestre==$semestre_consulta)and($C_year==$year_consulta))
							{ $hay_contrato=true;}
							else
							{ $hay_contrato=false;}
							if(DEBUG){ echo"|---->Contrato semestral del perido [$C_semestre - $C_year]<br>";}
							break;
						case"anual":
							if($C_year==$year_consulta)
							{ $hay_contrato=true;}
							else
							{ $hay_contrato=false;}
							if(DEBUG){ echo"|---->Contrato Anual del a�o [$C_year]<br>";}
							break;	
					}
					
					
				
				if($hay_contrato){ if(DEBUG){ echo"--> contrato concuerda con  periodo OK<br>";} break;}
				else{ if(DEBUG){ echo"--> contrato no concuerda con periodo periodo Error<br>";}}
			}
		}
		else
		{
			if(DEBUG){ echo"--> Sin Contratos encontrados<br>";}
		}
		$sql_xxx1->free();
		//--------------------------------------------------------------------------------------------------///
		if($hay_contrato)
		{ 
			if(DEBUG){echo"<strong>Hay contrato...condicion del contrato:$C_condicion</strong><br>";}
			$array_datos=array("id_contrato"=>$C_id_contrato, "condicion"=>$C_condicion, "nivel_alumno_contrato"=>$nivel_alumno_en_contrato,"jornada"=>$C_jornada, "sede"=>$C_sede_alumno, "fecha_generacion"=>$C_fecha_generacion);
			$array_respuesta=array($hay_contrato,$array_datos);
		}
		else
		{ 
			if(DEBUG){echo"<strong>NO hay contrato...</strong><br>";}
			$array_respuesta=array($hay_contrato,$array_datos);
		}
		
		if(DEBUG){ echo"<br>_______________<strong>FIN FUNCION CONDICION_DE_ALUMNO_PERIODO</strong>____________________<br>";}
		$conexion_mysqli->close();
	return($array_respuesta);
}
//----------------------------------//
function DEUDA_ACTUAL($id_alumno, $fecha_limite="", $idContrato="")
{
	require("conexion_v2.php");
	if($fecha_limite!="")
	{
		$array_fecha_limite=explode("-",$fecha_limite);
		if(checkdate($array_fecha_limite[1],$array_fecha_limite[2],$array_fecha_limite[0]))
		{ $condicion_fecha_limite=" AND fechavenc<='$fecha_limite'";}
		else
		{ $condicion_fecha_limite="";}
	}
	else{ $condicion_fecha_limite="";}
	
	$condicionIdContrato="";
	if($idContrato>0){
		$condicionIdContrato=" AND id_contrato='$idContrato'";
	}
	
	$cons="SELECT SUM(deudaXletra) FROM letras WHERE idalumn='$id_alumno' AND deudaXletra>'0' $condicion_fecha_limite $condicionIdContrato";
	$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$DD=$sql->fetch_row();
	$deuda_actual_alumno=$DD[0];
	if(empty($deuda_actual_alumno)){ $deuda_actual_alumno=0;}
	if(DEBUG){ echo"FUNCION DEUDA ACTUAL: $cons<br>deuda actual= $deuda_actual_alumno<br>";}
	$sql->free();
	$conexion_mysqli->close();
	return($deuda_actual_alumno);
}
//-----------------------------------------------------------------------//
///aplica interes y gastos de cobranza a deuda
//
function DEUDA_ACTUAL_V2($id_alumno, $fecha_limite="")
{
	if(DEBUG){ echo"_______________________________INICIO_FUNCION_DEUDA_ACTUAL_V2__________________________________<br>";}
	if(DEBUG){ echo"id_alumno: $id_alumno<br>";}
	require("conexion_v2.php");
		if($fecha_limite!="")
		{
			$array_fecha_limite=explode("-",$fecha_limite);
			if(checkdate($array_fecha_limite[1],$array_fecha_limite[2],$array_fecha_limite[0]))
			{ $condicion_fecha_limite=" AND fechavenc<='$fecha_limite'";}
			else
			{ $condicion_fecha_limite="";}
		}
		else{ $condicion_fecha_limite="";}
		
		//-----------------------------------------------------------------------------------//
		$consA="SELECT aplicar_intereses, aplicar_gastos_cobranza FROM alumno WHERE id='$id_alumno' LIMIT 1";
		$sqli_A=$conexion_mysqli->query($consA)or die($conexion_mysqli->error);
		$A=$sqli_A->fetch_assoc();

			$aplicar_intereses=$A["aplicar_intereses"];
			$aplicar_gastos_cobranza=$A["aplicar_gastos_cobranza"];
			
			if($aplicar_intereses==1){$aplicar_intereses=true; if(DEBUG){ echo"Aplicar intereses a alumno: si<br>";}}
			else{ $aplicar_intereses=false; if(DEBUG){ echo"Aplicar intereses a Alumno:No<br>";}}
			
			if($aplicar_gastos_cobranza==1){$aplicar_gastos_cobranza=true; if(DEBUG){ echo"Aplicar gastos cobranza alumno: Si<br>";}}
			else{ $aplicar_gastos_cobranza=false; if(DEBUG){ echo"Aplicar gastos Cobranzas Alumno:No<br>";}}
		$sqli_A->free();
		//---------------------------------------------------------------------------------//
		 $consL="SELECT * FROM letras WHERE idalumn='$id_alumno' $condicion_fecha_limite ORDER BY fechavenc";
   		 $sqlL=$conexion_mysqli->query($consL)or die("|-> CUO ".$conexion_mysqli->error);
		 $num_cuotas=$sqlL->num_rows;
		 if(DEBUG){ echo"-->$consL<br>Num cuotas: $num_cuotas<br>";}
		    $SUMA_DEUDA=0;
			$SUMA_VALOR=0;
			$TOTAL_INTERES=0;
			$TOTAL_GASTOS_COBRANZA=0;
		 if($num_cuotas>0)
		 {
			 while($B=$sqlL->fetch_assoc())
			{
				$id_cuota=$B["id"];
				$fechavenc=$B["fechavenc"];
				$valor=$B["valor"];
				$deudaXletra=$B["deudaXletra"];
				
				if($aplicar_intereses)
				{$aux_interes=INTERES_X_ATRASO_V2($id_cuota);}
				else{$aux_interes=0;}
				
				if($aplicar_gastos_cobranza)
				{$aux_gastos_cobranza=GASTOS_COBRANZA_V2($id_cuota);}
				else{$aux_gastos_cobranza=0;}
				
				if(DEBUG){ echo"id_cuota: $id_cuota fecha vencimiento: $fechavenc valor: $valor deuda: $deudaXletra interes: $aux_interes cobranza: $aux_gastos_cobranza<br>";}
				
				$TOTAL_INTERES+=$aux_interes;
				$TOTAL_GASTOS_COBRANZA+=$aux_gastos_cobranza;
				$SUMA_DEUDA+=$deudaXletra;
				$SUMA_VALOR+=$valor;
			}
		 }
		 if(DEBUG){ echo"TOTAL_INTERES: $TOTAL_INTERES<br>TOTAL GASTOS COBRANZA: $TOTAL_GASTOS_COBRANZA DEUDA TOTAL: $SUMA_DEUDA SUMA VALOR: $SUMA_VALOR<br>";}
		 
	$conexion_mysqli->close();
	if(DEBUG){ echo"_________________________________FIN_FUNCION_DEUDA_ACTUAL_V2__________________________________<br>";}
	$array_respuesta=array($SUMA_DEUDA, $TOTAL_INTERES, $TOTAL_GASTOS_COBRANZA);
	return($array_respuesta);
}
//----------------------------------------------------------------//
function NOMBRE_ASIGNACION($id_carrera, $cod_asignatura)
{
	require("conexion_v2.php");
	if(DEBUG){ echo"<br><strong>____________________________________INICIO FUNCION NOMBRE_ASIGNACION_______________________________________________</strong><br>";}
	if(DEBUG){ echo"<br><strong>FUNCION NOMBRE_ASIGNACION</strong><br>Codigo:$cod_asignatura<br>id_carrera: $id_carrera<br>";}
	switch($cod_asignatura)
	{
		case 0:
			$R_ramo="Jefatura";
			$R_nivel=0;
			break;
		case 99:
			$R_ramo="Toma Examen";
			$R_nivel=0;
			break;
		case 98:
			$R_ramo="Revision Informe";
			$R_nivel=0;
			break;
		case 97:
			$R_ramo="Supervision de Practica";
			$R_nivel=0;
			break;
		case 96:
			$R_ramo="Administracion Asignatura";
			$R_nivel=0;
			break;	
		case 95:
			$R_ramo="Taller Complementario";
			$R_nivel=0;	
			break;	
		case 94:
			$R_ramo="Asistencia Reunion";
			$R_nivel=0;	
			break;	
		case 93:
			$R_ramo="Bono Responsabilidad";
			$R_nivel=0;	
			break;		
		case 92:
			$R_ramo="Prestacion de Servicios Profesionales";
			$R_nivel=0;	
			break;			
		case 91:
			$R_ramo="Toma de Pruebas Pendientes";
			$R_nivel=0;	
			break;	
		case 90:
			$R_ramo="Asesoria Centro de Alumnos";
			$R_nivel=0;	
			break;		
		case 89:
			$R_ramo="Movilizacion";
			$R_nivel=0;	
			break;	
		case 88:
			$R_ramo="Proceso Examen Conocimiento Relevante";
			$R_nivel=0;	
			break;	
		case 87:
			$R_ramo="Tutorias";
			$R_nivel=0;	
			break;								
		default:	
			if(DEBUG){ echo"Codigo comun<br>";}
			$cons_a="SELECT ramo, nivel FROM mallas WHERE id_carrera='$id_carrera' AND cod='$cod_asignatura' LIMIT 1";
			$sql_a=$conexion_mysqli->query($cons_a);
				$Da=$sql_a->fetch_assoc();
				$R_ramo=$Da["ramo"];
				$R_nivel=$Da["nivel"];
			$sql_a->free();	
	
	}
	if(DEBUG){ echo"ASIGNACION: $R_ramo<br> Nivel:$R_nivel<br>";}
	$conexion_mysqli->close();
	if(DEBUG){ echo"<br><strong>____________________________________FIN FUNCION NOMBRE_ASIGNACION_______________________________________________</strong><br>";}
	return(array($R_ramo, $R_nivel));
}
function NOMBRE_CARRERA($id_carrera, $incluirVersion=false)
{
	if(DEBUG){ echo"<strong>________________________INICIO FUNCION NOMBRE_CARRERA_________________________________</strong><br>";}
	require("conexion_v2.php");
	$id_carrera=mysqli_real_escape_string($conexion_mysqli, $id_carrera);
	if($id_carrera>0)
	{
		if(DEBUG){ echo"id_carrera $id_carrera mayor a cero, consultar<br>";}
		$cons_cxx="SELECT carrera, version FROM carrera WHERE id='$id_carrera' LIMIT 1";
		$sql_cxx=$conexion_mysqli->query($cons_cxx)or die("NOMBRE_CARRERA ".$conexion_mysqli->error);
		$DCxx=$sql_cxx->fetch_row();
			$nombre_carrera_cxx=$DCxx[0];
			$version_carrera=$DCxx[1];
			
			if($incluirVersion){$nombre_carrera_cxx.=" (V. $version_carrera)";}
		$sql_cxx->free();
	}
	else
	{ $nombre_carrera_cxx="todas"; if(DEBUG){ echo"id_carrera $id_carrera igual a cero, NO consultar<br>";}}
	$conexion_mysqli->close();
	if(DEBUG){ echo"<strong>NOMBRE DE CARRERA: $nombre_carrera_cxx</strong><br>";}
	if(DEBUG){ echo"<strong>___________________________FIN FUNCION NOMBRE_CARRERA_________________________________</strong><br>";}
	return($nombre_carrera_cxx);	
}

//devuelve nombre de grado academico segun codigo suministrado
function NOMBRE_GRADO_ACADEMICO($cod_grado_academico)
{
	if((is_numeric($cod_grado_academico))and($cod_grado_academico>0))
	{
		$array_grado_academico=array(1=>"Doctorado",
								 2=>"Mag�ster",
								 3=>"Especialidad m�dica u odontol�gica",
								 4=>"T�tulo Profesional",
								 5=>"Licenciatura",
								 6=>"T�cnico de Nivel Superior",
								 7=>"T�cnico de Nivel Medio",
								 8=>"Sin t�tulo ni grado acad�mico");
								 
		if(isset($array_grado_academico[$cod_grado_academico]))						 
		{$nombre_grado_academico=$array_grado_academico[$cod_grado_academico]; }
		else
		{$nombre_grado_academico="Codigo invalido";}
	}
	else
	{$nombre_grado_academico="Codigo invalido";}
	return($nombre_grado_academico);
}
//--------------------------------------------------------//
function INTERESES_X_ATRASO_CUOTA($deuda_actual, $dias_morosidad)
{
	if(DEBUG){ echo"FUNCION INTERESES_X_ATRASO_CUOTA<br>";}
	$total_interes_x_atraso=0;
	if($dias_morosidad>0)
	{
		if(DEBUG){ echo"Alumno tiene $dias_morosidad dias de morosidad<br>";}
		$porcentaje_interes_mensual=20.5;
		$porcentaje_interes_diario=(($porcentaje_interes_mensual/100)/30);
		$total_interes_x_atraso=($porcentaje_interes_diario*$dias_morosidad)*$deuda_actual;
	}
	else
	{if(DEBUG){ echo" Sin Dias de Morosidad<br>";}}
	return($total_interes_x_atraso);
}
//------------------------------------------------------------------//
function GASTOS_COBRANZA_CUOTA($deuda_actual, $dias_morosidad)
{
	$total_gasto_cobranza=0;
	if($dias_morosidad>20)
	{
		$valor_uf=23000;
		
		if($deuda_actual<=(10*$valor_uf))
		{ $porcentaje_de_cobro=9;}
		elseif(($deuda_actual>(10*$valor_uf))and($deuda_actual<(50*$valor_uf)))
		{ $porcentaje_de_cobro=6;}
		else{ $porcentaje_de_cobro=3;}
		
		$total_gasto_cobranza=(($porcentaje_de_cobro/100)*$deuda_actual);
		if(DEBUG){ echo"Valor UF:$valor_uf<br> deuda actual: $deuda_actual<br> porcentaje de cobro: $porcentaje_de_cobro<br>Total gasto cobranza:$total_gasto_cobranza<br>";}
		
	}
	else
	{if(DEBUG){ echo"Dias de Morosidad menor o igual a 15<br>";}}
	return($total_gasto_cobranza);
}
//-----------------------------------------------------------------------------------//
function INTERES_X_ATRASO_V2($id_cuota, $fechaConsulta=0)
{
	//------------------------------------------//
	$porcentaje_interes_mensual=20.5;
	$porcentaje_interes_anual=20.5;
	$dias_gracia=6;
	//--------------------------------------------//
	
	if($fechaConsulta==0){$fecha_actual=date("Y-m-d");}
	else{$fecha_actual=$fechaConsulta;}
	
	$fecha_actual_time=strtotime($fecha_actual);
	
	require("conexion_v2.php");
	if(DEBUG){ echo"<strong>FUNCION INTERES_X_ATRASO_V2</strong><br>";}
	//----------------------------------------------------------//
	//datos cuota
	$cons_C="SELECT * FROM letras WHERE id='$id_cuota' LIMIT 1";
	$sqli_C=$conexion_mysqli->query($cons_C);
	$C=$sqli_C->fetch_assoc();
		$C_fecha_vencimiento=$C["fechavenc"];
		$C_fecha_vencimiento_time=strtotime($C_fecha_vencimiento);
		$C_deudaXcuota=$C["deudaXletra"];
		$C_valor=$C["valor"];
	$sqli_C->free();
	///--------------------------------------------------------------//	
	//dias transcurridos desde vencimiento cuota
	
	$dias_morosidad_cuota=($fecha_actual_time-$C_fecha_vencimiento_time);
	$dias_morosidad_cuota=round(((($dias_morosidad_cuota/60)/60)/24));
	if(DEBUG){ echo"Dias Morosidad Cuota: $dias_morosidad_cuota<br>";}
	//----------------------------------------------------------------------------------//	
	if($dias_morosidad_cuota>$dias_gracia)
	{
		//------------------------------------------------------------------------------------//
		$cons_I="SELECT * FROM pagos WHERE id_cuota='$id_cuota' AND por_concepto='interes_x_atraso' ORDER by fechapago";
		$sqli_i=$conexion_mysqli->query($cons_I)or die($conexion_mysqli->error);
		$num_registros=$sqli_i->num_rows;
		if(DEBUG){ echo"$cons_I<br> Num registros intereses: $num_registros<br>";}
		
		if($num_registros>0)
		{
			$interes_x_atraso_pendiente=0;
			$interes_x_atraso_ya_pagado=0;
			$primera_vuelta=true;
			if(DEBUG){ echo"Existes Interes Pagados Asociados<br>";}
			$fecha_pago_anterior="";
			while($I=$sqli_i->fetch_assoc())
			{
				$I_fecha_pago=$I["fechapago"];
				$I_fecha_pago_time=strtotime($I_fecha_pago);
				$I_valor=$I["valor"];
				
				if($primera_vuelta)
				{
					$primera_vuelta=false;
					$dias_transcurridos=($I_fecha_pago_time-$C_fecha_vencimiento_time);	
				}
				else
				{
					$dias_transcurridos=$I_fecha_pago_time-(strtotime($fecha_pago_anterior));
				}
				//calcular monto sobre el cual calcular interes
					$cons_P="SELECT SUM(valor) FROM pagos WHERE id_cuota='$id_cuota' AND por_concepto='arancel' AND fechapago<'$I_fecha_pago'";
					if(DEBUG){ echo"$cons_P<br>";}
					$sqli_P=$conexion_mysqli->query($cons_P)or die($conexion_mysqli->error);
						$P=$sqli_P->fetch_row();
						$suma_pagos=$P[0];
					$sqli_P->free();
					$deuda_cuota_actual=$C_valor-$suma_pagos;
				//calculo dias transcurridos
				$dias_transcurridos=round(((($dias_transcurridos/60)/60)/24));
				if(DEBUG){ echo"->Dias Transcurridos desde ultimo pago: $dias_transcurridos<br>";}
				if(DEBUG){ echo"-->Deuda Actual de Cuota: $deuda_cuota_actual<br>";}
				///------------------------------------------/////---------------------------------------//
				
				if($dias_transcurridos>0)
				{
					if(DEBUG){ echo"--->Calculo Interes<br>";}
					///calculo interes
					$porcentaje_interes_diario=(($porcentaje_interes_anual/100)/360);
					$interes_x_atraso=round((($porcentaje_interes_diario*$dias_transcurridos)*$deuda_cuota_actual),0);
					if(DEBUG){ echo"--->Intere x Atraso(calculado): $interes_x_atraso<br>";}
					
					$interes_x_atraso_pendiente+=($interes_x_atraso-$I_valor);
				}
				else
				{
					$interes_x_atraso-=$I_valor;
					if(DEBUG){ echo"--->No Calculo interes<br>";}
					if(DEBUG){ echo"--->Intere x Atraso(NO calculado): $interes_x_atraso<br>";}
					
					$interes_x_atraso_pendiente-=$I_valor;
				}
				
				
				
				
				if(DEBUG){ echo"---->Interes Pagado: $I_valor<br>";}
				if(DEBUG){ echo"---->Interes Pendiente: $interes_x_atraso_pendiente<br>";}
				
				//-------------------------------------------------------------------------------------//
				$fecha_pago_anterior=$I_fecha_pago;
			}
			$ultima_fecha_pago_interes=$I_fecha_pago_time;
			$dias_morosidad=($fecha_actual_time-$ultima_fecha_pago_interes);
			$dias_morosidad=round(((($dias_morosidad/60)/60)/24));
			if(DEBUG){ echo"Dias diferencia desde ultimo pago interes: $dias_morosidad<br>";}
		}
		else
		{
			$dias_morosidad=($fecha_actual_time-$C_fecha_vencimiento_time);
			$dias_morosidad=round(((($dias_morosidad/60)/60)/24));
			if(DEBUG){ echo"Dias diferencia: $dias_morosidad<br>";}
			$interes_x_atraso_pendiente=0;
		}
		$sqli_i->free();
		if(DEBUG){ echo"interes x atraso pendiente: $interes_x_atraso_pendiente<br>";}
		//----------------------------------------------------------------------------//
		$interes_x_atraso_actual=0;
		if($dias_morosidad>0)
		{
			$porcentaje_interes_diario=(($porcentaje_interes_anual/100)/360);
			$interes_x_atraso_actual=round((($porcentaje_interes_diario*$dias_morosidad)*$C_deudaXcuota),0);
			if(DEBUG){ echo"Alumno tiene $dias_morosidad dias de morosidad<br>Intere x Atraso actual: $interes_x_atraso_actual<br>";}
		}
		else
		{if(DEBUG){ echo" Sin Dias de Morosidad<br>";}}
		
		$TOTAL_INTERES_A_PAGAR=($interes_x_atraso_pendiente+$interes_x_atraso_actual);
		if($TOTAL_INTERES_A_PAGAR<0){$TOTAL_INTERES_A_PAGAR=0;}
		if(DEBUG){ echo"TOTAL INTERES A PAGAR: $TOTAL_INTERES_A_PAGAR<br>";}
	}
	else
	{
		if(DEBUG){ echo"Dentro de Periodo de Gracia No Cobrar Interes<br>";}
		$TOTAL_INTERES_A_PAGAR=0;
	}
	
	$conexion_mysqli->close();

	return($TOTAL_INTERES_A_PAGAR);
}
//--------------------------------------------------------------------------------///
function GASTOS_COBRANZA_V2($id_cuota, $fechaConsulta=0)
{
	//------------------------------------------//
	$dias_gracia=20;
	$valor_uf=23000;
	//--------------------------------------------//
	if($fechaConsulta==0){$fecha_actual=date("Y-m-d");}
	else{$fecha_actual=$fechaConsulta;}
	
	$fecha_actual_time=strtotime($fecha_actual);
	
	require("conexion_v2.php");
	if(DEBUG){ echo"<br><strong>FUNCION GASTOS_COBRANZA_V2</strong><br>";}
	//----------------------------------------------------------//
	//datos cuota
	$cons_C="SELECT * FROM letras WHERE id='$id_cuota' LIMIT 1";
	$sqli_C=$conexion_mysqli->query($cons_C);
	$C=$sqli_C->fetch_assoc();
		$C_fecha_vencimiento=$C["fechavenc"];
		$C_fecha_vencimiento_time=strtotime($C_fecha_vencimiento);
		$C_deudaXcuota=$C["deudaXletra"];
		$C_valor=$C["valor"];
	$sqli_C->free();
	///--------------------------------------------------------------//	
	
	$cons_GC="SELECT * FROM pagos WHERE id_cuota='$id_cuota' AND por_concepto='gastos_cobranza' ORDER by fechapago";
	$sqli_GC=$conexion_mysqli->query($cons_GC)or die($conexion_mysqli->error);
	$num_registros=$sqli_GC->num_rows;
	if(DEBUG){ echo"$cons_GC<br> Num registros gastos cobranza: $num_registros<br>";}
	
	if($num_registros>0)
	{
		$gastos_cobranza_pendiente=0;
		$gastos_cobranza_ya_cancelados=0;
		if(DEBUG){ echo"Gastos de Cobranza<br>";}
		$primera_vuelta=true;
		while($GC=$sqli_GC->fetch_assoc())
		{
			$GC_valor=$GC["valor"];
			$GC_fecha_pago=$GC["fechapago"];
			if(DEBUG){ echo"-->gasto cobranza valor: $GC_valor<br>";}
			$gastos_cobranza_ya_cancelados+=$GC_valor;
			if($primera_vuelta)
			{
				$primera_vuelta=false;
				//calcular monto sobre el cual calcular interes
				$cons_P="SELECT SUM(valor) FROM pagos WHERE id_cuota='$id_cuota' AND por_concepto='arancel' AND fechapago<'$GC_fecha_pago'";
				if(DEBUG){ echo"$cons_P<br>";}
				$sqli_P=$conexion_mysqli->query($cons_P)or die($conexion_mysqli->error);
					$P=$sqli_P->fetch_row();
					$suma_pagos=$P[0];	
				$sqli_P->free();
				$deuda_cuota_actual=$C_valor-$suma_pagos;
				if(DEBUG){ echo"Deuda cuota al primer pago: $deuda_cuota_actual<br>";}
			}
		}
	}
	else
	{
		$gastos_cobranza_pendiente=0;
		$gastos_cobranza_ya_cancelados=0;
		$deuda_cuota_actual=$C_deudaXcuota;
		if(DEBUG){ echo"Deuda cuota sin pagos previos: $deuda_cuota_actual<br>";}
	}
	$sqli_GC->free();
	//-----------------------------------------------------------------------///
	$dias_morosidad=($fecha_actual_time-$C_fecha_vencimiento_time);
	$dias_morosidad=round(((($dias_morosidad/60)/60)/24));
	if(DEBUG){ echo"Dias diferencia: $dias_morosidad<br>";}
	//----------------------------------------------------------------------------//
	$gastos_cobranza_actual=0;
	
	if($dias_morosidad>$dias_gracia)
	{
		if($deuda_cuota_actual<=(10*$valor_uf))
		{ $porcentaje_de_cobro=9;}
		elseif(($deuda_cuota_actual>(10*$valor_uf))and($deuda_cuota_actual<(50*$valor_uf)))
		{ $porcentaje_de_cobro=6;}
		else{ $porcentaje_de_cobro=3;}
		
		$gastos_cobranza_actual=((($porcentaje_de_cobro/100)*$deuda_cuota_actual)-$gastos_cobranza_ya_cancelados);
		if(DEBUG){ echo"Valor UF:$valor_uf<br> deuda actual: $C_deudaXcuota<br> porcentaje de cobro: $porcentaje_de_cobro<br>gasto cobranza actual:$gastos_cobranza_actual<br>Gasto cobranza ya cancelado: $gastos_cobranza_ya_cancelados<br>";}
	}
	else
	{if(DEBUG){ echo"Sin Dias de Morosidad<br>";}}
	
	$TOTAL_GASTOS_COBRANZA=round(($gastos_cobranza_pendiente+$gastos_cobranza_actual),0);
	
	if($TOTAL_GASTOS_COBRANZA<0){$TOTAL_GASTOS_COBRANZA=0;}
	if(DEBUG){ echo"TOTAL GASTOS COBRANZA: $TOTAL_GASTOS_COBRANZA<br>";}
	
	
	$conexion_mysqli->close();

	return($TOTAL_GASTOS_COBRANZA);
}
//--------------------------------------------------//
function NOMBRE_PERSONAL($id_personal)
{
	require("conexion_v2.php");
	$nombre_personal="";
	
	if((is_numeric($id_personal))and($id_personal>0))
	{
		$cons_P000="SELECT * FROM personal WHERE id='$id_personal' LIMIT 1";
		$sqli_P000=$conexion_mysqli->query($cons_P000)or die("NOMBRE_PERSONAL ".$conexion_mysqli->error);
		$PX000=$sqli_P000->fetch_assoc();
			$aux_nombre_personal=$PX000["nombre"];
			$aux_apellido_P_personal=$PX000["apellido_P"];
			$aux_apellido_M_personal=$PX000["apellido_M"];
		$sqli_P000->free();	
		$nombre_personal="$aux_nombre_personal $aux_apellido_P_personal $aux_apellido_M_personal";
	}
	
	
	$conexion_mysqli->close();

	return($nombre_personal);
}

#devuelve el id del personal segun el rut recibido
function ID_PERSONAL($rut_personal)
{
	require("conexion_v2.php");
	$id_personal=0;
	
	if(!empty($rut_personal))
	{
		$rut_personal=mysqli_real_escape_string($conexion_mysqli, $rut_personal);
		$cons_P000="SELECT id FROM personal WHERE rut='$rut_personal' LIMIT 1";
		$sqli_P000=$conexion_mysqli->query($cons_P000)or die("ID_PERSONAL ".$conexion_mysqli->error);
		$PX000=$sqli_P000->fetch_assoc();
			$id_personal=$PX000["id"];
		$sqli_P000->free();	
	}
	
	
	$conexion_mysqli->close();

	return($id_personal);
}

///devuelve el numero de horas de una asignatura en formato semestral o semanal segun los programas de estudios cargados.
function HORAS_PROGRAMA($id_carrera, $cod_asignatura, $tipo="semestral", $metodologia="todas")
{
	if(DEBUG){ echo"_____________________INICIO FUNCION HORAS PROGRAMA__________________________<br>";}
	$TOTAL_SEMANAS=18;
	require("conexion_v2.php");
	$tipo=strtolower($tipo);
	///horas de programa
	$TOTAL_HORAS_PROGRAMA=0;
	if($metodologia!=="todas"){$condicion_metodologia=" AND tipo='$metodologia'";}
	else{ $condicion_metodologia="";}
	
	$cons_HT="SELECT DISTINCT(numero_unidad) FROM programa_estudio WHERE id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' $condicion_metodologia";
	$sqli_HT=$conexion_mysqli->query($cons_HT)or die($conexion_mysqli->error);
	$num_programas=$sqli_HT->num_rows;
	if(DEBUG){ echo"$cons_HT <br> num_programas: $num_programas<br>";}
	if($num_programas>0)
	{
		while($HT=$sqli_HT->fetch_row())
		{
			$aux_numero_unidad=$HT[0];
			$aux_CONS="SELECT cantidad_horas FROM programa_estudio WHERE id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND numero_unidad='$aux_numero_unidad' LIMIT 1";
			$sqli_aux=$conexion_mysqli->query($aux_CONS)or die("HP ".$conexion_mysqli->error);
				$Pnh=$sqli_aux->fetch_row();
				$aux_numero_hora_x_unidad=$Pnh[0];
				if(DEBUG){ echo"----> $aux_CONS<br>numero horas x Unidad: $aux_numero_hora_x_unidad<br>";}
				if(empty($aux_numero_hora_x_unidad)){ $aux_numero_hora_x_unidad=0;}
			$TOTAL_HORAS_PROGRAMA+=$aux_numero_hora_x_unidad;
			$sqli_aux->free();	
		}
	}
	$sqli_HT->free();
	$conexion_mysqli->close();
	
	if($tipo=="semestral")
	{$HRS=$TOTAL_HORAS_PROGRAMA;}
	elseif($tipo="semanal")
	{$HRS=($TOTAL_HORAS_PROGRAMA/$TOTAL_SEMANAS);}
	else{$HRS=0;}
	if(DEBUG){ echo"TOTAL HORAS PROGRAMA: $TOTAL_HORAS_PROGRAMA<br>";}
	if(DEBUG){ echo"_____________________FIN FUNCION HORAS PROGRAMA__________________________<br>";}
	return($HRS);
}

//verifica si Planificacion docente esta OK
function ESTADO_PLANIFICACION_DOCENTE($sede, $year, $semestre, $id_carrera, $cod_asignatura, $jornada, $grupo, $id_funcionario)
{
	$TOTAL_SEMANA_PLANIFICAR=18;
	require("conexion_v2.php");
	//-------------------------------------------------------------------------------------------------------//	
	///horas de programa
	$TOTAL_HORAS_PROGRAMA=0;
	$cons_HT="SELECT DISTINCT(numero_unidad) FROM programa_estudio WHERE id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura'";
	$sqli_HT=$conexion_mysqli->query($cons_HT)or die($conexion_mysqli->error);
	$num_programas=$sqli_HT->num_rows;
	if($num_programas>0)
	{
		while($HT=$sqli_HT->fetch_row())
		{
			$aux_numero_unidad=$HT[0];
			$aux_CONS="SELECT cantidad_horas FROM programa_estudio WHERE id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND numero_unidad='$aux_numero_unidad' LIMIT 1";
			$sqli_aux=$conexion_mysqli->query($aux_CONS)or die("HP ".$conexion_mysqli->error);
				$Pnh=$sqli_aux->fetch_row();
				$aux_numero_hora_x_unidad=$Pnh[0];
				if(empty($aux_numero_hora_x_unidad)){ $aux_numero_hora_x_unidad=0;}
			$TOTAL_HORAS_PROGRAMA+=$aux_numero_hora_x_unidad;
			$sqli_aux->free();	
		}
	}
	$sqli_HT->free();
	//---------------------------------------------------------------------------------------//
	$cons_NH="SELECT SUM(horas_semana) FROM planificaciones WHERE id_carrera='$id_carrera' AND sede='$sede' AND cod_asignatura='$cod_asignatura' AND semestre='$semestre' AND year='$year' AND id_funcionario='$id_funcionario' AND jornada='$jornada' AND grupo='$grupo'";
	$sqli_NH=$conexion_mysqli->query($cons_NH)or die($conexion_mysqli->error);
	$NS=$sqli_NH->fetch_row();
		$total_horas_planificadas=$NS[0];
		if(empty($total_horas_planificadas)){ $total_horas_planificadas=0;}
	$sqli_NH->free();
	//-------------------------------------------------------------------------------------------//
	
	//max numero semana
	$cons_NS="SELECT COUNT(DISTINCT(numero_semana)) FROM planificaciones WHERE id_funcionario='$id_funcionario' AND id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND semestre='$semestre' AND year='$year' AND sede='$sede' AND jornada='$jornada' AND grupo='$grupo'";
	$sqli_NS=$conexion_mysqli->query($cons_NS)or die("NS. ".$conexion_mysqli->error);
	$NS=$sqli_NS->fetch_row();
		$numero_semanas_planificadas=$NS[0];
		if(empty($numero_semanas_planificadas)){$numero_semanas_planificadas=0;}	
	$sqli_NS->free();
	
	//--------------------------------------------------------------------------------------//
	
	if($total_horas_planificadas>=$TOTAL_HORAS_PROGRAMA)
	{ $condicion_horas=true;}
	else
	{ $condicion_horas=false;}
	
	if($numero_semanas_planificadas==$TOTAL_SEMANA_PLANIFICAR)
	{ $condicion_semanas=true;}
	else
	{ $condicion_semanas=false;}
	//-----------------------------------------------------------------------------------------//
	
	$conexion_mysqli->close();
	
	if($condicion_horas and $condicion_semanas)
	{ $estado_planificacion="OK";}
	else
	{ $estado_planificacion="Error";}
	
	return($estado_planificacion);
}
//----------------------------------------------------------------
function ESTADO_PLANIFICACION_DOCENTE_V2($sede, $year, $semestre, $id_carrera, $cod_asignatura, $jornada, $grupo, $id_funcionario)
{
	
	if($id_funcionario>0){$condicionFuncionario="AND id_funcionario='$id_funcionario'";}
	else{$condicionFuncionario="";}
	require("conexion_v2.php");
	$cons="SELECT COUNT(*) FROM planificaciones_v2 WHERE sede='$sede' AND year='$year' AND semestre='$semestre' AND id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND jornada='$jornada' AND grupo='$grupo' $condicionFuncionario";
	$sqli=$conexion_mysqli->query($cons);
	$P=$sqli->fetch_row();
	$NUM_ARCHIVOS=$P[0];
	if(empty($NUM_ARCHIVOS)){$NUM_ARCHIVOS=0;}
	$sqli->free();
	$conexion_mysqli->close();
	
	if($NUM_ARCHIVOS>0){ $respuesta=true;}
	else{ $respuesta=false;}
	
	return $respuesta;
	
}
///--------------------------------------------------------//
function ES_JEFE_DE_CARRERA($id_funcionario, $semestre, $year, $sede)
{
	if(DEBUG){ echo"<br>_____________________<strong>FUNCION ES_JEFE_DE_CARRERA</strong>_________________________<br>";}
	require("conexion_v2.php");
		$id_funcionario=mysqli_real_escape_string($conexion_mysqli,$id_funcionario); 
		$semestre=mysqli_real_escape_string($conexion_mysqli, $semestre);
		$year=mysqli_real_escape_string($conexion_mysqli, $year);
		$sede=mysqli_real_escape_string($conexion_mysqli, $sede);
		
		$cons="SELECT * FROM toma_ramo_docente WHERE id_funcionario='$id_funcionario' AND sede='$sede' AND semestre='$semestre' AND year='$year' AND cod_asignatura='0'";
		$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		$num_reg=$sqli->num_rows;
		if(DEBUG){ echo"-->$cons<br>-->num registros: $num_reg<br>";}
		
		$array_id_carrera=array();
		$es_jefe_de_carrera=false;
		if($num_reg>0)
		{
			$i=0;
			while($AS=$sqli->fetch_assoc())
			{
				$AS_id_carrera=$AS["id_carrera"];
				$array_id_carrera[$i]=$AS_id_carrera;
				$i++;
				$es_jefe_de_carrera=true;
			}
		}
		if(DEBUG)
		{
			if($es_jefe_de_carrera){ echo"Es jefe de carrera <br>"; var_dump($array_id_carrera);}
			else{ echo"No es jefe de carrera<br>";}
		}
	$conexion_mysqli->close();
	$array_respuesta=array($es_jefe_de_carrera, $array_id_carrera);
	if(DEBUG){ echo"<br>_____________________<strong>FIN FUNCION ES_JEFE_DE_CARRERA</strong>_________________________<br>";}
	return($array_respuesta);
}
///--------------------------------------------------------//
function ES_JEFE_DE_CARRERAV2($id_funcionario, $semestre, $year)
{
	if(DEBUG){ echo"<br>_____________________<strong>FUNCION ES_JEFE_DE_CARRERA</strong>_________________________<br>";}
	require("conexion_v2.php");
		$id_funcionario=mysqli_real_escape_string($conexion_mysqli,$id_funcionario); 
		$semestre=mysqli_real_escape_string($conexion_mysqli, $semestre);
		$year=mysqli_real_escape_string($conexion_mysqli, $year);
		
		$cons="SELECT * FROM toma_ramo_docente WHERE id_funcionario='$id_funcionario' AND semestre='$semestre' AND year='$year' AND cod_asignatura='0'";
		$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		$num_reg=$sqli->num_rows;
		if(DEBUG){ echo"-->$cons<br>-->num registros: $num_reg<br>";}
		
		$array_id_carrera=array();
		$es_jefe_de_carrera=false;
		if($num_reg>0)
		{
			$i=0;
			while($AS=$sqli->fetch_assoc())
			{
				$AS_id_carrera=$AS["id_carrera"];
				$AS_sede=$AS["sede"];
				$array_id_carrera[$i][1]=$AS_id_carrera;
				$array_id_carrera[$i][2]=$AS_sede;
				$i++;
				$es_jefe_de_carrera=true;
			}
		}
		if(DEBUG)
		{
			if($es_jefe_de_carrera){ echo"Es jefe de carrera <br>"; var_dump($array_id_carrera);}
			else{ echo"No es jefe de carrera<br>";}
		}
	$conexion_mysqli->close();
	$array_respuesta=array($es_jefe_de_carrera, $array_id_carrera);
	if(DEBUG){ echo"<br>_____________________<strong>FIN FUNCION ES_JEFE_DE_CARRERA</strong>_________________________<br>";}
	return($array_respuesta);
}
//--------------------------------------------
function CARGAR_ARCHIVO($ARRAY_ARCHIVO, $carpeta, $prefijo="", $array_extenciones_permitidas="")
{
	if(DEBUG){ echo"<br>_____________________<strong>FUNCION CARGAR_ARCHIVO</strong>_________________________<br>";}
	
	if(empty($carpeta)){$continuar_1=false; if(DEBUG){ echo"Argumento carpeta Vacio<br>";}}
	else{ $continuar_1=true; if(DEBUG){ echo"Argumento carpeta ok<br>";}}
	
	if((is_array($array_extenciones_permitidas))and(count($array_extenciones_permitidas)>0))
	{ 
		$continuar_2=true; 
		if(DEBUG){ echo"Array de Extenciones personalidadas enviada<br>";}
	}
	else
	{ 
		$continuar_2=false; 
		if(DEBUG){ echo"Array de Extenciones personalidadas NO enviada<br>";}
		$array_extenciones_permitidas=array("pdf", "doc", "docx", "xls", "xlsx", "jpg", "jpeg", "png", "zip", "rar", "ppt", "pptx");
	}
	
	
	$nombre_archivo_new="";
	$ruta=$carpeta."/";
	if(DEBUG){ var_dump($ARRAY_ARCHIVO);}
			
	$archivo=$ARRAY_ARCHIVO["name"];
	$peso=$ARRAY_ARCHIVO["size"];
	$temporal=$ARRAY_ARCHIVO["tmp_name"];
	
	$aux_archivo=explode(".",$archivo);
	
	$nombre_archivo=strtolower($aux_archivo[0]);
	$extencion_archivo=strtolower(end($aux_archivo));
	if(DEBUG){ echo"<br>Archivo: $archivo (peso: $peso)<br>Nombre: $nombre_archivo<br>Extencion: $extencion_archivo<br>";}
	///---------------------------------------------------------//
	if(in_array($extencion_archivo, $array_extenciones_permitidas))
	{
		if(DEBUG){ echo"Archivo Valido Continuar<br>";}
		$nombre_archivo_new=$prefijo.microtime();
		$nombre_archivo_new=str_replace(".","",$nombre_archivo_new);
		$nombre_archivo_new=str_replace(" ","",$nombre_archivo_new).".".$extencion_archivo;//nombre nuevo archivo;
		$ruta.=$nombre_archivo_new;
		if(DEBUG){ echo"Ruta: $ruta<br>";}
		if(move_uploaded_file($temporal, $ruta))
		{
			if(DEBUG){ echo"Archivo Cargado: $ruta<br>";}
			$archivo_cargado=true;
		}
		else
		{
			if(DEBUG){ echo"Archivo NO cargado Error<br>";}
			$archivo_cargado=false;	
		}
	}
	else
	{
		if(DEBUG){ echo"Extencion Incorrecta<br>";}
		$archivo_cargado=false;
	}
	
	$array_respuesta=array($archivo_cargado, $nombre_archivo_new);		
		//--------------------------------------------------------------------------//
	if(DEBUG){ echo"<br>_____________________<strong>FIN_FUNCION CARGAR_ARCHIVO</strong>_________________________<br>";}
	return($array_respuesta);
}
//---------------------------------------------------------//
//devuelve select con jornadas
function CAMPO_SELECCION($nombre_campo, $tipo, $valor_predeterminado="", $mostrar_opcion_todos=false,$funcion="", $id_campo="")
{
	$tipo=strtolower($tipo);
	$campo='';
	if(empty($id_campo)){ $id_campo=$nombre_campo;}
	
	switch($tipo)
	{
		case"jornada":
			$campo='<select name="'.$nombre_campo.'" id="'.$id_campo.'" '.$funcion.'>';
			
			if($mostrar_opcion_todos){ $array_opciones=array("0"=>"Todas", "D"=>"Diurno", "V"=>"Vespertino");}
			else{ $array_opciones=array("D"=>"Diurno", "V"=>"Vespertino");}
			

			foreach($array_opciones as $n => $valor)
			{
				if($n==$valor_predeterminado)
				{ $select='selected="selected"';}
				else
				{ $select='';}
				$campo.='<option value="'.$n.'" '.$select.'>'.$valor.'</option>';
			}
			$campo.='</select>';
			break;
		case"sexo":
			$campo='<select name="'.$nombre_campo.'" id="'.$id_campo.'" '.$funcion.'>';
			
			if($mostrar_opcion_todos){ $array_opciones=array("0"=>"Todas", "F"=>"Femenino", "M"=>"Masculino");}
			else{ $array_opciones=array("F"=>"Femenino", "M"=>"Masculino");}
			

			foreach($array_opciones as $n => $valor)
			{
				if($n==$valor_predeterminado)
				{ $select='selected="selected"';}
				else
				{ $select='';}
				$campo.='<option value="'.$n.'" '.$select.'>'.$valor.'</option>';
			}
			$campo.='</select>';
			break;	
		case"semestre":
			$campo='<select name="'.$nombre_campo.'" id="'.$id_campo.'" '.$funcion.'>';
			
			if($mostrar_opcion_todos){ $array_opciones=array("0"=>"Todos", "1"=>"1", "2"=>"2");}
			else{ $array_opciones=array("1"=>"1", "2"=>"2");}
			

			foreach($array_opciones as $n => $valor)
			{
				if($n==$valor_predeterminado)
				{ $select='selected="selected"';}
				else
				{ $select='';}
				$campo.='<option value="'.$n.'" '.$select.'>'.$valor.'</option>';
			}
			$campo.='</select>';
			break;	
		case"grupo":
			$campo='<select name="'.$nombre_campo.'" id="'.$id_campo.'" '.$funcion.'>';
			if($mostrar_opcion_todos){$campo.='<option value="0">Todas</option>';}
			foreach(range('A', 'Z') as $letra)
			{
				if($letra==$valor_predeterminado)
				{ $select='selected="selected"';}
				else
				{ $select='';}
				$campo.='<option value="'.$letra.'" '.$select.'>'.$letra.'</option>';
			}
			$campo.='</select>';
			break;	
		case"meses":	
			$campo='<select name="'.$nombre_campo.'" id="'.$id_campo.'" '.$funcion.'>';
			$array_opciones=array("0"=>"Todos",
								  "01"=>"Enero",
								  "02"=>"Febrero",
								  "03"=>"Marzo",
								  "04"=>"Abril",
								  "05"=>"Mayo",
								  "06"=>"Junio",
								  "07"=>"Julio",
								  "08"=>"Agosto",
								  "09"=>"Septiembre",
								  "10"=>"Octubre",
								  "11"=>"Noviembre",
								  "12"=>"Diciembre");
			foreach($array_opciones as $n => $valor)
			{
				if($n==$valor_predeterminado)
				{ $select='selected="selected"';}
				else
				{ $select='';}
				$campo.='<option value="'.$n.'" '.$select.'>'.$valor.'</option>';
			}
			$campo.='</select>';
			break;
		case"year":	
			$campo='<select name="'.$nombre_campo.'" id="'.$id_campo.'" '.$funcion.'>';
			$year_actual=date("Y");
			$year_inicio=1980;
			$year_final=$year_actual+1;
				
			for($y=$year_inicio;$y<=$year_final;$y++)
			{
				if($y==$valor_predeterminado)
				{ $select='selected="selected"';}
				else
				{ $select='';}
				
				$campo.='<option value="'.$y.'" '.$select.'>'.$y.'</option>';
			}
			if($mostrar_opcion_todos)
			{
					if($valor_predeterminado=="0"){ $select='selected="selected"';}
					else{ $select="";} 
					$campo.='<option value="0" '.$select.'>Todos</option>';
			}
			$campo.='</select>';
			break;	
		case"sede":	
			$campo='<select name="'.$nombre_campo.'" id="'.$id_campo.'" '.$funcion.'>';
			$array_opciones=array("01"=>"Talca",
								  "02"=>"Linares");
			if($mostrar_opcion_todos)
			{$campo.='<option value="0">Todos</option>';}
								  
			foreach($array_opciones as $n => $valor)
			{
				if($valor==$valor_predeterminado)
				{ $select='selected="selected"';}
				else
				{ $select='';}
				$campo.='<option value="'.$valor.'" '.$select.'>'.$valor.'</option>';
			}
			$campo.='</select>';
			break;
			
		case"sedexprivilegio":
			require("conexion_v2.php");
			
			$habilitarSedeRegistroUsuario=true;// muestra la sede propia del usuario aunque no la tenga como privilegio especifico, para usuarios que no las ven todas
			
			$privilegioUser=$_SESSION["USUARIO"]["privilegio"];
			$id_usuario_actual_x=$_SESSION["USUARIO"]["id"];
			$sede_usuario=$_SESSION["USUARIO"]["sede"];
			
			//echo"--->privilegio: $privilegioUser<br>";
			//llenado de array con datos de sedes
			$consX="SELECT NombreSede, id_sede FROM sede where estado='1'";
			$sqlX=$conexion_mysqli->query($consX)or die($conexion_mysqli->error);
			while($DA=$sqlX->fetch_assoc()){
				$id_sede=$DA["id_sede"];
				$ARRAY_SEDE[$id_sede]["nombreSede"]=$DA["NombreSede"];;
				$ARRAY_SEDE[$id_sede]["mostrar"]=false;
			}
			$sqlX->free();
			
			
			switch($privilegioUser){
				case"finan":
				case"inspeccion":
				case"admi_total":
					//muestro todo en estos casos
					foreach($ARRAY_SEDE as $auxIdSede =>$auxArray){
						$ARRAY_SEDE[$auxIdSede]["mostrar"]=true;
					}
					
					break;
				default:
					//habilito la sede segun privilegios especificos
					foreach($ARRAY_SEDE as $auxIdSede =>$auxArray){
						$consY="SELECT COUNT(id_relacion) FROM personalSede where id_sede='$auxIdSede' AND id_personal='$id_usuario_actual_x'";
						$sqlY=$conexion_mysqli->query($consY)or die($conexion_mysqli->error);
						$DY=$sqlY->fetch_row();
						$numRelaciones=$DY[0];
						if(empty($numRelaciones)){$numRelaciones=0;}
						$sqlY->free();
						if($numRelaciones>0){$ARRAY_SEDE[$auxIdSede]["mostrar"]=true;}
					}
					
				
					//habilito la sede del usuario
					if($habilitarSedeRegistroUsuario){
						foreach($ARRAY_SEDE as $auxIdSede =>$auxArray){
							if($sede_usuario==$auxArray["nombreSede"]){
								$ARRAY_SEDE[$auxIdSede]["mostrar"]=true;
							}
						}
					}
				
			}//fin switch
			
			//comienzo creacion del campo
			$campo='<select name="'.$nombre_campo.'" id="'.$id_campo.'" '.$funcion.'>';
			foreach($ARRAY_SEDE as $auxIdSede => $auxArray)
			{
				$auxMostrar=$auxArray["mostrar"];
				$auxNombreSede=$auxArray["nombreSede"];
				
				if($auxNombreSede==$valor_predeterminado)
				{ $select='selected="selected"';}
				else{ $select='';}
				
				//muestro solo si opcion mostrar es true
				if($auxMostrar){$campo.='<option value="'.$auxNombreSede.'" '.$select.'>'.$auxNombreSede.'</option>';}
			}
			if($mostrar_opcion_todos){$campo.='<option value="0">Todos</option>';}
			$campo.='</select>';
			//--------------------------------------------------------//
			$conexion_mysqli->close();
			break;	
		case"niveles_academicos":
			$campo='<select name="'.$nombre_campo.'" id="'.$id_campo.'" '.$funcion.'>';
			
			if($mostrar_opcion_todos)
			{$array_opciones=array("0"=>"Todos",
								  "1"=>"1",
								  "2"=>"2",
								  "3"=>"3",
								  "4"=>"4",
								  "5"=>"5");
			}
			else
			{
				$array_opciones=array("1"=>"1",
									  "2"=>"2",
									  "3"=>"3",
									  "4"=>"4",
									  "5"=>"5");
			}
								  
			foreach($array_opciones as $n => $valor)
			{
				if($n==$valor_predeterminado)
				{ $select='selected="selected"';}
				else
				{ $select='';}
				$campo.='<option value="'.$n.'" '.$select.'>'.$valor.'</option>';
			}
			$campo.='</select>';
			break;
		case"carreras":
			require("conexion_v2.php");
				$cons_C="SELECT id, carrera FROM carrera";
				$sqli_C=$conexion_mysqli->query($cons_C)or die($conexion_mysqli->error);
				$num_carreras=$sqli_C->num_rows;
				$campo='<select name="'.$nombre_campo.'" id="'.$id_campo.'" '.$funcion.'>';
				
				if($num_carreras>0)
				{
					while($DC=$sqli_C->fetch_assoc())
					{
						$aux_id_carrera=$DC["id"];
						$aux_nombre_carrera=$DC["carrera"];
						if($aux_id_carrera==$valor_predeterminado)
						{ $select='selected="selected"';}
						else
						{ $select='';}
						$campo.='<option value="'.$aux_id_carrera.'" '.$select.'>'.$aux_id_carrera.'_'.$aux_nombre_carrera.'</option>';
					}
					if($mostrar_opcion_todos)
					{
						if($valor_predeterminado==0){ $selectx='selected="selected"';}
						else{ $selectx='';}
						$campo.='<option value="0" '.$selectx.'>Todas</option>';
					}
				}
				else
				{
					$campo.='<option value="">Sin Datos</option>';
				}
				$campo.='</select>';
				$sqli_C->free();
				$conexion_mysqli->close();
			break;	
		case"conceptos_financieros":
			$campo='<select name="'.$nombre_campo.'" id="'.$id_campo.'" '.$funcion.'>';
			
			if($mostrar_opcion_todos)
			{$array_opciones=array("0"=>"Todos",
								  //"1"=>"academicos",
								  "2"=>"certificado",
								  "3"=>"interes",
								  "4"=>"letras",
								  "5"=>"multa",
								  "6"=>"otros ingresos",
								  "7"=>"becas",
								  "8"=>"convalidacion",
								  "9"=>"programas_estudio",
								  "10"=>"derecho a examen");
			}
			else
			{
				$array_opciones=array(//"1"=>"academicos",
									  "2"=>"certificado",
									  "3"=>"interes",
									  "4"=>"letras",
									  "5"=>"multa",
									  "6"=>"otros ingresos",
									  "7"=>"becas",
									  "8"=>"convalidacion",
									  "9"=>"programas_estudio",
									  "10"=>"derecho a examen");
			}
								  
			foreach($array_opciones as $n => $valor)
			{
				if($n==$valor_predeterminado)
				{ $select='selected="selected"';}
				else
				{ $select='';}
				$campo.='<option value="'.$valor.'" '.$select.'>'.$valor.'</option>';
			}
			$campo.='</select>';
			break;	
		case"conceptos_financieros_egresos":
			$campo='<select name="'.$nombre_campo.'" id="'.$id_campo.'" '.$funcion.'>';
			if($mostrar_opcion_todos)
			{$array_opciones=array("0"=>"Todos",
								  "1"=>"franqueo y Correspondencia",
								  "2"=>"gastos legales",
								  "3"=>"materiales de oficina",
								  "4"=>"gastos de fotocopia",
								  "5"=>"gastos de movilizacion",
								  "6"=>"gastos de administracion y venta",
								  "7"=>"mantencion de instalaciones",
								  "8"=>"honorario por pagar",
								  "9"=>"mantencion y aseo",
								  "10"=>"reparacion y mantencion",
								  "11"=>"proveedores",
								  "12"=>"arriendo",
								  "13"=>"gastos de computacion",
								  "14"=>"anticipo de sueldo",
								  "15"=>"gastos de alimentacion",
								  "16"=>"gastos basicos",
								  "17"=>"remuneracion",
								  "18"=>"imposiciones",
								  "19"=>"pago patentes",
								  "20"=>"intereses",
								  "21"=>"insumos tens",
								  "22"=>"fondo de cesantia",
								  "23"=>"publicidad",
								  "24"=>"arancel de verificacion institucional");
								  
			}
			else
			{
				$array_opciones=array("1"=>"franqueo y Correspondencia",
								  "2"=>"gastos legales",
								  "3"=>"materiales de oficina",
								  "4"=>"gastos de fotocopia",
								  "5"=>"gastos de movilizacion",
								  "6"=>"gastos de administracion y venta",
								  "7"=>"mantencion de instalaciones",
								  "8"=>"honorario por pagar",
								  "9"=>"mantencion y aseo",
								  "10"=>"reparacion y mantencion",
								  "11"=>"proveedores",
								  "12"=>"arriendo",
								  "13"=>"gastos de computacion",
								  "14"=>"anticipo de sueldo",
								  "15"=>"gastos de alimentacion",
								  "16"=>"gastos basicos",
								  "17"=>"remuneracion",
								  "18"=>"imposiciones",
								  "19"=>"pago patentes",
								  "20"=>"intereses",
								  "21"=>"insumos TENS",
								  "22"=>"fondo de cesantia",
								  "23"=>"publicidad",
								  "24"=>"arancel de verificacion institucional");
			}
			
			sort($array_opciones);
			foreach($array_opciones as $n => $valor)
			{
				if($n==$valor_predeterminado)
				{ $select='selected="selected"';}
				else
				{ $select='';}
				$campo.='<option value="'.$valor.'" '.$select.'>'.$valor.'</option>';
			}
			$campo.='</select>';
			break;		
		case"bancos":
				$campo='<select name="'.$nombre_campo.'" id="'.$id_campo.'" '.$funcion.'>';
			
			if($mostrar_opcion_todos)
			{$array_opciones=array("0"=>"Todos",
								  "1"=>"estado",
								  "2"=>"chile",
								  "3"=>"santander",
								  "4"=>"internacional",
								  "5"=>"Scotiabank",
								  "6"=>"Credito e Inversiones",
								  "7"=>"Corpbanca",
								  "8"=>"Bice",
								  "9"=>"HSBC Bank",
								  "10"=>"Itau",
								  "11"=>"Security",
								  "12"=>"Falabella",
								  "13"=>"Ripley",
								  "14"=>"Consorcio",
								  "15"=>"Penta",
								  "16"=>"Paris");
			}
			else
			{
				$array_opciones=array("1"=>"estado",
									  "2"=>"chile",
									  "3"=>"santander",
									  "4"=>"internacional",
									  "5"=>"Scotiabank",
									  "6"=>"Credito e Inversiones",
									  "7"=>"Corpbanca",
									  "8"=>"Bice",
									  "9"=>"HSBC Bank",
									  "10"=>"Itau",
									  "11"=>"Security",
									  "12"=>"Falabella",
									  "13"=>"Ripley",
									  "14"=>"Consorcio",
									  "15"=>"Penta",
									  "16"=>"Paris");
			}
			sort($array_opciones);					  
			foreach($array_opciones as $n => $valor)
			{
				if($n==$valor_predeterminado)
				{ $select='selected="selected"';}
				else
				{ $select='';}
				$campo.='<option value="'.$valor.'" '.$select.'>'.$valor.'</option>';
			}
			$campo.='</select>';
			break;	
		case"situaciones_academicas":
			$campo='<select name="'.$nombre_campo.'" id="'.$id_campo.'" '.$funcion.'>';
			
			if($mostrar_opcion_todos)
			{$array_opciones=array("0"=>"Todos",
								  "V"=>"vigente",
								  "R"=>"retirado",
								  "P"=>"postergado",
								  "E"=>"eliminado",
								  "EG"=>"Egresado",
								  "T"=>"Titulado");
			}
			else
			{
				$array_opciones=array( "V"=>"vigente",
									  "R"=>"retirado",
									  "P"=>"postergado",
									  "E"=>"eliminado",
									  "EG"=>"Egresado",
									  "T"=>"Titulado");
			}
								  
			foreach($array_opciones as $n => $valor)
			{
				if($n==$valor_predeterminado)
				{ $select='selected="selected"';}
				else
				{ $select='';}
				$campo.='<option value="'.$n.'" '.$select.'>'.$valor.'</option>';
			}
			$campo.='</select>';
			break;	
		case"paises":
			$campo='<select name="'.$nombre_campo.'" id="'.$id_campo.'" '.$funcion.'>';
			
			if($mostrar_opcion_todos)
			{$array_paises=array("todos","Afganistan", "Akrotiri", "Albania", "Alemania", "Andorra", "Angola", "Anguila", "Antartida", "Antigua y Barbuda", "Antillas Neerlandesas", "Arabia Saudi", "Arctic Ocean", "Argelia", "Argentina", "Armenia", "Aruba", "Ashmore andCartier Islands", "Atlantic Ocean", "Australia", "Austria", "Azerbaiyan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belgica", "Belice", "Benin", "Bermudas", "Bielorrusia", "Birmania Myanmar", "Bolivia", "Bosnia y Hercegovina", "Botsuana", "Brasil", "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Butan", "Cabo Verde", "Camboya", "Camerun", "Canada", "Chad", "Chile", "China", "Chipre", "Clipperton Island", "Colombia", "Comoras", "Congo", "Coral Sea Islands", "Corea del Norte", "Corea del Sur", "Costa de Marfil", "Costa Rica", "Croacia", "Cuba", "Dhekelia", "Dinamarca", "Dominica", "Ecuador", "Egipto", "El Salvador", "El Vaticano", "Emiratos Arabes Unidos", "Eritrea", "Eslovaquia", "Eslovenia", "Espa�a", "Estados Unidos", "Estonia", "Etiopia", "Filipinas", "Finlandia", "Fiyi", "Francia", "Gabon", "Gambia", "Gaza Strip", "Georgia", "Ghana", "Gibraltar", "Granada", "Grecia", "Groenlandia", "Guam", "Guatemala", "Guernsey", "Guinea", "Guinea Ecuatorial", "Guinea-Bissau", "Guyana", "Haiti", "Honduras", "Hong Kong", "Hungria", "India", "Indian Ocean", "Indonesia", "Iran", "Iraq", "Irlanda", "Isla Bouvet", "Isla Christmas", "Isla Norfolk", "Islandia", "Islas Caiman", "Islas Cocos", "Islas Cook", "Islas Feroe", "Islas Georgia del Sur y Sandwich del Sur", "Islas Heard y McDonald", "Islas Malvinas", "Islas Marianas del Norte", "Islas Marshall", "Islas Pitcairn", "Islas Salomon", "Islas Turcas y Caicos", "Islas Virgenes Americanas", "Islas Virgenes Britanicas", "Israel", "Italia", "Jamaica", "Jan Mayen", "Japon", "Jersey", "Jordania", "Kazajist�n", "Kenia", "Kirguizist�n", "Kiribati", "Kuwait", "Laos", "Lesoto", "Letonia", "Libano", "Liberia", "Libia", "Liechtenstein", "Lituania", "Luxemburgo", "Macao", "Macedonia", "Madagascar", "Malasia", "Malaui", "Maldivas", "Mali", "Malta", "Man, Isle of", "Marruecos", "Mauricio", "Mauritania", "Mayotte", "Mexico", "Micronesia", "Moldavia", "Monaco", "Mongolia", "Montserrat", "Mozambique", "Namibia", "Nauru", "Navassa Island", "Nepal", "Nicaragua", "Niger", "Nigeria", "Niue", "Noruega", "Nueva Caledonia", "Nueva Zelanda", "Oman", "Pacific Ocean", "Paises Bajos", "Pakist�n", "Palaos", "Panama", "Papua-Nueva Guinea", "Paracel Islands", "Paraguay", "Peru", "Polinesia Francesa", "Polonia", "Portugal", "Puerto Rico", "Qatar", "Reino Unido", "Republica Centroafricana", "Republica Checa", "Republica Democratica del Congo", "Republica Dominicana", "Ruanda", "Rumania", "Rusia", "Sahara Occidental", "Samoa", "Samoa Americana", "San Cristobal y Nieves", "San Marino", "San Pedro y Miquelon", "San Vicente y las Granadinas", "Santa Helena", "Santa Lucia", "Santo Tome y Pr�ncipe", "Senegal", "Seychelles", "Sierra Leona", "Singapur", "Siria", "Somalia", "Southern Ocean", "Spratly Islands", "Sri Lanka", "Suazilandia", "Sudafrica", "Sud�n", "Suecia", "Suiza", "Surinam", "Svalbard y Jan Mayen", "Tailandia", "Taiwan", "Tanzania", "Tayikistan", "Territorio Britanico del Oceano Indico", "Territorios Australes Franceses", "Timor Oriental", "Togo", "Tokelau", "Tonga", "Trinidad y Tobago", "Tunez", "Turkmenist�n", "Turquia", "Tuvalu", "Ucrania", "Uganda", "Union Europea", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Wake Island", "Wallis y Futuna", "West Bank", "World", "Yemen", "Yibuti", "Zambia", "Zimbabue");}
			else{$array_paises=array("Afganistan", "Akrotiri", "Albania", "Alemania", "Andorra", "Angola", "Anguila", "Antartida", "Antigua y Barbuda", "Antillas Neerlandesas", "Arabia Saudi", "Arctic Ocean", "Argelia", "Argentina", "Armenia", "Aruba", "Ashmore andCartier Islands", "Atlantic Ocean", "Australia", "Austria", "Azerbaiyan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belgica", "Belice", "Benin", "Bermudas", "Bielorrusia", "Birmania Myanmar", "Bolivia", "Bosnia y Hercegovina", "Botsuana", "Brasil", "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Butan", "Cabo Verde", "Camboya", "Camerun", "Canada", "Chad", "Chile", "China", "Chipre", "Clipperton Island", "Colombia", "Comoras", "Congo", "Coral Sea Islands", "Corea del Norte", "Corea del Sur", "Costa de Marfil", "Costa Rica", "Croacia", "Cuba", "Dhekelia", "Dinamarca", "Dominica", "Ecuador", "Egipto", "El Salvador", "El Vaticano", "Emiratos Arabes Unidos", "Eritrea", "Eslovaquia", "Eslovenia", "Espa�a", "Estados Unidos", "Estonia", "Etiopia", "Filipinas", "Finlandia", "Fiyi", "Francia", "Gabon", "Gambia", "Gaza Strip", "Georgia", "Ghana", "Gibraltar", "Granada", "Grecia", "Groenlandia", "Guam", "Guatemala", "Guernsey", "Guinea", "Guinea Ecuatorial", "Guinea-Bissau", "Guyana", "Haiti", "Honduras", "Hong Kong", "Hungria", "India", "Indian Ocean", "Indonesia", "Iran", "Iraq", "Irlanda", "Isla Bouvet", "Isla Christmas", "Isla Norfolk", "Islandia", "Islas Caiman", "Islas Cocos", "Islas Cook", "Islas Feroe", "Islas Georgia del Sur y Sandwich del Sur", "Islas Heard y McDonald", "Islas Malvinas", "Islas Marianas del Norte", "Islas Marshall", "Islas Pitcairn", "Islas Salomon", "Islas Turcas y Caicos", "Islas Virgenes Americanas", "Islas Virgenes Britanicas", "Israel", "Italia", "Jamaica", "Jan Mayen", "Japon", "Jersey", "Jordania", "Kazajist�n", "Kenia", "Kirguizist�n", "Kiribati", "Kuwait", "Laos", "Lesoto", "Letonia", "Libano", "Liberia", "Libia", "Liechtenstein", "Lituania", "Luxemburgo", "Macao", "Macedonia", "Madagascar", "Malasia", "Malaui", "Maldivas", "Mali", "Malta", "Man, Isle of", "Marruecos", "Mauricio", "Mauritania", "Mayotte", "Mexico", "Micronesia", "Moldavia", "Monaco", "Mongolia", "Montserrat", "Mozambique", "Namibia", "Nauru", "Navassa Island", "Nepal", "Nicaragua", "Niger", "Nigeria", "Niue", "Noruega", "Nueva Caledonia", "Nueva Zelanda", "Oman", "Pacific Ocean", "Paises Bajos", "Pakist�n", "Palaos", "Panama", "Papua-Nueva Guinea", "Paracel Islands", "Paraguay", "Peru", "Polinesia Francesa", "Polonia", "Portugal", "Puerto Rico", "Qatar", "Reino Unido", "Republica Centroafricana", "Republica Checa", "Republica Democratica del Congo", "Republica Dominicana", "Ruanda", "Rumania", "Rusia", "Sahara Occidental", "Samoa", "Samoa Americana", "San Cristobal y Nieves", "San Marino", "San Pedro y Miquelon", "San Vicente y las Granadinas", "Santa Helena", "Santa Lucia", "Santo Tome y Pr�ncipe", "Senegal", "Seychelles", "Sierra Leona", "Singapur", "Siria", "Somalia", "Southern Ocean", "Spratly Islands", "Sri Lanka", "Suazilandia", "Sudafrica", "Sud�n", "Suecia", "Suiza", "Surinam", "Svalbard y Jan Mayen", "Tailandia", "Taiwan", "Tanzania", "Tayikistan", "Territorio Britanico del Oceano Indico", "Territorios Australes Franceses", "Timor Oriental", "Togo", "Tokelau", "Tonga", "Trinidad y Tobago", "Tunez", "Turkmenist�n", "Turquia", "Tuvalu", "Ucrania", "Uganda", "Union Europea", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Wake Island", "Wallis y Futuna", "West Bank", "World", "Yemen", "Yibuti", "Zambia", "Zimbabue");}
			
			foreach($array_paises as $n => $valor)
			{
				if($valor==$valor_predeterminado)
				{ $select='selected="selected"';}
				else
				{ $select='';}
				$campo.='<option value="'.$valor.'" '.$select.'>'.$valor.'</option>';
			}
			$campo.='</select>';
			break;
		default:	
			$campo="campo no disponible [$tipo]";
	}
	
	return($campo);
}
//------------------------------------------------------------//
//notas parciales V3
//devuelve array con las notas parciales v3 de un alumno y periodo determinado
//ademas del promedio de la asignatura
function NOTAS_PARCIALES_V3($id_alumno, $id_carrera, $cod_asignatura, $jornada, $semestre, $year)
{
	if(DEBUG){ echo"INICIO FUNCION NOTAS_PARCIALES_V3<br>";}
	require("conexion_v2.php");
	
	
	//datos del alumno
	$cons_A="SELECT grupo, sede FROM alumno WHERE id='$id_alumno' LIMIT 1";
	$sqli_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error());
		$A=$sqli_A->fetch_assoc();
		$A_sede=$A["sede"];
		$A_grupo=$A["grupo"];
	$sqli_A->free();
		
	//busco evaluaciones para asignatura y periodo
	$cons_1="SELECT * FROM notas_parciales_evaluaciones WHERE id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND grupo='$A_grupo' AND sede='$A_sede' AND semestre='$semestre' AND year='$year' ORDER by id";
	
	$sql_1=$conexion_mysqli->query($cons_1)or die("Evaluaciones".$conexion_mysqli->error);
	$num_evaluaciones=$sql_1->num_rows;
	if(DEBUG){ echo"$cons_1<br>Num Evaluaciones: $num_evaluaciones<br> \n";}
	
	$ARRAY_NOTAS=array();
	$PROMEDIO_NOTA=0;
	
	if($num_evaluaciones>0)
	{
		$cuenta_notas_puestas=0;
		$cuenta_evaluaciones=0;
		$cuenta_notas=0;
		$hay_nota_repeticion=false;
		while($EV=$sql_1->fetch_assoc())
		{
			//caracteristicas de evaluacion
			$cuenta_evaluaciones++;
			$E_id=$EV["id"];
			$E_metodo_evaluacion=$EV["metodo_evaluacion"];
			$E_porcentaje=$EV["porcentaje"];
			$E_tipo_evaluacion=$EV["tipo_evaluacion"];
			if(DEBUG){ echo"----->[$E_id] -- $E_metodo_evaluacion  [tipo->$E_tipo_evaluacion] -- $E_porcentaje %<br>";}
			//busco notas de evaluacion
			$cons_3="SELECT * FROM notas_parciales_registros WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND id_evaluacion='$E_id' ORDER by id ASC LIMIT 1";
			$sql_3=$conexion_mysqli->query($cons_3)or die("Notas ".$conexion_mysqli->error);
			
			$aux_id_nota="";
			$aux_nota="";
			if($sql_3->num_rows>0){
				$DN=$sql_3->fetch_assoc();
					$aux_id_nota=$DN["id"];
					$aux_nota=$DN["nota"];
					if($aux_nota>0)
					{
						$ARRAY_NOTAS[$cuenta_notas]=$aux_nota;
						$cuenta_notas++;
					}
					if(DEBUG){ echo"|---> $aux_nota<br> \n";}
			}
				
			$sql_3->free();
			
			switch($E_metodo_evaluacion)
			{
				case"ponderado":
					if($aux_nota>0)
					{
						$PROMEDIO_NOTA+=(($aux_nota*$E_porcentaje)/100); 
						$cuenta_notas_puestas++; 
					}
					break;
				default:
					switch($E_tipo_evaluacion)
						{
							case"parcial":
								if($aux_nota>0){$PROMEDIO_NOTA+=$aux_nota; $cuenta_notas_puestas++;}
								break;
							case"global":
								if($aux_nota>0)
								{
									$PROMEDIO_NOTA+=($aux_nota*2);
									 $cuenta_notas_puestas+=2;
								}
								break;
							case"repeticion":
								if($aux_nota>0)
								{
									$hay_nota_repeticion=true;
									$aux_nota_repeticion=$aux_nota;
								}
								break;
						}
			}

		}//fin while
		switch($E_metodo_evaluacion)
		{
			case"ponderado":
				break;
			default:
				if(DEBUG){ echo "Notas Puestas $cuenta_notas_puestas SUMATORIA: $PROMEDIO_NOTA<br> \n";}
				if($cuenta_notas_puestas>0)
				{ 
					$PROMEDIO_NOTA=($PROMEDIO_NOTA/$cuenta_notas_puestas);
					if(DEBUG){ echo "Promedio: $PROMEDIO_NOTA<br> \n";}
					if($hay_nota_repeticion)
					{
						if($aux_nota_repeticion>$PROMEDIO_NOTA)
						{
							$PROMEDIO_NOTA=$aux_nota_repeticion;
						}
					}
				}
				else{ $PROMEDIO_NOTA=0;}
		}
		
		if(DEBUG){ echo "PROMEDIO NOTAS: $PROMEDIO_NOTA<br> \n";}
	}
	else
	{ if(DEBUG){echo"Sin Evaluaciones en asignatura cod($cod_asignatura)<br>";}}
	//------------------------------------------------------------------------------------------///
	
	$array_respuesta=array($ARRAY_NOTAS, $PROMEDIO_NOTA);
	$conexion_mysqli->close();
	if(DEBUG){ echo"FIN FUNCION NOTAS_PARCIALES_V3<br> \n";}
	return($array_respuesta);
}

//calculo promedio de Notas Final (asignaturas)
function PROMEDIO_FINAL_ASIGNATURAS($id_alumno, $id_carrera, $yearIngresoCarrera){
	require("conexion_v2.php");
	$acumula_promedio=0;
	$cuenta_promedio=0;
	if(DEBUG){ echo"-----------------------INICIO PROMEDIO_FINAL_ASIGNATURAS-----------------------------------<br>";}
	$cons_N="SELECT nivel, AVG(nota) AS PROMEDIO FROM `notas` WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera' AND es_asignatura='1' AND nivel <='4' group by nivel";
   $sqli_N=$conexion_mysqli->query($cons_N)or die($conexion_mysqli->error);
   $num_notas=$sqli_N->num_rows;
   $promedioFinal=0;
   if($num_notas>0)
   {

	   $acumula_promedio=0;
	   $cuenta_promedio=0;
	    while($N=$sqli_N->fetch_assoc()) 
		{
			$auxNivel=$N["nivel"];
			$auxPromedio=$N["PROMEDIO"];
			if(DEBUG){ echo"-->nivel:  $auxNivel promedio: $auxPromedio<br>";}
			$acumula_promedio+=$auxPromedio;
			$cuenta_promedio++;
		}
	}
	 
	 
	$conexion_mysqli->close();
	//Promedio Final
	if($cuenta_promedio>0){$promedioFinal=($acumula_promedio/$cuenta_promedio);}
	else{$promedioFinal=0;}
	if(DEBUG){ echo"Promedio Final Asignaturas: $promedioFinal<br>";}
	if(DEBUG){ echo"-----------------------FIN PROMEDIO_FINAL_ASIGNATURAS-----------------------------------<br>";}
	return($promedioFinal);
}
///------------------------------------------------
function NOTA_FINAL_TITULO($id_alumno, $id_carrera, $yearIngresoCarrera){
	require("conexion_v2.php");
	$notaFinalTitulo=0;
	$notaFinalPractica=0;
	$promedioFinalAsignaturas=0;
	$notaFinalPractica=0;
	$notaInformePractica=0;
	$notaExamenTitulo=0;
	$notaEvaluacionEmpresa=0;
	$notaSupervisionPractica=0;
	
	if(DEBUG){ echo"-----------------------INICIO NOTA FINAL TITULO-----------------------------------<br>";}
	list($es_egresado, $semestre_egreso, $year_egreso)=ES_EGRESADO_V2($id_alumno, $id_carrera, $yearIngresoCarrera);
	if($es_egresado)
	{
		$cons_pp="SELECT * FROM proceso_titulacion WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera'";
		 if(DEBUG){ echo"-> $cons_pp<br>";}
		
		 $sql_pp=$conexion_mysqli->query($cons_pp)or die($conexion_mysqli->error);
		 $num_regpp=$sql_pp->num_rows;
	
		 if($num_regpp>0)
		 {
			$DPP=$sql_pp->fetch_assoc();
				$notaInformePractica=$DPP["notaInformePractica"];
				$notaEvaluacionEmpresa=$DPP["notaEvaluacionEmpresa"];
				$notaSupervisionPractica=$DPP["notaSupervisionPractica"];
				
				$notaExamenTitulo=$DPP["notaExamen"];
		 }
		 $sql_pp->free();
		 
		$notaFinalPractica=$notaInformePractica*0.3+$notaEvaluacionEmpresa*0.4+$notaSupervisionPractica*0.3;
		$promedioFinalAsignaturas=PROMEDIO_FINAL_ASIGNATURAS($id_alumno, $id_carrera, $yearIngresoCarrera);
		$notaFinalTitulo=($promedioFinalAsignaturas*0.3)+($notaFinalPractica *0.35)+($notaExamenTitulo *0.35);
	}
	else{ if(DEBUG){ echo"ALUMNO NO Egresado... NO continuar<br>";}}
	
	
	if(DEBUG){ echo"Nota Practica Final: $notaFinalPractica<br>Nota Examen: $notaExamenTitulo<br>";}
	if(DEBUG){ echo"-----------------------FIN NOTA FINAL TITULO-----------------------------------<br>";}
	$conexion_mysqli->close();
	return($notaFinalTitulo);
}


///------------------------------------------------------//
//devuelve el numero de ramos inscritos como toma de ramos
//ademas de un array con los codigos de las asignatura que toma
function RAMOS_INSCRITOS_TOMA_RAMO($id_alumno, $id_carrera, $yearIngresoCarrera, $year="", $semestre="", $nivel="", $comparador_para_year='=')
{
	if(DEBUG){ echo"___________________________<strong>RAMOS_INSCRITOS_TOMA_RAMO</strong>____________________________<br>";}
	if(DEBUG){ echo"id_alumno:$id_alumno<br>id_carrera: $id_carrera<br>year:$year comparador para year: $comparador_para_year<br> semestre: $semestre<br>nivel:$nivel<br>";}
	$nota_aprobacion=4;
	require("conexion_v2.php");
	
	if((is_numeric($year))and($year>0))
	{$condicion_year="AND toma_ramos.year $comparador_para_year '$year'";}
	else{ $condicion_year="";}
	
	if((is_numeric($semestre))and($semestre>0))
	{ $condicion_semestre="AND toma_ramos.semestre='$semestre'";}
	else{ $condicion_semestre="";}
	
	if((is_numeric($nivel))and($nivel>0))
	{ $condicion_nivel="AND toma_ramos.nivel='$nivel'";}
	else
	{ $condicion_nivel="";}
	
	$cons="SELECT DISTINCT(cod_asignatura) FROM toma_ramos WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera' $condicion_year $condicion_semestre $condicion_nivel ORDER by cod_asignatura";
	$sqli=$conexion_mysqli->query($cons)or die("RAMOS_INSCRITOS_TOMA_RAMO ".$conexion_mysqli->error);
	$num_ramos_inscritos=$sqli->num_rows;
	if(DEBUG){ echo"<strong>---->$cons</strong><br>num ramos inscritos: $num_ramos_inscritos<br>";}
	
	
	//$ARRAY_RAMOS_INSCRITOS=array();
	$contador=0;
	$ramos_aprobados=0;
	
	$ARRAY_RAMOS_INSCRITOS["aprobado"]=array();
	$ARRAY_RAMOS_INSCRITOS["reprobado"]=array();
	if($num_ramos_inscritos>0)
	{
		while($RI=$sqli->fetch_row())
		{
			$aux_cod_asignatura=$RI[0];		
			if(DEBUG){ echo"N:".($contador+1)." COD_ASIGNATURA: $aux_cod_asignatura<br>";}
			//reviso si ramo esta aprobado
			$cons_A="SELECT id, nota FROM notas WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera' AND cod='$aux_cod_asignatura' ORDER by id DESC  LIMIT 1";
			if(DEBUG){ echo"-->$cons_A<br>";}
			$sqli_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
			$DN=$sqli_A->fetch_assoc();
				$aux_nota=$DN["nota"];
				$aux_id_nota=$DN["id"];
				if(DEBUG){ echo"Nota:$aux_nota id:$aux_id_nota";}
			$sqli_A->free();
				
				if($aux_nota>=$nota_aprobacion)
				{ $ramos_aprobados++; if(DEBUG){ echo"  [Ramo APROBADO]<br><br>";} $condicion_actual_ramo="aprobado";}
				else{ if(DEBUG){ echo" [Ramo NO aprobado]<br><br>";}  $condicion_actual_ramo="reprobado";}
				
				$ARRAY_RAMOS_INSCRITOS[$condicion_actual_ramo][$aux_cod_asignatura]=$aux_nota;
				
			$contador++;
		}
	}
	
	$sqli->free();
	$conexion_mysqli->close();
	
	if(DEBUG){ echo"Numero ramos Aprobados: $ramos_aprobados<br>";}
	if(DEBUG){ echo"___________________________<strong>FIN RAMOS_INSCRITOS_TOMA_RAMO</strong>____________________________<br>";}
	return($ARRAY_RAMOS_INSCRITOS);
}
//-----------------------------------------------------------------------------------///
//devuelve true/false si hay o no proceso de retiro creado
//ademas devuelve un array asociativo con datos del proceso de retiro
function HAY_PROCESO_RETIRO($id_alumno, $id_carrera)
{
	if(DEBUG){ echo"___________________________<strong>HAY_PROCESO_RETIRO</strong>____________________________<br>";}
	require("conexion_v2.php");
	
	$cons_PR="SELECT * FROM proceso_retiro WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' LIMIT 1";
	$sqli_PR=$conexion_mysqli->query($cons_PR)or die("ERROR ".$conexion_mysqli->error);
	$num_pr=$sqli_PR->num_rows;
	$hay_proceso_retiro=false;
	$array_datos=array();
	if(DEBUG){ echo"--> $cons_PR<br>Num Registros: $num_pr<br>";}
	if($num_pr>0)
	{
		while($PR=$sqli_PR->fetch_assoc())
		{
			$hay_proceso_retiro=true;
			$array_datos["motivo"]=$PR["motivo"];
		}
	}
	
	$sqli_PR->free();
	
	if(DEBUG)
	{
		echo"Alumno tiene Proceso de Retiro: ";
		if($hay_proceso_retiro){ echo"Si<br>";}
		else{ echo"No<br>";}
	}
	if(DEBUG){ echo"___________________________<strong>FIN HAY_PROCESO_RETIRO</strong>____________________________<br>";}
	
	$array_respuesta=array($hay_proceso_retiro, $array_datos);
	
	return($array_respuesta);
}
//-----------------------------------------------------------------------------------///
//devuelve true/false si hay o no proceso de PENDIENTE creado, ademas del semestre y year del proceso de PENDIENTE
function ES_PENDIENTE($id_alumno, $id_carrera, $yearIngresoCarrera)
{
	if(DEBUG){ echo"___________________________<strong>ES_PENDIENTE</strong>____________________________<br>";}
	require("conexion_v2.php");
	
	$cons_PR="SELECT * FROM proceso_pendiente WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera' ORDER by id_pendiente DESC LIMIT 1";
	$sqli_PR=$conexion_mysqli->query($cons_PR)or die("ERROR ".$conexion_mysqli->error);
	$num_pr=$sqli_PR->num_rows;
	$es_pendiente=false;
	$semestre_pendiente="";
	$year_pendiente="";
	if(DEBUG){ echo"--> $cons_PR<br>Num Registros: $num_pr<br>";}
	if($num_pr>0)
	{
		$es_pendiente=true;
		$PR=$sqli_PR->fetch_assoc();
			$hay_proceso_pendiente=true;
			$motivo=$PR["motivo"];
			$semestre_pendiente=$PR["semestre"];
			$year_pendiente=$PR["year"];
		if(DEBUG){echo"Alumno tiene Proceso de PENDIENTE: Si - PERIODO[$semestre_pendiente -  $year_pendiente]<br>";}	
	}
	else
	{
		if(DEBUG){ echo"Alumno sin proceso de pendiente, no pendiente<br>";}
		$es_pendiente=false;
	}
	$sqli_PR->free();
	
	
	if(DEBUG){ echo"___________________________<strong>FIN ES_PENDIENTE</strong>____________________________<br>";}
	
	$array_respuesta=array($es_pendiente, $semestre_pendiente, $year_pendiente);
	$conexion_mysqli->close();
	return($array_respuesta);
}
//------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------//
//-----------------------------------------------------------------------------------///
//-----------------------------------------------------------------------------------///
//devuelve true/false si hay o no proceso de POSTERGACION creado, ademas del semestre, year y semestres suspencion del proceso de postergacion
function ES_POSTERGADO($id_alumno, $id_carrera)
{
	if(DEBUG){ echo"___________________________<strong>ES_POSTERGADO</strong>____________________________<br>";}
	require("conexion_v2.php");
	
	$cons_PR="SELECT * FROM proceso_postergacion WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' ORDER by id_postergacion DESC LIMIT 1";
	$sqli_PR=$conexion_mysqli->query($cons_PR)or die("ERROR ".$conexion_mysqli->error);
	$num_pr=$sqli_PR->num_rows;
	$es_postergado=false;
	$semestre_postergado="";
	$year_postergado="";
	$semestres_suspencion=0;
	if(DEBUG){ echo"--> $cons_PR<br>Num Registros: $num_pr<br>";}
	if($num_pr>0)
	{
		$es_postergado=true;
		$PR=$sqli_PR->fetch_assoc();
			$hay_proceso_postergado=true;
			$motivo=$PR["motivo"];
			$semestre_postergado=$PR["semestre_postergacion"];
			$year_postergado=$PR["year_postergacion"];
			$semestres_suspencion=$PR["semestres_suspencion"];
		if(DEBUG){echo"Alumno tiene Proceso de Postergacion: Si - PERIODO[$semestre_postergado -  $year_postergado]<br>";}	
	}
	else
	{
		if(DEBUG){ echo"Alumno sin proceso de Postergacion, no postergado<br>";}
		$es_postergado=false;
	}
	$sqli_PR->free();
	
	
	if(DEBUG){ echo"___________________________<strong>FIN ES_POSTERGADO</strong>____________________________<br>";}
	
	$array_respuesta=array($es_postergado, $semestre_postergado, $year_postergado, $semestres_suspencion);
	$conexion_mysqli->close();
	return($array_respuesta);
}
//------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------//
//-----------------------------------------------------------------------------------///
//devuelve true/false si hay o no proceso de retiro creado, ademas del semestre y year del proceso de retiro
function ES_RETIRADO($id_alumno, $id_carrera, $yearIngresoCarrera)
{
	if(DEBUG){ echo"___________________________<strong>ES_RETIRADO</strong>____________________________<br>";}
	require("conexion_v2.php");
	
	$cons_PR="SELECT * FROM proceso_retiro WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera' ORDER by id_retiro DESC LIMIT 1";
	$sqli_PR=$conexion_mysqli->query($cons_PR)or die("ERROR ".$conexion_mysqli->error);
	$num_pr=$sqli_PR->num_rows;
	$es_retirado=false;
	$semestre_retiro="";
	$year_retiro="";
	if(DEBUG){ echo"--> $cons_PR<br>Num Registros: $num_pr<br>";}
	if($num_pr>0)
	{
		$es_retirado=true;
		$PR=$sqli_PR->fetch_assoc();
			$hay_proceso_retiro=true;
			$motivo=$PR["motivo"];
			$semestre_retiro=$PR["semestre_retiro"];
			$year_retiro=$PR["year_retiro"];
		if(DEBUG){echo"Alumno tiene Proceso de Retiro: Si - Year retiro [$year_retiro]<br>";}	
	}
	else
	{
		if(DEBUG){ echo"Alumno sin proceso de Retiro, no Retirado<br>";}
		$es_retirado=false;
	}
	$sqli_PR->free();
	
	
	if(DEBUG){ echo"___________________________<strong>FIN ES_RETIRADO</strong>____________________________<br>";}
	
	$array_respuesta=array($es_retirado, $semestre_retiro, $year_retiro);
	$conexion_mysqli->close();
	return($array_respuesta);
}
//------------------------------------------------------------------------------------------//
//funcion revisa egresado segun nota
function ES_EGRESADO($id_alumno, $id_carrera, $yearIngresoCarrera)
{
	if(DEBUG){ echo"___________________________<strong>FUNCION: ES_EGRESADO</strong>____________________________<br>\n";}
	require("conexion_v2.php");
	
	//---------------------------------------------//
	$alumno_es_egresado=false;
	$nota_minima_aprobacion=4;
	$num_ramos_aprobados=0;
	$num_ramos_reprobados=0;
	$year_egreso=0;
	$semestre_egreso=0;
	//---------------------------------------------///
	//compruebo que tenga registro academico
	$cons="SELECT COUNT(id) FROM notas WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND ramo<>'' AND yearIngresoCarrera='$yearIngresoCarrera' AND es_asignatura='1'";
	$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$R=$sqli->fetch_row();
	$num_registros_notas=$R[0];
	if(DEBUG){ echo"->$cons<br>num registros del alumno: $num_registros_notas<br>";}
	if(empty($num_registros_notas)){ $num_registros_notas=0;}
	$sqli->free();
	//---------------------------------------------//
	
	if($num_registros_notas>0){ $hay_registro_academico=true; if(DEBUG){ echo"Alumno, tiene registro academico Creado<br>\n";}}
	else{ $hay_registro_academico=false; if(DEBUG){ echo"Alumno, NO tiene registro academico Creado<br>\n";}}
	
	if($hay_registro_academico)
	{
		$cons_M="SELECT * FROM mallas WHERE id_carrera='$id_carrera' AND ramo<>'' AND nivel<'5' AND es_asignatura='1' ORDER by num_posicion, id";
		$sqli_M=$conexion_mysqli->query($cons_M)or die($conexion_mysqli->error);
		$num_registros_en_malla=$sqli_M->num_rows;
		if(DEBUG){ echo"-->$cons_M<br>\n Num registros en malla: $num_registros_en_malla<br>\n";}
		if($num_registros_en_malla>0)
		{
			while($M=$sqli_M->fetch_assoc())
			{
				$M_ramo=$M["ramo"];
				$M_cod=$M["cod"];
				
				if(DEBUG){ echo"--->COD: $M_cod RAMO: $M_ramo<br>";}
				$cons_NA="SELECT nota, semestre, ano FROM notas WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera' AND cod='$M_cod' LIMIT 1";
				$sqli_NA=$conexion_mysqli->query($cons_NA)or die($conexion_mysqli->error);
				$NA=$sqli_NA->fetch_assoc();
				$NA_nota=$NA["nota"];
				$NA_semestre=$NA["semestre"];
				$NA_ano=$NA["ano"];
				
				if($NA_ano>$year_egreso){ $year_egreso=$NA_ano; $semestre_egreso=$NA_semestre;}
				elseif(($NA_ano==$year_egreso)and($NA_semestre>$semestre_egreso)){ $semestre_egreso=$NA_semestre;}
				
				if(empty($NA_nota)){$NA_nota=0;}
				if(DEBUG){ echo"---->$cons_NA<br>\n nota alumno en ramo: $NA_nota [$NA_semestre - $NA_ano]<br>\n";}
			
				if($NA_nota>=$nota_minima_aprobacion){$ramo_aprobado=true; if(DEBUG){ echo"Ramo aprobado...<br>\n";} $num_ramos_aprobados++;}
				else{ $ramo_aprobado=false; if(DEBUG){ echo"Ramo NO aprobado...<br>\n";} $num_ramos_reprobados=false;}
				
				$sqli_NA->free();
			}
			
			if(($num_ramos_aprobados==$num_registros_en_malla)and($num_ramos_reprobados==0))
			{ $alumno_es_egresado=true; if(DEBUG){ echo"<br>\n<strong>Alumno es EGRESADO, todos los ramos Aprobados... periodo egreso[$semestre_egreso - $year_egreso]</strong><br>\n";}}
			else{ if(DEBUG){ echo"<br>\n<strong>Alumno NO Egresado</strong><br>\n";}}

		}
		else
		{
			if(DEBUG){ echo"-->Sin Registros en Malla<br>\n";}
		}
	}
	
	$conexion_mysqli->close();
	if(DEBUG){ echo"___________________________<strong>Fin Funcion: ES_EGRESADO</strong>____________________________<br>\n";}
	
	$array_respuesta=array($alumno_es_egresado, $semestre_egreso, $year_egreso);
	return($array_respuesta);
}
//---------------------------------------------------------------------//
//verifica si es egresado segun registro de proceso de egreso, devolviendo si es o no egresado, semestr egreso y year egreso
//26/05/2016
function ES_EGRESADO_V2($id_alumno, $id_carrera, $yearIngresoCarrera)
{
	$semestre_egreso="";
	$year_egreso="";
	$alumno_es_egresado=false;
	
	if(DEBUG){ echo"___________________________<strong>INICIO FUNCION: ES_EGRESADO_V2</strong>____________________________<br>";}
	require("conexion_v2.php");
	if(DEBUG){ echo"datos entrada--> id_alumno: $id_alumno id_carrera: $id_carrera yearIngresoCarrera: $yearIngresoCarrera<br>";}
	$cons_S="SELECT semestre_egreso, year_egreso FROM proceso_egreso WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera' ORDER by id_proceso_egreso";
	$sqli_S=$conexion_mysqli->query($cons_S)or die($conexion_mysqli->error);
	$num_registros=$sqli_S->num_rows;
	if(DEBUG){ echo"--->$cons_S<br>num registros egreso: $num_registros<br>";}
	if($num_registros>0)
	{
		$alumno_es_egresado=true;
		if(DEBUG){ echo"El alumno es egresado<br>";}
		while($EG=$sqli_S->fetch_assoc())
		{
			$semestre_egreso=$EG["semestre_egreso"];
			$year_egreso=$EG["year_egreso"];
		}
		
		if(DEBUG){ echo"Periodo egreso [$semestre_egreso - $year_egreso]<br>";}
	}
	else
	{if(DEBUG){ echo"El alumno NO es egresado<br>";}}
	
	$sqli_S->free();
	$conexion_mysqli->close();
	
	$array_respuesta=array($alumno_es_egresado, $semestre_egreso, $year_egreso);
	if(DEBUG){ echo"___________________________<strong>FIN FUNCION: ES_EGRESADO_V2</strong>____________________________<br>";}
	return($array_respuesta);
}
//--------------------------------------------------------------------//
function ES_TITULADO($id_alumno, $id_carrera, $yearIngresoCarrera)
{
	if(DEBUG){ echo"___________________________<strong>INICIO Funcion: ES_TITULADO</strong>____________________________<br>";}
	require("conexion_v2.php");
	
	$PT_semeste="";
	$PT_year="";
	$es_titulado=false;
	
	$cons="SELECT * FROM proceso_titulacion WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera' LIMIT 1";
	$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$num_reg=$sqli->num_rows;
	$es_titulado=false;
	$semestre_titulo="";
	$year_titulo="";
	if(DEBUG){ echo"--->$cons<br>NUM REGISTROS:$num_reg<br>";}
	if($num_reg>0)
	{
		$es_titulado=true;
		$PT=$sqli->fetch_assoc();
		$PT_titulo_fecha_emision=$PT["titulo_fecha_emision"];
		$PT_fecha_generacion=$PT["fecha_generacion"];
		$PT_semeste=$PT["semestre_titulo"];
		$PT_year=$PT["year_titulo"];
		
		$semestre_titulo=$PT_semeste;
		$year_titulo=$PT_year;

	}
	else
	{
		$es_titulado=false;
		if(DEBUG){ echo"No hay Proceso de Titulacion, No es titulado<br>";}
		
	}
	
	if(DEBUG)
	{
		if($es_titulado){ echo"ALUMNO es TITULADO perido [$PT_semeste - $PT_year]<br>";}
		else{ echo"Alumno NO es titulado<br>";}
	}
	$sqli->free();
	$conexion_mysqli->close();
	if(DEBUG){ echo"___________________________<strong>Fin Funcion: ES_TITULADO</strong>____________________________<br>";}
	
	$array_respuesta=array($es_titulado, $semestre_titulo, $year_titulo);
	return($array_respuesta);
	
}
//
function COLOR_CARRERA($id_carrera)
{
	if(DEBUG){ echo"______________________________FUNCION COLOR_CARRERA______________________________<br>";}
	if(DEBUG){ echo"id_carrera: $id_carrera<br>";}
	if(is_numeric($id_carrera)){ $continuar=true;}
	else{ $continuar=false;}
	$color_final="";
	
	$ARRAY_COLORES=array(1=>"#81BEF7",
						 2=>"#819FF7",
						 3=>"#E6E6E6",
						 4=>"#58D3F7",
						 5=>"#04B45F",
						 6=>"#F5A9F2",
						 7=>"#DA81F5",
						 8=>"#2EFE9A",
						 9=>"#F5A9A9",
						 11=>"#F3FF81",
						 12=>"#D0F5A9",
						 13=>"#F5DA81",
						 14=>"#81F781",
						 18=>"#F8ECE0",
						 19=>"#D0F5AA",
						 20=>"#D7DF01",
						 21=>"#F5A9BC",
						 24=>"#FA8258");
		if(isset($ARRAY_COLORES[$id_carrera])){ $color_final=$ARRAY_COLORES[$id_carrera];}	
		
		if(DEBUG){ echo'Color Generado: <font color="'.$color_final.'">'.$color_final.'</font><br>';}
		if(DEBUG){ echo"______________________________FIN COLOR_CARRERA______________________________<br>";}			 
	return($color_final);	
}
function TIENE_ASIGNACIONES($id_funcionario, $sede="", $semestre="", $year="" )
{
	if(DEBUG){ echo"<br><strong>_____________________________INICIO TIENE_ASIGNACIONES_____________________________</strong><br>";}
	require("conexion_v2.php");
	$year_actual=date("Y");
	$mes_actual=date("m");
	
	if($mes_actual>7){ $semestre_actual=2;}
	else{ $semestre_actual=1;}
	
	
	if(empty($sede)){ $condicion_sede="";}
	else{ $condicion_sede="AND sede='$sede'";}
	
	if(empty($semestre)){ $semestre_consulta=$semestre_actual;}
	else{ $semestre_consulta=$semestre;}
	
	if(empty($year)){ $year_consulta=$year_actual;}
	else{ $year_consulta=$year;}
	
	if(DEBUG){ echo"id_funcionario: $id_funcionario<br> semestre: $semestre_consulta <br>year: $year_consulta<br>sede: $sede";}
	
	$cons_a="SELECT COUNT(id) FROM toma_ramo_docente WHERE id_funcionario='$id_funcionario' AND semestre='$semestre_consulta' AND year='$year_consulta' $condicion_sede";
	$sqli_A=$conexion_mysqli->query($cons_a)or die("Error: ".$conexion_mysqli->error);
	$A=$sqli_A->fetch_row();
		$num_asignaciones_docente=$A[0];
		if(empty($num_asignaciones_docente)){$num_asignaciones_docente=0;}
	$sqli_A->free();	
	if(DEBUG){ echo"---> $cons_a<br>Num asignaciones:$num_asignaciones_docente<br>";}
	
	if($num_asignaciones_docente>0){ $tiene_asignaciones=true; if(DEBUG){ echo"<strong>Docente tiene Asignaciones en este periodo</strong><br>";}}
	else{ $tiene_asignaciones=false; if(DEBUG){ echo"<strong>Docente no tiene Asignaciones en este periodo</strong><br>";}}
	

	$conexion_mysqli->close();
	if(DEBUG){ echo"<br><strong>_____________________________FIN TIENE_ASIGNACIONES_____________________________</strong><br>";}
	return($tiene_asignaciones);
}
function PERIODO_TOMA_RAMO($id_alumno, $id_carrera, $yearIngresoCarrera, $tipo="max")
{
	if(DEBUG){ echo"<br><strong>_____________________________INICIO PERIODO_TOMA_RAMO_____________________________</strong><br>";}
		require("conexion_v2.php");
		$TM_YEAR=0;
		$TM_semestre=0;
			
		switch($tipo)
		{
			case"max":
				$operador="MAX";
				$continuar=true;
				break;
			case"min":
				$operador="MIN";
				$continuar=true;
				break;
			default:	
				$continuar=false;	
		}
		
		if($continuar)
		{
			$cons="SELECT $operador(year) FROM toma_ramos WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera'";
			if(DEBUG){ echo"-->$cons<br>";}
			$sqli=$conexion_mysqli->query($cons)or die($cnexion_mysqli->error);
				$Y=$sqli->fetch_row();
				$TM_YEAR=$Y[0];
				if(empty($TM_YEAR)){ $TM_YEAR=0;}
				if(DEBUG){ echo"$operador A�O en TOMA de RAMOS: $TM_YEAR<br>";}
			$sqli->free();
			
			if($TM_YEAR>0)
			{
				$cons_2="SELECT $operador(semestre) FROM toma_ramos WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera' AND year='$TM_YEAR'";
				if(DEBUG){ echo"-->$cons_2<br>";}
				$sqli_2=$conexion_mysqli->query($cons_2) or die($conexion_mysqli->error);
					$S=$sqli_2->fetch_row();
					$TM_semestre=$S[0];
					if(empty($TM_semestre)){ $TM_semestre=0;}
					if(DEBUG){ echo"$operador SEMESTRE en TOMAS de Ramos: $TM_semestre<br>";}
				$sqli_2->free();	
			}
		}
		else
		{
			if(DEBUG){ echo"No continuar, operador incorrecto<br>";}
		}
		
		$array_respuesta=array($TM_semestre, $TM_YEAR);
			
	if(DEBUG){ echo"<br><strong>_____________________________FIN PERIODO_TOMA_RAMO_____________________________</strong><br>";}
	$conexion_mysqli->close();
	return($array_respuesta);
}
////------------------------------------------------//
function GRABA_COMPROBANTE_EGRESO($fecha, $sede, $numero_comprobante, $valor_comprobante, $glosa_comprobante, $id_proveedor, $tipo_proveedor="proveedor", $formapago="efectivo")
{
	require("conexion_v2.php");
	if(empty($tipo_proveedor)){$tipo_proveedor='proveedor';}
	if(DEBUG){ echo"<br><strong>-------------------------------------INICIO FUNCION GRABA_COMPROBANTE_EGRESO------------------------------------------</strong><br>";}
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	$fecha_hora_actual=date("Y-m-d H:i:s");
	
	if($numero_comprobante>0){
		$cons_CE="INSERT INTO comprobante_egreso (fecha, tipo_proveedor, id_proveedor, sede, numero, valor, formaPago, glosa, cod_user, fecha_generacion) VALUES ('$fecha', '$tipo_proveedor', '$id_proveedor', '$sede', '$numero_comprobante', '$valor_comprobante', '$formapago', '$glosa_comprobante', '$id_usuario_actual', '$fecha_hora_actual')";
	}
	else{
		$cons_CE="INSERT INTO comprobante_egreso (fecha, tipo_proveedor, id_proveedor, sede, valor, formaPago, glosa, cod_user, fecha_generacion) VALUES ('$fecha', '$tipo_proveedor', '$id_proveedor', '$sede', '$valor_comprobante', '$formapago', '$glosa_comprobante', '$id_usuario_actual', '$fecha_hora_actual')";
		}
	
	if(DEBUG){ echo"--->$cons_CE<br>"; $id_comprobante_egreso_new="CE";}
	else{ $conexion_mysqli->query($cons_CE)or die("ERROR GRABA_COMPROBANTE_EGRESO".$conexion_mysqli->error); $id_comprobante_egreso_new=$conexion_mysqli->insert_id;}
	
	if(DEBUG){ echo"id_comprobante_egreso: $id_comprobante_egreso_new<br>";}
	
	
	if(DEBUG){ echo"<br><strong>-------------------------------------FIN FUNCION GRABA_COMPROBANTE_EGRESO------------------------------------------</strong><br>";}
	$conexion_mysqli->close();
	return($id_comprobante_egreso_new);
}
//---------------------------------------------------------------//
function GRABA_BOLETA_RECIBIDA($sede, $tipo, $numero_boleta, $valor_boleta, $glosa_boleta, $id_proveedor)
{
	require("conexion_v2.php");
	if(DEBUG){ echo"<br><strong>-------------------------------------INICIO FUNCION GRABA_BOLETA_RECIBIDA------------------------------------------</strong><br>";}
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	$fecha_hora_actual=date("Y-m-d H:i:s");
	
	$cons_BR="INSERT INTO boletas_recibidas (id_proveedor, tipo, sede,  numero, valor, glosa, cod_user, fecha_generacion) VALUES ('$id_proveedor', '$tipo', '$sede', '$numero_boleta', '$valor_boleta', '$glosa_boleta', '$id_usuario_actual', '$fecha_hora_actual')";
	
	if(DEBUG){ echo"--->$cons_BR<br>"; $id_boleta_recibida_new="BR";}
	else{ $conexion_mysqli->query($cons_BR)or die("ERROR GRABA_BOLETA_RECIBIDA ".$conexion_mysqli->error); $id_boleta_recibida_new=$conexion_mysqli->insert_id;}
	
	if(DEBUG){ echo"id_boleta_recibida: $id_boleta_recibida_new<br>";}
	
	
	if(DEBUG){ echo"<br><strong>-------------------------------------FIN FUNCION GRABA_BOLETA_RECIBIDA------------------------------------------</strong><br>";}
	$conexion_mysqli->close();
	return($id_boleta_recibida_new);
}
//------------------------------------------------------------------------//
function INFO_COMPROBANTE_FINANCIERO($id_pago)
{
	
	$tipo_documento="";
	$numero_documento="";
	if(DEBUG){ echo"<br><strong>-------------------------------------INICIO FUNCION INFO_COMPROBANTE_FINANCIERO------------------------------------------</strong><br>";}
	require("conexion_v2.php");
	
	$cons="SELECT tipodoc, id_comprobante_egreso, id_boleta_R, id_boleta, tipo_receptor, movimiento, id_factura, aux_num_documento FROM pagos WHERE idpago='$id_pago' LIMIT 1";
	
	if(DEBUG){ echo"--->$cons<br>";}
	
	$sqli=$conexion_mysqli->query($cons)or die("FUNCION INFO_COMPROBANTE_FINANCIERO ".$conexion_mysqli->error);
	$P=$sqli->fetch_assoc();
		$P_id_comprobante_egreso=$P["id_comprobante_egreso"];
		$P_id_boleta_R=$P["id_boleta_R"];
		$P_id_boleta=$P["id_boleta"];
		$P_tipo_receptor=$P["tipo_receptor"];
		$P_id_factura=$P["id_factura"];
		$P_aux_num_documento=$P["aux_num_documento"];
		$P_movimiento=$P["movimiento"];
		$P_tipodoc=$P["tipodoc"];
	$sqli->free();
	
	if((is_numeric($P_id_boleta))and($P_id_boleta>0))
	{
		$tipo_documento="boleta";
		if(DEBUG){ echo"Buscando datos de Boleta emitida<br>";}
		$cons_2="SELECT folio FROM boleta WHERE id='$P_id_boleta' LIMIT 1";
		if(DEBUG){ echo"---->$cons_2<br>";}
		$sqli_2=$conexion_mysqli->query($cons_2)or die("Datos de Boleta Emitida ".$conexion_mysqli->error);
		$B=$sqli_2->fetch_assoc();
			$B_folio=$B["folio"];
		$sqli_2->free();
		$numero_documento=$B_folio;	
	}
	elseif((is_numeric($P_id_factura))and($P_id_factura>0))
	{
		$tipo_documento="factura";
		if(DEBUG){ echo"Buscando datos de factura<br>";}
		$cons_2="SELECT cod_factura FROM facturas WHERE id='$P_id_factura' LIMIT 1";
		if(DEBUG){ echo"---->$cons_2<br>";}
		$sqli_2=$conexion_mysqli->query($cons_2)or die("Datos de Factura ".$conexion_mysqli->error);
		$F=$sqli_2->fetch_assoc();
			$F_cod_factura=$F["cod_factura"];
		$sqli_2->free();
		$numero_documento=$F_cod_factura;	
	}
	elseif((is_numeric($P_id_boleta_R))and($P_id_boleta_R>0))
	{
		$tipo_documento="boleta";
		if(DEBUG){ echo"Buscando datos de boleta recibida<br>";}
		$cons_2="SELECT numero FROM boletas_recibidas WHERE id_boleta_R='$P_id_boleta_R' LIMIT 1";
		if(DEBUG){ echo"---->$cons_2<br>";}
		$sqli_2=$conexion_mysqli->query($cons_2)or die("Datos de Boleta recibida ".$conexion_mysqli->error);
		$BR=$sqli_2->fetch_assoc();
			$BR_numero=$BR["numero"];
		$sqli_2->free();
		$numero_documento=$BR_numero;	
	}
	elseif((is_numeric($P_id_comprobante_egreso))and($P_id_comprobante_egreso>0))
	{
		$tipo_documento="comprobante_egreso";
		if(DEBUG){ echo"Buscando datos de comprobante_egreso<br>";}
		$cons_2="SELECT numero FROM comprobante_egreso WHERE id_comprobante='$P_id_comprobante_egreso' LIMIT 1";
		if(DEBUG){ echo"---->$cons_2<br>";}
		$sqli_2=$conexion_mysqli->query($cons_2)or die("Datos de Comprobante Egreso ".$conexion_mysqli->error);
		$CE=$sqli_2->fetch_assoc();
			$CE_numero=$P_id_comprobante_egreso." ".$CE["numero"];
		$sqli_2->free();
		$numero_documento=$CE_numero;	
	}
	else
	{ 
		if(DEBUG){ echo"No hay id del documento de respaldo, utilizar generico<br>";}
		$tipo_documento=$P_tipodoc;
		$numero_documento=$P_aux_num_documento;
	}
	
	if(DEBUG){ echo"id_pago:$id_pago movimiento:$P_movimiento<br>id_comprobante_egreso: $P_id_comprobante_egreso<br> id_boleta_R: $P_id_boleta_R<br>id_boleta:$P_id_boleta<br>id_factura:$P_id_factura<br> aux_num_documento: $P_aux_num_documento<br>";}
	
	$conexion_mysqli->close();
	if(DEBUG){ echo"<br>Tipo Documento: $tipo_documento<br>Numero Documento: $numero_documento<br>";}
	if(DEBUG){ echo"<br><strong>-------------------------------------FIN FUNCION INFO_COMPROBANTE_FINANCIERO------------------------------------------</strong><br>";}
	
	$array_respuesta=array($tipo_documento, $numero_documento);
	return($array_respuesta);
}
//-----------------------------------------------------------//
function ES_MATRICULA_NUEVA($id_alumno, $id_contrato)
{
	if(DEBUG){ echo"<br><strong>----------------------------------INICIO FUNCION ES_MATRICULA_NUEVA---------------------------------------</strong><br>";}
		require("conexion_v2.php");
		//busco nivel de alumno en contrato desde el cual se realizara el pago
		$cons="SELECT nivel_alumno FROM contratos2 WHERE id_alumno='$id_alumno' AND id='$id_contrato' LIMIT 1";
		if(DEBUG){ echo"--->$cons<br>";}
		$sqli_C=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		$DCA=$sqli_C->fetch_assoc();
			$C_nivel_alumno=$DCA["nivel_alumno"];
			if(empty($C_nivel_alumno)){ $C_nivel_alumno=0;}
		$sqli_C->free();	
		if(DEBUG){ echo"Nivel de Alumno en Contrato: $C_nivel_alumno<br>";}
		///--------------------------------------------------//
		
		
		if($C_nivel_alumno>1){$matricula_es_nueva=false; if(DEBUG){ echo"Matricula No es Nueva<br>";}}
		else{ $matricula_es_nueva=true; if(DEBUG){ echo"Matricula ES Nueva<br>";}}
		
		$conexion_mysqli->close();
	
	if(DEBUG){ echo"<br><strong>----------------------------------FIN FUNCION ES_MATRICULA_NUEVA---------------------------------------</strong><br>";}
	return($matricula_es_nueva);
}
//----------------------------------------------------------------------//
function TIPO_FUNCIONARIO_X_NIVEL($nivel_privilegio)
{
	switch($nivel_privilegio)
	{
		case"1":
			$tipo_funcionario="docente";
			break;
		case"2":
			$tipo_funcionario="admin";
			break;
		case"3":
			$tipo_funcionario="finanzas";
			break;
		case"4":
			$tipo_funcionario="Admi total";
			break;
		case"5":
			$tipo_funcionario="matricula";
			break;
		case"6":
			$tipo_funcionario="inspeccion";
			break;
		case"7":
			$tipo_funcionario="externo";
			break;
		case"8":
			$tipo_funcionario="jefe de carrera";
			break;							
		default:
			$tipo_funcionario="no definido";	
			break;
	}
	
	return($tipo_funcionario);
}
///devuelve el codigo de carrera sies para una carrera del cft
//-----------------------------------------------------------------------//
function CODIGO_CARRERA_SIES($sede, $jornada, $carrera, $id_carrera, $tipoRespuesta="completo")
{
	if(DEBUG){ echo"<br><strong>-------------------------------------INICIO FUNCION CODIGO_CARRERA_SIES-----------------------------------------</strong></br>";}
	$codigo_intitucion="I273";
	$version="V1";
	switch($sede)
	{
		case"Talca":
			$codigo_sede="S1";
			break;
		case"Linares":	
			$codigo_sede="S3";
			break;
	}
	$codigo_jornada="ERROR";
	switch($jornada)
	{
		case"D":
			$codigo_jornada="J1";
			break;
		case"V":
			$codigo_jornada="J2";
			break;	
	}
	switch($id_carrera)
	{
		case 1://juridico
			$codigo_carrera="C12";
			break;
		case 2://construccion
			$codigo_carrera="C5";
			break;	
		case 3://bancario
			$codigo_carrera="C13";
			break;	
		case 4://tens
			$codigo_carrera="C16";
			break;
		case 5://programacion
			$codigo_carrera="C2";
			break;	
		case 6://secretariado sin mencion inicial
			$codigo_carrera="C3";
			break;
		case 8://secretariado RRPP o juridica
			$codigo_carrera="C3";
			break;		
		case 11://construccion.(con punto)
			$codigo_carrera="C5";
			break;	
		case 14://construccion.(con punto)
			$codigo_carrera="C5";
			break;	
		case 18://prevencion de riesgos y medioambiente
			$codigo_carrera="C19";
			break;	
		case 19://parvulo
			$codigo_carrera="C22";
			break;		
		case 20://secretariado. (con punto)
			$codigo_carrera="C20";
			break;	
		case 21://administracion empresas. (con punto)
			$codigo_carrera="C33";
			break;										
		default:
			$codigo_carrera="sin_carrera";
			
	}
	switch($tipoRespuesta){
		case"completo":
			$codigo_carrera_final=$codigo_intitucion."".$codigo_sede."".$codigo_carrera."".$codigo_jornada."".$version;
			break;
		case"carrera":
			$codigo_carrera_final=$codigo_carrera;
			break;	
	}
	if(DEBUG){ echo"<strong>$id_carrera ( $carrera ) =|$codigo_carrera_final|</strong><br>";}
	if(DEBUG){ echo"<br><strong>-------------------------------------FIN FUNCION CODIGO_CARRERA_SIES-----------------------------------------</strong></br>";}
	return($codigo_carrera_final);
}
//-----------------------------------//
//devuelve situacion (V,EG,T,R,NN, PENDIENTE, POSTERGADO) de los alumnos en una periodo[semestre - year] determinado
function ESTADO_ALUMNO_PERIODO($id_alumno, $id_carrera, $yearIngresoCarrera, $semestre, $year)
{
	require("conexion_v2.php");
	if(DEBUG){ echo"<br><strong>----------------------------INICIO FUNCION ESTADO_ALUMNO_PERIODO-------------------------------------</strong></br><tt>";}
	$condicion_alumno_este_year="";
	$es_titulado=false;
	$es_egresado=false;
	$es_matriculado=false;
	$es_retirado=false;
	$es_pendiente=false;
	$es_postergado=false;
	
	$ultimo_year_informacion=0;
	$ultimo_semestre_informacion=0;
	
	
	//titulado
	list($es_titulado, $semestre_titulo, $year_titulo)=ES_TITULADO($id_alumno, $id_carrera, $yearIngresoCarrera);
	
	if((($es_titulado)and($year_titulo<$year))or(($es_titulado)and($year_titulo==$year)and($semestre_titulo<=$semestre)))
	{ $es_titulado=true; $condicion_alumno_este_year="T"; $ultimo_year_informacion=$year_titulo; $ultimo_semestre_informacion=$semestre_titulo;}
	else{ $es_titulado=false;}
	
	if(!$es_titulado)
	{
		//egresado
		list($es_egresado, $semestre_egreso, $year_egreso)=ES_EGRESADO_V2($id_alumno, $id_carrera, $yearIngresoCarrera);
		if((($es_egresado)and($year_egreso<$year))or(($es_egresado)and($year_egreso==$year)and($semestre_egreso<=$semestre)))
		{ $es_egresado=true; $condicion_alumno_este_year="EG"; $ultimo_year_informacion=$year_egreso; $ultimo_semestre_informacion=$semestre_egreso;}
		else{ $es_egresado=false; }
	}
	
	if(!$es_titulado)
	{
		//retirado
		list($es_retirado, $semestre_retiro,$year_retiro)=ES_RETIRADO($id_alumno, $id_carrera, $yearIngresoCarrera);
		if((($es_retirado)and($ultimo_year_informacion<$year_retiro)and($ultimo_semestre_informacion<$semestre_retiro)and($year_retiro<$year))
		or(($es_retirado)and($ultimo_year_informacion<$year_retiro)and($ultimo_semestre_informacion<$semestre_retiro)and($year_retiro==$year))and($semestre_retiro<=$semestre))
		{$es_retirado=true; $condicion_alumno_este_year="R"; $ultimo_year_informacion=$year_retiro; $ultimo_semestre_informacion=$year_retiro;}
		else{ $es_retirado=false;}
	}
	
	if((!$es_retirado)and(!$es_egresado)and(!$es_titulado))
	{
		if(DEBUG){ echo"-----------------INICIO Revision de POSTERGADO---------------------<br>";}
		//POSTERGADO //revisar, pueden haber multiples procesos de postergacion
		$consP="SELECT semestre_postergacion, year_postergacion, semestres_suspencion FROM proceso_postergacion WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera'";
		
		$sqliP=$conexion_mysqli->query($consP);
		$num_postergaciones=$sqliP->num_rows;
		if(DEBUG){ echo"--->$consP<br>Num postergaciones encontradas: $num_postergaciones<br>";}
		if($num_postergaciones>0)
		{
			while($P=$sqliP->fetch_assoc())
			{
				$P_semestre=$P["semestre_postergacion"];
				$P_year=$P["year_postergacion"];
				$P_semestres_suspencion=$P["semestres_suspencion"];
				list($P_semestre_F, $P_year_F, $en_rango)=CALCULA_PERIODO($P_semestre, $P_year, $P_semestres_suspencion,"+",$semestre, $year);
				
				if($en_rango){ $es_postergado=true; $year_postergado=$year; $semestre_postergado=$semestre;}
				
				if(($es_postergado)and($ultimo_year_informacion<$year_postergado)and($ultimo_semestre_informacion<$semestre_postergado))
				{ $condicion_alumno_este_year="POSTERGADO"; $ultimo_year_informacion=$year_postergado; $ultimo_semestre_informacion=$semestre_postergado;}

			}
		}
		$sqliP->free();
		if($es_postergado){ if(DEBUG){ echo" Alumno postergado en periodo[$semestre - $year]<br>";}}
		else{if(DEBUG){ echo" Alumno NO postergado<br>";}}
		if(DEBUG){ echo"-----------------FIN Revision de POSTERGADO---------------------<br>";}
	}
	if((!$es_retirado)and(!$es_egresado)and(!$es_titulado)and(!$es_postergado))
	{
		//PENDIENTE
		list($es_pendiente, $semestre_pendiente, $year_pendiente)=ES_PENDIENTE($id_alumno, $id_carrera, $yearIngresoCarrera);
		if((($es_pendiente)and($ultimo_year_informacion<$year_pendiente)and($ultimo_semestre_informacion<$semestre_pendiente)and($year_pendiente<$year))
		or(($es_pendiente)and($ultimo_year_informacion<$year_pendiente)and($ultimo_semestre_informacion<$semestre_pendiente)and($year_pendiente==$year))and($semestre_pendiente<=$semestre))
		{$es_pendiente=true; $condicion_alumno_este_year="PENDIENTE"; $ultimo_year_informacion=$year_pendiente; $ultimo_semestre_informacion=$semestre_pendiente;}
		else{ $es_pendiente=false;}
	}
	
	if((!$es_egresado)and(!$es_titulado))
	{
		//vigente
		$es_matriculado=VERIFICAR_MATRICULA($id_alumno, $id_carrera, $yearIngresoCarrera, false, false,$semestre, false,$year, true);
		if($es_matriculado)
		{
			$condicion_alumno_este_year="V";
			$ultimo_year_informacion=$year;
		   $ultimo_semestre_informacion=$semestre;
			//en caso de egresado que se matricularon para titularse
		}
	}
	
	//no es nada NN
	if((!$es_matriculado)and(!$es_egresado)and(!$es_titulado)and(!$es_retirado)and(!$es_pendiente)and(!$es_postergado))
	{ $condicion_alumno_este_year="NN";}
	
	if(DEBUG){echo"Condicion de alumno este year: $condicion_alumno_este_year<br>";}
	if(DEBUG){ echo"</tt><br><strong>----------------------------FIN FUNCION ESTADO_ALUMNO_PERIODO-------------------------------------</strong></br>";}
	$conexion_mysqli->close();
	return($condicion_alumno_este_year);
}
//------------------------------------------------//
function NIVEL_ACADEMICO_ALUMNO($id_alumno, $id_carrera, $semestre, $year)
{
	$nivel_academico_alumno="";
	if(DEBUG){ echo"<br><strong>----------------------------INICIO FUNCION NIVEL_ACADEMICO_ALUMNO-------------------------------------</strong></br>";}
	require("conexion_v2.php");
		$cons_TR="SELECT MIN(nivel) FROM toma_ramos WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND semestre='$semestre' AND year='$year'";	
		$sqli_TR=$conexion_mysqli->query($cons_TR)or die($conexion_mysqli->error);
		$TR=$sqli_TR->fetch_row();
		$nivel_minino_TR=$TR[0];
		if(DEBUG){ echo"->$cons_TR<br>nivel minimo: $nivel_minino_TR<br>";}
		if(DEBUG){if(empty($nivel_minino_TR)){ echo"Imposible determinar nivel academico sin tomas de ramos en el periodo [$semestre - $year]<br>";}}
		
		$nivel_academico_alumno=$nivel_minino_TR;
		$conexion_mysqli->close();
	if(DEBUG){ echo"<br><strong>----------------------------FIN FUNCION NIVEL_ACADEMICO_ALUMNO-------------------------------------</strong></br>";}
	
	return($nivel_academico_alumno);
}
//nivel academico actual
function NIVEL_ACADEMICO_ALUMNO_ACTUAL($id_alumno, $id_carrera)
{
	$nivel_academico_alumno="";
	if(DEBUG){ echo"<br><strong>----------------------------INICIO FUNCION NIVEL_ACADEMICO_ALUMNO_ACTUAL-------------------------------------</strong></br>";}
	require("conexion_v2.php");
		$cons_N="SELECT MIN(nivel) FROM notas WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND ramo<>'' AND NOT nota > '4' ";	
		$sqli_N=$conexion_mysqli->query($cons_N)or die($conexion_mysqli->error);
		$N=$sqli_N->fetch_row();
		$nivel_minino_N=$N[0];
		if(DEBUG){ echo"->$cons_N<br>nivel minimo: $nivel_minino_N<br>";}
		if(DEBUG){if(empty($nivel_minino_N)){ echo"Imposible determinar nivel academico sin tomas de ramos en el periodo <br>";}}
		
		$nivel_academico_alumno=$nivel_minino_N;
		$conexion_mysqli->close();
	if(DEBUG){ echo"<br><strong>----------------------------FIN FUNCION NIVEL_ACADEMICO_ALUMNO_ACTUAL-------------------------------------</strong></br>";}
	
	return($nivel_academico_alumno);
}
//---------------------------------------------------------//
function ES_REPROBADO($id_alumno, $id_carrera, $year, $semestre=0)
{
	if(DEBUG){ echo"<br><strong>----------------------------INICIO FUNCION ES_REPROBADO-------------------------------------</strong></br>";}
	require("conexion_v2.php");
	$nota_minima_para_aprobar=4;
	
	if($semestre>0){ $condicion_semestre="AND semestre='$semestre'";}
	else{ $condicion_semestre='';}
		
	$cons_RE="SELECT * FROM notas_hija WHERE year='$year' $condicion_semestre AND id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND nota<'$nota_minima_para_aprobar' AND nota<>'' ORDER By id";
	$sqli_RE=$conexion_mysqli->query($cons_RE)or die($conexion_mysqli->error);
	$num_notas=$sqli_RE->num_rows;
	if(DEBUG){ echo"-->$cons_RE<br>Numero notas encontradas: $num_notas<br>";}
	$aux=0;
	if($num_notas>0)
	{
		while($NH=$sqli_RE->fetch_assoc())
		{
			$aux++;
			$NH_id=$NH["id"];
			$NH_nota=$NH["nota"];
			
			if(DEBUG){ echo"-[$aux] id_nota_hija:$NH_id NH_nota: $NH_nota<br>";}
		}
	}
	else
	{if(DEBUG){ echo"Sin Notas Reprobadas encontradas en este periodo<br>";}}
	
	if($aux>0){ $es_reprobado=true; if(DEBUG){ echo"Alumno -> Reprobado<br>";}}
	else{ $es_reprobado=false; if(DEBUG){ echo"Alumno -> NO tiene ramos Reprobados<br>";}}
	$sqli_RE->free();
	$conexion_mysqli->close();
	if(DEBUG){ echo"<br><strong>----------------------------FIN FUNCION ES_REPROBADO-------------------------------------</strong></br>";}
	return($es_reprobado);
}
function CARGO_FUNCIONARIO($id_funcionario)
{
	if(DEBUG){ echo"<br><strong>----------------------------INICIO FUNCION CARGO_FUNCIONARIO-------------------------------------</strong></br>";}
	if(DEBUG){ echo"id_funcionario a consultar: $id_funcionario<br>";}
	$cargo="";
	$array_cargos=array("417"=>"Rector", "411"=>"Director Academico");	
	if(isset($array_cargos[$id_funcionario]))
	{
		if(DEBUG){ echo"Cargo definido<br>";}
		$cargo=$array_cargos[$id_funcionario];
		
	}
	else
	{if(DEBUG){ echo"Cargo no definido<br>";}}
	if(DEBUG){ echo"Cargo Funcionario: $cargo<br>";}
	if(DEBUG){ echo"<br><strong>----------------------------FIN FUNCION CARGO_FUNCIONARIO-------------------------------------</strong></br>";}
	return($cargo);	
}
//regresa hora de inicio y fin de un periodo y bloque dado
function HORARIO_iniFin_BLOQUE($periodo, $bloque)
{
	$periodo=strtoupper($periodo);
	
	 $array_bloque["M"][1]["inicio"]="08:30";
	 $array_bloque["M"][1]["fin"]="09:15";
	 $array_bloque["M"][2]["inicio"]="09:15";
	 $array_bloque["M"][2]["fin"]="10:00";  
	 $array_bloque["M"][3]["inicio"]="10:15";
	 $array_bloque["M"][3]["fin"]="11:00";  
	 $array_bloque["M"][4]["inicio"]="11:00";
	 $array_bloque["M"][4]["fin"]="11:45";  
	 $array_bloque["M"][5]["inicio"]="12:00";
	 $array_bloque["M"][5]["fin"]="12:45"; 
	 $array_bloque["M"][6]["inicio"]="12:45";
	 $array_bloque["M"][6]["fin"]="13:00";
	 $array_bloque["T"][1]["inicio"]="15:00";
     $array_bloque["T"][1]["fin"]="15:45";
     $array_bloque["T"][2]["inicio"]="15:45";
     $array_bloque["T"][2]["fin"]="16:30";
     $array_bloque["T"][3]["inicio"]="16:45";
     $array_bloque["T"][3]["fin"]="17:30";
     $array_bloque["T"][4]["inicio"]="17:30";
     $array_bloque["T"][4]["fin"]="18:15";
     $array_bloque["T"][5]["inicio"]="18:15";
     $array_bloque["T"][5]["fin"]="19:00";
     $array_bloque["T"][6]["inicio"]="19:00";
     $array_bloque["T"][6]["fin"]="19:45";
     $array_bloque["N"][1]["inicio"]="19:45";
     $array_bloque["N"][1]["fin"]="20:25";
     $array_bloque["N"][2]["inicio"]="20:25";
     $array_bloque["N"][2]["fin"]="21:05";
     $array_bloque["N"][3]["inicio"]="21:15";
     $array_bloque["N"][3]["fin"]="21:55";
     $array_bloque["N"][4]["inicio"]="22:05";
     $array_bloque["N"][4]["fin"]="22:40";
     $array_bloque["N"][5]["inicio"]="22:45";
     $array_bloque["N"][5]["fin"]="23:20";
     $array_bloque["N"][6]["inicio"]="23:20";
     $array_bloque["N"][6]["fin"]="24:00";
	 
	 if(isset($array_bloque[$periodo][$bloque]["inicio"]))
	 {$inicio=$array_bloque[$periodo][$bloque]["inicio"];}
	 else{ $inicio="";}
	 
	 if(isset($array_bloque[$periodo][$bloque]["fin"]))
	 {$fin=$array_bloque[$periodo][$bloque]["fin"];}
	 else
	 { $fin="";}
	 
	 $array_respuesta=array($inicio, $fin);
	 return($array_respuesta);
}

function BENEFICIO_ESTUDIANTIL_NOMBRE($id_beneficio){
	$BE_nombre="Sin Nombre";
	require("conexion_v2.php");
	if($id_beneficio>0){
		$cons_BE="SELECT beca_nombre FROM beneficiosEstudiantiles WHERE id='$id_beneficio' LIMIT 1";
		$sqli_BE=$conexion_mysqli->query($cons_BE);
		$DBE=$sqli_BE->fetch_assoc();
		$BE_nombre=$DBE["beca_nombre"];
		$sqli_BE->free();
	}
	else{$BE_nombre="Sin Beneficio";}
	$conexion_mysqli->close();	
	return($BE_nombre);
}

function BENEFICIOS_ESTUDIANTILES_ASIGNADOS($id_contrato){
	require("conexion_v2.php");
	
	//lleno array con beneficios actuales
	$ARRAY_BENEFICIOS_ESTUDIANTILES=array();
	$cons_BE="SELECT * FROM beneficiosEstudiantiles ORDER by id";
		$sqli_BE=$conexion_mysqli->query($cons_BE);
		$numBeneficios=$sqli_BE->num_rows;
		if($numBeneficios>0){
			while($DBE=$sqli_BE->fetch_assoc()){
				$BE_id=$DBE["id"];
				$BE_nombre=$DBE["beca_nombre"];
				$BE_tipoAporte=$DBE["beca_tipo_aporte"];
				$BE_aporteValor=$DBE["beca_aporte_valor"];
				$BE_aportePorcentaje=$DBE["beca_aporte_porcentaje"];
				
				$ARRAY_BENEFICIOS_ESTUDIANTILES[$BE_id]["nombre"]=$BE_nombre;
				$ARRAY_BENEFICIOS_ESTUDIANTILES[$BE_id]["valorAsignado"]=0;
			}
		}
		$sqli_BE->free();
	//busco los beneficios del contrato
		$cons_BEA="SELECT * FROM beneficiosEstudiantiles_asignaciones WHERE id_contrato='$id_contrato'";
		$sqli_BEA=$conexion_mysqli->query($cons_BEA);
		$numBeneficiosA=$sqli_BEA->num_rows;
		if($numBeneficiosA>0){
			while($DBEA=$sqli_BEA->fetch_assoc()){
				$BEA_id=$DBEA["id_beneficio"];
				$BEA_valor=$DBEA["valor"];	
				
				$ARRAY_BENEFICIOS_ESTUDIANTILES[$BEA_id]["valorAsignado"]=$BEA_valor;	
			}
		}
	
	$conexion_mysqli->close();	
	return($ARRAY_BENEFICIOS_ESTUDIANTILES);
}

//-------------------------------------------------------//
function DATOS_CONTRATO_ESPECIFICO($id_contrato){
	require("conexion_v2.php");
	if(DEBUG){ echo"---------------------INICIO FUNCION DATOS_CONTRATO_ESPECIFICO--------------------------------<br>";}
	$cons="SELECT * FROM contratos2 WHERE id='$id_contrato' LIMIT 1";
	if(DEBUG){ echo"----->$cons<br>";}
	$sql=$conexion_mysqli->query($cons)or die("DATOS_CONTRATO".$conexion_mysqli->error);
	$num_reg=$sql->num_rows;
	if($num_reg>0)
	{
		$DC=$sql->fetch_assoc();
			$C_id=$DC["id"];
			$C_nivel_alumno=$DC["nivel_alumno"];
			$C_vigencia=$DC["vigencia"];
			$C_ano=$DC["ano"];
			$C_semestre=$DC["semestre"];
			$C_condicion=$DC["condicion"];
			$C_matricula_a_pagar=$DC["matricula_a_pagar"];
			$C_matricula_valor=$DC["matricula_valor"];
			$C_matricula_forma_pago=$DC["opcion_pag_matricula"];
			$C_BNM=$DC["beca_nuevo_milenio"];
			$C_aporte_BNM=$DC["aporte_beca_nuevo_milenio"];
			$C_excedentes=$DC["excedente"];
			$C_aporte_BET=$DC["aporte_beca_excelencia"];
			$C_BET=$DC["beca_excelencia"];
			$C_L_credito=$DC["linea_credito_paga"];
			$C_porcentaje_desc=$DC["porcentaje_beca"];
			$C_cantidad_desc=$DC["cantidad_beca"];
			$C_arancel=$DC["arancel"];
			$C_saldo_a_favor=$DC["saldo_a_favor"];
			$C_fechaGeneracion=$DC["fecha_generacion"];
			$C_totalBeneficiosEstudiantiles=$DC["totalBeneficiosEstudiantiles"];
	}
	else
	{
		$C_totalBeneficiosEstudiantiles=0;
		$C_saldo_a_favor=0;
		$C_matricula_a_pagar=0;
		$C_matricula_forma_pago="---";
		$C_matricula_valor=0;
		$C_id="---";
		$C_nivel_alumno="---";
		$C_vigencia="---";
		$C_ano="---";
		$C_semestre="---";
		$C_condicion="---";	
		$C_BNM="---";
		$C_aporte_BNM=0;
		$C_excedentes=0;
		$C_aporte_BET=0;
		$C_BET="---";
		$C_L_credito=0;
		$C_porcentaje_desc=0;
		$C_cantidad_desc=0;
		$C_arancel=0;
		$C_fechaGeneracion="0000-00-00";
	}
	$sql->free();
	$conexion_mysqli->close();
	$devolver=array("nivel_alumno"=>$C_nivel_alumno,
					"vigencia"=> $C_vigencia,
					"year"=> $C_ano,
					"semestre" => $C_semestre,
					"condicion"=> $C_condicion,
					"matricula_valor"=>$C_matricula_valor,
					"matricula_a_pagar"=> $C_matricula_a_pagar,
					"matricula_forma_pago"=> $C_matricula_forma_pago,
					"BNM"=> $C_BNM,
					"aporte_BNM"=> $C_aporte_BNM,
					"BET"=> $C_BET,
					"aporte_BET"=> $C_aporte_BET,
					"excedentes"=> $C_excedentes,
					"linea_credito"=> $C_L_credito,
					"porcentaje_desc"=> $C_porcentaje_desc,
					"cantidad_desc"=> $C_cantidad_desc,
					"arancel"=> $C_arancel,
					"saldo_a_favor"=>$C_saldo_a_favor,
					"fecha_generacion"=> $C_fechaGeneracion,
					"totalBeneficiosEstudiantiles"=> $C_totalBeneficiosEstudiantiles);
	if(DEBUG){ var_export($devolver); echo"<br><br>";}
	if(DEBUG){ echo"---------------------FIN FUNCION DATOS_CONTRATO_ESPECIFICO--------------------------------<br>";}
	return($devolver);
}

function DATOS_CONTRATO($id_alumno, $id_carrera, $semestre, $year)
{
	if(DEBUG){ echo"<br><strong>----------------------------INICIO FUNCION DATOS_CONTRATO-------------------------------------</strong></br>";}
	require("conexion_v2.php");
	$cons_C="SELECT MAX(id) FROM contratos2 WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND semestre='$semestre' AND ano='$year'";
	if(DEBUG){ echo"--->$cons_C<br>";}
	$sqli_C=$conexion_mysqli->query($cons_C)or die($conexion_mysqli->error);
	$C=$sqli_C->fetch_row();
	$C_id=$C[0];
	if(empty($C_id)){$C_id=0;}
	$sqli_C->free();
	
	if($C_id>0){$hay_contrato=true; if(DEBUG){ echo"Hay contrato<br>";}}
	else{$hay_contrato=false; if(DEBUG){ echo"No se encontro contrato<br>";}}
	
	if((!$hay_contrato)and($semestre==2))
	{
		if(DEBUG){ echo"Buscando en contratos Anuales X 2 semestre<br>";}
		$cons_C2="SELECT MAX(id) FROM contratos2 WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND vigencia='anual' AND ano='$year'";
		if(DEBUG){ echo"--->$cons_C2<br>";}
		$sqli_C2=$conexion_mysqli->query($cons_C2)or die($conexion_mysqli->error);
		$num_contrato_2=$sqli_C2->num_rows;
		if($num_contrato_2>0)
		{
			$C2=$sqli_C2->fetch_row();
			$C_id=$C2[0];
		}
		$sqli_C2->free();
		if(empty($C_id)){$C_id=0;}
	}
	if(DEBUG){echo"id_contrato: $C_id<br>";}
	
	if($C_id>0)
	{
		$cons_CF="SELECT * FROM contratos2 WHERE id='$C_id' LIMIT 1";
		$sqli_CF=$conexion_mysqli->query($cons_CF)or die($conexion_mysqli->error);
		$DCF=$sqli_CF->fetch_assoc();
			$array_datos["matricula_valor"]=$DCF["matricula_valor"];
			$array_datos["matricula_a_pagar"]=$DCF["matricula_a_pagar"];
			$array_datos["aporte_BNM"]=$DCF["aporte_beca_nuevo_milenio"];
			$array_datos["arancel"]=$DCF["arancel"];
			$array_datos["aporte_BET"]=$DCF["aporte_beca_excelencia"];
			$array_datos["cantidad_desc"]=$DCF["cantidad_beca"];
			$array_datos["porcentaje_desc"]=$DCF["porcentaje_beca"];
			$array_datos["linea_credito"]=$DCF["linea_credito_paga"];
			$array_datos["vigencia"]=$DCF["vigencia"];
			$array_datos["excedente"]=$DCF["excedente"];
			$array_datos["saldo_a_favor"]=$DCF["saldo_a_favor"];
		$sqli_CF->free();	
	}
	else
	{ if(DEBUG){ echo"Sin id_contrato encontrato<br>";}}
	
	
	$conexion_mysqli->close();
	if(DEBUG){ echo"<br><strong>----------------------------Fin FUNCION DATOS_CONTRATO-------------------------------------</strong></br>";}
	return($array_datos);
}
//-----------------------------------------------------------/
function SEMESTRE_SUSPENCION($id_alumno, $id_carrera, $yearIngresoCarrera)
{
	require("conexion_v2.php");
	if(DEBUG){echo"<strong>________________________INICIO FUNCION SEMESTRES SUSPENCION__________________________</strong><br>";}
	
	$cons_A="SELECT ingreso, year_egreso, situacion, YEAR(titulo_fecha_emision) AS year_titulo, proceso_titulacion.semestre_titulo FROM alumno LEFT JOIN proceso_titulacion ON alumno.id=proceso_titulacion.id_alumno WHERE proceso_titulacion.id_alumno='$id_alumno' AND proceso_titulacion.id_carrera='$id_carrera' AND proceso_titulacion.yearIngresoCarrera='$yearIngresoCarrera' LIMIT 1";
	
	$sqli_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
	if(DEBUG){ echo"---->$cons_A<br>";}
	$A=$sqli_A->fetch_assoc();
		$A_year_ingreso=$A["ingreso"];
		$A_year_egreso=$A["year_egreso"];
		$A_year_titulo=$A["year_titulo"];
		$A_semestre_titulo=$A["semestre_titulo"];
		$A_situacion=$A["situacion"];
	$sqli_A->free();	
	
	list($es_egresado, $semestre_egreso, $year_egreso)=ES_EGRESADO_V2($id_alumno, $id_carrera, $yearIngresoCarrera);
	if(DEBUG){ echo"Year ingreso Alumno: $A_year_ingreso Year egreso Alumno: $year_egreso semestre egreso Alumno: $semestre_egreso Year titulo Alumno: $A_year_titulo Semestre titulo Alumno: $A_semestre_titulo<br><br>";}
	
	
	if(($A_situacion=="EG")or($A_situacion=="T")){$revisar_1=true;}
	else{$revisar_1=false;}
	
	if($A_situacion=="T"){$revisar_2=true;}
	else{$revisar_2=false;}
	
	$periodos_sin_actividad=0;
	if($revisar_1)
	{
		if(DEBUG){ echo"<strong>Inicio Revision 1(Antes de Egresar)</strong><br>";}
		$semestre_carrera=5;
		$semestre=1;
		$year=$A_year_ingreso;
		$cuenta_semestre_total=0;
		if(DEBUG){ echo"Periodo a Revisar desde [$semestre - $year] Hasta [$semestre_egreso - $year_egreso]<br>";}
		
		if(($semestre_egreso>0)and($year_egreso>0))
		{
		
			while(($year<$year_egreso)or(($year<=$year_egreso)and($semestre<=$semestre_egreso)))
			{
				$cuenta_semestre_total++;
				$cons_N="SELECT COUNT(id) FROM notas WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera' AND semestre='$semestre' AND ano='$year'";
				$sqli_N=$conexion_mysqli->query($cons_N)or die("ERROR: ".$conexion_mysqli->error);
					$DN=$sqli_N->fetch_row();
					$num_notas_en_periodo=$DN[0];
					if(empty($num_notas_en_periodo)){$num_notas_en_periodo=0;}
					if($num_notas_en_periodo>0){ $hay_actividad=true;}
					else{$hay_actividad=false;}
				$sqli_N->free();
				if(!$hay_actividad){$periodos_sin_actividad++;}
				
				if(DEBUG){ echo"--->Semestre consulta: $semestre year_consulta: $year TOTAL semestre: $cuenta_semestre_total N. notas en periodo($num_notas_en_periodo)<br>";}
				$semestre++;
				if($semestre>2){ $semestre=1; $year++;}
			}
		}
		else{ if(DEBUG){echo"No se puede revisar 1 sin semestre y a�o de egreso<br>";} $periodos_sin_actividad=-100;}
		if(DEBUG){echo"Semestres sin actividad Antes de egresar: $periodos_sin_actividad<br>";}
	}
	//------------------------------------------------------**
	$periodos_sin_actividad_2=0;
	if($revisar_2)
	{
		$numero_semestre_restados=4;//1 para descontar el semestre de egreso que no se cuenta, 1 por la practica, otro por el examen y uno mas por tardanza titulo
		if(DEBUG){ echo"<br><strong>Inicio Revision 2 (Despues de egresar) </strong><br>";}
		
		$semestre=$semestre_egreso;
		$year=$year_egreso;
		
		$cuenta_semestre_total_2=0;
		if(DEBUG){ echo"Periodo a Revisar desde [$semestre - $year] Hasta [$A_semestre_titulo - $A_year_titulo]<br>";}
		if(($semestre_egreso>0)and($year_egreso>0))
		{
			while(($year<$A_year_titulo)or(($year<=$A_year_titulo)and($semestre<=$A_semestre_titulo)))
			{
				$cuenta_semestre_total_2++;
				
				if(DEBUG){ echo"--->Semestre consulta: $semestre year_consulta: $year TOTAL semestre: $cuenta_semestre_total_2<br>";}
				$semestre++;
				if($semestre>2){ $semestre=1; $year++;}
			}
			$periodos_sin_actividad_2=$cuenta_semestre_total_2;
			$periodos_sin_actividad_2=($periodos_sin_actividad_2-$numero_semestre_restados);
			if($periodos_sin_actividad_2<0){$periodos_sin_actividad_2=0;}
		}
		else{ if(DEBUG){echo"No se puede revisar 2 sin a�o y semestre de egreso<br>";}$periodos_sin_actividad_2=-200;}
		
		if(DEBUG){echo"Semestres sin actividad despues  de egresar: $periodos_sin_actividad_2<br>";}
	}
	
	$periodos_sin_actividad_final=($periodos_sin_actividad+$periodos_sin_actividad_2);
	
	if(DEBUG){ echo"Periodo sin Actividad final: $periodos_sin_actividad_final<br>";}
	if(DEBUG){echo"<strong>________________________FIN FUNCION SEMESTRES SUSPENCION__________________________</strong><br>";}
	return($periodos_sin_actividad_final);
}
//devuelve el numero de semestre con contrato que tiene beca BNM o BET asignada
function SEMESTRES_CON_BECA($id_alumno, $id_carrera="")
{
	if(DEBUG){echo"<strong>________________________FUNCION SEMESTRES CON BECA__________________________</strong><br>";}
	require("conexion_v2.php");
	if($id_carrera>0)
	{ $condicion_carrera=" AND id_carrera='$id_carrera'";}
	else
	{ $condicion_carrera="";}
	
	$semestres_con_beca=0;
	$cons1="SELECT * FROM contratos2 WHERE id_alumno='$id_alumno' $condicion_carrera AND NOT condicion IN('inactivo', 'RETIRO')  ORDER by id";
	 if(DEBUG){echo"--->$cons1<br>";}
	$sql1=$conexion_mysqli->query($cons1);
	$num_contratos=$sql1->num_rows;
	if($num_contratos>0)
	{
		$contador=0;
		
		while($C=$sql1->fetch_assoc())
		{
			
			$considerar_contrato=false;
			$contador++;
			
			$C_id=$C["id"];
			$C_beca_nuevo_milenio=$C["beca_nuevo_milenio"];
			$C_beca_excelencia=$C["beca_excelencia"];
			$C_aporte_BNM=$C["aporte_beca_nuevo_milenio"];
			$C_aporte_BET=$C["aporte_beca_excelencia"];
			
			if(($C_aporte_BET >0)or($C_aporte_BNM>0)){
				$considerar_contrato=true;
			}
			
			$C_semestre=$C["semestre"];
			$C_ano=$C["ano"];
			$C_vigencia=$C["vigencia"];
			$C_condicion=$C["condicion"];
			
			if(DEBUG){ echo"$contador -$C_id [$C_beca_nuevo_milenio] $C_vigencia $C_condicion |$C_semestre - $C_ano|<br>";}
			
			if($considerar_contrato)
			{
				if($C_vigencia=="anual"){ $semestres_con_beca+=2;}
				if($C_vigencia=="semestral"){ $semestres_con_beca+=1;}
			}
		}
	}
	else
	{
		if(DEBUG){ echo"No se encontraron contratos...<br>";}
		$semestres_con_beca=0;
	}
	$sql1->free();
	$conexion_mysqli->close();
	
	if(DEBUG){ echo"SEMESTRES CON BECA NUEVO MILENIO: $semestres_con_beca<br>";}
	if(DEBUG){echo"<strong>________________________FIN FUNCION SEMESTRES CON BECA__________________________</strong><br>";}
	return($semestres_con_beca);
}
//devuelve el numero de semestre en que se asigna un beneficio
function SEMESTRES_CON_BECA_V2($id_alumno, $id_beneficio, $id_carrera="", $yearIngresoCarrera=""){
	if(DEBUG){ echo"<br><strong>----------------------------------INICIO FUNCION SEMESTRES_CON_BECA_V2--------------------------------</strong><br>";}
	if(DEBUG){ echo"DATOS DE ENTRADA<br>id_alumno: $id_alumno<br>";}
	require("conexion_v2.php");
	$cons_SB="SELECT contratos2.vigencia, beneficiosEstudiantiles_asignaciones.* FROM contratos2 INNER JOIN beneficiosEstudiantiles_asignaciones ON contratos2.id = beneficiosEstudiantiles_asignaciones.id_contrato WHERE beneficiosEstudiantiles_asignaciones.id_alumno='$id_alumno' AND beneficiosEstudiantiles_asignaciones.id_beneficio='$id_beneficio' AND contratos2.yearIngresoCarrera='$yearIngresoCarrera' AND contratos2.id_carrera='$id_carrera'";
	$sqli_SB=$conexion_mysqli->query($cons_SB)or die($conexion_mysqli->error);
	$num_beneficios=$sqli_SB->num_rows;
	$semestresConBeca=0;
	if($num_beneficios>0){
		while($BE=$sqli_SB->fetch_assoc()){
			$auxIdbeneficio=$BE["id_beneficio"];
			$auxValor=$BE["valor"];
			$auxVigencia=$BE["vigencia"];
			if(DEBUG){ echo"id_beneficio: $auxIdbeneficio vigencia: $auxVigencia<br>";}
			if($auxVigencia=="semestral"){$semestresConBeca+=1;}
			else{$semestresConBeca+=2;}
		}
	}
	$sqli_SB->free();
	$conexion_mysqli->close();
	if(DEBUG){ echo"Total semestre con beca para este beneficio: $semestresConBeca<br>";}
	if(DEBUG){ echo"<br><strong>----------------------------------FIN FUNCION SEMESTRES_CON_BECA_V2--------------------------------</strong><br>";}
	return($semestresConBeca);
}
function PORCENTAJE_ASISTENCIA($year, $semestre, $id_carrera, $cod_asignatura, $id_alumno)
{
	if(DEBUG){ echo"<br><strong>----------------------------------INICIO FUNCION PORCENTAJE ASISTENCIA--------------------------------</strong><br>";}
	if(DEBUG){ echo"DATOS DE ENTRADA<br> periodod[$semestre - $year]<br>id_carrera: $id_carrera ASignatura: $cod_asignatura id_alumno: $id_alumno<br>";}
	require("conexion_v2.php");
		
	   //-------------------------------------------------------------------------------//
	//busco distintas fechas en las que se paso lista
	$cons_F="SELECT DISTINCT(fecha_clase) FROM asistencia_alumnos WHERE  semestre='$semestre' AND year='$year' AND id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND id_alumno='$id_alumno' ORDER by fecha_clase";
	
	if(DEBUG){ echo"Busco fechas en que se paso asistencia<br>---> $cons_F<br>";}
	
	$sqli_F=$conexion_mysqli->query($cons_F)or die($conexion_mysqli->error);
	$num_fechas=$sqli_F->num_rows;
	$ARRAY_FECHAS=array();
	$aux_array_fecha=array();
	$ARRAY_FECHA_ESTADO=array();
	if(DEBUG){ echo"-->NUM FECHAS: $num_fechas<br>";}
	if($num_fechas)
	{
		while($F=$sqli_F->fetch_row())
		{
			$F_fecha_clase=$F[0];
			if(DEBUG){ echo"---->$F_fecha_clase<br>";}
			$ARRAY_FECHAS[]=$F_fecha_clase;
			list($aux_year, $aux_mes, $aux_dia)=explode("-",$F_fecha_clase);
			$aux_array_fecha[$aux_year][$aux_mes][$aux_dia]=true;
		}
		foreach($aux_array_fecha as $aux_year => $array_1)
		{
			foreach($array_1 as $aux_mes => $array_2)
			{$n_dias_mes=count($array_2);}
		}
		
	}
	else
	{
		//sin fechas
	}
	$sqli_F->free();
	//var_dump($aux_array_fecha);
	//-------------------------------------------------------------------------------//
$TOTAL_HORAS_PROGRAMA=HORAS_PROGRAMA($id_carrera, $cod_asignatura,"semestral","teorico");	
$TOTAL_HORAS_ALUMNO=0;
$hrs_clase_realizada=0;
$aux=0;
if(DEBUG){ echo"Recorro Fechas y reviso estado de asistencia <br>";}
foreach($ARRAY_FECHAS as $i => $aux_fecha_clase)		
{
	if(DEBUG){ echo"<br>Fecha a Consultar: $aux_fecha_clase<br>";}
		$cons_AF="SELECT asistencia,  horas_pedagogicas FROM asistencia_alumnos WHERE semestre='$semestre' AND year='$year' AND id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura'  AND id_alumno='$id_alumno' AND fecha_clase='$aux_fecha_clase' LIMIT 1";
		if(DEBUG){ echo"--------->$cons_AF<br>";}
		$sqli_AF=$conexion_mysqli->query($cons_AF)or die($conexion_mysqli->error);
		$DAF=$sqli_AF->fetch_assoc();
			$A_asistencia_clase=$DAF["asistencia"];
			$A_hrs_pedagogicas_clase=$DAF["horas_pedagogicas"];
			if(DEBUG){ echo"Asistencia Clase[$A_asistencia_clase] Horas Clase[$A_hrs_pedagogicas_clase]<br>";}
		$sqli_AF->free();
		
		$considerar=false;
		switch($A_asistencia_clase)
		{
			case"presente":
				$A_asistencia_clase_label="P";
				$considerar=true;
				$factor=1;
				$color="#0A0";
				break;
			case"ausente":
				$A_asistencia_clase_label="A";
				$considerar=true;
				$factor=0;
				$color="#F77";
				break;
			case"justificado":
				$A_asistencia_clase_label="J";
				$considerar=true;
				$factor=0.7;
				$color="#0AA";
				break;	
			default:
				$A_asistencia_clase_label="";
				$color="";	
		}
		
		$ARRAY_FECHA_ESTADO[$aux_fecha_clase]=$A_asistencia_clase_label;
		if($considerar)
		{
			$A_hrs_asistencia_alumno=($A_hrs_pedagogicas_clase*$factor);
			$TOTAL_HORAS_ALUMNO+=$A_hrs_asistencia_alumno;
			$hrs_clase_realizada+=$A_hrs_pedagogicas_clase;

		}
}//fin foreach	

	if($TOTAL_HORAS_PROGRAMA>0)
	{$porcentaje_asistencia_alumno=(($TOTAL_HORAS_ALUMNO*100)/ $TOTAL_HORAS_PROGRAMA);	}
	else{$porcentaje_asistencia_alumno=0;}
	
	if($hrs_clase_realizada >0)
	{$porcentaje_asistencia_clase_realizada=(($TOTAL_HORAS_ALUMNO*100)/$hrs_clase_realizada);}
	else{$porcentaje_asistencia_clase_realizada=0;}
	
	if(DEBUG){ echo"Resumen<br> HRS CLASE REALIZADA: $hrs_clase_realizada<br>TOTAL HORAS ALUMNO: $TOTAL_HORAS_ALUMNO<br>% asistencia clase realizada: $porcentaje_asistencia_clase_realizada<br>% asistencia General $porcentaje_asistencia_alumno<br>";
		var_dump($ARRAY_FECHA_ESTADO);
	}
			
	$ARRAY_RESPUESTA=array($hrs_clase_realizada, $TOTAL_HORAS_ALUMNO, $porcentaje_asistencia_clase_realizada, $porcentaje_asistencia_alumno, $ARRAY_FECHA_ESTADO);			
		
	$conexion_mysqli->close();	
	if(DEBUG){ echo"<br><strong>----------------------------------FIN FUNCION PORCENTAJE ASISTENCIA--------------------------------</strong><br>";}
	return($ARRAY_RESPUESTA);
	
}
//determina si un ramo es aprobado, reprobado o sin nota en un periodo de acuerdo a las tres opciones de salida
//1 aprobado, 2 reporbado, 3 NN
// en periodo determinado, segun notas semestrales.

function RAMO_CONDICION($id_alumno, $id_carrera, $cod_asignatura, $semestre=0, $year=0){
	if(DEBUG){ echo"<br><strong>----------------------------------FUNCION RAMO_CONDICION--------------------------------</strong><br>";}
	if(DEBUG){ echo"id_alumno: $id_alumno<br>id_carrera: $id_carrera <br>cod_asignatura: $cod_asignatura<br>periodo: $semestre - $year<br>";}
	require("conexion_v2.php");
	$notaAprobacion=4;
	$ramoCondicion=0;
	$consultar=false;
	
	if($semestre==0 and $year==0){
		//busco condicion actual del ramo
		$consN="SELECT nota FROM notas WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND cod='$cod_asignatura'";
		$consultar=true;
	}
	elseif($semestre>0 and $year>0){
		
		//busco condicion del ramo en periodo particular
		$consN="SELECT nota FROM notas_hija WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND codigo='$cod_asignatura' AND semestre='$semestre' AND year='$year'";
		$consultar=true;
	}
	
	
	if($consultar){
		$sqliN=$conexion_mysqli->query($consN);
		$N=$sqliN->fetch_row();
		$auxNota=$N[0];
		if(empty($auxNota)){$ramoCondicion=3;}
		else{		
			if($auxNota>=$notaAprobacion){$ramoCondicion=1;}
			else{$ramoCondicion=2;}
		}
		$sqliN->free();
	}else{if(DEBUG){echo"ERROR, semestre o year invalido......";}}
	
	if(DEBUG){ echo"Ramo condicion: $ramoCondicion<br>";}
	$conexion_mysqli->close();
	if(DEBUG){ echo"<br><strong>----------------------------------FIN FUNCION RAMO_CONDICION--------------------------------</strong><br>";}
	return($ramoCondicion);
	
}

function ALUMNOS_CURSO($sede, $id_carrera, $yearIngresoCarrera, $jornada, $grupo, $nivel, $yearVigencia, $semestreVigencia){
	$LISTALUMNOS=array();
	require("conexion_v2.php");
	
	if($sede==""){$sede="Talca";}
	$condicion=" contratos2.sede='$sede' AND contratos2.condicion<>'inactivo'";
	
	if($id_carrera>0){ $condicion.=" AND contratos2.id_carrera='$id_carrera'";}
	if($yearIngresoCarrera!="0"){$condicion.=" AND contratos2.yearingresocarrera='$yearIngresoCarrera'";}
	if($jornada!="0"){$condicion.=" AND contratos2.jornada='$jornada'";}
	if($grupo!="Todos"){$condicion.=" AND alumno.grupo='$grupo'";}
	$condicion.=" AND contratos2.ano='$yearVigencia'";
	
	$inicio_ciclio=true;
	$niveles="";
	if(is_array($nivel))
	{
		foreach($nivel as $nn=>$valornn)
		{
			if($inicio_ciclio)
			{ 
				$niveles.="'$valornn'";
				$inicio_ciclio=false;
			}
			else
			{ $niveles.=", '$valornn'";}
			//echo"--> $niveles<br>";
		}
	}
	else{ $niveles="'sin nivel'";}
	
	$cons_main_1="SELECT DISTINCT(id_alumno), contratos2.id_carrera, contratos2.yearIngresoCarrera FROM contratos2 INNER JOIN alumno ON contratos2.id_alumno = alumno.id WHERE $condicion";
		$sql_main_1=$conexion_mysqli->query($cons_main_1)or die("<br>MAIN 1 $cons_main_1 ".$conexion_mysqli->error);
		$num_reg_M=$sql_main_1->num_rows;
		if(DEBUG){ echo"<br><br><strong>$cons_main_1<br>NUM.$num_reg_M</strong><br>";}
		if($num_reg_M>0)
		{
			$x=0;
			$aux=0;
			$i=0;
			while($DID=$sql_main_1->fetch_row())
			{
				$x++;
				$id_alumno=$DID[0];
				$id_carrera_alumno=$DID[1];
				$yearIngresoCarrera_alumno=$DID[2];
				
				if(DEBUG){ echo"[$x] id_alumno: $id_alumno id_carrera_alumno: $id_carrera_alumno yearIngresoCarrera: $yearIngresoCarrera_alumno<br>";}
				list($hay_contrato, $array_datos_contrato)=CONDICION_DE_ALUMNO_PERIODO($id_alumno, $id_carrera_alumno, $yearIngresoCarrera_alumno, $semestreVigencia,$yearVigencia);
				
				if($hay_contrato){
					$LISTALUMNOS[$i]["id_alumno"]=$id_alumno;
					$LISTALUMNOS[$i]["id_carrera"]=$id_carrera;
					$LISTALUMNOS[$i]["yearIngresoCarrera"]=$yearIngresoCarrera_alumno;
					$i++;
				}
			}
		}
		$sql_main_1->free();
		$conexion_mysqli->close();
		
		return($LISTALUMNOS);
}

//
function NOMBRE_PROVEEDOR($id_proveedor){
	$razon_social="";
	if($id_proveedor>0){
		require("conexion_v2.php");
		$cons="SELECT razon_social FROM proveedores WHERE id_proveedor='$id_proveedor' LIMIT 1";
		$sqli=$conexion_mysqli->query($cons);
		$P=$sqli->fetch_assoc();
		$razon_social=$P["razon_social"];
		$sqli->free();
		$conexion_mysqli->close();
	}
	return($razon_social);
}
//devuelve datos de un liceo dado su id. nombre, region, comuna
function DATOS_LICEO($idLiceo){
	
	require("conexion_v2.php");
	
	$nombreEstablecimiento=""; 
	$region=""; 
	$comuna=""; 
	$dependencia="";
		
	if($idLiceo>0){
		$cons="SELECT nombreEstablecimiento, region, comuna, dependencia FROM liceos WHERE idLiceo='$idLiceo' LIMIT 1";
		$sqli=$conexion_mysqli->query($cons);
		$L=$sqli->fetch_assoc();
		$nombreEstablecimiento=trim($L["nombreEstablecimiento"]);
		$comuna=$L["comuna"];
		$region=$L["region"];
		$dependencia=$L["dependencia"];
		$sqli->free();
	}
	$ARRAY_RESPUESTA=array($nombreEstablecimiento, $region, $comuna, $dependencia);
	
	$conexion_mysqli->close();
	
	return($ARRAY_RESPUESTA);
}

function EMAIL_INSTITUCIONAL_DISPONIBLE($emailCandidato){
	require("../../../funciones/conexion_v2.php");
	if(DEBUG){ echo"<br>-----------------------INICIO FUNCION EMAIL_DISPONIBLE----------------------<br>";}

	$ARRAY_BUSQUEDA=array('alumno'=>'emailInstitucional',
						  'personal'=>'email');
	
	$coincidencias=0;
	foreach($ARRAY_BUSQUEDA as $tabla => $campo){
		$cons="SELECT COUNT(id) FROM $tabla WHERE $campo='$emailCandidato'";
	
		$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		$C=$sqli->fetch_row();
		$found=$C[0];
		if(empty($found)){$found=0;}
		if(DEBUG){echo "$cons -> ($found)<br>";}
		$coincidencias+=$found;
		$sqli->free();
	}
	
	if(DEBUG){ echo"Numero Coincidencias: $coincidencias<br>";}
	 $conexion_mysqli->close();
	 
	  if(DEBUG){ echo"-----------------------FIN FUNCION EMAIL_DISPONIBLE----------------------<br>";}
	if($coincidencias>0){ return(false);}
	else{ return(true);}
}
?>