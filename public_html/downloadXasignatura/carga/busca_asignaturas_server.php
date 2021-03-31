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
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCAR_ASIGNATURAS");
$xajax->register(XAJAX_FUNCTION,"BUSCAR_CARRERAS");
$xajax->register(XAJAX_FUNCTION,"BUSCAR_SEDES");
$xajax->register(XAJAX_FUNCTION,"CARGA_ARCHIVOS");

function BUSCAR_ASIGNATURAS($id_carrera, $semestre, $year, $sede)
{
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	$id_usuario=$_SESSION["USUARIO"]["id"];
	
	$objResponse = new xajaxResponse();
	$div='div_asignaturas';
	$campo_asignatura='<select name="asignatura" id="asignatura">';
	require("../../../funciones/conexion_v2.php");
	 
	 switch($privilegio)
	 {
		 case"admi":
			 $cons="SELECT * FROM mallas WHERE id_carrera='$id_carrera' AND ramo<>'' ORDER by num_posicion, cod";
			 $sql=$conexion_mysqli->query($cons)or die("asig 1 ".$conexion_mysqli->error);
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
			 $sql=$conexion_mysqli->query($cons)or die("asig 1 ".$conexion_mysqli->error);
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
		case"Docente":
			$cons_asignatura="SELECT DISTINCT(cod_asignatura) FROM toma_ramo_docente WHERE id_funcionario='$id_usuario' AND semestre='$semestre' AND year='$year' AND sede='$sede' AND id_carrera='$id_carrera'";
					$sql_asignatura=$conexion_mysqli->query($cons_asignatura)or die("Asignatura: ".$conexion_mysqli->error);
					$num_asignatura=$sql_asignatura->num_rows;
					
					if(DEBUG){$objResponse->Alert("->$cons_asignatura\nNum carreras: $num_asignatura");}
					if($num_asignatura>0)
					{
						$campo_asignatura='<select id="asignatura" name="asignatura">';
						while($S=$sql_asignatura->fetch_assoc())
						{
							$aux_cod_asignatura=$S["cod_asignatura"];
							$cons_c1="SELECT ramo FROM mallas WHERE id_carrera='$id_carrera' AND cod='$aux_cod_asignatura' LIMIT 1";
							$sql_c1=$conexion_mysqli->query($cons_c1);
								$Dc=$sql_c1->fetch_assoc();
								$aux_nombre_asignatura=$Dc["ramo"];
							$sql_c1->free();	
							if(!empty($aux_nombre_asignatura))
							{$campo_asignatura.='<option value="'.$aux_cod_asignatura.'">['.$aux_cod_asignatura.'] '.$aux_nombre_asignatura.'</option>';}
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
			case"jefe_carrera":
			$cons_asignatura="SELECT DISTINCT(cod_asignatura) FROM toma_ramo_docente WHERE id_funcionario='$id_usuario' AND semestre='$semestre' AND year='$year' AND sede='$sede' AND id_carrera='$id_carrera'";
					$sql_asignatura=$conexion_mysqli->query($cons_asignatura)or die("Asignatura: ".$conexion_mysqli->error);
					$num_asignatura=mysql_num_rows($sql_asignatura);
					
					if(DEBUG){$objResponse->Alert("->$cons_asignatura\nNum carreras: $num_asignatura");}
					if($num_asignatura>0)
					{
						$campo_asignatura='<select id="asignatura" name="asignatura">';
						while($S=$sql_asignatura->fetch_assoc())
						{
							$aux_cod_asignatura=$S["cod_asignatura"];
							$cons_c1="SELECT ramo FROM mallas WHERE id_carrera='$id_carrera' AND cod='$aux_cod_asignatura' LIMIT 1";
							$sql_c1=$conexion_mysqli->query($cons_c1);
								$Dc=$sql_c1->fetch_assoc();
								$aux_nombre_asignatura=$Dc["ramo"];
							$sql_c1->free();	
							if(!empty($aux_nombre_asignatura))
							{$campo_asignatura.='<option value="'.$aux_cod_asignatura.'">['.$aux_cod_asignatura.'] '.$aux_nombre_asignatura.'</option>';}
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
	$campo_select='<select name="carrera" id="carrera">';
	require("../../../funciones/conexion_v2.php");
	 
	 switch($privilegio)
	 {
		 case "Docente":
			$cons_carrera="SELECT DISTINCT(id_carrera) FROM toma_ramo_docente WHERE id_funcionario='$id_usuario' AND semestre='$semestre' AND year='$year' AND sede='$sede'";
					$sql_carrera=$conexion_mysqli->query($cons_carrera)or die("Carrera: ".$conexion_mysqli->error);
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
								$aux_nombre_carrera=$Dc["carrera"];
							$sql_c1->free();	
							$campo_carrera.='<option value="'.$aux_id_carrera.'">'.$aux_nombre_carrera.'</option>';
						}
						$campo_carrera.='</select>';
					}
					else
					{ 
						$campo_carrera='<select id="carrera" name="carrera">
									   <option value="0">Sin Carrera...</option>
									   </select>';
					}
					$sql_carrera->free();;
					break;
			 case "jefe_carrera":
			$cons_carrera="SELECT DISTINCT(id_carrera) FROM toma_ramo_docente WHERE id_funcionario='$id_usuario' AND semestre='$semestre' AND year='$year' AND sede='$sede'";
					$sql_carrera=$conexion_mysqli->query($cons_carrera)or die("Carrera: ".$conexion_mysqli->error);
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
								$aux_nombre_carrera=$Dc["carrera"];
							$sql_c1->free();	
							$campo_carrera.='<option value="'.$aux_id_carrera.'">'.$aux_nombre_carrera.'</option>';
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
				case"admi_total":
					//listo todas las carreras
					$cons="SELECT carrera.* FROM carrera INNER JOIN hija_carrera_valores ON carrera.id=hija_carrera_valores.id_madre_carrera WHERE permite_matriculas='si'";
					$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
					$num_carrera=$sql->num_rows;
					if($num_carrera>0)
					{
						$campo_carrera='<select id="carrera" name="carrera" onchange="xajax_BUSCAR_ASIGNATURAS(this.value,\''.$semestre.'\', \''.$year.'\', \''.$sede.'\'); return false;">
									<option value="0">Seleccione</option>';
						while($C=$sql->fetch_assoc())
						{
							$aux_id_carrera=$C["id"];
							$aux_nombre_carrera=$C["carrera"];
							$campo_carrera.='<option value="'.$aux_id_carrera.'">'.$aux_nombre_carrera.'</option>';
							
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
					$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
					$num_carrera=$sql->num_rows;
					if($num_carrera>0)
					{
						$campo_carrera='<select id="carrera" name="carrera" onchange="xajax_BUSCAR_ASIGNATURAS(this.value,\''.$semestre.'\', \''.$year.'\', \''.$sede.'\'); return false;">
									<option value="0">Seleccione</option>';
						while($C=$sql->fetch_assoc())
						{
							$aux_id_carrera=$C["id"];
							$aux_nombre_carrera=$C["carrera"];
							$campo_carrera.='<option value="'.$aux_id_carrera.'">'.$aux_nombre_carrera.'</option>';
							
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
	
	if($restablecer_campo_asociado){ $objResponse->Assign('div_asignaturas',"innerHTML",'...<input name="asignatura" type="hidden" id="asignatura" value="0" />');}
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
		case"Docente":
			$campo_sede="";
				//---------------------------------------------//
				//seleccion de Sede
				$cons_sede="SELECT DISTINCT(sede) FROM toma_ramo_docente WHERE id_funcionario='$id_usuario' AND semestre='$semestre' AND year='$year'";
				$sql_sede=$conexion_mysqli->query($cons_sede)or die("Sede: ".$conexion_mysqli->error);
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
		case"jefe_carrera":
			$campo_sede="";
				//---------------------------------------------//
				//seleccion de Sede
				$cons_sede="SELECT DISTINCT(sede) FROM toma_ramo_docente WHERE id_funcionario='$id_usuario' AND semestre='$semestre' AND year='$year'";
				$sql_sede=$conexion_mysqli->query($cons_sede)or die("Sede: ".$conexion_mysqli->error);
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
	}
	$conexion_mysqli->close();
	return $objResponse;
}
function CARGA_ARCHIVOS($FORMULARIO)
{
	$id_usuario=$_SESSION["USUARIO"]["id"];
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	
	$div="apDiv9";
	$CONTINUAR=true;
	$html_tablas_archivos=' <table width="100%" border="1">
    <thead>
      <tr>
        <th colspan="6">Archivos Previamente Cargados</th>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td>N.</td>
        <td>Fecha</td>
        <td>Titulo</td>
        <td>Descripcion</td>
        <td colspan="2">Opciones</td>
      </tr>';
	
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funcion.php");
	$objResponse = new xajaxResponse();
	
	$sede=$FORMULARIO["sede"];
	$semestre=$FORMULARIO["semestre"];
	$year=$FORMULARIO["year"];
	$carrera=$FORMULARIO["carrera"];
	$asignatura=$FORMULARIO["asignatura"];
	$grupo_curso=$FORMULARIO["grupo_curso"];
	$jornada=$FORMULARIO["jornada"];
	//---------------------------------------//
	switch($privilegio)
	{
		case"admi":
		 	$condicion_usuario="";
			break;
		case"admi_total":
			$condicion_usuario="";
			break;
		case"Docente":
			$condicion_usuario="AND cod_user='$id_usuario'";
			break;
		case"jefe_carrera":
			$condicion_usuario="AND cod_user='$id_usuario'";
			break;
	}
	
	if($sede=="0"){ $CONTINUAR=false; $objResponse->Alert("Seleccione Sede");}
	if($carrera==0){ $CONTINUAR=false; $objResponse->Alert("Seleccione Carrera");}	
	if($asignatura==0){ $CONTINUAR=false; $objResponse->Alert("Seleccione asignatura");}	
	
		
		
		$cons="SELECT * FROM contenedor_archivos WHERE seccion='archivosXasignatura' AND sede='$sede' AND id_carrera='$carrera' AND cod_asignatura='$asignatura' AND semestre='$semestre' AND year='$year' AND grupo_curso='$grupo_curso' AND jornada='$jornada' $condicion_usuario";
		
	if($CONTINUAR)
	{
		$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		$num_registros=$sql->num_rows;
		if($num_registros>0)
		{
			$aux=0;
			while($AR=$sql->fetch_assoc())
			{
				$aux++;
				$AR_id=$AR["id"];
				$AR_titulo=$AR["titulo"];
				$AR_descripcion=$AR["descripcion"];
				$AR_fecha=$AR["fecha_generacion"];
				$AR_archivo=$AR["archivo"];
				$path="../../CONTENEDOR_GLOBAL/cargaXasignatura/";
				
				$ruta=$path.$AR_archivo;
				
				$html_tablas_archivos.='<tr>
						<td>'.$aux.'</td>
						<td>'.fecha_format($AR_fecha).'</td>
						<td>'.$AR_titulo.'</td>
						<td><a href="#" class="tooltip" title="'.$AR_descripcion.'">'.substr($AR_descripcion,0,10).'</a></td>
						<td><a href="'.$ruta.'"  class="tooltip" title="click para ver" target="_blank">'.$AR_archivo.'</a></td>
						<td><a href="#" class="tooltip" title="Eliminar" onclick="Confirmar('.$AR_id.');"><img src="../../BAses/Images/b_drop.png" width="16" height="16" alt="x" /></a></td>
					  </tr>';
			}
		}
		else
		{ $html_tablas_archivos.='<tr><td colspan="6">Sin Archivos Cargados</td></tr>';}
		$sql->free();
		$html_tablas_archivos.='</tbody></table><br><br>';
		$objResponse->Assign($div,"innerHTML",$html_tablas_archivos);
	}	
	
	
	$conexion_mysqli->close();
	return $objResponse;
}
$xajax->processRequest();
?>