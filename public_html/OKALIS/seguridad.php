<?php
session_start();
//COMPRUEBA QUE EL USUARIO ESTA AUTENTIFICADO
//comprueba que se halla iniciado session bajo este sistema
//nombre del actual sistema
$SISTEMA_ACTUAL="7fc7951c34b41d0423d1714ff3a51972";
$graba_cambio_de_condicion=true;
$url_error="http://intranet.cftmassachusetts.cl/OKALIS/msj_error/error.php?s=caduca";

if(isset($_SESSION["USUARIO"]))
{
	if(!$_SESSION["USUARIO"]["autentificado"])
	{
		@session_destroy();
		header("Location: $url_error");
		exit();
	}
	else
	{
		//var_dump($_SESSION);
		$nombre_session_autentificacion=$_SESSION["USUARIO"]["session_autorizacion"];
		$valor_session_autorizacion=$_SESSION["SISTEMA"][$nombre_session_autentificacion];
		if($valor_session_autorizacion===$SISTEMA_ACTUAL)
		{}
		else
		{
			@session_destroy();
			header("Location: $url_error");
			exit();
		}
	}
}
else
{
	//si no existe, envio a la página de autentificacion
	header("Location: $url_error");
	//header("Location: ../SC/error.htm");
	//ademas salgo de este script
	exit();
}	
?>