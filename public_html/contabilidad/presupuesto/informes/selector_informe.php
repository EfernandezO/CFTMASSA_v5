<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="inspeccion";
	OKALIS($lista_invitados);
//-----------------------------------------//	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>seleccion de Informe</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">

<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:476px;
	height:115px;
	z-index:1;
	left: 208px;
	top: 137px;
}
-->
</style>

<script src="../../../libreria_publica/JSCal/src/js/jscal2.js"></script>
<script src="../../../libreria_publica/JSCal/src/js/lang/es.js"></script>
 
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/steel/steel.css">
<style type="text/css">
<!--
a:link {
	color: #006699;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #006699;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #006699;
}
-->
</style>
<script language="javascript">
function CAMBIA_DESTINO(tipo)
{
	formulario=document.getElementById('frm');
	switch(tipo)
	{
		case"dia":
			formulario.action="informe_dia/informe_dia.php";
			break;
		case"mes":
			formulario.action="informe_mes/informe_mes.php";
			break;
		case"year":		
			formulario.action="informe_year/informe_year.php";
			break;
	}
}
</script>
</head>
<body>
<h1 id="banner">Administrador- Presupuesto</h1>

<div id="link"><a href="../menu_presupuesto.php" class="Estilo2">Volver al Menu</a></div>
<div id="apDiv1">
  <form action="informe_year/informe_year.php" method="post" name="frm" id="frm">
    <table width="100%" border="0">
    <thead>
      <tr>
        <td colspan="2" bgcolor="#e5e5e5">&#9658;<strong>Parametros</strong></td>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td width="53%" bgcolor="#f5f5f5">Dia</td>
        <td width="47%" bgcolor="#f5f5f5"><input name="fecha_presupuesto" type="text" id="fecha_presupuesto" size="11" maxlength="10" value="<?php echo date("Y-m-d");?>" onchange="cargarMovimientos();" readonly="true"/>
          <input type="button" name="boton" id="boton" value="..."/></td>
      </tr>
      <tr>
        <td bgcolor="#f5f5f5">Sede</td>
        <td bgcolor="#f5f5f5"><select name="sede" id="sede">
          <option value="Talca">Talca</option>
          <option value="Linares">Linares</option>
        </select></td>
      </tr>
      <tr>
        <td colspan="2" bgcolor="#e5e5e5">&#9658;<strong>tipo</strong></td>
        </tr>
      <tr>
        <td bgcolor="#f5f5f5">Dia</td>
        <td bgcolor="#f5f5f5"><input type="radio" name="tipo" id="tipo" value="dia"  onclick="CAMBIA_DESTINO('dia');"/></td>
      </tr>
      <tr>
        <td bgcolor="#f5f5f5">Mes</td>
        <td bgcolor="#f5f5f5"><input type="radio" name="tipo" id="tipo2" value="mes" onclick="CAMBIA_DESTINO('mes');"/></td>
      </tr>
      <tr>
        <td bgcolor="#f5f5f5">A&ntilde;o</td>
        <td bgcolor="#f5f5f5"><input name="tipo" type="radio" id="tipo3" value="year" checked="checked" onclick="CAMBIA_DESTINO('year');"/></td>
      </tr>
      <tr>
        <td colspan="2" bgcolor="#f5f5f5"><div align="right">
          <input type="submit" name="button" id="button" value="Consultar" />
        </div>          <div align="right"></div></td>
        </tr>
      </tbody>
    </table>
  </form>
</div>

<script type="text/javascript">//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton", "fecha_presupuesto", "%Y-%m-%d");
		//cargarMovimientos();
    //]]></script>
</body>
</html>