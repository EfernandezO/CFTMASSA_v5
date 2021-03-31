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
require("../../../funciones/funciones_sistema.php");
//-----------------------------------------------//
if($_GET)
{
	if(DEBUG){ var_dump($_GET);}
	
	$id_encuesta=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["id_encuesta"]));
	$sede_evaluar=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["sede"]));
	$id_carrera_evaluar=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["id_carrera"]));
	$semestre_evaluar=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["semestre"]));
	$year_evaluar=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["year"]));
	$id_docente_evaluar=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["id_docente"]));
	$continuar=true;
	
	require("../../../funciones/VX.php");
	$evento="Docente id_docente:$id_docente_evaluar -> Revisa Resultados Evaluacion Docente id_encuesta:$id_encuesta para la carrera id_carrera: $id_carrera_evaluar - $sede_evaluar [$semestre_evaluar - $year_evaluar]";
	REGISTRA_EVENTO($evento);
}
else
{
	$continuar=false;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Resultados Evaluacion Docente</title>
	<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
    <link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
  <!--INICIO LIGHTBOX EVOLUTION-->
  <script type="text/javascript" src="../../libreria_publica/lightbox_1.6.9_evolution/js/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.css" />
  <!--[if IE 6]>
  <link rel="stylesheet" type="text/css" href="../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.ie6.css" />
  <![endif]-->
  <script type="text/javascript" src="../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/jquery.lightbox.min.js"></script>
 <script type="text/javascript">
    jQuery(document).ready(function($){
      $('.lightbox').lightbox(); 
    });
  </script>
 <!--FIN LIGHTBOX EVOLUTION-->   
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 169px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:81px;
	z-index:2;
	left: 30%;
	top: 75px;
	text-align:center;
}
</style>
</head>

<body>
<h1 id="banner">Resultados Evalucion Docente</h1>
<div id="apDiv2">
<?php
if($continuar)
{
	 //datos de encuesta
		 $cons_E="SELECT * FROM encuestas_main WHERE id_encuesta='$id_encuesta' LIMIT 1";
		 $sql_E=$conexion_mysqli->query($cons_E)or die("Encuesta".$conexion_mysqli->error);
		$DE=$sql_E->fetch_assoc();
			$encuesta_nombre=$DE["nombre"];
			$encuesta_descripcion=$DE["descripcion"];
	$sql_E->free();
		///////////////////////////////////////////
		if(DEBUG){ echo"Calculando participantes para Todos<br>";}
		$cons_NP="SELECT COUNT(DISTINCT(id_usuario)) FROM encuestas_resultados WHERE id_encuesta='$id_encuesta' AND semestre_evaluar='$semestre_evaluar' AND year_evaluar='$year_evaluar' AND id_carrera_evaluar='$id_carrera_evaluar' AND sede_evaluar='$sede_evaluar' AND id_usuario_evaluar='$id_docente_evaluar'";
		$sql_NP=$conexion_mysqli->query($cons_NP)or die("Participantes".$conexion_mysqli->error);
			$Dnp=$sql_NP->fetch_row();
			$num_participantes=$Dnp[0];
			if(empty($num_participantes)){ $num_participantes=0;}
			if(DEBUG){ echo"----->$cons_NP<br> Num participantes: $num_participantes<br>";}
			
			if($num_participantes>0){ $num_participantes_label='<a href="ver_participantes.php?id_encuesta='.base64_encode($id_encuesta).'&id_carrera_evaluar='.base64_encode($id_carrera_evaluar).'&sede_evaluar='.base64_encode($sede_evaluar).'&semestre_evaluar='.base64_encode($semestre_evaluar).'&year_evaluar='.base64_encode($year_evaluar).'&id_usuario_evaluar='.base64_encode($id_docente_evaluar).'&lightbox[iframe]=true&lightbox[width]=650&lightbox[height]=350"  class="lightbox"">'.$num_participantes.'</a>';}
			else{ $num_participantes_label=$num_participantes;}
		$sql_NP->free();		
		
	echo"Resultados de Encuesta id_encuesta: $id_encuesta - $encuesta_nombre<br>";	
	echo"Para el docente: <strong>".NOMBRE_PERSONAL($id_docente_evaluar)."</strong><br>";
	echo"Evaluado en la Carrera <strong>". NOMBRE_CARRERA($id_carrera_evaluar)."</strong> id_carrera: $id_carrera_evaluar <br>";
	echo"en el Periodo [$semestre_evaluar - $year_evaluar]<br><br>";
	echo"Numero de encuestados: $num_participantes_label<br>";
}
?>
</div>
<div id="apDiv1">
<?php

if($continuar)
{


		
		$ARRAY_ENCUESTA=array();
		//////////////////////////////////////////////
		$cons_P="SELECT * FROM encuestas_pregunta WHERE id_encuesta='$id_encuesta' ORDER by posicion, id_pregunta";
		 if(DEBUG){ echo"-->$cons_P<br>";}
		 $sql=$conexion_mysqli->query($cons_P)or die("Preguntas".$conexion_mysqli->error);
	  $num_preguntas=$sql->num_rows;
	   if($num_preguntas>0)
	   {
		   $contador=0;
			while($M=$sql->fetch_assoc())
			{
				$contador++;
				$id_pregunta=$M["id_pregunta"];
				$pregunta=$M["pregunta"];
				$tipo=$M["tipo"];
				///////////////////////////////////////
				$ARRAY_ENCUESTA["pregunta"][$id_pregunta]["tipo"]=$tipo;
				$ARRAY_ENCUESTA["pregunta"][$id_pregunta]["interrogante"]=$pregunta;
				////////////////////////////////////////
				///busco numero alternativas
				
				switch($tipo)
				{
					case"alternativa":
						$cons_A="SELECT * FROM encuestas_pregunta_hija WHERE id_encuesta='$id_encuesta' AND id_pregunta='$id_pregunta'";
						if(DEBUG){ echo"--->$cons_A<br>";}
						$sql_A=$conexion_mysqli->query($cons_A)or die("Alternativas".$conexion_mysqli->error);
						$num_alternativas=$sql_A->num_rows;
						if($num_alternativas>0)
						{
							while($A=$sql_A->fetch_assoc())
							{
								$id_alternativa=$A["id_pregunta_hija"];
								$contenido=$A["contenido"];
								$respuesta_anexa=$A["respuesta_anexa"];
								//////////////////////////////////////////////////////////////
								
								$ARRAY_ENCUESTA[$tipo][$id_pregunta][$id_alternativa]["contenido"]=$contenido;
								$ARRAY_ENCUESTA[$tipo][$id_pregunta][$id_alternativa]["respuesta_anexa"]=$respuesta_anexa;
								//////////////////////////////////////////////////////////////
							}
						}
						else
						{}
						$sql_A->free();
					break;
					case"directa":
						$ARRAY_ENCUESTA[$tipo][$id_pregunta]=true;
						break;
				}
				///////////////////////////////////////
			}
		}
		else
		{
			
		}
		$sql->free();
		
}
//---------------------------------------------------------------------------------------//

if($continuar)
{
   if(DEBUG){ var_dump($ARRAY_ENCUESTA);}
   if($num_preguntas)
   {
	   $contador_pregunta=0;
	   foreach($ARRAY_ENCUESTA["pregunta"] as $id_pregunta => $aux_array_pregunta)
	   {
		   $ARRAY_RESULTADOS=array();
		   $contador_pregunta++;
		   $aux_tipo_pregunta=$aux_array_pregunta["tipo"];
		   $aux_interrogante_pregunta=$aux_array_pregunta["interrogante"];
		   echo'<table border="1" width="100%">
				<thead>
					<th colspan="10">PREGUNTA '.$contador_pregunta.': '.$aux_interrogante_pregunta.'</th>
				</thead>
				<tbody>
				<tr>';
				
		   $contador_alternativas=0;
		   
		   if(DEBUG){echo"TIPO PREGUNTA: $aux_tipo_pregunta<br>";}
		   switch($aux_tipo_pregunta)
		   {
			   case"alternativa":
					$primera_vuelta_grafico=true;
					$dato_grafico='[';
					echo'<td>Alternativas</td>';
				   if(isset($ARRAY_ENCUESTA["alternativa"][$id_pregunta]))
				   {
					   foreach($ARRAY_ENCUESTA["alternativa"][$id_pregunta] as $nX => $array_X)
					   {
						   $valorX=$array_X["contenido"];
						   $respuesta_anexa=$array_X["respuesta_anexa"];
						   
							
						
							$cons_AL="SELECT COUNT(id_resultados) FROM encuestas_resultados WHERE id_encuesta='$id_encuesta' AND semestre_evaluar='$semestre_evaluar' AND year_evaluar='$year_evaluar' AND id_carrera_evaluar='$id_carrera_evaluar' AND sede_evaluar='$sede_evaluar' AND id_usuario_evaluar='$id_docente_evaluar' AND id_pregunta='$id_pregunta' AND id_alternativa='$nX'";
									
							if($respuesta_anexa=="1"){ if(DEBUG){ echo"Hay Respuesta Anexa<br>";} $info_anexa='<a href="ver_respuestas_anexas.php?id_encuesta='.base64_encode($id_encuesta).'&id_alternativa='.base64_encode($nX).'&id_pregunta='.base64_encode($id_pregunta).'&id_carrera_evaluar='.base64_encode($id_carrera_evaluar).'&sede_evaluar='.base64_encode($sede_evaluar).'&semestre_evaluar='.base64_encode($semestre_evaluar).'&year_evaluar='.base64_encode($year_evaluar).'&id_usuario_evaluar='.base64_encode($id_docente_evaluar).'&lightbox[iframe]=true&lightbox[width]=500&lightbox[height]=410"  class="lightbox">[Ver]</a>';}
						   else{ if(DEBUG){ echo"NO hay Respuesta Anexa<br>";} $info_anexa='';}
								
							
							
							echo'<td> '.$valorX.' '.$info_anexa.'</td>';
							 
						   $sql_AL=$conexion_mysqli->query($cons_AL)or die("Resultados".$conexion_mysqli->error);
							$Dal=$sql_AL->fetch_row();
								$num_resultados=$Dal[0];
								if(empty($num_resultados)){ $num_resultados=0;}
								$ARRAY_RESULTADOS[$contador_alternativas]=$num_resultados;
								if(DEBUG){ echo"<strong><tt>------>$cons_AL<br> num resultados: $num_resultados</tt></strong><br>";}
							$sql_AL->free();
							$contador_alternativas++;
							if($primera_vuelta_grafico){$primera_vuelta_grafico=false; $prefijo='';}
							else{ $prefijo=', ';}
							
							$dato_grafico.=$prefijo.'{"alternativa": "'.$valorX.'","value": '.$num_resultados.'}';
					   }
					   $dato_grafico.='];';
					   $dato_grafico=base64_encode($dato_grafico);
					   if(DEBUG){ echo"-->$cons_AL<br>";}
				   }
				   else
				   {
					   echo"<tr><td>X -> pregunta sin alternativas</td></tr>";
				   }
		   
				   echo'</tr>
						<tr>
						<td>cantidad</td>';
				   for($x=0;$x<$contador_alternativas;$x++)
				   {
					   if($num_participantes>0)
					   {$aux_porcentaje=(($ARRAY_RESULTADOS[$x]*100)/$num_participantes);}
					   else{ $aux_porcentaje=0;}
					   echo'<td>'.$ARRAY_RESULTADOS[$x].' ('.number_format($aux_porcentaje,2,",",".").'%)</td>';
				   }
				   
				   echo'</tr><tr><td><a href="ver_grafico.php?id_encuesta='.base64_encode($id_encuesta).'&id_pregunta='.base64_encode($id_pregunta).'&dato_grafico='.$dato_grafico.'&lightbox[iframe]=true&lightbox[width]=650&lightbox[height]=510" class="lightbox">Ver grafico</a></td></tr></tbody></table><br>';
				break;
				case"directa":
					echo'</tr>';
					if(isset($ARRAY_ENCUESTA["directa"][$id_pregunta]))
					{
						
							
							$cons_D="SELECT respuesta_directa FROM encuestas_resultados WHERE id_encuesta='$id_encuesta' AND semestre_evaluar='$semestre_evaluar' AND year_evaluar='$year_evaluar' AND id_carrera_evaluar='$id_carrera_evaluar' AND sede_evaluar='$sede_evaluar' AND id_usuario_evaluar='$id_docente_evaluar' AND id_pregunta='$id_pregunta'";
							
									
							if(DEBUG){ echo"<strong><tt>--> $cons_D</tt></strong><br>";}
							   $sql_D=$conexion_mysqli->query($cons_D)or die("Resultados D".$conexion_mysqli->error);
							   $contador_respuestas_directas=0;
								while($Di=$sql_D->fetch_assoc())
								{
									$contador_respuestas_directas++;
									$aux_respuesta_directa=$Di["respuesta_directa"];
									echo'<tr><td><strong>Respuesta Directa</strong> '.$contador_respuestas_directas.': '.$aux_respuesta_directa.'</td></tr>';
								}
								echo'</tbody></table>';
								$sql_AL->free();
					}
					else
					{
						if(DEBUG){ echo"No definida<br>";}
					}
					break;
		   }
	   }
   }
   else
   {
	   echo"Sin Preguntas<br>";
   }
}
@mysql_close($conexion);
$conexion_mysqli->close();
?>		
</div>

</body>
</html>