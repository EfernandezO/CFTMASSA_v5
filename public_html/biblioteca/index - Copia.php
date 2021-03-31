<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
//////////////////////XAJAX/////////////////
@require_once ("../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("search_server.php");
$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCAR_LIBRO");
////////////////////////////////////////////
?>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="../IMG_store/emov.css">
<title>CFT Massachusetts</title>

 <style type="text/css">
<!--
.Estilo1 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
	color: #1D4771;
	font-size: 12px;
}
#searchbox
{
width:300px;
padding:0px;
}
#display
{
	position: absolute;
	width:299px;
	float:left;
	margin-left:1px;
	border-left:solid 1px #dedede;
	border-right:solid 1px #dedede;
	border-bottom:solid 1px #dedede;
	overflow:hidden;
	visibility: hidden;
}
.display_box
{
padding:4px; border-top:solid 1px #dedede; font-size:12px; height:30px;
}

.display_box:hover
{
background:#CCFF99;
color:#FFFFFF;
}
#shade
{
background-color:#00CCFF;

}
#apDiv1 {	position:absolute;
	width:100%;
	height:115px;
	z-index:2;
	left: 0px;
	top: 14px;
	text-align: center;
}

-->
 </style>
 <?php $xajax->printJavascript(); ?> 
<!--INICIO -->
<script type="text/javascript" src="../libreria_publica/jquery.js"></script>
<script type="text/javascript" src="../libreria_publica/jquery.watermarkinput.js"></script>
<script type="text/javascript">
jQuery(function($){
   $("#searchbox").Watermark("Todos");
   });
</script>
<script language="javascript" type="text/javascript">
function sobrescribir(texto,carrerax)
{
	document.getElementById('searchbox').value=texto;
	//alert(carrerax);
	document.getElementById('carrera').value=carrerax;
	document.getElementById("display").style.visibility="hidden";//oculto sugerencias
	//document.forms['frm']['carrera'].value='General'
	//actual=document.getElementById('carrera').value;
	//alert('actual '+actual);
	//
}
</script>
<!--FIN -->
<script type="text/javascript">
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>
</head>
<body onload="MM_preloadImages('../IMG_store/btn_admin_press.jpg','../IMG_store/btn_docente_press.jpg','../IMG_store/btn_biblio_press.jpg','../IMG_store/btn_galeria_press.jpg','../IMG_store/btn_contacto_press.jpg','../IMG_store/btn_webmail_press.jpg','../IMG_store/btn_alumnos_press.jpg','../IMG_store/btn_exalumnos_press.jpg')">
<div id="bannerX">
<div id="header">
  <div id="header_r">
	<span class="Estilo1"><u>Carreras</u> </span>
	<h2>Tecnico en Enfermeria</h2>
	<h2>Tecnico en Construccion</h2>
	<h2>Tecnico Juridico</h2>
	<h2>Secretariado</h2>
	<h2>Mass..</h2>
  </div>
  <div id="header_logo">
    <p>&nbsp;</p>
    </div>	
</div>

<div id="apDiv1"><img src="../IMG_store/imagenes/bannerX_titulo_03.png" alt="CFT" width="357" height="124" /></div>
<div id="header_menu"><img src="../IMG_store/h_menu.png" width="191" height="11"></div>
</div>

<div id="body">
  <div id="izquierda">
		<div id="menu">
	  <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tbody><tr>
          <td bgcolor="#e8e8e6">
		  <h1>Nuestra Institución</h1> 
              <ul>
                <li><a href="../nuestra_institucion/quienes_somos.php">Quienes Somos</a></li>
                <li><a href="../nuestra_institucion/direccion_docencia.php">Direcci&oacute;n y Docencia</a></li>
                <li><a href="../nuestra_institucion/mision_vision.php">Misión - Visión</a></li>
              </ul>
		  <h2>Sedes</h2>
          <ul>
              <li><a href="#">Talca</a></li>
              <li><a href="http://www.cftm.cl/">Linares</a></li>
          </ul>   
		  <h2><a href="../Carreras/">Carreras</a></h2>
		  <h1>Servicios</h1>
		  <ul>
		  <li><a href="../biblioteca/">Biblioteca</a></li>
		  <li><a href="../upload_arch/listador_archivos/">Centro de Descargas</a></li>
		  <li><a href="../ex_alumnos/">Ex alumno</a></li>
		  <li><a href="../Empresas/">Empresas</a></li>
		  </ul>
          <h2><a href="http://cftmass.cl/">HOME</a></h2>
          <br>

          
          
		  </td>
        </tr>
        <tr>
          <td valign="top"><img src="../IMG_store/menu_abajo.jpg" width="191" height="9"></td>
        </tr>
      </tbody></table>
	  </div>
	    <div align="center">
	      <hr width="85%" color="#cccccc" size="1px">
	      <div align="left">&nbsp;&nbsp;&nbsp;<a href="#"><img src="../IMG_store/you.jpg" width="37" border="0" height="35" title="Youtube" /></a>&nbsp;&nbsp;&nbsp;<a href="#"><img src="../IMG_store/face.jpg" width="37" border="0" height="37" title="Facebook"></a><br>
<br></div></div>
  </div>
	<div id="contenido">
	  <div align="center"> <br>
      
     
	    <br>
<!--Inicio biblioteca-->
<img src="../IMG_store/biblioteca_banner.jpg" alt="Biblioteca" width="525" height="46" />
<form action="listador_libros.php" method="post" enctype="multipart/form-data" name="frm" id="frm">
    <table width="100%" border="0">
	<tr>
	<td colspan="3" bgcolor="#CCFF99"> <div align="center" class="Estilo1">Seleccione </div></td>
	</tr>
      <tr> 
        <td><div align="left"><span class="Estilo2">Sede</span></div></td>
        <td colspan="2"> 
          <div align="left">
            <select name="sede">
              <option>Talca</option>
              <option>Linares</option>
              <option selected>Todas</option>
              </select>
          </div></td>
      </tr>
      <tr>
        <td><div align="left"><span class="Estilo2">Carrera</span></div></td>
        <td colspan="2"><div align="left">
          <select name="carrera" id="carrera" >
            <?php 
  include('../../funciones/conexion.php');
   
   
   $res="SELECT * FROM carrera where id >= 0 order by carrera";
   $result=mysql_query($res);
   while($row = mysql_fetch_array($result)) {


  $nomcar=$row["carrera"];
  
?>
            <option value="<?php echo $nomcar;?>">
              <?php 
  echo $nomcar;
}
mysql_free_result($result); 
mysql_close($conexion); 
 ?>
              </option>
          </select>
        </div></td>
      </tr>
      <tr> 
        <td><div align="left"><span class="Estilo2">Filtro</span></div></td>
        <td><div align="left">
          <input name="filtro" type="radio" id="radio" value="ninguno" checked>        
          Ninguno</div></td>
        <td><div align="left">
          <input type="radio" name="filtro" id="radio2" value="con_pdf">
          Solo Archivos</div></td>
      </tr>
      <tr> 
        <td><div align="left"><span class="Estilo2">Titulo</span></div></td>
        <td colspan="2">
		<div style="width:300px; float:left; left: 0px;" align="left">
<div align="left">
  <input name="searchbox" type="text" class="search" id="searchbox" value="Todos" autocomplete="off" onchange="xajax_BUSCAR_LIBRO(this.value);return false;"/>
  <br />
</div>
<div id="display"></div>
</div>        </td>
      </tr>
      <tr> 
        <td colspan="3"><div align="right">
          <input type="submit" name="Submit" value="Listar">
        </div></td>
      </tr>
    </table>
  </form>
<!--Fin biblioteca-->
<br>

<iframe src="../IMG_store/noticia_avanza.htm" width="100%" frameborder="0" height="40px" scrolling="no"></iframe>

	 </div>
	</div>
	<div id="derecha">
	  <div align="center"><a href="https://mail.google.com/a/cftmass.cl" title="Webmail"><img src="../IMG_store/btn_webmail.jpg" border="0" id="img_webmail" onmouseover="MM_swapImage('img_webmail','','../IMG_store/btn_webmail_press.jpg',1)" onmouseout="MM_swapImgRestore()"></a><br>
	  <br>
<a href="../consultas/" title="Contacto"><img src="../IMG_store/btn_contacto.jpg" name="img_contacto" width="191" height="62" border="0" id="img_contacto" onmouseover="MM_swapImage('img_contacto','','../IMG_store/btn_contacto_press.jpg',1)" onmouseout="MM_swapImgRestore()"></a><br>
          <br>
<a href="../Galeria/"><img src="../IMG_store/btn_galeria_2.jpg" name="img_galeria" width="191" height="60" border="0" id="img_galeria" onmouseover="MM_swapImage('img_galeria','','../IMG_store/btn_galeria_press.jpg',1)" onmouseout="MM_swapImgRestore()"></a><br>
          <br>
    <a href="../Alumnos/" title="Alumnos"><img src="../IMG_store/btn_alumnos.jpg" name="img_alumno" width="191" height="60" border="0" id="img_alumno" onmouseover="MM_swapImage('img_alumno','','../IMG_store/btn_alumnos_press.jpg',1)" onmouseout="MM_swapImgRestore()"></a><br>
    <br>
    <a href="../ex_alumnos/" title="EX-alumnos"><img src="../IMG_store/btn_exalumnos.jpg" alt="ex_alumnos" name="img_exalumnos" width="191" height="62" border="0" id="img_exalumnos" onmouseover="MM_swapImage('img_exalumnos','','../IMG_store/btn_exalumnos_press.jpg',1)" onmouseout="MM_swapImgRestore()"/></a><br>
    <br>
        <a href="../biblioteca/" title="Biblioteca"><img src="../IMG_store/btn_biblio.jpg" name="img_biblioteca" width="191" height="60" border="0" id="img_biblioteca" onmouseover="MM_swapImage('img_biblioteca','','../IMG_store/btn_biblio_press.jpg',1)" onmouseout="MM_swapImgRestore()"></a><br>
        <br>
        <a href="../Docentes/" title="Docente"><img src="../IMG_store/btn_docente.jpg" name="img_docente" width="191" height="60" border="0" id="img_docente" onmouseover="MM_swapImage('img_docente','','../IMG_store/btn_docente_press.jpg',1)" onmouseout="MM_swapImgRestore()"></a><br>
        <br>
        <a href="../Administrador/" target="_blank" title="Administrador"><img src="../IMG_store/btn_admin.jpg" name="img_admin" width="191" height="60" border="0" id="img_admin" onmouseover="MM_swapImage('img_admin','','../IMG_store/btn_admin_press.jpg',1)" onmouseout="MM_swapImgRestore()"><br>
      </a><br>
		 
          <div align="left"><br>
      </div></div>
  </div>	

<div id="pie">
<hr width="95%" color="#cccccc" size="1px">

<div align="center">
<h1>C.F.T Massachusetts <?php echo date("Y");?><br />
Talca | Linares</h1>
  
</div>
</div>
</div>
</body>
</html>