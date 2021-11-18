<?php 
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php');		
			}	
		}
		$acao = "cadastrar";
		if(!empty($_GET['fornecedorEditar'])){
			$fornecedorId = $_GET['fornecedorEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['fornecedorDeletar'])){
			$fornecedorId = $_GET['fornecedorDeletar'];
			$acao = "deletar";
		}
		if(!empty($fornecedorId)){
			$readfornecedor = read('estoque_fornecedor',"WHERE id = '$fornecedorId'");
			if(!$readfornecedor){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readfornecedor as $edit);
		}
 ?>
 
<div class="content-wrapper">
  <section class="content-header">
          <h1>Fornecedores</h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Compras</a></li>
            <li><a href="#">Fornecedores</a></li>
             <li class="active">Editar</li>
          </ol>
  </section>
  
  <section class="content">
      <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><small><?php echo $edit['nome'];?></small></h3>
             	<div class="box-tools">
            		<small>
            		 <?php if($acao=='cadastrar') echo 'Cadastrando';?>
                     <?php if($acao=='deletar') echo 'Deletando';?>
                     <?php if($acao=='atualizar') echo 'Alterando';?>
                    </small>
          		</div><!-- /box-tools-->
      	  </div><!-- /.box-header -->
      	  
     
      
	<?php 
		  
		if(isset($_POST['atualizar'])){
			
			$cad['nome'] 	= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			$cad['nome_fantasia'] 	= strip_tags(trim(mysql_real_escape_string($_POST['nome_fantasia'])));
		
			$cad['endereco'] 	= strip_tags(trim(mysql_real_escape_string($_POST['endereco'])));
			$cad['bairro'] 	= strip_tags(trim(mysql_real_escape_string($_POST['bairro'])));
			$cad['cidade'] 	= strip_tags(trim(mysql_real_escape_string($_POST['cidade'])));
			$cad['cep'] 	= strip_tags(trim(mysql_real_escape_string($_POST['cep'])));
			$cad['email'] 	= strip_tags(trim(mysql_real_escape_string($_POST['email'])));
			$cad['telefone'] 	= strip_tags(trim(mysql_real_escape_string($_POST['telefone'])));
			$cad['contato'] 	= strip_tags(trim(mysql_real_escape_string($_POST['contato'])));
			$cad['cnpj'] 	= strip_tags(trim(mysql_real_escape_string($_POST['cnpj'])));
			$cad['id_tipo'] 	= strip_tags(trim(mysql_real_escape_string($_POST['id_tipo'])));
			
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
			  }else{
				update('estoque_fornecedor',$cad,"id = '$fornecedorId'");	
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				header('Location: painel.php?execute=suporte/compras/fornecedores');
			}
		}
		  
		if(isset($_POST['cadastrar'])){
			
			$cad['nome'] 	= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			$cad['nome_fantasia'] 	= strip_tags(trim(mysql_real_escape_string($_POST['nome_fantasia'])));
			$cad['endereco'] 	= strip_tags(trim(mysql_real_escape_string($_POST['endereco'])));
			$cad['bairro'] 	= strip_tags(trim(mysql_real_escape_string($_POST['bairro'])));
			$cad['cidade'] 	= strip_tags(trim(mysql_real_escape_string($_POST['cidade'])));
			$cad['cep'] 	= strip_tags(trim(mysql_real_escape_string($_POST['cep'])));
			$cad['email'] 	= strip_tags(trim(mysql_real_escape_string($_POST['email'])));
			$cad['telefone'] 	= strip_tags(trim(mysql_real_escape_string($_POST['telefone'])));
			$cad['contato'] 	= strip_tags(trim(mysql_real_escape_string($_POST['contato'])));
			$cad['cnpj'] 	= strip_tags(trim(mysql_real_escape_string($_POST['cnpj'])));
			$cad['id_tipo'] 	= strip_tags(trim(mysql_real_escape_string($_POST['id_tipo'])));
		
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
			  }else{
				create('estoque_fornecedor',$cad);	
				$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
				header('Location: painel.php?execute=suporte/compras/fornecedores');
			}
		}
		  
		if(isset($_POST['deletar'])){
			delete('estoque_fornecedor',"id = '$fornecedorId'");
			$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
			header('Location: painel.php?execute=suporte/compras/fornecedors');
		}

	?>
	
	
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
    
    	 <div class="box-body">
    	 
  			<div class="form-group col-xs-12 col-md-1"> 
               <label>Id</label>
               <input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled />
            </div> 
   
      		<div class="form-group col-xs-12 col-md-6"> 
       		   <label>Razão Social </label>
               <input type="text" name="nome" value="<?php echo $edit['nome'];?>"  class="form-control"/>
           </div> 
			 
			<div class="form-group col-xs-12 col-md-5"> 
       		   <label>Nome Fantasia </label>
               <input type="text" name="nome_fantasia" value="<?php echo $edit['nome_fantasia'];?>"  class="form-control"/>
           </div> 

          
           <div class="form-group col-xs-12 col-md-5"> 
       		   <label>Endereço</label>
               <input type="text" name="endereco" value="<?php echo $edit['endereco'];?>"  class="form-control"/>
           </div> 
           
             <div class="form-group col-xs-12 col-md-3"> 
       		   <label>Bairro</label>
               <input type="text" name="bairro" value="<?php echo $edit['bairro'];?>"  class="form-control"/>
           </div> 
           
            <div class="form-group col-xs-12 col-md-2"> 
       		   <label>Cidade</label>
               <input type="text" name="cidade" value="<?php echo $edit['cidade'];?>"  class="form-control"/>
           </div> 

           	<div class="form-group col-xs-12 col-md-2"> 
       		   <label>Cep</label>
               <input type="text" name="cep" value="<?php echo $edit['cep'];?>"  class="form-control"/>
           </div> 
           
            <div class="form-group col-xs-12 col-md-4">  
                   <label>Telefone</label>
                   <input name="telefone"  class="form-control" type="text" value="<?php echo $edit['telefone'];?>"/>
            </div>
			
              
            <div class="form-group col-xs-12 col-md-4">  
                   <label>Contato</label>
                   <input type="text" name="contato" class="form-control" value="<?php echo $edit['contato'];?>"  />
             </div>
             
              <div class="form-group col-xs-12 col-md-4">  
                   <label>Email</label>
                   <input type="text" name="email" class="form-control" value="<?php echo $edit['email'];?>"  />
             </div>
			 
			  <div class="form-group col-xs-12 col-md-6">  
                   <label>CNPJ/CPF</label>
                   <input type="text" name="cnpj" class="form-control" value="<?php echo $edit['cnpj'];?>"  />
             </div>
			 
			<div class="form-group col-xs-12 col-md-6">  
				 <label>Tipo Material</label>
				<select name="id_tipo" class="form-control input-sm"  >
					<option value="">Selecione o Tipo</option>
							<?php 
							$readContrato = read('estoque_material_tipo',"WHERE id ORDER BY codigo ASC");
							if(!$readContrato){
								echo '<option value="">Nao registro no momento</option>';	
							}else{
								foreach($readContrato as $mae):
									if($edit['id_tipo'] == $mae['id']){
										echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['codigo'].' - '.$mae['nome'].'</option>';
									}else{
										echo '<option value="'.$mae['id'].'">'.$mae['codigo'].' -  '.$mae['nome'].'</option>';
									}
									endforeach;	
								}
							?> 
				 </select>
			</div> 


		 </div>
            
         <div class="box-body">
             
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