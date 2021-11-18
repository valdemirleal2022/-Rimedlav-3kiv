<?php 
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php');		
			}	
		}

		$acao = "cadastrar";
		if(!empty($_GET['veiculoEditar'])){
			$veiculoId = $_GET['veiculoEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['veiculoDeletar'])){
			$veiculoId = $_GET['veiculoDeletar'];
			$acao = "deletar";
		}
		if(!empty($veiculoId)){
			$readveiculo = read('veiculo',"WHERE id = '$veiculoId'");
			if(!$readveiculo){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readveiculo as $edit);
		}

 ?>
 
<div class="content-wrapper">
  <section class="content-header">
          <h1>Veiculos</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Cadastro</a></li>
            <li><a href="painel.php?execute=suporte/pagar/bancos">Veiculos</a></li>
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
			
			$cad['modelo'] = strip_tags(trim(mysql_real_escape_string($_POST['modelo'])));
			$cad['placa'] = strip_tags(trim(mysql_real_escape_string($_POST['placa'])));
			$cad['ano'] = strip_tags(trim(mysql_real_escape_string($_POST['ano'])));
			
			$cad['status'] = strip_tags(trim(mysql_real_escape_string($_POST['status'])));
			
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos com * são obrigatórios!</div>';	
			  }else{
				$cad['motorista'] = strip_tags(trim(mysql_real_escape_string($_POST['motorista'])));
				$cad['observacao'] = strip_tags(trim(mysql_real_escape_string($_POST['observacao'])));
			
				update('veiculo',$cad,"id = '$veiculoId'");	
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				header('Location: painel.php?execute=suporte/veiculo/veiculos');
				unset($cad);
			}
		}
			 
		if(isset($_POST['cadastrar'])){
			
			$cad['modelo'] 	= strip_tags(trim(mysql_real_escape_string($_POST['modelo'])));
			$cad['placa'] 	= strip_tags(trim(mysql_real_escape_string($_POST['placa'])));
			$cad['ano'] 	= strip_tags(trim(mysql_real_escape_string($_POST['ano'])));
			$cad['status']   = strip_tags(trim(mysql_real_escape_string($_POST['status'])));
		
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
			  }else{
				$cad['motorista'] = strip_tags(trim(mysql_real_escape_string($_POST['motorista'])));
				$cad['observacao'] = strip_tags(trim(mysql_real_escape_string($_POST['observacao'])));
				create('veiculo',$cad);	
				$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
				header('Location: painel.php?execute=suporte/veiculo/veiculos');
				unset($cad);
			}
		}
			 
		if(isset($_POST['deletar'])){
			delete('veiculo',"id = '$veiculoId'");
			$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
			header('Location: painel.php?execute=suporte/veiculo/veiculos');
		}

	?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
		
  			<div class="form-group col-xs-12 col-md-1"> 
               <label>Id</label>
               <input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled />
            </div> 
   
      		<div class="form-group col-xs-12 col-md-3"> 
       		   <label>Modelo</label>
               <input type="text" name="modelo" value="<?php echo $edit['modelo'];?>"  class="form-control"/>
           </div> 
           
           <div class="form-group col-xs-12 col-md-3"> 
       		   <label>Placa</label>
               <input type="text" name="placa" value="<?php echo $edit['placa'];?>"  class="form-control"/>
           </div> 
           
             <div class="form-group col-xs-12 col-md-2"> 
       		   <label>Ano</label>
               <input type="text" name="ano" value="<?php echo $edit['ano'];?>"  class="form-control"/>
           </div> 
		
			<div class="form-group col-xs-12 col-md-3"> 
			  <label>Status </label>
				<select name="status" class="form-control">
				  <option value="">Selecione o status &nbsp;&nbsp;</option>

				  <option <?php if($edit['status'] && $edit['status'] == '1') echo' selected="selected"';?> value="1"> Ativo &nbsp;&nbsp;</option>

				  <option <?php if($edit['status'] && $edit['status'] == '0') echo' selected="selected"';?> value="0">Inativo &nbsp;&nbsp;</option>
				 </select>
			</div>
           

            <div class="form-group col-xs-12 col-md-3">  
            <label>Motorista</label>
                <select name="motorista" class="form-control">
                    <option value="">Motorista</option>
                    <?php 
                        $readConta = read('veiculo_motorista',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos motorista no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['motorista'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
         	</div> 
	
		
		   <div class="form-group col-xs-12 col-md-9"> 
       		   <label>Observação</label>
               <input type="text" name="observacao" value="<?php echo $edit['observacao'];?>"  class="form-control"/>
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
                </form>
        
           </div> 
  	</div><!-- /.box-body -->
    </div><!-- /.box box-default -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->