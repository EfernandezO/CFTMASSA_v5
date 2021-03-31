<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Funcionarios->Edicion Datos V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

$continuar=false;
if(isset($_GET["id_funcionario"]))
{
	$id_funcionario=base64_decode($_GET["id_funcionario"]);
	if(is_numeric($id_funcionario)){ $continuar=true;}
}


if($continuar)
{
	$error="";
	if(DEBUG){ echo"Acceso: Continuar<br>id_funcionario: $id_funcionario<br>";}
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/VX.php");
	
	$cons="SELECT rut FROM personal WHERE id='$id_funcionario' LIMIT 1";
	$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$D=$sqli->fetch_assoc();
		$F_rut=$D["rut"];
	$sqli->free();
	
	if(DEBUG){ echo"Rut Actual: $F_rut<br>";}
	
	
	$clave=md5("Ma_".$F_rut);
	$cons_UP="UPDATE personal SET clave='$clave' WHERE id='$id_funcionario' LIMIT 1";
	if(DEBUG){ echo"--->$cons_UP<br>";}
	else
	{
		$evento="Restablece clave a funcionario id_funcionario: $id_funcionario";
		REGISTRA_EVENTO($evento);
		$descripcion="Clave Restablecida";
		REGISTRO_EVENTO_FUNCIONARIO($id_funcionario, "notificacion", $descripcion);
		 $conexion_mysqli->query($cons_UP)or die($conexion_mysqli->error);
		 $error="RC0";
	}
	
	$url="mdocente.php?id_fun=".base64_encode($id_funcionario)."&error=$error";
	
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
	
	
	$conexion_mysqli->close();
}
else
{ echo"No se puede Continuar<br>";}
?>