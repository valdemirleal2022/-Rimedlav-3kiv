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
		$edit['id_divergencia'] = '1';
			
		if(!empty($_GET['divergenciaEditar'])){
			$divergenciaId = $_GET['divergenciaEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['divergenciaVisualizar'])){
			$divergenciaId = $_GET['divergenciaVisualizar'];
			$acao = "visualizar";
		}
		if(!empty($_GET['divergenciaDeletar'])){
			$divergenciaId = $_GET['divergenciaDeletar'];
			$acao = "deletar";
		}
		if(!empty($_GET['divergenciaBaixar'])){
			$divergenciaId = $_GET['divergenciaBaixar'];
			$acao = "baixar";
		}

		if(!empty($divergenciaId)){
			$readdivergencia = read('funcionario_divergencia',"WHERE id = '$divergenciaId'");
			if(!$readdivergencia){
				header('Location: painel.php?execute=suporte/naoencontrado');	
			}
			foreach($readdivergencia as $edit);
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
          <h1>Divergencia</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Funcionario</a></li>
            <li><a href="painel.php?execute=suporte/funcionario/divergencia">Divergencia</a></li>
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
				$edit['id_divergencia'] = mysql_real_escape_string($_POST['id_divergencia']);
				$cad['solucao'] 	= htmlspecialchars($_POST['solucao']);
				
				update('funcionario_divergencia',$edit,"id = '$divergenciaId'");	
				
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				
				header("Location: ".$_SESSION['url']);
			}
		
			if(isset($_POST['baixar'])){
				
				$edit['status']	='OK';
				$edit['data_solucao'] = date('Y-m-d');
				$edit['solucao'] = htmlspecialchars($_POST['solucao']);
				$edit['procedente'] = htmlspecialchars($_POST['procedente']);
				$edit['usuario']	=  $_SESSION['autUser']['nome'];
				 
				if(in_array('',$edit)){
					echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
				  }else{
					update('funcionario_divergencia',$edit,"id = '$divergenciaId'");	
					$_SESSION['cadastro'] = '<div class="alert alert-success">Baixado com sucesso</div>';
				    header("Location: ".$_SESSION['url']);
				 }
			}
			
			if(isset($_POST['abrir'])){
				$edit['data_solicitacao']	= date('Y-m-d');
				$edit['solicitacao'] = $_POST['solicitacao'];
				$edit['usuario']  			= $_SESSION['autUser']['nome'];
				$edit['id_divergencia']= strip_tags(trim(mysql_real_escape_string($_POST['id_divergencia'])));
				$edit['status'] = "Aguardando";
				$edit['id_funcionario']=$funcionarioId;
				if(in_array('',$edit)){
					echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
						  print_r($edit);
				  }else{
				    create('funcionario_divergencia',$edit);
					$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
				    header("Location: ".$_SESSION['url']);
		    	}
			}
			
			
			if(isset($_POST['deletar'])){
					$readDeleta = read('funcionario_divergencia',"WHERE id = '$divergenciaId'");
					if(!$readDeleta){
						echo '<div class="alert alert-warning">Desculpe, o registro não existe!</div><br />';
					}else{
						delete('funcionario_divergencia',"id = '$divergenciaId'");
						$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
						header("Location: ".$_SESSION['url']);
					}
			 }
			 
	 if(isset($_POST['pdf-divergencia1'])){
			$arquivo = $_FILES['arquivo'];
			$tamanhoPermitido = 1024 * 1024 * 2; // 1Mb
			$diretorio = "../uploads/funcionarios/divergencia1/";

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
			 
		if(isset($_POST['pdf-divergencia2'])){
			$arquivo = $_FILES['arquivo'];
			$tamanhoPermitido = 1024 * 1024 * 1; // 1Mb
			$diretorio = "../uploads/funcionarios/divergencia2/";

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
			 
	  if(isset($_POST['pdf-divergencia3'])){
			$arquivo = $_FILES['arquivo'];
			$tamanhoPermitido = 1024 * 1024 * 1; // 1Mb
			$diretorio = "../uploads/funcionarios/divergencia3/";

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
			
		if(isset($_POST['enviar-divergencia'])){
 
			$link1 = URL."/uploads/funcionarios/divergencia1/".$edit['id'].'.pdf'; 
			$link2 = URL."/uploads/funcionarios/divergencia2/".$edit['id'].'.pdf'; 
			$link3 = URL."/uploads/funcionarios/divergencia3/".$edit['id'].'.pdf'; 	
			$assunto = "Divergencia - boleto " .$funcionario['nome'];
			$msg = "<font size='2 px' face='Verdana, Geneva, sans-serif' color='#009c89'>";
			$msg .="<img src='http://www.cleanambiental.com.br/wpc/site/images/header-logo.png'><br/><br/><br/>";
			$msg .= "Funcionario : " . $funcionario['nome'] . "<br />";
			
			 
				$msg .= "<a href=" . $link1 . ">Gerar Boleto 1</a> <br />";
			 
				$msg .= "<a href=" . $link2 . ">Gerar Boleto 2</a> <br />";
			 
				$msg .= "<a href=" . $link3 . ">Gerar Boleto 3</a> <br />";
			 
			$msg .=  "</font>";

			 $financeiro='financeiro@cleanambiental.com.br';
			 enviaEmail($assunto,$msg,MAILUSER,SITENOME,$financeiro,SITENOME);
			
			//$financeiro='patricia@cleanambiental.com.br';
			//enviaEmail($assunto,$msg,MAILUSER,SITENOME,$financeiro,SITENOME);
			
			//$financeiro='financeiro@toyamadedetizadora.com.br';
			//enviaEmail($assunto,$msg,MAILUSER,SITENOME,$financeiro,SITENOME);
			
		    //echo "Host => ". MAILHOST ."<br>";
		    //echo "Email => ". MAILUSER ."<br>";
		    //echo "Senha => ". MAILPASS ."<br>";
		    //echo "Porta => ". MAILPORT ."<br>";
			
			 
		    //$mailReturn = enviaEmail($assunto,$msg,MAILUSER,SITENOME,$financeiro,SITENOME);
		    //echo "O mail foi enviado? => "; var_dump($mailReturn);
			
			 header("Location: ".$_SESSION['url']);
		}
			 
		 
			 
		?>
 
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
    
     <div class="form-group col-xs-12 col-md-12">  
		 
		 <div class="box-header with-border">
             <h3 class="box-title">Divergencia</h3>
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

            <div class="form-group col-xs-12 col-md-6">  
            <label>Divergencia</label>
                <select name="id_divergencia" class="form-control" >
                    <option value="">Selecione uma Divergencia</option>
                    <?php 
                        $readSuporte = read('funcionario_divergencia_motivo',"WHERE id");
                        if(!$readSuporte){
                            echo '<option value="">Não temos Suporte no momento</option>';	
                        }else{
                            foreach($readSuporte as $mae):
							   if($edit['id_divergencia'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
      		 </div> 
  
             <div class="form-group col-xs-12 col-md-6">  
                 <label>Data</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
                  <input type="date" name="data_solicitacao" class="form-control" disabled value="<?php echo $edit['data_solicitacao'];?>"/>
                  </div><!-- /.input group -->
           </div> 
              
          <div class="form-group col-xs-12 col-md-12"> 
              <label>Solicitação</label>
                <textarea name="solicitacao" rows="5" cols="100" class="form-control" /><?php echo $edit['solicitacao'];?></textarea>
         </div>  
		
		 </div><!-- /.row -->
       </div><!-- /.box-body -->
          
		
  		<div class="box-header with-border">
                  <h3 class="box-title">Fechamento</h3>
        </div><!-- /.box-header -->
                
         <div class="box-body">
        <div class="row">
            
             <div class="form-group col-xs-12 col-md-3">  
                 <label>Data</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
                  <input type="date" name="data_solucao" class="form-control pull-right" disabled value="<?php echo $edit['data_solucao'];?>"    />
                  </div><!-- /.input group -->
           </div> 
		 

            
             <div class="form-group col-xs-12 col-md-12"> 
              <label>Solução</label>
                <textarea  name="solucao" rows="5" cols="100" class="form-control"  <?php echo $readonly;?>/><?php echo $edit['solucao'];?></textarea>
        	 </div>  
			 
		  </div><!-- /.row -->
       </div><!-- /.box-body -->
			 
			  <div class="form-group col-xs-3">
                <label>Procedente/Improcedente</label>
					<select name="procedente" class="form-control"  <?php echo $readonly;?>>
					  <option value="">Selecione a opção</option>
						
					  <option <?php if($edit['procedente'] == '1') echo' selected="selected"';?> value="1">Procedente</option>
						
					  <option <?php if($edit['procedente'] == '0') echo' selected="selected"';?> value="0">Improcedente</option>
						
					 <option <?php if($edit['procedente'] == '2') echo' selected="selected"';?> value="2">Pagamento Extra</option>
					 </select>
              </label>
       		</div>
          

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
                            echo '<input type="submit" name="abrir" value="Abrir Divergencia" class="btn btn-primary" />';
	
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
					  <input type="submit" name="pdf-divergencia1" value="Upload Comprovante PDF" class="btn btn-primary" />
					</form>
				  </div> 
				
				
				
				  <div class="form-group pull-right">
					<?php
						$pdf='../uploads/funcionarios/divergencia1/'.$edit['id'].'.pdf';
						if(file_exists($pdf)){
						echo '<a href="../uploads/funcionarios/divergencia1/'.$edit['id'].'.pdf" target="_blank">
								<img src="ico/pdf.png" title="Comprovante" />
							</a>';	
						}
					?>
				   </div> 

			</div>
		   </div>
		</div>
		
		 <div class="col-md-4">   
          <div class="box">
         	<div class="box-header">
				
				  <div class="form-group pull-left">
					<form name="form" action="" method="post" enctype="multipart/form-data">
					   <input type="file" name="arquivo">
					   <br><br>
					  <input type="submit" name="pdf-divergencia2" value="Upload Comprovante PDF" class="btn btn-primary" />
					</form>
				  </div> 
				
					<div class="form-group pull-right">
					  <?php
						$pdf='../uploads/funcionarios/divergencia2/'.$edit['id'].'.pdf';
						if(file_exists($pdf)){
							echo '<a href="../uploads/funcionarios/divergencia2/'.$edit['id'].'.pdf" target="_blank">
									<img src="ico/pdf.png" title="Comprovante" />
								</a>';	
						}
					   ?>
					 </div> 
				
			 
	
			</div>
		   </div>
		</div>
		
		
		 <div class="col-md-4">   
          <div class="box">
         	<div class="box-header">
				
				  <div class="form-group pull-left">
					<form name="form" action="" method="post" enctype="multipart/form-data">
					   <input type="file" name="arquivo">
					   <br><br>
					  <input type="submit" name="pdf-divergencia3" value="Upload Comprovante PDF" class="btn btn-primary" />
					</form>
				  </div> 
				
					<div class="form-group pull-right">
					<?php
						$pdf='../uploads/funcionarios/divergencia3/'.$edit['id'].'.pdf';

						if(file_exists($pdf)){

							echo '<a href="../uploads/funcionarios/divergencia3/'.$edit['id'].'.pdf" target="_blank">
									<img src="ico/pdf.png" title="Comprovante" />
								</a>';	
						}
					?>
				     </div> 
				
				
	
			</div>
		   </div>
		</div>
		 
	 <div class="col-md-4">   
          <div class="box">
         	<div class="box-header">
     			
				  <br><br><br><br>
				
				  <div class="form-group pull-left">
	  				 <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
						 <input type="submit" name="enviar-divergencia" value="Enviar Email com Boleto Anexados" class="btn btn-success" /> 
					  </form>
  				  </div> 
		 
		</div>
		   </div>
		</div>
	 </section><!-- /.content -->	


	 		
</div><!-- /.content-wrapper -->


