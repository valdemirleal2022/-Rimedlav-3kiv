<?php 
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php?execute=suporte/403');	
			}	
		}
		
		$acao = "cadastrar";
		if(!empty($_GET['funcionarioId'])){
			$funcionarioId = $_GET['funcionarioId'];
			$readonly = "readonly";
			$disabled = 'disabled="disabled"';
		}

		$edit['data']=  date("Y-m-d");
 
		if(!empty($_GET['epiEditar'])){
			$epiId = $_GET['epiEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['epiVisualizar'])){
			$epiId = $_GET['epiVisualizar'];
			$acao = "visualizar";
		}
		if(!empty($_GET['epiDeletar'])){
			$epiId = $_GET['epiDeletar'];
			$acao = "deletar";
		}
		if(!empty($_GET['epiBaixar'])){
			$epiId = $_GET['epiBaixar'];
			$acao = "baixar";
		}
	

		if(!empty($epiId)){
			$readepi = read('funcionario_epi',"WHERE id = '$epiId'");
			if(!$readepi){
				header('Location: painel.php?execute=suporte/naoencontrado');	
			}
			foreach($readepi as $edit);
			$funcionarioId = $edit['id_funcionario'];	
		}
		
		if(!empty($funcionarioId)){
		 
			$funcionario = mostra('funcionario',"WHERE id = '$funcionarioId'");
			if(!$funcionario){
				//header('Location: painel.php?execute=suporte/error');
				$funcionario['nome'] = 'Funcionário Excluido';
			}
		
			$funcaoId = $funcionario['id_funcao'];
			$funcao = mostra('funcionario_funcao',"WHERE id = '$funcaoId'");
		}

 ?>
 
 <div class="content-wrapper">
  <section class="content-header">
          <h1>Diária</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Funcionario</a></li>
            <li><a href="painel.php?execute=suporte/funcionario/epi">Diária</a></li>
             <li class="active">Editar</li>
          </ol>
  </section>
  
  <section class="content">
      <div class="box box-default">
           
            <div class="box-header with-border">
				    <h3 class="box-title"><?php echo $funcionario['nome']. ' | '. $funcao['nome'];?></h3>
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
				
				$edit['epi'] = mysql_real_escape_string($_POST['epi']);
			    $edit['motivo'] = mysql_real_escape_string($_POST['_motivo']);
				$edit['data']= mysql_real_escape_string($_POST['data']);
				$edit['quantidade']= mysql_real_escape_string($_POST['quantidade']);
				$edit['observacao']= mysql_real_escape_string($_POST['observacao']);
			
				update('funcionario_epi',$edit,"id = '$epiId'");	
				
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				
				header("Location: ".$_SESSION['url']);
			}
			 
			 
		
			 
			if(isset($_POST['cadastrar'])){
				
				$edit['id_funcionario'] = $funcionarioId;
				$edit['epi'] = mysql_real_escape_string($_POST['epi']);		
				$edit['id_motivo'] = mysql_real_escape_string($_POST['id_motivo']);
				$edit['data']= mysql_real_escape_string($_POST['data']);
				$edit['quantidade']= mysql_real_escape_string($_POST['quantidade']);
				$edit['observacao']= mysql_real_escape_string($_POST['observacao']);
			
				if(in_array('',$edit)){
				  print_r($edit);
					echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
				  }else{
				    create('funcionario_epi',$edit);
					$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
				    header("Location: ".$_SESSION['url']);
		    	}
			}
			
			
			if(isset($_POST['deletar'])){
					$readDeleta = read('funcionario_epi',"WHERE id = '$epiId'");
					if(!$readDeleta){
						echo '<div class="alert alert-warning">Desculpe, o registro não existe!</div><br />';
					}else{
						delete('funcionario_epi',"id = '$epiId'");
						$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
						header("Location: ".$_SESSION['url']);
					}
			 }
			 
			 
	 
		?>
		
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
    
     <div class="form-group col-xs-12 col-md-12">  
		 
		 <div class="box-header with-border">
             <h3 class="box-title">EPI</h3>
           </div><!-- /.box-header -->
                
         <div class="box-body">
        <div class="row">
 
      		<div class="form-group col-xs-12 col-md-1">  
                <label>Id</label>
                 <input name="id" class="form-control" type="text" disabled value="<?php echo $edit['id'];?>"/>
            </div>
  
            <div class="form-group col-xs-12 col-md-3">  
            <label>Tipo</label>
                <select name="epi" class="form-control" >
                    <option value="">Selecione uma epi</option>
                    <?php 
                        $readSuporte = read('funcionario_epi_tipo',"WHERE id");
                        if(!$readSuporte){
                            echo '<option value="">Não temos registro no momento</option>';	
                        }else{
                            foreach($readSuporte as $mae):
							   if($edit['epi'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
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
                  <input type="date" name="data" class="form-control" value="<?php echo $edit['data'];?>"/>
                  </div><!-- /.input group -->
           </div> 
		 	 
		   <div class="form-group col-xs-3">
								<label>Motivo</label>
								<select name="id_motivo" class="form-control"  >
								  <option value="">Motivo</option>
								  <option <?php if($edit['id_motivo'] == '1') echo' selected="selected"';?> value="1" <?php echo $edit['id_motivo'];?>>Novo</option>
								  <option <?php if($edit['id_motivo'] == '2') echo' selected="selected"';?> value="2" <?php echo $edit['id_motivo'];?>>Troca 
								 </select>
							</div><!-- /.row -->
                           
		 <div class="form-group col-xs-12 col-md-3">  
          		<label>Quantidade</label>
               <input type="text" name="quantidade" class="form-control pull-right" value="<?php echo $edit['quantidade'];?>"   />
                 
           </div>
			 
              
          <div class="form-group col-xs-12 col-md-12"> 
              <label>Observação</label>
                <textarea name="observacao" rows="5" cols="100" class="form-control" /><?php echo $edit['observacao'];?></textarea>
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
							
                   if($_SESSION['autUser']['nivel']==5){	//Gerencial 
				  	   
					    echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-danger" onClick="return confirm(\'Confirma Exclusão do Registro ?\')" />';
					 
					}
                        }
                        if($acao=="cadastrar"){
                            echo '<input type="submit" name="cadastrar" value="Caddastrar" class="btn btn-primary" />';
	
                        }
                     ?>  
					
               </div><!-- /.row -->
         </div><!-- /.box-body -->
			 
		</form>
  	</div><!-- /.box-body -->
    </div><!-- /.box box-default -->
  </section><!-- /.content -->
 
</div><!-- /.content-wrapper -->


