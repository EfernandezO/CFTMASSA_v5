<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define("DEBUG", false);
//-----------------------------------------//

if(isset($_GET["id_cheque"]))
{
	$id_cheque=$_GET["id_cheque"];
	if(is_numeric($id_cheque))
	{ $continuar=true;}
	else
	{ $continuar=false;}
}
else
{ $continuar=false;}


if($continuar)
{
	$error="debug";
	if(DEBUG){ echo"Eliminar cheque id_cheque: $id_cheque<br>";}
	require("../../../../funciones/conexion_v2.php");
	
	$cons_DELCH="DELETE FROM registro_cheques WHERE id='$id_cheque' LIMIT 1";
	if(DEBUG){ echo"---> $cons_DELCH<br>";}
	else
	{
		if($conexion_mysqli->query($cons_DELCH))
		{ 
			$error="CH2";
			include("../../../../funciones/VX.php");
			$evento="Elimina Cheque id_cheque:$id_cheque";
			REGISTRA_EVENTO($evento);
		}
		else
		{ $error="CH3";}
	}
	
	
	@mysql_close($conexion);
	$conexion_mysqli->close();
	
	$URL="elimina_cheque_2.php?error=$error";
	
	if(DEBUG){ echo"URL: $URL<br>";}
	else{ header("location: $URL");}
}
else
{ echo"Sin Datos :(<br>";}
?>