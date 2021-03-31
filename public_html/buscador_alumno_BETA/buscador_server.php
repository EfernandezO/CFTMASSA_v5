<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
/////////////////////--/XAJAX/----////////////////
@require_once("../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("buscador_sever.php");
$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../libreria_publica/xajax/');
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
	require("../../funciones/conexion_v2.php");
	require("../../funciones/class_ALUMNO.php");
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
		
		$rut=str_replace(".","",$rut);
		$rut=str_replace(" ","",$rut);
		
	$cons1="SELECT id, nombre, apellido, apellido_P, apellido_M FROM alumno WHERE rut='$rut' AND sede='$sede' ORDER by id desc";
	$sql1=$conexion_mysqli->query($cons1)or die(mysql_error());
	$num_coincide_rut=$sql1->num_rows;
	if(DEBUG){$resultado.=$cons1;}
	if($num_coincide_rut>0)
	{
		while($A=$sql1->fetch_assoc())
		{
			$id_alumno=$A["id"];
			$ALUMNO=new ALUMNO($id_alumno);
			$ALUMNO->SetDebug(DEBUG);
			
			$nombre=utf8_decode($A["nombre"]);
			$apellido_new=$A["apellido_P"]." ".$A["apellido_M"];
			$apellido_label=utf8_decode($apellido_new);
			
			
			
			$resultado.='<tr><td bgcolor="'.$ALUMNO->getColorSituacion().'"><a href="#" onclick="ASIGNAR(\''.$id_alumno.'\');"><b>'.$rut.'</b> -'.$nombre.' '.$apellido_label.'</a></td></tr>';
		}
		$resultado.="<tr><td>($num_coincide_rut) Encontrados</td></tr>";
	}
		else{$resultado.="<tr><td>No encontrado :(</td></tr>";}
		$sql1->free();
	}
	else
	{$resultado.="<tr><td>Sin Datos :S</td></tr>";}
	
	$conexion_mysqli->close();
	
	$resultado.="</tbody></table>";
	if($mostrar_respuesta)
	{
		$objResponse->Assign("rut","value",$rut);
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
	require("../../funciones/conexion_v2.php");
	require("../../funciones/class_ALUMNO.php");
	$objResponse = new xajaxResponse();
	
	//$apellido_P=utf8_decode($apellido_P);
	//$apellido_M=utf8_decode($apellido_M);
	$operador_logico="AND";
	$consultar=true;
	$condicion2="";
	
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
	
	$cons2="SELECT id, rut, nombre, apellido, apellido_P, apellido_M FROM alumno WHERE sede='$sede' $condicion2 ORDER by apellido_P, apellido_M";
	
	$resultado='<table width="100%" border="1" align="right">
	  <thead>
		<tr>
		  <th>Resultado de Busqueda</th>
		  </tr>
		  </thead>
		  <tbody>';
	
	$mostrar_respuesta=true;
	if(DEBUG){$resultado.="$cons2<br>";}
	if($consultar)
	{
		$sql2=$conexion_mysqli->query($cons2)or die("apellidos ".$conexion_misqli->error);
		$num_resultados=$sql2->num_rows;
		if($num_resultados>0)
		{
			
			while($AX=$sql2->fetch_assoc())
			{
				$id_alumno=$AX["id"];
				$ALUMNO=new ALUMNO($id_alumno);
				$ALUMNO->SetDebug(DEBUG);
				$rut=$AX["rut"];
				
				$nombre=$AX["nombre"];
				
				$apellido_P=$AX["apellido_P"];
				$apellido_M=$AX["apellido_M"];
				
				$apellido_label=$apellido_P." ".$apellido_M;
				
				
				$alumno=utf8_decode(ucwords(strtolower($nombre." ".$apellido_label)));
				
				$resultado.='<tr><td bgcolor="'.$ALUMNO->getColorSituacion().'"><a href="#" onclick="ASIGNAR(\''.$id_alumno.'\');"><b>'.$rut.'</b> -'.$alumno.'</a></td></tr>';
			}
			$resultado.="<tr><td><strong>($num_resultados)</strong> Encontrados</td></tr>";
		}
		else
		{$resultado.="<tr><td>Sin Resultados :(</td></tr>";}
		$sql2->free();
	}
	else
	{$resultado.="<tr><td>Sin Datos :S</td></tr>";}
	$resultado.="</tbody></table>";
	
	if($mostrar_respuesta)
	{ $objResponse->Assign($div,"innerHTML",$resultado);}
	
	$conexion_mysqli->close();
	return $objResponse;
}
function BUSCA_X_ID($id_alumno)
{
	require("../../funciones/conexion_v2.php");
	require("../../funciones/funciones_sistema.php");
	$objResponse = new xajaxResponse();
	
	
	$mostrar_respuesta=false;
	if(is_numeric($id_alumno))
	{
		$cons="SELECT rut, nombre, apellido, apellido_P, apellido_M FROM alumno WHERE id='$id_alumno'";
		$sql2=$conexion_mysqli->query($cons)or die("apellidosXid ".$conexion_mysqli->error);
		$num_reg=$sql2->num_rows;
		if($num_reg>0)
		{$mostrar_respuesta=true;}
		$AX=$sql2->fetch_assoc();
			$rut=$AX["rut"];
			$nombre=$AX["nombre"];
			
			$apellido_P=$AX["apellido_P"];
			$apellido_M=$AX["apellido_M"];

			$apellido_label=$apellido_P." ".$apellido_M;
			$alumno=utf8_decode($nombre." ".$apellido_label);
			////////////////////////
			if($mostrar_respuesta)
			{
				require("../../funciones/class_ALUMNO.php");
				$ALUMNO=new ALUMNO($id_alumno);
				
				$infoCarreras="";
				foreach($ALUMNO->getMatriculasAlumno() as $n =>$auxArray){
					$auxIdCarrera=$auxArray["id_carrera"];
					$auxYearIngresoCarrera=$auxArray["yearIngresoCarrera"];
					$auxSituacion=$auxArray["situacion"];
					
					$infoCarreras.=utf8_decode(NOMBRE_CARRERA($auxIdCarrera))." ".$auxYearIngresoCarrera." ".$auxSituacion."<br>";
				}
				
				//asigno a campos de formulario
				$objResponse->Assign("rut","value",$rut);
				$objResponse->Assign("id_alumno","value",$id_alumno);
				$objResponse->Assign("hi_id_alumno","value",$id_alumno);
				
				//muestro datos solo texto
				$objResponse->Assign("div_rut","innerHTML",$rut);
				$objResponse->Assign("div_alumno","innerHTML",$alumno);
				
				$objResponse->Assign("div_carreras","innerHTML",$infoCarreras);
				
			}
			
		$sql2->free();	
	}///fin si id_alumno numerico
		
	$conexion_mysqli->close();
	return $objResponse;
}
function BUSCA_X_ID_SEDE($id_alumno, $sede)
{
	require("../../funciones/conexion_v2.php");
	require("../../funciones/funciones_sistema.php");
	$mostrar_respuesta=false;
	$objResponse = new xajaxResponse();
	
	if((is_numeric($id_alumno))and(!empty($sede)))
	{
		$mostrar_respuesta=true;
		$cons="SELECT rut, nombre, apellido, apellido_P, apellido_M FROM alumno WHERE id='$id_alumno' AND sede='$sede'";
		$sql2=$conexion_mysqli->query($cons)or die("apellidosXid ".$conexion_mysqli->error);
		$num_reg=$sql2->num_rows;
		if($num_reg>0){
			$AX=$sql2->fetch_assoc();
			$rut=$AX["rut"];
			$nombre=$AX["nombre"];
			
			$apellido_P=$AX["apellido_P"];
			$apellido_M=$AX["apellido_M"];
			$apellido_label=$apellido_P." ".$apellido_M;
			
			$alumno=utf8_decode($nombre." ".$apellido_label);
			////////////////////////
			//asigno a campos de formulario
			$objResponse->Assign("rut","value",$rut);
			$objResponse->Assign("hi_id_alumno","value",$id_alumno);
			
			//muestro datos solo texto
			$objResponse->Assign("div_rut","innerHTML",$rut);
			$objResponse->Assign("div_alumno","innerHTML",$alumno);
			
			
			require("../../funciones/class_ALUMNO.php");
			$ALUMNO=new ALUMNO($id_alumno);
			
			$infoCarreras="";
			foreach($ALUMNO->getMatriculasAlumno() as $n =>$auxArray){
				$auxIdCarrera=$auxArray["id_carrera"];
				$auxYearIngresoCarrera=$auxArray["yearIngresoCarrera"];
				$auxSituacion=$auxArray["situacion"];
				
				$infoCarreras.=utf8_decode(NOMBRE_CARRERA($auxIdCarrera))." ".$auxYearIngresoCarrera." ".$auxSituacion."<br>";
			}
			$objResponse->Assign("div_carreras","innerHTML",$infoCarreras);
		}
		else{
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
			
			#reset a los demas campos
			$objResponse->Assign("rut","value","");
			$objResponse->Assign("hi_id_alumno","value","");
			
			//muestro datos solo texto
			$objResponse->Assign("div_rut","innerHTML","");
			$objResponse->Assign("div_alumno","innerHTML","");
			$objResponse->Assign("div_carreras","innerHTML","");
		}
			
		$sql2->free();
	}//fin si id_alumno numerico y sede no vacio
		
	$conexion_mysqli->close();
	return $objResponse;
}
////////////////
$xajax->processRequest();
?>