<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}

	$_SESSION['url']=$_SERVER['REQUEST_URI'];
	
?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Motivos Atendimento - Pos-Venda</h1>
        <ol class="breadcrumb">
            <li>Home</a>
            <li>Atendimento</a>
            <li class="active">Cadastro</li>
          </ol>
 </section>
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
            <div class="box-header">
            <a href="painel.php?execute=suporte/atendimento/atendimento-motivo-editar" class="btnnovo">
	   		<img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" />
    		</a>
     </div><!-- /.box-header -->
     <div class="box-body table-responsive">
     
	<?php 
	
	$total = conta('contrato_atendimento_pos_venda_motivo',"WHERE id");

	$leitura = read('contrato_atendimento_pos_venda_motivo',"WHERE id ORDER BY id ASC");
	if($leitura){
			echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">ID</td>
					<td>Nome</td>
					<td align="center" colspan="3">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		 	echo '<tr>';
				echo '<td>'.$mostra['id'].'</td>';
				echo '<td>'.$mostra['nome'].'</td>';
				echo '<td align="center">
				<a href="painel.php?execute=suporte/atendimento/atendimento-motivo-editar&atendimentoEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" title="Editar" />
              			</a>
				      </td>';
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/atendimento/atendimento-motivo-editar&atendimentoDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png" title="Excluir" />
              			</a>
						</td>';
			echo '</tr>';
		 endforeach;
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