<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="ALUMNO";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define("DEBUG",false);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<title>Resultados - Test
</title>
<?php
	if(isset($_GET["v"])){$V=$_GET["v"];}
	if(isset($_GET["W"])){$W=$_GET["W"];}
	$largo_palabra=5;
	
	$errores=base64_decode($_GET["x"]);
	$caracteres_texto=base64_decode($_GET["l"]);
	$tiempo_segundo=base64_decode($_GET["s"]);
	if((empty($tiempo_segundo))or(!is_numeric($tiempo_segundo)))
	{ $tiempo_segundo=0;}
	if($tiempo_segundo>0)
	{ $tiempo_segundo=$tiempo_segundo-300;}
	$hora_inicio=base64_decode($_GET["hi"]);
	$hora_fin=base64_decode($_GET["hf"]);
	$id_leccion=base64_decode($_GET["id"]);
	$pulsaciones_totales=base64_decode($_GET["tp"]);
	$indicador_tiempo=base64_decode($_GET["it"]);
	
	if(DEBUG)
	{
		echo"Errores: $errores<br> caracteres texto: $caracteres_texto<br> Tiempo seg: $tiempo_segundo<br>hora ini: $hora_inicio Hora fin: $hora_fin<br>pulsaciones totales: $pulsaciones_totales<br> indicador tiempo: $indicador_tiempo<br>";
	}
	//----------------------------------//
	//datos leccion
	include("../../../funciones/conexion_v2.php");
	$cons_L="SELECT * FROM dactilografia_lecciones WHERE id='$id_leccion' LIMIT 1";
	$sql_L=$conexion_mysqli->query($cons_L);
	$L=$sql_L->fetch_assoc();
		$L_titulo=$L["titulo"];
		$L_clasificacion=$L["clasificacion"];
		$L_exigencia=$L["nivel_exigencia"];
		$L_duracion=$L["duracion_seg"];
		$L_texto=$L["texto"];
		$L_texto=str_replace("<br>","-",$L_texto);
		$numero_caracteres_texto_original=strlen($L_texto);
	$sql_L->free();	
	
	////----------------------------------------//
	
	$img_ok='<img src="../../BAses/Images/ok.png" width="29" height="26" alt="ok" />';
	$img_error='<img src="../../BAses/Images/b_drop.png" width="16" height="16" alt="X" />';
	switch($indicador_tiempo)
	{
		case"MAXIMO":
			$tiempo_segundo=$L_duracion;//if se acabo el tiempo el tiempo es el maximo
			$msj="Se te Acabo el Tiempo...";
			$img=$img_error;
			break;
		case"error":
			$tiempo_segundo=$L_duracion;
			$msj="Se Detecto el Uso de teclas indebidas...";
			$img=$img_error;
			break;
		case"ok":
			$msj="Test Realizado Correctamente...";
			$img=$img_ok;
			break;	
	}
	//calculos
	$porcentaje_error=(($errores*100)/$caracteres_texto);
	$porcentaje_precision=(100-$porcentaje_error);
	
	$porcentaje_error=number_format($porcentaje_error,2,",",".");
	$porcentaje_precision=number_format($porcentaje_precision,2,",",".");
	$ppmb=number_format(($pulsaciones_totales/($tiempo_segundo/60)),1,".","");
	$ppmn=number_format((($pulsaciones_totales-$errores)/($tiempo_segundo/60)),1,".","");
	
	$palabrasXminuto_N=((($pulsaciones_totales-$errores)/$largo_palabra)/($tiempo_segundo/60));
	$palabrasXminuto_B=(($pulsaciones_totales/$largo_palabra)/($tiempo_segundo/60));
	//grabo resultados
	if(DEBUG){ $grabar=true;}
	else
	{
		if($_SESSION["DACTILOGRAFIA"]["verificador"])
		{
			$_SESSION["DACTILOGRAFIA"]["verificador"]=false;
			$grabar=true;
		}
		else{ $grabar=false;}
	}
	/////
	//variables comuns
		$privilegio=$_SESSION["USUARIO"]["privilegio"];
		$id_usuario_activo=$_SESSION["USUARIO"]["id"];
		$tipo_usuario=$_SESSION["USUARIO"]["privilegio"];
		$fecha_generacion=date("Y-m-d");
		
		switch($privilegio)
		{
			case"ALUMNO":
			 include("../../../funciones/VX.php");
			 //cambio estado_conexion USER-----------
			 CAMBIA_ESTADO_CONEXION_ALUMNO($id_usuario_activo, "ON");
			//-----------------------------------------------//
			$evento="Dactilografia -> Revision de Resultados";
			REGISTRA_EVENTO($evento);
				break;
		}
	/////
	if($grabar)
	{
		$campos="id_leccion, id_usuario, tipo_usuario, resultado_1, resultado_2, fecha_generacion";
		$valores="'$id_leccion', '$id_usuario_activo', '$tipo_usuario', '$ppmn', '$ppmb', '$fecha_generacion'";
		$cons_IN_reg="INSERT INTO dactilografia_registros ($campos) VALUES ($valores)";
		if(DEBUG){ echo"IN -> $cons_IN_reg<br>";}
		else
		{
			$conexion_mysqli->query($cons_IN_reg);
		}
	}
?>

<style type="text/css">
#apDiv1 {
	position:absolute;
	width:47%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 126px;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:2;
	left: 5%;
	top: 582px;
	text-align: center;
}
#apDiv3 {
	position:absolute;
	width:45%;
	height:115px;
	z-index:3;
	left: 54%;
	top: 127px;
}
</style>
</head>

<body>
<h1 id="banner">Dactilografia - Test de Velocidad</h1><br />

<div id="link"><a href="../Lecciones_disponibles.php" class="button">Volver a Lecciones</a></div>
<div id="cont">

	<div class="contenido">
	</div>


</div>
<div id="apDiv1">
  <table width="100%" border="1" align="center">
  <thead>
    <tr>
      <th colspan="3">Resultados</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>Codigo Leccion</td>
      <td colspan="2"><span class="contenido"><?php echo $id_leccion;?></span></td>
    </tr>
    <tr>
      <td>Titulo Leccion</td>
      <td colspan="2"><span class="contenido"><?php echo $L_titulo;?></span></td>
    </tr>
    <tr>
      <td>Nivel Exigencia</td>
      <td colspan="2"><span class="contenido"><?php echo $L_exigencia;?></span></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td rowspan="2"><span class="contenido">Pulsaciones Por Minuto (PPM): </span></td>
      <td>Bruto</td>
      <td><?php echo $ppmb; ?></td>
    </tr>
    <tr>
      <td>Neto</td>
      <td><?php echo $ppmn; ?></td>
    </tr>
    <tr>
      <td rowspan="2">Palabras Por Minuto(ppm)</td>
      <td>Bruto</td>
      <td><?php echo number_format($palabrasXminuto_B,2,",",".");?></td>
    </tr>
    <tr>
      <td>Neto</td>
      <td><?php echo number_format($palabrasXminuto_N,2,",",".");?></td>
    </tr>
    <tr>
      <td><span class="contenido">Tiempo utilizado (segundos):</span></td>
      <td colspan="2"><span class="contenido"><?php echo $tiempo_segundo;?></span></td>
    </tr>
    <tr>
      <td><span class="contenido">N&uacute;mero de errores:</span></td>
      <td colspan="2"><span class="contenido"><?php echo $errores;?> (<?php echo $porcentaje_error;?>%)</span></td>
    </tr>
    <tr>
      <td>Precision</td>
      <td colspan="2"><span class="contenido"><?php echo $porcentaje_precision;?>%</span></td>
    </tr>
    <tr>
      <td><span class="contenido">Total Caracteres del Texto:</span></td>
      <td colspan="2"><span class="contenido"><?php echo $numero_caracteres_texto_original;?></span></td>
    </tr>
    <tr>
      <td>Total Teclas Presionadas</td>
      <td colspan="2"><?php echo $pulsaciones_totales;?></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><?php echo "<em>$msj</em> $img";?></td>
    </tr>
    </tbody>
  </table>
</div>
<div id="apDiv2">
  <?php
	include("../../../funciones/funcion.php");
	$concat_r1="";
	$concat_r2="";
	$rango_x="";
	$rango_y="";
	$maximo=0;
	$contador=0;
	
		$cons_br="SELECT * FROM dactilografia_registros WHERE id_leccion='$id_leccion' AND id_usuario='$id_usuario_activo' AND tipo_usuario='$tipo_usuario' ORDER by id LIMIT 0, 10";
		if(DEBUG){ echo"--> $cons_br<br>";}
		$sql_br=$conexion_mysqli->query($cons_br);
		$num_reg=$sql_br->num_rows;
		if($num_reg>0)
		{
			$primera_vuelta=true;
			
			
			$graficar=true;
			while($RL=$sql_br->fetch_assoc())
			{
				$contador++;
				
				$aux_fecha=fecha_format($RL["fecha_generacion"]);
				$aux_resultado_1=$RL["resultado_1"];
				$aux_resultado_2=$RL["resultado_2"];
				
				//obtengo maximo de ppmn
				if($aux_resultado_1>$maximo)
				{ $maximo=$aux_resultado_1; }
				
				if($primera_vuelta)
				{
					$concat_r1=$aux_resultado_1;
					$concat_r2=$aux_resultado_2;
					$rango_x="|".$aux_fecha."|";
					$rango_y="|".$contador."|";
					$primera_vuelta=false;
				}
				else
				{
					$concat_r1.=",".$aux_resultado_1;
					$concat_r2.=",".$aux_resultado_2;
					$rango_x.=$aux_fecha."|";
					$rango_y.=$contador."|";
				}
				
			}
		}
		else
		{
			$graficar=false;
		}
	$sql_br->free();
	//------------------------------------------------------------//
$array_grafico["tipo"]="lc";
$array_grafico["datos"][]=$concat_r1;
$array_grafico["rango_X"]=$rango_x;
$array_grafico["datos"][]=$concat_r2;
$array_grafico["rango_Y"]=$rango_y;
$array_grafico["rango_Y_auto"]=true;//si true no necesita enviar "rango_Y", se genera automaticamnete
$array_grafico["dato_max"]=round($maximo);
$array_grafico["etiqueta_izquierda"]="puntaje";
$array_grafico["etiqueta_inferior"]="fecha";
$array_grafico["titulo"]="Ultimos Resultados";
$array_grafico["simbologia"]="ppmn|ppmb";
$array_grafico["colores_lineas_hex"]="F1A100,1F1F00";
$array_grafico["color_titulo_hex"]="F10000";
$array_grafico["size_titulo"]=20;
//---------------------------------------------------------------//
	if($graficar)
	{
		//var_export($array_grafico["rango_Y"]);
		include("../../../funciones/G_chart.php");
		GRAFICO_GOOGLE($array_grafico, DEBUG);
	}
?>
</div>
<div id="apDiv3">
<table width="80%" align="center">
<thead>
<tr>
	<th colspan="4">Ranking Leccion (<?php echo $id_leccion;?>)</th>
</tr>
<tr>
	<td><strong>Posición</strong></td>
    <td><strong>PPMN</strong></td>
    <td><strong>Usuario</strong></td>
    <td><strong>Fecha</strong></td>
</tr>
</thead>
<tbody>
<?php

$cons="SELECT * FROM dactilografia_registros WHERE id_leccion='$id_leccion' ORDER by resultado_1 desc LIMIT 10";
$sql=$conexion_mysqli->query($cons);
$num_regX=$sql->num_rows;
if(DEBUG){echo"REGISTROS: $num_regX<br>";}
if($num_regX>0)
{
	$posicion=0;
	while($MP=$sql->fetch_assoc())
	{
		$estrellas="";
		if($posicion<=3)
		{
			$numero_estrellas=3-$posicion;
			for($i=0;$i<$numero_estrellas;$i++)
			{
				$estrellas.='<img src="../../BAses/Images/estrella.png" width="15" height="15" alt=":)" />';
			}
		}
		$posicion++;
		$MP_id_usuario=$MP["id_usuario"];
		$MP_tipo_usuario=$MP["tipo_usuario"];
		$MP_resultado_1=$MP["resultado_1"];
		$MP_resultado_2=$MP["resultado_2"];
		$MP_fecha=$MP["fecha_generacion"];
		///////buscar datos usuario
		switch($MP_tipo_usuario)
		{
			case"ALUMNO":
				$mostrar_MP=true;
				$consDU="SELECT nombre, apellido_P, apellido_M, carrera FROM alumno WHERE id='$MP_id_usuario' LIMIT 1";
				$sql_DU=$conexion_mysqli->query($consDU);
					$DU=$sql_DU->fetch_assoc();
					$MP_nombre=$DU["nombre"];
					$MP_apellido=$DU["apellido_P"]." ".$DU["apellido_M"];
				$sql_DU->free();
				break;
			default:
				$mostrar_MP=false;
			$consDU="SELECT nombre, apellido FROM personal WHERE id='$MP_id_usuario' LIMIT 1";
				$sql_DU=$conexion_mysqli->query($consDU);
					$DU=$sql_DU->fetch_assoc();
					$MP_nombre=$DU["nombre"];
					$MP_apellido=$DU["apellido"];
				$sql_DU->free();		
					
		}
		
		$MP_usuario_info=$MP_nombre." ".$MP_apellido;
		
		
		if(DEBUG){echo"--> $MP_id_usuario $MP_tipo_usuario $MP_resultado_1 $MP_resultado_2 $MP_fecha<br>";}
		if($mostrar_MP)
		{
			echo'<tr>
					<td><strong>'.$posicion.'</strong> '.$estrellas.'</td>
					<td>'.$MP_resultado_1.'</td>
					<td>'.$MP_usuario_info.'</td>
					<td>'.fecha_format($MP_fecha).'</td>
				 </tr>';
		}
	}
}
else
{
	echo'<tr><td>Sin Registros Previos de Esta Leccion...</td></tr>';
}
$sql->free();
$conexion_mysqli->close();
?>
</tbody>
</table>
</div>
</body>
</html>