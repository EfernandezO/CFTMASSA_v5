<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_seguimientoXCohorte_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
set_time_limit(6000);
ini_set('memory_limit', '-1');
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
//---------------------------------------------//
require("../../../funciones/conexion_v2.php");
require("../../../funciones/funciones_sistema.php");
//Periodo
$year_actual=date("Y");
$mes_actual=date("m");
$mostrar_detalle=true;//muestra listalle de alumno y situacion x año

if($mes_actual>=8)
{ $semestre_actual=2;}
else
{ $semestre_actual=1;}

if($_GET)
{
	if(DEBUG){ echo"Hay Get<br>";}
	$year_consulta=strip_tags(mysqli_real_escape_string($conexion_mysqli, $_GET["year"]));
	$semestre_consulta=$semestre_actual;
	$sede_consulta=strip_tags(mysqli_real_escape_string($conexion_mysqli, $_GET["sede"]));
	$jornada_consulta=strip_tags(mysqli_real_escape_string($conexion_mysqli, $_GET["jornada"]));
}
else
{
	if(DEBUG){ echo"NO Hay Get<br>";}
	$year_consulta=$year_actual;
	$semestre_consulta=$semestre_actual;
	$sede_consulta=0;
	$jornada_consulta=0;
}
//-----------------------------------------------//
require("../../../funciones/VX.php");
$evento="Cohorte Institucional year $year_consulta";
REGISTRA_EVENTO($evento);
//--------------------------------------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Cohorte institucional V2</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:1;
	left: 425px;
	top: 103px;
}
#apDiv2 {position:absolute;
	width:40%;
	height:58px;
	z-index:2;
	left: 30%;
	top: 90px;
}
#apDiv3 {
	position:absolute;
	width:100%;
	height:115px;
	z-index:3;
	left: 0%;
	top: 266px;
}
</style>
<!--INICIO LIGHTBOX EVOLUTION-->
  <script type="text/javascript" src="../../libreria_publica/lightbox_1.6.9_evolution/js/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.css" />
  <!--[if IE 6]>
  <link rel="stylesheet" type="text/css" href="../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.ie6.css" />
  <![endif]-->
  <script type="text/javascript" src="../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/jquery.lightbox.min.js"></script>
 <script type="text/javascript">
    jQuery(document).ready(function($){
      $('.lightbox').lightbox(); 
    });
  </script>
 <!--FIN LIGHTBOX EVOLUTION--> 
</head>


<body>
<h1 id="banner">Administrador - Cohorte Institucional</h1>
<div id="link"><br /><a href="../../Alumnos/menualumnos.php" class="button">Volver a Menu</a></div>
<div id="apDiv2">
  <form action="cohorte_institucional_1_v2_segun_notas.php" method="get" id="frm">
    <table width="100%" border="1">
      <thead>
        <tr>
          <th colspan="2">Parametros Busqueda</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Sede</td>
          <td><?php echo CAMPO_SELECCION("sede", "sede",$sede_consulta,true);?></td>
        </tr>
        <tr>
          <td>a&ntilde;o</td>
          <td><?php echo CAMPO_SELECCION("year", "year", $year_consulta,false);?></td>
        </tr>
        <tr>
          <td>Jornada</td>
          <td><?php echo CAMPO_SELECCION("jornada", "jornada", $jornada_consulta,true);?>*Utilizar todas (filtrar solo para depurar)</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><a href="#" class="button_R" onclick="javascript:document.getElementById('frm').submit();">Consultar</a></td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<div id="apDiv3">
<?php
	$ARRAY_GLOBAL=array();
	$ARRAY_RESULTADOS=array();
	$ARRAY_id_carrera=array();
	$ARRAY_SEDE=array("Talca", "Linares");
	$ARRAY_DATOS_ALUMNO=array();
	
	
	$year_contrato=$year_consulta;
	if($sede_consulta!="0"){$condicion_sede="AND contratos2.sede='$sede_consulta'";}
	else{ $condicion_sede="";}
	
	if($jornada_consulta!="0"){$condicion_jornada="AND alumno.jornada='$jornada_consulta'";}
	else{ $condicion_jornada="";}
	$aux_G=1;
	
	
	//lleno array inicial de universo de alumnos
	$cons_MAIN="SELECT DISTINCT(id_alumno) FROM contratos2 INNER JOIN alumno ON contratos2.id_alumno=alumno.id WHERE contratos2.ano='$year_contrato' AND alumno.ingreso='$year_consulta' $condicion_sede $condicion_jornada ORDER BY alumno.id_carrera, alumno.jornada, alumno.apellido_P, alumno.apellido_M";
	if(DEBUG){ echo"Consulta GLobal: $cons_MAIN<br>";}
	$sqli_MAIN=$conexion_mysqli->query($cons_MAIN)or die($conexion_mysqli->error);
	$num_registros=$sqli_MAIN->num_rows;
	
	if($num_registros>0)
	{
		$aux=0;
		while($CA=$sqli_MAIN->fetch_row())
		{
			$aux++;
			$id_alumno=$CA[0];
			if(DEBUG){ echo"<br><strong>$aux -> id_alumno:$id_alumno </strong><br>";}
			
			$cons_1="SELECT alumno.sexo, contratos2.id, contratos2.id_carrera, contratos2.sede FROM alumno INNER JOIN contratos2 ON alumno.id=contratos2.id_alumno WHERE alumno.id='$id_alumno' AND contratos2.ano='$year_contrato' $condicion_sede $condicion_jornada ORDER by contratos2.id DESC LIMIT 1";		
			if(DEBUG){ echo"--->$cons_1<br>";}
			$sqli_1=$conexion_mysqli->query($cons_1)or die($conexion_mysqli->error);
			$DC=$sqli_1->fetch_assoc();
				$A_sexo=trim($DC["sexo"]);
				$A_sexo=str_replace(" ","",$A_sexo);
				$C_id=$DC["id"];
				$C_id_carrera=$DC["id_carrera"];
				$C_sede=$DC["sede"];
			$sqli_1->free();	
			if(DEBUG){ echo"-----> id_contrato:$C_id id_carrera_contrato: $C_id_carrera  sexo_alumno:$A_sexo sede_contrato: $C_sede<br>";}
			
			$ARRAY_GLOBAL[$sede_consulta][$C_id_carrera][$A_sexo][$id_alumno]=true;
		}
	}
	else
	{
		if(DEBUG){ echo"sin alumnos en matricula inicial este año";}
	}
	//----------------------------------------------------------------------------------------------------------//

	///recorremos arreglos
	
	
		$c=0;	
		if(DEBUG){ echo"<strong>YEAR CONTRATO: $year_contrato</strong><br>";}
		foreach($ARRAY_GLOBAL as $aux_sede => $aux_array_1)
		{
			if(DEBUG){ echo "-->Sede: $aux_sede ";}
			foreach($aux_array_1 as $aux_id_carrera => $aux_array_2)
			{
				if(DEBUG){ echo "-->id_carrera: $aux_id_carrera ";}
				foreach($aux_array_2 as $aux_sexo => $aux_array_3)
				{
					if(DEBUG){ echo "-->sexo: $aux_sexo <br>";}
					foreach($aux_array_3 as $aux_id_alumno => $utilizar_alumno)
					{
						$c++;
						if(DEBUG){echo"<br><strong>[$c]</strong> id_alumno: $aux_id_alumno<br>";}
						if($utilizar_alumno)
						{
							if(DEBUG){ echo"Revisar Periodos....<br>";}
							
							$y=$year_consulta;
							while($y<=$year_actual)
							{
								$s=1;
								while($s<=2)
								{
									if(DEBUG){ echo"-->Periodo[$s - $y]<br>";}
									$cons_N1="SELECT COUNT(id) FROM notas WHERE id_alumno='$aux_id_alumno' AND id_carrera='$aux_id_carrera' AND semestre='$s' AND ano='$y' AND nota>0";
									$sqli_N1=$conexion_mysqli->query($cons_N1)or die($conexion_mysqli->error);
									$CX=$sqli_N1->fetch_row();
									$num_notas=$CX[0];
									if(empty($num_notas)){$num_notas=0;}
									$sqli_N1->free();
									if(DEBUG){ echo"--->$cons_N1<br>Num Notas: $num_notas<br>";}
									
									$ARRAY_DATOS_ALUMNO[$aux_id_alumno][$aux_id_carrera][$y][$s]["N_notas"]=$num_notas;
									$s++;
								}
								$y++;
							}
							
						}
						else
						{
							if(DEBUG){ echo"No utilizar este alumno -> Ultima condicion: ".$ARRAY_DATOS_ALUMNO[$aux_id_alumno]["situacion"]."<br>";}
						}
					}
				}
			}
		}
		$year_contrato++;
	
//------------------------------------------//
$periodos=($year_actual-$year_consulta);
$periodos++;
//------------------------------------------//
							
if($mostrar_detalle)
{
	echo'<table border="1" width="85%" align="center">
		<thead>
			<tr>
				<th colspan="'.($periodos*2+5).'">Detalle de Cohorte X alumno '.$year_consulta.'</th>
			</tr>
			<tr>
				<td>N</td>
				<td>id_alumno</td>
				<td>Alumno</td>
				<td>Carrera</td>
				<td>Situacion</td>';
				for($y7=$year_consulta;$y7<=$year_actual;$y7++)
				{ 
					for($s6=1;$s6<=2;$s6++)
					{
						echo'<td align="center">'.$s6.' - '.$y7.'</td>';
					}
				}
				
	echo'</tr></thead><tbody>';
		
	$contador=0;
	$num_titulados=0;
	$num_egresados=0;
	$cuenta_alumnos=0;
	$primera_vuelta=true;
	foreach($ARRAY_DATOS_ALUMNO as $aux_id_alumno => $array_A)
	{
		$contador++;
		//---------------------------------------------//
		$cons_A="SELECT rut, nombre, apellido_P, apellido_M, situacion FROM alumno WHERE id='$aux_id_alumno' LIMIT 1";
		$sqli_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
		$AX=$sqli_A->fetch_assoc();
			$Alumno=$AX["nombre"]." ".$AX["apellido_P"]." ".$AX["apellido_M"];
			$rut=$AX["rut"];
			$A_situacion=$AX["situacion"];
		$sqli_A->free();	
		//----------------------------------------------//	
		$cuenta_alumnos++;
		foreach($array_A as $aux_id_carrera_A => $array_B)
		{
			
			switch($A_situacion)
			{
				case"V":
					$color_situacion='#9E9';
					break;
				case"R":
					$color_situacion='#E99';
					break;
				case"EG":
					$color_situacion='#99E';
					break;
				case"T":
					$color_situacion='#77F';
					break;
			}
			
			
			echo'<tr>
					<td>'.$contador.'</td>
					<td>'.$aux_id_alumno.'</td>
					<td>'.$Alumno.'</td>
					<td bgcolor="'.COLOR_CARRERA($aux_id_carrera_A).'">'.NOMBRE_CARRERA($aux_id_carrera_A).'</td>
					<td bgcolor="'.$color_situacion.'"><strong>'.$A_situacion.'</strong></td>';

				for($y7=$year_consulta;$y7<=$year_actual;$y7++)
				{
					for($s7=1;$s7<=2;$s7++)
					{
						$num_notas=$array_B[$y7][$s7]["N_notas"];
						
						if($num_notas>0){$color_X='#AFA';}
						else{ $color_X='#FAA';}
						
						echo'<td bgcolor="'.$color_X.'">'.$num_notas.'</td>';
					}
				}
			
			echo'</tr>';
		}
	}
	echo'</tbody></table>';
}
$conexion_mysqli->close();				
?>
</div>
</body>
</html>