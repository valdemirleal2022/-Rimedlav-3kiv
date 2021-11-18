<?php 
		if(function_exists(ProtUser)){
			
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php');		
			}	
			
		}
 
		if(!empty($_GET['reposicaoNova'])){
			
			$estoqueMateriaId = $_GET['reposicaoNova'];
			$acao = "cadastrar";
			
		}

		if(!empty($_GET['reposicaoCotacao'])){
			
			$cotacaoId = $_GET['reposicaoCotacao'];
			$cotacao= mostra('estoque_compras_material',"WHERE id = '$cotacaoId'");
			 
			$cad['id_material'] = $cotacao['id_material'];
			$cad['quantidade' ]= $cotacao['quantidade'];
			
			$estoqueMateriaId = $cad['id_material'];
			
			$compraId=$cotacao['id_compras'];
			$compra= mostra('estoque_compras',"WHERE id = '$compraId'");
	 
			if($compra['fornecedor1_melhor']==1){
				$cad['valor_unitario']=$cotacao['valor1'];
			}else if($compra['fornecedor2_melhor']==1){
				$cad['valor_unitario']=$cotacao['valor2'];
			}else if($compra['fornecedor3_melhor']==1){
				$cad['valor_unitario']=$cotacao['valor3'];
			}
	 
			$cad['nota_fiscal'] = $compra['nota_fiscal'];
			$cad['data']= $compra['data_recebimento'];
			$cad['id_compra']= $compraId;
			$acao = "cadastrar";
			
		}

		if(!empty($_GET['reposicaoDeletar'])){
			
			$reposicaoId = $_GET['reposicaoDeletar'];
			$acao = "deletar";
			
		}

		if(!empty($reposicaoId)){
			
			$readreposicao= read('estoque_material_reposicao',"WHERE id = '$reposicaoId'");
			if(!$readreposicao){
				header('Location: painel.php?execute=suporte/error');	
			}
			
			foreach($readreposicao as $edit);

		}

		if(!empty($estoqueMateriaId)){
			
			$readMaterial= mostra('estoque_material',"WHERE id = '$estoqueMateriaId'");
			if(!$readMaterial){
				header('Location: painel.php?execute=suporte/error');	
			}
			
			$cad['id_material']=$readMaterial['id'];
			$cad['id_tipo']=$readMaterial['id_tipo'];
			$cad['saldo_anterior']=$readMaterial['estoque'];
		 
			 
		}
 ?>
 
<div class="content-wrapper">
  <section class="content-header">
          <h1>Reposição</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Material</a></li>
            <li><a href="#">Reposição</a></li>
             <li class="active">Editar</li>
          </ol>
  </section>
  <section class="content">
      <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo $readMaterial['nome'];?></h3>
				  <h3 class="box-title">  || Saldo Atual : <?php echo $readMaterial['estoque'];?></h3>
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
	
		
	if(isset($_POST['cadastrar'])){
		
		$cad['quantidade'] = strip_tags(trim(mysql_real_escape_string($_POST['quantidade'])));
		$cad['data'] = strip_tags(trim(mysql_real_escape_string($_POST['data'])));
		$cad['nota_fiscal'] = strip_tags(trim(mysql_real_escape_string($_POST['nota_fiscal'])));
		$cad['valor_unitario'] =mysql_real_escape_string($_POST['valor_unitario']);
		$cad['valor_unitario'] = str_replace(",",".",str_replace(".","",$cad['valor_unitario']));
		
		if(in_array('',$cad)){
				print_r($cad);
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
		}else{
			create('estoque_material_reposicao',$cad);
			
			//ATUALIZA ESTOQUE;
			$materialId=$cad['id_material'];
			$estoque= mostra('estoque_material',"WHERE id = '$materialId'");
			$est['estoque'] = $estoque['estoque'] + $cad['quantidade'];
			$est['valor_unitario'] = $cad['valor_unitario'];
			update('estoque_material',$est,"id = '$materialId'");
			
			if(isset($cotacaoId)){
				
				$cot['quantidade_recebida'] = $cad['quantidade'];
				$cot['data_entrega'] = $cad['data'];
				update('estoque_compras_material',$cot,"id = '$cotacaoId'");
				
			}
	
			$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
			header("Location: ".$_SESSION['url']);
			unset($cad);
		}
	}
		
	if(isset($_POST['deletar'])){
		delete('estoque_material_reposicao',"id = '$reposicaoId'");
		$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
		header('Location: painel.php?execute=suporte/estoque/material-reposicao');
	}

	?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
		
  			<div class="form-group col-xs-12 col-md-1"> 
               <label>Id</label>
               <input name="id" type="text" value="<?php echo $cad['id'];?>" class="form-control" disabled />
            </div> 
            
           <div class="form-group col-xs-12 col-md-5">  
            <label>material</label>
                <select name="id_material" class="form-control" disabled>
                    <option value="">material</option>
                    <?php 
                        $readConta = read('estoque_material',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos materials no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($cad['id_material'] == $mae['id']){
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
          		<label>Quantidade</label>
               <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-archive"></i>
                       </div>
                      <input type="text" name="quantidade" class="form-control pull-right"  value="<?php echo $cad['quantidade'];?>"/>
                 </div><!-- /.input group -->
           </div>
           

           <div class="form-group col-xs-12 col-md-3">  
                 <label>Data</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
               <input type="date" name="data" class="form-control pull-right" value="<?php echo date('Y-m-d',strtotime($cad['data'])) ?>"/>
                </div><!-- /.input group -->
           </div> 
           
           <div class="form-group col-xs-12 col-md-3">  
          		<label>Valor Unitário</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="valor_unitario" class="form-control pull-right"  value="<?php echo converteValor($cad['valor_unitario']);?>"/>
                 </div><!-- /.input group -->
           </div>
		
		<div class="form-group col-xs-12 col-md-3"> 
               <label>Nota Fiscal</label>
               <input name="nota_fiscal" type="text" value="<?php echo $cad['nota_fiscal'];?>" class="form-control" />
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