<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//
if($_GET)
{
	$id=$_GET["id"];
	//echo "$id<br>";
	if(is_numeric($id))
	{
		include("../../../../funciones/conexion_v2.php");
		$consD1="DELETE FROM cuenta_corriente WHERE id='$id' LIMIT 1";
		
		if(mysql_query($consD1))
		{
			$error=3;//todo bien cta eliminado
			////////////REGISTRA EVENTO////////////////////
				include("../../../../funciones/VX.php");
				$evento="Elimina Cta. Cte ID-> $id";
				REGISTRA_EVENTO($evento);
			///////////////////////////////////////////////
		}
		else
		{
			$msj= mysql_error();
			$error=4;
			//falla consulta
		}
		mysql_close($conexion);
	}
	else
	{
		$error=5;
		//id invalido
	}
	$url="../listador.php?error=$error&msj=$msj";
	header("location: $url");
}
else
{
	header("location: ../ilistador.php");
}
?>