<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Exportar_alumno_SuperdeSalud_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>sup. de .salud -  Formato</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:91px;
	z-index:1;
	left: 5%;
	top: 109px;
}
a:link {
	color: #006699;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #006699;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #006699;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:38px;
	z-index:2;
	left: 5%;
	top: 299px;
	text-align: center;
}
-->
</style>
</head>

<body>
<h1 id="banner">Administrador - Alumnos Titulados Super Int. Salud.</h1>
<div id="link">
  <div align="right"><br />
<a href="../../Alumnos/menualumnos.php" class="button">Volver al Menu</a></div>
</div>
<div id="apDiv1">
<form action="titulados_tens_super_int_salud.php" method="post" name="frm" id="frm">
  <table width="40%" border="0" align="center">
  <thead>
    <tr>
      <th colspan="3" >Parametros para Busqueda</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="42%">Sede</td>
      <td width="58%" colspan="2"><?php
	  include("../../../funciones/funcion.php");
	  echo selector_sede(); 
	  ?></td>
    </tr>
    <tr>
      <td>A&ntilde;o titulacion (acta)</td>
      <td colspan="2"><select name="year" id="year">
        <?php
	  	$ańos_anteriores=10;
		$ańos_siguientes=1;
	  	$ańo_actual=date("Y");
		
		$ańo_ini=$ańo_actual-$ańos_anteriores;
		$ańo_fin=$ańo_actual+$ańos_siguientes;
		
		for($a=$ańo_ini;$a<=$ańo_fin;$a++)
		{
			if($a==$ańo_actual)
			{
				echo'<option value="'.$a.'" selected="selected">'.$a.'</option>';	
			}
			else
			{
				echo'<option value="'.$a.'">'.$a.'</option>';
			}	
		}
	  ?>
        <option value="todos">Todos</option>
      </select></td>
    </tr>
    <tr>
      <td >Marcar Alumnos</td>
      <td ><input type="radio" name="marcar_alumnos" id="marcar_alumnos" value="si" />
        <label for="marcar_alumnos">Si</label></td>
      <td ><input name="marcar_alumnos" type="radio" id="marcar_alumnos2" value="no" checked="checked" />
        No</td>
    </tr>
    <tr>
      <td colspan="3" ><div align="right">
        <input type="submit" name="button" id="button" value="Consultar" />
      </div></td>
      </tr>
      </tbody>
  </table>
  </form>
</div>
<div id="apDiv2">Genera Archivo .TXT(texto) Con los Datos de Todos Los Alumnos<br />
  Titulados TENS por Sede, en base al Formato dado por Super intendecia de Salud.<br />
  <br />
Los alumnos debe cumplir con los siguientes requisitos para ser listados:<br />
-condicion: titulado<br />
-proceso de titulacion realizado, registrando numero de registro de titulo
y fecha de emision.<br />
</div>
</body>
</html>