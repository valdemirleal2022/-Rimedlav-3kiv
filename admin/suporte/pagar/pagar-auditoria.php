<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'],'1')){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}
	
?>
<div class="content-wrapper">
      
  <section class="content-header">
     <h1>Despesas Auditoria</h1>
     <ol class="breadcrumb">
     	<li>Home</a>
     	<li>Contas a Pagar</a>
     	<li class="active">Despesas Auditoria</li>
     </ol>
 </section>

 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
            <div class="box-header">
            
            </div><!-- /.box-header -->
     <div class="box-body table-responsive">
     
	<?php 

	$pag = (empty($_GET['pag']) ? '1' : $_GET['pag']);
	$maximo = '50';
	$inicio = ($pag * $maximo) - $maximo;
	
	$leitura = read('pagar',"WHERE status<>'Em Aberto' ORDER BY interacao DESC LIMIT $inicio,$maximo");
	if($leitura){
				echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">Id</td>
					<td>Interação</td>
					<td>Descrição</td>
					<td>Valor</td>
					<td>Pagamento</td>
					<td>Forma/Banco</td>
					<td>Conta</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		 	echo '<tr>';
				echo '<td align="center">'.$mostra['id'].'</td>';
				echo '<td>'.date('d/m/Y H:i:s',strtotime($mostra['interacao'])).'</td>';
				echo '<td>'.substr($mostra['descricao'],0,20).'</td>';
				echo '<td align="right">'.converteValor($mostra['valor']).'</td>';
				echo '<td align="center">'.date('d/m/Y',strtotime($mostra['pagamento'])).'</td>';
				$bancoId=$mostra['banco'];
				$banco = mostra('banco',"WHERE id ='$bancoId'");
				$formapagId=$mostra['formpag'];
				$formapag = mostra('formpag',"WHERE id ='$formapagId'");
				echo '<td align="center">'.$banco['nome']. "|".$formapag['nome'].'</td>';
				$contaId = $mostra['id_conta'];
				$conta = mostra('pagar_conta',"WHERE id ='$contaId '");
				if(!$conta){
					echo '<td align="center">Conta Nao Encontrado</td>';
				}else{
					echo '<td>'.substr($conta['nome'],0,20).'</td>';
				}
				echo '<td align="center">
				<a href="painel.php?execute=suporte/pagar/pagar-editar&pagamentoEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
              			</a>
				      </td>';
				 
			echo '</tr>';
		endforeach;
		echo '</table>';
	 		$link = 'painel.php?execute=suporte/pagar/pagar-auditoria&pag=';
			pag('pagar',"WHERE status='Em Aberto' ORDER BY interacao DESC", $maximo, $link, $pag);
		}
		?>
  		<div class="box-footer">
            <?php echo $_SESSION['cadastro'];
			unset($_SESSION['cadastro']);
			?>
       </div><!-- /.box-footer-->

    </div><!-- /.box-body table-responsive -->
    
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
</section><!-- /.content -->

</div><!-- /.content-wrapper -->