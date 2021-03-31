<? include ("../../SC/seguridad.php");?>
<? include ("../../SC/privilegio2.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Datos adicionales para letras</title>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:350px;
	height:115px;
	z-index:1;
	left: 152px;
	top: 109px;
}
#Layer2 {
	position:absolute;
	width:348px;
	height:196px;
	z-index:1;
	left: 158px;
	top: 411px;
}
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
.Estilo2 {color: #FFFFFF}
-->
</style>
</head>

<body>
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
       if($_SESSION)
	   {
	        include("../../../funciones/conexion.php");
			
            $rut=$_SESSION[rut_c];
	   		$carrera=$_SESSION[carrera_c];
	   		$sede=$_SESSION[sede_c];
	   		$ano=$_SESSION[ano_c];
	   		$semestre =$_SESSION[semestre_c];
			
			$consA="SELECT rut,apellido,nombre,direccion,ciudad,apoderado FROM alumno WHERE rut='$rut' and carrera='$carrera' and sede='$sede'";
			$sqlA=mysql_query($consA);
			
			while($X=mysql_fetch_array($sqlA))
			{
			    $rut_N=$X["rut"];
				$apellido=$X["apellido"];
			    $nombre=$X["nombre"];
				$apoderado=$X["apoderado"];
				$direccion=$X["direccion"];
				$ciudad=$X["ciudad"];
			}
			
			$nombre = ucwords(strtolower($nombre));
	        $apellido = ucwords(strtolower($apellido));
			
			$alumno="$nombre $apellido";
			
			if(($apoderado=="")or($apoderado="Yo Mismo"))
			{
			     $rut_apo=$rut_N;
			     $nombre_apo=$alumno;
			}
			
			$ciudad_apo=$ciudad;
			$direccion_apo=$direccion;
			
			mysql_free_result($sqlA);
			mysql_close($conexion);
			
		}
?>	
			<div id="Layer1">
			<form action="letra.php" method="post" name="frmX" id="frmX">
  <table width="349" border="0">
  
    <tr>
      <td colspan="2" bgcolor="#CCCCCC"><div align="center" class="Estilo1 Estilo2">Datos Actuales </div></td>
    </tr>
    <tr>
      <td width="143" bgcolor="#CCFF66"><strong>Rut:</strong></td>
      <td width="196" bgcolor="#CCFF66"><label>
        <input name="frut" type="text" id="frut" value="<? echo"$rut_N";?>" maxlength="10"  />
      </label></td>
    </tr>
    <tr>
      <td bgcolor="#CCFF66"><strong>Carrera:</strong></td>
      <td bgcolor="#CCFF66"><label>
        <input name="fcarrera" type="text" id="fcarrera" value="<? echo"$carrera";?>" maxlength="45" />
      </label></td>
    </tr>
    <tr>
      <td height="21" bgcolor="#CCFF66"><strong>A&ntilde;o:</strong></td>
      <td bgcolor="#CCFF66"><label>
        <input name="fano" type="text" id="fano" value="<? echo"$ano";?>" maxlength="4"  />
      </label></td>
    </tr>
    <tr>
      <td height="21" bgcolor="#CCFF66"><strong>Semestre:</strong></td>
      <td bgcolor="#CCFF66"><label>
        <input name="fsemestre" type="text" id="fsemestre" value="<? echo"$semestre";?>"  />
      </label></td>
    </tr>
    <tr>
      <td height="24" bgcolor="#CCFF66"><strong>Apoderado</strong></td>
      <td bgcolor="#CCFF66"><label>
        <input name="fapoderado" type="text" id="fapoderado" value="<? echo"$nombre_apo";?>" maxlength="30" />
      </label></td>
    </tr>
    <tr>
      <td height="24" bgcolor="#CCFF66"><strong>Rut Apoderado </strong></td>
      <td bgcolor="#CCFF66"><label>
        <input name="frut_apo" type="text" id="frut_apo" value="<? echo"$rut_apo"?>" maxlength="10" />
      </label></td>
    </tr>
    <tr>
      <td height="26" bgcolor="#CCFF66"><strong>Direccion Apoderado </strong></td>
      <td bgcolor="#CCFF66"><label>
        <input name="fdirecc_apo" type="text" id="fdirecc_apo" value="<? echo"$direccion_apo";?>" maxlength="30" />
      </label></td>
    </tr>
    <tr>
      <td height="25" bgcolor="#CCFF66"><strong>Ciudad Apoderado: </strong></td>
      <td height="25" bgcolor="#CCFF66"><label>
        <input name="fciudad_apo" type="text" id="fciudad_apo" value="<? echo"$ciudad_apo";?>" maxlength="15" />
      </label></td>
    </tr>
    <tr>
      <td height="34" colspan="2" bgcolor="#CECFCE"><div align="center">
        <label>
        <input name="respuesta" type="hidden" id="respuesta" value="SI" />
        <input type="submit" name="Submit" value="Continuar&gt;&gt;" />
        </label>
        <label>
        &nbsp;
        <input type="reset" name="Submit2" value="Restablecer" />
        </label>
      </div></td>
    </tr>
  </table>
  </form>
</div>
<?	  
	  
	  if($_POST["respuesta"]=="SI")
	  {
	     $error=0;
	      extract($_POST);
		 
		 if(($fapoderado=="")or($frut_apo=="")or ($fdirecc_apo=="")or ($fciudad_apo==""))
		 {
		     $error=1;
		 }
		  
		  if($error==0)
		  {
	      include("../../../funciones/funcion.php");
		  
		  $frut=str_inde($frut);
		  $fcarrera=str_inde($fcarrera);
		  $fano=str_inde($fano);
		  $fsemestre=str_inde($fsemestre);
		  $fapoderado=str_inde($fapoderado);
		  $frut_apo=str_inde($frut_apo);
		  $fdirecc_apo=str_inde($fdirecc_apo);
		  $fciudad_apo=str_inde($fciudad_apo);
		  
		  echo'
<div id="Layer2">
  <form action="letra2.php" method="post" name="frmZ" id="frmZ">
  <table width="347" height="194" border="0">
    <tr>
      <td colspan="2" bgcolor="#CECFCE"><div align="center" class="Estilo1">Confirmacion de Datos </div></td>
    </tr>
    <tr>
      <td width="143" bgcolor="#CEFF63"><strong>Rut:</strong></td>
      <td bgcolor="#CEFF63"><div align="right">
        '.$frut.'</div></td>
    </tr>
    <tr>
      <td bgcolor="#CEFF63"><strong>Carrera:</strong></td>
      <td bgcolor="#CEFF63"><div align="right">
        '.$fcarrera.'</div></td>
    </tr>
    <tr>
      <td bgcolor="#CEFF63"><strong>A&ntilde;o:</strong></td>
      <td bgcolor="#CEFF63"><div align="right">'.$fano.'</div></td>
    </tr>
    <tr>
      <td bgcolor="#CEFF63"><strong>Semestre:</strong></td>
      <td bgcolor="#CEFF63"><div align="right">'.$fsemestre.'</div></td>
    </tr>
    <tr>
      <td bgcolor="#CEFF63"><strong>Apoderado</strong></td>
      <td bgcolor="#CEFF63"><div align="right">
        <input name="ocu_apo" type="hidden" id="ocu_apo" value="'.$fapoderado.'" />
        '.$fapoderado.'</div></td>
    </tr>
    <tr>
      <td bgcolor="#CEFF63"><strong>Rut Apoderado </strong></td>
      <td bgcolor="#CEFF63"><div align="right">
        <input name="ocu_rutapo" type="hidden" id="ocu_rutapo" value="'.$frut_apo.'" />
        '.$frut_apo.'</div></td>
    </tr>
    <tr>
      <td bgcolor="#CEFF63"><strong>Direccion Apoderado </strong></td>
      <td bgcolor="#CEFF63"><div align="right">
        <input name="ocu_direcapo" type="hidden" id="ocu_direcapo" value="'.$fdirecc_apo.'" />
        '.$fdirecc_apo.'</div></td>
    </tr>
    <tr>
      <td bgcolor="#CEFF63"><strong>Ciudad Apoderado: </strong></td>
      <td bgcolor="#CEFF63"><div align="right">
        <input name="ocu_ciuapo" type="hidden" id="ocu_ciuapo" value="'.$fciudad_apo.'" />
        '.$fciudad_apo.'</div></td>
    </tr>
    <tr>
      <td bgcolor="#CEFF63">&nbsp;</td>
      <td bgcolor="#CEFF63">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" bgcolor="#CECFCE"><div align="center">
        <label>
        <input type="submit" name="Submit3" value="Ir A Impresion" />
        </label>
      </div></td>
    </tr>
  </table>
  </form>
</div>';
        }
		if($error==1)
		{
		    echo"No Puede haber Campos Vacios<br>";
		}
	  }
	  
	 
?>
</body>
</html>
