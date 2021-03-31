<?php

//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("seguimiento_semestral_de_alumnos_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
require("../../../../funciones/conexion_v2.php");
require("../../../../funciones/funciones_sistema.php");
//Periodo
$year_actual=date("Y");
$mes_actual=date("m");
$mostrar_detalle=true;//muestra listalle de alumno y situacion x año

if($mes_actual>=8)
{ $semestre_actual=2;}
else
{ $semestre_actual=1;}

if($_GET)
{
	if(DEBUG){ echo"Hay Get<br>";}
	$year_consulta=strip_tags(mysqli_real_escape_string($conexion_mysqli, $_GET["year"]));
	//$sede_consulta=strip_tags(mysqli_real_escape_string($conexion_mysqli, $_GET["sede"]));
}
else
{
	if(DEBUG){ echo"NO Hay Get<br>";}
	$year_consulta=$year_actual;
	$sede_consulta=0;
}
$numYear=($year_actual-$year_consulta)+1;
$numSemestres=$numYear*2;
//-----------------------------------------------//
require("../../../../funciones/VX.php");
$evento="Cohorte Institucional year $year_consulta";
REGISTRA_EVENTO($evento);
//--------------------------------------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>Seguimiento Semestral</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:1;
	left: 425px;
	top: 103px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:58px;
	z-index:2;
	left: 5%;
	top: 90px;
}
#apDiv3 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:3;
	left: 5%;
	top: 233px;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Seguimiento Semestral dentro de un año</h1>
<div id="link"><br /><a href="../../../Alumnos/menualumnos.php" class="button">Volver a Menu</a></div>
<div id="apDiv2">
  <form action="seguimiento_Tipo2.php" method="get" id="frm">
    <table width="100%" border="1">
      <thead>
        <tr>
          <th colspan="2">Parametros Busqueda</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>A&ntilde;o Ingreso</td>
          <td><?php echo CAMPO_SELECCION("year", "year", $year_consulta,false);?></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><a href="#" class="button_R" onclick="javascript:document.getElementById('frm').submit();">Consultar</a></td>
        </tr>
      </tbody>
    </table>
  </form>
</div>

<div id="apDiv3">
<table border="1">
<thead>
	<tr>
    	<th colspan="20">Seguimiento Alumnos year <?php echo $year_consulta;?> <br />
    	  Considera la existencia de contrato en cada semestre</th>
    </tr>
    <tr>
    	<td>N Global</td>
        <td>Sede</td>
    	<td>Carrera</td>
        <td>Jornada</td>
        <td>YearIngreso</td>
        <td>idAlumno</td>
        <td>Rut</td>
        <td>n. periodo</td>
        <td>semestre</td>
        <td>year</td>
        <td>Situacion</td>
    </tr>
</thead>
<tbody>
<?php
	$ARRAY_DATOS_ALUMNO=array();
	require("../../../../funciones/class_ALUMNO.php");
	
	
	$validador=md5("GDXT".date("d-m-Y"));

	$condicion_sede="";
	
	$cons_MAIN="SELECT DISTINCT(id_alumno) FROM contratos2 INNER JOIN alumno ON contratos2.id_alumno=alumno.id WHERE contratos2.yearIngresoCarrera='$year_consulta' $condicion_sede ORDER BY contratos2.id_carrera, contratos2.jornada, alumno.apellido_P, alumno.apellido_M";
	
	$sqli_MAIN=$conexion_mysqli->query($cons_MAIN)or die($conexion_mysqli->error);
	$num_registros=$sqli_MAIN->num_rows;
	if(DEBUG){ echo"Consulta GLobal: $cons_MAIN<br>num reg: $num_registros<br>";}
	
	
	$arrayEgresados=array();
	$arrayTitulados=array();
	$arrayRetirados=array();
	
	if($num_registros>0)
	{
		$aux=0;
		while($CA=$sqli_MAIN->fetch_row())
		{
			$aux++;
			$id_alumno=$CA[0];
			if(DEBUG){ echo"<strong>-> id_alumno: $id_alumno</strong><br>";}
			
			$DATOS=array();
			$i=0;
			
			$ALUMNO=new ALUMNO($id_alumno);
			//$ALUMNO->SetDebug(DEBUG);
			
			
			$primeraVuelta=true;
			for($y=$year_consulta;$y<=$year_actual;$y++){
				for($s=1;$s<=2;$s++){
					if(DEBUG){ echo"--->periodo: [ $s - $y] <br>";}	
					$ALUMNO->IR_A_PERIODO($s,$y);
					$situacionAlumnoPeriodo=$ALUMNO->getSituacionAlumnoPeriodo();
				
					$idCarreraPeriodo=$ALUMNO->getIdCarreraPeriodo();
					$sedeAlumno=$ALUMNO->getSedeAlumnoPeriodo();
					
					if($primeraVuelta){$primeraVuelta=false; $idCarreraPeriodo_OLD=$idCarreraPeriodo;	$yearIngresoCarreraPeriodo=$ALUMNO->getYearIngresoCarreraPeriodo();}
					
					
					if(($idCarreraPeriodo==$idCarreraPeriodo_OLD)and($yearIngresoCarreraPeriodo==$year_consulta)){}
					else{
					$situacionAlumnoPeriodo="NI/OC"; if(DEBUG){ echo" (OTRA CARRERA) Situacion: $situacionAlumnoPeriodo yearIngreso: $yearIngresoCarreraPeriodo idCarrera:$idCarreraPeriodo<br>";}
					}
					
					$DATOS[$i]["semestre"]=$s;
					$DATOS[$i]["year"]=$y;
					$DATOS[$i]["jornada"]=$ALUMNO->getJornadaPeriodo();
					$DATOS[$i]["situacion"]=$situacionAlumnoPeriodo;
				
				
				$idCarreraPeriodo_OLD=$idCarreraPeriodo;
				$i+=1;
				}
			}//fin for
			
			$url_destino='../../../buscador_alumno_BETA/enrutador.php?validador='.$validador.'&id_alumno='.$id_alumno;
	
					
				
					for($j=0;$j<$numSemestres;$j++){	
						if(isset($DATOS[$j]["situacion"]))
						{
							$aux_situacionPeriodo=$DATOS[$j]["situacion"];
							$aux_semestre=$DATOS[$j]["semestre"];
							$aux_year=$DATOS[$j]["year"];
							$aux_jornada=$DATOS[$j]["jornada"];
							
							switch($aux_situacionPeriodo){
								case"V":
									$colorSituacion='#00FF00';
									break;
								case"EG":
									$colorSituacion='#3498db';
									break;
								case"T":
									$colorSituacion='#0000FF';
									break;		
								case"R":
									$colorSituacion='#FF0000';
									break;	
								default:
									$colorSituacion='';	
							}
									
						}
						else{ $colorSituacion=""; $situacionPeriodo="";}
						
					echo'<tr>
					<td>'.$aux.'</td>
					<td>'.$sedeAlumno.'</td>
					<td>'.NOMBRE_CARRERA($idCarreraPeriodo).'</td>
					<td>'.$aux_jornada.'</td>
					<td>'.$yearIngresoCarreraPeriodo.'</td>
					<td>'.$id_alumno.'</td>
					<td><a href="'.$url_destino.'" title="Revisar este Alumno" target="_blank">'.$ALUMNO->getRut().'</a></td>
					<td>'.($j+1).'</td>
					<td>'.$aux_semestre.'</td>
					<td>'.$aux_year.'</td>
					<td bgcolor="'.$colorSituacion.'" align="center">'.$aux_situacionPeriodo.' </td>
					</tr>';
						
					}
					
							
		}
	}
	$sqli_MAIN->free();
	$conexion_mysqli->close();
	?>
 </tbody>
</table>    
</div>
</body>
</html>