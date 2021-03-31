<?php
//--------------CLASS_okalis------------------//
require("../../../OKALIS/class_OKALIS_v1.php");
define("DEBUG", false);
$O=new OKALIS();
$O->DEBUG=DEBUG;
$O->ruta_conexion="../../../../funciones/";
$O->clave_del_archivo=md5("Docentes->estudioTrabajo");
$O->PERMITIR_ACCESO_USUARIO();	
//////////////////////XAJAX/////////////////
@require_once ("../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("estudios_laborales_server.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"GRABA_ESTUDIOS");
//---------------------------------------------------------------------------------------------//
$privilegio=$_SESSION["USUARIO"]["privilegio"];
$array_tipo_estudios=array("basica", "media", "tecnica", "universitaria", "magister", "doctorado", "postitulo");
$array_grado_academico=array(1=>"Doctorado",
							 2=>"Magíster",
							 3=>"Especialidad médica u odontológica",
							 4=>"Título Profesional",
							 5=>"Licenciatura",
							 6=>"Técnico de Nivel Superior",
							 7=>"Técnico de Nivel Medio",
							 8=>"Sin título ni grado académico");


$year_i=date("Y")-100;
$year_actual=date("Y");

///////////////////////////////////////////////////////////
if(isset($_GET["id_funcionario"]))
{$id_funcionario=$_GET["id_funcionario"];}
else
{$id_funcionario=$_SESSION["USUARIO"]["id"];}
//-------------------------------------------------------------//
    require("../../../../funciones/conexion_v2.php");
	$cons_1="SELECT rut, nombre, apellido_P, apellido_M FROM personal WHERE id='$id_funcionario' LIMIT 1";
	$sql_1=$conexion_mysqli->query($cons_1);
	$D1=$sql_1->fetch_assoc();
		$F_rut=$D1["rut"];
		$F_nombre=$D1["nombre"];
		$F_apellido=$D1["apellido_P"]." ".$D1["apellido_M"];
	$sql_1->free();	
//------------------------------------------------------------------------///	
switch($privilegio)	
{
	case"Docente":
		$url_menu="../../okdocente.php";
		break;
	case"jefe_carrera":
		$url_menu="../../okdocente.php";
		break;	
	default:
		$url_menu="../../lista_funcionarios.php";
		break;	
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
 <link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<title>Estudios funcionarios</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:50%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 105px;
}
#div_boton_estudios {
	position:absolute;
	width:39%;
	height:30px;
	z-index:2;
	left: 56%;
	top: 194px;
	text-align: center;
}
#div_estudios {
	position:absolute;
	width:90%;
	height:71px;
	z-index:3;
	left: 5%;
	top: 490px;
}
#div_informacion {
	position:absolute;
	width:39%;
	height:41px;
	z-index:4;
	left: 56%;
	top: 244px;
	text-align: center;
}
</style>
<?php $xajax->printJavascript(); ?> 
<script language="javascript">
function CONFIRMAR(id_estudio)
{
	c=confirm('Seguro(a) desea Eliminar este Estudio..?');
	if(c){ window.location="elimina_estudio.php?id_estudio="+id_estudio+"&id_funcionario=<?php echo $id_funcionario;?>";}
}
</script>
<!--INICIO LIGHTBOX EVOLUTION-->
  <script type="text/javascript" src="../../../libreria_publica/lightbox_1.6.9_evolution/js/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.css" />
  <!--[if IE 6]>
  <link rel="stylesheet" type="text/css" href="../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.ie6.css" />
  <![endif]-->
  <script type="text/javascript" src="../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/jquery.lightbox.min.js"></script>
 <script type="text/javascript">
    jQuery(document).ready(function($){
      $('.lightbox').lightbox(); 
    });
  </script>
 <!--FIN LIGHTBOX EVOLUTION--> 
 <link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
 <script src="../../../libreria_publica/JSCal/src/js/jscal2.js"></script>
 <script src="../../../libreria_publica/JSCal/src/js/lang/es.js"></script>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/steel/steel.css">
</head>

<body>
<h1 id="banner">Docentes - Estudios</h1>
<div id="link"><br>
<a href="<?php echo $url_menu;?>" class="button">Volver al Menu</a><br />
<br />
<a href="laborales_1.php?id_funcionario=<?php echo $id_funcionario;?>" class="button">Registros Laborales</a>
</div>
<div id="apDiv1">
<form action="#" method="post" id="frm_1">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="3">Agregar Estudios
        <input name="id_funcionario" type="hidden" id="id_funcionario" value="<?php echo $id_funcionario;?>" /></th>
    </tr>
    <tr>
      <td>Rut</td>
      <td colspan="2"><?php echo $F_rut;?></td>
    </tr>
    <tr>
      <td>Funcionario</td>
      <td colspan="2"><?php echo $F_nombre." ".$F_apellido;?></td>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="36%">Tipo estudio</td>
      <td colspan="2"><label for="tipo_estudio"></label>
        <select name="tipo_estudio" id="tipo_estudio">
        <?php
        foreach($array_tipo_estudios as $n =>$valor)
		{ echo'<option value="'.$valor.'">'.$valor.'</option>';}
		?>
        </select></td>
    </tr>
    <tr>
      <td>nombre institucion</td>
      <td colspan="2"><label for="nombre_institucion"></label>
        <input name="nombre_institucion" type="text" id="nombre_institucion" size="45" /></td>
    </tr>
    <tr>
      <td>Titulo</td>
      <td colspan="2"><label for="titulo"></label>
        <input name="titulo" type="text" id="titulo" size="45" /></td>
    </tr>
    <tr>
      <td>Grado academico Obtenido</td>
      <td colspan="2"><label for="grado_academico"></label>
        <select name="grado_academico" id="grado_academico">
        <?php
        foreach($array_grado_academico as $n => $valor)
		{
			 if($n==8)
			  { $select='selected="selected"';}
			  else
			  { $select="";}
			echo'<option value="'.$n.'" '.$select.'>'.$n.'_'.$valor.'</option>';
		}
		?>
        </select>
        </td>
    </tr>
    <tr>
      <td>Fecha Obtencion Titulo</td>
      <td colspan="2"><input  name="fecha_obtencion_titulo" id="fecha_obtencion_titulo" size="15" maxlength="10" readonly="readonly" value="<?php echo date("Y-m-d");?>"/>
        <input type="button" name="boton1" id="boton1" value="..." /></td>
    </tr>
    <tr>
      <td>Pais Obtencion Titulo/grado academico</td>
      <td colspan="2">
      <select name="pais">
      <?php
      include("../../../../funciones/lista_paises.php");
	  foreach($paises as $n => $valor)
	  {
		  if($valor=="Chile")
		  { $select='selected="selected"';}
		  else
	  	  { $select="";}
		  echo'<option value="'.$valor.'" '.$select.'>'.$valor.'</option>';
	  }
	  ?>
      </select></td>
    </tr>
    <tr>
      <td>Descripcion</td>
      <td colspan="2"><label for="descripcion"></label>
        <input name="descripcion" type="text" id="descripcion" size="45" /></td>
    </tr>
    <tr>
      <td>Periodo</td>
      <td width="20%">
        <select name="year_inicio" id="year_inicio">
        <?php for($i=$year_i;$i<=$year_actual;$i++)
		{ 
			if($i==$year_actual)
			{echo'<option value="'.$i.'" selected="selected">'.$i.'</option>';}
			else{ echo'<option value="'.$i.'">'.$i.'</option>';}
		}?>
        </select></td>
      <td width="44%">
        <select name="year_fin" id="year_fin">
         <?php for($i=$year_i;$i<=$year_actual;$i++)
		{ 
			if($i==$year_actual)
			{echo'<option value="'.$i.'" selected="selected">'.$i.'</option>';}
			else{ echo'<option value="'.$i.'">'.$i.'</option>';}
		}?>
        </select></td>
    </tr>
    </tbody>
  </table>
  </form>
</div>
<div id="div_boton_estudios"><a href="#" class="button_G" onclick="xajax_GRABA_ESTUDIOS(xajax.getFormValues('frm_1')); return false;">Grabar Estudios</a></div>
<div id="div_estudios">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="12">Estudios Registrados</th>
    </tr>
    <tr>
      <td>N</td>
      <td>Tipo Estudio</td>
      <td>Nombre Institucion</td>
      <td>A&ntilde;o Inicio</td>
      <td>A&ntilde;o Fin</td>
      <td>Titulo</td>
	  <td>grado academico</td>
	  <td>Pais</td>
	  <td>Fecha titulo</td>
      <td>Descripcion</td>
      <td>Archivo</td>
      <td>Opcion</td>
    </tr>
     </thead>
    <tbody>
    <?php
   
	$cons_E="SELECT * FROM personal_registro_estudios WHERE id_funcionario='$id_funcionario' ORDER by id";
	$sql_E=$conexion_mysqli->query($cons_E);
	$num_registros=$sql_E->num_rows;
	if($num_registros>0)
	{
		$aux=0;
		while($E=$sql_E->fetch_assoc())
		{
			$aux++;
			
			$E_id=$E["id"];
			$E_tipo_estudio=$E["tipo_estudio"];
			$E_nombre_institucion=$E["nombre_institucion"];
			$E_year_inicio=$E["year_inicio"];
			$E_year_fin=$E["year_fin"];
			$E_titulo=$E["titulo"];
			$E_cod_grado_academico=$E["cod_grado_academico"];
			$E_pais=$E["pais_titulo"];
			$E_fecha_obtencion_titulo=$E["fecha_titulo"];
			$E_descripcion=$E["descripcion"];
			$E_archivo=$E["archivo"];
			$path="../../../CONTENEDOR_GLOBAL/docente_estudios/";
			
			if((empty($E_archivo))or($E_archivo=="NULL")){ $archivo_X='<a href="carga_archivo/carga_archivo_1.php?E_id='.base64_encode($E_id).'&id_funcionario='.base64_encode($id_funcionario).'&lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=450"  class="lightbox button_R" title="">Cargar</a>';}
			else{ $archivo_X='<a href="carga_archivo/carga_archivo_1.php?E_id='.base64_encode($E_id).'&id_funcionario='.base64_encode($id_funcionario).'&lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=450"  class="lightbox button" title="">Ver</a>';}
			
			echo'<tr>
				  <td>'.$aux.'</td>
				  <td>'.$E_tipo_estudio.'</td>
				  <td>'.$E_nombre_institucion.'</td>
				  <td>'.$E_year_inicio.'</td>
				  <td>'.$E_year_fin.'</td>
				  <td>'.$E_titulo.'</td>
				  <td>'.$E_cod_grado_academico.'</td>
				  <td>'.$E_pais.'</td>
				  <td>'.$E_fecha_obtencion_titulo.'</td>
				  <td>'.$E_descripcion.'</td>
				  <td>'.$archivo_X.'</td>
				  <td>
				  <a href="editar_estudios_1.php?E_id='.base64_encode($E_id).'&id_funcionario='.base64_encode($id_funcionario).'&lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=450"  class="lightbox" title="Editar"><img src="../../../BAses/Images/b_edit.png" width="16" height="16" alt="editar" /></a>
				  <a href="#" onclick="CONFIRMAR('.$E_id.');" title="Eliminar"><img src="../../../BAses/Images/b_drop.png" width="16" height="16" alt="Eliminar" /></a>
				  </td>
				</tr>';
		}
	}
	else
	{
		echo'<tr><td colspan="8">Sin Registro de Estudios Previos</td></tr>';
	}
	$sql_E->free();
	$conexion_mysqli->close();
	?>
    </tbody>
  </table>
</div>

<div id="div_informacion"></div>
<script type="text/javascript">
//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton1", "fecha_obtencion_titulo", "%Y-%m-%d");
    //]]>
</script>
</body>
</html>