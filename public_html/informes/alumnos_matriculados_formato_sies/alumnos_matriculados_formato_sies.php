<?php 
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_alumnos_matriculados_formato_sies_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////////
if(!DEBUG)
	{
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=ALUMNOS_MATRICULADOS_FORMATO_SIES_".date("Y").".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
echo'
<table border="1">
<thead>
	<th>N.global</th>
	<th>N.xcarrera</th>
	<th>Run</th>
    <th>DV</th>
    <th>Apellido Paterno</th>
    <th>Apellido Materno</th>
    <th>Nombres</th>
    <th>Sexo</th>
    <th>Fecha Nacimiento</th>
    <th>Nacionalidad</th>
	<th>Pais Estudios Secundarios</th>
	<th>COD IES</th>
	<th> COD SEDE</th>
	<th>COD Carrera</th>
	<th>Jornada</th>
    <th>carrera</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
    <th>Codigo Carrera</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
    <th>Año Ingreso 1 año</th>
    <th>Semestre Ingreso 1 Año</th>
	<th>NEM</th>
    <th>Año Ingreso</th>
    <th>Semestre Ingreso </th>
    <th>Semestre Suspencion</th>
    <th>Proceso Titulacion</th>
    <th>Año Egreso Plan Regular</th>
    <th>Semestre Egreso</th>
	<th bgcolor="#FFFF00">SITUACION Alumno</th>
	<th bgcolor="#FFFF00">Condicion Contrato</th>
</thead>
<tbody>';
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	include("../../../funciones/VX.php");
		$contador=0;
		$sede=$_POST["sede"];
		$contrato_year=$_POST["year"];
		
		
		//-------------------------------------------------//
		$evento="Genera informe alumnos Matriculados formato SIES para sede: $sede year_contrato: $contrato_year";
		REGISTRA_EVENTO($evento);
		
		//-------------------------------------------------//
		
		echo"<strong>Sede:</strong> $sede <strong>Año de Contrato:</strong> $contrato_year";
		if(DEBUG){ var_export($_POST);}
		$hay_condiciones=true;
				
		if($sede!="0")
		{
			 $condicion_sede="AND contratos2.sede='$sede'";
			 $hay_condiciones=true;
		}
		else
		{ $condicion_sede="";}
		
		$cons_main_1="SELECT DISTINCT(id_alumno) FROM contratos2 INNER JOIN alumno ON contratos2.id_alumno = alumno.id WHERE contratos2.ano='$contrato_year' $condicion_sede AND contratos2.condicion<>'inactivo' AND alumno.situacion IN('V','EG') ORDER by alumno.carrera, alumno.apellido_P, apellido_M";
		
		
		if(DEBUG){ echo"<br><br><b>$cons_main_1</b><br>";}
		$sqli_main_1=$conexion_mysqli->query($cons_main_1)or die($conexion_mysqli->error);
		$num_reg_M=$sqli_main_1->num_rows;
		if(DEBUG){ echo"NUM GLOBAL: $num_reg_M<br>";}
		$contador_global=0;
		$aux_X=0;
		if($num_reg_M>0)
		{
			$aux_X++;
			$primera_vuelta=true;
			while($DID=$sqli_main_1->fetch_row())
			{
				$id_alumno=$DID[0];
				if(DEBUG){ echo"<br><br>-------------------------------------------------------------------<br>[$aux_X] UID:$id_alumno <br>";}
				$cons_main_2="SELECT MAX(id) FROM contratos2 WHERE id_alumno='$id_alumno' AND ano='$contrato_year' $condicion_sede AND condicion<>'inactivo'";
				if(DEBUG){ echo"<br>$cons_main_2<br>";}
				$sqli_main_2=$conexion_mysqli->query($cons_main_2)or die($conexion_mysqli->error);
				$DCM=$sqli_main_2->fetch_row();
					$aux_id_contrato=$DCM[0];
				$sqli_main_2->free();
					if(DEBUG){ echo"--->MAX id contrato: $aux_id_contrato<br>";}
					//-------------------------------------------------//
					$cons_main="SELECT alumno.*, contratos2.id as id_contrato, contratos2.jornada as contrato_jornada, contratos2.semestre, contratos2.ano, contratos2.vigencia, contratos2.condicion, contratos2.yearIngresoCarrera FROM alumno INNER JOIN contratos2 ON alumno.id = contratos2.id_alumno WHERE contratos2.id='$aux_id_contrato' $condicion_sede  AND contratos2.condicion='OK' LIMIT 1";
					
					if(DEBUG){ echo"<br>$cons_main<br>";}
	
					$sqli_main=$conexion_mysqli->query($cons_main)or die($conexion_mysqli->error);
					$num_registros=$sqli_main->num_rows;
					if(DEBUG){ echo"Numero Registros $num_registros<br>";}
					
					if($num_registros>0)
					{
						
						while($DB=$sqli_main->fetch_assoc())
						{
							$A_yearIngresoCarrera=$DB["yearIngresoCarrera"];
							$A_id=$DB["id"];
							$A_nombre=$DB["nombre"];
							$A_apellido_P=$DB["apellido_P"];
							$A_apellido_M=$DB["apellido_M"];
							$A_rut=$DB["rut"];		
							$array_rut=explode("-",$A_rut);
								$aux_rut_sin_guion=$array_rut[0];
								$aux_dv=$array_rut[1];
									
							$A_carrera=$DB["carrera"];
							$A_id_carrera=$DB["id_carrera"];
							$A_sexo=$DB["sexo"];
							if($A_sexo=="F"){$A_sexo="M";}
							else{$A_sexo="H";}

							$A_fecha_nac=$DB["fnac"];
							//$A_nacionalidad="chilena";
							$A_pais_origen=$DB["pais_origen"];
							$A_pais_estudios=$DB["liceo_pais"];
							$A_sede=$DB["sede"];
							$A_jornada=$DB["contrato_jornada"];
							$A_nem=$DB["liceo_nem"];
							$A_situacion=$DB["situacion"];	
							$aux_codigo_carrera=CODIGO_CARRERA_SIES($A_sede, $A_jornada, $A_carrera, $A_id_carrera, "carrera");
							$aux_codigo_carrera=str_replace('C','',$aux_codigo_carrera);

							$A_ingreso_primer_year=$DB["ingreso"];
							$A_ingreso_primer_semestre=1;
								$A_ingreso_year=$A_ingreso_primer_year;
								$A_ingreso_semestre=1;
								$A_nivel=$DB["nivel"];
							$C_condicion=$DB["condicion"];
							
							$aux_semestre_egreso="";
							
							if(DEBUG){ echo"<strong>yearIngresoCarrera:$A_yearIngresoCarrera id_carrera: $A_id_carrera</strong><br>";}
								if($A_nivel>=4)
								{
									if(DEBUG){ echo"Alumno con nivel >=4, Revisar si es Egresado<br>";}
									 list($es_egresado, $semestre_egreso, $year_egreso)=ES_EGRESADO($id_alumno, $A_id_carrera, $A_yearIngresoCarrera);
									 if($es_egresado)
									 {
										 if(DEBUG){ echo"Alumno es Egresado<br>";}
										 $proceso_terminal=1;
										 $aux_year_egreso=$year_egreso;
										 $aux_semestre_egreso=$semestre_egreso;
									 }
									 else
									 {
										  if(DEBUG){ echo"Alumno NO es Egresado<br>";}
										 $proceso_terminal=2;
										 $aux_year_egreso="";
										 $aux_semestre_egreso="";
									 }
									
								}
								else
								{ 
								if(DEBUG){ echo"Alumno con nivel <4, NO Revisar si es Egresado<br>";}
									$proceso_terminal=2;
									$aux_year_egreso="";
									$aux_semestre_egreso="";
								}
								
								$semestres_suspencion=SEMESTRE_SUSPENCION($id_alumno, $A_id_carrera, $A_yearIngresoCarrera);
							
							if(DEBUG){ echo"[$A_id - $A_rut ]->  situacion alumno: $A_situacion nivel Alumno: $A_nivel Proceso terminal: $proceso_terminal<br><br>";}
							$mostrar_alumno=true;
							
							if($mostrar_alumno)
							{
								if($primera_vuelta)
								{ 
									$carrera_old=$A_carrera;
									$primera_vuelta=false;
								}
								
								if($A_carrera==$carrera_old)
								{ $contador++;}
								else
								{$contador=1;}
								$carrera_old=$A_carrera;
								
								$contador_global++;
								
								echo'<tr>
								<td>'.$contador_global.'</td>
								<td>'.$contador.'</td>
								<td>'.$aux_rut_sin_guion.'</td>
								<td>'.$aux_dv.'</td>
								<td>'.utf8_decode($A_apellido_P).'</td>
								<td>'.utf8_decode($A_apellido_M).'</td>
								<td>'.utf8_decode($A_nombre).'</td>
								<td>'.$A_sexo.'</td>
								<td>'.$A_fecha_nac.'</td>
								<td>'.$A_pais_origen.'</td>
								<td>'.$A_pais_estudios.'</td>

								<td>273</td>
								<td>'.$A_sede.'</td>
								<td>'.$aux_codigo_carrera.'</td>
								<td>'.$A_jornada.'</td>
								<td>'.utf8_decode($A_carrera).'</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>'.$aux_codigo_carrera.'</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>'.$A_ingreso_primer_year.'</td>
								<td>'.$A_ingreso_primer_semestre.'</td>
								<td>'.$A_nem.'</td>
								<td>'.$A_ingreso_year.'</td>
								<td>'.$A_ingreso_semestre.'</td>
								<td>'.$semestres_suspencion.'</td>
								<td>'.$proceso_terminal.'</td>
								<td>'.$aux_year_egreso.'</td>
								<td>'.$aux_semestre_egreso.'</td>
								<td bgcolor="#FFFF00">'.$A_situacion.'</td>
								<td bgcolor="#FFFF00">'.$C_condicion.'</td>
								</tr>';
							}
						}
					}
					else
					{
						if(DEBUG){ echo"SIN REGISTROS<br>";}
					}
					//--------------------------------------------------//
				$sqli_main->free();
			}
		}
		else
		{
			//sin id ese año
			if(DEBUG){ echo"UID:0<br>";}
		}
		
		$sqli_main_1->free();
		
		
		//-------------------------------------------------------------------------//
		$conexion_mysqli->close();
	
	echo'</tbody></table>';
//--------------------------------------/
//---------------------------------------------------//
?>