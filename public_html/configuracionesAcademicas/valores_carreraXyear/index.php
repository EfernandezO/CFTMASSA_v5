<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Gestion_carreras_Mallas->ARANCELES_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<title>Graba valores carrera a&ntilde;o</title>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:200px;
	height:88px;
	z-index:1;
	left: 51px;
	top: 94px;
}
#Layer3 {	position:absolute;
	width:218px;
	height:48px;
	z-index:2;
	left: 39px;
	top: 16px;
}
#Layer2 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:3;
	left: 5%;
	top: 89px;
}
#Layer4 {
	position:absolute;
	width:113px;
	height:30px;
	z-index:3;
	left: 265px;
	top: 18px;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: underline;
}
a:active {
	text-decoration: none;
}
#apDiv1 {
	position:absolute;
	width:60%;
	height:36px;
	z-index:1;
	left: 5%;
	top: 427px;
}
#apDiv2 {
	position:absolute;
	width:30%;
	height:115px;
	z-index:2;
	left: 5%;
	top: 116px;
}
#apDiv3 {
	position:absolute;
	width:23%;
	height:29px;
	z-index:3;
	left: 50%;
	top: 183px;
	text-align: center;
}
-->
</style>
<script language="javascript">
function CONFIRMAR()
{
	continuar=true;
	
	arancel_1=document.getElementById('arancel_1').value;
	arancel_2=document.getElementById('arancel_2').value;
	matricula=document.getElementById('matricula').value;
	
	if((arancel_1=="")||(arancel_1==" "))
	{
		continuar=false;
		alert('Ingrese arancel 1');
	}
	if((arancel_2=="")||(arancel_2==" "))
	{
		continuar=false;
		alert('Ingrese arancel 2');
	}
	if((matricula=="")||(matricula==" "))
	{
		continuar=false;
		alert('Ingrese matricula');
	}
	if(continuar)
	{
		c=confirm('Seguro(a) desea Guardar los Cambios...?');
		if(c)
		{document.getElementById('frm').submit();}
	}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Arancel y Matricula de Carrera</h1>
<div id="link"><br />
<a href="../index.php" class="button">Volver al Men&uacute; </a>
</div>
<div id="apDiv1">
<table width="60%" align="left">
<thead>
	<tr>
	<th colspan="9">Valores Carrera</th>
    </tr>
    <tr>
    <td>N</td>
    <td>Año</td>
    <td>Matricula</td>
    <td>Arancel 1</td>
    <td>Arancel 2</td>
    <td>Total Arancel (anual)</td>
    <td>Permite Matricula Alumnos Nuevos</td>
    <td>Vacantes Diurnos</td>
    <td>Vacantes Vespertino</td>
</tr>
</thead>
<tbody>
<?php
if($_GET)
{
	require("../../../funciones/funcion.php");
	$id_carrera=str_inde($_GET["id_carrera"]);
	$sede=str_inde($_GET["sede"]);
	
	if(is_numeric($id_carrera))
	{
		if($id_carrera>0)
		{ $continuar=true;}
		else
		{ $continuar=false;}
	}
}
else
{ $continuar=false;}
//----------------------------------------------//

if($continuar)
{
	require("../../../funciones/conexion_v2.php");
	
	$cons="SELECT carrera FROM carrera WHERE id='$id_carrera' LIMIT 1";
	$sqli=$conexion_mysqli->query($cons);
	$D=$sqli->fetch_assoc();
		$carrera=$D["carrera"];
	$sqli->free();	
	
	$cons="SELECT * FROM hija_carrera_valores WHERE id_madre_carrera='$id_carrera' AND sede='$sede' ORDER by year DESC";
	$sqli=$conexion_mysqli->query($cons);
	$num_registros=$sqli->num_rows;
	if($num_registros>0)
	{
		$aux=0;
		while($DC=$sqli->fetch_assoc())
		{
			$aux++;
			$aux_arancel_1=$DC["arancel_1"];
			$aux_arancel_2=$DC["arancel_2"];
			$aux_matricula=$DC["matricula"];
			$aux_year=$DC["year"];
			$aux_vacantesDiurno=$DC["vacantesDiurno"];
			$aux_vacantesVespertino=$DC["vacantesVespertino"];
			$aux_permite_matricula_nuevos=$DC["permite_matricula_nuevos"];
			
			$permitirVacantesLabel="no";
			if($aux_permite_matricula_nuevos=="1"){$permitirVacantesLabel="si";}
			echo'<tr>
					<td>'.$aux.'</td>
					<td>'.$aux_year.'</td>
					<td>'.$aux_matricula.'</td>
					<td>'.$aux_arancel_1.'</td>
					<td>'.$aux_arancel_2.'</td>
					<td align="right">'.($aux_arancel_1+$aux_arancel_2).'</td>
					
					<td align="center">'.$permitirVacantesLabel.'</td>
					<td>'.$aux_vacantesDiurno.'</td>
					<td>'.$aux_vacantesVespertino.'</td>
				</tr>';
		}
	}
	else
	{ echo'<tr><td colspan="6">Sin Registros</td></tr>';}
	$sqli->free();
	$conexion_mysqli->close();
}
?>
</tbody>
</table>
</div>
<div id="apDiv2">
<form action="graba_valores_year.php" method="post" id="frm">
  <table width="100%" border="1" align="left">
  <thead>
    <tr>
      <th colspan="3"><input type="hidden" name="id_carrera" id="id_carrera" value="<?php echo $id_carrera;?>"/>
      <input type="hidden" name="sede" id="sede" value="<?php echo $sede;?>"/><?php echo "$carrera [$id_carrera] Sede: $sede";?></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>A&ntilde;o</td>
      <td colspan="2">
        <select name="year" id="year">
       	<?php
		$year_ini=2000;
		$year_max=date("Y")+10;
        for($x=$year_ini;$x<=$year_max;$x++)
		{
			if($x==date("Y"))
			{echo'<option value="'.$x.'" selected="selected">'.$x.'</option>';}
			else{ echo'<option value="'.$x.'">'.$x.'</option>';}
		}
		?>
        </select></td>
    </tr>
    <tr>
      <td>Permitir matricula de Alumnos Nuevos</td>
      <td><input name="permitirMatricula" type="radio" id="permitir" value="1" checked="checked" />
        <label for="permitirMatricula">Si</label></td>
      <td><input type="radio" name="permitirMatricula" id="permitir2" value="0" />
        No</td>
    </tr>
    <tr>
      <td width="45%">Arancel 1</td>
      <td width="55%" colspan="2"><label for="arancel_1"></label>
      <input type="text" name="arancel_1" id="arancel_1" /></td>
    </tr>
    <tr>
      <td>Arancel 2</td>
      <td colspan="2"><label for="arancel_2"></label>
      <input type="text" name="arancel_2" id="arancel_2" /></td>
    </tr>
    <tr>
      <td>Matricula</td>
      <td colspan="2"><label for="matricula"></label>
      <input type="text" name="matricula" id="matricula" /></td>
    </tr>
    <tr>
      <td>Numero de Vacantes Diurno</td>
      <td colspan="2"><input name="vacantesDiurno" type="text" id="vacantesDiurno" size="5" /></td>
    </tr>
    <tr>
      <td><p>Numero de Vacantes Vespertino</p></td>
      <td colspan="2"><input name="vacantesVespertino" type="text" id="vacantesVespertino" size="5" /></td>
    </tr>
    </tbody>
  </table>
  </form>
</div>
<div id="apDiv3"><a href="#" class="button_G" onclick="CONFIRMAR();">Guardar Cambios</a></div>
</body>
</html>