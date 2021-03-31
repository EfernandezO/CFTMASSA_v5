<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("REGISTRO_FUAS");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

 //////////////////////XAJAX/////////////////
	@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
	$xajax = new xajax("registros_FUAS_server.php");
	$xajax->configure('javascript URI','../../libreria_publica/xajax/');
	$xajax->register(XAJAX_FUNCTION,"REGISTRA_FUAS");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php"); ?>
<title>Registro postulacion FUAS</title>
   <?php $xajax->printJavascript(); ?> 
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:60%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 291px;
}
</style>
<script language="javascript" type="text/javascript">
function CONFIRMAR(id_alumno, year, estado)
{
	//alert(id_alumno+" "+year+" "+estado);
	c=confirm('Modificar...?');
	
	if(c){xajax_REGISTRA_FUAS(id_alumno, year, estado);}
}
</script>
</head>
<?php
//datos alumno
$continuar=false;
if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
{
	require("../../../funciones/conexion_v2.php");
	$continuar=true;
	
	$year_actual=date("Y");
	
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	$carrera=$_SESSION["SELECTOR_ALUMNO"]["carrera"];
	$jornada=$_SESSION["SELECTOR_ALUMNO"]["jornada"];
	$year_ingreso=$_SESSION["SELECTOR_ALUMNO"]["ingreso"];
	$sede=$_SESSION["SELECTOR_ALUMNO"]["sede"];
	$nivel_alumno=$_SESSION["SELECTOR_ALUMNO"]["nivel"];
	
	$nombre_alumno=$_SESSION["SELECTOR_ALUMNO"]["nombre"]." ".$_SESSION["SELECTOR_ALUMNO"]["apellido"];
	
	$cons="SELECT MAX(ano) FROM contratos2 WHERE id_alumno='$id_alumno' LIMIT 1";
	$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$D=$sqli->fetch_row();
		$year_final=$D[0];
		$year_final+=1;
	$sqli->free();	
	
}

?>
<body>
<h1 id="banner">Administrador - Registros FUAS</h1>
<div id="link"><a href="../../buscador_alumno_BETA/HALL/index.php" class="button">Volver</a></div>
<div id="apDiv1">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="7">Indique si postula o renueva BNM o BET</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td >Año</td>
      <td >Postula</td>
      <td >Renueva</td>
      <td >No postula ni renueva</td>
      <td >Tipo Usuario Registra</td>
      <td >Usuario Registra</td>
      <td>Fecha Registro</td>
    </tr>
    
    <?php
    for($Y=$year_ingreso;$Y<=$year_final;$Y++)
	{
		if($Y==$year_actual){ $color="#AFA";}
		else{$color="";}
		
		///Buscar Registros en BBDD
		
		$consF="SELECT * FROM registros_FUAS WHERE id_alumno='$id_alumno' AND year='$Y' LIMIT 1";
		$sqli_F=$conexion_mysqli->query($consF)or die($conexion_mysqli->error);
		$DF=$sqli_F->fetch_assoc();
			$F_estado_fuas=$DF["estado_fuas"];
			$F_cod_user=$DF["cod_user"];
			$F_fecha_generacion=$DF["fecha_generacion"];
			$F_tipo_user=$DF["tipo_usuario"];
		$sqli_F->free();
		
		
				$check_1='';
				$check_2='';
				$check_3='';
		switch($F_estado_fuas)	
		{
			case"postula":
				$check_1='checked="checked"';
				break;
			case"renueva":
				$check_2='checked="checked"';
				break;
			case"NPR":
				$check_3='checked="checked"';
				break;
		}
		
		
		echo'<tr>
				<td bgcolor="'.$color.'">'.$Y.'</td>
				<td bgcolor="'.$color.'"><input type="radio" name="estado_FUAS['.$Y.']" id="radio" value="postula" '.$check_1.' onclick="CONFIRMAR(\''.$id_alumno.'\', \''.$Y.'\', this.value)"/></td>
				<td bgcolor="'.$color.'"><input type="radio" name="estado_FUAS['.$Y.']" id="radio2" value="renueva" '.$check_2.' onchange="CONFIRMAR(\''.$id_alumno.'\', \''.$Y.'\', this.value)"/></td>
				<td bgcolor="'.$color.'"><input name="estado_FUAS['.$Y.']" type="radio" id="radio3" value="NPR" '.$check_3.' onchange="CONFIRMAR(\''.$id_alumno.'\', \''.$Y.'\', this.value)"/></td>
				<td bgcolor="'.$color.'"><div id="div_tipo_usuario_'.$Y.'">'.$F_tipo_user.'</div></td>
				<td bgcolor="'.$color.'"><div id="div_usuario_'.$Y.'">'.$F_cod_user.'</div></td>
				<td bgcolor="'.$color.'"><div id="div_fecha_'.$Y.'">'.$F_fecha_generacion.'</div></td>
			 </tr>';
		
	}
	?>
    
    </tbody>
  </table><br />
<div id="div_resultado">
  
  ...</div>
</div>
<div id="Layer1" style="position:absolute; left:5%; top:108px; width:50%; height:165px; z-index:1">
  <table width="100%" border="0" align="left">
    <thead>
      <tr>
        <th colspan="4">Datos Alumno</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td width="64"><strong>Carrera</strong></td>
        <td colspan="3"><?php echo "$carrera - Jornada: $jornada"; ?>
          <input name="id_carrera" type="hidden" value="<?php echo $id_carrera;?>" /></td>
      </tr>
      <tr>
        <td width="64"><strong>Alumno</strong></td>
        <td colspan="3"><?php echo $nombre_alumno; ?>
          <input type="hidden" name="id_alumno" id="id_alumno" value="<?php  echo $id_alumno;?>" /></td>
      </tr>
      <tr>
        <td>Nivel</td>
        <td width="168"><?php echo $nivel_alumno;?>
          <input name="nivel_alumno" type="hidden" id="nivel_alumno" value="<?php echo $nivel_alumno;?>" /></td>
        <td width="191">Ingreso</td>
        <td width="70"><?php echo $year_ingreso;?></td>
      </tr>
      
    </tbody>
  </table>
  <p><br />
  </p>
  
  <p>&nbsp;</p>
</div>
</body>
</html>