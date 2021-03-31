<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	OKALIS($lista_invitados);
	define("DEBUG",false);
//-----------------------------------------//
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Alumno | Asignaturas Pendientes</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:50%;
	height:91px;
	z-index:1;
	left: 5%;
	top: 109px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:91px;
	z-index:2;
	left: 55%;
	top: 110px;
}
-->
</style>
</head>
<?php
if($_POST)
{
	$sede=$_POST["fsede"];
	$array_sede=$_POST["carrera"];
	$array_carrera=explode("_",$array_sede);
	$carrera=$array_carrera[1];
	$id_carrera=$array_carrera[0];
	$nivel=$_POST["nivel"];
	$year=$_POST["year"];
	$alumnos_a_mostrar=$_POST["mostrar_alumnos"];
}
?>
<body>
<h1 id="banner">Administrador - Alumnos y asignaturas Pendientes</h1>
<div id="link">
  <div align="right"><br />
<a href="../../Alumnos/menualumnos.php" class="button">Volver al Menu</a></div>
</div>
<div id="apDiv1">
<table width="100%" border="1">
<thead>
<tr>
<th colspan="7"><?php echo"ALUMNOS de $carrera - $sede <br> Ingreso: $year Nivel: $nivel";?></th>
</tr>
  <tr>
    <td><strong>N</strong></td>
    <td><strong>ID</strong></td>
    <td><strong>Rut</strong></td>
    <td><strong>Nombre</strong></td>
    <td><strong>Apellido P</strong></td>
    <td><strong>Situacion</strong></td>
    <td><strong>Actualmente Matriculado</strong></td>
  </tr>
</thead>
<tbody>
<?php
if($_POST)
{
include("../../../funciones/conexion.php");
	///////////////////
	$year_actual=date("Y");
	if(date("m")>8)
	{$semestre_actual=2;}
	else{ $semestre_actual=1;}
	///////////////////
	
	$img_ok='<img src="../../BAses/Images/ok.png" width="29" height="26" alt="ok" />';
	$img_error='<img src="../../BAses/Images/b_drop.png" width="16" height="16" alt="X" />';
	$ALUMNO_PENDIENTE=array();
	$ALUMNO_SITUACION_PENDIENTE=array();
	
	if($year=="Todos")
	{ $condicion_ingreso="";}
	else{ $condicion_ingreso="AND ingreso='$year'";}
	
	$cons="SELECT * FROM alumno WHERE id_carrera='$id_carrera' AND sede='$sede' AND nivel='$nivel' $condicion_ingreso ORDER by apellido_P, apellido_M";
	$sql=mysql_query($cons)or die(mysql_error());
	$num_registros=mysql_num_rows($sql);
	if(DEBUG){ echo"$cons<br>NUM REG: $num_registros<br><br>";}
	if($num_registros>0)
	{
		$aux=0;
		while($A=mysql_fetch_assoc($sql))
		{
			$aux++;
			
			$alumno_con_situacion_pendiente=false;
			
			$A_id=$A["id"];
			$A_rut=$A["rut"];
			$A_nombre=$A["nombre"];
			$A_apellido_P=$A["apellido_P"];
			$A_apellido_M=$A["apellido_M"];
			$A_situacion=$A["situacion"];
			
			
			$matriculado_actualmente=ACTUALMENTE_MATRICULADO($A_id, $semestre_actual, $year_actual);
			if($matriculado_actualmente)
			{ $esta_matriculado="si"; $TOTAL_ALUMNOS_MATRICULADOS_ACTUALMENTE++;}
			else
			{ $esta_matriculado="no";}
			
			if(DEBUG){ echo"$aux | $A_id | $A_rut | $A_nombre | $A_apellido_P | $A_situacion | $esta_matriculado <br>";}
			switch($alumnos_a_mostrar)
			{
				 case"todos":
				 	$mostrar=true;
				 	break;
				 case"matriculados":	
				 	if($matriculado_actualmente)
					{ $mostrar=true;}
					else{ $mostrar=false;}
				 	break;
				 case"no_matriculados";	
				 	if($matriculado_actualmente)
					{ $mostrar=false;}
					else{ $mostrar=true;}
				 	break;
			}
			if($mostrar)
			{
				echo'<tr>
						<td>'.$aux.'</td>
						<td>'.$A_id.'</td>
						<td>'.$A_rut.'</td>
						<td>'.$A_nombre.'</td>
						<td>'.$A_apellido_P.'</td>
						<td>'.$A_situacion.'</td>
						<td>'.$esta_matriculado.'</td>
					</tr>';
			
			/////////////////
				//recorro asignaturas
				$cons_N="SELECT * FROM notas WHERE id_alumno='$A_id' AND ramo<>'' AND nivel<'$nivel' ORDER by cod";
				$sql_N=mysql_query($cons_N)or die(mysql_error());
				$num_ramos=mysql_num_rows($sql_N);
				if(DEBUG){ echo"------------------------------------------------<br>$cons_N<br>NUM RAMOS: $num_ramos<br>";}
				echo'<tr><td colspan="7"><table align="right">
						<thead>
							<th colspan="6">Asignaturas</th>
						</thead>
						<tbody>';
							
				if($num_ramos>0)
				{
					while($N=mysql_fetch_assoc($sql_N))
					{
						$N_id=$N["id"];
						$N_ramo=$N["ramo"];
						$N_nota=$N["nota"];
						$N_nivel=$N["nivel"];
						
						if(DEBUG){ echo"---->$N_id | $N_nivel| $N_ramo | $N_nota ";}
						
						if(empty($N_nota))
						{ if(DEBUG){ echo"<strong>SITUACION PENDIENTE</strong>";} $img_asignatura=$img_error; $ALUMNO_PENDIENTE[$N_ramo]+=1; $alumno_con_situacion_pendiente=true;}
						else{ $img_asignatura=$img_ok;}
						
						echo'<tr>
							<td>&nbsp;</td>
							<td>'.$N_id.'</td>
							<td>'.$N_nivel.'</td>
							<td>'.$N_ramo.'</td>
							<td>'.$N_nota.'</td>
							<td>'.$img_asignatura.'</td>
							</tr>';
						
						
						
						if(DEBUG){ echo"<br>";}
					}
					
					if($alumno_con_situacion_pendiente)
					{ $ALUMNO_SITUACION_PENDIENTE[$A_rut]=$A_nombre." ".$A_apellido_P." ".$A_apellido_M;}
					
				}
				else
				{
					if(DEBUG){ echo"Sin Registro Creado<br>";}
					echo'<tr><td colspan="4">Sin Registro Creado</td></tr>';
				}
				echo'</tbody></table></td></tr>';
			}
			if(DEBUG){ echo"------------------------------------------------<br>";}
		}
	}
	else
	{
		echo'<tr><td colspan="8";>Sin ALumnos Encontrados</td></tr>';
	}
mysql_free_result($sql);	
mysql_close($conexion);	
}
//////////////////////////////////////
//////////////////////////////////////////////
function ACTUALMENTE_MATRICULADO($id_alumno, $semeste_actual, $year_actual)
{
	$cons="SELECT * FROM contratos2 WHERE id_alumno='$id_alumno' AND ano='$year_actual' ORDER by id";
	$sql=mysql_query($cons)or die(mysql_error());
	$num_contratos=mysql_num_rows($sql);
	if(DEBUG){ echo"CONTRATOS ($semeste_actual-$year_actual)<br>$cons<br>Num. $num_contratos<br>";}
	if($num_contratos>0)
	{
		while($C=mysql_fetch_assoc($sql))
		{
			$C_id=$C["id"];
			$C_vigencia=strtolower($C["vigencia"]);
			$C_condicion=strtolower($C["condicion"]);
			$C_semestre=$C["semestre"];
			$C_year=$C["ano"];
			if(DEBUG){ echo"--->$C_id | $C_vigencia | $C_condicion |$C_semestre| $C_year| ";}
			
			if($C_condicion=="ok")
			{
				switch($C_vigencia)
				{
					case"semestral":
						if(($C_semestre==$semeste_actual)and($C_year==$year_actual))
						{ $alumno_vigente=true;}
						else
						{ $alumno_vigente=false;}
						break;
					case"anual":
						if($C_year==$year_actual)
						{ $alumno_vigente=true;}
						else
						{ $alumno_vigente=false;}
						break;	
				}
			}
			else
			{ $alumno_vigente=false;}
			if(DEBUG){if($alumno_vigente){ echo"OK<br>";}else{ echo"NO<br>";}}
		}
	}
	else
	{ $alumno_vigente=false;}
	
	if(DEBUG){ if($alumno_vigente){ echo"ALUMNO VIGENTE: <strong>OK</strong><br><br>";}else{ echo"ALUMNO VIGENTE: <strong>NO</strong><br><br>";} }
	mysql_free_result($sql);
	
	return($alumno_vigente);
}
?>
  </tbody>
</table>
</div>
<div id="apDiv2">
<table width="80%" border="1" align="center">
<thead>
  <tr>
    <th colspan="3">Resumen Situaciones Pendientes X asignatura</th>
  </tr>
  <tr>
    <td>N</td>
    <td>Asignatura</td>
    <td>Situaciones Pendientes</td>
  </tr>
   </thead>
    <tbody>
    <?php
	$x=0;
	$SUMA_VALOR=0;
	
	$c=count($ALUMNO_PENDIENTE);
	if($c>0)
	{
		foreach($ALUMNO_PENDIENTE as $n => $valor)
		{
			$x++;
			$SUMA_VALOR+=$valor;
			echo'<tr>
					<td>'.$x.'</td>
					<td>'.$n.'</td>
					<td>'.$valor.'</td>
				 </tr>';
		}
	}
	else
	{
		echo'<tr><td colspan="3">No hay Situaciones Pendientes</td></tr>';
	}
	?>
    <tr>
    	<td colspan="2">Total</td>
        <td><?php echo $SUMA_VALOR;?></td>
    </tr>
  </tbody>
</table>

<table width="80%" border="1" align="center">
<thead>
  <tr>
    <th colspan="3">Alumnos Con Situacion Pendiente</th>
  </tr>
  <tr>
    <td>N</td>
    <td>Rut</td>
    <td>Alumno</td>
  </tr>
   </thead>
    <tbody>
    <?php
	$x=0;
	$d=count($ALUMNO_SITUACION_PENDIENTE);
	if($d>0)
	{
		foreach($ALUMNO_SITUACION_PENDIENTE as $nx => $valorx)
		{
			$x++;
			echo'<tr>
					<td>'.$x.'</td>
					<td>'.$nx.'</td>
					<td>'.$valorx.'</td>
				 </tr>';
		}
	}
	else
	{
		echo'<tr><td colspan="3">No hay Situaciones Pendientes</td></tr>';
	}
	?>
  </tbody>
</table>
</div>
</body>
</html>