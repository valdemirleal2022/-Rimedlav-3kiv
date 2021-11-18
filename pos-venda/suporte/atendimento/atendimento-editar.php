<?php 
	
	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autpos_venda']['id'])){
			header('Location: painel.php');	
		}	
	}

	$pos_vendaId=$_SESSION['autpos_venda']['id'];

		if(!empty($_GET['contratoId'])){
			$contratoId = $_GET['contratoId'];
			$acao = "cadastrar";
			$readonly = "readonly";
			$disabled = 'disabled="disabled"';
		}

		$edit['status'] = "Aguardando";
		$edit['data_solicitacao']=   date("Y-m-d");
		$edit['hora_solicitacao'] = date("H:i");
		$edit['data_solucao']=   date("Y-m-d");
		$edit['hora_solucao'] = date("H:i");
		$edit['pos_venda'] = $pos_vendaId;

		if(!empty($_GET['atendimentoEditar'])){
			$atendimentoId = $_GET['atendimentoEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['atendimentoDeletar'])){
			$atendimentoId = $_GET['atendimentoDeletar'];
			$acao = "deletar";
		}
		if(!empty($_GET['atendimentoBaixar'])){
			$atendimentoId = $_GET['atendimentoBaixar'];
			$acao = "baixar";
		}

		
		if(!empty($contratoId)){
			$contratoId = $_GET['contratoId'];
			$readContrato = read('contrato',"WHERE id = '$contratoId'");
			if(!$readContrato){
				header('Location: painel.php?execute=atendimento/error');
			}
			foreach($readContrato as $contrato);

			$contratoId= $contrato['id'];
			$clienteId= $contrato['id_cliente'];
		}

		if(!empty($atendimentoId)){
			$readatendimento = read('contrato_atendimento_pos_venda',"WHERE id = '$atendimentoId'");
			if(!$readatendimento){
				header('Location: painel.php?execute=atendimento/naoencontrado');	
			}
			foreach($readatendimento as $edit);
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
          <h1>Atendimento - Pos-Venda</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Cliente</a></li>
            <li><a href="#">Atendimento</a></li>
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
		
			if(isset($_POST['atualizar'])){
				
				$cad['solicitacao'] = mysql_real_escape_string($_POST['solicitacao']);
				$cad['usuario']	=  $_SESSION['autUser']['nome'];
				$cad['motivo'] = strip_tags(trim(mysql_real_escape_string($_POST['motivo'])));
				$cad['solucao'] = mysql_real_escape_string($_POST['solucao']);
				$cad['usuario'] = strip_tags(trim(mysql_real_escape_string($_POST['usuario'])));
				$cad['pos_venda']= strip_tags(trim(mysql_real_escape_string($_POST['pos_venda'])));
				update('contrato_atendimento_pos_venda',$cad,"id = '$atendimentoId'");	
			$_SESSION['cadastro']='<div class="alert alert-success">Atualizado com sucesso</div>';
				header("Location: ".$_SESSION['url']);
			}
		
			if(isset($_POST['baixar'])){
				$cad['status']	='OK';
				$cad['solucao'] = mysql_real_escape_string($_POST['solucao']);
				$cad['usuario']	=  $_SESSION['autUser']['nome'];
			 
				if(in_array('',$cad)){
					echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
				  }else{
					update('contrato_atendimento_pos_venda',$cad,"id = '$atendimentoId'");	
					$_SESSION['cadastro'] = '<div class="alert alert-success">Baixado com sucesso</div>';
				    header("Location: ".$_SESSION['url']);
				 }
			}
			
			if(isset($_POST['abrir'])){
				
				$edit['data_solicitacao'] = date('Y-m-d');
				$edit['hora_solicitacao'] = date("H:i");
				$edit['solicitacao'] = mysql_real_escape_string($_POST['solicitacao']);
				$cad['usuario']	=  $_SESSION['autUser']['nome'];
				$edit['motivo']= strip_tags(trim(mysql_real_escape_string($_POST['motivo'])));
				$edit['pos_venda']= strip_tags(trim(mysql_real_escape_string($_POST['pos_venda'])));
				$edit['status'] = "Aguardando";
				$edit['id_cliente'] = $clienteId;
				$edit['id_contrato'] = $contratoId;
				$edit['pos_venda'] = $pos_vendaId;
				
				if(!empty($_FILES['foto']['tmp_name'])){
				
					$imagem = $_FILES['foto'];
					$pasta  = '../uploads/atendimentos/';
					$tmp    = $imagem['tmp_name'];
					$ext    = substr($imagem['name'],-3);
					$nome   = md5(time()).'.'.$ext;
					$edit['foto'] = $nome;
					uploadImg($tmp, $nome, '350', $pasta);	

				}
			
				if(in_array('',$edit)){
					echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
				  }else{
				    create('contrato_atendimento_pos_venda',$edit);
					$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
				    header("Location: ".$_SESSION['url']);
		    	}
			}
			
			
			if(isset($_POST['deletar'])){
					$readDeleta = read('contrato_atendimento_pos_venda',"WHERE id = '$atendimentoId'");
					if(!$readDeleta){
						echo '<div class="alert alert-warning">Desculpe, o registro não existe!</div><br />';
					}else{
						delete('contrato_atendimento_pos_venda',"id = '$atendimentoId'");
						$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
						header('Location: painel.php?execute=atendimento/pedido/pedidos');
					}
			 }
		?>
		
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
    
     <div class="form-group col-xs-12 col-md-12">  
		 
		 <div class="box-header with-border">
             <h3 class="box-title">Abertura</h3>
           </div><!-- /.box-header -->
                
         <div class="box-body">
        <div class="row">
 
      		<div class="form-group col-xs-12 col-md-1">  
                <label>Id</label>
                 <input name="id" class="form-control" type="text" disabled value="<?php echo $edit['id'];?>"/>
            </div>
		 
		   <div class="form-group col-xs-12 col-md-2">  
       		   <label>Status</label>
               <input type="text" name="status" value="<?php echo $edit['status'];?>" class="form-control" disabled />
            </div> 

            <div class="form-group col-xs-12 col-md-2">  
            <label>Pos-Venda</label>
                <select name="pos_venda" class="form-control" disabled>
                    <option value="">Selecione um Pos-Venda</option>
                    <?php 
                        $readatendimento = read('contrato_pos_venda',"WHERE id");
                        if(!$readatendimento){
                            echo '<option value="">Não temos Pos-Venda no momento</option>';	
                        }else{
                            foreach($readatendimento as $mae):
							   if($edit['pos_venda'] == $mae['id']){
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
            <label>Motivo do Atendimento</label>
                <select name="motivo" class="form-control" >
                    <option value="">Selecione um Motivo</option>
                    <?php 
                        $readatendimento = read('contrato_atendimento_pos_venda_motivo',"WHERE id");
                        if(!$readatendimento){
                            echo '<option value="">Não temos atendimento no momento</option>';	
                        }else{
                            foreach($readatendimento as $mae):
							   if($edit['motivo'] == $mae['id']){
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
                  <input type="date" name="data_solicitacao" class="form-control" disabled value="<?php echo $edit['data_solicitacao'];?>"/>
                  </div><!-- /.input group -->
           </div> 
           
            <div class="form-group col-xs-12 col-md-2">  
                 <label>Hora</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-clock-o "></i>
                       </div>
                  <input type="text" name="hora_solicitacao" class="form-control" disabled value="<?php echo $edit['hora_solicitacao'];?>"/>
                  </div><!-- /.input group -->
           </div> 
           
          
              
          <div class="form-group col-xs-12 col-md-12"> 
              <label>Solicitação</label>
                <textarea name="solicitacao" rows="5" cols="100" class="form-control" /><?php echo $edit['solicitacao'];?></textarea>
         </div>  
		
		 </div><!-- /.row -->
       </div><!-- /.box-body -->
          
		
  		<div class="box-header with-border">
                  <h3 class="box-title">Fechamento</h3>
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
   
            
             <div class="form-group col-xs-12 col-md-12"> 
              <label>Solução</label>
                <textarea  name="solucao" rows="5" cols="100" class="form-control"  <?php echo $readonly;?>/><?php echo $edit['solucao'];?></textarea>
        	 </div>  
	 	 
			 <div class="form-group col-xs-12 col-md-12"> 
				 <label>Foto do Atendimento</label>
				<div class="form-group">
					<?php 
						if($edit['foto'] != '' && file_exists('../uploads/atendimentos/'.$edit['foto'])){
							echo '<img src="../uploads/atendimentos/'.$edit['foto'].'"/>';
						}
						 
					?>
				</div>
				<div class="form-group">
					<input type="file" name="foto"/>
				</div>
					<?php 
					 
						$foto='../uploads/atendimentos/'.$edit['foto'];
						if(file_exists($foto)){
									echo '<a href="../uploads/atendimentos/'.$edit['foto'].'" target="_blank">
									<img src="../admin/ico/download.png" title="Download da Foto" />
								</a>';	
						}
					?>
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
                            echo '<input type="submit" name="abrir" value="Abrir Atendimento" class="btn btn-primary" />';
	
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
			 