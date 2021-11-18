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
       <h1>Manutenção/Responsável</h1>
       <ol class="breadcrumb">
         <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i>Home</a></li>
         <li><a href="#">Manutenção</a></li>
         <li class="active">Responsável</li>
       </ol>
 </section>
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
		 
		 
			 <div class="box-header">
				 
				 	  <a href="painel.php?execute=suporte/veiculo/manutencao-responsavel-editar" class="btnnovo">
					  <img src="ico/novo.png" title="Criar Novo" class="tip" />
						<small>Novo Motorista</small>
					 </a>
				 
			 </div><!-- /.box-header -->
 
		 
     <div class="box-body table-responsive">
     	<div class="box-body table-responsive data-spy='scroll'">
     		<div class="col-md-12 scrool">  

	<?php 
	 
	$total = conta('veiculo_manutencao_responsavel',"WHERE id");
	$leitura = read('veiculo_manutencao_responsavel',"WHERE id ORDER BY nome");
 	
	if($leitura){
				echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">ID</td>
					<td align="center">Nome</td>
					<td align="center">Função</td>
					<td align="center">Senha</td>
					<td align="center" colspan="3">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
				
				echo '<td>'.$mostra['id'].'</td>';
				echo '<td>'.$mostra['nome'].'</td>';
				echo '<td>'.$mostra['funcao'].'</td>';
				echo '<td>'.$mostra['senha'].'</td>';
		
				echo '<td align="center">
				<a href="painel.php?execute=suporte/veiculo/manutencao-responsavel-editar&responsavelEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" title="Editar" />
              			</a>
				      </td>';
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/veiculo/manutencao-responsavel-editar&responsavelDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png" title="Excluir"/>
              			</a>
						</td>';

			echo '</tr>';
		
		 endforeach;
		 
		  echo '<tfoot>';
         			echo '<tr>';
                	echo '<td colspan="14">' . 'Total de Registros : ' .  $total . '</td>';
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

	      </div><!--/col-md-12 scrool-->   
			</div><!-- /.box-body table-responsive data-spy='scroll -->
 	  </div><!-- /.box-body table-responsive -->
	  
    </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 
 </section><!-- /.content -->
  
</div><!-- /.content-wrapper -->