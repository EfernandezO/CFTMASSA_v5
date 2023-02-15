<?php
//--------------CLASS_okalis------------------//
	require("../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../funciones/";
	$O->clave_del_archivo=md5("revision_historial_general_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link rel="stylesheet" type="text/css" href="../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../CSS/tabla_2.css">
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:129px;
	height:15px;
	z-index:1;
	left: 499px;
	top: 66px;
}
a:link {
	color: #6699FF;
	text-decoration: none;
}
a:visited {
	color: #6699FF;
	text-decoration: none;
}
a:hover {
	color: #FF0000;
	text-decoration: underline;
}
a:active {
	color: #6699FF;
	text-decoration: none;
}
#Layer2 {
	position:absolute;
	width:90%;
	height:23px;
	z-index:2;
	left: 5%;
	top: 123px;
}
.Estilo1 {font-size: 12px}
#apDiv1 {
	position:absolute;
	width:40%;
	height:32px;
	z-index:3;
	left: 30%;
	text-align: center;
}
-->
</style>
<head>
<?php include("../../funciones/codificacion.php");?>
<title>CFTMASS | historial</title>
</head>
<body>
<h1 id="banner">Administrador - Historial de Eventos</h1>
<div id="link"><br />
<a href="index.php" class="button">Volver a Selecci&oacute;n</a></div>
<div id="Layer2">
  <table width="100%" border="0" align="center">
  <caption></caption>
  <thead>
    <tr>
      <th >N&ordm;</th>
       <th>Tipo</th>
      <th>Usuario</th>
      <th >Ip</th>
      <th>fecha/hora</th>
      <th >Evento</th>
    </tr>
	</thead>
	<tbody>
    
<?php
if($_POST)
{
	if(DEBUG){ var_dump($_POST);}
	$fecha=$_POST["fecha_X"];
	$usuario=$_POST["usuario"];
	
	$condicion_usuario="";
	if(($usuario!="sin datos")and($usuario!="0"))
	{
		$array_usuario=explode("-",$usuario);
		$tipo_usuario=$array_usuario[0];
		$id_usuario=$array_usuario[1];
		
		$condicion_usuario="tipo_usuario='$tipo_usuario' AND id_user='$id_usuario' AND";
		
	}
	
	require("../../funciones/conexion_v2.php");
	require("../../funciones/funciones_sistema.php");
	$fecha_i=$fecha." 00:00:00";
	$fecha_f=$fecha." 23:59:59";
	$cons="SELECT * FROM historial WHERE $condicion_usuario fecha_hora BETWEEN '$fecha_i' AND '$fecha_f' ORDER by id";
	
	$sqli=$conexion_mysqli->query($cons)or die("1:".$conexion_mysqli->error);
	$num_reg=$sqli->num_rows;
	if($num_reg>0)
	{
		$aux=1;
		while($H=$sqli->fetch_assoc())
		{
			$id_user=$H["id_user"];
			$ip=$H["ip"];
			$fecha_hora=$H["fecha_hora"];
			$evento=$H["evento"];
			$sede=$H["sede"];
			
			$tipo_user=$H["tipo_usuario"];
			
			switch($tipo_user)
			{
				case"alumno":
					$cons_user="SELECT nombre, apellido_P, apellido_M FROM alumno WHERE id='$id_user' LIMIT 1";
					$sqli_A=$conexion_mysqli->query($cons_user)or die("Alumno".$conexion_mysqli->error);
						$A=$sqli_A->fetch_assoc();
						$A_nombre=$A["nombre"];
						$A_apellido_P=$A["apellido_P"];
						$A_apellido_M=$A["apellido_M"];
					$sqli_A->free();	
					$usuario_nombre=$A_nombre." ".$A_apellido_P." ".$A_apellido_M;
					break;
				default:
					$usuario_nombre=NOMBRE_PERSONAL($id_user);
			}
			////////////////////
			
			//////////////////////
			
			//echo"$aux - $id_user - $ip - $fecha_hora - $evento<br>";
			echo'<tr class="odd">
      			<td align="center"><span class="Estilo1">'.$aux.'</span></td>
				<td align="center">'.$tipo_user.'</td>
      			<td align="center"><span class="Estilo1"><a href="#" title="'.$usuario_nombre.' - '.$sede.'">'.$id_user.'_'.$usuario_nombre.'</a></span></td>
      			<td align="center"><span class="Estilo1">'.$ip.'</span></td>
      			<td align="center"><span class="Estilo1">'.$fecha_hora.'</span></td>
      			<td><span class="Estilo1">'.$evento.'</span></td>
    			</tr>';
			$aux++;
		}
	}
	else
	{
		echo"sin registros este dia<br>";
	}
	if(DEBUG){echo "<strong>$cons</strong><br><br>";}
	//----------------------------------------------//
	include("../../funciones/VX.php");
	$evento="Revisa historia del sistema para el dia: $fecha";
	REGISTRA_EVENTO($evento);
	//-----------------------------------------------//
	$sqli->free();
	@mysql_close($conexion);
	$conexion_mysqli->close();
	
}
?>
</tbody>
<tfoot>
<tr>
<td colspan="6"><span class="Estilo1">Total Eventos -> <?php echo ($aux-1);?></span></td>
</tr>
</tfoot>
</table>
</div>
<div id="apDiv1">Eventos del Dia:<?php echo $fecha;?></div>
<p>
</body>
</html>
