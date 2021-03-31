<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Alumnos_Pago_Mensualidades_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$error=$_GET["error"];
include("../../../../funciones/funcion.php");
$valor=str_inde($_GET["valor"]);
$error=str_inde($error,"1");
$num_cuotas=$_GET["num_cuotas"];
/////////
$id_boleta=$_GET["id_boleta"];
$semestre=$_GET["semestre"];
$year_estudio=$_GET["year"];
/////////
$url_boleta="../../contrato/imprimibles/boleta/boleta_1.php?id_boleta=$id_boleta&semestre=$semestre&year_estudio=$year_estudio&lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=430";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>Multi Pago Final</title>
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<!--INICIO LIGHTBOX EVOLUTION-->
  <script type="text/javascript" src="../../../libreria_publica/lightbox_1.6.9_evolution/js/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.css" />
  <!--[if IE 6]>
  <link rel="stylesheet" type="text/css" href="../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.ie6.css" />
  <![endif]-->
  <script type="text/javascript" src="../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/jquery.lightbox.min.js"></script>
 <script type="text/javascript">
    jQuery(document).ready(function($){
      $('.lightbox').lightbox(); 
    });
  </script>
 <!--FIN LIGHTBOX EVOLUTION--> 
  <script type="text/javascript">
    jQuery(document).ready(function($){
      $.lightbox("<?php echo $url_boleta;?>");
    });
  </script>
<script language="javascript">
function ABRE_VENTANA(url)
{
	//alert(url);
	window.open(url,'boleta','height=500, width=450');
}
</script>  
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:40%;
	height:115px;
	z-index:1;
	left: 30%;
	top: 135px;
}
#Layer2 {
	position:absolute;
	width:40%;
	height:117px;
	z-index:1;
	left: 30%;
	top: 130px;
}
.Estilo3 {color: #0080C0}
#Layer4 {
	position:absolute;
	width:121px;
	height:26px;
	z-index:1;
	left: 243px;
	top: 399px;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
#Layer3 {
	position:absolute;
	width:502px;
	height:87px;
	z-index:2;
	left: 66px;
	top: 33px;
}
.Estilo4 {
	font-size: large;
	color: #FF0000;
}
.Estilo5 {
	color: #669966;
	font-size: large;
}
-->
</style>
<style type="text/css">
<!--
.Estilo6 {
	font-size: 12px;
	font-weight: bold;
}
.Estilo7 {font-size: 12px}
#link {
	text-align: right;
	padding-right: 10px;
}
.Estilo9 {
	font-size: 12px;
	color: #0000FF;
	font-weight: bold;
	text-decoration: blink;
}
.Estilo10 {font-size: 12%}
-->
</style>
</head>

<body>
<h1 id="banner">Finanzas - Pago Mensualidad Linea de Credito</h1>
<div id="link"><span class="Estilo3"><br />
<a href="../cuota1.php" class="button">Volver a Seleccion</a></span></div>
<?php
if($_GET)
{
   
	if($error=="0")
	{
     ?>
       <div id="Layer1">
       <table width="100%" sumary="resumen">
	   <caption></caption>
	   <thead>
       <tr>
       <th colspan="2" scope="col"><div align="center" class="Estilo1 Estilo5"><strong>INFORMACION</strong></div></th>
       </tr>
	   </thead>
		<tbody>
       <tr class="odd">
       <td width="37%" bgcolor="#FFFFCC"><span class="Estilo6">Cuotas Pagadas</span></td>
        <td width="63%" bgcolor="#FFFFCC"><div align="right" class="Estilo3 Estilo7"><?php echo"$num_cuotas";?></div></td>
        </tr>
        <tr class="odd">
        <td bgcolor="#FFFFCC"><span class="Estilo7"><strong>Valor:</strong></span></td>
        <td bgcolor="#FFFFCC"><div align="right" class="Estilo7">$ <?php echo number_format($valor,0,",",".");?></div>        </td>
        </tr>
		
        <tr>
        <td colspan="2" scope="col">&nbsp;</td>
        </tr>
        <tr>
        <td colspan="2" bgcolor="#FFFFCE"><div align="center" class="Estilo6">Ha sido Pagada Con Exito<br />
  Transacci&oacute;n realizada Satisfactoriamente </div></td>
        </tr>
        <tr>
        <td colspan="2" ><div id="boleteria">
       
          <div align="center" class="Estilo10"><a href="<?php echo $url_boleta;?>" class="lightbox"><img src="../../../BAses/Images/imprimir.jpg" alt="i" width="29" height="30" /> <br />
              <span class="Estilo7">IMPRIMIR BOLETA</span></a><br />
             </div>
        </div></td>
        </tr>
        <tr>
          <td colspan="2" ><span class="Estilo9">*No Olvide Registrar La Boleta*</span></td>
        </tr>
        <tr>
          <td colspan="2" ><a href="../cuota1.php" class="Estilo6">Volver a las Cuotas</a></td>
        </tr>
		</tbody>
        </table>
</div>
<?php
	}
	if($error=="1")
	{
	     ?>
          <div id="Layer2">
          <table sumary="Error">
		  <caption></caption>
		  <thead>
          <tr>
          <th scope="col"><div align="center" class="Estilo1 Estilo4">Error</div></th>
          </tr>
		  </thead>
		  <tbody>
          <tr class="odd">
      <td ><div align="center" class="Estilo6">Ha Ocurrido un Error Al Intentar Realizar la Transaccion o ha introducido una cantidad no Valida,<br />
        Por Favor Intentelo Desp&uacute;es <br />
      </div>        
      <div align="center"></div>
      <div align="center"></div>        <div align="center"></div></td>
    </tr>
    </tbody>
	<tfoot>
    <tr>
      <td>&nbsp;
              </td>
    </tr>
	</tfoot>
  </table>
</div>
<?php
	}	
}
else
{
    echo"NO GET<br>";
}
?>
</body>
</html>