<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("gestion_facturas_v1.2");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

////////////////////////////////////////////////////
$fecha_actual=date("Y-m-d");
require("../../../../../funciones/conexion_v2.php");
if(isset($_GET["id_factura"]))
{$id_factura=$_GET["id_factura"];}
else
{ $id_factura="";}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<title>Carga de Facturas</title>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">


<script src="../../../../libreria_publica/JSCal/src/js/jscal2.js"></script>
<script src="../../../../libreria_publica/JSCal/src/js/lang/es.js"></script>
 
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/steel/steel.css">

<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css"/>
<script language="javascript">
function ELIMINAR(id)
{
	c=confirm('seguro que desea Eliminar esta IMG');
	if(c)
	{
		url="elimina_img.php?id_factura=<?php echo base64_encode($id_factura);?>&id_img="+id;
		window.location=url;
	}
}
function CONFIRMAR()
{
	continuar=true;
	c=confirm('Seguro(a) Desea Cargar este Archivo');
	archivo=document.getElementById('archivo').value;
	
	if((archivo=="")||(archivo==" "))
	{
		alert("Seleccione un archivo antes de continuar...");
		continuar=false;
	}
	if(continuar)
	{
		if(c){document.getElementById('form1').submit();}
	}
}
</script>    
<style type="text/css">
<!--
.Estilo2 {font-size: 12px}
.Estilo3 {
	font-size: 14px;
	font-weight: bold;
	font-style: italic;
}
#content #msj {
	height: 50px;
	text-decoration: blink;
	font-size: 16px;
}
#apDiv1 {
	position:absolute;
	width:40%;
	height:20px;
	z-index:1;
	left: 5%;
	top: 68px;
}
#apDiv2 {
	position:absolute;
	width:259px;
	height:115px;
	z-index:1;
	left: 622px;
	top: 54px;
}
#apDiv3 {
	position:absolute;
	width:45%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 90px;
}
#apDiv4 {
	position:absolute;
	width:90%;
	height:70px;
	z-index:2;
	left: 5%;
	top: 309px;
}
#apDiv5 {
	position:absolute;
	width:30%;
	height:31px;
	z-index:3;
	left: 65%;
	top: 147px;
}
-->
</style>
</head>
<body>
<h1 id="banner">Facturas - Carga Archivos a Factura</h1>
<div id="contento">
  <div id="apDiv3">
<form id="form1" action="graba_bbdd.php" enctype="multipart/form-data" method="post" name="form1">
		  <table width="100%" height="154" align="left" >
            <thead>
            	<tr>
               	  <th colspan="2">Carga de Imagenes
           	      <input name="id_factura" type="hidden" id="id_factura" value="<?php echo $id_factura;?>" />
           	      - <?php echo $id_factura;?></th>
                </tr>
            </thead>
            <tbody>
				<tr>
					<td height="37"><label for="lastname">Fecha</label></td>
					<td><input name="fecha_X" type="text" id="lastname" size="11" maxlength="10" value="<?php echo"$fecha_actual";?>" readonly="true"/>
                      <input type="button" name="boton" id="boton" value="..."/></td>
				</tr>
				<tr>
				  <td height="29">Archivo</td>
				  <td height="29"><label for="archivo"></label>
			      <input type="file" name="archivo" id="archivo" /></td>
		      </tr>
				<tr>
				  <td height="29">&nbsp;</td>
				  <td height="29">&nbsp;</td>
			  </tr>
            </tbody>
		  </table>
			<br />
  </form>
</div>
</div>
<div id="apDiv4">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="3">Imagenes Ya cargadas</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>N</td>
      <td>Imagen</td>
      <td>Opcion</td>
    </tr>
   <?php
   $cons_I="SELECT * FROM facturas_imagenes WHERE id_factura='$id_factura'";
	$sql_I=mysql_query($cons_I)or die(mysql_error());
	$num_imagenes=mysql_num_rows($sql_I);
	$path='../../../../CONTENEDOR_GLOBAL/facturas/';
	if($num_imagenes>0)
	{
		$aux=0;
		while($I=mysql_fetch_assoc($sql_I))
		{
			$aux++;
			$id_imagen=$I["id"];
			$archivo=$I["archivo"];
			$ruta=$path.$archivo;
			echo'<tr>
					<td>'.$aux.'</td>
					<td><a href="'.$ruta.'" target="_blank">factura_'.$aux.'</a></td>
					<td align="center"><a href="#" onclick="ELIMINAR(\''.base64_encode($id_imagen).'\');" class="button">Eliminar</a></td>	
				 </tr>';
		}
	}
	else
	{
		echo'<tr><td colspan="3">Sin Imagenes Pre cargadas...</td></tr>';
	}
   ?>
    </tbody>
  </table>
</div>
<div id="apDiv5"><a href="#" class="button_R" onclick="CONFIRMAR();">Cargar Archivo</a></div>
<script type="text/javascript">//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton", "lastname", "%Y-%m-%d");
		//cargarMovimientos();
    //]]></script>
</body>
</html>