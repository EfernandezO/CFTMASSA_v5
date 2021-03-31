<?php
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="inspeccion";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
	define("DEBUG",true)
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Deudores Mensualidad</title>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:767px;
	height:37px;
	z-index:1;
	left: 34px;
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
	$carrera=$_POST["carrera"];
	$nivel=$_POST["nivel"];
	$fecha_corte=$_POST["fecha_corte"];
	$detalle=$_POST["detalle"];
	$jornada=$_POST["jornada"];
}
elseif($_GET)
{
		$sede=base64_decode($_GET["sede"]);
		$carrera=base64_decode($_GET["carrera"]);
		$nivel=base64_decode($_GET["nivel"]);
		$fecha_corte=base64_decode($_GET["fecha_corte"]);
		$detalle=base64_decode($_GET["detalle"]);
		$jornada=base64_decode($_GET["jornada"]);
}
	include("../../../funciones/funcion.php");
?>
<body>
<h1 id="banner">Administrador - Deudores</h1>
<div id="link"><br /> 
  <a href="index.php">Volver a Seleccion</a></div>
<div id="apDiv1">
<form action="" method="post" name="frm" id="frm">
  <table width="100%" border="0">
    <tr>
      <td colspan="11"><div align="center"><strong>Alumnos Con Deuda, Fecha Corte <?php echo fecha_format($fecha_corte);?><br />
          <?php echo $carrera;?>, Nivel <?php echo $nivel;?>, Jornada <?php echo $jornada;?><br />
          <?php echo $sede;?></strong>
          <input type="hidden" name="sede" id="sede"  value="<?php echo $sede;?>"/>
          <input type="hidden" name="carrera" id="carrera"  value="<?php echo $carrera;?>"/>
          <input name="nivel" type="hidden" id="nivel" value="<?php echo $nivel;?>" />
          <input name="grupo" type="hidden" id="grupo" value="<?php echo $grupo;?>" />
          <input name="fecha_corte" type="hidden" id="fecha_corte" value="<?php echo $fecha_corte;?>" />
           <input name="detalle" type="hidden" id="detalle" value="<?php echo $detalle;?>" />
           <input type="hidden" name="jornada" id="jornada"  value="<?php echo $jornada;?>"/>
           <br />
           <a href="<?php echo $url_reset;?>"></a></div></td>

    </tr>
<?php
if(($_POST)or($_GET))
{
	
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
		
	$consX="SELECT alumno.id, alumno.rut, alumno.nombre, alumno.apellido, alumno.apellido_P, alumno.apellido_M, alumno.carrera, alumno.nivel, alumno.grupo, alumno.situacion_financiera, alumno.jornada, letras.fechavenc, letras.valor, letras.deudaXletra, letras.ano, letras.semestre, letras.pagada, letras.tipo FROM alumno INNER JOIN letras ON alumno.id = letras.idalumn WHERE alumno.carrera='$carrera' AND alumno.sede='$sede' AND alumno.situacion='V' AND $condicion_nivel $condicion_jornada letras.fechavenc <='$fecha_corte' AND NOT(letras.pagada='S') ORDER BY alumno.id, letras.fechavenc";
	
	if(DEBUG){ echo "$consX<br><br>";}
	$sql=mysql_query($consX)or die("consX ".mysql_error());
	$num_seleccionados=mysql_num_rows($sql);
	if($num_seleccionados>0)
	{
		$old_id_alumno=0;
		$primera_vuelta=true;
		$cuenta_alumno=0;
		while($DA=mysql_fetch_assoc($sql))
		{
			$id_alumno=$DA["id"];
			$rut=$DA["rut"];
			$nombre=$DA["nombre"];
			$apellido=$DA["apellido"];
			$apellido_P=$DA["apellido_P"];
			$apellido_M=$DA["apellido_M"];
			$nivel=$DA["nivel"];
			$grupo=$DA["grupo"];
			$fecha_vence=$DA["fechavenc"];
			$valor=$DA["valor"];
			$deudaXletra=$DA["deudaXletra"];
			$ano=$DA["ano"];
			$semestre=$DA["semestre"];
			$pagada=$DA["pagada"];
			$tipo_cuota=$DA["tipo"];
			$situacion_financiera=$DA["situacion_financiera"];
			$jornada_alumno=$DA["jornada"];
			switch($situacion_financiera)
			{
				case"M":
					$situacion_financiera_label="Moroso";
					$img='<img src="../../BAses/Images/color_rojo.png" />';
					break;
				case"V":
					$situacion_financiera_label="Vigente";
					$img='<img src="../../BAses/Images/color_verde.png" />';
					break;
				default:
					$situacion_financiera_label="---";	
					$img='<img src="../../BAses/Images/color_amarillo.png" />';	
			}
			
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
			
			$apellido_new=$apellido_P." ".$apellido_M;
			if($apellido_new!=" ")
			{
				$alumno=$nombre." ".$apellido_new;
			}
			else
			{
				$alumno=$nombre." ".$apellido;
			}
			$alumno=ucwords(strtolower($alumno))."|$jornada_alumno|";
			//////////////////////////////////
			if($primera_vuelta)
			{
				$old_id_alumno=$id_alumno;
				$primera_vuelta=false;
				echo'<tr bgcolor="#e5e5e5">
				 <td><input name="id_alumno[]" type="checkbox" value="'.$id_alumno.'" '.$checked.' />('.$id_alumno.')</td>
				 <td>'.$rut.'</td>
				 <td colspan="8">'.$alumno.'</td>
				 <td align="center">'.$img.'</td>
				 </tr>';
				 if($ver_detalle)
				 {
					 echo'<tr align="center">
					<td colspan="4">&nbsp;</td>
					 <td>Vencimiento</td>
					 <td>Valor</td>
					 <td>Deuda</td>
					 <td>Semestre</td>
					 <td>Año</td>
					 <td>Condicion</td>
					  <td>Tipo</td>
					 </tr>';
				 }
				 $cuenta_alumno++;
			}
			if($old_id_alumno!=$id_alumno)
			{
				$total_pagado=$aux_valor - $aux_deuda;
				echo'
				<tr>
					<td colspan="11" align="right">Total Cuotas('.$cuenta_cuota.')-> $'.number_format($aux_valor,0,",",".").' Total Deuda-> $'.number_format($aux_deuda,0,",",".").' Total Pagado -> $'.number_format($total_pagado,0,",",".").'</td>
					</tr>
				<tr bgcolor="#e5e5e5">
				 <td><input name="id_alumno[]" type="checkbox" value="'.$id_alumno.'" '.$checked.' />('.$id_alumno.')</td>
				 <td>'.$rut.'</td>
				 <td colspan="8">'.$alumno.'</td>
				 <td align="center">'.$img.'</td>
				 </tr>';
					$old_id_alumno=$id_alumno;
					$aux_valor=0;
					$aux_deuda=0;
					$cuenta_cuota=0;
					if($ver_detalle)
					{
						 echo'<tr align="center">
						<td colspan="4">&nbsp;</td>
						 <td>Vencimiento</td>
						 <td>Valor</td>
						 <td>Deuda</td>
						 <td>Semestre</td>
						 <td>Año</td>
						 <td>Condicion</td>
						  <td>Tipo</td>
						 </tr>';
				  	}
				  $cuenta_alumno++;
			}
			//////////////////////////////////
			if($ver_detalle)
			{
			
			echo"<tr align=\"center\" style=\"background-color:$color\" onMouseOver=\"this.style.backgroundColor='$color3'\" onMouseOut=\"this.style.backgroundColor='$color'\" >";	
			echo'
				<td colspan="4">&nbsp;</td>
				 <td><em>'.fecha_format($fecha_vence).'</em></td>
				 <td><em>'.number_format($valor,0,",",".").'</em></td>
				 <td><em>'.number_format($deudaXletra,0,",",".").'</em></td>
				 <td><em>'.$semestre.'</em></td>
				 <td><em>'.$ano.'</em></td>
				 <td><em>'.$condicion_label.'</em></td>
				 <td><em>'.$tipo_cuota.'</em></td>
				 </tr>';
			}	 
				$cuenta_cuota++;
				 $aux_valor+=$valor;
				 $aux_deuda+=$deudaXletra;
		}
		
		$total_pagado=$aux_valor - $aux_deuda;
		echo'<tr>
				<td colspan="11" align="right">Total Cuotas('.$cuenta_cuota.')-> $'.number_format($aux_valor,0,",",".").' Total Deuda-> $'.number_format($aux_deuda,0,",",".").' Total Pagado -> $'.number_format($total_pagado,0,",",".").'</td>
				</tr>
			<tr>';
	}
	else
	{
		if(DEBUG){ echo"No seleccionados";}
	}
	echo'<tr>
		<td colspan="11">'.$cuenta_alumno.' Alumno(s) con Deuda(s) a la fecha seleccionada</td>
	
		 </tr>';
}
else
{
	echo"No post<br>";
}
?>
  </table>
 <div id="reset">
  <div align="right">
    <p>&nbsp;</p>
  </div>
</div>
</form>
</div> 

</body>
</html>
