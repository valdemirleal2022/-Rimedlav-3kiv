<?php 
	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}
	
	echo '<head>';
    echo '<meta charset="iso-8859-1">';
	echo '</head>';
     
?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Grupo</h1>
        <ol class="breadcrumb">
            <li>Home</a>
            <li>Contas a Pagar</a>
            <li class="active">Grupo</li>
          </ol>
 </section>

<section class="content">
	
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
		 
         <div class="box-header">
            <a href="painel.php?execute=suporte/pagar/pagar-grupo-editar" class="btnnovo">
	   		<img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" />
    		</a>
     	</div><!-- /.box-header -->
		 
     <div class="box-body table-responsive">
  
	<?php 

	$total = conta('pagar_grupo',"WHERE id ORDER BY codigo ASC");
	$leitura = read('pagar_grupo',"WHERE id ORDER BY codigo ASC");
		 
	if($leitura){
		echo '<table class="table table-hover">
					<tr class="set">
					
					<td align="center">ID</td>
					<td align="center">Codigo</td>
					<td align="center">Nome</td>
					
					<td align="center" colspan="3">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		 	echo '<tr>';
				echo '<td>'.$mostra['id'].'</td>';
				echo '<td>'.$mostra['codigo'].'</td>';
				echo '<td>'.$mostra['nome'].'</td>';
				echo '<td align="center">
				<a href="painel.php?execute=suporte/pagar/pagar-grupo-editar&grupoEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
              			</a>
				      </td>';
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/pagar/pagar-grupo-editar&grupoDeletar='.$mostra['id'].'">
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