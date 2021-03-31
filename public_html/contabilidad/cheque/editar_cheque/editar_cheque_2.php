<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define("DEBUG", false);
//-----------------------------------------//
if($_POST)
{
	$error="debug";
	if(DEBUG){ var_dump($_POST);}
	require("../../../../funciones/conexion_v2.php");
	
	$id_cheque=mysqli_real_escape_string($conexion_mysqli, $_POST["id_cheque"]);
	$numero_cheque=mysqli_real_escape_string($conexion_mysqli, $_POST["numero_cheque"]);
	$cheque_banco=mysqli_real_escape_string($conexion_mysqli, $_POST["cheque_banco"]);
	$cheque_fecha_emision=mysqli_real_escape_string($conexion_mysqli, $_POST["cheque_fecha_emision"]);
	$cheque_fecha_vencimiento=mysqli_real_escape_string($conexion_mysqli, $_POST["cheque_fecha_vence"]);
	$cheque_valor=mysqli_real_escape_string($conexion_mysqli, $_POST["valor"]);
	
	$cons_UPCH="UPDATE registro_cheques SET numero='$numero_cheque', banco='$cheque_banco', fecha='$cheque_fecha_emision', fecha_vencimiento='$cheque_fecha_vencimiento', valor='$cheque_valor' WHERE id='$id_cheque' LIMIT 1";
	
	if(DEBUG){ echo"---> $cons_UPCH<br>";}
	else
	{ 
			if($conexion_mysqli->query($cons_UPCH))
			{ 
				include("../../../../funciones/VX.php");
				$evento="Modifica Cheque id_cheque: $id_cheque";
				REGISTRA_EVENTO($evento);
				$error="CH0";
			}
			else
			{ $error="CH1";}
	}
	
	@mysql_close($conexion);
	$conexion_mysqli->close();
	
	$url="editar_cheque_3.php?error=$error";
	
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
}
else
{ echo"Sin Datos<br>";}
?>