<?php 

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php?execute=suporte/403');
			}	
		}
		$acao = "cadastrar";
		if(!empty($_GET['noticiaEditar'])){
			$noticiaId = $_GET['noticiaEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['noticiaDeletar'])){
			$noticiaId = $_GET['noticiaDeletar'];
			$acao = "deletar";
		}
		if(!empty($_GET['noticiaEnviar'])){
			$noticiaId = $_GET['noticiaEnviar'];
			$acao = "enviar";
		}
		if(!empty($noticiaId)){
			$readnoticia = read('noticias',"WHERE id = '$noticiaId'");
			if(!$readnoticia){
				header('Location: painel.php?execute=suporte/naoencontrado');
			}
			foreach($readnoticia as $edit);
		}
?>

<div class="content-wrapper">
  <section class="content-header">
          <h1>Notícias</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Site</a></li>
            <li><a href="painel.php?execute=site/noticias">Notícias</a></li>
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
			$cad['titulo']    = htmlspecialchars(mysql_real_escape_string($_POST['titulo']));
			$cad['pre']    = htmlspecialchars(mysql_real_escape_string($_POST['pre']));
			$cad['tags'] 	  = htmlspecialchars(mysql_real_escape_string($_POST['tags']));
			$cad['descricao'] = mysql_real_escape_string($_POST['descricao']);
			$cad['categoria'] = htmlspecialchars(mysql_real_escape_string($_POST['categoria']));	
			$cad['status'] = htmlspecialchars(mysql_real_escape_string($_POST['status']));	
			$cad['destaques'] = htmlspecialchars(mysql_real_escape_string($_POST['destaques']));	
			$cad['autor']     = $_SESSION['autUser']['id'];
		
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div><br />';	
			}else{
				
				$cad['data'] = date('Y/m/d H:i:s');
				$cad['url']  = url($cad['titulo']);
				$cad['video_titulo'] =$_POST['video_titulo'];
				$cad['video_id'] =$_POST['video_id'];
				$readCadUrl = read('noticias',"WHERE url LIKE '%$cad[url]%'");
				if(!empty($_FILES['fotopost']['tmp_name'])){
					$imagem = $_FILES['fotopost'];
					$pasta = '../uploads/noticias/';
					$tmp = $imagem['tmp_name'];
					$ext = substr($imagem['name'],-3);
					$nome = md5(time()).'.'.$ext;
					$cad['fotopost'] = $nome;
					uploadImg ($tmp, $nome, '1200', $pasta);
				}
				create('noticias',$cad);
				unset($cad);
				$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div><br />';
				header('Location: painel.php?execute=site/noticias');
			}
		}
		
		if(isset($_POST['atualizar'])){
			$cad['titulo']    = htmlspecialchars(mysql_real_escape_string($_POST['titulo']));
			$cad['pre']    = htmlspecialchars(mysql_real_escape_string($_POST['pre']));
			$cad['tags'] 	  = htmlspecialchars(mysql_real_escape_string($_POST['tags']));
			$cad['descricao'] = mysql_real_escape_string($_POST['descricao']);
			$cad['categoria'] = htmlspecialchars(mysql_real_escape_string($_POST['categoria']));	
			$cad['status'] = htmlspecialchars(mysql_real_escape_string($_POST['status']));	
			$cad['destaques'] = htmlspecialchars(mysql_real_escape_string($_POST['destaques']));		
			$cad['autor']     = $_SESSION['autUser']['id'];
			
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
				$edit['descricao']=htmlspecialchars($_POST['descricao']);	
			}else{
				$cad['data'] = date('Y/m/d H:i:s');
				$cad['url']  = url($cad['titulo']);
			 	$cad['video_titulo'] =$_POST['video_titulo'];
				$cad['video_id'] =$_POST['video_id'];
				$readCadUrl = read('noticias',"WHERE url LIKE '%$cad[url]%'");
				if(!empty($_FILES['fotopost']['tmp_name'])){
					$imagem = $_FILES['fotopost'];
					$pasta = '../uploads/noticias/';
					$tmp = $imagem['tmp_name'];
					$ext = substr($imagem['name'],-3);
					$nome = md5(time()).'.'.$ext;
					$cad['fotopost'] = $nome;
					uploadImg ($tmp, $nome, '1200', $pasta);
				}
				update('noticias', $cad, "id = '$noticiaId'");
				unset($cad);
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				header('Location: painel.php?execute=site/noticias');
			  }
		}
		if(isset($_POST['excluir'])){
				$readDeleta = read('noticias',"WHERE id = '$noticiaId'");
				if(!$readDeleta){
					echo '<div class="alert alert-warning">Desculpe, o registro não existe!</div><br />';		
				}else{
					delete('noticias',"id = '$noticiaId'");
					$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
					header('Location: painel.php?execute=site/noticias');
				}
		}
		
		if(isset($_POST['enviar'])){
				
			 $readCliente = read('cliente',"id");
			 if(!$readCliente){
			   header('Location: painel.php?execute=suporte/error');
			 }
			 
			 
			 foreach($readCliente as $cliente):
				$link = URL.'/single/'.$edit['url'];
			 
				$assunto = $edit['titulo'];
				// email	
				$msg = "<font size='2px' face='Verdana, Geneva, sans-serif' color='#009' >";
				$msg .="<img src='http://www.wpcsistema.com.br/site/images/header-logo.png'> <br /><br />";
				$msg .= stripslashes($edit['titulo']).  "<br />";
				$msg .= stripslashes($edit['pre']).  "<br />";
				$msg .= stripslashes($edit['descricao']).  "<br /><br />";
				$msg .= "<a href=" . $link . ">".$link."</a>";
				$msg .=  "</font>";
				$cliente['email']="wellington@wpcsistema.com.br";
				$cliente['nome']="wellington";
				enviaEmail($assunto,$msg,MAILUSER,SITENOME,$cliente['email'],$cliente['nome']);
			    break;
			endforeach;
			
			header('Location: painel.php?execute=site/noticias');
		}
	?>
    
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">

    	<div class="form-group">
       	  <label>Post</label>
       	</div>
        
        <div class="form-group">
          	<?php 
				if($edit['fotopost'] != '' && file_exists('../uploads/noticias/'.$edit['fotopost'])){
					echo '<img class="img-thumbnail imagem-formulario" src="../uploads/noticias/'.$edit['fotopost'].'"/>';
				}
			?>
       	</div>
        
      
        <div class="form-group">
            <span class="btn btn-primary btn-file">
                Upload<input type="file" name="fotopost" class="form-control"/>
            </span>						
        </div>
      
  
              
        <div class="form-group">
             <label>Título</label>
              <input name="titulo"  class="form-control" type="text" value="<?php echo $edit['titulo'];?>"/>
        </div>
        
        <div class="form-group">
             <label>Pré Descrição</label>
              <input name="pre" class="form-control" type="text" value="<?php echo $edit['pre'];?>"/>
        </div>
        
        <div class="form-group">
             <label>Tags</label>
              <input name="tags" class="form-control" type="text" value="<?php echo $edit['tags'];?>"/>
        </div>           
               
         <div class="form-group">
              <label>Descrição</label>
                <textarea id="editor-texto" name="descricao" rows="8" cols="80"><?php echo htmlspecialchars($edit['descricao']);?></textarea>
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
            <label>Selecione a categoria:</label>
                <select name="categoria" class="form-control" >
                    <option value="">Selecione uma categoria</option>
                    <?php 
                        $readCategorias = read('categorias',"WHERE id");
                        if(!$readCategorias){
                            echo '<option value="">Não temos Categorias no momento</option>';	
                        }else{
                            foreach($readCategorias as $mae):
							   if($edit['categoria'] == $mae['nome']){
									echo '<option value="'.$mae['nome'].'"selected="selected">'.$mae['nome'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['nome'].'">'.$mae['nome'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
              </div>   
			
              <div class="form-group col-xs-3">
                <label>Status:</label>
                <select name="status" class="form-control" >
                  <option value="">Selecione o Status</option>
                  <option <?php if($edit['status'] == '1') echo' selected="selected"';?> value="1">Ativo</option>
                  <option <?php if($edit['status'] == '0') echo' selected="selected"';?> value="0">Inativo</option>
                 </select>
            </div>  
			
            <div class="form-group col-xs-3">
                <label>Prioridade:</label>
                <select name="destaques" class="form-control" >
                  <option value="">Selecione a Prioridade</option>
                  <option <?php if($edit['destaques'] == '1') echo' selected="selected"';?> value="1">Destaques</option>
                  <option <?php if($edit['destaques'] == '0') echo' selected="selected"';?> value="0">Normal</option>
                 </select>
            </label>
       		</div>
            </div>
          	 
             <div class="row">
                <div class="box-header">
                      <h3 class="box-title"><i class="fa fa-youtube-play"></i> Vídeo</h3>
                </div><!-- /.box-header -->
            
             <div class="col-xs-6">
                <label>Título : </label>
                <input type="text" name="video_titulo" value="<?php echo $edit['video_titulo'];?>" class="form-control" >
            </div>   
              <div class="col-xs-6">
                <label>Url :</label>
                <input type="text" name="video_id" value="<?php echo $edit['video_id'];?>" class="form-control" >
              </label>    
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



 
 
