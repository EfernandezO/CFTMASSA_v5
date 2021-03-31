<? include ("../../SC/seguridad.php");?>
<? include ("../../SC/privilegio2.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Formulario de Selecion</title>
<style type="text/css">
<!--
.Estilo2 {color: #0080C0}
#Layer5 {	position:absolute;
	width:175px;
	height:21px;
	z-index:3;
	left: 153px;
	top: 337px;
}
#Layer1 {	position:absolute;
	width:329px;
	height:176px;
	z-index:1;
	left: 64px;
	top: 126px;
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
#Layer2 {
	position:absolute;
	width:350px;
	height:115px;
	z-index:1;
	left: 5px;
	top: 9px;
}
.Estilo3 {
	font-size: 18px;
	font-weight: bold;
}
#Layer3 {
	position:absolute;
	width:285px;
	height:39px;
	z-index:4;
	left: 91px;
	top: 70px;
}
-->
</style>
<script language="javascript">
function cambiar_action(valor)
{
	document.frm2.action=valor;
}
</script>
</head>

<body>
<div id="Layer3">
  <div align="center"><strong>Lista Alumnos Morosos de Todos las Carreras Seg&uacute;n Criterios de Busqueda </strong></div>
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
<div id="Layer5"><a href="../index.php" class="Estilo2">Volver al Menu de Finanzas</a> </div>
<div id="Layer1"><img src="../../BAses/Images/roller.jpg" width="362" height="192" alt="Ingresa los datos" />
  <div id="Layer2">
  <form action="lista_moroso1.php" method="post" name="frm2" id="frm2" target="_blank">
    <table width="352" border="0">
      <tr>
        <td colspan="3" bgcolor="#CCFF33"><div align="center" class="Estilo3">Criterio de Busqueda </div></td>
      </tr>
      <tr>
        <td width="118">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td>Sede</td>
        <td colspan="2"><label><?
	  include("../../../funciones/funcion.php");
	  echo selector_sede(); 
	  ?>
        </label></td>
      </tr>
      <tr>
        <td>Nivel</td>
        <td colspan="2"><select name="fnivel" id="fnivel">
          <option value="1">I</option>
          <option value="2">II</option>
          <option value="3">III</option>
          <option value="4">IV</option>
          <option value="5">V</option>
          <option value="6">VI</option>
          <option value="todos" selected="selected">Todos</option>
                        </select></td>
      </tr>
      <tr>
        <td>Fecha de corte</td>
        <td colspan="2"><?
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
          /
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
            /
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
?></td>
      </tr>
      <tr>
        <td>
        Formato</td>
        <td width="115"><label>
          <input name="tf" type="radio" value="lista_moroso1.php" checked="checked"  onclick="cambiar_action(this.value);"/>
          Detalle</label></td>
        <td width="105"><label>
          <input name="tf" type="radio" value="lista_moroso1_no_detalle.php"  onclick="cambiar_action(this.value);"/>
          solo listado</label></td>
      </tr>
      <tr>
        <td colspan="3"><div align="center">
          <input type="submit" name="Submit" value="Continuar &gt;&gt;" />
        </div></td>
        </tr>
    </table>
	</form>
  </div>
</div>
</body>
</html>
