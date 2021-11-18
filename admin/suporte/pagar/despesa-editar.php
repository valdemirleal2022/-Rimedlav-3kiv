<?php 

	if ( function_exists( 'ProtUser' ) ) {
		if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
			header( 'Location: painel.php?execute=suporte/403' );
		}
	}

		echo '<script type="text/javascript">';
			echo 'function excluir() {';
				echo 'if ( confirm("Confirma Exclusão de Registro?") ) {';
					echo 'return true;';
				echo '}';
					echo 'return false;';
				echo '}';
		echo '</script>';

		if(!empty($_GET['pagamentoEditar'])){
			$pagamentoId = $_GET['pagamentoEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['pagamentoDeletar'])){
			$pagamentoId = $_GET['pagamentoDeletar'];
			$acao = "deletar";
		}
		if(!empty($_GET['pagamentoBaixar'])){
			$pagamentoId = $_GET['pagamentoBaixar'];
			$acao = "baixar";
		}
		 
		if(!empty($pagamentoId)){
			$readPagar = read('pagar',"WHERE id = '$pagamentoId'");
			if(!$readPagar){
				header('Location: painel.php?execute=suporte/naoencontrado');
			  }else{
			    foreach($readPagar as $edit);	
				if($acao == "baixar"){
 					$edit['pagamento']= $edit['vencimento'];
				}
				// SE MOVIMENTO BLOQUEADO NAO DEIXA MAS EDITAR/EXCLUIR
				$banco= $edit['banco'];
				$data = $edit['pagamento'];
				$movimentacao= mostra('movimentacao',"WHERE banco ='$banco' AND data ='$data'");
				if($movimentacao['bloqueado']=='1'){
					$acao = "visualizar";
				}
			}
		  }else{	
			header('Location: painel.php?execute=suporte/naoencontrado');
		}
?>


<div class="content-wrapper">

  <section class="content-header">
          <h1>Pagar</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li>Site</li>
            <li><a href="painel.php?execute=suporte/pagar/despesas">
              	Despesas</a>
            </li>
            <li class="active">Editar</li>
          </ol>
  </section>

   <section class="content">
      <div class="box box-default">
   
            <div class="box-header with-border">
                <h3 class="box-title"><small><?php echo $edit['descricao'];?></small></h3>
                <div class="box-tools">
            		<small>
            		 <?php if($acao=='cadastrar') echo 'Cadastrando';?>
                     <?php if($acao=='deletar') echo 'Deletando';?>
                     <?php if($acao=='atualizar') echo 'Alterando';?>
                    </small>
          		</div><!-- /box-tools-->
      	  </div><!-- /.box-header -->
		  
	    <div class="box-body">

	<?PHP

		if(isset($_POST['atualizar'])){
			
			$cad['id_conta']  	= strip_tags(trim(mysql_real_escape_string($_POST['id_conta'])));
			$cad['emissao']		= strip_tags(trim(mysql_real_escape_string($_POST['emissao'])));
			$cad['vencimento'] 	= strip_tags(trim(mysql_real_escape_string($_POST['vencimento'])));
			$cad['valor']		= strip_tags(trim(mysql_real_escape_string($_POST['valor'])));
			$cad['valor'] = str_replace(",",".",str_replace(".","",$cad['valor']));
			$cad['formpag'] 	= strip_tags(trim(mysql_real_escape_string($_POST['formpag'])));
			$cad['banco']  		= strip_tags(trim(mysql_real_escape_string($_POST['banco'])));
			$cad['descricao'] 	= strip_tags(trim(mysql_real_escape_string($_POST['descricao'])));
			
			$cad['interacao']= date('Y/m/d H:i:s');
			
			if(in_array('',$cad)){
				
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
				
			  }else{
				
				
				$cad['juros'] = strip_tags(trim(mysql_real_escape_string($_POST['juros'])));
				$cad['juros'] = str_replace(",",".",str_replace(".","",$cad['juros']));
				
				
				$cad['parcela']  = strip_tags(trim(mysql_real_escape_string($_POST['parcela'])));
				$cad['pagamento'] = strip_tags(trim(mysql_real_escape_string($_POST['pagamento'])));
				$cad['nota'] 	= strip_tags(trim(mysql_real_escape_string($_POST['nota'])));
				$cad['cheque'] 	= strip_tags(trim(mysql_real_escape_string($_POST['cheque'])));
		$cad['codigo_barra'] = strip_tags(trim(mysql_real_escape_string($_POST['codigo_barra'])));
		$cad['id_fornecedor']  = strip_tags(trim(mysql_real_escape_string($_POST['id_fornecedor'])));
				$cad['usuario']	=  $_SESSION['autUser']['nome'];
				
				$cad['data']= date('Y/m/d');
				update('pagar',$cad,"id = '$pagamentoId'");
				
				// INTERAÇÃO
				$servicoId='-';
				$interacao='Pagamento Alterado n. '.$pagamentoId;
				interacao($interacao,$servicoId);
				
            $_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				
				header("Location: ".$_SESSION['url']);
				
			  }
			
			}
			
			if(isset($_POST['deletar'])){
		
			
				$readDeleta = read('pagar',"WHERE id = '$pagamentoId'");
				if(!$readDeleta){
					$_SESSION['cadastro'] = '<div class="alert alert-success">Registro Não Deletado</div>';	
				}else{
					delete('pagar',"id = '$pagamentoId'");
					
					// INTERAÇÃO
					$servicoId='-';
				 	$interacao='Pagamento excluido n. '.$pagamentoId;
					 interacao($interacao,$servicoId);
					
					$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
					header("Location: ".$_SESSION['url']);
				}
			}

			if(isset($_POST['baixar'])){
				$cad['id_conta']  = strip_tags(trim(mysql_real_escape_string($_POST['id_conta'])));
				$cad['valor']	= strip_tags(trim(mysql_real_escape_string($_POST['valor'])));
				$cad['valor'] = str_replace(",",".",str_replace(".","",$cad['valor']));
				$cad['formpag'] 	= strip_tags(trim(mysql_real_escape_string($_POST['formpag'])));
				$cad['banco']  = strip_tags(trim(mysql_real_escape_string($_POST['banco'])));
			$cad['descricao'] = strip_tags(trim(mysql_real_escape_string($_POST['descricao'])));
				
			$cad['pagamento'] = strip_tags(trim(mysql_real_escape_string($_POST['pagamento'])));
				$cad['status'] ='Baixado';
				$cad['interacao']= date('Y/m/d H:i:s');
				if(in_array('',$cad)){
					echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
				  }else{
					
					$cad['juros'] = strip_tags(trim(mysql_real_escape_string($_POST['juros'])));
					$cad['juros'] = str_replace(",",".",str_replace(".","",$cad['juros']));
			
					$cad['nota'] = strip_tags(trim(mysql_real_escape_string($_POST['nota'])));
					$cad['cheque'] = strip_tags(trim(mysql_real_escape_string($_POST['cheque'])));
		 $cad['codigo_barra'] = strip_tags(trim(mysql_real_escape_string($_POST['codigo_barra'])));
					  $cad['usuario']	=  $_SESSION['autUser']['nome'];
					  update('pagar',$cad,"id = '$pagamentoId'");
			
					 // INTERAÇÃO
					 $servicoId='-';
				 	 $interacao='Pagamento baixado n. '.$pagamentoId;
					 interacao($interacao,$servicoId);
					
					 $_SESSION['cadastro'] = '<div class="alert alert-success">Baixado com sucesso</div>';	
					 header("Location: ".$_SESSION['url']);
				  }
			}


		if(isset($_POST['pdf-boleto'])){
			$arquivo = $_FILES['arquivo'];
			$tamanhoPermitido = 1024 * 1024 * 1; // 1Mb
			$diretorio = "../uploads/pagamentos/boletos/";

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
			
			
		if(isset($_POST['pdf-nota'])){
			$arquivo = $_FILES['arquivo'];
			$tamanhoPermitido = 1024 * 1024 * 1; // 1Mb
			$diretorio = "../uploads/pagamentos/notas/";

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

		if(isset($_POST['pdf-comprovante'])){
			$arquivo = $_FILES['arquivo'];
			$tamanhoPermitido = 1024 * 1024 * 1; // 1Mb
			$diretorio = "../uploads/pagamentos/comprovantes/";

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
        
    	  <div class="box-body">
  			 <div class="form-group col-xs-12 col-md-2">  
               <label>Id</label>
               <input name="id" type="text" value="<?php echo $edit['id'];?>"  disabled class="form-control" />
             </div> 
             <div class="form-group col-xs-12 col-md-5">  
               	<label>Interação </label>
   				<input name="orc_resposta" type="text" value="<?php echo date('d/m/Y H:i:s',strtotime($edit['interacao']));?>" readonly class="form-control" /> 
			</div> 
             <div class="form-group col-xs-12 col-md-5">  
            	<label>Usuário</label>
                <input name="usuario" type="text" value="<?php echo $edit['usuario'];?>" disabled class="form-control" /> 
            </div>
			  
	 	<div class="form-group col-xs-12 col-md-12">  
            <label>Fornecedor</label>
                <select name="id_fornecedor" class="form-control">
                    <option value="">Fornecedor</option>
                    <?php 
                        $readConta = read('estoque_fornecedor',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos Bancos no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['id_fornecedor'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
             </div> 

              
           	<div class="form-group col-xs-12 col-md-12">  
            <label>Conta </label>
                <select name="id_conta" class="form-control">
                    <option value="">Conta</option>
                    <?php 
                         $readConta = read('pagar_conta',"WHERE status='1' ORDER BY codigo ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos Bancos no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['id_conta'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['codigo'].' | '.$mae['nome'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['codigo'].' | '.$mae['nome'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
             </div> 
            
      		 <div class="form-group col-xs-12 col-md-3">  
                 <label>Emissão</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
               <input type="date" name="emissao" class="form-control pull-right" value="<?php echo $edit['emissao'];?>"/>
                </div><!-- /.input group -->
           </div> 
           
           <div class="form-group col-xs-12 col-md-3">  
                 <label>Vencimento</label>
                  <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                       </div>
                       <input type="date" name="vencimento" class="form-control pull-right" value="<?php echo $edit['vencimento'];?>"/>
                 </div><!-- /.input group -->
           </div>
              
     		<div class="form-group col-xs-12 col-md-3">  
          		<label>Valor</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="valor" class="form-control pull-right"  value="<?php echo converteValor($edit['valor']);?>"/>
                 </div><!-- /.input group -->
           </div>
	   
	   
	  	 <div class="form-group col-xs-12 col-md-3">  
          		<label>Juros</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="juros" class="form-control pull-right"  value="<?php echo converteValor($$edit['juros']);?>"/>
                 </div><!-- /.input group -->
           </div>
              
            <div class="form-group col-xs-12 col-md-6"> 
              <label>Banco</label>
                <select name="banco" class="form-control">
                    <option value="">Selecione Banco</option>
                    <?php 
                         $readBanco = read('banco',"WHERE status='1' ORDER BY nome ASC");
                        if(!$readBanco){
                            echo '<option value="">Não temos Bancos no momento</option>';	
                        }else{
                            foreach($readBanco as $mae):
							   if($edit['banco'] == $mae['id']){
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
            <label>Forma de Pagamento </label>
                <select name="formpag" class="form-control" >
                    <option value="">Forma de Pagamento</option>
                    <?php 
                        $readFormpag = read('formpag',"WHERE id");
                        if(!$readFormpag){
                            echo '<option value="">Não temos Forma de Pagamento no momento</option>';	
                        }else{
                            foreach($readFormpag as $mae):
							   if($edit['formpag'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
            </div>
            
            <div class="form-group col-xs-12 col-md-12"> 
                 <label>Descrição</label>
                 <input type="text" name="descricao" value="<?php  echo $edit['descricao'];?>" class="form-control" />
     	    </div>
			  
			  <div class="form-group col-xs-12 col-md-12"> 
                 <label>Código de Barra</label>
                 <input type="text" name="codigo_barra" value="<?php  echo $cad['codigo_barra'];?>" class="form-control" />
     	    </div>

            <div class="form-group col-xs-12 col-md-2"> 
                 <label>Nota Fiscal </label>
                 <input type="text" name="nota" value="<?php if($edit['nota']) echo $edit['nota'];?>" class="form-control" />
    		 </div>
             
              <div class="form-group col-xs-12 col-md-2"> 
                 <label>Cheque</label>
                 <input type="text" name="cheque" value="<?php if($edit['cheque']) echo $edit['cheque'];?>" class="form-control" />
    		 </div>
             
              <div class="form-group col-xs-12 col-md-4"> 
                 <label>Parcela</label>
                 <input type="text" name="parcela" value="<?php if($edit['parcela']) echo $edit['parcela'];?>" class="form-control" />
    		  </div>
             
               <div class="form-group col-xs-12 col-md-4">  
                 <label>Pagamento</label>
                  <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                       </div>
                       <input type="date" name="pagamento" class="form-control pull-right" value="<?php echo $edit['pagamento'];?>"/>
                 </div><!-- /.input group -->
           </div>
		
	 <div class="form-group col-xs-12 col-md-12">  
  
      	 <div class="box-footer">
            <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>
          	  
           	  <?php 
                if($acao=="baixar"){
                    echo '<input type="submit" name="baixar" value="Baixar" class="btn btn-success" />';	
                }
				if($acao=="atualizar"){
                    echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';
					echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-danger" onClick="return confirm(\'Confirma Exclusão do Registro ?\')" />';
                }
                if($acao=="deletar"){
                    echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-danger" onClick="return confirm(\'Confirma Exclusão do Registro ?\')" />';	
                }
                if($acao=="cadastrar"){
                    echo '<input type="submit" name="cadastrar" value="Cadastrar" class="btn btn-primary" />';	
                }
                if($acao=="enviar"){
                    echo '<input type="submit" name="enviar" value="Enviar" class="btn btn-primary" />';	
                }
             ?>  
			 
		  </div>
		 
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
						 <div class="file-field">
							<div class="btn btn-default btn-sm">
								
							  <input type="file" name="arquivo">
								
							<br>
								
						 	 <input type="submit" name="pdf-boleto" value="Upload Boleto PDF" class="btn btn-primary" />
								
							</div>
						</div>
					</form>
				  </div> 
				
				<br>
				<br>
				<br>
				<br>
				<br>
				
				  <div class="form-group pull-right">
					  
					<?php
						$pdf='../uploads/pagamentos/boletos/'.$edit['id'].'.pdf';

						if(file_exists($pdf)){

							echo '<a href="../uploads/pagamentos/boletos/'.$edit['id'].'.pdf" target="_blank">
									<img src="ico/pdf.png"title="Boleto" />
								</a>';	
						}
					?>
					  
					   <label>Boleto PDF </label>
				</div> 
				
			 
	
			</div>
		 </div>
		</div>
		
		
		 <div class="col-md-4">   
          <div class="box">
         	<div class="box-header">
				
				 <div class="form-group pull-left">
					<form name="form" action="" method="post" enctype="multipart/form-data">
						 <div class="file-field">
							<div class="btn btn-default btn-sm">
								
							  <input type="file" name="arquivo">
								
							<br>
								
						 	 <input type="submit" name="pdf-nota" value="Upload Nota PDF" class="btn btn-primary" />
								
							</div>
						</div>
					</form>
				  </div> 
				
				<br>
				<br>
				<br>
				<br>
				<br>
				
				  <div class="form-group pull-right">
					  
					<?php
						$pdf='../uploads/pagamentos/notas/'.$edit['id'].'.pdf';

						if(file_exists($pdf)){

							echo '<a href="../uploads/pagamentos/notas/'.$edit['id'].'.pdf" target="_blank">
									<img src="ico/pdf.png"title="Nota" />
								</a>';	
						}
					?>
					  
					   <label>Nota PDF </label>
				</div> 
				
			 
	
			</div>
		 </div>
		</div>

	   <div class="col-md-4">   
          <div class="box">
         	<div class="box-header">
				
				  <div class="form-group pull-left">
					<form name="form" action="" method="post" enctype="multipart/form-data">
						 <div class="file-field">
							<div class="btn btn-default btn-sm">
								
							  <input type="file" name="arquivo">
							
							  <br>
								
						 	  <input type="submit" name="pdf-comprovante" value="Upload Boleto PDF" class="btn btn-primary" />
								
							</div>
						</div>
					</form>
				  </div> 
				
				<br>
				<br>
				<br>
				<br>
				<br>
				
					<div class="form-group pull-right">
						 
					<?php
						$pdf='../uploads/pagamentos/comprovantes/'.$edit['id'].'.pdf';

						if(file_exists($pdf)){

							echo '<a href="../uploads/pagamentos/comprovantes/'.$edit['id'].'.pdf" target="_blank">
									<img src="ico/pdf.png" title="Comprovante" />
								</a>';	
						}
					?>
						
						  <label>Comprovante PDF </label>
				</div> 
	
			</div>
		 </div>
		</div>
		
</div><!-- /.content-wrapper -->


