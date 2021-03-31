<?php
//-----------------------------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(true);
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Planificaciones->nuevoRegistro");
	$O->PERMITIR_ACCESO_USUARIO();
//-----------------------------------------//
if($_GET)
{
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	$id_carrera=$_GET["id_carrera"];
	$cod_asignatura=$_GET["asignatura"];
	$jornada=$_GET["jornada"];
	$grupo_curso=$_GET["grupo"];
	$sede=$_GET["sede"];
	$semestre=$_GET["semestre"];
	$year=$_GET["year"];
	require("../../../../funciones/conexion_v2.php");
	
	$cons_P="SELECT * FROM programa_estudio WHERE id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura'";
	$sqli_P=$conexion_mysqli->query($cons_P)or die($conexion_mysqli->error);
	$num_registros=$sqli_P->num_rows;
	if(DEBUG){ echo"<br>--> $cons_P <br>registros: $num_registros<br>";}
	
	$ARRAY_PROGRAMA=array();
	
	
	if($num_registros>0)
	{
		while($P=$sqli_P->fetch_assoc())
		{
			$id_programa=$P["id_programa"];
			$numero_unidad=$P["numero_unidad"];
			$nombre_unidad=$P["nombre_unidad"];
			$cantidad_horas=$P["cantidad_horas"];
			$contenido=$P["contenido"];
			
			$ARRAY_PROGRAMA[$id_programa]["cantidad_horas"]=$cantidad_horas;
			$ARRAY_PROGRAMA[$id_programa]["numero_unidad"]=$numero_unidad;
			$ARRAY_PROGRAMA[$id_programa]["nombre_unidad"]=$nombre_unidad;
			$ARRAY_PROGRAMA[$id_programa]["contenido"]=$contenido;
		}
	}
	
	
	$sqli_P->free();
	
	////-----------------------------------------------------------//
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<title>Agrega Registro a Planificacion</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 77px;
}
</style>

<script language="javascript">
function CONFIRMAR()
{
	document.getElementById('frm').submit();
}
</script>
</head>

<body>
<h1 id="banner">Administrador -  Nueva Planificaciones V1.0</h1>
<div id="apDiv1">
<form action="../../contenidos/nueva/nueva_planificacion_2.php" method="post" id="frm">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="5">Que Contenido Planificar&aacute;
        <input name="id_carrera" type="hidden" id="id_carrera" value="<?php echo $id_carrera;?>" />
        <input name="cod_asignatura" type="hidden" id="cod_asignatura" value="<?php echo $cod_asignatura;?>" />
        <input name="semestre" type="hidden" id="semestre" value="<?php echo $semestre;?>" />
        <input name="year" type="hidden" id="year" value="<?php echo $year;?>" />
        <input name="sede" type="hidden" id="sede" value="<?php echo $sede;?>" />
        <input name="jornada" type="hidden" id="jornada" value="<?php echo $jornada;?>" />
        <input name="grupo_curso" type="hidden" id="grupo_curso" value="<?php echo $grupo_curso;?>" /></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>-</td>	
      <td>Horas</td>
      <td>N. Unidad</td>
      <td>Nombre Unidad</td>
      <td>Contenido</td>
    </tr>
    <?php
	if(count($ARRAY_PROGRAMA)>0)
	{
		$mostrar_boton=true;
		foreach($ARRAY_PROGRAMA as $id_programax=>$array_datos)
		{
			$aux_cantidad_horas=$array_datos["cantidad_horas"];
			$aux_contenido=$array_datos["contenido"];
			$aux_numero_unidad=$array_datos["numero_unidad"];
			$aux_nombre_unidad=$array_datos["nombre_unidad"];
			
			///busco si programa ya lo he usado
			if($id_programax>0)
			{
				$cons_B="SELECT COUNT(id_programa) FROM planificaciones WHERE id_funcionario='$id_usuario_actual' AND id_carrera='$id_carrera' AND semestre='$semestre' AND year='$year' AND sede='$sede' AND cod_asignatura='$cod_asignatura' AND id_programa='$id_programax' AND jornada='$jornada' AND grupo='$grupo_curso'";
				$sqli_B=$conexion_mysqli->query($cons_B)or die($conexion_mysqli->error);
				$B=$sqli_B->fetch_row();
				$coincidencias=$B[0];
				if(empty($coincidencias)){ $coincidencias=0;}
				
				if($coincidencias>0)
				{ $programa_disponible=false;}
				else{ $programa_disponible=true;}
				if(DEBUG){ echo"-->$cons_B<br> Coincidencias: $coincidencias<br>";}
				
			}
			else
			{ $programa_disponible=true;}
			
			echo'<tr>
					<td>';
			if($programa_disponible){echo'<input name="programa" type="radio" value="'.$id_programax.'" />';}
			 echo'</td>
					<td>'.$aux_cantidad_horas.'</td>
					<td>'.$aux_numero_unidad.'</td>
					<td>'.$aux_nombre_unidad.'</td>
					<td>'.$aux_contenido.'</td>
				</tr>';
		}
	}
	else
	{
		$mostrar_boton=false;
		echo'<tr>
				<td colspan="5">Sin Programa de Estudio Creado, no se puede Continuar</td>
			</tr>';
	}
	
	$conexion_mysqli->close();
	mysql_close($conexion);
	?>
    <tr>
        <td><input name="programa" type="radio" value="0"  checked="checked"/></td>
        <td>---</td>
        <td>---</td>
        <td>otro</td>
        <td>otro</td>
    </tr>
    </tbody>
  </table>
  <p><br />
    </p>
  <p><?php if($mostrar_boton){ ?><a href="#" class="button_G" onclick="CONFIRMAR();">Continuar</a><?php } ?></p>
 </form> 
</div>
</body>
</html>