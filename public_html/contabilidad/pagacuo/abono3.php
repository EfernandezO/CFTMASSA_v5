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
$id_cuotaX=$_GET["id_cuota"];
$valorX=$_GET["valor"];
$id_boleta=$_GET["id_boleta"];
$semestre=$_GET["semestre"];
$year_estudio=$_GET["year_estudio"];
$error=$_GET["error"];
$url_boleta="../contrato/imprimibles/boleta/boleta_1.php?id_boleta=$id_boleta&semestre=$semestre&year_estudio=$year_estudio&lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=430";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Abono Final</title>
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
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:90%;
	height:139px;
	z-index:2;
	left: 5%;
	top: 105px;
}
#Layer2 {
	position:absolute;
	width:90%;
	height:112px;
	z-index:1;
	left: 5%;
	top: 110px;
}
#Layer3 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 103px;
}
#Layer4 {
	position:absolute;
	width:114px;
	height:22px;
	z-index:1;
	left: 604px;
	top: 173px;
}
.Estilo3 {color: #0080C0}
.Estilo5 {
	font-size: 18px;
	color: #FFFFFF;
}
#Layer5 {
	position:absolute;
	width:435px;
	height:82px;
	z-index:2;
	left: 101px;
	top: 46px;
}
.Estilo6 {color: #669900}
.Estilo7 {
	color: #FF0000;
	font-size: large;
}
.Estilo8 {
	color: #FFFF00;
	font-size: large;
}
.Estilo9 {
	font-size: 12px;
	font-weight: bold;
}
.Estilo10 {font-size: 12px}
#link {
	text-align: right;
	padding-right: 10px;
}
.Estilo12 {
	font-size: 12px;
	color: #0000FF;
	text-decoration: blink;
	font-weight: bold;
}
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
    //echo"Error: $error<br>";
	include("../../../funciones/funcion.php");
	$error=str_inde($error,"1");
	/////
	
	//echo"Error: $error<br>";
	if($error=="0")
	{
		?>
        <div id="Layer1">
          <table width="60%" sumary="informacion">
        </table>
        <div align="center">
          <table width="60%" align="center" sumary="informacion">
            <caption>&nbsp;
            </caption>
            <thead>
              <tr>
                <th width="100%" colspan="2" scope="col"><div align="center" class="Estilo1 Estilo5 Estilo6"><strong>INFORMACION</strong></div></th>
              </tr>
            </thead>
            <tbody>
              <tr class="odd">
                <td height="67" colspan="2" ><div align="center" class="Estilo9">La Transacci&oacute;n se ha Realizado Correctamente <img src="../../BAses/Images/ok.png" width="29" height="26" /></div></td>
              </tr>
            </tbody>
            <tr>
              <td height="27" colspan="2" bgcolor="#EBE5D9">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2" bgcolor="#FFFFCC"><div id="boleteria">
                  <div align="center" class="Estilo10"><a href="<?php echo $url_boleta;?>" class="lightbox"><img src="../../BAses/Images/imprimir.jpg" alt="i" width="29" height="30" /> <br />
                    IMPRIMIR BOLETA</a><br />
                  </div>
              </div></td>
            </tr>
            <tr>
              <td height="45" colspan="2"><span class="Estilo12">*No Olvide Registar La Boleta*</span></td>
            </tr>
            <tr>
              <td height="39" colspan="2" align="center"><a href="cuota1.php" class="button">Volver a las Cuotas</a></td>
            </tr>
            <tfoot>
             <tr>
               <td colspan="2" scope="col" class="Estilo6" >&nbsp;</td>
             </tr>
            </tfoot>
          </table>
            <div id="final">
           <p>Para anula esta Transacci&oacute;n haga click <a href="../anula_transaccion/anula_transaccion.php?id_boleta=<?php echo $id_boleta;?>&id_cuota=<?php echo $id_cuotaX;?>&valor=<?php echo $valorX;?>">Aqui</a></p>
         </div>
        </div>
</div>
<?php
	}
	
	if($error=="1")
	{
	    // echo"No numero<br>";
	  ?>
         <div id="Layer2">
         <table width="60%" align="center" sumary="Error">
		 <caption></caption>
		 <thead>
         <tr>
         <th width="100%" height="25" colspan="2" bgcolor="#FF0000" scope="col"><div align="center" class="Estilo1 Estilo7">ERROR</div></th>
         </tr>
		 </thead>
		 <tbody>
         <tr class="odd">
         <td  colspan="2" ><div align="center" class="Estilo9">No Ingrese Letras en el Abono </div>         </td>
         </tr>
		 </tbody>
		 <tfoot>
         <tr>
         <td colspan="2" bgcolor="#FF0000">&nbsp;</td>
         </tr>
		 </tfoot>
         </table>
</div>
<?php
	}
	if($error=="2")
	{
	     //echo"Excede<br>";
	     ?>
         <div id="Layer3">
         <table align="center" sumary="advertencia">
		 <caption></caption>
		 <thead>
         <tr>
         <th scope="col" colspan="2" ><div align="center" class="Estilo1 Estilo4 Estilo8"><strong>ADVERTENCIA</strong></div></th>
         </tr>
		 </thead>
		 <tbody>
         <tr class="odd">
         <td height="65" colspan="2" bgcolor="#66FFFF"><div align="center" class="Estilo9"><span class="Estilo2">No</span> Ingrese Valores Mayores al Valor de la Deuda, Antes Paguelas individualmente         </div></td>
         </tr>
		 </tbody>
		 <tfoot>
         <tr>
         <td colspan="2" bgcolor="#FFFF00">&nbsp;</td>
         </tr>
		 </tfoot>
         </table>
</div>
<?php
	}
	
	
}
else
{
    echo"No GET<br>";
}
?>
</body>
</html>