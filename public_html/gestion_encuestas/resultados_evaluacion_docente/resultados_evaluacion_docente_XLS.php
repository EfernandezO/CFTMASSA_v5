<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Gestion de Encuestas_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
require("../../../funciones/conexion_v2.php");
require("../../../funciones/funciones_sistema.php");
//-----------------------------------------------//
if($_POST)
{
	if(DEBUG){ var_dump($_POST);}
	
	$id_encuesta=mysqli_real_escape_string($conexion_mysqli, $_POST["id_encuesta"]);
	$sede_evaluar=mysqli_real_escape_string($conexion_mysqli, $_POST["sede"]);
	$id_carrera_evaluar=mysqli_real_escape_string($conexion_mysqli, $_POST["id_carrera"]);
	$semestre_evaluar=mysqli_real_escape_string($conexion_mysqli, $_POST["semestre"]);
	$year_evaluar=mysqli_real_escape_string($conexion_mysqli, $_POST["year"]);
	$continuar=true;
}
else
{
	$continuar=false;}

if($continuar)
{
	
	//------------------------------------------//
	require("../../../funciones/VX.php");
	$evento="Exporta resultados evaluacion docentes a xls id_encuesta: $id_encuesta Sede: $sede_evaluar id_carrera: $id_carrera_evaluar [$semestre_evaluar - $year_evaluar]";
	REGISTRA_EVENTO($evento);
	//--------------------------------------------//
	
	if(!DEBUG)
	{
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=Resultado_Encuesta_evaluacion_docentes.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
	
	$ARRAY_DOCENTES=array();
	
	$cons_D="SELECT DISTINCT(id_usuario_evaluar) FROM encuestas_resultados WHERE encuestas_resultados.id_encuesta='$id_encuesta' AND encuestas_resultados.id_carrera_evaluar='$id_carrera_evaluar' AND encuestas_resultados.semestre_evaluar='$semestre_evaluar' AND encuestas_resultados.year_evaluar='$year_evaluar' AND encuestas_resultados.sede_evaluar='$sede_evaluar'";
	
	$sqli_D=$conexion_mysqli->query($cons_D)or die($conexion_mysqli->error);
	$num_docentes_evaluados=$sqli_D->num_rows;
	if(DEBUG){ echo"---->$cons_D<br> num: $num_docentes_evaluados<br>";}
	if($num_docentes_evaluados>0)
	{
		while($D=$sqli_D->fetch_row())
		{
			$ARRAY_DOCENTES[]=$D[0];
		}
	}
	$sqli_D->free();
	
	if(DEBUG){ var_dump($ARRAY_DOCENTES);}
//--------------------------------------------------------------------------------------------------------------------------------------//

	
	
	 //datos de encuesta
		 $cons_E="SELECT * FROM encuestas_main WHERE id_encuesta='$id_encuesta' LIMIT 1";
		 $sql_E=$conexion_mysqli->query($cons_E)or die("Encuesta".$conexion_mysqli->error);
		$DE=$sql_E->fetch_assoc();
			$encuesta_nombre=$DE["nombre"];
			$encuesta_descripcion=$DE["descripcion"];
	$sql_E->free();
		///////////////////////////////////////////
		
}
?>
</div>
<div id="apDiv1">
<?php

if($continuar)
{


$fila_2='';
$color1='#ffffaa';
$color2='#aaffff';

echo'ENCUESTA '.utf8_decode($encuesta_nombre).'<br>
Carrera:'.utf8_decode(NOMBRE_CARRERA($id_carrera_evaluar)).'<br>Sede '.$sede_evaluar.' Periodo ['.$semestre_evaluar.' - '.$year_evaluar.']<br>';

echo'<table border="1">
</tr>


<tr>
	<td rowspan="2">Usuario Evaluado</td>';


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
				
				if($contador%2==0){$color=$color1;}
				else{$color=$color2;}
				
				switch($tipo)
				{
					case"alternativa":
						
						$cons_A="SELECT * FROM encuestas_pregunta_hija WHERE id_encuesta='$id_encuesta' AND id_pregunta='$id_pregunta'";
						if(DEBUG){ echo"--->$cons_A<br>";}
						$sql_A=$conexion_mysqli->query($cons_A)or die("Alternativas".$conexion_mysqli->error);
						$num_alternativas=$sql_A->num_rows;
						
						echo'<td bgcolor="'.$color.'" colspan="'.$num_alternativas.'">'.$pregunta.'</td>';
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
								$fila_2.='<td bgcolor="'.$color.'">'.$contenido.'</td>';
								//////////////////////////////////////////////////////////////
							}
							
							
						}
						else
						{}
						$sql_A->free();
					break;
					case"directa":
						echo'<td rowspan="2" bgcolor="'.$color.'">'.$pregunta.'</td>';
						$ARRAY_ENCUESTA[$tipo][$id_pregunta]=true;
						break;
				}
				///////////////////////////////////////
			}
		}
	echo'<td rowspan="2">Participantes Evaluadores</td></tr>
	<tr>
		'.$fila_2.'
	</tr>';
	
	
		$sql->free();
		
}
//---------------------------------------------------------------------------------------//

if($continuar)
{
	
	foreach($ARRAY_DOCENTES as $n => $id_usuario)	
	{
		echo'<tr>
					<td>'.utf8_decode(NOMBRE_PERSONAL($id_usuario)).'</td>';
	   //if(DEBUG){ var_dump($ARRAY_ENCUESTA);}
	   if($num_preguntas)
	   {
		   $contador_pregunta=0;
		   foreach($ARRAY_ENCUESTA["pregunta"] as $id_pregunta => $aux_array_pregunta)
		   {
			   $ARRAY_RESULTADOS=array();
			   $contador_pregunta++;
			   $aux_tipo_pregunta=$aux_array_pregunta["tipo"];
			   $aux_interrogante_pregunta=$aux_array_pregunta["interrogante"];
			   $contador_alternativas=0;
			   
			   if($contador_pregunta%2==0){$color=$color1;}
				else{$color=$color2;}
			   
			   if(DEBUG){echo"TIPO PREGUNTA: $aux_tipo_pregunta<br>";}
			   switch($aux_tipo_pregunta)
			   {
				   case"alternativa":
					   if(isset($ARRAY_ENCUESTA["alternativa"][$id_pregunta]))
					   {
						   foreach($ARRAY_ENCUESTA["alternativa"][$id_pregunta] as $nX => $array_X)
						   {
							   $valorX=$array_X["contenido"];
							   $respuesta_anexa=$array_X["respuesta_anexa"];
							   
							
								$cons_AL="SELECT COUNT(id_resultados) FROM encuestas_resultados WHERE id_encuesta='$id_encuesta' AND semestre_evaluar='$semestre_evaluar' AND year_evaluar='$year_evaluar' AND id_carrera_evaluar='$id_carrera_evaluar' AND sede_evaluar='$sede_evaluar' AND id_usuario_evaluar='$id_usuario' AND id_pregunta='$id_pregunta' AND id_alternativa='$nX'";
									 if(DEBUG){ echo"-->$cons_AL<br>";}	
								if($respuesta_anexa=="1"){ if(DEBUG){ echo"Hay Respuesta Anexa<br>";}
							   else{ if(DEBUG){ echo"NO hay Respuesta Anexa<br>";} $info_anexa='';}}
							  
								 
							   $sql_AL=$conexion_mysqli->query($cons_AL)or die("Resultados".$conexion_mysqli->error);
								$Dal=$sql_AL->fetch_row();
									$num_resultados=$Dal[0];
									if(empty($num_resultados)){ $num_resultados=0;}
									$ARRAY_RESULTADOS[$contador_alternativas]=$num_resultados;
									if(DEBUG){ echo"<strong><tt>------>$cons_AL<br> num resultados: $num_resultados</tt></strong><br>";}
								$sql_AL->free();
								$contador_alternativas++;
								
								echo'<td bgcolor="'.$color.'">'.$num_resultados.'</td>';
								
						   }
						 
						  
					   }
					  
			   
					  
					break;
					case"directa":
						echo'';
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
										echo'<td bgcolor="'.$color.'"><strong>Respuesta Directa</strong> '.$contador_respuestas_directas.': '.$aux_respuesta_directa.'</td>';
									}
									
									$sql_AL->free();
						}
						
						break;
			   }
		   }
	   }
	 
	 if(DEBUG){ echo"Calculando participantes para Todos<br>";}
			$cons_NP="SELECT COUNT(DISTINCT(id_usuario)) FROM encuestas_resultados WHERE id_encuesta='$id_encuesta' AND semestre_evaluar='$semestre_evaluar' AND year_evaluar='$year_evaluar' AND id_carrera_evaluar='$id_carrera_evaluar' AND sede_evaluar='$sede_evaluar' AND id_usuario_evaluar='$id_usuario'";
			$sql_NP=$conexion_mysqli->query($cons_NP)or die("Participantes".$conexion_mysqli->error);
				$Dnp=$sql_NP->fetch_row();
				$num_participantes=$Dnp[0];
				if(empty($num_participantes)){ $num_participantes=0;}
	 echo'<td>'.$num_participantes.'</td>';
	  
	}
}
echo'</tbody></table>',
@mysql_close($conexion);
$conexion_mysqli->close();
?>		