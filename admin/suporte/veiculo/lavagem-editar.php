<?php 
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php');		
			}	
		}
		
		$acao = "cadastrar";
		$edit['data'] = date("Y-m-d");

		if(!empty($_GET['lavagemEditar'])){
			$lavagemId = $_GET['lavagemEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['lavagemBaixar'])){
			$lavagemId = $_GET['lavagemBaixar'];
			$acao = "baixar";
		}
		if(!empty($_GET['lavagemDeletar'])){
			$lavagemId = $_GET['lavagemDeletar'];
			$acao = "deletar";
		}
		if(!empty($lavagemId)){
			$readlavagem= read('veiculo_lavagem',"WHERE id = '$lavagemId'");
			if(!$readlavagem){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readlavagem as $edit);
		}
 ?>
 
<div class="content-wrapper">
 
  <section class="content-header">
         
          <h1>Lavagem </h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Cadastro</a></li>
            <li><a href="painel.php?execute=suporte/veiculo/lavagens">Lavagem </a></li>
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
			
			$cad['id_veiculo'] = strip_tags(trim(mysql_real_escape_string($_POST['id_veiculo'])));
			$cad['data']= strip_tags(trim(mysql_real_escape_string($_POST['data'])));
			$cad['tipo']= strip_tags(trim(mysql_real_escape_string($_POST['tipo'])));
			$cad['executor']= strip_tags(trim(mysql_real_escape_string($_POST['executor'])));

			if(in_array('',$cad)){
				print_r($cad);
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
				
			  }else{
			 	$cad['observacao'] = strip_tags(trim(mysql_real_escape_string($_POST['observacao'])));
		
				update('veiculo_lavagem',$cad,"id = '$lavagemId'");	
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				header("Location: ".$_SESSION['url']);
				
			}
		}
		
			
		if(isset($_POST['cadastrar'])){
			
			$cad['id_veiculo'] = strip_tags(trim(mysql_real_escape_string($_POST['id_veiculo'])));
			$cad['data']= strip_tags(trim(mysql_real_escape_string($_POST['data'])));
			$cad['tipo']= strip_tags(trim(mysql_real_escape_string($_POST['tipo'])));
			$cad['executor']= strip_tags(trim(mysql_real_escape_string($_POST['executor'])));

			if(in_array('',$cad)){
				
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
				
			  }else{
				
				$cad['observacao'] = strip_tags(trim(mysql_real_escape_string($_POST['observacao'])));
				
				create('veiculo_lavagem',$cad);
				unset($cad);
				$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
				header("Location: ".$_SESSION['url']);
				
			}
		}
		
		if(isset($_POST['deletar'])){
			delete('veiculo_lavagem',"id = '$lavagemId'");
			$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
			header("Location: ".$_SESSION['url']);;
		}

	?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
		
  			<div class="form-group col-xs-12 col-md-1"> 
               <label>Id</label>
               <input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled />
            </div> 
            
           <div class="form-group col-xs-12 col-md-3">  
            <label>Veículo</label>
                <select name="id_veiculo" class="form-control">
                    <option value="">Veículo</option>
                    <?php 
                        $readConta = read('veiculo',"WHERE id ORDER BY placa ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos veiculos no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['id_veiculo'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['placa'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['placa'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
           </div> 
		
		  <div class="form-group col-xs-12 col-md-2">  
                 <label>Data</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
               <input type="date" name="data" class="form-control pull-right" value="<?php echo $edit['data'];?>"/>
                </div><!-- /.input group -->
           </div> 
		
		   <div class="form-group col-xs-12 col-md-3">  
             <label>Tipo de Lavagens</label>
                <select name="tipo" class="form-control">
                    <option value="">Selecione Tipo</option>
                    <?php 
                        $readConta = read('veiculo_lavagem_tipo',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos veiculos no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['tipo'] == $mae['id']){
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
             <label>Executor</label>
                <select name="executor" class="form-control">
                    <option value="">Selecione Executor</option>
                    <?php 
                        $readConta = read('veiculo_lavagem_executor',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos veiculos no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['executor'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
           </div> 
  
		
			<div class="form-group col-xs-12 col-md-12"> 
               <label>Observação</label>
               <input name="observacao" type="text" value="<?php echo $edit['observacao'];?>" class="form-control" />
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