<?php 
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Matriculas_generadas_X_rango_F_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Informe Contratos -  Matriculas</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 93px;
}
#link {
	text-align: right;
	padding-right: 10px;
}
-->
</style>
   <script src="../../libreria_publica/JSCal/src/js/jscal2.js"></script>
 <script src="../../libreria_publica/JSCal/src/js/lang/es.js"></script>
 
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/steel/steel.css">
<style type="text/css">
#apDiv2 {
	position:absolute;
	width:40%;
	height:38px;
	z-index:2;
	left: 30%;
	top: 350px;
	text-align: center;
}
</style>
</head>
<body>
<div id="apDiv2">Muestra Matriculas Generadas durante el periodo de tiempo<br />
  establecido, considerando solamente las que se encuentren condicion<br />
  'ok' o 'retirado'.
</div>
<h1 id="banner">Administrador - Informe Contratos </h1>
<?php
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	switch($privilegio)
	{
		case"inspeccion":
			$url="../../Administrador/menu_inspeccion/index.php";
			break;
		case"externo":
			$url="../../Administrador/menu_externos/index.php";
			break;
		default:
			$url="../index.php";	
	}
?>
<div id="link"><br />
<a href="<?php echo $url;?>" class="button">Volver al Menu</a></div>
<div id="apDiv1">
<form action="informe_contratos.php" method="post" name="frm" id="frm">
  <table width="40%" border="0" align="center">
  <thead>
    <tr>
      <th colspan="2"> Parametros Para Generar informe</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td >Sede</td>
      <td ><?php
	  include("../../../funciones/funcion.php");
	  echo selector_sede("fsede","",true,false,false);
	  ?>
      </td>
    </tr>
    <tr>
      <td width="54%" >Nivel</td>
      <td width="46%" ><select name="nivel" id="nivel">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="Todos" selected="selected">Todos</option>
                  </select></td>
    </tr>
    <tr>
      <td >A&ntilde;o Ingreso</td>
      <td ><span class="Estilo2 Estilo2">
        <select name="year_ingreso" id="year_ingreso">
          <?php
				$año_actual=date("Y");
				$año_ini=$año_actual-10;
				$año_fin=$año_actual+1;
            	for($a=$año_ini;$a<=$año_fin;$a++)
				{
						if($a==$año_actual)
						{
							echo'<option value="'.$a.'">'.$a.'</option>';
						}
						else
						{
							echo'<option value="'.$a.'" >'.$a.'</option>';
						}
				}
			?>
            <option value="Todos" selected="selected">Todos</option>
        </select>
      </span></td>
    </tr>
    <tr>
      <td >Fecha Inicio</td>
      <td ><input  name="fecha_ini" id="fecha_ini" size="15" maxlength="10" readonly="true" value="<?php echo date("Y-m-d");?>"/>
        <input type="button" name="boton1" id="boton1" value="..." /></td>
    </tr>
    <tr>
      <td >Fecha Fin</td>
      <td ><input  name="fecha_fin" id="fecha_fin" size="15" maxlength="10" readonly="true" value="<?php echo date("Y-m-d");?>"/>
        <input type="button" name="boton2" id="boton2" value="..." /></td>
    </tr>
    <tr>
      <td >&nbsp;</td>
      <td ><input type="submit" name="button" id="button" value="Consultar" /></td>
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
      cal.manageFields("boton1", "fecha_ini", "%Y-%m-%d");
	   cal.manageFields("boton2", "fecha_fin", "%Y-%m-%d");

    //]]></script>
</body>
</html>