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

if(isset($_GET["id_encuesta"]))
{
	$id_encuesta=base64_decode($_GET["id_encuesta"]);
	$id_usuario_evaluar=base64_decode($_GET["id_usuario_evaluar"]);
	$id_carrera_evaluar=base64_decode($_GET["id_carrera_evaluar"]);
	$semestre_evaluar=base64_decode($_GET["semestre_evaluar"]);
	$year_evaluar=base64_decode($_GET["year_evaluar"]);
	$sede_evaluar=base64_decode($_GET["sede_evaluar"]);
	
	
	if(is_numeric($id_encuesta))
	{ $continuar=true;}
	else{ $continuar=false;}
	
	
}
else
{ $continuar=false;}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<title>Ver Participantes Encuesta</title>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 89px;
}
</style>

</head>

<body>
<h1 id="banner">Administrador - Ver Participantes</h1>
<div id="Layer1">
  <table width="100%" border="1" align="center">
  <thead>
  	<th colspan="8">Participantes</th>
  </thead>
    <tr>
      <td>N</td>
      <td>Sede</td>
      <td>Rut</td>
      <td>Nombre</td>
      <td>Apellido P</td>
      <td>Apellido M</td>
      <td>Carrera</td>
      <td>Fecha</td>
    </tr>
    <tbody>
    <?php
	if($continuar)
	{
		require("../../../funciones/conexion_v2.php");
		include("../../../funciones/VX.php");
		$evento="Revisa Participantes de encuesta (Evaluacion Docente) id_encuesta: $id_encuesta";
		REGISTRA_EVENTO($evento);
		
		
		$cons="SELECT DISTINCT(id_usuario) FROM encuestas_resultados WHERE id_encuesta='$id_encuesta' AND semestre_evaluar='$semestre_evaluar' AND year_evaluar='$year_evaluar' AND id_carrera_evaluar='$id_carrera_evaluar' AND sede_evaluar='$sede_evaluar' AND id_usuario_evaluar='$id_usuario_evaluar'";
		$sql=$conexion_mysqli->query($cons)or die("MAIN".$conexion_mysqli->error."-->$cons");
		$num_participantes_alumno=$sql->num_rows;
		if(DEBUG){ echo"---->$cons<br>Num participantes: $num_participantes_alumno<br>";}
		if($num_participantes_alumno>0)
		{
			$contador_alumno=0;
			while($P=$sql->fetch_row())
			{
				$id_participante=$P[0];
				if(DEBUG){ echo"$id_participante<br>";}
				$cons_A="SELECT * FROM alumno WHERE id='$id_participante' LIMIT 1";
				$sql_A=$conexion_mysqli->query($cons_A)or die("Datos".$conexion_mysqli->error);
				
				
				$A=$sql_A->fetch_assoc();
					$contador_alumno++;
					$A_rut=$A["rut"];
					$A_nombre=$A["nombre"];
					$A_apellido_P=$A["apellido_P"];
					$A_apellido_M=$A["apellido_M"];
					$A_carrera=$A["carrera"];
					$A_sede=$A["sede"];
					
					///------------------------------------------//
					$cons_FE="SELECT DISTINCT(fecha_generacion) FROM encuestas_resultados WHERE id_encuesta='$id_encuesta' AND id_usuario='$id_participante' LIMIT 1";
					$sqli_FE=$conexion_mysqli->query($cons_FE)or die("Fechas".$conexion_mysqli->error);
					$FF=$sqli_FE->fetch_row();
						$F_fecha=$FF[0];
					$sqli_FE->free();	
					///------------------------------------------------//
					$A_fecha=$F_fecha;
					
					echo'<tr>
						  <td>'.$contador_alumno.'</td>
						  <td>'.$A_sede.'</td>
						  <td>'.$A_rut.'</td>
						  <td>'.$A_nombre.'</td>
						  <td>'.$A_apellido_P.'</td>
						  <td>'.$A_apellido_M.'</td>
						  <td>'.$A_carrera.'</td>
						  <td>'.$A_fecha.'</td>
						</tr>';
				$sql_A->free();
				
			}
			$sql->free();
		}
		else
		{
			echo'<tr>
					<td colspan="9">Sin Alumnos Participantes</td>
					</tr>';
		}
		@mysql_close($conexion);
		$conexion_mysqli->close();
	}
		?>
        </tbody>
        </table>
</div>
</body>
</html>
