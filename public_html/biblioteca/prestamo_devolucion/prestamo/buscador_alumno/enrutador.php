<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("Bibioteca_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	//**************************************//
	$comparador=md5("GDXT".date("d-m-Y"));
	$validador=$_POST["validador"];
	$acceso=true;
	$url_destino="../../menu_biblioteca.php";//en caso de que no tenga post
	$url="../verifica_prestamo_1.php";//si todo bien redirige aqui
	//**************************************//
	if($comparador==$validador)
	{	$acceso=true;}
	if(($_POST)and($acceso))
	{
		if(DEBUG){ echo"Acceso Confirmado :D<br>";}
		if(DEBUG){var_export($_POST);}
		$id_alumno=$_POST["hi_id_alumno"];
		$id_libro=$_POST["id_libro"];
		$fecha_devolucion=$_POST["fecha_devolucion"];
		include("../../../../../funciones/conexion_v2.php");
		$cons="SELECT * FROM alumno WHERE id='$id_alumno' LIMIT 1";
		if(DEBUG){ echo"$cons<br>";}
		$sql=mysql_query($cons)or die("Datos alumno ".mysql_error());
		$DA=mysql_fetch_assoc($sql);
			$_SESSION["SELECTOR_ALUMNO_BIBLIOTECA"]["id"]=$id_alumno;
			$_SESSION["SELECTOR_ALUMNO_BIBLIOTECA"]["rut"]=$DA["rut"];
			$_SESSION["SELECTOR_ALUMNO_BIBLIOTECA"]["nombre"]=ucwords(strtolower($DA["nombre"]));
			$apellido_old=$DA["apellido"];
			$apellido_P=$DA["apellido_P"];
			$apellido_M=$DA["apellido_M"];
			$apellido_new=$apellido_P." ".$apellido_M;
			if($apellido_new==" ")
			{ $apellido_label=$apellido_old;}
			else
			{ $apellido_label=$apellido_new;}
			$ingreso=$DA["ingreso"];
			$_SESSION["SELECTOR_ALUMNO_BIBLIOTECA"]["id_carrera"]=$DA["id_carrera"];
			$_SESSION["SELECTOR_ALUMNO_BIBLIOTECA"]["carrera"]=$DA["carrera"];
			$_SESSION["SELECTOR_ALUMNO_BIBLIOTECA"]["situacion"]=$DA["situacion"];
			$_SESSION["SELECTOR_ALUMNO_BIBLIOTECA"]["sede"]=$DA["sede"];
			$_SESSION["SELECTOR_ALUMNO_BIBLIOTECA"]["jornada"]=$DA["jornada"];
			$_SESSION["SELECTOR_ALUMNO_BIBLIOTECA"]["nivel"]=$DA["nivel"];
			$_SESSION["SELECTOR_ALUMNO_BIBLIOTECA"]["sexo"]=$DA["sexo"];
			$apellido_label=ucwords(strtolower($apellido_label));
			$_SESSION["SELECTOR_ALUMNO_BIBLIOTECA"]["apellido"]=$apellido_label;
			$_SESSION["SELECTOR_ALUMNO_BIBLIOTECA"]["ingreso"]=$ingreso;
			$_SESSION["SELECTOR_ALUMNO_BIBLIOTECA"]["ACTIVO"]=true;
			$_SESSION["SELECTOR_ALUMNO_BIBLIOTECA"]["id_libro_a_prestar"]=$id_libro;
		mysql_free_result($sql);	
		mysql_close($conexion);
		
		$url.="?devolucion=$fecha_devolucion";
		if(DEBUG){ echo"URL -> $url";}
		else{ header("location: $url");}
		
	}
	else
	{header("location: $url_destino");}
?>