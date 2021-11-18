<?php 
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php');		
			}	
		}

		$acao = "cadastrar";
		if(!empty($_GET['patrimonioEditar'])){
			$patrimonioId = $_GET['patrimonioEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['patrimonioDeletar'])){
			$patrimonioId = $_GET['patrimonioDeletar'];
			$acao = "deletar";
		}
		if(!empty($patrimonioId)){
			$readpatrimonio = read('estoque_patrimonio',"WHERE id = '$patrimonioId'");
			if(!$readpatrimonio){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readpatrimonio as $edit);
		}

 ?>
 
<div class="content-wrapper">
	
  <section class="content-header">
	  
          <h1>Patrimônio</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Estoque</a></li>
            <li><a href="painel.php?execute=suporte/estoque/patrimonio-editar">Patrimônio</a></li>
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
		 
			$cad['codigo'] 	= strip_tags(trim(mysql_real_escape_string($_POST['codigo'])));
			$cad['item'] = strip_tags(trim(mysql_real_escape_string($_POST['item'])));
			$cad['descricao'] 	= strip_tags(trim(mysql_real_escape_string($_POST['descricao'])));
			$cad['departamento'] = strip_tags(trim(mysql_real_escape_string($_POST['departamento'])));
			$cad['usuario'] = strip_tags(trim(mysql_real_escape_string($_POST['usuario'])));
			$cad['data'] = strip_tags(trim(mysql_real_escape_string($_POST['data'])));
			$cad['id_conta'] = strip_tags(trim(mysql_real_escape_string($_POST['id_conta'])));
			
			if(in_array('',$cad)){
				
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
				
			  }else{
				
				$cad['valor'] 	= strip_tags(trim(mysql_real_escape_string($_POST['valor'])));
				$cad['valor'] = str_replace(",",".",str_replace(".","",$cad['valor'])); 
				
				update('estoque_patrimonio',$cad,"id = '$patrimonioId'");	
				unset($cad);
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				
				header('Location: painel.php?execute=suporte/estoque/patrimonio');
				
			}
			
		}
			 
		if(isset($_POST['cadastrar'])){
			
			$edit['codigo']	= strip_tags(trim(mysql_real_escape_string($_POST['codigo'])));
			$edit['item'] = strip_tags(trim(mysql_real_escape_string($_POST['item'])));
			$edit['descricao'] 	= strip_tags(trim(mysql_real_escape_string($_POST['descricao'])));
			$edit['departamento'] = strip_tags(trim(mysql_real_escape_string($_POST['departamento'])));
			$edit['usuario'] = strip_tags(trim(mysql_real_escape_string($_POST['usuario'])));
			$edit['data'] = strip_tags(trim(mysql_real_escape_string($_POST['data'])));
			$edit['id_conta'] = strip_tags(trim(mysql_real_escape_string($_POST['id_conta'])));;
			
			if(in_array('',$edit)){
				
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
				
			  }else{
				
			 	$edit['valor'] 	= strip_tags(trim(mysql_real_escape_string($_POST['valor'])));
				$edit['valor'] = str_replace(",",".",str_replace(".","",$edit['valor']));
				unset($edit);
				create('estoque_patrimonio',$edit);	
				
				$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
				
				
				header('Location: painel.php?execute=suporte/estoque/patrimonio');
				
			}
		}
			 
		if(isset($_POST['deletar'])){
			
			delete('estoque_patrimonio',"id = '$patrimonioId'");
			
			$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
			
			header('Location: painel.php?execute=suporte/estoque/patrimonio');
		}

	?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
		
  			<div class="form-group col-xs-12 col-md-2"> 
               <label>Id</label>
           <input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled />
            </div> 
   
      		<div class="form-group col-xs-12 col-md-2"> 
       		   <label>Código</label>
               <input type="text" name="codigo" value="<?php echo $edit['codigo'];?>"  class="form-control"/>
            </div> 
		
		    <div class="form-group col-xs-12 col-md-4"> 
       		   <label>Item</label>
               <input type="text" name="item" value="<?php echo $edit['item'];?>"  class="form-control"/>
            </div> 

            <div class="form-group col-xs-12 col-md-4"> 
       		   <label>Descrição</label>
               <input type="text" name="descricao"  align="right" value="<?php echo $edit['descricao'];?>"  class="form-control"/>
            </div> 
           
            <div class="form-group col-xs-12 col-md-6"> 
       		   <label>Departamento</label>
               <input type="text" name="departamento"  align="right" value="<?php echo $edit['departamento'];?>"  class="form-control"/>
            </div> 
		
			<div class="form-group col-xs-12 col-md-6"> 
       		   <label>Usuário</label>
               <input type="text" name="usuario"  align="right" value="<?php echo $edit['usuario'];?>"  class="form-control"/>
            </div> 
  
           
           <div class="form-group col-xs-12 col-md-3">  
          		<label>Valor </label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="valor" class="form-control pull-right" value="<?php echo converteValor($edit['valor']);?>" <?php echo $readonly;?> />
                 </div><!-- /.input group -->
           </div>
		
			<div class="form-group col-xs-12 col-md-3">  
                 <label>Data da Compra</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
               <input type="date" name="data" class="form-control pull-right" value="<?php echo $edit['data'];?>"/>
                </div><!-- /.input group -->
           </div> 
		
		   <div class="form-group col-xs-12 col-md-6">  
            <label>Centro de Custo</label>
                <select name="id_conta" class="form-control">
                    <option value="">Centro de Custo</option>
                    <?php 
                        $readConta = read('pagar_conta',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos Centro de Custo no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['id_conta'] == $mae['id']){
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
                </form>
        
           </div> 
  	</div><!-- /.box-body -->
    </div><!-- /.box box-default -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->