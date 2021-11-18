<?php 
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php');		
			}	
		}
		
		$acao = "cadastrar";
		if(!empty($_GET['motoristaEditar'])){
			$motoristaId = $_GET['motoristaEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['motoristaBaixar'])){
			$motoristaId = $_GET['motoristaBaixar'];
			$acao = "baixar";
		}
		if(!empty($_GET['motoristaDeletar'])){
			$motoristaId = $_GET['motoristaDeletar'];
			$acao = "deletar";
		}
		if(!empty($motoristaId)){
			$readmotorista= read('veiculo_motorista',"WHERE id = '$motoristaId'");
			if(!$readmotorista){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readmotorista as $edit);
		}


		if ($edit['parcial'] == "1") {
			$edit['parcial'] = "checked='CHECKED'";
		} else {
			$edit['parcial'] = "";
		}
 ?>
 
<div class="content-wrapper">
  <section class="content-header">
          <h1>Motorista</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Veiculo</a></li>
            <li><a href="painel.php?execute=suporte/pagar/bancos">Motorista</a></li>
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
			$cad['nome'] 	= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));

		if(in_array('',$cad)){
			
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
			
		}else{
			
			$cad['habilitacao'] = strip_tags(trim(mysql_real_escape_string($_POST['habilitacao'])));
			$cad['vencimento'] 	= strip_tags(trim(mysql_real_escape_string($_POST['vencimento'])));
			$cad['tipo'] = strip_tags(trim(mysql_real_escape_string($_POST['tipo'])));
			$cad['parcial'] = strip_tags(trim(mysql_real_escape_string($_POST['parcial'])));
			$cad['status'] = strip_tags(trim(mysql_real_escape_string($_POST['status'])));
		
			update('veiculo_motorista',$cad,"id = '$motoristaId'");	
			$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
			header('Location: painel.php?execute=suporte/veiculo/motoristas');
			unset($cad);
		  }
		}
		
		
	if(isset($_POST['cadastrar'])){
		$cad['nome'] 	= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
		if(in_array('',$cad)){
			echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
		}else{
				
			$cad['habilitacao'] = strip_tags(trim(mysql_real_escape_string($_POST['habilitacao'])));
			$cad['vencimento'] 	= strip_tags(trim(mysql_real_escape_string($_POST['vencimento'])));
			$cad['tipo'] = strip_tags(trim(mysql_real_escape_string($_POST['tipo'])));
			$cad['parcial'] = strip_tags(trim(mysql_real_escape_string($_POST['parcial'])));
			$cad['status'] = strip_tags(trim(mysql_real_escape_string($_POST['status'])));
		
			create('veiculo_motorista',$cad);	
			$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
			header('Location: painel.php?execute=suporte/veiculo/motoristas');
			unset($cad);
		}
	}
		
	if(isset($_POST['deletar'])){
		delete('veiculo_motorista',"id = '$motoristaId'");
		$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
		header('Location: painel.php?execute=suporte/veiculo/motoristas');
	}

	?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
		
  			<div class="form-group col-xs-12 col-md-1"> 
               <label>Id</label>
               <input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled />
            </div> 
            
           <div class="form-group col-xs-12 col-md-6"> 
       		   <label>Nome</label>
               <input type="text" name="nome" value="<?php echo $edit['nome'];?>"  class="form-control"/>
           </div> 
		
			
            <div class="form-group col-xs-12 col-md-3"> 
                <label>Tipo</label>
                <select name="tipo" class="form-control" >
                  <option value="">Tipo</option>
                  <option <?php if($edit['tipo'] == '1') echo' selected="selected"';?> value="1">Motorista</option>
                  <option <?php if($edit['tipo'] == '2') echo' selected="selected"';?> value="2">coletor</option>
                 </select>
            </div>  
		
			  <!-- parcial-->
               <div class="form-group col-xs-12 col-md-2">
                   <input name="parcial" type="checkbox" id="documentos_0" value="1" <?PHP echo $edit['parcial']; ?>  class="minimal"  <?php echo $disabled;?> >
                Parcial
              </div> 
 
            <div class="form-group col-xs-12 col-md-3"> 
       		   <label>Habilitação</label>
               <input type="text" name="habilitacao" value="<?php echo $edit['habilitacao'];?>"  class="form-control"/>
           </div> 
            
           <div class="form-group col-xs-12 col-md-3">  
                 <label>Vencimento</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
               <input type="date" name="vencimento" class="form-control pull-right" value="<?php echo $edit['vencimento'];?>"/>
                </div><!-- /.input group -->
           </div> 
		
		 <div class="form-group col-xs-12 col-md-6">   
             <label>Selecione Status</label>
                  <select name="status" class="form-control" />   
                    <option value="">Selecione um Status</option>
                        <?php 
                            $readclassificacao = read('funcionario_status',"id ORDER BY id ASC");
                            if(!$readclassificacao){
                                echo '<option value="">Não temos status no momento</option>';	
                            }else{
                                foreach($readclassificacao as $mae):
                                   if($edit['status'] == $mae['id']){
                                        echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                     }else{
                                        echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                    }
                                endforeach;	
                            }
                        ?> 
                  </select>
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