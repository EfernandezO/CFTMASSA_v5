<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>cta. cte.</title>
<style type="text/css" title="currentStyle">
@import "../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/css/demo_table.css";
@import "../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/examples/examples_support/themes/smoothness/jquery-ui-1.8.4.custom.css";
</style>

<script type="text/javascript" language="javascript" src="../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="ISO-8859-1">
			$(document).ready(function() {
				oTable = $('#example').dataTable({
					"bJQueryUI": true
				});
			} );
		</script>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:60%;
	height:29px;
	z-index:1;
	left: 20%;
	top: 121px;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:104px;
	z-index:2;
	left: 5%;
	top: 71px;
}
#apDiv3 {
	position:absolute;
	width:146px;
	height:20px;
	z-index:1;
	left: 758px;
	top: 72px;
}
-->
</style>
<script language="javascript" type="text/javascript">
function nueva_cuenta()
{
	window.location="nueva_cta/nva_ctacte.php";
}
///////////////////////////////////
function elimina_cta(id)
{
	r=confirm('¿Seguro desea Eliminar esta Cuenta?');
	if(r)
	{
		window.location="del_cta/del_cuenta.php?id="+id;
	}
	
}
</script>
</head>

<body>
<h1 id="banner">Cta. Cte</h1> 
<div id="link"><br />
<a href="../index.php" class="button">Volver a Menu</a><br /><br />
<a href="#" onclick="nueva_cuenta();" class="button">Crear Nueva Cta. Cte. </a></div>
<div class="demo_jui" id="apDiv1">
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="display" id="example">
	<thead>
		<tr>	
      <th >ID</th>
      <th>Banco</th>
      <th >Titular</th>
      <th>Cta.Cte.</th>
      <th colspan="2">Opcion</th>
		</tr>
	</thead>
	<tbody>
    <?php
		require("../../../funciones/conexion_v2.php");
		$cons="SELECT * FROM cuenta_corriente ORDER BY id";//saco
		$sql=mysql_query($cons)or die(mysql_error());
		$num_reg=mysql_num_rows($sql);
		if($num_reg>0)
		 {
		 	while($U=mysql_fetch_assoc($sql))
			{
				$id=$U["id"];
				$banco=$U["banco"];
				$titular=$U["titular"];
				$num_cuenta=$U["num_cuenta"];
				echo'<tr>
					<td>'.$id.'</td>
					<td>'.$banco.'</td>
					<td>'.$titular.'</td>
					<td>'.$num_cuenta.'</td>
					<td align="right">
					<a href="edit_cta/edita_cuenta.php?id='.$id.'" >
					<img src="../../BAses/Images/b_edit.png" alt="Editar" border="0" title="Editar"/></a>
					</td>
					<td>
					<a href="#" onclick="elimina_cta(\''.$id.'\')">
					<img src="../../BAses/Images/b_drop.png" alt="Eliminar" border="0" title="Eliminar"/></a>	
					</td>
					</tr>';
				
			}
			mysql_free_result($sql);
		 }
		else
		 {
		 	echo'<tr><td colspan="5">Cuenta Corriente NO Registradas...</td></tr>';
		 } 
		 mysql_close($conexion);
	?>
</tbody>
  </table>
  
  <?php
  if(isset($_GET["error"]))
  {
  	$error=$_GET["error"];
	switch($error)
	{
		case"0":
			$msj="Cuenta Agregada Exitosamente";
			break;
		case"1":
			$msj="la Cueta ya existe ya Existe ";
			break;	
		case"2":
			$msj="Fallo en consulta, por favor intentelo mas tarde";
			break;
		case"3":
			$msj="Cuenta Eliminada Correctamente";
			break;
		case"4":
			$msj="Fallo en consulta, por favor intentelo mas tarde <strong>$msj_G</strong>";
			break;			
		case"5":
			$msj="Codigo de Cuenta Invalido";
			break;	
		case"6":	
			$msj="Cuenta Modificada.";
			break;
	}
  }
  else
  {
	  $msj="";
  }
  ?>
  <div id="msj_error"><?php echo $msj;?></div>
</div>
</body>
</html>