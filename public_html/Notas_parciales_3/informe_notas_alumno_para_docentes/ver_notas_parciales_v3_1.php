<?php
 //-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("carga_notas_parciales_v3_1_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCAR_NOTAS_PARCIALES_V3");
?>
<html>
<head>
<title>Notas Parciales V3</title>
<?php $xajax->printJavascript(); ?> 
<?php include("../../../funciones/codificacion.php");?>
 <?php 
 
 	////nivels que se rinden x semestre
 	$ARRAY_NIVELES_X_SEMESTRE[1]=array(1,3,5);
	$ARRAY_NIVELES_X_SEMESTRE[2]=array(2,4);
 
  if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]){$action="graba_toma_ramo.php";}
  else{ $action="";}
  
   $rut_alumno=$_SESSION["SELECTOR_ALUMNO"]["rut"];
   $id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
   $id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
   $carrera=$_SESSION["SELECTOR_ALUMNO"]["carrera"];
   $nombre_alumno=$_SESSION["SELECTOR_ALUMNO"]["nombre"]." ".$_SESSION["SELECTOR_ALUMNO"]["apellido"];
   $nivel_alumno=$_SESSION["SELECTOR_ALUMNO"]["nivel"];
   $year_ingreso=$_SESSION["SELECTOR_ALUMNO"]["ingreso"];
   $jornada_alumno=$_SESSION["SELECTOR_ALUMNO"]["jornada"];
   require("../../../funciones/conexion_v2.php");
   require("../../../funciones/funciones_sistema.php");
   $alumno_actualmente_matriculado=VERIFICAR_MATRICULA($id_alumno, $id_carrera,true);
   $alumno_actualmente_matriculado=true;
   $array_semeste=array(1,2);
   $mes_actual=date("m");
   
   if($mes_actual>=8)///utilizo agosto para inicio 2 semeste
   { $semeste_actual=2;}
   else{ $semeste_actual=1;}


      	$cons_TR="SELECT `semestre`, `year` FROM `toma_ramos` WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' GROUP BY `semestre`, `year` ORDER by `year`, `semestre`";
		
		$sql_TR=$conexion_mysqli->query($cons_TR)or die("GRUPOS".$conexion_mysqli->error);
		$num_periodos=$sql_TR->num_rows;
		
		$msj="";
		if($num_periodos>0)
		{
			while($PTR=$sql_TR->fetch_assoc())
			{
				$periodo_semestre=$PTR["semestre"];
				$periodo_year=$PTR["year"];
				
				$msj.='<a href="#" class="button_R" onClick="xajax_BUSCAR_NOTAS_PARCIALES_V3('.$periodo_semestre.', '.$periodo_year.', '.$id_alumno.', '.$id_carrera.'); return false;">'.$periodo_semestre.'-'.$periodo_year.'</a>&nbsp;';
			}
		}
		else
		{ $msj="Sin Registros...";}
		
		
		$sql_TR->free();
	@mysql_close($conexion);
   $conexion_mysqli->close();
 ?>   

<style type="text/css">
<!--
.Estilo1 {color: #0080C0}
#link {
	text-align: right;
	padding-right: 10px;
}
-->
</style>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/hint.css-master/hint.css">
<style type="text/css">
#div_resultado {
	position:absolute;
	width:90%;
	height:115px;
	z-index:2;
	left: 5%;
	top: 300px;
}
</style>
</head>

<body onLoad="xajax_BUSCAR_NOTAS_PARCIALES_V3(<?php echo $periodo_semestre;?>, <?php echo$periodo_year;?>, <?php echo$id_alumno;?>, <?php echo$id_carrera;?>); return false;">
<h1 id="banner">Administrador - Calificaciones Parciales V3.1</h1>
<div id="link"><br>
  <a href="../../Docentes/buscador_alumno_BETA_para_docentes/HALL/index.php" class="button">
Volver al Menu</a></div>
<div id="Layer1" style="position:absolute; left:5%; top:108px; width:90%; height:165px; z-index:1"> 
 
  <table width="60%" border="0" align="left">
  <thead>
    <tr>
      <th colspan="4">Datos Alumno</th>
    </tr>
    </thead>
    <tbody>
    <tr> 
      <td width="64"><strong>Carrera</strong></td>
      <td colspan="3"><?php echo "$carrera - Jornada: $jornada_alumno"; ?><input name="id_carrera" type="hidden" value="<?php echo $id_carrera;?>"></td>
    </tr>
    <tr> 
      <td width="64"><strong>Alumno</strong></td>
      <td colspan="3"><?php echo $nombre_alumno; ?><input type="hidden" name="id_alumno" id="id_alumno" value="<?php  echo $id_alumno;?>"></td>
    </tr>
    <tr>
      <td>Nivel</td>
      <td width="168"><?php echo $nivel_alumno;?>
        <input name="nivel_alumno" type="hidden" id="nivel_alumno" value="<?php echo $nivel_alumno;?>"></td>
      <td width="191">Ingreso</td>
      <td width="70"><?php echo $year_ingreso;?></td>
    </tr>
    <tr>
      <td>Periodo</td>
      <td colspan="3">
       <?php echo $msj;?>
        </td>
    </tr>
    </tbody>
  </table>
  <p><br>
    
  </p>

    
   
</div>
<div id="div_resultado"></div>
</body>
</html>
