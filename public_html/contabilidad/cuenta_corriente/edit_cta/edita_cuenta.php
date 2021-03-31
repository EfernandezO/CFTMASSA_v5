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
if($_GET)
{
	$id_cta=$_GET["id"];
	if(is_numeric($id_cta))
	{
		include("../../../../funciones/conexion.php");
		
		$consB1="SELECT * FROM cuenta_corriente WHERE id='$id_cta' LIMIT 1";
		
		$sql1=mysql_query($consB1)or die(mysql_error());
	
		$DC=mysql_fetch_assoc($sql1);
		
		///-----datos cuenta-----//
				$banco=$DC["banco"];
				$titular=$DC["titular"];
				$num_cuenta=$DC["num_cuenta"];
		//----------------------//
		
		mysql_free_result($sql1);
		mysql_close($conexion);
		
	}
	else
	{
		//id invalido
		header("location: ../listador.php");
	}
}
else
{
	header("location: ../listador.php");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Edita Cta Cte</title>
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
<style type="text/css">
<!--
.Estilo2 {font-size: 12px}
-->
</style>
</head>
<body>
<h1 id="banner">Edicion Cuenta Corriente</h1>
<div id="link"><br />
<a href="../listador_cuentas.php" class="button">
Volver a Menu Cta. Cte</a></div>
<div id="container">
  <div id="leftSide">
    <form action="edita_cuenta2.php" method="post" name="frm" class="form" id="frm">
    <fieldset>
        <legend>Cuenta Corriente</legend>
        <label for="password">Banco</label>
        <div class="div_texbox">
          <select name="banco" id="banco">
     <?php 
		 foreach($array_bancos as $n)
		 {
		 	if($n==$banco)
			{echo'<option value="'.$n.'" selected="selected">'.$n.'</option>';}
			else
			{echo'<option value="'.$n.'">'.$n.'</option>';}	
		 }
		 ?>
         </select>
          <input name="id_cta" type="hidden" id="id_cta" value="<?php echo $id_cta;?>" />
        </div>
        <label for="password">Cta. Cte.</label>
        <div class="div_texbox">
          <input name="cta_cte" class="textbox" id="cta_cte" type="text" value="<?php echo $num_cuenta;?>"/>
        </div>
        <label for="password">Titular</label>
        <div class="div_texbox">
          <input name="titular" class="textbox" id="titular" type="text" value="<?php echo $titular;?>"/>
        </div>
        <div class="clear"></div>
        <div class="button_div">
          <input name="Submit" value="Modificar" class="buttons" type="button" onclick="Confirmar();" />
        </div>
      </fieldset>
      <hr size="1" />
    </form>
    <div class="clear"></div>
  </div>
  <div id="rightSide">
    <p><u>Modificar Cuenta Corriente</u><u><br />
    </u></p>
</div>
  <div class="clear"></div>
</div>
<div class="clear"></div>
</body></html>