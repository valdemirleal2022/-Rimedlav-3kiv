<?php 

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php');		
			}	
		}
		
		
		if(!empty($_GET['numeracaoEditar'])){
			$numeracaoId = $_GET['numeracaoEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['numeracaoDeletar'])){
			$numeracaoId = $_GET['numeracaoDeletar'];
			$acao = "deletar";
		}

		if(!empty($numeracaoId)){
			$readnumeracao= read('estoque_equipamento_retirada_numeracao',"WHERE id = '$numeracaoId'");
			if(!$readnumeracao){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readnumeracao as $edit);
			$equipamentoId = $edit['id_equipamento'];
		}

		if(!empty($_GET['retiradaId'])){
			
			$retiradaId = $_GET['retiradaId'];
			$equipamentoRetirada = mostra('estoque_equipamento_retirada',"WHERE id = '$retiradaId'");
			if(!$equipamentoRetirada){
				header('Location: painel.php?execute=suporte/error');	
			}
			$equipamentoId = $equipamentoRetirada['id_equipamento'];
			$equipamento = mostra('estoque_equipamento',"WHERE id = 'equipamentoId'");
			
			$acao = "cadastrar";
		}
		
 ?>
 
<div class="content-wrapper">
 
  <section class="content-header">
          <h1>Numeração</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Equipamento</a></li>
            <li><a href="#">Numeração</a></li>
             <li class="active">Editar</li>
          </ol>
  </section>
  
  <section class="content">
     
     <div class="box box-default">
		 
            <div class="box-header with-border">
                <h3 class="box-title"><small><?php echo $equipamento['nome'];?></small></h3>
             	<div class="box-tools">
            		<small>
            		 <?php if($acao=='cadastrar') echo 'Cadastrando';?>
                     <?php if($acao=='deletar') echo 'Deletando';?>
                     <?php if($acao=='atualizar') echo 'Alterando';?>
                    </small>
          		</div><!-- /box-tools-->
          	 </div><!-- /.box-header -->
          	  	
          	<div class="box-header">
              <div class="row">
          		<div class="col-xs-10 col-md-3 pull-left">  
            		 
            	</div><!-- /col-xs-10 col-md-5 pull-right-->
   			 </div><!-- /row-->  
           </div><!-- /box-header-->   
      	  
    <div class="box-body">
      
	<?php 
			 
	if(isset($_POST['cadastrar'])){
		$cad['id_retirada'] = $retiradaId;
		$cad['id_equipamento'] = $equipamentoId;
		$cad['numeracao'] = strip_tags(trim(mysql_real_escape_string($_POST['numeracao'])));
		if(in_array('',$cad)){
			echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
		}else{
			create('estoque_equipamento_retirada_numeracao',$cad);
			$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
			header("Location: ".$_SESSION['url']);
		}
	}
			 
	if(isset($_POST['atualizar'])){
		$cad['numeracao'] = strip_tags(trim(mysql_real_escape_string($_POST['numeracao'])));
		if(in_array('',$cad)){
			echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
		 }else{
			update('estoque_equipamento_retirada_numeracao',$cad,"id = '$numeracaoId'");	
			$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
			header("Location: ".$_SESSION['url']);
		}
	}

	if(isset($_POST['deletar'])){
		
		delete('estoque_equipamento_retirada_numeracao',"id = '$numeracaoId'");
		
		$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
		header("Location: ".$_SESSION['url']);
	}

	?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
    
  		<div class="form-group col-xs-12 col-md-2"> 
             <label>Id</label>
            <input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled />
        </div> 

         <div class="form-group col-xs-12 col-md-6">  
            <label>Equipamento</label>
                <select name="id_equipamento" class="form-control" disabled>
                    <option value="">Equipamento</option>
                    <?php 
                        $readConta = read('estoque_equipamento',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos equipamentos no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($equipamentoId == $mae['id']){
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
          		<label>Numeração</label>
               <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-archive"></i>
                       </div>
                      <input type="text" name="numeracao" class="form-control pull-right"  value="<?php echo $edit['numeracao'];?>"/>
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
          
          </div>
		</form>
  	</div><!-- /.box-body -->
    </div><!-- /.box box-default -->
  </section><!-- /.content -->
	
 
</div><!-- /.content-wrapper -->