<?php
///////////////////////////////////////////////
////////////Registra Eventos de User///////////
///////////////////////////////////////////////
function REGISTRA_EVENTO($evento)
{
	@session_start();
	require("conexion_v2.php");
	$id_user=$_SESSION["USUARIO"]["id"];
	$privilegio_save=strtolower($_SESSION["USUARIO"]["privilegio"]);
		
		
	
	$sede=$_SESSION["USUARIO"]["sede"];
	if(isset($_SERVER['REMOTE_ADDR'])){$ip=$_SERVER['REMOTE_ADDR'];}
	else{$ip="127.0.0.1";}
	
	date_default_timezone_set('America/Santiago');//zona horaria
	$fecha_hora=date("Y-m-d H:i:s");
	$campos="tipo_usuario, id_user, ip, fecha_hora, evento, sede";
	$valores="'$privilegio_save', '$id_user', '$ip', '$fecha_hora', '$evento', '$sede'";
	$cons="INSERT into historial ($campos) VALUES($valores)";
	$conexion_mysqli->query($cons)or die("REGISTRA_EVENTO: ".$conexion_mysqli->error);
	$conexion_mysqli->close();
}
////cambia de estado al usuario para saber los usuarios on line
function CAMBIA_ESTADO_CONEXION($id_usuario, $new_estado_conexion)
{
	require("conexion_v2.php");
	$fecha_hora_actual=date("Y-m-d H:i:s");
	$cons_cE="UPDATE personal SET estado_conexion='$new_estado_conexion', ultima_conexion='$fecha_hora_actual' WHERE id='$id_usuario' LIMIT 1";
	if(DEBUG){ echo"--->$cons_cE<br>\n";}
	else
	{$conexion_mysqli->query($cons_cE)or die("CAMBIA ESTADO CONEXION".$conexion_mysqli->error);}
	$conexion_mysqli->close();
}
////cambia de estado al alumno para saber los usuarios on line
function CAMBIA_ESTADO_CONEXION_ALUMNO($id_alumno, $new_estado_conexion)
{
	require("conexion_v2.php");
	$fecha_hora_actual=date("Y-m-d H:i:s");
	$cons_cE="UPDATE alumno SET estado_conexion='$new_estado_conexion', ultima_conexion='$fecha_hora_actual' WHERE id='$id_alumno' LIMIT 1";
	if(DEBUG){ echo"--->$cons_cE<br>";}
	else{$conexion_mysqli->query($cons_cE)or die("CAMBIA ESTADO CONEXION ALUMNO :".$conexion_mysqli->error);}
	$conexion_mysqli->close();
}
//---------------------------------------//
//--------busca usuario on line----------//
//---------------------------------------//
function USUARIOS_ACTIVOS($id_usuario_actual)
{
	require("conexion_v2.php");
	$array_activos=array();
	$fecha_hora_actual=date("Y-m-d H:i:s");
	$fecha_hora_actual_time=time();
	$cons_UA="SELECT id, nick, ultima_conexion FROM personal WHERE estado_conexion='on' AND NOT id='$id_usuario_actual' ORDER by nick";
	if(DEBUG){ echo"$cons_UA<br>";}
	$sql_UA=$conexion_mysqli->query($cons_UA);
	$num_activo=$sql_UA->num_rows;
	if($num_activo>0)
	{
		while($D_ua=$sql_UA->fetch_assoc())
		{
			$aux_id=$D_ua["id"];
			$aux_nick=$D_ua["nick"];
			$aux_ultima_conexion=$D_ua["ultima_conexion"];
			$aux_tiempo_conexion_time=strtotime($aux_ultima_conexion);
			$tiempo_expira=($aux_tiempo_conexion_time+3600);
			
			if(DEBUG){ echo"$aux_id - $aux_nick - Hora ultima conexion:$aux_ultima_conexion - Hora ultima conexion time:$aux_tiempo_conexion_time - hora-tiempo expira time:$tiempo_expira - fecha-hora actual time:$fecha_hora_actual_time<br>";}
			
			if($fecha_hora_actual_time>$tiempo_expira)
			{
				if(DEBUG){ echo"FUERA DEL TIEMPO<br>";}
				$cons_desconexion="UPDATE personal SET estado_conexion='off' WHERE id='$aux_id' LIMIT 1";
				if(DEBUG){ echo"DESCONEXION FORZADA: $cons_desconexion<br>";}
				$conexion_mysqli->query($cons_desconexion)or die("desconexion forzada".$conexion_mysqli->error);
			}
			else
			{
				if(DEBUG){ echo"DENTRO DEL TIEMPO<br>";}
				$array_activos[]=$aux_nick;
			}
		}
	}
	else
	{
		//no hay usuario activos
		if(DEBUG){ echo"No hay Activos<br>";}
		$array_activos[]="No hay usuarios";
	}
	$conexion_mysqli->close();
	return($array_activos);
}
/////alumnos activos
function ALUMNOS_ACTIVOS()
{
	require("conexion_v2.php");
	$array_activos=array();
	$fecha_hora_actual=date("Y-m-d H:i:s");
	$fecha_hora_actual_time=time();
	$cons_UA="SELECT id, rut, ultima_conexion FROM alumno WHERE estado_conexion='ON' ORDER by id";
	if(DEBUG){ echo"$cons_UA<br>";}
	$sql_UA=$conexion_mysqli->query($cons_UA)or die("ALUMNO_ACTIVOS:".$conexion_mysqli->error);
	$num_activo=$sql_UA->num_rows;
	if($num_activo>0)
	{
		while($D_ua=$sql_UA->fetch_assoc())
		{
			$aux_id=$D_ua["id"];
			$aux_rut=$D_ua["rut"];
			$aux_ultima_conexion=$D_ua["ultima_conexion"];
			$aux_tiempo_conexion_time=strtotime($aux_ultima_conexion);
			$tiempo_expira=($aux_tiempo_conexion_time+3600);
			
			if(DEBUG){ echo"$aux_id - $aux_rut - Hora ultima conexion:$aux_ultima_conexion - Hora ultima conexion time:$aux_tiempo_conexion_time - hora-tiempo expira time:$tiempo_expira - fecha-hora actual time:$fecha_hora_actual_time<br>";}
			
			if($fecha_hora_actual_time>$tiempo_expira)
			{
				if(DEBUG){ echo"FUERA DEL TIEMPO<br>";}
				$cons_desconexion="UPDATE alumno SET estado_conexion='OFF' WHERE id='$aux_id' LIMIT 1";
				if(DEBUG){ echo"DESCONEXION FORZADA: $cons_desconexion<br>";}
				$conexion_mysqli->query($cons_desconexion)or die("desconexion forzada".$conexion_mysqli->error);
			}
			else
			{
				if(DEBUG){ echo"DENTRO DEL TIEMPO<br>";}
				$array_activos[]=$aux_rut;
			}
		}
	}
	else
	{
		//no hay usuario activos
		if(DEBUG){ echo"No hay Alumnos Activos<br>";}
		$array_activos[]="No hay Alumnos";
	}
	$conexion_mysqli->close();
	return($array_activos);
}
//registro de eventos solo alumnos
function REGISTRO_EVENTO_ALUMNO($id_alumno, $tipo_registro, $descripcion)
{
	require("conexion_v2.php");
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	$fecha_hora_actual=date("Y-m-d H:i:s");
	
	$cons_IN="INSERT INTO alumno_registros (id_alumno, tipo_registro, descripcion, fecha_generacion, cod_user) VALUES ('$id_alumno', '$tipo_registro', '$descripcion', '$fecha_hora_actual', '$id_usuario_actual')";
	if(DEBUG){ echo "$cons_IN<br>";}
	else
	{ 
		if($conexion_mysqli->query($cons_IN))
		{}
		else
		{ echo"REGISTRO_EVENTO_ALUMNO ".$conexion_mysqli->error;}
	}
	$conexion_mysqli->close();
}
function REGISTRO_EVENTO_FUNCIONARIO($id_funcionario, $tipo_registro, $descripcion)
{
	require("conexion_v2.php");
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	$fecha_hora_actual=date("Y-m-d H:i:s");
	
	$cons_IN="INSERT INTO personal_registros (id_funcionario, tipo_registro, descripcion, fecha_generacion, cod_user) VALUES ('$id_funcionario', '$tipo_registro', '$descripcion', '$fecha_hora_actual', '$id_usuario_actual')";
	if(DEBUG){ echo "$cons_IN<br>";}
	else
	{ 
		if($conexion_mysqli->query($cons_IN)){}
		else{ echo"REGISTRO_EVENTO_FUNCIONARIO ".$conexion_mysqli->error;}
	}
	$conexion_mysqli->close();
}
?>