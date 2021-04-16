<?php
//--------------CLASS_okalis------------------//
require("../../OKALIS/class_OKALIS_v1.php");
define("DEBUG", false);
$O=new OKALIS();
$O->DEBUG=DEBUG;
$O->setDisplayErrors(false);
$O->ruta_conexion="../../../funciones/";
$O->clave_del_archivo=md5("SelectorAsignaturaUnificadoAdministradorV1->ver");
$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("actualizar_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');

$xajax->register(XAJAX_FUNCTION,"BUSCAR_JORNADA_GRUPO");
$xajax->register(XAJAX_FUNCTION,"BUSCAR_ASIGNATURAS");
$xajax->register(XAJAX_FUNCTION,"BUSCAR_CARRERAS");
$xajax->register(XAJAX_FUNCTION,"BUSCAR_SEDES");
$xajax->register(XAJAX_FUNCTION,"VERIFICAR");
$xajax->register(XAJAX_FUNCTION,"BUSCAR_FUNCIONARIOS");


function BUSCAR_ASIGNATURAS($id_carrera, $semestre, $year, $sede)
{
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	$divBotones='apDiv2';
	
	$objResponse = new xajaxResponse();
	$div='div_asignaturas';
	$campo_asignatura='<select name="asignatura" id="asignatura" onchange="xajax_BUSCAR_JORNADA_GRUPO(this.value,\''.$id_carrera.'\', \''.$semestre.'\', \''.$year.'\', \''.$sede.'\'); return false;">
	<option value="0" selected="selected">Seleccione</option>';
	
	require("../../../funciones/conexion_v2.php");
	include("../../../funciones/funciones_sistema.php");
	 
	 switch($privilegio)
	 {
		 case"admi":
			 $cons="SELECT * FROM mallas WHERE id_carrera='$id_carrera' AND ramo<>'' ORDER by num_posicion, cod";
			 $sql=$conexion_mysqli->query($cons);
			 $num_asignaturas=$sql->num_rows;
			 if($num_asignaturas>0)
			 {
				 while($A=$sql->fetch_assoc())
				 {
					 $ASIG_cod=$A["cod"];
					 $ASIG_ramo=$A["ramo"];
					 $ASIG_nivel=$A["nivel"];
					 $campo_asignatura.='<option value="'.$ASIG_cod.'">['.$ASIG_nivel.'] '.$ASIG_ramo.'</option>';
				 }
			 }
			 else
			 { $campo_select.='<option value="0">Sin Asignaturas</option>';}
			$campo_asignatura.='</select>';
			$sql->free();
			break;
		case"admi_total":
			 $cons="SELECT * FROM mallas WHERE id_carrera='$id_carrera' AND ramo<>'' ORDER by num_posicion, cod";
			 $sql=$conexion_mysqli->query($cons);
			 $num_asignaturas=$sql->num_rows;
			 if($num_asignaturas>0)
			 {
				 while($A=$sql->fetch_assoc())
				 {
					 $ASIG_cod=$A["cod"];
					 $ASIG_ramo=$A["ramo"];
					 $ASIG_nivel=$A["nivel"];
					 $campo_asignatura.='<option value="'.$ASIG_cod.'">['.$ASIG_nivel.'] '.$ASIG_ramo.'</option>';
				 }
			 }
			 else
			 { $campo_select.='<option value="0">Sin Asignaturas</option>';}
			$campo_asignatura.='</select>';
			$sql->free();
			break;	
	 }
	
	$objResponse->Assign($div,"innerHTML",$campo_asignatura);
	$objResponse->Assign($divBotones,"innerHTML","<p></p>");
	$conexion_mysqli->close();
	return $objResponse;
}
function BUSCAR_CARRERAS($sede, $semestre, $year)
{
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	$id_usuario=$_SESSION["USUARIO"]["id"];
	$restablecer_campo_asociado=true;
	
	$objResponse = new xajaxResponse();
	$div='div_carrera';
	if(DEBUG){$objResponse->Alert("->Busca Carreras privilegio: $privilegio");}
	require("../../../funciones/conexion_v2.php");
	 
	 switch($privilegio)
	 {		
				case"admi_total":
					//listo todas las carreras
					$cons="SELECT * FROM carrera ORDER by id";
					$sql_carrera=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
					$num_carrera=$sql_carrera->num_rows;
					if($num_carrera>0)
					{
						$campo_carrera='<select id="carrera" name="carrera" onchange="xajax_BUSCAR_ASIGNATURAS(this.value,\''.$semestre.'\', \''.$year.'\', \''.$sede.'\'); return false;">
									<option value="0">Seleccione</option>';
						while($C=$sql_carrera->fetch_assoc())
						{
							$aux_id_carrera=$C["id"];
							$aux_nombre_carrera=$C["carrera"];
							$campo_carrera.='<option value="'.$aux_id_carrera.'">'.$aux_id_carrera.'_'.$aux_nombre_carrera.'</option>';
							
						}
						$campo_carrera.='</select>';			
					}
					else
					{
						 $campo_carrera='<select id="carrera" name="carrera">
									   <option value="0">Sin Carrera...</option>
									   </select>';
					}
					$sql_carrera->free();
					break;
					case"admi":
					//listo todas las carreras
					$cons="SELECT * FROM carrera ORDER by id";
					$sql=$conexion_mysqli->query($cons);
					$num_carrera=$sql->num_rows;
					if($num_carrera>0)
					{
						$campo_carrera='<select id="carrera" name="carrera" onchange="xajax_BUSCAR_ASIGNATURAS(this.value,\''.$semestre.'\', \''.$year.'\', \''.$sede.'\'); return false;">
									<option value="0">Seleccione</option>';
						while($C=$sql->fetch_assoc())
						{
							$aux_id_carrera=$C["id"];
							$aux_nombre_carrera=$C["carrera"];
							$campo_carrera.='<option value="'.$aux_id_carrera.'">'.$aux_id_carrera.'_'.$aux_nombre_carrera.'</option>';
						}
						$campo_carrera.='</select>';			
					}
					else
					{
						 $campo_carrera='<select id="carrera" name="carrera">
									   <option value="0">Sin Carrera...</option>
									   </select>';
					}
					$sql->free();
					break;
	 }
	$objResponse->Assign($div,"innerHTML",$campo_carrera);
	
	if($restablecer_campo_asociado)
	{ 
		$objResponse->Assign('div_asignaturas',"innerHTML",'...<input name="asignatura" type="hidden" id="asignatura" value="0" />');
		$objResponse->Assign('div_jornada',"innerHTML",'...<input name="jornada" type="hidden" id="jornada" value="0" />');
		$objResponse->Assign('div_grupo',"innerHTML",'...<input name="grupo_curso" type="hidden" id="grupo_curso" value="0" />');
	}
	$conexion_mysqli->close();
	
	return $objResponse;
}
function BUSCAR_SEDES($semestre, $year)
{
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funcion.php");
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	$id_usuario=$_SESSION["USUARIO"]["id"];
	
	$restablecer_campo_asociado=true;
	$objResponse = new xajaxResponse();
	$div='div_sede';
	switch($privilegio)
	{
		case"admi":
			$campo_sede=selector_sede("sede", 'onchange="xajax_BUSCAR_CARRERAS(this.value, document.getElementById(\'semestre\').value, document.getElementById(\'year\').value); return false;"',false,true);
			break;
		case"admi_total":
			$campo_sede=selector_sede("sede", 'onchange="xajax_BUSCAR_CARRERAS(this.value, document.getElementById(\'semestre\').value, document.getElementById(\'year\').value); return false;"',false,true);
			break;
		
	}
	
	$objResponse->Assign($div,"innerHTML",$campo_sede);
	if($restablecer_campo_asociado)
	{
	$objResponse->Assign('div_carrera',"innerHTML",'...<input name="carrera" type="hidden" id="carrera" value="0" />');	 
	$objResponse->Assign('div_asignaturas',"innerHTML",'...<input name="asignatura" type="hidden" id="asignatura" value="0" />');
	$objResponse->Assign('div_jornada',"innerHTML",'...<input name="jornada" type="hidden" id="jornada" value="0" />');
	$objResponse->Assign('div_grupo',"innerHTML",'...<input name="grupo_curso" type="hidden" id="grupo_curso" value="0" />');
	}
	$conexion_mysqli->close();
	return $objResponse;
}
function BUSCAR_FUNCIONARIOS($asignatura, $id_carrera, $semestre, $year, $sede, $jornada, $grupo_curso)
{
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funcion.php");
	$divBotones='apDiv2';
	

	$objResponse = new xajaxResponse();
	
	$div_docente='div_docente';
	$ARRAY_DOCENTE_IMPARTE_ASIGNATURA=array();
		//busco al docente de esta asignatura
		$cons_D="SELECT id_funcionario FROM toma_ramo_docente WHERE id_carrera='$id_carrera' AND jornada='$jornada' AND grupo='$grupo_curso' AND semestre='$semestre' AND year='$year' AND sede='$sede' AND cod_asignatura='$asignatura' ORDER by id_funcionario";
		$sqli_D=$conexion_mysqli->query($cons_D)or die($conexion_mysqli->error);
		$Do=$sqli_D->fetch_assoc();
			$id_funcionario_realiza_asignatura=$Do["id_funcionario"];
			$nombre_funcionario_realiza_asignatura=NOMBRE_PERSONAL($id_funcionario_realiza_asignatura);
			if(DEBUG){ echo"Busqueda de funcionario realiza asignatura<br> $cons_D<br>id_funcionario: $id_funcionario_realiza_asignatura<br>nombre funcionario: $nombre_funcionario_realiza_asignatura<br>";}
			$ARRAY_DOCENTE_IMPARTE_ASIGNATURA[$id_funcionario_realiza_asignatura]["nombre_funcionario"]=$nombre_funcionario_realiza_asignatura;
		$sqli_D->free();
	
	$campo_funcionarios='<select name="funcionario" id="funcionario">';
	foreach($ARRAY_DOCENTE_IMPARTE_ASIGNATURA as $auxIdFuncionario => $auxArray){
		$auxNombreFuncionario=$auxArray["nombre_funcionario"];
		$campo_funcionarios.='<option value="'.$auxIdFuncionario.'">'.$auxNombreFuncionario.'</option>';
	} 
	$campo_funcionarios.='</select>';
	

	$objResponse->Assign($divBotones,"innerHTML","<p></p>");
	$objResponse->Assign($div_docente,"innerHTML",$campo_funcionarios);
	$conexion_mysqli->close();
	return $objResponse;
}

function BUSCAR_JORNADA_GRUPO($asignatura, $id_carrera, $semestre, $year, $sede)
{
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funcion.php");

	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	$divBotones='apDiv2';
	
	$restablecer_campo_asociado=true;
	$objResponse = new xajaxResponse();
	
	$div_jornada='div_jornada';
	$div_grupo='div_grupo';
	$array_jornada=array("D"=>"Diurno","V"=>"Vespertino");
	$array_grupo=array("A"=>"A", "B"=>"B", "C"=>"C");
	
	$campo_jornada='<select name="jornada" id="jornada" onChange="xajax_BUSCAR_FUNCIONARIOS(\''.$asignatura.'\',\''.$id_carrera.'\', \''.$semestre.'\', \''.$year.'\', \''.$sede.'\',this.value, document.getElementById(\'grupo_curso\').value)"><option selected="selected" value="0">seleccione</option>';
	$campo_grupo='<select name="grupo_curso" id="grupo_curso" onChange="xajax_BUSCAR_FUNCIONARIOS(\''.$asignatura.'\',\''.$id_carrera.'\', \''.$semestre.'\', \''.$year.'\', \''.$sede.'\',this.value, document.getElementById(\'grupo_curso\').value)"><option selected="selected" value="0">seleccione</option>';


			
	switch($privilegio)
	{
		case"admi":
			
			foreach($array_jornada as $nj => $valorj)
			{$campo_jornada.='<option value="'.$nj.'">'.$valorj.'</option>';}
			
			foreach($array_grupo as $ng => $valorg)
			{$campo_grupo.='<option value="'.$ng.'">'.$valorg.'</option>';}
			break;
		case"admi_total":
			
			foreach($array_jornada as $nj => $valorj)
			{$campo_jornada.='<option value="'.$nj.'">'.$valorj.'</option>';}
			
			foreach($array_grupo as $ng => $valorg)
			{$campo_grupo.='<option value="'.$ng.'">'.$valorg.'</option>';}
			break;
		
		
	}
	
	$campo_jornada.='</select>';
	$campo_grupo.='</select>';
	
		$objResponse->Assign($div_jornada,"innerHTML",$campo_jornada);
		$objResponse->Assign($div_grupo,"innerHTML",$campo_grupo);
		$objResponse->Assign($divBotones,"innerHTML","<p></p>");
	
	$conexion_mysqli->close();
	return $objResponse;
}
function VERIFICAR($FORMULARIO){
	$objResponse = new xajaxResponse();
	$mostrarBotones=false;
	$div='apDiv2';

	//$objResponse->Alert("-->".var_dump($FORMULARIO));

	if(isset($FORMULARIO["jornada"])){
		if(($FORMULARIO["jornada"]!=="0")and($FORMULARIO["asignatura"]!=="0")){
			$mostrarBotones=true;
		}
	}

	if($mostrarBotones){
		$infoUrl='?semestre='.base64_encode($FORMULARIO["semestre"])."&year=".base64_encode($FORMULARIO["year"])."&sede=".base64_encode($FORMULARIO["sede"])."&id_carrera=".base64_encode($FORMULARIO["carrera"])."&cod_asignatura=".base64_encode($FORMULARIO["asignatura"])."&jornada=".base64_encode($FORMULARIO["jornada"])."&grupo_curso=".base64_encode($FORMULARIO["grupo_curso"])."&id_funcionario=".base64_encode($FORMULARIO["id_funcionario"]);
		
		$arrayDestinos["CALIFICADOR"]="../../Notas_parciales_3/evaluaciones/ver_evaluaciones.php".$infoUrl;
		$arrayDestinos["PLANIFICACIONES"]="../planificaciones/ver_planificaciones.php".$infoUrl;
		$arrayDestinos["CONTENIDOS"]="../contenidos/ver_contenidos.php".$infoUrl;
		$arrayDestinos["ASISTENCIA"]="../../asistenciaAlumnos/gestionAsistencia/asistenciaClases.php".$infoUrl;

		
		

		$htmlBotones='Seleccione en la seccion que desea abrir el ramo seleccionado:<br><br>';
		foreach($arrayDestinos as $auxNombre => $auxUrl){
			$htmlBotones.=' <a href="'.$auxUrl.'" class="button_R">'.$auxNombre.'</a>';
		}
		
		$objResponse->Assign($div,"innerHTML","<p>".$htmlBotones."</p>");
	}
	else{
		$objResponse->Alert("Seleccione los campos antes de continuar");
		$objResponse->Assign($div,"innerHTML","<p></p>");
	}
	
	
		
	
	return $objResponse;
}
$xajax->processRequest();
?>