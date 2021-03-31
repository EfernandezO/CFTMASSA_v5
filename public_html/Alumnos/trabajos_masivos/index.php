<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Procesos_masivos_excel_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

////////intento cabiar permisos de carpeta...
	$permisos_carpeta=fileperms("../../CONTENEDOR_GLOBAL/trabajos_masivos/");
	$permisos_carpeta=substr(decoct($permisos_carpeta),1);
	if(DEBUG){ echo"Permisos: $permisos_carpeta<br>";}
	if($permisos_carpeta!="0777")
	{
		if(chmod("../../CONTENEDOR_GLOBAL/trabajos_masivos/", 0777))
		{ echo"Permisos Cambiados... :-)<br>";}
		else
		{ echo"<b>Fallo al intentar Cambiar Permisos... :-(</b><br>";}
	}
	//------------------------------------------------------------//
	include("../../../funciones/VX.php");
	$evento="Ingreso a Procesos Masivos Excel";
	REGISTRA_EVENTO($evento);
////////////////////////////////////////////////////
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<?php include("../../../funciones/codificacion.php"); ?>
<title>Proceso Masivos Excel</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/hint.css-master/hint.css"/>
<meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fine Uploader New/Modern CSS file
    ====================================================================== -->
   
<link rel="stylesheet" type="text/css" href="../../libreria_publica/fine-uploader-5.2.1/fine-uploader/fine-uploader-new.css">
    <!-- Fine Uploader JS file
    ====================================================================== -->
    <script src="../../libreria_publica/fine-uploader-5.2.1/fine-uploader/fine-uploader.js"></script>
    <!-- Fine Uploader Thumbnails template w/ customization
    ====================================================================== -->
    <script type="text/template" id="qq-template-validation">
        <div class="qq-uploader-selector qq-uploader" qq-drop-area-text="Arrastre Archivo Aqui">
            <div class="qq-total-progress-bar-container-selector qq-total-progress-bar-container">
                <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar"></div>
            </div>
            <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
                <span class="qq-upload-drop-area-text-selector"></span>
            </div>
            <div class="qq-upload-button-selector qq-upload-button">
                <div>Seleccione</div>
            </div>
            <span class="qq-drop-processing-selector qq-drop-processing">
                <span>Eliminando Archivo...</span>
                <span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
            </span>
            <ul class="qq-upload-list-selector qq-upload-list" aria-live="polite" aria-relevant="additions removals">
                <li>
                    <div class="qq-progress-bar-container-selector">
                        <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar"></div>
                    </div>
                    <span class="qq-upload-spinner-selector qq-upload-spinner"></span>
                    <img class="qq-thumbnail-selector" qq-max-size="100" qq-server-scale>
                    <span class="qq-upload-file-selector qq-upload-file"></span>
                    <span class="qq-upload-size-selector qq-upload-size"></span>
                    <button class="qq-btn qq-upload-cancel-selector qq-upload-cancel">Cancelar</button>
                    <button class="qq-btn qq-upload-retry-selector qq-upload-retry">Reintentar</button>
                    <button class="qq-btn qq-upload-delete-selector qq-upload-delete">Borrar</button>
                    <span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
                </li>
            </ul>

            <dialog class="qq-alert-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <div class="qq-dialog-buttons">
                    <button class="qq-cancel-button-selector">Cerrar</button>
                </div>
            </dialog>

            <dialog class="qq-confirm-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <div class="qq-dialog-buttons">
                    <button class="qq-cancel-button-selector">No</button>
                    <button class="qq-ok-button-selector">Si</button>
                </div>
            </dialog>

            <dialog class="qq-prompt-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <input type="text">
                <div class="qq-dialog-buttons">
                    <button class="qq-cancel-button-selector">Cancelar</button>
                    <button class="qq-ok-button-selector">Ok</button>
                </div>
            </dialog>
        </div>
    </script>
    <script language="javascript">
function Confirmar(id)
{
	c=confirm('seguro que desea Eliminar esta Prueba');
	if(c)
	{
		window.location="elimina_prueba.php?id="+id;
	}
}
function MSJ(txt)
{
	alert(txt);
}
</script>   
</head>
<body>
<h1 id="banner">Cargar Archivos Excel</h1>
<div id="link">
	<a href="../../Administrador/ADmenu.php" class="button">Volver al Menu</a><br /><br />
    <a href="#" class="button_R hint--left  hint--info" data-hint="Archivo .xls cargado debe tener el siguiente orden de columnas A: rut B:dv C:aporte_BNM D:aporte_BET E:cantidad_desc F:porcentaje_desc">info</a>
</div>
    <!-- Fine Uploader DOM Element
    ====================================================================== -->
    <div id="fine-uploader-validation"></div>

    <!-- Your code to create an instance of Fine Uploader and bind to the DOM/template
    ====================================================================== -->
    <script>
        var restrictedUploader = new qq.FineUploader({
            element: document.getElementById("fine-uploader-validation"),
            template: 'qq-template-validation',
            request: {
                endpoint: 'endpoint.php'
            },
            thumbnails: {
                placeholders: {
                    waitingPath: '../../libreria_publica/fine-uploader-5.2.1/fine-uploader/placeholders/waiting-generic.png',
                    notAvailablePath: '../../libreria_publica/fine-uploader-5.2.1/fine-uploader/placeholders/not_available-generic.png'
                }
            },
            validation: {
                allowedExtensions: ['xlsx', 'xls'],
                itemLimit: 3,
                sizeLimit: 7000000 // 50 kB = 50 * 1024 bytes
            }
        });
    </script>
   
<?php
  $msj="";
  if($_GET)
  {
  	$error=$_GET["error"];
	switch($error)
	{
		case"6":
			$msj='<span class="Estilo5">Error al Eliminar Archivo...</span>';
			break;		
		case"5":
			$msj='<span class="Estilo4">Archivo Eliminado Correctamente...</span>';
			break;		
	}
  }
  ?>
    <div id="msj"><?php echo $msj;?></div>
    <table width="100%" border="0"  summary="Excel Cargados ">
	<caption>
	Archivos Presentes<br />
	<br />
    </caption>
			<thead>
				<tr height="25">
					<th ><strong>N&ordm;</strong></th>
				  <th><strong>Archivo</strong></th>
				  <th colspan="5">Opcion</th>
		      </tr>
			</thead>	
			<tbody>
<?php
$ruta_dir="../../CONTENEDOR_GLOBAL/trabajos_masivos/";
$dir=dir($ruta_dir);
$array_extenciones=array("xlsx","xls");
$first=true;
$hay_archivos=false;
while($elemento=$dir->read())
{
	if(DEBUG){echo"--> $elemento :";}
	$largo_nombre=strlen($elemento);
	if($largo_nombre<4)
	{
		$utilizar=false;
		if(DEBUG){ echo"No Usar<br>";}
	}
	else
	{
		$utilizar=true;
		if(DEBUG){ echo"Usar<br>";}
	}
	if($utilizar)
	{
		$D_elemento=explode(".",$elemento);
		$nombre_archivo=$D_elemento[0];
		$extencion=end($D_elemento);
		
		if(in_array($extencion,$array_extenciones))
		{
			$hay_archivos=true;
			//$ruta_archivo=$ruta_dir.$elemento;
			$array_archivos_P[]=$elemento;
		}
		
	}
	
}
$dir->close();
if(DEBUG){var_export($array_archivos_P);}
		if($hay_archivos)
		{
			$contador=0;
			foreach($array_archivos_P as $n=>$valor)
			{
				$contador++;
				echo'<tr>
						<td height="50">'.$contador.'</td>
						<td><img src="../../BAses/Images/excel_icon.png" width="15" height="15" /> <a href="../../CONTENEDOR_GLOBAL/trabajos_masivos/'.$valor.'" target="_blanck">'.$valor.'</a></td>
						<td><a href="elimina_archivo.php?archivo='.base64_encode($valor).'" class="button_R hint--right  hint--info" data-hint="Eliminar esteArchivo">Eliminar</a></td>
						<td><a href="procesa_excel_v2.php?archivo='.base64_encode($valor).'" class="button hint--right  hint--info" data-hint="Genera Excel con Informacion General de Alumnos incluyendo notas, contrato..." >General</a></td>
						<td><a href="procesa_excel_liceo.php?archivo='.base64_encode($valor).'" class="button hint--right  hint--info" data-hint="Genera Excel con Informacion General de Alumnos incluyendo notas, contrato..." >Liceo - Ciudad</a></td>
						<td><a href="masivo_concentracion_notas.php?archivo='.base64_encode($valor).'" class="button hint--right  hint--info" data-hint="Genera .pdf con concentracines de notas de alumnos presentes en archivo" target="_blank">Concentracion</a></td>
						<td><a href="proceso_coteja_beneficiados.php?archivo='.base64_encode($valor).'" class="button hint--right  hint--info" data-hint="Compara alumnos beneficiados actualmente contra los de archivo cargado" target="_blank">Coteja Beneficiados</a></td>
						
						<td><a href="actualizaCampoConExcel.php?archivo='.base64_encode($valor).'" class="button hint--right  hint--info" data-hint="actualiza un campo previamente configurado con informacion de excel" target="_blank"> Campo Up</a></td>
	</tr>';
			}
		}
		else
		{echo'<tr><td>No hay Archivos Cargados...:(</td></tr>';}	
?>
	</tbody>		
  </table>    
</body>
</html>

                                