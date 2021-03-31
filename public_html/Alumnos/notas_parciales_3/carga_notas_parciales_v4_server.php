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
$xajax = new xajax("carga_notas_parciales_v3_1_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCAR_NOTAS_PARCIALES_V4");


function BUSCAR_NOTAS_PARCIALES_V4($semestre, $year, $id_alumno, $id_carrera)
{
	$maximo_de_notas=15;
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	
	$objResponse = new xajaxResponse();
	$div='div_resultado';
	
	$html='<div class="widget orange">
                            <div class="widget-title">
                                <h4><i class="icon-reorder"></i>Notas Parciales '.$semestre.' Semestre - '.$year.'</h4>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                            </span>
                            </div>
                            <div class="widget-body">
                                <table class="table table-striped table-bordered table-advance table-hover">
                                    <thead>
                                    <tr>
										<th><i class="icon-sort-by-order-alt"></i></th>
                                        <th><i class="icon-sort-by-attributes"></i>Nivel</th>
                                        <th class="hidden-phone"><i class="icon-sun"></i>Jornada</th>
                                        <th><i class="icon-bookmark"></i>Cod</th>
                                        <th><i class="icon-edit"></i>Asignatura</th>
                                        <th colspan="'.$maximo_de_notas.'"><i class="icon-edit"></i>Notas</th>
										<th><i class="icon-edit"></i>Promedio Parcial</th>
                                    </tr>
                                    </thead>
                                    <tbody>';
	
	if(DEBUG){$html.="Periodo $semestre - $year<br>id_alumno: $id_alumno id_carrera: $id_carrera<br>";}
	//-----------------------------------------------//
	 include("../../../funciones/VX.php");
	 $evento="Revisa Calificaciones Parciales V4 [$semestre - $year] id_carrera: $id_carrera";
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
			list($array_notas_parciales_v3, $promedio_parcial)=NOTAS_PARCIALES_V3($id_alumno, $id_carrera, $TR_cod_asignatura, $TR_jornada, $semestre, $year);
			
			//var_dump($array_notas_parciales_v3);
			$html.='<tr>
						<td>'.$contador.'</td>
						<td>'.$TR_nivel.'</td>
						<td>'.$TR_jornada.'</td>
						<td>'.$TR_cod_asignatura.'</td>
						<td>'.$TR_ramo.'</td>';
			for($x=0;$x<$maximo_de_notas;$x++)
			{
				if(isset($array_notas_parciales_v3[$x]))
				{
					$aux_nota=$array_notas_parciales_v3[$x];
					
					if($aux_nota>0){ $aux_nota_label=$aux_nota;}
					else{ $aux_nota_label='';}
				}
				else{ $aux_nota_label='';}
				
				$html.='<td align="right">'.$aux_nota_label.'</td>';
			}
			
			$html.='<td align="right"><strong>'.number_format($promedio_parcial,2,",",".").'</strong></td>';
						
			$html.='</tr>';
			
		}
	}
	else
	{$html.='<tr><td>Sin Registros</td></tr>';}	
	
	$sqli_1->free();
	
	
	$html.='</tbody>
			<tfoot>
				<td colspan="10">*Importante: Las Notas en intranet son de caracter informativo cualquier error u omision comunicar a secretaria.</td>
			</tfoot>
			 </table>
					</div>
					</div>';
	
	$objResponse->Assign($div,"innerHTML",$html);
	$conexion_mysqli->close();
	return $objResponse;
}
$xajax->processRequest();
?>