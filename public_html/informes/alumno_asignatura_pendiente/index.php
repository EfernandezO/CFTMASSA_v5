<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	OKALIS($lista_invitados);
//-----------------------------------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Alumnos | Asignaturas Pendientes</title>
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
#apDiv2 {
	position:absolute;
	width:90%;
	height:38px;
	z-index:2;
	left: 5%;
	top: 377px;
	text-align: center;
}
-->
</style>
</head>

<body>
<h1 id="banner">Administrador - Alumnos y asignaturas Pendientes</h1>
<div id="link">
  <div align="right"><br />
<a href="../../Alumnos/menualumnos.php" class="button">Volver al Menu</a></div>
</div>
<div id="apDiv1">
<form action="alumno_asignaturas_pendientes.php" method="post" name="frm" id="frm">
  <table width="50%" border="0" align="center">
  <thead>
    <tr>
      <th colspan="6">Parametros para Busqueda</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="42%">Sede</td>
      <td width="58%" colspan="5"><?php
	  include("../../../funciones/funcion.php");
	  echo selector_sede(); 
	  ?></td>
    </tr>
    <tr>
      <td>Carrera</td>
      <td colspan="5">
       <select name="carrera" id="carrera">
		   <?php
		    include("../../../funciones/conexion.php");
		   $res="SELECT id, carrera FROM carrera where id >= 0";
		   $result=mysql_query($res);
           while($row = mysql_fetch_array($result)) 
           {
			   $id_carrera=$row["id"];
            	$nomcar=$row["carrera"];
                echo'<option value="'.$id_carrera.'_'.$nomcar.'">'.$nomcar.'</option>';	
           }
            mysql_free_result($result); 
            mysql_close($conexion);
             ?>
        </select>
      </td>
      </tr>
    <tr>
      <td>Nivel</td>
      <td>1.
        
        <label for="nivel[]">
          <input name="nivel" type="radio" id="nivel" value="1" checked="checked" />
        </label></td>
      <td>2.        
        <input type="radio" name="nivel" id="nivel2" value="2" /></td>
      <td>3.        
        <input type="radio" name="nivel" id="nivel3" value="3" /></td>
      <td>4.        
        <input type="radio" name="nivel" id="nivel4" value="4" /></td>
      <td>5.        
        <input type="radio" name="nivel" id="nivel5" value="5" /></td>
    </tr>
    <tr>
      <td>A&ntilde;o ingreso</td>
      <td colspan="5"><select name="year" id="year">
        <option value="Todos" selected="selected">Todos</option>
        <?php
	  	$años_anteriores=25;
		$años_siguientes=1;
	  	$año_actual=date("Y");
		
		$año_ini=$año_actual-$años_anteriores;
		$año_fin=$año_actual+$años_siguientes;
		
		for($a=$año_ini;$a<=$año_fin;$a++)
		{
			if($a==$año_actual)
			{
				echo'<option value="'.$a.'">'.$a.'</option>';	
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
      <td>Ver alumnos</td>
      <td colspan="5">
        <select name="mostrar_alumnos" id="mostrar_alumnos">
          <option value="todos">todos</option>
          <option value="matriculados">matriculados</option>
          <option value="no_matriculados">no_matriculados</option>
        </select></td>
    </tr>
    <tr>
      <td colspan="6"><div align="right">
        <input type="submit" name="button" id="button" value="Consultar" />
      </div></td>
      </tr>
      </tbody>
  </table>
  </form>
</div>
<div id="apDiv2">Muestra los alumnos que tiene alguna situacion pendiente<br />
sus asignatura y detalles de ello.</div>
</body>
</html>