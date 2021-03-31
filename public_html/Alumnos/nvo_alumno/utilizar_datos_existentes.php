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
$id_alumno=$_GET["id_alumno"];
if(is_numeric($id_alumno))
{
	require("../../../funciones/conexion_v2.php");
	$cons="SELECT * FROM alumno WHERE id='$id_alumno' LIMIT 1";	
	$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		$D=$sql->fetch_assoc();
		
		$_SESSION["MATRICULA"]["rut_alumno"]=$D["rut"];
		$_SESSION["MATRICULA"]["rut_apoderado"]=$D["rut_apoderado"];
		
		$_SESSION["MATRICULA"]["apellido_P_alumno"]=ucwords(strtolower($D["apellido_P"]));
		$_SESSION["MATRICULA"]["apellido_M_alumno"]=ucwords(strtolower($D["apellido_M"]));
		
		$_SESSION["MATRICULA"]["nombres_alumno"]=ucwords(strtolower($D["nombre"]));
		$_SESSION["MATRICULA"]["sexo_alumno"]=$D["sexo"];
		$_SESSION["MATRICULA"]["pais_origen"]=$D["pais_origen"];
		$_SESSION["MATRICULA"]["fnac_alumno"]=$D["fnac"];
		$_SESSION["MATRICULA"]["fono_alumno"]=$D["fono"];
		$_SESSION["MATRICULA"]["direccion_alumno"]=ucwords(strtolower($D["direccion"]));
		$_SESSION["MATRICULA"]["ciudad_alumno"]=ucwords(strtolower($D["ciudad"]));
		$_SESSION["MATRICULA"]["correo_alumno"]=$D["email"];
		$_SESSION["MATRICULA"]["nombreC_apoderado"]=ucwords(strtolower($D["apoderado"]));
		$_SESSION["MATRICULA"]["fono_apoderado"]=$D["fonoa"];
		$_SESSION["MATRICULA"]["direccion_apoderado"]=ucwords(strtolower($D["direccion_apoderado"]));
		$_SESSION["MATRICULA"]["ciudad_apoderado"]=ucwords(strtolower($D["ciudad_apoderado"]));
		$_SESSION["MATRICULA"]["estado_civil"]=$D["estado_civil"];
		$_SESSION["MATRICULA"]["PASO_A"]=true;
		///PASO B//////////////////////////////////////////////
		$_SESSION["MATRICULA"]["liceo"]=ucwords(strtolower($D["liceo"]));
		$_SESSION["MATRICULA"]["liceo_dependencia"]=$D["liceo_dependencia"];
		
		$_SESSION["MATRICULA"]["liceo_ciudad"]=ucwords(strtolower($D["liceo_ciudad"]));
		$_SESSION["MATRICULA"]["liceo_pais"]=$D["liceo_pais"];
		$_SESSION["MATRICULA"]["liceo_egreso"]=$D["liceo_egreso"];
		$_SESSION["MATRICULA"]["liceo_formacion"]=$D["liceo_formacion"];
		//sesion para opcion otros estudios
		$_SESSION["MATRICULA"]["otro_estudio_U"]=$D["otro_estudio_U"];
		$_SESSION["MATRICULA"]["otro_estudio_T"]=$D["otro_estudio_T"];
		$_SESSION["MATRICULA"]["otro_estudio_P"]=$D["otro_estudio_P"];
		$_SESSION["MATRICULA"]["PASO_B"]=true;
	$sql->free();	
	@mysql_close($conexion);
	$conexion_mysqli->close();
	if(DEBUG){ var_export($_SESSION["MATRICULA"]);}
	else{ header("location: paso_A.php");}
}
else
	{ if(DEBUG){ echo"DATOS INCORRECTOS<br>";}
	 else{header("location: paso_A.php");}
	 }
?>