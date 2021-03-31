<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Seleccion_de_alumno_para_realizarle_cobranza_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
{
	if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
	{$continuar=true;}
	else
	{$continuar=false;}
}
else
{$continuar=false;}

if(isset($_GET["aplicar"]))
{
	$continuar_2=false;
	$realizar_cobranza=$_GET["aplicar"];
	if(is_numeric($realizar_cobranza))
	{
		if(($realizar_cobranza==1)or($realizar_cobranza==0))
		{$continuar_2=true;}
	}
}
else
{$continuar_2=false;}

if($continuar and $continuar_2)
{
	if(DEBUG){ echo"ingreso realizar Cobranza<br>";}
	$error="AI0";
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/VX.php");
	
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	
	$cons_UP="UPDATE alumno SET realizar_cobranza='$realizar_cobranza' WHERE id='$id_alumno' LIMIT 1";
	if(DEBUG){ echo"---->$cons_UP<br>";}
	else
	{ 
		if($conexion_mysqli->query($cons_UP))
		{
			if($realizar_cobranza>0){ $evento="Selecciona a alumno id_alumno: $id_alumno para realizarle Cobranza"; $descripcion="Seleccionado para permitir realizarle Cobranza";}
			else{ $evento="Selecciona a alumno id_alumno: $id_alumno para NO realizarle Cobranza"; $descripcion="Seleccionado para Excluirlo de la realizacion de Cobranza";}
			
			REGISTRA_EVENTO($evento);
			REGISTRO_EVENTO_ALUMNO($id_alumno, "notificacion", $descripcion);
		}
		else
		{$error="AI1";}
	}
	
	$conexion_mysqli->close();
	@mysql_close($conexion);
}

$url="utilizar_para_cobranza_final.php?error=$error";
if(DEBUG){ echo"URL: $url<br>";}
else{ header("location: $url");}