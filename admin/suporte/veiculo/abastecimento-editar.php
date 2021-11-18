<?php 
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php');		
			}	
		}
		
		$acao = "cadastrar";
		$edit['data'] = date("Y-m-d");
		if(!empty($_GET['abastecimentoEditar'])){
			$abastecimentoId = $_GET['abastecimentoEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['abastecimentoBaixar'])){
			$abastecimentoId = $_GET['abastecimentoBaixar'];
			$acao = "baixar";
		}
		if(!empty($_GET['abastecimentoDeletar'])){
			$abastecimentoId = $_GET['abastecimentoDeletar'];
			$acao = "deletar";
		}
		if(!empty($abastecimentoId)){
			$readabastecimento= read('veiculo_abastecimento',"WHERE id = '$abastecimentoId'");
			if(!$readabastecimento){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readabastecimento as $edit);
		}
 ?>
 
<div class="content-wrapper">
 
  <section class="content-header">
         
          <h1>Abastecimento</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Cadastro</a></li>
            <li><a href="painel.php?execute=suporte/pagar/bancos">Abastecimento</a></li>
             <li class="active">Editar</li>
          </ol>
          
  </section>
  
  <section class="content">
     
      <div class="box box-default">
           
            <div class="box-header with-border">
                <h3 class="box-title"><small><?php echo $edit['nome'];?></small></h3>
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
			
			$cad['id_veiculo'] 	= strip_tags(trim(mysql_real_escape_string($_POST['id_veiculo'])));
			$cad['id_motorista'] 	= strip_tags(trim(mysql_real_escape_string($_POST['id_motorista'])));
			$cad['tipo_combustivel'] = strip_tags(trim(mysql_real_escape_string($_POST['tipo_combustivel'])));
			$cad['data'] 	= strip_tags(trim(mysql_real_escape_string($_POST['data'])));
			$cad['km'] 	= strip_tags(trim(mysql_real_escape_string($_POST['km'])));
			
			$cad['quantidade'] 	= strip_tags(trim(mysql_real_escape_string($_POST['quantidade'])));
			
			$cad['valor'] 	= strip_tags(trim(mysql_real_escape_string($_POST['valor'])));
			$cad['valor'] = str_replace(",",".",str_replace(".","",$cad['valor']));
			
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
			  }else{
				update('veiculo_abastecimento',$cad,"id = '$abastecimentoId'");	
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				header('Location: painel.php?execute=suporte/veiculo/abastecimentos');
				unset($cad);
			}
		}
		
		
		if(isset($_POST['baixar'])){
			 	$cad['status']	= 'Baixado';
				update('veiculo_abastecimento',$cad,"id = '$abastecimentoId'");	
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				header('Location: painel.php?execute=suporte/veiculo/abastecimentos');
				unset($cad);
			
		}
		
		if(isset($_POST['cadastrar'])){
			$cad['id_veiculo'] 	= strip_tags(trim(mysql_real_escape_string($_POST['id_veiculo'])));
			$cad['id_motorista'] 	= strip_tags(trim(mysql_real_escape_string($_POST['id_motorista'])));
			$cad['tipo_combustivel'] = strip_tags(trim(mysql_real_escape_string($_POST['tipo_combustivel'])));
			$cad['data'] 	= strip_tags(trim(mysql_real_escape_string($_POST['data'])));
			$cad['valor'] 	= strip_tags(trim(mysql_real_escape_string($_POST['valor'])));
			$cad['valor'] = str_replace(",",".",str_replace(".","",$cad['valor']));
			$cad['km'] 	= strip_tags(trim(mysql_real_escape_string($_POST['km'])));
			
			$cad['quantidade'] 	= strip_tags(trim(mysql_real_escape_string($_POST['quantidade'])));
			
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
			  }else{
				create('veiculo_abastecimento',$cad);	
				$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
				header('Location: painel.php?execute=suporte/veiculo/abastecimentos');
				unset($cad);
			}
		}
		
		if(isset($_POST['deletar'])){
			delete('veiculo_abastecimento',"id = '$abastecimentoId'");
			$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
			header('Location: painel.php?execute=suporte/veiculo/abastecimentos');
		}

	?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
		
  			<div class="form-group col-xs-12 col-md-2"> 
               <label>Id</label>
               <input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled />
            </div> 
            
           <div class="form-group col-xs-12 col-md-3">  
            <label>Veículo</label>
                <select name="id_veiculo" class="form-control">
                    <option value="">Veículo</option>
                    <?php 
                        $readConta = read('veiculo',"WHERE id ORDER BY placa ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos veiculos no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['id_veiculo'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['placa'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['placa'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
             </div> 
             
             
          <div class="form-group col-xs-12 col-md-4">  
            <label>Motorista</label>
                <select name="id_motorista" class="form-control">
                    <option value="">Motorista</option>
                    <?php 
                        $readConta = read('veiculo_motorista',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos motorista no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['id_motorista'] == $mae['id']){
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
            <label>Combustível</label>
                <select name="tipo_combustivel" class="form-control">
                    <option value="">Combustível</option>
                    <?php 
                        $readConta = read('veiculo_tipo_combustivel',"WHERE id ORDER BY id ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos motorista no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['tipo_combustivel'] == $mae['id']){
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
                 <label>Data</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
               <input type="date" name="data" class="form-control pull-right" value="<?php echo $edit['data'];?>"/>
                </div><!-- /.input group -->
           </div> 
           
           <div class="form-group col-xs-12 col-md-3">  
          		<label>Quantidade</label>
               <div class="input-group">
                   <div class="input-group-addon">
                        <i class="fa fa-credit-calendar"></i>
                    </div>
                     <input type="text" name="quantidade" class="form-control pull-right"  value="<?php echo $edit['quantidade'];?>"/>
                 </div><!-- /.input group -->
           </div>
           
           
            <div class="form-group col-xs-12 col-md-3">  
          		<label>Valor</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="valor" class="form-control pull-right"  value="<?php echo converteValor($edit['valor']);?>"/>
                 </div><!-- /.input group -->
           </div>
           
 		   <div class="form-group col-xs-12 col-md-3">  
          		<label>Km</label>
               <div class="input-group">
                   <div class="input-group-addon">
                        <i class="fa fa-credit-calendar"></i>
                    </div>
                     <input type="text" name="km" class="form-control pull-right"  value="<?php echo $edit['km'];?>"/>
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