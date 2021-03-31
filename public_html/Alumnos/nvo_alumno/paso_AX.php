<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Agrega_alumno_nuevo_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$debug=false;
if($_POST)
{
	$continuar=true;
	include("../../../funciones/funciones_varias.php");
	//valido rut alumno
	
	
	$aux_rut=str_replace(".","",$_POST["rut"]);
	$aux_rut=trim($aux_rut);
	$aux_rut=strtolower($aux_rut);
	
	$datos_rut=explode("-",$aux_rut);
	$rut=$datos_rut[0];
	$digito_verificador=$datos_rut[1];
	$digito_correcto=validar_rut($rut);
	
	if(RUT_OK($aux_rut)){
		$rut_correcto=$aux_rut;
		$_SESSION["MATRICULA"]["rut_alumno"]=$aux_rut;
		//----DEBUG-----//
		if($debug)
		{echo"Rut Alumno Correcto<br>";}
		//-------------//
	}
	else{
		//----DEBUG-----//
		if($debug)
		{echo"Rut Alumno Incorrecto<br>";}
		//-------------//
		$continuar=false;
		$error="rut_alumno";
	}
	
		
	//valido rut apoderado
	$aux_rut_apo=str_replace(".","",$_POST["rut_apoderado"]);
	$aux_rut_apo=strtolower($aux_rut_apo);
	$aux_rut_apo=trim($aux_rut_apo);
	
	$datos_rut_apo=explode("-",$aux_rut_apo);
	$rut_apo=$datos_rut_apo[0];
	$digito_verificador_apo=$datos_rut_apo[1];
	$digito_correcto_apo=validar_rut($rut_apo);
	
	if(RUT_OK($aux_rut_apo)){
		$rut_correcto_apo=$aux_rut_apo;
		$_SESSION["MATRICULA"]["rut_apoderado"]=$aux_rut_apo;
		//----DEBUG-----//
		if($debug)
		{echo"Rut Apoderado Correcto<br>";}
		//-------------//
	}
	else{
		//----DEBUG-----//
		if($debug)
		{echo"Rut Apoderado Incorrecto<br>";}
		//-------------//
		$continuar=false;
		if($error=="")
		{$error.="rut_apoderado";}
		else
		{$error.="|rut_apoderado";}	
	}
	
	
	
		//campos independientes para apellidos
		$_SESSION["MATRICULA"]["apellido_P_alumno"]=trim(ucwords(strtolower($_POST["apellido_P"])));
		$_SESSION["MATRICULA"]["apellido_M_alumno"]=trim(ucwords(strtolower($_POST["apellido_M"])));
		
		$_SESSION["MATRICULA"]["nombres_alumno"]=trim(ucwords(strtolower($_POST["nombres"])));
		$_SESSION["MATRICULA"]["sexo_alumno"]=trim($_POST["sexo"]);
		//arreglo para guardar fecha de nacimiento
		$dia=$_POST["dia"];
		$mes=$_POST["mes"];
		$año=$_POST["ano"];
		if(checkdate($mes,$dia,$año))
		{
				//----DEBUG-----//
				if($debug)
				{echo"Fecha Nacimiento Aceptada...<br>";}
				//-------------//
			if($dia<10)
			{$dia="0".$dia;}
			if($mes<10)
			{$mes="0".$mes;}
			$fecha_nac=$año."-".$mes."-".$dia;
			$_SESSION["MATRICULA"]["fnac_alumno"]=$fecha_nac;
		}
		else
		{
			//----DEBUG-----//
			if($debug)
			{echo"Fecha Nacimiento Inaceptada...<br>";}
			//-------------//
			$continuar=false;
			if($error=="")
			{$error.="fecha_nac";}
			else
			{$error.="|fecha_nac";}	
		}
			
		///////////////////////////////////////////////////////////////////////////////////////////
		
		$_SESSION["MATRICULA"]["condicionRut"]=$_POST["condicionRut"];
		$_SESSION["MATRICULA"]["estado_civil"]=strip_tags($_POST["estado_civil"]);
		$_SESSION["MATRICULA"]["fono_alumno"]=$_POST["fono"];
		$_SESSION["MATRICULA"]["direccion_alumno"]=ucwords(strtolower($_POST["direccion"]));
		$_SESSION["MATRICULA"]["pais_origen"]=strip_tags($_POST["pais_origen"]);
		$_SESSION["MATRICULA"]["ciudad_alumno"]=ucwords(strtolower($_POST["ciudad"]));
		$_SESSION["MATRICULA"]["correo_alumno"]=$_POST["correo"];
		$_SESSION["MATRICULA"]["nombreC_apoderado"]=ucwords(strtolower($_POST["apoderado"]));
		$_SESSION["MATRICULA"]["fono_apoderado"]=$_POST["fono_apoderado"];
		$_SESSION["MATRICULA"]["direccion_apoderado"]=ucwords(strtolower($_POST["direccion_apoderado"]));
		$_SESSION["MATRICULA"]["ciudad_apoderado"]=ucwords(strtolower($_POST["ciudad_apoderado"]));
		$_SESSION["MATRICULA"]["PASO_A"]=true;
		//---------------------------------------------------------------------------------------------///
	if($continuar)
	{header("location: paso_B.php");}
	else
	{header("location: paso_A.php?error=".base64_encode($error));}	
}
else
{header("location: paso_A.php");}
?>