<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Matriculas_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//parametros
	$error=0;
	 $continuar=true;
	 $boleta_global=true;
	 $cambiar_condicion_contratos_antiguos=true;
	 $hacer_toma_ramo_nivel_1=true; ////realizar toma ramo automatica a alumno nivel 1	 
	 $grabar_boleta_pagare=false;//boleta para pagare
//-----------------------------------------//		 
	 if(DEBUG)
	 {
	 	echo"PASO 1 ->".$_SESSION["FINANZAS"]["paso1"]."<br>";
		echo"PASO 2 ->".$_SESSION["FINANZAS"]["paso2"]."<br>";
		echo"PASO 3 ->".$_SESSION["FINANZAS"]["paso3"]."<br>";
	 }
///////////////////////////////////
///verificacion ya grabado x session
if(isset($_SESSION["FINANZAS"]["SAVE"]))
{
	if($_SESSION["FINANZAS"]["SAVE"]){ $contrato_ya_grabado=true;}
	else{ $contrato_ya_grabado=false;}
}
else
{ $contrato_ya_grabado=false;}
//verificar pasos previos

if((isset($_SESSION["FINANZAS"]["paso1"]))and(isset($_SESSION["FINANZAS"]["paso2"]))and(isset($_SESSION["FINANZAS"]["paso3"])))
{
	if(($_SESSION["FINANZAS"]["paso1"])and($_SESSION["FINANZAS"]["paso2"])and($_SESSION["FINANZAS"]["paso3"]))
	{ $pasos_previos_ok=true;}
	else
	{ $pasos_previos_ok=false;}
}
else
{$pasos_previos_ok=false;}
////////////////////////////////////	 
	
if(!$contrato_ya_grabado) 
{
	if($pasos_previos_ok)
	{ $iniciar_proceso_grabacion=true;}
	else
	{ $iniciar_proceso_grabacion=false;}
}
else
{ $iniciar_proceso_grabacion=false;}

///---------------------------------------------------//
if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
{
	$hay_alumno_seleccionado=$_SESSION["SELECTOR_ALUMNO"]["ACTIVO"];
	if(empty($hay_alumno_seleccionado)){ $hay_alumno_seleccionado=false;}
}
else
{ $hay_alumno_seleccionado=false;}
	 
	if(($iniciar_proceso_grabacion)and($hay_alumno_seleccionado))
	{
		require("../../../funciones/funciones_sistema.php");
		require("../../../funciones/conexion_v2.php");
		include("../../../funciones/VX.php");
		//////////////////////////Obtencion de ID del Alumno //////////////////////////////
		//y ordenamiento de datos en array contrato
		$cantidad_de_boletas=0;
 		$sede_alumno=$_SESSION["FINANZAS"]["lugar_contrato"];
 		$rut_alumno=$_SESSION["FINANZAS"]["rut_alumno"];
		
		$jornada_alumno=$_SESSION["FINANZAS"]["jornada"];
 		$carrera=$_SESSION["FINANZAS"]["carrera_alumno"];
		$id_carrera=$_SESSION["FINANZAS"]["id_carrera"];//agregada para poner id_carerra en contrato 02/05/2013
 		$semestre=$_SESSION["FINANZAS"]["semestre"];
		$year_estudio=$_SESSION["FINANZAS"]["year_estudio"];
 		//$año_contrato=end(explode("-",$_SESSION["FINANZAS"]["fecha_inicio"])); //modifico por una variable independiente que traigo
		$año_contrato=$_SESSION["FINANZAS"]["year_estudio"];
		$linea_credito_cantidad=$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["cantidad"];
		$contado_cantidad=$_SESSION["FINANZAS"]["METODO_PAGO"]["CONTADO"]["cantidad"];
		$contado_descuento=$_SESSION["FINANZAS"]["METODO_PAGO"]["CONTADO"]["descuento"];
		$cheque_cantidad=$_SESSION["FINANZAS"]["METODO_PAGO"]["CHEQUE"]["cantidad"];
		$cheque_numero=$_SESSION["FINANZAS"]["METODO_PAGO"]["CHEQUE"]["numero"];
		$cheque_banco=$_SESSION["FINANZAS"]["METODO_PAGO"]["CHEQUE"]["banco"];
		$cheque_matricula_arancel=$_SESSION["FINANZAS"]["METODO_PAGO"]["CHEQUE"]["matricula_arancel"];///--------------->
		////por precaucion valores < 0 (Excedente muy grande)
		if($linea_credito_cantidad<0){ $linea_credito_cantidad=0;}
		if($cheque_cantidad<0){$cheque_cantidad=0;}
		if($contado_cantidad<0){ $contado_cantidad=0;}
		//////
		
		////////////
		//por excedentes
		$excedente=$_SESSION["FINANZAS"]["excedente"];
		$id_contrato_anterior=$_SESSION["FINANZAS"]["id_contrato_anterior"];
		////para becas
		$totalbeneficiosEstudiantiles=$_SESSION["FINANZAS"]["totalBeneficiosEstudiantiles"];
		/////////
		if(empty($cheque_matricula_arancel))
		{$cheque_matricula_arancel=false;}
		
		if($cheque_matricula_arancel=="ON"){ $cheque_matricula_arancel=true;}
		else{ $cheque_matricula_arancel=false;}
		
		//calculo la cantidad a pagar al contado menos el % de desc
		$descuento_pago_contado=round(($contado_cantidad*$contado_descuento)/100);
		$contado_cantidad_menos_desc=($contado_cantidad-$descuento_pago_contado);
		/////////////////
		if($linea_credito_cantidad>0)
		{$numero_cuotas_lcredito=$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["cantidad_cuotas"];}
		else
		{$numero_cuotas_lcredito=0;}	
		
		//calculo el arancel que pagara descontando beca
		$vigencia_cuotas=$_SESSION["FINANZAS"]["vigencia_cuotas"];
		switch($vigencia_cuotas)
		{
			case"semestral":
				$arancelV2=$_SESSION["FINANZAS"]["arancel"];
				break;
			case"anual":
				$arancelV2=$_SESSION["FINANZAS"]["arancel_anual"];
				break;	
		}
		
		/////////////////////-becas--/////////////////////
	
		
		

		
		if($_SESSION["FINANZAS"]["opcion_matricula"]=="EXCEDENTE")
		{ 
			$aux_excedente=($excedente-$_SESSION["FINANZAS"]["matricula"]);
			if($aux_excedente<0){ $aux_excedente=0;}
			if(DEBUG){ echo"paga matricula con excedente NUEVO EXCEDENTE: $aux_excedente<br>";}
		}
		else
		{ $aux_excedente=$excedente;}
		
		$aux_total=((($arancelV2)-$aux_excedente)-$totalbeneficiosEstudiantiles);
		
		if($aux_total<0)
		{ 
			$excedente_proximo_year=abs($aux_total);
			$aux_total=0;
		}
		else
		{ $excedente_proximo_year=0;}
		
		$contrato["excedente_proximo_year"]=$excedente_proximo_year;
		
		
		///////////////////////----/////////////////////////////
		$arancel_total=($aux_total);
	
		////////DESCUENTO porcentaje pago al contado
		if($contado_descuento>0)
		{
			$descuento_contado=(($arancel_total*$contado_descuento)/100);
			$arancel_total=round($arancel_total-$descuento_contado);
		}
		
		
 		//------------------------------------datos alumno-----------------------------------
 		$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
		//$sede_alumno=$_SESSION["SELECTOR_ALUMNO"]["sede"];
		//------------------------------------------------------------------------------
 //------------------------------------------------------------------------------------//
 		//añado campos para reflejar exactamente como paga arancel
		$contrato["contado_paga"]=$contado_cantidad_menos_desc;
		$contrato["cheque_paga"]=$cheque_cantidad;
		$contrato["linea_credito_paga"]=$linea_credito_cantidad;
		
		$contrato["arancel"]=$arancelV2; //$_SESSION["FINANZAS"]["arancel"];
		
		
		$contrato["p_desc_contado"]=$contado_descuento;
		$contrato["id_alumno"]=$id_alumno;
		$contrato["id_carrera"]=$id_carrera;
		$contrato["jornada"]=$jornada_alumno;
		$contrato["sede_alumno"]=$sede_alumno;
		
		$contrato["totalBeneficiosEstudiantiles"]=$totalbeneficiosEstudiantiles;
		$contrato["txt_beca"]="";
		
		$F_inicio=explode("-",$_SESSION["FINANZAS"]["fecha_inicio"]);
		$contrato["fecha_inicio"]=$F_inicio[2]."-".$F_inicio[1]."-".$F_inicio[0];
		
		$F_fin=explode("-",$_SESSION["FINANZAS"]["fecha_fin"]);
		$contrato["fecha_fin"]=$F_fin[2]."-".$F_fin[1]."-".$F_fin[0];
		
		$contrato["año_contrato"]=$año_contrato;
		$contrato["semestre"]=$semestre;
		$contrato["total"]=$arancel_total;
		//comentario por excedente utilizado solo en arancel
		//-------------------------------------------//
		if(($_SESSION["FINANZAS"]["opcion_matricula"]!="EXCEDENTE")and($excedente>0))
		{$_SESSION["FINANZAS"]["comentario_beca"]=" Utiliza Excedente de: $".number_format($excedente,0,",",".")." Para Desc. Arancel.";}
		//-------------------------------------------//
		///agrego comentario si uso excedente
		if($excedente>0)
		{	
			if(isset($_SESSION["FINANZAS"]["EX_nuevo_excedente"]))
			{
				if($_SESSION["FINANZAS"]["excedente"]>=$_SESSION["FINANZAS"]["matricula"])
				{$nuevo_valor_matricula=0;}
				else
				{$nuevo_valor_matricula=($_SESSION["FINANZAS"]["matricula"]-$_SESSION["FINANZAS"]["excedente"]);}
				$contrato["txt_beca"].="*Utiliza Saldo A Favor de $".number_format($excedente,0,",",".")." por Excedente de Contrato anterior Cod.:(".$id_contrato_anterior."). -para Matricula: $".number_format($_SESSION["FINANZAS"]["matricula"],0,",",".")." Para Arancel: $".number_format($_SESSION["FINANZAS"]["EX_nuevo_excedente"],0,",",".")."-*";
			}
			else
			{
				$contrato["txt_beca"].="*Utiliza Saldo A Favor de $".number_format($excedente,0,",",".")." por Excedente de Contrato anterior Cod.:(".$id_contrato_anterior.")*";
				$nuevo_valor_matricula=$_SESSION["FINANZAS"]["matricula"];
				
			}
			
		}
		else
		{ $nuevo_valor_matricula=$_SESSION["FINANZAS"]["matricula"];}
		
		if(DEBUG){echo"nuevo valor matricula---->$nuevo_valor_matricula<br>";}
		
		$contrato["matricula_valor"]=$_SESSION["FINANZAS"]["matricula_total"];
		$contrato["matricula_a_pagar"]=$nuevo_valor_matricula;
		
		$contrato["opcion_pag_matricula"]=$_SESSION["FINANZAS"]["opcion_matricula"];
		
		$contrato["numero_cuotas"]=$numero_cuotas_lcredito;
		$contrato["yearIngresoCarrera"]=$_SESSION["FINANZAS"]["ingresoCarrera"];
		//$contrato["matricula_a_pagar"]=$_SESSION["FINANZAS"]["matricula"];
		
		$contrato["saldo_a_favor"]=$excedente;
		$contrato["id_contrato_anterior"]=$id_contrato_anterior;
		//para el campo del sostenedor agregado en el contrato
		$contrato["sostenedor"]=$_SESSION["FINANZAS"]["sostenedor"];
		if($contrato["sostenedor"]=="otro")
		{$contrato["sostenedor_nombre"]=$_SESSION["FINANZAS"]["sostenedor_nombre"];}
		//si no paga matricula lo reflejo en la variable como 0
		if($_SESSION["FINANZAS"]["opcion_matricula"]=="NO")
		{$contrato["matricula_a_pagar"]=0;}
		/////////////////////////////////////////////////
	//	var_export($contrato);
		////////////////////ORDENO DATOS PAGO DE MATRICULA//////////////////////
		if(DEBUG){var_dump($_SESSION["FINANZAS"]);}
		
			//////////////////////GENERO BOLETA PRIMERO/////////////
			$opcion_matriculaX=$contrato["opcion_pag_matricula"];
			$valor_matriculaX=$contrato["matricula_a_pagar"];
			$array_boleta["global"]= $boleta_global;
			//una para todo
			$str_br="[br]";
			if($boleta_global)
			{
				$hacer_boleta=false;
				$valor_global=0;
				$glosa_global="";
				
				switch($opcion_matriculaX)
				{
					case"CONTADO":
						if($valor_matriculaX>0)
						{
							$valor_global+=$valor_matriculaX;
							$glosa_global.="Pago Matricula $(".number_format($valor_matriculaX,0,",",".").") $str_br";
							$hacer_boleta=true;///hago boleta
						}
						break;
					case"EXCEDENTE":
						if($valor_matriculaX>0)
						{
							$valor_global+=$valor_matriculaX;
							$glosa_global.="Valor Matricula $(".number_format($_SESSION["FINANZAS"]["matricula"],0,",",".").") $str_br";
							$glosa_global.="Utiliza EXCEDENTE para matricula$(".number_format($_SESSION["FINANZAS"]["matricula"]-$valor_matriculaX,0,",",".").") $str_br";
							$glosa_global.="Total a Pagar por Matricula$(".number_format($valor_matriculaX,0,",",".").") $str_br";
							$hacer_boleta=true;///hago boleta
						}
						break;	
					case"CHEQUE":
						if($valor_matriculaX>0)
						{
							$cheque_numero_mat=$_SESSION["FINANZAS"]["num_cheque_mat"];
							$cheque_banco_mat=$_SESSION["FINANZAS"]["banco_cheque_mat"];
							$valor_global+=$valor_matriculaX;
							$glosa_global.="Pago Matricula (cheque: $cheque_banco_mat N:$cheque_numero_mat Valor: $".number_format($valor_matriculaX,0,",",".").") $str_br";
							$hacer_boleta=true;///hago boleta
						}
						break;	
				}
				///////para arancel
				if($contado_cantidad>0)
				{
					$valor_global+=$contado_cantidad_menos_desc;
					$glosa_global.="Pago Arancel(efectivo) $".number_format($contado_cantidad_menos_desc,0,",",".")." $str_br";
					$hacer_boleta=true;///hago boleta
				}	
				if($cheque_cantidad>0)
				{
					$valor_global+=$cheque_cantidad;
					$glosa_global.="Pago Arancel(cheque: $cheque_banco N:$cheque_numero Valor: $".number_format($cheque_cantidad,0,",",".").") $str_br";
					$hacer_boleta=true;///hago boleta
				}
				
				/////si hay que hacer boleta se genera
				if($hacer_boleta)
				{$id_boleta_global=GENERA_BOLETA($id_alumno, $valor_global, $sede_alumno, $glosa_global);}
				else
				{$id_boleta_global=0;}	
				if(DEBUG)
				{echo"<br><br>----> VALOR GLOBAL BOLETA: $valor_global<br>";}	
				
				$array_boleta["matricula"]=$id_boleta_global;
				$array_boleta["contado"]=$id_boleta_global;
				$array_boleta["cheque"]=$id_boleta_global;
				$array_boleta["hacer_boleta"]=$hacer_boleta;
			}
			else
			{
				//boletas individuales
				//matricula
				$hacer_boleta=false;
				if($opcion_matriculaX!="NO")
				{
					switch($opcion_matriculaX)
					{
						case"CONTADO":
							$glosa="Pago Matricula(efectivo)";
							$hacer_boleta=true;
							$id_boleta_matricula=GENERA_BOLETA($id_alumno, $valor_matriculaX, $sede_alumno, $glosa);
							break;
						case"CHEQUE":
							$glosa="Pago Matricula (cheque: $cheque_banco_mat N:$cheque_numero_mat) ";
							$hacer_boleta=true;
							$id_boleta_matricula=GENERA_BOLETA($id_alumno, $valor_matriculaX, $sede_alumno, $glosa);
							break;	
					}
					$array_boleta["matricula"]=$id_boleta_matricula;
				}
				//arancel
				if($contado_cantidad>0)
				{
					$glosa="Pago Arancel(efectivo) ";
					$hacer_boleta=true;
					$id_boleta_contado=GENERA_BOLETA($id_alumno, $contado_cantidad_menos_desc, $sede_alumno, $glosa);
					$array_boleta["contado"]=$id_boleta_contado;
				}	
				if($cheque_cantidad>0)
				{
					$glosa="Pago Arancel(cheque: $cheque_banco N:$cheque_numero) ";
					$hacer_boleta=true;
					$id_boleta_cheque=GENERA_BOLETA($id_alumno, $cheque_cantidad, $sede_alumno, $glosa);
					$array_boleta["cheque"]=$id_boleta_cheque;
				}
			}
				$array_boleta["hacer_boleta"]=$hacer_boleta;
				$_SESSION["FINANZAS"]["BOLETA"]=$array_boleta;
				
				$contrato["id_boleta_global"]=$id_boleta_global;//agregada para dejar registro de la boleta que se genero si realizo pago
				
		
		if(!$continuar)
		{
			if(DEBUG)
		 		{echo"Error GRABANDO CONTRATO salir<br>";}
				else{header("location: resumen.php");};
		}
			$id_contrato=GRABA_CONTRATO($contrato);
			if($id_contrato>0)
			{
					////Registro Matricula Alumno
					$tipo_registro="Matricula";
					$descripcion="Alumno Matriculado Carrera: $carrera Nivel: ".$_SESSION["FINANZAS"]["nivel"]." Jornada: ".$_SESSION["FINANZAS"]["jornada"]." Grupo: ".$_SESSION["FINANZAS"]["grupo"];
					REGISTRO_EVENTO_ALUMNO($id_alumno, $tipo_registro, $descripcion);
					////////////////////////////////////////////
					//registro evento global
					$evento="Matricula V1.2 Alumno id_alumno: $id_alumno id_carrera: $id_carrera Sede: $sede_alumno";
					REGISTRA_EVENTO($evento);
				///////////////////REGISTRO PAGO MATRICULA///////////////////////////////////
				$opcion_pago_mat=$_SESSION["FINANZAS"]["opcion_matricula"];
				
				$id_cheque_inicial=0;
				$mat_datos["fecha_vence_cheque_inicial"]="0000-00-00";
				if($opcion_pago_mat!="NO")
				{
					//linea credito
					if(isset($_SESSION["FINANZAS"]["fecha_vence_lcredito_mat"])){$mat_datos["fechaV_lcredito"]=$_SESSION["FINANZAS"]["fecha_vence_lcredito_mat"];}
					else{ $mat_datos["fechaV_lcredito"]="0000-00-00";}
					//cheque
					
						if(isset($_SESSION["FINANZAS"]["fecha_vence_cheque_mat"])){$cheque_vence_mat=$_SESSION["FINANZAS"]["fecha_vence_cheque_mat"];}
						else{ $cheque_vence_mat="0000-00-00";}
						
						if(isset($_SESSION["FINANZAS"]["num_cheque_mat"])){$cheque_numero_mat=$_SESSION["FINANZAS"]["num_cheque_mat"];}
						else{$cheque_numero_mat="";}
						
						if(isset($_SESSION["FINANZAS"]["banco_cheque_mat"])){$cheque_banco_mat=$_SESSION["FINANZAS"]["banco_cheque_mat"];}
						else{$cheque_banco_mat="";}
					if($opcion_pago_mat=="CHEQUE")
					{
						///REGISTRO CHEQUES
						if(DEBUG){ echo"<br>CHEQUE MATRICULA<br>valor: $nuevo_valor_matricula<br>";}
						if(DEBUG){ echo"CHEQUE ARANCEL<br>valor: $cheque_cantidad<br>";}
						if($cheque_matricula_arancel)
						{
							if(DEBUG){ echo"Hacer cheque X matricula y arancel: Si<br>";}
							 $valor_total_cheque=($nuevo_valor_matricula+$cheque_cantidad);
							 $glosa_cheque_inicial="Pago Matricula y Arancel";
						}
						else
						{
							if(DEBUG){ echo"Hacer cheque X matricula y arancel: No<br>";}
							 $valor_total_cheque=$nuevo_valor_matricula;
							 $glosa_cheque_inicial="Pago de matricula";
						}
						
						$array_cheque_inicial["id_alumno"]=$id_alumno;
						$array_cheque_inicial["numero"]=$cheque_numero_mat;
						$array_cheque_inicial["fecha_vence"]=$cheque_vence_mat;
						$array_cheque_inicial["banco"]=$cheque_banco_mat;
						$array_cheque_inicial["valor"]=$valor_total_cheque;
						$array_cheque_inicial["sede"]=$sede_alumno;
						$array_cheque_inicial["glosa"]=$glosa_cheque_inicial;
						
						$id_cheque_inicial=REGISTRA_CHEQUE($array_cheque_inicial, $cheque_matricula_arancel);
					}//fin si pago mat == cheque
					
					//comunes
					$mat_datos["ano"]=$año_contrato;
					$mat_datos["semestre"]=$semestre;
					$mat_datos["opcion_pago"]=$opcion_pago_mat;	
					$mat_datos["sede"]=$sede_alumno;	
					$mat_datos["id_alumno"]=$id_alumno;	
					$mat_datos["matricula"]=$nuevo_valor_matricula;
					$mat_datos["cheque_matricula_arancel"]=$cheque_matricula_arancel;
					$mat_datos["id_contrato"]=$id_contrato; //agregado
					$mat_datos["id_cheque_inicial"]=$id_cheque_inicial;
					$mat_datos["fecha_vence_cheque_inicial"]=$cheque_vence_mat;
					
					//genero el pago de matricula solo si hay un valor que pagar en matricula
					if($nuevo_valor_matricula>0)
					{$continuar=GRABAR_PAG_MAT($mat_datos, $array_boleta);}
				}
				
		
			/////cambio de condicion los demas contratos que tenga el alumno registra
				if($cambiar_condicion_contratos_antiguos)
				{
					$nueva_condicion="old";
					CAMBIAR_CONDICION_CONTRATOS_OLD($id_contrato,$id_alumno, $nueva_condicion);
				}
			///////////////////////////////////////////////////////////////////////////
			
			//----------------------------------------------------------//
				//				BOLETA  	PAGARE							//
				//----------------------------------------------------------//
				if($grabar_boleta_pagare)
				{ 
					if($linea_credito_cantidad>0)
					{
						$hacer_boleta_pagare=true;
						if(DEBUG){ echo"<br>---><strong>Crea Boleta Pagare</strong><br>";}
						$glosa_pagare="Respaldo Pagare";
						$id_boleta_pagare=GENERA_BOLETA($id_alumno, $linea_credito_cantidad, $sede_alumno, $glosa_pagare);
						$contrato["id_boleta_pagare"]=$id_boleta_pagare;
						$pago_pagare["id_alumno"]=$id_alumno;
						$pago_pagare["glosa"]=$glosa_pagare;
						$pago_pagare["sede"]=$sede_alumno;
						$pago_pagare["id_boleta"]=$id_boleta_pagare;
						$pago_pagare["total"]=$linea_credito_cantidad;	
						$pago_pagare["forma_pago"]="efectivo";
						$pago_pagare["por_concepto"]="arancel";
						$pago_pagare["semestre"]=$semestre;
						$pago_pagare["year"]=$year_estudio;
						GENERA_PAGO($pago_pagare);
						$_SESSION["FINANZAS"]["BOLETA"]["id_boleta_pagare"]=$id_boleta_pagare;
						if($id_contrato>0)
						{
							$cons_up="UPDATE contratos2 SET id_boleta_pagare='$id_boleta_pagare' WHERE id='$id_contrato' AND id_alumno='$id_alumno' LIMIT 1";
							if(DEBUG){ echo"ACTUALIZA ID_boleta_pagare:--> $cons_up<br>";}
							else{$conexion_mysqli->query($cons_up);}
						}
					}
					else{$hacer_boleta_pagare=false;}
					
				}
				else{$contrato["id_boleta_pagare"]=0; $hacer_boleta_pagare=true;}
				$_SESSION["FINANZAS"]["BOLETA"]["hacer_boleta_pagare"]=$hacer_boleta_pagare;
				//-------------------------------------------------------------//
				//-------------------------------------------------------------//
				/////---------------------------------------------------------///
				//toma ramo 1 nivel obligatorio
				if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
				{
					$nivel_alumnox=$_SESSION["FINANZAS"]["nivel"];
					if($nivel_alumnox==1)
					{ $TOMA_RAMO_NIVEL_1=true;}
					else
					{ $TOMA_RAMO_NIVEL_1=false;}
				}
				else
				{ $TOMA_RAMO_NIVEL_1=false;}
				///////////////////////////////////////////
				$ingresoCarrera=$_SESSION["FINANZAS"]["ingresoCarrera"];
				if($year_estudio==$ingresoCarrera)
				{ $TOMA_RAMO_NIVEL_1_A=true;}
				else
				{ $TOMA_RAMO_NIVEL_1_A=false;}
				//---------------------------------
				if($TOMA_RAMO_NIVEL_1 and $TOMA_RAMO_NIVEL_1_A)
				{
					$tipo_registro="Toma Ramos";
					$descripcion="Toma Ramo Nivel 1 Automatica";
					
					if($hacer_toma_ramo_nivel_1)
					{ 
					if(DEBUG){ echo"hacer toma ramo nivel 1: activada...<br>";}
					   TOMA_RAMO_OBLIGATORIA($id_alumno, $id_carrera, $ingresoCarrera, $nivel_alumnox, $semestre, $year_estudio, $sede_alumno,$jornada_alumno);
					   REGISTRO_EVENTO_ALUMNO($id_alumno, $tipo_registro, $descripcion);
					}
					else
					{ if(DEBUG){ echo"hacer toma ramo nivel 1: desactivada...<br>";}}
				}
				else
				{ if(DEBUG){ echo"NO se puede hacer toma ramo nivel 1<br>";}}
				/////---------------------------------------------------------///
				//--------------------------------------------------------------//
				if(DEBUG){ echo"<br><strong>Recorro metodos de pago</strong><br>";}
				foreach($_SESSION["FINANZAS"]["METODO_PAGO"] as $n => $valor)
				{
					$metodo_pago=$n;
					$cantidad=$valor["cantidad"];
					switch($metodo_pago)
					{
						case"LINEA_CREDITO":
							if(DEBUG){ echo"---> <strong>LINEA_CREDITO</strong><br>";}
							if($cantidad>0)
							{
								if(DEBUG){ echo"---> Cantidad Pactada: $cantidad<br>";}
								$cuotas["cantidad_cuotas"]=$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["cantidad_cuotas"];
								$cuotas["mes_ini"]=$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["mes_ini_cuota"];
								$cuotas["dia_vence"]=$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["dia_vence_cuota"];
								$cuotas["total"]=$cantidad;
								$cuotas["year"]=$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["year"];
								$cuotas["year_estudio"]=$año_contrato;
								$cuotas["semestre"]=$semestre;
								$cuotas["sede"]=$sede_alumno;
								$cuotas["id_alumno"]=$id_alumno;
								$cuotas["id_contrato"]=$id_contrato;
								$cuotas["meses_avance"]=$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["meses_avance"];//agregado;
								GENERAR_CUOTAS($cuotas);
								unset($cuotas);
							}
							break;
						case"CONTADO":
							if(DEBUG){ echo"---> <strong>CONTADO</strong><br>";}
							if($cantidad>0)
							{
								if(DEBUG){ echo"---> Cantidad Pactada: $cantidad<br>";}
								$idX_boleta=$array_boleta["contado"];
								$pago["id_alumno"]=$id_alumno;
								$pago["sede"]=$sede_alumno;
								$pago["id_boleta"]=$idX_boleta;
								$pago["total"]=$contado_cantidad_menos_desc;
								if($contado_cantidad_menos_desc==$arancel_total)
								{$pago["glosa"]="Pago Total Arancel (CONTADO) Boleta($idX_boleta)";}
								else
								{$pago["glosa"]="Pago Arancel (CONTADO) Boleta($idX_boleta)";}	
								$pago["forma_pago"]="efectivo";
								$pago["por_concepto"]="arancel";
								$pago["semestre"]=$semestre;
								$pago["year"]=$year_estudio;
								GENERA_PAGO($pago);
								unset($pago);
							}
							break;
						case"CHEQUE":
							if(DEBUG){ echo"---> <strong>CHEQUE</strong><br>";}
							if($cantidad>0)
							{
								if(DEBUG){ echo"---> Cantidad Pactada: $cantidad<br>";}
								$cheque["banco"]=$valor["banco"];
								$cheque["fecha_vence"]=$valor["fecha_vencimiento"];
								$cheque["numero"]=$valor["numero"];
								$cheque["id_alumno"]=$id_alumno;
								$cheque["sede"]=$sede_alumno;
								$cheque["valor"]=$valor["cantidad"];
								
								////boleta
								$ID_boleta=$array_boleta["cheque"];
								
								///registro de pago
								$pagoX["id_alumno"]=$id_alumno;
								$pagoX["sede"]=$sede_alumno;
								$pagoX["id_boleta"]=$ID_boleta;
								$pagoX["total"]=$cantidad;
								$pagoX["por_concepto"]="arancel";
								$pagoX["semestre"]=$semestre;
								$pagoX["year"]=$year_estudio;
								//armo glosa dependiendo del valor
								if($cantidad==$arancel_total)
								{$pagoX["glosa"]="Pago Total Arancel (CHEQUE) Boleta($ID_boleta)";}
								else
								{$pagoX["glosa"]="Pago Arancel (CHEQUE) Boleta($ID_boleta)";}	
								$pagoX["forma_pago"]="cheque";
								
								//si es distinto el cheque lo registro sino ya se registro totalidado matricula arancel, cuando se registro el pago de la matricula
								if($cheque_matricula_arancel)
								{
									if(DEBUG){ echo"un solo Cheque X Matricula y Arancel ya generado, utilizo datos de ese cheque<br>";}
									$id_cheque_new=$id_cheque_inicial;
									$aux_fecha_vence_cheque=$_SESSION["FINANZAS"]["fecha_vence_cheque_mat"];
			
								}
								else
								{
									if(DEBUG){ echo"Cheque individual X arancel a Generar<br>";}
									$cheque["glosa"]="Paga Arancel";
									$id_cheque_new=REGISTRA_CHEQUE($cheque);
									$aux_fecha_vence_cheque=$cheque["fecha_vence"];
								}
								$pagoX["cheque_fecha_vence"]=$aux_fecha_vence_cheque;
								$pagoX["id_cheque"]=$id_cheque_new;
								GENERA_PAGO($pagoX);
								
								unset($cheque);
								unset($pagoX);
							}
							break;			
					}
				}	
			}
			else
			{ if(DEBUG){echo"Falla Grabando Contrato<br>";} $error=1;}
			
			GRABA_DATO_ALUMNO($id_alumno);//modificado tiene debug dentro de funcion
			$conexion_mysqli->close();
			if(!DEBUG)
			{
				$_SESSION["FINANZAS"]["SAVE"]=true;
				$_SESSION["FINANZAS"]["id_contrato"]=$id_contrato;//agregado
				
				
				header("location: opciones_finales.php?error=$error");
			}	
	}
	else
	{
		echo"faltan pasos redirigir<br>o sesion ya guardada...<br>";
		echo'Click <a href="destructor_sesion_finanzas.php?url=HALL">Aqui</a> Para Volver al Menu';
	}

//**--------------------------------------------------------**//
function GRABA_CONTRATO($contrato)
{
	require("../../../funciones/conexion_v2.php");
	$error=true;
	if(DEBUG){ echo"<br><strong>___________________________________FUNCION GRABA_CONTRATO_______________________________________</strong><br>";}
	$aux_sostenedor=$contrato["sostenedor"];
	$arancel=$contrato["arancel"];
	$p_desc_contado=$contrato["p_desc_contado"];
	$user_activo=$_SESSION["USUARIO"]["id"];
	$fecha_actualX=date("Y-m-d H:i:s");
	
	
	$vigencia=$_SESSION["FINANZAS"]["vigencia_cuotas"];
	
	$saldo_a_favor=$contrato["saldo_a_favor"];
	$id_contrato_anterior=$contrato["id_contrato_anterior"];
	if(empty($id_contrato_anterior)){ $id_contrato_anterior=0;}
	$id_boleta_global=$contrato["id_boleta_global"];
	$nivel_alumno=$_SESSION["FINANZAS"]["nivel"];
	
	$excedente=$contrato["excedente_proximo_year"];
	if($excedente>0)
	{
		 $contrato["txt_beca"].='*Excedente Proximo Contrato:$ '.number_format($excedente,0,",",".").'.-*';
		  $_SESSION["FINANZAS"]["comentario_beca"]=$contrato["txt_beca"];//actualizo session
	}
	//-------------------------------------------------------------------//
	
	//------------------------------------------------------------------//
	
	switch($aux_sostenedor)
	{
		case"otro":
			$G_sostenedor=strtolower($contrato["sostenedor_nombre"]);
			break;
		case"alumno":
			$G_sostenedor="alumno";
			break;
		case"apoderado":
			$G_sostenedor="apoderado";
			break;		
	}
	
	
	$tabla="contratos2";
	$campos="id_alumno, id_carrera, jornada, nivel_alumno, sede, fecha_inicio, fecha_fin, yearIngresoCarrera, ano, semestre, numero_cuotas, arancel, saldo_a_favor, porcentaje_desc_contado, total, contado_paga, cheque_paga, linea_credito_paga, id_boleta_generada, txt_beca, totalBeneficiosEstudiantiles, opcion_pag_matricula, matricula_valor, matricula_a_pagar, sostenedor, cod_user, fecha_generacion, vigencia, id_contrato_previo, excedente";
	
	$valores="'$contrato[id_alumno]', '$contrato[id_carrera]', '$contrato[jornada]', '$nivel_alumno', '$contrato[sede_alumno]', '$contrato[fecha_inicio]','$contrato[fecha_fin]','$contrato[yearIngresoCarrera]', '$contrato[año_contrato]', '$contrato[semestre]', '$contrato[numero_cuotas]', '$arancel', '$saldo_a_favor', '$p_desc_contado', '$contrato[total]',  '$contrato[contado_paga]', '$contrato[cheque_paga]', '$contrato[linea_credito_paga]',  '$id_boleta_global', '$contrato[txt_beca]', '$contrato[totalBeneficiosEstudiantiles]',  '$contrato[opcion_pag_matricula]',  '$contrato[matricula_valor]', '$contrato[matricula_a_pagar]', '$G_sostenedor', '$user_activo', '$fecha_actualX', '$vigencia', '$id_contrato_anterior', '$excedente'";
	
	$consC="INSERT INTO $tabla ($campos) VALUES($valores)";
	
	if(DEBUG)
	{
		echo"<br><br><strong>CONTRATO:-></strong> $consC<br>";
		$id_contrato="5";
	}
	else
	{
		if($conexion_mysqli->query($consC))
		{
			$error=true;
			///para el caso que necesite el id contrato generado, opcion guardar en session  para posterior utilizacion
			$id_contrato=$conexion_mysqli->insert_id;
		}
		else
		{
			$info_error=$conexion_mysqli->error;
			$error=false;
			$id_contrato=0;
			
			echo'<script languaje="javascript"> alert("ERROR: Enviar a Elias \n \n'.$info_error.'\n \n'.$consC.'");</script>';	
			echo"GRABA_CONTRATO: ".$info_error;	
		}
	}	
	if(DEBUG){ echo "ID contrato -> $id_contrato<br><br>";}
	if(DEBUG){ echo"<br><strong>___________________________________FIN FUNCION GRABA_CONTRATO_______________________________________</strong><br>";}
	
	if(($id_contrato>0)and($contrato["totalBeneficiosEstudiantiles"]>0)){
		if(DEBUG){ echo"<b>Registrar Beneficios asignados</b><br>";}
		if(count($_SESSION["FINANZAS"]["beneficiosEstudiantiles"]>0)){
			foreach($_SESSION["FINANZAS"]["beneficiosEstudiantiles"] as $auxIdBeneficio =>$arrayValores){
			 $auxNombre=$arrayValores["nombre"];
			 $auxAporteValor=$arrayValores["aporteValor"];
			 $auxAportePorcentaje=$arrayValores["aportePorcentaje"];
			 $auxTipo=$arrayValores["tipo"];
			 
			 if($auxTipo=="porcentaje"){$totalizadoBeneficio=($arancel*$auxAportePorcentaje)/100;}
			 else{$totalizadoBeneficio=$auxAporteValor;}
			 
			 $cons_BE="INSERT INTO beneficiosEstudiantiles_asignaciones (id_alumno, id_contrato, id_beneficio, valor) VALUES ('$contrato[id_alumno]', '$id_contrato', '$auxIdBeneficio', '$totalizadoBeneficio')";
			 
			 if(DEBUG){ echo"---> $cons_BE<br>";}
			 else{ $conexion_mysqli->query($cons_BE)or die("Beneficio estudiantil ".$conexion_mysqli->error);}
			}
		}
		if(DEBUG){ echo"<br><br>";}
	}else{if(DEBUG){echo"<b>NO Registrar Beneficios asignados, no hay</b><br>";}}
	$conexion_mysqli->close();
	return $id_contrato;
}
//////////////////////////////////////////////////////////////////////////+
//-------------------GRABA LETRAS-INGRESO Xmatricula ---------------------+
//////////////////////////////////////////////////////////////////////////+
function GRABAR_PAG_MAT($datos, $boletas)
{
	//echo"En funcion<br>";
	require("../../../funciones/conexion_v2.php");
	$debug=DEBUG;
	if($debug){ echo"<br><strong>_________________________________FUNCION GRABAR_PAG_MAT______________________________________</strong></br>";}
	$opcion=$datos["opcion_pago"];
	$error=true;
	$sede=$datos["sede"];
	$fecha_actual=date("Y-m-d");
	$generar_boleta=true;
	$registrar_cheque=true;
	$n=1;//para numero de cuota
	$id_alumno=$datos["id_alumno"];
	$id_contrato=$datos["id_contrato"];
	$valor_matricula=$datos["matricula"];
	
	$chequeXmatricula_arancel=$datos["cheque_matricula_arancel"];
	$id_cheque_inicial=$datos["id_cheque_inicial"];
	$fecha_vencimiento_cheque_inicial=$datos["fecha_vence_cheque_inicial"];
	
	///-------------------------------------------------//
	if(ES_MATRICULA_NUEVA($id_alumno, $id_contrato))
	{ $por_concepto="matricula_nueva";}
	else{$por_concepto="matricula";}
	
	//-----------------------------------------------------///
	$user_activo=$_SESSION["USUARIO"]["id"];
		///////para campos agregados//////////////
		date_default_timezone_set('America/Santiago');//zona horaria
		$fecha_generacion=date("Y-m-d H:i:s");
		$ip=$_SERVER['REMOTE_ADDR'];
		///designando codigo item segun plan cuentas
		switch($sede)
		{
			case"Talca":
				$codigo_item="4101286";
				break;
			case"Linares":
				$codigo_item="4101294";
				break;
		}
	//////////////////////////////////////////
	
	if(DEBUG){ echo"OPCION pago de Matricula--> $opcion<br>";}
	switch ($opcion)
	{
		case "L_CREDITO":
			//echo"Paga Con Letra<br>";
			$campos="idalumn, id_contrato, numcuota, fechavenc, valor, deudaXletra, ano, semestre, fechemision, sede, tipo";
			
			$valores="'$id_alumno', '$id_contrato', '$n', '$datos[fechaV_lcredito]', '$valor_matricula', '$valor_matricula', '$datos[ano]', '$datos[semestre]', '$fecha_actual', '$sede', 'matricula'";
			$consIN="INSERT INTO letras ($campos) VALUES($valores)";
			break;
		case "CONTADO":
			//echo"paga al contado<br>";
			$numero_boletaX=$boletas["matricula"];
			$glosa="Pago total de Matricula con boleta (".$numero_boletaX.")";
			$campos="id_boleta, id_alumno, item, fechapago, valor, tipodoc, glosa, sede, movimiento, forma_pago, por_concepto, semestre, year, cod_user, fecha_generacion, ip";
			$valores="'$numero_boletaX', '$datos[id_alumno]', '$codigo_item', '$fecha_actual', '$datos[matricula]', 'boleta', '$glosa', '$sede', 'I', 'efectivo', '$por_concepto', '$datos[semestre]', '$datos[ano]', '$user_activo', '$fecha_generacion', '$ip'";
			$consIN="INSERT INTO pagos ($campos) VALUES($valores)";
			break;
		case "EXCEDENTE":
			//echo"paga al contado<br>";
			$numero_boletaX=$boletas["matricula"];
			$glosa="Pago total de Matricula , cubre diferencia con EXCEDENTE. boleta (".$numero_boletaX.")";
			$campos="id_boleta, id_alumno, item, fechapago, valor, tipodoc, glosa, sede, movimiento, forma_pago, por_concepto, semestre, year, cod_user, fecha_generacion, ip";
			$valores="'$numero_boletaX', '$datos[id_alumno]', '$codigo_item', '$fecha_actual', '$datos[matricula]', 'boleta', '$glosa', '$sede', 'I', 'efectivo', '$por_concepto', '$datos[semestre]', '$datos[ano]', '$user_activo', '$fecha_generacion', '$ip'";
			$consIN="INSERT INTO pagos ($campos) VALUES($valores)";
			break;	
			
		case "CHEQUE":
			//registro  boleta//
			$numero_boletaX=$boletas["matricula"];
			
			$glosa="Pago total de Matricula con boleta (".$numero_boletaX.")";
			
			$campos="id_boleta, id_alumno, item, fechapago, valor, tipodoc, glosa, sede, movimiento, forma_pago, fechaV_cheque, id_cheque, por_concepto, semestre, year, cod_user, fecha_generacion, ip";
			$valores="'$numero_boletaX', '$datos[id_alumno]', '$codigo_item', '$fecha_actual', '$datos[matricula]', 'boleta', '$glosa', '$sede', 'I', 'cheque', '$fecha_vencimiento_cheque_inicial', '$id_cheque_inicial', '$por_concepto', '$datos[semestre]', '$datos[ano]', '$user_activo', '$fecha_generacion', '$ip'";
			$consIN="INSERT INTO pagos ($campos) VALUES($valores)";
			break;		
	}
	
	if($debug){echo"<br><br>PAGO MATRICULA:-> $consIN<br><br>";}
	else
	{
		if($conexion_mysqli->query($consIN)){$error=true;}
		else
		{
			$error=false;
			echo "ERROR EN GRABAR_PAG_MAT <br>".$conexion_mysqli->error;
		}
	}
		if($debug){ echo"<br><strong>_________________________________FIN FUNCION GRABAR_PAG_MAT______________________________________</strong></br>";}
	$conexion_mysqli->close();	
	return $error;
}
//==============================================================//
//------------------------GENERAR CUOTAS-----------------------//
//============================================================//
function GENERAR_CUOTAS($cuotas)
{
	require("../../../funciones/conexion_v2.php");
	$debug=DEBUG;
	$id_alumno=$cuotas["id_alumno"];
	$id_contrato=$cuotas["id_contrato"];//agregado
	$numero_cuotas=$cuotas["cantidad_cuotas"];
	$dia_vence=$cuotas["dia_vence"];
	$mes_ini=$cuotas["mes_ini"];
	$semestre=$cuotas["semestre"];
	$sede=$cuotas["sede"];
	$total=$cuotas["total"];
	$year=$cuotas["year"];
	$year_estudio=$cuotas["year_estudio"];
	$fecha_actual=date("Y-m-d");
	$tipo_cuota="cuota";
	$meses_avance=$cuotas["meses_avance"];//agregado
	
	//echo"----> $meses_avance<br>";
	if($debug){echo"<br><br>";}
	
	$valor_cuota=round($total/$numero_cuotas);
	$deudaXcuota=$valor_cuota;
	$campos="idalumn, id_contrato, numcuota, fechavenc, valor, deudaXletra, ano, semestre, fechemision, sede, tipo";
	
	for($c=1;$c<=$numero_cuotas;$c++)
	{
		if(($dia_vence>28)and($mes_ini==2))
		{$vencimiento="$year-02-28";}
		else
		{
			if($mes_ini<10)
			{$mes_label="0".$mes_ini;}
			else{$mes_label=$mes_ini;}
			if($dia_vence<10)
			{$dia_vence_label="0".$dia_vence;}
			else{$dia_vence_label=$dia_vence;}
			$vencimiento="$year-$mes_label-$dia_vence_label";	
		}	
		
		$valores="'$id_alumno', '$id_contrato', '$c', '$vencimiento', '$valor_cuota', '$deudaXcuota', '$year_estudio', '$semestre', '$fecha_actual', '$sede', '$tipo_cuota'";//modifico el año por el año de estudio = al del contrato
		
		$cons="INSERT INTO letras ($campos) VALUES($valores)";
		if($debug)
		{echo"CUO->$cons<br>";}
		else
		{$conexion_mysqli->query($cons)or die("GENERA CUOTA ".$conexion_mysqli->error);}	
		
		////avance y condiciones para fechas
		$mes_ini+=$meses_avance;
		if($mes_ini>12)
		{
			$mes_ini=1;
			$year++;
		}
	}
	$conexion_mysqli->close();
}
//--
function GENERA_PAGO($pago)
{
	require("../../../funciones/conexion_v2.php");
	if(DEBUG){ echo"<br><strong>____________________________________INICIO FUNCION GENERA_PAGO__________________________________________</strong><br>";}
	$debug=DEBUG;
	$id_boleta=$pago["id_boleta"];
	$id_alumno=$pago["id_alumno"];
	$fecha_actual=date("Y-m-d");
	$valor=$pago["total"];
	$glosa=$pago["glosa"];
	$sede=$pago["sede"];
	$forma_pago=$pago["forma_pago"];
	
	if(isset($pago["cheque_fecha_vence"]))
	{$cheque_fecha_vence="'".$pago["cheque_fecha_vence"]."'";}
	else{$cheque_fecha_vence= "NULL";}
	
	if(isset($pago["id_cheque"]))
	{$id_cheque=$pago["id_cheque"];}
	else{$id_cheque=0;}
	$por_concepto=$pago["por_concepto"];
	$semestre=$pago["semestre"];
	$year=$pago["year"];
	$user_activo=$_SESSION["USUARIO"]["id"];
	///////para campos agregados//////////////
		date_default_timezone_set('America/Santiago');//zona horaria
		$fecha_generacion=date("Y-m-d H:i:s");
		$ip=$_SERVER['REMOTE_ADDR'];
		///designando codigo item segun pal cuentas
		switch($sede)
		{
			case"Talca":
				$codigo_item="4101286";
				break;
			case"Linares":
				$codigo_item="4101294";
				break;
		}
	//////////////////////////////////////////
	$campos="id_boleta, id_alumno, item, fechapago, valor, tipodoc, glosa, sede, movimiento, forma_pago, fechaV_cheque, id_cheque, por_concepto, semestre, year, cod_user, fecha_generacion, ip";
	$valores="'$id_boleta', '$id_alumno', '$codigo_item', '$fecha_actual', '$valor', 'boleta', '$glosa', '$sede', 'I', '$forma_pago', $cheque_fecha_vence, '$id_cheque', '$por_concepto', '$semestre', '$year', '$user_activo', '$fecha_generacion', '$ip'";
	$consIN="INSERT INTO pagos ($campos) VALUES($valores)";
	//echo $consIN;
	if($debug)
	{echo"<br>PAGO -> $consIN<br><br>";}
	else
	{$conexion_mysqli->query($consIN)or die("GENERA_PAGO ".$conexion_mysqli->error);}	
	if(DEBUG){ echo"<br><strong>____________________________________INICIO FUNCION GENERA_PAGO__________________________________________</strong><br>";}
	$conexion_mysqli->close();
}
//-------------------------------------------------//
function CAMBIAR_CONDICION_CONTRATOS_OLD($id_contrato, $id_alumno, $nueva_condicion)
{
	require("../../../funciones/conexion_v2.php");
	if(DEBUG){ echo"<br><strong>____________________________________INICIO FUNCION CAMBIAR_CONDICION_CONTRATOS_OLD__________________________________________</strong><br>";}
	$cons_upc="UPDATE contratos2 SET condicion='$nueva_condicion' WHERE id_alumno='$id_alumno' AND condicion='OK' AND id<>'$id_contrato'";
	if(DEBUG){echo"CAMBIO CONTRATOS OLD-> $cons_upc<br>";}
	else
	{$conexion_mysqli->query($cons_upc)or die("condicion contratos old".$conexion_mysqli->error);}
	if(DEBUG){ echo"<br><strong>____________________________________FIN FUNCION CAMBIAR_CONDICION_CONTRATOS_OLD__________________________________________</strong><br>";}
	$conexion_mysqli->close();
}
/////**********************************************//
function TOMA_RAMO_OBLIGATORIA($id_alumno, $id_carrera, $yearIngresoCarrera, $nivel_alumno, $semestre, $year, $sede_alumno, $jornada_alumno)
{
	require("../../../funciones/conexion_v2.php");
	if(DEBUG){ echo"<br><strong>____________________________________INICIO FUNCION TOMA_RAMO_OBLIGATORIA__________________________________________</strong><br>";}
	$borrar_tomas_ramo_previas=false;
	if($nivel_alumno==1)
	{
		if(DEBUG){ echo"Nivel de Alumno 1, Realizar Toma Ramo Automatica<br>";}
		$fecha_actual=date("Y-m-d");
		$condicion="ok";
		$id_usuario_robot=$_SESSION["USUARIO"]["id"];
		if(DEBUG){ echo"<br><strong>INICIO Funcion Toma Ramo Nivel 1</strong><br>";}
		
		////veo si hay registro previos
		 $cons="SELECT COUNT(id) FROM toma_ramos WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera' AND semestre='$semestre' AND year='$year'";
		 $sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		 $D=$sql->fetch_row();
		 $coincidencias=$D[0];
		 if(empty($coincidencias)){ $coincidencias=0;}
		 if(DEBUG){ echo"-->$cons<br>Num: $coincidencias<br>";}
		 
		 if($coincidencias>0)
		 { $hay_toma_previa=true;}
		 else
		 { $hay_toma_previa=false;}
		 $sql->free();
		 
		 /////////////////////
		 if($hay_toma_previa)
		 {
			 if($borrar_tomas_ramo_previas)
			 {
				 ///borro registros si existen
				  if(DEBUG){ echo"xxxxxxxxxxxxxELIMINA REGISTROSxxxxxxxxxx<br>";}
				 $cons_D="DELETE FROM toma_ramos WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera' AND semestre='$semestre' AND year='$year'";
				 
				 if(DEBUG){ echo"X-> $cons_D<br>";}
				 else{ $conexion_mysqli->query($cons_D)or die("ELIMINA REGISTROS ".$conexion_mysqli->error);}
				  if(DEBUG){ echo"xxxxxxxxxxxxxxxxFINxxxxxxxxxxxxxxx<br>";}
				  $grabar_toma_ramos=true;
			 }
			 else
			 { $grabar_toma_ramos=false; if(DEBUG){ echo"Hay Toma de RAMOS pREVIAS, NO Borrar, No Continuar<br>";}}
		 }
		 else
		 { $grabar_toma_ramos=true;}
		 //////////////////////////////////////////////
		 if($grabar_toma_ramos)
		 {
			 $cons_BR="SELECT * FROM mallas WHERE id_carrera='$id_carrera' AND ramo<>'' AND nivel='1' ORDER by num_posicion, cod";
			 $sql_BR=$conexion_mysqli->query($cons_BR)or die($conexion_mysqli->error);
			 $num_ramos=$sql_BR->num_rows;
			 
			 $campos="id_alumno, id_carrera, yearIngresoCarrera, jornada, nivel, semestre, year, cod_asignatura, condicion, fecha_generacion, cod_user";
			 if($num_ramos>0)
			 {
				 while($R=$sql_BR->fetch_assoc())
				 {
					 $cod_asignatura=$R["cod"];
					 $nom_asignatura=$R["ramo"];
					 
					 if(DEBUG){ echo"<strong>--->$cod_asignatura  -> $nom_asignatura</strong><br>";}
					 $valores="'$id_alumno', '$id_carrera', '$yearIngresoCarrera', '$jornada_alumno', '$nivel_alumno', '$semestre', '$year', '$cod_asignatura', '$condicion', '$fecha_actual', '$id_usuario_robot'";
							$cons_IN="INSERT INTO toma_ramos ($campos) VALUES($valores)";
							if(DEBUG){ echo"----> $cons_IN<br>";}
							else{ $conexion_mysqli->query($cons_IN)or die("$cons_IN <br>TOMA_RAMO_OBLIGATORIA:".$conexion_mysqli->error);}
				 }
				 
				 
			 }
			 else
			 {
				 if(DEBUG){ echo"No hay Ramos Cargados en Mallas...<br>";}
			 }
			 $sql_BR->free();
		 }
	}
	else
	{if(DEBUG){ echo"Nivel Mayor a 1, Realizar Toma de Ramos Manual<br>";}}
	if(DEBUG){ echo"<br><strong>____________________________________FIN FUNCION TOMA_RAMO_OBLIGATORIA__________________________________________</strong><br>";}
	$conexion_mysqli->close();
}

function GRABA_DATO_ALUMNO($id_alumno)
{
	require("../../../funciones/conexion_v2.php");
	if(DEBUG){ echo"<br><strong>____________________________________INICIO FUNCION GRABA_DATO_ALUMNO__________________________________________</strong><br>";}
	$nivel=$_SESSION["FINANZAS"]["nivel"];
	$grupo=$_SESSION["FINANZAS"]["grupo"];
	$id_carrera=$_SESSION["FINANZAS"]["id_carrera"];
	$carrera=$_SESSION["FINANZAS"]["carrera_alumno"];
	$jornada=$_SESSION["FINANZAS"]["jornada"];
	$yearIngresoCarrera=$_SESSION["FINANZAS"]["ingresoCarrera"];
	$sede=$_SESSION["FINANZAS"]["lugar_contrato"];;
	
	//$comentario_beca=ucwords(strtolower($_SESSION["FINANZAS"]["comentario_beca"]));
	$cons_UP="UPDATE alumno SET id_carrera='$id_carrera', carrera='$carrera', grupo='$grupo', nivel='$nivel', situacion='V', jornada='$jornada', ingreso='$yearIngresoCarrera', sede='$sede' WHERE id='$id_alumno' LIMIT 1";
	if(DEBUG){ echo"<br><strong>Graba datos Alumno -></strong><br> $cons_UP<br>";}
	else
	{$conexion_mysqli->query($cons_UP)or die("GRABA dato alumno ".$conexion_mysqli->error);}
	if(DEBUG){ echo"<br><strong>____________________________________FIN FUNCION GRABA_DATO_ALUMNO__________________________________________</strong><br>";}
	$conexion_mysqli->close();
}
?>