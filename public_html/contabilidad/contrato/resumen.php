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

 ////////////////////////////////////////////////////////
require("../../../funciones/conexion_v2.php");
include("../../../funciones/funcion.php");
 $sede_alumno=$_SESSION["FINANZAS"]["sede_alumno"];
 $rut_alumno=$_SESSION["FINANZAS"]["rut_alumno"];
 $carrera=$_SESSION["FINANZAS"]["carrera_alumno"];
 $semestre=$_SESSION["FINANZAS"]["semestre"];
// $año_contrato=end(explode("-",$_SESSION["FINANZAS"]["fecha_inicio"]));
 $año_contrato=$_SESSION["FINANZAS"]["year_estudio"];
 $id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];

 //------------------------------------------------------


 $cons="SELECT COUNT(id) FROM contratos2 WHERE id_alumno='$id_alumno' AND sede='$sede_alumno' AND ano='$año_contrato' AND semestre='$semestre' AND condicion='ok'";
 if(DEBUG){echo "$cons<br>";}
 $sqlC=mysql_query($cons)or die(mysql_error());
 $D=mysql_fetch_row($sqlC);
 $n_regs_contrato=$D[0];
 if($n_regs_contrato>0)
 {
 	$msj_error="El Alumno ya tiene un contrato generado en este semestre, año, sede y carrera";
	$repetido_contrato=true;
 }
 else
 {
 	//echo"sin contratos generados";
	$repetido_contrato=false;
	$msj_error="";
 }
 mysql_free_result($sqlC);
@mysql_close($conexion);
$conexion_mysqli->close();
 ////////////////////////////////////////////////////////////
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Contrato - Resumen</title>

<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">

<?php
	switch($_SESSION["FINANZAS"]["opcion_matricula"])
	{
		case"CHEQUE":
			$msj_pago_mat="EL ALUMNO PAGARA MATRICULA con Cheque...";
			break;
		case"NO":
			$msj_pago_mat="EL ALUMNO NO PAGARA MATRICULA...";
			break;
		case"CONTADO":
			$msj_pago_mat="EL ALUMNO PAGARA MATRICULA ("."$".number_format($_SESSION["FINANZAS"]["matricula"],0,",",".").") AL CONTADO (GENERA BOLETA)";	
			break;
		case"L_CREDITO":
			$msj_pago_mat="EL ALUMNO PAGARA MATRICULA CON UNA CUOTA PARA EL ".fecha_format($_SESSION["FINANZAS"]["fecha_vence_lcredito_mat"])."(NO GENERA BOLETA)";	
			break;
		case"EXCEDENTE":
			 if(isset($_SESSION["FINANZAS"]["EX_nuevo_excedente"]))
			  {
				  if($_SESSION["FINANZAS"]["excedente"]>=$_SESSION["FINANZAS"]["matricula"])
				  { 
				  		$aux_valor='$ 0';
						$msj_pago_mat="EL ALUMNO UTILIZA EXCEDENTE PARA CUBRIR COMPLETAMENTE LA MATRICULA...(NO GENERA BOLETA)";
				  }
				  else
				  { 
				  	$aux_valor="$".number_format(($_SESSION["FINANZAS"]["matricula"]-$_SESSION["FINANZAS"]["excedente"]),0,",",".");
					$msj_pago_mat="EL ALUMNO UTILIZA EXCEDENTE PARA CUBRIR PARCIALMENTE LA MATRICULA DEBE PAGAR $aux_valor...(GENERA BOLETA)";
					}
			  }
			break;
	}
?>

<script language="javascript" type="text/javascript">
function CONFIRMAR()
{
	<?php if($repetido_contrato){?>
	c=confirm('Esta a punto de Terminar el Proceso de Matricula\n Pero el Alumno ya Presenta un Contrato Registrado en este Periodo \n No se Puede Continuar, verifique y vuelva a intentarlo');
	<?php }else{?>
	mensaje='Esta a punto de Terminar el Proceso de Matricula\n ¿Esta seguro(a) que desea continuar?\n <?php echo"$msj_pago_mat ";?>';
	c=confirm(mensaje);
	if(c==true)
	{
		//document.frm.submit();
		window.location="REC_1.php";
	}
	<?php }?>
}
</script>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 43px;
}
#Layer1 #msj_error {
	text-decoration: blink;
	background-color: #FFFF00;
}
.Estilo1 {color: #FF0000}
.Estilo2 {color: #006600}
-->
</style>
</head>

<body>
<h1 id="banner">Resum&eacute;n - Confirmaci&oacute;n </h1>
<div id="Layer1">
  <table width="100%" border="0" align="center">
    <tr>
      <td colspan="7" bgcolor="#e5e5e5"><strong>General</strong></td>
    </tr>
    <tr>
      <td width="14%">Rut</td>
      <td colspan="3"><em><?php echo $_SESSION["FINANZAS"]["rut_alumno"];?></em></td>
      <td colspan="2">Inicio contrato </td>
      <td width="33%"><em><?php echo $_SESSION["FINANZAS"]["fecha_inicio"];?></em></td>
    </tr>
    <tr>
      <td>Alumno </td>
      <td colspan="3"><em><?php echo $_SESSION["SELECTOR_ALUMNO"]["nombre"]." ".$_SESSION["SELECTOR_ALUMNO"]["apellido_P"];?></em></td>
      <td colspan="2">Termino Contrato </td>
      <td><em><?php echo $_SESSION["FINANZAS"]["fecha_fin"];?></em></td>
    </tr>
    <tr>
      <td>Carrera</td>
      <td colspan="3"><em><?php echo $_SESSION["FINANZAS"]["carrera_alumno"];?></em></td>
      <td colspan="2">Vigencia Cuotas</td>
      <td><em><?php echo $_SESSION["FINANZAS"]["vigencia_cuotas"];?></em></td>
    </tr>
    <tr>
      <td>Sede</td>
      <td colspan="3"><em><?php echo $_SESSION["FINANZAS"]["lugar_contrato"];?></em></td>
      <td colspan="2">A&ntilde;o </td>
      <td><em><?php echo $_SESSION["FINANZAS"]["year_estudio"];?></em></td>
    </tr>
    <tr>
      <td>Apoderado</td>
      <td colspan="3"><em><?php echo $_SESSION["FINANZAS"]["nombreC_apo"];?></em></td>
      <td colspan="2">Semestre</td>
      <td><em><?php echo $_SESSION["FINANZAS"]["semestre"];?></em></td>
    </tr>
    <tr>
      <td>Nivel</td>
      <td width="14%"><em><?php echo $_SESSION["FINANZAS"]["nivel"];?></em></td>
      <td width="9%">Grupo</td>
      <td width="15%"><em><?php echo $_SESSION["FINANZAS"]["grupo"];?></em></td>
      <td colspan="2">Arancel Carrera</td>
      <td><em>$
	  <?php 
	  if($_SESSION["FINANZAS"]["vigencia_cuotas"]=="semestral")
	  	{echo number_format($_SESSION["FINANZAS"]["arancel"],0,",",".");}
		else
		{ echo number_format($_SESSION["FINANZAS"]["arancel_anual"],0,",",".");}
	  ?></em></td>
    </tr>
    <tr>
      <td colspan="7" bgcolor="#f5f5f5"><strong>Matricula</strong></td>
    </tr>
    <tr>
      <td>Matricula Carrera </td>
      <td colspan="3"><em>$<?php echo number_format($_SESSION["FINANZAS"]["matricula"],0,",",".");?></em></td>
      <td colspan="2">Paga Matricula </td>
      <td>
	  <?php
	  	if($_SESSION["FINANZAS"]["opcion_matricula"]!="NO")
		{
			echo"SI";
		}
		else
		{
			echo"NO";
		}
	  ?>
&nbsp;	  </td>
    </tr>
    <tr>
      <td>Como paga </td>
      <td colspan="3"><?php
	  	if($_SESSION["FINANZAS"]["opcion_matricula"]!="NO")
		{
			$opcion_matricula_label=$_SESSION["FINANZAS"]["opcion_matricula"];
			switch($opcion_matricula_label)
			{
				case"L_CREDITO":
					$opcion_matricula_label="Linea de Credito";
					break;
			}
				echo $opcion_matricula_label;
		}
		else
		{
			echo"-------";
		}
	  ?></td>
      <td colspan="2" valign="top">Total a pagar Mat.</td>
      <td valign="top">
      <?php
      if(isset($_SESSION["FINANZAS"]["EX_nuevo_excedente"]))
	  {
		  if($_SESSION["FINANZAS"]["excedente"]>=$_SESSION["FINANZAS"]["matricula"])
		  { echo '$ 0';}
		  else
		  { echo  "$".number_format(($_SESSION["FINANZAS"]["matricula"]-$_SESSION["FINANZAS"]["excedente"]),0,",",".");}
	  }
	  else
	  {
		  echo "$".number_format($_SESSION["FINANZAS"]["matricula"],0,",",".");
	  }
	  ?>
      </td>
    </tr>
    <tr>
      <td>Fecha Vencimiento </td>
      <td colspan="3"><em>
        <?php
	  	if($_SESSION["FINANZAS"]["opcion_matricula"]=="L_CREDITO")
		{
			echo fecha_format($_SESSION["FINANZAS"]["fecha_vence_lcredito_mat"]);
		}
		elseif($_SESSION["FINANZAS"]["opcion_matricula"]=="CHEQUE")
		{
			echo $_SESSION["FINANZAS"]["fecha_vence_cheque_mat"];
		}
		else
		{
			echo"-------";
		}
		
	  ?>
      </em></td>
      <td colspan="3" valign="top"><em>
        <?php
		if(isset($_SESSION["FINANZAS"]["comentario_beca_v2"]))
		{ $comentario_beca_v2=$_SESSION["FINANZAS"]["comentario_beca_v2"];}
		else
		{ $comentario_beca_v2="";}
		
	  if(isset($_SESSION["FINANZAS"]["comentario_beca"]))
	  {$aux_comentario_beca=$_SESSION["FINANZAS"]["comentario_beca"];}
	  else{ $aux_comentario_beca="";}
	  
	  echo "Comentario.: ".$comentario_beca_v2." [".$aux_comentario_beca."]";
	   ?>
      </em></td>
    </tr>
    <tr>
      <td colspan="7" bgcolor="#e5e5e5"><strong>Beca y Desc.</strong></td>
    </tr>
    <tr>
      <td>Otros Desc.</td>
      <td colspan="3"><?php 
	  	if(isset($_SESSION["FINANZAS"]["cantidad_beca"]))
		{
	  		echo "$".number_format($_SESSION["FINANZAS"]["cantidad_beca"],0,",",".");
		}
		else
		{
			echo" 0";
		}	
	  ?></td>
      <td colspan="2">Saldo a Favor</td>
      <td>
	  <?php 
	  echo "$".number_format($_SESSION["FINANZAS"]["excedente"],0,",",".")." contrato COD:(".$_SESSION["FINANZAS"]["id_contrato_anterior"].")";
	  if(isset($_SESSION["FINANZAS"]["EX_nuevo_excedente"]))
	  {
		  echo"(menos matricula)=$".number_format($_SESSION["FINANZAS"]["EX_nuevo_excedente"],0,",",".");
	  }
	  ?>
      
      </td>
    </tr>
    <tr>
      <td>% Desc. Beca</td>
      <td colspan="3">
	  <?php
	  if(isset($_SESSION["FINANZAS"]["porcentaje_beca"]))
	  {
		  if($_SESSION["FINANZAS"]["porcentaje_beca"]>0)
		  { echo $_SESSION["FINANZAS"]["porcentaje_beca"];}
		  else
		  { echo"---";}
	  }
	  else
		  { echo"---";}
      ?>
      </td>
      <td colspan="2">Aporte Beca Milenio</td>
      <td>$
      <?php
	  if(isset($_SESSION["FINANZAS"]["aporte_beca_nuevo_milenio"]))
	  { $aux_porcentaje_beca=$_SESSION["FINANZAS"]["aporte_beca_nuevo_milenio"];}
	  else
	  { $aux_porcentaje_beca=0;}
	  	echo number_format($aux_porcentaje_beca,0,",",".");
	  ?></td>
    </tr>
    <tr>
      <td height="15">Beca Nuevo Milenio</td>
      <td colspan="3">
	  <?php 
	  	if(isset($_SESSION["FINANZAS"]["beca_nuevo_milenio"]))
	  	{echo $_SESSION["FINANZAS"]["beca_nuevo_milenio"]; }
		else{ echo"";}
	 ?>
      </td>
      <td colspan="2"><strong>Total a pagar </strong></td>
      <td><strong>$
      <?php
	  	echo number_format($_SESSION["FINANZAS"]["total_a_pagar_arancel"],0,",",".");
	  ?>
      </strong></td>
    </tr>
    <tr>
      <td height="15">Beca Excelencia</td>
      <td colspan="3">$
	  <?php 
	  	if(isset($_SESSION["FINANZAS"]["aporte_beca_excelencia_academica"]))
		{ echo number_format($_SESSION["FINANZAS"]["aporte_beca_excelencia_academica"],0,",","."); }
		else{ echo"0";}
	  ?></td>
      <td colspan="2"><strong>Arancel</strong></td>
      <td>$
      <?php
      if( $_SESSION["FINANZAS"]["vigencia_cuotas"]=="semestral")
	  { echo number_format($_SESSION["FINANZAS"]["arancel"],0,",",".");;}
	  else
	  {  echo number_format($_SESSION["FINANZAS"]["arancel_anual"],0,",",".");;}
	  ?>
      </td>
    </tr>
    <tr>
      <td colspan="7" bgcolor="#f5f5f5"><strong>Forma de Pago</strong></td>
    </tr>
	<?php
	$contador=0;
	$cabecera=true;
	foreach($_SESSION["FINANZAS"]["METODO_PAGO"] as $n => $valor)
	{
		$metodo_pago=$n;
		$cantidad=$valor["cantidad"];
		
		switch($metodo_pago)
		{
			case"LINEA_CREDITO":
				$estilo="Estilo2";
				$cantidad_cuotas=$valor["cantidad_cuotas"];
				$valor_cuota=round($cantidad/$cantidad_cuotas);
				$dia_vence=$valor["dia_vence_cuota"];
				$mes=$valor["mes_ini_cuota"];
				$año=$valor["year"];
				$valor_cuota=number_format($valor_cuota,0,",",".");
				$cantidad_label=number_format($cantidad,0,",",".");
				$meses_avance=$valor["meses_avance"];
				//var_export($valor);
				//////////
				if(($dia_vence>28)and($mes==2))
					{
						$vencimiento="28/02/$año";
					}
					else
					{
						if($mes<10)
						{$mes_label="0".$mes;}
						else{$mes_label=$mes;}
						if($dia_vence<10)
						{$dia_vence_label="0".$dia_vence;}
						else{$dia_vence_label=$dia_vence;}
						$vencimiento="$dia_vence_label/$mes_label/$año";	
					}	
				//////////
				if(empty($cantidad))
				{
					$estilo="Estilo1";
					$vencimiento="---";
					$valor_cuota="---";
					$cantidad_cuotas="---";
				}
				
				echo'<tr>
					<td colspan="7"><em> -&gt;<span class="'.$estilo.'"> LINEA CREDITO</span></em></td>
					<tr>
					<tr>
						<td bgcolor="#f7f7f7"><div align="center"><em>N. Cuotas</em></div></td>
						<td colspan="2" bgcolor="#f7f7f7"><div align="center"><em>Valor Cuota</em></div></td>
						<td colspan="2" bgcolor="#f7f7f7"><div align="center"><em>Primer Vencimiento</em></div></td>
						<td colspan="2" bgcolor="#f7f7f7"><div align="center"><em>Total</em></div></td>
	</tr>
					<tr>
						<td bgcolor="#f7f7f7"><div align="center"><em>'.$cantidad_cuotas.'</em></div></td>
						<td colspan="2" bgcolor="#f7f7f7"><div align="center"><em>'.$valor_cuota.'</em></div></td>
						<td colspan="2" bgcolor="#f7f7f7"><div align="center"><em>'.$vencimiento.'</em></div></td>
						<td colspan="2" bgcolor="#f7f7f7"><div align="center"><em>'.$cantidad_label.'</em></div></td>
	</tr>';
				break;
			case"CONTADO":
				$estilo="Estilo2";
				//echo"<br>";
				//var_export($valor);
				$cantidad_label=number_format($cantidad,0,",",".");
				$contado_descuento=$valor["descuento"];
				$AUX=($cantidad*$contado_descuento)/100;
				$total=($cantidad-$AUX);
				$total=number_format($total,0,",",".");
				if(empty($cantidad))
				{
					$estilo="Estilo1";
					$contado_descuento="---";
					$cantidad_label="---";
				}
				echo'<tr>
					<td colspan="7" ><em> -&gt; <span class="'.$estilo.'">CONTADO</span></em></td>
					</tr>
					<tr>
						<td colspan="3" bgcolor="#f7f7f7"><div align="center"><em>Cantidad</em></div></td>
						<td colspan="2" bgcolor="#f7f7f7"><div align="center"><em>% Descuento</em></div></td>
						<td colspan="2" bgcolor="#f7f7f7"><div align="center"><em>Total</em></div></td>
	</tr>
					<tr>
						<td colspan="3" bgcolor="#f7f7f7"><div align="center"><em>'.$cantidad_label.'</em></div></td>
						<td colspan="2" bgcolor="#f7f7f7"><div align="center"><em>'.$contado_descuento.'</em></div></td>
						<td colspan="2" bgcolor="#f7f7f7"><div align="center"><em>'.$total.'</em></div></td>
	</tr>';
				break;
			case"CHEQUE":
				$estilo="Estilo2";
				//var_export($valor);
				$cantidad_label=number_format($cantidad,0,",",".");
				$n_cheque=$valor["numero"];
				$banco=$valor["banco"];
				$fecha_vencimiento=$valor["fecha_vencimiento"];
				$matricula_arancel=$valor["matricula_arancel"];
				if(empty($cantidad))
				{
					$estilo="Estilo1";
					$n_cheque="---";
					$banco="---";
					$fecha_vencimiento="---";
					
				}
				if($matricula_arancel=="ON")
				{
					$n_cheque=$_SESSION["FINANZAS"]["num_cheque_mat"];
					$banco=$_SESSION["FINANZAS"]["banco_cheque_mat"];
					$fecha_vencimiento=$_SESSION["FINANZAS"]["fecha_vence_cheque_mat"];
					$msj="(utilizando el mismo cheque de Matricula)";
				}
				else
				{
					$msj="";
				}
				echo'<tr>
					<td colspan="7" ><em> -&gt; <span class="'.$estilo.'">CHEQUE '.$msj.'</span></em></td>
					<tr>
						<td bgcolor="#f7f7f7"><div align="center"><em>N. Cheque</em></div></td>
						<td colspan="2" bgcolor="#f7f7f7"><div align="center"><em>Banco</em></div></td>
						<td colspan="2" bgcolor="#f7f7f7"><div align="center"><em>Fecha Vencimiento</em></div></td>
						<td colspan="2" bgcolor="#f7f7f7"><div align="center"><em>Total</em></div></td>
	</tr>
					<tr>
						<td bgcolor="#f7f7f7"><div align="center"><em>'.$n_cheque.'</em></div></td>
						<td colspan="2" bgcolor="#f7f7f7"><div align="center"><em>'.$banco.'</em></div></td>
						<td colspan="2" bgcolor="#f7f7f7"><div align="center"><em>'.$fecha_vencimiento.'</em></div></td>
						<td colspan="2" bgcolor="#f7f7f7"><div align="center"><em>'.$cantidad_label.'</em></div></td>
	</tr>';
				break;			
		}
		
	}
	////para excedente
	$valor_excedente_proximo_contrato=$_SESSION["FINANZAS"]["excedente_proximo_contrato"];
	if($valor_excedente_proximo_contrato>0){$estilo="Estilo2";}
	else{ $estilo="Estilo1";}
	echo'<tr>
					<td colspan="7" ><em> -&gt; <span class="'.$estilo.'">Excedentes</span></em></td>
					<tr>
						<td bgcolor="#f7f7f7" colspan="2"><div align="center"><em>Excedente Proximo Contrato</em></div></td>
						<td bgcolor="#f7f7f7" colspan="3"><div align="center">&nbsp;</div></td>
						<td colspan="2" bgcolor="#f7f7f7"><div align="center">Total</div></td>
					</tr>
					<tr>
						<td bgcolor="#f7f7f7" colspan="2"><div align="center">&nbsp;</div></td>
						<td bgcolor="#f7f7f7" colspan="3"><div align="center">&nbsp;</div></td>
						<td colspan="2" bgcolor="#f7f7f7"><div align="center">'.$valor_excedente_proximo_contrato.'</div></td>
					</tr>';
	
	?>
	<tr>
	  <td colspan="7" bgcolor="#e5e5e5"><strong>Terminar Proceso Matricula </strong></td>
    </tr>
	<tr>
	<td colspan="7"><div align="center">
	  <input type="button" name="Submit" value="Aceptar"  onclick="CONFIRMAR();"/>
	  </div></td>
	</tr>
	<tr>
	  <td colspan="7"><div align="center" id="msj_error"><?php echo $msj_error;?></div></td>
    </tr>
	<tr>
	  <td>Volver a </td>
      <td colspan="3"><a href="paso1.php" class="button">Paso 1 </a></td>
      <td colspan="2"><a href="paso2.php" class="button">Paso 2 </a></td>
      <td><a href="paso_3b.php" class="button">Paso 3 </a></td>
	</tr>
	<tr>
	  <td><span class="Estilo1">.</span></td>
	  <td colspan="4"><span class="Estilo2">.</span></td>
	  <td width="1%">&nbsp;</td>
	  <td>&nbsp;</td>
    </tr>
  </table>
</div>
</body>
</html>
