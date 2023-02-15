<?php require("../../SC/seguridad.php");?>
<?php require("../../SC/privilegio.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<title>Elimina Noticia</title>
<style type="text/css">
<!--
#Layer2 {
	position:absolute;
	width:348px;
	height:115px;
	z-index:1;
	left: 140px;
	top: 149px;
}
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
#Layer1 {
	position:absolute;
	width:384px;
	height:225px;
	z-index:2;
	left: 111px;
	top: 117px;
}
#Layer3 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:3;
	left: 123px;
	top: 138px;
}
-->
</style>
</head>
<script language="javascript">
 function Envia()
 {
 	
 
     C=confirm('¿Realmente desea Eliminar la Noticia?');
	 if(C==true)
 	{
   		document.frm.submit();
 	}
 }	

</script>
<body>
<h1 id="banner">Elimina  - Noticia</h1>
<div id="link"><a href="edita_not1.php">Volver a Seleccion</a></div>
<?php
  $ocu_id=$_POST["ocu_id"];
   include("../../../funciones/conexion.php");
   include("../../../funciones/funcion.php");   
   
   $consN="SELECT fecha,autor,breve,imagen FROM noticias WHERE idn='$ocu_id'";
   $sqlN=mysql_query($consN);
   While($A=mysql_fetch_array($sqlN))
   {
   		$fecha=fecha_format($A["fecha"]);
		$autor=$A["autor"];
		$breve=$A["breve"];
		$ruta=$A["imagen"];
   }
    
?>
<div id="Layer3">
  <form action="borra_not2.php" method="post" name="frm" id="frm">
  <table width="353" border="0">
    <tr>
      <td colspan="2" bgcolor="#FFFF00"><div align="center" class="Estilo1">Elimina Noticia </div></td>
    </tr>
    <tr>
      <td width="158" bgcolor="#e5e5e5"><strong>Autor:</strong></td>
      <td width="175" bgcolor="#e5e5e5"><div align="right"><?php echo"$autor";?></div></td>
    </tr>
    <tr>
      <td bgcolor="#e5e5e5"><strong>Fecha de publicaci&oacute;n : </strong></td>
      <td bgcolor="#e5e5e5"><div align="right"><?php echo"$fecha";?></div></td>
    </tr>
    <tr>
      <td bgcolor="#e5e5e5"><strong>Breve:</strong></td>
      <td bgcolor="#e5e5e5"><div align="right"><?php echo"$breve";?></div></td>
    </tr>
    <tr>
      <td bgcolor="#e5e5e5">&nbsp;</td>
      <td bgcolor="#e5e5e5"><input name="ocu_idn" type="hidden" id="ocu_idn" value="<?php echo"$ocu_id";?>" /></td>
    </tr>
    <tr>
      <td colspan="2" bgcolor="#e5e5e5"><input name="ocu_ruta" type="hidden" id="ocu_ruta" value="<?php echo"$ruta";?>" /></td>
    </tr>
    <tr>
      <td bgcolor="#e5e5e5">&nbsp;</td>
      <td bgcolor="#e5e5e5">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" bgcolor="#e5e5e5"><div align="center">
        <label>
        <div align="right">
          <input type="button" name="Submit" value="Eliminar&gt;&gt;"  onClick="Envia()"/>
        </div>
        </label>
      </div></td>
    </tr>
  </table>
  </form>
</div>

<p>&nbsp;</p>
</body>
</html>
