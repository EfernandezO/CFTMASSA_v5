<?php
include ("../../SC/seguridad.php");
include ("../../SC/privilegio7.php");

if(($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])and($_POST))
{
		 $_SESSION["AUX_CERTIFICADO"]["auxfirma"]= $_POST["firma"];
		 $_SESSION["AUX_CERTIFICADO"]["auxnivel"]=$_POST["nivel"];
		 $_SESSION["AUX_CERTIFICADO"]["auxpresentado"]=$_POST["presentado"];
		 
	     header ("Location: certificado_alumno_regular.php");
}
else
{
	header("location: ../../buscador_alumno_BETA/HALL/index.php");
} 

?>