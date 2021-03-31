<? include ("../../SC/seguridad.php");?>
<? include ("../../SC/privilegio2.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>anula letra 2</title>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:321px;
	height:112px;
	z-index:1;
	left: 207px;
	top: 229px;
}
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
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
.Estilo3 {
	color: #FFFFFF;
	font-weight: bold;
	font-size: 16px;
}
-->
</style>
</head>

<body>

<?
if($_POST)
{
     include("../../../funciones/conexion.php");
	 include("../../../funciones/funcion.php");
    $tipo=$_POST["ocultotipo"];
	//echo"===>$tipo";
	
    if($tipo=="individual")
	{
	    $cant_letra=$_POST["ocultocant_letra"];
		$g=0;
		for($h=1;$h<=$cant_letra;$h++)
		{
		    $cadena='$letra['.$g.'] = str_inde($_POST["letra_'.$h.'"]); ';
			//echo "$cadena<br>";
			eval($cadena);
			
			$g++;
		}
		foreach($letra as $n => $valor)
		{
		    if(is_numeric($valor))
			{
			   $cons="UPDATE letras SET anulada='S' WHERE numletra='$valor'";
			   //echo"$cons<br>";
			   mysql_query($cons) or die("Letra No Anulada");
			}     
			
			
		}
		echo'
        <div id="Layer1">
         <table width="321" height="115" border="0">
         <tr>
         <td height="33" bgcolor="#CCFF00"><div align="center" class="Estilo1">INFORMACION</div>         </td>
         </tr>
          <tr>
          <td height="46" bgcolor="#CCCCCC"><div align="center" class="Estilo3">Las Letras Fueron Anuladas Exitosamente </div></td>
           </tr>
           <tr>
          <td bgcolor="#CEFF00"><div align="center"><a href="anula_letra.php" class="Estilo2">Volver</a></div></td>
          </tr>
         </table>
      </div>';
		 
	}
	if($tipo=="intervalo")
	{
	   
	     $ini_letra=str_inde($_POST["ocultoini_letra"]);
		 $fin_letra=str_inde($_POST["ocultofin_letra"]);
		 
		 // echo"$ini_letra   $fin_letra<br>";
		  
		  $I= $ini_letra;
		  $F=$fin_letra;
		while($I<=$F)
		 {
		      $conI="UPDATE letras SET anulada='S' WHERE numletra='$I'";
			  //echo"$conI<br>";
			  mysql_query($consI)or die("Letra No Anulada");
			  $I++;
		 }
		 
		 echo'
        <div id="Layer1">
         <table width="321" height="115" border="0">
         <tr>
         <td height="33" bgcolor="#CCFF00"><div align="center" class="Estilo1">INFORMACION</div>         </td>
         </tr>
          <tr>
          <td height="46" bgcolor="#CCCCCC"><div align="center" class="Estilo3">Las Letras Fueron Anuladas Exitosamente </div></td>
           </tr>
           <tr>
          <td bgcolor="#CEFF00"><div align="center"><a href="anula_letra.php" class="Estilo2">Volver</a></div></td>
          </tr>
         </table>
      </div>';
	}
	
	mysql_close($conexion);
}
?>
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
</body>
</html>
