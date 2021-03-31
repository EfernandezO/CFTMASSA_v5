<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
//-----------------------------------------//	
?>
<html>
<head>
<title>Modifica Datos</title>
<?php include("../../../../funciones/codificacion_2.php");?>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
 <link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<style type="text/css">
<!--
body {
	background-color: #FFFFFF;
}
.Estilo2 {color: #0080C0; }
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
.Estilo3 {
	font-size: 12px;
	font-weight: bold;
}
-->
</style>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 103px;
}
#link {
	text-align: right;
	padding-right: 10px;
}
.Estilo4 {
	font-size: 12px
}
#apDiv2 {
	position:absolute;
	width:46%;
	height:115px;
	z-index:2;
	left: 52%;
	top: 103px;
}
#apDiv3 {
	position:absolute;
	width:40%;
	height:30px;
	z-index:3;
	left: 30%;
	top: 545px;
	text-align: center;
}
#apDiv4 {
	position:absolute;
	width:49%;
	height:115px;
	z-index:4;
	left: 2%;
	top: 466px;
}
-->
</style>
<script language="javascript">
function CONFIRMAR()
{
	c=confirm('¿Seguro(a) Desea Actualizar sus Datos?');
	if(c){document.getElementById('frm').submit();}
}
</script>
</head>
<body>
<h1 id="banner">Docentes - Mis Datos </h1>

 <div id="link"><br>
<a href="../../okdocente.php" class="button">Volver al Menu</a></div>
 

<form action="grabardocenteactx.php" method="post" enctype="multipart/form-data" name="frm" id="frm">
<?php
    require("../../../../funciones/conexion_v2.php");
   /////////////////////////////////////////
  	$id=$_SESSION["USUARIO"]["id"];
  	$array_tipo_institucion=array("U"=>"Universidad","IP"=>"Intituto Profesional","CFT"=>"Centro Formacion Tecnica");
	$array_grado_academico=array("Licenciado","Magister","Doctor");
	$array_sexo=array("M"=>"Masculino", "F"=>"Femenino");
	//////////////////////////////////////////
   $res="SELECT * FROM personal where id='$id' LIMIT 1";
   $result=$conexion_mysqli->query($res);
   $row = $result->fetch_assoc();
   
    $id=$row["id"];
    $rut=$row["rut"];
    $nombre=$row["nombre"];
	$apellido_P=$row["apellido_P"];
	$apellido_M=$row["apellido_M"];
	
    $email_personal=$row["email_personal"];
	$email=$row["email"];
    $fono=$row["fono"];
	$direccion=$row["direccion"];
    $ciudad=$row["ciudad"];
    $clave=$row["clave"];
	/////////////////////////////
	$sexo=$row["sexo"];
	$fecha_nacimiento=$row["fecha_nacimiento"];
	$array_fecha=explode("-",$fecha_nacimiento);
	$year=$array_fecha[0];
	$mes=$array_fecha[1];
	$dia=$array_fecha[2];
	/////////////////
	//var_export($row);
	$result->free();
?> 
 <div id="apDiv1">
        <table width="60%" align="center">
        <thead>
        <tr>
        	<th colspan="2">Datos Personales</th>
        </tr>
        <tr >
          <td>Rut</td>
          <td><?php echo $rut;?></td>
        </tr>
        </thead>
	<tbody>
      <tr > 
        <td width="35%"><span class="Estilo3">Nombres:</span></td>
        <td width="65%"> 
          <input type="text" name="nombres" size="30" maxlength="50" value="<?php echo $nombre;?>">
          <input type="hidden" name="id" value="<?php echo $id;?>">        </td>
      </tr>
      <tr > 
        <td><span class="Estilo3">Apellido P:</span></td>
        <td> 
          <input type="text" name="apellido_P" size="30" maxlength="50" value="<?php echo $apellido_P;?>">        </td>
      </tr>
      <tr >
        <td class="Estilo3">Apellido M</td>
        <td><input type="text" name="apellido_M" size="30" maxlength="50" value="<?php echo $apellido_M;?>"></td>
      </tr>
      <tr >
        <td class="Estilo3">Fecha Nacimiento</td>
        <td><select name="fn_dia" id="fn_dia">
            <?php
            for($d=1;$d<=31;$d++)
			{
				if($d<10){ $d_label="0".$d;}
				else{ $d_label=$d;}
				
				if($dia==$d)
				{echo'<option value="'.$d_label.'" selected="selected">'.$d_label.'</option>';}
				else
				{ echo'<option value="'.$d_label.'" >'.$d_label.'</option>';}
			}
			?>
            </select>
            -
            <select name="fn_mes" id="fn_mes">
             <?php
            for($m=1;$m<=12;$m++)
			{
				if($m<10){$m_label="0".$m;}
				else{ $m_label=$m;}
				
				if($mes==$m)
				{echo'<option value="'.$m_label.'" selected="selected">'.$m_label.'</option>';}
				else
				{ echo'<option value="'.$m_label.'" >'.$m_label.'</option>';}
			}
			?>
            </select>
            -
            <select name="fn_year" id="fn_year">
             <?php
			 $year_actual=date("Y");
			 $year_inicio=$year_actual-100;
            for($y=$year_actual;$y>=$year_inicio;$y--)
			{
				if($year==$y)
				{
					echo'<option value="'.$y.'" selected="selected">'.$y.'</option>';
				}
				else
				{ echo'<option value="'.$y.'" >'.$y.'</option>';}
			}
			?>
            </select>
          </td>
      </tr>
      <tr >
        <td class="Estilo3">Sexo</td>
        <td><select name="sexo" id="sexo">
        	<?php
			  	foreach($array_sexo as $n => $valor)
				{
					if($sexo==$n)
					{
						echo'<option value="'.$n.'" selected="selected">'.$valor.'</option>';
					}
					else
					{ echo'<option value="'.$n.'" >'.$valor.'</option>';}	
					
				}
              ?>
        </select>
        </td>
      </tr>
      <tr > 
        <td><span class="Estilo3">Fono</span></td>
        <td> 
          <input type="text" name="fono" size="25" maxlength="25" value="<?php echo $fono;?>">        </td>
      </tr>
      <tr > 
        <td><span class="Estilo3">Direccion</span></td>
        <td> 
          <input type="text" name="direccion" size="50" maxlength="50" value="<?php echo $direccion;?>">        </td>
      </tr>
      <tr > 
        <td><span class="Estilo3">Ciudad</span></td>
        <td>
          <input name="ciudad" type="text" value="<?php echo $ciudad;?>"></td>
         
      </tr>
      <tr > 
        <td><span class="Estilo3">Email</span></td>
        <td><input type="text" name="correo" size="50" maxlength="50" value="<?php echo $email_personal;?>">        </td>
      </tr>
      <tr >
        <td>Email Institucional</td>
        <td>          <input name="correoIntitucional" type="text" id="correoIntitucional" value="<?php echo $email;?>" size="50" maxlength="50" readonly>
          Acceso por <a href="http://www.gmail.com" target="_blank">ww.gmail.com</a></td>
      </tr>
	  </tbody>
    </table>
  </div>
</form>
<div id="apDiv3"><a href="#" class="button_G" onClick="CONFIRMAR();">Guardar Datos</a></div>
</body>
</html>
