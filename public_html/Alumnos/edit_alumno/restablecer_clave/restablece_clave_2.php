<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Restablecer_clave_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//-----------------------------------------//

if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
{
	if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
	{ $hay_alumno=true;}
	else
	{$hay_alumno=false;}
}
else
{$hay_alumno=false;}
//-------------------------------------------------//

if($hay_alumno)
{
	require("../../../../funciones/conexion_v2.php");
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	
	$prefijo="Ma_";
	//----------------------------------------------------------//
	$cons_a="SELECT rut FROM alumno WHERE id='$id_alumno' LIMIT 1";
	$sqli_A=$conexion_mysqli->query($cons_a);
		$A=$sqli_A->fetch_assoc();
	$rut_alumno=$A["rut"];	
	$sqli_A->free();
	//------------------------------------------------------///
	$clave=mysqli_real_escape_string($conexion_mysqli, $prefijo.$rut_alumno);
	
	
	$cons_up="UPDATE alumno SET clave='$clave' WHERE id='$id_alumno' LIMIT 1";
	if(DEBUG){ echo"--> $cons_up<br>";}
	else
	{ 
		$conexion_mysqli->query($cons_up); 
		
		//------------------------------------------------------------------//
		include("../../../../funciones/VX.php");
		$evento="Restablece Clave a Alumno id_alumno: $id_alumno";
		REGISTRA_EVENTO($evento);
		$descripcion="Clave de Acceso a Intranet, Restablecida";
		REGISTRO_EVENTO_ALUMNO($id_alumno, "notificacion",$descripcion);
		//------------------------------------------------------------------//
		
		header("location: restablece_clave_3.php?error=RC0");
	}
	
	
	$conexion_mysqli->close();
}
else
{
	if(DEBUG){ echo"Sin Alumno<br>";}
}

?>