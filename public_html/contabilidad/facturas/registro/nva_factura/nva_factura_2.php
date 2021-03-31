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
	
if($_POST)	
{
	$evento="";
	$descripcion_error="";
	require("../../../../../funciones/conexion_v2.php");
	require("../../../../../funciones/funcion.php");
	require("../../../../../funciones/VX.php");
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	$sede_usuario_actual=$_SESSION["USUARIO"]["sede"];
	$fecha_actual=date("Y-m-d");
	$error="F";
	$grabar_F=true;
		if(DEBUG){var_dump($_POST);}
		//proveedor
		$proveedor_id=mysqli_real_escape_string($conexion_mysqli, $_POST["proveedor_id"]);
		$proveedor_rut=mysqli_real_escape_string($conexion_mysqli, $_POST["proveedor_rut"]);
		$proveedor_razon_social=mysqli_real_escape_string($conexion_mysqli, str_inde($_POST["proveedor_razon_social"]));
		$proveedor_direccion=mysqli_real_escape_string($conexion_mysqli, str_inde($_POST["proveedor_direccion"]));
		$proveedor_ciudad=mysqli_real_escape_string($conexion_mysqli, str_inde($_POST["proveedor_ciudad"]));
		//factura
		$fsede=$_POST["fsede"];
		$cod_factura=$_POST["cod_factura"];
		$comentario=$_POST["comentario"];
		$fecha_ingreso=$_POST["fecha_ingreso"];
		$fecha_vencimiento=$_POST["fecha_vencimiento"];
		$movimiento=$_POST["movimiento"];
		$condicion=$_POST["condicion"];
		$valor=mysqli_real_escape_string($conexion_mysqli, $_POST["valor"]);
		$orden_compra=mysqli_real_escape_string($conexion_mysqli, $_POST["orden_compra"]);
		
		$modo=mysqli_real_escape_string($conexion_mysqli, $_POST["modo"]);
		
		switch($condicion)
		{
			case"pendiente":
				$saldo=$valor;
				$abono=0;
				break;
			case"cancelada":
				$saldo=0;
				$abono=$valor;
				break;
		}
		
		
		//verificar hay oc
		if($orden_compra>0)
		{ $hay_oc=true;}
		else
		{ $hay_oc=false;}
		///--------------------------------------------------------//
		//guardar proveedor
		if($proveedor_id>0)
		{ $guardar_proveedor=false;}
		else
		{ $guardar_proveedor=true;}
		
		if($guardar_proveedor)
		{
			$campos="rut, razon_social, ciudad, direccion";
			$proveedor_rut=str_replace(".","",$proveedor_rut);
			$valores="'$proveedor_rut', '$proveedor_razon_social', '$proveedor_ciudad', '$proveedor_direccion'";
			$cons_IN_P="INSERT INTO proveedores ($campos) VALUES ($valores)";
			if(DEBUG){ echo"Guardar Proveedor<br>-> $cons_IN_P<br>"; $proveedor_id="p_1";}
			else{ $conexion_mysqli->query($cons_IN_P)or die("Guarda Proveedor ".$conexion_mysqli->error); $proveedor_id=$conexion_mysqli->insert_id;}
			$evento.="Graba Proveedor automaticamente id_proveedor: $proveedor_id ";
		}
		else
		{ if(DEBUG){ echo"-->Proveedor ya Existe No Guardar<br>";}}
		//-----------------------------------------------------------------//
		if($grabar_F)
		{
			$evento=" Crea Factura de proveedor id_proveedor: $proveedor_id rut_proveedor: $proveedor_rut";
			 REGISTRA_EVENTO($evento);
			//------------------------------------------------------------//
			$campos="cod_factura, ";
			if($hay_oc){$campos.="id_oc, ";}
			$campos.="id_proveedor, comentario, fecha_ingreso, fecha_vencimiento, valor, saldo, abono, condicion, movimiento, sede, id_user";
			$valores="'$cod_factura', ";
			if($hay_oc){$valores.="'$orden_compra', ";}
			$valores.="'$proveedor_id', '$comentario', '$fecha_ingreso', '$fecha_vencimiento', '$valor', '$saldo', '$abono', '$condicion', '$movimiento', '$fsede', '$id_usuario_actual'";
			$cons_F="INSERT INTO facturas ($campos) VALUES($valores)";
			if(DEBUG){ echo"Graba FACTURA<br>---> $cons_F<br>"; $id_F="f_1";}
			else
			{ 
				if($conexion_mysqli->query($cons_F))
				{ $error.="0";}
				else
				{ $error.="1"; $descripcion_error=$conexion_mysqli->error;}
			    $id_F=$conexion_mysqli->insert_id;
			}
			//------------------------------------------------------------//
		}
		$conexion_mysqli->close();
		///redireccion
		switch($modo)
		{
			case"normal":
				$url="../ver/ver_factura.php?error=$error&descripcion=$descripcion_error";
				break;
			case"modal":
				$url="nva_factura_3.php?error=$error";
				break;
		}
		if(DEBUG){ echo"URL: $url<br>";}
		else{ header("location: $url");}
}
else
{ echo"sin datos<br>";}
//-------------------------------------------------------------------//
//////////////////////////////////////////////////////////
?>