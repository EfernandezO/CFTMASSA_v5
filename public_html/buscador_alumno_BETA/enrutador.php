<?php
//--------------CLASS_okalis------------------//
	require("../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../funciones/";
	$O->clave_del_archivo=md5("Gestion_alumnos_BETA_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	//**************************************//
	$vienen_datos=false;
	$comparador=md5("GDXT".date("d-m-Y"));
	$acceso=false;
	$url_destino="../index.php";//en caso de que no tenga datos
	//**************************************//

	if($_POST)
	{
		$validador=$_POST["validador"]; 
		$vienen_datos=true; 
		if(DEBUG){var_export($_POST);} 
		$id_alumno=$_POST["hi_id_alumno"]; 
		if(isset($_POST["url"])){ $url=base64_decode($_POST["url"]);}
		else{ $url="HALL/index.php";} //si todo bien redirige aqui
	}
	
	if($_GET)
	{
		$validador=$_GET["validador"];
		$vienen_datos=true; 
		if(DEBUG){var_export($_GET);} 
		$id_alumno=$_GET["id_alumno"];
		if(isset($_GET["url"])){ $url=base64_decode($_GET["url"]);}
		else{ $url="HALL/index.php";}//si todo bien redirige aqui
	}
	//**************************************//
	if($comparador==$validador)
	{	$acceso=true;}
	//****************************************//
	
	if(($vienen_datos)and($acceso))
	{
		if(DEBUG){ echo"<br>Acceso Confirmado :D<br>";}
		
		
		require("../../funciones/conexion_v2.php");
		require("../../funciones/funciones_sistema.php");
		
		$cons="SELECT * FROM alumno WHERE id='$id_alumno' LIMIT 1";
		if(DEBUG){ echo"---> $cons<br>";}
		$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		$DA=$sqli->fetch_assoc();
			$_SESSION["SELECTOR_ALUMNO"]["id"]=$id_alumno;
			$_SESSION["SELECTOR_ALUMNO"]["rut"]=$DA["rut"];
			$_SESSION["SELECTOR_ALUMNO"]["nombre"]=$DA["nombre"];
			
			$apellido_P=$DA["apellido_P"];
			$apellido_M=$DA["apellido_M"];
			
			$apellido_new=($apellido_P." ".$apellido_M);
			
			$ingreso=$DA["ingreso"];
			$egreso=$DA["year_egreso"];
			$grupo=$DA["grupo"];
			
			$_SESSION["SELECTOR_ALUMNO"]["nivel"]=$DA["nivel"];
			$_SESSION["SELECTOR_ALUMNO"]["sexo"]=trim($DA["sexo"]);
			$_SESSION["SELECTOR_ALUMNO"]["apellido"]=$apellido_new;
			$_SESSION["SELECTOR_ALUMNO"]["ingreso"]=$ingreso;
			$_SESSION["SELECTOR_ALUMNO"]["egreso"]=$egreso;
			$_SESSION["SELECTOR_ALUMNO"]["grupo"]=$grupo;
			$_SESSION["SELECTOR_ALUMNO"]["imagen"]=$DA["imagen"];
			////////////////////////////////////////////////////
			
			require("../../funciones/class_ALUMNO.php");
			$ALUMNO=new ALUMNO($id_alumno);
			$ALUMNO->SetDebug(DEBUG);
			
			$_SESSION["SELECTOR_ALUMNO"]["nivel_academico"]=$ALUMNO->getNivelAcademicoActual();
			$_SESSION["SELECTOR_ALUMNO"]["sede"]=$ALUMNO->getSedeActual();
			$_SESSION["SELECTOR_ALUMNO"]["jornada"]=$ALUMNO->getJornadaActual();
			
			
			$_SESSION["SELECTOR_ALUMNO"]["id_carrera"]=$ALUMNO->getUltimaIdCarreraMat();
			$_SESSION["SELECTOR_ALUMNO"]["carrera"]=NOMBRE_CARRERA($ALUMNO->getUltimaIdCarreraMat());
			$_SESSION["SELECTOR_ALUMNO"]["yearIngresoCarrera"]=$ALUMNO->getUltimoYearIngresoMat();
			$_SESSION["SELECTOR_ALUMNO"]["situacion"]=$ALUMNO->getUltimaSituacionMat();
			$_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]=true;

			//registro el id_alumno para recuperarlo
			if(isset($_SESSION["ULTIMO_ALUMNO"]["id_alumno"])){$arrayUltimos=$_SESSION["ULTIMO_ALUMNO"]["id_alumno"];}
			else{$arrayUltimos=array();}
			if(!in_array($id_alumno,$arrayUltimos)){
				if(DEBUG){echo"agregar id<br>";}
				array_push($arrayUltimos,$id_alumno);
				$_SESSION["ULTIMO_ALUMNO"]["id_alumno"]=$arrayUltimos;
			}
			
			
			
		$sqli->free();
		$conexion_mysqli->close();
		
		require("../../funciones/VX.php");
		$evento="Seleccion de alumnos para gestion id_alumno: $id_alumno";
		REGISTRA_EVENTO($evento);
		
	
	if(DEBUG){var_dump($_SESSION["SELECTOR_ALUMNO"]); echo"URL: $url<br>";}
	if(DEBUG){var_dump($_SESSION["ULTIMO_ALUMNO"]); echo"<br>";}
	else{ header("location: $url");}
		
	}
	else
	{
		if(DEBUG){ echo"Sin Datos<br>";}
		else{header("location: $url_destino");}
	}
?>