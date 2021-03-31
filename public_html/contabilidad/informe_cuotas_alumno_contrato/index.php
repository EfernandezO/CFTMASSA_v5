<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="inspeccion";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
//-----------------------------------------//	
$privilegio=$_SESSION["USUARIO"]["privilegio"];
switch($privilegio)
{
	case"inspeccion":
		$url_menu="../../Administrador/menu_inspeccion/index.php";
		break;
	default:
		$url_menu="../index.php";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Administrador - informe</title>
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
	top: 97px;
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
#link {
	text-align: right;
	padding-right: 10px;
}
#apDiv1 {
	position:absolute;
	width:40%;
	height:35px;
	z-index:2;
	left: 30%;
	top: 532px;
	text-align: center;
}
-->
</style>
</head>

<body>
<h1 id="banner">Administrador - Alumno X curso y Cuotas</h1>
<div id="link"><br><a href="<?php echo $url_menu;?>" class="button">Volver al menu Principal </a>
  </div>
<div id="Layer1">
<form action="cuota_alumno_contrato.php" method="post" name="frm" id="frm">
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

    include("../../../funciones/conexion.php");
   
   $res="SELECT id, carrera FROM carrera where id >= 0";
   $result=mysql_query($res);
   $carrera_oculta=$_POST["ocultocarrera"];
   //echo "--------> $carrera_oculta<br>";
   ?>
   <select name="carrera" id="carrera">
   <?php
   while($row = mysql_fetch_array($result)) 
   {
    $nomcar=$row["carrera"];
	$id_carrera=$row["id"];
	
		echo'<option value="'.$id_carrera.'_'.$nomcar.'">'.$nomcar.'</option>';
   }
    mysql_free_result($result); 
    mysql_close($conexion);
	 ?>
     <option value="0">Todas</option>
        </select></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo1">A&ntilde;o Ingreso </span></td>
      <td colspan="5"><select name="ano_ingreso" id="ano_ingreso">
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
      <option value="Todos">Todos</option>
      </select>      </td>
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
      <td><span class="Estilo1">Nivel</span></td>
      <td width="42">1
        <br />
        <input name="nivel[]" type="checkbox" id="nivel[]" value="1" />
        <label for="nivel[]"></label></td>
      <td width="36">2
        <br />        <input name="nivel[]2" type="checkbox" id="nivel[]2" value="2" /></td>
      <td width="32">3<br />        <input name="nivel[]3" type="checkbox" id="nivel[]3" value="3" /></td>
      <td width="28">4<br />        <input name="nivel[]4" type="checkbox" id="nivel[]4" value="4" /></td>
      <td width="33">5<br />        <input name="nivel[]5" type="checkbox" id="nivel[]5" value="5" /></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo1">Estado</span></td>
      <td colspan="5"><select name="estado" id="estado">
        <option value="V">Vigente</option>
        <option value="T">Titulados</option>
        <option value="A" selected="selected">Todos</option>
        <option value="E">Egresado</option>
        <option value="P">Postergado</option>
        <option value="R">Retirado</option>
        <option value="E">Eliminado</option>
        </select></td>
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
<div id="apDiv1">Muestra alumnos con contrato vigente segun criterios seleccionados, detallando aporte x beca y total de linea de credito.</div>
</body>
</html>
