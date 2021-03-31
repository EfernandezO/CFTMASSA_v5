<?php
class ASISTENCIA_ALUMNOS{
	private $sede;
	private $id_carrera;
	private $jornada;
	private $grupo;
	private $semestre;
	private $year;
	private $id_funcionario;
	private $cod_asignatura;
	
	private $id_curso;
	private $id_clase;
	private $id_alumno;
	private $listaAlumnosCurso;
	private $listaClases;
	private $CLASE_fecha;
	private	$CLASE_duracion;
	private	$CLASE_horario;
	private	$CLASE_modalidad;
	private	$CLASE_fecha_generacion;

	private $DEBUG=false;
	private $ruta_conexion="conexion_v2.php";
	private $rutaClaseAlumno="class_ALUMNO.php";

	
	function __construct($id_curso, $sede="", $year="", $semestre="", $id_carrera="", $cod_asignatura="", $jornada="", $grupo="", $id_funcionario=""){
		
		if($id_curso>0){$this->id_curso=$id_curso; $this->INFO_CURSO();	$this->LISTADO_ALUMNOS();}
		else{
			$this->sede=$sede;
			$this->semestre=$semestre;
			$this->year=$year;
			$this->jornada=$jornada;
			$this->grupo=$grupo;
			$this->id_carrera=$id_carrera;
			$this->cod_asignatura=$cod_asignatura;
			$this->id_funcionario=$id_funcionario;
			
			$this->ID_CURSO();
			$this->LISTADO_ALUMNOS();
		}
		
	}
	
	private function RESET(){
		unset($this->sede);
		unset($this->id_carrera);
		unset($this->jornada);
		unset($this->grupo);
		unset($this->semestre);
		unset($this->year);
		unset($this->id_funcionario);
		unset($this->cod_asignatura);
		
		unset($this->id_curso);
		unset($this->id_clase);
		unset($this->id_alumno);
		unset($this->listaAlumnosCurso);
		unset($this->listaClases);
		unset($this->CLASE_fecha);
		unset($this->CLASE_duracion);
		unset($this->CLASE_horario);
		unset($this->CLASE_modalidad);
		unset($this->CLASE_fecha_generacion);
	}
	private function INFO_CURSO(){
		if($this->DEBUG){echo"<strong>inicio metodo INFO_CURSO</strong><br>";} 
		require($this->ruta_conexion);
		$cons_C="SELECT * FROM cursos WHERE idCurso='$this->id_curso'";
		$sqliC=$conexion_mysqli->query($cons_C)or die($conexion_mysqli->error);
		$DC=$sqliC->fetch_assoc();
			$this->sede=$DC["sede"];
			$this->year=$DC["year"];
			$this->semestre=$DC["semestre"];
			$this->id_carrera=$DC["id_carrera"];
			$this->cod_asignatura=$DC["cod_asignatura"];
			$this->jornada=$DC["jornada"];
			$this->grupo=$DC["grupo"];
		
		$conexion_mysqli->close();
		if($this->DEBUG){echo"<strong>Fin metodo INFO_CURSO</strong><br>";} 
	}
	
	private function ID_CURSO(){
		if($this->DEBUG){echo"<strong>inicio metodo ID_CURSO</strong><br>";} 
		require($this->ruta_conexion);
		//busco x curso, no asocio a funcionario
			$cons="SELECT idCurso FROM cursos WHERE sede='$this->sede' AND year='$this->year' AND semestre='$this->semestre' AND id_carrera='$this->id_carrera' AND cod_asignatura='$this->cod_asignatura' AND jornada='$this->jornada' AND grupo='$this->grupo'";
			if($this->DEBUG){echo"--->$cons<br>";} 
			$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
			$DC=$sqli->fetch_assoc();
			$id_curso=$DC["idCurso"];
			if(empty($id_curso)){$id_curso=0;}
			$sqli->free();
			//determino si crear o no nuevo curso, segun id
			if($id_curso>0){$crearNuevoRegistro=false; $this->id_curso=$id_curso;}else{$crearNuevoRegistro=true;}
			
			if($crearNuevoRegistro){$this->REGISTRA_CURSO();}
		$conexion_mysqli->close();
		if($this->DEBUG){echo"<strong>Fin metodo ID_CURSO</strong><br>";} 
	}
	
	private function REGISTRA_CURSO(){
		if($this->DEBUG){echo"<strong>inicio metodo REGISTRA CURSO</strong><br>";} 
		$fechaActual=date("Y-m-d");
		require($this->ruta_conexion);
			$campos="year, semestre, sede, id_carrera, cod_asignatura, jornada, grupo, fecha_generacion";
			$valores="'$this->year', '$this->semestre', '$this->sede', '$this->id_carrera', '$this->cod_asignatura', '$this->jornada', '$this->grupo', '$fechaActual'";
			$consIN="INSERT INTO cursos ($campos) VALUES ($valores)";
			if($this->DEBUG){echo"--->$consIN<br>";} 
			
			$conexion_mysqli->query($consIN)or die($conexion_mysqli->error);
			$id_curso=$conexion_mysqli->insert_id;
			if($this->DEBUG){echo"id_curso new: $id_curso<br>";} 
			$this->id_curso=$id_curso;
		$conexion_mysqli->close();
		if($this->DEBUG){echo"<strong>Fin metodo REGISTRA CURSO</strong><br>";} 
	}
	
	private function LISTADO_ALUMNOS(){
		if($this->DEBUG){echo"<strong>inicio metodo LISTADO_ALUMNOS</strong><br>";} 
		require($this->ruta_conexion);
		//donde y como se almacenan los alumnos
		require_once($this->rutaClaseAlumno);
		$this->listaAlumnosCurso = array();
		
		$cons_A="SELECT toma_ramos.* FROM toma_ramos INNER JOIN alumno ON toma_ramos.id_alumno = alumno.id WHERE toma_ramos.id_carrera='$this->id_carrera' AND alumno.sede='$this->sede' AND toma_ramos.jornada='$this->jornada' AND toma_ramos.grupo='$this->grupo' AND toma_ramos.semestre='$this->semestre' AND toma_ramos.year='$this->year' AND toma_ramos.cod_asignatura='$this->cod_asignatura' ORDER by alumno.apellido_P, alumno.apellido_M";
		$sqliA=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
		if($this->DEBUG){echo"---->$cons_A<br>";} 
		while($A=$sqliA->fetch_assoc()){
			//alumnos del tipo Alumno se guardan en array
			$auxIdAlumno=$A["id_alumno"];
			$ALUMNO=new ALUMNO($auxIdAlumno);
			array_push($this->listaAlumnosCurso,$ALUMNO);
		}
		$sqliA->free();
		
		$conexion_mysqli->close();
		if($this->DEBUG){echo"<strong>Fin metodo LISTADO_ALUMNOS</strong><br>";} 
		return($this->listaAlumnosCurso);
	}
	//crea una clase en la tabla
	public function REGISTRA_CLASE($fecha, $horario, $duracion, $modalidad){
		$errorInfo="";
		$error=false;
		$grabar=true;
		
		if($this->DEBUG){echo"<br>inicio metodo REGISTRA_CLASE</strong><br>";} 
		require($this->ruta_conexion);
		$fechaActual=date("Y-m-d");
		
		//rango horario de la clase que quiere ingresar
		$time=new DateTime($horario);
		$time->modify("+".($duracion*45)." minute");
		$HorarioFin=$time->format("H:i:s");
		
		//verifico si existe choque horario
		$cons="SELECT horario, duracion_clase FROM cursoClaseAsistencia WHERE idCurso='$this->id_curso' AND fecha='$fecha'";
		$sqli=$conexion_mysqli->query($cons)or die("verificacion :".$conexion_mysqli->error);
		while($CA=$sqli->fetch_assoc()){
			$auxHorario=$CA["horario"];
			$auxDuracion=$CA["duracion_clase"];
			$time=new DateTime($auxHorario);
			$time->modify("+".($auxDuracion*45)." minute");
			$timeFin=$time->format("H:i:s");
			
			if($this->DEBUG){echo"->Horario $auxHorario Duracion: $auxDuracion time Fin: $timeFin<br>";}
			
			if((strtotime($horario)>=strtotime($timeFin))or(strtotime($HorarioFin)<=strtotime($auxHorario))){
				if($this->DEBUG){echo"No hay choque<br>";}
			}
			else{
				if($this->DEBUG){echo"Hay choque <br>";}
				$grabar=false;
				$errorInfo="Choque Horario con otra clase del curso";
				$error=true;
				break;
			}
		}
		$sqli->free();
		
		
		//armo consulta para grabar
		$campos="idCurso, fecha, horario, duracion_clase, modalidad, fecha_generacion";
		$valores="'$this->id_curso', '$fecha', '$horario', '$duracion', '$modalidad', '$fechaActual'";
		$cons_CL="INSERT INTO cursoClaseAsistencia ($campos) VALUES ($valores)";
		if($this->DEBUG){echo"---->$cons_CL<br>";} 
		else{
		
			//grabo si corresponde
			if($grabar){
				if(!$conexion_mysqli->query($cons_CL)){
					$error=true;
					$errorInfo="Error en consulta: ".$conexion_mysqli->error;
				}
			}
		}
		$conexion_mysqli->close();
		
		if($this->DEBUG){echo"ERRORES: $errorInfo<br> Fin metodo REGISTRA_CLASE<br>";} 
		
		return($error);
	}
	//entrega una array con todos los idClase del curso actual
	private function LISTA_CLASES($orden="DESC"){
		
		switch($orden){
			case"DESC":
				break;
			case "ASC":
				break;
			default:
				$orden="DESC";
		}
		
		if($this->DEBUG){echo"<strong>inicio metodo LISTA_CLASE</strong><br>";} 
		require($this->ruta_conexion);
			$cons="SELECT * FROM cursoClaseAsistencia WHERE idCurso='$this->id_curso' ORDER by idClase $orden";
			$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
			$this->listaClases=array();
			while($CL=$sqli->fetch_assoc()){
				array_push($this->listaClases, $CL["idClase"]);
			}
			
		$sqli->free();
		$conexion_mysqli->close();
		if($this->DEBUG){echo"<strong>Fin metodo LISTA_CLASE<br>";} 
	}
	
	//informacion sobre clase seleccionada
	private function INFO_CLASE(){
		if($this->DEBUG){echo"<strong>inicio metodo LISTA_CLASE</strong><br>";} 
		require($this->ruta_conexion);
			$cons="SELECT * FROM cursoClaseAsistencia WHERE idCurso='$this->id_curso' AND idClase='$this->id_clase'";
			$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
			while($CL=$sqli->fetch_assoc()){
				
				$this->CLASE_fecha=$CL["fecha"];
				$this->CLASE_duracion=$CL["duracion_clase"];
				$this->CLASE_horario=$CL["horario"];
				$this->CLASE_modalidad=$CL["modalidad"];
				$this->CLASE_fecha_generacion=$CL["fecha_generacion"];
			}
			
		$sqli->free();
		$conexion_mysqli->close();
		if($this->DEBUG){echo"<strong>Fin metodo LISTA_CLASE</strong><br>";} 
	}
	
	//busca si hay registros de asistencia bajo una clase determinada
	public function HAY_REGISTRO_ASISTENCIA_EN_CLASE(){
		if($this->DEBUG){echo"<strong>inicio metodo HAY_REGISTRO_CLASE</strong><br>";} 
		require($this->ruta_conexion);
		$hayRegistro=false;
			if($this->id_clase>0){
			$cons="SELECT COUNT(idAsistencia) FROM cursoAlumnoAsistencia WHERE idCurso='$this->id_curso' AND idClase='$this->id_clase'";
			if($this->DEBUG){echo"--->$cons<br>";} 
			$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
			$CL=$sqli->fetch_row();
			$numCoincidencias=$CL[0];
			if(empty($numCoincidencias)){$numCoincidencias=0;}
			if($numCoincidencias>0){$hayRegistro=true;}
			}
		if($this->DEBUG){echo"hay registro: $hayRegistro<br>";} 	
		$conexion_mysqli->close();
		if($this->DEBUG){echo"<strong>Fin metodo HAY_REGISTRO_CLASE</strong><br>";} 
		return($hayRegistro);
	}
	
	//numero total de horas de duracion de todas las clases creadas en un curso
	public function HORAS_TOTAL_CLASES_IMPARTIDAS(){
		if($this->DEBUG){echo"<strong>inicio metodo HORAS_TOTAL_CLASES_IMPARTIDAS</strong><br>";} 
		require($this->ruta_conexion);
		$horasTotal=0;
			$cons="SELECT SUM(duracion_clase) FROM cursoClaseAsistencia WHERE idCurso='$this->id_curso'";
			if($this->DEBUG){echo"--->$cons<br>";} 
			$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
			$D=$sqli->fetch_row();
			$horasTotal=$D[0];
			if(empty($horasTotal)){$horasTotal=0;}
			$sqli->free();
		$conexion_mysqli->close();
		
		if($this->DEBUG){echo"--->Horas total clases impartidas en este curso: $horasTotal<br>";} 
		if($this->DEBUG){echo"<strong>Fin metodo HORAS_TOTAL_CLASES_IMPARTIDAS</strong><br>";} 
		return($horasTotal);
	}
	
	//registra la asistencia de un alumno en una clase
	public function REGISTRA_ASISTENCIA($numHoras, $cod_user){
		if($this->DEBUG){echo"<strong>inicio metodo REGISTRA_ASISTENCIA</strong><br>";} 
		$ok=false;
		if($this->id_alumno>0){
			
			require($this->ruta_conexion);
			$fecha_generacion=date("Y-m-d H:i:s");
			$errorInfo="";
			
			$campos="idCurso, idClase, id_alumno, num_horas, fecha_generacion, cod_user";
			$valores="'$this->id_curso', '$this->id_clase', '$this->id_alumno', '$numHoras', '$fecha_generacion', '$cod_user'";
			$consIN="INSERT INTO cursoAlumnoAsistencia ($campos) VALUES ($valores)";
			if($this->DEBUG){echo"-->$consIN<br>";} 
			else{
				if($conexion_mysqli->query($consIN)){$ok=true;}else{$errorInfo=" registro asistencia: ".$conexion_mysqli->error; echo"Class ASISTENCIA_ALUMNOS metodo: Registro_asistencia". $errorInfo;}
			}
			
			$conexion_mysqli->close();
		}else{$errorInfo="Sin Alumno Seleccionado o alumno no pertenece a clase seleccionada<br>";}
		if($this->DEBUG){echo"ERROR: $errorInfo<br>Fin metodo REGISTRA_ASISTENCIA<br>";} 
		return($ok);
	}
	
	//revisa si efectivamente un alumno esta inscrito en una clase segun toma de ramos
	private function ALUMNO_PERTENECE_A_CLASE($id_alumno){
		if($this->DEBUG){echo"<strong>inicio metodo ALUMNO_PERTENECE_A_CLASE</strong><br>";} 
		$estaEnClase=false;
		if(count($this->getListaAlumnos())>0){
			foreach($this->getListaAlumnos() as $n => $auxObjeto){
				$aux_id_alumno=$auxObjeto->getIdAlumno();
				if($aux_id_alumno==$id_alumno){$estaEnClase=true; break;}
			}
		}
		if($this->DEBUG){
			if($estaEnClase){echo"ALUMNO encontrado en clase OK<br>";}
			else{echo"ALUMNO NO encontrado en clase ERROR<br>";}
		}
		if($this->DEBUG){echo"<strong>Fin metodo ALUMNO_PERTENECE_A_CLASE</strong><br>";} 
		return($estaEnClase);
	}
	
	//informacion sobre el registro de asistencia de una alumno
	public function INFO_ASISTENCIA_ALUMNO(){
		if($this->DEBUG){echo"<strong>inicio metodo INFO_ASISTENCIA_ALUMNO</strong><br>";} 
		$DA=array();
		require($this->ruta_conexion);
		if(($this->id_clase>0)and($this->id_alumno>0)){
			$cons="SELECT * FROM cursoAlumnoAsistencia WHERE idCurso='$this->id_curso' AND idClase='$this->id_clase' AND id_alumno='$this->id_alumno' LIMIT 1";
			if($this->DEBUG){echo"--->$cons<br>";} 
			$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
			$DA=$sqli->fetch_assoc();
			$sqli->free();
			
		}
		if($this->DEBUG){echo"<strong>Fin metodo INFO_ASISTENCIA_ALUMNO</strong><br>";} 
		$conexion_mysqli->close();
		return($DA);
	}
	
	//elimina todo el registro de asistencia de los alumnos en una clase en particular
	public function BORRAR_REGISTRO_ASISTENCIA_CLASE(){
		$borrado=false;
		if($this->DEBUG){echo"<strong>inicio metodo BORRAR_REGISTRO_ASISTENCIA_CLASE</strong><br>";} 
		require($this->ruta_conexion);
		if($this->id_clase>0){
			$cons="DELETE FROM cursoAlumnoAsistencia WHERE idCurso='$this->id_curso' AND idClase='$this->id_clase'";
			if($this->DEBUG){echo"--->$cons<br>";} 
			if($conexion_mysqli->query($cons)){$borrado=true;}
			else{ echo"ERROR: ".$conexion_mysqli->error;}
			
		}
		if($this->DEBUG){echo"<strong>Fin metodo BORRAR_REGISTRO_ASISTENCIA_CLASE</strong><br>";} 
		$conexion_mysqli->close();
		return($borrado);
	}
	
	//horas total de presencia de alumno en un curso en particular(todas las clases)
	public function ALUMNO_HORAS_PRESENTE_CURSO(){
		if($this->DEBUG){echo"<strong>inicio metodo ALUMNOS_HORAS_PRESENTE_CURSO</strong><br>";} 
		$horasTotalAlumno=0;
		require($this->ruta_conexion);
		if(($this->id_clase>0)and($this->id_alumno>0)){
			$cons="SELECT SUM(num_horas) FROM cursoAlumnoAsistencia WHERE idCurso='$this->id_curso' AND id_alumno='$this->id_alumno' LIMIT 1";
			if($this->DEBUG){echo"--->$cons<br>";} 
			$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
			$DA=$sqli->fetch_row();
			$sqli->free();
			$horasTotalAlumno=$DA[0];
			if(empty($horasTotalAlumno)){$horasTotalAlumno=0;}
		}
		if($this->DEBUG){echo"Horas presente en curso: $horasTotalAlumno<br><strong>Fin metodo ALUMNOS_HORAS_PRESENTE_CURSO</strong><br>";} 
		$conexion_mysqli->close();
		return($horasTotalAlumno);
	}
	
	//retorna el porcentaje de asistencia del alumno en un curso
	public function ALUMNO_PORCENTAJE_ASISTENCIA_CURSO(){
		if($this->DEBUG){echo"<strong>inicio metodo ALUMNOS_PORCENTAJE_ASISTENCIA_CURSO</strong><br>";} 
		$porcentajeAsistencia=0;
		require($this->ruta_conexion);
		if(($this->id_clase>0)and($this->id_alumno>0)){
			
			if($this->HORAS_TOTAL_CLASES_IMPARTIDAS()>0){
			  $porcentajeAsistencia=($this->ALUMNO_HORAS_PRESENTE_CURSO()*100)/$this->HORAS_TOTAL_CLASES_IMPARTIDAS();}
			  
		}
		
		if($this->DEBUG){echo"porcentaje Asistencia: $porcentajeAsistencia<br><strong>Fin metodo ALUMNOS_PORCENTAJE_ASISTENCIA_CURSO</strong><br>";} 
		$conexion_mysqli->close();
		return($porcentajeAsistencia);
	}
	
	//busca alumnos que se ausentan las x ultimas clases
	public function ALUMNOS_AUSENTES_X_CLASES($numeroClasesAusente=3){
		
		$ARRAY_AUSENTES=array();
		if($this->DEBUG){echo"<strong>inicio metodo  ALUMNOS_AUSENTES_X_CLASES</strong><br>";} 
		require($this->ruta_conexion);
		
		foreach($this->getListaAlumnos() as $n => $auxALUMNO){
			if($this->DEBUG){echo"->revisando al alumno $n<br>";}
			
			$cuentaClaseAusente=0;
			foreach($this->getListaClases() as $m => $auxIdClase){
				if($this->DEBUG){echo"->revisando clase $m -> $auxIdClase<br>";}
				$this->setIdAlumno($auxALUMNO->getIdAlumno());
				$this->setIdClase($auxIdClase);
				if($this->HAY_REGISTRO_ASISTENCIA_EN_CLASE()){
					if($this->DEBUG){echo"-->hay registros de asistencia en esta clase<br>";}
					$ARRAY_DATOS_ALUMNO=$this->INFO_ASISTENCIA_ALUMNO();
					$auxHorasPresenteAlumno=$ARRAY_DATOS_ALUMNO["num_horas"];
					if(empty($auxHorasPresenteAlumno)){$auxHorasPresenteAlumno=0;}
					
					if($this->DEBUG){echo"--->numero de horas que alumno esta en esta clase ($auxHorasPresenteAlumno)<br>";}
					//deteccion de ausente
					if($auxHorasPresenteAlumno==0){
						if($this->DEBUG){echo"--->Alumno ausente... registrar<br>";}
						$ARRAY_AUSENTES[$auxALUMNO->getIdAlumno()][]=$auxIdClase;
						$cuentaClaseAusente++;
					}
				}
				else{if($this->DEBUG){echo"-->NO hay registros de asistencia en esta clase<br>";}}
				
			}
			if($cuentaClaseAusente<$numeroClasesAusente){
				unset($ARRAY_AUSENTES[$auxALUMNO->getIdAlumno()]);
			}
		}
		
		
		if($this->DEBUG){echo"<strong>Fin metodo  ALUMNOS_AUSENTES_X_CLASES</strong><br>";} 
		$conexion_mysqli->close();
		return($ARRAY_AUSENTES);
	}
	
	//--------------------------------------------------------------------------//
	public function setDebug($debug){
		$this->DEBUG=$debug;
	}
	public function getIdCurso(){
		return($this->id_curso);
	}
	
	public function getListaAlumnos(){
		return($this->listaAlumnosCurso);
	}
	
	public function setIdClase($id_clase){
		$this->id_clase=$id_clase;
		$this->INFO_CLASE();
	}
	
	public function getListaClases($orden="DESC"){
		$this->LISTA_CLASES($orden);
		return($this->listaClases);
	}
	
	public function getClaseFecha(){
		if(empty($this->CLASE_fecha)){$this->INFO_CLASE();}
		return($this->CLASE_fecha);
	}
	
	public function getClasehorario(){
		if(empty($this->CLASE_horario)){$this->INFO_CLASE();}
		return($this->CLASE_horario);
	}
	public function getClaseDuracion(){
		if(empty($this->CLASE_duracion)){$this->INFO_CLASE();}
		return($this->CLASE_duracion);
	}
	public function getClaseModalidad(){
		if(empty($this->CLASE_tipo)){$this->INFO_CLASE();}
		return($this->CLASE_modalidad);
	}
	public function getClaseFechaGeneracion(){
		if(empty($this->CLASE_fecha_generacion)){$this->INFO_CLASE();}
		return($this->CLASE_fecha_generacion);
	}
	
	public function getIdCarrera(){
		return($this->id_carrera);
	}
	
	public function getCodAsignatura(){
		return($this->cod_asignatura);
	}
	
	public function getJornada(){
		return($this->jornada);
	}
	
	public function getGrupo(){
		return($this->grupo);
	}
	
	public function getSemestre(){
		return($this->semestre);
	}
	
	public function getYear(){
		return($this->year);
	}
	
	public function getSede(){
		return($this->sede);
	}
	
	public function setIdAlumno($id_alumno){
		if($this->ALUMNO_PERTENECE_A_CLASE($id_alumno)){
			$this->id_alumno=$id_alumno;
			return(true);
		}else{$this->id_alumno=0; return(false);}
	}
}
?>