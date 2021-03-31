<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", true);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumno->asignacion de Becas V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
 $acceso=false;
 $comparador=md5("reasignacion_c".date("Y-m-d"));
 $validador=$_POST["validador"];
 if($validador==$comparador){ $acceso=true;}
if($_SESSION["REASIGNAR"]["verificador"])
{
if(($_POST)and($acceso))
{
	if(!DEBUG){$_SESSION["REASIGNAR"]["verificador"]=false;}
	 require("../../../funciones/conexion_v2.php");
	 if(DEBUG){ var_dump($_POST);}
	 if(DEBUG){echo"<br><br>===================================================================================<br>";}
	 
	 
	$vigencia_contrato_manual=$_POST["vigencia_contrato_manual"];
	
	if(DEBUG){ echo"VIGENCIA MANUAL $vigencia_contrato_manual<br>";}
	$id_contratoX=$_POST["id_contratoX"];
	$linea_credito_cantidad=$_POST["linea_credito_cantidad"];
	$linea_credito_cantidad_cuotas=$_POST["linea_credito_cantidad_cuotas"];
	$linea_credito_mes_ini=$_POST["linea_credito_mes_ini"];
	$meses_avance=$_POST["meses_avance"];
	$linea_credito_dia_vencimiento=$_POST["linea_credito_dia_vencimiento"];
	$linea_credito_year=$_POST["linea_credito_year"];
	
	$arancel_anual=$_POST["arancel_anual"];
	$porcentaje_becaX=$_POST["porcentaje_beca_old"];
	
	$saldo_a_favorX=$_POST["saldo_a_favor"];
	$cantidad_descX=$_POST["cantidad_desc"];
	if(empty($cantidad_descX)){ $cantidad_descX=0;}
	
	$aporte_beca_nuevo_milenio=$_POST["aporte_beca"];
	
	$aporte_beca_excelencia=$_POST["aporte_beca_excelencia"];
	
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$sede=$_SESSION["SELECTOR_ALUMNO"]["sede"];
	$nivel_alumno=$_SESSION["SELECTOR_ALUMNO"]["nivel"];
	$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	$jornada_alumno=$_SESSION["SELECTOR_ALUMNO"]["jornada"];
	
	$tipo="cuota";
	$fecha_hora_actual=date("Y-m-d H:i:s");
	$fecha_actual=date("Y-m-d");
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	 ////Generando nuevo contrato
	 //busco datos del antiguo
	 $cons_C="SELECT * FROM contratos2 WHERE id='$id_contratoX' LIMIT 1";
	 $sql_c=$conexion_mysqli->query($cons_C);
	 
	 $DC=$sql_c->fetch_assoc();
	 
	 $CO_yearingresocarrera=$DC["yearIngresoCarrera"];
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
	 $sql_c->free();
	 
	 
	 if($aporte_beca_excelencia>0){ $beca_excelencia="completa";}
	 else{ $beca_excelencia="sin_beca";}
	 
	 /////////////////////////////////////////////
	 $condicion_contrato="OK";
	 $excedente=0;
	if($aporte_beca_nuevo_milenio>0) 
	{
		if($nivel_alumno>=5)
		{
			 $beca_nuevo_milenio="media_beca";
			 $vigencia_contrato="semestral";
		}
		 else
		{ 
			$beca_nuevo_milenio="completa";
			$vigencia_contrato="anual";
		}
	}
	else
	{ 
		$beca_nuevo_milenio="sin_beca";
		$vigencia_contrato="anual";
	}
	 
	 //------------------------------------------------//
	 $vigencia_contrato=$vigencia_contrato_manual;
	 //------------------------------------------------//
	 
	 $total_a_pagar=$linea_credito_cantidad;
	 ////comentario de beca
	 $comentario_beca=mysqli_real_escape_string($conexion_mysqli, $_POST["comentario_beca_X"]);
	 $comentario_beca=ucwords(strtolower($comentario_beca));
	 $comentario_beca_aux="";
	 if($aporte_beca_nuevo_milenio>0){$comentario_beca_aux="Beca Nuevo Milenio ($".number_format($aporte_beca_nuevo_milenio,0,",",".").")";}
	 if($aporte_beca_excelencia>0)///comentario por beca excelencia
	 { $comentario_beca_aux.=" Beca Excelencia Academica ($".number_format($aporte_beca_excelencia,0,",",".").")";}
	 if(empty($comentario_beca))
	 { $comentario_beca=$comentario_beca_aux;}
	 ////////////////////////////
	 $reasignado="si";
	 //genero nuevo
	 $campos1="id_alumno, id_carrera, nivel_alumno, jornada, sede, yearIngresoCarrera, fecha_inicio, fecha_fin, ano, semestre, numero_cuotas, arancel, saldo_a_favor, porcentaje_desc_contado, total, contado_paga, cheque_paga, id_boleta_generada, linea_credito_paga, cantidad_beca, porcentaje_beca, txt_beca,beca_nuevo_milenio, aporte_beca_nuevo_milenio, beca_excelencia, aporte_beca_excelencia, opcion_pag_matricula, matricula_valor, matricula_a_pagar, sostenedor, cod_user, fecha_generacion, vigencia, condicion, excedente, id_contrato_previo, reasignado";
	 
	 $valores1="'$id_alumno', '$id_carrera', '$CO_nivel_alumno', '$jornada_alumno', '$sede', '$CO_yearingresocarrera', '$CO_fecha_ini', '$CO_fecha_fin', '$CO_year', '$CO_semestre', '$linea_credito_cantidad_cuotas', '$arancel_anual', '$saldo_a_favorX', '0', '$total_a_pagar', '0', '0', '$CO_id_boleta_generada', '$linea_credito_cantidad', '$cantidad_descX', '$porcentaje_becaX', '$comentario_beca', '$beca_nuevo_milenio', '$aporte_beca_nuevo_milenio', '$beca_excelencia', '$aporte_beca_excelencia', '$CO_opcion_paga_matricula', '$CO_matricula_valor', '$CO_matricula_a_pagar', '$CO_sostenedor', '$id_usuario_actual', '$fecha_hora_actual', '$vigencia_contrato', '$condicion_contrato', '$excedente', '$id_contratoX', '$reasignado'";
	 
	 $cons_contrato="INSERT INTO contratos2 ($campos1)VALUES($valores1)";
	 
	 $cons_up_old_contrato="UPDATE contratos2 SET condicion='inactivo' WHERE id='$id_contratoX' LIMIT 1";
	 if(DEBUG)
	 {
	 	echo"C-> $cons_contrato<br><br>";
		
		echo"UP-> $cons_up_old_contrato<br>";
		$id_contrato_new=1;
		$continuar=true;
	 }
	 else
	 {
		$conexion_mysqli->query($cons_contrato);
		$id_contrato_new=$conexion_mysqli->insert_id;
		if($id_contrato_new>0)
		{
			//cambio de estado Contrato_anterio
			$conexion_mysqli->query($cons_up_old_contrato);
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
					{
						$vencimiento="28/02/$año";
					}
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
					if(DEBUG){echo "<br>$cons_in_c";}
					else
					{
						$conexion_mysqli->query($cons_in_c);
					}
				}
				//CAMBIA_SITUACION_FINANCIERA_ALUMNO($id_alumno);//dejo al alumno "V"
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
		 $evento="ASIGNACION BECA COD.$id_contrato_new (sin excedente)";
		 REGISTRA_EVENTO($evento);
		 ///////////////////////////
		 $conexion_mysqli->close();
		 $url="msj_final.php?error=$error&tipo=cuota";
		 if(DEBUG){ echo"<br><strong>URL: $url</strong><br>";}
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
function ELIMINA_CUOTAS_OLD($id_contrato, $id_alumno)
{
	require("../../../funciones/conexion_v2.php");
	$cons_del="DELETE FROM letras WHERE id_contrato='$id_contrato' AND idalumn='$id_alumno' AND tipo='cuota'";
	if(DEBUG){ echo"FUNCION -> $cons_del<br>";}
	else
	{ $conexion_mysqli->query($cons_del);}
	$conexion_mysqli->close();
}
///////////////////////////////---------------------------------/////////////////////////////////////
function CAMBIA_SITUACION_FINANCIERA_ALUMNO($id_alumno, $nueva_condicion="V")
{
	require("../../../funciones/conexion_v2.php");
	$cons_UP="UPDATE alumno SET situacion_financiera='$nueva_condicion' WHERE id='$id_alumno' LIMIT 1";
	if(DEBUG){ echo"---->$cons_UP<br>";}
	else{$conexion_mysqli->query($cons_UP);}
	$conexion_mysqli->close();
}
?>