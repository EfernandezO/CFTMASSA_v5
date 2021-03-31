<?php include ("../../../SC/seguridad.php");?>
<?php include ("../../../SC/privilegio2.php");?>
<?php
	include("../../../../funciones/conexion.php");
	$sede=$_SESSION["CUOTA"]["sede_f"];
	$consL="SELECT MAX(numletra) FROM letras WHERE sede='$sede'";
	//echo"$consL<br>";
	$sqlL=mysql_query($consL)or die(mysql_error());
	$D=mysql_fetch_row($sqlL);
	$max_letra=$D[0];
	if(empty($max_letra))
	{
		$max_letra=0;
	}
	$max_letra++;
	mysql_free_result($sqlL);
	mysql_close($conexion);
	
	/*foreach($_SESSION["CUOTA"] as $n => $valor)
	{
		echo"$n -> $valor <br>";
	}*/
	//para errores recividos por GET
	$msj="";
	if($_GET)
	{
		$error=$_GET["error"];
		switch($error)
		{
			case"0":
				$msj="";
				break;
			case"1":
				$msj="Error Datos incorrectos, Vacios o Nº de letra Ya existe...";
				break;
			case"2"	:
				$msj="Fallo al intentar grabar la Letra Por Favor intentelo más Tarde ";
				break;	
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Creacion de Letra </title>

<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">

<script src="../../../libreria_publica/JSCal/src/js/jscal2.js"></script>
 <script src="../../../libreria_publica/JSCal/src/js/lang/es.js"></script>
 
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/steel/steel.css">
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:376px;
	height:115px;
	z-index:1;
	left: 167px;
	top: 127px;
}
.Estilo1 {
	color: #FF0000;
	font-weight: bold;
}
#Layer2 {
	position:absolute;
	width:101px;
	height:19px;
	z-index:2;
	left: 441px;
	top: 78px;
}
a:link {
	color: #6699FF;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #6699FF;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #6699FF;
}
-->
</style>
<script language="javascript" type="text/javascript">
function Confirmar()
{
	continuar=false;
	c=confirm('Seguro(a) Desea agregar esta letra');
	if(c)
	{
		continuar=true;
	
	}
	if(continuar)
	{
		document.frm.submit();
	}
}
</script>
</head>
<body>
<h1 id="banner">Administrador- Creaci&oacute;n de Letra </h1>
<div id="Layer1">
<form action="nva_letra_rec.php" method="post" name="frm" id="frm">
  <table width="100%" sumary="">
    <caption>
      Creando Letra Individualmente para el Alumno
    </caption>
    <thead>
      <tr bgcolor="#EBE5D9">
        <th  scope="col" colspan="2">&nbsp;</th>
      </tr>
    </thead>
    <tbody>
      <tr bgcolor="#F7F4EE" class="odd">
        <td width="34%">N&ordm; Letra </td>
        <td width="66%"><input name="num_letra" type="text" id="num_letra" value="<?php echo"$max_letra";?>" size="11" maxlength="10" /></td>
      </tr>
      <tr class="odd" bgcolor="#F7F4EE">
        <td>Fecha Vencimiento </td>
        <td><input name="fecha_vence_letra" type="text" id="fecha_vence_letra" size="11" maxlength="10"  readonly="true" />
        <input type="button" name="boton" id="boton" value="..." /></td>
      </tr>
      <tr class="odd" bgcolor="#F7F4EE">
        <td>Valor</td>
        <td><input name="valor_letra" type="text" id="valor_letra" size="11" maxlength="10" /></td>
      </tr>
      <tr class="odd" bgcolor="#F7F4EE">
        <td>Tipo</td>
        <td><select name="tipo_letra" id="tipo_letra">
          <option value="matricula">Matricula</option>
          <option value="cuota" selected="selected">Cuota</option>
        </select>        </td>
      </tr>
    </tbody>
    <tfoot>
      <tr  bgcolor="#EBE5D9">
        <td colspan="2"><input type="button" name="Submit" value="Crear"  onclick="Confirmar();"/></td>
      </tr>
      <tr >
        <td colspan="2"><div align="center" class="Estilo1"><?php echo"$msj";?></div></td>
      </tr>
    </tfoot>
  </table>
  </form>
</div>
 <script type="text/javascript">//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton", "fecha_vence_letra", "%Y-%m-%d");

    //]]></script>
 <div id="Layer2">
   <div align="right"><a href="../../pagacuo/cuota1.php">Volver a Cuotas </a></div>
</div>
</body>
</html>