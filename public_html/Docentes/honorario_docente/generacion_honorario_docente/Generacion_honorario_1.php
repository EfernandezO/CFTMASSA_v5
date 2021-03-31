<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Genera_honorario_1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("proceso_server.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"COMPRUEBA_HONORARIO");
//---------------------------------------------------------///

$array_semestre=array(1,2);
   $mes_actual=date("m");
   $array_meses=array(1=>"Enero",
   						2=>"Febrero",
						3=>"Marzo",
						4=>"Abril",
						5=>"Mayo",
						6=>"junio",
						7=>"Julio",
						8=>"Agosto",
						9=>"Septiembre",
						10=>"Octubre",
						11=>"Noviembre",
						12=>"Diciembre");
						
   if($mes_actual>=8)///utilizo agosto para inicio 2 semeste
   { $semestre_actual=2;}
   else{ $semestre_actual=1;}
   ///////////////////////////////////////////////
if(isset($_SESSION["HONORARIO"])){ unset($_SESSION["HONORARIO"]);}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>Docentes | Generacion Honorario</title>
<?php $xajax->printJavascript(); ?> 
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:101px;
	z-index:1;
	left: 5%;
	top: 152px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:51px;
	z-index:2;
	left: 30%;
	top: 216px;
	text-align: center;
}
</style>
<script language="javascript">
function CONFIRMAR()
{
	c=confirm("Seguro(a) Desea Generar los Honorarios en Base a las Asignaciones actualmente Generadas...z?");
	if(c)
	{document.getElementById('frm').submit();}
}
</script>
</head>

<body onload="xajax_COMPRUEBA_HONORARIO(document.getElementById('fsede').value, document.getElementById('mes').value, document.getElementById('year').value, document.getElementById('year_generacion').value); return false;">
<h1 id="banner">Funcionarios - Generacion de Honorario</h1>
<div id="link"><br>
<a href="../../lista_funcionarios.php" class="button">Volver al Menu</a></div>
<div id="apDiv1">
<form action="Generacion_honorario_2.php" method="get" enctype="application/x-www-form-urlencoded" id="frm">
  <table width="50%" border="1" align="center">
  <thead>
    <tr>
      <th colspan="5">Parametros </th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="43%">Sede</td>
      <td colspan="4"><?php include("../../../../funciones/funcion.php"); echo selector_sede("fsede",'onchange="xajax_COMPRUEBA_HONORARIO(this.value, document.getElementById(\'mes\').value, document.getElementById(\'year\').value, document.getElementById(\'year_generacion\').value)"; return false;');?></td>
    </tr>
    <tr>
      <td>Periodo Asignaciones</td>
      <td>Semestre        </td>
      <td><select name="semestre" id="semestre">
        <?php 
		  foreach($array_semestre as $n => $valor)
		  {
			  if($valor==$semestre_actual)
			  { $selected='selected="selected"';}
			  else
			  { $selected='';}
			  
			  echo'<option value="'.$valor.'" '.$selected.'>'.$valor.'</option>';
		  }
		  ?>
      </select></td>
      <td>A&ntilde;o</td>
      <td><select name="year" id="year" onchange="xajax_COMPRUEBA_HONORARIO(document.getElementById('fsede').value, document.getElementById('mes').value, document.getElementById('year').value, document.getElementById('year_generacion').value); return false;">
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
      <td>&nbsp;</td>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td>Generado en</td>
      <td width="12%"><label for="mes">Mes</label></td>
      <td width="11%"><select name="mes" id="mes" onchange="xajax_COMPRUEBA_HONORARIO(document.getElementById('fsede').value, document.getElementById('mes').value, document.getElementById('year').value, document.getElementById('year_generacion').value); return false;">
        <?php
        foreach($array_meses as $n => $valor)
		{
			if($n==$mes_actual)
			{echo'<option value="'.$n.'" selected="selected">'.$valor.'</option>';}
			else{echo'<option value="'.$n.'">'.$valor.'</option>';}
		}
		?>
      </select></td>
      <td width="24%">Año</td>
      <td width="10%"><?php require("../../../../funciones/funciones_sistema.php"); echo CAMPO_SELECCION("year_generacion","year",date("Y"),false ,'onchange="xajax_COMPRUEBA_HONORARIO(document.getElementById(\'fsede\').value, document.getElementById(\'mes\').value, document.getElementById(\'year\').value, document.getElementById(\'year_generacion\').value)"');?></td>
    </tr>
    <tr>
      <td colspan="5">
        <div id="apDiv2"><div id="div_x">...
        </div>
        </div>
        ...</td>
      </tr>
    </tbody>
  </table>
  </form>
</div>

</body>
</html>