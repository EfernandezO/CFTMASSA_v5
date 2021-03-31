<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_Resumen_general_cantidad_alumnos");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	$tiempo_inicio_script = microtime(true);
	
$array_semestre=array(1,2);
	
//-----------------------------------------//	
//var_dump($_POST);
//////////////////////////

$tipo_programa="todos";

$id_carrera=0;
$carrera="todas";
$año_ingreso="Todos";
$jornada="T";
$situacion="A";
$grupo="Todos";

$nivel=array(1,2,3,4,5);
$estado_financiero="Todos";
//---------------------------------------------//
$year_actual=date("Y");
$mes_actual=date("m");

if($mes_actual>=8)
{ $semestre_actual=2;}
else
{ $semestre_actual=1;}
//---------------------------//
require("../../../funciones/conexion_v2.php");

if($_GET)
{
	$year_consulta=mysqli_real_escape_string($conexion_mysqli, $_GET["year"]);
	$semestre_consulta=mysqli_real_escape_string($conexion_mysqli, $_GET["semestre"]);;
}
else
{
	$year_consulta=$year_actual;
	$semestre_consulta=$semestre_actual;
}
//--------------------------------------------//


$verificar_contrato=true;
$no_mostrar_retirados=false;
/////////////////////////////

$condicion=" contratos2.condicion<>'inactivo'";

if($id_carrera>0)
{ $condicion.=" AND alumno.id_carrera='$id_carrera'";}

if($año_ingreso!="Todos")
{$condicion.=" AND alumno.ingreso='$año_ingreso'";}

if($jornada!="T")
{$condicion.=" AND contratos2.jornada='$jornada'";}

if($situacion!="A")
{$condicion.="";}

if($grupo!="Todos")
{$condicion.=" AND alumno.grupo='$grupo'";}

$inicio_ciclio=true;
$niveles="";
if(is_array($nivel))
{
	foreach($nivel as $nn=>$valornn)
	{
		if($inicio_ciclio)
		{ 
			$niveles.="'$valornn'";
			$inicio_ciclio=false;
		}
		else
		{ $niveles.=", '$valornn'";}
		//echo"--> $niveles<br>";
	}
}
else{ $niveles="'sin nivel'";}

$condicion.="AND alumno.nivel IN($niveles)";
$fecha_actual=date("Y-m-d");
///////////////////////////
//alumno.grupo='A' AND alumno.situacion='V' AND alumno.nivel=1 AND alumno.sede='Talca'

$privilegioUser=$_SESSION["USUARIO"]["privilegio"];

switch($privilegioUser){
	case"inspeccion":
		$url="../../Administrador/menu_inspeccion/index.php";
		break;
	default:
		$url="../../Alumnos/menualumnos.php";	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Resumen General</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:50px;
	z-index:1;
	left: 5%;
	top: 520px;
}

#apDiv2 {
	position:absolute;
	width:40%;
	height:58px;
	z-index:2;
	left: 30%;
	top: 90px;
}

#resumen{
	position:absolute;
	width:90%;
	height:58px;
	z-index:2;
	left: 5%;
	top: 238px;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Resum&eacute;n General V.1</h1>
<div id="link">
  <br>
<a href="<?php echo $url;?>" class="button">Volver al menu </a>
  </div>
  
  <div id="apDiv2">
  <form action="resumen_general_x.php" method="get" id="frm">
    <table width="100%" border="1">
    <thead>
      <tr>
        <th colspan="2">Parametros Busqueda</th>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td width="50%">Semestre</td>
        <td width="50%"><select name="semestre" id="semestre">
    <?php
            foreach($array_semestre as $n=>$valor)
			{
				if($valor==$semestre_consulta)
				{ $seleccion='selected="selected"';}
				else
				{ $seleccion="";}
				echo'<option value="'.$valor.'" '.$seleccion.'>'.$valor.'</option>';
			}
			?>
  </select></td>
      </tr>
      <tr>
        <td>a&ntilde;o</td>
        <td><select name="year" id="year">
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
			 $evento="Ver  Resumen General V.2 periodo [$semestre_consulta - $year_consulta]";
			 REGISTRA_EVENTO($evento);
			 
			require("../../../funciones/funciones_sistema.php");				
											
		$aux=0;	 
	
	$cons_main_1="SELECT DISTINCT (id_alumno), contratos2.id_carrera, contratos2.yearIngresoCarrera FROM (contratos2 INNER JOIN alumno ON contratos2.id_alumno = alumno.id) INNER JOIN carrera ON alumno.id_carrera = carrera.id WHERE $condicion AND contratos2.ano='$year_consulta' ORDER by alumno.id_carrera";
	
	
		$sqli_main_1=$conexion_mysqli->query($cons_main_1)or die("MAIN 1".$conexion_mysqli->error);
		$num_reg_M=$sqli_main_1->num_rows;
		if(DEBUG){ echo"<br><br>$cons_main_1<br>NUM.$num_reg_M<br>";}
		if($num_reg_M>0)
		{
			$x=0;
			while($DID=$sqli_main_1->fetch_row())
			{
				$x++;
				$cumple_condicion_para_ser_mostrado=false;
				$id_alumno=$DID[0];		
				$id_carrera_alumno=$DID[1];
				$yearIngresoCarrera=$DID[2];
				
				if(DEBUG){ echo"<br>[$x] <strong>id_alumno: $id_alumno id_carrera_alumno: $id_carrera_alumno</strong><br><br>";}
				list($hay_contrato, $array_datos_contrato)=CONDICION_DE_ALUMNO_PERIODO($id_alumno, $id_carrera_alumno,$yearIngresoCarrera, $semestre_consulta,$year_consulta);
				//-------------------------------------------------------------------------------------------------------------------------------------//
				if($hay_contrato)	
				{
					$cons_A="SELECT * FROM alumno WHERE id='$id_alumno' LIMIT 1";
					if(DEBUG){ echo"Busco datos de alumno<br>$cons_A<br><br>";}
					$sqli_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
					$DA=$sqli_A->fetch_assoc();
						$A_rut=$DA["rut"];
						$A_nombre=$DA["nombre"];
						$A_apellido_P=$DA["apellido_P"];
						$A_apellido_M=$DA["apellido_M"];
						$A_year_ingreso=$DA["ingreso"];
						$A_sexo=$DA["sexo"];
						
						
						
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
					
					$condicion_alumno_este_year=ESTADO_ALUMNO_PERIODO($id_alumno, $id_carrera_alumno,$yearIngresoCarrera, $semestre_consulta, $year_consulta);
					if($hay_contrato){$cumple_condicion_para_ser_mostrado=true;}	
					
					if(($cumple_condicion_para_ser_mostrado)and($C_nivel_alumno_contrato>0))
					{
						if(DEBUG){ echo"Guardado en Array...<br>";}
						switch($condicion_alumno_este_year)
						{
							case"EG":
								if(DEBUG){ echo"Alumno Egresado sumar<br>";}
								if(!isset($ARRAY[$C_sede_contrato][$C_jornada_contrato][$id_carrera_alumno][$C_nivel_alumno_contrato]["egresados"]))
								{$ARRAY[$C_sede_contrato][$C_jornada_contrato][$id_carrera_alumno][$C_nivel_alumno_contrato]["egresados"]=0;}
								$ARRAY[$C_sede_contrato][$C_jornada_contrato][$id_carrera_alumno][$C_nivel_alumno_contrato]["egresados"]+=1;
								break;
							case"T":
								if(DEBUG){ echo"Alumno vigente sumar<br>";}
								if(!isset($ARRAY[$C_sede_contrato][$C_jornada_contrato][$id_carrera_alumno][$C_nivel_alumno_contrato]["titulados"]))
								{$ARRAY[$C_sede_contrato][$C_jornada_contrato][$id_carrera_alumno][$C_nivel_alumno_contrato]["titulados"]=0;}
								$ARRAY[$C_sede_contrato][$C_jornada_contrato][$id_carrera_alumno][$C_nivel_alumno_contrato]["titulados"]+=1;
								break;	
							case"V":
								if(DEBUG){ echo"Alumno vigente sumar<br>";}
								if(!isset($ARRAY[$C_sede_contrato][$C_jornada_contrato][$id_carrera_alumno][$C_nivel_alumno_contrato]["vigentes"]))
								{$ARRAY[$C_sede_contrato][$C_jornada_contrato][$id_carrera_alumno][$C_nivel_alumno_contrato]["vigentes"]=0;}
								$ARRAY[$C_sede_contrato][$C_jornada_contrato][$id_carrera_alumno][$C_nivel_alumno_contrato]["vigentes"]+=1;
								break;
							case"P":
								if(DEBUG){ echo"Alumno Pendiente sumar<br>";}
								if(!isset($ARRAY[$C_sede_contrato][$C_jornada_contrato][$id_carrera_alumno][$C_nivel_alumno_contrato]["pendientes"]))
								{$ARRAY[$C_sede_contrato][$C_jornada_contrato][$id_carrera_alumno][$C_nivel_alumno_contrato]["pendientes"]=0;}
								$ARRAY[$C_sede_contrato][$C_jornada_contrato][$id_carrera_alumno][$C_nivel_alumno_contrato]["pendientes"]+=1;
								break;	
							case"R":
								if(DEBUG){ echo"Alumno Retirado sumar<br>";}
								if(!isset($ARRAY[$C_sede_contrato][$C_jornada_contrato][$id_carrera_alumno][$C_nivel_alumno_contrato]["retirados"]))
								{$ARRAY[$C_sede_contrato][$C_jornada_contrato][$id_carrera_alumno][$C_nivel_alumno_contrato]["retirados"]=0;}
								$ARRAY[$C_sede_contrato][$C_jornada_contrato][$id_carrera_alumno][$C_nivel_alumno_contrato]["retirados"]+=1;
								break;		
							default:
								if(DEBUG){ echo"situacion de alumno no establecida NO sumar<br>";}
						}
					}//fin si
				}//fin si hay contrato
			}//fin while
		}//fin si hay registros
		else
		{	
			echo"Sin Registros<br>";
		}
		//fin documento
	$sqli_main_1->free();
	
	$conexion_mysqli->close();
/////////////////////////////////////////////

//////////////////////////////////////////////
?>
<table align="center" border="1" width="80%">
<thead>
<tr>
	<th colspan="8">Resumen Talca Diurno <?php echo"[$semestre_consulta - $year_consulta]";?></th>
</tr>
<tr>
    <td>Carrera</td>
    <td>Nivel</td>
    <td>Total Matriculas</td>
    <td>Vigentes</td>
    <td>Pendientes</td>
    <td>Retirados</td>
    <td>Egresados</td>
    <td>Titulados</td>
 </tr>
</thead>
<tbody>
<?php

if(DEBUG){ var_dump($ARRAY["Talca"]["D"]);}
$SUMA_TOTAL_MOROSIDAD=0;
$SUMA_VIGENTES=0;
$SUMA_EGRESADOS=0;
$SUMA_TITULADOS=0;
$SUMA_NUEVOS["Talca"]["D"]=0;
$SUMA_ANTIGUOS["Talca"]["D"]=0;

foreach($ARRAY["Talca"]["D"] as $aux_programa => $array_promocion)
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
			
			if(isset($aux_array["egresados"]))
			{$aux_egresados=$aux_array["egresados"];}
			else{$aux_egresados=0;}
			
			if(isset($aux_array["titulados"]))
			{$aux_titulados=$aux_array["titulados"];}
			else{$aux_titulados=0;}
			
			if($i=="1"){ $SUMA_NUEVOS["Talca"]["D"]+=$aux_vigentes;}
			else{$SUMA_ANTIGUOS["Talca"]["D"]+=($aux_vigentes + $aux_egresados);}
			//-----------------------------------------------------------//
			$aux_total_matricula=($aux_vigentes+$aux_pendientes+$aux_retirados);
			$SUMA_TOTAL_MOROSIDAD+=$aux_total_morosidad;
			$SUMA_VIGENTES+=$aux_vigentes;
			$SUMA_EGRESADOS+=$aux_egresados;
			$SUMA_TITULADOS+=$aux_titulados;
			//-----------------------------------------------------------//
			echo'<tr>
					<td bgcolor="'.COLOR_CARRERA($aux_programa).'">'.NOMBRE_CARRERA($aux_programa).'</td>
					<td align="center">'.$i.'</td>
					<td align="right">'.$aux_total_matricula.'</td>
					<td align="right">'.$aux_vigentes.'</td>
					<td align="right">'.$aux_pendientes.'</td>
					<td align="right">'.$aux_retirados.'</td>
					<td align="right">'.$aux_egresados.'</td>
					<td align="right">'.$aux_titulados.'</td>
				 </tr>';
		}
	}
}
?>
<tr>
	<td colspan="3"><strong>Total</strong></td>
    <td align="right"><strong><?php echo $SUMA_VIGENTES;?></strong></td>
    <td colspan="2">&nbsp;</td>
    <td><strong><?php echo $SUMA_EGRESADOS;?></strong></td>
    <td><strong><?php echo $SUMA_TITULADOS;?></strong></td>
</tr>
<tr>
	<td><strong>Suma Nuevos</strong></td>
    <td><strong><?php echo $SUMA_NUEVOS["Talca"]["D"];?></strong></td>
	<td><strong>Suma Antiguos</strong></td>
    <td><strong><?php echo $SUMA_ANTIGUOS["Talca"]["D"];?></strong></td>
    <td><strong>SUMA TOTAL (nuevos + antiguos)</strong></td>
    <td><strong><?php echo $SUMA_ANTIGUOS["Talca"]["D"]+$SUMA_NUEVOS["Talca"]["D"];?></strong></td>
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
	<th colspan="8">Resumen Talca Vespertino <?php echo"[$semestre_consulta - $year_consulta]";?></th>
</tr>
<tr>
    <td>Carrera</td>
    <td>Nivel</td>
    <td>Total Matriculas</td>
    <td>Vigentes</td>
    <td>Pendientes</td>
    <td>Retirados</td>
     <td>Egresados</td>
    <td>Titulados</td>
 </tr>
</thead>
<tbody>
<?php

if(DEBUG){ var_dump($ARRAY["Talca"]["V"]);}
$SUMA_TOTAL_MOROSIDAD=0;
$SUMA_VIGENTES=0;
$SUMA_EGRESADOS=0;
$SUMA_TITULADOS=0;
$SUMA_NUEVOS["Talca"]["V"]=0;
$SUMA_ANTIGUOS["Talca"]["V"]=0;
foreach($ARRAY["Talca"]["V"] as $aux_programa => $array_promocion)
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
			
			if(isset($aux_array["egresados"]))
			{$aux_egresados=$aux_array["egresados"];}
			else{$aux_egresados=0;}
			
			if(isset($aux_array["titulados"]))
			{$aux_titulados=$aux_array["titulados"];}
			else{$aux_titulados=0;}
			
			if($i=="1"){ $SUMA_NUEVOS["Talca"]["V"]+=$aux_vigentes;}
			else{$SUMA_ANTIGUOS["Talca"]["V"]+=($aux_vigentes + $aux_egresados);}
			//-----------------------------------------------------------//
			$aux_total_matricula=($aux_vigentes+$aux_pendientes+$aux_retirados);
			$SUMA_TOTAL_MOROSIDAD+=$aux_total_morosidad;
			$SUMA_VIGENTES+=$aux_vigentes;
			$SUMA_EGRESADOS+=$aux_egresados;
			$SUMA_TITULADOS+=$aux_titulados;
			//-----------------------------------------------------------//
			echo'<tr>
					<td bgcolor="'.COLOR_CARRERA($aux_programa).'">'.NOMBRE_CARRERA($aux_programa).'</td>
					<td align="center">'.$i.'</td>
					<td align="right">'.$aux_total_matricula.'</td>
					<td align="right">'.$aux_vigentes.'</td>
					<td align="right">'.$aux_pendientes.'</td>
					<td align="right">'.$aux_retirados.'</td>
					<td align="right">'.$aux_egresados.'</td>
					<td align="right">'.$aux_titulados.'</td>
				 </tr>';
		}
	}
}
?>
<tr>
	<td colspan="3"><strong>Total</strong></td>
    <td align="right"><strong><?php echo $SUMA_VIGENTES;?></strong></td>
     <td colspan="2">&nbsp;</td>
    <td><strong><?php echo $SUMA_EGRESADOS;?></strong></td>
    <td><strong><?php echo $SUMA_TITULADOS;?></strong></td>
</tr>
<tr>
	<td><strong>Suma Nuevos</strong></td>
    <td><strong><?php echo $SUMA_NUEVOS["Talca"]["V"];?></strong></td>
	<td><strong>Suma Antiguos</strong></td>
    <td><strong><?php echo $SUMA_ANTIGUOS["Talca"]["V"];?></strong></td>
    <td><strong>SUMA TOTAL (nuevos + antiguos)</strong></td>
    <td><strong><?php echo $SUMA_ANTIGUOS["Talca"]["V"]+$SUMA_NUEVOS["Talca"]["V"];?></strong></td>
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
	<th colspan="8">Resumen Linares Diurno <?php echo"[$semestre_consulta - $year_consulta]";?></th>
</tr>
<tr>
    <td>Carrera</td>
    <td>Nivel</td>
    <td>Total Matriculas</td>
    <td>Vigentes</td>
    <td>Pendientes</td>
    <td>Retirados</td>
    <td>Egresados</td>
    <td>Titulados</td>
 </tr>
</thead>
<tbody>
<?php

if(DEBUG){ var_dump($ARRAY["Linares"]["D"]);}
$SUMA_TOTAL_MOROSIDAD=0;
$SUMA_VIGENTES=0;
$SUMA_EGRESADOS=0;
$SUMA_TITULADOS=0;
$SUMA_NUEVOS["Linares"]["D"]=0;
$SUMA_ANTIGUOS["Linares"]["D"]=0;
foreach($ARRAY["Linares"]["D"] as $aux_programa => $array_promocion)
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
			
			if(isset($aux_array["egresados"]))
			{$aux_egresados=$aux_array["egresados"];}
			else{$aux_egresados=0;}
			
			if(isset($aux_array["titulados"]))
			{$aux_titulados=$aux_array["titulados"];}
			
			if($i=="1"){ $SUMA_NUEVOS["Linares"]["D"]+=$aux_vigentes;}
			else{$SUMA_ANTIGUOS["Linares"]["D"]+=($aux_vigentes + $aux_egresados);}
			//-----------------------------------------------------------//
			$aux_total_matricula=($aux_vigentes+$aux_pendientes+$aux_retirados);
			$SUMA_TOTAL_MOROSIDAD+=$aux_total_morosidad;
			$SUMA_VIGENTES+=$aux_vigentes;
			$SUMA_EGRESADOS+=$aux_egresados;
			$SUMA_TITULADOS+=$aux_titulados;
			//-----------------------------------------------------------//
			echo'<tr>
					<td bgcolor="'.COLOR_CARRERA($aux_programa).'">'.NOMBRE_CARRERA($aux_programa).'</td>
					<td align="center">'.$i.'</td>
					<td align="right">'.$aux_total_matricula.'</td>
					<td align="right">'.$aux_vigentes.'</td>
					<td align="right">'.$aux_pendientes.'</td>
					<td align="right">'.$aux_retirados.'</td>
					<td align="right">'.$aux_egresados.'</td>
					<td align="right">'.$aux_titulados.'</td>
				 </tr>';
		}
	}
}
?>
<tr>
	<td colspan="3"><strong>Total</strong></td>
    <td align="right"><strong><?php echo $SUMA_VIGENTES;?></strong></td>
     <td colspan="2">&nbsp;</td>
    <td><strong><?php echo $SUMA_EGRESADOS;?></strong></td>
    <td><strong><?php echo $SUMA_TITULADOS;?></strong></td>
</tr>
<tr>
	<td><strong>Suma Nuevos</strong></td>
    <td><strong><?php echo $SUMA_NUEVOS["Linares"]["D"];?></strong></td>
	<td><strong>Suma Antiguos</strong></td>
    <td><strong><?php echo $SUMA_ANTIGUOS["Linares"]["D"];?></strong></td>
    <td><strong>SUMA TOTAL (nuevos + antiguos)</strong></td>
    <td><strong><?php echo $SUMA_ANTIGUOS["Linares"]["D"]+$SUMA_NUEVOS["Linares"]["D"];?></strong></td>
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
	<th colspan="8">Resumen Linares Vespertino <?php echo"[$semestre_consulta - $year_consulta]";?></th>
</tr>
<tr>
    <td>Carrera</td>
    <td>Nivel</td>
    <td>Total Matriculas</td>
    <td>Vigentes</td>
    <td>Pendientes</td>
    <td>Retirados</td>
    <td>Egresados</td>
    <td>Titulados</td>
 </tr>
</thead>
<tbody>
<?php
if(DEBUG){ var_dump($ARRAY["Linares"]["V"]);}
$SUMA_TOTAL_MOROSIDAD=0;
$SUMA_VIGENTES=0;
$SUMA_EGRESADOS=0;
$SUMA_TITULADOS=0;
$SUMA_NUEVOS["Linares"]["V"]=0;
$SUMA_ANTIGUOS["Linares"]["V"]=0;
foreach($ARRAY["Linares"]["V"] as $aux_programa => $array_promocion)
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
			
			if(isset($aux_array["egresados"]))
			{$aux_egresados=$aux_array["egresados"];}
			else{$aux_egresados=0;}
			
			if(isset($aux_array["titulados"]))
			{$aux_titulados=$aux_array["titulados"];}
			
			if($i=="1"){ $SUMA_NUEVOS["Linares"]["V"]+=$aux_vigentes;}
			else{$SUMA_ANTIGUOS["Linares"]["V"]+=($aux_vigentes + $aux_egresados);}
			//-----------------------------------------------------------//
			$aux_total_matricula=($aux_vigentes+$aux_pendientes+$aux_retirados);
			$SUMA_TOTAL_MOROSIDAD+=$aux_total_morosidad;
			$SUMA_VIGENTES+=$aux_vigentes;
			$SUMA_EGRESADOS+=$aux_egresados;
			$SUMA_TITULADOS+=$aux_titulados;
			//-----------------------------------------------------------//
			echo'<tr>
					<td bgcolor="'.COLOR_CARRERA($aux_programa).'">'.NOMBRE_CARRERA($aux_programa).'</td>
					<td align="center">'.$i.'</td>
					<td align="right">'.$aux_total_matricula.'</td>
					<td align="right">'.$aux_vigentes.'</td>
					<td align="right">'.$aux_pendientes.'</td>
					<td align="right">'.$aux_retirados.'</td>
					<td align="right">'.$aux_egresados.'</td>
					<td align="right">'.$aux_titulados.'</td>
				 </tr>';
		}
	}
}


$tiempo_fin_script = microtime(true);
?>
<tr>
	<td colspan="3"><strong>Total</strong></td>
    <td align="right"><strong><?php echo $SUMA_VIGENTES;?></strong></td>
     <td colspan="2">&nbsp;</td>
    <td><strong><?php echo $SUMA_EGRESADOS;?></strong></td>
    <td><strong><?php echo $SUMA_TITULADOS;?></strong></td>
</tr>
<tr>
	<td><strong>Suma Nuevos</strong></td>
    <td><strong><?php echo $SUMA_NUEVOS["Linares"]["V"];?></strong></td>
	<td><strong>Suma Antiguos</strong></td>
    <td><strong><?php echo $SUMA_ANTIGUOS["Linares"]["V"];?></strong></td>
    <td><strong>SUMA TOTAL (nuevos + antiguos)</strong></td>
    <td><strong><?php echo $SUMA_ANTIGUOS["Linares"]["V"]+$SUMA_NUEVOS["Linares"]["V"];?></strong></td>
</tr>
</tbody>
</table>
</div>

<div id="resumen">
<table width="50%" border="1" align="center">
<thead>
  <tr>
    <th colspan="4">Resumen General al <?php echo date("d-m-Y H:i:s");?></th>
    </tr>
    </thead>
    <tbody>
  <tr>
    <td>&nbsp;</td>
    <td>Nuevos</td>
    <td>Antiguos</td>
    <td>Total</td>
  </tr>
  <tr>
    <td>Talca Diurno</td>
    <td><?php echo $SUMA_NUEVOS["Talca"]["D"];?></td>
    <td><?php echo $SUMA_ANTIGUOS["Talca"]["D"];?></td>
    <td><?php echo $SUMA_NUEVOS["Talca"]["D"]+$SUMA_ANTIGUOS["Talca"]["D"];?></td>
  </tr>
  <tr>
    <td>Talca Vespertino</td>
     <td><?php echo $SUMA_NUEVOS["Talca"]["V"];?></td>
    <td><?php echo $SUMA_ANTIGUOS["Talca"]["V"];?></td>
    <td><?php echo $SUMA_NUEVOS["Talca"]["V"]+$SUMA_ANTIGUOS["Talca"]["V"];?></td>
  </tr>
  <tr>
    <td bgcolor="#99FFCC"><strong>Total Talca</strong></td>
    <td bgcolor="#99FFCC"><strong><?php echo $SUMA_NUEVOS["Talca"]["D"]+$SUMA_NUEVOS["Talca"]["V"];?></strong></td>
    <td bgcolor="#99FFCC"><strong><?php echo $SUMA_ANTIGUOS["Talca"]["D"]+$SUMA_ANTIGUOS["Talca"]["V"];?></strong></td>
    <td bgcolor="#99FFCC"><strong><?php echo $SUMA_NUEVOS["Talca"]["D"]+$SUMA_ANTIGUOS["Talca"]["D"]+$SUMA_NUEVOS["Talca"]["V"]+$SUMA_ANTIGUOS["Talca"]["V"];?></strong></td>
  </tr>
  <tr>
    <td>Linares Diurno</td>
    <td><?php echo $SUMA_NUEVOS["Linares"]["D"];?></td>
    <td><?php echo $SUMA_ANTIGUOS["Linares"]["D"];?></td>
    <td><?php echo $SUMA_NUEVOS["Linares"]["D"]+$SUMA_ANTIGUOS["Linares"]["D"];?></td>
  </tr>
  <tr>
    <td>Linares Vespertino</td>
     <td><?php echo $SUMA_NUEVOS["Linares"]["V"];?></td>
    <td><?php echo $SUMA_ANTIGUOS["Linares"]["V"];?></td>
    <td><?php echo $SUMA_NUEVOS["Linares"]["V"]+$SUMA_ANTIGUOS["Linares"]["V"];?></td>
  </tr>
  <tr>
    <td bgcolor="#99FFCC"><strong>Total Linares</strong></td>
    <td bgcolor="#99FFCC"><strong><?php echo $SUMA_NUEVOS["Linares"]["D"]+$SUMA_NUEVOS["Linares"]["V"];?></strong></td>
    <td bgcolor="#99FFCC"><strong><?php echo $SUMA_ANTIGUOS["Linares"]["D"]+$SUMA_ANTIGUOS["Linares"]["V"];?></strong></td>
    <td bgcolor="#99FFCC"><strong><?php echo $SUMA_NUEVOS["Linares"]["D"]+$SUMA_ANTIGUOS["Linares"]["D"]+$SUMA_NUEVOS["Linares"]["V"]+$SUMA_ANTIGUOS["Linares"]["V"];?></strong></td>
  </tr>
  </tbody>
</table>

<div id="tiempo_ejecucion" align="center"><?php echo "<br>Tiempo empleado: " . round($tiempo_fin_script - $tiempo_inicio_script,4)." Segundos"; ?></div>
</div>
</body>
</html>