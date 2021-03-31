<?php
$url="../index.php";
if($_GET)
{
	$destino=$_GET["url"];
}
session_start();
if(isset($_SESSION["CUOTA"]))
{
	unset($_SESSION["CUOTA"]);
}
switch ($destino)
{
	case"menu_principal":
		$url="../index.php";
		break;
}
header("location: $url");
?>