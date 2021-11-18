<?php 

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php');		
			}	
		}
		
	
		if(!empty($_GET['retiradaDeletar'])){
			$retiradaId = $_GET['retiradaDeletar'];
			$acao = "deletar";
		}

		if(!empty($_GET['retiradaBaixar'])){
			$estoqueMateriaId = $_GET['retiradaBaixar'];
			$acao = "baixar";
		}

		if(!empty($retiradaId)){
			$readretirada= read('estoque_material_retirada',"WHERE id = '$retiradaId'");
			if(!$readretirada){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readretirada as $cad);
		}

		if(!empty($estoqueMateriaId)){
			$readMaterial= mostra('estoque_material',"WHERE id = '$estoqueMateriaId'");
			if(!$readMaterial){
				header('Location: painel.php?execute=suporte/error');	
			}
			
			$cad['id_material']=$readMaterial['id'];
			$cad['id_tipo']=$readMaterial['id_tipo'];
			$cad['data']= date( "Y/m/d" );
			 
		}


 ?>
 
<div class="content-wrapper">
 
  <section class="content-header">
          <h1>Retirada</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">material</a></li>
            <li><a href="#">Retirada</a></li>
             <li class="active">Editar</li>
          </ol>
  </section>
  
  <section class="content">
     
     <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><small><?php echo $readMaterial['nome'];?></small></h3>
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
            		 
            	</div><!-- /col-xs-10 col-md-5 pull-right-->
   			 </div><!-- /row-->  
           </div><!-- /box-header-->   
      	  
    <div class="box-body">
      
	<?php 
			 
	if(isset($_POST['baixar'])){

		$cad['quantidade'] = strip_tags(trim(mysql_real_escape_string($_POST['quantidade'])));
		$cad['data']=strip_tags(trim(mysql_real_escape_string($_POST['data'])));
		$cad['id_material']=$cad['id_material'];
		$cad['id_tipo'] =$cad['id_tipo'];
		
		if(in_array('',$cad)){
			
			echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
			
		}else{
			
			$cad['observacao'] = strip_tags(trim(mysql_real_escape_string($_POST['observacao'])));
			create('estoque_material_retirada',$cad);
			
			//RETIRAA DO ESTOQUE;
			$materialId=$cad['id_material'];
			$estoque= mostra('estoque_material',"WHERE id = '$materialId'");

			$est['estoque'] = $estoque['estoque'] - $cad['quantidade'];
			update('estoque_material',$est,"id = '$materialId'");	
			
			$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
			header('Location: painel.php?execute=suporte/estoque/material-retirada');
			unset($cad);
		}
		
	}


	if(isset($_POST['deletar'])){
		
		$retidadaId= $cad['id'];
		delete('estoque_material_retirada',"id = '$retidadaId'");
		
		//DEVOLVE AO ESTOQUE;
		$materialId=$cad['id_material'];
		$estoque= mostra('estoque_material',"WHERE id = '$materialId'");
		$est['estoque'] = $estoque['estoque'] + $cad['quantidade'];
		update('estoque_material',$est,"id = '$materialId'");	
		
		$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
		header('Location: painel.php?execute=suporte/estoque/material-retirada');
	}

	?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
    
  		<div class="form-group col-xs-12 col-md-2"> 
             <label>Id</label>
            <input name="id" type="text" value="<?php echo $cad['id'];?>" class="form-control" disabled />
        </div> 

         <div class="form-group col-xs-12 col-md-4">  
            <label>Material</label>
                <select name="id_material" class="form-control" disabled>
                    <option value="">Material</option>
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
               			<input type="date" name="data" class="form-control pull-right" value="<?php echo $cad['data'];?>"/>
              </div><!-- /.input group -->
        </div> 
 
		<div class="form-group col-xs-12 col-md-12"> 
             <label>Observação</label>
            <input name="observacao" type="text" value="<?php echo $cad['observacao'];?>" class="form-control"  />
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