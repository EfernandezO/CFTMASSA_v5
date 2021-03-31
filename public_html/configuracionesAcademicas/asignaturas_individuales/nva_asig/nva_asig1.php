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
	if(DEBUG){ var_export($_GET);}
	$id_carrera=$_GET["id_carrera"];
	$sede=$_GET["sede"];
	$carrera=base64_decode($_GET["nombre_carrera"]);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Nueva Asignatura</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 102px;
}
#Layer2 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:1;
	left: 20px;
	top: -45px;
}
#Layer3 {
	position:absolute;
	width:180px;
	height:27px;
	z-index:2;
	left: 549px;
	top: 135px;
}
</style>
<script language="javascript">
function confirmar()
{
	continuar=true;
	nombre_asignatura=document.getElementById('nombre_asignatura').value;
	
	if(nombre_asignatura=="")
	{
		alert("Ingrese Asignaturas");
		continuar=false;
	}
	
	
	if(continuar)
	{
		c=confirm('Agregar esta Asignatura...¿?');
		if(c==true)
		{
			document.frm.submit();
		}
	}
}
</script>
</head>
<body>
<h1 id="banner">Administrador - Nueva Asignatura Individual</h1>
<div id="link"><br />
<a href="../index.php" class="button">Volver al Menu Asignatura</a></div>
<div id="Layer1">
<form action="nva_asig2.php" method="post" name="frm" id="frm">
      <table width="50%" border="0" align="center">
	  <thead>
	  <tr>
	  <th colspan="2"><span class="Estilo2">Nueva Asignatura para la carrera: <?php echo "$carrera - $sede";?></span></th>
	  </tr>
	  </thead>
	  <tbody>
	    <tr class="odd">
	      <td ><span class="Estilo2">Nivel</span></td>
	      <td ><label for="nivel"></label>
	        <select name="nivel" id="nivel">
	          <option value="1">1</option>
	          <option value="2">2</option>
	          <option value="3">3</option>
	          <option value="4">4</option>
	          <option value="5">5</option>
          </select></td>
        </tr>
	    <tr class="odd">
	      <td width="82" >
	        <span class="Estilo2">
	        Asignatura:
	        </span> </td>
	      <td width="265" ><input name="nombre_asignatura" id="nombre_asignatura" type="text" size="45" maxlength="50" /></td>
        </tr>
	    <tr class="odd">
	      <td ><span class="Estilo2">Carrera:</span></td>
	      <td ><select name="carrera" id="carrera">
	        <?php 
    include("../../../../funciones/conexion.php");
   $res="SELECT id, carrera FROM carrera";
   $result=mysql_query($res);
   while($row = mysql_fetch_array($result)) 
   {
	   $id_carr=$row["id"];
    	$nomcar=$row["carrera"];
		if($id_carr==$id_carrera)
		{ echo'<option value="'.$id_carr.'_'.$nomcar.'" selected="selected">'.$nomcar.'</option>';}
		else{ echo'<option value="'.$id_carr.'_'.$nomcar.'">'.$nomcar.'</option>';}
    }
    mysql_free_result($result); 
    mysql_close($conexion);
	 ?>
	        </select></td>
        </tr>
	    <tr>
	      <td >
	        <span class="Estilo2">
	        <label>Sede:</label>
	        </span> </td>
	      <td ><?php
include("../../../../funciones/funcion.php");
echo selector_sede(); 
?></td>
        </tr>
	    <tr class="odd">
	      <td >&nbsp;</td>
	      <td ><input type="button" name="Submit" value="Agregar"  onclick="confirmar();"/></td>
        </tr>
		</tbody>
      </table>
  </form>
</div>
</body>
</html>