<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("revision_planificaciones_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

//////////////////////XAJAX/////////////////
@require_once ("../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("proceso_server.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCA_PLANIFICACIONES");
$xajax->register(XAJAX_FUNCTION,"BUSCA_SEDE");
$xajax->register(XAJAX_FUNCTION,"BUSCA_CARRERAS");


function BUSCA_PLANIFICACIONES($sede, $year, $semestre, $id_carrera)
{
	
		$boton_FULL='<a href="planificaciones_full.php?sede='.base64_encode($sede).'&semestre='.base64_encode($semestre).'&year='. base64_encode($year).' " target="_blank" class="button_R">FULL PLANIFICACIONES -> PDF ['.$semestre.' - '.$year.']</a>';
		
		require("../../../../funciones/conexion_v2.php");
		require("../../../../funciones/funciones_sistema.php");
		//---------------------------------------------------//
		require("../../../../funciones/VX.php");
		$evento="Revisa Planificaciones V2 de Sede $sede periodo [ $semestre - $year]";
		REGISTRA_EVENTO($evento);
		//---------------------------------------------------//
		$objResponse = new xajaxResponse();
		$div='div_planificaciones';
		$tabla='<table width="100%" border="1">
		  <thead>
			<tr>
			  <th colspan="12">Planificaciones Existentes '.$sede.' ['.$semestre.' - '.$year.']</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td>Sede</td>
				<td>Ano</td>	
				<td>Semestre</td>
				<td>Carrera</td>
				<td>Asignatura</td>
				<td>Nivel</td>
				<td>Jornada</td>
				<td>Grupo</td>
				<td>Docente</td>
				<td>condicion</td>
				<td>Opcion</td>
			</tr>';
			
			if($id_carrera>0){$condicionCarrera="AND toma_ramo_docente.id_carrera='$id_carrera'";}
			else{ $condicionCarrera="";}
			
			$cons_MAIN="SELECT toma_ramo_docente.* FROM toma_ramo_docente LEFT JOIN mallas ON toma_ramo_docente.cod_asignatura=mallas.cod AND toma_ramo_docente.id_carrera = mallas.id_carrera WHERE toma_ramo_docente.cod_asignatura BETWEEN('1' AND '86') AND toma_ramo_docente.sede='$sede' AND toma_ramo_docente.year='$year' AND toma_ramo_docente.semestre='$semestre' $condicionCarrera ORDER by toma_ramo_docente.id_carrera,toma_ramo_docente.cod_asignatura, toma_ramo_docente.jornada, toma_ramo_docente.grupo";
			
			//$tabla.= $cons_MAIN;
			$sqli_MAIN=$conexion_mysqli->query($cons_MAIN)or die($conexion_mysqli->error);
			$num_toma_ramo_docente=$sqli_MAIN->num_rows;
			$aux=0;
			$planificaciones_ok=0;
			$planificaciones_error=0;
			
			if($num_toma_ramo_docente>0)
			{
				while($TRD=$sqli_MAIN->fetch_assoc())
				{
					$TRD_cod_asignatura=$TRD["cod_asignatura"];
					$TRD_id_carrera=$TRD["id_carrera"];
					$TRD_jornada=$TRD["jornada"];
					$TRD_grupo=$TRD["grupo"];
					$TRD_id_funcionario=$TRD["id_funcionario"];
					$TRD_sede=$TRD["sede"];
					$TRD_year=$TRD["year"];
					$TRD_semestre=$TRD["semestre"];
					
					
					if($TRD_cod_asignatura>0)
					{
							  list($P_nombre_asignatura,$P_nivel_asignatura)=NOMBRE_ASIGNACION($TRD_id_carrera, $TRD_cod_asignatura);
							$condicion_planificacion=ESTADO_PLANIFICACION_DOCENTE_V2($TRD_sede, $TRD_year, $TRD_semestre, $TRD_id_carrera, $TRD_cod_asignatura, $TRD_jornada, $TRD_grupo, 0);
								
							
							if($P_nivel_asignatura>0)
							{
								$aux++;
								$escribir_registro=true;
								if($condicion_planificacion)
								{ $planificaciones_ok++; $color_condicion="#00FF00"; $condicion_planificacion_label="OK"; $url_descarga='descarga_planificacion.php?id_funcionario='.base64_encode($TRD_id_funcionario).'&sede='.base64_encode($TRD_sede).'&id_carrera='.base64_encode($TRD_id_carrera).'&jornada='.base64_encode($TRD_jornada).'&grupo='.base64_encode($TRD_grupo).'&cod_asignatura='.base64_encode($TRD_cod_asignatura).'&semestre='.base64_encode($TRD_semestre).'&year='.base64_encode($TRD_year).'&lightbox[iframe]=true&lightbox[width]=500&lightbox[height]=400" class="lightbox"'; $target='target="_blank"'; $title1="ver_planificacion";}
								else
								{ $planificaciones_error++; $color_condicion="#FF0000"; $condicion_planificacion_label="pendiente"; $url_descarga='../ayudas/programa_word.php?id_carrera='.base64_encode($TRD_id_carrera).'&cod_asignatura='.base64_encode($TRD_cod_asignatura).'&year='.base64_encode($TRD_year).'&semestre='.base64_encode($TRD_semestre).'&jornada='.base64_encode($TRD_jornada).'&grupo='.base64_encode($TRD_grupo).'&sede='.base64_encode($TRD_sede).'&id_funcionario='.base64_encode($TRD_id_funcionario); $target=''; $title1="ver_plantilla";}
							
							}
							else{ $escribir_registro=false;}
					}
					else
					{
						$escribir_registro=false;
					}
					   
					 
							
							//estado revision manual planificacion
							$cons_R="SELECT * FROM planificaciones_revision_manual WHERE sede='$TRD_sede' AND semestre='$TRD_semestre' AND year='$TRD_year' AND id_carrera='$TRD_id_carrera' AND cod_asignatura='$TRD_cod_asignatura' AND jornada='$TRD_jornada' AND grupo='$TRD_grupo'";
							$sqli_R=$conexion_mysqli->query($cons_R);
							$RP=$sqli_R->fetch_assoc();
								$rev_manual_planificacion=$RP["estado"];
								if(empty($rev_manual_planificacion)){ $rev_manual_planificacion=0;}
							$sqli_R->free();
									
							if($rev_manual_planificacion==1)
							{
								$rev_manual_planificacion_label="ok"; 
								
								$url_rev_manual_planificacion="revision_manual_planificacion.php?&sede=".base64_encode($TRD_sede)."&id_carrera=".base64_encode($TRD_id_carrera)."&jornada=".base64_encode($TRD_jornada)."&grupo=".base64_encode($TRD_grupo)."&asignatura=".base64_encode($TRD_cod_asignatura)."&semestre=".base64_encode($TRD_semestre)."&year=".base64_encode($TRD_year)."&estado=0";
								$title_rev_manual_planificacion="click para desmarcar";
								$color_rev="#00FF00";
							}
							else
							{
								$rev_manual_planificacion_label="X";
								
								$url_rev_manual_planificacion="revision_manual_planificacion.php?&sede=".base64_encode($TRD_sede)."&id_carrera=".base64_encode($TRD_id_carrera)."&jornada=".base64_encode($TRD_jornada)."&grupo=".base64_encode($TRD_grupo)."&asignatura=".base64_encode($TRD_cod_asignatura)."&semestre=".base64_encode($TRD_semestre)."&year=".base64_encode($TRD_year)."&estado=1";
								$title_rev_manual_planificacion="click para marcar";
								$color_rev="#FF0000";
							}
								//-------------------------------------//
						
						if($escribir_registro)		
						{
							$color_carrera=COLOR_CARRERA($TRD_id_carrera);
							   $tabla.='<tr>
										<td>'.$TRD_sede.'</td>
										<td>'.$TRD_year.'</td>
										<td>'.$TRD_semestre.'</td>
										<td bgcolor="'.$color_carrera.'">'.NOMBRE_CARRERA($TRD_id_carrera).'</td>
										<td>'.$TRD_cod_asignatura.'_'.$P_nombre_asignatura.'</td>
										<td>'.$P_nivel_asignatura.'</td>
										<td>'.$TRD_jornada.'</td>
										<td>'.$TRD_grupo.'</td>
										<td>'.NOMBRE_PERSONAL($TRD_id_funcionario).'</td>
										<td align="center" bgcolor="'.$color_condicion.'">'.$condicion_planificacion_label.'</td>
										<td><a href="'.$url_descarga.'" '.$target.' title='.$title1.'>Ver Planificacion</a></td>
										<td bgcolor="'.$color_rev.'"><a href="'.$url_rev_manual_planificacion.'" title="'.$title_rev_manual_planificacion.'">'.$rev_manual_planificacion_label.'</a></td>
									</tr>';
						}
											
					}//fin while
				
								
			}
			else
			{
				
			}
		   $sqli_MAIN->free();		
		  $tabla.='</tbody><tfoot><tr><td colspan="12">'.$planificaciones_ok.'/'.$aux.' planificaciones creadas, '.$planificaciones_error.' planificaciones faltantes</td></tr></tfoot></table>'; 
		$objResponse->Assign($div,"innerHTML",$tabla);
		
		//$objResponse->Assign("div_superior","innerHTML",$boton_FULL);
	
		
		$conexion_mysqli->close();
		@mysql_close($conexion);
		return $objResponse;
	
}
//----------------------------------------------///
function BUSCA_SEDE($year, $semestre)
{
		$id_funcionario=$_SESSION["USUARIO"]["id"];
		$privilegio=$_SESSION["USUARIO"]["privilegio"];
		require("../../../../funciones/conexion_v2.php");
		require("../../../../funciones/funciones_sistema.php");
		$objResponse = new xajaxResponse();
		
		
		$tabla_planificaciones='<table width="100%" border="1">
		<thead>
    <tr>
      <th colspan="12">Planificaciones Existentes ['.$semestre.' - '.$year.']</th>
    </tr>
    </thead>
    <tbody>
    <tr>
    	<td>Sede</td>
    	<td>Ano</td>	
        <td>Semestre</td>
        <td>Carrera</td>
        <td>Asignatura</td>
        <td>Nivel</td>
        <td>Jornada</td>
        <td>Grupo</td>
        <td>Docente</td>
        <td>condicion</td>
        <td>Opcion</td>
        <td>-</td>
    </tr>
    <tr>
      <td colspan="12">Seleccione los parametros para revisar las planificaciones...</td>
      </tr>
    </tbody>
  </table>';
		
		switch($privilegio){
			case"jefe_carrera":
				$cons_MAIN="SELECT DISTINCT(sede)FROM toma_ramo_docente WHERE toma_ramo_docente.year='$year' AND toma_ramo_docente.semestre='$semestre' AND toma_ramo_docente.id_funcionario='$id_funcionario'";
			break;
			default:
				$cons_MAIN="SELECT DISTINCT(sede)FROM toma_ramo_docente WHERE toma_ramo_docente.year='$year' AND toma_ramo_docente.semestre='$semestre'";
				
		}
			
				
		$sqli_MAIN=$conexion_mysqli->query($cons_MAIN)or die($conexion_mysqli->error);
		
		$campo_select='<select id="sede" name="sede" onchange="xajax_BUSCA_CARRERAS(document.getElementById(\'year\').value, document.getElementById(\'semestre\').value, document.getElementById(\'sede\').value);">
		<option value="seleccione">Seleccione</option>';
		$num_sede=$sqli_MAIN->num_rows;
		
		//$objResponse->Alert('SEDE: '.$cons_MAIN."NUM: ".$num_sede);
		if($num_sede>0)
		{
			$primera_vuelta=true;
			while($TRD=$sqli_MAIN->fetch_row())
			{
				$TRD_sede=$TRD[0];
				if($primera_vuelta){$primera_vuelta=false; $aux_sede=$TRD_sede;}
				$campo_select.='<option value="'.$TRD_sede.'">'.$TRD_sede.'</option>';
			}//fin while				
		}
		else
		{$campo_select.='<option value="">...</option>';}
		$campo_select.='</select>';
		$sqli_MAIN->free();		
	
		//-----------------------------------------------------------------------------------//
		$campo_select_2='<select id="id_carrera" name="id_carrera" onchange="xajax_BUSCA_PLANIFICACIONES(document.getElementById(\'sede\').value, document.getElementById(\'year\').value, document.getElementById(\'semestre\').value, document.getElementById(\'id_carrera\').value);"><option value="seleccione">Seleccione...</option>';
		
		$campo_select_2.='</select>';
		
		//-------------------------------------------------------------------------------------------------//
		
		$objResponse->Assign("div_sede","innerHTML",$campo_select);
		$objResponse->Assign("div_carrera","innerHTML",$campo_select_2);
		$objResponse->Assign("div_planificaciones","innerHTML",$tabla_planificaciones);
		
	
		$conexion_mysqli->close();
		@mysql_close($conexion);
		return $objResponse;
}
//---------------------------------/
function BUSCA_CARRERAS($year, $semestre, $sede)
{
		$id_funcionario=$_SESSION["USUARIO"]["id"];
		$privilegio=$_SESSION["USUARIO"]["privilegio"];
		require("../../../../funciones/conexion_v2.php");
		require("../../../../funciones/funciones_sistema.php");
		$objResponse = new xajaxResponse();
		
		//-----------------------------------------------------------------------------------//
		$sede=mysqli_real_escape_string($conexion_mysqli, $sede);
		
		
		$campo_select_2='<select id="id_carrera" name="id_carrera" onchange="xajax_BUSCA_PLANIFICACIONES(document.getElementById(\'sede\').value, document.getElementById(\'year\').value, document.getElementById(\'semestre\').value, document.getElementById(\'id_carrera\').value);"><option value="seleccione">Seleccione</option>';
		
		
			switch($privilegio){
				case"jefe_carrera":
					$cons_C="SELECT DISTINCT(id_carrera) FROM toma_ramo_docente WHERE id_funcionario='$id_funcionario' AND year='$year' AND semestre='$semestre' AND sede='$sede'";
				break;
				default:
					$campo_select_2.='<option value="0">Todas</option>';
					$cons_C="SELECT DISTINCT(id_carrera) FROM toma_ramo_docente WHERE year='$year' AND semestre='$semestre' AND sede='$sede'";
			}
			$sqli_C=$conexion_mysqli->query($cons_C)or($conexion_mysqli->error);
			$num_carreras=$sqli_C->num_rows;
			if($num_carreras>0)
			{
				while($C=$sqli_C->fetch_row())
				{
					$TRD_id_carrera=$C[0];
					$aux_nombre_carrera=NOMBRE_CARRERA($TRD_id_carrera);
					
					$campo_select_2.='<option value="'.$TRD_id_carrera.'">'.$TRD_id_carrera.'_'.$aux_nombre_carrera.'</option>';
				}
			}
			else
			{$campo_select_2.='<option value="">...</option>';}
			$sqli_C->free();
		
		$campo_select_2.='</select>';
		
		//-------------------------------------------------------------------------------------------------//
		
		$objResponse->Assign("div_carrera","innerHTML",$campo_select_2);
		
	
		$conexion_mysqli->close();
		@mysql_close($conexion);
		return $objResponse;
}
$xajax->processRequest();
?>