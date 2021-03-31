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

if($_POST)
{
	var_dump($_POST);
	$_SESSION["MATRICULA"]["liceo_nem"]=ucwords(strtolower($_POST["liceo_nem"]));
	$_SESSION["MATRICULA"]["idLiceo"]=$_POST["liceo"];
	
	$_SESSION["MATRICULA"]["liceo_pais"]=$_POST["liceo_pais"];
	$_SESSION["MATRICULA"]["liceo_egreso"]=$_POST["liceo_egreso"];
	$_SESSION["MATRICULA"]["liceo_formacion"]=$_POST["formacion_liceo"];
	//sesion para opcion otros estudios
	$_SESSION["MATRICULA"]["otro_estudio_U"]=$_POST["otro_estudio_U"];
	$_SESSION["MATRICULA"]["otro_estudio_T"]=$_POST["otro_estudio_T"];
	$_SESSION["MATRICULA"]["otro_estudio_P"]=$_POST["otro_estudio_P"];
	//si tiene valor el registro la guardo sino elimino la session anterior en caso de que hubiese
	/*
	$registro_academico=$_POST["registro_academico"];
	$_SESSION["MATRICULA"]["year_ingreso"]=$_POST["ingreso"];
	$_SESSION["MATRICULA"]["estado_academico"]=$_POST["estado_academico"];
	$_SESSION["MATRICULA"]["sede"]=$_POST["sede"];
	$_SESSION["MATRICULA"]["jornada"]=$_POST["jornada"];
	$_SESSION["MATRICULA"]["nivel_academico"]=$_POST["nivel"];//agregado
	$_SESSION["MATRICULA"]["grupo_curso"]=$_POST["grupo_curso"];//agregado
	$_SESSION["MATRICULA"]["carrera"]=$_POST["carrera"];
	*/
	$_SESSION["MATRICULA"]["PASO_B"]=true;
	
	if(DEBUG){ echo"Fin<br>";}
	else{header("location: resumen_mat.php");}
}
else
{
	header("location: paso_A.php");
}
?>