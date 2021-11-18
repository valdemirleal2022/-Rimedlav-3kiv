<?php 
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php');		
			}	
		}
		$acao = "cadastrar";
		if(!empty($_GET['aterroEditar'])){
			$aterroId = $_GET['aterroEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['aterroDeletar'])){
			$aterroId = $_GET['aterroDeletar'];
			$acao = "deletar";
		}
		if(!empty($aterroId)){
			$readaterro = read('aterro',"WHERE id = '$aterroId'");
			if(!$readaterro){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readaterro as $edit);
		}
 ?>
 
<div class="content-wrapper">
  <section class="content-header">
          <h1>Aterros</h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Veículos</a></li>
            <li><a href="#">Aterros</a></li>
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
			$cad['endereco'] 	= strip_tags(trim(mysql_real_escape_string($_POST['endereco'])));
			$cad['bairro'] 	= strip_tags(trim(mysql_real_escape_string($_POST['bairro'])));
			$cad['cidade'] 	= strip_tags(trim(mysql_real_escape_string($_POST['cidade'])));
			$cad['cep'] 	= strip_tags(trim(mysql_real_escape_string($_POST['cep'])));
			$cad['inea'] 	= strip_tags(trim(mysql_real_escape_string($_POST['inea'])));
			$cad['email'] 	= strip_tags(trim(mysql_real_escape_string($_POST['email'])));
			$cad['telefone'] 	= strip_tags(trim(mysql_real_escape_string($_POST['telefone'])));
			$cad['contato'] 	= strip_tags(trim(mysql_real_escape_string($_POST['contato'])));
			$cad['tratamento'] 	= strip_tags(trim(mysql_real_escape_string($_POST['tratamento'])));
			$cad['responsavel'] 	= strip_tags(trim(mysql_real_escape_string($_POST['responsavel'])));
			$cad['cargo'] 	= strip_tags(trim(mysql_real_escape_string($_POST['cargo'])));
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
			  }else{
				update('aterro',$cad,"id = '$aterroId'");	
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				header('Location: painel.php?execute=suporte/veiculo/aterros');
			}
		}
		if(isset($_POST['cadastrar'])){
			$cad['nome'] 	= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			$cad['endereco'] 	= strip_tags(trim(mysql_real_escape_string($_POST['endereco'])));
			$cad['bairro'] 	= strip_tags(trim(mysql_real_escape_string($_POST['bairro'])));
			$cad['cidade'] 	= strip_tags(trim(mysql_real_escape_string($_POST['cidade'])));
			$cad['cep'] 	= strip_tags(trim(mysql_real_escape_string($_POST['cep'])));
			$cad['inea'] 	= strip_tags(trim(mysql_real_escape_string($_POST['inea'])));
			$cad['email'] 	= strip_tags(trim(mysql_real_escape_string($_POST['email'])));
			$cad['telefone'] 	= strip_tags(trim(mysql_real_escape_string($_POST['telefone'])));
			$cad['contato'] 	= strip_tags(trim(mysql_real_escape_string($_POST['contato'])));
			$cad['tratamento'] 	= strip_tags(trim(mysql_real_escape_string($_POST['tratamento'])));
			$cad['responsavel'] 	= strip_tags(trim(mysql_real_escape_string($_POST['responsavel'])));
			$cad['cargo'] 	= strip_tags(trim(mysql_real_escape_string($_POST['cargo'])));
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
			  }else{
				create('aterro',$cad);	
				$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
				header('Location: painel.php?execute=suporte/veiculo/aterros');
			}
		}
		if(isset($_POST['deletar'])){
			delete('aterro',"id = '$aterroId'");
			$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
			header('Location: painel.php?execute=suporte/veiculo/aterros');
		}

	?>
	
	
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
    
    	 <div class="box-body">
    	 
  			<div class="form-group col-xs-12 col-md-1"> 
               <label>Id</label>
               <input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled />
            </div> 
   
      		<div class="form-group col-xs-12 col-md-9"> 
       		   <label>Nome</label>
               <input type="text" name="nome" value="<?php echo $edit['nome'];?>"  class="form-control"/>
           </div> 
           	
           <div class="form-group col-xs-12 col-md-2">  
				<label>INEA</label>
				 <input name="inea"  class="form-control" type="text" value="<?php echo $edit['inea'];?>"/>
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
              
              
             
             <div class="form-group col-xs-12 col-md-4">
					<label>Tipo de Tratamento</label>
					<select name="tratamento" class="form-control">
									<option value="">Selecione o tipo tratamento</option>
									<?php 
                                    $leitura = read('aterro_tratamento',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Não temos tipo residuo no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['tratamento'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?>
					</select>
				</div>
             		
             	<div class="form-group col-xs-12 col-md-4"> 
				   <label>Responsável</label>
				   <input type="text" name="responsavel" value="<?php echo $edit['responsavel'];?>"  class="form-control"/>
			   </div> 


			<div class="form-group col-xs-12 col-md-4">  
				 <label>Cargo</label>
				  <input type="text" name="cargo" class="form-control" value="<?php echo $edit['cargo'];?>"  />
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