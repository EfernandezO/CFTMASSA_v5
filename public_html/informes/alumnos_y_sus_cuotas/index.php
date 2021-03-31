<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="inspeccion";
	OKALIS($lista_invitados);
//-----------------------------------------//	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Administrador - informe</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:90%;
	height:145px;
	z-index:1;
	left: 5%;
	top: 95px;
}
.Estilo1 {font-size: 12px}
#Layer2 {
	position:absolute;
	width:168px;
	height:16px;
	z-index:2;
	left: 420px;
	top: 49px;
}
#apDiv1 {
	position:absolute;
	width:40%;
	height:43px;
	z-index:2;
	left: 30%;
	top: 286px;
	text-align: center;
}
-->
</style>
</head>

<body>
<h1 id="banner">Administrador - Informe Alumnos Con Cuotas</h1>
<?php
$privilegio=$_SESSION["USUARIO"]["privilegio"];
switch($privilegio)
{
	case"inspeccion":
		$url="../../Administrador/menu_inspeccion/index.php";
		break;
	default:
		$url="../../contabilidad/index.php";	
}
?>
<div id="link"><br />
<a href="<?php echo $url;?>" class="button">Volver al menu</a><br />
</div>
<div id="Layer1">
<form action="alumno_y_sus_cuotas.php" method="post" name="frm" id="frm">
  <table width="50%" border="1" align="center">
  <caption></caption>
  <thead>
    <tr>
      <th colspan="2"><span class="Estilo1">Busqueda de Alumnos </span></th>
    </tr>
	</thead>
	<tbody>
    <tr class="odd">
      <td width="187"><span class="Estilo1">Sede</span></td>
      <td width="197"><?php
	  include("../../../funciones/funcion.php");
	  echo selector_sede(); 
	  ?></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo1">Carrera</span></td>
      <td>
	  <?php 
    include("../../../funciones/conexion.php");
   $res="SELECT id, carrera FROM carrera";
   $result=mysql_query($res);
   ?>
        <select name="carrera" id="carrera">
          <?php
   while($row = mysql_fetch_array($result)) 
   {
    $nomcar=$row["carrera"];
	$id_carrera=$row["id"];
    	echo'<option value="'.$id_carrera.'_'.$nomcar.'">'.$nomcar.'</option>';
   }
    mysql_free_result($result); 
    mysql_close($conexion);
	 ?>
        </select></td>
    </tr>
    <tr class="odd">
      <td>ver los alumnos cuya cuota esten:</td>
      <td><label for="ver_cuotas"></label>
        <select name="ver_cuotas" id="ver_cuotas">
          <option value="todas" selected="selected">Todas</option>
          <option value="pagadas">pagadas</option>
          <option value="pendientes">pendientes</option>
        </select></td>
    </tr>
	</tbody>
	<tfoot>
    <tr>
      <td colspan="2"><div align="right">
        <input type="submit" name="Submit" value="Generar Informe" />
      </div></td>
      </tr>
	</tfoot>
  </table>
 </form> 
</div>
<div id="apDiv1">Muestra los alumnos de la sede y carrera seleccionada, que cumplan con la condicion de visualizacion de cuotas al dia de hoy. Ademas del aporte de BNM y BET de su ultimo contrato</div>
</body>
</html>