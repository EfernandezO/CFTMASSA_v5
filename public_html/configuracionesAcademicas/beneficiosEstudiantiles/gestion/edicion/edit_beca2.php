
<?php
//-----------------------------------------//
	require("../../../../OKALIS/seguridad.php");
	require("../../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	
if($_POST)
{
	require("../../../../../funciones/conexion_v2.php");
	include("../../../../../funciones/VX.php");
	if(DEBUG){ var_export($_POST);}
	

	$id_beca=mysql_real_escape_string($_POST["id_beca"]);
	$familiaBeneficio=mysql_real_escape_string($_POST["familiaBeneficio"]);
	$patrocinador=mysql_real_escape_string($_POST["patrocinador"]);
	$procedencia=mysql_real_escape_string($_POST["procedencia"]);
	$vigencia=mysql_real_escape_string($_POST["vigencia"]);
	$duracion=mysql_real_escape_string($_POST["duracion"]);
	$nombre=mysql_real_escape_string($_POST["nombre"]);
	$tipo_aporte=mysql_real_escape_string($_POST["tipo_aporte"]);
	$formaAporte=mysql_real_escape_string($_POST["formaAporte"]);
	$aporte_valor=mysql_real_escape_string($_POST["aporte_valor"]);
	$aporte_porcentaje=mysql_real_escape_string($_POST["aporte_porcentaje"]);
	$condicion=mysql_real_escape_string($_POST["condicion"]);
	
	$url="../index.php?error=R3";
	$error="";
	
	if($tipo_aporte=="valor"){ $aporte_porcentaje=0;}
	else{$aporte_valor=0;}
	
	
		$campo_valor="familiaBeneficio='$familiaBeneficio',  patrocinador='$patrocinador', procedencia='$procedencia', vigencia='$vigencia', duracion='$duracion', beca_nombre='$nombre', beca_tipo_aporte='$tipo_aporte', formaAporte='$formaAporte', beca_aporte_valor='$aporte_valor', beca_aporte_porcentaje='$aporte_porcentaje', beca_condicion='$condicion'";
		
		$cons_UP="UPDATE beneficiosEstudiantiles SET $campo_valor WHERE id='$id_beca' LIMIT 1";
		if(DEBUG){ echo"<br>-->$cons_UP<br>";}
		else
		{$conexion_mysqli->query($cons_UP) or die($conexion_mysqli->error);}
		
	$evento="Edita Beneficio Estudiantil id-> $id_beca";
	REGISTRA_EVENTO($evento);
		
	@mysql_close($conexion);
	$conexion_mysqli->close();
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
}
else
{ header("location: ../index.php");}
/////////////////////////////////////////////
?>