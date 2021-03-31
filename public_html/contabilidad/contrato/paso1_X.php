<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Matriculas_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
 if($_POST)
 {
	 require("../../../funciones/funciones_sistema.php");
	 if(DEBUG){var_export($_POST);}
	 
	 $id_carrera=$_POST["carrera"];
	 $carrera=NOMBRE_CARRERA($id_carrera);
		
	$_SESSION["FINANZAS"]["id_carrera"]=$id_carrera;
	$_SESSION["FINANZAS"]["carrera_alumno"]=$carrera;
	//////////////////////////////////////////////////////////////
	$_SESSION["FINANZAS"]["lugar_contrato"]=$_POST["lugar_contrato"];
	$_SESSION["FINANZAS"]["fecha_inicio"]=$_POST["fecha_inicio"];
	$_SESSION["FINANZAS"]["fecha_fin"]=$_POST["fecha_fin"];
	$_SESSION["FINANZAS"]["ingresoCarrera"]=$_POST["ingresoCarrera"];
	
	$_SESSION["FINANZAS"]["arancel"]=$_POST["arancel"];
	$_SESSION["FINANZAS"]["year_estudio"]=$_POST["year_estudio"];
	//echo $_SESSION["FINANZAS"]["year_estudio"];
	$_SESSION["FINANZAS"]["matricula"]=$_POST["matricula"];
	$_SESSION["FINANZAS"]["matricula_total"]=$_POST["matricula"];
	$_SESSION["FINANZAS"]["semestre"]=$_POST["semestre"];
	/////////////////////////////////////////V_2/
	$_SESSION["FINANZAS"]["vigencia_cuotas"]=$_POST["vigencia_cuota"];
	$_SESSION["FINANZAS"]["arancel_anual"]=$_POST["arancel_anual"];
	
	
	//$_SESSION["FINANZAS"]["estacion_retiro"]=$_POST["estacion_retiro"];
	$_SESSION["FINANZAS"]["nivel"]=$_POST["nivel"];
	$_SESSION["FINANZAS"]["jornada"]=$_POST["jornada"];
	$_SESSION["FINANZAS"]["grupo"]=$_POST["grupo"];//agregado
	$_SESSION["FINANZAS"]["rut_apo"]=strip_tags($_POST["rut_apo"]);
	$_SESSION["FINANZAS"]["nombreC_apo"]=strip_tags($_POST["nombreC_apo"]);
	$_SESSION["FINANZAS"]["nombreC_apo"]=ucwords(strtolower($_SESSION["FINANZAS"]["nombreC_apo"]));
	
	$_SESSION["FINANZAS"]["direccion_apo"]=strip_tags($_POST["direccion_apo"]);
	$_SESSION["FINANZAS"]["direccion_apo"]=ucwords(strtolower($_SESSION["FINANZAS"]["direccion_apo"]));
	$_SESSION["FINANZAS"]["ciudad_apo"]=strip_tags($_POST["ciudad_apo"]);
	$_SESSION["FINANZAS"]["ciudad_apo"]=ucwords(strtolower($_SESSION["FINANZAS"]["ciudad_apo"]));
	$_SESSION["FINANZAS"]["id_alumno"]=$_POST["id_alumno"];
	$_SESSION["FINANZAS"]["paga_letra"]=$_POST["paga_letra"];
	$_SESSION["FINANZAS"]["sostenedor"]=$_POST["sostenedor"];
	
	//si el sostenedor es otro guardo su nombre sino lo desecho si cambio
	if($_SESSION["FINANZAS"]["sostenedor"]=="otro")
	{
		$_SESSION["FINANZAS"]["sostenedor_nombre"]=strip_tags($_POST["sostenedor_nombre"]);
		$_SESSION["FINANZAS"]["sostenedor_nombre"]=ucwords(strtolower($_SESSION["FINANZAS"]["sostenedor_nombre"]));
		$_SESSION["FINANZAS"]["sostenedor_nombre"]=strip_tags($_POST["sostenedor_rut"]);
	}
	else
	{
		if(isset($_SESSION["FINANZAS"]["sostenedor_nombre"]))
		{
			unset($_SESSION["FINANZAS"]["sostenedor_nombre"]);
		}
		if(isset($_SESSION["FINANZAS"]["sostenedor_rut"]))
		{
			unset($_SESSION["FINANZAS"]["sostenedor_rut"]);
		}
	}
	
	if(DEBUG){var_dump($_SESSION["FINANZAS"]);}
	else
	{
		$_SESSION["FINANZAS"]["paso1"]=true;
		header("location: paso2c.php");
	}
 }
 else
 {
 	if(DEBUG){ echo"NO POST<br>";}
	else{header("location: paso1.php");}
 }
 ?>