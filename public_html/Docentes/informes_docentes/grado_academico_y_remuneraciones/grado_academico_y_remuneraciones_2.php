<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("informe_docente_aca_remu");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

if($_POST)
{
	$semestre=$_POST["semestre"];
	$year=$_POST["year"];
	$sede=$_POST["sede"];
	$continuar=true;
}
else{ $continuar=false;}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>informe | Docente</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:69px;
	z-index:1;
	left: 5%;
	top: 89px;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Informe Docente</h1>
<div id="link"><br />
<a href="index.php" class="button">Volver a Seleccion</a></div>
<div id="apDiv1">
  <table width="80%" align="center">
<thead>
<tr>
	<th colspan="8">Lista Docentes Sede:<?php echo"$sede [$semestre - $year]";?></th>
</tr>
<tr>
	<td>n.</td>
    <td>funcionario</td>
    <td>Sexo</td>
    <td>grado academico</td>
    <td colspan="2">hrs semanales [pedagogicas/cronologicos]</td>
    <td>Hrs Totales</td>
    <td>total $</td>
</tr>
</thead>
<tbody>
<?php
if($continuar)
{
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/funciones_sistema.php");
	$TOTAL_HORAS=0;
	$TOTAL_REMUNERACIONES=0;
	$TOTAL_HORAS_CRONOLOGICAS=0;
	
	if($semestre!="0"){ $condicion_semestre="AND toma_ramo_docente.semestre='$semestre'";}
	else{ $condicion_semestre="";}
	
	if($sede!="0"){ $condicion_sede=" AND toma_ramo_docente.sede='$sede'";}
	else{}
	
	$cons="SELECT DISTINCT(id_funcionario) FROM toma_ramo_docente INNER JOIN personal ON toma_ramo_docente.id_funcionario=personal.id WHERE toma_ramo_docente.year='$year' $condicion_semestre $condicion_sede ORDER by personal.apellido_P, personal.nombre";
	if(DEBUG){ echo"---> $cons<br>";}
	
	$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$num_registros=$sqli->num_rows;
	$aux=0;
	if($num_registros>0)
	{
		while($F=$sqli->fetch_row())
		{
			$F_id=$F[0];
			//---------------------------------------------//
			//grado academico
			$cons_GA="SELECT MIN(cod_grado_academico) FROM personal_registro_estudios WHERE id_funcionario='$F_id' AND cod_grado_academico<>'NULL' AND cod_grado_academico<>''";
			$sqli_ga=$conexion_mysqli->query($cons_GA)or die($conexion_mysqli->error);
			$GA=$sqli_ga->fetch_row();
				$F_cod_grado_academico=$GA[0];
			$sqli_ga->free();
			
			//busco sexo del docente
			$cons_S="SELECT sexo FROM personal WHERE id='$F_id' LIMIT 1";
			$sqli_S=$conexion_mysqli->query($cons_S)or die($conexion_mysqli->error);
			$FS=$sqli_S->fetch_assoc();
				$F_sexo=$FS["sexo"];
			$sqli_S->free();	
			//--------------------------------------------//
			//asignaciones docente
			$cons_A="SELECT * FROM toma_ramo_docente WHERE id_funcionario='$F_id' $condicion_semestre AND year='$year' $condicion_sede";
			$sqli_A=$conexion_mysqli->query($cons_A);
			$num_asignacion=$sqli_A->num_rows;
			$suma_horas=0;
			$suma_valor=0;
			if($num_asignacion>0)
			{
				while($A=$sqli_A->fetch_assoc())
				{
					$A_horas=$A["numero_horas"];	
					$A_total=$A["total"];
					$A_valor_hora=$A["valor_hora"];
					
					$suma_horas+=$A_horas;
					$suma_valor+=$A_total;
				}
			}
			else
			{
				if(DEBUG){ echo"Sin Asignaciones<br>";}
			}
			$sqli_A->free();
			$horas_semanales=($suma_horas/18);
			$horas_cronologicas=(($horas_semanales*45)/60);
			$nombre_grado_academico=NOMBRE_GRADO_ACADEMICO($F_cod_grado_academico);
			$nombre_personal=NOMBRE_PERSONAL($F_id);
			$aux++;
			if(DEBUG){ echo"id_funcionario: $F_id<br>Horas semanales: $horas_semanales<br> Grado academico: $F_cod_grado_academico $nombre_grado_academico<br>TOTAL remuneraciones: $suma_valor<br>";}
			//--------------------------------------------------//
			
			echo'<tr>
					<td>'.$aux.'</td>
					<td>'.$nombre_personal.'</td>
					<td>'.$F_sexo.'</td>
					<td>'.$nombre_grado_academico.' ('.$F_cod_grado_academico.')</td>
					<td>'.number_format($horas_semanales,1,",",".").'</td>
					<td>'.number_format($horas_cronologicas,1,",",".").'</td>
					<td>'.$suma_horas.'</td>
					<td align="right">'.$suma_valor.'</td>
				</tr>';
			$TOTAL_HORAS+=$horas_semanales;
			$TOTAL_REMUNERACIONES+=$suma_valor;
			$TOTAL_HORAS_CRONOLOGICAS+=$horas_cronologicas;
		}
	}
	else
	{
		echo'<tr><td colspan="8">Sin Funcionarios</td></tr>';
	}
	$conexion_mysqli->close();
}
?>
<tr>
	<td colspan="3">TOTAL</td>
    <td><?php echo $TOTAL_HORAS;?></td>
    <td><?php echo $TOTAL_HORAS_CRONOLOGICAS;?></td>
    <td align="right" colspan="2"><?php echo number_format($TOTAL_REMUNERACIONES,0,",",".");?></td>
</tr>
</tbody>
</table>
</div>
</body>
</html>