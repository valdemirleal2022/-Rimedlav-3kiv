<?php 
	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'],'1')){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}
	echo '<head>';
    echo '<meta charset="iso-8859-1">';
	echo '</head>';
?>

<div class="content-wrapper">

  <section class="content-header">
          <h1>FAQ</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li>Site</li>
            <li class="active">FAQ</li>
          </ol>
  </section>

<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
         <div class="box-header">
            <a href="painel.php?execute=site/faq-editar" class="btnnovo">
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
            
            $total = conta('faq',"WHERE id");
	
			$leitura = read('faq',"WHERE id ORDER BY id ASC LIMIT $inicio,$maximo");
			if($leitura){
				echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Pergunta</td>
					<td align="center">Resposta</td>
					<td align="center" colspan="3">Gerenciar</td>
				</tr>';
			foreach($leitura as $mostra):
				echo '<tr>';
					echo '<td>'.$mostra['id'].'</td>';
					echo '<td>'.resumos($mostra['pergunta'],$palavras = '60').'</td>';
					echo '<td>'.resumos($mostra['resposta'],$palavras = '100').'</td>';
					
					echo '<td align="center">
						  <a href="painel.php?execute=site/faq-editar&faqEditar='.$mostra['id'].'">
								<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
							</a>
						  </td>';
					echo '<td align="center">
						   <a href="painel.php?execute=site/faq-editar&faqDeletar='.$mostra['id'].'">
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
                
                 $link = 'painel.php?execute=site/faqs&pag=';
                 pag('faq',"WHERE id ORDER BY id ASC", $maximo, $link, $pag);
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