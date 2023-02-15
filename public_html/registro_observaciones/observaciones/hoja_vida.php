<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
//-----------------------------------------//	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Hoja de Vida</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla.css">
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:1;
	left: 7px;
	top: 111px;
}
#apDiv2 {
	position:absolute;
	width:792px;
	height:115px;
	z-index:2;
	left: 7px;
	top: 314px;
}
.Estilo1 {font-size: 12px}
#apDiv2 #errores {
	text-decoration: blink;
	text-align: center;
}
-->
</style>
<script language="javascript">
function confirmar(ida, ido)
{
	url="borrar_observacion/borrar_observacion.php?id_alumno="+ida+"&id_observacion="+ido;
	c=confirm('Seguro desea Eliminar');
	if(c)
	{
		//alert(url);
		window.location=url;
	}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Hoja de Vida</h1>

	<div id="link">
    <?php
    $privilegio=$_SESSION["USUARIO"]["privilegio"];
	switch($privilegio)
	{
		case"Docente":
			$url="../../Docentes/buscador_alumno_BETA_para_docentes/HALL/index.php";
			break;
		case"jefe_carrera":
			$url="../../Docentes/buscador_alumno_BETA_para_docentes/HALL/index.php";
			break;	
		default:
			$url="../../buscador_alumno_BETA/HALL/index.php";
	}
	?>
	  <div align="right"><br />
<a href="<?php echo $url;?>" class="button">Volver a Seleccion</a></div>
	</div>
	<h3>Registro de Las Observaciones del Alumno</h3>
    <p>
      <?php
	if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
	{
		include("../../../funciones/conexion.php");
		$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
		if(is_numeric($id_alumno))
		{
			$cons_alu="SELECT * FROM alumno WHERE id='$id_alumno'";
			$sql_alu=mysql_query($cons_alu)or die(mysql_error());
			$DA=mysql_fetch_assoc($sql_alu);
			$nombre=$DA["nombre"];
			$apellido_old=$DA["apellido"];
			$apellido_new=$DA["apellido_P"]." ".$DA["apellido_M"];
			if($apellido_new==" ")
			{ $apellido_label=$apellido_old;}
			else
			{ $apellido_label=$apellido_new;}
			$carrera=$DA["carrera"];
			$nivel=$DA["nivel"];
			mysql_free_result($sql_alu);
			
		}
	}
	else
	{ echo"Sin Datos";}
?>
</p>
    <div id="apDiv1">
      <table width="450" border="1">
      	<thead>
        <tr>
          <td colspan="2"><span class="Estilo1">Datos Alumno</span></td>
        </tr>
        </thead>
        <tbody>
        <tr class="odd">
          <td><span class="Estilo1">ID</span></td>
          <td><span class="Estilo1"><?php echo $id_alumno;?></span></td>
        </tr>
        <tr class="odd">
          <td><span class="Estilo1">Nombre</span></td>
          <td><span class="Estilo1"><?php echo $nombre;?></span></td>
        </tr>
        <tr class="odd">
          <td><span class="Estilo1">Apellido</span></td>
          <td><span class="Estilo1"><?php echo $apellido_label;?></span></td>
        </tr>
        <tr class="odd">
          <td><span class="Estilo1">Carrera</span></td>
          <td><span class="Estilo1"><?php echo $carrera;?></span></td>
        </tr>
        <tr class="odd">
          <td><span class="Estilo1">nivel</span></td>
          <td><span class="Estilo1"><?php echo $nivel;?></span></td>
        </tr>
        </tbody>
      </table>
</div>
    <div id="apDiv2">
      <table width="100%" border="1">
      <thead>
        <tr>
          <td><span class="Estilo1"><strong>N&deg;</strong></span></td>
          <td><span class="Estilo1"><strong>Fecha</strong></span></td>
          <td><span class="Estilo1"><strong>Observaci&oacute;n</strong></span></td>
          <td><span class="Estilo1"><strong>Usuario</strong></span></td>
          <td colspan="2"><span class="Estilo1"><strong>Opcion</strong></span></td>
        </tr>
        </thead>
        <tbody>
        <?php
		$privilegio=$_SESSION["USUARIO"]["privilegio"];
		 switch($privilegio)
		{
			case"admi_total":
				$mostrar_edicion=true;
				break;
			default:
				$mostrar_edicion=false;	
		}
        $cons_HV="SELECT * FROM hoja_vida WHERE id_alumno='$id_alumno' ORDER by fecha desc";
		$sql_HV=mysql_query($cons_HV)or die(mysql_error());
		$num_observaciones=mysql_num_rows($sql_HV);
		if($num_observaciones>0)
		{
			$contador=1;
			while($HV=mysql_fetch_assoc($sql_HV))
			{
				$id_observacion=$HV["id"];
				$observacion=$HV["observacion"];
				$fecha=$HV["fecha"];
				$id_user=$HV["id_user"];
				echo'<tr class="odd">
					  <td><span class="Estilo1 Estilo1">'.$contador.'</span></td>
					  <td><span class="Estilo1 Estilo1">'.$fecha.'</span></td>
					  <td><span class="Estilo1 Estilo1">
					    <textarea id="elm_'.$contador.'" name="elm_'.$contador.'" rows="2" cols="40">'.$observacion.'</textarea>
					    </span></td>
					  <td><span class="Estilo1 Estilo1">'.$id_user.'</span></td>
					  ';
					 if($mostrar_edicion)
					 {
					 	echo'<td><a href="ver_observacion/verO.php?id_alumno='.$id_alumno.'&id_observacion='.$id_observacion.'" class="Estilo1" ">Ver</a></td>
						<td> <a href="#" class="Estilo1" onclick="confirmar(\''.$id_alumno.'\',\''.$id_observacion.'\');">Borrar</a></td>
						';
					 }
					 else
					 {
					 	echo'<td colspan="2"><a href="ver_observacion/verO.php?id_alumno='.$id_alumno.'&id_observacion='.$id_observacion.'" class="Estilo1">Ver</a></td>
					 	';
					 }
					echo'</tr>';
					$contador++;
			}
			mysql_free_result($sql_HV);
		}
		else
		{  echo'<tr><td colspan="7"><span class="Estilo1">Sin observaciones Registradas...</span></td></tr>';}
		mysql_close($conexion);
		?>
          </tbody>
        
        <tfoot>
        <tr>
        <td colspan="7"><div align="right" class="Estilo1"><a href="nueva_observacion.php/nva_observacion.php?id_alumno=<?php echo $id_alumno;?>" title="Agregar Observacion"><img src="../../BAses/Images/add.png" alt="[+]" width="32" height="31" /></a></div></td>
        </tr>
        </tfoot>
      </table>
  	<?php
    $error=$_GET["error"];
	switch($error)
	{
		case"0":
			$msj_error="Observacion Registrada";
			break;
		case"1":
			$msj_error="Fallo Al Registrar Observacion";
			break;
		case"2":
			$msj_error="Observacion Modificada";
			break;
		case"3":
			$msj_error="Fallo Al Modificar Observacion";
			break;
		case"4":
			$msj_error="Observacion Eliminada";
			break;
		case"5":
			$msj_error="Fallo Al Eliminar Observacion";
			break;					
	}
	?>
      <div id="errores">*<?php echo $msj_error;?>*</div>
</div>
</body>
</html>