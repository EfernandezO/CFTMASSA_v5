<?php
//--------------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="ALUMNO";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("carga_asistencia_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCAR_ASISTENCIA");
$xajax->register(XAJAX_FUNCTION,"VER_DETALLES_ASISTENCIA");


function BUSCAR_ASISTENCIA($semestre, $year, $id_alumno, $id_carrera)
{
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	
	$objResponse = new xajaxResponse();
	$div='div_resultado';
	
	$html='<div class="widget orange">
                            <div class="widget-title">
                                <h4><i class="icon-reorder"></i>Periodo '.$semestre.' Semestre - '.$year.'</h4>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                            </span>
                            </div>
                            <div class="widget-body">
                                <table class="table table-striped table-bordered table-advance table-hover">
                                    <thead>
                                    <tr>
										<th><i class="icon-sort-by-order-alt"></i></th>
                                        <th><i class="icon-edit"></i>Asignatura</th>
                                        <th><i class="icon-edit"></i>% Asistencia Clase Realizada</th>
										<th><i class="icon-edit"></i>% Asistencia General</th>
										<th><i class="icon-sort-by-order-alt">Opc</i></th>
                                    </tr>
                                    </thead>
                                    <tbody>';
	
	if(DEBUG){$html.="Periodo $semestre - $year<br>id_alumno: $id_alumno id_carrera: $id_carrera<br>";}
	//-----------------------------------------------//
	 include("../../../funciones/VX.php");
	 $evento="Revisa Asistencia [$semestre - $year] id_carrera: $id_carrera";
   	 REGISTRA_EVENTO($evento);
	//---------------------------------------------------//
	$cons_1="SELECT * FROM toma_ramos WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND semestre='$semestre' AND year='$year'";
	$sqli_1=$conexion_mysqli->query($cons_1)or die($conexion_mysqli->error());
	$num_ramos=$sqli_1->num_rows;
	if(DEBUG){$html.="--> $cons_1<br>num_ramos: $num_ramos<br>";}
	
	
	$contador=0;
	if($num_ramos>0)
	{
		
		while($TR=$sqli_1->fetch_assoc())
		{
			$contador++;
			$TR_cod_asignatura=$TR["cod_asignatura"];
			list($TR_ramo, $TR_nivel)=NOMBRE_ASIGNACION($id_carrera, $TR_cod_asignatura);
			$TR_jornada=$TR["jornada"];
			//------------------------------------------------//
			//busco notas asociadas
			list($hrs_clase_realizada, $TOTAL_HORAS_ALUMNO, $porcentaje_asistencia_clase_realizada, $porcentaje_asistencia_alumno, $ARRAY_FECHA_ESTADO)=PORCENTAJE_ASISTENCIA($year, $semestre, $id_carrera, $TR_cod_asignatura, $id_alumno);
			
			//var_dump($array_notas_parciales_v3);
			$html.='<tr>
						<td>'.$contador.'</td>
						<td>'.$TR_ramo.'</td>
						<td align="right"><strong>'.number_format($porcentaje_asistencia_clase_realizada,1,",",".").'</strong></td>
						<td align="right"><strong>'.number_format($porcentaje_asistencia_alumno,1,",",".").'</strong></td>
						<td><button type="button" class="btn btn-small btn-success" onclick="xajax_VER_DETALLES_ASISTENCIA('.$semestre.', '.$year.', '.$id_alumno.', '.$id_carrera.', '.$TR_cod_asignatura.')">Revisar</button></td>
					</tr>';
			
		}
	}
	else
	{$html.='<tr><td>Sin Registros</td></tr>';}	
	
	$sqli_1->free();
	
	
	$html.='</tbody>
			 </table>
			 *La Asistencia Valida es la que se encuentra registrada en el libro de clases, cualquier diferencia con esta informar a Direccion Academica
					</div>
					</div>';
	
	$objResponse->Assign($div,"innerHTML",$html);
	$conexion_mysqli->close();
	return $objResponse;
}
function VER_DETALLES_ASISTENCIA($semestre, $year, $id_alumno, $id_carrera, $cod_asignatura)
{
	require("../../../funciones/funciones_sistema.php");
	
	$objResponse = new xajaxResponse();
	$div='detalle_asistencia';
	
	$html='<div class="widget-body">
                                <table class="table table-striped table-bordered table-advance table-hover">
                                    <thead>
                                    <tr>
										<th><i class="icon-sort-by-order-alt"></i></th>
										<th><i class="icon-edit"></i>Asignatura</th>
                                        <th><i class="icon-edit"></i>Fecha</th>
                                        <th><i class="icon-edit"></i>Condicion</th>
                                    </tr>
                                    </thead>
                                    <tbody>';
	
	
			list($hrs_clase_realizada, $TOTAL_HORAS_ALUMNO, $porcentaje_asistencia_clase_realizada, $porcentaje_asistencia_alumno, $ARRAY_FECHA_ESTADO)=PORCENTAJE_ASISTENCIA($year, $semestre, $id_carrera, $cod_asignatura, $id_alumno);
			
			//var_dump($array_notas_parciales_v3);
			
			list($nombre_asignatura, $nivel_asignatura)=$nombre_asignatura=NOMBRE_ASIGNACION($id_carrera, $cod_asignatura);
			
	if(count($ARRAY_FECHA_ESTADO)>0)		
	{
		$contador=0;
		foreach($ARRAY_FECHA_ESTADO as $aux_fecha => $aux_estado)	
		{	
			$contador++;
			
			switch($aux_estado)
			{
				case"P":
					$class='btn btn-small btn-success';
					break;
				case"A":
					$class='btn btn-small btn-warning';
					break;
				case"J":
					
					$class='btn btn-small btn-info';
					break;		
			}
		
				$html.='<tr>
							<td>'.$contador.'</td>
							<td>'.$nombre_asignatura.'</td>
							<td>'.$aux_fecha.'</td>
							<td><button type="button" class="'.$class.'">'.$aux_estado.'</button></td>
						</tr>';
				
			
		
		
		}
	}
	else
	{
		$html.='<tr><td>Sin Registros de Asistencia</td></tr>';
	}
	
	
	$html.='</tbody>
			 </table>
					</div>
					</div>';
	
	$objResponse->Assign($div,"innerHTML",$html);
	return $objResponse;
}
$xajax->processRequest();
?>