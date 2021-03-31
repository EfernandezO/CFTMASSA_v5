<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", true);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Gestion_alumnos_BETA_v1");
	$O->PERMITIR_ACCESO_USUARIO();
	//--------------FIN CLASS_okalis---------------//	
	
	require("../../../funciones/class_ALUMNO.php");
	$ALUMNO=new ALUMNO();
	$ALUMNO->SetDebug(DEBUG);
	echo "RUT ".$ALUMNO->getRut()."<br>";
	
	foreach($ALUMNO->getMatriculasAlumno() as $n =>$valor){
		echo "$n -> $valor[id_carrera] $valor[yearIngresoCarrera]<br>";
	}
	var_dump($ALUMNO->getMatriculasAlumno());
	
	echo "SEDE:".$ALUMNO->getSedeActual()."<br>";
	
	echo "Jornada:".$ALUMNO->getJornadaActual()."<br>";
	
	echo "Nivel:".$ALUMNO->getNivelAcademicoActual()."<br>";

	$ALUMNO->IR_A_PERIODO(2,2018);
	
	var_dump($_SESSION["SELECTOR_ALUMNO"]);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>prueba Alumno</title>
</head>

<body>
</body>
</html>