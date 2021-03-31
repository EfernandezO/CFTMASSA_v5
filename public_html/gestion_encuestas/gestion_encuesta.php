<?php
//--------------CLASS_okalis------------------//
	require("../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../funciones/";
	$O->clave_del_archivo=md5("Gestion de Encuestas_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$privilegio=$_SESSION["USUARIO"]["privilegio"];
switch($privilegio)
{
	case"admi_total":
		$permite_editar=true;
		$url_menu="../Administrador/ADmenu.php";
		$num_opciones=11;
		break;
	case"admi":
		$permite_editar=true;
		$url_menu="../Administrador/ADmenu.php";
		$num_opciones=11;
		break;
	case"finan":
		$permite_editar=false;
		$url_menu="../contabilidad/index.php";
		$num_opciones=7;
		break;
	case"ALUMNO":
		$permite_editar=false;
		$url_menu="../Alumnos/okalumno.php";
		$num_opciones=7;
		break;
	case"Docente":
		$permite_editar=false;
		$url_menu="../Docentes/okdocente.php";
		$num_opciones=7;
		break;	
	case"jefe_carrera":
		$permite_editar=false;
		$url_menu="../Docentes/okdocente.php";
		$num_opciones=7;
		break;
	case"ex_alumno":
		$permite_editar=false;
		$url_menu="../ex_alumnos/ex_alumnos_MENU.php";
		$num_opciones=7;
		break;	
}

if(isset($_SESSION["ENCUESTA"]["contestada"]))
{ unset($_SESSION["ENCUESTA"]["contestada"]);}


$continuar_1=false;
$continuar_2=false;
$continuar_3=false;


if(isset($_GET["tipo_usuario"]))
{ $continuar_1=true;}

if(isset($_GET["id_usuario"]))
{ $continuar_2=true;}

if(isset($_GET["id_carrera"]))
{ $continuar_3=true;}

if($continuar_1 and $continuar_2 and $continuar_3)
{
	if(DEBUG){ echo"HAY GET<br>";}
	$tipo_usuario=$_GET["tipo_usuario"];
	$id_usuario=base64_decode($_GET["id_usuario"]);
	$id_carrera=base64_decode($_GET["id_carrera"]);
}
else
{
	if(DEBUG){ echo"No hay Get<br>";}
	$tipo_usuario=$_SESSION["USUARIO"]["tipo"];
	$id_usuario=$_SESSION["USUARIO"]["id"];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../funciones/codificacion.php");?>
<title>Menu encuestas - Gestion</title>
	<link rel="stylesheet" type="text/css" href="../libreria_publica/jquery_treeview/demo/screen.css">
    <link rel="stylesheet" type="text/css" href="../libreria_publica/hint.css-master/hint.css"/>
    <script type="text/javascript" language="javascript" src="../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.js"></script>
<!--INICIO MENU HORIZONTAL-->
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
</script>
<!--FIN MENU HORIZONTAL-->
	<style type="text/css" title="currentStyle">
			@import "../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/css/demo_page.css";
			@import "../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/css/demo_table_jui.css";
			@import "../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/examples/examples_support/themes/smoothness/jquery-ui-1.8.4.custom.css";
			
			
    </style>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:52px;
	z-index:1;
	left: 5%;
	top: 157px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:23px;
	z-index:2;
	left: 30%;
	top: 107px;
	text-align: center;
	font-size: medium;
	font-weight: bold;
	border: thin dashed #1E78C3;
}
-->
</style>

<script type="text/javascript" language="javascript" src="../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="ISO-8859-1">
			$(document).ready(function() {
				oTable = $('#example').dataTable({
					"bJQueryUI": true,
					"bPaginate": false
				});
			} );
function CONFIRMAR(url)
{
	c=confirm('Seguro(a) Desea Eliminar esta encuesta');
	if(c)
	{
		window.location=url;
	}
}
</script>
</head>

<body>
<h1 id="banner"> Gesti&oacute;n Encuestas</h1>
<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a href="#">Encuestas</a>
	<ul>
    	 <li><?php if($permite_editar){?><a href="encuestas/nva_encuesta/ingreso_encuesta_1.php">Nueva Encuesta</a><?php }?></li>
        <li><a href="index.php">Ir a Revision </a></li>
    </ul>
</li>
<li><a href="#">Evaluacion Docente</a>
	<ul>
    	 <li><a href="resultados_evaluacion_docente/resultados_evaluacion_docente.php">Resultados Evaluacion Docente</a></li>
    </ul>
</li>

    <li><a href="<?php echo $url_menu;?>">Menu Principal</a></li>
</ul>
<br style="clear: left" />
</div>
</div>
<div id="apDiv2">Encuestas Disponibles<br />
</div>
<div id="apDiv1" class="demo_jui">
 
    <table width="100%" border="1" align="center" class="display" id="example">
        <thead>
          <tr>
            <th>N</th>
            <th>id</th>
            <th>Nombre</th>
            <th>Descripcion</th>
            <th>Num Preguntas</th>
            <th colspan="4">Visibilidad</th>
            <th>Utilizar para evaluacion Docente</th>
            <th>Utilizar para evaluacion JC</th>
            <th>Utilizar para evaluacion JC -> Docente</th>
            <th>Utilizar para autoevaluacion Docente</th>
            <th colspan="<?php echo $num_opciones-3;?>">Opciones</th>
          </tr>
          </thead>
        <tbody>
          <?php
	  require("../../funciones/conexion_v2.php");
	  include("../../funciones/VX.php");
	  //----------------------------------//
	  $evento="Ingreso a Gestion Encuestas";
	  REGISTRA_EVENTO($evento);
	   //----------------------------//
	   
	   $cons="SELECT * FROM encuestas_main ORDER by id_encuesta Desc";
	   $sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	   $num_encuestas=$sql->num_rows;
	   if($num_encuestas>0)
	   {
		   $contador=0;
			while($C=$sql->fetch_assoc())
			{
				$contador++;
				
				$visible_alumno=$C["visible_alumno"];
				$visible_docente=$C["visible_docente"];
				$visible_exalumno=$C["visible_exalumno"];
				$visible_jefe_carrera=$C["visible_jefe_carrera"];
				
				$utilizar_para_evaluacion_docente=$C["utilizar_para_evaluacion_docente"];
				$utilizar_para_evaluacion_JC=$C["utilizar_para_evaluacion_JC"];
				$utilizar_para_evaluacion_JC_D=$C["utilizar_para_evaluacion_JC_D"];
				$utilizar_para_autoevaluacion_docente=$C["utilizar_para_autoevaluacion_docente"];
				
				if(DEBUG){ echo"utilizar para evaluacion_docente:$utilizar_para_evaluacion_docente<br>utilizar para evalaucion JC_D: $utilizar_para_evaluacion_JC_D<br>Utilizar para autoevalucion docente: $utilizar_para_autoevaluacion_docente<br>";}
				
				if($utilizar_para_evaluacion_docente==1){ $utilizar_para_evaluacion_docente_label="si"; $color_1="#00ff00";}
				else{ $utilizar_para_evaluacion_docente_label="No"; $color_1="";}
				
				if($utilizar_para_evaluacion_JC_D==1){ $utilizar_para_evaluacion_JC_D_label="si"; $color_2="#00ff00";}
				else{$utilizar_para_evaluacion_JC_D_label="No"; $color_2="";}
				
				if($utilizar_para_evaluacion_JC==1){ $utilizar_para_evaluacion_JC_label="si"; $color_4="#00ff00";}
				else{$utilizar_para_evaluacion_JC_label="No"; $color_4="";}
				
				if($utilizar_para_autoevaluacion_docente==1){$utilizar_para_autoevaluacion_docente_label="si"; $color_3="#00ff00";}
				else{$utilizar_para_autoevaluacion_docente_label="No"; $color_3="";}
				
				if(empty($visible_alumno)){ $visible_alumno="off";}
				if(empty($visible_docente)){ $visible_docente="off";}
				if(empty($visible_exalumno)){ $visible_exalumno="off";}
				if(empty($visible_jefe_carrera)){ $visible_jefe_carrera="off";}
				
				$id_encuesta=$C["id_encuesta"];
				$nombre_encuesta=$C["nombre"];
				$descripcion_encuesta=substr($C["descripcion"],0,50)."...";
				$fecha_generacion_encuesta=$C["fecha_generacion"];
				$cod_user_encuesta=$C["cod_user"];
				/////num preguntas
				$cons_NP="SELECT COUNT(id_pregunta) FROM encuestas_pregunta WHERE id_encuesta='$id_encuesta'";
				$sql_NP=$conexion_mysqli->query($cons_NP)or die($conexion_mysqli->error);
					$Dnp=$sql_NP->fetch_row();
					$num_preguntas=$Dnp[0];
				$sql_NP->free();
				if(empty($num_preguntas)){ $num_preguntas=0;}
				/////////////////////////////////////
				$cons_YC="SELECT COUNT(id_resultados) FROM encuestas_resultados WHERE id_usuario='$id_usuario' AND tipo_usuario='$tipo_usuario' AND id_encuesta='$id_encuesta'";
				$sql_YC=$conexion_mysqli->query($cons_YC)or die($conexion_mysqli->error);
					$Dyc=$sql_YC->fetch_row();
					$num_resultados=$Dyc[0];
				$sql_YC->free();
				if(empty($num_resultados)){ $num_resultados=0;}
				if(DEBUG){ echo"--->$cons_YC<br> num resultados: $num_resultados<br>";}
				
				if($num_resultados>0)
				{ $encuesta_ya_contestada=true;}
				else
				{ $encuesta_ya_contestada=false;}
				
				
				switch($privilegio)
				{
					case"ALUMNO":
						if($visible_alumno=="on"){ $ver_encuesta=true;}
						else{ $ver_encuesta=false;}
						break;
					case"Docente":
						if($visible_docente=="on"){ $ver_encuesta=true;}
						else{ $ver_encuesta=false;}
						break;
					case"jefe_carrera":
						if($visible_jefe_carrera=="on"){ $ver_encuesta=true;}
						else{ $ver_encuesta=false;}
						break;	
					case"ex_alumno":
						if($visible_exalumno=="on"){ $ver_encuesta=true;}
						else{ $ver_encuesta=false;}
						break;
					default:
						$ver_encuesta=true;
				}
				
				
				
				if($ver_encuesta)
				{
				echo'<tr class="gradeA" height="35">
						<td>'.$contador.'</td>
						<td>'.$id_encuesta.'</td>
						<td>'.$nombre_encuesta.'</td>
						<td>'.$descripcion_encuesta.'</td>
						<td>'.$num_preguntas.'</td>';
					
					if($permite_editar)	
					{
					echo'<td><a href="#" class="hint--bottom" data-hint="Visible para Alumno">'.$visible_alumno.'</a></td>
						<td><a href="#" class="hint--bottom" data-hint="Visible para Docente">'.$visible_docente.'</a></td>
						<td><a href="#" class="hint--bottom" data-hint="Visible Jefe Carrera">'.$visible_jefe_carrera.'</a></td>
						<td><a href="#" class="hint--bottom" data-hint="Visible para Exalumno">'.$visible_exalumno.'</a></td>
						<td bgcolor='.$color_1.'>'.$utilizar_para_evaluacion_docente_label.'</td>
						<td bgcolor='.$color_4.'>'.$utilizar_para_evaluacion_JC_label.'</td>
						<td bgcolor='.$color_2.'>'.$utilizar_para_evaluacion_JC_D_label.'</td>
						<td bgcolor='.$color_3.'>'.$utilizar_para_autoevaluacion_docente_label.'</td>
						<td><a href="preguntas/ver_preguntas.php?id_encuesta='.$id_encuesta.'">preguntas</a></td>
						<td><a href="encuestas/editar_encuesta/edita_encuesta1.php?id_encuesta='.$id_encuesta.'">Editar</a></td>
						<td><a href="encuestas/eliminar_encuesta/elimina_encuesta.php?id_encuesta='.$id_encuesta.'">Eliminar</a></td>
						<td><a href="encuestas/copiar_encuesta/copiar_encuesta_1.php?id_encuesta='.$id_encuesta.'">Copiar</a></td>
						<td>&nbsp;</td>';
					}
					echo'<td align="center">';
						if($encuesta_ya_contestada)
						{ echo'<a href="#" class="button_R">Ya Contestada</a>';}
						else
						{ echo'<a href="contestar_encuesta/index.php?id_encuesta='.base64_encode($id_encuesta).'&tipo_usuario='.base64_encode($tipo_usuario).'&id_usuario='.base64_encode($id_usuario).'" class="button">Contestar</a>';}
						
						echo'</td>
					 </tr>';
				}
			}
		}
		$sql->free();
	   $conexion_mysqli->close();
       ?>
          </tbody>
        </table>
    <div id="msj">
    <?php
    if(isset($_GET["error"]))
	{
		$error=$_GET["error"];
		$img="";
		$msj="";
		$img_ok='<img src="../BAses/Images/ok.png" width="29" height="26" alt="ok" />';
		$img_error='<img src="../BAses/Images/b_drop.png" width="16" height="16" alt="X" />';
		switch($error)
		{
			case"D0":
				$msj="Decreto Modificado Correctamente...";
				$img=$img_ok;
				break;
			case"CC1":
				$msj="Fallo al Grabar encuesta...";
				$img=$img_error;
				break;
			case"CC0":
				$msj="encuesta Agregada...";
				$img=$img_ok;
				break;	
			case"CE0":
				$msj="encuesta Copiada...";
				$img=$img_ok;
				break;	
			case"CE!":
				$msj="ERROR al Copiar Encuesta...";
				$img=$img_error;
				break;			
				
					
		}
		echo"$msj $img";
	}
	?>
</div>
</div>
</body>
</html>