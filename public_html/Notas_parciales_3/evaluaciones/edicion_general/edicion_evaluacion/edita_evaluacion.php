<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("Notas_parcialesV3->Editar");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_GET)
{
	if(DEBUG){ var_dump($_GET);}
	
	require("../../../../../funciones/conexion_v2.php");
	require("../../../../../funciones/VX.php");
	$sede=base64_decode($_GET["sede"]);
	$id_carrera=base64_decode($_GET["id_carrera"]);
	$jornada=base64_decode($_GET["jornada"]);
	$grupo=base64_decode($_GET["grupo"]);
	$cod_asignatura=base64_decode($_GET["cod_asignatura"]);
	$semestre=base64_decode($_GET["semestre"]);
	$year=base64_decode($_GET["year"]);
	$id_evaluacion=base64_decode($_GET["id_evaluacion"]);
	
	$cons="SELECT * FROM notas_parciales_evaluaciones WHERE id='$id_evaluacion' LIMIT 1";
	$sqliE=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$E=$sqliE->fetch_assoc();
		$E_nombre=$E["nombre_evaluacion"];
		$E_fecha=$E["fecha_evaluacion"];
	$sqliE->free();	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<script src="../../../../libreria_publica/JSCal/src/js/jscal2.js"></script>
 <script src="../../../../libreria_publica/JSCal/src/js/lang/es.js"></script>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/steel/steel.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css">
<title>Edita Evaluacion</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 102px;
}
</style>
<script>
function CONFIRMAR(){
	document.getElementById('frm').submit();
}
</script>
</head>

<body>
<h1 id="banner">Administrador -  Registro Notas Parciales v3</h1>
<div id="link"><br />
  <div id="apDiv1">
  <form action="edita_evaluacion_2.php" method="post" id="frm">
    <table width="50%" border="1" align="center">
    <thead>
    <th colspan="2">Edicion de Evaluacion
    	
   	    
   	      <input type="hidden" name="sede" id="sede"  value="<?php echo $sede;?>"/>
          <input type="hidden" name="id_carrera" id="id_carrera" value="<?php echo $id_carrera;?>"/>
          <input type="hidden" name="jornada" id="jornada" value="<?php echo $jornada;?>"/>
          <input type="hidden" name="grupo" id="grupo" value="<?php echo $grupo;?>"/>
          <input type="hidden" name="cod_asignatura" id="cod_asignatura" value="<?php echo $cod_asignatura;?>"/>
          <input type="hidden" name="semestre" id="semestre" value="<?php echo $semestre;?>"/>
          <input type="hidden" name="year" id="year" value="<?php echo $year;?>"/>
          <input type="hidden" name="id_evaluacion" id="id_evaluacion" value="<?php echo $id_evaluacion;?>"/>
          </th>
          </thead>
    <tbody>
      <tr>
        <td width="47%">Nombre Evaluacion</td>
        <td width="53%"><label for="nombreEvaluacion"></label>
          <input name="nombreEvaluacion" type="text" id="nombreEvaluacion" value="<?php echo $E_nombre;?>" maxlength="25"/></td>
      </tr>
      <tr>
        <td>Fecha Evaluacion</td>
        <td><input  name="fechaEvaluacion" id="fechaEvaluacion" size="15" maxlength="10" readonly="readonly" value="<?php echo $E_fecha;?>"/>
          <input type="button" name="boton1" id="boton1" value="..." /></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      </tbody>
    </table>
    </form>
    <p><a href="#" class="button_G" onclick="CONFIRMAR();">Modificar</a></p>
  
  </div>
<a href="../../ver_evaluaciones.php?sede=<?php echo base64_encode($sede);?>&id_carrera=<?php echo base64_encode($id_carrera);?>&jornada=<?php echo base64_encode($jornada);?>&grupo_curso=<?php echo base64_encode($grupo);?>&cod_asignatura=<?php echo base64_encode($cod_asignatura);?>&semestre=<?php echo base64_encode($semestre);?>&year=<?php echo base64_encode($year);?>" class="button">Volver</a></div>

<script type="text/javascript">
//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton1", "fechaEvaluacion", "%Y-%m-%d");

    //]]>
</script>
</body>
</html>