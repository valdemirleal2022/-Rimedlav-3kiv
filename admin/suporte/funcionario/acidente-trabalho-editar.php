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
			$readacidente = read('funcionario_acidente_trabalho',"WHERE id = '$acidenteId'");
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
          <h1>Acidente de Trabalho</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Funcionario</a></li>
            <li><a href="painel.php?execute=suporte/funcionario/acidentes">acidente</a></li>
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
				
				$edit['id_funcionario'] = mysql_real_escape_string($_POST['id_funcionario']);
				$edit['tipo_acidente'] = mysql_real_escape_string($_POST['tipo_acidente']);
				$edit['data'] = mysql_real_escape_string($_POST['data']);
				$edit['local_lesao'] = mysql_real_escape_string($_POST['local_lesao']);
				$edit['agente_causador'] = mysql_real_escape_string($_POST['agente_causador']);
				$edit['status'] = mysql_real_escape_string($_POST['status']);
				$edit['dias_afastados'] = mysql_real_escape_string($_POST['dias_afastados']);
				$edit['usuario'] = mysql_real_escape_string($_POST['usuario']);
				$edit['usuario'] = $_SESSION['autUser']['nome'];
				update('funcionario_acidente_trabalho',$edit,"id = '$acidenteId'");	
				
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				
				header("Location: ".$_SESSION['url']);
			}
		
			 
			if(isset($_POST['cadastrar'])){
				$edit['id_funcionario'] = mysql_real_escape_string($_POST['id_funcionario']);
				$edit['tipo_acidente'] = mysql_real_escape_string($_POST['tipo_acidente']);
				$edit['data'] = mysql_real_escape_string($_POST['data']);
				$edit['local_lesao'] = mysql_real_escape_string($_POST['local_lesao']);
				$edit['agente_causador'] = mysql_real_escape_string($_POST['agente_causador']);
				$edit['status'] = mysql_real_escape_string($_POST['status']);
				$edit['dias_afastados'] = mysql_real_escape_string($_POST['dias_afastados']);
				$edit['usuario'] =  $_SESSION['autUser']['nome'];
				$edit['id_funcionario'] = $funcionarioId;
				if(in_array('',$edit)){
				 
					echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
				  }else{
				    create('funcionario_acidente_trabalho',$edit);
					$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
				    header("Location: ".$_SESSION['url']);
		    	}
			}
			
			
			if(isset($_POST['deletar'])){
					$readDeleta = read('funcionario_acidente_trabalho',"WHERE id = '$acidenteId'");
					if(!$readDeleta){
						echo '<div class="alert alert-warning">Desculpe, o registro não existe!</div><br />';
					}else{
						delete('funcionario_acidente_trabalho',"id = '$acidenteId'");
						$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
						header("Location: ".$_SESSION['url']);
					}
			 }
			 
	 if(isset($_POST['pdf-comprovante'])){
		 
			$arquivo = $_FILES['arquivo'];
			$tamanhoPermitido = 1024 * 1024 * 2; // 1Mb
			$diretorio = "../uploads/funcionarios/acidente-trabalho/";

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
             <h3 class="box-title">Acidente de Trabalho</h3>
           </div><!-- /.box-header -->
                
         <div class="box-body">
        <div class="row">
 
      		<div class="form-group col-xs-12 col-md-3">  
                <label>Id</label>
                 <input name="id" class="form-control" type="text" disabled value="<?php echo $edit['id'];?>"/>
            </div>

		   <div class="form-group col-xs-12 col-md-3">  
            	<label>Usuário</label>
                <input name="usuario" type="text" value="<?php echo $edit['usuario'];?>" readonly class="form-control" /> 
            </div> 
			
			
			 <div class="form-group col-xs-12 col-md-3"> 
                <label>Tipo de Acidente</label>
                <select name="tipo_acidente" class="form-control" >
                  <option value="">Status</option>
                  <option <?php if($edit['tipo_acidente'] == '1') echo' selected="selected"';?> value="1">Condição Insegura</option>
                  <option <?php if($edit['tipo_acidente'] == '2') echo' selected="selected"';?> value="2">Ato Inseguro</option>
                 </select>
            </div>  
            
  
			<div class="form-group col-xs-12 col-md-3">  
					 <label>Data</label>
					  <div class="input-group">
						   <div class="input-group-addon">
							 <i class="fa fa-calendar"></i>
						   </div>
					  <input type="date" name="data" class="form-control" value="<?php echo $edit['data'];?>"/>
					  </div><!-- /.input group -->
			 </div> 
			
			 
              
			 <div class="form-group col-xs-12 col-md-12"> 
				  <label>Local Lesão</label>
					<input name="local_lesao" type="text" value="<?php echo $edit['local_lesao'];?>"  class="form-control" /> 

			 </div>  

			 <div class="form-group col-xs-12 col-md-6"> 
				  <label>Agente Causador</label>
					<input name="agente_causador" type="text" <?php echo $edit['agente_causador'];?> class="form-control" /> 
			 </div> 
		 
		 	 <div class="form-group col-xs-12 col-md-3"> 
                <label>Tipo</label>
                <select name="status" class="form-control" >
                  <option value="">Status</option>
                  <option <?php if($edit['status'] == '1') echo' selected="selected"';?> value="1">Com afastamento</option>
                  <option <?php if($edit['status'] == '2') echo' selected="selected"';?> value="2">Sem afastamento</option>
                 </select>
            </div>  
            
		 
		 	 <div class="form-group col-xs-12 col-md-3"> 
				  <label>Dias afastado</label>
						<input name="dias_afastados" type="text" <?php echo $edit['dias_afastados'];?> class="form-control" /> 
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
						$pdf='../uploads/funcionarios/acidente-trabalho/'.$edit['id'].'.pdf';

						if(file_exists($pdf)){

							echo '<a href="../uploads/funcionarios/acidente-trabalho/'.$edit['id'].'.pdf" target="_blank">
									<img src="ico/pdf.png" title="Comprovante" />
								</a>';	
						}
					?>
				</div> 
	
			</div>
		 </div>
		</div>
		
</div><!-- /.content-wrapper -->


