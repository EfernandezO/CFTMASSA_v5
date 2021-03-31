<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Impresion_Y_Gestion_de_contratos_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$eliminar_cheques_asociados=false;
//-----------------------------------------//
if(DEBUG){ var_dump($_GET);}

if(isset($_GET["id_pago"]))
{
	$id_pago=$_GET["id_pago"];
	if((is_numeric($id_pago))and($id_pago>0))
	{ $continuar_1=true;}
	else
	{ $continuar_1=false;}
}
else
{ $continuar_1=false;}

if(isset($_GET["id_contrato"]))
{
	$id_contrato=$_GET["id_contrato"];
	if((is_numeric($id_contrato))and($id_contrato>0))
	{ $continuar_2=true;}
	else
	{ $continuar_2=false;}
}
else
{ $continuar_2=false;}
//----------------------------------------------------//

if(isset($_GET["id_boleta"]))
{
	$id_boleta=$_GET["id_boleta"];
	if((is_numeric($id_boleta))and($id_boleta>0))
	{$hay_boleta=true;}
	else
	{$hay_boleta=false;}
}
else
{ $hay_boleta=false;}
//--------------------------------------------------------//

if($continuar_1 and $continuar_2)
{
	if(DEBUG){ echo"Iniciar<br>";}
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/VX.php");

	if($eliminar_cheques_asociados)	
	{
		
		if(DEBUG){ echo"Elimina Cheque Asociados si existen<br>";}
		//busco metodo pago
		$cons_BP="SELECT * FROM pagos WHERE idpago='$id_pago' LIMIT 1";
		$sqli_BP=$conexion_mysqli->query($cons_BP) or die($conexion_mysqli->error);
			$P=$sqli_BP->fetch_assoc();
			$P_forma_pago=$P["forma_pago"];
			$P_id_cheque=$P["id_cheque"];
			if(empty($P_id_cheque)){ $P_id_cheque=0;}
			$P_id_multi_cheque=$P["id_multi_cheque"];
			if(empty($P_id_multi_cheque)){ $P_id_multi_cheque=0;}
		$sqli_BP->free();
		
		//elimino cheque Relacionado
		if($P_id_cheque>0)
		{
			if(DEBUG){ echo"Elimina Cheque Relacionados";}
			$cons_cheque="DELETE FROM registro_cheques WHERE id='$P_id_cheque' LIMIT 1";
			if(DEBUG){ echo"CHEQUES: $cons_cheque<br>";}
			else{ $conexion_mysqli->query($cons_cheque)or die($conexion_mysqli->error);}
		}	
		//elimina Multi cheques
		if($P_id_multi_cheque>0)
		{
			$cons_multi="SELECT * FROM registro_multi_cheques WHERE id='$P_id_multi_cheque' LIMIT 1";
			$sqli_multi=$conexion_mysqli->query($cons_multi)or die($conexion_mysqli->error);
			$numero_registros=$sqli_multi->num_rows;
			if($numero_registros>0)
			{
				$condicion_multi="";
				$MCH=$sqli_multi->fetch_assoc();
				$aux_id_cheque_1=$MCH["id_cheque_1"];
				$aux_id_cheque_2=$MCH["id_cheque_2"];
				$aux_id_cheque_3=$MCH["id_cheque_3"];
				$aux_id_cheque_4=$MCH["id_cheque_4"];
				$aux_id_cheque_5=$MCH["id_cheque_5"];
				$aux_id_cheque_6=$MCH["id_cheque_6"];
				
				if($aux_id_cheque_1>0)
				{ $condicion_multi.="'$aux_id_cheque_1'";}
				if($aux_id_cheque_2>0)
				{ $condicion_multi.=", '$aux_id_cheque_2'";}
				if($aux_id_cheque_3>0)
				{ $condicion_multi.=", '$aux_id_cheque_3'";}
				if($aux_id_cheque_4>0)
				{ $condicion_multi.=", '$aux_id_cheque_4'";}
				if($aux_id_cheque_5>0)
				{ $condicion_multi.=", '$aux_id_cheque_5'";}
				if($aux_id_cheque_6>0)
				{ $condicion_multi.=", '$aux_id_cheque_6'";}
				
				$cons_DEL_MULTI="DELETE FROM registro_multi_cheque WHERE id='$P_id_multi_cheque' LIMIT 1";
				if(DEBUG){ echo"MULTI CHEQUES: $cons_DEL_MULTI<br>";}
				else{ $conexion_mysqli->query($cons_DEL_MULTI)or die($conexion_mysqli->error);}
				
				$cons_DEL_MULTI_2="DELETE FROM registro_cheques WHERE id IN($condicion_multi)";
				if(DEBUG){ echo"MULTI CHEQUES 2: $cons_DEL_MULTI_2<br>";}
				else{ $conexion_mysqli->query($cons_DEL_MULTI_2)or die($conexion_mysqli->error);}
			}
		}
	}
	else{ if(DEBUG){ echo"No Elimina Cheque Asociados si existen<br>";}}
	//elimina pago
	$cons_P="DELETE FROM pagos WHERE idpago='$id_pago' LIMIT 1";
	if(DEBUG){ echo"PAGOS--> $cons_P<br>";}
	else{$conexion_mysqli->query($cons_P)or die($conexion_mysqli->error);}
	
	//elimina boleta
	$cons_B="DELETE FROM boleta WHERE id='$id_boleta' LIMIT 1";
	if(DEBUG){ echo"BOLETA--> $cons_B<br>";}
	else{$conexion_mysqli->query($cons_B)or die($conexion_mysqli->error);}
	$conexion_mysqli->close();
	
	$evento="Elimina Pago de alumno id($id_alumno)";
	REGISTRA_EVENTO($evento);
	$url="../informe_finan1.php?id_contrato=$id_contrato&error=P0";
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
}
else
{
	if(DEBUG){ echo"Error No continuar<br>";}
	else{ header("location: index.php");}
}
?>
