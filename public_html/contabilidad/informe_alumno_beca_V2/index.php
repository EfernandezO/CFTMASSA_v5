<?php 
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_alumnos_beneficio_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
 require("../../../funciones/funciones_sistema.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Informe Contratos -  Matriculas</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 93px;
}
#link {
	text-align: right;
	padding-right: 10px;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:26px;
	z-index:2;
	left: 62px;
	top: 486px;
	text-align: center;
}
-->
</style>
</head>
<body>
<h1 id="banner">Administrador - Informe Beneficios Estudiantiles</h1>
<?php
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	switch($privilegio)
	{
		case"inspeccion":
			$url="../../Administrador/menu_inspeccion/index.php";
			break;
		default:
			$url="../index.php";	
	}
?>
<div id="link"><br />
<a href="<?php echo $url;?>" class="button">Volver al Menu</a></div>
<div id="apDiv1">
<form action="alumnos_becados_new.php" method="post" name="frm" id="frm">
  <table width="60%" border="0" align="center">
  <thead>
    <tr>
      <th colspan="9">Parametros Para Generar informe</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td >A&ntilde;o Contrato</td>
      <td width="74" colspan="8" ><select name="year_vigencia_contrato" id="year_vigencia_contrato">
        <?php
	  	$años_anteriores=10;
		$años_siguientes=1;
	  	$año_actual=date("Y");
		
		$año_ini=$año_actual-$años_anteriores;
		$año_fin=$año_actual+$años_siguientes;
		
		for($a=$año_ini;$a<=$año_fin;$a++)
		{
			if($a==$año_actual)
			{
				echo'<option value="'.$a.'" selected="selected">'.$a.'</option>';	
			}
			else
			{
				echo'<option value="'.$a.'">'.$a.'</option>';
			}	
		}
	  ?>
        </select></td>
    </tr>
    <tr>
      <td >&nbsp;</td>
      <td colspan="8" ><input type="submit" name="button" id="button" value="continuar" /></td>
    </tr>
    </tbody>
  </table>
  </form>
</div>
<div id="apDiv2">
  <p>Busca Alumnos con algun tipo de desc. en pesos o porcentaje<br />
    ademas contabiliza la cantidad de alumnos con por cada tipo de beneficio asignado con sus <br />
    valores de aporte Totalizados.
  <br />
    Filtra los contratos de acuerdo a su vigencia, considerando el año y semestre.<br />
  <strong>Considera el ultimo contrato que tenga algun beneficio, segun el periodo consultado.</strong></p>
</div>
</body>
</html>