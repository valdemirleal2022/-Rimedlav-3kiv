<?php 

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php?execute=suporte/403');	
			}	
		}

		$acao = "cadastrar";
		
		if(!empty($_GET['grupoEditar'])){
			$grupoId = $_GET['grupoEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['grupoDeletar'])){
			$grupoId = $_GET['grupoDeletar'];
			$acao = "deletar";
		}
		if(!empty($grupoId)){
			$readpagar_grupo = read('pagar_grupo',"WHERE id = '$grupoId'");
			if(!$readpagar_grupo){
				header('Location: painel.php?execute=suporte/error');	
			  }else{	
			}
			foreach($readpagar_grupo as $edit);
		}

 ?>

<div class="content-wrapper">
  <section class="content-header">
          <h1>Grupo</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Contas a Pagar</a></li>
            <li><a href="painel.php?execute=suporte/pagar/pagar-grupos">Grupos</a></li>
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
			$cad['codigo'] 	= strip_tags(trim(mysql_real_escape_string($_POST['codigo'])));
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
			  }else{
				update('pagar_grupo',$cad,"id = '$grupoId'");
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';	
				header('Location: painel.php?execute=suporte/pagar/pagar-grupos');
				unset($cad);
			}
		}
		if(isset($_POST['cadastrar'])){
			$cad['nome'] 	= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			$cad['codigo'] 	= strip_tags(trim(mysql_real_escape_string($_POST['codigo'])));
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
			  }else{
				create('pagar_grupo',$cad);	
				$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
				header('Location: painel.php?execute=suporte/pagar/pagar-grupos');
				unset($cad);
			}
		}
		if(isset($_POST['deletar'])){
				$readDeleta = read('pagar_grupo',"WHERE id = '$grupoId'");
				if(!$readDeleta){
						echo '<div class="alert alert-warning">Desculpe, o registro não existe!</div>';	
				}else{
					delete('pagar_grupo',"id = '$grupoId'");
					$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
					header('Location: painel.php?execute=suporte/pagar/pagar-grupos');
				}
		}

	?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
    
    		<div class="form-group col-xs-12 col-md-1"> 
               <label>Id</label>
               <input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled />
            </div>
		
			<div class="form-group col-xs-12 col-md-1"> 
       		   <label>Codigo</label>
               <input type="text" name="codigo" value="<?php echo $edit['codigo'];?>"  class="form-control"/>
           </div> 
   
      		<div class="form-group col-xs-12 col-md-10"> 
       		   <label>Descrição</label>
               <input type="text" name="nome" value="<?php echo $edit['nome'];?>"  class="form-control"/>
           </div> 

       
		<div class="box-footer">
        <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>
        <?php 
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
   
		</div><!-- /.box-body -->
   </div><!-- /.box box-default -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->

 