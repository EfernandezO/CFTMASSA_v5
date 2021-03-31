<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	
//////////////////////XAJAX/////////////////
@require_once ("../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("proceso_server.php");
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCA_PLANIFICACIONES");
$xajax->register(XAJAX_FUNCTION,"BUSCA_SEDE");
$xajax->register(XAJAX_FUNCTION,"BUSCA_CARRERAS");

function BUSCA_PLANIFICACIONES($sede, $year, $semestre, $id_carrera)
{
	if($id_carrera>0)
	{
		require("../../../../funciones/conexion_v2.php");
		require("../../../../funciones/funciones_sistema.php");
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
			
			$cons_MAIN="SELECT toma_ramo_docente.* FROM toma_ramo_docente LEFT JOIN mallas ON toma_ramo_docente.cod_asignatura=mallas.cod AND toma_ramo_docente.id_carrera = mallas.id_carrera WHERE toma_ramo_docente.sede='$sede' AND toma_ramo_docente.year='$year' AND toma_ramo_docente.semestre='$semestre' AND toma_ramo_docente.id_carrera='$id_carrera' ORDER by toma_ramo_docente.id_carrera, mallas.nivel, toma_ramo_docente.jornada, toma_ramo_docente.grupo";
			
		
			$sqli_MAIN=$conexion_mysqli->query($cons_MAIN)or die($conexion_mysqli->error);
			$num_toma_ramo_docente=$sqli_MAIN->num_rows;
			
				//$objResponse->Alert("Cons: $cons_MAIN\n NUM: $num_toma_ramo_docente");
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
						$escribir_registro=true;
					  	$aux++;
							
							  list($P_nombre_asignatura,$P_nivel_asignatura)=NOMBRE_ASIGNACION($TRD_id_carrera, $TRD_cod_asignatura);
							$condicion_planificacion=ESTADO_PLANIFICACION_DOCENTE($TRD_sede, $TRD_year, $TRD_semestre, $TRD_id_carrera, $TRD_cod_asignatura, $TRD_jornada, $TRD_grupo, $TRD_id_funcionario);
								
							if($condicion_planificacion=="OK")
							{ $planificaciones_ok++; $color_condicion="#00FF00";}
							else
							{ $planificaciones_error++; $color_condicion="#FF0000";}
							
					}
					else
					{
						$escribir_registro=false;
					}
					   
					//$objResponse->Alert("id_funcionario: $TRD_id_funcionario -> $escribir_registro");	
						if($escribir_registro)		
						{
							$color_carrera=COLOR_CARRERA($TRD_id_carrera);
							   $tabla.='<tr>
										<td>'.$TRD_sede.'</td>
										<td>'.$TRD_year.'</td>
										<td>'.$TRD_semestre.'</td>
										<td bgcolor="#'.$color_carrera.'">'.NOMBRE_CARRERA($TRD_id_carrera).'</td>
										<td>'.$P_nombre_asignatura.'</td>
										<td>'.$P_nivel_asignatura.'</td>
										<td>'.$TRD_jornada.'</td>
										<td>'.$TRD_grupo.'</td>
										<td>'.NOMBRE_PERSONAL($TRD_id_funcionario).'</td>
										<td align="center" bgcolor="'.$color_condicion.'">'.$condicion_planificacion.'</td>
										<td><a href="../informe_imprimible/informe_imprimible_1.php?id_funcionario='.base64_encode($TRD_id_funcionario).'&sede='.base64_encode($TRD_sede).'&id_carrera='.base64_encode($TRD_id_carrera).'&jornada='.base64_encode($TRD_jornada).'&grupo='.base64_encode($TRD_grupo).'&asignatura='.base64_encode($TRD_cod_asignatura).'&semestre='.base64_encode($TRD_semestre).'&year='.base64_encode($TRD_year).'" target="_blank">Ver Planificacion</a></td>
									</tr>';
						}
											
					}//fin while
					
					//-----------------------------------------------///
					include("../../../../funciones/VX.php");
					$evento="Revisa Planificaciones de su Carrera id_carrera: $id_carrera [$semestre - $year]";
					REGISTRA_EVENTO($evento);
					//-------------------------------------------------///
								
			}
			else
			{
				
			}
		   $sqli_MAIN->free();		
		  $tabla.='</tbody><tfoot><tr><td colspan="12">'.$planificaciones_ok.'/'.$aux.' planificaciones creadas, '.$planificaciones_error.' planificaciones faltantes</td></tr></tfoot></table>'; 
		$objResponse->Assign($div,"innerHTML",$tabla);
	
		
		$conexion_mysqli->close();
		@mysql_close($conexion);
		return $objResponse;
	}
}
//----------------------------------------------///
function BUSCA_SEDE($year, $semestre, $id_funcionario)
{
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
		
		$cons_MAIN="SELECT DISTINCT(sede)FROM toma_ramo_docente WHERE toma_ramo_docente.year='$year' AND toma_ramo_docente.semestre='$semestre' AND toma_ramo_docente.id_funcionario='$id_funcionario'";
		$sqli_MAIN=$conexion_mysqli->query($cons_MAIN)or die($conexion_mysqli->error);
		
		$campo_select='<select id="sede" name="sede" onchange="xajax_BUSCA_CARRERAS(document.getElementById(\'year\').value, document.getElementById(\'semestre\').value, document.getElementById(\'id_funcionario\').value, document.getElementById(\'sede\').value);">
		<option value="seleccione">Seleccione</option>';
		$num_sede=$sqli_MAIN->num_rows;
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
		{
			$campo_select.='<option value="">...</option>';
		}
		$campo_select.='</select>';
		$sqli_MAIN->free();		
	
		//-----------------------------------------------------------------------------------//
		$campo_select_2='<select id="id_carrera" name="id_carrera" onchange="xajax_BUSCA_PLANIFICACIONES(document.getElementById(\'sede\').value, document.getElementById(\'year\').value, document.getElementById(\'semestre\').value, document.getElementById(\'id_carrera\').value);"><option value="seleccione">Seleccione...</option>';
		if($num_sede>0)
		{
			$cons_C="SELECT DISTINCT(id_carrera) FROM toma_ramo_docente WHERE id_funcionario='$id_funcionario' AND year='$year' AND semestre='$semestre' AND sede='$aux_sede'";
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
		}
		else{$campo_select_2.='<option value="">...</option>';}
		
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
function BUSCA_CARRERAS($year, $semestre, $id_funcionario, $sede)
{
		require("../../../../funciones/conexion_v2.php");
		require("../../../../funciones/funciones_sistema.php");
		$objResponse = new xajaxResponse();
		
		//-----------------------------------------------------------------------------------//
		$sede=mysqli_real_escape_string($conexion_mysqli, $sede);
		
		
		$campo_select_2='<select id="id_carrera" name="id_carrera" onchange="xajax_BUSCA_PLANIFICACIONES(document.getElementById(\'sede\').value, document.getElementById(\'year\').value, document.getElementById(\'semestre\').value, document.getElementById(\'id_carrera\').value);"><option value="seleccione">Seleccione</option>';
			$cons_C="SELECT DISTINCT(id_carrera) FROM toma_ramo_docente WHERE id_funcionario='$id_funcionario' AND year='$year' AND semestre='$semestre' AND sede='$sede'";
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