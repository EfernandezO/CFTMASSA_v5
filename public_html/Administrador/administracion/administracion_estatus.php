<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("sistema->status");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<title>Estatus</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 178px;
}
</style>
</head>

<body>
<h1 id="banner">Administraci&oacute;n - Estatus</h1>
<div id="link">
  <p><br />
    <a href="../ADmenu.php" class="button">Volver Al menu</a></p>
  <p><a href="info_php.php" target="_blank" class="button_R">php_info</a><br />
    <br />
  <strong>Test Mail -></strong><a href="test_mail.php?servidor_correo=localhost" class="button_R">localhost</a>  <a href="test_mail.php?servidor_correo=gmail" class="button_R">gmail</a></p>
</div>
<div id="apDiv1">
<?php 
	
?>
    <table width="50%" border="0" align="center">
    <thead>
      <tr>
        <th height="33" colspan="2" align="center"><strong>Opciones</strong></th>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td width="53%">Zona Horaria</td>
        <td><?php echo date_default_timezone_get();?></td>
      </tr>
      <tr>
        <td>Hora</td>
        <td><?php echo date("H:i:s");?></td>
      </tr>
      <tr>
        <td>Fecha</td>
        <td><?php echo date("d-m-Y");?></td>
      </tr>
      </tbody>
    </table>
    <br />
    <table width="50%" border="1" align="center">
    <thead>
      <tr>
        <th colspan="4">Alumnos Conectados</th>
      </tr>
      </thead>
      <tbody>
     <tr>
        <td>N</td>
        <td>ID</td>
        <td>Rut</td>
        <td>ID carrera</td>
     </tr>
        <?php
		include("../../../funciones/conexion_v2.php");
			$cons="SELECT * FROM alumno WHERE estado_conexion='on'";
			$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
			$num_alumnos_conectados=$sql->num_rows;
			if($num_alumnos_conectados>0)
			{
				$contador=0;
				while($AC=$sql->fetch_assoc())
				{
					$contador++;
					$A_id=$AC["id"];
					$A_rut=$AC["rut"];
					$A_id_carrera=$AC["id_carrera"];
					
					echo'<tr>
							<td>'.$contador.'</td>
							<td>'.$A_id.'</td>
							<td>'.$A_rut.'</td>
							<td>'.$A_id_carrera.'</td>
						 </tr>';
				}
			}
			else
			{
				echo'<tr>
						<td colspan="4">Sin Conexiones...</td>
					 </tr>';
			}
		$sql->free();
		?>
      </tbody>
    </table>
    
     <br />
    <table width="50%" border="1" align="center">
    <thead>
      <tr>
        <th colspan="4">Personal Conectado</th>
      </tr>
      </thead>
      <tbody>
     <tr>
        <td>N</td>
        <td>ID</td>
        <td>Rut</td>
        <td>Nombre</td>
     </tr>
        <?php
			$cons="SELECT id, rut, nombre, apellido_P, apellido_M FROM personal WHERE estado_conexion='on'";
			$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
			$num_personal_conectados=$sql->num_rows;
			if($num_personal_conectados>0)
			{
				$contador=0;
				while($AC=$sql->fetch_assoc())
				{
					$contador++;
					$P_id=$AC["id"];
					$P_rut=$AC["rut"];
					$P_nombre=$AC["nombre"]." ".$AC["apellido_P"]." ".$AC["apellido_M"];
					
					echo'<tr>
							<td>'.$contador.'</td>
							<td>'.$P_id.'</td>
							<td>'.$P_rut.'</td>
							<td>'.$P_nombre.'</td>
						 </tr>';
				}
			}
			else
			{
				echo'<tr>
						<td colspan="4">Sin Conexiones...</td>
					 </tr>';
			}
		$sql->free();
		$conexion_mysqli->close();
		?>
      </tbody>
    </table>
</div>
</body>
</html>