<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
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


function BUSCA_PLANIFICACIONES($sede, $year, $semestre)
{
	
		$boton_FULL='<a href="planificaciones_full.php?sede='.base64_encode($sede).'&semestre='.base64_encode($semestre).'&year='. base64_encode($year).' " target="_blank" class="button_R">FULL PLANIFICACIONES -> PDF ['.$semestre.' - '.$year.']</a>';
		
		require("../../../../funciones/conexion_v2.php");
		require("../../../../funciones/funciones_sistema.php");
		//---------------------------------------------------//
		require("../../../../funciones/VX.php");
		$evento="Revisa Planificaciones de Sede $sede periodo [ $semestre - $year]";
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
			
			$cons_MAIN="SELECT toma_ramo_docente.* FROM toma_ramo_docente LEFT JOIN mallas ON toma_ramo_docente.cod_asignatura=mallas.cod AND toma_ramo_docente.id_carrera = mallas.id_carrera WHERE toma_ramo_docente.sede='$sede' AND toma_ramo_docente.year='$year' AND toma_ramo_docente.semestre='$semestre' ORDER by toma_ramo_docente.id_carrera,toma_ramo_docente.cod_asignatura, toma_ramo_docente.jornada, toma_ramo_docente.grupo";
			
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
							$condicion_planificacion=ESTADO_PLANIFICACION_DOCENTE($TRD_sede, $TRD_year, $TRD_semestre, $TRD_id_carrera, $TRD_cod_asignatura, $TRD_jornada, $TRD_grupo, $TRD_id_funcionario);
								
							
							if($P_nivel_asignatura>0)
							{
								$aux++;
								$escribir_registro=true;
								if($condicion_planificacion=="OK")
								{ $planificaciones_ok++; $color_condicion="#00FF00";}
								else
								{ $planificaciones_error++; $color_condicion="#FF0000";}
							
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
										<td align="center" bgcolor="'.$color_condicion.'">'.$condicion_planificacion.'</td>
										<td><a href="../informe_imprimible/informe_imprimible_1.php?id_funcionario='.base64_encode($TRD_id_funcionario).'&sede='.base64_encode($TRD_sede).'&id_carrera='.base64_encode($TRD_id_carrera).'&jornada='.base64_encode($TRD_jornada).'&grupo='.base64_encode($TRD_grupo).'&asignatura='.base64_encode($TRD_cod_asignatura).'&semestre='.base64_encode($TRD_semestre).'&year='.base64_encode($TRD_year).'" target="_blank">Ver Planificacion</a></td>
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
		
		$objResponse->Assign("div_superior","innerHTML",$boton_FULL);
	
		
		$conexion_mysqli->close();
		@mysql_close($conexion);
		return $objResponse;
	
}
$xajax->processRequest();
?>