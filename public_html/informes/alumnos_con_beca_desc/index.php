<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="inspeccion";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define(DEBUG, true);?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Administrador - informe</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla.css">
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:90%;
	height:145px;
	z-index:1;
	left: 5%;
	top: 86px;
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
#link {
	text-align: right;
	padding-right: 10px;
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

<body>
<h1 id="banner">Administrador - Alumnos Con Beca y Desc.</h1>
<?php
$privilegio=$_SESSION["USUARIO"]["privilegio"];
switch($privilegio)
{
	case"inspeccion":
		$url="../../Administrador/menu_inspeccion/index.php";
		break;
	default:
		$url="../../Administrador/ADmenu.php";	
}
?>
<div id="link"><a href="<?php echo $url;?>" class="button">Volver al menu Principal </a><br />
</div>
<div id="Layer1">
<form action="alumnos_beca_desc.php" method="post" name="frm" id="frm">
  <table width="50%" border="1" align="center">
  <caption></caption>
  <thead>
    <tr>
      <th colspan="3"><span class="Estilo1">Busqueda de Alumnos </span></th>
    </tr>
	</thead>
	<tbody>
    <tr class="odd">
      <td class="Estilo1">Semestre contrato</td>
      <td colspan="2"><select name="semestre" id="semestre">
        <option value="1">1 Semestre</option>
        <option value="2">2 Semestre</option>
        <option value="ambos">ambos</option>
      </select></td>
    </tr>
    <tr class="odd">
      <td class="Estilo1">A&ntilde;o contrato</td>
      <td colspan="2"><span class="Estilo2 Estilo2">
        <select name="year" id="year">
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
	      </select>
      </span></td>
    </tr>
    <tr class="odd">
      <td width="187"><span class="Estilo1">Sede</span></td>
      <td width="197" colspan="2"><?php
	  include("../../../funciones/funcion.php");
	  echo selector_sede(); 
	  ?></td>
    </tr>
    <tr class="odd">
      <td>Nivel</td>
      <td colspan="2"><label for="nivel"></label>
        <select name="nivel" id="nivel">
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="todos">todos</option>
        </select></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo1">Carrera</span></td>
      <td colspan="2"><?php 

    include("../../../funciones/conexion.php");
   
   $res="SELECT carrera FROM carrera where id >= 0";
   $result=mysql_query($res);
   $carrera_oculta=$_POST["ocultocarrera"];
   //echo "--------> $carrera_oculta<br>";
   ?>
        <select name="carrera" id="carrera">
          <?php
   while($row = mysql_fetch_array($result)) 
   {
    $nomcar=$row["carrera"];
	if($nomcar==$carrera_oculta)
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
    <tr class="odd">
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr class="odd">
      <td>Ver detalle Boleta</td>
      <td><input type="radio" name="ver_boleta" id="ver_boleta" value="si" />
        <label for="ver_boleta">Si</label></td>
      <td><input name="ver_boleta" type="radio" id="ver_boleta2" value="no" checked="checked" />
        No</td>
    </tr>
	</tbody>
	<tfoot>
    <tr>
      <td colspan="3"><div align="right">
        <input type="submit" name="Submit" value="Generar Informe" />
      </div></td>
      </tr>
	</tfoot>
  </table>
 </form> 
</div>
</body>
</html>