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

		$edit['status'] = "Aguardando";
		$edit['data_solicitacao']=   date("Y-m-d");
		$edit['hora_solicitacao'] = date("H:i");
		$edit['id_desconto'] = '1';
			
		if(!empty($_GET['descontoEditar'])){
			$descontoId = $_GET['descontoEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['descontoVisualizar'])){
			$descontoId = $_GET['descontoVisualizar'];
			$acao = "visualizar";
		}
		if(!empty($_GET['descontoDeletar'])){
			$descontoId = $_GET['descontoDeletar'];
			$acao = "deletar";
		}
		if(!empty($_GET['descontoBaixar'])){
			$descontoId = $_GET['descontoBaixar'];
			$acao = "baixar";
		}

		if(!empty($descontoId)){
			$readdesconto = read('funcionario_desconto',"WHERE id = '$descontoId'");
			if(!$readdesconto){
				header('Location: painel.php?execute=suporte/naoencontrado');	
			}
			foreach($readdesconto as $edit);
			$funcionarioId = $edit['id_funcionario'];
		}

		if(!empty($funcionarioId)){
			$funcionario = mostra('funcionario',"WHERE id = '$funcionarioId'");
			if(!$funcionario){
				header('Location: painel.php?execute=suporte/error');
			}
		}

		if($acao=="baixar"){
			$edit['status'] = "Ok";
			$edit['data_solucao']=  date('Y-m-d');
			$edit['hora_solucao'] = date("H:i");
		 
		}

 ?>

 <div class="content-wrapper">
  <section class="content-header">
          <h1>Desconto</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Funcionário</a></li>
            <li><a href="painel.php?execute=suporte/funcionario/desconto">Desconto</a></li>
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
				
				$edit['solicitacao'] = htmlspecialchars($_POST['solicitacao']);
				$edit['usuario'] = $_SESSION['autUser']['nome'];
				$edit['nota'] = $_POST['nota'];
				$edit['ordem'] = $_POST['nota'];
				$edit['id_desconto'] = mysql_real_escape_string($_POST['id_desconto']);
				$edit['valor'] = mysql_real_escape_string($_POST['valor']);
				$edit['parcelas'] = strip_tags(trim(mysql_real_escape_string($_POST['parcelas'])));
				update('funcionario_desconto',$edit,"id = '$descontoId'");	
				
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				
				header("Location: ".$_SESSION['url']);
			}
		
			if(isset($_POST['baixar'])){
				
				$cad['status']	='OK';
			  
					update('funcionario_desconto',$cad,"id = '$descontoId'");	
					$_SESSION['cadastro'] = '<div class="alert alert-success">Baixado com sucesso</div>';
				    header("Location: ".$_SESSION['url']);
			}
			
			if(isset($_POST['abrir'])){
				$edit['data_solicitacao']	= date('Y-m-d');
			$edit['solicitacao'] = strip_tags(trim(mysql_real_escape_string($_POST['solicitacao'])));
				$edit['nota'] = strip_tags(trim(mysql_real_escape_string($_POST['nota'])));
				$edit['ordem'] = strip_tags(trim(mysql_real_escape_string($_POST['nota'])));
				$edit['usuario'] = $_SESSION['autUser']['nome'];
			$edit['id_desconto']= strip_tags(trim(mysql_real_escape_string($_POST['id_desconto'])));
				$edit['status'] = "Aguardando";
				$edit['valor'] = mysql_real_escape_string($_POST['valor']);
				$edit['id_funcionario']=$funcionarioId;
				if(in_array('',$edit)){
					echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
						  print_r($edit);
				  }else{
					
					$edit['parcelas'] = strip_tags(trim(mysql_real_escape_string($_POST['parcelas'])));
				    create('funcionario_desconto',$edit);
					$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
				    header("Location: ".$_SESSION['url']);
		    	}
			}
			
			
			if(isset($_POST['deletar'])){
					$readDeleta = read('funcionario_desconto',"WHERE id = '$descontoId'");
					if(!$readDeleta){
						echo '<div class="alert alert-warning">Desculpe, o registro não existe!</div><br />';
					}else{
						delete('funcionario_desconto',"id = '$descontoId'");
						$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
						header("Location: ".$_SESSION['url']);
					}
			 }
			 
	 if(isset($_POST['pdf-desconto'])){
			$arquivo = $_FILES['arquivo'];
			$tamanhoPermitido = 1024 * 1024 * 2; // 1Mb
			$diretorio = "../uploads/funcionarios/descontos/";

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
             <h3 class="box-title">desconto</h3>
           </div><!-- /.box-header -->
                
         <div class="box-body">
        <div class="row">
 
      		<div class="form-group col-xs-12 col-md-4">  
                <label>Id</label>
                 <input name="id" class="form-control" type="text" disabled value="<?php echo $edit['id'];?>"/>
            </div>
		 
		   <div class="form-group col-xs-12 col-md-4">  
       		   <label>Status</label>
               <input type="text" name="status" value="<?php echo $edit['status'];?>" class="form-control" disabled />
            </div> 
			
		   <div class="form-group col-xs-12 col-md-4">  
            	<label>Usuário</label>
                <input name="usuario" type="text" value="<?php echo $edit['usuario'];?>" readonly class="form-control" /> 
            </div> 

            <div class="form-group col-xs-12 col-md-4">  
            <label>Motivo do Desconto</label>
                <select name="id_desconto" class="form-control" >
                    <option value="">Selecione uma desconto</option>
                    <?php 
                        $readSuporte = read('funcionario_desconto_motivo',"WHERE id");
                        if(!$readSuporte){
                            echo '<option value="">Não temos Suporte no momento</option>';	
                        }else{
                            foreach($readSuporte as $mae):
							   if($edit['id_desconto'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
      		 </div> 
  
             <div class="form-group col-xs-12 col-md-4">  
                 <label>Data</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
                  <input type="date" name="data_solicitacao" class="form-control" disabled value="<?php echo $edit['data_solicitacao'];?>"/>
                  </div><!-- /.input group -->
           </div> 
			
		 <div class="form-group col-xs-12 col-md-4">  
          		<label>Valor</label>
               <div class="input-group">
                      	<div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="valor" class="form-control pull-right" value="<?php echo converteValor($edit['valor']);?>"  />
                 </div><!-- /.input group -->
           </div>
              
          <div class="form-group col-xs-12 col-md-12"> 
              <label>Descrição</label>
                <textarea name="solicitacao" rows="5" cols="100" class="form-control" /><?php echo $edit['solicitacao'];?></textarea>
         </div>  
			 
		 <div class="form-group col-xs-12 col-md-4"> 
              <label>Nº de O.S de Manutenção </label>
              <input type="text" name="ordem" value="<?php echo $edit['ordem'];?>" class="form-control">
         </div>  
		 
		 <div class="form-group col-xs-12 col-md-4"> 
              <label>Nº da Nota Fiscal</label>
              <input type="text" name="nota" value="<?php echo $edit['nota'];?>" class="form-control">
         </div>  
			 
		 <div class="form-group col-xs-12 col-md-4"> 
              <label>Parcelas</label>
              <input type="text" name="parcelas" value="<?php echo $edit['parcelas'];?>" class="form-control">
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
                            echo '<input type="submit" name="abrir" value="Abrir desconto" class="btn btn-primary" />';
	
                        }
                     ?>  
					
               </div><!-- /.row -->
         </div><!-- /.box-body -->
			 
		</form>
  	</div><!-- /.box-body -->
    </div><!-- /.box box-default -->
  </section><!-- /.content -->

	<section class="content">

	  <div class="col-md-4">   
          <div class="box">
         	<div class="box-header">
				
				  <div class="form-group pull-left">
					<form name="form" action="" method="post" enctype="multipart/form-data">
					   <input type="file" name="arquivo">
					   <br><br>
					  <input type="submit" name="pdf-desconto" value="Upload Comprovante PDF" class="btn btn-primary" />
					</form>
				  </div> 
				
				
				
				  <div class="form-group pull-right">
					<?php
						$pdf='../uploads/funcionarios/descontos/'.$edit['id'].'.pdf';
						if(file_exists($pdf)){
						echo '<a href="../uploads/funcionarios/descontos/'.$edit['id'].'.pdf" target="_blank">
								<img src="ico/pdf.png" title="Comprovante" />
							</a>';	
						}
					?>
				   </div> 

			</div>
		   </div>
		</div>
		
		 
	 </section><!-- /.content -->	


	 		
</div><!-- /.content-wrapper -->


