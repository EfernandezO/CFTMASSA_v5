<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(300);
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_comprobar_egresados_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//	
	$tiempo_inicio_script = microtime(true);

	if(DEBUG){}
	else
	{
		header('Content-type: application/vnd.ms-excel');
			header("Content-Disposition: attachment; filename=egresado_titulados.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
	}
if($_GET)
{
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	include("../../../funciones/VX.php");
	
	$sede=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["sede"]));
	$id_carrera=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["id_carrera"]));
	$year_egreso_consulta=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["year_egreso_consulta"]));
	$situacion=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["situacion"]));
	$year_ingreso_consulta=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["year_ingreso"]));
	$continuar=true;
}
else{ $continuar=false;}

if($continuar)
{

echo'<table width="100%" align="center" cellpadding="0" cellspacing="0" border="1" id="example">
<thead>
	<tr>
		<th colspan="13" bgcolor="#CCCC00">Titulados / Egresados <br>Filtro Sede: '.$sede.' id_carrera: '.$id_carrera.' Year ingreso: '.$year_ingreso_consulta.' Year Egreso: '.$year_egreso_consulta.'</th>
	</tr>
	<tr>	
	<th>N.</th>
	 <th>Sede</th>
    <th>Carrera</th>
    <th>Rut</th>
    <th>Nombre</th>
    <th>Apellido P</th>
    <th>Apellido M</th>
    <th>Fono</th>
    <th>Email</th>
	<th>Year Ingreso</th>
    <th>Year egreso</th>
    <th>Situacion</th>
    <th>Ciudad</th>
	<th>Direccion</th>
	<th>Practica Aprobada</th>
	<th>Examen titulo Aprobado</th>
	
	</tr>
</thead>
<tbody>';

if(DEBUG){ var_dump($_GET);}	
	$nota_aprobacion=4;
	
	
	if(DEBUG){ echo"Sede: $sede <br> id_carrera: $id_carrera <br> year_egreso_consulta: $year_egreso_consulta<br> Situacion: $situacion<br>";}
	
	//--------------------------------------//
	$evento="Revisa Informe Alumnos Egresados/Titulados(XLS) sede: $sede id_carrera: $id_carrera year_egreso_consulta: $year_egreso_consulta situacion: $situacion";
	REGISTRA_EVENTO($evento);
	//-----------------------------------//
	if($year_ingreso_consulta=="0"){ $condicion_year_ingreso="";}
	else{ $condicion_year_ingreso="AND ingreso='$year_ingreso_consulta'";}
	
	if($sede=="0"){ $condicion_sede="";}
	else{ $condicion_sede="AND sede='$sede'";}
	
	if($id_carrera=="0"){ $condicion_carrera="";}
	else{ $condicion_carrera=" AND id_carrera='$id_carrera'";}

	switch($situacion)
	{
		case"todos":
				$condicion_situacion="'T', 'EG'";
			break;
			default:
				$condicion_situacion="'$situacion'";
				break;
	}

	$cons="SELECT * FROM alumno WHERE situacion IN($condicion_situacion) $condicion_year_ingreso $condicion_sede $condicion_carrera ORDER by sede,id_carrera, id_carrera, apellido_P, apellido_M";
	if(DEBUG){echo"<br>--> <b>$cons </b><br>";}
	$sqli=$conexion_mysqli->query($cons)or die("Alumnos ".$conexion_mysqli->error);
	$num_reg=$sqli->num_rows;
	if(DEBUG){echo"<br>Num registros: $num_reg<br>";}
	if($num_reg>0)
	{
		$contador=0;
		$contador_global=0;
		$mostrar_alumno=false;
		$array_condicion=array();
		while($D=$sqli->fetch_assoc())
		{
			$contador_global++;
			$A_id=$D["id"];
			$A_id_carrera=$D["id_carrera"];
			$A_ingreso=$D["ingreso"];
			
			
			//revision de proceso de titulacion para determinar si aprobo practica y examen segun sus notas
			//-------------------------------------------------------------------//
			$notaInformePractica=0;
			$notaEvaluacionEmpresa=0;
			$notaSupervisionPractica=0;
			$notaExamenTitulo=0;
			
			$cons_pp="SELECT * FROM proceso_titulacion WHERE id_alumno='$A_id' AND id_carrera='$A_id_carrera' AND yearIngresoCarrera='$A_ingreso'";
			 if(DEBUG){ echo"-> $cons_pp<br>";}
			
			 $sql_pp=$conexion_mysqli->query($cons_pp)or die($conexion_mysqli->error);
			 $num_regpp=$sql_pp->num_rows;
		
			 if($num_regpp>0)
			 {
				$DPP=$sql_pp->fetch_assoc();
					$notaInformePractica=$DPP["notaInformePractica"];
					$notaEvaluacionEmpresa=$DPP["notaEvaluacionEmpresa"];
					$notaSupervisionPractica=$DPP["notaSupervisionPractica"];
					
					$notaExamenTitulo=$DPP["notaExamen"];
			 }
			 $sql_pp->free();
			 
			$notaFinalPractica=$notaInformePractica*0.3+$notaEvaluacionEmpresa*0.4+$notaSupervisionPractica*0.3;
			
			$aproboPractica="no";
			$aproboExamen="no";
			
			if($notaExamenTitulo>=4){$aproboExamen="si";}
			if($notaFinalPractica>=4){$aproboPractica="si";}
			
			
			
			
			$A_rut=$D["rut"];
			$A_nombre=$D["nombre"];
			$A_apellido_P=$D["apellido_P"];
			$A_apellido_M=$D["apellido_M"];
			
			$A_nivel=$D["nivel"];
			$A_jornada=$D["jornada"];
			$A_grupo=$D["grupo"];
			$A_sede=$D["sede"];
			$A_carrera=$D["carrera"];
			$A_fono=$D["fono"];
			$A_fono_apoderado=$D["fonoa"];
			$A_email=$D["email"];
			$A_situacion_actual=$D["situacion"];
			$A_ciudad=$D["ciudad"];
			$A_direccion=$D["direccion"];
		
			
			
			$cons_N="SELECT MAX(ano) FROM notas WHERE id_alumno='$A_id' AND id_carrera='$A_id_carrera'";
			if(DEBUG){ echo"-->$cons_N<br>";}
			$sqli_N=$conexion_mysqli->query($cons_N)or die($conexion_mysqli->error);
			$N=$sqli_N->fetch_row();
				$N_year_egreso=$N[0];
			$sqli_N->free();	
			if(DEBUG){ echo"-->Year egreso: $N_year_egreso<br>";}
			
			
			if($year_egreso_consulta!="0")
			{
				if($year_egreso_consulta==$N_year_egreso){ $mostrar_alumno=true; if(DEBUG){ echo"filtrar X year egreso OK<br>";}}
				else{$mostrar_alumno=false; if(DEBUG){ echo"filtrar X year egreso ERROR<br>";}}
			}
			else{ $mostrar_alumno=true; if(DEBUG){ echo"No filtrar X year egreso<br>";}}
			
			if($mostrar_alumno)
			{
				$contador++;
				if(isset($array_condicion[$A_situacion_actual])){ $array_condicion[$A_situacion_actual]+=1;}
				else{ $array_condicion[$A_situacion_actual]=1;}
				$color_carrera=COLOR_CARRERA($A_id_carrera);
				echo'<tr height="30">
						<td>'.$contador.'</td>
						<td>'.$A_sede.'</td>
						<td bgcolor="'.$color_carrera.'">'.utf8_decode($A_carrera).'</td>
						<td>'.$A_rut.'</td>
						<td>'.utf8_decode($A_nombre).'</td>
						<td>'.utf8_decode($A_apellido_P).'</td>
						<td>'.utf8_decode($A_apellido_M).'</td>
						<td>'.$A_fono.'- '.$A_fono_apoderado.'</td>
						<td>'.$A_email.'</td>
						<td align="center">'.$A_ingreso.'</td>
						<td align="center">'.$N_year_egreso.'</td>
						<td align="center">'.$A_situacion_actual.'</td>
						<td align="center">'.utf8_decode($A_ciudad).'</td>
						<td align="center">'.utf8_decode($A_direccion).'</td>
						<td align="center">'.$aproboPractica.'</td>
						<td align="center">'.$aproboExamen.'</td>
					</tr>';
			}
			
			
		}
		$MSJ="";
		if(isset($array_condicion["EG"])){$MSJ.=$array_condicion["EG"]." Egresados / ";}
		if(isset($array_condicion["T"])){$MSJ.=$array_condicion["T"]." Titulados";}
		
		echo $MSJ;
	}
	else
	{/*Sin registros*/}
	$sqli->free();
	@mysql_close($conexion);
	$conexion_mysqli->close();
}
else
{ header("location: index.php");}

$tiempo_fin_script = microtime(true);

echo'</tbody>
</table><br />';
echo "<br>Tiempo empleado: " . round($tiempo_fin_script - $tiempo_inicio_script,4)." Segundos";
?>