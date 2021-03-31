<?php
	 session_start();
	 $mostrar=false;
	 $acceso_biblio=$_SESSION["acceso_biblio"];
	 if($acceso_biblio=="SI")
	 {
	 	$mostrar=true;
	 }
	// echo"$acceso_biblio<br>";
	 //var_dump($_SESSION);
if(($_GET)&&($mostrar))
{
	$pdf=$_GET["pdf"];
	$pdf=base64_decode($pdf);
	$path="../../CONTENEDOR_GLOBAL/biblioteca_pdf/";
	$ruta_pdf=$path.$pdf;
#esta variable recoge el nombre del fichero a visualizar
//$ruta_pdf="../PDF_L/prueba_(9951).pdf";
#esta función determina la longitud en bytes del fichero
$len = filesize($ruta_pdf);
/* esta cabecera -válida para HTTP/1.1- ordena al navegador
que no permita guardar la página
que no permita que se almecene en la caché del cliente*/
header("Cache-Control: no-store, no-cache, must-revalidate");
/* esta otra cabecera -válida para HTTP/1.0
indica al navegador que no guarde la página en la caché del cliente
he puesto ambas opciones para cubrir todo el especto probable */
header("Pragma: no-cache");
/* esta cabecera especifica al navegador el contenido
que va a recibir que en este caso no sería otra cosa
que algo que requiere una aplicacion capaz de interpretar
ficheros pdf */
header("Content-type: application/pdf");
/* como la norma de los headers establece que
siempre que se conozca el tamaño del contenido enviado
se incluya en la cabecera ese contenido, pues...
incluimos el tamaño ya que "filesize" nos midió el fichero
y guardo esa medida en la variable $len...
pero... fue posible utilizar esa función antes de las header
porque esa medida no fue mandada a la salida...
si hubiéramos escrito antes de los header... algo así como
Echo $len; ... la habríamos fastidiado...
ya habríamos tenido error en las cabeceras...*/
header("Content-Length: $len");
/* con esta otra header indicamos ls forma de presentación de
el contenido del documento... permite dos posibilidades
inline (la que he puesto aquí) o
attachment (que seria como fichero anexo)
fijate que en este "header" he puesto en filename un nombre distinto
del que tenía el fichero original... eso no tiene importancia
solo será el nombre con el que se guardará en la caché del cliente
en el caso de que no hubiéramos incluido la cabecera "no cache"
que dicho sea de paso... la he puesto aquí como ejemplo
pero que serían absolutamente innecesarias para este ejemplo
de visualización del documento */
header("Content-Disposition: inline; filename=$nombre_pdf");
/* ya se acabaron las cabeceras del documento
aquí le decimos al servidor que lea el fichero y lo envie
al navegador del cliente... este ya lo interpretará
siguiendo las especificaciones que le hemos incluido
las cabeceras....*/
readfile($ruta_pdf);
}
else
{
	header("location: ../index.php");
}
?>