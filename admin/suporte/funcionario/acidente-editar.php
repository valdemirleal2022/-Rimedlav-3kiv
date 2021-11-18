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

		if(!empty($_GET['acidenteEditar'])){
			$acidenteId = $_GET['acidenteEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['acidenteVisualizar'])){
			$acidenteId = $_GET['acidenteVisualizar'];
			$acao = "visualizar";
		}
		if(!empty($_GET['acidenteDeletar'])){
			$acidenteId = $_GET['acidenteDeletar'];
			$acao = "deletar";
		}
		if(!empty($_GET['acidenteBaixar'])){
			$acidenteId = $_GET['acidenteBaixar'];
			$acao = "baixar";
		}

		

		if(!empty($acidenteId)){
			$readacidente = read('funcionario_acidente',"WHERE id = '$acidenteId'");
			if(!$readacidente){
				header('Location: painel.php?execute=suporte/naoencontrado');	
			}
			foreach($readacidente as $edit);
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
          <h1>Acidente de Trânsito</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Funcionario</a></li>
            <li><a href="painel.php?execute=suporte/funcionario/acidentes">Acidente de Trânsito</a></li>
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
				$edit['tipo_acidente'] = mysql_real_escape_string($_POST['tipo_acidente']);
				$edit['local'] = mysql_real_escape_string($_POST['local']);
				$edit['agente_causador'] = mysql_real_escape_string($_POST['agente_causador']);
				$edit['id_veiculo'] = mysql_real_escape_string($_POST['id_veiculo']);
				$edit['acionou_seguradora'] = mysql_real_escape_string($_POST['acionou_seguradora']);
				$edit['status_seguradora'] = mysql_real_escape_string($_POST['status_seguradora']);
				$edit['status'] = mysql_real_escape_string($_POST['status']);
				$edit['descricao'] = mysql_real_escape_string($_POST['descricao']);
				$edit['versao_motorista'] = mysql_real_escape_string($_POST['versao_motorista']);
				$edit['usuario'] = $_SESSION['autUser']['nome'];
				update('funcionario_acidente',$edit,"id = '$acidenteId'");	
				
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				
				header("Location: ".$_SESSION['url']);
			}
		
			 
			if(isset($_POST['cadastrar'])){
				$edit['data'] = mysql_real_escape_string($_POST['data']);
				$edit['tipo_acidente'] = mysql_real_escape_string($_POST['tipo_acidente']);
				$edit['local'] = mysql_real_escape_string($_POST['local']);
				$edit['agente_causador'] = mysql_real_escape_string($_POST['agente_causador']);
				$edit['id_veiculo'] = mysql_real_escape_string($_POST['id_veiculo']);
				$edit['acionou_seguradora'] = mysql_real_escape_string($_POST['acionou_seguradora']);
				$edit['status_seguradora'] = mysql_real_escape_string($_POST['status_seguradora']);
				$edit['status'] = mysql_real_escape_string($_POST['status']);
				$edit['descricao'] = mysql_real_escape_string($_POST['descricao']);
				$edit['versao_motorista'] = mysql_real_escape_string($_POST['versao_motorista']);
				$edit['id_funcionario'] = $funcionarioId;
				if(in_array('',$edit)){
				 
					echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
				  }else{
				    create('funcionario_acidente',$edit);
					$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
				    header("Location: ".$_SESSION['url']);
		    	}
			}
			
			
			if(isset($_POST['deletar'])){
					$readDeleta = read('funcionario_acidente',"WHERE id = '$acidenteId'");
					if(!$readDeleta){
						echo '<div class="alert alert-warning">Desculpe, o registro não existe!</div><br />';
					}else{
						delete('funcionario_acidente',"id = '$acidenteId'");
						$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
						header("Location: ".$_SESSION['url']);
					}
			 }
			 
	 if(isset($_POST['pdf-comprovante'])){
			$arquivo = $_FILES['arquivo'];
			$tamanhoPermitido = 1024 * 1024 * 2; // 1Mb
			$diretorio = "../uploads/funcionarios/acidente/";

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
             <h3 class="box-title">Acidentes</h3>
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

           <div class="form-group col-xs-12 col-md-2">  
            <label>Veículo</label>
                <select name="id_veiculo" class="form-control">
                    <option value="">Veículo</option>
                    <?php 
                        $readConta = read('veiculo',"WHERE id ORDER BY placa ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos veiculos no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['id_veiculo'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['placa'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['placa'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
           </div> 
		
			
			<div class="form-group col-xs-12 col-md-2">  
            <label>Tipo</label>
                <select name="tipo_acidente" class="form-control" >
                    <option value="">Selecione uma Tipo</option>
                    <?php 
                        $readSuporte = read('funcionario_acidente_tipo',"WHERE id");
                        if(!$readSuporte){
                            echo '<option value="">Não temos Suporte no momento</option>';	
                        }else{
                            foreach($readSuporte as $mae):
							   if($edit['tipo_acidente'] == $mae['id']){
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
					  <input type="date" name="data" class="form-control" value="<?php echo $edit['data'];?>"/>
					  </div><!-- /.input group -->
			 </div> 
			
			 <div class="form-group col-xs-12 col-md-2"> 
                <label>Status</label>
                <select name="status" class="form-control" >
                  <option value="">Status</option>
                  <option <?php if($edit['status'] == '1') echo' selected="selected"';?> value="1">Advertência</option>
				  <option <?php if($edit['status'] == '2') echo' selected="selected"';?> value="2">Treinamento</option>
				  <option <?php if($edit['status'] == '3') echo' selected="selected"';?> value="3">Suspensão</option>
                  <option <?php if($edit['status'] == '4') echo' selected="selected"';?> value="4">Demissão</option>
				  <option <?php if($edit['status'] == '5') echo' selected="selected"';?> value="5">NA</option>
                 </select>
            </div>  
		
              
			 <div class="form-group col-xs-12 col-md-12"> 
				  <label>Local</label>
					<textarea name="local" rows="1" cols="100" class="form-control" /><?php echo $edit['local'];?></textarea>
			 </div>  

			 <div class="form-group col-xs-12 col-md-12"> 
				  <label>Agente Causador</label>
					<textarea name="agente_causador" rows="1" cols="100" class="form-control" /><?php echo $edit['agente_causador'];?></textarea>
			 </div> 
		 
		 	 <div class="form-group col-xs-12 col-md-12"> 
				  <label>Descrição do Acidente</label>
					<textarea name="descricao" rows="3" cols="100" class="form-control" /><?php echo $edit['descricao'];?></textarea>
			 </div>  
		
			 <div class="form-group col-xs-12 col-md-12"> 
				  <label>Parecer do Instrutor</label>
					<textarea name="versao_motorista" rows="3" cols="100" class="form-control" /><?php echo $edit['versao_motorista'];?></textarea>
			 </div> 
		 
		 	 <div class="form-group col-xs-12 col-md-2"> 
                <label>Acionou Seguradora</label>
                 <select name="acionou_seguradora" class="form-control" >
                  <option <?php if($edit['acionou_seguradora'] == '1') echo' selected="selected"';?> value="1">Sim</option>
                  <option <?php if($edit['acionou_seguradora'] == '2') echo' selected="selected"';?> value="2">Não</option>
                 </select>
            </div> 
		 
		 	 <div class="form-group col-xs-12 col-md-10"> 
				  <label>Status</label>
				  <select name="status_seguradora" class="form-control" >
                  <option <?php if($edit['status_seguradora'] == '1') echo' selected="selected"';?> value="1">Em andamento</option>
                  <option <?php if($edit['status_seguradora'] == '2') echo' selected="selected"';?> value="2">Concluído</option>
                 </select>
			 
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
						$pdf='../uploads/funcionarios/acidente/'.$edit['id'].'.pdf';

						if(file_exists($pdf)){

							echo '<a href="../uploads/funcionarios/acidente/'.$edit['id'].'.pdf" target="_blank">
									<img src="ico/pdf.png" title="Comprovante" />
								</a>';	
						}
					?>
				</div> 
	
			</div>
		 </div>
		</div>
		
</div><!-- /.content-wrapper -->


