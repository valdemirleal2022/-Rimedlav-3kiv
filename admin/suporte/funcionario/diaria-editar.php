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
		$edit['usuario'] = $_SESSION['autUser']['nome'];

		if(!empty($_GET['diariaEditar'])){
			$diariaId = $_GET['diariaEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['diariaVisualizar'])){
			$diariaId = $_GET['diariaVisualizar'];
			$acao = "visualizar";
		}
		if(!empty($_GET['diariaDeletar'])){
			$diariaId = $_GET['diariaDeletar'];
			$acao = "deletar";
		}
		if(!empty($_GET['diariaBaixar'])){
			$diariaId = $_GET['diariaBaixar'];
			$acao = "baixar";
		}
	

		if(!empty($diariaId)){
			$readdiaria = read('funcionario_diaria',"WHERE id = '$diariaId'");
			if(!$readdiaria){
				header('Location: painel.php?execute=suporte/naoencontrado');	
			}
			foreach($readdiaria as $edit);
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
            <li><a href="painel.php?execute=suporte/funcionario/diaria">Diária</a></li>
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
				
				$edit['rota']=  mysql_real_escape_string($_POST['rota']);
				$edit['rota_hora']=  mysql_real_escape_string($_POST['rota_hora']);
				//$edit['hora_chegada']=  mysql_real_escape_string($_POST['hora_chegada']);
				//$edit['liberacao_entrada']=  mysql_real_escape_string($_POST['liberacao_entrada']);
				$edit['id_motivo'] = mysql_real_escape_string($_POST['id_motivo']);
				$edit['data']= mysql_real_escape_string($_POST['data']);
				$edit['observacao']= mysql_real_escape_string($_POST['observacao']);
				$edit['aprovacao_operacional'] = mysql_real_escape_string($_POST['aprovacao_operacional']);
			    $edit['autorizacao_pagamento'] = mysql_real_escape_string($_POST['autorizacao_pagamento']);
			
			    $edit['valor']= strip_tags(trim(mysql_real_escape_string($_POST['valor'])));
				$edit['valor'] = str_replace(",",".",str_replace(".","",$edit['valor']));
				$edit['motivo_cancelamento'] = mysql_real_escape_string($_POST['motivo_cancelamento']);
			   
				$edit['usuario'] = $_SESSION['autUser']['nome'];
				update('funcionario_diaria',$edit,"id = '$diariaId'");	
				
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				
				header("Location: ".$_SESSION['url']);
			}
			 
			if(isset($_POST['autorizar'])){
				
			    $edit['autorizacao_pagamento'] = mysql_real_escape_string($_POST['autorizacao_pagamento']);
				$edit['motivo_cancelamento'] = mysql_real_escape_string($_POST['motivo_cancelamento']);
				$edit['usuario'] = $_SESSION['autUser']['nome'];
				update('funcionario_diaria',$edit,"id = '$diariaId'");	
				
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				
				header("Location: ".$_SESSION['url']);
			}
		
			 
			if(isset($_POST['cadastrar'])){
				
				$edit['id_funcionario'] = $funcionarioId;
				$edit['rota']=  mysql_real_escape_string($_POST['rota']);
				$edit['rota_hora']=  mysql_real_escape_string($_POST['rota_hora']);
				//$edit['hora_chegada']=  mysql_real_escape_string($_POST['hora_chegada']);
				//$edit['liberacao_entrada']=  mysql_real_escape_string($_POST['liberacao_entrada']);
				$edit['id_motivo'] = mysql_real_escape_string($_POST['id_motivo']);
				$edit['data']= mysql_real_escape_string($_POST['data']);
				$edit['observacao']= mysql_real_escape_string($_POST['observacao']);
				$edit['aprovacao_operacional']= '1';
				$edit['valor']= strip_tags(trim(mysql_real_escape_string($_POST['valor'])));
				$edit['valor'] = str_replace(",",".",str_replace(".","",$edit['valor']));
			
				$edit['usuario'] = $_SESSION['autUser']['nome'];
 
				if(in_array('',$edit)){
				  print_r($edit);
					echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
				  }else{
				    create('funcionario_diaria',$edit);
					$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
				    header("Location: ".$_SESSION['url']);
		    	}
			}
			
			
			if(isset($_POST['deletar'])){
					$readDeleta = read('funcionario_diaria',"WHERE id = '$diariaId'");
					if(!$readDeleta){
						echo '<div class="alert alert-warning">Desculpe, o registro não existe!</div><br />';
					}else{
						delete('funcionario_diaria',"id = '$diariaId'");
						$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
						header("Location: ".$_SESSION['url']);
					}
			 }
			 
			 if(isset($_POST['baixar'])){
				
				$edit['aprovacao_operacional']= mysql_real_escape_string($_POST['aprovacao_operacional']);
				$edit['autorizacao_pagamento'] = mysql_real_escape_string($_POST['autorizacao_pagamento']);
				$edit['motivo_cancelamento'] = mysql_real_escape_string($_POST['motivo_cancelamento']);
		 
				$edit['usuario'] = $_SESSION['autUser']['nome'];
				update('funcionario_diaria',$edit,"id = '$diariaId'");	
				
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				
				header("Location: ".$_SESSION['url']);
			}
			 
		    
			 
	 
		?>
		
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
    
     <div class="form-group col-xs-12 col-md-12">  
		 
		 <div class="box-header with-border">
             <h3 class="box-title">Diária</h3>
           </div><!-- /.box-header -->
                
         <div class="box-body">
        <div class="row">
 
      		<div class="form-group col-xs-12 col-md-3">  
                <label>Id</label>
                 <input name="id" class="form-control" type="text" disabled value="<?php echo $edit['id'];?>"/>
            </div>

		   <div class="form-group col-xs-12 col-md-3">  
            	<label>Usuário</label>
                <input name="usuario" type="text" value="<?php echo $edit['usuario'];?>" readonly class="form-control" /> 
            </div> 

            <div class="form-group col-xs-12 col-md-6">  
            <label>Motivo</label>
                <select name="id_motivo" class="form-control" >
                    <option value="">Selecione uma diaria</option>
                    <?php 
                        $readSuporte = read('funcionario_diaria_motivo',"WHERE id");
                        if(!$readSuporte){
                            echo '<option value="">Não temos registro no momento</option>';	
                        }else{
                            foreach($readSuporte as $mae):
							   if($edit['id_motivo'] == $mae['id']){
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
            <label>Rota</label>
                <select name="rota" class="form-control" >
                    <option value="">Selecione uma rota</option>
                    <?php 
                        $readSuporte = read('contrato_rota',"WHERE id");
                        if(!$readSuporte){
                            echo '<option value="">Não temos registro no momento</option>';	
                        }else{
                            foreach($readSuporte as $mae):
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
			
			<div class="form-group col-xs-12 col-md-3">  
                <label>Hora da Rota</label>
                 <input name="rota_hora" class="form-control" type="text"  value="<?php echo $edit['rota_hora'];?>"/>
            </div>

  
             <div class="form-group col-xs-12 col-md-3">  
                 <label>Data</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
                  <input type="date" name="data" class="form-control" value="<?php echo $edit['data'];?>"/>
                  </div><!-- /.input group -->
           </div> 
			
				 
		   <div class="form-group col-xs-3">
								<label>Liberação Portaria</label>
								<select name="aprovacao_operacional" class="form-control"  >
								  <option value="">Selecione solicitação</option>
								  <option <?php if($edit['aprovacao_operacional'] == '1') echo' selected="selected"';?> value="1" <?php echo $liberacaoOperacional;?>>Aguardando</option>
								  <option <?php if($edit['aprovacao_operacional'] == '2') echo' selected="selected"';?> value="2" <?php echo $liberacaoOperacional;?>>Autorização de Entrada Diarista</option>
								 <option <?php if($edit['aprovacao_operacional'] == '3') echo' selected="selected"';?> value="3" <?php echo $liberacaoOperacional;?>>Cancelado</option>
								
								 </select>
							</div><!-- /.row -->
                           
			 
              
          <div class="form-group col-xs-12 col-md-12"> 
              <label>Observação</label>
                <textarea name="observacao" rows="5" cols="100" class="form-control" /><?php echo $edit['observacao'];?></textarea>
         </div>  
			 
		
			 <div class="form-group col-xs-3">
					<label>Autorização de Pagamento</label>
						<select name="autorizacao_pagamento" class="form-control"  >
							<option value="">Selecione solicitação</option>
							<option <?php if($edit['autorizacao_pagamento'] == '1') echo' selected="selected"';?> value="1" <?php echo $autorizacaoPagamento;?>>Solicitar Autorização</option>
							<option <?php if($edit['autorizacao_pagamento'] == '2') echo' selected="selected"';?> value="2" <?php echo $autorizacaoPagamento;?>>Autorizado Pagamento</option>
							<option <?php if($edit['autorizacao_pagamento'] == '3') echo' selected="selected"';?> value="3" <?php echo $autorizacaoPagamento;?>>Cancelado</option>
								
					 </select>
			</div><!-- /.row -->
			 
	 
			 <div class="form-group col-xs-12 col-md-6">  
          		<label>Motivo Cancelamento</label>
               <input type="text" name="motivo_cancelamento" class="form-control pull-right" value="<?php echo $edit['motivo_cancelamento'];?>" <?php echo $readonly;?> />
                 
           </div>
			 
			  <div class="form-group col-xs-12 col-md-3">  
          		<label>Valor</label>
               <div class="input-group">
                      	<div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="valor" class="form-control pull-right" value="<?php echo converteValor($edit['valor']);?>" <?php echo $readonly;?> />
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


