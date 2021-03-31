<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//
$array_bancos=array("Estado","Chile","Santander","Internacional","Scotiabank","Credito e Inversiones","Corpbanca","Bice","BBVA","HSBC Bank","Itau","Security","Falabella","Ripley","Consorcio","Penta","Paris");
 sort($array_bancos);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Nueva Cta. Cte.</title>
<link rel="stylesheet" type="text/css" href="../../../CSS/style_formulario.css"/>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<script language="javascript">
function Confirmar()
{
	error=true;
	banco=document.getElementById('banco').value;
	titular=document.getElementById('titular').value;
	cta_cte=document.getElementById('cta_cte').value;
	if(banco=="")
	{
		alert('Ingrese Banco');
		error=false;
	}
	if(titular=="")
	{
		alert('Ingrese Titular de Cuenta');
		error=false;
	}
	if(cta_cte=="")
	{
		alert('Ingrese Cta. Cte');
		error=false;
	}
	if(error)
	{
		document.frm.submit();
	}	
}
</script>
</head><body>
<h1 id="banner">Nueva Cta. Cte.</h1>
<div id="link"><br />
<a href="../listador_cuentas.php" class="button">Volver a Menu Cta.Cte.</a></div>
<div id="container">
  <div id="leftSide">
  <form action="nva_ctacte2.php" method="POST" name="frm" class="form" id="frm">
  <fieldset>
<legend>Cuenta Corriente</legend>
  <label for="password">Banco</label>
    <div class="div_texbox">
    <select name="banco" id="banco">
     <?php 
		 foreach($array_bancos as $n)
		 {echo'<option value="'.$n.'">'.$n.'</option>';	}
		 ?>
         </select>
	</div>
	<label for="password">Cta. Cte.</label>
    <div class="div_texbox">
    <input name="cta_cte" class="textbox" id="cta_cte" type="text">
	</div>
	<label for="password">Titular</label>
    <div class="div_texbox">
    <input name="titular" class="textbox" id="titular" type="text">
	</div>
	<div class="clear"></div>
    <div class="button_div">
	<input name="Submit" value="Agregar" class="buttons" type="button" onclick="Confirmar();">
	</div>
	</fieldset>
	<hr size="1">
  </form>
<div class="clear"></div>
	
  </div>
  <div id="rightSide">
  <p><u>Agregar Cuenta Corriente</u><u><br />
  </u></p>
</div>
  <div class="clear"></div>
</div>
<div class="clear"></div>
</body></html>