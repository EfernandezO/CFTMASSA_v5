<?include ("../../SC/seguridad.php");?>
<?include ("../../SC/privilegio2.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:265px;
	height:52px;
	z-index:1;
	left: 153px;
	top: 275px;
}
#Layer2 {
	position:absolute;
	width:200px;
	height:102px;
	z-index:2;
	left: 135px;
	top: 156px;
}
-->
</style>
</head>
<SCRIPT LANGUAGE="JavaScript"> 
<!-- 
//programamos la funcion de enlace: 
function Enlace() 
{ 
window.location= "crealetra.php" 
} 
// --> 
</script>
<body>
<p>
  <label></label>
</p>
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
<?
   if($_POST)
   {
     extract($_POST);
	 
	 foreach($_POST as $n => $valor)
	 {
	    
		if($n!="Submit3")
		{
		   $exe='$'.$n.'2 = "'.$valor.'";';
		   eval($exe);
		   //echo"$exe<br>";
		}
	 }
	 
   }
  // echo"---> $ocu_ida2<br>";
?>
<div id="Layer1">
  <form action="crealetra3.php" method="post" name="frm" id="frm" target="_blank">
  
  <input type="button" name="Submit2" value="Volver"  onclick="Enlace()"/>
  <input type="submit" name="Submit3" value="IR a imprime Letra"/>
  <input name="ocunum_letra" type="hidden" id="ocunum_letra" value="<? echo"$ocunum_letra2";?>" />
  <input name="ocualumno" type="hidden" id="ocualumno" value="<? echo"$ocualumno2";?>" />
  <input name="ocuaceptacion" type="hidden" id="ocuaceptacion" value="<? echo"$ocuaceptacion2";?>" />
  <input name="ocuvencimiento" type="hidden" id="ocuvencimiento" value="<? echo"$ocuvencimiento2";?>" />
  <input name="ocuvalor" type="hidden" id="ocuvalor" value="<? echo"$ocuvalor2";?>" />
  <input name="ocuapoderado" type="hidden" id="ocuapoderado" value="<? echo"$ocuapoderado2";?>" />
  <input name="direc_apo" type="hidden" id="direc_apo" value="<? echo"$direc_apo2";?>" />
  <input name="rut_apo" type="hidden" id="rut_apo" value="<? echo"$rut_apo2";?>" />
  <input name="ciudad_apo" type="hidden" id="ciudad_apo" value="<? echo"$ciudad_apo2";?>" />
  <input name="ocusede" type="hidden" id="ocusede" value="<? echo"$ocusede2";?>" />
  <input name="ocuano" type="hidden" id="ocuano" value="<? echo"$ocuano2";?>" />
  <input name="ocusemestre" type="hidden" id="ocusemestre" value="<? echo"$ocusemestre2";?>" />
  <input name="ocudiaV" type="hidden" id="ocudiaV" value="<? echo"$ocudiaV2";?>" />
  <input name="ocumesV" type="hidden" id="ocumesV" value="<? echo"$ocumesV2";?>" />
  <input name="ocuanoV" type="hidden" id="ocuanoV" value="<? echo"$ocuanoV2";?>" />
  <input name="ocudiaA" type="hidden" id="ocudiaA" value="<? echo"$ocudiaA2";?>" />
  <input name="ocumesA" type="hidden" id="ocumesA" value="<? echo"$ocumesA2";?>" />
  <input name="ocuanoA" type="hidden" id="ocuanoA" value="<? echo"$ocuanoA2";?>" />
  <input name="ocu_ida" type="hidden" id="ocu_ida" value="<? echo"$ocu_ida2";?>" />
  <input name="ocu_numcuota" type="hidden" id="ocu_numcuota" value="<? echo"$ocu_numcuota2";?>" />
  </form>
</div>
<div id="Layer2">
  <textarea name="textfield" cols="30" rows="6">haga click sobre el boton Letras.
para ver las letra correspondiente
 Para volver al menu haga click sobre el
 boton  volver 
  </textarea>
</div>
<p>
  <label>  <br />
  </label>
  <label>&nbsp; </label>
  <label> &nbsp;</label>
</p>
<p></p>

</body>
</html>
