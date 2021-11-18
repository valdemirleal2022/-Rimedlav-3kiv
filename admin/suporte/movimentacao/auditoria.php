<?php 
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php');		
			}	
		}
		
		$_SESSION['banco']=$_SERVER['REQUEST_URI'];
?>

<div class="content">

	<h1>Auditoria :</h1>

	<?php 

		$pag = (empty($_GET['pag']) ? '1' : $_GET['pag']);
		$maximo = '50';
		$inicio = ($pag * $maximo) - $maximo;
		
		$total=0;
		
		$leitura = read('banco',"WHERE id ORDER BY id ASC LIMIT $inicio,$maximo");
		if($leitura){
			echo '<table width="775" class="tabelaspg" id="tabelasconfig" border="0" cellspacing="0" cellpadding="0">
					<tr class="set">
					<td align="center">ID:</td>
					<td>Nome:</td>
					<td>Numero:</td>
					<td>Agencia:</td>
					<td>Conta:</td>
					<td>Data:</td>
					<td>Saldo Atual:</td>
					<td align="center" colspan="3">Moderar:</td>
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
						echo '<td align="center">'.convertevalor($movimentacao['saldo']).'</td>';
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
		 
		 $link = 'painel.php?execute=suporte/movimentacao/movimentacao&pag=';
	     
		 pag('banco',"WHERE id ORDER BY nome ASC", $maximo, $link, $pag);
	  
	  }
	  
		$data1 = date("Y-m-d", strtotime("-3 day"));
		$data2 = date("Y-m-d", strtotime("-350 day"));
		$receitavencida = soma('receber',"WHERE vencimento>='$data2' AND vencimento<='$data1' AND status='Em Aberto'",'valor');
	
		$data1 = date("Y-m-d", strtotime("-2 day"));
		$data2 = date("Y-m-d", strtotime("+30 day"));
		$receita = soma('receber',"WHERE vencimento>='$data1' AND vencimento<='$data2' AND status='Em Aberto'",'valor');
		$despesa = soma('pagar',"WHERE vencimento<='$data2' AND status='Em Aberto'",'valor');
		
		$credito = $total+$receitavencida+$receita; 
		$saldo=$credito - $despesa; 
	
	
		echo '<table width="200" class="tabelaspg" id="tabelasconfig" border="0" cellspacing="0" cellpadding="0">
					<tr class="set">
					<td align="center">Descrição</td>
					<td align="center">Valor</td>
				</tr>';
			echo '<tr>';
				echo '<td align="right">Saldo Bancário  R$</td>';
				echo '<td align="center">'.converteValor($total).'</td>';	
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right">Clientes Atrasados R$</td>';
				echo '<td align="center">'.converteValor($receitavencida).'</td>';	
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right">Receita 30 dias R$</td>';
				echo '<td align="center">'.converteValor($receita).'</td>';	
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right">Total de Receita R$</td>';
				echo '<td align="center">'.converteValor($credito).'</td>';	
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right">Total de Despesa R$</td>';
				echo '<td align="center">'.converteValor($despesa).'</td>';	
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right">Saldo Previsto 	R$</td>';
				echo '<td align="center">'.converteValor($saldo).'</td>';	
			echo '</tr>';
 
		 echo '</table>';
	
	?>
    
     
    
</div><!--/content-->
 