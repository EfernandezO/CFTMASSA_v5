<?php
    $idn=$_POST["ocu_idn"];
	$img=$_POST["ocu_ruta"];
	 include("../../../funciones/conexion.php");
	 $cons="DELETE FROM noticias WHERE idn='$idn' LIMIT 1";
	 $error_D=1;
	 
	 
	 
	 if(mysql_query($cons));
	  {
		    header("Location: ../image_not/borrar_img.php?nombre='$img'");;
	  }	   
?>
