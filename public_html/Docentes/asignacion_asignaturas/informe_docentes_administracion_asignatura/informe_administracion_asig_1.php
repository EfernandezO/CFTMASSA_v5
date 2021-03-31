<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("informe_asignacion_asignatura_docente_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$mes_actual=date("m");
if($mes_actual>8)
{ $semestre_actual=2;}
else
{ $semestre_actual=1;}

$array_semestre=array(1=>"1",2=>"2");
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>Revision Asignaciones General</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {	position:absolute;
	width:90%;
	height:101px;
	z-index:1;
	left: 5%;
	top: 152px;
}
#apDiv2 {
	position:absolute;
	width:35%;
	height:31px;
	z-index:2;
	left: 35%;
	top: 237px;
}
</style>
<script language="javascript">
function VERIFICAR()
{
	document.getElementById('frm').submit();
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Informe Asignacion Asignatura</h1>
<div id="link"><br>
<a href="../../lista_funcionarios.php" class="button">Volver al Menu</a></div>
<div id="apDiv1">
  <form action="informe_administracion_asig_2.php" method="post" id="frm">
    <table width="40%" border="1" align="center">
      <thead>
        <tr>
          <th colspan="3">Parametros </th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Sede</td>
          <td colspan="2"><?php include("../../../../funciones/funcion.php"); echo selector_sede("fsede");?></td>
        </tr>
        <tr>
          <td>A&ntilde;o</td>
          <td colspan="2"><select name="year" id="year">
            <?php
	  	$anos_anteriores=10;
		$anos_siguientes=1;
	  	$year_actual=date("Y");
		
		$ano_ini=$year_actual-$anos_anteriores;
		$ano_fin=$year_actual+$anos_siguientes;
		
		for($a=$ano_ini;$a<=$ano_fin;$a++)
		{
			if($a==$year_actual)
			{echo'<option value="'.$a.'" selected="selected">'.$a.'</option>';	}
			else
			{echo'<option value="'.$a.'">'.$a.'</option>';}	
		}
	  ?>
          </select></td>
        </tr>
        <tr>
          <td>Semestre</td>
          <td colspan="2">
            <select name="semestre" id="semestre">
              <?php
        foreach($array_semestre as $n => $valor)
		{
			if($n==$semestre_actual)
			{echo'<option value="'.$n.'" selected="selected">'.$valor.'</option>';}
			else{echo'<option value="'.$n.'">'.$valor.'</option>';}
		}
		?>
          </select></td>
        </tr>
        <tr>
          <td colspan="3"><div id="apDiv2">
            <div id="div_x"><a href="#" class="button_G" onclick="VERIFICAR();">Revisar, Asignaciones</a></div>
          </div>
            ...</td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
</body>
</html>