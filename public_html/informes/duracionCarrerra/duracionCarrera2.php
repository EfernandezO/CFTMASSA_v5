<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//set_time_limit(6000);
//ini_set('memory_limit', '-1');
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_duracionCarrera_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Administrador - informe</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<body onload="MM_preloadImages('../../BAses/Images/BarraProgreso.gif')">
<h1 id="banner">Administrador - Duración Carrera</h1>
<div id="link"><br />
<a href="../../Alumnos/menualumnos.php" class="button">Volver al menu Principal </a></div>
<?php
if($_POST)
{

	$year=$_POST["year"];
	$sede=$_POST["fsede"];
	
	
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	require("../../../funciones/VX.php");
	require("../../../funciones/class_ALUMNO.php");
	
	$evento="informe duracion Carrera, sede $sede Year: $year";
	REGISTRA_EVENTO($evento);
	
	
	$html="";
	if(DEBUG){echo"año: $year sede: $sede";}
	//-------------------------------------------------------------------------------//
	
	$condicion_year="";
	if($year>0){$condicion_year="AND ingreso='$year'";}
	//consulto por alumnos titulados o egresados
	$cons_MAIN="SELECT * FROM alumno WHERE situacion IN('EG', 'T') AND sede='$sede' $condicion_year";
	if(DEBUG){echo"$cons_MAIN<br>";}
	
	$sqli=$conexion_mysqli->query($cons_MAIN)or die($conexion_mysqli->error);
	$num_registros=$sqli->num_rows;
	
	$ARRAY_RESULTADOS=array();
	
	//recorro alumnos de consulta, por cada uno genero ciclos de semestre/año consultando el estado del alumno en ese periodo
	//si alumno no es R ni NN se contabiliza el semestre, si es T o EG termina el ciclo de forma anticipada.
	
	if($num_registros>0)
	{
		if(DEBUG){echo"<strong>Periodo: $sede - desde año: $year</strong><br>$num_registros Registros<br>";}
		while($TM=$sqli->fetch_assoc())
		{
			$id_alumno=$TM["id"];
			$id_carrera_alumno=$TM["id_carrera"];
			$jornada_alumno=$TM["jornada"];//actualizado toma de ramos
			
			
			$semestresDuracionAlumno=0;	
			$detener=false;
			
			if($year>0){$yearInicio=$year;}
			else{$yearInicio=2011;}
			
			if(DEBUG){ echo"id_alumno: $id_alumno id_carrera: $id_carrera_alumno<br>";}
			
			for($auxYear=$yearInicio;$auxYear<=date("Y");$auxYear++){
				for($auxSemestre=1;$auxSemestre<=2;$auxSemestre++){
					
					$ALUMNO=new ALUMNO($id_alumno);
					//$ALUMNO->SetDebug(DEBUG);
					$ALUMNO->IR_A_PERIODO($auxSemestre, $auxYear);
					$estadoAlumnoPeriodo=$ALUMNO->getSituacionAlumnoPeriodo();
					if(DEBUG){ echo"->Periodo[$auxSemestre - $auxYear] situacion: $estadoAlumnoPeriodo<br>";}
					$sumar=true;
					
					if(($estadoAlumnoPeriodo=="R")or($estadoAlumnoPeriodo=="NN")){$sumar=false;}
					if(($estadoAlumnoPeriodo=="EG")or($estadoAlumnoPeriodo=="T")){$detener=true;}
					if($sumar){$semestresDuracionAlumno+=1;}
					
					if($detener){break;}
				}
				if($detener){break;}
			}
			if(DEBUG){ echo"<strong>=>Total Semestre $semestresDuracionAlumno</strong><br><br>";}
			
			//filtro ya que encuentro casos con menos de 4 semestres
			//27/12/2018
			if($semestresDuracionAlumno>0){
				if(isset($ARRAY_RESULTADOS[$id_carrera_alumno]["cantidad"])){$ARRAY_RESULTADOS[$id_carrera_alumno]["cantidad"]+=1;}
				else{$ARRAY_RESULTADOS[$id_carrera_alumno]["cantidad"]=1;}
				
				if(isset($ARRAY_RESULTADOS[$id_carrera_alumno]["acumulado"])){$ARRAY_RESULTADOS[$id_carrera_alumno]["acumulado"]+=$semestresDuracionAlumno;}
				else{$ARRAY_RESULTADOS[$id_carrera_alumno]["acumulado"]=$semestresDuracionAlumno;}
			}
			
		}
		
	}
	else
	{
		echo"Sin Registros... :(<br>";
		
	}
	
	
	//-------------------------------------------------------------------------------------------------------------------------------//
	
	//genero tabla de valores con los resultados obtenidos
	
	$tabla='<table width="55%" align="center">
			<thead>
			<tr>
				<th colspan="3">RESULTADOS <br> Year: '.$year.'</th>
			</tr>
			</thead>
			<tr>
				<td>Carrera</td>
				<td>Duracion Promedio en Semestres</td>
				<td>Cantidad Alumnos (EG, T)</td>
			</tr><tbody>';
	
	$SUMA=0;
	foreach($ARRAY_RESULTADOS as $aux_id_carrera => $array_1){
		$aux_cantidad=$array_1["cantidad"];
		$SUMA+=$aux_cantidad;
		$aux_acumulado=$array_1["acumulado"];
		$auxPromedio=($aux_acumulado/$aux_cantidad);
		
		$tabla.='<tr>
				<td bgcolor="'.COLOR_CARRERA($aux_id_carrera).'">'.$aux_id_carrera.' '.NOMBRE_CARRERA($aux_id_carrera).'</td>
				<td>'.$auxPromedio.' -> '.round($auxPromedio).'</td>
				<td>'.$aux_cantidad.'</td>
			</tr>';
		
	}
	$tabla.='</tbody>
	<tr>
	<td colspan="3">Total Alumnos Consultados : '.$num_registros.' Total alumnos Utilizados:  '.$SUMA.'</td>
	</tr>
	</table>';
	
	echo $tabla;
	

	$sqli->free();
	$conexion_mysqli->close();
	@mysql_close($conexion);
}	
?>
</body>
</html>