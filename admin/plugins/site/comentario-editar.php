<?php 

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'],'1')){
				header('Location: painel.php');		
			}
		}
		
		$acao = "cadastrar";
		if(!empty($_GET['comentarioEditar'])){
			$comentarioId = $_GET['comentarioEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['comentarioDeletar'])){
			$comentarioId = $_GET['comentarioDeletar'];
			$acao = "deletar";
		}
		if(!empty($_GET['comentarioEnviar'])){
			$comentarioId = $_GET['comentarioEnviar'];
			$acao = "enviar";
		}
		if(!empty($_GET['comentarioNovo'])){
			$comentarioId = $_GET['comentarioEnviar'];
			$acao = "enviar";
		}
		if(!empty($comentarioId)){
			$readcomentario = read('comentarios',"WHERE id = '$comentarioId'");
			if(!$readcomentario){
				header('Location: painel.php?execute=suporte/naoencontrado');	
			}
			foreach($readcomentario as $edit);
		}
?>

<div class="content-wrapper">

  <section class="content-header">
          <h1>Comentários</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Site</a></li>
            <li><a href="painel.php?execute=site/comentarios">Comentários</a></li>
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
			$cad['data']    = htmlspecialchars(mysql_real_escape_string($_POST['data']));
			$cad['nome'] 	  = htmlspecialchars(mysql_real_escape_string($_POST['nome']));
			$cad['empresa'] = mysql_real_escape_string($_POST['empresa']);
			$cad['mensagem'] = mysql_real_escape_string($_POST['mensagem']);
			if(in_array('',$cad)){
				echo '<div class="msgError">Todos os campos são obrigatórios!</div><br />';
			 }else{
				if(!empty($_FILES['foto']['tmp_name'])){
					$imagem = $_FILES['foto'];
					$pasta = '../uploads/comentarios/';
					$tmp = $imagem['tmp_name'];
					$ext = substr($imagem['name'],-3);
					$nome = md5(time()).'.'.$ext;
					$cad['foto'] = $nome;
					uploadImg ($tmp, $nome, '100', $pasta);
				}
				$cad['status'] = 1;
				$cad['data']= date('Y/m/d H:i:s');
				create('comentarios',$cad);
				unset($cad);
				$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div><br />';
				header('Location: painel.php?execute=site/comentarios');
			}
		}
	
		if(isset($_POST['atualizar'])){
			$cad['nome'] 	  = htmlspecialchars(mysql_real_escape_string($_POST['nome']));
			$cad['empresa'] = mysql_real_escape_string($_POST['empresa']);
			$cad['mensagem'] = mysql_real_escape_string($_POST['mensagem']);
			$cad['status'] = htmlspecialchars(mysql_real_escape_string($_POST['status']));
			$cad['data']= date('Y/m/d H:i:s');
			if(in_array('',$cad)){
				print_r($cad);
				echo '<div class="msgError">Todos os campos são obrigatórios!</div><br />';
			}else{
				if(!empty($_FILES['foto']['tmp_name'])){
					$imagem = $_FILES['foto'];
					$pasta = '../uploads/comentarios/';
					$tmp = $imagem['tmp_name'];
					$ext = substr($imagem['name'],-3);
					$nome = md5(time()).'.'.$ext;
					$cad['foto'] = $nome;
					uploadImg ($tmp, $nome, '100', $pasta);
				}
				update('comentarios', $cad, "id = '$comentarioId'");
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div><br />';
				unset($cad);
				header('Location: painel.php?execute=site/comentarios');
			  }
		}
		
		if(isset($_POST['deletar'])){
				$readDeleta = read('comentarios',"WHERE id = '$comentarioId'");
				if(!$readDeleta){
					echo '<div class="msgError">Desculpe, o registro não existe</div><br />';	
				}else{
					delete('comentarios',"id = '$comentarioId'");
					$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div><br />';
					header('Location: painel.php?execute=site/comentarios');
				}
		}

	?>

     <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">

    	<div class="form-group">
       	  <label>Foto</label>
       	</div>
        
        <div class="form-group">
          	<?php 
				if($edit['foto'] != '' && file_exists('../uploads/comentarios/'.$edit['foto'])){
					echo '<img src="../uploads/comentarios/'.$edit['foto'].'"/>';
				}
			?>
       	</div>
        
      
        <div class="form-group">
            <span class="btn btn-primary btn-file">
                Upload<input type="file" name="foto" class="form-control"/>
            </span>						
        </div>
              
        <div class="form-group">
                <label>Nome</label>
                <input name="nome" type="text"  class="form-control" value="<?php echo $edit['nome'];?>"/>
        </div>
            
        <div class="form-group">
                <label>Empresa</label>
                <input name="empresa" type="text"  class="form-control" value="<?php echo $edit['empresa'];?>"/>
            </label>
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
            
            <div class="form-group col-xs-3">
                <label>Status</label>
                <select name="status" class="form-control" >
                  <option value="">Selecione o Status</option>
                  <option <?php if($edit['status'] == '1') echo' selected="selected"';?> value="1">Ativo</option>
                  <option <?php if($edit['status'] == '0') echo' selected="selected"';?> value="0">Inativo</option>
                 </select>
            </div>   
         </div>   
         
       	 <div class="form-group">  
            <label>
                <label>Mensagem</label>
                <textarea name="mensagem" cols="100 " rows="5" class="form-control" >
                    <?php echo htmlspecialchars($edit['mensagem']);?>
                </textarea>
            </label>
          </div>     

          <div class="box-footer">
           <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-danger"></a>
			<?php 
                if($acao=="atualizar"){
                    echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';	
                }
                if($acao=="deletar"){
                    echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-primary" />';	
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
 
    </div><!-- /.box box-default -->

  </section><!-- /.content -->
</div><!-- /.content-wrapper -->	
 
