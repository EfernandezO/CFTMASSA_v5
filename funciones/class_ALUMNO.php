<?php
/*
33525da1e53d23189c9291d0e74b076b
clase ALUMNO para cftmassachusetts 2019
eliasfernandezo@gmail.com

dato un id_alumno, permite obtener informacion de ese alumno
*/
class ALUMNO{
	private $idAlumnoObjeto=0;
	private $rutAlumnoObjeto;
	private $nombreAlumnoObjeto;
	private $apellido_PAlumnoObjeto;
	private $apellido_MAlumnoObjeto;
	private $sexoAlumnoObjeto;
	private $fechaNacimiento;
	private $ciudad;
	private $direccion;
	private $fono;
	private $fonoApoderado;
	private $email;
	private $emailInstitucional;
	private $estadoCivil;
	private $nacionalidad;
	private $paisOrigen;
	private $paisEstudiosMedios;
	private $liceo_formacion;
	private $liceo_dependencia;
	private $liceo;
	private $yearEgresoLiceo;
	private $otro_estudio_U;
	private $otro_estudio_T;
	private $otro_estudio_P;
	
	private $numeroMatriculasAlumno;
	private $arrayMatriculasAlumnoObjeto=array();
	
	private $numeroSemestresAlumno;
	private $nivelAcademicoAlumnoMaximo;
	private $nivelAcademicoAlumnoObjeto;
	private $sedeActualAlumnoObjeto;
	private $jornadaActualAlumnoObjeto;
	private $DEBUG=false;
	
	private $nivelAcademicoAlumnoPeriodo;
	private $sedeAlumnoPeriodo;
	private $jornadaAlumnoPeriodo;
	private $situacionAlumnoPeriodo;
	private $idCarreraAlumnoPeriodo;
	private $yearIngresoCarreraPeriodo;
	private $idContratoPeriodo;
	private $presenteEnPeriodo;
	
	private $ruta_conexion;
	private $ERROR=0;
	

	function __construct($id_alumno=0){
		//si esta iniciada la session uso el id_alumno de la session
		if((is_numeric($id_alumno))and($id_alumno>0)){$this->idAlumnoObjeto=$id_alumno;}
		elseif(isset($_SESSION["SELECTOR_ALUMNO"]["id"])){ $this->idAlumnoObjeto=$_SESSION["SELECTOR_ALUMNO"]["id"];}
		else{ $this->ERROR=1;}
		
		if($this->idAlumnoObjeto>0){$this->MATRICULAS_ALUMNO();}
		
	}
	
	private function DATOS_PERSONALES(){
		if($this->DEBUG){ echo"-----------------------------INICIO DATOS_PERSONALES-----------------------------<br>";}
		$ruta_conexion=$this->ruta_conexion."conexion_v2.php";
		require($ruta_conexion);
		if($this->idAlumnoObjeto>0){
			$cons_A="SELECT * FROM alumno WHERE id='$this->idAlumnoObjeto' LIMIT 1";
			$sqli_A=$conexion_mysqli->query($cons_A);
			$encontrado=$sqli_A->num_rows;
			if($this->DEBUG){ echo"$cons_A<br>num reg: $encontrado<br>";}
			if($encontrado>0){
				$DA=$sqli_A->fetch_assoc();
					$this->rutAlumnoObjeto=$DA["rut"];
					$this->nombreAlumnoObjeto=$DA["nombre"];
					$this->apellido_MAlumnoObjeto=$DA["apellido_M"];
					$this->apellido_PAlumnoObjeto=$DA["apellido_P"];
					$this->sexoAlumnoObjeto=$DA["sexo"];
					$this->fechaNacimiento=$DA["fnac"];
					$this->ciudad=$DA["ciudad"];
					$this->direccion=$DA["direccion"];
					$this->fono=$DA["fono"];
					$this->fonoApoderado=$DA["fonoa"];
					$this->email=$DA["email"];
					$this->emailInstitucional=$DA["emailInstitucional"];
					$this->estadoCivil=$DA["estado_civil"];
					$this->nacionalidad=$DA["pais_origen"];
					$this->paisOrigen=$DA["pais_origen"];
					$this->paisEstudiosMedios=$DA["liceo_pais"];
					$this->liceo_formacion=$DA["liceo_formacion"];
					$this->liceo_dependencia=$DA["liceo_dependencia"];
					$this->liceo=$DA["liceo"];
					$this->yearEgresoLiceo=$DA["liceo_egreso"];
					$this->otro_estudio_U=$DA["otro_estudio_U"];
					$this->otro_estudio_T=$DA["otro_estudio_T"];
					$this->otro_estudio_P=$DA["otro_estudio_P"];
					
			}
			else{$this->ERROR=2;}
			$sqli_A->free();
		}
		else{if($this->DEBUG){ echo"id_alumno invalido<br>";}}
		$conexion_mysqli->close();
		if($this->DEBUG){ echo"-----------------------------FIN DATOS_PERSONALES-----------------------------<br>";}
	}
	
	private function MATRICULAS_ALUMNO(){
	   if($this->DEBUG){ echo"-----------------------------INICIO MATRICULAS_ALUMNO-----------------------------<br>";}
	   $ruta_conexion=$this->ruta_conexion."conexion_v2.php";	
	   require($ruta_conexion);
	   if($this->idAlumnoObjeto>0){
		   $cons_PN="SELECT id_carrera, yearIngresoCarrera FROM `contratos2` WHERE id_alumno='$this->idAlumnoObjeto' group by id_carrera, yearIngresoCarrera ORDER by yearIngresoCarrera";
		   $sqli_PN=$conexion_mysqli->query($cons_PN) or die($conexion_mysqli->error);
		   $this->numeroMatriculasAlumno=$sqli_PN->num_rows;
		   if($this->DEBUG){ echo"$cons_PN<br>";}
		   $i=0;
		   while($DPN=$sqli_PN->fetch_assoc()){
			   $this->arrayMatriculasAlumnoObjeto[$i]["id_carrera"]=$DPN["id_carrera"];
			   $this->arrayMatriculasAlumnoObjeto[$i]["yearIngresoCarrera"]=$DPN["yearIngresoCarrera"];
			   $i++;
		   }
		   $sqli_PN->free();
		   //consulto estado de cada matricula
		   $this->ESTADO_ALUMNO_MATRICULA();
	   }else{$this->ERROR=3;}
	   
	   $conexion_mysqli->close();
	    if($this->DEBUG){ echo"-----------------------------FIN MATRICULAS_ALUMNO-----------------------------<br>";}
	}
	
	private function SEDE_ACTUAL(){
		if($this->DEBUG){ echo"-----------------------------INICIO SEDE_ACTUAL-----------------------------<br>";}
	   $ruta_conexion=$this->ruta_conexion."conexion_v2.php";	
	   require($ruta_conexion);
	   if($this->idAlumnoObjeto>0){
			 $cons_S="SELECT sede FROM `contratos2` WHERE id_alumno='$this->idAlumnoObjeto' ORDER by id DESC LIMIT 1";
			  if($this->DEBUG){ echo"$cons_S<br>";}
			 $sqli_S=$conexion_mysqli->query($cons_S) or die($conexion_mysqli->error);
			 $DSC=$sqli_S->fetch_assoc();
				$this->sedeActualAlumnoObjeto=$DSC["sede"];
			 $sqli_S->free();
	   }else{$this->ERROR=4;}
	   $conexion_mysqli->close();
	   if($this->DEBUG){ echo"-----------------------------FIN SEDE_ACTUAL-----------------------------<br>";}
	}
	
	private function JORNADA_ACTUAL(){
		if($this->DEBUG){ echo"-----------------------------INICIO JORNADA_ACTUAL-----------------------------<br>";}
	   $ruta_conexion=$this->ruta_conexion."conexion_v2.php";	
	   require($ruta_conexion);
	   if($this->idAlumnoObjeto>0){
			 $cons_J="SELECT jornada FROM `contratos2` WHERE id_alumno='$this->idAlumnoObjeto' ORDER by id DESC LIMIT 1";
			 if($this->DEBUG){ echo"$cons_J<br>";}
			 $sqli_J=$conexion_mysqli->query($cons_J) or die($conexion_mysqli->error);
			 $DJC=$sqli_J->fetch_assoc();
				$this->jornadaActualAlumnoObjeto=$DJC["jornada"];
			 $sqli_J->free();
	   }else{$this->ERROR=5;}
	   $conexion_mysqli->close();
	   if($this->DEBUG){ echo"-----------------------------FIN JORNADA_ACTUAL-----------------------------<br>";}
	}
	
	//muestra el nivel academico de la ultima matricula
	//o segun se le indique con id_carrera y yearIngresoCarrera
	private function NIVEL_ACADEMICO_ACTUAL($id_carrera="", $yearIngresoCarrera=""){
		if($this->DEBUG){ echo"<br><strong>----------------------------INICIO NIVEL_ACADEMICO_ACTUAL-------------------------------------</strong></br>";}
		$ruta_conexion=$this->ruta_conexion."conexion_v2.php";	
	     require($ruta_conexion);
		 $hayRegistroAcademico=false;
		 if(empty($id_carrera)){$id_carrera=$this->getUltimaIdCarreraMat();}
		 
		 if(empty($yearIngresoCarrera)){$yearIngresoCarrera=$this->getUltimoYearIngresoMat();}
		 
		 if(($this->idAlumnoObjeto>0)and($this->numeroMatriculasAlumno>0)){
			 
			 $consRA="SELECT COUNT(id) FROM notas WHERE  id_alumno='$this->idAlumnoObjeto' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera'";
			 $sqliRA=$conexion_mysqli->query($consRA);
			 $DRA=$sqliRA->fetch_row();
			 	$numReg=$DRA[0];
				if(empty($numReg)){$numReg=0;}
			$sqliRA->free();	
			 
			 if($numReg>0){$hayRegistroAcademico=true;}
			 
			 if($hayRegistroAcademico){
				 
				$cons_N="SELECT MIN(nivel) FROM notas WHERE id_alumno='$this->idAlumnoObjeto' AND id_carrera='".$this->getUltimaIdCarreraMat()."' AND yearIngresoCarrera='".$this->getUltimoYearIngresoMat()."' AND ramo<>'' AND NOT nota > '4' AND es_asignatura='1'";	
				$sqli_N=$conexion_mysqli->query($cons_N)or die($conexion_mysqli->error);
				$N=$sqli_N->fetch_row();
				$nivel_minino_N=$N[0];
				if($this->DEBUG){ echo"->$cons_N<br>nivel minimo: $nivel_minino_N<br>";}
				if(empty($nivel_minino_N)){ $nivel_minino_N=5; if($this->DEBUG){echo"*aprobo todas las asignaturas<br>";}}
				$sqli_N->free();
				
			 }else{$nivel_minino_N=1; if($this->DEBUG){ echo"Sin registro academico creado...<br>";}}
			
			if($this->DEBUG){ echo"nivel academico: $nivel_minino_N<br>";}
			$this->nivelAcademicoAlumnoObjeto=$nivel_minino_N;
			
		 }else{$this->ERROR=6;}
			$conexion_mysqli->close();
		if($this->DEBUG){ echo"<br><strong>----------------------------FIN NIVEL_ACADEMICO_ACTUAL-------------------------------------</strong></br>";}
	}
	
	private function NIVEL_ACADEMICO_MAX(){
		if($this->DEBUG){ echo"<br><strong>----------------------------INICIO NIVEL_ACADEMICO_MAX-------------------------------------</strong></br>";}
		$ruta_conexion=$this->ruta_conexion."conexion_v2.php";	
	     require($ruta_conexion);
		 if(($this->idAlumnoObjeto>0)and($this->numeroMatriculasAlumno>0)){
			$cons_N="SELECT nivel_alumno, nivel_alumno_2 FROM contratos2 WHERE id_alumno='$this->idAlumnoObjeto' AND id_carrera='".$this->getUltimaIdCarreraMat()."' AND yearIngresoCarrera='".$this->getUltimoYearIngresoMat()."'";	
			$sqli_N=$conexion_mysqli->query($cons_N)or die($conexion_mysqli->error);
			$auxMaximo=0;
			while($N=$sqli_N->fetch_assoc()){
				$nivel_alumno1=$N['nivel_alumno'];
				$nivel_alumno2=$N['nivel_alumno_2'];
				
				if($nivel_alumno1>$auxMaximo){$auxMaximo=$nivel_alumno1;}
				if($nivel_alumno2>$auxMaximo){$auxMaximo=$nivel_alumno2;}
			}
			if($this->DEBUG){ echo"->$cons_N<br>nivel maximo: $auxMaximo<br>";}
			$sqli_N->free();
			$this->nivelAcademicoAlumnoMaximo=$auxMaximo;
		 }else{$this->ERROR=6;}
			$conexion_mysqli->close();
		if($this->DEBUG){ echo"<br><strong>----------------------------FIN NIVEL_ACADEMICO_MAXIMO-------------------------------------</strong></br>";}
	}
	
	private function NUMERO_SEMESTRES(){
		if($this->DEBUG){ echo"<br><strong>----------------------------INICIO NUMERO_SEMESTRES-------------------------------------</strong></br>";}
		$ruta_conexion=$this->ruta_conexion."conexion_v2.php";	
	     require($ruta_conexion);
		 if(($this->idAlumnoObjeto>0)and($this->numeroMatriculasAlumno>0)){
			$cons_N="SELECT vigencia FROM contratos2 WHERE id_alumno='$this->idAlumnoObjeto' AND id_carrera='".$this->getUltimaIdCarreraMat()."' AND yearIngresoCarrera='".$this->getUltimoYearIngresoMat()."' AND condicion IN('ok', 'old')";	
			$sqli_N=$conexion_mysqli->query($cons_N)or die($conexion_mysqli->error);
			$auxSemestres=0;
			while($N=$sqli_N->fetch_assoc()){
				$auxVigencia=$N['vigencia'];
				if($auxVigencia=="semestral"){$auxSemestres++;}
				if($auxVigencia=="anual"){$auxSemestres+=2;}
				if($this->DEBUG){ echo"-> $auxVigencia $auxSemestres<br>";}
			}
			if($this->DEBUG){ echo"->$cons_N<br>Numero Semestre contrato: $auxSemestres<br>";}
			$sqli_N->free();
			$this->numeroSemestresAlumno=$auxSemestres;
		 }else{$this->ERROR=6;}
			$conexion_mysqli->close();
		if($this->DEBUG){ echo"<br><strong>----------------------------FIN NUMERO_SEMESTRES-------------------------------------</strong></br>";}
	}
	
	private function ES_TITULADO($id_carrera, $yearIngresoCarrera)
	{
		if($this->DEBUG){ echo"<br><strong>----------------------------ES_TITULADO-------------------------------------</strong></br>";}
		$ruta_conexion=$this->ruta_conexion."conexion_v2.php";	
	    require($ruta_conexion);
		
		$PT_semeste="";
		$PT_year="";
		$es_titulado=false;
		
		$cons="SELECT * FROM proceso_titulacion WHERE id_alumno='$this->idAlumnoObjeto' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera 'LIMIT 1";
		$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		$num_reg=$sqli->num_rows;
		$es_titulado=false;
		$semestre_titulo="";
		$year_titulo="";
		if($this->DEBUG){ echo"--->$cons<br>NUM REGISTROS:$num_reg<br>";}
		if($num_reg>0)
		{
			$es_titulado=true;
			$PT=$sqli->fetch_assoc();
			$PT_titulo_fecha_emision=$PT["titulo_fecha_emision"];
			$PT_fecha_generacion=$PT["fecha_generacion"];
			$PT_semeste=$PT["semestre_titulo"];
			$PT_year=$PT["year_titulo"];
			
			$semestre_titulo=$PT_semeste;
			$year_titulo=$PT_year;
	
		}
		else
		{
			$es_titulado=false;
			if($this->DEBUG){ echo"No hay Proceso de Titulacion, No es titulado<br>";}
			
		}
		
		if($this->DEBUG)
		{
			if($es_titulado){ echo"ALUMNO es TITULADO perido [$PT_semeste - $PT_year]<br>";}
			else{ echo"Alumno NO es titulado<br>";}
		}
		$sqli->free();
		$conexion_mysqli->close();
		if($this->DEBUG){ echo"___________________________<strong>Fin ES_TITULADO</strong>____________________________<br>";}
		
		$array_respuesta=array($es_titulado, $semestre_titulo, $year_titulo);
		return($array_respuesta);
	}
	
	private function ES_EGRESADO_V3($id_carrera, $yearIngresoCarrera)
	{
		if($this->DEBUG){ echo"<br><strong>----------------------------ES_TITULADO-------------------------------------</strong></br>";}
		$ruta_conexion=$this->ruta_conexion."conexion_v2.php";	
	    require($ruta_conexion);
		if(($this->idAlumnoObjeto>0)and($this->numeroMatriculasAlumno>0)){
			$semestre_egreso="";
			$year_egreso="";
			$alumno_es_egresado=false;
			
			if($this->DEBUG){ echo"___________________________<strong>INICIO ES_EGRESADO_V3</strong>____________________________<br>";}
			require("conexion_v2.php");
			if($this->DEBUG){ echo"datos entrada-->id_carrera: $id_carrera yearIngresoCarrera: $yearIngresoCarrera<br>";}
			$cons_S="SELECT semestre_egreso, year_egreso FROM proceso_egreso WHERE id_alumno='$this->idAlumnoObjeto' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera' ORDER by id_proceso_egreso";
			$sqli_S=$conexion_mysqli->query($cons_S)or die($conexion_mysqli->error);
			$num_registros=$sqli_S->num_rows;
			if($this->DEBUG){ echo"--->$cons_S<br>num registros egreso: $num_registros<br>";}
			if($num_registros>0)
			{
				$alumno_es_egresado=true;
				if($this->DEBUG){ echo"El alumno es egresado<br>";}
				while($EG=$sqli_S->fetch_assoc())
				{
					$semestre_egreso=$EG["semestre_egreso"];
					$year_egreso=$EG["year_egreso"];
				}
				
				if($this->DEBUG){ echo"Periodo egreso [$semestre_egreso - $year_egreso]<br>";}
			}
			else
			{if($this->DEBUG){ echo"El alumno NO es egresado<br>";}}
			
			$sqli_S->free();
			$conexion_mysqli->close();
			
			$array_respuesta=array($alumno_es_egresado, $semestre_egreso, $year_egreso);
			if($this->DEBUG){ echo"___________________________<strong>FIN ES_EGRESADO_V3</strong>____________________________<br>";}
			return($array_respuesta);
		}
	}
	
	private function ES_RETIRADO($id_carrera, $yearIngresoCarrera)
	{
		if($this->DEBUG){ echo"<br><strong>----------------------------ES_RETIRADO-------------------------------------</strong></br>";}
		$ruta_conexion=$this->ruta_conexion."conexion_v2.php";	
	    require($ruta_conexion);
		if(($this->idAlumnoObjeto>0)and($this->numeroMatriculasAlumno>0)){
		require("conexion_v2.php");
		
		$cons_PR="SELECT * FROM proceso_retiro WHERE id_alumno='$this->idAlumnoObjeto' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera' ORDER by id_retiro DESC LIMIT 1";
		$sqli_PR=$conexion_mysqli->query($cons_PR)or die("ERROR ".$conexion_mysqli->error);
		$num_pr=$sqli_PR->num_rows;
		$es_retirado=false;
		$semestre_retiro="";
		$year_retiro="";
		if($this->DEBUG){ echo"--> $cons_PR<br>Num Registros: $num_pr<br>";}
		if($num_pr>0)
		{
			$es_retirado=true;
			$PR=$sqli_PR->fetch_assoc();
				$hay_proceso_retiro=true;
				$motivo=$PR["motivo"];
				$semestre_retiro=$PR["semestre_retiro"];
				$year_retiro=$PR["year_retiro"];
			if($this->DEBUG){echo"Alumno tiene Proceso de Retiro: Si - Periodo retiro [$semestre_retiro - $year_retiro]<br>";}	
		}
		else
		{
			if($this->DEBUG){ echo"Alumno sin proceso de Retiro, no Retirado<br>";}
			$es_retirado=false;
		}
		$sqli_PR->free();
		
		
		if($this->DEBUG){ echo"___________________________<strong>FIN ES_RETIRADO</strong>____________________________<br>";}
		
		$array_respuesta=array($es_retirado, $semestre_retiro, $year_retiro);
		$conexion_mysqli->close();
		return($array_respuesta);
		}
	}
	
	public function VERIFICAR_MATRICULA($id_carrera, $yearIngresoCarrera, $considerar_vigencia=true, $semestre_automatico=true, $semestre_consulta="", $year_automatico=true, $year_consulta="", $alargar_vigencia_para_5_nivel=false)
	{
		if($this->DEBUG){ echo"<br><strong>---------------------------- VERIFICAR_MATRICULA-------------------------------------</strong></br>";}
		$ruta_conexion=$this->ruta_conexion."conexion_v2.php";	
		require($ruta_conexion);
		if(($this->idAlumnoObjeto>0)and($this->numeroMatriculasAlumno>0)){
			$matricula_vigente=false;
			$year_actual=date("Y");
			$mes_actual=date("m");
			////////////////////////////////////////////////////
			if($alargar_vigencia_para_5_nivel)
			{ if($this->DEBUG){ echo"Se Considera vigente alumnos de 5 nivel que tenga contrato en el year consultado, no utiliza el semestre<br>";}}
			//considero agosto como inicio 2 semestre
			if($semestre_automatico)
			{
				if($mes_actual>=8){ $semestre_actual=2;}
				else{ $semestre_actual=1;}
				if($this->DEBUG){ echo"Semestre actual calculado Automaticamente: $semestre_actual<br>";}
			}
			else
			{ $semestre_actual=$semestre_consulta; if($this->DEBUG){ echo"Semestre actual Manual: $semestre_actual<br>";}}
			
			if($year_automatico)
			{if($this->DEBUG){ echo"Year actual calculado Automaticamente: $year_actual<br>";} }
			else
			{ 
				if($this->DEBUG){ echo"Year actual Manual: $year_consulta<br>";}
				$year_actual=$year_consulta;
			}
			
			/////////////////////////////////////////////////////////
			
				$cons_xxx1="SELECT * FROM contratos2 WHERE id_alumno='$this->idAlumnoObjeto' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera' AND ano='$year_actual' ORDER by id";
				$sql_xxx1=$conexion_mysqli->query($cons_xxx1)or die($conexion_mysqli->error);
				$num_contratos=$sql_xxx1->num_rows;
				if($this->DEBUG){ echo"$cons_xxx1<br>num_contratos: $num_contratos<br>";}
				if($num_contratos>0)
				{
					while($DC=$sql_xxx1->fetch_assoc())
					{
							$C_condicion=strtolower($DC["condicion"]);
							$C_year=$DC["ano"];
							$C_semestre=$DC["semestre"];
							$C_vigencia=$DC["vigencia"];
							$C_nivel_alumno=$DC["nivel_alumno"];
							
							if($this->DEBUG){ echo"----->condicion: $C_condicion year: $C_year Semestre: $C_semestre Vigencia: $C_vigencia<br>";}
							
						if($considerar_vigencia)
						{
							if($this->DEBUG){ echo"<strong>Considerar Vigencia de Contrato</strong><br>";}
							if(($C_condicion=="ok")or($C_condicion=="old"))
							{
								switch($C_vigencia)
								{
									case"semestral":
										///alumno de 5 nivel se le considera simepre como contrato anual
										if(($C_nivel_alumno==5)and($alargar_vigencia_para_5_nivel))
										{
											if($this->DEBUG){ echo"Nivel Alumno=5, solo considerar Vigencia del año del contratos...<br>";}
											if($C_year==$year_actual)
											{ $matricula_vigente=true;}
											else
											{ $matricula_vigente=false;}
										}
										else
										{
											if(($C_semestre==$semestre_actual)and($C_year==$year_actual))
											{ $matricula_vigente=true;}
											else
											{ $matricula_vigente=false;}
										}
										break;
									case"anual":
										if($C_year==$year_actual)
										{ $matricula_vigente=true;}
										else
										{ $matricula_vigente=false;}
										break;	
								}
							}
							else
							{ $matricula_vigente=false; }
							
						}
						else
						{
							if($this->DEBUG){ echo"<strong>No considero Vigencia</strong><br>";}
							if(($C_condicion=="ok")or($C_condicion=="old"))
							{ $matricula_vigente=true;}
						}
						
						if($matricula_vigente){ if($this->DEBUG){ echo"Alumno con Matricula en este periodo OK<br>";} break;}
						else{ if($this->DEBUG){ echo"Alumno Sin Matricula en este periodo Error<br>";}}
					}
				}
				else
				{
					if($this->DEBUG){ echo"Sin Contratos encontrados<br>";}
				}
				$sql_xxx1->free();
				
				if($this->DEBUG){ if($matricula_vigente){ echo"Alumno Vigente...<br>";}else{ echo"Alumno NO Vigente...<br>";}}
				if($this->DEBUG){ echo"<br>_______________<strong>FIN VERIFICAR_MATRICULA</strong>____________________<br>";}
				$conexion_mysqli->close();
			return($matricula_vigente);
		}
	}
	//----------------------------------//


	private function CALCULA_PERIODO($semestre_consulta, $year_consulta, $semestres, $operacion="+", $semestre_buscado="", $year_buscado="")
	{
		if($this->DEBUG){ echo"<strong>----------------------------INICIO CALCULA_PERIODO---------------------------------</strong><br>";}
		if($this->DEBUG){ echo"Periodo Inicial [$semestre_consulta - $year_consulta]<br> Operacion: $operacion -> ($semestres) Semestres<br>periodo buscado [$semestre_buscado - $year_buscado]<br>";}
		
		$semestre_X=$semestre_consulta;
		$year_X=$year_consulta;
		
		if(($semestre_buscado>0)and($year_buscado>0)){$buscar=true;}else{$buscar=false;}
		$encontrado=false;
		for($i=0;$i<$semestres;$i++)
		{
			if($buscar)
			{
				if(($semestre_buscado==$semestre_X) and($year_buscado==$year_X))
				{$encontrado=true; if($this->DEBUG){ echo"periodo encontrado<br>";}}
			}
			
			if($operacion=="+")
			{
				$semestre_X+=1;
				if($semestre_X>2){ $semestre_X=1; $year_X+=1;}
			}
			else
			{
				$semestre_X-=1;
				if($semestre_X<1){$semestre_X=2; $year_X-=1;}
			}
			
			
		}
		if($buscar)
			{
				if(($semestre_buscado==$semestre_X) and($year_buscado==$year_X))
				{$encontrado=true; if($this->DEBUG){ echo"preiodo encontrado<br>";}}
			}
		
		if($this->DEBUG){ 
				echo"Periodo Final [$semestre_X - $year_X]<br>"; 
				if($buscar){ if($encontrado){ echo"Encontrado<br>";}else{ echo"NO encontrado<br>";} }
		}
	
		if($this->DEBUG){ echo"<strong>----------------------------FIN CALCULA_PERIODO---------------------------------</strong><br>";}
		
		if($buscar){$array_respuesta=array($semestre_X, $year_X, $encontrado);}
		else{$array_respuesta=array($semestre_X, $year_X);}
		return($array_respuesta);
	}
	
	//selecciona la matricula correspondiente al periodo consultado
	private function MATRICULA_SEGUN_PERIODO($semestreConsulta, $yearConsulta){
		$auxIdcarrera="";
		$auxYearIngresoCarrera="";
		if($this->DEBUG){ echo"<br><strong>----------------------------INICIO MATRICULA SEGUN PERIODO-------------------------------------</strong></br>";}
		if(($this->idAlumnoObjeto>0)and($this->numeroMatriculasAlumno>0)){
			if($this->DEBUG){ echo"Revisando matriculas<br>";}
			foreach($this->getMatriculasAlumno() as $indice =>$array){
				if($this->DEBUG){ echo"->[id carrera: ".$array["id_carrera"]." yearIngresoCarrera:".$array["yearIngresoCarrera"]."]: ";}	
				if($yearConsulta >= $array["yearIngresoCarrera"]){
					 $auxIdcarrera=$array["id_carrera"];
					 $auxYearIngresoCarrera=$array["yearIngresoCarrera"];
					 if($this->DEBUG){ echo"OK<br>";}
				}
				else{ if($this->DEBUG){ echo"NO<br>";}}
			}//fin foreach
			
		if($this->DEBUG){ echo"<br>Matricula Seleccionada [$auxIdcarrera - $auxYearIngresoCarrera]<br><strong>----------------------------FIN MATRICULA SEGUN PERIODO-------------------------------------</strong></br>";}
		return(array($auxIdcarrera, $auxYearIngresoCarrera));
		}
	}
	
	
	//estado actual de cada matricula
	private function ESTADO_ALUMNO_MATRICULA(){
		if($this->DEBUG){ echo"<br><strong>----------------------------INICIO ESTADO_ALUMNO_MATRICULA-------------------------------------</strong></br>";}
		$ruta_conexion=$this->ruta_conexion."conexion_v2.php";	
	     require($ruta_conexion);
		 
		 $yearConsulta=date("Y");
		 $mes_actual=date("m");
		 if($mes_actual>=8){$semestreConsulta=2;}
		 else{ $semestreConsulta=1;}
		 
		 
		 if(($this->idAlumnoObjeto>0)and($this->numeroMatriculasAlumno>0)){
			 foreach($this->arrayMatriculasAlumnoObjeto as $indice => $auxArray)
			 {
				 $auxIdCarrera=$auxArray["id_carrera"];
				 $auxYearIngresoCarrera=$auxArray["yearIngresoCarrera"];
				 
				$this->arrayMatriculasAlumnoObjeto[$indice]["situacion"]=$this->ESTADO_ALUMNO_PERIODO($semestreConsulta, $yearConsulta, $auxIdCarrera, $auxYearIngresoCarrera);    
			  }
		 }
		 $conexion_mysqli->close();
	}
	
	private function ESTADO_ALUMNO_PERIODO($semestreConsulta, $yearConsulta, $idCarrera=0, $yearIngresoCarrera=0){
		if($this->DEBUG){ echo"<br><strong>----------------------------INICIO ESTADO_ALUMNO_PERIODO-------------------------------------</strong></br>";}
		$ruta_conexion=$this->ruta_conexion."conexion_v2.php";	
	     require($ruta_conexion);
		 
		 //selecciono matricula segun periodo, si no lo envia
		 if(($this->idAlumnoObjeto>0)and($this->numeroMatriculasAlumno>0)){
			if($idCarrera==0 or $yearIngresoCarrera==0){
		 		list($idCarrera, $yearIngresoCarrera)=$this->MATRICULA_SEGUN_PERIODO($semestreConsulta, $yearConsulta);
			}
			
			if($this->DEBUG){echo"Revisando Matricula-----> id_carrera: $idCarrera yearIngresoCarrera: $yearIngresoCarrera<br>";}
			$condicion_alumno_este_year="";
			$es_titulado=false;
			$es_egresado=false;
			$es_matriculado=false;
			$es_retirado=false;
			$es_pendiente=false;
			$es_postergado=false;
			
			$ultimo_year_informacion=0;
			$ultimo_semestre_informacion=0;

			//titulado
			list($es_titulado, $semestre_titulo, $year_titulo)=$this->ES_TITULADO($idCarrera, $yearIngresoCarrera);
		
			if((($es_titulado)and($year_titulo<$yearConsulta))or(($es_titulado)and($year_titulo==$yearConsulta)and($semestre_titulo<=$semestreConsulta)))
			{ $es_titulado=true; $condicion_alumno_este_year="T"; $ultimo_year_informacion=$year_titulo; $ultimo_semestre_informacion=$semestre_titulo;}
			else{ $es_titulado=false;}
					
			if(!$es_titulado)
			{
				//egresado
				list($es_egresado, $semestre_egreso, $year_egreso)=$this->ES_EGRESADO_V3($idCarrera, $yearIngresoCarrera);
				if((($es_egresado)and($year_egreso<$yearConsulta))or(($es_egresado)and($year_egreso==$yearConsulta)and($semestre_egreso<=$semestreConsulta)))
				{ $es_egresado=true; $condicion_alumno_este_year="EG"; $ultimo_year_informacion=$year_egreso; $ultimo_semestre_informacion=$semestre_egreso;}
				else{ $es_egresado=false; }
			}
		
			if(!$es_egresado)
			{
				//retirado
				list($es_retirado, $semestre_retiro,$year_retiro)=$this->ES_RETIRADO($idCarrera, $yearIngresoCarrera);
				if((($es_retirado)and($ultimo_year_informacion<$year_retiro)and($ultimo_semestre_informacion<$semestre_retiro)and($year_retiro<$yearConsulta))
				or(($es_retirado)and($ultimo_year_informacion<$year_retiro)and($ultimo_semestre_informacion<$semestre_retiro)and($year_retiro==$yearConsulta))and($semestre_retiro<=$semestreConsulta))
				{$es_retirado=true; $condicion_alumno_este_year="R"; $ultimo_year_informacion=$year_retiro; $ultimo_semestre_informacion=$semestre_retiro;}
				else{ $es_retirado=false;}
				
			}
		
			if((!$es_retirado)and(!$es_egresado)and(!$es_titulado))
			{
				if($this->DEBUG){ echo"-----------------INICIO Revision de POSTERGADO---------------------<br>";}
				//POSTERGADO //revisar, pueden haber multiples procesos de postergacion
				$consP="SELECT semestre_postergacion, year_postergacion, semestres_suspencion FROM proceso_postergacion WHERE id_alumno='$this->idAlumnoObjeto' AND id_carrera='$idCarrera' AND yearIngresoCarrera='$yearIngresoCarrera'";
				
				$sqliP=$conexion_mysqli->query($consP)or die($conexion_mysqli->error);
				$num_postergaciones=$sqliP->num_rows;
				if($this->DEBUG){ echo"--->$consP<br>Num postergaciones encontradas: $num_postergaciones<br>";}
				if($num_postergaciones>0)
				{
					while($P=$sqliP->fetch_assoc())
					{
						$P_semestre=$P["semestre_postergacion"];
						$P_year=$P["year_postergacion"];
						$P_semestres_suspencion=$P["semestres_suspencion"];
						list($P_semestre_F, $P_year_F, $en_rango)=$this->CALCULA_PERIODO($P_semestre, $P_year, $P_semestres_suspencion,"+",$semestreConsulta, $yearConsulta);
						
						if($en_rango){ $es_postergado=true; $year_postergado=$yearConsulta; $semestre_postergado=$semestreConsulta;}
						
						if(($es_postergado)and($ultimo_year_informacion<$year_postergado)and($ultimo_semestre_informacion<$semestre_postergado))
						{ $condicion_alumno_este_year="POSTERGADO"; $ultimo_year_informacion=$year_postergado; $ultimo_semestre_informacion=$semestre_postergado;}
		
					}
				}
				$sqliP->free();
				if($es_postergado){ if($this->DEBUG){ echo" Alumno postergado en periodo[$semestre - $year]<br>";}}
				else{if($this->DEBUG){ echo" Alumno NO postergado<br>";}}
				if($this->DEBUG){ echo"-----------------FIN Revision de POSTERGADO---------------------<br>";}
			}
					
					
			if((!$es_egresado)and(!$es_titulado)and(!$es_retirado))
			{
				//vigente
				$es_matriculado=$this->VERIFICAR_MATRICULA($idCarrera, $yearIngresoCarrera, true, false,$semestreConsulta, false,$yearConsulta, true);
				if($es_matriculado)
				{
					$condicion_alumno_este_year="V";
					$ultimo_year_informacion=$yearConsulta;
					$ultimo_semestre_informacion=$semestreConsulta;
					//en caso de egresado que se matricularon para titularse
				}
			}
				
				//no es nada NN
			if((!$es_matriculado)and(!$es_egresado)and(!$es_titulado)and(!$es_retirado)and(!$es_pendiente)and(!$es_postergado))
			{ $condicion_alumno_este_year="NN";}
		
			if($this->DEBUG){echo"Condicion de alumno este year: $condicion_alumno_este_year<br>";}
			if($this->DEBUG){ echo"</tt><br><strong>----------------------------FIN ESTADO_ALUMNO_PERIODO-------------------------------------</strong></br>";}
			
			return($condicion_alumno_este_year);
		 }
		 $conexion_mysqli->close();
	}
	
	//dado un periodo semestre-year, consulta si el alumno estuvo presente y con que matricula, ademas de datos de esta(jornada, sede, nivel)
	public function IR_A_PERIODO($semestreConsulta, $yearConsulta){
		//vaciar variables
		$this->nivelAcademicoAlumnoPeriodo="";
		$this->sedeAlumnoPeriodo="";
		$this->jornadaAlumnoPeriodo="";
		$this->situacionAlumnoPeriodo="";
		$this->idCarreraAlumnoPeriodo="";
		$this->yearIngresoCarreraPeriodo="";
		$this->idContratoPeriodo="";
		$this->presenteEnPeriodo=false;
		
		if($this->DEBUG){ echo"<br><strong>----------------------------INICIO IR_A_PERIODO-------------------------------------</strong></br>";}
		$ruta_conexion=$this->ruta_conexion."conexion_v2.php";	
	    require($ruta_conexion);
		$existen_contratos=false;
		$hay_contrato=false;
		
		if(($this->idAlumnoObjeto>0)and($this->numeroMatriculasAlumno>0)){
			list($auxIdcarrera, $aux_yearIngresoCarrera)=$this->MATRICULA_SEGUN_PERIODO($semestreConsulta, $yearConsulta);
			if($this->DEBUG){ echo"semestreConsulta: $semestreConsulta year consulta: $yearConsulta matricula Seleccionada[ $auxIdcarrera - $aux_yearIngresoCarrera]<br>";}
			$this->idCarreraAlumnoPeriodo=$auxIdcarrera;
			$this->yearIngresoCarreraPeriodo=$aux_yearIngresoCarrera;
			
			$this->situacionAlumnoPeriodo=$this->ESTADO_ALUMNO_PERIODO($semestreConsulta, $yearConsulta, $auxIdcarrera, $aux_yearIngresoCarrera);
			
			$cons_xxx1="SELECT * FROM contratos2 WHERE id_alumno='$this->idAlumnoObjeto' AND id_carrera='$auxIdcarrera' AND yearIngresoCarrera='$aux_yearIngresoCarrera' AND ano='$yearConsulta' ORDER by id DESC";
				
			$sql_xxx1=$conexion_mysqli->query($cons_xxx1)or die($conexion_mysqli->error);
			$num_contratos=$sql_xxx1->num_rows;
			if($this->DEBUG){ echo"$cons_xxx1<br>num_contratos: $num_contratos<br>";}
			
			if($num_contratos>0)
			{
				$existen_contratos=true;
				while($DC=$sql_xxx1->fetch_assoc())
				{
						$C_id_contrato=$DC["id"];
						$C_condicion=strtolower($DC["condicion"]);
						$C_year=$DC["ano"];
						$C_semestre=$DC["semestre"];
						$C_vigencia=$DC["vigencia"];
						$C_nivel_alumno=$DC["nivel_alumno"];
						$C_nivel_alumno_2=$DC["nivel_alumno_2"];
						$C_jornada_alumno=$DC["jornada"];
						$C_fecha_generacion=$DC["fecha_generacion"];
						$C_sede_alumno=$DC["sede"];
						
						if($this->DEBUG){ echo"<strong>----->id_contrato: $C_id_contrato condicion: $C_condicion year: $C_year Semestre: $C_semestre Vigencia: $C_vigencia</strong><br>";}
						if($this->DEBUG){ echo"--->Revisando Nivel del alumno al realizar contrato<br>";}
						switch($C_vigencia)
						{
							case"semestral":
								$nivel_alumno_en_contrato=$C_nivel_alumno;
								if($this->DEBUG){ echo"|---->Como contrato semestral, utilizar nivel del contrato [$C_nivel_alumno]<br>";}
								break;
							case"anual":
								if($this->DEBUG){ echo"|---->Como contrato anual, revisar nivel segun semestre consulta<br>";}
								if($semestreConsulta==1)
								{$nivel_alumno_en_contrato=$C_nivel_alumno;if($this->DEBUG){ echo"|---->Como semestre a consultar es [1], utilizar nivel del contrato [$C_nivel_alumno]<br>";}}
								elseif($semestreConsulta==2)
								{
	
									$nivel_alumno_en_contrato=$C_nivel_alumno_2; 
									if($this->DEBUG){ echo"|---->Como semestre a consultar es [2], utilizar nivel del contrato 2 [$C_nivel_alumno_2]<br>";}
									if($nivel_alumno_en_contrato>5){ $nivel_alumno_en_contrato=5;}
								}
								break;
						}
						
						
						if($this->DEBUG){ echo"--->Revisando Vigencia del Contrato en periodo contrato<br>";}
						
						switch($C_vigencia)
						{
							case"semestral":
								if(($C_semestre==$semestreConsulta)and($C_year==$yearConsulta))
								{ $hay_contrato=true;}
								else
								{ $hay_contrato=false;}
								if($this->DEBUG){ echo"|---->Contrato semestral del perido [$C_semestre - $C_year]<br>";}
								break;
							case"anual":
								if($C_year==$yearConsulta)
								{ $hay_contrato=true;}
								else
								{ $hay_contrato=false;}
								if($this->DEBUG){ echo"|---->Contrato Anual del año [$C_year]<br>";}
								break;	
						}
						
					if($hay_contrato){ if($this->DEBUG){ echo"--> contrato concuerda con  periodo OK<br>";} break;}
					else{ if($this->DEBUG){ echo"--> contrato NO concuerda con periodo periodo Error<br>";}}
				}
			}//fin si hay contratos
			else
			{if($this->DEBUG){ echo"--> Sin Contratos encontrados<br>";}}
			
			$sql_xxx1->free();
			}
			
			if($this->DEBUG){ echo"================================================<br>";}
			
			if(($existen_contratos and $hay_contrato)){
				 if($this->DEBUG){ echo"Guardando Datos del contrato encontrado en el periodo [$semestreConsulta - $yearConsulta]...<br>";}
				 	$this->nivelAcademicoAlumnoPeriodo=$nivel_alumno_en_contrato;
					$this->sedeAlumnoPeriodo=$C_sede_alumno;
					$this->jornadaAlumnoPeriodo=$C_jornada_alumno;	
					$this->idContratoPeriodo=$C_id_contrato;
					$this->presenteEnPeriodo=true;	
					
			}
			else{
				if($this->DEBUG){ echo"Guardando Datos del ultimo contrato encontrado en el periodo [$semestreConsulta - $yearConsulta]...<br>";}
				
					if(($this->situacionAlumnoPeriodo=="EG")or($this->situacionAlumnoPeriodo=="NN")or($this->situacionAlumnoPeriodo=="T")or($this->situacionAlumnoPeriodo=="R"))
					{
						if($this->DEBUG){ echo"<strong>Buscar datos de su ultimo contrato...</strong><br>";}
						$cons_xxx2="SELECT * FROM contratos2 WHERE id_alumno='$this->idAlumnoObjeto' AND id_carrera='$auxIdcarrera' AND yearIngresoCarrera='$aux_yearIngresoCarrera' ORDER by ano DESC, semestre DESC LIMIT 1";
				
						$sql_xxx2=$conexion_mysqli->query($cons_xxx2)or die($conexion_mysqli->error);
						$num_contratos=$sql_xxx2->num_rows;
						if($this->DEBUG){ echo"$cons_xxx2<br>num_contratos: $num_contratos<br>";}
						
						if($num_contratos>0)
						{
							$existen_contratos=true;
							while($DC=$sql_xxx2->fetch_assoc())
							{
									$C_id_contrato=$DC["id"];
									$C_condicion=strtolower($DC["condicion"]);
									$C_year=$DC["ano"];
									$C_semestre=$DC["semestre"];
									$C_vigencia=$DC["vigencia"];
									$C_nivel_alumno=$DC["nivel_alumno"];
									$C_nivel_alumno_2=$DC["nivel_alumno_2"];
									$C_jornada_alumno=$DC["jornada"];
									$C_fecha_generacion=$DC["fecha_generacion"];
									$C_sede_alumno=$DC["sede"];
									
									if($this->DEBUG){ echo"id_contrato: $C_id_contrato year: $C_year semestre: $C_semestre vigencia: $C_vigencia</br>";}
									if($C_vigencia=="anual"){ $nivelRespuesta=$C_nivel_alumno_2;}
									else{$nivelRespuesta=$C_nivel_alumno;}
							}
						}
						$this->nivelAcademicoAlumnoPeriodo=$nivelRespuesta;
						$this->sedeAlumnoPeriodo=$C_sede_alumno;
						$this->jornadaAlumnoPeriodo=$C_jornada_alumno;	
						
					}
					else{
				
						$this->nivelAcademicoAlumnoPeriodo="";
						$this->sedeAlumnoPeriodo="";
						$this->jornadaAlumnoPeriodo="";	
						$this->idContratoPeriodo="";
					}
			}
			if($this->DEBUG){ echo"idContrato Periodo:$this->idContratoPeriodo<br>  Nivel en Periodo: $this->nivelAcademicoAlumnoPeriodo<br>Sede Alumno en periodo: $this->sedeAlumnoPeriodo<br>jornada en Alumno Periodo: $this->jornadaAlumnoPeriodo<br>";}
					
					
			
		if($this->DEBUG){ echo"hay contrato: $hay_contrato<br>";}
		if($this->DEBUG){ echo"<br><strong>----------------------------FIN IR_A_PERIODO-------------------------------------</strong></br>";}
		$conexion_mysqli->close();
	}
	
	///---------------------------------------------------------------------------------------------------------//
	///---------------------------------------------------------------------------------------------------------//
	public function SetDebug($DEBUG){
		$this->DEBUG=$DEBUG;
	}
	
	public function SetRutaConexion($rutaConexion){
		$this->ruta_conexion=$rutaConexion;
	}
	
	
	public function getIdAlumno(){
		return($this->idAlumnoObjeto);
	}
	
	public function getRut(){
		if(empty($this->rutAlumnoObjeto)){$this->DATOS_PERSONALES();}
		return($this->rutAlumnoObjeto);
	}
	
	public function getNombre(){
		if(empty($this->rutAlumnoObjeto)){$this->DATOS_PERSONALES();}
		return($this->nombreAlumnoObjeto);
	}
	
	public function getApellido_P(){
		if(empty($this->rutAlumnoObjeto)){$this->DATOS_PERSONALES();}
		return($this->apellido_PAlumnoObjeto);
	}
	
	public function getApellido_M(){
		if(empty($this->rutAlumnoObjeto)){$this->DATOS_PERSONALES();}
		return($this->apellido_MAlumnoObjeto);
	}
	
	public function getSexo(){
		if(empty($this->sexoAlumnoObjeto)){$this->DATOS_PERSONALES();}
		return $this->sexoAlumnoObjeto;
	}
	
	public function getFechaNacimiento(){
		if(empty($this->sexoAlumnoObjeto)){$this->DATOS_PERSONALES();}
		return $this->fechaNacimiento;
	}
	
	public function getCiudad(){
		if(empty($this->sexoAlumnoObjeto)){$this->DATOS_PERSONALES();}
		return $this->ciudad;
	}
	
	public function getDireccion(){
		if(empty($this->sexoAlumnoObjeto)){$this->DATOS_PERSONALES();}
		return $this->direccion;
	}
	
	public function getEstadoCivil(){
		if(empty($this->sexoAlumnoObjeto)){$this->DATOS_PERSONALES();}
		return $this->estadoCivil;
	}
	
	public function getFono(){
		if(empty($this->sexoAlumnoObjeto)){$this->DATOS_PERSONALES();}
		return $this->fono;
	}
	
	public function getFonoApoderado(){
		if(empty($this->FonoApoderado)){$this->DATOS_PERSONALES();}
		return $this->fonoApoderado;
	}
	
	public function getEmail(){
		if(empty($this->sexoAlumnoObjeto)){$this->DATOS_PERSONALES();}
		return $this->email;
	}
	
	public function getEmailInstitucional(){
		if(empty($this->sexoAlumnoObjeto)){$this->DATOS_PERSONALES();}
		return $this->emailInstitucional;
	}
	
	public function getNacionalidad(){
	if(empty($this->nacionalidad)){$this->DATOS_PERSONALES();}
	return $this->nacionalidad;
	}
	
	public function getPaisOrigen(){
	if(empty($this->paisOrigen)){$this->DATOS_PERSONALES();}
	return $this->paisOrigen;
	}
	
	public function getPaisEstudiosMedios(){
	if(empty($this->paisEstudiosMedios)){$this->DATOS_PERSONALES();}
	return $this->paisEstudiosMedios;
	}
	
	public function getLiceoFormacion(){
	if(empty($this->liceo_formacion)){$this->DATOS_PERSONALES();}
	return $this->liceo_formacion;
	}
	
	public function getLiceoDependencia(){
	if(empty($this->liceo_dependencia)){$this->DATOS_PERSONALES();}
	return $this->liceo_dependencia;
	}
	
	public function getLiceo(){
	if(empty($this->liceo)){$this->DATOS_PERSONALES();}
	return $this->liceo;
	}
	
	public function getLiceoYearEgreso(){
	if(empty($this->yearEgresoLiceo)){$this->DATOS_PERSONALES();}
	return $this->yearEgresoLiceo;
	}
	
	public function getOtrosEstudiosU(){
	if(empty($this->otro_estudio_U)){$this->DATOS_PERSONALES();}
	return $this->otro_estudio_U;
	}
	
	public function getOtrosEstudiosT(){
	if(empty($this->otro_estudio_T)){$this->DATOS_PERSONALES();}
	return $this->otro_estudio_T;
	}
	
	public function getOtrosEstudiosP(){
	if(empty($this->otro_estudio_P)){$this->DATOS_PERSONALES();}
	return $this->otro_estudio_P;
	}
	
	public function getMatriculasAlumno(){
		if(empty($this->arrayMatriculasAlumnoObjeto)){$this->MATRICULAS_ALUMNO();}
		return($this->arrayMatriculasAlumnoObjeto);
	}
	
	public function getSedeActual(){
		if(empty($this->sedeActualAlumnoObjeto)){$this->SEDE_ACTUAL();}
		return($this->sedeActualAlumnoObjeto);
	}
	
	public function getJornadaActual(){
		if(empty($this->jornadaActualAlumnoObjeto)){$this->JORNADA_ACTUAL();}
		return($this->jornadaActualAlumnoObjeto);
	}
	
	public function getNivelAcademicoActual($id_carrera="", $yearIngresoCarrera=""){
		if(empty($this->nivelAcademicoAlumnoObjeto)){$this->NIVEL_ACADEMICO_ACTUAL($id_carrera, $yearIngresoCarrera);}
		return($this->nivelAcademicoAlumnoObjeto);
	}
	
	public function getNivelAcademicoMaximo(){
		if(empty($this->nivelAcademicoAlumnoMaximo)){$this->NIVEL_ACADEMICO_MAX();}
		return($this->nivelAcademicoAlumnoMaximo);
	}
	
	public function getNumeroSemestre(){
		if(empty($this->numeroSemestresAlumno)){$this->NUMERO_SEMESTRES();}
		return($this->numeroSemestresAlumno);
	}
	
	public function getUltimaIdCarreraMat(){
		if(empty($this->arrayMatriculasAlumnoObjeto)){$this->MATRICULAS_ALUMNO();}
		$auxIdCarrera=$this->arrayMatriculasAlumnoObjeto[$this->numeroMatriculasAlumno -1]["id_carrera"];
		return($auxIdCarrera);
	}
	
	public function getUltimoYearIngresoMat(){
		if(empty($this->arrayMatriculasAlumnoObjeto)){$this->MATRICULAS_ALUMNO();}
		$auxYearIngresoCarrera="";
		if(isset($this->arrayMatriculasAlumnoObjeto[$this->numeroMatriculasAlumno -1])){
			$auxYearIngresoCarrera=$this->arrayMatriculasAlumnoObjeto[$this->numeroMatriculasAlumno -1]["yearIngresoCarrera"];
		}
		return($auxYearIngresoCarrera);
	}
	
	public function getUltimaSituacionMat(){
		if(empty($this->arrayMatriculasAlumnoObjeto)){$this->MATRICULAS_ALUMNO();}
		if(isset($this->arrayMatriculasAlumnoObjeto[$this->numeroMatriculasAlumno -1]["situacion"])){
		$auxSituacion=$this->arrayMatriculasAlumnoObjeto[$this->numeroMatriculasAlumno -1]["situacion"];}
		else{$auxSituacion="NN";}
		return($auxSituacion);
	}
	
	public function getSituacionAlumnoPeriodo(){
		return $this->situacionAlumnoPeriodo;
	}
	
	public function getIdCarreraPeriodo(){
		return $this->idCarreraAlumnoPeriodo;
	}
	
	public function getYearIngresoCarreraPeriodo(){
		return $this->yearIngresoCarreraPeriodo;
	}
	
	public function getSedeAlumnoPeriodo(){
		return $this->sedeAlumnoPeriodo;
	}
	public function getidContratoPeriodo(){
		return $this->idContratoPeriodo;
	}
	public function getJornadaPeriodo(){
		return $this->jornadaAlumnoPeriodo;
	}
	
	public function getNivelAlumnoPeriodo(){
		return $this->nivelAcademicoAlumnoPeriodo;
	}
	
	public function getPresenteEnPeriodo(){
		return $this->presenteEnPeriodo;
	}
	
	public function getColorSituacion(){
		$colorSituacion="";
		switch($this->getUltimaSituacionMat()){
			case"V":
				$colorSituacion="#8de387";
				break;
			case"EG":
				$colorSituacion="#87e3dd";
				break;
			case"T":
				$colorSituacion="#87bbe3";
				break;
			case"R":
				$colorSituacion="#e3878d";
				break;
			case"NN":
				$colorSituacion="#dce387";
				break;	
		}
		return($colorSituacion);
	}
}
?>