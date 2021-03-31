<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//--------------CLASS_okalis------------------//
	require("../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../funciones/";
	$O->clave_del_archivo=md5("Alumno->Notas_Semestrales_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//	
//////////////////////XAJAX/////////////////
@require_once ("../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("ingresanota_individual_server.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"DATOS_NOTA");
$xajax->register(XAJAX_FUNCTION,"CARGA_NOTAS");
////////////////////////////////////////////
//---------------------------------------------------------------------//
//----------------------------------------------------//
function DATOS_NOTA($id_nota_hija, $codigo_nota, $id_carrera, $yearIngresoCarrera, $id_alumno, $id_nota)
{
	$div="resultado";
	$objResponse = new xajaxResponse();
	$fecha_actual=date("Y-m-d");
	$year_actual=date("Y");
	
	$array_semestres=array(1,2);
	require("../../funciones/conexion_v2.php");
	//$objResponse->Alert("id_nota_hija: $id_nota_hija");
	switch($id_nota_hija)
	{
	
		case 0:
			$array_condiciones=array("ok","convalidacion","repeticion", "homologacion");
			$NH_observacion="";
			$X_nota="";
			$X_ano=$year_actual;
			//$X_fecha_recepcion=$fecha_actual;
			$X_observacion="";
			$NH_condicion="ok";
			//--------------------------------------------------------------------------//
			//busco nivel de nota
				$cons_1="SELECT nivel FROM mallas WHERE id_carrera='$id_carrera' AND cod='$codigo_nota' LIMIT 1";
				$sqli_1=$conexion_mysqli->query($cons_1)or die($conexion_mysqli->error);
				$DNN=$sqli_1->fetch_assoc();
					$aux_nivel_nota=$DNN["nivel"];
				$sqli_1->free();
			//------------------------------------------------------------------------------//	
			if($aux_nivel_nota%2==0){ $NH_semestre="2";}
			else{ $NH_semestre="1";}
			
			$escribir=true;
			$escribir_campo_condicion=true;
			break;
		case -1:
			$array_condiciones=array("ok","convalidacion","homologacion", "repeticion");
			$NH_observacion="";
			$escribir=false;
			$escribir_campo_condicion=true;
			break;
		default:	
			$escribir=true;	
			$escribir_campo_condicion=true;
			$array_condiciones=array("ok","convalidacion", "homologacion", "repeticion", "eliminar");
			
				$cons="SELECT * FROM notas_hija WHERE id='$id_nota_hija' LIMIT 1";
				$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
				$D=$sql->fetch_assoc();
					$X_nota=$D["nota"];
					$X_observacion=$D["observacion"];
					$NH_condicion=$D["condicion"];
					
					//echo"--->$NH_condicion<br>";
					$NH_semestre=$D["semestre"];
					$X_ano=$D["year"];
					//$X_fecha_recepcion=$D["fecha_recepcion"];
			$sql->free();
			
				
				break;
	}
	//////////////////---------------------------------------///////////////////
	///busco numero de veces tomo el ramo
		$cons_TR="SELECT COUNT(id) FROM toma_ramos WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera' AND cod_asignatura='$codigo_nota'";
		$sqli_TR=$conexion_mysqli->query($cons_TR)or die("Toma de Ramos ".$conexion_mysqli->error);
			$DTR=$sqli_TR->fetch_row();
			$numero_veces_toma_ramo=$DTR[0];
			if(empty($numero_veces_toma_ramo)){ $numero_veces_toma_ramo=0;}
		$sqli_TR->free();	
		
	 ////////////////////////////////
	 //verifico numero de veces en tabla notas_hija
	 $aux_observacion="";
	 $cons_NH="SELECT COUNT(id) FROM notas_hija WHERE id_nota='$id_nota' AND id_alumno='$id_alumno'";
	 if(DEBUG){ echo"---> $cons_NH<br>";}
	 $sql_NH=$conexion_mysqli->query($cons_NH)or die($conexion_mysqli->error);
	 $AUX=$sql_NH->fetch_row();
	 $num_registros_NH=$AUX[0];
	 $sql_NH->free();
	
	//verifico si realmente puedo calificar segun tomas de ramos hechas
	if($numero_veces_toma_ramo>$num_registros_NH){ $Advertencia=0; $msjVisibleAd='';}
	else{ $Advertencia=1; $msjVisibleAd='(Advertencia Toma Ramo)'; $objResponse->Alert("Advertencia\n La asignatura que pretende calificar, no tiene una toma de ramos previa\n");}
	
	$msjAdvertencia=$msjVisibleAd.'<input name="permitirGrabar" type="hidden" value="'.$Advertencia.'" />';
	
	
	if($escribir_campo_condicion)
	{
			$X_condicion='<select name="condicion[]" id="condicion_'.$codigo_nota.'">';
			foreach($array_condiciones as $n=>$valor)
			{
				if($NH_condicion==$valor)
				{ $X_condicion.='<option value="'.$valor.'" selected="selected">'.$valor.'</option>';}
				else
				{ $X_condicion.='<option value="'.$valor.'">'.$valor.'</option>';}
			}
			$X_condicion.='</select>';
			$objResponse->Assign("div_condicion_$codigo_nota","innerHTML",$X_condicion);
	}
	if($escribir)
	{
	        $X_semestre='<select name="semestre[]" id="semestre=_'.$codigo_nota.'">';
			foreach($array_semestres as $n => $valor)
			{
				if($NH_semestre==$valor){ $select='selected="selected"';}
				else{ $select="";}
				
				$X_semestre.='<option value="'.$valor.'" '.$select.'>'.$valor.'</option>';
			}
			$X_semestre.='</select>';
	
	
		$objResponse->Assign($div,"innerHTML","ID: ".$id_nota_hija." ->OBS: $X_observacion".$msjAdvertencia);
		$objResponse->Assign("div_icono_$codigo_nota","innerHTML",'<img src="../BAses/Images/color_amarillo.png" width="10" height="10" alt="a" />');
		
		
		
		$objResponse->Assign("div_semestre_$codigo_nota","innerHTML",$X_semestre);
		
		$objResponse->Assign("nota_$codigo_nota","value",$X_nota);//nota
		$objResponse->Assign("ano_$codigo_nota","value",$X_ano);
		//$objResponse->Assign("fecha_recepcion_$codigo_nota","value",$X_fecha_recepcion);
		$objResponse->Assign("observacion_$codigo_nota","value",$X_observacion);
	
	}
	$conexion_mysqli->close();
	return $objResponse;
}
function CARGA_NOTAS($id_alumno, $id_carrera, $yearIngresoCarrera){
	require("../../funciones/conexion_v2.php");
	require("../../funciones/funciones_sistema.php");
	
	$year_actual=date("Y");
    $mes_actual=date("M");
	$array_condiciones=array("ok","convalidacion","homologacion","repeticion");	
	$div="Layer1";
	$objResponse = new xajaxResponse();
	$html='<br><form action="grabanotas_individual.php" method="post" name="frm" id="frm">
    <table width="100%" border="0" align="left">
    <thead>
		<tr>
			<th colspan="12">'.$id_carrera.' - '.$yearIngresoCarrera.'</th>
		</tr>	
      <tr> 
      <th>
	  	<input name="id_carrera" type="hidden" value="'.$id_carrera.'" />
		<input name="yearIngresoCarrera" type="hidden" value="'.$yearIngresoCarrera.'" />
		<input name="id_alumno" type="hidden" value="'.$id_alumno.'" />
		</th>
        <th>Cod</th>
        <th>Asignatura</th>
         <th>N. veces Rendida</th>
         <th>N. Toma Ramo</th>
          <th>Accion</th>
          <th>Condicion</th> 
        <th>Nivel</th>
        <th>Nota</th>
        <th>Semestre</th>
        <th> A&ntilde;o</th>
        <th>Observacion</th>
      </tr>
      </thead>
      <tbody>';
   $cb=0;
   $js="";
   $mostrar_boton=false;
    $res="SELECT * FROM notas WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND ramo<>'' AND yearIngresoCarrera='$yearIngresoCarrera' order by cod";
   if(DEBUG){ echo"-->$res<br>";}
   $result=$conexion_mysqli->query($res)or die($conexion_mysqli->error);
   $num_notas=$result->num_rows;
   $aux=0;
   if($num_notas>0){ $mostrar_boton=true;}
   while($row =$result->fetch_assoc()) 
   {
		$enid=$row["id"];
		$cod=$row["cod"];
		$ramo=$row["ramo"];
		$nota=$row["nota"];
		$nivel=$row["nivel"];
		$nota=$row["nota"];
		$semestre=$row["semestre"];
		$es_asignatura=$row["es_asignatura"];
		$ano=$row["ano"];
			if(empty($ano)){$ano=$year_actual;}
		
		$condicion=$row["condicion"];
	 if ($ramo != "")
	 {
	 
	 	///busco numero de veces tomo el ramo
		$cons_TR="SELECT COUNT(id) FROM toma_ramos WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera' AND cod_asignatura='$cod'";
		$sqli_TR=$conexion_mysqli->query($cons_TR)or die("Toma de Ramos ".$conexion_mysqli->error);
			$DTR=$sqli_TR->fetch_row();
			$numero_veces_toma_ramo=$DTR[0];
			if(empty($numero_veces_toma_ramo)){ $numero_veces_toma_ramo=0;}
		$sqli_TR->free();	
		
	 ////////////////////////////////
	 //verifico numero de veces en tabla notas_hija
	 $aux_observacion="";
	 $cons_NH="SELECT * FROM notas_hija WHERE id_nota='$enid' AND id_alumno='$id_alumno' ORDER by id";
	 if(DEBUG){ echo"---> $cons_NH<br>";}
	 $sql_NH=$conexion_mysqli->query($cons_NH)or die($conexion_mysqli->error);
	 $num_registros_NH=$sql_NH->num_rows;
	 $contador_repeticion_asig=0;
	 $repeticion_msj="";
	 $array_notas_hija=array("-1"=>"Sin Accion", 0=>"nueva");
	if($num_registros_NH>0)
	{
		 while($D_NH=$sql_NH->fetch_assoc())
		 {
			 $aux_id_nota_hija=$D_NH["id"];
			 $aux_nota=$D_NH["nota"];
			 $aux_fecha_generacion=$D_NH["fecha_generacion"];
			 $aux_cod_user=$D_NH["cod_user"];
			 $aux_observacion=$D_NH["observacion"];
			 $aux_fecha_recepcion="";//$D_NH["fecha_recepcion"];
			 $contador_repeticion_asig++;
			 $nombre_usuario=NOMBRE_PERSONAL($aux_cod_user);
			 if(DEBUG){ echo"AN: $aux_nota AF: $aux_fecha_generacion C: $contador_repeticion_asig<br>";}
			 $repeticion_msj.="* N $contador_repeticion_asig: $aux_nota Fecha: ".$aux_fecha_generacion." Usuario: [$aux_cod_user]_".$nombre_usuario.' -------------------------------- ';
			 
			 $array_notas_hija[$aux_id_nota_hija]=$aux_nota;
		 }
	}
	else{ $repeticion_msj="Sin Registro";}
	$sql_NH->free();
	 if(empty($aux_observacion)){ $aux_observacion="";}
	 
	 
	 if($numero_veces_toma_ramo>$contador_repeticion_asig){ $color_mensaje='#FF0'; $mensaje_1="Asignatura se Puede Calificar";}
	 elseif($numero_veces_toma_ramo==$contador_repeticion_asig){$color_mensaje='#0F0'; $mensaje_1="No Volver a calificar sin antes realizar toma de Ramos";}
	 else{$color_mensaje='#F00'; $mensaje_1="Falta Realizar Toma de Ramos de Asignatura antes de Calificar";}
	 //////////////////////////////////
	 $html.="<input type='hidden' name='cod[]'  value='$cod'>"; 
	 $aux++;
	
		if($es_asignatura=="1"){$color="";}
		else{$color="#faa";}
	
	 $html.='<tr bgcolor="'.$color.'">
	  		<td><div id="div_icono_'.$cod.'"><img src="../BAses/Images/color_verde.png" width="10" height="10" alt="v"></div></td>
	  		<td align="center">'.$cod.'</td>
			<td align="left">'.$ramo.'</td>
			<td align="center"><a href="#" title="'.$repeticion_msj.'" class="button tooltip">'.$contador_repeticion_asig.'</a></td>
			<td align="center" bgcolor="'.$color_mensaje.'"><a href="#" title="'.$mensaje_1.'" class="button tooltip">'.$numero_veces_toma_ramo.'</a></td>
			<td align="center">
				<select name="accion[]" id="accion_'.$cod.'" onChange="xajax_DATOS_NOTA(this.value, '.$cod.', '.$id_carrera.', '.$yearIngresoCarrera.', '.$id_alumno.', '.$enid.'); return false;">';
				foreach($array_notas_hija as $n =>$valor)
				{
					$html.='<option value="'.$n.'">'.$valor.'</option>';
				}
				$html.='</select>
			</td>
			<td align="center">
			<div id="div_condicion_'.$cod.'">
				<select name="condicion[]" id="condicion_'.$cod.'">';
					foreach($array_condiciones as $indiceX =>$condicionX)
					{
						if($condicion==$condicionX)
						{ $html.='<option value="'.$condicionX.'" selected="selected">'.$condicionX.'</option>';}
						else
						{ $html.='<option value="'.$condicionX.'">'.$condicionX.'</option>';}
					}
			$html.='</select>
			</div>
			</td>
			<td align="center">'.$nivel.'</td>
			<td align="center">
				<input type="text" name="nota[]" size="3" maxlength="3" value="'.$nota.'" id="nota_'.$cod.'"">
				<input name="id_nota_madre[]" type="hidden" value="'.$enid.'">
				<div id="div_posicion_'.$cod.'"></div>
			</td>
			<td align="center">
			<div id="div_semestre_'.$cod.'">'.CAMPO_SELECCION("semestre[]", "semestre", $semestre, false,'',"semestre_$cod").'</div>
	<td align="center">'.CAMPO_SELECCION("ano[]", "year", $ano, false,'',"ano_$cod").'</td>
	<td><input name="observacion[]"  value="'.$aux_observacion.'" type="text" id="observacion_'.$cod.'"></td>
	</tr>';
	
	$js.='cal.manageFields("boton_'.$cod.'", "fecha_recepcion_'.$cod.'", "%Y-%m-%d");';
}   
} 
	$result->free();
	$conexion_mysqli->close();

	$html.=' <tr>
    	<td colspan="13">
        <input name="id_alumno" type="hidden" value="'.$_SESSION["SELECTOR_ALUMNO"]["id"].'">';
		if($mostrar_boton){ $html.='<input type="button" name="Submit" value="Grabar" onClick="CONFIRMAR();">';}else{ $html.="Sin Registro academico";}
	$html.='</td>
    </tr>
    </tbody>
    </table>
	</form>';
	
	$objResponse->Assign($div,"innerHTML",$html);
	return $objResponse;
}
////////////////
$xajax->processRequest();
?>