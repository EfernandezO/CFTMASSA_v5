<?php
/**
 * PHP Server-Side Example for Fine Uploader (traditional endpoint handler).
 * Maintained by Widen Enterprises.
 *
 * This example:
 *  - handles chunked and non-chunked requests
 *  - supports the concurrent chunking feature
 *  - assumes all upload requests are multipart encoded
 *  - supports the delete file feature
 *
 * Follow these steps to get up and running with Fine Uploader in a PHP environment:
 *
 * 1. Setup your client-side code, as documented on http://docs.fineuploader.com.
 *
 * 2. Copy this file and handler.php to your server.
 *
 * 3. Ensure your php.ini file contains appropriate values for
 *    max_input_time, upload_max_filesize and post_max_size.
 *
 * 4. Ensure your "chunks" and "files" folders exist and are writable.
 *    "chunks" is only needed if you have enabled the chunking feature client-side.
 *
 * 5. If you have chunking enabled in Fine Uploader, you MUST set a value for the `chunking.success.endpoint` option.
 *    This will be called by Fine Uploader when all chunks for a file have been successfully uploaded, triggering the
 *    PHP server to combine all parts into one file. This is particularly useful for the concurrent chunking feature,
 *    but is now required in all cases if you are making use of this PHP example.
 */
// Include the upload handler class

require_once "handler.php";
$uploader = new UploadHandler();
// Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
$uploader->allowedExtensions = array(); // all files types allowed by default
// Specify max file size in bytes.
$uploader->sizeLimit = 7 * 1024 * 1024; // default is 10 MiB
// Specify the input name set in the javascript.
$uploader->inputName = "qqfile"; // matches Fine Uploader's default inputName value by default
// If you want to use the chunking/resume feature, specify the folder to temporarily save parts.
$uploader->chunksFolder = "../../CONTENEDOR_GLOBAL/archivos_temporales";
$method = $_SERVER["REQUEST_METHOD"];

if ($method == "POST") {
    header("Content-Type: text/plain");
    // Assumes you have a chunking.success.endpoint set to point here with a query parameter of "done".
    // For example: /myserver/handlers/endpoint.php?done
    if (isset($_GET["done"])) {
        $result = $uploader->combineChunks("../../CONTENEDOR_GLOBAL/PLANIFICACIONES_D");
		
    }
    // Handles upload requests
    else {
        // Call handleUpload() with the name of the folder, relative to PHP's getcwd()
		require("../../../funciones/conexion_v2.php");
		
		$sede=$_POST["sede"];
		$id_carrera=$_POST["id_carrera"];
		$cod_asignatura=$_POST["cod_asignatura"];
		$jornada=$_POST["jornada"];
		$grupo=$_POST["grupo"];
		$semestre=$_POST["semestre"];
		$year=$_POST["year"];
		$id_funcionario=$_POST["id_funcionario"];
		$fechaHora_actual=date("Y-m-d H:i:s");
		
		
		$nombre_archivo_new='planiD_'.$id_funcionario.$semestre.$year.$id_carrera.$cod_asignatura.$jornada.$grupo.$sede;
		$EX=$uploader->getExtencion();
		$nombre_archivo_new=str_replace(".","",$nombre_archivo_new);
		$nombre_archivo_new=str_replace(" ","",$nombre_archivo_new);
		$nombre_archivo_new=strtolower($nombre_archivo_new.'.'.$EX);
		
		
		$cargar_archivo=false;
		///-------------------------------------------------------//
		//escritura en BBDD
		require("../../../funciones/VX.php");
		$cons="SELECT * FROM planificaciones_v2 WHERE sede='$sede' AND semestre='$semestre' AND year='$year' AND id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND jornada='$jornada' AND grupo='$grupo' AND id_funcionario='$id_funcionario'";
$sqli_P=$conexion_mysqli->query($cons);
$num_planificaciones=$sqli_P->num_rows;

		//echo"$cons NUM planificaciones ya cargadas: $num_planificaciones<br>";

		if($num_planificaciones>0)
		{
			$cons_UP="UPDATE planificaciones_v2 SET archivo='$nombre_archivo_new', fecha_generacion='$fechaHora_actual' WHERE sede='$sede' AND semestre='$semestre' AND year='$year' AND id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND jornada='$jornada' AND grupo='$grupo_curso' AND id_funcionario='$id_usuario_actual'";
			
			if($conexion_mysqli->query($cons_UP))
			{
				$evento="MODIFICACION Carga Planificacion_v2 periodo $sede [$semestre - $year] id_carrera: $id_carrera cod_asignatura: $cod_asignatura archivo: $nombre_archivo_new";
				REGISTRA_EVENTO($evento);
				$error="PV2_2";
				$cargar_archivo=true;
				
				while($PLX=$sqli_P->fetch_assoc())
				{
					$PLX_archivo_old=$PLX["archivo"];
					
					//echo"---> $PLX_archivo_old ";
					if(unlink('../../CONTENEDOR_GLOBAL/PLANIFICACIONES_D/'.$PLX_archivo_old)){ }
					else{ }
					
				}
				$sqli_P->free();
			}
			else
			{
				$error="PV2_3";
				//echo"--->".$conexion_mysqli->error;
			}
		}
		else
		{
			$cons_IN="INSERT INTO planificaciones_v2 (id_funcionario, semestre, year, archivo, sede, id_carrera, cod_asignatura, jornada, grupo, fecha_generacion) VALUES ('$id_funcionario', '$semestre', '$year', '$nombre_archivo_new', '$sede', '$id_carrera', '$cod_asignatura', '$jornada', '$grupo', '$fechaHora_actual')";
			
			if($conexion_mysqli->query($cons_IN))
			{
				$evento="Carga Planificacion_v2 periodo $sede [$semestre - $year] id_carrera: $id_carrera cod_asignatura: $cod_asignatura archivo: $nombre_archivo_new";
				REGISTRA_EVENTO($evento);
				$error="PV2_0";
				$cargar_archivo=true;
			}
			else
			{
				$error="PV2_1";
				//echo"--->".$conexion_mysqli->error;
			}
		}
		$conexion_mysqli->close();
		//----------------------------------------------------------------------
		
		if($cargar_archivo)
		{
			//nombre nuevo archivo;
			//echo"---------------> ".$nombre_archivo_new;
			$result = $uploader->handleUpload("../../CONTENEDOR_GLOBAL/PLANIFICACIONES_D",$nombre_archivo_new);
			// To return a name used for uploaded file you can use the following line.
			$result["uploadName"] = $uploader->getUploadName();
			//echo"=============>".$uploader->getUploadName();
		}
		
		
		
    }
    echo json_encode($result);
}
// for delete file requests
else if ($method == "DELETE") {
    //$result = $uploader->handleDelete("../../CONTENEDOR_GLOBAL/PLANIFICACIONES_D");
    echo json_encode($result);
}
else {
    header("HTTP/1.0 405 Method Not Allowed");
}
?>