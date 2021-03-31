<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("ranking_alumnos");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	set_time_limit(300);
//-----------------------------------------//	

$sede=$_POST["fsede"];
$array_carrera=$_POST["carrera"];
$array_carrera=explode("_",$array_carrera);
$id_carrera=$array_carrera[0];
$carrera=$array_carrera[1];
$jornada=$_POST["jornada"];
$situacion="V";
$grupo=$_POST["grupo"];
$nivel=$_POST["nivel"];

$semestre_actual=$_POST["semestre_vigencia_contrato"];
$year_actual=$_POST["year_vigencia_contrato"];

//*-------------------------------*//
$verificar_contrato=true;
$no_mostrar_retirados=false;
//*-------------------------------*//
if(DEBUG){ var_export($_POST);}

if($sede=="")
{$sede="Talca";}
$condicion=" alumno.sede='$sede' AND contratos2.condicion<>'inactivo'";

if($id_carrera>0)
{ $condicion.=" AND alumno.id_carrera='$id_carrera'";}
if($jornada!="T")
{$condicion.=" AND alumno.jornada='$jornada'";}
if($situacion!="A")
{$condicion.=" AND alumno.situacion IN('$situacion','M')";}
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
	}
}
else{ $niveles="'sin nivel'";}
$condicion.="AND alumno.nivel IN($niveles)";
///////////////////////////
include("../../../funciones/conexion_v2.php");
///////////////////////////////////
						
		$titulo="Listado Alumnos $semestre_actual Semestre - $year_actual";
		$descripcion=$carrera."$sede - Jornada $jornada";
		$descripcion_more="Nivel ".str_replace("'","",$niveles)." - Grupo $grupo";
		$zoom=75;
		$msj_sin_reg="No hay resultados en esta Busqueda";
		
		/////Registro ingreso///
			 include("../../../funciones/VX.php");
			 $evento="Ve Informe(Ranking Alumno)->".$carrera."-".$sede."-".$jornada."-".$situacion;
			 REGISTRA_EVENTO($evento);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Administrador -ranking alumno</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:90%;
	height:122px;
	z-index:1;
	left: 5%;
	top: 229px;
}
#apDiv1 {
	position:absolute;
	width:90%;
	height:74px;
	z-index:2;
	left: 5%;
	top: 105px;
}

-->
</style>
</head>

<body>
<h1 id="banner">Administrador - Ranking Alumnos</h1>
<div id="link"><br>
<a href="index.php" class="button">Volver al Seleccion </a>
  </div>
<div id="Layer1">
<?php echo "$titulo<br>$descripcion <br>$descripcion_more<br>";?>
<table width="90%" border="1" align="left">
<thead>
	<tr>
    	<th colspan="10">Lista Alumnos</th>
    </tr>
    <tr>
    <td>N°</td>
    <td>Carrera</td>
    <td>Jornada</td>
    <td>Rut</td>
    <td>Nombre</td>
    <td>Apellido</td>
    <td>Nivel Actual</td>
    <td>Estado</td>
    <td>Ingreso</td>
    <td>Promedio</td>
</tr>
</thead>
<tbody>
<?php												
	$aux=0;	 
	$cons_main_1="SELECT DISTINCT(id_alumno) FROM contratos2 INNER JOIN alumno ON contratos2.id_alumno = alumno.id WHERE $condicion ORDER by alumno.apellido_P, alumno.apellido_M";
		
		$sql_main_1=mysql_query($cons_main_1)or die("MAIN 1".mysql_error());
		$num_reg_M=mysql_num_rows($sql_main_1);
		if(DEBUG){ echo"<br><br>$cons_main_1<br><strong>NUM.$num_reg_M</strong><br>";}
		if($num_reg_M>0)
		{
			
			while($DID=mysql_fetch_row($sql_main_1))
			{
				$id_alumno=$DID[0];
				
					if($verificar_contrato)
						{
							$cons="SELECT alumno.*, contratos2.id as id_contrato, contratos2.semestre, contratos2.ano, contratos2.vigencia, contratos2.condicion, contratos2.nivel_alumno, contratos2.beca_nuevo_milenio, contratos2.aporte_beca_nuevo_milenio FROM alumno INNER JOIN contratos2 ON alumno.id = contratos2.id_alumno WHERE contratos2.id_alumno='$id_alumno' AND contratos2.ano='$year_actual' AND  contratos2.condicion<>'inactivo' ORDER by id_contrato DESC LIMIT 1";
						}
						else
						{$cons="SELECT * FROM alumno WHERE $condicion ORDER by apellido_P";}
						
						if(DEBUG){echo"<br><br>--> $cons <br><br>";}
						
						$sql=mysql_query($cons)or die(mysql_error());
						$num_reg=mysql_num_rows($sql);
							
						if($num_reg>0)
						{
							if(DEBUG){ echo"|---> contratos encontrados<br>";}
							///////////////////////
							while($A=mysql_fetch_assoc($sql))
							{
								$id_alumno=$A["id"];
								$rut=$A["rut"];
								$nombre=$A["nombre"];
								$apellido=$A["apellido"];
								$year_ingreso=$A["ingreso"];
								$carrera_alumno=$A["carrera"];
								$id_carrera_alumno=$A["id_carrera"];
								/////------------ACTUALIZACION----------------/////
								$apellido_P=$A["apellido_P"];
								$apellido_M=$A["apellido_M"];
								$apellido_aux=$apellido_P." ".$apellido_M;
								$nivel_alumno=$A["nivel"];
								$grupo_curso=$A["grupo"];
								$jornada=$A["jornada"];
								$A_direccion=$A["direccion"];
								$A_ciudad=$A["ciudad"];
								$A_telefono=$A["fono"];
								$A_email=$A["email"];
								/////////////////////------------Datos del Contrato------------/////////////
								$id_contrato=$A["id_contrato"];
								$semestre_contrato=$A["semestre"];
								$year_contrato=$A["ano"];
								$vigencia=$A["vigencia"];
								$condicion_contrato=strtolower($A["condicion"]);
								$nivel_alumno_realiza_contrato=$A["nivel_alumno"];
								$BNM=$A["beca_nuevo_milenio"];
								$aporte_BNM=$A["aporte_beca_nuevo_milenio"];
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
								{  $alumno_vigente=true;}	
								
								
								if($no_mostrar_retirados)
								{
									if(($condicion_contrato=="ok")or($condicion_contrato=="old"))
									{ $contrato_mostrar=true;}
									else
									{ $contrato_mostrar=false;}
								}
								else
								{ $contrato_mostrar=true;} 
								
								
								if(DEBUG)
								{ 
									echo"$aux - ID ALUMNO: $id_alumno - Rut: $rut - Nombre: $nombre - Apellido: $apellido_label - Situacion: $situacion - Nivel Actual: $nivel_alumno Nivel en que realiza Contrato:($nivel_alumno_realiza_contrato)- Grupo: $grupo_curso - Jornada: $jornada - Ingreso: $year_ingreso | ID contrato: $id_contrato - Semestre: $semestre_contrato - Año: $year_contrato - Vigencia: $vigencia [$condicion_contrato] - mostrar=";
									if($alumno_vigente)
									{ echo"<strong>OK</strong><br>";}
									else{  echo"<strong>NO</strong><br>";}
								}

									if(($alumno_vigente)and($contrato_mostrar))
									{	
									
										$promedio_alumno=0;
										//////////////////////////////////////////////////////////////////
										$cons_N="SELECT * FROM notas WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera_alumno' AND nota<>'' ORDER by cod";
										$sql_N=mysql_query($cons_N)or die(mysql_error());
										$num_notas=mysql_num_rows($sql_N);
										if(DEBUG){ echo"--->$cons_N<br> Num notas: $num_notas<br>";}
										if($num_notas)
										{
											$cuenta_notas=0;
											$acumula_nota=0;
											while($NA=mysql_fetch_assoc($sql_N))
											{
												$cuenta_notas++;
												$N_ramo=$NA["ramo"];
												$N_nota=$NA["nota"];
												$acumula_nota+=$N_nota;
												
												if(DEBUG){ echo"$N_ramo -> $N_nota<br>";}
											}
											$promedio_alumno=($acumula_nota/$cuenta_notas);
										}
										else
										{ if(DEBUG){ echo"Sin Notas<br>";}}
										mysql_free_result($sql_N);
										if(DEBUG){ echo"Promedio Alumno $promedio_alumno<br>";}
										//---------------------------------------------------------------//
										if($promedio_alumno>0)
										{
											if(isset($ARRAY_PROMEDIO[$id_carrera_alumno]))
											{
												if($promedio_alumno>$ARRAY_PROMEDIO[$id_carrera_alumno])
												{ $ARRAY_PROMEDIO[$id_carrera_alumno]=$promedio_alumno; $ARRAY_ALUMNOS[$id_carrera_alumno]=$id_alumno;}
											}
											else
											{ $ARRAY_PROMEDIO[$id_carrera_alumno]=$promedio_alumno; $ARRAY_ALUMNOS[$id_carrera_alumno]=$id_alumno;}
											
										}
										///////////////////////////////////////////////////////////////////	
										$aux++;
										echo'<tr>
												<td>'.$aux.'</td>
												<td>'.$carrera_alumno.'</td>
												<td>'.$jornada.'</td>
												<td>'.$rut.'</td>
												<td>'.ucwords(strtolower($nombre)).'</td>
												<td>'.ucwords(strtolower($apellido_label)).'</td>
												<td>'.$nivel_alumno.'</td>
												<td>'.$situacion.'</td>
												<td>'.$year_ingreso.'</td>
												<td>'.number_format($promedio_alumno,1,",",".").'</td>
											 </tr>';			
									}
								
							}
						}
						else
						{if(DEBUG){ echo"Sin Contratos encontrados<br>";}}
			}
		}
		else
		{	
			echo'<tr>
					<td>'.$msj_sin_reg.'</td>
				</tr>';
		}
		
				
	mysql_free_result($sql_main_1);
?>
<tr>
        <td colspan="10"><?php echo $aux;?> Alumnos Encontrados</td>
    </tr>
    </tbody>
    </table>
</div>
<div id="apDiv1">
<table width="50%" border="1" align="left">
<thead>
<tr>
	<th colspan="4">Resumen Mejor Promedio</th>
</tr>
  <tr>
    <td>n</td>
    <td>Carrera</td>
    <td>Alumno</td>
    <td>Promedio</td>
  </tr>
  </thead>
  <tbody>
  <?php
   $aux=0;
   if(count($ARRAY_PROMEDIO)>0)
   {
  	foreach($ARRAY_PROMEDIO as $id_carrera => $promedio)
	{
		$aux++;
		$id_alumno=$ARRAY_ALUMNOS[$id_carrera];
		
		////datos carrera
		$cons_C="SELECT carrera FROM carrera WHERE id='$id_carrera' LIMIT 1";
		$sql_C=mysql_query($cons_C)or die(mysql_error());
			$DC=mysql_fetch_assoc($sql_C);
			$aux_carrera=$DC["carrera"];
		mysql_free_result($sql_C);	
		/////////////////////////////////
		//datos alumno
		$cons_A="SELECT * FROM alumno WHERE id='$id_alumno' LIMIT 1";
		$sql_A=mysql_query($cons_A)or die(mysql_error());
			$DA=mysql_fetch_assoc($sql_A);
			$aux_alumno=$DA["nombre"]." ".$DA["apellido_P"]." ".$DA["apellido_M"];
		mysql_free_result($sql_A);	
		echo'<tr>
				<td>'.$aux.'</td>
				<td>'.$id_carrera.' '.$aux_carrera.'</td>
				<td>'.$id_alumno.' '.$aux_alumno.'</td>
				<td>'.number_format($promedio,1,",",".").'</td>
			</tr>';
	}
   }
   else
   { echo'<tr><td colspan="4">Sin ALumnos</td></tr>';}
	mysql_close($conexion);
  ?>
  </tbody>
</table></div>
</body>
</html>   
        