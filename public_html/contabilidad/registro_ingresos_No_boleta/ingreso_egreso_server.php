<?php
session_start();
/////////////////////--/XAJAX/----////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
@$xajax = new xajax("ingreso_egreso_server.php");
$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"CARGA_CONTENIDO");
$xajax->register(XAJAX_FUNCTION,"CARGA_MOVIMIENTO");
$xajax->register(XAJAX_FUNCTION,"CARGA_X_CONCEPTO");
///////////////----------------////////////////////////////

function CARGA_CONTENIDO($tipo, $div)
{
	require("../../../funciones/conexion_v2.php");
	$objResponse = new xajaxResponse();
	
	//////////////
	$seccion="finanzas";
	$nombre_select="ftipo_doc";
	$select='<select name="'.$nombre_select.'" id="'.$nombre_select.'" onchange="xajax_CARGA_X_CONCEPTO(this.value, document.getElementById(\'ftipo_mov\').value); return false">
     	<option value="SS">Seleccione</option>';
	$cons="SELECT contenido FROM parametros WHERE seccion='$seccion' AND tipo='$tipo'";
	//echo"---> $cons<br>";
	$sql=$conexion_mysqli->query($cons);
	$num_reg=$sql->num_rows;
	//echo"$num_reg<br>";
	if($num_reg>0)
	{
		while($A=$sql->fetch_assoc())
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
	
	$sql->free();
	$conexion_mysqli->close();
	$objResponse->Assign($div,"innerHTML",$select);
	$objResponse->Assign('por_concepto',"innerHTML",'Seleccione');//borro el 3 selecc para obligar a seleccion de este
	///////////////////
	return $objResponse;
}
///
function CARGA_MOVIMIENTO($fecha, $sede)
{
	require("../../../funciones/conexion_v2.php");
	$objResponse = new xajaxResponse();
	$SUMA_TOTAL=0;
 	if(($fecha!="")and($sede!=""))
	{
		$filtrar=false;
		$por_concepto='otro_ingreso';
		$por_concepto_2="otro_egreso";
		
		require("../../../funciones/conexion_v2.php");
		include("../../../funciones/funcion.php");
		$cons="SELECT * FROM pagos WHERE fechapago='$fecha' AND sede='$sede' AND por_concepto IN('$por_concepto', '$por_concepto_2') ORDER by idpago";
		$color3 = "#E0FAC5";
		$sql=$conexion_mysqli->query($cons);
		$numReg=$sql->num_rows;
		$tabla='<table border="0" width="100%">
		<tr>
		<thead>
		<th><strong>ID Pago</strong></th>
		<th><strong>Valor</strong></th>
		<th><strong>Tipo Documento</strong></th>
		<th><strong>Glosa</strong></th>
		<th><strong>N&ordm; Doc.</strong></th>
		<th><strong>Movimiento</strong></th>
		<th><strong>Forma Pago</strong></th>
		</thead>
		<tbody>
		</tr>';
		if($numReg>0)
		{
			$i=0;
			$SUMA_TOTAL=0;
		while($D=$sql->fetch_assoc())
		{
			$idpago=$D["idpago"];
			@$numeroletra=$D["numletra"];
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
			
			$SUMA_TOTAL+=$valor;
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
							$sql_bo=$conexion_mysqli->query($cons_bo)or die("Boleta");
							$D_bo=$sql_bo->fetch_assoc();
							
							$folio_boleta=$D_bo["folio"];
							$sql_bo->free();
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
					$sql_bo=$conexion_mysqli->query($cons_bo)or die("Boleta");
					$D_bo=$sql_bo->fetch_assoc();
					$folio_boleta=$D_bo["folio"];
					$sql_bo->free();
					$aux_numero_documento=$folio_boleta;
				}	
				/////////////----------------------------------------------------------------///////////////
				switch($tipodoc)
				{
					case"L":
						$tipodoc="Letra/Cuota";
						break;
				}

					
			  $tabla.="<tr>";
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
				$tabla='<table width="100%"><thead><tr><th colspan="6" align="center">No hay Movimientos el '.fecha_format($fecha).' en '.$sede.'</th></tr></thead><tbody>';
			}
		$tabla.='</table>';
		
		$objResponse->Assign("registros_anteriores","innerHTML",$tabla);
		
		$sql->free();
		$conexion_mysqli->close();
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
	require("../../../funciones/conexion_v2.php");
	$cons_1c="SELECT * FROM parametros_2 WHERE mostrar='ON' $condicionx2 ORDER by contenido";
	
	$sql_1c=$conexion_mysqli->query($cons_1c)or die("CONCEPTO");
	$select='<select name="'.$nombre_select.'" id="'.$nombre_select.'">';
	while($C=$sql_1c->fetch_assoc())
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
	$sql_1c->free();
	$conexion_mysqli->close();
	return $objResponse;
}
////////////////
$xajax->processRequest();
?>