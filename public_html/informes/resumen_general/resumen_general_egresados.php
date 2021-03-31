<?php
//-----------------------------------------//
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Resumen_General->alumno_egresados_v1");
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

require("../../../funciones/conexion_v2.php");
require("../../../funciones/funciones_sistema.php");

if($_GET)
{$year_consulta=mysqli_real_escape_string($conexion_mysqli, $_GET["year"]);}
else
{$year_consulta=$year_actual;}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Resumen General Egresados</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:50px;
	z-index:1;
	left: 5%;
	top: 234px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:58px;
	z-index:2;
	left: 30%;
	top: 90px;
}
</style>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
</head>

<body>
<h1 id="banner">Administrador - Resum&eacute;n Alumnos Egresados</h1>
<div id="link">
  <br>
<a href="../../Alumnos/menualumnos.php" class="button">Volver al menu </a>
</div>
  
  <div id="apDiv2">
  <form action="resumen_general_egresados.php" method="get" id="frm">
    <table width="100%" border="1">
    <thead>
      <tr>
        <th colspan="2">Parametros Busqueda (año Egreso)</th>
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
<?php
///////////////////////////////////
	
		/////Registro ingreso///
			 include("../../../funciones/VX.php");
			 $evento="Ver  Resumen General Alumnos Egresados V1";
			 REGISTRA_EVENTO($evento);
	
	$cons_main_1="SELECT id_alumno, id_carrera FROM `proceso_egreso` WHERE year_egreso='$year_consulta'";
		
		$sqli_main_1=$conexion_mysqli->query($cons_main_1)or die("MAIN 1".$conexion_mysqli->error);
		$num_reg_M=$sqli_main_1->num_rows;
		if(DEBUG){ echo"<br><br>$cons_main_1<br>NUM.$num_reg_M<br>";}
		$ARRAY_EGRESADOS=array();
		$ARRAY_TITULADOS=array();
		if($num_reg_M>0)
		{
			
			while($N=$sqli_main_1->fetch_assoc())
			{
				$N_id_alumno=$N["id_alumno"];
				$N_id_carrera=$N["id_carrera"];
				if(DEBUG){ echo"id_alumno: $N_id_alumno id_carrera: $N_id_carrera<br>";}
				
				list($alumno_es_egresado, $semestre_egreso, $year_egreso)=ES_EGRESADO_V2($N_id_alumno, $N_id_carrera);
				if($alumno_es_egresado)
				{
					$cons_A="SELECT jornada, sexo, sede FROM alumno WHERE id='$N_id_alumno' AND id_carrera='$N_id_carrera' LIMIT 1";
					$sqli_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
					$A=$sqli_A->fetch_assoc();
						$A_jornada=$A["jornada"];
						$A_sexo=$A["sexo"];
						$A_sede=$A["sede"];
					$sqli_A->free();
					
					
					if($year_egreso==$year_consulta){ $utilizar_alumno=true; if(DEBUG){ echo"Year Egreso: $year_egreso igual a year consulta: $year_consulta OK<br>";}}
					else{ $utilizar_alumno=false; if(DEBUG){ echo"*Year Egreso: $year_egreso diferente de year consulta: $year_consulta ERROR<br>";}}
					
					if($utilizar_alumno)
					{
						if(isset($ARRAY_EGRESADOS[$A_sede][$N_id_carrera][$A_jornada][$A_sexo])){ $ARRAY_EGRESADOS[$A_sede][$N_id_carrera][$A_jornada][$A_sexo]++;}
						else{$ARRAY_EGRESADOS[$A_sede][$N_id_carrera][$A_jornada][$A_sexo]=1;}
						
						list($es_titulado, $semestre_titulo, $year_titulo)=ES_TITULADO($N_id_alumno, $N_id_carrera);
						if($es_titulado){
							if(isset($ARRAY_TITULADOS[$A_sede][$N_id_carrera][$A_jornada][$A_sexo])){ $ARRAY_TITULADOS[$A_sede][$N_id_carrera][$A_jornada][$A_sexo]++;}
							else{$ARRAY_TITULADOS[$A_sede][$N_id_carrera][$A_jornada][$A_sexo]=1;}
						}
					}
				}
				
			
			}//fin while alumnos
		}//fin hay alumnos
		else
		{	
			echo"<tr><td>Sin Registros</td></tr>";
		}
		//fin documento
		if(DEBUG){ var_dump($ARRAY_EGRESADOS);}
	$sqli_main_1->free();
	@mysql_close($conexion);
	$conexion_mysqli->close();
/////////////////////////////////////////////
$tiempo_fin_script = microtime(true);
//////////////////////////////////////////////
?>
<table align="center" border="1" width="80%">
<thead>
<tr>
	<th colspan="7">Resumen Egresados <?php echo"[$year_consulta]";?></th>
</tr>
<tr>
    <td rowspan="2">Sede</td>
    <td rowspan="2">Carrera</td>
    <td rowspan="2">Jornada</td>
    <td colspan="2">Egresados <?php echo"$year_consulta";?></td>
    <td colspan="2">Titulados Actualmente</td>
    </tr>
<tr>
  <td bgcolor="#99CC33">F</td>
  <td>M</td>
  <td bgcolor="#99CC33">F</td>
  <td>M</td>
</tr>
</thead>
<tbody>
<?php
foreach($ARRAY_EGRESADOS as $aux_sede => $array_datos)
{
	foreach($array_datos as $aux_id_carrera => $array_datos_2)
	{
		foreach($array_datos_2 as $aux_jornada => $array_sexo)
		{
			if(isset($array_sexo["F"])){$F=$array_sexo["F"];}
			else{ $F=0;}
			
			if(isset($array_sexo["M"])){$M=$array_sexo["M"];}
			else{ $M=0;}
			
			if(isset($ARRAY_TITULADOS[$aux_sede][$aux_id_carrera][$aux_jornada]["M"])){
				$hombresTitulados=$ARRAY_TITULADOS[$aux_sede][$aux_id_carrera][$aux_jornada]["M"];}
				else{$hombresTitulados=0;}
			if(isset($ARRAY_TITULADOS[$aux_sede][$aux_id_carrera][$aux_jornada]["F"])){
				$mujeresTituladas=$ARRAY_TITULADOS[$aux_sede][$aux_id_carrera][$aux_jornada]["F"];}
				else{$mujeresTituladas=0;}
			
			echo'<tr>
					<td>'.$aux_sede.'</td>
					<td>'.$aux_id_carrera.'_'.NOMBRE_CARRERA($aux_id_carrera).'</td>
					<td>'.$aux_jornada.'</td>
					<td bgcolor="#99CC33">'.$F.'</td>
					<td>'.$M.'</td>
					<td bgcolor="#99CC33">'.$mujeresTituladas.'</td>
					<td>'.$hombresTitulados.'</td>
				</tr>';
			
		}
	}
}
?>
</tbody>
</table>


<div id="tiempo_ejecucion" align="center"><?php echo "<br>Tiempo empleado: " . round($tiempo_fin_script - $tiempo_inicio_script,4)." Segundos"; ?></div>
</div>
</body>
</html>