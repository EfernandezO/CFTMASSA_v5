<?php
//-----------------------------------------//
	require("../../../../OKALIS/seguridad.php");
	require("../../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="inspeccion";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define("DEBUG", false);
if($_POST)
{
	include("../../../../../funciones/conexion.php");
	if(DEBUG){var_export($_POST);}
	$id_presupuesto=$_POST["id_presupuesto"];
	$movimiento=$_POST["movimiento"];
	$item=$_POST["item"];
	$valor=$_POST["valor"];
	$forma_pago=$_POST["forma_pago"];
	$glosa=mysql_real_escape_string($_POST["glosa"]);
	
	if((is_numeric($id_presupuesto))and(is_numeric($valor)))
	{
		$cons_up="UPDATE presupuesto SET movimiento='$movimiento', item='$item', valor='$valor',forma_pago='$forma_pago', glosa='$glosa' WHERE id='$id_presupuesto' LIMIT 1";
		if(DEBUG){echo"<br>-->$cons_up <br>";}
		else
		{
			if(mysql_query($cons_up))
			{ $error=4;}
			else
			{ $error=5;}
		}
	}
	else
	{ $error=5;}
	mysql_close($conexion);
	if(!DEBUG){header("location: ../presupuesto_main.php?error=$error");}
}
else
{ header("location: ../presupuesto_main.php");}
?>