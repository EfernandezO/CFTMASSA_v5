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
if($_POST)
{
	if(DEBUG){var_dump($_POST);}
	include ("../../../funciones/funcion.php");
	//recolecto variable
	$url_next="paso_3b.php";
	$opcion_pago_mat=$_POST["opcion_matricula"];
	$fecha_vence_cuota=$_POST["fecha_vence_cuota_mat"];
	$num_cheque=$_POST["cheque_numero"];
	$cheque_banco=$_POST["cheque_banco"];
	//echo"--> $cheque_banco<br>";
	$cheque_fecha_vence=$_POST["cheque_fecha_vence"];
	//$num_boleta=$_POST["num_boleta"];
	$num_boleta=1;
	if(isset($_POST["beca"]))
	{$tiene_beca=$_POST["beca"]; }
	else{ $tiene_beca=""; }
	
	$cantidad_beca=$_POST["cantidad_beca"];
	$porcentaje_beca=$_POST["porcentaje_beca"];
	$comentario_beca=str_inde($_POST["fcomentario_beca"],"");
	
	if(isset($_POST["comentario_2"]))
	{$comentario_2=$_POST["comentario_2"];}
	else{ $comentario_2="";}
	///*****///
	$beca_nuevo_milenio=$_POST["beca_nuevo_milenio"];
	$aporte_beca_nuevo_milenio=$_POST["aporte_beca_nuevo_milenio"];
	
	$beca_excelencia_academica=$_POST["beca_excelencia_academica"];
	$aporte_beca_excelencia_academica=$_POST["aporte_beca_excelencia_academica"];
	
	if(!is_numeric($aporte_beca_nuevo_milenio))
	{ $aporte_beca_nuevo_milenio=0;}
	///*****///
	$excedente=$_POST["excedente"];
	$id_contrato_anterior=$_POST["id_contrato_anterior"];
	//var_dump($_SESSION["FINANZAS"]);
	//para los valores de las becas los guardo en session
	//echo"---> $cantidad_beca<br>";
	if(($tiene_beca=="SI")and(is_numeric($cantidad_beca))and(is_numeric($porcentaje_beca)))
	{
		if(($cantidad_beca>=0)or($porcentaje_beca>=0)or($aporte_beca_nuevo_milenio>=0))
		{
			if(DEBUG){ echo"<br>---------> guardar datos de beca<br>";}
			$_SESSION["FINANZAS"]["porcentaje_beca"]=$porcentaje_beca;
			$_SESSION["FINANZAS"]["comentario_beca"]=$comentario_beca;
			$_SESSION["FINANZAS"]["comentario_beca_v2"]=$comentario_2;
			$_SESSION["FINANZAS"]["cantidad_beca"]=$cantidad_beca;
			
			$_SESSION["FINANZAS"]["beca_nuevo_milenio"]=$beca_nuevo_milenio;
			$_SESSION["FINANZAS"]["aporte_beca_nuevo_milenio"]=$aporte_beca_nuevo_milenio;
			
			$_SESSION["FINANZAS"]["beca_excelencia_academica"]=$beca_excelencia_academica;
			$_SESSION["FINANZAS"]["aporte_beca_excelencia_academica"]=$aporte_beca_excelencia_academica;
			//echo"cantidad beca...> $cantidad_beca<br>";
		}
	}
	else
	{
		if(isset($_SESSION["FINANZAS"]["comentario_beca"]))
		{$_SESSION["FINANZAS"]["comentario_beca"]="";}
		if(isset($_SESSION["FINANZAS"]["cantidad_beca"]))
		{$_SESSION["FINANZAS"]["cantidad_beca"]=0;}
		if(isset($_SESSION["FINANZAS"]["comentario_beca_v2"]))
		{$_SESSION["FINANZAS"]["comentario_beca_v2"]="";}
		if(isset($_SESSION["FINANZAS"]["porcentaje_beca"]))
		{$_SESSION["FINANZAS"]["porcentaje_beca"]=0;}
		
	}
	//fin becas
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
				$url="paso2.php?error=2";//numero boleta invalido
				
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
				$url="paso2.php?error=3";//faltan datos a cheque
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
					$url="paso2.php?error=4";//sin excedente
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
	header("location: formu.php");
} 
//funcion para comprobar si letra ya existe x numero y probablemente x sede
function COMPRUEBA_LETRA($num_letra, $sede)
{
	//echo"funcion<br>";
	if(is_numeric($num_letra))
	{
		include("../../../funciones/conexion.php");
		//
		$cons="SELECT COUNT(numletra) FROM letras WHERE numletra='$num_letra' AND sede='$sede'";
		//echo"$cons<br>";
		$sql=mysql_query($cons)or die(mysql_error());
		$L=mysql_fetch_row($sql);
		$coincidencia=$L[0];
		//echo"--> $coincidencia<br>";
		if($coincidencia>0)
		{
			//echo"letra ya registrada<br>";
			$error=false;
		}
		else
		{
			//echo"letra no registrada<br>";
			$error=true;
		}
		
		mysql_free_result($sql);
		mysql_close($conexion);
	}
	else
	{
		$error=false; //hay error
	}	
	return $error;
}
?>