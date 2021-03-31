<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("registro_horario_clases");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
@require_once ("../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("horario_clase_server.php");
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"CONSULTA_HORARIO");
$xajax->register(XAJAX_FUNCTION,"GRABA_HORARIO");
$xajax->register(XAJAX_FUNCTION,"ELIMINA_HORARIO");
////////////////////////////////////////////
function CONSULTA_HORARIO($AS_id)
{
	$objResponse = new xajaxResponse();
	require("../../../../funciones/conexion_v2.php");
	$div='div_horario';
	$div_boton='div_boton';
	$html_tabla='  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="7">Horarios de Clases</th>
    </tr>
     <tr>
      <td>N</td>
	  <td>Periodo(semanas)</td>
      <td>Dia</td>
      <td>Hora Inicio</td>
      <td>Hora Fin</td>
	  <td>Sala</td>
      <td>Opc</td>
    </tr>
    </thead>
    <tbody>';
	$array_dia=array(0 =>"Domingo",
				 1=>"Lunes",
				 2=>"Martes",
				 3=>"Miercoles",
				 4=>"Jueves",
				 5=>"Viernes",
				 6=>"Sabado");
	$cons_H="SELECT * FROM horario_docente WHERE id_asignacion='$AS_id' ORDER by dia_semana, hora_inicio";
	$sqli_H=$conexion_mysqli->query($cons_H)or die($conexion_mysqli->error);
	$num_registros=$sqli_H->num_rows;
	if($num_registros>0)
	{
		$aux=0;
		while($H=$sqli_H->fetch_assoc())
		{
			$aux++;
			$H_id=$H["id_horario"];
			$H_dia_semana=$H["dia_semana"];
			$H_hora_inicio=$H["hora_inicio"];
			$H_hora_fin=$H["hora_fin"];
			$H_sala=$H["sala"];
			$H_semanaInicio=$H["semanaInicio"];
			$H_semanaFin=$H["semanaFin"];
			///--------------------------------------------------------//
			$html_tabla.='<tr>
							<td>'.$aux.'</td>
							<td>'.$H_semanaInicio.'-'.$H_semanaFin.'</td>
							<td>'.$array_dia[$H_dia_semana].'</td>
							<td>'.$H_hora_inicio.'</td>
							<td>'.$H_hora_fin.'</td>
							<td>'.$H_sala.'</td>
							<td><a href="#" onclick="CONFIRMAR_ELIMINACION(\''.$H_id.'\');"><img src="../../../BAses/Images/b_drop.png" width="16" height="16" alt="x" /></a></td>
						 </tr>';
		}
	}
	else
	{ $html_tabla.='<tr><td colspan="6">Sin Registros</td></tr>';}
	$sqli_H->free();
	
	$html_tabla.='</tbody>
				  </table>';
				  
	$conexion_mysqli->close();
	
	$objResponse->Assign($div,"innerHTML",$html_tabla);
	
	//---------------------------------------------//
	return $objResponse;
}
//-------------------------------------------------------------------------------------------//
function GRABA_HORARIO($AS_id, $dia_semana, $hora_inicio, $minuto_inicio, $hora_fin, $minuto_fin, $sala, $semanaInicio, $semanaFin)
{
	$grabar=false;
	$verificarChoquesHorarios=false;//verifica o no si se producen choques de horario
	$objResponse = new xajaxResponse();
	require("../../../../funciones/conexion_v2.php");
	
	//--------------------------------------------------------------------------------//
	$cons_A="SELECT  * FROM toma_ramo_docente WHERE id='$AS_id' LIMIT 1";
	$sqli_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
	$TRD=$sqli_A->fetch_assoc();
		$TRD_id_carrera=$TRD["id_carrera"];
		$TRD_id_funcionario=$TRD["id_funcionario"];
		$TRD_cod_asignatura=$TRD["cod_asignatura"];
		$TRD_jornada=$TRD["jornada"];
		$TRD_grupo=$TRD["grupo"];
		$TRD_semestre=$TRD["semestre"];
		$TRD_year=$TRD["year"];
		$TRD_sede=$TRD["sede"];
	$sqli_A->free();	
	//------------------------------------------------------------------------------//
	
	$semanaInicio=mysqli_real_escape_string($conexion_mysqli, $semanaInicio);
	$semanaFin=mysqli_real_escape_string($conexion_mysqli, $semanaFin);
	
	$dia_semana=mysqli_real_escape_string($conexion_mysqli, $dia_semana);
	$hora_inicio_X=mysqli_real_escape_string($conexion_mysqli, $hora_inicio).":".mysqli_real_escape_string($conexion_mysqli, $minuto_inicio);
	$hora_fin_X=mysqli_real_escape_string($conexion_mysqli, $hora_fin).":".mysqli_real_escape_string($conexion_mysqli, $minuto_fin);
	//////verificar horas 1 menor a hora 2
	
	if(strtotime($hora_fin_X)>strtotime($hora_inicio_X))
	{  if(DEBUG){$objResponse->alert("Rango horario Correcto");} $hora_ok=true;}
	else
	{ $objResponse->alert("Hora Fin Menor a Hora inicio"); $hora_ok=false;}
	
	//verificar semana inicio sea menor a semana fin
	if(($semanaFin)>($semanaInicio))
	{  if(DEBUG){$objResponse->alert("Rango Semanal Correcto");} $semana_ok=true;}
	else
	{ $objResponse->alert("Hora Fin Menor a Hora inicio"); $semana_ok=false;}
	//----------------------------------------------------------------------//
	//verifico hora no este ya utilizada
	if(($hora_ok)and($semana_ok))
	{
		if(DEBUG){ $objResponse->alert("Comprobar choques horarios");}
		
		$cons_CH="SELECT hora_inicio, hora_fin, id_funcionario FROM horario_docente WHERE sede='$TRD_sede' AND id_funcionario='$TRD_id_funcionario' AND id_carrera='$TRD_id_carrera' AND cod_asignatura='$TRD_cod_asignatura'  AND grupo='$TRD_grupo' AND jornada='$TRD_jornada' AND semestre='$TRD_semestre' AND year='$TRD_year' AND dia_semana='$dia_semana' ORDER by hora_inicio";
		
		$cons_CH="SELECT hora_inicio, hora_fin, id_funcionario, sala, semanaInicio, semanaFin FROM horario_docente WHERE sede='$TRD_sede' AND semestre='$TRD_semestre' AND year='$TRD_year' AND dia_semana='$dia_semana' ORDER by hora_inicio";
		$sqli_CH=$conexion_mysqli->query($cons_CH)or die("Error".$conexion_mysqli->error);
		$num_registros=$sqli_CH->num_rows;
		if(DEBUG){ $objResponse->alert("Numero de registros guardados: $num_registros");}
		
		$hora_fin_X_time=strtotime($hora_fin_X);
		$hora_inicio_X_time=strtotime($hora_inicio_X);
		//-------------------------------------------------------------------------------------------//
		$colisiones_horarias=0;
		$hayChoque=false;
		if($num_registros>0)
		{
			$aux=0;
			$info="";
			while($DCH=$sqli_CH->fetch_assoc())
			{
				
				$aux++;
				$msj="";
				
				$H_semanaInicio_guardada=$DCH["semanaInicio"];
				$H_semanaFin_guardada=$DCH["semanaFin"];
				$H_hora_inicio_guardada_time=strtotime($DCH["hora_inicio"]);
				$H_hora_fin_guardada_time=strtotime($DCH["hora_fin"]);
				$H_id_funcionario_guardado=$DCH["id_funcionario"];
				$H_sala=$DCH["sala"];
				
				//verifico choque semanal
				if(($semanaFin<=$H_semanaInicio_guardada)or($semanaInicio>=$H_semanaFin_guardada))
				{$choqueSemana=false; $info.="choque semana: no ";}else{$choqueSemana=true; $info.="choque semana: si ";}
				
				///verifico choque horario
				if(($hora_fin_X_time<=$H_hora_inicio_guardada_time)or($hora_inicio_X_time>=$H_hora_fin_guardada_time))
				{$choqueHorario=false; $info.="choque horario: no ";}
				else{$choqueHorario=true; $info.="choque horario: si ";}
				//choque de sala
				if(($sala=="Aula Virtual")or($sala!=$H_sala)){$choqueSala=false; $info.="choque sala: no ";}
				else{$choqueSala=true; $info.="choque sala: si ";}
				
				if($verificarChoquesHorarios){
					//verifico si, el choque es con el mismo docente o no
					if($H_id_funcionario_guardado!=$TRD_id_funcionario){
						$info.="mismo funcionario: no ";
						if($choqueSala and $choqueHorario and $choqueSemana){
							$msj.="[hay choque con otro]"; $hayChoque=true;
						}else{ $msj.="[No hay choque con otro funcionario]";}
								
					}else{
						$info.="mismo funcionario: si ";
						if($choqueHorario and $choqueSemana){$hayChoque=true; $msj.="[ hay choque con el mismo]";}
						else{$msj.="[No hay choque con el mismo]";}
					}
				}
				else{$msj.="[Verificacion de choque horario desactivado]";}
					
					
		$info.="\n[$aux]id_funcionario actual: $TRD_id_funcionario id_funcionario guardado: $H_id_funcionario_guardado sala guardada: $H_sala  sala actual: $sala horario: ($hora_inicio_X_time - $hora_fin_X_time) horario guardado: ($H_hora_inicio_guardada_time - $H_hora_fin_guardada_time)\n MSJ: $msj\n";
				if($hayChoque){break;}
				
				
		
			}
			
			//$objResponse->alert("info: ".$info);
		}
		//---------------------------------------------------------------------------------------------------//
		if($hayChoque){ $objResponse->alert("ERROR. Colision Horaria con registro anterior\n Verifique Antes de Continuar \n $msj"); $grabar=false;}
		else{ $grabar=true;}
		$sqli_CH->free();
	}
	
	
	if($grabar)
	{
		$campos="id_asignacion, sede, id_funcionario, id_carrera, cod_asignatura, jornada, grupo, semestre, year, dia_semana, semanaInicio, semanaFin, hora_inicio, hora_fin, sala";
		$valores="'$AS_id', '$TRD_sede', '$TRD_id_funcionario', '$TRD_id_carrera', '$TRD_cod_asignatura', '$TRD_jornada', '$TRD_grupo', '$TRD_semestre', '$TRD_year', '$dia_semana', '$semanaInicio', '$semanaFin', '$hora_inicio_X', '$hora_fin_X', '$sala'";
		
		$cons_IN="INSERT INTO horario_docente ($campos) VALUES ($valores)";
		include("../../../../funciones/VX.php");
		$evento="Graba Registro Horario_Docente Asignacion id: $AS_id";
		REGISTRA_EVENTO($evento);
		if(DEBUG){ $objResponse->alert("Grabar horario OK \n".$cons_IN);}
		else{ $conexion_mysqli->query($cons_IN)or die("Error al Grabar".$conexion_mysqli->error);}
	}
	$conexion_mysqli->close();
	
	
	$objResponse->script("AUTO_CONSULTA();");
	return $objResponse;
}
//----------------------------------------------------------------------------------------------------//
function ELIMINA_HORARIO($id_horario)
{
	$objResponse = new xajaxResponse();
	require("../../../../funciones/conexion_v2.php");
	$eliminar=true;
	
	
	if($eliminar)
	{
		
		$cons_D="DELETE FROM horario_docente WHERE id_horario='$id_horario' LIMIT 1";
		
		if(DEBUG){ $objResponse->alert($cons_D);}
		else{ $conexion_mysqli->query($cons_D);}
	}
	
	$conexion_mysqli->close();
	mysql_close($conexion);
	$objResponse->script("AUTO_CONSULTA();");
	return $objResponse;
}

$xajax->processRequest();
?>