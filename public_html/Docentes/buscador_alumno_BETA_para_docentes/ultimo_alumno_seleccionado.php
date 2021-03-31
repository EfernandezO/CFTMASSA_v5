<?php
 //-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//
$url_destino="HALL/index.php";
if(DEBUG){ var_export($_SESSION["ULTIMO_ALUMNO"]); echo"<br><br>";}
if(isset($_SESSION["ULTIMO_ALUMNO"]["ACTIVO"]))
{
	if($_SESSION["ULTIMO_ALUMNO"]["ACTIVO"])
	{
		$id_alumno=$_SESSION["ULTIMO_ALUMNO"]["id"];
		$sede=$_SESSION["ULTIMO_ALUMNO"]["sede"];
		
		if(DEBUG){ echo"RE_ seleccionanado ALUMNO...$id_alumno - $sede<br>";}
		include("../../../funciones/conexion.php");
		$cons="SELECT * FROM alumno WHERE id='$id_alumno' AND sede='$sede' LIMIT 1";
		if(DEBUG){ echo"$cons<br>";}
		$sql=mysql_query($cons)or die("Datos alumno ".mysql_error());
		$DA=mysql_fetch_assoc($sql);
			$_SESSION["SELECTOR_ALUMNO"]["id"]=$id_alumno;
			$_SESSION["SELECTOR_ALUMNO"]["rut"]=$DA["rut"];
			$_SESSION["SELECTOR_ALUMNO"]["nombre"]=$DA["nombre"];
			$apellido_old=$DA["apellido"];
			$apellido_P=$DA["apellido_P"];
			$apellido_M=$DA["apellido_M"];
			$apellido_new=$apellido_P." ".$apellido_M;
			if($apellido_new==" ")
			{ $apellido_label=$apellido_old;}
			else
			{ $apellido_label=$apellido_new;}
			$ingreso=$DA["ingreso"];
			$egreso=$DA["year_egreso"];
			$_SESSION["SELECTOR_ALUMNO"]["id_carrera"]=$DA["id_carrera"];
			$_SESSION["SELECTOR_ALUMNO"]["carrera"]=$DA["carrera"];
			$_SESSION["SELECTOR_ALUMNO"]["situacion"]=$DA["situacion"];
			$_SESSION["SELECTOR_ALUMNO"]["sede"]=$DA["sede"];
			$_SESSION["SELECTOR_ALUMNO"]["jornada"]=$DA["jornada"];
			$_SESSION["SELECTOR_ALUMNO"]["nivel"]=$DA["nivel"];
			$_SESSION["SELECTOR_ALUMNO"]["sexo"]=$DA["sexo"];
			$apellido_label=$apellido_label;
			$_SESSION["SELECTOR_ALUMNO"]["apellido"]=$apellido_label;
			$_SESSION["SELECTOR_ALUMNO"]["ingreso"]=$ingreso;
			$_SESSION["SELECTOR_ALUMNO"]["egreso"]=$egreso;
			$_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]=true;
	}
}
if(DEBUG){ echo" URL: $url_destino<br>";}
else{ header("location: $url_destino");}
?>