<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Gestion_proveedores_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
/////////////////////////////
if($_POST)
{
	$continuar=true;
	$error="PG";
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/VX.php");
	require("../../../../funciones/funciones_varias.php");
	require("../../../../funciones/funcion.php");
	if(DEBUG){ var_dump($_POST);}
	//---------------------------------------------------//
	$proveedor_rut=mysqli_real_escape_string($conexion_mysqli, $_POST["proveedor_rut"]);
	$proveedor_razon_social=mysqli_real_escape_string($conexion_mysqli, $_POST["proveedor_razon_social"]);
	$proveedor_direccion=mysqli_real_escape_string($conexion_mysqli, $_POST["proveedor_direccion"]);
	$proveedor_ciudad=mysqli_real_escape_string($conexion_mysqli, $_POST["proveedor_ciudad"]);
	$proveedor_telefono=mysqli_real_escape_string($conexion_mysqli, $_POST["proveedor_telefono"]);
	$modo=mysqli_real_escape_string($conexion_mysqli, $_POST["modo"]);
	///----------------------------------------------------///
	$proveedor_rut=str_inde(str_replace(".","",$proveedor_rut));
	$rut_ok=RUT_OK($proveedor_rut);
	//	verificacion de rut
	if((!empty($proveedor_rut))and($rut_ok))
	{
		$proveedor_rut=strtoupper($proveedor_rut);
		$array_rut_proveedor=explode("-",$proveedor_rut);
			
			$dv_correcto_rut_proveedor=validar_rut($array_rut_proveedor[0]);
		if($array_rut_proveedor[1]==$dv_correcto_rut_proveedor)
		{ $rut_correcto=true; if(DEBUG){ echo"Rut OK<br>";}}
		else
		{ $rut_correcto=false; if(DEBUG){ echo"Rut Incorrecto<br>";}}	
	}
	else
	{$rut_correcto=false; if(DEBUG){ echo"Rut Vacio<br>";}}
	//-------------------------------------------------------------------//
	if($rut_correcto)
	{
		$cons="SELECT COUNT(id_proveedor) FROM proveedores WHERE rut='$proveedor_rut'";
		$sql=$conexion_mysqli->query($cons);
		$DCP=$sql->fetch_row();
		$num_proveedores_coincidentes=$DCP[0];
		if(empty($num_proveedores_coincidentes)){$num_proveedores_coincidentes=0;}
		if($num_proveedores_coincidentes>0){ $ya_existe_proveedor=true;}
		else{ $ya_existe_proveedor=false;}
		$sql->free();
		if(DEBUG){ echo"$cons<br>num proveedores coincidentes: $num_proveedores_coincidentes<br>";}
		
		if(!$ya_existe_proveedor)
		{
			if(empty($proveedor_razon_social)){$continuar=false; $error="3";}
			if(empty($proveedor_direccion)){$continuar=false; $error="3";}
			if(empty($proveedor_ciudad)){$continuar=false; $error="3";}
			
		}
		else
		{ $continuar=false; $error="2";}
	}
	else
	{$continuar=false;}
	//------------------------------------------------------------------//
	if($continuar)
	{
		if(DEBUG){ echo"Se Puede Continuar... :D<br>";}
		$campos="rut, razon_social, ciudad, direccion, telefono";
		$valores="'$proveedor_rut', '$proveedor_razon_social', '$proveedor_ciudad', '$proveedor_direccion', '$proveedor_telefono'";
		$cons_IN_P="INSERT INTO proveedores ($campos) VALUES ($valores)";
		if(DEBUG){ echo"Guardar Proveedor<br>-> $cons_IN_P<br>"; $proveedor_id="p_1";}
		else
		{ 
			if($conexion_mysqli->query($cons_IN_P))
			{ 
				$error=0;
				$evento="Graba Proveedor Manualmente Rut: $proveedor_rut";
				REGISTRA_EVENTO($evento);
			}
			else
			{ $error=1; echo "ERROR:".$conexion_mysqli->error;}
		}
	}
	else
	{if(DEBUG){ echo"NO Se Puede Continuar... :(<br>";} $error=4;}
	//--------------------------------------------------//
	switch($modo)
	{
		case"normal":
			$url="../listar_proveedores.php?error=$error";
			break;
		case"modal":
			$url="nvo_proveedor_3.php?error=$error";
			break;
	}
	if(DEBUG){ echo"ERROR: $error<br>URL: $url<br>";}
	else{header("location: $url");}
	//-------------------------------------------------//
	$conexion_mysqli->close();
	@mysql_close($conexion);
	
}
else
{ echo"Sin Datos<br>";}
//------------------------------------------------------------------------//
?>