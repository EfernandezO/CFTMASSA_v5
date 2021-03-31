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
	$error="debug";
	require("../../../../../funciones/conexion_v2.php");
	
		$id_user_actual=$_SESSION["USUARIO"]["id"];
		
		$sede=mysqli_real_escape_string($conexion_mysqli, $_POST["fsede"]);
		$id_factura=mysqli_real_escape_string($conexion_mysqli, $_POST["id_factura"]);
		$condicion=mysqli_real_escape_string($conexion_mysqli, $_POST["condicion"]);
		$cod_factura=mysqli_real_escape_string($conexion_mysqli, $_POST["cod_factura"]);
		$id_proveedor=mysqli_real_escape_string($conexion_mysqli, $_POST["id_proveedor"]);
		$comentario=mysqli_real_escape_string($conexion_mysqli, $_POST["comentario"]);
		$fecha_ingreso=mysqli_real_escape_string($conexion_mysqli, $_POST["fecha_ingreso"]);
		$fecha_vencimiento=mysqli_real_escape_string($conexion_mysqli, $_POST["fecha_vencimiento"]);
		$valor=mysqli_real_escape_string($conexion_mysqli, $_POST["valor"]);
		$saldo=mysqli_real_escape_string($conexion_mysqli, $_POST["saldo"]);
		$abono=mysqli_real_escape_string($conexion_mysqli, $_POST["abono"]);
	
	$campo_valor="sede='$sede', condicion='$condicion', cod_factura='$cod_factura', id_proveedor='$id_proveedor',  comentario='$comentario', fecha_ingreso='$fecha_ingreso', fecha_vencimiento='$fecha_vencimiento', valor='$valor', saldo='$saldo', abono='$abono'";
	if(EXISTE_FACTURA($cod_factura, $id_proveedor, $sede, $id_factura))
	{
		echo"Valor de Factura ya utilizados en otra...<br>";
		echo'<a href="edit_factura_1.php?id=$id_factura">Volver a Edicion de Factura...</a>';
	}
	else
	{
		$cons_up="UPDATE facturas SET $campo_valor WHERE id='$id_factura' LIMIT 1";
		if(DEBUG)
			 {echo "$cons_up<br>";}
			 else
			{
				if($conexion_mysqli->query($cons_up))
				{ $error=4;}
				else
				{ $error=5;}
			}	
		$conexion_mysqli->close();
		
		$url="../ver/ver_factura.php?error=$error";
		if(DEBUG){ echo"URL: $url<br>";}
		else{header("location: $url");}
	}
}
else
{ header("location: ../ver/ver_factura.php");}
///////------------------------------------/////////////
function EXISTE_FACTURA($cod_factura, $proveedor, $sede, $id_factura)
{
	require("../../../../../funciones/conexion_v2.php");
		$cons="SELECT COUNT(id) FROM facturas WHERE sede='$sede' AND cod_factura='$cod_factura' AND proveedor='$proveedor' AND id<>'$id_factura'";
		
		if(DEBUG)
		{ echo"F.: $cons<br>";}
		$sqli=$conexion_mysqli->query($cons);
		$EF=$sqli->fetch_row();
		$coincidencias=$EF[0];
		if(empty($coincidencias))
		{$coincidencias=0;}
		
		if($coincidencias>0)
		{ $ya_existe=true;}
		else
		{ $ya_existe=false;}
		$sqli->free();
	$conexion_mysqli->close();
	return($ya_existe);
}	
?>