<?php
//--------------CLASS_okalis------------------//
	require("../../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../../funciones/";
	$O->clave_del_archivo=md5("control_horario_docente");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$continuar=false;
if($_GET)
{
	if(DEBUG){ var_dump($_GET);}
	
	$H_id=base64_decode($_GET["H_id"]);
	$sede=base64_decode($_GET["sede"]);
	$semestre=base64_decode($_GET["semestre"]);
	$year=base64_decode($_GET["year"]);
	$id_carrera=base64_decode($_GET["id_carrera"]);
	$jornada=base64_decode($_GET["jornada"]);
	$cod_asignatura=base64_decode($_GET["cod_asignatura"]);
	$grupo=base64_decode($_GET["grupo"]);
	$fecha_clase=base64_decode($_GET["fecha_clase"]);
	$continuar=true;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../../../funciones/codificacion.php");?>
<title>Resumen Asistencia Alumnos</title>
<link rel="stylesheet" type="text/css" href="../../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 86px;
}
</style>
<script language="javascript">
function CONFIRMAR()
{
	var codigo="<?php echo date("dmYHis");?>";
	var respuesta;
	url='../registra_horario_personal_ind/lista_alumnos/eliminar_asistencia.php?sede=<?php echo base64_encode($sede);?>&semestre=<?php echo base64_encode($semestre);?>&year=<?php echo base64_encode($year);?>&id_carrera=<?php echo base64_encode($id_carrera);?>&jornada=<?php echo base64_encode($jornada);?>&cod_asignatura=<?php echo base64_encode($cod_asignatura);?>&grupo=<?php echo base64_encode($grupo);?>&fecha_clase=<?php echo base64_encode($fecha_clase);?>&H_id=<?php echo base64_encode($H_id);?>';
	respuesta=prompt("Para eliminar ingrese el siguiente codigo: "+codigo+" \n");
	if(respuesta==codigo)
	{window.location=url;}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Revisi&oacute;n Asistencia Alumnos</h1>
<div id="apDiv1">
<table width="100%" border="1">
<thead>
<tr>
	<th>N.</th>
    <th>Rut</th>
    <th>Nombre</th>
    <th>Apellido P</th>
    <th>Apellido M</th>
    <th>Fecha</th>
    <th>Fecha Clase</th>
    <th>Hora</th>
    <th>Asistencia</th>
    <th>id usuario</th>
</tr>
</thead>
<tbody>
<?php
require("../../../../../../funciones/conexion_v2.php");
require("../../../../../../funciones/funciones_sistema.php");
if($continuar)
{
	
	$cons_B="SELECT * FROM asistencia_alumnos WHERE semestre='$semestre' AND  year='$year' AND sede='$sede' AND id_carrera='$id_carrera' AND jornada='$jornada' AND cod_asignatura='$cod_asignatura' AND grupo='$grupo' AND fecha_clase='$fecha_clase' AND id_horario='$H_id'";
	if(DEBUG){ echo"--->$cons_B<br>";}		
	$sqli_B=$conexion_mysqli->query($cons_B)or die($conexion_mysqli->error);
	$num_alumnos=$sqli_B->num_rows;
	
	$SUMA_PRESENTES=0;
	$SUMA_AUSENTES=0;
	$SUMA_JUSTIFICADOS=0;
	
	if($num_alumnos>0)
	{
		$aux=0;
		while($A=$sqli_B->fetch_assoc())
		{
			$aux++;
			$A_fecha_clase=$A["fecha_clase"];
			$A_fecha=$A["fecha"];
			$A_hora=$A["hora"];
			$A_id_alumno=$A["id_alumno"];
			$A_asistencia=$A["asistencia"];
			$A_id_usuario_actual=$A["id_usuario_actual"];
			$A_id_horario=$A["id_horario"];
			$A_participantes_curso=$A["participantes_curso"];
			$A_horas_pedagogicas=$A["horas_pedagogicas"];
			$cons_A="SELECT rut, nombre, apellido_P, apellido_M FROM alumno WHERE id='$A_id_alumno' LIMIT 1";
			$sqli_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
			$AL=$sqli_A->fetch_assoc();
				$AL_rut=$AL["rut"];
				$AL_nombre=$AL["nombre"];
				$AL_apellido_P=$AL["apellido_P"];
				$AL_apellido_M=$AL["apellido_M"];
			$sqli_A->free();	
			
			if($A_asistencia=="presente"){ $SUMA_PRESENTES+=1; $color='#00aa00';}
			elseif($A_asistencia=="ausente"){ $SUMA_AUSENTES+=1; $color='#aa0000';}
			else{$SUMA_JUSTIFICADOS+=1; $color='#aaaa00';}
			
			echo'<tr>
					<td>'.$aux.'</td>
					<td>'.$AL_rut.'</td>
					<td>'.$AL_nombre.'</td>
					<td>'.$AL_apellido_P.'</td>
					<td>'.$AL_apellido_M.'</td>
					<td>'.$A_fecha_clase.'</td>
					<td>'.$A_fecha.'</td>
					<td>'.$A_hora.'</td>
					<td bgcolor="'.$color.'">'.$A_asistencia.'</td>
					<td align="center">'.NOMBRE_PERSONAL($A_id_usuario_actual).'</td>
				</tr>';
		}
	}	
	$sqli_B->free();
	$conexion_mysqli->close();
?>
</tbody>
<tr>
  <td colspan="10">(<?php echo $SUMA_PRESENTES;?>) Presentes - (<?php echo $SUMA_AUSENTES;?>) Ausentes - (<?php echo $SUMA_JUSTIFICADOS;?>) Justificados|----> Participantes curso [
    <?php if($A_participantes_curso=="0"){ echo"curso completo";}else{ echo"Grupo: $A_participantes_curso";}?>
    ]</td>
</tr>
<tr>
	<td colspan="10">(<?php echo $A_horas_pedagogicas;?>) Hrs. Pedagogicas Realizadas en esta clase</td>
</tr>
<tr>
  <td colspan="10"><a href="#" onclick="CONFIRMAR()">Eliminar este registro de asistencia</a></td>
</tr>
</table>
<?php }
else{ echo"NO continuar<br>";}
?>
</div>
</body>
</html>