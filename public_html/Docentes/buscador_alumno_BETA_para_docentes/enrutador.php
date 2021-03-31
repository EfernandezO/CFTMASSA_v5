<?php
 //-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
//-----------------------------------------//
	//**************************************//
	define("DEBUG",false);
	$comparador=md5("GDXT".date("d-m-Y"));
	$validador=$_POST["validador"];
	$acceso=true;
	$url_destino="../index.php";//en caso de que no tenga post
	$url="HALL/index.php";//si todo bien redirige aqui
	//**************************************//
	if($comparador==$validador)
	{	$acceso=true;}
	if(($_POST)and($acceso))
	{
		if(DEBUG){ echo"Acceso Confirmado :D<br>";}
		if(DEBUG){var_export($_POST);}
		$id_alumno=$_POST["hi_id_alumno"];
		require("../../../funciones/conexion_v2.php");
		$cons="SELECT * FROM alumno WHERE id='$id_alumno'";
		if(DEBUG){ echo"$cons<br>";}
		$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		$DA=$sql->fetch_assoc();
			$_SESSION["SELECTOR_ALUMNO"]["id"]=$id_alumno;
			$_SESSION["SELECTOR_ALUMNO"]["rut"]=$DA["rut"];
			$_SESSION["SELECTOR_ALUMNO"]["nombre"]=$DA["nombre"];
			$apellido_old=$DA["apellido"];
			$apellido_P=$DA["apellido_P"];
			$apellido_M=$DA["apellido_M"];
			$apellido_new=$apellido_P." ".$apellido_M;
			if($apellido_new==" "){ $apellido_label=$apellido_old;}
			else{ $apellido_label=$apellido_new;}
			$ingreso=$DA["ingreso"];
			$grupo=$DA["grupo"];
			$_SESSION["SELECTOR_ALUMNO"]["carrera"]=$DA["carrera"];
			$_SESSION["SELECTOR_ALUMNO"]["id_carrera"]=$DA["id_carrera"];
			$_SESSION["SELECTOR_ALUMNO"]["situacion"]=$DA["situacion"];
			$_SESSION["SELECTOR_ALUMNO"]["sede"]=$DA["sede"];
			$_SESSION["SELECTOR_ALUMNO"]["jornada"]=$DA["jornada"];
			$_SESSION["SELECTOR_ALUMNO"]["nivel"]=$DA["nivel"];
			$_SESSION["SELECTOR_ALUMNO"]["sexo"]=$DA["sexo"];
			$apellido_label=$apellido_label;
			$_SESSION["SELECTOR_ALUMNO"]["apellido"]=$apellido_label;
			$_SESSION["SELECTOR_ALUMNO"]["ingreso"]=$ingreso;
			$_SESSION["SELECTOR_ALUMNO"]["grupo"]=$grupo;
			$_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]=true;
			
			////////////////////////////////////////////////////
			$_SESSION["ULTIMO_ALUMNO"]["id"]=$id_alumno;
			$_SESSION["ULTIMO_ALUMNO"]["sede"]=$DA["sede"];
			$_SESSION["ULTIMO_ALUMNO"]["ACTIVO"]=true;
			
		$sql->free();
		@mysql_close($conexion);
		$conexion_mysqli->close();
	
	if(DEBUG){var_export($_SESSION["SELECTOR_ALUMNO"]);}
		header("location: $url");
	}
	else
	{header("location: $url_destino");}
?>