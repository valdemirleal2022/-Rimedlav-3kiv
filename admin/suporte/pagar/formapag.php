<?php 
if ( function_exists( 'ProtUser' ) ) {
    if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
        header( 'Location: painel.php?execute=suporte/403' );
    }
}
?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Forma de Pagamento</h1>
        <ol class="breadcrumb">
            <li>Home</a>
            <li>Movimentação</a>
            <li class="active">Forma de Pagamento</li>
          </ol>
 </section>
 
<section class="content">
	
  <div class="row">
   <div class="col-md-6">  
     <div class="box box-default">
		 
         <div class="box-header">
            <a href="painel.php?execute=suporte/pagar/formapag-editar" class="btnnovo">
	   		<img src="ico/novo.png" title="Criar Novo" />
    		</a>
 	  	</div><!-- /.box-header -->
		 
     <div class="box-body table-responsive">
     
     
	<?php 
		
		$total = conta('formpag',"WHERE id");
	
		$leitura = read('formpag',"WHERE id ORDER BY id ASC");
		 
		if($leitura){
			
			echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">ID</td>
					<td>Nome</td>
					<td align="center" colspan="8">Gerenciar</td>
				</tr>';
			
			foreach($leitura as $mostra):
			
				echo '<tr>';
			
					echo '<td>'.$mostra['id'].'</td>';
					echo '<td>'.$mostra['nome'].'</td>';
			
					echo '<td align="center">
					<a href="painel.php?execute=suporte/pagar/formapag-editar&formaEditar='.$mostra['id'].'">
								<img src="ico/editar.png" title="Editar"  />
							</a>
						  </td>';
			
					echo '<td align="center">
							<a href="painel.php?execute=suporte/pagar/formapag-editar&formaDeletar='.$mostra['id'].'">
								<img src="ico/excluir.png" title="Excluir" />
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