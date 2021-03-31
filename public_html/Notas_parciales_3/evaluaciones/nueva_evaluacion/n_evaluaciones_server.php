<?php
session_start();
//////////////////////XAJAX/////////////////
@require_once ("../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("n_evaluaciones_server.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"N_EVALUACIONES");
$xajax->register(XAJAX_FUNCTION,"VERIFICAR");
$xajax->register(XAJAX_FUNCTION,"NOMBRE_EVALUACION");
define("DEBUG", false);

function N_EVALUACIONES($numero, $metodo_evaluacion, $cod_asignatura, $sede, $id_carrera, $semestre, $year, $jornada, $grupo)
{
	$porcentaje_maximo=100;
	$msj_error="";
	require("../../../../funciones/conexion_v2.php");
	
	$div="notas";
	$DEBUG="";
	$objResponse = new xajaxResponse();
	
	///busco que tipo tiene las notas de esta asignatura, en el mismo intento
	$cons="SELECT DISTINCT(metodo_evaluacion) FROM notas_parciales_evaluaciones WHERE sede='$sede' AND id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND jornada='$jornada' AND grupo='$grupo' AND semestre='$semestre' AND year='$year'";
	$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$D=$sql->fetch_row();
		$metodo_evaluacion_old=$D[0];
	$sql->free();
	$DEBUG.="Metodo evaluacion old: $metodo_evaluacion_old<br> NOW: $metodo_evaluacion<br><br>";
	if(empty($metodo_evaluacion_old))
	{
		$porcentaje_acumulado=0; 
		$porcentaje_a_pactar=$porcentaje_maximo;
		$escribir_tabla=true;
		$DEBUG.="[No hay Registros Previos para esta Asignatura]<br>";
	}
	else
	{
		if($metodo_evaluacion_old==$metodo_evaluacion)
		{
			if($metodo_evaluacion=="ponderado")
			{
				$cons_P="SELECT SUM(porcentaje) FROM notas_parciales_evaluaciones WHERE sede='$sede' AND id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND jornada='$jornada' AND grupo='$grupo' AND semestre='$semestre' AND year='$year'";
				$sql_P=$conexion_mysqli->query($cons_P)or die($conexion_mysqli->error);
				$DP=$sql_P->fetch_row();
					$porcentaje_acumulado=$DP[0];
					$porcentaje_a_pactar=($porcentaje_maximo-$porcentaje_acumulado);
				$sql_P->free();
				
				if($porcentaje_a_pactar>0){ $escribir_tabla=true;}
				else{ $escribir_tabla=false; $msj_error="Porcentaje Ponderacion Notas Anteriores Alcanzo limite...(100%)";}
			}
			else
			{$escribir_tabla=true;}
		}
		else
		{ $escribir_tabla=false; $msj_error="No Puede Mezclar Metodos diferentes de Evaluacion OLD: $metodo_evaluacion_old - NOW: $metodo_evaluacion";}
	}
	@mysql_close($conexion);
	$conexion_mysqli->close();
	
	if($metodo_evaluacion=="ponderado")	
	{ $DEBUG.="X METODO $metodo_evaluacion ->% Acumulado: $porcentaje_acumulado - % a Pactar :$porcentaje_a_pactar<br>";}
	else{ $DEBUG.="--->METODO $metodo_evaluacion -> $metodo_evaluacion_old<br>";}
	
	if($escribir_tabla)
	{
		$html="$numero";
		$html='<table  width="100%"  summary="notas" border="0">
				<thead>
					<th>N.</th>
					<th>Tipo</th>
					<th>Nombre Evaluacion</th>
					<th>Fecha Evaluacion</th>';
				if($metodo_evaluacion=="ponderado")	
				{ $html.='<th>%</th>';}
			$html.='</thead>
				<tbody>';
		if((is_numeric($numero))and($numero>0)and($numero<10))
		{
			$dia_actual=date("d");
			$mes_actual=date("m");
			$year_actual=date("Y");
			 $año_ini=($year_actual-100);
		  $año_fin=($year_actual);
			$array_mes=array(1=>"Enero",2=>"Febrero", 3=>"Marzo", 4=>"Abril", 5=>"Mayo", 6=>"Junio",7=>"Julio",8=>"Agosto", 9=>"Septiembre", 10=>"Octubre", 11=>"Noviembre", 12=>"Diciembre");
			for($x=0;$x<$numero;$x++)
			{
				$j=$x+1;
				
				/////////////////////TIPO PRUEBA/////////////////////////
				$campo_prueba_tipo='<select name="tipo_prueba[]" id="tipo_prueba_'.$j.'" onchange="xajax_NOMBRE_EVALUACION(\'nombre_evaluacion_'.$j.'\', this.value); return false;">
				<option value="parcial" selected="selected">Parcial</option>
				<option value="global">Global</option>
				<option value="repeticion">Repeticion</option>
				</select>';
				///////////////////FECHAS//////////////////////////////
				$fecha_evaluacion='<select name="fecha_evaluacion_dia[]" id="fecha_evaluacion_dia_'.$j.'">';
				for($d=1;$d<=31;$d++)
				{
					if($d==$dia_actual)
					{if($d<10){$d="0".$d;} $fecha_evaluacion.='<option value="'.$d.'" selected="selected">'.$d.'</option>';}
					else
					{if($d<10){$d="0".$d;} $fecha_evaluacion.='<option value="'.$d.'">'.$d.'</option>';}	
				}
		  $fecha_evaluacion.='</select>/
		  <select name="fecha_evaluacion_mes[]" id="fecha_evaluacion_mes_'.$j.'">';
		  
			foreach($array_mes as $n => $valor)
			{
				if($n == $mes_actual)
				{if($n<10){$n="0".$n;} $fecha_evaluacion.='<option value="'.$n.'" selected="selected">'.$valor.'</>';}
				else
				{if($n<10){$n="0".$n;} $fecha_evaluacion.='<option value="'.$n.'">'.$valor.'</>';}	
			}
		$fecha_evaluacion.='</select>/<select name="fecha_evaluacion_year[]" id="fecha_evaluacion_year_'.$j.'">';
		  for($año=$año_ini;$año<=$año_fin;$año++)
		  {
			if($año==$year_actual)
			{$fecha_evaluacion.='<option value="'.$año.'" selected="selected">'.$año.'</option>';}
			else
			{$fecha_evaluacion.='<option value="'.$año.'">'.$año.'</option>';}	
		  }
		  $fecha_evaluacion.='</select>';
		  /////////////////////////////////////////////////
				$html.='<tr>
				<td align="center">'.$j.'</td>
				<td align="center">'.$campo_prueba_tipo.'</td>

				<td align="center"><input name="nombre_evaluacion[]" id="nombre_evaluacion_'.$j.'" type="text" value="Prueba Parcial '.$j.'"/></td>
				<td align="center">'.$fecha_evaluacion.'</td>';
				if($metodo_evaluacion=="ponderado")
				{ $html.='<td align="center"><input name="porcentaje[]" id="porcentaje_'.$j.'" type="text" value="0" size="5"/></td>';}
				$html.='<tr>';
			}
		$html.='</tbody></table>';
		}
		else
		{$html="<b>Seleccione numero de Notas...</b>";}
		
		$objResponse->Assign("div_debug","innerHTML",$DEBUG);
		$objResponse->Assign("apDiv3","innerHTML",'<a href="#" class="button_G" onclick="xajax_VERIFICAR(xajax.getFormValues(\'frmN\')); return false;">Grabar Evaluaciones</a>');
		$objResponse->Assign($div,"innerHTML",$html);
		
	}//fin si escribir tabla
	else
	{
		$objResponse->Assign("apDiv3","innerHTML",'');
		$objResponse->Alert("ERROR: $msj_error");
	}
	return $objResponse;
}

function NOMBRE_EVALUACION($campo_nombre, $tipo)
{
	$objResponse = new xajaxResponse();
	
	$aux_nombre_evaluacion="";
	switch($tipo)
	{
		case"parcial":
			$aux_nombre_evaluacion="Prueba Parcial";
			break;
		case"global":
			$aux_nombre_evaluacion="Prueba Global";
			break;
		case"repeticion":
			$aux_nombre_evaluacion="Prueba Repeticion";
			break;		
	}
	
	$objResponse->Assign($campo_nombre,"value",$aux_nombre_evaluacion);
	return $objResponse;
}


function VERIFICAR($FORMULARIO)
{
	
	$DEBUG="";
	require("../../../../funciones/conexion_v2.php");
	
	$objResponse = new xajaxResponse();
	$porcentaje_maximo=100;
		//var_export($FORMULARIO);
		
		$id_carrera=$FORMULARIO["id_carrera"];
		$cod_asignatura=$FORMULARIO["fasignatura"];
  		$numero_notas=$FORMULARIO["fn_notas"];
  		$sede=$FORMULARIO["sede"];
  		$metodo_evaluacion=$FORMULARIO["metodo_evaluacion"];
		$jornada=$FORMULARIO["jornada"];
		$grupo=$FORMULARIO["grupo"];
		$semestre=$FORMULARIO["semestre"];
		$year=$FORMULARIO["year"];
		
		$array_tipo_evaluaciones=$FORMULARIO["tipo_prueba"];
		
		switch($metodo_evaluacion)
		{
			case"ponderado":
				$total_porcentaje_actual=0;
				$array_porcentaje=$FORMULARIO["porcentaje"];
				
				
				$cons_P="SELECT SUM(porcentaje) FROM notas_parciales_evaluaciones WHERE id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND id_carrera='$id_carrera' AND sede='$sede' AND semestre='$semestre' AND year='$year' AND jornada='$jornada' AND grupo='$grupo'";
				$sql_P=mysql_query($cons_P)or die(mysql_error());
				$DP=mysql_fetch_row($sql_P);
				$porcentaje_acumulado=$DP[0];
				$porcentaje_a_pactar=($porcentaje_maximo-$porcentaje_acumulado);
				mysql_free_result($sql_P);
				$porcentajes_correctos=true;
				foreach($array_porcentaje as $indice=>$aux_porcentaje)
				{ 
					if(($aux_porcentaje<=0)or(!is_numeric($aux_porcentaje))){ $porcentajes_correctos=false;}
					 $total_porcentaje_actual+=$aux_porcentaje;
				}
				mysql_close($conexion);
				$DEBUG.="VERIFICANDO:Ponderado: Total % actual: $total_porcentaje_actual - Maximo % a Pactar: $porcentaje_a_pactar<br>";
				/////////////////////
				if($porcentajes_correctos)
				{
					if($total_porcentaje_actual>$porcentaje_a_pactar)
					{ $permite_grabar=false;}
					else
					{ $permite_grabar=true;}
				}
				else
				{
					$permite_grabar=false;
					$DEBUG.="% ingresado Invalido Verificar...<br>";
				}
				
				break;
			case"normal":	
				$permite_grabar=true;
				$DEBUG.="VERIFICANDO: Normal<br>Se puede Grabar OK<br>";
				break;
		}
	
	
	//--------------------------------//
	$cons_PG="SELECT tipo_evaluacion FROM notas_parciales_evaluaciones WHERE id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND id_carrera='$id_carrera' AND sede='$sede' AND semestre='$semestre' AND year='$year' AND jornada='$jornada' AND grupo='$grupo'";
	
	$sqli_PG=$conexion_mysqli->query($cons_PG)or die($conexion_mysqli->error);
	$num_evaluacion_x1=$sqli_PG->num_rows;
	
	$N_parciales_old=0;
	$N_globales_old=0;
	$N_repeticiones_old=0;
	
	if($num_evaluacion_x1>0)
	{
		while($TE=$sqli_PG->fetch_assoc())
		{
			$TE_tipo_evaluacion=$TE["tipo_evaluacion"];
			switch($TE_tipo_evaluacion)
			{
				case"parcial":
					$N_parciales_old++;
					break;
				case"global":
					$N_globales_old++;
					break;
				case"repeticion":
					$N_repeticiones_old++;
					break;
			}
		}
		
		if(DEBUG){$DEBUG.="----------------------------<br> Parciales old: $N_parciales_old<br>Globales old: $N_globales_old<br>Repeticion old: $N_repeticiones_old<br>-------------------------------<br>";}

	}
	$sqli_PG->free();
	
		//----------------------------------------------------------//
		//reviso el tipo de evaluaciones que ahora intento crear
		$N_parciales_new=0;
		$N_globales_new=0;
		$N_repeticiones_new=0;
		if(count($array_tipo_evaluaciones)>0)
		{
			foreach($array_tipo_evaluaciones as $ne => $valore)
			{
				switch($valore)
				{
					case"parcial":
						$N_parciales_new++;
						break;
					case"global":
						$N_globales_new++;
						break;
					case"repeticion":
						$N_repeticiones_new++;
						break;
				}
			}
		}
		
		
		if(DEBUG){$DEBUG.="----------------------------<br> Parciales new: $N_parciales_new<br>Globales new: $N_globales_new<br>Repeticion new: $N_repeticiones_new<br>-------------------------------<br>";}
	//----------------------------------------------------------------------------------//
	
	$N_parciales_total=($N_parciales_old+$N_parciales_new);
	$N_globales_total=($N_globales_old+$N_globales_new);
	$N_repeticiones_total=($N_repeticiones_old+$N_repeticiones_new);
	//-----------------------------------------------------------------------------------//
	$condicion_global=true;
	$condicion_repeticion=true;
	
	if($N_globales_total>1){ $condicion_global=false; $objResponse->Alert("Solo se permite una Prueba Global");}
	if($N_repeticiones_total>1){ $condicion_repeticion=false; $objResponse->Alert("Solo se permite una Prueba de Repeticion");}
	
	$DEBUG.="----------------------------<br> Parciales: $N_parciales_total<br>Globales: $N_globales_total<br>Repeticion: $N_repeticiones_total<br>-------------------------------<br>";
	
	if($condicion_global and $condicion_repeticion){ $permite_grabar_2=true; $DEBUG.="Se puede Grabar OK<br>";}
	else{ $permite_grabar_2=false; }
	
	
	
	
	
	
	$objResponse->Assign("div_debug","innerHTML",$DEBUG);	
	//------------------------------------//
	if($permite_grabar and $permite_grabar_2)
	{ $objResponse->script("CONFIRMAR();");}
	else
	{ $objResponse->Alert("NO se PUEDE GRABAR");}
	return $objResponse;
}
$xajax->processRequest();
?>