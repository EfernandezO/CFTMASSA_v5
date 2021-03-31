<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumno->asignacion de Becas V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
 $acceso=false;
 $comparador=md5("reasignacion_c".date("Y-m-d"));
 $validador=$_POST["validador"];
  if(DEBUG){ var_dump($_POST); foreach($_POST as $n => $valor){echo'$'.$n.'=$_POST["'.$n.'"];'."<br>";}}
 if($validador==$comparador){ $acceso=true;}
if($_SESSION["REASIGNAR"]["verificador"])
{
	if(($_POST)and($acceso) and($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
	{
		if(!DEBUG){$_SESSION["REASIGNAR"]["verificador"]=false;}
		 require("../../../funciones/conexion_v2.php");
		
		 if(DEBUG){echo"<br><br>===================================================================================<br>";}
		 
		 
		 
		$vigencia_contrato_manual=$_POST["vigencia_contrato_manual"];
		if(DEBUG){ echo"-->$vigencia_contrato_manual<br>";}
		
		if(strpos($vigencia_contrato_manual,"_")>0){
			$ARRAY_vigencia_contrato_manual=explode("_",$vigencia_contrato_manual);
			$vigencia_contrato_manual=$ARRAY_vigencia_contrato_manual[1];
			$semestre_manual=$ARRAY_vigencia_contrato_manual[0];
		}else{
			$vigencia_contrato_manual=$vigencia_contrato_manual;
			$semestre_manual=3;
		}
		
		if(DEBUG){ echo"(Manual) VIGENCIA  $vigencia_contrato_manual SEMESTRE: $semestre_manual<br>";}
		$hay_cuotas=$_POST["hay_cuotas"];
		if($hay_cuotas==1){$generarCuotas=true;}
		else{ $generarCuotas=false;}
		
		
		$totalBeneficiosEstudiantiles=$_POST["totalBeneficiosEstudiantiles"];
		$id_contrato=$_POST["id_contrato"];
		$arancel=$_POST["arancel"];
		$total_cancelado=$_POST["total_cancelado"];
		$semestre=$_POST["semestre"];
		$year=$_POST["year"];
		if(isset($_POST["linea_credito_cantidad"])){$linea_credito_cantidad=$_POST["linea_credito_cantidad"];}
		else{$linea_credito_cantidad=0;}
		if(isset($_POST["linea_credito_cantidad_cuotas"])){$linea_credito_cantidad_cuotas=$_POST["linea_credito_cantidad_cuotas"];}
		else{$linea_credito_cantidad_cuotas=0;}
		$excedenteContratoPrevio=$_POST["excedente_anterior"];
		$total_saldar=$_POST["total_saldar"];
		$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
		$sede=$_SESSION["SELECTOR_ALUMNO"]["sede"];
		$nivel_alumno=$_SESSION["SELECTOR_ALUMNO"]["nivel"];
		$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
		$jornada_alumno=$_SESSION["SELECTOR_ALUMNO"]["jornada"];
		$vigencia_contrato=$vigencia_contrato_manual;
		$fecha_hora_actual=date("Y-m-d H:i:s");
		$fecha_actual=date("Y-m-d");
		$id_usuario_actual=$_SESSION["USUARIO"]["id"];

		 ////Generando nuevo contrato
			 //busco datos del antiguo
			 $cons_C="SELECT * FROM contratos2 WHERE id='$id_contrato' LIMIT 1";
			 $sql_c=$conexion_mysqli->query($cons_C);
			 
			 $DC=$sql_c->fetch_assoc();
			 
			 $CO_yearingresocarrera=$DC["yearIngresoCarrera"];
			 $CO_fecha_ini=$DC["fecha_inicio"];
			 $CO_fecha_ini=date("Y-m-d");//fecha actual
			 $CO_fecha_fin=$DC["fecha_fin"];
			 $CO_year=$DC["ano"];
			 $CO_nivel_alumno=$DC["nivel_alumno"];
			 $CO_nivel_alumno_2=$CO_nivel_alumno+1;
			 
			 $CO_fecha_fin=$CO_year."-12-30";//fecha final = fin de año
			 $CO_semestre=$DC["semestre"];
			 $CO_opcion_paga_matricula=$DC["opcion_pag_matricula"];
			 $CO_matricula_valor=$DC["matricula_valor"];
			 $CO_matricula_a_pagar=$DC["matricula_a_pagar"];
			 $CO_sostenedor=$DC["sostenedor"];
			 $CO_id_boleta_generada=$DC["id_boleta_generada"];
			 $sql_c->free();
		 
		 
			 $condicion_contrato="OK";
			
		
		 
		 if($total_saldar>0){$total_a_pagar=$total_saldar; $excedente=0;}
		 else{$total_a_pagar=0;  $excedente=($total_saldar*-1); }
		 ////
		
		$saldoAfavor=$total_cancelado+$excedenteContratoPrevio;
		
		
		 ////////////////////////////
		 $reasignado="si";
		 //genero nuevo
		 $campos1="id_alumno, id_carrera, nivel_alumno, nivel_alumno_2, jornada, sede, yearIngresoCarrera, fecha_inicio, fecha_fin, ano, semestre, numero_cuotas, arancel, saldo_a_favor, porcentaje_desc_contado, total, contado_paga, cheque_paga, id_boleta_generada, linea_credito_paga, totalBeneficiosEstudiantiles, opcion_pag_matricula, matricula_valor, matricula_a_pagar, sostenedor, cod_user, fecha_generacion, vigencia, condicion, excedente, id_contrato_previo, reasignado";
		 
		 $valores1="'$id_alumno', '$id_carrera', '$CO_nivel_alumno', '$CO_nivel_alumno_2', '$jornada_alumno', '$sede', '$CO_yearingresocarrera', '$CO_fecha_ini', '$CO_fecha_fin', '$CO_year', '$CO_semestre', '$linea_credito_cantidad_cuotas', '$arancel', '$saldoAfavor', '0', '$total_a_pagar', '0', '0', '$CO_id_boleta_generada', '$linea_credito_cantidad', '$totalBeneficiosEstudiantiles', '$CO_opcion_paga_matricula', '$CO_matricula_valor', '$CO_matricula_a_pagar', '$CO_sostenedor', '$id_usuario_actual', '$fecha_hora_actual', '$vigencia_contrato', '$condicion_contrato', '$excedente', '$id_contrato', '$reasignado'";
		 
		 $cons_contrato="INSERT INTO contratos2 ($campos1)VALUES($valores1)";
		 
		 //actualixo contrato previo
		 
		 $cons_up_old_contrato="UPDATE contratos2 SET condicion='inactivo', excedente='0',  totalBeneficiosEstudiantiles='0' WHERE id='$id_contrato' LIMIT 1";
		 if(DEBUG)
		 {
			echo"Inserto Nuevo Contrato-> <br>$cons_contrato<br><br>";
			
			echo"Actualizo contrato Previo-><br> $cons_up_old_contrato<br>";
			$id_contrato_new=1;
			$continuar=true;
		 }
		 else
		 {
			$conexion_mysqli->query($cons_contrato);
			$id_contrato_new=$conexion_mysqli->insert_id;
			if($id_contrato_new>0)
			{
				//cambio de estado Contrato_anterio
				$conexion_mysqli->query($cons_up_old_contrato);
				$continuar=true;
			}
			else
			{$continuar=false;}
		 }
		
		//elimino beneficios estudiantiles de contrato previo
		
		$cons_DEL_BE="DELETE FROM  beneficiosEstudiantiles_asignaciones WHERE id_alumno='$id_alumno' AND id_contrato='$id_contrato'";
		if(DEBUG){ echo"Borro Beneficios Estudiantiles Previos<br>$cons_DEL_BE<br>";}
		else{$conexion_mysqli->query($cons_DEL_BE) or die($conexion_mysqli->error);}
		
		
		///registrando beneficios estudiantiles Nuevos
		if(($id_contrato_new>0)and($totalBeneficiosEstudiantiles>0)){
			if(DEBUG){ echo"<b>Registrar Nuevos Beneficios asignados</b><br>";}
			if(count($_SESSION["FINANZASX"]["beneficiosEstudiantiles"]>0)){
				foreach($_SESSION["FINANZASX"]["beneficiosEstudiantiles"] as $auxIdBeneficio =>$arrayValores){
				 $auxNombre=$arrayValores["nombre"];
				 $auxAporteValor=$arrayValores["aporteValor"];
				 $auxAportePorcentaje=$arrayValores["aportePorcentaje"];
				 $auxTipo=$arrayValores["tipo"];
				 
				 if($auxTipo=="porcentaje"){$totalizadoBeneficio=($arancel*$auxAportePorcentaje)/100;}
				 else{$totalizadoBeneficio=$auxAporteValor;}
				 
				 $cons_BE="INSERT INTO beneficiosEstudiantiles_asignaciones (id_alumno, id_contrato, id_beneficio, valor) VALUES ('$id_alumno', '$id_contrato_new', '$auxIdBeneficio', '$totalizadoBeneficio')";
				 
				 if(DEBUG){ echo"---> $cons_BE<br>";}
				 else{ $conexion_mysqli->query($cons_BE)or die("Beneficio estudiantil ".$conexion_mysqli->error);}
				}
			}
			if(DEBUG){ echo"<br><br>";}
		}else{if(DEBUG){echo"<b>NO Registrar Beneficios asignados, no hay</b><br>";}}
	
		
		ELIMINA_CUOTAS_OLD($id_contrato, $id_alumno);//elimino cuotas antiguas
			
		if($generarCuotas)
		{
			$linea_credito_mes_ini=$_POST["linea_credito_mes_ini"];
			$meses_avance=$_POST["meses_avance"];
			$linea_credito_dia_vencimiento=$_POST["linea_credito_dia_vencimiento"];
			$linea_credito_year=$_POST["linea_credito_year"];
			if(DEBUG){ echo"Generar Cuotas<br>";}
			$tipo="cuota";
		 //////////ARMANDO CUOTAS/////////////////////////////////////
			$dia_vence=$linea_credito_dia_vencimiento;
			$mes=$linea_credito_mes_ini;
			$año=$linea_credito_year;
			$valor_cuota=round($linea_credito_cantidad/$linea_credito_cantidad_cuotas);
			
			for($c=1;$c<=$linea_credito_cantidad_cuotas;$c++)
			{
				if(($dia_vence>28)and($mes==2))
				{$vencimiento="28/02/$año";}
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
				$valores="'$id_alumno', '$id_contrato_new', '$c', '$vencimiento', '$valor_cuota', '$valor_cuota', '$year', '$semestre', '$fecha_actual', '$sede', '$tipo'";
				$cons_in_c="INSERT INTO letras ($campos) VALUES ($valores)";
				if(DEBUG){echo "<br>$cons_in_c";}
				else{$conexion_mysqli->query($cons_in_c);}
			}//fin for
		}/// fin generar cuotas
		
			
		 if($continuar)
		 {$error=0;}

		 else
		 {
			echo"IMPOSIBLE CONTINUAR FALLA INSERTANDO CONTRATO<br>";
			$error=1;
		 }
		 /////////////
		 include("../../../funciones/VX.php");
		 $evento="ASIGNACION BECA COD.$id_contrato_new (sin excedente)";
		 REGISTRA_EVENTO($evento);
		 ///////////////////////////

		 $conexion_mysqli->close();
		 $url="msj_final.php?error=$error&hay_cuotas=$hay_cuotas";
		 if(DEBUG){ echo"<br><strong>URL: $url</strong><br>";}
		 else{ header("location: $url");}
		
	}
	else
	{ 
		if(DEBUG){ echo"Sin Acceso Redirijir<br>";}
		else{header("location: asignar_beca_1.php");}
	}	
		
}
else
{ echo"Accion Ya Realizada...";}

///////////////////////////////-----------------------------------///////////////////////////////////
function ELIMINA_CUOTAS_OLD($id_contrato, $id_alumno)
{
	require("../../../funciones/conexion_v2.php");
	$cons_del="DELETE FROM letras WHERE id_contrato='$id_contrato' AND idalumn='$id_alumno' AND tipo='cuota' AND pagada='N'";
	if(DEBUG){ echo"FUNCION Borro Cuotas Antiguas -> $cons_del<br>";}
	else
	{ $conexion_mysqli->query($cons_del);}
	$conexion_mysqli->close();
	
	AJUSTA_CUOTAS_PAGADAS_OLD($id_contrato, $id_alumno);
}
///////////////////////////////---------------------------------/////////////////////////////////////

function AJUSTA_CUOTAS_PAGADAS_OLD($id_contrato, $id_alumno){
	require("../../../funciones/conexion_v2.php");
	$cons_Up="UPDATE letras SET valor=valor-deudaXletra , deudaXletra='0', pagada='P' WHERE id_contrato='$id_contrato' AND idalumn='$id_alumno' AND tipo='cuota' AND pagada='A'";
	if(DEBUG){ echo"FUNCION Ajusta cuotas antiguas -> $cons_Up<br>";}
	else
	{ $conexion_mysqli->query($cons_Up);}
	$conexion_mysqli->close();
}
?>