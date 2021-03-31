<?php 
session_start();
	if($_SESSION)
	{
		 $dato_1x=unserialize($_SESSION["valor1"]);
	     $dato_2x=unserialize($_SESSION["valor2"]);
		 //var_dump($dato_1x);
	}
	else
	{
		$dato_1x=array(1,1,1,1,1,1,1,1,1,1,1,1);
		$dato_2x=array(2,3,2,3,2,3,2,3,2,3,2,3);
	}
	$m_max=0;
	for($x=0;$x<count($dato_1x);$x++)
	{
		$d1=$dato_1x[$x];
		$d2=$dato_2x[$x];
		
		if($d1!=$d2)
		{
			if($d1>$d2)
			{
				$max=$d1;
			}
			elseif($d1<$d2)
			{
				$max=$d2;
			}
		}
		else
		{
			$max=$d1;
		}
		
		if($max > $m_max)
		{
			$m_max=$max;
		}	
	}
	$N_max=ceil(($m_max*100)/80);
?>
<?php
// generate some random data
//srand((double)microtime()*1000000);
//
// NOTE: how we are filling 3 arrays full of data,
//       one for each line on the graph
//

$data_1 = array();
$data_2 = array();
//$data_3 = array();
for( $i=0; $i<12; $i++ )
{
  //$data_1[$i] = $dato_1x[$i];
  //$data_2[$i] = $dato_2x[$i];
  $data_1[$i] = rand(1,20);
  $data_2[$i] = rand(8,13);
  //$data_3[] = rand(1,7);
}
include_once('../../../Graf/ofc/php-ofc-library/open-flash-chart.php');
$g = new graph();
$g->title( 'Graficos de Lineas', '{font-size: 20px; color: #736AFF}' );

// we add 3 sets of data:
$g->set_data( $dato_1x );
$g->set_data( $dato_2x );
//$g->set_data( $data_3 );

// we add the 3 line types and key labels
$g->line( 2, '0x9933CC', 'Espectativas', 10 );
$g->line( 2, '0xCC3399', 'Ingresos', 10);    // <-- 3px thick + dots
//$g->line_hollow( 2, 4, '0x80a033', 'Bounces', 10 );

$g->set_x_labels( array( 'Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre' ) );
$g->set_x_label_style( 10, '0x000000', 0, 2 );

$g->set_y_max( $N_max );
$g->y_label_steps(10 );
$g->set_y_legend( 'Estadisticas', 12, '#736AFF' );
echo $g->render();
?>