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
$xajax->register(XAJAX_FUNCTION,"GRABA_EMPLEOS");

function GRABA_ESTUDIOS($FORMULARIO)
{
	$html="";
	$error="";
	$grabar_estudio=true;
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	$fecha_actual=date("Y-m-d");
	
	$id_funcionario=$FORMULARIO["id_funcionario"];
	$tipo_estudio=$FORMULARIO["tipo_estudio"];
	$nombre_institucion=strtolower($FORMULARIO["nombre_institucion"]);
	$titulo=(strtolower($FORMULARIO["titulo"]));
	$descripcion=(strtolower($FORMULARIO["descripcion"]));
	$year_inicio=$FORMULARIO["year_inicio"];
	$year_fin=$FORMULARIO["year_fin"];
	
	$cod_grado_academico=$FORMULARIO["grado_academico"];
	$fecha_obtencion_titulo=$FORMULARIO["fecha_obtencion_titulo"];
	$pais=$FORMULARIO["pais"];
	
	$div="div_estudios";
	$objResponse = new xajaxResponse();
	
	if(DEBUG){$objResponse->Alert("ID funcionario: $id_funcionario \n Tipo estudio: $tipo_estudio \n Nombre Institucion: $nombre_institucion \n Titulo: $titulo \n descripcion: $descripcion \n Periodo: $year_inicio - $year_fin \n");}
	//--------------------------------------------------------------------//
	require("../../../../funciones/conexion_v2.php");
	
	if(empty($nombre_institucion)){ $grabar_estudio=false; $objResponse->Alert("Ingrese Nombre de Institucion Educacional...");}
	
	
	
	if($grabar_estudio)
	{
		$campos="id_funcionario, tipo_estudio, nombre_institucion, year_inicio, year_fin, titulo, cod_grado_academico, pais_titulo, fecha_titulo, descripcion, fecha_generacion, cod_user";
		$valores="'$id_funcionario', '$tipo_estudio', '$nombre_institucion', '$year_inicio', '$year_fin', '$titulo', '$cod_grado_academico', '$pais', '$fecha_obtencion_titulo', '$descripcion', '$fecha_actual', '$id_usuario_actual'";
		$cons_IN="INSERT INTO personal_registro_estudios ($campos) VALUES ($valores)";
		if(DEBUG){$objResponse->Alert("--->$cons_IN"); $error="debug";}
		else
		{
			if($conexion_mysqli->query($cons_IN))
			{ 
				$error="E0";
				include("../../../../funciones/VX.php");
				$evento="Registra Estudios a Docente id_docente:$id_funcionario tipo_estudio: $tipo_estudio";
				REGISTRA_EVENTO($evento);
			}
			else
			{ $error="E1";}
		}
			
	}
	
	

	//-----------------------------------------------------------------------//
	//para mostrar en div informacion
	$img_ok='<img src="../../../BAses/Images/ok.png" width="29" height="26" alt="ok" />';
	$img_error='<img src="../../../BAses/Images/b_drop.png" width="16" height="16" alt="x" />';
	$html_msj="";
	switch($error)
	{
		case"E0":
			$html_msj=$img_ok;
			$html_msj.="Estudios Agregados...";
			break;
		case"E1":
			$html_msj=$img_error;
			$html_msj="Fallo al Agregar Estudios...";
			break;
		default:
			$html_msj="";		
	}
	$objResponse->Assign("div_informacion","innerHTML",$html_msj);	
	//-----------------------------------------------------------------------------------------------------------//
	////muestra tabla principal
	
	$html_tabla_principal='<table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="12">Estudios Registrados</th>
    </tr>
    </thead>
    <tbody>
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
    </tr>';
	$cons_E="SELECT * FROM personal_registro_estudios WHERE id_funcionario='$id_funcionario' ORDER by id";
	$sql_E=$conexion_mysqli->query($cons_E)or die($conexion_mysqli->error);
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
			
	$html_tabla_principal.='<tr>
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
				  <td><a href="#" onclick="CONFIRMAR('.$E_id.');"><img src="../../../BAses/Images/b_drop.png" width="16" height="16" alt="Eliminar" /></a></td>
				</tr>';
		}
	}
	else
	{
		$html_tabla_principal.='<tr><td colspan="7">Sin Registro de Estudios Previos</td></tr>';
	}
	$sql_E->free();
    $html_tabla_principal.='</tbody></table>';
	//-----------------------------------------------------------------------------------------------------------///
	
	$objResponse->Assign($div,"innerHTML",$html_tabla_principal);	
	$conexion_mysqli->close();
	return $objResponse;
}
//----------------------------------------------------------------------///
function GRABA_EMPLEOS($FORMULARIO)
{
	$html="";
	$error="";
	$grabar_empleo=true;
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	$fecha_actual=date("Y-m-d");
	
	$id_funcionario=$FORMULARIO["id_funcionario"];
	$cargo=strtolower($FORMULARIO["cargo"]);
	$empresa=strtolower($FORMULARIO["empresa"]);
	$descripcion=strtolower($FORMULARIO["descripcion"]);
	$year_inicio=$FORMULARIO["year_inicio"];
	$year_fin=$FORMULARIO["year_fin"];
	
	$div="div_estudios";
	$objResponse = new xajaxResponse();
	
	if(DEBUG){$objResponse->Alert("ID funcionario: $id_funcionario \n cargo: $cargo \n Empresa: $empresa \n descripcion: $descripcion \n Periodo: $year_inicio - $year_fin \n");}
	//--------------------------------------------------------------------//
	require("../../../../funciones/conexion_v2.php");
	
	if(empty($cargo)){ $grabar_empleo=false; $objResponse->Alert("Ingrese Cargo...");}
	if(empty($empresa)){ $grabar_empleo=false; $objResponse->Alert("Ingrese Empresa...");}
	
	
	
	if($grabar_empleo)
	{
		$campos="id_funcionario, cargo, empresa, descripcion, year_inicio, year_fin, fecha_generacion, cod_user";
		$valores="'$id_funcionario', '$cargo', '$empresa', '$descripcion', '$year_inicio', '$year_fin', '$fecha_actual', '$id_usuario_actual'";
		$cons_IN="INSERT INTO personal_registro_laborales ($campos) VALUES ($valores)";
		if(DEBUG){$objResponse->Alert("--->$cons_IN"); $error="debug";}
		else
		{
			if($conexion_mysqli->query($cons_IN))
			{ 
				$error="E0";
				include("../../../../funciones/VX.php");
				$evento="Registra Trabjos a Docente id_docente:$id_funcionario empresa: $empresa";
				REGISTRA_EVENTO($evento);
			}
			else
			{ $error="E1";}
		}
			
	}
	
	

	//-----------------------------------------------------------------------//
	//para mostrar en div informacion
	$img_ok='<img src="../../../BAses/Images/ok.png" width="29" height="26" alt="ok" />';
	$img_error='<img src="../../../BAses/Images/b_drop.png" width="16" height="16" alt="x" />';
	$html_msj="";
	switch($error)
	{
		case"E0":
			$html_msj=$img_ok;
			$html_msj.="Empleos Agregados...";
			break;
		case"E1":
			$html_msj=$img_error;
			$html_msj="Fallo al Agregar Empleos...";
			break;
		default:
			$html_msj="";		
	}
	$objResponse->Assign("div_informacion","innerHTML",$html_msj);	
	//-----------------------------------------------------------------------------------------------------------//
	////muestra tabla principal
	$html_tabla_principal='<table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="6"> Registros Laborales</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>N</td>
      <td>Cargo</td>
      <td>Empresa</td>
      <td>A&ntilde;o Inicio</td>
      <td>A&ntilde;o Fin</td>
      <td>Descripcion</td>
      </tr>';

	$cons_E="SELECT * FROM personal_registro_laborales WHERE id_funcionario='$id_funcionario' ORDER by id";
	$sql_E=$conexion_mysqli->query($cons_E)or die($conexion_mysqli->error);
	$num_registros=$sql_E->num_rows;
	if($num_registros>0)
	{
		$aux=0;
		while($E=$sql_E->fetch_assoc())
		{
			$aux++;
			
			$E_cargo=$E["cargo"];
			$E_empresa=$E["empresa"];
			$E_year_inicio=$E["year_inicio"];
			$E_year_fin=$E["year_fin"];
			$E_descripcion=$E["descripcion"];
			
			$html_tabla_principal.='<tr>
				  <td>'.$aux.'</td>
				  <td>'.$E_cargo.'</td>
				  <td>'.$E_empresa.'</td>
				  <td>'.$E_year_inicio.'</td>
				  <td>'.$E_year_fin.'</td>
				  <td>'.$E_descripcion.'</td>
				</tr>';
		}
	}
	else
	{
		$html_tabla_principal.='<tr><td colspan="6">Sin Registro de Empleos Previos</td></tr>';
	}
	$sql_E->free();
   $html_tabla_principal.='</tbody></table>';
	//-----------------------------------------------------------------------------------------------------------///
	
	$objResponse->Assign($div,"innerHTML",$html_tabla_principal);	
	$conexion_mysqli->close();
	return $objResponse;
}
$xajax->processRequest();
?>