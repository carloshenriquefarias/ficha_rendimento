<?php  
	require_once '../classes/config.php';
	require_once '../classes/Notas.php';
	require_once '../classes/Disciplinas.php';
	require_once '../classes/Cursos.php';

	// $bloco = 6;

	// $periodo_id = 201901;	
	// $id_curso = 1;
	
	// $professor_id = 682 ;
	if (!isset($_POST['bloco_id']) || !isset($_POST['curso_id']) || !isset($_POST['periodo_id'])  ) {
		header('Location: ../selecionar.php');
	}

	if ($_POST['bloco_id'] == '') {
		header('Location: ../selecionar.php');
		
	}

	$bloco 			= $_POST['bloco_id'] ;
	$id_curso 		= $_POST['curso_id'] ;	
	$periodo_id 	= $_POST['periodo_id'] ;

	$disciplinas = new Disciplinas();
	$cursos = new Cursos();
	$qtdDisciplina = $disciplinas->qtdDisciplina($bloco, $periodo_id, $id_curso);

	$desc_curso = $cursos->descCurso($id_curso);
	

	// echo "<pre>";
	// print_r($desc_curso);
	// echo "</pre>";
	// exit();

?>

<!DOCTYPE html>
<html>
<head>
	<title>Ficha Rendimento</title>
	<meta charset="utf-8">
	<link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700" rel="stylesheet">
	<link rel="stylesheet" href="../css/style-1.css">	
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="https://code.highcharts.com/modules/series-label.js"></script>
	<!-- <script src="https://code.highcharts.com/modules/exporting.js"></script> -->
	<!-- <script src="https://code.highcharts.com/modules/export-data.js"></script> -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<style>
		@media print {
			.no-print {
				display: none;
			}
		}
		/*impressao layout*/
		body {
			background: #fff;
		}
		.container* {
			min-width: 310px !important; 
			height: 400px  !important; 
			max-width: 500px  !important; 
			border: 1px solid #000;
		}
		
		
		/*.grafico {
			min-width: 310px !important; 
			height: 400px  !important; 
			max-width: 500px  !important; 
			border: 1px solid #000;
		}*/

			* {
				background: transparent;
				color: #000;
				text-shadow: none;
				filter: none;
				-ms-filter: none;
			}
			.container {
				/*min-width: 310px !important; 
				max-width: 500px  !important; */
				width: 48% !important;
				height: 300px  !important; 
				/*border: 1px solid #000;*/
				float: left;
				margin-left: 0;
				margin-right: 0;
			}
			.container-2 {
				/*min-width: 310px !important; 
				max-width: 500px  !important; */
				width:48% !important;
				height: 300px  !important; 
				/*border: 1px solid #d3d3d3;*/
				float: left;
				margin-top: 20px;
				margin-left: 0;
				margin-right: 0;
			}

			#grafico-1 {
				min-width: 200px !important; 
				height: 300px  !important; 
				max-width: 350px  !important; 
				border: 1px solid #d3d3d3;
			}
			

			#grafico-2 {
				min-width: 200px !important; 
				height: 300px  !important; 
				max-width: 350px  !important; 
				border: 1px solid #d3d3d3;
			}
			.grafico {
				min-width: 200px !important; 
				height: 300px  !important; 
				max-width: 350px  !important; 
				border: 1px solid #d3d3d3;
			}

			.row {
				width: 100% !important;
				height: auto !important;
			}

			.conteudo {
				width: 100% !important;

			}
		/*}*/
	</style>
</head>
<body>
	<!-- cold -->
	<!-- <button id="imprimir">Imprimir</button> -->
	<div class="conteudo" >
		<div class="titulo">
			<h2>Relatório de Rendimento</h2>			
			<h4>Curso: <?php echo $desc_curso ?> - <?php echo $bloco."º Bloco "; ?> - Período: <?php echo $periodo_id; ?> <a href="grafico1/alunosPorDisciplina.php?p1=<?php echo $periodo_id ?>&p2=<?php echo $bloco ?>&p3=<?php echo $id_curso ?>"></a></h4>			
		</div>
		<div class="row" style=";">
		<?php  
			
			$contador = 1;
			while ($contador <= $qtdDisciplina) {	
				$posicao = $contador % 2;
				if ($posicao == 0) {
					$lado = '-2';
				} else {
					$lado = '';
				}
							
			?>
			<div class="container<?php echo $lado ?>" style="">
				<div class="grafico" id="grafico-<?php echo $contador ?>" ></div>
			</div>
		<?php  
			$contador++;
			}	 
		?>
						
			<div class="clear"></div>			
		</div>
	
		<div class="row"  >
	
		</div>
	</div>
	<!-- <button id="executar">executar</button> -->

	<!-- <script src="https://code.highcharts.com/modules/data.js"></script>
	<script src="https://code.highcharts.com/modules/drilldown.js"></script> -->
<script type="text/javascript">
// $('body').css('display', 'none');
$(document).ready(function() {

			$.ajax({
			  url: '../json/DisciplinasPorBlocoEPeriodo.php',
			  dataType: 'json',
			  type: 'GET',
			  async: false,
			  data: {param1: "<?php echo $periodo_id ?>" , param2: "<?php  echo $bloco ?>", param3: "<?php  echo $id_curso ?>"},
			  success: function(data) {
			  	// console.log(data);
			  		$.each(data, function(i, value) {
			  			 
			  			 $titulo = value.descricao;
			  			 i = 1 + i; 
						$.ajax({
							  url: '../json/PercAprovPorDisciplina.php',
							  dataType: 'json',
							  async: false,
							  data: {param1: "<?php echo $periodo_id ?>" , param2: "<?php  echo $bloco ?>", param3: "<?php  echo $id_curso ?>", param4: value.id_disciplina }, 
							  success: function(data) {
						
								$.each(data, function(i, val) {
									 $percAprov = val.percAprov;
									 $percReprov = val.percReprov;
									 $percAlunosSemNotas = val.percAlunosSemN;									 
								});	
																		
								options1 = {
									    colors: ['#7cb5ec', '#ef1a1a','#ffad14'],
									    chart: {
									        renderTo: 'grafico-'+i,			        
									        plotBackgroundColor: null,
									        plotBorderWidth: null,
									        plotShadow: false,
									        type: 'pie'
									    },
									    title: {
									        text: '<a target="_blank" href="grafico1/detalhes.php?p1=<?php echo $periodo_id ?>&p2=<?php echo $bloco ?>&p3=<?php echo $id_curso ?>&p4='+value.id_disciplina+'" >'+$titulo+'</a>'
									    },
									    tooltip: {			        
									        pointFormat: '{series.name}: <b>{point.percentage:.0f}%</b>'
									    },			    
									    plotOptions: {
									        pie: {
									            allowPointSelect: true,
									            cursor: 'pointer',
									            dataLabels: {
									                enabled: true,
	                								format: '{point.y:.0f}% {point.name}'
									            },
									            showInLegend: true
									        }
									    },

									    
									    series: [{
										        name: 'Brands',
										        colorByPoint: true,
										        data: [{
										            name: 'Aprovados',
										            y: parseFloat($percAprov),
										            // sliced: true,
										            // selected: true
										        }, {
										            name: 'Reprovados',
										            y: parseFloat($percReprov),
										        },{
										            name: 'Sem Nota',
										            y: parseFloat($percAlunosSemNotas),
										            // sliced: true,
										            // selected: true
										        }]
										    }]
								};
								var chart1 = new Highcharts.Chart(options1);
								
								// ajax
							 }
							});


			  		});
			  		// $('body').css('display', 'block');
			  		
			  		setTimeout(function(){window.print();},1000);
			  		setTimeout("document.location = '../selecionar.php'",4000);
				}
			});


		
		
});//fim do carregar depois que a DOM carrega

	
	
	</script>	
	
	<script>	
	
	var i = 1;
	var qtdDisciplina = parseInt('<?php echo $qtdDisciplina ?>');

	// console.log(qtdDisciplina);

	while (i <= qtdDisciplina) {	
	
		
		
		
			jQuery.noConflict();
						var example = 'column-drilldown', 
							theme = 'default';
						(function($){ // encapsulate jQuery
					
				// Situação do Bloco do Curso
				Highcharts.chart('grafico-'+i, {
					colors: ['#7cb5ec', '#ef1a1a',],
				    chart: {
				        plotBackgroundColor: null,
				        plotBorderWidth: null,
				        plotShadow: false,
				        type: 'pie'
				    },
				    title: {
				        text: 'Carregando'
				        // text: '1º Bloco  - Direito (50 alunos)'
				    },
				    tooltip: {
				        // pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
				        pointFormat: '{series.name}: <b>{point.percentage:.0f}%</b>'
				    },
				    plotOptions: {
				        pie: {
				            allowPointSelect: true,
				            cursor: 'pointer',
				            dataLabels: {
				                enabled: false
				            },
				            showInLegend: true
				        }
				    },
				    series: [{
				        name: 'Brands',
				        colorByPoint: true,
				        data: [{
				            name: 'Aprovados',
				            y: 10,
				            // sliced: true,
				            // selected: true
				        }, {
				            name: 'Reprovados',
				            y: 90,
				        }]
				    }]
				});
			})(jQuery);
		// });
		i++;
	}

	
	</script>
	<!-- <script src="//code.highcharts.com/themes/grid-light.js"></script> -->
	<!-- <script src="//code.highcharts.com/themes/dark-unica.js"></script> -->
	<script>
		$('#imprimir').on('click',  function(event) {
			event.preventDefault();
			// $(this).css('display', 'none');
			// $('#grafico-1').css('display', 'none');
			$('#grafico-1').css({
				height: '200px',
				width: '200px'
			});

		});
	</script>
	

</body>
</html>