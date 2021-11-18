<?php 
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php');		
			}	
		}

		$acao = "cadastrar";
		if(!empty($_GET['equipamentoEditar'])){
			$equipamentoId = $_GET['equipamentoEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['equipamentoDeletar'])){
			$equipamentoId = $_GET['equipamentoDeletar'];
			$acao = "deletar";
		}
		if(!empty($equipamentoId)){
			$readequipamento = read('estoque_equipamento',"WHERE id = '$equipamentoId'");
			if(!$readequipamento){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readequipamento as $edit);
		}

 ?>
 
<div class="content-wrapper">
  <section class="content-header">
          <h1>Equipamentos</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Estoque</a></li>
            <li><a href="painel.php?execute=suporte/estoque/equipamento-editar">Eequipamentos</a></li>
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
			$cad['nome'] 	= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			$cad['estoque'] 	= strip_tags(trim(mysql_real_escape_string($_POST['estoque'])));
			$cad['estoque_minimo'] 	= strip_tags(trim(mysql_real_escape_string($_POST['estoque_minimo'])));
			$cad['valor_unitario'] 	= strip_tags(trim(mysql_real_escape_string($_POST['valor_unitario'])));
			$cad['valor_unitario'] = str_replace(",",".",str_replace(".","",$cad['valor_unitario']));
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos s�o obrigat�rios!</div>';	
			  }else{
				update('estoque_equipamento',$cad,"id = '$equipamentoId'");	
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				header('Location: painel.php?execute=suporte/estoque/equipamentos');
				unset($cad);
			}
		}
		if(isset($_POST['cadastrar'])){
			$cad['nome'] 	= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			$cad['estoque'] 	= strip_tags(trim(mysql_real_escape_string($_POST['estoque'])));
			$cad['estoque_minimo'] 	= strip_tags(trim(mysql_real_escape_string($_POST['estoque_minimo'])));
			$cad['valor_unitario'] 	= strip_tags(trim(mysql_real_escape_string($_POST['valor_unitario'])));
			$cad['valor_unitario'] = str_replace(",",".",str_replace(".","",$cad['valor_unitario']));
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos s�o obrigat�rios!</div>';	
			  }else{
				create('estoque_equipamento',$cad);	
				$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
				header('Location: painel.php?execute=suporte/estoque/equipamentos');
				unset($cad);
			}
		}
		if(isset($_POST['deletar'])){
			delete('estoque_equipamento',"id = '$equipamentoId'");
			$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
			header('Location: painel.php?execute=suporte/estoque/equipamentos');
		}

	?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
  			<div class="form-group col-xs-12 col-md-1"> 
               <label>Id</label>
               <input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled />
            </div> 
   
      		<div class="form-group col-xs-12 col-md-5"> 
       		   <label>Nome</label>
               <input type="text" name="nome" value="<?php echo $edit['nome'];?>"  class="form-control"/>
           </div> 
           
           <div class="form-group col-xs-12 col-md-2"> 
       		   <label>Estoque</label>
               <input type="text" name="estoque"  align="right" value="<?php echo $edit['estoque'];?>"  class="form-control"/>
           </div> 
           
           <div class="form-group col-xs-12 col-md-2"> 
       		   <label>Estoque M�nimo</label>
               <input type="text" name="estoque_minimo"  align="right" value="<?php echo $edit['estoque_minimo'];?>"  class="form-control"/>
           </div> 
           
			
           
           <div class="form-group col-xs-12 col-md-2">  
          		<label>Valor Unit�rio</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="valor_unitario" class="form-control pull-right" value="<?php echo converteValor($edit['valor_unitario']);?>" <?php echo $readonly;?> />
                 </div><!-- /.input group -->
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
                </form>
        
           </div> 
  	</div><!-- /.box-body -->
    </div><!-- /.box box-default -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->