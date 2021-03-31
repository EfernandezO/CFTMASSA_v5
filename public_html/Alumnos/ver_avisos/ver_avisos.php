
<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="ALUMNO";
	OKALIS($lista_invitados);
//-----------------------------------------//	

$carrera=$_SESSION["USUARIO"]["carrera"];
$id_carrera=$_SESSION["USUARIO"]["id_carrera"];
$sede=$_SESSION["USUARIO"]["sede"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Avisos</title>
	<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
	<style type="text/css" title="currentStyle">
			@import "../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/css/demo_page.css";
			@import "../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/css/demo_table_jui.css";
			@import "../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/examples/examples_support/themes/smoothness/jquery-ui-1.8.4.custom.css";
</style>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:40px;
	z-index:1;
	left: 5%;
	top: 119px;
}
-->
</style>
<script type="text/javascript" language="javascript" src="../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="ISO-8859-1">
			$(document).ready(function() {
				oTable = $('#example').dataTable({
					"bJQueryUI": true,
					"bPaginate": false
				});
			} );

</script>
</head>

<body>
<h1 id="banner">Administrador - AVISOS</h1>
<h3>Avisos para <?php echo" $carrera, $sede";?></h3>
<div id="apDiv1" class="demo_jui">
  <table width="50%" border="1" align="center" class="display" id="example">
      <thead>
	    <tr>
	      <th>N</th>
	      <th>fecha/hora recepcion</th>
	      <th>Profesor</th>
          <th>carrera</th>
          <th>asignatura</th>
	      <th>cuando falta</th>
	      <th>Informado</th>
	      <th>a quien ?</th>
	      <th>Observacion</th>
          <th>Creador</th>
        </tr>
        </thead>
        <tbody>
	   <?php
	   $privilegio=$_SESSION["USUARIO"]["privilegio"];
	   include("../../../funciones/conexion.php");
	   $cons="SELECT * FROM avisos WHERE id_carrera='$id_carrera' AND sede='$sede' ORDER by id desc";
	   $sql=mysql_query($cons)or die(mysql_error());
	   $num_avisos=mysql_num_rows($sql);
	   if($num_avisos>0)
	   {
		   $contador=0;
			while($A=mysql_fetch_assoc($sql))
			{
				$contador++;
				$A_id=$A["id"];
				$A_sede=$A["sede"];
				$A_id_carrera=$A["id_carrera"];
				//////////////////////////
				//carrera
				$cons="SELECT carrera FROM carrera WHERE id='$A_id_carrera' LIMIT 1";
				$sqlC=mysql_query($cons)or die(mysql_error());
					$DC=mysql_fetch_assoc($sqlC);
					$carrera_nombre=$DC["carrera"];
				mysql_free_result($sqlC);	
				/////////////////////////////
				
				$A_id_asignatura=$A["id_asignatura"];
				//////////////////////////
				//asignatura
				$consA="SELECT asignatura FROM asignatura WHERE id='$A_id_asignatura' LIMIT 1";
				$sqlA=mysql_query($consA)or die(mysql_error());
					$DA=mysql_fetch_assoc($sqlA);
					$asignatura_nombre=$DA["asignatura"];
				mysql_free_result($sqlA);	
				/////////////////////////////
				$A_quien_se_ausenta=$A["quien_se_ausenta"];
				$A_cuando_se_ausenta=$A["cuando_se_ausenta"];
				$A_se_informo=$A["se_informo"];
				$A_a_quien=$A["a_quien"];
				$A_observacion=$A["observacion"];
				$A_fecha_generacion=$A["fecha_generacion"];
				$A_cod_user=$A["cod_user"];
				///////////////
				$consU="SELECT nombre, apellido FROM personal WHERE id='$A_cod_user' LIMIT 1";
				$sqlU=mysql_query($consU)or die(mysql_error());
					$DU=mysql_fetch_assoc($sqlU);
					$user_nombre=$DU["nombre"];
					$user_apellido=$DU["apellido"];
				mysql_free_result($sqlU);	
				///////////////
				
				
				///////////////////
				switch($A_se_informo)
				{
					case"si":
						$img='<img src="../../BAses/Images/color_verde.png" width="24" height="24" alt="si" />';
						break;
					case"no":
						$img='<img src="../../BAses/Images/color_rojo.png" width="26" height="24" alt="no" />';
						break;
				}
				//////////////////
				
						$urlX="";
						$urlI="";	
				/////////////
				echo'<tr>
						<td>'.$contador.'</td>
						<td>'.FORMATEA_FECHA($A_fecha_generacion).'</td>
						<td>'.$A_quien_se_ausenta.'</td>
						<td><a href="#" title="'.$A_id_carrera.'">'.$carrera_nombre.'</a></td>
						<td><a href="#" title="'.$A_id_asignatura.'">'.$asignatura_nombre.'</a></td>
						<td>'.FORMATEA_FECHA($A_cuando_se_ausenta).'</td>
						<td align="center"><a href="'.$urlI.'" title="'.$A_se_informo.'">'.$img.'</a></td>
						<td>'.$A_a_quien.'</td>
						<td>'.$A_observacion.'</td>
						<td>'.$user_nombre.' '.$user_apellido.'</td>
					 </tr>';
			}
		}
		mysql_free_result($sql);
	   mysql_close($conexion);
       ?>
        </tbody>
      </table>
</div>
</body>
</html>
<?php
function FORMATEA_FECHA($fecha)
{
	$array=explode(" ",$fecha);
	$fecha=$array[0];
	$hora=$array[1];
	
	$array_fecha=explode("-",$fecha);
	$year=$array_fecha[0];
	$mes=$array_fecha[1];
	$dia=$array_fecha[2];
	
	$fecha_hora_final=$dia."/".$mes."/".$year." ".$hora;
	return($fecha_hora_final);
}
?>