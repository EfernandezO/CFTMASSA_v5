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
if($_GET)
{
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	require("../../../funciones/conexion_v2.php");
	$F_id=$_GET["fid"];
	if(is_numeric($F_id))
	{
		$F_con_acceso=$_GET["acceso"];
		
		switch($F_con_acceso)
		{
			case"ON":
				$con_acceso_new="OFF";
				break;
			case"OFF":
				$con_acceso_new="ON";
				break;
			default:
				$con_acceso_new="OFF";	
		}
		
		$cons_UP="UPDATE personal SET con_acceso='$con_acceso_new' WHERE id='$F_id' LIMIT 1";
		if(DEBUG)
		{ echo"--->$cons_UP<br>";}
		else
		{ 
			if($conexion_mysqli->query($cons_UP))
			{
				include("../../../funciones/VX.php");
				$evento="Cambio de Acceso a funcionario id_funcionario: $F_id Nuevo Acceso[$con_acceso_new]";
				REGISTRA_EVENTO($evento);
				
				 $descripcion="Modificacion Acceso a $con_acceso_new por usuario: $id_usuario_actual";
	 			 REGISTRO_EVENTO_FUNCIONARIO($F_id, "notificacion", $descripcion);
			}
		}
	}
	else{ if(DEBUG){ echo"FID-> Invalido<br>";}}
	
	$conexion_mysqli->close();
}
if(DEBUG){ echo"FIN<br>";}
else
{ header("location: ../lista_funcionarios.php");}
?>