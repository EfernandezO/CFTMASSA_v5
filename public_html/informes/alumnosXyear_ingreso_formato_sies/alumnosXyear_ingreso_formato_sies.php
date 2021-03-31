<?php 
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="inspeccion";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
	$sede=$_POST["fsede"];
	$year_ingreso=$_POST["year"];
//var_dump($_POST);
//////////////////////////
define("DEBUG", false);
set_time_limit(90);
if(!DEBUG)
	{
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=ALUMNOS_ingreso_".$year_ingreso."_FORMATO_SIES_".$sede.".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
echo'
  <table border="1">
<thead>
	<th>N.</th>
	<th>Run</th>
    <th>DV</th>
    <th>Apellido Paterno</th>
    <th>Apellido Materno</th>
    <th>Nombres</th>
    <th>Sexo</th>
    <th>Fecha Nacimiento</th>
    <th>Nacionalidad</th>
    <th>Codigo Carrera</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
    <th>Año Ingreso 1°año</th>
    <th>Semestre Ingreso 1°Año</th>
    <th>Año Ingreso</th>
    <th>Semestre Ingreso </th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
</thead>
<tbody>';
	include("../../../funciones/conexion.php");
	include("../../../funciones/funcion.php");
		
		
		
		
		echo"<strong>Sede:</strong> $sede <strong>Año de Ingreso:</strong> $year_ingreso";
		if(DEBUG){ var_export($_POST);}
		$hay_condiciones=false;
				
		if($sede!="todas")
		{
			 $condicion_sede="alumno.sede='$sede'";
			 $hay_condiciones=true;
		}
		else
		{ $condicion_sede="";}
		
		if($year=="todos")
		{ $ingreso_condicion='';}
		else{ 
			if($hay_condicion)
			{ $ingreso_condicion="alumno.ingreso='$year_ingreso'";}
			else{ 
			$year_ingreso_menos_1=($year_ingreso-1);
			//$ingreso_condicion="AND (alumno.ingreso='$year_ingreso')OR (alumno.ingreso='$year_ingreso_menos_1' AND alumno.nivel='3')";
			$ingreso_condicion="AND alumno.ingreso='$year_ingreso'";
			
			}
		}
		
		$cons_main_1="SELECT DISTINCT(id) FROM alumno WHERE  alumno.sede='$sede' $ingreso_condicion ORDER by alumno.carrera, alumno.apellido_P, apellido_M";
		
		if(DEBUG){ echo"<br><br><b>$cons_main_1</b><br>";}
		$sql_main_1=mysql_query($cons_main_1)or die("MAIN 1".mysql_error());
		$num_reg_M=mysql_num_rows($sql_main_1);
		if(DEBUG){ echo"NUM GLOBAL: $num_reg_M<br>";}
		
		if($num_reg_M>0)
		{
			$primera_vuelta=true;
			while($DID=mysql_fetch_row($sql_main_1))
			{
				$id_alumno=$DID[0];
				if(DEBUG){ echo"UID:$id_alumno<br>";}
					//-------------------------------------------------//
					$cons_main="SELECT alumno.* FROM alumno WHERE id='$id_alumno' LIMIT 1";
					
					if(DEBUG){ echo"<br>$cons_main<br>";}
					$sql_main=mysql_query($cons_main)or die("MAIN".mysql_error());
					$num_registros=mysql_num_rows($sql_main);
					if(DEBUG){ echo"Numero Registros $num_registros<br>";}
					
					if($num_registros>0)
					{
						
						while($DB=mysql_fetch_assoc($sql_main))
						{
							$A_id=$DB["id"];
							$A_nombre=$DB["nombre"];
							$A_apellido_P=$DB["apellido_P"];
							$A_apellido_M=$DB["apellido_M"];
							$A_rut=$DB["rut"];		
							$array_rut=explode("-",$A_rut);
								$aux_rut_sin_guion=$array_rut[0];
								$aux_dv=$array_rut[1];
									
							$A_carrera=$DB["carrera"];
							$A_sexo=$DB["sexo"];
							$A_fecha_nac=$DB["fnac"];
							
							//////////////////
							//fecha nacimiento arreglo
							$array_fecha_nac=explode("-",$A_fecha_nac);
							$aux_year_nacimiento=$array_fecha_nac[0];
							if(($A_fecha_nac=="0000-00-00")or($aux_year_nacimiento==date("Y")))
							{
								$A_fecha_nac_label="---";
							}
							else
							{ $A_fecha_nac_label=fecha_format($A_fecha_nac);}
							
							
							$A_nacionalidad="chilena";
							$A_sede=$DB["sede"];
							$A_jornada=$DB["jornada"];
								
							$aux_codigo_carrera=CODIGO_CARRERA($A_sede, $A_jornada, $A_carrera);
							$A_ingreso_primer_year=$DB["ingreso"];
							$A_ingreso_primer_semestre=1;
								$A_ingreso_year=$A_ingreso_primer_year;
								$A_ingreso_semestre=1;
								$A_nivel=$DB["nivel"];
							$C_condicion="XXX";
								
								if($A_nivel>4)
								{
									if(NIVEL_APROVADO($id_alumno,"IV"))
									{
										 $proceso_terminal=1;
										 $year_IV_nivel=MAX_YEAR_NIVEL($id_alumno,"IV");
									}
									else
									{ 
										$proceso_terminal=2;
										$year_IV_nivel="";
									}
									
								}
								else
								{ 
									$proceso_terminal=2;
									$year_IV_nivel="";
								}
								
								
								$aux_semestre_egreso=2;
							
							if(DEBUG){ echo"[$A_id - $A_rut ]->?<br><br>";}
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
								{ $contador=1;}
								$carrera_old=$A_carrera;
								
								echo'<tr>
								<td>'.$contador.'</td>
								<td>'.$aux_rut_sin_guion.'</td>
								<td>'.$aux_dv.'</td>
								<td>'.$A_apellido_P.'</td>
								<td>'.$A_apellido_M.'</td>
								<td>'.$A_nombre.'</td>
								<td>'.$A_sexo.'</td>
								<td>'.$A_fecha_nac_label.'</td>
								<td>'.$A_nacionalidad.'</td>
								<td>'.$aux_codigo_carrera.'</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>'.$A_ingreso_primer_year.'</td>
								<td>'.$A_ingreso_primer_semestre.'</td>
								<td>'.$A_ingreso_year.'</td>
								<td>'.$A_ingreso_semestre.'</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								</tr>';
							}
						}
					}
					else
					{
						if(DEBUG){ echo"SIN REGISTROS<br>";}
					}
					//--------------------------------------------------//
					mysql_free_result($sql_main);
			}
		}
		else
		{
			//sin id ese año
			if(DEBUG){ echo"UID:0<br>";}
		}
		
		mysql_free_result($sql_main_1);
		
		
		//-------------------------------------------------------------------------//
		
	mysql_close($conexion);
	echo'</tbody></table>';
//--------------------------------------//
function CODIGO_CARRERA($sede, $jornada, $carrera)
{
	$codigo_intitucion="I273";
	$version="V1";
	switch($sede)
	{
		case"Talca":
			$codigo_sede="S1";
			break;
		case"Linares":	
			$codigo_sede="S3";
			break;
	}
	switch($jornada)
	{
		case"D":
			$codigo_jornada="J1";
			break;
		case"V":
			$codigo_jornada="J2";
			break;	
	}
	switch($carrera)
	{
		case"Técnico en Construcción.":
			$codigo_carrera="C5";
			break;
		case"Técnico en Construcción":
			$codigo_carrera="C5";
			break;	
		case"Técnico Construcción":
			$codigo_carrera="C5";
			break;		
		case"Secretariado Ejecutivo Computacional m/RR.PP":
			$codigo_carrera="C3";
			break;	
		case"Secretariado Ejecutivo Computacional m/Jurídica":
			$codigo_carrera="C3";
			break;
		case"Secretariado Ejecutivo Computacional":
			$codigo_carrera="C3";
			break;	
		case"Técnico en Enfermería de Nivel Superior":
			$codigo_carrera="C16";
			break;	
		case"Técnico Financiero Bancario":	
			$codigo_carrera="C13";
			break;
		case"Técnico Jurídico":	
			$codigo_carrera="C12";
			break;
		case"Programacion Computacional":	
			$codigo_carrera="C2";
			break;
		case"Programación Computacional":	
			$codigo_carrera="C2";
			break;	
			
	}
	$codigo_carrera_final=$codigo_intitucion."".$codigo_sede."".$codigo_carrera."".$codigo_jornada."".$version;
	return($codigo_carrera_final);
}
//------------------------------------------------//
function MAX_YEAR_NIVEL($id_alumno, $nivel)
{
	$cons="SELECT MAX(ano) FROM notas WHERE id_alumno='$id_alumno' AND nivel='$nivel'";
	$sql=mysql_query($cons)or die(mysql_error());
	$D=mysql_fetch_row($sql);
	$year_fin_pal_regular=$D[0];
	mysql_free_result($sql);
	return($year_fin_pal_regular);
}
//--------------------------------------------------//
function NIVEL_APROVADO($id_alumno, $nivel)
{
	$cons="SELECT COUNT(ID) FROM notas WHERE NOT ramo='' AND id_alumno='$id_alumno' AND nivel='$nivel' AND nota<'4'";
	$sql=mysql_query($cons)or die(mysql_error());
	$D=mysql_fetch_row($sql);
	$numero_ramos_reprobados=$D[0];
	mysql_free_result($sql);
	if(DEBUG){ echo"$cons<br>Numero de ramos Reprobados en $nivel son:$numero_ramos_reprobados<br>";}
	
	if($numero_ramos_reprobados>0)
	{ $nivel_aprobado=false;}
	else
	{ $nivel_aprobado=true;}
	
	return($nivel_aprobado);
}
?>