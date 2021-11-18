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


	$leitura = read('estoque_fornecedor',"Where id ORDER BY id_tipo ASC, nome ASC");


	if(!empty($pesquisa)){
		$pesquisa=strip_tags(trim(mysql_real_escape_string($_POST['pesquisa'])));
		$leitura =read('estoque_fornecedor',"WHERE id AND (nome LIKE '%$pesquisa%'  ORDER BY nome ASC");  
	}
     
?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Fornecedores</h1>
        <ol class="breadcrumb">
            <li>Home</a>
            <li></a>
            <li class="active">Fornecedores</li>
          </ol>
 </section>
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
         
          <div class="box-header">
            <a href="painel.php?execute=suporte/compras/fornecedor-editar" class="btnnovo">
			  <img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
				<small>Novo Fornecedor</small>
			 </a>
    	</div><!-- /.box-header -->
		 
		 <div class="box-header">
         
         
            <div class="col-xs-10 col-md-6 pull-right">
               <form name="form-pesquisa" method="post" class="form-inline " role="form">
				 <div class="input-group">
					   <input type="text" name="pesquisa" class="form-control input-sm" placeholder="Pesquisar">
					 	<div class="input-group-btn">
               			<button class="btn btn-sm btn-default" name="nome" type="submit"><i class="fa fa-search"></i></button>											
               			</div><!-- /.input-group -->
               </div><!-- /input-group-->
              </form> 
           </div>   <!-- /col-xs-10 col-md-6 pull-right-->
		 
    </div><!-- /.box-header -->
	   
    	
     
     <div class="box-body table-responsive data-spy='scroll'">
     	<div class="col-md-12 scrool">  
			<div class="box-body table-responsive">
   
<?php  
	 
				
	if($leitura){
			echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Razão Social</td>
					<td align="center">Nome Fantasia</td>
					<td align="center">Tipo</td>
					<td align="center">Bairro</td>
					<td align="center">Telefone</td>
					<td align="center">Contato</td>
					<td align="center" colspan="3">Gerenciar</td>
				</tr>';
		
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
		
				echo '<td>'.$mostra['id'].'</td>';
				echo '<td>'.$mostra['nome'].'</td>';
			    echo '<td>'.$mostra['nome_fantasia'].'</td>';
		
				$tipoId = $mostra['id_tipo'];
				$estoqueMaterial = mostra('estoque_material_tipo',"WHERE id ='$tipoId '");
				echo '<td>'.$estoqueMaterial['nome'].'</td>';
			
				echo '<td>'.$mostra['bairro'].'</td>';
				echo '<td>'.$mostra['telefone'].'</td>';
				echo '<td>'.$mostra['contato'].'</td>';
			
				echo '<td align="center">
					<a href="painel.php?execute=suporte/compras/fornecedor-editar&fornecedorEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" title="Editar" />
              			</a>
				      </td>';
			
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/compras/fornecedor-editar&fornecedorDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png" title="Excluir" />
              			</a>
						</td>';
			
			echo '</tr>';
			
		 endforeach;
		echo '</table>';
	 
		}
				
		?>
				
		 <div class="box-footer">
            <?php echo $_SESSION['cadastro'];
			unset($_SESSION['cadastro']);
			?>
        </div><!-- /.box-footer-->

	    </div><!--/box-body table-responsive-->   
	  </div><!-- /.col-md-12 scrool -->
 	  </div><!-- /.box-body table-responsive -->
 	  	  
    </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 
 </section><!-- /.content -->
</div><!-- /.content-wrapper -->