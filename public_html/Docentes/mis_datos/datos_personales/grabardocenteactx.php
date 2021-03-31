<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
	define("DEBUG", false); 
//-----------------------------------------//	
  //echo"inicio";
  if($_POST)
  {
	  $url="";
	require('../../../../funciones/conexion_v2.php');
	$id=$_POST["id"];
	$nombre=mysqli_real_escape_string($conexion_mysqli, $_POST["nombres"]);
	$apellido_P=mysqli_real_escape_string($conexion_mysqli, $_POST["apellido_P"]);
	$apellido_M=mysqli_real_escape_string($conexion_mysqli, $_POST["apellido_M"]);

	$fn_dia=$_POST["fn_dia"];
	$fn_mes=$_POST["fn_mes"];
	$fn_year=$_POST["fn_year"];
	
	$fecha_nacimiento=$fn_year."-".$fn_mes."-".$fn_dia;
		
	$sexo=$_POST["sexo"];
	$fono=mysqli_real_escape_string($conexion_mysqli, $_POST["fono"]);
	$direccion=mysqli_real_escape_string($conexion_mysqli, $_POST["direccion"]);
	$ciudad=mysqli_real_escape_string($conexion_mysqli, $_POST["ciudad"]);
	$correo=$_POST["correo"];
	////////////////////
	/////////////////////////////
	$id_user_activo=$_SESSION["USUARIO"]["id"];
	///compara clave ingresada con la existente en bbdd
	

$Ucons="UPDATE  personal set nombre='$nombre', apellido_P='$apellido_P', apellido_M='$apellido_M', fono='$fono', direccion='$direccion',ciudad='$ciudad',email_personal='$correo', sexo='$sexo', fecha_nacimiento='$fecha_nacimiento' WHERE id=$id LIMIT 1";

	if(DEBUG){echo"---->$Ucons <br>"; $error="debug";}
    else{ 
			if($conexion_mysqli->query($Ucons)){ $error="D0";}
			else{ $error="D1";}
		}
	
	$url="graba_datos_personales_final.php?error=$error";
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}

}
else
{echo"no post <br>";}
?> 