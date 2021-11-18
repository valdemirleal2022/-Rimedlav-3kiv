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
          <h1>Eventos</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li>Site</li>
            <li class="active">Eventos</li>
          </ol>
  </section>

<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
         <div class="box-header">
        
            <a href="painel.php?execute=site/eventos-editar" class="btnnovo">
	   		<img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" />
    		</a>
            
       		<div class="box-tools">
                 
            </div><!-- /box-tools-->
       </div><!-- /.box-header -->
            
     <div class="box-body table-responsive">
                
		<?php 
        
            $pag = (empty($_GET['pag']) ? '1' : $_GET['pag']);
            if(isset($_POST['contador'])){
                $maximo=($_POST['numero']);
              }else{
                $maximo = '10';
            }
            $inicio = ($pag * $maximo) - $maximo;
            
            $total = conta('eventos',"WHERE id");
	
	$leitura = read('eventos',"WHERE id ORDER BY data DESC LIMIT $inicio,$maximo");
	if($leitura){
			 echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">Id</td>
					<td>Imagem</td>
					<td align="center">Data</td>
					<td align="center">Hora</td>
					<td align="center">Local</td>
					<td align="center">Titulo</td>
					<td align="center">Descrição</td>
					<td align="center" colspan="3">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		 	echo '<tr>';
				echo '<td>'.$mostra['id'].'</td>';
				if($mostra['fotopost']!= '' && file_exists('../uploads/eventos/'.$mostra['fotopost'])){
					echo '<td align="center">
						  <img class="img-thumbnail imagem-tabela" src="'.URL.'/uploads/eventos/'
                                     .$mostra['fotopost'].'">';
				  }else{
					echo '<td align="center">
		  				 <i class="fa fa-picture-o"></i>
				   	 </td>';
				}	
				echo '<td>'.converteData($mostra['data']).'</td>';
				echo '<td>'.$mostra['hora'].'</td>';
				echo '<td>'.$mostra['local'].'</td>';
				echo '<td>'.resumos($mostra['titulo'],$palavras = '50').'</td>';
				echo '<td>'.resumos($mostra['descricao'],$palavras = '50').'</td>';
				echo '<td align="center">
					  <a href="painel.php?execute=site/evento-editar&eventoEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
              			</a>
				      </td>';
				echo '<td align="center">
			  		   <a href="painel.php?execute=site/evento-editar&eventoDeletar='.$mostra['id'].'">
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
                
                 $link = 'painel.php?execute=site/eventos&pag=';
                 pag('eventos',"WHERE id ORDER BY data DESC", $maximo, $link, $pag);
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