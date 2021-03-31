<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", true);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Matriculas_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
  	$activo=true;
	
	$hay_advertencias=false;
	$year_actual=date("Y");
	$mes_actual=date("m");
	
	if($mes_actual>=7){$semestre_matricula=2;}
	else{$semestre_matricula=1;}
	
	
	if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
	{if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]){ $activo=true;}}
	
	  if($activo)
	  {
		 require("../../../funciones/conexion_v2.php");
		 require("../../../funciones/funciones_sistema.php");

		 $id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"]; 
		 $id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"]; 
		 $nivel_alumno=$_SESSION["SELECTOR_ALUMNO"]["nivel"]; 
		 
		 $_SESSION["FINANZAS"]["rut_alumno"]=$_SESSION["SELECTOR_ALUMNO"]["rut"];
		 $_SESSION["FINANZAS"]["carrera_alumno"]=$_SESSION["SELECTOR_ALUMNO"]["carrera"];
		 $_SESSION["FINANZAS"]["sede_alumno"]=$_SESSION["SELECTOR_ALUMNO"]["sede"];
		 $_SESSION["FINANZAS"]["paso1"]=false;
			
			//comprueba deuda
			
			$js='c=confirm(\' ADVERTENCIA \n ';
			list($deuda_arancel, $intereses, $gastos_cobranza)=DEUDA_ACTUAL_V2($id_alumno);	
			$TOTAL_DEUDA_ACTUAL=($deuda_arancel + $intereses + $gastos_cobranza);
			if(DEBUG){ echo"$cons<br>Total  deuda: $TOTAL_DEUDA_ACTUAL<br>";}
			if($TOTAL_DEUDA_ACTUAL>0){ $js.='\n -- Alumno Con Deuda Actual de : '.$TOTAL_DEUDA_ACTUAL.' \n\n'; $hay_advertencias=true;}
			
			//comprueba toma de ramos
			list($TR_semestre, $TR_year)=PERIODO_TOMA_RAMO($id_alumno, $id_carrera, "max");
			
			if(($year_actual==$TR_year)and($semestre_matricula=$TR_semestre))
			{ $hay_toma_ramos=true; }
			else{ $hay_toma_ramos=false; $js.='\n -- Alumno sin Toma de Ramos para el periodo ['.$semestre_matricula.'-'.$year_actual.'] \n \n '; $hay_advertencias=true;}
			
           //--------------------------------------------------------------------
		   //verifica encuesta contestada JEFES DE CARRERA
		   
		   
		   if(DEBUG){ echo"Alumno Nivel: $nivel_alumno<br>";}
		   if($nivel_alumno>1)
		   {
			  
			   $id_encuesta_JEFE_CARRERA=15;///id de encuesta de evaluacion jefes de carrera
			   $id_encuesta_EVALUCION_DOCENTE=6;//id de encuesta utilizada para la evaluacion docente
			   $periodo_encuesta_semestre=2;
			   $periodo_encuesta_year=2016;
			   
			   $conse="SELECT COUNT(id_resultados) FROM encuestas_resultados WHERE id_encuesta='$id_encuesta_JEFE_CARRERA' AND id_usuario='$id_alumno' AND tipo_usuario='alumno'";
			   $sqli_e1=$conexion_mysqli->query($conse)or die($conexion_mysqli->error);
			   $E=$sqli_e1->fetch_row();
				$E_num_resultados=$E[0];
				if(DEBUG){ echo"--->$conse<br>N. $E_num_resultados<br>";}
				if(empty($E_num_resultados)){ $E_num_resultados=0;}
				$sqli_e1->free();
				
				if($E_num_resultados>0){ $encuesta_JC_OK=true; if(DEBUG){ echo"Encuesta Jefes de carrera CONTESTADA<br>";}}
				else{$encuesta_JC_OK=false; if(DEBUG){ echo"Encuesta Jefes de carrera NO CONTESTADA<br>";} $hay_advertencias=true;}
				
				if(!$encuesta_JC_OK){ $js.=' -- Alumno No ha contestado Encuesta EVALUCION JEFES DE CARRERA \n\n ';}
		  
		   //-------------------------------------------------------------------------/
		   
		   //ENCUESTA EVALUCION DOCENTE
		   
			   //busco numero de ramos que tomo en periodo a consultar
			   $cons_TR="SELECT COUNT(cod_asignatura) FROM toma_ramos WHERE semestre='$periodo_encuesta_semestre' AND year='$periodo_encuesta_year' AND id_alumno='$id_alumno' AND id_carrera='$id_carrera'";
			   if(DEBUG){ echo"---->$cons_TR<br>";}
			   $sqli_TR=$conexion_mysqli->query($cons_TR)or die($conexion_mysqli->error);
			   $TR=$sqli_TR->fetch_row();
			   $num_TR=$TR[0];
			   if(empty($num_TR)){$num_TR=0;}
			   $sqli_TR->free();
			   if(DEBUG){ echo"Numero de ramos tomados en el periodo[$periodo_encuesta_semestre - $periodo_encuesta_year] = $num_TR<br>";}
			   //reviso cuantas evaluaciones docente realizo
			   $cons_ED="SELECT COUNT(id_usuario_evaluar) FROM encuestas_resultados WHERE semestre_evaluar='$periodo_encuesta_semestre' AND year_evaluar='$periodo_encuesta_year' AND id_usuario='$id_alumno' AND tipo_usuario='alumno' AND id_encuesta='$id_encuesta_EVALUCION_DOCENTE'";
			   if(DEBUG){ echo"--->$cons_ED<br>";}
			   $sqli_evd=$conexion_mysqli->query($cons_ED)or die($conexion_mysqli->error);
			   $EV=$sqli_evd->fetch_row();
			   $num_evaluaciones_docentes=$EV[0];
			   if(empty($num_evaluaciones_docentes)){$num_evaluaciones_docentes=0;}
			   if(DEBUG){ echo"Numero de evaluaciones docentes Realizadas: $num_evaluaciones_docentes<br>";}
			   
			   if($num_evaluaciones_docentes==0)
			   {
				   $js.=' -- Alumno No ha contestado la Evaluacion Docente Periodo ['.$periodo_encuesta_semestre.' - '.$periodo_encuesta_year.'] \n\n';
					$hay_advertencias=true;
				   if(DEBUG){ echo"Evaluacion Docente No realizada<br>"; }
			   }
			   elseif($num_evaluaciones_docentes<$num_TR)
			   {
				   $js.=' -- Alumno No ha contestado completamente la Evaluacion Docente Periodo ['.$periodo_encuesta_semestre.' - '.$periodo_encuesta_year.'] \n\n';
				   $hay_advertencias=true;
					if(DEBUG){ echo"Evaluacion Docente Incompleta<br>"; }
			   }
			   else{
					 if(DEBUG){ echo"Evaluacion Docente OK<br>";}
				   }
		   
		   
		   }
		   else
		   {$encuesta_JC_OK=true;}
		   
		   //--------------------------------------------------------------------------------------///
		   $url="paso1.php";
		   $url_menu="destructor_sesion_finanzas.php?url=HALL";
		   
		   
		   
		  //-----------------------------------------------------------------------------------------------------// 
		  ////-------------------------------------------------------------------------------------------------------/// 
		  $js.="');";
		   if(DEBUG){ echo"$js";}
		  
		 if($hay_advertencias)
		 {
			
			echo'<script languaje="javascript">
			 		'.$js.'
					if(c)
					{window.location="'.$url.'"}
					else
					{ window.location="'.$url_menu.'"}
					</script>';
		 }
		 else
		 { header("location: $url");}
	  
	  }
	  else
	  {header("Location: ../../buscador_alumno_BETA/HALL/index.php");}
	  
	  $conexion_mysqli->close();
?>