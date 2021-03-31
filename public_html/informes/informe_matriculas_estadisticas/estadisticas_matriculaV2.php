<?php 
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(true);
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Comparador_matriculas_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<title>estadisticas Matricula</title>
<style type="text/css">

#apDiv5 {
	width:431px;
	height:15px;
	z-index:1;
	left: 51px;
	top: 327px;
}
</style>



<script language="javascript">
function mostrar_informacion(dato_1, dato_2, dato_3)
{
	alert("Hoy: +"+dato_1+"\n Total a la Fecha:"+dato_2);
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Matriculas Estadisticas</h1>
<div id="link"><br />
<a href="index.php" class="button">Volver al Seleccion</a></div>
<div id="apDiv1">
<?php
	$ARRAY_DATOS=array();
	$ARRAY_DATOS2=array();
	
	$usarFechaCorte=true;
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funcion.php");
	if(DEBUG){ var_export($_POST);}
	
	$yearActual=date("Y");
	for($yearBuscado=2011;$yearBuscado<=$yearActual;$yearBuscado++){
		$auxFechaCorte=$yearBuscado."-".date("m-d");
		$campoFechaCorte="";
		$fechaCortelabel="No usando fecha de corte";
		
		if($usarFechaCorte){$campoFechaCorte="AND fecha_generacion<='$auxFechaCorte'"; $fechaCortelabel="usando fecha de corte segun fecha actual";}
		
		
		$cons_1="SELECT COUNT(idAlumno)as cantidad, sede, yearIngresoCarrera, yearG, mesG  FROM (SELECT DISTINCT(id_alumno)as idAlumno,sede, yearIngresoCarrera, YEAR(min(fecha_generacion)) as yearG, month(min(fecha_generacion))as mesG FROM contratos2
		where ano='$yearBuscado' $campoFechaCorte
		GROUP by id_alumno)as A
		GROUP by sede, yearIngresoCarrera, yearG, mesG
		ORDER by sede, yearG, mesG, yearIngresoCarrera";
		if(DEBUG){ echo"--->$cons_1<br><br>";}
						
			$sqli=$conexion_mysqli->query($cons_1)or die($conexion_mysqli->error);
			while($D=$sqli->fetch_assoc()){
				$x_cantidad=$D["cantidad"];
				$x_sede=$D["sede"];
				$x_yearIngresoCarrera=$D["yearIngresoCarrera"];
				$x_yearGeneracion=$D["yearG"];
				$x_mesGeneracion=$D["mesG"];
				
				$x_tipoAlumno="antiguo";
				if($x_yearIngresoCarrera==$yearBuscado){$x_tipoAlumno="nuevo";}
				
				
				$ARRAY_DATOS[$yearBuscado][$x_sede][$x_yearIngresoCarrera][$x_yearGeneracion][$x_mesGeneracion]["cantidad"]=$x_cantidad;
				$ARRAY_DATOS[$yearBuscado][$x_sede][$x_yearIngresoCarrera][$x_yearGeneracion][$x_mesGeneracion]["tipo"]=$x_tipoAlumno;
				
				if(isset($ARRAY_DATOS2[$yearBuscado][$x_sede][$x_tipoAlumno][$x_yearGeneracion][$x_mesGeneracion]["cantidad"])){
				$ARRAY_DATOS2[$yearBuscado][$x_sede][$x_tipoAlumno][$x_yearGeneracion][$x_mesGeneracion]["cantidad"]+=$x_cantidad;}
				else{$ARRAY_DATOS2[$yearBuscado][$x_sede][$x_tipoAlumno][$x_yearGeneracion][$x_mesGeneracion]["cantidad"]=$x_cantidad;}
				
				
			}
			$sqli->free();
		}
		
$conexion_mysqli->close();

if(DEBUG){var_dump($ARRAY_DATOS);}
?>
<div id="chartdiv" style="width: 100%; height: 400px;">...</div>

<div id="apDiv5">
 <table width="100" border="1">
  <thead>
    <tr>
      <th colspan="7">&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>Year Revision</td>
      <td>Sede</td>
      <td>Year ingreso Carrera</td>
      <td>Tipo Alumno</td>
      <td>Year Matricula</td>
      <td>Mes matricula</td>
      <td>Cantidad</td>
    </tr>
    <?php
    foreach($ARRAY_DATOS as $X_year => $array1){
	
		foreach($array1 as $X_sede => $array2){
			foreach($array2 as $X_yearIngresoCarrera => $array3){
				foreach($array3 as $X_yearG => $array4){
					foreach($array4 as $X_mesG => $array5){
						$X_cantidad=$array5["cantidad"];
						$X_tipo=$array5["tipo"];
						echo'<tr>
							  <td>'.$X_year.'</td>
							  <td>'.$X_sede.'</td>
							  <td>'.$X_yearIngresoCarrera.'</td>
							  <td>'.$X_tipo.'</td>
							  <td>'.$X_yearG.'</td>
							  <td>'.$X_mesG.'</td>
							  <td>'.$X_cantidad.'</td>
							</tr>';
					}
				}
			}
		}
	}
	?>
  </tbody>
  </table><br><br>
  
  <table width="100" border="1">
  <thead>
    <tr>
      <th colspan="7">Tipo 2</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>Year Revision</td>
      <td>Sede</td>
      <td>Tipo Alumno</td>
      <td>Year Matricula</td>
      <td>Mes matricula</td>
      <td>Cantidad</td>
       <td>Acumulado</td>
    </tr>
    <?php
	 foreach($ARRAY_DATOS2 as $X_year => $array1){
		foreach($array1 as $X_sede => $array2){
			foreach($array2 as $X_tipo => $array3){
				$ACUMULADOR[$X_year][$X_sede][$X_tipo]=0;
				foreach($array3 as $X_yearG => $array4){
					foreach($array4 as  $X_mesG=> $array5){
						$X_cantidad=$array5["cantidad"];
						$ACUMULADOR[$X_year][$X_sede][$X_tipo]+=$X_cantidad;
						$ARRAY_DATOS2[$X_year][$X_sede][$X_tipo][$X_yearG][$X_mesG]["acumulado"]=$ACUMULADOR[$X_year][$X_sede][$X_tipo];
							
								
						
					}
				}
			}
		}
	}
     foreach($ARRAY_DATOS2 as $X_year => $array1){
		foreach($array1 as $X_sede => $array2){
			foreach($array2 as $X_tipo => $array3){
				foreach($array3 as $X_yearG => $array4){
					foreach($array4 as  $X_mesG=> $array5){
						$X_cantidad=$array5["cantidad"];
						$X_acumulado=$array5["acumulado"];
					$mostrar=true;
					//if($X_tipo!=="nuevo"){$mostrar=false;}
					//if($X_sede!=="Talca"){$mostrar=false;}
					
						if($mostrar){
							
							echo'<tr>
								  <td>'.$X_year.'</td>
								  <td>'.$X_sede.'</td>
								  <td>'.$X_tipo.'</td>
								  <td>'.$X_yearG.'</td>
								  <td>'.$X_mesG.'</td>
								  <td>'.$X_cantidad.'</td>	
								  <td>'.$X_acumulado.'</td>			 
								</tr>';
						}
					}
				}
			}
		}
	}
	?>
  </tbody>
  </table><br><br>

   <script src="../../libreria_publica/amcharts_3.20.7/amcharts/amcharts.js" type="text/javascript"></script>
        <script src="../../libreria_publica/amcharts_3.20.7/amcharts/serial.js" type="text/javascript"></script>

        <script>
           var chart;

            var chartData = [
                {
                    "mes": 1,
                    "italy": 1,
                    "germany": 5,
                    "uk": 3,
					"chile":3
                },
                {
                    "mes": 2,
                    "italy": 7,
                    "germany": 2,
                    "uk": 6,
					"chile":3
                }
            ];


            AmCharts.ready(function () {
                // SERIAL CHART
                chart = new AmCharts.AmSerialChart();
                chart.dataProvider = chartData;
                chart.categoryField = "mes";
                chart.startDuration = 0.5;
                chart.balloon.color = "#000000";

                // AXES
                // category
                var categoryAxis = chart.categoryAxis;
                categoryAxis.fillAlpha = 1;
                categoryAxis.fillColor = "#FAFAFA";
                categoryAxis.gridAlpha = 0;
                categoryAxis.axisAlpha = 0;
                categoryAxis.gridPosition = "start";
                categoryAxis.position = "top";

                // value
                var valueAxis = new AmCharts.ValueAxis();
                valueAxis.title = "Matriculas";
                valueAxis.dashLength = 5;
                valueAxis.axisAlpha = 0;
                valueAxis.minimum = 1;
                valueAxis.maximum = 10;
                valueAxis.integersOnly = true;
                valueAxis.gridCount = 10;
                valueAxis.reversed = false; // this line makes the value axis reversed
                chart.addValueAxis(valueAxis);

                // GRAPHS
                // Italy graph
                var graph = new AmCharts.AmGraph();
                graph.title = "Italy";
                graph.valueField = "italy";
                graph.hidden = false; // this line makes the graph initially hidden
               // graph.balloonText = "place taken by Italy in [[category]]: [[value]]";
                graph.bullet = "round";
                chart.addGraph(graph);

                // Germany graph
                var graph = new AmCharts.AmGraph();
                graph.title = "Germany";
                graph.valueField = "germany";
                graph.balloonText = "place taken by Germany in [[category]]: [[value]]";
                graph.bullet = "round";
                chart.addGraph(graph);

                // United Kingdom graph
                var graph = new AmCharts.AmGraph();
                graph.title = "United Kingdom";
                graph.valueField = "uk";
                graph.balloonText = "place taken by UK in [[category]]: [[value]]";
                graph.bullet = "round";
                chart.addGraph(graph);

                // CURSOR
                var chartCursor = new AmCharts.ChartCursor();
                chartCursor.cursorPosition = "mouse";
                chartCursor.zoomable = false;
                chartCursor.cursorAlpha = 0;
                chart.addChartCursor(chartCursor);

                // LEGEND
                var legend = new AmCharts.AmLegend();
                legend.useGraphSettings = true;
                chart.addLegend(legend);

                // WRITE
                chart.write("chartdiv");
            });
        </script>
</div>
</body>
</html>