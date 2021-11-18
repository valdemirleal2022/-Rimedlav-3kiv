<?php 

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php?execute=suporte/403');
			}	
		}
		
		
		$acao = "cadastrar";
		if(!empty($_GET['eventoEditar'])){
			$eventoId = $_GET['eventoEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['eventoDeletar'])){
			$eventoId = $_GET['eventoDeletar'];
			$acao = "deletar";
		}
		if(!empty($_GET['eventoEnviar'])){
			$eventoId = $_GET['eventoEnviar'];
			$acao = "enviar";
		}
		if(!empty($_GET['eventoNovo'])){
			$eventoId = $_GET['eventoEnviar'];
			$acao = "enviar";
		}
		if(!empty($eventoId)){
			$readevento = read('eventos',"WHERE id = '$eventoId'");
			if(!$readevento){
				header('Location: painel.php?execute=suporte/error');	
			  }else{	
			}
			foreach($readevento as $edit);
		}
?>

<div class="content-wrapper">

  <section class="content-header">
          <h1>Eventos</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Site</a></li>
            <li><a href="painel.php?execute=site/Eventos">Eventos</a></li>
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
			$cad['data']    = htmlspecialchars(mysql_real_escape_string($_POST['data']));
			$cad['hora'] 	  = htmlspecialchars(mysql_real_escape_string($_POST['hora']));
			$cad['descricao'] = mysql_real_escape_string($_POST['descricao']);
			$cad['local'] = htmlspecialchars(mysql_real_escape_string($_POST['local']));	
			
			if(in_array('',$cad)){
				
				echo '<div class="msgError">Todos os campos são obrigatórios!</div><br />';
			}else{
				if(!empty($_FILES['fotopost']['tmp_name'])){
					$imagem = $_FILES['fotopost'];
					$pasta = '../uploads/eventos/';
					$tmp = $imagem['tmp_name'];
					$ext = substr($imagem['name'],-3);
					$nome = md5(time()).'.'.$ext;
					$cad['fotopost'] = $nome;
					uploadImg ($tmp, $nome, '600', $pasta);
				}
				create('eventos',$cad);
			    $_SESSION['cadastro'] = '<div class="msgAcerto">Cadastrado com sucesso</div><br />';
				header('Location: painel.php?execute=site/eventos');
				unset($cad);
			  }
		}
	
		if(isset($_POST['atualizar'])){
			$cad['titulo']    = htmlspecialchars(mysql_real_escape_string($_POST['titulo']));
			$cad['data']    = htmlspecialchars(mysql_real_escape_string($_POST['data']));
			$cad['hora'] 	  = htmlspecialchars(mysql_real_escape_string($_POST['hora']));
			$cad['descricao'] = mysql_real_escape_string($_POST['descricao']);
			$cad['local'] = htmlspecialchars(mysql_real_escape_string($_POST['local']));
			if(in_array('',$cad)){
				print_r($cad);
				echo '<div class="msgError">Todos os campos são obrigatórios!</div><br />';
			}else{
				if(!empty($_FILES['fotopost']['tmp_name'])){
					$imagem = $_FILES['fotopost'];
					$pasta = '../uploads/eventos/';
					$tmp = $imagem['tmp_name'];
					$ext = substr($imagem['name'],-3);
					$nome = md5(time()).'.'.$ext;
					$cad['fotopost'] = $nome;
					uploadImg ($tmp, $nome, '600', $pasta);
				}
				update('eventos', $cad, "id = '$eventoId'");
			    $_SESSION['atualiza'] = '<div class="msgAcerto">Atualizado com sucesso</div><br />';
				header('Location: painel.php?execute=site/eventos');
				unset($cad);
				
			  }
		}
		
		if(isset($_POST['deletar'])){
				$readDeleta = read('eventos',"WHERE id = '$eventoId'");
				if(!$readDeleta){
					echo '<div class="msgError">Desculpe, o registro não existe</div><br />';	
				}else{
					delete('eventos',"id = '$eventoId'");
					header('Location: painel.php?execute=site/eventos');
				}
		}
		
		
	?>
    
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
  			
            <div class="form-group">
              <label>Post</label>
            </div>
            
            <div class="form-group">
                <?php 
                    if($edit['fotopost'] != '' && file_exists('../uploads/eventos/'.$edit['fotopost'])){
                        echo '<img class="img-thumbnail imagem-formulario" src="../uploads/eventos/'.$edit['fotopost'].'"/>';
                    }
                ?>
            </div>

            <div class="form-group">
               	<input type="file" name="fotopost" class="btn btn-primary" />	
	        </div>
            <div class="form-group">
                 <label>Título</label>
                  <input name="titulo"  class="form-control" type="text" value="<?php echo $edit['titulo'];?>"/>
            </div>
            <div class="form-group">
                     <label>Local :</label>
                     <input type="text" name="local" value="<?php echo $edit['local'];?>" class="form-control" >
            </div>
            <div class="form-group">
                <label>Data:</label>
               <input type="date" name="data" value="<?php echo $edit['data'];?>"  class="form-control" >
			</div>
            <div class="form-group">
                 <label>Hora :</label>
                 <input type="text" name="hora" value="<?php echo $edit['hora'];?>" class="form-control" >
            </div>
             <div class="form-group">
              <label>Descrição</label>
                <textarea id="editor-texto" name="descricao" rows="8" cols="80">
                    <?php echo htmlspecialchars($edit['descricao']);?>
                </textarea>
         	</div> 
              
			
           <div class="box-footer">
            <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-danger"> </a>
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
   
   
		</div><!-- /.box-body -->

            
        <div class="box-footer">
            
        </div><!-- /.box-footer-->
            
    </div><!-- /.box box-default -->

  </section><!-- /.content -->
</div><!-- /.content-wrapper -->



 
 

