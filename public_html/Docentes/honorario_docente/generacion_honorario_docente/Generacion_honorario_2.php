<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Genera_honorario_1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("Generacion_honorario_2_server.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"ASIGNACION_CAMBIO_ESTADO");
//---------------------------------------------------------///

if(isset($_SESSION["HONORARIO"]))
{ $hay_sesion=true;}
else
{ $hay_sesion=false;}

require("../../../../funciones/conexion_v2.php");
require("../../../../funciones/funciones_sistema.php");
if($_GET)
{
	$sede=$_GET["fsede"];
	$mes_actual=date("m");
	if($mes_actual>8)
	{$semestre_actual=2;}
	else{$semestre_actual=1;}
	
	$semestre_asignacion_consulta=$_GET["semestre"];
	$year=$_GET["year"];
	$mes=$_GET["mes"];
	$year_generacion=$_GET["year_generacion"];
if((!$hay_sesion)or(DEBUG))
{
	if(DEBUG){var_dump($_GET);}
	$TOTAL_HONORARIOS=0;
	$fecha_actual=date("Y-m-d H:i:s");
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	
	$cons_MAIN="SELECT DISTINCT(id_funcionario) FROM toma_ramo_docente INNER JOIN personal ON toma_ramo_docente.id_funcionario=personal.id WHERE toma_ramo_docente.sede='$sede'AND toma_ramo_docente.condicion='pendiente' AND toma_ramo_docente.semestre='$semestre_asignacion_consulta' AND toma_ramo_docente.year='$year' ORDER by personal.apellido_P, personal.apellido_M";
	if(DEBUG){ echo"--><strong>$cons_MAIN</strong><br>";}
	$sqli_F=$conexion_mysqli->query($cons_MAIN)or die($conexion_mysqli->error);
	$num_total_funcionarios=$sqli_F->num_rows;
	if(DEBUG){echo"Total de Funcionarios con asignaciones: $num_total_funcionarios<br>";}
	$tabla=' <table width="90%" border="1" id="report" align="center">
  <thead>
    <tr>
      <th colspan="6">Honorarios a Generar</th>
    <tbody>';
	if($num_total_funcionarios>0)
	{
		$contador_1=0;
		while($F=$sqli_F->fetch_row())
		{
			$contador_1++;
			$id_funcionario=$F[0];
			
			//Datos funcionarios
			$cons_DF="SELECT * FROM personal WHERE id='$id_funcionario' LIMIT 1";
			$sqli_DF=$conexion_mysqli->query($cons_DF)or die($conexion_mysqli->error);
				$DF=$sqli_DF->fetch_assoc();
				$F_rut=$DF["rut"];
				$F_nombre=$DF["nombre"];
				$F_apellido_P=$DF["apellido_P"];
				$F_apellido_M=$DF["apellido_M"];
			$sqli_DF->free();
			//--------------------------------------------------------------------//	
			//datos asignatuta
			$contador=0;
			
			//modifique aqui para mejorar la carga de asignaciones 05/10/2015
			$cons_asignaciones="SELECT * FROM toma_ramo_docente WHERE id_funcionario='$id_funcionario' AND semestre='$semestre_asignacion_consulta' AND year='$year' AND sede='$sede' AND condicion='pendiente' order by cod_asignatura";
			$sqli=$conexion_mysqli->query($cons_asignaciones)or die($conexion_mysqli->error);
			$num_asignaciones=$sqli->num_rows;
			if(DEBUG){ echo"Asignaciones: $cons_asignaciones<br>Numero asignaciones: $num_asignaciones<br>";}
			$ARRAY_ITEM_DETALLE=array();
			if($num_asignaciones>0)
			{
				$tabla_1='<tr>
							<td colspan="6">
							<h4>Asignaciones Realizadas</h4>';
				$mostrar_resumen_funcionario=false;		
				$SUMA_VALOR_CUOTAS=0;	
				
				while($AS=$sqli->fetch_assoc())
				{
					$contador++;
					$glosa_detalle="";
					$AS_sede=$AS["sede"];
					$AS_id=$AS["id"];
					$AS_id_carrera=$AS["id_carrera"];
					$AS_jornada=$AS["jornada"];
					$AS_grupo=$AS["grupo"];
					$AS_valor_hora=$AS["valor_hora"];
					$AS_cod_asignatura=$AS["cod_asignatura"];
					$AS_numero_horas=$AS["numero_horas"];
					$AS_numero_cuotas=$AS["numero_cuotas"];
					
					$AS_numero_horas_mensuales=($AS_numero_horas/$AS_numero_cuotas);//obtengo numero horas semanales
					
					$AS_total=$AS["total"];
					$AS_semestre=$AS["semestre"];
					$AS_year=$AS["year"];
					//carrera
					$nombre_carrera=NOMBRE_CARRERA($AS_id_carrera);
					//----------------------------//
					//asignatura
						
					list($nombre_asignatura,$nivel_asignatura)=NOMBRE_ASIGNACION($AS_id_carrera, $AS_cod_asignatura);
						
					$valor_cuota_actual=$AS_total/$AS_numero_cuotas;	
					$valor_cuota_actual_2=$valor_cuota_actual;
					
					$valor_cuota_actual_v2=($AS_numero_horas_mensuales*$AS_valor_hora);
					
					//----------------------------------------------------------------------------------------//
					//busco numero de registros previos de esta asignacion
					$cons_honorario_detalle="SELECT * FROM honorario_docente_detalle WHERE sede='$AS_sede' AND id_carrera='$AS_id_carrera' AND cod_asignatura='$AS_cod_asignatura' AND id_funcionario='$id_funcionario' AND semestre='$AS_semestre' AND year='$AS_year' AND jornada='$AS_jornada' AND grupo='$AS_grupo'";
					$sqli_HD=$conexion_mysqli->query($cons_honorario_detalle)or die($conexion_mysqli->error);
					$num_registros_honorario_detalle=$sqli_HD->num_rows;
					if(DEBUG){ echo"---->$cons_honorario_detalle<br>Numero detalles honorario: $num_registros_honorario_detalle<br>";}
					
					$TOTAL_ya_cancelado=0;
					if($num_registros_honorario_detalle>0)
					{
						$num_cuotas_previas=0;
						while($HD=$sqli_HD->fetch_assoc())
						{
							$num_cuotas_previas++;
							$HD_total_base=$HD["total_base"];
							$HD_cargo=$HD["cargo"];
							$HD_abono=$HD["abono"];
							
							$aux_total_x_asignatura=($HD_total_base+$HD_abono)-$HD_cargo;
							
							$TOTAL_ya_cancelado+=$aux_total_x_asignatura;
						}
						$valor_ultima_cuota_detalle=$HD_total_base;
						$num_cuota_actual_a_pagar=$num_cuotas_previas+1;
						
						
					}
					else
					{
						$valor_ultima_cuota_detalle=$valor_cuota_actual;
						$num_cuotas_previas=0;
						$num_cuota_actual_a_pagar=1;
					}
					$sqli_HD->free();
					//--------------------------------------------------------------//
					if(DEBUG){ echo"Valor Cuota Actual: $valor_cuota_actual  Valor Ultima Cuota: $valor_ultima_cuota_detalle<br>Valor Cuota V2: $valor_cuota_actual_v2<br>Cantidad Horas mensuales: $AS_numero_horas_mensuales<br>";}

					//---------------------------------------------------------------//
					//determino si debe ser pagada o no	
					if($num_cuota_actual_a_pagar>$AS_numero_cuotas)
					{$utilizar_para_honorario=false; if(DEBUG){ echo"Numero de Cuota Actual Supera al Maximo de cuotas Generadas NO utiliza para honorario<br><br>";} $cambiar_condicion_asignacion=true;}
					elseif($num_cuota_actual_a_pagar==$AS_numero_cuotas)
					{$utilizar_para_honorario=true;if(DEBUG){ echo"Numero de Cuota Actuales igual al Maximo de cuotas Generadas utiliza para honorario<br><br>";} $cambiar_condicion_asignacion=true;}
					else{$utilizar_para_honorario=true; if(DEBUG){ echo"Numero de Cuota Actuales menor al Maximo de cuotas Generadas utiliza para honorario<br><br>";} $cambiar_condicion_asignacion=false;}
					//----------------------------------------------------------------------//	
					
					
					if($utilizar_para_honorario)
					{
						if(DEBUG){ echo"Utilizar para Honorario<br>";}
						$mostrar_resumen_funcionario=true;
						$SUMA_VALOR_CUOTAS+=$valor_cuota_actual;	
							
						/////////////////////////////////////////////////////	
						//grabo	en arreglo
						$ARRAY_ITEM_DETALLE[$contador]["id_carrera"]=$AS_id_carrera;
						$ARRAY_ITEM_DETALLE[$contador]["cod_asignatura"]=$AS_cod_asignatura;
						$ARRAY_ITEM_DETALLE[$contador]["jornada"]=$AS_jornada;
						$ARRAY_ITEM_DETALLE[$contador]["grupo"]=$AS_grupo;
						$ARRAY_ITEM_DETALLE[$contador]["total_base"]=$valor_cuota_actual;
						$ARRAY_ITEM_DETALLE[$contador]["horas_mensuales"]=$AS_numero_horas_mensuales;
						$ARRAY_ITEM_DETALLE[$contador]["sede"]=$AS_sede;
						$ARRAY_ITEM_DETALLE[$contador]["semestre"]=$AS_semestre;
						$ARRAY_ITEM_DETALLE[$contador]["year"]=$AS_year;
						$ARRAY_ITEM_DETALLE[$contador]["cargo"]=0;
						$ARRAY_ITEM_DETALLE[$contador]["abono"]=0;
						$ARRAY_ITEM_DETALLE[$contador]["glosa_cargo"]="";
						$ARRAY_ITEM_DETALLE[$contador]["glosa_abono"]="";
						$ARRAY_ITEM_DETALLE[$contador]["condicion"]="on";
						$ARRAY_ITEM_DETALLE[$contador]["num_cuota_actual"]=$num_cuota_actual_a_pagar;
						$ARRAY_ITEM_DETALLE[$contador]["num_cuotas_totales"]=$AS_numero_cuotas;
						$ARRAY_ITEM_DETALLE[$contador]["valor_hora"]=$AS_valor_hora;
					}
					else
					{ if(DEBUG){ echo"NO Utilizar para Honorario<br>";}}
				}

			}
			else
			{
				//sin asignaciones
			}
				if($mostrar_resumen_funcionario)
				{
					
					if($SUMA_VALOR_CUOTAS>0)
					{ $honorario_a_pagar_docente=$SUMA_VALOR_CUOTAS; $estado_honorario="pendiente";}
					else
					{ $honorario_a_pagar_docente=0; $estado_honorario="cancelado";}
					
					
					$_SESSION["HONORARIO"][$id_funcionario]["total_a_pagar"]=$honorario_a_pagar_docente;
					$_SESSION["HONORARIO"][$id_funcionario]["asignaciones"]=$ARRAY_ITEM_DETALLE;
				
					//GRABO HONORARIO
					if(DEBUG){ echo"<strong>GUARDO HONORARIO</strong><br>";}

					///grabo item
					
				}
			
		}
		$mostrar_boton=true;
	}
	else{$mostrar_boton=false;}
		
	$sqli_F->free();
	

}//fin si no hay session
else{ if(DEBUG){ echo"Ya hay session<br>";}}
}
else
{ if(DEBUG){echo"Sin Datos GET...<br>";}}
if(DEBUG){var_dump($_SESSION["HONORARIO"]);}

$array_meses=array(1=>"Enero",
					2=>"Febrero",
					3=>"Marzo",
					4=>"Abril",
					5=>"Mayo",
					6=>"Junio",
					7=>"Julio",
					8=>"Agosto",
					9=>"Septiembre",
					10=>"Octubre",
					11=>"Noviembre",
					12=>"Diciembre");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>Docentes | Generacion Honorario</title>
<?php $xajax->printJavascript(); ?> 
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:101px;
	z-index:1;
	left: 5%;
	top: 135px;
}
.estilo_SUMA_TOTAL {
	font-size: large;
	font-weight: bolder;
}
</style>
  <!--INICIO LIGHTBOX EVOLUTION-->
  <script type="text/javascript" src="../../../libreria_publica/lightbox_1.6.9_evolution/js/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.css" />
  <!--[if IE 6]>
  <link rel="stylesheet" type="text/css" href="../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.ie6.css" />
  <![endif]-->
  <script type="text/javascript" src="../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/jquery.lightbox.min.js"></script>
 <script type="text/javascript">
    jQuery(document).ready(function($){
      $('.lightbox').lightbox(); 
    });
  </script>
 <!--FIN LIGHTBOX EVOLUTION--> 
 
<script language="javascript">
function CONFIRMAR_GENERACION()
{
	c=confirm('Seguro(a) desea Generar estos Honorarios');
	if(c){window.location="Genera_honorario_3X.php?verificador=<?php echo md5("massa".date("Ymd"));?>&sede=<?php echo $sede;?>&mes=<?php echo $mes;?>&year=<?php echo $year;?>&semestre=<?php echo $semestre_asignacion_consulta;?>&year_generacion=<?php echo $year_generacion;?>";}
}
</script>
</head>

<body>

<h1 id="banner">Funcionarios - Generacion de Honorario V 2.5</h1>
<div id="link"><br>
<a href="Generacion_honorario_1.php" class="button">Volver a seleccion</a><br /><br />
<a href="recarga_asignaciones/recargar_asignaciones_1.php?sede=<?php echo $sede;?>&year=<?php echo $year;?>&semestre=<?php echo $semestre_asignacion_consulta;?>&mes=<?php echo $mes;?>&year_generacion=<?php echo $year_generacion;?>" class="button_R">Recargar Asignacion</a>
</div>

<div id="apDiv1">
<table align="center" width="95%">
<thead>
	<tr>
    	<th colspan="9">Generacion de Honorarios <?php echo"$sede [".$mes."_".$array_meses[$mes]." - $year_generacion]";?></th>
    </tr>
    <tr>
    	<td>N</td>
        <td>Rut</td>
        <td>Nombre</td>
        <td>Apellido</td>
        <td colspan="5">Total</td>
    </tr>
</thead>
<tbody>
<?php
//---------------------------------------------------------------------------------///
//escribo sesion
//---------------------------------------------------------------------------------//
$boton="";
$SUMA_TOTAL=0;
if(isset($_SESSION["HONORARIO"]))
{
if(count($_SESSION["HONORARIO"])>0)
{
	$aux=0;
	$boton='<a href="#" class="button_R" onclick="CONFIRMAR_GENERACION();">Confirma la Generacion de Estos Honorarios</a>';
	foreach($_SESSION["HONORARIO"] as $X_id_funcionario =>$aux_array)
	{
		$aux++;
		$X_total_a_pagar=$aux_array["total_a_pagar"];
		$SUMA_TOTAL+=$X_total_a_pagar;
		//------------------------------------------------------------------------------//
		//Datos funcionarios
			$cons_DF="SELECT * FROM personal WHERE id='$X_id_funcionario' LIMIT 1";
			$sqli_DF=$conexion_mysqli->query($cons_DF)or die($conexion_mysqli->error);
				$DF=$sqli_DF->fetch_assoc();
				$F_rut=$DF["rut"];
				$F_nombre=$DF["nombre"];
				$F_apellido_P=$DF["apellido_P"];
				$F_apellido_M=$DF["apellido_M"];
			$sqli_DF->free();
	//-------------------------------------------------------------------------//		
		echo'<tr>
				<td>'.$aux.'</td>
				<td>'.$F_rut.'</td>
				<td>'.$F_nombre.'</td>
				<td>'.$F_apellido_P.' '.$F_apellido_M.'</td>
				<td colspan="5" align="right"><div id="total_pagar_'.$X_id_funcionario.'"><strong>$ '.number_format($X_total_a_pagar,0,",",".").'</strong></div></td>
				
			 </tr>';
		echo'<tr>
				 <th>--></th>
				 <td><strong>N</strong></td>
				 <td><strong>Carrera</strong></td>
				 <td><strong>Asignatura</strong></td>
				 <td><strong>Jor-Grup</strong></td>
				 <td bgcolor="#3399FF">Horas Mensuales</td>
				 <td bgcolor="#FF0000">Cargo</td>
				 <td bgcolor="#009900">Abono</td>
				 <td bgcolor="#FFFF00">Total Asignacion</td>
				 <tr>';	 
		foreach($aux_array["asignaciones"] as $indice => $array_asignaciones)
		{
			$X_id_carrera=$array_asignaciones["id_carrera"];
			$X_cod_asignatura=$array_asignaciones["cod_asignatura"];
			$X_jornada=$array_asignaciones["jornada"];
			$X_grupo=$array_asignaciones["grupo"];
			$X_total_base=$array_asignaciones["total_base"];
			$X_horas_mensuales=$array_asignaciones["horas_mensuales"];
			$X_cargo=$array_asignaciones["cargo"];
			$X_abono=$array_asignaciones["abono"];
			$X_condicion=$array_asignaciones["condicion"];
			$X_cuota_actual=$array_asignaciones["num_cuota_actual"];
			$X_total_cuotas=$array_asignaciones["num_cuotas_totales"];
			$X_valor_hora=$array_asignaciones["valor_hora"];
			if(empty($X_condicion)){$X_condicion="on";}
			
			
			//--------------------------------------------------------------------------------------//
			//calculo de totales
			$total_base=($X_horas_mensuales*$X_valor_hora);
			$total_cargo=($X_cargo*$X_valor_hora);
			$total_abono=($X_abono*$X_valor_hora);
			
			$X_total_asignacion=($total_base-$total_cargo)+$total_abono;
			//-----------------------------------------------------------------------------------//
			//carrera
			$nombre_carrera=NOMBRE_CARRERA($X_id_carrera);
			//---------------------------------------------------------------------------//
			//asignatura
			list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($X_id_carrera, $X_cod_asignatura);
			//-----------------------------------------------------------------------------------------------//	
			
			echo'<tr>
				 <td><div id="AS_condicion_'.$X_id_funcionario.'_'.$indice.'"><a href="#" onclick="xajax_ASIGNACION_CAMBIO_ESTADO('.$X_id_funcionario.', '.$indice.', \''.$X_condicion.'\'); return false;">'.$X_condicion.'</a></div></td>
				 <td>'.$indice.'</td>
				 <td><tt>'.$nombre_carrera.'</tt></td>
				 <td><tt>'.$nombre_asignatura.' ['.$X_cuota_actual.'/'.$X_total_cuotas.']</tt></td>
				 <td><tt>'.$X_jornada.'-'.$X_grupo.'</tt></td>
				 <td align="right" bgcolor="#3399FF"><div id="AS_total_base_'.$X_id_funcionario.'_'.$indice.'">'.$X_horas_mensuales.' X [$'.number_format($X_valor_hora,0,",",".").']</div></td>
				 <td align="right"><div id="AS_cargo_'.$X_id_funcionario.'_'.$indice.'"><a href="cargos_abonos_server.php?id_funcionario='.base64_encode($X_id_funcionario).'&indice='.base64_encode($indice).'&tipo=cargos&lightbox[iframe]=true&lightbox[width]=500&lightbox[height]=300"  class="lightbox" title="click para Descuento de Horas">'.number_format($X_cargo,0,",",".").'</a></div></td>
				 <td align="right"><div id="AS_abono_'.$X_id_funcionario.'_'.$indice.'"><a href="cargos_abonos_server.php?id_funcionario='.base64_encode($X_id_funcionario).'&indice='.base64_encode($indice).'&tipo=abonos&lightbox[iframe]=true&lightbox[width]=500&lightbox[height]=300"  class="lightbox" title="click para Horas Extra">'.number_format($X_abono,0,",",".").'</a></div></td>
				 <td align="right">$'.number_format($X_total_asignacion, 0,",",".").'</td>
				 <tr>';
		}
		echo'<tr><td colspan="9">&nbsp;</td></tr>';
	}
}
}
else
{ echo'<tr><td colspan="9">Sin Honorarios que Generar</td></tr>';}

	$conexion_mysqli->close();
?>
<tr>
	<td colspan="6">TOTAL</td>
    <td colspan="3" align="right"><div class="estilo_SUMA_TOTAL" id="SUMA_TOTAL"><?php echo"$ ".number_format($SUMA_TOTAL,0,",",".");?></div></td>
   
</tr>
</tbody>
</table>
</br>
<div id="boton" align="center"><?php echo $boton;?></div>
</div>
</body>
</html>