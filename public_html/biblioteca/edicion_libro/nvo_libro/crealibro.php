<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Bibioteca_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<html>
<head>
<title>Nuevo Libro</title>
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<script language="javascript" type="text/javascript">
function Confirmar()
{
	e=true;
	nombre=document.getElementById('nombres').value;
	autor=document.getElementById('autor').value;
	editorial=document.getElementById('editorial').value;
	if((nombre=="")||(nombre==" "))
	{
		alert('Ingrese el Nombre del Libro');
		e=false
	}
	if((autor=="")||(autor==" "))
	{
		alert('Ingrese el Autor del Libro');
		e=false
	}
	if((editorial=="")||(editorial==" "))
	{
		alert('Ingrese La Editorial del Libro');
		e=false
	}
	if(e)
	{
		c=confirm('¿Seguro(a) Desea Agregar este Libro?');
		if(c)
		{
		 	document.frm.submit();
		}	
	}

}
</script>
 
<style type="text/css">
<!--
body {
	background-color: #FFFFFF;
}
.Estilo1 {color: #3366CC}
a:link {
	text-decoration: none;
	color: #006699;
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
#Layer1 {
	position:absolute;
	width:200px;
	height:270px;
	z-index:42;
	left: 26px;
	top: 133px;
}
.Estilo2 {
	font-size: 18px;
	font-weight: bold;
}
#Layer2 {
	position:absolute;
	width:200px;
	height:33px;
	z-index:44;
	left: 215px;
	top: 108px;
}
.Estilo3 {color: #0080C0; }
.Estilo4 {
	font-size: 12px;
	font-weight: bold;
}
#link .Estilo3 {
	text-align: right;
}
-->
</style>
</head>

<body>
<h1 id="banner">Biblioteca - Ingreso de Libro</h1>
<div id="link">
  <div align="right"><br>
<a href="../../menu_biblioteca.php" class="button">Volver a Biblioteca</a></div>
</div>
<div id="Layer7" style="position:absolute; left:5%; top:128px; width:90%; height:326px; z-index:43;"> 
  <form action="grabarlibro.php" method="post" enctype="multipart/form-data" name="frm" id="frm">
    <table width="431" height="263" align="center">
    	<thead>
      <tr>
        <th height="23" colspan="3">Nuevo Libro</th>
      </tr>
      </thead>
      <tbody>
      <tr class="odd">
        <td width="77" height="36"><span class="Estilo4">Nombre:</span></td>
        <td colspan="2"><div align="left">
          <input type="text" name="nombres" id="nombres" size="30" maxlength="255">        
        </div></td>
      </tr>
      <tr class="odd">
        <td width="77" height="34"><span class="Estilo4">Autor:</span></td>
        <td colspan="2" height="34"><div align="left">
          <input type="text" name="autor" id="autor" size="30" maxlength="50">        
        </div></td>
      </tr>
      <tr class="odd">
        <td width="77" height="24"><span class="Estilo4">Editorial</span></td>
        <td colspan="2"><div align="left">
          <input type="text" name="editorial" id="editorial" maxlength="20" size="20">        
        </div></td>
      </tr>
      <tr class="odd">
        <td width="77" height="24"><span class="Estilo4">Carrera</span></td>
        <td colspan="2"><div align="left">
          <select name="carrera" id="carrera">
            <?php
   require("../../../../funciones/conexion_v2.php");   
   $res="SELECT * FROM carrera";
   $result=$conexion_mysqli->query($res);
   while($row = $result->fetch_assoc()) 
   {
	   $id_carrera=$row["id"];
  		$nomcar=$row["carrera"];
  		echo'<option value="'.$id_carrera.'_'.$nomcar.'">'.$id_carrera.'_'.$nomcar.'</option>';
	}
$result->free();
@mysql_close($conexion); 
$conexion_mysqli->close();
 ?>
          </select>
        </div></td>
      </tr>
      <tr class="odd">
        <td width="77" height="24"><span class="Estilo4">A&ntilde;o</span></td>
        <td colspan="2"><div align="left">
          <input type="text" name="ano" size="5" maxlength="4">
        </div></td>
      </tr>
      <tr class="odd">
        <td width="77" height="24"><span class="Estilo4">Estado</span></td>
        <td colspan="2"><div align="left">
          <select name="estado">
            <option selected>Bueno</option>
            <option>Regular</option>
            <option>Malo</option>
          </select>
        </div></td>
      </tr>
      <tr class="odd">
        <td width="77" height="24"><span class="Estilo4">Sede</span></td>
        <td colspan="2"><div align="left">
                <?php
	  include("../../../../funciones/funcion.php");
	  echo selector_sede("sede"); 
	  ?>
        </div></td>
      </tr>
      <tr class="odd">
        <td height="28"  colspan="3"><div align="center" id="bton">&nbsp;&nbsp;
          <input type="reset" name="Submit" value="borrar">
          &nbsp;
          <input type="button" name="accion" value="Grabar" onClick="Confirmar();">
        </div></td>
      </tr>
 </tbody>
    </table>
  </form>
</div>
</body>
</html>