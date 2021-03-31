<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Resumen General -> Alumnos MAtriculados nivel 1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	$tiempo_inicio_script = microtime(true);
//-----------------------------------------//	
//var_dump($_POST);
//////////////////////////
require("../../../funciones/conexion_v2.php");
require("../../../funciones/funciones_sistema.php");

$array_semestre=array(1,2);
$tipo_programa="todos";

$id_carrera=0;
$carrera="todas";
$año_ingreso="Todos";
$jornada="T";
$situacion="A";
$grupo="Todos";
$nivel_alumno_realiza_contrato_consulta=1;
$nivel=array(1,2,3,4,5);
$estado_financiero="Todos";
//---------------------------------------------//
//Periodo
$year_actual=date("Y");
$mes_actual=date("m");

if($mes_actual>=8)
{ $semestre_actual=2;}
else
{ $semestre_actual=1;}

$semestre_actual=1;
if($_GET)
{
	if(DEBUG){ echo"Hay Get<br>";}
	$year_consulta=strip_tags(mysqli_real_escape_string($conexion_mysqli, $_GET["year"]));
	$semestre_consulta=$semestre_actual;
}
else
{
	if(DEBUG){ echo"NO Hay Get<br>";}
	$year_consulta=$year_actual;
	$semestre_consulta=$semestre_actual;
}
//--------------------------------------------//
$verificar_contrato=true;
$no_mostrar_retirados=false;
/////////////////////////////

//$condicion.="AND alumno.nivel IN($niveles)";
$fecha_actual=date("Y-m-d");
///////////////////////////
//alumno.grupo='A' AND alumno.situacion='V' AND alumno.nivel=1 AND alumno.sede='Talca'

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Resumen General Alumnos matriculados 1 Nivel</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:50px;
	z-index:1;
	left: 5%;
	top: 240px;
}
</style>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<style type="text/css">
#apDiv2 {	position:absolute;
	width:40%;
	height:58px;
	z-index:2;
	left: 30%;
	top: 90px;
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
</head>

<body>
<h1 id="banner">Administrador - Resum&eacute;n General Alumnos Matriculados nivel 1 Cohorte <?php echo $year_consulta;?> V.3</h1>
<div id="link"><br>
<a href="../../Alumnos/menualumnos.php" class="button">Volver al menu </a>
  </div>
<div id="apDiv1">
<?php
///////////////////////////////////
						
		$msj_sin_reg="No hay resultados en esta Busqueda";
		
	
		/////Registro ingreso///
			 include("../../../funciones/VX.php");
			 $evento="Ver  Resumen General V.1 Alumnos matriculados Nivel 1 year:$year_consulta";
			 REGISTRA_EVENTO($evento);
											
		$aux=0;	 
	
	#$cons_main_1="SELECT DISTINCT (id_alumno), contratos2.id_carrera FROM contratos2 INNER JOIN alumno ON contratos2.id_alumno = alumno.id WHERE contratos2.ano='$year_consulta' AND contratos2.nivel_alumno='1' AND contratos2.condicion<>'inactivo' ORDER by alumno.id_carrera";
	
	$cons_main_1="SELECT DISTINCT(id_alumno), contratos2.id_carrera, contratos2.yearIngresoCarrera FROM contratos2 INNER JOIN alumno ON contratos2.id_alumno = alumno.id WHERE contratos2.yearIngresoCarrera='$year_consulta' AND contratos2.ano='$year_consulta' AND contratos2.nivel_alumno='1' AND contratos2.condicion<>'inactivo'  ORDER by contratos2.id_carrera";	
		$sql_main_1=$conexion_mysqli->query($cons_main_1)or die($cons_main_1.": ".$conexion_mysqli->error);
		$num_reg_M=$sql_main_1->num_rows;
		if(DEBUG){ echo"<br><br>$cons_main_1<br>NUM.$num_reg_M<br>";}
		if($num_reg_M>0)
		{
			$x=0;
			while($DID=$sql_main_1->fetch_row())
			{
				$id_alumno=$DID[0];
				$id_carrera_alumno=$DID[1];
				$yearIngresoCarrera=$DID[2];
				
				if(DEBUG){ echo"<br>[$x] <strong>id_alumno: $id_alumno id_carrera_alumno: $id_carrera_alumno yearIngresoCarrera:$yearIngresoCarrera</strong><br><br>";}
				list($hay_contrato, $array_datos_contrato)=CONDICION_DE_ALUMNO_PERIODO($id_alumno, $id_carrera_alumno,$yearIngresoCarrera,$semestre_consulta,$year_consulta);
				
				if($hay_contrato)	
				{
					$cons_A="SELECT * FROM alumno WHERE id='$id_alumno' AND id_carrera='$id_carrera_alumno' LIMIT 1";
					if(DEBUG){ echo"Busco datos de alumno<br>$cons_A<br><br>";}
					$sqli_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
					if($sqli_A->num_rows>0){
						$DA=$sqli_A->fetch_assoc();
						$A_rut=$DA["rut"];
						$A_nombre=$DA["nombre"];
						$A_apellido_P=$DA["apellido_P"];
						$A_apellido_M=$DA["apellido_M"];
						$A_sexo=$DA["sexo"];
					}
					$sqli_A->free();	
					//------------------------------------//
					$C_nivel_alumno_contrato=$array_datos_contrato["nivel_alumno_contrato"];
					$C_jornada_contrato=$array_datos_contrato["jornada"];
					$C_fecha_generacion=$array_datos_contrato["fecha_generacion"];
					$C_sede_contrato=$array_datos_contrato["sede"];
								
								
					
					if(DEBUG){ echo"id_carrera: $id_carrera_alumno sede contrato: $C_sede_contrato Jornada Contrato: $C_jornada_contrato Nivel alumno contrato: $C_nivel_alumno_contrato<br>";}
					
					if(DEBUG){ echo"Nivel de Alumno segun contrato: $C_nivel_alumno_contrato<br>";}
					//-------------------------------//
					//condicion del alumno en el semestre-año
					
					$condicion_alumno_este_year=ESTADO_ALUMNO_PERIODO($id_alumno, $id_carrera_alumno, $yearIngresoCarrera, $semestre_consulta, $year_consulta);
					if($hay_contrato){$cumple_condicion_para_ser_mostrado=true;}	
					
					if(($cumple_condicion_para_ser_mostrado)and($C_nivel_alumno_contrato>0))
					{
						if(DEBUG){ echo"Guardado en Array...<br>";}
						switch($condicion_alumno_este_year)
						{
							case"V":
								$considerar_alumno=true;
								if(DEBUG){ echo"Alumno vigente sumar<br>";}
								if(!isset($ARRAY[$C_sede_contrato][$C_jornada_contrato][$id_carrera_alumno][$C_nivel_alumno_contrato]["vigentes"]))
								{$ARRAY[$C_sede_contrato][$C_jornada_contrato][$id_carrera_alumno][$C_nivel_alumno_contrato]["vigentes"]=0;}
								$ARRAY[$C_sede_contrato][$C_jornada_contrato][$id_carrera_alumno][$C_nivel_alumno_contrato]["vigentes"]+=1;
								break;
							case"P":
								$considerar_alumno=true;
								if(DEBUG){ echo"Alumno Pendiente sumar<br>";}
								if(!isset($ARRAY[$C_sede_contrato][$C_jornada_contrato][$id_carrera_alumno][$C_nivel_alumno_contrato]["pendientes"]))
								{$ARRAY[$C_sede_contrato][$C_jornada_contrato][$id_carrera_alumno][$C_nivel_alumno_contrato]["pendientes"]=0;}
								$ARRAY[$C_sede_contrato][$C_jornada_contrato][$id_carrera_alumno][$C_nivel_alumno_contrato]["pendientes"]+=1;
								break;	
							case"R":
								$considerar_alumno=true;
								if(DEBUG){ echo"Alumno Retirado sumar<br>";}
								if(!isset($ARRAY[$C_sede_contrato][$C_jornada_contrato][$id_carrera_alumno][$C_nivel_alumno_contrato]["retirados"]))
								{$ARRAY[$C_sede_contrato][$C_jornada_contrato][$id_carrera_alumno][$C_nivel_alumno_contrato]["retirados"]=0;}
								$ARRAY[$C_sede_contrato][$C_jornada_contrato][$id_carrera_alumno][$C_nivel_alumno_contrato]["retirados"]+=1;
								break;		
							default:
								$considerar_alumno=false;
								if(DEBUG){ echo"situacion de alumno no establecida NO sumar<br>";}
						}
							///////////////////////////////////////////////////////////////////////
							
							////////////////////////////////////////////////////////////////////////
					}//fin alumno vigentes
					else
					{
						if(DEBUG){ echo"Alumno No Cumple Condicion... NO Mostrar<br>";}
					}
				}
						
			
			}
		}
		else
		{	
			echo"Sin Registros<br>";
		}
		//fin documento
	$sql_main_1->free();
	$conexion_mysqli->close();
/////////////////////////////////////////////

//////////////////////////////////////////////
?>
<table align="center" border="1" width="80%">
<thead>
<tr>
	<th colspan="5">Resumen Talca Diurno</th>
</tr>
<tr>
    <td>Carrera</td>
    <td>Total Matriculas</td>
    <td>Vigentes</td>
    <td>Pendientes</td>
    <td>Retirados</td>
 </tr>
</thead>
<tbody>
<?php
if(DEBUG){ var_dump($ARRAY["Talca"]["D"]);}
$SUMA_TOTAL_MOROSIDAD=0;
$SUMA_VIGENTES=0;
$SUMA_RETIRADOS=0;
if(isset($ARRAY["Talca"]["D"])){
foreach($ARRAY["Talca"]["D"] as $aux_id_carrera => $array_promocion)
{
	for($i=1;$i<=5;$i++)
	{
		if(isset($array_promocion[$i]))
		{
			$aux_array=$array_promocion[$i];
			
			if(isset($aux_array["total_morosidad"]))
			{ $aux_total_morosidad=$aux_array["total_morosidad"];}
			else{ $aux_total_morosidad=0;}
			
			if(isset($aux_array["morosos"]))
			{$aux_morosos=$aux_array["morosos"];}
			else{ $aux_morosos=0;}
			
			if(isset($aux_array["pendientes"]))
			{$aux_pendientes=$aux_array["pendientes"];}
			else{$aux_pendientes=0;}
			
			if(isset($aux_array["vigentes"]))
			{$aux_vigentes=$aux_array["vigentes"];}
			else{$aux_vigentes=0;}
			
			if(isset($aux_array["al_dia"]))
			{$aux_al_dia=$aux_array["al_dia"];}
			else{$aux_al_dia=0;}
			
			if(isset($aux_array["retirados"]))
			{$aux_retirados=$aux_array["retirados"];}
			else{$aux_retirados=0;}
			//---------------------------........--------------------------------//
			$aux_total_matricula=($aux_vigentes+$aux_pendientes+$aux_retirados);
			$SUMA_TOTAL_MOROSIDAD+=$aux_total_morosidad;
			$SUMA_VIGENTES+=$aux_vigentes;
			$SUMA_RETIRADOS+=$aux_retirados;
			//---------------------------.........--------------------------------//
			
			$aux_programa=NOMBRE_CARRERA($aux_id_carrera);
			
			echo'<tr>
					<td  bgcolor="'.COLOR_CARRERA($aux_id_carrera).'">>'.$aux_programa.'</td>
					<td align="right"><a href="ver_alumnos_nivel_1.php?sede='.base64_encode("Talca").'&jornada='.base64_encode("D").'&id_carrera='.base64_encode($aux_id_carrera).'&year_consulta='.base64_encode($year_consulta).'&lightbox[iframe]=true&lightbox[width]=800&lightbox[height]=500" class="lightbox" title="Click para detalle">'.$aux_total_matricula.'</a></td>
					<td align="right">'.$aux_vigentes.'</td>
					<td align="right">'.$aux_pendientes.'</td>
					<td align="right">'.$aux_retirados.'</td>
				 </tr>';
		}
	}
}
}
?>
<tr>
	<td colspan="2"><strong>Total</strong></td>
    <td align="right"><strong><?php echo $SUMA_VIGENTES;?></strong></td>
    <td>&nbsp;</td>
    <td align="right"><strong><?php echo $SUMA_RETIRADOS;?></strong></td>
</tr>
</tbody>
</table>
<br />
<br />
<br />
<br />
<br />
<table align="center" border="1" width="80%">
<thead>
<tr>
	<th colspan="5">Resumen Talca Vespertino</th>
</tr>
<tr>
    <td>Carrera</td>
    <td>Total Matriculas</td>
    <td>Vigentes</td>
    <td>Pendientes</td>
    <td>Retirados</td>
 </tr>
</thead>
<tbody>
<?php
if(DEBUG){ var_dump($ARRAY["Talca"]["V"]);}
$SUMA_TOTAL_MOROSIDAD=0;
$SUMA_VIGENTES=0;
$SUMA_RETIRADOS=0;

if(isset($ARRAY["Talca"]["V"])){
foreach($ARRAY["Talca"]["V"] as $aux_id_carrera => $array_promocion)
{
	for($i=1;$i<=5;$i++)
	{
		if(isset($array_promocion[$i]))
		{
			$aux_array=$array_promocion[$i];
			
			if(isset($aux_array["total_morosidad"]))
			{ $aux_total_morosidad=$aux_array["total_morosidad"];}
			else{ $aux_total_morosidad=0;}
			
			if(isset($aux_array["morosos"]))
			{$aux_morosos=$aux_array["morosos"];}
			else{ $aux_morosos=0;}
			
			if(isset($aux_array["pendientes"]))
			{$aux_pendientes=$aux_array["pendientes"];}
			else{$aux_pendientes=0;}
			
			if(isset($aux_array["vigentes"]))
			{$aux_vigentes=$aux_array["vigentes"];}
			else{$aux_vigentes=0;}
			
			if(isset($aux_array["al_dia"]))
			{$aux_al_dia=$aux_array["al_dia"];}
			else{$aux_al_dia=0;}
			
			if(isset($aux_array["retirados"]))
			{$aux_retirados=$aux_array["retirados"];}
			else{$aux_retirados=0;}
			//-----------------------------------------------------------//
			$aux_total_matricula=($aux_vigentes+$aux_pendientes+$aux_retirados);
			$SUMA_TOTAL_MOROSIDAD+=$aux_total_morosidad;
			$SUMA_VIGENTES+=$aux_vigentes;
			$SUMA_RETIRADOS+=$aux_retirados;
			//-----------------------------------------------------------//
			
			$aux_programa=NOMBRE_CARRERA($aux_id_carrera);
			
			echo'<tr>
					<td  bgcolor="'.COLOR_CARRERA($aux_id_carrera).'">>'.$aux_programa.'</td>
					<td align="right"><a href="ver_alumnos_nivel_1.php?sede='.base64_encode("Talca").'&jornada='.base64_encode("V").'&id_carrera='.base64_encode($aux_id_carrera).'&year_consulta='.base64_encode($year_consulta).'&lightbox[iframe]=true&lightbox[width]=800&lightbox[height]=500" class="lightbox" title="Click para detalle">'.$aux_total_matricula.'</a></td>
					<td align="right">'.$aux_vigentes.'</td>
					<td align="right">'.$aux_pendientes.'</td>
					<td align="right">'.$aux_retirados.'</td>
				 </tr>';
		}
	}
}
}
?>
<tr>
	<td colspan="2"><strong>Total</strong></td>
    <td align="right"><strong><?php echo $SUMA_VIGENTES;?></strong></td>
    <td>&nbsp;</td>
    <td align="right"><strong><?php echo $SUMA_RETIRADOS;?></strong></td>
</tr>
</tbody>
</table>
<br />
<br />
<br />
<br />
<br />
<table align="center" border="1" width="80%">
<thead>
<tr>
	<th colspan="5">Resumen Linares Diurno</th>
</tr>
<tr>
    <td>Carrera</td>
    <td>Total Matriculas</td>
    <td>Vigentes</td>
    <td>Pendientes</td>
    <td>Retirados</td>
 </tr>
</thead>
<tbody>
<?php
if(DEBUG){ var_dump($ARRAY["Linares"]["D"]);}
$SUMA_TOTAL_MOROSIDAD=0;
$SUMA_VIGENTES=0;
$SUMA_RETIRADOS=0;
if(isset($ARRAY["Linares"]["D"])){
foreach($ARRAY["Linares"]["D"] as $aux_id_carrera => $array_promocion)
{
	for($i=1;$i<=5;$i++)
	{
		if(isset($array_promocion[$i]))
		{
			$aux_array=$array_promocion[$i];
			
			if(isset($aux_array["total_morosidad"]))
			{ $aux_total_morosidad=$aux_array["total_morosidad"];}
			else{ $aux_total_morosidad=0;}
			
			if(isset($aux_array["morosos"]))
			{$aux_morosos=$aux_array["morosos"];}
			else{ $aux_morosos=0;}
			
			if(isset($aux_array["pendientes"]))
			{$aux_pendientes=$aux_array["pendientes"];}
			else{$aux_pendientes=0;}
			
			if(isset($aux_array["vigentes"]))
			{$aux_vigentes=$aux_array["vigentes"];}
			else{$aux_vigentes=0;}
			
			if(isset($aux_array["al_dia"]))
			{$aux_al_dia=$aux_array["al_dia"];}
			else{$aux_al_dia=0;}
			
			if(isset($aux_array["retirados"]))
			{$aux_retirados=$aux_array["retirados"];}
			else{$aux_retirados=0;}
			//-----------------------------------------------------------//
			$aux_total_matricula=($aux_vigentes+$aux_pendientes+$aux_retirados);
			$SUMA_TOTAL_MOROSIDAD+=$aux_total_morosidad;
			$SUMA_VIGENTES+=$aux_vigentes;
			$SUMA_RETIRADOS+=$aux_retirados;
			//-----------------------------------------------------------//
			$aux_programa=NOMBRE_CARRERA($aux_id_carrera);
			echo'<tr>
					<td  bgcolor="'.COLOR_CARRERA($aux_id_carrera).'">>'.$aux_programa.'</td>
					<td align="right"><a href="ver_alumnos_nivel_1.php?sede='.base64_encode("Linares").'&jornada='.base64_encode("D").'&id_carrera='.base64_encode($aux_id_carrera).'&year_consulta='.base64_encode($year_consulta).'&lightbox[iframe]=true&lightbox[width]=800&lightbox[height]=500" class="lightbox" title="Click para detalle">'.$aux_total_matricula.'</a></td>
					<td align="right">'.$aux_vigentes.'</td>
					<td align="right">'.$aux_pendientes.'</td>
					<td align="right">'.$aux_retirados.'</td>
				 </tr>';
		}
	}
}
}
?>
<tr>
	<td colspan="2"><strong>Total</strong></td>
    <td align="right"><strong><?php echo $SUMA_VIGENTES;?></strong></td>
    <td>&nbsp;</td>
    <td align="right"><strong><?php echo $SUMA_RETIRADOS;?></strong></td>
</tr>
</tbody>
</table>
<br />
<br />
<br />
<br />
<br />
<table align="center" border="1" width="80%">
<thead>
<tr>
	<th colspan="5">Resumen Linares Vespertino</th>
</tr>
<tr>
    <td>Carrera</td>
    <td>Total Matriculas</td>
    <td>Vigentes</td>
    <td>Pendientes</td>
    <td>Retirados</td>
 </tr>
</thead>
<tbody>
<?php
if(DEBUG){ var_dump($ARRAY["Linares"]["V"]);}
$SUMA_TOTAL_MOROSIDAD=0;
$SUMA_VIGENTES=0;
$SUMA_RETIRADOS=0;
if(isset($ARRAY["Linares"]["V"])){
foreach($ARRAY["Linares"]["V"] as $aux_id_carrera => $array_promocion)
{
	for($i=1;$i<=5;$i++)
	{
		if(isset($array_promocion[$i]))
		{
			$aux_array=$array_promocion[$i];
			
			if(isset($aux_array["total_morosidad"]))
			{ $aux_total_morosidad=$aux_array["total_morosidad"];}
			else{ $aux_total_morosidad=0;}
			
			if(isset($aux_array["morosos"]))
			{$aux_morosos=$aux_array["morosos"];}
			else{ $aux_morosos=0;}
			
			if(isset($aux_array["pendientes"]))
			{$aux_pendientes=$aux_array["pendientes"];}
			else{$aux_pendientes=0;}
			
			if(isset($aux_array["vigentes"]))
			{$aux_vigentes=$aux_array["vigentes"];}
			else{$aux_vigentes=0;}
			
			if(isset($aux_array["al_dia"]))
			{$aux_al_dia=$aux_array["al_dia"];}
			else{$aux_al_dia=0;}
			
			if(isset($aux_array["retirados"]))
			{$aux_retirados=$aux_array["retirados"];}
			else{$aux_retirados=0;}
			//-----------------------------------------------------------//
			$aux_total_matricula=($aux_vigentes+$aux_pendientes+$aux_retirados);
			$SUMA_TOTAL_MOROSIDAD+=$aux_total_morosidad;
			$SUMA_VIGENTES+=$aux_vigentes;
			$SUMA_RETIRADOS+=$aux_retirados;
			//-----------------------------------------------------------//
			$aux_programa=NOMBRE_CARRERA($aux_id_carrera);
			echo'<tr>
					<td bgcolor="'.COLOR_CARRERA($aux_id_carrera).'">'.$aux_programa.'</td>
					<td align="right"><a href="ver_alumnos_nivel_1.php?sede='.base64_encode("Linares").'&jornada='.base64_encode("V").'&id_carrera='.base64_encode($aux_id_carrera).'&year_consulta='.base64_encode($year_consulta).'&lightbox[iframe]=true&lightbox[width]=800&lightbox[height]=500" class="lightbox" title="Click para detalle">'.$aux_total_matricula.'</a></td>
					<td align="right">'.$aux_vigentes.'</td>
					<td align="right">'.$aux_pendientes.'</td>
					<td align="right">'.$aux_retirados.'</td>
				 </tr>';
		}
	}
}
}
$tiempo_fin_script = microtime(true);
?>
<tr>
	<td colspan="2"><strong>Total</strong></td>
    <td align="right"><strong><?php echo $SUMA_VIGENTES;?></strong></td>
    <td>&nbsp;</td>
    <td align="right"><strong><?php echo $SUMA_RETIRADOS;?></strong></td>
</tr>
</tbody>
</table>
<div id="tiempo_ejecucion" align="center"><?php echo "<br>Tiempo empleado: " . round($tiempo_fin_script - $tiempo_inicio_script,4)." Segundos"; ?></div>
</div>
<div id="apDiv2">
  <form action="resumen_general_alumnos_matriculados_nivel_1.php" method="get" id="frm">
    <table width="100%" border="1">
      <thead>
        <tr>
          <th colspan="2">Parametros Busqueda</th>
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
</body>
</html>