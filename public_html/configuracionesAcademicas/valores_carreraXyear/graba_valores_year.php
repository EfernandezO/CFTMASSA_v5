<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Gestion_carreras_Mallas->ARANCELES_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

if($_POST)
{
	$continuar=true;
	require("../../../funciones/funcion.php");
	
	$permite_matricula=$_POST["permitirMatricula"];
	$vacantesDiurno=$_POST["vacantesDiurno"];
	$vacantesVespertino=$_POST["vacantesVespertino"];
	$fecha_actual=date("Y-m-d");
	if(DEBUG){var_dump($_POST);}
	$arancel_1=str_inde($_POST["arancel_1"]);
	$arancel_2=str_inde($_POST["arancel_2"]);
	$matricula=str_inde($_POST["matricula"]);
	$year=str_inde($_POST["year"]);
	$sede=str_inde($_POST["sede"]);
	$id_carrera=str_inde($_POST["id_carrera"]);
	
	if(!is_numeric($arancel_1)){ $continuar=false;}
	if(!is_numeric($arancel_2)){ $continuar=false;}
	if(!is_numeric($matricula)){ $continuar=false;}
	
	if($continuar)
	{
		require("../../../funciones/conexion_v2.php");
		$cons="SELECT COUNT(id) FROM hija_carrera_valores WHERE id_madre_carrera='$id_carrera' AND sede='$sede' AND year='$year'";
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
			$cons_1="UPDATE hija_carrera_valores SET matricula='$matricula', arancel_1='$arancel_1', arancel_2='$arancel_2', permite_matricula_nuevos='$permite_matricula', vacantesDiurno='$vacantesDiurno', vacantesVespertino='$vacantesVespertino' WHERE id_madre_carrera='$id_carrera' AND sede='$sede' AND year='$year' LIMIT 1";
		}
		else
		{
			if(DEBUG){ echo"No existen Registros<br>Insertar<br>";}
			$cons_1="INSERT INTO hija_carrera_valores (year, id_madre_carrera, sede, arancel_1, arancel_2, matricula, fecha, permite_matricula_nuevos, vacantesDiurno, vacantesVespertino) VALUES ('$year', '$id_carrera', '$sede', '$arancel_1', '$arancel_2', '$matricula', '$fecha_actual', '$permite_matricula', '$vacantesDiurno', '$vacantesVespertino')";
		}
		//------------------------------------------------------------------------------------//
		if(DEBUG){echo"---> $cons_1<br>";}
		else{$conexion_mysqli->query($cons_1)or die($conexion_mysqli->error);}
		//----------------------------------------------------------------------------------///
		
		$conexion_mysqli->close();
		$url="index.php?id_carrera=$id_carrera&sede=$sede";
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