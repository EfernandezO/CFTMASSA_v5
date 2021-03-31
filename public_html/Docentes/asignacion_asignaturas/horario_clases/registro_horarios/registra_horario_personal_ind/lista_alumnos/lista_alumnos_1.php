<?php
//-----------------------------------------//
	require("../../../../../../OKALIS/seguridad.php");
	require("../../../../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../../../../funciones/codificacion.php");?>
<title>Lista Alumnos</title>
<link rel="stylesheet" type="text/css" href="../../../../../../libreria_publica/jquery_treeview/demo/screen.css">
 <link rel="stylesheet" type="text/css" href="../../../../../../CSS/tabla_2.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 91px;
}
</style>
<script language="javascript">
function CONFIRMAR()
{
	var continuar=true;
	var opcion=document.getElementById('participantes_curso');
	var opcion_marcada=opcion.options[opcion.selectedIndex].value;
	
	var opcion2=document.getElementById('horas_pedagogicas');
	var opcion_marcada2=opcion2.options[opcion2.selectedIndex].value;
	
	
	if(opcion_marcada=="-1")
	{ continuar=false; alert("Seleccione participantes de esta clase");}
	
	if(opcion_marcada2=="-1")
	{ continuar=false; alert("Seleccione Hrs. Pedagogicas Realizadas");}

	if(continuar)
	{
		c=confirm('Confirmar asistencia...');
		if(c){document.getElementById('frm').submit();}
	}
}
function seleccionar_presente(){
   for (i=0;i<document.frm.elements.length;i++)
      if(document.frm.elements[i].type == "radio")
	  	if (document.frm.elements[i].value=="presente")
			document.frm.elements[i].checked=1
		else
			document.frm.elements[i].checked=0
} 
function seleccionar_ausente(){
    for (i=0;i<document.frm.elements.length;i++)
      if(document.frm.elements[i].type == "radio")
	  	if (document.frm.elements[i].value=="ausente")
			document.frm.elements[i].checked=1
		else
			document.frm.elements[i].checked=0
} 
function seleccionar_justificado(){
   for (i=0;i<document.frm.elements.length;i++)
      if(document.frm.elements[i].type == "radio")
	  	if (document.frm.elements[i].value=="justificado")
			document.frm.elements[i].checked=1
		else
			document.frm.elements[i].checked=0
} 
function seleccionar_no_considerar(){
   for (i=0;i<document.frm.elements.length;i++)
      if(document.frm.elements[i].type == "radio")
	  	if (document.frm.elements[i].value=="no_considerar")
			document.frm.elements[i].checked=1
		else
			document.frm.elements[i].checked=0
} 

</script>
</head>

<body onload="seleccionar_no_considerar()">
<h1 id="banner">Docentes - Asistencia</h1>
<div id="apDiv1">
<?php
	  require("../../../../../../../funciones/conexion_v2.php");
	  require("../../../../../../../funciones/funciones_sistema.php");
if($_GET)
{
	
	
	if(isset($_GET["tipo_usuario"])){$tipo_usuario=base64_decode($_GET["tipo_usuario"]);}
	else{ $tipo_usuario="docente";}
	$aux=0;
	if(DEBUG){ var_dump($_GET);}
	  $fecha=base64_decode($_GET["fecha_clase"]);
	  $sede=base64_decode($_GET['sede']);
	  $id_carrera=base64_decode($_GET['id_carrera']); 
	 // $nivel=base64_decode($_GET['nivel']);
	  $jornada=base64_decode($_GET['jornada']);
	  $grupo=base64_decode($_GET['grupo']);
	  $cod_asignatura=base64_decode($_GET['cod_asignatura']);
	  $year=base64_decode($_GET['year']);
	  $semestre=base64_decode($_GET['semestre']);
	  $mostrar_solo_alumnos_con_matricula=true;
	  $H_id=base64_decode($_GET['H_id']);
  //////////////////////////////////////////////////////////////
  		$cons_H="SELECT hora_inicio, hora_fin FROM horario_docente WHERE id_horario='$H_id' LIMIT 1";
		$sqli_H=$conexion_mysqli->query($cons_H)or die($conexion_mysqli->error);
		$DH=$sqli_H->fetch_assoc();
			$H_horario_inicio=$DH["hora_inicio"];
			$H_horario_fin=$DH["hora_fin"];
		$sqli_H->free();	
	//datos carrera
	$aux_nombre_carrera=NOMBRE_CARRERA($id_carrera);
	//---------------------------------------------------------------------
	list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($id_carrera, $cod_asignatura);
	//-------------------------------------------------------------	
?>
<form action="lista_alumnos_2.php" method="post" id="frm" name="frm">
<table width="100%" align="center">
<thead>
	<tr>
    	<th colspan="9">Lista Alumnos <?php echo"[$fecha]";?> <br> <?php echo"$aux_nombre_carrera <br> $nombre_asignatura Jornada: $jornada Grupo: $grupo<br> $H_horario_inicio - $H_horario_fin";?>
          <input name="sede" type="hidden" id="sede" value="<?php echo $sede;?>" />
    	  <input type="hidden" name="id_carrera" id="id_carrera" value="<?php echo $id_carrera;?>"/>
          <input type="hidden" name="jornada" id="jornada" value="<?php echo $jornada;?>"/>
          <input type="hidden" name="cod_asignatura" id="cod_asignatura"value="<?php echo $cod_asignatura;?>"/>
          <input type="hidden" name="grupo" id="grupo" value="<?php echo $grupo;?>"/> 
          <input type="hidden" name="semestre" id="semestre" value="<?php echo $semestre;?>"/>
          <input type="hidden" name="year" id="year" value="<?php echo $year;?>"/> 
          <input type="hidden" name="H_id" id="H_id" value="<?php echo $H_id;?>"/> 
          <input type="hidden" name="fecha_clase" id="fecha_clase" value="<?php echo $fecha;?>"/>  
          <input type="hidden" name="tipo_usuario" id="tipo_usuario" value="<?php echo $tipo_usuario;?>"/>  
          </th>
    </tr>
	<tr>
    	<td>N.</td>
        <td>Rut</td>
        <td>Nombre</td>
        <td>Apellido P</td>
        <td>Apellido M</td>
        <td align="center">Presente</td>
        <td align="center">Ausente</td>
        <td align="center">Justificado</td>
        <td align="center">No considerar</td>
    </tr>
</thead>
<tbody>
<?php
	//calcular horas pedagocicas del rango horario
	$time_1=strtotime($H_horario_inicio);
	$time_2=strtotime($H_horario_fin);
	$diferencia=($time_2-$time_1);
	
	$min_diferencia=($diferencia/60);
	
	switch($jornada)
	{
		case"D":
			$duracion_hora_pedagogica=45;
			break;
		case"V":
			$duracion_hora_pedagogica=40;
			break;	
	}
		
	
	$horas_pedagogicas=($min_diferencia/$duracion_hora_pedagogica);
	
	if(DEBUG){ echo"-------------------------------------<br>TIME 1 $time_1 <br>TiME 2 $time_2<br>diferencia:$diferencia<br>diferencia min:$min_diferencia<br>hrs pedagogicas: $horas_pedagogicas<br>-------------------------------------<br>";}
	
  //-------------------------------------------------------------------------------------------------//
	$cons_MAIN="SELECT DISTINCT(toma_ramos.id_alumno) FROM toma_ramos INNER JOIN alumno ON toma_ramos.id_alumno=alumno.id WHERE alumno.sede='$sede' AND toma_ramos.id_carrera='$id_carrera' AND alumno.id_carrera='$id_carrera' AND toma_ramos.jornada='$jornada' AND alumno.grupo='$grupo' AND toma_ramos.cod_asignatura='$cod_asignatura' AND toma_ramos.semestre='$semestre' AND toma_ramos.year='$year' ORDER by alumno. apellido_P, alumno.apellido_M";
	if(DEBUG){ echo"--->$cons_MAIN<br>";}
	$sqli=$conexion_mysqli->query($cons_MAIN)or die($conexion_mysqli->error);
	$num_registros=$sqli->num_rows;
	if(DEBUG){ echo"N. $num_registros<br>";}
	
	if($num_registros>0)
	{
		
		while($IA=$sqli->fetch_row())
		{
			$id_alumno=$IA[0];
			$cons_A="SELECT rut, nombre, apellido_P, apellido_M, ingreso, situacion FROM alumno WHERE id='$id_alumno' LIMIT 1";
			$sqli_a=$conexion_mysqli->query($cons_A);
			$A=$sqli_a->fetch_assoc();
			$A_rut=$A["rut"];
			$A_nombre=$A["nombre"];
			$A_apellido_P=$A["apellido_P"];
			$A_apellido_M=$A["apellido_M"];
			$A_ingreso=$A["ingreso"];
		
			$sqli_a->free();
			if(DEBUG){ echo"--->ID alumno: $id_alumno $A_rut $A_nombre $A_apellido_P $A_apellido_M<br>";}
			
			//--------------------------------------------------------------------------------------//
			//verificacion de matricula
			$A_situacion=ESTADO_ALUMNO_PERIODO($id_alumno, $id_carrera,$semestre, $year);
			$alumno_con_matricula=VERIFICAR_MATRICULA($id_alumno, $id_carrera, true, false, $semestre, false, $year);
			
			if($alumno_con_matricula){$mostrar_alumno=true;}
			else{$mostrar_alumno=false;}
			
			if($A_situacion=="V"){ $mostrar_alumno_2=true;}
			else{ $mostrar_alumno_2=false;}
			
			if(($mostrar_alumno)and($mostrar_alumno_2))	
			{
				$aux++;
				echo'<tr>
						<td>'.$aux.'</td>
						<td>'.$A_rut.'</td>
						<td>'.$A_nombre.'</td>
						<td>'.$A_apellido_P.'</td>
						<td>'.$A_apellido_M.'</td>
						<td align="center" bgcolor="#66CC66"><input name="asistencia['.$id_alumno.']" type="radio" value="presente" checked/></td>
						<td align="center" bgcolor="#CC3333"><input name="asistencia['.$id_alumno.']" type="radio" value="ausente" /></td>
						<td align="center" bgcolor="#CCCC33"><input name="asistencia['.$id_alumno.']" type="radio" value="justificado" /></td>
						<td align="center" bgcolor="#8A4B08"><input name="asistencia['.$id_alumno.']" type="radio" value="no_considerar" /></td>
					 </tr>';	
			}
				
		}
	}
	$sqli->free();
	$conexion_mysqli->close();
}
?>
</tbody>
<tr>
	<td colspan="2">Participantes del Curso</td>
    <td colspan="3">
    <select name="participantes_curso" id="participantes_curso">
    	<option value="-1" selected="selected">Seleccione</option>
        <option value="0">Todo el curso</option>
        <option value="A">Grupo A</option>
        <option value="B">Grupo B</option>
        <option value="C">Grupo C</option>
    </select>
    </td>
    <td align="center"><a href="#" class="button_R" onclick="seleccionar_presente();">P</a></td>
    <td align="center"><a href="#" class="button_R" onclick="seleccionar_ausente();">A</a></td>
    <td align="center"><a href="#" class="button_R" onclick="seleccionar_justificado();">J</a></td>
    <td align="center"><a href="#" class="button_R" onclick="seleccionar_no_considerar();">NC</a></td>
</tr>
<tr>
<td colspan="2">Hrs Pedagogicas Realizadas</td>	
<td colspan="7">
    <select name="horas_pedagogicas" id="horas_pedagogicas">
    <option value="-1" selected="selected">Seleccione</option>
    	<?php
        for($h=1;$h<=$horas_pedagogicas;$h++)
		{echo'<option value="'.$h.'">'.$h.'</option>';}
		?>
    </select>
    </td>
</tr>
</table>
<br><br />
<div id="div_boton"><a href="#" class="button_R" onclick="CONFIRMAR()">Finalizar Asistencia</a></div>
</form>
</div>
</body>
</html>