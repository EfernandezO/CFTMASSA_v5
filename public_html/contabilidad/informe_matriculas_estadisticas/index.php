<?php 
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Comparador_matriculas_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
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
-->
</style>
   <script src="../../libreria_publica/JSCal/src/js/jscal2.js"></script>
 <script src="../../libreria_publica/JSCal/src/js/lang/es.js"></script>
 
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/steel/steel.css">
<style type="text/css">
#apDiv2 {
	position:absolute;
	width:40%;
	height:50px;
	z-index:2;
	left: 30%;
	top: 318px;
	text-align: center;
}
</style>
</head>
<body>
<h1 id="banner">Administrador - Matriculas Estadisticas</h1>
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
	$array_meses=array(1=>"Enero",
						2=>"Febrero",
						3=>"Marzo",
						4=>"Abril",
						5=>"Mayo",
						6=>"Junio",
						7=>"Julio",
						8=>"Agosto",
						9=>"Septiembre",
						10=>"Octubre",
						11=>"Noviembre",
						12=>"Diciembre",);
		$year_actual=date("Y");				
?>
<div id="link"><br />
<a href="<?php echo $url;?>" class="button">Volver al Menu</a></div>
<div id="apDiv1">
<form action="estadisticas_matricula.php" method="post" name="frm" id="frm">
  <table width="40%" border="0" align="center">
  <thead>
    <tr>
      <th colspan="2"> Parametros Para Generar informe</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td >Alumnos</td>
      <td ><label for="tipo_alumnos"></label>
        <select name="tipo_alumnos" id="tipo_alumnos">
          <option value="nuevos">Nuevos</option>
          <option value="todos">Todos</option>
        </select></td>
    </tr>
    <tr>
      <td >Mes consultar</td>
      <td >
      <select name="mes_consulta" id="mes_consulta">
      <?php
      foreach($array_meses as $n =>$valor)
	  {
		  echo'<option value="'.$n.'_'.$valor.'">'.$valor.'</option>';
	  }
	  ?>
      </select>
      </td>
    </tr>
    <tr>
      <td >A&ntilde;o contrato a consultar 1</td>
      <td ><select name="year_consulta_1" id="year_consulta_1">
          <?php
				$año_actual=date("Y");
				$año_ini=$año_actual-10;
				$año_fin=$año_actual+1;
            	for($a=$año_ini;$a<=$año_fin;$a++)
				{
						if($a==$año_actual-1)
						{
							echo'<option value="'.$a.'" selected="selected">'.$a.'</option>';
						}
						else
						{
							echo'<option value="'.$a.'" >'.$a.'</option>';
						}
				}
			?>
          </select></td>
    </tr>
    <tr>
      <td width="54%" >A&ntilde;o contrato a consultar 2</td>
      <td width="46%" ><span class="Estilo2 Estilo2">
        <select name="year_consulta_2" id="year_consulta_2">
          <?php
				$año_actual=date("Y");
				$año_ini=$año_actual-10;
				$año_fin=$año_actual+1;
            	for($a=$año_ini;$a<=$año_fin;$a++)
				{
						if($a==$año_actual)
						{
							echo'<option value="'.$a.'" selected="selected">'.$a.'</option>';
						}
						else
						{
							echo'<option value="'.$a.'" >'.$a.'</option>';
						}
				}
			?>
          </select>
        </span></td>
    </tr>
    <tr>
      <td >&nbsp;</td>
      <td ><input type="submit" name="button" id="button" value="Consultar" /></td>
    </tr>
    </tbody>
  </table>
  </form>
</div>
<div id="apDiv2">
  <p>Genera Graficos comparativos  por a&ntilde;o de la totalidad de matriculas<br />
    generadas en un mes determinada en distintas sede.
    <br />
    no considerando la condicion actual de ese contrato ('ok', 'retiro','inactivo'), solo el total
  </p>
  <p>desde mes 10 en adelante utiliza el a&ntilde;o anterior para calcular la cantidad de matriculas antes del periodo.</p>
</div>
<script type="text/javascript">//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton1", "fecha_ini", "%Y-%m-%d");
	   cal.manageFields("boton2", "fecha_fin", "%Y-%m-%d");

    //]]></script>
</body>
</html>