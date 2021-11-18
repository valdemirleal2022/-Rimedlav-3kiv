<?php 
	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'],'1')){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}
?><head>
    <meta charset="iso-8859-1">
</head>


<div class="content-wrapper">

  <section class="content-header">
          <h1>Comentários</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li>Site</li>
            <li class="active">Comentários</li>
          </ol>
  </section>
  
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
         <div class="box-header">
            <a href="painel.php?execute=site/comentarios-editar" class="btnnovo">
	   		<img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" />
    		</a>
            
       		<div class="box-tools">
                
            </div><!-- /box-tools-->
       </div><!-- /.box-header -->
            
     <div class="box-body table-responsive">
      
	<?php 
	
	$pag = (empty($_GET['pag']) ? '1' : $_GET['pag']);
	$maximo = '10';
	$inicio = ($pag * $maximo) - $maximo;
	$total = conta('comentarios',"WHERE id");
	
	$leitura = read('comentarios',"WHERE id ORDER BY data DESC LIMIT $inicio,$maximo");
	if($leitura){
			echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">Id</td>
					<td>Imagem</td>
					<td align="center">Nome</td>
					<td align="center">Data</td>
					<td align="center">Texto</td>
					<td align="center">Status</td>
					<td align="center" colspan="3">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		 	echo '<tr>';
				echo '<td>'.$mostra['id'].'</td>';
				if($mostra['foto']!= '' && file_exists('../uploads/comentarios/'.$mostra['foto'])){
					echo '<td align="center">
						  <img class="img-thumbnail col-xs-11 col-md-4" src="'.URL.'/uploads/comentarios/'
                                     .$mostra['foto'].'">';
				  }else{
					echo '<td align="center">
		  				 <i class="fa fa-picture-o"></i>
				   	 </td>';
				}	
				echo '<td>'.$mostra['nome'].'</td>';
				echo '<td>'.converteData($mostra['data']).'</td>';
				echo '<td>'.resumos($mostra['mensagem'],$palavras = '110').'</td>';
				if($mostra['status']==1){
					echo '<td align="center">Ativo</td>';
				}else{
					echo '<td align="center">Inativo</td>';
				}
				echo '<td align="center">
					  <a href="painel.php?execute=site/comentario-editar&comentarioEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
              			</a>
				      </td>';
				echo '<td align="center">
			  		   <a href="painel.php?execute=site/comentario-editar&comentarioDeletar='.$mostra['id'].'">
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
		 $link = 'painel.php?execute=site/comentarios&pag=';
	     pag('comentarios',"WHERE id ORDER BY data DESC", $maximo, $link, $pag);
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