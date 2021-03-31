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
 ////////////////////
 
 $array_bancos=array("Estado","Chile","Santander","Internacional","Scotiabank","Credito e Inversiones","Corpbanca","Bice","BBVA","HSBC Bank","Itau","Security","Falabella","Ripley","Consorcio","Penta","Paris");
 sort($array_bancos);
 
 
 $aporte_beca_nuevo_milenio=0;
 $aporte_beca_excelencia_academica=0;
 
//Busco su Ultimo Contrato Generado y Verifico si tiene algun excendente/////
$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
include("../../../funciones/conexion.php");
$cons_ex="SELECT id, excedente, cantidad_beca, porcentaje_beca, txt_beca, beca_nuevo_milenio, aporte_beca_nuevo_milenio FROM contratos2 WHERE id_alumno='$id_alumno' ORDER by id Desc LIMIT 1";
if(DEBUG){ echo"$cons_ex<br>";}
$sql_ex=mysql_query($cons_ex)or die("excedente ".mysql_error());
$D_Exc=mysql_fetch_assoc($sql_ex);
	$excedente_contrato_anterior=trim($D_Exc["excedente"]);
	$id_contrato_anterior=$D_Exc["id"];
	//beca o desc anterior
	$beca_cantidad=$D_Exc["cantidad_beca"];
	$beca_porcentaje=$D_Exc["porcentaje_beca"];
	$beca_txt=$D_Exc["txt_beca"];
	
	$C_beca_nuevo_milenio=$D_Exc["beca_nuevo_milenio"];
	$C_aporte_beca_nuevo_milenio=$D_Exc["aporte_beca_nuevo_milenio"];
	///
	if($id_contrato_anterior>0)
	{
	
		if(($beca_cantidad>0)or($beca_porcentaje>0)or($C_beca_nuevo_milenio!="sin_beca"))
		{
			$beca_mensaje_anterior="En contrato Anterior del Alumno la Condicion de la Beca Nuevo Milenio Fue: <strong>$C_beca_nuevo_milenio</strong>, Con un aporte de: <strong>$".number_format($C_aporte_beca_nuevo_milenio,0,",",".")."</strong>. Y otros Desc por: <strong>$beca_porcentaje%</strong> Desc. + $ <strong>".number_format($beca_cantidad,0,",",".")."</strong> Glosa: <em>$beca_txt</em>";
		}
		else
		{
			$beca_mensaje_anterior="";
		}
		
	}
	else
	{ $beca_mensaje_anterior="*No hay Registro de Contrato Previo.*";}
	
	
	
if(empty($excedente_contrato_anterior))
{$excedente_contrato_anterior=0;}
mysql_free_result($sql_ex);
//////////////------------------------------------------////////////////////
 
 if(isset($_SESSION["FINANZAS"]["paso2"]))
 {
	 if(($_SESSION["FINANZAS"]["paso2"]))
	 {
		//echo"hay datos"; 
		$opcion_marcada=$_SESSION["FINANZAS"]["opcion_matricula"];

		/////////////////
		$session_Y=true;
		//echo"-----> $opcion_marcada<br>";
		switch($opcion_marcada)
		{
			case"CONTADO":
				$num_boleta=$_SESSION["FINANZAS"]["num_boleta_mat"];
				$num_cheque="";
				$banco_cheque="";
				break;
			case"L_CREDITO":
				//$num_cuota=$_SESSION["FINANZAS"]["num_letra_mat"];
				$fecha_vence=$_SESSION["FINANZAS"]["fecha_vence_lcredito_mat"];
				$num_cheque="";
				$banco_cheque="";
				//echo"===> $fecha_vence";
				break;
			case"CHEQUE":
				$num_cheque=$_SESSION["FINANZAS"]["num_cheque_mat"];
				$fecha_vence_cheque=$_SESSION["FINANZAS"]["fecha_vence_cheque_mat"];
				$banco_cheque=$_SESSION["FINANZAS"]["banco_cheque_mat"];
				//echo"----> $fecha_vence_cheque";
				break;	
			case"EXCEDENTE":
				$num_cheque="";
				$banco_cheque="";
				break;	
			case"NO":
				break;	
		}
	 }
	 else
	 {
		//echo"no hay nada";
		$session_Y=false;
	 }
 }
 else
 {
 	$session_Y=false;
	$num_cheque="";
	$banco_cheque="";
 }
 ////////////////////////////////////////////////////////////////
 if($_GET)
 {
 	$error=$_GET["error"];
	switch($error)
	{
		case "1":
			$msj="Numero de Letra Repetido o Vacio";
			break;
		case "2":
			$msj="Numero de Boleta No valido";
			break;
		case "3":
			$msj="Ingrese todos los datos del Cheque";
			break;	
		case "4":
			$msj="Sin Excedente, o sin necesidad de usar esta opcion...";
			break;			
	}
 }
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<?php include("../../../funciones/codificacion.php");?>
<title>Contrato - Paso 2</title>
 <script src="../../libreria_publica/JSCal/src/js/jscal2.js"></script>
 <script src="../../libreria_publica/JSCal/src/js/lang/es.js"></script>
 
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/steel/steel.css">

<script language="javascript" type="text/javascript">
function Volver()
{
	window.location="paso1.php";
}
function FOCO(id)
{
	document.getElementById(id).focus();
}

function pulsar(e)
 {
  return (e.keyCode!=13);
}
</script>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:90%;
	height:179px;
	z-index:10;
	left: 5%;
	top: 44px;
}
#Layer3 {
	position:absolute;
	width:20%;
	height:17px;
	z-index:11;
	left: 40%;
	top: 458px;
	text-align: center;
}
a:link {
	color: #006699;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #006699;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #006699;
}
#Layer1 #frm #msjXX {
	font-weight: bold;
	color: #FF0000;
	text-decoration: blink;
}
-->
</style>
</head>

<body>
<h1 id="banner">Contrato- Paso 2/3 (V 1.4)</h1>
<div id="Layer1">
<form action="paso_2b_X.php" method="post" name="frm" id="frm" onkeypress = "return pulsar(event)">
  <table width="101%" height="103" border="0">
    <tr>
      <td colspan="7" bgcolor="#e5e5e5"><strong>Matricula</strong></td>
    </tr>
    <tr>
      <td height="23" colspan="7"><em>&iquest;Como paga Matricula? </em>($ <?php echo @number_format($_SESSION["FINANZAS"]["matricula"],0,",",".");?>)</td>
    </tr>

    <tr>
      <td width="15%" bgcolor="#f7f7f7">
 <input name="opcion_matricula" type="radio" value="NO" <?php if($session_Y){ if($opcion_marcada=="NO"){?>checked="checked" <?php }}?>/>
No paga Matricula </td>
 <td colspan="2" bgcolor="#f5f5f5">
 <input name="opcion_matricula" type="radio" value="CHEQUE" <?php if($session_Y){if($opcion_marcada=="CHEQUE"){?> checked="checked" <?php }}?>  onclick="FOCO('cheque_numero')"/>
        Cheque</td>
      <td width="20%" bgcolor="#f7f7f7"><input name="opcion_matricula" type="radio" value="CONTADO" <?php if($session_Y){if($opcion_marcada=="CONTADO"){?>checked="checked" <?php }}elseif(! $session_Y){?>checked="checked" <?php }?>/>
        Contado</td>
      <td colspan="2" bgcolor="#f5f5f5"><input name="opcion_matricula" type="radio" value="L_CREDITO" <?php if($session_Y){if($opcion_marcada=="L_CREDITO"){?> checked="checked" <?php }}?>  onclick="FOCO('boton')"/>
        Linea credito</td>
      <td width="12%" bgcolor="#f5f5f5"><input name="opcion_matricula" type="radio" value="EXCEDENTE" <?php if($session_Y){if($opcion_marcada=="EXCEDENTE"){?>checked="checked" <?php }}?> />
        Utilizar Excedente</td>
      </tr>
    <tr>
      <td bgcolor="#f7f7f7">&nbsp;</td>
      <td bgcolor="#f5f5f5">N&deg; Cheque</td>
      <td bgcolor="#f5f5f5"><input name="cheque_numero" type="text" id="cheque_numero" value="<?php echo $num_cheque;?>" /></td>
      <td rowspan="2" bgcolor="#f7f7f7">Pagara en Efectivo la Matricula</td>
      <td bgcolor="#f5f5f5">&nbsp;</td>
      <td bgcolor="#f5f5f5">&nbsp;</td>
      <td rowspan="3" valign="top" bgcolor="#f5f5f5">
        <em>
      <?php
	 if(($_SESSION["FINANZAS"]["matricula"]>0)and($excedente_contrato_anterior>0))
	 {
		 if($excedente_contrato_anterior>=$_SESSION["FINANZAS"]["matricula"])
		 {
			 $aux_excedente=($excedente_contrato_anterior-$_SESSION["FINANZAS"]["matricula"]);
			 echo"Valor Matricula: $".$_SESSION["FINANZAS"]["matricula"]." Desc. excedente: $".$_SESSION["FINANZAS"]["matricula"]." Total a pagar por Matricula: $0 Excedente Disponible para Arancel:$ $aux_excedente";
		 }
		 else
		 {
			 $diferencia_mat=($_SESSION["FINANZAS"]["matricula"]-$excedente_contrato_anterior);
			  echo"Valor Matricula: $".$_SESSION["FINANZAS"]["matricula"]." Desc. Excedente: $".$excedente_contrato_anterior." Total a pagar por Matricula: $".$diferencia_mat." Excedente Disponible para Arancel: $0";
		 }
	 }
	 else
	 {
		 echo"Opcion NO Disponible...<br>";
	 }
	 ?>
      </em>
      <br />
      *Si queda una diferencia por la matricula, debe ser pagada al contado(efectivo)*
</td>
      </tr>
    <tr>
      <td width="15%" bgcolor="#f7f7f7">&nbsp;</td>
      <td width="17%" bgcolor="#f5f5f5">Banco</td>
      <td width="17%" bgcolor="#f5f5f5">
        <select name="cheque_banco" id="cheque_banco">
         <?php 
		 foreach($array_bancos as $n)
		 {
		 	if($n==$banco_cheque)
			{
		 		echo'<option value="'.$n.'" selected="selected">'.$n.'</option>';
			}
			else
			{
				echo'<option value="'.$n.'">'.$n.'</option>';
			}	
		 }
		 ?>
        </select></td>
      <td bgcolor="#f5f5f5">Fecha Vence </td>
      <td bgcolor="#f5f5f5"><input  name="fecha_vence_cuota_mat" id="fecha_vence_cuota_mat" size="10" maxlength="10"
	   <?php
	    if(($session_Y)and($opcion_marcada=="L_CREDITO"))
		{
			echo'value="'.$fecha_vence.'"';
		}
		?>
	    readonly="true"/>
        <input type="button" name="boton" id="boton" value="..." /></td>
      </tr>
    <tr>
      <td bgcolor="#f7f7f7">&nbsp;</td>
      <td bgcolor="#f5f5f5">Fecha Vence</td>
      <td bgcolor="#f5f5f5"><input  name="cheque_fecha_vence" id="cheque_fecha_vence" size="10" maxlength="10"
	   <?php
	    if(($session_Y)and($opcion_marcada=="CHEQUE"))
		{
			echo'value="'.$fecha_vence_cheque.'"';
		}
		?>
	    readonly="true"/>
          <input type="button" name="boton2" id="boton2" value="..." /></td>
      <td bgcolor="#f7f7f7">&nbsp;</td>
      <td width="9%" bgcolor="#f5f5f5">&nbsp;</td>
      <td width="10%" bgcolor="#f5f5f5">&nbsp;</td>
      </tr>
    
    <tr>
      <td colspan="7">&nbsp;</td>
      </tr>
    <tr>
      <td colspan="7" bgcolor="#f5f5f5"><strong>Informacion Beca</strong></td>
      </tr>
    <tr>
      <td colspan="7">&#9658; Periodo <?php echo"(". $_SESSION["FINANZAS"]["vigencia_cuotas"].")";?></td>
      </tr>
    <tr>
      <td>Beca Anterior</td>
      <td colspan="6"><?php echo "$beca_mensaje_anterior";?></td>
    </tr>
    <tr>
      <td colspan="7" bgcolor="#e5e5e5"><strong>Saldo A Favor</strong></td>
      </tr>
    <tr>
      <td>&#9658;Por Excedente</td>
      <td colspan="6"><?php echo "$".number_format($excedente_contrato_anterior,0,",",".");?><input name="excedente" type="hidden" id="excedente" value="<?php echo $excedente_contrato_anterior;?>" /> 
        <em>del contrato Anterior COD.(<?php echo $id_contrato_anterior;?>)</em>
        <input name="id_contrato_anterior" type="hidden" id="id_contrato_anterior" value="<?php echo $id_contrato_anterior;?>" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td><div align="left">
        <input type="button" name="Submit2" value="&#9668;&#9668; Anterior"  onclick="Volver();"/>
      </div></td>
      <td colspan="6"><div align="right">
        <input type="submit" name="Submit" value="Siguiente &#9658;&#9658;" />
      </div></td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="6"><div id="msjXX">
        <?php if(isset($msj)){ echo"$msj";}?>
      </div></td>
      </tr>
  </table>
 </form> 
</div>
<script type="text/javascript">//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton", "fecha_vence_cuota_mat", "%Y-%m-%d");
	   cal.manageFields("boton2", "cheque_fecha_vence", "%Y-%m-%d");

    //]]></script>
 <div id="Layer3">
   <?php
if(isset($_SESSION["FINANZAS"]["paso3"]))
{
	if($_SESSION["FINANZAS"]["paso3"])
	{
		?>
	   <a href="resumen.php" class="button">Volver al Resumen</a>
	   <?php
	}
}
function SEMESTRES_CON_BECA($id_alumno)
{
	$cons1="SELECT * FROM contratos2 WHERE id_alumno='$id_alumno' AND condicion <>'inactivo' ORDER by id";
	if(DEBUG){ echo"--->$cons1<br>";}
	$sql1=mysql_query($cons1)or die(mysql_error());
	$num_contratos=mysql_num_rows($sql1);
	if($num_contratos>0)
	{
		$contador=0;
		$semestres_con_beca_NM=0;
		while($C=mysql_fetch_assoc($sql1))
		{
			$contador++;
			
			$C_id=$C["id"];
			$C_beca_nuevo_milenio=$C["beca_nuevo_milenio"];
			if(empty($C_beca_nuevo_milenio)){ $C_beca_nuevo_milenio="sin_beca";}
			$C_semestre=$C["semestre"];
			$C_ano=$C["ano"];
			$C_vigencia=$C["vigencia"];
			$C_condicion=$C["condicion"];
			
			if(DEBUG){ echo"$contador -$C_id [$C_beca_nuevo_milenio] $C_vigencia $C_condicion |$C_semestre - $C_ano|<br>";}
			
			if($C_beca_nuevo_milenio!="sin_beca")
			{
				if($C_vigencia=="anual"){ $semestres_con_beca_NM+=2;}
				if($C_vigencia=="semestral"){ $semestres_con_beca_NM+=1;}
			}
		}
	}
	else
	{
		if(DEBUG){ echo"No se encontraron contratos...<br>";}
		$semestres_con_beca_NM=0;
	}
	mysql_free_result($sql1);
	
	if(DEBUG){ echo"SEMESTRES CON BECA NUEVO MILENIO: $semestres_con_beca_NM<br>";}
	return($semestres_con_beca_NM);
}
mysql_close($conexion);
?>
 </div>
</body>
</html>