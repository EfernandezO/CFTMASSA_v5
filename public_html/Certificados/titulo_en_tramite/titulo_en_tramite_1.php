<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("certificado->titulo_en_tramite_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//	
////////////////////////////////////////////////////
	require("../../../funciones/conexion_v2.php");
	$action="";
	$funcion_js='function CONFIRMAR()
					{alert(\'Alumno sin Proceso Titulacion No se Puede Continuar...\');}';
	
if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))	
{
	if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
	{
		$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
		$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
		
		$aux_examen_fecha="";
		$aux_nombre_titulo="";
		
		$cons_pt="SELECT * FROM proceso_titulacion WHERE id_alumno='$id_alumno' ORDER by id desc LIMIT 1";
		if(DEBUG){ echo $cons_pt;}
		$sql_pt=mysql_query($cons_pt)or die(mysql_error());
		$num_reg_pt=mysql_num_rows($sql_pt);
		if($num_reg_pt>0)
		{
			while($PT=mysql_fetch_assoc($sql_pt))
			{
				$aux_examen_condicion=$PT["examen_condicion"];
				$aux_examen_fecha=$PT["examen_fecha"];
				$aux_nombre_titulo=$PT["nombre_titulo"];
				if($aux_examen_condicion=="aprobado")
				{ 
					$action="titulo_en_tramite_2.php";
					$funcion_js='function CONFIRMAR()
						{
							c=confirm(\'Generar Certificado...?\');
							if(c)
							{ document.frm.submit();}
						}';
				}
				else
				{ $funcion_js='function CONFIRMAR()
					{alert(\'Examen Registrado Como Pendiente en Proceso Titulacion...\');}';}
			}
		}
		mysql_free_result($sql_pt);
	}
	else{ header("location: ../../buscador_alumno_BETA/HALL/index.php");}
}
else
{ header("location: ../../buscador_alumno_BETA/HALL/index.php");}
?>
<html>
<head>
<title>certificado Titulo en Tramite</title>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<script src="../../libreria_publica/JSCal/src/js/jscal2.js"></script>
<script src="../../libreria_publica/JSCal/src/js/lang/es.js"></script>

<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/steel/steel.css">
<style type="text/css">
<!--
#apDiv1 {	position:absolute;
	width:200px;
	height:115px;
	z-index:8;
	left: 594px;
	top: 78px;
}
#apDiv2 {	position:absolute;
	width:200px;
	height:115px;
	z-index:8;
	left: 575px;
	top: 86px;
}
#apDiv3 {
	position:absolute;
	width:462px;
	height:39px;
	z-index:9;
	left: 102px;
	top: 283px;
}
.Estilo4 {font-size: 12px}
-->
</style>
<script language="javascript">
<?php echo $funcion_js;?>
</script>
</head>

<body>
<h1 id="banner">Administrador - Certificado titulo en Tramite</h1>
<div id="link"><br><a href="../../buscador_alumno_BETA/HALL/index.php" class="button">Volver al Menu</a> </div>
<div id="layer" style="position:absolute; left:101px; top:84px; width:384px; height:136px; z-index:7">
  <form action="<?php echo $action;?>" method="post"  enctype="multipart/form-data" name="frm"  id="frm">
    <table width="462" border="0">
    <thead>
      <tr>
        <th colspan="2" align="center" class="Estilo8" >Datos Para Certificado</th>
      </tr>
      </thead>
      <tbody>
      <tr class="odd">
        <td width="161"><span class="Estilo7">Firma</span></td>
        <td width="289"><select name="firma">
          <option value="PAOLA MAUREIRA SANCHEZ, Director Acad&eacute;mico">PAOLA MAUREIRA SANCHEZ</option>
          <option value="JUAN PABLO JAÑA PEREZ, Rector">JUAN PABLO JAÑA PEREZ</option>
          <option value="JUAN A. VEGA GONZALEZ, Director Acad&eacute;mico">JUAN A. VEGA GONZALEZ</option>
        </select>        </td>
      </tr>
	  <tr class="odd">
	  <td><span class="Estilo7">Presentado a:
	  </span></td>
	  <td><input name="presentado" type="text" id="presentado" size="40"></td>
	  </tr>
      <tr class="odd">
        <td>Fecha Rinde Examen Titulo</td>
        <td><span class="Estilo4">
          <input name="fecha_examen" type="text" id="fecha_examen" size="11" maxlength="10" value="<?php echo $aux_examen_fecha;?>" onChange="cargarMovimientos();" readonly/>
          <input type="button" name="boton1" id="boton1" value="..."/>
        </span></td>
        </tr>
      <tr class="odd">
        <td colspan="2"><input type="reset" name="Submit" value="Restablecer">          <input type="button" name="Submit2" value="Generar Certificado"  onClick="CONFIRMAR();">
          <input type="hidden" name="nombre_titulo" id="nombre_titulo" value="<?php echo $aux_nombre_titulo;?>"></td>
      </tr>
      </tbody>
    </table>
  </form>
</div>
<div id="apDiv2">
  <?php
	$tipo_certificado="certificado titulo en tramite";
	$cons="SELECT COUNT(id) FROM registro_certificados WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND tipo_certificado='$tipo_certificado'";
	$sql=mysql_query($cons)or die(mysql_error());
	$C=mysql_fetch_row($sql);
	$numero_certificados=$C[0];
	if(empty($numero_certificados))
	{ $numero_certificados=0;}
	mysql_free_result($sql);
	echo"Se han Impreso ($numero_certificados) $tipo_certificado a este Alumno";
	
  ?>
</div>
<div id="apDiv3">
  <strong>

  <?php 
include("../../../funciones/funcion.php");
////////////////////////
$year_actual=date("Y");
$mes_actual=date("m");
if($mes_actual>8)
{ $semestre_actual=2;}
else{ $semestre_actual=1;}
$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
//////////////////////////
	$condicion_contrato=VERIFICA_CONTRATO($id_alumno, $year_actual, $semestre_actual);
	
	if($condicion_contrato)
	{
		$msj="Contrato OK";
	}
	else
	{
		$msj="Alumno, Sin Contrato o Caduco";
	}
	
@mysql_close($conexion);
$conexion_mysqli->close();
	echo $msj;
?> 
</strong></div>
<script type="text/javascript">
//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton1", "fecha_examen", "%Y-%m-%d");
    //]]>
</script>
</body>
</html>