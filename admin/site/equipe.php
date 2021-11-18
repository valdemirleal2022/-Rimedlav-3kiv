<?php 

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php?execute=suporte/403');
			}	
		}
		
		$acao = "cadastrar";
		if(!empty($_GET['professorEditar'])){
			$professorId = $_GET['professorEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['professorDeletar'])){
			$professorId = $_GET['professorDeletar'];
			$acao = "deletar";
		}
		if(!empty($_GET['professorEnviar'])){
			$professorId = $_GET['professorEnviar'];
			$acao = "enviar";
		}
		if(!empty($_GET['professorNovo'])){
			$professorId = $_GET['professorEnviar'];
			$acao = "enviar";
		}
		if(!empty($professorId)){
			$readprofessor = read('professor',"WHERE id = '$professorId'");
			if(!$readprofessor){
				header('Location: painel.php?execute=suporte/error');	
			  }else{	
			}
			foreach($readprofessor as $edit);
		}
?>

<div class="content">

	<?php 
		if(isset($_POST['cadastrar'])){
			$cad['email']    = htmlspecialchars(mysql_real_escape_string($_POST['email']));
			$cad['nome'] 	  = htmlspecialchars(mysql_real_escape_string($_POST['nome']));
			$cad['descricao'] = mysql_real_escape_string($_POST['descricao']);
			$cad['status'] = 1;
			if(in_array('',$cad)){
				echo '<div class="msgError">Todos os campos são obrigatórios!</div><br />';
			 }else{
				if(!empty($_FILES['foto']['tmp_name'])){
					$imagem = $_FILES['foto'];
					$pasta = '../uploads/professores/';
					$tmp = $imagem['tmp_name'];
					$ext = substr($imagem['name'],-3);
					$nome = md5(time()).'.'.$ext;
					$cad['foto'] = $nome;
					uploadImg ($tmp, $nome, '150', $pasta);
				}
				create('professor',$cad);
				unset($cad);
				header('Location: painel.php?execute=site/professores');
			}
		}
	
		if(isset($_POST['atualizar'])){
			$cad['email']    = htmlspecialchars(mysql_real_escape_string($_POST['email']));
			$cad['nome'] 	  = htmlspecialchars(mysql_real_escape_string($_POST['nome']));
			$cad['descricao'] = mysql_real_escape_string($_POST['descricao']);
			$cad['status'] = htmlspecialchars(mysql_real_escape_string($_POST['status']));
			if(in_array('',$cad)){
				print_r($cad);
				echo '<div class="msgError">Todos os campos são obrigatórios!</div><br />';
			}else{
				if(!empty($_FILES['foto']['tmp_name'])){
					$imagem = $_FILES['foto'];
					$pasta = '../uploads/professores/';
					$tmp = $imagem['tmp_name'];
					$ext = substr($imagem['name'],-3);
					$nome = md5(time()).'.'.$ext;
					$cad['foto'] = $nome;
					uploadImg ($tmp, $nome, '150', $pasta);
				}
				update('professor', $cad, "id = '$professorId'");
				unset($cad);
				header('Location: painel.php?execute=site/professores');
			  }
		}
		
		if(isset($_POST['deletar'])){
				$readDeleta = read('professor',"WHERE id = '$professorId'");
				if(!$readDeleta){
					echo '<div class="msgError">Desculpe, o registro não existe</div><br />';	
				}else{
					delete('professor',"id = '$professorId'");
					header('Location: painel.php?execute=site/professores');
				}
		}
		
		
	?>
    
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
  			
            <h1>Professor : <?php echo $edit['nome'];?></h1>

      		<h2>Foto :</h2>
	  		<label class="perfil">
			<?php 
				if($edit['foto'] != '' && file_exists('../uploads/professores/'.$edit['foto'])){
				echo '<a href="../uploads/professores/'.$edit['foto'].'"
					  title="'.$edit['titulo'].'" rel="Shadowbox">';
				echo '<img src="../config/tim.php?src=../uploads/professores/'.$edit['foto'].'&w=50&h=50&zc=1&q=100"
					 title="Ver" alt="Foto do professor" />';
				echo '</a>';}
			?>
            </label>
            
      
            <label>
               <input type="file" name="foto" size="10" />      
             </label>
                   
            <label>
                <span>Nome :</span>
                <input name="nome" type="text" value="<?php echo $edit['nome'];?>" size="90" />
            </label>
            
            <label>
                <span>Email :</span>
                <input name="email" type="email" value="<?php echo $edit['email'];?>" size="90" />
            </label>
            
            
            <label>
                <span>Status:</span>
                <select name="status">
                  <option value="">Selecione o Status</option>
                  <option <?php if($edit['status'] == '1') echo' selected="selected"';?> value="1">Ativo</option>
                  <option <?php if($edit['status'] == '0') echo' selected="selected"';?> value="0">Inativo</option>
                 </select>
            </label>
            
            <label>
                <span>Mensagem :</span>
                <textarea name="descricao" cols="100 " rows="20" class="editor" >
                    <?php echo htmlspecialchars($edit['descricao']);?>
                </textarea>
            </label>
            
            

              
			
            <a href="javascript:window.history.go(-1)"> <input type="button" value="Voltar"></a>
               <?php 
				if($acao=="atualizar"){
					echo '<input type="submit" name="atualizar" value="Atualizar" class="enviar" />';	
				}
				if($acao=="deletar"){
					echo '<input type="submit" name="deletar" value="Deletar"  class="enviar" />';	
				}
				if($acao=="cadastrar"){
					echo '<input type="submit" name="cadastrar" value="Cadastrar" class="enviar" />';	
				}

			?>

   </form>
</div><!--/content-->
 
