<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumno->asignacion de Becas V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
 $acceso=false;
 $comparador=md5("reasignacion_e".date("Y-m-d"));
 $validador=$_POST["validador2"];
 if($validador==$comparador)
 { $acceso=true;}

if($_SESSION["REASIGNAR"]["verificador"])
{
if(($_POST)and($acceso))
{
	if(!DEBUG){$_SESSION["REASIGNAR"]["verificador"]=false;}
	 require("../../../funciones/conexion_v2.php");
	 if(DEBUG){ var_export($_POST);}
	 if(DEBUG){echo"<br><br>===================================================================================<br>";}
	 
	  $vigencia_contrato_manual=$_POST["vigencia_contrato_manual"];
	 
	$id_contratoX2=$_POST["id_contratoX2"];
	$porcentaje_beca_old2=$_POST["porcentaje_beca_old2"];
	$aporte_beca=$_POST["aporte_beca2"];
	
	$aporte_beca_excelencia=$_POST["aporte_beca_excelencia2"];
	
	$cantidad_desc2=$_POST["cantidad_desc2"];
	$arancel_anual2=$_POST["arancel_anual2"];
	$saldo_a_favor2=$_POST["saldo_a_favor2"];
	$excedente_valor=trim($_POST["excedente_valor"]);
	
	
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$sede=$_SESSION["SELECTOR_ALUMNO"]["sede"];
	$nivel_alumno=$_SESSION["SELECTOR_ALUMNO"]["nivel"];
	$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	$jornada_alumno=$_SESSION["SELECTOR_ALUMNO"]["jornada"];
	
	$tipo="cuota";
	$fecha_actual=date("Y-m-d");
	$fecha_hora_actual=date("Y-m-d H:i:s");
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	 ////Generando nuevo contrato
	 //busco datos del antiguo
	 $cons_C="SELECT * FROM contratos2 WHERE id='$id_contratoX2' LIMIT 1";
	 $sql_c=$conexion_mysqli->query($cons_C);
	 $DC=$sql_c->fetch_assoc();
	 
	 $CO_yearingresocarrera=$DC["yearIngresoCarrera"];
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
	 
	 $sql_c->free();
	 
	  if($aporte_beca_excelencia>0){ $beca_excelencia="completa";}
	 else{ $beca_excelencia="sin_beca";}
	 //////////////////////////////////////////
	 $linea_credito_cantidad=0;
	 $linea_credito_cantidad_cuotas=0;
	 $vigencia_contrato="anual";
	 $condicion_contrato="OK";
	 //$total_a_pagar=($arancel_anual2-$saldo_a_favor2);
	 $total_a_pagar=0;
	 
	  ////comentario de beca
	 $comentario_beca=mysqli_real_escape_string($conexion_mysqli, $_POST["comentario_beca_X"]);
	 $comentario_beca=ucwords(strtolower($comentario_beca));
	
	 $comentario_beca.=" *Excedente Proximo Contrato $".number_format($excedente_valor,0,",",".")."*";
	
	 ////////////////////////////
	
	 $reasignado="si";
	 
	 if($aporte_beca>0)
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
	
	 //genero nuevo
	 $campos1="id_alumno, id_carrera, nivel_alumno, jornada, sede, yearIngresoCarrera, fecha_inicio, fecha_fin, ano, semestre, numero_cuotas, arancel, saldo_a_favor, porcentaje_desc_contado, total, contado_paga, cheque_paga, id_boleta_generada, linea_credito_paga, cantidad_beca, porcentaje_beca, txt_beca, beca_nuevo_milenio, aporte_beca_nuevo_milenio, beca_excelencia, aporte_beca_excelencia, opcion_pag_matricula, matricula_valor, matricula_a_pagar, sostenedor, cod_user, fecha_generacion, vigencia, condicion, excedente, id_contrato_previo, reasignado";
	 
	 $valores1="'$id_alumno', '$id_carrera', '$CO_nivel_alumno', '$jornada_alumno', '$sede', '$CO_yearingresocarrera', '$CO_fecha_ini', '$CO_fecha_fin', '$CO_year', '$CO_semestre', '$linea_credito_cantidad_cuotas', '$arancel_anual2', '$saldo_a_favor2', '0', '$total_a_pagar', '0', '0', '$CO_id_boleta_generada', '$linea_credito_cantidad', '$cantidad_desc2', '$porcentaje_beca_old2', '$comentario_beca', '$beca_nuevo_milenio', '$aporte_beca', '$beca_excelencia', '$aporte_beca_excelencia', '$CO_opcion_paga_matricula', '$CO_matricula_valor', '$CO_matricula_a_pagar', '$CO_sostenedor', '$id_usuario_actual', '$fecha_hora_actual', '$vigencia_contrato', '$condicion_contrato', '$excedente_valor', '$id_contratoX2', '$reasignado'";
	 
	 $cons_contrato="INSERT INTO contratos2 ($campos1)VALUES($valores1)";
	 
	 $cons_up_old_contrato="UPDATE contratos2 SET condicion='inactivo' WHERE id='$id_contratoX2' LIMIT 1";
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
	////////////////////
	 if($continuar)
	 {
	 	ELIMINA_CUOTAS_OLD($id_contratoX2, $id_alumno);//elimino cuotas antiguas
		//CAMBIA_SITUACION_FINANCIERA_ALUMNO($id_alumno);//dejo al alumno "V"
		$error=0;
	 }	
	 else
	 {
	 	echo"IMPOSIBLE CONTINUAR FALLA INSERTANDO CONTRATO<br>";
		$error=1;
	 }		
	 
	  /////////////
		 include("../../../funciones/VX.php");
		 $evento="ASIGNACION BECA id_contrato: $id_contrato_new (excedente)";
		 REGISTRA_EVENTO($evento);
		 ///////////////////////////
		
		 
		 $conexion_mysqli->close();
		 
	 	 $url="msj_final.php?error=$error&tipo=excedente";
		 
		 if(DEBUG){ echo"<br>URL: $url<br>";}
		 else{ header("location: $url");}
		 echo"fin<br>";
}
else
{ 
	if(DEBUG){ echo"Sin Acceso Redirijir<br>";}
	else{header("location: asignar_beca_1.php");}
}
}
else
{
	echo"Accion Ya Realizada...";
}
//////////////////////////////////////////////////////////////////
function ELIMINA_CUOTAS_OLD($id_contrato, $id_alumno)
{
	require("../../../funciones/conexion_v2.php");
	$cons_del="DELETE FROM letras WHERE id_contrato='$id_contrato' AND idalumn='$id_alumno' AND tipo='cuota'";
	if(DEBUG){ echo"FUNCION -> $cons_del<br>";}
	else{$conexion_mysqli->query($cons_del);}
	$conexion_mysqli->close();
}
///////////////////////////////////////////
function CAMBIA_SITUACION_FINANCIERA_ALUMNO($id_alumno, $nueva_condicion="V")
{
	 require("../../../funciones/conexion_v2.php");
	$cons_UP="UPDATE alumno SET situacion_financiera='$nueva_condicion' WHERE id='$id_alumno' LIMIT 1";
	if(DEBUG){ echo"---->$cons_UP<br>";}
	else{ $conexion_mysqli->query($cons_UP);}
	$conexion_mysqli->close();
}
?>