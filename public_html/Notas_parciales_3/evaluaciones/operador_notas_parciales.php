<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Notas_parcialesV3->verCalificador");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("operador_notas_parciales.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"GRABA_NOTA_AUTOMATICO");
$xajax->register(XAJAX_FUNCTION,"BORRA_NOTA");
$xajax->register(XAJAX_FUNCTION,"MUESTRA_OPCIONES_NOTA");
//---------------------------------------------------------------//
function GRABA_NOTA_AUTOMATICO($nota_evaluacion, $id_evaluacion, $id_alumno, $id_carrera, $cod_asignatura, $semestre, $year, $sede, $jornada, $grupo, $indice_posicion)
{
	$objResponse = new xajaxResponse();
	if(!empty($nota_evaluacion)and($nota_evaluacion>0))
	{
		require("../../../funciones/conexion_v2.php");
		include("../../../funciones/VX.php");
		
		$msj="";
		$div='div_posicion_'.$indice_posicion;
		$div_msj="div_msj";
		$input="input_nota_".$indice_posicion;
		
		$fecha_actual=date("Y-m-d");
		$fecha_hora_actual=date("Y-m-d H:i:s");
		$id_usuario_actual=$_SESSION["USUARIO"]["id"];
		$nota_evaluacion=trim($nota_evaluacion);
		$nota_evaluacion=str_replace(",",".",$nota_evaluacion);
		$observacion='-guardado automatico';
		//valido nota
		if(is_numeric($nota_evaluacion))
		{
			if(($nota_evaluacion>0)and($nota_evaluacion<=7))
			{ $nota_valida=true;}
			else
			{ $nota_valida=false;}
		}
		else
		{ $nota_valida=false;}	
		//--------------------------
		//hay nota previa
		$nota_antigua="";
		$cons="SELECT nota FROM notas_parciales_registros WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND id_evaluacion='$id_evaluacion' AND cod_asignatura='$cod_asignatura' AND semestre='$semestre' AND year='$year'";
		$sql=$conexion_mysqli->query($cons)or die("VERIFICAR_EXISTE_NOTA".$conexion_mysqli->error);
		$num_coincidencias=$sql->num_rows;
		if($num_coincidencias>0)
		{
			$D=$sql->fetch_assoc();
			$nota_antigua=$D["nota"];
			$observacion.=' [M]';
		}
		if(empty($num_coincidencias)){ $num_coincidencias=0;}
		if(DEBUG){ echo"$cons<br>Num Coincidencias: $num_coincidencias<br>";}
		$sql->free();
		if($num_coincidencias>0)
		{ $hay_nota_previamente=true;}
		else
		{ $hay_nota_previamente=false;}
		
		
		//-----------------------------------------------------------------//
	
		if($nota_valida)
		{
			$aux_nota_evaluacion=$nota_evaluacion;
			if($hay_nota_previamente)
			{
				
				if($nota_antigua!=$nota_evaluacion)
				{
					///actualizar registro
					$cons_UNE="UPDATE notas_parciales_registros SET nota='$nota_evaluacion', observacion=CONCAT(observacion, '$observacion'), fecha_generacion='$fecha_hora_actual', cod_user='$id_usuario_actual' WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND id_evaluacion='$id_evaluacion' AND cod_asignatura='$cod_asignatura' AND semestre='$semestre' AND year='$year' LIMIT 1";
					if(DEBUG){ echo"$cons_UNE<br>";}
					else{ $conexion_mysqli->query($cons_UNE)or die("Update".$conexion_mysqli->error);}
					
					 /////Registro ingreso///
					 $evento="Modifica Nota Parcial V3 ID alumno $id_alumno id carrera: $id_carrera cod_asignatura: $cod_asignatura id_evaluacin: $id_evaluacion cambio nota de [$nota_antigua -> $nota_evaluacion]";
					 REGISTRA_EVENTO($evento);
					 ///////////////////////
					  $msj='<img src="../../BAses/Images/advertencia.png" width="29" height="26" alt="advertencia" />Nota Guardada Automaticamente [UP]... ['.$nota_antigua.' -> '.$nota_evaluacion.']';
				}
			}
			else
			{
				$campos="id_alumno, id_carrera, id_evaluacion, cod_asignatura, semestre, year, nota, observacion, fecha_generacion, cod_user";	
				$cons_NE="INSERT INTO notas_parciales_registros ($campos) VALUES('$id_alumno', '$id_carrera', '$id_evaluacion', '$cod_asignatura', '$semestre', '$year', '$nota_evaluacion', '$observacion', '$fecha_hora_actual', '$id_usuario_actual')";
				if(DEBUG){ echo"----$cons_NE<br>";}
				else{ $conexion_mysqli->query($cons_NE)or die("Insertar".$conexion_mysqli->error);}
				
				 /////Registro ingreso///
				 $evento="Agrega Nota Parcial V3 ID alumno $id_alumno id carrera: $id_carrera cod_asignatura: $cod_asignatura id_evaluacion: $id_evaluacion nota ingresada [$nota_evaluacion]";
				 REGISTRA_EVENTO($evento);
				 ///////////////////////
				  $msj='<img src="../../BAses/Images/advertencia.png" width="29" height="26" alt="advertencia" />Nota Guardada Automaticamente [IN]... :)';	
			}
			
		}
		else
		{
			if(DEBUG){ echo"Nota no Valida para Insertar...<br>";}
			 $msj='<img src="../../BAses/Images/b_drop.png" width="16" height="16" alt="Error" />Nota Invalida... :(';	
			 $aux_nota_evaluacion='';
			
		}
		//--------------------------------------------------------------//
		$objResponse->Assign($div_msj,"innerHTML",$msj);
		$objResponse->Assign($input,"value",$aux_nota_evaluacion);
		$conexion_mysqli->close();
	}
	return $objResponse;
}
//----------------------------------------------------------------//
function MUESTRA_OPCIONES_NOTA($id_nota_parcial_registro, $indice_posicion)
{
	$objResponse = new xajaxResponse();
	$div='div_posicion_'.$indice_posicion;
	$OPC='<a href="#" onclick="xajax_BORRA_NOTA('.$id_nota_parcial_registro.', '.$indice_posicion.');"><img src="../../BAses/Images/b_drop.png" width="16" height="16" alt="X" title="Eliminar esta Nota"/></a>';
	$objResponse->Assign($div,"innerHTML",$OPC);
	return $objResponse;
}
//---------------------------------------------------------------------//
function BORRA_NOTA($id_nota_parcial_registro, $indice_posicion)
{
	$objResponse = new xajaxResponse();
	$fecha_actual=date("Y-m-d");
	$fecha_hora_actual=date("Y-m-d H:i:s");
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	
	$observacion="-Nota borrada";
	$div='div_posicion_'.$indice_posicion;
	$div_msj="div_msj";
	$msj="";
	$input="input_nota_".$indice_posicion;
	require("../../../funciones/conexion_v2.php");
	
	//--------------------------
		//hay nota previa
		$nota_antigua="";
		$cons="SELECT nota FROM notas_parciales_registros WHERE id='$id_nota_parcial_registro' LIMIT 1";
		$sql=$conexion_mysqli->query($cons)or die("VERIFICAR_EXISTE_NOTA".$conexion_mysqli->error);
		$num_coincidencias=$sql->num_rows;
		if($num_coincidencias>0)
		{
			$D=$sql->fetch_assoc();
			$nota_antigua=$D["nota"];
			$observacion.=' [D]';
		}
		if(empty($num_coincidencias)){ $num_coincidencias=0;}
		if(DEBUG){ echo"$cons<br>Num Coincidencias: $num_coincidencias<br>";}
		$sql->free();
		if($num_coincidencias>0)
		{ $hay_nota_previamente=true;}
		else
		{ $hay_nota_previamente=false;}
		
		
		//-----------------------------------------------------------------//
	
	
	
	$cons_D="UPDATE notas_parciales_registros SET nota='0.0', observacion=CONCAT(observacion, '$observacion'), fecha_generacion='$fecha_hora_actual', cod_user='$id_usuario_actual' WHERE id='$id_nota_parcial_registro' LIMIT 1";
	if($conexion_mysqli->query($cons_D))
	{
		$msj='<img src="../../BAses/Images/advertencia.png" width="29" height="26" alt="advertencia" />Nota Borrada Exitosamente... :)';
		$OPC='';
		$mostrar_msj=true;
		$restablecer_opc=true;
		$guardar_evento=true;
		$restablecer_input=true;
	}
	else
	{
		$msj='<img src="../../BAses/Images/b_drop.png" width="16" height="16" alt="error" />ERROR al intentar Borrar la Nota, por favor intentelo mas Tarde... :('.$conexion_mysqli->error;
		$mostrar_msj=true;
		$restablecer_opc=false;
		$guardar_evento=false;
		$restablecer_input=false;
	}
	//-----------------------------------------------------//
	if($mostrar_msj){$objResponse->Assign($div_msj,"innerHTML",$msj);}
	if($restablecer_opc){$objResponse->Assign($div,"innerHTML",$OPC);}
	if($restablecer_input){$objResponse->Assign($input,"value","");}
	if($guardar_evento)
	{
		include("../../../funciones/VX.php");
		$evento="Borra Nota Parcial de alumno id_nota_parcial_registro: $id_nota_parcial_registro borra nota de [ $nota_antigua -> 0.0]";
		REGISTRA_EVENTO($evento);
	}
	
	
	
	$conexion_mysqli->close();
	return $objResponse;
}


$xajax->processRequest();
?>