<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Gestion_carreras_Mallas->MALLAS_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_GET)
{
	$id_carrera=$_GET["id_carrera"];
	$sede=$_GET["sede"];
	
}
if($_POST)
{
	$id_carrera=$_POST["id_carrera"];
	$sede=$_POST["sede"];
}

///////////////
require("../../../../funciones/conexion_v2.php");
require("../../../../funciones/funciones_sistema.php");

$nombre_carrera=NOMBRE_CARRERA($id_carrera);
///////////////////
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<title>mallas| nuevo ramo</title>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:90%;
	height:80px;
	z-index:1;
	left: 5%;
	top: 95px;
}
#Layer2 {
	position:absolute;
	width:90%;
	height:130px;
	z-index:2;
	left: 5%;
	top: 250px;
}
#Layer3 {
	position:absolute;
	width:218px;
	height:48px;
	z-index:2;
	left: 39px;
	top: 16px;
}
#Layer4 {
	position:absolute;
	width:98px;
	height:31px;
	z-index:3;
	left: 292px;
	top: 32px;
}
-->
</style>
<script language="javascript">
function Confirmar()
{
	c=confirm('!! Si esta Carrera Ya tenia una Malla Registrada,\n Será Eliminada y Sobreescrita ¡¡');
	
	if(c==true)
	{
		document.frmX.submit();
	}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Mallas Nuevo(s) ramo(s)</h1>
<div id="link"><br />
<a href="../ver_malla.php?id_carrera=<?php echo $id_carrera;?>&sede=<?php echo $sede;?>" class="button">Volver a Seleccion</a></div>
<div id="Layer1">
<form action="nva_malla.php" method="post" name="frm" id="frm">
  <table width="50%" border="0" align="left">
  <thead>
    <tr>
      <th colspan="2">Primero Seleccione
        <input name="id_carrera" type="hidden" id="id_carrera" value="<?php echo $id_carrera;?>" />
        <input name="sede" type="hidden" id="sede" value="<?php echo $sede;?>" />
        <input name="nombre_carrera" type="hidden" value="<?php echo $nombre_carrera;?>" />
        </th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="23%">Carrera:</td>
      <td width="77%"><input name="id_carrera" type="hidden" value="<?php echo $id_carrera;?>" />
        <?php echo $id_carrera;?> - <?php echo $nombre_carrera;?></td>
      </tr>
    <tr>
      <td>N. Ramos </td>
      <td><select name="ramos">
        <?php
	  $max=20;
      for($x=1;$x<=$max;$x++)
	  {
		  echo'<option value="'.$x.'">'.$x.'</option>';
	  }
	  ?>
      </select></td>
      </tr>
    <tr>
      <td colspan="2"><label>
        <input type="submit" name="Submit" value="Continuar" />
      </label></td>
    </tr>
    </tbody>
  </table>
  </form>
</div>
<?php
if($_POST)
{
	$id_carrera=$_POST["id_carrera"];
	$sede=$_POST["sede"];
	$nombre_carrera=$_POST["nombre_carrera"];
	$ramos=$_POST["ramos"];
	$array_nivel=array(1,2,3,4,5);
	$color3 = "#36FFCF";
	$color = "";
	echo'<div id="Layer2"><form action="nva_malla2.php" method="post" name="frmX" id="frmX">
  <table width="70%" border="0" align="left">
  <thead>
    <tr>
      <th colspan="11"><strong>Carrera: '.base64_decode($nombre_carrera).'</strong>
      <input name="carrera" type="hidden" id="carrera" value="'.$nombre_carrera.'" />
       <input name="numero_ramo" type="hidden" id="numero_ramo"  value="'.$ramos.'"/>
	   <input name="id_carrera" type="hidden" value="'.$id_carrera.'" />
	   <input name="sede" type="hidden" value="'.$sede.'" />
	   </th>
    </tr>
	 <tr>
      <td>Codigo</td>
	  <td>Num. Posicion</td>
      <td>PR1</td>
      <td>PR2</td>
      <td>PR3</td>
      <td>PR4</td>
	  <td>PR5</td>
      <td>Nivel</td>
      <td>Ramo</td>
	  <td>N. Horas Teoricas</td>
	  <td>N. Horas Practicas</td>
	  <td>Es Asignatura</td>
    </tr>
	</thead>
	<tbody>';
	if((is_numeric($ramos))and($ramos<100)and($ramos>0))
	{
		//echo"Valido<br>";
		for($i=1;$i<=$ramos;$i++)
		{
			echo "<tr align=\"center\" onMouseOver=\"this.style.backgroundColor='$color3'\" onMouseOut=\"this.style.backgroundColor='$color'\" >";
			echo'
     		 <td>
     		   <input name="codigo[]" type="text" id="codigo'.$i.'" size="10"  value="0"/>
     		</td>
			 <td>
     		   <input name="num_posicion[]" type="text" id="num_posicion'.$i.'" size="10"  value="0"/>
     		</td>
      		<td>
      		  <input name="pr1[]" type="text" id="pra'.$i.'" size="10" value="0"/>
      		</td>
      		<td>
      		  <input name="pr2[]" type="text" id="prb'.$i.'" size="10" value="0"/>
      		</td>
      		<td>
      		  <input name="pr3[]" type="text" id="prc'.$i.'" size="10" value="0"/>
      		</td>
      		<td>
      		  <input name="pr4[]" type="text" id="prd'.$i.'" size="10" value="0"/>
      		</td>
			<td>
      		  <input name="pr5[]" type="text" id="prd'.$i.'" size="10" value="0"/>
      		</td>
      		<td>
      		   <select name="nivel[]" id="nivel'.$i.'">';
				  foreach($array_nivel as $n =>$valor)
				  {echo'<option value="'.$valor.'">'.$valor.'</option>';}
			echo'</select>
      		</td>
      		<td>
      		  <div align="center">
      		    <input name="ramo[]" type="text" id="ramo'.$i.'" />
   		    </div>
      		</td>
			<td><input id="numero_horas_teoricas_'.$i.'" name="numero_horas_teoricas[]" type="text" value="0" size="10"/></td>
			<td><input id="numero_horas_practicas_'.$i.'" name="numero_horas_practicas[]" type="text" value="0" size="10"/></td>
			<td>
      		   <select name="es_asignatura[]" id="es_asignatura'.$i.'">
					<option value="1">Si</option>
					<option value="0">No</option>
				</select>
      		</td>
    		</tr>';
			
		}
		
		echo'<tr><td colspan="11"><label>
  <input type="button" name="Submit2" value="Grabar"  onclick="Confirmar();"/>
</label></td></tr></table></form></div>';
	}
	else
	{
			echo"<big>* Fuera de Rango... *</big>";
	}
	
}
echo'</tbody></table>';
$conexion_mysqli->close();
?>
</body>
</html>
