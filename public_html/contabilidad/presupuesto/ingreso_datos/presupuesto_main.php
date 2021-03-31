<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="inspeccion";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define("DEBUG", false);
$_SESSION["PRESUPUESTO"]["verificador"]=true;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<title>Presupuesto</title>
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
	border: thin solid #006699;
	width: 30%;
	padding-bottom: 0px;
	margin-bottom: 30px;
	margin-left: 50px;
}
    #apDiv1 {
	position:absolute;
	width:792px;
	height:115px;
	z-index:1;
	left: 50px;
	top: 159px;
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
	c=confirm('Seguro(a) Desea Elimiar este Registro...?');
	if(c)
	{
		window.location=url;
	}
}
</script>       
</head>
<body>
<h1 id="banner">Presupuesto</h1>
   
	<div id="link"><a href="presupuesto_seleccion.php">Volver a Seleccion</a></div>
<div id="container">
		<div class="full_width big"></div>
			
	  <div class="demo_jui">
<?php 
if($_POST)
{
	if(DEBUG){echo"DATOS-> POST<br>";}
   $sede=$_POST["sede"];
   $fecha_presupuesto=$_POST["fecha_presupuesto"];
   $_SESSION["PRESUPUESTO"]["sede"]=$sede;
   $_SESSION["PRESUPUESTO"]["fecha"]=$fecha_presupuesto;
}
else
{
	if(DEBUG){echo"DATOS-> SESSION<br>";}
	$sede=$_SESSION["PRESUPUESTO"]["sede"];
	$fecha_presupuesto=$_SESSION["PRESUPUESTO"]["fecha"];
}

   include('../../../../funciones/conexion.php');
   include('../../../../funciones/funcion.php');
   if(DEBUG){ var_export($_POST);}
   
 $cons_P="SELECT * FROM presupuesto WHERE sede='$sede' AND fecha='$fecha_presupuesto'";
 if(DEBUG){ echo"<br>$cons_P<br>";}
?>
<div id="msj">
  <p><strong>Sede:</strong> <?php echo $sede;?><br>
      <strong>Fecha:</strong> <?php echo fecha_format($fecha_presupuesto);?></p>
  </div>
  <div id="apDiv1">
    <table width="80%" class="display" id="example" border="0">
      <thead>
        <tr>
          <th width="8%">N&deg;</th>
          <th width="8%">ID</th>
          <th width="13%">Item</th>
          <th width="12%">Movimiento</th>
          <th width="34%">Glosa</th>
          <th width="15%">Valor</th>
          <th width="10%" colspan="2">Opcion</th>
        </tr>
      </thead>
      <tbody>
        <?php   
 $sql_P=mysql_query($cons_P)or die(mysql_error());
 $num_reg=mysql_num_rows($sql_P);
 $contador=0;
 while($P=mysql_fetch_assoc($sql_P))
  {
 	$id_presupuesto=$P["id"];
	$movimiento=$P["movimiento"];
	$item=$P["item"];
	/////////////////////////////
	$cons_I="SELECT nombre FROM presupuesto_parametros WHERE codigo='$item'";
	$sql_I=mysql_query($cons_I)or die("nombre_item".mysql_error());
	$D_Ix=mysql_fetch_assoc($sql_I);
	$nombre_item=$D_Ix["nombre"];
	mysql_free_result($sql_I);
	////////////////////////////
	
	$valor=$P["valor"];
	$fecha=$P["fecha"];
	$glosa=$P["glosa"];
	$sede=$P["sede"];
	$fecha_generacion=$P["fecha_generacion"];
	$cod_user=$P["cod_user"];
	
	$contador++;
	$Total+=$valor;
	
	echo'<tr>
			<td><div align="center">'.$contador.'</div></td>
			<td><div align="center">'.$id_presupuesto.'</div></td>
			<td><div align="center"><a href="#" title="'.$item.'">'.$nombre_item.'</a></div></td>
			<td><div align="center">'.$movimiento.'</div></td>
			<td><div align="center">'.$glosa.'</div></td>
			<td><div align="center">'.number_format($valor,0,",",".").'</div></td>';
			
			$url="borra_registro/borra_registro.php?ID=".base64_encode($id_presupuesto);
			echo'
			<td><div align="center"><a href="#" title="Eliminar" onclick="CONFIRMAR_B(\''.$url.'\')"><img src="../../../BAses/Images/b_drop.png" alt="[-]"></a></div></td>
		    <td><div align="center"><a href="edita_registro/edita_registro_presupuesto.php?id_presupuesto='.base64_encode($id_presupuesto).'" title="Editar"><img src="../../../BAses/Images/b_edit.png" width="16" height="16"></a></div></td>
        </tr>';
	
   }
   mysql_free_result($sql_P); 
   mysql_close($conexion); 
?>
      </tbody>
      <tfoot>
       <tr>
        	<td colspan="5"><strong>Total</strong></td>
            <td><div align="center"><strong><?php echo "$".number_format($Total,0,",",".");?></strong></div>            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="8"><div align="right"><a href="nuevo_registro/nuevo_registro_presupuesto.php">Agregar Nuevo Registro Al Presupuesto<img src="../../../BAses/Images/add.png" alt="[+]" width="32" height="31"></a></div></td>
        </tr>
      </tfoot>
    </table>
    
    <div id="error">
      <div align="center"><em><strong>*
          <?php
	$error=$_GET["error"];
	$img_ok='<img src="../../../BAses/Images/ok.png">';
	$img_error='<img src="../../../BAses/Images/b_drop.png">';
	switch($error)
	{
		case"0":
			$msjX="Registro Agregado...";
			$img=$img_ok;
			break;
		case"1":
			$msjX="Fallo Al Intentar Registrar";
			$img=$img_error;	
			break;
		case"2":
			$msjX="Registro Eliminado";
			$img=$img_ok;	
			break;
		case"3":
			$msjX="Fallo Al Intentar Eliminar Registrar";
			$img=$img_error;	
			break;	
		case"4":
			$msjX="Registro Modificado";
			$img=$img_ok;	
			break;
		case"5":
			$msjX="Fallo Al Intentar Modificar el Registrar";
			$img=$img_error;	
			break;			
	}
	echo "$msjX $img";
    ?>
        *      </strong></em></div>
    </div>
  </div>
  </div>
  <div class="spacer"></div>
</div>
</body>
</html>