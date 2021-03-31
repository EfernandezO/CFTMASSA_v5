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
if($_POST)
{
	include("../../../../funciones/conexion_v2.php");
	

	///////////////////Datos cta.cte///////////////////////
	$id_cta=mysql_real_escape_string($_POST["id_cta"]);
	$banco=ucwords(strtolower(mysql_real_escape_string($_POST["banco"])));
	$cta_cte=mysql_real_escape_string($_POST["cta_cte"]);
	$titular=ucwords(strtolower(mysql_real_escape_string($_POST["titular"])));
	//////////////////////////////////////////////////////
	
	//actualizo
	if(is_numeric($id_cta))
	{
		$campo_valor="titular='$titular', banco='$banco', num_cuenta='$cta_cte'";
		$consU="UPDATE cuenta_corriente SET $campo_valor WHERE id='$id_cta' LIMIT 1";
		if(!mysql_query($consU))
		{
			$msj=mysql_error();
			$error=4;
		}
		else
		{
			$error=6;
			////////////REGISTRA EVENTO////////////////////
				include("../../../../funciones/VX.php");
				$evento="Edita Cta. Cte ID-> $id_cta";
				REGISTRA_EVENTO($evento);
				///////////////////////////////////////////////
		}
		//echo"$consU<br>";
	}
	else
	{
		$error=5;
	}
	header("location: ../listador.php?error=$error&msj=$msj");
}
else
{
	header("location: ../index.php");
}
?>