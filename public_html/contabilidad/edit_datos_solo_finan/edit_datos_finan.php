<?php require("../../SC/seguridad.php");?>
<?php require("../../SC/privilegio2.php");?>
<html>
<head>
<title>Modifica Datos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	background-color: #FFFFFF;
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
#link {
	text-align: right;
	padding-right: 10px;
}
-->
</style>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<style type="text/css">
<!--
.Estilo1 {
	font-size: 12px;
	font-weight: bold;
}
-->
</style>
</head>
<body>
<h1 id="banner">Administrador - Finanzas </h1>
<div id="link"><a href="../index.php">Volver al Menu</a></div>
<h3>Edicion de datos</h3>
<div id="Layer7" style="position:absolute; left:72px; top:101px; width:462px; height:274px; z-index:1; overflow: hidden; visibility: visible"> 
  <form action="grabar_datos_finan.php" method="post" enctype="multipart/form-data" name="frm" id="frm">
<?php
  
    include("../../../funciones/conexion.php");
   
   $rutb=$_SESSION["ADrut"];
   $claveb=$_SESSION["ADclave"];

   $res="SELECT * FROM personal where rut = '$rutb'and clave = '$claveb'";
   $result=mysql_query($res);
   while($row = mysql_fetch_array($result)) 
   {
    $id=$row["id"];
    $rut=$row["rut"];
    $nombre=$row["nombre"];
	$apellido=$row["apellido"];
	
    $email=$row["email"];
    $fono=$row["fono"];
	$direccion=$row["direccion"];
	
    $ciudad=$row["ciudad"];
    
    $clave=$row["clave"];
   }
?> 
    <table width="364">
      <tr> 
        <td><span class="Estilo1">Nombres:</span></td>
        <td> 
          <input type="text" name="nombres" size="30" maxlength="50" value="<?php echo $nombre;?>">
          <input type="hidden" name="id" value="<?php echo $id;?>">        </td>
      </tr>
      <tr> 
        <td><span class="Estilo1">Apellidos:</span></td>
        <td> 
          <input type="text" name="apellidos" size="30" maxlength="50" value="<?php echo $apellido;?>">        </td>
      </tr>
      <tr> 
        <td><span class="Estilo1">Rut</span></td>
        <td> 
         <?php echo $rut;?>        </td>
      </tr>
      <tr> 
        <td><span class="Estilo1">Clave</span></td>
        <td> 
          <input type="text" name="clave" size="10" maxlength="10" value="<?php echo $clave;?>">        </td>
      </tr>
      <tr> 
        <td><span class="Estilo1">Fono</span></td>
        <td> 
          <input type="text" name="fono" size="25" maxlength="25" value="<?php echo $fono;?>">        </td>
      </tr>
      <tr> 
        <td><span class="Estilo1">Direccion</span></td>
        <td> 
          <input type="text" name="direccion" size="50" maxlength="50" value="<?php echo $direccion;?>">        </td>
      </tr>
      <tr> 
        <td><span class="Estilo1">Ciudad</span></td>
        <td><?php echo $ciudad; ?>
          <input name="ciudad" type="hidden" id="ciudad" value="<?php echo $ciudad;?>"></td>
      </tr>
      <tr> 
        <td><span class="Estilo1">Email</span></td>
        <td> 
          <input type="text" name="correo" size="50" maxlength="50" value="<?php echo $email;?>">        </td>
      </tr>
    </table>
    <input type="submit" name="accion" value="Grabar">
    <input type="reset" name="Submit" value="Restablecer">
    
  </form>
</div>
</body>
</html>
