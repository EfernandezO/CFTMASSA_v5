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
if($_POST)
{
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	require("../../../../funciones/conexion_v2.php");
	
	if(DEBUG){var_dump($_POST);}
	$clave_actual=strip_tags($_POST["clave_actual"]);
	$nueva_clave_1=strip_tags($_POST["nueva_clave"]);
	$nueva_clave_2=strip_tags($_POST["nueva_clave_2"]);
	///-----------------------------------------------------------///
	$cons_1="SELECT clave FROM personal WHERE id='$id_usuario_actual' LIMIT 1";
	$sql_1=$conexion_mysqli->query($cons_1);
	$D1=$sql_1->fetch_assoc();
		$clave_actual_codificada=$D1["clave"];
	$sql_1->free();
	
	if(DEBUG){ echo"CLAVE ACTUAL CODIFICADA: $clave_actual_codificada<br>";}
	///-----------------------------------------------------------///
	
	
	if((!empty($clave_actual))and(md5($clave_actual)==$clave_actual_codificada))
	{ $se_puede_grabar_1=true; if(DEBUG){ echo"clave coincide con guardada...<br>";}}
	else
	{ $se_puede_grabar_1=false; if(DEBUG){ echo"clave NO coincide con guardada...<br>";}}
	//-------------------------------------------------------------///
	
	if($nueva_clave_1==$nueva_clave_2)
	{ $se_puede_grabar_2=true; if(DEBUG){ echo"Nuevas Claves Coinciden<br>";}}
	else
	{ $se_puede_grabar_2=false; if(DEBUG){ echo"Nuevas CLaves NO coinciden<br>";}}
	//------------------------------------------------------------------///
	if($se_puede_grabar_1)
	{
		if($se_puede_grabar_2)
		{
			$clave_a_guardar=md5($nueva_clave_1);
			$cons_UP="UPDATE personal SET clave='$clave_a_guardar' WHERE id='$id_usuario_actual' LIMIT 1";
			if(DEBUG){ $error="debug"; echo" $cons_UP<br>";}
			else
			{
				if($conexion_mysqli->query($cons_UP))
				{ $error="C0";}
				else
				{ $error="C1";}
			}
		}
		else
		{ $error="C3";}
	}
	else
	{ $error="C2";}
//--------------------------------------------------------------------------------------------------------------//
	$conexion_mysqli->close();
	$url="cambio_clave_final.php?error=$error";	
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
}
else
{ header("location: cambio_clave_1.php");}
?>