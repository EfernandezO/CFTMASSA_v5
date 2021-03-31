<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
	define("DEBUG",false);
//-----------------------------------------//
require("../../../funciones/conexion_v2.php");
require("../../../funciones/funciones_sistema.php");

$id_usuario_actual=$_SESSION["USUARIO"]["id"];
if($_POST)
{
	$semestre=$_POST["semestre"];
	$year=$_POST["year"];
	$sede=$_POST["sede"];
	$id_carrera=$_POST["carrera"];
	$cod_asignatura=$_POST["asignatura"];
	$jornada=$_POST["jornada"];
	$grupo_curso=$_POST["grupo_curso"];
}
elseif($_GET)
{
	$semestre=mysqli_real_escape_string($conexion_mysqli, $_GET["semestre"]);
	$year=mysqli_real_escape_string($conexion_mysqli, $_GET["year"]);
	$sede=mysqli_real_escape_string($conexion_mysqli, $_GET["sede"]);
	$id_carrera=mysqli_real_escape_string($conexion_mysqli, $_GET["id_carrera"]);
	$cod_asignatura=mysqli_real_escape_string($conexion_mysqli, $_GET["cod_asignatura"]);
	$jornada=mysqli_real_escape_string($conexion_mysqli, $_GET["jornada"]);
	$grupo_curso=mysqli_real_escape_string($conexion_mysqli, $_GET["grupo_curso"]);
}

list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($id_carrera, $cod_asignatura);
$nombre_carrera=NOMBRE_CARRERA($id_carrera);
$nombre_docente=NOMBRE_PERSONAL($id_usuario_actual);


///horas de programa
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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<title>ver Planificaciones</title>
 <!-- Fine Uploader New/Modern CSS file
    ====================================================================== -->
   
<link rel="stylesheet" type="text/css" href="../../libreria_publica/fine-uploader-5.2.1/fine-uploader/fine-uploader-new.css">
    <!-- Fine Uploader JS file
    ====================================================================== -->
    <script src="../../libreria_publica/fine-uploader-5.2.1/fine-uploader/fine-uploader.js"></script>
    <!-- Fine Uploader Thumbnails template w/ customization
    ====================================================================== -->
    <script type="text/template" id="qq-template-validation">
        <div class="qq-uploader-selector qq-uploader" qq-drop-area-text="Arrastre Archivo Aqui">
            <div class="qq-total-progress-bar-container-selector qq-total-progress-bar-container">
                <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar"></div>
            </div>
            <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
                <span class="qq-upload-drop-area-text-selector"></span>
            </div>
            <div class="qq-upload-button-selector qq-upload-button">
                <div>Seleccione</div>
            </div>
            <span class="qq-drop-processing-selector qq-drop-processing">
                <span>Eliminando Archivo...</span>
                <span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
            </span>
            <ul class="qq-upload-list-selector qq-upload-list" aria-live="polite" aria-relevant="additions removals">
                <li>
                    <div class="qq-progress-bar-container-selector">
                        <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar"></div>
                    </div>
                    <span class="qq-upload-spinner-selector qq-upload-spinner"></span>
                    <img class="qq-thumbnail-selector" qq-max-size="100" qq-server-scale>
                    <span class="qq-upload-file-selector qq-upload-file"></span>
                    <span class="qq-upload-size-selector qq-upload-size"></span>
                    <button class="qq-btn qq-upload-cancel-selector qq-upload-cancel">Cancelar</button>
                    <button class="qq-btn qq-upload-retry-selector qq-upload-retry">Reintentar</button>
                    <button class="qq-btn qq-upload-delete-selector qq-upload-delete">Borrar</button>
                    <span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
                </li>
            </ul>

            <dialog class="qq-alert-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <div class="qq-dialog-buttons">
                    <button class="qq-cancel-button-selector">Cerrar</button>
                </div>
            </dialog>

            <dialog class="qq-confirm-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <div class="qq-dialog-buttons">
                    <button class="qq-cancel-button-selector">No</button>
                    <button class="qq-ok-button-selector">Si</button>
                </div>
            </dialog>

            <dialog class="qq-prompt-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <input type="text">
                <div class="qq-dialog-buttons">
                    <button class="qq-cancel-button-selector">Cancelar</button>
                    <button class="qq-ok-button-selector">Ok</button>
                </div>
            </dialog>
        </div>
		
    </script>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:77px;
	z-index:1;
	left: 5%;
	top: 389px;
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
	top: 86px;
}
#apDiv3 {
	position:absolute;
	width:35%;
	height:59px;
	z-index:3;
	left: 60%;
	top: 149px;
}
#apDiv4 {
	position:absolute;
	width:90%;
	height:49px;
	z-index:4;
	left: 5%;
	top: 328px;
}
</style>
</head>

<body>
<h1 id="banner">Administrador -  Registro Planificaciones V2.0</h1>

<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a href="#">Planificaciones</a>
</li>
<li><a href="#">Ayudas</a>
  <ul>
  <li><a href="ayudas/programa_word.php?id_carrera=<?php echo base64_encode($id_carrera);?>&cod_asignatura=<?php echo base64_encode($cod_asignatura);?>&sede=<?php echo base64_encode($sede);?>&year=<?php echo base64_encode($year);?>&semestre=<?php echo base64_encode($semestre);?>&jornada=<?php echo base64_encode($jornada);?>&grupo=<?php echo base64_encode($grupo_curso);?>&id_funcionario=<?php echo base64_encode($id_usuario_actual);?>">Plantilla con programa</a></li>
  <li><a href="#">Syllabus</a>
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

<li><a href="#">Pruebas</a>
  <ul>
<?php
//busco archivos
$cons_A="SELECT * FROM banco_pruebas WHERE id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura'";
$sqli_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
$num_archivos=$sqli_A->num_rows;
$ruta="../../CONTENEDOR_GLOBAL/banco_pruebas/";
if($num_archivos>0)
{
	$aux=0;
	while($P=$sqli_A->fetch_assoc())
	{
		$aux++;
		$P_archivo=$P["archivo"];
		$P_tipo=$P["tipo"];
		echo'<li><a href="'.$ruta.$P_archivo.'" target="_blank">'.$aux.'_'.$P_tipo.'</a></li>';
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
<li><a href="index.php">Volver a Seleccion</a></li>
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
<!-- Fine Uploader DOM Element
    ====================================================================== -->
     <div id="fine-uploader-validation"></div>
      <form action="endpoint.php" id="qq-form">
        	<input id="sede" name="sede" type="hidden" value="<?php echo $sede;?>" />
            <input name="id_carrera" id="id_carrera" type="hidden" value="<?php echo $id_carrera;?>" />
            <input name="cod_asignatura" id="cod_asignatura" type="hidden" value="<?php echo $cod_asignatura;?>" />
            <input name="jornada" id="jornada" type="hidden" value="<?php echo $jornada;?>" />
            <input name="grupo" id="grupo" type="hidden" value="<?php echo $grupo_curso;?>" />
            <input name="semestre" id="semestre" type="hidden" value="<?php echo $semestre;?>" />
            <input name="year" id="year" type="hidden" value="<?php echo $year;?>" />
            <input name="id_funcionario" id="id_funcionario" type="hidden" value="<?php echo $id_usuario_actual;?>" />
            
            <input type="submit" value="CARGAR">
        </form>
        
   
     

    <!-- Your code to create an instance of Fine Uploader and bind to the DOM/template
    ====================================================================== -->
    <script>
        var restrictedUploader = new qq.FineUploader({
            element: document.getElementById("fine-uploader-validation"),
            template: 'qq-template-validation',
            request: {
                endpoint: 'endpoint.php'
            },
            thumbnails: {
                placeholders: {
                    waitingPath: '../../libreria_publica/fine-uploader-5.2.1/fine-uploader/placeholders/waiting-generic.png',
                    notAvailablePath: '../../libreria_publica/fine-uploader-5.2.1/fine-uploader/placeholders/not_available-generic.png'
                }
            },
            validation: {
                allowedExtensions: ['docx', 'doc', 'pdf'],
                itemLimit: 1,
                sizeLimit: 7000000 // 50 kB = 50 * 1024 bytes
            },
			callbacks: {
				onAllComplete: function() {
					document.getElementById('apDiv4').innerHTML='<img src="../../BAses/Images/estrella.png" width="50" height="50" alt="OK" /><strong>Planificacion Cargada Correctamente...</strong>'; 
				}
			}
        });
    </script>
</div>
<div id="apDiv3">
</div>
<div id="apDiv4">
<?php
$cons="SELECT * FROM planificaciones_v2 WHERE sede='$sede' AND semestre='$semestre' AND year='$year' AND id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND jornada='$jornada' AND grupo='$grupo_curso' AND id_funcionario='$id_usuario_actual'";
$sqli_P=$conexion_mysqli->query($cons);
$num_planificaciones=$sqli_P->num_rows;

if($num_planificaciones>0)
{
	while($PL=$sqli_P->fetch_assoc())
	{
		$PL_id=$PL["id"];
		$PL_archivo=$PL["archivo"];
		//echo "$PL_archivo<br>";
	}
	echo'<img src="../../BAses/Images/ok.png" width="29" height="26" alt="ok" />Ya tiene su planificación cargada en este curso, si carga otra, reemplazará a la anterior. para ver la ultima planificacion cargada click <a href="descarga_planificacion.php?id_planificacion='.base64_encode($PL_id).'&lightbox[iframe]=true&lightbox[width]=500&lightbox[height]=400" class="lightbox">aqui</a>';
}
else
{
	echo'<img src="../../BAses/Images/b_drop.png" width="16" height="16" alt="X" /> NO hay planificaciones cargadas...<br>';
}

$sqli_P->free();
$conexion_mysqli->close();
?>
</div>
</body>
</html>