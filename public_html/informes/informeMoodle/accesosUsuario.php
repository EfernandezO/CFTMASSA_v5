<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informesMoodle");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//	
/*
SELECT COUNT( cm.id ) AS counter, m.name
FROM `prefix_course_modules` AS cm
JOIN prefix_modules AS m ON cm.module = m.id
GROUP BY cm.module
ORDER BY counter DESC

SELECT cm.added, m.name, c.shortname shortname ,c.fullname coursename ,( SELECT DISTINCT CONCAT(u.firstname,' ',u.lastname) FROM mdl_role_assignments AS ra JOIN mdl_user AS u ON ra.userid = u.id JOIN mdl_context AS ctx ON ctx.id = ra.contextid WHERE ra.roleid = 3 AND ctx.instanceid = cm.course AND ctx.contextlevel = 50 LIMIT 1) AS Teacher
FROM `mdl_course_modules` AS cm
JOIN mdl_modules AS m ON cm.module = m.id
WHERE cm.course=252
*/
require("../../../funciones/conexionMoodlev1.php");


$cons="SELECT mdl_user.id, mdl_user.username, mdl_user.firstname, mdl_user.lastname, mdl_user.lastaccess, mdl_user.lastlogin FROM mdl_user ORDER by mdl_user.id";

if(DEBUG){}
	else
	{
			header('Content-type: application/vnd.ms-excel');
			header("Content-Disposition: attachment; filename=moodleUsuarioFullAccesos".date("d-m-Y").".xls");
			header("Pragma: no-cache");
			header("Expires: 0");
	}
$tablaDetalle='<table border="1">
				<tr bgcolor="#66FF99">
					<td>N.</td>
					<td>username</td>
					<td>Nombre</td>
					<td>Apellido</td>
					<td>ultimo acceso</td>
					<td>ultimo login</td>
					<td>acceso</td>
				</tr>';
				
$ARRAY_RESUMEN=array();		
$ARRAY_MESES=array();
$ARRAY_USUARIOSxMES=array();
$ARRAY_HORA_USO=array();
$sqli=$conexion_mysqliMoodle->query($cons)or die($conexion_mysqliMoodle->error);
$aux=0;
while($C=$sqli->fetch_assoc()){
	$aux++;
	$mostrar=false;
	$Uid=$C["id"];
	$Uusername=$C["username"];
	$Ufirstname=$C["firstname"];
	$Ulastname=$C["lastname"];
	
	$UultimoAcceso=$C["lastaccess"];
	if($UultimoAcceso!=0){ $UultimoAcceso=date("d/m/Y h:i:s",$UultimoAcceso);}
	
	$Uultimologin=$C["lastlogin"];
	if($Uultimologin!=0){$Uultimologin=date("d/m/Y h:i:s",$Uultimologin);}
	
	$consLog="SELECT mdl_logstore_standard_log.timecreated FROM mdl_logstore_standard_log WHERE mdl_logstore_standard_log.action='loggedin' AND mdl_logstore_standard_log.objectid='$Uid' ORDER by mdl_logstore_standard_log.timecreated";
	
	//echo $consLog."<br>";
	$sqliLog=$conexion_mysqliMoodle->query($consLog)or die("LOG ".$conexion_mysqliMoodle->error);
	$primerIngreso=true;
	while(($L=$sqliLog->fetch_assoc())or($primerIngreso)){
		$primerIngreso=false;
		if(isset($L["timecreated"])){$horaConexion=date("H",$L["timecreated"]); $UAccesoX=date("d/m/Y H:i:s",$L["timecreated"]); $mesYearAcceso=date("m/Y",$L["timecreated"]);}else{ $UAccesoX=0; $mesYearAcceso="0/0"; $horaConexion="";}
		
		
		
		$mostrar=true;
	
		if($mostrar){
		$tablaDetalle.='<tr>
				<td>'.$aux.'</td>
				<td>'.$Uusername.'</td>
				<td>'.$Ufirstname.'</td>
				<td>'.$Ulastname.'</td>
				<td>'.$UultimoAcceso.'</td>
				<td>'.$Uultimologin.'</td>
				<td>'.$UAccesoX.'</td>
			 </tr>';
			 
			 //lleno array con todos los recursos diferentes
			 //lleno array con resumen
			 $ARRAY_RESUMEN[$Uid]["nombre"]=$Ufirstname." ".$Ulastname;
			 $ARRAY_RESUMEN[$Uid]["username"]=$Uusername;
			 
			 //contabilizo en que hora del dia hay mas acceso
			if(isset($ARRAY_HORA_USO[$horaConexion])){$ARRAY_HORA_USO[$horaConexion]+=1;}
			else{$ARRAY_HORA_USO[$horaConexion]+=1;}
			
			//cantidad de usuarios unicos x mes
			if(!isset($ARRAY_USUARIOSxMES[$mesYearAcceso][$Uid])){ $ARRAY_USUARIOSxMES[$mesYearAcceso][$Uid]=true; if(DEBUG){ echo"No  $Uid registrado en este mes [$mesYearAcceso]<br>"; var_dump($ARRAY_USUARIOSxMES); echo"<br>";}}
			else{ if(DEBUG){echo"$Uid ya esta registrado en este mes...[$mesYearAcceso]<br>";}}
			 
			 if(!isset($ARRAY_MESES[$mesYearAcceso])){ $ARRAY_MESES[$mesYearAcceso]=true;}
			 
			 
			 if(isset($ARRAY_RESUMEN[$Uid][$mesYearAcceso])){$ARRAY_RESUMEN[$Uid][$mesYearAcceso]+=1;}
			 else{ $ARRAY_RESUMEN[$Uid][$mesYearAcceso]=1;}
			 
			 
		}
	}
	$sqliLog->free();
	
}
		 
$sqli->free();
$conexion_mysqliMoodle->close();		

if(isset($ARRAY_MESES["0/0"])){unset($ARRAY_MESES["0/0"]);}
 
$tablaDetalle.='</table>';	

echo $tablaDetalle;

if(count($ARRAY_RESUMEN)>0){
	
	$tablaResumen='<table border="1">
					<tr bgcolor="#FF6600">
						<td>Usuario</td>
						<td>Username</td>';
	foreach($ARRAY_MESES as $auxMes =>$valor){ $tablaResumen.='<td>'.$auxMes.'</td>';}
	$tablaResumen.='</tr>';					
	foreach($ARRAY_RESUMEN as $auxUid =>$auxArray){
		
		$tablaResumen.='<tr>
							<td>'.$auxArray["nombre"].'</td>
							<td>'.$auxArray["username"].'</td>';
		foreach($ARRAY_MESES as $auxMes =>$valor){
			if(isset($auxArray[$auxMes])){$auxCantidad=$auxArray[$auxMes];}
			else{$auxCantidad=0;}
			
			$tablaResumen.='<td>'.$auxCantidad.'</td>';
		}
		$tablaResumen.='</tr>';
	}
	$tablaResumen.='</table>';
}
echo"<br>Resumen Accesos por Mes/Año<br>";
echo $tablaResumen;


//tabla con horas y cantidad de accesos
$tablaHoras='<table border="1">
					<tr bgcolor="#FF66AA">
						<td>Hora</td>
						<td>Numero Usuarios (accesos unicos x usuario)</td>
					</tr>';
					
sort($ARRAY_HORA_USO);					
foreach($ARRAY_HORA_USO as $auxHora => $AuxAcceso){
	if(!empty($auxHora)){
		$tablaHoras.='<tr>
			<td>'.$auxHora.'</td>
			<td>'.$AuxAcceso.'</td>
		</tr>';
	}
}
$tablaHoras.='</table>';

echo"<br>Resumen Horas y cantidad de accesos<br>";
echo $tablaHoras;


//tabla acceso por mes unicos x usuario
$tablaUsuarioMes='<table border="1">
					<tr bgcolor="#AA99AA">
						<td>Mes /año</td>
						<td>Numero Usuarios (accesos unicos x usuario)</td>
					</tr>';
					
sort($ARRAY_HORA_USO);					
foreach($ARRAY_USUARIOSxMES as $auxMesyear => $array_AuxNumAccesoUsuario){
	$AuxNumAccesoUsuario=count($array_AuxNumAccesoUsuario);
	
		$tablaUsuarioMes.='<tr>
			<td>'.$auxMesyear.'</td>
			<td>'.$AuxNumAccesoUsuario.'</td>
		</tr>';
}
$tablaUsuarioMes.='</table>';

echo"<br>Resumen usuarios que ingresan por mes/año<br>";
echo $tablaUsuarioMes;

echo"Generado el ".date("d-m-Y H:i:s");
?>