<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
	define("DEBUG",false);
//-----------------------------------------//

require("../../../funciones/conexion_v2.php");

if($_GET)
{
	$continuar=true;
	$id_encuesta=base64_decode($_GET["id_encuesta"]);
	$id_usuario_evaluar=base64_decode($_GET["id_usuario_evaluar"]);
	$id_carrera_evaluar=base64_decode($_GET["id_carrera_evaluar"]);
	$semestre_evaluar=base64_decode($_GET["semestre_evaluar"]);
	$year_evaluar=base64_decode($_GET["year_evaluar"]);
	$sede_evaluar=base64_decode($_GET["sede_evaluar"]);
	
	$id_pregunta=base64_decode($_GET["id_pregunta"]);
	$id_alternativa=base64_decode($_GET["id_alternativa"]);

}
else
{ $continuar=false;}
	
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
	if($continuar_1)
	{
		include("../../../funciones/VX.php");
		$evento="Revisa Respuestas Anexas de encuesta (Evaluacion Docente) id_encuesta: $id_encuesta id_pregunta: $id";
		REGISTRA_EVENTO($evento);
		
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
							
				
		$cons_RA="SELECT COUNT(*) AS `Filas`, `respuesta_directa` FROM `encuestas_resultados` WHERE id_encuesta='$id_encuesta' AND id_pregunta='$id_pregunta' AND id_alternativa='$id_alternativa' AND semestre_evaluar='$semestre_evaluar' AND year_evaluar='$year_evaluar' AND id_carrera_evaluar='$id_carrera_evaluar' AND sede_evaluar='$sede_evaluar' AND id_usuario_evaluar='$id_usuario_evaluar' GROUP BY `respuesta_directa` ORDER BY `respuesta_directa` ";				  
		
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