<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="ALUMNO";
	$lista_invitados["privilegio"][]="jefe_carrera";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="ex_alumno";
	$lista_invitados["privilegio"][]="matricula";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define("DEBUG", false);
if(isset($_GET["id_encuesta"]))
{
	$id_encuesta=base64_decode($_GET["id_encuesta"]);
	$sede_evaluar=base64_decode($_GET["sede_evaluar"]);
	$semestre_evaluar=base64_decode($_GET["semestre_evaluar"]);
	$year_evaluar=base64_decode($_GET["year_evaluar"]);
	
	if(is_numeric($id_encuesta))
	{$continuar=true;}
	else{ $continuar=false;}
}
else
{ $continuar=false;}

//-----------------------------------------------//
$continuar_1=false;
$continuar_2=false;

if(isset($_GET["tipo_usuario"]))
{ $continuar_1=true;}

if(isset($_GET["id_usuario"]))
{ $continuar_2=true;}


if($continuar_1 and $continuar_2)
{
	$realizar_encuesta_X_tercero=true;
	if(DEBUG){ echo"HAY GET<br>";}
	$tipo_usuario=base64_decode($_GET["tipo_usuario"]);
	$id_usuario=base64_decode($_GET["id_usuario"]);
}
else
{
	$realizar_encuesta_X_tercero=false;
	if(DEBUG){ echo"No hay Get<br>";}
	$tipo_usuario=$_SESSION["USUARIO"]["tipo"];
	$id_usuario=$_SESSION["USUARIO"]["id"];
}
//---------------------------------------------------------------------------//
//////////////////////+++++++/////////////////////////
if($continuar)
{ if(DEBUG){ echo"Continuar: OK<br>";}}
else
{ if(DEBUG){ echo"Continuar: NO<br>";}else{header("location: ../index.php");}}

///////////////////////////////////////////////
$action="";
if($continuar)
   {
	   if(!isset($_SESSION["ENCUESTA"]["contestada"]))
	   {$_SESSION["ENCUESTA"]["contestada"]=false;}
		 require("../../../funciones/conexion_v2.php");
		 
		
		 
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
				$cons_A="SELECT * FROM encuestas_pregunta_hija WHERE id_encuesta='$id_encuesta' AND id_pregunta='$id_pregunta' ORDER by posicion";
				if(DEBUG){ echo"--->$cons_A<br>";}
				$sql_A=$conexion_mysqli->query($cons_A)or die("Alternativas". $conexion_mysqli->error);
				$num_alternativas=$sql_A->num_rows;

				if($num_alternativas>0)
				{
					$action="guarda_resultado.php";
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
			
		}
		$sql->free();
		@mysql_close($conexion);
		$conexion_mysqli->close();
   }
//////////////////////+++++++/////////////////////////
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Contestar | Encuesta</title>
	<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:88px;
	z-index:1;
	left: 5%;
	top: 205px;
}
#boton {
	text-align:center;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:45px;
	z-index:2;
	left: 30%;
	top: 107px;
	text-align: center;
	font-size: medium;
	font-weight: bold;
	border: thin dashed #1E78C3;
}
#apDiv3 {
	position:absolute;
	width:70%;
	height:31px;
	z-index:3;
	left: 15%;
	top: 163px;
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
<h1 id="banner">Contestar Encuesta</h1>
<div id="link"><br />
<a href="../index.php" class="button">Volver a Encuestas</a><br />
<br />
</div>
<div id="apDiv1">
<form action="<?php echo $action;?>" method="post" id="frm">
<input name="id_encuesta" type="hidden" value="<?php echo $id_encuesta;?>" />
<input name="id_usuario" type="hidden" value="<?php echo $id_usuario; ?>" />
<input name="tipo_usuario" type="hidden" value="<?php echo $tipo_usuario;?>" />

<input name="sede_evaluar" type="hidden" value="<?php echo $sede_evaluar;?>" />
<input name="semestre_evaluar" type="hidden" value="<?php echo $semestre_evaluar;?>" />
<input name="year_evaluar" type="hidden" value="<?php echo $year_evaluar;?>" />

	   <?php
	   if($continuar)
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
       ?>
       <div id="boton">
        <a href="#" class="button_R" onclick="CONFIRMAR();">Finalizar Encuesta</a>
       </div>
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
<div id="apDiv2">Encuesta cod:<?php echo $id_encuesta?><br> 
  &quot;<?php echo $encuesta_nombre; ?>
</div>
<div id="apDiv3"><?php echo $encuesta_descripcion;?></div>
</body>
</html>