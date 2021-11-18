<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}
	
	$data1 = converteData1();
	$data2 = converteData2();

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$conta = $_POST[ 'conta' ];
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['conta']=$conta;
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-despesas-programadas-pdf");';
		echo '</script>';
	}

	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$conta = $_POST[ 'conta' ];
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['conta']=$conta;
		header( 'Location: ../admin/suporte/relatorio/relatorio-despesas-programadas-excel.php' );
	}

	if ( isset( $_POST[ 'pesquisar' ] ) ) {
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$conta = $_POST[ 'conta' ];
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
	}

	$leitura = read('pagar',"WHERE status='Em Aberto' AND programacao>='$data1' AND programacao<='$data2'
					ORDER BY programacao ASC");
	$total = conta('pagar',"WHERE status='Em Aberto' AND programacao>='$data1' AND programacao<='$data2'
					ORDER BY programacao ASC");
	$valor_total = soma('pagar',"WHERE status='Em Aberto' AND programacao>='$data1' AND programacao<='$data2'
					ORDER BY programacao ASC",'valor');
		
 
	$_SESSION['url']=$_SERVER['REQUEST_URI'];

?>

<div class="content-wrapper">
      
  <section class="content-header">
     <h1>Programação</h1>
     <ol class="breadcrumb">
     	<li>Home</a>
     	<li>Contas a Pagar</a>
     	<li class="active">Programação</li>
     </ol>
 </section>

 
 <section class="content">
   <div class="row">
    <div class="col-xs-12">
		
     <div class="box box-default">
			 
      
         		 <div class="box-header"> 

                    <!--PESQUISA DE RELATORIO-->
                    <div class="col-xs-10 col-md-9 pull-right">
                        <form name="form-pesquisa" method="post" class="form-inline" role="form">
							
                           <div class="form-group pull-left">
                            <input type="date" name="inicio" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                         </div>   <!-- /.input-group -->
                            
                         <div class="form-group pull-left">
                             <input type="date" name="fim" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                         </div>  <!-- /.input-group -->
							
                      
                       <!-- /.input-group -->
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar"><i class="fa fa-search"></i></button>
                            </div>
                            <!-- /.input-group -->
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf"><i class="fa fa-file-pdf-o"></i></button>
                            </div>
                            <!-- /.input-group -->
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel"><i class="fa fa-file-excel-o"></i></button>
                            </div>
                            <!-- /.input-group -->

                	    </form>
                    </div><!-- /col-xs-10 col-md-5 pull-right-->
					 
  					</div>  <!-- /box-header-->

    
       <div class="box-body table-responsive data-spy='scroll'">
     	<div class="col-md-12 scrool">  
			<div class="box-body table-responsive">

	<?php 


	$datamovimento='';
	$totalmovimento=0;
	$totalregistros=0;
	
	if($leitura){
		echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Fornecedor</td>
					<td align="center">Valor</td>
					<td align="center">Vencimento</td>
					<td align="center">Programação</td>
					<td align="center">Parcela</td>
					<td align="center">Descrição</td>
					<td align="center" colspan="6">Gerenciar</td>
		 </tr>';
		foreach($leitura as $mostra):

			   if(empty($datamovimento)){
					$datamovimento=$mostra['programacao'];
						   
			   }
		
			   if($datamovimento<>$mostra['programacao']){
							echo '<tr>';				
							echo '<td></td>';
							echo '<td align="center">Total</td>';
							echo '<td align="right">'.converteValor($totalmovimento).'</td>';
							echo '<td></td>';
				   			echo '<td></td>';
							echo '<td></td>';
							echo '<td></td>';
				   			echo '<td></td>';
							echo '<td></td>';
							echo '<td></td>';
				   			echo '<td></td>';
							echo '<td></td>';
							echo '</tr>';
					        $datamovimento=$mostra['programacao'];
					        $totalmovimento=$mostra['valor'];
					}else{
						$totalmovimento=$totalmovimento+$mostra['valor'];
					}
	 
		 	echo '<tr>';
				
				echo '<td align="center">'.$mostra['id'].'</td>';
		
				$fornecedorId = $mostra['fornecedor'];
				$fornecedor = mostra('estoque_fornecedor',"WHERE id ='$fornecedorId '");
		
				if(!$fornecedor){
					echo '<td align="center">-</td>';
				}else{
					echo '<td>'.substr($fornecedor['nome'],0,35).'</td>';
				}
				
				echo '<td align="right">'.converteValor($mostra['valor']).'</td>';
				echo '<td align="center">'.converteData($mostra['vencimento']).'</td>';
				echo '<td align="center">'.converteData($mostra['programacao']).'</td>';
				echo '<td>'.$mostra['parcela'].'</td>';
				echo '<td>'.substr($mostra['descricao'],0,25).'</td>';
				
				echo '<td align="center">
					<a href="painel.php?execute=suporte/pagar/despesa-editar&pagamentoEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
              			</a>
				      </td>';
				echo '<td align="center">
					<a href="painel.php?execute=suporte/pagar/despesa-editar&pagamentoBaixar='.$mostra['id'].'">
			  				<img src="ico/baixar.png" alt="Baixar" title="Baixar" class="tip" />
              			</a>
				      </td>';
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/pagar/despesa-editar&pagamentoDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png" alt="Excluir" title="Excluir" class="tip" />
              			</a>
						</td>';
		
				$pdf='../uploads/pagamentos/boletos/'.$mostra['id'].'.pdf';
				if(file_exists($pdf)){
					echo '<td align="center">
						<a href="../uploads/pagamentos/boletos/'.$mostra['id'].'.pdf" target="_blank">
							<img src="ico/pdf.png" alt="Boleto" title="Boleto" />
              			</a>
						</td>';	
				}else{
					echo '<td align="center">-</td>';	
				}
				
				$pdf='../uploads/pagamentos/comprovantes/'.$mostra['id'].'.pdf';
				if(file_exists($pdf)){
					echo '<td align="center">
						<a href="../uploads/pagamentos/comprovantes/'.$mostra['id'].'.pdf" target="_blank">
							<img src="ico/pdf.png" alt="Comprovante" title="Comprovante" />
              			</a>
						</td>';	
				}else{
					echo '<td align="center">-</td>';	
				}
		
					// imprimir ordem
				echo '<td align="center">
						<a href="painel.php?execute=suporte/pagar/ordem-pagamento&ordemImprimir='.$mostra['id'].'" target="_blank">
							<img src="ico/imprimir.png" title="Imprimir"  />
						</a>
					 </td>';	
				
			echo '</tr>';
				
		 endforeach;
		
		  
							echo '<tr>';				
							echo '<td></td>';
							echo '<td align="center">Total</td>';
							echo '<td align="right">'.converteValor($totalmovimento).'</td>';
							echo '<td></td>';
				   			echo '<td></td>';
							echo '<td></td>';
							echo '<td></td>';
				   			echo '<td></td>';
							echo '<td></td>';
							echo '<td></td>';
				   			echo '<td></td>';
							echo '<td></td>';
							echo '</tr>';
					      
		 
		 echo '<tfoot>';
				echo '<tr>';
                	echo '<td colspan="13">' . 'Total de Registros : ' .  $total . '</td>';
                echo '</tr>';
                echo '<tr>';
                	echo '<td colspan="13">' . 'Valor Total R$ : ' . converteValor($valor_total) . '</td>';
                echo '</tr>';
          echo '</tfoot>';
		 
		echo '</table>';

	}
?>

	
   		<div class="box-footer">
            <?php echo $_SESSION['cadastro'];
			unset($_SESSION['cadastro']);
			?>
       </div><!-- /.box-footer-->

	  </div><!-- /.box-body table-responsive -->
    </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 
 </section><!-- /.content -->
  

<?php

	$mes = date('m/Y');
	$mesano = explode('/',$mes);

	$despesas = soma('pagar',"WHERE Month(vencimento)='$mesano[0]' AND 
							Year(vencimento)='$mesano[1]'",'valor');

	$totalQuitado = soma('pagar',"WHERE Month(vencimento)='$mesano[0]' AND 
							Year(vencimento)='$mesano[1]' AND 
							status<>'Em Aberto'",'valor');

	$despesas_aberta = $despesas- $totalQuitado;
 		
?>

<section>
 <div class="row">   
  	<div class="col-md-12"> 
		
		 <div class="col-md-6">   
			 <div class="info-box bg-red">
					<span class="info-box-icon"><i class="ion ion-pie-graph"></i></span>
					<div class="info-box-content">
					  <span class="info-box-text">Despesas</span>
					  <span class="info-box-number"><?php echo converteValor($despesas);?></span>
					  <div class="progress">
						<?php
						 $percentual=($totalQuitado/$despesas)*100;
						 $percentual=intval($percentual);
						  $saldo=$despesas-$despesas_aberta;
						?>	
						<div class="progress-bar" style="width: <?php echo $percentual;?>%"></div>
					  </div>
					  <span class="progress-description">
						 <?php echo $percentual. '%  Pagos R$ '. converteValor($saldo) . '   || Saldo a pagar R$ '. converteValor($despesas_aberta) ;?>
					  </span>
					</div><!-- /.info-box-content -->
			  </div><!-- /.info-box -->
			 </div><!-- /.col-md-6 -->
		
			<?php
			   $mes = date('m/Y');
			   $mesano = explode('/',$mes);
			   $receitas = soma('receber',"WHERE Month(vencimento)='$mesano[0]' AND 
													Year(vencimento)='$mesano[1]'",'valor');
			   $receitas_aberta = soma('receber',"WHERE Month(vencimento)='$mesano[0]' AND 
													Year(vencimento)='$mesano[1]' AND 
													status='Em Aberto'",'valor');
			   $receitas_paga = $receitas-$receitas_aberta;	
			?>

			 <div class="col-md-6">   	
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
                                 <?php echo $percentual. '%  Quitados R$ '. converteValor($receitas_paga) . '   || Saldo a Receber R$ '. converteValor($receitas_aberta) ;?>
                              </span>
                            </div><!-- /.info-box-content -->
                      </div><!-- /.info-box -->
			 </div><!-- /.col-md-6-->
			 

		

		<?php
		
			$mes = date('m/Y');
            $mesano = explode('/',$mes);
		
			$contador=0;
			$nome = array();
			$valor = array();
		
			$leitura = read('banco',"WHERE id ORDER BY id ASC");
			if($leitura){
				foreach($leitura as $mostraVend):
					$nomeConta= $mostraVend['nome'];
					$Id = $mostraVend['id'];
					$total = soma('pagar',"WHERE Month(vencimento)='$mesano[0]' AND Year(vencimento)='$mesano[1]' 
									AND status='Em Aberto' AND banco='$Id'",'valor');
					if($total<>0){
						$nome[$contador] = $nomeConta;
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
				  ['Banco', 'Mes Atual'],
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
				   title: 'Despesas por Banco'
				};
				var chart = new google.visualization.PieChart(document.getElementById('banco'));
				chart.draw(data, options);
			  }
			</script>
            
          <div class="col-md-6">   
          	<div class="box">
         		<div class="box-header">
        		  <div id="banco"></div><!--/chat do google-->
          		</div><!-- /.box-header -->
    		</div><!-- /.box  -->
    	 </div><!-- /.col-md-6  -->


		<?php
		
			$mes = date('m/Y');
            $mesano = explode('/',$mes);
		
			$contador=0;
			$nome = array();
			$valor = array();
		
			$leitura = read('formpag',"WHERE id ORDER BY id ASC");
			if($leitura){
				foreach($leitura as $mostraVend):
					$nomeConta= $mostraVend['nome'];
					$Id = $mostraVend['id'];
					$total = soma('pagar',"WHERE Month(vencimento)='$mesano[0]' AND Year(vencimento)='$mesano[1]' 
									AND status='Em Aberto' AND formpag='$Id'",'valor');
					if($total<>0){
						$nome[$contador] = $nomeConta;
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
				  ['Banco', 'Mes Atual'],
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
				   title: 'Despesas por Form Pag'
				};
				var chart = new google.visualization.PieChart(document.getElementById('formpag'));
				chart.draw(data, options);
			  }
			</script>
		

          <div class="col-md-6">   
          	<div class="box">
         		<div class="box-header">
        		  <div id="formpag"></div><!--/chat do google-->
          		</div><!-- /.box-header -->
    		</div><!-- /.box  -->
    	 </div><!-- /.col-md-6  -->

		
   		<?php
		
			$mes120= date('m/Y',strtotime('+4months'));
			$mesano = explode('/',$mes120);
			$pagar120 = soma('pagar',"WHERE status='Em Aberto' AND Month(vencimento)='$mesano[0]' AND 
							       Year(vencimento)='$mesano[1]'",'valor');
			$mes90 = date('m/Y',strtotime('+3months'));
			$mesano = explode('/',$mes90);
			$pagar90 = soma('pagar',"WHERE status='Em Aberto' AND Month(vencimento)='$mesano[0]' AND 
							       Year(vencimento)='$mesano[1]'",'valor');
									
			$mes60 = date('m/Y',strtotime('+2months'));
			$mesano = explode('/',$mes60);
			$pagar60 = soma('pagar',"WHERE status='Em Aberto' AND Month(vencimento)='$mesano[0]' AND 
							       Year(vencimento)='$mesano[1]'",'valor');
									
			$mes30 = date('m/Y',strtotime('+1months'));
			$mesano = explode('/',$mes30);
			$pagar30 = soma('pagar',"WHERE status='Em Aberto' AND Month(vencimento)='$mesano[0]' AND 
							       Year(vencimento)='$mesano[1]'",'valor');

			?> 

            <div class="col-lg-3 col-xs-4">
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?php echo (int)$pagar30;?></h3>
                  <p>Despesas</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
              <a href="#" class="small-box-footer"><?php echo $mes30;?> <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col-lg-3 col-xs-6-->

            
            <div class="col-lg-3 col-xs-4">
              <div class="small-box bg-green">
                <div class="inner">
                  <h3><?php echo (int)$pagar60;?></h3>
                  <p>Despesas</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i> 
                </div>
             <a href="#" class="small-box-footer"><?php echo $mes60;?> <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col-lg-3 col-xs-6-->

            
            <div class="col-lg-3 col-xs-4">
              <div class="small-box bg-yellow">
                <div class="inner">
                   <h3><?php echo (int)$pagar90;?></h3>
                  <p>Despesas</p>
                </div>
                <div class="icon">
                  <i class="ion ion-calculator"></i>
                </div>
              <a href="#" class="small-box-footer"><?php echo $mes90;?> <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col-lg-3 col-xs-6-->

            
            <div class="col-lg-3 col-xs-4">
              <div class="small-box bg-red">
                <div class="inner">
                   <h3><?php echo (int)$pagar120;?></h3>
                  <p>Despesas</p>
                </div>
                <div class="icon">
                  <i class="ion ion-card"></i>
                </div>
            <a href="#" class="small-box-footer"><?php echo $mes120;?> <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col-lg-3 col-xs-6-->
		
		
		<?php
		
			$mes = date('m/Y');
            $mesano = explode('/',$mes);
		
			$contador=0;
			$nome = array();
			$valor = array();
		
			$leitura = read('pagar_conta',"WHERE id ORDER BY id ASC");
			if($leitura){
				foreach($leitura as $mostraConta):
					$nomeConta= $mostraConta['nome'];
					$Id = $mostraConta['id'];
					$total = soma('pagar',"WHERE Month(vencimento)='$mesano[0]' AND Year(vencimento)='$mesano[1]' 
									AND status='Em Aberto' AND id_conta='$Id'",'valor');
					if($total<>0){
						$nome[$contador] = $nomeConta;
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
				  ['Contas', 'Mes Atual'],
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
				   title: 'Conta Despesas do Mes'
				};
				var chart = new google.visualization.PieChart(document.getElementById('conta'));
				chart.draw(data, options);
			  }
			</script>
            
          <div class="col-md-12">   
          	<div class="box">
         		<div class="box-header">
        		  <div id="conta"></div><!--/chat do google-->
          		</div><!-- /.box-header -->
    		</div><!-- /.box  -->
    	 </div><!-- /.col-md-6  -->



        </div> <!-- /.col-md-12 ---->
    </div> <!-- /.row ---->
 </section><!-- /.content -->

</div><!-- /.content-wrapper -->
