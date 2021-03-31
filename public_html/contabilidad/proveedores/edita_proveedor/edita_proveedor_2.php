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
if($_POST)
{
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/funciones_varias.php");
	require("../../../../funciones/funcion.php");
	require("../../../../funciones/VX.php");
	$error="PE";
	$continuar=true;
	if(DEBUG){ var_dump($_POST);}
	$proveedor_id=$_POST["proveedor_id"];
	$proveedor_rut=$_POST["proveedor_rut"];
	$proveedor_razon_social=$_POST["proveedor_razon_social"];
	$proveedor_direccion=$_POST["proveedor_direccion"];
	$proveedor_ciudad=$_POST["proveedor_ciudad"];
	$proveedor_telefono=$_POST["proveedor_telefono"];
	//-------------------------------------------------------//
	///----------------------------------------------------///
	//	verificacion de rut
	if(!empty($proveedor_rut))
	{
		$proveedor_rut=str_inde(str_replace(".","",$proveedor_rut));
		$proveedor_rut=strtoupper($proveedor_rut);
		if(strpos($proveedor_rut, "-"))
		{
			$array_rut_proveedor=explode("-",$proveedor_rut);
				
				$dv_correcto_rut_proveedor=validar_rut($array_rut_proveedor[0]);
			if($array_rut_proveedor[1]==$dv_correcto_rut_proveedor)
			{ $rut_correcto=true; if(DEBUG){ echo"Rut OK<br>";}}
			else
			{ $rut_correcto=false; if(DEBUG){ echo"Rut Incorrecto<br>";}}	
		}
		else{ $rut_correcto=false; if(DEBUG){ echo"Rut Incorrecto sin caracter (-)<br>";}}
	}
	else
	{$rut_correcto=false; if(DEBUG){ echo"Rut Vacio<br>";}}
	//-------------------------------------------------------------------//
	if($rut_correcto)
	{
		$cons="SELECT COUNT(id_proveedor) FROM proveedores WHERE rut='$proveedor_rut' AND id_proveedor<>'$proveedor_id'";
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
			if(empty($proveedor_razon_social)){$continuar=false; $error.="3";}
			if(empty($proveedor_direccion)){$continuar=false; $error.="3";}
			if(empty($proveedor_ciudad)){$continuar=false; $error.="3";}
			
		}
		else
		{ $continuar=false; $error.="2";}
	}
	else
	{$continuar=false;}
	//------------------------------------------------------------------//
	if($continuar)
	{
		if(DEBUG){ echo"Se Editar Continuar... :D<br>";}
		$campos_valores="rut='$proveedor_rut', razon_social='$proveedor_razon_social', ciudad='$proveedor_ciudad', direccion='$proveedor_direccion', telefono='$proveedor_telefono'";
		
		$cons_UP_P="UPDATE proveedores SET $campos_valores WHERE id_proveedor='$proveedor_id' LIMIT 1";
		if(DEBUG){ echo"Edita Proveedor<br>-> $cons_UP_P<br>"; }
		else
		{ 
			if($conexion_mysqli->query($cons_UP_P))
			{ 
				$error.=0;
				$evento="Edita Proveedor id_proveedor: $proveedor_id rut_proveedor: $proveedor_rut";
				REGISTRA_EVENTO($evento);
			}
			else
			{ $error.=1;}
		}
	}
	else
	{if(DEBUG){ echo"NO Se Puede Continuar... :(<br>";}}
	
	if($error=="PE0")
	{ $url="../listar_proveedores.php?error=$error";}
	else{ $url="edita_proveedor_1.php?error=$error&id_proveedor=$proveedor_id";}
	
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
	//-------------------------------------------------------------------------------------------------------------//
}
else
{
	if(DEBUG){ echo"sin datos<br>";}
	else{ header("location: ../listar_proveedores.php");}
}
?>