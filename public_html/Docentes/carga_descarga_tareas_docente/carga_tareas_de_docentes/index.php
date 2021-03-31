<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
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
<?php include("../../../../funciones/codificacion.php");?>
<title>Carga descarga descarga docente</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<!--INICIO LIGHTBOX EVOLUTION-->
  <script type="text/javascript" src="../../../libreria_publica/lightbox_1.6.9_evolution/js/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.css" />
  <!--[if IE 6]>
  <link rel="stylesheet" type="text/css" href="../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.ie6.css" />
  <![endif]-->
  <script type="text/javascript" src="../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/jquery.lightbox.min.js"></script>
 <script type="text/javascript">
    jQuery(document).ready(function($){
      $('.lightbox').lightbox(); 
    });
  </script>
 <!--FIN LIGHTBOX EVOLUTION--> 
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 203px;
}
</style>
</head>

<body>
<h1 id="banner">Carga-Descarga Tareas Docente</h1>
<div id="link"><br />
<a href="../../okdocente.php" class="button">Volver al Menu</a></div>
<div id="apDiv1">
  <table width="100%" border="1" align="center">
  <thead>
    <tr>
      <th colspan="6">Trabajos Disponibles</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>N.</td>
      <td>Nombre</td>
      <td>Descripcion</td>
      <td colspan="2">OPC</td>
    </tr>
    <?php
	
	$id_docente=$_SESSION["USUARIO"]["id"];
    require("../../../../funciones/conexion_v2.php");
	$continuar=false;
	$cons_TR="SELECT `semestre`, `year` FROM `toma_ramo_docente` WHERE id_funcionario='$id_docente' GROUP BY `semestre`, `year` ORDER by `year`, `semestre`";
	if(DEBUG){ echo"---> $cons_TR<br>";}
		$sqli_TR=$conexion_mysqli->query($cons_TR)or die($conexion_mysqli->error);
		$num_periodos=$sqli_TR->num_rows;
		
		if($num_periodos>0)
		{
			while($PTR=$sqli_TR->fetch_assoc())
			{
				$periodo_semestre=$PTR["semestre"];
				$periodo_year=$PTR["year"];
				$continuar=true;
			}
		}
		
	if($continuar)
	{
	$cons="SELECT * FROM tareas_docente WHERE tipo='trabajo' AND semestre='$periodo_semestre' AND year='$periodo_year' ORDER by fecha_generacion";
	if(DEBUG){ echo"---> $cons<br>";}
	$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$num_trabajos=$sqli->num_rows;
	$path="../enrutador_v2.php";

	if($num_trabajos>0)
	{
		$aux=0;
		while($T=$sqli->fetch_assoc())
		{
			
			$encontrado=0;
			$aux++;
			$T_id=$T["id"];
			//////////
			//busco si hay permisos para ver este trabajo
			//primero reviso si tiene permisos para alguien
			$cons_P="SELECT * FROM tareas_docente_permisos WHERE id_trabajo='$T_id'";
			$sqli_P=$conexion_mysqli->query($cons_P)or die($conexion_mysqli->error);
			$num_total_de_permisos_establecidos=$sqli_P->num_rows;
			if($num_total_de_permisos_establecidos>0)
			{
				while($P1=$sqli_P->fetch_assoc())
				{
					$P1_id_usuario=$P1["id_usuario"];
					if($P1_id_usuario==$id_docente){$encontrado++; break;}
				}
				if($encontrado>0){$mostrar=true;}
				else{$mostrar=false;}
			}
			else{ $mostrar=true;}
			$sqli_P->free();	
			///
			
			
			$T_nombre=$T["nombre"];
			$T_descripcion=$T["descripcion"];
			$T_archivo=$T["archivo"];
			$T_fecha_generacion=$T["fecha_generacion"];
			$T_cod_user=$T["cod_user"];
			
			$ruta_archivo=$path."?file=".base64_encode($T_archivo).'&T_id='.base64_encode($T_id);
			
			///busco si tiene tareas cargadas a este trabajo
			$cons2="SELECT id, archivo FROM tareas_docente WHERE tipo='tarea' AND id_trabajo='$T_id' AND id_docente='$id_docente'";
			$sqli2=$conexion_mysqli->query($cons2)or die($conexion_mysqli->error);
			$num_tareas_cargadas=$sqli2->num_rows;	
			$TA=$sqli2->fetch_assoc();
				$TA_id=$TA["id"];
				$TA_archivo=$TA["archivo"];
			$sqli2->free();
			
			if(empty($num_tareas_cargadas)){ $num_tareas_cargadas=0;}
			$url_tareas='carga_tarea_1.php?id_trabajo='.$T_id.'&lightbox[iframe]=true&amp;lightbox[width]=600&amp;lightbox[height]=530"  class="lightbox" title="cargar trabajo"';
			$tarea_msj="Cargar Tarea";
			if($num_tareas_cargadas>0){$url_tareas='../enrutador_v2.php?file='.base64_encode($TA_archivo); $tarea_msj="Tarea Cargada";}
			
			if($mostrar)
			{
				echo'<tr>
					  <td>'.$aux.'</td>
					  <td>'.$T_nombre.'</td>
					  <td>'.$T_descripcion.'</td>
					  <td><a href="'.$ruta_archivo.'" target="_blanck">Descargar</a></td>
					  <td><a href="'.$url_tareas.'">'.$tarea_msj.'</a></td>';
				echo'</tr>';	
			}
		}
	}
	else
	{echo'<tr><td colspan="4">Sin Trabajos Disponibles...</td></tr>';}
	$sqli->free();
	}
	$conexion_mysqli->close();
	
	?>
    </tbody>
  </table>
</div>
</body>
</html>