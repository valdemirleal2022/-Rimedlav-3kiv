<?php 
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php');		
			}	
		}
		
		$acao = "cadastrar";
		$edit['status'] = 'Solicitando';
		$edit['data_solicitacao'] =date("Y-m-d");
		$edit['interacao']= date('Y/m/d H:i:s');

		if(!empty($_GET['solicitacaoEditar'])){
			$solicitacaoId = $_GET['solicitacaoEditar'];
			$acao = "atualizar";
		}
		
		if(!empty($_GET['solicitacaoBaixar'])){
			$solicitacaoId = $_GET['solicitacaoaixar'];
			$acao = "baixar";
		}
		if(!empty($_GET['solicitacaoDeletar'])){
			$solicitacaoId = $_GET['solicitacaoDeletar'];
			$acao = "deletar";
		}

		if(!empty($solicitacaoId)){
			$readcompra= read('estoque_compras',"WHERE id = '$solicitacaoId'");
			if(!$readcompra){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readcompra as $edit);

		}

		$_SESSION['aba']=1;

 
///* Informa o nível dos erros que serão exibidos */
//error_reporting(E_ALL);
// 
///* Habilita a exibição de erros */
//ini_set("display_errors", 1);

 ?>
 
<div class="content-wrapper">
	
  <section class="content-header">
          <h1>Compras - Solicitação</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Compras</a></li>
            <li><a href="#">Solicitação</a></li>
             <li class="active">Editar</li>
          </ol>
  </section>
	
  <section class="content">
	  
      <div class="box box-default">
            <div class="box-header with-border">
            
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
		
		$cad['id_material'] = strip_tags(trim(mysql_real_escape_string($_POST['id_material'])));
		$cad['solicitante'] = strip_tags(trim(mysql_real_escape_string($_POST['solicitante'])));
		$cad['interacao']= date('Y/m/d H:i:s');
		if(in_array('',$cad)){
			echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
		 }else{
			
			update('estoque_compras',$cad,"id = '$solicitacaoId'");	
			
			$_SESSION['cadastro'] = '<div class="alert alert-success">Adicione o material</div>';
			header("Location: ".$_SESSION['url']);	
			 
		}
	}
			 
	if(isset($_POST['cotar'])){
		
		$cad['status'] = '2';
		$cad['interacao']= date('Y/m/d H:i:s');
		if(in_array('',$cad)){
			echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
		 }else{
			
			update('estoque_compras',$cad,"id = '$solicitacaoId'");	
			
			$_SESSION['cadastro'] = '<div class="alert alert-success">Adicione o material</div>';
			header("Location: ".$_SESSION['url']);	
			 
		}
	}
		
	if(isset($_POST['cadastrar'])){
		
		//$cad['data_cotacao'] = date('Y/m/d');
		$cad['data_solicitacao'] = mysql_real_escape_string($_POST['data_solicitacao']);
		$cad['status'] = 1;
		$cad['id_material'] = strip_tags(trim(mysql_real_escape_string($_POST['id_material'])));
		$cad['solicitante'] = strip_tags(trim(mysql_real_escape_string($_POST['solicitante'])));
		$cad['interacao']= date('Y/m/d H:i:s');
		
		if(in_array('',$cad)){
			
			echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
			
		}else{
			
			create('estoque_compras',$cad);
			$solicitacaoId = $ultimoId;
			
			$_SESSION['cadastro'] = '<div class="alert alert-success">Adicione o material</div>';
			header('Location: painel.php?execute=suporte/compras/solicitacao-editar&solicitacaoEditar='.$solicitacaoId);
 
			
		}
	}
		
	if(isset($_POST['deletar'])){
		delete('estoque_compras',"id = '$solicitacaoId'");
		$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
		header("Location: ".$_SESSION['url']);	
	}


	?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
		
  			 <div class="form-group col-xs-12 col-md-2">  
               <label>Id </label>
               <input name="id" type="text" value="<?php echo $edit['id'];?>"  readonly class="form-control" />
             </div> 
		
             <div class="form-group col-xs-12 col-md-2">  
               	<label>Interação</label>
   				<input name="orc_resposta" type="text" value="<?php echo date('d/m/Y H:i:s',strtotime($edit['interacao']));?>" readonly class="form-control" /> 
			</div> 
		  
			<div class="form-group col-xs-12 col-md-6">  
				 <label>Tipo Material</label>
				<select name="id_material"  class="form-control" >
					<option value="">Selecione o Tipo</option>
							<?php 
							$readContrato = read('estoque_material_tipo',"WHERE id  ORDER BY codigo ASC");
							if(!$readContrato){
								echo '<option value="">Nao registro no momento</option>';	
							}else{
								foreach($readContrato as $mae):
									if($edit['id_material'] == $mae['id']){
										echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['codigo'].' - '.$mae['nome'].'</option>';
									}else{
										echo '<option value="'.$mae['id'].'">'.$mae['codigo'].' -  '.$mae['nome'].'</option>';
									}
									endforeach;	
								}
							?> 
				 </select>
			</div> 
		
		
		  <div class="form-group col-xs-12 col-md-2">  
                 <label>Data da Solicitação</label>
                
               <input type="date" name="data_solicitacao" class="form-control pull-right" value="<?php echo $edit['data_solicitacao'];?>"  />
          
           </div> 
		
		
		  <div class="form-group col-xs-12 col-md-3">  
            	<label>Solicitante</label>
                <input name="solicitante" type="text" value="<?php echo $edit['solicitante'];?>" class="form-control" /> 
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
						echo '<input type="submit" name="cotar" value="Abrir Cotações" class="btn btn-success" />';	
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
					
					<?php 
					  if($acao<>'cadastrar'){
					?>
		
				 <a href="painel.php?execute=suporte/compras/solicitacao-material-editar&solicitacaoId=<?PHP echo $solicitacaoId; ?>" class="btnnovo">
				  <img src="ico/novo.png" title="Criar Novo" />
					<small>Adicionar Material</small>
					 </a>
					
					<?php 
					  }
					?>
		
				</div><!-- /.box-header -->
					

		<div class="box-body table-responsive">

			<?php 

			$leitura = read('estoque_compras_material',"WHERE id AND id_compras='$solicitacaoId' ORDER BY id ASC");
			
			if($leitura){
				
				echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Material</td>
					<td align="center">Quantidade</td>
					<td align="center">Unidade</td>
					<td align="center">Observacao</td>
					<td align="center">Veículo</td>
					<td align="center" colspan="4">Gerenciar</td>
				</tr>';
				
			foreach($leitura as $mostra):
	 
		 	echo '<tr>';
				
				echo '<td align="center">'.$mostra['id'].'</td>';
				
				$materialId = $mostra['id_material'];
				$material = mostra('estoque_material',"WHERE id ='$materialId'");
				echo '<td>'.$material['nome'].'</td>';
				
				echo '<td align="right">'.$mostra['quantidade'].'</td>';
				echo '<td align="left">'.$mostra['unidade'].'</td>';
				echo '<td align="left">'.$mostra['observacao'].'</td>';
				
				$veiculoId = $mostra['id_veiculo'];
				$veiculo = mostra('veiculo',"WHERE id ='$veiculoId'");
				echo '<td>'.$veiculo['placa'].'</td>';

				$total++;
					
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/compras/solicitacao-material-editar&compraMaterialEditar='.$mostra['id'].'">
							<img src="ico/editar.png" alt="Editar" title="Editar" />
              			</a>
					</td>';

					echo '<td align="center">
			  			<a href="painel.php?execute=suporte/compras/solicitacao-material-editar&compraMaterialDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png" alt="Excluir" title="Excluir" />
              			</a>
					</td>';
				
						
			echo '</tr>';
		
		 endforeach;
 
		echo '</table>';
 
	}
	?>

					<div class="box-footer">
 

							<?php echo $_SESSION['cadastro'];
							unset($_SESSION['cadastro']);
							?>
 

					</div><!-- /.box-footer-->

					</div><!-- /.box-body table-responsive -->
				</div><!-- /.box -->
			</div><!-- /.col-md-12 -->
		</div>	<!-- /.row -->

	</section>
	<!-- /.content -->
	
	
	
	
</div><!-- /.content-wrapper -->