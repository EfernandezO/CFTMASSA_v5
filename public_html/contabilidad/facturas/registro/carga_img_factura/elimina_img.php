<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("gestion_facturas_v1.2");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_GET)
{
	$id_img=base64_decode($_GET["id_img"]);
	$id_factura=base64_decode($_GET["id_factura"]);
	
	if(DEBUG){ var_dump($_GET);}
	if(is_numeric($id_img))
	{
		require("../../../../../funciones/conexion_v2.php");
		require("../../../../../funciones/VX.php");
		$path='../../../../CONTENEDOR_GLOBAL/facturas/';
		$consB="SELECT archivo FROM facturas_imagenes WHERE id='$id_img' LIMIT 1";
		$sql=$conexion_mysqli->query($consB)or die($conexion_mysqli->error);
		$NP=$sql->fetch_assoc();
		$ruta_archivo=$path.$NP["archivo"];
		$sql->free();
		
		if(DEBUG){ echo"RUTA ARCHIVO: $ruta_archivo<br>$consB<br>";}
		
		$cons_D="DELETE FROM facturas_imagenes WHERE id='$id_img' LIMIT 1";
		if(DEBUG){echo"$cons_D<br>";}
		else
		{
			if($conexion_mysqli->query($cons_D))
			{
				$evento="Elimina imagen de factura id_factura: $id_factura";
				//borro archivo si existe
				if(file_exists($ruta_archivo))
				{
					$evento.=" [Archivo eliminado]";
					@unlink($ruta_archivo);
				}
				$error=5;
				//todo bien
				REGISTRA_EVENTO($evento);
				
			}
			else
			{
				//error en consulta
				$error=4;
			}
		}
	
		@mysql_close($conexion);	
		$conexion_mysqli->close();
		
		if(DEBUG){ echo"FIN<br>";}else{header("location: graba_bbdd_final.php?error=$error&id_factura=$id_factura");}
	}
	else
	{
		//id invalido
		if(DEBUG){ echo"id_invalido<br>";}
		else{header("location: graba_bbdd_final.php?error=3&id_factura=$id_factura");}
	}
}
else
{
	if(DEBUG){ echo"Sin GET<br>";}
	else{header("location: ../ver/ver_factura.php");}
}
?>