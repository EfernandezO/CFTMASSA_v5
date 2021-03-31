<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_Alumnos_con_excedente");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$privilegio=$_SESSION["USUARIO"]["privilegio"];
switch($privilegio)
{
	case"inspeccion":
		$url_menu="../../Administrador/menu_inspeccion/index.php";
		break;
	default:
		$url_menu="../index.php";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Administrador - informe</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:90%;
	height:186px;
	z-index:1;
	left: 5%;
	top: 97px;
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
#apDiv1 {
	position:absolute;
	width:40%;
	height:35px;
	z-index:2;
	left: 30%;
	top: 532px;
	text-align: center;
}
-->
</style>
</head>

<body>
<h1 id="banner">Administrador - Alumnos Con Excedentes</h1>
<div id="link"><br><a href="<?php echo $url_menu;?>" class="button">Volver al menu Principal </a>
  </div>
<div id="Layer1">
<form action="alumno_con_excedente.php" method="post" name="frm" id="frm">
  <table width="50%" border="1" align="center">
  <caption></caption>
  <thead>
    <tr>
      <th colspan="6"><span class="Estilo1">Busqueda de Alumnos </span></th>
    </tr>
	</thead>
	<tbody>
    <tr class="odd">
      <td width="160"><span class="Estilo1">Sede</span></td>
      <td width="171" colspan="5"><?php
	  include("../../../funciones/funcion.php");
	  echo selector_sede(); 
	  ?></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo1">Carrera</span></td>
      <td colspan="5">
<?php 

    require("../../../funciones/conexion_v2.php");
   
   $res="SELECT id, carrera FROM carrera where id >= 0";
   $result=$conexion_mysqli->query($res);
   //echo "--------> $carrera_oculta<br>";
   ?>
   <select name="carrera" id="carrera">
   <?php
   while($row =$result->fetch_assoc()){
    $nomcar=$row["carrera"];
	$id_carrera=$row["id"];
	
		echo'<option value="'.$id_carrera.'_'.$nomcar.'">'.$nomcar.'</option>';
   }
    $result->free(); 
	 ?>
     <option value="0" selected="selected">Todas</option>
        </select></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo1">A&ntilde;o Ingreso </span></td>
      <td colspan="5"><select name="ano_ingreso" id="ano_ingreso">
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
      <option value="Todos" selected="selected">Todos</option>
      </select>      </td>
    </tr>
    <tr class="odd">
      <td>A&ntilde;o Vigencia Contrato</td>
      <td colspan="5"><select name="year_vigencia_contrato" id="year_vigencia_contrato">
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
		
		$conexion_mysqli->close();
	  ?>
        </select></td>
    </tr>
    <tr class="odd">
      <td>&nbsp;</td>
      <td colspan="5">&nbsp;</td>
    </tr>
    </tbody>
	<tfoot>
    <tr>
      <td colspan="6"><div align="right">
        <input type="submit" name="Submit" value="Generar Informe" />
      </div></td>
      </tr>
	</tfoot>
  </table>
 </form> 
</div>
<div id="apDiv1">Muestra alumnos con contrato vigente segun criterios seleccionados, y que tienen Excedentes X utilizar.</div>
</body>
</html>
