<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("gestion_cobranza_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../../funciones/codificacion.php");?>
<title>Informe Cobranzas Realizadas</title>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 110px;
}
</style>
</head>
<?php
$continuar=false;
if($_POST)
{
	require("../../../../../funciones/conexion_v2.php");
	require("../../../../../funciones/funciones_sistema.php");
	$continuar=true;
	if(DEBUG){ var_dump($_POST);}
	$fecha_actual=date("Y-m-d");

	$campo_periodo=mysqli_real_escape_string($conexion_mysqli, $_POST["campo_periodo"]);//determina en que campo de fecha busca
	$rut_alumno=mysqli_real_escape_string($conexion_mysqli, $_POST["rut_alumno"]);
	$sede=mysqli_real_escape_string($conexion_mysqli, $_POST["sede"]);
	$id_carrera=mysqli_real_escape_string($conexion_mysqli, $_POST["carrera"]);
	$id_personal=mysqli_real_escape_string($conexion_mysqli, $_POST["id_personal"]);
	$fecha_inicio=mysqli_real_escape_string($conexion_mysqli, $_POST["fecha_inicio"]);
	$fecha_fin=mysqli_real_escape_string($conexion_mysqli, $_POST["fecha_fin"]);
	$aux_nombre_carrera=NOMBRE_CARRERA($id_carrera);
}
?>
<body>
<h1 id="banner">Administrador - Informe Cobranzas Realizadas</h1>
<div id="link"><br />
<a href="cobranza_realizada_1.php" class="button">Volver a Seleccion</a></div>
<div id="apDiv1">

<table width="100%" border="1">
<thead>
  <tr>
    <th colspan="14">Listado de Cobranzas Realizadas en <?php echo"$sede perido ($campo_periodo)[$fecha_inicio - $fecha_fin] carrera: $aux_nombre_carrera";?></th>
    </tr>
  </thead>
  <tbody>  
  <tr>
    <td>N</td>
    <td>Rut</td>
    <td>Alumno</td>
    <td>Carrera</td>
    <td>Fecha</td>
    <td>Fecha Compromiso</td>
    <td>Monto cancelado hasta compromiso</td>
    <td>Tipo</td>
    <td>Usuario</td>
    <td>Year Cuota</td>
    <td>deuda al realizar cobranza</td>
    <td>deuda actual</td>
    <td>Hay Respuesta</td>
    <td>Observacion</td>
  </tr>
<?php
if($continuar)
{
	
		if(empty($rut_alumno)){ $filtrar_rut=false;}
		else{ $filtrar_rut=true;}
		
		if($id_carrera>0){ $condicion_carrera="AND id_carrera='$id_carrera'";}
		else{ $condicion_carrera="";}
		
		if($id_personal>0){ $condicion_personal="AND cod_user='$id_personal'";}
		else{ $condicion_personal="";}
		
		if($fecha_inicio==$fecha_fin){ $condicion_fecha="LEFT($campo_periodo, 10) = '$fecha_inicio'";}
		else{ $condicion_fecha="$campo_periodo BETWEEN '$fecha_inicio' AND '$fecha_fin'";}
		
		$cons_C="SELECT * FROM cobranza WHERE sede='$sede' $condicion_carrera $condicion_personal AND $condicion_fecha ORDER by fecha";
		
		$sqli_C=$conexion_mysqli->query($cons_C)or die($conexion_mysqli->error);
		$num_reg=$sqli_C->num_rows;
		$cuenta_registros=0;
		if(DEBUG){ echo"<strong>-->$cons_C</strong><br>Num. $num_reg<br>";}
		if($num_reg>0)
		{
			//----------------------------------------------//
			require("../../../../../funciones/VX.php");
			$evento="Revision informe Cobranzas realizadas periodo [$fecha_inicio - $fecha_fin] $sede id_carrera: $id_carrera id_personal: $id_personal";
			REGISTRA_EVENTO($evento);
			//---------------------------------------------//
			$n=0;
			while($C=$sqli_C->fetch_assoc())
			{
				$n++;
				$C_id=$C["id_cobranza"];
				$C_id_alumno=$C["id_alumno"];
				//---------------------------------------------//
				$cons_A="SELECT rut, nombre, apellido_P, apellido_M, id_carrera FROM alumno WHERE id='$C_id_alumno' LIMIT 1";
				$sqli_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
					$A=$sqli_A->fetch_assoc();
					$A_rut=$A["rut"];
					$A_alumno=$A["nombre"]." ".$A["apellido_P"]." ".$A["apellido_M"];
					$A_id_carrera=$A["id_carrera"];
				$sqli_A->free();	
				//---------------------------------------------//
				
				if($filtrar_rut)
				{
					if($rut_alumno==$A_rut){ $mostrar_registro=true;}
					else{ $mostrar_registro=false;}
				}
				else{ $mostrar_registro=true;}
				
				$C_id_carrera=$C["id_carrera"];
				
				if($C_id_carrera>0){$nombre_carrera=NOMBRE_CARRERA($C_id_carrera);}
				else{$nombre_carrera=NOMBRE_CARRERA($A_id_carrera);}
				
				$C_tipo=$C["tipo"];
				$C_fecha=$C["fecha"];
				$C_fecha_compromiso=$C["fecha_compromiso"];
				$C_hay_respuesta=$C["hay_respuesta"];
				$C_observacion=$C["observacion"];
				$C_deuda_al_realizar_cobranza=number_format($C["deuda_actual"],0,"","");
				$C_year_cuota=$C["year_cuota"];
				$C_cod_user=$C["cod_user"];
				$nombre_personal=NOMBRE_PERSONAL($C_cod_user);
				
				if($C_hay_respuesta==1){$C_hay_respuesta_label="Si";}
				else{ $C_hay_respuesta_label="No";}
				
				list($deuda_arancel_actual, $intereses_actuales, $gastos_cobranza_actuales)=DEUDA_ACTUAL_V2($C_id_alumno, $fecha_actual);
				$deuda_actual_alumno=($deuda_arancel_actual);
					//$deuda_actual_alumno=DEUDA_ACTUAL($C_id_alumno, $fecha_actual);
					
					
			//--------------------------------------------------------------------------------------------------------//	
				if(DEBUG){ echo"<strong>Busco Pagos...</strong><br>";}
				$valor_pagos=0;
				if(empty($C_fecha_compromiso))
				{if(DEBUG){ echo"Sin Fecha de Compromiso Registrada, no consulta pagos<br>";}}
				else
				{
					///busco pagos realizados desde la fecha de realizacion de cobranza a la fecha de compromiso
					$cons_P="SELECT SUM(valor) FROM pagos WHERE id_alumno='$C_id_alumno' AND por_concepto='arancel' AND fechapago BETWEEN '$C_fecha' AND '$C_fecha_compromiso' ORDER by idpago";
					$sqli_P=$conexion_mysqli->query($cons_P)or die($conexion_mysqli->error);
					$Dv=$sqli_P->fetch_row();
						$valor_pagos=$Dv[0];
						if(empty($valor_pagos)){ $valor_pagos=0;}
					$sqli_P->free();	
					if(DEBUG){ echo"-->$cons_P<br>Valor pagos: $valor_pagos<br>";}
				}
				if(DEBUG){ echo"<strong>FIN Busco Pagos...</strong><br>";}
			//--------------------------------------------------------------------------------------------------------///	
				
			
				
				if($mostrar_registro)
				{
					$cuenta_registros++;
					echo'<tr>
							<td>'.$n.'</td>
							<td>'.$A_rut.'</td>
							<td>'.$A_alumno.'</td>
							<td>'.$nombre_carrera.'</td>
							<td>'.$C_fecha.'</td>
							<td>'.$C_fecha_compromiso.'</td>
							<td align="right">'.$valor_pagos.'</td>
							<td>'.$C_tipo.'</td>
							<td>'.$nombre_personal.'</td>
							<td>'.$C_year_cuota.'</td>
							<td align="right">'.$C_deuda_al_realizar_cobranza.'</td>
							<td align="right">'.$deuda_actual_alumno.'</td>
							<td>'.$C_hay_respuesta_label.'</td>
							<td>'.$C_observacion.'</td>
						 </tr>';
				}
			}
			echo'<tr><td colspan="14">'.$cuenta_registros.'/'.$num_reg.' Registros de Cobranza Encontrados... generado el: '.date("d-m-Y").'</td></tr>';
		}
		else
		{ echo'<tr><td colspan="14">Sin Registro...</td></tr>';}
		$sqli_C->free();
	$conexion_mysqli->close();
}
?>
  </tbody>
</table>
</div>
</body>
</html>