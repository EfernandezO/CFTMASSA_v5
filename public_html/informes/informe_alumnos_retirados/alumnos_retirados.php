<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_alumnos_retirados_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion_2.php"); ?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<title>Alumnos Retirados/Postergados</title>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:45px;
	z-index:1;
	left: 5%;
	top: 198px;
	padding-bottom: 30px;
	margin-bottom: 30px;
}
a:link {
	color: #006699;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #006699;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #006699;
}
-->
</style>
</head>
<?php
	if(DEBUG){ var_dump($_POST);}
	$sede=$_POST["sede"];
	$id_carrera=$_POST["carrera"];


	$year_contrato=$_POST["year"];
	$situacion=$_POST["tipo_alumno"];
?>
<body>
<h1 id="banner">Administrador - informe Alumnos Retirados</h1>
<div id="link"><br />
<a href="index.php" class="button">Volver a Seleccion</a></div>
<div id="apDiv1">
  <div align="center">
    <table width="100%" border="1">
      <thead>
      <tr>
      	<th colspan="15">Alumnos:<?php echo $situacion;?> year <?php echo $year_contrato;?> Sede: <?php echo $sede;?></th>
      </tr>
        <tr>
          <th>N&deg;</th>
          <th>Sede</th>
          <th>Carrera</th>
          <th>Nivel</th>
          <th>ID Alumno</th>
          <th>Rut</th>
          <th>Nombre</th>
          <th>Apellido P</th>
          <th>Apellido M</th>
          <th>Ingreso</th>
          <th>Fecha Retiro</th>
          <th>Motivo</th>
          <th>Observacion</th>
          <th>Aporte BNM</th>
          <th>Aporte BET</th>
        </tr>
      </thead>
      <tbody>
        <?php
if($_POST)
{
	if($year_ingreso!="todos")
	{ $condicion_year_contrato="AND contratos2.ano='$year_contrato'";}
	else
	{ $condicion_year_contrato="";}
	
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	require("../../../funciones/VX.php");
	
	$evento="Revisa informe de alumnos Retirados/Postergados de la sede: $sede id_carrera:$id_carrera";
	REGISTRA_EVENTO($evento);
	
	if($sede=="todas"){$campo_sede="";}
	else{$campo_sede="AND alumno.sede='$sede' ";}
	
	if($id_carrera>0)
	{ $condicion_carrera_R="AND proceso_retiro.id_carrera='$id_carrera'"; $condicion_carrera_P="AND proceso_postergacion.id_carrera='$id_carrera'";}
	else
	{ $condicion_carrera_R=""; $condicion_carrera_P="";}
	
				//-----------------------------------------------------------//
				switch($situacion)
				{
					case"R":
						$array_motivo_retiro=array("1"=>"Dificultades Economicas",
						   "2"=>"No obtener beca ni financiamiento",
						   "3"=>"Excluido por Motivos Diciplinarios",
						   "4"=>"Retiro por aplazamiento del semestre",
						   "5"=>"Excluido por bajo rendimiento academico",
						   "6"=>"No cumplimiento con expectativas academicas",
						   "7"=>"No cumplimiento con expectativas de equipamiento",
						   "8"=>"Erronea eleccion de carrera a estudiar",
						   "9"=>"Cambio a otra institucion",
						   "10"=>"Dificultades familiares",
						   "11"=>"Problemas de Salud",
						   "12"=>"Cambio Domicilio personal a otra ciudad",
						   "13"=>"Cambio de ubicacion o condicion Laboral",
						   "14"=>"No se imparte la carrera");

						$contador=0;
						$cons_R="SELECT proceso_retiro.*, alumno.sede, alumno.nivel, alumno.rut, alumno.nombre, alumno.apellido_P, alumno.apellido_M, alumno.ingreso FROM proceso_retiro INNER JOIN alumno ON proceso_retiro.id_alumno=alumno.id WHERE proceso_retiro.year_retiro='$year_contrato' $condicion_carrera_R $campo_sede ORDER by id_carrera, alumno.sede, alumno.apellido_P, alumno.apellido_M";
						$sqli_R=$conexion_mysqli->query($cons_R)or die($conexion_mysqli->error);
						$num_reg=$sqli_R->num_rows;
						if(DEBUG){ echo"--->$cons_R<br>N.: $num_reg<br>";}
						if($num_reg>0)
						{
							while($R=$sqli_R->fetch_assoc())
							{
								$contador++;
								
								$R_sede=$R["sede"];
								$R_nivel=$R["nivel"];
								$R_rut=$R["rut"];
								$R_nombre=$R["nombre"];
								$R_apellido_P=$R["apellido_P"];
								$R_apellido_M=$R["apellido_M"];
								$R_year_ingreso=$R["ingreso"];
								
								$R_id_alumno=$R["id_alumno"];
								$R_id_carrera=$R["id_carrera"];
								$R_semestre=$R["semestre_retiro"];
								$R_year=$R["year_retiro"];
								$R_cod_motivo=$R["motivo"];
								$R_observacion=$R["observacion"];
								$R_fecha_generacion=$R["fecha_generacion"];
								
								//------------------------------------------------------------------------------------------------------------///
								$cons_C="SELECT id, aporte_beca_nuevo_milenio, aporte_beca_excelencia FROM contratos2 WHERE id_alumno='$R_id_alumno' AND id_carrera='$R_id_carrera' ORDER by id DESC LIMIT 1";
								if(DEBUG){ echo"----->$cons_C<br>";}
								$sqli_C=$conexion_mysqli->query($cons_C)or die($conexion_mysqli->error);
									$C=$sqli_C->fetch_assoc();
									$C_id=$C["id"];
									$C_aporte_beca_nuevo_milenio=$C["aporte_beca_nuevo_milenio"];
									$C_aporte_beca_excelencio=$C["aporte_beca_excelencia"];
									if(DEBUG){ echo"--------->id_contrato: $C_id<br>";}
								$sqli_C->free();	
								//-------------------------------------------------------------------------------------------------------------///
							
							echo'<tr>
									  <td>'.$contador.'</td>
									  <td>'.$R_sede.'</td>
									  <td bgcolor="'.COLOR_CARRERA($R_id_carrera).'">'.NOMBRE_CARRERA($R_id_carrera).'</td>
									  <td>'.$R_nivel.'</td>
									  <td>'.$R_id_alumno.'</td>
									  <td>'.$R_rut.'</td>
									  <td>'.$R_nombre.'</td>
									  <td>'.$R_apellido_P.'</td>
									  <td>'.$R_apellido_M.'</td>
									  <td>'.$R_year_ingreso.'</td>
									  <td>'.$R_fecha_generacion.'</td>
									  <td>'.$array_motivo_retiro[$R_cod_motivo].'</td>
									  <td>'.$R_observacion.'</td>
									  <td>'.$C_aporte_beca_nuevo_milenio.'</td>
									  <td>'.$C_aporte_beca_excelencio.'</td>
									</tr>';
							}
							
						}
						$sqli_R->free();
						break;
					case"P":
						$array_motivo_postergacion=array("1"=>"Dificultades Economicas",
						   "2"=>"No obtener beca ni financiamiento",
						   "3"=>"Excluido por Motivos Diciplinarios",
						   "4"=>"Retiro por aplazamiento del semestre",
						   "5"=>"Excluido por bajo rendimiento academico",
						   "6"=>"No cumplimiento con expectativas academicas",
						   "7"=>"No cumplimiento con expectativas de equipamiento",
						   "8"=>"Erronea eleccion de carrera a estudiar",
						   "9"=>"Cambio a otra institucion",
						   "10"=>"Dificultades familiares",
						   "11"=>"Problemas de Salud",
						   "12"=>"Cambio Domicilio personal a otra ciudad",
						   "13"=>"Cambio de ubicacion o condicion Laboral",
						   "14"=>"No se imparte la carrera");

						$contador=0;
						$cons_P="SELECT proceso_postergacion.*, alumno.sede, alumno.nivel, alumno.rut, alumno.nombre, alumno.apellido_P, alumno.apellido_M, alumno.ingreso FROM proceso_postergacion INNER JOIN alumno ON proceso_postergacion.id_alumno=alumno.id WHERE proceso_postergacion.year_postergacion='$year_contrato' $condicion_carrera_P $campo_sede ORDER by alumno.sede, alumno.apellido_P, alumno.apellido_M";
						$sqli_P=$conexion_mysqli->query($cons_P)or die($conexion_mysqli->error);
						$num_reg=$sqli_P->num_rows;
						if(DEBUG){ echo"--->$cons_P<br>N.: $num_reg<br>";}
						if($num_reg>0)
						{
							while($P=$sqli_P->fetch_assoc())
							{
								$contador++;
								
								$P_sede=$P["sede"];
								$P_nivel=$P["nivel"];
								$P_rut=$P["rut"];
								$P_nombre=$P["nombre"];
								$P_apellido_P=$P["apellido_P"];
								$P_apellido_M=$P["apellido_M"];
								$P_year_ingreso=$P["ingreso"];
								
								$P_id_alumno=$P["id_alumno"];
								$P_id_carrera=$P["id_carrera"];
								$P_semestre=$P["semestre_retiro"];
								$P_year=$P["year_retiro"];
								$P_cod_motivo=$P["motivo"];
								$P_observacion=$P["observacion"];
								$P_fecha_generacion=$P["fecha_generacion"];
								
								//------------------------------------------------------------------------------------------------------------///
								$cons_C="SELECT id, aporte_beca_nuevo_milenio, aporte_beca_excelencia FROM contratos2 WHERE id_alumno='$P_id_alumno' AND id_carrera='$P_id_carrera' ORDER by id DESC LIMIT 1";
								if(DEBUG){ echo"----->$cons_C<br>";}
								$sqli_C=$conexion_mysqli->query($cons_C)or die($conexion_mysqli->error);
									$C=$sqli_C->fetch_assoc();
									$C_id=$C["id"];
									$C_aporte_beca_nuevo_milenio=$C["aporte_beca_nuevo_milenio"];
									$C_aporte_beca_excelencio=$C["aporte_beca_excelencia"];
									if(DEBUG){ echo"--------->id_contrato: $C_id<br>";}
								$sqli_C->free();	
								//-------------------------------------------------------------------------------------------------------------///
							
							echo'<tr>
									  <td>'.$contador.'</td>
									  <td>'.$P_sede.'</td>
									  <td bgcolor="'.COLOR_CARRERA($P_id_carrera).'">'.NOMBRE_CARRERA($P_id_carrera).'</td>
									  <td>'.$P_nivel.'</td>
									  <td>'.$P_id_alumno.'</td>
									  <td>'.$P_rut.'</td>
									  <td>'.$P_nombre.'</td>
									  <td>'.$P_apellido_P.'</td>
									  <td>'.$P_apellido_M.'</td>
									  <td>'.$P_year_ingreso.'</td>
									  <td>'.$P_fecha_generacion.'</td>
									  <td>'.$array_motivo_postergacion[$P_cod_motivo].'</td>
									  <td>'.$P_observacion.'</td>
									  <td>'.$C_aporte_beca_nuevo_milenio.'</td>
									  <td>'.$C_aporte_beca_excelencio.'</td>
									</tr>';
							}
							
						}
						$sqli_P->free();
						break;
				}
				//------------------------------------------------------------------//	
				
				
					
				
			}
		
		else
		{ echo'<tr><td colspan="15">Sin Registros Encontrados... :(</td></tr>'; }
	mysql_free_result($sql);	
	@mysql_close($conexion);
	$conexion_mysqli->close();


?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="15">(<?php echo $num_reg;?>) Alumnos Retirados Encontrados </td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
</body>
</html>