<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="ALUMNO";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Hoja de Vida</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:48px;
	z-index:1;
	left: 5%;
	top: 105px;
}
a:link {
	color: #006699;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #006699;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #006699;
}
-->
</style>
</head>

<body>
<h1 id="banner">Alumno - Hoja de Vida</h1>
<div id="apDiv1">
  <table border="1" width="100%">
<thead>
<tr>
	<th>N°</th>
    <th>Fecha</th>
    <th>Observación</th>
    <th>Escrito Por</th>
</tr>
</thead>
<tbody>
<?php
   include('../../../funciones/conexion.php');
   include('../../../funciones/funcion.php');
   $id_alumno=$_SESSION["USUARIO"]["id"];
   $cons_o="SELECT * FROM hoja_vida WHERE id_alumno='$id_alumno' AND tipo_visualizacion='publica' ORDER by fecha";
   $sql_o=mysql_query($cons_o)or die(mysql_error());
   $num_reg=mysql_num_rows($sql_o);
   //--------------------------------------------------//
 	 include("../../../funciones/VX.php");
	 //cambio estado_conexion USER-----------
	 CAMBIA_ESTADO_CONEXION_ALUMNO($id_alumno, "ON");
	//-----------------------------------------------//
   if($num_reg>0)
   {
   	$contador=0;
   		while($O=mysql_fetch_assoc($sql_o))
		{
			$contador++;
			$id_observacion=$O["id"];
			$observacion=$O["observacion"];
			$fecha=$O["fecha"];
			$id_user=$O["id_user"];
				////////////////////
			$cons_user="SELECT nombre, apellido FROM personal WHERE id ='$id_user'";
			$sql_user=mysql_query($cons_user) or die(mysql_error());
			$DU=mysql_fetch_assoc($sql_user);
			$nombre=$DU["nombre"];
			$apellido=$DU["apellido"];
			$usuario_nombre=$nombre." ".$apellido;
			mysql_free_result($sql_user);
			//////////////////////
			echo'<tr>
				<td>'.$contador.'</td>
				<td>'.fecha_format($fecha).'</td>
				<td>'.$observacion.'</td>
				<td>'.$usuario_nombre.'</td>
				</tr>';
		}
   }
   else
   {	
   		echo'<tr><td colspan="4">Sin Observaciones Registradas</td></tr>';
   }
   mysql_free_result($sql_o);
   mysql_close($conexion);
?>
</tbody>
</table>
</div>
</body>
</html>