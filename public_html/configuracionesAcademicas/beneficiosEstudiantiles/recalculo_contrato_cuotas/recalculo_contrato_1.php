<?php
//-----------------------------------------//
	require("../../../Edicion_carreras/OKALIS/seguridad.php");
	require("../../../Edicion_carreras/OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
	define("DEBUG", FALSE);
//-----------------------------------------//
$_SESSION["REASIGNAR"]["verificador"]=true;
//////////////////////XAJAX/////////////////
@require_once ("../../../Edicion_carreras/libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("recalculo_server.php");
$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"ACTUALIZA_CANTIDAD");
//////////////////////////////////////////////////

if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
{
	if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
	{ $alumno_seleccionado=true;}
	else
	{ $alumno_seleccionado=false;}
}
else
{ $alumno_seleccionado=false;}

if(isset($_GET["id_contrato"]))
{
	$id_contrato=$_GET["id_contrato"];
	if(is_numeric($id_contrato))
	{ $hay_contrato=true;}
	else
	{ $hay_contrato=false;}
}
else
{ $hay_contrato=false;}


if(($alumno_seleccionado)and($hay_contrato))
{
	//////////////////////////////////////////////////////////////
	//Parametros
	  $array_dias_disponibles=array(5,10,15,20,25,30);
	  $max_num_cuotas=11;
	  $max_dia_mes=30;
	  $array_meses=array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
	  $linea_credito_meses_avance=1;
	  $max_avance_mes=6;
	//////////////////////////////////////////////////////////////
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	$sede_alumno=$_SESSION["SELECTOR_ALUMNO"]["sede"];
	$nivel_actual_alumno=$_SESSION["SELECTOR_ALUMNO"]["nivel"];
 	include ("../../../funciones/conexion.php");
////////////////////////////////////////////////////////////
	//////contrato
/////////////////////////////////////////////////////////////	
	$cons_contrato="SELECT * FROM contratos2 WHERE id_alumno='$id_alumno' AND id='$id_contrato' LIMIT 1";
	$sql_contrato=mysql_query($cons_contrato)or die("contrato ".mysql_error());
	$D_contrato=mysql_fetch_assoc($sql_contrato);
	////obtengo datos del contrato
	$C_fecha_inicio=$D_contrato["fecha_inicio"];
	$C_fecha_fin=$D_contrato["fecha_fin"];
	$nivel_alumno_contrato=$D_contrato["nivel_alumno"];
	$numero_cuotas=$D_contrato["numero_cuotas"];
	$arancel=$D_contrato["arancel"];
	$saldo_a_favor=$D_contrato["saldo_a_favor"];
	$porcentaje_desc_contado=$D_contrato["porcentaje_desc_contado"];
	$total=$D_contrato["total"];
	$contado_paga=$D_contrato["contado_paga"];
	$cheque_paga=$D_contrato["cheque_paga"];
	$linea_credito_paga=$D_contrato["linea_credito_paga"];
	$cantidad_beca=$D_contrato["cantidad_beca"];
	$porcentaje_beca=$D_contrato["porcentaje_beca"];
	$txt_beca=$D_contrato["txt_beca"];
	$opcion_pag_matricula=$D_contrato["opcion_pag_matricula"];
	$matricula=$D_contrato["matricula_a_pagar"];
	$condicion=strtolower($D_contrato["condicion"]);
	$excedente=$D_contrato["excedente"];
	$cod_contrato_anterior=$D_contrato["id_contrato_previo"];
	$reasignado=$D_contrato["reasignado"];
	$vigencia=$D_contrato["vigencia"];
	
	$beca_nuevo_milenio=$D_contrato["beca_nuevo_milenio"];
	$aporte_beca_nuevo_milenio=$D_contrato["aporte_beca_nuevo_milenio"];
	
	$beca_excelencia=$D_contrato["beca_excelencia"];
	$aporte_beca_excelencia=$D_contrato["aporte_beca_excelencia"];
	
	$semestre_contrato=$D_contrato["semestre"];
	$year_contrato=$D_contrato["ano"];
	
	mysql_free_result($sql_contrato);
	//////////////////////////////////////////
	///valores semestrales carrera
	///////////////////////////////////////////////////////
	$cons_C1="SELECT * FROM hija_carrera_valores WHERE id_madre_carrera='$id_carrera' AND sede='$sede_alumno'";
	$sql_C1=mysql_query($cons_C1)or die(mysql_error());
		$DC=mysql_fetch_assoc($sql_C1);
			$CH_arancel[1]=$DC["arancel_1"];
			$CH_arancel[2]=$DC["arancel_2"];
		mysql_free_result($sql_C1);	
	//////////////////////////////////////////////////////////
	//total ya pagado//////////////////////////////////
///////////////////////////////////////////////	
			$cons_yc="SELECT valor, deudaXletra FROM letras WHERE idalumn='$id_alumno' AND id_contrato='$id_contrato' AND tipo='cuota'";
			if(DEBUG){ echo"---> $cons_yc<br>";}
			$sql_yc=mysql_query($cons_yc)or die(mysql_error());
			$num_cuotas=mysql_num_rows($sql_yc);
			if($num_cuotas>0)
			{
				$total_ya_cancelado=0;
				while($M=mysql_fetch_assoc($sql_yc))
				{
					$valor_cuota=$M["valor"];
					$deudaXcuota=$M["deudaXletra"];
					
					$pagado_X_cuota=($valor_cuota-$deudaXcuota);
					if(DEBUG){ echo"--->$pagado_X_cuota<br>";}
					$total_ya_cancelado+=$pagado_X_cuota;
				}	
			}
			else
			{ $total_ya_cancelado=0;}
			////Sumo pago contado con cheque registrado en contrato
			$total_ya_cancelado_cuotas=$total_ya_cancelado;
			$total_ya_cancelado+=($contado_paga+$cheque_paga);
			//////////////////////////////////
			//descuento x pago contado
			$descuento_pago_contado=(($porcentaje_desc_contado*$arancel)/100);
			
			
	////////////////////////////////////////
	//busca asignaciones de beca
/////////////////////////////////////////////////////	
$contrato_new_aporte_BNM=0;
$contrato_new_aporte_BET=0;
$contrato_new_desc_valor=0;
$contrato_new_desc_porcentaje=0;
//////////////////////////////////////////////////
//asignaciones de becas
//////////////////////////////////////////////////////////
	$cons_B="SELECT * FROM beca_asignaciones WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND estado='por_asignar' AND semestre='$semestre_contrato' AND year='$year_contrato'";
	$sql_B=mysql_query($cons_B)or die("asignaciones".mysql_error());
	$num_becas_asignadas=mysql_num_rows($sql_B);
	
	$ARRAY_BECAS=array();
	$CANTIDAD_DESCONTAR=array();
	if($num_becas_asignadas>0)
	{
		$hay_becas=true;
		while($B=mysql_fetch_assoc($sql_B))
		{
			$B_id=$B["id"];
			$B_id_beca=$B["id_beca"];
			$B_valor=$B["valor"];
			$B_glosa=$B["glosa"];
			$B_fecha_generacion=$B["fecha_generacion"];
			$B_cod_user_creador=$B["cod_user_creador"];
			$B_semestre=$B["semestre"];
			$B_year=$B["year"];

			///////////////////////////////////////////
				$cons_B2="SELECT * FROM becas WHERE id='$B_id_beca' LIMIT 1";
				$sql_B2=mysql_query($cons_B2)or die("beca".mysql_error());
					$DB=mysql_fetch_assoc($sql_B2);
					$B_nombre=$DB["beca_nombre"];
					$B_vigencia=$DB["vigencia"];
					$B_tipo_aporte=$DB["beca_tipo_aporte"];
				mysql_free_result($sql_B2);	
			///////////////////////////////////////////

			$ARRAY_BECAS[$B_id]["nombre_beca"]=$B_nombre;
			$ARRAY_BECAS[$B_id]["id_beca"]=$B_id_beca;
			$ARRAY_BECAS[$B_id]["valor"]=$B_valor;
			$ARRAY_BECAS[$B_id]["glosa"]=$B_glosa;
			$ARRAY_BECAS[$B_id]["fecha_generacion"]=$B_fecha_generacion;
			$ARRAY_BECAS[$B_id]["cod_user_creador"]=$B_cod_user_creador;
			$ARRAY_BECAS[$B_id]["tipo_aporte"]=$B_tipo_aporte;
			
			if(DEBUG){ echo"VIGENCIA BECA: $B_vigencia VALOR: $B_valor tipo aporte: $B_tipo_aporte<br>";}
			//////////////////////////////
			//clasificacion de becas para contrato
			////////////////////////////////////////////
			switch(strtolower($B_nombre))
			{
				case"beca nuevo milenio":
					$contrato_new_aporte_BNM+=$B_valor;
					break;
				case"beca excelencia tecnica":
					$contrato_new_aporte_BET+=$B_valor;
					break;
				case"hijo de profesor":
					$contrato_new_desc_porcentaje+=$B_valor;
					break;
				default:
					$contrato_new_desc_valor+=$B_valor;
					break;	
			}
			////////////////////////////////////
			
			
			
				switch($B_vigencia)
				{
					case"semestral":
						if(!isset($CANTIDAD_DESCONTAR[$B_semestre])){ $CANTIDAD_DESCONTAR[$B_semestre]=0;}
						switch($B_tipo_aporte)
						{
							case"valor":
								$CANTIDAD_DESCONTAR[$B_semestre]+=$B_valor;
								
								if(isset($CANTIDAD_DESCONTAR["periodo"]))
								{
									if($nivel_actual_alumno<5)
									{
										if($CANTIDAD_DESCONTAR["periodo"]!="anual")
										{$CANTIDAD_DESCONTAR["periodo"]="semestral";}
									}
									else
									{
										if(DEBUG){ echo"Utilizar Vigencia Semestral X nivel alumno...<br>";}
										$CANTIDAD_DESCONTAR["periodo"]="semestral";
									}
								}
								else
								{ $CANTIDAD_DESCONTAR["periodo"]="semestral";}
								
								if(DEBUG){ echo"<strong>$B_valor</strong><br>";}
								break;
							case"porcentaje":
								$aux_descuento=(($CH_arancel[$B_semestre]*$B_valor)/100);
								$CANTIDAD_DESCONTAR[$B_semestre]+=$aux_descuento;
								
								if(isset($CANTIDAD_DESCONTAR["periodo"]))
								{
									if($CANTIDAD_DESCONTAR["periodo"]!="anual")
									{$CANTIDAD_DESCONTAR["periodo"]="semestral";}
								}
								else
								{ $CANTIDAD_DESCONTAR["periodo"]="semestral";}
								
								if(DEBUG){ echo"<strong>$aux_descuento</strong><br>";}
								break;
						}
						break;
					case"anual":
						if(!isset($CANTIDAD_DESCONTAR[$B_semestre])){ $CANTIDAD_DESCONTAR[$B_semestre]=0;}
						if($nivel_actual_alumno<5)
						{
							if(DEBUG){ echo"Nivel Alumno menor a 5 se puede hacer  beca anual....<br>";}
							switch($B_tipo_aporte)
							{
								case"valor":
									$CANTIDAD_DESCONTAR[$B_semestre]+=$B_valor;
									$CANTIDAD_DESCONTAR["periodo"]="anual";
									if(DEBUG){ echo"<strong>$B_valor</strong><br>";}
									break;
								case"porcentaje":
									$aux_descuento=(($CH_arancel[$B_semestre]*$B_valor)/100);
									$CANTIDAD_DESCONTAR[$B_semestre]+=$aux_descuento;
									$CANTIDAD_DESCONTAR["periodo"]="anual";
									if(DEBUG){ echo"<strong>$aux_descuento</strong><br>";}
									break;
							}
						}
						else
						{
							//////////alumno nivel 5
							if(DEBUG){ echo"Alumno Nivel >=5<br>";}
							switch($B_tipo_aporte)
							{
								case"valor":
									$CANTIDAD_DESCONTAR[$B_semestre]+=$B_valor;
									if(isset($CANTIDAD_DESCONTAR["periodo"]))
									{$CANTIDAD_DESCONTAR["periodo"]="semestral";}
									if(DEBUG){ echo"<strong>$B_valor</strong><br>";}
									break;
								case"porcentaje":
									$aux_descuento=(($CH_arancel[$B_semestre]*$B_valor)/100);
									$CANTIDAD_DESCONTAR[$B_semestre]+=$aux_descuento;
									
									if(isset($CANTIDAD_DESCONTAR["periodo"]))
									{$CANTIDAD_DESCONTAR["periodo"]="semestral";}
									if(DEBUG){ echo"<strong>$aux_descuento</strong><br>";}
								break;
							}
						}
						break;
				}
		}
	}
	else
	{
		$hay_becas=false;
	}
	mysql_free_result($sql_B);
	
	if(DEBUG){ var_dump($CANTIDAD_DESCONTAR);}
	
	///////////////////////////////
	//CALCULOS//
	///////////////////////////////
	$saldo_a_favor_new=($excedente+$saldo_a_favor+$total_ya_cancelado+$descuento_pago_contado);	
	
	if($hay_becas)
	{
			$periodo_descuento=$CANTIDAD_DESCONTAR["periodo"];
			if(isset($CANTIDAD_DESCONTAR[1])){$descuento_semestre_1=$CANTIDAD_DESCONTAR[1];}
			else{$descuento_semestre_1=0;}
			if(isset($CANTIDAD_DESCONTAR[2])){$descuento_semestre_2=$CANTIDAD_DESCONTAR[2];}
			else{$descuento_semestre_2=0;}
			
		switch($CANTIDAD_DESCONTAR["periodo"])
		{
			case"semestral":
				$TOTAL_A_PACTAR=($CH_arancel[$semestre_contrato]-$saldo_a_favor_new-$descuento_semestre_1-$descuento_semestre_2);
				break;
			case"anual":
				$TOTAL_A_PACTAR=(($CH_arancel[1]+$CH_arancel[2])-$total_ya_cancelado-$excedente-$saldo_a_favor-$descuento_semestre_1-$descuento_semestre_2);
				break;
		}
		
		//////////////////////////////////////////
		//define que mostrar
		////////////////////////////////////////
		
		
			if($TOTAL_A_PACTAR>0)
			{ $hay_excedente=false; $generar_cuota=true; $nuevo_excedente=0; $total_a_pactar_cuotas=$TOTAL_A_PACTAR; $mostrar_boton_final=false;}
			else
			{ $hay_excedente=true; $generar_cuota=false; $nuevo_excedente=abs($TOTAL_A_PACTAR); $total_a_pactar_cuotas=0; $mostrar_boton_final=true;}
		
	}
	
	if($mostrar_boton_final)
	{
		if($hay_excedente)
		{  $boton_final='<a href="#" onclick="VERIFICAR2();" class="button_G">GRABAR CONTRATO</a>';}
		else
		{ $boton_final="";}
	}
	else
	{ $boton_final="";}
			
	
			
mysql_close($conexion);	
}
else
{ if(DEBUG){ echo"Sin Acceso<br>";}}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-3" />
<title>Recalculo de Contrato</title>
<?php $xajax->printJavascript(); ?> 
<script language="javascript" type="text/javascript">
function Verificar()
{
	c=confirm('Seguro(a) Desea Continuar\n Se Generara un Nuevo Contrato y crearan Nuevas mensualidades(Las Antiguas se Eliminaran)');
	if(c)
	{
		//document.getElementById('comentario_beca_X').value=document.getElementById('comentario_beca_main').value;
		document.frm.submit();
	}
}
function VERIFICAR2()
{
	c=confirm('Seguro(a) Desea Continuar\n generara un nuevo contrato y eliminara las actuales mensualidades\n quedara un saldo a favor del alumno');
	if(c)
	{
		//document.getElementById('comentario_beca_Y').value=document.getElementById('comentario_beca_main').value;
		document.frm1.submit();
	}
}
</script>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:30%;
	height:91px;
	z-index:1;
	left: 5%;
	top: 69px;
}
</style>
<link rel="stylesheet" type="text/css" href="../../../Edicion_carreras/libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../Edicion_carreras/CSS/tabla_2.css"/>
<link rel="stylesheet" type="text/css" href="../../../Edicion_carreras/libreria_publica/hint.css-master/hint.css"/>
<style type="text/css">
#apDiv2 {
	position:absolute;
	width:30%;
	height:115px;
	z-index:2;
	left: 40%;
	top: 87px;
}
#apDiv3 {
	position:absolute;
	width:40%;
	height:115px;
	z-index:3;
	left: 40%;
	top: 237px;
}
#botonera {
	position:absolute;
	width:30%;
	height:20px;
	z-index:4;
	left: 5%;
	top: 648px;
	text-align: center;
}
</style>
</head>
<body>
<h1 id="banner">Re-Calculo de Contrato</h1>
<div id="link"><br />
<a href="../../../Edicion_carreras/contabilidad/informe_financiero_alumno/index.php" class="button">Volver a Seleccion</a></div>

<div id="apDiv1">
<form name="frm_info" id="frm_info">
  <table width="100%" border="0" align="center">
  	<thead>
    <tr>
      <th colspan="2">Información Contrato anterior</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td >Arancel semestre 1</td>
      <td >
        <input type="text" name="arancel_1" id="arancel_1"  value="<?php echo $CH_arancel[1];?>"/></td>
    </tr>
    <tr>
      <td >Arancel semestre 2</td>
      <td ><input name="arancel_2" type="text" id="arancel_2" readonly="readonly" value="<?php echo $CH_arancel[2];?>"/></td>
    </tr>
    <tr>
      <td >Excedente Anterior</td>
      <td><input name="excedente_anterior" type="text" id="excedente_anterior"  value="<?php echo $excedente;?>"/></td>
    </tr>
    <tr>
      <td >Saldo a Favor</td>
      <td><label for="saldo_a_favor"></label>
        <input type="text" name="saldo_a_favor" id="saldo_a_favor"  value="<?php echo $saldo_a_favor;?>"/></td>
    </tr>
    <tr>
      <td >Pago Contado previo</td>
      <td>
        <input name="pago_contado" type="text" id="pago_contado" value="<?php echo $contado_paga;?>" /></td>
    </tr>
    <tr>
      <td >Desc Pago Contado (<?php echo $porcentaje_desc_contado;?>%)</td>
      <td><input type="text" name="desc_pago_contado" id="desc_pago_contado"  value="<?php echo $descuento_pago_contado;?>"/></td>
    </tr>
    <tr>
      <td >Total Cancelado cuota</td>
      <td><input type="text" name="total_cancelado" id="total_cancelado" readonly="readonly" value="<?php echo $total_ya_cancelado_cuotas;?>"/></td>
    </tr>
    <tr>
      <td >&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td ><strong>TOTAL A FAVOR</strong></td>
      <td><strong><?php echo "$ ".number_format($saldo_a_favor_new,0,",",".");?></strong></td>
    </tr>
      </tbody>
  </table>
  <br />
<br />
  <table width="100%">
      <thead>
      <th colspan="2">Descuentos</th>
        </thead>
      <tbody>
<tr>
    <td>Vigencia</td>
    <td><?php echo $periodo_descuento; ?></td>
</tr>
<tr>
    <td>Semestre 1</td>
    <td><?php echo $descuento_semestre_1; ?></td>
</tr>
<tr>
    <td>Semestre 2</td>
    <td><?php echo $descuento_semestre_2; ?></td>
</tr>
<tr>
    <td>Total</td>
    <td><?php echo ($descuento_semestre_1+$descuento_semestre_2); ?></td>
</tr>
<tr>
    <td><strong>TOTAL A PAGAR</strong></td>
    <td><strong><?php echo "$ ".number_format($TOTAL_A_PACTAR,0,",","."); ?></strong></td>
</tr>
  </tbody>
  </table>
 </form>
</div>
<div id="apDiv2">
<table width="100%" border="1" align="center">
<thead>
  <tr>
    <th colspan="6">Becas Asignadas</th>
  </tr>
 </thead>
 <tbody> 
 <tr>
 	<td>N</td>
    <td>beca</td>
    <td>glosa</td>
    <td>Quien</td>
    <td>Aporte</td>
    <td>-</td>
 </tr>
  <?php
  if($num_becas_asignadas>0)
  {
	  $aux=0;
	  foreach($ARRAY_BECAS as $n => $array_b)
	  {
		  $aux++;
		  $aux_beca_nombre=$array_b["nombre_beca"];
		  $aux_valor=$array_b["valor"];
		  $aux_glosa=$array_b["glosa"];
		  $aux_quien=$array_b["cod_user_creador"];
		  $aux_tipo_aporte=$array_b["tipo_aporte"];
		  
		  switch($aux_tipo_aporte)
		  {
			  case"valor":
			  	$valor_label="$ ".$aux_valor;
			  	break;
			  case"porcentaje":
			  	$valor_label=$aux_valor." %";
			  	break;
		  }
		  echo'<tr>
		  			<td>'.$aux.'</td>
					<td>'.$aux_beca_nombre.'</td>
					<td><a href="#" class="hint--left hint--info" data-hint="'.$aux_glosa.'">'.substr($aux_glosa,0,3).'</a></td>
					<td>'.$aux_quien.'</td>
					<td>'.$valor_label.'</td>
					<td><img src="../../BAses/Images/ok.png" width="29" height="26" alt="ok" /></td>
		  		</tr>';
	  }
  }
  else
  {echo'<tr><td colspan="5">Sin Becas Asignadas</td></tr>'; }
  ?>
 </tbody> 
</table>
</div>
<div id="apDiv3">
<?php if($hay_excedente){?>
<form action="genera_excedente.php" method="post" name="frm1" id="frm1">
<table width="100%" align="center">
<thead>
  <tr>
    <th colspan="2">Informacion Nuevo Contrato (Excedente)
     <input name="validador" type="hidden" value="<?php echo md5("recalculo_cuota".date("Y-m-d"));?>" />
    <input name="id_contratoX" type="hidden" value="<?php echo $id_contrato;?>" />
    <input name="vigencia" type="hidden" value="<?php echo $periodo_descuento;?>" />
    <input name="arancel_1" type="hidden" value="<?php echo $CH_arancel[1];?>" />
    <input name="arancel_2" type="hidden" value="<?php echo $CH_arancel[2];?>" />
    <input name="saldo_a_favor" type="hidden" value="<?php echo $saldo_a_favor_new;?>" />
    <input name="aporte_BNM" type="hidden" value="<?php echo $contrato_new_aporte_BNM;?>" />
    <input name="aporte_BET" type="hidden" value="<?php echo $contrato_new_aporte_BET;?>" />
    <input name="desc_porcentaje" type="hidden" value="<?php echo $contrato_new_desc_porcentaje;?>" />
    <input name="desc_valor" type="hidden" value="<?php echo $contrato_new_desc_valor;?>" />
    <input name="porcentaje_desc_contado" type="hidden" value="<?php echo $porcentaje_desc_contado;?>" />
    </th>
    </tr>
   </thead>
   <tbody> 
  <tr>
    <td>Excedente A Favor Alumno</td>
    <td><input name="excedente_valor" type="text" id="excedente_valor" value="<?php echo $nuevo_excedente;?>" /></td>
  </tr>
  <tr>
    <td colspan="2"><div align="right">...</div>
    </td>
    </tr>
    </tbody>
</table>
</form>
<?php }?>
<?php if($generar_cuota){?>
<form action="genera_cuotas.php" method="post" name="frm" id="frm">
        <table width="100%" border="0">
        <thead>
          <tr>
            <th colspan="4">Informacion Nuevo Contrato (Linea Credito)
            <input name="validador" type="hidden" value="<?php echo md5("recalculo_cuota".date("Y-m-d"));?>" />
            <input name="id_contratoX" type="hidden" value="<?php echo $id_contrato;?>" />
            <input name="vigencia" type="hidden" value="<?php echo $periodo_descuento;?>" />
            <input name="arancel_1" type="hidden" value="<?php echo $CH_arancel[1];?>" />
            <input name="arancel_2" type="hidden" value="<?php echo $CH_arancel[2];?>" />
            <input name="saldo_a_favor" type="hidden" value="<?php echo $saldo_a_favor_new;?>" />
            <input name="aporte_BNM" type="hidden" value="<?php echo $contrato_new_aporte_BNM;?>" />
            <input name="aporte_BET" type="hidden" value="<?php echo $contrato_new_aporte_BET;?>" />
            <input name="desc_porcentaje" type="hidden" value="<?php echo $contrato_new_desc_porcentaje;?>" />
            <input name="desc_valor" type="hidden" value="<?php echo $contrato_new_desc_valor;?>" />
            <input name="porcentaje_desc_contado" type="hidden" value="<?php echo $porcentaje_desc_contado;?>" />
            </th>
          </tr>
          </thead>
          <tbody>
          <tr>
            <td width="136">Cantidad</td>
            <td colspan="3"><input type="text" name="linea_credito_cantidad" id="linea_credito_cantidad"  value="<?php echo $total_a_pactar_cuotas;?>" onchange="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'), 'LINEA_CREDITO');return false;" readonly="readonly"/></td>
          </tr>
          <tr>
            <td>Numero de Cuotas</td>
            <td colspan="3"><select name="linea_credito_cantidad_cuotas" id="linea_credito_cantidad_cuotas"  onchange="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'), 'LINEA_CREDITO');return false;">
              <?php
	  for($c=1;$c<=$max_num_cuotas;$c++)
	  {echo'<option value="'.$c.'">'.$c.'</option>';}
	  ?>
            </select></td>
          </tr>
          <tr>
            <td>Mes Inicio</td>
            <td width="117"><select name="linea_credito_mes_ini" id="linea_credito_mes_ini" onchange="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'), 'LINEA_CREDITO');return false;">
              <?php
	  		foreach($array_meses as $n => $valor)
			{
			 
				if($n+1==date("m"))
				{echo'<option value="'.($n + 1).'" selected="selected">'.$valor.'</option>';}
				else
				{echo'<option value="'.($n + 1).'">'.$valor.'</option>';}	
			}	
	   ?>
                        </select></td>
            <td width="118">Meses Avance</td>
            <td width="97"><select name="meses_avance" id="meses_avance" onchange="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'), 'LINEA_CREDITO');return false;"> 
              <?php
              for($ma=1;$ma<=$max_avance_mes;$ma++)
			  {
			  	if($linea_credito_meses_avance==$ma)
				{echo'<option value="'.$ma.'" selected="selected">'.$ma.'</option>';}
				else
				{echo'<option value="'.$ma.'">'.$ma.'</option>';}
			  }
			  ?>
            </select>            </td>
          </tr>
          <tr>
            <td>Dia Vencimiento</td>
            <td colspan="3"><select name="linea_credito_dia_vencimiento" id="linea_credito_dia_vencimiento" onchange="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'), 'LINEA_CREDITO');return false;">
              <?php
			
			  
	  foreach($array_dias_disponibles as $n => $valor)
	  {
	  	echo'<option value="'.$valor.'">'.$valor.'</option>';
	  }
	  ?>
            </select></td>
          </tr>
          <tr>
            <td>Año</td>
            <td colspan="3">
            <select name="linea_credito_year" id="linea_credito_year" onchange="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'), 'LINEA_CREDITO');return false;">
            <?php
				$año_actual=date("Y");
				$año_ini=$año_actual-10;
				$año_fin=$año_actual+1;
            	for($a=$año_ini;$a<=$año_fin;$a++)
				{
						if($a==$año_actual)
						{echo'<option value="'.$a.'" selected="selected">'.$a.'</option>';}
						else
						{echo'<option value="'.$a.'" >'.$a.'</option>';}
						
				}
			?>
            </select>            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td colspan="3">&nbsp;</td>
          </tr>
          </tbody>
        </table>
    <div id="resultado_linea_credito">
          <div align="center"><br />
          <a href="#" class="button_R" onclick="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'), 'LINEA_CREDITO');return false;">Actualizar</a>
          </div>
    </div>
</form>
<?php }?>
</div>
<div id="botonera">
<?php
if($mostrar_boton_final)
{ echo $boton_final;}
?>
</div>
</body>
</html>