<?php 

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php?execute=suporte/403');
			}	
		}
		
		
		$acao = "cadastrar";
		if(!empty($_GET['galeriaEditar'])){
			$galeriaId = $_GET['galeriaEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['galeriaDeletar'])){
			$galeriaId = $_GET['galeriaDeletar'];
			$acao = "deletar";
		}
		if(!empty($_GET['galeriaEnviar'])){
			$galeriaId = $_GET['galeriaEnviar'];
			$acao = "enviar";
		}
		if(!empty($_GET['galeriaNovo'])){
			$galeriaId = $_GET['galeriaEnviar'];
			$acao = "enviar";
		}
		if(!empty($galeriaId)){
			$readgaleria = read('galeria',"WHERE id = '$galeriaId'");
			if(!$readgaleria){
				header('Location: painel.php?execute=suporte/error');	
			  }else{	
			}
			foreach($readgaleria as $edit);
		}
?>

<div class="content-wrapper">

  <section class="content-header">
          <h1>Galeria</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Site</a></li>
            <li><a href="painel.php?execute=site/emails">Galeria</a></li>
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
			$cad['descricao'] = mysql_real_escape_string($_POST['descricao']);
			$cad['data']= date('Y/m/d H:i:s');
			if(in_array('',$cad)){
				echo '<div class="msgError">Todos os campos são obrigatórios!</div><br />';
			 }else{
				if(!empty($_FILES['foto']['tmp_name'])){
					$imagem = $_FILES['foto'];
					$pasta = '../uploads/galeria/';
					$tmp = $imagem['tmp_name'];
					$ext = substr($imagem['name'],-3);
					$nome = md5(time()).'.'.$ext;
					$cad['foto'] = $nome;
					uploadImg ($tmp, $nome, '800', $pasta);
				}
				create('galeria',$cad);
				unset($cad);
				header('Location: painel.php?execute=site/galeria');
			}
		}
	
		if(isset($_POST['atualizar'])){
			$cad['descricao'] = mysql_real_escape_string($_POST['descricao']);
			$cad['data']= date('Y/m/d H:i:s');
			if(in_array('',$cad)){
				print_r($cad);
				echo '<div class="msgError">Todos os campos são obrigatórios!</div><br />';
			}else{
				if(!empty($_FILES['foto']['tmp_name'])){
					$imagem = $_FILES['foto'];
					$pasta = '../uploads/galeria/';
					$tmp = $imagem['tmp_name'];
					$ext = substr($imagem['name'],-3);
					$nome = md5(time()).'.'.$ext;
					$cad['foto'] = $nome;
					uploadImg ($tmp, $nome, '800', $pasta);
				}
				update('galeria', $cad, "id = '$galeriaId'");
				unset($cad);
				header('Location: painel.php?execute=site/galeria');
			  }
		}
		
		if(isset($_POST['deletar'])){
				$readDeleta = read('galeria',"WHERE id = '$galeriaId'");
				if(!$readDeleta){
					echo '<div class="msgError">Desculpe, o registro não existe</div><br />';	
				}else{
					delete('galerias',"id = '$galeriaId'");
					header('Location: painel.php?execute=site/galeria');
				}
		}
		
		
	?>
    
	
   	
   		<form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">

             <div class="form-group">
              <label>Post</label>
            </div>
            
            <div class="form-group">
                <?php 
                    if($edit['foto'] != '' && file_exists('../uploads/galeria/'.$edit['foto'])){
                        echo '<img class="img-thumbnail imagem-formulario" src="../uploads/galeria/'.$edit['foto'].'"/>';
                    }
                ?>
            </div>

            <div class="form-group">
               	<input type="file" name="foto" class="btn btn-primary" />	
	        </div>
          
                  
            <div class="form-group">
                 <label>Descrição</label>
                  <input name="descricao"  class="form-control" type="text" value="<?php echo $edit['descricao'];?>"/>
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
	
    </div><!-- /.box box-default -->

  </section><!-- /.content -->
</div><!-- /.content-wrapper -->
 
