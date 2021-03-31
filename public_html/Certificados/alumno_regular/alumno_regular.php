<?php
//--------------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	
if(isset($_SESSION["AUX_CERTIFICADO"]))
{
	unset($_SESSION["AUX_CERTIFICADO"]);
}
?>
<html>
<head>
<title>Alumno Regular</title>
<?php include("../../../funciones/codificacion.php");?>

<style type="text/css">
<!--
body {
	background-color: #FFFFFF;
}
.Estilo2 {color: #0080C0}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
-->
</style>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<style type="text/css">
<!--
.Estilo5 {font-size: 12px; font-weight: bold; color: #FF0000; }
.Estilo7 {font-size: 12px}
.Estilo8 {font-size: 12px; font-weight: bold; }
#apDiv1 {	position:absolute;
	width:200px;
	height:115px;
	z-index:8;
	left: 594px;
	top: 78px;
}
#apDiv2 {
	position:absolute;
	width:20%;
	height:115px;
	z-index:8;
	left: 60%;
	top: 86px;
}
#apDiv3 {
	position:absolute;
	width:50%;
	height:39px;
	z-index:9;
	left: 5%;
	top: 283px;
}
-->
</style>
<script language="javascript">
function CONFIRMAR()
{
	c=confirm('Generar Certificado...?');
	if(c)
	{
		document.frm.submit();
	}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Certificado Alumno Regular</h1>
<div id="link"><br><a href="../../buscador_alumno_BETA/HALL/index.php" class="button">Volver al Menu</a> </div>
<div id="layer" style="position:absolute; left:5%; top:84px; width:50%; height:160px; z-index:7">
  <form action="verirut2.php" method="post"  enctype="multipart/form-data" name="frm"  id="frm">
    <table width="462" border="0">
    <thead>
      <tr>
        <th colspan="6" align="center" class="Estilo8" >Datos Para Certificado</th>
      </tr>
      </thead>
      <tbody>
      <tr class="odd">
        <td width="142"><span class="Estilo7">Firma</span></td>
        <td colspan="5"><select name="firma">
          <option value="OSVALDO ACEVEDO GUTIERREZ, Director Acad&eacute;mico" selected>OSVALDO ACEVEDO GUTIERREZ</option>
          <option value="JAIME A. AULADELL ALDANA, Rector">JAIME A. AULADELL ALDANA</option>
          <option value="NIBALDO E. BENAVIDES MORENO, Director">NIBALDO E. BENAVIDES MORENO</option>
        </select>        </td>
      </tr>
	  <tr class="odd">
	  <td><span class="Estilo7">Presentado a:
	  </span></td>
	  <td colspan="5"><input name="presentado" type="text" id="presentado" size="40"></td>
	  </tr>
	  <tr class="odd"> 
        <td width="142"><span class="Estilo7">Nivel(es)</span></td>
        <td colspan="5"><input type="text" name="nivel" id="nivel"></td>
        </tr>
      <tr class="odd">
        <td colspan="6"><input type="reset" name="Submit" value="Restablecer">          <input type="button" name="Submit2" value="Generar Certificado"  onClick="CONFIRMAR();"></td>
      </tr>
      </tbody>
    </table>
  </form>
</div>
<div id="apDiv2">
  <?php
  	$rut_alumno=$_SESSION["SELECTOR_ALUMNO"]["rut"];
	$carrera_alumno=$_SESSION["SELECTOR_ALUMNO"]["carrera"];
	$tipo_certificado="certificado de alumno regular";
  	include("../../../funciones/conexion.php");
	$cons="SELECT COUNT(id) FROM registro_certificados WHERE rut_alumno='$rut_alumno' AND carrera_alumno='$carrera_alumno' AND tipo_certificado='$tipo_certificado'";
	$sql=mysql_query($cons)or die(mysql_error());
	$C=mysql_fetch_row($sql);
	$numero_certificados=$C[0];
	if(empty($numero_certificados))
	{ $numero_certificados=0;}
	mysql_free_result($sql);
	mysql_close($conexion);
	echo"Se han Impreso ($numero_certificados) $tipo_certificado a este Alumno";
	
  ?>
</div>
<div id="apDiv3">
  <strong>
  <?php 
include("../../../funciones/conexion.php");
include("../../../funciones/funcion.php");
////////////////////////
$year_actual=date("Y");
$mes_actual=date("m");
if($mes_actual>8)
{ $semestre_actual=2;}
else{ $semestre_actual=1;}
$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
//////////////////////////
	$condicion_contrato=VERIFICA_CONTRATO($id_alumno, $year_actual, $semestre_actual);
	
	if($condicion_contrato)
	{
		$msj="Contrato OK";
	}
	else
	{
		$msj="Alumno, Sin Contrato o Caduco";
	}
mysql_close($conexion);
	echo $msj;
?> 
</strong></div>
</body>
</html>