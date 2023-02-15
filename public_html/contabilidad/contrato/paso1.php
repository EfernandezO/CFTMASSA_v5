<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Matriculas_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
require("../../../funciones/conexion_v2.php");
require("../../../funciones/funciones_sistema.php");
//-----------------------------------------//
 //////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("paso1_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCA_VALORES_CARRERA");
$xajax->register(XAJAX_FUNCTION,"ACTUALIZA_FECHAS");
$xajax->register(XAJAX_FUNCTION,"CARGA_CARRERAS");
$xajax->register(XAJAX_FUNCTION,"FULL_INFO_CARRERA");
//////////------------------------////////////////
//------------------------------------datos alumno-----------------------------------
 	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	
	$sede_alumno=$_SESSION["SELECTOR_ALUMNO"]["sede"];
	$idCarreraActual=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	$jornadaActual=$_SESSION["SELECTOR_ALUMNO"]["jornada"];
	$grupoActual=$_SESSION["SELECTOR_ALUMNO"]["grupo"];
	
//------------------------------------------------------------------------------
if(isset($_SESSION["FINANZAS"]["paso1"]))
{
	if($_SESSION["FINANZAS"]["paso1"])
	{$paso_1_ok=true;}
	else
	{$paso_1_ok=false;}
}
else
{$paso_1_ok=false;}
//----------------------------------------------------------------//
 if($paso_1_ok)
 {
 	//echo"sesion <br>";
	////////////////////AUX
	$session_X=true;
	////////////////////////
	$rut_alumno=$_SESSION["SELECTOR_ALUMNO"]["rut"];///////
	$id_carrera=$_SESSION["FINANZAS"]["id_carrera"];
	$fecha_actual=$_SESSION["FINANZAS"]["fecha_inicio"];
	$expira=$_SESSION["FINANZAS"]["fecha_fin"];
	
	$ingresoCarrera=$_SESSION["FINANZAS"]["ingresoCarrera"];
	
	$arancel_alu=$_SESSION["FINANZAS"]["arancel"];
	$matricula=$_SESSION["FINANZAS"]["matricula"];
	$matricula_total=$_SESSION["FINANZAS"]["matricula_total"];
	$semestre_alu=$_SESSION["FINANZAS"]["semestre"];
	
	$vigencia_cuotas=$_SESSION["FINANZAS"]["vigencia_cuotas"];
	//$estacion_retiro=$_SESSION["FINANZAS"]["estacion_retiro"];
	$nivel=$_SESSION["FINANZAS"]["nivel"];
	$jornada=$_SESSION["FINANZAS"]["jornada"];
	$grupoX=$_SESSION["FINANZAS"]["grupo"];
	
	//echo"==> $jornada<br>";
	$rut_apo=$_SESSION["FINANZAS"]["rut_apo"];
	$apoderado_alu=$_SESSION["FINANZAS"]["nombreC_apo"];
	$direccion_apo=$_SESSION["FINANZAS"]["direccion_apo"];
	$ciudad_apo=$_SESSION["FINANZAS"]["ciudad_apo"];
	//
	@$sostenedor_nombre=$_SESSION["FINANZAS"]["sostenedor_nombre"];
	@$sostenedor_rut=$_SESSION["FINANZAS"]["sostenedor_rut"];
	$year_contrato=$_SESSION["FINANZAS"]["year_estudio"];
 }
 else
 {
	 $id_carrera=$idCarreraActual;
 	$session_X=false;
	//busco ingreso a carrera en base a constratos previos
	//15/03/2019
	
	$consIC="SELECT MAX(yearIngresoCarrera) FROM contratos2 WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera'";
	$sqliIC=$conexion_mysqli->query($consIC)or die($conexion_mysqli->error);
	$YIC=$sqliIC->fetch_row();
	$ingresoCarrera=$YIC[0];
	$sqliIC->free();
	if(DEBUG){ echo "$consIC<br>";}
	
	if((empty($ingresoCarrera))||($ingresoCarrera==0)){
		$ingresoCarrera=date("Y");	
	}
 	
 	$cons="SELECT * FROM alumno WHERE id='$id_alumno' LIMIT 1";
	
 	if(DEBUG){echo"sin Session consulto por datos.: $cons<br>";}
 	$sql=$conexion_mysqli->query($cons)or die("Datos Alumno: ".$conexion_mysqli->error);
 	$DA=$sql->fetch_assoc();
		$rut_alumno=$DA["rut"];
		$apoderado_alu=$DA["apoderado"];
		$nivel=$DA["nivel"];////agregado
		$ciudad_apo=$DA["ciudad"];
		$direccion_apo=$DA["direccion"];
		
		$grupoX=$grupoActual;
		$jornada=$jornadaActual;
	$sql->free();
	
	//limpio variable apoderado
	if(($apoderado_alu=="IDEM")or($apoderado_alu=="Sin Registro"))
	{
		$apoderado_alu=$nombre_alu." ".$apellido_alu_P;
		$rut_apo=$rut_alumno;
	}
	else
	{ $rut_apo=$rut_alumno;}
 
 	// datos de la carrera
 	
 		$fecha_actual=date("d-m-Y");
		//fecha en que contrato expira
		$mes_actual=date("m");
		$ano_actual=date("Y");
		$ano_next=$ano_actual+1;
		if($mes_actual<=6)
		{
			$semestre_actual=1;
			$expira="30-08-$ano_actual";
		}
		else
		{
			$semestre_actual=2;
			
			$expira="31-01-$ano_next";
		}
		
		//mysql_free_result($sql2);
		////sostenedor
		$sostenedor_nombre="";
		$sostenedor_rut="";
		$year_contrato=date("Y");
}
//-------------------------------------------------------------------------//

 //valores de carrera con id rescatado en cons anterior
 		$year_actual=date("Y");
		$cons3="SELECT * FROM hija_carrera_valores where id_madre_carrera='$id_carrera' AND sede='$sede_alumno' AND year='$year_contrato'";
		//echo "$cons3<br>";
		$sql3=$conexion_mysqli->query($cons3)or die("VAlores Carrera : ".$conexion_mysqli->error);
		$DV=$sql3->fetch_assoc();
		$arancel1=$DV["arancel_1"];
		$arancel2=$DV["arancel_2"];
		if(empty($arancel1)){$arancel1=0;}
		if(empty($arancel2)){$arancel2=0;}
		
		$array_arancel=array($arancel1, $arancel2);
		$arancel_anual=$DV["arancel_1"]+$DV["arancel_2"];
		//var_dump($array_arancel);
		//si no esta reg en session utilizo la de la consulta
		/*if($matricula=="")
		{*/
		$matricula=$DV["matricula"];
		if(empty($matricula)){$matricula=0;}
		$matricula_total=$matricula;
			
		//}	
		
		$array_semestre=array(1,2);
		$array_estacion=array("Primavera", "Otońo");
		$array_jornada["D"]="Diurno";
		$array_jornada["V"]="Vespertino";
		$array_grupo=array("A","B","C","D");
	$sql3->free();		
	$conexion_mysqli->close();

?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Contrato - Paso 1</title>
<?php $xajax->printJavascript(); ?> 
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:45%;
	height:115px;
	z-index:1;
	left: 49px;
	top: 46px;
}
#Layer2 {
	position:absolute;
	width:416px;
	height:115px;
	z-index:2;
	left: 311px;
	top: 0px;
}
#Layer3 {
	position:absolute;
	width:394px;
	height:17px;
	z-index:2;
	left: 60%;
	top: 430px;
	text-align: center;
}
#Layer4 {
	position:absolute;
	width:533px;
	height:25px;
	z-index:3;
	left: 54px;
}
-->
</style>
<script language="javascript" type="text/javascript">
function Redirijir()
{
	window.location="destructor_sesion_finanzas.php?url=HALL";
}
///////////////
function VERIFICAR()
{
	continuar=true;
	sostenedor=document.getElementById('frm').sostenedor;
	paga_letra=document.getElementById('frm').paga_letra;
	rut_apoderado=document.getElementById('frm').rut_apo.value;
	nombre_sostenedor=document.getElementById('frm').sostenedor_nombre.value;
	
	sostenedor_valor=radiovalue(sostenedor);
	paga_letra_valor=radiovalue(paga_letra);
	
	if(((sostenedor_valor=="apoderado")||(paga_letra_valor=="apoderado"))&&(rut_apoderado==""))
	{
		alert('Ingrese el Rut del apoderado');
		continuar=false;
	}
	if((sostenedor_valor=="otro")&&(nombre_sostenedor==""))
	{
		alert('ingrese Nombre del sostenedor')
		continuar=false;
	}
	if(continuar)
	{
		//alert('todo bien');
		document.frm.submit();
	}
}
////////devuelve el valor del radiobutton seleccionado////////
function radiovalue(radios) {
    for (i = 0; radio = radios[i]; i++) {
        if (radio.checked) {
            return radio.value;
        }
    }
}
</script>
<script src="../../libreria_publica/JSCal/src/js/jscal2.js"></script>
 <script src="../../libreria_publica/JSCal/src/js/lang/es.js"></script>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/steel/steel.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:45%;
	height:326px;
	z-index:3;
	left: 50%;
	top: 46px;
}
</style>
</head>
   <!-- para volver a como estaba comentar busca valores carrera -->
<!--body onload="xajax_BUSCA_VALORES_CARRERA(document.getElementById('carrera').value, document.getElementById('year_estudio').value, document.getElementById('lugar_contrato').value, document.getElementById('semestre').value, document.getElementById('jornada').value, document.getElementById('ingresoCarrera').value)"-->

<body onload="xajax_CARGA_CARRERAS(document.getElementById('year_estudio').value, document.getElementById('jornada').value, document.getElementById('ingresoCarrera').value, document.getElementById('lugar_contrato').value)">


<h1 id="banner">Contrato- Paso 1/3</h1>
<form action="paso1_X.php" method="post" name="frm" id="frm">
<div id="Layer1">
  <table width="95%" align="center">
    <thead>
      <tr>
        <th colspan="4"><strong>ALUMNO
          <input name="id_alumno" type="hidden" id="id_alumno" value="<?php echo $id_alumno;?>" />
          (<?php echo $id_alumno;?>) </strong></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td width="131">Rut</td>
        <td width="228" colspan="3"><?php echo"$rut_alumno";?>
          <input type="hidden" name="hiddenField" id="hiddenField" /></td>
      </tr>
      <tr>
        <td>Semestre Contrato</td>
        <td colspan="3"><select name="semestre" id="semestre" onchange="xajax_FULL_INFO_CARRERA(document.getElementById('carrera').value, document.getElementById('year_estudio').value, document.getElementById('lugar_contrato').value, document.getElementById('semestre').value, document.getElementById('jornada').value, document.getElementById('ingresoCarrera').value); return false;">
          <?php
	  if($session_X)
	  {
	  	foreach($array_semestre as $n => $valor)
		{
			if($valor==$semestre_alu)
			{echo'<option value="'.$valor.'" selected="selected">'.$valor.' Semestre</option>';}
			else
			{echo'<option value="'.$valor.'">'.$valor.' Semestre</option>';}	
		}
	  }
	  else
	  {
	  	switch ($semestre_actual)
	  	{
	  		case "1":
			
			echo'<option value="'.$array_semestre[0].'" selected="selected">'.$array_semestre[0].' Semestre</option>';
			echo'<option value="'.$array_semestre[1].'">'.$array_semestre[1].' Semestre</option>';	
				break;
				
			case "2":
				echo'<option value="'.$array_semestre[0].'" >'.$array_semestre[0].' Semestre</option>';
			echo'<option value="'.$array_semestre[1].'" selected="selected">'.$array_semestre[1].' Semestre</option>';	
				break;	
	  	}
	 }	
	  ?>
        </select></td>
      </tr>
      <tr>
        <td>A&ntilde;o Contrato</td>
        <td colspan="3"><select name="year_estudio" id="year_estudio" onchange="xajax_FULL_INFO_CARRERA(document.getElementById('carrera').value, document.getElementById('year_estudio').value, document.getElementById('lugar_contrato').value, document.getElementById('semestre').value, document.getElementById('jornada').value, document.getElementById('ingresoCarrera').value); return false;">
          <?php
				$ańo_actual=date("Y");
				$ańo_ini=1980;
				$ańo_fin=$ańo_actual+1;
				if(isset($_SESSION["FINANZAS"]["year_estudio"]))
				{$ańo_session=$_SESSION["FINANZAS"]["year_estudio"];}
            	for($a=$ańo_ini;$a<=$ańo_fin;$a++)
				{
					if($session_X)
					{
						if($a==$ańo_session)
						{echo'<option value="'.$a.'" selected="selected">'.$a.'</option>';}
						else
						{echo'<option value="'.$a.'" >'.$a.'</option>';}
					}
					else
					{
						if($a==$ańo_actual)
						{echo'<option value="'.$a.'" selected="selected">'.$a.'</option>';}
						else
						{echo'<option value="'.$a.'" >'.$a.'</option>';}
					}	
				}
			?>
        </select></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Año Ingreso Carrera</td>
        <td><?php echo CAMPO_SELECCION("ingresoCarrera","year",$ingresoCarrera,false,'onchange="xajax_FULL_INFO_CARRERA(document.getElementById(\'carrera\').value, document.getElementById(\'year_estudio\').value, document.getElementById(\'lugar_contrato\').value, document.getElementById(\'semestre\').value, document.getElementById(\'jornada\').value, document.getElementById(\'ingresoCarrera\').value)"');?></td>
        <td><strong>Jornada</strong></td>
        <td><?php echo CAMPO_SELECCION("jornada","jornada","jornada",false,'onchange="xajax_FULL_INFO_CARRERA(document.getElementById(\'carrera\').value, document.getElementById(\'year_estudio\').value, document.getElementById(\'lugar_contrato\').value, document.getElementById(\'semestre\').value, document.getElementById(\'jornada\').value, document.getElementById(\'ingresoCarrera\').value)"','jornada');?></td>
      </tr>
      <tr>
        <td><strong>Nivel (<em>Actual</em>)</strong></td>
        <td><?php echo CAMPO_SELECCION("nivel","niveles_academicos", $nivel);?></td>
        <td>Grupo</td>
        <td><select name="grupo" id="grupo">
          <?php
      foreach($array_grupo as $n =>$valor)
	  {
	  	if($grupoX==$valor)
		{echo'<option value="'.$valor.'" selected="selected">'.$valor.'</option>';}
		else
		{echo'<option value="'.$valor.'" >'.$valor.'</option>';}	
	  }
	  ?>
        </select></td>
      </tr>
      <tr>
        <td height="32">Carrera</td>
        <td colspan="3"><div id="div_carreras"><?php echo CAMPO_SELECCION("carrera","carreras", $id_carrera,false,'onchange="xajax_BUSCA_VALORES_CARRERA(document.getElementById(\'carrera\').value, document.getElementById(\'year_estudio\').value, document.getElementById(\'lugar_contrato\').value, document.getElementById(\'semestre\').value); return false;"','carrera');?></div></td>
      </tr>
      <tr>
        <td rowspan="2">&iquest;Quien paga Cuota? </td>
        <td colspan="3"><input name="paga_letra" type="radio" value="alumno" 
	  <?php 
	  if($session_X)
	  {
	  	if($_SESSION["FINANZAS"]["paga_letra"]=="alumno")
		{echo' checked="checked" ';}
	  }
	  else
	  {echo' checked="checked" ';}
	  ?>
	 
	  />
          Alumno</td>
      </tr>
      <tr>
        <td colspan="3"><input name="paga_letra" type="radio" value="apoderado"
	  <?php
	   if($session_X)
	  {
	  	if($_SESSION["FINANZAS"]["paga_letra"]=="apoderado")
		{echo' checked="checked" ';}
	  }
	  ?>
	   />
          Apoderado</td>
      </tr>
    </tbody>
  </table>
  <br>
  <table width="95%" align="center">
  <thead>
    <tr>
      <th colspan="4"><strong>FINANCIERO</strong></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="153">Arancel</td>
      <td width="206" colspan="3"><div id="div_arancel" class="div_arancel">
        <select name="arancel" id="arancel">
          <?php 
	  if($session_X)
	  {
	  	foreach($array_arancel as $n => $valor)
		{
			if($valor==$arancel_alu)
			{echo'<option value="'.$valor.'" selected="selected">'.$valor.'</option>';}
			else
			{echo'<option value="'.$valor.'">'.$valor.'</option>';}	
		}
	  }
	  else
	  {
		switch ($semestre_actual)
		{
			case"1":
				echo'<option value="'.$array_arancel[0].'" selected="selected">'.$array_arancel[0].'</option>';
				echo'<option value="'.$array_arancel[1].'" >'.$array_arancel[1].'</option>';	
				break;
			case"2":
				echo'<option value="'.$array_arancel[0].'" >'.$array_arancel[0].'</option>';
				echo'<option value="'.$array_arancel[1].'" selected="selected">'.$array_arancel[1].'</option>';	
				break;
		}
	 }	
	  ?>
          </select>
        </div></td>
    </tr>
    <tr>
      <td>Matricula</td>
      <td colspan="3">
      <input name="matricula" type="text" id="matricula"  value="<?php echo $matricula;?>" size="11" maxlength="9"/>
      <input name="matricula_total" type="hidden" value="<?php echo $matricula;?>" />
      </td>
    </tr>
    <tr>
      <td rowspan="2">Vigencia Cuotas </td>
      <td colspan="3"><input name="vigencia_cuota" type="radio" id="vigencia_cuota_s" value="semestral" 
      <?php
      if($session_X)
	  {
	  	if($vigencia_cuotas=="semestral")
		{echo'checked="checked"';}
	  }
	  else
	  {echo'checked="checked"';}
	  ?> />
        Semestrales</td>
    </tr>
    <tr>
      <td colspan="3"><input type="radio" name="vigencia_cuota" id="vigencia_cuota_a" value="anual" 
      <?php
      if($session_X)
	  {
	  	if($vigencia_cuotas=="anual")
		{echo'checked="checked"';}
	  }
	  ?>
      />
        Anuales
          <input name="arancel_anual" type="hidden" id="arancel_anual" value="<?php echo $arancel_anual;?>" /></td>
    </tr>
    <tr>
      <td rowspan="4">Sostenedor<br />
        (contrato)</td>
      <td colspan="3"><input id="sostenedor3" name="sostenedor" type="radio" value="alumno" 
	  <?php 
	  if($session_X)
	  {
	  	if($_SESSION["FINANZAS"]["sostenedor"]=="alumno")
		{echo' checked="checked" ';}
	  }
	  ?>
	  />
        Alumno</td>
    </tr>
    <tr>
      <td colspan="3"><input id="sostenedor" name="sostenedor" type="radio" value="apoderado" 
	  <?php
	   if($session_X)
	  {
	  	if($_SESSION["FINANZAS"]["sostenedor"]=="apoderado")
		{echo' checked="checked" ';}
	  }
	  else
	  {echo' checked="checked" ';}
	  ?>
	  />
        Apoderado</td>
    </tr>
    <tr>
      <td rowspan="2"><input id="sostenedor2" name="sostenedor" type="radio" value="otro"
	  <?php
	   if($session_X)
	  {
	  	if($_SESSION["FINANZAS"]["sostenedor"]=="otro")
		{echo' checked="checked" ';}
	  }
	  ?>
	   />
        Otro </td>
      <td>Nombre</td>
      <td><input name="sostenedor_nombre" type="text" id="sostenedor_nombre" value="<?php echo"$sostenedor_nombre";?>" title="Nombre"/></td>
    </tr>
    <tr>
      <td>Rut</td>
      <td><input name="sostenedor_rut" type="text" id="sostenedor_rut" value="<?php echo"$sostenedor_rut";?>"  title="Rut"/></td>
    </tr>
      </tbody>
  </table>


 </div>
<div id="Layer3">
<input type="button" name="Submit2" value="&#9668;&#9668; Menu"  onclick="Redirijir();"/>
<?php
if(isset($_SESSION["FINANZAS"]["paso3"]))
{
	if($_SESSION["FINANZAS"]["paso3"])
	{
		?>
	<a href="resumenV2.php" class="button">Volver al Resumen</a> 
	<?php
	}
}
?>
<input type="button" name="Submit" value="Siguiente &#9658;&#9658;"  onclick="VERIFICAR();"/>
</div>
<div id="apDiv1"><br>
  <table width="95%" border="0" align="center">
    <thead>
      <tr>
        <th height="20" colspan="2"><strong>GENERAL</strong></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td height="20">Lugar</td>
        <td width="208"><?php echo CAMPO_SELECCION("lugar_contrato","sede",$sede_alumno);?></td>
      </tr>
      <tr>
        <td width="153" height="26">Fecha Inicio </td>
        <td><input name="fecha_inicio" type="text" id="fecha_inicio"  value="<?php echo date("d-m-Y");?>" size="11" maxlength="10" readonly="readonly"/>
          <input type="button" name="boton1" id="boton1" value="..." /></td>
      </tr>
      <tr>
        <td height="28">Fecha Vencimiento</td>
        <td><input name="fecha_fin" type="text" id="fecha_fin"  value="<?php echo $expira;?>" size="11" maxlength="10" readonly="readonly"/>
          <input type="button" name="boton2" id="boton2" value="..." /></td>
      </tr>
    </tbody>
  </table>
  <br />
  <table width="95%" align="center">
    <thead>
    <tr>
      <th colspan="4"><strong>APODERADO</strong></th>
      </tr>
    </thead>
	<tbody>
    <tr>
      <td width="119">Rut Apoderado </td>
      <td width="240" colspan="3"><input name="rut_apo" type="text" id="rut_apo" value="<?php echo"$rut_apo";?>" size="11" maxlength="10" /></td>
    </tr>
    <tr>
      <td>Nombre Completo </td>
      <td colspan="3"><input name="nombreC_apo" type="text" id="nombreC_apo" value="<?php echo"$apoderado_alu";?>"/></td>
    </tr>
    <tr>
      <td height="24">Direccion</td>
      <td colspan="3"><input name="direccion_apo" type="text" id="direccion_apo" value="<?php echo"$direccion_apo";?>"/></td>
    </tr>
    <tr>
      <td height="24">Ciudad</td>
      <td colspan="3"><input name="ciudad_apo" type="text" id="ciudad_apo"  value="<?php echo"$ciudad_apo";?>"/></td>
    </tr>
    </tbody>
</table>
</div>
</form> 
<script type="text/javascript">
//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton1", "fecha_inicio", "%d-%m-%Y");
	   cal.manageFields("boton2", "fecha_fin", "%d-%m-%Y");

    //]]>
</script>
</body>
</html>