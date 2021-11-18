<?php 

		if ( function_exists( ProtUser ) ) {
			if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
				header( 'Location: painel.php?execute=suporte/403' );
			}
		}

		$acao = "cadastrar";
		if(!empty($_GET['materialTipoEditar'])){
			$materialTipoId = $_GET['materialTipoEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['materialTipoDeletar'])){
			$materialTipoId = $_GET['materialTipoDeletar'];
			$acao = "deletar";
		}
		if(!empty($materialTipoId)){
			$readmaterialTipo = read('estoque_material_tipo',"WHERE id = '$materialTipoId'");
			if(!$readmaterialTipo){
				header('Location: painel.php?execute=materialTipo/error');	
			}
			foreach($readmaterialTipo as $edit);
		}
		
 ?>

<div class="content-wrapper">
  <section class="content-header">
          <h1>Material Tipo</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Cadastro</a></li>
            <li><a href="painel.php?execute=suporte/estoqoue/material-tipos">Material Tipo</a></li>
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
				update('estoque_material_tipo',$cad,"id = '$materialTipoId'");	
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				header('Location: painel.php?execute=suporte/estoque/material-tipos');
				unset($cad);
			}
		}
		if(isset($_POST['cadastrar'])){
			$cad['nome'] 	= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			$cad['codigo'] 	= strip_tags(trim(mysql_real_escape_string($_POST['codigo'])));
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
			  }else{
				create('estoque_material_tipo',$cad);	
				$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
				header('Location: painel.php?execute=suporte/estoque/material-tipos');
				unset($cad);
			}
		}
		if(isset($_POST['deletar'])){
			delete('estoque_material_tipo',"id = '$materialTipoId'");
			$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
			header('Location: painel.php?execute=suporte/estoque/material-tipos');
		}

	?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
		
  		  <div class="form-group col-xs-12 col-md-2"> 
               <label>Id</label>
               <input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled />
            </div> 
		
			<div class="form-group col-xs-12 col-md-2"> 
       		   <label>codigo</label>
               <input type="text" name="codigo" value="<?php echo $edit['codigo'];?>"  class="form-control"/>
            </div> 
   
      		<div class="form-group col-xs-12 col-md-8"> 
       		   <label>Nome</label>
               <input type="text" name="nome" value="<?php echo $edit['nome'];?>"  class="form-control"/>
           </div> 
		
	      
           <div class="form-group col-xs-12 col-md-12">          
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
		  </div>  
   </form>
   
		</div><!-- /.box-body -->
   </div><!-- /.box box-default -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->
