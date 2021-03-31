<?php 
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="inspeccion";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
//-----------------------------------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Deudores de Mensualidades</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<script src="../../libreria_publica/JSCal/src/js/jscal2.js"></script>
<script src="../../libreria_publica/JSCal/src/js/lang/es.js"></script>
 
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/steel/steel.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:494px;
	height:219px;
	z-index:1;
	left: 52px;
	top: 98px;
}
#link {
	text-align: right;
	padding-right: 10px;
}
#apDiv2 {
	position:absolute;
	left:5%;
	top:107px;
	width:90%;
	height:200px;
	z-index:1;
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
#apDiv3 {
	position:absolute;
	width:200px;
	height:33px;
	z-index:2;
	left: 603px;
	top: 128px;
}
#apDiv4 {
	position:absolute;
	width:40%;
	height:36px;
	z-index:2;
	left: 30%;
	top: 538px;
	text-align: center;
}
-->
</style>
</head>
<?php
$fecha_actual=date("Y-m-d");
?>
<body>
<h1 id="banner">Administrador - Informe Cuotas Adeudadas</h1>
<?php
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	switch($privilegio)
	{
		case"admi_total":
			$url_menu="../index.php";
			break;
		case"inspeccion":
			$url_menu="../../Administrador/menu_inspeccion/index.php";
			break;	
			
	}
?>
<div id="link"><br />
<a href="<?php echo $url_menu;?>" class="button">Volver al menu</a></div>
<div id="apDiv2">
<form action="informe_deudores_mensualidad.php" method="post" name="frm" id="frm">
  <table width="50%" border="0" align="center">
  <thead>
    <tr>
      <th colspan="3"><strong>Parametros de Busqueda para Generaci&oacute;n</strong></th>
    </tr>
    </thead>
    </tbody>
    <tr>
      <td width="29%">Sede</td>
      <td colspan="2"><span class="Estilo2 Estilo2">
        <?php
	  include("../../../funciones/funcion.php");
	  echo selector_sede(); 
	  ?>
      </span></td>
    </tr>
    <tr>
      <td>Carrera</td>
      <td colspan="2"><span class="Estilo2 Estilo2">
        <select name="carrera" id="carrera">
          <?php 
    include("../../../funciones/conexion.php");
   $res="SELECT id, carrera FROM carrera";
   $result=mysql_query($res);
   while($row = mysql_fetch_array($result)) 
   {
	   $id_carrera=$row["id"];
    	$nomcar=$row["carrera"]; 
		echo'<option value="'.$id_carrera.'_'.$nomcar.'" selected="selected">'.$nomcar.'</option>';
    }
    mysql_free_result($result); 
    mysql_close($conexion); 
	?>
    	<option value="0_todas" selected="selected">Todas</option>
        </select>
      </span></td>
    </tr>
    <tr>
      <td>Nivel</td>
      <td colspan="2"><select name="nivel" id="nivel">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
         <option value="todos" selected="selected">Todos</option>
      </select>      </td>
    </tr>
    <tr>
      <td>Jornada</td>
      <td colspan="2"><select name="jornada" id="jornada">
        <option value="D">Diurno</option>
        <option value="V">Vespertino</option>
        <option value="todas" selected="selected">Todas</option>
      </select>
      </td>
    </tr>
    <tr>
      <td>Fecha Corte</td>
      <td colspan="2"><input  name="fecha_corte" id="fecha_corte" size="10" maxlength="10" value="<?php echo $fecha_actual; ?>" readonly="true"/>
        <input type="button" name="boton" id="boton" value="..." /></td>
    </tr>
    <tr>
      <td>A&ntilde;o Corresponden cuotas</td>
      <td colspan="2"><span class="Estilo2 Estilo2">
        <select name="year_letras" id="year_letras">
          <?php
				$año_actual=date("Y");
				$año_ini=$año_actual-10;
				$año_fin=$año_actual+1;
            	for($a=$año_ini;$a<=$año_fin;$a++)
				{
						if($a==$año_actual)
						{
							echo'<option value="'.$a.'" selected="selected">'.$a.'</option>';
						}
						else
						{
							echo'<option value="'.$a.'" >'.$a.'</option>';
						}
				}
			?>
            <option value="Todos">Todos</option>
        </select>
      </span></td>
    </tr>
    <tr>
      <td>Situacion Financiera Actual</td>
      <td colspan="2"><label for="situacion_financiera"></label>
        <select name="situacion_financiera" id="situacion_financiera">
          <option value="V">Al dia</option>
          <option value="M">Moroso</option>
          <option value="todos" selected="selected">Todos</option>
        </select></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td>Mostrar Subtotales</td>
      <td width="11%"><input name="mostrar_subtotales" type="radio" id="radio" value="ON" checked="checked" />
        <label for="mostrar_subtotales">Si</label></td>
      <td width="60%"><input type="radio" name="mostrar_subtotales" id="radio2" value="OFF" />
        No</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2"><div align="right">
        <input type="submit" name="button" id="button" value="Consultar" />
        </div></td>
    </tr>
    </tbody>
  </table>
</form>  
</div>
<div id="apDiv4">Lista Cuotas Adeudas segun los parametros seleccionados y permite la generacion de un archivo .xls</div>
<script type="text/javascript">//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton", "fecha_corte", "%Y-%m-%d");
    //]]></script>
</body>
</html>
