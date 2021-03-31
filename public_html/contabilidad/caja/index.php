<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
//-----------------------------------------//	

define("DEBUG",false);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-3" />
<title>Caja</title>
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
	top: 77px;
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
</head>
<?php
	require("../../../funciones/conexion_v2.php");
	
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	switch($privilegio)
	{
		case "admi_total":
			$cons="SELECT DISTINCT(cod_user) FROM pagos";
			$sql=$conexion_mysqli->query($cons);
			while($D=$sql->fetch_row())
			{
				$cod_user=$D[0];
				$cons="SELECT nombre, apellido FROM personal WHERE id='$cod_user'";
				$sqlX=$conexion_mysqli->query($cons);	
				$DA=$sqlX->fetch_assoc();
				$nombre=$DA["nombre"];
				$apellido=$DA["apellido"];
				$user=$nombre." ".$apellido;
				
				$array_usuario_codigo[]=$cod_user;
				$array_usuario_user[]=$user;
				$sqlX->free();	
			}
			$sql->free();
			break;
		case"inspeccion":
			$cons="SELECT DISTINCT(cod_user) FROM pagos";
			$sql=$conexion_mysqli->query($cons);
			while($D=$sql->fetch_row())
			{
				$cod_user=$D[0];
				$cons="SELECT nombre, apellido FROM personal WHERE id='$cod_user'";
				$sqlX=$conexion_mysqli->query($cons);
				$DA=$sqlX->fetch_assoc();
				$nombre=$DA["nombre"];
				$apellido=$DA["apellido"];
				$user=$nombre." ".$apellido;
				
				$array_usuario_codigo[]=$cod_user;
				$array_usuario_user[]=$user;
				$sqlX->free();
			}
			$sql->free();
			break;
			
		default:
			$cod_user=$_SESSION["USUARIO"]["id"];
			$cons="SELECT nombre, apellido FROM personal WHERE id='$cod_user'";
				$sqlX=$conexion_mysqli->query($cons);
				$DA=$sqlX->fetch_assoc();
				$nombre=$DA["nombre"];
				$apellido=$DA["apellido"];
				$user=$nombre." ".$apellido;	
				
				$array_usuario_codigo[]=$cod_user;
				$array_usuario_user[]=$user;
				$sqlX->free();
	}		
	//var_export($array_usuario_codigo);

$conexion_mysqli->close();
?>
<body>
<h1 id="banner">Administrador - Informe Caja </h1>
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
<form action="caja.php" method="post" name="frm" id="frm">
  <table width="50%" border="0" align="center">
  <thead>
    <tr>
      <th colspan="2"> Parametros Para Generar Caja</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="54%">Usuario</td>
      <td width="46%">
      
      <select name="usuario" id="usuario">
      <?php
	  $c=count($array_usuario_codigo);
      for($i=0;$i<$c;$i++)
	  {
	  	$cod=$array_usuario_codigo[$i];
		$user=$array_usuario_user[$i];
			echo'<option value="'.$cod.'">'.$cod.' - '.$user.'</option>';
	  }
	  ?>
      </select>      </td>
    </tr>
    <tr>
      <td>Fecha Inicio</td>
      <td><input  name="fecha_ini" id="fecha_ini" size="15" maxlength="10" readonly="true" value="<?php echo date("Y-m-d");?>"/>
        <input type="button" name="boton1" id="boton1" value="..." /></td>
    </tr>
    <tr>
      <td>Fecha Fin</td>
      <td><input  name="fecha_fin" id="fecha_fin" size="15" maxlength="10" readonly="true" value="<?php echo date("Y-m-d");?>"/>
        <input type="button" name="boton2" id="boton2" value="..." /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="button" id="button" value="continuar" /></td>
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
