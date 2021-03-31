<?php
//-----------------------------------------//
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(true);
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Planificaciones->importar");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//-----------------------------------------//
require("../../../../funciones/conexion_v2.php");
require("../../../../funciones/funciones_sistema.php");

$id_usuario_actual=$_SESSION["USUARIO"]["id"];
if($_POST)
{
	$semestre=$_POST["semestre"];
	$year=$_POST["year"];
	$sede=$_POST["sede"];
	$id_carrera=$_POST["carrera"];
	$cod_asignatura=$_POST["asignatura"];
	$jornada=$_POST["jornada"];
	$grupo_curso=$_POST["grupo_curso"];
}
elseif($_GET)
{
	$semestre=mysqli_real_escape_string($conexion_mysqli, $_GET["semestre"]);
	$year=mysqli_real_escape_string($conexion_mysqli, $_GET["year"]);
	$sede=mysqli_real_escape_string($conexion_mysqli, $_GET["sede"]);
	$id_carrera=mysqli_real_escape_string($conexion_mysqli, $_GET["id_carrera"]);
	$cod_asignatura=mysqli_real_escape_string($conexion_mysqli, $_GET["asignatura"]);
	$jornada=mysqli_real_escape_string($conexion_mysqli, $_GET["jornada"]);
	$grupo_curso=mysqli_real_escape_string($conexion_mysqli, $_GET["grupo"]);
}

list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($id_carrera, $cod_asignatura);
$nombre_carrera=NOMBRE_CARRERA($id_carrera);
$nombre_docente=NOMBRE_PERSONAL($id_usuario_actual);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Planificaciones -&gt; Copiar</title>
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 106px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:51px;
	z-index:2;
	left: 30%;
	top: 327px;
	text-align:center;
}
</style>
<script language="javascript">
function CONFIRMAR(datos)
{
	url="copia_planificacion_2.php?"+datos;
	c=confirm("seguro(a) Desea Importar estos registros a su planificacion actual");
	if(c)
	{
		window.location=url;
	}
}
</script>
</head>

<body>
<h1 id="banner">Administrador -  Importar Planificaciones V1.0</h1>
<div id="apDiv1">
<table width="60%" border="1" align="center">
<thead>
  <tr>
    <th colspan="9">
    	Puede importar registros de planificaciones realizadas en la misma asignatura por Ud u otro docente.<br><?php echo $nombre_asignatura;?>
    </tr>
	<tr>
        <td>N.</td>
        <td>Semestre</td>
        <td>Año</td>
        <td>Sede</td>
        <td>Jornada</td>
        <td>Grupo</td>
        <td>Funcionario</td>
        <td colspan="2">Opc</td>
    </tr>
    </thead>
    <tbody>
  <tr>
   <?php
		//busco planificacion en la misma carrera y asignatura
		$cons="SELECT id_carrera, jornada, grupo, semestre, year, id_funcionario, sede FROM `planificaciones` WHERE id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' GROUP BY sede, year, semestre, id_carrera, jornada, grupo, id_funcionario ORDER BY `year`, semestre";
		
		$sqli=$conexion_mysqli->query($cons);
		$num_registro=$sqli->num_rows;
		if(DEBUG){ echo"-->$cons <br>-->num planificaciones: $num_registro<br>";}
		if($num_registro>0)
		{
			$existe_planificacion=true;	
			$aux=0;
			while($P=$sqli->fetch_assoc())
			{
				$aux++;
				$P_semestre=$P["semestre"];
				$P_year=$P["year"];
				$P_jornada=$P["jornada"];
				$P_grupo=$P["grupo"];
				$P_sede=$P["sede"];
				$P_id_funcionario=$P["id_funcionario"];
				$aux_nombre_funcionario=NOMBRE_PERSONAL($P_id_funcionario);
				
				echo'<tr>
						<td>'.$aux.'</td>
						<td>'.$P_semestre.'</td>
						<td>'.$P_year.'</td>
						<td>'.$P_sede.'</td>
						<td>'.$P_jornada.'</td>
						<td>'.$P_grupo.'</td>
						<td>'.$P_id_funcionario.'_'.$aux_nombre_funcionario.'</td>
						<td><a href="../informe_imprimible/informe_imprimible_1.php?semestre='.base64_encode($P_semestre).'&year='.base64_encode($P_year).'&sede='.base64_encode($P_sede).'&jornada='.base64_encode($P_jornada).'&grupo='.base64_encode($P_grupo).'&id_funcionario='.base64_encode($P_id_funcionario).'&id_carrera='.base64_encode($id_carrera).'&asignatura='.base64_encode($cod_asignatura).'" target="_blank">Ver</a></td>
						
						<td><a href="#" onclick="CONFIRMAR(\'semestre='.base64_encode($semestre).'&year='.base64_encode($year).'&sede='.base64_encode($sede).'&jornada='.base64_encode($jornada).'&grupo='.base64_encode($grupo_curso).'&id_carrera='.base64_encode($id_carrera).'&asignatura='.base64_encode($cod_asignatura).'&semestre_old='.base64_encode($P_semestre).'&year_old='.base64_encode($P_year).'&sede_old='.base64_encode($P_sede).'&jornada_old='.base64_encode($P_jornada).'&grupo_old='.base64_encode($P_grupo).'&id_funcionario_old='.base64_encode($P_id_funcionario).'&id_carrera_old='.base64_encode($id_carrera).'&asignatura_old='.base64_encode($cod_asignatura).'\');">Importar</a></td>
					</tr>';
			}
			$sqli->free();
		}
		else
		{
			$existe_planificacion=false;
			echo'<tr>
					<td colspan="8">Sin Planificaciones para esta asignatura</td>
				 </tr>';
			
		}
		
		$conexion_mysqli->close();
	?>
  </tr>
 
    </tbody>
</table>
</div>
</body>
</html>