<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	

$sede_usuario_actual=$_SESSION["USUARIO"]["sede"];
$privilegio=$_SESSION["USUARIO"]["privilegio"];
$filtrar_X_sede=true;

//-----------------------------------------------------------//
$fid=$_SESSION["USUARIO"]["id"];
$continuar=true;


if($continuar)
{
  $array_semeste=array(1,2);
   $mes_actual=date("m");
   
   if($mes_actual>=8)///utilizo agosto para inicio 2 semeste
   { $semeste_actual=2;}
   else{ $semeste_actual=1;}
   ///////////////////////////////////////////////
   include("../../../../funciones/conexion_v2.php");
   	//datos del docente
	$cons_D="SELECT * FROM personal WHERE id='$fid' LIMIT 1";
	$sql_D=$conexion_mysqli->query($cons_D);
		$D=$sql_D->fetch_assoc();
		$D_nombre=$D["nombre"];
		$D_apellido=$D["apellido_P"];
		$D_rut=$D["rut"];
	$sql_D->free();	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {	position:absolute;
	width:45%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 79px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:52px;
	z-index:2;
	left: 30%;
	top: 287px;
	text-align: center;
}
</style>
<title>Asignaciones y Horarios</title>
</head>

<body>
<h1 id="banner">Administrador -Mis Asignacion de Ramos</h1>
<div id="link"><br>
<a href="../../okdocente.php" class="button">Volver al Menu</a></div>
<div id="apDiv1">

  <table width="100%" border="0" align="left">
    <thead>
      <tr>
        <th colspan="4">Datos Docente</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td width="99"><strong>Docente</strong></td>
        <td width="326" colspan="3"><?php echo $D_nombre." ".$D_apellido;; ?>
        <input name="id_docente" type="hidden" id="id_docente" value="<?php echo $fid;?>" /></td>
      </tr>
      <tr>
        <td>Asignaciones</td>
        <td colspan="3">
		<?php
      	$cons_TR="SELECT `semestre`, `year` FROM `toma_ramo_docente` WHERE id_funcionario='$fid' GROUP BY `semestre`, `year` ORDER by `year`, `semestre`";
		$sql_TR=$conexion_mysqli->query($cons_TR);
		$num_periodos=$sql_TR->num_rows;
		
		$msj="";
		$msj_2="";
		if($num_periodos>0)
		{
			while($PTR=$sql_TR->fetch_assoc())
			{
				$periodo_semestre=$PTR["semestre"];
				$periodo_year=$PTR["year"];
				
				$msj.='<a href="ver_mis_asignaciones_2.php?id_funcionario='.base64_encode($fid).'&semestre='.base64_encode($periodo_semestre).'&year='.base64_encode($periodo_year).'" class="button_R" target="_blank" title="Click para revisar asignaciones en este Periodo">'.$periodo_semestre.'-'.$periodo_year.'</a>&nbsp;';
				$msj_2.='<a href="../horario_clases/ver_mis_horarios.php?id_funcionario='.base64_encode($fid).'&semestre='.base64_encode($periodo_semestre).'&year='.base64_encode($periodo_year).'" class="button_R" target="_blank" title="Click para revisar Horario de Clases en este Periodo">'.$periodo_semestre.'-'.$periodo_year.'</a>&nbsp;';
			}
		}
		else
		{ $msj="Sin Registros...";}
		$sql_TR->free();
		echo $msj;
	  ?>
		</td>
      </tr>
      <tr>
      	<td>Horarios</td>
      	<td colspan="3"><?php echo $msj_2;?></td>
      </tr>
    </tbody>
  </table>
</div>
<div id="apDiv2">Presione el boton cuyo periodo desea consular<br />
  y podra revisar las asignaciones y horarios que ud.<br />
  ya tiene registradas en el sistema.
</div>
</body>
</html>