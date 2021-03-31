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
if(DEBUG){ include("../../../funciones/codificacion.php");}
$activar_registro=false;

$registro_academico="";
//------------------------------------------//
if(isset($_SESSION["MATRICULA"]["GRABADO"]))
{
	if($_SESSION["MATRICULA"]["GRABADO"])
	{ $GRABADO=true;}
	else
	{ $GRABADO=false;}
}
else
{ $GRABADO=false;}
//--------------------------------------------//

if(($_SESSION["MATRICULA"]["RESUMEN"])and(!$GRABADO))
{
	require("../../../funciones/conexion_v2.php");
	
	$fecha_hora_actual=date("Y-m-d H:i:s");
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	$sedeActual=$_SESSION["USUARIO"]["sede"];
	
	$rut_alumno=(mysqli_real_escape_string($conexion_mysqli, $_SESSION["MATRICULA"]["rut_alumno"]));
	$apellido_P=(mysqli_real_escape_string($conexion_mysqli, $_SESSION["MATRICULA"]["apellido_P_alumno"]));
	$apellido_M=(mysqli_real_escape_string($conexion_mysqli, $_SESSION["MATRICULA"]["apellido_M_alumno"]));
	
	$nombres_alumno=(mysqli_real_escape_string($conexion_mysqli, $_SESSION["MATRICULA"]["nombres_alumno"]));
	$sexo_alumno=(mysqli_real_escape_string($conexion_mysqli, $_SESSION["MATRICULA"]["sexo_alumno"]));
	$fnac_alumno=(mysqli_real_escape_string($conexion_mysqli, $_SESSION["MATRICULA"]["fnac_alumno"]));
	$fono_alumno=(mysqli_real_escape_string($conexion_mysqli, $_SESSION["MATRICULA"]["fono_alumno"]));
	$direccion_alumno=(mysqli_real_escape_string($conexion_mysqli, $_SESSION["MATRICULA"]["direccion_alumno"]));
	$ciudad_alumno=(mysqli_real_escape_string($conexion_mysqli, $_SESSION["MATRICULA"]["ciudad_alumno"]));
	$pais_origen_alumno=(mysqli_real_escape_string($conexion_mysqli, $_SESSION["MATRICULA"]["pais_origen"]));
	$correo_alumno=(mysqli_real_escape_string($conexion_mysqli, $_SESSION["MATRICULA"]["correo_alumno"]));
	$estado_civil_alumno=(mysqli_real_escape_string($conexion_mysqli, $_SESSION["MATRICULA"]["estado_civil"]));//agregado
	$nombreC_apoderado=(mysqli_real_escape_string($conexion_mysqli, $_SESSION["MATRICULA"]["nombreC_apoderado"]));
	$fono_apoderado=(mysqli_real_escape_string($conexion_mysqli, $_SESSION["MATRICULA"]["fono_apoderado"]));
	$rut_apoderado=(mysqli_real_escape_string($conexion_mysqli, $_SESSION["MATRICULA"]["rut_apoderado"]));
	$direccion_apoderado=(mysqli_real_escape_string($conexion_mysqli, $_SESSION["MATRICULA"]["direccion_apoderado"]));
	$ciudad_apoderado=(mysqli_real_escape_string($conexion_mysqli, $_SESSION["MATRICULA"]["ciudad_apoderado"]));
	
	
	$idLiceo=(mysqli_real_escape_string($conexion_mysqli, $_SESSION["MATRICULA"]["idLiceo"]));
	$liceo_nem=(mysqli_real_escape_string($conexion_mysqli, $_SESSION["MATRICULA"]["liceo_nem"]));
	$liceo_pais=(mysqli_real_escape_string($conexion_mysqli, $_SESSION["MATRICULA"]["liceo_pais"]));
	$liceo_egreso=(mysqli_real_escape_string($conexion_mysqli, $_SESSION["MATRICULA"]["liceo_egreso"]));
	
	//campos agregados
	//sesion para opcion otros estudios
	$liceo_formacion=mysqli_real_escape_string($conexion_mysqli, $_SESSION["MATRICULA"]["liceo_formacion"]);
	$otro_estudio_U=mysqli_real_escape_string($conexion_mysqli, $_SESSION["MATRICULA"]["otro_estudio_U"]);
	$otro_estudio_T=mysqli_real_escape_string($conexion_mysqli, $_SESSION["MATRICULA"]["otro_estudio_T"]);
	$otro_estudio_P=mysqli_real_escape_string($conexion_mysqli, $_SESSION["MATRICULA"]["otro_estudio_P"]);
	/////////////////////////////////////////////////////////////////////
	
	
	////////////////////////////GENERANDO CONSULTA/////////////////////////////////
	$clave="Ma_".strtolower($rut_alumno);//genero clave
	$campos="rut, nombre, apellido_P, apellido_M, sexo, estado_civil, direccion, ciudad, pais_origen, fono, idLiceo, liceo_pais, liceo_egreso, liceo_formacion, liceo_nem, otro_estudio_U, otro_estudio_T, otro_estudio_P, apoderado, rut_apoderado, direccion_apoderado, ciudad_apoderado, fonoa, clave, email, fnac, fecha_registro, cod_user, sede";
	
	$valores="'$rut_alumno', '$nombres_alumno', '$apellido_P', '$apellido_M', '$sexo_alumno', '$estado_civil_alumno', '$direccion_alumno', '$ciudad_alumno', '$pais_origen_alumno', '$fono_alumno', '$idLiceo','$liceo_pais', '$liceo_egreso', '$liceo_formacion', '$liceo_nem', '$otro_estudio_U', '$otro_estudio_T', '$otro_estudio_P', '$nombreC_apoderado', '$rut_apoderado', '$direccion_apoderado', '$ciudad_apoderado', '$fono_apoderado', '$clave', '$correo_alumno', '$fnac_alumno', '$fecha_hora_actual', '$id_usuario_actual', '$sedeActual'";
	
	$cons_IN="INSERT INTO alumno ($campos) VALUES($valores)";
	if(DEBUG){echo"$cons_IN<br>";}
	else
	{
		if($conexion_mysqli->query($cons_IN))
		{
			$_SESSION["MATRICULA"]["GRABADO"]=true;
			$id_alumno=$conexion_mysqli->insert_id;
			$_SESSION["MATRICULA"]["id_alumno"]=$id_alumno;
			$error=0;
		}
		else
		{
			$_SESSION["MATRICULA"]["GRABADO"]=false;
			$error=1;
			echo "$cons_IN<br>ERROR ".$conexion_mysqli->error;
		}
	}

//Redirijiendo//////////////////////////////
	if($error>0)
	{$url="resumen_mat.php?error=$error";}
	else
	{
		$url="opciones_finales_mat.php?error=$error";
		/////Registro EVENTO///
		require("../../../funciones/VX.php");
		$evento="agrega alumno(INSCRIPCION) ->".$_SESSION["MATRICULA"]["rut_alumno"];
		REGISTRA_EVENTO($evento);
		
		$descripcion="Registrado en Sistema como Alumno";
		REGISTRO_EVENTO_ALUMNO($id_alumno, "informacion",$descripcion);
		///////////////////////
	}
	///////////////
	$conexion_mysqli->close();
	if(!DEBUG){header("location: $url");}
//////////////////////////////////	


}
else
{if(DEBUG){ echo"Session Activa...<br>";}else{header("location: opciones_finales_mat.php");}}
?>