<? include ("../../SC/seguridad.php");?>
<? include ("../../SC/privilegio2.php");?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<style type="text/css">
<!--
body {
	background-color: #FFFFFF;
}
-->
</style></head>

<body leftmargin="-30">
<div id="Layer4" style="position:absolute; left:3px; top:133px; width:95%; height:44px; z-index:44"> 
  <div align="center"><font color="#0000CC"><b>Registro de Personal<br>
    C.F.T. Massachusetts Talca - Linares</b></font></div>
</div>
<div id="Layer1" style="position:absolute; left:7px; top:185px; width:95%; height:161px; z-index:1; overflow: auto"> 
  <? 
  
 include('../../../funciones/conexion.php');
 extract($_POST);
//echo"$nombres $id $correo";
$_SESSION["ADclave"]=$clave;

$res="UPDATE  personal set nombre='$nombres', apellido='$apellidos',clave='$clave',fono='$fono', direccion='$direccion',ciudad='$ciudad',email='$correo' WHERE id=$id";

     $result=mysql_query($res);
echo "<div align='center'><font color='#0000CC'>Los datos han sido modificados</font></div>";
echo"<br><br><br>";
 echo"
  <div align='center'><a href='edit_datos_finan.php'>Regresar</a> <a href='../index.php'>Ir al Menu</a></div>
  ";


?> </div>
<div id="Layer5" style="position:absolute; left:168px; top:441px; width:166px; height:29px; z-index:45"> 
  
</div>
<table width="540" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><div align="justify">
      <p><font color="#000066" size="5" face="Arial, Helvetica, sans-serif"><strong>Finanzas<br>
        </strong><font size="2"><br>
          </font></font><font color="#000066" size="2" face="Arial, Helvetica, sans-serif"><br>
        </font><font color="#000066" size="5" face="Arial, Helvetica, sans-serif"> </font></p>
    </div></td>
  </tr>
</table>
</body>
</html>
