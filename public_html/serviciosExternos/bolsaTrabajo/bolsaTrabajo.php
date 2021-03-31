<!DOCTYPE html>
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if IE 9 ]><html class="ie ie9" lang="en"> <![endif]-->
<html lang="es">

<head>
  	<meta charset="utf8">
    <title>CFT MASS | Bolsa de Trabajo</title>
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, maximum-scale=1, user-scalable=no">
    
    <!-- Favicons-->
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon"/>
    <link rel="apple-touch-icon" type="image/x-icon" href="img/apple-touch-icon-57x57-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="img/apple-touch-icon-72x72-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="img/apple-touch-icon-114x114-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="img/apple-touch-icon-144x144-precomposed.png">
    
    <!-- CSS -->
    <link href="http://cftmass.cl/version_2/css/bootstrap.min.css" rel="stylesheet">
    <link href="http://cftmass.cl/version_2/css/superfish.css" rel="stylesheet">
    <link href="http://cftmass.cl/version_2/css/style.css" rel="stylesheet">
    <link href="http://cftmass.cl/version_2/fontello/css/fontello.css" rel="stylesheet">
     <!-- color scheme css -->
    <link href="http://cftmass.cl/version_2/css/color_scheme.css" rel="stylesheet">
    
    <!--[if lt IE 9]>
      <script src="http://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="http://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    
</head>

  <body>
  <?php include("../includes/cabeceraWebN1.php");?>

    <section id="sub-header">
    <div class="container">
        <div class="row">
        	<div class="col-md-12 text-center">
            	<h1>Bolsa de Trabajo</h1>
                
                <p class="lead">C.F.T. <strong>Massachusetts</strong> instituci&oacute;n educacional con m&aacute;s de 30 a&ntilde;os de exitosa trayectoria</p>
            </div>
        </div><!-- End row -->
    </div><!-- End container -->
    <div class="divider_top"></div>
    </section><!-- End sub-header -->
    
    
    <section id="main_content" >
    	<div class="container">
        
        
        
        <div class="row">
        
        <aside class="col-lg-3 col-md-4 col-sm-4">
          <div class="box_style_1">
            	<h4>InformaciOn</h4>
            <ul class="submenu-col">
                <li>te presentamos ofertas laborales </li>
                
           
            </ul>
            
            <hr>
            
            <h5>&nbsp;</h5>
          </div>
        </aside>
        
        <div class="col-lg-9 col-md-8 col-sm-8">
       
           <?php
           	require("../../../funciones/conexion_v2.php");
			 $cons="SELECT * FROM bolsaTrabajo ORDER by id DESC";
			 $sqli=$conexion_mysqli->query($cons);
			 while($BT=$sqli->fetch_assoc()){
				 $auxTitulo=$BT["titulo"];
				 $auxCuerpo=$BT["cuerpo"];
				 $auxFecha=$BT["fechaGeneracion"];
				 
				echo'<div class="panel panel-info filterable add_bottom_45">
                    <div class="panel-heading">
                        <h3 class="panel-title">'.strtoupper($auxTitulo).', creado el ['.$auxFecha.']</h3>
                        <div class="pull-right">
                            
                        </div>
                  </div>
                    <table class="table table-responsive table-striped">
                       
                        <tbody>
                          <tr>
                          	<td>'.$auxCuerpo.'</td>
                           </tr>
                        </tbody>
                    </table>
                    
                   
          </div><!-- End filterable -->';
				 
			 }
			 
			 $sqli->free();
			 $conexion_mysqli->close();
			 ?>     

          
                    
          </div>
          </div>
          </div><!-- End filterable -->
                    
                    
                        
 
        </div><!-- End col-lg-9-->                        
        
            	
        </div><!-- End container -->
    </section><!-- End main_content -->

<?php include("../includes/pieWebN1.php");?>

<div id="toTop">Subir</div>

<!-- JQUERY -->
<script src="js/jquery-1.10.2.min.js"></script>
<script src="js/table_filter.js"></script>

<!-- OTHER JS --> 
<script src="js/superfish.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/row_link.js"></script>
<script src="js/retina.min.js"></script>
<script src="assets/validate.js"></script>
<script src="js/jquery.placeholder.js"></script>
<script src="js/functions.js"></script>
<script src="js/classie.js"></script>
<script src="js/uisearch.js"></script>
<script>new UISearch( document.getElementById( 'sb-search' ) );</script>


  </body>
</html>