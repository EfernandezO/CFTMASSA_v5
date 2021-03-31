<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Solicitud->AutorizacionFinanciera");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

if($_GET)
{
	$id_solicitud=$_GET["id_solicitud"];
	
	$cod_user_activo=$_SESSION["USUARIO"]["id"];
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	$fecha_generacion=date("Y-m-d H:i:s");
	
	
	if($id_solicitud>0)
	{
		require("../../../funciones/conexion_v2.php");
		$error="SF1";
		if(DEBUG){ echo"AUTORIZAR SOLICITUD<br>";}
		$cons_solicitud="UPDATE solicitudes SET id_autorizador='$cod_user_activo', autorizado='si', tipo_autorizador='$privilegio', fecha_hora_autorizacion='$fecha_generacion', metodo_autorizacion='forzada', id_pago='0' WHERE id='$id_solicitud' LIMIT 1";
		
		if(DEBUG){ echo"--->$cons_solicitud<br>";}
		else
		{ 
			$conexion_mysqli->query($cons_solicitud);
			
			 /////Registro evento///
			 include("../../../funciones/VX.php");
			 $evento="Autoriza Forzadamente Solicitud: ($id_solicitud)";
			 REGISTRA_EVENTO($evento);
			 ///////////////////////
		}
		$conexion_mysqli->close();
	}
	else
	{ $error="SF2";}
	////////////////////////////////////////////////////
	
	$url="msj_final_autorizacion_forzada.php?error=$error";
	
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
	
}
else
{
	header("location:../../buscador_alumno_BETA/HALL/index.php");
}
?>