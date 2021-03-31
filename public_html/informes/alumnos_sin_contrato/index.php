<?php include ("../../SC/seguridad.php");?>
<?php include ("../../SC/privilegio.php");?>
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
	width:200px;
	height:186px;
	z-index:1;
	left: 106px;
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
-->
</style>
</head>

<body>
<h1 id="banner">Administrador - Informe de Alumnos Sin Contrato Vigente</h1>
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
<div id="link"><a href="<?php echo $url;?>" class="Estilo1">Volver al menu Principal </a></div>
<div id="Layer1">
<form action="genera_informe_SC.php" method="post" name="frm" target="_blank" id="frm">
  <table width="400" border="1">
  <caption></caption>
  <thead>
    <tr>
      <th colspan="2"><span class="Estilo1">Busqueda de Alumnos </span></th>
    </tr>
	</thead>
	<tbody>
    <tr class="odd">
      <td width="187"><span class="Estilo1">Sede</span></td>
      <td width="197"><?
	  include("../../../funciones/funcion.php");
	  echo selector_sede(); 
	  ?></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo1">Carrera</span></td>
      <td>
<?php 

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
        </select></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo1">A&ntilde;o Ingreso </span></td>
      <td><select name="ano_ingreso" id="ano_ingreso">
	  <?php
	  	$años_anteriores=10;
		$años_siguientes=1;
	  	$año_actual=date("Y");
		
		$año_ini=$año_actual-$años_anteriores;
		$año_fin=$año_actual+$años_siguientes;
		
		for($a=$año_ini;$a<=$año_fin;$a++)
		{
			if($a==$año_actual)
			{
				echo'<option value="'.$a.'" selected="selected">'.$a.'</option>';	
			}
			else
			{
				echo'<option value="'.$a.'">'.$a.'</option>';
			}	
		}
	  ?>
      <option value="Todos">Todos</option>
      </select>      </td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo1">Jornada</span></td>
      <td><select name="jornada" id="jornada">
        <option value="D">Diurno</option>
        <option value="V">Vespertino</option>
        <option value="T">Todas</option>
      </select></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo1">Grupo</span></td>
      <td><select name="grupo" id="grupo">
        <option value="Todos" selected="selected">Todos</option>
        <option value="A">A</option>
        <option value="B">B</option>
        <option value="C">C</option>
        <?php 
		
		foreach(range('A', 'Z') as $letra)
		{
				echo'<option value="'.$letra.'">'.$letra.'</option>';
		}
		?>
      </select></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo1">Nivel</span></td>
      <td><select name="nivel" id="nivel">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="Todos">Todos</option>
      </select>
      </td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo1">Estado</span></td>
      <td><select name="estado" id="estado">
        <option value="V">Vigente</option>
        <option value="T">Titulados</option>
        <option value="A">Todos</option>
        <option value="E">Egresado</option>
        <option value="P">Postergado</option>
        <option value="R">Retirado</option>
        <option value="E">Eliminado</option>
      </select>      </td>
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
</body>
</html>
