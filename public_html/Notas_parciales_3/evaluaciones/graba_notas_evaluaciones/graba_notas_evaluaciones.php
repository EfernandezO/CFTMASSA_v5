<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
	define("DEBUG",false);
	set_time_limit(120);
//-----------------------------------------//	
if($_POST)
{
	require("../../../../funciones/conexion_v2.php");
	include("../../../../funciones/VX.php");
	$fecha_hora_actual=date("Y-m-d H:i:s");
	if(DEBUG){ var_dump($_POST);}
		$ARRAY_EVALUACIONES=$_POST["evaluacion"];
		$sede=$_POST["sede"];
		$id_carrera=$_POST["id_carrera"];
		$cod_asignatura=$_POST["cod_asignatura"];
		$jornada=$_POST["jornada"];
		$grupo=$_POST["grupo"];
		$semestre=$_POST["semestre"];
		$year=$_POST["year"];
		$id_usuario_actual=$_SESSION["USUARIO"]["id"];
		
		$fecha_actual=date("Y-m-d");
		$error=0;
	
	foreach($ARRAY_EVALUACIONES as $id_evaluacion => $ARRAY_1)
	{
		echo"$id_evaluacion -><br>";
		foreach($ARRAY_1 as $id_alumno => $nota_evaluacion)
		{
			$observacion="";
			echo"$id_alumno -> $nota_evaluacion<br>";
			$nota_evaluacion=trim($nota_evaluacion);
			$nota_evaluacion=str_replace(",",".",$nota_evaluacion);
			$nota_valida=VALIDA_NOTA($nota_evaluacion);
			
				if(DEBUG){ echo"<strong>NOTA $nota_evaluacion ES VALIDA</strong><br>";}
				//--------------------------
				//hay nota previa
				$nota_antigua="";
				$cons="SELECT nota FROM notas_parciales_registros WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND id_evaluacion='$id_evaluacion' AND cod_asignatura='$cod_asignatura' AND semestre='$semestre' AND year='$year'";
				$sql=$conexion_mysqli->query($cons)or die("VERIFICAR_EXISTE_NOTA".$conexion_mysqli->error);
				$num_coincidencias=$sql->num_rows;
				if($num_coincidencias>0)
				{
					$D=$sql->fetch_assoc();
					$nota_antigua=$D["nota"];
					$observacion.=' [M]';
				}
				if(empty($num_coincidencias)){ $num_coincidencias=0;}
				if(DEBUG){ echo"$cons<br>Num Coincidencias: $num_coincidencias<br>";}
				$sql->free();
				if($num_coincidencias>0)
				{ $hay_nota_previamente=true;}
				else
				{ $hay_nota_previamente=false;}
				
				
				//-----------------------------------------------------------------//
				//$hay_nota_previamente=EXISTE_NOTA($id_evaluacion, $id_carrera, $id_alumno, $cod_asignatura, $semestre, $year);
				if($hay_nota_previamente)
				{
					if($nota_valida)
					{
						if($nota_antigua!=$nota_evaluacion)
						{
							///actualizar registro
							$cons_UNE="UPDATE notas_parciales_registros SET nota='$nota_evaluacion', observacion=CONCAT(observacion, '$observacion'), fecha_generacion='$fecha_hora_actual', cod_user='$id_usuario_actual' WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND id_evaluacion='$id_evaluacion' AND cod_asignatura='$cod_asignatura' AND semestre='$semestre' AND year='$year' LIMIT 1";
							if(DEBUG){ echo"$cons_UNE<br>";}
							else{ $conexion_mysqli->query($cons_UNE)or die("Update".$conexion_mysqli->error);}
							
							 /////Registro ingreso///
							 $evento="Modifica Nota Parcial V3 ID alumno $id_alumno id carrera: $id_carrera cod_asignatura: $cod_asignatura id_evaluacion: $id_evaluacion cambio nota de [$nota_antigua -> $nota_evaluacion]";
							 REGISTRA_EVENTO($evento);
							 ///////////////////////
						}
					}
					else
					{ if(DEBUG){ echo"Nota invalida para Actualizar...<br>";} }
				}
				else
				{
					///insertar registro
					if($nota_valida)
					{
						$campos="id_alumno, id_carrera, id_evaluacion, cod_asignatura, semestre, year, nota, observacion, fecha_generacion, cod_user";	
						$cons_NE="INSERT INTO notas_parciales_registros ($campos) VALUES('$id_alumno', '$id_carrera', '$id_evaluacion', '$cod_asignatura', '$semestre', '$year', '$nota_evaluacion', '$observacion', '$fecha_hora_actual', '$id_usuario_actual')";
						if(DEBUG){ echo"----$cons_NE<br>";}
						else{ $conexion_mysqli->query($cons_NE)or die("Insertar".$conexion_mysqli->error);}
						
						 /////Registro ingreso///
						 $evento="Agrega Nota Parcial V3 ID alumno $id_alumno id carrera: $id_carrera cod_asignatura: $cod_asignatura id_evaluacion: $id_evaluacion nota ingresada [$nota_evaluacion]";
						 REGISTRA_EVENTO($evento);
						 ///////////////////////
					}
					else
					{if(DEBUG){ echo"Nota no Valida para Insertar...<br>";}}
				}
		}
	}
	@mysql_close($conexion);
	$conexion_mysqli->close();
	
	
	$url="../ver_evaluaciones.php?error=$error&sede=".base64_encode($sede)."&id_carrera=".base64_encode($id_carrera)."&jornada=".base64_encode($jornada)."&grupo_curso=".base64_encode($grupo)."&cod_asignatura=".base64_encode($cod_asignatura)."&semestre=".base64_encode($semestre)."&year=".base64_encode($year);
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
}
else
{
	if(DEBUG){ echo"NO post <br>";}
	else{ header("location: ../index.php");}
}
///////////////////////////////
function VALIDA_NOTA($nota_evaluacion)
{

	if(is_numeric($nota_evaluacion))
	{
		if(($nota_evaluacion>0)and($nota_evaluacion<=7))
		{ $es_valida=true;}
		else
		{ $es_valida=false;}
	}
	else
	{ $es_valida=false;}
	
	return($es_valida);
}
?>