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
if(DEBUG){echo"POST<br>"; var_dump($_POST); echo"<br>";}
if($_POST)
{
	if(DEBUG){ echo"hay Post<br>";}
	include ("../../../funciones/funcion.php");
	//recolecto variable
	$url_next="paso_3c.php";
	$opcion_pago_mat=$_POST["opcion_matricula"];
	$fecha_vence_cuota=$_POST["fecha_vence_cuota_mat"];
	$num_cheque=$_POST["cheque_numero"];
	$cheque_banco=$_POST["cheque_banco"];
	//echo"--> $cheque_banco<br>";
	$cheque_fecha_vence=$_POST["cheque_fecha_vence"];
	//$num_boleta=$_POST["num_boleta"];
	$num_boleta=1;
	///*****///
	$excedente=$_POST["excedente"];
	$id_contrato_anterior=$_POST["id_contrato_anterior"];
	//var_dump($_SESSION["FINANZAS"]);
	//para los valores de las becas los guardo en session
	//echo"---> $cantidad_beca<br>";
	$sede=$_SESSION["FINANZAS"]["sede_alumno"];
	
	$_SESSION["FINANZAS"]["opcion_matricula"]=$opcion_pago_mat;
	if(DEBUG){ echo"<br>OPCION Matricula: $opcion_pago_mat<br><br>";}
	//segun tipo de matricula sigo curso de accion
	switch($opcion_pago_mat)
	{
		case"NO":
			$_SESSION["FINANZAS"]["paso2"]=true;
			$url=$url_next;
			//borro si al session que no usare si es que cambio el resultado con la vez anterior
			//linea credito
			if(isset($_SESSION["FINANZAS"]["fecha_vence_lcredito_mat"]))
			{
				unset($_SESSION["FINANZAS"]["fecha_vence_lcredito_mat"]);
			}
			//contado
			if(isset($_SESSION["FINANZAS"]["num_boleta_mat"]))
			{
				unset($_SESSION["FINANZAS"]["num_boleta_mat"]);
			}
			//cheque
			if(isset($_SESSION["FINANZAS"]["num_cheque_mat"],$_SESSION["FINANZAS"]["fecha_vence_cheque_mat"],$_SESSION["FINANZAS"]["banco_cheque_mat"]))
			{
				unset($_SESSION["FINANZAS"]["num_cheque_mat"],$_SESSION["FINANZAS"]["fecha_vence_cheque_mat"],$_SESSION["FINANZAS"]["banco_cheque_mat"]);
			}
			//excedente
			if(isset($_SESSION["FINANZAS"]["EX_nuevo_excedente"]))
			{
				unset($_SESSION["FINANZAS"]["EX_nuevo_excedente"]);
			}
			
			break;
		case"CONTADO":
			if(is_numeric($num_boleta))
			{
				$url=$url_next;
				$_SESSION["FINANZAS"]["paso2"]=true;
				$_SESSION["FINANZAS"]["num_boleta_mat"]=$num_boleta;
				//bo//linea credito
					if(isset($_SESSION["FINANZAS"]["fecha_vence_lcredito_mat"]))
					{
						unset($_SESSION["FINANZAS"]["fecha_vence_lcredito_mat"]);
					}
					//cheque
					if(isset($_SESSION["FINANZAS"]["num_cheque_mat"],$_SESSION["FINANZAS"]["fecha_vence_cheque_mat"],$_SESSION["FINANZAS"]["banco_cheque_mat"]))
					{
						unset($_SESSION["FINANZAS"]["num_cheque_mat"],$_SESSION["FINANZAS"]["fecha_vence_cheque_mat"],$_SESSION["FINANZAS"]["banco_cheque_mat"]);
					}
					//excedente
					if(isset($_SESSION["FINANZAS"]["EX_nuevo_excedente"]))
					{
						unset($_SESSION["FINANZAS"]["EX_nuevo_excedente"]);
					}
					//borro si existen las session del contenido de linea credito y cheque
	
				//fin borro
				
			}
			else
			{
				$url="paso2c.php?error=2";//numero boleta invalido
				
			}	
			break;
		case"L_CREDITO":
			//compruebo si letra esta registrada
			if(!empty($fecha_vence_cuota))
			{
				//echo"Sin Error<br>";
				//$_SESSION["FINANZAS"]["num_letra_mat"]=$num_letra;
				$_SESSION["FINANZAS"]["fecha_vence_lcredito_mat"]=$fecha_vence_cuota;
				$_SESSION["FINANZAS"]["paso2"]=true;
				$url=$url_next;
				//borrro lo almacenado en session para pago contado si ya fue visitado
					//contado
					if(isset($_SESSION["FINANZAS"]["num_boleta_mat"]))
					{
						unset($_SESSION["FINANZAS"]["num_boleta_mat"]);
					}
					//cheque
					if(isset($_SESSION["FINANZAS"]["num_cheque_mat"]))
					{
						unset($_SESSION["FINANZAS"]["num_cheque_mat"]);
						unset($_SESSION["FINANZAS"]["banco_cheque_mat"]);
						unset($_SESSION["FINANZAS"]["fecha_vence_cheque_mat"]);
					}
					//excedente
					if(isset($_SESSION["FINANZAS"]["EX_nuevo_excedente"]))
					{
						unset($_SESSION["FINANZAS"]["EX_nuevo_excedente"]);
					}
				//fin borro
			}
			else
			{
				//echo"Error<br>";
				$url="paso2.php?error=1";
			}
			break;		
		case"CHEQUE":
			if((!empty($num_cheque))and(!empty($cheque_fecha_vence))and(!empty($cheque_banco)))
			{
				$_SESSION["FINANZAS"]["num_cheque_mat"]=$num_cheque;
				$_SESSION["FINANZAS"]["fecha_vence_cheque_mat"]=$cheque_fecha_vence;
				$_SESSION["FINANZAS"]["banco_cheque_mat"]=$cheque_banco;
				$_SESSION["FINANZAS"]["paso2"]=true;
				$url=$url_next;
					//linea credito
					if(isset($_SESSION["FINANZAS"]["fecha_vence_lcredito_mat"]))
					{
						unset($_SESSION["FINANZAS"]["fecha_vence_lcredito_mat"]);
					}
					//contado
					if(isset($_SESSION["FINANZAS"]["num_boleta_mat"]))
					{unset($_SESSION["FINANZAS"]["num_boleta_mat"]);}
					//excedente
					if(isset($_SESSION["FINANZAS"]["EX_nuevo_excedente"]))
					{unset($_SESSION["FINANZAS"]["EX_nuevo_excedente"]);}
			}
			else
			{
				$url="paso2c.php?error=3";//faltan datos a cheque
			}	
			break;
			case"EXCEDENTE":
				if(DEBUG){echo"<br> Excedente...<br>";}
				$valor_matricula=$_SESSION["FINANZAS"]["matricula"];
				$_SESSION["FINANZAS"]["paso2"]=true;
				if(($excedente>0)and($valor_matricula>0))
				{
					if(DEBUG){echo"Matricula: $valor_matricula<br>Excedente: $excedente<br><br>";}
					
					if($excedente>$valor_matricula)
					{
						$nuevo_excedente=($excedente-$valor_matricula);
						$nuevo_valor_matricula=0;
						$matricula_pagada="total";
					}
					elseif($valor_matricula==$excedente)
					{
						$nuevo_excedente=($valor_matricula-$excedente);
						$nuevo_valor_matricula=0;
						$matricula_pagada="total";
					}
					else
					{
						$nuevo_excedente=0;
						$nuevo_valor_matricula=($valor_matricula-$excedente);
						$matricula_pagada="parcial";
						
					}
					/////////////////
						$_SESSION["FINANZAS"]["EX_nuevo_excedente"]=$nuevo_excedente;
					////////////////
					
					if(DEBUG){echo"nuevo excedente=$nuevo_excedente<br>nuevo valor_matricula=$nuevo_valor_matricula<br>Matricula pagada: $matricula_pagada<br><br>";}
					
					//linea credito
					if(isset($_SESSION["FINANZAS"]["fecha_vence_lcredito_mat"]))
					{
						unset($_SESSION["FINANZAS"]["fecha_vence_lcredito_mat"]);
					}
					//contado
					if(isset($_SESSION["FINANZAS"]["num_boleta_mat"]))
					{
						unset($_SESSION["FINANZAS"]["num_boleta_mat"]);
					}
					//cheque
					if(isset($_SESSION["FINANZAS"]["num_cheque_mat"]))
					{
						unset($_SESSION["FINANZAS"]["num_cheque_mat"]);
						unset($_SESSION["FINANZAS"]["banco_cheque_mat"]);
						unset($_SESSION["FINANZAS"]["fecha_vence_cheque_mat"]);
					}
					$url=$url_next;
				}
				else
				{ 
					$url="paso2c.php?error=4";//sin excedente
				}
				break;
			
	}
	//GUARDO EL EXCEDENTE e id contrato old///////////////////////////////////////
		$_SESSION["FINANZAS"]["excedente"]=$excedente;
		$_SESSION["FINANZAS"]["id_contrato_anterior"]=$id_contrato_anterior;
			//////////////------------------------------------------///////////////////////
	//var_export($_SESSION["FINANZAS"]);
	//echo "-->".$_SESSION["FINANZAS"]["banco_cheque_mat"];
	if(DEBUG){ var_export($_SESSION["FINANZAS"]);}
	else{header("location: $url");}
}
else
{
	header("location: paso2c.php");
} 
?>