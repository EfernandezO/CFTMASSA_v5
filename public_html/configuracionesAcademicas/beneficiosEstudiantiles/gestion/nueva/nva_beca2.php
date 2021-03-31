
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
	if(DEBUG){ var_export($_POST);}
	
		$fecha_actual=date("Y-m-d");
		$id_usuario_actual=$_SESSION["USUARIO"]["id"];	
		
		$familiaBeneficio=mysql_real_escape_string($_POST["familiaBeneficio"]);
		$patrocinador=mysql_real_escape_string($_POST["patrocinador"]);
		$procedencia=mysql_real_escape_string($_POST["procedencia"]);
		$vigencia=mysql_real_escape_string($_POST["vigencia"]);
		$duracion=mysql_real_escape_string($_POST["duracion"]);
		$nombre=mysql_real_escape_string($_POST["nombre"]);
		$tipo_aporte=mysql_real_escape_string($_POST["tipo_aporte"]);
		$forma_aporte=mysql_real_escape_string($_POST["formaAporte"]);
		$aporte_valor=mysql_real_escape_string($_POST["aporte_valor"]);
		$aporte_porcentaje=mysql_real_escape_string($_POST["aporte_porcentaje"]);
		$condicion=mysql_real_escape_string($_POST["condicion"]);
	
	$error="";
	
	
		
		$campos="familiaBeneficio, patrocinador, procedencia, vigencia, duracion, beca_nombre, beca_tipo_aporte, formaAporte, beca_aporte_valor, beca_aporte_porcentaje, beca_condicion, fecha_generacion, cod_user";
		$valores="'$familiaBeneficio', '$patrocinador', '$procedencia', '$vigencia', '$duracion', '$nombre', '$tipo_aporte', '$forma_aporte', '$aporte_valor', '$aporte_porcentaje', '$condicion', '$fecha_actual', '$id_usuario_actual'";
		$cons_IN="INSERT INTO beneficiosEstudiantiles ($campos) VALUES ($valores)";
		if(DEBUG){ echo"<br>-->$cons_IN<br>";}
		else
		{$conexion_mysqli->query($cons_IN) or die("INSERTAR: ".$conexion_mysqli->error);}
		$url="../index.php?error=R2";
	
	@mysql_close($conexion);
	$conexion_mysqli->close();
	
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
}
else
{ header("location: ../index.php");}
/////////////////////////////////////////////
?>