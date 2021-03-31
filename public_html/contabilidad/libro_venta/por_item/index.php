<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="inspeccion";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
//-----------------------------------------//	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Libro de Venta</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:129px;
	z-index:1;
	left: 5%;
	top: 96px;
}
.Estilo1 {	font-size: 12px;
	font-style: italic;
}
.Estilo2 {font-size: 12px}
.Estilo3 {	font-size: 12px;
	font-weight: bold;
}
#link {	text-align: right;
	padding-right: 10px;
}
-->
</style>
</head>
<?php
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	switch($privilegio)
	{
		case"inspeccion":
			$url="../../../Administrador/menu_inspeccion/index.php";
			break;
		default:
			$url="../../index.php";
	}
?>
<body>
<h1 id="banner">Administrador - Finanzas Libro de Venta X Item</h1>
<div id="link"><br />
<a href="<?php echo $url;?>" class="button">Volver al Menu</a></div>
<div id="apDiv1">
  <form action="por_item.php" method="post" name="frm" id="frm">
    <table width="50%" border="0" align="center">
      <thead>
        <tr>
          <th colspan="3"><span class="Estilo3">Parametros</span></th>
        </tr>
      </thead>
      <tbody>
        <tr class="odd">
          <td width="39%"><span class="Estilo1">Mes</span></td>
          <td width="61" colspan="2"><label for="mes"></label>
            <select name="mes" id="mes">
              <option value="01">Enero</option>
              <option value="02">Febrero</option>
              <option value="03">Marzo</option>
              <option value="04">Abril</option>
              <option value="05">Mayo</option>
              <option value="06">Junio</option>
              <option value="07">Julio</option>
              <option value="08">Agosto</option>
              <option value="09">Septiembre</option>
              <option value="10">Octubre</option>
              <option value="11">Noviembre</option>
              <option value="12">Diciembre</option>
            
          </select></td>
        </tr>
        <tr class="odd">
          <td><span class="Estilo1">A&ntilde;o</span></td>
          <td colspan="2"><label for="year"></label>
            <select name="year" id="year">
            <?php
            	$year_actual=date("Y");
				$year_inicio=2011;
				$year_final=$year_actual+1;
				
				for($y=$year_inicio;$y<=$year_final;$y++)
				{
					if($y==$year_actual)
					{ echo'<option value="'.$y.'" selected="selected">'.$y.'</option>';}
					else
					{ echo'<option value="'.$y.'">'.$y.'</option>';}
					
				}
			?>
          </select></td>
        </tr>
        <tr class="odd">
          <td height="22"><span class="Estilo2">Sede</span></td>
          <td colspan="2"><?php
	  include("../../../../funciones/funcion.php");
	  echo selector_sede(); 
	  ?></td>
        </tr>
        <tr class="odd">
          <td height="22">&nbsp;</td>
          <td colspan="2">&nbsp;</td>
        </tr>
     
        <tr>
          <td colspan="3"><div align="right">
            <input type="submit" name="button" id="button" value="Consultar" />
          </div></td>
        </tr>
         </tbody>
    </table>
  </form>
</div>
<?php
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	switch($privilegio)
	{
		case"inspeccion":
			$url="../../../Administrador/menu_inspeccion/index.php";
			break;
		default:
			$url="../../index.php";	
	}
?>
</body>
</html>