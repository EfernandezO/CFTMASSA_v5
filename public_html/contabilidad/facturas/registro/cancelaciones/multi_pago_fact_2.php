<?php 
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("gestion_facturas_v1.2");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	//------------------------------------------------//
	$acceso=false;
	$comparador=md5("P_facturas".date("Y-m-d"));
	$validador=$_POST["validador"];
	if($comparador==$validador)
	{$acceso=true;}
	$verificador=$_SESSION["FACTURA"]["verificador"];
	//------------------------------------------------//
if(($_POST)and($acceso)and($verificador))	
{
	if(!DEBUG){$_SESSION["FACTURA"]["verificador"]=false;}
	include("../../../../../funciones/VX.php");
	
	
	require("../../../../../funciones/conexion_v2.php");
	require("../../../../../funciones/funciones_sistema.php");
	/////variables/////}
	if(DEBUG)
	{ 
		var_export($_POST);
		echo"<br>";
	}
	$valor_facturas=mysql_real_escape_string($_POST["valor_facturas"]);
	$opcion_pago=$_POST["opcion_pago"];
	$fecha_pagoX=mysql_real_escape_string($_POST["fecha_pagoX"]);
	$comentario=mysql_real_escape_string($_POST["comentario"]);
	$comentario2=mysql_real_escape_string($_POST["comentario2"]);
	$cheque_numero=mysql_real_escape_string($_POST["cheque_numero"]);
	$cheque_banco=mysql_real_escape_string($_POST["cheque_banco"]);
	$cheque_fecha_vence=mysql_real_escape_string($_POST["cheque_fecha_vence"]);
	$cheque_sede=$_POST["sede_cheque"];
	$checkbox2=$_POST["checkbox2"];
	$id_factura=$_POST["id_factura"];
	$movimiento=mysql_real_escape_string($_POST["movimiento"]);
	
	$por_concepto="";
	$item="000";
	////////////////////////////
	$tipo_documento="factura";
	////////////////////
	//para campos agregados
		$id_usuario_actual=$_SESSION["USUARIO"]["id"];
		date_default_timezone_set('America/Santiago');//zona horaria
		$fecha_generacion=date("Y-m-d H:i:s");
		$ip=$_SERVER['REMOTE_ADDR'];
		$id_item="";
	///////////
	////////////obtengo semestre y año del la fecha ingresada/////////////
		$DFI=explode("-",$fecha_pagoX);
		$year=$DFI[0];
		$mes=$DFI[1];
		$dia=$DFI[2];
		
		if(abs($mes)>6)
		{$semestre=2;}
		else
		{$semestre=1;}
	//////////-----------------------------------------------/////////////
		///segun forma de pago
		switch($opcion_pago)
		{
			case"efectivo":
				if(DEBUG)
					{echo"Pago -> Efectivo<br>";}
					$fecha_vencimiento_cheque="0000-00-00";
					$id_cheque=0;
				break;
			case"cheque":
				if(DEBUG)
					{echo"Pago -> Cheque<br>";}
				$cheque["numero"]=$cheque_numero;
				$cheque["fecha_vence"]=$cheque_fecha_vence;
				$cheque["banco"]=$cheque_banco;
				$cheque["valor"]=$valor_facturas;
				$cheque["sede"]=$cheque_sede;
				
				$glosa_cheque="Pago Factura(s) COD.: $id_factura";
				$cheque["glosa"]=$glosa_cheque;
				$cheque["movimiento"]=$movimiento;
				$cheque["id_alumno"]=0;
				$id_cheque=REGISTRA_CHEQUE($cheque);
				$fecha_vencimiento_cheque=$cheque_fecha_vence;
				break;	
		}
	
		//-----------------recorro las facturas y creo un pago por cada una de ellas-------------------------////
		$array_id_factura=explode(",",$id_factura);
		$campos="tipo_receptor, id_proveedor, item, id_factura, fechapago, valor, tipodoc, glosa, sede, movimiento, forma_pago, fechaV_cheque, id_cheque, por_concepto, semestre, year, cod_user, fecha_generacion, ip, aux_num_documento";
		foreach($array_id_factura as $nf => $valorf)	
		{
			if(DEBUG){echo"$nf -> $valorf<br>";}
			///datos de factura
			$cons_f="SELECT * FROM facturas WHERE id='$valorf' LIMIT 1";
			if(DEBUG){echo"F.-> $cons_f<br>";}
			$sql_f=mysql_query($cons_f)or die("datos factura".mysql_error());
			$D_f=mysql_fetch_assoc($sql_f);
			$aux_valor_factura=$D_f["valor"];
			$aux_sede_factura=$D_f["sede"];
			$aux_cod_factura=$D_f["cod_factura"];
			$aux_proveedor=$D_f["proveedor"];
			
			$aux_id_proveedor=$D_f["id_proveedor"];
			$aux_tipo_receptor="proveedor";
			
			if($movimiento=="E")
			{
				$glosa_pago="Pago Total de Factura Proveedor: $aux_proveedor Cod.: $aux_cod_factura";
				//$item="";
				$por_concepto="pago_factura";
			}
			else
			{
				$glosa_pago="Recivo Pago con Factura Cod.: $aux_cod_factura";
				//$item="";
				$por_concepto="recivo_pago_con_factura";
			}
			//generando consulta insert pagos
			$valores="'$aux_tipo_receptor', '$aux_id_proveedor', '$item', '$valorf', '$fecha_pagoX', '$aux_valor_factura', '$tipo_documento', '$glosa_pago', '$aux_sede_factura', '$movimiento', '$opcion_pago', '$fecha_vencimiento_cheque', '$id_cheque', '$por_concepto', '$semestre', '$year', '$id_usuario_actual', '$fecha_generacion', '$ip', '$aux_cod_factura'";
			$cons_pf="INSERT INTO pagos ($campos) VALUES($valores)";
			if(DEBUG){ echo"-> $cons_pf<br>";}
			else
			{mysql_query($cons_pf)or die("(1) insertando pago: ".mysql_error());}
			mysql_free_result($sql_f);
			////actualizo condicion de factura
			$cons_UP_F="UPDATE facturas SET condicion='cancelada', saldo=saldo-$aux_valor_factura, abono=abono+$aux_valor_factura WHERE id='$valorf' LIMIT 1";
			if(DEBUG)
			{ echo"UP facturas.: $cons_UP_F<br>";}
			else
			{ 
				mysql_query($cons_UP_F)or die("actualizando factura".mysql_error);
				$evento="Pago de Factura id_factura: $id_factura num: $aux_cod_factura";
				REGISTRA_EVENTO($evento);
			}
		}
		mysql_close($conexion);
		if(DEBUG){ echo"Fin<br>";}
		else{header("location: ../ver/ver_factura.php?error=6");}
		
}
else
{ header("location: ../ver/ver_factura.php");}
///////////////////////////////////////////////////////////////////
//--------------------> Registro CHEQUE <------------------------//
///////////////////////////////////////////////////////////////////
?>