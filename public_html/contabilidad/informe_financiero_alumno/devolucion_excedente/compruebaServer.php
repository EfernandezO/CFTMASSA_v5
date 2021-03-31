<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Impresion_Y_Gestion_de_contratos_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
/////////////////////--/XAJAX/----////////////////
@require_once("../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("compruebaServer.php");
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"TIPO_DEVOLUCION");
define("DEBUG",false);
///////////////----------------////////////////////////////
function TIPO_DEVOLUCION($tipo_devolucion, $id_contrato)
{
	$id_alumno=$_SESSION["USUARIO"]["id"];
	require("../../../../funciones/conexion_v2.php");
	$objResponse = new xajaxResponse();
	$objResponse->Alert("tipo devolucion: $tipo_devolucion id_contrato:$id_contrato");
	
	$objResponse->Assign('valor',"value",0);
	$objResponse->Assign('glosa',"value",'');
	$objResponse->Assign('valorTotalDevolucion',"value",0);
	
	
	switch($tipo_devolucion){
		case"excedente":
			//busco 
				$cons="SELECT * FROM contratos2 WHERE id='$id_contrato' LIMIT 1";
				if(DEBUG){ echo"--->$cons<br>";}
				$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
				$DC=$sql->fetch_assoc();
					$C_excedente=$DC["excedente"];
					$C_condicion=strtolower($DC["condicion"]);
					if(empty($C_excedente)){ $C_excedente=0;}
				$sql->free();
				
				if(($C_excedente>0))
				{ $continuar=true;}
				
				if($continuar){
					$objResponse->Assign('valor',"value",$C_excedente);
					$objResponse->Assign('valorTotalDevolucion',"value",$C_excedente);
					$objResponse->Assign('glosa',"value",'devolucion de excedente');
					}
			break;
		case"matricula":
			$totalMatricula=0;
			//cantidad ya devuelta por matricula
			$cons="SELECT SUM(valor) FROM pagos WHERE por_concepto='devolucion_matricula' AND aux_num_documento='$id_contrato' AND tipodoc='contrato'";
			if(DEBUG){ echo"--->$cons<br>";}
			$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
			$DC=$sql->fetch_row();
				$matriculaDevuelta=$DC[0];
				if(empty($matriculaDevuelta)){ $matriculaDevuelta=0;}
			$sql->free();
			//busco lo pagado al contado
			$cons="SELECT valor FROM boleta RIGHT JOIN contratos2 ON boleta.id = contratos2.id_boleta_generada WHERE contratos2.id='$id_contrato'";
			if(DEBUG){ echo"--->$cons<br>";}
			$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
			$DC=$sql->fetch_assoc();
				$matriculaContado=$DC["valor"];
				if(empty($matriculaContado)){ $matriculaContado=0;}
			$sql->free();
			//busco lo pagado en cuotas
			$cons="SELECT SUM(valor - deudaXletra) FROM letras WHERE id_contrato='$id_contrato' AND tipo='matricula'";
			if(DEBUG){ echo"--->$cons<br>";}
			$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
			$DC=$sql->fetch_row();
				$matriculaCuota=$DC[0];
				if(empty($matriculaContado)){ $matriculaContado=0;}
			$sql->free();
			
			$totalMatricula=($matriculaContado+$matriculaCuota)-$matriculaDevuelta;
			if($totalMatricula>0){$continuar=true;}
			
			if($continuar){
				$objResponse->Assign('valor',"value",$totalMatricula);
				$objResponse->Assign('glosa',"value",'devolucion de matricula');
				$objResponse->Assign('valorTotalDevolucion',"value",$totalMatricula);
				}
			break;
		
		case"derecho_examen":
			$totalDerechoExamen=0;
			//cantidad ya devuelta por matricula
			$cons="SELECT SUM(valor) FROM pagos WHERE por_concepto='devolucion_derecho_examen' AND id_alumno='$id_alumno'";
			if(DEBUG){ echo"--->$cons<br>";}
			$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
			$DC=$sql->fetch_row();
				$derechoExamenDevuelto=$DC[0];
				if(empty($derechoExamenDevuelto)){ $derechoExamenDevuelto=0;}
			$sql->free();
			//busco lo pagado
			$cons="SELECT SUM(valor) FROM pagos WHERE por_concepto='derecho a examen' AND id_alumno='$id_alumno'";
			if(DEBUG){ echo"--->$cons<br>";}
			$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
			$DC=$sql->fetch_row();
				$pagoDerechoExamen=$DC[0];
				if(empty($pagoDerechoExamen)){ $pagoDerechoExamen=0;}
			$sql->free();
			
			$totalDerechoExamen=$pagoDerechoExamen-$derechoExamenDevuelto;
			if($totalDerechoExamen>0){$continuar=true;}
			
			if($continuar){
				$objResponse->Assign('valor',"value",$$totalDerechoExamen);
				$objResponse->Assign('glosa',"value",'devolucion de derecho a examen');
				$objResponse->Assign('valorTotalDevolucion',"value",$totalDerechoExamen);
				}
			break;
	}

	
	return $objResponse;
}
$xajax->processRequest();
?>