<?php 
	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autCliente']['id'])){
			header('Location: painel.php');	
		}	
	}
	 	
	$clienteId = $_SESSION['autCliente']['id'];
	$readCliente = read('cliente',"WHERE id = '$clienteId'");
	if(!$readCliente){
			header('Location: painel.php?execute=suporte/naoEncontrado');
	}
	foreach($readCliente as $edit);
	$acao="atualizar";
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
                <h3 class="box-title"><small><?php echo $edit['nome'];?></small></h3>
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
	$cad['email']= strip_tags(trim(mysql_real_escape_string($_POST['email'])));
	$cad['email'] = strtolower($cad['email']);
	$cad['email_financeiro']= strip_tags(trim(mysql_real_escape_string($_POST['email_financeiro'])));
	$cad['email_financeiro'] = strtolower($cad['email_financeiro']);
	$cad['senha']		= strip_tags(trim(mysql_real_escape_string($_POST['senha'])));
	$cad['celular'] 	= strip_tags(trim(mysql_real_escape_string($_POST['celular'])));
	$cad['telefone'] 	= strip_tags(trim(mysql_real_escape_string($_POST['telefone'])));
	$cad['contato'] 	= strip_tags(trim(mysql_real_escape_string($_POST['contato'])));
	if(in_array('',$cad)){
		echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
	}else{
		 update('cliente',$cad,"id = '$clienteId'");
		 header('Location: painel.php?execute=inc/home');
	}
}
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
                 <label>Restrição</label>
                  <input name="restricao"  class="form-control" type="text" value="###" disabled/>
            </div>
            <div class="form-group col-xs-12 col-md-6">  
                 <label>Nome</label>
                  <input name="nome"  class="form-control" type="text" value="<?php echo $edit['nome'];?>" disabled/>
            </div>
            <div class="form-group col-xs-12 col-md-6">  
                 <label>Nome Fantasia</label>
                  <input name="nome_fantasia"  class="form-control" type="text" value="<?php echo $edit['nome_fantasia'];?>" disabled/>
            </div>
            
            <div class="form-group col-xs-12 col-md-4">  
                 <label>Email</label>
                  <input name="email"  class="form-control" type="text" value="<?php echo $edit['email'];?>" >
            </div>
            
            <div class="form-group col-xs-12 col-md-4">  
                 <label>Envio de Email</label>
                 <select name="nao_enviar_email" class="form-control" disabled/>
                  <option value="">Selecione</option>
                  <option <?php if($edit['nao_enviar_email'] == '1') echo' selected="selected"';?> value="1">Não Enviar Email </option>
                  <option <?php if($edit['nao_enviar_email'] == '0') echo' selected="selected"';?> value="0">Sem Restrição</option>
                 </select>
           </div>
           <div class="form-group col-xs-12 col-md-4">  
              <label>Selecione um Tipo </label>
           	  <select name="tipo" class="form-control" disabled/>
                <option value="">Selecione Tipo de Cliente</option>
                    <?php 
                        $readtipo = read('cliente_tipo',"WHERE id");
                        if(!$readtipo){
                            echo '<option value="">Não temos tipo no momento</option>';	
                        }else{
                            foreach($readtipo as $mae):
							   if($edit['tipo'] == $mae['id']){
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
                <label>CPF </label>
                <input name="cpf" type="text"  value="<?php echo $edit['cpf'];?>"  class="form-control" OnKeyPress="formatar('###.###.###-##', this)" disabled/>
            </div>
            <div class="form-group col-xs-12 col-md-4">  
                <label>CNPJ </label>
                <input type="text" name="cnpj" value="<?php echo $edit['cnpj'];?>"   class="form-control" OnKeyPress="formatar('##.###.###/####-##', this)" disabled/>
           </div>
           <div class="form-group col-xs-12 col-md-4">  
                <label>Inscrição</label>
                <input type="text" name="inscricao"  value="<?php echo $edit['inscricao'];?>" class="form-control" disabled/>
           </div>
           
           <div class="form-group col-xs-12 col-md-4">  
                <label>Celular </label>
                <input type="text" name="celular" id="celular" value="<?php if($edit['celular']) echo $edit['celular'];?>"  class="form-control"  />
           </div>
           
           <div class="form-group col-xs-12 col-md-4">  
                <label>Fixo</label>
                <input type="text" name="telefone" id="telefone" value="<?php echo $edit['telefone'];?>"  class="form-control" />
           </div>       
           <div class="form-group col-xs-12 col-md-4">  
                    <label>Contato </label>
                    <input type="text" name="contato" value="<?php echo $edit['contato'];?>"   class="form-control" />  
           </div>
     		<div class="form-group col-xs-12 col-md-2">
                    <label>CEP </label>
                <input name="cep" id="cep" value="<?php echo $edit['cep'];?>"  class="form-control" disabled/>  
           </div>
			<div class="form-group col-xs-12 col-md-6">   
                <label>Endereço</label>
                <input name="endereco" id="endereco" value="<?php echo $edit['endereco'];?>" class="form-control" disabled/>
            </div>
           <div class="form-group col-xs-12 col-md-2">   
                <label>Numero </label>
                <input name="numero"  value="<?php echo $edit['numero'];?>" class="form-control" disabled/>  
            </div> 
             <div class="form-group col-xs-12 col-md-2">   
               <label>Complemento </label>
                <input name="complemento"  value="<?php echo $edit['complemento'];?>" class="form-control" disabled/>  
            </div>
            
      		<div class="form-group col-xs-12 col-md-3">   
                <label>Bairro</label>
                <input name="bairro" id="bairro" value="<?php echo $edit['bairro'];?>" class="form-control" disabled/>         					
            </div> 
            
           <div class="form-group col-xs-12 col-md-2">   
                <label>Cidade </label>
                <input name="cidade" id="cidade" value="<?php echo $edit['cidade'];?>" class="form-control" disabled/> 
           </div>
           
            <div class="form-group col-xs-12 col-md-1">   
                <label>UF </label>
                <input name="uf" id="uf" value="<?php echo $edit['uf'];?>"  class="form-control"  disabled/> 
            </div>

           <div class="form-group col-xs-12 col-md-6">   
                <label>Referencia </label>
                <input name="referencia" value="<?php echo $edit['referencia'];?>" class="form-control" disabled/> 
           </div>
           		 

          <div class="form-group col-xs-12 col-md-6">   
                <label>Email Financeiro </label>
                <input type="email" name="email_financeiro" value="<?php echo $edit['email_financeiro'];?>" class="form-control" />
          </div>
             
    	  <div class="form-group col-xs-12 col-md-2">   
                <label>Senha </label>
                <input type="password" name="senha" value="<?php echo $edit['senha'];?>" class="form-control" /> 
          </div>
              
          <div class="form-group col-xs-12 col-md-4">   
             <label>Selecione um Classificação</label>
                  <select name="classificacao" class="form-control" disabled  />   
                    <option value="">Selecione uma Classificação</option>
                        <?php 
                            $readclassificacao = read('cliente_classificacao',"id ORDER BY nome ASC");
                            if(!$readclassificacao){
                                echo '<option value="">Não temos classificação no momento</option>';	
                            }else{
                                foreach($readclassificacao as $mae):
                                   if($edit['classificacao'] == $mae['id']){
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
                <div class="box-footer">
                  <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>
                 <?php 
                    if($acao=="atualizar"){
                        echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';	
                    }
                    if($acao=="deletar"){
                        echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-danger" />';	
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
  

<section class="content">
	<div class="row">          
	 <div class="col-md-12">   
          <div class="box">
          
          	<div class="box-header">
       		   
        	   <?php
                  	echo '<p align="center">'.$edit['nome'].', '.$edit['telefone'].' / '.$edit['contato'].'</p>';
					echo '<p align="center">'.$edit['endereco'].', '.$edit['numero'].'  '.$edit['complemento'].
				' - '.$edit['bairro'].' - '.$edit['cidade'].' - '.$edit['cep'].'</p>';
				
				$address = url($edit['endereco'].', '.$edit['numero'].'  '.$edit['complemento'].
				' - '.$edit['bairro'].' - '.$edit['cidade'].' - '.$edit['cep'])
				
           		?>
           		
         		
         		<iframe width='100%' height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" zomm="1" src="https://maps.google.com.br/maps?q=<?php echo $address; ?>&output=embed">
         		</iframe>
           	</div><!-- /.box-header -->
    	</div><!-- /.box  -->
     </div><!-- /.box  -->
   </div>
</section><!-- /.content -->
  
</div><!-- /.content-wrapper -->

 

 