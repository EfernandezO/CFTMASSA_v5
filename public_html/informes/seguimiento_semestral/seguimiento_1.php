<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("seguimiento_semestral_de_alumnos_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//--------------FIN CLASS_okalis---------------//
set_time_limit(6000);
ini_set('memory_limit', '-1');
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
//---------------------------------------------//
require("../../../funciones/conexion_v2.php");
require("../../../funciones/funciones_sistema.php");
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
	$sede_consulta=strip_tags(mysqli_real_escape_string($conexion_mysqli, $_GET["sede"]));
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
require("../../../funciones/VX.php");
$evento="Cohorte Institucional year $year_consulta";
REGISTRA_EVENTO($evento);
//--------------------------------------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Seguimiento Semestral</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:1;
	left: 425px;
	top: 103px;
}
#apDiv2 {position:absolute;
	width:40%;
	height:58px;
	z-index:2;
	left: 30%;
	top: 90px;
}
#apDiv3 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:3;
	left: 5%;
	top: 266px;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Seguimiento Semestral dentro de un año</h1>
<div id="link"><br /><a href="../../Alumnos/menualumnos.php" class="button">Volver a Menu</a></div>
<div id="apDiv2">
  <form action="seguimiento_1.php" method="get" id="frm">
    <table width="100%" border="1">
      <thead>
        <tr>
          <th colspan="2">Parametros Busqueda</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Sede</td>
          <td><?php echo CAMPO_SELECCION("sede", "sede",$sede_consulta,true);?></td>
        </tr>
        <tr>
          <td>a&ntilde;o</td>
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
    	<th colspan="20">Seguimiento Alumnos year <?php echo $year_consulta;?> Sede: <?php echo $sede_consulta;?><br />
    	  Considera la existencia de contrato en cada semestre</th>
    </tr>
    <tr>
    	<td>N Global</td>
        <td>Sede</td>
    	<td>Carrera</td>
        <td>Rut</td>
        <td>Nombre</td>
        <td>Apellido_P</td>
        <td>Apellido_M</td>
    
        <?php
		for($x=$year_consulta;$x<=$year_actual;$x++){
			echo'<td>1 Semestre '.$x.'</td>
				 <td>2 Semestre '.$x.'</td>';
		}
        ?>
        <td>Semestres hasta Egreso</td>
        <td>Semestre hasta Titulo</td>
    </tr>
</thead>
<tbody>
<?php
	$ARRAY_DATOS_ALUMNO=array();
	require("../../../funciones/class_ALUMNO.php");
	
	
	$validador=md5("GDXT".date("d-m-Y"));
	
	
	if($sede_consulta!="0"){$condicion_sede="AND contratos2.sede='$sede_consulta'";}
	else{ $condicion_sede="";}
	
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
				if(DEBUG){ echo"--->periodo: [$y] <br>";}	
				$ALUMNO->IR_A_PERIODO(1,$y);
				$situacionAlumnoPeriodo=$ALUMNO->getSituacionAlumnoPeriodo();
				$yearIngresoCarreraPeriodo=$ALUMNO->getYearIngresoCarreraPeriodo();
				$idCarreraPeriodo=$ALUMNO->getIdCarreraPeriodo();
				if($primeraVuelta){
					$primeraVuelta=false;
					$sedeAlumno=$ALUMNO->getSedeAlumnoPeriodo();
					$idCarreraAlumno=$idCarreraPeriodo;
					$yearIngresoCarreraAlumno=$yearIngresoCarreraPeriodo;
				}
				if(($idCarreraAlumno==$idCarreraPeriodo)and($yearIngresoCarreraPeriodo==$yearIngresoCarreraAlumno)){
					$DATOS[$i]["situacion"]=$situacionAlumnoPeriodo;
					if(DEBUG){ echo"->SEMESTRE 1: Situacion: $situacionAlumnoPeriodo yearIngreso: $yearIngresoCarreraPeriodo idCarrera:$idCarreraPeriodo<br>";}
				}
				else{
					$DATOS[$i]["situacion"]="";
					if(DEBUG){ echo"->SEMESTRE 1: (OTRA CARRERA) Situacion: $situacionAlumnoPeriodo yearIngreso: $yearIngresoCarreraPeriodo idCarrera:$idCarreraPeriodo<br>";}
					}
				
				$ALUMNO->IR_A_PERIODO(2,$y);
				$situacionAlumnoPeriodo=$ALUMNO->getSituacionAlumnoPeriodo();
				$yearIngresoCarreraPeriodo=$ALUMNO->getYearIngresoCarreraPeriodo();
				$idCarreraPeriodo=$ALUMNO->getIdCarreraPeriodo();
				if(($idCarreraAlumno==$idCarreraPeriodo)and($yearIngresoCarreraPeriodo==$yearIngresoCarreraAlumno)){
					$DATOS[$i+1]["situacion"]=$situacionAlumnoPeriodo;
					if(DEBUG){ echo"->SEMESTRE 2: Situacion: $situacionAlumnoPeriodo yearIngreso: $yearIngresoCarreraPeriodo idCarrera:$idCarreraPeriodo<br>";}
				}
				else{
					$DATOS[$i+1]["situacion"]="";
					if(DEBUG){ echo"->SEMESTRE 2: (OTRA CARRERA) Situacion: $situacionAlumnoPeriodo yearIngreso: $yearIngresoCarreraPeriodo idCarrera:$idCarreraPeriodo<br>";}
					}
				
				$i+=2;
			}//fin for
			
			$url_destino='../../buscador_alumno_BETA/enrutador.php?validador='.$validador.'&id_alumno='.$id_alumno;
	
			echo'<tr>
					<td>'.$aux.'</td>
					<td>'.$sedeAlumno.'</td>
					<td>'.$idCarreraAlumno.' '.NOMBRE_CARRERA($idCarreraAlumno).'</td>
					<td><a href="'.$url_destino.'" title="Revisar este Alumno" target="_blank">'.$ALUMNO->getRut().'</a></td>
					<td>'.$ALUMNO->getNombre().'</td>
					<td>'.$ALUMNO->getApellido_P().'</td>
					<td>'.$ALUMNO->getApellido_M().'</td>';
					
					$soloUnaVez=true;
					for($j=0;$j<$numSemestres;$j++){	
						if(isset($DATOS[$j]["situacion"]))
						{
							$situacionPeriodo=$DATOS[$j]["situacion"];
							
							switch($situacionPeriodo){
								case"V":
									$colorSituacion='#00FF00';
									break;
								case"EG":
									$colorSituacion='#3498db';
									if(!isset($arrayEgresados[$id_alumno])){$arrayEgresados[$id_alumno]=($j+1);$arrayEgresadosV2[$id_alumno]=($j+1);}
									break;
								case"T":
									$colorSituacion='#0000FF';
									if(!isset($arrayTitulados[$id_alumno])){$arrayTitulados[$id_alumno]=($j+1);}
									if(isset($arrayEgresados[$id_alumno])){unset($arrayEgresados[$id_alumno]);}
									break;		
								case"R":
									$colorSituacion='#FF0000';
									if(!isset($arrayRetirados[$id_alumno])){$arrayRetirados[$id_alumno]=true;}
									break;	
								default:
									$colorSituacion='';	
							}
									
						}
						else{ $colorSituacion=""; $situacionPeriodo="";}
						
						echo'<td bgcolor="'.$colorSituacion.'" align="center">'.$situacionPeriodo.' </td>';
						
					}
					
					$semestresEgreso=0;
					$semestresTitulo=0;
					if(isset($arrayEgresadosV2[$id_alumno])){$semestresEgreso=$arrayEgresadosV2[$id_alumno];}
					if(isset($arrayTitulados[$id_alumno])){$semestresTitulo=$arrayTitulados[$id_alumno];}
					if($semestresEgreso>$semestresTitulo){$semestreX=$semestresEgreso;}
					else{ $semestreX=$semestresTitulo;}
					
				echo'<td>'.$semestresEgreso.'</td>
					 <td>'.$semestresTitulo.'</td>	
				</tr>';
			
		}
	}
	$sqli_MAIN->free();
	$conexion_mysqli->close();
	?>
    <tr>
        <td colspan="8"><STRONG>TOTAL RETIRADOS</STRONG></td>
        <td><?php echo count($arrayRetirados);?></td>
    </tr>
     <tr>
        <td colspan="8"><STRONG>TOTAL EGRESADOS</STRONG></td>
        <td><?php echo count($arrayEgresados);?></td>
    </tr>
     <tr>
        <td colspan="8"><STRONG>TOTAL TITULADOS</STRONG></td>
        <td><?php echo count($arrayTitulados);?></td>
    </tr>
 </tbody>
</table>    
</div>
</body>
</html>