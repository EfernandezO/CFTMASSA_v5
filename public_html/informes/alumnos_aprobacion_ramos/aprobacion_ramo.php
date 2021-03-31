<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="inspeccion";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
//-----------------------------------------//	
	define("DEBUG", false);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Aprobacion de Ramos</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:94%;
	height:25px;
	z-index:1;
	left: 3%;
	top: 82px;
}
</style>
<style type="text/css" title="currentStyle">
			@import "../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/css/demo_page.css";
			@import "../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/css/demo_table_jui.css";
			@import "../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/examples/examples_support/themes/smoothness/jquery-ui-1.8.4.custom.css";
			
		</style>
		<script type="text/javascript" language="javascript" src="../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.dataTables.js"></script>
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				oTable = $('#example').dataTable({
					"bJQueryUI": true,
					"sPaginationType": "full_numbers",
					"bPaginate": false,
				});
			} );
		</script>
</head>
<body>
<h1 id="banner"> Informe Nivel Aprobaci&oacute;n Alumnos</h1>
<div id="link"></div>
<div id="apDiv1" class="demo_jui">
  <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" class="display" id="example">
<thead>
	<th>N.</th>
	<th>ID</th>
    <th>Rut</th>
    <th>Nombre</th>
    <th>Apellido P</th>
    <th>Apellido M</th>
    <th>Cantidad Beca</th>
    <th>Porcentaje Beca</th>
    <th>Txt Beca</th>
    <th>Total Ramos</th>
    <th>Num. Aprobados</th>
    <th>% Aprobacion</th>
</thead>
<tbody>
<?php
if(DEBUG)
{ var_export($_POST);}
if($_POST)
{
	set_time_limit(90);
	$nota_aprobacion=4;
	
	$sede=$_POST["fsede"];
	$carrera=$_POST["carrera"];
	$año_ingreso=$_POST["ano_ingreso"];
	$jornada=$_POST["jornada"];
	$grupo=$_POST["grupo"];
	$nivel=$_POST["nivel"];
	$situacion=$_POST["estado"];
	$con_beca=$_POST["con_beca"];
	
	$notas_year=$_POST["year_notas"];
	$notas_nivel=$_POST["nivel_notas"];
	
	if($sede=="")
	{$sede="Talca";}
	$condicion=" sede='$sede' AND alumno.carrera='$carrera'";
	
	
	if($año_ingreso!="Todos")
	{
		$condicion.=" AND ingreso='$año_ingreso'";
	}
	if($jornada!="T")
	{
		$condicion.=" AND jornada='$jornada'";
	}
	if($situacion!="A")
	{
		$condicion.=" AND situacion='$situacion'";
	}
	if($grupo!="Todos")
	{
		$condicion.=" AND grupo='$grupo'";
	}
	if($nivel!="Todos")
	{
		$condicion.="AND nivel='$nivel'";
	}
	
	//notas---------------------------------------------
	if($notas_nivel!="Todos")
	{ $condicion_notas="AND nivel='".$notas_nivel."'";}
	if($notas_year!="Todos")
	{ $condicion_notas.=" AND ano='$notas_year'";}
	$condicion_notas.=" AND NOT nota=''";
	//////////////////////////////////////////////
	
	require("../../../funciones/conexion_v2.php");

	$cons="SELECT * FROM alumno WHERE $condicion ORDER by apellido_P";
	
	if(DEBUG)
	{echo"<br>--> <b>$cons </b><br><br>";}
	$sql=$conexion_mysqli->query($cons);
	$num_reg=$sql->num_rows;
	if($num_reg>0)
	{
		$contador=0;
		while($D=$sql->fetch_assoc())
		{
			$contador++;
			
			$A_id=$D["id"];
			$A_rut=$D["rut"];
			$A_nombre=$D["nombre"];
			$A_apellido_P=$D["apellido_P"];
			$A_apellido_M=$D["apellido_M"];
			$A_ingreso=$D["ingreso"];
			$A_nivel=$D["nivel"];
			$A_jornada=$D["jornada"];
			$A_grupo=$D["grupo"];
			$A_sede=$D["sede"];
			$A_carrera=$D["carrera"];
			
			if(DEBUG){ echo"<br><b>($contador)</b> ID: $A_id - $A_rut - $A_carrera<br>";}
			
			//**********************************************//
			//datos de beca
			$cons_B="SELECT * FROM contratos2 WHERE id_alumno='$A_id' ORDER by id desc LIMIT 1";
			$sql_B=$conexion_mysqli->query($cons_B)or die(mysql_error());
				$D_beca=$sql_B->fetch_assoc();
				$A_cantidad_beca=$D_beca["cantidad_beca"];
				$A_porcentaje_beca=$D_beca["porcentaje_beca"];
				$A_txt_beca=$D_beca["txt_beca"];
			$sql_B->free();	
				if(($A_cantidad_beca>0)or($A_porcentaje_beca>0))
				{ $alumno_tiene_beca=true;}
				else
				{ $alumno_tiene_beca=false;}
				
			
			switch($con_beca)
			{
				case"todos":
					$mostrar_alumno=true;
					break;
				case"con_beca":	
					if($alumno_tiene_beca)
					{$mostrar_alumno=true;}
					else{ $mostrar_alumno=false;}
					break;
				case"sin_beca":
					if($alumno_tiene_beca)
					{$mostrar_alumno=false;}
					else{ $mostrar_alumno=true;}
					break;	
			}
			
			if($mostrar_alumno)
			{
				echo'<tr>
					<td>'.$contador.'</td>
					<td>'.$A_id.'</td>
					<td>'.$A_rut.'</td>
					<td>'.ucwords(strtolower($A_nombre)).'</td>
					<td>'.$A_apellido_P.'</td>
					<td>'.$A_apellido_M.'</td>
					<td>'.$A_cantidad_beca.'</td>
					<td>'.$A_porcentaje_beca.'</td>
					<td>'.$A_txt_beca.'</td>';
					
					
					
					
				$cons_N="SELECT * FROM notas WHERE id_alumno='$A_id' $condicion_notas ORDER by cod";
				if(DEBUG){ echo":::$cons_N<br>";}
				$sql_N=$conexion_mysqli->query($cons_N);
				$num_registros=$sql_N->num_rows;
				if($num_registros>0)
				{
					$ramo_total=0;
					$ramo_aprobado=0;
					$ramo_reprobado=0;
					while($Dn=$sql_N->fetch_assoc())
					{
						$ramo_total++;
						$N_cod=$Dn["cod"];
						$N_nivel=$Dn["nivel"];
						$N_ramo=$Dn["ramo"];
						$N_nota=$Dn["nota"];
						$N_semestre=$Dn["semestre"];
						$N_year=$Dn["ano"];
						
						if($N_nota>=$nota_aprobacion)
						{
							$ramo_aprobado++;
							$ramo_condicion="Aprobado";
						}
						else
						{
							$ramo_reprobado++;
							$ramo_condicion="Reprobado";
						}
						
						if(DEBUG){ echo"<tt>N: $N_cod - $N_ramo - $N_nivel - $N_year - $N_nota ->[$ramo_condicion]</tt><br>";}
					}
					$PORCENTAJE_APROBACION=(($ramo_aprobado*100)/$ramo_total);
					if(DEBUG){ echo"**TOTAL RAMOS: $ramo_total APROBADOS: $ramo_aprobado PROCENTAJE APROBACION: $PORCENTAJE_APROBACION%**<br>";}
					
					echo'<td>'.$ramo_total.'</td>
						 <td>'.$ramo_aprobado.'</td>
						 <td>'.number_format($PORCENTAJE_APROBACION,2).'</td>
						 </tr>';
				}
				else
				{
					if(DEBUG){ echo"...Sin Registro de Notas<br><br>";}
					echo'<td>---</td>
						 <td>---</td>
						 <td>0</td>
						 </tr>';
				}
			$sql_N->free();	
			}
			
		}
	}
	else
	{}
	$sql->free();
	@mysql_close($conexion);
	$conexion_mysqli->close();
}
else
{ header("location: index.php");}

//////////////////////////////////////////////+
function NUMERO_A_ROMANO($num)
{
	if ($num <0 || $num >9999) {return -1;}
	$r_ones = array(1=>"I", 2=>"II", 3=>"III", 4=>"IV", 5=>"V", 6=>"VI", 7=>"VII", 8=>"VIII",
	9=>"IX");
	$r_tens = array(1=>"X", 2=>"XX", 3=>"XXX", 4=>"XL", 5=>"L", 6=>"LX", 7=>"LXX",
	8=>"LXXX", 9=>"XC");
	$r_hund = array(1=>"C", 2=>"CC", 3=>"CCC", 4=>"CD", 5=>"D", 6=>"DC", 7=>"DCC",
	8=>"DCCC", 9=>"CM");
	$r_thou = array(1=>"M", 2=>"MM", 3=>"MMM", 4=>"MMMM", 5=>"MMMMM", 6=>"MMMMMM",
	7=>"MMMMMMM", 8=>"MMMMMMMM", 9=>"MMMMMMMMM");
	$ones = $num % 10;
	$tens = ($num - $ones) % 100;
	$hundreds = ($num - $tens - $ones) % 1000;
	$thou = ($num - $hundreds - $tens - $ones) % 10000;
	$tens = $tens / 10;
	$hundreds = $hundreds / 100;
	$thou = $thou / 1000;
	if ($thou) {$rnum .= $r_thou[$thou];}
	if ($hundreds) {$rnum .= $r_hund[$hundreds];}
	if ($tens) {$rnum .= $r_tens[$tens];}
	if ($ones) {$rnum .= $r_ones[$ones];}
return $rnum;
}
?>
</tbody>
</table>
</div>
</body>
</html>