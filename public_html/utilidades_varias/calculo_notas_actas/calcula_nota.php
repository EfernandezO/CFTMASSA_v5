<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	OKALIS($lista_invitados);
//-----------------------------------------//	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_3.css"/>
<title>calcula nota</title>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 86px;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:31px;
	z-index:2;
	left: 5%;
	top: 238px;
	font-size: larger;
	text-align: center;
}
-->
</style>
<script>
function FOCO()
{
	document.getElementById('n1').focus();
}
</script>
</head>

<body onload="FOCO();">
<h1 id="banner">Utilidades - Calculo Notas Actas.</h1>
<div id="link"><br><a href="../../Administrador/ADmenu.php" class="button">Volver al Menu</a></div>
<div id="apDiv1">
  <form action="calcula_nota.php" method="post" name="frm" id="frm">
  <table width="45%" border="1" align="center">
    <tr>
      <td width="49" rowspan="3">&nbsp;</td>
      <td width="54">N1(30%)</td>
      <td width="52">N2(35%)</td>
      <td width="117">N3(35%)</td>
    </tr>
    <tr>
      <td><input name="n1" type="text" id="n1" size="5" /></td>
      <td><input name="n2" type="text" id="n2" size="5" /></td>
      <td><input name="n3" type="text" id="n3" size="5" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="submit" name="button" id="button" value="calcular" /></td>
    </tr>
  </table>
  </form>
</div>

<div id="apDiv2">
<?php
if($_POST)
{
	$n1=$_POST["n1"];
	$n2=$_POST["n2"];
	$n3=$_POST["n3"];
	
	$n1=str_replace(",",".",$n1);
	$n2=str_replace(",",".",$n2);
	$n3=str_replace(",",".",$n3);
	
	$aux_n1=($n1*0.3);
	$aux_n2=($n2*0.35);
	$aux_n3=($n3*0.35);
	
	$NF=($aux_n1+$aux_n2+$aux_n3);
	
	
	echo"//--------------------------------------//<br>";
	echo"N1(30%) -> $aux_n1<br>";
	echo"N2(35%) -> $aux_n2<br>";
	echo"N3(35%) -> $aux_n3<br>";
	
	echo"Nota Final -> $NF<br>";
	echo"//--------------------------------------//<br>";
}
?></div>
</body>
</html>
