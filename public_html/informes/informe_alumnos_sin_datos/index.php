<?php require ("../../SC/seguridad.php");?>
<?php require ("../../SC/privilegio.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Alumnos sin Datos</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:653px;
	height:115px;
	z-index:1;
	left: 20px;
	top: 78px;
}
#link {
	text-align: right;
	padding-right: 10px;
}
a:link {
	color: #006699;
	text-decoration: none;
}
a:visited {
	color: #006699;
	text-decoration: none;
}
a:hover {
	color: #FF0000;
	text-decoration: underline;
}
a:active {
	color: #006699;
	text-decoration: none;
}
-->
</style>
</head>

<body>
<h1 id="banner">Administrador - informe Alumnos </h1>
<div id="link"><a href="../../Alumnos/menualumnos.php">Volver al Menu</a></div>
<div id="apDiv1">
<form action="informe_SDA.php" method="post" name="frm" id="frm">
  <table width="673" border="0">
    <tr>
      <td colspan="8" bgcolor="#e5e5e5"><strong>Seleccione los campos para buscar los vacios</strong></td>
    </tr>

    <tr>
      <td colspan="4" bgcolor="#f5f5f5"><em>A&ntilde;o Ingreso</em></td>
      <td colspan="4" bgcolor="#f5f5f5"><em>
        <select name="ano" id="ano">
            <?php
	  if(isset($año_nac))
	  {
	  	$año_actual=$año_nac;
		echo"-> $año_nac<br>";
	  }
	  else
	  {
	  	$año_actual=date("Y");
	  }	
	  $año_ini=($año_actual-100);
	  $año_fin=($año_actual);
      for($año=$año_ini;$año<=$año_fin;$año++)
      {
	  	if($año==$año_actual)
		{
			echo'<option value="'.$año.'" selected="selected">'.$año.'</option>';
		}
		else
		{
	  		echo'<option value="'.$año.'">'.$año.'</option>';
		}	
      }
	  ?>
          </select>
      </em></td>
    </tr>
    <tr>
      <td colspan="4" bgcolor="#f5f5f5"><em>Sede</em></td>
      <td colspan="4" bgcolor="#f5f5f5"><em>
        <?php  
		   include("../../../funciones/funcion.php");
		   echo selector_sede("sede");  
		  ?>
      </em></td>
    </tr>
    <tr>
      <td colspan="4" bgcolor="#f5f5f5"><em>Carrera</em></td>
      <td colspan="4" bgcolor="#f5f5f5">
        <select name="carrera">
          <?php 

    include("../../../funciones/conexion.php");
   
   $res="SELECT * FROM carrera where id >= 0";
   $result=mysql_query($res);
   while($row = mysql_fetch_array($result)) 
   {
	$nomcar=$row["carrera"];
  		if(($session_B)and($carrera==$nomcar))
		{
			echo'<option value="'.$nomcar.'" selected="selected">'.$nomcar.'</option>';
		}
		else
		{
			echo'<option value="'.$nomcar.'">'.$nomcar.'</option>';
		}
	
	}
mysql_free_result($result); 
mysql_close($conexion); 
 ?>
        </select>
      </td>
    </tr>
    <tr>
      <td colspan="4" bgcolor="#f5f5f5">Tipo Condici&oacute;n</td>
      <td colspan="4" bgcolor="#f5f5f5"><select name="tipo_condicion" id="tipo_condicion">
        <option value="AND">Y</option>
        <option value="OR">O</option>
      </select>
      </td>
    </tr>
    <tr>
      <td width="59" bgcolor="#f5f5f5"><p align="center"><em>fono</em></p></td>
      <td width="58" bgcolor="#f5f5f5"><div align="center"><em>email</em></div></td>
      <td width="70" bgcolor="#f5f5f5"><div align="center"><em>direccion</em></div></td>
      <td width="57" bgcolor="#f5f5f5"><div align="center"><em>ciudad</em></div></td>
      <td width="116" bgcolor="#f5f5f5"><div align="center"><em>nombre apoderado</em></div></td>
      <td width="126" bgcolor="#f5f5f5"><div align="center"><em>direccion apoderado</em></div></td>
      <td width="72" bgcolor="#f5f5f5"><div align="center"><em>ciudad apo</em></div></td>
      <td width="81" bgcolor="#f5f5f5"><div align="center"><em>fono apo</em></div></td>
    </tr>
    <tr>
      <td bgcolor="#f5f5f5"><div align="center">
        <input name="campo[]" type="checkbox" id="campo[]" value="fono" />
      </div></td>
      <td bgcolor="#f5f5f5"><div align="center">
        <input name="campo[]" type="checkbox" id="campo[]" value="email" />
      </div></td>
      <td bgcolor="#f5f5f5"><div align="center">
        <input name="campo[]" type="checkbox" id="campo[]" value="direccion" />
      </div></td>
      <td bgcolor="#f5f5f5"><div align="center">
        <input name="campo[]" type="checkbox" id="campo[]" value="ciudad" />
      </div></td>
      <td bgcolor="#f5f5f5"><div align="center">
        <input name="campo[]" type="checkbox" id="campo[]" value="apoderado" />
      </div></td>
      <td bgcolor="#f5f5f5"><div align="center">
        <input name="campo[]" type="checkbox" id="campo[]" value="direccion_apoderado" />
      </div></td>
      <td bgcolor="#f5f5f5"><div align="center">
        <input name="campo[]" type="checkbox" id="campo[]" value="ciudad_apoderado" />
      </div></td>
      <td bgcolor="#f5f5f5"><div align="center">
        <input name="campo[]" type="checkbox" id="campo[]" value="fonoa" />
      </div></td>
    </tr>
    <tr>
      <td colspan="8"><div align="right">
        <input type="submit" name="button" id="button" value="consultar" />
      </div></td>
    </tr>
  </table>
</form>  
</div>

</body>
</html>