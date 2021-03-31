<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("revision_mensual_honorario_Docente");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//	
	
if(isset($_GET["H_id"]))
{
	$id_honorario=base64_decode($_GET["H_id"]);
	if(is_numeric($id_honorario)){ $continuar=true;}
	else{$continuar=false;}
}
else
{ $continuar=false;}
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../../funciones/codificacion.php");?>
<title>Honorario Docente Detalle</title>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 269px;
}
#apDiv2 {
	position:absolute;
	width:50%;
	height:115px;
	z-index:2;
	left: 5%;
	top: 59px;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Revisi&oacute;n Honorario Docente</h1>

<?php if($continuar)
{
	require("../../../../../funciones/conexion_v2.php");
	require("../../../../../funciones/funciones_sistema.php");
	$cons="SELECT * FROM honorario_docente WHERE id_honorario='$id_honorario'";
	$sqli=$conexion_mysqli->query($cons);
	$D=$sqli->fetch_assoc();
		$H_mes=$D["mes_generacion"];
		$H_year=$D["year"];
		$H_semestre=$D["semestre"];
		$H_year_generacion=$D["year_generacion"];
		$H_id_funcionario=$D["id_funcionario"];
		$H_sede=$D["sede"];
		$H_total=$D["total"];
		$cons_A="SELECT * FROM personal WHERE id='$H_id_funcionario' LIMIT 1";
	$sql_A=mysql_query($cons_A)or die(mysql_error());
	$DA=mysql_fetch_assoc($sql_A);
		$H_rut=$DA["rut"];
		$H_nombre=$DA["nombre"];
		$H_apellido=$DA["apellido"];
	mysql_free_result($sql_A);	
	$sqli->free();	
	
	//--------------------------------------------//
		include("../../../../../funciones/VX.php");
		$evento="Revisa para Aprobacion de contabilidad, Honorario Docente individual RUT:$H_rut $H_sede [$H_mes - $H_year]";
		@REGISTRA_EVENTO($evento);
		//----------------------------------------------//
	?>
<div id="apDiv2">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="2">Honorario Funcionario</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>Sede</td>
      <td><?php echo $H_sede;?></td>
    </tr>
    <tr>
      <td width="21%">Periodo</td>
      <td width="79%"><?php echo"[$H_semestre - $H_year]";?></td>
    </tr>
     <tr>
      <td width="21%">Generado en</td>
      <td width="79%"><?php echo"[mes: $H_mes - año: $H_year_generacion]";?></td>
    </tr>
    <tr>
      <td>Rut</td>
      <td><?php echo $H_rut;?></td>
    </tr>
    <tr>
      <td>Nombre</td>
      <td><?php echo "$H_nombre $H_apellido";?></td>
    </tr>
    <tr>
      <td>Total</td>
      <td><?php echo number_format($H_total,0,",",".");?></td>
    </tr>
    </tbody>
  </table>
</div>
<div id="apDiv1">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="9">Detalle Honorario</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>N</td>
      <td>Sede</td>
      <td>Carrera</td>
      <td>Asignatura</td>
      <td>Jornada</td>
      <td>Grupo</td>
      <td>Cuota</td>
      <td>Glosa</td>
      <td>Valor</td>
    </tr>
    <?php
    	$cons_HD="SELECT * FROM honorario_docente_detalle WHERE id_honorario='$id_honorario'";
		$sqli_hd=$conexion_mysqli->query($cons_HD);
		$num_registros=$sqli_hd->num_rows;
		if($num_registros>0)
		{
			$aux=0;
			$SUMA_TOTAL=0;
			while($HD=$sqli_hd->fetch_assoc())
			{
				$aux++;
				$HD_id=$HD["id"];
				$HD_id_carrera=$HD["id_carrera"];
				$HD_cod_asignatura=$HD["cod_asignatura"];
				$HD_jornada=$HD["jornada"];
				$HD_grupo=$HD["grupo"];
				$HD_cuota=$HD["cuota"];
				$HD_sede=$HD["sede"];
				
				$HD_cargo=$HD["cargo"];
				$HD_abono=$HD["abono"];
				$HD_valor_hora=$HD["valor_hora"];
				
				$HD_glosa_cargo=$HD["glosa_cargo"];
				$HD_glosa_abono=$HD["glosa_abono"];
				
				$HD_total_a_pagar=$HD["total_a_pagar"];
				$SUMA_TOTAL+=$HD_total_a_pagar;
				
				$HD_fecha_generacion=$HD["fecha_generacion"];
				$HD_cod_user=$HD["cod_user"];
			
				list($HD_ramo,$HD_nivel)=NOMBRE_ASIGNACION($HD_id_carrera, $HD_cod_asignatura);
				$HD_carrera=NOMBRE_CARRERA($HD_id_carrera);
		
				echo'<tr>
						<td>'.$aux.'</td>
						<td>'.$HD_sede.'</td>
						<td>['.$HD_id_carrera.'] '.$HD_carrera.'</td>
						<td>['.$HD_cod_asignatura.'] '.$HD_ramo.'</td>
						<td>'.$HD_jornada.'</td>
						<td>'.$HD_grupo.'</td>
						<td>'.$HD_cuota.'</td>
						<td></td>
						<td>'.number_format($HD_total_a_pagar,0,",",".").'</td>
					</tr>';
			}
			echo'<tr>
					<td colspan="7">Total<td>
					<td>'.$SUMA_TOTAL.'</td>
				</tr>';
		}
		else
		{ echo'<tr><td colspan="9">Sin Registros...</td></tr>';}
		$sqli_hd->free();
	?>
    </tbody>
  </table>
</div>
<?php $conexion_mysqli->close(); mysql_close($conexion);}else{ echo"Datos incorrectos :(";}?>  
  


</body>
</html>