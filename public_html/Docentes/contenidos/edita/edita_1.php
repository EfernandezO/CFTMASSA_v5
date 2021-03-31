<?php
//-----------------------------------------//
//-----------------------------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(false);
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Contenidos->Editar");
	$O->PERMITIR_ACCESO_USUARIO();
//-----------------------------------------//
//-----------------------------------------//
//////////////////////XAJAX/////////////////
@require_once ("../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("comprueba_server.php");
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"COMPRUEBA");
//---------------------------------------------------------///
if(DEBUG){var_dump($_POST);}
	require("../../../../funciones/funciones_sistema.php");
	require("../../../../funciones/conexion_v2.php");
	
	$id_contenido=base64_decode($_GET["id_contenido"]);
	$id_contenidoMain=base64_decode($_GET["id_contenidoMain"]);
	
	$consPM="SELECT * FROM contenidosMain WHERE idContenidoMain='$id_contenidoMain'";
	$sqliPM=$conexion_mysqli->query($consPM)or die($conexion_mysqli->error);
	$DPM=$sqliPM->fetch_assoc();
		$id_carrera=$DPM["id_carrera"];
		$cod_asignatura=$DPM["cod_asignatura"];
		$semestre=$DPM["semestre"];
		$year=$DPM["year"];
		$sede=$DPM["sede"];
		$id_funcionario=$DPM["id_funcionario"];
		$jornada=$DPM["jornada"];
		$grupo=$DPM["grupo"];
		
		$numeroSemanas=$DPM["numero_semanas"];
	$sqliPM->free();
	
	
	//bibliografia de planificaciones
	$ARRAY_BIBLIOGRAFIA_PLANIFICACIONES=array();
	$cons_BP="SELECT DISTINCT(planificaciones.bibliografia) FROM planificaciones INNER JOIN planificacionesMain ON planificacionesMain.idPlanificacionMain= planificaciones.idPlanificacionMain WHERE planificacionesMain.sede='$sede' AND planificacionesMain.year='$year' AND planificacionesMain.semestre='$semestre' AND planificacionesMain.id_carrera='$id_carrera' AND planificacionesMain.cod_asignatura='$cod_asignatura' AND planificacionesMain.jornada='$jornada' AND planificacionesMain.grupo='$grupo' AND planificacionesMain.id_funcionario='$id_funcionario'";
	$sqliBP=$conexion_mysqli->query($cons_BP) or die($conexion_mysqli->error);
	$numBibliografias=$sqliBP->num_rows;
	if($numBibliografias>0){
		while($BP=$sqliBP->fetch_row()){
			$auxBibliografia=$BP[0];
			array_push($ARRAY_BIBLIOGRAFIA_PLANIFICACIONES, $auxBibliografia);
		}
	}
	$sqliBP->free();
	
	$cons="SELECT * FROM contenidosDetalle WHERE id_contenido='$id_contenido' LIMIT 1";
	$sqli=$conexion_mysqli->query($cons);
		$P=$sqli->fetch_assoc();

				$numero_semana=$P["numero_semana"];
				$fecha_clase=$P["fecha_clase"];
				$Array_horario_inicio_clase=explode(":",$P["horario_inicio_clase"]);
				$duracion_clase=$P["duracion_clase"];
				
				$contenido=$P["contenido"];
				$tipo_actividad=$P["tipo_actividad"];
				$bibliografia=$P["bibliografia"];
		
	$sqli->free();
	//----------------------------------------------------------------------------------------///
	$nombre_carrera=NOMBRE_CARRERA($id_carrera);
	list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($id_carrera, $cod_asignatura);
	
	
	//------------------------------------------------------------------//
	///horas de programa total
	$TOTAL_HORAS_PROGRAMA=0;
	$cons_HT="SELECT DISTINCT(numero_unidad) FROM programa_estudio WHERE id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura'";
	$sqli_HT=$conexion_mysqli->query($cons_HT)or die($conexion_mysqli->error);
	$num_programas=$sqli_HT->num_rows;
	if($num_programas>0)
	{
		while($HT=$sqli_HT->fetch_row())
		{
			$aux_numero_unidad=$HT[0];
			$aux_CONS="SELECT cantidad_horas FROM programa_estudio WHERE id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND numero_unidad='$aux_numero_unidad' LIMIT 1";
			$sqli_aux=$conexion_mysqli->query($aux_CONS)or die("HP ".$conexion_mysqli->error);
				$Pnh=$sqli_aux->fetch_row();
				$aux_numero_hora_x_unidad=$Pnh[0];
				if(empty($aux_numero_hora_x_unidad)){ $aux_numero_hora_x_unidad=0;}
			$TOTAL_HORAS_PROGRAMA+=$aux_numero_hora_x_unidad;
			$sqli_aux->free();	
		}
	}
	$sqli_HT->free();
	//----------------------------------------------------//
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<title>Edita Contenido</title>
<?php $xajax->printJavascript(); ?> 
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:50%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 53px;
}
#apDiv2 {
	position:absolute;
	width:60%;
	height:115px;
	z-index:2;
	left: 5%;
	top: 187px;
}
#div_boton {
	position:absolute;
	width:25%;
	height:33px;
	z-index:3;
	left: 70%;
	top: 419px;
	text-align:center;
}
#div_info {
	position:absolute;
	width:25%;
	height:115px;
	z-index:4;
	left: 70%;
	top: 283px;
}
</style>
<script language="javascript">
function CONFIRMAR()
{
	continuar=true;
	contenido=document.getElementById('contenido').value;
	bibliografia=document.getElementById('bibliografia').value;
	
	if((contenido=="")||(contenido==" "))
	{
		continuar=false;
		alert("Ingrese el contenido");
	}
	
	
	if(continuar)
	{
		c=confirm('Desea Modificar este Registro ?');
		if(c){ document.getElementById('frm').submit();}
	}
}
function TRASPASAR(valor){
	document.getElementById('bibliografia').value=valor;
}
</script>
</head>

<body onload="xajax_COMPRUEBA(<?php echo $numero_semana;?>, <?php echo $id_contenido;?>, <?php echo $id_contenidoMain;?>); return false;">
<h1 id="banner">Administrador -  Edita Planificaciones V1.0</h1>
<div id="apDiv1">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="2">&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="25%">Carrera</td>
      <td><?php echo $nombre_carrera;?></td>
    </tr>
    <tr>
      <td>Asinatura</td>
      <td><?php echo $nombre_asignatura;?></td>
    </tr>
    </tbody>
  </table>
</div>
<div id="apDiv2">
<form action="edita_2.php" method="post" id="frm">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="3">Ingrese los Datos
        <input name="id_contenidoMain" type="hidden" id="id_contenidoMain" value="<?php echo $id_contenidoMain;?>" />
        <input name="id_contenido" type="hidden" id="id_contenido" value="<?php echo $id_contenido;?>" />
      </tr>
    </thead>
    <tbody>
    <tr>
      <td width="43%">N. Semana</td>
      <td colspan="2">
        <select name="numero_semana" id="numero_semana" onchange="xajax_COMPRUEBA(this.value, <?php echo $id_contenidoMain?>); return false;">
          <option value="0" selected="selected">Seleccione</option>
          <?php
        for($x=1;$x<=$numeroSemanas;$x++)
		{
			if($numero_semana==$x){$selec='selected="selected"';}
			else{$selec='';}
			echo'<option value="'.$x.'" '.$selec.'>'.$x.'</option>';
		}
		?>
          </select>
        </td>
    </tr>
    <tr>
      <td>Fecha de Clase</td>
      <td colspan="2"><input  name="fecha_clase" id="fecha_clase" size="15" maxlength="10" readonly="readonly" value="<?php echo $fecha_clase;?>"/>
        <input type="button" name="boton1" id="boton1" value="..." /></td>
    </tr>
    <tr>
      <td>Horario Inicio de Clase</td>
      <td colspan="2"><select name="hora_inicio" id="hora_inicio">
        <?php
        $hora_i=0;
		$hora_f=23;
		for($h=$hora_i;$h<=$hora_f;$h++)
		{
			if($h<10){$h_label="0".$h;}
			else{ $h_label=$h;}
			
			if($Array_horario_inicio_clase[0]==$h){$selec='selected="selected"';}
			else{$selec='';}
			echo'<option value="'.$h_label.'" '.$selec.'>'.$h_label.'</option>';
		}
		?>
      </select>
:
<select name="minuto_inicio" id="minuto_inicio">
  <?php
        $min_i=0;
		$min_f=59;
		for($m=$min_i;$m<=$min_f;$m+=5)
		{
			if($m<10){$m_label="0".$m;}
			else{$m_label=$m;}
			
			if($Array_horario_inicio_clase[1]==$m){$selec='selected="selected"';}
			else{$selec='';}
			
			
			echo'<option value="'.$m_label.'">'.$m_label.'</option>';
		}
		?>
</select></td>
    </tr>
    <tr>
      <td>Duracion de clase (hrs.)</td>
      <td colspan="2"><div id="div_horas_semana">...</div>
        
        </td>
    </tr>
    <tr>
      <td>Contenido</td>
      <td colspan="2"><label for="contenido"></label>
        <textarea name="contenido" id="contenido"><?php echo $contenido;?></textarea></td>
    </tr>
    <tr>
      <td>Tipo Actividad</td>
      <td colspan="2"><label for="tipoActividad"></label>
        <label for="tipoActividad2"></label>
        <select name="tipoActividad" id="tipoActividad2">
          <option value="1">opcion 1</option>
          <option value="2">opcion2</option>
        </select></td>
    </tr>
    <tr>
      <td>Bibliografia</td>
      <td width="36%"><label for="bibliografia"></label>
        <input type="text" name="bibliografia" id="bibliografia" value="<?php echo $bibliografia;?>" /></td>
      <td width="21%"><label for="bibliografiaPlanificaciones">(bibliografia registrada en planificaciones)<br />
      </label>
        <select name="bibliografiaPlanificaciones" id="bibliografiaPlanificaciones" onchange="TRASPASAR(this.value)">
          <option>Seleccione</option>
          <?php
		foreach($ARRAY_BIBLIOGRAFIA_PLANIFICACIONES as $n => $valor){
			echo'<option value="'.$valor.'">'.$valor.'</option>';
		}
        ?>
        </select></td>
    </tr>
    </tbody>
  </table>
  </form>
</div>
<div id="div_boton"></div>
<div id="div_info"></div>
</body>
</html>
<?php
$conexion_mysqli->close();	
?>