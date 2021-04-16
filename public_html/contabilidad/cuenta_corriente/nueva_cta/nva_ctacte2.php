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
	
	require("../../../../funciones/conexion_v2.php");
	
	$banco=ucwords(strtolower(mysqli_real_escape_string($conexion_mysqli, $_POST["banco"])));
	$cta_cte=mysqli_real_escape_string($conexion_mysqli, $_POST["cta_cte"]);
	$titular=ucwords(strtolower(mysqli_real_escape_string($conexion_mysqli, $_POST["titular"])));
	
	$campos="titular, banco, num_cuenta";
	$valores="'$titular', '$banco', '$cta_cte'";
	
	$cons_B="SELECT COUNT(id) FROM cuenta_corriente WHERE num_cuenta='$cta_cte' AND banco='$banco'";
	if(DEBUG){echo"$cons_B<br>";}
	
	$sql=$conexion_mysqli->query($cons_B)or die($conexion_mysqli->error);
	$D=$sql->fetch_row();
	$coincidencias=$D[0];
	$sql->free();
	if(DEBUG){echo"---- $coincidencias<br>";}
	
	if(!$coincidencias>0)
	{
		$cons="INSERT INTO cuenta_corriente ($campos) VALUES ($valores)";
		if(DEBUG){echo"--> $cons<br>";}
		else
		{
			if($conexion_mysqli->query($cons))
			{
					////////////REGISTRA EVENTO////////////////////
					include("../../../../funciones/VX.php");
					$evento="Nueva Cta. Cte -> $cta_cte";
					REGISTRA_EVENTO($evento);
					///////////////////////////////////////////////
			}
			else
			{$error=1;}
		}
	
	}
	else
	{$error=2;}
	$conexion_mysqli->close();
	
	$url="../listador_cuentas.php?error=$error";
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
}	
else
{
	header("location: ../listador_cuentas.php");
}
?>