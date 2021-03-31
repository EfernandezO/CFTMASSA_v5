<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", true);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("ranking_alumnos");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Administrador - Ranking Alumno</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:90%;
	height:186px;
	z-index:1;
	left: 5%;
	top: 102px;
}
.Estilo1 {font-size: 12px}
#Layer2 {
	position:absolute;
	width:168px;
	height:16px;
	z-index:2;
	left: 420px;
	top: 49px;
}
#apDiv1 {
	position:absolute;
	width:40%;
	height:50px;
	z-index:2;
	left: 30%;
	top: 350px;
	text-align: center;
}
-->
</style>
</head>

<body>
<h1 id="banner">Administrador - Ranking Alumnos</h1>
<div id="link"><br>
  <a href="../../Alumnos/menualumnos.php" class="button">Volver al menu Alumno </a>
  </div>
<div id="Layer1">
<form action="ranking_alumnos.php" method="post" name="frm" id="frm">
  <div id="apDiv1">Segun los Parametros de Selecciona<br />
    Busca los alumnos vigentes y promedia sus notas<br />
    luego se muestra un resumen con el mejor promedio x carrera
  </div>
  <table width="50%" border="1" align="center">
  <caption></caption>
  <thead>
    <tr>
      <th colspan="6"><span class="Estilo1">Busqueda de Alumnos </span></th>
    </tr>
	</thead>
	<tbody>
    <tr class="odd">
      <td width="160"><span class="Estilo1">Sede</span></td>
      <td colspan="5"><?php
	  include("../../../funciones/funcion.php");
	  echo selector_sede(); 
	  ?></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo1">Carrera</span></td>
      <td colspan="5">
<?php 

    include("../../../funciones/conexion_v2.php");
   
   $res="SELECT id, carrera FROM carrera";
   $result=mysql_query($res);
   ?>
   <select name="carrera" id="carrera">
   <?php
   while($row = mysql_fetch_array($result)) 
   {
	    $id_carrera=$row["id"];
    	$nomcar=$row["carrera"];
		echo'<option value="'.$id_carrera.'_'.$nomcar.'">'.$nomcar.'</option>';
   }
    mysql_free_result($result); 
    mysql_close($conexion);
	 ?>
     <option value="0_todas">Todas</option>
        </select></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo1">Jornada</span></td>
      <td colspan="5"><select name="jornada" id="jornada">
        <option value="D">Diurno</option>
        <option value="V">Vespertino</option>
        <option value="T" selected="selected">Todas</option>
        </select></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo1">Grupo</span></td>
      <td colspan="5"><select name="grupo" id="grupo">
        <option value="Todos" selected="selected">Todos</option>
        <option value="A">A</option>
        <option value="B">B</option>
        <option value="C">C</option>
        <?php 
		
		foreach(range('A', 'Z') as $letra)
		{
				echo'<option value="'.$letra.'">'.$letra.'</option>';
		}
		?>
      </select></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo1">Nivel Actual</span></td>
      <td width="42">1
        <br />
        <input name="nivel[]" type="checkbox" id="nivel[]" value="1" checked="checked" />
        <label for="nivel[]"></label></td>
      <td width="36">2
        <br />        <input name="nivel[]2" type="checkbox" id="nivel[]2" value="2" checked="checked" /></td>
      <td width="32">3<br />        <input name="nivel[]3" type="checkbox" id="nivel[]3" value="3" checked="checked" /></td>
      <td width="28">4<br />        <input name="nivel[]4" type="checkbox" id="nivel[]4" value="4" checked="checked" /></td>
      <td width="33">5<br />        <input name="nivel[]5" type="checkbox" id="nivel[]5" value="5" checked="checked" /></td>
    </tr>
    <tr class="odd">
      <td>A&ntilde;o Vigencia Contrato</td>
      <td colspan="5"><select name="year_vigencia_contrato" id="year_vigencia_contrato">
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
    <tr class="odd">
      <td>Semestre Vigencia Contrato</td>
      <td colspan="5"><select name="semestre_vigencia_contrato" id="semestre_vigencia_contrato">
        <?php
            $array_semestre=array(1,2);
			$mes_actual=date("m");
			if($mes_actual>8)
			{ $semestre_actual=2;}
			else
			{ $semestre_actual=1;}
			foreach($array_semestre as $n => $valor)
			{
				if($valor==$semestre_actual)
				{ echo'<option value="'.$valor.'" selected="selected">'.$valor.' Semestre</option>';}
				else
				{  echo'<option value="'.$valor.'">'.$valor.' Semestre</option>';}
			}
			?>
      </select></td>
    </tr>
    </tbody>
	<tfoot>
	  <tr>
	    <td colspan="6"><div align="right">
	      <input type="submit" name="Submit" value="Generar Informe" />
	      </div></td>
	    </tr>
	  </tfoot>
  </table>
 </form> 
</div>
</body>
</html>
