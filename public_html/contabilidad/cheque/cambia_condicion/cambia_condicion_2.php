<?php
   require("../../../SC/seguridad.php");
   require("../../../SC/privilegio2.php");
   define("DEBUG",false);
?>
<?php
if($_POST)
{
	include("../../../../funciones/conexion.php");
	if(DEBUG){ var_export($_POST);}
	$cod_user_activo=$_SESSION["USUARIO"]["id"];
	$condicion=$_POST["condicion"];
	$fecha_condicion=$_POST["fecha_condicion"];
	$id_cheque=$_POST["id_cheque"];
	
	$cons_UP="UPDATE registro_cheques SET condicion='$condicion', fecha_condicion='$fecha_condicion', cod_user='$cod_user_activo' WHERE id IN($id_cheque)";
	if(DEBUG){ echo"-> $cons_UP <br>";} 
	if(mysql_query($cons_UP))
	{ $error=0;}
	else
	{ 
		$error=1;
		if(DEBUG){ echo"ERROR".mysql_error()."<br>";}
	}
	mysql_close($conexion);
	$url="cambia_condicion_3.php?error=$error";
	if(DEBUG)
	{ echo"URL= $url<br>";}
	else
	{ header("location: $url");}
	
}
else
{ 
	if(DEBUG){ echo"NO POST<br>";}
    else{header("location: ../index.php");}
}  
?>