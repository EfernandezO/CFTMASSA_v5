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
$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"GRABA_EMPLEOS");
//---------------------------------------------------------------------------------------------//
$privilegio=$_SESSION["USUARIO"]["privilegio"];
$array_tipo_estudios=array("basica", "media", "tecnica", "universitaria", "magister", "doctorado", "postitulo");

$year_i=date("Y")-100;
$year_actual=date("Y");

/////////////////////////////////////////////////////////////
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
//-------------------------------------------------------------//
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
<title>Laborales</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:40%;
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
	top: 331px;
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
function CONFIRMAR(id_laborales)
{
	c=confirm('Seguro(a) desea Eliminar este Empleo');
	if(c){ window.location="elimina_laborales.php?id_laborales="+id_laborales+"&id_funcionario=<?php echo $id_funcionario;?>";}
}
</script>
</head>

<body>
<h1 id="banner">Docentes - Trabajo</h1>
<div id="link"><br>
<a href="<?php echo $url_menu;?>" class="button">Volver al Menu</a><br />
<br />
<a href="estudio_1.php?id_funcionario=<?php echo $id_funcionario;?>" class="button">Registro Estudios</a>
</div>
<div id="apDiv1">
<form action="#" method="post" id="frm_1">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="3">Agregar Registros Laborales
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
      <td width="30%">cargo</td>
      <td colspan="2"><label for="cargo"></label>
        <input type="text" name="cargo" id="cargo" /></td>
    </tr>
    <tr>
      <td>Empresa</td>
      <td colspan="2"><label for="empresa"></label>
        <input type="text" name="empresa" id="empresa" /></td>
    </tr>
    <tr>
      <td>Descripcion</td>
      <td colspan="2"><label for="descripcion"></label>
        <input type="text" name="descripcion" id="descripcion" /></td>
    </tr>
    <tr>
      <td>Periodo</td>
      <td width="70%">
        <select name="year_inicio" id="year_inicio">
        <?php for($i=$year_i;$i<=$year_actual;$i++)
		{ 
			if($i==$year_actual)
			{echo'<option value="'.$i.'" selected="selected">'.$i.'</option>';}
			else{ echo'<option value="'.$i.'">'.$i.'</option>';}
		}?>
        </select></td>
      <td>
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
<div id="div_boton_estudios"><a href="#" class="button_G" onclick="xajax_GRABA_EMPLEOS(xajax.getFormValues('frm_1')); return false;">Grabar Trabajos</a></div>
<div id="div_estudios">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="7"> Registros Laborales</th>
    </tr>
    <tr>
      <td>N</td>
      <td>Cargo</td>
      <td>Empresa</td>
      <td>A&ntilde;o Inicio</td>
      <td>A&ntilde;o Fin</td>
      <td>Descripcion</td>
      <td>Opciones</td>
      </tr>
       </thead>
    <tbody>
    <?php
	$cons_E="SELECT * FROM personal_registro_laborales WHERE id_funcionario='$id_funcionario' ORDER by id";
	$sql_E=$conexion_mysqli->query($cons_E);
	$num_registros=$sql_E->num_rows;
	if($num_registros>0)
	{
		$aux=0;
		while($E=$sql_E->fetch_assoc())
		{
			$aux++;
			
			$E_id=$E["id"];
			$E_cargo=$E["cargo"];
			$E_empresa=$E["empresa"];
			$E_year_inicio=$E["year_inicio"];
			$E_year_fin=$E["year_fin"];
			$E_descripcion=$E["descripcion"];
			
			echo'<tr>
				  <td>'.$aux.'</td>
				  <td>'.$E_cargo.'</td>
				  <td>'.$E_empresa.'</td>
				  <td>'.$E_year_inicio.'</td>
				  <td>'.$E_year_fin.'</td>
				  <td>'.$E_descripcion.'</td>
				   <td><a href="#" onclick="CONFIRMAR('.$E_id.');"><img src="../../../BAses/Images/b_drop.png" width="16" height="16" alt="Eliminar" /></a></td>
				</tr>';
		}
	}
	else
	{
		echo'<tr><td colspan="7">Sin Registro de Empleos Previos</td></tr>';
	}
	$sql_E->free();
	$conexion_mysqli->close();
	?>
    </tbody>
  </table>
</div>
<div id="div_informacion"></div>
</body>
</html>