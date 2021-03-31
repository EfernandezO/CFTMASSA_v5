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
	if(DEBUG){ var_dump($_POST);}
	$id_contrato=$_POST["id_contrato"];
	$folio_pagare=$_POST["folio_pagare"];
	//------------------------------------------------------//
	if(is_numeric($folio_pagare))
	{
		if($folio_pagare>0)
		{ $folio_pagare_ok=true;}
		else
		{ $folio_pagare_ok=false;}
	}
	else
	{ $folio_pagare_ok=false;}
	//-----------------------------------------------------//
	//----------------------------------------------------//
	if($folio_pagare_ok)
	{
		//-------------------------------------------------------------------------------------------------//
		//compruebo folio repetido
		if(DEBUG){ echo"Folio Pagare numericamente correcto<br>";}
		require("../../../../funciones/conexion_v2.php");
		mysqli_real_escape_string($conexion_mysqli,$folio_pagare);
		mysqli_real_escape_string($conexion_mysqli,$id_contrato);
		$cons="SELECT COUNT(id) FROM contratos2 WHERE folio_pagare='$folio_pagare' AND id<>'$id_contrato'";
		$sqli=$conexion_mysqli->query($cons) or die($conexion_mysqli->error);
			$D=$sqli->fetch_row();
			$numero_coincidencias=$D[0];
			if(empty($numero_coincidencias)){$numero_coincidencias=0;}
		$sqli->free();
		if(DEBUG){echo"--->$cons<br>numero coincidencias: $numero_coincidencias<br>";}
		
		if($numero_coincidencias>0)
		{
			if(DEBUG){ echo"Folio Pagare repetido<br>";}
			else{ header("location: folio_pagare_1.php?id_contrato=$id_contrato&error=2");}
		}
		else
		{
			//------------------------------------------------------------------------------------------//
			//actualizo folio pagare
			$cons_UP="UPDATE contratos2 SET folio_pagare='$folio_pagare' WHERE id='$id_contrato' LIMIT 1";	
			if(DEBUG){ echo"---> $cons_UP<br>";}
			$conexion_mysqli->query($cons_UP)or die($conexion_mysqli->error);
			//--------------------------------------------------------//
			@mysql_close($conexion);
			$conexion_mysqli->close();
			//------------------------------------------------//
			$sede=$_SESSION["USUARIO"]["sede"];
			switch($sede)
			{
				case"Talca":
					$url="../imprimibles/pagare_v2.php?id_contrato=$id_contrato";
					break;
				case"Linares":
					$url="../imprimibles/pagare_v2.php?id_contrato=$id_contrato";
					break;
			}
			
			
			if(DEBUG){ echo"URL: $url";}
			else{ header("location: $url");}
		}
	}
	else
	{
		if(DEBUG){ echo"Folio Pagare numericamente incorrecto<br>";}
		else{ header("location: folio_pagare_1.php?id_contrato=$id_contrato&error=1");}
	}
	
}
else
{header("location: ../opciones_finales.php");}
?>