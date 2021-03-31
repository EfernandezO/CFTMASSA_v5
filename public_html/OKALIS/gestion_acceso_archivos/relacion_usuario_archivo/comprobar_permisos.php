<?php
//-----------------------------------------//
//--------------CLASS_okalis------------------//
	require("../../class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Permiso_acceso_a_modulos");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//-----------------------------------------//	
//////////////////////XAJAX/////////////////
@require_once ("../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("conceder_denegar_permisos.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"PERMISOS");
$xajax->register(XAJAX_FUNCTION,"PERMISOS_ROLES");
//------------------------------------------------------------//
if($_GET)
{
	if(isset($_GET["id_archivo"]))
	{
		$id_archivo=base64_decode($_GET["id_archivo"]);
		if(is_numeric($id_archivo))
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
<title>okalis comprobar permisos</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:63px;
	z-index:1;
	left: 5%;
	top: 343px;
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
	left: 5%;
	top: 170px;
}
#apDiv3 {
	position:absolute;
	width:40%;
	height:76px;
	z-index:4;
	left: 55%;
	top: 58px;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Comprobar Permisos de Modulo</h1>
<div id="apDiv1">
<?php if($continuar){?>
<table width="100%" border="1" align="left">
<thead>
  <tr>
    <th colspan="4">Permisos por USUARIO</th>
    </tr>
    </thead>
    <tbody>
 <?php
 	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/funciones_sistema.php");
	//--------------------------------------------//
	//datos modulo
	$cons_M="SELECT nombre_modulo FROM okalis_archivos WHERE id_archivo='$id_archivo' LIMIT 1";
	$sqli_M=$conexion_mysqli->query($cons_M);
	$M=$sqli_M->fetch_assoc();
		$M_nombre_modulo=$M["nombre_modulo"];
	$sqli_M->free();	
	
	//---------------------------------------------//
	$year_actual=date("Y");
	$mes_actual=date("m");
	if($mes_actual>=8){$semestre_actual=2;}
	else{ $semestre_actual=1;}
 	$cons="SELECT * FROM personal WHERE nivel >=1 ORDER by apellido_P, apellido_M";
	$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$num_usuario=$sqli->num_rows;
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
				$cons_P="SELECT COUNT(id_archivo) FROM okalis_relacion_usuario_archivo WHERE id_usuario='$U_id' AND id_archivo='$id_archivo'";
				$sqli_P=$conexion_mysqli->query($cons_P);
				$RUA=$sqli_P->fetch_row();
					$coincidencias=$RUA[0];
					if(empty($coincidencias)){ $coincidencias=0;}
				$sqli_P->free();
				if($coincidencias>0){ $tiene_permiso=true;}
				else{ $tiene_permiso=false;}
				
				if($tiene_permiso){$check='<a href="#" onclick="xajax_PERMISOS(0, '.$U_id.', '.$id_archivo.'); return false;">OK</a>';}
				else{$check='<a href="#" onclick="xajax_PERMISOS(1, '.$U_id.', '.$id_archivo.'); return false;">X</a>';}
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
      <td width="64%"><?php echo $id_archivo;?></td>
    </tr>
    <tr>
      <td>Nombre Modulo</td>
      <td><?php echo $M_nombre_modulo;?></td>
    </tr>
    </tbody>
  </table>
</div>
<div id="apDiv3">
  <table width="80%" border="1" align="right">
  <thead>
    <tr>
      <th colspan="3">Permisos por ROL</th>
    </tr>
    </thead>
    <tbody>
    <tr>
    	<td>ID</td>
        <td>ROL</td>
        <td>PERMISO</td>
    </tr>
    <?php
	$consR="SELECT * FROM usuarioRoles";
	$sqliR=$conexion_mysqli->query($consR);
	$numRoles=$sqliR->num_rows;
	if($numRoles>0){
		while($DR=$sqliR->fetch_assoc()){
			$aux_idROL=$DR["id_rol"];
			$aux_nombreRol=$DR["nombreRol"];
			
			////--------------------------------------------------------//
				$cons_P="SELECT COUNT(id_archivo) FROM okalis_relacion_rol_archivo WHERE id_rol='$aux_idROL' AND id_archivo='$id_archivo'";
				$sqli_P=$conexion_mysqli->query($cons_P);
				$RUA=$sqli_P->fetch_row();
					$coincidencias=$RUA[0];
					if(empty($coincidencias)){ $coincidencias=0;}
				$sqli_P->free();
				if($coincidencias>0){ $tiene_permiso=true;}
				else{ $tiene_permiso=false;}
				
				$permisoLabel='<a href="#" onclick="xajax_PERMISOS_ROLES(1, '.$aux_idROL.', '.$id_archivo.'); return false;">X</a>';
				if($tiene_permiso){$permisoLabel='<a href="#" onclick="xajax_PERMISOS_ROLES(0, '.$aux_idROL.', '.$id_archivo.'); return false;">OK</a>';}
			
			echo' <tr>
				<td>'.$aux_idROL.'</td>
				<td>'.$aux_nombreRol.'</td>
				<td><div id="div_R'.$aux_idROL.'">'.$permisoLabel.'</div></td>
			</tr>';
		}
	}
    $sqliR->free();
	?>
    
    </tbody>
  </table>
</div>
<?php }?>
<div id="div_informacion"></div>

</body>
</html>
<?php 	$conexion_mysqli->close();?>
