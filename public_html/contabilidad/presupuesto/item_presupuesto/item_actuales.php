<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="inspeccion";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define("DEBUG", false);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<title>Item</title>
	<style type="text/css" title="currentStyle">
			@import "../../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/css/demo_table_jui.css";
			@import "../../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/examples/examples_support/themes/smoothness/jquery-ui-1.8.4.custom.css";
		a:link {
	color: #006699;
	text-decoration: none;
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
#container .demo_jui #msj {
}
    #apDiv1 {
	position:absolute;
	width:677px;
	height:115px;
	z-index:1;
	left: 102px;
	top: 119px;
}
    </style>
<script type="text/javascript" language="javascript" src="../../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="iso-8859-1">
			$(document).ready(function() {
				oTable = $('#example').dataTable({
					"bJQueryUI": true,
					"sPaginationType": "full_numbers"
				});
			} );
		</script>
<script language="javascript">        
function CONFIRMAR_B(url)
{
	c=confirm('Seguro(a) Desea Elimiar este ITEM...?');
	if(c)
	{
		window.location=url;
	}
}
</script>
</head>
<body>
<h1 id="banner">ITEM Actualmente Disponibles</h1>
   
	<div id="link"><a href="../menu_presupuesto.php">Volver al menu</a></div>
<div id="container">
		<div class="full_width big"></div>
			
	  <div class="demo_jui">
<?php 
   include('../../../../funciones/conexion.php');
   include('../../../../funciones/funcion.php');
   
 $cons_P="SELECT * FROM presupuesto_parametros order by sede, codigo";
 if(DEBUG){ echo"<br>$cons_P<br>";}
?>
<div id="msj">
  <p>&nbsp;</p>
  <div id="apDiv1">
  Administraci&oacute;n para ITEM 
    <table width="80%" class="display" id="example" border="0">
      <thead>
        <tr>
          <th>N&deg;</th>
           <th>Sede</th>
          <th>Codigo</th>
          <th>nombre</th>
          <th>Movimiento</th>
          <th colspan="2">Opcion</th>
        </tr>
      </thead>
      <tbody>
        <?php   
 $sql_P=mysql_query($cons_P)or die(mysql_error());
 $num_reg=mysql_num_rows($sql_P);
 $contador=0;
 while($P=mysql_fetch_assoc($sql_P))
  {
 	$id_item=$P["id"];
	$codigo=$P["codigo"];
	$nombre=$P["nombre"];
	$descripcion=$P["descripcion"];
	$movimiento=$P["movimiento"];
	$sede=$P["sede"];
	$estado=$P["estado"];
	
	$contador++;
	echo'<tr>
			<td><div align="center">'.$contador.'</div></td>
			<td><div align="center">'.$sede.'</div></td>
			<td><div align="center">'.$codigo.'</div></td>
			<td><div align="center">'.$nombre.'</div></td>
			<td><div align="center">'.$movimiento.'</div></td>';
			
			$url="borra_item/borra_item.php?ID=".base64_encode($id_item);
			echo'
			<td><div align="center"><a href="#" title="Eliminar" onclick="CONFIRMAR_B(\''.$url.'\')"><img src="../../../BAses/Images/b_drop.png" alt="[-]"></a></div></td>
		    <td><div align="center"><a href="edicion_item/edita_item.php?ID='.base64_encode($id_item).'" title="Editar"><img src="../../../BAses/Images/b_edit.png" width="16" height="16"></a></div></td>
        </tr>';
	
   }
   mysql_free_result($sql_P); 
   mysql_close($conexion); 
?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="8"><div align="right"><a href="nuevo_item/nuevo_item.php">Agregar Nuevo Item<img src="../../../BAses/Images/add.png" alt="[+]" width="32" height="31"></a></div></td>
        </tr>
      </tfoot>
    </table>
    
    <div id="error">
    <?php
		$error=$_GET["error"];
		$img_ok='<img src="../../../BAses/Images/ok.png">';
		$img_error='<img src="../../../BAses/Images/b_drop.png">';
		switch($error)
		{
			case"0":
				$msjX="Item Agregado Correctamente";
				$img=$img_ok;
				break;
			case"1":
				$msjX="Fallo Al Intentar Agregar Item";
				$img=$img_error;
				break;
			case"2":
				$msjX="Item Eliminado";
				$img=$img_ok;
				break;
			case"3":
				$msjX="Fallo Al intentar Eliminar el Item";
				$img=$img_error;
				break;	
			case"4":
				$msjX="Item Modificado";
				$img=$img_ok;
				break;	
			case"5":
				$msjX="Fallo Al intentar Modificar el Item";
				$img=$img_error;
				break;					
		}
    ?>
      <div align="center"><em><strong>*<?php echo"$msjX $img";?>*</strong></em></div>
    </div>
  </div>
</div>
      </div>
	  <div class="spacer"></div>
</div>
</body>
</html>