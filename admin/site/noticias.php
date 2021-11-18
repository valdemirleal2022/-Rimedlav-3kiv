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
          <h1>Not�cias</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li>Site</li>
            <li class="active">Not�cias</li>
          </ol>
  </section>

 <section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
         <div class="box-header">
            <a href="painel.php?execute=site/noticia-editar" class="btnnovo">
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
        $total = conta('categorias',"WHERE id");
        
        $leitura = read('noticias',"WHERE id ORDER BY categoria ASC LIMIT $inicio,$maximo");
        if($leitura){
                echo '<table class="table table-hover">
                        <tr class="set">
                        <td align="center">Id</td>
                        <td>Foto</td>
                        <td align="center">Titulo</td>
                        <td align="center">Pr�-Descri��o</td>
                        <td align="center">Categoria</td>
                        <td align="center">Tipo</td>
                        <td align="center">Status</td>
                        <td align="center" colspan="3">Gerenciar</td>
                    </tr>';
            foreach($leitura as $mostra):
                echo '<tr>';
                    echo '<td>'.$mostra['id'].'</td>';
                    if($mostra['fotopost']!= '' && file_exists('../uploads/noticias/'.$mostra['fotopost'])){
                        echo '<td align="center">
						<a href="#" class="abrirModal">
                              <img class="img-thumbnail imagem-tabela abrirModal" src="'.URL.'/uploads/noticias/'
                                         .$mostra['fotopost'].'"> </a>';
                      }else{
                        echo '<td align="center">
                             <i class="fa fa-picture-o"></i>
                         </td>';
                    }	
                    echo '<td>'.resumos($mostra['titulo'],$palavras = '50').'</td>';
                    echo '<td>'.resumos(ucfirst($mostra['pre']),$palavras = '30').'</td>';
                    echo '<td>'.$mostra['categoria'].'</td>';
                    if($mostra['destaques']==1){
                        echo '<td align="center">Destaques</td>';
                    }else{
                        echo '<td align="center">Normal</td>';
                    }
                    if($mostra['status']==1){
                        echo '<td align="center">Ativo</td>';
                    }else{
                        echo '<td align="center">Inativo</td>';
                    }
                    echo '<td align="center">
                          <a href="painel.php?execute=site/noticia-editar&noticiaEditar='.$mostra['id'].'">
                                <img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
                            </a>
                          </td>';
                    echo '<td align="center">
                           <a href="painel.php?execute=site/noticia-editar&noticiaDeletar='.$mostra['id'].'">
                                <img src="ico/excluir.png" alt="Excluir" title="Excluir" class="tip" />
                            </a>
                            </td>';
                    echo '<td align="center">
                           <a href="painel.php?execute=site/noticia-editar&noticiaEnviar='.$mostra['id'].'">
                                <img src="ico/email.png" alt="Enviar" title="Enviar" class="tip" />
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
			
             $link = 'painel.php?execute=site/noticias&pag=';
             pag('noticias',"WHERE id ORDER BY id ASC", $maximo, $link, $pag);
			
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
	
	
	<script>
	
	$(".abrirModal").click(function() {
  var url = $(this).find("img").attr("src");
  $("#myModal img").attr("src", url);
  $("#myModal").modal("show");
});
	
	</script>
	
	
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Exemplo</h4>
      </div>
      <div class="modal-body text-center">
        <img src="" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Sair</button>
      </div>
    </div>
  </div>
</div>