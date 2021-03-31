<?php
//-----------------------------------------//
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("memoria_matriculas_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	set_time_limit(360);
//-----------------------------------------//	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Memoria Matriculas</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 152px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:33px;
	z-index:2;
	left: 30%;
	top: 100px;
	text-align: center;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Memoria Matriculas CFT</h1>
<div id="link"><br><a href="../../Alumnos/menualumnos.php" class="button">Volver al menul </a>
</div>
<div id="apDiv1">
   <?php
   	require("../../../funciones/conexion_v2.php");
   if(isset($_GET["sede"]))
   {
		$sede=mysqli_real_escape_string($conexion_mysqli, $_GET["sede"]);
   }
   else
   { $sede="";}
   
   if($sede==""){ $condicion_sede="";}
   else{ $condicion_sede="WHERE alumno.sede='$sede'";}
   
   
   	$aux=0;	 
	$verificar_contrato=true;
	$no_mostrar_retirados=true;
	$year_actual=date("Y");
	$cons_main_1="SELECT DISTINCT (id_alumno) FROM (contratos2 INNER JOIN alumno ON contratos2.id_alumno = alumno.id) INNER JOIN carrera ON alumno.id_carrera = carrera.id $condicion_sede ORDER by alumno.id_carrera";
		
		$sqli_main_1=$conexion_mysqli->query($cons_main_1)or die("Principal: ".$conexion_mysqli->error);
		$num_reg_M=$sqli_main_1->num_rows;
		if(DEBUG){ echo"<br><br>$cons_main_1<br>NUM.$num_reg_M<br>";}
		if($num_reg_M>0)
		{
			
			while($DID=$sqli_main_1->fetch_row())
			{
				$id_alumno=$DID[0];
				
				$cons="SELECT DISTINCT(ano) FROM contratos2 WHERE contratos2.id_alumno='$id_alumno' AND contratos2.condicion<>'inactivo' ORDER by contratos2.id DESC";
					
						if(DEBUG){echo"<br><br>--> $cons <br><br>";}
						
						$sqli=$conexion_mysqli->query($cons);
						$num_reg=$sqli->num_rows;
							
						if($num_reg>0)
						{
							///////////////////////
							while($A=$sqli->fetch_row())
							{
								$aux++;
								$year_contrato=$A[0];
								$cons_C="SELECT id_carrera FROM alumno WHERE id='$id_alumno' LIMIT 1";
								$sqli_C=$conexion_mysqli->query($cons_C);
									$C=$sqli_C->fetch_assoc();
									$id_carrera_alumno=$C["id_carrera"];
								$sqli_C->free();	
								
								if(DEBUG)
								{ 
									echo"<br>$aux - <strong>ID_ALUMNO:</strong> $id_alumno <br> year contrato: $year_contrato<br> id_carrera: $id_carrera_alumno<br>";
									
								}
								if(DEBUG){ echo"Condiderar alumno:si<br>";}
								if(isset($ARRAY_DATOS[$id_carrera_alumno][$year_contrato]))
								{$ARRAY_DATOS[$id_carrera_alumno][$year_contrato]+=1;}
								else{ $ARRAY_DATOS[$id_carrera_alumno][$year_contrato]=1;}
								
							}
						}
						
						$sqli->free();
			}
		}
		else
		{	
			echo"Sin Registros<br>";
		}
		//fin documento
	$sqli_main_1->free();
	$conexion_mysqli->close();
	
	if(DEBUG){var_dump($ARRAY_DATOS);}
   ?>
  <table width="100%" border="1" align="center">
  <thead>
    <tr>
      <th colspan="100">Memoria Matriculas CFT(alumnos Matriculados <?php echo $sede;?> x a&ntilde;o contrato no considera situacion)</th>
    </tr>
    </thead>
    <tbody>
    <?php
	
	//ordeno los años con matricula
	$year_con_datos=array();
	foreach($ARRAY_DATOS as $aux_programa => $array_valor)
	{
		
		
		foreach($array_valor as $aux_year => $num_matriculas)
		{
			$year_con_datos[$aux_year]=$aux_year;
		}
	}
	ksort($year_con_datos);
	if(DEBUG){var_dump($year_con_datos);}
	//-----------------------------------------------------------------------------------//
	 	require("../../../funciones/funciones_sistema.php");
    foreach($ARRAY_DATOS as $n => $valor)
	{
		echo'<tr><td>'.NOMBRE_CARRERA($n).'</td>';
		
		ksort($valor);
		foreach($year_con_datos as $aux_year =>$x)
		{
			if(isset($valor[$aux_year])){$num_matriculas=$valor[$aux_year];}
			else{ $num_matriculas=0;}
			
			if(isset($TOTAL[$aux_year])){$TOTAL[$aux_year]+=$num_matriculas;}
			else{ $TOTAL[$aux_year]=$num_matriculas;}
			
			$color_2=dechex($aux_year*24+6);
			
			echo'<td bgcolor="'.$color_2.'" align="center"><strong>'.$aux_year.'</strong></td>
				 <td bgcolor="'.$color_2.'" align="center">'.$num_matriculas.'</td>';
		}
		echo'</tr>';
	}
	?>
    <tr>
    	<td><strong>TOTALES</strong></td>
        <?php
        foreach($TOTAL as $N => $VALOR)
		{
			echo'<td colspan="2" align="center">'.$VALOR.'</td>';
		}
		?>
    </tr>
    </tbody>
  </table>
  <tt>Generado el <?php echo date("d-m-Y H:i:s");?></tt>
</div>
<div id="apDiv2"><a href="memoria_matriculas.php?sede=Talca" class="button_R">Talca</a> <a href="memoria_matriculas.php?sede=Linares" class="button_R">Linares</a> <a href="memoria_matriculas.php" class="button_R">Todas</a></div>
</body>
</html>