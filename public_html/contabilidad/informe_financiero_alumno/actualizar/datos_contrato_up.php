<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Impresion_Y_Gestion_de_contratos_v1_editar");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

if(($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])and($_POST))
{
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	require("../../../../funciones/conexion_v2.php");
	$id_contrato=$_POST["id_contrato"];
	
	if(DEBUG)
	{
		var_export($_POST);
		echo"<br><br>";
	}
	
	$sede_contrato=mysqli_real_escape_string($conexion_mysqli,$_POST["sede_contrato"]);
	$fecha_inicio=mysqli_real_escape_string($conexion_mysqli,$_POST["fecha_inicio"]);
	$fecha_fin=mysqli_real_escape_string($conexion_mysqli,$_POST["fecha_fin"]);
	$id_carrera_contrato=mysqli_real_escape_string($conexion_mysqli,$_POST["id_carrera_contrato"]);
	$comentario=mysqli_real_escape_string($conexion_mysqli,$_POST["comentario"]);
	$year_contrato=mysqli_real_escape_string($conexion_mysqli,$_POST["year_contrato"]);
	$semestre_contrato=mysqli_real_escape_string($conexion_mysqli,$_POST["semestre_contrato"]);
	$matricula_contrato=mysqli_real_escape_string($conexion_mysqli,$_POST["matricula_contrato"]);
	$forma_pago_matricula=mysqli_real_escape_string($conexion_mysqli,$_POST["forma_pago_matricula"]);
	$arancel=mysqli_real_escape_string($conexion_mysqli,$_POST["arancel"]);
	$saldo_a_favor=mysqli_real_escape_string($conexion_mysqli,$_POST["saldo_a_favor"]);
	$cantidad_beca=mysqli_real_escape_string($conexion_mysqli,$_POST["cantidad_beca"]);
	$porcentaje_desc_beca=mysqli_real_escape_string($conexion_mysqli,$_POST["porcentaje_desc_beca"]);
	$porcentaje_desc_contado=mysqli_real_escape_string($conexion_mysqli,$_POST["porcentaje_desc_contado"]);
	$total=mysqli_real_escape_string($conexion_mysqli,$_POST["total"]);
	$contado=mysqli_real_escape_string($conexion_mysqli,$_POST["contado"]);
	$cheque=mysqli_real_escape_string($conexion_mysqli,$_POST["cheque"]);
	$linea_credito=mysqli_real_escape_string($conexion_mysqli,$_POST["linea_credito"]);
	$excedentes=mysqli_real_escape_string($conexion_mysqli,$_POST["excedentes"]);
	$id_contrato_anterior=mysqli_real_escape_string($conexion_mysqli,$_POST["id_contrato_anterior"]);
	
	$nivel_alumno_contrato=mysqli_real_escape_string($conexion_mysqli,$_POST["nivel_alumno_contrato"]);
	$nivel_alumno_contrato_2=mysqli_real_escape_string($conexion_mysqli,$_POST["nivel_alumno_contrato_2"]);
	
	$jornada_contrato=mysqli_real_escape_string($conexion_mysqli,$_POST["jornada_contrato"]);
	$yearIngresoCarrera=mysqli_real_escape_string($conexion_mysqli,$_POST["yearIngresoCarrera"]);
	
	if(!is_numeric($nivel_alumno_contrato)){ $campo_nivel_alumno_contrato="";}
	else{ $campo_nivel_alumno_contrato=", nivel_alumno='$nivel_alumno_contrato'";}
	
	if(!is_numeric($nivel_alumno_contrato_2)){ $campo_nivel_alumno_contrato_2="";}
	else{ $campo_nivel_alumno_contrato_2=", nivel_alumno_2='$nivel_alumno_contrato_2'";}
	
	$beca_nuevo_milenio=mysqli_real_escape_string($conexion_mysqli,$_POST["beca_nuevo_milenio"]);
	$aporte_beca_nuevo_milenio=mysqli_real_escape_string($conexion_mysqli,$_POST["aporte_beca_nuevo_milenio"]);
	$vigencia=mysqli_real_escape_string($conexion_mysqli,$_POST["vigencia"]);
	
	$beca_excelencia=mysqli_real_escape_string($conexion_mysqli,$_POST["beca_excelencia"]);
	$aporte_beca_excelencia=mysqli_real_escape_string($conexion_mysqli,$_POST["aporte_beca_excelencia"]);
	
	$campos_valores="sede='$sede_contrato', id_carrera='$id_carrera_contrato', fecha_inicio='$fecha_inicio', fecha_fin='$fecha_fin', txt_beca='$comentario', ano='$year_contrato', yearIngresoCarrera='$yearIngresoCarrera', semestre='$semestre_contrato', jornada='$jornada_contrato', matricula_a_pagar='$matricula_contrato', opcion_pag_matricula='$forma_pago_matricula', arancel='$arancel', saldo_a_favor='$saldo_a_favor', cantidad_beca='$cantidad_beca', porcentaje_beca='$porcentaje_desc_beca', porcentaje_desc_contado='$porcentaje_desc_contado', total='$total', contado_paga='$contado', cheque_paga='$cheque', linea_credito_paga='$linea_credito', excedente='$excedentes', id_contrato_previo='$id_contrato_anterior', beca_nuevo_milenio='$beca_nuevo_milenio', aporte_beca_nuevo_milenio='$aporte_beca_nuevo_milenio', vigencia='$vigencia', beca_excelencia='$beca_excelencia', aporte_beca_excelencia='$aporte_beca_excelencia' $campo_nivel_alumno_contrato $campo_nivel_alumno_contrato_2";
	
	$cons_UPC="UPDATE contratos2 SET $campos_valores WHERE id='$id_contrato' LIMIT 1";
	
	if(DEBUG){ echo"--> $cons_UPC<br>";}
	else
	{
		//------------------------------------//
		include("../../../../funciones/VX.php");
		$evento="Modifica contrato de Alumno id_alumno: $id_alumno id_contrato: $id_contrato";
		REGISTRA_EVENTO($evento);
		//-----------------------------------//
		 $conexion_mysqli->query($cons_UPC)or die("UP contrato ".$conexion_mysqli->close());
	}
	$conexion_mysqli->close();
	if(!DEBUG){ header("location: ../index.php?error=0&ID=$id_contrato");}
}
else
{ header("location: ../index.php");}
?>