<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Gestion de Encuestas_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_POST)
{
	if((isset($_POST["id_encuesta"]))and(isset($_POST["id_pregunta"])and(isset($_POST["id_pregunta_original"]))))
	{
		$id_encuesta=$_POST["id_encuesta"];
		$id_pregunta=$_POST["id_pregunta"];
		$id_pregunta_original=$_POST["id_pregunta_original"];
		
		if((is_numeric($id_encuesta))and(is_numeric($id_pregunta)))
		{ $continuar=true;}
		else
		{ $continuar=false;}
	}
}
else
{ $continuar=false;}

$error="I0";
$id_usuario_actual=$_SESSION["USUARIO"]["id"];
if($continuar)
{
	include("../../../../funciones/VX.php");
$evento="importa alternativas de encuesta id_encuesta:$id_encuesta  desde id_pregunta:$id_pregunta_original -> hacia id_pregunta: $id_pregunta";
REGISTRA_EVENTO($evento);

	if(DEBUG){ echo"Datos de llegada OK<br>";}
	require("../../../../funciones/conexion_v2.php");
	 $cons="SELECT * FROM encuestas_pregunta_hija WHERE id_encuesta='$id_encuesta' AND id_pregunta='$id_pregunta_original'";
	  
	   $sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	 	$num_registros=$sql->num_rows;
		 if(DEBUG){ echo"-->$cons<br>N.alternativas relacinadas $num_registros<br>";}
	   if($num_registros>0)
	   {
		   if(DEBUG){ echo"La pregunta de origen tiene alternativas, iniciando la importacion<br>";}
		   $contador=0;
			while($M=$sql->fetch_assoc())
			{
				$contador++;
				$id_pregunta_hija=$M["id_pregunta_hija"];
				$posicion=$M["posicion"];
				$contenido=$M["contenido"];
				$respuesta_anexa=$M["respuesta_anexa"];
				
				$cons_INP="INSERT INTO encuestas_pregunta_hija (id_encuesta, id_pregunta,posicion, respuesta_anexa, contenido, cod_user) VALUES ('$id_encuesta', '$id_pregunta', '$posicion','$respuesta_anexa', '$contenido', '$id_usuario_actual')";
				if(DEBUG){ echo"--->$cons_INP <br>";}
				else
				{
					if($conexion_mysqli->query($cons_INP))
					{ if(DEBUG){ echo"-> Correcto<br>";}}
					else
					{ $error="I1"; if(DEBUG){ echo"-> Error al importar<br>";}}
				}
			}
	   }
	   else
	   {
		   if(DEBUG){ echo"La pregunta de origen NO tiene alternativas, NO hacer importacion<br>";}
		   $error="I2";
		}
		$conexion_mysqli->close();
}
else
{
	if(DEBUG){ echo"No se puede continuar<br>";}
	$error="I3";
}


$url="importar_alternativas_3.php?error=$error";
header("location: $url")
?>
