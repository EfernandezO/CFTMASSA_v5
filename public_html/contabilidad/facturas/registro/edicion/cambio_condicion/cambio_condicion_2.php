<?php
//--------------CLASS_okalis------------------//
	require("../../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../../funciones/";
	$O->clave_del_archivo=md5("gestion_facturas_v1.2");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_POST)
{
	if(DEBUG){ var_dump($_POST);}
	
	$id_factura=$_POST["id_factura"];
	$F_valor=$_POST["valor"];
	$F_saldo_a_pagar=$_POST["saldo"];
	
	if((is_numeric($F_saldo_a_pagar))and($F_saldo_a_pagar>0))
	{
		$continuar=true;
	}
	else
	{
		$continuar=false;
		if(DEBUG){ echo"---> Saldo incorrecto<br>";}
	}
}
else
{$continuar=false;}
//----------------------------------------------------------------------------------------------------//
if($continuar)
{

	require("../../../../../../funciones/conexion_v2.php");
	require("../../../../../../funciones/VX.php");
	
	//datos actuales de factura
	$cons="SELECT * FROM facturas WHERE id='$id_factura' LIMIT 1";
	$sqli=$conexion_mysqli->query($cons);
		$F=$sqli->fetch_assoc();
		$F_actual_valor=$F["valor"];
		$F_actual_saldo=$F["saldo"];
		$F_actual_abono=$F["abono"];
	$sqli->free();
	//--------------------------------------------------//
	
	if($F_saldo_a_pagar>$F_actual_saldo){ $continuar_2=false; if(DEBUG){ echo"Valor a Pagar excede Valor deuda<br>";}}
	else{ $continuar_2=true;}
	
	
	if($continuar_2)
	{
		$F_new_saldo=($F_actual_saldo - $F_saldo_a_pagar);
		$F_new_abono=($F_actual_abono + $F_saldo_a_pagar);
		
		
		if($F_new_saldo==0){ $nueva_condicion='cancelada';}
		elseif($F_new_saldo<$F_actual_valor){ $nueva_condicion="abonada";}
		else{ $nueva_condicion="pendiente";}
		
		if(DEBUG){ echo"id_factura: $id_factura<br> Valor Actual: $F_actual_valor<br>Saldo Actual: $F_actual_saldo<br>Abono Actual: $F_actual_abono<br><br> Saldo New: $F_new_saldo<br>Abono New: $F_new_abono<br>";}
		
		//-------------------------------------------------//	
		$cons_UP="UPDATE facturas SET condicion='$nueva_condicion', saldo='$F_new_saldo', abono='$F_new_abono'  WHERE id='$id_factura' LIMIT 1";
		if(DEBUG){ echo"--> $cons_UP<br>";}
		$sqli=$conexion_mysqli->query($cons_UP) or die($conexion_mysqli->error);
		$error="CF0";
		
		$evento="Cambia Condicion de Factura id_factura: $id_factura cambio a condicion: $nueva_condicion";
		REGISTRA_EVENTO($evento);
		
	}
	else
	{$error="CF2";}
	
	$conexion_mysqli->close();
	@mysql_close($conexion);
	
	

	
}
else
{ $error="CF1";}

if(!DEBUG){header("location: cambio_condicion_3.php?error=$error");}
?>