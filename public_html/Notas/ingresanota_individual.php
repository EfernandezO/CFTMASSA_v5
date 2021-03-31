<?php
//--------------CLASS_okalis------------------//
	require("../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../funciones/";
	$O->clave_del_archivo=md5("Alumno->Notas_Semestrales_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//	
 //////////////////////XAJAX/////////////////
@require_once ("../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("ingresanota_individual_server.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"DATOS_NOTA");
$xajax->register(XAJAX_FUNCTION,"CARGA_NOTAS");
//////////DEBUG////////////////
?>
<html>
<head>
<title>Ingreso de Notas Semestrales</title>
<?php include("../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../CSS/tabla_2.css">
<?php $xajax->printJavascript(); ?> 
<style type="text/css">
<!--
.Estilo2 {color: #0080C0}
#Layer2 {
	position:absolute;
	width:90%;
	height:34px;
	z-index:1;
	left: 5%;
	top: 71px;
}
-->
</style>
<link rel="stylesheet" type="text/css" href="../libreria_publica/tooltip_1/tooltip.css">
<script language="javascript">
function CONFIRMAR()
{
		c=confirm('GRABAR NOTAS...?');
		if(c)
		{
				document.getElementById('frm').submit();
		}
}
</script>

</head>
<body>
<h1 id="banner">Administrador - Notas Semestrales Alumnos V 1.4</h1>
<div id="link"><br><a href="../buscador_alumno_BETA/HALL/index.php" class="button">
Volver al Menu</a></div>
<?php 
  $array_condiciones=array("ok","convalidacion", "homologacion", "repeticion");	
  $array_semestre=array("","1","2");
  $id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
  
  $year_actual=date("Y");
  $mes_actual=date("M");
  
  require("../../funciones/conexion_v2.php");
  include("../../funciones/funcion.php");
  require("../../funciones/funciones_sistema.php");
 
   $nomap=$_SESSION["SELECTOR_ALUMNO"]["nombre"]." ".$_SESSION["SELECTOR_ALUMNO"]["apellido"];
   
   $array_periodosCarrera=array();
   #carrera periodo alumno  tiene registro academico
   $cons_PN="SELECT id_carrera, yearIngresoCarrera FROM `contratos2` WHERE id_alumno='$id_alumno' group by id_carrera, yearIngresoCarrera";
   $sqli_PN=$conexion_mysqli->query($cons_PN) or die($conexion_mysqli->error);
   $numRegistrosPeriodos=$sqli_PN->num_rows;
   $i=0;
   while($DPN=$sqli_PN->fetch_assoc()){
	   $array_periodosCarrera[$i]["id_carrera"]=$DPN["id_carrera"];
	   $array_periodosCarrera[$i]["yearIngresoCarrera"]=$DPN["yearIngresoCarrera"];
	   $i++;
   }
   $sqli_PN->free();
   
   
   
   
?> 
<div id="Layer2">
  <table width="60%" border="0" align="left">
  <thead>
  	<th colspan="3">Alumno
      </thead></th>
  <tbody>
    <tr> 
      <td width="56">Alumno</td>
      <td><?php echo $nomap; ?> <a href="../index.html"></a>      </td>
    </tr>
    <tr>
      <td><em>Info</em></td>
      <td><em>*seleccione &quot;nueva&quot; en el selector de acciones para ingresar nueva nota*</em></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><div id="resultado">...</div></td>
    </tr>
     <tr>
      <td>Periodos</td>
      <td>
      	<?php
			foreach($array_periodosCarrera as $n => $valor){
				echo'<a href="#" class="button_R" onClick="xajax_CARGA_NOTAS('.$id_alumno.', '.$valor["id_carrera"].', '.$valor["yearIngresoCarrera"].');">'.NOMBRE_CARRERA($valor["id_carrera"]).' - '.$valor["yearIngresoCarrera"].'</a><br><br>';
			}
        ?>
      </td>
    </tr>
    </tbody>
  </table>
  <br>
      <div id="Layer1" > 
      </div>
</div>

</body>
</html>
