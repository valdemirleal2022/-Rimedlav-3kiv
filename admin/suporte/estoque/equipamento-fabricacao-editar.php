<?php 

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php');		
			}	
		}

		$acao = "cadastrar";
		 
		if(!empty($_GET['fabricacaoEditar'])){
			$fabricacaoId = $_GET['fabricacaoEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['fabricacaoBaixar'])){
			$fabricacaoId = $_GET['fabricacaoBaixar'];
			$acao = "baixar";
		}
		if(!empty($_GET['fabricacaoDeletar'])){
			$fabricacaoId = $_GET['fabricacaoDeletar'];
			$acao = "deletar";
		}

		if(!empty($fabricacaoId)){
			
			$readfabricacao= read('estoque_equipamento_fabricacao',"WHERE id = '$fabricacaoId'");
			if(!$readfabricacao){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readfabricacao as $edit);
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
          <h1>Fabricação</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Equipamento</a></li>
            <li><a href="#">Fabricação</a></li>
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
            		  	<a href="painel.php?execute=suporte/relatorio/ficha-fabricacao-equipamento-pdf&fabricacaoId=<?PHP echo $fabricacaoId; ?>"  target="_blank">
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
		$cad['id_equipamento'] = strip_tags(trim(mysql_real_escape_string($_POST['id_equipamento'])));
		$cad['quantidade'] = strip_tags(trim(mysql_real_escape_string($_POST['quantidade'])));
		$cad['data_solicitacao']=strip_tags(trim(mysql_real_escape_string($_POST['data_solicitacao'])));
			if(in_array('',$cad)){
			echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
		}else{
			$cad['fabricacao_inicio'] = mysql_real_escape_string($_POST['fabricacao_inicio']);
			$cad['fabricacao_termino'] = mysql_real_escape_string($_POST['fabricacao_termino']);
			$cad['fabricacao_status'] = 0;
			
			$cad['pintura_inicio'] = mysql_real_escape_string($_POST['pintura_inicio']);
			$cad['pintura_termino'] = mysql_real_escape_string($_POST['pintura_termino']);
			$cad['pintura_status'] = 0;
			$cad['observacao'] = strip_tags(trim(mysql_real_escape_string($_POST['observacao'])));
		 
			create('estoque_equipamento_fabricacao',$cad);
			$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
			header('Location: painel.php?execute=suporte/estoque/equipamento-fabricacao');
			unset($cad);
		}
	}
			 
	if(isset($_POST['atualizar'])){
		$cad['id_equipamento'] = strip_tags(trim(mysql_real_escape_string($_POST['id_equipamento'])));
		$cad['quantidade'] = strip_tags(trim(mysql_real_escape_string($_POST['quantidade'])));
		$cad['data_solicitacao']=strip_tags(trim(mysql_real_escape_string($_POST['data_solicitacao'])));
		if(in_array('',$cad)){
			echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
		 }else{
			$cad['observacao'] = strip_tags(trim(mysql_real_escape_string($_POST['observacao'])));
			$cad['fabricacao_inicio'] = mysql_real_escape_string($_POST['fabricacao_inicio']);
			$cad['fabricacao_termino'] = mysql_real_escape_string($_POST['fabricacao_termino']);
			$cad['fabricacao_status'] = mysql_real_escape_string($_POST['fabricacao_status']);
			
			$cad['pintura_inicio'] = mysql_real_escape_string($_POST['pintura_inicio']);
			$cad['pintura_termino'] = mysql_real_escape_string($_POST['pintura_termino']);
			$cad['pintura_status'] = mysql_real_escape_string($_POST['pintura_status']);
	
			update('estoque_equipamento_fabricacao',$cad,"id = '$fabricacaoId'");	
			$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
			header('Location: painel.php?execute=suporte/estoque/equipamento-fabricacao');
			unset($cad);
		}
	}
	
	if(isset($_POST['baixar'])){
		
		$cad['status'] = 'Baixado';
		$cad['quantidade'] 	= strip_tags(trim(mysql_real_escape_string($_POST['quantidade'])));
		$cad['data_entrega_fabricacao'] = mysql_real_escape_string($_POST['data_entrega_fabricacao']);
		$cad['data_entrega_pintura'] = mysql_real_escape_string($_POST['data_entrega_pintura']);
		
		if(in_array('',$cad)){
			echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
		 }else{
			update('estoque_equipamento_fabricacao',$cad,"id = '$fabricacaoId'");	

			if($statusAtual=='Em Aberto'){
				//RETIRAA DO ESTOQUE;
				$equipamentoId=$edit['id_equipamento'];
				$estoque= mostra('estoque_equipamento',"WHERE id = '$equipamentoId'");

				$est['estoque'] = $estoque['estoque'] + $cad['quantidade'];
				update('estoque_equipamento',$est,"id = '$equipamentoId'");	
				print_r($est);
			}

			$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
			header('Location: painel.php?execute=suporte/estoque/equipamento-fabricacao');
			unset($cad);
		}
	}
		
	if(isset($_POST['deletar'])){
		$cad['id_equipamento']= strip_tags(trim(mysql_real_escape_string($_POST['id_equipamento'])));
		delete('estoque_equipamento_fabricacao',"id = '$fabricacaoId'");
 
		
		$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
		header('Location: painel.php?execute=suporte/estoque/equipamento-fabricacao');
	}

	?>
	<form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
	  
		
		<div class="box-header with-border">
             <h3 class="box-title">Fabricação/Pintura de Container</h3>
        </div><!-- /.box-header -->	 

         <div class="box-body">
           <div class="row">
			   
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


         <div class="form-group col-xs-12 col-md-2">  
          		<label>Quantidade</label>
                <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-archive"></i>
                       </div>
                      <input type="text" name="quantidade" class="form-control pull-right"  value="<?php echo $edit['quantidade'];?>"/>
                 </div><!-- /.input group -->
         </div>
           

         <div class="form-group col-xs-12 col-md-2">  
               <label>Solicitação</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
               			<input type="date" name="data_solicitacao" class="form-control pull-right" value="<?php echo $edit['data_solicitacao'];?>"/>
              </div><!-- /.input group -->
        </div> 
	
		
		
		   </div><!-- /.row -->
       </div><!-- /.box-body -->
		
		<div class="box-header with-border">
             <h3 class="box-title">Fabricação</h3>
        </div><!-- /.box-header -->	 

         <div class="box-body">
           <div class="row">
			
		  <div class="form-group col-xs-12 col-md-4">  
               	<label>Início</label>
   				<input name="fabricacao_inicio" type="datetime-local" value="<?php echo $edit['fabricacao_inicio'];?>"  class="form-control" /> 
		 </div> 
		 
		 <div class="form-group col-xs-12 col-md-4">  
               	<label>Termino</label>
   				<input name="fabricacao_termino" type="datetime-local" value="<?php echo $edit['fabricacao_termino'];?>"  class="form-control" /> 
		 </div> 
	
     
	 
		 <div class="form-group col-xs-12 col-md-4"> 
			  <label>Status </label>
				<select name="fabricacao_status" class="form-control">
				  <option value="">Selecione o status &nbsp;&nbsp;</option>

				  <option <?php if($edit['fabricacao_status'] && $edit['fabricacao_status'] == '1') echo' selected="selected"';?> value="1">Em Manutenção &nbsp;&nbsp;</option>

				  <option <?php if($edit['fabricacao_status'] && $edit['fabricacao_status'] == '2') echo' selected="selected"';?> value="2">Concluida &nbsp;&nbsp;</option>
				 </select>
		 </div>
		
	     </div><!-- /.row -->
       </div><!-- /.box-body -->
		
		<div class="box-header with-border">
             <h3 class="box-title">Pintura</h3>
        </div><!-- /.box-header -->	 

         <div class="box-body">
           <div class="row">
		
		  <div class="form-group col-xs-12 col-md-4">  
               	<label>Início</label>
   				<input name="pintura_inicio" type="datetime-local" value="<?php echo $edit['pintura_inicio'];?>"  class="form-control" /> 
		 </div> 
		 
		 <div class="form-group col-xs-12 col-md-4">  
               	<label>Termino</label>
   				<input name="pintura_termino" type="datetime-local" value="<?php echo $edit['pintura_termino'];?>"  class="form-control" /> 
		 </div> 
	
		 <div class="form-group col-xs-12 col-md-4"> 
			  <label>Status </label>
				<select name="pintura_status" class="form-control">
				  <option value="">Selecione o status &nbsp;&nbsp;</option>

				  <option <?php if($edit['pintura_status'] && $edit['pintura_status'] == '1') echo' selected="selected"';?> value="1">Em Pintura &nbsp;&nbsp;</option>

				  <option <?php if($edit['pintura_status'] && $edit['pintura_status'] == '2') echo' selected="selected"';?> value="2">Concluida &nbsp;&nbsp;</option>
				 </select>
		 </div>
			   
	   </div><!-- /.row -->
    </div><!-- /.box-body -->
		
	<div class="form-group col-xs-12 col-md-12"> 
             <label>Observação</label>
            <input name="observacao" type="text" value="<?php echo $edit['observacao'];?>" class="form-control"  />
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
	
<div class="row">
  <div class="col-md-12">
	<div class="box box-default">
					
			<div class="box-header">
				
				 <a href="painel.php?execute=suporte/estoque/material-retirada-fabricacao-editar&fabricacaoId=<?PHP echo $fabricacaoId; ?>" class="btnnovo">
				  <img src="ico/novo.png" title="Criar Novo" />
					<small>Retirada de Material</small>
					 </a>
		
				
			</div><!-- /.box-header -->

			<div class="box-body table-responsive">

			<?php 
			$totalManutencao=0;
			$leitura = read(' estoque_material_retirada',"WHERE id AND id_fabricacao='$fabricacaoId' ORDER BY id ASC");
			 
			if($leitura){
						
				echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Codigo</td>
					<td align="center">Material</td>
					<td align="center">Quantidade</td>
					<td align="center">Unidade</td>
					<td align="center">Vl Unitário</td>
					<td align="center">Total</td>
					<td align="center">Observação</td>
					<td align="center" colspan="2">Gerenciar</td>
				</tr>';
				
			foreach($leitura as $mostra):
	 
		 	echo '<tr>';
				
				echo '<td align="center">'.$mostra['id'].'</td>';
				
				$materialId = $mostra['id_material'];
				$material = mostra('estoque_material',"WHERE id ='$materialId'");
				
				echo '<td>'.$material['codigo'].'</td>';
				echo '<td>'.$material['nome'].'</td>';
				
				echo '<td align="right">'.$mostra['quantidade'].'</td>';
				echo '<td align="right">'.$material['unidade'].'</td>';
				
				$totalManutencao=$totalManutencao+$mostra['quantidade']*$material['valor_unitario'];
				
				echo '<td align="right">'.converteValor($material['valor_unitario']).'</td>';
				echo '<td align="right">'.converteValor($mostra['quantidade']*$material['valor_unitario']).'</td>';
				
				echo '<td align="right">'.$mostra['observacao'].'</td>';
			
				 
					echo '<td align="center">
			  			<a href="painel.php?execute=suporte/estoque/material-retirada-editar&retiradaDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png" alt="Excluir" title="Excluir" />
              			</a>
						</td>';

			echo '</tr>';
		
		 endforeach;
		 
		
			echo '<tfoot>';
         			echo '<tr>';
                	echo '<td colspan="14">' . 'Total da  Manutenção : ' . converteValor($totalManutencao) . '</td>';
                echo '</tr>';

          echo '</tfoot>';
		
		echo '</table>';
		
		
		}
	?>

				<div class="box-footer">
					<?php echo $_SESSION['cadastro'];
					unset($_SESSION['cadastro']);
					?>
				</div>
						<!-- /.box-footer-->

					</div>
					<!-- /.box-body table-responsive -->
				</div>
				<!-- /.box -->
			</div>
			<!-- /.col-md-12 -->
		</div>
		<!-- /.row -->

	</section>
	<!-- /.content -->
	
	
	
	
</div><!-- /.content-wrapper -->
			 