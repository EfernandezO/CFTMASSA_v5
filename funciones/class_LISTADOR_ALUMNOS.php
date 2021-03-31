<?php
/*
33525da1e53d23189c9291d0e74b076b
eliasfernandezo@gmail.com
para cftmassachusetts 2020
30/04/2020
clase para unificar el filtrado de alumnos por curso
devuelve una lista con objetos ALUMNOS
*/
class LISTADOR_ALUMNOS{
	private $sede;
	private $id_carrera;
	private $yearIngresoCarrera;
	private $jornada;
	private $situacionAcademica;
	private $grupo;
	private $niveles;
	private $semestreVigencia;
	private $yearVigencia;
	private $consultaMain;
	private $DEBUG=false;
	private $lista;
	private $ruta_conexion="conexion_v2.php";
	private $rutaClaseAlumno="class_ALUMNO.php";
	private $totalAlumnos=0;
	private $exe=false;
	
	function __construct(){
		
		
	}
	
	private function generaConsulta(){
		if($this->DEBUG){ echo"----------------INICIO METODO generaConsulta----------------------------<br>";}
		//comienzo a armar condiciones de consulta maestra
		if($this->DEBUG){$this->verParametrosIngreso();}
		if($this->exe==false){$this->exe=true;}
		
		$condicion=" contratos2.sede='$this->sede' AND contratos2.condicion<>'inactivo'";
		if($this->id_carrera>0){ $condicion.=" AND contratos2.id_carrera='$this->id_carrera'";}
		if($this->yearIngresoCarrera>0){$condicion.=" AND contratos2.yearIngresoCarrera='$this->yearIngresoCarrera'";}
		if($this->jornada!="0"){$condicion.=" AND contratos2.jornada='$this->jornada'";}
		if($this->grupo!="0"){$condicion.=" AND alumno.grupo='$this->grupo'";}
		
		$condicion.=" AND contratos2.ano='$this->yearVigencia'";
		
		//----------------------------------------------------------//
		$ordenar="alumno.apellido_P, alumno.apellido_M";
	
		$this->consultaMain="SELECT DISTINCT(id_alumno), contratos2.id_carrera, contratos2.yearIngresoCarrera FROM contratos2 INNER JOIN alumno ON contratos2.id_alumno = alumno.id WHERE $condicion ORDER by $ordenar";
		if($this->DEBUG){ echo"Consulta Main: $this->consultaMain <br>";}
		if($this->DEBUG){ echo"----------------FIN METODO filtrarAlumnos----------------------------<br>";}
	}
	
	private function filtraAlumnos(){
		
		if($this->DEBUG){ echo"----------------INICIO METODO filtrarAlumnos----------------------------<br>";}
		require_once($this->rutaClaseAlumno);
		$this->lista = array();
		$this->generaConsulta();
		
		//ejecuta consulta maestra en base a condiciones generadas
		require($this->ruta_conexion);
		$sql_main_1=$conexion_mysqli->query($this->consultaMain)or die("MAIN".$conexion_mysqli->error);
		$num_reg_M=$sql_main_1->num_rows;
		if($this->DEBUG){ echo"<br><br><strong>$this->consultaMain<br>NUM.$num_reg_M</strong><br>";}
		if($num_reg_M>0)
		{
			$x=0;//contador
			while($DID=$sql_main_1->fetch_row())
			{
				$x++;
				$cumple_condicion_para_ser_mostrado=false;
				$id_alumno=$DID[0];
				$id_carrera_alumno=$DID[1];
				$yearIngresoCarrera_alumno=$DID[2];
				if($this->DEBUG){ echo"[$x] id_alumno: $id_alumno id_carrera_alumno: $id_carrera_alumno yearIngresoCarrera: $yearIngresoCarrera_alumno<br>";}
				///----------------------------------------//
				//con estos datos creo objeto alumno para contrastar, y determinar si mostrarlo o no
				$ALUMNO=new ALUMNO($id_alumno);
				//$ALUMNO->SetDebug($this->DEBUG);
				$ALUMNO->IR_A_PERIODO($this->semestreVigencia,$this->yearVigencia);
					
				//------------------------------------//
				
				$C_nivel_alumno_contrato=$ALUMNO->getNivelAlumnoPeriodo();
				$C_jornada_contrato=$ALUMNO->getJornadaPeriodo();
				$C_sede=$ALUMNO->getSedeAlumnoPeriodo();
				$C_idContratoPeriodo=$ALUMNO->getidContratoPeriodo();
				$C_presenteEnPeriodo=$ALUMNO->getPresenteEnPeriodo();
					
					
				if(empty($C_jornada_contrato)){if($this->DEBUG){echo"Jornada de cotrato vacia....<br>";}}
				if($this->DEBUG){ echo"Jornada Periodo: $C_jornada_contrato<br> Nivel de Alumno segun contrato: $C_nivel_alumno_contrato<br>id_contrato seleccionado: $C_idContratoPeriodo<br>";}
					//-------------------------------//
				//jornada
				if($this->jornada=='0')
				{ $cumple_condicion_jornada=true; if($this->DEBUG){ echo"--->cumple condicion de Jornada (todas)(buscada[$this->jornada] encontrada[$C_jornada_contrato])<br>";}}
				else
				{
					if($this->jornada==$C_jornada_contrato)
					{$cumple_condicion_jornada=true; if($this->DEBUG){ echo"--->cumple condicion de Jornada (buscada[$this->jornada] encontrada[$C_jornada_contrato])<br>";}}
					else
					{$cumple_condicion_jornada=false; if($this->DEBUG){ echo"--->NO cumple condicion de Jornada (buscada[$this->jornada] encontrada[$C_jornada_contrato])<br>";}}
				}
				//-----------------------------------//
				//nivel
				if(in_array($C_nivel_alumno_contrato, $this->niveles))
				{ $cumple_condicion_nivel=true; if($this->DEBUG){ echo"--->cumple condicion de Nivel (encontrado [$C_nivel_alumno_contrato])<br>";}}
				else
				{ $cumple_condicion_nivel=false; if($this->DEBUG){ echo"--->NO cumple condicion de Nivel (encontrado [$C_nivel_alumno_contrato])<br>";}}
				//-------------------------------------------//
				//condicion del alumno en el semestre-a�o
				
				$condicion_alumno_este_year=$ALUMNO->getSituacionAlumnoPeriodo();
				
					
				//condicion de situacion
				if($this->situacionAcademica!="A"){ 
					if($condicion_alumno_este_year==$this->situacionAcademica){
						$cumple_condicion_situacion=true;
						if($this->DEBUG){ echo"--->Cumple condicion SituacionAcademica buscada: $this->situacionAcademica encontrada: $condicion_alumno_este_year<br>";}
						}
					else{
						$cumple_condicion_situacion=false;
						if($this->DEBUG){ echo"--->NO cumple condicion SituacionAcademica buscada: $this->situacionAcademica encontrada: $condicion_alumno_este_year<br>";}
						}
				}else{
					$cumple_condicion_situacion=true;
					if($this->DEBUG){ echo"--->Cumple condicion SituacionAcademica buscada: $this->situacionAcademica encontrada: $condicion_alumno_este_year<br>";}
					}
					
				//-------------------------------------------//	
				if($cumple_condicion_jornada and $cumple_condicion_nivel and $cumple_condicion_situacion){
					$cumple_condicion_para_ser_mostrado=true;}	
				//-----------------------------------------------------------------------------------------------//					
				if(($cumple_condicion_para_ser_mostrado)and($C_presenteEnPeriodo))
				{
					$this->totalAlumnos++;
					if(isset($array_cuenta_alumnos[$condicion_alumno_este_year])){$array_cuenta_alumnos[$condicion_alumno_este_year]+=1;}
					else{ $array_cuenta_alumnos[$condicion_alumno_este_year]=1;}
					
					if($this->DEBUG){ echo"Mostrar alumno....<br><br>";}
					
					array_push($this->lista,$ALUMNO);
					//$this->lista[$id_alumno]["id_carrera"]=$id_carrera_alumno;
					//$this->lista[$id_alumno]["yearIngresoCarrera"]=$yearIngresoCarrera_alumno;
				
				}else{if($this->DEBUG){ echo"NO mostrar alumno....<br><br>";}}
				
			}
			$sql_main_1->free();
		}
		
		$conexion_mysqli->close();
		if($this->DEBUG){ echo"----------------FIN METODO filtrarAlumnos----------------------------<br>";}
	}
	
	
	
	
	private function verParametrosIngreso(){
		echo"--------------Ver Parametros Entrada-------------------<br>";
		var_dump(get_object_vars($this));
		echo"<br>--------------Ver Parametros Entrada-------------------<br><br>";
	}
	
	
	//setter
	public function SetRutaConexion($rutaConexion){
		$this->ruta_conexion=$rutaConexion;
	}
	public function setDebug($DEBUG){
		$this->DEBUG=$DEBUG;
	}
	public function setSede($sede){
		$this->sede=$sede;
	}
	public function setId_carrera($id_carrera){
		$this->id_carrera=$id_carrera;
	}
	public function setYearIngressoCarrera($yearIngresoCarrera){
		$this->yearIngresoCarrera=$yearIngresoCarrera;
	}
	public function setJornada($jornada){
		$this->jornada=$jornada;
	}
	public function setSituacionAcademica($situacionAcademica){
		$this->situacionAcademica=$situacionAcademica;
	}
	public function setGrupo($grupo){
		$this->grupo=$grupo;
	}
	public function setNiveles($niveles){
		$this->niveles=$niveles;
	}
	public function setSemestreVigencia($semestreVigencia){
		$this->semestreVigencia=$semestreVigencia;
	}
	public function setYearVigencia($yearVigencia){
		$this->yearVigencia=$yearVigencia;
	}
	public function getTotalAlumno(){
		if(!$this->exe){$this->filtraAlumnos();}
		return($this->totalAlumnos);
	}
	public function getListaAlumnos(){
		if(!$this->exe){$this->filtraAlumnos();}
		return($this->lista);
	}
}

?>