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
if(isset($_GET["id_encuesta"]))
{
	$id_encuesta=$_GET["id_encuesta"];
	if(is_numeric($id_encuesta))
	{$continuar=true;}
	else{ $continuar=false;}
}
else
{ $continuar=false;}

//////////////////////+++++++/////////////////////////
if($continuar)
{ if(DEBUG){ echo"Continuar: OK<br>";}}
else
{ if(DEBUG){ echo"Continuar: NO<br>";}else{header("location: ../index.php");}}

///////////////////////////////////////////////
if($continuar)
   {
	   	 require("../../../funciones/conexion_v2.php");
		if(DEBUG){ var_dump($_GET);}
		
		$array_participantes=array("alumno", "ex_alumno");
		
		if(isset($_GET["tipo_participante"])){ $tipo_participante=mysqli_real_escape_string($conexion_mysqli, $_GET["tipo_participante"]);}
		else{$tipo_participante="0";}
		if(DEBUG){ echo"__> TIpo Participante: $tipo_participante<br>";}
		if(isset($_GET["sexo"])){$filtro_1_sexo=mysqli_real_escape_string($conexion_mysqli, $_GET["sexo"]);}
		else{ $filtro_1_sexo="0";}
		
		if(isset($_GET["year_egreso"])){$filtro_1_year_egreso=mysqli_real_escape_string($conexion_mysqli, $_GET["year_egreso"]);}
		else{ $filtro_1_year_egreso="0";}
		
		if(isset($_GET["year_ingreso"])){$filtro_1_year_ingreso=mysqli_real_escape_string($conexion_mysqli, $_GET["year_ingreso"]);}
		else{ $filtro_1_year_ingreso="0";}
		
		
		if(isset($_GET["sede"])){$filtro_1_sede=mysqli_real_escape_string($conexion_mysqli, $_GET["sede"]);}
		else{ $filtro_1_sede="0";}
		if(isset($_GET["carrera"])){$filtro_1_carrera=mysqli_real_escape_string($conexion_mysqli, $_GET["carrera"]);}
		else{ $filtro_1_carrera="0";}
		
		
		 require("../../../funciones/VX.php");
		 require("../../../funciones/funciones_sistema.php");
		 $evento="Revisa Resultados de Encuesta id_encuesta: $id_encuesta";
		 REGISTRA_EVENTO($evento);
		 
		 //datos de encuesta
		 $cons_E="SELECT * FROM encuestas_main WHERE id_encuesta='$id_encuesta' LIMIT 1";
		 $sql_E=$conexion_mysqli->query($cons_E)or die("Encuesta".$conexion_mysqli->error);
		$DE=$sql_E->fetch_assoc();
			$encuesta_nombre=$DE["nombre"];
			$encuesta_descripcion=$DE["descripcion"];
	$sql_E->free();
		///////////////////////////////////////////
		
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
		
		
		//////////////////////////////////////
		//numero de participantes
		
		
		
		switch($tipo_participante)
		{
			case"ex_alumno":
				if(DEBUG){ echo"Calculando participantes para ex_alumnos<br>";}
				if($filtro_1_sede!=="0"){ $condicion_sede="AND alumno.sede='$filtro_1_sede'";}
				else{ $condicion_sede="";}
				
				if($filtro_1_carrera!=="0"){ $condicion_carrera="AND alumno.id_carrera='$filtro_1_carrera'";}
				else{ $condicion_carrera="";}
				
				if($filtro_1_sexo!=="0"){ $condicion_sexo="AND alumno.sexo='$filtro_1_sexo'";}
				else{ $condicion_sexo="";}
				
				if($filtro_1_year_egreso!=="0"){ $condicion_year_egreso="AND alumno.year_egreso='$filtro_1_year_egreso'";}
				else{ $condicion_year_egreso="";}
				
				
				$cons_NP="SELECT COUNT(DISTINCT(encuestas_resultados.id_usuario)) FROM encuestas_resultados LEFT JOIN alumno ON encuestas_resultados.id_usuario=alumno.id WHERE encuestas_resultados.id_encuesta='$id_encuesta' AND encuestas_resultados.tipo_usuario='ex_alumno' $condicion_sede $condicion_carrera $condicion_sexo $condicion_year_egreso";
				if(DEBUG){ echo"----->$cons_NP<br>";}
				$sql_NP=$conexion_mysqli->query($cons_NP)or die("Participantes".$conexion_mysqli->error);
					$Dnp=$sql_NP->fetch_row();
					$num_participantes=$Dnp[0];
					if(empty($num_participantes)){ $num_participantes=0;}
					if(DEBUG){ echo"----->Num participantes: $num_participantes<br>";}
				$sql_NP->free();	
				break;
			case"alumno":
				if(DEBUG){ echo"Calculando participantes para alumnos<br>";}
				if($filtro_1_sede!=="0"){ $condicion_sede="AND alumno.sede='$filtro_1_sede'";}
				else{ $condicion_sede="";}
				
				if($filtro_1_carrera!=="0"){ $condicion_carrera="AND alumno.id_carrera='$filtro_1_carrera'";}
				else{ $condicion_carrera="";}
				
				if($filtro_1_sexo!=="0"){ $condicion_sexo="AND alumno.sexo='$filtro_1_sexo'";}
				else{ $condicion_sexo="";}
				
				if($filtro_1_year_ingreso!=="0"){ $condicion_year_ingreso="AND alumno.ingreso='$filtro_1_year_ingreso'";}
				else{ $condicion_year_ingreso="";}
				
				
				
				$cons_NP="SELECT COUNT(DISTINCT(encuestas_resultados.id_usuario)) FROM encuestas_resultados LEFT JOIN alumno ON encuestas_resultados.id_usuario=alumno.id WHERE encuestas_resultados.id_encuesta='$id_encuesta' AND encuestas_resultados.tipo_usuario='alumno' $condicion_sede $condicion_carrera $condicion_sexo $condicion_year_ingreso";
				if(DEBUG){ echo"----->$cons_NP<br>";}
				$sql_NP=$conexion_mysqli->query($cons_NP)or die("Participantes".$conexion_mysqli->error);
					$Dnp=$sql_NP->fetch_row();
					$num_participantes=$Dnp[0];
					if(empty($num_participantes)){ $num_participantes=0;}
					if(DEBUG){ echo"----->Num participantes: $num_participantes<br>";}
				$sql_NP->free();	
				break;	
			default:
				if(DEBUG){ echo"Calculando participantes para Todos<br>";}
				$cons_NP="SELECT COUNT(DISTINCT(id_usuario)) FROM encuestas_resultados WHERE id_encuesta='$id_encuesta'";
				$sql_NP=$conexion_mysqli->query($cons_NP)or die("Participantes".$conexion_mysqli->error);
					$Dnp=$sql_NP->fetch_row();
					$num_participantes=$Dnp[0];
					if(empty($num_participantes)){ $num_participantes=0;}
					if(DEBUG){ echo"----->$cons_NP<br> Num participantes: $num_participantes<br>";}
				$sql_NP->free();		
		}
		
		
		///////////////////////////////////////
		
   }
//////////////////////+++++++/////////////////////////
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Resultados | Encuesta</title>
	<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
    <link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:55px;
	z-index:1;
	left: 5%;
	top: 453px;
}
#boton {
	text-align:center;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:64px;
	z-index:2;
	left: 30%;
	top: 112px;
	text-align: center;
	font-size: medium;
	font-weight: bold;
	border: thin dashed #1E78C3;
}
#apDiv3 {
	position:absolute;
	width:40%;
	height:60px;
	z-index:3;
	left: 30%;
	top: 199px;
}
-->
</style>
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
</head>
<body>
<h1 id="banner">Contestar Encuesta</h1>
<div id="link"><br />
<a href="../index.php" class="button">Volver a Encuestas</a><br />
<br />
</div>
<div id="apDiv1">
	   <?php
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
								   
									switch($tipo_participante)
									{
										case"ex_alumno":
											if(DEBUG){ echo"Tipo Participante: ex_alumno<br>";}
												$cons_AL="SELECT COUNT(encuestas_resultados.id_resultados) FROM encuestas_resultados INNER JOIN alumno ON encuestas_resultados.id_usuario=alumno.id WHERE encuestas_resultados.tipo_usuario='ex_alumno' AND encuestas_resultados.id_encuesta='$id_encuesta' AND encuestas_resultados.id_pregunta='$id_pregunta' AND encuestas_resultados.id_alternativa='$nX' $condicion_sede $condicion_carrera $condicion_sexo $condicion_year_egreso";
										    if($respuesta_anexa=="1"){ if(DEBUG){ echo"Hay Respuesta Anexa<br>";} $info_anexa='<a href="ver_respuestas_anexas.php?id_encuesta='.base64_encode($id_encuesta).'&id_alternativa='.base64_encode($nX).'&id_pregunta='.base64_encode($id_pregunta).'&tipo_participante='.base64_encode("exalumno").'&sede='.base64_encode($filtro_1_sede).'&id_carrera='.base64_encode($filtro_1_carrera).'&sexo='.base64_encode($filtro_1_sexo).'&year_egreso='.base64_encode($filtro_1_year_egreso).'&lightbox[iframe]=true&lightbox[width]=500&lightbox[height]=410"  class="lightbox">[Ver]</a>';}
								  			 else{ if(DEBUG){ echo"NO hay Respuesta Anexa<br>";} $info_anexa='';}
											break;
										case"alumno":
											if(DEBUG){ echo"Tipo Participante: alumno<br>";}
												$cons_AL="SELECT COUNT(encuestas_resultados.id_resultados) FROM encuestas_resultados INNER JOIN alumno ON encuestas_resultados.id_usuario=alumno.id WHERE encuestas_resultados.tipo_usuario='alumno' AND encuestas_resultados.id_encuesta='$id_encuesta' AND encuestas_resultados.id_pregunta='$id_pregunta' AND encuestas_resultados.id_alternativa='$nX' $condicion_sede $condicion_carrera $condicion_sexo $condicion_year_ingreso";
										    if($respuesta_anexa=="1"){ if(DEBUG){ echo"Hay Respuesta Anexa<br>";} $info_anexa='<a href="ver_respuestas_anexas.php?id_encuesta='.base64_encode($id_encuesta).'&id_alternativa='.base64_encode($nX).'&id_pregunta='.base64_encode($id_pregunta).'&tipo_participante='.base64_encode("alumno").'&sede='.base64_encode($filtro_1_sede).'&id_carrera='.base64_encode($filtro_1_carrera).'&sexo='.base64_encode($filtro_1_sexo).'&year_egreso='.base64_encode($filtro_1_year_egreso).'&lightbox[iframe]=true&lightbox[width]=500&lightbox[height]=410"  class="lightbox">[Ver]</a>';}
								  			 else{ if(DEBUG){ echo"NO hay Respuesta Anexa<br>";} $info_anexa='';}	
											 break;
										default:
											if(DEBUG){ echo"Tipo Participante: Todos<br>";}
											$cons_AL="SELECT COUNT(id_resultados) FROM encuestas_resultados WHERE id_encuesta='$id_encuesta' AND id_pregunta='$id_pregunta' AND id_alternativa='$nX'";
											
											 if($respuesta_anexa=="1"){ if(DEBUG){ echo"Hay Respuesta Anexa<br>";} $info_anexa='<a href="ver_respuestas_anexas.php?id_encuesta='.base64_encode($id_encuesta).'&id_alternativa='.base64_encode($nX).'&id_pregunta='.base64_encode($id_pregunta).'&lightbox[iframe]=true&lightbox[width]=500&lightbox[height]=410"  class="lightbox">[Ver]</a>';}
								   else{ if(DEBUG){ echo"NO hay Respuesta Anexa<br>";} $info_anexa='';}
										
									}
									
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
								
									switch($tipo_participante)
									{
										case"ex_alumno":
											$cons_D="SELECT encuestas_resultados.respuesta_directa FROM encuestas_resultados INNER JOIN alumno ON encuestas_resultados.id_usuario=alumno.id WHERE encuestas_resultados.tipo_usuario='ex_alumno' AND encuestas_resultados.id_encuesta='$id_encuesta' AND encuestas_resultados.id_pregunta='$id_pregunta' $condicion_sede $condicion_carrera $condicion_sexo $condicion_year_egreso";
											break;
										case"alumno":
											$cons_D="SELECT encuestas_resultados.respuesta_directa FROM encuestas_resultados INNER JOIN alumno ON encuestas_resultados.id_usuario=alumno.id WHERE encuestas_resultados.tipo_usuario='alumno' AND encuestas_resultados.id_encuesta='$id_encuesta' AND encuestas_resultados.id_pregunta='$id_pregunta' $condicion_sede $condicion_carrera $condicion_sexo $condicion_year_ingreso";
											break;	
											
										default:
											$cons_D="SELECT respuesta_directa FROM encuestas_resultados WHERE id_encuesta='$id_encuesta' AND id_pregunta='$id_pregunta'";
									}
											
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
       <br />

</div>
<div id="apDiv2">Encuesta cod:<?php echo $id_encuesta?><br> 
  &quot;<?php echo $encuesta_nombre; ?>&quot;<br />
Numero de Participantes:<a href="ver_participantes/ver_participantes.php?id_encuesta=<?php echo $id_encuesta;?>"><?php echo $num_participantes;?></a></div>
<div id="apDiv3">
<form action="ver_resultados.php" method="get" id="frm">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="2">Filtro 
        <input name="id_encuesta" type="hidden" id="id_encuesta" value="<?php echo $id_encuesta;?>" /></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>Tipo Participante</td>
      <td><label for="tipo_participante"></label>
        <select name="tipo_participante" id="tipo_participante">
          <?php
          foreach($array_participantes as $n => $valor)
		  {
			  if($tipo_participante==$valor)
			  { $selected='selected="selected"';}
			  else
			  { $selected='';}
			  echo'<option value="'.$valor.'" '.$selected.'>'.$valor.'</option>';
		  }
		  ?>
        </select></td>
    </tr>
    <tr>
      <td>Año Ingreso</td>
      <td><?php echo CAMPO_SELECCION("year_ingreso", "year", $filtro_1_year_ingreso, true);?>*solo para alumnos</td>
    </tr>
    <tr>
      <td width="49%">Año Egreso</td>
      <td width="51%"><?php echo CAMPO_SELECCION("year_egreso", "year", $filtro_1_year_egreso, true);?>*solo para ex_alumnos</td>
    </tr>
    <tr>
      <td>Sede</td>
      <td><?php echo CAMPO_SELECCION("sede", "sede", $filtro_1_sede, true);?></td>
    </tr>
    <tr>
      <td>Carrera</td>
      <td><?php echo CAMPO_SELECCION("carrera", "carreras", $filtro_1_carrera, true);?></td>
    </tr>
    <tr>
      <td>Sexo</td>
      <td><?php echo CAMPO_SELECCION("sexo", "sexo", $filtro_1_sexo, true);?></td>
    </tr>
    <tr>
      <td colspan="2"><a href="#" class="button_R" onclick="javascript:document.getElementById('frm').submit();">Filtrar</a></td>
      </tr>
    </tbody>
  </table>
  </form>
</div>
</body>
</html>