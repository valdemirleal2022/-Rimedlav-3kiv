<?php 

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php?execute=suporte/403');
			}	
		}
		
		$acao = "cadastrar";
		
		if(!empty($_GET['categoriaEditar'])){
			$categoriaId = $_GET['categoriaEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['categoriaDeletar'])){
			$categoriaId = $_GET['categoriaDeletar'];
			$acao = "deletar";
		}
		if(!empty($categoriaId)){
			$readcategoria = read('categorias',"WHERE id = '$categoriaId'");
			if(!$readcategoria){
				header('Location: painel.php?execute=suporte/naoencontrado');	
			}
			foreach($readcategoria as $edit);
		}
		
		
?> 

<div class="content-wrapper">
  <section class="content-header">
          <h1>Categoria</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Site</a></li>
            <li><a href="painel.php?execute=site/categorias">Categoria</a></li>
             <li class="active">Editar</li>
          </ol>
  </section>
  <section class="content">
      <div class="box box-default">
            <div class="box-header with-border">
               <h1><?php echo $edit['nome']  ;?></h1>	
            </div><!-- /.box-header -->
      <div class="box-body">
		
		<?php 
		
		if(isset($_POST['cadastrar'])){
			$cad['nome'] 	  = htmlspecialchars(mysql_real_escape_string($_POST['nome']));
			$cad['descricao'] = htmlspecialchars(mysql_real_escape_string($_POST['descricao']));
			$cad['tags'] 	  = htmlspecialchars(mysql_real_escape_string($_POST['tags']));
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
			 }else{
				$cad['data']= date('Y/m/d H:i:s');
				$cad['url'] = url($cad['nome']);
				$readCad = read('categorias',"WHERE url LIKE '%$cad[url]%'");
				if($readCad){
					$cad['url']	= $cad['url'].'-'.count($readCad);
					$readCad = read('categorias',"WHERE url = '$cad[url]'");
					if($readCad){
					 	$readCad['url']	= $$readCad['url'].'_'.time();
					}
				}
				create('categorias',$cad);
				unset($cad);
				$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div><br />';
				header('Location: painel.php?execute=site/categorias');
			}
		
		}
		
		if(isset($_POST['atualizar'])){
			$cad['nome'] 	  = htmlspecialchars(mysql_real_escape_string($_POST['nome']));
			$cad['descricao'] = htmlspecialchars(mysql_real_escape_string($_POST['descricao']));
			$cad['tags'] 	  = htmlspecialchars(mysql_real_escape_string($_POST['tags']));
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div><br />';	
			}else{
				$cad['data']= date('Y/m/d H:i:s');
				$cad['url'] = url($cad['nome']);
				update('categorias',$cad,"id = '$categoriaId'");
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div><br />';
				unset($cad);
				header('Location: painel.php?execute=site/categorias');
			}
		}
			if(isset($_POST['deletar'])){
				$readDeleta = read('categorias',"WHERE id = '$categoriaId'");
				if(!$readDeleta){
					echo '<div class="alert alert-warning">Desculpe, o registro não existe</div><br />';	
				}else{
					delete('categorias',"id = '$categoriaId'");
					$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div><br />';
					header('Location: painel.php?execute=site/categorias');
				}
			}
		?>
        
        <form role="form" action="" class="formulario" method="post">
  
            <div class="form-group">
                <label>Nome</label>
                 <input name="nome"  class="form-control" type="text" value="<?php echo $edit['nome'];?>"/>
            </div>
                    
             <div class="form-group">
                <label>Descrição</label>
                <input name="descricao"  class="form-control" type="text" value="<?php echo $edit['descricao'];?>" />
             </div>
                    
             <div class="form-group">
               <label>Tags</label>
                <input name="tags"  class="form-control" type="text" value="<?php echo $edit['tags'];?>" />
              </div>
                     
   			 <div class="form-group">
                 <label>Data:</label>
                  <div class="input-group">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input type="text" name="data" class="form-control pull-right" value="<?php echo date('d/m/Y H:i:s');?>" disabled>
                        </div><!-- /.input group -->
                 </div>   
           </div>
    	<div class="box-footer">
           <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"></a>
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
 