<?php 
		if ( function_exists( ProtUser ) ) {
			if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
				header( 'Location: painel.php?execute=suporte/403' );
			}
		}

		$acao = "cadastrar";
		if(!empty($_GET['rotaEditar'])){
			$rotaId = $_GET['rotaEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['rotaDeletar'])){
			$rotaId = $_GET['rotaDeletar'];
			$acao = "deletar";
		}
		if(!empty($rotaId)){
			$readrota = read('contrato_rota',"WHERE id = '$rotaId'");
			if(!$readrota){
				header('Location: painel.php?execute=rota/error');	
			}
			foreach($readrota as $edit);
			
			
		}
		
		if ($edit['autorizacao_gerencial'] == "1") {
			$edit['autorizacao_gerencial'] = "checked='CHECKED'";
		  } else {
			$edit['autorizacao_gerencial'] = "";
		 }
 ?>

<div class="content-wrapper">
  <section class="content-header">
          <h1>Rotas</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Cadastro</a></li>
            <li><a href="painel.php?execute=suporte/cadastro/rota">Rotas<</a></li>
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
     	 <div class="box-body">
	<?php 
			 
		if(isset($_POST['atualizar'])){
			
			 
/* Informa o nível dos erros que serão exibidos */
error_reporting(E_ALL);
 
/* Habilita a exibição de erros */
ini_set("display_errors", 1);

			
			$cad['nome'] = strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			$cad['email'] = strip_tags(trim(mysql_real_escape_string($_POST['email'])));
 			$cad['senha'] = strip_tags(trim(mysql_real_escape_string($_POST['senha'])));
			$cad['peso_medio'] = strip_tags(trim(mysql_real_escape_string($_POST['peso_medio'])));
			
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
			  }else{
				 
				update('contrato_rota',$cad,"id = '$rotaId'");	
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				header('Location: painel.php?execute=suporte/cadastro/rotas');
				unset($cad);
			}
		}
			 
		if(isset($_POST['cadastrar'])){
			
			$edit['nome'] = strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			$edit['email'] = strip_tags(trim(mysql_real_escape_string($_POST['email'])));
 			$edit['senha'] = strip_tags(trim(mysql_real_escape_string($_POST['senha'])));
			$edit['peso_medio'] = trim(mysql_real_escape_string($_POST['peso_medio']));
			create('contrato_rota',$edit);	
			$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
			unset($edit);
			header('Location: painel.php?execute=suporte/cadastro/rotas');
			 
		}
			 
		if(isset($_POST['deletar'])){
			delete('contrato_rota',"id = '$rotaId'");
			$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
			header('Location: painel.php?execute=suporte/cadastro/rotas');
		}

	?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
    
     
     
    
  			<div class="form-group col-xs-12 col-md-1"> 
               <label>Id</label>
               <input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled />
            </div> 
   
      		<div class="form-group col-xs-12 col-md-3"> 
       		   <label>Nome</label>
               <input type="text" name="nome" value="<?php echo $edit['nome'];?>"  class="form-control"/>
           </div> 
           
           
           <div class="form-group col-xs-12 col-md-4"> 
                 <label>Email</label>
                 <input name="email" type="text" value="<?php echo $edit['email'];?>" class="form-control"  />
           </div> 
   
      	   <div class="form-group col-xs-12 col-md-2"> 
                <label>Senha </label>
                <input name="senha"  type="password" value="<?php echo $edit['senha'];?>" class="form-control" />
      	 </div> 
		
		
			<div class="form-group col-xs-2">
				 
                <label>Peso Médio</label>
				 
                <select name="peso_medio" class="form-control" <?php echo $readonly;?>>
					
                     <option value="">Selecione Peso Médio</option>
                 	 <option <?php if($edit['peso_medio'] == '1') echo' selected="selected"';?> value="1">1</option>
                  	 <option <?php if($edit['peso_medio'] == '2') echo' selected="selected"';?> value="2">2</option>
				  	 <option <?php if($edit['peso_medio'] == '3') echo' selected="selected"';?> value="3">3</option>
					
                 </select>
            </div><!-- /.row -->
		
		
			<div class="form-group col-xs-3">
				 
               	 <label>Autorização</label>
				   <?php
						if($_SESSION['autUser']['nivel']==5){	//Gerencial 
							echo '<select name="autoizacao_gerencial" class="form-control" >';
						}else{
							echo '<select name="autoizacao_gerencial" class="form-control"  disabled>';
						}
					?>	
				 
                  <option value="">Selecione Autorização</option>
				
                  <option <?php if($edit['autorizacao_gerencial'] == '1') echo' selected="selected"';
						  ?> value="1" >Autorizado </option>
				   <option <?php if($edit['autorizacao_gerencial'] == '0') echo' selected="selected"';
						   ?> value="">Aguardando</option>
				 
                 </select>
				  
            </div>  
		
	      
           <div class="form-group col-xs-12 col-md-12">          
			 <div class="box-footer">
				<a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>
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
		  </div>  
   </form>
   
		</div><!-- /.box-body -->
   </div><!-- /.box box-default -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->
