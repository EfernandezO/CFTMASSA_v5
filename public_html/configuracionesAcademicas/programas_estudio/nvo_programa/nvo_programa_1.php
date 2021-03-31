<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Gestion_carreras_Mallas->PROGRAMAS_ESTUDIO_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_GET)
{
	if(isset($_GET["id_carrera"]))
	{
		$id_carrera=$_GET["id_carrera"];
		if(is_numeric($id_carrera)){ $continuar_1=true;}
		else{ $continuar_1=false;}
	}
	else
	{ $continuar_1=false;}
	
	if(isset($_GET["cod_asignatura"]))
	{
		$cod_asignatura=$_GET["cod_asignatura"];
		if(is_numeric($cod_asignatura)){ $continuar_2=true;}
		else{ $continuar_2=false;}
	}
	else
	{ $continuar_2=false;}
	
	$sede=$_GET["sede"];
	
	if($continuar_1 and $continuar_2)
	{ $continuar=true;}
	else
	{ $continuar=false;}
	
	if($continuar)
	{
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/funciones_sistema.php");
	
	$nombre_carrera=NOMBRE_CARRERA($id_carrera);
	list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($id_carrera, $cod_asignatura);
	
	@mysql_close($conexion);
	$conexion_mysqli->close();
	}
}
else
{header("location: ../index.php");}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php require("../../../../funciones/codificacion.php");?>
<title>Programa de Estudios</title>
	<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
    <link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:55px;
	z-index:1;
	left: 5%;
	top: 84px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:27px;
	z-index:2;
	left: 30%;
	top: 501px;
	text-align: center;
}
-->
</style>

<script language="javascript">
function confirmar()
{
	c=confirm('Seguro desea Grabar estos datos..?');
	if(c){document.getElementById('frm').submit();}
}
</script>
</head>
<body>
<h1 id="banner">Administrador - Programa de Estudios</h1>
<h3>Administre los Programas de la Carrera: <?php echo $nombre_carrera;?> - Asignatura: <?php echo $nombre_asignatura;?></h3>
<div id="apDiv1" class="demo_jui">
<form action="nvo_programa_2.php" method="post" id="frm">
  <table width="100%" border="1" align="center" class="display" id="example">
      <thead>
	    <tr>
	      <th colspan="2">Ingreso de Nuevo Registro a Programa
          <input name="id_carrera" type="hidden" id="id_carrera" value="<?php echo $id_carrera;?>" />
          <input name="cod_asignatura" type="hidden" id="cod_asignatura" value="<?php echo $cod_asignatura;?>" />
          <input name="sede" type="hidden" id="sede" value="<?php echo $sede;?>" /></th>
        </tr>
    </thead>
        <tbody>
         <tr>
	      <th width="31%">Numero Unidad</th>
	      <th width="69%"><label for="numero_unidad"></label>
	        <select name="numero_unidad" id="numero_unidad">
	          <option value="1" selected="selected">1</option>
	          <option value="2">2</option>
	          <option value="3">3</option>
	          <option value="4">4</option>
	          <option value="5">5</option>
	          <option value="6">6</option>
	          <option value="7">7</option>
	          <option value="8">8</option>
	          <option value="9">9</option>
	          <option value="10">10</option>
	          <option value="11">11</option>
	          <option value="12">12</option>
	          <option value="13">13</option>
	          <option value="14">14</option>
	          <option value="15">15</option>
	          <option value="16">16</option>
	          <option value="17">17</option>
	          <option value="18">18</option>
	          <option value="19">19</option>
	          <option value="20">20</option>
           </select></th>
         </tr>
         <tr>
           <th>Nombre Unidad</th>
           <th><label for="nombre_unidad"></label>
           <input type="text" name="nombre_unidad" id="nombre_unidad" /></th>
         </tr>
         <tr>
           <th>Cantidad Horas Unidad</th>
           <th><label for="cantidad_horas"></label>
             <select name="cantidad_horas" id="cantidad_horas">
             <?php
             for($x=1;$x<=800;$x++)
			 {
				 echo'<option value="'.$x.'">'.$x.'</option>';
			 }
			 ?>
           </select></th>
         </tr>
         <tr>
           <th colspan="2">Contenido</th>
          </tr>
         <tr>
           <th colspan="2"><label for="contenido"></label>
           <textarea name="contenido" cols="50" rows="10" id="contenido"></textarea></th>
         </tr>
        </tbody>
  </table>
  </form>
  </div>
</div>
<div id="apDiv2"><a href="#" class="button_G" onclick="confirmar();">Grabar</a></div>
</body>
</html>