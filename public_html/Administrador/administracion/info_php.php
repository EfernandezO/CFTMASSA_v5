<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	
phpinfo();
?>