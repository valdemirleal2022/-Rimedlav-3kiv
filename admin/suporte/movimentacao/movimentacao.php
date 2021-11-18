<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}
 
	$_SESSION['banco']=$_SERVER['REQUEST_URI'];
	
	$mes = date('m/Y');
    $mesano = explode('/',$mes);

?>

<div class="content-wrapper">
  <section class="content-header">
     <h1>Movimentação</h1>
     <ol class="breadcrumb">
     	<li>Home</a>
     	<li>Suporte</a>
     	<li class="active">Movimentação</li>
     </ol>
 </section>
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-info">
		 
      <div class="box-body table-responsive">

	  <?php 
	 
		$leitura = read('banco',"WHERE id ORDER BY id ASC");
		if($leitura){
			echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">ID</td>
					<td>Nome</td>
					<td>Numero</td>
					<td>Agencia</td>
					<td>Conta</td>
					<td>Data</td>
					<td>Saldo Atual</td>
					<td align="center" colspan="3">Extrato</td>
				</tr>';
			
			foreach($leitura as $mostra):
			
		 		echo '<tr>';
					echo '<td>'.$mostra['id'].'</td>';
					echo '<td>'.$mostra['nome'].'</td>';
					echo '<td>'.$mostra['codigo_banco']. '-' . $mostra['digito_banco'].'</td>';
					echo '<td>'.$mostra['agencia'].'</td>';
					echo '<td>'.$mostra['conta'] . '-' . $mostra['conta_digito'].'</td>';
					$bancoId = $mostra['id'];
                    
						 //ATUALIZAÇÃO DO SALDO
						atualizaSaldo($bancoId);
            
					$movimentacao = mostra('movimentacao',"WHERE banco ='$bancoId' ORDER BY data ASC");
					$movimentacaoLeitura= read('movimentacao',"WHERE banco ='$bancoId' ORDER BY data ASC");
					if($movimentacaoLeitura){
						foreach($movimentacaoLeitura as $movimentacao):

						endforeach;
					}
					if($movimentacaoLeitura){
						echo '<td align="center">'.convertedata($movimentacao['data']).'</td>';
						
						if($movimentacao['saldo']>0){
							echo '<td align="center"><span class="badge bg-light-blue">'.convertevalor($movimentacao['saldo']).'</span></td>';
						}else{
							echo '<td align="center"><span class="badge bg-red">'.convertevalor($movimentacao['saldo']).'</span></td>';
						}
						
						$total=$total+$movimentacao['saldo'];
					  }else{
						echo '<td align="center">-</td>';
						echo '<td align="center">-</td>';
					}
			
			
					echo '<td align="center">
							<a href="painel.php?execute=suporte/movimentacao/movimentacoes&bancoAtualizar='.$mostra['id'].'">
								<img src="ico/extrato.png" alt="Extrado" title="Extrado" class="tip" />
							</a>
						  </td>';
				echo '</tr>';
		 endforeach;
		 
		 echo '</table>';
	  
	  }
?>

   </div><!-- /.box-body table-responsive-->
 </div><!-- /.box box-default -->
 </div><!-- /.col-md-12 -->
</div><!-- /.row -->
</section><!-- /.content -->
		
       
<section class="content">
  <div class="row">
  
   <div class="col-md-4">
     <div class="box box-warning"> 
	     <div class="box-body table-responsive">

            <h2 class="page-header">Saldo Previsto: <?php echo $mesano[0].'/'.$mesano[1] ?></h2>
			<?php
				$data1 = date("Y-m-d", strtotime("-3 day"));
				$data2 = date("Y-m-d", strtotime("-240 day"));
				$receitavencida = soma('receber',"WHERE vencimento>='$data2' AND vencimento<='$data1' AND status='Em Aberto'",'valor');
				$receita = soma('receber',"WHERE Month(vencimento)='$mesano[0]' AND 
												Year(vencimento)='$mesano[1]' AND 
												status='Em Aberto'",'valor');
				$despesa = soma('pagar',"WHERE Month(vencimento)='$mesano[0]' AND 
												Year(vencimento)='$mesano[1]' AND 
												status='Em Aberto'",'valor');
				$credito = $total+$receita; 
				$saldo=$credito - $despesa; 
	
				echo '<table class="table table-hover">
						<tr class="set">
						<td align="center">Descrição</td>
						<td align="center">Valor</td>
						</tr>';
					echo '<tr>';
						echo '<td align="right">Saldo Bancário  R$</td>';
						if($total>0){
							echo '<td align="center"><span class="badge bg-light-blue">'.convertevalor($total).'</span></td>';
						}else{
							echo '<td align="center"><span class="badge bg-red">'.convertevalor($total).'</span></td>';
						}
					echo '</tr>';
					echo '<tr>';
						echo '<td align="right">Receita  R$</td>';
						if($receita>0){
							echo '<td align="center"><span class="badge bg-light-blue">'.convertevalor($receita).'</span></td>';
						}else{
							echo '<td align="center"><span class="badge bg-red">'.convertevalor($receita).'</span></td>';
						}
					echo '</tr>';
					echo '<tr>';
						echo '<td align="right">Total de Receita R$</td>';
						if($credito>0){
							echo '<td align="center"><span class="badge bg-light-blue">'.convertevalor($credito).'</span></td>';
						}else{
							echo '<td align="center"><span class="badge bg-red">'.convertevalor($credito).'</span></td>';
						}
						echo '</tr>';
					echo '<tr>';
						echo '<td align="right">Total de Despesa R$</td>';
						if($despesa>0){
							echo '<td align="center"><span class="badge bg-light-blue">'.convertevalor($despesa).'</span></td>';
						}else{
							echo '<td align="center"><span class="badge bg-red">'.convertevalor($despesa).'</span></td>';
						}
						echo '</tr>';
						echo '<tr>';
							echo '<td align="right">Saldo Previsto 	R$</td>';
							if($saldo>0){
								echo '<td align="center"><span class="badge bg-light-blue">'.convertevalor($saldo).'</span></td>';
							}else{
								echo '<td align="center"><span class="badge bg-red">'.convertevalor($saldo).'</span></td>';
							}
					echo '<tr>';
					 echo '</table>';
			
				?>
       </div><!-- /.box-body table-responsive --> 
      </div><!-- /.box box-warning --> 
    </div><!-- /.col-md-4--> 

   <div class="col-md-4">
	 <div class="box box-danger">
       <div class="box-body table-responsive">
			<?php
                 echo '<table class="table table-hover">
                            <tr class="set">
                            <td align="center">Descrição</td>
                            <td align="center">Valor</td>
                        </tr>';
                         echo '<tr>';
                        echo '<td align="right">Clientes Atrasados R$</td>';
                        echo '<td align="center"><span class="badge bg-red">'.convertevalor($receitavencida).'</span></td>';
                    echo '</tr>';
                 echo '</table>';
            ?>
       </div><!-- /.box-body table-responsive --> 
      </div><!-- /.box box-danger --> 
    </div><!-- /.col-md-4--> 
    
 </div><!-- /.row -->
</section><!-- /.content -->  

</div><!-- /.content-wrapper -->
