<?php
//-----------------------------------------//
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Resumen_General->alumno_proceso_titulacion_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	$tiempo_inicio_script = microtime(true);
	

//---------------------------------------------//
$year_actual=date("Y");
$mes_actual=date("m");

if($mes_actual>=8)
{ $semestre_actual=2;}
else
{ $semestre_actual=1;}
//---------------------------//
require("../../../funciones/conexion_v2.php");
require("../../../funciones/funciones_sistema.php");

if($_GET)
{$year_consulta=mysqli_real_escape_string($conexion_mysqli, $_GET["year"]);}
else
{$year_consulta=$year_actual;}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Resumen General Alumno con proceso Titulacion</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:50px;
	z-index:1;
	left: 5%;
	top: 234px;
}
</style>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<style type="text/css">
#apDiv2 {
	position:absolute;
	width:40%;
	height:58px;
	z-index:2;
	left: 30%;
	top: 90px;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Resum&eacute;n Alumnos con Proceso TitulacionV.1</h1>
<div id="link">
  <br>
<a href="../../Alumnos/menualumnos.php" class="button">Volver al menu </a>
  </div>
  
  <div id="apDiv2">
  <form action="resumen_alumno_proceso_titulacion_x.php" method="get" id="frm">
    <table width="100%" border="1">
    <thead>
      <tr>
        <th colspan="2">Parametros Busqueda (año titulo en acta)</th>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td width="50%">a&ntilde;o</td>
        <td width="50%"><select name="year" id="year">
          <?php
	  	$años_anteriores=10;
		$años_siguientes=1;
		
		$año_ini=$year_actual-$años_anteriores;
		$año_fin=$year_actual+$años_siguientes;
		
		for($a=$año_ini;$a<=$año_fin;$a++)
		{
			if($a==$year_consulta)
			{echo'<option value="'.$a.'" selected="selected">'.$a.'</option>';	}
			else
			{echo'<option value="'.$a.'">'.$a.'</option>';}	
		}
	  ?>
          </select></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><a href="#" class="button_R" onclick="javascript:document.getElementById('frm').submit();">Consultar</a></td>
      </tr>
      </tbody>
    </table>
    </form>
  </div>
<div id="apDiv1">

<table align="center" border="1" width="80%">
<thead>
<tr>
	<th colspan="5">Resumen Titulados <?php echo"[$year_consulta]";?></th>
</tr>
<tr>
    <td>Cantidad</td>
    <td>Sede</td>
    <td>Carrera</td>
    <td>Sexo</td>
    <td>Jornada</td>
 </tr>
</thead>
<tbody>
<?php
///////////////////////////////////
	
		/////Registro ingreso///
			 include("../../../funciones/VX.php");
			 $evento="Ver  Resumen General Alumnos con proceso titulacion V1";
			 REGISTRA_EVENTO($evento);
	
	$cons_main_1="SELECT COUNT(*) AS `Filas`, proceso_titulacion.sede, alumno.id_carrera, alumno.sexo, alumno.jornada FROM `proceso_titulacion` INNER JOIN alumno ON proceso_titulacion.id_alumno=alumno.id WHERE alumno.situacion='T' AND proceso_titulacion.year_titulo='$year_consulta' GROUP BY proceso_titulacion.sede, alumno.id_carrera, alumno.sexo, alumno.jornada";
		
		$sqli_main_1=$conexion_mysqli->query($cons_main_1)or die("MAIN 1".$conexion_mysqli->error);
		$num_reg_M=$sqli_main_1->num_rows;
		if(DEBUG){ echo"<br><br>$cons_main_1<br>NUM.$num_reg_M<br>";}
		$SUMA_TOTAL=0;
		if($num_reg_M>0)
		{
			
			while($PT=$sqli_main_1->fetch_assoc())
			{
				$PT_filas=$PT["Filas"];
				$PT_sede=$PT["sede"];
				$PT_id_carrera=$PT["id_carrera"];
				$PT_sexo=$PT["sexo"];
				$PT_jornada=$PT["jornada"];
				$aux_nombre_carrera=NOMBRE_CARRERA($PT_id_carrera);
				
				$SUMA_TOTAL+=$PT_filas;
				echo'<tr>
						<td>'.$PT_filas.'</td>
						<td>'.$PT_sede.'</td>
						<td>'.$PT_id_carrera.'_'.$aux_nombre_carrera.'</td>
						<td>'.$PT_sexo.'</td>
						<td>'.$PT_jornada.'</td>
					</tr>';
			
			}//fin while alumnos
		}//fin hay alumnos
		else
		{	
			echo"<tr><td>Sin Registros</td></tr>";
		}
		//fin documento
	$sqli_main_1->free();
	@mysql_close($conexion);
	$conexion_mysqli->close();
/////////////////////////////////////////////
$tiempo_fin_script = microtime(true);
//////////////////////////////////////////////
?>
<tr>
	<td><?php echo $SUMA_TOTAL;?></td>
    <td colspan="4">TOTAL</td>
</tr>
</tbody>
</table>


<div id="tiempo_ejecucion" align="center"><?php echo "<br>Tiempo empleado: " . round($tiempo_fin_script - $tiempo_inicio_script,4)." Segundos"; ?></div>
</div>
</body>
</html>