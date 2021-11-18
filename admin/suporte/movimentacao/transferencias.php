<?php 
	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}
	echo '<head>';
    echo '<meta charset="iso-8859-1">';
	echo '</head>';
	$_SESSION['url']=$_SERVER['REQUEST_URI'];
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
      	<div class="box-header">
         <a href="painel.php?execute=suporte/movimentacao/transferencia-editar" class="btnnovo">
			<img src="ico/novo.png" alt="Novo" title="Novo" class="imagem" />
            <small>Transferencias</small>
		 </a>
       </div><!-- /.box-header -->
    <div class="box-body table-responsive">

    <?php 

	$pag = (empty($_GET['pag']) ? '1' : $_GET['pag']);
	$maximo = '50';
	$inicio = ($pag * $maximo) - $maximo;

	$leitura = read('transferencia',"WHERE data ORDER BY data DESC LIMIT $inicio,$maximo");
	if($leitura){
		echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">ID</td>
					<td>Data</td>
					<td>Banco</td>
					<td>Crédito</td>
					<td>Débito</td>
					<td align="center" colspan="1">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
			if($mostra['credito']<>0){
				echo '<tr>';
					echo '<td align="center">'.$mostra['id'].'</td>';
					$bancoId=$mostra['banco'];
					$banco = mostra('banco',"WHERE id ='$bancoId'");
					echo '<td align="center">'.$banco['nome'].'</td>';
					echo '<td align="center">'.date('d/m/Y',strtotime($mostra['data'])).'</td>';
					echo '<td align="right">'.converteValor($mostra['credito']).'</td>';
					echo '<td align="right"></td>';
					echo '<td align="center">
							<a href="painel.php?execute=suporte/movimentacao/transferencia-editar&transferenciaDeletar='.$mostra['id'].'">
								<img src="ico/excluir.png" alt="Excluir" title="Excluir" class="tip" />
							</a>
							</td>';
				echo '</tr>';
			}
			if($mostra['debito']<>0){
				echo '<tr>';
					echo '<td align="center">'.$mostra['id'].'</td>';
					$bancoId=$mostra['banco'];
					$banco = mostra('banco',"WHERE id ='$bancoId'");
					echo '<td align="center">'.$banco['nome'].'</td>';
					echo '<td align="center">'.date('d/m/Y',strtotime($mostra['data'])).'</td>';
					echo '<td align="right"></td>';
					echo '<td align="right">'.converteValor($mostra['debito']).'</td>';
					echo '<td align="center">
							<a href="painel.php?execute=suporte/movimentacao/transferencia-editar&transferenciaDeletar='.$mostra['id'].'">
								<img src="ico/excluir.png" alt="Excluir" title="Excluir" class="tip" />
							</a>
							</td>';
				echo '</tr>';
			}
		 endforeach;
		echo '</table>';
		$link = 'painel.php?execute=suporte/movimentacao/transferencias&pag=';
	     pag('transferencia',"WHERE data ORDER BY data DESC", $maximo, $link, $pag);
		}
	?>
	</div><!-- /.box-body table-responsive -->
        <div class="box-footer">
            <?php echo $_SESSION['cadastro'];
			unset($_SESSION['cadastro']);
			?> 
       </div><!-- /.box-footer-->
    </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
</section><!-- /.content -->