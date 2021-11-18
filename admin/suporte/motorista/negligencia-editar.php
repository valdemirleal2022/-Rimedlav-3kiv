<?php 
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php?execute=suporte/403');	
			}	
		}
		
		$acao = "cadastrar";
		if(!empty($_GET['motoristaId'])){
			$edit['id_motorista'] = $_GET['motoristaId'];
			$readonly = "readonly";
			$disabled = 'disabled="disabled"';
		}

		$edit['data']= date("Y-m-d");
		
			
		if(!empty($_GET['negligenciaEditar'])){
			$negligenciaId = $_GET['negligenciaEditar'];
			$acao = "atualizar";
		}
		
		if(!empty($_GET['negligenciaDeletar'])){
			$negligenciaId = $_GET['negligenciaDeletar'];
			$acao = "deletar";
		}
	 
		

		if(!empty($negligenciaId)){
			$readnegligencia = read('veiculo_motorista_negligencia',"WHERE id = '$negligenciaId'");
			if(!$readnegligencia){
				header('Location: painel.php?execute=suporte/naoencontrado');	
			}
			foreach($readnegligencia as $edit);
			
			$motoristaId=$edit['id_motorista'];
		}


		
		if(!empty($motoristaId)){
			 
			$motorista = mostra('veiculo_motorista',"WHERE id = '$motoristaId'");
			if(!$motorista){
				header('Location: painel.php?execute=suporte/error');
			}
		}

	 
 		
 ?>
 
 <div class="content-wrapper">
  <section class="content-header">
          <h1>Negligencia</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Motorista</a></li>
            <li><a href="painel.php?execute=suporte/motorista/negligencia">Negligencia</a></li>
             <li class="active">Editar</li>
          </ol>
  </section>
  
  <section class="content">
      <div class="box box-default">
           
            <div class="box-header with-border">
				    <h3 class="box-title"><?php echo $motorista['nome'];?></h3>
             	<div class="box-tools">
            		<small>
            		 <?php if($acao=='cadastrar') echo 'Cadastrando';?>
                     <?php if($acao=='deletar') echo 'Deletando';?>
                     <?php if($acao=='atualizar') echo 'Alterando';?>
                     <?php if($acao=='baixar') echo 'Baixando';?>
                    </small>
          		</div><!-- /box-tools-->
      	  </div><!-- /.box-header -->
	
      <div class="box-body">

		<?php 
		
			if(isset($_POST['atualizar'])){
				
				$edit['id_negligencia'] = strip_tags(trim(mysql_real_escape_string($_POST['id_negligencia'])));
				$edit['data'] 	= htmlspecialchars(stripslashes($_POST['data']));
				$edit['rota'] = htmlspecialchars(stripslashes($_POST['rota']));
				
				update('veiculo_motorista_negligencia',$edit,"id = '$negligenciaId'");	
				
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				
				 header('Location: painel.php?execute=suporte/motorista/negligencias');
			}

			if(isset($_POST['cadastrar'])){
				
				$edit['id_negligencia']= strip_tags(trim(mysql_real_escape_string($_POST['id_negligencia'])));
				$edit['data'] = htmlspecialchars(stripslashes($_POST['data']));
				$edit['rota'] = htmlspecialchars(stripslashes($_POST['rota']));
				$edit['id_motorista']=htmlspecialchars(stripslashes($_POST['id_motorista']));
				if(in_array('',$edit)){
					echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
				  }else{
				    create('veiculo_motorista_negligencia',$edit);
					$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
				 	echo $_SESSION['cadastro'];
					unset($_SESSION['cadastro']);
				 
				  // header('Location: painel.php?execute=suporte/motorista/negligencias');
		    	}
			}
			
			if(isset($_POST['deletar'])){
					$readDeleta = read('veiculo_motorista_negligencia',"WHERE id = '$negligenciaId'");
					if(!$readDeleta){
						echo '<div class="alert alert-warning">Desculpe, o registro não existe!</div><br />';
					}else{
						delete('veiculo_motorista_negligencia',"id = '$negligenciaId'");
						$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
						 header('Location: painel.php?execute=suporte/motorista/negligencias');
					}
			 }

	?>
		
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
    
     <div class="form-group col-xs-12 col-md-12">  
		 
		 <div class="box-header with-border">
             <h3 class="box-title">Negligencia</h3>
           </div><!-- /.box-header -->
                
         <div class="box-body">
        <div class="row">
 
      		<div class="form-group col-xs-12 col-md-2">  
                <label>Id</label>
                 <input name="id" class="form-control" type="text" disabled value="<?php echo $edit['id'];?>"/>
            </div>
			
			<div class="form-group col-xs-12 col-md-10">  
            <label>Motorista</label>
                <select name="id_motorista" class="form-control" >
                    <option value="">Selecione um Motorista</option>
                    <?php 
                        $readSuporte = read('veiculo_motorista',"WHERE id ORDER BY nome ASC");
                        if(!$readSuporte){
                            echo '<option value="">Não temos Suporte no momento</option>';	
                        }else{
                            foreach($readSuporte as $mae):
							   if($edit['id_motorista'] == $mae['id']){
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
            <label>Negligencia</label>
                <select name="id_negligencia" class="form-control" >
                    <option value="">Selecione uma negligencia</option>
                    <?php 
                        $readSuporte = read('veiculo_motorista_motivo_negligencia',"WHERE id");
                        if(!$readSuporte){
                            echo '<option value="">Não temos Suporte no momento</option>';	
                        }else{
                            foreach($readSuporte as $mae):
							   if($edit['id_negligencia'] == $mae['id']){
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
            <label>Rota (*)</label>
                <select name="rota" class="form-control">
                    <option value="">Rota</option>
                    <?php 
                        $readConta = read('contrato_rota',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos motorista no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['rota'] == $mae['id']){
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
                 <label>Data</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
                  <input type="date" name="data" class="form-control"  value="<?php echo $edit['data'];?>"/>
                  </div><!-- /.input group -->
           </div> 
              
      
		 </div><!-- /.row -->
       </div><!-- /.box-body -->
          
		 

			 <div class="form-group col-xs-12 col-md-12">  
                <div class="box-footer">
                   <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"></a>
                     <?php 
                        if($acao=="atualizar"){
                            echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';	
                        }
                        if($acao=="deletar"){
                            echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-danger" />';	
                        }
						 if($acao=="baixar"){
                            echo '<input type="submit" name="baixar" value="Baixar"  class="btn btn-success" />';	
                        }
                        if($acao=="cadastrar"){
                            echo '<input type="submit" name="cadastrar" value="Cadastrar" class="btn btn-primary" />';
	
                        }
                     ?>  
					
               </div><!-- /.row -->
         </div><!-- /.box-body -->
			 
		</form>
  	</div><!-- /.box-body -->
    </div><!-- /.box box-default -->
  </section><!-- /.content -->

	 
		
</div><!-- /.content-wrapper -->


