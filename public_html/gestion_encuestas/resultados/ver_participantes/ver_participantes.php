<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Gestion de Encuestas_resultados_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

if(isset($_GET["id_encuesta"]))
{
	$id_encuesta=$_GET["id_encuesta"];
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
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<title>Ver Participantes Encuesta</title>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 115px;
}
</style>

</head>

<body>
<h1 id="banner">Administrador - Ver Participantes</h1>
<div id="link"><br />
<a href="../ver_resultados.php?id_encuesta=<?php echo $id_encuesta;?>" class="button">Volver</a></div>
<div id="Layer1">
  <table width="100%" border="1" align="center">
  <thead>
  	<th colspan="9">Participantes Alumnos</th>
  </thead>
    <tr>
      <td>N</td>
      <td>Sede</td>
      <td>Rut</td>
      <td>Nombre</td>
      <td>Apellido P</td>
      <td>Apellido M</td>
      <td>Carrera</td>
      <td>Tipo</td>
      <td>Fecha</td>
    </tr>
    <tbody>
    <?php
	if($continuar)
	{
		require("../../../../funciones/conexion_v2.php");
		include("../../../../funciones/VX.php");
		$evento="Revisa Participantes de encuesta id_encuesta: $id_encuesta";
		REGISTRA_EVENTO($evento);
		
		
		$cons="SELECT DISTINCT(id_usuario) FROM encuestas_resultados WHERE id_encuesta='$id_encuesta' AND tipo_usuario='alumno'";
		$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		$num_participantes_alumno=$sql->num_rows;
		
		if($num_participantes_alumno>0)
		{
			while($P=$sql->fetch_row())
			{
				$id_participante=$P[0];
				if(DEBUG){ echo"$id_participante<br>";}
				$cons_A="SELECT * FROM alumno WHERE id='$id_participante' LIMIT 1";
				$sql_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
				
				$contador_alumno=0;
				$A=$sql_A->fetch_assoc();
					$contador_alumno++;
					$A_rut=$A["rut"];
					$A_nombre=$A["nombre"];
					$A_apellido_P=$A["apellido_P"];
					$A_apellido_M=$A["apellido_M"];
					$A_carrera=$A["carrera"];
					$A_sede=$A["sede"];
					
					///------------------------------------------//
					$cons_FE="SELECT DISTINCT(fecha_generacion) FROM encuestas_resultados WHERE id_encuesta='$id_encuesta' AND id_usuario='$id_participante' AND tipo_usuario='alumno'";
					$sqli_FE=$conexion_mysqli->query($cons_FE)or die($conexion_mysqli->error);
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
						  <td>Alumno</td>
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
		
		?>
        </tbody>
        </table>
        <br />
         <table width="100%" border="1" align="center">
  <thead>
  	<th colspan="9">Participantes Funcionarios</th>
  </thead>
    <tr>
      <td>N</td>
      <td>Sede</td>
      <td>Rut</td>
      <td>Nombre</td>
      <td>Apellido</td>
      <td>Tipo</td>
      <td>Fecha</td>
    </tr>
    <tbody>
        <?php
		
		$cons="SELECT DISTINCT(id_usuario) FROM encuestas_resultados WHERE id_encuesta='$id_encuesta' AND tipo_usuario='funcionario'";
		$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		$num_participantes_funcionarios=$sql->num_rows;
		if($num_participantes_funcionarios>0)
		{
			while($P=$sql->fetch_row())
			{
				$id_participante=$P[0];
				if(DEBUG){ echo"$id_participante<br>";}
				$cons_F="SELECT * FROM personal WHERE id='$id_participante' LIMIT 1";
				$sql_F=$conexion_mysqli->query($cons_F)or die($conexion_mysqli->error);
				$contador_funcionarios=0;
				$F=$sql_F->fetch_assoc();
					$contador_funcionarios++;
					$F_rut=$F["rut"];
					$F_nombre=$F["nombre"];
					$F_apellido=$F["apellido"];
					$F_sede=$F["sede"];
					///------------------------------------------//
					$cons_FE="SELECT DISTINCT(fecha_generacion) FROM encuestas_resultados WHERE id_encuesta='$id_encuesta' AND id_usuario='$id_participante' AND tipo_usuario='funcionario'";
					$sqli_FE=$conexion_mysqli->query($cons_FE)or die($conexion_mysqli->error);
					$FF=$sqli_FE->fetch_row();
						$F_fecha=$FF[0];
					$sqli_FE->free();	
					///------------------------------------------------//
					echo'<tr>
							<td>'.$contador_funcionarios.'</td>
							<td>'.$F_sede.'</td>
							<td>'.$F_rut.'</td>
							<td>'.$F_nombre.'</td>
							<td>'.$F_apellido.'</td>
							<td>funcionario</td>
							<td>'.$F_fecha.'</td>
						 </tr>';
					
				$sql_F->free();
			}
		
		}
		else
		{
			echo'<tr>
					<td colspan="7">Sin Funcionarios Participantes</td>
					</tr>';
		}
		
		
		$sql->free();
	?>
    </tbody>
  </table>
   <br />
   <table width="100%" border="1" align="center">
  <thead>
  	<th colspan="11">Participantes exalumno</th>
  </thead>
    <tr>
     <td>N</td>
      <td>Sede</td>
      <td>Carrera</td>
      <td>Sexo</td>
      <td>Rut</td>
      <td>Nombre</td>
      <td>Apellido P</td>
      <td>Apellido M</td>
      <td>Year Egreso</td>
      <td>Tipo</td>
      <td>Fecha</td>
    </tr>
    <tbody>
        <?php
		$consx="SELECT DISTINCT(id_usuario) FROM encuestas_resultados WHERE id_encuesta='$id_encuesta' AND tipo_usuario='ex_alumno'";
		$sqlx=$conexion_mysqli->query($consx)or die($conexion_mysqli->error);
		$num_participantes_exalumnos=$sqlx->num_rows;
		$ARRAY_RESULTADOS=array();
		if($num_participantes_exalumnos>0)
		{
			$contador_exalumno=0;
			while($EX1=$sqlx->fetch_row())
			{
				$id_participante=$EX1[0];
				if(DEBUG){ echo"$id_participante<br>";}
				$cons_x2="SELECT * FROM alumno WHERE id='$id_participante' LIMIT 1";
				$sql_x2=$conexion_mysqli->query($cons_x2)or die($conexion_mysqli->error);
				
				$EX2=$sql_x2->fetch_assoc();
				
					$contador_exalumno++;
					$A_rut=$EX2["rut"];
					$A_nombre=$EX2["nombre"];
					$A_apellido_P=$EX2["apellido_P"];
					$A_apellido_M=$EX2["apellido_M"];
					$A_carrera=$EX2["carrera"];
					$A_sede=$EX2["sede"];
					$A_sexo=$EX2["sexo"];
					$A_year_egreso=$EX2["year_egreso"];
					
					if(isset($ARRAY_RESULTADOS[$A_sede][$A_sexo])){ $ARRAY_RESULTADOS[$A_sede][$A_sexo]++;}
					else{ $ARRAY_RESULTADOS[$A_sede][$A_sexo]=1;}
					
					///------------------------------------------//
					$cons_FE="SELECT DISTINCT(fecha_generacion) FROM encuestas_resultados WHERE id_encuesta='$id_encuesta' AND id_usuario='$id_participante' AND tipo_usuario='ex_alumno'";
					$sqli_FE=$conexion_mysqli->query($cons_FE)or die($conexion_mysqli->error);
					$FF=$sqli_FE->fetch_row();
						$F_fecha=$FF[0];
					$sqli_FE->free();	
					///------------------------------------------------//
					
					echo'<tr>
						  <td>'.$contador_exalumno.'</td>
						  <td>'.$A_sede.'</td>
						  <td>'.$A_carrera.'</td>
						  <td>|'.$A_sexo.'|</td>
						  <td>'.$A_rut.'</td>
						  <td>'.$A_nombre.'</td>
						  <td>'.$A_apellido_P.'</td>
						  <td>'.$A_apellido_M.'</td>
						  <td>'.$A_year_egreso.'</td>
						  <td>ex-alumno</td>
						  <td>'.$F_fecha.'</td>
						</tr>';
					
				$sql_x2->free();
			}
		
		}
		else
		{
			echo'<tr>
					<td colspan="9">Sin Exalumnos Participantes</td>
					</tr>';
		}
		echo'
		<tr>
			<td>Talca</td>
			<td>'.$ARRAY_RESULTADOS["Talca"]["F"].' (Mujeres)</td>
			<td>'.$ARRAY_RESULTADOS["Talca"]["M"].' (Hombres)</td>
		</tr>
			<tr>
			<td>Linares</td>
			<td>'.$ARRAY_RESULTADOS["Linares"]["F"].' (Mujeres)</td>
			<td>'.$ARRAY_RESULTADOS["Linares"]["M"].' (Hombres)</td>
		</tr>
		</tbody>
					<table>';
		$sqlx->free();
		@mysql_close($conexion);
		$conexion_mysqli->close();
	}
	var_dump($ARRAY_RESULTADOS);
	?>
</div>
</body>
</html>
