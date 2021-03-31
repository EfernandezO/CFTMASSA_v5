<? include ("../../SC/seguridad.php");?>
<? include ("../../SC/privilegio2.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Anula Letras</title>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:421px;
	height:126px;
	z-index:1;
	left: 90px;
	top: 128px;
}
#Layer2 {
	position:absolute;
	width:303px;
	height:115px;
	z-index:2;
	left: 197px;
	top: 286px;
}
#Layer3 {
	position:absolute;
	width:418px;
	height:115px;
	z-index:2;
	left: 93px;
	top: 267px;
}
#Layer4 {
	position:absolute;
	width:108px;
	height:29px;
	z-index:2;
	left: 409px;
	top: 92px;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #0080C0;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
.Estilo2 {color: #0080C0}
#Layer5 {
	position:absolute;
	width:200px;
	height:38px;
	z-index:3;
	left: 93px;
	top: 88px;
}
.Estilo3 {font-size: 24px}
-->
</style>
</head>

<body>
<?
if($_POST)
{
    $selector=$_POST["selector"];
    if($selector=="individual")
	{
	    
		$cant_letra=$_POST["cant_letra"];
		 //echo"ini $cant_letra";
		 
		 if((is_numeric($cant_letra))and($cant_letra <=10))
		   {
		      echo'
              <div id="Layer2">
			  <form action="anula_letra2.php" method="post" name="frmI" id="frmI" >
              <table width="300" border="0">
              <tr>
              <td width="105">Cantidad </td>
              <td width="85">Letra Nª</td>
              </tr>';
			
			  for($h=1;$h<=$cant_letra;$h++)
		       {
			       $nombre="letra_$h";
			       echo'<tr>
                   <td width="105">'.$h.' </td>
                   <td width="85">
                   <input type="text" name="'.$nombre.'" />
                   </td>
                   </tr>';
		      }
			  echo'
			  <tr>
			  <td colspan="2"> Esta Seguro que Desea Anular las Letras</td>
			  </tr>
			  <tr>
			  <td colspan="2"><label>
			    <input type="checkbox" name="checkbox2" value="checkbox" onclick="document.frmI.Boton.disabled=!document.frmI.Boton.disabled"/>
			  Si. Seguro.</label></td>
			  </tr>
			  <tr>
			  <td colspan="2"><div align="center">
			    <input name="ocultotipo" type="hidden" id="ocultotipo" value="individual" /> 
			  <input type="submit" name="Boton" value="Anular"  disabled="disabled"/>
			  <input name="ocultocant_letra" type="hidden" id="ocultocant_letra" value="'.$cant_letra.'" />
			  </div></td>
			  </tr>
              </table>
			  </form>
              </div>';
		}
		else
		{
		    echo"<b>FUERA DE RANGO O CANTIDAD NO VALIDA</b><br>";
		}	  
		
	}
	if($selector=="intervalo")
	{
	    $ini_letra=$_POST["ini_letra"];
		$fin_letra=$_POST["fin_letra"];
		if (((is_numeric($ini_letra))and (is_numeric($fin_letra)))and($ini_letra < $fin_letra))
		{
	        echo'
            <div id="Layer3">
            <form action="anula_letra2.php" method="post" name="frmI" id="frmI">
            <table width="418" border="0">
            <tr>
            <td height="24" bgcolor="#FF0000"><div align="center" class="Estilo1">ADVERTENCIA</div></td>
            </tr>
            <tr>
            <td bgcolor="#FFFFCC"><div align="center">Seguro que desea anular las letras Que se encuentra en el sgte intervalo </div></td>
            </tr>
			<tr>
			<td bgcolor="#FFFFCE"><div align="center"><strong>'.$ini_letra.' - '.$fin_letra.'</strong></div></td>
			</tr>
            <tr>
            <td bgcolor="#FFFFCC"><label>
            <input type="checkbox" name="checkbox" value="checkbox"  onclick="document.frmI.Submit2.disabled=!document.frmI.Submit2.disabled"/>
		
			<label>Si.Seguro(a).</label>            </td>
            </tr>
            <tr>
            <td bgcolor="#FF0000"><div align="center">
              <input name="ocultotipo" type="hidden" id="ocultotipo" value="intervalo" />
              <input type="submit" name="Submit2" value="Anular"  disabled="disabled"/>
              <input name="ocultoini_letra" type="hidden" id="ocultoini_letra" value="'.$ini_letra.'" />
              <input name="ocultofin_letra" type="hidden" id="ocultofin_letra" value="'.$fin_letra.'" />
            </div>			</td>
			</tr>
            </table>
            </form>
</div>';
		}
		else
		{
		    echo"<b>FUERA DE RANGO O CANTIDAD NO VALIDA</b><br>
		    ";
		}	
	}
} 
?>
<div id="Layer1">
<form method="post" name="frmX" id="frmX">
  <table width="422" height="116" border="0">
    <tr>
      <td height="24" colspan="2" bgcolor="#CCFF00"><div align="center"><strong>Seleccione como Desea Anular Letras </strong></div></td>
    </tr>
    <tr>
      <td width="165" bgcolor="#CCCCCC"><label>
        <input name="selector" type="radio"      onselect="document.frmX.cant_letra.disabled=!document.frmX.cant_letra.disabled" value="individual" checked="checked"/>
        <strong>Individualmente</strong></label></td>
      <td width="247" bgcolor="#CCCCCC"><label><strong>Cantidad de Letras</strong> 
        <input name="cant_letra" type="text" id="cant_letra" size="5" />
      </label></td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC"><label>
        <input name="selector" type="radio" value="intervalo" />
        <strong>Por Intervalos </strong></label></td>
      <td bgcolor="#CCCCCC"><strong>Entre
          <label>
        <input name="ini_letra" type="text" id="ini_letra" size="10"  />
      y</label>
      </strong>
        <label>
      <input name="fin_letra" type="text" id="fin_letra" size="10" />
      </label></td>
    </tr>
    <tr>
      <td colspan="2" bgcolor="#CEFF00"><div align="center">
        <label>
        <input type="submit" name="Submit" value="Continuar&gt;&gt;" />
        </label>
      </div></td>
      </tr>
  </table>
  </form>
</div>
<div class="Estilo3" id="Layer5">Anulaci&oacute;n de Letras </div>
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
<div id="Layer4"><a href="../index.php" class="Estilo2">Volver al Menu </a></div>
</body>
</html>
