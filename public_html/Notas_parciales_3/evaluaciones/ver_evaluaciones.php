<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(false);
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Notas_parcialesV3->verCalificador");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("operador_notas_parciales.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"GRABA_NOTA_AUTOMATICO");
$xajax->register(XAJAX_FUNCTION,"BORRA_NOTA");
$xajax->register(XAJAX_FUNCTION,"MUESTRA_OPCIONES_NOTA");
//---------------------------------------------------------//
require("../../../funciones/conexion_v2.php");
$year_actual=date("Y");
$mes_actual=date("m");
if($mes_actual>8){ $semestre_actual=2;}
else{ $semestre_actual=1;}

$urlMenu="index.php";


if($_POST)
{
	$sede=$_POST["sede"];
	$array_carrera=explode("_",$_POST["carrera"]);
	$id_carrera=$array_carrera[0];
	$jornada=$_POST["jornada"];
	$grupo_curso=$_POST["grupo_curso"];
	$cod_asignatura=$_POST["asignatura"];
	$semestre=$_POST["semestre"];
	$year=$_POST["year"];
	
	$destacar_alumno=false;
}
if($_GET)
{
	$sede=base64_decode($_GET["sede"]);
	$id_carrera=base64_decode($_GET["id_carrera"]);
	$jornada=base64_decode($_GET["jornada"]);
	$grupo_curso=base64_decode($_GET["grupo_curso"]);
	$cod_asignatura=base64_decode($_GET["cod_asignatura"]);
	$semestre=base64_decode($_GET["semestre"]);
	$year=base64_decode($_GET["year"]);
	
	if(isset($_GET["id_alumno"]))
	{
		$id_alumno_destacado=base64_decode($_GET["id_alumno"]);
		
		if(is_numeric($id_alumno_destacado))
		{ $destacar_alumno=true;}
		else
		{ $destacar_alumno=false;}
	}
	else
	{ $destacar_alumno=false;}
}

//----------------------Redireccion a creacion de evaluaciones-------------------------------------------------//
$creacionAutomaticaEvaluaciones=true;//habilita o no la opcion

$redirijirForzada=false;
$cons="SELECT COUNT(id) FROM notas_parciales_evaluaciones WHERE sede='$sede' AND semestre='$semestre' AND year='$year' AND id_carrera='$id_carrera' AND jornada='$jornada' AND grupo='$grupo_curso' AND cod_asignatura='$cod_asignatura'";
	$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$E=$sqli->fetch_row();
	$numeroRegistros=$E[0];
	if(empty($numeroRegistros)){$numeroRegistros=0;}
	$sqli->free();
	
	//redirije siempre y cuando se encuentre en el semestre año actual
	if($numeroRegistros>0){}
	else{ if(($year==$year_actual)and($semestre==$semestre_actual)){$redirijirForzada=true;}}
	
	
	if(DEBUG){echo"Numero registros previos: $numeroRegistros<br>";}
	
	if($creacionAutomaticaEvaluaciones){
		if($redirijirForzada){
			$urlForzada="nueva_evaluacion/creacionEvaluacionesForzadas.php?sede=".base64_encode($sede)."&id_carrera=".base64_encode($id_carrera)."&jornada=".base64_encode($jornada)."&grupo=".base64_encode($grupo_curso)."&asignatura=".base64_encode($cod_asignatura)."&semestre=".base64_encode($semestre)."&year=".base64_encode($year);
			if(DEBUG){echo"url: $urlForzada<br>";}
			else{header("location: $urlForzada");}
		}
	}
///////
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<title>Evaluaciones - Parciales</title>
<?php $xajax->printJavascript(); ?> 
<script type="text/javascript" src="../../libreria_publica/jquery_libreria/jquery.min_1.2.6.js"></script>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/hint.css-master/hint.css"/>
<!--INICIO MENU HORIZONTAL-->
<link rel="stylesheet" type="text/css" href="../../libreria_publica/menu_horizontal/ddsmoothmenu.css"/>
<script type="text/javascript" src="../../libreria_publica/menu_horizontal/ddsmoothmenu.js">

/***********************************************
* Smooth Navigational Menu- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

</script>

<script type="text/javascript">

ddsmoothmenu.init({
	mainmenuid: "smoothmenu1", //menu DIV id
	orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu', //class added to menu's outer DIV
	//customtheme: ["#1c5a80", "#18374a"],
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})

</script>
<!--FIN MENU HORIZONTAL-->
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:69px;
	z-index:1;
	left: 5%;
	top: 136px;
}
#apDiv2 {
	position:absolute;
	width:45%;
	height:20px;
	z-index:2;
	left: 55%;
	text-align: center;
}
#div_msj {
	position:absolute;
	width:45%;
	height:39px;
	z-index:2;
	left: 50%;
	top: 174px;
	text-align: center;
	font-weight: bold;
	color: #F00;
	text-decoration: blink;
}
#apDiv4 {
	position:absolute;
	width:90%;
	height:18px;
	z-index:3;
	left: 5%;
	top: 73px;
	text-align: center;
}
#apDiv5 {
	position:absolute;
	width:45%;
	height:34px;
	z-index:4;
	left: 50%;
	top: 3px;
	text-align: center;
}
</style>
<?php

require("../../../funciones/funciones_sistema.php");
require("../../../funciones/class_NOTAS.php");

$ARRAY_DOCENTE_IMPARTE_ASIGNATURA=array();
//busco al docente de esta asignatura
		$cons_D="SELECT id_funcionario FROM toma_ramo_docente WHERE id_carrera='$id_carrera' AND jornada='$jornada' AND grupo='$grupo_curso' AND semestre='$semestre' AND year='$year' AND sede='$sede' AND cod_asignatura='$cod_asignatura' ORDER by id_funcionario";
		$sqli_D=$conexion_mysqli->query($cons_D)or die($conexion_mysqli->error);
		$Do=$sqli_D->fetch_assoc();
			$id_funcionario_realiza_asignatura=$Do["id_funcionario"];
			$nombre_funcionario_realiza_asignatura=NOMBRE_PERSONAL($id_funcionario_realiza_asignatura);
			if(DEBUG){ echo"Busqueda de funcionario realiza asignatura<br> $cons_D<br>id_funcionario: $id_funcionario_realiza_asignatura<br>nombre funcionario: $nombre_funcionario_realiza_asignatura<br>";}
			$ARRAY_DOCENTE_IMPARTE_ASIGNATURA[$id_funcionario_realiza_asignatura]["nombre_funcionario"]=$nombre_funcionario_realiza_asignatura;
		$sqli_D->free();
		
		
		$id_usuario_actual=$_SESSION["USUARIO"]["id"];
//******************************************************************//		
//permite o no la edicion de notas
$PromedioTraspasar=true;//traspasa a notas semestrales
$bloquearIngresoNotasFueraTiempo=false; //solo x dias despues de fecha de evalucion para editar notas
$privilegio=$_SESSION["USUARIO"]["privilegio"];
$soloUsuarioDictaRamoActualizaPromedio=false; //si entra otro usuario no actualiza los promedios de alumnos a notas semestrales
//-------------------------------------------------//


if(($privilegio=="Docente")or ($privilegio=="jefe_carrera")){
	$urlMenu="../../Docentes/selectorAsignaturaDocenteUnificado/index.php";
}


?>
<script language="javascript">
function GRABAR_CALIFICACIONES()
{
	c=confirm('Seguro(a) Desea Grabar estas Calificaciones');
	if(c){document.getElementById('frm').submit();}
}
</script>
</head>
<body>
<h1 id="banner">Administrador -  Registro Notas Parciales v3</h1>
<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a href="#">Evaluaciones</a>
  <ul>
  	<li><a href="nueva_evaluacion/nva_evaluacion_1.php?sede=<?php echo base64_encode($sede);?>&id_carrera=<?php echo base64_encode($id_carrera);?>&jornada=<?php echo base64_encode($jornada);?>&grupo_curso=<?php echo base64_encode($grupo_curso);?>&cod_asignatura=<?php echo base64_encode($cod_asignatura);?>&semestre=<?php echo base64_encode($semestre);?>&year=<?php echo base64_encode($year);?>">Nueva Evaluacion</a></li>
    <li><a href="edicion_general/lista_evaluaciones.php?sede=<?php echo base64_encode($sede);?>&id_carrera=<?php echo base64_encode($id_carrera);?>&jornada=<?php echo base64_encode($jornada);?>&grupo=<?php echo base64_encode($grupo_curso);?>&cod_asignatura=<?php echo base64_encode($cod_asignatura);?>&semestre=<?php echo base64_encode($semestre);?>&year=<?php echo base64_encode($year);?>">Edicion Evaluaciones</a></li>
  </ul>
</li>
<li><a href="#">Calificaciones</a>
	<ul>
    	<li><a href="#" onclick="GRABAR_CALIFICACIONES();">Grabar Calificaciones</a></li>
    </ul>
</li>
<li><a href="#">Imprimir</a>
  <ul>
    <li><a href="informe_imprimible/informe_imprimible_1.php?sede=<?php echo base64_encode($sede);?>&id_carrera=<?php echo base64_encode($id_carrera);?>&jornada=<?php echo base64_encode($jornada);?>&grupo_curso=<?php echo base64_encode($grupo_curso);?>&cod_asignatura=<?php echo base64_encode($cod_asignatura);?>&semestre=<?php echo base64_encode($semestre);?>&year=<?php echo base64_encode($year);?>" target="_blank">Resumen Notas pdf</a></li> 
    
     <li><a href="informe_imprimible/informe_imprimible_2.php?sede=<?php echo base64_encode($sede);?>&id_carrera=<?php echo base64_encode($id_carrera);?>&jornada=<?php echo base64_encode($jornada);?>&grupo_curso=<?php echo base64_encode($grupo_curso);?>&cod_asignatura=<?php echo base64_encode($cod_asignatura);?>&semestre=<?php echo base64_encode($semestre);?>&year=<?php echo base64_encode($year);?>" target="_blank">Lista Alumnos pdf</a></li> 
</ul>
</li>
<li><a href="<?php echo $urlMenu;?>">Volver a Selecci&oacute;n</a></li>
<?php
if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
{
	if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
	{ $mostrar_boton=false;}
	else
	{ $mostrar_boton=false;}
}
else
{ $mostrar_boton=false;}

if($mostrar_boton)
{echo'<li><a href="../informe_notas_alumno/ver_notas_parciales_v3.php">Volver a Alumno Seleccionado</a></li>';}
?>
</ul>
<br style="clear: left" />
</div> 
<div id="apDiv1">
<form action="graba_notas_evaluaciones/graba_notas_evaluaciones.php" method="post" id="frm">
  <table width="50%" border="1" align="left">
  <thead>
    <tr>
      <th colspan="7">Evaluaciones <?php echo"[$semestre Semestre-$year]";?>
        <input type="hidden" name="sede" id="sede" value="<?php echo $sede;?>"/>
        <input type="hidden" name="id_carrera" id="id_carrera" value="<?php echo $id_carrera;?>"/>
        <input type="hidden" name="cod_asignatura" id="cod_asignatura" value="<?php echo $cod_asignatura;?>"/>
        <input type="hidden" name="jornada" id="jornada" value="<?php echo $jornada;?>"/>
        <input type="hidden" name="grupo" id="grupo" value="<?php echo $grupo_curso;?>"/>
        <input type="hidden" name="semestre" id="semestre" value="<?php echo $semestre;?>"/>
        <input type="hidden" name="year" id="year" value="<?php echo $year;?>"/></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>N</td>
      <td>Nombre Evaluacion</td>
      <td>Fecha Evaluacion</td>
      <td>Plazo Maximo para Evaluar</td>
      <td>Metodo Evaluacion</td>
      <td>Tipo Evaluacion</td>
      <td>Activa</td>
    </tr>
     <?php
		//------------------------------------------------//
		include("../../../funciones/VX.php");
		$evento="Gestion de Calificaciones Parciales V3 sede: $sede id_carrera: $id_carrera jornada: $jornada grupo:$grupo_curso [$semestre - $year]";
		REGISTRA_EVENTO($evento);
		/////////--------------------------------//////////
		
		
		
		
		///nombre de carrea para titulo
			$nombre_carrera=NOMBRE_CARRERA($id_carrera);
		//nombre asignatura
			list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($id_carrera, $cod_asignatura);

		/////////------------SECCION EVALUCIONES--------------------//////////
		
		
		
	 	$cons_e="SELECT * FROM notas_parciales_evaluaciones WHERE sede='$sede' AND id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND jornada='$jornada' AND grupo='$grupo_curso' AND semestre='$semestre' AND year='$year'";
		$sql_e=$conexion_mysqli->query($cons_e)or die($conexion_mysqli->error);
		$num_evaluaciones=$sql_e->num_rows;
		if(DEBUG){ echo"$cons_e<br>num evaluaciones: $num_evaluaciones<br>";}
		$array_evaluaciones=array();
		$array_evaluaciones_nombre=array();
		$fechaActualTime=strtotime(date("Y-m-d"));
		$numEvalucionesParciales=0;
		$numeroEvaluacionesEditables=0;
		if($num_evaluaciones>0)
		{
			$aux=0;
			while($E=$sql_e->fetch_assoc())
			{
				$aux++;
				$sePermiteEditar=false;
				
				$id_evaluacion=$E["id"];
				$nombre_evaluacion=$E["nombre_evaluacion"];
				$fecha_generacion=$E["fecha_generacion"];
				$fecha_evaluacion=$E["fecha_evaluacion"];
				
				$fechaMaximaEvaluacion=new DateTime($fecha_evaluacion);
				$fechaMaximaEvaluacion->modify("+15 days");
				$fechaMaximaEvaluacion->format("Y-m-d");
				
				
				
				$fechaEvaluacionTime=strtotime($fecha_evaluacion);
				$fechaEvaluacionMaximoPlazo=strtotime($fechaMaximaEvaluacion->format("Y-m-d"));
			
				
				$metodo_evaluacion=$E["metodo_evaluacion"];
				$tipo_evaluacion=$E["tipo_evaluacion"];
				$porcentaje=$E["porcentaje"];
				
				//se permite editar notas de esta evaluacion solo si esta dentro del rango de fechas
				
				if(DEBUG){echo"fechaActualTime : $fechaActualTime<br>fecha Evaluacion: $fechaEvaluacionTime<br>Plazo Maximo:::::   $fechaEvaluacionMaximoPlazo<br><br>";}
				
				if($bloquearIngresoNotasFueraTiempo){
					if(($fechaActualTime>=$fechaEvaluacionTime)and($fechaActualTime<=$fechaEvaluacionMaximoPlazo)){$sePermiteEditar=true;}
				}else{$sePermiteEditar=true; $numeroEvaluacionesEditables++;}
				
				$array_evaluaciones[$id_evaluacion]=$porcentaje;
				$array_evaluaciones_metodo[$id_evaluacion]=$metodo_evaluacion;
				$array_evaluaciones_tipo[$id_evaluacion]=$tipo_evaluacion;
				$array_evaluaciones_nombre[$id_evaluacion]=$nombre_evaluacion;
				$array_evaluaciones_editable[$id_evaluacion]=$sePermiteEditar;
				
				if($sePermiteEditar){ $msjEdicion="si";}
				else{$msjEdicion="no";}
				
				if($tipo_evaluacion=="parcial"){$numEvalucionesParciales++;}
				
				echo'<tr>
						 <td>'.$aux.'</td>
						  <td>'.$nombre_evaluacion.'</td>
						  <td>'.$fecha_evaluacion.'</td>
						  <td>'.$fechaMaximaEvaluacion->format("Y-m-d").'</td>
						  <td>'.$metodo_evaluacion.'</td>
						  <td>'.$tipo_evaluacion.'</td>
						  <td>'.$msjEdicion.'</td>
					 </tr>';
			}
		}
		else
		{echo'<tr><td colspan="6">Sin Evaluacion Creadas...</td></tr>';}
		$sql_e->free();
		
	 ?>
    </tbody>
  </table><br /><br>

  <div id="tabla_alumnos">
    <div id="apDiv5"><?php if($num_evaluaciones>0){?><a href="#" class="button_R" onclick="GRABAR_CALIFICACIONES();"> Grabar Calificaciones</a><?php }?></div>
    <p>&nbsp;</p>
    <table width="90%" border="1" align="left">
      <thead>
      <th colspan="<?php echo ($num_evaluaciones+6);?>">Alumnos <?php echo"Jornada: $jornada Grupo: $grupo_curso";?></th>
        </thead>
      <tbody>
        <tr>
          <td>N</td>
          <td>Rut</td>
          <td>Apellido P</td>
          <td>Apellido M</td>
          <td>Nombre</td>
          <td colspan="<?php echo ($num_evaluaciones+6);?>">Evaluaciones</td>
        </tr>
        <?php
	
	//utilizo jornada de la toma de ramos
	
		$cons_A="SELECT toma_ramos.*, alumno.id, alumno.rut, alumno.nombre, alumno.apellido_P, alumno.apellido_M FROM toma_ramos INNER JOIN alumno ON toma_ramos.id_alumno = alumno.id WHERE toma_ramos.id_carrera='$id_carrera' AND alumno.sede='$sede' AND toma_ramos.jornada='$jornada' AND alumno.grupo='$grupo_curso' AND toma_ramos.semestre='$semestre' AND toma_ramos.year='$year' AND toma_ramos.cod_asignatura='$cod_asignatura' ORDER by alumno.apellido_P, alumno.apellido_M, nombre";
		
		
	$sql_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
	$num_alumnos=$sql_A->num_rows;
	if(DEBUG){ echo"$cons_A<br>NUM alumnos: $num_alumnos<br>";}
	
	if($num_alumnos>0)
	{
		$cuenta_alumnos=0;
		$indice_posicion=0;
		while($A=$sql_A->fetch_assoc())
		{
			$cuenta_alumnos++;
			
			$A_id=$A["id"];
			$A_rut=$A["rut"];
			$A_nombre=$A["nombre"];
			$A_apellido_P=$A["apellido_P"];
			$A_apellido_M=$A["apellido_M"];
			$A_id_carrera=$A["id_carrera"];
			$A_yearIngresoCarrera=$A["yearIngresoCarrera"];
			
			if($destacar_alumno)
			{
				if($id_alumno_destacado==$A_id)
				{ $color="#FFAA00";}
				else
				{ $color="";}
			}
			else{ $color="";}
			
			//armo link para seleccion de alumno
			
			if($privilegio=="admi_total"){
				$validador=md5("GDXT".date("d-m-Y"));
				$urlAlumnoSelector="../../buscador_alumno_BETA/enrutador.php?id_alumno=$A_id&validador=$validador";
			}else{$urlAlumnoSelector="";}
			
			echo'<tr bgcolor="'.$color.'">
					<td>'.$cuenta_alumnos.'</td>
					<td><a href="'.$urlAlumnoSelector.'" target="_blanck">'.$A_rut.'</a><input name="id_alumno[]" type="hidden" value="'.$A_id.'" /></td>
					<td>'.$A_apellido_P.'</td>
					<td>'.$A_apellido_M.'</td>
					<td>'.$A_nombre.'</td>';
					
			///espacio segun numero evaluaciones existentes		
			$PROMEDIO_ALUMNO=0;
			$cuenta_evaluacion=0;
			$cuenta_notas_puestas=0;
			$hay_nota_repeticion=false;
			
			foreach($array_evaluaciones as $id_evaluacionx =>$porcentajex)
			{
				$cuenta_evaluacion++;
				
				$metodo_evaluacionx=$array_evaluaciones_metodo[$id_evaluacionx];
				$aux_nombre_evaluacion=$array_evaluaciones_nombre[$id_evaluacionx];
				$aux_tipo_evaluacion=$array_evaluaciones_tipo[$id_evaluacionx];
				$aux_permiteEditarEvaluacion=$array_evaluaciones_editable[$id_evaluacionx];
				//echo"$metodo_evaluacionx<br>";
				
				$aux_info="";
				$cons_BN="SELECT * FROM notas_parciales_registros WHERE id_alumno='$A_id' AND id_evaluacion='$id_evaluacionx' AND id_carrera='$id_carrera' LIMIT 1";
				$sql_BN=$conexion_mysqli->query($cons_BN)or die($conexion_mysqli->error);
					$DN=$sql_BN->fetch_assoc();
					$aux_nota_parcial=$DN["nota"];
					$aux_id_nota_parcial_registro=$DN["id"];
					$aux_observacion=$DN["observacion"];
					$aux_fecha_ultima_modificacion=$DN["fecha_generacion"];
					$aux_id_usuario_modifica=$DN["cod_user"];
					
					$aux_info="info: [$aux_observacion] ultimo_usuario_modifica: [".NOMBRE_PERSONAL($aux_id_usuario_modifica)."] Fecha ultima Modificacion:[ $aux_fecha_ultima_modificacion]";
				$sql_BN->free();
				
				$indice_posicion++;
				///campo de nota con opciones
				if($aux_permiteEditarEvaluacion){ $bloqueoDeCampo='ondblclick="xajax_MUESTRA_OPCIONES_NOTA('.$aux_id_nota_parcial_registro.', '.$indice_posicion.');"'; $colorBloqueoCampo="success";}
				else{$bloqueoDeCampo='readonly="readonly" '; $colorBloqueoCampo="warning";}
				
				switch($metodo_evaluacionx)
				{
					case"ponderado":
						if($aux_nota_parcial>0){$PROMEDIO_ALUMNO+=(($aux_nota_parcial*$porcentajex)/100); $cuenta_notas_puestas++; }
						$title_nota="$aux_nombre_evaluacion [ $porcentajex %]";
						break;
					default:
						switch($aux_tipo_evaluacion)
						{
							case"parcial":
								if($aux_nota_parcial>0){$PROMEDIO_ALUMNO+=$aux_nota_parcial; $cuenta_notas_puestas++;}
								$title_nota="$aux_nombre_evaluacion";
								break;
							case"global":
								if($aux_nota_parcial>0)
								{
									$PROMEDIO_ALUMNO+=($aux_nota_parcial*2);
									 $cuenta_notas_puestas+=2;
								}
								$title_nota="$aux_nombre_evaluacion";
								break;
							case"repeticion":
								$hay_nota_repeticion=true;
								$aux_nota_repeticion=$aux_nota_parcial;
								$title_nota="$aux_nombre_evaluacion";
								break;
						}
						
				}
				
				
				echo'<td align="center"><a  class="hint--top  hint--'.$colorBloqueoCampo.'" data-hint="'.$title_nota.'-> '.$aux_info.'"><input name="evaluacion['.$id_evaluacionx.']['.$A_id.']" type="text" id="input_nota_'.$indice_posicion.'" value="'.$aux_nota_parcial.'"  size="3" '.$bloqueoDeCampo.'  onblur="xajax_GRABA_NOTA_AUTOMATICO(this.value, '.$id_evaluacionx.', '.$A_id.', '.$id_carrera.', '.$cod_asignatura.', '.$semestre.', '.$year.', \''.$sede.'\', \''.$jornada.'\', \''.$grupo_curso.'\', '.$indice_posicion.');"/></a><div id="div_posicion_'.$indice_posicion.'" ></div></td>';
			}//fin foreach
			if(isset($metodo_evaluacionx))
			{
				switch($metodo_evaluacionx)
					{
						case"ponderado":
							break;
						default:
							if($cuenta_notas_puestas>0)
							{ 
								$PROMEDIO_ALUMNO=($PROMEDIO_ALUMNO/$cuenta_notas_puestas);
								if($hay_nota_repeticion)
								{
									if($aux_nota_repeticion>$PROMEDIO_ALUMNO)
									{
										$PROMEDIO_ALUMNO=$aux_nota_repeticion;
									}
								}
							}
							else{ $PROMEDIO_ALUMNO=0;}
					}
					
				
				//---------------------------------Actualiza Promedion Semestral--------------------------------//
				$msjGrabarpromedio="";
				$actualizarPromedioSemestral=false;	
				
				///permite elegir, si la actualizacion de promedio se realiza solo cuando el docente ingresa a la asignatura o con cualquier usuario.
				
				$usuarioActualDictaRamo=false;
				if($soloUsuarioDictaRamoActualizaPromedio){
					if($id_usuario_actual==$id_funcionario_realiza_asignatura){$usuarioActualDictaRamo=true;}
				}else{$usuarioActualDictaRamo=true;}
				
				
				if(($usuarioActualDictaRamo)and($PromedioTraspasar)and($num_evaluaciones>0)and($numEvalucionesParciales>0)and($cuenta_notas_puestas>=$numEvalucionesParciales)and($numeroEvaluacionesEditables>0)){$actualizarPromedioSemestral=true;}
				
				
				
				if($actualizarPromedioSemestral and($PROMEDIO_ALUMNO>0)){
					$NOTA= new NOTAS($A_id, $A_yearIngresoCarrera, $id_carrera, $sede);
					$NOTA->setDebug(false);
					
					$NOTA->setCodAsignatura($cod_asignatura);
					$NOTA->setYear($year);
					$NOTA->setSemestre($semestre);
					
					$NOTA->grabaNota($PROMEDIO_ALUMNO);
					$msjGrabarpromedio="(actualizado)";
					
				}
				//--------------------------------------------------------------------------------//
				
				echo'<td align="center"><strong>'.number_format($PROMEDIO_ALUMNO,1,".",",").'</strong>'.$msjGrabarpromedio.'</td></tr>';
			}
		}
	}
	else
	{ echo'<tr><td colspan="6">Sin Registros</td></tr>';}
  $sql_A->free();
 
  $conexion_mysqli->close();
   ?>
</tbody>
</table><br /><br />
  </div>
  </form>
</div>
<div id="div_msj">
<?php
if(isset($_GET["error"]))
{
	$error=$_GET["error"];
	$img_ok='<img src="../../BAses/Images/ok.png" width="29" height="26" alt="ok" />';
	$img_error='<img src="../../BAses/Images/b_drop.png" width="16" height="16" alt="X" />';
	switch($error)
	{
		case"0":
			$msj="Calificaciones Grabadas...";
			$img=$img_ok;
			break;
		default:
			$msj="";
			$img="";	
	}
	echo $msj.$img;
}
?>
</div>
<div id="apDiv4">
<?php echo "<h3>$nombre_carrera * ($cod_asignatura) $nombre_asignatura - Jornada:$jornada";
foreach($ARRAY_DOCENTE_IMPARTE_ASIGNATURA as $auxIdFuncionario => $auxArray){
	$auxNombreFuncionario=$auxArray["nombre_funcionario"];
	echo"<br>Docente: ($auxIdFuncionario) $auxNombreFuncionario";
} 
echo"</h3>";?></div>
</body>
</html>