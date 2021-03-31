<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
	define("DEBUG", false);
	
if($_POST)	
{
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/funcion.php");
	require("../../../../funciones/VX.php");
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	$sede_usuario_actual=$_SESSION["USUARIO"]["sede"];
	$fecha_actual=date("Y-m-d");
	$error="OC1";
		if(DEBUG){var_dump($_POST);}
		$proveedor_id=mysqli_real_escape_string($conexion_mysqli, $_POST["proveedor_id"]);
		$proveedor_rut=mysqli_real_escape_string($conexion_mysqli, $_POST["proveedor_rut"]);
		$proveedor_razon_social=mysqli_real_escape_string($conexion_mysqli, str_inde($_POST["proveedor_razon_social"]));
		$proveedor_direccion=mysqli_real_escape_string($conexion_mysqli, str_inde($_POST["proveedor_direccion"]));
		$proveedor_ciudad=mysqli_real_escape_string($conexion_mysqli, str_inde($_POST["proveedor_ciudad"]));
		
		$solicitud_unidad=mysqli_real_escape_string($conexion_mysqli, $_POST["solicitante_unidad"]);
		$solicitud_id_responsable=mysqli_real_escape_string($conexion_mysqli, $_POST["solicitante_id_responsable"]);
		$solicitud_cotizacion=mysqli_real_escape_string($conexion_mysqli, $_POST["solicitante_cotizacion"]);
		$solicitud_condicion_pago=mysqli_real_escape_string($conexion_mysqli, $_POST["solicitante_condicion_pago"]);
		$solicitud_descripcion=mysqli_real_escape_string($conexion_mysqli, $_POST["solicitante_descripcion"]);
		//-------------------------------//
		//determino sede solicitante
		$cons_SS="SELECT sede FROM personal WHERE id='$solicitud_id_responsable' LIMIT 1";
		$sql_SS=$conexion_mysqli->query($cons_SS);
 		$SS=$sql_SS->fetch_assoc();
			$solicitud_sede_responsable=$SS["sede"];
		$sql_SS->free();	
		
		$array_item_cantidad=$_POST["item_cantidad"];
		$array_item_unidad_medida=$_POST["item_unidad_medida"];
		$array_item_descripcion=$_POST["item_descripcion"];
		$array_item_valor=$_POST["item_valor"];
		
		$num_item=count($array_item_cantidad);
		if($num_item>0){$grabar_OC=true;}
		else{$grabar_OC=false;}
		///--------------------------------------------------------//
		//guardar proveedor
		if($proveedor_id>0)
		{ $guardar_proveedor=false;}
		else
		{ $guardar_proveedor=true;}
		
		if($guardar_proveedor)
		{
			$campos="rut, razon_social, ciudad, direccion";
			$valores="'$proveedor_rut', '$proveedor_razon_social', '$proveedor_ciudad', '$proveedor_direccion'";
			$cons_IN_P="INSERT INTO proveedores ($campos) VALUES ($valores)";
			if(DEBUG){ echo"Guardar Proveedor<br>-> $cons_IN_P<br>"; $proveedor_id="p_1";}
			else{ $conexion_mysqli->query($cons_IN_P); $proveedor_id=$conexion_mysqli->insert_id;}
			
		}
		else
		{ if(DEBUG){ echo"-->Proveedor ya Existe No Guardar<br>";}}
		//-----------------------------------------------------------------//
		//guarda ORDER COMPRA
		$crear_solicitud=false;
		if($grabar_OC)
		{
			$crear_solicitud=true;
			$evento="Crea Orden de Compra";
			 REGISTRA_EVENTO($evento);
			//------------------------------------------------------------//
			$campos="id_proveedor, id_solicitante, unidad_solicitante, cotizacion, condiciones_pago, descripcion, sede, fecha_creacion, cod_user";
			$valores="'$proveedor_id', '$solicitud_id_responsable', '$solicitud_unidad', '$solicitud_cotizacion', '$solicitud_condicion_pago', '$solicitud_descripcion', '$solicitud_sede_responsable', '$fecha_actual', '$id_usuario_actual'";
			$cons_OC="INSERT INTO orden_compra ($campos) VALUES ($valores)";
			if(DEBUG){ echo"Graba Orden de Compra<br>---> $cons_OC<br>"; $id_OC="oc_1";}
			else{ $conexion_mysqli->query($cons_OC); $id_OC=$conexion_mysqli->insert_id;}
			//------------------------------------------------------------//
			if(DEBUG){ echo"GRABA ITEM<br>";}
			foreach($array_item_cantidad as $indice => $aux_item_cantidad)
			{
				$aux_item_cantidad=mysqli_real_escape_string($conexion_mysqli, $aux_item_cantidad);
				$aux_item_unidad_medida=mysqli_real_escape_string($conexion_mysqli, $array_item_unidad_medida[$indice]);
				$aux_item_descripcion=mysqli_real_escape_string($conexion_mysqli, $array_item_descripcion[$indice]);
				$aux_item_valor=mysqli_real_escape_string($conexion_mysqli, $array_item_valor[$indice]);
				
				$campos="id_oc, cantidad, unidad_medida, descripcion, valor_unitario";
				$valores="'$id_OC', '$aux_item_cantidad', '$aux_item_unidad_medida', '$aux_item_descripcion', '$aux_item_valor'";
				$cons_OC_ITEM="INSERT INTO orden_compra_item ($campos) VALUES ($valores)";
				
				if(DEBUG){ echo"$indice -> $cons_OC_ITEM<br>"; }
				else{ $conexion_mysqli->query($cons_OC_ITEM);}
			}
		}
		
		
		$conexion_mysqli->close();
		mysql_close($conexion);
		
		///redireccion
		$url="orden_compra_final.php?error=$error";
		if(DEBUG){ echo"URL: $url<br>";}
		else{ header("location: $url");}
	
}
else
{ echo"sin datos<br>";}
//-------------------------------------------------------------------//
//////////////////////////////////////////////////////////
?>	