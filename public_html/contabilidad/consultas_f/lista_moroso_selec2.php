<? include ("../../SC/seguridad.php");?>
<? include ("../../SC/privilegio2.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Morozos</title>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:553px;
	height:239px;
	z-index:1;
	left: 6px;
	top: 102px;
}
#Layer2 {
	position:absolute;
	width:525px;
	height:115px;
	z-index:2;
	left: 20px;
	top: 117px;
}
#Layer3 {
	position:absolute;
	width:656px;
	height:71px;
	z-index:2;
	left: 34px;
	top: 349px;
}
#Layer4 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:2;
}
#Layer5 {
	position:absolute;
	width:175px;
	height:21px;
	z-index:3;
	left: 210px;
	top: 357px;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
.Estilo2 {color: #0080C0}
#Layer6 {
	position:absolute;
	width:414px;
	height:43px;
	z-index:4;
	left: 87px;
	top: 60px;
}
-->
</style>
<script language="javascript">
function cambia_action(valor)
{
	document.frm.action=valor;
}
</script>
</head>

<body>
<div id="Layer6">
  <div align="center"><strong>Lista A los Alumnos Morosos de Una carrera determinados y que cumplan con las condiciones Seleccionadas </strong></div>
</div>
<table width="540" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><div align="justify">
      <p><font color="#000066" size="5" face="Arial, Helvetica, sans-serif"><strong>Administrador-Finanzas<br />
        </strong><font size="2"><br />
          </font></font><font color="#000066" size="2" face="Arial, Helvetica, sans-serif"><br />
          </font><font color="#000066" size="5" face="Arial, Helvetica, sans-serif"> </font></p>
    </div></td>
  </tr>
</table>
<div id="Layer2">
<form action="lista_moroso2.php" method="post" name="frm" id="frm" target="_blank">
  <table width="523" border="0">
    <tr>
      <td colspan="3" bgcolor="#CCFF00"><div align="center"><strong>Seleccione Criterio de Busqueda </strong></div></td>
    </tr>
    <tr>
      <td width="94"><strong>Carrera</strong></td>
      <td colspan="2"><label>
      <select name="fcarrera" id="fcarrera">
          <? 

    include("../../../funciones/conexion.php");
   
   $res="SELECT carrera FROM carrera where id >= 0";
   $result=mysql_query($res);
   while($row = mysql_fetch_array($result)) 
   {
    $nomcar=$row["carrera"];
    ?>
          <option>
          <? 
    echo $nomcar;
    }
    mysql_free_result($result); 
    mysql_close($conexion);
	$_SESSION["proviene"]="contrato";
	 ?>
          </option>
        </select>
      </label></td>
    </tr>
    <tr>
      <td><strong>Jornada</strong></td>
      <td colspan="2"><label>
      <select name="fjornada" id="fjornada">
          <option value="D" selected="selected">D</option>
          <option value="V">V</option>
        </select>
      </label></td>
    </tr>
    <tr>
      <td><strong>Sede</strong></td>
      <td colspan="2"><label><?
	  include("../../../funciones/funcion.php");
	  echo selector_sede(); 
	  ?>
      </label></td>
    </tr>
    <tr>
      <td><strong>Nivel</strong></td>
      <td colspan="2"><label>
      <select name="fnivel" id="fnivel">
        <option value="1">I</option>
		<option value="2">II</option>
		<option value="3">III</option>
		<option value="4">IV</option>
		<option value="5">V</option>
		<option value="6">VI</option>
		<option value="T">Todos</option>
        </select>
      </label></td>
    </tr>
	<tr>
	<td><strong>Fecha</strong></td>
	<td colspan="2"><label>
	  
	dia
        <?
	$dia_actual = date("d"); 
    


echo('<select name="fdia">'); 

for($i=1;$i<=30;$i++)
{ 

     if($i==$dia_actual)
	 {
	     echo('<option value="'.$i.'" selected="selected">'.$i.'</option>'); 
	 }
	 else
	 {
         echo('<option value="'.$i.'">'.$i.'</option>'); 
	  }	 

} 

echo('</select>');  
?>
mes
<?
	$mes_actual = date("n"); 
    


echo('<select name="fmes">'); 

for($i=1;$i<=12;$i++)
{ 

     if($i==$mes_actual)
	 {
	     echo('<option value="'.$i.'" selected="selected">'.$i.'</option>'); 
	 }
	 else
	 {
         echo('<option value="'.$i.'">'.$i.'</option>'); 
	  }	 

} 

echo('</select>');  
?>
a&ntilde;o
<?
	$anio_actual = date("Y"); 
    
	$anios_anteriores = $anio_actual-5;
	$anios_futuros= $anio_actual +5;

echo'<select name="fano">'; 

for($i=$anios_anteriores;$i<=$anios_futuros;$i++)
{ 
     if($i==$anio_actual)
	 {
	     echo('<option value="'.$i.'" selected="selected">'.$i.'</option>'); 
	 }
	 else
	 {
         echo('<option value="'.$i.'">'.$i.'</option>'); 
	  }	

} 

echo('</select>');  
?>
	</label></td>
	</tr>
	<tr>
	<td><label>
	  <input name="fopcion" type="radio" value="V" />
	  <em>Total</em></label></td>
	<td width="233"><label>
	  <input name="fopcion" type="radio" value="F" />
	  <em>Solo a&ntilde;o y semestre Selecionado</em> </label></td>
	<td width="182"><label>
	  <input name="fopcion" type="radio" value="A" checked="checked" />
	  <em>Solo a&ntilde;o Seleccionado </em></label></td>
	</tr>
    <tr>
      <td bgcolor="#CCCCCC">Formato
        <div align="center"></div>
      <td bgcolor="#CCCCCC"><label>
      <input name="tf" type="radio" value="lista_moroso2.php" checked="checked"  onclick="cambia_action(this.value);"/>
      Detalle</label>
      
      <td bgcolor="#CCCCCC"><label>
        <input name="tf" type="radio" value="lista_moroso2_no_detalle.php"  onclick="cambia_action(this.value);"/>
        Solo Listado </label>
      <tr>
      <td colspan="3" bgcolor="#CEFF00"><div align="center">
        <input type="submit" name="Submit" value="Consultar&gt;&gt;" />    
        </div>
    </table>
  </form>
</div>
<div id="Layer1"><img src="../../BAses/Images/roller.jpg" width="553" height="249" alt="Ingresa los datos" /></div>
<div id="Layer5"><a href="../index.php" class="Estilo2">Volver al Menu de Finanzas</a> </div>
</body>
</html>