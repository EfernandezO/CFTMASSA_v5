<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	//$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("revision_mensual_honorario_Docente");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_GET)
{
	
	$sede=base64_decode($_GET["sede"]);
	$mes=base64_decode($_GET["mes"]);
	//$year=base64_decode($_GET["year"]);
	$year_generacion=base64_decode($_GET["year_generacion"]);
	if(!DEBUG)
		{
			header('Content-type: application/vnd.ms-excel');
			header("Content-Disposition: attachment; filename=Honorario_docente_resumen[".$sede."_".$mes."_".$year_generacion."].xlsx");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
		else
		{var_export($_GET);}
		
	
	
	require("../../../../funciones/conexion_v2.php");
	include("../../../../funciones/funcion.php");
	include("../../../../funciones/VX.php");
	
	$logo="../../../BAses/Images/logoX.jpg";
	$borde=1;
	$letra_1=12;
	$letra_2=10;
	$autor="ACX";
	$titulo="Resumen Honorario Docente";
	$zoom=75;
	
	$ARRAY_MESES=array(1=>"Enero",
						2=>"Febrero",
						3=>"Marzo",
						4=>"Abril",
						5=>"Mayo",
						6=>"Junio",
						7=>"Julio",
						8=>"Agosto",
						9=>"Septiembre",
						10=>"Octubre",
						11=>"Noviembre",
						12=>"Diciembre");
	
	$html_tabla='<table border="1">
				<tr>
					<td colspan="7" align="right">Impresion.:'.fecha().'</td>
				</tr>
				<tr>
					<td colspan="7" align="center" bgcolor="#66CCFF">'.$titulo.'</td>
				</tr>
				<tr>
					<td colspan="7" align="center">'.$sede.' Periodo '.$ARRAY_MESES[$mes].' - '.$year_generacion.'</td>
				</tr>';
	
			
			$html_tabla.='<tr>
							<td>N</td>
							<td>Rut</td>
							<td>Nombre</td>
							<td>Apellido</td>
							<td>Contabilidad</td>
							<td>Estado</td>
							<td>Total</td>
						  </tr>';
	
	$cons_H="SELECT honorario_docente.* FROM honorario_docente INNER JOIN personal ON honorario_docente.id_funcionario=personal.id WHERE honorario_docente.mes_generacion='$mes' AND honorario_docente.year_generacion='$year_generacion' AND honorario_docente.sede='$sede' ORDER by personal.apellido_P, personal.apellido_M";
	$sqli_H=$conexion_mysqli->query($cons_H)or die("Honorario Docente ".$conexion_mysqli->error);
	$num_registros=$sqli_H->num_rows;
	
	$SUMA_TOTAL_HONORARIO=0;
	if($num_registros>0)
	{
		//------------------------------------------------------------------//
		$evento="Revisa Resumen Honorario .xlsx $sede periodo [$mes -$year_generacion]";
		REGISTRA_EVENTO($evento);
		//-----------------------------------------------------------------//
		$contador=0;
		while($H=$sqli_H->fetch_assoc())
		{
			$contador++;
			$H_id=$H["id_honorario"];
			$H_sede=$H["sede"];
			$H_mes=$H["mes_generacion"];
			$H_year=$H["year_generacion"];
			$H_id_funcionario=$H["id_funcionario"];
			$H_total=$H["total"];
			$H_estado=$H["estado"];
			$H_generado_contabilidad=$H["generado_contabilidad"];
			$H_fecha_generacion=$H["fecha_generacion"];
			$H_cod_user=$H["cod_user"];
			if(empty($H_generado_contabilidad)){ $H_generado_contabilidad="pendiente";}
			
			$SUMA_TOTAL_HONORARIO+=$H_total;
			//------------------------------------------------------//
			$cons_A="SELECT * FROM personal WHERE id='$H_id_funcionario' LIMIT 1";
			$sql_A=$conexion_mysqli->query($cons_A);
			$DA=$sql_A->fetch_assoc();
				$D_rut=$DA["rut"];
				$D_nombre=$DA["nombre"];
				$D_apellido=$DA["apellido_P"]." ".$DA["apellido_M"];
			$sql_A->free();
			//------------------------------------------------------//
			
			$html_tabla.='<tr>
							<td>'.$contador.'</td>
							<td>'.$D_rut.'</td>
							<td>'.utf8_decode($D_nombre).'</td>
							<td>'.utf8_decode($D_apellido).'</td>
							<td>'.$H_generado_contabilidad.'</td>
							<td>'.$H_estado.'</td>
							<td>'.$H_total.'</td>
						  </tr>';
		}

		
		$html_tabla.='<tr>
					<td colspan="6">Total</td>
					<td>'.$SUMA_TOTAL_HONORARIO.'</td>
					</tr>
					<tr>
					<td colspan="7">Honorarios Generados el '.fecha_format($H_fecha_generacion).', por el usuario cod['.$H_cod_user.']</td>
				  </tr>';
	}
	else
	{ $html_tabla.='<tr><td>Total</td></tr>';}
	
	$sqli_H->free();	


	$conexion_mysqli->close();
	
	echo $html_tabla;
}
else
{}
?>