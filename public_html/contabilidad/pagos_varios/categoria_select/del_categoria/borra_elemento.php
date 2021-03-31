<?php include ("../../../../SC/seguridad.php");?>
<?php include ("../../../../SC/privilegio.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>elimina - elemento</title>
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla.css">

<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:378px;
	height:51px;
	z-index:1;
	left: 30px;
	top: 146px;
}
#titulo #link {
	text-align: right;
	width: 95%;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: underline;
}
a:active {
	text-decoration: none;
}
#Layer2 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:2;
	left: 489px;
	top: 124px;
}
#Layer3 {
	position:absolute;
	width:163px;
	height:41px;
	z-index:2;
	left: 360px;
	top: 69px;
}
.Estilo5 {
	font-size: small;
	font-weight: bold;
}
.Estilo6 {
	font-size: small;
	font-style: italic;
}
.Estilo7 {font-size: small}
-->
</style>
<script language="javascript">
function Verificar()
{
	c=confirm('¿Seguro desea Eliminar este Elemento?');
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
	$error=$_GET["error"];
	switch ($error)
	{
		case "0":
			$msj="Elemento Eliminado";
		 	break;
		case "1":
			$msj="Fallo al Eliminar Elemento";	
		 	break;
	}
}
?>
<body>
<h1 id="banner">Administrador - Eliminacion de Elementos </h1>
<div id="Layer1">
<form action="borra_categoria2.php" method="post" name="frm" id="frm">
  <table sumary="listador">
    <caption>
      Elimina Elementos de la categoria "tipo documento"
      </caption>
    <thead>
      <tr>
        <th colspan="4" bgcolor="#CCFF66"><span class="Estilo5">Elimina tipo de Documento </span></th>
      </tr>
      <tr>
        <th  scope="col" width="45"><span class="Estilo6">N&ordm;</span></th>
        <th  scope="col" width="95"><span class="Estilo6">tipo</span></th>
        <th  scope="col" width="235"><span class="Estilo6">contenido</span></th>
        <th  scope="col" width="43"><span class="Estilo6">opci&oacute;n</span></th>
      </tr>
    </thead>
    <tbody>
      <?php
	include("../../../../../funciones/conexion.php");
	$seccion="finanzas";
	$cons_s="SELECT * FROM parametros WHERE seccion='$seccion' ORDER by tipo";
	$sql_s=mysql_query($cons_s)or die(mysql_error());
	$num_reg=mysql_num_rows($sql_s);
	if($num_reg>0)
	{
		$aux=1;
		$color3 = "#E0FAC5";
		$color="#FFFFFF";
		while($R=mysql_fetch_assoc($sql_s))
		{
			$id=$R["id"];
			$tipo=$R["tipo"];
			$contenido=$R["contenido"];
			
			echo'<tr class="odd">
			<td><span class="Estilo7">'.$aux.'</span></td>
      			<td><span class="Estilo7">'.$tipo.'</span></td>
      			<td><span class="Estilo7">'.$contenido.'</span></td>
      			<td>
        		<input name="opc_X" type="radio" value="'.$id.'" />      			</td>
    			</tr>';	
				$aux++;
		}
		echo'</tbody>';
	}
	else
	{
		echo'<tr><td colspan="4" align="center"><span class="Estilo7">Sin Datos</span></td></tr>';
	}
	if($aux>0)
	{
		echo'<tfoot><TR>
		  <td colspan="4" align="center"><input type="button" name="Submit" value="continuar"  onclick="Verificar();"/></td>
		</tr></tfoot>';
	}
	mysql_free_result($sql_s);
	mysql_close($conexion);
?>
      <tr>
        <td colspan="4" align="center"><tt><span class="Estilo7"><?php echo $msj;?></span></tt></td>
      </tr>
    </tbody>
  </table>
</form>
</div>
<div id="Layer3">
  <div align="right"><a href="../nva_categoria/nvo_categoria.php">Agregar un Elemento</a><br />
      <a href="../../form_pago1.php">Volver a Registro de Mov</a> <br />
    <a href="../../../index.php">Volver al Menu</a></div>
</div>
</body>
</html>