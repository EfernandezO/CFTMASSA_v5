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

if(isset($_GET["id_contrato"]))
{
	if(is_numeric($_GET["id_contrato"]))
	{ $continuar=true; $id_contrato=$_GET["id_contrato"];}
	else{ $continuar=false; $id_contrato=0;}
}
else
{ $continuar=false; $id_contrato=0;}
//---------------------------------------------------//
if($continuar)
{
	require("../../../../funciones/conexion_v2.php");	
	$cons="SELECT folio_pagare FROM contratos2 WHERE id='$id_contrato' LIMIT 1";
	$sqli=$conexion_mysqli->query($cons) or die($conexion_mysqli->error);
		$DF=$sqli->fetch_assoc();
		$folio_pagare_actual=$DF["folio_pagare"];
	$sqli->free();	
	mysql_close($conexion);
	$conexion_mysqli->close();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>folio pagare</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 162px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:18px;
	z-index:2;
	left: 30%;
	top: 318px;
	text-align: center;
}
#apDiv3 {
	position:absolute;
	width:50%;
	height:31px;
	z-index:3;
	left: 25%;
	top: 366px;
}
</style>
<script language="javascript">
function CONFIRMAR()
{
	<?php if($continuar){?>
	folio_pagare=document.getElementById('folio_pagare').value;
	continuar=true;
	
	if((folio_pagare=="")||(folio_pagare==" "))
	{
		continuar=false;
		alert('Ingrese Folio Pagare');
	}
	
	if(continuar)
	{
		c=confirm('Seguro(a) Desea Continuar..?');
		if(c){ document.getElementById('frm').submit();}
	}
	<?php }else{?>
	alert('Sin Folio de Contrato...');
	<?php }?>
}
</script>
</head>

<body>
<div id="apDiv2">Ingrese el Folio que tiene el pagare que utilizar&aacute;.</div>
<h1 id="banner">Contrato - Folio Pagar&eacute;</h1>
<div id="apDiv1">
<form action="folio_pagare_2.php" method="post" enctype="multipart/form-data" id="frm">
  <table width="60%" border="1" align="center">
  <thead>
    <tr>
      <th colspan="2">Folio Pagare</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="38%">N&deg;</td>
      <td width="62%"><label for="folio_pagare"></label>
        <input name="folio_pagare" type="text" id="folio_pagare" value="<?php echo $folio_pagare_actual;?>" /></td>
    </tr>
    <tr>
      <td>id Contrato</td>
      <td><?php echo $id_contrato;?><input name="id_contrato" type="hidden" value="<?php echo $id_contrato;?>" /></td>
    </tr>
    <tr>
      <td colspan="2"><a href="#" class="button_R" onclick="CONFIRMAR();">Continuar</a></td>
    </tr>
    </tbody>
  </table>
  </form>
</div>
<div id="apDiv3">
<?php
if(isset($_GET["error"]))
{
	$error=$_GET["error"];
	$img_error='<img src="../../../BAses/Images/b_drop.png" width="16" height="16" alt="error" />';
	switch($error)
	{
		case"1":
			$msj="Folio Pagare Incorrecto";
			$img=$img_error;
			break;
		case"2":
			$msj="Folio Ya Utilizado anteriormente";	
			$img=$img_error;
			break;
	}
	
	echo $img.$msj;
}
?>
</div>
</body>
</html>