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
$continuar=false;
if($_GET)
{
	$array_tipos_cobranza=array("telefonico", "domiciliaria", "carteo","email", "sms");
	
	require("../../../../../funciones/conexion_v2.php");
	require("../../../../../funciones/funciones_sistema.php");
	$continuar=true;
	if(DEBUG){ var_dump($_GET);}
	$sede=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["sede"]));
	$id_carrera=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["id_carrera"]));
	$year_ingreso=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["year_ingreso"]));
	$year_cuotas=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["year_cuotas"]));
	$jornada=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["jornada"]));
	$grupo=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["grupo"]));
	$array_niveles=base64_decode($_GET["niveles"]);
	
	$array_niveles=unserialize($array_niveles);
	
	$fecha_corte=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["fecha_corte"]));
	
}

	if($continuar)
	{
		require("../../../../../funciones/VX.php");
		$evento="Revisa Listado de Cobranza a XLS de sede: $sede id_carrera: $id_carrera";
		REGISTRA_EVENTO($evento);
		if(!DEBUG)
		{
			header('Content-type: application/vnd.ms-excel');
			header("Content-Disposition: attachment; filename=Listado_cobranza.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
		echo'<table border="1">
				<thead>
				<tr>
					<th colspan="11" bgcolor="#00FFFF">Alumno en Cobranza Sede: '.$sede.' Carrera: '.$id_carrera.' Jornada: '.$jornada.' Grupo: '.$grupo.' <br>year ingreso: '.$year_ingreso.' Year cuotas: '.$year_cuotas.'<br>Fecha corte: '.$fecha_corte.'</th>
				<tr>
					<tr>
						<th>N</th>
						<th>id_Carrera</th>
						<th>id_alumno</th>
						<th>Rut</th>
						<th>Nombre</th>
						<th>Apellido P</th>
						<th>Apellido M</th>
						<th>Nivel</th>
						<th>Fono</th>
						<th>Dias Morosidad</th>
						<th>Deuda Actual</th>
					</tr>
				</thead>';
		$array_niveles_serializado=base64_encode(serialize($array_niveles));
	
		if($id_carrera!=="0"){ $condicion_carrera="alumno.id_carrera='$id_carrera' AND";}
		else{ $condicion_carrera="";}
		
		if($year_ingreso!=="0"){ $condicion_ingreso=" AND alumno.ingreso='$year_ingreso'";}
		else{ $condicion_ingreso="";}
		
		if($year_cuotas!=="0"){ $condicion_year_cuota=" AND letras.ano='$year_cuotas'";}
		else{ $condicion_year_cuota="";}
		
		if($jornada!=="0"){ $condicion_jornada=" AND alumno.jornada='$jornada'";}
		else{ $condicion_jornada="";}
		
		if($grupo!=="0"){ $condicion_grupo=" AND alumno.grupo='$grupo'";}
		else{ $condicion_grupo="";}
		
		$condicion_fecha_corte=" AND letras.fechavenc<='$fecha_corte'";
		
		$inicio_ciclio=true;
		$niveles="";
		if(count($array_niveles)>0)
		{
			if(is_array($array_niveles))
			{
				foreach($array_niveles as $nn=>$valornn)
				{
					$valornn=mysqli_real_escape_string($conexion_mysqli, $valornn);
					if($inicio_ciclio)
					{ 
						$niveles.="'$valornn'";
						$inicio_ciclio=false;
					}
					else
					{ $niveles.=", '$valornn'";}
				}
			}
			else{ $niveles="'sin nivel'";}
			$condicion_nivel="AND alumno.nivel IN($niveles)";
		}
		else{$condicion_nivel="";}
		
		$condicion_cuota=" AND letras.pagada IN('N', 'A')";
		
		
		//----------------------------------SELECCION de Alumnos y llenado de array-----------------------------------------------//
		$cons_MAIN="SELECT DISTINCT(idalumn) FROM letras INNER JOIN alumno ON letras.idalumn=alumno.id WHERE $condicion_carrera alumno.sede='$sede' $condicion_ingreso $condicion_jornada $condicion_nivel $condicion_grupo $condicion_year_cuota $condicion_cuota $condicion_fecha_corte ORDER by alumno.id_carrera, alumno.apellido_P, alumno.apellido_M";
		if(DEBUG){ echo"---> $cons_MAIN<br>";}
		$sqli_M=$conexion_mysqli->query($cons_MAIN)or die("MAIN ".$conexion_mysqli->error);
		$num_alumnos=$sqli_M->num_rows;
		if(DEBUG){ echo"Total alumno encontrados: $num_alumnos<br>";}
		
		if($num_alumnos>0)
		{
			$aux=0;
			while($D=$sqli_M->fetch_row())
			{
				$aux_id_alumno=$D[0];
				$aux++;
				//-----------------------------------//
				$cons_A="SELECT * FROM alumno WHERE id='".$aux_id_alumno."' LIMIT 1";
				$sqli_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
				$DA=$sqli_A->fetch_assoc();
					
					$A_id_alumno=$DA["id"];
					$A_rut=$DA["rut"];
					$A_nombre=$DA["nombre"];
					$A_apellido_P=$DA["apellido_P"];
					$A_apellido_M=$DA["apellido_M"];
					$A_year_ingreso=$DA["ingreso"];
					$A_nivel=$DA["nivel"];
					$A_id_carrera=$DA["id_carrera"];
					$A_nombre_carrera=NOMBRE_CARRERA($A_id_carrera);
					$A_jornada=$DA["jornada"];
					$A_img=$DA["imagen"];
					$A_sede=$DA["sede"];
					$A_fono=$DA["fono"];
					$A_fono_2=$DA["fonoa"];
					$A_email=$DA["email"];
					
					
					$aplicar_intereses=$DA["aplicar_intereses"];
					$aplicar_gastos_cobranza=$DA["aplicar_gastos_cobranza"];
					
					if($aplicar_intereses==1){$aplicar_intereses=true;}
					else{ $aplicar_intereses=false;}
					
					if($aplicar_gastos_cobranza==1){$aplicar_gastos_cobranza=true;}
					else{ $aplicar_gastos_cobranza=false;}
					
					if($aplicar_intereses){ $info_interes=" Intereses: Si";}
					else{ $info_interes="Intereses: No";}
					
					if($aplicar_gastos_cobranza){ $info_interes.=" Gastos: Si";}
					else{ $info_interes.="Gastos: NO";}
					
					if(empty($A_img)){ $img_alumno="../../../BAses/Images/login_logo.png";}
					else{ $img_alumno=$ruta.$A_img;}
				$sqli_A->free();	
				list($A_deuda_actual_arancel, $A_intereses, $A_gastos_cobranza)=DEUDA_ACTUAL_V2($A_id_alumno, $fecha_corte);
				$A_deuda_actual=($A_deuda_actual_arancel+$A_intereses+$A_gastos_cobranza);
				$A_dias_morosidad_alumno=DIAS_MOROSIDAD($A_id_alumno);
				//--------------------------------------//
				echo'<tr>
						<td>'.$aux.'</td>
						<td>'.$A_id_carrera.'</td>
						<td>'.$aux_id_alumno.'</td>
						<td>'.$A_rut.'</td>
						<td>'.utf8_decode($A_nombre).'</td>
						<td>'.utf8_decode($A_apellido_P).'</td>
						<td>'.utf8_decode($A_apellido_M).'</td>
						<td>'.$A_nivel.'</td>
						<td>'.$A_fono.'/'.$A_fono_2.'</td>
						<td>'.$A_dias_morosidad_alumno.'</td>
						<td>'.$A_deuda_actual.'</td>
					</tr>';
			}
		}
		$sqli_M->free();
		echo'</table>';
	}
	else
	{
		if(DEBUG){ echo"No continuar<br>";}
	}
	
	$conexion_mysqli->close();
	$fecha_actual=date("Y-m-d");

?>