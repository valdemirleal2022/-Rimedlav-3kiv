<?php 

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php?execute=suporte/403');
			}	
		}
		
		
		$acao = "cadastrar";
		if(!empty($_GET['manualEditar'])){
			$manualId = $_GET['manualEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['manualDeletar'])){
			$manualId = $_GET['manualDeletar'];
			$acao = "deletar";
		}
		if(!empty($_GET['manualEnviar'])){
			$manualId = $_GET['manualEnviar'];
			$acao = "enviar";
		}
		if(!empty($_GET['manualNovo'])){
			$manualId = $_GET['manualEnviar'];
			$acao = "enviar";
		}
		if(!empty($manualId)){
			$readmanual = read('manual_usuario',"WHERE id = '$manualId'");
			if(!$readmanual){
				header('Location: painel.php?execute=suporte/error');	
			  }else{	
			}
			foreach($readmanual as $edit);
		}
?>

<div class="content-wrapper">

  <section class="content-header">
          <h1>Manual</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Interação</a></li>
            <li><a href="#">Manual</a></li>
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
			
			$cad['pergunta'] = htmlspecialchars(mysql_real_escape_string($_POST['pergunta']));
			$cad['resposta'] = mysql_real_escape_string($_POST['resposta']);
			$cad['ordem'] = htmlspecialchars(mysql_real_escape_string($_POST['ordem']));
			
			if(in_array('',$cad)){
				echo '<div class="msgError">Todos os campos são obrigatórios!</div><br />';
			}else{
				if(!empty($_FILES['fotopost']['tmp_name'])){
					$imagem = $_FILES['fotopost'];
					$pasta = '../uploads/manuais/';
					$tmp = $imagem['tmp_name'];
					$ext = substr($imagem['name'],-3);
					$nome = md5(time()).'.'.$ext;
					$cad['fotopost'] = $nome;
					uploadImg ($tmp, $nome, '600', $pasta);
				}
				create('manual_usuario',$cad);
			    $_SESSION['cadastro'] = '<div class="msgAcerto">Cadastrado com sucesso</div><br />';
				header("Location: ".$_SESSION['url']);
			  }
		}
	
		if(isset($_POST['atualizar'])){
			
			$cad['pergunta'] = htmlspecialchars(mysql_real_escape_string($_POST['pergunta']));
			$cad['resposta'] = mysql_real_escape_string($_POST['resposta']);
			$cad['ordem'] = htmlspecialchars(mysql_real_escape_string($_POST['ordem']));
			if(in_array('',$cad)){
				print_r($cad);
				echo '<div class="msgError">Todos os campos são obrigatórios!</div><br />';
			}else{
				if(!empty($_FILES['fotopost']['tmp_name'])){
					$imagem = $_FILES['fotopost'];
					$pasta = '../uploads/manuais/';
					$tmp = $imagem['tmp_name'];
					$ext = substr($imagem['name'],-3);
					$nome = md5(time()).'.'.$ext;
					$cad['fotopost'] = $nome;
					uploadImg ($tmp, $nome, '600', $pasta);
				}
				update('manual_usuario', $cad, "id = '$manualId'");
			    $_SESSION['atualiza'] = '<div class="msgAcerto">Atualizado com sucesso</div><br />';
				header("Location: ".$_SESSION['url']);
				
			  }
		}
		
		if(isset($_POST['deletar'])){
				$readDeleta = read('manual_usuario',"WHERE id = '$manualId'");
				if(!$readDeleta){
					echo '<div class="msgError">Desculpe, o registro não existe</div><br />';	
				}else{
					delete('manual_usuario',"id = '$manualId'");
					header("Location: ".$_SESSION['url']);
				}
		}
		
		
	?>
    
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
  			
             <div class="form-group col-xs-12 col-md-12"> 
              <label>Imagem</label>
            </div>
            
              <div class="form-group col-xs-12 col-md-12"> 
                <?php 
                    if($edit['fotopost'] != '' && file_exists('../uploads/manuais/'.$edit['fotopost'])){
                        echo '<img class="img-thumbnail imagem-formulario" src="../uploads/manuais/'.$edit['fotopost'].'"/>';
                    }
                ?>
            </div>

             <div class="form-group col-xs-12 col-md-12"> 
               	<input type="file" name="fotopost" class="btn btn-primary" />	
	        </div>
	        
	        <div class="form-group col-xs-12 col-md-2"> 
       		   <label>Ordem no Manual</label>
               <input type="text" name="ordem" value="<?php echo $edit['ordem'];?>"  class="form-control"/>
           </div> 
           
           
            <div class="form-group col-xs-12 col-md-10"> 
       		   <label>Menu</label>
               <input type="text" name="pergunta" value="<?php echo $edit['pergunta'];?>"  class="form-control"/>
           </div> 
            
           <div class="form-group col-xs-12 col-md-12"> 
              <label>Procedimento</label>
                <textarea id="editor-texto" name="resposta" rows="8" cols="80"><?php echo htmlspecialchars($edit['resposta']);?></textarea>
         	</div>  
         
         
         <div class="form-group col-xs-12 col-md-12">    
			
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
        
        	</div> 
  
   </form>
   
   
		</div><!-- /.box-body -->

            
        <div class="box-footer">
            
        </div><!-- /.box-footer-->
            
    </div><!-- /.box box-default -->

  </section><!-- /.content -->
</div><!-- /.content-wrapper -->



 
 

