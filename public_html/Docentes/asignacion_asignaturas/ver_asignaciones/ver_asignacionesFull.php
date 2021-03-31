<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(true);
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("asignaciones_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_GET)
{
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/funcion.php");
	require("../../../../funciones/funciones_sistema.php");
	//------------------------------------------------------//
	
	$id_funcionario=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["id_funcionario"]));

	
	$cons_A="SELECT * FROM personal WHERE id='$id_funcionario' LIMIT 1";
	$sql_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
	$DA=$sql_A->fetch_assoc();
		$D_rut=$DA["rut"];
		$D_nombre=$DA["nombre"];
		$D_apellido=$DA["apellido_P"]." ".$DA["apellido_M"];
	$sql_A->free();
	//------------------------------------------------------//
	include("../../../../funciones/VX.php");
	$evento="Revisa Asignacion FULL Docente id_funcionario: $id_funcionario formato xls";
	REGISTRA_EVENTO($evento);
	//-----------------------------------------------------------//

	$tabla='<table border="1">
		<thead>
			<tr>
				<th colspan="2">Datos del Docente</th>
			</tr>
			<tr>
				<td>Rut</td>
				<td>'.$D_rut.'</td>
			</tr>
			<tr>
				<td>Nombre</td>
				<td>'.$D_nombre.' '.$D_apellido.'</td>
			</tr>
		</thead>
		</table>';
		
		$tabla2='<table border="1">
		<thead>
			<tr>
				<th colspan="11">Lista TOTAL de Ramos Asignados</th>
			</tr>
			<tr>
				<td>-</td>
				<td>semestre</td>
				<td>año</td>
				<td>Carrera</td>
				<td>Jornada</td>
				<td>Grupo</td>
				<td>Nivel</td>
				<td>Ramo</td>
				<td>$.Hr</td>
				<td>N. Hrs</td>
				<td>Total</td>
			</tr>
		</thead>
		<tbody>';


	
		$cons="SELECT toma_ramo_docente.* FROM toma_ramo_docente WHERE toma_ramo_docente.id_funcionario='$id_funcionario' ORDER by year, semestre";
		if(DEBUG){ echo"--->$cons<br>";}
		$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		$num_ramos_tomados=$sql->num_rows;
		
		if($num_ramos_tomados>0)
		{
			$aux=0;
			$SUMA_TOTAL=0;
			$SUMA_HORAS=0;
			while($R=$sql->fetch_assoc())
			{
				$aux++;
				$R_numero_horas=$R["numero_horas"];
				$R_codigo=$R["cod_asignatura"];
				$R_fecha_generacion=fecha_format($R["fecha_generacion"]);
				$R_id_carrera=$R["id_carrera"];
				$R_sede=$R["sede"];
				$R_valor_hora=$R["valor_hora"];
				$R_total=$R["total"];
				$R_jornada=$R["jornada"];
				$R_grupo=$R["grupo"];
				
				$R_semestre=$R["semestre"];
				$R_year=$R["year"];
				
				$SUMA_TOTAL+=$R_total;
				$SUMA_HORAS+=$R_numero_horas;

				list($R_ramo, $R_nivel)=NOMBRE_ASIGNACION($R_id_carrera, $R_codigo);
				$R_carrera=NOMBRE_CARRERA($R_id_carrera);	
				
				if(DEBUG){ echo"$R_codigo - $R_ramo<br>";}
				
				
				$tabla2.='<tr>
							<td>'.$aux.'</td>
							<td>'.$R_semestre.'</td>
							<td>'.$R_year.'</td>
							<td>'.utf8_decode($R_carrera).'</td>
							<td>'.$R_jornada.'</td>
							<td>'.$R_grupo.'</td>
							<td>'.$R_nivel.'</td>
							<td>'.utf8_decode($R_ramo).'</td>
							<td>'.number_format($R_valor_hora,0,",",".").'</td>
							<td>'.$R_numero_horas.'</td>
							<td>'.number_format($R_total,0,",",".").'</td>
						</tr>';

			}
			$tabla2.='<tr>
							<td>Total</td>
							<td colspan="8"></td>
							<td>'.$SUMA_HORAS.'</td>
							<td>'.number_format($SUMA_TOTAL,0,",",".").'</td>
					  </tr>';
			
			

		}
		else
		{
			if(DEBUG){ echo"Sin Ramos Tomados En el $semestre Semestre - $year<br>";}
			$tabla2.='<tr><td>Sin Asignaciones registradas para este docente</td></tr>';
		}
	
	
	$tabla2.='</tbody></table>';	
	$sql->free();
	$conexion_mysqli->close();
	
	if(!DEBUG)
		{
			header('Content-type: application/vnd.ms-excel');
			header("Content-Disposition: attachment; filename=AsignacionesFullDocente_$id_funcionario.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
	
	echo $tabla;
	echo $tabla2;
	echo"generado el ".date("d-m-Y H:i:s");
}
else
{}
?>