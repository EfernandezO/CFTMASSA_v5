<?php
//--------------CLASS_okalis------------------//
	require("../../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../../funciones/";
	$O->clave_del_archivo=md5("control_horario_docente");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("marca_time_server.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"CONTROL_HORARIO");
////////////////////////////////////////////
function CONTROL_HORARIO($tipo, $horas, $minutos, $segundos, $H_id, $fecha)
{
	$objResponse = new xajaxResponse();
	
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	$fecha_hora_actual=date("Y-m-d H:i:s");
	$hora_registro="$horas:$minutos:$segundos";
	
	require("../../../../../../funciones/conexion_v2.php");
	require("../../../../../../funciones/funciones_sistema.php");
	
	$fecha=mysqli_real_escape_string($conexion_mysqli, $fecha);
	$H_id=mysqli_real_escape_string($conexion_mysqli, $H_id);
	
	$html='<table width="100%" border="0"><tr>';
	$div='div_informacion';
	
	if(DEBUG){ $objResponse->Alert("Fecha: $fecha\n hora registro: $hora_registro \n Tipo: $tipo");}
	
	//buscar registro 
	$cons_B="SELECT tipo_registro FROM horario_docente_registros WHERE id_horario='$H_id' AND fecha='$fecha'";
	if(DEBUG){$objResponse->Alert("BUSCAR: $cons_B");}
	$sqli_B=$conexion_mysqli->query($cons_B)or die($conexion_mysqli->error);
	$total_registros_diario=$sqli_B->num_rows;
	$coincidencias=0;
	if($total_registros_diario>0)
	{
		while($B=$sqli_B->fetch_row())
		{
			$aux_tipo_registro=$B[0];
			if($aux_tipo_registro==$tipo){$coincidencias++;}
		}
	}
	$sqli_B->free();	
	//--------------------------------------------------------------------------------///
	$color="#ae0";
	//-----------------------------------------------------------------------------------//
	if($coincidencias>0)
	{
		if(DEBUG){$objResponse->Alert("Registro ya realizado, coincidencias: $coincidencias");}
		$html.='<td bgcolor="'.$color.'">Registro de '.$tipo.',  Anteriormente ya Realizado...';
	}
	else
	{ 
		if($tipo!=="inasistencia"){ $grabar=true;}
		elseif($total_registros_diario>0){ $grabar=false;}
		else{$grabar=true;}
		
		if($grabar)
		{
			$cons_IN="INSERT INTO horario_docente_registros(id_horario, fecha, hora, tipo_registro, cod_user, fecha_generacion) VALUES ('$H_id', '$fecha', '$hora_registro', '$tipo', '$id_usuario_actual', '$fecha_hora_actual')";
			
			if(DEBUG){$objResponse->Alert("INSERTAR: $cons_IN");}
			else
			{
				if($conexion_mysqli->query($cons_IN))
				{$html.='<td bgcolor="'.$color.'">Registro de '.$tipo.',  Correctamente Registrado...';}
				else{$html.='<td bgcolor="#fa0">Registro de '.$tipo.',  Error Registrado...'.$conexion_mysqli->error;}
			}
		}
		else
		{
			$html.='<td bgcolor="'.$color.'">Registro de '.$tipo.',  No se puede grabar ya tiene registros de entrada/salida...';
		}
		
		
	}
	
	
	$html.='<tr></table>';		  
	$conexion_mysqli->close();
	@mysql_close($conexion);
	$objResponse->Assign($div,"innerHTML",$html);
	
	//---------------------------------------------//
	return $objResponse;
}
//----------------------------------------------------------------------------------------------------//
$xajax->processRequest();
?>