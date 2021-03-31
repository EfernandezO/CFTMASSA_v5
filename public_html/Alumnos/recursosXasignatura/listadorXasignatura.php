<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="ALUMNO";
	OKALIS($lista_invitados);
//-----------------------------------------//	
	define("DEBUG",false);


if($_GET)
{ $id_asignatura_seleccionada=$_GET["id_asignatura"];}
else
{ $id_asignatura_seleccionada=0;}

$id_alumno=$_SESSION["USUARIO"]["id"];
$sede=$_SESSION["USUARIO"]["sede"];
$id_carrera=$_SESSION["USUARIO"]["id_carrera"];


$ARRAY_ASIGNATURAS=array();
////////>-------------------------------------------------------</////////
///////////<:/:>---------------------------------------<:/:>//////////////
require("../../../funciones/conexion_v2.php");
require("../../../funciones/funciones_sistema.php");

$mes_actual=date("m");
$year_actual=date("Y");

if($mes_actual>=8)
{ $semestre_actual=2;}
else{ $semestre_actual=1;}

$carrera=NOMBRE_CARRERA($id_carrera);
$condicion_toma_ramo=ASIGNATURAS_TOMADAS($id_alumno, $id_carrera, $semestre_actual, $year_actual);

 //--------------------------------------------------//
 	 include("../../../funciones/VX.php");
	 //cambio estado_conexion USER-----------
	 CAMBIA_ESTADO_CONEXION_ALUMNO($id_alumno, "ON");
	 $evento="Revisa Recursos X asignatura";
	 REGISTRA_EVENTO($evento);
	//-----------------------------------------------//
//busco datos de alumno
$cons_A="SELECT * FROM alumno WHERE id='$id_alumno' LIMIT 1";	
$sql_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
$DAX=$sql_A->fetch_assoc();
	$alumno_jornada=$DAX["jornada"];
	$alumno_grupo_curso=$DAX["grupo"];
$sql_A->free();	
	
/*Busco asignaturas con carga de recurso para la carrera del alumno*/
$cons="SELECT DISTINCT (cod_asignatura) FROM contenedor_archivos  WHERE seccion='archivosXasignatura' AND id_carrera='$id_carrera' AND sede='$sede' AND jornada='$alumno_jornada' AND grupo_curso='$alumno_grupo_curso' $condicion_toma_ramo";
$sql=$conexion_mysqli->query($cons) or die($conexion_mysqli->error);
$num_asignaturas=$sql->num_rows;
if(DEBUG){ echo"-->$cons<br> NUM ASIGNATURAS: $num_asignaturas<br>";}
if($num_asignaturas>0)
{
	$primera=true;
	while($A=$sql->fetch_row())
	{
		$aux_id_asignatura=$A[0];
		if($primera)
		{
			if($id_asignatura_seleccionada<=0) 
			{ $id_asignatura_seleccionada=$aux_id_asignatura;}
			$primera=false;
		}
		
		$consX="SELECT ramo FROM mallas WHERE cod='$aux_id_asignatura' AND id_carrera='$id_carrera' LIMIT 1";
		$sqlX=$conexion_mysqli->query($consX)or die($conexion_mysqli->error);
			$DA=$sqlX->fetch_assoc();
			$aux_nombre_asignatura=$DA["ramo"];
		$sqlX->free();
		if(DEBUG){ echo"--->$consX<br> nombre asignatura: $aux_nombre_asignatura<br>";}
		
		$ARRAY_ASIGNATURAS[$aux_id_asignatura]=$aux_nombre_asignatura;
	}
}
$sql->free();

if(DEBUG){ echo"ID asignatura seleccionada: $id_asignatura_seleccionada<br>";}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Recursos X asignatura</title>

		<link rel="stylesheet" type="text/css" href="../../libreria_publica/CircleHoverEffects/css/demo.css"/>
		<link rel="stylesheet" type="text/css" href="../../libreria_publica/CircleHoverEffects/css/common.css" />
        <link rel="stylesheet" type="text/css" href="../../libreria_publica/CircleHoverEffects/css/style7.css" />
        <link rel="stylesheet" type="text/css" href="../../CSS/estilo_fuentes.css"/>
		<script type="text/javascript" src="../../libreria_publica/CircleHoverEffects/js/modernizr.custom.79639.js"></script> 
		<!--[if lte IE 8]><style>.main{display:none;} .support-note .note-ie{display:block;}</style><![endif]-->
        
         <script type="text/javascript" src="../../libreria_publica/jquery_libreria/mootools-yui-compressed.js"></script>
  <script type="text/javascript" src="../../libreria_publica/sexy_lightbox/Mootools/sexylightbox.v2.3.mootools.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../../libreria_publica/sexy_lightbox/Mootools/sexylightbox.css"/>
  <script type="text/javascript">
    window.addEvent('domready', function(){
      SexyLightbox = new SexyLightBox({color:'black', dir: '../../libreria_publica/sexy_lightbox/Mootools/sexyimages/'});
    });
  </script>
</head>

<body>
 <div class="container">
		
			<!-- Codrops top bar --><!--/ Codrops top bar -->
			
			<header>
			
				<h1><strong>Recursos</strong> Descargables X Asignatura </br> <?php echo $carrera;?></h1>
				<h2>Seleccione la Asignatura que le intereza.</h2>
				
				<nav class="codrops-demos">
                <?php
                	if(count($ARRAY_ASIGNATURAS)>0)
					{
						if(DEBUG){ echo"asignaturas<br>";}
						foreach($ARRAY_ASIGNATURAS as $n => $valor)
						{
							if($n==$id_asignatura_seleccionada)
							{ echo'<a href="listadorXasignatura.php?id_asignatura='.$n.'" class="current-demo" title="Click para ver los recursos de esta Asignatura">'.$valor.'</a>';}
							else{ echo'<a href="listadorXasignatura.php?id_asignatura='.$n.'" title="Click para ver los recursos de esta Asignatura">'.$valor.'</a>';}
						}
					}
					else
					{
						if(DEBUG){ echo"SIN asignaturas<br>";}
					}
				?>
				</nav>
            <a href="../alumno_menu.php" title="Click Para Volver al Menu"> Volver al Menu </a></header>
			
			<section class="main">
			
				<ul class="ch-grid">
					<?php
						//carga de archivos segun id asignatura seleccionada
						$cons_A="SELECT * FROM contenedor_archivos WHERE seccion='archivosXasignatura' AND id_carrera='$id_carrera' AND sede='$sede' AND cod_asignatura='$id_asignatura_seleccionada' AND jornada='$alumno_jornada' AND grupo_curso='$alumno_grupo_curso' AND year='$year_actual' ORDER by fecha_generacion";
						$sql_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
						$num_archivos_encontrados=$sql_A->num_rows;
						if(DEBUG){ echo"$cons_A<br>NUM archivos: $num_archivos_encontrados<br>";}
						if($num_archivos_encontrados>0)
						{
							while($AR=$sql_A->fetch_assoc())
							{
								$archivo_id=$AR["id"];
								$archivo_titulo=$AR["titulo"];
								$archivo_descripcion=$AR["descripcion"];
								$archivo_nombre=$AR["archivo"];
								
								$extencion_archivo=end(explode(".",$archivo_nombre));
								$archivo_fecha=$AR["fecha_generacion"];
								$archivo_cod_user=$AR["cod_user"];
								
								$cons_P="SELECT nombre, apellido FROM personal WHERE id='$archivo_cod_user' LIMIT 1";
								$sql_P=$conexion_mysqli->query($cons_P)or die($conexion_mysqli->error);
									$DP=$sql_P->fetch_assoc();
									$aux_nombre_usuario=$DP["nombre"]." ".$DP["apellido"];
								$sql_P->free();	
								
								switch($extencion_archivo)
								{
									case"xlsx":
										$icono='ch-img-excel';
										break;
									case"xls":
										$icono='ch-img-excel';
										break;	
									case"docx":
										$icono='ch-img-word';
										break;
									case"doc":
										$icono='ch-img-word';
										break;	
									case"pdf":
										$icono='ch-img-pdf';
										break;	
									default:
										$icono='ch-img-default';		
								}
								
								echo'<li>
						<div class="ch-item">				
							<div class="ch-info">
								<div class="ch-info-front '.$icono.'"></div>
								<div class="ch-info-back">
									<h3>'.$archivo_titulo.'</h3>
									<p>'.$aux_nombre_usuario.' <a href="enrutador.php?ruta='.base64_encode($archivo_nombre).'&extencion='.base64_encode($extencion_archivo).'&TB_iframe=true&height=310&width=450" rel="sexylightbox" title="'.$archivo_descripcion.'">Ver Archivo</a></p>
								</div>	
							</div>
						</div>
					</li>';
							}
						}
						else
						{
							echo'Lo Sentimos Aún no se an Cargado Recursos en esta Carrera... :(';
						}
						$sql_A->free();
						$conexion_mysqli->close();
                    ?>
				</ul>
				
			</section>
        </div>
</body>
</html>
<?php
function ASIGNATURAS_TOMADAS($id_alumno, $id_carrera, $semestre, $year)
{
	require("../../../funciones/conexion_v2.php");
	$cons_TR="SELECT * FROM toma_ramos WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND semestre='$semestre' AND year='$year'";
	$sql_TR=$conexion_mysqli->query($cons_TR)or die($conexion_mysqli->error);
	$num_ramos_tomados=$sql_TR->num_rows;
	if(empty($num_ramos_tomados)){ $num_ramos_tomados=0;}
	if(DEBUG){ echo"$cons_TR<br>Num Ramos Tomados: $num_ramos_tomados<br>";}
	
	$condicion_toma_ramo=" AND cod_asignatura IN(";
	if($num_ramos_tomados>0)
	{
		while($TR=$sql_TR->fetch_assoc())
		{
			$aux_cod_asignatura=$TR["cod_asignatura"];
			$condicion_toma_ramo.=" $aux_cod_asignatura, ";
		}
		
		$condicion_toma_ramo=substr($condicion_toma_ramo,0,(strlen($condicion_toma_ramo)-2));
		$condicion_toma_ramo.=")";
	}
	else
	{ $condicion_toma_ramo="";}
	
	$sql_TR->free();
	$conexion_mysqli->close();
	return($condicion_toma_ramo);
}
?>