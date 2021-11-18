<head>
    <meta charset="iso-8859-1">
</head>


<div class="content-wrapper">

    <section class="content-header">
          <h1>Gráficos de Vendas </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Painel</li>
          </ol>
     </section>


            <?php  
			
				$consultor=$_SESSION['autConsultor']['id'];
	
				$contrato90=0;
				$contrato60=0;
				$contrato30=0;
				$contrato00=0;
				
				$orcamento90=0;
				$orcamento60=0;
				$orcamento30=0;
				$orcamento00=0;
			 	
			    // 90 dias //
				// contrato  
				$mes = date('m/Y',strtotime('-3months'));
				$mesano = explode('/',$mes);
				$contrato = read('contrato',"WHERE tipo='2' AND Month(inicio)='$mesano[0]' AND Year(inicio)='$mesano[1]' AND consultor ='$consultor'");
				$contrato90=0;
				if($contrato){
					$valor_total = '0';
					foreach($contrato as $mostracontrato):
						$valor_total=$valor_total+$mostracontrato['valor'];
					endforeach;
					$contrato90=$valor_total;
				}
				
				
				// Orçamentos
				$contrato = read('contrato',"WHERE tipo='1' AND Month(orc_data)='$mesano[0]' AND Year(orc_data)='$mesano[1]' AND consultor ='$consultor'");
				$orcamento90=0;
				if($contrato){
					$valor_total = '0';
					foreach($contrato as $mostracontrato):
						$valor_total=$valor_total+$mostracontrato['orc_valor'];
					endforeach;
					$orcamento90=$valor_total;
				}

				 
				// 60 dias
				// contratos
				$mes = date('m/Y',strtotime('-2months'));
				$mesano = explode('/',$mes);
				$contrato = read('contrato',"WHERE tipo='2' AND Month(inicio)='$mesano[0]' AND Year(inicio)='$mesano[1]' AND consultor ='$consultor'");
				if($contrato){
					$valor_total = '0';
					foreach($contrato as $mostracontrato):
						$valor_total=$valor_total+$mostracontrato['valor'];
					endforeach;
					$contrato60=$valor_total;
				}
				
				
				// Orçamentos
				$contrato = read('contrato',"WHERE tipo='1' AND Month(orc_data)='$mesano[0]' AND Year(orc_data)='$mesano[1]' AND consultor ='$consultor'");
				if($contrato){
					$valor_total = '0';
					foreach($contrato as $mostracontrato):
						$valor_total=$valor_total+$mostracontrato['orc_valor'];
					endforeach;
					$orcamento60=$valor_total;
				}
				
				//-- 30 dias //---
				
				// contratos
				$mes = date('m/Y',strtotime('-1months'));
				$mesano = explode('/',$mes);
				
				$contrato = read('contrato',"WHERE tipo='2' AND Month(inicio)='$mesano[0]' AND Year(inicio)='$mesano[1]' AND consultor ='$consultor'");
				if($contrato){
					$valor_total = '0';
					foreach($contrato as $mostracontrato):
						$valor_total=$valor_total+$mostracontrato['valor'];
					endforeach;
					$contrato30=$valor_total;
				}
				
				
				// Orçamentos
				$contrato = read('contrato',"WHERE tipo='1' AND Month(orc_data)='$mesano[0]' AND Year(orc_data)='$mesano[1]' AND consultor ='$consultor'");
				if($contrato){
					$valor_total = '0';
					foreach($contrato as $mostracontrato):
						$valor_total=$valor_total+$mostracontrato['orc_valor'];
					endforeach;
					$orcamento30=$valor_total;
				}
				
				// atual
				// Serviços
				$mes = date('m/Y');
				$mesano = explode('/',$mes);
				$contrato = read('contrato',"WHERE tipo='2' AND Month(inicio)='$mesano[0]' AND Year(inicio)='$mesano[1]' AND consultor ='$consultor'");
				if($contrato){
					$valor_total = '0';
					foreach($contrato as $mostracontrato):
						$valor_total=$valor_total+$mostracontrato['valor'];
					endforeach;
					$contrato00=$valor_total;
				}

				
				// Orçamentos
				$contrato = read('contrato',"WHERE tipo='1' AND Month(orc_data)='$mesano[0]' AND Year(orc_data)='$mesano[1]' AND consultor ='$consultor'");
				if($contrato){
					$valor_total = '0';
					foreach($contrato as $mostracontrato):
						$valor_total=$valor_total+$mostracontrato['orc_valor'];
					endforeach;
					$orcamento00=$valor_total;
				}

			 	
				 // tres de visitas e pageviews do site
				$tres = date('m/Y',strtotime('-3months'));
				$dois = date('m/Y',strtotime('-2months'));
				$um = date('m/Y',strtotime('-1months'));
				$atual = date('m/Y');
			 							
			?>	
            
            
<section class="content">

  <div class="row">
   <div class="col-xs-12 col-md-12">  
     <div class="box box-default">

 			<script type="text/javascript">
			 google.load("visualization", "1", {packages:["corechart"]});
			  google.setOnLoadCallback(drawChart);
			  function drawChart() {
				var data = google.visualization.arrayToDataTable([
				  ['Mes',  'Orçamento', 'Contrato'],
				  ['<?php echo $tres;?>', <?php echo $orcamento90;?>, <?php echo $contrato90;?>],
				  ['<?php echo $dois;?>', <?php echo $orcamento60;?>, <?php echo $contrato60;?>],
				  ['<?php echo $um;?>',  <?php echo $orcamento30;?>,  <?php echo $contrato30;?>],
				  ['<?php echo $atual;?>', <?php echo $orcamento00;?>, <?php echo $contrato00;?>]
				]);
				var options = {
				  title: 'Orçamento - Contrato',
				  hAxis: {title: 'Mes', titleTextStyle: {color: 'red'}}
				};
				var chart = new google.visualization.ColumnChart(document.getElementById('vendas'));
				chart.draw(data, options);
			  }
			</script>

 
		<div class="row">
            <div class="col-md-12">
              <div class="box">
                    <div class="box-header">
                        <div id="vendas"></div><!--/chat do google-->
                    </div><!-- /.box-header -->
                </div><!-- /.box  -->
            </div>
		 </div>
    
    </div> <!-- /.box box-default-->
    
  </div> <!-- /.col-md-12 -->

 </div> <!-- /. row -->
</section> <!-- /.content --> 

 </div> <!-- /. row -->