<?php 

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php');		
			}	
		}
	
		if(!empty($_GET['retiradaEditar'])){
			$retiradaId = $_GET['retiradaEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['retiradaBaixar'])){
			$retiradaId = $_GET['retiradaBaixar'];
			$acao = "baixar";
		}
		if(!empty($_GET['retiradaDeletar'])){
			$retiradaId = $_GET['retiradaDeletar'];
			$acao = "deletar";
		}

		if(!empty($retiradaId)){
			
			$readretirada= read('estoque_etiqueta_retirada',"WHERE id = '$retiradaId'");
			if(!$readretirada){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readretirada as $edit);
			
			$contratoId = $edit['id_contrato'];
			$clienteId = $edit['id_cliente'];
			$statusAtual = $edit['status'];
			
			$cliente = mostra('cliente',"WHERE id = '$clienteId'");
			$contrato = mostra('contrato',"WHERE id = '$contratoId'");


		}

		if(!empty($_GET['contratoId'])){
			$contratoId = $_GET['contratoId'];
			$contrato = mostra('contrato',"WHERE id = '$contratoId'");
			$clienteId = $contrato['id_cliente'];
			$cliente = mostra('cliente',"WHERE id = '$clienteId'");
			$acao = "cadastrar";
		}
		
 ?>
 
<div class="content-wrapper">
 
  <section class="content-header">
          <h1>Retirada</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Etiqueta</a></li>
            <li><a href="#">Retirada</a></li>
             <li class="active">Editar</li>
          </ol>
  </section>
  
  <section class="content">
     
     <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><small><?php echo $cliente['nome'];?></small></h3>
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
            		  	<a href="painel.php?execute=suporte/relatorio/ficha-solicitacao-etiqueta-pdf&retiradaId=<?PHP echo $retiradaId; ?>"  target="_blank">
						 <img src="ico/contratos.png" title="Solicitacao de Etiqueta" />
						  <small>Solicitação de Etiqueta</small>
						 </a>
            	</div><!-- /col-xs-10 col-md-5 pull-right-->
   			 </div><!-- /row-->  
           </div><!-- /box-header-->   
      	  
    <div class="box-body">
      
	<?php 
			 
	if(isset($_POST['cadastrar'])){
		$cad['status'] = 'Em Aberto';
		$cad['id_contrato'] = $contratoId;
		$cad['id_cliente'] = $clienteId;
		$cad['id_etiqueta'] = strip_tags(trim(mysql_real_escape_string($_POST['id_etiqueta'])));
		$cad['quantidade'] = strip_tags(trim(mysql_real_escape_string($_POST['quantidade'])));
		$cad['data_solicitacao']=strip_tags(trim(mysql_real_escape_string($_POST['data_solicitacao'])));
		$cad['data_entrega'] = strip_tags(trim(mysql_real_escape_string($_POST['data_entrega'])));
		if(in_array('',$cad)){
			echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
		}else{
			$cad['observacao'] 	= strip_tags(trim(mysql_real_escape_string($_POST['observacao'])));

			create('estoque_etiqueta_retirada',$cad);
			$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
				header("Location: ".$_SESSION['url']);

		}
	}
			 
	if(isset($_POST['atualizar'])){
		$cad['id_etiqueta']= strip_tags(trim(mysql_real_escape_string($_POST['id_etiqueta'])));
		$cad['quantidade'] 	= strip_tags(trim(mysql_real_escape_string($_POST['quantidade'])));
		$cad['data_solicitacao']=strip_tags(trim(mysql_real_escape_string($_POST['data_solicitacao'])));
		$cad['data_entrega'] = strip_tags(trim(mysql_real_escape_string($_POST['data_entrega'])));
		if(in_array('',$cad)){
			echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
		 }else{
			$cad['observacao'] 	= strip_tags(trim(mysql_real_escape_string($_POST['observacao'])));
			update('estoque_etiqueta_retirada',$cad,"id = '$retiradaId'");	
			$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				header("Location: ".$_SESSION['url']);

		}
	}
	
	if(isset($_POST['baixar'])){
		$cad['status'] = 'Baixado';
		$cad['quantidade'] 	= strip_tags(trim(mysql_real_escape_string($_POST['quantidade'])));
		$cad['data_entrega'] = strip_tags(trim(mysql_real_escape_string($_POST['data_entrega'])));
		
		if(in_array('',$cad)){
			echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
		 }else{
			$cad['observacao'] 	= strip_tags(trim(mysql_real_escape_string($_POST['observacao'])));

			update('estoque_etiqueta_retirada',$cad,"id = '$retiradaId'");	
			
			if($statusAtual=='Em Aberto'){
				
				//RETIRAA DO ESTOQUE;
				$etiquetaId=$edit['id_etiqueta'];
				$estoque= mostra('estoque_etiqueta',"WHERE id = '$etiquetaId'");
				
				$est['estoque'] = $estoque['estoque'] - $cad['quantidade'];
				update('estoque_etiqueta',$est,"id = '$etiquetaId'");	
					
				//ATUALIZADO SALDO CONTRATO;
				$con['saldo_etiqueta'] = $contrato['saldo_etiqueta'] + $cad['quantidade'];
				update('contrato',$con,"id = '$contratoId'");	
				
//				echo 'Status : '.$statusAtual.'<br>';
//				echo 'Saldo Atual : '.$contrato['saldo_etiqueta'] .'<br>';
//				echo 'Retirada : '.$cad['quantidade'] .'<br>';
//				$con['saldo_etiqueta'] = $contrato['saldo_etiqueta'] + $cad['quantidade'];
//				echo 'Saldo  : '.$con['saldo_etiqueta'] .'<br>';

		
			}
			
			$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				header("Location: ".$_SESSION['url']);
		}
	}
		
	if(isset($_POST['deletar'])){
		$cad['id_etiqueta']= strip_tags(trim(mysql_real_escape_string($_POST['id_etiqueta'])));
		delete('estoque_etiqueta_retirada',"id = '$retiradaId'");
		
		if($statusAtual=='Baixa'){
			//DEVOLVE AO ESTOQUE;
			$etiquetaId=$edit['id_etiqueta'];
			$estoque= mostra('estoque_etiqueta',"WHERE id = '$etiquetaId'");

			$est['estoque'] = $estoque['estoque'] + $cad['quantidade'];
			update('estoque_etiqueta',$est,"id = '$etiquetaId'");	
		}
		
		$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
			header("Location: ".$_SESSION['url']);
	}

	?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
    
  		<div class="form-group col-xs-12 col-md-1"> 
             <label>Id</label>
            <input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled />
        </div> 

         <div class="form-group col-xs-12 col-md-3">  
            <label>Etiqueta</label>
                <select name="id_etiqueta" class="form-control">
                    <option value="">Selecione Etiqueta</option>
                    <?php 
                        $readConta = read('estoque_etiqueta',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos etiquetas no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['id_etiqueta'] == $mae['id']){
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
          		<label>Quantidade</label>
               <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-archive"></i>
                       </div>
                      <input type="text" name="quantidade" class="form-control pull-right"  value="<?php echo $edit['quantidade'];?>"/>
                 </div><!-- /.input group -->
         </div>
           

         <div class="form-group col-xs-12 col-md-3">  
               <label>Solicitação</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
               			<input type="date" name="data_solicitacao" class="form-control pull-right" value="<?php echo $edit['data_solicitacao'];?>"/>
              </div><!-- /.input group -->
          </div> 
           
           <div class="form-group col-xs-12 col-md-3">  
               <label>Entrega</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
               			<input type="date" name="data_entrega" class="form-control pull-right" value="<?php echo $edit['data_entrega'];?>"/>
              </div><!-- /.input group -->
          </div> 
		
		 <div class="form-group col-xs-12 col-md-12">  
          		<label>Observação</label>
                       <input type="text" name="observacao" class="form-control pull-right"  value="<?php echo $edit['observacao'];?>"/>
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