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
			
			$readretirada= read('estoque_equipamento_retirada',"WHERE id = '$retiradaId'");
			if(!$readretirada){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readretirada as $edit);
			$clienteId = $edit['id_cliente'];
			$statusAtual = $edit['status'];
			$cliente = mostra('cliente',"WHERE id = '$clienteId'");

		}

		if(!empty($_GET['contratoId'])){
			$contratoId = $_GET['contratoId'];
			$contrato = mostra('contrato',"WHERE id = '$contratoId'");
			$clienteId = $contrato['id_cliente'];
			$cliente = mostra('cliente',"WHERE id = '$clienteId'");
			$acao = "cadastrar";
		}
		
		$_SESSION['url']=$_SERVER['REQUEST_URI'];
 ?>
 
<div class="content-wrapper">
 
  <section class="content-header">
          <h1>Retirada</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Equipamento</a></li>
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
            		  	<a href="painel.php?execute=suporte/relatorio/ficha-solicitacao-equipamento-pdf&retiradaId=<?PHP echo $retiradaId; ?>"  target="_blank">
						 <img src="ico/contratos.png" title="Solicitacao de Equipamento" />
						  <small>Solicitação de Equipamento</small>
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
		$cad['id_equipamento'] = strip_tags(trim(mysql_real_escape_string($_POST['id_equipamento'])));
		$cad['quantidade'] = strip_tags(trim(mysql_real_escape_string($_POST['quantidade'])));
		$cad['data_solicitacao']=strip_tags(trim(mysql_real_escape_string($_POST['data_solicitacao'])));
		$cad['data_entrega'] = strip_tags(trim(mysql_real_escape_string($_POST['data_entrega'])));
		$cad['tipo'] = strip_tags(trim(mysql_real_escape_string($_POST['tipo'])));
		if(in_array('',$cad)){
			echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
		}else{
			$cad['observacao'] = strip_tags(trim(mysql_real_escape_string($_POST['observacao'])));
			create('estoque_equipamento_retirada',$cad);
			$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
			header('Location: painel.php?execute=suporte/estoque/equipamento-retirada-emaberto');
			unset($cad);
		}
	}
			 
	if(isset($_POST['atualizar'])){
		$cad['id_equipamento']= strip_tags(trim(mysql_real_escape_string($_POST['id_equipamento'])));
		$cad['quantidade'] 	= strip_tags(trim(mysql_real_escape_string($_POST['quantidade'])));
		$cad['data_solicitacao']=strip_tags(trim(mysql_real_escape_string($_POST['data_solicitacao'])));
		$cad['data_entrega'] = strip_tags(trim(mysql_real_escape_string($_POST['data_entrega'])));
		$cad['tipo'] = strip_tags(trim(mysql_real_escape_string($_POST['tipo'])));
		if(in_array('',$cad)){
			echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
		 }else{
			$cad['observacao'] = strip_tags(trim(mysql_real_escape_string($_POST['observacao'])));
			update('estoque_equipamento_retirada',$cad,"id = '$retiradaId'");	
			$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
			header('Location: painel.php?execute=suporte/estoque/equipamento-retirada');
			unset($cad);
		}
	}
	
	if(isset($_POST['baixar'])){
		$cad['status'] = 'Baixado';
		$cad['quantidade'] 	= strip_tags(trim(mysql_real_escape_string($_POST['quantidade'])));
		$cad['data_entrega'] = strip_tags(trim(mysql_real_escape_string($_POST['data_entrega'])));
		$cad['bom_estado'] = strip_tags(trim(mysql_real_escape_string($_POST['bom_estado'])));
		
		if(in_array('',$cad)){
			echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
		 }else{
			update('estoque_equipamento_retirada',$cad,"id = '$retiradaId'");	

			if($statusAtual=='Em Aberto'){
				//RETIRAA DO ESTOQUE;
				$equipamentoId=$edit['id_equipamento'];
				$estoque= mostra('estoque_equipamento',"WHERE id = '$equipamentoId'");

				$est['estoque'] = $estoque['estoque'] - $cad['quantidade'];
				update('estoque_equipamento',$est,"id = '$equipamentoId'");	
				print_r($est);
			}

			$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
			header('Location: painel.php?execute=suporte/estoque/equipamento-retirada');
			unset($cad);
		}
	}
		
	if(isset($_POST['deletar'])){
		$cad['id_equipamento']= strip_tags(trim(mysql_real_escape_string($_POST['id_equipamento'])));
		delete('estoque_equipamento_retirada',"id = '$retiradaId'");
		
		//DEVOLVE AO ESTOQUE;
		$equipamentoId=$cad['id_equipamento'];
		$estoque= mostra('estoque_equipamento',"WHERE id = '$equipamentoId'");
		$est['estoque'] = $estoque['estoque'] + $cad['quantidade'];
		update('estoque_equipamento',$est,"id = '$equipamentoId'");	
		
		$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
		header('Location: painel.php?execute=suporte/estoque/equipamento-retirada');
	}

	?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
    
  		<div class="form-group col-xs-12 col-md-2"> 
             <label>Id</label>
            <input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled />
        </div> 

         <div class="form-group col-xs-12 col-md-6">  
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


         <div class="form-group col-xs-12 col-md-4">  
          		<label>Quantidade</label>
               <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-archive"></i>
                       </div>
                      <input type="text" name="quantidade" class="form-control pull-right"  value="<?php echo $edit['quantidade'];?>"/>
                 </div><!-- /.input group -->
         </div>
           

         <div class="form-group col-xs-12 col-md-6">  
               <label>Solicitação</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
               			<input type="date" name="data_solicitacao" class="form-control pull-right" value="<?php echo $edit['data_solicitacao'];?>"/>
              </div><!-- /.input group -->
          </div> 
           
           <div class="form-group col-xs-12 col-md-6">  
               <label>Entrega</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
               			<input type="date" name="data_entrega" class="form-control pull-right" value="<?php echo $edit['data_entrega'];?>"/>
              </div><!-- /.input group -->
          </div> 
		
		
		 <div class="form-group col-xs-12 col-md-2">  
                 <label>Tipo</label>
                 <select name="tipo" class="form-control">
                  <option value="">Selecione</option>
                  <option <?php if($edit['tipo'] == '1') echo' selected="selected"';?> value="1">Troca </option>
                  <option <?php if($edit['tipo'] == '2') echo' selected="selected"';?> value="2">Entrega</option>
				  <option <?php if($edit['tipo'] == '3') echo' selected="selected"';?> value="3">Retirada</option>
				  <option <?php if($edit['tipo'] == '4') echo' selected="selected"';?> value="4">Manutenção</option>
                 </select>
           </div>
		
		
		<div class="form-group col-xs-12 col-md-8"> 
             <label>Observação</label>
            <input name="observacao" type="text" value="<?php echo $edit['observacao'];?>" class="form-control"  />
        </div> 
		
		 <div class="form-group col-xs-12 col-md-2">  
                 <label>Bom Estado</label>
                 <select name="bom_estado" class="form-control">
                  <option value="">Selecione</option>
                  <option <?php if($edit['bom_estado'] == '1') echo' selected="selected"';?> value="1">Sim </option>
                  <option <?php if($edit['bom_estado'] == '2') echo' selected="selected"';?> value="2">Nao</option>
				 
                 </select>
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
	
  <section class="content">
         <div class="box box-default">
           <div class="box-header with-border">
	
			<div class="box-header with-border">
                  <h3 class="box-title">Numeração</h3>
            </div><!-- /.box-header -->
      
            <div class="box-header">
            	 <div class="col-xs-10 col-md-3 pull-left">  
					 <a href="painel.php?execute=suporte/estoque/equipamento-retirada-numeracao-editar&retiradaId=<?PHP echo $retiradaId; ?>" class="btnnovo">
					 <img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" />
					 </a>
				</div><!-- /.col-xs-10 col-md-3 pull-left-->
 			</div><!-- /.box-header -->
 					
			<div class="box-body table-responsive">  
			 <div class="box-body table-responsive data-spy='scroll'">
			  <div class="col-md-12 scrool">  
     
				<?php 

				$total = conta('estoque_equipamento_retirada_numeracao',"WHERE id AND 	id_retirada='$retiradaId' ORDER BY id ASC");
				  
				$leitura = read('estoque_equipamento_retirada_numeracao',"WHERE id AND 	id_retirada='$retiradaId' ORDER BY id ASC");
				if($leitura){
						echo '<table class="table table-hover">	
								<tr class="set">
								<td align="center">Id</td>
								<td align="center">Equipamento</td>
								<td align="center">Numeração</td>
								<td align="center" colspan="5">Gerenciar</td>
							</tr>';
					foreach($leitura as $mostra2):

						echo '<tr>';

							echo '<td>'.$mostra2['id'].'</td>';
					
							$numeracaoId = $mostra2['id'];
							$equipamentoId = $mostra2['id_equipamento'];
				
							$equipamento = mostra('estoque_equipamento',"WHERE id ='$equipamentoId'");
							echo '<td>'.$equipamento['nome'].'</td>';
					
							echo '<td align="center">'.$mostra2['numeracao'].'</td>';
							

							echo '<td align="center">
										<a href="painel.php?execute=suporte/estoque/equipamento-retirada-numeracao-editar&numeracaoEditar='.$mostra2['id'].'">
											<img src="ico/editar.png" title="Editar Coleta"/>
										</a>
									  </td>';
							echo '<td align="center">
									<a href="painel.php?execute=suporte/estoque/equipamento-retirada-numeracao-editar&numeracaoDeletar='.$mostra2['id'].'">
										<img src="ico/excluir.png" title="Deletar Coleta" />
									</a>
								  </td>';
						echo '</tr>';
					 endforeach;
					  echo '<tfoot>';
         			echo '<tr>';
                	echo '<td colspan="14">' . 'Total de Registros : ' .  $total . '</td>';
                echo '</tr>';
          echo '</tfoot>';
		
		echo '</table>';
					}
				?>
 			
      		        </div><!--/col-md-12 scrool-->   
				</div><!-- /.box-body table-responsive -->
       		 </div><!--/box-body table-responsive-->
	
			</div><!--/box-->
		</div><!--/box-->
	</section><!-- /.content -->
	
</div><!-- /.content-wrapper -->