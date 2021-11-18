<?php 

if ( function_exists( 'ProtUser' ) ) {
    if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
        header( 'Location: painel.php?execute=suporte/403' );
    }
}
     
?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Bancos</h1>
        <ol class="breadcrumb">
            <li>Home</a>
            <li>Movimentação</a>
            <li class="active">Bancos</li>
          </ol>
 </section>
 
<section class="content">
	
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
         <div class="box-header">
         
         	
            <a href="painel.php?execute=suporte/pagar/banco-editar" class="btnnovo">
	   		<img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" />Novo Banco
    		</a>
			 
			 <a href="painel.php?execute=suporte/movimentacao/movimentacao-editar" class="btnnovo">
				<img src="ico/novo.png" alt="Novo" title="Novo" class="imagem" />Saldo Inicial
		 	</a>
     </div><!-- /.box-header -->
     <div class="box-body table-responsive">
     
	<?php 

	$total = conta('banco',"WHERE id");
	$leitura = read('banco',"WHERE id ORDER BY status DESC, nome ASC");
	if($leitura){
			echo '<table class="table table-hover">
					<tr class="set">
					
					<td align="center">ID</td>
					<td>Nome</td>
					<td>Numero</td>
					<td>Agencia</td>
					<td>Conta</td>
					<td>Carteira</td>
					<td>Multa</td>
					<td>Juros ao Dia</td>
					<td>Limite</td>
				
					<td align="center" colspan="3">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		 	echo '<tr>';
				echo '<td>'.$mostra['id'].'</td>';
				echo '<td>'.$mostra['nome'].'</td>';
				echo '<td>'.$mostra['codigo_banco']. '-' . $mostra['digito_banco'].'</td>';
				echo '<td>'.$mostra['agencia'].'</td>';
				echo '<td>'.$mostra['conta'] . '-' . $mostra['conta_digito'].'</td>';
				echo '<td>'.$mostra['carteira'].'</td>';
				echo '<td>'.$mostra['multa'].'</td>';
				echo '<td>'.$mostra['juros'].'</td>';
				echo '<td>'.converteValor($mostra['limite']).'</td>';
		
				echo '<td align="center">
				<a href="painel.php?execute=suporte/pagar/banco-editar&bancoEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
              			</a>
				      </td>';
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/pagar/banco-editar&bancoDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png" alt="Excluir" title="Excluir" class="tip" />
              			</a>
						</td>';
			echo '</tr>';
		 endforeach;
			echo '<tfoot>';
                  echo '<tr>';
                        echo '<td colspan="13">' . 'Total de Registros : ' .  $total . '</td>';
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
    
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
</section><!-- /.content -->

</div><!-- /.content-wrapper -->