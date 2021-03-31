<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="inspeccion";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
//-----------------------------------------//	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Morosidad Alumnos Matriculados</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:137px;
	z-index:1;
	left: 5%;
	top: 197px;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:40px;
	z-index:2;
	left: 5%;
	top: 100px;
	text-align: center;
	font-size: 18px;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Informe morosos</h1>
<div id="link"><br />
<a href="#" onclick="javascript:window.close();" class="button">
Cerrar</a></div>
<div id="apDiv1">
  <table width="60%" border="1" align="left">
  <thead>
  <tr>
  	<th colspan="11">Alumno</th>
  </tr>
  </thead>
  <tbody>
<?php
//var_dump($_POST);
//////////////////////////
define("DEBUG",false);

$sede=$_POST["fsede"];
$carrera=$_POST["carrera"];
$año_ingreso=$_POST["ano_ingreso"];
$jornada=$_POST["jornada"];
$situacion=$_POST["estado"];
$grupo=$_POST["grupo"];
$nivel=$_POST["nivel"];

$semestre_actual=$_POST["semestre_vigencia_contrato"];
$year_actual=$_POST["year_vigencia_contrato"];

$verificar_contrato=true;
$no_mostrar_retirados=false;
////////////////////////////---> Datos actuales de Semestre y año
/*$year_actual=date("Y");
$mes_actual=date("m");
if($mes_actual<8)/////porque los contratos semestrales vencen en agosto
{ $semestre_actual=1;}
else
{ $semestre_actual=2;}
*/
/////////////////////////////
if(DEBUG){ var_export($_POST);}

if($sede=="")
{$sede="Talca";}
$condicion=" alumno.sede='$sede' AND contratos2.condicion<>'inactivo'";


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

if($carrera!="todas"){ $condicion.="AND alumno.carrera='$carrera'";}


$inicio_ciclio=true;
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

///////////////////////////
//alumno.grupo='A' AND alumno.situacion='V' AND alumno.nivel=1 AND alumno.sede='Talca'
include("../../../funciones/conexion.php");
///////////////////////////////////
					
 							/////Registro ingreso///
								 include("../../../funciones/VX.php");
								 $evento="Ve Informe(alumnosXcurso)->".$carrera."-".$año_ingreso."-".$sede."-".$jornada."-".$situacion;
								 REGISTRA_EVENTO($evento);
								
									echo'<tr>
									<td>N</td>	
									<td>Carrera</td>
									<td>Nivel</td>
									<td>jornada</td>
									<td>Rut</td>
									<td>Nombre</td>
									<td>Apellido</td>
									<td>Estado</td>
									<td>Ingreso</td>
									<td>Situacion Financiera</td>
									<td>Becas</td>
									</tr>';
									
							 
								$aux=0;	
								$cantidad_morosos=0; 
								$cantidad_vigentes=0;
								
								$DATOS_MOROSOS=array();
								$DATOS_NO_MOROSOS=array();
								
	$cons_main_1="SELECT DISTINCT(id_alumno) FROM contratos2 INNER JOIN alumno ON contratos2.id_alumno = alumno.id WHERE $condicion ORDER by alumno.carrera,alumno.nivel, alumno.jornada, alumno.apellido_P, alumno.apellido_M";
		
		$sql_main_1=mysql_query($cons_main_1)or die("MAIN 1".mysql_error());
		$num_reg_M=mysql_num_rows($sql_main_1);
		if(DEBUG){ echo"<br><br>$cons_main_1<br>NUM.$num_reg_M<br>";}
		if($num_reg_M>0)
		{
			
			while($DID=mysql_fetch_row($sql_main_1))
			{
				$id_alumno=$DID[0];
				
					if($verificar_contrato)
						{
							$cons="SELECT alumno.*, contratos2.id as id_contrato, contratos2.semestre, contratos2.ano, contratos2.vigencia, contratos2.condicion, contratos2.beca_nuevo_milenio, contratos2.beca_excelencia FROM alumno INNER JOIN contratos2 ON alumno.id = contratos2.id_alumno WHERE contratos2.id_alumno='$id_alumno' AND contratos2.ano='$year_actual' AND contratos2.condicion<>'inactivo' ORDER by apellido_P, apellido_M";
						
						}
						else
						{
							$cons="SELECT * FROM alumno WHERE $condicion ORDER by apellido_P";
						}	
						if(DEBUG)
						{echo"<br><br>--> $cons <br><br>";}
						
						$sql=mysql_query($cons)or die(mysql_error());
						$num_reg=mysql_num_rows($sql);

						if($num_reg>0)
						{
							///////////////////////
							while($A=mysql_fetch_assoc($sql))
							{
								$id_alumno=$A["id"];
								$rut=$A["rut"];
								$nombre=utf8_decode($A["nombre"]);
								$apellido=$A["apellido"];
								$year_ingreso=$A["ingreso"];
								/////------------ACTUALIZACION----------------/////
								$apellido_P=$A["apellido_P"];
								$apellido_M=$A["apellido_M"];
								$apellido_aux=$apellido_P." ".$apellido_M;
								$nivel_alumno=$A["nivel"];
								$grupo_curso=$A["grupo"];
								$jornada_alumno=$A["jornada"];
								$situacion_financiera=$A["situacion_financiera"];
								$carrera_alumno=$A["carrera"];
								
								/////////////////////------------Datos del Contrato------------/////////////
								$id_contrato=$A["id_contrato"];
								$semestre_contrato=$A["semestre"];
								$year_contrato=$A["ano"];
								$vigencia=$A["vigencia"];
								$condicion_contrato=$A["condicion"];
								
								$beca_nuevo_milenio=$A["beca_nuevo_milenio"];
								$beca_excelencia_tec=$A["beca_excelencia"];
								
								$msj_beca="BNM: $beca_nuevo_milenio - Beca Excelencia: $beca_excelencia_tec";
								
								/////////////////////////------------------------------/////////////////////
								if($apellido_aux==" ")
								{
									$apellido_label=$apellido;
								}
								else
								{
									$apellido_label=$apellido_aux;
								}
								
								$apellido_label=utf8_decode($apellido_label);
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
								{  $alumno_vigente=true;}	
								//$alumno_vigente=true;//hack para no condicionar por semestre ni año solo condicon "ok" del contrato						
								
								if($no_mostrar_retirados)
								{
									if(($condicion_contrato=="OK")or($condicion_contrato=="OLD")or($condicion_contrato=="old")or($condicion_contrato=="ok"))
									{ $contrato_mostrar=true;}
									else
									{ $contrato_mostrar=false;}
								}
								else
								{ $contrato_mostrar=true;} 
								
								
								if(DEBUG)
								{ 
									echo"$aux - $id_alumno - $rut - $nombre - $apellido_label - $situacion - $nivel_alumno - $grupo_curso - $jornada - $year_ingreso | $id_contrato - $semestre_contrato - $year_contrato - $vigencia [$condicion_contrato] - mostrar=";
									if($alumno_vigente)
									{ echo"<strong>OK</strong><br>";}
									else{  echo"<strong>NO</strong><br>";}
								}
								if(($alumno_vigente)and($contrato_mostrar))
									{
												$aux++;
											echo'<tr>
												<td>'.$aux.'</td>
												<td>'.$carrera_alumno.'</td>
												<td>'.$nivel_alumno.'</td>
												<td>'.$jornada_alumno.'</td>
												<td>'.$rut.'</td>
												<td>'.$nombre.'</td>
												<td>'.$apellido_label.'</td>
												<td>'.$situacion.'</td>
												<td>'.$year_ingreso.'</td>
												<td>'.$situacion_financiera.'</td>
												<td><a href="#" title="'.$msj_beca.'">info</a></td>
												</tr>';
												
												if((strtoupper($situacion_financiera))=="M")
												{
													$cantidad_morosos++;
													$DATOS_MOROSOS[$carrera_alumno][$nivel_alumno][$jornada_alumno]+=1;
												}
												else
												{ 
													$cantidad_vigentes++;
													$DATOS_NO_MOROSOS[$carrera_alumno][$nivel_alumno][$jornada_alumno]+=1;
												}
									}
								
								
							}
						}
			
			}
		}
		else
		{
				echo'<tr><td>Sin Registros</td></tr>';
		}
		//fin documento
	mysql_free_result($sql_main_1);
	mysql_close($conexion);
?>   
</tbody> 
  </table>
  <table width="30%" align="left">
    <thead>
  	<tr>
    	<th colspan="3">Resumen Global</th>
  	</tr>
  </thead>
  <tbody>
  	<tr>
    	<td>Cantidad Alumno Morosos</td>
        <td>Cantidad Alumno Vigentes</td>
        <td>Total</td>
    </tr>
    
    
    <tr>
    	<td><?php if($aux>0){echo "$cantidad_morosos (".number_format((($cantidad_morosos*100)/$aux),2)."%)";}?></td>
        <td><?php if($aux>0){echo "$cantidad_vigentes (".number_format((($cantidad_vigentes*100)/$aux),2)."%)";}?></td>
        <td><?php if($aux>0){echo $aux;}?></td>
    </tr>
  </tbody>
</table><br /><br />
 <table width="30%" align="left">
    <thead>
  	<tr>
    	<th colspan="7">Resumen X Curso</th>
  	</tr>
  </thead>
  <tbody>
  	<tr>
    	<td>Carrera</td>
        <td>Nivel</td>
        <td>Jornada</td>
        <td>Morosos</td>
        <td>% del Curso</td>
        <td>% del Total </td>
    </tr>
    <?php
	echo"<br><br>";
    	if((isset($DATOS_MOROSOS))and($aux>0))
		{
			
			$aux_V=0;
			$aux_total_X_curso=0;
			foreach($DATOS_MOROSOS as $carrerax => $array_datos_1)
			{
				if(DEBUG){echo"$carrerax -> $array_datos_1<br>";}
				foreach($array_datos_1 as $nivelx =>$valoresx)
				{
					if(DEBUG){echo"$nivelx -> $valor<br>";}
					foreach($valoresx as $jornadax => $cantidadx)
					{
						if(DEBUG){echo" |$jornadax -> $cantidadx| ";}
						echo'<tr>
								<td>'.$carrerax.'</td>
								<td>'.$nivelx.'</td>
								<td>'.$jornadax.'</td>';
								
								$aux_V=$DATOS_NO_MOROSOS[$carrerax][$nivelx][$jornadax];
								if(empty($aux_V)){ $aux_V=0;}
								$aux_total_X_curso=($aux_V+$cantidadx);
								
						   echo'
						   <td>'.$cantidadx.'/'.$aux_total_X_curso.'</td>
						   <td>'.number_format((($cantidadx*100)/$aux_total_X_curso),2).'</td>
						   <td>'.number_format((($cantidadx*100)/$aux),2).'</td>
							</tr>';
					}
				}
			}
		}
		else
		{
			echo'<tr><td colspan="7">Sin Registros...</td></tr>';
		}
	?>
    </tbody>
    </table>
</div>
<div id="apDiv2">Alumno Carrera: <?php echo $carrera;?><br> 
  Nivel(<?php echo $niveles;?>) - Jornada:<?php echo $jornada;?> - Grupo:<?php echo $grupo;?><br />
  Sede: <?php echo $sede;?> <br />Vigencia: <?php echo "$semestre_actual Semestre - $year_actual";?> 
</div>
</body>
</html>