<?php 

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php?execute=suporte/403');
			}	
		}
		
		
		if(!empty($_GET['clienteId'])){
			$clienteId = $_GET['clienteId'];
			$acao = "atualizar";
			$quantidade = 1;
		}

		if(!empty($_GET['clienteEditar'])){
			$clienteCobrancaId = $_GET['clienteEditar'];
			$acao = "atualizar";
			$quantidade = 1;
		}

		if(!empty($_GET['clienteDeletar'])){
			$clienteCobrancaId = $_GET['clienteDeletar']; 
			$acao = "deletar";
		}

		if(!empty($clienteCobrancaId)){
			
			$readCliente = read('cadastro_visita_cobranca',"WHERE id = '$clienteCobrancaId'");
			if(!$readCliente){
				header('Location: painel.php?execute=suporte/error');
			}
			
			foreach($readCliente as $edit);
			
 		}

		if(!empty($clienteId)){
			
			$readCliente = read('cadastro_visita',"WHERE id = '$clienteId'");
			if(!$readCliente){
				header('Location: painel.php?execute=suporte/error');
			}
			
			foreach($readCliente as $cliente);
			$clienteId = $cliente['id']; 
			
			$acao = "cadastrar";

			
 		}

?>

<div class="content-wrapper">

     <section class="content-header">
              <h1>Cliente</h1>
              <ol class="breadcrumb">
                <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
                <li><a href="#">Cliente</a></li>
                <li><a href="painel.php?execute=suporte/cliente/clientes">Clientes</a></li>
                 <li class="active">Editar</li>
              </ol>
     </section>
     
	 <section class="content">
      <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><small><?php echo $cliente['nome'];?></small></h3>
             	<div class="box-tools">
            		<small>
            		 <?php if($acao=='cadastrar') echo 'Cadastrando';?>
                     <?php if($acao=='deletar') echo 'Deletando';?>
                     <?php if($acao=='atualizar') echo 'Alterando';?>
                    </small>
          		</div><!-- /box-tools-->
      	  </div><!-- /.box-header -->
    	  <div class="box-body">
         
		 <?php 
	
		if(isset($_POST['atualizar'])){

			$edit['nome'] 		= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			$edit['endereco']	= strip_tags(trim(mysql_real_escape_string($_POST['endereco'])));
			$edit['numero'] 		= strip_tags(trim(mysql_real_escape_string($_POST['numero'])));
			$edit['complemento']	= strip_tags(trim(mysql_real_escape_string($_POST['complemento'])));
			$edit['bairro']		= strip_tags(trim(mysql_real_escape_string($_POST['bairro'])));
			$edit['cep'] 		= strip_tags(trim(mysql_real_escape_string($_POST['cep'])));
			$edit['cidade']  	= strip_tags(trim(mysql_real_escape_string($_POST['cidade'])));
			$edit['uf']  		= strip_tags(trim(mysql_real_escape_string($_POST['uf'])));
			$edit['cnpj'] 	= strip_tags(trim(mysql_real_escape_string($_POST['cnpj'])));
			$edit['inscricao'] 	= strip_tags(trim(mysql_real_escape_string($_POST['inscricao'])));
			$edit['cpf']   	= strip_tags(trim(mysql_real_escape_string($_POST['cpf'])));
				
			if(empty($edit['nome'])){
				echo '<div class="alert alert-warning">O Nome do cliente é obrigatório!</div>'.'<br>';
			  }else{
					update('cadastro_visita_cobranca',$edit,"id = '$clienteCobrancaId'");
					header("Location: ".$_SESSION['url_cliente']);
				}
		}
	
	 	if(isset($_POST['deletar'])){
			$readDeleta = read('cadastro_visita_cobranca',"WHERE id = '$clienteCobrancaId'");
			if(!$readDeleta){
				echo '<div class="alert alert-warning">Desculpe, o registro não existe</div><br />';	
			 }else{
				delete('cadastro_visita_cobranca',"id = '$clienteId'");
				header("Location: ".$_SESSION['url_cliente']);
			}
		}
			
		if(isset($_POST['cadastrar'])){
			
		 	$edit['nome']= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			$edit['id_cliente']= $clienteId;
			$edit['endereco']= strip_tags(trim(mysql_real_escape_string($_POST['endereco'])));
			$edit['numero']= strip_tags(trim(mysql_real_escape_string($_POST['numero'])));
			$edit['complemento']= strip_tags(trim(mysql_real_escape_string($_POST['complemento'])));
			$edit['bairro']= strip_tags(trim(mysql_real_escape_string($_POST['bairro'])));
			$edit['cep']= strip_tags(trim(mysql_real_escape_string($_POST['cep'])));
			$edit['cidade']= strip_tags(trim(mysql_real_escape_string($_POST['cidade'])));
			$edit['uf']	= strip_tags(trim(mysql_real_escape_string($_POST['uf'])));
		 	$edit['cnpj']= strip_tags(trim(mysql_real_escape_string($_POST['cnpj'])));
			$edit['inscricao']= strip_tags(trim(mysql_real_escape_string($_POST['inscricao'])));
			
			$edit['cpf']= strip_tags(trim(mysql_real_escape_string($_POST['cpf'])));
			
		  	if(empty($edit['nome'])){
				
				echo '<div class="alert alert-warning">O Nome do cliente é obrigatório!</div>'.'<br>';
		
				}elseif(empty($edit['cep'])){
				
					echo '<div class="alert alert-warning">O CEP do cliente é obrigatório!</div>'.'<br>';
				
				}elseif(!empty($edit['cnpj']) && !cnpj($edit['cnpj']) ){
				
					echo '<div class="alert alert-warning">Desculpe o CNPJ informado é inválido!</div>'.'<br>';
				
				}elseif(!empty($edit['cpf']) && !cpf($edit['cpf']) ){
				
						echo '<div class="alert alert-warning">Desculpe o CPF informado é inválido!</div>'.'<br>';
				
				}elseif(empty($edit['cpf']) && empty($edit['cnpj']) ){
				
					echo '<div class="alert alert-warning">Desculpe o CNPJ/CPF precisa ser preenchido!</div>'.'<br>';
	
				 }else{
				
					create('cadastro_visita_cobranca',$edit);	
					header("Location: ".$_SESSION['url_orcamento']);
				
			}
		};	
		
	?>

    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
             <div class="form-group col-xs-12 col-md-1">  
                 <label>Id</label>
                  <input name="id"  class="form-control" type="text" value="<?php echo $edit['id'];?>" disabled/>
             </div>
             <div class="form-group col-xs-12 col-md-3">  
                 <label>Data do Cadastro</label>
                  <input name="id"  class="form-control" type="text" value="<?php echo $edit['data'];?>" disabled/>
            </div>
            <div class="form-group col-xs-12 col-md-8">  
                 <label>Nome</label>
                  <input name="nome"  class="form-control" type="text" value="<?php echo $edit['nome'];?>">
            </div>
           
            <div class="form-group col-xs-12 col-md-4">  
                <label>CPF </label>
                <input name="cpf" type="text" onBlur="ValidarCPF(formulario.cpf);"  onKeyPress="MascaraCPF(formulario.cpf);" value="<?php echo $edit['cpf'];?>"  class="form-control" onblur="TestaCPF(this)" >
            </div>
		
            <div class="form-group col-xs-12 col-md-4">  
                 <label>CNPJ </label>
                <input type="text" name="cnpj" value="<?php echo $edit['cnpj'];?>"   class="form-control" OnKeyPress="formatar('##.###.###/####-##', this)" />
           </div>

           
           <div class="form-group col-xs-12 col-md-4">  
                <label>Inscrição</label>
                <input type="text" name="inscricao"  value="<?php echo $edit['inscricao'];?>" class="form-control" />
           </div>
           
           
     		<div class="form-group col-xs-12 col-md-2">
                    <label>CEP </label>
                <input name="cep" id="cep" value="<?php echo $edit['cep'];?>"  class="form-control" />  
           </div>
			<div class="form-group col-xs-12 col-md-6">   
                <label>Endereço</label>
                <input name="endereco" id="endereco" value="<?php echo $edit['endereco'];?>" class="form-control" readonly />  
            </div>
           <div class="form-group col-xs-12 col-md-2">   
                <label>Numero </label>
                <input name="numero"  value="<?php echo $edit['numero'];?>" class="form-control" />  
            </div> 
             <div class="form-group col-xs-12 col-md-2">   
               <label>Complemento </label>
                <input name="complemento"  value="<?php echo $edit['complemento'];?>" class="form-control" />  
            </div>
            
      		<div class="form-group col-xs-12 col-md-3">   
                <label>Bairro</label>
                <input name="bairro" id="bairro" value="<?php echo $edit['bairro'];?>" class="form-control" readonly/>           					
            </div> 
            
           <div class="form-group col-xs-12 col-md-2">   
                <label>Cidade </label>
                <input name="cidade" id="cidade" value="<?php echo $edit['cidade'];?>" class="form-control" readonly />  
           </div>
           
            <div class="form-group col-xs-12 col-md-1">   
                <label>UF </label>
                <input name="uf" id="uf" value="<?php echo $edit['uf'];?>"  class="form-control" readonly/>  
            </div>

        
         	<div class="form-group col-xs-12 col-md-12">  
                <div class="box-footer">
                  <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>
                 <?php 
                    if($acao=="atualizar"){
                        echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';	
						
						 if($_SESSION['autUser']['nivel']==5){	//Gerencial 
							 echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-danger" onClick="return confirm(\'Confirma Exclusão do Registro ?\')" />';	
						 }
                    }
                    if($acao=="deletar"){
                 
                    }
                    if($acao=="cadastrar"){
                        echo '<input type="submit" name="cadastrar" value="Cadastrar" class="btn btn-primary" />';	
                    }
                    if($acao=="enviar"){
                        echo '<input type="submit" name="enviar" value="Enviar" class="btn btn-primary" />';	
                    }
                 ?>  
              </div>  <!-- /. box-footer -->        
         </div>  <!-- /. col-md-12 -->        
    	</form>
       
    </div><!-- /.box-body -->
    </div><!-- /.box box-default -->
  </section><!-- /.content -->

</div><!-- /.content-wrapper -->


<script>
	
	$(document).ready(function() {
		
            function limpa_formulário_cep() {
                // Limpa valores do formulário de cep.
                $("#endereco").val("");
                $("#bairro").val("");
                $("#cidade").val("");
                $("#uf").val("");
                $("#ibge").val("");
            }
		
            //Quando o campo cep perde o foco.
            $("#cep").blur(function() {
                //Nova variável "cep" somente com dígitos.
                var cep = $(this).val().replace(/\D/g, '');
                //Verifica se campo cep possui valor informado.
                if (cep != "") {
                    //Expressao regular para validar o CEP.
                    var validacep = /^[0-9]{8}$/;
                    //Valida o formato do CEP.
                    if(validacep.test(cep)) {
                        //Preenche os campos com "..." enquanto consulta webservice.
                        $("#endereco").val("procurando... Aguarde ")
                        $("#bairro").val("")
                        $("#cidade").val("")
                        $("#uf").val("")
                        //Consulta o webservice viacep.com.br/
                        $.getJSON("//viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
                            if (!("erro" in dados)) {
                                //Atualiza os campos com os valores da consulta.
                                $("#endereco").val(dados.logradouro);
                                $("#bairro").val(dados.bairro);
                                $("#cidade").val(dados.localidade);
								$("#uf").val(dados.uf);
                            } //end if.
                            else {
                                //CEP pesquisado nao foi encontrado.
                                limpa_formulário_cep();
                                alert("CEP nao encontrado.");
                            }
                        });
                    } //end if.
					
                    else {
                        //cep é inválido.
                        limpa_formulário_cep();
                        alert("Formato de CEP inválido.");
                    }
                } //end if.
                else {
                    //cep sem valor, limpa formulário.
                    limpa_formulário_cep();
                }
            });
	
	
        });
	  
      $(function () {
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace('editor-texto');
        //bootstrap WYSIHTML5 - text editor
        $(".textarea").wysihtml5();
      });
	  
	  
	   //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });
        //Red color scheme for iCheck
        $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
          checkboxClass: 'icheckbox_minimal-red',
          radioClass: 'iradio_minimal-red'
        });
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
          checkboxClass: 'icheckbox_flat-green',
          radioClass: 'iradio_flat-green'
        });

		
 </script>