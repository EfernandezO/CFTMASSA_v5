<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(true);
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("contenidos->ver");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
require("../../../funciones/conexion_v2.php");
require("../../../funciones/funciones_sistema.php");


$urlDestino="http://intranet.cftmassachusetts.cl/Docentes/selectorAsignaturaDocenteUnificado/";

if($_GET)
{
	if(isset($_GET["id_contenidoMain"])){$id_contenidoMain=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["id_contenidoMain"])); if(DEBUG){ echo"--->CON id_contenidoMain OK<br>";}}
	else{
		if(DEBUG){ echo"--->SIN id_contenidoMain consultar x el";}
		$id_contenidoMain=0;
		$semestre=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["semestre"]));
		$year=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["year"]));
		$sede=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["sede"]));
		$id_carrera=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["id_carrera"]));
		$cod_asignatura=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["cod_asignatura"]));
		$jornada=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["jornada"]));
		$grupo_curso=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["grupo_curso"]));
		$id_funcionario=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["id_funcionario"]));
		
		$urlDestino="configuracionInicio/configuracionInicial1.php?semestre=".base64_encode($semestre)."&year=".base64_encode($year)."&sede=".base64_encode($sede)."&id_carrera=".base64_encode($id_carrera)."&cod_asignatura=".base64_encode($cod_asignatura)."&jornada=".base64_encode($jornada)."&grupo=".base64_encode($grupo_curso)."&id_funcionario=".base64_encode($id_funcionario);
	}
}


//consulta si esta creado el registro MAIN contenidoes
if(DEBUG){echo"Busqueda de contenido MAIN<br>";}
if($id_contenidoMain>0){ $consMAIN="SELECT * FROM contenidosMain WHERE idContenidoMain='$id_contenidoMain'";}
else{
	$consMAIN="SELECT * FROM contenidosMain WHERE semestre='$semestre' AND year='$year' AND sede='$sede' AND id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND jornada='$jornada' AND grupo='$grupo_curso' AND id_funcionario='$id_funcionario'";
}
if(DEBUG){ echo"-->$consMAIN<br>";}
$sqliMain=$conexion_mysqli->query($consMAIN)or die("1111:".$conexion_mysqli->error);
$DMain=$sqliMain->fetch_assoc();
	$id_contenidoMain=$DMain["idContenidoMain"];
	$numeroSemanas=$DMain["numero_semanas"];
	$id_carrera=$DMain["id_carrera"];
	$cod_asignatura=$DMain["cod_asignatura"];
	$sede=$DMain["sede"];
	$semestre=$DMain["semestre"];
	$year=$DMain["year"];
	$jornada=$DMain["jornada"];
	$grupo_curso=$DMain["grupo"];
	$id_funcionario=$DMain["id_funcionario"];
	
	if(empty($id_contenidoMain)){$id_contenidoMain=0;}
$sqliMain->free();



$redirigirAconfiguracionInicial=false;	
if($id_contenidoMain==0){
	$redirigirAconfiguracionInicial=true;
}

if($redirigirAconfiguracionInicial){if(DEBUG){ echo"No encontrado idContrenidoMain, URL destino:(config inicial) $urlDestino";}else{header("location: $urlDestino");}}
else{if(DEBUG){echo"idContenidoMain OK : $id_contenidoMain<br>";}}

list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($id_carrera, $cod_asignatura);
$nombre_carrera=NOMBRE_CARRERA($id_carrera);
$nombre_docente=NOMBRE_PERSONAL($id_funcionario);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<title>ver contenidos</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:77px;
	z-index:1;
	left: 5%;
	top: 347px;
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
<!--FIN MENU HORIZONTAL-->


<style type="text/css">
#apDiv2 {
	position:absolute;
	width:50%;
	height:115px;
	z-index:2;
	left: 5%;
	top: 111px;
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
function ELIMINAR(id_contenido, id_contenidoMain)
{
	url="elimina/elimina_1.php?&id_contenido="+id_contenido+"&id_contenidoMain="+id_contenidoMain;
	
	c=confirm('Seguro(a) Desea Eliminar este Registro de la contenido...ż?');
	if(c){ window.location=url;}
}
</script>
</head>

<body>
<h1 id="banner">Administrador -  Registro Contenidos V0.5</h1>

<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a href="#">contenidos</a>
  <ul>
  <li><a href="nueva/nueva_1.php?id_contenidoMain=<?php echo base64_encode($id_contenidoMain);?>&amp;lightbox[iframe]=true&amp;lightbox[width]=750&amp;lightbox[height]=550" class="lightbox">Nuevo Registro</a></li>
  
  </ul>
</li>
<li><a href="#">Ayudas</a>
  <ul>
  <li><a href="informe_imprimible/informe_imprimible_1.php?id_contenidoMain=<?php echo base64_encode($id_contenidoMain);?>&amp;lightbox[iframe]=true&amp;lightbox[width]=500&amp;lightbox[height]=400" class="lightbox">Informe imprimible</a></li>
  
  <li><a href="#">Programa Estudio</a>
  <ul>
<?php
//busco archivos
$cons_A="SELECT * FROM programa_estudio_archivo WHERE id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura'";
$sqli_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
$num_archivos=$sqli_A->num_rows;
$ruta="../../CONTENEDOR_GLOBAL/programa_estudios/";
if($num_archivos>0)
{
	$aux=0;
	while($A=$sqli_A->fetch_assoc())
	{
		$aux++;
		$PE_archivo=$A["archivo"];
		echo'<li><a href="'.$ruta.$PE_archivo.'" target="_blank">Programa_estudio_'.$aux.'</a></li>';
	}
}
else
{ echo'<li><a href="#">Sin Archivos</a></li>';}
$sqli_A->free();
//----------------------------------------//
?>
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
      <td>Docente</td>
      <td colspan="5"><?php echo $nombre_docente;?></td>
    </tr>
    <tr>
      <td>Periodo</td>
      <td colspan="5"><?php echo $semestre;?> Semestre - <?php echo $year;?></td>
    </tr>
    </tbody>
  </table>
</div>

<div id="apDiv1">
<table width="100%" border="1">
<thead>
  <tr>
    <th colspan="9">Contenidos</th>
  </tr>
  <tr>
  	<td>N° Semana</td>
    <td>Fecha Clase</td>
    <td>horario Clase</td>
    <td>Hrs. Duracion Clase</td>
    <td>Contenido</td> 
    <td>Tipo Actividad</td>
    <td>Bibliografia</td>
    <td colspan="2">Opc</td>
  </tr>
  </thead>
  <tbody>
<?php

	if($id_contenidoMain>0){
		$cons_P="SELECT * FROM contenidosDetalle WHERE idContenidoMain='$id_contenidoMain' ORDER by numero_semana, fecha_clase, horario_inicio_clase";
		$sqli_P=$conexion_mysqli->query($cons_P)or die($conexion_mysqli->error);
		$num_registros=$sqli_P->num_rows;
		if(DEBUG){ echo"-->$cons_P<br>numero registros: $num_registros<br>";}
		$SUMA_HORAS_SEMANA=0;
		$numero_semana=0;
		$cuenta_semana=0;
		$numero_semana_old=0;
		$cuentaClases=1;
		if($num_registros>0)
		{
			while($P=$sqli_P->fetch_assoc())
			{
				
				
				$id_contenido=$P["id_contenido"];
			
				$numero_semana=$P["numero_semana"];
				$fecha_clase=$P["fecha_clase"];
				$horario_inicio_clase=$P["horario_inicio_clase"];
				$duracion_clase=$P["duracion_clase"];
				
				$contenido=$P["contenido"];
				$tipo_actividad=$P["tipo_actividad"];
				$bibliografia=$P["bibliografia"];
				
				
				if($numero_semana!==$numero_semana_old){ $cuenta_semana++; $cuentaClases=1;}
				else{$cuentaClases++;}
				$numero_semana_old=$numero_semana;
				
				
				echo'<tr>
						<td>'.$numero_semana.'</td>
						<td>'.$fecha_clase.'</td>
						<td>'.$horario_inicio_clase.'</td>
						<td>'.$duracion_clase.'</td>
						
						<td>'.$contenido.'</td>
						<td>'.$tipo_actividad.'</td>
						<td>'.$bibliografia.'</td>
						<td><a href="edita/edita_1.php?id_contenido='.base64_encode($id_contenido).'&id_contenidoMain='.base64_encode($id_contenidoMain).'&lightbox[iframe]=true&lightbox[width]=750&lightbox[height]=550" class="lightbox" title="Modificar"><img src="../../BAses/Images/b_edit.png" width="16" height="16" alt="edit" /></a></td>
						<td><a href="#" onclick="ELIMINAR('.$id_contenido.', '.$id_contenidoMain.');" title="Eliminar"><img src="../../BAses/Images/b_drop.png" width="16" height="16" alt="Eliminar" /></a></td>
					</tr>';
			}
		}
		else
		{
			echo'<tr><td colspan="9">Sin Registros Creados</td></tr>';
		}
		
	$sqli_P->free();
	}
	$conexion_mysqli->close();
?>
<tr>
	<td><strong><?php echo $cuenta_semana;?>/<?php echo $numeroSemanas;?></strong></td>
    <td><strong></strong></td>
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
			$msj="Registro de contenido Eliminado";
			break;
	}
	echo"$msj $img";
}
?>
</div>
</body>
</html>