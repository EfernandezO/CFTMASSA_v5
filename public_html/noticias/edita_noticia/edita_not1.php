<?php require("../../SC/seguridad.php");?>
<?php require("../../SC/privilegio.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<title>Edicion de Noticias</title>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:90%;
	height:67px;
	z-index:1;
	left: 5%;
	top: 113px;
}
#Layer2 {
	position:absolute;
	width:174px;
	height:24px;
	z-index:2;
	left: 329px;
	top: 72px;
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
.Estilo1 {color: #0080C0}
-->
</style>
</head>

<body>
<h1 id="banner">Edici&oacute;n  - Noticia</h1>
<div id="link"><a href="../menu_noticias.php">Volver al Menu</a></div>
<div id="Layer1">
  <div align="center">
    <table width="70%" border="1">
      <thead>
        <tr>
          <th><div align="center"><strong>Fecha</strong></div></th>
          <th><div align="center"><strong>Autor</strong></div></th>
          <th><div align="center"><strong>Titulo</strong></div></th>
          <th colspan="2"><div align="center"><strong>Opcion</strong></div></th>
        </tr>
      </thead>
      <tbody>
        <?php
	include("../../../funciones/conexion.php");
	include("../../../funciones/funcion.php");
	
	$cons="SELECT * FROM noticias ORDER BY idn desc";
	$sql=mysql_query($cons);
	$numero_not=mysql_num_rows($sql);
	$aux=0;
	while($X=mysql_fetch_array($sql))
	{
		$idn=$X["idn"];
		$fecha=$X["fecha"];
		$autor=$X["autor"];
		$titulo=$X["titulo"];
		
		echo'<tr>
      <td><div align="center">'.fecha_format($fecha).'</div></td>
      <td><div align="center">'.$autor.'</div></td>
      <td><div align="center">'.$titulo.'</div></td>
      <td><form action="borra_not.php" method="post" name="frmX" id="frmX">
        <label>
          <div align="center">
            <input type="submit" name="Submit2" value="Eliminar" />
            <input name="ocu_id" type="hidden" id="ocu_id" value="'.$idn.'" />
          </div>
        </label>
      </form></td>
      <td><a href="../modificar_noticia.php/modifica_noticia_1.php?idN='.base64_encode($idn).'"><img src="../../BAses/Images/b_edit.png" alt="editar" width="16" height="16" /></a></td>
      </tr>';
	$aux++;
	}

?>
        <tr>
          <td><strong>Total:</strong></td>
          <td colspan="4"><?php echo $numero_not;?></td>
        </tr>
      </tbody>
    </table>
    <?php
    if($_GET)
	{
		$error=$_GET["error"];
		$img_error='<img src="../../BAses/Images/b_drop.png" />';
		$img_ok='<img src="../../BAses/Images/ok.png" />';
		
		switch($error)
		{
			case"0":
				$img=$img_ok;
				$msj="Noticia Actualizada...";
				break;
		}
	}
	?>
    <?php echo "$msj $img";?>
  </div>
</div>
</body>
</html>
