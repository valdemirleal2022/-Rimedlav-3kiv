<?php 

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php?execute=suporte/403');	
			}	
		}
		echo '<head>';
		echo '<meta charset="iso-8859-1">';
		echo '</head>';
		
		$acao = "cadastrar";
		if(!empty($_GET['paginaEditar'])){
			$paginaId = $_GET['paginaEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['paginaDeletar'])){
			$paginaId = $_GET['paginaDeletar'];
			$acao = "deletar";
		}
		if(!empty($_GET['paginaEnviar'])){
			$paginaId = $_GET['paginaEnviar'];
			$acao = "enviar";
		}
		if(!empty($paginaId)){
			$readpagina = read('paginas',"WHERE id = '$paginaId'");
			if(!$readpagina){
				header('Location: painel.php?execute=suporte/naoencontrado');
			}
			foreach($readpagina as $edit);
		}
?>

<div class="content-wrapper">
  <section class="content-header">
          <h1>Paginas</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Site</a></li>
            <li><a href="painel.php?execute=site/paginas">Paginas</a></li>
             <li class="active">Editar</li>
          </ol>
  </section>
  <section class="content">
      <div class="box box-default">
            <div class="box-header with-border">
               <h1><?php echo $edit['titulo']  ;?></h1>	
            </div><!-- /.box-header -->
      <div class="box-body">

	<?php 
	
	if(isset($_POST['cadastrar'])){
			$cad['nome']      = htmlspecialchars(mysql_real_escape_string($_POST['nome']));	
			$cad['tags']      = htmlspecialchars(mysql_real_escape_string($_POST['tags']));	
			$cad['conteudo']  = mysql_real_escape_string($_POST['conteudo']);
						
			if(in_array('',$cad)){
				echo '<div class="msgError">Todos os campos são obrigatórios!</div><br />';
			}else{
				$cad['data'] = date('Y/m/d H:i:s');
				$cad['url']  = url($cad['nome']);
				create('paginas',$cad);
			    $_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				header('Location: painel.php?execute=site/paginas');
				unset($cad);
				}
		 }
		
		if(isset($_POST['atualizar'])){
			$cad['nome']      = htmlspecialchars(mysql_real_escape_string($_POST['nome']));	
			$cad['tags']      = htmlspecialchars(mysql_real_escape_string($_POST['tags']));	
			$cad['conteudo']  = mysql_real_escape_string($_POST['conteudo']);
			if(in_array('',$cad)){
				echo '<div class="msgError">Todos os campos são obrigatórios!</div><br />';
			}else{
				$cad['data'] = date('Y/m/d H:i:s');
				$cad['url']  = url($cad['nome']);
				$readCadUrl = read('paginas',"WHERE url LIKE '%$cad[url]%'");
				update('paginas', $cad, "id = '$paginaId'");
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				header('Location: painel.php?execute=site/paginas');
				unset($cad);
			  }
		}
		  
		if(isset($_POST['deletar'])){
				$readDeleta = read('paginas',"WHERE id = '$paginaId'");
				if(!$readDeleta){
					echo '<div class="alert alert-warning">Desculpe, o registro não existe!</div><br />';		
				}else{
					delete('paginas',"id = '$paginaId'");
					$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
					header('Location: painel.php?execute=site/paginas');
				}
		}
		
		
	?>
    
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">

    	<div class="form-group">
       	  <label>PAginas</label>
       	</div>
        

        <div class="form-group">
             <label>Nome</label>
              <input name="nome"  class="form-control" type="text" value="<?php echo $edit['nome'];?>"/>
        </div>
        
        
        <div class="form-group">
             <label>Tags</label>
              <input name="tags" class="form-control" type="text" value="<?php echo $edit['tags'];?>"/>
        </div>           
               
         <div class="form-group">
              <label>Descrição</label>
                <textarea id="editor-texto" name="conteudo" rows="15" cols="80">
                    <?php echo htmlspecialchars($edit['conteudo']);?>
                </textarea>
         </div>  
          
        <div class="row">
        
        	 <div class="form-group col-xs-3">
             
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

            
        <div class="box-footer">
            
        </div><!-- /.box-footer-->
            
    </div><!-- /.box box-default -->

  </section><!-- /.content -->
</div><!-- /.content-wrapper -->



 
 
