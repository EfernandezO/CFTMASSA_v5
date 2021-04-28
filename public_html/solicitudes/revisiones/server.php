<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="ALUMNO";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	
if(isset($_SESSION["SELECTOR_ALUMNO"]))
{unset($_SESSION["SELECTOR_ALUMNO"]);}
//-----------------------------------------//	
$privilegio=$_SESSION["USUARIO"]["privilegio"];
$sede_usuario=$_SESSION["USUARIO"]["sede"];
$id_usuario_actual=$_SESSION["USUARIO"]["id"];

$fecha_actual=date("Y-m-d");
$fecha_limite=date("Y-m-d", strtotime("$fecha_actual -10 days"));
require("../../../funciones/conexion_v2.php");

$pagina = mysqli_real_escape_string($conexion_mysqli, $_GET['pagina']);
		
		switch($privilegio)
		{
			case"admi_total":
				$condicion_solicitud="";
				$ver_boton_opciones=true;
				$msj_solicitudes="[todas]";
				break;
			case"matricula":
				//$condicion_solicitud="WHERE sede_receptor='$sede_usuario' AND autorizado='no' AND fecha_hora_solicitud>= '$fecha_limite'";
				$condicion_solicitud="WHERE sede_receptor='$sede_usuario' AND autorizado='no'";
				$ver_boton_opciones=true;
				$msj_solicitudes="[no autorizadas]";
				break;
			case"admi":
				//$condicion_solicitud="WHERE sede_receptor='$sede_usuario' AND autorizado='si' AND estado='pendiente' OR fecha_hora_creacion>='$fecha_limite'";
				$condicion_solicitud="WHERE sede_receptor='$sede_usuario' AND autorizado='si' AND estado='pendiente'";
				$ver_boton_opciones=true;
				$msj_solicitudes="[pendientes]";
				break;
			case"ALUMNO":
				$condicion_solicitud="WHERE id_receptor='$id_usuario_actual' AND sede_receptor='$sede_usuario' AND estado='pendiente'";
				$ver_boton_opciones=false;
				$msj_solicitudes="[pendientes]";
				break;
			default:
				$ver_boton_opciones=false;
				$msj_solicitudes=" ";
		}
		
		
		
		$pag_inicio=(($pagina-1)*20);
		$limite=20;
		
		if($pagina>1){$pag_inicio+=1; $aux=(($pagina-1)*20);}
		else{$aux=0;}
		$pag_fin=($pagina*20);
		
		$cons="SELECT * FROM solicitudes  $condicion_solicitud ORDER by id desc LIMIT $pag_inicio, $limite";
		$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		$num_solcitudes=$sqli->num_rows;
		if(DEBUG){ echo"--->$cons<br>num solicitudes: $num_solcitudes<br>";}
		if($num_solcitudes>0)
		{
			$path="../../CONTENEDOR_GLOBAL/solicitudes_comprobantes/";
			
			while($S=$sqli->fetch_assoc())
			{
				$aux++;
				
				$S_id=$S["id"];
				$S_tipo=$S["tipo"];
				$S_categoria=$S["categoria"];
				$S_tipo_solicitante=strtolower($S["tipo_solicitante"]);
				$S_fecha_hora_solicitud=$S["fecha_hora_solicitud"];
				$S_id_solicitante=$S["id_solicitante"];
				$S_id_carrera_solicitante=$S["id_carrera_solicitante"];
				
				$S_tipo_receptor=$S["tipo_receptor"];
				$S_id_receptor=$S["id_receptor"];
				$S_id_carrera_receptor=$S["id_carrera_receptor"];
				
				$S_id_autorizador=$S["id_autorizador"];
				$S_tipo_autorizador=$S["tipo_autorizador"];
				$S_fecha_hora_autorizacion=$S["fecha_hora_autorizacion"];
				$S_metodo_autorizacion=$S["metodo_autorizacion"];
				$S_autorizado=$S["autorizado"];
				$S_archivo_autorizacion=$S["archivo_autorizacion"];
				
				if(empty($S_archivo_autorizacion))
				{ $archivo_label='<a href="#" class="hint--left  hint--error" data-hint="No tiene Archivo Cargado...">No</a>';}
				else{ $archivo_label='<a href="'.$path.$S_archivo_autorizacion.'" class="hint--left  hint--error" data-hint=" Tiene Archivo Cargado..." target="_blank">Si</a>';}
				
				$S_tipo_creador=$S["tipo_creador"];
				$S_id_creador=$S["id_creador"];
				$S_fecha_hora_creacion=$S["fecha_hora_creacion"];
				$S_estado=$S["estado"];
				
				
				switch($S_estado)
				{
					case"pendiente":
						$color_1="FFAAAA";
						break;
					case"generada":
						$color_1='#AAFFAA';
						break;	
				}
				//identifica donde enviara segun estado autorizacion
				$validador=md5("GDXT".date("d-m-Y"));
				switch($S_tipo_receptor)
				{
					case"alumno":
						if($S_autorizado=="si")
						{$url='../../buscador_alumno_BETA/enrutador.php?validador='.$validador.'&id_alumno='.$S_id_receptor.'&url='.base64_encode("../solicitudes/generacion_documentos/redireccion.php?id_solicitud=$S_id").'"'; $target="_blank";}
						else{ $url='../../buscador_alumno_BETA/enrutador.php?validador='.$validador.'&id_alumno='.$S_id_receptor.'&url='.base64_encode("../solicitudes/operaciones/autorizacion_financiera_1.php?id_solicitud=$S_id").'"'; $target="";}
						////////////////////
						//datos del alumno
						$nombre_receptor="";
						if($S_id_receptor>0){
							$cons_A="SELECT * FROM alumno WHERE id='$S_id_receptor' LIMIT 1";
							$sql_A=$conexion_mysqli->query($cons_A);
								$DA=$sql_A->fetch_assoc();
								if(isset($DA["nombre"])){
								$nombre_receptor=$DA["nombre"]." ".$DA["apellido_P"]." ".$DA["apellido_M"];}
							$sql_A->free();
						}
						//datos carrera
						$cons_C="SELECT carrera FROM carrera WHERE id='$S_id_carrera_receptor' LIMIT 1";
						
						$sql_C=$conexion_mysqli->query($cons_C);
							$DC=$sql_C->fetch_assoc();
							$carrera_receptor=$DC["carrera"];
						$sql_C->free();
						break;
					default:
						$url="#";
						$carrera_receptor="";	
						$cons_r="SELECT nombre,apellido FROM personal WHERE id='$S_id_receptor' LIMIT 1";
						$sql_r=$conexion_mysqli->query($cons_r);
							$DP=$sql_r->fetch_assoc();
							$nombre_receptor=$DP["nombre"]." ".$DP["apellido"];
						$sql_r->free();
				}
				
				///datos personal solicitante
				switch($S_tipo_solicitante)
				{
					case"alumno":
						$cons_A="SELECT * FROM alumno WHERE id='$S_id_solicitante' LIMIT 1";
						$sql_A=$conexion_mysqli->query($cons_A);
							$DA=$sql_A->fetch_assoc();
							$nombre_solicitante=$DA["nombre"]." ".$DA["apellido_P"]." ".$DA["apellido_M"];
						$sql_A->free();
						break;
					default:
						$cons_p="SELECT nombre,apellido FROM personal WHERE id='$S_id_solicitante' LIMIT 1";
						$sql_p=$conexion_mysqli->query($cons_p);
							$DP=$sql_p->fetch_assoc();
							$nombre_solicitante=$DP["nombre"]." ".$DP["apellido"];
						$sql_p->free();		
				}
				////////////////////
				//datos personal autorizador
				$nombre_autorizador="";
				if($S_id_autorizador>0){
				$cons_p="SELECT nombre,apellido FROM personal WHERE id='$S_id_autorizador' LIMIT 1";
						$sql_p=$conexion_mysqli->query($cons_p);
							$DP=$sql_p->fetch_assoc();
							if(isset($DP["nombre"]))
							{$nombre_autorizador=$DP["nombre"]." ".$DP["apellido"];}
						$sql_p->free();
				}
				///////////////////////////////////////////
				//datos personal creador
				$nombre_creador="";
				if($S_id_creador>0){
				$cons_p="SELECT nombre,apellido FROM personal WHERE id='$S_id_creador' LIMIT 1";
						$sql_p=$conexion_mysqli->query($cons_p);
							$DP=$sql_p->fetch_assoc();
							if(isset($DP["nombre"])){$nombre_creador=$DP["nombre"]." ".$DP["apellido"];}
						$sql_p->free();
				}
				
				echo'<tr height="35">
						<td>'.$aux.'</td>
						<td><a href="#" class="hint--right  hint--warning" data-hint="Solicitada el: '.$S_fecha_hora_solicitud.' por '.$S_tipo_solicitante.' ['.$nombre_solicitante.']">'.$S_tipo.'</a></td>
						<td>'.$S_categoria.'</td>
						<td>'.$S_tipo_receptor.'</td>
						<td>'.$nombre_receptor.'</td>
						<td>'.$carrera_receptor.'</td>
						<td><a href="#" class="hint--left  hint--error" data-hint="Autorizada el: '.$S_fecha_hora_autorizacion.' por '.$S_tipo_autorizador.' ['.$nombre_autorizador.']">'.$S_autorizado.'</a></td>
						<td>'.$archivo_label.'</td>
						<td bgcolor="'.$color_1.'"><a href="#" class="hint--left  hint--success" data-hint="Generada el '.$S_fecha_hora_creacion.' por '.$S_tipo_creador.' ['.$nombre_creador.']">'.$S_estado.'</a></td>
						<td>';
						if($ver_boton_opciones){ echo'<a href="'.$url.'" class="button" target="'.$target.'">Revisar</a>';}
						else{ echo'&nbsp;';}
					echo'</td>
					 </tr>';
			}
		}
		else
		{ echo'<tr><td colspan="9">Sin Solicitudes Pendientes...</td></tr>';}
		$sqli->free();
		$conexion_mysqli->close();
?>