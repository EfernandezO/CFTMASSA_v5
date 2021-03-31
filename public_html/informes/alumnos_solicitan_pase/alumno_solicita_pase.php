<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	OKALIS($lista_invitados);
//-----------------------------------------//	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Administrador - solicitud pase</title>
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
	top: 93px;
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
	height:30px;
	z-index:2;
	left: 30%;
	top: 296px;
	text-align: center;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Solicitud Pase Escolar</h1>
<?php
$privilegio=$_SESSION["USUARIO"]["privilegio"];
switch($privilegio)
{
	case"matricula":
		$url="../../Administrador/menu_matricula/index.php";
		break;
	case"inspeccion":
		$url="../../Administrador/menu_inspeccion/index.php";
		break;
	default:
		$url="../../Alumnos/menualumnos.php";	
}
?>
<div id="link"><br>
<a href="<?php echo $url;?>" class="button">Volver al menu</a><br />
</div>
<div id="Layer1">
<form action="alumno_solicita_pase_2.php" method="post" name="frm" id="frm">
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
        </select>
        </td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo1">A&ntilde;o Ingreso </span></td>
      <td><select name="ano_ingreso" id="ano_ingreso">
        
	  <?php
	  	$años_anteriores=1;
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
<div id="apDiv1">Lista los Alumnos que registran solicitud<br />
  de Pase Escolar.
</div>
</body>
</html>