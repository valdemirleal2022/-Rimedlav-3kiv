<?php 
	if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php?execute=suporte/403');
			}	
		}
?>


<div class="content">

	<h1>Gerenciar Professores:</h1>
    
         <a href="painel.php?execute=site/professor-editar" class="btnnovo">
			  <img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
               Cadastrar Novo Professores
         </a>

	<?php 
	

	$pag = (empty($_GET['pag']) ? '1' : $_GET['pag']);
	$maximo = '50';
	$inicio = ($pag * $maximo) - $maximo;
	
	$leitura = read('professor',"WHERE id ORDER BY id ASC LIMIT $inicio,$maximo");
	if($leitura){

			echo '<table width="775" class="tabelaspg" id="tabelasconfig" border="0" cellspacing="0" cellpadding="0">
					<tr class="set">
					<td align="center">Id</td>
					<td>Imagem</td>
					<td align="center">Nome</td>
					<td align="center">Email</td>
					<td align="center">Status</td>
					<td align="center" colspan="3">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		 	echo '<tr>';
				echo '<td>'.$mostra['id'].'</td>';
				if($mostra['foto']!= '' && file_exists('../uploads/professores/'.$mostra['foto'])){
					echo '<td align="center">
						<img src="../config/tim.php?src=../uploads/professores/'.$mostra['foto'].'&w=200&h=160&zc=1&q=100"
						 title="Ver" alt="Foto do professores" />';
				  }else{
					echo '<td align="center">
		  				<img src="../config/tim.php?src=site/images/autor.png&w=200&h=160&zc=1&q=100"
						 title="Ver" alt="Foto do professores" class="tip" />
				   	 </td>';
				}	
				echo '<td>'.$mostra['nome'].'</td>';
				echo '<td>'.$mostra['email'].'</td>';
				if($mostra['status']==1){
					echo '<td align="center">Ativo</td>';
				}else{
					echo '<td align="center">Inativo</td>';
				}
				echo '<td align="center">
					  <a href="painel.php?execute=site/professor-editar&professorEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
              			</a>
				      </td>';
				echo '<td align="center">
			  		   <a href="painel.php?execute=site/professor-editar&professorDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png" alt="Excluir" title="Excluir" class="tip" />
              			</a>
						</td>';
			echo '</tr>';
		 endforeach;
		 
		 echo '</table>';
		 
		 $link = 'painel.php?execute=site/professors&pag=';
		 
	     pag('professor',"WHERE id ORDER BY id ASC", $maximo, $link, $pag);
		 
	  }
	  
	?>
    
</div><!--/content-->
