<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//-----------------------------------------//
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("carga_descarga_tareas_docente_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//-----------------------------------------//	
//////////////////////XAJAX/////////////////
@require_once ("../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("conceder_denegar_permisos.php");
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"PERMISOS");
//------------------------------------------------------------//
if($_GET)
{
	if(isset($_GET["id_trabajo"]))
	{
		$id_trabajo=base64_decode($_GET["id_trabajo"]);
		if(is_numeric($id_trabajo))
		{ $continuar=true;}
		else
		{ $continuar=false;}
	}
	else
	{ $continuar=false;}
}
else
{$continuar=false;}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php $xajax->printJavascript(); ?> 
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css"/>
<title>comprobar permisos</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:63px;
	z-index:1;
	left: 5%;
	top: 194px;
}
#apDiv2 {
	position:absolute;
	width:50%;
	height:100px;
	z-index:2;
	left: 5%;
	top: 58px;
}
#div_informacion {
	position:absolute;
	width:40%;
	height:98px;
	z-index:3;
	left: 55%;
	top: 58px;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Comprobar Permisos</h1>
<div id="apDiv1">
<?php if($continuar){?>
<table width="100%" border="1" align="left">
<thead>
  <tr>
    <th colspan="4">Permisos</th>
    </tr>
    </thead>
    <tbody>
 <?php
 	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/funciones_sistema.php");
	//--------------------------------------------//
	//datos modulo
	$cons_M="SELECT nombre FROM tareas_docente WHERE id='$id_trabajo' AND tipo='trabajo' LIMIT 1";
	$sqli_M=$conexion_mysqli->query($cons_M);
	$M=$sqli_M->fetch_assoc();
		$M_nombre=$M["nombre"];
	$sqli_M->free();	
	
	//---------------------------------------------//
	$year_actual=date("Y");
	$mes_actual=date("m");
	if($mes_actual>=8){$semestre_actual=2;}
	else{ $semestre_actual=1;}
 	$cons="SELECT * FROM personal WHERE nivel >=1 AND con_acceso='ON' ORDER by apellido_P, apellido_M";
	$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$num_usuario=$sqli->num_rows;
	$num_usuarios_con_permiso=0;
	if($num_usuario>0)
	{
		$aux=0;
		while($U=$sqli->fetch_assoc())
		{
			$aux++;
			$U_id=$U["id"];
			$U_nombre=$U["nombre"];
			$U_apellido_P=$U["apellido_P"];
			$U_apellido_M=$U["apellido_M"];
			$U_nivel=$U["nivel"];
			$U_sede=$U["sede"];
			$U_privilegio=TIPO_FUNCIONARIO_X_NIVEL($U_nivel);
			list($es_jefe_de_carrera, $array_carreras)=ES_JEFE_DE_CARRERA($U_id, $semestre_actual, $year_actual, $U_sede);
			if($es_jefe_de_carrera){ $U_privilegio="jefe_carrera";}
			////--------------------------------------------------------//
				$cons_P="SELECT COUNT(id_trabajo) FROM tareas_docente_permisos WHERE id_usuario='$U_id' AND id_trabajo='$id_trabajo'";
				$sqli_P=$conexion_mysqli->query($cons_P);
				$RUA=$sqli_P->fetch_row();
					$coincidencias=$RUA[0];
					if(empty($coincidencias)){ $coincidencias=0;}
				$sqli_P->free();
				if($coincidencias>0){ $tiene_permiso=true; $num_usuarios_con_permiso++;}
				else{ $tiene_permiso=false; }
				
				if($tiene_permiso){$check='<a href="#" onclick="xajax_PERMISOS(0, '.$U_id.', '.$id_trabajo.'); return false;">OK</a>';}
				else{$check='<a href="#" onclick="xajax_PERMISOS(1, '.$U_id.', '.$id_trabajo.'); return false;">X</a>';}
			//-----------------------------------------------------------//
			echo'<tr>
					<td>'.$aux.'</td>
					<td>'.$U_nombre.' '.$U_apellido_P.' '.$U_apellido_M.'</td>
					<td>'.$U_privilegio.'</td>
					<td align="center"><div id="div_'.$U_id.'">'.$check.'</div></td>
				</tr>';
		}
	}
	else
	{}
	$sqli->free();
	$conexion_mysqli->close();
 ?>
  </tbody>
</table>

</div>
<div id="apDiv2">
  <table width="100%" border="1" align="left">
  <thead>
    <tr>
      <th colspan="2">Informacion</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="36%">Id Modulo</td>
      <td width="64%"><?php echo $id_trabajo;?></td>
    </tr>
    <tr>
      <td>Nombre Modulo</td>
      <td><?php echo $M_nombre;?></td>
    </tr>
     <tr>
      <td width="36%">N. Usuarios con permiso</td>
      <td width="64%"><?php echo "$num_usuarios_con_permiso / $num_usuario";?> (si hay &quot;0&quot; usuario con pemiso entonces es acceso libre a todos)</td>
    </tr>
    </tbody>
  </table>
</div>
<?php }?>
<div id="div_informacion"></div>
</body>
</html>