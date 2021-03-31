<?php
session_start();
define("DEBUG", false);
/////////////////////////////////////////////
if(isset($_SESSION["BIBLIOTECA"]))
{ if(DEBUG){echo"BORRANDO SESSION...<br>";} unset($_SESSION["BIBLIOTECA"]);}
else
{
	if(DEBUG){echo"Sin Session...<br>";}
}
///////////////////////////
if(isset($_SESSION["SELECTOR_ALUMNO_BIBLIOTECA"]))
{ if(DEBUG){echo"BORRANDO SESSION Alumno...<br>";} unset($_SESSION["SELECTOR_ALUMNO_BIBLIOTECA"]);}
else
{
	if(DEBUG){echo"Sin Session Alumno...<br>";}
}

/////////////////////////////////////////////
if($_GET)
{
	if(isset($_GET["destino"]))
	{ $destino=$_GET["destino"];}
	else
	{ $destino="biblioteca";}
	
	switch($destino)
	{
		case"biblioteca":
			$url="../menu_biblioteca.php";
			break;
		default:
			$url="../menu_biblioteca.php";
	}
	
	if(DEBUG){ echo"DESTINO: $url";}
	else{header("location: $url");}
}
else
{header("location: ../menu_biblioteca.php");}
?>