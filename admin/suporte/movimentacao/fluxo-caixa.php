<?php 

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php?execute=suporte/403');	
			}	
		}
?>

<div class="content-wrapper">
  <section class="content-header">
     <h1>Movimentaçao</h1>
     <ol class="breadcrumb">
     	<li>Home</a>
     	<li>Suporte</a>
     	<li class="active">Movimentaçao</li>
     </ol>
 </section>
 
 <section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-info">
      <div class="box-body table-responsive">

	  <?php 
		$pag = (empty($_GET['pag']) ? '1' : $_GET['pag']);
		$maximo = '20';
		$inicio = ($pag * $maximo) - $maximo;
		$saldo=0;
		$leitura = read('banco',"WHERE id ORDER BY id ASC LIMIT $inicio,$maximo");
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
					
				 	//atualizaSaldo($bancoId);
					
					$movimentacao = mostra('movimentacao',"WHERE banco ='$bancoId' ORDER BY data ASC");
					if($movimentacao){
						echo '<td align="center">'.convertedata($movimentacao['data']).'</td>';
						
						if($movimentacao['saldo']>0){
							echo '<td align="center"><span class="badge bg-light-blue">'.convertevalor($movimentacao['saldo']).'</span></td>';
						}else{
							echo '<td align="center"><span class="badge bg-red">'.convertevalor($movimentacao['saldo']).'</span></td>';
						}
						
						$saldo+=$movimentacao['saldo'];
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
		 
		 echo '<tfoot>';
                echo '<tr>';
				
				   if($saldo>0){
							echo '<td colspan="12"><span class="badge bg-light-blue">' . 'Saldo R$ : ' .converteValor($saldo).'</span></td>';
						}else{
							echo '<td colspan="12"><span class="badge bg-red">' . 'Saldo R$ : ' .converteValor($saldo).'</span></td>';
						}
                echo '</tr>';
          echo '</tfoot>';
		 
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
   <div class="col-md-12">  
     <div class="box box-info">
      <div class="box-body table-responsive">
      	<div class="box-header">
           
       </div><!-- /.box-header -->
    <div class="box-body table-responsive data-spy="scroll"">



     <div class="col-md-12 scrool">  
    <?php 
	
	$data1= date("Y-m-d");
	$data2= date("Y-m-d", strtotime("+30 day"));

	echo '<table class="table table-hover">
			<tr class="set">
			<td align="center">Id</td>
			<td>Nome</td>
			<td>Descrição</td>
			<td>Nota</td>
			<td>Data</td>
			<td>Crédito</td>
			<td>Débito</td>
			<td>Saldo</td>
			<td align="center" colspan="1">Gerenciar</td>
		</tr>';
		
	 while (strtotime($data1) <= strtotime($data2)) {
		
			
		$credito=0;
		$debito=0;
			
				//débitos
				$leitura = read('pagar',"WHERE vencimento='$data1' AND status='Em Aberto'");
				if($leitura){
					foreach($leitura as $pagar):
						echo '<tr>';
							echo '<td align="center">'.$pagar['id'].'</td>';
							$contaId = $pagar['id_conta'];
							$conta = mostra('pagar_conta',"WHERE id ='$contaId '");
							if(!$conta){
								echo '<td align="left">Conta Nao Encontrado</td>';
							}else{
								echo '<td align="left">'.substr($conta['nome'],0,20).'</td>';
							}
							echo '<td align="left">'.substr($pagar['descricao'],0,20).'</td>';
							
							echo '<td>'.$pagar['nota'].'</td>';
							echo '<td align="center">'.date('d/m/Y',strtotime($pagar['vencimento'])).'</td>';
							echo '<td align="right"></td>';
							echo '<td class="vermelho"> -'.converteValor($pagar['valor']).'</td>';
							echo '<td align="right"></td>';
							echo '<td align="center">
								<a href="painel.php?execute=suporte/pagar/pagar-editar&pagamentoEditar='.$pagar['id'].'">
										<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
									</a>
								  </td>';
							 
							$debito+=$pagar['valor'];
							 
						echo '</tr>';
						
					endforeach;
				}

				//creditos
				$leitura = read('receber',"WHERE vencimento='$data1' AND status='Em Aberto'");
				if($leitura){
					foreach($leitura as $receber):
						echo '<tr>';
							echo '<td align="center">'.$receber['id'].'</td>';
							$clienteId = $receber['id_cliente'];
							$cliente = mostra('cliente',"WHERE id ='$clienteId '");
							if(!$cliente){
								echo '<td>Cliente Nao Encontrado</td>';
							}else{
								echo '<td align="left">'.substr($cliente['nome'],0,20).'</td>';
							}
							echo '<td align="left">'.substr($receber['observacao'],0,10).'</td>';
							echo '<td>'.$receber['nota'].'</td>';
							echo '<td align="center">'.date('d/m/Y',strtotime($receber['vencimento'])).'</td>';
							echo '<td align="right"> + '.converteValor($receber['valor']).'</td>';
							echo '<td align="right"></td>';
							echo '<td align="right"></td>';
							
							$credito+=$receber['valor'];
							
							echo '<td align="center">
								<a href="painel.php?execute=suporte/receber/receber-editar&receberEditar='.$receber['id'].'">
										<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
									</a>
								  </td>';
						echo '</tr>';
					endforeach;
				 }
				
					echo '<tr>';
					echo '<td align="center">--></td>';
					echo '<td>Saldo</td>';
					echo '<td>--></td>';
					echo '<td>--></td>';
					echo '<td align="center">'.converteData($data1).'</td>';
					echo '<td align="right">'.converteValor($credito).'</td>';
					echo '<td align="right">'.converteValor($debito).'</td>';
					$saldo=$saldo+$credito-$debito;
					
					if($saldo>0){
							echo '<td align="center"><span class="badge bg-light-blue">'.convertevalor($saldo).'</span></td>';
						}else{
							echo '<td align="center"><span class="badge bg-red">'.convertevalor($saldo).'</span></td>';
						}
					echo '<td>-</td>';	
				echo '</tr>';
				
				 
				$data1 = date ("Y-m-d", strtotime("+1 days", strtotime($data1)));
				
	    }
		
		
	
 
		echo '</table>';

	?>
 </div><!--/col-md-12 scrool-->   
	</div><!-- /.box-body table-responsive -->

        <div class="box-footer">
            <?php echo $_SESSION['cadastro'];
			unset($_SESSION['cadastro']);
			?> 
       </div><!-- /.box-footer-->
       
   </div><!-- /.box-body table-responsive --> 
      </div><!-- /.box box-danger --> 
    </div><!-- /.col-md-4--> 
    
 </div><!-- /.row -->
</section><!-- /.content -->  

</div><!-- /.content-wrapper -->
