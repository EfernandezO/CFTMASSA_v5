<?php
//-----------------------------------------//
	require("../../../Edicion_carreras/OKALIS/seguridad.php");
	require("../../../Edicion_carreras/OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
//-----------------------------------------//	
//var_dump($_POST);
//////////////////////////
define("DEBUG",false);
set_time_limit(300);
if($_POST)
{
	$sede=$_POST["fsede"];
	$array_carrera=$_POST["carrera"];
	$array_carrera=explode("_",$array_carrera);
	$id_carrera=$array_carrera[0];
	$carrera=$array_carrera[1];
	$año_ingreso=$_POST["ano_ingreso"];
	$jornada=$_POST["jornada"];
	$situacion=$_POST["estado"];
	$grupo=$_POST["grupo"];
	$nivel=$_POST["nivel"];
	
	$semestre_actual=$_POST["semestre_vigencia_contrato"];
	$year_actual=$_POST["year_vigencia_contrato"];
}
if($_GET)
{
	$sede=base64_decode($_GET["sede"]);
	$id_carrera=base64_decode($_GET["id_carrera"]);
	$carrera=base64_decode($_GET["carrera"]);
	$año_ingreso=base64_decode($_GET["ingreso"]);
	$jornada=base64_decode($_GET["jornada"]);
	$situacion=base64_decode($_GET["situacion"]);
	$grupo=base64_decode($_GET["grupo"]);
	$nivelx=base64_decode($_GET["niveles"]);
	$nivelx=explode(",",$nivelx);
	if(DEBUG){var_dump($nivelx);}
	$indice=0;
	foreach($nivelx as $nx => $valorx)
	{
		if(!empty($valorx))
		{
			$nivel[$indice]=$valorx;
			$indice++;
		}
	}
	
	$semestre_actual=base64_decode($_GET["semestre"]);
	$year_actual=base64_decode($_GET["year"]);
}
$estado_financiero="todos";
$verificar_contrato=true;
$no_mostrar_retirados=false;

if(DEBUG){ var_export($_POST);}


$condicion="";
if($sede!="todas"){ $condicion=" alumno.sede='$sede' AND contratos2.condicion<>'inactivo'";}
else{ $condicion=" contratos2.condicion<>'inactivo'";}


if($id_carrera>0)
{ $condicion.=" AND alumno.id_carrera='$id_carrera'";}

if($año_ingreso!="Todos")
{
	$condicion.=" AND alumno.ingreso='$año_ingreso'";
}
if($jornada!="T")
{
	$condicion.=" AND alumno.jornada='$jornada'";
}
if($situacion!="A")
{
	$condicion.=" AND alumno.situacion IN('$situacion','M')";//la condicion que sea mas moroso
}
if($grupo!="Todos")
{
	$condicion.=" AND alumno.grupo='$grupo'";
}
$inicio_ciclio=true;
$niveles="";
$niveles_post="";
if(is_array($nivel))
{
	foreach($nivel as $nn=>$valornn)
	{
		if($inicio_ciclio)
		{ 
			$niveles.="'$valornn'";
			$inicio_ciclio=false;
			$niveles_post.=",$valornn";
		}
		else
		{ $niveles.=", '$valornn'"; $niveles_post.=",$valornn";}
		//echo"--> $niveles<br>";
	}
}
else{ $niveles="'sin nivel'";}

$condicion.="AND alumno.nivel IN($niveles)";

$msj="Alumnos Sede: $sede Carrera: $carrera Jornada: $jornada Grupo: $grupo<br>Estado Academico: $situacion<br>Año Ingreso: $año_ingreso<br> Contratos Periodo ($semestre_actual - $year_actual) <br>Niveles: $niveles";

$fecha_actual=date("Y-m-d");
///////////////////////////
//alumno.grupo='A' AND alumno.situacion='V' AND alumno.nivel=1 AND alumno.sede='Talca'
include("../../../funciones/conexion_v2.php");
//--------------------------------------------------------//
/////Registro ingreso///
	 include("../../../funciones/VX.php");
	 $evento="Ve IASignacion Beca Alumnos->".$carrera." Sede: ".$sede." Jornada: ".$jornada;
	 REGISTRA_EVENTO($evento);
//--------------------------------------------------------//	 
$cons_becas="SELECT * FROM becas WHERE beca_condicion='activa'";
$sql_becas=mysql_query($cons_becas)or die("becas".mysql_error());
$num_becas_registradas=mysql_num_rows($sql_becas);
if(DEBUG){ echo"Num becas disponibles: $num_becas_registradas<br>";}
$ARRAY_BECAS=array();
/////inicial
 $ARRAY_BECAS[0]["nombre"]="sin_beca";
 $ARRAY_BECAS[0]["tipo_aporte"]="valor";
 $ARRAY_BECAS[0]["aporte_valor"]=0;
 $ARRAY_BECAS[0]["aporte_porcentaje"]=0;
if($num_becas_registradas>0)
{
	while($B=mysql_fetch_assoc($sql_becas))
	{
		 $B_id=$B["id"];
		 $B_nombre_beca=$B["beca_nombre"];
		 $B_tipo_aporte=$B["beca_tipo_aporte"];
		 $B_aporte_valor=$B["beca_aporte_valor"];
		 $B_aporte_porcentaje=$B["beca_aporte_porcentaje"];
		 
		 $ARRAY_BECAS[$B_id]["nombre"]=$B_nombre_beca;
		 $ARRAY_BECAS[$B_id]["tipo_aporte"]=$B_tipo_aporte;
		 $ARRAY_BECAS[$B_id]["aporte_valor"]=$B_aporte_valor;
		 $ARRAY_BECAS[$B_id]["aporte_porcentaje"]=$B_aporte_porcentaje;
	}
}
else
{}
mysql_free_result($sql_becas);

 //////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("datos_beca_server.php");
$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"ACTUALIZA_CANTIDAD_BECA");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../../Edicion_carreras/libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../Edicion_carreras/CSS/tabla_2.css"/>
<!--INICIO tabla EXPANDIBLE-->
 <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js" type="text/javascript"></script>
 <script src="../../../Edicion_carreras/libreria_publica/jExpand+samples/jExpand.js"></script>
     <script type="text/javascript">  
        $(document).ready(function(){
            $("#report tr:odd").addClass("odd");
            $("#report tr:not(.odd)").hide();
            $("#report tr:first-child").show();
            
            $("#report tr.odd").click(function(){
                $(this).next("tr").toggle();
                $(this).find(".arrow").toggleClass("up");
            });
            //$("#report").jExpand();
        });
    </script> 
    
  <!--FIN tabla EXPANDIBLE-->  
<title>informe porcentajes de Morosidad</title>
<?php $xajax->printJavascript(); ?> 
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 55px;
	top: 237px;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:53px;
	z-index:2;
	left: 5%;
	top: 169px;
}
#apDiv3 {
	position:absolute;
	width:40%;
	height:47px;
	z-index:3;
	left: 30%;
	text-align: center;
}
</style>
<script language="javascript">
function CONFIRMAR()
{
	c=confirm('Seguro(a) desea Grabar estas Asignaciones...?');
	if(c)
	{document.getElementById('frm').submit();}
}
</script>
</head>
<body>
<h1 id="banner">Administrador - Asignacion de Beca V2</h1>
<div id="link"><br>
<a href="index.php" class="button">Volver a Seleccion</a><br /><br />
<a href="#" class="button_R" onclick="CONFIRMAR();">Grabar Asignaciones</a></div>
<div id="apDiv1">
<form action="graba_asignacion_beca.php" method="post" id="frm" name="frm">
<table align="left" id="report">
<thead>
    <tr>
        <th>
          N°
          <input name="id_carrera" type="hidden" id="id_carrera" value="<?php echo $id_carrera;?>" />
          <input name="carrera" type="hidden" id="carrera" value="<?php echo $carrera;?>" />
		  <input name="sede" type="hidden" id="sede" value="<?php echo $sede;?>" />
          <input name="semestre" type="hidden" id="semestre" value="<?php echo $semestre_actual;?>" />
          <input name="year" type="hidden" id="year" value="<?php echo $year_actual;?>" />
          <input name="jornada" type="hidden" id="jornada" value="<?php echo $jornada;?>" />
          <input name="grupo" type="hidden" id="grupo" value="<?php echo $grupo;?>" />
          <input name="situacion" type="hidden" id="situacion" value="<?php echo $situacion;?>" />
          <input name="niveles" type="hidden" id="niveles" value="<?php echo $niveles_post;?>" />
          <input name="ingreso" type="hidden" id="ingreso" value="<?php echo $año_ingreso;?>" />
        </th>
        <th>-</th>
        <th>Sede</th>
        <th>Carrera</th>
        <th>Jornada</th>
        <th>Rut</th>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Nivel Actual</th>
        <th>Condicion</th>
        <th>Ingreso</th>
        <th>Beca</th>
        <th>$ - %</th>
        <th>Glosa</th>
        <th></th>
    </tr>
   </thead>
   <tbody> 
<?php
	$icono_hay_asignacion='<img src="../../BAses/Images/color_rojo.png" width="26" height="24" title="Tiene Asignaciones"/>';
	$icono_no_hay_asignacion='<img src="../../BAses/Images/color_verde.png" width="24" height="24" title="No tiene Asignaciones"/>';
	$aux=0;	 
	$cons_main_1="SELECT DISTINCT(id_alumno) FROM contratos2 INNER JOIN alumno ON contratos2.id_alumno = alumno.id WHERE $condicion ORDER by alumno.apellido_P, alumno.apellido_M";
	
	//$cons_main_1="SELECT DISTINCT (id_alumno) FROM (contratos2 INNER JOIN alumno ON contratos2.id_alumno = alumno.id) INNER JOIN carrera ON alumno.id_carrera = carrera.id WHERE $condicion ORDER by alumno.apellido_P, alumno.apellido_M";
		
		$sql_main_1=mysql_query($cons_main_1)or die(" MAIN 1".mysql_error());
		$num_reg_M=mysql_num_rows($sql_main_1);
		if(DEBUG){ echo"<br><br>$cons_main_1<br>NUM.$num_reg_M<br>";}
		if($num_reg_M>0)
		{
			
			$num_alumnos_morosos=0;
			$num_alumno_al_dia=0;
			while($DID=mysql_fetch_row($sql_main_1))
			{
				$id_alumno=$DID[0];
				
					if($verificar_contrato)
						{
							$cons="SELECT alumno.*, contratos2.id as id_contrato, contratos2.semestre, contratos2.ano, contratos2.vigencia, contratos2.condicion, contratos2.nivel_alumno FROM alumno INNER JOIN contratos2 ON alumno.id = contratos2.id_alumno WHERE contratos2.id_alumno='$id_alumno' AND contratos2.condicion IN('ok', 'old') ORDER by id_contrato DESC LIMIT 1";
						}
						else
						{$cons="SELECT * FROM alumno WHERE $condicion ORDER by apellido_P";}	
						if(DEBUG)
						{echo"<br><br>--> $cons <br><br>";}
						
						$sql=mysql_query($cons)or die(mysql_error());
						$num_reg=mysql_num_rows($sql);
							
						if($num_reg>0)
						{
							///////////////////////
							while($A=mysql_fetch_assoc($sql))
							{
								$alumno_vigente="";
								$id_alumno=$A["id"];
								$rut=$A["rut"];
								$nombre=$A["nombre"];
								$apellido=$A["apellido"];
								$year_ingreso=$A["ingreso"];
								$carrera_alumno=$A["carrera"];
								/////------------ACTUALIZACION----------------/////
								$apellido_P=$A["apellido_P"];
								$apellido_M=$A["apellido_M"];
								$apellido_aux=$apellido_P." ".$apellido_M;
								$nivel_alumno=$A["nivel"];
								$grupo_curso=$A["grupo"];
								$jornada=$A["jornada"];
								/////////////////////------------Datos del Contrato------------/////////////
								$id_contrato=$A["id_contrato"];
								$semestre_contrato=$A["semestre"];
								$year_contrato=$A["ano"];
								$vigencia=$A["vigencia"];
								$condicion_contrato=$A["condicion"];
								$nivel_alumno_realiza_contrato=$A["nivel_alumno"];
								$sede_alumno=$A["sede"];
								/////////////////////////------------------------------/////////////////////
								if($apellido_aux==" ")
								{$apellido_label=$apellido;}
								else
								{$apellido_label=$apellido_aux;}
								//////----------------------------//////
								$situacion=$A["situacion"];
								if($verificar_contrato)
								{
									switch($vigencia)
									{
										case"semestral":
											if(($semestre_contrato==$semestre_actual)and($year_contrato==$year_actual))
											{ $alumno_vigente=true;}
											else
											{ $alumno_vigente=false;}
											break;
										case"anual":
											if($year_contrato==$year_actual)
											{ $alumno_vigente=true;}
											else
											{ $alumno_vigente=false;}
											break;	
									}
								}
								else
								{  $alumno_vigente=true; if(DEBUG){ echo"Sin Registro de Contrato <br>";}}	
								
								if($no_mostrar_retirados)
								{
									if(($condicion_contrato=="OK")or($condicion_contrato=="OLD")or($condicion_contrato=="old")or($condicion_contrato=="ok"))
									{ $contrato_mostrar=true;}
									else
									{ $contrato_mostrar=false;}
								}
								else
								{ $contrato_mostrar=true;} 
								///////////////////////////////////////////////////////////////////////
							

								
								if(DEBUG)
								{ 
									echo"$aux - $id_alumno - $rut - $nombre - $apellido_label - $situacion - $nivel_alumno ($nivel_alumno_realiza_contrato)- $grupo_curso - $sede_alumno - $jornada - $year_ingreso | $id_contrato - $semestre_contrato - $year_contrato - $vigencia [$condicion_contrato] - mostrar=";
									if($alumno_vigente)
									{ echo"<strong>OK</strong><br>";}
									else{  echo"<strong>NO</strong><br>";}
									
								}
								///------------------------------------------------------------///
								if(($alumno_vigente)and($contrato_mostrar))
								{
									///////////////////////////////////////////
									////buscar asignaciones previas de alumno
									$cons_2="SELECT * FROM beca_asignaciones WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND semestre='$semestre_contrato' AND year='$year_contrato'";
									$sql_2=mysql_query($cons_2)or die("asignaciones ".mysql_error());
									$ARRAY_ASIGNACIONES=array();
									$num_asignaciones=mysql_num_rows($sql_2);
									if($num_asignaciones>0)
									{
										$icono=$icono_hay_asignacion;
										while($AS=mysql_fetch_assoc($sql_2))
										{
											$AS_id=$AS["id"];
											$AS_id_beca=$AS["id_beca"];
											$AS_valor=$AS["valor"];
											$AS_glosa=$AS["glosa"];
											$AS_estado=$AS["estado"];
											$AS_fecha_asignacion=$AS["fecha_asignacion"];
											
											$x_aux_nombre_beca=$ARRAY_BECAS[$AS_id_beca]["nombre"];
											
											$ARRAY_ASIGNACIONES[$AS_id]["nombre"]=$x_aux_nombre_beca;
											$ARRAY_ASIGNACIONES[$AS_id]["id_beca"]=$AS_id_beca;
											$ARRAY_ASIGNACIONES[$AS_id]["valor"]=$AS_valor;
											$ARRAY_ASIGNACIONES[$AS_id]["glosa"]=$AS_glosa;
											$ARRAY_ASIGNACIONES[$AS_id]["estado"]=$AS_estado;
											$ARRAY_ASIGNACIONES[$AS_id]["fecha_asignacion"]=$AS_fecha_asignacion;
										}
									}
									else
									{
										if(DEBUG){ echo"Alumno sin asginaciones<br>";}
										$icono=$icono_no_hay_asignacion;
									}
									mysql_free_result($sql_2);
									////////////////////////////////////////////
										$aux++;
										echo'<tr>
												<td>'.$icono.'</td>
												<td>'.$aux.'</td>
												<td>'.$sede_alumno.'</td>
												<td>'.$carrera_alumno.'</td>
												<td>'.$jornada.'</td>
												<td>'.$rut.'</td>
												<td>'.ucwords(strtolower($nombre)).'</td>
												<td>'.ucwords(strtolower($apellido_label)).'</td>
												<td>'.$nivel_alumno.'</td>
												<td>'.$situacion.'</td>
												<td>'.$year_ingreso.'</td>
												<td>
												<select name="id_beca['.$id_alumno.'] id="beca_'.$aux.'" onchange="xajax_ACTUALIZA_CANTIDAD_BECA(this.value, '.$aux.'); return false;">';
												$primer_valor=true;
												foreach($ARRAY_BECAS as $id_beca =>$array_1)
												{
													$aux_nombre=$array_1["nombre"];
													$aux_tipo_aporte=$array_1["tipo_aporte"];
													$aux_aporte_valor=$array_1["aporte_valor"];
													$aux_aporte_porcentaje=$array_1["aporte_porcentaje"];
													///////////////////////////////////////////////////////
													if($aux_tipo_aporte=="valor")
													{ 
														
														$aux_tipo_aporte_label="$";
														if($primer_valor)
														{
															$primer_valor=false;
															$aux_primer_valor_porcentaje=$aux_aporte_valor;
														}
													}
													else{ 
														
														 $aux_tipo_aporte_label="%";
															 if($primer_valor)
																{
																	$primer_valor=false;
																	$aux_primer_valor_porcentaje=$aux_aporte_porcentaje;
																}
														}
													
													echo'<option value="'.$id_beca.'">'.$aux_nombre.'['.$aux_tipo_aporte_label.']</option>';
												}
											echo'</select>
												</td>
												<td><input name="beca_valor_porcentaje['.$id_alumno.']" type="text" value="'.$aux_primer_valor_porcentaje.'" size="7" id="beca_valor_porcentaje_'.$aux.'"/></td>
												<td><input name="beca_glosa['.$id_alumno.']" id="beca_glosa_'.$aux.'" type="text" /></td>
												 <td><div class="arrow"></div></td>
												 </tr>
												  <tr>
												<td colspan="14">
													<h4>Asignaciones Realizadas</h4>
													<ul>';
											if($num_asignaciones>0)		
											{
												$contador=0;
												foreach($ARRAY_ASIGNACIONES as $id_asignacion => $array_2)
												{
													$contador++;
													$aux_nombre_beca_asignada=$array_2["nombre"];
													$aux_valor_beca_asignada=$array_2["valor"];
													$aux_glosa_beca_asignada=$array_2["glosa"];
													$aux_id_beca_asignada=$array_2["id_beca"];
													$aux_estado_beca_asignada=$array_2["estado"];
													$aux_fecha_asignacion_beca_asignada=$array_2["fecha_asignacion"];
													
													switch($aux_estado_beca_asignada)
													{
														case"asignada":
															$url_del_asignacion="#";
															$title_asignacion="No se puede Eliminar";
															break;
														case"por_asignar":
															$url_del_asignacion="borrar_asignacion/borra_asignacion.php?sede=".base64_encode($sede)."&id_carrera=".base64_encode($id_carrera)."&carrera=".base64_encode($carrera)."&year_ingreso=".base64_encode($año_ingreso)."&situacion=".base64_encode($situacion)."&jornada=".base64_encode($jornada)."&grupo=".base64_encode($grupo)."&niveles=".base64_encode($niveles_post)."&id_asignacion=".base64_encode($id_asignacion)."&semestre=".base64_encode($semestre_actual)."&year=".base64_encode($year_actual)."&id_beca=".base64_encode($id_beca)."&id_alumno=".base64_encode($id_alumno);
															$title_asignacion="Eliminar";	
															break;
													}
													
													echo'<li>'.$contador.' COD:['.$id_asignacion.'] '.$aux_nombre_beca_asignada.' '.$aux_valor_beca_asignada.' <a href="#" title="'.$aux_fecha_asignacion_beca_asignada.'">'.$aux_estado_beca_asignada.'</a> <a href="'.$url_del_asignacion.'" title="'.$title_asignacion.'"><img src="../../BAses/Images/b_drop.png" width="16" height="16" alt="X" /></a></li>';
												}
											}
											else
											{
												echo"<li>Sin Asignacion...</li>";
											}
											echo'</ul>   
												</td>
											</tr>';
								}
								///------------------------------------------------------------///
								
							}
						}
			
			}
		}
		else
		{
			echo'<tr><td colspan="13">Sin registros</td></tr>';	
		}
		//fin documento
	mysql_free_result($sql_main_1);
	mysql_close($conexion);
/////////////////////////////////////////////
?>
</tbody>
</table>
</form>
</div>
<div id="apDiv3"><strong><?php echo $msj;?></strong></div>
<br />
</body>
</html>