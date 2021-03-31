<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG",false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_seguimientoXCohorte_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
set_time_limit(6000);
ini_set('memory_limit', '-1');
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
//---------------------------------------------//
require("../../../funciones/conexion_v2.php");
require("../../../funciones/funciones_sistema.php");
//Periodo
$year_actual=date("Y");
$mes_actual=date("m");
$mostrar_detalle=true;//muestra listalle de alumno y situacion x año

if($mes_actual>=8)
{ $semestre_actual=2;}
else
{ $semestre_actual=1;}

if($_GET)
{
	if(DEBUG){ echo"Hay Get<br>";}
	$year_consulta=strip_tags(mysqli_real_escape_string($conexion_mysqli, $_GET["year"]));
	$semestre_consulta=$semestre_actual;
	$sede_consulta=strip_tags(mysqli_real_escape_string($conexion_mysqli, $_GET["sede"]));
	$jornada_consulta=strip_tags(mysqli_real_escape_string($conexion_mysqli, $_GET["jornada"]));
}
else
{
	if(DEBUG){ echo"NO Hay Get<br>";}
	$year_consulta=$year_actual;
	$semestre_consulta=$semestre_actual;
	$sede_consulta=0;
	$jornada_consulta=0;
}
//-----------------------------------------------//
require("../../../funciones/VX.php");
$evento="Cohorte Institucional year $year_consulta";
REGISTRA_EVENTO($evento);
//--------------------------------------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Cohorte institucional V2</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:1;
	left: 425px;
	top: 103px;
}
#apDiv2 {position:absolute;
	width:40%;
	height:58px;
	z-index:2;
	left: 30%;
	top: 90px;
}
#apDiv3 {
	position:absolute;
	width:100%;
	height:115px;
	z-index:3;
	left: 0%;
	top: 266px;
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
<h1 id="banner">Administrador - Cohorte Institucional</h1>
<div id="link"><br /><a href="../../Alumnos/menualumnos.php" class="button">Volver a Menu</a><br />
<br />
<a href="cohorte_institucional_1_v2_segun_notas.php" class="button_R">cohorte segun Notas</a></div>
<div id="apDiv2">
  <form action="cohorte_institucional_1_v2.php" method="get" id="frm">
    <table width="100%" border="1">
      <thead>
        <tr>
          <th colspan="2">Parametros Busqueda</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Sede</td>
          <td><?php echo CAMPO_SELECCION("sede", "sede",$sede_consulta,true);?></td>
        </tr>
        <tr>
          <td>a&ntilde;o</td>
          <td><?php echo CAMPO_SELECCION("year", "year", $year_consulta,false);?></td>
        </tr>
        <tr>
          <td>Jornada</td>
          <td><?php echo CAMPO_SELECCION("jornada", "jornada", $jornada_consulta,true);?>*Utilizar todas (filtrar solo para depurar)</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><a href="#" class="button_R" onclick="javascript:document.getElementById('frm').submit();">Consultar</a></td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<div id="apDiv3">
<?php
	$ARRAY_GLOBAL=array();
	$ARRAY_RESULTADOS=array();
	$ARRAY_id_carrera=array();
	$ARRAY_SEDE=array("Talca", "Linares");
	$ARRAY_DATOS_ALUMNO=array();
	
	
	$year_contrato=$year_consulta;
	if($sede_consulta!="0"){$condicion_sede="AND contratos2.sede='$sede_consulta'";}
	else{ $condicion_sede="";}
	
	if($jornada_consulta!="0"){$condicion_jornada="AND contratos2.jornada='$jornada_consulta'";}
	else{ $condicion_jornada="";}
	$aux_G=1;
	
	
	//lleno array inicial de universo de alumnos
	$cons_MAIN="SELECT DISTINCT(id_alumno) FROM contratos2  WHERE contratos2.ano='$year_contrato' AND contratos2.yearIngresoCarrera='$year_consulta' $condicion_sede $condicion_jornada ORDER BY contratos2.id_carrera";
	
	$sqli_MAIN=$conexion_mysqli->query($cons_MAIN)or die($conexion_mysqli->error);
	$num_registros=$sqli_MAIN->num_rows;
	if(DEBUG){ echo"Consulta GLobal: $cons_MAIN<br>Num reg: $num_registros<br>";}
	require("../../../funciones/class_ALUMNO.php");
	
	
	if($num_registros>0)
	{
		$aux=0;
		while($CA=$sqli_MAIN->fetch_row())
		{
			$aux++;
			$id_alumno=$CA[0];
			$ARRAY_GLOBAL[$sede_consulta][$id_alumno]=true;
		}
	}
	else
	{
		if(DEBUG){ echo"sin alumnos en matricula inicial este año";}
	}
	//----------------------------------------------------------------------------------------------------------//

	///recorremos arreglos
	
	$ARRAY_GLOBAL2=array();
	while($year_contrato<=$year_actual)
	{
		$c=0;	
		if(DEBUG){ echo"<strong>YEAR CONTRATO: $year_contrato</strong><br>";}
		foreach($ARRAY_GLOBAL as $aux_sede => $aux_array_1)
		{
			foreach($aux_array_1 as $aux_id_alumno => $utilizar_alumno)
			{
				$c++;
				if(DEBUG){echo"<br><strong>[$c]</strong> id_alumno: $aux_id_alumno<br>";}
					for($s=1;$s<=2;$s++)
					{
						if($utilizar_alumno)
						{
							if(DEBUG){ echo"<br><strong>Semestre: $s</strong><br>Utilizar al alumno<br>";}
							///ver si tiene  matricula
							$condicion_alumno_este_year="";
							$es_titulado=false;
							$es_egresado=false;
							$es_matriculado=false;
							$es_retirado=false;

							$ALUMNO=new ALUMNO($aux_id_alumno);
							//$ALUMNO->SetDebug(DEBUG);
							$aux_sexo=$ALUMNO->getSexo();
							
							$ALUMNO->IR_A_PERIODO($s,$year_contrato);

							$condicionAlumnoPeriodo=$ALUMNO->getSituacionAlumnoPeriodo();
							$aux_idCarreraPeriodo=$ALUMNO->getIdCarreraPeriodo();
							$aux_jornadaPeriodo=$ALUMNO->getJornadaPeriodo();
							
							
							if(DEBUG){echo"condicion alumno periodo: $condicionAlumnoPeriodo<br>";}
							//-------------------------------------------------------------------------------//
							///identifico reincorporados
							if(!empty($ARRAY_DATOS_ALUMNO[$aux_id_alumno]["situacion"]))
							{
								if(($ARRAY_DATOS_ALUMNO[$aux_id_alumno]["situacion"]=="NN") or($ARRAY_DATOS_ALUMNO[$aux_id_alumno]["situacion"]=="R"))
								{
									if(($condicionAlumnoPeriodo!=="NN")and($condicionAlumnoPeriodo!=="R"))
									{
										$condicionAlumnoPeriodo="reincorporado";
										if(DEBUG){ echo"----> Alumno reincorporado detectado...<br>";}
									}
								}
							}
							//-----------------------------------------------------------------------------//
							
							switch($condicionAlumnoPeriodo)
							{
								case"T":
									$utilizar_alumno=false;
									$actualizar_situacion=true;
									$suma_total=false;
									$sumar_condicion=true;
									break;
								case"R":
									$utilizar_alumno=true;
									
									if($ARRAY_DATOS_ALUMNO[$aux_id_alumno]["situacion"]!=="R")
									{ $actualizar_situacion=true; $suma_total=true; $sumar_condicion=true;}//sumo ya que antes no era retirado
									else{ $condicion_alumno_este_year="-"; $actualizar_situacion=false; $suma_total=false; $sumar_condicion=false;}//no sumo nada ya que se mantiene como retirado
									break;	
								case"EG":
									$utilizar_alumno=true;
									if($ARRAY_DATOS_ALUMNO[$aux_id_alumno]["situacion"]!=="EG")
									{ $actualizar_situacion=true; $suma_total=true; $sumar_condicion=true;}//sumo ya que antes no era retirado
									else{ $actualizar_situacion=false; $suma_total=true; $sumar_condicion=false;}//no sumo nada ya que se mantiene como retirado
									
									break;	
								case"NN":
									$utilizar_alumno=true;
									$actualizar_situacion=true;
									if($ARRAY_DATOS_ALUMNO[$aux_id_alumno]["situacion"]!=="NN")
									{$suma_total=false; $sumar_condicion=true;}
									else{$suma_total=false; $sumar_condicion=false;}
									break;
								default:
									$utilizar_alumno=true;	
									$sumar_condicion=true;
									$actualizar_situacion=true;
									$suma_total=true;
							}

							
							///utilizar o no alumno en siguiente año
							if($actualizar_situacion){$ARRAY_DATOS_ALUMNO[$aux_id_alumno]["situacion"]=$condicionAlumnoPeriodo;}
							
							$ARRAY_GLOBAL2[$sede_consulta][$aux_idCarreraPeriodo][$aux_sexo][$aux_id_alumno]=$utilizar_alumno;
							$ARRAY_DATOS_ALUMNO[$aux_id_alumno]["id_carrera"]=$aux_idCarreraPeriodo;
							
							$ARRAY_DATOS_ALUMNO[$aux_id_alumno][$year_contrato][$s]=$condicionAlumnoPeriodo;
							//---------------------------------------------------------------------------------//
							if(!isset($ARRAY_id_carrera[$aux_idCarreraPeriodo])){$ARRAY_id_carrera[$aux_idCarreraPeriodo]=true;}
							//sumo a condicion
							if($sumar_condicion)
							{
								if(DEBUG){ echo"Sumo a condicion<br>";}
							if(isset($ARRAY_RESULTADOS[$year_contrato][$s][$sede_consulta][$aux_idCarreraPeriodo][$condicionAlumnoPeriodo][$aux_sexo])){ $ARRAY_RESULTADOS[$year_contrato][$s][$sede_consulta][$aux_idCarreraPeriodo][$condicionAlumnoPeriodo][$aux_sexo]+=1;}
								else{$ARRAY_RESULTADOS[$year_contrato][$s][$sede_consulta][$aux_idCarreraPeriodo][$condicionAlumnoPeriodo][$aux_sexo]=1;}	
							}
							if($suma_total)	
							{
								if(DEBUG){ echo"Sumo a Total sede: $sede_consulta [$s - $year_contrato] [id_carrera:  $aux_idCarreraPeriodo][$aux_sexo] [".$ARRAY_RESULTADOS[$year_contrato][$s][$sede_consulta][$aux_idCarreraPeriodo]["TOTAL"][$aux_sexo]."] -> ";}
							//sumo a total
								if(isset($ARRAY_RESULTADOS[$year_contrato][$s][$sede_consulta][$aux_idCarreraPeriodo]["TOTAL"][$aux_sexo])){ $ARRAY_RESULTADOS[$year_contrato][$s][$sede_consulta][$aux_idCarreraPeriodo]["TOTAL"][$aux_sexo]+=1;}
									else{$ARRAY_RESULTADOS[$year_contrato][$s][$sede_consulta][$aux_idCarreraPeriodo]["TOTAL"][$aux_sexo]=1;}	
								if(DEBUG){ echo"[".$ARRAY_RESULTADOS[$year_contrato][$s][$sede_consulta][$aux_idCarreraPeriodo]["TOTAL"][$aux_sexo]."]<br>";}	
							}
						}
						else
						{
							if(DEBUG){ echo"[$s- $year_contrato]No utilizar este alumno -> Ultima condicion: ".$ARRAY_DATOS_ALUMNO[$aux_id_alumno]["situacion"]."<br>";}
						}
					}//fin for semestre
				
			}//fin for utilizar alumno
			
		}
		$year_contrato++;
	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////
//escritura
$periodos=($year_actual-$year_consulta)+1;
if(DEBUG){ echo"Periodos: $periodos<br>";}
//-----------------///
$array_colores=array(0=>"FFFF00",1=>"00FF00");
$color_1="#FFAAAA";

					echo'<table width="95%" align="center" border="1">
						<thead>
						  <tr>
							<th colspan="18">COHORTE X CARRERA <br>Sede: '.$sede_consulta.' - consulta '.$year_consulta.' Jornada: '.$jornada_consulta.'</th>
							</tr>';

					echo'<tr>
							<td rowspan="2">Carrera</td>
							<td rowspan="2">Semestre</td>
							<td rowspan="2">Year</td>
							
								<td colspan="2" align="center"><font size="1">Titulados</font></td>
								<td colspan="2" align="center"><font size="1">Egresados</font></td>
								<td colspan="2" align="center"><font size="1">Retirados</font></td>
								<td colspan="2" align="center"><font size="1">Faltantes</font></td>
								<td colspan="2" align="center"><font size="1">Reincorporados</font></td>
								<td colspan="2" align="center"><font size="1">Vigentes</font></td>
								<td colspan="2"  align="center"><a title="=(vigentes + retirados + egresados)">TOTAL MATRICULA</a></td>
								<td align="center"  rowspan="2"><font size="1"><a href="#" title="% retencion">%</a></font></td>
						</tr>		
						  <tr>
								<td align="center">H</td>
								<td align="center">M</td>
								<td align="center">H</td>
								<td align="center">M</td>
								<td align="center">H</td>
								<td align="center">M</td>
								<td align="center">H</td>
								<td align="center">M</td>
								<td align="center">H</td>
								<td align="center">M</td>
								<td align="center">H</td>
								<td align="center">M</td>
								<td align="center" bgcolor="'.$color_1.'" >H</td>
								<td align="center" bgcolor="'.$color_1.'" >M</td>
						</tr>
								</thead>
						<tbody>';	
					
					$ARRAY_TOTAL=array();	
					foreach($ARRAY_id_carrera as $aux_id_carrera_G => $estado)	
					{
						$MATRICULA_INICIAL=0;
						$EGRESADOS_X_CARRERA=0;
						$TITULADOS_X_CARRERA=0;
						
						
						$matricula_anterior_H=0;
						$matricula_anterior_M=0;
						$primera_vuelta=true;	
						for($y4=$year_consulta;$y4<=$year_actual;$y4++)
							{
								for($s=1;$s<=2;$s++)
								{
									
									echo'<tr>
								<td bgcolor="'.COLOR_CARRERA($aux_id_carrera_G).'">'.$aux_id_carrera_G.'_'.NOMBRE_CARRERA($aux_id_carrera_G).'</td>
								<td align="center">'.$s.'</td>
								<td align="center">'.$y4.'</td>';
									
									$MATRICULA_DE_YEAR=0;
									$porcentaje_retencion_del_year=0;
									
									//vigentes
									if(isset($ARRAY_RESULTADOS[$y4][$s][$sede_consulta][$aux_id_carrera_G]["V"]["M"])){$vigente_hombre=$ARRAY_RESULTADOS[$y4][$s][$sede_consulta][$aux_id_carrera_G]["V"]["M"];}
									else{ $vigente_hombre=0;}
									if(isset($ARRAY_RESULTADOS[$y4][$s][$sede_consulta][$aux_id_carrera_G]["V"]["F"])){$vigente_mujeres=$ARRAY_RESULTADOS[$y4][$s][$sede_consulta][$aux_id_carrera_G]["V"]["F"];}
									else{ $vigente_mujeres=0;}
									//titulados
									if(isset($ARRAY_RESULTADOS[$y4][$s][$sede_consulta][$aux_id_carrera_G]["T"]["M"])){$titulados_hombre=$ARRAY_RESULTADOS[$y4][$s][$sede_consulta][$aux_id_carrera_G]["T"]["M"];}
									else{ $titulados_hombre=0;}
									if(isset($ARRAY_RESULTADOS[$y4][$s][$sede_consulta][$aux_id_carrera_G]["T"]["F"])){$titulados_mujeres=$ARRAY_RESULTADOS[$y4][$s][$sede_consulta][$aux_id_carrera_G]["T"]["F"];}
									else{ $titulados_mujeres=0;}
									//egresado
									if(isset($ARRAY_RESULTADOS[$y4][$s][$sede_consulta][$aux_id_carrera_G]["EG"]["M"])){$egresados_hombre=$ARRAY_RESULTADOS[$y4][$s][$sede_consulta][$aux_id_carrera_G]["EG"]["M"];}
									else{ $egresados_hombre=0;}
									if(isset($ARRAY_RESULTADOS[$y4][$s][$sede_consulta][$aux_id_carrera_G]["EG"]["F"])){$egresados_mujeres=$ARRAY_RESULTADOS[$y4][$s][$sede_consulta][$aux_id_carrera_G]["EG"]["F"];}
									else{ $egresados_mujeres=0;}
									//retirado
									if(isset($ARRAY_RESULTADOS[$y4][$s][$sede_consulta][$aux_id_carrera_G]["R"]["M"])){$retirados_hombre=$ARRAY_RESULTADOS[$y4][$s][$sede_consulta][$aux_id_carrera_G]["R"]["M"];}
									else{ $retirados_hombre=0;}
									if(isset($ARRAY_RESULTADOS[$y4][$s][$sede_consulta][$aux_id_carrera_G]["R"]["F"])){$retirados_mujeres=$ARRAY_RESULTADOS[$y4][$s][$sede_consulta][$aux_id_carrera_G]["R"]["F"];}
									else{ $retirados_mujeres=0;}
									//faltantes NN
									if(isset($ARRAY_RESULTADOS[$y4][$s][$sede_consulta][$aux_id_carrera_G]["NN"]["M"])){$faltantes_hombre=$ARRAY_RESULTADOS[$y4][$s][$sede_consulta][$aux_id_carrera_G]["NN"]["M"];}
									else{ $faltantes_hombre=0;}
									if(isset($ARRAY_RESULTADOS[$y4][$s][$sede_consulta][$aux_id_carrera_G]["NN"]["F"])){$faltantes_mujeres=$ARRAY_RESULTADOS[$y4][$s][$sede_consulta][$aux_id_carrera_G]["NN"]["F"];}
									else{ $faltantes_mujeres=0;}
									//reincorporados
									if(isset($ARRAY_RESULTADOS[$y4][$s][$sede_consulta][$aux_id_carrera_G]["reincorporado"]["M"])){$reincorporados_hombre=$ARRAY_RESULTADOS[$y4][$s][$sede_consulta][$aux_id_carrera_G]["reincorporado"]["M"];}
									else{ $reincorporados_hombre=0;}
									if(isset($ARRAY_RESULTADOS[$y4][$s][$sede_consulta][$aux_id_carrera_G]["reincorporado"]["F"])){$reincorporados_mujeres=$ARRAY_RESULTADOS[$y4][$s][$sede_consulta][$aux_id_carrera_G]["reincorporado"]["F"];}
									else{ $reincorporados_mujeres=0;}
									///PARA CALCULOS
									
									if(isset($ARRAY_RESULTADOS[$y4][$s][$sede_consulta][$aux_id_carrera_G]["TOTAL"]["M"])){$TOTAL_MATRICULA_HOMBRES=$ARRAY_RESULTADOS[$y4][$s][$sede_consulta][$aux_id_carrera_G]["TOTAL"]["M"];}
									else{ $TOTAL_MATRICULA_HOMBRES=0;}
									
									if(isset($ARRAY_RESULTADOS[$y4][$s][$sede_consulta][$aux_id_carrera_G]["TOTAL"]["F"])){$TOTAL_MATRICULA_MUJERES=$ARRAY_RESULTADOS[$y4][$s][$sede_consulta][$aux_id_carrera_G]["TOTAL"]["F"];}
									else{ $TOTAL_MATRICULA_MUJERES=0;}
									
									//------------------------------------------------------------------------------//
									if($primera_vuelta)
									{
										$MATRICULA_INICIAL=($TOTAL_MATRICULA_HOMBRES+$TOTAL_MATRICULA_MUJERES); 
										$MAtricula_INICIAL_H=$TOTAL_MATRICULA_HOMBRES; 
										$MAtricula_INICIAL_M=$TOTAL_MATRICULA_MUJERES;
										$primera_vuelta=false;
									}
									$EGRESADOS_X_CARRERA+=($egresados_hombre+$egresados_mujeres+$titulados_hombre+$titulados_mujeres);
									$MATRICULA_DE_YEAR=($TOTAL_MATRICULA_HOMBRES+$TOTAL_MATRICULA_MUJERES);
									$TITULADOS_X_CARRERA+=($titulados_hombre+$titulados_mujeres);
									if($y4>$year_consulta)
									{
										if($MATRICULA_INICIAL>0)
										{$porcentaje_retencion_del_year=((($MATRICULA_DE_YEAR)*100)/$MATRICULA_INICIAL);}
										else{$porcentaje_retencion_del_year=0;}
				
									}
									else
									{
										
									}
									//-----------------------------------------------------------------------------------//
									//guardo para año siguiente
									$matricula_anterior_H=$vigente_hombre;
									$matricula_anterior_M=$vigente_mujeres;
									//------------------------------------------------------------------------------------//
									
									
									
									
									$ARRAY_TOTAL[$y4][$s]["V"]["H"]+=$vigente_hombre;
									$ARRAY_TOTAL[$y4][$s]["V"]["F"]+=$vigente_mujeres;
									$ARRAY_TOTAL[$y4][$s]["T"]["H"]+=$titulados_hombre;
									$ARRAY_TOTAL[$y4][$s]["T"]["F"]+=$titulados_mujeres;
									$ARRAY_TOTAL[$y4][$s]["EG"]["H"]+=$egresados_hombre;
									$ARRAY_TOTAL[$y4][$s]["EG"]["F"]+=$egresados_mujeres;
									$ARRAY_TOTAL[$y4][$s]["R"]["H"]+=$retirados_hombre;
									$ARRAY_TOTAL[$y4][$s]["R"]["F"]+=$retirados_mujeres;
									$ARRAY_TOTAL[$y4][$s]["NN"]["H"]+=$faltantes_hombre;
									$ARRAY_TOTAL[$y4][$s]["NN"]["F"]+=$faltantes_mujeres;
									$ARRAY_TOTAL[$y4][$s]["reincorporado"]["H"]+=$reincorporados_hombre;
									$ARRAY_TOTAL[$y4][$s]["reincorporado"]["F"]+=$reincorporados_mujeres;
									$ARRAY_TOTAL[$y4][$s]["TOTAL"]["H"]+=$TOTAL_MATRICULA_HOMBRES;
									$ARRAY_TOTAL[$y4][$s]["TOTAL"]["F"]+=$TOTAL_MATRICULA_MUJERES;
									
									if($y4%2==0){ $color=$array_colores[0];}
									else{$color=$array_colores[1];}
									
									echo'<td align="center" bgcolor="'.COLOR_CARRERA($aux_id_carrera_G).'"><font size="3" color="#00F">'.$titulados_hombre.'</font></td>
									<td align="center" bgcolor="'.COLOR_CARRERA($aux_id_carrera_G).'"><font size="3" color="#00F">'.$titulados_mujeres.'</font></td>
									<td align="center" bgcolor="'.COLOR_CARRERA($aux_id_carrera_G).'"><font size="3" color="#04B404">'.$egresados_hombre.'</font></td>
										
									<td align="center" bgcolor="'.COLOR_CARRERA($aux_id_carrera_G).'"><font size="3" color="#04B404">'.$egresados_mujeres.'</font></td>
									
									<td align="center" bgcolor="'.COLOR_CARRERA($aux_id_carrera_G).'"><font size="3" color="#F00">'.$retirados_hombre.'</font></td>
									
									<td align="center" bgcolor="'.COLOR_CARRERA($aux_id_carrera_G).'"><font size="3" color="#F00">'.$retirados_mujeres.'</font></td>
									
									<td align="center" bgcolor="'.COLOR_CARRERA($aux_id_carrera_G).'"><font size="3" color="#099">'.$faltantes_hombre.'</font></td>
									
									<td align="center" bgcolor="'.COLOR_CARRERA($aux_id_carrera_G).'"><font size="3" color="#099">'.$faltantes_mujeres.'</font></td>
									
									<td align="center" bgcolor="'.COLOR_CARRERA($aux_id_carrera_G).'"><font size="3" color="#F0F">'.$reincorporados_hombre.'</font></td>
									
									<td align="center" bgcolor="'.COLOR_CARRERA($aux_id_carrera_G).'"><font size="3" color="#F0F">'.$reincorporados_mujeres.'</font></td>
									
									
									<td align="center" bgcolor="'.COLOR_CARRERA($aux_id_carrera_G).'"><font size="3">'.$vigente_hombre.'</fon></td>
									<td align="center" bgcolor="'.COLOR_CARRERA($aux_id_carrera_G).'"><font size="3">'.$vigente_mujeres.'</font></td>
									
									<td align="center" bgcolor="'.COLOR_CARRERA($aux_id_carrera_G).'"><strong>'.$TOTAL_MATRICULA_HOMBRES.'</strong></td>
									<td align="center" bgcolor="'.COLOR_CARRERA($aux_id_carrera_G).'"><strong>'.$TOTAL_MATRICULA_MUJERES.'</strong></td>
									
									<td align="center" bgcolor="'.COLOR_CARRERA($aux_id_carrera_G).'"><a href="#" title="matricula inicial: '.$MATRICULA_INICIAL.' Matricula del año: '.$MATRICULA_DE_YEAR.' Egresados/titulados: '.$EGRESADOS_X_CARRERA.'">'.number_format($porcentaje_retencion_del_year,1).'</a></td>';
								}
							}
					}//fin for carrera
					
					echo'</tr>';
				    echo'</tbody></table><br><p>---</p>';
//----------------------------------------------------------------------------------------------------------------------------------------------//							
						echo'<table width="95%" align="center" border="1">
						<thead>
						  <tr>
							<th colspan="17">COHORTE INSTITUCIONAL <br>Sede: '.$sede_consulta.' - consulta '.$year_consulta.' Jornada: '.$jornada_consulta.'</th>
							</tr>';

					echo'<tr>
						
							<td rowspan="2">Semestre</td>
							<td rowspan="2">Year</td>
							
								<td colspan="2" align="center"><font size="1">Titulados</font></td>
								<td colspan="2" align="center"><font size="1">Egresados</font></td>
								<td colspan="2" align="center"><font size="1">Retirados</font></td>
								<td colspan="2" align="center"><font size="1">Faltantes</font></td>
								<td colspan="2" align="center"><font size="1">Reincorporados</font></td>
								<td colspan="2" align="center"><font size="1">Vigentes</font></td>
								<td colspan="2"  align="center"><a title="=(vigentes + retirados + egresados)">TOTAL MATRICULA</a></td>
								<td align="center"  rowspan="2"><font size="1"><a href="#" title="% retencion">%</a></font></td>
						</tr>		
						  <tr>
								<td align="center">H</td>
								<td align="center">M</td>
								<td align="center">H</td>
								<td align="center">M</td>
								<td align="center">H</td>
								<td align="center">M</td>
								<td align="center">H</td>
								<td align="center">M</td>
								<td align="center">H</td>
								<td align="center">M</td>
								<td align="center">H</td>
								<td align="center">M</td>
								<td align="center" bgcolor="'.$color_1.'" >H</td>
								<td align="center" bgcolor="'.$color_1.'" >M</td>
								</thead>
						<tbody>';	
					
					
					$primera_vuelta=true;
					for($y=$year_consulta;$y<=$year_actual;$y++)	
					{
						for($s=1;$s<=2;$s++)
						{
							$T_vigentes_hombres=$ARRAY_TOTAL[$y][$s]["V"]["H"];
							$T_vigentes_mujeres=$ARRAY_TOTAL[$y][$s]["V"]["F"];
							$T_titulados_hombres=$ARRAY_TOTAL[$y][$s]["T"]["H"];
							$T_titulados_mujeres=$ARRAY_TOTAL[$y][$s]["T"]["F"];
							$T_egresados_hombres=$ARRAY_TOTAL[$y][$s]["EG"]["H"];
							$T_egresados_mujeres=$ARRAY_TOTAL[$y][$s]["EG"]["F"];
							$T_retirados_hombres=$ARRAY_TOTAL[$y][$s]["R"]["H"];
							$T_retirados_mujeres=$ARRAY_TOTAL[$y][$s]["R"]["F"];
							$T_faltantes_hombres=$ARRAY_TOTAL[$y][$s]["NN"]["H"];
							$T_faltantes_mujeres=$ARRAY_TOTAL[$y][$s]["NN"]["F"];
							$T_reincorporados_hombres=$ARRAY_TOTAL[$y][$s]["reincorporado"]["H"];
							$T_reincorporados_mujeres=$ARRAY_TOTAL[$y][$s]["reincorporado"]["F"];
							$T_TOTAL_hombres=$ARRAY_TOTAL[$y][$s]["TOTAL"]["H"];
							$T_TOTAL_mujeres=$ARRAY_TOTAL[$y][$s]["TOTAL"]["F"];
							
							
					//CALCULO
					//------------------------------------------------------------------------------//
						//utilizo 2 semestre de primer año  para matricula inicial
							if(($primera_vuelta)and($s=="2"))
							{
								$primera_vuelta=false;
								$MATRICULA_INICIAL=($T_TOTAL_hombres+$T_TOTAL_mujeres); 
								$MAtricula_INICIAL_H=$T_TOTAL_hombres; 
								$MAtricula_INICIAL_M=$T_TOTAL_mujeres;
							}
							$EGRESADOS_X_CARRERA+=($T_egresados_hombres+$T_egresados_mujeres+$T_titulados_hombres+$T_titulados_mujeres);
							$MATRICULA_DE_YEAR=($T_TOTAL_hombres+$T_TOTAL_mujeres);
							$TITULADOS_X_CARRERA+=($T_titulados_hombres+$T_titulados_mujeres);
							if($y>$year_consulta)
							{
								if($MATRICULA_INICIAL>0)
								{$porcentaje_retencion_del_year=((($MATRICULA_DE_YEAR)*100)/$MATRICULA_INICIAL);}
								else{$porcentaje_retencion_del_year=0;}
		
							}
							else
							{
								
							}
							//-----------------------------------------------------------------------------------//
							//guardo para año siguiente
							$matricula_anterior_H=$T_vigentes_hombres;
							$matricula_anterior_M=$T_vigentes_mujeres;
							//------------------------------------------------------------------------------------//
							
							if($s%2==0){ $colorx='#e5e5e5';}
							else{ $colorx="#f5f5f5";}
							
							echo'<tr>
									<td bgcolor="'.$colorx.'" align="center">'.$s.'</td>
									<td bgcolor="'.$colorx.'" align="center">'.$y.'</td>
									<td bgcolor="'.$colorx.'" align="center">'.$T_titulados_hombres.'</td>
									<td bgcolor="'.$colorx.'" align="center">'.$T_titulados_mujeres.'</td>
									<td bgcolor="'.$colorx.'" align="center">'.$T_egresados_hombres.'</td>
									<td bgcolor="'.$colorx.'" align="center">'.$T_egresados_mujeres.'</td>
									<td bgcolor="'.$colorx.'" align="center">'.$T_retirados_hombres.'</td>
									<td bgcolor="'.$colorx.'" align="center">'.$T_retirados_mujeres.'</td>
									<td bgcolor="'.$colorx.'" align="center">'.$T_faltantes_hombres.'</td>
									<td bgcolor="'.$colorx.'" align="center">'.$T_faltantes_mujeres.'</td>
									<td bgcolor="'.$colorx.'" align="center">'.$T_reincorporados_hombres.'</td>
									<td bgcolor="'.$colorx.'" align="center">'.$T_reincorporados_mujeres.'</td>
									<td bgcolor="'.$colorx.'" align="center">'.$T_vigentes_hombres.'</td>
									<td bgcolor="'.$colorx.'" align="center">'.$T_vigentes_mujeres.'</td>
									<td bgcolor="'.$colorx.'" align="center"><strong>'.$T_TOTAL_hombres.'</strong></td>
									<td bgcolor="'.$colorx.'" align="center"><strong>'.$T_TOTAL_mujeres.'</strong></td>
									<td bgcolor="'.$colorx.'" align="center">'.number_format($porcentaje_retencion_del_year,1).'</td>
								 </tr>';
						}
					}
					 echo'</tbody></table><br><p>---</p>';		
///----------------------------------------------------------------------------------------------------------------------------------------------//							
							if($mostrar_detalle)
							{
								echo'<table border="1" width="85%" align="center">
									<thead>
										<tr>
											<th colspan="'.(2*$periodos+4).'">Detalle de Cohorte X alumno</th>
										</tr>
										<tr>
											<td>N</td>
											<td>id_alumno</td>
											<td>Alumno</td>
											<td>Carrera</td>';
											for($y7=$year_consulta;$y7<=$year_actual;$y7++)
											{ for($s=1;$s<=2;$s++){ echo'<td align="center">'.$s.'-'.$y7.'</td>';}}
											
								echo'</tr></thead><tbody>';
									
								$contador=0;
								$num_titulados=0;
								$num_egresados=0;
								$cuenta_alumnos=0;
								$primera_vuelta=true;
								$SUMA_CONTINUOS=0;
								foreach($ARRAY_DATOS_ALUMNO as $aux_id_alumno => $aux_array_A)
								{
									$contador++;
									$aux_id_carrera_A=$aux_array_A["id_carrera"];
									//---------------------------------------------//
										$cons_A="SELECT rut, nombre, apellido_P, apellido_M FROM alumno WHERE id='$aux_id_alumno' LIMIT 1";
										$sqli_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
										$AX=$sqli_A->fetch_assoc();
											$Alumno=$AX["nombre"]." ".$AX["apellido_P"]." ".$AX["apellido_M"];
											$rut=$AX["rut"];
										$sqli_A->free();	
									//----------------------------------------------//
									$mostrar_info=false;
									if($primera_vuelta){$id_carrera_old=$aux_id_carrera_A; $primera_vuelta=false;}
											
									if($id_carrera_old!==$aux_id_carrera_A)
									{$porcentaje_titulados=(($num_titulados*100)/$cuenta_alumnos); 
									$porcentaje_retencion_periodo=(($SUMA_CONTINUOS*100)/$cuenta_alumnos);
									$msj_retencion="$SUMA_CONTINUOS -> ".number_format($porcentaje_retencion_periodo,2)."%";
									//restablecer variables
									$SUMA_CONTINUOS=0;
									$msj_info="Total Alumno $cuenta_alumnos - NUM. Titulados: $num_titulados"; 
									$num_titulados=0; $num_egresados=0; $cuenta_alumnos=0;  $mostrar_info=true;
									}
									$cuenta_alumnos++;
									$id_carrera_old=$aux_id_carrera_A;
									if($mostrar_info)
									{
										echo'<tr>
											<td colspan="'.(2*$periodos+4).'" bgcolor="#FFCC00">Porcentaje Titulados '.number_format($porcentaje_titulados,2).' % '.$msj_info.' - Retencion del Periodo['.$msj_retencion.']</td>
											</tr>';
											
									}
									
									echo'<tr>
											<td>'.$cuenta_alumnos.'/'.$contador.' </td>
											<td>'.$aux_id_alumno.'</td>
											<td>'.$Alumno.'</td>
											<td>'.NOMBRE_CARRERA($aux_id_carrera_A).'</td>';
									
									$alumno_continuo_en_year=true;		
									for($y7=$year_consulta;$y7<=$year_actual;$y7++)
									{
										for($s=1;$s<=2;$s++)
										{
											if(isset($aux_array_A[$y7][$s])){$aux_situacion_del_year=$aux_array_A[$y7][$s];}
											else{$aux_situacion_del_year="-";}
											
											
											
											switch($aux_situacion_del_year)
											{
												case"V":
													$color_situacion='#00FF00';
													break;
												case"R":
													$color_situacion='#FF0000';
													$alumno_continuo_en_year=false;
													break;	
												case"T":
													$color_situacion='#0000AA';
													$num_titulados++;
													break;	
												case"NN":
													$color_situacion='#FFFF00';
													$alumno_continuo_en_year=false;
													break;	
												case"EG":
													$color_situacion='#00CC00';
													$num_egresados++;
													break;	
												default:
													$color_situacion='';
													$alumno_continuo_en_year=false;
													break;	
											}
											
											
											echo'<td bgcolor="'.$color_situacion.'" align="center">'.$aux_situacion_del_year.'</td>';
										}
									}
									if($alumno_continuo_en_year){ $SUMA_CONTINUOS++;}
									echo'</tr>';
									
									
								}
								$porcentaje_titulados=(($num_titulados*100)/$cuenta_alumnos); $msj_info="Total Alumno $cuenta_alumnos - NUM. Titulados: $num_titulados"; $num_titulados=0; $num_egresados=0;  $mostrar_info=true;
								$porcentaje_retencion_periodo=(($SUMA_CONTINUOS*100)/$cuenta_alumnos);
								$cuenta_alumnos=0; 
								$msj_retencion="$SUMA_CONTINUOS -> ".number_format($porcentaje_retencion_periodo,2)."%";
								if($mostrar_info)
								{
									echo'<tr>
										<td colspan="'.(2*$periodos+4).'" bgcolor="#FFCC00">Porcentaje Titulados '.number_format($porcentaje_titulados,2).' % '.$msj_info.' Retencion del Periodo['.$msj_retencion.']</td>
										</tr>';
								}
								echo'</tbody></table>';
							}
				

?>

</div>
</body>
</html>