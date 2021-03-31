<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Funcionarios->Edicion Datos V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<html>
<head>
<title>Modifica Datos</title>
<?php include("../../../funciones/codificacion.php");?>

<style type="text/css">
<!--
body {
	background-color: #FFFFFF;
}
.Estilo2 {color: #0080C0}
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
 <script src="../../libreria_publica/JSCal/src/js/jscal2.js"></script>
 <script src="../../libreria_publica/JSCal/src/js/lang/es.js"></script>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/steel/steel.css">
<style type="text/css">
<!--
.Estilo3 {	font-size: 12px;
	font-weight: bold;
}
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 168px;
}
-->
</style>
<script src="../../SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../SpryAssets/SpryTabbedPanels.css">
<style type="text/css">
<!--
.Estilo5 {
	font-size: 10px;
	font-style: italic;
}
.Estilo6 {color: #0000FF}
#apDiv2 {
	position:absolute;
	width:50%;
	height:29px;
	z-index:2;
	left: 5%;
	top: 131px;
	text-align: left;
}
-->
</style>
<script language="javascript">
function Verificar()
{
	c=confirm('Seguro(a) Desea Modificar a este Funcionario?');
	if(c)
	{
		document.frm.submit();
	}
}
</script>
</head>
      <?php
    include("../../../funciones/conexion_v2.php");
	include("../../../funciones/funciones_sistema.php");
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
   /////////////////////////////////////////
  $id_funcionario=base64_decode($_GET["id_fun"]);
  	$array_tipo_institucion=array("U"=>"Universidad","IP"=>"Intituto Profesional","CFT"=>"Centro Formacion Tecnica");
	$array_grado_academico=array("Licenciado","Magister","Doctor");
	$array_sexo=array("M"=>"Masculino", "F"=>"Femenino");
	$array_cuenta_docente=array("ACTIVA", "INACTIVA");
	$array_privilegio=array("1"=>"Docente","2"=>"Admin", "3"=>"Finanzas", "4"=>"ADMIN TOTAL", "5"=>"Matricula", "6"=>"Inspeccion", "7"=>"Externo", "8"=>"jefe_carrera");
	$array_con_acceso=array("ON", "OFF");
	
	$array_cajas=array("TODAS"=>"TODAS","TC1"=>"caja 1 Talca", "TC2"=>"caja 2 Talca", "LC1"=>"caja 1 Linares");
	//////////////////////////////////////////
   $res="SELECT * FROM personal where id='$id_funcionario' LIMIT 1";
   //echo"--> $res<br>";
   $result=$conexion_mysqli->query($res)or die("datos ".$conexion_mysqli->error);
   $row = $result->fetch_assoc();
   
    $id=$row["id"];
    $rut=$row["rut"];
	$con_acceso=$row["con_acceso"];
    $nombre=$row["nombre"];
	$apellido=$row["apellido"];
	$apellido_P=$row["apellido_P"];
	$apellido_M=$row["apellido_M"];
    $email=$row["email"];
	$email_personal=$row["email_personal"];
    $fono=$row["fono"];
	$direccion=$row["direccion"];
    $ciudad=$row["ciudad"];
	$sede=$row["sede"];
    $clave=$row["clave"];
	$nivel=$row["nivel"];
	$organizacion=$row["organizacion"];
	$fecha_ingreso_institucion=$row["fecha_ingreso_institucion"];
	$nick=$row["nick"];
	/////////////////////////////
	$sexo=$row["sexo"];
	$fecha_nacimiento=$row["fecha_nacimiento"];
	$array_fecha=explode("-",$fecha_nacimiento);
	$year=$array_fecha[0];
	$mes=$array_fecha[1];
	$dia=$array_fecha[2];
	/////////////////
	$caja_asignada=$row["caja_asignada"];
	
	$cuenta_docente=$row["cuenta_docente"];
	//var_export($row);
	$result->free();
	
	$consX="SELECT NombreSede, id_sede FROM sede where estado='1'";
	if(DEBUG){ echo"--->$consX<br>";}
	
	$ARRAY_RELACION_SEDE_PERSONAL=array();
	$sqlX=$conexion_mysqli->query($consX)or die($conexion_mysqli->error);
	while($DA=$sqlX->fetch_assoc()){
		$id_sede=$DA["id_sede"];
		$ARRAY_RELACION_SEDE_PERSONAL[$id_sede]["NombreSede"]=$DA["NombreSede"];
		$consY="SELECT COUNT(id_relacion) FROM personalSede where id_sede='$id_sede' AND id_personal='$id_funcionario'";
		if(DEBUG){ echo"==>$consY<br>";}
		$sqlY=$conexion_mysqli->query($consY)or die($conexion_mysqli->error);
		$DY=$sqlY->fetch_row();
		$numRelaciones=$DY[0];
		if(empty($numRelaciones)){$numRelaciones=0;}
		$sqlY->free();
		$ARRAY_RELACION_SEDE_PERSONAL[$id_sede]["relacion"]=$numRelaciones;
	}
	$sqlX->free();
	if(DEBUG){ var_dump($ARRAY_RELACION_SEDE_PERSONAL);}
	
?>
<body>
<h1 id="banner">Funcionarios - Edici&oacute;n Datos</h1>
<div id="link"><br>
<a href="../lista_funcionarios.php" class="button">Volver al Menu</a><br>
<br>
<div id="apDiv2"><?php
if(isset($_GET["error"]))
{
	$error=$_GET["error"];
	$msj="";
	switch($error)
	{
		case"RC0":
			$msj="<strong>Clave Restablecida...Ma_+rut</strong>";
			break;
		default:
			$msj="";	
	}
	
	echo $msj;
}
?>
</div>
<a href="../mis_datos/estudios_laborales/estudio_1.php?id_funcionario=<?php echo $id_funcionario;?>" class="button">Estudios y Trabajo</a><br>
<br>
<a href="../asignacion_asignaturas/asignacion_asignaturas_docente_1.php?fid=<?php echo base64_encode($id_funcionario);?>" class="button">Asignacion Ramos - Docente</a>
</br><br>
<a href="restablecer_clave.php?id_funcionario=<?php echo base64_encode($id_funcionario);?>" class="button_R">Restablecer Clave</a>
</div>
<div id="apDiv1">
    <div align="center" class="Estilo3">Modificacion de Funcionarios        </div>
    <form action="grabardocenteact.php" method="post" enctype="multipart/form-data" name="frm" id="frm2">

      <div id="TabbedPanels2" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Datos Personales</li>
          <li class="TabbedPanelsTab" tabindex="1">Otros</li>
          <li class="TabbedPanelsTab" tabindex="2">Registros</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent"><table width="100%">
            <tr class="odd">
              <td>ID</td>
              <td colspan="2"><?php echo $id_funcionario;?></td>
            </tr>
            <tbody>
              <tr class="odd">
                <td width="22%">Fecha Ingreso Institucion</td>
                <td width="11%"><label for="fecha_ingreso_institucion">
                  <input  name="fecha_ingreso_institucion" id="fecha_ingreso_institucion" size="15" maxlength="10" readonly value="<?php echo $fecha_ingreso_institucion;?>"/>
                </label></td>
                <td width="67%"><input type="button" name="boton1" id="boton1" value="..." /></td>
              </tr>
              <tr class="odd">
                <td><span class="Estilo3">Rut</span></td>
                <td colspan="2">
                <input name="rut" type="text" id="rut" value="<?php echo $rut;?>" size="15"></td>
              </tr>
              <tr class="odd">
                <td><span class="Estilo3">Nombres:</span></td>
                <td colspan="2"><input name="nombres" type="text" id="nombres" value="<?php echo $nombre;?>" size="30" maxlength="50">
                    <input type="hidden" name="id_funcionario" value="<?php echo $id_funcionario;?>">                </td>
              </tr>
              <tr class="odd">
                <td><span class="Estilo3">Apellido P</span></td>
                <td colspan="2"><input name="apellido_P" type="text" id="apellido_P" value="<?php echo $apellido_P;?>" size="30" maxlength="50">                </td>
              </tr>
              <tr class="odd">
                <td class="Estilo3">Apellido M</td>
                <td colspan="2"><label for="apellido_M"></label>
                <input name="apellido_M" type="text" id="apellido_M" value="<?php echo $apellido_M;?>" size="30" maxlength="50"></td>
              </tr>
              <tr class="odd">
                <td class="Estilo3">Fecha Nacimiento</td>
                <td colspan="2"><select name="fn_dia" id="fn_dia">
                    <?php
            for($d=1;$d<=31;$d++)
			{
				if($d<10){ $d_label="0".$d;}
				else{$d_label=$d;}
				
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
				if($m<10){ $m_label="0".$m;}
				else{$m_label=$m;}
				
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
				{echo'<option value="'.$y.'" selected="selected">'.$y.'</option>';}
				else
				{ echo'<option value="'.$y.'" >'.$y.'</option>';}
			}
			?>
                  </select>                </td>
              </tr>
              <tr class="odd">
                <td class="Estilo3">Sexo</td>
                <td colspan="2"><select name="sexo" id="sexo">
                    <?php
			  	foreach($array_sexo as $n => $valor)
				{
					if($sexo==$n)
					{echo'<option value="'.$n.'" selected="selected">'.$valor.'</option>';}
					else
					{ echo'<option value="'.$n.'" >'.$valor.'</option>';}	
					
				}
              ?>
                  </select>                </td>
              </tr>
              <tr class="odd">
                <td class="Estilo3">Sede</td>
                <td colspan="2">
                <?php
				$array_sede=array("Talca", "Linares");
				$select='<select name="fsede">';
                if($privilegio=="admi_total")
				{
					foreach($array_sede as $ns => $valors)
					{
						if($sede==$valors)
						{ $select.='<option value="'.$valors.'" selected="selected">'.$valors.'</option>';}
						else
						{ $select.='<option value="'.$valors.'" >'.$valors.'</option>';}
					}
				}
				else
				{
					$select.='<option value="'.$sede.'">'.$sede.'</option>';
				}
				$select.='</select>';
				echo $select;
				?>
                  <span class="Estilo5">(sede Actual:<?php echo $sede;?>)</span></td>
              </tr>
              <tr class="odd">
                <td><span class="Estilo3">Fono</span></td>
                <td colspan="2"><input name="fono" type="text" id="fono" value="<?php echo $fono;?>" size="25" maxlength="25">                </td>
              </tr>
              <tr class="odd">
                <td><span class="Estilo3">Direccion</span></td>
                <td colspan="2"><input name="direccion" type="text" id="direccion" value="<?php echo $direccion;?>" size="50" maxlength="50">                </td>
              </tr>
              <tr class="odd">
                <td><span class="Estilo3">Ciudad</span></td>
                <td colspan="2"><input name="ciudad" type="text" value="<?php echo $ciudad;?>"></td>
              </tr>
              <tr class="odd">
                <td><span class="Estilo3">Email Institucional</span></td>
                <td colspan="2"><input name="correo" type="text" id="correo" value="<?php echo $email;?>" size="50" maxlength="50"> 
                  (si, existe)                </td>
              </tr>
              <tr class="odd">
                <td><span class="Estilo3">Email</span> Personal</td>
                <td colspan="2"><label for="email_personal"></label>
                <input name="email_personal" type="text" id="email_personal" value="<?php echo $email_personal;?>" size="50"></td>
              </tr>
              <tr class="odd">
                <td>Nick</td>
                <td colspan="2"><label for="nick"></label>
                <input name="nick" type="text" id="nick" value="<?php echo $nick;?>"></td>
              </tr>
              <tr class="odd">
                <td>Tiene Acceso</td>
                <td colspan="2"><label for="con_acceso"></label>
                  <select name="con_acceso" id="con_acceso">
                   <?php
                   	foreach($array_con_acceso as $na => $valora)
					{
						if($valora==$con_acceso)
						{ echo'<option value="'.$valora.'" selected="selected">'.$valora.'</option>';}
						else
						{ echo'<option value="'.$valora.'">'.$valora.'</option>';}
					}
				   ?>
                </select></td>
              </tr>
              <tr>
                <td><span class="Estilo6"><strong>Cuenta Docente</strong></span></td>
                <td colspan="2"><span class="Estilo6">
                  <select name="cuenta_docente" id="cuenta_docente">
                  <?php
                  foreach($array_cuenta_docente as $n => $valor)
				  {
				  	if($valor==$cuenta_docente)
					{ echo'<option value="'.$valor.'" selected="selected">'.$valor.'</option>';}
					else
					{ echo'<option value="'.$valor.'">'.$valor.'</option>';}
				  }
				  ?>
                    </select>
                </span></td>
              </tr>
             
              <tr>
                <td>Organizacion</td>
                <td colspan="2"><label for="organizacion"></label>
                <input type="text" name="organizacion" id="organizacion" value="<?php echo $organizacion;?>"></td>
              </tr>
              <tr>
                <td>Nivel</td>
                <td colspan="2">
                 <?php
              if($privilegio=="admi_total")
			  {
			  	echo'<select name="nivel">';
				foreach($array_privilegio as $x => $valorx)
				{
					if($nivel==$x)
					{
						echo'<option value="'.$x.'" selected="selected">'.$valorx.'</option>';
					}
					else
					{
						echo'<option value="'.$x.'" >'.$valorx.'</option>';
					}
				}
				echo'</select>';
			  }
			  else
			  { echo '<input name="nivel" type="hidden" value="'.$nivel.'">'.$nivel;}
			  ?>
             
                </td>
              </tr>
            </tbody>
          </table>
          </div>
          <div class="TabbedPanelsContent">
            <table width="50%" border="0">
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td>Caja Asignada</td>
                <td><label for="caja_asignada"></label>
                  <select name="caja_asignada" id="caja_asignada">
                    <?php
					foreach($array_cajas as $codigo_caja => $label_caja)
					{
						if($codigo_caja==$caja_asignada)
						{
							 echo'<option value="'.$codigo_caja.'" selected>'.$label_caja.'</option>';
						}
						else
						{
							 echo'<option value="'.$codigo_caja.'">'.$label_caja.'</option>';
						}
					}
				?>
                  </select></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table>
            <table width="50%" border="0">
              <tr>
                <td colspan="2">Puede operar con las sgtes sede: (privilegio especifico)</td>
              </tr>
             
             <?php
			 if(count($ARRAY_RELACION_SEDE_PERSONAL)>0){
				 foreach($ARRAY_RELACION_SEDE_PERSONAL as $auxIdSede => $auxArray){
					 $auxNombreSede=$auxArray["NombreSede"];
					 $auxRelacionSede=$auxArray["relacion"];
					 
					 if($auxRelacionSede>0){$boton='<a href="relacionSede.php?id_funcionario='.$id_funcionario.'&nuevoEstado=no&id_sede='.$auxIdSede.'">si</a>';}
					 else{$boton='<a href="relacionSede.php?id_funcionario='.$id_funcionario.'&nuevoEstado=si&id_sede='.$auxIdSede.'">no</a>';}
					 
					 echo'<tr>
					 		<td>'.$auxNombreSede.'</td>
							<td>'.$boton.'</td>
					 	  </tr>';
				 }
			 }
             ?>
            </table>
            <p>&nbsp;</p>
          </div>
          <div class="TabbedPanelsContent"> <table width="100%" cellpadding="0" cellspacing="0" border="0" class="display" id="example">
  <thead>
    <tr>
      <th>N.</th>
      <th>Tipo Registro</th>
      <th>Descripcion</th>
      <th>Fecha Hora</th>
      <th>Usuario</th>
    </tr>
    </thead>
    <tbody>
   <?php
	   $cons="SELECT * FROM personal_registros WHERE id_funcionario='$id_funcionario' ORDER by id";
	   if(DEBUG){ echo"--->$cons<br>";}
	   $sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	   $num_reg=$sql->num_rows;
	   if($num_reg>0)
	   {
		   	$contador=0;
			while($R=$sql->fetch_assoc())
			{
				$contador++;
				
				$tipo_registro=$R["tipo_registro"];
				$descripcion=$R["descripcion"];
				$fecha_hora=$R["fecha_generacion"];
				$cod_user=$R["cod_user"];
				$usuario_nombre=NOMBRE_PERSONAL($cod_user);
					
				echo'<tr class="gradeX">
						<td>'.$contador.'</td>
						<td>'.$tipo_registro.'</td>
						<td>'.$descripcion.'</td>
						<td>'.$fecha_hora.'</td>
						<td><a href="#" title="'.$usuario_nombre.'">'.$cod_user.'</a></td>
						</tr>';
			}
		}
	$sql->free();
	$conexion_mysqli->close();
	
   ?>
    </tbody>
  </table></div>
        </div>
      </div>
      
      <div id="boton">  <input type="reset" name="Submit2" value="Restablecer">
      <input type="button" name="accion2" value="Grabar" onClick="Verificar();">
      </div>
    </form>
</div>

<script type="text/javascript">
<!--
var TabbedPanels2 = new Spry.Widget.TabbedPanels("TabbedPanels2");
//-->
</script>
<script type="text/javascript">
//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton1", "fecha_ingreso_institucion", "%Y-%m-%d");
    //]]>
</script>
</body>
</html>
