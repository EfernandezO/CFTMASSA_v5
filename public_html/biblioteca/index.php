<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
//////////////////////XAJAX/////////////////
@require_once ("../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("search_server.php");
$xajax->configure('javascript URI','../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCAR_LIBRO");
////////////////////////////////////////////
?>
<?php include("../../funciones/codificacion.php");?>
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="../IMG_store/emov.css">
<link rel="stylesheet" type="text/css" href="../CSS/tabla_2.css"/>
<title>CFT Massachusetts - Biblioteca</title>

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
#apDiv2 {
	position:absolute;
	width:40%;
	height:21px;
	z-index:1;
	left: 30%;
	top: 338px;
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
<body>
<!--Inicio biblioteca-->
<img src="../IMG_store/biblioteca_banner.jpg" alt="Biblioteca" width="525" height="46" />
<form action="listador_libros.php" method="post" enctype="multipart/form-data" name="frm" id="frm">
    <table width="80%" border="0" align="center">
    <thead>
	<tr>
	<th colspan="3" > <div align="center" class="Estilo1">Seleccione </div></th>
	</tr>
    </thead>
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
  require('../../funciones/conexion_v2.php');
   
   
   $res="SELECT * FROM carrera where id >= 0 order by carrera";
   $result=$conexion_mysqli->query($res);
   while($row = $result->fetch_assoc()) 
   {

	  $nomcar=$row["carrera"];
	  $id_carrera=$row["id"];
		echo'<option value="'.$id_carrera.'_'.$nomcar.'">'.$nomcar.'</option>';
   }

$result->free(); 

$conexion_mysqli->close();
 ?>
              </option>
          </select>
        </div></td>
      </tr>
      <tr> 
        <td><div align="left"><span class="Estilo2">Filtro</span></div></td>
        <td><div align="left">
          <input name="filtro" type="radio" id="radio" value="ninguno" checked>        
          Ninguno
          <div id="apDiv2"><a href="Http://www.cftmass.cl">www.cftmass.cl</a></div>
        </div></td>
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
</body>
</html>