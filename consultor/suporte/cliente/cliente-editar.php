<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autConsultor']['id'])){
			header('Location: painel.php');	
		}	
	}
		
		$acao = "cadastrar";
		if(!empty($_GET['clienteEditar'])){
			$clienteId = $_GET['clienteEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['clienteDeletar'])){
			$clienteId = $_GET['clienteDeletar']; 
			$acao = "deletar";
		}
		if(!empty($clienteId)){
			$readCliente = read('cliente',"WHERE id = '$clienteId'");
			if(!$readCliente){
				header('Location: painel.php?execute=suporte/error');
			}
			foreach($readCliente as $edit);
	
			if(empty($edit['email'])){
				$edit['email'] = $edit['id'] . '@empresa.com.br';
				$edit['email_financeiro'] = $edit['email'];
				$edit['email_financeiro2'] = $edit['email'];
				$edit['nao_enviar_email'] = '1';
			}
		}else{
			$total = conta('cliente',"WHERE id");
			$total=$total+1;
			$edit['email'] = $total . '@empresa.com.br';
			$edit['email_financeiro'] = $edit['email']; 
			$edit['email_financeiro'] = $edit['email'];
			$edit['data']= date('d/m/Y H:i:s');
 
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
			$edit['tipo']		= strip_tags(trim(mysql_real_escape_string($_POST['tipo'])));
			$edit['classificacao']= strip_tags(trim(mysql_real_escape_string($_POST['classificacao'])));
			$edit['email']		= strip_tags(trim(mysql_real_escape_string($_POST['email'])));
			$edit['email'] = strtolower($edit['email']);
			$edit['senha']		= strip_tags(trim(mysql_real_escape_string($_POST['senha'])));
			$edit['nome'] 		= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			$edit['nome_fantasia'] 		= strip_tags(trim(mysql_real_escape_string($_POST['nome_fantasia'])));
			$edit['endereco']	= strip_tags(trim(mysql_real_escape_string($_POST['endereco'])));
			$edit['numero'] 		= strip_tags(trim(mysql_real_escape_string($_POST['numero'])));
			$edit['complemento']	= strip_tags(trim(mysql_real_escape_string($_POST['complemento'])));
			$edit['bairro']		= strip_tags(trim(mysql_real_escape_string($_POST['bairro'])));
			$edit['cep'] 		= strip_tags(trim(mysql_real_escape_string($_POST['cep'])));
			$edit['cidade']  	= strip_tags(trim(mysql_real_escape_string($_POST['cidade'])));
			$edit['uf']  		= strip_tags(trim(mysql_real_escape_string($_POST['uf'])));
			$edit['referencia']  = strip_tags(trim(mysql_real_escape_string($_POST['referencia'])));
			$edit['celular'] 	= strip_tags(trim(mysql_real_escape_string($_POST['celular'])));
			$edit['telefone'] 	= strip_tags(trim(mysql_real_escape_string($_POST['telefone'])));
			$edit['cnpj'] 	= strip_tags(trim(mysql_real_escape_string($_POST['cnpj'])));
			$edit['cpf']   	= strip_tags(trim(mysql_real_escape_string($_POST['cpf'])));
			$edit['nao_enviar_email'] 	= strip_tags(trim(mysql_real_escape_string($_POST['nao_enviar_email'])));
	
			if(empty($edit['nome'])){
				echo '<div class="alert alert-warning">O Nome do cliente é obrigatório!</div>'.'<br>';
			 }elseif(empty($edit['cep'])){
				echo '<div class="alert alert-warning">O CEP do cliente é obrigatório!</div>'.'<br>';
			 }elseif(!email($edit['email'])){
				echo '<div class="alert alert-warning">Desculpe o e-mail informado é inválido!</div>'.'<br>';
			 }elseif(!empty($edit['cnpj']) && !cnpj($edit['cnpj']) ){
					echo '<div class="alert alert-warning">Desculpe o CNPJ informado é inválido!</div>'.'<br>';
			 }elseif(!empty($edit['cpf']) && !cpf($edit['cpf']) ){
					echo '<div class="alert alert-warning">Desculpe o CPF informado é inválido!</div>'.'<br>';
			  }else{
				$edit['contato']=strip_tags(trim(mysql_real_escape_string($_POST['contato'])));
				$edit['inscricao']=strip_tags(trim(mysql_real_escape_string($_POST['inscricao'])));
				$edit['senha']=strip_tags(trim(mysql_real_escape_string($_POST['senha'])));
				$edit['email_financeiro']=strip_tags(trim(mysql_real_escape_string($_POST['email_financeiro'])));
				$edit['restricao']=strip_tags(trim(mysql_real_escape_string($_POST['restricao'])));
				$edit['email_financeiro2']		= strip_tags(trim(mysql_real_escape_string($_POST['email_financeiro2'])));
					
					$edit['senha']   	= strip_tags(trim(mysql_real_escape_string($_POST['senha']))); 
					
				   // pegar latituto e longitude atualizado em 04/08/2017
					$endereco = url($edit['endereco'].', '.$edit['numero'].' - '.$edit['bairro'].', '.$edit['cidade'].', '.$edit['cep']);
					//$endereco = str_replace(' ','+',$endereco);
//					$geocode=file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$endereco.'&key=AIzaSyBtDxyR6NFaIlRXs6hO8adZyNNqLLfhSB0');
//
//					$output= json_decode($geocode);
//					$latitude = $output->results[0]->geometry->location->lat;
//					$longitude = $output->results[0]->geometry->location->lng;
//					 
//					$edit['latitude'] = $latitude;
//					$edit['longitude'] = $longitude;
				
					$geo=geo($endereco);
					$edit['latitude'] = $geo[0];
					$edit['longitude'] = $geo[1];
				
					if(empty($edit['latitude'] )){
						$edit['latitude']   	= strip_tags(trim(mysql_real_escape_string($_POST['latitude'])));
						$edit['longitude']   	= strip_tags(trim(mysql_real_escape_string($_POST['longitude'])));
					}
				
					update('cliente',$edit,"id = '$clienteId'");
					header("Location: ".$_SESSION['url']);
				}
		}
	
	 	if(isset($_POST['deletar'])){
			$readDeleta = read('cliente',"WHERE id = '$clienteId'");
			if(!$readDeleta){
				echo '<div class="alert alert-warning">Desculpe, o registro não existe</div><br />';	
			 }else{
				delete('cliente',"id = '$clienteId'");
				header("Location: ".$_SESSION['url']);
			}
		}
			
		if(isset($_POST['cadastrar'])){
			$edit['tipo']		= strip_tags(trim(mysql_real_escape_string($_POST['tipo'])));
			$edit['classificacao']		= strip_tags(trim(mysql_real_escape_string($_POST['classificacao'])));
			$edit['email']		= strip_tags(trim(mysql_real_escape_string($_POST['email'])));
			$edit['email_financeiro']		= strip_tags(trim(mysql_real_escape_string($_POST['email_financeiro'])));
			$edit['senha']		= strip_tags(trim(mysql_real_escape_string($_POST['senha'])));
			$edit['nome'] 		= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			$edit['nome_fantasia'] 		= strip_tags(trim(mysql_real_escape_string($_POST['nome_fantasia'])));
			$edit['endereco']	= strip_tags(trim(mysql_real_escape_string($_POST['endereco'])));
			$edit['numero'] 		= strip_tags(trim(mysql_real_escape_string($_POST['numero'])));
			$edit['complemento']	= strip_tags(trim(mysql_real_escape_string($_POST['complemento'])));
			$edit['bairro']		= strip_tags(trim(mysql_real_escape_string($_POST['bairro'])));
			$edit['cep'] 		= strip_tags(trim(mysql_real_escape_string($_POST['cep'])));
			$edit['cidade']  	= strip_tags(trim(mysql_real_escape_string($_POST['cidade'])));
			$edit['uf']  		= strip_tags(trim(mysql_real_escape_string($_POST['uf'])));
			$edit['referencia']  		= strip_tags(trim(mysql_real_escape_string($_POST['referencia'])));
			$edit['celular'] 	= strip_tags(trim(mysql_real_escape_string($_POST['celular'])));
			$edit['telefone'] 	= strip_tags(trim(mysql_real_escape_string($_POST['telefone'])));
			$edit['contato']	= strip_tags(trim(mysql_real_escape_string($_POST['contato'])));
			$edit['cnpj'] 	= strip_tags(trim(mysql_real_escape_string($_POST['cnpj'])));
			$edit['inscricao'] 	= strip_tags(trim(mysql_real_escape_string($_POST['inscricao'])));
			$edit['cpf']   	= strip_tags(trim(mysql_real_escape_string($_POST['cpf'])));
			$edit['nao_enviar_email'] 	= strip_tags(trim(mysql_real_escape_string($_POST['nao_enviar_email'])));
			$edit['data']= date('Y/m/d H:i:s');
			
			if(empty($edit['nome'])){
				echo '<div class="alert alert-warning">O Nome do cliente é obrigatório!</div>'.'<br>';
			 }elseif(empty($edit['referencia'])){
				echo '<div class="alert alert-warning">A Referencia do cliente é obrigatório!</div>'.'<br>';	
			 }elseif(!email($edit['email'])){
				echo '<div class="alert alert-warning">Desculpe o e-mail informado é inválido!</div>'.'<br>';	
			 }elseif(!empty($edit['cnpj']) && !cnpj($edit['cnpj']) ){
					echo '<div class="alert alert-warning">Desculpe o CNPJ informado é inválido!</div>'.'<br>';
			 }elseif(!empty($edit['cpf']) && !cpf($edit['cpf']) ){
					echo '<div class="alert alert-warning">Desculpe o CPF informado é inválido!</div>'.'<br>';
				}elseif(empty($edit['cpf']) && empty($edit['cnpj']) ){
					echo '<div class="alert alert-warning">Desculpe o CNPJ/CPF precisa ser preenchido!</div>'.'<br>';
				
				}elseif(empty($edit['cep']) ){
					echo '<div class="alert alert-warning">Desculpe o CNPJ/CPF precisa ser preenchido!</div>'.'<br>';
				
				 }else{
				
					// pegar latituto e longitude atualizado em 04/08/2017
					$endereco = url($edit['endereco'].', '.$edit['numero'].' - '.$edit['bairro'].', '.$edit['cidade'].', '.$edit['cep']);
					 
					$geo=geo($endereco);
					$edit['latitude'] = $geo[0];
					$edit['longitude'] = $geo[1];
				
					$edit['email_financeiro2']		= strip_tags(trim(mysql_real_escape_string($_POST['email_financeiro2'])));
					
					$edit['senha']   	= strip_tags(trim(mysql_real_escape_string($_POST['senha']))); 
					 
				    echo '<div class="alert alert-warning">Cadastrado</div>'.'<br>';
					 
					create('cliente',$edit);	
					$clienteEdit = mysql_insert_id();
					unset($edit);
					header("Location: ".$_SESSION['url']);
		
			}
		};	
		
	?>

    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
            
             <div class="form-group col-xs-12 col-md-1">  
                 <label>Id</label>
                  <input name="id"  class="form-control" type="text" value="<?php echo $edit['id'];?>" readonly />  
             </div>
             <div class="form-group col-xs-12 col-md-3">  
                 <label>Data do Cadastro</label>
                  <input name="id"  class="form-control" type="text" value="<?php echo $edit['data'];?>" readonly />  
            </div>
            <div class="form-group col-xs-12 col-md-8">  
                 <label>Restrição</label>
                  <input name="restricao"  class="form-control" type="text" value="<?php echo $edit['restricao'];?>" readonly />  
            </div>
            <div class="form-group col-xs-12 col-md-6">  
                 <label>Nome</label>
                  <input name="nome"  class="form-control" type="text" value="<?php echo $edit['nome'];?>" readonly />  
            </div>
            <div class="form-group col-xs-12 col-md-6">  
                 <label>Nome Fantasia</label>
                  <input name="nome_fantasia"  class="form-control" type="text" value="<?php echo $edit['nome_fantasia'];?>" readonly />  
            </div>
            
            <div class="form-group col-xs-12 col-md-4">  
                 <label>Email</label>
                  <input name="email"  class="form-control" type="text" value="<?php echo $edit['email'];?>" readonly />  
            </div>
            
            <div class="form-group col-xs-12 col-md-4">  
                 <label>Envio de Email</label>
                 <select name="nao_enviar_email" class="form-control" readonly >
                  <option value="">Selecione</option>
                  <option <?php if($edit['nao_enviar_email'] == '1') echo' selected="selected"';?> value="1">Não Enviar Email </option>
                  <option <?php if($edit['nao_enviar_email'] == '0') echo' selected="selected"';?> value="0">Sem Restrição</option>
                 </select>
           </div>
           <div class="form-group col-xs-12 col-md-4">  
              <label>Selecione um Tipo </label>
           	  <select name="tipo" class="form-control" readonly > 
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
                <input name="cpf" type="text"  value="<?php echo $edit['cpf'];?>"  class="form-control" OnKeyPress="formatar('###.###.###-##', this)" readonly />  
            </div>
            <div class="form-group col-xs-12 col-md-4">  
                <label>CNPJ </label>
                <input type="text" name="cnpj" value="<?php echo $edit['cnpj'];?>"   class="form-control" OnKeyPress="formatar('##.###.###/####-##', this)" readonly />
           </div>
           <div class="form-group col-xs-12 col-md-4">  
                <label>Inscrição</label>
                <input type="text" name="inscricao"  value="<?php echo $edit['inscricao'];?>" class="form-control" readonly />  
           </div>
           
           <div class="form-group col-xs-12 col-md-4">  
                <label>Celular </label>
                <input type="text" name="celular" id="celular" value="<?php if($edit['celular']) echo $edit['celular'];?>"  class="form-control" readonly />  
           </div>
           
           <div class="form-group col-xs-12 col-md-4">  
                <label>Fixo</label>
                <input type="text" name="telefone" id="telefone" value="<?php echo $edit['telefone'];?>"  class="form-control"  readonly />  
           </div>       
           <div class="form-group col-xs-12 col-md-4">  
                    <label>Contato </label>
                    <input type="text" name="contato" value="<?php echo $edit['contato'];?>"   class="form-control" readonly />    
           </div>
     		<div class="form-group col-xs-12 col-md-2">
                    <label>CEP </label>
                <input name="cep" id="cep" value="<?php echo $edit['cep'];?>"  class="form-control" readonly />    
           </div>
			<div class="form-group col-xs-12 col-md-6">   
                <label>Endereço</label>
                <input name="endereco" id="endereco" value="<?php echo $edit['endereco'];?>" class="form-control" readonly />    
            </div>
           <div class="form-group col-xs-12 col-md-2">   
                <label>Numero </label>
                <input name="numero"  value="<?php echo $edit['numero'];?>" class="form-control" readonly />  
            </div> 
             <div class="form-group col-xs-12 col-md-2">   
               <label>Complemento </label>
                <input name="complemento"  value="<?php echo $edit['complemento'];?>" class="form-control" readonly />    
            </div>
            
      		<div class="form-group col-xs-12 col-md-3">   
                <label>Bairro</label>
                <input name="bairro" id="bairro" value="<?php echo $edit['bairro'];?>" class="form-control"  readonly />             					
            </div> 
            
           <div class="form-group col-xs-12 col-md-2">   
                <label>Cidade </label>
                <input name="cidade" id="cidade" value="<?php echo $edit['cidade'];?>" class="form-control" readonly />    
           </div>
           
            <div class="form-group col-xs-12 col-md-1">   
                <label>UF </label>
                <input name="uf" id="uf" value="<?php echo $edit['uf'];?>"  class="form-control"  readonly />  
            </div>

           <div class="form-group col-xs-12 col-md-6">   
                <label>Referencia </label>
                <input name="referencia" value="<?php echo $edit['referencia'];?>" class="form-control"  readonly/>  
           </div>
           		 

          <div class="form-group col-xs-12 col-md-6">   
                <label>Email Financeiro </label>
                <input type="email" name="email_financeiro" value="<?php echo $edit['email_financeiro'];?>" class="form-control" readonly />  
          </div>
            
            <div class="form-group col-xs-12 col-md-6">   
                <label>Email Financeiro (2) </label>
                <input type="email" name="email_financeiro2" value="<?php echo $edit['email_financeiro2'];?>" class="form-control" readonly />  
          </div>
             
    	  <div class="form-group col-xs-12 col-md-2">   
                <label>Senha </label>
                <input type="password" name="senha" title="<?php echo $edit['senha'];?>" value="<?php echo $edit['senha'];?>" class="form-control" readonly />  
          </div>
              
               <div class="form-group col-xs-12 col-md-4">   
             <label>Selecione um Classificação</label>
                  <select name="classificacao" class="form-control"  readonly/>   
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
			  
			  <div class="form-group col-xs-12 col-md-2">   
                <label>Latitude </label>
                <input type="text" name="latitude" value="<?php echo $edit['latitude'];?>" class="form-control"  readonly />  
          </div>
			  
			  <div class="form-group col-xs-12 col-md-2">   
                <label>Longitude </label>
                <input type="text" name="longitude" value="<?php echo $edit['longitude'];?>" class="form-control" readonly />  
          </div>
            
         	<div class="form-group col-xs-12 col-md-12">  
                <div class="box-footer">
                  <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>
                
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
			
				//// pegar latituto e longitude atualizado em 04/08/2017
		
 //https://maps.googleapis.com/maps/api/geocode/json?address=1600+Amphitheatre+Parkway,+Mountain+View,+CA&key=YOUR_API_KEY				
				
// $geocode=file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=Rua Machado de Assis,74 - Flamengo, Rio de Janeiro - RJ, 22220-060, Brasil&key=AIzaSyBtDxyR6NFaIlRXs6hO8adZyNNqLLfhSB0');	

				   // pegar latituto e longitude atualizado em 04/08/2017
					$endereco = url($edit['endereco'].', '.$edit['numero'].' - '.$edit['bairro'].', '.$edit['cidade'].', '.$edit['cep']);
					 
					  $endereco = str_replace(' ','+',$endereco);
					  $geocode=file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$endereco.'&key=AIzaSyBtDxyR6NFaIlRXs6hO8adZyNNqLLfhSB0');

					  $output= json_decode($geocode);
					  $latitude = $output->results[0]->geometry->location->lat;
					  $longitude = $output->results[0]->geometry->location->lng;
	
					  echo "latitude - ".$latitude .'<br>';
					  echo "longitude - ".$longitude;
       	
                  	echo '<p align="center">'.$edit['nome'].', '.$edit['telefone'].' / '.$edit['contato'].'</p>';
					echo '<p align="center">'.$edit['endereco'].', '.$edit['numero'].'  '.$edit['complemento'].
				' - '.$edit['bairro'].' - '.$edit['cidade'].' - '.$edit['cep'].'</p>';
				
				$address = url($edit['endereco'].', '.$edit['numero'].' - '.$edit['bairro'].' - '.$edit['cidade'].' - '.$edit['cep'])
				
           		?>
           		
         		<iframe width='100%' height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" zomm="1" src="https://maps.google.com.br/maps?q=<?php echo $address; ?>&output=embed">
         		</iframe>
         		

         		
           	</div><!-- /.box-header -->
    	</div><!-- /.box  -->
     </div><!-- /.box  -->
   </div>
</section><!-- /.content -->
  
</div><!-- /.content-wrapper -->

 