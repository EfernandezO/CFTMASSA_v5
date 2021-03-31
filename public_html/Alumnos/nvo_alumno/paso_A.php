<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Agrega_alumno_nuevo_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("busca_alumno_server.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCA_ALUMNO");
$xajax->register(XAJAX_FUNCTION,"VERIFICAR");

require("../../../funciones/funciones_sistema.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion_2.php");?>
<title>Nuevo ALUMNO - Paso A</title>
<?php $xajax->printJavascript(); ?> 
<script language="javascript" type="text/javascript" >
function CONTINUAR()
{
	error=true;
	condicionRut=document.getElementById('condicionRut').value;
	rut=document.getElementById('rut').value;
	nombre=document.getElementById('nombres').value;
	apellido_P=document.getElementById('apellido_P').value;
	apellido_M=document.getElementById('apellido_M').value;
	rut_apoderado=document.getElementById('rut_apoderado').value;
	
	if(condicionRut=="error")
	{
		alert('Problemas con el rut...');
		error=false
	}

	if((rut=="")||(rut==" "))
	{
		alert('Ingrese Rut');
		error=false
	}
	if((nombre=="")||(nombre==" "))
	{
		alert('Ingrese Nombres');
		error=false
	}
	if((apellido_P=="")||(apellido_P==" "))
	{
		alert('Ingrese Apellido Paterno');
		error=false
	}
	if((apellido_M=="")||(apellido_M==" "))
	{
		alert('Ingrese Apellido Materno');
		error=false
	}
	if((rut_apoderado=="")||(rut_apoderado==" "))
	{
		alert('Ingrese Rut de Apoderado');
		error=false
	}
	
	if(error){document.getElementById('frm').submit();}
}
function Salir()
{
	<?php
		$privilegio=$_SESSION["USUARIO"]["privilegio"];
		if($privilegio=="matricula")
		{$destino="HALL";}
		else
		{$destino="HALL";}
	?>
	window.location="destructor_session_matricula.php?url=<?php echo $destino;?>";
}
function UTILIZAR_DATOS_ALUMNO()
{
	//obtengo los datos
	rut_alumno=document.getElementById('rut').value;
	nombre_alumno=document.getElementById('nombres').value;
	apellido_P_alumno=document.getElementById('apellido_P').value;
	apellido_M_alumno=document.getElementById('apellido_M').value;
	
	//alert(apellido_M_alumno);
	apoderado=(nombre_alumno+" "+apellido_P_alumno+" "+apellido_M_alumno);
	
	//alert(apoderado);
	
	direccion_alumno=document.getElementById('direccion').value;
	ciudad_alumno=document.getElementById('ciudad').value;
	//asigno a los campos
	document.getElementById('rut_apoderado').value=rut_alumno;
	document.getElementById('apoderado').value=apoderado;
	document.getElementById('direccion_apoderado').value=direccion_alumno;
	document.getElementById('ciudad_apoderado').value=ciudad_alumno;
}
function FOCO()
{document.getElementById('rut').focus();}
</script>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:90%;
	height:85px;
	z-index:1;
	left: 5%;
	top: 66px;
}
.Estilo1 {font-weight: bold}
#Layer1 #frm #msj {
	font-style: normal;
	font-weight: normal;
	color: #FF0000;
	text-decoration: blink;
}
-->
</style>
</head>
<?php

$array_estado_civil=array("soltero", "casado", "divorciado", "viudo");
//var_dump($_SESSION["MATRICULA"]);

////comprobar paso ya realizados
///A y B
$paso_A_ok=false;
$paso_B_ok=false;
if(isset($_SESSION["MATRICULA"]))
{
	if(isset($_SESSION["MATRICULA"]["PASO_A"]))
	{ $paso_A_ok=$_SESSION["MATRICULA"]["PASO_A"];}
	
	if(isset($_SESSION["MATRICULA"]["PASO_B"]))
	{ $paso_B_ok=$_SESSION["MATRICULA"]["PASO_B"];}
}

/////////////////////////////////////////////////
if($paso_A_ok)
{
	
	if(isset($_SESSION["MATRICULA"]["rut_alumno"]))
	{ $rut_alu=$_SESSION["MATRICULA"]["rut_alumno"];}
	else{$rut_alu="";}
	
	if(isset($_SESSION["MATRICULA"]["condicionRut"])){
		$condicionRut=$_SESSION["MATRICULA"]["condicionRut"];
	}
	else{$condicionRut="ok";}
	
	$apellido_P=$_SESSION["MATRICULA"]["apellido_P_alumno"];
	
	$apellido_M=$_SESSION["MATRICULA"]["apellido_M_alumno"];
	
	$nombres_alu=$_SESSION["MATRICULA"]["nombres_alumno"];
	$sexo_alu=$_SESSION["MATRICULA"]["sexo_alumno"];
	$fnac_alu=$_SESSION["MATRICULA"]["fnac_alumno"];
	$aux_fecha_nac=explode("-",$fnac_alu);
	//var_export($aux_fecha_nac);
	$ano_nac=$aux_fecha_nac[0];
	$mes_nac=$aux_fecha_nac[1];
	$dia_nac=$aux_fecha_nac[2];
	$fono_alu=$_SESSION["MATRICULA"]["fono_alumno"];
	$direccion_alu=$_SESSION["MATRICULA"]["direccion_alumno"];
	$pais_origen=$_SESSION["MATRICULA"]["pais_origen"];
	
	$ciudad_alu=$_SESSION["MATRICULA"]["ciudad_alumno"];
	$correo_alu=$_SESSION["MATRICULA"]["correo_alumno"];
	$nombreC_apo=$_SESSION["MATRICULA"]["nombreC_apoderado"];
	$fono_apo=$_SESSION["MATRICULA"]["fono_apoderado"];
	
	if(isset($_SESSION["MATRICULA"]["rut_apoderado"]))
	{ $rut_apo=$_SESSION["MATRICULA"]["rut_apoderado"];}
	else{ $rut_apo="";}
	$direccion_apo=$_SESSION["MATRICULA"]["direccion_apoderado"];
	$ciudad_apo=$_SESSION["MATRICULA"]["ciudad_apoderado"];
	$estado_civil=$_SESSION["MATRICULA"]["estado_civil"];
}
else
{
	$pais_origen="Chile";
	$condicionRut="error";
	$condicionEmail="error";
}
?>
<body onload="FOCO();">
<h1 id="banner">Administrador - Nuevo Alumno Paso 1</h1>
<div id="Layer1">
<form action="paso_AX.php" method="post" name="frm" id="frm">
  <table width="80%" border="0" align="center">
  <thead>
    <tr >
      <th colspan="4"><strong>Datos del Alumno </strong></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="17%" >Rut</td>
      <td width="36%"><input type="text" name="rut"  id="rut" maxlength="11" size="11"  value="<?php if($paso_A_ok){echo $rut_alu;}?>" onchange="xajax_BUSCA_ALUMNO(this.value);return false;" tabindex="1"/></td>
      <td width="13%">Fecha Nac.</td>
      <td>
      <select name="dia" id="dia" tabindex="7">
      <?php
	  	for($d=1;$d<=31;$d++)
		{
			if($paso_A_ok)
			{
				if($d==$dia_nac)
				{
					echo'<option value="'.$d.'" selected="selected">'.$d.'</option>';
				}
				else
				{
					echo'<option value="'.$d.'">'.$d.'</option>';
				}
			}
			else
			{echo'<option value="'.$d.'">'.$d.'</option>';}
		}
      ?>
      </select>
      /
      <select name="mes" id="mes" tabindex="8">
      <?php
	  	$array_mes=array(1=>"Enero",2=>"Febrero", 3=>"Marzo", 4=>"Abril", 5=>"Mayo", 6=>"Junio",7=>"Julio",8=>"Agosto", 9=>"Septiembre", 10=>"Octubre", 11=>"Noviembre", 12=>"Diciembre");
	  	foreach($array_mes as $n => $valor)
		{
			if($paso_A_ok)
			{
				if($n == $mes_nac)
				{
					echo'<option value="'.$n.'" selected="selected">'.$valor.'</>';
				}
				else
				{
					echo'<option value="'.$n.'">'.$valor.'</>';
				}	
			}
			else
			{echo'<option value="'.$n.'">'.$valor.'</>';}	
			
		}
      ?>
      </select>
      /
      <select name="ano" id="ano" tabindex="9">
      <?php
	  if(isset($ano_nac))
	  {
	  	$ano_actual=$ano_nac;
		echo"-> $ano_nac<br>";
	  }
	  else
	  {
	  	$ano_actual=date("Y");
	  }	
	  $ano_ini=($ano_actual-100);
	  $ano_fin=($ano_actual);
      for($ano=$ano_ini;$ano<=$ano_fin;$ano++)
      {
	  	if($ano==$ano_actual)
		{echo'<option value="'.$ano.'" selected="selected">'.$ano.'</option>';}
		else
		{echo'<option value="'.$ano.'">'.$ano.'</option>';}	
      }
	  ?>
      </select></td>
    </tr>
    <tr >
      <td height="11" >&nbsp;</td>
      <td height="11"><div id="resultado"><input id="condicionRut" name="condicionRut" type="hidden" value="<?php echo $condicionRut;?>" /></div></td>
      <td height="24" rowspan="3">Sexo</td>
      <td height="11">&nbsp;</td>
    </tr>
    <tr >
      <td height="11" >Apellido Paterno</td>
      <td height="11"><input type="text" name="apellido_P" id="apellido_P" size="30" maxlength="40" value="<?php if($paso_A_ok){echo $apellido_P;}?>" tabindex="2"/>      </td>
      <td height="11"><input name="sexo" type="radio" value="M" <?php if($paso_A_ok){if($sexo_alu=="M"){echo'checked="checked"';} }else{echo'checked="checked"';}?>tabindex="10" />
        Masculino</td>
    </tr>
    <tr >
      <td height="11" >Apellido Materno</td>
      <td height="11"><input type="text" name="apellido_M" id="apellido_M" size="30" maxlength="40" value="<?php if($paso_A_ok){ echo $apellido_M;}?>" tabindex="3"/></td>
      <td height="11"><input name="sexo" type="radio" value="F" <?php if($paso_A_ok){if($sexo_alu=="F"){echo'checked="checked"';}}?> tabindex="11"/>
Femenino</td>
    </tr>
    <tr >
      <td height="24" >Nombres</td>
      <td height="24" ><input type="text" name="nombres"  id="nombres" size="30" maxlength="40" value="<?php if($paso_A_ok){ echo $nombres_alu;}?>" tabindex="4"/></td>
      <td height="24" >Fono</td>
      <td height="24" ><input type="text" name="fono" size="25" maxlength="20" value="<?php if($paso_A_ok){ echo $fono_alu;}?>" tabindex="12"/></td>
    </tr>
    <tr>
      <td >Direcci&oacute;n</td>
      <td ><input type="text" name="direccion" id="direccion" size="35" maxlength="50" value="<?php if($paso_A_ok){echo $direccion_alu;}?>" tabindex="5"/></td>
      <td >Email</td>
      <td ><div id="divEmail">
        <input type="text" name="correo" size="35" maxlength="50" value="<?php if($paso_A_ok){echo $correo_alu;}?>" tabindex="13"/>
        <input name="condicinEmail" type="hidden" id="condicinEmail" value="<?php echo $condicionEmail;?>" />
      </div></td>
    </tr>
    <tr >
      <td >Ciudad</td>
      <td ><input type="text" name="ciudad" id="ciudad" size="15" maxlength="20" value="<?php if($paso_A_ok){echo $ciudad_alu;}?>" tabindex="6"/></td>
      <td >Estado Civil</td>
      <td ><select name="estado_civil" id="estado_civil">
        <?php
        foreach($array_estado_civil as $n => $valor)
		{ 
			if($paso_A_ok)
			{
				if($valor==$estado_civil){echo'<option value="'.$valor.'" selected="selected">'.$valor.'</option>';}
				else{ echo'<option value="'.$valor.'">'.$valor.'</option>';}
			}
			else{ echo'<option value="'.$valor.'">'.$valor.'</option>';}
		}
		?>
      </select></td>
    </tr>
    <tr >
      <td >Pais Origen</td>
      <td ><?php echo CAMPO_SELECCION("pais_origen","paises",$pais_origen,false);?></td>
      <td >&nbsp;</td>
      <td ><label for="estado_civil"></label></td>
    </tr>
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    </tbody>
    </table>
    <table width="80%" align="center">
    <thead>
    <tr>
      <th colspan="4">Datos del Apoderado (<a href="#" onclick="UTILIZAR_DATOS_ALUMNO();">Utilizar Datos del Alumno</a>)</th>
    </tr>
    </thead>
    <tr>
      <td>Rut  </td>
      <td><input name="rut_apoderado" type="text" id="rut_apoderado" size="11" maxlength="10" value="<?php if($paso_A_ok){echo $rut_apo;}?>" tabindex="14"/></td>
      <td>Ciudad</td>
      <td><input name="ciudad_apoderado" type="text" id="ciudad_apoderado"  value="<?php if($paso_A_ok){ echo $ciudad_apo;}?>" tabindex="17"/></td>
    </tr>
    <tr>
      <td>Nombre Completo </td>
      <td><input type="text" name="apoderado" id="apoderado" size="35" maxlength="50" value="<?php if($paso_A_ok){echo $nombreC_apo;}?>" tabindex="15"/></td>
      <td>Fono</td>
      <td><input name="fono_apoderado" type="text" id="fono_apoderado" value="<?php if($paso_A_ok){echo $fono_apo;}?>" size="20" maxlength="20" tabindex="18"/></td>
    </tr>
    <tr>
      <td>Direccion</td>
      <td colspan="3"><input name="direccion_apoderado" type="text" id="direccion_apoderado"  value="<?php if($paso_A_ok){echo $direccion_apo;}?>" tabindex="16"/></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="3">
	  <?php
	  if($_GET)
	  {
	  		$error=base64_decode($_GET["error"]);
			$ERR=explode("|",$error);
			//var_export($ERR);
			foreach($ERR as $n => $valor)
			{
				switch($valor)
				{
					case"rut_alumno":
						$div='<div id="msj"><strong>*Rut Alumno Incorrecto*</strong></div>';
						break;
					case"rut_apoderado":
						$div='<div id="msj"><strong>*Rut Apoderado Incorrecto*</strong></div>';
						break;
					case"fecha_nac":
						$div='<div id="msj"><strong>*Fecha Nacimiento Inaceptada*</strong></div>';
						break;		
				}
				echo $div;
			}	
	  }
	  ?>	  </td>
    </tr>
    <tr>
      <td><a href="#" onclick="Salir();" class="button">Salir</a></td>
      <td colspan="2"><div align="center">
	  <?php
	  if(($paso_A_ok)and($paso_B_ok))
	  {
	  ?>
	  <a href="resumen_mat.php">Ir a Resum&eacute;n </a>
	  <?php }?>
	  </div></td>
      <td width="34%"><div align="right">
       <a href="#" onclick="xajax_VERIFICAR(xajax.getFormValues('frm'));" class="button">Continuar</a>
      </div></td>
    </tr>
    </tbody>
  </table>
  </form>
</div>
</body>
</html>