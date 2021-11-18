<?php 

	if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php?execute=suporte/403');	
			}

			if($_SESSION['autUser']['nivel']<5){
				header('Location: painel.php?execute=suporte/acessonegado');	
			}
			// 1 Operacional 2 - Comercial - 3 Faturamento - 4 - Financeiro - 5 Gerencial
	}

	echo '<head>';
    echo '<meta charset="iso-8859-1">';
	echo '</head>';
?>
<div class="content-wrapper">

  <section class="content-header">
          <h1>Categorias</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li>Site</li>
            <li class="active">Categorias</li>
          </ol>
  </section>

<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
            <div class="box-header">
                <a href="painel.php?execute=site/categoria-editar" class="btnnovo">
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
        
            $leitura = read('categorias',"WHERE id ORDER BY id ASC LIMIT $inicio,$maximo");
            if($leitura){
                    echo '<table class="table table-hover">
                            <tr class="set">
                            <td align="center">Id</td>
                            <td align="center">Nome</td>
                            <td align="center">Descrição</td>
                            <td align="center">Tags</td>
                            <td align="center">Url</td>
                            <td align="center" colspan="3">Gerenciar</td>
                        </tr>';
                    foreach($leitura as $mostra):
                        echo '<tr>';
                            echo '<td>'.$mostra['id'].'</td>';
                            echo '<td>'.$mostra['nome'].'</td>';
                            echo '<td>'.resumos(ucfirst($mostra['descricao']),$palavras = '80').'</td>';
                            echo '<td>'.resumos($mostra['tags'],$palavras = '20').'</td>';
                            echo '<td>'.resumos($mostra['url'],$palavras = '20').'</td>';
                            echo '<td align="center">
                                <a href="painel.php?execute=site/categoria-editar&categoriaEditar='.$mostra['id'].'">
                                    <img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
                                </a>
                            </td>';
                            echo '<td align="center">
                                <a href="painel.php?execute=site/categoria-editar&categoriaDeletar='.$mostra['id'].'">
                                    <img src="ico/excluir.png" alt="Excluir" title="Excluir" class="tip" />
                                </a>
                            </td>';
                     endforeach;
                     
                     echo '<tfoot>';
                        echo '<tr>';
                            echo '<td colspan="13">' . 'Total de Registros : ' .  $total . '</td>';
                        echo '</tr>';
                     echo '</tfoot>';
        
                 echo '</table>'; 
                
                 $link = 'painel.php?execute=site/categorias&pag=';
                 pag('categorias',"WHERE id ORDER BY id ASC", $maximo, $link, $pag);
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
