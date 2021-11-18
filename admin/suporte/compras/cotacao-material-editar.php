<?php 
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php');		
			}	
		}
		
		

		if(!empty($_GET['cotacaoId'])){
			$cotacaoId = $_GET['cotacaoId'];
			$acao = "cadastrar";
			$edit['status']='Aguardando';
		}

		if(!empty($_GET['cotacaoMaterialEditar'])){
			$cotacaoMaterialId = $_GET['cotacaoMaterialEditar'];
			$acao = "atualizar";
		}

		if(!empty($_GET['cotacaoMaterialDeletar'])){
			$cotacaoMaterialId = $_GET['cotacaoMaterialDeletar'];
			$acao = "deletar";
		}

		if(!empty($cotacaoMaterialId)){
			$readcompra= read('estoque_compras_material',"WHERE id = '$cotacaoMaterialId'");
			if(!$readcompra){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readcompra as $edit);
			
			$compraMaterialId=$edit['id'];
			$cotacaoId=$edit['id_compras'];
			
				
			
			if ($edit['fornecedor1'] == "1") {
				$edit['fornecedor1'] = "checked='CHECKED'";
			} else {
				$edit['fornecedor1'] = "";
			}
			if ($edit['fornecedor2'] == "1") {
				$edit['fornecedor2'] = "checked='CHECKED'";
			} else {
				$edit['fornecedor2'] = "";
			}
			if ($edit['fornecedor3'] == "1") {
				$edit['fornecedor3'] = "checked='CHECKED'";
			} else {
				$edit['fornecedor3'] = "";
			}

		}

		if(!empty($cotacaoId)){
			
			$readcompra= read('estoque_compras',"WHERE id = '$cotacaoId'");
			if(!$readcompra){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readcompra as $cotacao);
			
			$materialId=$cotacao['id_material'];
			$materialTipo= mostra('estoque_material_tipo',"WHERE id = '$materialId'");

		}

		$total1=$edit['quantidade']*$edit['valor1'];
		$total2=$edit['quantidade']*$edit['valor2'];
		$total3=$edit['quantidade']*$edit['valor3'];
			

 ?>
 
<div class="content-wrapper">
	
  <section class="content-header">
          <h1>Cotação - Material</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Compras</a></li>
            <li><a href="#">Cotação</a></li>
             <li class="active">Editar</li>
          </ol>
  </section>
	
  <section class="content">
      <div class="box box-default">
            <div class="box-header with-border">
				
  				 <h3 class="box-title">Tipo Material : <?php echo  $materialTipo['nome'] ; ?></h3>
				
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
		$cad['quantidade'] = strip_tags(trim(mysql_real_escape_string($_POST['quantidade'])));
		$cad['unidade'] = strip_tags(trim(mysql_real_escape_string($_POST['unidade'])));
		
		if(in_array('',$cad)){
			echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
		 }else{
			
			$cad['valor1']= strip_tags(trim(mysql_real_escape_string($_POST['valor1'])));
			$cad['valor1'] = str_replace(",",".",str_replace(".","",$cad['valor1']));
			$cad['valor2']= strip_tags(trim(mysql_real_escape_string($_POST['valor2'])));
			$cad['valor2'] = str_replace(",",".",str_replace(".","",$cad['valor2']));
			$cad['valor3']= strip_tags(trim(mysql_real_escape_string($_POST['valor3'])));
			$cad['valor3'] = str_replace(",",".",str_replace(".","",$cad['valor3']));
			$cad['observacao'] = strip_tags(trim(mysql_real_escape_string($_POST['observacao'])));
			
			$cad['fornecedor1']=strip_tags((($_POST['fornecedor1'])));
			$cad['fornecedor2']=strip_tags((($_POST['fornecedor2'])));
			$cad['fornecedor3']=strip_tags((($_POST['fornecedor3'])));

			update('estoque_compras_material',$cad,"id = '$compraMaterialId'");	
			header('Location: painel.php?execute=suporte/compras/cotacao-editar&cotacaoEditar='.$cotacaoId);
			
		}
	}
		
	if(isset($_POST['cadastrar'])){
		
		$cad['id_compras'] = $compraId;
		$cad['id_material'] = strip_tags(trim(mysql_real_escape_string($_POST['id_material'])));
		$cad['quantidade'] = strip_tags(trim(mysql_real_escape_string($_POST['quantidade'])));
		$cad['unidade'] = strip_tags(trim(mysql_real_escape_string($_POST['unidade'])));
		
		if(in_array('',$cad)){
			print_r($cad);
			echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
		}else{
			$cad['observacao'] = strip_tags(trim(mysql_real_escape_string($_POST['observacao'])));
			$cad['valor1']= strip_tags(trim(mysql_real_escape_string($_POST['valor1'])));
			$cad['valor1'] = str_replace(",",".",str_replace(".","",$cad['valor1']));
			$cad['valor2']= strip_tags(trim(mysql_real_escape_string($_POST['valor2'])));
			$cad['valor2'] = str_replace(",",".",str_replace(".","",$cad['valor2']));
			$cad['valor3']= strip_tags(trim(mysql_real_escape_string($_POST['valor3'])));
			$cad['valor3'] = str_replace(",",".",str_replace(".","",$cad['valor3']));
			create('estoque_compras_material',$cad);
			$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
			header('Location: painel.php?execute=suporte/compras/cotacao-editar&cotacaoEditar='.$cotacaoId);
		}
	}
		
	if(isset($_POST['deletar'])){
		delete('estoque_compras_material',"id = '$compraMaterialId'");
		$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
		header('Location: painel.php?execute=suporte/compras/cotacao-editar&cotacaoEditar='.$cotacaoId);
	}

	?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
		
  		  <div class="form-group col-xs-12 col-md-2">  
               <label>Id </label>
               <input name="id" type="text" value="<?php echo $edit['id'];?>"  readonly class="form-control" />
          </div> 
            
		 <div class="form-group col-xs-12 col-md-4">  
            <label>Material</label>
                <select name="id_material" class="form-control">
                    <option value="">Selecione o Material</option>
                    <?php 
                        $readConta = read('estoque_material',"WHERE id AND id_tipo='$materialId' ORDER BY nome ASC");
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
               <label>Quantidade </label>
               <input name="quantidade" type="text" value="<?php echo $edit['quantidade'];?>"  class="form-control" />
          </div> 
		
		 <div class="form-group col-xs-12 col-md-3">  
               <label>Unidade Medida </label>
               <input name="unidade" type="text" value="<?php echo $edit['unidade'];?>"  class="form-control" />
          </div> 
		
		 <div class="form-group col-xs-12 col-md-12">  
               <label>Observação </label>
               <input name="observacao" type="text" value="<?php echo $edit['observacao'];?>"  class="form-control" />
          </div> 
		
	<div class="form-group col-xs-12 col-md-12">
			
	 	<div class="form-group col-xs-12 col-md-2">
               <input name="fornecedor1" type="checkbox" value="1" <?PHP echo $edit['fornecedor1']; ?>  > Comprar
          </div> 
			 
		 <div class="form-group col-xs-12 col-md-4">  
            <label>Fornecedor 1</label>
                <select name="" class="form-control" disabled>
                    <option value="">Fornecedor</option>
                    <?php 
                        $readConta = read('estoque_fornecedor',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos fornecedor no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($cotacao['fornecedor1'] == $mae['id']){
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
          		<label>Valor Unitário Fornecedor 1</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="valor1" class="form-control pull-right" value="<?php echo converteValor($edit['valor1']);?>"  />
                 </div><!-- /.input group -->
          </div>
		 
		 <div class="form-group col-xs-12 col-md-3">  
          		<label>Total</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text"  class="form-control pull-right" value="<?php echo converteValor($total1);?>" readonly  />
                 </div><!-- /.input group -->
          </div>
		
	  </div>
		
	 <div class="form-group col-xs-12 col-md-12">
		 
		 <div class="form-group col-xs-12 col-md-2">
               <input name="fornecedor2" type="checkbox" value="1" <?PHP echo $edit['fornecedor2']; ?>  >
				Comprar
          </div> 
			 
		
		 <div class="form-group col-xs-12 col-md-4">  
            <label>Fornecedor 2</label>
                <select name="" class="form-control" disabled>
                    <option value="">Fornecedor</option>
                    <?php 
                        $readConta = read('estoque_fornecedor',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos fornecedor no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($cotacao['fornecedor2'] == $mae['id']){
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
          		<label>Valor Unitário Fornecedor 2</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="valor2" class="form-control pull-right" value="<?php echo converteValor($edit['valor2']);?>" />
                 </div><!-- /.input group -->
           </div>
		
		 <div class="form-group col-xs-12 col-md-3">  
          		<label>Total</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text"  class="form-control pull-right" value="<?php echo converteValor($total2);?>" readonly  />
                 </div><!-- /.input group -->
          </div>
		 
	  </div>
		
	 <div class="form-group col-xs-12 col-md-12">
		 
		 <div class="form-group col-xs-12 col-md-2">
               <input name="fornecedor3" type="checkbox" value="1" <?PHP echo $edit['fornecedor3']; ?>  >
				Comprar
          </div> 
		
		 <div class="form-group col-xs-12 col-md-4">  
            <label>Fornecedor 3</label>
                <select name="" class="form-control" disabled>
                    <option value="">Fornecedor</option>
                    <?php 
                        $readConta = read('estoque_fornecedor',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos fornecedor no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($cotacao['fornecedor3'] == $mae['id']){
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
          		<label>Valor Unitário Fornecedor 3</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="valor3" class="form-control pull-right" value="<?php echo converteValor($edit['valor3']);?>"  />
                 </div><!-- /.input group -->
           </div>
		
		 <div class="form-group col-xs-12 col-md-3">  
          		<label>Total</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text"  class="form-control pull-right" value="<?php echo converteValor($total3);?>" readonly  />
                 </div><!-- /.input group -->
          </div>
		
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