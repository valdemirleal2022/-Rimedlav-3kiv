<?php 
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php');		
			}	
		}
		
		$acao = "cadastrar";
		$usuarioId=$_SESSION['autUser']['id'];
		$usuario= mostra('usuarios',"WHERE id AND id='$usuarioId'");

		$edit['solicitante']=$usuario['id'];
		$edit['data'] = date("Y-m-d");
		$edit['status'] = 'Aguardando';
	
		// 1 - Operacional  2 - Atendimento ao Cliente / Comercial  3 - Faturamento / Cobrança 4 - Compras/Financeiro
// 5 - Gerencial  6 - Manifesto  7 - DP/RH  8 - Vendas  9 - Manutenção / Almoxarifado  


		if($usuario['nivel']=='1'){  
		  $edit['area']='Operacional';
		} 
		if($usuario['nivel']=='2'){ 
		  $edit['area']='Atendimento';
		} 
		if($usuario['nivel']=='3'){ 
		  $edit['area']='Faturamento';
		} 
		if($usuario['nivel']=='4'){ 
		  $edit['area']='Financeiro';
		} 
		if($usuario['nivel']=='5'){  
		    $edit['area']='Gerencial';
		} 
		if($usuario['nivel']=='6'){ 
		   $edit['area']='Manifesto ';
		} 
		if($usuario['nivel']=='7'){  
		   $edit['area']='DP/RH';
		} 
		if($usuario['nivel']=='8'){  
		   $edit['area']='Vendas';
		} 
		if($usuario['nivel']=='9'){  
		   $edit['area']='Manutenção';
		} 
		 
			
		if(!empty($_GET['requisicaoEditar'])){
			$requisicaoId = $_GET['requisicaoEditar'];
			$acao = "atualizar";
		}

		if(!empty($_GET['requisicaoBaixar'])){
			$requisicaoId = $_GET['requisicaoBaixar'];
			$acao = "baixar";
			$readonly = "readonly";
			$disabled = 'disabled="disabled"';
		}

		if(!empty($_GET['requisicaoDeletar'])){
			$requisicaoId = $_GET['requisicaoDeletar'];
			$acao = "deletar";
		}

		if(!empty($requisicaoId)){
			$readcompra= read('estoque_material_requisicao',"WHERE id = '$requisicaoId'");
			if(!$readcompra){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readcompra as $edit);
			
			$requisicaoId=$edit['id'];
			$materialId=$edit['id_material'];
			
			$estoque= mostra('estoque_material',"WHERE id = '$materialId'");
			
			if($acao == "baixar"){
				$edit['quantidade_liberada']=$edit['quantidade'];
			}

		}
 

 ?>
 
<div class="content-wrapper">
	
  <section class="content-header">
          <h1>Requisição - Material</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Material</a></li>
            <li><a href="#">Cotação</a></li>
             <li class="active">Editar</li>
          </ol>
  </section>
	
  <section class="content">
      <div class="box box-default">
            <div class="box-header with-border">
				<h3 class="box-title"> <?php echo $estoque['nome'];?> || Estoque : <?php echo $estoque['estoque'];?>  </h3>
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
		
	if(isset($_POST['baixar'])){
		
		$edit['quantidade_liberada'] = mysql_real_escape_string($_POST['quantidade_liberada']);
		
		//RETIRAA DO ESTOQUE;
		$estoque= mostra('estoque_material',"WHERE id = '$materialId'");
  		 	
		if($estoque['estoque']<$edit['quantidade_liberada']){
			echo '<div class="alert alert-warning">Estoque insuficiente!</div>';	
		}else{
			
			$cad['observacao'] = $edit['observacao'];
			$cad['quantidade'] =  $edit['quantidade_liberada'];
			$cad['data'] = $edit['data'];
			$cad['id_material']= $edit['id_material'];
			$cad['id_tipo'] = $edit['id_tipo'];
			create('estoque_material_retirada',$cad);

			$est['estoque'] = $estoque['estoque'] - $cad['quantidade'];
			update('estoque_material',$est,"id = '$materialId'");	
			
			$req['status']='Baixado';
			$req['quantidade_liberada'] =  $edit['quantidade_liberada'];
			update('estoque_material_requisicao',$req,"id = '$requisicaoId'");	
			
			$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
			header('Location: painel.php?execute=suporte/compras/requisicoes');
			unset($cad);
		}
		
	}

	if(isset($_POST['atualizar'])){
		
		$cad['id_material'] = strip_tags(trim(mysql_real_escape_string($_POST['id_material'])));
		$cad['quantidade'] = strip_tags(trim(mysql_real_escape_string($_POST['quantidade'])));
		$cad['observacao'] = strip_tags(trim(mysql_real_escape_string($_POST['observacao'])));
		$cad['data']= strip_tags(trim(mysql_real_escape_string($_POST['data'])));
		$cad['status']= strip_tags(trim(mysql_real_escape_string($_POST['status'])));
		if(in_array('',$$cad)){
			echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
		 }else{
			 
			update('estoque_material_requisicao',$cad,"id = '$requisicaoId'");	
			header('Location: painel.php?execute=suporte/compras/requisicoes');
			
		}
	}
		
	if(isset($_POST['cadastrar'])){
		
	 	$edit['id_material'] = strip_tags(trim(mysql_real_escape_string($_POST['id_material'])));
		$edit['quantidade'] = strip_tags(trim(mysql_real_escape_string($_POST['quantidade'])));
		$edit['observacao'] = strip_tags(trim(mysql_real_escape_string($_POST['observacao'])));
		$edit['area']= strip_tags(trim(mysql_real_escape_string($_POST['area'])));
		$edit['solicitante'] = strip_tags(trim(mysql_real_escape_string($_POST['solicitante'])));
		$edit['data']= strip_tags(trim(mysql_real_escape_string($_POST['data'])));
		$edit['status']= 'Aguardando';
		
		if(in_array('',$edit)){
 			echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
		}else{
		 
			create('estoque_material_requisicao',$edit);
			
			$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
			header('Location: painel.php?execute=suporte/compras/requisicoes');
		}
	}
		
	if(isset($_POST['deletar'])){
		
		delete('estoque_material_requisicao',"id = '$requisicaoId'");
		
		$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
		header('Location: painel.php?execute=suporte/compras/requisicoes');
	}

	?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
		
  		  <div class="form-group col-xs-12 col-md-2">  
               <label>Id </label>
               <input name="id" type="text" value="<?php echo $edit['id'];?>"  readonly class="form-control" />
          </div> 
		
		 <div class="form-group col-xs-12 col-md-4">  
               <label>Area </label>
               <input name="area" type="text" value="<?php echo $edit['area'];?>"  readonly class="form-control" />
          </div> 
		
		<div class="form-group col-xs-12 col-md-4">  
            <label>Solicitante</label>
                <select name="solicitante" class="form-control" readonly />
                    <option value="">Selecione</option>
                    <?php 
                     $readConta = read('usuarios',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos fornecedor no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['solicitante'] == $mae['id']){
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
               <label>Status </label>
               <input name="status" type="text" value="<?php echo $edit['status'];?>"  readonly class="form-control" />
          </div> 
		
		 
            
		 <div class="form-group col-xs-12 col-md-6">  
            <label>Material</label>
                <select name="id_material" class="form-control" <?php echo $readonly;?> />
                    <option value="">Selecione o Material</option>
                    <?php 
                     $readConta = read('estoque_material',"WHERE id ORDER BY id_tipo ASC, nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos fornecedor no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['id_material'] == $mae['id']){
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
               <label>Quantidade Solicitada </label>
               <input name="quantidade" type="number" value="<?php echo $edit['quantidade'];?>"  class="form-control" <?php echo $readonly;?> />
          </div> 
		
		 <div class="form-group col-xs-12 col-md-3">  
                 <label>Data </label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
               <input type="date" name="data" class="form-control pull-right" value="<?php echo $edit['data'];?>"  <?php echo $readonly;?> />
                </div><!-- /.input group -->
           </div> 

	
		 <div class="form-group col-xs-12 col-md-12">  
               <label>Observação </label>
               <input name="observacao" type="text" value="<?php echo $edit['observacao'];?>"  class="form-control" <?php echo $readonly;?> />
          </div> 
		  
		  <?php 
			if($acao=="baixar"){
		  ?>
		  <div class="form-group col-xs-12 col-md-3">  
               <label>Quantidade Liberada </label>
               <input name="quantidade_liberada" type="number" value="<?php echo $edit['quantidade_liberada'];?>"  class="form-control" />
          </div> 
 		  <?php 
			}
		  ?>
		 
    	 <div class="form-group col-xs-12 col-md-12">  
             
		 <div class="box-footer">
			 <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>
				  <?php 
					if($acao=="baixar"){
						echo '<input type="submit" name="baixar" value="Baixar No Estoque" class="btn btn-primary" />';	
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