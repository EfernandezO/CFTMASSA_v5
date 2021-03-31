<?php
//--------------CLASS_okalis------------------//
require("../../../../OKALIS/class_OKALIS_v1.php");
define("DEBUG", false);
$O=new OKALIS();
$O->DEBUG=DEBUG;
$O->ruta_conexion="../../../../../funciones/";
$O->clave_del_archivo=md5("Docentes->estudioTrabajo");
$O->PERMITIR_ACCESO_USUARIO();
	
if((isset($_GET["E_id"]))and(isset($_GET["id_funcionario"])))
{
	$E_id=base64_decode($_GET["E_id"]);
	$id_funcionario=base64_decode($_GET["id_funcionario"]);
	
	include("../../../../../funciones/conexion_v2.php");
	
	$E_id=mysqli_real_escape_string($conexion_mysqli, $E_id);
	$id_funcionario=mysqli_real_escape_string($conexion_mysqli, $id_funcionario);
	
	$cons_UP="UPDATE personal_registro_estudios SET archivo='NULL' WHERE id='$E_id' AND id_funcionario='$id_funcionario' LIMIT 1";
	if(DEBUG){ echo" $cons_UP<br>";}
	else{ $conexion_mysqli->query($cons_UP)or die($conexion_mysqli->error);}
	$error="C3";
	
	if(DEBUG){ echo"FIN<br>";}
	else{  header("location: carga_final.php?error=$error");}
	$conexion_mysqli->close();
}
else
{
	if(DEBUG){ echo"Sin Datos";}
	else{ header("location: carga_archivo_1.php");}
}
	
?>