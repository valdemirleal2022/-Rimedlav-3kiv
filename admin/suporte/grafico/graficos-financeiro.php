<?php

	if(isset($_POST['data1'])){
		$data1 = $_POST['data1'];
	}else{
		$data1 = date( "Y/m/d" );
	}
	if(isset($_POST['data2'])){
		$data2 = $_POST['data2'];
	}else{
		$data2 = date( "Y/m/d" );
	}

?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Financeiro</h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li>Gráfico</a></li>
            <li>Financeiro</a></li>
         </ol>
 </section>
 
<section class="content">
 
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">

      	<div class="box-header">

              <div class="col-xs-10 col-md-7 pull-right">
                    
               <form name="form-pesquisa" method="post" class="form-inline" role="form">
				   
			 		   <div class="form-group pull-left">
                            <input name="data1" type="date" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                        </div><!-- /.input-group -->
				   
				   		 <div class="form-group pull-left">
                            <input name="data2" type="date" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                        </div><!-- /.input-group -->
						 
				   
			 			<div class="form-group pull-left">
                         	<button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar" title="Pesquisar">
                        	 <i class="fa fa-search"></i></button>
                         </div><!-- /.input-group -->
                   
                    </form> 
                  </div><!-- /col-xs-10 col-md-7 pull-right-->
 
       </div><!-- /box-header-->   
 
   		<?php
             
                $receitas = soma('receber',"WHERE vencimento>='$data1' AND 
                                       vencimento<='$data2'",'valor');
                $receitas_aberta = soma('receber',"WHERE vencimento>='$data1' AND 
                                       vencimento<='$data2' AND 
                                        status='Em Aberto'",'valor');
                $receitas_paga = 	$receitas-	$receitas_aberta;	
			?>

        <section>
           <div class="row">   
              <div class="col-md-12"> 
              
                <div class="col-md-8">   
                     <div class="info-box bg-blue">
                            <span class="info-box-icon"><i class="ion ion-pie-graph"></i></span>
                            <div class="info-box-content">
                              <span class="info-box-text">Receita</span>
                              <span class="info-box-number"><?php echo converteValor($receitas);?></span>
                              <div class="progress">
                              <?php
                                 $percentual=($receitas_paga/$receitas)*100;
                                 $percentual=intval($percentual);
                                ?>	
                                <div class="progress-bar" style="width: <?php echo $percentual;?>%"></div>
                              </div>
                              <span class="progress-description">
                                 <?php echo $percentual. '%  Pagos R$ '. converteValor($receitas_paga);?>
                              </span>
                            </div><!-- /.info-box-content -->
                      </div><!-- /.info-box -->
                  </div> <!-- /.col-md-6 -->
                  
                </div> <!-- /.col-md-12 ---->
               </div> <!-- /.row ---->
            </section><!-- /.content -->
   
			   <?php
               
                $despesas = soma('pagar',"WHERE vencimento>='$data1' AND 
                                        vencimento<='$data2'",'valor');
                $despesas_aberta = soma('pagar',"WHERE vencimento>='$data1' AND 
                                        vencimento<='$data2' AND 
                                        status='Em Aberto'",'valor');
										
				$despesas_paga = soma('pagar',"WHERE pagamento>='$data1' AND 
                                        pagamento<='$data2' AND 
                                        status<>'Em Aberto'",'valor');
 				
            ?>

           <section>
            <div class="row">   
              <div class="col-md-12"> 
              
                <div class="col-md-8">   
                     <div class="info-box bg-red">
                            <span class="info-box-icon"><i class="ion ion-pie-graph"></i></span>
                            <div class="info-box-content">
                              <span class="info-box-text">Despesas</span>
                              <span class="info-box-number"><?php echo converteValor($despesas);?></span>
                              <div class="progress">
                                <?php
                                 $percentual=($despesas_paga/$despesas)*100;
                                 $percentual=intval($percentual);
                                ?>	
                                <div class="progress-bar" style="width: <?php echo $percentual;?>%"></div>
                              </div>
                              <span class="progress-description">
                                 <?php echo $percentual. '%  Pagos R$ '. converteValor($despesas_paga);?>
                              </span>
                            </div><!-- /.info-box-content -->
                      </div><!-- /.info-box -->
                  </div> <!-- /.col-md-6 -->
                  
                 </div> <!-- /.col-md-12 ---->
               </div> <!-- /.row ---->
            </section><!-- /.content -->
 

   </div> <!-- /.col-md-12 ---->
</div> <!-- /.row ---->
</section><!-- /.content -->

            <?php  
				$mes = date('m/Y',strtotime('-1months'));
				$mesano = explode('/',$mes);
 
			
			 	// Receita & Despesas
			    // 90 dias
				$mes = date('m/Y',strtotime('-3months'));
				$mesano = explode('/',$mes);
				$receita = read('receber',"WHERE Month(pagamento)='$mesano[0]' AND Year(pagamento)='$mesano[1]'");
				$receitas90=0;
				if($receita){
					$valor_total = '0';
					foreach($receita as $mostrareceita):
						$valor_total=$valor_total+$mostrareceita['valor'];
					endforeach;
					$receitas90=$valor_total;
				}
				// Atrasados
				$atraso = read('receber',"WHERE Month(vencimento)='$mesano[0]' AND Year(vencimento)='$mesano[1]' AND status='Em Aberto'");
				$atrasos90=0;
				if($atraso){
					$valor_total = '0';
					foreach($atraso as $mostraatraso):
						$valor_total=$valor_total+$mostraatraso['valor'];
					endforeach;
					$atrasos90=$valor_total;
				}
				// Atrasados
				$despesa = read('pagar',"WHERE Month(pagamento)='$mesano[0]' AND Year(pagamento)='$mesano[1]'");
				$despesas90=0;
				if($despesa){
					$valor_total = '0';
					foreach($despesa as $mostradespesa):
						$valor_total=$valor_total+$mostradespesa['valor'];
					endforeach;
					$despesas90=$valor_total;
				}
				// 60 dias
				// Receitas
				$mes = date('m/Y',strtotime('-2months'));
				$mesano = explode('/',$mes);
				$receita = read('receber',"WHERE Month(pagamento)='$mesano[0]' AND Year(pagamento)='$mesano[1]'");
				$receitas60=0;
				if($receita){
					$valor_total = '0';
					foreach($receita as $mostrareceita):
						$valor_total=$valor_total+$mostrareceita['valor'];
					endforeach;
					$receitas60=$valor_total;

				}
				// Atrasados
				$atraso = read('receber',"WHERE Month(vencimento)='$mesano[0]' AND Year(vencimento)='$mesano[1]' AND status='Em Aberto'");
				$atrasos60=0;
				if($atraso){
					$valor_total = '0';
					foreach($atraso as $mostraatraso):
						$valor_total=$valor_total+$mostraatraso['valor'];
					endforeach;
					$atrasos60=$valor_total;
				}
				// Despesas
				$despesa = read('pagar',"WHERE Month(pagamento)='$mesano[0]' AND Year(pagamento)='$mesano[1]'");
				$despesas60=0; 
				if($despesa){
					$valor_total = '0';
					foreach($despesa as $mostradespesa):
						$valor_total=$valor_total+$mostradespesa['valor'];
					endforeach;
					$despesas60=$valor_total;
				}
				
				// 30 dias
				// Receitas
				$mes = date('m/Y',strtotime('-1months'));
				$mesano = explode('/',$mes);
				$receita = read('receber',"WHERE Month(pagamento)='$mesano[0]' AND Year(pagamento)='$mesano[1]'");
				$receitas30=0;
				if($receita){
					$valor_total = '0';
					foreach($receita as $mostrareceita):
						$valor_total=$valor_total+$mostrareceita['valor'];
					endforeach;
					$receitas30=$valor_total;
				}
				// Atrasados
				$atraso = read('receber',"WHERE Month(vencimento)='$mesano[0]' AND Year(vencimento)='$mesano[1]' AND status='Em Aberto'");
				$atrasos30=0;
				if($atraso){
					$valor_total = '0';
					foreach($atraso as $mostraatraso):
						$valor_total=$valor_total+$mostraatraso['valor'];
					endforeach;
					$atrasos30=$valor_total;
				}
				// Despesas
				$despesa = read('pagar',"WHERE Month(pagamento)='$mesano[0]' AND Year(pagamento)='$mesano[1]'");
				$despesas30=0; 
				if($despesa){
					$valor_total = '0';
					foreach($despesa as $mostradespesa):
						$valor_total=$valor_total+$mostradespesa['valor'];
					endforeach;
					$despesas30=$valor_total;
				}
				// atual
				$mes = date('m/Y');
				$mesano = explode('/',$mes);
				$receita = read('receber',"WHERE Month(pagamento)='$mesano[0]' AND Year(pagamento)='$mesano[1]'");
				$receitas00=0;
				if($receita){
					$valor_total = '0';
					foreach($receita as $mostrareceita):
						$valor_total=$valor_total+$mostrareceita['valor'];
					endforeach;
					$receitas00=$valor_total;
				}
				// Atrasados
				$atraso = read('receber',"WHERE Month(vencimento)='$mesano[0]' AND Year(vencimento)='$mesano[1]' AND status='Em Aberto'");
				$atrasos00=0;
				if($atraso){
					$valor_total = '0';
					$data=date('Y-m-d');
					foreach($atraso as $mostraatraso):
						if($mostraatraso['vencimento']<$data){
							$valor_total=$valor_total+$mostraatraso['valor'];
						}
					endforeach;
					$atrasos00=$valor_total;
				}
				// Despesas
				$despesa = read('pagar',"WHERE Month(pagamento)='$mesano[0]' AND Year(pagamento)='$mesano[1]'");
				$despesas00=0; 
				if($despesa){
					$valor_total = '0';
					foreach($despesa as $mostradespesa):
						$valor_total=$valor_total+$mostradespesa['valor'];
					endforeach;
					$despesas00=$valor_total;
				}

				
				// Previsao - Receita & Despesas
			    // 90 dias
				//Receber
				$mes90 = date('m/Y',strtotime('+3months'));
				$mesano90 = explode('/',$mes90);
				$receita = read('receber',"WHERE Month(vencimento)='$mesano90[0]' AND Year(vencimento)='$mesano90[1]'");
				$receber90=0;
				if($receita){
					$valor_total = '0';
					foreach($receita as $mostrareceita):
						$valor_total=$valor_total+$mostrareceita['valor'];
					endforeach;
					$receber90=$valor_total;
				}
				// Pagar
				$despesa = read('pagar',"WHERE Month(vencimento)='$mesano90[0]' AND Year(vencimento)='$mesano90[1]'");
				$pagar90=0;
				if($despesa){
					$valor_total = '0';
					foreach($despesa as $mostradespesa):
						$valor_total=$valor_total+$mostradespesa['valor'];
					endforeach;
					$pagar90=$valor_total;
				}
				// 60 dias
				// Receitas
				$mes60 = date('m/Y',strtotime('+2months'));
				$mesano60 = explode('/',$mes60);
				$receita = read('receber',"WHERE Month(vencimento)='$mesano60[0]' AND Year(vencimento)='$mesano60[1]'");
				$receber60=0;
				if($receita){
					$valor_total = '0';
					foreach($receita as $mostrareceita):
						$valor_total=$valor_total+$mostrareceita['valor'];
					endforeach;
					$receber60=$valor_total;
				}
				// Pagar
				$despesa = read('pagar',"WHERE Month(vencimento)='$mesano60[0]' AND Year(vencimento)='$mesano90[1]'");
				$pagar60=0;
				if($despesa){
					$valor_total = '0';
					foreach($despesa as $mostradespesa):
						$valor_total=$valor_total+$mostradespesa['valor'];
					endforeach;
					$pagar60=$valor_total;
				}
				
				// 30 dias
				// Receitas
				$mes30 = date('m/Y',strtotime('+1months'));
				$mesano30 = explode('/',$mes30);
				$receita = read('receber',"WHERE Month(vencimento)='$mesano30[0]' AND Year(vencimento)='$mesano30[1]'");
				$receber30=0;
				if($receita){
					$valor_total = '0';
					foreach($receita as $mostrareceita):
						$valor_total=$valor_total+$mostrareceita['valor'];
					endforeach;
					$receber30=$valor_total;
				}
				// Pagar
				$despesa = read('pagar',"WHERE Month(vencimento)='$mesano30[0]' AND Year(vencimento)='$mesano30[1]'");
				$pagar30=0;
				if($despesa){
					$valor_total = '0';
					foreach($despesa as $mostradespesa):
						$valor_total=$valor_total+$mostradespesa['valor'];
					endforeach;
					$pagar30=$valor_total;
				}
				// atual
				// Receitas
				$mes00 = date('m/Y');
				$mesano00 = explode('/',$mes00);
				$receita = read('receber',"WHERE Month(vencimento)='$mesano00[0]' AND Year(vencimento)='$mesano00[1]' AND status<>'Baixado'");
				$receber00=0;
				if($receita){
					$valor_total = '0';
					foreach($receita as $mostrareceita):
						$valor_total=$valor_total+$mostrareceita['valor'];
					endforeach;
					$receber00=$valor_total;
				}
				// Pagar
				$despesa = read('pagar',"WHERE Month(vencimento)='$mesano00[0]' AND Year(vencimento)='$mesano00[1]' AND status<>'Baixado'");
				$pagar00=0;
				if($despesa){
					$valor_total = '0';
					foreach($despesa as $mostradespesa):
						$valor_total=$valor_total+$mostradespesa['valor'];
					endforeach;
					$pagar00=$valor_total;
				}
				
				
				// tres de visitas e pageviews do site
				$tres = date('m/Y',strtotime('-3months'));
				$tresEx = explode('/',$tres);
				$lerTres = read('visitas',"WHERE mes = '$tresEx[0]' AND ano = '$tresEx[1]'");
				if($lerTres){
					foreach($lerTres as $resTres);
					$visitasT = $resTres['visitas'];
					$pagesT = $resTres['pageviews'];
				}else{
					$visitasT = '0';
					$pagesT = '0'; 	
				}
				// dois de visitas e pageviews do site
				$dois = date('m/Y',strtotime('-2months'));
				$doisEx = explode('/',$dois);
				$lerDois = read('visitas',"WHERE mes = '$doisEx[0]' AND ano = '$doisEx[1]'");
				if($lerDois){
					foreach($lerDois as $resDois);
					$visitasD = $resDois['visitas'];
					$pagesD = $resDois['pageviews'];
				}else{
					$visitasD = '0';
					$pagesD = '0'; 	
				}
				// um de visitas e pageviews do site
				$um = date('m/Y',strtotime('-1months'));
				$umEx = explode('/',$um);
				$lerUm = read('visitas',"WHERE mes = '$umEx[0]' AND ano = '$umEx[1]'");
				if($lerUm){
					foreach($lerUm as $resUm);
					$visitasU = $resUm['visitas'];
					$pagesU = $resUm['pageviews'];
				}else{
					$visitasU = '0';
					$pagesU = '0'; 	
				}
				// atual de visitas e pageviews do site
				$atual = date('m/Y');
				$atualEx = explode('/',$atual);
				$lerAtual = read('visitas',"WHERE mes = '$atualEx[0]' AND ano = '$atualEx[1]'");
				if($lerAtual){
					foreach($lerAtual as $resAtual);
					$visitas = $resAtual['visitas'];
					$pages = $resAtual['pageviews'];
				}else{
					$visitas = '0';
					$pages = '0'; 	
				}
		
			?>	

            
           
 			<script type="text/javascript">
			 google.load("visualization", "1", {packages:["corechart"]});
			  google.setOnLoadCallback(drawChart);
			  function drawChart() {
				var data = google.visualization.arrayToDataTable([
				  ['Mes',  'Receitas', 'Despesas', 'Atrasados'],
				  ['<?php echo $tres;?>', <?php echo $receitas90;?>, <?php echo $despesas90;?>,<?php echo $atrasos90;?>],
				  ['<?php echo $dois;?>', <?php echo $receitas60;?>, <?php echo $despesas60;?>, <?php echo $atrasos60;?>],
				  ['<?php echo $um;?>',  <?php echo $receitas30;?>, <?php echo $despesas30;?>, <?php echo $atrasos30;?>,],
				  ['<?php echo $atual;?>', <?php echo $receitas00;?>, <?php echo $despesas00;?>, <?php echo $atrasos00;?>,]
				]);
				var options = {
				  title: 'Previsao de Receita & Despesas:',hAxis: {title: 'Mes', titleTextStyle: {color: 'red'}}
				};
				var chart = new google.visualization.ColumnChart(document.getElementById('previsao'));
				chart.draw(data, options);
			  }
			</script>
            
 <section class="content">
    <div class="row">
        <div class="col-md-6">
          <div class="box">
                <div class="box-header">
                    <div id="previsao"></div><!--/chat do google-->
                </div><!-- /.box-header -->
            </div><!-- /.box  -->
        </div>
 
 			<script type="text/javascript">
			 google.load("visualization", "1", {packages:["corechart"]});
			  google.setOnLoadCallback(drawChart);
			  function drawChart() {
				var data = google.visualization.arrayToDataTable([
				  ['Mes',  'Receber', 'Pagar'],
				  ['<?php echo $mes00;?>', <?php echo $receber00;?>, <?php echo $pagar00;?>],
				  ['<?php echo $mes30;?>', <?php echo $receber30;?>, <?php echo $pagar30;?>],
				  ['<?php echo $mes60;?>', <?php echo $receber60;?>, <?php echo $pagar60;?>],
				  ['<?php echo $mes90;?>', <?php echo $receber90;?>, <?php echo $pagar90;?>],
				]);
				var options = {title: 'Próximo Trimestre:',hAxis: {title: 'Mes', titleTextStyle: {color: 'red'}}
				};
				var chart = new google.visualization.ColumnChart(document.getElementById('trimestre'));
				chart.draw(data, options);
			  }
			</script>
           
 
       <div class="col-md-6">   
          <div class="box">
         	<div class="box-header">
        		 <div id="trimestre"></div><!--/chat do google-->
          	</div><!-- /.box-header -->
    	  </div><!-- /.box  -->
       </div><!-- /.col-md-6  -->
  		     
      
          
       </div><!-- /.box  -->

  
  </section><!-- /.content -->
	   
<section>
 <div class="row">   
  	<div class="col-md-12"> 

      <?php

			$contador=0;
			$nome = array();
			$valor = array();
			$leitura = read('contrato_tipo',"WHERE id ORDER BY nome ASC");
			if($leitura){
				foreach($leitura as $mostra):
				
					$nomePagamento= $mostra['nome'];
					$PagamentoId = $mostra['id'];
		  
					$total= soma('receber',"WHERE status='Baixado' AND pagamento>='$data1' AND pagamento<='$data2' AND contrato_tipo='$PagamentoId'",'valor');
					if($total<>0){
						$nome[$contador] = $nomePagamento;
						$valor[$contador] = $total; 
						$contador++;
					}
				endforeach;
			}
		  ?>
          <script type="text/javascript">
			  google.load("visualization", "1", {packages:["corechart"]});
			  google.setOnLoadCallback(drawChart);
			  function drawChart() {
				var data = google.visualization.arrayToDataTable([
				  ['Vendas', 'Mes Atual'],
				  	    <?php
						$contador=0;
						for($x=0; $x< count($nome); $x++){
							echo "[ '".$nome[$contador]."'," .$valor[$contador]. "],";
							$contador++;
					 	}; 
					?>
				]);
				var options = {
				   is3D: true,
				   legend:{position: 'right', textStyle: {color: 'black', fontSize: 8}},
				   title: 'Tipo de Contrato'
				};
				var chart = new google.visualization.PieChart(document.getElementById('forma'));
				chart.draw(data, options);
			  }
			</script>
            
          <div class="col-md-6">   
          	<div class="box">
         		<div class="box-header">
        		  <div id="forma"></div><!--/chat do google-->
          		</div><!-- /.box-header -->
    		</div><!-- /.box  -->
    	 </div><!-- /.col-md-6  -->
         

    	<?php 

			$contador=0;
			$nome = array();
			$valor = array();
			$leitura = read('banco',"WHERE id ORDER BY id ASC");
			if($leitura){
				foreach($leitura as $mostra):
					$nomeBanco= $mostra['nome'];
					$BancoId = $mostra['id'];
		  
					$total= soma('receber',"WHERE status='Baixado' AND pagamento>='$data1' AND vencimento<='$data2' AND banco='$BancoId'",'valor');
					if($total<>0){
						$nome[$contador] = $nomeBanco;
						$valor[$contador] = $total; 
						$contador++;
					}
				endforeach;
			}
		  ?>
          <script type="text/javascript">
			  google.load("visualization", "1", {packages:["corechart"]});
			  google.setOnLoadCallback(drawChart);
			  function drawChart() {
				var data = google.visualization.arrayToDataTable([
				  ['Vendas', 'Mes Atual'],
				  	    <?php
						$contador=0;
						for($x=0; $x< count($nome); $x++){
							echo "[ '".$nome[$contador]."'," .$valor[$contador]. "],";
							$contador++;
					 	}; 
					?>
				]);
				var options = {
				   is3D: true,
				   legend:{position: 'right', textStyle: {color: 'black', fontSize: 8}},
				   title: 'Bancos'
				};
				var chart = new google.visualization.PieChart(document.getElementById('Banco'));
				chart.draw(data, options);
			  }
			</script>
            
	<div class="col-md-6">   
				<div class="box">
					<div class="box-header">
					  <div id="Banco"></div><!--/chat do google-->
					</div><!-- /.box-header -->
				</div><!-- /.box  -->
	 </div><!-- /.col-md-6  -->
		
		

  </div><!-- /.col-md-12"  -->
</section><!-- /.content -->
</div><!-- /.content-wrapper -->