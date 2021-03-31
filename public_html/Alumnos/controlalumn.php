<?php
//----------------------------------------//
define("DEBUG", false);
$acceso=false;
$desde_url_permitida=false;
$acceso_solo_con_matricula_vigente=false;//permite acceso solo alumno con contrato vigente
//---------------------------------------//
$verificar_url_origen=true;//filtra o no de la url de origen
$id_rolAlumno=9;//rol de alumno 01/04/2020, para usar OKALIS para todos
//////////////////
sleep(2.1);
//origen
$array_url_permitidas=array("http://186.10.233.98/~cftmassa/Alumnos/",
							"http://186.10.233.98/~cftmassa/Alumnos/index.php",
							"http://www.cftmass.cl/version_2/acceso.php",
							"http://186.10.233.98/~cftmassa/Administrador/index.php",
							"http://186.10.233.98/~cftmassa/Administrador/",
							"https://186.10.233.98/~cftmassa/Administrador/index.php",
							"https://186.10.233.98/~cftmassa/Administrador/",
							"http://cftmassachusetts.cl/~cftmassa/Administrador/index.php",
							"http://cftmassachusetts.cl/~cftmassa/Administrador/",
							"http://www.cftmassachusetts.cl/~cftmassa/Administrador/",
							"http://cftmassachusetts.cl/Administrador/index.php",
							"http://cftmassachusetts.cl/Administrador/",
							"http://www.cftmassachusetts.cl/Administrador/",
							"https://cftmassachusetts.cl/~cftmassa/Administrador/index.php",
							"https://cftmassachusetts.cl/~cftmassa/Administrador/",
							"https://www.cftmassachusetts.cl/~cftmassa/Administrador/",
							"https://cftmassachusetts.cl/Administrador/index.php",
							"http://intranet.cftmassachusetts.cl/Administrador/index.php",
							"http://www.cftmass.cl/",
							"https://intranet.cftmassachusetts.cl/Administrador/index.php");


$url_origen = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'http://intranet.cftmassachusetts.cl/Administrador/index.php';

if(DEBUG){ echo"URL origen: $url_origen<br>"; }
$array_url=explode("?",$url_origen);
$url_origen_1=$array_url[0];
if(DEBUG){ echo"URL BASE: $url_origen_1<br>";}
//////////////////


if(isset($_POST["validador"]))
{ $validador=$_POST["validador"];}
else{ $validador="xxxx";}

$comparador=md5("alumnoX".date("d-m-Y"));
if(DEBUG){ echo"validador: $validador comparador:$comparador<br>";}
if($validador==$comparador)
{ $acceso=true; if(DEBUG){echo"Acceso OK<br>";} }else{if(DEBUG){echo"Acceso ERROR<br>";}}

if($verificar_url_origen)
{
	foreach($array_url_permitidas as $nurl=>$vurl)
	{
		if(DEBUG){echo"$nurl -> $vurl";}
		if($url_origen_1==$vurl)
		{
			$desde_url_permitida=true;
			if(DEBUG){ echo"---->URL: <strong>PERMITIDA</strong><br>";}
			break;
			
		}
		else{	if(DEBUG){ echo"---->URL: <strong>NO PERMITIDA</strong><br>";}}
		
	}
}
else
{
	$desde_url_permitida=true;
	if(DEBUG){ echo"---->URL: <strong>PERMITIDA</strong> (sin bloque de url)<br>";}
}

$array_url=explode("?",$url_origen);
$url_origen_1=$array_url[0];
if(DEBUG){ echo"URL BASE: $url_origen_1<br>";}


echo "acceso".$acceso." url ok".$desde_url_permitida."<br>";

if(($acceso)and($_POST)and($desde_url_permitida))
{
	//------------------------------------//
	 require('../../funciones/conexion_v2.php');
	 require('../../funciones/funciones_sistema.php');
	 require("../../funciones/class_ALUMNO.php");
	 
	//----------------------------------------------------//
	$tiempo_espera=300;
	$fecha_hora_actual=date("Y-m-d H:i:s");
	$fecha_hora_actual_time=time();
	//busco alumnos con estado conexion ON
	$cons_ec="SELECT id, estado_conexion, ultima_conexion FROM alumno WHERE estado_conexion='ON'";
	$sql_ec=$conexion_mysqli->query($cons_ec)or die($conexion_mysqli->error);
	$num_conectados=$sql_ec->num_rows;
	if(DEBUG){ echo"<br>$cons_ec<br>N. Conectados: $num_conectados<br>";}
	if($num_conectados>0)
	{
		while($UC=$sql_ec->fetch_assoc())
		{
			$U_estado_conexion=$UC["estado_conexion"];
			$U_id_alumno=$UC["id"];
			$U_ultima_conexion=$UC["ultima_conexion"];
			
			$aux_ultima_conexion_time=strtotime($U_ultima_conexion);
			$tiempo_expira_ultima_conexion=($aux_ultima_conexion_time+$tiempo_espera);
			if(DEBUG){ echo"<br><strong>$U_estado_conexion</strong> $U_id_alumno -> $U_ultima_conexion [$fecha_hora_actual_time]-[$tiempo_expira_ultima_conexion]<br>";}
			
			if($fecha_hora_actual_time>$tiempo_expira_ultima_conexion)
			{
				if(DEBUG){ echo"FUERA DEL TIEMPO<br>";}
				$cons_desconexion="UPDATE alumno SET estado_conexion='OFF' WHERE id='$U_id_alumno' LIMIT 1";
				if(DEBUG){ echo"DESCONEXION FORZADA: $cons_desconexion<br>";}
				else{ $conexion_mysqli->query($cons_desconexion)or die("desconexion forzada".$conexion_mysqli->error);}
			}
			else
			{if(DEBUG){ echo"DENTRO DEL TIEMPO<br>";}}
		}
	}
	else
	{
		if(DEBUG){ echo"No hay Alumnos Conectados...<br>";}
	}
	$sql_ec->free();
	//----------------------------------------------------//
	
	
	///////////////////////////
	$year_actual=date("Y");
	$mes_actual=date("m");
	if($mes_actual>8)//despues de  agosto considero 2 semestre
	{ $semestre_actual=2;}
	else{ $semestre_actual=1;}
	////////////////////////////
	$frut=strtoupper($_POST["frut"]);
	$frut=strip_tags($frut);
	$frut=str_replace(".","",$frut);
	
	$fclave=$_POST["fclave"];
	$fclave=str_replace(".","",$fclave);
	
	$alumno_encontrado=false;
if(DEBUG){echo"<br>"; var_dump($_POST); echo"<br>";}

    $frut=mysqli_real_escape_string($conexion_mysqli, $frut);
   	$fclave=mysqli_real_escape_string($conexion_mysqli,$fclave);
	
	if((!empty($frut))and(!empty($fclave)))
	{
		
   		$consulta="SELECT id, rut, clave, estado_conexion FROM alumno ORDER by id desc";
		if(DEBUG){ echo"--->$consulta<br>";}
   		$resultado=$conexion_mysqli->query($consulta)or die($conexion_mysqli->error);

   		 while($col=$resultado->fetch_assoc())   
    	 {
		 	$id_alumno=$col["id"];
       		$rutX = strtoupper($col["rut"]);
       		$passX = $col["clave"];
			$estado_conexionX=strtoupper($col["estado_conexion"]);
			if(empty($estado_conexionX)){ $estado_conexionX="OFF";}
			if(DEBUG){
				//echo"$rutX - $passX - $sedeX - $situacionX || $frut - $fclave<br>";
			}
			
			if((($frut==$rutX)and($fclave==$passX))and($frut!=""))
			{
				$alumno_encontrado=true;
				if(DEBUG){ echo"=====>Alumno encontrado en la BBDD<br>";}
				$ALUMNO=new ALUMNO($id_alumno);
				$ALUMNO->SetDebug(DEBUG);	
				$situacionX=$ALUMNO->getUltimaSituacionMat();
				$id_carreraX=$ALUMNO->getUltimaIdCarreraMat();
				$yearIngresoCarrera=$ALUMNO->getUltimoYearIngresoMat();
				$sedeX=$ALUMNO->getSedeActual();
				
				break;
			}
				
	 	}
		$resultado->free();
		//---------------------------------------------------------------------------------------------------//
		if($alumno_encontrado)
		{
			switch($situacionX)
			{
				case"V":
					$cuenta_activa=true;
					///////////////////////////////////
					///doy solo acceso a los vigentes
					//$actualmente_matriculado=VERIFICAR_MATRICULA($id_alumno, $id_carreraX, true, true,0,true,0,true);
					$actualmente_matriculado=VERIFICAR_MATRICULA($id_alumno, $id_carreraX, $yearIngresoCarrera,true,false,$semestre_actual, false, $year_actual,true);
					/////////////////////////////////////
					break;
				case"EG":
					$cuenta_activa=true;
					///////////////////////////////////
					///doy acceso a egresados
					//$actualmente_matriculado=VERIFICAR_MATRICULA($id_alumno, $id_carreraX, true, true,0,true,0,true);
					$actualmente_matriculado=VERIFICAR_MATRICULA($id_alumno, $id_carreraX, $yearIngresoCarrera, true,false,$semestre_actual, false, $year_actual,true);
					/////////////////////////////////////
					break;	
				default:
					if($acceso_solo_con_matricula_vigente){
						$cuenta_activa=false;	
						$actualmente_matriculado=false;
					}else{
						$cuenta_activa=true;	
						$actualmente_matriculado=true;
					}
			}
		}
		else
		{if(DEBUG){ echo"Alumno NO encontrado<br>";}}
	}
	else
	{ 
		if(DEBUG){ echo"<br>Sin Datos de Entrada<br>";}
		$cuenta_activa=false;
		$actualmente_matriculado=false;
	}
		
	/////////////////
	if($acceso_solo_con_matricula_vigente)
	{
		if($actualmente_matriculado)
		{ $permite_acceso=true;}
		else
		{ $permite_acceso=false;}
	}
	else
	{ $permite_acceso=true;}
	/////////////////////
		
		
	  if(DEBUG){ echo"<br> ENCONTRADO: $alumno_encontrado<br> CUENTA $cuenta_activa <br>Estado Conexion: $estado_conexionX<br>";}
	  	if($alumno_encontrado)
	  	{
			if(($cuenta_activa)and($permite_acceso))
			{
				if($estado_conexionX=="OFF")
				{
					 session_start();
					///////////////Nuevas Sessiones////////////////
					$codigo_aleatorio=md5("cftmassa".microtime()); 
					$_SESSION["USUARIO"]["rut"]=$frut;
					$_SESSION["USUARIO"]["autentificado"]="SI";
					$_SESSION["USUARIO"]["clave"]=$fclave;
					
					$_SESSION["USUARIO"]["yearIngresoCarrera"]=$yearIngresoCarrera;
					$_SESSION["USUARIO"]["situacion"]=$situacionX;
					$_SESSION["USUARIO"]["id_carrera"]=$id_carreraX;
					$_SESSION["USUARIO"]["sede"]=$sedeX;
					$_SESSION["USUARIO"]["id"]=$id_alumno;
					$_SESSION["USUARIO"]["id_rol"]=$id_rolAlumno;//01/04/2020
					$_SESSION["USUARIO"]["privilegio"]="ALUMNO";
					$_SESSION["USUARIO"]["tipo"]="alumno";//de la tabla que los comparo
					$_SESSION["USUARIO"]["session_autorizacion"]=$codigo_aleatorio;
					$_SESSION["SISTEMA"][$codigo_aleatorio]=md5("CFTMASSA");////para seguridad
					$id_session=session_id();
					$_SESSION["USUARIO"]["id_session"]=$id_session;
					
					$cons_ciS="UPDATE alumno SET session_id='$id_session' WHERE id='$id_alumno' LIMIT 1";
					if(DEBUG){ echo"actualiza_ID SESSION: $cons_ciS<br>";}
					else{ $conexion_mysqli->query($cons_ciS);}
					//-------------------------------------------//
	
					 include("../../funciones/VX.php");
					 $evento="inicia sesion Alumno";
					 REGISTRA_EVENTO($evento);
					 ///////////////////////
					 //cambio estado_conexion USER-----------
					 CAMBIA_ESTADO_CONEXION_ALUMNO($id_alumno, "ON");
					/////----------------------------------
					
					
					
					//-----------------------------------------------------------------------//
					if(DEBUG){ echo"<br><strong>Verifico si alumno tiene que actualizar datos de contacto</strong><br>";}
					$cons_REA="SELECT MAX(YEAR(fecha_generacion)) FROM alumno_registros WHERE id_alumno='$id_alumno' AND descripcion='Alumno Actualiza datos contacto'";
				
					$sql_REA=$conexion_mysqli->query($cons_REA)or die(mysql_error());
					$REA=$sql_REA->fetch_row();
					$ultimo_year_actualizo_datos=$REA[0];
					$sql_REA->free();
					if(empty($ultimo_year_actualizo_datos)){ $ultimo_year_actualizo_datos=0;}
					if(DEBUG){ echo"--->$cons_REA<br>ultimo year actualizo datos: $ultimo_year_actualizo_datos<br>";}
					
					if($ultimo_year_actualizo_datos<$year_actual)
					{
						if(DEBUG){ echo"Debe actualizar datos<br>dirigir a Actualizacion de datos de contacto<br>";}
						$url="solicitar_actualizacion_datos/solicitar_1.php";
					}
					else
					{
						
						if(DEBUG){ echo"<br><strong>Verifico si alumno tiene que actualizar condicion de FUAS</strong><br>";}
						$cons_REA="SELECT MAX(YEAR(fecha_generacion)) FROM alumno_registros WHERE id_alumno='$id_alumno' AND descripcion='Alumno Actualiza FUAS'";
					
						$sql_REA=$conexion_mysqli->query($cons_REA);
						$REA=$sql_REA->fetch_row();
						$ultimo_year_actualizo_FUAS=$REA[0];
						$sql_REA->free();
						if(empty($ultimo_year_actualizo_FUAS)){ $ultimo_year_actualizo_FUAS=0;}
						
						if($ultimo_year_actualizo_FUAS<$year_actual)
						{
							if(DEBUG){ echo"Debe actualizar FUAS<br>dirigir a Actualizacion de datos de FUAS<br>";}
							$url="solicitar_actualizacion_datos/solicitar_actualizacion_FUAS.php";
						}
						else
						{
							if(DEBUG){ echo"Todo OK<br>dirigir a Menu<br>";}
							$url="alumno_menu.php";
						}
						
						
					}
					//-------------------------------------------------------------------///
					
					
					@$conexion_mysqli->close;
					if(DEBUG){ echo"URL: $url<br>";}
					else{ header("location: $url");}
					
					//------------------------------------------------------------------------//
					
				}
				else
				{
					$url_destino=$url_origen_1."?error=A3";
					if(DEBUG){ echo"Session Ya INICIADA...<br>$url_destino<br>";}
					else{header("Location: $url_destino");}
				}
			}
			else
			{
				$url_destino=$url_origen_1."?error=A1";
				if(DEBUG){ echo"CUENTA INACTIVA<br>$url_destino<br>";}
				else{header("Location: $url_destino");}
			}	
	  	}
	  	else
	  	{
			$url_destino=$url_origen_1."?error=A2";
	    	if(DEBUG){ echo"Alumno No encontrado<br>$url_destino<br>";}
			else{header("Location: $url_destino");}
	  	}
	}

else
{
	if(DEBUG){ echo"<br>Sin Acceso<br>";}
	else{header("location: $url_origen_1");}
}
////////////////////////////////////
?>