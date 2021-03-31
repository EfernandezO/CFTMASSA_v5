<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Impresion_Y_Gestion_de_contratos_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Agrega Cuota</title>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">

<script src="../../../../libreria_publica/JSCal/src/js/jscal2.js"></script>
<script src="../../../../libreria_publica/JSCal/src/js/lang/es.js"></script>
 
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/steel/steel.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css"/>

<style>
#link {
	text-align: right;
	padding-right: 10px;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 151px;
}
-->
</style>
<script language="javascript">
function CONFIRMAR()
{
	continuar=true;
	fecha_vence=document.getElementById('fecha_vence').value;
	valor=document.getElementById('valor_cuota').value;
	deuda=document.getElementById('deudaXcuota').value;
	
	if(fecha_vence=="")
	{
		continuar=false;
		alert('Ingrese Fecha de Vencimiento');
	}
	
	if(valor=="")
	{
		continuar=false;
		alert('Ingrese Valor Cuota');
	}
	if(deuda=="")
	{
		continuar=false;
		alert('ingrese Deuda Cuota');
	}
	
	if(continuar)
	{
		c=confirm("¿Seguro(a) Desea Agregar esta Cuota ?");
		if(c)
		{
			document.frm.submit();
		}
	}	
}
</script>
</head>
<?php
	require("../../../../../funciones/funciones_sistema.php");
	$sedeAlumno=$_SESSION["SELECTOR_ALUMNO"]["sede"];
	$id_alumno=base64_decode($_GET["id_alumno"]);
	$id_contrato=base64_decode($_GET["id_contrato"]);
	$year=base64_decode($_GET["year"]);
	$semestre=$_GET["semestre"];
	
	$array_tipo=array("cuota", "matricula", "examen");
	$array_condicion=array("N"=>"pendiente", "S"=>"pagada", "A"=>"abonada");
?>
<body>
<h1 id="banner">Agregar -Cuota</h1>
<div id="link"><br />
<a href="../../index.php" class="button">Volver</a></div>
<h3>Agregando Cuota a Alumno...</h3>
<div id="apDiv2">
  <form action="agrega_rec.php" method="post" name="frm" id="frm">
  <table width="60%" border="0" align="center">
  <thead>
    <tr>
      <th colspan="2"><strong>Agrega Cuota
        <input name="id_alumno" type="hidden" id="id_alumno" value="<?php echo $id_alumno;?>" />
        <input type="hidden" name="id_contrato" id="id_contrato"  value="<?php echo $id_contrato;?>"/>
        <input name="year" type="hidden" id="year" value="<?php echo $year;?>" />
        <input type="hidden" name="semestre" id="semestre"  value="<?php echo $semestre;?>"/>
      </strong></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="36%"><em>fecha vence</em></td>
      <td width="64%"><input name="fecha_vence" type="text" id="fecha_vence" size="15" readonly="readonly"/ value="<?php echo date("Y-m-d");?>">
        <input type="button" name="boton" id="boton" value="..." /></td>
    </tr>
    <tr>
      <td><em>Valor</em></td>
      <td><input name="valor_cuota" type="text" id="valor_cuota"  size="15" /></td>
    </tr>
    <tr>
      <td><em>Deuda X cuota</em></td>
      <td><input name="deudaXcuota" type="text" id="deudaXcuota"  size="15" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="validador" type="hidden" id="validador" value="<?php echo md5("AGREGA_cuota".date("Y-m-d"));?>" /></td>
    </tr>
    <tr>
      <td><em>A&ntilde;o</em></td>
      <td><?php echo CAMPO_SELECCION("year","year",$year,false);?></td>
    </tr>
    <tr>
      <td><em>Semestre</em></td>
      <td><?php echo CAMPO_SELECCION("semestre","semestre",$semestre,false);?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><em>Anulada</em></td>
      <td><select name="select" id="select">
        <option value="N" selected="selected">N</option>
        <option value="S">S</option>
      </select>      </td>
    </tr>
    <tr>
      <td bgcolor="#f5f5f5"><em>Condicion</em></td>
      <td bgcolor="#f5f5f5"><select name="pagada" id="pagada">
        <?php
      foreach($array_condicion as $nx=>$valorxx)
	  {echo'<option value="'.$nx.'">'.$valorxx.'</option>';}
	  ?>
      </select></td>
    </tr>
    <tr>
      <td bgcolor="#f5f5f5"><em>Sede</em></td>
      <td bgcolor="#f5f5f5">
	  <?php echo CAMPO_SELECCION("sede","sede",$sedeAlumno);?>
      </td>
    </tr>
    <tr>
      <td bgcolor="#f5f5f5"><em>Tipo</em></td>
      <td bgcolor="#f5f5f5"><select name="tipo_cuota" id="tipo_cuota">
        <?php
        	foreach($array_tipo as $n => $valor)
			{echo'<option value="'.$valor.'">'.$valor.'</option>';}
		?>
      </select></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center">
        <input type="button" name="button" id="button" value="Agregar"  onclick="CONFIRMAR();"/>
      </div></td>
    </tr>
    </tbody>
  </table>
  </form>
</div>
<script type="text/javascript">//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton", "fecha_vence", "%Y-%m-%d");
    //]]></script>
</body>
</html>
