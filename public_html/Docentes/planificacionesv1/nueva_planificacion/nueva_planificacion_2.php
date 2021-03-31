<?php
//-----------------------------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(true);
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Planificaciones->nuevoRegistro");
	$O->PERMITIR_ACCESO_USUARIO();
//-----------------------------------------//
//////////////////////XAJAX/////////////////
@require_once ("../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("comprueba_server.php");
$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"COMPRUEBA");
//---------------------------------------------------------///
if(DEBUG){var_dump($_POST);}
	
	$id_usuario=$_SESSION["USUARIO"]["id"];
	
	$id_carrera=$_POST["id_carrera"];
	$cod_asignatura=$_POST["cod_asignatura"];
	$jornada=$_POST["jornada"];
	$grupo_curso=$_POST["grupo_curso"];
	$semestre=$_POST["semestre"];
	$year=$_POST["year"];
	$id_programa=$_POST["programa"];
	$sede=$_POST["sede"];
	
	require("../../../../funciones/funciones_sistema.php");
	require("../../../../funciones/conexion_v2.php");
	
	$nombre_carrera=NOMBRE_CARRERA($id_carrera);
	list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($id_carrera, $cod_asignatura);
	
	if($id_programa>0)
	{
		$cons_P="SELECT * FROM programa_estudio WHERE id_programa='$id_programa' LIMIT 1";
		$sqli_P=$conexion_mysqli->query($cons_P);
		$P=$sqli_P->fetch_assoc();
			$P_cantidad_horas=$P["cantidad_horas"];
			$P_numero_unidad=$P["numero_unidad"];
			$P_nombre_unidad=$P["nombre_unidad"];
			$P_contenido=$P["contenido"];
		$sqli_P->free();	
	}
	else
	{
			$P_cantidad_horas=0;
			$P_numero_unidad=0;
			$P_nombre_unidad="Otro";
			$P_contenido="Otro uso del Docente";	
	}
	//------------------------------------------------------------------//
	///horas de programa total
	$TOTAL_HORAS_PROGRAMA=0;
	$cons_HT="SELECT DISTINCT(numero_unidad) FROM programa_estudio WHERE id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura'";
	$sqli_HT=$conexion_mysqli->query($cons_HT)or die($conexion_mysqli->error);
	$num_programas=$sqli_HT->num_rows;
	if($num_programas>0)
	{
		while($HT=$sqli_HT->fetch_row())
		{
			$aux_numero_unidad=$HT[0];
			$aux_CONS="SELECT cantidad_horas FROM programa_estudio WHERE id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND numero_unidad='$aux_numero_unidad' LIMIT 1";
			$sqli_aux=$conexion_mysqli->query($aux_CONS)or die("HP ".$conexion_mysqli->error);
				$Pnh=$sqli_aux->fetch_row();
				$aux_numero_hora_x_unidad=$Pnh[0];
				if(empty($aux_numero_hora_x_unidad)){ $aux_numero_hora_x_unidad=0;}
			$TOTAL_HORAS_PROGRAMA+=$aux_numero_hora_x_unidad;
			$sqli_aux->free();	
		}
	}
	$sqli_HT->free();
	//----------------------------------------------------//
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<title>Agrega Registro a Planificacion</title>
<?php $xajax->printJavascript(); ?> 
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:50%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 53px;
}
#apDiv2 {
	position:absolute;
	width:60%;
	height:115px;
	z-index:2;
	left: 5%;
	top: 281px;
}
#div_boton {
	position:absolute;
	width:25%;
	height:33px;
	z-index:3;
	left: 70%;
	top: 419px;
	text-align:center;
}
#div_info {
	position:absolute;
	width:25%;
	height:115px;
	z-index:4;
	left: 70%;
	top: 283px;
}
</style>
<script language="javascript">
function CONFIRMAR()
{
	continuar=true;
	actividad=document.getElementById('actividad').value;
	implemento=document.getElementById('implemento').value;
	evaluacion=document.getElementById('evaluacion').value;
	bibliografia=document.getElementById('bibliografia').value;
	<?php if($id_programa==0){?>
		contenido_tematico=document.getElementById('contenido_tematico').value;
		
		if((contenido_tematico=="")||(contenido_tematico==" "))
		{
			continuar=false;
			alert("Ingrese Contenido Tematico");
		}
	<?php }?>
	
	if((actividad=="")||(actividad==" "))
	{
		continuar=false;
		alert("Ingrese Una Actividad");
	}
	
	if(continuar)
	{
		c=confirm('Desea Agregar este Registro ¿?');
		if(c){ document.getElementById('frm').submit();}
	}
}
</script>
</head>

<body>
<h1 id="banner">Administrador -  Nueva Planificaciones V1.0</h1>
<div id="link"><br />
<a href="../../contenidos/nueva/nueva_planificacion_1.php?id_carrera=<?php echo $id_carrera;?>&amp;asignatura=<?php echo $cod_asignatura;?>&amp;semestre=<?php echo $semestre;?>&amp;year=<?php echo $year;?>&amp;sede=<?php echo $sede;?>&amp;jornada=<?php echo $jornada;?>&amp;grupo=<?php echo $grupo_curso;?>" class="button">Volver</a></div>
<div id="apDiv1">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="2">&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="25%">Carrera</td>
      <td><?php echo $nombre_carrera;?></td>
    </tr>
    <tr>
      <td>Asinatura</td>
      <td><?php echo $nombre_asignatura;?></td>
    </tr>
    <tr>
      <td>N. Horas Unidad</td>
      <td><?php echo $P_cantidad_horas;?></td>
      </tr>
    <tr>
      <td>Unidad Programa</td>
      <td><?php echo"[$P_numero_unidad] $P_nombre_unidad -> $P_contenido";?></td>
    </tr>
    </tbody>
  </table>
</div>
<div id="apDiv2">
<form action="../../contenidos/nueva/nueva_planificacion_3.php" method="post" id="frm">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="2">Ingrese los Datos
        <input name="id_carrera" type="hidden" id="id_carrera" value="<?php echo $id_carrera;?>" />
        <input name="cod_asignatura" type="hidden" id="cod_asignatura" value="<?php echo $cod_asignatura;?>" />
        <input name="semestre" type="hidden" id="semestre" value="<?php echo $semestre;?>" />
        <input name="year" type="hidden" id="year" value="<?php echo $year;?>" />
        <input name="sede" type="hidden" id="sede" value="<?php echo $sede;?>" />
        <input name="jornada" type="hidden" id="jornada" value="<?php echo $jornada;?>" />
        <input name="grupo_curso" type="hidden" id="grupo_curso" value="<?php echo $grupo_curso;?>" />
        <input type="hidden" name="id_programa" id="id_programa" value="<?php echo $id_programa;?>"/></th>
    </tr>
    </thead>
    <tbody>
    <?php if($id_programa==0){?>
    <tr>
      <td>Contenido Tematico</td>
      <td><label for="contenido_tematico"></label>
        <input type="text" name="contenido_tematico" id="contenido_tematico" /></td>
    </tr>
    <?php }?>
    <tr>
      <td width="43%">N. Semana</td>
      <td width="57%">
        <select name="numero_semana" id="numero_semana" onchange="xajax_COMPRUEBA(this.value, document.getElementById('id_carrera').value, document.getElementById('cod_asignatura').value, document.getElementById('semestre').value, document.getElementById('year').value, document.getElementById('sede').value, document.getElementById('jornada').value, document.getElementById('grupo_curso').value, document.getElementById('id_programa').value); return false;">
        <option value="0" selected="selected">Seleccione</option>
        <?php
        for($x=1;$x<=18;$x++)
		{
			
			echo'<option value="'.$x.'">'.$x.'</option>';
		}
		?>
        </select>
        </td>
    </tr>
    <tr>
      <td>Horas X Semana</td>
      <td><div id="div_horas_semana">...</div>
        
        </td>
    </tr>
    <tr>
      <td>Actividad/Metodologia</td>
      <td><label for="actividad"></label>
        <input type="text" name="actividad" id="actividad" /></td>
    </tr>
    <tr>
      <td>Implemento Apoyo a la Docencia</td>
      <td><label for="implemento"></label>
        <input type="text" name="implemento" id="implemento" /></td>
    </tr>
    <tr>
      <td>Evaluacion(Tipo)</td>
      <td><label for="evaluacion"></label>
        <input type="text" name="evaluacion" id="evaluacion" /></td>
    </tr>
    <tr>
      <td>Bibliografia</td>
      <td><label for="bibliografia"></label>
        <input type="text" name="bibliografia" id="bibliografia" /></td>
    </tr>
    </tbody>
  </table>
  </form>
</div>
<div id="div_boton"></div>
<div id="div_info"></div>
</body>
</html>
<?php
$conexion_mysqli->close();
mysql_close($conexion);	
?>