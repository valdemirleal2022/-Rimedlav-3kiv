<?php 

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'],'1')){
				header('Location: painel.php');		
			}
		}
		
		$acao = "cadastrar";
		if(!empty($_GET['faqEditar'])){
			$faqId = $_GET['faqEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['faqDeletar'])){
			$faqId = $_GET['faqDeletar'];
			$acao = "deletar";
		}
		if(!empty($_GET['faqEnviar'])){
			$faqId = $_GET['faqEnviar'];
			$acao = "enviar";
		}
		if(!empty($_GET['faqNovo'])){
			$faqId = $_GET['faqEnviar'];
			$acao = "enviar";
		}
		if(!empty($faqId)){
			$readfaq = read('faq',"WHERE id = '$faqId'");
			if(!$readfaq){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readfaq as $edit);
		}
?>

<div class="content-wrapper">

  <section class="content-header">
          <h1>Categoria</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Site</a></li>
            <li><a href="painel.php?execute=site/emails">Email</a></li>
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
			$cad['pergunta'] = mysql_real_escape_string($_POST['pergunta']);
			$cad['resposta'] = mysql_real_escape_string($_POST['resposta']);
			if(in_array('',$cad)){
				echo '<div class="msgError">Todos os campos são obrigatórios!</div><br />';
			 }else{
				create('faq',$cad);
				unset($cad);
		 		header('Location: painel.php?execute=site/faqs');
			}
		}
	
		if(isset($_POST['atualizar'])){
			$cad['pergunta'] = mysql_real_escape_string($_POST['pergunta']);
			$cad['resposta'] = mysql_real_escape_string($_POST['resposta']);
			if(in_array('',$cad)){
				print_r($cad);
				echo '<div class="msgError">Todos os campos são obrigatórios!</div><br />';
			 }else{
				update('faq', $cad, "id = '$faqId'");
				unset($cad);
				header('Location: painel.php?execute=site/faqs');
			  }
		}
		
		if(isset($_POST['deletar'])){
				$readDeleta = read('faq',"WHERE id = '$faqId'");
				if(!$readDeleta){
					echo '<div class="msgError">Desculpe, o registro não existe</div><br />';	
				}else{
					delete('faq',"id = '$faqId'");
					header('Location: painel.php?execute=site/faqs');
				}
		}
		
		
	?>
    
	 <form role="form" action="" class="formulario" method="post">
              <div class="form-group">
                <label>Pergunta</label>
                <textarea name="pergunta" cols="100 " rows="5" class="form-control">
                    <?php echo htmlspecialchars($edit['pergunta']);?>
                </textarea>
            </div>
            
             <div class="form-group">
                <label>Resposta :</label>
                <textarea name="resposta" cols="100 " rows="5" class="form-control">
                    <?php echo htmlspecialchars($edit['resposta']);?>
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
	
    </div><!-- /.box box-default -->

  </section><!-- /.content -->
</div><!-- /.content-wrapper -->
 
