<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Repactar_cuotas_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//--------------//	
 $acceso=false;
 $comparador=md5("reasignacion_c".date("Y-m-d"));
 $validador=$_POST["validador"];
 if($validador==$comparador)
 { $acceso=true;}
if($_SESSION["REASIGNAR"]["verificador"])
{
if(($_POST)and($acceso))
{
	if(!DEBUG){$_SESSION["REPACTAR"]["verificador"]=false;}
	 require("../../../funciones/conexion_v2.php");
	 if(DEBUG){ var_dump($_POST);}
	 if(DEBUG){echo"<br><br>===================================================================================<br>";}
	 
	 
	$error=0;
	$id_contratoX=$_POST["id_contratoX"];
	$linea_credito_cantidad=$_POST["linea_credito_cantidad"];
	$linea_credito_cantidad_cuotas=$_POST["linea_credito_cantidad_cuotas"];
	$linea_credito_mes_ini=$_POST["linea_credito_mes_ini"];
	$meses_avance=$_POST["meses_avance"];
	$linea_credito_dia_vencimiento=$_POST["linea_credito_dia_vencimiento"];
	$linea_credito_year=$_POST["linea_credito_year"];
	
	
	
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$sede=$_SESSION["SELECTOR_ALUMNO"]["sede"];
	$nivel_alumno=$_SESSION["SELECTOR_ALUMNO"]["nivel"];
	$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	
	$tipo="cuota";
	$fecha_hora_actual=date("Y-m-d H:i:s");
	$fecha_actual=date("Y-m-d");
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	
	
	///datos contrato
	$cons_C="SELECT semestre, ano FROM contratos2 WHERE id='$id_contratoX' LIMIT 1";
	$sqli_C=$conexion_mysqli->query($cons_C)or die($conexion_mysqli->error);
	$CO=$sqli_C->fetch_assoc();
		$CO_semestre=$CO["semestre"];
		$CO_year=$CO["ano"];
	$sqli_C->free();	
	//-----------------------------------------------------------------------------------------------------------------------------------//
	$txt_repactacion=" [Contrato Repactado el ".$fecha_actual." por una deuda total de $".number_format($linea_credito_cantidad,0,",",".")."]";
	 //---------------------------------------------------------------------------------------------------------------------------------///
	 
	 $cons_up_contrato="UPDATE contratos2 SET repactado='1', txt_beca=CONCAT(txt_beca, '$txt_repactacion') WHERE id='$id_contratoX' LIMIT 1";
	 if(DEBUG){ echo"UP-> $cons_up_contrato<br>";}
	 else{ $conexion_mysqli->query($cons_up_contrato)or die("Actualizando contrato ".$conexion_mysqli->error);}
	 
	 //elimino cuotas antiguas
	$cons_del="DELETE FROM letras WHERE id_contrato='$id_contratoX' AND idalumn='$id_alumno'";
	if(DEBUG){ echo"FUNCION -> $cons_del<br>";}
	else{ $conexion_mysqli->query($cons_del)or die("borra_cuota ".$conexion_mysqli->error);}
	 //////////ARMANDO CUOTAS/////////////////////////////////////
	 			$dia_vence=$linea_credito_dia_vencimiento;
				$mes=$linea_credito_mes_ini;
				$año=$linea_credito_year;
				$valor_cuota=round($linea_credito_cantidad/$linea_credito_cantidad_cuotas);
				
				for($c=1;$c<=$linea_credito_cantidad_cuotas;$c++)
				{
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
						$vencimiento="$año-$mes_label-$dia_vence_label";	
					}	
					////avance y condiciones para fechas
					$mes+=$meses_avance;
					if($mes>12)
					{
						$mes-=12;//modificado
						$año++;
					}
					//////////////////////////////////
					///armado de consulta
					$campos="idalumn, id_contrato, numcuota, fechavenc, valor, deudaXletra, ano, semestre, fechemision, sede, tipo";
					$valores="'$id_alumno', '$id_contratoX', '$c', '$vencimiento', '$valor_cuota', '$valor_cuota', '$CO_year', '$CO_semestre', '$fecha_actual', '$sede', '$tipo'";
					$cons_in_c="INSERT INTO letras ($campos) VALUES ($valores)";
					if(DEBUG){echo "<br>$cons_in_c";}
					else
					{
						$conexion_mysqli->query($cons_in_c)or die("cuotas ".$conexion_mysqli->error);
					}
				}
				
				//cambio situacion financiera de alumno
				$nueva_condicion="V";
				$cons_UP_A="UPDATE alumno SET situacion_financiera='$nueva_condicion' WHERE id='$id_alumno' LIMIT 1";
				if(DEBUG){ echo"<br>---->$cons_UP_A<br>";}
				else{$conexion_mysqli->query($cons_UP_A)or die($conexion_mysqli->error);}
				//-------------------------------------------------------------------------//
				
	 ///////////////////////////////////////////////
		 /////////////
		 include("../../../funciones/VX.php");
		 $evento="Realiza Repactacion de Cuotas de Alumno en el contrato id_contrato: $id_contratoX id_alumno:$id_alumno";
		 REGISTRA_EVENTO($evento);
		 
		 $descripcion="Realiza proceso de Repactacion de Cuotas de Contrato id_contrato: $id_contratoX";
		 REGISTRO_EVENTO_ALUMNO($id_alumno, "notificacion", $descripcion);
		 ///////////////////////////
		 @mysql_close($conexion);
		 $conexion_mysqli->close();
		 $url="msj_final.php?error=$error";
		 if(DEBUG){ echo"<br>URL: $url<br>";}
		 else
		 { header("location: $url");}
}
else
{ 
	if(DEBUG){ echo"Sin Acceso Redirijir<br>";}
	else{header("location: repactar_cuota_1.php");}
}	
}
else
{ echo"Accion Ya Realizada...";}
///////////////////////////////-----------------------------------///////////////////////////////////
?>