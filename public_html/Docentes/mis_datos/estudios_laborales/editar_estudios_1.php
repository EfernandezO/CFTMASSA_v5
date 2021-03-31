<?php
	//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Docentes->estudioTrabajo");
	$O->PERMITIR_ACCESO_USUARIO();
//-----------------------------------------//	
if(isset($_GET["E_id"]))
{
	$E_id=base64_decode($_GET["E_id"]);
	$id_funcionario=base64_decode($_GET["id_funcionario"]);
	if((is_numeric($E_id))and($E_id>0))
	{$continuar=true;}
	else
	{$continuar=false;}
}
else
{ $continuar=false;}
//-------------------------------------//
$action="#";
//----------------------------------//
if($continuar)
{
	$action="editar_estudios_2.php";
	$year_i=date("Y")-100;
	$year_actual=date("Y");
	$array_tipo_estudios=array("basica", "media", "tecnica", "universitaria", "magister", "doctorado", "postitulo");
	$array_grado_academico=array(1=>"Doctorado",
							 2=>"Magíster",
							 3=>"Especialidad médica u odontológica",
							 4=>"Título Profesional",
							 5=>"Licenciatura",
							 6=>"Técnico de Nivel Superior",
							 7=>"Técnico de Nivel Medio",
							 8=>"Sin título ni grado académico");
	
	require("../../../../funciones/conexion_v2.php");
	
	$cons_E="SELECT * FROM personal_registro_estudios WHERE id_funcionario='$id_funcionario' AND id='$E_id' LIMIT 1";
	$sqli_E=$conexion_mysqli->query($cons_E);
	$E=$sqli_E->fetch_assoc();
		$E_tipo_estudio=$E["tipo_estudio"];
			$E_nombre_institucion=$E["nombre_institucion"];
			$E_year_inicio=$E["year_inicio"];
			$E_year_fin=$E["year_fin"];
			$E_titulo=$E["titulo"];
			$E_cod_grado_academico=$E["cod_grado_academico"];
			$E_pais=$E["pais_titulo"];
			$E_fecha_obtencion_titulo=$E["fecha_titulo"];
			$E_descripcion=$E["descripcion"];
	$sqli_E->free();
	
	$conexion_mysqli->close();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
 <link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
 <script src="../../../libreria_publica/JSCal/src/js/jscal2.js"></script>
 <script src="../../../libreria_publica/JSCal/src/js/lang/es.js"></script>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/steel/steel.css">
<title>Edicion de Estudios</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 52px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:52px;
	z-index:2;
	left: 30%;
	top: 384px;
}
</style>
<script language="javascript">
function CONFIRMAR()
{
	c=confirm('Seguro desea Modificar estos datos..?');
	if(c){ document.getElementById('frm').submit();}
}
</script>
</head>

<body>
<h1 id="banner">Docentes - Editar Estudios</h1>
<div id="apDiv1">
<?php if($continuar){?>
<form action="<?php echo $action;?>" method="post" id="frm">
  <table width="90%" border="1" align="center">
  <thead>
    <tr>
      <th colspan="3"><input name="id_funcionario" type="hidden" id="id_funcionario" value="<?php echo $id_funcionario;?>" />
        <input name="E_id" type="hidden" id="E_id" value="<?php echo $E_id;?>" />
        Estudios del Docente</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="39%">Nombre institucion</td>
      <td colspan="2"><label for="nombre_institucion"></label>
        <input name="nombre_institucion" type="text" id="nombre_institucion" value="<?php echo $E_nombre_institucion;?>" size="50" /></td>
    </tr>
    <tr>
      <td>Periodo</td>
      <td width="37%"><select name="year_inicio" id="year_inicio">
        <?php for($i=$year_i;$i<=$year_actual;$i++)
		{ 
			if($i==$E_year_inicio)
			{echo'<option value="'.$i.'" selected="selected">'.$i.'</option>';}
			else{ echo'<option value="'.$i.'">'.$i.'</option>';}
		}?>
        </select></td>
      <td width="24%"> <select name="year_fin" id="year_fin">
         <?php for($i=$year_i;$i<=$year_actual;$i++)
		{ 
			if($i==$E_year_fin)
			{echo'<option value="'.$i.'" selected="selected">'.$i.'</option>';}
			else{ echo'<option value="'.$i.'">'.$i.'</option>';}
		}?>
        </select></td>
    </tr>
    <tr>
      <td>Titulo/grado</td>
      <td colspan="2"><label for="titulo"></label>
        <input name="titulo" type="text" id="titulo" value="<?php echo $E_titulo;?>" size="50" /></td>
    </tr>
    <tr>
      <td>cod grado academico</td>
      <td colspan="2"><select name="grado_academico" id="grado_academico">
        <?php
        foreach($array_grado_academico as $n => $valor)
		{
			 if($n==$E_cod_grado_academico)
			  { $select='selected="selected"';}
			  else
			  { $select="";}
			echo'<option value="'.$n.'" '.$select.'>'.$n.'_'.$valor.'</option>';
		}
		?>
        </select></td>
    </tr>
    <tr>
      <td>Pais titulo/grado</td>
      <td colspan="2"> <select name="pais">
      <?php
      include("../../../../funciones/lista_paises.php");
	  foreach($paises as $n => $valor)
	  {
		  if($valor==$E_pais)
		  { $select='selected="selected"';}
		  else
	  	  { $select="";}
		  echo'<option value="'.$valor.'" '.$select.'>'.$valor.'</option>';
	  }
	  ?>
      </select></td>
    </tr>
    <tr>
      <td>Fecha obtencion titulo/grado</td>
      <td colspan="2"><input  name="fecha_obtencion_titulo" id="fecha_obtencion_titulo" size="15" maxlength="10" readonly="readonly" value="<?php echo $E_fecha_obtencion_titulo;?>"/>
        <input type="button" name="boton1" id="boton1" value="..." /></td>
    </tr>
    <tr>
      <td>Descripcion</td>
      <td colspan="2"><label for="descripcion"></label>
        <input name="descripcion" type="text" id="descripcion" size="50"  value="<?php echo $E_descripcion;?>"/></td>
    </tr>
    </tbody>
  </table>
 <?php }?> 
 </form>
</div>
<div id="apDiv2"><a href="#" class="button_G" onclick="CONFIRMAR();">Grabar Datos</a></div>
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