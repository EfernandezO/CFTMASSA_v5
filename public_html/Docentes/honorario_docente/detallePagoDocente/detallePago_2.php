<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("detalle_PagoHonorarioDocente");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_POST)
{
	$continuar=true;
	$sede=$_POST["fsede"];
	$year=$_POST["year"];
	$semestre=$_POST["semestre"];
}
else
{ $continuar=false;}
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>Revision Asignaciones General</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:101px;
	z-index:1;
	left: 5%;
	top: 123px;
}
#apDiv2 {
	position:absolute;
	width:35%;
	height:31px;
	z-index:2;
	left: 35%;
	top: 198px;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Detalle Pago Docente</h1>
<div id="link"><br>
<a href="detallePago_1.php" class="button">Volver a seleccion</a><br /><br />
<a href="detallePago_xls.php?sede=<?php echo $sede;?>&semestre=<?php echo $semestre;?>&year=<?php echo $year;?>" class="button" target="_blank">.xls (sin uso)</a><br />
</div>
<div id="apDiv1">

    <table width="100%" border="1" align="center">
      <thead>
        <tr>
          <th colspan="12">Honorarios  <?php echo "Sede: $sede Periodo [$semestre - $year]";?></th>
        </tr>
      </thead>
      <tr>
      	<td>N.</td>
        <td>Sede</td>
      	<td>Rut</td>
        <td>Funcionario</td>
        <td>id honorario</td>
        <td>Valor Cuota</td>
        <td>Estado Cuota</td>
        <td>Forma pago</td>
        <td>Fecha pago</td>
        <td>Valor Pagado</td>
      </tr>
      <tbody>
      <?php
      if($continuar)
	  {
		  require("../../../../funciones/conexion_v2.php");
		  require("../../../../funciones/funciones_sistema.php");
		  $ordenar_por="personal.apellido_P, personal.apellido_M";	
		
		  
		  $sede=mysqli_real_escape_string($conexion_mysqli, $sede);
		  $semeste=mysqli_real_escape_string($conexion_mysqli, $semestre);
		  $year=mysqli_real_escape_string($conexion_mysqli, $year);
		  
		  
		  if($sede=="todas"){$condicionSede="";}else{$condicionSede="AND honorario_docente.sede='$sede'";}
		  
	  		$cons="SELECT honorario_docente.*, honorario_docente_pagos.fecha_pago, honorario_docente_pagos.forma_pago,  honorario_docente_pagos.valor FROM honorario_docente LEFT JOIN honorario_docente_pagos ON honorario_docente.id_honorario=honorario_docente_pagos.id_honorario INNER JOIN personal ON honorario_docente.id_funcionario = personal.id WHERE 1=1 $condicionSede AND honorario_docente.semestre='$semestre' AND honorario_docente.year='$year' ORDER by personal.apellido_P, apellido_M, honorario_docente.id_honorario";
			if(DEBUG){echo"->$cons<br>";}
			
			$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
			$num_registros=$sqli->num_rows;
			
			if($num_registros>0)
			{
				$contador=0;
				
				while($AS=$sqli->fetch_assoc())
				{
					$contador++;
					$H_idHonorario=$AS["id_honorario"];
					$AS_id_funcionario=$AS["id_funcionario"];
					$AS_total=$AS["total"];
					$AS_mes_generacion=$AS["mes_generacion"];
					$AS_condicion=$AS["estado"];
					$H_fechaPago=$AS["fecha_pago"];
					$H_formaPago=$AS["forma_pago"];
					$H_valorPago=$AS["valor"];
					$H_sede=$AS["sede"];
				
								
					//------------------------------------------------------/
					//Datos funcionarios
					$cons_DF="SELECT * FROM personal WHERE id='$AS_id_funcionario' LIMIT 1";
					$sqli_DF=$conexion_mysqli->query($cons_DF)or die($conexion_mysqli->error);
						$DF=$sqli_DF->fetch_assoc();
						$F_rut=$DF["rut"];
						$F_nombre=$DF["nombre"];
						$F_apellido=$DF["apellido_P"]." ".$DF["apellido_M"];
					$sqli_DF->free();
				
					
					echo'<tr>
							<td>'.$contador.'</td>
							<td>'.$H_sede.'</td>
							<td>'.$F_rut.'</td>
							<td>'.$F_apellido.' '.$F_nombre.'</td>
							<td>'.$H_idHonorario.'</td>
							<td align="right">'.number_format($AS_total,0,"","").'</td>
							<td>'.$AS_condicion.'</td>
							<td>'.$H_formaPago.'</td>
							<td>'.$H_fechaPago.'</td>
							<td>'.$H_valorPago.'</td>
							</tr>';	
					//-----------------------------------------------//		
				
					
				}
				
			
			
				
			}
			else
			{ echo'<tr><td colspan="12">Sin Honorario Generados...</td></tr>';}
			$sqli->free();
			
		  $conexion_mysqli->close();
	  }
	  else
	  { echo'<tr><td colspan="11">Sin datos</td></tr>';}
	  ?>
      </tbody>
    </table><br />
<br />
</div>
</body>
</html>