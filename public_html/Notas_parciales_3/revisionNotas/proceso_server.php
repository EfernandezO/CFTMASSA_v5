<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("revisionNotasparcialesV1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("proceso_server.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCA_NOTAS");


function BUSCA_NOTAS($sede, $year, $semestre)
{
		$boton_FULL='<a href="planificaciones_full.php?sede='.base64_encode($sede).'&semestre='.base64_encode($semestre).'&year='. base64_encode($year).' " target="_blank" class="button_R">FULL PLANIFICACIONES -> PDF ['.$semestre.' - '.$year.']</a>';
		
		require("../../../funciones/conexion_v2.php");
		require("../../../funciones/funciones_sistema.php");
		//---------------------------------------------------//
		require("../../../funciones/VX.php");
		$evento="Revisa Notas Parciales Evaluaciones de Sede $sede periodo [ $semestre - $year]";
		REGISTRA_EVENTO($evento);
		//---------------------------------------------------//
		$objResponse = new xajaxResponse();
		$div='div_planificaciones';
		$tabla='<table width="100%" border="1">
		  <thead>
			<tr>
			  <th colspan="12">Notas Parciales Existentes '.$sede.' ['.$semestre.' - '.$year.']</th>
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
				<td>N. Evaluaciones</td>
				<td>Opcion</td>
			</tr>';
			
			$cons_MAIN="SELECT toma_ramo_docente.* FROM toma_ramo_docente LEFT JOIN mallas ON toma_ramo_docente.cod_asignatura=mallas.cod AND toma_ramo_docente.id_carrera = mallas.id_carrera WHERE toma_ramo_docente.sede='$sede' AND toma_ramo_docente.year='$year' AND toma_ramo_docente.semestre='$semestre' ORDER by toma_ramo_docente.id_carrera,toma_ramo_docente.cod_asignatura, toma_ramo_docente.jornada, toma_ramo_docente.grupo";
			
			//$tabla.= $cons_MAIN;
			$sqli_MAIN=$conexion_mysqli->query($cons_MAIN)or die($conexion_mysqli->error);
			$num_toma_ramo_docente=$sqli_MAIN->num_rows;
			$aux=0;
			$notas_ok=0;
			$notas_error=0;
			
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
					
					$urlNotas='../evaluaciones/ver_evaluaciones.php?sede='.base64_encode($TRD_sede).'&id_carrera='.base64_encode($TRD_id_carrera).'&jornada='.base64_encode($TRD_jornada).'&grupo_curso='.base64_encode($TRD_grupo).'&cod_asignatura='.base64_encode($TRD_cod_asignatura).'&semestre='.base64_encode($TRD_semestre).'&year='.base64_encode($TRD_year);
					
					if($TRD_cod_asignatura>0)
					{
							  list($P_nombre_asignatura,$P_nivel_asignatura)=NOMBRE_ASIGNACION($TRD_id_carrera, $TRD_cod_asignatura);
							$numeroNotas=0;
							$color_condicion="#FF0000";
							//buscar notas parciales
							$consNN="SELECT COUNT(id) FROM notas_parciales_evaluaciones WHERE id_carrera='$TRD_id_carrera' AND cod_asignatura='$TRD_cod_asignatura' AND sede='$TRD_sede' AND semestre='$TRD_semestre' AND year='$year' AND jornada='$TRD_jornada' AND grupo='$TRD_grupo'";
							$sqliNN=$conexion_mysqli->query($consNN)or die($conexion_mysqli->error);
							$DN=$sqliNN->fetch_row();
							$numeroNotas=$DN[0];
							if(empty($numeroNotas)){$numeroNotas=0;}
							if($numeroNotas>0){ $color_condicion="#00FF00"; $notas_ok+=1;}
								
							
							if($P_nivel_asignatura>0)
							{
								$aux++;
								$escribir_registro=true;
							}
							else{ $escribir_registro=false;}
					}
					else
					{$escribir_registro=false;}
					   
					 
							
							
						
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
										<td align="center" bgcolor="'.$color_condicion.'"><a href="'.$urlNotas.'" title="Ver detalles" target="_blank">'.$numeroNotas.'</a></td>
										<td>...</td>
										
									</tr>';
						}
											
					}//fin while
				
								
			}
			else
			{
				
			}
		   $sqli_MAIN->free();		
		   $notas_error=($aux-$notas_ok);
		  $tabla.='</tbody><tfoot><tr><td colspan="12">'.$notas_ok.'/'.$aux.' notas creadas, '.$notas_error.' notas faltantes</td></tr></tfoot></table>'; 
		$objResponse->Assign($div,"innerHTML",$tabla);
		
		//$objResponse->Assign("div_superior","innerHTML",$boton_FULL);
	
		
		$conexion_mysqli->close();
		@mysql_close($conexion);
		return $objResponse;
	
}
$xajax->processRequest();
?>