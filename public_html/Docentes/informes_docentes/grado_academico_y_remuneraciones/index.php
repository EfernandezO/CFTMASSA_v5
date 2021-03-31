<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("informe_docente_aca_remu");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

$array_semestre=array(1,2,0);
   $mes_actual=date("m");
   
   if($mes_actual>=8)///utilizo agosto para inicio 2 semeste
   { $semestre_actual=2;}
   else{ $semestre_actual=1;}
   
   $sede_actual=$_SESSION["USUARIO"]["sede"];
   require("../../../../funciones/funciones_sistema.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>informes | Docente</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 114px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:28px;
	z-index:2;
	left: 30%;
	top: 328px;
	text-align: center;
}
#apDiv3 {
	position:absolute;
	width:40%;
	height:35px;
	z-index:3;
	left: 30%;
	top: 395px;
	text-align: center;
}
</style>
<script language="javascript">
function CONFIRMAR()
{
	c=confirm('Seguro(a) Desea Continuar..?');
	if(c){ document.getElementById('frm').submit();}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Informe Docente Grado Academico y Remuneraciones</h1>
<div id="link"><br />
<a href="../../lista_funcionarios.php" class="button">Volver al Menu</a></div>
<div id="apDiv1">
<form action="grado_academico_y_remuneraciones_2.php" method="post" id="frm" >
<table width="40%" border="1" align="center">
<thead>
  <tr>
    <th colspan="2">seleccion de  Docente</th>
  </tr>
</thead>
<tbody> 
<tr>
	<td width="50%">Sede</td>
	<td width="50%"><?php echo CAMPO_SELECCION("sede","sede","",true);?></td>
    </tr>
<tr>
  <td>Semestre</td>
  <td><?php echo CAMPO_SELECCION("semestre", "semestre", "", true);?></td>
</tr>
<tr>
  <td>A&ntilde;o</td>
  <td><select name="year" id="year">
        <?php
	  	$a�os_anteriores=10;
		$a�os_siguientes=1;
	  	$year_actual=date("Y");
		
		$a�o_ini=$year_actual-$a�os_anteriores;
		$a�o_fin=$year_actual+$a�os_siguientes;
		
		for($a=$a�o_ini;$a<=$a�o_fin;$a++)
		{
			if($a==$year_actual)
			{echo'<option value="'.$a.'" selected="selected">'.$a.'</option>';	}
			else
			{echo'<option value="'.$a.'">'.$a.'</option>';}	
		}
	  ?>
        </select></td>
</tr>
</tbody>    
</table>
</form>
</div>
<div id="apDiv2"><a href="#" class="button_G" onclick="CONFIRMAR();">Continuar</a></div>
<div id="apDiv3">
</div>
</body>
</html>