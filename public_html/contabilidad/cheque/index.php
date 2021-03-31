<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define("DEBUG", false);
//-----------------------------------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Busca cheque</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">

<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 113px;
}
-->
</style>
<script src="../../libreria_publica/JSCal/src/js/jscal2.js"></script>
<script src="../../libreria_publica/JSCal/src/js/lang/es.js"></script>

<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/steel/steel.css">
<style type="text/css">
<!--
.Estilo1 {
	font-size: 12px;
	font-style: italic;
}
.Estilo2 {font-size: 12px}
.Estilo3 {
	font-size: 12px;
	font-weight: bold;
}
-->
#link {
	text-align: right;
	padding-right: 10px;
}
</style>
</head>
<?php
$array_bancos=array("Estado","Chile","Santander","Internacional","Scotiabank","Credito e Inversiones","Corpbanca","Bice","HSBC Bank","Itau","Security","Falabella","Ripley","Consorcio","Penta","Paris");
 sort($array_bancos);
?>
<body>
<h1 id="banner">Administrador - Finanzas Cheques</h1>
<?php
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	switch($privilegio)
	{
		case"inspeccion":
			$url="../../Administrador/menu_inspeccion/index.php";
			break;
		default:
			$url="../index.php";	
	}
?>
<div id="link"><br />
<a href="<?php echo $url;?>" class="button">Volver al Menu</a></div>
<div id="apDiv1">
<form action="busca_cheque.php" method="post" name="frm" id="frm">
  <table width="50%" border="0" align="center">
  	<thead>
    <tr>
      <td colspan="2" bgcolor="#EBE5D9"><span class="Estilo3">Cheque</span></td>
    </tr>
    </thead>
    <tbody>
    <tr class="odd">
      <td rowspan="2" bgcolor="#F7F4EE">Buscar </td>
      <td bgcolor="#F7F4EE"><input name="opcion_cheque" type="radio" id="buscar" value="recepcion" checked="checked" />
        Cheque Recibido Entre intervalo de fechas</td>
    </tr>
    <tr class="odd">
      <td bgcolor="#F7F4EE"><input type="radio" name="opcion_cheque" id="buscar2" value="vencimiento" />
        Cheque con vencimiento entre intervalo de fechas</td>
    </tr>
    <tr class="odd">
      <td bgcolor="#F7F4EE">&nbsp;</td>
      <td bgcolor="#F7F4EE">&nbsp;</td>
    </tr>
    <tr class="odd">
      <td width="25%" bgcolor="#F7F4EE"><span class="Estilo1">Fecha Inicio</span></td>
      <td width="75%" bgcolor="#F7F4EE"><input  name="fecha_inicio" id="fecha_inicio" size="15" maxlength="10" readonly="true" value="<?php echo date("Y-m-d");?>"/>
        <input type="button" name="boton1" id="boton1" value="..." /></td>
    </tr>
    <tr class="odd">
      <td bgcolor="#F7F4EE"><span class="Estilo1">Fecha Fin</span></td>
      <td bgcolor="#F7F4EE"><input  name="fecha_fin" id="fecha_fin" size="15" maxlength="10" readonly="true" value="<?php echo date("Y-m-d");?>"/>
        <input type="button" name="boton2" id="boton2" value="..." /></td>
    </tr>
    <tr class="odd">
      <td bgcolor="#F7F4EE"><span class="Estilo2">Banco</span></td>
      <td bgcolor="#F7F4EE"><select name="cheque_banco" id="cheque_banco">
      		<option value="Todos">Todos</option>
         <?php 
		 foreach($array_bancos as $n)
		 {
				echo'<option value="'.$n.'">'.$n.'</option>';	
		 }
		 ?>
        </select></td>
    </tr>
    <tr class="odd">
      <td height="22" bgcolor="#F7F4EE"><span class="Estilo2">Sede</span></td>
      <td bgcolor="#F7F4EE"><?php
	  include("../../../funciones/funcion.php");
	  echo selector_sede(); 
	  ?></td>
    </tr>
    </tbody>
    <tfoot>
    <tr>
      <td colspan="2" bgcolor="#EBE5D9"><div align="right">
        <input type="submit" name="button" id="button" value="Consultar" />
      </div></td>
      </tr>
    </tfoot>
  </table>
 </form> 
</div>

</body>
 <script type="text/javascript">//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton1", "fecha_inicio", "%Y-%m-%d");
	   cal.manageFields("boton2", "fecha_fin", "%Y-%m-%d");

    //]]></script>
</html>
