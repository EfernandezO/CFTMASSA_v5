<? include ("../../SC/seguridad.php");?>
<? include ("../../SC/privilegio2.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Creacion de Letras</title>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:437px;
	height:410px;
	z-index:1;
	left: 47px;
	top: 84px;
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
.Estilo1 {
	color: #FFFFFF;
	font-weight: bold;
	font-size: 18px;
}
#Layer2 {
	position:absolute;
	width:102px;
	height:25px;
	z-index:2;
	left: 51px;
	top: 93px;
}
.Estilo2 {color: #0080C0}
#Layer3 {
	position:absolute;
	width:104px;
	height:27px;
	z-index:3;
	left: 400px;
	top: 52px;
}
#Layer4 {
	position:absolute;
	width:350px;
	height:100px;
	z-index:3;
	left: 54px;
	top: 510px;
}
#Layer5 {
	position:absolute;
	width:294px;
	height:22px;
	z-index:4;
	left: 100px;
	top: 63px;
}
-->
</style>
</head>

<body>
<div id="Layer5"><strong>Generar Una Letra Especifica para Alumno </strong></div>
<table width="318" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="366"><div align="justify">
      <p><font color="#000066" size="5" face="Arial, Helvetica, sans-serif"><strong>Administrador-Finanzas<br />
        </strong><font size="2"><br />
          </font></font><font color="#000066" size="2" face="Arial, Helvetica, sans-serif"><br />
          </font><font color="#000066" size="5" face="Arial, Helvetica, sans-serif"> </font></p>
    </div></td>
  </tr>
</table>
<div id="Layer1"><img src="../../BAses/Images/base.jpg" width="438" height="409" alt="Ingresa los datos" /></div>
<div id="Layer2">
<form action="crealetra.php" method="post" name="frm" id="frm">
  <table width="424" border="0">
    <tr>
      <td colspan="2" bgcolor="#CCCCCC"><div align="center" class="Estilo1">Ingrese Los Datos </div></td>
    </tr>
    <tr>
      <td colspan="2" bgcolor="#CCFF33"><strong>Datos del Alumno </strong></td>
    </tr>
    <tr>
      <td width="152"><strong>RUT:</strong></td>
      <td width="262"><? if(!$_POST){?><input name="frut" type="text" id="frut">
        <? }else{?><input name="frut" type="text" id="frut" value="<? echo $_POST["ocultorut"]?>">
        <? }?> 
          <a href="../../Certificados/consulalumno2.php"><strong>&nbsp;?</strong> </a></td>
    </tr>
    <tr>
      <td><strong>Sede</strong></td>
      <td><label><?
	  include("../../../funciones/funcion.php");
	  echo selector_sede(); 
	  ?>
      </label></td>
    </tr>
    <tr>
      <td><strong>Carrera</strong></td>
      <td><select name="fcarrera" id="fcarrera">
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
	$_SESSION["proviene"]="crealetra";
	 ?>
        </option>
      </select></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" bgcolor="#CEFF31"><strong>Datos de la Letra </strong></td>
    </tr>
    <tr>
      <td><strong>Letra N&ordf;:</strong></td>
      <td><label>
        <input name="fnumletra" type="text" id="fnumletra" size="10" maxlength="10" />
      </label></td>
    </tr>
	<tr>
	<td><strong>Valor de Letra : </strong></td>
	<td><label>
	  <input name="fvalor" type="text" id="fvalor" size="10" maxlength="10" />
	</label></td>
	</tr>
	<tr>
	<td><strong>Fecha Aceptaci&oacute;n : </strong></td>
	<td><label>
	  dia
	  
<?
	$dia_actual = date("d"); 
    


echo('<select name="facep_dia">'); 

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
    


echo('<select name="facep_mes">'); 

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

echo'<select name="facep_ano">'; 

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
	<td><strong>Fecha Vencimiento: </strong></td>
	<td><label>dia
<?
	$dia_actual = date("d"); 
    


echo('<select name="fvenc_dia">'); 

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
    


echo('<select name="fvenc_mes">'); 

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

echo('<select name="fvenc_ano">'); 

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
      <td>&nbsp;</td>
      <td><label></label></td>
    </tr>
    <tr>
      <td colspan="2" bgcolor="#CECFCE"><div align="center">
        <label>
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
<p>&nbsp;</p>
<div id="Layer3"><a href="../index.php" class="Estilo2">Volver al Menu</a> </div>
<?
    if($_POST)
	{
	     extract($_POST);
	     include("../../../funciones/conexion.php");
	     //include("../../../funciones/funcion.php");
		 
		 $frut=str_inde($frut);
		 $fnumletra=str_inde($fnumletra);
		 $fvalor=str_inde($fvalor);
		 
		
		
		 $consA="SELECT id,rut,apellido,nombre,direccion,ciudad,apoderado FROM alumno WHERE rut='$frut' and carrera='$fcarrera' and sede='$fsede'";
		 $sql=mysql_query($consA);
		 
		 while($A=mysql_fetch_array($sql))
		 {
		     $ID=$A["id"];
			 $rut=$A["rut"];
			 $apellido=$A["apellido"];
			 $nombre=$A["nombre"];
			 $direccion=$A["direccion"];
			 $ciudad=$A["ciudad"];
			 $apoderado=$A["apoderado"];
		 }
		  $nombre = ucwords(strtolower($nombre));
	      $apellido = ucwords(strtolower($apellido));
		  $alumno="$nombre $apellido";
		   //echo"$apoderado";
		 
		 //se ve si letra existe
		 $consL="SELECT numletra,numcuota FROM letras ORDER BY numcuota";
         $sqlL=mysql_query($consL);
         $encontrado=0;
         while($B=mysql_fetch_array($sqlL))
         {
           $num_letra=$B["numletra"];
		   
	       if($fnumletra==$num_letra)
	       {
	          $encontrado=1;
		      break;
	       }
	  
        }
		//si no esta letra
		$consLN="SELECT numcuota FROM letras WHERE idalumn='$ID' ORDER BY numcuota";
		$sqlLN=mysql_query($consLN);
		$num_cuota=0;
		while($C=mysql_fetch_array($sqlLN))
		{
		   $num_cuota=$C["numcuota"];
		}
		
		 
		 
		 if(($apoderado=="")or($apoderado=="Yo Mismo"))
		 {
		     $apoderado=$alumno;
			 
			 
		 }
		     $direccion_apo=$direccion;
			 $ciudad_apo=$ciudad;
		     $rut_apo=$rut;
		  
		  if(((is_numeric($fnumletra))and(is_numeric($fvalor))and ($nombre!="")and ($encontrado==0)))
		  {
		     
		     $numero_cuota=$num_cuota + 1;
			  
		      $aceptacion="$facep_dia/$facep_mes/$facep_ano";
			  $vencimiento="$fvenc_dia/$fvenc_mes/$fvenc_ano";
			  
			  $S=actual_semestre($facep_mes);
		
		      $semestre=substr($S,0,1);
			  //echo"SEMESTRE $semestre<br>";
		  
		        echo'
<div id="Layer4">
  <form action="crealetra2.php" method="post" name="frm2" id="frm2">
  <table width="426" border="0">
    <tr>
      <td colspan="2" bgcolor="#CEFF31"><div align="center"><strong>Verifique los Datos </strong></div></td>
    </tr>
    <tr>
      <td width="143" bgcolor="#CECFCE"><span class="Estilo3">N&ordf; Letra: </span></td>
      <td width="268" bgcolor="#CECFCE"><div align="right"><span class="Estilo3">'.$fnumletra.'
        <input name="ocunum_letra" type="hidden" id="ocunum_letra" value="'.$fnumletra.'" />
      </span></div></td>
    </tr>
    <tr>
      <td bgcolor="#CECFCE"><span class="Estilo3">Alumno</span></td>
      <td bgcolor="#CECFCE"><div align="right"><span class="Estilo3">'.$alumno.'
        <input name="ocualumno" type="hidden" id="ocualumno" value="'.$alumno.'" />
      </span></div></td>
    </tr>
    <tr>
      <td bgcolor="#CECFCE"><span class="Estilo3">Fecha Aceptacion </span></td>
      <td bgcolor="#CECFCE"><div align="right"><span class="Estilo3">'.$aceptacion.'
        <input name="ocuaceptacion" type="hidden" id="ocuaceptacion" value="'.$aceptacion.'" />
      </span></div></td>
    </tr>
    <tr>
      <td bgcolor="#CECFCE"><span class="Estilo3">Vencimiento</span></td>
      <td bgcolor="#CECFCE"><div align="right"><span class="Estilo3">'.$vencimiento.'
        <input name="ocuvencimiento" type="hidden" id="ocuvencimiento" value="'.$vencimiento.'" />
      </span></div></td>
    </tr>
    <tr>
      <td bgcolor="#CECFCE"><span class="Estilo3">Valor</span></td>
      <td bgcolor="#CECFCE"><div align="right"><span class="Estilo3">$ '.$fvalor.'
            <input name="ocuvalor" type="hidden" id="ocuvalor" value="'.$fvalor.'" />
      </span></div></td>
    </tr>
    <tr>
      <td bgcolor="#CECFCE"><span class="Estilo3">Apoderado:</span></td>
      <td bgcolor="#CECFCE"><div align="right"><span class="Estilo3">
        <label>
        <input name="ocuapoderado" type="text" id="ocuapoderado" value="'.$apoderado.'" maxlength="45" />
        </label>
      </span></div></td>
    </tr>
	<tr>
	<td bgcolor="#CECFCE"><span class="Estilo3">Direccion Apoderado </span>
	<td bgcolor="#CECFCE"><div align="right">
	  <label>
	  <input name="direc_apo" type="text" id="direc_apo" value="'.$direccion_apo.'" maxlength="30" />
	  </label>
	</div>
	
	</tr>
	<tr>
	<td bgcolor="#CECFCE"><span class="Estilo3">Rut Apoderado </span>
	<td bgcolor="#CECFCE"><div align="right">
	  <label>
	  <input name="rut_apo" type="text" id="rut_apo" value="'.$rut_apo.'" maxlength="12" />
	  </label>
	</div>
	
	</tr>
	<tr>
	<td bgcolor="#CECFCE"><span class="Estilo3">Ciudad Apoderado </span></td>
	<td bgcolor="#CECFCE"><div align="right">
	  <label>
	  <input name="ciudad_apo" type="text" id="ciudad_apo" value="'.$ciudad_apo.'" maxlength="15" />
	  </label>
	</div></td>
	</tr>
    <tr>
      <td colspan="2" bgcolor="#CEFF31"><div align="center">
        <input name="ocusede" type="hidden" id="ocusede" value="'.$fsede.'" />
        <input name="ocuano" type="hidden" id="ocuano" value="$facep_ano" />
        <input name="ocusemestre" type="hidden" id="ocusemestre" value="'.$semestre.'" />
        <label>
        <input name="ocu_numcuota" type="hidden" id="ocu_numcuota" value="'.$numero_cuota.'" />
        <input type="submit" name="Submit3" value="Continuar&gt;&gt;" />
        </label>
        <label></label>
        <input name="ocudiaV" type="hidden" id="ocudiaV" value="'.$fvenc_dia.'" />
        <input name="ocumesV" type="hidden" id="ocumesV" value="'.$fvenc_mes.'" />
        <input name="ocuanoV" type="hidden" id="ocuanoV" value="'.$fvenc_ano.'" />
        <input name="ocudiaA" type="hidden" id="ocudiaA" value="'.$facep_dia.'" />
        <input name="ocumesA" type="hidden" id="ocumesA" value="'.$facep_mes.'" />
        <input name="ocuanoA" type="hidden" id="ocuanoA" value="'.$facep_ano.'" />
        <input name="ocu_ida" type="hidden" id="ocu_ida" value="'.$ID.'" />
      </div></td>
    </tr>
  </table>
  </form>
</div>';
		  
		  }
		  else
		  {
		      echo'
<div id="Layer4">
  <table width="353" height="109" border="0">
    <tr>
      <td width="347" bgcolor="#FF0000"><div align="center" class="Estilo1">Error </div></td>
    </tr>
    <tr>
      <td height="53" bgcolor="#FFFFCC"><div align="center"><strong>Los Datos introducidos No son Validos.<br />
        Posibles causas:<br />
        - El alumno No est&aacute; Registrado<br />
        -El numero de la letra ya existe<br />
        -Ingreso Datos No Validos&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></div></td>
    </tr>
    
    <tr>
      <td bgcolor="#FF0000">&nbsp;</td>
    </tr>
  </table>
</div>
';
		  }
		  
		
		  
	   
	   
	}
?>

</body>
</html>
