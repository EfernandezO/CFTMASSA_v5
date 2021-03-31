<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="ALUMNO";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	
if(isset($_SESSION["SELECTOR_ALUMNO"]))
{unset($_SESSION["SELECTOR_ALUMNO"]);}
//-----------------------------------------//	
$privilegio=$_SESSION["USUARIO"]["privilegio"];
$sede_usuario=$_SESSION["USUARIO"]["sede"];
$id_usuario_actual=$_SESSION["USUARIO"]["id"];

$fecha_actual=date("Y-m-d");
$fecha_limite=date("Y-m-d", strtotime("$fecha_actual -10 days"));///fecha limite =fecha corte +10 dias
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/hint.css-master/hint.css"/>
<title>Revisi&oacute;n de Solicitudes General</title>
<style type="text/css">
#apDiv2 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:2;
	left: 5%;
	top: 147px;
}
#apDiv1 {
	position:absolute;
	width:90%;
	height:44px;
	z-index:3;
	left: 5%;
	top: 319px;
}
#apDiv3 {
	position:absolute;
	width:40%;
	height:17px;
	z-index:4;
	left: 50%;
	top: 159px;
	text-align: center;
}

#contenedor {
    display: table;
    border: 2px solid #000;
    width: 300px;
    text-align: center;
    margin: 0 auto;
}
#contenidos {
    display: table-row;
}
#columna1, #columna2, #columna3 {
    display: table-cell;
    border: 1px solid #000;
    vertical-align: middle;
    padding: 10px;
}
</style>

<?php
switch($privilegio)
		{
			case"admi_total":
				$url_menu="../../Administrador/ADmenu.php";
				$msj_solicitudes="[todas]";
				break;
			case"matricula":
				$url_menu="../../Administrador/menu_matricula/index.php";
				$msj_solicitudes="[no autorizadas]";
				break;
			case"admi":
				$url_menu="../../Administrador/ADmenu.php";
				$msj_solicitudes="[pendientes]";
				break;
			case"ALUMNO":
				$url_menu="../../Alumnos/okalumno.php";
				$msj_solicitudes="[pendientes]";
				break;
			default:
				$url_menu="#";
				$ver_boton_opciones=false;
				$msj_solicitudes=" ";
		}
?>
<script type="text/javascript" src="../../libreria_publica/jquery.js"></script>

<script type="text/javascript">
var pagina=1;
$(document).ready(function()
{
	// Carga inicial	
	cargardatos();
	
});
function cargardatos(){
		// PeticiÃ³n AJAX
		
		$("#loader").html('<img src="../../BAses/Images/massa_loading.gif" width="80" height="80" alt="cargando..." />');
		$.get("server.php?pagina="+pagina,
			function(data){
				if (data != "") {
					//$(".mensaje:last").after(data); 
					$('#tabla_datos tr:last').after(data);
				}
				$('#loader').empty();
			}
		);				
	}
$(window).scroll(function(){
	if ($(window).scrollTop() == $(document).height() - $(window).height()){
		pagina++;
		cargardatos()
	}					
});
</script>
</head>
<body>
<h1 id="banner">Administrador - Revisi&oacute;n de Solicitudes General</h1>
<div id="link"><br />
<a href="<?php echo $url_menu;?>" class="button">Volver a Menu</a></div>
<div id="mensajes">
	<div class="mensaje"></div>	
</div>
<div id="loader"></div>

<div id="apDiv2">
  <table width="100%" border="1" align="center" id="tabla_datos">
    <thead>
      <tr>
        <th colspan="10">Solicitudes Existentes <?php echo" $msj_solicitudes";?></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><strong>N</strong></td>
        <td><strong>Tipo</strong></td>
        <td><strong>Categoria</strong></td>
        <td><strong>Receptor</strong></td>
        <td><strong>Nombre Receptor</strong></td>
        <td><strong>Carrera Receptor</strong></td>
        <td><strong>Autorizado</strong></td>
        <td><strong>Archivo</strong></td>
        <td><strong>Estado</strong></td>
        <td><strong>Opcion</strong></td>
      </tr>
    </tbody>
  </table>
</div>
</body>
</html>