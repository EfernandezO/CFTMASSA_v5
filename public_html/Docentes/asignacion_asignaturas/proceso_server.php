<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("asignaciones_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("proceso_server.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCAR_ASIGNATURAS");
$xajax->register(XAJAX_FUNCTION,"BUSCA_ASIGNACIONES");
$xajax->register(XAJAX_FUNCTION,"GRABA_ASIGNACIONES");
$xajax->register(XAJAX_FUNCTION,"ACTUALIZA_TOTAL");
$xajax->register(XAJAX_FUNCTION,"horasPrograma");

function horasPrograma($idCarrera, $codAsignatura){
	$objResponse = new xajaxResponse();
	require("../../../funciones/funciones_sistema.php");
	
	$horasSemestrales=0;
	if(($codAsignatura>1)and($codAsignatura<86)){
		$horasSemestrales=HORAS_PROGRAMA($idCarrera, $codAsignatura,"semestral", "teorico");
	}
	$objResponse->Assign('numero_horas',"value",$horasSemestrales);
	return $objResponse;	
}

function BUSCAR_ASIGNATURAS($array_carrera)
{
	$array_carrera=explode("_",$array_carrera);
	$id_carrera=$array_carrera[0];
	$carrera=$array_carrera[1];
	
	$objResponse = new xajaxResponse();
	$div='div_asignaturas';
	$div_boton='div_boton';
	$campo_select='<select name="asignatura" id="asignatura" onchange="xajax_horasPrograma('.$id_carrera.', this.value)"><optgroup label="Asignaturas">
	<option value="">Seleccione</option>';
	require("../../../funciones/conexion_v2.php");
	 
	 if($id_carrera>0)
	 {
		 $cons="SELECT * FROM mallas WHERE id_carrera='$id_carrera' AND ramo<>'' ORDER by num_posicion, cod";
		 $sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		 $num_asignaturas=$sql->num_rows;
		 if($num_asignaturas>0)
		 {
			 while($A=$sql->fetch_assoc())
			 {
				 $ASIG_cod=$A["cod"];
				 $ASIG_ramo=$A["ramo"];
				 $ASIG_nivel=$A["nivel"];
				 $campo_select.='<option value="'.$ASIG_cod.'">['.$ASIG_nivel.'] '.$ASIG_ramo.'</option>';
			 }
			  
		 }
		 else
		 { $campo_select.='<option value="0">Sin Asignaturas</option>';}
		 
		 $campo_select.='</optgroup><optgroup label="Otros">
		 					  <option value="0">[00] *JEFATURA</option>
			  				  <option value="99">[99] *Toma Examen</option>
							  <option value="98">[98] *Revision Informe</option>
							  <option value="97">[97] *Supervision de Practica</option>
							  <option value="96">[96] *Administracion Asignatura</option>
							  <option value="95">[95] *Taller Complementario</option>
							  <option value="94">[94] *Asistencia Reunion</option>
							  <option value="93">[93] *Bono Responsabilidad</option>
							  <option value="92">[92] *Prestacion de Servicios Profesionales</option>
							  <option value="91">[91] *Toma Pruebas Pendientes</option>
							  <option value="90">[90] *Asesoria Centro de Alumno</option>
							  <option value="89">[89] *Movilizacion</option>
							  <option value="88">[88] *Proceso Examen Conocimiento Relevante</option>
							  <option value="87">[87] *Tutorias</option>
							  </optgroup>';
		 
		$sql->free();
		 $campo_select.='</select>';
		 $mostrar_boton=true;
	 }
	 else{ $campo_select='...<input name="asignatura" type="hidden" value="0" />'; $mostrar_boton=false;}
	
	
	$objResponse->Assign($div,"innerHTML",$campo_select);
	if($mostrar_boton)
	{$objResponse->Assign($div_boton,"innerHTML",'<a href="#" class="button_G" onclick="xajax_GRABA_ASIGNACIONES(document.getElementById(\'id_docente\').value, document.getElementById(\'semestre\').value, document.getElementById(\'year\').value, document.getElementById(\'carrera\').value, document.getElementById(\'asignatura\').value, document.getElementById(\'numero_horas\').value, document.getElementById(\'valor_hora\').value, document.getElementById(\'total\').value, document.getElementById(\'numero_cuotas\').value, document.getElementById(\'fsede\').value, document.getElementById(\'jornada\').value, document.getElementById(\'grupo\').value);return false;">Grabar</a>');}
	$conexion_mysqli->close();
	return $objResponse;
}

function BUSCA_ASIGNACIONES($semestre, $year, $id_funcionario)
{
	$sede_usuario_actual=$_SESSION["USUARIO"]["sede"];
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	
	switch($privilegio)
	{
		case"admi_total":
			$filtrar_X_sede=false;
			break;
		default:
			$filtrar_X_sede=true;	
	}
	$objResponse = new xajaxResponse();
	$div='div_asignaciones';
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	
	$tabla=' <table width="90%" border="1">
  <thead>
    <tr>
      <th colspan="16">Asignacion ya Creadas '.$semestre.' - '.$year.'</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>N.</td>
      <td>Sede</td>
      <td>carrera</td>
      <td>Jornada</td>
      <td>Grupo</td>
      <td>Asignatura</td>
      <td>N. Horas</td>
      <td>Valor. Horas</td>
	   <td>N.Cuotas</td>
	    <td>Total</td>
		<td>Estado</td>
		<td>Creada</td>
		<td>Usuario</td>
      <td colspan="3">Opciones</td>
    </tr>';
	
	if($filtrar_X_sede)
	{ $condicion_sede="sede='$sede_usuario_actual' AND";}
	else
	{ $condicion_sede="";}
	
	 $cons_1="SELECT * FROM toma_ramo_docente WHERE $condicion_sede id_funcionario='$id_funcionario' AND semestre='$semestre' AND year='$year'";
	 
	 if(DEBUG){$objResponse->Alert("$cons_1");}
	$sql_1=$conexion_mysqli->query($cons_1)or die($conexion_mysqli->error);
	$num_asignaciones=$sql_1->num_rows;
	if($num_asignaciones)
	{
		$contador=0;
		$SUMA_TOTAL=0;
		while($AS=$sql_1->fetch_assoc())
		{
			$aux_total_000=0;
			$contador++;
			$AS_sede=$AS["sede"];
			$AS_id=$AS["id"];
			$AS_id_carrera=$AS["id_carrera"];
			$AS_jornada=$AS["jornada"];
			$AS_grupo=$AS["grupo"];
			$AS_valor_hora=$AS["valor_hora"];
			$AS_cod_asignatura=$AS["cod_asignatura"];
			$AS_numero_horas=$AS["numero_horas"];
			$AS_total=$AS["total"];
			$AS_numero_cuotas=$AS["numero_cuotas"];
			$AS_condicion=$AS["condicion"];
			$AS_fecha_generacion=$AS["fecha_generacion"];
			$AS_cod_user=$AS["cod_user"];
			$SUMA_TOTAL+=$AS_total;
			
			$info_total="";
			////verificacion y correccion total
				$aux_total_000=($AS_valor_hora*$AS_numero_horas);
				if($AS_total!=$aux_total_000)
				{
					$cons_correccion="UPDATE toma_ramo_docente SET total='$aux_total_000' WHERE id='$AS_id' AND id_funcionario='$id_funcionario' LIMIT 1";
					if(DEBUG){$objResponse->Alert("aux_total:$aux_total_000 \n AS_TOTAL: $AS_total \n--->$cons_correccion");}
					if($conexion_mysqli->query($cons_correccion))
					{ $AS_total=$aux_total_000; $info_total="*"; $objResponse->Alert("Diferencia Detectada en los Totales se corregira $AS_total -> $aux_total_000\n ");}
					else
					{ $conexion_mysqli->error; $info_total="E";}
				}
				else
				{ $info_total="-";}
			
			/////
			//carrera
			$nombre_carrera=NOMBRE_CARRERA($AS_id_carrera);
			//---------------------------------------------------------------------------------------------//
			//asignatura
			list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($AS_id_carrera, $AS_cod_asignatura);
			//-----------------------------------------------------------------------------------------------//	
			//numero de cuotas ya asignadas
			$cons_honorario_detalle="SELECT COUNT(id) FROM honorario_docente_detalle WHERE sede='$sede_usuario_actual' AND id_carrera='$AS_id_carrera' AND cod_asignatura='$AS_cod_asignatura' AND id_funcionario='$id_funcionario' AND semestre='$semestre' AND year='$year' AND jornada='$AS_jornada' AND grupo='$AS_grupo'";
			$sqli_HD=$conexion_mysqli->query($cons_honorario_detalle)or die($conexion_mysqli->error);
			if(DEBUG){ echo"---->$cons_honorario_detalle<br>";}
			
			$CA=$sqli_HD->fetch_row();
			$cuotaActual=$CA[0];
			if(empty($cuotaActual)){$cuotaActual=0;}
			$sqli_HD->free();
			
			
			
			$tabla.='<tr>
				  <td>'.$contador.'</td>
				  <td>'.$AS_sede.'</td>
				  <td>['.$AS_id_carrera.'] '.$nombre_carrera.'</td>
				  <td>'.$AS_jornada.'</td>
				  <td>'.$AS_grupo.'</td>
				  <td>['.$AS_cod_asignatura.'] '.$nombre_asignatura.'</td>
				  <td>'.number_format($AS_numero_horas,2,",",".").'</td>
				  <td>$ '.number_format($AS_valor_hora,0,",",".").'</td>
				   <td align="center">'.$AS_numero_cuotas.' {'.$cuotaActual.'}</td>
				  <td>$ '.number_format($AS_total,0,",",".").' '.$info_total.'</td>
				  <td>'.$AS_condicion.'</td>
				  <td align="center">'.$AS_fecha_generacion.'</td>
				  <td align="center"><a href="#" title="'.NOMBRE_PERSONAL($AS_cod_user).'">'.$AS_cod_user.'</a></td>
				   <td><a href="horario_clases/ver_horario_clases.php?AS_id='.base64_encode($AS_id).'&lightbox[iframe]=true&lightbox[width]=650&lightbox[height]=560" class="lightbox" title="Horario"><img src="../../BAses/Images/icono_calendario_2.png" width="16" height="16" alt="horario" /></a></td>
				   <td><a href="edicion_asignacion/edicion_asignacion_1.php?AS_id='.base64_encode($AS_id).'&lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=550" class="lightbox"><img src="../../BAses/Images/b_edit.png" width="16" height="16" /></a></td>
				  <td><a href="#" onclick="ELIMINAR('.$AS_id.', '.$id_funcionario.');" title="Eliminar"><img src="../../BAses/Images/b_drop.png" width="16" height="16" alt="Eliminar" /></a></td>
				</tr>';
		}
		 $tabla.='<tr>
		 		<td colspan="9">TOTAL</td>
				<td colspan="7"><strong>$ '.number_format($SUMA_TOTAL,0,",",".").'</strong></td>
			</tr>';
	}
	else
	{ $tabla.='<tr><td colspan="16">Sin Asignacion de Ramos Creada en el ['.$semestre.' - '.$year.'] para este Docente</td></tr>';}
	
	$tabla.='</tbody></table>';
	
	$objResponse->Assign($div,"innerHTML",$tabla);
	$sql_1->free();
	$conexion_mysqli->close();
	return $objResponse;
}
/////////////////////////
function GRABA_ASIGNACIONES($id_docente, $semestre, $year, $array_carrera, $asignatura, $numero_horas, $valor_hora, $total, $numero_cuotas, $sede, $jornada, $grupo)
{
	$objResponse = new xajaxResponse();
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	$fecha_actual=date("Y-m-d");
	$array_carrera=explode("_",$array_carrera);
		$id_carrera=$array_carrera[0];
		$carrera=$array_carrera[1];
	require("../../../funciones/conexion_v2.php");
		//para asignaturas normales
		if(DEBUG){$objResponse->alert("Asignatura Normal Seleccionada");}
		if((is_numeric($numero_horas))and($numero_horas>0))
		{$continuar_1=true;}
		else{ $continuar_1=false; $objResponse->Alert("indique Numero de Horas Correcto...");}
		
		if((is_numeric($valor_hora))and($valor_hora>=0))
		{$continuar_2=true;}
		else{ $continuar_2=false; $objResponse->Alert("indique Valor de Hora Correcto...");}
		
		if((is_numeric($total))and($total>=0))
		{$continuar_3=true;}
		else{ $continuar_3=false; $objResponse->Alert("indique Total...");}
		
	
	if(($continuar_1)and($continuar_2)and($continuar_3))
	{
		///busca asignaciones previas
		if($jornada=='A'){
			$arrayJornada=array("D","V");
		$cons="SELECT COUNT(id) FROM toma_ramo_docente WHERE id_funcionario='$id_docente' AND id_carrera='$id_carrera' AND cod_asignatura='$asignatura' AND semestre='$semestre' AND year='$year' AND sede='$sede' AND grupo='$grupo' AND condicion='pendiente'";
		}else{
			$arrayJornada=array($jornada);
			$cons="SELECT COUNT(id) FROM toma_ramo_docente WHERE id_funcionario='$id_docente' AND id_carrera='$id_carrera' AND cod_asignatura='$asignatura' AND semestre='$semestre' AND year='$year' AND sede='$sede' AND jornada='$jornada' AND grupo='$grupo' AND condicion='pendiente'";
		}
		
		$sqli=$conexion_mysqli->query($cons)or die("ERROR: ".$conexion_mysqli->error);
		$Dx=$sqli->fetch_row();
		$coincidencias=$Dx[0];
		if(empty($coincidencias)){$coincidencias=0;}
		
		if(DEBUG){$objResponse->Alert("|---->".$cons."\n Coincidencias:".$coincidencias." ERROR: ".$conexion_mysqli->error."\n");}
		
		if($coincidencias>0){ $grabar=false;  $objResponse->Alert("Asignacion de Hora Ya Existe como pendiente...Verificar");}
		else{ $grabar=true;}
		$sqli->free();
	}
	else{$grabar=false;}
	
	//----------------------------------------------------///
		if($grabar)
		{
			include("../../../funciones/VX.php");
			foreach($arrayJornada as $auxJornada){
				$condicion="pendiente";
				$campos="id_funcionario, id_carrera, jornada, grupo, cod_asignatura, numero_horas, valor_hora, total, numero_cuotas, semestre, year, sede, condicion, fecha_generacion, cod_user";
				$valores="'$id_docente', '$id_carrera', '$auxJornada', '$grupo', '$asignatura', '$numero_horas', '$valor_hora', '$total', '$numero_cuotas', '$semestre', '$year', '$sede', '$condicion', '$fecha_actual', '$id_usuario_actual'";
				$cons_IN="INSERT INTO toma_ramo_docente ($campos) VALUES ($valores)";
				if(DEBUG){ $objResponse->Alert("---->".$cons_IN);}
				else
				{
					$conexion_mysqli->query($cons_IN)or die("graba_asignaciones ".$conexion_mysqli->error);
					//------------------------------------------------//
					
					$evento="Agrega Asignacion a Docente id_funcionario: $id_docente id_carrera: $id_carrera cod_asignatura: $asignatura sede: $sede [$semestre - $year] n. cuotas: $numero_cuotas jornada: $auxJornada";
					REGISTRA_EVENTO($evento);
					
					$descripcion="Agrega Asignacion id_carrera: $id_carrera jornada: $auxJornada Grupo: $grupo cod_asignatura: $asignatura sede: $sede Semestre: $semestre Year: $year";
					REGISTRO_EVENTO_FUNCIONARIO($id_docente, "notificacion", $descripcion);
					//---------------------------------------//
				}
			}
			//-----------------------------------------------------------------------//
			$objResponse->script('document.location.href="asignacion_asignaturas_docente_1.php?fid='.base64_encode($id_docente).'&error=A0";');	
			//-------------------------------------------------------------------------//
				
			
		}//fin si grabar
		else
		{ $objResponse->Alert("No se Puede Grabar esta Asignacion");}
			
		
	
	
	$conexion_mysqli->close();
	return $objResponse;
}
function ACTUALIZA_TOTAL($numero_horas, $valor_hora, $total)
{
	$objResponse = new xajaxResponse();
	
	if((is_numeric($numero_horas))and($numero_horas>0))
	{ $continuar_A=true;}
	else{ $continuar_A=false;}
	
	if((is_numeric($valor_hora))and($valor_hora>=0))
	{ $continuar_B=true;}
	else{ $continuar_B=false;}
	
	if((is_numeric($total))and($total>0))
	{ $continuar_C=true;}
	else{ $continuar_C=false;}
	
	if($continuar_A and $continuar_B)
	{
		$aux_total=($valor_hora*$numero_horas);
		$aux_numero_hora=$numero_horas;
		$aux_valor_hora=$valor_hora;
	}
	elseif($continuar_B and $continuar_C)
	{
		$aux_total=$total;
		$aux_valor_hora=$valor_hora;
		$aux_numero_hora=($aux_total/$aux_valor_hora);
		
	}
	else
	{
		$aux_numero_hora=$numero_horas;
		$aux_valor_hora=$valor_hora;
		$aux_total=$total;
	}
	
	$objResponse->Assign('numero_horas',"value",$aux_numero_hora);
	$objResponse->Assign('valor_hora',"value",$aux_valor_hora);
	$objResponse->Assign('total',"value",$aux_total);

	return $objResponse;
}
$xajax->processRequest();
?>