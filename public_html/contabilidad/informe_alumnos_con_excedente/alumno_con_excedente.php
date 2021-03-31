<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_Alumnos_con_excedente");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	
	
	$sede=$_POST["fsede"];
	$array_carrera=$_POST["carrera"];
	if($array_carrera>0)
	{
		$array_carrera=explode("_",$array_carrera);
		$id_carrera=$array_carrera[0];
		$carrera=$array_carrera[1];
	}
	else
	{
		$id_carrera=0;
		$carrera="todas";
	}
	
	$año_ingreso=$_POST["ano_ingreso"];
	//$semestre_actual=$_POST["semestre_vigencia_contrato"];
	$year_actual=$_POST["year_vigencia_contrato"];
	
	$year_ingreso=$_POST["ano_ingreso"];
	
	$verificar_contrato=true;
	$no_mostrar_retirados=false;
	if(DEBUG){ var_export($_POST);}
	
	
	if($id_carrera>0)
	{$condicion_carrera="AND alumno.id_carrera='$id_carrera'";}
	else
	{$condicion_carrera="";}
	
	
	if($sede=="")
	{$sede="Talca";}
	$condicion=" alumno.sede='$sede' $condicion_carrera AND contratos2.condicion<>'inactivo'";
	
	
	if($año_ingreso!="Todos")
	{$condicion.=" AND alumno.ingreso='$año_ingreso'";}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<title>Listado Alumno con Excedente</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 163px;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Alumno X Curso y Cuotas</h1>
<div id="link"><br />
<a href="index.php" class="button">Volver a Seleccion</a></div>
<div id="apDiv1">
  <table align="center" border="1">
	<thead>
    <tr>
    	<th colspan="9">Listado Alumnos <?php echo"Ultimo Contrato: $year_actual";?><br>Carrera: <?php echo $carrera;?><br>Ingreso: <?php echo $year_ingreso;?>Sede <?php echo $sede;?><br></th>
     </tr> 
     <tr>
     	<td>N</td>
        <td>Rut</td>
        <td>Nombre</td>
        <td>Apellido</td>
        <td>Nivel</td>
        <td>Situacion</td>
        <td>Ingreso</td>
      <!--  <td>Arancel</td>
        <td>N. Cuotas</td>
        <td>Linea Credito</td>
        <td>Beca Nuevo Milenio</td>
        <td>Beca Excelencia</td>
        <td>Porcentaje</td>-->
        <td>Excedente</td>
        <td>Total Cuotas pendientes</td>
     </tr>  
    </thead>
    </tbody>
<?php
///////////////////////////
//alumno.grupo='A' AND alumno.situacion='V' AND alumno.nivel=1 AND alumno.sede='Talca'
require("../../../funciones/conexion_v2.php");
///////////////////////////////////
						
 							/////Registro ingreso///
								 include("../../../funciones/VX.php");
								 $evento="Informe alumnos con excedente id_carrera: $id_carrera sede: $sede year contrato: $year_contrato";
								 REGISTRA_EVENTO($evento);
								$aux=0;	 
	$cons_main_1="SELECT DISTINCT(id_alumno) FROM contratos2 INNER JOIN alumno ON contratos2.id_alumno = alumno.id WHERE $condicion AND ano='$year_actual' ORDER by alumno.apellido_P, alumno.apellido_M";
		
		$sql_main_1=mysql_query($cons_main_1)or die("MAIN 1".mysql_error());
		$num_reg_M=mysql_num_rows($sql_main_1);
		$TOTAL_CUOTAS_PENDIENTES=0;
		$TOTAL_EXCEDENTES=0;
		$TOTAL_BNM=0;
		$TOTAL_BECA_EXCELENCIA=0;
		$TOTAL_LINEA_CREDITO=0;
		$TOTAL_ARANCEL=0;
		if(DEBUG){ echo"<br><br>$cons_main_1<br>NUM.$num_reg_M<br>";}
		if($num_reg_M>0)
		{
			
			while($DID=mysql_fetch_row($sql_main_1))
			{
				$id_alumno=$DID[0];
				
					if($verificar_contrato)
						{
							$cons="SELECT alumno.*, contratos2.id as id_contrato, contratos2.semestre, contratos2.ano, contratos2.vigencia, contratos2.condicion, contratos2.linea_credito_paga, contratos2.numero_cuotas, contratos2.aporte_beca_nuevo_milenio, contratos2.aporte_beca_excelencia, contratos2.excedente, contratos2.porcentaje_beca, contratos2.arancel FROM alumno INNER JOIN contratos2 ON alumno.id = contratos2.id_alumno WHERE contratos2.id_alumno='$id_alumno' AND contratos2.ano='$year_actual' AND contratos2.condicion<>'inactivo' ORDER by contratos2.id desc LIMIT 1";
						
						}
						else
						{ $cons="SELECT * FROM alumno WHERE $condicion ORDER by apellido_P";}	
						if(DEBUG)
						{echo"<br><br>--> $cons <br><br>";}
						
						$sql=mysql_query($cons)or die(mysql_error());
						$num_reg=mysql_num_rows($sql);	
						if($num_reg>0)
						{
							///////////////////////
							while($A=mysql_fetch_assoc($sql))
							{
								$id_alumno=$A["id"];
								$rut=$A["rut"];
								$nombre=$A["nombre"];
								$apellido=$A["apellido"];
								$year_ingreso=$A["ingreso"];
								/////------------ACTUALIZACION----------------/////
								$apellido_P=$A["apellido_P"];
								$apellido_M=$A["apellido_M"];
								$apellido_aux=$apellido_P." ".$apellido_M;
								$nivel_alumno=$A["nivel"];
								$grupo_curso=$A["grupo"];
								$jornada=$A["jornada"];
								/////////////////////------------Datos del Contrato------------/////////////
								$id_contrato=$A["id_contrato"];
								$semestre_contrato=$A["semestre"];
								$year_contrato=$A["ano"];
								$vigencia=$A["vigencia"];
								$condicion_contrato=$A["condicion"];
								$linea_credito=$A["linea_credito_paga"];
								$numero_cuotas=$A["numero_cuotas"];
								$aporte_beca_nuevo_milenio=$A["aporte_beca_nuevo_milenio"];
								$aporte_beca_excelencia_academica=$A["aporte_beca_excelencia"];
								$excedente=$A["excedente"];
								$porcentaje_beca=$A["porcentaje_beca"];
								$arancel=$A["arancel"];
								if($excedente>0)
								{$hay_excedente=true;}
								else{ $hay_excedente=false;}
								
								/////////////////////////------------------------------/////////////////////
								if($apellido_aux==" ")
								{$apellido_label=$apellido;}
								else
								{$apellido_label=$apellido_aux;}
								
								$apellido_label=utf8_decode($apellido_label);
								//////----------------------------//////
								$situacion=$A["situacion"];
								if($verificar_contrato)
								{
									if($year_contrato==$year_actual)
									{ $alumno_vigente=true;}
									else
									{ $alumno_vigente=false;}
								}
								else
								{  $alumno_vigente=true;}	
								//$alumno_vigente=true;//hack para no condicionar por semestre ni año solo condicon "ok" del contrato						
								
								if($no_mostrar_retirados)
								{
									if(($condicion_contrato=="OK")or($condicion_contrato=="OLD")or($condicion_contrato=="old")or($condicion_contrato=="ok"))
									{ $contrato_mostrar=true;}
									else
									{ $contrato_mostrar=false;}
								}
								else
								{ $contrato_mostrar=true;} 
								
								
							
									if(DEBUG){
										echo"$aux - $id_alumno - $rut - $nombre - $apellido_label - $situacion - $nivel_alumno - $grupo_curso - $jornada - $year_ingreso | $id_contrato - $semestre_contrato - $year_contrato - $vigencia [$condicion_contrato] - mostrar=";
										if($alumno_vigente)
										{ echo"<strong>OK</strong><br>";}
										else{  echo"<strong>NO</strong><br>";}
									}
					
									if(($alumno_vigente)and($contrato_mostrar)and($hay_excedente))
									{
										/////////////////////
										$cons_cuo="SELECT SUM(deudaXletra) FROM letras WHERE idalumn='$id_alumno' AND id_contrato='$id_contrato' AND pagada<>'S'";
										if(DEBUG){ echo"--->$cons_cuo<br>";}
										$sql_cuo=mysql_query($cons_cuo)or die(mysql_error());
											$D_cuo=mysql_fetch_row($sql_cuo);
											$total_cuotas_reales=$D_cuo[0];
											mysql_free_result($sql_cuo);
											if(empty($total_cuotas_reales)){ $total_cuotas_reales=0;}
										//
											$TOTAL_CUOTAS_PENDIENTES+=$total_cuotas_reales;
											$TOTAL_EXCEDENTES+=$excedente;
											$TOTAL_BNM+=$aporte_beca_nuevo_milenio;
											$TOTAL_BECA_EXCELENCIA+=$aporte_beca_excelencia_academica;
											$TOTAL_LINEA_CREDITO+=$linea_credito;
											$TOTAL_ARANCEL+=$arancel;
												$aux++;
												echo'<tr>
												<td>'.$aux.'</td>
												<td>'.$rut.'</td>
												<td>'.ucwords(strtolower($nombre)).'</td>
												<td>'.ucwords(strtolower($apellido_label)).'</td>
												<td>'.$nivel_alumno.'</td>
												<td>'.$situacion.'</td>
												<td>'.$year_ingreso.'</td>';
												/*<td align="right">'.$arancel.'</td>
												<td>'.$numero_cuotas.'</td>
												<td align="right">'.$linea_credito.'</td>
												<td align="right">'.$aporte_beca_nuevo_milenio.'</td>
												<td align="right">'.$aporte_beca_excelencia_academica.'</td>
												<td align="right">'.$porcentaje_beca.'%</td>*/
												echo'<td align="right">'.$excedente.'</td>
												<td align="right">'.$total_cuotas_reales.'</td>
												</tr>';
								   }
								
							}
						}
			
			}
		}
		else
		{
				echo'<tr><td>No hay</td></tr>';
		}
		//fin documento
	mysql_free_result($sql_main_1);
	@mysql_close($conexion);
	$conexion_mysqli->close();
?>
<tr>
	<td><strong>Totales</strong></td>
     <td>&nbsp;</td>
      <td>&nbsp;</td>
       <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>    
   <!-- <td align="right"><strong><?php echo number_format($TOTAL_ARANCEL,0,",",".");?></strong></td>
      <td>&nbsp;</td>
    <td align="right"><strong><?php echo number_format($TOTAL_LINEA_CREDITO,0,",",".");?></strong></td>
    <td align="right"><strong><?php echo number_format($TOTAL_BNM,0,",",".");?></strong></td>
    <td align="right"><strong><?php echo number_format($TOTAL_BECA_EXCELENCIA,0,",",".");?></strong></td>
    <td>&nbsp;</td>-->
    <td align="right"><strong><?php echo number_format($TOTAL_EXCEDENTES,0,",",".");?></strong></td>
    <td align="right"><strong><?php echo number_format($TOTAL_CUOTAS_PENDIENTES,0,",",".");?></strong></td>
</tr>
</tbody>
</table>
</div>
</body>
</html>