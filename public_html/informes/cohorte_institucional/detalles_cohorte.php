<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", true);
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>detalle cohorte</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:76px;
	z-index:1;
	left: 5%;
	top: 66px;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Detalle Cohorte Institucional</h1>
<div id="apDiv1">

<?php
$continuar=false;
$ARRAY_DATOS_ALUMNO=array();
$contador_alumnos=0;
$msj="";

if($_GET)
{
	$semestre=$_GET["semestre"];
	$tipo=$_GET["tipo"];
	$year_consulta=$_GET["year_consulta"];
	$y=$_GET["year"];
	$sede_consulta=$_GET["sede"];
	$sexo=$_GET["sexo"];
	$id_carrera=$_GET["id_carrera"];
	$jornada_consulta=$_GET["jornada"];
	$continuar=true;
	
	if(DEBUG){ echo"<br><br><bbr>________________________________<br>Alumnos Buscados<br>tipo: $tipo<br>year_consulta: $year_consulta<br> year_buscado: $y<br>sede consulta: $sede_consulta<br>sexo: $sexo<br>carrera: $id_carrera<br>_____________________________________________<br>";}
	$msj="[$tipo] year ingreso: $year_consulta year seguimiento: $y <br>Sede: $sede_consulta - carrera: $id_carrera"."Jornada: ".$jornada_consulta."_".NOMBRE_CARRERA($id_carrera)." sexo: $sexo";
}
?>
<table align="center" width="100%">
<thead>
	<tr>
    	<th colspan="6">Detalle de Alumnos <?php echo  $msj;?></th>
    </tr>
	<tr>
    	<th>N</th>
        <th>id_alumno</th>
        <th>Rut</th>
        <th>Nombre</th>
        <th>Carrera</th>
        <th>Condicion</th>
    </tr>
</thead>
<tbody>
<?php
if($continuar)
{
	$year_contrato=$year_consulta;
	if($sede_consulta!="0"){$condicion_sede="AND contratos2.sede='$sede_consulta'";}
	else{ $condicion_sede="";}
	
	if($jornada_consulta!="0"){$condicion_jornada="AND alumno.jornada='$jornada_consulta'";}
	else{ $condicion_jornada="";}
	$aux_G=1;
	
	
	//lleno array inicial de universo de alumnos
	$cons_MAIN="SELECT DISTINCT(id_alumno) FROM contratos2 INNER JOIN alumno ON contratos2.id_alumno=alumno.id WHERE contratos2.ano='$year_contrato' AND alumno.ingreso='$year_consulta' $condicion_sede $condicion_jornada";
	if(DEBUG){ echo"Consulta GLobal: $cons_MAIN<br>";}
	$sqli_MAIN=$conexion_mysqli->query($cons_MAIN)or die($conexion_mysqli->error);
	$num_registros=$sqli_MAIN->num_rows;
	
	if($num_registros>0)
	{
		$aux=0;
		while($CA=$sqli_MAIN->fetch_row())
		{
			$aux++;
			$id_alumno=$CA[0];
			if(DEBUG){ echo"<br><strong>$aux -> id_alumno:$id_alumno </strong><br>";}
			
			$cons_1="SELECT alumno.sexo, contratos2.id, contratos2.id_carrera, contratos2.sede FROM alumno INNER JOIN contratos2 ON alumno.id=contratos2.id_alumno WHERE alumno.id='$id_alumno' AND contratos2.ano='$year_contrato' $condicion_sede $condicion_jornada ORDER by contratos2.id DESC LIMIT 1";		
			if(DEBUG){ echo"--->$cons_1<br>";}
			$sqli_1=$conexion_mysqli->query($cons_1)or die($conexion_mysqli->error);
			$DC=$sqli_1->fetch_assoc();
				$A_sexo=trim($DC["sexo"]);
				$A_sexo=str_replace(" ","",$A_sexo);
				$C_id=$DC["id"];
				$C_id_carrera=$DC["id_carrera"];
				$C_sede=$DC["sede"];
			$sqli_1->free();	
			if(DEBUG){ echo"-----> id_contrato:$C_id id_carrera_contrato: $C_id_carrera  sexo_alumno:$A_sexo sede_contrato: $C_sede<br>";}
			
			$ARRAY_GLOBAL[$sede_consulta][$C_id_carrera][$A_sexo][$id_alumno]=true;
		}
	}
	else
	{
		if(DEBUG){ echo"sin alumnos en matricula inicial este año";}
	}
	//----------------------------------------------------------------------------------------------------------//

while($year_contrato<=$year_actual)
	{
		$c=0;	
		if(DEBUG){ echo"<strong>YEAR CONTRATO: $year_contrato</strong><br>";}
		foreach($ARRAY_GLOBAL as $aux_sede => $aux_array_1)
		{
			if(DEBUG){ echo "-->Sede: $aux_sede ";}
			foreach($aux_array_1 as $aux_id_carrera => $aux_array_2)
			{
				if(DEBUG){ echo "-->id_carrera: $aux_id_carrera ";}
				foreach($aux_array_2 as $aux_sexo => $aux_array_3)
				{
					if(DEBUG){ echo "-->sexo: $aux_sexo <br>";}
					foreach($aux_array_3 as $aux_id_alumno => $utilizar_alumno)
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
									
									
									$condicion_alumno_este_year=ESTADO_ALUMNO_PERIODO($aux_id_alumno, $aux_id_carrera, $s, $year_contrato);
									
									//-------------------------------------------------------------------------------//
									///identifico reincorporados
									if(!empty($ARRAY_DATOS_ALUMNO[$aux_id_alumno]["situacion"]))
									{
										if(($ARRAY_DATOS_ALUMNO[$aux_id_alumno]["situacion"]=="NN") or($ARRAY_DATOS_ALUMNO[$aux_id_alumno]["situacion"]=="R"))
										{
											if(($condicion_alumno_este_year!=="NN")and($condicion_alumno_este_year!=="R"))
											{
												$condicion_alumno_este_year="reincorporado";
												if(DEBUG){ echo"----> Alumno reincorporado detectado...<br>";}
											}
										}
									}
									//-----------------------------------------------------------------------------//
									
									switch($condicion_alumno_este_year)
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
									if($actualizar_situacion){$ARRAY_DATOS_ALUMNO[$aux_id_alumno]["situacion"]=$condicion_alumno_este_year;}
									
									$ARRAY_GLOBAL[$sede_consulta][$aux_id_carrera][$aux_sexo][$aux_id_alumno]=$utilizar_alumno;
									$ARRAY_DATOS_ALUMNO[$aux_id_alumno]["id_carrera"]=$aux_id_carrera;
									
									$ARRAY_DATOS_ALUMNO[$aux_id_alumno][$year_contrato][$s]=$condicion_alumno_este_year;
									//---------------------------------------------------------------------------------//
									if(!isset($ARRAY_id_carrera[$aux_id_carrera])){$ARRAY_id_carrera[$aux_id_carrera]=true;}
									//sumo a condicion
									if($sumar_condicion)
									{
										if(DEBUG){ echo"Sumo a condicion<br>";}
									if(isset($ARRAY_RESULTADOS[$year_contrato][$s][$sede_consulta][$aux_id_carrera][$condicion_alumno_este_year][$aux_sexo])){ $ARRAY_RESULTADOS[$year_contrato][$s][$sede_consulta][$aux_id_carrera][$condicion_alumno_este_year][$aux_sexo]+=1;}
										else{$ARRAY_RESULTADOS[$year_contrato][$s][$sede_consulta][$aux_id_carrera][$condicion_alumno_este_year][$aux_sexo]=1;}	
									}
									if($suma_total)	
									{
										if(DEBUG){ echo"Sumo a Total sede: $sede_consulta [$s - $year_contrato] [id_carrera: $aux_id_alumno][$aux_sexo] [".$ARRAY_RESULTADOS[$year_contrato][$s][$sede_consulta][$aux_id_carrera]["TOTAL"][$aux_sexo]."] -> ";}
									//sumo a total
										if(isset($ARRAY_RESULTADOS[$year_contrato][$s][$sede_consulta][$aux_id_carrera]["TOTAL"][$aux_sexo])){ $ARRAY_RESULTADOS[$year_contrato][$s][$sede_consulta][$aux_id_carrera]["TOTAL"][$aux_sexo]+=1;}
											else{$ARRAY_RESULTADOS[$year_contrato][$s][$sede_consulta][$aux_id_carrera]["TOTAL"][$aux_sexo]=1;}	
										if(DEBUG){ echo"[".$ARRAY_RESULTADOS[$year_contrato][$s][$sede_consulta][$aux_id_carrera]["TOTAL"][$aux_sexo]."]<br>";}	
									}
								}
								else
								{
									if(DEBUG){ echo"[$s- $year_contrato]No utilizar este alumno -> Ultima condicion: ".$ARRAY_DATOS_ALUMNO[$aux_id_alumno]["situacion"]."<br>";}
								}
							}//fin for semestre
						
					}//fin for utilizar alumno
				}
			}
		}
		$year_contrato++;
	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////
	if($contador_alumnos==0)
	{echo'<tr><td colspan="6">Sin Alumnos encontrados</td></tr>';}
}
?>
</tbody>
</table>
</div>
</body>
</html>