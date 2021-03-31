<?php
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="inspeccion";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
	define("DEBUG",false)
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<title>informe cuotas adeudadas</title>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:37px;
	z-index:1;
	left: 5%;
	top: 78px;
}
#link {
	text-align: right;
	padding-right: 10px;
}
#apDiv1 #frm #boton {
	background-color: #f5f5f5;
	padding-top: 0px;
	margin-top: 30px;
	margin-right: 0px;
	margin-bottom: 0px;
	margin-left: 0px;
	border: thin solid #FF0000;
	width: 55%;
}
#apDiv1 #frm #msj {
	background-color: #CCFF33;
	font-weight: bold;
	text-decoration: blink;
	text-align: center;
}
a:link {
	color: #6699FF;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #6699FF;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #6699FF;
}
#apDiv1 #frm #otro_boton {
	background-color: #f5f5f5;
	border: thin solid #006600;
	padding-top: 0px;
	margin-top: 10px;
	width: 55%;
}
-->
</style>
<script language="javascript">
function CONFIRMAR(estado)
{
	c=confirm('zEsta Seguro(a) De Realizar esta Accion? \n -> Cambio de Condicion Alumno ('+estado+')');
	switch(estado)
	{
		case"MOROSO":
			document.getElementById('frm').action="cambia_condicion_alumno/cambia_condicion_M.php";
			break;
		case"VIGENTE":
			document.getElementById('frm').action="cambia_condicion_alumno/cambia_condicion_V.php";
			break;
	}
	if(c)
	{
		document.frm.submit();
	}
}
</script>
</head>
<?php
if($_POST)
{
	$sede=$_POST["fsede"];
	$array_carrera=$_POST["carrera"];
	$array_carrera=explode("_",$array_carrera);
	$id_carrera=$array_carrera[0];
	$carrera=$array_carrera[1];
	
	$nivel=$_POST["nivel"];
	$fecha_corte=$_POST["fecha_corte"];
	$jornada=$_POST["jornada"];
	$situacion_financiera=$_POST["situacion_financiera"];
	$year_letras=$_POST["year_letras"];
	$mostrar_subtotales=$_POST["mostrar_subtotales"];
	
}
elseif($_GET)
{
		$sede=base64_decode($_GET["sede"]);
		$array_carrera=base64_decode($_GET["carrera"]);
			$array_carrera=explode("_",$array_carrera);
			$id_carrera=$array_carrera[0];
			$carrera=$array_carrera[1];
		$nivel=base64_decode($_GET["nivel"]);
		$fecha_corte=base64_decode($_GET["fecha_corte"]);
		$jornada=base64_decode($_GET["jornada"]);
		$year_letras=base64_decode($_GET["year_letras"]);
}
	include("../../../funciones/funcion.php");
	$columnas=11;
	if($id_carrera==0)
	{
		$columnas=12;
	}
?>
<body>
<h1 id="banner">Administrador -Informe Cuotas Adeudadas</h1>
<div id="link"><br /> 
  <a href="index.php" class="button">Volver a Seleccion </a><br /><br />
  <a href="informe_deudores_mensualidad_excel.php?sede=<?php echo base64_encode($sede);?>&carrera=<?php echo base64_encode($id_carrera."_".$carrera);?>&nivel=<?php echo base64_encode($nivel);?>&fecha_corte=<?php echo base64_encode($fecha_corte);?>&jornada=<?php echo base64_encode($jornada);?>&situacion_financiera=<?php echo base64_encode($situacion_financiera);?>&year_letras=<?php echo base64_encode($year_letras);?>&mostrar_subtotales=<?php echo base64_encode($mostrar_subtotales);?>" class="button">.xls</a>
  </div>
<div id="apDiv1">
  <table width="80%" border="1" align="center">
  	<thead>
    <tr>
      <th colspan="<?php echo $columnas;?>"><div align="center"><strong>Alumnos Con Deuda Cuotas(<?php echo $year_letras;?>) , Fecha Corte <?php echo fecha_format($fecha_corte);?><br />
        Condicion Financiera Actual <?php echo $situacion_financiera;?>
        <br />
         Carrera: <?php echo $carrera;?>, Nivel <?php echo $nivel;?>, Jornada <?php echo $jornada;?><br />
          <?php echo $sede;?></strong>
          <input type="hidden" name="sede" id="sede"  value="<?php echo $sede;?>"/>
          <input type="hidden" name="carrera" id="carrera"  value="<?php echo $carrera;?>"/>
          <input name="nivel" type="hidden" id="nivel" value="<?php echo $nivel;?>" />
          <input name="grupo" type="hidden" id="grupo" value="<?php echo $grupo;?>" />
          <input name="fecha_corte" type="hidden" id="fecha_corte" value="<?php echo $fecha_corte;?>" />
           <input name="detalle" type="hidden" id="detalle" value="<?php echo $detalle;?>" />
           <input type="hidden" name="jornada" id="jornada"  value="<?php echo $jornada;?>"/>
           <br />
        <a href="<?php echo $url_reset;?>"></a></div></th>

    </tr>
    <tr>
    <td>N&deg;</td>
    <td>Rut</td>
    <td>Nombre</td>
    <td>Apellido P</td>
    <td>Apellido M</td>
    <?php if($id_carrera==0){?>
    <td>Carrera</td>
    <?php }?>
    <td>ID Cuota</td>
    <td>A&ntilde;o Corresponde Cuota</td>
    <td>Vencimiento</td>
    <td>Valor</td>
    <td>Deuda X Cuota</td>
    <td>Condici&oacute;n</td>
    </tr>
    </thead>
    <tbody>
<?php
if(($_POST)or($_GET))
{
	//------------------------------//
	if($mostrar_subtotales=="ON")
	{ $mostrar_subtotales=true;}
	else
	{ $mostrar_subtotales=false;}
	//---------------------------------//
	include("../../../funciones/conexion.php");
	$checked='checked="checked"';
	$color3 = "#E0FAC5";
	if($detalle=="ON")
	{ $ver_detalle=true;}
	else
	{ $ver_detalle=false;}

	if($nivel!="todos")
	{ $condicion_nivel="alumno.nivel='$nivel' AND";}
	if($jornada!="todas")
	{ $condicion_jornada="alumno.jornada='$jornada' AND";}
	
	if($id_carrera!=0)
	{ 
		$condicion_carrera="alumno.id_carrera='$id_carrera' AND";
	}
	if($situacion_financiera!="todos")
	{ 
		$condicion_financiera="alumno.situacion_financiera='$situacion_financiera' AND";
	}
	if($year_letras!="Todos")
	{ $condicion_year_letras="letras.ano='$year_letras' AND";}
	
	
		
	$consX="SELECT alumno.id, alumno.rut, alumno.nombre, alumno.apellido, alumno.apellido_P, alumno.apellido_M, alumno.carrera, alumno.nivel, alumno.grupo, alumno.situacion_financiera, alumno.jornada,letras.id AS id_letra, letras.fechavenc, letras.valor, letras.deudaXletra, letras.ano, letras.semestre, letras.pagada, letras.tipo FROM alumno INNER JOIN letras ON alumno.id = letras.idalumn WHERE $condicion_carrera alumno.sede='$sede' AND alumno.situacion='V' AND $condicion_nivel $condicion_jornada $condicion_financiera $condicion_year_letras letras.fechavenc <='$fecha_corte' AND NOT(letras.pagada='S') ORDER BY carrera, apellido_p, apellido_M, letras.fechavenc";
	
	if(DEBUG){ echo "$consX<br><br>";}
	$sql=mysql_query($consX)or die("consX ".mysql_error());
	$num_seleccionados=mysql_num_rows($sql);
	if($num_seleccionados>0)
	{
		$id_alumno_old=0;
		$aux=0;
		$primera_vuelta=true;
		$aux_subtotal_parcial=0;
		$SUMA_TOTAL=0;
		while($DA=mysql_fetch_assoc($sql))
		{
			$aux++;
			$id_alumno=$DA["id"];
			
			$rut=$DA["rut"];
			$nombre=ucwords(strtolower($DA["nombre"]));
			$apellido=$DA["apellido"];
			$apellido_P=$DA["apellido_P"];
			$apellido_M=$DA["apellido_M"];
			$carrera_alumno=$DA["carrera"];
			$nivel=$DA["nivel"];
			$grupo=$DA["grupo"];
			
			$id_letra=$DA["id_letra"];
			$aux_year_letras=$DA["ano"];
			
			$fecha_vence=$DA["fechavenc"];
			$valor=$DA["valor"];
			$deudaXletra=$DA["deudaXletra"];
			$ano=$DA["ano"];
			$semestre=$DA["semestre"];
			$pagada=$DA["pagada"];
			$tipo_cuota=$DA["tipo"];
			$situacion_financiera=$DA["situacion_financiera"];
			$jornada_alumno=$DA["jornada"];
			
			$SUMA_TOTAL+=$deudaXletra;
			
			switch($pagada)
			{
				case"N":
					$condicion_label="pendiente";
					break;
				case"A":
					$condicion_label="abonada";
					break;
				case"S":
					$condicion_label="pagada";		
					break;
			}
	
			
			if($primera_vuelta)
			{
				$id_alumno_old=$id_alumno;
				$primera_vuelta=false;
				$cuenta_alumno=1;
			}
				
			if($id_alumno_old!=$id_alumno)
			{
				$cuenta_alumno++;
				$id_alumno_old=$id_alumno;
				$cuenta_cuotas=1;
				$imprimir_subtotal=true;
			}
			else
			{
				$cuenta_cuotas++;
				$imprimir_subtotal=false;
			}
			
			if($mostrar_subtotales)
			{
				if($imprimir_subtotal)
				{
				echo'<tr>
					<td colspan="2"><strong>Subtotal</strong></td>
					<td colspan="'.($columnas-3).'" align="right"><strong>$'.number_format($aux_subtotal_parcial,0,",",".").'</strong></td>
					<td>&nbsp;</td>
					</tr>';
					$aux_subtotal_parcial=$deudaXletra;
				}
				else
				{
					$aux_subtotal_parcial+=$deudaXletra;
				}
			}
			
			
			//*******************************************************//
				echo'<tr>
				<td>'.$aux.'</td>
				<td>'.$rut.'</td>
				<td>'.$nombre.'</td>
				<td>'.$apellido_P.'</td>
				<td>'.$apellido_M.'</td>';
			if($id_carrera==0)
			{ echo'<td>'.$carrera_alumno.'</td>';}
			echo'<td>'.$id_letra.'</td>
				<td>'.$aux_year_letras.'</td>
				<td>'.fecha_format($fecha_vence).'</td>
				<td>$'.number_format($valor,0,",",".").'</td>
				<td>$'.number_format($deudaXletra,0,",",".").'</td>
				<td>'.$condicion_label.'</td>
				</tr>';
			//********************************************************//
			
		}//fin while
		//solo para caso final
		//-----------------------------------------------//
		if($mostrar_subtotales)
		{
		if(1==1)
			{
			echo'<tr>
				<td colspan="2"><strong>Subtotal</strong></td>
				<td colspan="'.($columnas-3).'" align="right"><strong>$'.number_format($aux_subtotal_parcial,0,",",".").'</strong></td>
				<td>&nbsp;</td>
				</tr>';
			}
		}
		//-----------------------------------------------//
		//fin solo caso final
			echo'<tr>
					<td colspan="2"><strong>TOTAL</strong></td>
					<td colspan="'.($columnas-3).'" align="right"><strong>$'.number_format($SUMA_TOTAL,0,",",".").'</strong></td>
					<td>&nbsp;</td>
				</tr>';
	}
	else
	{
		if(DEBUG){ echo"No seleccionados";}
		echo'<tr>
		<td colspan="'.$columnas.'">Sin Alumnos con deuda a la fecha seleccionada</td>
	
		 </tr>';
		
	}
}
else
{
	echo"No DATOS<br>";
}
?>
</tbody>
<tfoot>
<tr>
	<td colspan="<?php echo $columnas;?>"><?php echo $cuenta_alumno ;?> Alumno(s) con Deuda(s) a la fecha seleccionada </td>
</tr>
</tfoot>
  </table>
</div> 

</body>
</html>