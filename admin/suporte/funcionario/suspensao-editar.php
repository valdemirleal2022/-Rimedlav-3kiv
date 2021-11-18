<?php 
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php?execute=suporte/403');	
			}	
		}
		
		$acao = "cadastrar";
		if(!empty($_GET['funcionarioId'])){
			$funcionarioId = $_GET['funcionarioId'];
			$readonly = "readonly";
			$disabled = 'disabled="disabled"';
		}

		$edit['data']=  date("Y-m-d");
		$edit['usuario'] = $_SESSION['autUser']['nome'];

		if(!empty($_GET['suspensaoEditar'])){
			$suspensaoId = $_GET['suspensaoEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['suspensaoVisualizar'])){
			$suspensaoId = $_GET['suspensaoVisualizar'];
			$acao = "visualizar";
		}
		if(!empty($_GET['suspensaoDeletar'])){
			$suspensaoId = $_GET['suspensaoDeletar'];
			$acao = "deletar";
		}
		if(!empty($_GET['suspensaoBaixar'])){
			$suspensaoId = $_GET['suspensaoBaixar'];
			$acao = "baixar";
		}

		

		if(!empty($suspensaoId)){
			$readsuspensao = read('funcionario_suspensao',"WHERE id = '$suspensaoId'");
			if(!$readsuspensao){
				header('Location: painel.php?execute=suporte/naoencontrado');	
			}
			foreach($readsuspensao as $edit);
		}

		
		if(!empty($funcionarioId)){
			$funcionarioId = $_GET['funcionarioId'];
			$funcionario = mostra('funcionario',"WHERE id = '$funcionarioId'");
			if(!$funcionario){
				header('Location: painel.php?execute=suporte/error');
			}
		}

	 
 		
 ?>
 
 <div class="content-wrapper">
  <section class="content-header">
          <h1>Suspensão</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Funcionario</a></li>
            <li><a href="painel.php?execute=suporte/funcionario/suspensao">Suspensão</a></li>
             <li class="active">Editar</li>
          </ol>
  </section>
  
  <section class="content">
      <div class="box box-default">
           
            <div class="box-header with-border">
				    <h3 class="box-title"><?php echo $funcionario['nome'];?></h3>
             	<div class="box-tools">
            		<small>
            		 <?php if($acao=='cadastrar') echo 'Cadastrando';?>
                     <?php if($acao=='deletar') echo 'Deletando';?>
                     <?php if($acao=='atualizar') echo 'Alterando';?>
                     <?php if($acao=='baixar') echo 'Baixando';?>
                    </small>
          		</div><!-- /box-tools-->
      	  </div><!-- /.box-header -->
	
     	 <div class="box-body">

		<?php 
		
			if(isset($_POST['atualizar'])){
				
				$edit['data'] = mysql_real_escape_string($_POST['data']);
				$edit['observacao'] = mysql_real_escape_string($_POST['observacao']);
				$edit['id_motivo'] = mysql_real_escape_string($_POST['id_motivo']);
				$edit['usuario'] = $_SESSION['autUser']['nome'];
				update('funcionario_suspensao',$edit,"id = '$suspensaoId'");	
				
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				
				header("Location: ".$_SESSION['url']);
			}
		
			 
			if(isset($_POST['cadastrar'])){
				
				$edit['data']= date('Y-m-d');
				$edit['observacao'] = mysql_real_escape_string($_POST['observacao']);
				$edit['usuario'] = $_SESSION['autUser']['nome'];
				$edit['id_motivo']= mysql_real_escape_string($_POST['id_motivo']);
				$edit['id_funcionario'] = $funcionarioId;
				if(in_array('',$edit)){
				 
					echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
				  }else{
				    create('funcionario_suspensao',$edit);
					$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
				    header("Location: ".$_SESSION['url']);
		    	}
			}
			
			
			if(isset($_POST['deletar'])){
					$readDeleta = read('funcionario_suspensao',"WHERE id = '$suspensaoId'");
					if(!$readDeleta){
						echo '<div class="alert alert-warning">Desculpe, o registro não existe!</div><br />';
					}else{
						delete('funcionario_suspensao',"id = '$suspensaoId'");
						$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
						header("Location: ".$_SESSION['url']);
					}
			 }
			 
	 if(isset($_POST['pdf-comprovante'])){
			$arquivo = $_FILES['arquivo'];
			$tamanhoPermitido = 1024 * 1024 * 2; // 1Mb
			$diretorio = "../uploads/funcionarios/suspensoes/";

			if( $arquivo['error'] == UPLOAD_ERR_OK ){
				$extensao = extensao($arquivo['name']);
				if( in_array( $extensao, array("pdf") ) ){
					if ( $arquivo['size'] > $tamanhoPermitido ){
							echo '<div class="alert alert-info">O arquivo enviado é muito grande!</div>'.'<br>';	
					}else{
						$novo_nome  = $edit['id'].".".$extensao;
						$enviou = move_uploaded_file($_FILES['arquivo']['tmp_name'], $diretorio.$novo_nome);
						if($enviou){
							echo '<div class="alert alert-info">Upload de arquivo com sucesso!</div>'.'<br>';
						}else{
							echo '<div class="alert alert-info">Falha ao enviar o arquivo!</div>'.'<br>';
						}
					}
				}else{
					echo '<div class="alert alert-info">Somente arquivos PDF são permitidos</div>'.'<br>';
				}

			}else{
				echo '<div class="alert alert-info">Você deve enviar um arquivo!</div>'.'<br>';
			}
		}

		function extensao($arquivo){
			$arquivo = strtolower($arquivo);
			$explode = explode(".", $arquivo);
			$arquivo = end($explode);
			return ($arquivo);
		}
		?>
		
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
    
     <div class="form-group col-xs-12 col-md-12">  
		 
		 <div class="box-header with-border">
             <h3 class="box-title">Suspensão</h3>
           </div><!-- /.box-header -->
                
         <div class="box-body">
        <div class="row">
 
      		<div class="form-group col-xs-12 col-md-2">  
                <label>Id</label>
                 <input name="id" class="form-control" type="text" disabled value="<?php echo $edit['id'];?>"/>
            </div>

		   <div class="form-group col-xs-12 col-md-2">  
            	<label>Usuário</label>
                <input name="usuario" type="text" value="<?php echo $edit['usuario'];?>" readonly class="form-control" /> 
            </div> 

            <div class="form-group col-xs-12 col-md-6">  
            <label>Motivo</label>
                <select name="id_motivo" class="form-control" >
                    <option value="">Selecione uma suspensao</option>
                    <?php 
                        $readSuporte = read('funcionario_suspensao_motivo',"WHERE id");
                        if(!$readSuporte){
                            echo '<option value="">Não temos Suporte no momento</option>';	
                        }else{
                            foreach($readSuporte as $mae):
							   if($edit['id_motivo'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
      		 </div> 
  
             <div class="form-group col-xs-12 col-md-2">  
                 <label>Data</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
                  <input type="date" name="data" class="form-control"  value="<?php echo $edit['data'];?>"/>
                  </div><!-- /.input group -->
           </div> 
              
          <div class="form-group col-xs-12 col-md-12"> 
              <label>Observação</label>
                <textarea name="observacao" rows="5" cols="100" class="form-control" /><?php echo $edit['observacao'];?></textarea>
         </div>  
		
		 </div><!-- /.row -->
       </div><!-- /.box-body -->
          
		
  		 

			 <div class="form-group col-xs-12 col-md-12">  
                <div class="box-footer">
                   <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"></a>
                     <?php 
                        if($acao=="atualizar"){
                            echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';	
                        }
                        if($acao=="deletar"){
                            echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-danger" />';	
                        }
						 if($acao=="baixar"){
                            echo '<input type="submit" name="baixar" value="Baixar"  class="btn btn-success" />';	
                        }
                        if($acao=="cadastrar"){
                            echo '<input type="submit" name="cadastrar" value="Caddastrar" class="btn btn-primary" />';
	
                        }
                     ?>  
					
               </div><!-- /.row -->
         </div><!-- /.box-body -->
			 
		</form>
  	</div><!-- /.box-body -->
    </div><!-- /.box box-default -->
  </section><!-- /.content -->

	<section class="content">

	  <div class="col-md-6">   
          <div class="box">
         	<div class="box-header">
				
				  <div class="form-group pull-left">
					<form name="form" action="" method="post" enctype="multipart/form-data">
					   <input type="file" name="arquivo">
					   <br><br>
					  <input type="submit" name="pdf-comprovante" value="Upload Comprovante PDF" class="btn btn-primary" />
					</form>
				  </div> 
				
					<div class="form-group pull-right">
					<?php
						$pdf='../uploads/funcionarios/suspensoes/'.$edit['id'].'.pdf';

						if(file_exists($pdf)){

							echo '<a href="../uploads/funcionarios/suspensoes/'.$edit['id'].'.pdf" target="_blank">
									<img src="ico/pdf.png" title="Comprovante" />
								</a>';	
						}
					?>
				</div> 
	
			</div>
		 </div>
		</div>
		
</div><!-- /.content-wrapper -->


