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
          <h1>Manual - Usuário</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li>Suporte</li>
            <li class="active">Manual</li>
          </ol>
  </section>

<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
         <div class="box-header">
            <a href="painel.php?execute=suporte/interacao/manual-editar" class="btnnovo">
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
            
            $total = conta('manual_usuario',"WHERE id");
	
			$leitura = read('manual_usuario',"WHERE id ORDER BY ordem ASC LIMIT $inicio,$maximo");
			if($leitura){
				echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">Id</td>
					<td>Foto</td>
					<td align="center">Ordem</td>
					<td align="center">Pergunta</td>
					<td align="center">Resposta</td>
					<td align="center" colspan="3">Gerenciar</td>
				</tr>';
			foreach($leitura as $mostra):
				echo '<tr>';
				
					echo '<td>'.$mostra['id'].'</td>';
					
				
			 if($mostra['fotopost']!= '' && file_exists('../uploads/manuais/'.$mostra['fotopost'])){
					echo '<td align="center"><img class="img-thumbnail imagem-tabela" src="'.URL.'/uploads/manuais/'.$mostra['fotopost'].'">';
				}else{
					echo '<td align="center"><i class="fa fa-picture-o"></i></td>';
              }	
				
				echo '<td>'.$mostra['ordem'].'</td>';
				
					echo '<td>'.resumos($mostra['pergunta'],$palavras = '60').'</td>';
					echo '<td>'.resumos($mostra['resposta'],$palavras = '100').'</td>';
					
					echo '<td align="center">
						  <a href="painel.php?execute=suporte/interacao/manual-editar&manualEditar='.$mostra['id'].'">
								<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
							</a>
						  </td>';
					echo '<td align="center">
						   <a href="painel.php?execute=suporte/interacao/manual-editar&manualDeletar='.$mostra['id'].'">
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
                
                 $link = 'painel.php?execute=suporte/interacao/manuals&pag=';
                 pag('manual_usuario',"WHERE id ORDER BY id ASC", $maximo, $link, $pag);
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