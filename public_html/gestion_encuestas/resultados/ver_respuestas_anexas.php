<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Gestion de Encuestas_resultados_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

require("../../../funciones/conexion_v2.php");

$continuar_1=false;
$continuar_2=false;
$continuar_3=false;

if(isset($_GET["id_encuesta"]))
{
	$id_encuesta=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["id_encuesta"]));
	if(is_numeric($id_encuesta)){ $continuar_1=true;}
}

if(isset($_GET["id_pregunta"]))
{
	$id_pregunta=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["id_pregunta"]));
	if(is_numeric($id_pregunta)){ $continuar_2=true;}
}

if(isset($_GET["id_alternativa"]))
{
	$id_alternativa=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["id_alternativa"]));
	if(is_numeric($id_alternativa)){ $continuar_3=true;}
}

if(isset($_GET["tipo_participante"]))
{
	$tipo_participante=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["tipo_participante"]));
	if(DEBUG){ echo"__> TIpo Participante: $tipo_participante<br>";}
	
	if(isset($_GET["sexo"])){$filtro_1_sexo=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["sexo"]));}
	else{ $filtro_1_sexo="0";}
	
	if(isset($_GET["year_egreso"])){$filtro_1_year_egreso=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["year_egreso"]));}
	else{ $filtro_1_year_egreso="0";}
	if(isset($_GET["sede"])){$filtro_1_sede=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["sede"]));}
	else{ $filtro_1_sede="0";}
	
	if(isset($_GET["id_carrera"])){$filtro_1_carrera=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["id_carrera"]));}
	else{ $filtro_1_carrera="0";}
	
	
	
	if($filtro_1_sede!=="0"){ $condicion_sede="AND alumno.sede='$filtro_1_sede'";}
	else{ $condicion_sede="";}
	
	if($filtro_1_carrera!=="0"){ $condicion_carrera="AND alumno.id_carrera='$filtro_1_carrera'";}
	else{ $condicion_carrera="";}
	
	if($filtro_1_sexo!=="0"){ $condicion_sexo="AND alumno.sexo='$filtro_1_sexo'";}
	else{ $condicion_sexo="";}
	
	if($filtro_1_year_egreso!=="0"){ $condicion_year_egreso="AND alumno.year_egreso='$filtro_1_year_egreso'";}
	else{ $condicion_year_egreso="";}
}
else
{ $tipo_participante="";}

if(DEBUG){ echo"Tipo Participante: $tipo_participante<br>";}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
 <link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<title>Respuestas Anexas</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 56px;
}
</style>
</head>

<body>
<h1 id="banner">Respuesta Anexa</h1>
<div id="apDiv1">
<?php
	if($continuar_1 and $continuar_2 and $continuar_3)
	{
		$cons_P="SELECT pregunta FROM encuestas_pregunta WHERE id_pregunta='$id_pregunta' AND id_encuesta='$id_encuesta' LIMIT 1";
		$sqli_P=$conexion_mysqli->query($cons_P)or die($conexion_mysqli->error);
		$P=$sqli_P->fetch_assoc();
			$P_pregunta=$P["pregunta"];
		$sqli_P->free();
		
			$cons_A="SELECT contenido FROM encuestas_pregunta_hija WHERE id_pregunta_hija='$id_alternativa' AND id_pregunta='$id_pregunta' AND id_encuesta='$id_encuesta' LIMIT 1";
	
		$sqli_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
		$A=$sqli_A->fetch_assoc();
			$A_alternativa=$A["contenido"];
		$sqli_A->free();
		
				$tabla='<table width="50%" border="1" align="left">
						  <thead>
							<tr>
							  <th colspan="2">Datos</th>
							</tr>
							</thead>
							<tbody>
							<tr>
							  <td width="25%">Pregunta</td>
							  <td width="65%">'.$id_pregunta.' '.$P_pregunta.'</td>
							</tr>
							<tr>
							  <td>Alternativa</td>
							  <td>'.$id_alternativa.' '.$A_alternativa.'</td>
							</tr>
							</tbody>
						  </table>';
			
			
		$tabla_2='<table width="100%" border="1" align="left">
						  <thead>
							<tr>
							  <th colspan="2">Respuestas Anexas</th>
							</tr>
							</thead>
							<tbody>
							<tr>
								<td>Cantidad Respuestas</td>
								<td>Respuesta Anexa</td>
							</tr>';	
							
		switch($tipo_participante)
		{
			case"alumno":
				$cons_RA="SELECT COUNT(*) AS `Filas`, `respuesta_directa` FROM `encuestas_resultados` INNER JOIN alumno ON encuestas_resultados.id_usuario=alumno.id WHERE encuestas_resultados.tipo_usuario='alumno' AND encuestas_resultados.id_encuesta='$id_encuesta' AND encuestas_resultados.id_pregunta='$id_pregunta' $condicion_sede $condicion_carrera $condicion_sexo AND id_alternativa='$id_alternativa'  GROUP BY `respuesta_directa` ORDER BY `respuesta_directa` ";		
			break;
			case"ex_alumno":
				$cons_RA="SELECT COUNT(*) AS `Filas`, `respuesta_directa` FROM `encuestas_resultados` INNER JOIN alumno ON encuestas_resultados.id_usuario=alumno.id WHERE encuestas_resultados.tipo_usuario='alumno' AND encuestas_resultados.id_encuesta='$id_encuesta' AND encuestas_resultados.id_pregunta='$id_pregunta' $condicion_sede $condicion_carrera $condicion_sexo $condicion_year_egreso AND id_alternativa='$id_alternativa'  GROUP BY `respuesta_directa` ORDER BY `respuesta_directa` ";		
			break;
			default:							
				$cons_RA="SELECT COUNT(*) AS `Filas`, `respuesta_directa` FROM `encuestas_resultados` WHERE id_encuesta='$id_encuesta' AND id_pregunta='$id_pregunta' AND id_alternativa='$id_alternativa' GROUP BY `respuesta_directa` ORDER BY `respuesta_directa` ";				  
		}
		if(DEBUG){ echo"<br>-->$cons_RA<br>";}
		$sqli_RA=$conexion_mysqli->query($cons_RA)or die($conexion_mysqli->error);
			while($RA=$sqli_RA->fetch_assoc())
			{
				
				$filas=$RA["Filas"];
				$aux_respuesta_anexa=$RA["respuesta_directa"];
				$tabla_2.='<tr>
							<td>'.$filas.'</td>
						    <td>'.$aux_respuesta_anexa.'</td>
							</tr>';
			}
		
		echo $tabla."<br></br>";
		echo $tabla_2;
						  
	}
	else
	{ echo"Sin Datos :(<br>";}
?>
</div>
</body>
</html>