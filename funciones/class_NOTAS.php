<?php
class NOTAS{
	
	private $notaValida=false;
	private $id_alumno=0;
	private $id_carrera=0;
	private $yearIngresoCarrera=0;
	private $cod_asignatura=0;
	private $semestre=0;
	private $year=0;
	private $idTomaRamos=0;
	private $nota=0;
	private $ruta_conexion;
	private $idNota=0;
	private $numIntentos=0;
	private $intentoYaRegistrado=false;
	private $esUltimoIntento=false;
	private $DEBUG=false;
	private $hayError=false;
	private $fechaActual=0;
	private $idUsuarioActual=0;
	private $sede;
	private $condicion="ok";
	private $forzarActualizacion=false;
	private $NotaEsIgualaActual=false;
	
	function __construct($id_alumno,$yearIngresoCarrera,$idCarrera, $sede){
		if($this->getDEBUG()){ echo "---------Constructor Clase NOTAS------<br>";}
		$this->id_alumno=$id_alumno;
		$this->id_carrera=$idCarrera;
		$this->yearIngresoCarrera=$yearIngresoCarrera;
		$this->fechaActual=date("Y-m-d");
		$this->idUsuarioActual=$_SESSION["USUARIO"]["id"];
		$this->sede=$sede;
		$this->crearRegistroAcademico();
	}
	
	//establece idNota a partir del cod asignatura
	public function setCodAsignatura($cod_asignatura){
		if($this->getDEBUG()){ echo "---------INICIO metodo setCodAsignatura------<br>";}
		$auxIdNota=0;
		if($cod_asignatura>0){
			if($this->getDEBUG()){ echo "codigo valido: $cod_asignatura<br>";}
			$ruta_conexion=$this->ruta_conexion."conexion_v2.php";
			require($ruta_conexion);
			$this->cod_asignatura=$cod_asignatura;
			
			$cons="SELECT id FROM notas WHERE id_alumno='$this->id_alumno' AND id_carrera='$this->id_carrera' AND cod='$this->cod_asignatura' AND yearIngresoCarrera='$this->yearIngresoCarrera' LIMIT 1";
			if($this->getDEBUG()){ echo "$cons <br>";}
			$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
				$N=$sqli->fetch_assoc();
				$auxIdNota=$N["id"];
				
				if(empty($auxIdNota)){$auxIdNota=0;}
			$sqli->free();	
			if($this->getDEBUG()){ echo "id nota identificado: $auxIdNota <br>";}
			$conexion_mysqli->close();
			
		}
		if($auxIdNota==0){$this->hayError=true;}
		else{$this->setIdNota($auxIdNota);}
		
		if($this->getDEBUG()){ echo "---------FIN metodo setCodAsignatura------<br>";}
	}
	
	private function getHayError(){
		return($this->hayError);
	}
	private function getDebug(){
		return($this->DEBUG);
	}
	
	
	
	public function setIdUsuarioCalifica($idusuariocalifica){
		$this->idUsuarioActual=$idusuariocalifica;
	}
	public function setCondicionNota($condicionNota){
		$this->condicion=$condicionNota;
	}
	public function setDebug($debug){
		$this->DEBUG=$debug;
	}
	private function setEsUltimoIntento($esUltimoIntento){
		$this->esUltimoIntento=$esUltimoIntento;
	}
	
	private function setNotaEsIgualaActual($notaEsIgualAlActual){
		$this->NotaEsIgualaActual=$notaEsIgualAlActual;
	}
	
	private function setIntentoYaRegistrado($intentoYaRegistrado){
		$this->intentoYaRegistrado=$intentoYaRegistrado;
	}
	private function setNumIntentos($numIntentos){
		$this->numIntentos=$numIntentos;
	}
	
	
	private function setIdNota($idNota){
		$this->idNota=$idNota;
	}
	
	public function setSemestre($semestre){
		$this->semestre=$semestre;
	}
	
	public function setYear($year){
		$this->year=$year;
	}
	
	//obtiene periodo semestre año a partir de id toma ramos
	public function setIdTomaRamos($idTomaRamos){
		$this->idTomaRamos=$idTomaRamos;
		if($idTomaRamos>0){
			$ruta_conexion=$this->ruta_conexion."conexion_v2.php";
			require($ruta_conexion);
			$cons="SELECT semestre, year FROM toma_ramos WHERE id_alumno='$this->id_alumno' AND id_carrera='$this->id_carrera' AND id='$this->idTomaRamos' AND yearIngresoCarrera='$this->yearIngresoCarrera' LIMIT 1";
			$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
			$TM=$sqli->fetch_assoc();
				$this->SetSemestre($TM["semestre"]);
				$this->SetYear($TM["year"]);
			$sqli->free;
			$conexion_mysqli->close();	
		}
		
	}
	
	private function borrarNotaHija(){
		if($this->getDEBUG()){ echo "---------INICIO metodo  borrarNotaHija------<br>";}
		$ruta_conexion=$this->ruta_conexion."conexion_v2.php";
		require($ruta_conexion);
		
		$cons="DELETE FROM notas_hija WHERE id_alumno='$this->id_alumno' AND id_carrera='$this->id_carrera' AND id_nota='$this->idNota' AND semestre='$this->semestre' AND year='$this->year' LIMIT 1";
		
		if($this->getDEBUG()){ echo "$cons<br>";}
		else{$conexion_mysqli->query($cons)or die($conexion_mysqli->error);}
		$conexion_mysqli->close();
		if($this->getDEBUG()){ echo "---------FIN metodo  borrarNotaHija------<br>";}
	}

	private function actualizoNotaHija(){
		if($this->getDEBUG()){ echo "---------INICIO metodo actualizoNotaHija------<br>";}
		$ruta_conexion=$this->ruta_conexion."conexion_v2.php";
		require($ruta_conexion);
		$fechaActual=date("Y-m-d");
		
		$cons="UPDATE notas_hija SET nota='$this->nota', fecha_generacion='$fechaActual', cod_user='$this->idUsuarioActual' WHERE id_alumno='$this->id_alumno' AND id_carrera='$this->id_carrera' AND id_nota='$this->idNota' AND semestre='$this->semestre' AND year='$this->year' LIMIT 1";
		if($this->getDEBUG()){ echo"-->$cons<br>";}
		else{$conexion_mysqli->query($cons)or die($conexion_mysqli->error);}
		$conexion_mysqli->close();
		if($this->getDEBUG()){ echo "---------FIN metodo actualizoNotaHija------<br>";}
	}
	
	private function insertoNotaHija(){
		if($this->getDEBUG()){ echo "---------INICIO metodo insertoNotaHija------<br>";}
		$ruta_conexion=$this->ruta_conexion."conexion_v2.php";
		require($ruta_conexion);
		
		$cons="INSERT INTO notas_hija (id_nota, id_alumno, codigo, semestre, year, sede, id_carrera, nota, condicion,  fecha_generacion, cod_user) VALUES ('$this->idNota', '$this->id_alumno', '$this->cod_asignatura', '$this->semestre', '$this->year', '$this->sede', '$this->id_carrera', '$this->nota', '$this->condicion', '$this->fechaActual', '$this->idUsuarioActual')";
		if($this->getDEBUG()){ echo "-->$cons<br>";}
		else{$conexion_mysqli->query($cons)or die($conexion_mysqli->error);}
		$conexion_mysqli->close();
		if($this->getDEBUG()){ echo "---------FIN metodo insertoNotaHija------<br>";}
	}



	/// actualiza los registros de la tabla nota segun el ultimo registro de la tabla notas_hija
	public function actualizaNota(){
		if($this->getDEBUG()){ echo "---------INICIO metodo actualizaNota------<br>";}
		$ruta_conexion=$this->ruta_conexion."conexion_v2.php";
		require($ruta_conexion);
		
		$cons="SELECT * FROM notas_hija WHERE id_alumno='$this->id_alumno' AND id_carrera='$this->id_carrera' AND id_nota='$this->idNota' ORDER by year, semestre";
		$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		
		$auxNota="nota=NULL";
		$auxCondicion="condicion=NULL";
		$auxSemestre="semestre=NULL";
		$auxYear="ano=NULL";
		while($NH=$sqli->fetch_assoc()){
			$auxNota="nota='".$NH["nota"]."'";
			$auxCondicion="condicion='".$NH["condicion"]."'";
			$auxSemestre="semestre='".$NH["semestre"]."'";
			$auxYear="ano='".$NH["year"]."'";
		}
		
		$cons="UPDATE notas SET $auxNota, $auxCondicion, $auxSemestre, $auxYear WHERE id_alumno='$this->id_alumno' AND id_carrera='$this->id_carrera' AND id='$this->idNota' AND yearIngresoCarrera='$this->yearIngresoCarrera' LIMIT 1";
		// echo"-->$cons<br>";
		if($this->getDEBUG()){ echo"-->$cons<br>";}
		else{$conexion_mysqli->query($cons)or die($conexion_mysqli->error);}
		$conexion_mysqli->close();
		if($this->getDEBUG()){ echo "---------FIN metodo actualizaNota------<br>";}	
	}
	
	private function revisaIntentosRegistrados(){
		if($this->getDEBUG()){ echo "---------INICIO metodo revisaIntentosRegistrados------<br>";}
		$ruta_conexion=$this->ruta_conexion."conexion_v2.php";
		require($ruta_conexion);
		$auxUltimoIntento=false;
		if(!$this->hayError){
			$cons="SELECT nota, semestre, year FROM notas_hija WHERE id_alumno='$this->id_alumno' AND id_carrera='$this->id_carrera' AND id_nota='$this->idNota' ORDER by year, semestre";
			if($this->getDEBUG()){ echo "$cons<br>";}
			$sqli=$conexion_mysqli->query($cons)or die($cons);
			
			$numIntentos=0;
			$this->setIntentoYaRegistrado(false);
			$this->setEsUltimoIntento(false);
			$this->setNotaEsIgualaActual(false);
			$auxSemestre="";
			$auxYear="";
			$auxNota="";
			while($I=$sqli->fetch_assoc()){
				$numIntentos++;
				$auxSemestre=$I["semestre"];
				$auxYear=$I["year"];	
				$auxNota=$I["nota"];
				if($this->getDEBUG()){ echo "-$numIntentos semestre: $auxSemestre year:$auxYear nota: $auxNota<br>";}
				//intento ya registrado?
				//es la misma nota?
				if(($auxSemestre==$this->semestre)and($auxYear==$this->year)){
					$this->setIntentoYaRegistrado(true);
					 if($this->getDEBUG()){ echo "intento ya registrado...<br>nota Nueva: $this->nota nota Actual $auxNota<br>";} 
					 if($this->nota==$auxNota){$this->setNotaEsIgualaActual(true); if($this->getDEBUG()){ echo "Notas Iguales...<br>";}}
					 else{if($this->getDEBUG()){ echo "Notas Diferentes...<br>";}}
				}
			}
			//condiciones para ser ultimo 
			if($numIntentos==0){$auxUltimoIntento=true;}
			if((($auxSemestre<=$this->semestre)and($auxYear==$this->year))){$auxUltimoIntento=true;}
			if($auxYear<=$this->year){$auxUltimoIntento=true;}
			//----------------------//
			
			if($auxUltimoIntento){$this->setEsUltimoIntento(true); if($this->getDEBUG()){ echo "-Intento es el ultimo<br>";}}
			
			$this->setNumIntentos($numIntentos);
			
			$conexion_mysqli->close();
		}else{ if($this->getDEBUG()){ echo "Hay Error No se puede continuar<br>";}}
		if($this->getDEBUG()){ echo "---------FIN metodo revisaIntentosRegistrados------<br>";}
	}
	
	
	
	public function borraNota(){
		if($this->getDEBUG()){ echo "---------INICIO metodo borraNota------<br>";}
		if(!$this->getHayError()){
			$this->borrarNotaHija();
			$this->actualizaNota();
		}
		if($this->getDEBUG()){ echo "---------FIN metodo borraNota------<br>";}
		
	}
	
	
	public function grabaNota($nota){
		if($this->getDEBUG()){ echo "---------INICIO metodo grabaNota------<br>";}
		$this->ValidarNota($nota);
		if($this->notaValida){$this->setNota($nota);}
		$ruta_conexion=$this->ruta_conexion."conexion_v2.php";
		require($ruta_conexion);
		
		if(!$this->getHayError()){
			
			$this->revisaIntentosRegistrados();
			
			if($this->intentoYaRegistrado){
				//actualizar nota hija
				if($this->getDEBUG()){ echo "intento ya registrado, actualizo nota hija (?)<br>";}
				
				//verifico si, se debe o no actulizar la nota hija
				$actualizarNota=false;
				if($this->forzarActualizacion){$actualizarNota=true; if($this->getDEBUG()){ echo "Actualizacion Forzosa nota hija<br>";}}
				else{ if(!$this->NotaEsIgualaActual){$actualizarNota=true; if($this->getDEBUG()){ echo "Cambio de nota.. actualizar nota hija<br>";}}}
				
				if($actualizarNota){$this->actualizoNotaHija(); if($this->getDEBUG()){ echo "nota hija: Actualizada<br>";}}
				else{if($this->getDEBUG()){ echo "nota hija: NO actualizada<br>";}}
				
			}else{
				//inserta nota hija
				if($this->getDEBUG()){ echo "intento NO registrado, Inserto nota hija<br>";}
				$this->insertoNotaHija();
			}
			
			
			if($this->esUltimoIntento){
				//actualizo nota Madre
				if($this->getDEBUG()){ echo "Es ultimo intento, actualizo nota madre<br>";}
				
				$this->actualizaNota();
			}
		}else{ if($this->getDEBUG()){ echo "-hay error no continuar<br>";}}
		
		if($this->getDEBUG()){ echo "---------FIN metodo grabaNota------<br>";}
	}
	
	
	public function setNota($nota){
		$nota=str_replace(",",".",$nota);
		//le dejo solo un decimal
		$nota=number_format($nota,1);
		$this->nota=$nota;
	}
	
	public function SetforzarActualizacion($opcion){
		if($opcion){$this->forzarActualizacion=true;}
		else{$this->forzarActualizacion=false;}
	}


	private function crearRegistroAcademico(){
		if($this->getDEBUG()){ echo "---------INICIO metodo crearRegistroAcademico------<br>";}
		$ruta_conexion=$this->ruta_conexion."conexion_v2.php";
		require($ruta_conexion);
		
		if(!$this->tieneRegistroAcademico()){
			if($this->getDEBUG()){ echo"No tiene registro academico, crear....<br>";}
			$cons="SELECT * FROM mallas WHERE id_carrera= '$this->id_carrera' AND ramo<>'' ORDER by num_posicion, cod";
		   if($this->getDEBUG()){ echo"$cons<br>";}
		   $result=$conexion_mysqli->query($cons);
		   while($row = $result->fetch_assoc()) 
		   {
				$cod=$row["cod"];
				$pr1=$row["pr1"];
				$pr2=$row["pr2"];
				$pr3=$row["pr3"];
				$pr4=$row["pr4"];
				$nivel=$row["nivel"];
				$ramo=$row["ramo"];
				$es_asignatura=$row["es_asignatura"];
				
				$consIN="INSERT INTO notas (id_alumno, id_carrera, yearIngresoCarrera, cod, nivel, ramo, es_asignatura, sede) VALUES ('$this->id_alumno', '$this->id_carrera', '$this->yearIngresoCarrera', '$cod', '$nivel','$ramo', '$es_asignatura', '$this->sede')";
	
				  if($this->getDEBUG()){echo"Escribiendo Registro -> $consIN<br>";}
				  else{ $conexion_mysqli->query($consIN)or die($conexion_mysqli->error);}
		   }
		}else{if($this->getDEBUG()){ echo"Ya tiene registro academico, NO crear...<br>";}}
		
		
		if($this->getDEBUG()){ echo "---------FIN metodo crearRegistroAcademico------<br>";}
	}
	
	private function tieneRegistroAcademico(){
		if($this->getDEBUG()){ echo "---------INICIO metodo tieneRegistroAcademico------<br>";}
		$ruta_conexion=$this->ruta_conexion."conexion_v2.php";
		require($ruta_conexion);
		
	   $cons="SELECT COUNT(id) FROM notas WHERE id_alumno='$this->id_alumno' AND id_carrera='$this->id_carrera' AND yearIngresoCarrera='$this->yearIngresoCarrera'";
	   if($this->getDEBUG()){ echo"--->$cons<br>";}
	   $sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->query($cons));
	   $RA=$sqli->fetch_row();
	   $cantidadRegistros=$RA[0];
	   if(empty($cantidadRegistros)){$cantidadRegistros=0;}
	  
	  $tieneRegistroAcademico=false;	
	  if($cantidadRegistros>0){$tieneRegistroAcademico=true;}	
	  if($this->getDEBUG()){echo"Cantidad de registros: $cantidadRegistros<br>";}
	  
	  $sqli->free();
	  $conexion_mysqli->close();
	  if($this->getDEBUG()){ echo "---------FIN metodo tieneRegistroAcademico------<br>";}
	  return($tieneRegistroAcademico);
	}

	//verifico si una nota es valida
	private function ValidarNota($nota)
	{
		
		if(is_numeric($nota))
		{	
			if (($nota>0)or($nota<=7))
			{ $this->notaValida=true;}
			else{$this->notaValida=false;}
			
		}
	}
	
}
?>