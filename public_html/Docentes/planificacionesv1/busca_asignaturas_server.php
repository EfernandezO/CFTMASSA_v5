<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	$lista_invitados["privilegio"][]="matricula";
	OKALIS($lista_invitados);
	define("DEBUG",false);
//-----------------------------------------//	
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("actualizar_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');

$xajax->register(XAJAX_FUNCTION,"BUSCAR_JORNADA_GRUPO");
$xajax->register(XAJAX_FUNCTION,"BUSCAR_ASIGNATURAS");
$xajax->register(XAJAX_FUNCTION,"BUSCAR_CARRERAS");
$xajax->register(XAJAX_FUNCTION,"BUSCAR_SEDES");

function BUSCAR_ASIGNATURAS($id_carrera, $semestre, $year, $sede)
{
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	$id_usuario=$_SESSION["USUARIO"]["id"];
	
	$objResponse = new xajaxResponse();
	$div='div_asignaturas';
	$campo_asignatura='<select name="asignatura" id="asignatura" onchange="xajax_BUSCAR_JORNADA_GRUPO(this.value,\''.$id_carrera.'\', \''.$semestre.'\', \''.$year.'\', \''.$sede.'\'); return false;">
	<option value="0" selected="selected">Seleccione</option>';
	
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	 
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
		case"jefe_carrera":
			$cons_asignatura="SELECT DISTINCT(cod_asignatura) FROM toma_ramo_docente WHERE  semestre='$semestre' AND year='$year' AND sede='$sede' AND id_carrera='$id_carrera' AND (cod_asignatura BETWEEN '1' AND '86')";
					$sql_asignatura=$conexion_mysqli->query($cons_asignatura);
					$num_asignatura=$sql_asignatura->num_rows;
					
					if(DEBUG){$objResponse->Alert("->$cons_asignatura\nNum carreras: $num_asignatura");}
					if($num_asignatura>0)
					{
						
						while($S=$sql_asignatura->fetch_assoc())
						{
							$aux_cod_asignatura=$S["cod_asignatura"];
							list($aux_nombre_asignatura, $aux_nivel_asignatura)=NOMBRE_ASIGNACION($id_carrera, $aux_cod_asignatura);
							if(!empty($aux_nombre_asignatura))
							{$campo_asignatura.='<option value="'.$aux_cod_asignatura.'">['.$aux_nivel_asignatura.']'.$aux_nombre_asignatura.'</option>';}
							
						}
						$campo_asignatura.='</select>';
					}
					else
					{ 
						$campo_asignatura='<select id="asignatura" name="asignatura">
									   <option value="0">Sin asignaturas...</option>
									   </select>';
					}
					$sql_asignatura->free();
			break;
		default:
			$cons_asignatura="SELECT DISTINCT(cod_asignatura) FROM toma_ramo_docente WHERE id_funcionario='$id_usuario' AND semestre='$semestre' AND year='$year' AND sede='$sede' AND id_carrera='$id_carrera'";
					$sql_asignatura=$conexion_mysqli->query($cons_asignatura);
					$num_asignatura=$sql_asignatura->num_rows;
					
					if(DEBUG){$objResponse->Alert("->$cons_asignatura\nNum carreras: $num_asignatura");}
					if($num_asignatura>0)
					{
						while($S=$sql_asignatura->fetch_assoc())
						{
							$aux_cod_asignatura=$S["cod_asignatura"];
							$cons_c1="SELECT ramo, nivel FROM mallas WHERE id_carrera='$id_carrera' AND cod='$aux_cod_asignatura' LIMIT 1";
							$sql_c1=$conexion_mysqli->query($cons_c1);
								$Dc=$sql_c1->fetch_assoc();
								$aux_nombre_asignatura=$Dc["ramo"];
								$aux_nivel_asignatura=$Dc["nivel"];
							$sql_c1->free();	
							
							if(!empty($aux_nombre_asignatura))
							{$campo_asignatura.='<option value="'.$aux_cod_asignatura.'">['.$aux_nivel_asignatura.']'.$aux_nombre_asignatura.'</option>';}
						}
						$campo_asignatura.='</select>';
					}
					else
					{ 
						$campo_asignatura='<select id="asignatura" name="asignatura">
									   <option value="0">Sin asignaturas...</option>
									   </select>';
					}
					$sql_asignatura->free();
			break;
			
	 }
	
	$objResponse->Assign($div,"innerHTML",$campo_asignatura);
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
	
	require("../../../funciones/conexion_v2.php");
	 
	 switch($privilegio)
	 {
				case"admi_total":
					//listo todas las carreras
					$cons="SELECT carrera.* FROM carrera INNER JOIN hija_carrera_valores ON carrera.id=hija_carrera_valores.id_madre_carrera WHERE permite_matriculas='si'";
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
					case"admi":
					//listo todas las carreras
					$cons="SELECT carrera.* FROM carrera INNER JOIN hija_carrera_valores ON carrera.id=hija_carrera_valores.id_madre_carrera WHERE permite_matriculas='si'";
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
					default:
			$cons_carrera="SELECT DISTINCT(id_carrera) FROM toma_ramo_docente WHERE id_funcionario='$id_usuario' AND semestre='$semestre' AND year='$year' AND sede='$sede'";
					$sql_carrera=$conexion_mysqli->query($cons_carrera);
					$num_carrera=$sql_carrera->num_rows;
					
					if(DEBUG){$objResponse->Alert("->$cons_carrera\nNum carreras: $num_carrera");}
					if($num_carrera>0)
					{
						$campo_carrera='<select id="carrera" name="carrera" onchange="xajax_BUSCAR_ASIGNATURAS(this.value,\''.$semestre.'\', \''.$year.'\', \''.$sede.'\'); return false;">
									<option value="0">Seleccione</option>';
						while($S=$sql_carrera->fetch_assoc())
						{
							$aux_id_carrera=$S["id_carrera"];
							$cons_c1="SELECT carrera FROM carrera WHERE id='$aux_id_carrera' LIMIT 1";
							$sql_c1=$conexion_mysqli->query($cons_c1);
								$Dc=$sql_c1->fetch_assoc();
								$aux_nombre_carrera=($Dc["carrera"]);
							$sql_c1->free();	
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
		default:
			$campo_sede="";
				//---------------------------------------------//
				//seleccion de Sede
				$cons_sede="SELECT DISTINCT(sede) FROM toma_ramo_docente WHERE id_funcionario='$id_usuario' AND semestre='$semestre' AND year='$year'";
				$sql_sede=$conexion_mysqli->query($cons_sede);
				$num_sede=$sql_sede->num_rows;
				if(DEBUG){$objResponse->Alert("->$cons_sede\nNum sede: $num_sede");}
				if($num_sede>0)
				{
					$campo_sede='<select id="sede" name="sede" onchange="xajax_BUSCAR_CARRERAS(this.value, document.getElementById(\'semestre\').value, document.getElementById(\'year\').value); return false;">
								<option value="0">Seleccione</option>';
					while($S=$sql_sede->fetch_assoc())
					{
						$aux_sede=$S["sede"];
						$campo_sede.='<option value="'.$aux_sede.'">'.$aux_sede.'</option>';
					}
					$campo_sede.='</select>';
				}
				else
				{ $campo_sede='...';}
				$sql_sede->free();
				//---------------------------------------------//
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
function BUSCAR_JORNADA_GRUPO($asignatura, $id_carrera, $semestre, $year, $sede)
{
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funcion.php");

	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	$id_usuario=$_SESSION["USUARIO"]["id"];
	
	$restablecer_campo_asociado=true;
	$objResponse = new xajaxResponse();
	
	$div_jornada='div_jornada';
	$div_grupo='div_grupo';
	$array_jornada=array("D"=>"Diurno","V"=>"Vespertino");
	$array_grupo=array("A"=>"A", "B"=>"B", "C"=>"C");
	
	$campo_jornada='<select name="jornada" id="jornada">';
	$campo_grupo='<select name="grupo_curso" id="grupo_curso">';


			
	switch($privilegio)
	{
		case"admi":
			if(DEBUG){$objResponse->Alert("ADMI->$cons_J\nNum jornada: $num_jornada");}
			foreach($array_jornada as $nj => $valorj)
			{$campo_jornada.='<option value="'.$nj.'">'.$valorj.'</option>';}
			
			foreach($array_grupo as $ng => $valorg)
			{$campo_grupo.='<option value="'.$ng.'">'.$valorg.'</option>';}
			break;
		case"admi_total":
			if(DEBUG){$objResponse->Alert("admi_total->$cons_J\nNum jornada: $num_jornada");}
			foreach($array_jornada as $nj => $valorj)
			{$campo_jornada.='<option value="'.$nj.'">'.$valorj.'</option>';}
			
			foreach($array_grupo as $ng => $valorg)
			{$campo_grupo.='<option value="'.$ng.'">'.$valorg.'</option>';}
			break;
		case"jefe_carrera":
				//---------------------------------------------//
				//Busco jornada
				$cons_J="SELECT DISTINCT(jornada) FROM toma_ramo_docente WHERE cod_asignatura='$asignatura' AND id_carrera='$id_carrera' AND semestre='$semestre' AND year='$year' AND sede='$sede'";
				$sqli_J=$conexion_mysqli->query($cons_J)or die($conexion_mysqli->error);
				$num_jornada=$sqli_J->num_rows;
				if(DEBUG){$objResponse->Alert("jefe_carrera->$cons_J\nNum jornada: $num_jornada");}
				if($num_jornada>0)
				{
					while($J=$sqli_J->fetch_row())
					{
						$aux_jornada=$J[0];
						$campo_jornada.='<option value="'.$aux_jornada.'">'.$aux_jornada.'</option>';
					}
				}
				else{ if(DEBUG){$objResponse->Alert("Sin Jornadas Registradas");}}
				$sqli_J->free();
				//---------------------------------------------//
				//grupo
				$cons_G="SELECT DISTINCT(grupo) FROM toma_ramo_docente WHERE cod_asignatura='$asignatura' AND id_carrera='$id_carrera' AND semestre='$semestre' AND year='$year' AND sede='$sede'";
				$sqli_G=$conexion_mysqli->query($cons_G)or die($conexion_mysqli->error);
				$num_grupo=$sqli_G->num_rows;
				if(DEBUG){$objResponse->Alert("jefe_carrera->$cons_G\nNum grupos: $num_grupo");}
				if($num_grupo>0)
				{
					while($G=$sqli_G->fetch_row())
					{
						$aux_grupo=$G[0];
						$campo_grupo.='<option value="'.$aux_grupo.'">'.$aux_grupo.'</option>';
					}
				}
				else{ if(DEBUG){$objResponse->Alert("Sin Grupos Registradas");}}
				$sqli_G->free();
			break;	
		default:
				//---------------------------------------------//
				//Busco jornada
				$cons_J="SELECT DISTINCT(jornada) FROM toma_ramo_docente WHERE id_funcionario='$id_usuario' AND cod_asignatura='$asignatura' AND id_carrera='$id_carrera' AND semestre='$semestre' AND year='$year' AND sede='$sede'";
				$sqli_J=$conexion_mysqli->query($cons_J)or die($conexion_mysqli->error);
				$num_jornada=$sqli_J->num_rows;
				if(DEBUG){$objResponse->Alert("jefe_carrera->$cons_J\nNum jornada: $num_jornada");}
				if($num_jornada>0)
				{
					while($J=$sqli_J->fetch_row())
					{
						$aux_jornada=$J[0];
						$campo_jornada.='<option value="'.$aux_jornada.'">'.$aux_jornada.'</option>';
					}
				}
				else{ if(DEBUG){$objResponse->Alert("Sin Jornadas Registradas");}}
				$sqli_J->free();
				//---------------------------------------------//
				//grupo
				$cons_G="SELECT DISTINCT(grupo) FROM toma_ramo_docente WHERE id_funcionario='$id_usuario' AND cod_asignatura='$asignatura' AND id_carrera='$id_carrera' AND semestre='$semestre' AND year='$year' AND sede='$sede'";
				$sqli_G=$conexion_mysqli->query($cons_G)or die($conexion_mysqli->error);
				$num_grupo=$sqli_G->num_rows;
				if(DEBUG){$objResponse->Alert("jefe_carrera->$cons_G\nNum grupos: $num_grupo");}
				if($num_grupo>0)
				{
					while($G=$sqli_G->fetch_row())
					{
						$aux_grupo=$G[0];
						$campo_grupo.='<option value="'.$aux_grupo.'">'.$aux_grupo.'</option>';
					}
				}
				else{ if(DEBUG){$objResponse->Alert("Sin Grupos Registradas");}}
				$sqli_G->free();
			break;
		
	}
	
	$campo_jornada.='</select>';
	$campo_grupo.='</select>';
	
		$objResponse->Assign($div_jornada,"innerHTML",$campo_jornada);
		$objResponse->Assign($div_grupo,"innerHTML",$campo_grupo);
	
	
	$conexion_mysqli->close();
	return $objResponse;
}
$xajax->processRequest();
?>