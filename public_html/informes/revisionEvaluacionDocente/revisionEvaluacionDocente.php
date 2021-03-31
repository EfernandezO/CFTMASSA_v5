<?php //--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumno->Registro Actividades V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//	
$alumno_seleccionado=false;
if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
{ 
	if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
	{ $alumno_seleccionado=true;}
}

if($alumno_seleccionado)
{
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	$sede_alumno=$_SESSION["SELECTOR_ALUMNO"]["sede"];
	$nivel_alumno=$_SESSION["SELECTOR_ALUMNO"]["nivel"];
	$yearIngresoCarrera=$_SESSION["SELECTOR_ALUMNO"]["yearIngresoCarrera"];
}
else
{ header("location: ../../buscador_alumno_BETA/HALL/index.php");} if(DEBUG){ echo"Alumno Nivel: $nivel_alumno<br>";}
////-----------------------------------
if($nivel_alumno>=1)
{
  require("../../../funciones/conexion_v2.php");
   $id_encuesta_JEFE_CARRERA=15;///id de encuesta de evaluacion jefes de carrera
   $id_encuesta_EVALUCION_DOCENTE=6;//id de encuesta utilizada para la evaluacion docente
   
   
  

//-------------------------------------------------------------------------/
$ARRAY_ENCUESTAS=array();
//ENCUESTA EVALUCION DOCENTE
for($y=$yearIngresoCarrera;$y<=date("Y");$y++){
	for($s=1;$s<=2;$s++){
		
	 //busco numero de ramos que tomo en periodo a consultar
   $cons_TR="SELECT COUNT(distinct(id_funcionario)) FROM toma_ramos INNER JOIN toma_ramo_docente ON toma_ramos.cod_asignatura=toma_ramo_docente.cod_asignatura AND toma_ramos.year=toma_ramo_docente.year AND toma_ramos.semestre=toma_ramo_docente.semestre AND toma_ramos.id_carrera=toma_ramo_docente.id_carrera WHERE toma_ramos.semestre='$s' AND toma_ramos.year='$y' AND toma_ramos.id_alumno='$id_alumno' AND toma_ramos.id_carrera='$id_carrera' AND toma_ramos.yearIngresoCarrera='$yearIngresoCarrera' AND toma_ramo_docente.sede='$sede_alumno'";
   if(DEBUG){ echo"TOMA RAMOS---->$cons_TR<br>";}
   $sqli_TR=$conexion_mysqli->query($cons_TR)or die($conexion_mysqli->error);
   $TR=$sqli_TR->fetch_row();
   $num_TR=$TR[0];
   if(empty($num_TR)){$num_TR=0;}
   $sqli_TR->free();
   if(DEBUG){ echo"Numero de ramos tomados en el periodo[$s - $y] = $num_TR<br>";}
   
   
   if($num_TR>0){
	
	 $conse="SELECT * FROM encuestas_resultados WHERE id_encuesta='$id_encuesta_JEFE_CARRERA' AND id_usuario='$id_alumno' AND tipo_usuario='alumno'  AND  semestre_evaluar='$s' AND year_evaluar='$y'";
   $sqli_e1=$conexion_mysqli->query($conse)or die($conexion_mysqli->error);
	if(DEBUG){ echo"JEFE CARRERA--->$conse<br><br>";}
	$fechaGeneracion=NULL;
	$estado="sin contestar";
	$numContestadas=0;
	$ARRAY_ENCUESTAS["JC"][$sede_alumno][$y][$s][$id_carrera]["fecha_generacion"]=$fechaGeneracion;
	$ARRAY_ENCUESTAS["JC"][$sede_alumno][$y][$s][$id_carrera]["estado"]=$estado;
	$ARRAY_ENCUESTAS["JC"][$sede_alumno][$y][$s][$id_carrera]["total"]=1;
	$ARRAY_ENCUESTAS["JC"][$sede_alumno][$y][$s][$id_carrera]["contestadas"]=$numContestadas;
	while($E=$sqli_e1->fetch_assoc()){
		
		$id_carreraEvaluar=$E["id_carrera_evaluar"];
		$fechaGeneracion=$E["fecha_generacion"];
		$estado="Evaluacion de Jefe de Carrera Contestada";
		$numContestadas=1;
		$ARRAY_ENCUESTAS["JC"][$sede_alumno][$y][$s][$id_carrera]["fecha_generacion"]=$fechaGeneracion;
		$ARRAY_ENCUESTAS["JC"][$sede_alumno][$y][$s][$id_carrera]["estado"]=$estado;
		$ARRAY_ENCUESTAS["JC"][$sede_alumno][$y][$s][$id_carrera]["total"]=1;
		$ARRAY_ENCUESTAS["JC"][$sede_alumno][$y][$s][$id_carrera]["contestadas"]=$numContestadas;
	}
	
	
	$sqli_e1->free();
	
		
		
	$fechaGeneracion=NULL;
	$estado="";
  
   //reviso cuantas evaluaciones docente realizo
   $cons_ED="SELECT COUNT(DISTINCT(id_usuario_evaluar)), fecha_generacion FROM encuestas_resultados WHERE semestre_evaluar='$s' AND year_evaluar='$y' AND id_usuario='$id_alumno' AND tipo_usuario='alumno' AND id_encuesta='$id_encuesta_EVALUCION_DOCENTE'";
   if(DEBUG){ echo"DOCENTE====>$cons_ED<br><br>";}
   $sqli_evd=$conexion_mysqli->query($cons_ED)or die($conexion_mysqli->error);
   $EV=$sqli_evd->fetch_row();
   $num_evaluaciones_docentes=$EV[0];
   $fechaGeneracion=$EV[1];
   if(empty($num_evaluaciones_docentes)){$num_evaluaciones_docentes=0;}
   
   
   if($num_evaluaciones_docentes==0)
   {
	  $estado="sin contestar";
		$hay_advertencias=true;
   }
   elseif($num_evaluaciones_docentes<$num_TR)
   {
		$hay_advertencias=true;
		$estado="Evaluacion Docente Incompleta<br>"; 
   }
   else{ $estado="Evaluacion Docente OK";}
   
   if(DEBUG){ echo"Numero de evaluaciones docentes Realizadas: $num_evaluaciones_docentes<br>Estado: $estado<br><br>";}
   	$sqli_evd->free();
	$ARRAY_ENCUESTAS["D"][$sede_alumno][$y][$s][$id_carrera]["fecha_generacion"]=$fechaGeneracion;
	$ARRAY_ENCUESTAS["D"][$sede_alumno][$y][$s][$id_carrera]["estado"]=$estado;
	$ARRAY_ENCUESTAS["D"][$sede_alumno][$y][$s][$id_carrera]["total"]=$num_TR;
	$ARRAY_ENCUESTAS["D"][$sede_alumno][$y][$s][$id_carrera]["contestadas"]=$num_evaluaciones_docentes;
   }
   else{if(DEBUG){ echo"Sin Toma de Ramos en el periodo<br><br>";}}
	}
	
	
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<title>Revision de Evaluaciones Docente</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:22px;
	z-index:1;
	left: 5%;
	top: 220px;
}
#apDiv2 {
	position:absolute;
	width:50%;
	height:115px;
	z-index:2;
	left: 5%;
	top: 82px;
}
#apDiv3 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:3;
	left: 49px;
	top: 202px;
}
</style>
</head>

<body>
<h1 id="banner">Estado Encuestas Evalucion Docentes</h1>
<div id="link"><br />
<a href="../../buscador_alumno_BETA/HALL/index.php" class="button">Volver</a><br />
</div>
<div id="apDiv2">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="2">Datos Alumno</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="20%">ID Alumno</td>
      <td width="80%"><?php echo $_SESSION["SELECTOR_ALUMNO"]["id"];?></td>
    </tr>
    <tr>
      <td>Nombre</td>
      <td><?php echo $_SESSION["SELECTOR_ALUMNO"]["nombre"];?></td>
    </tr>
    <tr>
      <td>Apellido</td>
      <td><?php echo $_SESSION["SELECTOR_ALUMNO"]["apellido"];?></td>
    </tr>
    <tr>
      <td>Carrera</td>
      <td><?php echo $_SESSION["SELECTOR_ALUMNO"]["carrera"];?></td>
    </tr>
    </tbody>
  </table>
</div>
<div id="apDiv1">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="9">&nbsp;</th>
    </tr>
    <tr>
      <td>Tipo Encuestas</td>
      <td>Sede</td>
      <td>Año</td>
      <td>Semestre</td>
      <td>Total Encuestas</td>
      <td>Total Contestadas</td>
      <td>Carrera</td>
      <td>Estado</td>
      <td>Fecha generacion</td>
    </tr>
    </thead>
    <tbody>
    <?php
	foreach($ARRAY_ENCUESTAS as $tipo =>$auxArray1){
		foreach($auxArray1 as  $sedex => $auxArray2){
			foreach($auxArray2 as $yx => $auxArray3){
				foreach($auxArray3 as $sx => $auxArray4){
					foreach($auxArray4 as $id_carrerax => $auxArray5){
						$estadoX=$auxArray5["estado"];
						$fechaGeneracionX=$auxArray5["fecha_generacion"];
						$totalx=$auxArray5["total"];
						$contestadasx=$auxArray5["contestadas"];
						
							echo'<tr>
							<td>'.$tipo.'</td>
							<td>'.$sedex.'</td>
							 <td>'.$yx.'</td>
							 <td>'.$sx.'</td>
							 <td>'.$totalx.'</td>
							 <td>'.$contestadasx.'</td>
							 <td>'.$id_carrerax.'</td>
							  <td>'.$estadoX.'</td>
							 <td>'.$fechaGeneracionX.'</td>
							
							 </tr>';
						
					}					
				}
			}
		}
	}
    ?>
    </tbody>
  </table>
</div>
</body>
</html>