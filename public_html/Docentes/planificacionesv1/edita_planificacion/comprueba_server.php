<?php
//-----------------------------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(true);
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Planificaciones->Editar");
	$O->PERMITIR_ACCESO_USUARIO();
//-----------------------------------------//
	
//////////////////////XAJAX/////////////////
@require_once ("../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("comprueba_server.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"COMPRUEBA");
//----------------------------------------------//

function COMPRUEBA($num_semana, $id_carrera, $cod_asignatura, $semestre, $year, $sede, $jornada, $grupo, $id_programa, $id_planificacion)
{
	$objResponse = new xajaxResponse();
	$id_usuario=$_SESSION["USUARIO"]["id"];
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/funciones_sistema.php");
		
	if(DEBUG){ $objResponse->Alert("Inicio Comprueba");}
	$html_info="";	
	$div='div_info';
		//------------------------------------------------------------------//
			///horas de programa total
			$TOTAL_HORAS_PROGRAMA=0;
			$cons_HT="SELECT DISTINCT(numero_unidad) FROM programa_estudio WHERE id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura'";
			$sqli_HT=$conexion_mysqli->query($cons_HT)or die($conexion_mysqli->error);
			$num_programas=$sqli_HT->num_rows;
			if($num_programas>0)
			{
				while($HT=$sqli_HT->fetch_row())
				{
					$aux_numero_unidad=$HT[0];
					$aux_CONS="SELECT cantidad_horas FROM programa_estudio WHERE id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND numero_unidad='$aux_numero_unidad' LIMIT 1";
					$sqli_aux=$conexion_mysqli->query($aux_CONS)or die("HP ".$conexion_mysqli->error);
						$Pnh=$sqli_aux->fetch_row();
						$aux_numero_hora_x_unidad=$Pnh[0];
						if(empty($aux_numero_hora_x_unidad)){ $aux_numero_hora_x_unidad=0;}
					$TOTAL_HORAS_PROGRAMA+=$aux_numero_hora_x_unidad;
					$sqli_aux->free();	
				}
			}
			$sqli_HT->free();
			//----------------------------------------------------//
			$numero_horas_X_semana=($TOTAL_HORAS_PROGRAMA/18);
			//---------------------------------------------------//
			if(DEBUG){$objResponse->Alert("N horas X semana: $numero_horas_X_semana<br>");}
			
		//cuantas horas van en esta semana sin contar la planificacion actual 03/10/2017
		$cons_HS="SELECT SUM(horas_semana) FROM planificaciones WHERE id_funcionario='$id_usuario' AND id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND semestre='$semestre' AND year='$year' AND sede='$sede' AND jornada='$jornada' AND grupo='$grupo' AND numero_semana='$num_semana' AND id_planificacion<>'$id_planificacion'";
		$sqli_HS=$conexion_mysqli->query($cons_HS)or die($conexion_mysqli->error);
		$HS=$sqli_HS->fetch_row();
		$num_horas_planificadas_en_semana=$HS[0];
		if(empty($num_horas_planificadas_en_semana)){ $num_horas_planificadas_en_semana=0;}
		$sqli_HS->free();
		
		if(DEBUG){$objResponse->Alert("N horas planificadas en semana [$num_semana]: $num_horas_planificadas_en_semana");}
		//------------------------------------------------------------//
		
		if($num_horas_planificadas_en_semana<$numero_horas_X_semana)
		{$horas_faltantes_para_semana=($numero_horas_X_semana-$num_horas_planificadas_en_semana);}
		else
		{ $horas_faltantes_para_semana=0;}
		//-------------------------------------------------------------//
		if($horas_faltantes_para_semana>0){ $hay_horas_disponibles_en_semana=true;}
		else{ $hay_horas_disponibles_en_semana=false;}
		
		if(DEBUG){$objResponse->Alert("Num horas faltantes a semana: $horas_faltantes_para_semana");}
		//--------------------------------------------------------------//
		
		$campo_horas_semana='<select name="horas_semana">';
		for($y=0;$y<=$horas_faltantes_para_semana;$y++)
		{
			if($y==$horas_faltantes_para_semana)
			{$campo_horas_semana.='<option value="'.$y.'" selected="selected">'.$y.'</option>';}
			else{$campo_horas_semana.='<option value="'.$y.'">'.$y.'</option>';}
		}
		$campo_horas_semana.='</select>';
		//--------------------------------------------///
		
		$objResponse->Assign($div,"innerHTML",$html_info);
		$objResponse->Assign("div_horas_semana","innerHTML",$campo_horas_semana);
		//-----------------------------------------------//	
		
		if($hay_horas_disponibles_en_semana)
		{
			$html_info.="Aun hay $horas_faltantes_para_semana hrs disponibles para planificar en la semana: $num_semana";
			$boton='<a href="#" class="button_G" onclick="CONFIRMAR();">Modificar</a>';
			$objResponse->Assign("div_boton","innerHTML",$boton);
		}
		else
		{
			$html_info.="No hay Horas Disponibles para Planificar en la Semana: $num_semana";
			$boton='<a href="#" class="button_G" onclick="CONFIRMAR();">Modificar</a>';
			$objResponse->Assign("div_boton","innerHTML",$boton);
		}
		$objResponse->Assign("div_info","innerHTML",$html_info);
		
	
		
	$conexion_mysqli->close();	
	return $objResponse;
}
$xajax->processRequest();
?>