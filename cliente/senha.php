<?php 
	ob_start();
	session_start();
	require_once('../config/crud.php');
	require_once('../config/funcoes.php');
?>

<head>
    <meta charset="iso-8859-1">
</head>
    

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Suporte ao Cliente</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php require_once("../admin/inc/funcoes.php");?>
  </head>
  
  <body class="hold-transition login-page">
    <div class="login-box">
      <div class="register-logo">
         <a href="index.php">
        <img src="../site/images/header-logo.png" alt="logo" title="painel logo" />
         </a>
      </div>
       <div class="register-logo">
        <a href="index.php">Suporte ao Cliente</a>
      </div>
      <div class="login-box-body">
         <p class="login-box-msg">Digite o email cadastro em nosso sistema e depois clique no bot&atilde;o "Enviar". Sua senha ser&aacute; enviada automaticamente</p>
            
        
        <?php 
			if(isset($_POST['senha'])){
				$email = mysql_real_escape_string($_POST['email']);
				if(!$email || !email($email)){
					echo '<div class="alert alert-warning">Desculpe o e-mail � invalido !</div><br />';	
				  }else{
					$leitura = read('cliente',"WHERE email = '$email'");
					if($leitura){
						foreach($leitura as $cliente);	
						$assunto  = "Recuperacao de Senha : " . $cliente['nome'];
			   			$msg = "<font size='2 px' face='Verdana, Geneva, sans-serif' color='#000099'>";
						$msg .= "Cliente : " . $cliente['nome'] . "<br />";
						$msg .= "E-mail : " . $cliente['email'] . "<br />";
						$msg .= "Senha : " . $cliente['senha'] . "<br />";
						$msg .=  "</font>";
						enviaEmail($assunto,$msg,MAILUSER,SITENOME,$cliente['email'], $cliente['nome']);
						echo '<div class="alert alert-success">Sua senha foi enviada com sucesso!</div>';
					  }else{
						echo '<div class="alert alert-warning">O e-mail N�o Cadastrado!</div>';
					}
					
				}

			}
		
		?>
        

        <form name="senha" action="" method="post">
        	<legend>Recupera��o de Senha</legend>
          <div class="form-group has-feedback">
           <input type="email" name="email" class="form-control" placeholder="Email" value="<?php if($email) echo $email;?>">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
           <div class="row">
            
           <div class="form-group col-xs-12 col-md-4">
                <a href="index.php"><input type="button" value="Voltar" class="btn btn-warning btn-block btn-flat"></a>
            </div><!-- /.col -->
            
             <div class="form-group col-xs-12 col-md-4">
              <input type="submit" value="Enviar" name="senha" class="btn btn-primary btn-block btn-flat" />
            </div><!-- /.col -->
          </div>
        </form>
  
        
      <div class="row">

      <!--  <div class="social-auth-links text-center">
               <a href="cliente-novo.php" class="btn btn-primary btn-block btn-flat">Ainda N�o Sou Cadastrado</a><br><br>

        </div>-->


      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

     
  </body>
  
<?php 
	ob_end_flush();
?>
</html>
