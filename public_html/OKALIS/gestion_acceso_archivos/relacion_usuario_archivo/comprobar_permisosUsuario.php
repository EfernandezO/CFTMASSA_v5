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
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"PERMISOS");
//------------------------------------------------------------//
if($_GET)
{
	if(isset($_GET["idUsuario"]))
	{
		$idUsuario=base64_decode($_GET["idUsuario"]);
		if(is_numeric($idUsuario))
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
	top: 169px;
}
#apDiv2 {
	position:absolute;
	width:50%;
	height:100px;
	z-index:2;
	left: 5%;
	top: 62px;
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
<h1 id="banner">Administrador - Comprobar Permisos de Modulo</h1>
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

	$year_actual=date("Y");
	$mes_actual=date("m");
	if($mes_actual>=8){$semestre_actual=2;}
	else{ $semestre_actual=1;}
 	$cons="SELECT id_archivo, nombre_modulo FROM okalis_archivos ORDER by categoria, nombre_modulo";
	$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$num_usuario=$sqli->num_rows;
	if($num_usuario>0)
	{
		$aux=0;
		while($U=$sqli->fetch_assoc())
		{
			$aux++;
			$P_id=$U["id_archivo"];
			$P_nombre=$U["nombre_modulo"];
			
			////--------------------------------------------------------//
				$cons_P="SELECT COUNT(id_usuario) FROM okalis_relacion_usuario_archivo WHERE id_archivo='$P_id' AND id_usuario='$idUsuario'";
				$sqli_P=$conexion_mysqli->query($cons_P);
				$RUA=$sqli_P->fetch_row();
					$coincidencias=$RUA[0];
					if(empty($coincidencias)){ $coincidencias=0;}
				$sqli_P->free();
				if($coincidencias>0){ $tiene_permiso=true;}
				else{ $tiene_permiso=false;}
				
				if($tiene_permiso){$check='<a href="#" onclick="xajax_PERMISOS(0, '.$idUsuario.', '.$P_id.', \'archivo\'); return false;">OK</a>';}
				else{$check='<a href="#" onclick="xajax_PERMISOS(1, '.$idUsuario.', '.$P_id.', \'archivo\'); return false;">X</a>';}
			//-----------------------------------------------------------//
			echo'<tr>
					<td>'.$aux.'</td>
					<td>'.$P_nombre.'</td>
					<td align="center"><div id="div_'.$P_id.'">'.$check.'</div></td>
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
      <td width="36%">Usuario</td>
      <td width="64%"><?php echo NOMBRE_PERSONAL($idUsuario);?></td>
    </tr>
   
    </tbody>
  </table>
</div>
<?php }?>
<div id="div_informacion"></div>
</body>
</html>