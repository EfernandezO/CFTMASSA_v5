<?php 
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Matriculas_generadas_X_rango_F_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

$fecha_ini=base64_decode($_GET["fecha_ini"]);
	$fecha_fin=base64_decode($_GET["fecha_fin"]);
	
	$sede=base64_decode($_GET["sede"]);
	$nivel=base64_decode($_GET["nivel"]);
	$year_ingreso=base64_decode($_GET["year_ingreso"]);
	$msj="Matriculas Generadas entre ($fecha_ini  y el $fecha_fin) en $sede<br /> Alumnos de Nivel: $nivel<br /> Año Ingreso: $year_ingreso";

	$nombre_archivo="matriculados_[$fecha_ini-$fecha_fin]_nivel[$nivel]_ingreso[$year_ingreso]_$sede".rand(111,999);

	if(!DEBUG){header('Content-type: application/vnd.ms-excel');
			header("Content-Disposition: attachment; filename=$nombre_archivo.xls");
			header("Pragma: no-cache");
			header("Expires: 0");}

$tabla='
  <table border="1" >
    <thead>
    <tr>
     	<th>ID Contrato</th>
      		<th>ID Alumno</th>
            <th>Sexo</th>
			<th>Rut</th>
      		<th>Nombre</th>
			<th>Apellido P</th>
            <th>Apellido M</th>
      		<th>Carrera</th>
	 	 <th>Jornada</th>
      		<th>Ingreso</th>
      		<th>Nivel</th>
            <th>Total a pagar</th>
      		<th>Matricula a Pagar</th>
      		<th>Txt Beca u otro</th>
			<th>Cantidad Desc.</th>
			<th>% Desc.</th>
            <th>Vigencia</th>
            <th>Condici&oacute;n Contrato</th>
    </tr>
    </thead>
    <tbody>';
if($_GET)
{
	if($nivel=="Todos")
	{
		$condicion_nivel="";
	}
	else
	{
		$condicion_nivel="alumno.nivel='$nivel' AND";
	}
	
	if($year_ingreso=="Todos")
	{
		$condicion_ingreso="";
	}
	else
	{
		$condicion_ingreso="AND alumno.ingreso='$year_ingreso'";
	}
	if($sede=="todas")
	{
		$condicion_sede="";
	}
	else
	{
		$condicion_sede="contratos2.sede='$sede' AND ";
	}
		$consC="SELECT contratos2.id, contratos2.id_alumno, contratos2.sede,  contratos2.matricula_a_pagar, contratos2.txt_beca, contratos2.cantidad_beca, contratos2.porcentaje_beca, contratos2.total, contratos2.vigencia, contratos2.condicion, alumno.nivel, alumno.rut, alumno.nombre, alumno.apellido_P, alumno.apellido_M, alumno.carrera, alumno.jornada, alumno.ingreso, alumno.sexo FROM contratos2 INNER JOIN alumno ON contratos2.id_alumno = alumno.id WHERE $condicion_nivel $condicion_sede contratos2.condicion IN('ok', 'inactivo', 'retiro') $condicion_ingreso AND contratos2.fecha_generacion BETWEEN '$fecha_ini' AND '$fecha_fin' ORDER by carrera, nivel, apellido_P, apellido_M";
	
	if(DEBUG){ echo"$consC<br>";}
	
	include("../../../funciones/conexion.php");
	$sql=mysql_query($consC)or die("1 ".mysql_error());
	$num_matriculados=mysql_num_rows($sql);
	if($num_matriculados>0)
	{
		while($A=mysql_fetch_array($sql))
		{
			$id_contrato=$A[0];
			$id_alumno=$A[1];
			$rut=$A["rut"];
			$nombre=$A["nombre"];
			$apellido_P=$A["apellido_P"];
			$apellido_M=$A["apellido_M"];
			$carrera=$A["carrera"];
			$jornada=$A["jornada"];
			$ingreso=$A["ingreso"];
			$sexo_alumno=$A["sexo"];
			
			$matricula_a_pagar=$A["matricula_a_pagar"];
			$txt_beca=$A["txt_beca"];
			$cantidad_desc=$A["cantidad_beca"];
			$porcentaje_dec=$A["porcentaje_beca"];
			$total_contrato=$A["total"];
			$vigencia_contrato=$A["vigencia"];
			$contrato_condicion=$A["condicion"];
			
			$nivel_alumno=$A["nivel"];
			
			$SUMA_X_SEXO[$sexo_alumno]+=1;
			
			$tabla.='<tr>
      		<td>'.$id_contrato.'</td>
			<td>'.$id_alumno.'</td>
			<td>'.$sexo_alumno.'</td>
			<td>'.$rut.'</td>
      		<td>'.$nombre.'</td>
			<td>'.$apellido_P.'</td>
			<td>'.$apellido_M.'</td>
      		<td>'.$carrera.'</td>
			<td>'.$jornada.'</td>
      		<td>'.$ingreso.'</td>
			<td>'.$nivel_alumno.'</td>
			<td>'.$total_contrato.'</td>
			<td>'.$matricula_a_pagar.'</td>
			<td>'.$txt_beca.'</td>
			<td>'.$cantidad_desc.'</td>
			<td>'.$porcentaje_dec.'</td>
			<td>'.$vigencia_contrato.'</td>
			<td>'.$contrato_condicion.'</td>
    		</tr>';
		}	
	}
	else
	{
		$tabla.=' <tr>
      		<td>&nbsp;</td>
    		</tr>';
	}
	mysql_free_result($sql);
	mysql_close($conexion);
		
}
else
{
	$tabla.="Sin Datos...";
}

$tabla.='
</tbody>
<tfoot>
<tr>
<td colspan="18"><span class="Estilo5">('.$num_matriculados.') Alumnos con Contrato</span></td>
</tr>
<tr>
<td colspan="18">['.$SUMA_X_SEXO["F"].'] Mujeres</td>
</tr>
<tr>
<td colspan="18">['.$SUMA_X_SEXO["M"].'] Hombres</td>
</tr>
<tr>
<td colspan="18">['.$SUMA_X_SEXO[""].'] N/I</td>
</tr>
</tfoot>
  </table>';
  
  echo $tabla;
?>