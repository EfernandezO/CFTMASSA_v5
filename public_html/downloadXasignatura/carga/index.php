<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	$lista_invitados["privilegio"][]="admi";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define("DEBUG", false);
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("busca_asignaturas_server.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCAR_ASIGNATURAS");
$xajax->register(XAJAX_FUNCTION,"BUSCAR_CARRERAS");
$xajax->register(XAJAX_FUNCTION,"BUSCAR_SEDES");
$xajax->register(XAJAX_FUNCTION,"CARGA_ARCHIVOS");
/////////////desbloqueo contenido si es administrador
$desbloqueado=false;

if(($_SESSION["USUARIO"]["privilegio"]=="admi_total")or($_SESSION["USUARIO"]["privilegio"]=="admi"))
{$desbloquear=true;}
////////intento cabiar permisos de carpeta...
	$permisos_carpeta=fileperms("../../CONTENEDOR_GLOBAL/cargaXasignatura");
	$permisos_carpeta=substr(decoct($permisos_carpeta),1);
	if(DEBUG){ echo"Permisos: $permisos_carpeta<br>";}
	if($permisos_carpeta!="0777")
	{
		if(chmod("../../CONTENEDOR_GLOBAL/cargaXasignatura", 0777))
		{ echo"Permisos Cambiados... :-)<br>";}
		else
		{ echo"<b>Fallo al intentar Cambiar Permisos... :-(</b><br>";}
	}
//------------------------------------------------------------//
////////////////////////------------////////////////////////////
$fecha_actual=date("Y-m-d");
$nombre_usuario=$_SESSION["USUARIO"]["nombre"]." ".$_SESSION["USUARIO"]["apellido"];
require("../../../funciones/conexion_v2.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Carga archivo X asignatura</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/tooltip_1/tooltip.css"/>

<script language="javascript">
function Confirmar(id)
{
	c=confirm('Seguro(a) que desea Eliminar este Recurso..?');
	if(c)
	{window.location="elimina_archivo.php?id_recurso="+id;}
}
function MSJ(txt)
{alert(txt);}
</script>    
<style type="text/css">
<!--
.Estilo2 {font-size: 12px}
.Estilo3 {
	font-size: 14px;
	font-weight: bold;
	font-style: italic;
}
#content #msj {
	height: 50px;
	text-decoration: blink;
	font-size: 16px;
}
#apDiv1 {
	position:absolute;
	width:199px;
	height:20px;
	z-index:1;
	left: 347px;
	top: 67px;
}
#apDiv2 {
	position:absolute;
	width:259px;
	height:115px;
	z-index:1;
	left: 622px;
	top: 54px;
}
#apDiv3 {
	position:absolute;
	width:279px;
	height:115px;
	z-index:2;
	left: 623px;
	top: 201px;
	font-size: 16px;
}
#apDiv4 {
	position:absolute;
	width:90%;
	height:405px;
	z-index:3;
	left: 5%;
}
#apDiv5 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:4;
}
#apDiv6 {
	position:absolute;
	width:45%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 78px;
}
#apDiv7 {
	position:absolute;
	width:45%;
	height:115px;
	z-index:2;
	left: 5%;
	top: 350px;
}
#apDiv8 {
	position:absolute;
	width:40%;
	height:26px;
	z-index:3;
	left: 55%;
	top: 151px;
	text-align: center;
}
#apDiv9 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:4;
	left: 5%;
	top: 572px;
}
-->
</style>
<?php $xajax->printJavascript(); ?> 
</head>
<body>
<?php
$id_usuario=$_SESSION["USUARIO"]["id"];
$privilegio=$_SESSION["USUARIO"]["privilegio"];
$sede_usuario=$_SESSION["USUARIO"]["sede"];
$mes_actual=date("m");
$year_actual=date("Y");

///segundo semestre desde mes 8 por inicio semestre intitucion
if($mes_actual>=8){$semestre_actual=2;}
else{ $semestre_actual=1;}
//echo"Semestre actual= $semestre_actual<br>";

$array_jornada=array("D"=>"Diurno","V"=>"Vespertino");
$array_sede=array();

require("../../../funciones/funcion.php");
$label="";

	switch($privilegio)
	{
		case"admi":
			$url_menu="../../Administrador/ADmenu.php";	
			$campo_sede=selector_sede("sede", 'onchange="xajax_BUSCAR_CARRERAS(this.value, document.getElementById(\'semestre\').value, document.getElementById(\'year\').value); return false;"',false,true);
			$label="Administrador";
			break;
		case"admi_total":
			$url_menu="../../Administrador/ADmenu.php";	
			$campo_sede=selector_sede("sede", 'onchange="xajax_BUSCAR_CARRERAS(this.value, document.getElementById(\'semestre\').value, document.getElementById(\'year\').value); return false;"',false,true);
			$label="Administrador";
			break;
		case"Docente":
			$label="Docente";
			$campo_sede="";
			$url_menu="../../Docentes/okdocente.php";
				//---------------------------------------------//
				//seleccion de Sede
				$cons_sede="SELECT DISTINCT(sede) FROM toma_ramo_docente WHERE id_funcionario='$id_usuario' AND semestre='$semestre_actual' AND year='$year_actual'";
				$sql_sede=$conexion_mysqli->query($cons_sede)or die("Sede: ".$conexion_mysqli->error);
				$num_sede=$sql_sede->num_rows;
				if(DEBUG){ echo"->$cons_sede<br>Num sede: $num_sede<br>";}
				if($num_sede>0)
				{
					$campo_sede='<select id="sede" name="sede" onchange="xajax_BUSCAR_CARRERAS(this.value, document.getElementById(\'semestre\').value, document.getElementById(\'year\').value); return false;">
								<option value="0">Seleccione</option>';
					while($S=$sql_sede->fetch_assoc())
					{
						$aux_sede=$S["sede"];
						$campo_sede.='<option value="'.$aux_sede.'">'.$aux_sede.'</option>';
					}
					$campo_sede.='</select>';
				}
				else
				{ $campo_sede="...";}
				$sql_sede->free();
				//---------------------------------------------//
			break;
		case"jefe_carrera":
			$label="Jefe Carrera";
			$url_menu="../../Docentes/okdocente.php";
			//---------------------------------------------//
				//seleccion de Sede
				$cons_sede="SELECT DISTINCT(sede) FROM toma_ramo_docente WHERE id_funcionario='$id_usuario' AND semestre='$semestre_actual' AND year='$year_actual'";
				$sql_sede=$conexion_mysqli->query($cons_sede)or die("Sede: ".$conexion_mysqli->error);
				$num_sede=$sql_sede->num_rows;
				if(DEBUG){ echo"->$cons_sede<br>Num sede: $num_sede<br>";}
				if($num_sede>0)
				{
					$campo_sede='<select id="sede" name="sede" onchange="xajax_BUSCAR_CARRERAS(this.value, document.getElementById(\'semestre\').value, document.getElementById(\'year\').value); return false;">
								<option value="0">Seleccione</option>';
					while($S=$sql_sede->fetch_assoc())
					{
						$aux_sede=$S["sede"];
						$campo_sede.='<option value="'.$aux_sede.'">'.$aux_sede.'</option>';
					}
					$campo_sede.='</select>';
				}
				else
				{ $campo_sede="...";}
				$sql_sede->free();
				//---------------------------------------------//
			break;
	}
$conexion_mysqli->close();

?>
<h1 id="banner"><?php echo $label;?> - Cargar Recursos X Curso</h1>
<div id="link"><br />
<a href="<?php echo $url_menu;?>" class="button">Volver al Menu </a></div>

  <form id="form1" action="graba_bbdd.php" enctype="multipart/form-data" method="post">
  
  <div id="apDiv6">
<table width="100%" align="center" >
    <thead>
            	<tr>
                	<th colspan="2">Identificacion de Curso</th>
                </tr>
    </thead>
            <tbody>
				<tr>
				  <td>Semestre</td>
				  <td><select name="semestre" id="semestre" onchange="xajax_BUSCAR_SEDES(this.value, document.getElementById('year').value); return false;">
        <option value="1" <?php if($semestre_actual==1){ echo'selected="selected"';}?>>1</option>
        <option value="2" <?php if($semestre_actual==2){ echo'selected="selected"';}?>>2</option>
      </select></td>
			  </tr>
				<tr>
				  <td>A&ntilde;o</td>
				  <td><select name="year" id="year" onchange="xajax_BUSCAR_SEDES(document.getElementById('semestre').value, this.value); return false;">
        <?php
       	$year_inicio=2000;
		$year_final=$year_actual;
		for($y=$year_inicio;$y<=$year_final;$y++)
		{
			if($y==$year_actual)
			{ echo'<option value="'.$y.'" selected="selected">'.$y.'</option>';}
			else
			{ echo'<option value="'.$y.'">'.$y.'</option>';}
		}
	   ?>
      </select></td>
			  </tr>
				<tr>
				  <td><label for="lastname2">Sede</label></td>
				  <td>
				  <div id="div_sede">
       <?php echo $campo_sede;?>
      </div>
                  </td>
			  </tr>
				<tr>
				  <td><label for="lastname3">Carrera</label></td>
				  <td><div id="div_carrera">...
        <input name="carrera" type="hidden" id="carrera" value="0" />
      </div></td>
			  </tr>
				<tr >
					<td><label for="firstname">Asignatura</label></td>
					<td><div id="div_asignatura"><div id="div_asignaturas">...
        <input name="asignatura" type="hidden" id="asignatura" value="0" />
        </div></div>
				    </td>
				</tr>
				<tr>
				  <td>Jornada</td>
				  <td><select name="jornada" id="jornada">
        <?php
	  	foreach($array_jornada as $nj => $valor)
		{echo'<option value="'.$nj.'">'.$valor.'</option>';}
	  ?>
      </select></td>
			  </tr>
				<tr>
				  <td>Grupo</td>
				  <td><select name="grupo_curso" id="grupo_curso4">
        <option value="A" selected="selected">A</option>
        <option value="B">B</option>
      </select></td>
			  </tr>
           </tbody>
    </table>
 </div>   
  <div id="apDiv7">
<table width="100%">
      <thead>
           	<tr>
            	<th colspan="2">Archivo
      </thead>
           <tbody>
				<tr>
					<td><label for="txtFileName">Archivo:</label></td>
					<td><label for="archivo"></label>
				    <input type="file" name="archivo" id="archivo" /></td>
				</tr>
				<tr>
				  <td><label for="references2">Titulo:</label></td>
				  <td><label for="titulo"></label>
			      <input name="titulo" type="text" id="titulo" maxlength="20" /></td>
			  </tr>
				<tr>
					<td><label for="references">Descripci&oacute;n:</label></td>
					<td><textarea name="descripcion" id="descripcion" cols="0" rows="0" style="width: 300px;"></textarea></td>
				</tr>
				<tr>
				  <td>&nbsp;</td>
				  <td><input type="submit" value="Cargar Archivo" id="btnSubmit" /></td>
			  </tr>
      </tbody>
	</table>
</div>    
    
</form>

  <div id="apDiv8">
<?php
  $msj="";
  if(isset($_GET["error"]))
  {
  	$error=$_GET["error"];
	$img_ok='<img src="../../BAses/Images/ok.png" width="29" height="26" alt="ok" />';
	$img_error='<img src="../../BAses/Images/b_drop.png" width="16" height="16" alt="X" />';
	switch($error)
	{
		case"0":
			$msj='<span class="Estilo4">Archivo Cargada Correctamente</span>';
			$img=$img_ok;
			break;
		case"1":
			$msj='<span class="Estilo5">Error al Cargar Archivo</span>';
			$img=$img_error;
			break;
		case"2":
			$msj='<span class="Estilo5">Datos NO recibidos...</span>';
			$img=$img_error;
			break;			
		case"3":
			$msj='<span class="Estilo5">ID de Archivo Invalido</span>';
			$img=$img_error;
			break;	
		case"4":
			$msj='<span class="Estilo5">Imposible Eliminar Error en Consulta</span>';
			$img=$img_error;
			break;
		case"5":
			$msj='<span class="Estilo4">Archivo Eliminada Correctamente...</span>';
			$img=$img_ok;
			break;		
	}
  }
  else
  { $msj=""; $img="";}
  echo $img.$msj;
  ?>
 <br /> <br />
 <a href="#" onclick="xajax_CARGA_ARCHIVOS(xajax.getFormValues('form1')); return false;" class="button_R tooltip" title="Al Presionar este Boton Busca Archivos Previamnete Cargados segun los parametros de curso.">actualizar</a></div>
  <div id="apDiv9">
    ...
  </div>
</body>
</html>