<?php
/////////////////////--/XAJAX/----////////////////
@require_once("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("buscador_sever.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCA_X_RUT");
$xajax->register(XAJAX_FUNCTION,"BUSCA_X_APELLIDO");
$xajax->register(XAJAX_FUNCTION,"BUSCA_X_ID");
$xajax->register(XAJAX_FUNCTION,"BUSCA_X_ID_SEDE");
define("DEBUG",false);
///////////////----------------////////////////////////////
function BUSCA_X_RUT($rut, $sede)
{
	$mostrar_respuesta=false;
	$div="div_resultadoX";
	require("../../../funciones/conexion_v2.php");
	$objResponse = new xajaxResponse();
	$resultado='<table width="100%" border="1" align="right">
  <THEAD>
    <tr>
      <tH>Resultado de Busqueda</tH>
      </tr>
      </THEAD>
      <TBODY>';
	if(!empty($rut))
	{
		$mostrar_respuesta=true;
	$cons1="SELECT id, nombre, apellido, apellido_P, apellido_M, carrera FROM alumno WHERE rut='$rut' AND sede='$sede' ORDER by id desc";
	$sql1=mysql_query($cons1)or die(mysql_error());
	$num_coincide_rut=mysql_num_rows($sql1);
	if(DEBUG){$resultado.=$cons1;}
	if($num_coincide_rut>0)
	{
		while($A=mysql_fetch_assoc($sql1))
		{
			$id_alumno=$A["id"];
			$nombre=utf8_encode($A["nombre"]);
			$apellido_old=utf8_encode($A["apellido"]);
			$apellido_new=utf8_encode($A["apellido_P"])." ".utf8_decode($A["apellido_M"]);
			$carrera=utf8_encode($A["carrera"]);
			
			if($apellido_new==" ")
			{
				$apellido_label=$apellido_old;
			}
			else
			{ $apellido_label=$apellido_new;}
			
			$resultado.='<tr><td><a href="#" onclick="ASIGNAR(\''.$id_alumno.'\');"><b>'.$rut.'</b> -'.$nombre.' '.$apellido_label.' - '.$carrera.'</a></td></tr>';
		}
		$resultado.="<tr><td>($num_coincide_rut) Encontrados</td></tr>";
	}
	else
	{
		$resultado.="<tr><td>No encontrado :(</td></tr>";
	}
	mysql_free_result($sql1);
	}
	else
	{
		$resultado.="<tr><td>Sin Datos :S</td></tr>";
	}
	mysql_close($conexion);
	$resultado.="</tbody></table>";
	if($mostrar_respuesta)
	{
		$objResponse->Assign($div,"innerHTML","");
		$objResponse->Assign($div,"innerHTML",$resultado);
	}
	///////////////////
	return $objResponse;
}
function BUSCA_X_APELLIDO($apellido_P, $apellido_M, $sede)
{
	//sleep(5);
	$div="div_resultadoX";
	$mostrar_respuesta=false;
	include("../../../funciones/conexion_v2.php");
	$objResponse = new xajaxResponse();
	
	//$apellido_P=utf8_decode($apellido_P);
	//$apellido_M=utf8_decode($apellido_M);
	$operador_logico="AND";
	$consultar=true;
	
	if((empty($apellido_P))and(empty($apellido_M)))
	{$consultar=false;}
	
	if((empty($apellido_P))and(!empty($apellido_M)))
	{
		$apellido_cons="$apellido_M%";
		$condicion2="$operador_logico apellido_M LIKE('$apellido_M%')";
	}
	if((!empty($apellido_P))and(empty($apellido_M)))
	{
		$apellido_cons="$apellido_P%";
		$condicion2="$operador_logico apellido_P LIKE('$apellido_P%')";
	}
	if((!empty($apellido_P))and(!empty($apellido_M)))
	{
		$apellido_cons="$apellido_P $apellido_M%";
		$condicion2="$operador_logico (apellido_P LIKE('$apellido_P%') $operador_logico apellido_M LIKE('$apellido_M%'))";
	}
	
	
	//$cons2="SELECT id, rut, nombre, apellido, apellido_P, apellido_M, carrera FROM alumno WHERE sede='$sede' AND apellido LIKE('$apellido_cons') $condicion2 ORDER by apellido_P, apellido_M, apellido";
	
	$cons2="SELECT id, rut, nombre, apellido, apellido_P, apellido_M, carrera FROM alumno WHERE sede='$sede' $condicion2 ORDER by apellido_P, apellido_M";
	
	$resultado='<table width="100%" border="1" align="right">
  <THEAD>
    <tr>
      <tH>Resultado de Busqueda</tH>
      </tr>
      </THEAD>
      <TBODY>';

	if(DEBUG){$resultado.="$cons2<br>";}
	if($consultar)
	{
		$sql2=mysql_query($cons2)or die("apellidos ".mysql_error());
		$num_resultados=mysql_num_rows($sql2);
		if($num_resultados>0)
		{
			$mostrar_respuesta=true;
			while($AX=mysql_fetch_assoc($sql2))
			{
				$id_alumno=$AX["id"];
				$rut=$AX["rut"];
				$nombre=$AX["nombre"];
				$apellido=$AX["apellido"];
				$apellido_P=$AX["apellido_P"];
				$apellido_M=$AX["apellido_M"];
				$carrera=$AX["carrera"];
				
				if((empty($apellido))and(!empty($apellido_P)))
				{
					$apellido_label=$apellido_P." ".$apellido_M;
				}
				else
				{ $apellido_label=$apellido;}
				
				$alumno=ucwords(strtolower($nombre." ".$apellido_label));
				
				$resultado.='<tr><td><a href="#" onclick="ASIGNAR(\''.$id_alumno.'\');"><b>'.$rut.'</b> -'.$alumno.' - <b>'.$carrera.'</b></a></td></tr>';
			}
			$resultado.="<tr><td><strong>($num_resultados)</strong> Encontrados</td></tr>";
		}
		else
		{$resultado.="<tr><td>Sin Resultados :(</td></tr>";}
		mysql_free_result($sql2);
	}
	else
	{$resultado.="<tr><td>Sin Datos :S</td></tr>";}
	$resultado.="</tbody></table>";
	
	if($mostrar_respuesta)
	{ $objResponse->Assign($div,"innerHTML",$resultado);}
	
	mysql_close($conexion);
	return $objResponse;
}
function BUSCA_X_ID($id_alumno)
{
	require("../../../funciones/conexion_v2.php");
	$objResponse = new xajaxResponse();
	$mostrar_respuesta=false;
	if(is_numeric($id_alumno))
	{
		$cons="SELECT rut, nombre, apellido, apellido_P, apellido_M, carrera, ingreso, situacion FROM alumno WHERE id='$id_alumno'";
		$sql2=mysql_query($cons)or die("apellidosXid ".mysql_error());
		$num_reg=mysql_num_rows($sql2);
		if($num_reg>0)
		{$mostrar_respuesta=true;}
		$AX=mysql_fetch_assoc($sql2);
			$rut=$AX["rut"];
			$nombre=$AX["nombre"];
			$apellido=$AX["apellido"];
			$apellido_P=$AX["apellido_P"];
			$apellido_M=$AX["apellido_M"];
			$carrera=$AX["carrera"];
			$ingreso=$AX["ingreso"];
			$situacion=$AX["situacion"];

				$apellido_label=$apellido_P." ".$apellido_M;
			$alumno=$nombre." ".$apellido_label;
			////////////////////////
			if($mostrar_respuesta)
			{
				//asigno a campos de formulario
				$objResponse->Assign("rut","value",$rut);
				$objResponse->Assign("id_alumno","value",$id_alumno);
				$objResponse->Assign("hi_id_alumno","value",$id_alumno);
				
				//muestro datos solo texto
				$objResponse->Assign("div_rut","innerHTML",$rut);
				$objResponse->Assign("div_alumno","innerHTML",$alumno);
				$objResponse->Assign("div_ingreso","innerHTML",$ingreso);
				$objResponse->Assign("div_situacion","innerHTML",$situacion);
				$objResponse->Assign("div_carreras","innerHTML",$carrera);	
			}
			
		mysql_free_result($sql2);	
	}///fin si id_alumno numerico
	mysql_close($conexion);	
	return $objResponse;
}
function BUSCA_X_ID_SEDE($id_alumno, $sede)
{
	require("../../../funciones/conexion_v2.php");
	$mostrar_respuesta=false;
	$objResponse = new xajaxResponse();
	
	if((is_numeric($id_alumno))and(!empty($sede)))
	{
		$mostrar_respuesta=true;
		$cons="SELECT rut, nombre, apellido, apellido_P, apellido_M, carrera, ingreso, situacion FROM alumno WHERE id='$id_alumno' AND sede='$sede'";
		$sql2=mysql_query($cons)or die("apellidosXid ".mysql_error());
		$num_reg=mysql_num_rows($sql2);
		$AX=mysql_fetch_assoc($sql2);
			$rut=$AX["rut"];
			$nombre=$AX["nombre"];
			$apellido=$AX["apellido"];
			$apellido_P=$AX["apellido_P"];
			$apellido_M=$AX["apellido_M"];
			$carrera=$AX["carrera"];
			$ingreso=$AX["ingreso"];
			$situacion=$AX["situacion"];
			
			
			if((empty($apellido))and(!empty($apellido_P)))
			{
				$apellido_label=$apellido_P." ".$apellido_M;
			}
			else
			{ $apellido_label=$apellido;}
			
			$alumno=$nombre." ".$apellido_label;
			////////////////////////
			//asigno a campos de formulario
			$objResponse->Assign("rut","value",$rut);
			$objResponse->Assign("hi_id_alumno","value",$id_alumno);
			
			//muestro datos solo texto
			$objResponse->Assign("div_rut","innerHTML",$rut);
			$objResponse->Assign("div_alumno","innerHTML",$alumno);
			$objResponse->Assign("div_ingreso","innerHTML",$ingreso);
			$objResponse->Assign("div_situacion","innerHTML",$situacion);
			$objResponse->Assign("div_carreras","innerHTML",$carrera);	
		if($num_reg<=0)
		{
			$resultado='<table width="100%" border="1" align="right">
			  <THEAD>
				<tr>
				  <tH>Resultado de Busqueda</tH>
				  </tr>
				  </THEAD>
				  <TBODY>';
			$resultado.="<tr><td>Sin Datos :S</td></tr>";
			$resultado.="</tbody></table>";
			$objResponse->Assign("div_resultadoX","innerHTML",$resultado);
		}
			
		mysql_free_result($sql2);
	}//fin si id_alumno numerico y sede no vacio
	mysql_close($conexion);	
	return $objResponse;
}
////////////////
$xajax->processRequest();
?>