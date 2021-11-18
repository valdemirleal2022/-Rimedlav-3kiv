<?php 

		 if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php?execute=suporte/403');
			}	
		}
		$acao = "cadastrar";
		if(!empty($_GET['consultorEditar'])){
			$consultorId = $_GET['consultorEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['consultorDeletar'])){
			$consultorId = $_GET['consultorDeletar'];
			$acao = "deletar";
		}
		if(!empty($consultorId)){
			$readconsultor = read('contrato_consultor',"WHERE id = '$consultorId'");
			if(!$readconsultor){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readconsultor as $edit);
		}
 ?>

<div class="content-wrapper">
 
  <section class="content-header">
          <h1>Consultor</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Cadastro</a></li>
            <li><a href="painel.php?execute=suporte/cadastro/Consultores">Consultor</a></li>
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
			
			$cad['nome'] = strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			$cad['email'] = strip_tags(trim(mysql_real_escape_string($_POST['email'])));
			$cad['telefone'] = strip_tags(trim(mysql_real_escape_string($_POST['telefone'])));
 			$cad['senha'] = strip_tags(trim(mysql_real_escape_string($_POST['senha'])));
			$cad['status'] = strip_tags(trim(mysql_real_escape_string($_POST['status'])));

			if(in_array('',$cad)){
					echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
			  }else{
				if(!empty($_FILES['fotoperfil']['tmp_name'])){
						$imagem = $_FILES['fotoperfil'];
						$pasta  = '../uploads/consultores/';
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
				update('contrato_consultor',$cad,"id = '$consultorId'");	
				header('Location: painel.php?execute=suporte/cadastro/consultores');
				unset($cad);
			}
			
		}
		
		if(isset($_POST['cadastrar'])){
			
			$cad['nome'] = strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			$cad['email'] = strip_tags(trim(mysql_real_escape_string($_POST['email'])));
			$cad['telefone'] = strip_tags(trim(mysql_real_escape_string($_POST['telefone'])));
 			$cad['senha'] = strip_tags(trim(mysql_real_escape_string($_POST['senha'])));
			$cad['observacao'] = strip_tags(trim(mysql_real_escape_string($_POST['observacao'])));
			$cad['status'] = strip_tags(trim(mysql_real_escape_string($_POST['status'])));
		
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
			  }else{
				if(!empty($_FILES['fotoperfil']['tmp_name'])){
						$imagem = $_FILES['fotoperfil'];
						$pasta  = '../uploads/consultores/';
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
				create('contrato_consultor',$cad);	
				header('Location: painel.php?execute=suporte/cadastro/consultores');
				unset($cad);
			}
		}
		
		if(isset($_POST['deletar'])){
			delete('contrato_consultor',"id = '$consultorId'");	
			header('Location: painel.php?execute=suporte/cadastro/consultores');
		}

	?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
    
      	<div class="form-group">
          	<?php 
				if($edit['fotoperfil'] != '' && file_exists('../uploads/consultores/'.$edit['fotoperfil'])){
					echo '<img src="../uploads/consultores/'.$edit['fotoperfil'].'"/>';
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
   
      		<div class="form-group col-xs-12 col-md-5"> 
                <label>Nome</label>
                <input type="text" name="nome" value="<?php echo $edit['nome'];?>" class="form-control"  />
            </div> 
		
			<div class="form-group col-xs-12 col-md-5"> 
			  <label>Status</label>
				<select name="status" class="form-control">
				  <option value="">Selecione o status &nbsp;&nbsp;</option>

				  <option <?php if($edit['status'] && $edit['status'] == '1') echo' selected="selected"';?> value="1"> Ativo &nbsp;&nbsp;</option>

				  <option <?php if($edit['status'] && $edit['status'] == '0') echo' selected="selected"';?> value="0">Inativo &nbsp;&nbsp;</option>
				 </select>
			</div>

   
      		<div class="form-group col-xs-12 col-md-4"> 
                 <label>Telefone</label>
                 <input type="text" name="telefone" value="<?php echo $edit['telefone'];?>" class="form-control"  />
         </div> 
   
      		<div class="form-group col-xs-12 col-md-4"> 
                 <label>Email</label>
                 <input name="email" type="text" value="<?php echo $edit['email'];?>" class="form-control"  />
           </div> 
   
      		<div class="form-group col-xs-12 col-md-4"> 
                <label>Senha</label>
                <input name="senha"  type="password" value="<?php echo $edit['senha'];?>"   title="<?php echo $edit['senha'];?>" class="form-control" />
      	 	</div> 
      	 	
      	 	<div class="form-group col-xs-12 col-md-12"> 
				<label>Observação </label>
					<textarea  name="observacao" rows="5" cols="120" class="form-control" ><?php echo htmlspecialchars($edit['observacao']);?></textarea>
			 </div>  
   
       <div class="form-group col-xs-12 col-md-12">  
   
      	  <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>
		 
        <?php 
			if($acao=="atualizar"){
				echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';	
			}
			if($acao=="deletar"){
				 echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-danger" onClick="return confirm(\'Confirma Exclusão do Registro ?\')" />';
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
               
  </div><!-- /.col-md-12 -->

  </div><!-- /.row -->

 </section>
 
 </div><!-- /.content-wrapper -->
 