<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_pagare_emitidos_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
require("../../../funciones/funciones_sistema.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Pagare Emitidos</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:129px;
	z-index:1;
	left: 5%;
	top: 96px;
}
.Estilo1 {	font-size: 12px;
	font-style: italic;
}
.Estilo2 {font-size: 12px}
.Estilo3 {	font-size: 12px;
	font-weight: bold;
}
#link {	text-align: right;
	padding-right: 10px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:45px;
	z-index:2;
	left: 30%;
	top: 286px;
	text-align: center;
}
-->
</style>
</head>
<body>
<h1 id="banner">Administrador - Pagar&eacute; Generados</h1>
<div id="link"><br />
<a href="../index.php" class="button">Volver al Menu</a></div>
<div id="apDiv1">
  <form action="informe_pagare.php" method="post" name="frm" id="frm" target="_blank">
    <table width="50%" border="0" align="center">
      <thead>
        <tr>
          <th colspan="3"><span class="Estilo3">Parametros</span></th>
        </tr>
      </thead>
      <tbody>
        <tr class="odd">
          <td width="39%"><span class="Estilo1">Mes</span></td>
          <td width="61" colspan="2"><label for="mes"></label>
           <?php echo CAMPO_SELECCION("mes", "meses"); ?></td>
        </tr>
        <tr class="odd">
          <td><span class="Estilo1">A&ntilde;o</span></td>
          <td colspan="2"><label for="year"></label>
         <?php echo CAMPO_SELECCION("year", "year");?>
       </td>
        </tr>
        <tr class="odd">
          <td height="22"><span class="Estilo2">Sede</span></td>
          <td colspan="2"> <?php echo CAMPO_SELECCION("sede", "sede","",true);?></td>
        </tr>
        <tr>
          <td colspan="3"><div align="right">
            <input type="submit" name="button" id="button" value="Consultar" />
          </div></td>
        </tr>
         </tbody>
    </table>
  </form>
</div>
<div id="apDiv2">Genera informe .xlsx de los pagare emitidos en el<br />
  mes, a&ntilde;o y sede indicados.
</div>
</body>
</html>