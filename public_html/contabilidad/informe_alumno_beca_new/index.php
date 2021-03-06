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
<h1 id="banner">Administrador - Informe Alumnos Becados V1.3</h1>
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
      <td >Sede</td>
      <td colspan="8" ><?php
	  include("../../../funciones/funcion.php");
	  echo CAMPO_SELECCION("fsede", "sede","0",true);
	  ?>
      </td>
    </tr>
    <tr>
      <td width="26%" >Nivel</td>
      <td colspan="8" ><?php echo CAMPO_SELECCION("nivel", "niveles_academicos","0",true);?></td>
    </tr>
    <tr>
      <td >Ingreso</td>
      <td colspan="8" ><?php echo CAMPO_SELECCION("year_ingreso", "year","0",true);?></td>
    </tr>
    <tr>
      <td >Carrera</td>
      <td colspan="8" ><span class="Estilo2 Estilo2">
          <?php echo CAMPO_SELECCION("carrera", "carreras","0",true); ?>
      </span></td>
    </tr>
    <tr>
      <td rowspan="2" >Filtrar</td>
      <td width="17%" colspan="2" >        BNM</td>
      <td width="20%" colspan="2" >        BET</td>
      <td width="16%" colspan="2" >        Desc. $</td>
      <td width="21%" colspan="2" >        Desc %</td>
    </tr>
    <tr>
      <td ><input name="mostrar_2[BNM]" type="radio" id="BNM" value="1" />
        <label for="BET"></label>
        Si</td>
      <td ><input name="mostrar_2[BNM]" type="radio" id="BNM2" value="0" checked="checked" />
        No</td>
      <td ><input name="mostrar_2[BET]" type="radio" id="BNM3" value="1" />
        Si</td>
      <td ><input name="mostrar_2[BET]" type="radio" id="BNM4" value="0" checked="checked" />
        No</td>
      <td ><input name="mostrar_2[cantidad_desc]" type="radio" id="BNM5" value="1" />
        Si</td>
      <td ><input name="mostrar_2[cantidad_desc]" type="radio" id="BNM6" value="0" checked="checked" />
        No</td>
      <td ><input name="mostrar_2[porcentaje_desc]" type="radio" id="BNM7" value="1" />
        Si</td>
      <td ><input name="mostrar_2[porcentaje_desc]" type="radio" id="BNM8" value="0" checked="checked" />
        No</td>
    </tr>
    <tr>
      <td >A&ntilde;o Contrato</td>
      <td colspan="8" ><select name="year_vigencia_contrato" id="year_vigencia_contrato">
        <?php
	  	$a??os_anteriores=10;
		$a??os_siguientes=1;
	  	$a??o_actual=date("Y");
		
		$a??o_ini=$a??o_actual-$a??os_anteriores;
		$a??o_fin=$a??o_actual+$a??os_siguientes;
		
		for($a=$a??o_ini;$a<=$a??o_fin;$a++)
		{
			if($a==$a??o_actual)
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
      <td rowspan="2" >Considerar Vigencia semestral</td>
      <td colspan="8" ><input type="radio" name="considerar_semestre" id="considerar_semestre2" value="1" />
        Si</td>
    </tr>
    <tr>
      <td colspan="8" ><input name="considerar_semestre" type="radio" id="considerar_semestre" value="0" checked="checked" />
        No
        
        <label for="considerar_semestre"></label></td>
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
    Filtra los contratos de acuerdo a su vigencia, considerando el a??o y semestre.<br />
  <strong>Considera solo el ultimo contrato para el periodo consultado. </strong></p>
</div>
</body>
</html>