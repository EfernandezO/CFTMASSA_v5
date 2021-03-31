<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_comprobar_egresados_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//	
	$tiempo_inicio_script = microtime(true);
	//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("cambiar_situacion_a_egresado.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"CAMBIO_SITUACION_ACADEMICA");
////////////////////////////////////////////
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Comprueba Egresados</title>
<?php $xajax->printJavascript(); ?> 
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:98%;
	height:25px;
	z-index:1;
	left: 1%;
	top: 82px;
}
</style>
<style type="text/css" title="currentStyle">
			@import "../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/css/demo_page.css";
			@import "../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/css/demo_table_jui.css";
			@import "../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/examples/examples_support/themes/smoothness/jquery-ui-1.8.4.custom.css";
		</style>
		<script type="text/javascript" language="javascript" src="../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.dataTables.js"></script>
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				oTable = $('#example').dataTable({
					"bJQueryUI": true,
					"sPaginationType": "full_numbers",
					"bPaginate": false,
				});
			} );
		</script>
</head>
<body>
<h1 id="banner"> Informe Comprobar Egresados</h1>
<div id="link"><br />
<a href="javascript:close();" class="button">Cerrar</a></div>
<div id="apDiv1" class="demo_jui">
<?php
if(DEBUG)
{ var_export($_POST);}

$exe=false;
if($_POST)
{
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	set_time_limit(300);
	$nota_aprobacion=4;
	$exe=true;
	$sede=mysqli_real_escape_string($conexion_mysqli, $_POST["sede"]);
	$id_carrera=mysqli_real_escape_string($conexion_mysqli, $_POST["id_carrera"]);
	$year_egreso_consulta=mysqli_real_escape_string($conexion_mysqli, $_POST["year_egreso"]);
}
?>
			Sede: <?php echo $sede;?> Year consulta:<?php echo $year_egreso_consulta;?> id_carrera: <?php echo $id_carrera;?>
          <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" class="display" id="example" summary="Sede: <?php $sede;?> Year consulta:<?php echo $year_egreso_consulta;?> id_carrera: <?php echo $id_carrera;?>">
        <thead>
            <th>N.</th>
            <th>Carrera</th>
            <th>ID</th>
            <th>Rut</th>
            <th>Nombre</th>
            <th>Apellido P</th>
            <th>Apellido M</th>
            <th>Periodo egreso</th>
            <th>Opciones</th>
        </thead>
        <tbody>
<?php
if($exe)
{	
	if($sede=="0"){ $condicion_sede="";}
	else{ $condicion_sede="AND sede='$sede'";}
	
	if($id_carrera=="0"){ $condicion_carrera="";}
	else{ $condicion_carrera=" AND id_carrera='$id_carrera'";}


	$cons="SELECT * FROM alumno WHERE NOT situacion IN('R', 'P', 'T', 'E', 'EG') $condicion_sede $condicion_carrera ORDER by sede, id_carrera, apellido_P, apellido_M";
	$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$num_reg=$sqli->num_rows;
	if(DEBUG){echo"<br>--> <b>$cons </b><br>Num registros: $num_reg<br>";}
	if($num_reg>0)
	{
		$contador=0;
		$contador_global=0;
		while($D=$sqli->fetch_assoc())
		{
			$contador_global++;
			$A_id=$D["id"];
			$A_id_carrera=$D["id_carrera"];
			$A_rut=$D["rut"];
			$A_nombre=$D["nombre"];
			$A_apellido_P=$D["apellido_P"];
			$A_apellido_M=$D["apellido_M"];
			$A_ingreso=$D["ingreso"];
			$A_nivel=$D["nivel"];
			$A_jornada=$D["jornada"];
			$A_grupo=$D["grupo"];
			$A_sede=$D["sede"];
			$A_carrera=$D["carrera"];
			$A_fono=$D["fono"];
			$A_email=$D["email"];
			$A_situacion_actual=$D["situacion"];
			
			if(DEBUG){ echo"<br><b>($contador_global)</b> ID: $A_id - $A_rut - $A_carrera<br>";}	
			list($alumno_es_egresado, $semestre_egreso, $year_egreso)=ES_EGRESADO($A_id, $A_id_carrera);
			
			if($semestre_egreso>2){ if($semestre_egreso%2==0){$semestre_egreso=2;}else{$semestre_egreso=1;}}
			if($alumno_es_egresado){ $mostrar_alumno_1=true;}
			else{ $mostrar_alumno_1=false;}
			
			if($year_egreso_consulta==0){ $mostrar_alumno_2=true;}
			elseif($year_egreso_consulta==$year_egreso){ $mostrar_alumno_2=true;}
			else{ $mostrar_alumno_2=false;}
			
			if($mostrar_alumno_1 and $mostrar_alumno_2)
			{
				$contador++;
				
				switch($A_situacion_actual)
					{
						case"V":
							$boton_cambio='<a href="#" onclick="xajax_CAMBIO_SITUACION_ACADEMICA('.$A_id.', '.$A_id_carrera.', '.$contador.');" class="button_R" title="Cambiar a Egresado">V</a>';
							break;
						default:	
							$boton_cambio="$A_situacion_actual";
					}
				
				$color_carrera=COLOR_CARRERA($A_id_carrera);
				echo'<tr height="30">
						<td>'.$contador.'</td>
						<td bgcolor="'.$color_carrera.'">'.$A_carrera.'</td>
						<td align="center">'.$A_id.'</td>
						<td>'.$A_rut.'</td>
						<td>'.$A_nombre.'</td>
						<td>'.$A_apellido_P.'</td>
						<td>'.$A_apellido_M.'</td>
						<td align="center">'.$semestre_egreso.' - '.$year_egreso.'</td>
						<td align="center"><div id="boton_'.$contador.'" name="boton_'.$contador.'">'.$boton_cambio.'</div></td>
					</tr>';
			}
			
			
		}
	}
	else
	{/*Sin registros*/}
	$sqli->free();
	@mysql_close($conexion);
	$conexion_mysqli->close();
}
else
{ header("location: index.php");}

$tiempo_fin_script = microtime(true);
?>
</tbody>
</table>
<div id="tiempo_ejecucion" align="center"><?php echo "<br>Tiempo empleado: " . round($tiempo_fin_script - $tiempo_inicio_script,4)." Segundos"; ?></div>
</div>
</body>
</html>