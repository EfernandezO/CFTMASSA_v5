<?php
 //-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
//-----------------------------------------//
?>
<?php
	$url_destino="HALL/index.php";
if(isset($_SESSION["SELECTOR_ALUMNO"]))
{unset($_SESSION["SELECTOR_ALUMNO"]);}
header("location: $url_destino");
?>