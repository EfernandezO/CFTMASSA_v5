<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumnos_Pago_Mensualidades_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	include("../../../funciones/funcion.php");
	$error=$_GET["error"];
	$id_cuota=str_inde($_GET["id_cuota"]);
	$valor=str_inde($_GET["v"]);
	$error=str_inde($error,"1");
	/////////
	$id_boleta=$_GET["ID"];
	$semestre=$_GET["semestre"];
	$year_estudio=$_GET["year_estudio"];
	/////////
	 $url_boleta="../contrato/imprimibles/boleta/boleta_1.php?id_boleta=$id_boleta&semestre=$semestre&year_estudio=$year_estudio&lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=430";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Pago Final</title>
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<!--INICIO LIGHTBOX EVOLUTION-->
  <script type="text/javascript" src="../../libreria_publica/lightbox_1.6.9_evolution/js/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.css" />
  <!--[if IE 6]>
  <link rel="stylesheet" type="text/css" href="../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.ie6.css" />
  <![endif]-->
  <script type="text/javascript" src="../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/jquery.lightbox.min.js"></script>
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
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:2;
	left: 5%;
	top: 75px;
}
#Layer2 {
	position:absolute;
	width:90%;
	height:117px;
	z-index:1;
	left: 5%;
	top: 140px;
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
#Layer1 #final {
	margin-top: 20px;
	padding-top: 20px;
	width: 60%;
	border: thin solid #FF0000;
	padding-bottom: 0px;
	text-align: left;
}
-->
</style>
</head>
<body <?php if($error=="0"){?> <?php }?>>
<h1 id="banner">Finanzas - Pago Mensualidad Linea de Credito v.2.1</h1>
<div id="link"><span class="Estilo3"><br />
<a href="cuota1.php" class="button">Volver a Seleccion</a></span></div>
<?php
if($_GET)
{
	if($error=="0")
	{
     ?>
       <div id="Layer1">
       <div align="center">
         <table width="60%" sumary="resumen">
           <caption>
           </caption>
           <thead>
             <tr>
               <th colspan="2" scope="col"><div align="center" class="Estilo1 Estilo5"><strong>INFORMACION</strong></div></th>
             </tr>
           </thead>
           <tbody>
             <tr class="odd">
               <td width="37%" bgcolor="#FFFFCC"><span class="Estilo6">ID Cuota</span></td>
               <td width="63%" bgcolor="#FFFFCC"><div align="right" class="Estilo3 Estilo7"><?php echo"$id_cuota";?></div></td>
             </tr>
             <tr class="odd">
               <td bgcolor="#FFFFCC"><span class="Estilo7"><strong>Valor:</strong></span></td>
               <td bgcolor="#FFFFCC"><div align="right" class="Estilo7">$ <?php echo number_format($valor,0,",",".");?></div></td>
             </tr>
             <tr>
               <td colspan="2" scope="col">&nbsp;</td>
             </tr>
             <tr>
               <td colspan="2" bgcolor="#FFFFCE"><div align="center" class="Estilo6">Ha sido Pagada Con Exito<br />
                 Transacci&oacute;n realizada Satisfactoriamente <img src="../../BAses/Images/ok.png" alt="ok" width="29" height="26" /></div></td>
             </tr>
             <tr>
               <td colspan="2"  align="center"><div id="boleteria">
                   <div align="center" class="Estilo10">
                   <a href="<?php echo $url_boleta;?>"  class="lightbox"><img src="../../BAses/Images/imprimir.jpg" alt="i" width="29" height="30" /><span class="Estilo7"><br />
                   IMPRIMIR BOLETA</span></a>
                   </div>
               </div></td>
             </tr>
             <tr>
               <td height="45" colspan="2" ><span class="Estilo9">*No Olvide Registrar La Boleta*</span></td>
             </tr>
             <tr>
               <td height="47" colspan="2" class="Estilo6"  align="center"><a href="cuota1.php" class="button">Volver a las cuotas</a></td>
             </tr>
           </tbody>
             <tfoot>
             <tr>
               <td colspan="2" scope="col" class="Estilo6" >&nbsp;</td>
             </tr>
             </tfoot>
           
         </table>
         <div id="final">
           <p>Para anula esta Transacci&oacute;n haga click <a href="../anula_transaccion/anula_transaccion.php?id_boleta=<?php echo $id_boleta;?>&id_cuota=<?php echo $id_cuota;?>&valor=<?php echo $valor;?>">Aqui</a></p>
         </div>
       </div>
</div>
<?php
	}
	if($error=="1")
	{
	     ?>
          <div id="Layer2">
          <table width="60%" align="center" sumary="Error">
		  <caption></caption>
		  <thead>
          <tr>
          <th scope="col"><div align="center" class="Estilo1 Estilo4">Error</div></th>
          </tr>
		  </thead>
		  <tbody>
          <tr class="odd">
      <td ><div align="center" class="Estilo6">Ha Ocurrido un Error Al Intentar Realizar la Transaccion, Por Favor Intentelo Desp&uacute;es </div>        
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