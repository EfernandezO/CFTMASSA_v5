<?php
//--------------CLASS_okalis------------------//
	require("../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Toma_de_ramos_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("verifica_matricula_server");
$xajax->configure('javascript URI','../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"VERIFICAR");
$xajax->register(XAJAX_FUNCTION,"CARGA_TOMA_RAMO");

////////////////////////////////////////////
function VERIFICAR($id_alumno, $id_carrera, $yearIngresoCarrera, $semestre, $year)
{
	$objResponse = new xajaxResponse();
	$condicionar_situacion_financiera_alumno=false;
	$dias_morosidad_maximo=20;
	require("../../funciones/conexion_v2.php");
	require("../../funciones/funciones_sistema.php");
	
	$alumno_con_matricula=VERIFICAR_MATRICULA($id_alumno, $id_carrera, $yearIngresoCarrera, true, false, $semestre, false, $year);

	$dias_morosidad_alumno=DIAS_MOROSIDAD($id_alumno);
	if($dias_morosidad_alumno>$dias_morosidad_maximo)
	{ $A_situacion_financiera="Moroso ($dias_morosidad_alumno / $dias_morosidad_maximo)"; $es_moroso=true;}
	else
	{ $A_situacion_financiera="Vigente ($dias_morosidad_alumno / $dias_morosidad_maximo)"; $es_moroso=false;}
	
	//verifico si tiene toma de ramos previa en este periodo
	$cons_TR="SELECT COUNT(id) FROM toma_ramos WHERE id_alumno='$id_alumno' AND semestre='$semestre' AND year='$year'";
	$sqli_TR=$conexion_mysqli->query($cons_TR)or die($conexion_mysqli->error);
	$D_TR=$sqli_TR->fetch_row();
	 $coincidencias=$D_TR[0];
	 if(empty($coincidencias)){ $coincidencias=0;}
	 if(DEBUG){ echo"-->$cons<br>Num: $coincidencias<br>";}
		 if($coincidencias>0){ $existe_toma_de_ramos_previa=true;}
		 else{ $existe_toma_de_ramos_previa=false;}
	$sqli_TR->free();
	//---------------------------------//	 
	
	$continuar=true;
	//------------------------//
	//verifico matricula
	if($alumno_con_matricula){}
	else{ $continuar=false; $objResponse->Alert("Alumno sin Matricula en este Periodo [$semestre - $year]\n No se puede Realizar la Toma de Ramos");}
	//verifico morosidad
	if($condicionar_situacion_financiera_alumno)
	{
		if($es_moroso){$continuar=false; $objResponse->Alert("Alumno Moroso $A_situacion_financiera \n No se puede Realizar la Toma de Ramos");}
		else{ }
	}
	
//---------------------------------------------------------------//	
	
	
	
	if($continuar)
	{
		if($existe_toma_de_ramos_previa)
		{
			$objResponse->Alert("Existe Una Toma de Ramos Previa en este periodo [$semestre - $year]\n Si continua se SOBRESCRIBIRA la toma previa\n Seguro(a) desea continuar...¿?");
			$objResponse->Script("CONFIRMAR_DOBLE()");
				
		}
		else{ $objResponse->Script("CONFIRMAR()");}
	}
	else
	{
		$objResponse->Alert("Alumno sin Matricula en este Periodo [$semestre - $year]\n No se puede Realizar la Toma de Ramos");
	}
	
	
	$conexion_mysqli->close();
	return $objResponse;
}

function CARGA_TOMA_RAMO($id_alumno, $id_carrera, $yearIngresoCarrera){
	
	$NOTA_APROBACION=4;
	$ARRAY_NIVELES_X_SEMESTRE[1]=array(1,3,5);
	$ARRAY_NIVELES_X_SEMESTRE[2]=array(2,4);
	$mes_actual=date("m");
	
	
	$omitirPreRequisito=false;
	/*
	if($id_carrera==4)
	{$omitirPreRequisito=true;}
	else{$omitirPreRequisito=false;}
	*/
	
   
    require("../../funciones/conexion_v2.php");
	require("../../funciones/funciones_sistema.php");
	require("../../funciones/class_ALUMNO.php");
	
   //tomas de ramos previas
   $div2="previas";
   $htmlTomasPrevias="";
   $cons_TR="SELECT `semestre`, `year` FROM `toma_ramos` WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera' GROUP BY `semestre`, `year` ORDER by `year`, `semestre`";
		$sql_TR=$conexion_mysqli->query($cons_TR)or die($conexion_mysqli->error);
		$num_periodos=$sql_TR->num_rows;
		if($num_periodos>0)
		{
			while($PTR=$sql_TR->fetch_assoc())
			{
				$periodo_semestre=$PTR["semestre"];
				$periodo_year=$PTR["year"];
				
				$htmlTomasPrevias.='<a href="ver_toma_ramo/ver_tomaramo_individual.php?semestre='.base64_encode($periodo_semestre).'&year='.base64_encode($periodo_year).'&yearIngresoCarrera='.base64_encode($yearIngresoCarrera).'" class="button_R" target="_blank">'.$periodo_semestre.'-'.$periodo_year.'</a>&nbsp;';
			}
		}
		else
		{ $htmlTomasPrevias="Sin Registros...";}
		$sql_TR->free();
		
		///-----------------------------------------------------
   
   
   
   
   if($mes_actual>=8)///utilizo agosto para inicio 2 semeste
   { $semeste_actual=2;}
   else{ $semeste_actual=1;}
   $year_actual=date("Y");
   
	
	if(($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])and($_SESSION["SELECTOR_ALUMNO"]["id"]==$id_alumno)){$action="graba_toma_ramo.php";}
  else{ $action="";}
  
	$div='area';
	$objResponse = new xajaxResponse();
	
	
	$ALUMNO=new ALUMNO($id_alumno);
	$ALUMNO->SetDebug(DEBUG);
	
	
	$nivel_alumno=$ALUMNO->getNumeroSemestre()+1;
	
	$jornada_alumno=$_SESSION["SELECTOR_ALUMNO"]["jornada"];
	
	
	$html='
	 <table width="60%" border=1 align="center">
    <thead>
	<tr>
		<th colspan="11">'.NOMBRE_CARRERA($id_carrera).' - '.$yearIngresoCarrera.' Semestres ('.$nivel_alumno.')</th>
	</tr>
      <tr> 
      <th width="31" height="15" > 
          <div align="center"><strong>N</strong></div>        </th>
        <th width="31" height="15" > 
          <div align="center"><strong>Cod</strong></div>        </th>
        <th width="221" height="15" > 
          <div align="center"><strong>Asignatura</strong></div>        </th>
        <th width="63" height="15" > 
          <div align="center"><strong>Nivel</strong></div>        </th>
        <th width="57" height="15" > 
          <div align="center"><strong>Nota&nbsp;&nbsp;</strong></div>        </th>
      
          <th width="82" height="15" > 
          <div align="center"><strong>Semestre</strong></div>        </th>
        <th width="54" height="15" > 
          <div align="center"><strong>A&ntilde;o</strong></div>        </th>
        <th width="19" height="15" > 
          <div align="center"><strong>Condicion</strong></div>        </th>
           <th width="82" height="15" > 
          <div align="center"><strong>Jornada</strong></div>        </th> 
        <th width="19" height="15" > 
          <div align="center"><strong>Si</strong></div>        </th>  
        <th width="24" height="15"> 
          <div align="center"><strong>No</strong></div>        </th>
      </tr>
      </thead>
      <tbody>
    <input type="hidden" name="id_alumno" value="'.$id_alumno.'">
    <input type="hidden" name="id_carrera" value="'.$id_carrera.'">
	<input type="hidden" name="yearIngresoCarrera" value="'.$yearIngresoCarrera.'">
	 <input type="hidden" name="nivel_alumno" value="'.$nivel_alumno.'">';

   $cons_1="SELECT * FROM notas WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND ramo<>'' AND yearIngresoCarrera='$yearIngresoCarrera' order by cod";
   $SQL=$conexion_mysqli->query($cons_1)or die($conexion_mysqli->error);
   $num_registros=$SQL->num_rows;
  if(DEBUG){echo"->$cons_1<br>$num_registros Registros Encontrados...<br>";}
	if($num_registros>0)
	{
		$aux=0;
		while($N=$SQL->fetch_assoc())
		{
			$aux++;
			
			$aux_codigo=$N["cod"];
			$aux_nivel=$N["nivel"];
			$aux_nota=$N["nota"];
			$aux_ramo=$N["ramo"];
			$aux_semestre=$N["semestre"];
			$aux_year=$N["ano"];
			$N_id=$N["id"];
			
			$REGISTRO_ACADEMICO[$aux_codigo]["id"]=$N_id;
			$REGISTRO_ACADEMICO[$aux_codigo]["nota"]=$aux_nota;
			$REGISTRO_ACADEMICO[$aux_codigo]["ramo"]=$aux_ramo;
			$REGISTRO_ACADEMICO[$aux_codigo]["year"]=$aux_year;
			$REGISTRO_ACADEMICO[$aux_codigo]["nivel"]=$aux_nivel;
			$REGISTRO_ACADEMICO[$aux_codigo]["semestre"]=$aux_semestre;
			
			
			if(DEBUG){ echo"($aux)-->$N_id - $aux_nota - $aux_nivel - $aux_ramo codigo: $aux_codigo<br>";}
			
			$cons_PR="SELECT * FROM mallas WHERE cod='$aux_codigo' AND id_carrera='$id_carrera' LIMIT 1";
			$sql_PR=$conexion_mysqli->query($cons_PR)or die($conexion_mysqli->error);
			$num_registro_prerequisito=$sql_PR->num_rows;	
			if(DEBUG){ echo"-------->$cons_PR<br>numero registro pre-requisitos: $num_registro_prerequisito<br><br>";}
			if($num_registro_prerequisito>0)
			{
				$PR=$sql_PR->fetch_assoc();
				$REGISTRO_ACADEMICO[$aux_codigo]["pre_requisito"][1]=$PR["pr1"];
				$REGISTRO_ACADEMICO[$aux_codigo]["pre_requisito"][2]=$PR["pr2"];
				$REGISTRO_ACADEMICO[$aux_codigo]["pre_requisito"][3]=$PR["pr3"];
				$REGISTRO_ACADEMICO[$aux_codigo]["pre_requisito"][4]=$PR["pr4"];
				$REGISTRO_ACADEMICO[$aux_codigo]["pre_requisito"][5]=$PR["pr5"];
				$REGISTRO_ACADEMICO[$aux_codigo]["pre_requisito"][6]=$PR["pr6"];
				$REGISTRO_ACADEMICO[$aux_codigo]["pre_requisito"][7]=$PR["pr7"];
				$REGISTRO_ACADEMICO[$aux_codigo]["pre_requisito"][8]=$PR["pr8"];
				$REGISTRO_ACADEMICO[$aux_codigo]["pre_requisito"][9]=$PR["pr9"];
				$REGISTRO_ACADEMICO[$aux_codigo]["pre_requisito"][10]=$PR["pr10"];
				
			}
			else
			{
				if(DEBUG){ echo"MALLA no ENCONTRADA sin PRERREQUISITOS CARGADOS<br>";}
			}
			$sql_PR->free();
		}
	}
	else
	{
		if(DEBUG){ echo"Sin Registro academico Creado...<br>";}
	}

  $SQL->free(); 
  ///---------------FIN LLENADO DE ARRAY-------------------//
  $asignaturas_evaluadas=0;
  $asignaturas_reprobadas=0;
  $asignaturas_aprobadas=0;
  if(isset($REGISTRO_ACADEMICO))
  {
  
  	if(DEBUG){ var_dump($REGISTRO_ACADEMICO);}
		
		//$num_ramos_debe_tomar=0;
		$contador=0;
		
		//$fuera_de_nivel=false;	
		foreach($REGISTRO_ACADEMICO as $codigo_X=>$array_X)
		{
			$contador++;
			$puede_tomar_ramo=false;
			$permitir_elegir=false;
			$tiene_pre_requisitos=false;
			$nota_X=$array_X["nota"];
			$ramo_X=$array_X["ramo"];
			$nivel_X=$array_X["nivel"];
			$year_X=$array_X["year"];
			$semestre_X=$array_X["semestre"];
			
			if(is_numeric($nota_X))
			{
				$asignaturas_evaluadas++;
				if($nota_X>=$NOTA_APROBACION)
				{ $asignaturas_aprobadas++;}
				else
				{ $asignaturas_reprobadas++;}
			}
			
			$html.='<tr>
					<td>'.$contador.'</td>
					<td>'.$codigo_X.'</td>
					<td>'.$ramo_X.'</td>
					<td align="center">'.$nivel_X.'</td>
					<td>'.$nota_X.'</td>
					<td>'.$semestre_X.'</td>
					<td>'.$year_X.'</td>';
					
			if($nivel_X<=$nivel_alumno)
			{
				$puede_tomar_ramo=true;
				$faltan_notas_nivel_anterior=false;
				if(DEBUG){ echo"NIVEL $nivel_X ->CODIGO $codigo_X  nota: $nota_X ramo: $ramo_X YEAR: $year_X<br>";}
				
				
				$info_de_ramo="";
				foreach($array_X["pre_requisito"] as $n =>$cod_pre_requisito)
				{
					if(DEBUG){ echo"===>$n $cod_pre_requisito";}
					
					if($cod_pre_requisito>0)
					{
						$tiene_pre_requisitos=true;
						
						$pre_requisito_ramo=$REGISTRO_ACADEMICO[$cod_pre_requisito]["ramo"];
						$pre_requisito_nota=$REGISTRO_ACADEMICO[$cod_pre_requisito]["nota"];
						if(DEBUG){ echo" =>HAY pre-requisito [<tt> $pre_requisito_ramo $pre_requisito_nota</tt>] ";}
						
						if($pre_requisito_nota>=4)
						{
							if(DEBUG){ echo"APROBADO<br>";}
							$info_de_ramo.="* $pre_requisito_ramo [APROBADO] ";
						}
						else
						{
							if(DEBUG){ echo"REPROBADO<br>";}
							$puede_tomar_ramo=false;
							$info_de_ramo.="* $pre_requisito_ramo [REPROBADO] ";
						}
					}
					else
					{
						if(DEBUG){ echo"*  sin pre-requisito";}
					}
				}//fin recorrido pre-requisito
				$fuera_de_nivel=false;
	
			}
			else
			{
				if(DEBUG){ echo"NIVEL $nivel_X CODIGO -> $codigo_X FUERA DE NIVEL<br>"; $fuera_de_nivel=true;}
			}
			/////////////////////////////////////////////////////
			
				if(@$fuera_de_nivel)
				{ $info_de_ramo="fuera de Nivel";}
				else
				{
					if(!$tiene_pre_requisitos)
					{ $info_de_ramo="sin pre-requisito";}
				}
		
			
			//$puede_tomar_ramo=true;
			//$permitir_elegir=true;
			///////////////////////////////////////////////////////
			if(($puede_tomar_ramo)or ($omitirPreRequisito))
			{
				if(DEBUG){ echo"puede tomar el ramo<br>";}
				if($nota_X>0)
				{
					if($nota_X>=4)
					{ $html.='<td align="center"><a href="#" class="hint--left  hint--info" data-hint="'.$info_de_ramo.'"><img src="../BAses/Images/ok.png" width="29" height="26" alt="ok"></a></td>';}
					else
					{ $html.='<td align="center"><a href="#" class="hint--left  hint--info" data-hint="'.$info_de_ramo.'"><img src="../BAses/Images/b_drop.png" width="16" height="16" alt="X"></a></td>'; $permitir_elegir=true;}
				}
				else
				{
					 $html.='<td align="center"><a href="#" class="hint--left  hint--info" data-hint="'.$info_de_ramo.'"><img src="../BAses/Images/-.png" width="16" height="5" alt="-"></a></td>';
					 $permitir_elegir=true;
				}
				
			}
			else
			{
				if(DEBUG){ echo"NO puede tomar el ramo<br><br>";}
				$html.='<td align="center"><a href="#" class="hint--left  hint--info" data-hint="'.$info_de_ramo.'"><img src="../BAses/Images/clock.png" width="29" height="26" ></a></td>';
			}
			
			if($permitir_elegir)
			{
				if(in_array($nivel_X,$ARRAY_NIVELES_X_SEMESTRE[$semeste_actual]))
				{
					$check_1="checked";
					$check_2="";
				}
				else
				{
					$check_1="";
					$check_2="checked";
				}
				$html.='<td>'.CAMPO_SELECCION("jornada[".$codigo_X."]","jornada",$jornada_alumno).'</td>';
				$html.='<td><input name="tomar_ramo['.$codigo_X.']" type="radio" value="si" '.$check_1.'></td>
					 <td><input name="tomar_ramo['.$codigo_X.']" type="radio" value="no" '.$check_2.'></td>';
			}
			else
			{
				$html.='<td>&nbsp;</td>
					 <td>&nbsp;</td>
					 <td>&nbsp;</td>';
			}
			$html.='</tr>';
		}//fin foreach
  
  		$html.='</tbody>
    <tfoot>
    <tr>
    	<td colspan="11"><input type="button" name="Submit" value="Correcto, Continuar con toma de Ramos" onClick="xajax_VERIFICAR('.$id_alumno.', '. $id_carrera.','.$yearIngresoCarrera.', document.getElementById(\'semestre\').value, document.getElementById(\'year\').value); return false;"><br>Total Asignaturas Calificadas:'. $asignaturas_evaluadas.'<br>Total asignaturas Aprobadas:'.$asignaturas_aprobadas.'<br> Total Asignaturas Reprobadas: '.$asignaturas_reprobadas.'</td>
    </tr>
    </tfoot>
    </table>';
  }////fin si hay array registro academico
  else
  {
	  $html.='<tr>
	  			<td colspan="11">Alumno sin registro academico creado...</td>
	  		<tr>';
  }
 	$conexion_mysqli->close();
  
	
	
	$objResponse->Assign($div,"innerHTML",$html);
	$objResponse->Assign($div2,"innerHTML",$htmlTomasPrevias);
	return $objResponse;
}

$xajax->processRequest();
?>