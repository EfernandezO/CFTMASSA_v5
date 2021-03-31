<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Impresion_Y_Gestion_de_contratos_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if(($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])and($_GET))
{
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$rut_alumno=$_SESSION["SELECTOR_ALUMNO"]["rut"];
	$carrera_alumno=$_SESSION["SELECTOR_ALUMNO"]["carrera"];
	$sede_alumno=$_SESSION["SELECTOR_ALUMNO"]["sede"];
	
	$contrato=$_GET["tipo_contrato"];
	$year_estudio=$_GET["year"];
	$semestre_estudiox=$_GET["semestre"];
	$id_contrato=$_GET["id_contrato"];
	
	require("../../../funciones/class_ALUMNO.php");
	$ALUMNO=new ALUMNO($id_alumno);
	
	////datos alumno
	require("../../../funciones/conexion_v2.php");
	$cons_alu="SELECT * FROM alumno WHERE id='$id_alumno' LIMIT 1";
	if(DEBUG){echo"---> $cons_alu<br><br>";}	
	$sql_alu=$conexion_mysqli->query($cons_alu)or die("Alumno ".$conexion_mysqli->error());
	$DA=$sql_alu->fetch_assoc();
	
	//$id_alumno=$DA["id"];
	$_SESSION["CONTRATO_OLD"]["id_alumno"]=$id_alumno;
	$_SESSION["CONTRATO_OLD"]["rut"]=$ALUMNO->getRut();
	$_SESSION["CONTRATO_OLD"]["nombre"]=$ALUMNO->getNombre();
	$_SESSION["CONTRATO_OLD"]["apellido_P"]=$ALUMNO->getApellido_P();
	$_SESSION["CONTRATO_OLD"]["apellido_M"]=$ALUMNO->getApellido_M();
	$_SESSION["CONTRATO_OLD"]["apellido_old"]=$DA["apellido"];//agregar
	$_SESSION["CONTRATO_OLD"]["direccion"]=$ALUMNO->getDireccion();
	$_SESSION["CONTRATO_OLD"]["ciudad"]=$ALUMNO->getCiudad();
	$_SESSION["CONTRATO_OLD"]["fono"]=$ALUMNO->getFono();
	
	
	$_SESSION["CONTRATO_OLD"]["rut_apo"]=$DA["rut_apoderado"];
	$_SESSION["CONTRATO_OLD"]["nombreC_apo"]=$DA["apoderado"];
	$_SESSION["CONTRATO_OLD"]["direccion_apo"]=$DA["direccion_apoderado"];
	$_SESSION["CONTRATO_OLD"]["ciudad_apo"]=$DA["ciudad_apoderado"];
	
	$sql_alu->free();
	
	////datos contrato
	$cons_contra="SELECT * FROM contratos2 WHERE id='$id_contrato' AND id_alumno='$id_alumno' LIMIT 1";
	if(DEBUG){echo"----> $cons_contra <br><br>";}	
	
	$sql_contra=$conexion_mysqli->query($cons_contra)or die("contrato ".$conexion_mysqli->error);
	$num_contrato=$sql_contra->num_rows;
	
	if($num_contrato==1){$continuar=true;}
	else{$continuar=false;}
	
	if($continuar)
	{
		$DC=$sql_contra->fetch_assoc();
		
		$_SESSION["CONTRATO_OLD"]["semestre"]=$DC["semestre"];
		$_SESSION["CONTRATO_OLD"]["year_estudio"]=$DC["ano"];
		$_SESSION["CONTRATO_OLD"]["ano"]=$DC["ano"];
		$_SESSION["CONTRATO_OLD"]["sede_alumno"]=$DC["sede"];
		$_SESSION["CONTRATO_OLD"]["jornada"]=$DC["jornada"];
		$_SESSION["CONTRATO_OLD"]["id_carrera"]=$DC["id_carrera"];
		$_SESSION["CONTRATO_OLD"]["yearIngresoCarrera"]=$DC["yearIngresoCarrera"];
		
		
		$_SESSION["CONTRATO_OLD"]["continuar"]=true;
		$_SESSION["CONTRATO_OLD"]["id_contrato"]=$id_contrato;
		$_SESSION["CONTRATO_OLD"]["fecha_inicio"]=$DC["fecha_inicio"];
		$_SESSION["CONTRATO_OLD"]["fecha_fin"]=$DC["fecha_fin"];
		$_SESSION["CONTRATO_OLD"]["numero_cuotas"]=$DC["numero_cuotas"];
		$_SESSION["CONTRATO_OLD"]["arancel"]=$DC["arancel"];
		$_SESSION["CONTRATO_OLD"]["saldo_a_favor"]=$DC["saldo_a_favor"];//agregado
		$_SESSION["CONTRATO_OLD"]["total"]=$DC["total"];
		$_SESSION["CONTRATO_OLD"]["nivel"]=$DC["nivel_alumno"];//nivel de alumno en que realiza el contrarto
		$_SESSION["CONTRATO_OLD"]["nivel_2"]=$DC["nivel_alumno_2"];//nivel_2 de alumno en que realiza el contrarto
		if(($_SESSION["CONTRATO_OLD"]["nivel_2"]==0)or(empty($_SESSION["CONTRATO_OLD"]["nivel_2"])))
		{ $_SESSION["CONTRATO_OLD"]["nivel_2"]="aun no definido";}
		
		
		$_SESSION["CONTRATO_OLD"]["contado_paga"]=$DC["contado_paga"];
		$_SESSION["CONTRATO_OLD"]["cheque_paga"]=$DC["cheque_paga"];
		$_SESSION["CONTRATO_OLD"]["linea_credito_paga"]=$DC["linea_credito_paga"];
		
		$_SESSION["CONTRATO_OLD"]["id_boleta_generada"]=$DC["id_boleta_generada"];
		
		$_SESSION["CONTRATO_OLD"]["txt_beca"]=$DC["txt_beca"];
		$_SESSION["CONTRATO_OLD"]["cantidad_beca"]=$DC["cantidad_beca"];
		$_SESSION["CONTRATO_OLD"]["porcentaje_beca"]=$DC["porcentaje_beca"];
		$_SESSION["CONTRATO_OLD"]["opcion_pago_mat"]=$DC["opcion_pag_matricula"];
		$_SESSION["CONTRATO_OLD"]["valor_matricula"]=$DC["matricula_valor"];
		$_SESSION["CONTRATO_OLD"]["matricula_a_pagar"]=$DC["matricula_a_pagar"];
		$_SESSION["CONTRATO_OLD"]["sostenedor"]=$DC["sostenedor"];
		$_SESSION["CONTRATO_OLD"]["vigencia"]=$DC["vigencia"];
		
		/////beca nuevo milenio
		$_SESSION["CONTRATO_OLD"]["beca_nuevo_milenio"]=$DC["beca_nuevo_milenio"];
		$_SESSION["CONTRATO_OLD"]["aporte_beca_nuevo_milenio"]=$DC["aporte_beca_nuevo_milenio"];
		////beca excelencia
		$_SESSION["CONTRATO_OLD"]["beca_excelencia"]=$DC["beca_excelencia"];
		$_SESSION["CONTRATO_OLD"]["aporte_beca_excelencia"]=$DC["aporte_beca_excelencia"];
		
		///////////excedente
		$_SESSION["CONTRATO_OLD"]["excedente_proximo_contrato"]=$DC["excedente"];
		
		///////////////BUSCO pago///////////////
		$cons_p="SELECT * from pagos WHERE id_alumno='$id_alumno' AND por_concepto='matricula' AND year='$year_estudio' ORDER by idpago desc LIMIT 1";
		//echo"$cons_p<br>";
		$sql_p=$conexion_mysqli->query($cons_p)or die("pago ".$conexion_mysqli->error);
		$DPA=$sql_p->fetch_assoc();
		if(isset($DPA["id_boleta"])){$id_boleta=$DPA["id_boleta"];}
		else{$id_boleta=0;}
		
		//echo"---> $id_boleta <br>";
		$_SESSION["CONTRATO_OLD"]["id_boleta"]=$id_boleta;
		$sql_p->free();
		/////////////////////////busco boleta
		$aux_folio="";
		$aux_fecha="";
		$aux_sede="";
		if($id_boleta>0){
			$cons_bo="SELECT fecha, folio, sede FROM boleta WHERE id='$id_boleta'";
			$sql_bo=$conexion_mysqli->query($cons_bo)or die("boleta ".$conexion_mysqli->error);
			$DBA=$sql_bo->fetch_assoc();
			$aux_folio=$DBA["folio"];
			$aux_fecha=$DBA["fecha"];
			$aux_sede=$DBA["sede"];
			
			$sql_bo->free();
		}
		
		//echo"---> $aux_folio<br>";
		$_SESSION["CONTRATO_OLD"]["folio_boleta"]=$aux_folio;
		$_SESSION["CONTRATO_OLD"]["fecha_boleta"]=$aux_fecha;
		$_SESSION["CONTRATO_OLD"]["sede_boleta"]=$aux_sede;
		
		///////////////////////////////////////////
		
	$sql_contra->free();
		
		//////si pago matricula con mensualidad
	if($_SESSION["CONTRATO_OLD"]["opcion_pago_mat"]=="L_CREDITO")	
	{
		$cons_VM="SELECT fechavenc FROM letras WHERE id_contrato='$id_contrato' AND tipo='matricula' ORDER by fechavenc LIMIT 1";
		$sql_VM=$conexion_mysqli->query($cons_VM)or die("fecha vencimiento".$conexion_mysqli->error);
		$DVM=$sql_VM->fetch_assoc();
		$_SESSION["CONTRATO_OLD"]["fecha_vence_lcredito_mat"]=$DVM["fechavenc"];
		$sql_VM->free();
	}
	
		
		$conexion_mysqli->close();
		if(DEBUG){var_export($_SESSION["CONTRATO_OLD"]);}	
		//echo"---> $contrato<br>";
		switch($contrato)
		{
			case "academico":
				$url="contratos/contratoY.php";
				break;
			case "credito":
				$url="contratos/contrato_credito_V2X.php";
				break;	
		}
		header("location: $url");
	}
	else
	{
		echo"sin contrato";
	}	
}
else
{
	header("location: index.php");
}
?>