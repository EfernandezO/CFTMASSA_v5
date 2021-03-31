<?php	
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="jefe_carrera";
	//$lista_invitados["privilegio"][]="Docente";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//
	////////////////////////
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	require("../../OKALIS/msj_error/anti_2_login.php");
	@mysql_close($conexion);


   $mes_actual=date("m");
   $year_actual=date("Y");
   if($mes_actual>=8){ $semestre_actual=2;}
   else{ $semestre_actual=1;}
   
  $id_usuario_actual=$_SESSION["USUARIO"]["id"];
   
   $continuar=false;
   $continuar_2=false;
  if(isset($_GET["id_docente"]))
  {
	  $id_encuesta=base64_decode($_GET["id_encuesta"]);
	  $id_docente=base64_decode($_GET["id_docente"]);
	  $semestre_periodo=base64_decode($_GET["semestre"]);
	  $year_periodo=base64_decode($_GET["year"]);
	  $sede=base64_decode($_GET["sede"]);
	  $id_carrera=base64_decode($_GET["id_carrera_evaluar"]);
	  $continuar=true;
	 
  }
  
  //-------------------------------------------------------------//
  if($continuar)
  {
	  if(DEBUG){ echo"Datos recibidos correctamente<br>";}
	  //---------------------------------------------------------//
	  if(DEBUG){ echo"ID ENCUESTA:$id_encuesta<br>";}
	  //-----------------------------------------------------------------------//
	  //busco si encuenta ha sido contestada para este docente perido carrera
	  if(DEBUG){ echo"Busco si encuesta id_encuesta:$id_encuesta ha sido contestada<br>";}
	  if($id_encuesta>0)
	  {
		  $cons_RE="SELECT COUNT(id_resultados) FROM encuestas_resultados WHERE id_encuesta='$id_encuesta' AND id_usuario='$id_alumno' AND id_usuario_evaluar='$id_docente' AND semestre_evaluar='$semestre_periodo' AND year_evaluar='$year_periodo' AND id_carrera_evaluar='$id_carrera'";
		  $SQLI_r=$conexion_mysqli->query($cons_RE)or die($conexion_mysqli->error);
		  $RE=$SQLI_r->fetch_row();
		  $numero_resultado=$RE[0];
		  if(DEBUG){ echo"--->$cons_RE<br>numero resultados: $numero_resultado<br>";}
		  if(empty($numero_resultado)){ $numero_resultado=0;}
		  if($numero_resultado>0){ $encuesta_contestada=true;   if(DEBUG){ echo"Encuesta ya ha sido contestada<br>";} }
		  else{ $encuesta_contestada=false; if(DEBUG){ echo"Encuesta No ha sido contestada<br>";} $continuar_2=true;}
	  }
	  
  }
  ///------------------------------------------------------------------------//
  
  
  $msj_encuesta="";
  if($continuar and $continuar_2)
  {
	 // list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($id_carrera, $cod_asignatura);
	  $msj_encuesta="Docente: ".NOMBRE_PERSONAL($id_docente)."<br>";
	  if(DEBUG){ echo"CARGADO encuesta<br>";}
	  $action="";
	 ///utilizo valor de la consulta
	   $_SESSION["ENCUESTA"]["contestada"]=$encuesta_contestada;
		 //datos de encuesta
		 $cons_E="SELECT * FROM encuestas_main WHERE id_encuesta='$id_encuesta' LIMIT 1";
		 $sql_E=$conexion_mysqli->query($cons_E)or die("Encuesta". $conexion_mysqli->error);
		$DE=$sql_E->fetch_assoc();	
			$encuesta_nombre=$DE["nombre"];
			$encuesta_descripcion=$DE["descripcion"];
		$sql_E->free();
		///////////////////////////////////////////
		
		$ARRAY_ENCUESTA=array();
		//////////////////////////////////////////////
		$cons_P="SELECT * FROM encuestas_pregunta WHERE id_encuesta='$id_encuesta'";
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
				$cons_A="SELECT * FROM encuestas_pregunta_hija WHERE id_encuesta='$id_encuesta' AND id_pregunta='$id_pregunta'";
				if(DEBUG){ echo"--->$cons_A<br>";}
				$sql_A=$conexion_mysqli->query($cons_A)or die("Alternativas". $conexion_mysqli->error);
				$num_alternativas=$sql_A->num_rows;

				if($num_alternativas>0)
				{
					$action="evaluacion_JC_D_3.php";
					while($A=$sql_A->fetch_assoc())
					{
						$id_alternativa=$A["id_pregunta_hija"];
						$contenido=$A["contenido"];
						$respuesta_anexa=$A["respuesta_anexa"];
						//////////////////////////////////////////////////////////////
						$ARRAY_ENCUESTA["alternativa"][$id_pregunta][$id_alternativa]["contenido"]=$contenido;
						$ARRAY_ENCUESTA["alternativa"][$id_pregunta][$id_alternativa]["respuesta_anexa"]=$respuesta_anexa;
						//////////////////////////////////////////////////////////////
					}
				}
				else
				{}
				$sql_A->free();
				///////////////////////////////////////
			}
		}
		else
		{
			echo"No se puede Continuar, Posiblemente ya contesto esta encuesta o No hay encuesta definifa para la evaluacion Docente<br>";	
		}
		$sql->free();
		
		
		//-----------------------------------------//	
		 include("../../../funciones/VX.php");
		 //cambio estado_conexion USER-----------
		  $evento="Revisa Encuesta Autoevaluacion Docente id_docente: $id_docente $sede [$semestre_periodo - $year_periodo]";
		 REGISTRA_EVENTO($evento);
		//-----------------------------------------------//
		@mysql_close($conexion);
		$conexion_mysqli->close();
//////////////////////+++++++/////////////////////////

  }
  
   
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Evalaucion JC -&gt; D</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:88px;
	z-index:1;
	left: 5%;
	top: 209px;
}
#boton {
	text-align:center;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:106px;
	z-index:2;
	left: 5%;
	top: 51px;
	text-align: center;
	font-size: medium;
	font-weight: bold;
	border: thin dashed #1E78C3;
}
#apDiv3 {
	position:absolute;
	width:90%;
	height:37px;
	z-index:3;
	left: 5%;
	top: 164px;
	text-align: center;
}
#preguntas {
	padding-top: 50px;
	list-style-type: decimal;
}
#alternativa {
	font-weight: normal;
	list-style-type: lower-alpha;
	list-style-position: outside;
	margin: 5px;
}
</style>
<script language="javascript">
function CONFIRMAR()
{
	c=confirm('Seguro(a) Desea Finalizar esta Encuesta');
	if(c){ document.getElementById('frm').submit();}
}
</script>
</head>

<body>
<h1 id="banner">Evaluación Docente</h1>
<div id="apDiv1">
<form action="<?php echo $action;?>" method="post" id="frm">
<input name="id_encuesta" type="hidden" value="<?php echo $id_encuesta;?>" />
<input name="id_usuario" type="hidden" value="<?php echo $id_usuario_actual; ?>" />
<input name="tipo_usuario" type="hidden" value="<?php echo "docente";?>" />
<input name="id_usuario_evaluar" type="hidden" id="id_usuario_evaluar" value="<?php echo $id_docente;?>" />
<input name="semestre_evaluar" type="hidden" id="semestre_evaluar" value="<?php echo $semestre_periodo;?>" />
<input name="year_evaluar" type="hidden" id="year_evaluar" value="<?php echo $year_periodo;?>" />
<input name="sede_evaluar" type="hidden" id="sede_evaluar" value="<?php echo $sede;?>" />
<input name="id_carrera_evaluar" type="hidden" id="id_carrera_evaluar" value="<?php echo $id_carrera;?>" />
	   <?php
	   if($continuar and $continuar_2)
	   {
		   if(DEBUG){ var_dump($ARRAY_ENCUESTA);}
		   echo'<ul>';
		  
		   
		   if($num_preguntas)
		   {
			   $contador_pregunta=0;
			   foreach($ARRAY_ENCUESTA["pregunta"] as $id_pregunta => $aux_array_pregunta)
			   {
				   $contador_pregunta++;
				   $aux_tipo_pregunta=$aux_array_pregunta["tipo"];
				   $aux_interrogante_pregunta=$aux_array_pregunta["interrogante"];
				   
				   
				   
				   echo'<li id="preguntas">'.$aux_interrogante_pregunta;
				   switch($aux_tipo_pregunta)
				   {
					   case"alternativa":
						   if(isset($ARRAY_ENCUESTA["alternativa"][$id_pregunta]))
						   {
							   echo'<ul>';
							   $contador_alternativa=0;
							   foreach($ARRAY_ENCUESTA["alternativa"][$id_pregunta] as $nX => $array_X)
							   {
								   $valorX=$array_X["contenido"];
								   $respuesta_anexa=$array_X["respuesta_anexa"];
								   if(DEBUG){ echo"$nX -> $valorX $respuesta_anexa<br>";}
								   
								 if($contador_alternativa==0)
								 { $check='checked="checked"';}else{ $check='';}
								 
								  echo'<li id="alternativa"><input name="RESPUESTA['.$id_pregunta.'][alternativa]" type="radio" value="'.$nX.'" '.$check.'/> '.$valorX;
								  if($respuesta_anexa==1)
								  { echo' <input name="RESPUESTA_ANEXA['.$nX.']" type="text" />';}
								  echo'</li>';
								   $contador_alternativa++;
							 }
							   echo'</ul>';
						   }
						   else
						   {
							   echo"<ul><li>X -> pregunta sin alternativas</li></ul>";
						   }
						   echo'</li>';
				   			break;
						case"directa":
							echo'<ul><li><textarea cols="50" rows="5" name="RESPUESTA['.$id_pregunta.'][directa]" id="directa"></textarea></li></ul>';
							break;	
				   }
			   }
			   echo'</ul>';
		   }
		   else
		   {
			   echo"Sin Preguntas<br>";
		   }
	   }
      
       if($continuar and $continuar_2)
	   {?>
       	<div id="boton"><a href="#" class="button_R" onclick="CONFIRMAR();">Finalizar Encuesta</a></div>
        <?php } ?>
 </form>

 <?php
 if(isset($_GET["error"]))
 {
	 $error=$_GET["error"];
	 
	 switch($error)
	 {
		 case"C0":
		 	$msj="Gracias por Contestar la Encuesta...:D";
	 }
	 
	 echo"$msj";
 }
 ?>
</div>
<?php 
if($continuar and $continuar_2){?>
<div id="apDiv2">Encuesta cod:<?php echo $id_encuesta?><br> 
  &quot;<?php echo $encuesta_nombre."<br>$msj_encuesta"; ?>
</div>
<div id="apDiv3"><?php echo $encuesta_descripcion;?></div>
<?php }elseif($encuesta_contestada){ echo"Encuesta Ya contestada...<br>";}?>
</body>
</html>