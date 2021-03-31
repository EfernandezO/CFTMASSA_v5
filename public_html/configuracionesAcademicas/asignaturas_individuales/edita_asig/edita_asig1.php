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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>edita asignatura individual</title>
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
	top: 81px;
}
#Layer2 {
	position:absolute;
	width:182px;
	height:29px;
	z-index:2;
	left: 219px;
	top: 271px;
}
#apDiv1 {
	position:absolute;
	width:40%;
	height:40px;
	z-index:2;
	left: 30%;
	top: 316px;
	text-align: center;
}
-->
</style>
<script language="javascript">
function confirmar()
{
	c=confirm('¿Seguro desea Modificar esta Asignatura?');
	if(c==true)
	{
		document.frm.submit();
	}
}
</script>
</head>
<?php
if($_GET)
{
	$id_ramo=$_GET["id_ramo"];
	$id_carrera=$_GET["id_carrera"];
	$sede=$_GET["sede"];
	include("../../../../funciones/conexion.php");
	include("../../../../funciones/funcion.php");
	if(DEBUG){ var_export($_GET);}
	$cons="SELECT * FROM asignatura WHERE id='$id_ramo' AND sede='$sede' LIMIT 1";
	if(DEBUG){echo"$cons<br>";}
	$sql=mysql_query($cons)or die(mysql_error());
	$R=mysql_fetch_array($sql);
		$nombre_asignatura=$R["asignatura"];
		$carrera=$R["carrera"];
		$nivel=$R["nivel"];
	mysql_free_result($sql);
	mysql_close($conexion);
}
?>
<body>
<h1 id="banner">Administrador -  Edici&oacute;n Asignatura Individual</h1>
<div id="link"><br />
<a href="../lista_asignaturas_individuales.php?id_carrera=<?php echo $id_carrera;?>&sede=<?php echo $sede;?>" class="button">Volver al Menu Asignatura</a></div>
<div id="Layer1">
<form action="edita_asig2.php" method="post" name="frm" id="frm">
  <table width="50%" border="0" align="center">
  <thead>
    <tr>
      <th scope="col" colspan="2"><div align="center"><span class="Estilo1">Edicion de Asignatura
        </span>
        <input name="id_asig" type="hidden" id="id_asig"  value="<?php echo $id_ramo;?>"/>
</div></th>
      </tr>
	  </thead>
	  <tbody>
    <tr class="odd">
      <td width="65" ><span class="Estilo4">Asignatura</span></td>
      <td width="268" ><label>
        <input name="nombre_asignatura" type="text" id="nombre_asignatura"  value="<?php echo $nombre_asignatura;?>" size="35"/>
      </label></td>
    </tr>
    <tr class="odd">
      <td ><span class="Estilo4">Sede:</span></td>
      <td >
        <?php
echo selector_sede(); 
?>
     </td>
    </tr>
    <tr class="odd">
      <td ><span class="Estilo4">Nivel</span></td>
      <td ><input name="nivel" type="text" id="nivel"  value="<?php echo $nivel;?>" size="5" maxlength="1"/></td>
    </tr>
    <tr class="odd">
      <td ><span class="Estilo4">Carrera</span></td>
      <td ><select name="fcarrera" id="fcarrera">
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
	</tbody>
	<tfoot>
    <tr>
      <td colspan="2"><div align="center">
        <label>
        <input type="button" name="Submit" value="modificar"  onclick="confirmar();"/>
        </label>
      </div></td>
      </tr>
	  </tfoot>
  </table>
  </form>
</div>
<div id="apDiv1">Modifica los valores de esta asignatura que <br />
  es parte de las asignaturas ingresadas <br />
  individualmente, que son utilizadas<br />
  para notas parciales y demas.
</div>
</body>
</html>