<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(true);
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("SelectorAsignaturaDocenteUnificadoV1->ver");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("actualizar_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCAR_ASIGNATURAS");
$xajax->register(XAJAX_FUNCTION,"SELECCIONAR");


function BUSCAR_ASIGNATURAS()
{
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	$id_usuario=$_SESSION["USUARIO"]["id"];
	
	//$id_usuario=426;
	
	$objResponse = new xajaxResponse();
	$div='areaTrabajo2';

	
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	 
	 
	 $ARRAY_ASIGNACIONES=array();
	 $ARRAY_CONSULTAS=array();
	 
	 //busco ultimo periodo que el usuario tiene con asignaciones semestre-year
	 $cons_periodos="SELECT semestre, year FROM `toma_ramo_docente` where id_funcionario='$id_usuario' AND semestre=(select semestre from toma_ramo_docente WHERE id_funcionario='$id_usuario' ORDER by year DESC , semestre DESC LIMIT 1) AND year=(select year from toma_ramo_docente WHERE id_funcionario='$id_usuario' ORDER by year DESC , semestre DESC LIMIT 1) LIMIT 1";
	 
	 
	$sqli_periodos=$conexion_mysqli->query($cons_periodos)or die($conexion_mysqli->error);
	
	$UP=$sqli_periodos->fetch_assoc();
		$ultimoSemestre=$UP["semestre"];
		$ultimoYear=$UP["year"];
	$sqli_periodos->free();
	
		if(DEBUG){echo"$cons_periodos\n ->Ultimos peridodo: $ultimoSemestre - $ultimoYear\n";}
	
	list($tiene_jefatura, $array_carrera_jefatura)=ES_JEFE_DE_CARRERAV2($id_usuario, $ultimoSemestre, $ultimoYear);
	
	 if($tiene_jefatura){
		 
		 foreach($array_carrera_jefatura as $n =>$valor){
			  $aux_idCarrera=$valor[1];
			  $aux_sede=$valor[2];
			  
			   if(DEBUG){ echo"jefatura en: $aux_idCarrera -> $aux_sede<br>";}
			  //asignaturas de la carrera que no tiene asignadas, pero puede supervisar.
			  $cons_asignaciones="SELECT * FROM `toma_ramo_docente` WHERE id_carrera='$aux_idCarrera' AND sede='$aux_sede' AND semestre='$ultimoSemestre' AND year='$ultimoYear' AND id_funcionario <> '$id_usuario' ORDER by sede, id_carrera, jornada";
			  array_push($ARRAY_CONSULTAS, $cons_asignaciones);
		 }
	 }
	 	//Todas sus asignaciones
		$cons_asignaciones="SELECT * FROM `toma_ramo_docente` where id_funcionario='$id_usuario' AND semestre='$ultimoSemestre' AND year='$ultimoYear' ORDER by sede, id_carrera, jornada";
		array_push($ARRAY_CONSULTAS, $cons_asignaciones);
			 
		 
		 
if(DEBUG){ var_dump($ARRAY_CONSULTAS);}		 
		
// inicio ejecucion de consulta
		$j=0;
		foreach($ARRAY_CONSULTAS as $n => $Xconsulta){
			if(DEBUG){$objResponse->alert($Xconsulta);}
			$sqliX=$conexion_mysqli->query($Xconsulta)or die($conexion_mysqli->error);
			while($ASX=$sqliX->fetch_assoc()){
				$guardar=false;
				
				if(($ASX["cod_asignatura"]>0)and($ASX["cod_asignatura"]<60)){$guardar=true;}
				
				if($guardar){
					$ARRAY_ASIGNACIONES[$j]["id_funcionario"]=$ASX["id_funcionario"];
					$ARRAY_ASIGNACIONES[$j]["id_carrera"]=$ASX["id_carrera"];
					$ARRAY_ASIGNACIONES[$j]["jornada"]=$ASX["jornada"];
					$ARRAY_ASIGNACIONES[$j]["grupo"]=$ASX["grupo"];
					$ARRAY_ASIGNACIONES[$j]["cod_asignatura"]=$ASX["cod_asignatura"];
					$ARRAY_ASIGNACIONES[$j]["semestre"]=$ASX["semestre"];
					$ARRAY_ASIGNACIONES[$j]["year"]=$ASX["year"];
					$ARRAY_ASIGNACIONES[$j]["sede"]=$ASX["sede"];
					$j++;
				}
			}
			$sqliX->free();
		}
	 
	 
	 //inicio escrituraa
	 
	 
	 
	 $idCarreraOLD=0;
	 $sedeOLD="";
	 $primeraVuelta=true;
	 
	$tablaSelectora='<table width="80%" align="center">';
	$c=0;
	 foreach($ARRAY_ASIGNACIONES as $n => $auxArray){
		 $c++;
		 $aux_id_funcionario=$auxArray["id_funcionario"];
		 $aux_idCarrera=$auxArray["id_carrera"];
		 $auxJornada=$auxArray["jornada"];
		 $auxGrupo=$auxArray["grupo"];
		 $auxCodAsignatura=$auxArray["cod_asignatura"];
		 $auxSemestre=$auxArray["semestre"];
		 $auxYear=$auxArray["year"];
		 $auxSede=$auxArray["sede"];
		 
		 if(($idCarreraOLD!==$aux_idCarrera)or($auxSede!==$sedeOLD)){
			 if(!$primeraVuelta){$tablaSelectora.='</tbody></table><br><table width="80%" align="center">';}
			 $tablaSelectora.='<thead><tr>
			 						<th colspan="5">'.$auxSede.' - '.NOMBRE_CARRERA($aux_idCarrera).' Periodo['.$ultimoSemestre.' - '.$ultimoYear.']</th>
			 					</tr>
								</thead>
								<tbody>';
		 }
		 
		 list($auxNombreRamo, $auxNivelRamo)=NOMBRE_ASIGNACION($aux_idCarrera, $auxCodAsignatura);
		 $tablaSelectora.='<tr>
								<td><div id="posicion_'.$c.'">'.$c.'</div></td>	
								<td>'.$auxNombreRamo.'</td>	
								<td>'.$auxJornada.' '.$auxGrupo.'</td>';
				if($tiene_jefatura){if($id_usuario==$aux_id_funcionario){$msj="Ramo Propio"; $class='button_VERDE';}else{$msj="Ramo supervisado"; $class='button_AMARILLO';} $tablaSelectora.='<td>'.$msj.'</td>';}else{$class='button_VERDE';}
			$tablaSelectora.='<td><a href="#" class="'.$class.'" onclick="xajax_SELECCIONAR('.$auxSemestre.', '.$auxYear.', \''.$auxSede.'\', '.$aux_idCarrera.', '.$auxCodAsignatura.', \''.$auxJornada.'\', \''.$auxGrupo.'\', '.$aux_id_funcionario.', '.$c.', '.$j.');">Seleccionar</a></td>
		 					</tr>'; 
		$primeraVuelta=false;	
		$idCarreraOLD=$aux_idCarrera;
		$sedeOLD=$auxSede;				
	 }
	 $tablaSelectora.='</table>';
	 

	 
			
	
			
	 
	
	$objResponse->Assign($div,"innerHTML",$tablaSelectora);
	$conexion_mysqli->close();	
	return $objResponse;
}


function SELECCIONAR($semestre, $year, $sede, $id_carrera, $cod_asignatura, $jornada, $grupo,$id_funcionario, $posicion, $numElementos)
{
	$infoUrl='?semestre='.base64_encode($semestre)."&year=".base64_encode($year)."&sede=".base64_encode($sede)."&id_carrera=".base64_encode($id_carrera)."&cod_asignatura=".base64_encode($cod_asignatura)."&jornada=".base64_encode($jornada)."&grupo_curso=".base64_encode($grupo)."&id_funcionario=".base64_encode($id_funcionario);
	
	$arrayDestinos["CALIFICADOR"]="../../Notas_parciales_3/evaluaciones/ver_evaluaciones.php".$infoUrl;
	$arrayDestinos["PLANIFICACIONES"]="../planificaciones/ver_planificaciones.php".$infoUrl;
	$arrayDestinos["CONTENIDOS"]="../contenidos/ver_contenidos.php".$infoUrl;
	$arrayDestinos["ASISTENCIA"]="../../asistenciaAlumnos/gestionAsistencia/asistenciaClases.php".$infoUrl;
	
	$objResponse = new xajaxResponse();
	$div='botonera';

	$htmlBotones='Seleccione en la seccion que desea abrir el ramo seleccionado:<br><br>';
	foreach($arrayDestinos as $auxNombre => $auxUrl){
		$htmlBotones.=' <a href="'.$auxUrl.'" class="button_R">'.$auxNombre.'</a>';
	}
	
	$objResponse->Assign($div,"innerHTML","<p>".$htmlBotones."</p>");
	
	for($x=0;$x<=$numElementos;$x++){
		$objResponse->Assign('posicion_'.$x,"innerHTML",$x);
	}
	
	//destacar campo
	$objResponse->Assign('posicion_'.$posicion,"innerHTML",'<strong>*'.$posicion.'</strong>');
	return $objResponse;
}

$xajax->processRequest();
?>