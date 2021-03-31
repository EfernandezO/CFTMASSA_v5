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
$url_destino="HALL/index.php";
if(isset($_GET["direccion"])){ $direccion=$_GET["direccion"];}
else{ $direccion="R";}

	if(DEBUG){ var_dump($_SESSION["ULTIMO_ALUMNO"]); echo"<br><br>";}
	if(isset($_SESSION["ULTIMO_ALUMNO"]["id_alumno"]))
	{
		
		$validador=md5("GDXT".date("d-m-Y"));
		
		$id_alumnoActual=$_SESSION["SELECTOR_ALUMNO"]["id"];
		$key=array_search($id_alumnoActual, $_SESSION["ULTIMO_ALUMNO"]["id_alumno"]);
		$maxKey=(count($_SESSION["ULTIMO_ALUMNO"]["id_alumno"])-1);
		
		if(DEBUG){ echo"el id_alumno Actual: $id_alumnoActual, esta en la posicion $key / $maxKey<br>";}
		//avance o retroceso de key
		if($direccion=="R"){ $key-=1;}
		else{ $key+=1;}
		//restricciones para funcionamiento ciclico
		if($key<0){$key=$maxKey;}
		if($key>$maxKey){$key=0;}
		
		$nextIdAlumno=$_SESSION["ULTIMO_ALUMNO"]["id_alumno"][$key];
		
		if(DEBUG){ echo"direccion: $direccion<br>nueva key: $key nextIdAlumno: $nextIdAlumno<br>" ;}
		
		$url_destino="enrutador.php?id_alumno=$nextIdAlumno&validador=$validador";
		
		
	}

if(DEBUG){ echo" URL: $url_destino<br>";}
else{ header("location: $url_destino");}
?>