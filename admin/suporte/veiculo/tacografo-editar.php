<?php 
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php');		
			}	
		}
		
		$acao = "cadastrar";
		$edit['data_troca'] = date("Y-m-d");
	
		if(!empty($_GET['tacografoEditar'])){
			$tacografoId = $_GET['tacografoEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['tacografoBaixar'])){
			$tacografoId = $_GET['tacografoBaixar'];
			$acao = "baixar";
		}
		if(!empty($_GET['tacografoDeletar'])){
			$tacografoId = $_GET['tacografoDeletar'];
			$acao = "deletar";
		}
		if(!empty($tacografoId)){
			$readtacografo= read('veiculo_tacografo',"WHERE id = '$tacografoId'");
			if(!$readtacografo){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readtacografo as $edit);
		}
 ?>
 
<div class="content-wrapper">
 
  <section class="content-header">
         
          <h1>Tacógrafos </h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Cadastro</a></li>
            <li><a href="painel.php?execute=suporte/veiculo/bancos">Tacógrafos </a></li>
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
			
			$cad['id_veiculo'] = mysql_real_escape_string($_POST['id_veiculo']);
			$cad['data_troca'] = mysql_real_escape_string($_POST['data_troca']);
				 
			if(in_array('',$cad)){
				
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
				
			  }else{
				
				$vencimento=$cad['data_troca'];
				$cad['data_prevista']= date("Y-m-d", strtotime("$vencimento + 7 days"));
				$cad['status'] = mysql_real_escape_string($_POST['status']);
				$cad['observacao'] = mysql_real_escape_string($_POST['observacao']);
				update('veiculo_tacografo',$cad,"id = '$tacografoId'");	
				
				unset($cad);
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				
				header('Location: painel.php?execute=suporte/veiculo/tacografos');
				
			}
		}
		
			
		if(isset($_POST['cadastrar'])){
			
			$cad['id_veiculo'] 	= strip_tags(trim(mysql_real_escape_string($_POST['id_veiculo'])));
			$cad['data_troca']= strip_tags(trim(mysql_real_escape_string($_POST['data_troca'])));
			$vencimento=$cad['data_troca'];
			$cad['data_prevista']= date("Y-m-d", strtotime("$vencimento + 7 days"));
						
			if(in_array('',$cad)){
				
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
				
			  }else{
				
				
				$cad['status'] 	= strip_tags(trim(mysql_real_escape_string($_POST['status'])));
				$cad['observacao'] = strip_tags(trim(mysql_real_escape_string($_POST['observacao'])));
		
				create('veiculo_tacografo',$cad);
				unset($cad);
				$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
				header('Location: painel.php?execute=suporte/veiculo/tacografos');
				
			}
		}
		
		if(isset($_POST['deletar'])){
			delete('veiculo_tacografo',"id = '$tacografoId'");
			$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
			header('Location: painel.php?execute=suporte/veiculo/tacografos');
		}

	?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
		
  			<div class="form-group col-xs-12 col-md-1"> 
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
		
		  <div class="form-group col-xs-12 col-md-3">  
                 <label>Data da Troca</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
               <input type="date" name="data_troca" class="form-control pull-right" value="<?php echo $edit['data_troca'];?>"/>
                </div><!-- /.input group -->
           </div> 
  
           <div class="form-group col-xs-12 col-md-3">  
                 <label>Data Prevista</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
               <input type="date" name="data_prevista" class="form-control pull-right" value="<?php echo $edit['data_prevista'];?>" disabled />
                </div><!-- /.input group -->
           </div> 
           
          
		
			<div class="form-group col-xs-2">
                <label>Status</label>
                <select name="status" class="form-control" >
                  <option value="">Selecione o Status</option>
                  <option <?php if($edit['status'] == '1') echo' selected="selected"';?> value="1">Realizada</option>
                  <option <?php if($edit['status'] == '0') echo' selected="selected"';?> value="0">-</option>
                 </select>
            </div> 
		
			<div class="form-group col-xs-12 col-md-12"> 
               <label>Observação</label>
               <input name="observacao" type="text" value="<?php echo $edit['observacao'];?>" class="form-control" />
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