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
	
	$id_factura=base64_decode($_GET["id"]);
	if(is_numeric($id_factura))
	{
		$path="../../CONTENEDOR_F/";
		require("../../../../../funciones/conexion_v2.php");
		$cons_d="DELETE FROM facturas WHERE id='$id_factura' LIMIT 1"; 
		
		
		
		if($conexion_mysqli->query($cons_d))
		{ 
			$error=2;
			$cons_FI="SELECT * FROM facturas_imagenes WHERE id_factura='$id_factura'";
			$sqli_FI=$conexion_mysqli->query($cons_FI)or die($conexion_mysqli->error);
			$num_imagenes=$sqli_FI->num_rows;
			
			if($num_imagenes>0)
			{
				while($FI=$sqli_FI->fetch_assoc())
				{
					$nombre_archivo=$FI["archivo"];
					$archivo_FULL_SRC=$path.$nombre_archivo;
					@unlink($archivo_FULL_SRC);
					
				}
				$cons_FI="DELETE FROM facturas_imagenes WHERE id_factura='$id_factura'";
				$conexion_mysqli->query($cons_FI)or die($conexion_mysqli->error);
			}
			$sqli_FI->free();
		}
		else
		{ $error=3;}
		

		$conexion_mysqli->close();
		header("location: ../ver/ver_factura.php?error=$error");
	}
	else
	{ echo"incorrecto<br>";}
	
}
else
{ header("location: ../ver/ver_factura.php");}
?>