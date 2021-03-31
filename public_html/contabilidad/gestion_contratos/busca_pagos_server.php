<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Impresion_Y_Gestion_de_contratos_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("busca_pagos_server.php");
$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCA_CUOTAS");
$xajax->register(XAJAX_FUNCTION,"OCULTAR_CUOTAS");
$xajax->register(XAJAX_FUNCTION,"ELIMINAR");
$xajax->register(XAJAX_FUNCTION,"ELIMINAR_CONTRATO");
define("DEBUG",false);
////////////////////////////////////////////

function BUSCA_CUOTAS($semestre, $ano, $aux, $forma_pago_matricula, $id_contrato)
{
	$sin_pagos=false;
	$sin_cuotas=false;
	$div="div_pagos_$aux";
	$html='<form name="frm_'.$aux.'" id="frm_'.$aux.'">
	<table width="80%" id="cuotas_lista" border="0" align="right">
			<thead>
			<tr>
				<td colspan="6"><strong>Cuotas Encontradas</strong></td>
			</tr>
			<tr bgcolor="#f5f5f5">
				<td>+</td>
				<td>ID Cuotas</td>
				<td>Valor</td>
				<td>Deuda</td>
				<td>Condicion</td>
				<td>Tipo</td>
				<td>Info pago</td>
			</tr>
			</thead>
			<tbody>';
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$objResponse = new xajaxResponse();
	include("../../../funciones/conexion.php");
	$cons="SELECT * FROM letras WHERE idalumn='$id_alumno' AND  id_contrato='$id_contrato' AND semestre='$semestre' AND ano='$ano' ORDER by id";
	$sql=mysql_query($cons)or die("pagos ".mysql_error());
	if(DEBUG){$html.=$cons."<br>";}
	$num_pagos=mysql_num_rows($sql);
	if($num_pagos>0)
	{
		while($L=mysql_fetch_assoc($sql))
		{
			$id_cuota=$L["id"];
			$valor=$L["valor"];
			$deudaXletra=$L["deudaXletra"];
			$pagada=$L["pagada"];
			$tipo=$L["tipo"];
			
			switch($pagada)
			{
				case"N":
					$condicion="Pendiente";
					break;
				case"S":
					$condicion="Pagada";
					break;
				case"A":
					$condicion="Abonada";
					break;		
			}
			$cons_P="SELECT * FROM pagos WHERE id_alumno='$id_alumno' AND id_cuota='$id_cuota'";
			$sql_P=mysql_query($cons_P)or die("pagos ".mysql_error());
			$num_pagos=mysql_num_rows($sql_P);
			if($num_pagos)
			{
				$cuenta_pago=0;
				$acumula_pago=0;
				while($P=mysql_fetch_assoc($sql_P))
				{
					$cuenta_pago++;
					$id_pago=$P["id"];
					$valor=$P["valor"];
					$acumula_pago+=$valor;
					
				}
				$info_pago='<img src="../../BAses/Images/advertencia.png" />('.$cuenta_pago.' ->$'.$acumula_pago.')';
			}
			else
			{
				$info_pago='<img src="../../BAses/Images/ok.png" />(0)';
			}
			$html.='<tr>
					<td><input name="cuota[]" id="cuota[]" type="checkbox" value="'.$id_cuota.'"/></td>
					<td>'.$id_cuota.'</td>
					<td>'.number_format($valor,0,",",".").'</td>
					<td>'.number_format($deudaXletra,0,",",".").'</td>
					<td>'.$condicion.'</td>
					<td>'.$tipo.'</td>
					<td>'.$info_pago.'</td>
					</tr>';
		}
	}
	else
	{
		$html.="<tr><td>Sin Cuotas</td></tr>";
		$sin_cuotas=true;
	}
	
	$html.='</tbody></table>';
	//////////////////////////////////////////////////////////////////
	//inicion seccion pagos matricula si es al contado
	$html.='<table width="80%" align="right">
			<tr>
				<td colspan="4"><strong>Pagos Encontrados</strong></td>
			</tr>
			<tr bgcolor="#f5f5f5">
			<td>+</td>
			<td>ID</td>
			<td>Valor</td>
			<td>Fecha</td>
			<td>Glosa</td>
			</tr>';
	$cons_pm="SELECT * FROM pagos WHERE id_alumno='$id_alumno' AND semestre='$semestre' AND year='$ano' AND por_concepto IN('matricula', 'arancel')";
	$sql_pm=mysql_query($cons_pm)or die("pago contado ".mysql_error());
	$num_pm=mysql_num_rows($sql_pm);
	if($num_pm>0)
	{
		while($PM=mysql_fetch_assoc($sql_pm))
		{
			$id_pago=$PM["idpago"];
			$valor=$PM["valor"];
			$fecha_pago=$PM["fechapago"];
			$glosa=$PM["glosa"];
			$por_concepto=$PM["por_concepto"];
			
			$html.='<tr>
					<td><input name="pago[]" id="pago[]" type="checkbox" value="'.$id_pago.'"/></td>
					<td>'.$id_pago.'</td>
					<td>'.number_format($valor,0,",",".").'</td>
					<td>'.$fecha_pago.'</td>
					<td>'.$glosa.'</td>
					</tr>';
		}
	}
	else
	{
		$sin_pagos=true;
		$html.='<tr><td colspan="5">No se encontraron pagos asociados ...</td></tr>';
	}
	$html.='</table></form>';
	$mostrar_opcion_eliminar=true;
	
		$opcion_final='<a href="#" class="button_R" onclick="xajax_ELIMINAR(xajax.getFormValues(\'frm_'.$aux.'\'), \''.$aux.'\', \''.$id_contrato.'\');return false;"><strong>1._Borrar Cuotas y Pagos</strong></a><br><br>';
	
		$opcion_final.='<a href="#" class="button_R" onclick="xajax_ELIMINAR_CONTRATO( \''.$id_contrato.'\', \''.$aux.'\');return false;"><strong>2._Eliminar Contrato</strong></a>';
	
	$html.=$opcion_final;
	mysql_free_result($sql_pm);
	mysql_free_result($sql);
	mysql_close($conexion);
	$objResponse->Assign($div,"innerHTML",$html);
	return $objResponse;
}
function OCULTAR_CUOTAS($aux)
{
	$div="div_pagos_$aux";
	$objResponse = new xajaxResponse();
	$objResponse->Assign($div,"innerHTML","");
	return $objResponse;
}
function ELIMINAR_CONTRATO($id_contrato, $aux)
{
	include("../../../funciones/conexion.php");
	include("../../../funciones/VX.php");
	$div="div_pagos_$aux";
	$objResponse = new xajaxResponse();
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$n_condicion_contrato="retiro";
	$cons_D="UPDATE contratos2 SET condicion='$n_condicion_contrato' WHERE id='$id_contrato' AND id_alumno='$id_alumno'";
	mysql_query($cons_D)or die(" actualiza  Contrato".mysql_error());
	
	///cambio de situacion a alumno
	/*
	$cons_A="UPDATE alumno SET situacion='R' WHERE id='$id_alumno' LIMIT 1";
	mysql_query($cons_A)or die("Actualiza Alumno".mysql_error());
	
	///////////////////////
	////Registro Matricula Alumno
	$tipo_registro="Retiro";
	$descripcion="Cambio condicion Alumno a Retirado y Caduca Contrato($id_contrato)";
	REGISTRO_EVENTO_ALUMNO($id_alumno, $tipo_registro, $descripcion);
	////////////////////////////////////////////
	*/
	$evento="Contrato de Alumno($id_alumno) Eliminado";
	REGISTRA_EVENTO($evento);
	////////////////////////
	mysql_close($conexion);
	$objResponse->Assign($div,"innerHTML",'CONTRATO Eliminado... <a href="#" onclick="ACTUALIZAR();"><strong>Actualizar</strong></a>');
	if(DEBUG){$objResponse->Assign($div,"innerHTML","Eliminar Contrato $cons_D<br>");}
	$objResponse->Alert("Contrato Eliminado... \n Situacion de Alumno -> Retirado");
	return $objResponse;
}
function ELIMINAR($FORMULARIO, $aux, $id_contrato)
{
	include("../../../funciones/conexion.php");
	$div="div_elimina";
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$objResponse = new xajaxResponse();
	$objResponse->Alert("Eliminando Cuotas, Pagos y Anulando Boletas...");
	///////////////
	$cuotas=$FORMULARIO["cuota"];
	$pagos=$FORMULARIO["pago"];
	//
	if(DEBUG){$html="<b>Cuotas</b><br>";}
	$num_cuotas=count($cuotas);
	$num_pagos=count($pagos);
	$eliminar_pagos_asociados_cuota=false;
	
	if($num_cuotas>0)
	{
		foreach($cuotas as $n => $valor)
		{
			if(DEBUG){$html.="$n -> $valor<br>";}
			
			if($eliminar_pagos_asociados_cuota)
			{
				//busco datos de pago asociado a cuota
				$cons1="SELECT * FROM pagos WHERE id_cuota='$valor' AND id_alumno='$id_alumno'";
				if(DEBUG){$html.="-->$cons1<br>";}
				$sql_1=mysql_query($cons1)or die("cons_1 ".mysql_error());
				$num_pagos_asociados=mysql_num_rows($sql_1);
				$pagos_asociados=false;
				if(DEBUG){$html.="pagos asociados: $num_pagos_asociados<br>";}
				if($num_pagos_asociados>0)
				{
					$primera=true;
					$pagos_asociados=true;
					while($PA=mysql_fetch_assoc($sql_1))
					{
						$id_pago_A=$PA["idpago"];
						$id_boleta_A=$PA["id_boleta"];
						
						//concateno id de boleta para posterior consulta(anular)
						if($primera)
						{ 
							$concatena_boleta=$id_boleta_A;
							$primera=false;
						}
						else
						{
							$concatena_boleta.=", $id_boleta_A";
						}
						if(DEBUG){$html.="[$id_boleta_A]<br>";}
					}
				}
				if($pagos_asociados)//si tiene apagos asociados los borro y anulo las boletas asociadas a estos
				{
					$cons_borro_pagoA="DELETE FROM pagos WHERE id_cuota='$valor' AND id_alumno='$id_alumno'";
					$cons_anulo_boleta="UPDATE boleta SET estado='ANULADA' WHERE id_alumno='$id_alumno' AND id IN('$concatena_boleta')";
					mysql_query($cons_borro_pagoA)or die("exe1 ".mysql_error());
					mysql_query($cons_anulo_boleta)or die("exe2 ".mysql_error());
				}
			}		
			$cons_borro_cuota="DELETE FROM letras WHERE id='$valor' AND idalumn='$id_alumno'";
			mysql_query($cons_borro_cuota)or die("exe3 ".mysql_error());
			
			if(DEBUG){$html.="$cons_borro_pagoA<br><br>$cons_anulo_boleta<br><br>$cons_borro_cuota<br><br><br>";}
			
		}
	}//fin si 
	if($num_pagos>0)
	{
		if(DEBUG){$html.="<b>Pagos</b><br>";}
		foreach($pagos as $m => $valorx)
		{
			if(DEBUG){$html.="$m -> $valorx<br>";}
			$cons2="SELECT * FROM pagos WHERE idpago='$valorx' AND id_alumno='$id_alumno'";
			$sql_2=mysql_query($cons2)or die("cons2 ".mysql_error());
			$PD=mysql_fetch_assoc($sql_2);
			$id_boleta_X=$PD["id_boleta"];
			
			$cons_anula_boletaX="UPDATE boleta SET estado='ANULADA' WHERE id='$id_boleta_X' AND id_alumno='$id_alumno'";
			$cons_borro_pagoX="DELETE FROM pagos WHERE idpago='$valorx' AND id_alumno='$id_alumno'" ;
			
			mysql_query($cons_anula_boletaX)or die("exe4 ".mysql_error());
			mysql_query($cons_borro_pagoX)or die("exe5 ".mysql_error());
			if(DEBUG){$html.="$cons_anula_boletaX<br><br>$cons_borro_pagoX<br><br><br>";}
			
		}
	}//fin si
	$objResponse->Assign($div,"innerHTML",$html);
	$objResponse->Assign("div_pagos_$aux","innerHTML",'Cuotas y/o Pagos Eliminados<br><a href="#" onclick="xajax_ELIMINAR_CONTRATO(\''.$id_contrato.'\', \''.$aux.'\');return false;"><strong>Click Aqui Para Eliminar el Contrato</strong></a>');
	mysql_close($conexion);
	return $objResponse;
}
////////////////
$xajax->processRequest();
?>