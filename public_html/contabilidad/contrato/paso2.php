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
 $array_comentarios_descuento=array( "10% Descto. Toma 2 Asignaturas",
									"10% Descto. Hijo de Profesor",
									"10% Descto.Hijo Funcionario MOP",
									"10% Descto.Funcionario MOP",
									"10% Descto. Socio Coopeuch",
									"15% Descto. Alumno Toma 1 Asignatura",
									"10% Descto. Hermano estudiando en MAssachusetts",
									"15% Descto. Convenio Ejercito");
 
 $semestre_duracion_carrera=5;//duracion normal de la carrera
 
 $array_bancos=array("Estado","Chile","Santander","Internacional","Scotiabank","Credito e Inversiones","Corpbanca","Bice","BBVA","HSBC Bank","Itau","Security","Falabella","Ripley","Consorcio","Penta","Paris");
 sort($array_bancos);
 
 $array_opciones_beca_nuevo_milenio=array("Sin Beca"=>"sin_beca", "Completa"=>"completa","Media Beca"=>"media_beca");
 //$array_opciones_beca_nuevo_milenio=array("Sin Beca"=>"sin_beca");
 
 $aporte_beca_nuevo_milenio=0;
 
 $array_opciones_beca_excelencia=array("Sin Beca"=>"sin_beca", "Completa"=>"completa");
 //$array_opciones_beca_excelencia=array("Sin Beca"=>"sin_beca");
 $aporte_beca_excelencia_academica=0;
 
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
{
	$excedente_contrato_anterior=0;
}
mysql_free_result($sql_ex);
//////////////------------------------------------------////////////////////
 if(isset($_SESSION["FINANZAS"]["paso2"]))
 {
	 if($_SESSION["FINANZAS"]["paso2"])
	 {$paso_2_ok=true;}
	 else
	 { $paso_2_ok=false;}
 }
 else
 {$paso_2_ok=false;}
//--------------------------------------------------------------------------------// 
 if($paso_2_ok)
 {
	//echo"hay datos"; 
	$opcion_marcada=$_SESSION["FINANZAS"]["opcion_matricula"];
	if(isset($_SESSION["FINANZAS"]["porcentaje_beca"])){$porcentaje_beca=$_SESSION["FINANZAS"]["porcentaje_beca"];}
	else{$porcentaje_beca=0;}
	if(isset($_SESSION["FINANZAS"]["cantidad_beca"]))
	{$cantidad_beca=$_SESSION["FINANZAS"]["cantidad_beca"];}
	else{ $cantidad_beca=0;}
	if(isset($_SESSION["FINANZAS"]["beca_nuevo_milenio"])){$beca_nuevo_milenio=$_SESSION["FINANZAS"]["beca_nuevo_milenio"];}
	else{$beca_nuevo_milenio="sin_beca";}
	if(isset($_SESSION["FINANZAS"]["aporte_beca_nuevo_milenio"])){$aporte_beca_nuevo_milenio=$_SESSION["FINANZAS"]["aporte_beca_nuevo_milenio"];}
	else{$aporte_beca_nuevo_milenio=0;}
	if(isset($_SESSION["FINANZAS"]["beca_excelencia_academica"])){$beca_excelencia_academica=$_SESSION["FINANZAS"]["beca_excelencia_academica"];}
	else{$beca_excelencia_academica="sin_beca";}
	if(isset($_SESSION["FINANZAS"]["aporte_beca_excelencia_academica"]))
	{$aporte_beca_excelencia_academica=$_SESSION["FINANZAS"]["aporte_beca_excelencia_academica"];}
	else{$aporte_beca_excelencia_academica=0;}
	if(($beca_excelencia_academica=="sin_beca")or(empty($aporte_beca_excelencia_academica)))
	{$aporte_beca_excelencia_academica=0;}
	
	
	if(empty($cantidad_beca))
	{$cantidad_beca=0;}
	if(empty($aporte_beca_nuevo_milenio))
	{$aporte_beca_nuevo_milenio=0;}
	if(empty($porcentaje_beca))
	{$porcentaje_beca=0;}
	
	/////////////////
	if(($porcentaje_beca>0)or($cantidad_beca>0)or($aporte_beca_nuevo_milenio>0))
	{$hay_beca_o_descto=true;}
	else
	{ $hay_beca_o_descto=false;}
	
	
	if(isset($_SESSION["FINANZAS"]["comentario_beca"])){$comentario=$_SESSION["FINANZAS"]["comentario_beca"];}
		else{$comentario="";}
		
	if(isset($_SESSION["FINANZAS"]["comentario_beca_v2"])){$comentario_v2=$_SESSION["FINANZAS"]["comentario_beca_v2"];}
	else{$comentario_v2="";}
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
function APLICA_BECA(tipo)
{
	campo_cantidad_beca=document.getElementById('aporte_beca_nuevo_milenio');
	valor_beca=600000;
	
	campo_glosa_beca=document.getElementById('fcomentario_beca');
	glosa_actual=campo_glosa_beca.value;
	switch(tipo)
	{
		case"completa":
			campo_cantidad_beca.value=valor_beca;
			glosa_BNM="$"+valor_beca+" Beca Nuevo Milenio";
			break;
		case"media_beca":
			campo_cantidad_beca.value=(valor_beca/2);
			glosa_BNM="$"+(valor_beca/2)+" Beca Nuevo Milenio";
			break;
		case"sin_beca":
			campo_cantidad_beca.value=0;
			glosa_BNM="";
			break;	
	}
	campo_glosa_beca.value=glosa_BNM;
	campo_cantidad_beca.focus();
}

function APLICAR_BET(tipo)
{
	campo_cantidad_beca=document.getElementById('aporte_beca_excelencia_academica');
	valor_beca=750000;
	
	campo_glosa_beca=document.getElementById('fcomentario_beca');
	glosa_actual=campo_glosa_beca.value;
	
	switch(tipo)
	{
		case"completa":
			campo_cantidad_beca.value=valor_beca;
			glosa_BET="$"+valor_beca+" Beca Excelencia Tecnica";
			break;
		case"sin_beca":
			campo_cantidad_beca.value=0;
			glosa_BET="";
			break;	
	}
	
	campo_glosa_beca.value=glosa_BET;
	campo_cantidad_beca.focus();
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
<form action="paso2_X.php" method="post" name="frm" id="frm" onkeypress = "return pulsar(event)">
  <table width="101%" height="103" border="0">
    <tr>
      <td colspan="7" bgcolor="#e5e5e5"><strong>Matricula</strong></td>
    </tr>
    <tr>
      <td height="23" colspan="7"><em>&iquest;Como paga Matricula? </em>($ <?php echo @number_format($_SESSION["FINANZAS"]["matricula"],0,",",".");?>)</td>
    </tr>

    <tr>
      <td width="15%" bgcolor="#f7f7f7">
 <input name="opcion_matricula" type="radio" value="NO" <?php if($paso_2_ok){ if($opcion_marcada=="NO"){?>checked="checked" <?php }}?>/>
No paga Matricula </td>
 <td colspan="2" bgcolor="#f5f5f5">
 <input name="opcion_matricula" type="radio" value="CHEQUE" <?php if($paso_2_ok){if($opcion_marcada=="CHEQUE"){?> checked="checked" <?php }}?>  onclick="FOCO('cheque_numero')"/>
        Cheque</td>
      <td width="20%" bgcolor="#f7f7f7"><input name="opcion_matricula" type="radio" value="CONTADO" <?php if($paso_2_ok){if($opcion_marcada=="CONTADO"){?>checked="checked" <?php }}elseif(! $paso_2_ok){?>checked="checked" <?php }?>/>
        Contado</td>
      <td colspan="2" bgcolor="#f5f5f5"><input name="opcion_matricula" type="radio" value="L_CREDITO" <?php if($paso_2_ok){if($opcion_marcada=="L_CREDITO"){?> checked="checked" <?php }}?>  onclick="FOCO('boton')"/>
        Linea credito</td>
      <td width="12%" bgcolor="#f5f5f5"><input name="opcion_matricula" type="radio" value="EXCEDENTE" <?php if($paso_2_ok){if($opcion_marcada=="EXCEDENTE"){?>checked="checked" <?php }}?> />
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
	    if(($paso_2_ok)and($opcion_marcada=="L_CREDITO"))
		{echo'value="'.$fecha_vence.'"';}
		?>
	    readonly="true"/>
        <input type="button" name="boton" id="boton" value="..." /></td>
      </tr>
    <tr>
      <td bgcolor="#f7f7f7">&nbsp;</td>
      <td bgcolor="#f5f5f5">Fecha Vence</td>
      <td bgcolor="#f5f5f5"><input  name="cheque_fecha_vence" id="cheque_fecha_vence" size="10" maxlength="10"
	   <?php
	    if(($paso_2_ok)and($opcion_marcada=="CHEQUE"))
		{echo'value="'.$fecha_vence_cheque.'"';}
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
      <td colspan="7" bgcolor="#f5f5f5"><strong>Beca</strong></td>
      </tr>
    <tr>
      <td colspan="7">&#9658;&iquest;El Alumno tiene Beca o alg&uacute;n Descuento? 
        <input name="beca" type="checkbox" id="beca" value="SI"
	  <?php
	  if(($paso_2_ok)and($hay_beca_o_descto))
	  {echo'checked="checked"';}
	  ?>
	  />
Si. Tiene </td>
      </tr>
    <tr>
      <td colspan="3">&#9658; Periodo <?php echo"(". $_SESSION["FINANZAS"]["vigencia_cuotas"].")";?></td>
      <td colspan="2" bgcolor="#f7f7f7">
      <em>Usar Beca Nuevo Milenio</em>
      <?php
      	$semestres_con_beca_NM=SEMESTRES_CON_BECA($id_alumno, $id_carrera_alumno);
		$semestres_restantes_con_BNM=($semestre_duracion_carrera-$semestres_con_beca_NM);
		 echo "($semestres_restantes_con_BNM) semestres restantes";
	  ?>
      </td>
      <td colspan="2" bgcolor="#f7f7f7">Aporte X Beca Nuevo Milenio</td>
      </tr>
    <tr>
      <td>Cantidad $        </td>
      <td><input name="cantidad_beca" type="text" id="cantidad_beca"  value="<?php echo $cantidad_beca;?>"/> </td>
      <td>&nbsp;</td>
      <td colspan="2" bgcolor="#f7f7f7">
        <select name="beca_nuevo_milenio" id="beca_nuevo_milenio" onchange="APLICA_BECA(this.value);">
         <?php
		 $mostrar_opc_BNM=false;
		 $bloquear_aporte_BNM='readonly="readonly"';
         foreach($array_opciones_beca_nuevo_milenio as $nbnm =>$valorbnm)
		 {
			 if($semestres_restantes_con_BNM>=2)
			 {
				 $mostrar_opc_BNM=true;
			 }
			 if($semestres_restantes_con_BNM==1)
			 {
				 if($valorbnm=="completa")
				 { $mostrar_opc_BNM=false;}
				 else{ $mostrar_opc_BNM=true;}
			 }
			  if($semestres_restantes_con_BNM<1)
			  {
				 if(($valorbnm=="completa")or($valorbnm=="media_beca"))
				 { $mostrar_opc_BNM=false;}
				 else{ $mostrar_opc_BNM=true;}
				 $bloquear_aporte_BNM='readonly="readonly"';
			  }
			 if($mostrar_opc_BNM)
			 {
				 if($valorbnm==$beca_nuevo_milenio)
				 {echo'<option value="'.$valorbnm.'" selected="selected">'.$nbnm.'</option>';}
				 else
				 {echo'<option value="'.$valorbnm.'">'.$nbnm.'</option>';}
			 }
		 }
		 ?>
        </select>
        <br>
        <?php echo $msj_info_p;?>
        </td>
      <td colspan="2" bgcolor="#f7f7f7">
        <input type="text" name="aporte_beca_nuevo_milenio" id="aporte_beca_nuevo_milenio"  value="<?php echo $aporte_beca_nuevo_milenio;?>" <?php echo $bloquear_aporte_BNM;?>/></td>
    </tr>
    <tr>
      <td>Porcentaje</td>
      <td colspan="2"><input name="porcentaje_beca" type="text" id="porcentaje_beca" value="<?php echo $porcentaje_beca;?>" size="5" maxlength="3" />
        %</td>
      <td colspan="2" bgcolor="#e5e5e5">Usar Beca Excelencia Tec.</td>
      <td colspan="2" bgcolor="#e5e5e5">Aporte X Beca Excelencia Tec.</td>
      </tr>
    <tr>
      <td>Comentario        </td>
      <td><label for="comentario_2">
        <select name="comentario_2" size="3" id="comentario_2">
          <?php
      foreach($array_comentarios_descuento as $n => $valor)
	  {
		  if($paso_2_ok)
		  {
			  if($comentario_v2==$valor)
			  { echo'<option value="'.$valor.'" selected="selected">'.$valor.'</option>';}
			  else{echo'<option value="'.$valor.'">'.$valor.'</option>';}
		  }
		  else{echo'<option value="'.$valor.'">'.$valor.'</option>';}
	  }
	  ?>
        </select>
      </label></td>
      <td><textarea name="fcomentario_beca" cols="15" rows="2" id="fcomentario_beca"><?php if(($paso_2_ok)and($hay_beca_o_descto))
{echo"$comentario";} ?></textarea></td>
      <td colspan="2" bgcolor="#e5e5e5"><label for="beca_excelencia_academica"></label>
        <select name="beca_excelencia_academica" id="beca_excelencia_academica" onchange="APLICAR_BET(this.value)">
        <?php 
			foreach($array_opciones_beca_excelencia as $label_BEX =>$valor_BEX)
			{
				if($valor_BEX==$beca_excelencia_academica)
				{ echo'<option value="'.$valor_BEX.'" selected="selected">'.$label_BEX.'</option>';}
				else
				{ echo'<option value="'.$valor_BEX.'">'.$label_BEX.'</option>';}
			}
		?>
        </select></td>
      <td colspan="2" bgcolor="#e5e5e5"><label for="aporte_beca_excelencia_academica"></label>
        <input name="aporte_beca_excelencia_academica" type="text" id="aporte_beca_excelencia_academica" value="<?php echo $aporte_beca_excelencia_academica?>"/></td>
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
mysql_close($conexion);
$conexion_mysqli->close();
?>
 </div>
</body>
</html>