<?php 
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Matriculas_generadas_X_rango_F_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
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
<?php include("../../../funciones/codificacion.php");?>
<title>informe Contratos</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
<!--
#link {
	text-align: right;
	padding-right: 10px;
}
#apDiv1 {
	position:absolute;
	width:96%;
	height:115px;
	z-index:1;
	left: 2%;
	top: 104px;
}
-->
</style>
<script src="../../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
<link href="../../SpryAssets/SpryCollapsiblePanel.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo5 {font-size: 12px}
#apDiv2 {
	position:absolute;
	width:13%;
	height:42px;
	z-index:2;
	left: 85%;
	top: 81px;
}
a:link {
	color: #006699;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #006699;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #006699;
}
-->
</style>
</head>
<?php
include("../../../funciones/funcion.php");
	$fecha_ini=$_POST["fecha_ini"];
	$fecha_fin=$_POST["fecha_fin"];
	
	$fecha_ini.=" 00:00:00";
	$fecha_fin.=" 23:59:59";
	
	$sede=$_POST["fsede"];
	$nivel=$_POST["nivel"];
	$year_ingreso=$_POST["year_ingreso"];
	$msj="Matriculas Generadas entre (".$fecha_ini." - ".$fecha_fin.")<br/>Sede: $sede<br />Alumnos de Nivel: $nivel<br />Ańo Ingreso: $year_ingreso";
	
	$envio_get="fecha_ini=".base64_encode($fecha_ini)."&fecha_fin=".base64_encode($fecha_fin)."&sede=".base64_encode($sede)."&nivel=".base64_encode($nivel)."&year_ingreso=".base64_encode($year_ingreso);
?>
<body>
<h1 id="banner">Administrador - Informe Financiero</h1>
<div id="link"><br />
<a href="index.php" class="button">Volver a la Selecci&oacute;n</a></div>
<p>
  <?php
if($_POST)
{
	$ALUMNOS_DIURNO=array();
	$ALUMNOS_VESPERTINO=array();
	$ALUMNOS_RETIRADOS=array();
	require("../../../funciones/conexion_v2.php");
	//----------------------------------------//
	require("../../../funciones/VX.php");
	$evento="Revisa Informe Matriculas Generadas (rango fecha)";
	REGISTRA_EVENTO($evento);
	
	///----------------lleno array de carreras-----------------//
	$cons_carreras="SELECT id, carrera FROM carrera";
	$sqlx1=mysql_query($cons_carreras)or die("carreras ".mysql_error());
	while($AC=mysql_fetch_array($sqlx1))
	{
		$carrera=$AC["carrera"];
		$id_carrera=$AC["id"];
		if($carrera!="General")
		{
			$array_carreras[$id_carrera]=0;
			$array_carreras_nombres[$id_carrera]=$carrera;
		}	
	}
	mysql_free_result($sqlx1);
	//-------------------------------------------------------///

	if(DEBUG){ var_dump($array_carreras); var_dump($array_carreras_nombres);}
	
	
	if($nivel=="Todos")
	{$condicion_nivel="";}
	elseif($nivel%2==0)
	{$condicion_nivel="AND contratos2.nivel_alumno_2='$nivel'";}
	else{$condicion_nivel="AND contratos2.nivel_alumno='$nivel'";}
	
	if($year_ingreso=="Todos")
	{
		$condicion_ingreso="";
		$year_actual=date("Y");
	}
	else
	{
		$condicion_ingreso="AND alumno.ingreso='$year_ingreso'";
		$year_actual=$year_ingreso;
	}
	
	if($sede=="todas")
	{$condicion_sede="";}
	else
	{$condicion_sede="contratos2.sede='$sede'";}
	
	$consC="SELECT contratos2.id, contratos2.id_alumno, contratos2.sede, contratos2.condicion, alumno.nivel, alumno.rut, alumno.nombre, alumno.apellido_P, alumno.carrera, alumno.jornada, alumno.ingreso FROM contratos2 INNER JOIN alumno ON contratos2.id_alumno = alumno.id WHERE $condicion_sede $condicion_nivel $condicion_ingreso AND contratos2.fecha_generacion BETWEEN '$fecha_ini' AND '$fecha_fin';";
	
	
	$sqlc=mysql_query($consC)or die(mysql_error());
	$num_contratos=mysql_num_rows($sqlc);
	if(DEBUG){echo"<br><br>--->$consC<br><br>Num contratos: $num_contratos<br>";}
	$ARRAY_ALUMNO=array();
	$contadorX=0;
	if($num_contratos>0)
	{
		//$year_actual=date("Y");
		while($DC=mysql_fetch_assoc($sqlc))
		{
			$contrato_condicion=strtoupper($DC["condicion"]);
			
			$id_contrato=$DC["id"];
			$id_alumno=$DC["id_alumno"];
			$nivel_alumno=$DC["nivel"];
			$jornada_alumno=$DC["jornada"];
			$ingreso_alumno=$DC["ingreso"];
			if(DEBUG){echo"<strong>id contrato:</strong> $id_contrato - <strong>Id Alumno:</strong> $id_alumno<br>";}
			//////////////Carrera de Alumno/////////////
				$cons_carrera="SELECT id_carrera, carrera FROM alumno WHERE id='$id_alumno' LIMIT 1";
				$sql_A=mysql_query($cons_carrera)or die("carrera alumno ".mysql_error());
				$DA=mysql_fetch_assoc($sql_A);
				$carrera_contrato=$DA["carrera"];
				$id_carrera_contrato=$DA["id_carrera"];
				if(DEBUG){echo"<br><br>contrato -> ($id_carrera_contrato)$carrera_contrato=>$contrato_condicion<br>";}
				
				
				if(in_array($id_alumno,$ARRAY_ALUMNO))
				{
					if(DEBUG){echo"Alumno Repetido<br>";}
					$alumno_repetido=true;
				}
				else
				{
					if(DEBUG){ echo"Alumno no repetido<br>";}
					$alumno_repetido=false;
					$ARRAY_ALUMNO[$contadorX]=$id_alumno;
					$contadorX++;
				}
				////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
				
				if(!$alumno_repetido)
				{
					if(($contrato_condicion=="OK")or($contrato_condicion=="OLD")or($contrato_condicion=="INACTIVO"))
					{
						switch($nivel_alumno)
						{
							case"1":
								if($jornada_alumno=="D")
								{ 
									if(isset($ALUMNOS_DIURNO[$id_carrera_contrato][$nivel_alumno]))
										{$ALUMNOS_DIURNO[$id_carrera_contrato][$nivel_alumno]+=1;}
									else{$ALUMNOS_DIURNO[$id_carrera_contrato][$nivel_alumno]=1;}
								}
								else
								{ 
									if(isset($ALUMNOS_VESPERTINO[$id_carrera_contrato][$nivel_alumno]))
										{$ALUMNOS_VESPERTINO[$id_carrera_contrato][$nivel_alumno]+=1;}
									else{$ALUMNOS_VESPERTINO[$id_carrera_contrato][$nivel_alumno]=1;}
								}
								break;
							case"2":
								if($jornada_alumno=="D")
								{ 
									if(isset($ALUMNOS_DIURNO[$id_carrera_contrato][$nivel_alumno]))
										{$ALUMNOS_DIURNO[$id_carrera_contrato][$nivel_alumno]+=1;}
									else{$ALUMNOS_DIURNO[$id_carrera_contrato][$nivel_alumno]=1;}
								}
								else
								{ 
									if(isset($ALUMNOS_VESPERTINO[$id_carrera_contrato][$nivel_alumno]))
											{$ALUMNOS_VESPERTINO[$id_carrera_contrato][$nivel_alumno]+=1;}
										else{$ALUMNOS_VESPERTINO[$id_carrera_contrato][$nivel_alumno]=1;}
								}
								break;
							case"3":
								if($jornada_alumno=="D")
								{ 
									if(isset($ALUMNOS_DIURNO[$id_carrera_contrato][$nivel_alumno]))
										{$ALUMNOS_DIURNO[$id_carrera_contrato][$nivel_alumno]+=1;}
									else{$ALUMNOS_DIURNO[$id_carrera_contrato][$nivel_alumno]=1;}
								}
								else
								{ 
									if(isset($ALUMNOS_VESPERTINO[$id_carrera_contrato][$nivel_alumno]))
										{$ALUMNOS_VESPERTINO[$id_carrera_contrato][$nivel_alumno]+=1;}
									else{$ALUMNOS_VESPERTINO[$id_carrera_contrato][$nivel_alumno]=1;}
								}
								break;
							case"4":
								if($jornada_alumno=="D")
								{ 
									if(isset($ALUMNOS_DIURNO[$id_carrera_contrato][$nivel_alumno]))
										{$ALUMNOS_DIURNO[$id_carrera_contrato][$nivel_alumno]+=1;}
									else{$ALUMNOS_DIURNO[$id_carrera_contrato][$nivel_alumno]=1;}
								}
								else
								{ 
									if(isset($ALUMNOS_VESPERTINO[$id_carrera_contrato][$nivel_alumno]))
										{$ALUMNOS_VESPERTINO[$id_carrera_contrato][$nivel_alumno]+=1;}
									else{$ALUMNOS_VESPERTINO[$id_carrera_contrato][$nivel_alumno]=1;}
								}
								break;
							case"5":
								if($jornada_alumno=="D")
								{ 
									if(isset($ALUMNOS_DIURNO[$id_carrera_contrato][$nivel_alumno]))
										{$ALUMNOS_DIURNO[$id_carrera_contrato][$nivel_alumno]+=1;}
									else{$ALUMNOS_DIURNO[$id_carrera_contrato][$nivel_alumno]=1;}
								}
								else
								{ 
									if(isset($ALUMNOS_VESPERTINO[$id_carrera_contrato][$nivel_alumno]))
										{$ALUMNOS_VESPERTINO[$id_carrera_contrato][$nivel_alumno]+=1;}
									else{$ALUMNOS_VESPERTINO[$id_carrera_contrato][$nivel_alumno]=1;}
								}
								break;				
						}
					}
					elseif($contrato_condicion=="RETIRO")
					{
						if(isset($ALUMNOS_RETIRADOS[$id_carrera_contrato]))
						{$ALUMNOS_RETIRADOS[$id_carrera_contrato]+=1;}
						else{$ALUMNOS_RETIRADOS[$id_carrera_contrato]=1;}
					}
				}
				mysql_free_result($sql_A);
			////////////-------------------/////////////
		}
		$graficar=true;
		//var_export($array_nuevos);
		
	}
	else
	{
		//sin contratos encontrados
		echo"<strong>Sin Contratos Generados en este rango de Fechas y sede o Nivel del Alumno</strong>";
		$graficar=false;
	}
	mysql_free_result($sqlc);
	mysql_close($conexion);
	
	
	if($graficar)
	{
		$aux=true;
		$max=0;
		$tabla='<table width="100%" border="1">
				<thead>
				<tr>
					<th rowspan="3"><strong>iniciales</strong></th>
					<th rowspan="3"><strong>Carrera</strong></th>
				</tr>
				<tr>
				  <th colspan="3"><strong>1</strong></th>
				  <th colspan="3"><strong>2</strong><strong></strong></th>
					<th colspan="3">3</th>
					<th colspan="3">4</th>
					<th colspan="3">5</th>
					<th rowspan="2"><a title="No esta sumado al total">Retiros</a></th>
					<th rowspan="2">Total</th>
				  </tr>
				<tr>
				  <th>Diurno</th>
				  <th>Vespertino</th>
				  <th>Total</th>
				  <th>Diurnos</th>
				  <th>Vespertino</th>
				  <th>Total</th>
				  <th>Diurnos</th>
				  <th>Vespertino</th>
				  <th>Total</th>
				  <th>Diurnos</th>
				  <th>Vespertino</th>
				  <th>Total</th>
				  <th>Diurnos</th>
				  <th>Vespertino</th>
				  <th>Total</th>
				  </tr>
				</thead>
				<tbody>';
		
		
	//para grafico
	$max_total=0;	
	$aux=true;
	$SUMA_TOTAL=0;
	
	//var_export($ALUMNOS_RETIRADOS);
	//var_export($array_carreras);
	
	//-----------------------------------------------//
	$total_1_D=0;
	$total_1_V=0;
	$total_2_D=0;
	$total_2_V=0;
	$total_3_D=0;
	$total_3_V=0;
	$total_4_D=0;
	$total_4_V=0;
	$total_5_D=0;
	$total_5_V=0;
	
	$TOTAL_1=0;
	$TOTAL_2=0;
	$TOTAL_3=0;
	$TOTAL_4=0;
	$TOTAL_5=0;
	
	$SUMA_TOTAL=0;
	$SUMA_RETIRADOS=0;
	$carrera_label="";
	$matriculas='';
	//---------------------------------------------------//
	foreach($array_carreras as $aux_id_carrera => $valor)		
	{
		$mostrar=false;
		
			$aux_carrera=$array_carreras_nombres[$aux_id_carrera];
			$desarma_carrera=explode(" ",$aux_carrera);
			$inicial="";
			foreach($desarma_carrera as $x=>$txt)
			{
				//echo"XD $x -> $txt<br>";
				$inicial.=substr($txt,0,1);
			}
			$n_label=$inicial;
		
		
		
		
		if(isset($ALUMNOS_DIURNO[$aux_id_carrera][1]))
		{$A1_nivel_D=$ALUMNOS_DIURNO[$aux_id_carrera][1];}
		else{$A1_nivel_D=0;}
		
		if(empty($A1_nivel_D)){ $A1_nivel_D=0;}
		if($A1_nivel_D>0){ $mostrar=true;}
		
		if(isset($ALUMNOS_VESPERTINO[$aux_id_carrera][1]))
		{$A1_nivel_V=$ALUMNOS_VESPERTINO[$aux_id_carrera][1];}
		else{$A1_nivel_V=0;}
		
		if(empty($A1_nivel_V)){ $A1_nivel_V=0;}
		if($A1_nivel_V>0){ $mostrar=true;}
		$A1_total=($A1_nivel_D+$A1_nivel_V);
		
		
		if(isset($ALUMNOS_DIURNO[$aux_id_carrera][2]))
		{$A2_nivel_D=$ALUMNOS_DIURNO[$aux_id_carrera][2];}
		else{$A2_nivel_D=0;}
		
		if(empty($A2_nivel_D)){ $A2_nivel_D=0;}
		if($A2_nivel_D>0){ $mostrar=true;}
		
		if(isset($ALUMNOS_VESPERTINO[$aux_id_carrera][2]))
		{$A2_nivel_V=$ALUMNOS_VESPERTINO[$aux_id_carrera][2];}
		else{$A2_nivel_V=0;}
		
		if(empty($A2_nivel_V)){ $A2_nivel_V=0;}
		if($A2_nivel_V>0){ $mostrar=true;}
		$A2_total=($A2_nivel_D+$A2_nivel_V);
		
		if(isset($ALUMNOS_DIURNO[$aux_id_carrera][3]))
		{$A3_nivel_D=$ALUMNOS_DIURNO[$aux_id_carrera][3];}
		else{$A3_nivel_D=0;}
		
		if(empty($A3_nivel_D)){ $A3_nivel_D=0;}
		if($A3_nivel_D>0){ $mostrar=true;}
		
		if(isset($ALUMNOS_VESPERTINO[$aux_id_carrera][3]))
		{$A3_nivel_V=$ALUMNOS_VESPERTINO[$aux_id_carrera][3];}
		else{$A3_nivel_V=0;}
		
		if(empty($A3_nivel_V)){ $A3_nivel_V=0;}
		if($A3_nivel_V>0){ $mostrar=true;}
		$A3_total=($A3_nivel_D+$A3_nivel_V);
		
		if(isset($ALUMNOS_DIURNO[$aux_id_carrera][4]))
		{$A4_nivel_D=$ALUMNOS_DIURNO[$aux_id_carrera][4];}
		else{$A4_nivel_D=0;}
		
		if(empty($A4_nivel_D)){ $A4_nivel_D=0;}
		if($A4_nivel_D>0){ $mostrar=true;}
		
		if(isset($ALUMNOS_VESPERTINO[$aux_id_carrera][4]))
		{$A4_nivel_V=$ALUMNOS_VESPERTINO[$aux_id_carrera][4];}
		else{$A4_nivel_V=0;}
		
		if(empty($A4_nivel_V)){ $A4_nivel_V=0;}
		if($A4_nivel_V>0){ $mostrar=true;}
		$A4_total=($A4_nivel_D+$A4_nivel_V);
		
		if(isset($ALUMNOS_DIURNO[$aux_id_carrera][5]))
		{$A5_nivel_D=$ALUMNOS_DIURNO[$aux_id_carrera][5];}
		else{$A5_nivel_D=0;}
		
		if(empty($A5_nivel_D)){ $A5_nivel_D=0;}
		if($A5_nivel_D>0){ $mostrar=true;}
		
		if(isset($ALUMNOS_VESPERTINO[$aux_id_carrera][5]))
		{$A5_nivel_V=$ALUMNOS_VESPERTINO[$aux_id_carrera][5];}
		else{$A5_nivel_V=0;}
		
		if(empty($A5_nivel_V)){ $A5_nivel_V=0;}
		if($A5_nivel_V>0){ $mostrar=true;}
		$A5_total=($A5_nivel_D+$A5_nivel_V);
		
		if(isset($ALUMNOS_RETIRADOS[$aux_id_carrera]))
		{$retirados=$ALUMNOS_RETIRADOS[$aux_id_carrera];}
		else{$retirados=0;}
		
		if(empty($retirados)){ $retirados=0;}
		if($retirados>0){ $mostrar=true;}
		$A_TOTAL=($A1_total+$A2_total+$A3_total+$A4_total+$A5_total);
		
		$total_1_D+=$A1_nivel_D;
		$total_1_V+=$A1_nivel_V;
		$total_2_D+=$A2_nivel_D;
		$total_2_V+=$A2_nivel_V;
		$total_3_D+=$A3_nivel_D;
		$total_3_V+=$A3_nivel_V;
		$total_4_D+=$A4_nivel_D;
		$total_4_V+=$A4_nivel_V;
		$total_5_D+=$A5_nivel_D;
		$total_5_V+=$A5_nivel_V;
		
		$TOTAL_1+=$A1_total;
		$TOTAL_2+=$A2_total;
		$TOTAL_3+=$A3_total;
		$TOTAL_4+=$A4_total;
		$TOTAL_5+=$A5_total;
		
		
		
		$SUMA_TOTAL+=$A_TOTAL;
		

		$fila='<tr>
				<td>'.$inicial.'</td>
				<td>'.$aux_carrera.'</td>
				<td align="center"><a href="informacion_alumnos.php?id_carrera='.$aux_id_carrera.'&nivel=1&jornada=D&fecha_ini='.base64_encode($fecha_ini).'&fecha_fin='.base64_encode($fecha_fin).'&sede='.base64_encode($sede).'&niveles_consultados='.base64_encode($nivel).'&year_ingreso='.base64_encode($year_ingreso).'&lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=510"  class="lightbox">'.$A1_nivel_D.'</a></td>
				<td align="center"><a href="informacion_alumnos.php?id_carrera='.$aux_id_carrera.'&nivel=1&jornada=V&fecha_ini='.base64_encode($fecha_ini).'&fecha_fin='.base64_encode($fecha_fin).'&sede='.base64_encode($sede).'&niveles_consultados='.base64_encode($nivel).'&year_ingreso='.base64_encode($year_ingreso).'&lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=510"  class="lightbox">'.$A1_nivel_V.'</a></td>
				<td align="center"><strong>'.$A1_total.'</strong></td>
				<td align="center"><a href="informacion_alumnos.php?id_carrera='.$aux_id_carrera.'&nivel=2&jornada=D&fecha_ini='.base64_encode($fecha_ini).'&fecha_fin='.base64_encode($fecha_fin).'&sede='.base64_encode($sede).'&niveles_consultados='.base64_encode($nivel).'&year_ingreso='.base64_encode($year_ingreso).'&lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=510"  class="lightbox">'.$A2_nivel_D.'</a></td>
				<td align="center"><a href="informacion_alumnos.php?id_carrera='.$aux_id_carrera.'&nivel=2&jornada=V&fecha_ini='.base64_encode($fecha_ini).'&fecha_fin='.base64_encode($fecha_fin).'&sede='.base64_encode($sede).'&niveles_consultados='.base64_encode($nivel).'&year_ingreso='.base64_encode($year_ingreso).'&lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=510"  class="lightbox">'.$A2_nivel_V.'</a></td>
				<td align="center"><strong>'.$A2_total.'</strong></td>
				<td align="center"><a href="informacion_alumnos.php?id_carrera='.$aux_id_carrera.'&nivel=3&jornada=D&fecha_ini='.base64_encode($fecha_ini).'&fecha_fin='.base64_encode($fecha_fin).'&sede='.base64_encode($sede).'&niveles_consultados='.base64_encode($nivel).'&year_ingreso='.base64_encode($year_ingreso).'&lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=510"  class="lightbox">'.$A3_nivel_D.'</a></td>
				<td align="center"><a href="informacion_alumnos.php?id_carrera='.$aux_id_carrera.'&nivel=3&jornada=V&fecha_ini='.base64_encode($fecha_ini).'&fecha_fin='.base64_encode($fecha_fin).'&sede='.base64_encode($sede).'&niveles_consultados='.base64_encode($nivel).'&year_ingreso='.base64_encode($year_ingreso).'&lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=510"  class="lightbox">'.$A3_nivel_V.'</a></td>
				<td align="center"><strong>'.$A3_total.'</strong></td>
				<td align="center"><a href="informacion_alumnos.php?id_carrera='.$aux_id_carrera.'&nivel=4&jornada=D&fecha_ini='.base64_encode($fecha_ini).'&fecha_fin='.base64_encode($fecha_fin).'&sede='.base64_encode($sede).'&niveles_consultados='.base64_encode($nivel).'&year_ingreso='.base64_encode($year_ingreso).'&lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=510"  class="lightbox">'.$A4_nivel_D.'</a></td>
				<td align="center"><a href="informacion_alumnos.php?id_carrera='.$aux_id_carrera.'&nivel=4&jornada=V&fecha_ini='.base64_encode($fecha_ini).'&fecha_fin='.base64_encode($fecha_fin).'&sede='.base64_encode($sede).'&niveles_consultados='.base64_encode($nivel).'&year_ingreso='.base64_encode($year_ingreso).'&lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=510"  class="lightbox">'.$A4_nivel_V.'</a></td>
				<td align="center"><strong>'.$A4_total.'</strong></td>
				<td align="center"><a href="informacion_alumnos.php?id_carrera='.$aux_id_carrera.'&nivel=5&jornada=D&fecha_ini='.base64_encode($fecha_ini).'&fecha_fin='.base64_encode($fecha_fin).'&sede='.base64_encode($sede).'&niveles_consultados='.base64_encode($nivel).'&year_ingreso='.base64_encode($year_ingreso).'&lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=510"  class="lightbox">'.$A5_nivel_D.'</a></td>
				<td align="center"><a href="informacion_alumnos.php?id_carrera='.$aux_id_carrera.'&nivel=5&jornada=V&fecha_ini='.base64_encode($fecha_ini).'&fecha_fin='.base64_encode($fecha_fin).'&sede='.base64_encode($sede).'&niveles_consultados='.base64_encode($nivel).'&year_ingreso='.base64_encode($year_ingreso).'&lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=510"  class="lightbox">'.$A5_nivel_V.'</a></td>
				<td align="center"><strong>'.$A5_total.'</strong></td>
				<td align="center">'.$retirados.'</td>
				<td align="center"><strong>'.$A_TOTAL.'</strong></td>
				</tr>';
		if($mostrar)
		{
			$SUMA_RETIRADOS+=$retirados;
			$tabla.=$fila;
			//para grafico
			if($A_TOTAL>$max_total)
			{ $max_total=$A_TOTAL;}
			if($aux)
				{
					$carrera_label.="|$n_label|";
					$matriculas.="$A_TOTAL";
					$aux=false;
				}
				else
				{
					$matriculas.=",$A_TOTAL";
					$carrera_label.="$n_label|";
				}
		//----------------------------------------------//
		}
		
	}
		$tabla.='
		<tr>
		<td colspan="2"><strong>TOTALES</strong></td>
		<td align="center"><strong>'.$total_1_D.'</strong></td>
		<td align="center"><strong>'.$total_1_V.'</strong></td>
		<td align="center"><strong>'.$TOTAL_1.'</strong></td>
		<td align="center"><strong>'.$total_2_D.'</strong></td>
		<td align="center"><strong>'.$total_2_V.'</strong></td>
		<td align="center"><strong>'.$TOTAL_2.'</strong></td>
		<td align="center"><strong>'.$total_3_D.'</strong></td>
		<td align="center"><strong>'.$total_3_V.'</strong></td>
		<td align="center"><strong>'.$TOTAL_3.'</strong></td>
		<td align="center"><strong>'.$total_4_D.'</strong></td>
		<td align="center"><strong>'.$total_4_V.'</strong></td>
		<td align="center"><strong>'.$TOTAL_4.'</strong></td>
		<td align="center"><strong>'.$total_5_D.'</strong></td>
		<td align="center"><strong>'.$total_5_V.'</strong></td>
		<td align="center"><strong>'.$TOTAL_5.'</strong></td>
		<td align="center"><strong>'.$SUMA_RETIRADOS.'</strong></td>
		<td align="center"><strong>'.$SUMA_TOTAL.'</strong></td>
		</tr>
		</tbody></table>';
		//echo"---> $matriculas<br>";
		//echo"---> $carrera_label<br>";		
		include("../../../funciones/G_chart.php");
		$array_grafico["tipo"]="lc";//"bvs";"lc"
		$array_grafico["datos"][]=$matriculas;
		$array_grafico["rango_X"]=$carrera_label;
		$array_grafico["rango_Y_auto"]=true;//si true no necesita enviar "rango_Y", se genera automaticamnete
		$array_grafico["dato_max"]=$max_total;
		$array_grafico["etiqueta_izquierda"]="matriculas";
		$array_grafico["etiqueta_inferior"]="carreras";
		$array_grafico["titulo"]="Matriculas Generadas";
		$array_grafico["simbologia"]="matriculas";
		$array_grafico["colores_lineas_hex"]="F1A100";
		$array_grafico["color_titulo_hex"]="F10000";
		$array_grafico["size_titulo"]=20;
		?>
<div id="apDiv1">
  <h3><?php echo $msj;?> </h3>
  <div id="CollapsiblePanel1" class="CollapsiblePanel">
    <div class="CollapsiblePanelTab Estilo5" tabindex="0">	Resumen</div>
    <div class="CollapsiblePanelContent"><?php echo $tabla;?></div>
  </div>
  
  <div id="CollapsiblePanel2" class="CollapsiblePanel">
    <div class="CollapsiblePanelTab  Estilo5" tabindex="0">Grafico</div>
    <div class="CollapsiblePanelContent"><?php GRAFICO_GOOGLE($array_grafico);?></div>
  </div>
</div>
<?php
	}
}
else
{
	echo"Sin Datos...<br>";
}	
?>

<div id="apDiv2"><br />
  <a href="ver_alumnos_matriculados.php?<?php echo $envio_get;?>" target="_blank" class="button"> Ver Alumnos</a></div>
<script type="text/javascript">
<!--
var CollapsiblePanel1 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel1");
var CollapsiblePanel2 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel2");
//-->
</script>
</body>
</html>