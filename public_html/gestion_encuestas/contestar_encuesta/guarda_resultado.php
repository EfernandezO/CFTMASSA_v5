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
if(isset( $_SESSION["ENCUESTA"]["contestada"]))
{
	if(!$_SESSION["ENCUESTA"]["contestada"])
	{
		if($_POST)
		{ $continuar=true;}
		else
		{ $continuar=false; if(DEBUG){ echo"NO POST<br>";}}
	}
	else
	{ $continuar=false; if(DEBUG){ echo"Ya Contestada...<br>";}}
}
else
{ $continuar=false;}
//*******************************************************//
if($continuar)
{
	require("../../../funciones/conexion_v2.php");
	if(!DEBUG){$_SESSION["ENCUESTA"]["contestada"]=true;}
	$id_encuesta=mysqli_real_escape_string($conexion_mysqli, $_POST["id_encuesta"]);
	//$array_preguntas=$_POST["pregunta"];
	$ARRAY_RESPUESTAS=$_POST["RESPUESTA"];
	$ARRAY_RESPUESTA_ANEXA=$_POST["RESPUESTA_ANEXA"];
	$fecha_hora_actual=date("Y-m-d H:i:s");
	
	$id_usuario=mysqli_real_escape_string($conexion_mysqli, $_POST["id_usuario"]);
	$tipo_usuario=mysqli_real_escape_string($conexion_mysqli, $_POST["tipo_usuario"]);
	$sede_evaluar=mysqli_real_escape_string($conexion_mysqli, $_POST["sede_evaluar"]);
	$semestre_evaluar=mysqli_real_escape_string($conexion_mysqli, $_POST["semestre_evaluar"]);
	$year_evaluar=mysqli_real_escape_string($conexion_mysqli, $_POST["year_evaluar"]);
	
	if(DEBUG){ var_dump($_POST); echo"<br><br>";}
	
	
	$id_usuario_encuesta=$id_usuario;
	$tipo_usuario_encuesta=$tipo_usuario;
	
	
	if(isset($ARRAY_RESPUESTAS))
	{
		
		foreach($ARRAY_RESPUESTAS as $aux_id_pregunta => $array_valores)
		{
			if(DEBUG){ echo"id_pregunta: $aux_id_pregunta ";}
			foreach($array_valores as $aux_tipo_pregunta => $aux_respuesta)
			{
				$aux_respuesta=mysqli_real_escape_string($conexion_mysqli, $aux_respuesta);
				$aux_tipo_pregunta=mysqli_real_escape_string($conexion_mysqli, $aux_tipo_pregunta);
				$aux_id_pregunta=mysqli_real_escape_string($conexion_mysqli, $aux_id_pregunta);
				if(DEBUG){var_dump($aux_respuesta);}
				if(DEBUG){ echo"tipo pregunta: $aux_tipo_pregunta _> Respuesta: $aux_respuesta<br>";}
				
				switch($aux_tipo_pregunta)
				{
					case"alternativa":
						$guardar=true;
						
						if(isset($ARRAY_RESPUESTA_ANEXA[$aux_respuesta]))
						{
							if(DEBUG){ echo"Hay respuesta Anexa<br>";}
							$aux_respuesta_anexa=mysqli_real_escape_string($conexion_mysqli, $ARRAY_RESPUESTA_ANEXA[$aux_respuesta]);
							$campos="id_usuario, tipo_usuario, fecha_generacion, id_encuesta, id_pregunta, id_alternativa, respuesta_directa, sede_evaluar, semestre_evaluar, year_evaluar";
							$valores="'$id_usuario_encuesta', '$tipo_usuario_encuesta', '$fecha_hora_actual', '$id_encuesta', '$aux_id_pregunta', '$aux_respuesta', '$aux_respuesta_anexa', '$sede_evaluar', '$semestre_evaluar', '$year_evaluar'";
						}
						else{
							if(DEBUG){ echo"NO hay respuesta Anexa<br>";}
							$campos="id_usuario, tipo_usuario, fecha_generacion, id_encuesta, id_pregunta, id_alternativa, sede_evaluar, semestre_evaluar, year_evaluar";
							$valores="'$id_usuario_encuesta', '$tipo_usuario_encuesta', '$fecha_hora_actual', '$id_encuesta', '$aux_id_pregunta', '$aux_respuesta', '$sede_evaluar', '$semestre_evaluar', '$year_evaluar'";	
							}
						
						
						break;
					case"directa":
						$guardar=true;
						$campos="id_usuario, tipo_usuario, fecha_generacion, id_encuesta, id_pregunta, respuesta_directa, sede_evaluar, semestre_evaluar, year_evaluar";
						$valores="'$id_usuario_encuesta', '$tipo_usuario_encuesta', '$fecha_hora_actual', '$id_encuesta', '$aux_id_pregunta', '$aux_respuesta', '$sede_evaluar', '$semestre_evaluar', '$year_evaluar'";
						break;
					default:
						$guardar=false;	
				}
				
				if($guardar)
				{
					$cons_INR="INSERT INTO encuestas_resultados ($campos) VALUES ($valores)";
					if(DEBUG){ echo"-->$cons_INR<br><br>";}
					else{ $conexion_mysqli->query($cons_INR)or die($conexion_mysqli->error);}
				}
				else
				{ if(DEBUG){ echo"No guardar tipo incorrecto de pregunta<br>";}}
			}
		}
		 ///////////////registr evento/////////////////////
		 include("../../../funciones/VX.php");
		 $evento="Contesta Encuesta id_encuesta: $id_encuesta para tipo_usuario: $tipo_usuario_encuesta id_usuario: $id_usuario_encuesta";
		 REGISTRA_EVENTO($evento);
		 ///////////////////////////////////////////////////
	}
	else
	{
		if(DEBUG){ echo"Sin Respuestas...<br>";}
	}
	
	@mysql_close($conexion);
	$conexion_mysqli->close();
	$url="finaliza_encuesta.php?error=C0";
	
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
}
else
{
	if(DEBUG){ echo"NO continuar<br>";}
	else{ header("location: ../index.php");}
}

?>