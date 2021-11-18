<?php 
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php');		
			}	
		}
		
		$acao = "cadastrar";
		if(!empty($_GET['reposicaoEditar'])){
			$reposicaoId = $_GET['reposicaoEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['reposicaoBaixar'])){
			$reposicaoId = $_GET['reposicaoBaixar'];
			$acao = "baixar";
		}
		if(!empty($_GET['reposicaoDeletar'])){
			$reposicaoId = $_GET['reposicaoDeletar'];
			$acao = "deletar";
		}
		if(!empty($reposicaoId)){
			$readreposicao= read('estoque_equipamento_reposicao',"WHERE id = '$reposicaoId'");
			if(!$readreposicao){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readreposicao as $edit);

		}
 ?>
 
<div class="content-wrapper">
  <section class="content-header">
          <h1>Reposição</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Equipamento</a></li>
            <li><a href="#">Reposição</a></li>
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
		$cad['id_equipamento']= strip_tags(trim(mysql_real_escape_string($_POST['id_equipamento'])));
		$cad['quantidade'] 	= strip_tags(trim(mysql_real_escape_string($_POST['quantidade'])));
		$cad['nota_fiscal'] 	= strip_tags(trim(mysql_real_escape_string($_POST['nota_fiscal'])));
		$cad['data'] 	= strip_tags(trim(mysql_real_escape_string($_POST['data'])));
		$cad['valor_unitario'] 	= strip_tags(trim(mysql_real_escape_string($_POST['valor_unitario'])));
		$cad['valor_unitario'] = str_replace(",",".",str_replace(".","",$cad['valor_unitario']));
		if(in_array('',$cad)){
			echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
		 }else{
			update('estoque_equipamento_reposicao',$cad,"id = '$reposicaoId'");	
			
			//ATUALIZA ESTOQUE;
			$equipamentoId=$cad['id_equipamento'];
			$estoque= mostra('estoque_equipamento',"WHERE id = '$equipamentoId'");
			
			//10+15=25-10
			$est['valor_unitario'] = $cad['valor_unitario'];
			$est['estoque'] = ($estoque['estoque'] - $edit['quantidade']) + $cad['quantidade'];
			update('estoque_equipamento',$est,"id = '$equipamentoId'");	
			
			$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
			header('Location: painel.php?execute=suporte/estoque/equipamento-reposicao');
			unset($cad);
		}
	}
		
	if(isset($_POST['cadastrar'])){
		$cad['id_equipamento'] = strip_tags(trim(mysql_real_escape_string($_POST['id_equipamento'])));
		$cad['quantidade'] = strip_tags(trim(mysql_real_escape_string($_POST['quantidade'])));
		$cad['data'] = strip_tags(trim(mysql_real_escape_string($_POST['data'])));
		$cad['nota_fiscal'] 	= strip_tags(trim(mysql_real_escape_string($_POST['nota_fiscal'])));
		$cad['valor_unitario'] = strip_tags(trim(mysql_real_escape_string($_POST['valor_unitario'])));
		$cad['valor_unitario'] = str_replace(",",".",str_replace(".","",$cad['valor_unitario']));
		if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
		}else{
			create('estoque_equipamento_reposicao',$cad);
			
			//ATUALIZA ESTOQUE;
			$equipamentoId=$cad['id_equipamento'];
			$estoque= mostra('estoque_equipamento',"WHERE id = '$equipamentoId'");
			$est['estoque'] = $estoque['estque'] + $cad['quantidade'];
			update('estoque_equipamento',$est,"id = '$equipamentoId'");	
	
			$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
			header('Location: painel.php?execute=suporte/estoque/equipamento-reposicao');
			unset($cad);
		}
	}
		
	if(isset($_POST['deletar'])){
		delete('estoque_equipamento_reposicao',"id = '$reposicaoId'");
		$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
		header('Location: painel.php?execute=suporte/estoque/equipamento-reposicao');
	}

	?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
		
  			<div class="form-group col-xs-12 col-md-1"> 
               <label>Id</label>
               <input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled />
            </div> 
            
           <div class="form-group col-xs-12 col-md-5">  
            <label>Equipamento</label>
                <select name="id_equipamento" class="form-control">
                    <option value="">Equipamento</option>
                    <?php 
                        $readConta = read('estoque_equipamento',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos equipamentos no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['id_equipamento'] == $mae['id']){
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
          		<label>Quantidade</label>
               <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-archive"></i>
                       </div>
                      <input type="text" name="quantidade" class="form-control pull-right"  value="<?php echo $edit['quantidade'];?>"/>
                 </div><!-- /.input group -->
           </div>
           

           <div class="form-group col-xs-12 col-md-3">  
                 <label>Data</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
               <input type="date" name="data" class="form-control pull-right" value="<?php echo $edit['data'];?>"/>
                </div><!-- /.input group -->
           </div> 
           
           <div class="form-group col-xs-12 col-md-3">  
          		<label>Valor</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="valor_unitario" class="form-control pull-right"  value="<?php echo converteValor($edit['valor_unitario']);?>"/>
                 </div><!-- /.input group -->
           </div>
		
			<div class="form-group col-xs-12 col-md-3"> 
               <label>Nota Fiscal</label>
               <input name="nota_fiscal" type="text" value="<?php echo $edit['nota_fiscal'];?>" class="form-control" />
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