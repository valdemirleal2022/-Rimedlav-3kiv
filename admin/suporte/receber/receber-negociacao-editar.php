<?php 
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php?execute=negociacao/403');	
			}	
		}
		
		
		if(!empty($_GET['receberId'])){
			$receberId = $_GET['receberId'];
			$acao = "cadastrar";
			$readonly = "readonly";
			$disabled = 'disabled="disabled"';
		}

		$edit['status'] = "Aguardando";
		$edit['data']=   date("Y-m-d");
		$edit['hora'] = date("H:i");
		$edit['id_usuario']=$_SESSION['autUser']['id'];
			
		if(!empty($_GET['negociacaoVisualizar'])){
			$negociacaoId = $_GET['negociacaoVisualizar'];
			$acao = "visualizar";
		}
		if(!empty($_GET['negociacaoDeletar'])){
			$negociacaoId = $_GET['negociacaoDeletar'];
			$acao = "deletar";
		}
		if(!empty($_GET['negociacaoBaixar'])){
			$negociacaoId = $_GET['negociacaoBaixar'];
			$acao = "baixar";
		}

		
		if(!empty($receberId)){
			$readReceber = read('receber',"WHERE id = '$receberId'");
			if(!$readReceber){
				header('Location: painel.php?execute=negociacao/error');
			}
			foreach($readReceber as $receber);

			$clienteId= $receber['id_cliente'];
		}

		if(!empty($negociacaoId)){
			$readnegociacao = read('receber_negociacao',"WHERE id = '$negociacaoId'");
			if(!$readnegociacao){
				header('Location: painel.php?execute=negociacao/naoencontrado');	
			}
			foreach($readnegociacao as $edit);
			$clienteId = $edit['id_cliente'];
			
		}


		$readCliente = read('cliente',"WHERE id = '$clienteId'");
		if($readCliente ){
			foreach($readCliente as $cliente);
		}else{
			$cliente['nome']='Cliente Exluido';
			
		}

		if($acao=="baixar"){
			$edit['status'] = "Ok";
			$edit['data_solucao']=   date("d-m-Y");
			$edit['hora_solucao'] = date("H:i");
		 
		}

 ?>
 
 <div class="content-wrapper">
  <section class="content-header">
          <h1>Negociação</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Cliente</a></li>
            <li><a href="painel.php?execute=negociacao/receber/negociações">Negociação</a></li>
             <li class="active">Editar</li>
          </ol>
  </section>
  
  <section class="content">
      <div class="box box-default">
           
            <div class="box-header with-border">
				 <?php require_once('cliente-modal.php');?>
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
		
			 
			if(isset($_POST['cadastrar'])){
				
				$edit['id_solucao']= strip_tags(trim(mysql_real_escape_string($_POST['id_solucao'])));
				$solucaoId=$edit['id_solucao'];
				
				$negociacao = mostra('recebe_negociacao_solucao',"WHERE id ='$solucaoId'");
				$edit['peso']=$negociacao['peso'];
					
				$edit['id_receber'] = $receberId;
				$edit['id_cliente'] = $clienteId;
				$edit['id_motivo']= strip_tags(trim(mysql_real_escape_string($_POST['id_motivo'])));
				
				$edit['status'] = "Aguardando";
		 		$edit['data']	= date('Y-m-d');
				$edit['hora'] 	= date("H:i");
				$edit['observacao'] = mysql_real_escape_string($_POST['observacao']);
				$edit['id_usuario']=$_SESSION['autUser']['id'];
			
				if(in_array('',$edit)){
					//print_r($edit);
					echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
				  }else{
					$edit['previsao_pagamento']= mysql_real_escape_string($_POST['previsao_pagamento']);
			 
				    create('receber_negociacao',$edit);
					$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
				    header("Location: ".$_SESSION['url']);
		    	}
				
			}
			
			
			if(isset($_POST['deletar'])){
					$readDeleta = read('receber_negociacao',"WHERE id = '$negociacaoId'");
					if(!$readDeleta){
						echo '<div class="alert alert-warning">Desculpe, o registro não existe!</div><br />';
					}else{
						delete('receber_negociacao',"id = '$negociacaoId'");
						$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
						header("Location: ".$_SESSION['url']);
					}
			 }
		?>
		
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
    
     <div class="form-group col-xs-12 col-md-12">  
		 
		 <div class="box-header with-border">
             <h3 class="box-title">Negocição</h3>
           </div><!-- /.box-header -->
                
         <div class="box-body">
        <div class="row">
 
      		<div class="form-group col-xs-12 col-md-2">  
                <label>Id</label>
                 <input name="id" class="form-control" type="text" disabled value="<?php echo $edit['id'];?>"/>
            </div>
		 
		    <div class="form-group col-xs-12 col-md-2">  
       		   <label>Status</label>
               <input type="text" name="status" value="<?php echo $edit['status'];?>" class="form-control" disabled />
            </div> 
			
		 <div class="form-group col-xs-12 col-md-5">  
            <label>Usuario</label>
                <select name="id_usuario" class="form-control"  disabled>
                    <option value="">Selecione um Usuario</option>
                    <?php 
                        $readnegociacao = read('usuarios',"WHERE id");
                        if(!$readnegociacao){
                            echo '<option value="">Não temos negociacao no momento</option>';	
                        }else{
                            foreach($readnegociacao as $mae):
							   if($edit['id_usuario'] == $mae['id']){
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
            <label>Motivo</label>
                <select name="id_motivo" class="form-control" >
                    <option value="">Selecione um Motivo</option>
                    <?php 
                        $readnegociacao = read('recebe_negociacao_motivo',"WHERE id");
                        if(!$readnegociacao){
                            echo '<option value="">Não temos negociacao no momento</option>';	
                        }else{
                            foreach($readnegociacao as $mae):
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
                 <label>Data</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
                  <input type="date" name="data" class="form-control" disabled value="<?php echo $edit['data'];?>"/>
                  </div><!-- /.input group -->
           </div> 
           
            <div class="form-group col-xs-12 col-md-2">  
                 <label>Hora</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-clock-o "></i>
                       </div>
                  <input type="text" name="hora" class="form-control" disabled value="<?php echo $edit['hora'];?>"/>
                  </div><!-- /.input group -->
           </div> 
           
          <div class="form-group col-xs-12 col-md-3">  
                 <label>Previsão de Pagamento </label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
                  <input type="date" name="previsao_pagamento" class="form-control" value="<?php echo $edit['previsao_pagamento'];?>"/>
                  </div><!-- /.input group -->
           </div> 
			
		  <div class="form-group col-xs-12 col-md-4">  
            <label>Solução</label>
                <select name="id_solucao" class="form-control" >
                    <option value="">Selecione uma Solução</option>
                    <?php 
                        $readnegociacao = read('recebe_negociacao_solucao',"WHERE id");
                        if(!$readnegociacao){
                            echo '<option value="">Não temos registro no momento</option>';	
                        }else{
                            foreach($readnegociacao as $mae):
							   if($edit['id_solucao'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].' -' .$mae['peso'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['nome'].' -' .$mae['peso'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
      		 </div> 
              
          <div class="form-group col-xs-12 col-md-12"> 
              <label>Observação</label>
                <textarea name="observacao" rows="5" cols="100" class="form-control" /><?php echo $edit['observacao'];?></textarea>
         </div>  
		
		 </div><!-- /.row -->
       </div><!-- /.box-body -->
          
		
  		<div class="box-header with-border">
                  <h3 class="box-title">Baixa</h3>
        </div><!-- /.box-header -->
                
         <div class="box-body">
        <div class="row">
            
             <div class="form-group col-xs-12 col-md-3">  
                 <label>Data</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
                  <input type="date" name="data_solucao" class="form-control" disabled value="<?php echo $edit['data_solucao'];?>  <?php echo $readonly;?> "/>
                  </div><!-- /.input group -->
           </div> 
           
            <div class="form-group col-xs-12 col-md-2">  
                 <label>Hora</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-clock-o "></i>
                       </div>
                  <input type="text" name="hora_solucao" class="form-control" disabled value="<?php echo $edit['hora_solucao'];?>"  <?php echo $readonly;?>/>
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
                 </div>
             </div>
			</div>
    </form>
   		
   	</div><!-- /.box-body -->
  </div><!-- /.box box-default -->
 </section><!-- /.content -->
</div><!-- /.content-wrapper -->
			 