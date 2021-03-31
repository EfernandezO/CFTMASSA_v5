<?php
//-----------------------------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(true);
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Planificaciones->Editar");
	$O->PERMITIR_ACCESO_USUARIO();
//-----------------------------------------//
if($_GET)
{
	$error="debug";
	require("../../../../funciones/conexion_v2.php");
	
	if(DEBUG){ var_dump($_GET);}
	
	$id_contenido=mysqli_real_escape_string($conexion_mysqli, $_GET["id_contenido"]);
	$id_contenidoMain=mysqli_real_escape_string($conexion_mysqli, $_GET["id_contenidoMain"]);
	
	if((is_numeric($id_contenido))and($id_contenido>0))
	{
		$cons_D="DELETE FROM contenidosDetalle WHERE id_contenido='$id_contenido' AND idContenidoMain='$id_contenidoMain' LIMIT 1";
		if(DEBUG){ echo"---> $cons_D<br>";}
		else
		{ 
			$conexion_mysqli->query($cons_D)or die($conexion_mysqli->error); 
			$error="PE0";
			require("../../../../funciones/VX.php");
			$evento="Elimina Registro de contenido id_contenido: $id_contenido id_contenidoMain:$id_contenidoMain";
			REGISTRA_EVENTO($evento);
		}
	}
	
	$conexion_mysqli->close();
	
	$url="../ver_contenidos.php?id_contenidoMain=".base64_encode($id_contenidoMain)."&error=$error";
	
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
}