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
		include("../../../funciones/funciones_sistema.php");
		 if(DEBUG){ var_export($_POST);}
		 if(DEBUG){echo"<br><br>===================================================================================<br>";}
			$id_contratoX=$_POST["id_contratoX"];
		
			$validador=$_POST["validador"];
			$id_contratoX=$_POST["id_contratoX"];
			$vigencia=$_POST["vigencia"];
			$arancel_1=$_POST["arancel_1"];
			$arancel_2=$_POST["arancel_2"];
			$saldo_a_favor=$_POST["saldo_a_favor"];
			$aporte_BNM=$_POST["aporte_BNM"];
			if($aporte_BNM>0){ $label_BNM="completa";}
			else{ $label_BNM="sin_beca";}
			$aporte_BET=$_POST["aporte_BET"];
			if($aporte_BET>0){ $label_BET="completa";}
			else{ $label_BET="sin_beca";}
			$desc_porcentaje=$_POST["desc_porcentaje"];
			$desc_valor=$_POST["desc_valor"];
			$excedente_valor=$_POST["excedente_valor"];
			$porcentaje_desc_contado=$_POST["porcentaje_desc_contado"];
		
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
		 $CO_fecha_ini=date("Y-m-d");
		 $CO_fecha_fin=$DC["fecha_fin"];
		 $CO_year=$DC["ano"];
		 $CO_fecha_fin=$CO_year."-12-30";
		 $CO_semestre=$DC["semestre"];
		 $CO_opcion_paga_matricula=$DC["opcion_pag_matricula"];
		 $CO_matricula_a_pagar=$DC["matricula_a_pagar"];
		 $CO_matricula_valor=$DC["matricula_valor"];
		 $CO_sostenedor=$DC["sostenedor"];
		 $CO_id_boleta_generada=$DC["id_boleta_generada"];
		 $CO_nivel_alumno=$DC["nivel_alumno"];
		 $CO_contado_paga=$DC["contado_paga"];
		 $CO_cheque_paga=$DC["cheque_paga"];
		 mysql_free_result($sql_c);
		 $condicion_contrato="OK";
		 $reasignado="si";
		 
		 switch($vigencia)
		 {
			 case"semestral":
				$total_a_pagar=($arancel_1-$saldo_a_favor);
				if($CO_semestre==1)
				{$arancel_contrato=$arancel_1;}
				else{$arancel_contrato=$arancel_2;}
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
				$total_a_pagar=(($arancel_1+$arancel_2)-$saldo_a_favor);
				$arancel_contrato=($arancel_1+$arancel_2);
				
				$fecha_inicio=$fecha_actual;
				$fecha_fin=date("Y")."-12-30";
				break;
		 }
		 ////////////////////////////////////////////////////////////////////////////////
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
		 $comentario_beca_general.=($aux_num_beca+1).".- Excedente Proximo Contrato: $ ".number_format($excedente_valor,0,",",".");
		 
		 
		 
		 
		 //genero nuevo
		 $campos1="id_alumno, id_carrera, nivel_alumno, sede, fecha_inicio, fecha_fin, ano, semestre, numero_cuotas, arancel, saldo_a_favor, porcentaje_desc_contado, total, contado_paga, cheque_paga, id_boleta_generada, linea_credito_paga, cantidad_beca, porcentaje_beca, txt_beca, beca_nuevo_milenio, aporte_beca_nuevo_milenio, beca_excelencia, aporte_beca_excelencia, opcion_pag_matricula, matricula_valor, matricula_a_pagar, sostenedor, cod_user, fecha_generacion, vigencia, condicion, excedente, id_contrato_previo, reasignado";
		 
		 $valores1="'$id_alumno', '$id_carrera', '$nivel_alumno', '$sede', '$fecha_inicio', '$fecha_fin', '$CO_year', '$CO_semestre', '0', '$arancel_contrato', '$saldo_a_favor', '$porcentaje_desc_contado', '0', '0', '0', '$CO_id_boleta_generada', '0', '$desc_valor', '$desc_porcentaje', '$comentario_beca_general', '$label_BNM', '$aporte_BNM', '$label_BET', '$aporte_BET', '$CO_opcion_paga_matricula', '$CO_matricula_valor', '$CO_matricula_a_pagar', '$CO_sostenedor', '$id_usuario_actual', '$fecha_actual', '$vigencia', '$condicion_contrato', '$excedente_valor', '$id_contratoX', '$reasignado'";
		 
		 $cons_contrato="INSERT INTO contratos2 ($campos1)VALUES($valores1)";
		 
		 if(DEBUG){ echo"--->$cons_contrato<br>"; $contrato_insertado=true; $id_contrato_new="debug";}
		 else
		 {
			 if(mysql_query($cons_contrato))
			 { $contrato_insertado=true; $id_contrato_new=mysql_insert_id();}
			 else
			 { $contrato_insertado=false; $id_contrato_new="x";}
		 }
		 
		 /////////////////////////////////////////////////////////////////////////////////////////////////////
		 $cons_up_old_contrato="UPDATE contratos2 SET condicion='inactivo' WHERE id='$id_contratoX' LIMIT 1";
		 if($contrato_insertado)
		 {
			 if(DEBUG){ echo"---->$cons_up_old_contrato<br>"; $contrato_old_inactivo=true;}
			 else
			 {
				 if(mysql_query($cons_up_old_contrato))
				 { $contrato_old_inactivo=true;}
				 else
				 { $contrato_old_inactivo=false;}
			 }
		 }
		 
		 if(($contrato_insertado)and($contrato_old_inactivo))
		 {
			 ELIMINA_CUOTAS_OLD($id_contratoX, $id_alumno);
			 CAMBIA_SITUACION_FINANCIERA_ALUMNO($id_alumno);
			 $error=0;
			 
			 if($num_becas_asignadas>0)
			 {
				 foreach($ARRAY_ID_ASIGNACION_BECA as $indice => $aux_id_asignacion)
				 {
					$cons_asignacion_beca="UPDATE beca_asignaciones SET estado='asignada', fecha_asignacion='$fecha_actual', cod_user_asignador='$id_usuario_actual', id_contrato='$id_contrato_new' WHERE id='$aux_id_asignacion' AND id_alumno='$id_alumno' AND id_carrera='$id_carrera' LIMIT 1";
				if(DEBUG){ echo"----->$cons_asignacion_beca<br>";}
				else{ mysql_query($cons_asignacion_beca)or die("asignacion ".mysql_error());}
				 }
			 }
		 }
		 else
		 {$error=1;}
		 /////////////
		 include("../../../funciones/VX.php");
		 
		 $descripcion="Recalculo de Contrato Por Asignacion de Becas";
		 $evento="Recalculo de Contrato id alumno: $id_alumno id carrera: $id_carrera";
		 if($excedente_valor>0){ $descripcion.=" (con excedentes $excedente_valor)"; $evento.=" (con excedente $excedente_valor)"; $tipo_operacion="excedente";}
		 else{ $tipo_operacion="sin_excedente";}
		 REGISTRO_EVENTO_ALUMNO($id_alumno, "Notificacion",$descripcion);
		 REGISTRA_EVENTO($evento);
		 ///////////////////////////
	$url="msj_final.php?error=$error&tipo=$tipo_operacion";
	if(DEBUG){ echo"URL: $url";}
	else{ header("location: $url");}
		
	}
	else
	{ 
		if(DEBUG){ echo"Sin Acceso Redirijir<br>";}
		else{header("location: recalculo_contrato_1.php");}
	}
}
else
{echo"Accion Ya Realizada...";}
?>