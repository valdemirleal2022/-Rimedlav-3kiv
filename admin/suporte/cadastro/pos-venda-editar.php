<?php 

		 if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php?execute=suporte/403');
			}	
		}
		$acao = "cadastrar";
		if(!empty($_GET['pos-vendaEditar'])){
			$pos_vendaId = $_GET['pos-vendaEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['pos-vendaDeletar'])){
			$pos_vendaId = $_GET['pos-vendaDeletar'];
			$acao = "deletar";
		}
		if(!empty($pos_vendaId)){
			$readpos_venda = read('contrato_pos_venda',"WHERE id = '$pos_vendaId'");
			if(!$readpos_venda){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readpos_venda as $edit);
		}
 ?>

<div class="content-wrapper">
 
  <section class="content-header">
          <h1>Pos-Venda</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Cadastro</a></li>
            <li><a href="painel.php?execute=suporte/cadastro/pos_vendaes">Pos-Venda</a></li>
             <li class="active">Editar</li>
          </ol>
  </section>
  
  <section class="content">
      <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo $edit['nome_completo'];?></h3>
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
			$cad['nome']= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			$cad['email']	= strip_tags(trim(mysql_real_escape_string($_POST['email'])));
			$cad['telefone']		= strip_tags(trim(mysql_real_escape_string($_POST['telefone'])));
 			$cad['senha'] 		= strip_tags(trim(mysql_real_escape_string($_POST['senha'])));
			
			if(in_array('',$cad)){
					echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
			  }else{
				if(!empty($_FILES['fotoperfil']['tmp_name'])){
						$imagem = $_FILES['fotoperfil'];
						$pasta  = '../uploads/pos_vendas/';
				        if(file_exists($pasta.$edit['fotoperfil'])
						  && !is_dir($pasta.$edit['fotoperfil'])){
						    unlink($pasta.$edit['fotoperfil']);
						}
						$tmp    = $imagem['tmp_name'];
						$ext    = substr($imagem['name'],-3);
						$nome   = md5(time()).'.'.$ext;
						$cad['fotoperfil'] = $nome;
						uploadImg($tmp, $nome, '160', $pasta);		
				}
				$cad['observacao']=strip_tags(trim(mysql_real_escape_string($_POST['observacao'])));
				update('contrato_pos_venda',$cad,"id = '$pos_vendaId'");	
				header('Location: painel.php?execute=suporte/cadastro/pos-venda');
				unset($cad);
			}
			
		}
		
		if(isset($_POST['cadastrar'])){
			$cad['nome']= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			$cad['email']	= strip_tags(trim(mysql_real_escape_string($_POST['email'])));
			$cad['telefone']		= strip_tags(trim(mysql_real_escape_string($_POST['telefone'])));
 			$cad['senha'] 		= strip_tags(trim(mysql_real_escape_string($_POST['senha'])));
			$cad['observacao'] 		= strip_tags(trim(mysql_real_escape_string($_POST['observacao'])));
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
			  }else{
				if(!empty($_FILES['fotoperfil']['tmp_name'])){
						$imagem = $_FILES['fotoperfil'];
						$pasta  = '../uploads/pos_vendas/';
				        if(file_exists($pasta.$edit['fotoperfil'])
						  && !is_dir($pasta.$edit['fotoperfil'])){
						    unlink($pasta.$edit['fotoperfil']);
						}
						$tmp    = $imagem['tmp_name'];
						$ext    = substr($imagem['name'],-3);
						$nome   = md5(time()).'.'.$ext;
						$cad['fotoperfil'] = $nome;
						uploadImg($tmp, $nome, '160', $pasta);		
				}
				create('contrato_pos_venda',$cad);	
				header('Location: painel.php?execute=suporte/cadastro/pos-venda');
				unset($cad);
			}
		}
		
		if(isset($_POST['deletar'])){
			delete('contrato_pos_venda',"id = '$pos_vendaId'");	
			header('Location: painel.php?execute=suporte/cadastro/pos-venda');
		}

	?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
    
      	<div class="form-group">
          	<?php 
				if($edit['fotoperfil'] != '' && file_exists('../uploads/pos_vendas/'.$edit['fotoperfil'])){
					echo '<img src="../uploads/pos_vendas/'.$edit['fotoperfil'].'"/>';
				}else{
					
					echo '<img src="'.URL.'/site/images/autor.png">';
				}
					 
		?>
       </div>
      
      <div class="form-group">
      		<input type="file" name="fotoperfil"/>
      </div>
        
  			<div class="form-group col-xs-12 col-md-2"> 
               <label>Id</label>
               <input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled />
            </div> 
   
      		<div class="form-group col-xs-12 col-md-6"> 
                <label>Nome </label>
                <input type="text" name="nome" value="<?php echo $edit['nome'];?>" class="form-control"  />
            </div> 
   
      		<div class="form-group col-xs-12 col-md-4"> 
                 <label>Telefone</label>
                 <input type="text" name="telefone" value="<?php echo $edit['telefone'];?>" class="form-control"  />
         </div> 
   
      		<div class="form-group col-xs-12 col-md-6"> 
                 <label>Email</label>
                 <input name="email" type="text" value="<?php echo $edit['email'];?>" class="form-control"  />
           </div> 
   
      		<div class="form-group col-xs-12 col-md-6"> 
         
                <label>Senha:</label>
                <input name="senha"  type="password" value="<?php echo $edit['senha'];?>"   title="<?php echo $edit['senha'];?>" class="form-control" />
      	 	</div> 
      	 	
      	 	<div class="form-group col-xs-12 col-md-12"> 
				<label>Observação </label>
					<textarea  name="observacao" rows="5" cols="120" class="form-control" ><?php echo htmlspecialchars($edit['observacao']);?></textarea>
			 </div>  
   
      	<div class="box-footer">
        <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-success"> </a>
        <?php 
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
  
   </form>
 
 </div><!-- /.box-body -->
               
  </div><!-- /.col-md-12 -->

  </div><!-- /.row -->

 </section>
 
 </div><!-- /.content-wrapper -->
 