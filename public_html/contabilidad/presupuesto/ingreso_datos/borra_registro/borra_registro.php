<?php
//-----------------------------------------//
	require("../../../../OKALIS/seguridad.php");
	require("../../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="inspeccion";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define("DEBUG", false);
if($_GET)
{
	$id_presupuesto=base64_decode($_GET["ID"]);
	
	if(is_numeric($id_presupuesto))
	{
		include("../../../../../funciones/conexion.php");
		$cons_D="DELETE FROM presupuesto WHERE id='$id_presupuesto' LIMIT 1";
		
		if(DEBUG){ echo"$cons_D<br>";}
		else{
			if(mysql_query($cons_D))
			{ $error=2;}
			else
			{ $error=3;}
		}	
		mysql_close($conexion);
		
	}
	header("location: ../presupuesto_main.php?error=$error");
}
else
{ header("location: ../presupuesto_main.php");}
?>