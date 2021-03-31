<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("ver_asignaciones_general");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_GET)
{
	$continuar=true;
	$sede=$_GET["sede"];
	$year=$_GET["year"];
	$semestre=$_GET["semestre"];
	$ordenar=$_GET["ordenar"];
}
else
{ $continuar=false;}
$tabla='<table width="100%" border="1" align="center">
      <thead>
        <tr>
          <th colspan="8" bgcolor="#CCFF00">Docentes con Asignacines '.$sede.' Periodo ['.$semestre.' - '.$year.']</th>
        </tr>
      </thead>
      <tr>
      	<td>N.</td>
      	<td>Rut</td>
        <td>Nombre</td>
		<td>Apellidos</td>
		<td>Email</td>
		<td>Email Personal</td>
		<td>Fono</td>
        <td>$. Hrs</td>
      </tr>
      <tbody>';

      if($continuar)
	  {
		   switch($ordenar)
		  {
			  	case"funcionario":
					$ordenar_por="personal.apellido_P, personal.apellido_M";
			  		break;
				case"curso":
					$ordenar_por="toma_ramo_docente.id_carrera, mallas.nivel, toma_ramo_docente.jornada, toma_ramo_docente.grupo";
					break;
				default:
					$ordenar_por="personal.apellido_P, personal.apellido_M";	
		  }
		  require("../../../../../funciones/conexion_v2.php");
		  require("../../../../../funciones/funciones_sistema.php");
		  
		  $sede=mysqli_real_escape_string($conexion_mysqli,$sede);
		  $semestre=mysqli_real_escape_string($conexion_mysqli, $semestre);
		  $year=mysqli_real_escape_string($conexion_mysqli, $year);
		  
	  		$cons="SELECT DISTINCT(id_funcionario) FROM toma_ramo_docente INNER JOIN personal ON toma_ramo_docente.id_funcionario=personal.id  WHERE toma_ramo_docente.sede='$sede' AND toma_ramo_docente.semestre='$semestre' AND toma_ramo_docente.year='$year' ORDER by personal.apellido_P, personal.apellido_M";
			if(DEBUG){echo"->$cons<br>";}
			$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
			$num_registros=$sqli->num_rows;
			if(DEBUG){ echo"Numero Registros: $num_registros<br>";}
			if($num_registros>0)
			{
				$contador=0;
				while($AS=$sqli->fetch_assoc())
				{
					$contador++;
					
					$AS_id_funcionario=$AS["id_funcionario"];
						//Datos funcionarios
					$cons_DF="SELECT * FROM personal WHERE id='$AS_id_funcionario' LIMIT 1";
					$sqli_DF=$conexion_mysqli->query($cons_DF)or die($conexion_mysqli->error);
						$DF=$sqli_DF->fetch_assoc();
						$F_rut=$DF["rut"];
						$F_nombre=$DF["nombre"];
						$F_apellido=$DF["apellido_P"]." ".$DF["apellido_M"];
						$F_email=$DF["email"];
						$F_email_personal=$DF["email_personal"];
						$F_fono=$DF["fono"];
					$sqli_DF->free();
					///busco max valor hora
					$valor_hora_max=0;
					$cons_VH="SELECT MAX(valor_hora) FROM toma_ramo_docente WHERE id_funcionario='$AS_id_funcionario' AND semestre='$semestre' AND year='$year' AND sede='$sede'";
					$sqli_VH=$conexion_mysqli->query($cons_VH)or die($conexion_mysqli->error);
					$DVH=$sqli_VH->fetch_row();
						$valor_hora_max=$DVH[0];
					$sqli_VH->free();
					
					//--------------------------------------------------------------------//	
					
					if((empty($F_email))or($F_email=="Sin Registro")){ $color_1='#FF0000';}
					else{ $color_1='';}
					
					if((empty($F_email_personal))or($F_email_personal=="Sin Registro")){ $color_2='#FF0000';}
					else{ $color_2='';}
					
					if((empty($F_fono))or($F_fono=="Sin Registro")){ $color_3='#FF0000';}
					else{ $color_3='';}
					
					
					
					
					$tabla.='<tr>
								<td>'.$contador.'</td>
								<td>'.$F_rut.'</td>
								<td>'.utf8_decode($F_nombre).'</td>
								<td>'.utf8_decode($F_apellido).'</td>
								<td bgcolor="'.$color_1.'">'.$F_email.'</td>
								<td bgcolor="'.$color_2.'">'.$F_email_personal.'</td>
								<td bgcolor="'.$color_3.'">'.$F_fono.'</td>
								<td>'.number_format($valor_hora_max,0,"","").'</td>
							</tr>';
									
					//------------------------------------------------------/
					//------------------------------------------------------/
				
					//--------------------------------------------------------------------//	
				
					
				}
			
			}
			else
			{$tabla.='<tr><td colspan="18">Sin Asignaciones Creadas</td></tr>';}
			$sqli->free();
			
		  $conexion_mysqli->close();
	  }
	  else
	  { $tabla.='<tr><td colspan="8">Sin datos</td></tr>';}
	
      
      $tabla.='</tbody>
    </table>';
	
	if(!DEBUG)
		{
			header('Content-type: application/vnd.ms-excel');
			header("Content-Disposition: attachment; filename=Docentes_".$sede."_[".$semestre."_".$year."].xls");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
		echo $tabla;
?>