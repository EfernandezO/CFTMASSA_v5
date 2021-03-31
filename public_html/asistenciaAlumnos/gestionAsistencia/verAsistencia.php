<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(tfalse);
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Planificaciones->ver");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
require("../../../funciones/conexion_v2.php");
require("../../../funciones/funciones_sistema.php");

$id_usuario_actual=$_SESSION["USUARIO"]["id"];
require("../../../funciones/class_ASISTENCIA_ALUMNOS.php");

if($_GET)
{
	if(isset($_GET["id_curso"])){
		$id_curso=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["id_curso"]));
		 if(DEBUG){ echo"--->CON id_curso OK";}
		 $ASISTENCIA_ALUMNOS = new ASISTENCIA_ALUMNOS($id_curso);
		}
	else{
		if(DEBUG){ echo"--->SIN id_curso consultar x el";}
		$semestre=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["semestre"]));
		$year=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["year"]));
		$sede=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["sede"]));
		$id_carrera=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["id_carrera"]));
		$cod_asignatura=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["cod_asignatura"]));
		$jornada=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["jornada"]));
		$grupo_curso=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["grupo_curso"]));
		$id_funcionario=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["id_funcionario"]));
		
		$ASISTENCIA_ALUMNOS = new ASISTENCIA_ALUMNOS(0,$sede, $year, $semestre, $id_carrera, $cod_asignatura, $jornada, $grupo_curso);
		$id_curso=$ASISTENCIA_ALUMNOS->getIdCurso();
	}
}

list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($id_carrera, $cod_asignatura);
$nombre_carrera=NOMBRE_CARRERA($id_carrera);
//$nombre_docente=NOMBRE_PERSONAL($id_funcionario);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<title>ver Asistencias</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:77px;
	z-index:1;
	left: 5%;
	top: 291px;
}
</style>


<!--INICIO LIGHTBOX EVOLUTION-->
   <script type="text/javascript" src="../../libreria_publica/lightbox_1.6.9_evolution/js/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.css" />
  <!--[if IE 6]>
  <link rel="stylesheet" type="text/css" href="../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.ie6.css" />
  <![endif]-->
  <script type="text/javascript" src="../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/jquery.lightbox.min.js"></script>
 <script type="text/javascript">
    jQuery(document).ready(function($){
      $('.lightbox').lightbox(); 
    });
  </script>
 <!--FIN LIGHTBOX EVOLUTION--> 

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
 <script src="../../../libreria_publica/JSCal/src/js/jscal2.js"></script>
 <script src="../../../libreria_publica/JSCal/src/js/lang/es.js"></script>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/steel/steel.css">
<!--FIN MENU HORIZONTAL-->


<style type="text/css">
#apDiv2 {
	position:absolute;
	width:50%;
	height:115px;
	z-index:2;
	left: 5%;
	top: 82px;
}
#apDiv3 {
	position:absolute;
	width:35%;
	height:59px;
	z-index:3;
	left: 60%;
	top: 149px;
}
</style>
<script language="javascript">
function ELIMINAR(id_planificacion, id_planificacionMain)
{
	url="elimina_planificacion/elimina_planificacion_1.php?&id_planificacion="+id_planificacion+"&id_planificacionMain="+id_planificacionMain;
	
	c=confirm('Seguro(a) Desea Eliminar este Registro de la Planificacion...ż?');
	if(c){ window.location=url;}
}
</script>
</head>

<body>
<h1 id="banner">Administrador -  Registro Planificaciones V1.0</h1>

<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a href="#">Planificaciones</a>
  <ul>
  <li><a href="nueva_planificacion/nueva_planificacion_1.php?id_planificacionMain=<?php echo base64_encode($id_planificacionMain);?>&lightbox[iframe]=true&lightbox[width]=750&lightbox[height]=550" class="lightbox">Nuevo Registro</a></li>
  
    <li><a href="compara_planificaciones/compara_p1.php?id_planificacionMain=<?php echo base64_encode($id_planificacionMain);?>">Comparador</a></li>
    
    <li><a href="copiar_planificacion/copia_planificacion_1.php?id_planificacionMain=<?php echo base64_encode($id_planificacionMain);?>
&lightbox[iframe]=true&lightbox[width]=750&lightbox[height]=550" class="lightbox">Importar</a></li>
  </ul>
</li>
<li><a href="#">Ayudas</a>
  <ul>
  <li><a href="informe_imprimible/informe_imprimible_1.php?id_planificacionMain=<?php echo base64_encode($id_planificacionMain);?>&lightbox[iframe]=true&lightbox[width]=500&lightbox[height]=400" class="lightbox">Informe imprimible</a></li>
  
  <li><a href="#">xxx</a>
  <ul>

</ul>
</li>


  </ul>
</li>
<li><a href="../selectorAsignaturaDocenteUnificado/index.php">Volver a Seleccion</a></li>
</ul>
<br style="clear: left" />
</div> 

</div>
<div id="apDiv2">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="6">Informacion</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>Sede</td>
      <td colspan="5"><?php echo $sede;?></td>
    </tr>
    <tr>
      <td width="20%">Carrera</td>
      <td colspan="5"><?php echo $nombre_carrera;?></td>
    </tr>
    <tr>
      <td>Jornada</td>
      <td width="12%"><?php echo $jornada;?></td>
      <td width="11%">Nivel</td>
      <td width="12%"><?php echo $nivel_asignatura;?></td>
      <td width="12%">Grupo</td>
      <td width="33%"><?php echo $grupo_curso;?></td>
    </tr>
    <tr>
      <td>Asignatura</td>
      <td colspan="5"><?php echo $nombre_asignatura;?></td>
    </tr>
    <tr>
      <td>Periodo</td>
      <td colspan="5"><?php echo $semestre;?> Semestre - <?php echo $year;?></td>
    </tr>
    </tbody>
  </table>
</div>

<div id="apDiv1">

<div id="datosClase">
<table width="100%" border="1">
<thead>
  <tr>
    <th colspan="9">Datos de La Clase</th>
    </tr>
    </thead>
    <tbody>
  <tr>
    <td width="5%" >Fecha</td>
    <td width="15%"><input  name="fecha_clase" id="fecha_clase" size="15" maxlength="10" readonly="readonly" value="<?php echo date("Y-m-d");?>"/>
      <input type="button" name="boton1" id="boton1" value="..." /></td>
    <td width="8%" >Hora Inicio</td>
    <td width="13%" ><select name="hora_inicio" id="hora_inicio">
      <?php
        $hora_i=0;
		$hora_f=23;
		for($h=$hora_i;$h<=$hora_f;$h++)
		{
			if($h<10){$h_label="0".$h;}
			else{ $h_label=$h;}
			echo'<option value="'.$h_label.'">'.$h_label.'</option>';
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
			
			echo'<option value="'.$m_label.'">'.$m_label.'</option>';
		}
		?>
</select></td>
    <td width="20%" >Duracion Clase (hrs pedagogicas)</td>
    <td width="17%" ><select name="tipoActividad2" id="tipoActividad">
      <option value="1">opcion 1</option>
      <option value="2">opcion2</option>
    </select></td>
    <td width="4%" >Tipo </td>
    <td width="9%" ><select name="tipoActividad" id="tipoActividad2">
      <option value="1">opcion 1</option>
      <option value="2">opcion2</option>
    </select></td>
    <td width="9%" ><a href="#" class="button_R">Agregar</a></td>
  </tr>
  </tbody>
</table>


</div>
<table width="100%" border="1">
  <thead>
  <tr>
    <th colspan="9">Registros Previos</th>
  </tr>
  <tr>
  	<td>N. Clase</td>
    <td>Fecha</td>
    <td>Horario</td>
    <td>Duracion</td>
    <td>Tipo</td> 
    <td>Opciones</td>
  </tr>
  </thead>
  <tbody>
<?php

	if($id_curso>0){
		
		$clases=$ASISTENCIA_ALUMNOS->getListaClases();
		if(count($clases)>0){
			foreach($ASISTENCIA_ALUMNOS->getListaClases() as $n => $AuxIdClase){
					$ASISTENCIA_ALUMNOS->setIdClase($$auxIdClase);
					
					
					echo'<tr>
							<td>'.($n+1).'</td>
							<td>'.$ASISTENCIA_ALUMNOS->getClaseFecha().'</td>
							<td>'.$ASISTENCIA_ALUMNOS->getClaseHorario().'</td>
							<td>'.$ASISTENCIA_ALUMNOS->getClaseDuracion().'</td>
							<td>'.$ASISTENCIA_ALUMNOS->getClaseTipo().'</td>
							<td><a href="#" class="button_VERDE">Controlar Asistencia</a></td>				
						</tr>';
				}
				
			}
		else
		{
			echo'<tr><td colspan="9">Sin Registros Creados</td></tr>';
		}
		
	$conexion_mysqli->close();
}
?>
<tr>
    <td colspan="6">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div id="apDiv3">
<?php
$img="";
$msj="";
if(isset($_GET["error"]))
{
	$error=$_GET["error"];
	switch($error)
	{
		case"PE0":
			$img='<img src="../../BAses/Images/ok.png" width="29" height="26" alt="ok" />';
			$msj="Registro de Planificacion Eliminado";
			break;
	}
	echo"$msj $img";
}
?>
</div>
<script type="text/javascript">
//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton1", "fecha_clase", "%Y-%m-%d");
    //]]>
</script>
</body>
</html>