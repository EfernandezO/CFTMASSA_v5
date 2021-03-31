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
if(isset($_SESSION["SELECTOR_ALUMNO"]))
{ unset($_SESSION["SELECTOR_ALUMNO"]);}

$privilegio=$_SESSION["USUARIO"]["privilegio"];
switch($privilegio)
{
	default:
		$url="../okdocente.php";
}
header("location: $url");
?>