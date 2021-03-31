<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Bibioteca_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$maximo_libro_prestados_alumno=5;//maximo libros a alumno

	if(($_SESSION["SELECTOR_ALUMNO_BIBLIOTECA"]["ACTIVO"])and($_SESSION["SELECTOR_ALUMNO_BIBLIOTECA"]["id_libro_a_prestar"]>0))
	{
		include("../../../../funciones/conexion_v2.php");
		 //--------Cuantos Libros Tiene el Alumno---------//
		 $fecha_devolucion=$_GET["devolucion"];
		 $id_alumno=$_SESSION["SELECTOR_ALUMNO_BIBLIOTECA"]["id"];
		 $cons_BX="SELECT COUNT(id_libro) FROM biblioteca WHERE id_alumno='$id_alumno'";
		 if(DEBUG){ echo"A: $cons_BX<br>";}
		 $sql_BX=$conexion_mysqli->query($cons_BX);
		 $DATO=$sql_BX->fetch_row();
		 $cantidad_libros_alumno=$DATO[0];
		 
			if(DEBUG){echo"---> Cantidad $cantidad_libros_alumno<br> $maximo_libro_prestados_alumno<br>";}
		$sql_BX->free();
		$conexion_mysqli->close();
		 //-----------------------------------------------//
		 if($cantidad_libros_alumno <= $maximo_libro_prestados_alumno)
		 {
			 
	     	if(DEBUG){ echo"El Alumno aun no alcanza el limite maximo de prestamos<br>continuar<br>";}
			else{header ("Location: verifica_prestamo_2.php?devolucion=$fecha_devolucion");}
		 }
		 else
		 {
		 	 if(DEBUG){ echo"Alumno Alcanso el Maximo Numero de Prestamos...<br>";}
			 else{header("Location: verifica_prestamo_2X.php?error=maximo");}
		 }	
	  
	  }
	  else
	  {
	    if(DEBUG){ echo"Sin Alumno o libro seleccionado<br>";}
		else{header("Location: ../menubiblio.php");}
	  }
?>