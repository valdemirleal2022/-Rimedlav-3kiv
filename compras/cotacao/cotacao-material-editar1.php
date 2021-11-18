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
		
	 	$cad['valor1']= strip_tags(trim(mysql_real_escape_string($_POST['valor1'])));
		$cad['valor1'] = str_replace(",",".",str_replace(".","",$cad['valor1']));
			
		if(in_array('',$cad)){
			
			echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
			
		 }else{
			
			update('estoque_compras_material',$cad,"id = '$compraMaterialId'");	
			$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
			header("Location: ".$_SESSION['url']);
	   }
	}
		
	 

	?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
		
  		  <div class="form-group col-xs-12 col-md-1">  
               <label>Id </label>
               <input name="id" type="text" value="<?php echo $edit['id'];?>"  readonly class="form-control" />
          </div> 
            
		 <div class="form-group col-xs-12 col-md-4">  
            <label>Material</label>
                <select name="id_material" class="form-control"  readonly>
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
		
		 <div class="form-group col-xs-12 col-md-1">  
               <label>Quantidade </label>
               <input name="quantidade" type="text" value="<?php echo $edit['quantidade'];?>"  class="form-control"  readonly/>
          </div> 
		
		 <div class="form-group col-xs-12 col-md-2">  
               <label>Unidade Medida </label>
               <input name="unidade" type="text" value="<?php echo $edit['unidade'];?>"  class="form-control"  readonly />
          </div> 
	
	 
		  <div class="form-group col-xs-12 col-md-2">  
          		<label>Valor Unitário</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="valor1" class="form-control pull-right" value="<?php echo converteValor($edit['valor1']);?>"  />
                 </div><!-- /.input group -->
          </div>
		 
		 <div class="form-group col-xs-12 col-md-2">  
          		<label>Total</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text"  class="form-control pull-right" value="<?php echo converteValor($total1);?>" readonly  />
                 </div><!-- /.input group -->
          </div>
		
	 	 <div class="form-group col-xs-12 col-md-12">  
             
		 <div class="box-footer">
			 <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>
		<?php 
					 
		echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';	
				 
		 ?>  
		 </div> 
          
          </div>
		</form>
  	</div><!-- /.box-body -->
    </div><!-- /.box box-default -->
  </section><!-- /.content -->
	
	
	
</div><!-- /.content-wrapper -->