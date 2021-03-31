<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Gestion_carreras_Mallas->FechasAcademicas_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

if($_POST)
{
	$continuar=true;
	require("../../../funciones/funcion.php");
	
	
	if(DEBUG){var_dump($_POST);}
	
	$fechaInicio=str_inde($_POST["fechaInicio"]);
	$fechaFin=str_inde($_POST["fechaFin"]);
	$semestre=str_inde($_POST["semestre"]);
	$year=str_inde($_POST["year"]);

	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	
	$continuar=true;
	
	if($continuar)
	{
		require("../../../funciones/conexion_v2.php");
		$cons="SELECT COUNT(id) FROM fechasAcademicas WHERE semestre='$semestre' AND year='$year'";
		$sqli=$conexion_mysqli->query($cons);
		$R=$sqli->fetch_row();
		$num_registros=$R[0];
		if(empty($num_registros)){$num_registros=0;}
		$sqli->free();
		
		if($num_registros>0){$ya_exiten_registros=true;}
		else{ $ya_exiten_registros=false;}
		//-------------------------------------------------------------------------------------//
		if($ya_exiten_registros)
		{
			if(DEBUG){ echo"YA existen Registros<br>Actualizar<br>";}
			$cons_1="UPDATE fechasAcademicas SET  fechaInicio='$fechaInicio', fechaFin='$fechaFin', codUser='$id_usuario_actual' WHERE semestre='$semestre' AND year='$year' LIMIT 1";
		}
		else
		{
			if(DEBUG){ echo"No existen Registros<br>Insertar<br>";}
			$cons_1="INSERT INTO fechasAcademicas (semestre, year, fechaInicio, fechaFin, codUser) VALUES ('$semestre','$year', '$fechaInicio', '$fechaFin', '$id_usuario_actual')";
		}
		//------------------------------------------------------------------------------------//
		if(DEBUG){echo"---> $cons_1<br>";}
		else{$conexion_mysqli->query($cons_1)or die($conexion_mysqli->error);}
		//----------------------------------------------------------------------------------///
		@mysql_close($conexion);
		$conexion_mysqli->close();
		$url="index.php";
		if(DEBUG){ echo"URL: $url<br>";}
		else{header("location: $url");}
	}
	else
	{
		if(DEBUG){echo"No Continuar<br>";}
		else{header("location: ../index.php");}
	}

}
else
{header("location: ../index.php");}
?>