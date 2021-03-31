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
	
	if(DEBUG){ var_dump($_POST); echo"<br><br>";}
	if(DEBUG){ var_dump($_FILES); echo"<br>";}

$hay_POST=false;
$hay_archivo=false;

if($_POST){ $hay_POST=true;}
if(isset($_FILES["archivo"])){ $hay_archivo=true;}

if($hay_archivo and $hay_POST)
{
 	require("../../../../../funciones/conexion_v2.php");
	require("../../../../../funciones/funcion.php");
	require("../../../../../funciones/funciones_sistema.php");
	$error="debug";
	
	$ruta="../../../../CONTENEDOR_GLOBAL/facturas";
	$array_extensiones=array("pdf", "jpg");
	//------------------------------------///
	list($archivo_cargado, $nombre_archivo_new)=CARGAR_ARCHIVO($_FILES["archivo"],$ruta,"F_",$array_extensiones);
	///////////////////////////////////
	
	
	if($archivo_cargado)
	{
	
		$id_factura=$_POST["id_factura"];
		$fecha_X=$_POST["fecha_X"];
		$id_usuario_activo=$_SESSION["USUARIO"]["id"];
	
		
		//////////////
		$campos="id_factura, archivo, fecha_generacion, cod_user";
		$valores="'$id_factura', '$nombre_archivo_new', '$fecha_X', '$id_usuario_activo'";
		
		$cons="INSERT INTO facturas_imagenes ($campos) VALUES($valores)";
		if(DEBUG){ echo"--> $cons<br>";}
		else
		{
			if($conexion_mysqli->query($cons))
			{
				include("../../../../../funciones/VX.php");
				$evento="Agrega Imagen a Factura id_factura: $id_factura";
				REGISTRA_EVENTO($evento);
				$error="0";
			}
			else
			{
				if(DEBUG){ echo"Error al grabar factura en BBDD ".$conexion_mysqli->error;}
				$error="2";
			}
		}
	
	}
	else
	{$error="1";}
	
	@mysql_close($conexion);
	$conexion_mysqli->close();
	
	$url="graba_bbdd_final.php?id_factura=$id_factura&error=$error";
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
  }
  else
  { if(DEBUG){ echo"No se puede continuar<br>";}}
?>