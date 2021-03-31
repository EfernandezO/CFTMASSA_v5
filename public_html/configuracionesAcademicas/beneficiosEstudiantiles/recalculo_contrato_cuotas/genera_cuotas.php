<?php
//-----------------------------------------//
	require("../../../Edicion_carreras/OKALIS/seguridad.php");
	require("../../../Edicion_carreras/OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	

 $acceso=false;
 $comparador=md5("recalculo_cuota".date("Y-m-d"));
 $validador=$_POST["validador"];
 if($validador==$comparador)
 { $acceso=true;}

if($_SESSION["REASIGNAR"]["verificador"])
{
if(($_POST)and($acceso))
{
	if(!DEBUG){$_SESSION["REASIGNAR"]["verificador"]=false;}
	 include ("../../../funciones/conexion.php");
	 include ("../../../funciones/funciones_sistema.php");
	 if(DEBUG){ var_export($_POST);}
	 if(DEBUG){echo"<br><br>===================================================================================<br>";}
		
			$id_contratoX=$_POST["id_contratoX"];
			$vigencia=$_POST["vigencia"];
			$arancel_1=$_POST["arancel_1"];
			$arancel_2=$_POST["arancel_2"];
			$saldo_a_favor=$_POST["saldo_a_favor"];
			$aporte_BNM=$_POST["aporte_BNM"];
			$aporte_BET=$_POST["aporte_BET"];
			if($aporte_BNM>0){ $label_BNM="completa";}
			else{ $label_BNM="sin_beca";}
			$aporte_BET=$_POST["aporte_BET"];
			if($aporte_BET>0){ $label_BET="completa";}
			else{ $label_BET="sin_beca";}
			$desc_porcentaje=$_POST["desc_porcentaje"];
			$desc_valor=$_POST["desc_valor"];
			$porcentaje_desc_contado=$_POST["porcentaje_desc_contado"];
			$linea_credito_cantidad=$_POST["linea_credito_cantidad"];
			$linea_credito_cantidad_cuotas=$_POST["linea_credito_cantidad_cuotas"];
			$linea_credito_mes_ini=$_POST["linea_credito_mes_ini"];
			$meses_avance=$_POST["meses_avance"];
			$linea_credito_dia_vencimiento=$_POST["linea_credito_dia_vencimiento"];
			$linea_credito_year=$_POST["linea_credito_year"];
	
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$sede=$_SESSION["SELECTOR_ALUMNO"]["sede"];
	$nivel_alumno=$_SESSION["SELECTOR_ALUMNO"]["nivel"];
	$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	$tipo="cuota";
	$fecha_actual=date("Y-m-d");
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	 ////Generando nuevo contrato
	 //busco datos del antiguo
	 $cons_C="SELECT * FROM contratos2 WHERE id='$id_contratoX' LIMIT 1";
		 $sql_c=mysql_query($cons_C)or die("contrato_old".mysql_error());
		 $DC=mysql_fetch_assoc($sql_c);
		 
		 $CO_fecha_ini=$DC["fecha_inicio"];
		 $CO_fecha_ini=date("Y-m-d");//fecha actual
		 $CO_fecha_fin=$DC["fecha_fin"];
		 $CO_year=$DC["ano"];
		 $CO_nivel_alumno=$DC["nivel_alumno"];
		 
		  $CO_fecha_fin=$CO_year."-12-30";//fecha final = fin de año
		 $CO_semestre=$DC["semestre"];
		 $CO_opcion_paga_matricula=$DC["opcion_pag_matricula"];
		 $CO_matricula_valor=$DC["matricula_valor"];
		 $CO_matricula_a_pagar=$DC["matricula_a_pagar"];
		 $CO_sostenedor=$DC["sostenedor"];
		 $CO_id_boleta_generada=$DC["id_boleta_generada"];
	 mysql_free_result($sql_c);
	 
	 
	 $condicion_contrato="OK";
	 $excedente=0;
	 
	switch($vigencia)
	{
		case"semestral":
			if($CO_semestre==1)
			{ $arancel=$arancel_1;}
			else
			{ $arancel=$arancel_2;}
			
			if($CO_semestre==1)
			{
				$fecha_inicio=$fecha_actual;
				$fecha_fin=date("Y")."-08-30";
			}
			else
			{
				$fecha_inicio=$fecha_actual;
				$fecha_fin=date("Y")."-12-30";
			}
			break;
		case"anual":
			$arancel=($arancel_1+$arancel_2);
			
			$fecha_inicio=$fecha_actual;
			$fecha_fin=date("Y")."-12-30";
			break;	
	}
	 ///////////////////////////////////////////////
	  $comentario_beca_general="";
		 $cons_B="SELECT * FROM beca_asignaciones WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND estado='por_asignar' AND semestre='$CO_semestre' AND year='$CO_year'";
	$sql_B=mysql_query($cons_B)or die("asignaciones".mysql_error());
	$num_becas_asignadas=mysql_num_rows($sql_B);
	$aux_num_beca=0;
	$ARRAY_ID_ASIGNACION_BECA=array();
	if($num_becas_asignadas>0)
	{
		while($B=mysql_fetch_assoc($sql_B))
		{
			$B_id_asignacion=$B["id"];
			$ARRAY_ID_ASIGNACION_BECA[$aux_num_beca]=$B_id_asignacion;
			
			$aux_num_beca++;
			$B_id_beca=$B["id_beca"];
			$B_valor=$B["valor"];
			$B_glosa=$B["glosa"];

			///////////////////////////////////////////
				$cons_B2="SELECT * FROM becas WHERE id='$B_id_beca' LIMIT 1";
				$sql_B2=mysql_query($cons_B2)or die("beca".mysql_error());
					$DB=mysql_fetch_assoc($sql_B2);
					$B_nombre=$DB["beca_nombre"];
					$B_vigencia=$DB["vigencia"];
					$B_tipo_aporte=$DB["beca_tipo_aporte"];
				mysql_free_result($sql_B2);	
			///////////////////////////////////////////
			if($B_tipo_aporte=="valor")
			{ $valor_label="$".number_format($B_valor,0,",",".");}
			else
			{ $valor_label=number_format($B_valor,1,",",".")." %";}
			
			$comentario_beca_general.=$aux_num_beca.".- $B_nombre ".$valor_label." ";
			
		}
	}
		 ///////////////////////////////////////////////////////////////////////////////////////
		 
	 ////////////////////////////////////////////////////
	 
	 $total_a_pagar=$linea_credito_cantidad;
	 
	 ////////////////////////////
	 $reasignado="si";
	 //genero nuevo
	 $campos1="id_alumno, id_carrera, nivel_alumno, sede, fecha_inicio, fecha_fin, ano, semestre, numero_cuotas, arancel, saldo_a_favor, porcentaje_desc_contado, total, contado_paga, cheque_paga, id_boleta_generada, linea_credito_paga, cantidad_beca, porcentaje_beca, txt_beca, beca_nuevo_milenio, aporte_beca_nuevo_milenio, beca_excelencia, aporte_beca_excelencia, opcion_pag_matricula, matricula_valor, matricula_a_pagar, sostenedor, cod_user, fecha_generacion, vigencia, condicion, excedente, id_contrato_previo, reasignado";
	 
	 $valores1="'$id_alumno', '$id_carrera', '$nivel_alumno', '$sede', '$fecha_inicio', '$fecha_fin', '$CO_year', '$CO_semestre', '$linea_credito_cantidad_cuotas', '$arancel', '$saldo_a_favor', '$porcentaje_desc_contado', '$linea_credito_cantidad', '0', '0', '$CO_id_boleta_generada', '$linea_credito_cantidad', '$desc_valor', '$desc_porcentaje', '$comentario_beca_general', '$label_BNM', '$aporte_BNM', '$label_BET', '$aporte_BET', '$CO_opcion_paga_matricula', '$CO_matricula_valor', '$CO_matricula_a_pagar', '$CO_sostenedor', '$id_usuario_actual', '$fecha_actual', '$vigencia', '$condicion_contrato', '$excedente', '$id_contratoX', '$reasignado'";
	 
	 $cons_contrato="INSERT INTO contratos2 ($campos1)VALUES($valores1)";
	 
	 $cons_up_old_contrato="UPDATE contratos2 SET condicion='inactivo' WHERE id='$id_contratoX' LIMIT 1";
	 if(DEBUG)
	 {
	 	echo"<br>C-> $cons_contrato<br><br>";
		
		echo"UP-> $cons_up_old_contrato<br>";
		$id_contrato_new=1;
		$continuar=true;
	 }
	 else
	 {
	 	mysql_query($cons_contrato)or die(mysql_error());
		$id_contrato_new=mysql_insert_id();
		if($id_contrato_new>0)
		{
			//cambio de estado Contrato_anterio
			mysql_query($cons_up_old_contrato)or die(mysql_error());
			$continuar=true;
		}
		else
		{$continuar=false;}
	 }
	 if($continuar)
	 {
	 			ELIMINA_CUOTAS_OLD($id_contratoX, $id_alumno);//elimino cuotas antiguas
	 //////////ARMANDO CUOTAS/////////////////////////////////////
	 			$dia_vence=$linea_credito_dia_vencimiento;
				$mes=$linea_credito_mes_ini;
				$año=$linea_credito_year;
				$valor_cuota=round($linea_credito_cantidad/$linea_credito_cantidad_cuotas);
				
				for($c=1;$c<=$linea_credito_cantidad_cuotas;$c++)
				{
					if(($dia_vence>28)and($mes==2))
					{$vencimiento="28/02/$año";}
					else
					{
						if($mes<10)
						{$mes_label="0".$mes;}
						else{$mes_label=$mes;}
						if($dia_vence<10)
						{$dia_vence_label="0".$dia_vence;}
						else{$dia_vence_label=$dia_vence;}
						$vencimiento="$año-$mes_label-$dia_vence_label";	
					}	
					////avance y condiciones para fechas
					$mes+=$meses_avance;
					if($mes>12)
					{
						$mes-=12;//modificado
						$año++;
					}
					//////////////////////////////////
					///armado de consulta
					$campos="idalumn, id_contrato, numcuota, fechavenc, valor, deudaXletra, ano, semestre, fechemision, sede, tipo";
					$valores="'$id_alumno', '$id_contrato_new', '$c', '$vencimiento', '$valor_cuota', '$valor_cuota', '$CO_year', '$CO_semestre', '$fecha_actual', '$sede', '$tipo'";
					$cons_in_c="INSERT INTO letras ($campos) VALUES ($valores)";
					if(DEBUG){echo "$cons_in_c<br>";}
					else
					{mysql_query($cons_in_c)or die("cuotas ".mysql_error());}
				}
				CAMBIA_SITUACION_FINANCIERA_ALUMNO($id_alumno);//dejo al alumno "V"
				
				
				///////////////////////////
				//actualiza asignacion de becas
				///////////////////////////////////////
				 if($num_becas_asignadas>0)
				 {
					 foreach($ARRAY_ID_ASIGNACION_BECA as $indice => $aux_id_asignacion)
					 {
						$cons_asignacion_beca="UPDATE beca_asignaciones SET estado='asignada', fecha_asignacion='$fecha_actual', cod_user_asignador='$id_usuario_actual', id_contrato='$id_contrato_new' WHERE id='$aux_id_asignacion' AND id_alumno='$id_alumno' AND id_carrera='$id_carrera' LIMIT 1";
					if(DEBUG){ echo"----->$cons_asignacion_beca<br>";}
					else{ mysql_query($cons_asignacion_beca)or die("asignacion ".mysql_error());}
					 }
				 }
				 //////////-------------------------------------------------////
				$error=0;
	 ///////////////////////////////////////////////
	 }//fin si continuar
	 else
	 {
	 	echo"IMPOSIBLE CONTINUAR FALLA INSERTANDO CONTRATO<br>";
		$error=1;
	 }
		 /////////////
		 include("../../../funciones/VX.php");
		 
		 $descripcion="Recalculo de Contrato Por Asignacion de Becas (genera $linea_credito_cantidad_cuotas cuotas)";
		 REGISTRO_EVENTO_ALUMNO($id_alumno, "Notificacion",$descripcion);
		 $evento="Recalculo de Contrato id alumno: $id_alumno id carrera: $id_carrera (genera $linea_credito_cantidad_cuotas cuotas)";
		 REGISTRA_EVENTO($evento);
		 ///////////////////////////
		 @mysql_close($conexion);
		 $url="msj_final.php?error=$error&tipo=cuota";
		 
		 if(DEBUG){ echo"<br>URL: $url<br>";}
		 else
		 { header("location: $url");}
}
else
{ 
	if(DEBUG){ echo"Sin Acceso Redirijir<br>";}
	else{header("location: asignar_beca_1.php");}
}	
}
else
{ echo"Accion Ya Realizada...";}
///////////////////////////////-----------------------------------///////////////////////////////////
?>