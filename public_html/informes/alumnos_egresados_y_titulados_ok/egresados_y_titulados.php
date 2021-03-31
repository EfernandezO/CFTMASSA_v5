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
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	include("../../../funciones/VX.php");
	
if($_POST)	
{
	$sede=mysqli_real_escape_string($conexion_mysqli, $_POST["sede"]);
	$id_carrera=mysqli_real_escape_string($conexion_mysqli, $_POST["id_carrera"]);
	$year_egreso_consulta=mysqli_real_escape_string($conexion_mysqli, $_POST["year_egreso"]);
	$year_ingreso_consulta=mysqli_real_escape_string($conexion_mysqli, $_POST["year_ingreso"]);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Egresados/Titulados</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
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
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:98%;
	height:25px;
	z-index:1;
	left: 1%;
	top: 180px;
}
</style>
<style type="text/css" title="currentStyle">
			@import "../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/css/demo_page.css";
			@import "../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/css/demo_table_jui.css";
			@import "../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/examples/examples_support/themes/smoothness/jquery-ui-1.8.4.custom.css";
		#apDiv2 {
	position:absolute;
	width:40%;
	height:30px;
	z-index:2;
	left: 30%;
	top: 114px;
	text-align: center;
}
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
<h1 id="banner"> Informe Egresados/Titulados</h1>
<div id="link"><br />
<a href="javascript:close();" class="button">Cerrar</a><br /><br />
<a href="egresados_y_titulados_xls.php?sede=<?php echo base64_encode($sede);?>&id_carrera=<?php echo base64_encode($id_carrera);?>&year_egreso_consulta=<?php echo base64_encode($year_egreso_consulta); ?>&situacion=<?php echo base64_encode($situacion);?>&year_ingreso=<?php echo base64_encode($year_ingreso_consulta);?>" class="button" target="_blank">.XLS</a>
</div><br />

<div id="apDiv1" class="demo_jui">
  <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" class="display" id="example">
<thead>
	<th>N.</th>
    <th>Sede</th>
    <th>Carrera</th>
    <th>Jornada</th>
    <th>Rut</th>
    <th>Nombre</th>
    <th>Apellido P</th>
    <th>Apellido M</th>
    <th>Fono</th>
    <th>Email</th>
    <th>Year Ingreso</th>
    <th>Year egreso</th>
    <th>Year Titulo</th>
    <th>Situacion</th>
</thead>
<tbody>
<?php
$ARRAY_RESUMEN=array();
if(DEBUG)
{ var_export($_POST);}
if($_POST)
{
	
	require("../../../funciones/class_ALUMNO.php");
			
	//--------------------------------------//
	$evento="Revisa Informe Alumnos Egresados/Titulados sede: $sede id_carrera: $id_carrera year_egreso_consulta: $year_egreso_consulta situacion: $situacion year_ingreso: $year_ingreso_consulta";
	REGISTRA_EVENTO($evento);
	//-----------------------------------//
	
	if($year_ingreso_consulta=="0"){ $condicion_year_ingreso="";}
	else{ $condicion_year_ingreso="AND yearIngresoCarrera ='$year_ingreso_consulta'";}
	
	if($sede=="0"){ $condicion_sede="";}
	else{ $condicion_sede="AND sede='$sede'";}
	
	if($id_carrera=="0"){ $condicion_carrera="";}
	else{ $condicion_carrera=" AND id_carrera='$id_carrera'";}
	
	if($year_egreso_consulta=="0"){$condicionYearEgreso="";}
	else{ $condicionYearEgreso="AND year_egreso='$year_egreso_consulta'";}


	$cons="SELECT * FROM proceso_egreso WHERE 1=1 $condicion_year_ingreso $condicion_sede $condicion_carrera $condicionYearEgreso";
	if(DEBUG){echo"<br>--> <b>$cons </b><br>";}
	$sqli=$conexion_mysqli->query($cons)or die("Alumnos ".$conexion_mysqli->error);
	$num_reg=$sqli->num_rows;
	if(DEBUG){echo"<br>Num registros: $num_reg<br>";}
	if($num_reg>0)
	{
		$c=0;
		while($D=$sqli->fetch_assoc())
		{
			
			$PE_idAlumno=$D["id_alumno"];
			$PE_id_carrera=$D["id_carrera"];
			$PE_yearIngresoCarrera=$D["yearIngresoCarrera"];
			$PE_sede=$D["sede"];
			$PE_semestre_egreso=$D["semestre_egreso"];
			$PE_year_egreso=$D["year_egreso"];
			
			$ALUMNO=new ALUMNO($PE_idAlumno);
			
			
			$A_jornada=$ALUMNO->getJornadaActual();
			$A_sexo=$ALUMNO->getSexo();
			$A_situacion_actual=$ALUMNO->getUltimaSituacionMat();
			
			$A_matriculas=$ALUMNO->getMatriculasAlumno();
			foreach($A_matriculas as $n => $auxArray){
				$Xid_carrera=$auxArray["id_carrera"];
				$XyearIngresoCarrera=$auxArray["yearIngresoCarrera"];
				
				if(($PE_id_carrera==$Xid_carrera)and($PE_yearIngresoCarrera==$XyearIngresoCarrera)){
					$A_situacion=$Xsituacion=$auxArray["situacion"]; break;}
			}
			
			//
			$consT="SELECT year_titulo FROM proceso_titulacion WHERE id_alumno='$PE_idAlumno' AND id_carrera='$PE_id_carrera' AND yearIngresoCarrera='$PE_yearIngresoCarrera' ORDER by id DESC LIMIT 1";
			$sqliT=$conexion_mysqli->query($consT)or die($conexion_mysqli->error);
			$DT=$sqliT->fetch_assoc();
				$PT_year_titulo=$DT["year_titulo"];
			$sqliT->free();	
			
			
			
			
			$A_carrera=NOMBRE_CARRERA($PE_id_carrera);
			
			
			if($A_situacion=="T"){$mostrar_alumno=true;}
			else{$mostrar_alumno=false;}
			
			if($mostrar_alumno)
			{
				
				$c++;
				if(isset($ARRAY_RESUMEN[$PE_yearIngresoCarrera][$PE_sede][$PE_id_carrera][$A_jornada][$PE_year_egreso][$A_situacion_actual][$A_sexo])){$ARRAY_RESUMEN[$PE_yearIngresoCarrera][$PE_sede][$PE_id_carrera][$A_jornada][$PE_year_egreso][$A_situacion_actual][$A_sexo]+=1;}
				else{$ARRAY_RESUMEN[$PE_yearIngresoCarrera][$PE_sede][$PE_id_carrera][$A_jornada][$PE_year_egreso][$A_situacion_actual][$A_sexo]=1;}
				
			
				if(isset($array_condicion[$A_situacion_actual])){ $array_condicion[$A_situacion_actual]+=1;}
				else{ $array_condicion[$A_situacion_actual]=1;}
				
				$color_carrera=COLOR_CARRERA($PE_id_carrera);
				
				$validador=md5("GDXT".date("d-m-Y"));
				$urlDestino="../../buscador_alumno_BETA/enrutador.php?validador=$validador&id_alumno=$PE_idAlumno";
				echo'<tr height="30">
						<td>'.$c.'</td>
						<td>'.$PE_sede.'</td>
						<td bgcolor="'.$color_carrera.'">'.$A_carrera.'</td>
						<td>'.$A_jornada.'</td>
						<td><a href="'.$urlDestino.'" target="_blank">'.$ALUMNO->getRut().'</a></td>
						<td>'.$ALUMNO->getNombre().'</td>
						<td>'.$ALUMNO->getApellido_P().'</td>
						<td>'.$ALUMNO->getApellido_M().'</td>
						<td>'.$ALUMNO->getFono().' '.$ALUMNO->getFonoApoderado().'</td>
						<td>'.$ALUMNO->getEmail().' '. $ALUMNO->getEmailInstitucional().'</td>
						<td align="center">'.$PE_yearIngresoCarrera.'</td>
						<td align="center">'.$PE_year_egreso.'</td>
						<td align="center">'.$PT_year_titulo.'</td>
						<td align="center">'.$A_situacion.' </td>
					</tr>';
			}
			
			
		}
		
		
	}
	else
	{/*Sin registros*/}
	$sqli->free();
	$conexion_mysqli->close();
}
else
{ header("location: index.php");}

$tiempo_fin_script = microtime(true);
?>
</tbody>
</table><br />

<?php
echo'<table border="1">
<tr>
	<td colspan="8">RESUMEN</td>
</tr>
<tr>
<td>Year Ingreso</td>
<td>Sede</td>
<td>Carrera</td>
<td>jornada</td>
<td>Year Egreso</td>
<td>Situacion</td>
<td>Sexo</td>
<td>Cantidad</td>
</tr>';
foreach($ARRAY_RESUMEN as $ingresoX => $array1){
	foreach($array1 as $sedeX => $array2){
		foreach($array2 as $id_carreraX => $array3){
			foreach($array3 as $jornadaX => $array4){
				foreach($array4 as $yearEgresoX => $array5){
					foreach($array5 as $situacionX => $array6){
						foreach($array6 as $sexoX => $valorX){
							
							echo'<tr>
									<td>'.$ingresoX.'</td>
									<td>'.$sedeX.'</td>
									<td>'.$id_carreraX.'</td>
									<td>'.$jornadaX.'</td>
									<td>'.$yearEgresoX.'</td>
									<td>'.$situacionX.'</td>
									<td>'.$sexoX.'</td>
									<td>'.$valorX.'</td>
								</tr>';
						}
					}
				}
			}
		}
	}
}
echo'</table>';
?>
<div id="tiempo_ejecucion" align="center"><?php echo "<br>Tiempo empleado: " . round($tiempo_fin_script - $tiempo_inicio_script,4)." Segundos"; ?></div>
</div>
<div id="apDiv2">Filtros de Busqueda<br />
<?php echo "Sede: $sede id_carrera: $id_carrera Año ingreso: $year_ingreso_consulta Año Egreso: $year_egreso_consulta ";?></div>
</body>
</html>