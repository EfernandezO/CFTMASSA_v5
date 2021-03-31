<?php 
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG",false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_alumnos_beneficio_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Informe Contratos -  Matriculas</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:96%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 121px;
}
#link {
	text-align: right;
	padding-right: 10px;
}
-->
</style>
</head>
<?php
if($_POST)
{
	require("../../../funciones/conexion_v2.php");	
	require("../../../funciones/funciones_sistema.php");	
	require("../../../funciones/class_ALUMNO.php");	
	if(DEBUG){ var_dump($_POST);}
		
		$contrato_year=mysqli_real_escape_string($conexion_mysqli, $_POST["year_vigencia_contrato"]);
		include("../../../funciones/VX.php");
		$evento="Revisa Informe Alumnos con Beneficios Asignados year_contrato:  $contrato_year";
		REGISTRA_EVENTO($evento);
		
}
?>
<body>
<h1 id="banner">Administrador - Informe Alumnos Becados</h1>

<div id="link"><br />
<a href="index.php" class="button">Volver a Seleccion</a><br />
<br />
</div>
<div id="apDiv1" class="demo_jui">
<table border="1">
<thead>
<tr>
	<th>N.</th>
	<th>id_alumno</th>
    <th>Rut</th>
    <th>ingreso</th>
    <th>sede</th>
    <th>id_carrera</th>
    <th>jornada</th>
	<th>Beneficio Asignado</th>
	<th>Monto Asignado</th>
    <th>Deuda Total Actual</th>
    <th>Tipo Morosidad</th>
	
</tr>
</thead>
<tbody>
<?php

		$mostrar_alumno=false;
		
		$ARRAY_COLORES=array(1=>"#81BEF7",
						 2=>"#819FF7",
						 3=>"#E6E6E6",
						 4=>"#58D3F7",
						 5=>"#04B45F",
						 0=>"#F5A9F2");
		
		echo"<strong>Año de Contrato:</strong> $contrato_year<br><strong>Fecha de generacion: </strong>".date("d-m-Y H:i:s")."<br><strong>ADVERTENCIA</strong> Considerar que un alumno puede tener más de un beneficio a la vez, por lo cual será considerado en cada caso, al desagregar por morosidad, riesgo de solapamiento. Revisar morosidad de forma separada por beneficio";
		if(DEBUG){ var_export($_POST);}
		
		
		$ARRAY_BENEFICIOS=array();
		$arrayLabelMorosidad=array();
		$cons_main_1="SELECT beneficiosEstudiantiles_asignaciones.*, contratos2.sede, contratos2.id_carrera, contratos2.jornada, contratos2.yearIngresoCarrera FROM beneficiosEstudiantiles_asignaciones INNER JOIN contratos2 ON beneficiosEstudiantiles_asignaciones.id_contrato=contratos2.id AND beneficiosEstudiantiles_asignaciones.id_alumno=contratos2.id_alumno WHERE contratos2.ano='$contrato_year' ORDER by beneficiosEstudiantiles_asignaciones.id_beneficio";
		
		$sql_main_1=$conexion_mysqli->query($cons_main_1)or die($conexion_mysqli->error);
		$num_reg_M=$sql_main_1->num_rows;
		if(DEBUG){ echo"<strong>$cons_main_1</strong><br>CANTIDAD: $num_reg_M<br><br>";}
		$aux=0;
		if($num_reg_M>0)
		{
			$x=1;
			
			while($DID=$sql_main_1->fetch_assoc())
			{
				
				
				$id_alumno=$DID["id_alumno"];
				$ALUMNO=new ALUMNO($id_alumno);
				
				$rutAlumno=$ALUMNO->getRut();
				$id_beneficio=$DID["id_beneficio"];
				$valor_beneficio=$DID["valor"];
				$C_sede=$DID["sede"];
				$C_id_carrera=$DID["id_carrera"];
				$C_yearIngresoCarrera=$DID["yearIngresoCarrera"];
				$C_jornada=$DID["jornada"];
				
				//deuda actual y tipo de morosidad
				//list($deudaActualAlumno, $interesesAlumno, $gastosCObranzaAlumno)= DEUDA_ACTUAL_V2($id_alumno, date("Y-m-d"));
				$deudaActualAlumno=DEUDA_ACTUAL($id_alumno, date("Y-m-d"));
				$diasMorosidad=DIAS_MOROSIDAD($id_alumno);
				
				$validador=md5("GDXT".date("d-m-Y"));
				$url_destino='../../buscador_alumno_BETA/enrutador.php?validador='.$validador.'&id_alumno='.$id_alumno;
				
				$tipoMorosidad=TIPO_MOROSIDAD($diasMorosidad);
				$tipoMorosidadLabel=TIPO_MOROSIDAD_LABEL($tipoMorosidad);
				echo'<tr>
					<td>'.$x.'</td>
					<td><a href="'.$url_destino.'" title="Revisar este Alumno" target="_blank">'.$id_alumno.'</a></td>
					<td>'.$rutAlumno.'</td>
					<td>'.$C_yearIngresoCarrera.'</td>
					<td>'.$C_sede.'</td>
					<td>'.NOMBRE_CARRERA($C_id_carrera).'</td>
					<td>'.$C_jornada.'</td>
					<td>'.BENEFICIO_ESTUDIANTIL_NOMBRE($id_beneficio).'</td>
					<td align="right">'.$valor_beneficio.'</td>
					<td align="right">'.$deudaActualAlumno.'</td>
					<td bgcolor="'.$ARRAY_COLORES[$tipoMorosidad].'">'.$tipoMorosidadLabel.'</td>
					
					</tr>';
				$x++;
				
				if(isset($ARRAY_BENEFICIOS[$id_beneficio]["valor"])){
				$ARRAY_BENEFICIOS[$id_beneficio]["valor"]+=$valor_beneficio;
				}
				else{$ARRAY_BENEFICIOS[$id_beneficio]["valor"]=$valor_beneficio;}
				
				if(!isset($ARRAY_BENEFICIOS[$id_beneficio]["alumnos"][$id_alumno])){
					$ARRAY_BENEFICIOS[$id_beneficio]["alumnos"][$id_alumno]=true;}
					
				if(isset($ARRAY_BENEFICIOS[$id_beneficio][$tipoMorosidad]["cantidad"])){
					$ARRAY_BENEFICIOS[$id_beneficio][$tipoMorosidad]["cantidad"]+=1;
					$ARRAY_BENEFICIOS[$id_beneficio][$tipoMorosidad]["valor"]+=$deudaActualAlumno;
					}
				else{$ARRAY_BENEFICIOS[$id_beneficio][$tipoMorosidad]["cantidad"]=1;
				$ARRAY_BENEFICIOS[$id_beneficio][$tipoMorosidad]["valor"]=$deudaActualAlumno;}	
				
				if(!isset($arrayLabelMorosidad[$tipoMorosidad])){$arrayLabelMorosidad[$tipoMorosidad]=true;}
					
							
			}
		}
		else
		{
			//sin id ese año
			if(DEBUG){ echo"UID:0<br>";}
		}
		
		$sql_main_1->free();
		//-------------------------------------------------------------------------//
		
		$cons_main_2="SELECT contratos2.id_alumno, contratos2.sede, contratos2.id_carrera, contratos2.jornada, contratos2.yearIngresoCarrera FROM contratos2 WHERE contratos2.ano='$contrato_year' AND contratos2.totalBeneficiosEstudiantiles='0' AND condicion<>'INACTIVO' ORDER by id_carrera";
		
		$sql_main_2=$conexion_mysqli->query($cons_main_2)or die($conexion_mysqli->error);
		$num_reg_M=$sql_main_2->num_rows;
		if(DEBUG){ echo"<strong>$cons_main_2</strong><br>CANTIDAD: $num_reg_M<br><br>";}
		$aux=0;
		if($num_reg_M>0)
		{
			$x=1;
			
			while($DID=$sql_main_2->fetch_assoc())
			{
				
				$id_alumno=$DID["id_alumno"];
				$ALUMNO=new ALUMNO($id_alumno);
				
				$rutAlumno=$ALUMNO->getRut();
				
				$C_sede=$DID["sede"];
				$C_id_carrera=$DID["id_carrera"];
				$C_yearIngresoCarrera=$DID["yearIngresoCarrera"];
				$C_jornada=$DID["jornada"];
				$deudaActualAlumno=DEUDA_ACTUAL($id_alumno, date("Y-m-d"));
				$diasMorosidad=DIAS_MOROSIDAD($id_alumno);
				
				$validador=md5("GDXT".date("d-m-Y"));
				$url_destino='../../buscador_alumno_BETA/enrutador.php?validador='.$validador.'&id_alumno='.$id_alumno;
				
				$tipoMorosidad=TIPO_MOROSIDAD($diasMorosidad);
				$tipoMorosidadLabel=TIPO_MOROSIDAD_LABEL($tipoMorosidad);
				
				$id_beneficio=0;
				$valor_beneficio=0;
				
				echo'<tr>
					<td>'.$x.'</td>
					<td><a href="'.$url_destino.'" title="Revisar este Alumno" target="_blank">'.$id_alumno.'</a></td>
					<td>'.$rutAlumno.'</td>
					<td>'.$C_yearIngresoCarrera.'</td>
					<td>'.$C_sede.'</td>
					<td>'.NOMBRE_CARRERA($C_id_carrera).'</td>
					<td>'.$C_jornada.'</td>
					<td>'.BENEFICIO_ESTUDIANTIL_NOMBRE($id_beneficio).'</td>
					<td align="right">'.$valor_beneficio.'</td>
					<td align="right">'.$deudaActualAlumno.'</td>
					<td bgcolor="'.$ARRAY_COLORES[$tipoMorosidad].'">'.$tipoMorosidadLabel.'</td>
					
					</tr>';
					
					$x++;
				
				if(isset($ARRAY_BENEFICIOS[$id_beneficio]["valor"])){
				$ARRAY_BENEFICIOS[$id_beneficio]["valor"]+=$valor_beneficio;
				}
				else{$ARRAY_BENEFICIOS[$id_beneficio]["valor"]=$valor_beneficio;}
				
				if(!isset($ARRAY_BENEFICIOS[$id_beneficio]["alumnos"][$id_alumno])){
					$ARRAY_BENEFICIOS[$id_beneficio]["alumnos"][$id_alumno]=true;}
					
				if(isset($ARRAY_BENEFICIOS[$id_beneficio][$tipoMorosidad]["cantidad"])){
					$ARRAY_BENEFICIOS[$id_beneficio][$tipoMorosidad]["cantidad"]+=1;
					$ARRAY_BENEFICIOS[$id_beneficio][$tipoMorosidad]["valor"]+=$deudaActualAlumno;
					}
				else{$ARRAY_BENEFICIOS[$id_beneficio][$tipoMorosidad]["cantidad"]=1;
				$ARRAY_BENEFICIOS[$id_beneficio][$tipoMorosidad]["valor"]=$deudaActualAlumno;}	
				
				if(!isset($arrayLabelMorosidad[$tipoMorosidad])){$arrayLabelMorosidad[$tipoMorosidad]=true;}
			}
		}
		

	$conexion_mysqli->close();
	

?>
</table>
<?php
echo'<table border="1">
<thead>
<tr>
	<th rowspan="3">Beneficio</th>
	<th rowspan="3">Numero Beneficiados</th>
	<th rowspan="3">Total Asignado</th>
	<th colspan="18">Morosidad</th>
	</tr>
	<tr>';
	for($x=0;$x<6;$x++){echo'<th colspan="3">'.TIPO_MOROSIDAD_LABEL($x).'</th>';}
echo'</tr>
	<tr>';
	for($x=0;$x<6;$x++){
		echo'<th align="center">%</th>
			<th align="center">Cantidad</th>
			<th align="right">Valor</th>';
	}
echo'</tr>
</thead>
<tbody>';

foreach($ARRAY_BENEFICIOS as $auxIdbeneficio => $array3){
	$total=$array3["valor"];
	$numBeneficiados=count($array3["alumnos"]);
	
	
	echo'<tr>
			<td>'.BENEFICIO_ESTUDIANTIL_NOMBRE($auxIdbeneficio).'</td>
			<td>'.$numBeneficiados.'</td>
			<td align="right">'.number_format($total,0,",",".").'</td>';
			for($x=0;$x<6;$x++){
				$cantidadMorosos=0;
				$valorMorosidad=0;
				$porcentajeMorosos=0;
				if(isset($array3[$x])){
					$cantidadMorosos=$array3[$x]["cantidad"];
					$valorMorosidad=$array3[$x]["valor"];
					$porcentajeMorosos=($cantidadMorosos*100)/$numBeneficiados;
				}
			echo'<td bgcolor="'.$ARRAY_COLORES[$x].'" align="center">'.number_format($porcentajeMorosos,1,",",".").'</td>';
			echo'<td bgcolor="'.$ARRAY_COLORES[$x].'" align="center">'.$cantidadMorosos.'</td>';
			echo'<td bgcolor="'.$ARRAY_COLORES[$x].'" align="right">'.$valorMorosidad.'</td>';
			}		
			
	echo'</tr>';
	
}
echo'</tbody></table>';	
?>

</div>
</body>
</html>