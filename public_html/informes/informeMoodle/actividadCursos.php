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
$cons="SELECT r.name,r.timemodified ,r.course ,c.shortname shortname ,c.fullname coursename ,( SELECT DISTINCT CONCAT(u.firstname,' ',u.lastname) FROM mdl_role_assignments AS ra JOIN mdl_user AS u ON ra.userid = u.id JOIN mdl_context AS ctx ON ctx.id = ra.contextid WHERE ra.roleid = 3 AND ctx.instanceid = r.course AND ctx.contextlevel = 50 LIMIT 1) AS Teacher FROM mdl_resource r JOIN mdl_course c ON r.course = c.id ";

$cons="SELECT c.id, c.shortname shortname ,c.fullname coursename ,cm.added, m.name,( SELECT DISTINCT CONCAT(u.firstname,' ',u.lastname) FROM mdl_role_assignments AS ra JOIN mdl_user AS u ON ra.userid = u.id JOIN mdl_context AS ctx ON ctx.id = ra.contextid WHERE ra.roleid = 3 AND ctx.instanceid = cm.course AND ctx.contextlevel = 50 LIMIT 1) AS Teacher
FROM `mdl_course_modules` AS cm
JOIN mdl_modules AS m ON cm.module = m.id
JOIN mdl_course AS c ON cm.course=c.id
ORDER by c.id";

if(DEBUG){}
	else
	{
			header('Content-type: application/vnd.ms-excel');
			header("Content-Disposition: attachment; filename=moodleActividadCursos".date("d-m-Y").".xls");
			header("Pragma: no-cache");
			header("Expires: 0");
	}
$tablaDetalle='<table border="1">
				<tr bgcolor="#66FF99">
					<td>id Curso</td>
					<td>Nombre Corto</td>
					<td>Nombre Completo</td>
					<td>Tipo recuros o actividad</td>
					<td>fecha Agregado</td>
					<td>profesor</td>
				</tr>';
				
$ARRAY_RESUMEN=array();		
$ARRAY_TIPO_RECURSO=array();	

$mostrarSoloCursosConProfesor=true;	

$sqli=$conexion_mysqliMoodle->query($cons)or die($conexion_mysqliMoodle->error);
while($C=$sqli->fetch_assoc()){
	$mostrar=false;
	$Rname=$C["name"];
	$Rfecha=date("d/m/Y h:i:s",$C["added"]);
	$Cid=$C["id"];
	$Cshorname=$C["shortname"];
	$Cfullname=utf8_decode($C["coursename"]);
	$Cprofesor=utf8_decode($C["Teacher"]);
	
	if($mostrarSoloCursosConProfesor){ if(empty($Cprofesor)){$mostrar=false;}else{$mostrar=true;}}
	else{$mostrar=true;}
	
	if($mostrar){
	$tablaDetalle.='<tr>
			<td>'.$Cid.'</td>
			<td>'.$Cshorname.'</td>
			<td>'.$Cfullname.'</td>
			<td>'.$Rname.'</td>
			<td>'.$Rfecha.'</td>
			<td>'.$Cprofesor.'</td>
		 </tr>';
		 
		 //lleno array con todos los recursos diferentes
		 if(isset($ARRAY_TIPO_RECURSO[$Rname])){$ARRAY_TIPO_RECURSO[$Rname]=true;}
		 else{$ARRAY_TIPO_RECURSO[$Rname]=true;}
		 
		 //lleno array con resumen
		 $ARRAY_RESUMEN[$Cid]["nombre"]=$Cfullname;
		 $ARRAY_RESUMEN[$Cid]["profesor"]=$Cprofesor;
		 if(isset($ARRAY_RESUMEN[$Cid][$Rname])){$ARRAY_RESUMEN[$Cid][$Rname]+=1;}
		 else{ $ARRAY_RESUMEN[$Cid][$Rname]=1;}
	}
}
		 
$sqli->free();
$conexion_mysqliMoodle->close();		 
$tablaDetalle.='</table>';	

echo $tablaDetalle;

if(count($ARRAY_RESUMEN)>0){
	
	$tablaResumen='<table border="1">
					<tr bgcolor="#FF6600">
						<td>Curso</td>
						<td>Profesor</td>';
	foreach($ARRAY_TIPO_RECURSO as $auxTipo =>$valor){ $tablaResumen.='<td>'.$auxTipo.'</td>';}
	$tablaResumen.='</tr>';					
	foreach($ARRAY_RESUMEN as $auxCid =>$auxArray){
		
		$tablaResumen.='<tr>
							<td>'.$auxArray["nombre"].'</td>
							<td>'.$auxArray["profesor"].'</td>';
		foreach($ARRAY_TIPO_RECURSO as $auxTipo =>$valor){
			if(isset($auxArray[$auxTipo])){$auxCantidad=$auxArray[$auxTipo];}
			else{$auxCantidad=0;}
			
			$tablaResumen.='<td>'.$auxCantidad.'</td>';
		}
		$tablaResumen.='</tr>';
	}
	
}
echo"<br>Resumen";
echo $tablaResumen;
?>