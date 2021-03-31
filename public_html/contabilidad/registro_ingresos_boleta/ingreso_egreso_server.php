<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_otros_pagos_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
/////////////////////--/XAJAX/----////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("ingreso_egreso_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"CARGA_MOVIMIENTO");
$xajax->register(XAJAX_FUNCTION,"CARGA_X_CONCEPTO");
$xajax->register(XAJAX_FUNCTION,"CARGA_GLOSA");

///////////////----------------////////////////////////////
function CARGA_MOVIMIENTO($fecha, $sede)
{
	$objResponse = new xajaxResponse();
 	if(($fecha!="")and($sede!=""))
	{
		$filtrar=false;
		$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
		include("../../../funciones/conexion_v2.php");
		$cons="SELECT * FROM pagos WHERE id_alumno='$id_alumno' AND fechapago='$fecha' AND sede='$sede' ORDER by idpago";
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
			$numeroletra=$D["aux_num_documento"];
			if($numeroletra=="")
			{$numeroletra="---";}
			
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
					case"otro_ingreso_2":
						$filtrar=true;
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
				$tabla='<tr><td colspan="6" align="center">No hay Movimientos el '.$fecha.' en '.$sede.' para este Alumno</td></tr>';
			}
		$tabla.='<tr><td colspan="6">&nbsp;</td></tr></table>';
		$objResponse->Assign("registros_anteriores","innerHTML",$tabla);
		
		mysql_free_result($sql);
		@mysql_close($conexion);
	}
	return $objResponse;
}
//////////////////
function CARGA_X_CONCEPTO($documento_asociado)
{
	$documento_asociado=strtolower($documento_asociado);
	$nombre_select="por_conceptoX";
	require("../../../funciones/funciones_sistema.php");
	$objResponse = new xajaxResponse();
	$div="por_concepto";
	
	$campo_conceptos='';
	$glosa="";
	switch($documento_asociado)
	{
		case"letras":
			$campo_conceptos='<select name="'.$nombre_select.'" id="="'.$nombre_select.'" onchange="xajax_CARGA_GLOSA(this.value);"><option value="letras">Pago de letras</option></select>';
			break;
		case"boleta":	
			$campo_conceptos=CAMPO_SELECCION($nombre_select,"conceptos_financieros","",false,'onchange="xajax_CARGA_GLOSA(this.value);"');
			break;
			
	}
	$objResponse->Assign($div,"innerHTML",$campo_conceptos);
	return $objResponse;
}
function CARGA_GLOSA($por_conceptoX)
{
	require("../../../funciones/funciones_sistema.php");
	$objResponse = new xajaxResponse();
	$div="div_glosa";
	
	$glosa="";
	switch($por_conceptoX)
	{
		case"letras":
			$glosa="Pago de Letras N.";
			break;
		case"derecho a examen":	
			$glosa="Derecho a Examen y Titulo";
			break;
		case"programas_estudio":
			$glosa="Pago de Programa(s) de Estudio";
			break;
		case"convalidacion":
			$glosa="Convalidacion de () Asignatura(s)";
			break;
	}
	$objResponse->Assign("fglosa","value",$glosa);
	return $objResponse;
}
////////////////
$xajax->processRequest();
?>