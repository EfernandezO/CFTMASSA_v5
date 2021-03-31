<?php
session_start();
/////////////////////--/XAJAX/----////////////////
require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("ingreso_egreso_server.php");
$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"CARGA_CONTENIDO");
$xajax->register(XAJAX_FUNCTION,"CARGA_MOVIMIENTO");
$xajax->register(XAJAX_FUNCTION,"CARGA_X_CONCEPTO");
///////////////----------------////////////////////////////

function CARGA_CONTENIDO($tipo, $div)
{
	include("../../../funciones/conexion.php");
	$objResponse = new xajaxResponse();
	
	//////////////
	$seccion="finanzas";
	$nombre_select="ftipo_doc";
	$select='<select name="'.$nombre_select.'" id="'.$nombre_select.'" onchange="xajax_CARGA_X_CONCEPTO(this.value, document.getElementById(\'ftipo_mov\').value); return false">
     	<option value="SS">Seleccione</option>';
	$cons="SELECT contenido FROM parametros WHERE seccion='$seccion' AND tipo='$tipo'";
	//echo"---> $cons<br>";
	$sql=mysql_query($cons)or die(mysql_error());
	$num_reg=mysql_num_rows($sql);
	//echo"$num_reg<br>";
	if($num_reg>0)
	{
		while($A=mysql_fetch_assoc($sql))
		{
			$contenido=$A["contenido"];
			//echo"$contenido<br>";
			$select.='<option value="'.$contenido.'">'.$contenido.'</option>';
		}
	}
	else
	{
		$select.='<option>Sin Elementos</option>';
	}
	$select.='</select>';
	mysql_free_result($sql);
	mysql_close($conexion);
	$objResponse->Assign($div,"innerHTML",$select);
	$objResponse->Assign('por_concepto',"innerHTML",'Seleccione');//borro el 3 selecc para obligar a seleccion de este
	///////////////////
	return $objResponse;
}
///
function CARGA_MOVIMIENTO($fecha, $sede)
{
	$objResponse = new xajaxResponse();
 	if(($fecha!="")and($sede!=""))
	{
		$filtrar=false;
		include("../../../funciones/conexion.php");
		$cons="SELECT * FROM pagos WHERE fechapago='$fecha' AND sede='$sede' ORDER by idpago";
		$color3 = "#E0FAC5";
		$sql=mysql_query($cons);
		$numReg=mysql_num_rows($sql);
		$tabla='<table border="0" width="100%">
		<tr>
		<td><strong>ID Pago</strong></td>
		<td><strong>Valor</strong></td>
		<td><strong>Tipo Documento</strong></td>
		<td><strong>Glosa</strong></td>
		<td><strong>N&ordm; Doc.</strong></td>
		<td><strong>Movimiento</strong></td>
		<td><strong>Forma Pago</strong></td>
		</tr>';
		if($numReg)
		{
			$i=0;
		while($D=mysql_fetch_assoc($sql))
		{
			$idpago=$D["idpago"];
			$numeroletra=$D["numletra"];
			if($numeroletra=="")
			{
				$numeroletra="---";
			}
			$id_boleta=$D["id_boleta"];
			$valor=$D["valor"];
			$tipodoc=$D["tipodoc"];
			$glosa=$D["glosa"];
			$movimiento=$D["movimiento"];
			$forma_pago=$D["forma_pago"];
			$por_concepto=$D["por_concepto"];
			$aux_num_documento=$D["aux_num_documento"];
			switch($movimiento)
			{
				case"I":
					$movimiento="Ingreso";
					break;
				case"E":	
					$movimiento="Egreso";
					break;
			}
			///////////////--------------------------------------------------////////////////
			switch($por_concepto)
				{
					case"otro_ingreso":
						$filtrar=false;
						break;
					case"arancel":
						$filtrar=true;
						break;
					case"matricula":
						$filtrar=true;
						break;	
					case"otro_egreso":	
						$filtrar=false;
						break;
				}
				//echo"---------> $id_boleta <br>";
			//	echo"|$por_concepto| <br>";
				if((empty($id_boleta))or($id_boleta<=0))
				{
					
					if($filtrar)
					{
						//echo"extraer de glosa <br>";
						//echo"F-> $filtrar<br>";
					//	echo"$glosa   -- ";
						$busqueda=array("(",")");
					//	$patron='/\(+\d+\)/';
						$patron='/\((\d+?)\)/';
						$resultado=preg_match($patron,$glosa,$NUMEROS);
						//echo"$glosa<br>";
						//echo "__________>".$NUMEROS[$i]."<br>";
						$final_ID=str_replace($busqueda,"",$NUMEROS[$i]);
						if(is_numeric($final_ID))
						{
							$cons_bo="SELECT folio FROM boleta WHERE id='$final_ID'";
							$sql_bo=mysql_query($cons_bo)or die("boleta ".mysql_error());
							$D_bo=mysql_fetch_assoc($sql_bo);
							$folio_boleta=$D_bo["folio"];
							mysql_free_result($sql_bo);
							$aux_numero_documento=$folio_boleta;
						}
					//	echo"R-> $resultado $final<br>";
					}
					else
					{
						$aux_numero_documento=$aux_num_documento;
					}
				}
				else
				{
					$cons_bo="SELECT folio FROM boleta WHERE id='$id_boleta'";
							$sql_bo=mysql_query($cons_bo)or die("boleta ".mysql_error());
							$D_bo=mysql_fetch_assoc($sql_bo);
							$folio_boleta=$D_bo["folio"];
							mysql_free_result($sql_bo);
							$aux_numero_documento=$folio_boleta;
				}	
				/////////////----------------------------------------------------------------///////////////
				switch($tipodoc)
				{
					case"L":
						$tipodoc="Letra/Cuota";
						break;
				}

					
			  $tabla.="<tr align=\"center\" style=\"background-color:$color\" onMouseOver=\"this.style.backgroundColor='$color3'\" onMouseOut=\"this.style.backgroundColor='$color'\" >";
				$tabla.='
				<td align="center">'.$idpago.'</td>
				<td align="right">$ '.number_format($valor,0,",",".").'</td>
				<td align="center">'.$tipodoc.'</td>
				<td>'.$glosa.'</td>
				<td align="center">'.$aux_numero_documento.'</td>
				<td align="center">'.$movimiento.'</td>
				<td align="center">'.$forma_pago.'</td>
				</tr>';
				
			}
			}
			else
			{
				$tabla='<tr><td colspan="6" align="center">No hay Movimientos el '.$fecha.' en '.$sede.'</td></tr>';
			}
		$tabla.='<tr><td colspan="6">&nbsp;</td></tr></table>';
		$objResponse->Assign("registros_anteriores","innerHTML",$tabla);
		
		mysql_free_result($sql);
		mysql_close($conexion);
	}
	return $objResponse;
}
////////////////
function CARGA_X_CONCEPTO($documento_asociado, $tipo_mov)
{
	if($tipo_mov=="I")
	{ $condicionx2="AND tipo_seleccion='1'";}
	else
	{ $condicionx2="AND tipo_seleccion='2'";}

	$documento_asociado=strtolower($documento_asociado);
	$nombre_select="por_conceptoX";
	$div="por_concepto";
	$objResponse = new xajaxResponse();
	include("../../../funciones/conexion.php");
	$cons_1c="SELECT * FROM parametros_2 WHERE mostrar='ON' $condicionx2 ORDER by contenido";
	$sql_1c=mysql_query($cons_1c)or die(mysql_error());
	$select='<select name="'.$nombre_select.'" id="'.$nombre_select.'">';
	while($C=mysql_fetch_assoc($sql_1c))
	{
		$id_concepto=$C["id"];
		$asociado=strtolower($C["asociado"]);
		$contenido=$C["contenido"];
		$label=$C["label"];
		if($asociado==$documento_asociado)
		{
			$html_select.='<option value="'.$contenido.'" selected="selected">'.$label.'</option>';
		}
		else
		{
			$html_select.='<option value="'.$contenido.'">'.$label.'</option>';
		}	
	}
	$select.=$html_select.'</select>';
	$objResponse->Assign($div,"innerHTML",$select);
	mysql_free_result($sql_1c);
	mysql_close($conexion);
	return $objResponse;
}
////////////////
$xajax->processRequest();
?>