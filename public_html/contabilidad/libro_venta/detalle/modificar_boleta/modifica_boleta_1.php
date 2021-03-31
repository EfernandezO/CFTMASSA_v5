<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("modifica_boleta_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//--------------------------------------------------///
$continuar_1=false;
if($_GET)
{
	if(isset($_GET["id_boleta"]))
	{
		$id_boleta=base64_decode($_GET["id_boleta"]);
		
		if(is_numeric($id_boleta))
		{
			$continuar_1=true;
			require("../../../../../funciones/conexion_v2.php");
			$id_boleta=mysqli_real_escape_string($conexion_mysqli, $id_boleta);
			$cons="SELECT * FROM boleta WHERE id='$id_boleta' LIMIT 1";
			$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
				$B=$sqli->fetch_assoc();
				$B_id=$B["id"];
				$B_valor=$B["valor"];
				$B_glosa=$B["glosa"];
				$B_fecha=$B["fecha"];
				$B_sede=$B["sede"];
				$B_caja=$B["caja"];
				$B_folio=$B["folio"];
			$sqli->free();
			$conexion_mysqli->close();	
		}
	}
}
//--------------------------------------------------///
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css">
<title>Modifica Boleta</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:80%;
	height:115px;
	z-index:1;
	left: 10%;
	top: 126px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:31px;
	z-index:2;
	left: 30%;
	top: 427px;
}
</style>
<script language="javascript" type="text/javascript">
function CONFIRMAR()
{
	c=confirm('Seguro(a) desea Modificar esta boleta...?');
	if(c){document.getElementById('frm').submit();}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Modifica Boleta</h1>
<div id="apDiv1">
<?php if($continuar_1){?>
<form action="modifica_boleta_2.php" method="post" id="frm">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="2">Datos de Boleta</th>
      </tr>
    </thead>
    <tbody>
    <tr>
      <td width="9%">ID Boleta</td>
      <td width="91%"><?php echo $B_id;?>
        <input name="id_boleta" type="hidden" id="id_boleta" value="<?php echo $B_id;?>" /></td>
    </tr>
    <tr>
      <td>Fecha</td>
      <td><?php echo $B_fecha;?></td>
    </tr>
    <tr>
      <td>sede</td>
      <td><?php echo $B_sede;?></td>
    </tr>
    <tr>
      <td>Caja</td>
      <td><?php echo $B_caja;?></td>
    </tr>
    <tr>
      <td>Folio</td>
      <td><label for="folio"></label>
        <input name="folio" type="text" id="folio" value="<?php echo $B_folio;?>" /></td>
    </tr>
    <tr>
      <td>Glosa</td>
      <td><?php echo $B_glosa;?></td>
    </tr>
    </tbody>
  </table>
  </form>
 <?php }else{ echo"sin datos";}?> 
</div>
<div id="apDiv2"><a href="#" class="button_G" onclick="CONFIRMAR();">Modificar</a></div>
</body>
</html>