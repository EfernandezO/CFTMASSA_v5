<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	//$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("ALUMNO->intranet");
	$O->PERMITIR_ACCESO_USUARIO();
	$O->anti2LoggAlumno();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("carga_notas_parciales_v3_1_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"VER_MALLA");
$xajax->register(XAJAX_FUNCTION,"VERIFICAR");

function VERIFICAR($id_alumno, $id_carrera, $yearIngresoCarrera, $semestre, $year)
{
	$objResponse = new xajaxResponse();
	$condicionar_situacion_financiera_alumno=false;
	$dias_morosidad_maximo=20;
	
	//permite realizar toma de ramos solo para este periodo
	$semestrePermitido=1;
	$yearpermitido=2021;
	
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	
	$alumno_con_matricula=VERIFICAR_MATRICULA($id_alumno, $id_carrera, $yearIngresoCarrera, true, false, $semestre, false, $year);
	//$alumno_con_matricula=true;
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
	if(($semestre==$semestrePermitido)and($year==$yearpermitido)){}
	else{$continuar=false; $objResponse->Alert("Periodo [$semestre - $year]\n No se PERMITE Realizar la Toma de Ramos");}
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
			//$objResponse->Script("CONFIRMAR_DOBLE()");
				
		}
		else{ $objResponse->Script("CONFIRMAR()");
		}
	}
	else
	{
		$objResponse->Alert("Alumno sin Matricula en este Periodo [$semestre - $year]\n No se puede Realizar la Toma de Ramos");
	}
	
	
	$conexion_mysqli->close();
	return $objResponse;
}

function VER_MALLA($semestre, $year, $id_alumno, $id_carrera, $yearIngresoCarrera){
	
	$NOTA_APROBACION=4;
	$ARRAY_NIVELES_X_SEMESTRE[1]=array(1,3,5);
	$ARRAY_NIVELES_X_SEMESTRE[2]=array(2,4);
	$mes_actual=date("m");
	
	$omitirPreRequisito=false;
	
   
    require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	require("../../../funciones/class_ALUMNO.php");
	
  
	$div='div_resultado';
	$objResponse = new xajaxResponse();
	
	
	$ALUMNO=new ALUMNO($id_alumno);
	$ALUMNO->SetDebug(DEBUG);
	
	
	$nivel_alumno=$ALUMNO->getNumeroSemestre()+1;
	$jornada_alumno=$ALUMNO->getJornadaActual();
	
	$semestre_actual=$semestre;
	$year_actual=$year;
	
	
	$html='<div class="widget orange">
	<form name="frm" id="frm" action="tomaRamosAlumnoREC.php" method="post">
		<div class="widget-title">
			<h4><i class="icon-reorder"></i>'.NOMBRE_CARRERA($id_carrera).' - '.$yearIngresoCarrera.' Semestres ('.$nivel_alumno.')</h4>
		<span class="tools">
			<a href="javascript:;" class="icon-chevron-down"></a>
		</span>
		</div>
		<div class="widget-body">
		<div class="alert alert-success" role="alert">*Marque en "Si" los ramos que desea tomar en este periodo y luego presione el boton "Grabar Toma de Ramos"</div>
			<table class="table table-striped table-bordered table-advance table-hover">
				<thead>
				<tr>
					<th><i class="icon-sort-by-attributes"></i>Cod</th>
					<th><i class="icon-sun"></i>Asignatura</th>
					<th><i class="icon-bookmark"></i>Nivel</th>
					<th><i class="icon-edit"></i>Si</th>
					<th><i class="icon-edit"></i>No</th>
				</tr>
				</thead>
				<tbody><input type="hidden" name="id_alumno" value="'.$id_alumno.'">
    <input type="hidden" name="id_carrera" value="'.$id_carrera.'">
	<input type="hidden" name="yearIngresoCarrera" value="'.$yearIngresoCarrera.'">
	<input type="hidden" name="semestre" value="'.$semestre.'">
	<input type="hidden" name="year" value="'.$year.'">
	<input type="hidden" name="jornadaAlumno" value="'.$jornada_alumno.'">
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
			
			$htmlFila='<tr>
					<td>'.$codigo_X.'</td>
					<td>'.$ramo_X.'</td>
					<td align="center">'.$nivel_X.'</td>';
					
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
					{ //$htmlFila.='<td align="center"><a href="#" class="hint--left  hint--info" data-hint="'.$info_de_ramo.'"><img src="../BAses/Images/ok.png" width="29" height="26" alt="ok"></a></td>';
					}
					else
					{ //$htmlFila.='<td align="center"><a href="#" class="hint--left  hint--info" data-hint="'.$info_de_ramo.'"><img src="../BAses/Images/b_drop.png" width="16" height="16" alt="X"></a></td>'; 
					$permitir_elegir=true;
					}
				}
				else
				{
					//$htmlFila.='<td align="center"><a href="#" class="hint--left  hint--info" data-hint="'.$info_de_ramo.'"><img src="../BAses/Images/-.png" width="16" height="5" alt="-"></a></td>';
					 $permitir_elegir=true;
				}
				
			}
			else
			{
				if(DEBUG){ echo"NO puede tomar el ramo<br><br>";}
				//$htmlFila.='<td align="center"><a href="#" class="hint--left  hint--info" data-hint="'.$info_de_ramo.'"><img src="../BAses/Images/clock.png" width="29" height="26" ></a></td>';
			}
			
			$enPerido_de_elegir=false;
			if($permitir_elegir)
			{
				if(in_array($nivel_X,$ARRAY_NIVELES_X_SEMESTRE[$semestre_actual]))
				{
					$check_1="checked";
					$check_2="";
					$enPerido_de_elegir=true;
				}
				else
				{
					$check_1="";
					$check_2="checked";
				}
				//$htmlFila.='<td>'.CAMPO_SELECCION("jornada[".$codigo_X."]","jornada",$jornada_alumno).'</td>';
				$htmlFila.='<td><input name="tomar_ramo['.$codigo_X.']" type="radio" value="si" '.$check_1.'></td>
					 <td><input name="tomar_ramo['.$codigo_X.']" type="radio" value="no" '.$check_2.'></td>';
			}
			else
			{
				$htmlFila.='<td>&nbsp;</td>
					 <td>&nbsp;</td>
					 <td>&nbsp;</td>';
			}
			$htmlFila.='</tr>';
			
			if($permitir_elegir and $enPerido_de_elegir){$html.=$htmlFila;}
		}//fin foreach
  
	
	
	$html.='</tbody>
			<tfoot>
				<td colspan="10">*Importante: Si ud. realiza la toma de ramos por este medio debe imprimir su comprobante y enviarlo firmado a secretaria, de lo contrario debe dirigirse a secretaria a firmar su comprobante, indicando que el proceso ya fue realizado.</td>
			</tfoot>
			 </table>
			 <button type="button" class="btn btn-large btn-success" onClick="xajax_VERIFICAR('.$id_alumno.', '. $id_carrera.','.$yearIngresoCarrera.', '.$semestre.', '.$year.'); return false;">Grabar Toma de Ramos</button>
					</div>
					</form>
					</div>';
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