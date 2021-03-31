<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	$lista_invitados["privilegio"][]="inspeccion";
	OKALIS($lista_invitados);
//-----------------------------------------//	
?>
<?php
define("DEBUG",true);
$fecha_actual=date("Y-m-d");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<script src="../../libreria_publica/JSCal/src/js/jscal2.js"></script>
<script src="../../libreria_publica/JSCal/src/js/lang/es.js"></script>
 
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/steel/steel.css">
<title>Situacion Financiera Historica - Alumno</title>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:1;
	left: 461px;
	top: 103px;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:43px;
	z-index:1;
	left: 5%;
	top: 103px;
}
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
</head>
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
<body>
<h1 id="banner">Administrador -Estado financiero X Fecha</h1>
<div id="link"><br />
<a href="<?php echo $url;?>" class="button">Volver al Menu</a></div>
<div id="apDiv2">
<form action="estado_financiero_x_fecha_alumno.php" method="post" id="frm">
  <table width="60%" border="0" align="center">
    <tr>
      <th colspan="2" bgcolor="#e5e5e5">Ingrese los Parametros</th>
    </tr>
    <tr>
      <td bgcolor="#f5f5f5">Sede</td>
      <td bgcolor="#f5f5f5"><span class="Estilo2 Estilo2">
        <?php
	  include("../../../funciones/funcion.php");
	  echo selector_sede(); 
	  ?>
      </span></td>
    </tr>
    <tr>
      <td bgcolor="#f5f5f5">Carrera</td>
      <td bgcolor="#f5f5f5"><select name="carrera" id="carrera">
          <?php 
    include("../../../funciones/conexion.php");
   
   $res="SELECT carrera FROM carrera where id >= 0";
   $result=mysql_query($res);
   while($row = mysql_fetch_array($result)) 
   {
    	$nomcar=$row["carrera"]; 
		if($nomcar==$_POST["ocu_carrera"])
		{
			echo'<option value="'.$nomcar.'" selected="selected">'.$nomcar.'</option>';
		}
		else
		{
			echo'<option value="'.$nomcar.'">'.$nomcar.'</option>';
		}	
    }
    mysql_free_result($result); 
    mysql_close($conexion); 
	?>
    	<option value="todas">Todas</option>
        </select></td>
    </tr>
    <tr>
      <td bgcolor="#f5f5f5">Nivel</td>
      <td bgcolor="#f5f5f5"><select name="nivel" id="nivel">
        <option value="1" selected="selected">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="todos">Todos</option>
      </select></td>
    </tr>
    <tr>
      <td bgcolor="#f5f5f5">Jornada</td>
      <td bgcolor="#f5f5f5"><select name="jornada" id="jornada">
        <option value="D" selected="selected">Diurno</option>
        <option value="V">Vespertino</option>
        <option value="todas">Todas</option>
      </select></td>
    </tr>
    <tr>
      <td bgcolor="#f5f5f5">Fecha Corte</td>
      <td bgcolor="#f5f5f5"><input  name="fecha_corte" id="fecha_corte" size="10" maxlength="10" value="<?php echo $fecha_actual; ?>" readonly="readonly"/>
        <input type="button" name="boton" id="boton" value="..." /></td>
    </tr>
    <tr>
      <td bgcolor="#e5e5e5">&nbsp;</td>
      <td bgcolor="#e5e5e5"><input type="submit" name="button" id="button" value="consultar" /></td>
    </tr>
  </table>
 </form>
</div>
<script type="text/javascript">//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton", "fecha_corte", "%Y-%m-%d");
    //]]></script>
</body>
</html>