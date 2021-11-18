<?php 

if ( function_exists( 'ProtUser' ) ) {
    if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
        header( 'Location: painel.php?execute=suporte/403' );
    }
}

	$data1 = converteData1();
	$data2 = converteData2();

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$conta = $_POST['conta'];
		$banco = $_POST['banco'];
		
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['conta']=$conta;
		$_SESSION['banco']=$banco;
		
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-despesas-quitadas-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$conta = $_POST['conta'];
		$banco = $_POST['banco'];
		
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['conta']=$conta;
		$_SESSION['banco']=$banco;
		
		header( 'Location: ../admin/suporte/relatorio/relatorio-despesas-quitadas-excel.php' );
	}

	if ( isset( $_POST[ 'pesquisar' ] ) ) {
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$conta = $_POST['conta'];
		$banco = $_POST['banco'];
		
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['conta']=$conta;
		$_SESSION['banco']=$banco;
	}

	$valor_total =soma('pagar',"WHERE status<>'Em Aberto' AND pagamento>='$data1' AND pagamento<='$data2'",'valor');
	$total=conta('pagar',"WHERE status<>'Em Aberto' AND pagamento>='$data1' AND pagamento<='$data2'");
	$leitura=read('pagar',"WHERE status<>'Em Aberto' AND pagamento>='$data1' AND pagamento<='$data2' ORDER BY
						 pagamento ASC");

	if(!empty($conta)){
			$valor_total = soma('pagar',"WHERE status<>'Em Aberto' AND pagamento>='$data1' AND pagamento<='$data2' AND
										 id_conta='$conta'",'valor');
			$total = conta('pagar',"WHERE status<>'Em Aberto' AND pagamento>='$data1' AND pagamento<='$data2' AND
									id_conta='$conta'");
			$leitura = read('pagar',"WHERE status<>'Em Aberto' AND pagamento>='$data1' AND pagamento<='$data2' AND
									 id_conta='$conta' ORDER BY pagamento ASC");
	}

	if(!empty($banco)){
			$valor_total = soma('pagar',"WHERE status<>'Em Aberto' AND pagamento>='$data1' AND pagamento<='$data2' AND banco='$banco'",'valor');
			$total = conta('pagar',"WHERE status<>'Em Aberto' AND pagamento>='$data1' AND pagamento<='$data2' AND banco='$banco'");
			$leitura = read('pagar',"WHERE status<>'Em Aberto' AND pagamento>='$data1' AND pagamento<='$data2' AND banco='$banco' ORDER BY pagamento ASC");
	}


$_SESSION['url']=$_SERVER['REQUEST_URI'];

?>

<div class="content-wrapper">
      
  <section class="content-header">
     <h1>Despesas Quitadas</h1>
     <ol class="breadcrumb">
     	<li>Home</a>
     	<li>Site</a>
     	<li class="active">Despesas Quitadas</li>
     </ol>
 </section>
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
           
                 
			 <div class="box-header">
        		 <div class="col-xs-8 col-md-9 pull-right">
                    
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                       
                        <div class="form-group pull-left">
                            <input type="date" name="inicio" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                         </div>   <!-- /.input-group -->
                            
                         <div class="form-group pull-left">
                             <input type="date" name="fim" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                         </div>  <!-- /.input-group -->
					
                          <div class="form-group pull-left">
                        		 <select name="conta" class="form-control input-sm">
                                <option value="">Conta</option>
                            <?php 
                             $readConta = read('pagar_conta',"WHERE status='1' ORDER BY codigo ASC");
                                    if(!$readConta){
                                        echo '<option value="">Não temos Bancos no momento</option>';	
                                    }else{
                                        foreach($readConta as $mae):
                                           if($conta == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['codigo'].'-'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['codigo'].'-'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                            </select>   
                            </div><!-- /.input-group --> 
						 
						  <div class="form-group pull-left">
                        		 <select name="banco" class="form-control input-sm">
                                <option value="">Banco</option>
                                <?php 
                                    $readConta = read('banco',"WHERE status='1' ORDER BY nome ASC");
                                    if(!$readConta){
                                        echo '<option value="">Não temos registro no momento</option>';	
                                    }else{
                                        foreach($readConta as $mae):
                                           if($banco == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                            </select>   
                            </div><!-- /.input-group --> 
						   <div class="form-group pull-left">
                         	<button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar" title="Pesquisar">
                        	 <i class="fa fa-search"></i></button>
                         </div><!-- /.input-group -->
                          
                           <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf"><i class="fa fa-file-pdf-o" title="Relatório PDF"></i></button>
                            </div>  <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel"><i class="fa fa-file-excel-o" title="Relaório Excel"></i></button>
                            </div>   <!-- /.input-group -->
                            
                    </form> 
                 </div><!-- /col-xs-10 col-md-7 pull-right-->
                  
             </div>
        <!-- /.box-header -->
      

   <div class="box-body table-responsive data-spy='scroll'">
     	<div class="col-md-12 scrool">  
			<div class="box-body table-responsive">
     
	<?php 



	if($leitura){
			echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Descrição</td>
					<td align="center">Valor</td>
					<td align="center">Pagamento</td>
					<td align="center">Forma/Banco</td>
					<td align="center">Conta</td>
					<td align="center">Parcela</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
				
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
				echo '<td align="left">'.$mostra['id'].'</td>';
				echo '<td align="left">'.substr($mostra['descricao'],0,25).'</td>';
				echo '<td align="right">'.converteValor($mostra['valor']).'</td>';
				echo '<td align="left">'.date('d/m/Y',strtotime($mostra['pagamento'])).'</td>';
				$bancoId=$mostra['banco'];
				$banco = mostra('banco',"WHERE id ='$bancoId'");
				$formapagId=$mostra['formpag'];
				$formapag = mostra('formpag',"WHERE id ='$formapagId'");
				echo '<td align="left">'.$banco['nome']. "|".$formapag['nome'].'</td>';
				$contaId = $mostra['id_conta'];
				$contaMostra = mostra('pagar_conta',"WHERE id ='$contaId '");
				if(!$contaMostra){
					echo '<td align="left">Conta Nao Encontrado</td>';
				}else{
					echo '<td align="left">'.substr($contaMostra['nome'],0,30).'</td>';
				}
				 
				echo '<td align="left">'.$mostra['parcela'].'</td>';

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
		
			echo '</tr>';
		 endforeach;
		 
		  echo '<tfoot>';
				echo '<tr>';
                	echo '<td colspan="13">' . 'Total Listado : ' .  $total . '</td>';
                echo '</tr>';
                echo '<tr>';
                	echo '<td colspan="13">' . 'Total de Despesas R$ : ' . converteValor($valor_total) . '</td>';
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
	  </div><!-- /.col-md-12 scrool -->
 	  </div><!-- /.box-body table-responsive data-spy='scroll' -->
 	  	  
    </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->

 </section><!-- /.content -->

<section>
 <div class="row">   
  	<div class="col-md-12"> 

     <?php 
	 
	 		$contador=0;
			$nome = array();
			$valor = array();
			
			$leituraConta = read('pagar_conta',"WHERE id ORDER BY nome ASC");
			
			if($leituraConta){
				foreach($leituraConta as $mostraConta):
					$descricao= $mostraConta['nome'];
					$contaId = $mostraConta['id'];
					$total = soma('pagar',"WHERE pagamento>='$data1' AND pagamento<='$data2' AND 
									status<>'Em Aberto' AND id_conta='$contaId'",'valor');
					if($total<>0){
						$nome[$contador] = $descricao;
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
				  ['Despesas', 'Mes Atual'],
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
				  legend:{position: 'right', textStyle: {color: 'black', fontSize: 5}},
				  title: 'Despesas por Conta'
				};
				var chart=new google.visualization.PieChart(document.getElementById('contas'));
				chart.draw(data, options);
			}
		</script>

	
		<div class="col-md-6">   
          	<div class="box">
         		<div class="box-header">
        		  <div id="contas"></div><!--/chat do google-->
          		</div><!-- /.box-header -->
    		</div><!-- /.box  -->
    	 </div><!-- /.col-md-6  -->
		
		
		<?php 
	 
	 		$contador=0;
			$nome = array();
			$valor = array();
			
			$leituraGrupo = read('pagar_grupo',"WHERE id ORDER BY nome ASC");
			
			if($leituraGrupo){
				foreach($leituraGrupo as $mostraGrupo):
				
					$descricao= $mostraGrupo['nome'];
					$grupoId = $mostraGrupo['id'];
				
					$leituraConta = read('pagar_conta',"WHERE id_grupo ='$grupoId'");
					$total=0;
				
					foreach($leituraConta as $mostraConta):
						$contaId = $mostraConta['id'];
						$total = $total + soma('pagar',"WHERE pagamento>='$data1' AND pagamento<='$data2' AND 
									status<>'Em Aberto' AND id_conta='$contaId'",'valor');
					endforeach;

					if($total<>0){
						$nome[$contador] = $descricao;
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
				  ['Despesas', 'Mes Atual'],
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
				  legend:{position: 'right', textStyle: {color: 'black', fontSize: 5}},
				  title: 'Despesas por Grupo'
				};
				var chart=new google.visualization.PieChart(document.getElementById('grupo'));
				chart.draw(data, options);
			}
		</script>

	
		<div class="col-md-6">   
          	<div class="box">
         		<div class="box-header">
        		  <div id="grupo"></div><!--/chat do google-->
          		</div><!-- /.box-header -->
    		</div><!-- /.box  -->
    	 </div><!-- /.col-md-6  -->

			   
   			<?php
		
				$mes120= date('m/Y',strtotime('-4months'));
				$mesano = explode('/',$mes120);
				$pagar120 = soma('pagar',"WHERE status='Baixado' AND Month(pagamento)='$mesano[0]' AND 
									   Year(vencimento)='$mesano[1]'",'valor');
				$mes90 = date('m/Y',strtotime('-3months'));
				$mesano = explode('/',$mes90);
				$pagar90 = soma('pagar',"WHERE status='Baixado' AND Month(pagamento)='$mesano[0]' AND 
									   Year(pagamento)='$mesano[1]'",'valor');

				$mes60 = date('m/Y',strtotime('-2months'));
				$mesano = explode('/',$mes60);
				$pagar60 = soma('pagar',"WHERE status='Baixado' AND Month(pagamento)='$mesano[0]' AND 
									   Year(pagamento)='$mesano[1]'",'valor');

				$mes30 = date('m/Y',strtotime('-1months'));
				$mesano = explode('/',$mes30);
				$pagar30 = soma('pagar',"WHERE status='Baixado' AND Month(pagamento)='$mesano[0]' AND 
									   Year(pagamento)='$mesano[1]'",'valor');

			?> 

            <div class="col-lg-3 col-xs-4">
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?php echo (int)$pagar120;?></h3>
                  <p>Despesas</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
              <a href="#" class="small-box-footer"><?php echo $mes120;?> <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col-lg-3 col-xs-6-->

            
            <div class="col-lg-3 col-xs-4">
              <div class="small-box bg-green">
                <div class="inner">
                  <h3><?php echo (int)$pagar90;?></h3>
                  <p>Despesas</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i> 
                </div>
             <a href="#" class="small-box-footer"><?php echo $mes90;?> <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col-lg-3 col-xs-6-->

            
            <div class="col-lg-3 col-xs-4">
              <div class="small-box bg-yellow">
                <div class="inner">
                   <h3><?php echo (int)$pagar60;?></h3>
                  <p>Despesas</p>
                </div>
                <div class="icon">
                  <i class="ion ion-calculator"></i>
                </div>
              <a href="#" class="small-box-footer"><?php echo $mes60;?> <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col-lg-3 col-xs-6-->

            
            <div class="col-lg-3 col-xs-4">
              <div class="small-box bg-red">
                <div class="inner">
                   <h3><?php echo (int)$pagar30;?></h3>
                  <p>Despesas</p>
                </div>
                <div class="icon">
                  <i class="ion ion-card"></i>
                </div>
            <a href="#" class="small-box-footer"><?php echo $mes30;?> <i class="fa fa-arrow-circle-right"></i></a>
              </div>
          </div><!-- ./col-lg-3 col-xs-6-->

	
  </div><!-- /.col-md-12"  -->
</section><!-- /.content -->
</div><!-- /.content-wrapper -->
