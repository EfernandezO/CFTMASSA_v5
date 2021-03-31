<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Ver_notas_parciales_v3");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("carga_notas_parciales_v3_1_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCAR_NOTAS_PARCIALES_V3");


function BUSCAR_NOTAS_PARCIALES_V3($semestre, $year, $id_alumno, $id_carrera)
{
	$maximo_de_notas=15;
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	
	$objResponse = new xajaxResponse();
	$div='div_resultado';
	
	$html='<table width="100%" align="left">
			<thead>
				<tr>
					<th colspan="'.($maximo_de_notas+6).'">Calificaciones Periodo '.$semestre.'-'.$year.'</th>
				</tr>
				<tr>
					<td>N</td>
					<td>Nivel</td>
					<td>Jornada</td>
					<td>Cod</td>
					<td>Ramo</td>
					<td colspan="'.$maximo_de_notas.'">Notas</td>
					<td align="right">Promedio Parcial</td>
				</tr>
			</thead>
			<tbody>';
	
	if(DEBUG){$html.="Periodo $semestre - $year<br>id_alumno: $id_alumno id_carrera: $id_carrera<br>";}
	
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
					else{ $aux_nota_label='&nbsp;';}
				}
				else{ $aux_nota_label='&nbsp;';}
				
				$html.='<td align="right">'.$aux_nota_label.'</td>';
			}
			
			$html.='<td align="right"><strong>'.number_format($promedio_parcial,2,",",".").'</strong></td>';
						
			$html.='</tr>';
			
		}
	}
	else
	{}	
	
	$sqli_1->free();
	
	
	$html.='</tbody></table>';
	
	$objResponse->Assign($div,"innerHTML",$html);
	@mysql_close($conexion);	
	$conexion_mysqli->close();
	return $objResponse;
}
$xajax->processRequest();
?>