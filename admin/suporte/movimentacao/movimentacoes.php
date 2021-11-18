<?php 

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php?execute=suporte/403');	
			}	
		}
		
		if(!empty($_GET['bancoAtualizar'])){
			
			$bancoId = $_GET['bancoAtualizar'];
			$banco = mostra('banco',"WHERE id ='$bancoId'");
            
			//funcao atualiza saldo
			atualizaSaldo($bancoId);

		}
		
		//if(!isset($_SESSION['bancoId'])){
//			$bancoId=$_POST['bancoId'];
//			$_SESSION['bancoId']=$bancoId ;
//		}

		$_SESSION['url2']=$_SERVER['REQUEST_URI'];
		$_SESSION['url']=$_SERVER['REQUEST_URI'];
		
		$banco = mostra('banco',"WHERE id ='$bancoId'");
?>

<div class="content-wrapper">
  <section class="content-header">
     <h1>Movimentaçao</h1>
     <ol class="breadcrumb">
     	<li>Home</a>
     	<li>Suporte</a>
     	<li class="active">Movimentação</li>
     </ol>
 </section>
 
<section class="content">
 
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
   
			<div class="box-header">
			 
				 <a href="painel.php?execute=suporte/pagar/despesa-novo" class="btnnovo">
				 <img src="ico/novo.png" alt="Novo" title="Novo" class="imagem" /><small>Novo Pagamento</small>
				 </a>
				 
				  <a href="painel.php?execute=suporte/movimentacao/transferencia-editar" class="btnnovo">
				 <img src="ico/novo.png" alt="Novo" title="Novo" class="imagem" /><small>Transferência</small>
				 </a>
				 
		   </div><!-- /.box-header -->
       

       <div class="box-body table-responsive data-spy='scroll'">
     	<div class="col-md-12 scrool">  
			<div class="box-body table-responsive">
				
       <?php 
         
		$data= date("Y-m-d", strtotime("-30 day"));
         
		$leitura = read('movimentacao',"WHERE data AND banco ='$bancoId' AND data>'$data'ORDER BY data ASC");
				
		if($leitura){
			echo '<table class="table table-fixed">
					<tr class="set">
					<td align="center">Id</td>
					<td>Nome</td>
					<td>Descrição</td>
					<td>Nota</td>
					<td>Data</td>
					<td>Crédito</td>
					<td>Débito</td>
					<td>Saldo</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		    foreach($leitura as $mostra):
			
				$bancoId=$mostra['banco'];
				$data=$mostra['data'];
			
				$leitura = read('pagar',"WHERE pagamento='$data' AND banco='$bancoId' ");
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
							echo '<td align="center">'.converteData($pagar['pagamento']).'</td>';
							echo '<td align="right"></td>';
							echo '<td class="vermelho"> -'.converteValor($pagar['valor']).'</td>';
							echo '<td align="right"></td>';
							echo '<td align="center">
								<a href="painel.php?execute=suporte/pagar/despesa-editar&pagamentoEditar='.$pagar['id'].'">
										<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
									</a>
								  </td>';
						echo '</tr>';
					endforeach;
				}
				
				//transferencias
				$leitura = read('transferencia',"WHERE data='$data' AND banco='$bancoId' ");
				if($leitura){
					foreach($leitura as $transferencia):
						echo '<tr>';
							echo '<td align="left">'.$transferencia['id'].'</td>';
							echo '<td>Transfencias em Contas</td>';
							echo '<td>-</td>';
							echo '<td>-</td>';
							echo '<td align="center">'.date('d/m/Y',strtotime($transferencia['data'])).'</td>';
							if($transferencia['credito']>0){
								echo '<td align="right"> + '.converteValor($transferencia['credito']).'</td>';
							 }else{
								echo '<td align="right"> </td>';
							}
							if($transferencia['debito']>0){
								echo '<td class="vermelho"> -'.converteValor($transferencia['debito']).'</td>';
							 }else{
								echo '<td align="right"> </td>';
							}
							echo '<td align="right"></td>';
							echo '<td align="center">
								-
								  </td>';
						echo '</tr>';
					endforeach;
				}	
				
				//creditos
				$leitura = read('receber',"WHERE pagamento='$data' AND banco='$bancoId' ORDER BY formpag ASC ");
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
							echo '<td align="center">'.converteData($receber['pagamento']).'</td>';
							echo '<td align="right"> + '.converteValor($receber['valor']+$receber['juros']).'</td>';
							echo '<td align="right"></td>';
							echo '<td align="right"></td>';
					
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
			
					echo '<td align="center">'.converteData($mostra['data']).'</td>';
					echo '<td align="right">'.converteValor($mostra['credito']).'</td>';
					echo '<td align="right">'.converteValor($mostra['debito']).'</td>';
			
					if($mostra['saldo']>0){
						echo '<td align="center"><span class="badge bg-light-blue">'.convertevalor($mostra['saldo']).'</span></td>';
					}else{
						echo '<td align="center"><span class="badge bg-red">'.convertevalor($mostra['saldo']).'</span></td>';
					}
			
					echo '<td align="center"><--</td>';
			
				echo '</tr>';
			
		 endforeach;
			
		echo '</table>';
			
		  }else{
			echo '<div class="alert alert-warning">Nenhum Registrado Encontrado!</div>';	
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
  
</div><!-- /.content-wrapper -->