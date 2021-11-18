<?php 

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				echo '<h1>Erro, você não tem permissão para acessar essa página!</h1>';	
				header('Location: painel.php');		
			}	
		}
		
		$acao = "cadastrar";
		if(!empty($_GET['emailPromocao'])){
		   $emailId = $_GET['emailPromocao'];
		   $acao = "enviar";
		}
		
		if(!empty($_GET['emailEditar'])){
		   $emailId = $_GET['emailEditar'];
		   $acao = "atualizar";
		}
 		
		if(!empty($emailId)){
			$read=read('email',"WHERE id = '$emailId'");
			if(!$read){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($read as $edit);
		}
		

		
 ?>

<div class="content-wrapper">
  <section class="content-header">
          <h1>Email</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Cadastro</a></li>
            <li><a href="painel.php?execute=suporte/email/emails">Email</a></li>
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

		if(isset($_POST['deletar'])){
				$readDeleta = read('email',"WHERE id = '$emailId'");
				if(!$readDeleta){
					echo '<div class="alert alert-warning">Desculpe, o registro não existe</div><br />';	
				}else{
					delete('email',"id = '$emailId'");
					header("Location: ".$_SESSION['url']);
				}
		}
	
	   if(isset($_POST['atualizar'])){
			$cad2['email'] = strip_tags(trim(mysql_real_escape_string($_POST['email'])));
			$cad2['email'] = strtolower($cad2['email']);
			$cad2['status'] = '';
			if(!email($cad2['email'])){
				echo '<div class="alert alert-warning">Desculpe o e-mail informado é inválido!</div>'.'<br>';
			}else{
				$id = $edit['id'];
				update('email',$cad2,"id = '$emailId'");
				unset($cad);
				header("Location: ".$_SESSION['url']);
			}
		}
		
		 if(isset($_POST['cadastrar'])){
			$cad2['email'] = strip_tags(trim(mysql_real_escape_string($_POST['email'])));
			$cad2['email'] = strtolower($cad2['email']);
			$cad2['nome'] = mysql_real_escape_string($_POST['nome']);	
            $cad2['email'] = mysql_real_escape_string($_POST['email']);	
            $cad2['telefone'] = mysql_real_escape_string($_POST['telefone']);
            $cad2['endereco'] = mysql_real_escape_string($_POST['endereco']);
            $cad2['bairro'] = mysql_real_escape_string($_POST['bairro']);
			$cad2['cep'] = mysql_real_escape_string($_POST['cep']);
			$cad2['contato'] = mysql_real_escape_string($_POST['contato']);
			create('email',$cad2);
			unset($cad);
			header("Location: ".$_SESSION['url']);
		}
		
		 if(isset($_POST['orcamento'])){
			$cli['tipo'] =1;
			$cli['classificacao'] =1;
			$ser['indicacao'] =1;
			$ser['consultor'] =1;
			$ser['atendente'] =1;
			$cli['senha'] ='123456';
            $cli['nome'] = mysql_real_escape_string($_POST['nome']);	
            $cli['email'] = mysql_real_escape_string($_POST['email']);	
            $cli['telefone'] = mysql_real_escape_string($_POST['telefone']);
            $cli['endereco'] = mysql_real_escape_string($_POST['endereco']);
            $cli['bairro'] = mysql_real_escape_string($_POST['bairro']);
			$cli['cep'] = mysql_real_escape_string($_POST['cep']);
			$cli['data']= date('Y/m/d H:i:s');
					$cli['contato'] = mysql_real_escape_string($_POST['contato']);	
					$cli['uf'] = 'RJ';
					create('cliente',$cli);	
					$ser['id_cliente'] = mysql_insert_id();
					$ser['interacao']= date('Y/m/d H:i:s');
					$ser['orc_data']= date('Y/m/d H:i:s');
					$ser['status'] =1;
					$ser['tipo'] =1;
					create('servico',$ser);
		   header('Location: painel.php?execute=suporte/orcamento/solicitacoes');
		}
		
		
	if(isset($_POST['enviar'])){
		
		$email_mkt = mostra('email_mkt',"WHERE id");
		if(!$email_mkt){
			header('Location: painel.php?execute=suporte/error');	
		}
		foreach($email_mkt as $email_mkt);
		
		//$link1="https://www.cleanambiental.com.br/.com.br/orcamento-dedetizacao";
		$link2="https://www.cleanambiental.com.br/admin/painel2.php?execute=suporte/email/email-cancelar&clienteId=".$edit['id'];
		$assunto = $email_mkt['titulo'];
		$msg = "<font size='2px' face='Verdana, Geneva, sans-serif' color='#444'>";
		$msg .="<img src='https://www.cleanambiental.com.br/wpc/site/images/header-logo.png'> <br />";
		$msg .= stripslashes($email_mkt['descricao']).  "<br /><br />";
		$msg .= "<a href=" . $link1 . ">ORÇAMENTO GRÁTIS.</a><br /> ";
		$msg .= "<a href=" . $link2 . ">Cancele o recebimento de novos e-mails, clicando aqui.</a> ";
		$msg .=  "</font>";
		
 		$edit['email']='wpcsistema@hotmail.com';
 		enviaEmail($assunto,$msg,MAILUSER,SITENOME,$edit['email'],$edit['nome']);
		
		$edit['email']='wellington@toyamadedetizadora.com.br';
		enviaEmail($assunto,$msg,MAILUSER,SITENOME,$edit['email'],$edit['nome']);
		
		$edit['email']='suporte@wpcsistema.com.br';
		enviaEmail($assunto,$msg,MAILUSER,SITENOME,$edit['email'],$edit['nome']);
		
		$edit['email']='vls2020leal@outlook.com';
		enviaEmail($assunto,$msg,MAILUSER,SITENOME,$edit['email'],$edit['nome']);
		
		$cad['data']= date('Y/m/d H:i:s');
 		update('email',$cad,"id = '$emailId'");	
		unset($cad);
				
	    //header("Location: ".$_SESSION['url']);
 
		
		echo "Host => ". MAILHOST ."<br>";
		echo "Email => ". MAILUSER ."<br>";
		echo "Senha => ". MAILPASS ."<br>";
		echo "Porta => ". MAILPORT ."<br>";
		
		$mailReturn = enviaEmail($assunto,$msg,MAILUSER,SITENOME,$edit['email'],$edit['nome']);
		echo "O mail foi enviado? => "; var_dump($mailReturn);
	}
	

	?>
    
	<form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
  			<div class="form-group col-xs-12 col-md-2"> 
               <label>Id</label>
               <input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled />
            </div> 
            
            <div class="form-group col-xs-12 col-md-2"> 
               <label>Interaçao</label>
               <input name="data" type="text" value="<?php echo $edit['data'];?>" class="form-control" disabled />
            </div> 
   
      		<div class="form-group col-xs-12 col-md-8"> 
       		   <label>Email</label>
               <input type="text" name="email" value="<?php echo $edit['email'];?>"  class="form-control"/>
           </div> 
           
           <div class="form-group col-xs-12 col-md-12"> 
       		   <label>Nome</label>
               <input type="text" name="nome" value="<?php echo $edit['nome'];?>"  class="form-control"/>
           </div> 
           
           <div class="form-group col-xs-12 col-md-6"> 
       		   <label>Endereço</label>
               <input type="text" name="endereco" value="<?php echo $edit['endereco'];?>"  class="form-control"/>
           </div> 
           
            <div class="form-group col-xs-12 col-md-4"> 
       		   <label>Bairro</label>
               <input type="text" name="bairro" value="<?php echo $edit['bairro'];?>"  class="form-control"/>
           </div> 
           
           <div class="form-group col-xs-12 col-md-2"> 
       		   <label>Cep</label>
               <input type="text" name="cep" value="<?php echo $edit['cep'];?>"  class="form-control"/>
           </div> 
           
            <div class="form-group col-xs-12 col-md-6"> 
       		   <label>Telefone</label>
               <input type="text" name="telefone" value="<?php echo $edit['telefone'];?>"  class="form-control"/>
           </div> 
           
           <div class="form-group col-xs-12 col-md-6"> 
       		   <label>Contato</label>
               <input type="text" name="contato" value="<?php echo $edit['contato'];?>"  class="form-control"/>
           </div> 
           
     
      	 <div class="box-footer">
         <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>
           	  <?php 
				 if($acao=="atualizar"){
                    echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';
					 echo '<input type="submit" name="enviar" value="Enviar" class="btn btn-primary" />';	
                    echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-danger" />';	
                }
                if($acao=="cadastrar"){
                    echo '<input type="submit" name="cadastrar" value="Cadastrar" class="btn btn-primary" />';	
                }
              
			  echo '<br>';
			  
             ?>  
          </div> 
		</form>
  	</div><!-- /.box-body -->
    </div><!-- /.box box-default -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->