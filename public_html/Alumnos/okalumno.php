<?php
//-----------------------------------------//
	require("../OKALIS/seguridad.php");
	require("../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="ALUMNO";
	OKALIS($lista_invitados);
	////////////////////////
	//
	include("../../funciones/conexion.php");
	require("../OKALIS/msj_error/anti_2_login.php");
	mysql_close($conexion);
	define("DEBUG",false);
//-----------------------------------------//	
?>
<html>
<head>
<title>Menu alumnos | CFTMASS</title>
<?php include("../../funciones/codificacion.php"); ?>
<link rel="stylesheet" type="text/css" href="../libreria_publica/jquery_treeview/jquery.treeview.css">
	<link rel="stylesheet" type="text/css" href="../libreria_publica/jquery_treeview/demo/screen.css">
    <link rel="stylesheet" type="text/css" href="../CSS/tabla_2.css">
	<link rel="stylesheet" type="text/css" href="../libreria_publica/hint.css-master/hint.css">
	<script type="text/javascript" src="../libreria_publica/jquery_libreria/jquery.min_1.2.6.js"></script>
	
	<script src="../libreria_publica/jquery_treeview/lib/jquery.cookie.js" type="text/javascript"></script>
	<script src="../libreria_publica/jquery_treeview/jquery.treeview.js" type="text/javascript"></script>
	
	<script type="text/javascript">
		$(function() {
			$("#browser").treeview();
		});
	</script>
 <!--INICIO LIGHTBOX EVOLUTION-->
  <script type="text/javascript" src="../libreria_publica/lightbox_1.6.9_evolution/js/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.css" />
  <!--[if IE 6]>
  <link rel="stylesheet" type="text/css" href="../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.ie6.css" />
  <![endif]-->
  <script type="text/javascript" src="../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/jquery.lightbox.min.js"></script>
 <script type="text/javascript">
    jQuery(document).ready(function($){
      $('.lightbox').lightbox(); 
    });
  </script>
 <!--FIN LIGHTBOX EVOLUTION--> 
<style type="text/css">
<!--
a:link {
	text-decoration: none;
	color: #6699CC;
}
a:visited {
	text-decoration: none;
	color: #6699CC;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #6699CC;
}
.Estilo1 {
	color: #800040;
	font-weight: bold;
}
.Estilo2 {
	font-size: 12px;
	font-weight: bold;
}
.Estilo3 {font-size: 12px}
#apDiv1 {
	position:absolute;
	width:75px;
	height:107px;
	z-index:1;
	left: 727px;
	top: 267px;
}
#apDiv2 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:2;
	left: 162px;
	top: 61px;
}
#tabla {
	width: 90%;
}
#tabla #imagen {
	width: 108px;
	height: auto;
	float: left;
}
#tabla #contenido_tabla {
	width: 450px;
	position: relative;
	height: auto;
}
.Estilo6 {font-size: 12px; font-style: italic; }
-->
</style>
    <!--INICIO MENU HORIZONTAL-->
 <link rel="stylesheet" type="text/css" href="../libreria_publica/menu_horizontal/ddsmoothmenu-v.css"/>  
  <link rel="stylesheet" type="text/css" href="../libreria_publica/menu_horizontal/ddsmoothmenu.css"/>
 
<script type="text/javascript" src="../libreria_publica/menu_horizontal/ddsmoothmenu.js">

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

ddsmoothmenu.init({
	mainmenuid: "smoothmenu2", //Menu DIV id
	orientation: 'v', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu-v', //class added to menu's outer DIV
	//customtheme: ["#804000", "#482400"],
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})

</script>
<!--FIN MENU HORIZONTAL-->
<style type="text/css">
#apDiv3 {
	position:absolute;
	width:60%;
	height:115px;
	z-index:1;
	left: 35%;
	top: 299px;
}
#apDiv4 {
	position:absolute;
	width:45%;
	height:115px;
	z-index:1;
	left: 50%;
	top: 322px;
}
</style>
</head>
<?php 
   require('../../funciones/conexion.php');
   
   $mes_actual=date("m");
   $year_actual=date("Y");
   if($mes_actual>=8){ $semestre_actual=2;}
   else{ $semestre_actual=1;}
   
   $id_alumno=$_SESSION["USUARIO"]["id"];
   $id_carrera=$_SESSION["USUARIO"]["id_carrera"];
   $cons="SELECT * FROM alumno WHERE id='$id_alumno' LIMIT 1";
   //--------------------------------------------------//
 	 include("../../funciones/VX.php");
	 //cambio estado_conexion USER-----------
	 CAMBIA_ESTADO_CONEXION_ALUMNO($id_alumno, "ON");
	//-----------------------------------------------//
   $sql=mysql_query($cons)or die(mysql_error());
   
   		$row = mysql_fetch_assoc($sql);
    	$nombre=$row["nombre"];
    	$apellido_P=$row["apellido_P"];
		$apellido_M=$row["apellido_M"];
    	$carrera=$row["carrera"];
		$sede=$row["sede"];
		$nivel=$row["nivel"];
		$grupo=$row["grupo"];
		$jornada=$row["jornada"];
		$nombre_alumno="$nombre $apellido_P $apellido_M";
	mysql_free_result($sql);
	$msj_AVISOS="";
	///////////////////
	$cons2="SELECT COUNT(id) FROM avisos WHERE id_carrera='$id_carrera' AND sede='$sede'";
	$sql2=mysql_query($cons2)or die(mysql_error());
		$D=mysql_fetch_row($sql2);
		$numero_avisos=$D[0];
		mysql_free_result($sql2);
		if(empty($numero_avisos)){ $numero_avisos=0;}
		
		if($numero_avisos>0)
		{ $msj_AVISOS='<a href="ver_avisos/ver_avisos.php?lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=500" class="lightbox" title="Click Para ver Avisos">Hay ('.$numero_avisos.') Avisos...</a>';}
		else{ $msj_AVISOS="No Hay Avisos...";}
	///////////////////////////
		///////////
	//revision solicitudes
	
	//Solicitudes//
$ARRAY_SOLICITUDES=array();
$num_solicitudes_autorizadas=0;
$num_solicitudes_no_autorizadas=0;

	$cons="SELECT * FROM solicitudes WHERE tipo_receptor='alumno' AND id_receptor='$id_alumno' AND id_carrera_receptor='$id_carrera' AND estado='pendiente' ORDER by id Desc";
		$sql=mysql_query($cons)or die(mysql_error());
		$num_solicitudes=mysql_num_rows($sql);
		if(DEBUG){ echo"--> $cons<br>Num Solicitudes: $num_solicitudes<br>";}
		if($num_solicitudes>0)
		{
			$aux=0;
			while($S=mysql_fetch_assoc($sql))
			{
				$aux++;
				$S_id=$S["id"];
				$S_tipo=$S["tipo"];
				$S_categoria=$S["categoria"];
				$S_tipo_solicitante=$S["tipo_solicitante"];
				$S_id_solicitante=$S["id_solicitante"];
				$S_id_carrera_solicitante=$S["id_carrera_solicitante"];
				$S_fecha_hora_solicitud=$S["fecha_hora_solicitud"];
				$S_autorizado=$S["autorizado"];
				$S_id_autorizador=$S["id_autorizador"];
				$S_tipo_autorizador=$S["tipo_autorizador"];
				$S_fecha_hora_autorizacion=$S["fecha_hora_autorizacion"];
				$S_estado=$S["estado"];
				$S_fecha_hora_creacion=$S["fecha_hora_creacion"];
				
				if($S_autorizado=="no")
				{ $num_solicitudes_no_autorizadas++;}
				elseif($S_autorizado=="si")
				{ $num_solicitudes_autorizadas++;}
				
				$ARRAY_SOLICITUDES[$S_id]["categoria"]=$S_categoria;
				$ARRAY_SOLICITUDES[$S_id]["autorizado"]=$S_autorizado;
			}
		}
	
	
	mysql_close($conexion);
?>
<body>
<h1 id="banner">Alumno - Men&uacute; Principal </h1>
 <div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a href="#">Aplicaciones</a>
  <ul>
  <li><a href="../dactilografia/Lecciones_disponibles.php">Dactilografia</a></li>
  </ul>
</li>
<!--OPCIONES SOLICITUDES-->
<li>
	<?php if(count($ARRAY_SOLICITUDES)>0)
	{
		$informacion_solicitudes='<a href="#" class="hint--top  hint--error hint--always" data-hint="'.$num_solicitudes.' Pendientes">Solicitud Certificados</a>';
	}
	else{ $informacion_solicitudes='<a href="#">Solicitud Certificados</a>';}
	
	echo $informacion_solicitudes;
  	?>
     <ul>
  		<li><a href="#">Solicitudes Pendientes</a>
        	<ul>
            	<?php 
				if(count($ARRAY_SOLICITUDES)>0)
				{
					$ver_solicitud_actual=false;
					$cuenta_solicitud=0;
					foreach($ARRAY_SOLICITUDES as $nS =>$valorS)
					{ 
						$cuenta_solicitud++;
						$aux_categoria=$valorS["categoria"];
						$aux_autorizado=$valorS["autorizado"];
						$ver_solicitud_actual=true;
						$url_solicitud="../solicitudes/revisiones/revisar_solicitudes_global.php";
						
					   if($ver_solicitud_actual)
					   {
						   echo'<li><a href="'.$url_solicitud.'" class="hint--right  hint--info" data-hint="Autorizado -> '.$aux_autorizado.'" title="click para informacion">'.$cuenta_solicitud.'->'.$aux_categoria.'</a></li>';
					   }
					}
				}
				else
				{ echo'<li><a href="#">Sin Solicitudes...</a></li>';}?>
            </ul>
        </li>
        <li><a href="../solicitudes/crea_solicitud/crea_solicitud_1.php?TB_iframe=true&height=400&width=750" rel="sexylightbox">Crear Solicitud</a></li>
    </ul>
</li> 

<!--FIN OPCIONES SOLICITUDES--> 
<li><a href="../OKALIS/msj_error/salir.php">Salir</a></li>
</ul>
<br style="clear: left" />
</div> 

	<br>
	<div id="tabla">
		    <div id="imagen"><img src="../BAses/Images/alumno_estudiando_icon.jpg" alt="Alumno" width="103" height="95"></div>
            
		    <div id="contenido_tabla">
            <table width="103%" height="96" border="0">
            <thead>
            <th colspan="3">Mis Datos
              </thead></th>
            <tbody>
          <tr>
            <td width="23%"><span class="Estilo2">Alumno Sr(ita)</span></td>
            <td width="77%"><span class="Estilo6"><?php echo ucwords(strtolower($nombre_alumno)); ?></span></td>
          </tr>
          <tr>
            <td><span class="Estilo2">Carrera</span></td>
            <td><span class="Estilo6"><?php echo $carrera;?></span></td>
          </tr>
          <tr>
            <td><span class="Estilo2">Sede</span></td>
            <td><span class="Estilo6"><?php echo $sede;?></span></td>
          </tr>
          <tr>
            <td height="20" valign="top"><span class="Estilo3"><strong>ID Alumno</strong></span></td>
            <td valign="top"><span class="Estilo6"><?php echo $id_alumno;?></span></td>
          </tr>
          <tr>
            <td height="20" colspan="2" valign="top" align="right">
            <?php
            	echo $msj_AVISOS;
			?>
            </td>
            </tr>
            </tbody>
        </table>
        </div>
</div>
		 
    <br>

<div id="main">
<h3>&nbsp;</h3>
<h3>¿Que desea Hacer Ahora?</h3>
<ul id="browser" class="filetree">
	  <li class="Estilo1"><strong><img src="../libreria_publica/jquery_treeview/images/file.gif" alt="X" /> <a href="edicion_alumno/malumnox.php?lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=500" class="lightbox">Antecedentes Personales</a></strong></li>
	  <li class="Estilo1"><img src="../libreria_publica/jquery_treeview/images/file.gif" alt="X" /> <a href="pedido_libro/pedido_libro.php?lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=500"  class="lightbox">Libros Pedidos</a></li>
	  <li class="Estilo1"><img src="../libreria_publica/jquery_treeview/images/file.gif" alt="X" /> <a href="notas_semestrales/listacalifica.php?lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=500"  class="lightbox">Calificaciones Semestrales</a></li>
	  <li class="Estilo1"><img src="../libreria_publica/jquery_treeview/images/file.gif" alt="X" /> <a href="notas_parciales_3/ver_notas_parciales_v3_1.php?lightbox[iframe]=true&lightbox[width]=750&lightbox[height]=550"  class="lightbox">Calificaciones Parciales</a> v3</li>
	  <li class="Estilo1"><img src="../libreria_publica/jquery_treeview/images/file.gif" alt="X" /> <a href="consulta_cuotas/cons_letra1.php?lightbox[iframe]=true&lightbox[width]=650&lightbox[height]=550"  class="lightbox">Estado Financiero</a></li>
	  <li class="Estilo1"> <img src="../libreria_publica/jquery_treeview/images/file.gif" alt="X" /> <a href="ver_observaciones/ver_observaciones.php?lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=500"  class="lightbox">Ver Observaciones</a></li>
	  <li class="Estilo1"><img src="../libreria_publica/jquery_treeview/images/file.gif" alt="X" /> <a href="../gestion_encuestas/index.php">Ver Encuestas</a></li>
    <li class="Estilo1"><img src="../libreria_publica/jquery_treeview/images/file.gif" alt="X" /> <a href="recursosXasignatura/listadorXasignatura.php">Recursos Descargables</a></li>
	  <li class="Estilo1"><img src="../libreria_publica/jquery_treeview/images/file.gif" alt="X" /> <a href="../horario/ver/ver_horario.php?sede=<?php echo $sede?>&id_carrera=<?php echo $id_carrera;?>&nivel=<?php echo $nivel?>&jornada=<?php echo $jornada;?>&grupo=<?php echo $grupo;?>&semestre=<?php echo $semestre_actual;?>&year=<?php echo $year_actual;?>&TB_iframe=true&height=400&width=800" rel="sexylightbox" target="_blank">Horario</a><br />
    </li>
</ul>
</div>

</body>
</html>
