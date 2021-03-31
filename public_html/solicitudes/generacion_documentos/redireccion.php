<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("SOLICITUDES->verCertificados");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

	if(isset($_GET["id_solicitud"]))
	{$id_solicitud=$_GET["id_solicitud"];}
	else
	{ $id_solicitud=0;}
	
	$urlAnterior=$_SERVER['HTTP_REFERER'];
	if(DEBUG){ echo"URL PROVIENE: $urlAnterior<br>";}
	$url=$urlAnterior;
	
	if($id_solicitud>0)
	{
		require("../../../funciones/conexion_v2.php");
			$cons_S="SELECT * FROM solicitudes WHERE id='$id_solicitud' AND autorizado='si' LIMIT 1";
			$sql_S=$conexion_mysqli->query($cons_S);
			$num_solicitudes=$sql_S->num_rows;
			if(DEBUG){ echo"$cons_S<br>NUM: $num_solicitudes<br>";}
			if($num_solicitudes>0)
			{
				$continuar=true;
				$Ds=$sql_S->fetch_assoc();
					$S_tipo=$Ds["tipo"];
					$S_categoria=$Ds["categoria"];
				$sql_S->free();	
			}
			else
			{ $continuar=false;}
			
		$conexion_mysqli->close();
	//----------------------------------------------------------------------------------------------//	
		if($continuar)
		{
			require("../../../funciones/conexion_v2.php");
			$S_tipo=strtolower($S_tipo);
			switch($S_tipo)
			{
				case"certificado":
						if(DEBUG){ echo"TIPO: certificado<br>";}
						switch($S_categoria)
						{
							case"alumno_regular":
								if(DEBUG){ echo"Categoria: Alumno_regular<br>";}
								$url="certificado_alumno_regular/alumno_regular_v1_1.php?id_solicitud=$id_solicitud";
								break;
							case"titulo_en_tramite":
								if(DEBUG){ echo"Categoria: Titulo<br>";}
								$url="certificado_titulo_en_tramite/titulo_en_tramite_v1_1.php?id_solicitud=$id_solicitud";
								break;
							case"certificado_titulo":
								if(DEBUG){ echo"Categoria: Titulo<br>";}
								$url="certificado_titulo/certificado_titulo_v1_1.php?id_solicitud=$id_solicitud";
								break;		
							case"copia_titulo":
								if(DEBUG){ echo"Categoria: Titulo<br>";}
								$url="copia_titulo/copia_titulo_v1_1.php?id_solicitud=$id_solicitud";
								break;	
							case"concentracion_notas":
								if(DEBUG){ echo"Categoria: Concentracion Notas<br>";}
								$url="certificado_concentracion_notas/concentracion_notas_v1_1.php?id_solicitud=$id_solicitud";
								break;	
							case"concentracion_notas_HRS":
								if(DEBUG){ echo"Categoria: Concentracion Notas HRS<br>";}
								$url="certificado_concentracion_notasHrs/concentracion_notasHrs_v1_1.php?id_solicitud=$id_solicitud";
								break;
							case"egreso":
								if(DEBUG){ echo"Categoria: Egreso<br>";}
								$url="certificado_egreso/alumno_egreso_v1_1.php?id_solicitud=$id_solicitud";
								break;	
							case"plan_curricular":
								if(DEBUG){ echo"Categoria: Plan Curricular<br>";}
								$url="certificado_plan_curricular/plan_curricular_v1_1.php?id_solicitud=$id_solicitud";
								break;
							default:
								if(DEBUG){ echo"Categoria: Default<br>";}
								$url="../../buscador_alumno_BETA/HALL/index.php";	
						}
					break;
			}
			$conexion_mysqli->close();
		}
		
		
	}

	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}


?>