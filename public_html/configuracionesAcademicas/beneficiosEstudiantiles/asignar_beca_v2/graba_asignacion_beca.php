<?php
//-----------------------------------------//
	require("../../../Edicion_carreras/OKALIS/seguridad.php");
	require("../../../Edicion_carreras/OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	
if($_POST)
{
	if(DEBUG){ var_dump($_POST);}
		$error="G0";
		$estado="por_asignar";
		$fecha_actual=date("Y-m-d");
		$id_usuario_actual=$_SESSION["USUARIO"]["id"];

		$id_carrera=$_POST["id_carrera"];
		$carrera=$_POST["carrera"];
		$sede=$_POST["sede"];
		$semestre=$_POST["semestre"];
		$year=$_POST["year"];
		$jornada=$_POST["jornada"];
		$grupo=$_POST["grupo"];
		$situacion=$_POST["situacion"];
		$niveles=$_POST["niveles"];
		$ingreso=$_POST["ingreso"];
		
		$array_id_beca=$_POST["id_beca"];
		$array_beca_valor_porcentaje=$_POST["beca_valor_porcentaje"];
		$array_beca_glosa=$_POST["beca_glosa"];
		
		$campos="id_alumno, id_beca, id_carrera, semestre, year, valor, glosa, estado, fecha_generacion, cod_user_creador";
		include("../../../funciones/conexion.php");
		include("../../../funciones/funciones_sistema.php");
		foreach($array_id_beca as $id_alumno =>$id_beca)
		{
			if(DEBUG){ echo"$id_alumno -> $id_beca<br>";}
			if(empty($id_beca)){ $id_beca=0;}
			else
			{
				//--------------------------------------------------------------------------------------//
				$verificacion_beca=VERIFICAR_BECA($id_alumno, $id_carrera, $semestre, $year, $id_beca);
				//--------------------------------------------------------------------------------------//
			}
			if(($id_beca>0)and($verificacion_beca))
			{
				$error=0;
				$aux_valor_beca=$array_beca_valor_porcentaje[$id_alumno];
				$aux_beca_glosa=$array_beca_glosa[$id_alumno];
				if(DEBUG){ echo"Asignar Beca<br>";}
				
				$valores="'$id_alumno', '$id_beca', '$id_carrera', '$semestre', '$year', '$aux_valor_beca', '$aux_beca_glosa', '$estado', '$fecha_actual', '$id_usuario_actual'";
				
				$CONS_IN="INSERT INTO beca_asignaciones ($campos) VALUES ($valores)";
				if(DEBUG){ echo"---->$CONS_IN<br>";}
				else{ mysql_query($CONS_IN)or die("--->".mysql_error());}
				
			}
			else
			{ if(DEBUG){ echo"Sin Beca que asignar<br>";}}
		}
		mysql_close($conexion);
		
		$url="seleccion_alumnos_asignacion.php?error=$error&id_carrera=".base64_encode($id_carrera)."&carrera=".base64_encode($carrera)."&sede=".base64_encode($sede)."&semestre=".base64_encode($semestre)."&year=".base64_encode($year)."&jornada=".base64_encode($jornada)."&grupo=".base64_encode($grupo)."&situacion=".base64_encode($situacion)."&niveles=".base64_encode($niveles)."&ingreso=".base64_encode($ingreso);
		if(DEBUG){ echo"URL: $url<br>";}
		else{ header("location: $url");}
		
}
else
{header("location: index.php");}
?>