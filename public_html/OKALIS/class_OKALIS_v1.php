<?php
//-------------------------------------------------------//
//33525da1e53d23189c9291d0e74b076b
//clase de seguridad
//permite acceso individual de usuarios a archivos
//todah HaShem,
//armado por Elias Fernandez
//el 13/10/2014
//***************
//01/04/2020 
//agrego funcion para manejar rol de alumno.
//-----------------------------------------------------///
class OKALIS
{ 
    public $clave_del_archivo; 
	public $DEBUG=false;
	private $SISTEMA_ACTUAL="7fc7951c34b41d0423d1714ff3a51972";
	public $ruta_conexion;
	private $id_rol=0;
	private $idRolAlumno=9; //rol de alumno por defecto
	private $wwwroot="http://intranet.cftmassachusetts.cl";
	//public $id_usuario;
    //BUSCO RELACION USUARIO ARCHIVO
    private function busco_usuario_con_acceso() 
	{ 
		$ARRAY_USUARIO=array();
		$i=0;
       $ruta_conexion=$this->ruta_conexion."conexion_v2.php";
		require($ruta_conexion);
	   		$clave_archivo=mysqli_real_escape_string($conexion_mysqli, $this->clave_del_archivo);
	   		$cons="SELECT okalis_relacion_usuario_archivo.id_usuario FROM okalis_relacion_usuario_archivo INNER JOIN okalis_archivos ON okalis_relacion_usuario_archivo.id_archivo = okalis_archivos.id_archivo WHERE okalis_archivos.clave_archivo='$clave_archivo'";
			$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
			$num_registros=$sqli->num_rows;
			if(empty($num_registros)){$num_registros=0;}
			if($this->DEBUG){ echo"<strong>busco_usuario_con_acceso() </strong><br>\n--->$cons<br>\n---->N. $num_registros<br>\n";}
			while($U=$sqli->fetch_assoc())
			{
				$U_id=$U["id_usuario"];
				$ARRAY_USUARIO[$i]=$U_id;
				$i++;
			}
			$sqli->free();
	   $conexion_mysqli->close();
	   if($this->DEBUG){ var_dump($ARRAY_USUARIO);}
	   return($ARRAY_USUARIO);
    } 
	private function busco_roles_con_acceso() 
	{ 
		$ARRAY_ROL=array();
		$i=0;
       $ruta_conexion=$this->ruta_conexion."conexion_v2.php";
		require($ruta_conexion);
	   		$clave_archivo=mysqli_real_escape_string($conexion_mysqli, $this->clave_del_archivo);
	   		$cons="SELECT okalis_relacion_rol_archivo.id_rol FROM okalis_relacion_rol_archivo INNER JOIN okalis_archivos ON okalis_relacion_rol_archivo.id_archivo = okalis_archivos.id_archivo WHERE okalis_archivos.clave_archivo='$clave_archivo'";
			$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
			$num_registros=$sqli->num_rows;
			if(empty($num_registros)){$num_registros=0;}
			if($this->DEBUG){ echo"<strong>busco_rol_con_acceso() </strong><br>\n--->$cons<br>\n---->N. $num_registros<br>\n";}
			while($R=$sqli->fetch_assoc())
			{
				$R_id=$R["id_rol"];
				$ARRAY_ROL[$i]=$R_id;
				$i++;
			}
			$sqli->free();
	   $conexion_mysqli->close();
	   if($this->DEBUG){ var_dump($ARRAY_ROL);}
	   return($ARRAY_ROL);
    } 
    // constructor ejecuta automaticamente la existencia de la clave del archivo
    function __construct()
	{
		if(!isset($_SESSION)){ session_start();}
		$hostActual=$_SERVER['HTTP_HOST'];
    	if($this->DEBUG){echo"host actual: $hostActual<br> constructor<br>\n";}
		$primera_linea_seguridad=false;
		//---------------------------------------------------------//
		if(isset($_SESSION["USUARIO"]))
		{
			if($_SESSION["USUARIO"]["autentificado"])
			{
				$nombre_session_autentificacion=$_SESSION["USUARIO"]["session_autorizacion"];
				$valor_session_autorizacion=$_SESSION["SISTEMA"][$nombre_session_autentificacion];
				
				if($valor_session_autorizacion===$this->SISTEMA_ACTUAL){$primera_linea_seguridad=true;}
				else{ @session_destroy();}
			}
			else{ @session_destroy();}
			
		}
		else{ @session_destroy();}
		//------------------------------------------------------//
		if($primera_linea_seguridad){ if($this->DEBUG){ echo"Primera linea seguridad OK<br>\n";}}
		else
		{ 
			if($this->DEBUG){ echo"Primera linea seguridad Error<br>\n";}
			$this->redirijir_a("error");
		}
    }
	
	
	private function busca_privilegio_usuario(){
		if($this->DEBUG){ echo"Busco id_rol de usuario actual<br>\n";}
		if(isset($_SESSION["USUARIO"]["id_rol"])){
			if($this->DEBUG){ echo"Obteniendo el rol por session<br>\n";}
			$Aux_id_rol=$_SESSION["USUARIO"]["id_rol"];
		}
		else
		{
			if($this->DEBUG){ echo"Obteniendo el rol por consulta nivel<br>\n";}
			$ruta_conexion=$this->ruta_conexion."conexion_v2.php";
			require($ruta_conexion);
			$id_usuario=$_SESSION["USUARIO"]["id"];
			$tipoUsuario=$_SESSION["USUARIO"]["tipo"];
			
			if($this->DEBUG){ echo"Tipo de USUARIO: $tipoUsuario<br>\n";}
			switch($tipoUsuario){
				case"funcionario":
					$consP="SELECT nivel FROM personal WHERE id='$id_usuario' LIMIT 1";
					$sqliP=$conexion_mysqli->query($consP);
						$DP=$sqliP->fetch_assoc();
						$Aux_id_rol=$DP["nivel"];
					$sqliP->free();	
					$conexion_mysqli->close();
					break;
				case"alumno":
					//rol de Alumno por defecto
					$Aux_id_rol=$this->idRolAlumno;
					break;
				default:
					$Aux_id_rol=0;	
			}
		}
		$this->id_rol=$Aux_id_rol;
		if($this->DEBUG){ echo"id_rol: $Aux_id_rol<br>\n";}
	}
	
	public function setDisplayErrors($desplegarErrores){
		if($desplegarErrores){
			error_reporting(E_ALL);
			ini_set("display_errors", 1);
		}
	}
	//funcion busca si usuario tiene acceso
 	public function PERMITIR_ACCESO_USUARIO($id_usuario=0)
	{
		$this->busca_privilegio_usuario();
		if($this->DEBUG){ echo"<strong>PERMITIR_ACCESO_USUARIO</strong><br>\n";}
		$permitir_acceso=false;
		
		if($id_usuario==0){ $id_usuario=$_SESSION["USUARIO"]["id"]; if($this->DEBUG){ echo"id usuario no enviado utilizar el de sesion<br>\n";}}
		
		if(!$this->existe_clave_archivo())
		{ $this->redirijir_a("agrega_clave");}
		else
		{ 
			if($this->DEBUG){echo"Clave de Archivo ya generada continuar<br>\n";}
	
			$array_relacion_usuario_archivo=$this->busco_usuario_con_acceso();
			
			
			if((is_numeric($id_usuario))and(is_numeric($this->id_rol))){$realizar_busqueda=true; if($this->DEBUG){ echo"id usuario correcto<br>\n";}}
			else{$realizar_busqueda=false; if($this->DEBUG){ echo"id usuario incorrecto [".$id_usuario."]<br>\n";}}
			
			
			$permitir_acceso=false;
			if($realizar_busqueda)
			{
				if($this->DEBUG){echo"Realizar Busqueda por usuario y rol<br>\n";}
				if(in_array($id_usuario, $array_relacion_usuario_archivo)){ 
					$permitir_acceso=true; if($this->DEBUG){echo"Conceder Acceso a Usuario<br>\n";}}
				else{ 
					if($this->DEBUG){echo"Usuario SIN acceso, comprobar acceso a su ROL<br>\n";}
					$array_relacion_rol_archivo=$this->busco_roles_con_acceso();
					if(in_array($this->id_rol, $array_relacion_rol_archivo)){ 
						$permitir_acceso=true; if($this->DEBUG){echo"Conceder Acceso a Rol<br>\n";}
					}else{ if($this->DEBUG){echo"SIN Acceso a Rol<br>\n";} $this->redirijir_a("sin_privilegios"); }
					
				}//sino in array id_usuario
			}//fin si realizar busqueda
			else{$this->redirijir_a("error");}
		}
	}
	//redirije a las paginas
	private function redirijir_a($tipo)
	{
		//se debe agregar las url absoluta de los archivos
		//$host="http://localhost/CFTMASSA/www/OKALIS/";
		
		if(isset($_SERVER['HTTPS'])){$host=$this->wwwroot;}
		else{$host=$this->wwwroot;}
		
		
		if($this->DEBUG){ echo"<strong>redirijir_a</strong><br>\n";}
		switch($tipo)
		{
			case"sessionYaIniciada":
				$url="/OKALIS/msj_error/error.php?s=iniciada";
			break;
			case"error":
				$url="/OKALIS/msj_error/error.php?s=error";
				break;
			case"sin_privilegios":
				$url="/OKALIS/msj_error/error.php?s=privilegios";
				break;
			case"agrega_clave":
				$url="/OKALIS/gestion_acceso_archivos/registra_clave_archivo/registra_clave_archivo_1.php?clave_archivo=".base64_encode($this->clave_del_archivo);
				break;	
			default:
				$url="/OKALIS/msj_error/error.php";
				
		}
		
		$url=$host.$url;
		if($this->DEBUG){ echo"Url: $url<br>\n";}
		else{ header("location: $url");}
		die();
	}
	// verificar si hay clave del archivo registrada
	private function existe_clave_archivo()
	{
		if($this->DEBUG){ echo"<strong>existe_clave_archivo</strong><br>\n";}
		$ruta_conexion=$this->ruta_conexion."conexion_v2.php";
		require($ruta_conexion);
			$clave_archivo=mysqli_real_escape_string($conexion_mysqli, $this->clave_del_archivo);
			//busco relacion es usuario-archivo
			if($this->DEBUG){ echo"busco relacion entre usuario/archivo<br>\n";}
			$cons="SELECT COUNT(okalis_relacion_usuario_archivo.id_archivo) FROM okalis_relacion_usuario_archivo INNER JOIN okalis_archivos ON okalis_relacion_usuario_archivo.id_archivo = okalis_archivos.id_archivo WHERE okalis_archivos.clave_archivo='$clave_archivo'";
			$sqli=$conexion_mysqli->query($cons)or die("CLASS existe_clave_archivo :".$conexion_mysqli->error);
				$CA=$sqli->fetch_row();
				$num_coincidencias=$CA[0];
				if(empty($num_coincidencias)){ $num_coincidencias=0;}
			$sqli->free();	
			if($this->DEBUG){ echo"numero coincidencias(usuario/archivo): $num_coincidencias<br>\n";}
			//busco relacion es rol-archivo
			if($num_coincidencias==0){
				if($this->DEBUG){ echo"busco relacion entre rol/archivo<br>\n";}
				$cons="SELECT COUNT(okalis_relacion_rol_archivo.id_archivo) FROM okalis_relacion_rol_archivo INNER JOIN okalis_archivos ON okalis_relacion_rol_archivo.id_archivo = okalis_archivos.id_archivo WHERE okalis_archivos.clave_archivo='$clave_archivo'";
			$sqli=$conexion_mysqli->query($cons)or die("CLASS existe_clave_archivo :".$conexion_mysqli->error);
				$CA=$sqli->fetch_row();
				$num_coincidencias=$CA[0];
				if(empty($num_coincidencias)){ $num_coincidencias=0;}
				if($this->DEBUG){ echo"numero coincidencias(rol/archivo): $num_coincidencias<br>\n";}
			$sqli->free();	
				
			}
			
			
		$conexion_mysqli->close();
		
		if($num_coincidencias>0){$existe_clave=true; if($this->DEBUG){ echo"Existe clave archivo---->Si<br>\n";}}
		else{ $existe_clave=false; if($this->DEBUG){ echo"Existe clave archivo---->No<br>\n";}}
		if($this->DEBUG){ echo"---->N veces se encuentra clave archivo: $num_coincidencias<br>\n";}
		return($existe_clave);
	}
	//impide el doble log de alumno para que cada alumno pueda iniciar solo una session simultanea
	//30/10/2018
	//eliasfernandezo@gmail.com
	public function anti2LoggAlumno()
	{
		//datos para conexion en bbdd
		if($this->DEBUG){ echo"<strong>anti2LoggAlumno</strong><br>\n";}
		$ruta_conexion=$this->ruta_conexion."conexion_v2.php";
		require($ruta_conexion);
		//inicializacion de variables
		$id_usuario_000=$_SESSION["USUARIO"]["id"];
		$id_session_000=$_SESSION["USUARIO"]["id_session"];
		$privilegio_000=$_SESSION["USUARIO"]["privilegio"];
		$tipoUsuario_000=$_SESSION["USUARIO"]["tipo"];
		
		if($this->DEBUG){ echo"Tipo usuario: $tipoUsuario_000<br>\n";}
		if($tipoUsuario_000=="alumno")
		{
			
			//generar consulta, ESTADO ACTUAL DE CONEXION de alumno, verifico id de sesion con el de BBDD
			$cons_session="SELECT estado_conexion, session_id FROM alumno WHERE id='$id_usuario_000' LIMIT 1";
			if(DEBUG){echo"$cons_session<br>";}
			$sql_session=$conexion_mysqli->query($cons_session)or die($conexion_mysqli->error);
			$DA_000=$sql_session->fetch_assoc();
			$BBDD_id_session=$DA_000["session_id"];
			$BBDD_estado_conexion=strtoupper($DA_000["estado_conexion"]);
			if($this->DEBUG){echo"estado conexion BBDD:$BBDD_estado_conexion<br>ID session BBDD: $BBDD_id_session<br>id sesion actual: $id_session_000<br>";}
			$sql_session->free();
			
			///compara lo de session con lo de la BBDD
				if($BBDD_estado_conexion=="ON")
				{
					if($id_session_000!=$BBDD_id_session)
					{
						if($this->DEBUG){echo"ID session INCORRECTOS<br>";}
						else{ $this->redirijir_a("sessionYaIniciada");}
					}
					else
					{if($this->DEBUG){echo"ID SESSION correctos<br>";}}
				}
				else
				{
					if($this->DEBUG){echo"Estado Conexion: OFF cerrado<br>";}
					else{ $this->redirijir_a("sessionYaIniciada");}
				}
		}else{if($this->DEBUG){ echo"<strong>No Verificar</strong><br>\n";}}
		//cierro conexion	
		$conexion_mysqli->close();
	}
} 
?>