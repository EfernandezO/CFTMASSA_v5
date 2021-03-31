<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("certificado->certificado_de_matricula_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Certificado de Matricula</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<style type="text/css">
<!--
.Estilo5 {font-size: 12px; font-weight: bold; color: #FF0000; }
.Estilo7 {font-size: 12px}
.Estilo8 {font-size: 12px; font-weight: bold; }
#link {
	text-align: right;
	padding-right: 10px;
}
#apDiv1 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:8;
	left: 594px;
	top: 78px;
}
#link #apDiv1 #div_ajax {
	font-style: italic;
	text-align: center;
}
#apDiv2 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:8;
	left: 575px;
	top: 86px;
}
-->
</style>
</head>
<?php
if(isset($_SESSION["AUX_CERTIFICADO"]))
{
	unset($_SESSION["AUX_CERTIFICADO"]);
}
?>
<body>
<h1 id="banner">Administrador - Certificado de Matricula</h1>

<div id="link">
  <div id="apDiv2">
  <?php
  	$rut_alumno=$_SESSION["SELECTOR_ALUMNO"]["rut"];
	$carrera_alumno=$_SESSION["SELECTOR_ALUMNO"]["carrera"];
	$tipo_certificado="certificado de matricula";
  	include("../../../funciones/conexion_v2.php");
	$cons="SELECT COUNT(id) FROM registro_certificados WHERE rut_alumno='$rut_alumno' AND carrera_alumno='$carrera_alumno' AND tipo_certificado='$tipo_certificado'";

	$sql=$conexion_mysqli->query($cons);
	$C=$sql->fetch_row();	
	$numero_certificados=$C[0];
	if(empty($numero_certificados))
	{ $numero_certificados=0;}
	$sql->free();
	$conexion_mysqli->close();

	echo"Se han Impreso ($numero_certificados) $tipo_certificado a este Alumno";
	
  ?>
  </div><br />

<a href="../../buscador_alumno_BETA/HALL/index.php" class="button">Volver al Menu</a></div>
<div id="layer" style="position:absolute; left:101px; top:84px; width:384px; height:160px; z-index:7">
  <form action="verificacion_alumno_matriculado.php" method="post"  enctype="multipart/form-data" name="frm"  id="frm">
    <table width="462" border="0">
      <thead>
        <tr>
          <th colspan="3" align="center"><span class="Estilo7">Ingrese los Datos</span></th>
        </tr>
      </thead>
      <tbody>
        <tr class="odd">
          <td width="142"><span class="Estilo7">Firma</span></td>
          <td colspan="2"><select name="firma">
            <option value="JUAN PABLO JAÑA PEREZ-Rector" selected="selected">JUAN PABLO JAÑA PEREZ</option>
            <option value="JUAN ANTONIO VEGA GONZALEZ-Director Academico">JUAN ANTONIO VEGA GONZALEZ</option>
           
          </select>          </td>
        </tr>
        <tr class="odd">
          <td><span class="Estilo7">Presentado a: </span></td>
          <td colspan="2"><input name="presentado" type="text" id="presentado" size="40" /></td>
        </tr>
        <tr class="odd">
          <td><span class="Estilo7">A&ntilde;o</span></td>
          <td colspan="2">
          <select name="year" id="year">
          <?php
		  $year_actual=date("Y");
		  $year_ini=$year_actual-10;
		  $year_fin=$year_actual+1;
          for($x=$year_ini;$x<=$year_fin;$x++)
		  {
		  	if($x==$year_actual)
			{
				echo'<option value="'.$x.'" selected="selected">'.$x.'</option>';
			}
			else
			{
				echo'<option value="'.$x.'">'.$x.'</option>';
			}
		  }
		  ?>
          </select>          </td>
        </tr>
        <tr class="odd">
          <td>ver logo</td>
          <td><input type="radio" name="ver_logo" id="radio" value="si" />
          <label for="ver_logo">si</label></td>
          <td><input name="ver_logo" type="radio" id="radio2" value="no" checked="checked" />
            no</td>
        </tr>
        <tr class="odd">
          <td colspan="3"><input type="reset" name="Submit2" value="Restablecer" />
          <input type="submit" name="Submit" value="Generar Certificado" /></td>
        </tr>
        <?php
		if(isset($_GET["error"]))
		{
			$error=$_GET["error"];
			switch($error)
			{
				case"1":
					$msj="Alumno No Encontrado";
					break;
				case"2":
					$msj="Sin Contrato Vigente...";
					break;	
			}
		}
		else
		{
			$msj="";
		}
        ?>
        <tr class="odd">
          <td colspan="3"><?php echo"* $msj *";?></td>
        </tr>
      </tbody>
    </table>
  </form>
</div>

</body>
</html>