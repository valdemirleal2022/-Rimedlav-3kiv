<?php 
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php');		
			}	
		}

		if(!empty($_GET['solicitacaoId'])){
			$solicitacaoId = $_GET['solicitacaoId'];
			$acao = "cadastrar";
			$edit['status']='Aguardando';
		}

		if(!empty($_GET['compraMaterialEditar'])){
			$compraMaterialId = $_GET['compraMaterialEditar'];
			$acao = "atualizar";
		}

		if(!empty($_GET['compraMaterialDeletar'])){
			$compraMaterialId = $_GET['compraMaterialDeletar'];
			$acao = "deletar";
		}
		if(!empty($compraMaterialId)){
			$readcompra= read('estoque_compras_material',"WHERE id = '$compraMaterialId'");
			if(!$readcompra){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readcompra as $edit);
			$compraMaterialId=$edit['id'];
			$solicitacaoId=$edit['id_compras'];

		}

		if(!empty($solicitacaoId)){
			$readcompra= read('estoque_compras',"WHERE id = '$solicitacaoId'");
			if(!$readcompra){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readcompra as $compras);
			
			$materialId=$compras['id_material'];
			$materialTipo= mostra('estoque_material_tipo',"WHERE id = '$materialId'");

		}

 ?>
 
<div class="content-wrapper">
  <section class="content-header">
          <h1>Solicitação - Material</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Compras</a></li>
            <li><a href="#">Solicitação</a></li>
             <li class="active">Editar</li>
          </ol>
  </section>
  <section class="content">
      <div class="box box-default">
		  
            <div class="box-header with-border">
				
				<h3 class="box-title">Id da Compra : <?php echo  $solicitacaoId ; ?> || Tipo Material : <?php echo  $materialTipo['nome'] ; ?></h3>
				
             	<div class="box-tools">
            		<small>
            		 <?php if($acao=='cadastrar') echo 'Cadastrando';?>
                     <?php if($acao=='deletar') echo 'Deletando';?>
                     <?php if($acao=='atualizar') echo 'Alterando';?>
                    </small>
          		</div><!-- /box-tools-->
			 
		    </div><!-- /.box-header -->
		  
     	 <div class="box-body">
      
	<?php 
			 
	if(isset($_POST['atualizar'])){
		
		$cad['id_material'] = strip_tags(trim(mysql_real_escape_string($_POST['id_material'])));
		$cad['quantidade'] = strip_tags(trim(mysql_real_escape_string($_POST['quantidade'])));
		$cad['unidade'] = strip_tags(trim(mysql_real_escape_string($_POST['unidade'])));
		
		if(in_array('',$cad)){
 
			echo '<div class="alert alert-warning">Todos os campos com (*) são obrigatórios!</div>';	
		 }else{
			$cad['observacao'] = strip_tags(trim(mysql_real_escape_string($_POST['observacao'])));
			update('estoque_compras_material',$cad,"id = '$compraMaterialId'");	
			
			$_SESSION['cadastro'] = '<div class="alert alert-success">Alterado com sucesso</div>';
			
			header('Location: painel.php?execute=suporte/compras/solicitacao-editar&solicitacaoEditar='.$solicitacaoId);
			
		}
		
	}
		
	if(isset($_POST['cadastrar'])){
		
		$cad['id_compras'] = $solicitacaoId;
		$cad['id_material'] = strip_tags(trim(mysql_real_escape_string($_POST['id_material'])));
		$cad['quantidade'] = strip_tags(trim(mysql_real_escape_string($_POST['quantidade'])));
		$cad['unidade'] = strip_tags(trim(mysql_real_escape_string($_POST['unidade'])));
		
		if(in_array('',$cad)){

			echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
		}else{
			
			$cad['observacao'] = strip_tags(trim(mysql_real_escape_string($_POST['observacao'])));
			create('estoque_compras_material',$cad);
			
			$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
			
			header('Location: painel.php?execute=suporte/compras/solicitacao-editar&solicitacaoEditar='.$solicitacaoId);
		}
	}
		
	if(isset($_POST['deletar'])){
		
		delete('estoque_compras_material',"id = '$solicitacaoId'");
		$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
		header('Location: painel.php?execute=suporte/compras/solicitacao-editar');
		
	}

	?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
		
  		  <div class="form-group col-xs-12 col-md-2">  
               <label>Id </label>
               <input name="id" type="text" value="<?php echo $edit['id'];?>"  readonly class="form-control" />
          </div> 
            
		 <div class="form-group col-xs-12 col-md-4">  
            <label>Material (*)</label>
                <select name="id_material" class="form-control">
                    <option value="">Selecione o Material</option>
                    <?php 
                        $readConta = read('estoque_material',"WHERE id AND id_tipo='$materialId' ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos marerial no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['id_material'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
          </div> 
		
		 <div class="form-group col-xs-12 col-md-3">  
               <label>Quantidade (*)</label>
               <input name="quantidade" type="text" value="<?php echo $edit['quantidade'];?>"  class="form-control" />
          </div> 
		
		 <div class="form-group col-xs-12 col-md-3">  
               <label>Unidade Medida (*)</label>
               <input name="unidade" type="text" value="<?php echo $edit['unidade'];?>"  class="form-control" />
          </div> 
		
		
		 <div class="form-group col-xs-12 col-md-12">  
               <label>Observação </label>
               <input name="observacao" type="text" value="<?php echo $edit['observacao'];?>"  class="form-control" />
          </div> 

    	 <div class="form-group col-xs-12 col-md-12">  
             
		 <div class="box-footer">
			 <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>
				  <?php 
					if($acao=="baixar"){
						echo '<input type="submit" name="baixar" value="Baixar" class="btn btn-primary" />';	
					}
					 if($acao=="atualizar"){
						echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';	
					}
					if($acao=="deletar"){
						echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-danger" />';	
					}
					if($acao=="cadastrar"){
						echo '<input type="submit" name="cadastrar" value="Cadastrar" class="btn btn-primary" />';	
					}
					if($acao=="enviar"){
						echo '<input type="submit" name="enviar" value="Enviar" class="btn btn-primary" />';	
					}
				 ?>  
			  </div> 
          
          </div>
		</form>
  	</div><!-- /.box-body -->
    </div><!-- /.box box-default -->
  </section><!-- /.content -->
	
	
	
</div><!-- /.content-wrapper -->