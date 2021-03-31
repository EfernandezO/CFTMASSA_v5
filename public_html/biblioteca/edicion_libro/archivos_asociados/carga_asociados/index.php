<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("Bibioteca_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	$id_libro=$_GET["id_libro"];
	if(is_numeric($id_libro))
	{
		$action="final_carga.php";
		//obtengo nombre de libro que mas tarde usare
		include("../../../../../funciones/conexion.php");
		$cons_N="SELECT nombre FROM biblioteca WHERE id_libro=$id_libro LIMIT 1";
		$sqlL=mysql_query($cons_N);
		$LX=mysql_fetch_assoc($sqlL);
		$titulo_libro_reg=$LX["nombre"];
		mysql_free_result($sqlL);
		//
	}
	else{header("location: ../seleccion_libro.php");}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<?php
$hay_errores=false;
if(isset($_GET["error"]))
{
	$hay_errores=true;
	$cod_error=$_GET["error"];
	if(!is_numeric($cod_error))
	{
		$cod_error=7;
	}
}
	$array_errores=array("Archivo Asociado Correctamente","Ha Ocurrido un Problema Asegurese de Activar Javascript...","El Archivo No pudo se Recibido Intente mas Tarde", "Ha Ocurrido un Error Al intentar registrar el Archivo en la BBDD...", "El Archivo No pudo ser Borrado","El Archivo Fue Correctamente Eliminado", "Ha Ocurrido un Error al Intentar eliminar el Registro del Archivo..."); 
	
////////intento cabiar permisos de carpeta...
	$permisos_carpeta=fileperms("../../../../CONTENEDOR_GLOBAL/biblioteca_img");
	$permisos_carpeta=substr(decoct($permisos_carpeta),1);
	
	$permisos_carpeta_2=fileperms("../../../../CONTENEDOR_GLOBAL/biblioteca_pdf");
	$permisos_carpeta_2=substr(decoct($permisos_carpeta_2),1);
	
	if(DEBUG){ 
		echo"Permisos(IMAGENES): $permisos_carpeta<br>";
		 echo"Permisos(PDF): $permisos_carpeta_2<br>";
		}
	if($permisos_carpeta!="0777")
	{
		if(chmod("../../../../CONTENEDOR_GLOBAL/biblioteca_img", 0777))
		{ echo"Permisos Cambiados...IMG :-)<br>";}
		else
		{ echo"<b>Fallo al intentar Cambiar Permisos...IMG :-(</b><br>";}
	}
	if($permisos_carpeta_2!="0777")
	{
		if(chmod("../../../../CONTENEDOR_GLOBAL/biblioteca_pdf", 0777))
		{ echo"Permisos Cambiados... PDF:-)<br>";}
		else
		{ echo"<b>Fallo al intentar Cambiar Permisos...PDF :-(</b><br>";}
	}
	
	//------------------------------------------------------------//
?>
<title>Archivos Asociados</title>

<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css">
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/SWFUpload/demos/css/default.css">
<script type="text/javascript" src="../../../../libreria_publica/SWFUpload/demos/swfupload/swfupload.js"></script>
<script type="text/javascript" src="js/fileprogress.js"></script>
<script type="text/javascript" src="js/handlers.js"></script>
<script type="text/javascript">
		var swfu;

		window.onload = function () {
			swfu = new SWFUpload({
				// Backend settings
				upload_url: "upload.php",
				file_post_name: "archivo_asociado",

				// Flash file settings
				file_size_limit : "10 MB",
				file_types : "*.pdf;*.jpeg;*.jpg",			// or you could use something like: "*.doc;*.wpd;*.pdf",
				file_types_description : "Pdf o Imagenes",
				file_upload_limit : "0",
				file_queue_limit : "1",

				// Event handler settings
				swfupload_loaded_handler : swfUploadLoaded,
				
				file_dialog_start_handler: fileDialogStart,
				file_queued_handler : fileQueued,
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				
				//upload_start_handler : uploadStart,	// I could do some client/JavaScript validation here, but I don't need to.
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,

				// Button Settings
				button_image_url : "boton.png",
				button_placeholder_id : "spanButtonPlaceholder",
				button_width: 61,
				button_height: 22,
				
				// Flash Settings
				flash_url : "../../../../libreria_publica/SWFUpload/demos/swfupload/swfupload.swf",

				custom_settings : {
					progress_target : "fsUploadProgress",
					upload_successful : false
				},
				
				// Debug settings
				debug: false
			});

		};
	</script>
<script language="javascript" type="text/javascript">	
function Confirmar(id_asociado, id_libro)
{
	C=confirm('¿Seguro desea Eliminar este Archivo Asociado?');
	if(C)
	{
		window.location="../elimina_asociado/index.php?id_asociado="+id_asociado+"&id_libro="+id_libro
	}
}
</script>
<style type="text/css">
<!--
#content #form1 #link {
	text-align: right;
	width: 520px;
}
.Estilo1 {font-size: 12px}
a:link {
	color: #006699;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #006699;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #006699;
}
.Estilo2 {
	font-size: 12px;
	color: #0000FF;
	font-weight: bold;
}
#apDiv1 {
	position:absolute;
	width:60%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 58px;
}
-->
</style>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /></head>
<body>
<h1 id="banner">Biblioteca - Archivos Asociados </h1>
<div id="link">
<br />
    <a href="../../../menu_biblioteca.php" class="button">Volver	a Biblioteca</a>
</div>
<div id="apDiv1">
<div id="content">
  <form action="<?php echo $action;?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
		
		  <p><span class="Estilo1">A&ntilde;ade Contenido a los Libros Registrados .  Se requiere Flash Player 9+ para su funcionamiento. </span><br />
<div class="fieldset">
			<span class="legend">Subir Archivos </span>
			<table style="vertical-align:top;">
				<tr>
					<td width="153"><label for="lastname">Titulo:</label></td>
				  <td width="379"><input name="titulo" id="titulo" type="text" style="width: 200px" />
				    <input name="id_libro" type="hidden" id="id_libro" value="<?php echo $id_libro;?>" /></td>
				</tr>
				<tr>
					<td><label for="txtFileName">Archivo:</label></td>
					<td>
					  <div>
							<div>
								<input type="text" id="txtFileName" disabled="true" style="border: solid 1px; background-color: #FFFFFF;" />
								<span id="spanButtonPlaceholder"></span>
								(10 MB max)							</div>
							<div class="flash" id="fsUploadProgress">
								<!-- This is where the file progress gets shown.  SWFUpload doesn't update the UI directly.
											The Handlers (in handlers.js) process the upload events and make the UI updates -->
							</div>
							<input type="hidden" name="hidFileID" id="hidFileID" value="" />
						  <!-- This is where the file ID is stored after SWFUpload uploads the file and gets the ID back from upload.php -->
						    <em>*Solo Archivos .jpg y .pdf*</em></div>					</td>
				</tr>
			</table>
			<br />
			<input type="submit" value="Continuar" id="btnSubmit" />
		</div>
  </form>
  
  <div id="tabla">
	<table summary="Archivos Cargados">
			<caption class="Estilo1">Archivos Cargados - <?php echo ucwords(strtolower($titulo_libro_reg));?>
	        </caption>
<thead>
				<tr>
					<th width="38" scope="col">N&ordm;</th>
					<th width="113" scope="col">Titulo</th>
					<th width="238" scope="col">Archivo</th>
					<th width="95" scope="col">Fecha Creacion </th>
					<th width="47" scope="col">Opci&oacute;n</th>
				</tr>
			</thead>	
			<tbody>
	<?php
	include("../../../../../funciones/funcion.php");
	$cons_B="SELECT * FROM biblioteca_asociados WHERE id_libro ='$id_libro'";
	$sql=mysql_query($cons_B)or die(mysql_error());
	$total_asociados=mysql_num_rows($sql);
	if($total_asociados>0)
	{
		$n=0;
		while($L=mysql_fetch_assoc($sql))
		{
			$id_asociado=$L["id"];
			$titulo=$L["titulo"];
			$archivo=$L["archivo"];
			$fecha_C=fecha_format($L["fecha"]);
			$n++;
			?>
				<tr class="odd">
					<th id="r1" scope="row"><?php echo $n;?></th>
					<td><?php echo $titulo;?></td>
					<td><?php echo $archivo;?></td>
					<td><?php echo $fecha_C;?></td>
					<td><a href="#" onclick="Confirmar('<?php echo $id_asociado;?>', '<?php echo $id_libro;?>');" title="Eliminar"><img src="../../../../BAses/Images/b_drop.png" width="16" height="16" alt="Eliminar" /></a></td>
				</tr>
			<?php
		}
	}
	else
	{
	?>
		<tr class="odd">
					<th colspan="5" class="Estilo1" id="r1" scope="row">Sin Archivos Asociados...</th>
		</tr>
	<?php			
	}
	?>
    <tr>
        <th scope="row">Total</th>
        <td colspan="4"><?php echo $total_asociados;?></td>
    </tr>
	</tbody>		
	</table>
	
	<div class="Estilo2" id="mensajes">
	<?php
	if($hay_errores)
	{ echo $array_errores[$cod_error];}
	 ?>
    </div>
  </div>
</div>
</div>
<?php
	mysql_free_result($sql);
	mysql_close($conexion);
?>
</body>
</html>