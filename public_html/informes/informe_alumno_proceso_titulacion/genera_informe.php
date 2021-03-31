<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG",false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_alumnos_proceso_titulacion_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<?php include("../../../funciones/codificacion.php");?>
<title>Procesos de Titulacion</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:57px;
	z-index:1;
	left: 5%;
	top: 169px;
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
if($_POST)
{
		require("../../../funciones/conexion_v2.php");
		require("../../../funciones/funcion.php");
		require("../../../funciones/funciones_sistema.php");
	$continuar=true;
	if(DEBUG){var_dump($_POST);}
	$sede=$_POST["sede"];
	$year_ingreso=$_POST["year_ingreso"];
	$year_emision_titulo=$_POST["year_emision_titulo"];
	
	$year_titulacion=$_POST["year_titulacion"];
	
	$fecha_ini="$year_emision_titulo-01-01";
	$fecha_fin="$year_emision_titulo-12-31";
	$id_carrera=$_POST["carrera"];
	$opcion=$_POST["opcion"];
	$tipo_informe=$_POST["tipo_informe"];
	
	switch($opcion)
	{
		case"todos":
			$opcion_label="(todos)";
			break;
		case"con_proceso":
			$opcion_label="(proceso titulacion)";
			break;
	}
	$nombre_carrera=NOMBRE_CARRERA($id_carrera);
	$msj="Carrera: $nombre_carrera<br>Año ingreso: $year_ingreso Año titulacion(acta):$year_titulacion - Sede: $sede ";
	include("../../../funciones/VX.php");
	$evento="informe alumno con proceso de titulacion sede: $sede year_titulo:$year_titulacion year_ingreso:$year_ingreso id_carrera: $id_carrera";
	REGISTRA_EVENTO($evento);
}
else
{$continuar=false;}
?>
<body>
<h1 id="banner">Administrador - informe Alumnos (Proceso Titulaci&oacute;n)</h1>
<div id="link"><br />
<a href="index.php" class="button">Volver a Seleccion</a></div>

<div id="apDiv1">
  <table width="100%" border="1">
  <?php
  switch($tipo_informe)
  {
	  case"datos_proceso_titulacion":
  ?>
  <thead>
  <tr>
  	<td colspan="14"><div align="center"><?php echo $msj;?></div>  	  </td>
  </tr>
    <tr>
   
      <th>N&deg;</th>
      <th>Sede</th>
      <th>Ingreso</th>
      <th>Nombre</th>
      <th>Apellido P</th>
      <th>Apellido M</th>
      <th>Run</th>
      <th>fecha Nacimiento</th>
      <th>Sexo</th>
      <th>Nacionalidad</th>
      <th>Situacion</th>
      <th>Denominacion del Titulo</th>
      <th>Fecha Emision de Titulo</th>
      <th>A&ntilde;o Acta</th>
      <th>Verificacion 1 (verifica año ingreso - titulacion)</th>
    </tr>
    </thead>
    <?php
    		break;
		case"datos_contacto":?>
    <thead>
  <tr>
  	<td colspan="9"><div align="center"><?php echo $msj;?></div>  	  </td>
  </tr>
    <tr>
      <th>N&deg;</th>
      <th>Sede</th>
      <th>Nombre</th>
      <th>Apellido P</th>
      <th>Apellido M</th>
      <th>Run</th>
      <th>Telefono</th>
      <th>Email</th>
      <th>Denominacion del Titulo</th>
      <th>A&ntilde;o Acta</th>
    </tr>
    </thead>     

    <?php
		break;
  }
    ?>
    <tbody>
  <?php
  	if($continuar)
	{
	
		$hay_condiciones=false;
		
		if($year_ingreso!="0")
		{ $condicion_ingreso="AND ingreso='$year_ingreso'"; $hay_condiciones=true;}
		else{ $condicion_ingreso="";}
		
		if($year_emision_titulo!="0")
		{ $condicion_e_titulo="AND titulo_fecha_emision BETWEEN '$fecha_ini' AND '$fecha_fin'";  $hay_condiciones=true;}
		else{ $condicion_e_titulo="";}
		
		if($year_titulacion!="0")
		{ $condicion_titulacion="AND proceso_titulacion.year_titulo='$year_titulacion'";  $hay_condiciones=true;}
		else{ $condicion_titulacion="";}
		
		
		if($id_carrera!="0"){ $condicion_carrera="AND alumno.id_carrera='$id_carrera'";  $hay_condiciones=true;}
		else{ $condicion_carrera="";}
		
		if($sede!="0"){ $condicion_sede="AND alumno.sede='$sede'";  $hay_condiciones=true;}
		else{ $condicion_sede="";}
		
		if($hay_condiciones){ $condicionador="WHERE 1=1 ";}
		else{ $condicionador="";}
	
		switch($opcion)
		{
			case"todos":
				$cons="SELECT alumno.*, proceso_titulacion.* FROM alumno LEFT JOIN proceso_titulacion ON alumno.id = proceso_titulacion.id_alumno WHERE alumno.sede='$sede' $condicion_carrera $condicion_ingreso AND situacion IN('EG', 'T') ORDER by alumno.carrera, proceso_titulacion.year_titulo, alumno.apellido_P, alumno.apellido_M";
			break;
			case"con_proceso":
				$cons="SELECT alumno.*, proceso_titulacion.* FROM proceso_titulacion INNER JOIN alumno ON proceso_titulacion.id_alumno = alumno.id  $condicionador $condicion_sede $condicion_carrera  $condicion_ingreso $condicion_e_titulo $condicion_titulacion ORDER by  alumno.carrera , proceso_titulacion.year_titulo, alumno.apellido_P, alumno.apellido_M";
				break;
		}		
			
			
		
			$sql=mysql_query($cons)or die(mysql_error());
			$num_reg=mysql_num_rows($sql);
				if(DEBUG){ echo"<br>-->$cons<br>Num REG: $num_reg<br>";}
			if($num_reg>0)
			{
				$aux=0;
				while($D=mysql_fetch_assoc($sql))
				{
					$aux++;
					$id_alumno=$D["id"];
					$rut=$D["rut"];
					$nombre=$D["nombre"];
					$apellido_P=$D["apellido_P"];
					$apellido_M=$D["apellido_M"];
					$fono=$D["fono"];
					$email=$D["email"];
					$sexo=$D["sexo"];
					$situacion_academica=$D["situacion"];
					$nacionalidad=$D["nacionalidad"];
					$carrera_alumno=$D["carrera"];
					$fecha_nacimiento=$D["fnac"];
					$titulo_fecha_emision=$D["titulo_fecha_emision"];
					$nombre_titulo=$D["nombre_titulo"];
					$year_titulacion_alumno=$D["year_titulo"];
					$A_year_ingreso=$D["ingreso"];
					$A_sede=$D["sede"];
					
					
					if($A_year_ingreso>=$year_titulacion_alumno){ $verificacion_1="error"; $color_1='#F00';}
					else{ $verificacion_1="ok"; $color_1='#0F0';}
					
					if(empty($titulo_fecha_emision))
					{ $titulo_fecha_emision_label="---";}
					else
					{ $titulo_fecha_emision_label=fecha_format($titulo_fecha_emision);}
				
					switch($tipo_informe)	
					{
						case"datos_proceso_titulacion":
							echo'<tr>
								<td><div align="center">'.$aux.'</div></td>	
								<td><div align="center">'.$A_sede.'</div></td>
								<td><div align="center">'.$A_year_ingreso.'</div></td>
								<td><div align="center">'.$nombre.'</div></td>
								<td><div align="center">'.$apellido_P.'</div></td>
								<td><div align="center">'.$apellido_M.'</div></td>
								<td><div align="center">'.$rut.'</div></td>
								<td><div align="center">'.fecha_format($fecha_nacimiento).'</div></td>
								<td><div align="center">'.$sexo.'</div></td>
								<td><div align="center">'.$nacionalidad.'</div></td>
								<td><div align="center">'.$situacion_academica.'</div></td>
								<td><div align="center">'.$nombre_titulo.'</div></td>
								<td><div align="center">'.$titulo_fecha_emision_label.'</div></td>
								<td><div align="center">'.$year_titulacion_alumno.'</div></td>
								<td bgcolor="'.$color_1.'"><div align="center">'.$verificacion_1.'</div></td>
								</tr>';
								break;
						case"datos_contacto":
								echo'<tr>
								<td><div align="center">'.$aux.'</div></td>	
								<td><div align="center">'.$A_sede.'</div></td>
								<td><div align="center">'.$nombre.'</div></td>
								<td><div align="center">'.$apellido_P.'</div></td>
								<td><div align="center">'.$apellido_M.'</div></td>
								<td><div align="center">'.$rut.'</div></td>
								<td><div align="center">'.$fono.'</div></td>
								<td><div align="center">'.$email.'</div></td>
								<td><div align="center">'.$nombre_titulo.'</div></td>
								<td><div align="center">'.$year_titulacion_alumno.'</div></td>
								</tr>';
							break;		
					}
				}
			}
			else
			{
				echo'<tr><td colspan="12">Sin Alumnos encontrados con proceso de titulacion registrado...</td></tr>';
			}
			 include("../../../funciones/VX.php");
			$evento="VE INFORME ALUMNO PROCESO TITULACION id_carrera:($id_carrera) year ingreso($year_ingreso)";
			REGISTRA_EVENTO($evento);
		mysql_free_result($sql);	
		
	}
	@mysql_close($conexion);
	$conexion_mysqli->close();
?>
  </tbody>
  <tfoot>
  	<tr>
    	<td colspan="2">Alumnos Encontrados</td>
        <td colspan="10"><?php echo $num_reg;?></td>
    </tr>
  </tfoot>
  </table>
</div>
</body>
</html>