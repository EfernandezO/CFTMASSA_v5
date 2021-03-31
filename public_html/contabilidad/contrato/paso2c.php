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
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("paso_2_server.php");
$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"ASIGNAR_BENEFICIO");
$xajax->register(XAJAX_FUNCTION,"QUITAR_BENEFICIO");
$xajax->register(XAJAX_FUNCTION,"ACTUALIZAR_TABLA_BENEFICIOS");
$xajax->register(XAJAX_FUNCTION,"ACTUALIZAR_BENEFICIO");
$xajax->register(XAJAX_FUNCTION,"VERIFICAR");

////////////////////////////////////////////
 ////////////////////
 
 
 $semestre_duracion_carrera=5;//duracion normal de la carrera
 
 $array_bancos=array("Estado","Chile","Santander","Internacional","Scotiabank","Credito e Inversiones","Corpbanca","Bice","BBVA","HSBC Bank","Itau","Security","Falabella","Ripley","Consorcio","Penta","Paris");
 sort($array_bancos);
 
 
//Busco su Ultimo Contrato Generado y Verifico si tiene algun excendente/////
$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
$id_carrera_alumno=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
require("../../../funciones/conexion_v2.php");
///--------------------------------------//
	///consulto % de aprobacion del año a matricular segun toma de ramos
	$msj_info_p="";
	$year_consulta=$_SESSION["FINANZAS"]["year_estudio"];
	$mes_actual=date("m");
	
	if($mes_actual<=7){$year_consulta--;}
	else{$year_consulta=$year_consulta;}
	
	$cons_Y="SELECT DISTINCT(cod_asignatura) FROM toma_ramos WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera_alumno' AND year='$year_consulta'";
	$sqli_Y=$conexion_mysqli->query($cons_Y)or die($conexion_mysqli->error);
	$num_ramos_tomados=$sqli_Y->num_rows;
	$num_ramos_aprobados=0;
	$num_ramos_reprobados=0;
	if($num_ramos_tomados>0)
	{
		
		while($TR=$sqli_Y->fetch_row())
		{
			$TR_cod=$TR[0];
			$cons_N="SELECT nota FROM notas WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera_alumno' AND cod='$TR_cod' LIMIT 1";
			$sqli_N=$conexion_mysqli->query($cons_N)or die($conexion_mysqli->error);
			$N_1=$sqli_N->fetch_assoc();
				$N_nota=$N_1["nota"];
			if(empty($N_nota)){$N_nota=0;}
			$sqli_N->free();
			if($N_nota>=4){$num_ramos_aprobados++;}
			else{$num_ramos_reprobados++;}
			
		}
		$porcentaje_aprobacion=(($num_ramos_aprobados*100)/$num_ramos_tomados);
		$msj_info_p="Porcentaje aprobacion ramos en año $year_consulta : $porcentaje_aprobacion %";
	}
	else
	{
		$msj_info_p="No se puede calcular el % de aprobacion del año $year_consulta, sin toma de ramos";
	}
	$sqli_Y->free();
	
	
	
	//------------------------------------------------------------------------------------------//
	//beneficios contratos anterior
$cons_ex="SELECT id, excedente, cantidad_beca, porcentaje_beca, txt_beca, beca_nuevo_milenio, aporte_beca_nuevo_milenio FROM contratos2 WHERE id_alumno='$id_alumno' ORDER by id Desc LIMIT 1";
if(DEBUG){ echo"$cons_ex<br>";}
$sql_ex=$conexion_mysqli->query($cons_ex)or die("excedente ".$conexion_mysqli->error);
$D_Exc=$sql_ex->fetch_assoc();
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
		{$beca_mensaje_anterior="";}
		
	}
	else
	{ $beca_mensaje_anterior="*No hay Registro de Contrato Previo.*";}
	
	
	
if(empty($excedente_contrato_anterior))
{$excedente_contrato_anterior=0;}
$sql_ex->free();
//////////////------------------------------------------////////////////////
$paso_2_ok=false;
 if(isset($_SESSION["FINANZAS"]["paso2"]))
 {
	 if($_SESSION["FINANZAS"]["paso2"])
	 {$paso_2_ok=true;}
 }
//--------------------------------------------------------------------------------// 
 if($paso_2_ok)
 {
	 $opcion_marcada=$_SESSION["FINANZAS"]["opcion_matricula"];
	/////////////////
	switch($opcion_marcada)
	{
		case"CONTADO":
			$num_boleta=$_SESSION["FINANZAS"]["num_boleta_mat"];
			$num_cheque="";
			$fecha_vence_cheque="";
			$banco_cheque="";
			$fecha_vence="";
			break;
		case"L_CREDITO":
			$fecha_vence=$_SESSION["FINANZAS"]["fecha_vence_lcredito_mat"];
			$num_cheque="";
			$fecha_vence_cheque="";
			$banco_cheque="";
			break;
		case"CHEQUE":
			$num_cheque=$_SESSION["FINANZAS"]["num_cheque_mat"];
			$fecha_vence_cheque=$_SESSION["FINANZAS"]["fecha_vence_cheque_mat"];
			$banco_cheque=$_SESSION["FINANZAS"]["banco_cheque_mat"];
			$fecha_vence="";
			break;	
		case"NO":
			$fecha_vence="";
			$num_cheque="";
			$fecha_vence_cheque="";
			$banco_cheque="";
			break;	
	}
 }
 else
 {
	//echo"no hay nada";
	$cantidad_beca=0;
	$porcentaje_beca=0;
	$cantidad_beca=0;
	$porcentaje_beca=0;
	$num_cheque="";
	$banco_cheque="";
	$beca_nuevo_milenio="";
	$beca_excelencia_academica="";
 }
//--------------------------------------------------------------------//
$hay_error=false;
 if($_GET)
 {
	 
 	$error=$_GET["error"];
	switch($error)
	{
		case "1":
			$msj="Numero de Letra Repetido o Vacio";
			$hay_error=true;
			break;
		case "2":
			$msj="Numero de Boleta No valido";
			$hay_error=true;
			break;
		case "3":
			$msj="Ingrese todos los datos del Cheque";
			$hay_error=true;
			break;	
		case "4":
			$msj="Sin Excedente, o sin necesidad de usar esta opcion...";
			$hay_error=true;
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
<?php $xajax->printJavascript(); ?> 
 <script src="../../libreria_publica/JSCal/src/js/jscal2.js"></script>
 <script src="../../libreria_publica/JSCal/src/js/lang/es.js"></script>
 
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/steel/steel.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
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
function INICIALIZAR(){
	xajax_ACTUALIZAR_TABLA_BENEFICIOS();
}

</script>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:90%;
	height:308px;
	z-index:10;
	left: 5%;
	top: 44px;
}
#Layer3 {
	position:absolute;
	width:20%;
	height:17px;
	z-index:11;
	left: 390px;
	top: 657px;
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
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:12;
	left: 5%;
	top: 420px;
}
#div_beneficiosEstudiantilesAsignados {
	overflow:auto;
	height:150px;
	width:30%;
}
#apDiv3 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:14;
	left: 31px;
	top: 400px;
}
#div_beneficiosEstudiantilesDisponibles{
	height:100;
	width:30%;
	position:relative;
}
-->
</style>
</head>

<body onload="INICIALIZAR()">
<?php
if($hay_error){
	echo'<script language="JavaScript" type="text/javascript">
	alert("'.$msj.'");
	</script>';
}       
?>
<h1 id="banner">Contrato- Paso 2/3 (V 1.5)</h1>

<form action="paso2c_X.php" method="post" name="frm" id="frm" onkeypress = "return pulsar(event)">
<div id="Layer1">
  <table>
  <thead>
    <tr>
      <th colspan="7" bgcolor="#e5e5e5"><strong>Matricula</strong></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td height="23" colspan="7"><em>&iquest;Como paga Matricula? </em>($ <?php echo @number_format($_SESSION["FINANZAS"]["matricula"],0,",",".");?>)</td>
    </tr>

    <tr>
      <td width="15%">
 <input name="opcion_matricula" type="radio" value="NO" <?php if($paso_2_ok){ if($opcion_marcada=="NO"){?>checked="checked" <?php }}?>/>
No paga Matricula </td>
 <td colspan="2">
 <input name="opcion_matricula" type="radio" value="CHEQUE" <?php if($paso_2_ok){if($opcion_marcada=="CHEQUE"){?> checked="checked" <?php }}?>  onclick="FOCO('cheque_numero')"/>
        Cheque</td>
      <td width="20%"><input name="opcion_matricula" type="radio" value="CONTADO" <?php if($paso_2_ok){if($opcion_marcada=="CONTADO"){?>checked="checked" <?php }}elseif(!$paso_2_ok){?>checked="checked" <?php }?>/>
        Contado</td>
      <td colspan="2"><input name="opcion_matricula" type="radio" value="L_CREDITO" <?php if($paso_2_ok){if($opcion_marcada=="L_CREDITO"){?> checked="checked" <?php }}?>  onclick="FOCO('boton')"/>
        Linea credito</td>
      <td width="12%"><input name="opcion_matricula" type="radio" value="EXCEDENTE" <?php if($paso_2_ok){if($opcion_marcada=="EXCEDENTE"){?>checked="checked" <?php }}?> />
        Utilizar Excedente</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>N&deg; Cheque</td>
      <td bgcolor="#f5f5f5"><input name="cheque_numero" type="text" id="cheque_numero" value="<?php echo $num_cheque;?>" /></td>
      <td rowspan="2">Pagara en Efectivo la Matricula</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td rowspan="3" valign="top">
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
      <td width="15%">&nbsp;</td>
      <td width="17%">Banco</td>
      <td width="17%">
        <select name="cheque_banco" id="cheque_banco">
         <?php 
		 foreach($array_bancos as $n)
		 {
		 	if($n==$banco_cheque)
			{echo'<option value="'.$n.'" selected="selected">'.$n.'</option>';}
			else
			{echo'<option value="'.$n.'">'.$n.'</option>';}	
		 }
		 ?>
        </select></td>
      <td>Fecha Vence </td>
      <td><input  name="fecha_vence_cuota_mat" id="fecha_vence_cuota_mat" size="10" maxlength="10"
	   <?php
	    if(($paso_2_ok)and($opcion_marcada=="L_CREDITO"))
		{echo'value="'.$fecha_vence.'"';}
		?>
	    readonly="true"/>
        <input type="button" name="boton" id="boton" value="..." /></td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>Fecha Vence</td>
      <td><input  name="cheque_fecha_vence" id="cheque_fecha_vence" size="10" maxlength="10"
	   <?php
	    if(($paso_2_ok)and($opcion_marcada=="CHEQUE"))
		{echo'value="'.$fecha_vence_cheque.'"';}
		?>
	    readonly="true"/>
          <input type="button" name="boton2" id="boton2" value="..." /></td>
      <td>&nbsp;</td>
      <td width="9%">&nbsp;</td>
      <td width="10%">&nbsp;</td>
      </tr>
      </tbody>
      </table>
  <div id="div_beneficiosEstudiantilesDisponibles">
    <table width="100%" align="left">
      <thead>
        <tr>
          <th colspan="2">Beneficios Estudiantiles Disponibles</th>
          </tr>
        </thead>
      <tbody>
        <tr>
          <td>
            <select name="beneficiosDisponibles" id="beneficiosDisponibles">
              <?php
      	$cons_BE="SELECT * FROM beneficiosEstudiantiles WHERE beca_condicion='activa' ORDER by id";
		$sqli_BE=$conexion_mysqli->query($cons_BE);
		$numBeneficios=$sqli_BE->num_rows;
		if($numBeneficios>0){
			while($DBE=$sqli_BE->fetch_assoc()){
				$BE_id=$DBE["id"];
				$BE_nombre=$DBE["beca_nombre"];
				$BE_tipoAporte=$DBE["beca_tipo_aporte"];
				$BE_aporteValor=$DBE["beca_aporte_valor"];
				$BE_aportePorcentaje=$DBE["beca_aporte_porcentaje"];
				
				echo'<option value="'.$BE_id.'">'.$BE_nombre.'</option>';
			}
		}
		$sqli_BE->free();
	  ?>
              </select>
            </td>
          </tr>
        <tr>
          <td><a href="#" class="button_R" onclick="xajax_ASIGNAR_BENEFICIO(document.getElementById('beneficiosDisponibles').value)">Asignar</a></td>
          </tr>
        <tr></tr>
        </tbody>
    </table>
  </div>
  
     <div id="div_beneficiosEstudiantilesAsignados">
       <table width="100%" align="left">
          <thead>
            <tr>
              <th colspan="2">Beneficio Estudiantil Asignados</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
          </tbody>
       </table>
    </div>
      </div>
<div id="apDiv1">
  <table width="50%" align="left">
  <thead>
  <tr>
      <th colspan="3">Datos Contrato Anterior</th>
    </tr>
    </thead>
    <tbody>
    <tr>
    	<td colspan="3"><?php echo "$beca_mensaje_anterior";?></td>
    </tr>
    <tr>
      <td colspan="3"><strong>Saldo A Favor</strong></td>
      </tr>
    <tr>
      <td>&#9658;Por Excedente</td>
      <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="7"><?php echo "$".number_format($excedente_contrato_anterior,0,",",".");?>
        <input name="excedente" type="hidden" id="excedente" value="<?php echo $excedente_contrato_anterior;?>" />
        <em>del contrato Anterior COD.(<?php echo $id_contrato_anterior;?>)</em>
        <input name="id_contrato_anterior" type="hidden" id="id_contrato_anterior" value="<?php echo $id_contrato_anterior;?>" /></td>
    </tr>
    <tr>
      <td colspan="7"><?php if(isset($msj)){ echo"$msj<br>";}
		echo $msj_info_p;?></td>
      </tr>
      </tbody>
  </table>
  
 <table width="50%" border="1" align="center">
<thead>
  <tr>
    <th colspan="3">Becas Asignadas Contrato anterior id: <?php echo $id_contrato_anterior;?></th>
  </tr>
 </thead>
 <tbody> 
 <tr>
 	<td>N</td>
    <td>beca</td>
    <td>Aporte</td>
 </tr>
 <?php
 //////////////////////////////////////////////////
//asignaciones de beneficios
//////////////////////////////////////////////////////////
	$cons_B="SELECT * FROM beneficiosEstudiantiles_asignaciones WHERE id_alumno='$id_alumno' AND id_contrato='$id_contrato_anterior'";
	
	if(DEBUG){echo"--->$cons_B<br>";}
	$sql_B=$conexion_mysqli->query($cons_B)or die("asignaciones".$conexion_mysqli->error);
	$num_becas_asignadas=$sql_B->num_rows;;
	
	$aux_cuenta_asignacion=0;
	$totalbeneficiosEstudiantiles=0;
	if($num_becas_asignadas>0)
	{
		$hay_becas=true;
		while($B=$sql_B->fetch_assoc())
		{
			$aux_cuenta_asignacion++;
			
			$B_id=$B["id"];
			$B_id_beneficio=$B["id_beneficio"];
			$B_valor=$B["valor"];
			
			$totalbeneficiosEstudiantiles+=$B_valor;

			///////////////////////////////////////////
				$cons_B2="SELECT beca_nombre FROM beneficiosEstudiantiles WHERE id='$B_id_beneficio' LIMIT 1";
				$sql_B2=$conexion_mysqli->query($cons_B2)or die("beca".$conexion_mysqli->error);
					$DB=$sql_B2->fetch_assoc();
					$B_nombre=$DB["beca_nombre"];
				$sql_B2->free();
			///////////////////////////////////////////
			
			echo'<tr>
					<td>'.$aux_cuenta_asignacion.'</td>
					<td>'.$B_nombre.'</td>
					<td align="right">'.number_format($B_valor,0,",",".").'</td>
				 </tr>';
		}
	}
	else
	{
		echo'<tr><td colspan="3">Sin Becas Asignadas...</td></tr>';
	}
	echo'<tr><td colspan="2">TOTAL</td><td align="right">'.$totalbeneficiosEstudiantiles.'</td></tr>';
?>
 </tbody>
 </table>
  
  <p>&nbsp;</p>
  <p>&nbsp;</p>

  <table width="100%" border="1">
    <tr>
      <td width="50%"><input type="button" name="Submit2" value="&#9668;&#9668; Anterior"  onclick="Volver();"/></td>
      <td width="50%" align="right"><input type="button" name="Submit" value="Siguiente &#9658;&#9658;"  onclick="xajax_VERIFICAR();"/></td>
    </tr>
</table>
  <p>&nbsp;</p>
</div>
</form> 

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
	   <a href="resumenV2.php" class="button">Volver al Resumen</a>
	   <?php
	}
}
function SEMESTRES_CON_BECA($id_alumno, $id_carrera="")
{
	if($id_carrera>0)
	{ $condicion_carrera=" AND id_carrera='$id_carrera'";}
	else
	{ $condicion_carrera="";}
	
	$cons1="SELECT * FROM contratos2 WHERE id_alumno='$id_alumno' $condicion_carrera AND NOT condicion IN('inactivo', 'RETIRO')  ORDER by id";
	 if(DEBUG){echo"--->$cons1<br>";}
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

?>
 </div>
</body>
</html>
<?php
mysql_close($conexion);
$conexion_mysqli->close();
?>